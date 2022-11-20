<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class KeranjangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Keranjang::all();
        return response([
            'status' => 200,
            'data' => $product
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
            'id_produk' => 'required|integer',
            'jumlah' => 'required|integer',
        ], $message);

        if ($validate->fails()) {
            return response([
                'status' => 400,
                'message' => $validate->errors()
            ], 400);
        }

        $validate = $validate->validate();

        $total_harga = DB::table('products')->select('harga')->where('id', $validate['id_produk'])->first();
        $total_harga =  (int)$total_harga->harga * $validate['jumlah'];

        $validate += ['total_harga' => $total_harga];

        $keranjang = Keranjang::create($validate);
        if ($keranjang) {
            return response([
                'status' => 200,
                'message' => "data berhasil ditambahkan",
                'data' => $keranjang
            ], 200);
        }else {
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
     * @param  \App\Models\Keranjang  $keranjang
     * @return \Illuminate\Http\Response
     */
    public function show(Keranjang $keranjang)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Keranjang  $keranjang
     * @return \Illuminate\Http\Response
     */
    public function edit(Keranjang $keranjang)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Keranjang  $keranjang
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Keranjang $keranjang)
    {
        $data = $request->all();
        if ($request->jumlah) {
            $data += [
                'total_harga' => (int)$keranjang->product->harga * (int)$request->jumlah
            ];
        }
        $update = Keranjang::where('id', $keranjang->id)->update($data);
        if ($update) {
            return response([
                'status' => 200,
                'message' => 'data berhasil diubah',
            ], 200);
        }else{
            return response([
                'status' => 400,
                'message' => 'data gagal diubah',
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Keranjang  $keranjang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Keranjang $keranjang)
    {
        $delete = Keranjang::destroy($keranjang->id);
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
