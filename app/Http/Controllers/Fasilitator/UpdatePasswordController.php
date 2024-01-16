<?php

namespace App\Http\Controllers\Fasilitator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\PasswordValidateRequest;
use App\Models\Fasilitator\UserFasilitator;

class UpdatePasswordController extends Controller
{
    public function index()
    {
        return view('fasilitator.update_password.index');
    }

    public function update(PasswordValidateRequest $request)
    {
        try {
            $data = UserFasilitator::find(auth()->user()->username);
            $data->password = Hash::make($request->password);
            $data->save();

            return back()->with(["type" => "success", "message" => "berhasil merubah password"]);
        } catch (\Throwable $th) {
            return back()->with(["type" => "error", "message" => "terjadi kesalahan"]);
        }
    }
}
