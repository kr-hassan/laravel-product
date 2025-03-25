<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
</head>
<body>
<h1>Product List</h1>

<table>
    <thead>
    <tr>
        <th>Product ID</th>
        <th>Image</th>
        <th>Name</th>
        <th>Description</th>
        <th>Price</th>
    </tr>
    </thead>
    <tbody>
    @foreach($allProducts as $product)
        <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->description }}</td>
            <td>${{ number_format($product->price, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    // Configure Pusher
    Pusher.logToConsole = true; // Remove in production
    var pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
        cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    });

    // Subscribe to the 'products-channel'
    var channel = pusher.subscribe("products-channel");

    // Listen for 'product-added' event
    channel.bind("product-added", function(data) {
        let product = data.product;

        // Dynamically add new product to the table
        let newRow = `
                <tr>
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.description}</td>
                    <td>$${product.price.toFixed(2)}</td>
                </tr>
            `;
        document.querySelector("tbody").innerHTML += newRow;
    });
</script>
</body>
</html>
