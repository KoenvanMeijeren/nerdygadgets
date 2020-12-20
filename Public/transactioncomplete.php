<?php
require_once __DIR__ . '/../Src/header.php';

$cart = get_cart();
$price = $cart->getTotalPrice();

if (empty($price) || empty($cart->getItems())) {
    add_user_error('Er zijn geen producten in de winkelwagen gevonden om af te rekenen.');
    redirect(get_url('shoppingcart.php'));
}

$orderSuccessful = checkPayment(session_get('paymentId'));

$customerId = (int) session_get('customer_id', 0);
$loggedIn = (bool) session_get('LoggedIn', false);
$customer = getCustomer($customerId);

// Always clear the payment process in order to be able to start a new payment.
session_key_unset('customer_id');
session_key_unset('paymentId');

if ($orderSuccessful) {
    $connection = getDatabaseConnection(config_get('database_user_create_or_update'), config_get('database_password_create_or_update'));
    beginTransaction($connection);

    try {
        $products = $cart->getItems();

        $dateTime = new DateTime();
        $currentDate = $dateTime->format('Y-m-d');

        $dateTime->modify('+1 day');
        $deliveryDate = $dateTime->format('Y-m-d');

        $orderId = createOrder($customerId, $currentDate, $deliveryDate, $connection);

        foreach ($products as $product) {
            $productId = (int) ($product['id'] ?? 0);
            $productAmount = (int) ($product['amount'] ?? 0);
            $productFromDB = getProduct($productId);

            createOrderLine($orderId, $productFromDB, $productAmount, $currentDate, $connection);
        }

        commitTransaction($connection);

        reset_cart();
        add_user_message('De bestelling is succesvol geplaatst.');
    } catch (Exception $exception) {
        $orderSuccessful = false;
        add_user_error('Bestelling kon niet worden geplaatst. Probeer het opnieuw of neem contact op met NerdyGadgets.');
        rollbackTransaction($connection);
    }
} elseif (!empty($customerId) && !$loggedIn) {
    deleteCustomer($customerId);
}

include __DIR__ . '/../Src/Html/alert.php'; ?>

<div class="container-fluid">
    <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
        <?php include_once __DIR__ . '/../Src/Html/order-progress.php'; ?>

        <div class="row">
            <div class="col-sm-12">
                <?php if ($orderSuccessful) : ?>
                    <h1 class="text-success">Bestelling is geplaatst</h1>
                    <p>
                        Uw bestelling is succesvol geplaatst en wordt morgen bezorgt.
                    </p>

                    <h1>Bezorggegevens</h1>
                    <ul class="list-group list-group-flush w-50">
                        <li class="list-group-item bg-dark">
                            Naam: <b class="float-right"><?= $customer['PrivateCustomerName'] ?? '' ?></b>
                        </li>
                        <li class="list-group-item bg-dark">
                            Adres: <b class="float-right"><?= $customer['DeliveryAddressLine1'] ?? '' ?></b>
                        </li>
                        <li class="list-group-item bg-dark">
                            Postcode: <b class="float-right"><?= $customer['DeliveryPostalCode'] ?? '' ?></b>
                        </li>
                        <li class="list-group-item bg-dark">
                            Woonplaats: <b class="float-right"><?= $customer['CityName'] ?? '' ?></b>
                        </li>
                        <li class="list-group-item bg-dark">
                            Telefoonnummer: <b class="float-right"><?= $customer['PhoneNumber'] ?? '' ?></b>
                        </li>
                    </ul>

                    <div class="form-group mt-5 text-center">
                        <a class="btn btn-success my-4" href="<?= get_url('index.php') ?>">
                            3. Afronden
                        </a>
                    </div>
                <?php else : ?>
                    <h1 class="text-danger text-center">Producten afrekenen is mislukt</h1>

                    <div class="form-group mt-5 text-center">
                        <a href="<?= get_url('checkout.php') ?>" class="btn btn-success my-4">
                            Opnieuw afrekenen
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/../Src/footer.php';
?>