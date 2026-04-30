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

    public function edit($id)
    {
        $user = UserModel::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = UserModel::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:4', // Password opsional saat edit
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'Akun kasir berhasil diperbarui');
    }
}
