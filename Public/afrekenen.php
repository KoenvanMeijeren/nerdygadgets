<?php
require_once __DIR__ . "/../Src/header.php";
#check hier of the klant is ingelogd, zo wel pak de NAW gegevens en skip de form direct naar afbetalen anders NAW gegevens via de form krijgen
?>
<div class="container-fluid">
    <div class="products-overview w-50 ml-auto mr-auto mt-5 mb-5">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="mb-5 float-left">1. Bezorggegevens</h1>

                <div class="form-progress float-right">
                    <!-- Grey with black text -->
                    <nav class="navbar navbar-expand-sm bg-primary navbar-dark">
                        <ul class="navbar-nav">
                            <li class="nav-item active border-right border-white">
                                <a class="nav-link" href="#">Bezorggegevens</a>
                            </li>
                            <li class="nav-item border-right border-white">
                                <a class="nav-link" href="#">Afrekenen</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Afronden</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <form class="text-center w-100" action="<?= get_url('payment.php') ?>" method="post">
                    <div class="form-group form-row">
                        <label for="Naam" class="col-sm-2 text-left">Naam</label>
                        <input type="text" id="Naam" class="form-control col-sm-10" placeholder="Naam">
                    </div>

                    <div class="form-group form-row">
                        <label for="Postcode" class="col-sm-2 text-left">Postcode</label>
                        <input type="text" maxlength="6" id="Postcode" class="form-control col-sm-4" placeholder="Postcode">

                        <label for="Huisnummer" class="col-sm-3 text-left pl-5">Huisnummer</label>
                        <input type="number" id="Huisnummer" class="form-control col-sm-3" placeholder="Huisnummer">
                    </div>

                    <div class="form-group form-row">
                        <label for="Straatnaam" class="col-sm-2 text-left">Straatnaam</label>
                        <input type="text" id="Straatnaam" class="form-control col-sm-10" placeholder="Straatnaam">
                    </div>

                    <div class="form-group form-row">
                        <label for="Woonplaats" class="col-sm-2 text-left">Woonplaats</label>
                        <input type="text" id="Woonplaats" class="form-control col-sm-10" placeholder="Woonplaats">
                    </div>

                    <div class="form-group form-row">
                        <label for="Email" class="col-sm-2 text-left">Email</label>
                        <input type="email" id="Email" class="form-control col-sm-10" placeholder="Email">
                    </div>

                    <div class="form-group form-row">
                        <label for="Telefoonnummer" class="col-sm-2 text-left">Telefoonnummer</label>
                        <input type="tel" id="Telefoonnummer" class="form-control col-sm-10" placeholder="Telefoonnummer">
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger float-left my-4" type="button" name="back"
                                onclick="window.location.href='<?= get_url('products-overview.php') ?>'">
                            Terug naar overzicht
                        </button>
                        <button class="btn btn-success float-right my-4" type="submit">
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