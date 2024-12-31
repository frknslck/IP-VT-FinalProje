<div class="card">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Order Management</h2>
    </div>
    <div class="card-body">
        @if($orders->isEmpty())
            <p>No orders to process or finalize.</p>
        @else
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->order_number }}</td>
                            <td>{{ ucfirst($order->status) }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <form action="{{ route('action-panel.update-order', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-info btn-sm">Mark as Processing</button>
                                    </form>
                                @elseif($order->status == 'processing')
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#orderModal{{ $order->id }}">Finalize</button>
                                @elseif($order->status == 'completed')
                                    <form action="{{ route('action-panel.finalize-order', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Cancel Order</button>
                                    </form>
                                @endif
                            </td>
                        </tr>

                        <div class="modal fade" id="orderModal{{ $order->id }}" tabindex="-1" aria-labelledby="orderModalLabel{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="orderModalLabel{{ $order->id }}">Update Order Status for Order #{{ $order->order_number }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('action-panel.finalize-order', $order->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="status" class="form-label">Select Status</label>
                                                <select id="status" name="status" class="form-control" required>
                                                    <option value="completed">Completed</option>
                                                    <option value="cancelled">Cancelled</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="statusMessage" class="form-label">Enter a message</label>
                                                <textarea id="statusMessage" name="message" class="form-control" rows="3"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

