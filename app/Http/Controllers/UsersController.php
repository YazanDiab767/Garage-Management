<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use \App\User;
use Illuminate\Support\Facades\Hash;


class UsersController extends Controller
{
    //show all employees
    public function index()
    {
        return view('employees' , [
            'employees' => User::paginate(User::$paginate)
        ]);
    }

    //get more employees
    public function getMore(Request $request) 
    {
        if ( $request->post() )
        {
            return view('layouts.divs.employee' , [
                'employees' => User::paginate(User::$paginate)
            ]);
        }
    }

    //add new employee
    public function add(Request $request)
    {
        if ( $request->post() )
        {

            $employee = User::create([
                'name' => $request->name,
                'email' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => ( $request->address ) ? $request->address : "",
                'password' => Hash::make($request->password),
                'type' => $request->type
            ]);

            return view( 'layouts.divs.employee' , [ 'employees' => [$employee] ] );
        }
    }

    //get employee based on employee id
    public function get(Request $request)
    {
        if ($request->ajax())
        {
            return User::find($request->id);
        }
    }


    //update employee
    public function update(Request $request, $id)
    {
        if ($request->ajax())
        {
            $employee = User::find($id);

            $employee->update([
                'name' => $request->name,
                'email' => $request->name,
                'phone_number' => $request->phone_number,
                'address' => ( $request->address ) ? $request->address : "",
                'type' => $request->type
            ]);
            
            if ( $request->password )
            {
                $employee->password = Hash::make($request->password);
                $employee->save();
            }

            return view( 'layouts.divs.employee' , [ 'employees' => [$employee] ] );
        }
    }

    //delete employee
    public function delete(Request $request,$id)
    {
        if ($request->ajax())
        {
            $employee = User::find( $id );
            $employee->delete();
        }
    }

    //filter employee
    public function filter(Request $request)
    {
        if ( $request->ajax() )
        {
            $text = $request->text;
            if ($text)
                $employee = User::where('name','LIKE','%'.$text.'%')
                    ->orWhere('phone_number','LIKE','%'.$text.'%')
                    ->orWhere('address','LIKE','%'.$text.'%')
                    ->orWhere('type','LIKE','%'.$text.'%')
                    ->get();
            else
                return view('layouts.divs.employee' , [
                    'employees' => User::paginate(User::$paginate)
                ]);

            return view( 'layouts.divs.employee' , [ 'employees' => $employee ] );
        }
    }

    //update password
    public function updatePassword(Request $request)
    {
        if ($request->ajax())
        {
            $user = auth()->user();
            if ( Hash::check( $request->current_password , $user->password ) )
            {
                $user->password = Hash::make( $request->new_password );
                $user->save();
            }
            else
            {
                throw ValidationException::withMessages(['password' => 'كلمة المرور الحالية خاطئة']);
            }
        }
    }
}
