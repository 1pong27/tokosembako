<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Psr\Http\Message\ResponseInterface;

class OrderController extends Controller
{
    public function __construct()

{
    $this->middleware('auth:api')->except(['index']);
}    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();

        return response()->json([
            'data' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'id_member' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();

        $Order = Order::create($input);

        for ($i=0; $i < count($input['id_produk']); $i++) { 
            OrderDetail::create([
                'id_order'=> $input ['id'],
                'jumlah'=> $input ['jumlah'][$i],
                'total'=> $input ['total'][$i],
                'id_produk'=> $input ['id_produk'][$i],
            ]);
        }
        return response()->json([
            'data' => $Order
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $Order)
    {
        return response()->json([

            'data' => $Order
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $Order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $Order)
    {
        $validator = Validator::make($request->all(),[
            'id_member  ' => 'required',
            
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();
        $Order->update($input);

        OrderDetail::where('id_order', $Order ['id'])->delete();

        for ($i=0; $i < count($input['id_produk']); $i++) { 
            OrderDetail::create([
                'id_order'=> $input ['id'],
                'jumlah'=> $input ['jumlah'][$i],
                'total'=> $input ['total'][$i],
                'id_produk'=> $input ['id_produk'][$i],
            ]);
        }

    return response()->json([
        'message' => 'success',
        'data' => $Order
    ]);
    }

    function ubah_status(Request $request, Order $order)  {
        $order->update([
            'status'=> $request->status
        ]);

        return response()->json([
            'message' => 'success',
            'data' => $order
        ]);
    }

    function dikonfirmasi(){
        $orders = Order::where('status', 'Dikonfirmasi')->get();

        return response()->json([
            'data' => $orders
        ]);
        
    }
    function dikemas(){
        $orders = Order::where('status', 'Dikemas')->get();

        return response()->json([
            'data' => $orders
        ]);
        
    }

    function dikirim(){
        $orders = Order::where('status', 'Dikirim')->get();

        return response()->json([
            'data' => $orders
        ]);
        
    }

    function diterima(){
        $orders = Order::where('status', 'Diterima')->get();

        return response()->json([
            'data' => $orders
        ]);
        
    }

    function selesai(){
        $orders = Order::where('status', 'Selesai')->get();

        return response()->json([
            'data' => $orders
        ]);
        
    }
    public function destroy(Order $Order)
    {
        File::delete('uploads/' . $Order->gambar);
        $Order->delete();

        return response()->json([
            'message'=>'success'
        ]);
    }
}
