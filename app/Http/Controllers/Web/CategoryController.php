<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
         $categories = Category::with('parent')
            ->withCount('children')
            ->orderBy('name')
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $parentCategories = Category::orderBy('name')->get();

        return view(
            'categories.create',
            compact('parentCategories')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreCategoryRequest $request
    ): RedirectResponse {
        $validated = $request->validated();

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'] ?? null,
            $validated['name']
        );

        Category::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        $parentCategories = Category::where(
            'id',
            '!=',
            $category->id
        )
            ->orderBy('name')
            ->get();

        return view(
            'categories.edit',
            compact('category', 'parentCategories')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateCategoryRequest $request,
        Category $category
    ): RedirectResponse {
        $validated = $request->validated();

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'] ?? null,
            $validated['name'],
            $category->id
        );

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->children()->exists()) {
            return back()->with(
                'error',
                'Please move or delete child categories first.'
            );
        }

        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'Please reassign or delete products first.'
            );
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    //  Generate a unique slug.
     
    private function generateUniqueSlug(
        ?string $slug,
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($slug ?: $name);
        $uniqueSlug = $baseSlug;
        $counter = 1;

        while (
            Category::where('slug', $uniqueSlug)
                ->when(
                    $ignoreId !== null,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $uniqueSlug;
    }
}
