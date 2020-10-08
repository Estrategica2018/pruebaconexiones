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
        $kits = Kit::
        where('kits.end_date', '>=', date('Y-m-d'))
        ->orWhereNull('kits.end_date')
        ->get();
        
        $elements = Element::where('end_date', '>=', date('Y-m-d'))
        ->orWhereNull('end_date')
        ->get();
        
        return response()->json(['kits'=>$kits, 'elements'=>$elements],200);
    }

    /**
     * @param Request $request
     * @param $kid_id
     * @return mixed
     */
    public function get_kit(Request $request, $kid_id)
    {
        $dt = new \DateTime();
        return Kit::
        with(['moment_kits' => function($query) use ($dt) {
            $query->with(['moment'=>function($moment) use ($dt) {
                $moment->with(['sequence' => function($sequence)  use ($dt) {
                    $sequence->select('company_sequences.id','company_sequences.company_id','company_sequences.name','company_sequences.description','company_sequences.url_image');
                    $sequence->where(function ($query) use ($dt) {
                        $query->where('company_sequences.expiration_date', '>=', $dt->format('Y-m-d'))
                            ->orWhereNull('company_sequences.expiration_date'); 
                    });
                    $sequence->where('company_sequences.init_date', '<=', $dt->format('Y-m-d'));
                }]);
                //$moment->select('*');
                $moment->select(['sequence_moments.id','sequence_moments.id','sequence_moments.sequence_company_id','sequence_moments.order']);
            }]);
            $query->select(['moment_kits.id','moment_kits.*']);
        }])
        ->with('kit_elements', 'kit_elements.element')
        ->where('end_date', '>=', date('Y-m-d'))
        ->find($kid_id);
    }

    /**
     * @param Request $request
     * @param $element_id
     * @return mixed
     */
    public function get_element(Request $request, $element_id)
    {
        $dt = new \DateTime();
        return Element::
        with(['element_in_moment' => function($query) use ($dt) {
            $query->with(['moment' => function($detail) use ($dt) {
                $detail->with(['sequence' => function($seq) use ($dt) {
                    $seq->select(['company_sequences.id','company_sequences.name','company_sequences.description','company_sequences.url_image','company_sequences.url_slider_images']);
                    $seq->where(function ($query) use ($dt) {
                        $query->where('company_sequences.expiration_date', '>=', $dt->format('Y-m-d'))
                            ->orWhereNull('company_sequences.expiration_date'); 
                    });
                    $seq->where('company_sequences.init_date', '<=', $dt->format('Y-m-d'));
                }]);
                $detail->select(['sequence_moments.id','sequence_moments.id','sequence_moments.sequence_company_id','sequence_moments.order']);
            }]);
            $query->select(['moment_kits.id','moment_kits.*']);
        }])
        ->select('elements.*',DB::raw('(CASE WHEN elements.quantity = 0 THEN "sold-out" ELSE CASE WHEN elements.init_date < CURDATE() THEN "available" ELSE "no-available" END END) AS status'))
        ->find($element_id);
    }

    public function get_kit_element_dt (){

        $kitsElements['kits'] = Kit::get();
        $kitsElements['elements'] = Element::get();

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
