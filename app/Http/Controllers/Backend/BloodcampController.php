<?php

namespace App\Http\Controllers\Backend;
use App\Http\Controllers\Controller;
use App\Models\Bloodbank;
use App\Models\Camp;
use Illuminate\Http\Request;

class BloodcampController extends Controller
{
    public function index(){
        $camps=Camp::with('bloodBanks')->paginate(5);
        // dd($camps);
        return view('backend.bloodcamps.index',compact('camps'));
    }

    public function create(){
        $camps=null;
        $bloodbank=Bloodbank::where('status',1)->get();
        $user = auth()->user();
        $userBloodBanks = $user->bloodBank()->where('status', 1)->get();
        return view('backend.bloodcamps.create',compact('camps','userBloodBanks','bloodbank'));
    }

    public function store(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
            'address'=>'required|string|max:255',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'=>'nullable|string|max:1000',
            'status'=>'required|boolean',
            'date'=>'required|date',
            'time'=>'required',
            'bloodbank'=>'required|integer',
        ]);

        $camp = new Camp();
        $camp->name = $request->name;
        $camp->address = $request->address;
        $camp->description = $request->description;
        $camp->status = $request->status;
        $camp->date = $request->date;
        $camp->time = $request->time;   
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images/camps'), $imageName);
            $camp->image = $imageName;
        }
        $camp->save();
        $camp->bloodBanks()->sync($request->bloodbank);
        return redirect()->route('bloodcamp.index')->with('success','Blood camp created successfully');
    }

    public function status(Request $request,$id){

        $request->validate([
            'Status' => 'integer',
        ]);
        $camp = Camp::find($id);
        if ($camp) {
            $camp->Status = $request->Status;
            $camp->save();  
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
       
    }

    public function edit($id){
      
        $camps=Camp::with('bloodBanks')->find($id);
        $bloodbanks=Bloodbank::where('status',1)->get();
        $user = auth()->user();
        $userBloodBanks = $user->bloodBank()->where('status', 1)->get();
        return view('backend.bloodcamps.edit',compact('camps','userBloodBanks','bloodbanks'));
    }

    public function update(Request $request, $id){
        // dd($request->all());
        $request->validate([
            'name'=>'required|string|max:255',
            'address'=>'required|string|max:255',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'description'=>'nullable|string|max:1000',
            'status'=>'required|boolean',
            'date'=>'required|date',
            'time'=>'required',
            'bloodbank'=>'required|integer',
        ]);

        $camp = Camp::find($id);

        $camp->name = $request->name;
        $camp->address = $request->address;
        $camp->description = $request->description;
        $camp->status = $request->status;
        $camp->date = $request->date;
        $camp->time = $request->time;   
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images/camps'), $imageName);
            $camp->image = $imageName;
        }
        
        $camp->save();
        $camp->bloodBanks()->sync($request->bloodbank);
        
        return redirect()->route('bloodcamp.index')->with('success','Blood camp updated successfully');
    }

    public function delete($id){
        $camp =  Camp::find($id);
        // dd($camp);
        $camp->delete();
        return response()->json(['success' => true]);
    }
}
