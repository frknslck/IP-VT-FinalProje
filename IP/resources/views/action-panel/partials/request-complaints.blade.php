<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Requests and Complaints</h2>
    </div>
    <div class="card-body">
        @if($rcs->isEmpty())
            <p>No pending requests or complaints.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Subject</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rcs as $complaint)
                        <tr>
                            <td>{{ $complaint->RorC }}</td>
                            <td>{{ $complaint->subject }}</td>
                            <td>{{ ucfirst($complaint->category) }}</td>
                            <td>
                                {{ ucfirst($complaint->status) }}
                            </td>
                            <td>{{ $complaint->created_at->format('d.m.Y H:i') }}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#complaintModal{{ $complaint->id }}">Read</button>
                                @if($complaint->status == 'Pending')
                                    <a href="{{ route('action-panel.review-requests-complaints', $complaint->id) }}" class="btn btn-info btn-sm text-white">Mark as Reviewed</a>
                                @elseif($complaint->status == 'Reviewed')
                                    <a href="{{ route('action-panel.resolve-requests-complaints', $complaint->id) }}" class="btn btn-success btn-sm">Mark as Resolved</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

@foreach($rcs as $complaint)
    <div class="modal fade" id="complaintModal{{ $complaint->id }}" tabindex="-1" aria-labelledby="complaintModalLabel{{ $complaint->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="complaintModalLabel{{ $complaint->id }}">{{$complaint->RorC}} Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Subject:</strong> {{ $complaint->subject }}</p>
                    <p><strong>Category:</strong> {{ ucfirst($complaint->category) }}</p>
                    <p><strong>Message:</strong></p>
                    <p>{{ $complaint->message }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
