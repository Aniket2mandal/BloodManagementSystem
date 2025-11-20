<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\GeoHelper;
use App\Http\Controllers\Controller;
use App\Models\Bloodbank;
use App\Models\Camp;
use Illuminate\Http\Request;

class FrontBloodBankController extends Controller
{
    public function index(){
      
        $bloodbanks=Bloodbank::where('status',1)->get();
        // dd($bloodbanks);
        return view('frontend.bloodbank.index',compact('bloodbanks'));
    }

    public function detail($id){
        // dd($id);
        $camps=Camp::with('bloodBanks')->get();
        $bloodbank=BloodBank::where('status', 1)->where('id',$id)->with('bloods')->first();
        // dd($bloodbank);
        return view('frontend.bloodbank.detail',compact('bloodbank', 'camps'));
    }

    
    public function searchBlood(Request $request)
    {
        // dd($request->all());
        $district = $request->input('district');
        $state = $request->input('state');
        // $bloodgroup = $request->input('group');
        $Location = $request->input('location');
        // dd($currentLocation);
        $currentLocation=$Location.','.'Nepal';
        // dd($currentLocation);
        if (!$district || !$state  || !$currentLocation) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

            // 1. Get coordinates of user current location
            $userCoords = GeoHelper::getCoordinates($currentLocation);
            // dd($userCoords);

            if (!$userCoords) {
                return response()->json(['error' => 'Unable to find coordinates for the current location'], 400);
            }

         // 2. Get bloodbanks filtered by district, state, active status & blood group availability
         $bloodBanks = Bloodbank::where('status', 1)
         ->where('district', $district)
         ->where('state', $state)
         ->with('bloods')
         ->get();
        //  dd($bloodBanks);

     // 3. Calculate distance of each bloodbank from user location
     foreach ($bloodBanks as $bank) {
         if ($bank->lat && $bank->lng) {
             $bank->distance = GeoHelper::haversineDistance(
                 $userCoords['lat'], $userCoords['lng'],
                 $bank->lat, $bank->lng
             );
         } else {
             $bank->distance = INF;
         }
     }

     // 4. Sort bloodbanks by distance ascending
     $sorted = $bloodBanks->sortBy('distance')->values();

        return response()->json([
            'status' => true,
            'data' => $sorted,

        ]);
    }
}
