<?php

namespace App\Http\Controllers\Fasilitator;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserFasilitatorRequest;
use App\Models\Fasilitator\UserFasilitator;
use App\Models\Skpd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserFasilitatorController extends Controller
{
    public function index(Request $request)
    {        
        $per_page = $request->perPage ?? 10;
        $users = UserFasilitator::with('skpd')
                ->select('username','id_skpd','no_telp','nama_lengkap','level','blokir')
                ->when($request->user, function($query) use ($request) {
                    $query->where('username', 'like', '%'.$request->user.'%')
                            ->orWhere('nama_lengkap', 'like', '%'.$request->user.'%');
                })
                ->paginate($per_page);

        return view('fasilitator.user.index', compact('users', 'per_page'));
    }

    public function create()
    {
        $submit = "Simpan";
        return view('fasilitator.user.create', compact('submit'));
    }

    public function store(UserFasilitatorRequest $request)
    {
        try {
            UserFasilitator::create([
                'username' => $request->username,
                'nama_lengkap' => $request->nama_lengkap,
                'password' => Hash::make($request->password),
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'id_skpd' => explode(" - ", $request->skpd)[0],
                'level' => $request->level,
            ]);

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, null, $request->segment(2), 'input');

            return redirect()->route('fasilitator.user')->with(["type" => "success", "message" => "berhasil ditambahkan!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function edit(UserFasilitator $username)
    {
        $submit = "Update";
        $user = UserFasilitator::where('username', $username->username)->first();
        $skpd = Skpd::where('id', $user->id_skpd)->first(['id','name']);
        return view('fasilitator.user.edit', compact('submit', 'user', 'skpd'));
    }

    public function update(UserFasilitatorRequest $request)
    {
        try {
            $user = UserFasilitator::find($request->username);
            $password = $request->password ? Hash::make($request->password) : $user->password;

            $user->password = $password;
            $user->nama_lengkap = $request->nama_lengkap;
            $user->email = $request->email;
            $user->no_telp = $request->no_telp;
            $user->id_skpd = explode(" - ", $request->skpd)[0];
            $user->level = $request->level;
            $user->blokir = $request->blokir;
            $user->save();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, null, $request->segment(2), 'update');

            return redirect()->route('fasilitator.user')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function destroy(UserFasilitator $username)
    {
        try {
            $username->delete();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, null, 'user-fasilitator', 'hapus');

            return redirect('fasilitator/user-fasilitator?page='. request()->current_page)->with(["type" => "success", "message" => "berhasil dihapus!"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }
}
