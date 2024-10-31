<?php

use Carbon\Carbon;
use App\MainConfig;

$title = sprintf(
    /* Translators: %s stands for the amount of days where we show the statiscs for */
    esc_html__('Dashboard', 'tussendoor-rdw'),
    apply_filters('tussendoor_bol_statistic_from_days', MainConfig::get('plugin.statistic_days')),
);

$dismissTime = get_option('open-rdw-notice-dismissed', false);
$currentTime = Carbon::now()->toDateString();
$licenseStatus = get_option('tsd_rdw_license_status', false);

if (($dismissTime === false || $dismissTime === '' || $dismissTime == '1' || Carbon::parse($currentTime)->greaterThan($dismissTime)) && ($licenseStatus !== 'valid')) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const url = window.location.href;
            const parsedUrl = new URL(url);
            const queryParams = new URLSearchParams(parsedUrl.search);

            const pageParam = queryParams.get('page');
            const tabParam = queryParams.get('tab');

            if (
                (tabParam === 'home-tab' && pageParam === 'tsd-rdw') ||
                (pageParam === 'tsd-rdw')
            ) {
                var modalElement = document.getElementById('staticBackdrop');
                if (modalElement) {
                    var modal = new bootstrap.Modal(modalElement);
                    modal.show();
                }
            }
        });
    </script>
<?php } ?>

<div id="tab-dashboard">
    <div class="tab-title mb-3">
        <h3><?php echo $title; ?></h3>
    </div>

    <div class="history mt-5">
        <div>
            <p>
                Bedankt voor het gebruiken van de Tussendoor - Open RDW WordPress plugin.
                <br>
                <br>
                Deze plugin kun je weergeven door gebruik te maken van Open RDW widgets in je zijbalk, naast widgets is er ook een mogelijkheid om de WYSIWYG editor te gebruiken om een shortcode aan je pagina of bericht toe te voegen (of andere CPT). Het is ook mogelijk om de plug-in te integreren met Contact Form 7.
            </p>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog tussendoor">
            <div class="modal-content" id="free-variant-notice">
                <div class="modal-header tussendoor">
                    <button type="button" class="btn-close open-rdw-notice-dismiss-action" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body tussendoor">
                    <i class="fa-regular fa-calendar" style="font-size:48px; color:orange;"></i>
                    <h1 class="modal-title fs-4" id="staticBackdropLabel">Vanaf 1 november verandert het betaalmodel van deze plugin.</h1>
                </div>
                <div class="modal-footer tussendoor">
                    <p>Waarom? <a href="https://tussendoor.nl/ons-betaalmodel-verandert?utm_source=wordpress&utm_medium=popup&utm_campaign=meer+informatie" target="_blank">Hier vind je meer informatie</a></p>
                </div>
            </div>
        </div>
        <div class="modal-dialog-extra">
            <div class="modal-content" id="free-variant-notice">
                <div class="modal-header tussendoor">
                </div>
                <div class="modal-body tussendoor">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Je hebt een betaalde licentie nodig om deze plugin vanaf 1 november nog te kunnen gebruiken.</h1>
                    <br>
                    <span>
                        Deze licentie kost het eerste jaar 39,95. Daarna betaal je op basis van verbruik.
                        <br>
                        <br>
                        Heb je vragen? Mail ons dan gerust op info@tussendoor.nl
                        of bel ons werkdagen tussen 9 - 17 uur op 088 808 8800.</span>
                    <br>
                    <br>
                    <div class="d-grid gap-2 col-9">
                        <a href="https://tussendoor.nl/plugins/openrdw-kenteken-wordpress-plugin?utm_source=wordpress&utm_medium=popup&utm_campaign=licentie+aanschaffen" target="_blank" class="btn btn-secondary rounded-pill fw-light custom-button-width me-3" type="button"> Licentie aanschaffen <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                    <div class="accordion tussendoor" id="accordionExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Wat kost deze plugin na het eerste jaar?
                                </button>
                            </h2>
                            <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    De kosten voor de plugin zijn afhankelijk van het aantal verzoeken. Hierbij is het ophalen van gegevens voor één kenteken richting de RDW, één verzoek.
                                    <br>
                                    <br>
                                    Binnen je dashboard kun je precies zien hoeveel verzoeken je gemiddeld doet per maand.
                                    <a href="https://tussendoor.nl/plugins/openrdw-kenteken-wordpress-plugin?utm_source=wordpress&utm_medium=popup&utm_campaign=bekijk+pakketten" target="_blank">Bekijk hier de pakketten</a>.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Waarom moet ik straks voor deze plugin betalen?
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    We vragen een kleine vergoeding zodat jij gebruik kunt blijven maken van een kwalitatief goede plugin die veilig is en wordt doorontwikkeld wanneer dit nodig is.
                                    <br>
                                    <br>
                                    Daarnaast zijn wij groot voorstander van eerlijk factureren en betaalt iemand die veel verzoeken doet, meer dan iemand die weinig verzoeken doet.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 col-9">
                        <a href="https://tussendoor.nl/contact/?utm_source=wordpress&utm_medium=popup&utm_campaign=stuur+een+mail" target="_blank" class="btn btn-outline-secondary rounded-pill fw-light custom-button-width me-3" type="button">Stuur een mail <i class="fa-solid fa-arrow-right"></i></a>
                        <a href="https://tussendoor.nl/contact/?modal=call-me-back" target="_blank" class="btn btn-outline-secondary rounded-pill fw-light custom-button-width me-3" type="button">Bel mij terug <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
                <div class="modal-footer tussendoor"></div>
            </div>
        </div>
    </div>
</div>