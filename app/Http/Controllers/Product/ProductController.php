<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create(Request $request)
    {
    }

    public function read(Request $request)
    {
        $response = array();
        $name = $request->name;
        try {
            $query = PaymentMethod::select('id', 'name')
                ->where('name', 'like', '%' . $name . '%')
                ->where('is_active', 1)
                ->orderBy('id', 'ASC')
                ->get();
            $count = $query->count();
            if ($count == 0) {
                $response['status'] = 0;
                $response['message'] = 'Payment method not found!';
                $response['count'] = $count;
                $code = 200;
            } else {
                $response['status'] = 0;
                $response['message'] = 'Payment method found!';
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
