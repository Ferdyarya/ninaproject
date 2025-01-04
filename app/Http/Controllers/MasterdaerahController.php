<?php

namespace App\Http\Controllers;

use App\Models\Masterdaerah;
use Illuminate\Http\Request;

class MasterdaerahController extends Controller
{
    public function index(Request $request)
    {
        if($request->has('search')){
            $masterdaerah = Masterdaerah::where('namadaerah', 'LIKE', '%' .$request->search.'%')->paginate(10);
        }else{
            $masterdaerah = Masterdaerah::paginate(10);
        }
        return view('masterdaerah.index',[
            'masterdaerah' => $masterdaerah
        ]);
    }


    public function create()
    {
        return view('masterdaerah.create');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        Masterdaerah::create($data);

        return redirect()->route('masterdaerah.index')->with('success','Data Telah Ditambahkan');
    }


    public function show($id)
    {

    }


    public function edit(Masterdaerah $masterdaerah)
    {
        return view('masterdaerah.edit', [
            'item' => $masterdaerah
        ]);
    }


    public function update(Request $request, Masterdaerah $masterdaerah)
    {
        $data = $request->all();

        $masterdaerah->update($data);

        //dd($data);

        return redirect()->route('masterdaerah.index')->with('success', 'Data Telah diupdate');

    }


    public function destroy(Masterdaerah $masterdaerah)
    {
        $masterdaerah->delete();
        return redirect()->route('masterdaerah.index')->with('success', 'Data Telah dihapus');
    }
}
