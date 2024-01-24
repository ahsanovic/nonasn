<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use App\Models\Biodata;
use Illuminate\Http\Request;
use App\Models\NonAsn\UserNonAsn;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserNonAsnRequest;

class UserNonAsnController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->perPage ?? 10;

        if (auth()->user()->level == 'admin') {
            $users = UserNonAsn::with('skpd')
                    ->select('niptt','nama','blokir','id_skpd')
                    ->when($request->user, function($query) use ($request) {
                        $query->where('niptt', 'like', '%'.$request->user.'%')
                                ->orWhere('nama', 'like', '%'.$request->user.'%');
                    })
                    ->paginate($per_page);
        } else if (auth()->user()->level == 'user') {
            $users = UserNonAsn::with('skpd')
                    ->select('niptt','nama','blokir','id_skpd')
                    ->where('id_skpd', 'like', auth()->user()->id_skpd.'%')
                    ->when($request->user, function($query) use ($request) {
                        $query->where('niptt', 'like', '%'.$request->user.'%')
                                ->orWhere('nama', 'like', '%'.$request->user.'%')
                                ->where('id_skpd', 'like', auth()->user()->id_skpd.'%');
                    })
                    ->paginate($per_page);
        }
        return view('fasilitator.user_nonasn.index', compact('users', 'per_page'));
    }

    public function edit(UserNonAsn $username)
    {
        $user = UserNonAsn::where('niptt', $username->niptt)->first();
        // check scope of id skpd
        if (!in_array($user->id_skpd, getScopeIdSkpd())) return back()->with(["type" => "error", "message" => "forbidden!"]);

        $skpd = Skpd::where('id', $user->id_skpd)->first(['id','name']);

        return view('fasilitator.user_nonasn.edit', compact('user', 'skpd'));
    }

    public function update(UserNonAsnRequest $request)
    {
        try {
            $user = UserNonAsn::where('niptt', $request->query('niptt'))->first(['id_ptt', 'password', 'blokir']);
            $password = $request->password ? Hash::make($request->password) : $user->password;

            $user->password = $password;
            $user->blokir = $request->blokir;
            $user->save();

            logFasilitator(auth()->user()->username, auth()->user()->id_skpd, $user->id_ptt, $request->segment(2), 'update');

            return redirect()->route('fasilitator.user-nonasn')->with(["type" => "success", "message" => "berhasil diubah!"]);
        } catch (\Throwable $th) {
            // throw $th;
            return back()->with(["type" => "error", "message" => "terjadi kesalahan!"]);
        }
    }

    public function autocomplete(Request $request)
    {
        $data = Biodata::whereAktif('Y')
                ->where('id_skpd', 'like', auth()->user()->id_skpd . '%')
                ->where(function($query) use ($request) {
                    $query->where('nama', 'like', '%' . $request->search . '%')
                        ->orWhere('niptt', 'like', $request->search . '%');
                })
                ->limit(10)
                ->orderBy('nama')
                ->get(['nama', 'niptt']);
        
        $res = [];
        foreach ($data as $value) {
            $res[] = [
                'label' => $value->nama
            ];
        }
        return response()->json($res);
    }
}
