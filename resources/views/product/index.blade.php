
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
   


 @include('layouts.nav')


    <div class="container">
        <h1>Add Stocks</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($stocks as $stock)
                <tr>
                    <td>{{ $stock->product->name }}</td>
                    <td>{{ $stock->product->description }}</td>
                    <td>{{ $stock->product->price }}</td>
                    <td>{{ $stock->quantity }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <form id="stockForm" method="post" action="{{ url('/stocks/store') }}" >
            @csrf
            <div id="stockRows">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <select class="form-select product-select" name="stocks[0][product_id]" required>
                            <option value="" selected disabled>Select Product</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" class="form-control" name="stocks[0][quantity]" placeholder="Quantity" required>
                    </div>
                    <div class="col-md-3">
                        <input type="text" class="form-control" name="stocks[0][previous_stock]" placeholder="Previous Stock" readonly>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger remove-row">Remove</button>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-12">
                    <button type="button" id="addRow" class="btn btn-success">Add Row</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            let productOptions = '';

// Get products using AJAX
$.ajax({
    url: '{{ route('products.get') }}',
    type: 'GET',
    success: function (response) {
        response.forEach(function (product) {
            productOptions += '<option value="' + product.id + '">' + product.name + '</option>';
        });

        $('.product-select').append(productOptions);
    }
});

// Fetch and populate previous/current stock on product selection
        $(document).on('change', '.product-select', function () {
            var selectedProductId = $(this).val();
            var currentRow = $(this).closest('.row');
            var previousStockField = currentRow.find('input[name^="stocks["][name$="][previous_stock]"]');

            // Make AJAX request to retrieve previous/current stock
            $.ajax({
                url: '/stocks/get-stock', // Replace with your route URL to fetch stock information
                type: 'GET',
                data: { product_id: selectedProductId },
                success: function (response) {
                    previousStockField.val(response.stock);
                }
            });
        });


            // Add row
            $('#addRow').click(function () {
                let row = $('#stockRows .row').last().clone();
                row.find('input').val('');
                row.find('.product-select').val('');
                $('#stockRows').append(row);
            });

            // Remove row
            $(document).on('click', '.remove-row', function () {
                if ($('#stockRows .row').length > 1) {
                    $(this).closest('.row').remove();
                }
            });

            // Submit form using AJAX
            $('#stockForm').submit(function (event) {
                event.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (response) {
                        $('#stockForm')[0].reset();
                        $('#stockRows .row').not(':first').remove();
                        $('#stockForm').prepend('<div class="alert alert-success">' + response.success + '</div>');
                        
                    },
                    error: function (xhr) {
                        if (xhr.responseJSON.errors) {
                            let errorsHtml = '';

                            $.each(xhr.responseJSON.errors, function (key, value) {
                                errorsHtml += '<li>' + value + '</li>';
                            });

                            $('#stockForm').prepend('<div class="alert alert-danger"><ul>' + errorsHtml + '</ul></div>');
                        }
                    }
                });
            });
        });
    </script>

