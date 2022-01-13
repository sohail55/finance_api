<?php

namespace App\Http\Controllers;
use Validator;
use App\Models\Company;
use App\Models\CompanyUser;
use Session;

use Illuminate\Http\Request;

class HomeController extends Controller
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
        $financeResult = Company::where('user_id','=',Auth()->user()->id)->get()->toArray();
        //dd($financeResult);
        return view('home',compact('financeResult'));
    }

    public function SearchQuery(Request $request) {

        $validator = Validator::make($request->all(),[
            'searchQuery'=>'required',
          ]);

          if(!$validator->passes()){
              return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
          }else{
        
            $result = curlRequest('auto-complete?q=',$request->searchQuery);

            if(!empty($result)) {
                $quotes = $result['quotes'];
                return response()->json(['status'=>1, 'result'=>$quotes]);
            }

        }
    }

    public function saveResult(Request $request) {

        $financeArray  = explode('_',$request->financeResult);
        $company_name = Company::where('shortname','LIKE',$financeArray[1])->get()->toArray();
        //$company_name = DB::table('search_finances')->where('shortname','LIKE',$financeArray[1])->get();
        if(empty($company_name)){
            $data = [];
            $company =[];
            $data['user_id']   =  $request->user()->id;
            $data['exchange']  =  $financeArray[0];
            $data['shortname'] =  $financeArray[1];
            $data['quoteType'] =  $financeArray[2];
            $data['symbol']    =  $financeArray[3];

            $quotes_result = curlRequest('market/v2/get-quotes?symbols',$financeArray[3]);

            $data['messageBoardId'] = !empty($quotes_result['quoteResponse']['result'][0]['messageBoardId']) ? $quotes_result['quoteResponse']['result'][0]['messageBoardId'] : '';

            //dd($quotes_result['quoteResponse']['result'][0]['messageBoardId']);

            $companyResult = Company::create($data);

            $company['company_id'] = $companyResult->id;
            $company['user_id'] = $request->user()->id;

            //dd($company);

            CompanyUser::create($company);
        }else{

            $company['company_id'] = $company_name[0]['id'];
            $company['user_id'] = $request->user()->id;
            CompanyUser::create($company);
        }
        return redirect('/home')->with('msg', 'Company added Successfully');
    }

}