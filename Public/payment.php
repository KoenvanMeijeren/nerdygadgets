<?php
require_once __DIR__ . '/../Src/header.php';

$cart = getCart();
$price = $cart->getTotalPrice();

if (empty($price) || empty($cart->getItems())) {
    addUserError('Er zijn geen producten in de winkelwagen gevonden om af te rekenen.');
    redirect(getUrl('shoppingcart.php'));
}

initiatePayment(number_format($price, 2, '.', ''), random_int(1,9999));

require_once __DIR__ . '/../Src/footer.php';
