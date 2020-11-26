<?php
require_once __DIR__ . "/../Src/header.php";

if(isset($_POST["contact"])){
    $name = get_form_data_post("name");
    $email = get_form_data_post("email");
    $subject = get_form_data_post("subject");
    $message = get_form_data_post("message");

    if(empty($email) || empty($name) || empty($subject) || empty($message)){
        add_user_error("Niet all verplichte velden met een * zijn ingevuld.");
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        add_user_error("Ongeldige email opgegeven.");
    }
    elseif(strlen($message) > 100){
        add_user_error("Uw opgegeven naam is langer dan toegestaan. (Max: 100 tekens)");
    }
    elseif(strlen($email) > 100){
        add_user_error("Uw opgegeven email adres is langer dan toegestaan. (Max: 100 tekens)");
    }
    elseif(strlen($subject) > 100){
        add_user_error("Uw opgegeven onderwerp is langer dan toegestaan (Max: 100 tekens).");
    }
    elseif(strlen($message) > 2000){
        add_user_error("Uw bericht is langer dan toegestaan.");
    }
    else {
        createContactRequest($name, $email, $subject, $message);
        add_user_message("Uw bericht is verstuurd.");
        redirect(get_current_url());
    }
}

include __DIR__ . '/../Src/Html/alert.php';
?>

    <div class="container-fluid">
        <div class="w-50 ml-auto mr-auto mt-5 mb-5">
            <h1 class="mb-5">Contact opnemen.</h1>

            <div class="row">
                <div class="col-sm-12">
                    <form class="text-center w-100" action="" method="post">
                        <div class="form-group form-row">
                            <label for="name" class="col-sm-3 text-left">Naam <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" class="form-control col-sm-9"
                                   placeholder="Naam" value="<?=get_form_data_post("name")?>">
                        </div>

                        <div class="form-group form-row">
                            <label for="email" class="col-sm-3 text-left">Email <span class="text-danger">*</span></label>
                            <input type="email" id="email" name="email" class="form-control col-sm-9"
                                   placeholder="Email" value="<?=get_form_data_post("email")?>">
                        </div>

                        <div class="form-group form-row">
                            <label for="text" class="col-sm-3 text-left">Onderwerp <span class="text-danger">*</span></label>
                            <input type="text" id="subject" name="subject" class="form-control col-sm-9"
                                   placeholder="Onderwerp" value="<?=get_form_data_post("subject")?>">
                        </div>

                        <div class="form-group form-row">
                            <label for="phonenumber" class="col-sm-3 text-left">Uw bericht <span class="text-danger">*</span></label>
                            <textarea id="message" name="message" class="form-control col-sm-9" rows="10"
                                      placeholder="Uw bericht (maximaal 2000 karakters)"><?=get_form_data_post("message")?></textarea>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-success float-right my-4" type="submit" name="contact">
                                Indienen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
require_once __DIR__ . "/../Src/footer.php";