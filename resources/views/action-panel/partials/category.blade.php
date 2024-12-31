<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Category Management</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('action-panel.store-category') }}" method="POST" class="mb-4">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">Category Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" required value="{{ old('name') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="slug" class="form-label">Category Slug</label>
                    <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                           id="slug" name="slug" required value="{{ old('slug') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="image_url" class="form-label">Category Image URL</label>
                    <input type="text" class="form-control @error('image_url') is-invalid @enderror" 
                           id="image_url" name="image_url" required value="{{ old('image_url') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="parent_id" class="form-label">Parent Setting</label>
                    <select class="form-select @error('parent_id') is-invalid @enderror" 
                            id="parent_id" name="parent_id">
                        <option value="">Add as a parent category</option>
                        @foreach($categories->whereNull('parent_id') as $parentCategory)
                            <option value="{{ $parentCategory->id }}" {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                {{ $parentCategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add to Categories
            </button>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Category Slug</th>
                        <th>Parent Category</th>
                        <th>Category Image URL</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td>
                                @if($category->parent_id)
                                    {{ $categories->find($category->parent_id)->name }}
                                @else
                                    <span class="badge bg-info">Parent Category</span>
                                @endif
                            </td>
                            <td>{{ $category->image_url }}</td>
                            <td>{{ $category->created_at->format('d.m.Y H:i') }}</td>
                            <td>{{ $category->updated_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $category->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <form action="{{ route('action-panel.delete-category', $category) }}" 
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

                        <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Kategori Düzenle: {{ $category->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('action-panel.update-category', $category) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="edit_name{{ $category->id }}" class="form-label">Category Name</label>
                                                <input type="text" class="form-control" 
                                                       id="edit_name{{ $category->id }}" 
                                                       name="name" 
                                                       value="{{ $category->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_slug{{ $category->id }}" class="form-label">Category Slug</label>
                                                <input type="text" class="form-control" 
                                                       id="edit_slug{{ $category->id }}" 
                                                       name="slug" 
                                                       value="{{ $category->slug }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="image_url{{ $category->id }}" class="form-label">Category Image URL</label>
                                                <input type="text" class="form-control" 
                                                       id="image_url{{ $category->id }}" 
                                                       name="image_url" 
                                                       value="{{ $category->image_url }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_parent_id{{ $category->id }}" class="form-label">Parent Category</label>
                                                <select class="form-select" 
                                                        id="edit_parent_id{{ $category->id }}" 
                                                        name="parent_id">
                                                    <option value="">Change as a parent category</option>
                                                    @foreach($categories->whereNull('parent_id')->where('id', '!=', $category->id) as $parentCategory)
                                                        <option value="{{ $parentCategory->id }}" 
                                                                {{ $category->parent_id == $parentCategory->id ? 'selected' : '' }}>
                                                            {{ $parentCategory->name }}
                                                        </option>
                                                    @endforeach
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