<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminController extends ApiResponse
{
    public function index()
    {
        $admins=User::whereHas("roles", function($q){ $q->where("name","super-admin"); })->with('roles')->paginate(3);
        return $this->handleResponse($admins,'admins');
    }
    public function show(User $admin)
    {
        $admin->roles;
        return  $this->handleResponse($admin,'admin');
    }
    public function store(Request $request)
    {
        $data=$request->validate([
            'name'=>'required|string|min:3',
            'email'=>'required|string|unique:users,email',
            'phone'=>'required|max:11|min:11',
            'city_id'=>'required|exists:cities,id',
            'address'=>'required|string',
            'password'=>'required|min:8',
            'roles'=>'required',
            'roles.*'=>'exists:roles,id'
           ]);

        $data['password']=bcrypt($request->password);
        DB::beginTransaction();
        $admin=User::create($data);
        $admin->assignRole($data['roles']);
        DB::commit();
        if($admin->id)
        {
            $admin->roles;
            return $this->handleResponse($admin,'Admin saved successfully');
        }
        return $this->handleError('error','Something went wrong try again');
    }
    public  function update(User $admin,Request $request)
    {

        $request->validate([
            'roles'=>'required',
            'roles.*'=>'exists:roles,id'
        ]);
        $admin->syncRoles($request->roles);
        return $this->handleResponse($admin,'Admin roles updated successfully');
    }
    public function destroy(Admin $admin)
    {
        return $admin->delete()?
            $this->handleResponse('deleted','Admin deleted successfully'):
            $this->handleError('delete','Something went wrong try again');
    }

}
