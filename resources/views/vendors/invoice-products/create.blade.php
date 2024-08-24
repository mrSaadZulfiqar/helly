@extends('vendors.layout')

@section('content')
  <div class="page-header">
    <h4 class="page-title">{{ __('Add Product') }}</h4>
    <ul class="breadcrumbs">
      <li class="nav-home">
        <a href="{{ route('vendor.dashboard') }}">
          <i class="flaticon-home"></i>
        </a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Invoice System') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Products') }}</a>
      </li>
      <li class="separator">
        <i class="flaticon-right-arrow"></i>
      </li>
      <li class="nav-item">
        <a href="#">{{ __('Add Product') }}</a>
      </li>

    </ul>
  </div>

  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <div class="card-title d-inline-block">
            {{ __('Add Product') }}
          </div>
          <a class="btn btn-info btn-sm float-right d-inline-block"
            href="{{ route('vendor.invoice-system.products.index') }}">
            <span class="btn-label">
              <i class="fas fa-backward"></i>
            </span>
            {{ __('Back') }}
          </a>
        </div>

        <div class="card-body">
          <div class="row">
            <div class="col-lg-8 offset-lg-2">
              <form id="productForm" class="row g-3" action="{{ route('vendor.invoice-system.products.store') }}" method="POST" enctype="multipart/form-data">

                @csrf
              
                <div class="col-md-6 mb-3">
                  
                  <label class="form-label" for="name">Name</label>
              
                  <input 
                    type="text"
                    name="name" 
                    id="name"
                    class="form-control"
                    value="{{ old('name') }}">
              
                    @error('name')
                      <p class="text-danger">{{ $message }}</p>  
                    @enderror
              
                </div>
              
                <div class="col-md-6 mb-3">
              
                  <label class="form-label" for="unit_price">Unit Price</label>
              
                  <input 
                    type="number" 
                    name="unit_price"
                    id="unit_price" 
                    class="form-control"
                    value="{{ old('unit_price') }}">
              
                    @error('unit_price')
                      <p class="text-danger">{{ $message }}</p>
                    @enderror
              
                </div>
              
                <div class="col-md-6 mb-3">
              
                  <label class="form-label" for="category_id">Category</label>
              
                  <select name="category_id" id="category_id" class="form-control">
              
                    <option value="">Select Category</option>
              
                    @foreach($categories as $category)
                      <option 
                        value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                      </option>  
                    @endforeach
              
                  </select>
              
                  @error('category_id')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
              
                </div>

                <div class="col-md-6 mb-3">

                  <label class="form-label" for="code">Code</label>
                
                  <input 
                    type="text" 
                    name="code"
                    id="code"
                    class="form-control">
                  @error('code')
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">

                  <label for="image">Image</label>
                
                  <div class="mb-3">
                    <img id="preview"> 
                  </div>
                
                  <input 
                    type="file" 
                    name="image"
                    id="image"
                    class="form-control"
                    onchange="loadFile(event)">
                
                </div>              
              
              </form>             
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-12 text-center">
              <button type="submit" form="productForm" class="btn btn-success">
                {{ __('Save') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
<script>
  function loadFile(event) {
  var image = document.getElementById('preview');
  image.src = URL.createObjectURL(event.target.files[0]);
}
</script>
@endsection