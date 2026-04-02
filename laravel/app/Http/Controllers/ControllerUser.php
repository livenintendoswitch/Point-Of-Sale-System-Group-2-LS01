<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class ControllerUser extends Controller
{
    

    public function index()
    {
        $users = UserModel::where('role', 'kasir')->latest()->paginate(10);
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
        ]);
        UserModel::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
        ]);
        return redirect()->route('user.index')->with('success', 'Akun kasir berhasil dibuat');
    }

    public function destroy($id)
    {
        $user = UserModel::findOrFail($id);
        if ($user->role == 'admin') {
            return back()->with('error', 'Tidak bisa menghapus admin');
        }
        $user->delete();
        return redirect()->route('user.index')->with('success', 'Kasir dihapus');
    }
}
