<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdvanceLine;
use App\Models\AffiliatedAccountService;
use App\Models\CompanySequence;
use App\Models\SequenceMoment;
use Carbon\Carbon;

/**
 * Class AdvanceLineController
 * @package App\Http\Controllers
 */
class AdvanceLineController extends Controller
{
    //
    /**
     * @param Request $request
     * @param $accountServiceId
     * @param $sequenceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $accountServiceId, $sequenceId)
    {
        $accountService = AffiliatedAccountService::with('affiliated_content_account_service')->
                where('init_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
                ->find($accountServiceId);
        
        $sequence = CompanySequence::where('id', $sequenceId)->get()->first();    
        $student_id = auth('afiliadoempresa')->user()->id;
        $moments = [];
        foreach($sequence->moments as $sequenceMoment) {
            $result = app('App\Http\Controllers\AchievementController')->retriveProgressMoment($accountService, $student_id, $sequence->id, $sequenceMoment);
            
            $sections = [];
            if($result['moment']['isAvailable']) {
                foreach([1,2,3,4] as $section_id) {
                    $resultSection = app('App\Http\Controllers\AchievementController')->retriveProgressSection($accountService, $student_id, $sequence->id, $sequenceMoment, $section_id);
                    $sections[$section_id] = $resultSection['section'];
					$sections[$section_id]['nombre'] = json_decode($sequenceMoment['section_'.$section_id],true)['section']['name'];
                }    
            }
            $moments[$sequenceMoment['order']] = [
                'moment_id'=> $sequenceMoment['id'],
                'order'=> $sequenceMoment['order'],
                'isAvailable'=> $result['moment']['isAvailable'],
                'progress'=> $result['moment']['progress'],
                'performance'=> $result['moment']['performance'],
				'exclude_experience'=> $result['moment']['exclude_experience'],
                'sections' => $sections
            ];
        }
        return response()->json(['moments'=>$moments], 200);
    }
}
