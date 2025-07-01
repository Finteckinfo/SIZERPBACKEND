<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Creators;
use App\Models\SaleofContent;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardDataController extends Controller
{
    public function getDashboardData(Request $request)
    {
        try {
            if (!$request->user() || !$request->user()->currentAccessToken()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // // Check if cached version exists
            if (Cache::has('dashboarddata')) {
                return response()->json(Cache::get('dashboarddata'));
            }

            // If cache is missing, fetch fresh data
            $freshData = $this->fetchDashboardData();

            // Queue the cache update (after the response to user)
            dispatch(function () use ($freshData) {
                Cache::put('dashboarddata', $freshData, now()->addMinutes(3));
            })->afterResponse();

            // Return fresh data immediately
            return response()->json($freshData);
        } catch (\Exception $e) {
            Log::error('Error occurred: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    protected function fetchDashboardData()
    {
        try {
            // Updated to use new field names
            $PaidPagesCreators = Creators::where('account_group', 'Default')->get();
            $FreePageCreators = Creators::where('account_group', 'FreePages')->get();

            $dailyEarningsData = $this->getDailyEarningsData();
            $monthlyEarningsData = $this->getMonthlyEarningsData();
            $revenueDistributionData = $this->getRevenueDistributionData();

            // Calculate total revenue from completed transactions
            $totalCreatorsRevenue = Transactions::where('status', 'completed')->sum('amount');

            // Updated to use Transactions instead of Earnings
            $currentMonthRevenue = Transactions::where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');

            $lastMonthRevenue = Transactions::where('status', 'completed')
                ->whereMonth('created_at', now()->subMonth()->month)
                ->whereYear('created_at', now()->subMonth()->year)
                ->sum('amount');

            // Best selling content from transactions items
            $bestSellingContent = Transactions::where('status', 'completed')
                ->whereNotNull('items')
                ->get()
                ->flatMap(function ($transaction) {
                    return collect($transaction->items)->pluck('name');
                })
                ->countBy()
                ->sortDesc()
                ->take(1)
                ->map(function ($count, $name) {
                    return (object) [
                        'content_purchased' => $name,
                        'total_sales' => $count
                    ];
                })
                ->first();

            // Total content sales from transactions
            $totalSaleofContent = Transactions::where('status', 'completed')
                ->whereIn('payment_type', ['content_purchase', 'sales'])
                ->sum('amount');

            $revenueChange = $lastMonthRevenue > 0
                ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100
                : 0;

            $creatorsAddedThisMonth = Creators::whereMonth('created_at', now()->month)->count();

            // Earnings by source analysis using Transactions
            $totalTips = Transactions::where('payment_type', 'tip')
                ->where('status', 'completed')
                ->sum('amount');

            $totalSubscription = Transactions::where('payment_type', 'subscription')
                ->where('status', 'completed')
                ->sum('amount');

            $totalSales = Transactions::whereIn('payment_type', ['sales', 'content_purchase'])
                ->where('status', 'completed')
                ->sum('amount');

            $totalReferrals = Transactions::where('payment_type', 'referrals')
                ->where('status', 'completed')
                ->sum('amount');

            // Find the biggest total
            $biggestEarning = max($totalTips, $totalSubscription, $totalSales, $totalReferrals);

            // Determine which source has the biggest earning
            $biggestSource = match ($biggestEarning) {
                $totalTips => 'tip',
                $totalSubscription => 'subscription',
                $totalSales => 'sales',
                $totalReferrals => 'referrals',
                default => ''
            };

            // Last month sources using Transactions
            $totalTipsLastmonth = Transactions::where('payment_type', 'tip')
                ->where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->sum('amount');

            $totalSubscriptionLastmonth = Transactions::where('payment_type', 'subscription')
                ->where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->sum('amount');

            $totalSalesLastmonth = Transactions::whereIn('payment_type', ['sales', 'content_purchase'])
                ->where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->sum('amount');

            $totalReferralsLastmonth = Transactions::where('payment_type', 'referrals')
                ->where('status', 'completed')
                ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                ->whereYear('created_at', Carbon::now()->subMonth()->year)
                ->sum('amount');

            $biggestEarningLastmonth = max($totalTipsLastmonth, $totalSubscriptionLastmonth, $totalSalesLastmonth, $totalReferralsLastmonth);

            $biggestSourceLastmonth = match ($biggestEarningLastmonth) {
                $totalTipsLastmonth => 'tip',
                $totalSubscriptionLastmonth => 'subscription',
                $totalSalesLastmonth => 'sales',
                $totalReferralsLastmonth => 'referrals',
                default => ''
            };

            $isSameBiggestSource = $biggestSourceLastmonth === $biggestSource;

            // Active creators (updated to use is_active field)
            $activeCreators = Creators::where('is_active', true)->count();
            $creatorsAllSubscriptions = Transactions::where('payment_type', 'subscriptions')->sum('amount');
            $creatorssubscriptionsThisMonth = Transactions::where('payment_type', 'subscriptions')
                ->whereMonth('created_at', Carbon::now()->month)
                ->sum('amount');

            $creators = Creators::select('id', 'email')
                ->withSum(['transactions as total_gross' => function ($query) {
                    $query->where('status', 'completed');
                }], 'amount')
                ->orderBy('total_gross', 'desc')
                ->limit(10)
                ->get();

            $topCreator = Creators::withSum(['transactions as total_gross' => function ($query) {
                $query->where('status', 'completed');
            }], 'amount')
                ->orderByDesc('total_gross')
                ->first(['id', 'email']);

            // Updated to use new field names and calculate total_gross from transactions
            $top5Creators = Creators::where('account_group', 'Default')
                ->withSum(['transactions as total_gross' => function ($query) {
                    $query->where('status', 'completed');
                }], 'amount')
                ->orderByDesc('total_gross')
                ->take(5)
                ->get(['id', 'email', 'created_at', 'content_type']);

            $creatorsStats = [];
            $paidCreatorsStats = [];

            foreach ($top5Creators as $creator) {
                $createdDate = new Carbon($creator->created_at);
                $monthsSinceCreation = $createdDate->diffInMonths(Carbon::now()) ?: 1;

                // Updated to use Transactions instead of Earnings
                $lastMonthEarnings = Transactions::where('creator_id', $creator->id)
                    ->where('status', 'completed')
                    ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->whereYear('created_at', Carbon::now()->subMonth()->year)
                    ->sum('amount');

                $currentMonthEarnings = Transactions::where('creator_id', $creator->id)
                    ->where('status', 'completed')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('amount');

                if ($monthsSinceCreation < 1) {
                    $monthlyAvgEarnings = $currentMonthEarnings;
                } else {
                    $monthlyAvgEarnings = $creator->total_gross / $monthsSinceCreation;
                }

                $growth = $lastMonthEarnings > 0
                    ? (($currentMonthEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100
                    : 0;

                $creatorsStats[] = [
                    'id' => $creator->id,
                    'email' => $creator->email,
                    'account_group' => 'Default',
                    'content_type' => $creator->content_type,
                    'total_gross' => $creator->total_gross,
                    'monthly_avg_earnings' => $monthlyAvgEarnings,
                    'growth_percentage' => $growth
                ];
            }

            foreach ($PaidPagesCreators as $paidcreator) {
                $createdDate = new Carbon($paidcreator->created_at);
                $monthsSinceCreation = $createdDate->diffInMonths(Carbon::now()) ?: 1;

                // Calculate total gross from completed transactions
                $totalGrossEarnings = Transactions::where('creator_id', $paidcreator->id)
                    ->where('status', 'completed')
                    ->sum('amount');

                $monthlyAvgEarnings = $totalGrossEarnings / $monthsSinceCreation;

                // Updated to use Transactions instead of Earnings
                $lastMonthEarnings = Transactions::where('creator_id', $paidcreator->id)
                    ->where('status', 'completed')
                    ->whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->whereYear('created_at', Carbon::now()->subMonth()->year)
                    ->sum('amount');

                $currentMonthEarnings = Transactions::where('creator_id', $paidcreator->id)
                    ->where('status', 'completed')
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->sum('amount');

                $growth = $lastMonthEarnings > 0
                    ? (($currentMonthEarnings - $lastMonthEarnings) / $lastMonthEarnings) * 100
                    : 0;

                $paidCreatorsStats[] = [
                    'id' => $paidcreator->id,
                    'email' => $paidcreator->email,
                    'account_group' => $paidcreator->account_group,
                    'content_type' => $paidcreator->content_type,
                    'total_gross' => $totalGrossEarnings,
                    'monthly_avg_earnings' => $monthlyAvgEarnings,
                    'growth_percentage' => $growth,
                ];
            }

            // Updated to use Transactions for content sales
            $saleofcontent = Transactions::whereIn('payment_type', ['content_purchase', 'sales'])
                ->where('status', 'completed')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount');

            // Updated trending creators based on transaction count
            $trendingCreators = Creators::withCount(['transactions as transaction_count' => function ($query) {
                $query->where('status', 'completed');
            }])
                ->orderByDesc('transaction_count')
                ->take(3)
                ->get(['id', 'email']);

            // Additional analytics for active vs inactive creators
            $activeCreatorsCount = Creators::where('is_active', true)->count();
            $inactiveCreatorsCount = Creators::where('is_active', false)->count();

            // Platform status analytics
            $onPlatformCreators = Creators::where('on_platform', true)->count();
            $offPlatformCreators = Creators::where('on_platform', false)->count();

            // Pay type analytics
            $directPayCreators = Creators::where('creator_type', 'Direct Pay')->count();
            $nonDirectPayCreators = Creators::where('creator_type', 'Non-Direct Pay')->count();

            // Archived creators
            $archivedCreators = Creators::where('archived', true)->count();
            $activeNonArchivedCreators = Creators::where('archived', false)->where('is_active', true)->count();

            return [
                'paidcreators' => $paidCreatorsStats,
                'top5creators' => $creatorsStats,
                'total_creators_revenue' => $totalCreatorsRevenue,
                'total_content_sales' => $totalSaleofContent,
                'revenue_change' => $revenueChange,
                'active_creators' => $activeCreators,
                'top_creator' => $topCreator ? [
                    'email' => $topCreator->email,
                    'earnings' => $topCreator->total_gross ?? 0
                ] : null,
                'content_sales_this_month' => $saleofcontent,
                'trending_creators' => $trendingCreators,
                'creators_added' => $creatorsAddedThisMonth,
                'biggest_earning' => $biggestEarning,
                'biggest_earningLastmonth' => $biggestEarningLastmonth,
                'biggest_source' => $biggestSource,
                'biggest_sourceLastmonth' => $biggestSourceLastmonth,
                'is_same_biggest_source' => $isSameBiggestSource,
                'best_selling_content' => $bestSellingContent ? [
                    'name' => $bestSellingContent->content_purchased,
                    'total_sales' => $bestSellingContent->total_sales,
                ] : null,
                'dailyEarningsData' => $dailyEarningsData,
                'monthlyEarningsData' => $monthlyEarningsData,
                'revenueDistributionData' => $revenueDistributionData,
                'statsData' => [
                    'totalEarnings' => $totalCreatorsRevenue,
                    'averageDailyRevenue' => round($totalCreatorsRevenue / 30, 2),
                    'subscriberCount' => $creatorsAllSubscriptions,
                    'subscriberCountThisMonth' => $creatorssubscriptionsThisMonth,
                    'engagementRate' => '78%'
                ],
                'creators' => $creators,

                // New analytics based on updated schema
                'creatorAnalytics' => [
                    'active_creators_count' => $activeCreatorsCount,
                    'inactive_creators_count' => $inactiveCreatorsCount,
                    'on_platform_creators' => $onPlatformCreators,
                    'off_platform_creators' => $offPlatformCreators,
                    'direct_pay_creators' => $directPayCreators,
                    'non_direct_pay_creators' => $nonDirectPayCreators,
                    'archived_creators' => $archivedCreators,
                    'active_non_archived_creators' => $activeNonArchivedCreators,
                    'paid_pages_count' => $PaidPagesCreators->count(),
                    'free_pages_count' => $FreePageCreators->count(),
                ],

                // Content type distribution
                'contentTypeDistribution' => $this->getContentTypeDistribution(),

                // Platform performance metrics
                'platformMetrics' => $this->getPlatformMetrics(),

                // Transaction analytics
                'transactionAnalytics' => [
                    'total_tips' => $totalTips,
                    'total_subscriptions' => $totalSubscription,
                    'total_sales' => $totalSales,
                    'total_referrals' => $totalReferrals,
                    'current_month_transactions' => Transactions::where('status', 'completed')
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->count(),
                    'last_month_transactions' => Transactions::where('status', 'completed')
                        ->whereMonth('created_at', now()->subMonth()->month)
                        ->whereYear('created_at', now()->subMonth()->year)
                        ->count(),
                ],
            ];
        } catch (\Exception $e) {
            Log::error('Dashboard error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
    private function getContentTypeDistribution()
    {
        // Get content type distribution with earnings in a single optimized query
        $contentTypes = DB::table('creators')
            ->select(
                'creators.content_type',
                DB::raw('COUNT(DISTINCT creators.id) as creator_count'),
                DB::raw('COALESCE(SUM(transactions.amount), 0) as total_earnings')
            )
            ->leftJoin('transactions', function ($join) {
                $join->on('creators.id', '=', 'transactions.creator_id')
                    ->where('transactions.status', '=', 'completed');
            })
            ->whereNotNull('creators.content_type')
            ->groupBy('creators.content_type')
            ->get();

        return $contentTypes->map(function ($contentType) {
            return [
                'name' => $contentType->content_type,
                'count' => (int) $contentType->creator_count,
                'total_earnings' => (float) $contentType->total_earnings,
                'avg_earnings' => $contentType->creator_count > 0
                    ? round($contentType->total_earnings / $contentType->creator_count, 2)
                    : 0
            ];
        })
            ->sortByDesc('total_earnings')
            ->values();
    }

    private function getPlatformMetrics()
    {
        // Calculate earnings for creators by platform status
        $onPlatformCreators = Creators::where('on_platform', true)->pluck('id');
        $offPlatformCreators = Creators::where('on_platform', false)->pluck('id');

        $onPlatformEarnings = Transactions::whereIn('creator_id', $onPlatformCreators)
            ->where('status', 'completed')
            ->sum('amount');

        $offPlatformEarnings = Transactions::whereIn('creator_id', $offPlatformCreators)
            ->where('status', 'completed')
            ->sum('amount');

        // Calculate earnings for creators by pay type
        $directPayCreators = Creators::where('creator_type', 'Direct Pay')->pluck('id');
        $nonDirectPayCreators = Creators::where('creator_type', 'Non-Direct Pay')->pluck('id');

        $directPayEarnings = Transactions::whereIn('creator_id', $directPayCreators)
            ->where('status', 'completed')
            ->sum('amount');

        $nonDirectPayEarnings = Transactions::whereIn('creator_id', $nonDirectPayCreators)
            ->where('status', 'completed')
            ->sum('amount');

        return [
            'platform_earnings' => [
                'on_platform' => $onPlatformEarnings,
                'off_platform' => $offPlatformEarnings,
            ],
            'creator_type_earnings' => [
                'direct_pay' => $directPayEarnings,
                'non_direct_pay' => $nonDirectPayEarnings,
            ],
            'performance_ratio' => [
                'on_platform_vs_off' => $offPlatformEarnings > 0 ? round($onPlatformEarnings / $offPlatformEarnings, 2) : 0,
                'direct_vs_non_direct' => $nonDirectPayEarnings > 0 ? round($directPayEarnings / $nonDirectPayEarnings, 2) : 0,
            ]
        ];
    }

    private function getDailyEarningsData()
    {
        $tips = Transactions::where('payment_type', 'tip')
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount');

        $subscriptions = Transactions::where('payment_type', 'subscription')
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount');

        $referrals = Transactions::where('payment_type', 'referrals')
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount');

        $posts = Transactions::where('payment_type', 'post')
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount');

        // Also include content sales
        $contentSales = Transactions::whereIn('payment_type', ['sales', 'content_purchase'])
            ->where('status', 'completed')
            ->whereDate('created_at', today())
            ->sum('amount');

        return [
            ['name' => 'Tips', 'value' => $tips],
            ['name' => 'Subscriptions', 'value' => $subscriptions],
            ['name' => 'Referrals', 'value' => $referrals],
            ['name' => 'Posts', 'value' => $posts],
            ['name' => 'Content Sales', 'value' => $contentSales]
        ];
    }

    private function getMonthlyEarningsData()
    {
        $tips = Transactions::where('payment_type', 'tip')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $subscriptions = Transactions::where('payment_type', 'subscription')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $referrals = Transactions::where('payment_type', 'referrals')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $posts = Transactions::where('payment_type', 'post')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        // Also include content sales
        $contentSales = Transactions::whereIn('payment_type', ['sales', 'content_purchase'])
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return [
            ['name' => 'Tips', 'value' => $tips],
            ['name' => 'Subscriptions', 'value' => $subscriptions],
            ['name' => 'Referrals', 'value' => $referrals],
            ['name' => 'Posts', 'value' => $posts],
            ['name' => 'Content Sales', 'value' => $contentSales]
        ];
    }

    private function getRevenueDistributionData()
    {
        $tips = Transactions::where('payment_type', 'tip')
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $subscriptions = Transactions::where('payment_type', 'subscription')
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $referrals = Transactions::where('payment_type', 'referrals')
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $posts = Transactions::where('payment_type', 'post')
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        $contentSales = Transactions::whereIn('payment_type', ['sales', 'content_purchase'])
            ->where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->sum('amount');

        return [
            ['name' => 'Tips', 'value' => $tips, 'color' => '#8884d8'],
            ['name' => 'Subscriptions', 'value' => $subscriptions, 'color' => '#82ca9d'],
            ['name' => 'Referrals', 'value' => $referrals, 'color' => '#ffc658'],
            ['name' => 'Posts', 'value' => $posts, 'color' => '#ff8042'],
            ['name' => 'Content Sales', 'value' => $contentSales, 'color' => '#00c49f']
        ];
    }
}
