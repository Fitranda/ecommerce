<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Category;
use App\Models\Tenant\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class StorefrontController extends Controller
{
    public function home()
    {
        $featuredProducts = Cache::remember('featured_products', 3600, function () {
            return Product::with('category')
                ->active()
                ->inStock()
                ->latest()
                ->take(8)
                ->get();
        });

        $categories = Cache::remember('categories_active', 3600, function () {
            return Category::active()
                ->withCount(['products' => fn ($q) => $q->active()->inStock()])
                ->orderBy('name')
                ->get();
        });

        return Inertia::render('Tenant/Storefront/Home', [
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
        ]);
    }

    public function products(Request $request)
    {
        $products = Product::with('category')
            ->active()
            ->inStock()
            ->when($request->search, fn ($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->when($request->category, fn ($q, $cat) => $q->where('category_id', $cat))
            ->when($request->sort === 'price_asc', fn ($q) => $q->orderBy('price'))
            ->when($request->sort === 'price_desc', fn ($q) => $q->orderByDesc('price'))
            ->when($request->sort === 'newest', fn ($q) => $q->latest())
            ->when(!$request->sort, fn ($q) => $q->latest())
            ->paginate(12)
            ->withQueryString();

        $categories = Cache::remember('categories_active', 3600, function () {
            return Category::active()->orderBy('name')->get(['id', 'name', 'slug']);
        });

        return Inertia::render('Tenant/Storefront/Products', [
            'products' => $products,
            'categories' => $categories,
            'filters' => $request->only('search', 'category', 'sort'),
        ]);
    }

    public function productDetail(string $slug)
    {
        $product = Cache::remember("product_{$slug}", 3600, function () use ($slug) {
            return Product::with('category')
                ->where('slug', $slug)
                ->active()
                ->firstOrFail();
        });

        $relatedProducts = Product::with('category')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->inStock()
            ->take(4)
            ->get();

        return Inertia::render('Tenant/Storefront/ProductDetail', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
