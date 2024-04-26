<?php

namespace App\Http\Controllers;

use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Psr\Http\Message\ResponseInterface;

class SubcategoryController extends Controller
{
    public function __construct()

{
    $this->middleware('auth:api')->except(['index']);
}    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Subcategory::all();

        return response()->json([
            'data' => $categories
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
            'nama_subkategori' => 'required',
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

        $Subcategory = Subcategory::create($input);
        return response()->json([
            'data' => $Subcategory
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Subcategory $Subcategory)
    {
        return response()->json([

            'data' => $Subcategory
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subcategory $Subcategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subcategory $Subcategory)
    {
        $validator = Validator::make($request->all(),[
            'nama_subkategori' => 'required',
            'deskripsi' => 'required',
            
        ]);

        if($validator->fails()){
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();
        

        if ($request->has('gambar')){
            File::delete('uploads/' . $Subcategory->gambar);
            $gambar = $request->file('gambar');
            $nama_gambar = time() . rand(1,9) . '.' . $gambar->getClientOriginalExtension();
            $gambar->move('uploads', $nama_gambar);
            $input['gambar'] = $nama_gambar;
        }else{
            unset($input['gambar']);
        }
        $Subcategory->update($input);

    return response()->json([
        'message' => 'success',
        'data' => $Subcategory
    ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subcategory $Subcategory)
    {
        File::delete('uploads/' . $Subcategory->gambar);
        $Subcategory->delete();

        return response()->json([
            'message'=>'success'
        ]);
    }
}
