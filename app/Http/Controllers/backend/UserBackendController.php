<?php

namespace App\Http\Controllers\backend;

use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserBackendController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // SEARCH
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('role', 'like', '%' . $request->search . '%');
        }

        $users = $query->latest()->paginate(5)->withQueryString();

        return view('pages.backend.user.index', compact('users'));
    }
    public function create()
    {
        return view('pages.backend.user.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            'role' => 'required',
            'img' => 'nullable|image'
        ]);

        $data = $request->all();

        // upload gambar
        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('users', 'public');
        }

        // hash password
        $data['password'] = Hash::make($request->password);

        User::create($data);

        return redirect()->route('user.index')->with('success', 'User berhasil ditambah');
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('pages.backend.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required',
            'img' => 'nullable|image'
        ]);

        $data = $request->all();
        if ($request->hasFile('img')) {

            // hapus foto lama
            if ($user->img && Storage::disk('public')->exists($user->img)) {
                Storage::disk('public')->delete($user->img);
            }

            // upload baru
            $data['img'] = $request->file('img')->store('users', 'public');
        }

        // password opsional
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // hapus foto dari storage (kalau ada)
        if ($user->img && Storage::disk('public')->exists($user->img)) {
            Storage::disk('public')->delete($user->img);
        }

        // hapus data user
        $user->delete();

        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
}
