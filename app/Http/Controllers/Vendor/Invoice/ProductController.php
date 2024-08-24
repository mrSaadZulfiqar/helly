<?php

namespace App\Http\Controllers\Vendor\Invoice;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Invoice\Product;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Models\Invoice\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index(): \Illuminate\View\View
    {
        $products = Product::with('category')->where(['vendor_id' => auth('vendor')->user()->id])->get();
        return view('vendors.invoice-products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     */
    public function create(): \Illuminate\View\View
    {
        $categories = Category::where(['vendor_id' => auth('vendor')->user()->id])->get();
        return view('vendors.invoice-products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'category_id' => 'required',
            'unit_price' => 'required',
            'description' => 'nullable',
            'image' => 'nullable',
        ];

        $validator = validator()->make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $filename = "";

        if ($request->hasFile('image')) {

            $image = $request->file('image');
            $filename = time() . '.' . $image->extension();

            $image->move('uploads/products', $filename);
        }

        $validated = $validator->validated();
        $validated["image"] = $filename;
        $validated["vendor_id"] = auth('vendor')->user()->id;

        Product::create($validated);

        return redirect()->route('vendor.invoice-system.products.index')->with('success', 'Product created successfully');
    }

    public function edit($productId): \Illuminate\View\View
    {
        $product = Product::whereId($productId)->whereVendorId(auth('vendor')->user()->id)->firstOrFail();
        $categories = Category::where([
            'vendor_id' => auth('vendor')->user()->id
        ])->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $rules = [
            'name' => 'required',
            'code' => 'required',
            'category_id' => 'required',
            'unit_price' => 'required',
            'description' => 'nullable',
            'image' => 'nullable',
        ];

        $validator = validator()->make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $filename = "";

        $validated = $validator->validated();
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->extension();
            $image->move('uploads/products', $filename);
            $validated["image"] = $filename;
        }
        
        $product->update($validated);
        return redirect()->route('vendor.invoice-system.products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        if ($product->vendor_id  != auth('vendor')->user()->id) {
            return abort(404);
        }

        $product->delete();
        return redirect()->route('vendor.invoice-system.products.index')->with('success', 'Product deleted successfully');
    }

}
