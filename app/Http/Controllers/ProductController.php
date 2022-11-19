<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::with('user')->get();
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
            'user_id' => 'required|integer',
            'title' => 'required',
            'image' => 'required',
            'description' => 'required',
            'stock' => 'required|integer',
            'harga' => 'required',
            'merek' => 'required'
        ], $message);

        if ($validate->fails()) {
            return response([
                'status' => 400,
                'message' => $validate->errors()
            ]);
        }
        $validator = $validate->validate();
        $validator['image'] = $request->file('image')->store('image_product');
        $product = Product::create($validator);
        if ($product) {
            return response([
                'status' => 200,
                'message' => "data berhasil ditambahkan",
                'data' => $product
            ]);
        }else {
            return response([
                'status' => 400,
                'message' => "data gagal ditambahkan",
                'data' => null
            ]);
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
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->image != null) {
            $data['image'] = $request->file('image')->store('image_product');
        }
        $update = Product::where('id', $id)->update($data);
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = Product::destroy($id);
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
