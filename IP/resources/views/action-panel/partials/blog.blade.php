<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Blog Management</h2>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($blogs as $blog)
                    <tr>
                        <td>{{ $blog->title }}</td>
                        <td>{{ $blog->user->name }}</td>
                        <td>
                            <span class="badge bg-{{ $blog->status == 'published' ? 'success' : ($blog->status == 'draft' ? 'warning' : 'secondary') }}">
                                {{ ucfirst($blog->status) }}
                            </span>
                        </td>
                        <td>{{ $blog->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if($user->hasRole('Admin') || $blog->user_id == $user->id)
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editBlogModal{{ $blog->id }}">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                            @endif
                            <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this blog?')">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Edit Blog Modal -->
                    <div class="modal fade" id="editBlogModal{{ $blog->id }}" tabindex="-1" aria-labelledby="editBlogModalLabel{{ $blog->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editBlogModalLabel{{ $blog->id }}">Edit Blog</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form action="{{ route('blogs.update', $blog->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="title" class="form-label">Title</label>
                                            <input type="text" class="form-control" id="title" name="title" value="{{ $blog->title }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="content" class="form-label">Content</label>
                                            <textarea class="form-control" id="content" name="content" rows="4" required>{{ $blog->content }}</textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="image_url" class="form-label">Image URL</label>
                                            <input type="url" class="form-control" id="image_url" name="image_url" value="{{ $blog->image_url }}">
                                        </div>
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-select" id="status" name="status" required>
                                                <option value="draft" {{ $blog->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ $blog->status == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="archived" {{ $blog->status == 'archived' ? 'selected' : '' }}>Archived</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
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

