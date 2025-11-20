<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GeoHelper;
use App\Http\Controllers\Controller;
use App\Models\Bloodbank;
use HTMLPurifier;
use Illuminate\Http\Request;

class BloodbankController extends Controller
{
    public function index()
    {
        $bloodbanks = Bloodbank::paginate(5);
        return view('backend.bloodbank.index', compact('bloodbanks'));
    }

    public function create()
    {
        // $bloodbank = Bloodbank::where('slug', $slug)->first();
        return view('backend.bloodbank.create');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string',
            'district' => 'required|string',
            'state' => 'required|string',
            'city'=> 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'integer',
            'description' => 'required|string|min:10|max:65535',
            'slug' => 'required|string|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:bloodbanks,slug',
        ]);
        // dd($request->all());

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/bloodbank'), $imageName);
        } else {
            $imageName = null;
        }
        $purifier = new HTMLPurifier();
        $cleanDescription = $purifier->purify($request->description);
        $fullAddress= $request->city . ', ' . $request->district . ', ' . $request->state.', Nepal';
        $coords = GeoHelper::getCoordinates($fullAddress);
        
        $bloodbank = new Bloodbank();
        $bloodbank->name = $request->name;
        $bloodbank->district = $request->district;
        $bloodbank->state = $request->state;
        $bloodbank->city = $request->city;
        $bloodbank->image = $imageName;
        $bloodbank->status = $request->status;
        $bloodbank->description = $cleanDescription;
        $bloodbank->slug = $request->slug;

        if ($coords) {
            $bloodbank->lat = $coords['lat'];
            $bloodbank->lng = $coords['lng'];
        }

        $bloodbank->save();
        return redirect()->route('bloodbank.index')->with('success', 'Bloodbank Created Successfully with Role');
    }

    public function edit($slug)
    {
        $bloodbank = Bloodbank::where('slug', $slug)->first();
        return view('backend.bloodbank.edit', compact('bloodbank'));
    }

    public function update(Request $request, $slug)
    {
        // dd($request->all());
        $bloodbank = Bloodbank::where('slug', $slug)->first();
        $request->validate([
            'name' => 'required|string',
            'district' => 'required|string',
            'state' => 'required|string',
            'city'=> 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'integer',
            'description' => 'required|string|min:10|max:65535',
            'slug' => 'required|string|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/|unique:bloodbanks,slug,' .$bloodbank->id,
        ]);
        // dd($request->all());
    

        if ($request->hasFile('image')) {
            // If there is an existing image, delete it
            if ($bloodbank->image && file_exists(public_path('images/bloodbank/' . $bloodbank->image))) {
                unlink(public_path('images/bloodbank/' . $bloodbank->image));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/bloodbank'), $imageName);
        } else {
            $imageName = old('image', $bloodbank->image);
        }
        $purifier = new HTMLPurifier();
        $cleanDescription = $purifier->purify($request->description);

        $fullAddress= $request->city . ', ' . $request->district . ', ' . $request->state.', Nepal';
        $coords = GeoHelper::getCoordinates($fullAddress);

        $bloodbank->name = $request->name;
        $bloodbank->district = $request->district;
        $bloodbank->state = $request->state;
        $bloodbank->city = $request->city;
        $bloodbank->image = $imageName;
        $bloodbank->status = $request->status;
        $bloodbank->description = $cleanDescription;
        $bloodbank->slug = $request->slug;

        if ($coords) {
            $bloodbank->lat = $coords['lat'];
            $bloodbank->lng = $coords['lng'];
        }

        $bloodbank->save();
        return redirect()->route('bloodbank.index')->with('success', 'Bloodbank Updated Successfully');
    }

    // TO UPDATE THE STATUS OF POST
    public function status(Request $request, $id)
    {
        // Log::info('Received request with status: ' . $request->Status);
        $request->validate([
            'Status' => 'integer',
        ]);
        // dd($request->Status);
        $post = Bloodbank::find($id);
        if ($post) {
            // Update the status field
            $post->Status = $request->Status;
            $post->save();  // Save the changes to the database
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function delete($id)
    {
        //   dd($id);
        $post =  Bloodbank::find($id);
        // $this->authorize('delete',$post);
        $post->delete();

        return response()->json(['success' => true]);
        // return redirect()->route('post.index')->with('success', 'Post Deleted Successfully')
    }
}
