<?php

namespace App\Http\Controllers\PaymentMethod;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function create(Request $request)
    {
    }

    public function read(Request $request)
    {
        $response = array();
        $product_name = $request->name;
        try {
            $query = PaymentMethod::select('id as product_id', 'name as product_name', 'price')
                ->where('name', 'like', '%' . $product_name . '%')
                ->orderBy('id', 'ASC')
                ->get();
            $count = $query->count();
            if ($count == 0) {
                $response['status'] = 0;
                $response['message'] = 'Products not found!';
                $response['count'] = $count;
                $code = 200;
            } else {
                $response['status'] = 0;
                $response['message'] = 'Products found!';
                $response['count'] = $count;
                $response['data'] = $query;
                $code = 200;
            }
        } catch (Exception $e) {
            $response['status'] = 0;
            $response['message'] = 'Server Error!';
            $code = 500;
        }
        return response()->json($response, $code);
    }

    public function update(Request $request)
    {
    }

    public function delete(Request $request)
    {
    }
}
