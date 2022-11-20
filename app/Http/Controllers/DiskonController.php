<?php

namespace App\Http\Controllers;

use App\Models\Diskon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiskonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $diskon = Diskon::all();
        return response([
            'status' => 200,
            'data' => $diskon
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
            'name' => 'required',
            'deskripsi' => 'required',
            'potongan' => 'required|integer',
        ], $message);

        if ($validate->fails()) {
            return response([
                'status' => 400,
                'message' => $validate->errors()
            ], 400);
        }
        $diskon = Diskon::create($validate->validate());
        if ($diskon) {
            return response([
                'status' => 200,
                'message' => "data berhasil ditambahkan",
                'data' => $diskon
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
        $update = Diskon::where('id', $id)->update($request->all());
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
        $delete = Diskon::destroy($id);
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
