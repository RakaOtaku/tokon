<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Payment::all();
        return response()->json([
            "Berikut Payment yang Tersedia : ",
            $data
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
        $data = Payment::create([
            "nama_pembayaran" => $request->nama_pembayaran,
            "no_admin" => $request->no_admin
        ]);
        
        return response()->json([
            "Payment Berhasil Ditambah",
            $data
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $show = Product::where('nama_pembayaran', 'like', '%' . $id . '%')->get();
        if($show){
            return response()->json([
                "message" => " Payment : ",
                "data" => $show 
            ]);
        }else{
            return ["message" => "Data not found"];
        }
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
        $data = Payment::find($id);
        if($data){
            $data->nama_pembayaran = $request->nama_pembayaran ? $request->nama_pembayaran : $data->nama_pembayaran;
            $data->no_admin = $request->no_admin ? $request->no_admin : $data->no_admin;
            $data->save();

            return response()->json(["Data Berhasil Diubah", $data], 200);
        }else{
            return ["message" => "Data not found"];
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
        $data = Payment::find($id);
        if($data){
            $data->delete();
            return response()->json(["Payment Berhasil Dihapus", $data], 200);
        }else{
            return ["message" => "?"];
        }
    }
}
