<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masterpangkat;
use App\Models\Masterpegawai;

class MasterpegawaiController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('search')){
            $masterpegawai = Masterpegawai::where('nama', 'LIKE', '%' .$request->search.'%')->paginate(10);
        }else{
            $masterpegawai = Masterpegawai::paginate(10);
        }
        return view('masterpegawai.index',[
            'masterpegawai' => $masterpegawai
        ]);
    }


    public function create()
    {
       $masterpangkat = Masterpangkat::all();
        return view('masterpegawai.create', [
            'masterpangkat' => $masterpangkat,
        ]);
        return view('masterpegawai.create')->with('success', 'Data Telah ditambahkan');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        Masterpegawai::create($data);

        return redirect()->route('masterpegawai.index')->with('success','Data Telah Ditambahkan');
    }


    public function show($id)
    {

    }


    public function edit(Masterpegawai $masterpegawai)
    {
         $masterpangkat = Masterpangkat::all();

        return view('masterpegawai.edit', [
            'item' => $masterpegawai,
            'masterpangkat' => $masterpangkat,
        ]);
    }


    public function update(Request $request, Masterpegawai $masterpegawai)
    {
        $data = $request->all();

        $masterpegawai->update($data);

        //dd($data);

        return redirect()->route('masterpegawai.index')->with('success', 'Data Telah diupdate');

    }


    public function destroy(Masterpegawai $masterpegawai)
    {
        $masterpegawai->delete();
        return redirect()->route('masterpegawai.index')->with('success', 'Data Telah dihapus');
    }
}
