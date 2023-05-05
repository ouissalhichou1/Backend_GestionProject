<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Annonce;

class AnnonceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $annonces = Annonce::all();
        return response()->json(['data' => $annonces], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'title' => 'required',
            'message' => 'required',
            'id_group' => 'required',
        ]);

        $annonce = Annonce::create($request->all());

        return response()->json(['message' => 'Annonce created successfully', 'data' => $annonce], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $annonce = Annonce::find($id);
        if (!$annonce) {
            return response()->json(['message' => 'Annonce not found'], 404);
        }
        return response()->json(['data' => $annonce], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $annonce = Annonce::find($id);
        if (!$annonce) {
            return response()->json(['message' => 'Annonce not found'], 404);
        }
        $request->validate([
            'id_user' => 'required',
            'title' => 'required',
            'message' => 'required',
            'id_group' => 'required',
        ]);
        $annonce->update($request->all());

        return response()->json(['message' => 'Annonce updated successfully', 'data' => $annonce], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $annonce = Annonce::find($id);
        if (!$annonce) {
            return response()->json(['message' => 'Annonce not found'], 404);
        }
        $annonce->delete();

        return response()->json(['message' => 'Annonce deleted successfully'], 200);
    }
}
