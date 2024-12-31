@extends('layout')

@section('content')
<div class="container">
    <h1 class="text-center mb-5">Shop</h1>
    <div class="row">
        <div class="col-md-3">
            <h3 class="mb-3">Filters</h3>
            <form action="{{ route('shop.index') }}" method="GET" id="filter-form">

                <div class="mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->getFormattedName() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="color" class="form-label">Color</label>
                    <select name="color" id="color" class="form-select">
                        <option value="">All Colors</option>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ request('color') == $color->id ? 'selected' : '' }}>
                                {{ $color->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3" id="size-container">
                    <label for="size" class="form-label">Size</label>
                    <select name="size" id="size" class="form-select" disabled>
                        <option value="">All Sizes</option>
                    </select>
                    <small id="size-help" class="form-text text-muted">Please select a category to enable size selection.</small>
                </div>

                <div class="mb-3">
                    <label for="material" class="form-label">Material</label>
                    <select name="material" id="material" class="form-select">
                        <option value="">All Materials</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" {{ request('material') == $material->id ? 'selected' : '' }}>
                                {{ $material->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="min_price" class="form-label">Min Price</label>
                    <input type="number" name="min_price" id="min_price" class="form-control" value="{{ request('min_price', $minPrice) }}" min="{{ $minPrice }}" max="{{ $maxPrice }}">
                </div>

                <div class="mb-3">
                    <label for="max_price" class="form-label">Max Price</label>
                    <input type="number" name="max_price" id="max_price" class="form-control" value="{{ request('max_price', $maxPrice) }}" min="{{ $minPrice }}" max="{{ $maxPrice }}">
                </div>

                <div class="mb-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}">
                </div>

                <div class="mb-3">
                    <label for="sort" class="form-label">Sort By</label>
                    <select name="sort" id="sort" class="form-select">
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Newest</option>
                        <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Price</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="direction" class="form-label">Sort Direction</label>
                    <select name="direction" id="direction" class="form-select">
                        <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Apply Filters</button>
            </form>
        </div>

        <div class="col-md-9">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($products as $product)
                    <div class="col">
                        @include('partials.product-card')
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category');
    const sizeSelect = document.getElementById('size');
    const sizeHelp = document.getElementById('size-help');

    categorySelect.addEventListener('change', function() {
        if (this.value) {
            fetch(`/shop/sizes-for-category/${this.value}`)
                .then(response => response.json())
                .then(sizes => {
                    sizeSelect.innerHTML = '<option value="">All Sizes</option>';
                    sizes.forEach(size => {
                        const option = document.createElement('option');
                        option.value = size.id;
                        option.textContent = size.name;
                        sizeSelect.appendChild(option);
                    });
                    sizeSelect.disabled = false;
                    sizeHelp.style.display = 'none';
                });
        } else {
            sizeSelect.disabled = true;
            sizeHelp.style.display = 'block';
            sizeSelect.innerHTML = '<option value="">All Sizes</option>';
        }
    });

    if (categorySelect.value) {
        categorySelect.dispatchEvent(new Event('change'));
    }
});
</script>

@endsection