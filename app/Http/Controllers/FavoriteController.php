<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{


    /**
     * FavoriteController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Reply $reply)
    {


        //return 'MM';
         //since we are using route model binding
         $reply->favorite();
         //return back()->with('flash', 'You Have liked liked this thread!');
    }

    public function destroy(Reply $reply)
    {
         //since we are using route model binding
          //return 'MM';
        $reply->unfavorite();
       // return back()->with('flash', 'You Have liked liked this thread!');
    }
}
