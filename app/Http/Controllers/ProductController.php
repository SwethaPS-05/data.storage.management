<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(){
        return view('products.index',[
            'products' =>  Product::get() 
        ]);
    }

    public function create(){
        return view('products.create');
    }

    public function store(Request $request){

        $request->validate([
            'name' => 'required',
            'description' =>'required',
            'image' => 'required|mimes:jpeg,jpg,png,gif|max:1000'
        ]);

        //dd($request->all());
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('products'), $imageName);

        $product = new Product;
        $product->image = $imageName;
        $product->name = $request->name;
        $product->describtion = $request->description;

        $product->save();

        return back()->withSuccess('Product Created !!!');
    }

    public function edit($id){
        $product = Product::where('id',$id)->first();

        return view('products.edit',['product' => $product]);
    }

    public function update(Request $request, $id)
    {
       // dd($request->all());
        $request->validate([
            'name' => 'required',
            'description' =>'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:1000'
        ]);

        $product = Product::where('id',$id)->first();

        if(isset($request->image)){
             //dd($request->all());
             $imageName = time().'.'.$request->image->extension();
             $request->image->move(public_path('products'), $imageName);
             $product->image = $imageName;
        }

        $product->name = $request->name;
        $product->describtion = $request->description;

        $product->save();

        return back()->withSuccess('Product updated !!!');
    }

    public function destory($id){
        $product = Product::where('id',$id)->first();
        $product->delete();
        return back()->withSuccess('Product deleted !!!!');
    }
  
    public function show($id){
      $product = Product::where('id',$id)->first();
      
      return view('products.show',['product'=>$product]);
}

}
