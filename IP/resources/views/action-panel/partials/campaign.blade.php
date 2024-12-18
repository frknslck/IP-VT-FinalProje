<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Campaign Management</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('action-panel.store-campaign') }}" method="POST" class="mb-4">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="name" class="form-label">Campaign Name</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                           id="name" name="name" required value="{{ old('name') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="type" class="form-label">Campaign Type</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="value" class="form-label">Campaign Value</label>
                    <input type="number" class="form-control @error('value') is-invalid @enderror" 
                           id="value" name="value" min="0" required value="{{ old('value') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="image_url" class="form-label">Campaign Image URL</label>
                    <input type="text" class="form-control @error('image_url') is-invalid @enderror" 
                           id="image_url" name="image_url" required value="{{ old('image_url') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="start_date" class="form-label">Campaign Start Date</label>
                    <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                           id="start_date" name="start_date" required value="{{ old('start_date') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="end_date" class="form-label">Campaign End Date</label>
                    <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                           id="end_date" name="end_date" required value="{{ old('end_date') }}">
                </div>
                <div class="col-md-4 mb-3">
                <label for="is_active" class="form-label">Active Status</label>
                    <select class="form-select @error('is_active') is-invalid @enderror" 
                            id="is_active" name="is_active" required>
                        <option value="1" {{ old('is_active') == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add to Campaigns
            </button>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Campaign Name</th>
                        <th>Campaign Type</th>
                        <th>Campaign Value</th>
                        <th>Campaign Image URL</th>
                        <th>Validity Date</th>
                        <th>Is Active?</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $campaign)
                        <tr>
                            <td>{{ $campaign->id }}</td>
                            <td>{{ $campaign->name }}</td>
                            <td>{{ $campaign->type }}</td>
                            <td>{{ $campaign->type == 'fixed' ? $campaign->value.'$' : $campaign->value.'%' }}</td>
                            <td>{{ $campaign->image_url }}</td>
                            <td>{{ $campaign->start_date->format('d.m.Y H:i')." - ".$campaign->end_date->format('d.m.Y H:i') }}</td>
                            <td>
                                <span class="badge {{ $campaign->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $campaign->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $campaign->created_at->format('d.m.Y H:i') }}</td>
                            <td>{{ $campaign->updated_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $campaign->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <form action="{{ route('action-panel.delete-campaign', $campaign) }}" 
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

                        <div class="modal fade" id="editModal{{ $campaign->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Campaign: {{ $campaign->name }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('action-panel.update-campaign', $campaign) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="edit_name{{ $campaign->id }}" class="form-label">Campaign Name</label>
                                                <input type="text" class="form-control" 
                                                    id="edit_name{{ $campaign->id }}" 
                                                    name="name" value="{{ $campaign->name }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_type{{ $campaign->id }}" class="form-label">Campaign Type</label>
                                                <select class="form-select" 
                                                        id="edit_type{{ $campaign->id }}" 
                                                        name="type" required>
                                                    <option value="fixed" {{ $campaign->type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                                    <option value="percentage" {{ $campaign->type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_value{{ $campaign->id }}" class="form-label">Campaign Value</label>
                                                <input type="number" class="form-control" 
                                                    id="edit_value{{ $campaign->id }}" 
                                                    name="value" min="0" value="{{ $campaign->value }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_image_url{{ $campaign->id }}" class="form-label">Campaign Image URL</label>
                                                <input type="text" class="form-control" 
                                                    id="edit_image_url{{ $campaign->id }}" 
                                                    name="image_url" value="{{ $campaign->image_url }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_start_date{{ $campaign->id }}" class="form-label">Campaign Start Date</label>
                                                <input type="datetime-local" class="form-control" 
                                                    id="edit_start_date{{ $campaign->id }}" 
                                                    name="start_date" value="{{ $campaign->start_date->format('Y-m-d\TH:i') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_end_date{{ $campaign->id }}" class="form-label">Campaign End Date</label>
                                                <input type="datetime-local" class="form-control" 
                                                    id="edit_end_date{{ $campaign->id }}" 
                                                    name="end_date" value="{{ $campaign->end_date->format('Y-m-d\TH:i') }}" required>
                                            </div>
                                            <div class="mb-3">
                                            <label for="edit_best_seller{{ $campaign->id }}" class="form-label">Best Seller</label>
                                                <select class="form-select" 
                                                        id="edit_best_seller{{ $campaign->id }}" 
                                                        name="best_seller">
                                                    <option value="1" {{ $campaign->best_seller ? 'selected' : '' }}>Yes</option>
                                                    <option value="0" {{ !$campaign->best_seller ? 'selected' : '' }}>No</option>
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
