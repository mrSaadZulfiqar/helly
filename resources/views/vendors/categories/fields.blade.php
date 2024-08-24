
<div class="row">
    <div class="col-12">
        <form method="POST" id="categoryForm" name="categoryForm" action="@if(isset($category)){{ route('vendor.invoice-system.categories.update', $category->id) }}@else{{ route('vendor.invoice-system.categories.store') }}@endif">
            @csrf
            @if(isset($category)) @method('PUT') @endif

            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="@if(isset($category)){{ old('name', $category->name) }}@else{{ old('name') }}@endif">
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </form>
    </div>
</div>