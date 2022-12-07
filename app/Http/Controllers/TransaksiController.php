<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // show data
    public function index()
    {
        $data = Transaksi::all();
        return response([
            'status' => 200,
            'data' => $data
        ], 200);
    }

    // Add Data
    public function store(Request $request)
    {
        $produk_id = $request->produk_id;
        $payment_id = $request->payment_id;
        $user_id = auth()->user()->id;
        $harga = Produk::where('id', $produk_id)->value('harga');
        $qty = $request->qty;
        $total_price = $harga * $qty;

        $store = new Transaksi();
        $store->produk_id = $produk_id;
        $store->produk_name = Produk::where('id', $produk_id)->value('nama_produk');
        $store->user_id = $user_id;
        $store->user_name = User::where('id', $user_id)->value('name');
        $store->address = $request->address;
        $store->qty = $qty;
        $store->total_price = $total_price;
        $store->payment_id = $payment_id;
        $store->payment = Payment::where('id', $payment_id)->value('nama_pembayaran');
        $store->save();

        return response()->json([
            "message" => "Create Transaksi success",
            "data" => $store
        ], 200);
    }

    // show my trans
    public function show()
    {
        $user_id = auth()->user()->id;
        $user_name = User::where('id', $user_id)->value('name');

        $show = Transaksi::where('user_name', $user_name)->get();
        if($show){
            return response()->json([
                "message" => "Transaksi yang sedang berlangsung :",
                "data" => $show 
            ]);
        }else{
            return ["message" => "No Transaksi"];
        }
    }

    // update status
    public function update(Request $request, $id)
    {
        $update = Transaksi::where("id", $id)->update($request->all());
        
        // return $update;
         return response()->json([
            "message" => "Transaksi success",
            "data" => $update
        ], 200);

    }

    // update status
    public function destroy($id)
    {
        //
    }
}