<?php

namespace App\Http\Controllers\Fasilitator;

use App\Models\Skpd;
use Hashids\Hashids;
use App\Http\Controllers\Controller;

class TreeviewController extends Controller
{
    public function index()
    {
        return view('treeview.index');
    }

    private function _hashId()
    {
        return new Hashids(env('SECRET_SALT_KEY'),15);
    }

    public function unor()
    {
        $hashid = $this->_hashId();
        $skpd = Skpd::where('id', 'like', auth()->user()->id_skpd . '%')->get();
        $tree = [];
        foreach ($skpd as $unor) {
            $array = [
                'id' => $unor->id,
                'pId' => $unor->pId,
                'name' => $unor->id . ' - ' . $unor->name,
                'url' => url()->to('/fasilitator/pegawai') . '/' . $hashid->encode($unor->id),
                'icon' => url('/zTree/css/zTreeStyle/img/diy/1_close.png'),
                'open' => $unor->open
            ];
            array_push($tree, $array);
        }
        return response()->json($tree);
    }

    public function unorNoLink()
    {
        $hashid = $this->_hashId();
        $skpd = Skpd::where('id', 'like', auth()->user()->id_skpd . '%')->get();
        $tree = [];
        foreach ($skpd as $unor) {
            $array = [
                'id' => $unor->id,
                'pId' => $unor->pId,
                'name' => $unor->id . ' - ' . $unor->name,
                'url' => '',
                'icon' => url('/zTree/css/zTreeStyle/img/diy/1_close.png'),
                'open' => $unor->open
            ];
            array_push($tree, $array);
        }
        return response()->json($tree);
    }
}
