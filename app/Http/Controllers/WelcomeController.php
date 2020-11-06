<?php

namespace App\Http\Controllers;

use App\Models\RatingPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Class WelcomeController
 * @package App\Http\Controllers
 */
class WelcomeController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $rating_plan_id_free = RatingPlan::where('is_free', true)->first();
        return view('welcome', ['rating_plan_id_free' => $rating_plan_id_free->id]);
    }
	
	public function test(Request $request,$url)
    {
     return view('test',['url'=>$url]);
    }
}
