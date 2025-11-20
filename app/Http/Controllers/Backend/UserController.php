<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blood;
use App\Models\Bloodbank;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $user = User::where('email', '!=', 'super@gmail.com')->where('email', '!=', auth()->user()->email)->latest()->paginate(5);
        return view('backend.user.index', compact('user'));
    }

    
    public function create()
    {
        $roles = Role::all();
        $bloodbank=Bloodbank::where('status',1)->get();
        return view('backend.user.create',compact('roles','bloodbank'));
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'Name' => ['required', 'string', 'regex:/^[A-Za-z\s]+$/'],
            'Email' => ['required', 'email', 'regex:/^[A-Za-z][A-Za-z._]*@[A-Za-z]+\.[A-Za-z]{2,}$/'],
            'password' => 'required|min:8|confirmed', // No need for custom rules here
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'Status' => 'integer',
            'Role' => 'required|array',
            'bloodbank' => 'required|integer'
        ]);
        // dd($request->all());
        // dd($request->Role);
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/user'), $imageName);
        } else {
            $imageName = null;
        }

        // dd($request->all());
        $user = new User;
        $user->Name = $request->Name;
        $user->Email = $request->Email;
        $user->image=$imageName;
        $user->password = Hash::make($request->password);
        if (!$request->Role) {
            $user->assignRole('user');
        }
        $user->assignRole($request->Role);
        $user->Status = $request->Status;
        $user->save();
        $user->bloodBank()->sync($request->bloodbank);
       
        return redirect()->route('user.index')->with('success', $request->Name . ' ' . 'User Created Successfully with Role');
    }


    public function edit($id)
    {
        $user = User::find($id);
        // dd($user->roles);
        $roles = Role::all();
        $bloodbank=Bloodbank::where('status',1)->get();
        return view('backend.user.edit', compact('user', 'roles', 'bloodbank'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        if ($request->password) {
            $request->validate([
                'Name' => 'required|string',
                'Email' => 'required|email',
                'password' => '|min:8|confirmed', // No need for custom rules here
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'existing_image' => 'nullable|string',
                'Status' => 'integer',
                'Role' => 'required|array',
                 'bloodbank' => 'required|integer'
            ]);
        } else {
            // dd($request->all());
            $request->validate([
                'Name' => 'required|string',
                'Email' => 'required|email',
                // 'password' => '|min:8|confirmed', // No need for custom rules here
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'existing_image' => 'nullable|string',
                'Status' => 'integer',
                'Role' => 'required|array',
                 'bloodbank' => 'required|integer'
            ]);
            // dd($request->all());
        }
        // dd($request->Role);
        //  dd($request->existing_image);
        $user = User::find($id);
        // dd($user->password);
        // dd($request->all());
        if ($request->hasFile('image')) {
            if ($user->userimage && file_exists(public_path('images/user') . $user->userimage->image)) {
                if (is_file(public_path('images/user') . '/' . $user->userimage->image)) {
                    unlink(public_path('images/user') . '/' . $user->userimage->image); // Delete the old image
                }
                // unlink(public_path('images'). $user->userimage->image); // Delete the old image
            }
            // Store the new image
            // $imageName = $request->file('image')->store('images', 'public');
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/user'), $imageName);
        } else {
            $imageName = $request->input('existing_image');
        }
        // dd($request->all());

        $user->Name = $request->Name;
        $user->Email = $request->Email;
        $user->image = $imageName;
        if ($request->password) {
            $user->password = bcrypt($request->password);
            DB::table('sessions')->where('user_id', $user->id)->delete();
        }
        if ($user->roles->isNotEmpty()) {
            // Remove the first role in the collection
            $user->removeRole($user->roles->first());
            $user->assignRole($request->Role);
        }
        $user->assignRole($request->Role);
        $user->Status = $request->Status;
        $user->bloodBank()->sync($request->bloodbank);
        $user->save();

        return redirect()->route('user.index')->with('success', $request->Name . ' ' . 'User Updated Successfully with Role');
    }

    public function status(Request $request, $id)
    {
        $request->validate([
            'Status' => 'integer',
        ]);
        // dd($request->Status);
        $user = User::find($id);
        // dd($user->id);
        if ($user) {
            // Update the status field
            $user->Status = $request->Status;
            $user->save();  // Save the changes to the database
            return response()->json(['success' => true]);
            // Check if the status is being set to inactive
            if ($request->Status == 0) {  // Assuming 0 means inactive
                DB::table('sessions')->where('user_id', $user->id)->delete();
                // Log out the user
                return response()->json(['success' => true, 'logout' => true]);
            }
        }
        return response()->json(['success' => false], 404);
    }


    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return redirect()->route('user.index')->with('success', 'User Deleted Successfully');
    }
}
