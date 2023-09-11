<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\saveOtp;
use Illuminate\Support\str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Session;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)

    {
        $validate=validator::make($request->all(),[
            'name'=>['required'],
            'email'=>['required' , 'email','unique:users,email'],
            'password'=>['required' , 'min:6' ,'confirmed'] ,
            'password_confirmation'=>['required']
        ]);
        if($validate->fails()){
            return response()->json($validate->messages(),400);
        }else{
            $data=[
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),

            ];
            DB::beginTransaction();
           try{
               $user= user::create($data);
                DB::commit();
           } catch(\Exception $e){
                p($e->getMessage());
                $user=null;
           }
           if($user !=null){
                return  response()->json([
                    'message'=>'user resistration successfully',
                ],200);
           }else{
                return response()->json([
                    'message'=>'Internal server error',
                ],500);
           }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updatedata(Request $request, string $id)
    {
        $user=user::find($id);
        if($user == null){
            return response()->json([
                'status'=>0,
                 'message'=>'user does not exists',
            ]);
        }else{
            DB::beginTransaction();
            try{
                if($user->name==$request->name){
                    return response()->json([
                        'message'=>'Your old name and new name is same '
                    ],200);
                }else{
                    $user->name=$request->name;
                    $user= $user->save();
                    DB::commit();
                }

            }catch(\Exception $e){
              Db::rollBack();
              $user=null;
            }
            if(is_null($user)){
                return response()->json([
                    'message'=>'Internal server error',
                    'err_message'=>$e->getMessage(),
                ],500);
            }else{
                return response()->json([
                    'message'=>'user data updated successfully '
                ],200);
            }
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    // reset password

    public function change_password(Request $request, $id){
        $user=user::find($id);
        if(is_null($user)){
            return response()->json([
                'status'=>0,
                // 'message'=>'user does not exists'
            ],200);
        }else{
            if(!Hash::check($request->password,$user['password'])){
                    return response()->json([
                    'status'=>0,
                    'message'=>' old password does not same',
                ]);
            }else{
                if($request->new_password==$request->password_confirmation){
                    $user->password=$request->new_password;
                    $user->save();
                    DB::commit();
                }else{
                    return response()->json([
                        'status'=>0,
                        'message'=>'The new password and password_confirmation does not same',
                    ]);
                }
            }
            if(is_null($user)){
                return response()->json([
                    'message'=>'Internal server error',

                ],500);
            }else{
                return response()->json([
                    'message'=>'user password updated successfully '
                ],200);
            }
        }
    }

    // forget password

    public function forget_password(Request $request ){

        try{
            $user=user::where('email',$request->email)->get();

            if(count($user)>0){
                $token=str::random(30);
                $otp=rand(10,9999);
                $domen=URL::to('/');
                $url=$domen.'/forgetPassword?token='.$token;

                $data['url']=$url;
                $data['email']=$request->email;
                $data['title']='forget password';
                // $data['body']='please click on below link for forget password';

                Mail::send('forgetpasswordMail',['data'=>$data,'otp'=>$otp],function($message) use ($data){
                    $message->to( $data['email'])->subject( $data['title']);
                });

                saveOtp::updateOrCreate(
                ['email'=>$request->email],
                [
                    'email'=>$request->email,
                    'otp'=>$otp,

                ]);
                Session::put('otp',$otp);
                return response()->json([
                    'success'=> true,
                    'message'=>'Please check your email for OTP',
                ]

            );

            }else{
                return response()->json([
                    'success'=> false,
                    'message'=>'user not found'
                ]);
            }

        }catch(\Exception $e){
            return response()->json([
                'success'=> false,
                'message'=>$e->getMessage()
            ]);
        }

    }

    public function verifyEmailOtp(Request $request){
        $data=saveOtp::where('email',$request->email)->first();
        // dd($data);
        if($data){
            $userData=user::where('email',$request->email)->first();
            if($data->otp==$request->otp && $userData){
                $userData->password=$request->new_password;
                $userData->update();
                $data->delete();
                return response()->json([
                    'success'=>true,
                    'message'=>'Your password updated successfully'
                ]);

            }else{
                return response()->json([
                    'success'=>false,
                    'message'=>'Please enter correct otp'
                ]);
            }
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'user not found'
            ]);
        }
    }
}
