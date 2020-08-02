<?php

namespace App\Http\Controllers;

use App\Models\Kit;
use App\Models\Element;
use App\Models\MomentKits;
use Illuminate\Http\Request;

use DB;

/**
 * Class KitElementController
 * @package App\Http\Controllers
 */
class KitElementController extends Controller
{
    //
    /**
     * @param Request $request
     * @return Kit[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get_kit_elements(Request $request)
    {
        return Kit::with('kit_elements')
        ->with(['kit_elements.element' => function($query) {
            $query->select('elements.*',DB::raw('(CASE WHEN elements.quantity = 0 THEN "sold-out" ELSE CASE WHEN elements.init_date < CURDATE() THEN "available" ELSE "no-available" END END) AS status'));
        }])
        ->select('kits.*',DB::raw('(CASE WHEN kits.quantity = 0 THEN "sold-out" ELSE CASE WHEN kits.init_date < CURDATE() THEN "available" ELSE "no-available" END END) AS status'))
        ->get();
    }

    /**
     * @param Request $request
     * @param $kid_id
     * @return mixed
     */
    public function get_kit(Request $request, $kid_id)
    {
        return Kit::
        with(['moment_kits' => function($query) {
            $query->with(['moment' => function($detail) {
                $detail->with(['sequence' => function($seq) {
                    $seq->select(['company_sequences.id','company_sequences.name','company_sequences.description','company_sequences.url_image','company_sequences.url_slider_images']);
                }]);
                $detail->select(['id','sequence_moments.*']);
            }]);
            $query->select(['moment_kits.id','moment_kits.*']);
        }])
        ->with('kit_elements', 'kit_elements.element')
        ->select('kits.*',DB::raw('(CASE WHEN kits.quantity = 0 THEN "sold-out" ELSE CASE WHEN kits.init_date < CURDATE() THEN "available" ELSE "no-available" END END) AS status'))
        
        ->find($kid_id);
    }

    /**
     * @param Request $request
     * @param $element_id
     * @return mixed
     */
    public function get_element(Request $request, $element_id)
    {

        return Element::where('id', $element_id)
        ->select('elements.*',DB::raw('(CASE WHEN elements.quantity = 0 THEN "sold-out" ELSE CASE WHEN elements.init_date < CURDATE() THEN "available" ELSE "no-available" END END) AS status'))
        ->get();

    }

    public function get_kit_element_dt (){

        $kitsElements['kits'] = Kit::
        select('kits.*',DB::raw('(CASE WHEN kits.quantity = 0 THEN "sold-out" ELSE CASE WHEN kits.init_date < CURDATE() THEN "available" ELSE "no-available" END END) AS status'))
        ->get();
        $kitsElements['elements'] = Element::
        select('elements.*',DB::raw('(CASE WHEN elements.quantity = 0 THEN "sold-out" ELSE CASE WHEN elements.init_date < CURDATE() THEN "available" ELSE "no-available" END END) AS status'))
        ->get();

        return response()->json($kitsElements,200);

    }

    public function delete_elementorkit_in_moment (Request $request){

        $kitElement = MomentKits::where(
            'id',$request->id
        )->delete();
        return response()->json([
            'status' => 'successfull',
            'message' => 'El elemento ha sido desvinculado del momento',
        ]);
    }

}
