<?php

namespace App\Http\Controllers;

use App\Category;
use App\Page;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;



class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $product = new Product();
            $product->fill($request->all());
            $image = array();
            if ($files = $request->file('files')) {
                foreach ($files as $index=>$file) {
                    $image[] = $file->store('User_' . preg_replace('/[^a-zA-Z0-9\-\._]/', '', auth()->user()->name) . '_' . auth()->user()->id . '_products');
                }

                $product->image = implode(",", $image);
            } else {
                $product->image = 'product.jpg';
            }

            $product->Category()->associate(Category::find($request->category_id));
            $product->Page()->associate(Page::find($request->page_id));
            if (!auth()->user()->isadmin)
                $product->usercreated()->associate(auth()->user());
            else
                $product->usercreated()->associate(User::find($request->user_id));
            $product->save();
            return response()->json(['message' => 'Product Created Succes', 'data' => $product], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error: ' . $e->getLine() . ' \n ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        try {
            $product->fill($request->all());
            $image = array();
            if ($files = $request->file('files')) {
                foreach ($files as $file) {
                    $image[] = $file->store('User_' . preg_replace('/[^a-zA-Z0-9\-\._]/', '', auth()->user()->name) . '_' . auth()->user()->id . '_products');
                }

                if (empty($request->image_urls))
                    $product->image = implode(",", $image);
                else
                    $product->image = $request->image_urls . "," . implode(",", $image);
            } else {
                if (empty($request->image_urls))
                    $product->image = 'product.jpg';
                else
                    $product->image = $request->image_urls;

            }
            $product->Category()->associate(Category::find($request->category_id));
            $product->Page()->associate(Page::find($request->page_id));
            $product->update();

            return response()->json(['message' => 'Product Updated Succes', 'data' => $product], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json(['message' => 'Product Deleted Succes'], 200);
        } catch (\Exception $e) {
            return response(['message' => 'Error: ' . $e->getMessage()], 500);
        }

    }
}
