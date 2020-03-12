<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json($users);
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
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            if($request->password)
            {
                $user->password = bcrypt($request->password);
            }
            $user->save();
            return response()->json(['message' => 'User Created Succes','data'=>$user], 200);
        }
        catch (\Exception $e)
        {
            return response(['message'=>'Email Already Exists'],500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            $user->name = $request->name;
            $user->email = $request->email;
            if($request->password)
            {

                $user->password = bcrypt($request->password);
            }
            $user->update();
            return response()->json(['message' => 'User Updated Succes','data'=>$user], 200);
        }
        catch (\Exception $e)
        {
            return response(['message'=>'Email Already Exists'],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            if($user->isadmin)
                return response()->json(['message' => 'User Is Admin Cannot Delete Admin'], 500);
            $user->delete();
            return response()->json(['message' => 'User Deleted Succes'], 200);
        }
        catch (\Exception $e)
        {
            return response(['message'=>'Error: ' . $e->getMessage()],500);
        }

    }
}
