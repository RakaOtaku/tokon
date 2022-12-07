<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Produk::all();
        return response()->json([
            "Berikut Produk yang Tersedia : ",
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
        //variabel gambar
        $gambar = Str::random(8).".".$request->gambar->getClientOriginalExtension();

        $data = Produk::create([
            "nama_produk" => $request->nama_produk,
            "deskripsi" => $request->deskripsi,
            "harga" => $request->harga,
            "stok" => $request->stok,
            "gambar" => $gambar
        ]);
        //menyimpan gambar ke storage laravel
        Storage::disk('public')->put($gambar, file_get_contents($request->gambar));
        
        return response()->json([
            "Produk Berhasil Ditambah",
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
        $show = Produk::where('nama_produk', 'like', '%' . $id . '%')->get();
        if($show){
            return response()->json([
                "Produk" => $show 
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
        $data = Produk::find($id);
        if($data){
            $data->nama_produk = $request->nama_produk ? $request->nama_produk : $data->nama_produk;
            $data->deskripsi = $request->deskripsi ? $request->deskripsi : $data->deskripsi;
            $data->harga = $request->harga ? $request->harga : $data->harga;
            $data->stok = $request->stok ? $request->stok : $data->stok;
            if($request->gambar) {
                // Public storage
                $storage = Storage::disk('public');
     
                // Old iamge delete
                if($storage->exists($data->gambar))
                    $storage->delete($data->gambar);
     
                // Image name
                $gambarName = Str::random(8).".".$request->gambar->getClientOriginalExtension();
                $data->gambar = $gambarName;
     
                // Image save in public folder
                $storage->put($gambarName, file_get_contents($request->gambar));
            }
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
        // Detail 
        $produk = Produk::find($id);
        if(!$produk){
          return response()->json([
             'message'=>'produk Not Found.'
          ],404);
        }
     
        // Public storage
        $storage = Storage::disk('public');
     
        // Iamge delete
        if($storage->exists($produk->gambar))
            $storage->delete($produk->gambar);
     
        // Delete produk
        $produk->delete();
     
        // Return Json Response
        return response()->json([
            'message' => "produk successfully deleted."
        ],200);
    }
}
