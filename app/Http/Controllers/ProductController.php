<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\Laravel\Facades\Image;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::query();
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
}
