<?php

namespace App\Http\Controllers;

use DB;
use App\Models\City;
use Illuminate\Http\Request;

/**
 * Class CityController
 * @package App\Http\Controllers
 */
class CityController extends Controller
{
    //

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCitiesList()
    {

        $cities = DB::table('cities')
            ->join('departments', 'cities.department_id', '=', 'departments.id')
            ->select('cities.id', 'cities.name as text', 'cities.department_id', 'departments.name as department_name')
            ->get();

        return response()->json([
            'data' => $cities
        ], 200);

    }

}
