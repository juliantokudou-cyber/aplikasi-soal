<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    /*public function index() 
    {
        // Ambil daftar peserta yang BELUM di-approve
        $users = User::where('is_approved', false)->get();
        return view('admin.dashboard', compact('users'));
    }*/

    // Tambahkan construct ini untuk mengamankan seluruh halaman admin
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->check() && auth()->user()->email !== 'admin@cbt.com') {
                abort(403, 'Anda tidak memiliki hak akses ke halaman Admin.');
            }
            return $next($request);
        });
    }

    // Halaman Utama/Awal Admin
    public function index() 
    {
        // Mengambil semua peserta kecuali email admin sendiri
        $allPeserta = User::where('email', '!=', 'admin@cbt.com')->get();
        
        return view('admin.dashboard', compact('allPeserta'));
    }

    public function approve(Request $request, User $user) 
    {
        $request->validate([
            'username' => [
                'required', 
                'size:6', 
                'unique:users,username',
                'regex:/^(?=(?:.*[A-Z]){2})(?=(?:.*[a-z]){2})(?=(?:.*\d){2})[A-Za-z\d]{6}$/'
            ]
        ], [
            'username.regex' => 'Username harus 6 digit: 2 Huruf Besar, 2 Huruf Kecil, 2 Angka!'
        ]);

        $user->update([
            'username' => $request->username,
            'is_approved' => true
        ]);

        return back()->with('success', 'Akun ' . $user->name . ' berhasil di-approve!');
    }

    /*public function approve(Request $request, User $user) 
    {
        $request->validate([
            // Regex validasi tepat 6 karakter: mengandung tepat 2 huruf besar, 2 huruf kecil, 2 angka
            'username' => [
                'required', 
                'size:6', 
                'unique:users,username',
                'regex:/^(?=(?:.*[A-Z]){2})(?=(?:.*[a-z]){2})(?=(?:.*\d){2})[A-Za-z\d]{6}$/'
            ]
        ], [
            'username.regex' => 'Username harus berupa 6 digit kombinasi 2 huruf besar, 2 huruf kecil, dan 2 angka!'
        ]);

        $user->update([
            'username' => $request->username,
            'is_approved' => true
        ]);

        return back()->with('success', 'Akun peserta ' . $user->name . ' berhasil di-approve!');
    }*/

    public function updatePeserta(Request $request, $id)
    {
        $peserta = User::findOrFail($id);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'birth_date' => [
                'required',
                'date',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($peserta->id),
            ],

            'whatsapp' => [
                'required',
                'string',
                'max:25',
            ],

            'username' => [
                'nullable',
                'regex:/^[A-Za-z0-9]{1,6}$/',
                Rule::unique('users', 'username')->ignore($peserta->id),
            ],
        ]);

        $peserta->name = $validated['name'];
        $peserta->birth_date = $validated['birth_date'];
        $peserta->email = $validated['email'];
        $peserta->whatsapp = $validated['whatsapp'];
        $peserta->username = $validated['username'] ?? null;

        $peserta->save();

        return redirect()
            ->back()
            ->with('success', 'Data akun peserta berhasil diperbarui.');
    }

    /**
     * Menghapus akun peserta.
     */
    public function deletePeserta($id)
    {
        $peserta = User::findOrFail($id);

        // Jangan izinkan penghapusan akun sendiri.
        if ((string) Auth::id() === (string) $peserta->id) {
            return redirect()
                ->back()
                ->with('error', 'Akun yang sedang login tidak dapat dihapus.');
        }

        $peserta->delete();

        return redirect()
            ->back()
            ->with('success', 'Akun peserta berhasil dihapus.');
    }

   /*public function importSoal(Request $request)
{
    $request->validate([
        'file_soal' => 'required|mimes:csv,txt',
        'files_media.*' => 'nullable|mimes:jpg,jpeg,png,mp3,mp4|max:20480' // Maksimal 20MB per file
    ], [
        'file_soal.mimes' => 'Format file wajib .csv',
        'files_media.*.mimes' => 'Format media harus berupa jpg, png, mp3, atau mp4'
    ]);

    // 1. Proses Upload File-File Media Pendukung (Jika ada)
    if ($request->hasFile('files_media')) {
        foreach ($request->file('files_media') as $media) {
            // Dapatkan nama asli file (misal: paru-paru.jpg)
            $filename = $media->getClientOriginalName();
            // Simpan ke folder public/uploads/soal/
            $media->move(public_path('uploads/soal'), $filename);
        }
    }

    // 2. Proses Baca & Import Data CSV Excel
    $file = $request->file('file_soal');
    $handle = fopen($file->getRealPath(), "r");
    fgetcsv($handle, 1000, ","); // Lewati baris header

    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if (isset($row[0]) && isset($row[6])) {
            \App\Models\Question::create([
                'question_text'  => $row[0],
                'media_type'     => !empty($row[1]) ? trim($row[1]) : null,
                'media_file'     => !empty($row[2]) ? trim($row[2]) : null,
                'option_a'       => $row[3],
                'option_b'       => $row[4],
                'option_c'       => $row[5],
                'correct_answer' => strtoupper(trim($row[6])),
            ]);
        }
    }
    fclose($handle);

    return back()->with('success', 'Bank soal dan file multimedia berhasil di-import ke server!');
}*/

   public function importSoal(Request $request)
{
    $request->validate([
        'file_soal' => 'required|mimes:csv,txt',
        'files_media.*' => 'nullable|mimes:jpg,jpeg,png,mp3,mp4|max:20480'
    ], [
        'file_soal.mimes' => 'Format file wajib .csv',
        'files_media.*.mimes' => 'Format media harus berupa jpg, png, mp3, atau mp4'
    ]);

    // 1. Proses Upload File-File Media Pendukung (Tetap dipertahankan jika sewaktu-waktu dipakai)
    if ($request->hasFile('files_media')) {
        foreach ($request->file('files_media') as $media) {
            $filename = $media->getClientOriginalName();
            $media->move(public_path('uploads/soal'), $filename);
        }
    }

    // 2. Proses Baca & Import Data CSV Excel
    $file = $request->file('file_soal');
    $handle = fopen($file->getRealPath(), "r");
    
    // DETEKSI OTOMATIS: Mengantisipasi perbedaan pemisah koma (,) dan titik koma (;) pada Excel Indo
    $firstLine = fgets($handle);
    $separator = (strpos($firstLine, ';') !== false) ? ';' : ',';
    
    rewind($handle); // Kembalikan penunjuk ke baris paling atas
    fgetcsv($handle, 1000, $separator); // Lewati baris header resmi Excel

    // PERBAIKAN UTAMA: Looping membaca tepat 8 kolom kustom baru (Indeks 0 sampai 7)
    while (($row = fgetcsv($handle, 1000, $separator)) !== FALSE) {
        // Lewati baris jika kolom teks pertanyaan (kolom A / indeks 0) kosong
        if (empty($row) || !isset($row[0]) || trim($row[0]) === '') {
            continue;
        }

        \App\Models\Question::create([
            'question_text'  => $row[0],                                               // Kolom A
            'media_type'     => isset($row[1]) && !empty($row[1]) ? strtolower(trim($row[1])) : null, // Kolom B
            'media_file'     => isset($row[2]) && !empty($row[2]) ? trim($row[2]) : null, // Kolom C
            'option_type'    => isset($row[3]) && !empty($row[3]) ? strtolower(trim($row[3])) : 'text', // Kolom D (image / text)
            'option_a'       => $row[4] ?? '-',                                        // Kolom E
            'option_b'       => $row[5] ?? '-',                                        // Kolom F
            'option_c'       => $row[6] ?? '-',                                        // Kolom G
            'correct_answer' => isset($row[7]) ? strtoupper(trim($row[7])) : 'A',     // Kolom H
        ]);
    }
    fclose($handle);

    return back()->with('success', 'Bank soal dan file multimedia berhasil di-import ke server!');
}


   public function resetSoal()
{
    // Menghapus seluruh data soal dari database secara instan
    \App\Models\Question::truncate();

    return back()->with('success', 'Seluruh bank soal lama berhasil dikosongkan! Anda sekarang bisa mengunggah paket soal baru.');
}



}

