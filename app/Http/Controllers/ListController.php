<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\UserWatchList;
use App\Models\WatchListCompany;
use Session;

use Illuminate\Http\Request;

class ListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //dd('i am here again');
        $companies = Company::where('user_id','=',Auth()->user()->id)->get()->toArray();
        $user_watchlists = UserWatchList::where('user_id','=',Auth()->user()->id)
        ->with('WatchListCompany')
        ->get()
        ->toArray();

        return view('List.create', compact('companies','user_watchlists'));
    }

    public function create(Request $request) {

        $validator = Validator::make($request->all(),[
            'list_name'=>'required',
          ]);
        //dd($request);

          if(!$validator->passes()){
              return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
          }else{

            $watchlist['user_id']   =  Auth()->user()->id;
            $watchlist['list_name'] =  $request->list_name;
            
            $user_watchlist = UserWatchList::create($watchlist);

            $companies_id = $request->company_id;

            $watchlist_company =[];
            foreach($companies_id as $key => $company_id)
            {
                $watchlist_company[$key]['user_watch_list_id'] = $user_watchlist->id;
                $watchlist_company[$key]['company_id'] = $company_id;
            }

            $watchlistCompanyResult = WatchListCompany::insert($watchlist_company);

            return response()->json(['status'=>1, 'result'=>'/createList']);
            return route('createList');
            return redirect('/createList')->with('msg', 'WatchList added Successfully');
        }
    }


    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(),[
            'list_name'=>'required',
          ]);

          if(!$validator->passes()){
              return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
          }else{

            if (!UserWatchList::where('list_name', '=', $request->list_name)->exists()) {
                UserWatchList::where('id', $id)->update(array('list_name' => $request->list_name));
            }
            
            $company_name = UserWatchList::where('list_name','=',$request->list_name)->get()->toArray();

            $result = WatchListCompany::where('user_watch_list_id','=', $id)->delete(); 
            
            $companies_id = $request->company_id;
            $watchlist_company = [];
            if($result) {
                foreach($companies_id as $key => $company_id)
                {
                    $watchlist_company[$key]['user_watch_list_id'] = $id;
                    $watchlist_company[$key]['company_id'] = $company_id;
                }
            }
            else {
                foreach($companies_id as $key => $company_id)
                {
                    $watchlist_company[$key]['user_watch_list_id'] = $id;
                    $watchlist_company[$key]['company_id'] = $company_id;
                }
            }

            $watchlistCompanyResult = WatchListCompany::insert($watchlist_company);

            //return redirect()->back()->with('msg', 'WatchList updated Successfully');
            return response()->json(['status'=>1, 'result'=>"/editList/$id"]);
            // return route('createList');
            // return redirect('/createList')->with('msg', 'WatchList added Successfully');
        }
    }

    public function editList($id) {
        
        $user_watchlists = UserWatchList::where('id','=',$id)
        ->with('WatchListCompany.UserCompany')
        ->first()
        ->toArray();


        $companies_list = Company::all()->toArray();
        $companyIds = [];
        foreach($user_watchlists['watch_list_company'] as $user_company) {
            foreach($user_company['user_company'] as $company) {
                $companyIds[] = $company['id'];
            }
        }
        //$companies = Company::pluck('id')->toArray();

        //dd($companyIds);
        //dd($user_watchlists);
        return view('List.edit', compact('user_watchlists', 'companies_list','companyIds'));
    
    }


}
