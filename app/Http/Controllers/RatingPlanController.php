<?php

namespace App\Http\Controllers;

use App\Models\CompanySequence;
use App\Models\MomentExperience;
use App\Models\RatingPlan;
use App\Models\SequenceMoment;
use App\Models\AffiliatedAccountService;
use App\Models\TypesRatingPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

/**
 * Class RatingPlanController
 * @package App\Http\Controllers
 */
class RatingPlanController extends Controller
{

    
    /**
     * @param Request $request
     * @param $rating_plan_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showRatingPlan(Request $request, $rating_plan_id)
    {
        $ratingPlan = RatingPlan::find($rating_plan_id);
        return $ratingPlan ? view('ratingPlan.detail',['rating_plan_id'=>$request->rating_plan_id,'sequence_id'=>$request->sequence_id])
            : view('page404',['message'=>'Plan de acceso no encontrado']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_rating_plans(Request $request)
    {
        $ratingPlan = RatingPlan::where('expiration_date', '<', Carbon::now()->format('Y-m-d'))->orWhere('expiration_date', NULL)->with('type_plan')->get();

        return response()->json(['data' => $ratingPlan], 200);
    }

    /**
     * @param Request $request
     * @param $rating_plan_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_rating_plan_detail(Request $request, $rating_plan_id)
    {
        $ratingPlan = RatingPlan::where('id', $rating_plan_id)->get();
        return response()->json(['data' => $ratingPlan], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {

        $data = $request->all();
        $ratingPlan = new RatingPlan();
        $ratingPlan->name = $data['name'];
        $ratingPlan->description = $data['description'];
        $ratingPlan->type_rating_plan_id = $data['type_rating_plan_id'];
        $ratingPlan->count = $data['quantity'];
        $ratingPlan->days = $data['days'];
        $ratingPlan->price = $data['cost'];
        $itmes = @json_decode($data['itmes']);
        $itemConcat = '';
        foreach ($itmes as $item) {
            $itemConcat = $itemConcat . $item->description . '|';
        }
        $ratingPlan->description_items = $itemConcat;
        if ($data['is_free'] == "true") {
            $ratingPlan->is_free = $data['is_free'];
            $ratingPlan->sequence_free_id = $data['sequenceSelected'];
            $ratingPlan->moment_free_ids = $data['momentSelected'];
        }
        $ratingPlan->init_date = $data['init_date'];
        $ratingPlan->expiration_date = $data['expiration_date'];
        $ratingPlan->save();
        return response()->json(['data' => $ratingPlan], 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {

        $data = $request->all();
        $ratingPlan = RatingPlan::findOrFail($data['id']);
        $ratingPlan->name = $data['name'];
        $ratingPlan->description = $data['description'];
        $ratingPlan->price = $data['cost'];
        $ratingPlan->days = $data['days'];
        $ratingPlan->init_date = $data['init_date'];
        if ($data['isExpiration'] == 'true') {
            $ratingPlan->expiration_date = $data['expiration_date'];
        } else {
            $ratingPlan->expiration_date = NULL;
        }
        $ratingPlan->save();
        return response()->json(['data' => $ratingPlan], 200);

    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function get_plans_dt()
    {

        $ratingPlans = RatingPlan::with('type_plan')->get();

        return DataTables::of($ratingPlans)
            ->addColumn('name', function ($ratingPlan) {
                return $ratingPlan->name;
            })
            ->addColumn('description', function ($ratingPlan) {
                return $ratingPlan->description;
            })
            ->addColumn('type_rating_plan', function ($ratingPlan) {
                return $ratingPlan->type_plan->name;
            })
            ->addColumn('quantity_contents', function ($ratingPlan) {
                return $ratingPlan->count;
            })
            ->addColumn('quantity_days', function ($ratingPlan) {
                return $ratingPlan->days;
            })
            ->addColumn('price', function ($ratingPlan) {
                return $ratingPlan->price;
            })
            ->addColumn('init_date', function ($ratingPlan) {
                return $ratingPlan->init_date;
            })
            ->addColumn('expiration_date', function ($ratingPlan) {
                if ($ratingPlan->expiration_date === null || $ratingPlan->expiration_date === '')
                    return 'No aplica';
                return $ratingPlan->expiration_date;
            })
            ->addColumn('edit', function ($ratingPlan) {
                return '<button class="btn btn-warning btn-sm mr-1 mb-1 editPlan" type="button" style="padding: 0.01875rem 1.0rem;font-size: 0.67rem;">Editar</button>';
            })
            ->rawColumns(['edit'])
            ->make(true);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_types_plans()
    {
        $typesRatingPlans = TypesRatingPlan::all();
        return response()->json(['data' => $typesRatingPlans], 200);

    }

}
