<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Coupon Management</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('action-panel.store-coupon') }}" method="POST" class="mb-4">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="code" class="form-label">Coupon Code</label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                           id="code" name="code" required value="{{ old('code') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="type" class="form-label">Coupon Type</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                        <option value="fixed">Fixed</option>
                        <option value="percentage">Percentage</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="value" class="form-label">Coupon Value</label>
                    <input type="number" class="form-control @error('value') is-invalid @enderror" 
                           id="value" name="value" required value="{{ old('value') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="usage_limit" class="form-label">Usage Limit</label>
                    <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                           id="usage_limit" name="usage_limit" required value="{{ old('usage_limit') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="is_active" class="form-label">Active Status</label>
                    <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="start_date" class="form-label">Start Date</label>
                    <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" 
                           id="start_date" name="start_date" required value="{{ old('start_date') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="end_date" class="form-label">End Date</label>
                    <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" 
                           id="end_date" name="end_date" required value="{{ old('end_date') }}">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i> Add Coupon
            </button>
        </form>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Coupon Code</th>
                        <th>Type</th>
                        <th>Value</th>
                        <th>Usage Limit</th>
                        <th>Usage Count</th>
                        <th>Status</th>
                        <th>Validity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($coupons as $coupon)
                        <tr>
                            <td>{{ $coupon->id }}</td>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->type }}</td>
                            <td>{{ $coupon->type == 'fixed' ? $coupon->value.'$' : $coupon->value.'%' }}</td>
                            <td>{{ $coupon->usage_limit }}</td>
                            <td>{{ $coupon->used_count }}</td>
                            <td>
                                <span class="badge {{ $coupon->is_active ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $coupon->start_date->format('d.m.Y H:i') }} - {{ $coupon->end_date->format('d.m.Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary me-1" 
                                            data-bs-toggle="modal" data-bs-target="#editModal{{ $coupon->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <form action="{{ route('action-panel.delete-coupon', $coupon) }}" 
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

                        <div class="modal fade" id="editModal{{ $coupon->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Coupon: {{ $coupon->code }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="{{ route('action-panel.update-coupon', $coupon) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="edit_code{{ $coupon->id }}" class="form-label">Coupon Code</label>
                                                <input type="text" class="form-control" id="edit_code{{ $coupon->id }}" 
                                                       name="code" value="{{ $coupon->code }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_type{{ $coupon->id }}" class="form-label">Type</label>
                                                <select class="form-select" id="edit_type{{ $coupon->id }}" name="type" required>
                                                    <option value="fixed" {{ $coupon->type == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                                    <option value="percentage" {{ $coupon->type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_value{{ $coupon->id }}" class="form-label">Value</label>
                                                <input type="number" class="form-control" id="edit_value{{ $coupon->id }}" 
                                                       name="value" value="{{ $coupon->value }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_usage_limit{{ $coupon->id }}" class="form-label">Usage Limit</label>
                                                <input type="number" class="form-control" id="edit_usage_limit{{ $coupon->id }}" 
                                                       name="usage_limit" value="{{ $coupon->usage_limit }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_is_active{{ $coupon->id }}" class="form-label">Active Status</label>
                                                <select class="form-select" id="edit_is_active{{ $coupon->id }}" name="is_active" required>
                                                    <option value="1" {{ $coupon->is_active ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ !$coupon->is_active ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_start_date{{ $coupon->id }}" class="form-label">Start Date</label>
                                                <input type="datetime-local" class="form-control" id="edit_start_date{{ $coupon->id }}" 
                                                       name="start_date" value="{{ $coupon->start_date->format('Y-m-d\TH:i') }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_end_date{{ $coupon->id }}" class="form-label">End Date</label>
                                                <input type="datetime-local" class="form-control" id="edit_end_date{{ $coupon->id }}" 
                                                       name="end_date" value="{{ $coupon->end_date->format('Y-m-d\TH:i') }}" required>
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
