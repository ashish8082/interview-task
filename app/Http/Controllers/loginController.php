<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Auth;
use Session;
use App\Models\Vehicle;
use Hash;
class loginController extends Controller
{
    public function index()
    {
       
        return view('login');
    }
    public function login(Request $request)
    {
        if($request->isMethod('post'))
        {
            $request_data= $request->all();
            $message = [
                'email.required'=>'Please enter email id',
                'email.email'=>'Valid Email is required',
                'password.required'=>'Please enter password'
            ];
           $validator =  Validator::make($request_data,[
            'email'=>'required|email',
            'password'=>'required'
           ],$message);
           if($validator->fails())
           {
                return response()->json(['errors' => $validator->errors()]);
            }
           else 
           {
                $email =$request->email;
                $password = $request->password;
            
              $user_details = User::where('email', $email)->get();
                 
                if (count($user_details) > 0) 
                {
                    
                       
                            if (Auth::attempt(['email'=>$email,'password'=>$password]))
                            {
                                Session::put("uname",$user_details[0]->name);
                                return response()->json(['success' =>'Added successfully']);

                            } 
                            else 
                            {
                                return response()->json(['password' =>'Please enter  correct password']);
                              
                            }
                    
                } 
                else
                 {
                     return response()->json(['email' =>'  user email is not found']);
                    
                }
           }

        }
    }
    public function addVehicle (Request $request)
    {
       if($request->isMethod('post'))
       {
        $data = $request->all();

        $message = [
            'vehicle_type.required'=>'Please select Vehicle type',
            'yom.required'=>'Please select year of manufacture',
            'dop.required'=>'Please select date of purchase'
        ];
        $validator  = Validator::make($data,[
            'vehicle_type'=>'required',
            'yom'=>'required',
            'dop'=>'required'
        ],$message);
        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
           
        }
        else 
        {
            if($request->vehicleId)
            {
            $vehicle = Vehicle::find($request->vehicleId);
            }
            else 
            {
                $vehicle = new Vehicle();
            }
            $userid = Auth::user()->id;
            $vehicle->vehicle_type = $request->vehicle_type;
            $vehicle->yom = $request->yom;
            $vehicle->dop = $request->dop;
            $vehicle->dop = $request->dop;
            $vehicle->user_id =  $userid;
            $vehicle->save();

            $vehicledata = Vehicle:: where('user_id',$userid)->get();
            $totalVehicle = [];
            foreach( $vehicledata as $key=>$vehicleRow)
            {
                if($vehicleRow->vehicle_type== 1 )
                {
                    $vehicle_name ="Car";
                }
                else if($vehicleRow->vehicle_type==2)
                {
                    $vehicle_name ="Bike";
                }
                else if($vehicleRow->vehicle_type==3)
                {
                    $vehicle_name ="Bus";
                }

                $totalVehicle[] = "<tr><td>".($key+1)."</td><td>".$vehicle_name."</td><td>".$vehicleRow->vehicle_type."</td><td>".$vehicleRow->yom."</td><td>".$vehicleRow->dop."</td><td>".$vehicleRow->created_at."</td><td>".$vehicleRow->updated_at."</td><td><button class='btn btn-primary editVehicle mb-2' id='editVehicle' value=".$vehicleRow->id." data-vehicle_type=".$vehicleRow->vehicle_type." data-yom=".$vehicleRow->yom." data-dop=".$vehicleRow->dop.">Edit</button>
                <button  class='btn btn-danger deleteVehicle' value =".$vehicleRow->id.">Delete</button></td></tr>";
            }
            return response()->json(['success' => '1',"totalvahicle"=> $totalVehicle]);
            
        }

       }
    }
    public function dashboard()
    {    
                $userid = Auth::user()->id;
                $vehicles = Vehicle::where('user_id',$userid)->get();
                return  view('dashboard')
                ->with("vehicles",$vehicles);
          
    }
    public function userLogout(Request $request)
    {
       
            Auth::logout();
            Session::forget('uname');
            return redirect('/');
        
    }
    public function deleteVehicle($id)
    {
        $vehicles = Vehicle::find($id);
        $vehicles->delete();
        $totalVehicle = [];
        $userid = Auth::user()->id;
           
        $vehicledata = Vehicle::where('user_id',$userid)->get();
           $i=1;
        foreach( $vehicledata as $key=>$vehicleRow)
        {
            if($vehicleRow->vehicle_type== 1 )
            {
                $vehicle_name ="Car";
            }
            else if($vehicleRow->vehicle_type==2)
            {
                $vehicle_name ="Bike";
            }
            else if($vehicleRow->vehicle_type==3)
            {
                $vehicle_name ="Bus";
            }

            $totalVehicle[] = "<tr><td>".$i."</td><td>".$vehicle_name."</td><td>".$vehicleRow->vehicle_type."</td><td>".$vehicleRow->yom."</td><td>".$vehicleRow->dop."</td><td>".$vehicleRow->created_at."</td><td>".$vehicleRow->updated_at."</td><td><button class='btn btn-primary editVehicle mb-2' id='editVehicle' value=".$vehicleRow->id." data-vehicle_type=".$vehicleRow->vehicle_type." data-yom=".$vehicleRow->yom." data-dop=".$vehicleRow->dop.">Edit</button><button  class='btn btn-danger deleteVehicle' value =".$vehicleRow->id.">Delete</button></td></tr>";
            $i++;
        }
        return response()->json(['success' => '1',"totalvahicle"=> $totalVehicle]);
        

      
    }
    public function register()
    {
       return view('registration');
      
    }
    public function registerForm(Request $request)
    {
        if($request->isMethod('post'))
        {
            $request_data = $request->all();
	    
        $messages = [
                    'fname.required'=> 'Please enter name',
                    'email.required' => 'Please enter email',                    
                    'password.required' => 'Please enter password',
                    'password.min'=>'Password should be 8 character',
                    'email.unique'=>'Email is already exists'
            ];
            $validator = Validator::make($request_data, [
                    'email' => 'required|unique:users',
                    'password' => 'required|min:8', 
                    'fname' => 'required'	
            ], $messages);

        if($validator->fails())
        {
            return response()->json(['errors' => $validator->errors()]);
        }
        else
            {
                $user = New User();
                $user->name = $request->fname;
                $user->email = $request->email;
                $user->password = hash::make($request->password);
                $user->save();
                Session::put("uname",$request->fname);

                if (Auth::attempt(['email' => $request->email, 'password' => $request->password]))
                return response()->json(['success' =>'User Added successfully']);
            }
        }
    }
    public function searchData(Request $request)
    {
        if($request->isMethod('post'))
        {
            
            $userid = Auth::user()->id;
           
            $vehicledata = [];
           
            if($request->searchdata =="updated_date")
            {   
                $vehicledata=  Vehicle::where('user_id',$userid)
                 ->whereDate('created_at', '=', date('Y-m-d'))
                 ->orderBy('created_at','desc')->get();
            }
            if($request->searchdata =="by_descending_year")
            {
                $vehicledata=  Vehicle::where('user_id',$userid)
                    ->whereYear('yom', '=',date('Y-m-d'))
                    ->orderBy('yom','desc')->get();

                    
            }
            
            $i=1;
            foreach( $vehicledata as $key=>$vehicleRow)
            {
                if($vehicleRow->vehicle_type== 1 )
                {
                    $vehicle_name ="Car";
                }
                else if($vehicleRow->vehicle_type==2)
                {
                    $vehicle_name ="Bike";
                }
                else if($vehicleRow->vehicle_type==3)
                {
                    $vehicle_name ="Bus";
                }
    
                $totalVehicle[] = "<tr><td>".$i."</td><td>".$vehicle_name."</td><td>".$vehicleRow->vehicle_type."</td><td>".$vehicleRow->yom."</td><td>".$vehicleRow->dop."</td><td>".$vehicleRow->created_at."</td><td>".$vehicleRow->updated_at."</td><td><button class='btn btn-primary editVehicle mb-2' id='editVehicle' value=".$vehicleRow->id." data-vehicle_type=".$vehicleRow->vehicle_type." data-yom=".$vehicleRow->yom." data-dop=".$vehicleRow->dop.">Edit</button><button  class='btn btn-danger deleteVehicle' value =".$vehicleRow->id.">Delete</button></td></tr>";
                $i++;
            }
            return response()->json(['success' => '1',"totalvahicle"=> $totalVehicle]);
            
            
        }
    }
}
