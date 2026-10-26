<?php

namespace App\Http\Controllers;
use App\Models\Question;

class CbtController extends Controller
{
    public function index() {
        return view('cbt.dashboard');
    }

    public function startExam() {
        // Menarik seluruh kumpulan soal yang ada di database
        $soals = Question::all();

        if($soals->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'Maaf, paket soal ujian belum tersedia. Silahkan hubungi Admin');
        }

        // Set alokasi waktu default (Misal: 30 Menit)
        $durasiMenit = 11;

        return view('cbt.exam', compact('soals', 'durasiMenit'));
    }
}

