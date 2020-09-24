<?php

namespace App\Http\Controllers;

use App\Mail\SendChangeDateExpirationContent;
use App\Models\AffiliatedAccountService;
use App\Models\AfiliadoEmpresa;
use App\Models\ShoppingCart;
use App\Models\AffiliatedCompanyRole;
use App\Models\ConectionAffiliatedStudents;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\DataTables;
use App\Traits\RelationRatingPlan;
use DateTime;
/**
 * Class AdminController
 * @package App\Http\Controllers
 */
class AdminController extends Controller
{
    use RelationRatingPlan;
    
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {

        $request->user('afiliadoempresa')->authorizeRoles(['admin']);
        $affiliated = AfiliadoEmpresa::whereHas('company_teacher_rol', function ($query) {
            $query->where('rol_id','=','3');
        })->with('company_teacher_rol')->count();
        
        $companyAffiliated = AfiliadoEmpresa::whereHas('affiliated_account_services', function ($query) {
            $query->where([
                ['init_date', '<=', Carbon::now()],
                ['end_date', '>=', Carbon::now()]
            ]);
        })->count();
        
        $shoppingCarts = ShoppingCart::
            with('rating_plan', 'shopping_cart_product','affiliate','shopping_cart_product','payment_status')
            ->where('payment_status_id', '!=',1)
            ->orderBy('updated_at', 'DESC')
            ->skip(0)->take(10)
            ->get();
            
        $totalShoppingCarts = ShoppingCart::where('payment_status_id', '!=',1)->count();
        
        $payments = ShoppingCart::where('payment_status_id', '=',3)->get();
        
        $fist_day_previous = date("Y-n-j", strtotime("first day of previous month"));
        $end_day_previous =  date("Y-n-j", strtotime("last day of previous month"));
        $lastSumPrices =  ShoppingCart::where('payment_status_id', '=',3)->where([['updated_at','>=',$fist_day_previous],['updated_at','<=',$end_day_previous]])->get()->sum('rating_plan_price');
        
        
        $fist_day_now = date("Y-n-j", strtotime("first day of this month"));
        $end_day_now =  date("Y-n-j", strtotime("last day of this month"));
        $nowSumPrices =  ShoppingCart::where('payment_status_id', '=',3)->where([['updated_at','>=',$fist_day_now],['updated_at','<=',$end_day_now]])->get()->sum('rating_plan_price');
        
        $progressPrice = '0%';
        if($lastSumPrices > 0 && $nowSumPrices > 0) {
            $progressPrice = round($nowSumPrices/$lastSumPrices,2);
        }
        //dd($lastSumPrices, $nowSumPrices, $progressPrice);
        
        $totalSumPrices =  $payments->sum('rating_plan_price');
        
        
            
        $countShoppingCarts = count($shoppingCarts);
        return view('roles.admin.index',['affiliated'=>$affiliated,'companyAffiliated'=>$companyAffiliated, 'shoppingCarts'=>$shoppingCarts, 'countShoppingCarts'=>$countShoppingCarts, 'totalShoppingCarts'=>$totalShoppingCarts, 'totalSumPrices'=>$totalSumPrices, 'progressPrice'=>$progressPrice]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get_users_contracted_products_view(Request $request)
    {

        return view('roles.admin.listUsersAccountServices');

    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function get_users_contracted_products_dt(Request $request)
    {

        $companyAffiliateds = AfiliadoEmpresa::whereHas('affiliated_account_services')->get();

        return DataTables::of($companyAffiliateds)
            ->addColumn('avatar', function ($companyAffiliated) {
                $url = $companyAffiliated->url_image ? $companyAffiliated->url_image : '/images/icons/default-avatar.png';
                return '<div class="avatar avatar-m">
                        <img class="rounded-circle" src="' . $url. '" alt="" />
                       </div>';
            })
            ->addColumn('name', function ($companyAffiliated) {
                return $companyAffiliated->name;
            })
            ->addColumn('last_name', function ($companyAffiliated) {
                return $companyAffiliated->last_name;
            })
            ->addColumn('email', function ($companyAffiliated) {
                return $companyAffiliated->email;
            })
            ->addColumn('phone', function ($companyAffiliated) {
                return $companyAffiliated->phone;
            })
            ->addColumn('content', function ($companyAffiliated) {
                return '<button class="btn btn-primary btn-sm mr-1 mb-1 viewContens" type="button" style="padding: 0.1875rem 1.75rem;font-size: 0.67rem;">Ver</button>';
            })
            ->rawColumns(['content', 'avatar'])
            ->make(true);

    }

    /**
     * @param Request $request
     * @param null $affiliatedId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get_user_contracted_products_view(Request $request, $affiliatedId = null)
    {

        if ($affiliatedId !== null) {
            $companyAffiliated = AfiliadoEmpresa::find($affiliatedId);
            return view('roles.admin.listUserAccountServices')->with('companyAffiliated', $companyAffiliated);
        }

    }

    /**
     * @param Request $request
     * @param $affiliatedId
     * @return mixed
     * @throws \Exception
     */
    public function get_user_contracted_products_dt(Request $request, $affiliatedId)
    {
        $affiliatedAccountService = AffiliatedAccountService::with(['rating_plan.type_plan', 'affiliated_content_account_service' => function ($query) {
            $query->with('sequence')->select('id', 'sequence_id', 'affiliated_account_service_id')->groupBy('affiliated_account_service_id', 'sequence_id');
        }])->where([
            ['company_affiliated_id', $affiliatedId],
        ])->get();
        return DataTables::of($affiliatedAccountService)
            ->addColumn('plan', function ($affiliatedAccountService) {
                return $affiliatedAccountService->rating_plan->name . ' (' . $affiliatedAccountService->rating_plan->type_plan->name . ')';
            })
            ->addColumn('state', function ($affiliatedAccountService) {
                if($affiliatedAccountService->init_date <= Carbon::now() && $affiliatedAccountService->end_date >= Carbon::now())
                    return '<span class="rounded-capsule badge badge-soft-success">Activo</span>';
                return '<span class="rounded-capsule badge badge-soft-warning">Inactivo</span>';
            })
            ->addColumn('init_date', function ($affiliatedAccountService) {
                return $affiliatedAccountService->init_date;
            })
            ->addColumn('end_date', function ($affiliatedAccountService) {
                return $affiliatedAccountService->end_date;
            })
            ->addColumn('edit_date', function ($affiliatedAccountService) {
                return '<button class="btn btn-warning btn-sm mr-1 mb-1 edit_date" type="button" style="padding: 0.1875rem 1.75rem;font-size: 0.67rem;">Editar</button>';
            })
            ->rawColumns(['state', 'edit_date'])
            ->make(true);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update_date_expiration_content_user(Request $request)
    {

        $update = AffiliatedAccountService::where([
            ['id', $request->get('accountServiceId')],
        ])->update(array('end_date' => $request->get('end_date')));

        Mail::to($request->get('email'))->send(new SendChangeDateExpirationContent(
            $request->get('originalEndDate'),
            $request->get('end_date'),
            $request->get('plan'),
            $request->get('full_name')
        ));
        if ($update) {
            return response()->json(['validation' => true, 'message' => 'Se ha actualizado la fecha de expiración'], 200);
        } else {
            return response()->json(['validation' => false, 'message' => 'Algo salio mal, intente de nuevo'], 400);
        }
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function plans_view()
    {

        return view('roles.admin.plans');

    }

    public function payments(){

        return response()->json(['data'=>ShoppingCart::with('affiliate')->get()],200);

    }

    public function affiliates(){

       return response()->json(['data'=>AfiliadoEmpresa::all()->count()],200);

    }

    public function management_kit_elements_view() {

        return view('roles.admin.management_kit_elements');

    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show_all_users()
    {
        return view('roles.admin.listAllUsers');
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_all_users(Request $request)
    {
        $affiliated = AfiliadoEmpresa::whereHas('company_teacher_rol', function ($query) {
            $query->where('rol_id','=','3');
        })
        ->with(['company_teacher_rol','country','city','affiliated_account_services'=>function($query){
            $query->where([
                ['init_date', '<=', Carbon::now()],
                ['end_date', '>=', Carbon::now()]
            ]);            
        }])->get();
        
        return response()->json(['users' => $affiliated], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_user(Request $request, $user_id)
    {
        
        $user = AfiliadoEmpresa::with('country','affiliated_account_services')->find($user_id);
        

        $rol_id = AffiliatedCompanyRole::where([
            ['affiliated_company_id', $user->id],
            ['rol_id', 3],//familiar
            ['company_id', 1]//conexiones
        ])->first();
        
        $kidSelected = ConectionAffiliatedStudents::with('student_family.retrive_afiliado_empresa')->where([
            ['tutor_company_id', $rol_id->id]
        ])->get();
        
        
        $shoppingCarts = ShoppingCart::
            with('rating_plan', 'shopping_cart_product','payment_status')
            ->where('payment_status_id', '!=',1)
            ->whereHas('affiliate', function ($query) use ($user_id) {
                 $query->where('id','=',$user_id);
            })
            ->get();
        $shoppingCarts = $this->relation_rating_plan($shoppingCarts);

        return response()->json(['shoppingCarts'=>$shoppingCarts, 'affiliate'=>$user, 'kidSelected'=>$kidSelected], 200);
    }
    
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get_user_shoppingCart(Request $request, $idShoppingCart)
    {   
        $shoppingCart = ShoppingCart::
            with('rating_plan', 'shopping_cart_product','payment_status')
            ->where([['payment_status_id', '!=',1],['id',$idShoppingCart]])
            ->get();
            
        $shoppingCart = $this->relation_rating_plan($shoppingCart)[0];    
        
        $user = AfiliadoEmpresa::with('country','affiliated_account_services')->find($shoppingCart->affiliate->id);
        $user_id = $user->id;
        
        $rol_id = AffiliatedCompanyRole::where([
            ['affiliated_company_id', $user->id],
            ['rol_id', 3],//familiar
            ['company_id', 1]//conexiones
        ])->first();
        
        $kidSelected = ConectionAffiliatedStudents::with('student_family.retrive_afiliado_empresa')->where([
            ['tutor_company_id', $rol_id->id]
        ])->get();
        
        $shoppingCarts = ShoppingCart::
            with('rating_plan', 'shopping_cart_product','payment_status')
            ->where('payment_status_id', '!=',1)
            ->whereHas('affiliate', function ($query) use ($user_id) {
                 $query->where('id','=',$user_id);
            })
            ->get();
        $shoppingCarts = $this->relation_rating_plan($shoppingCarts);
        
        return response()->json(['transaction'=>$shoppingCart, 'affiliate' => $user, 'shoppingCarts' => $shoppingCarts, 'kidSelected'=>$kidSelected], 200);
    }
}
