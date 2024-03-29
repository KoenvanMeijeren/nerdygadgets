<?php
require_once __DIR__ . "/../Src/header.php";

csrfValidate(getCurrentUrl());

$cart = getCart();
$price = $cart->getTotalPrice();

if (empty($price) || empty($cart->getItems())) {
    addUserError('Er zijn geen producten in de winkelwagen gevonden om af te rekenen.');
    redirect(getUrl('shoppingcart.php'));
}

$loggedIn = (bool) sessionGet('LoggedIn', false);
$personId = sessionGet('personID', 0);
$account = getCustomerByPeople($personId);

$name = getFormDataPost('name', $account['PrivateCustomerName'] ?? '');
$postalCode = getFormDataPost('postalcode', $account['DeliveryPostalCode'] ?? '');
$address = getFormDataPost('address', $account['DeliveryAddressLine1'] ?? '');
$city = getFormDataPost('city', $account['CityName'] ?? '');
$phoneNumber = getFormDataPost('phonenumber', $account['PhoneNumber'] ?? '');

if (isset($_POST['checkout'])) {
    $valuesValid = true;
    if (empty($name) || empty($postalCode) || empty($address) || empty($city) || empty($phoneNumber)) {
        $valuesValid = false;
        addUserError('Niet all verplichte velden met een * zijn ingevuld.');
    }

    if ($valuesValid && !preg_match('/^[1-9][0-9]{3}?(?!sa|sd|ss)[a-z]{2}$/i', $postalCode)) {
        addUserError('Ongeldige postcode opgegeven.');
        $valuesValid = false;
    }

    if ($valuesValid) {
        $customerId = $account['PrivateCustomerID'] ?? 0;
        if (empty($customerId)) {
            $customerId = createCustomer($name, $phoneNumber, $address, $postalCode, $city);
        }

        if (!empty($customerId)) {
            sessionSave('customer_id', $customerId, true);
            redirect('payment.php');
        }
    }
}

?>
<?php include __DIR__ . '/../Src/Html/alert.php'; ?>

    <div class="container-fluid">
        <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
            <?php include_once __DIR__ . '/../Src/Html/order-progress.php'; ?>

            <div class="row">
                <div class="col-sm-12">
                    <form class="text-center w-100" action="<?= getUrl('checkout.php') ?>" method="post">
                        <input type="hidden" name="token" value="<?=csrfGetToken()?>"/>
                        <div class="form-group form-row">
                            <label for="name" class="col-sm-3 text-left">Naam <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control col-sm-9"
                                   placeholder="Naam" value="<?= $name ?>" <?= $loggedIn && !authorizeAdmin() ? 'disabled' : '' ?>>
                        </div>

                        <div class="form-group form-row">
                            <label for="postalcode" class="col-sm-3 text-left">Postcode <span class="text-danger">*</span></label>
                            <input type="text" maxlength="6" id="postalcode" name="postalcode" class="form-control col-sm-9"
                                   placeholder="Postcode" value="<?= $postalCode ?>" <?= $loggedIn && !authorizeAdmin() ? 'disabled' : '' ?>>
                        </div>

                        <div class="form-group form-row">
                            <label for="address" class="col-sm-3 text-left">Adres <span class="text-danger">*</span></label>
                            <input type="text" id="address" name="address" class="form-control col-sm-9"
                                   placeholder="Adres" value="<?= $address ?>" <?= $loggedIn && !authorizeAdmin() ? 'disabled' : '' ?>>
                        </div>

                        <div class="form-group form-row">
                            <label for="city" class="col-sm-3 text-left">Woonplaats <span class="text-danger">*</span></label>
                            <input type="text" id="city" name="city" class="form-control col-sm-9"
                                   placeholder="Woonplaats" value="<?= $city ?>" <?= $loggedIn && !authorizeAdmin() ? 'disabled' : '' ?>>
                        </div>

                        <div class="form-group form-row">
                            <label for="phonenumber" class="col-sm-3 text-left">Telefoonnummer <span class="text-danger">*</span></label>
                            <input type="tel" id="phonenumber" name="phonenumber" class="form-control col-sm-9"
                                   placeholder="Telefoonnummer" value="<?= $phoneNumber ?>" <?= $loggedIn && !authorizeAdmin() ? 'disabled' : '' ?>>
                        </div>

                        <div class="form-group">
                            <a href="<?= getUrl('products-overview.php') ?>" class="btn btn-danger float-left my-4" type="button">
                                Terug naar overzicht
                            </a>
                            <button class="btn btn-success float-right my-4" type="submit" name="checkout">
                                2. Afrekenen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . "/../Src/footer.php";
?>