<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();
        if($request->searchQuery != ''){
            $products = Product::where('name','like','%'. $request->searchQuery . '%');
        }
        $products = $products->latest()->paginate(2);
        return response()->json([
            'products' => $products
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        if($request->image != ""){
            $strpos = strpos($request->image, ";");
            $sub = substr($request->image,0,$strpos);
            $ex = explode('/', $sub)[1];
            $name = time().".".$ex;
            $img = Image::read($request->image)->resize(200,200);
            $uploadPath = public_path() . "/upload/";
            $img->save($uploadPath.$name);
            $product->image = $name;
        }else{
            $product->image = "no-image.jpg";
        }
        $product->type = $request->type;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->save();
    }

    public function edit($id)
    {
        $product  = Product::find($id);
        return response()->json([
            'products' => $product
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        if($request->image != $product->image){
            $strpos = strpos($request->image, ";");
            $sub = substr($request->image,0,$strpos);
            $ex = explode('/', $sub)[1];
            $name = time().".".$ex;
            $img = Image::read($request->image)->resize(200,200);
            $uploadPath = public_path() . "/upload/";
            $image = $uploadPath . $product->image;
            if(file_exists($image)){
                @unlink($image);
            }
            $img->save($uploadPath.$name);
            $product->image = $name;
        }else{
            $product->image = $product->image;
        }
        $product->type = $request->type;
        $product->quantity = $request->quantity;
        $product->price = $request->price;
        $product->save();
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $imagePath = public_path() . "/upload/";
        $image = $imagePath . $product->image;
        if(file_exists($image)){
            @unlink($image);
        }
        $product->delete();
    }
}
