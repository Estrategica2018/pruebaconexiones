<?php

namespace App\Http\Controllers;

use App\Models\ManagementPages;
use Illuminate\Http\Request;

/**
 * Class HelpPlatformController
 * @package App\Http\Controllers
 */
class HelpPlatformController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $page = ManagementPages::where('name','tutorial')->first();
        $section = json_decode($page['section'],true);
        return view('help-platform',['page'=>$page, 'section'=>$section]);
    }
    
}
