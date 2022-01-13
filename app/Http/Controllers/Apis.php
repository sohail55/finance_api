<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\User;
use Dotenv\Validator as DotenvValidator;
use Illuminate\Support\Facades\Redis;
use Validator;

class Apis extends Controller
{
    //

    public function index(){
        return "index function";
    }


    public function firstApi(Request $request){

        $responseArray = [
            'status'=>'ok',
            'data'=>'22',
            'params1'=>$request->get('name'),
            'params2'=>$request->get('type')
        ]; 
        return response()->json($responseArray,200);
    }

    public function secondApi($id){
        $responseArray = [
            'status'=>'ok',
            'data'=>$id
        ]; 
        return response()->json($responseArray,200);
    }

    public function postApi(Request $request){
       // echo "<pre>"; print_r($request->file('image'));
        $responseArray = [
            'status'=>'ok',
            'data'=>'s',
            'name'=>$request->post('name'),
            'lastname'=>$request->post('lastname') 

        ]; 
        return response()->json($responseArray,200);
    }
    
    public function getTaskList(){
        $data =  Task::all();
        $responseArray = [
            'status'=>'ok',
            'data'=>$data
        ]; 
        return response()->json($responseArray,200);
    }

    public function getSingleTaskList($id){
        $data =  Task::find($id);
        if($data ==null){
            $data = 'Data Not Found';
        }
        $responseArray = [
            'status'=>'ok',
            'data'=>$data
        ]; 
        return response()->json($responseArray,200);
    }
    

    ////// PASSPORT LOGIN & REGISTER /////////////

    public function register(Request $request){ 

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
            'c_password'=>'required|same:password'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),202);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        $responseArray = [];
        $responseArray['token'] = $user->createToken('MyApp')->accessToken;
        $responseArray['name'] = $user->name;
        
        return response()->json($responseArray,200);  
    }

    /// login //////

    public function login(Request $request){ 
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = Auth::user();
            $responseArray = [];
            $responseArray['token'] = $user->createToken('MyApp')->accessToken;
            $responseArray['name'] = $user->name;
            
            return response()->json($responseArray,200);

        }else{
            return response()->json(['error'=>'Unauthenticated'],203);
        }
    }

    public function SearchQuery(Request $request){ 
      //dd('i m here');
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = Auth::user();
            $responseArray = [];
            $responseArray['token'] = $user->createToken('MyApp')->accessToken;
            $responseArray['name'] = $user->name;
            
            return response()->json($responseArray,200);

        }else{
            return response()->json(['error'=>'Unauthenticated'],203);
        }
    }

    
 
    
}