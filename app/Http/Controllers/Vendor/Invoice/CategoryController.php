<?php

namespace App\Http\Controllers\Vendor\Invoice;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vendor\Category\CreateCategoryRequest;
use App\Http\Requests\Vendor\Category\UpdateCategoryRequest;
use App\Models\Invoice\Category;
use App\Repositories\CategoryRepository;
use Exception;

class CategoryController extends Controller
{
    /** @var CategoryRepository */
    public $categoryRepository;

    public function __construct(CategoryRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * @throws Exception
     */
    public function index()
    {
        $categories = Category::where(['vendor_id' => auth('vendor')->user()->id])->get();
        return view('vendors.categories.index', compact('categories'));
    }

    public function create() {
        return view('vendors.categories.create');
    }

    public function store(CreateCategoryRequest $request)
    {
        $input = $request->all();
        $input['vendor_id'] = auth('vendor')->user()->id;
        // dd($input);
        
        Category::create($input);

        return redirect()->route('vendor.invoice-system.categories.index')->with('success', 'Category created successfully');
    }

    public function edit($id)
    {
        $category = Category::where(['id' => $id, 'vendor_id' => auth('vendor')->user()->id])->firstOrFail();
        return view('vendors.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $input = $request->all();
        $this->categoryRepository->updateCategory($input, $id);

        return redirect()->route('vendor.invoice-system.categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * @param  Category  $category
     */
    public function destroy($id)
    {
        $category = Category::where(['id' => $id, 'vendor_id' => auth('vendor')->user()->id])->firstOrFail();
        $category->delete();

        return redirect()->route('vendor.invoice-system.categories.index');
    }
}
