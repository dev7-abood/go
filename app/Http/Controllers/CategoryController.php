<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = Category::query();

        if(!auth()->user()->isadmin)
            $user_id = auth()->user()->id;
        else
            $user_id = $request->user_id;

        $categories=$categories->where('user_created',$user_id);
        $categories = $categories->get();
        return response()->json($categories);
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
        //
        try{
            $category = new Category;
            $category->fill($request->all());

            $category->usercreated()->associate(auth()->user());
            $category->save();
            return response()->json(['message'=>'Category Created Succes','data'=>$category],200);
        }
        catch (\Exception $e)
        {
            return response(['message'=>'Error: ' + $e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        try{
            $category->fill($request->all());
            $category->update();
            return response()->json(['message'=>'Category Updated Succes','data'=>$category],200);
        }
        catch (\Exception $e)
        {
            return response(['message'=>'Error: ' + $e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try{
            $category->delete();
            return response()->json(['message'=>'Category Deleted Succes','data'=>$category],200);
        }
        catch (\Exception $e)
        {
            return response(['message'=>'Error: ' + $e->getMessage()],500);
        }
    }
}
