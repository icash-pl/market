<?php
require 'vendor/autoload.php';

use iCashpl\Market\Market;

$status = '';

/**
 * $market = new Market('APP_KEY_ICASH');
 */
$market = new Market('6E55RJzXzfilUULatK0YPpzPL8WEXnPh');

// Usługa z iCash
$market->setService(['id' => 'rGiDLltiS4OrAntBHae664P7BKbNWECL', 'text' => 'ICH.TEST', 'number' => 7055, 'cost' => 1, 'name' => 'PRODUKT 1']);

// Usługa z iCash
$market->setService(['id' => 'rGiDLltiS4OrAntBHae664P7BKbNWECL', 'text' => 'ICH.TEST', 'number' => 7555, 'cost' => 5, 'name' => 'PRODUKT 2']);

if (isset($_POST['code'])) {
    $market->getStatusCode([
        'service' => $_POST['service'],
        'code' => $_POST['code'],
        'user' => $_POST['user'],
    ]);
    
    /**
     * Jeśli kod jest prawidłowy
     */
    if ($market->getCurrentService() && $market->icash()->statusOk()) {
        $status .= '<div class="alert alert-success">Twój kod jest prawidłowy. Dziękujemy za zakupy.</div>';
        
        /**
         * Tutaj możesz również wykonywać inne operacje
         * Np. wysyłanie danych do API
         */
    } else {
        $status .= '<div class="alert alert-danger">Przesłany kod jest nieprawidłowy, przepisz go ponownie.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="style.css">
        <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <title>Bramka SMS</title>
    </head>
    <body>
        <div class="container">            
            <div class="row">
                <div class="col-sm-6">
                    <img src="http://icash.pl/img/logo.png">
                </div>
                <div class="col-sm-6 text-right">
                    <h3>Payment Gateway</h3>
                </div>
            </div>
            
            <hr>
            
            <?php echo $status; ?>
            
            <div class="text-center margin-bottom-25">
                <h4>Kup produkt</h4>
            </div>
            
            <p class="margin-bottom-25 text-center">
                Wybierz interesujący Cię produkt!
            </p>
            
            <form method="post">
                <div class="text-center margin-bottom-25">
                    <div class="btn-group services" data-toggle="buttons">
                        <?php
                        foreach ($market->getServices() as $service) {
                            if ($service->hasActive()) {
                                $active = 'active';
                                $checked = 'checked';
                            } else {
                                $active = $checked = '';
                            }

                            $text = '<b>KUP '.$service->name.'</b> - '.$service->fullCost().' zł brutto';

                            echo '<label class="btn btn-primary '.$active.'">';
                            echo '<input type="radio" name="service" value="'.$service->uid.'" autocomplete="off" '.$checked.'> ' . $text;
                            echo '</label>';
                        }
                        ?>
                    </div>
                </div>
            
                <div class="margin-bottom-25">
                    <h4 class="margin-bottom-25 text-center" style="line-height: 24px;">
                        W celu zakupu produktu proszę wysłać SMS na numer <b><span id="service_number"></span></b><br>
                        o treści <b><span id="service_text"></span></b><br>
                        Koszt wysłania wiadomości <span id="service_cost"></span> zł netto (<span id="service_full_cost"></span> zł z vat).</p>
                    </h4>
                    
                    <div class="form-group" style="width: 350px; margin: 0 auto 15px;">
                        <label>Wpisz tutaj kod sms:</label>
                        <input name="code" type="text" class="form-control" required />
                    </div>
                    
                    <div class="form-group" style="width: 350px; margin: 0 auto 15px;">
                        <label>Wpisz swój kod identyfikacyjny:</label>
                        <input name="user" type="text" value="<?php echo $market->getUser(); ?>" class="form-control" required />
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg">Zapłać</button>
                    </div>
                </div>
            </form>
            
            <p><small>Właścicielem serwisu jest <b>Nazwa firmy</b>. Wysyłając wiadomość akceptujesz regulamin serwisu <b>Regulamin serwisu Partnera</b>, 
            oraz regulamin <a href="https://icash.pl/document/regulamin-icashpl-platnosci-sms" target="_blank">systemu płatności 
            <strong>iCash.pl</strong></a>, który jest dostawcą usług mikropłatności SMS Premium. 
            W razie problemów z płatnością prosimy o kontakt poprzez <a href="https://icash.pl/reklamacje">formularz reklamacyjny</a>.</small></p>
        </div>
        <script>
            $(function() {
                var services = <?php echo $market->getServicesToJson(); ?>;
                
                function avtive(avtive) {
                    var service = services[avtive];
                    
                    $.each(service, function(i, v) {
                        $('#service_' + i).text(v);
                    });
                };
                
                $('.services label').click(function() {
                    avtive($(this).find('input').val());
                });
                
                avtive($('.services .active input').val());
            });
        </script>
    </body>
</html>