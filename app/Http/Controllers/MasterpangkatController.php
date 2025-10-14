<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masterpangkat;

class MasterpangkatController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('search')){
            $masterpangkat = Masterpangkat::where('pangkat', 'LIKE', '%' .$request->search.'%')->paginate(10);
        }else{
            $masterpangkat = Masterpangkat::paginate(10);
        }
        return view('masterpangkat.index',[
            'masterpangkat' => $masterpangkat
        ]);
    }


    public function create()
    {
        return view('masterpangkat.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        Masterpangkat::create($data);

        return redirect()->route('masterpangkat.index')->with('success','Data Telah Ditambahkan');
    }


    public function show($id)
    {

    }


    public function edit(Masterpangkat $masterpangkat)
    {
        return view('masterpangkat.edit', [
            'item' => $masterpangkat
        ]);
    }


    public function update(Request $request, Masterpangkat $masterpangkat)
    {
        $data = $request->all();

        $masterpangkat->update($data);

        //dd($data);

        return redirect()->route('masterpangkat.index')->with('success', 'Data Telah diupdate');

    }


    public function destroy(Masterpangkat $masterpangkat)
    {
        $masterpangkat->delete();
        return redirect()->route('masterpangkat.index')->with('success', 'Data Telah dihapus');
    }
}
