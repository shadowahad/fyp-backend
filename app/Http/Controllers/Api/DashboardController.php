<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Analytic;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    //
    public function dashboard(Request $request){
        //Total Customers
        $old = Analytic::whereIn('file_id', $request->value)->sum('old_cus');
        $new = Analytic::whereIn('file_id', $request->value)->sum('new_cus');
        $totalCus = $old + $new;

        $totalCustomers = [
            'total' => $totalCus,
            'old' => $old,
            'new' => $new,
        ];

        //Total Reviews
        $totalReviewsNumber = Analytic::whereIn('file_id', $request->value)->sum('number_of_reviews');
        $anaData = Analytic::whereIn('file_id', $request->value)->get();
        $positive = 0;
        $negative = 0;
        foreach ($anaData as $value) {
            if($value->number_of_reviews > 2.5){
                $positive = $positive+1;
            }else{
                $negative = $negative+1;
            }
        }
        $totalReviews = [
            'total' => $totalReviewsNumber,
            'positive' => $positive,
            'negative' => $negative,
        ];

        // total stock
        $totalBuy = Analytic::whereIn('file_id', $request->value)->sum('price');
        $totalSto = Analytic::whereIn('file_id', $request->value)->sum('number_available_in_stock');
        $totalSt = Analytic::whereIn('file_id', $request->value)->whereNotNull('number_available_in_stock')->select('number_available_in_stock', 'sellingPrice')->get();
        $totalRev = 0;
        foreach($totalSt as $val){
            $totalRev = $totalRev + ($val->number_available_in_stock * $val->sellingPrice);
        }
        $totalReveneue = [
            'totalReveneue' => $totalRev,
            'totalProducts' => $totalSto,
            'totalBuying' => $totalBuy,
        ];

        //total pending orders
        $totalPendingOrders = Analytic::whereIn('file_id', $request->value)->sum('pending_orders');

        //top product name and vendor on bases of reviews and average reviws (comarison with most top 5 products)
        $topProducts = Analytic::whereIn('file_id', $request->value)->orderBy('soldAmountProducts', 'DESC')->orderBy('average_review_rating', 'DESC')->orderBy('number_of_reviews', 'DESC')->limit(5)->select('manufacturer', 'product_name', 'sellingPrice', 'soldAmountProducts', 'number_available_in_stock')->get();
        
        //bottom product name and vendor on bases of reviews and average reviws (comarison with most top 5 products)
        $bottomProducts = Analytic::whereIn('file_id', $request->value)->orderBy('number_available_in_stock', 'DESC')->orderBy('soldAmountProducts', 'ASC')->orderBy('average_review_rating', 'ASC')->orderBy('number_of_reviews', 'ASC')->limit(5)->select('manufacturer', 'product_name', 'sellingPrice', 'soldAmountProducts', 'number_available_in_stock')->get();
        
        //Customer Gained
        $totalCustomerGained = Analytic::whereIn('file_id', $request->value)->sum('new_cus');
        $graphCustomerGained = Analytic::whereIn('file_id', $request->value)->whereNotNull('new_cus')->orderBy('created_at', 'DESC')->limit(10)->get()->pluck('new_cus');

        //Review Gained
        $totalReviewGained = Analytic::whereIn('file_id', $request->value)->sum('number_of_reviews');
        $graphReviewGained = Analytic::whereIn('file_id', $request->value)->whereNotNull('number_of_reviews')->orderBy('created_at', 'DESC')->limit(10)->get()->pluck('number_of_reviews');

        //Current Stock Count
        $totalCurrentStockCount = Analytic::whereIn('file_id', $request->value)->sum('number_available_in_stock');
        $graphCurrentStockCount = Analytic::whereIn('file_id', $request->value)->whereNotNull('number_available_in_stock')->orderBy('created_at', 'DESC')->limit(10)->get()->pluck('number_available_in_stock');

        // TotalSold Amount Products
        $totalSoldAmountProducts = Analytic::whereIn('file_id', $request->value)->sum('soldAmountProducts');

        //Reveneue To Generated
        $reveneueToGenerated = $totalBuy-$totalSoldAmountProducts;

        //Reveneue Generated

        //Orderd Recived
        $totalOrderdRecived = Analytic::whereIn('file_id', $request->value)->sum('pending_orders');
        $graphOrderdRecived = Analytic::whereIn('file_id', $request->value)->whereNotNull('pending_orders')->orderBy('created_at', 'DESC')->limit(10)->get()->pluck('pending_orders');

        //Product Sold
        $totalProductSold = Analytic::whereIn('file_id', $request->value)->sum('soldAmountProducts');
        $graphProductSold = Analytic::whereIn('file_id', $request->value)->whereNotNull('soldAmountProducts')->orderBy('created_at', 'DESC')->limit(10)->get()->pluck('soldAmountProducts');

        // Customer Trend graph
        $old = Analytic::whereIn('file_id', $request->value)->orderBy('created_at', 'DESC')->limit(10)->get()->pluck('old_cus');
        $new = Analytic::whereIn('file_id', $request->value)->orderBy('created_at', 'DESC')->limit(10)->get()->pluck('new_cus');

        $data = [
            'totalCustomers' => $totalCustomers,
            'totalReviews' => $totalReviews,
            'totalPendingOrders' => $totalPendingOrders,
            'topProducts' => $topProducts,
            'bottomProducts' => $bottomProducts,
            'totalReveneue' => $totalReveneue,
            'customerGained' => [
                'totalCustomerGained' => $totalCustomerGained,
                'graphCustomerGained' => $graphCustomerGained
            ],
            'reviewGained' => [
                'totalReviewGained' => $totalReviewGained,
                'graphReviewGained' => $graphReviewGained
            ],
            'currentStockCount' => [
                'totalCurrentStockCount' => $totalCurrentStockCount,
                'graphCurrentStockCount' => $graphCurrentStockCount
            ],
            'reveneueToGenerated' => $reveneueToGenerated,
            'orderdRecived' => [
                'totalOrderdRecived' => $totalOrderdRecived,
                'graphOrderdRecived' => $graphOrderdRecived
            ],
            'productSold' => [
                'totalProductSold' => $totalProductSold,
                'graphProductSold' => $graphProductSold
            ],
            'customerTrendGraph' => [
                'old' => $old,
                'new' => $new
            ]
        ];
        return response()->json($data, 200);
    }
}
