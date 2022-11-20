<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $order = Order::where('id_user', auth()->user()->id)->get();
        return response([
            'status' => 200,
            'data' => $order->load('product', 'user', 'diskon')
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute harus di isi',
            'integer' => ':attribute harus berisi integer'
        ];
        $validate = Validator::make($request->all(), [
            'id_user' => 'required|integer',
            'id_product' => 'required|integer',
            'jumlah' => 'required|integer',
            'payment' => 'required',
            'alamat' => 'required',
            'diskon_id' => 'required|integer',
        ], $message);
        if ($validate->fails()) {
            return response([
                'status' => 400,
                'message' => $validate->errors()
            ], 400);
        }

        $validate = $validate->validate();

        $harga = DB::table('products')->select('harga')->where('id', $validate['id_product'])->first();
        $diskon = DB::table('diskons')->select('potongan')->where('id', $validate['diskon_id'])->first();
        $diskon = (int)$harga->harga * (int)$diskon->potongan / 100;
        $total_harga =  (int)$harga->harga * (int)$validate['jumlah'] - $diskon;

        $validate += [
            'total_harga' => $total_harga
        ];

        $order = Order::create($validate);
        if ($order) {
            return response([
                'status' => 200,
                'message' => "data berhasil ditambahkan",
                'data' => $order
            ], 200);
        } else {
            return response([
                'status' => 400,
                'message' => "data gagal ditambahkan",
                'data' => null
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $data = $request->all();
        if ($request->jumlah) {
            $data += [
                'total_harga' => (int)$order->product->harga * (int)$request->jumlah
            ];
        }
        $update = Order::where('id', $order->id)->update($data);
        if ($update) {
            return response([
                'status' => 200,
                'message' => 'data berhasil diubah',
            ], 200);
        } else {
            return response([
                'status' => 400,
                'message' => 'data gagal diubah',
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Order::destroy($id);
        if($delete){
            return response([
                'status' => 200,
                'message' => 'data berhasil dihapus',
            ], 200);
        }else{
            return response([
                'status' => 400,
                'message' => 'data berhasil diubah',
            ], 400);
        }
    }
}
