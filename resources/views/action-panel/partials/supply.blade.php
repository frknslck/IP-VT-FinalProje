<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h2 class="mb-0">Supply Management</h2>
    </div>
    <div class="card-body">
        <form id="product-search-form">
            <div class="mb-3">
                <label for="product_id" class="form-label">Product ID</label>
                <input type="text" class="form-control" id="product_id" placeholder="Enter Product ID">
            </div>
            <button type="button" class="btn btn-primary" id="apply-product">Apply</button>
        </form>
    </div>

    <div id="variant-list-container" style="display: none;">
        <div class="card">
            <div class="card-header">Variants for Product</div>
            <div class="card-body">
                <div id="variant-list" class="row"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="supplyModal" tabindex="-1" aria-labelledby="supplyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="supply-form" action="{{ route('action-panel.store-supplier') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="supplyModalLabel">Supply Variant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="variant_id" name="variant_id">
                    <div class="mb-3">
                        <label for="supplier_id" class="form-label">Supplier</label>
                        <select id="supplier_id" name="supplier_id" class="form-select">
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name.' - '.$supplier->contact_person }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label for="cost" class="form-label">Cost</label>
                        <input type="number" class="form-control" id="cost" name="cost" min="0" step="0.01" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('apply-product').addEventListener('click', function () {
            const productId = document.getElementById('product_id').value;

            if (!productId) {
                alert('Please enter a Product ID.');
                return;
            }

            fetch(`/actions/supply/products/${productId}/`)
                .then(response => response.json())
                .then(data => {
                    const variantList = document.getElementById('variant-list');
                    variantList.innerHTML = '';

                    if (data.variants.length > 0) {
                        data.variants.forEach(variant => {
                            const variantCard = `
                                <div class="col-md-4 mb-3">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">Variant: ${variant.name}</h5>
                                            <p>SKU: ${variant.sku}</p>
                                            <p>Stock: ${variant.stock}</p>
                                            <button class="btn btn-success btn-supply" data-id="${variant.id}">Supply</button>
                                        </div>
                                    </div>
                                </div>`;
                            variantList.insertAdjacentHTML('beforeend', variantCard);
                        });

                        document.getElementById('variant-list-container').style.display = 'block';

                        document.querySelectorAll('.btn-supply').forEach(button => {
                            button.addEventListener('click', function () {
                                const variantId = this.getAttribute('data-id');
                                document.getElementById('variant_id').value = variantId;
                                document.getElementById('supplier_id').value = '';
                                document.getElementById('cost').value = '';
                                const modal = new bootstrap.Modal(document.getElementById('supplyModal'));
                                modal.show();
                            });
                        });
                    } else {
                        alert('No variants found for this product.');
                    }
                })
                .catch(() => {
                    alert('Product not found or no variants available.');
                });
        });

        document.getElementById('supplier_id').addEventListener('change', function () {
            const variantId = document.getElementById('variant_id').value;
            const supplierId = this.value;

            if (variantId && supplierId) {
                fetch('{{ route('action-panel.get-suggested-cost') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ variant_id: variantId, supplier_id: supplierId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.suggested_cost) {
                        document.getElementById('cost').value = data.suggested_cost;
                    }
                })
                .catch(error => console.error('Error fetching suggested cost:', error));
            }
        });
    });
</script>
