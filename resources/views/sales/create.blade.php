@extends('layouts.app')

@section('title', 'Nouvelle vente')

@section('styles')
<style>
.sale-container {
    max-width: 900px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.sale-card {
    background: white;
    border-radius: 0.75rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    padding: 2rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-label {
    font-weight: 500;
    margin-bottom: 0.25rem;
    display: block;
}

.form-select, .form-input {
    width: 100%;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    border: 1px solid #d1d5db;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 1rem;
}

.table th, .table td {
    padding: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
    text-align: left;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    border: none;
}

.btn-primary { background-color: #2563eb; color: white; }
.btn-primary:hover { background-color: #1e40af; }
.btn-secondary { background-color: #f3f4f6; color: #374151; }
.btn-secondary:hover { background-color: #e5e7eb; }

.total-row { font-weight: 700; }
</style>
@endsection

@section('content')
<div class="sale-container">
    <h1>Nouvelle vente</h1>

    <form action="{{ route('sales.store') }}" method="POST" id="sale-form">
        @csrf

        <div class="form-group">
            <label for="client_id" class="form-label">Client</label>
            <select name="client_id" id="client_id" class="form-select">
                <option value="">-- Client inconnu --</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                @endforeach
            </select>
        </div>

        <table class="table" id="products-table">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>
                        <button type="button" class="btn btn-secondary" id="add-product">+</button>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="product-row">
                    <td>
                        <select name="products[0][product_id]" class="form-select product-select" required>
                            <option value="">-- Choisir un produit --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td class="unit-price">0 FCFA</td>
                    <td>
                        <input type="number" name="products[0][quantity]" class="form-input quantity-input" min="1" value="1" required>
                    </td>
                    <td class="line-total">0 FCFA</td>
                    <td>
                        <button type="button" class="btn btn-secondary remove-product">-</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="3">Total général</td>
                    <td id="grand-total">0 FCFA</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>

        <button type="submit" class="btn btn-primary">Enregistrer la vente</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let rowIndex = 1;

    function updateLineTotal(row) {
        const select = row.querySelector('.product-select');
        const quantityInput = row.querySelector('.quantity-input');
        const unitPriceTd = row.querySelector('.unit-price');
        const lineTotalTd = row.querySelector('.line-total');

        const price = parseFloat(select.selectedOptions[0]?.dataset.price || 0);
        const qty = parseInt(quantityInput.value) || 0;
        const total = price * qty;

        unitPriceTd.textContent = price.toLocaleString('fr-FR') + ' FCFA';
        lineTotalTd.textContent = total.toLocaleString('fr-FR') + ' FCFA';

        updateGrandTotal();
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        document.querySelectorAll('.product-row').forEach(row => {
            const totalText = row.querySelector('.line-total').textContent;
            const total = parseFloat(totalText.replace(/\s|FCFA/g, '')) || 0;
            grandTotal += total;
        });
        document.getElementById('grand-total').textContent = grandTotal.toLocaleString('fr-FR') + ' FCFA';
    }

    document.getElementById('products-table').addEventListener('change', function(e) {
        if (e.target.classList.contains('product-select')) {
            updateLineTotal(e.target.closest('.product-row'));
        }
    });

    document.getElementById('products-table').addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            updateLineTotal(e.target.closest('.product-row'));
        }
    });

    document.getElementById('add-product').addEventListener('click', function() {
        const tbody = document.querySelector('#products-table tbody');
        const newRow = tbody.querySelector('tr').cloneNode(true);

        // Reset values
        newRow.querySelectorAll('select, input').forEach(el => {
            el.value = '';
            if(el.name.includes('quantity')) el.value = 1;
        });
        newRow.querySelector('.unit-price').textContent = '0 FCFA';
        newRow.querySelector('.line-total').textContent = '0 FCFA';

        // Update input names
        newRow.querySelectorAll('select').forEach(select => {
            select.name = `products[${rowIndex}][product_id]`;
        });
        newRow.querySelectorAll('input').forEach(input => {
            input.name = `products[${rowIndex}][quantity]`;
        });

        tbody.appendChild(newRow);
        rowIndex++;
    });

    document.getElementById('products-table').addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-product')) {
            const row = e.target.closest('tr');
            if (document.querySelectorAll('.product-row').length > 1) {
                row.remove();
                updateGrandTotal();
            }
        }
    });

    // Initial calculation
    document.querySelectorAll('.product-row').forEach(updateLineTotal);
});
</script>
@endsection
