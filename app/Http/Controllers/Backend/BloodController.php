<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blood;
use App\Models\Bloodbank;
use App\Models\Donor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BloodController extends Controller
{
    public function index()
    {
        $blood = Blood::with('bloodBanks')->paginate(5);
        // dd($blood);
        $user = auth()->user();
        $userBloodBanks = $user->bloodBank()->where('status', 1)->get();

        $donatedByGroup = Donor::with('bloodBanks')->get()
        ->groupBy('blood') // Group by blood type
        ->map(function ($donors) {
            $bloodBankQuantities = [];

            foreach ($donors as $donor) {
                foreach ($donor->bloodBanks as $bloodBank) {
                    $bankName = $bloodBank->name;
                    $quantity = $bloodBank->pivot->quantity_donated ?? 0;

                    if (!isset($bloodBankQuantities[$bankName])) {
                        $bloodBankQuantities[$bankName] = 0;
                    }

                    $bloodBankQuantities[$bankName] += $quantity;
                }
            }

            return $bloodBankQuantities;
        });

// dd($donatedByGroup);

        return view('backend.blood.index', compact('blood','donatedByGroup','userBloodBanks'));
    }

    public function create()
    {
        $bloodbank = Bloodbank::where('status', 1)->get();
        // dd($bloodbank);
        return view('backend.blood.create', compact('bloodbank'));
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer',
            'status' => 'integer',
            'bloodbank' => 'required|array',
            'description'=> 'nullable|string|max:65535',
        ]);

        // dd($request);
        // Check for existing blood type in the selected blood banks

        // foreach ($request->bloodbank as $bloodbankId) {
        //     $existingBlood = DB::table('blood_bloodbanks')
        //         ->where('blood_id', $request->blood_id)  // or $request->name if using name as identifier
        //         ->where('bloodbank_id', $bloodbankId)
        //         ->exists();

        //     if ($existingBlood) {
        //         return redirect()->back()->with('error', 'This blood type already exists in the selected blood bank.');
        //     }
        // }

        $blood = new Blood;
        $blood->name = $request->name;
        $blood->status = $request->status;
        $blood->description = $request->description;
        $blood->save();

        // Assign the same quantity to all bloodbanks
        $bloodBankData = [];
        foreach ($request->bloodbank as $bloodbankId) {
            $bloodBankData[$bloodbankId] = ['quantity' => $request->quantity]; // Using the single quantity
        }

        // Sync with pivot table
        $blood->bloodBanks()->sync($bloodBankData);

        return redirect()->route('blood.index')->with('success', 'Blood Added Successfully');
    }

    public function edit($id){
     $blood=Blood::where('status',1)->with('bloodBanks')->find($id);
     if($blood==null){
        return redirect()->back()->with('error','Blood Not Found');
     }
     $quantity=$blood->bloodBanks->pluck('pivot.quantity')->first();;
    //  dd($quantity);
    //  dd($blood->bloodBanks->pivot);
     $bloodbank=Bloodbank::all();
    //  dd($blood);
    return view('backend.blood.edit',compact('blood','bloodbank','quantity'));

    }

    public function update(Request $request,$id){
        // dd("Hello");
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            // 'quantity' => 'required|integer',
            'status' => 'integer',
            // 'bloodbank' => 'required|array',
            'description'=> 'nullable|string|max:65535',
        ]);

        $blood=Blood::find($id);
    // dd($blood);
        if($blood) {
            $blood->name = $request->name;
            $blood->status = $request->status;
            $blood->description = $request->description;
            $blood->save();
            // $bloodBankData = [];
            // foreach ($request->bloodbank as $bloodbankId) {
            //     $bloodBankData[$bloodbankId] = ['quantity' => $request->quantity]; // Using the single quantity
            // }

            // $blood->bloodBanks()->sync($bloodBankData);

            return redirect()->route('blood.index')->with('success', 'Blood Updated Successfully');
        } else {
            return redirect()->back()->with('error', 'Blood not found.');
        }
    }

    public function delete($id){
        $blood=Blood::find($id);
         if($blood){
            $blood->delete();
            return redirect()->back()->with('success','Blood Deleted Successfully');
         }
         else{
            return redirect()->back()->with('error','Blood Not Found');
         }
    }

    public function status(Request $request, $id)
    {
        // Log::info('Received request with status: ' . $request->Status);
        // dd("Hello dd");
        $request->validate([
            'Status' => 'integer',
        ]);
        // dd($request->Status);
        $post = Blood::find($id);
        if ($post) {
            // Update the status field
            $post->status = $request->Status;
            $post->save();  // Save the changes to the database
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }



    public function addBloodQuantity(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'quantity' => 'required|integer',
            'bloodbank' => 'required|integer|exists:bloodbanks,id',
            'blood_id' => 'required|integer|exists:bloods,id',
        ]);
        // dd($request->all());
        $blood = Blood::find($request->blood_id);

        // Check if this bloodbank already has this blood
        $exists = $blood->bloodBanks()->where('bloodbank_id', $request->bloodbank)->exists();

        if ($exists) {
            // Update existing pivot record
            $blood->bloodBanks()->updateExistingPivot($request->bloodbank, [
                'quantity' => $request->quantity,
            ]);
        } else {
            // Attach new pivot record
            $blood->bloodBanks()->attach($request->bloodbank, [
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $exists ? 'Blood quantity updated successfully' : 'Blood bank added successfully',
        ]);
    }


}

