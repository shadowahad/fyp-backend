<?php

namespace App\Imports;

use App\Models\Analytic;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\File as ModelsFile;
use Carbon\Carbon;

class AnalyticImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // return "hi";
        // dd($row);

        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 720);

        $file = ModelsFile::latest()->first();

        return new Analytic([
            // "uniq_id" => $row["uniq_id"],
            // "product_name" => $row["product_name"],
            // "manufacturer" => $row["manufacturer"],
            // "price" => $row["price"],
            // "number_available_in_stock" => $row["number_available_in_stock"],
            // "number_of_reviews" => $row["number_of_reviews"],
            // "number_of_answered_questions" => $row["number_of_answered_questions"],
            // "average_review_rating" => $row["average_review_rating"],
            // "amazon_category_and_sub_category" => $row["amazon_category_and_sub_category"],
            // "customers_who_bought_this_item_also_bought" => $row["customers_who_bought_this_item_also_bought"],
            // "description" => $row["description"],
            // "product_information" => $row["product_information"],
            // "product_description" => $row["product_description"],
            // "items_customers_buy_after_viewing_this_item" => $row["items_customers_buy_after_viewing_this_item"],
            // "customer_questions_and_answers" => $row["customer_questions_and_answers"],
            // "customer_reviews" => $row["customer_reviews"],
            // "sellers" => $row["sellers"],

            'product_name' => $row["product_name"],
            'manufacturer' => $row["manufacturer"], //optional
            'price' => str_replace("Â£", "", $row["price"]), //to filter as number
            'number_available_in_stock' => str_replace("Â new", "", $row["number_available_in_stock"]),
            'number_of_reviews' => $row["number_of_reviews"], //to filter as number
            'average_review_rating' => $row["average_review_rating"], //to filter as number
            // 'entry_date' => $row["entry_date"], //to filter as number
            'entry_date' => Carbon::parse(Carbon::today()->subDays(rand(1, 3))),
            "file_id" => $file->id,
            'sellingPrice' => $row["sellingprice"],
            'soldAmountProducts' => $row["soldamountproducts"],
            'old_cus' => $row["old_cus"],
            'new_cus' => $row["new_cus"],
            'pending_orders' => $row["pending_orders"],
            // // 'total_revenue'    => $row[1], // alsold * price (calculation)
        ]);
    }
}
