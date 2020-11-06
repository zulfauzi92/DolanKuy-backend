<?php

namespace App\Http\Controllers;
use App\Models\Galery;
use App\Models\ListLocations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GaleryController extends Controller
{
    public function index()
    {
        $galery = Galery::all();
        return response()->json($galery);
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:png,jpeg,jpg',
            'list_location_id' => 'required|exists:ListLocations,id'
        ]);

        $file = $request->file('image');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/dolankuy/', $filename);
        $galery = Galery::create([
            'list_location_id' => $request->list_location_id,
            'filename' => $filename,
        ]);
        
        return response()->json($galery);
    }

    public function show($id)
    {
        $galery = Galery::find($id);
        return response()->json($galery);
    }

    
    public function update(Request $request, $id)
    {
        $galery = Galery::find($id);
        $request->validate([
            'image' => 'required|image|mimes:png,jpeg,jpg',
            'list_location_id' => 'required'
        ]);

        if($request->hasFile('image')) {
            Storage::delete('/public/dolankuy/' . $galery->filename);
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/dolankuy/', $filename);
            $galery->update([
                'list_location_id' => $request->list_location_id,
                'filename' => $filename,
            ]);
        }

        return response()->json($galery);

    }

    
    public function destroy($id)
    {
        $galery = Galery::find($id);
        Storage::delete('/public/dolankuy/' . $galery->filename);
        $galery->delete();
        return response()->json($galery);
    }
}