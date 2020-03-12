<?php

namespace App\Http\Controllers;

use App\Category;
use App\Link;
use App\Page;
use App\Product;
use App\User;
use http\Env\Response;
use Illuminate\Http\Request;

class HelperController extends Controller
{

    public function product_json(Request $request)
    {
        $products = Product::query();
        $products = $products->where('category_id',$request->category_id);
        if (!auth()->user()->isadmin)
            $products = $products->where('user_created', auth()->user()->id);
        else
            $products = $products->where('user_created', $request->user_id);
        $products = $products->orderByDesc('id');
        $products = $products->get();

        return response()->json($products);
    }


    public function data_json(Request $request, $user_id, $url)
    {
        $link = Link::where('url', $url)->where('user_created', $user_id)->first();

        if ($link == null)
            return response()->json(["message" => 'Try Again data invalide'], 403);

        $products = Product::query();
        if ($link->category_id != null)
            $products = $products->where('category_id',$link->category_id);
        else if($link->page_id != null)
            $products = $products->where('page_id',$link->page_id);

        $products = $products->where('user_created', $user_id);
        $products = $products->orderByDesc('id');
        $products = $products->get();
        return response()->json(['status' => 'OK', 'data' => $products], 200);
    }


    public function Generate_Links(Request $request)
    {
        if (!auth()->user()->isadmin)
            $user_id = auth()->user()->id;
        else
            $user_id = $request->input('user_id');
        if (User::find($user_id) != null) {
            $categories = Category::where('user_created', $user_id)->get();

            foreach ($categories as $category) {
                if (Link::where('category_id', $category->id)->where('user_created', $user_id)->count() == 0) {
                    $link = new Link();
                    $link->url = $this->quickRandom(10);
                    $link->category_id = $category->id;
                    $link->page_id = null;
                    $link->usercreated()->associate(User::find($user_id));
                    $link->save();
                }
            }


            $pages = Page::where('user_created', $user_id)->get();

            foreach ($pages as $page) {
                if (Link::where('page_id', $page->id)->where('user_created', $user_id)->count() == 0) {
                    $link = new Link();
                    $link->url = $this->quickRandom(10);
                    $link->category_id = null;
                    $link->page_id = $page->id;
                    $link->usercreated()->associate(User::find($user_id));
                    $link->save();
                }
            }

            $links = Link::where('user_created', $user_id)->get();
            return response()->json(['message' => 'Links Genereted Succes', 'data' => $links]);
        }

        return response()->json(['message' => 'Links Genereted Succes', 'data' => []]);
    }

    public function passwordchange(Request $request)
    {
        if (strlen($request->password) >= 6) {
            $user = auth()->user();
            $user->password = bcrypt($request->password);
            $user->update();
            return response()->json(['message' => 'Password Change Succes']);
        }


        return response()->json(['message' => 'Password Must have great then 6 digits Succes'], 500);

    }

    public function fullnamechange(Request $request)
    {
        $user = auth()->user();
        $user->name = ($request->name);
        $user->update();
        return response()->json(['message' => 'Name Change Succes']);
    }


    public static function quickRandom($length = 16)
    {
        $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
    }


}
