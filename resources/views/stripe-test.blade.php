<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Stripe Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>

<body>
    <form action="/" method="post" id="payment-form">
        <div class="form-row">
            <label for="card-element">
                Credit or debit card
            </label>
            <div id="card-element">
                <!-- A Stripe Element will be inserted here. -->
            </div>

            <!-- Used to display Element errors. -->
            <div id="card-errors" role="alert"></div>
        </div>

        <button type="submit" id="submit-button">Create Payment Method</button>

        <input type="text" id="payment-method-id" />
    </form>

    <script type="text/javascript">
        // Create an instance of the Stripe object with your publishable API key
        var stripe = Stripe(
            "{{ $stripe_publishable_key }}"
        );

        var elements = stripe.elements();

        // Create an instance of the card Element.
        var card = elements.create("card");

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount("#card-element");

        var paymentMethodId = document.getElementById("payment-method-id");

        var form = document.getElementById("payment-form");
        form.addEventListener("submit", function(event) {
            event.preventDefault();
            stripe.createPaymentMethod({
                    type: "card",
                    card: card,
                    billing_details: {
                        name: "Jan Kowalski",
                    },
                })
                .then(function(result) {
                    console.log(result);
                    if (result.paymentMethod) {
                        paymentMethodId.value = result.paymentMethod.id;
                    }
                });
        });
    </script>
</body>

</html>
