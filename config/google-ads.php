<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Ads Configuration
    |--------------------------------------------------------------------------
    |
    | Configuración para Google Ads y seguimiento de conversiones
    |
    */

    'conversion_id' => env('GOOGLE_ADS_CONVERSION_ID', 'AW-CONVERSION_ID'),
    'conversion_label' => env('GOOGLE_ADS_CONVERSION_LABEL', 'CONVERSION_LABEL'),
    
    // Configuración para diferentes tipos de conversiones
    'conversions' => [
        'contact_form' => [
            'id' => env('GOOGLE_ADS_CONTACT_CONVERSION_ID', 'AW-CONVERSION_ID'),
            'label' => env('GOOGLE_ADS_CONTACT_CONVERSION_LABEL', 'CONTACT_LABEL'),
        ],
        'phone_call' => [
            'id' => env('GOOGLE_ADS_PHONE_CONVERSION_ID', 'AW-CONVERSION_ID'),
            'label' => env('GOOGLE_ADS_PHONE_CONVERSION_LABEL', 'PHONE_LABEL'),
        ],
        'email_click' => [
            'id' => env('GOOGLE_ADS_EMAIL_CONVERSION_ID', 'AW-CONVERSION_ID'),
            'label' => env('GOOGLE_ADS_EMAIL_CONVERSION_LABEL', 'EMAIL_LABEL'),
        ],
    ],
]; 