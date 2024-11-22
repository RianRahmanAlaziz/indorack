<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Category extends Model
{
    use HasFactory, HasRecursiveRelationships;
    protected $guarded = ['id'];

    /**
     * Relasi ke parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relasi ke child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function getParentChain()
    {
        $parents = [];
        $current = $this;

        // Loop untuk mengambil semua parent dari kategori ini
        while ($current->parent) {
            $current = $current->parent;
            array_unshift($parents, $current->name);
        }

        return implode(', ', $parents); // Gabungkan array menjadi string
    }
}
