<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Country;
use Illuminate\Http\Request;

/**
 * Class CountryController
 * @package App\Http\Controllers
 */
class CountryController extends Controller
{
    //

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountriesList()
    {

        $country = DB::table('countries')
            ->select('countries.id', 'countries.name as text')
            ->get();

        return response()->json([
            'data' => $country
        ], 200);


    }

}
