<?php

namespace App\Http\Controllers;

use App\Models\Info;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class InfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Info::query();

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nfile', 'like', "%{$search}%");
        }

        $info = $query->paginate(10);

        return view('dashboard.info.info', [
            'title' => 'Info',
            'info' => $info->appends([
                'search' => $request->input('search'),
            ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'gambar' => 'required|image|mimes:jpg,png,jpeg,webp',
            'description' => 'required',
        ]);

        if ($request->hasFile('gambar')) {
            // Mulai transaksi database
            DB::beginTransaction();
            try {
                $gambar = $request->file('gambar');
                $nama_gambar = $request->title . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('assets/images/info', $nama_gambar);

                // Menambahkan nama file ke array validatedData
                $validatedData['gambar'] = $nama_gambar;

                // Menyimpan data ke database
                Info::create($validatedData);

                // Commit transaksi jika semuanya berhasil
                DB::commit();

                return redirect('/dashboard/info')->with('success', 'Info Baru Berhasil di Tambahkan');
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                DB::rollBack();

                // Hapus file yang sudah diupload jika terjadi error
                if (file_exists(public_path('assets/images/info/' . $nama_gambar))) {
                    unlink(public_path('assets/images/info/' . $nama_gambar));
                }

                // Kembali ke halaman sebelumnya dengan pesan error
                return redirect()
                    ->back()
                    ->with(['error' => 'Terjadi kesalahan, Silahkan coba lagi.']);
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Info $info)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Info $info)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $info = Info::findOrFail($id);
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        try {
            if ($request->has('gambar')) {
                File::delete('assets/images/info/' . $info->gambar);
                $gambar = $request->file('gambar');
                $nama_gambar = $request->nfile . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('assets/images/info', $nama_gambar);
                $validatedData['gambar'] = $nama_gambar;
            } else {
                unset($validatedData['gambar']);
            }
            Info::where('id', $id)->update($validatedData);
            return redirect('/dashboard/info')->with('success', 'Berhasil di Update');
        } catch (\Exception $e) {
            return redirect('/dashboard/info')->with('error', 'Terjadi kesalahan, Silahkan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $file = Info::findOrFail($id);
        try {
            Info::destroy($file->id);
            File::delete('assets/images/info/' . $file->gambar);
            return redirect('/dashboard/info')->with('success', 'Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/dashboard/info')->with('error', 'Gagal Menghapus. Silakan Coba Lagi.');
        }
    }
}
