<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Notification Service</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('action-panel.send-notification') }}" method="POST" class="mb-4">
            @csrf
            <div class="mb-3">
                <label for="from" class="form-label">From</label>
                <input type="text" class="form-control" id="from" name="from" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Message</label>
                <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label for="users" class="form-label">Send To</label>
                <select class="form-select" id="users" name="user_ids[]" multiple required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-send"></i> Send Notification
            </button>
        </form>
    </div>
</div>
