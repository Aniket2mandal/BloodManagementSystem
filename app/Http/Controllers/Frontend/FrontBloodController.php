<?php

namespace App\Http\Controllers\Frontend;

use App\Helpers\GeoHelper;
use App\Http\Controllers\Controller;
use App\Models\Blood;
use App\Models\Bloodbank;
use App\Models\Camp;
use Illuminate\Http\Request;

class FrontBloodController extends Controller
{
    public function index()
    {
       
        $blood = Blood::where('status', 1)->with('bloodBanks')->get();
        return view('frontend.blood.index', compact('blood'));
    }

    public function searchBlood(Request $request)
    {
        // dd($request->all());
        $district = $request->input('district');
        $state = $request->input('state');
        $bloodgroup = $request->input('group');
        $Location = $request->input('location');
        $currentLocation=$Location.','.'Nepal';
        
        if (!$district || !$state || !$bloodgroup || !$currentLocation) {
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
         ->whereHas('bloods', function ($query) use ($bloodgroup) {
             $query->where('name', $bloodgroup)
                   ;
         })
         ->with(['bloods' => function ($query) use ($bloodgroup) {
            $query->where('name', $bloodgroup);
        }])
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
    //  dd($sorted);

        return response()->json([
            'status' => true,
            'data' => $sorted,

        ]);
    }

    public function detail($id){
        // dd($id);
        $camps=Camp::with('bloodBanks')->get();
        $blood=Blood::where('status', 1)->where('id',$id)->with('bloodBanks')->first();
        // dd($blood);
        // foreach ($blood->bloodBanks as $bloodBank){
        //     dd($bloodBank->image);
        // }
        return view('frontend.blood.detail',compact('blood', 'camps'));
    }
}
