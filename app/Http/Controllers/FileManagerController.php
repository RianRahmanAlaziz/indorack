<?php

namespace App\Http\Controllers;

use App\Models\FileManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = FileManager::query();

        // Filter berdasarkan kategori (default ke 'gambar' jika tidak ada kategori yang dipilih)
        if ($request->has('category')) {
            $category = $request->input('category');
            $query->where('category', $category);  // Pastikan field 'category' ada dalam tabel Anda
        }

        // Filter berdasarkan pencarian
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('nfile', 'like', "%{$search}%");
        }

        $files = $query->paginate(10);

        return view('dashboard.file-manager.file-manager', [
            'title' => 'File Manager',
            'files' => $files->appends([
                'search' => $request->input('search'),
                'category' => $request->input('category'),
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
            'nfile' => 'required',
            'gambar' => 'required|image|mimes:jpg,png,jpeg,webp',
            'category' => 'required',
            'url' => 'nullable',
        ]);

        if ($request->hasFile('gambar')) {
            // Mulai transaksi database
            DB::beginTransaction();
            try {
                $gambar = $request->file('gambar');
                $nama_gambar = $request->nfile . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('assets/images/file-manager', $nama_gambar);

                // Menambahkan nama file ke array validatedData
                $validatedData['gambar'] = $nama_gambar;

                // Menyimpan data ke database
                FileManager::create($validatedData);

                // Commit transaksi jika semuanya berhasil
                DB::commit();

                return redirect('/dashboard/file-manager')->with('success', 'File Baru Berhasil di Tambahkan');
            } catch (\Exception $e) {
                // Rollback transaksi jika terjadi kesalahan
                DB::rollBack();

                // Hapus file yang sudah diupload jika terjadi error
                if (file_exists(public_path('assets/images/file-manager/' . $nama_gambar))) {
                    unlink(public_path('assets/images/file-manager/' . $nama_gambar));
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
    public function show(FileManager $fileManager)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FileManager $fileManager)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $file = FileManager::findOrFail($id);
        $validatedData = $request->validate([
            'nfile' => 'required',
            'category' => 'required',
            'url' => 'nullable',
        ]);

        try {
            if ($request->has('gambar')) {
                File::delete('assets/images/file-manager/' . $file->gambar);
                $gambar = $request->file('gambar');
                $nama_gambar = $request->nfile . '.' . $gambar->getClientOriginalExtension();
                $gambar->move('assets/images/file-manager', $nama_gambar);
                $validatedData['gambar'] = $nama_gambar;
            } else {
                unset($validatedData['gambar']);
            }
            FileManager::where('id', $id)->update($validatedData);
            return redirect('/dashboard/file-manager')->with('success', 'File Berhasil di Update');
        } catch (\Exception $e) {
            return redirect('/dashboard/file-manager')->with('error', 'Terjadi kesalahan, Silahkan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $file = FileManager::findOrFail($id);
        try {
            FileManager::destroy($file->id);
            File::delete('assets/images/file-manager/' . $file->gambar);
            return redirect('/dashboard/file-manager')->with('success', 'File Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/dashboard/file-manager')->with('error', 'Gagal Menghapus File. Silakan Coba Lagi.');
        }
    }
}
