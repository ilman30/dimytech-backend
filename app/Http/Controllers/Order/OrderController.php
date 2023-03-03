<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;

class OrderController extends Controller
{
    public function create(Request $request)
    {

        $body = $request->only(
            'order_detail',
            'payment_method_id'
        );

        $validator = Validator::make($body, [
            'order_detail' => 'required',
            'payment_method_id' => 'required'
        ]);

        if ($validator->fails()) {
            $response['status'] = 0;
            $response['message'] = $validator->messages();
            $code = 400;
        } else {
            $params['id'] = Uuid::uuid4()->toString();
            $countDtl = count($body['order_detail']);
            $total = 0;
            for ($i = 0; $i < $countDtl; $i++) {
                $paramsDtl[$i]['order_id'] = $params['id'];
                $paramsDtl[$i]['product_id'] = $body['order_detail'][$i]['product_id'];
                $paramsDtl[$i]['price'] = Product::select('price')->where('id', $paramsDtl[$i]['product_id'])->get()->toArray()[0]['price'];
                $paramsDtl[$i]['qty'] = $body['order_detail'][$i]['qty'];
                $paramsDtl[$i]['subtotal'] = $paramsDtl[$i]['price'] * $paramsDtl[$i]['qty'];
                $paramsDtl[$i]['created_at'] = date('Y-m-d H:i:s');
                $paramsDtl[$i]['updated_at'] = date('Y-m-d H:i:s');
                $total += $paramsDtl[$i]['subtotal'];
            }
            $params['order_date'] = date('Y-m-d H:i:s');
            $params['order_detail'] = $paramsDtl;
            $params['total'] = $total;
            $params['customer'] = 'ilman';
            $params['payment_method_id'] = $body['payment_method_id'];
            $params['status'] = 0;

            DB::beginTransaction();
            // try {
                Order::create($params);
                if ($countDtl == 0) {
                    $response['status'] = 0;
                    $response['message'] = 'Please fill order detail!';
                    $code = 400;
                } else {
                    OrderDetail::insert($paramsDtl);
                    $response['status'] = 1;
                    $response['message'] = 'Order created!';
                    $response['order_id'] = $params['id'];
                    $response['payment_method'] = PaymentMethod::select('name')->where('id', $params['payment_method_id'])->get()->toArray()[0]['name'];
                    $code = 200;
                }
            // } catch (Exception $e) {
            //     $response['status'] = 0;
            //     $response['message'] = 'Server Error!';
            //     $code = 500;
            // }
        }

        if ($response['status'] == 1) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return response()->json($response, 200);
    }
}
