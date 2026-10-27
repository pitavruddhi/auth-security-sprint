<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $products = Product::with('category')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'] ?? null,
            $validated['title']
        );

        Product::create($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
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
    public function edit(string $id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        $validated['slug'] = $this->generateUniqueSlug(
            $validated['slug'] ?? null,
            $validated['title'],
            $product->id
        );

        $product->update($validated);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
         $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product moved to trash.');
    }

    private function generateUniqueSlug(
        ?string $slug,
        string $title,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($slug ?: $title);
        $uniqueSlug = $baseSlug;
        $counter = 1;

        while (
            Product::withTrashed()
                ->where('slug', $uniqueSlug)
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

    
    public function trash(): \Illuminate\View\View
    {
        $products = Product::onlyTrashed()
            ->with('category')
            ->orderByDesc('deleted_at')
            ->paginate(10);

        return view('products.trash', compact('products'));
    }

    public function restore(int $id): \Illuminate\Http\RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->restore();

        return redirect()
            ->route('admin.products.trash')
            ->with('success', 'Product restored successfully.');
    }

    public function forceDelete(int $id): \Illuminate\Http\RedirectResponse
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $product->forceDelete();

        return redirect()
            ->route('admin.products.trash')
            ->with('success', 'Product permanently deleted.');
    }

       
    public function catalog(\Illuminate\Http\Request $request): \Illuminate\View\View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0', 'gte:min_price'],
        ]);

        $products = Product::with('category')
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $query->where('title', 'like', '%' . $filters['search'] . '%');
            })
            ->when(!empty($filters['category_id']), function ($query) use ($filters) {
                $query->where('category_id', $filters['category_id']);
            })
            ->when(isset($filters['min_price']), function ($query) use ($filters) {
                $query->where('base_price', '>=', $filters['min_price']);
            })
            ->when(isset($filters['max_price']), function ($query) use ($filters) {
                $query->where('base_price', '<=', $filters['max_price']);
            })
            ->orderBy('title')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('catalog.index', compact('products', 'categories', 'filters'));
    }

    public function publicShow(Product $product): \Illuminate\View\View
    {
        $product->load('category');

        return view('catalog.show', compact('product'));
    }
 
    
}
