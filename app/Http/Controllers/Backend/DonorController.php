<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Bloodbank;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DonorController extends Controller
{
    public function index(){

        // $bloodbank
    // $donor=Donor::with('');
    $donor=Donor::with('bloodBanks')->latest()->paginate(5);
    // $bloodbanks=Bloodbank::where('status',1)->get();
    $user = auth()->user();
    $userBloodBanks = $user->bloodBank()->where('status', 1)->get();
    
    // dd($bloodbank);

    return view('backend.donor.index',compact('donor','userBloodBanks'));
    }

    public function create(){
        // $bloodbank=Bloodbank::where('status',1)->get();
        $donor=null;
        $user = auth()->user();
        $userBloodBanks = $user->bloodBank()->where('status', 1)->get();
        // dd($bloodbank);
        return view('backend.donor.create',compact('userBloodBanks','donor'));
    }

    public function store(Request $request){
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|string',
            'address'=>'required|string',
            'password'=> 'required|string|min:8',
            // 'status' => 'required|integer',
            'allergies' => 'required|array',
            'bloodgroup' => 'required|string',
            'quantity' => 'required|integer',
            'bloodbank' => 'required|integer',
            'date'=>'required|date',
        ]);

        // dd($request->all());
        $donor = new Donor();
        $donor->name = $request->name;
        $donor->email = $request->email;
        $donor->phone = $request->phone;
        $donor->address = $request->address;
        $donor->password = Hash::make($request->password);
        $donor->allergy = json_encode($request->allergies);
        $donor->blood = $request->bloodgroup;
        // $donor->quantity_donated = $request->quantity;
        $donor->save();
        $donor->bloodBanks()->sync([
            $request->bloodbank => ['donation_date' => $request->date, 'quantity_donated' => $request->quantity]
        ]);
        return redirect()->route('donor.index')->with('success', 'Donor created successfully');
    }

    public function edit($id){
 
        $donor=Donor::with('bloodBanks')->find($id);
        // dd($donor);
        // $bloodbank=Bloodbank::where('status',1)->get();
        $user = auth()->user();
        $userBloodBanks = $user->bloodBank()->where('status', 1)->get();
        return view('backend.donor.edit',compact('donor','userBloodBanks'));
    }

    public function update(Request $request,$id){
        
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email',
            'phone' => 'required|string',
            'address'=>'required|string',
            // 'password'=> 'required|string|min:8',
            // 'status' => 'required|integer',
            'allergies' => 'required|array',
            'bloodgroup' => 'required|string',
            // 'quantity' => 'required|integer',
            // 'bloodbank' => 'required|integer',
            // 'date'=>'required|date',
        ]);

        $donor = Donor::find($id);
        $exists = $donor->bloodBanks()->where('bloodbank_id', $request->bloodbank)->exists();
   
        $donor->name = $request->name;
        $donor->email = $request->email;
        $donor->phone = $request->phone;
        $donor->address = $request->address;
        if($request->password){
            $donor->password = Hash::make($request->password);
        }
        $donor->allergy = json_encode($request->allergies);
        $donor->blood = $request->bloodgroup;
        // $donor->quantity_donated = $request->quantity;
        $donor->save();
        // $donor->bloodBanks()->sync([
        //     $request->bloodbank => ['donation_date' => $request->date, 'quantity_donated' => $request->quantity]
        // ]);
        
        return redirect()->route('donor.index')->with('success', 'Donor updated successfully');
    }

    public function search(Request $request)
    {

        $search = $request->input('search');
        if (strlen($search) >= 2) {
            $donors = Donor::where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->with('bloodBanks:name')
                    ->latest()
                    ->get();
        } else {
            // If search is less than 2 characters, maybe return all or none:
            $donors = Donor::with('bloodBanks:name')->latest()->get();
        }

       return response()->json([
            'success' => true,
            'data' => $donors
        ]);
    }


    public function addBloodBank(Request $request){
        // dd($request->all());
        $request->validate([
            'email' => 'required|string|email',
            'quantity' => 'required|integer',
            'bloodbank' => 'required|integer|exists:bloodbanks,id',
        ]);
        // dd($request->all());
        $donor = Donor::where('email', $request->email)->first();
        // dd($donor); 
        $donor->bloodBanks()->attach($request->bloodbank, ['donation_date' => $request->date,'quantity_donated' => $request->quantity,]);
        return response()->json([
            'success' => true,
            'message' => 'Blood bank added successfully'
        ]);
    }

  


}
