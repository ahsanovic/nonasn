<?php

use App\Models\Fasilitator\LogFasilitator;
use App\Models\NonAsn\LogNonAsn;
use App\Models\Skpd;

function rupiah($angka)
{	
	$rupiah = "Rp " . number_format($angka,2,',','.');
	echo $rupiah;
}

function getScopeIdSkpd()
{
	$getScopeIdSkpd = Skpd::where('id', 'like', auth()->user()->id_skpd . '%')->pluck('id')->toArray();
	return $getScopeIdSkpd;
}

function getScopeIdSkpdApi($organization_id)
{
	$getScopeIdSkpd = Skpd::where('id', 'like', $organization_id . '%')->pluck('id')->toArray();
	return $getScopeIdSkpd;
}

function logPtt($idPtt, $modul, $aksi)
{
	LogNonAsn::create([
		'ptt_id' => $idPtt,
		'modul' => $modul,
		'aksi' => $aksi,
		'tgl' => date('Y-m-d'),
		'jam' => date('H:i:s')
	]);
}

function logFasilitator($username, $idSkpd, $idPtt, $modul, $aksi)
{
	LogFasilitator::create([
		'username' => $username,
		'id_skpd' => $idSkpd,
		'id_ptt' => $idPtt,
		'modul' => $modul,
		'aksi' => $aksi,
		'tgl' => date('Y-m-d'),
		'jam' => date('H:i:s')
	]);
}

function logDpaFasilitator($username, $idSkpd, $modul, $aksi)
{
	LogFasilitator::create([
		'username' => $username,
		'id_skpd' => $idSkpd,
		'modul' => $modul,
		'aksi' => $aksi,
		'tgl' => date('Y-m-d'),
		'jam' => date('H:i:s')
	]);
}
