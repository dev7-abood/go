<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pages = Page::query();

        if(!auth()->user()->isadmin)
            $user_id = auth()->user()->id;
        else
            $user_id = $request->user_id;

        $pages=$pages->where('user_created',$user_id);
        $pages = $pages->get();
        return response()->json($pages);
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
            $page = new Page;
            $page->fill($request->all());

            $page->usercreated()->associate(auth()->user());
            $page->save();
            return response()->json(['message'=>'Page Created Succes','data'=>$page],200);
        }
        catch (\Exception $e)
        {
            return response(['message'=>'Error: ' + $e->getMessage()],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        try{
            $page->fill($request->all());
            $page->update();
            return response()->json(['message'=>'Page Updated Succes','data'=>$page],200);
        }
        catch (\Exception $e)
        {
            return response(['message'=>'Error: ' + $e->getMessage()],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        try{
            $page->delete();
            return response()->json(['message'=>'Page Deleted Succes','data'=>$page],200);
        }
        catch (\Exception $e)
        {
            return response(['message'=>'Error: ' + $e->getMessage()],500);
        }
    }
}
