<?php

namespace App\Http\Controllers\NonAsn;

use App\Models\Biodata;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PasswordValidateRequest;

class NonasnUpdatePasswordController extends Controller
{
    public function index()
    {
        return view('nonasn.update_password.index');
    }

    public function update(PasswordValidateRequest $request)
    {
        try {
            $data = Biodata::find(auth()->user()->id_ptt);
            $data->password = Hash::make($request->password);
            $data->save();

            return back()->with(["type" => "success", "message" => "berhasil merubah password"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan"]);
        }
    }
}
