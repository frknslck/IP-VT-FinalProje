<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Product Management</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('action-panel.store-product') }}" method="POST" class="mb-4">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" required value="{{ old('name') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="description" class="form-label">Product Description</label>
                    <input type="text" class="form-control @error('description') is-invalid @enderror" 
                           id="description" name="description" required value="{{ old('description') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="image_url" class="form-label">Product Image URL</label>
                    <input type="text" class="form-control @error('image_url') is-invalid @enderror" 
                           id="image_url" name="image_url" required value="{{ old('image_url') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                           id="price" name="price" required value="{{ old('price') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="brand_id" class="form-label">Brand</label>
                    <select class="form-select @error('brand_id') is-invalid @enderror" 
                            id="brand_id" name="brand_id" required>
                        <option value="">Select Brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="categories" class="form-label">Categories</label>
                    <select class="form-select @error('categories') is-invalid @enderror" 
                            id="categories" name="categories[]" multiple>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ collect(old('categories'))->contains($category->id) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="is_active" class="form-label">Active Status</label>
                    <select class="form-select @error('is_active') is-invalid @enderror" 
                            id="is_active" name="is_active" required>
                        <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="best_seller" class="form-label">Best Seller</label>
                    <select class="form-select @error('best_seller') is-invalid @enderror" 
                            id="best_seller" name="best_seller" required>
                        <option value="1" {{ old('best_seller') == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ old('best_seller') == 0 ? 'selected' : '' }}>No</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Product
            </button>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Active Status</th>
                        <th>Best Seller</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->brand->name ?? 'No Brand' }}</td>
                            <td>{{ $product->categories->first()->name ?? 'No Brand' }}</td>
                            <td>
                                <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $product->best_seller ? 'bg-warning text-dark' : 'bg-secondary' }}">
                                    {{ $product->best_seller ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>{{ $product->created_at->format('d.m.Y H:i') }}</td>
                            <td>{{ $product->updated_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <form action="{{ route('action-panel.destroy-product', $product) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Product: {{ $product->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('action-panel.update-product', $product) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="edit_name{{ $product->id }}" class="form-label">Product Name</label>
                                                <input type="text" class="form-control" 
                                                       id="edit_name{{ $product->id }}" 
                                                       name="name" 
                                                       value="{{ $product->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_description{{ $product->id }}" class="form-label">Description</label>
                                                <input type="text" class="form-control" 
                                                       id="edit_description{{ $product->id }}" 
                                                       name="description" 
                                                       value="{{ $product->description }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_image_url{{ $product->id }}" class="form-label">Image URL</label>
                                                <input type="text" class="form-control" 
                                                       id="edit_image_url{{ $product->id }}" 
                                                       name="image_url" 
                                                       value="{{ $product->image_url }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_price{{ $product->id }}" class="form-label">Price</label>
                                                <input type="number" step="0.01" class="form-control" 
                                                       id="edit_price{{ $product->id }}" 
                                                       name="price" 
                                                       value="{{ $product->price }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_brand_id{{ $product->id }}" class="form-label">Brand</label>
                                                <select class="form-select" 
                                                        id="edit_brand_id{{ $product->id }}" 
                                                        name="brand_id">
                                                    @foreach($brands as $brand)
                                                        <option value="{{ $brand->id }}" 
                                                                {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                                            {{ $brand->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_categories{{ $product->id }}" class="form-label">Categories</label>
                                                <select id="edit_categories{{ $product->id }}" name="categories[]" 
                                                        class="form-select" multiple>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" 
                                                                {{ $product->categories->contains($category->id) ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_is_active{{ $product->id }}" class="form-label">Active Status</label>
                                                <select class="form-select" 
                                                        id="edit_is_active{{ $product->id }}" 
                                                        name="is_active">
                                                    <option value="1" {{ $product->is_active ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ !$product->is_active ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_best_seller{{ $product->id }}" class="form-label">Best Seller</label>
                                                <select class="form-select" 
                                                        id="edit_best_seller{{ $product->id }}" 
                                                        name="best_seller">
                                                    <option value="1" {{ $product->best_seller ? 'selected' : '' }}>Yes</option>
                                                    <option value="0" {{ !$product->best_seller ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>