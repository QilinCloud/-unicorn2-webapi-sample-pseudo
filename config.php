<?php
declare(strict_types=1);

/**
 * Central configuration for the standalone pseudo-marketplace ApiWeb sample.
 *
 * This sample intentionally does not contain real marketplace credentials.
 * Replace the pseudo logic in samples/pseudo/sync.php with calls to your own
 * marketplace API and keep all secrets in environment variables or protected
 * hosting configuration.
 */
return array(
    'apiKey' => getenv('APIWEB_TEST_KEY') ?: 'local-dev-api-key-2026',
    'implementation' => 'pseudo',
    'logLevel' => getenv('APIWEB_LOG_LEVEL') ?: 'info',
    'timezone' => 'UTC',

    'debug' => array(
        'includeRequestContextInErrors' => getenv('APIWEB_INCLUDE_DEBUG_CONTEXT') === '1',
        'failureMode' => getenv('APIWEB_FAILURE_MODE') ?: ''
    ),

    'licence' => array(
        'enabled' => true,
        'acceptedLicenceKeys' => array()
    ),

    'capabilities' => array(
        'SupportedLanguages' => array('Deutsch', 'Englisch'),
        'SupportedWaehrungen' => array('EURO', 'USDOLLAR', 'POUND'),
        'SupportedZahlungsarten' => array('PayPal', 'Rechnung', 'Kreditkarte'),
        'ShippingProfiles' => array('Paket National', 'Paket International', 'Kurier Express'),
        'Features' => array(
            'stockPolicy',
            'EmulatedVakos',
            'TeilVersand',
            'FulfillmentByMarketplace',
            'RetoureAnnouncementDownload',
            'PortalCategories',
            'NoBranding',
            'NoDummyText',
            'Purge',
            'InvoiceFileUpload',
            'InvoiceDataUpload',
            'RefundFileUpload',
            'RefundDataUpload',
            'RetoureUpload',
            'InvoiceFileDownload',
            'InvoiceDataDownload',
            'RefundFileDownload',
            'RefundDataDownload'
        )
    ),

    'sampleData' => array(
        'orderPrefix' => 'pseudo-order',
        'skuPrefix' => 'PSEUDO-SAMPLE',
        'portalRootId' => 'pseudo-root'
    )
);
