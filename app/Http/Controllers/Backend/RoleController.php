<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::latest()->paginate(5);
        return view('backend.role.index',compact('roles'));
    }

    public function create(){
        $permissions=Permission::all();
        return view('backend.role.create',compact('permissions',));
    }

    public function store(Request $request){
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:roles,slug',
            'permission'=>'nullable|array'
        ]);
        // dd($request->Permission);
        // dd($request->all());
       $role=new Role;
       $role->Name=$request->name;
       $role->Slug=$request->slug;
       if ($request->has('permission') && !empty($request->permission)) {
       
        $role->syncPermissions($request->permission);  
    }
       $role->save();
        return redirect()->route('role.index')->with('success', $request->Name.' '.'Role Created Successfully with permission');
    }

    public function edit($id){
        $role = Role::find($id);
        $permissions = Permission::all();
        return view('backend.role.edit', compact('role', 'permissions'));
    }

    public function update(Request $request,$id){
        $request->validate([
            'name' => 'required|string',
            'slug' => 'required|string|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:roles,slug,'. $id,
            'permission'=>'nullable|array'
        ]);
        $role = Role::find($id);
        $role->name = $request->name;
        $role->slug = $request->slug;
        if ($request->has('permission') && !empty($request->permission)) {
            $role->syncPermissions($request->permission);
        }else{
            $role->syncPermissions([]);
        }
        $role->save();
        return redirect()->route('role.index')->with('success', $request->Name.' '.'Role Updated Successfully with permission');
    }

    public function delete($id){
        $role = Role::find($id);
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role Deleted Successfully');
    }
}
