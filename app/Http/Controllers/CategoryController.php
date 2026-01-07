<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('courses')->get();
        
        return Inertia::render('Categories/index', [
            'categories' => $categories,
        ]);
    }

    public function show(int $id)
    {
        $category = Category::where('id', $id)->with(['courses' => function($query) {
            $query->withCount('episodes')->with('user');
        }])->firstOrFail();

        return Inertia::render('Categories/show', [
            'category' => $category,
        ]);
    }
}

