<?php

namespace App;

/**
 * All category types and response IDs with labels encapsulated in a class
 *
 * @since    2.0.0
 */
class Fields
{
    public static $categories;
    public static $fields;

    public function __construct()
    {
        self::$categories = [
            'miscellaneous' => __('Miscellaneous', 'tussendoor-rdw'),
            'history'       => __('History', 'tussendoor-rdw'),
            'environment'   => __('Environment', 'tussendoor-rdw'),
            'vehicle'       => __('Vehicle', 'tussendoor-rdw'),
            'capacity'      => __('Weight and capacity', 'tussendoor-rdw'),
            'maxtow'        => __('Maximum towable mass', 'tussendoor-rdw'),
            'engine'        => __('Engine', 'tussendoor-rdw'),
            'design'        => __('Design', 'tussendoor-rdw'),
            'moped'         => __('Moped', 'tussendoor-rdw'),
            'finance'        => __('Financials', 'tussendoor-rdw'),
            'axels'         => __('Axels', 'tussendoor-rdw'),
            'fuel'          => __('Fuel information', 'tussendoor-rdw'),
            'body'          => __('Body work', 'tussendoor-rdw')
        ];

        self::$fields = [
            'aangedreven_as' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Driven axle', 'tussendoor-rdw')
            ],
            'aantal_assen' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Number of axles', 'tussendoor-rdw')
            ],
            'aantal_cilinders' => [
                'category' => self::$categories['engine'],
                'label' => __('Number of cylinders', 'tussendoor-rdw')
            ],
            'aantal_wielen' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Number of wheels', 'tussendoor-rdw')
            ],
            'aantal_zitplaatsen' => [
                'category' => self::$categories['design'],
                'label' => __('Number of seats', 'tussendoor-rdw')
            ],
            'aanhangwagen_autonoom_geremd' => [
                'category' => self::$categories['maxtow'],
                'label' => __('Trailer autonomous braked', 'tussendoor-rdw')
            ],
            'aanhangwagen_middenas_geremd' => [
                'category' => self::$categories['maxtow'],
                'label' => __('Trailer center axis braked', 'tussendoor-rdw')
            ],
            'afstand_voorzijde_voertuig_tot_hart_koppeling' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Distance from vehicle front to coupling point', 'tussendoor-rdw')
            ],
            'as_nummer' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Axle number', 'tussendoor-rdw')
            ],
            'brandstof_omschrijving' => [
                'category' => self::$categories['engine'],
                'label' => __('Fuel description', 'tussendoor-rdw')
            ],
            'brandstof_volgnummer' => [
                'category' => self::$categories['engine'],
                'label' => __('Fuel sequence number', 'tussendoor-rdw')
            ],
            'brandstof_verbruik_buitenweg' => [
                'category' => self::$categories['engine'],
                'label' => __('Fuel consumption outside city', 'tussendoor-rdw')
            ],
            'brandstof_verbruik_gecombineerd' => [
                'category' => self::$categories['engine'],
                'label' => __('Fuel consumption combined', 'tussendoor-rdw')
            ],
            'brandstof_verbruik_stad' => [
                'category' => self::$categories['engine'],
                'label' => __('Fuel consumption city', 'tussendoor-rdw')
            ],
            'brandstofverbruik_buiten' => [
                'category' => self::$categories['engine'],
                'label' => __('Fuel consumption outside', 'tussendoor-rdw')
            ],
            'brandstofverbruik_gecombineerd' => [
                'category' => self::$categories['engine'],
                'label' => __('Combined fuel consumption', 'tussendoor-rdw')
            ],
            'brandstofverbruik_stad' => [
                'category' => self::$categories['engine'],
                'label' => __('Fuel consumption in the city', 'tussendoor-rdw')
            ],
            'bruto_bpm' => [
                'category' => self::$categories['finance'],
                'label' => __('Gross BPM', 'tussendoor-rdw')
            ],
            'catalogusprijs' => [
                'category' => self::$categories['finance'],
                'label' => __('Catalog price', 'tussendoor-rdw')
            ],
            'carrosserie_volgnummer' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Car body sequence number', 'tussendoor-rdw')
            ],
            'carrosserie_voertuig_nummer_code_volgnummer' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Car body vehicle code sequence number', 'tussendoor-rdw')
            ],
            'carrosserie_voertuig_nummer_europese_omschrijving' => [
                'category' => self::$categories['vehicle'],
                'label' => __('European car body vehicle number', 'tussendoor-rdw')
            ],
            'carrosserietype' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Car body type', 'tussendoor-rdw')
            ],
            'carrosseriecode' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Car body code', 'tussendoor-rdw')
            ],
            'cilinderinhoud' => [
                'category' => self::$categories['engine'],
                'label' => __('Cylinder capacity', 'tussendoor-rdw')
            ],
            'co2_uitstoot_gecombineerd' => [
                'category' => self::$categories['environment'],
                'label' => __('CO2 emission combined', 'tussendoor-rdw')
            ],
            'co2_uitstoot_meer_etalages' => [
                'category' => self::$categories['environment'],
                'label' => __('CO2 emissions for more displays', 'tussendoor-rdw')
            ],
            'code_toelichting_tellerstandoordeel' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Explanation code odometer reading', 'tussendoor-rdw')
            ],
            'datum_eerste_afgifte_nederland' => [
                'category' => self::$categories['history'],
                'label' => __('Date of first release Netherlands', 'tussendoor-rdw')
            ],
            'datum_eerste_tenaamstelling_in_nederland' => [
                'category' => self::$categories['history'],
                'label' => __('Date of first registration in the Netherlands', 'tussendoor-rdw')
            ],
            'datum_eerste_tenaamstelling_in_nederland_dt' => [
                'category' => self::$categories['history'],
                'label' => __('Date of first registration in NL (datetime)', 'tussendoor-rdw')
            ],
            'datum_eerste_toelating' => [
                'category' => self::$categories['history'],
                'label' => __('Date of first registration', 'tussendoor-rdw')
            ],
            'datum_eerste_toelating_dt' => [
                'category' => self::$categories['history'],
                'label' => __('Date of first registration (datetime)', 'tussendoor-rdw')
            ],
            'datum_tenaamstelling' => [
                'category' => self::$categories['history'],
                'label' => __('Date ascription', 'tussendoor-rdw')
            ],
            'datum_tenaamstelling_dt' => [
                'category' => self::$categories['history'],
                'label' => __('Date ascription (datetime)', 'tussendoor-rdw')
            ],
            'eerste_kleur' => [
                'category' => self::$categories['design'],
                'label' => __('First colour', 'tussendoor-rdw')
            ],
            'emissiecode' => [
                'category' => self::$categories['environment'],
                'label' => __('Emission code', 'tussendoor-rdw')
            ],
            'emissiecode_omschrijving' => [
                'category' => self::$categories['environment'],
                'label' => __('Emission code description', 'tussendoor-rdw')
            ],
            'europese_voertuigcategorie' => [
                'category' => self::$categories['vehicle'],
                'label' => __('European vehicle category', 'tussendoor-rdw')
            ],
            'export_indicator' => [
                'category' => self::$categories['history'],
                'label' => __('Export indicator', 'tussendoor-rdw')
            ],
            'geluidsniveau_stationair' => [
                'category' => self::$categories['environment'],
                'label' => __('Stationary noise level', 'tussendoor-rdw')
            ],
            'handelsbenaming' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Commercial name', 'tussendoor-rdw')
            ],
            'hefas' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Lift axle', 'tussendoor-rdw')
            ],
            'inrichting' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Layout', 'tussendoor-rdw')
            ],
            'kenteken' => [
                'category' => self::$categories['vehicle'],
                'label' => __('License plate', 'tussendoor-rdw')
            ],
            'laadvermogen' => [
                'category' => self::$categories['capacity'],
                'label' => __('Capacity', 'tussendoor-rdw')
            ],
            'lengte' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Length', 'tussendoor-rdw')
            ],
            'massa_ledig_voertuig' => [
                'category' => self::$categories['capacity'],
                'label' => __('Empty vehicle mass', 'tussendoor-rdw')
            ],
            'massa_rijklaar' => [
                'category' => self::$categories['capacity'],
                'label' => __('Mass roadworthy', 'tussendoor-rdw')
            ],
            'maximale_constructiesnelheid_brom_snorfiets' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Maximum construction speed moped', 'tussendoor-rdw')
            ],
            'maximum_last_onder_de_vooras_sen_tezamen_koppeling' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Max load on front axle combined', 'tussendoor-rdw')
            ],
            'maximum_massa_samenstelling' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Maximum combined mass', 'tussendoor-rdw')
            ],
            'maximum_massa_trekken_ongeremd' => [
                'category' => self::$categories['maxtow'],
                'label' => __('Maximum towable mass non-braked', 'tussendoor-rdw')
            ],
            'maximum_trekken_massa_geremd' => [
                'category' => self::$categories['maxtow'],
                'label' => __('Maximum towable mass braked', 'tussendoor-rdw')
            ],
            'merk' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Brand', 'tussendoor-rdw')
            ],
            'milieuklasse' => [
                'category' => self::$categories['environment'],
                'label' => __('Environmental class', 'tussendoor-rdw')
            ],
            'milieuklasse_eg_goedkeuring_licht' => [
                'category' => self::$categories['environment'],
                'label' => __('EG environmental class light', 'tussendoor-rdw')
            ],
            'nettomaximumvermogen' => [
                'category' => self::$categories['engine'],
                'label' => __('Net maximum power', 'tussendoor-rdw')
            ],
            'openstaande_terugroepactie_indicator' => [
                'category' => self::$categories['history'],
                'label' => __('Open recall action indicator', 'tussendoor-rdw')
            ],
            'oplegger_geremd' => [
                'category' => self::$categories['maxtow'],
                'label' => __('Trailer braked', 'tussendoor-rdw')
            ],
            'plaats_chassisnummer' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Chassis number location', 'tussendoor-rdw')
            ],
            'plaatscode_as' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Axle position code', 'tussendoor-rdw')
            ],
            'roetuitstoot' => [
                'category' => self::$categories['environment'],
                'label' => __('Particulate emission', 'tussendoor-rdw')
            ],
            'taxi_indicator' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Taxi indicator', 'tussendoor-rdw')
            ],
            'technische_max_massa_voertuig' => [
                'category' => self::$categories['capacity'],
                'label' => __('Technical max vehicle mass', 'tussendoor-rdw')
            ],
            'technisch_toegestane_maximum_aslast' => [
                'category' => self::$categories['capacity'],
                'label' => __('Technically permissible max axle load', 'tussendoor-rdw')
            ],
            'tellerstandoordeel' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Odometer reading judgment', 'tussendoor-rdw')
            ],
            'tenaamstellen_mogelijk' => [
                'category' => self::$categories['history'],
                'label' => __('Registration possible', 'tussendoor-rdw')
            ],
            'toegestane_maximum_massa_voertuig' => [
                'category' => self::$categories['capacity'],
                'label' => __('Maximum permissible mass of vehicle', 'tussendoor-rdw')
            ],
            'toerental_geluidsniveau' => [
                'category' => self::$categories['environment'],
                'label' => __('RPM noise level', 'tussendoor-rdw')
            ],
            'tweede_kleur' => [
                'category' => self::$categories['design'],
                'label' => __('Secondary colour', 'tussendoor-rdw')
            ],
            'type' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Type', 'tussendoor-rdw')
            ],
            'type_carrosserie_europese_omschrijving' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Type of car body European description', 'tussendoor-rdw')
            ],
            'uitlaatemissieniveau' => [
                'category' => self::$categories['environment'],
                'label' => __('Exhaust emission level', 'tussendoor-rdw')
            ],
            'uitstoot_deeltjes_licht' => [
                'category' => self::$categories['environment'],
                'label' => __('Light particle emissions', 'tussendoor-rdw')
            ],
            'vermogen_bij_massarijklaar' => [
                'category' => self::$categories['engine'],
                'label' => __('Power at curb weight', 'tussendoor-rdw')
            ],
            'vermogen_massarijklaar' => [
                'category' => self::$categories['engine'],
                'label' => __('Power curb weight', 'tussendoor-rdw')
            ],
            'vervaldatum_apk' => [
                'category' => self::$categories['history'],
                'label' => __('MOT expiry', 'tussendoor-rdw')
            ],
            'vervaldatum_apk_dt' => [
                'category' => self::$categories['history'],
                'label' => __('MOT expiry (datetime)', 'tussendoor-rdw')
            ],
            'vervaldatum_tachograaf' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Tachograph expiration date', 'tussendoor-rdw')
            ],
            'vervaldatum_tachograaf_dt' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Tachograph expiration date (datetime)', 'tussendoor-rdw')
            ],
            'voertuigsoort' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Vehicle type', 'tussendoor-rdw')
            ],
            'wam_verzekerd' => [
                'category' => self::$categories['finance'],
                'label' => __('WAM insured', 'tussendoor-rdw')
            ],
            'wacht_op_keuren' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Waiting for approval', 'tussendoor-rdw')
            ],
            'wegenbelasting_milieuklasse_lichte_benzine_voertuigen' => [
                'category' => self::$categories['finance'],
                'label' => __('Road tax environmental class light petrol vehicles', 'tussendoor-rdw')
            ],
            'wegenbelasting_milieuklasse_lichte_diesel_voertuigen' => [
                'category' => self::$categories['finance'],
                'label' => __('Road tax environmental class light diesel vehicles', 'tussendoor-rdw')
            ],
            'wegenbelasting_milieuklasse_lichte_lpg_voertuigen' => [
                'category' => self::$categories['finance'],
                'label' => __('Road tax environmental class light LPG vehicles', 'tussendoor-rdw')
            ],
            'wegenbelasting_milieuklasse_zware_benzine_voertuigen' => [
                'category' => self::$categories['finance'],
                'label' => __('Road tax environmental class heavy petrol vehicles', 'tussendoor-rdw')
            ],
            'wegenbelasting_milieuklasse_zware_diesel_voertuigen' => [
                'category' => self::$categories['finance'],
                'label' => __('Road tax environmental class heavy diesel vehicles', 'tussendoor-rdw')
            ],
            'wegenbelasting_milieuklasse_zware_lpg_voertuigen' => [
                'category' => self::$categories['finance'],
                'label' => __('Road tax environmental class heavy LPG vehicles', 'tussendoor-rdw')
            ],
            'wegenbelasting_zuinige_voertuigen' => [
                'category' => self::$categories['finance'],
                'label' => __('Road tax fuel-efficient vehicles', 'tussendoor-rdw')
            ],
            'weggedrag_code' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Road behavior code', 'tussendoor-rdw')
            ],
            'wettelijk_toegestane_maximum_aslast' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Legally permissible maximum axle load', 'tussendoor-rdw')
            ],
            'wielbasis' => [
                'category' => self::$categories['vehicle'],
                'label' => __('Wheelbase', 'tussendoor-rdw')
            ],
            'zuinigheidslabel' => [
                'category' => self::$categories['environment'],
                'label' => __('Efficiency label', 'tussendoor-rdw')
            ]
        ];
    }

    public static function init()
    {
        if (self::$fields === null) {
            new self();
        }
    }

    public static function getCategories(): array
    {
        self::init();
        return self::$categories;
    }

    public static function getFields(): array
    {
        self::init();
        self::$fields = apply_filters('open_rdw_vehicle_plate_information_fields', self::$fields);
        return self::$fields;
    }
}
