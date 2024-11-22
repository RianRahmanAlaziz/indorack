<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }

        $categories = $query->get();

        // Urutkan kategori berdasarkan hierarki
        $sortedCategories = $this->sortCategoriesByHierarchy($categories);

        // Pagination manual
        $perPage = 10; // Jumlah item per halaman
        $currentPage = $request->input('page', 1); // Halaman saat ini (default 1)
        $currentItems = array_slice($sortedCategories, ($currentPage - 1) * $perPage, $perPage);

        $paginatedCategories = new LengthAwarePaginator(
            $currentItems, // Data saat ini
            count($sortedCategories), // Total item
            $perPage, // Item per halaman
            $currentPage, // Halaman saat ini
            ['path' => $request->url(), 'query' => $request->query()] // URL dan query
        );

        return view('dashboard.category.category', [
            'title' => 'Category',
            'categories' => $paginatedCategories,
        ]);
    }

    private function sortCategoriesByHierarchy($categories, $parentId = null, &$result = [])
    {
        // Ubah array menjadi collection jika diperlukan
        $categories = collect($categories);

        // Filter kategori berdasarkan parent_id
        $filtered = $categories->filter(function ($category) use ($parentId) {
            return $category['parent_id'] == $parentId;
        });

        // Proses kategori yang difilter
        foreach ($filtered as $category) {
            // Tambahkan kategori ke hasil
            $result[] = $category;

            // Rekursif untuk anak kategori
            $this->sortCategoriesByHierarchy($categories, $category['id'], $result);
        }

        // Tambahkan kategori yang tidak memiliki parent_id ke akhir hasil (jika belum ada)
        if ($parentId === null) {
            $unassigned = $categories->filter(function ($category) use ($result) {
                return !in_array($category, $result);
            });

            foreach ($unassigned as $category) {
                $result[] = $category;
            }
        }

        return $result;
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id', // Validasi parent_id harus ada di tabel categories
        ]);

        Category::create($validated);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        try {
            Category::where('id', $id)->update($validatedData);
            return redirect('/dashboard/category')->with('success', 'Berhasil di Update');
        } catch (\Exception $e) {
            return redirect('/dashboard/category')->with('error', 'Terjadi kesalahan, Silahkan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        try {
            Category::destroy($category->id);
            return redirect('/dashboard/category')->with('success', 'Berhasil di Hapus');
        } catch (\Exception $e) {
            return redirect('/dashboard/category')->with('error', 'Gagal Menghapus. Silakan Coba Lagi.');
        }
    }
}
