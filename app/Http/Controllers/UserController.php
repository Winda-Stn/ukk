<?php
namespace App\Http\Controllers;

use App\Models\User; // Gunakan model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Ambil data pelanggan dari tabel users dengan role = 'pelanggan'
        $pelanggan = User::where('role', 'pelanggan')->get();

        return view('crud.pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('crud.pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
            'tipe_pelanggan' => 'required|in:tipe_1,tipe_2,tipe_3'
        ]);

        User::create([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pelanggan', // Set role pelanggan
            'tipe_pelanggan' => $request->tipe_pelanggan,
            'poin_pelanggan' => 0 // Default 0 karena poin bertambah otomatis
        ]);

        return redirect()->route('crud.pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function edit(User $pelanggan)
    {
        return view('crud.pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, User $pelanggan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($pelanggan->id)],
            'password' => 'nullable|min:8|regex:/^(?=.*[a-zA-Z])(?=.*\d).+$/',
            'tipe_pelanggan' => 'required|in:tipe_1,tipe_2,tipe_3',
        ]);

        $pelanggan->update([
            'name' => $request->nama,
            'email' => $request->email,
            'tipe_pelanggan' => $request->tipe_pelanggan
        ]);

        if ($request->filled('password')) {
            $pelanggan->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('crud.pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }

    public function destroy(User $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('crud.pelanggan.index')->with('success', 'Pelanggan berhasil dihapus.');
    }
}
