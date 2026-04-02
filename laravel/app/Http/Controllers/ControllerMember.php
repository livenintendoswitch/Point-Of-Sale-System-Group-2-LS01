<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MemberModel;

class ControllerMember extends Controller
{
    
    public function index()
    {
        $members = MemberModel::latest()->paginate(10);
        return view('admin.member.index', compact('members'));
    }

    public function create()
    {
        return view('admin.member.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_member' => 'required|unique:members,kode_member',
            'nama' => 'required',
            'telepon' => 'nullable',
            'poin' => 'integer',
        ]);
        MemberModel::create($request->all());
        return redirect()->route('member.index')->with('success', 'Member ditambahkan');
    }

    public function edit($id)
    {
        $member = MemberModel::findOrFail($id);
        return view('admin.member.edit', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = MemberModel::findOrFail($id);
        $request->validate([
            'kode_member' => 'required|unique:members,kode_member,' . $id,
            'nama' => 'required',
            'telepon' => 'nullable',
            'poin' => 'integer',
        ]);
        $member->update($request->all());
        return redirect()->route('member.index')->with('success', 'Member diupdate');
    }

    public function destroy($id)
    {
        MemberModel::findOrFail($id)->delete();
        return redirect()->route('member.index')->with('success', 'Member dihapus');
    }
}