// <?php

// namespace App\Http\Controllers\Backend;

// use App\Http\Controllers\Controller;
// use App\Models\Blood;
// use App\Models\Bloodbank;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;

// class BloodController extends Controller
// {
//     public function index()
//     {
//         $blood = Blood::with('bloodBanks')->get();
//         // dd($blood);
//         return view('backend.blood.index', compact('blood'));
//     }

//     public function create()
//     {
//         $bloodbank = Bloodbank::where('status', 1)->get();
//         // dd($bloodbank);
//         return view('backend.blood.create', compact('bloodbank'));
//     }

//     public function store(Request $request)
//     {

//         // dd($request->all());
//         $request->validate([
//             'name' => 'required|string',
//             'quantity' => 'required|integer',
//             'status' => 'integer',
//             'bloodbank' => 'required|array',
//             'description'=> 'nullable|string|max:65535',
//         ]);

//         // dd($request);
//         // Check for existing blood type in the selected blood banks

//         // foreach ($request->bloodbank as $bloodbankId) {
//         //     $existingBlood = DB::table('blood_bloodbanks')
//         //         ->where('blood_id', $request->blood_id)  // or $request->name if using name as identifier
//         //         ->where('bloodbank_id', $bloodbankId)
//         //         ->exists();

//         //     if ($existingBlood) {
//         //         return redirect()->back()->with('error', 'This blood type already exists in the selected blood bank.');
//         //     }
//         // }

//         $blood = new Blood;
//         $blood->name = $request->name;
//         $blood->status = $request->status;
//         $blood->description = $request->description;
//         $blood->save();

//         // Assign the same quantity to all bloodbanks
//         $bloodBankData = [];
//         foreach ($request->bloodbank as $bloodbankId) {
//             $bloodBankData[$bloodbankId] = ['quantity' => $request->quantity]; // Using the single quantity
//         }

//         // Sync with pivot table
//         $blood->bloodBanks()->sync($bloodBankData);

//         return redirect()->route('blood.index')->with('success', 'Blood Added Successfully');
//     }

//     public function edit($id){
//      $blood=Blood::where('status',1)->with('bloodBanks')->find($id);
//      if($blood==null){
//         return redirect()->back()->with('error','Blood Not Found');
//      }
//      $quantity=$blood->bloodBanks->pluck('pivot.quantity')->first();;
//     //  dd($quantity);
//     //  dd($blood->bloodBanks->pivot);
//      $bloodbank=Bloodbank::all();
//     //  dd($blood);
//     return view('backend.blood.edit',compact('blood','bloodbank','quantity'));

//     }

//     public function update(Request $request,$id){
//         // dd("Hello");
//         // dd($request->all());
//         $request->validate([
//             'name' => 'required|string',
//             'quantity' => 'required|integer',
//             'status' => 'integer',
//             'bloodbank' => 'required|array',
//             'description'=> 'nullable|string|max:65535',
//         ]);

//         $blood=Blood::find($id);
//     // dd($blood);
//         if($blood) {
//             $blood->name = $request->name;
//             $blood->status = $request->status;
//             $blood->description = $request->description;
//             $blood->save();
//             $bloodBankData = [];
//             foreach ($request->bloodbank as $bloodbankId) {
//                 $bloodBankData[$bloodbankId] = ['quantity' => $request->quantity]; // Using the single quantity
//             }

//             $blood->bloodBanks()->sync($bloodBankData);

//             return redirect()->route('blood.index')->with('success', 'Blood Updated Successfully');
//         } else {
//             return redirect()->back()->with('error', 'Blood not found.');
//         }
//     }

//     public function delete($id){
//         $blood=Blood::find($id);
//          if($blood){
//             $blood->delete();
//             return redirect()->back()->with('success','Blood Deleted Successfully');
//          }
//          else{
//             return redirect()->back()->with('error','Blood Not Found');
//          }
//     }

//     public function status(Request $request, $id)
//     {
//         // Log::info('Received request with status: ' . $request->Status);
//         // dd("Hello dd");
//         $request->validate([
//             'Status' => 'integer',
//         ]);
//         // dd($request->Status);
//         $post = Blood::find($id);
//         if ($post) {
//             // Update the status field
//             $post->status = $request->Status;
//             $post->save();  // Save the changes to the database
//             return response()->json(['success' => true]);
//         }
//         return response()->json(['success' => false], 404);
//     }


// }

