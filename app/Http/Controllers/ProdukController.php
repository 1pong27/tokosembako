<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Psr\Http\Message\ResponseInterface;

class ProdukController extends Controller
{
    public function __construct()

{
    $this->middleware('auth:api')->except(['index']);
}    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Produks = Produk::all();

        return response()->json([
            'data' => $Produks
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
            'id_kategori' => 'required',
            'id_subkategori' => 'required',
            'nama_barang' => 'required',
            'harga' => 'required',
            'diskon' => 'required',
            'bahan' => 'required',
            'tags' => 'required',
            'sku' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpg,png,jpeg,webp',
        ]);
        

        if($validator->fails()){
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();

        if ($request->has('gambar')){
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1,9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('uploads', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }

        $Produk = Produk::create($input);
        return response()->json([
            'data' => $Produk
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $Produk)
    {
        return response()->json([

            'data' => $Produk
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produk $Produk)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $Produk)
    {
        $validator = Validator::make($request->all(),[
            'id_kategori' => 'required',
            'id_subkategori' => 'required',
            'nama_barang' => 'required',
            'harga' => 'required',
            'diskon' => 'required',
            'bahan' => 'required',
            'tags' => 'required',
            'sku' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpg,png,jpeg,webp',
        ]);
        

        if($validator->fails()){
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();
        

        if ($request->has('gambar')){
            File::delete('uploads/' . $Produk->gambar);
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1,9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('uploads', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }else{
            unset($input['gambar']);
        }
        $Produk->update($input);

    return response()->json([
        'message' => 'success',
        'data' => $Produk
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $Produk)
    {
        File::delete('uploads/' . $Produk->gambar);
        $Produk->delete();

        return response()->json([
            'message'=>'success'
        ]);
    }
}
