<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends ApiResponse
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    function __construct()
    {

//        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);
//        $this->middleware('permission:role-create', ['only' => ['create','store']]);
//        $this->middleware('permission:role-edit', ['only' => ['edit','update']]);
//        $this->middleware('permission:role-delete', ['only' => ['destroy']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if($request->page) {
            $roles = Role::with('permissions')->paginate(3);
            return $this->handleResponse($roles, 'Roles');
        }
        return $this->handleResponse(Role::all(),'Roles');
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission'=>'required',
            'permission.*' => 'exists:permissions,id',
        ]);


        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        return $this->handleResponse($role,'Role Saved Successfully');

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Role $role)
    {

        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$role->id)
            ->get();
        $role['permissions']=$rolePermissions;
        return $this->handleResponse($role,'role');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,Role $role)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission'=>'required',
            'permission.*' => 'exists:permissions,id',
        ]);

        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));
        return $this->handleResponse($role,'Role Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Role $role)
    {

        return $role->delete()?$this->handleResponse('delete','Role Deleted Successfully'):$this->handleError('error','something went wrong try again');

    }
}
