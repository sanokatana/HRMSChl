<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        $query->orderBy('name', 'asc');

        if (!empty($request->nama)) {
            $query->where('name', 'like', '%' . $request->nama . '%');
        }

        $user = $query->paginate(10);
        return view("user.index", compact('user'));
    }



    public function store(Request $request)
    {
        $nik = $request->nik;
        $name = $request->nama;
        $email = $request->email;
        $password = Hash::make($request->password);

        try {
            $data = [
                'nik' => $nik,
                'name' => $name,
                'email' => $email,
                'password' => $password,
            ];
            $simpan = DB::table('users')->insert($data);
            if ($simpan) {
                return Redirect::back()->with(['success' => 'Data Berhasil Di Simpan']);
            }
        } catch (\Exception $e) {
            return Redirect::back()->with(['danger' => 'Data Gagal Di Simpan']);
        }
    }

    public function edit(Request $request)
    {

        $nik = $request->nik;
        $user = DB::table('users')
            ->where('nik', $nik)
            ->first();
        return view('user.edit', compact('user'));
    }

    public function update($nik, Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::where('nik', $nik)->firstOrFail();
        $user->nik = $request->nik;
        $user->name = $request->nama;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
        }

        try {
            $user->save();
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['danger' => 'Data Gagal Di Update']);
        }
    }



    public function delete($nik)
    {
        $delete = DB::table('users')->where('nik', $nik)->delete();
        if ($delete) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }
}
