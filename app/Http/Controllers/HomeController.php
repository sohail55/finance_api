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
            return redirect('/home')->with('msg', 'Company added Successfully');
        }else{
            echo "<pre>";
            print_r($company_name);
        }

    }


    // function curlRequest($query) {

    //     $curl = curl_init();

    //     curl_setopt_array($curl, [
    //     CURLOPT_URL => "https://yh-finance.p.rapidapi.com/auto-complete?q=$query&region=US",
    //     CURLOPT_RETURNTRANSFER => true,
    //     CURLOPT_FOLLOWLOCATION => true,
    //     CURLOPT_ENCODING => "",
    //     CURLOPT_MAXREDIRS => 10,
    //     CURLOPT_TIMEOUT => 30,
    //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //     CURLOPT_CUSTOMREQUEST => "GET",
    //     CURLOPT_HTTPHEADER => [
    //         "x-rapidapi-host: yh-finance.p.rapidapi.com",
    //         "x-rapidapi-key: f0d3e7f880msh2833404c41f6f40p1906b2jsn610b11b925a0"
    //     ],
    //     ]);

    //     $response = curl_exec($curl);
    //     $err = curl_error($curl);
    //     $result = json_decode($response, true);
    //     return $result;

    // }
}
