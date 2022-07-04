<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Analytic;

class DashboardController extends Controller
{
    //
    public function dashboard(Request $request){
        $data = Analytic::whereIn('file_id', $request->value);
        return $data;
        
        //Total Customers
        $old = $data->sum('old_cus');
        $new = $data->sum('new_cus');
        $totalCus = $old + $new;

        $totalCustomers = [
            'total' => $totalCus,
            'old' => $old,
            'new' => $new,
        ];

        //Total Reviews
        $totalReviewsNumber = $data->sum('number_of_reviews');
        $anaData = $data->get();
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

        // TotalSold Amount Products
        $totalSoldAmountProducts = $data->sum('soldAmountProducts');

        // total stock
        $totalBuy = $data->sum('price');
        $totalSto = $data->sum('number_available_in_stock');
        $totalSt = $data->whereNotNull('number_available_in_stock')->select('number_available_in_stock', 'sellingPrice')->get();
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
        $totalPendingOrders = $data->sum('pending_orders');

        //top product name and vendor on bases of reviews and average reviws (comarison with most top 5 products)
        $topProducts = $data->orderBy('soldAmountProducts', 'DESC')->orderBy('average_review_rating', 'DESC')->orderBy('number_of_reviews', 'DESC')->limit(5)->select('manufacturer', 'product_name', 'sellingPrice', 'soldAmountProducts', 'number_available_in_stock')->get();
        
        //bottom product name and vendor on bases of reviews and average reviws (comarison with most top 5 products)
        $bottomProducts = $data->orderBy('number_available_in_stock', 'DESC')->orderBy('soldAmountProducts', 'ASC')->orderBy('average_review_rating', 'ASC')->orderBy('number_of_reviews', 'ASC')->limit(5)->select('manufacturer', 'product_name', 'sellingPrice', 'soldAmountProducts', 'number_available_in_stock')->get();
        
        $data = [
            'totalCustomers' => $totalCustomers,
            'totalReviews' => $totalReviews,
            'totalSoldAmountProducts' => $totalSoldAmountProducts,
            'totalPendingOrders' => $totalPendingOrders,
            'topProducts' => $topProducts,
            'bottomProducts' => $bottomProducts,
            'totalReveneue' => $totalReveneue
        ];
        return response()->json($data, 200);
    }
}
