<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login') ->withErrors(['email' => 'Please login to access the dashboard',])->onlyInput('email');
        }
        $users = User::get();
        return view('users')->with('userss', $users);
    }

    public function edit(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect('users')->with ('error', 'User not found');
        }
        return view('edit', compact('user'));
    }

    public function update(Request $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect('users')->with ('error', 'User not found');
        }

        // validasi input
        $request->validate([
            'name' => 'required|string|max:205',
            'email' => 'required|email|max:250|unique:users,email,' . $user->id,
            'password' => 'required|min:8|confirmed', // Menambahkan validasi untuk password
            'photo'=>'image|nullable|max:1999' // Menambahkan validasi untuk photo
            // validasi untuk memastikan file yang diupload adalah image.
        ]);

        // update nama dan email
        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        if ($request->hasFile('photo')) {
            if ($user->photo){
                $oldFile = public_path('storage/'. $user->photo);
                if (File::exists($oldFile)) {
                    File::delete($oldFile);
                }
            }
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('images', $filename, 'public');
            $user->photo='images/'. $filename;
        }
        $user->save();

        return redirect('users')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect('users')->with('error', 'User not found');
        }

        $file = public_path() . '/' . 'storage/' . $user->photo;

        try {
            if (File::exists($file)) {
                File::delete($file);
            }
             $user->delete();
        } catch (\Throwable $th) {
            return redirect('users')->with('error', 'Gagal hapus data');
        }
        return redirect('users')->with('success', 'Berhasil hapus data');
    }
}
