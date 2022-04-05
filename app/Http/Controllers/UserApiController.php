<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Validator;
//use App\Http\Requests\UserRequest;


class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
    public function search(Request $request)
    {
        $email = $request->email;
        $phone = $request->phone;

        $rec = User::when($email, function ($query, $email) {
                    return $query->whereRaw("email like '%".$email."%'")->orderBy('email');
                })
                ->when($phone, function ($query, $phone) {
                    return $query->whereRaw("phone like '%".$phone."%'")->orderBy('phone');
                })
                ->get(); 

        if(isset($rec) && count($rec) > 0)
        {
            $data = [
                'status' => true,
                'recs'    => $rec
            ];
        }
        else
        {
            $data = [
                'status' => false,
                'msg'    => 'There is no record!'
            ];
        }        
        return $data;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules=array(
            'name'  =>"required",
            'email' =>"required|unique:users|email"
        );

        $validator=Validator::make($request->all(),$rules);
        if($validator->fails())
        {
            return $validator->errors();
        }
        else{
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->age = $request->age;
            $user->save();
            if(isset($user))
            {
                $data = [
                    'status' => true,
                    'UserId' => $user->id
                ];
            }
            return $data;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $flag = User::where('id',$id)
                ->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'age'   => $request->age
                ]);

        if($flag > 0)
        {
            $data = [
                'status' => true,
                'msg'    => "This user is successfully updated."
            ];
        }
        else
        {
            $data = [
                'status' => false,
                'msg'    => "This user is not successfully updated. Please check"
            ];
        } 
        return $data;       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $flag = User::where('id',$id)->delete();

        if($flag > 0)
        {
            $data = [
                'status' => true,
                'msg'    => "This user is successfully deleted"
            ];
        }
        else
        {
            $data = [
                'status' => false,
                'msg'    => "This user is not successfully deleted. Please check"
            ];
        }
        return $data;
    }
}
