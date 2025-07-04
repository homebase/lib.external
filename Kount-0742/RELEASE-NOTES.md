Version 7.4.3
=====================
5/12/2022
1. Only load lines from the RIS response that contain '='. Otherwise, those lines will not be parseable as key-value pairs.
2. Reformatted the SDK

Version 7.3.9
=====================
26/03/2021
1. Removed warning for KOUNT_CUSTOM_SETTINGS_FILE constant

Version 7.3.7
=====================
19/03/2021
1. PHP 8 Support

Version 7.1.0
=====================
06/28/2018
1. KOUNT-12435: ipv6 addresses now allowed by SDK validation

Version 7.0.0
=====================
09/12/2017
1. Introduced configuration key - this breaks backward compatibility with older releases.
2. Removed the SALT phrase.
3. Introduced base85 encoding and decoding.
4. Added sha256 validation for the config key.

Version 6.5.4
=====================
08/14/2017
1. Removed request parameters CCYY(Expiration year) and CCMM(Expiration month) from the sdk.

Version 6.5.3
=====================
07/17/2017
1. Enabled acceptance of custom settings from user defined file.

Version 6.5.2
=====================
06/27/2017
1. Added getResponseAsDict() method for retrieving the response fields as array.
2. Removed the stopwatch package and introduced php's native microtime() to track milliseconds.
3. Added autoload configuration in composer.json, so that when the user opts for a composer installation he would have to only include the autoload in vendor folder.

Version 6.5.1
=====================
06/23/2017
1. Improved communication logging
2. Added more information to connection headers
3. Integrated more payment types, see https://api.test.kount.net/rpc/support/payments.html

Version 6.5.0
=====================
05/29/2017
1. SALT phrase configurable as a settings variable

Version 6.4.2
=====================
04/07/2017
1. Downgrading phpunit version in composer.json from 5.7 to 4.8. PHP 5.5.9 isn't supported with newer versions of phpunit.

Version 6.4.1
=====================
03/31/2017
1. Removing usage of visibility modifiers in test classes - not supported with older versions of php.

Version 6.4.0
=====================
03/31/2017
1. New requirement for PHP - 5.5 or newer.
2. Secure communication between client and server now using TLS v1.2 and TLS v1.1
3. Added Composer for package management, unit and integration tests, generating documentation,
   also a release.sh script for release a new version and creating .zip(will be automated in the near future).
4. General source code improvements and modernization
5. General phpdoc enhancements

Version 6.3.1 changes
=====================
05/06/2015

1. Security Patching


Version 6.3.0 changes
=====================
01/20/2015

1. Added support for additional payment token information (expiration month [MM]
    and expiration year [YYYY]).  The new field names are: CCMM and CCYY.
2. Added support for API keys.  You can now use an API key instead of a
    certificate.  API keys are typically much easier to integrate and maintain.

Version 6.0.0 changes
=====================
08/01/2014

1. Added support for new 'Kount Central' RIS query modes 'J' and 'W'.

Version 5.5.5 changes
=====================
09/12/2013

1. Updated sdk_guide.pdf regarding .NET help documentation.

08/27/2013

1. Added new getter functions for the following RIS response fields:
    PIP_IPAD, PIP_LAT, PIP_LON, PIP_COUNTRY, PIP_REGION, PIP_CITY, PIP_ORG,
    IP_IPAD, IP_LAT, IP_LON, IP_COUNTRY, IP_REGION, IP_CITY, IP_ORG, DDFS,
    UAS, DSR, OS, BROWSER

Version 5.5.0 changes
=====================
06/13/2013

1. Expanded Payment types accepted. Legacy payment setter functions will work,
    but the new generic payment function is recommended. See doc for usage.
2. New getter function for RIS response field, MASTERCARD added.

Version 5.0.0 changes
=====================
04/19/2012

1. SDK language identifier has been added to the inbound RIS request.
2. All payment tokens are now hashed by default before submitting to RIS. Hence,
    no plain text credit card numbers, Paypal payment IDs, Check numbers, Google
    Checkout IDs, Bill Me Later IDs, Green Dot MoneyPak IDs or gift card numbers
    are transmitted in plain text to RIS by default. The value of the new RIS
    input field LAST4 is automatically set by the SDK for all payment types
    prior to hashing the payment token.
3. Data validation for the RIS request elements "ORDR" and "AUTH" have been
    updated to allow up to 32 characters and null values respectively, matching
    the RIS specification guide.
4. RIS connection timeout value can now be set in the SDK configuration. The
    recommended value is 30 seconds.
5. The method Kount_Ris_Response::getReason() has been deprecated and replaced
    with Kount_Ris_Response::getReasonCode(). This new method allows the
    merchant defined decision reason code to be fetched from the response.
6. Ability to replace settings.ini configuration with custom source.
7. Added an `spl_autoload_register()` compatible class loader to replace
    the prior relative path based `require` implementation. NOTE: This change
    requires a small alteration in any existing integration. You will need to
    include/require the new autoloader.php script before you instantiate any
    Kount SDK provided objects in your scripts.

Version 4.6.0 changes
=====================
09/20/2011

1. Expanded payment type support. Supported payment types include: Bill Me
    Later, check, credit card, gift card, Green Dot MoneyPak, Google, no
    payment, and PayPal.
2. Added KHASH payment encoding support. Contact your Kount representative for
    more information on this feature.
3. Added method Kount_Ris_Response::getLexisNexisInstantIdAttributes().
    This is used to fetch LexisNexis Instant ID data that may be associated
    with a RIS transaction. Please contact your Kount representative to have
    Instant ID enabled for your merchant account if you need to access this
    data.
4. Merchant SSL authentication certificate can now be configured in settings.ini
    file located in the zip SDK package hierarchy at the path:
    Sdk-Ris-Php-0631-20150506T1139.zip
    `-- Sdk-Ris-Php-0631/
        `-- src/
            `-- settings.ini

Version 4.5.0 changes
=====================
06/28/2011

1. Added methods Kount_Ris_Response::getCountersTriggered() and
    Kount_Ris_Response::getNumberCountersTriggered() to get the RIS rules
    counter data associated with a particular transaction.

Version 4.3.5 changes
=====================
12/09/2010

1. Added method Kount_Ris_Response::getLexisNexisCbdAttributes(). This is used
    to fetch LexisNexis Chargeback Defender data that may be associated with a
    RIS transaction. Please contact your Kount representative to have Chargeback
    Defender enabled for your merchant account if you need to access this data.

Version 4.3.0 changes
=====================
08/18/2010

1. RIS response version 4.3.0 now includes rule IDs and descriptions for all
    rules triggered by the RIS input (request) data based on the RIS rules the
    merchant has defined. The following functions have been added to accommodate
    this change:
    a. Kount_Ris_Response::getRulesTriggered() - Get a Map of the rules 
        triggered by this response.
    b. Kount_Ris_Response::getNumberRulesTriggered() - Get the number of rules
        triggered with the response.

Version 4.2.0 changes
=====================
06/28/2010

This is a quick summary of changes made to the SDK compared to version 4.1.0:

1. Logging has been added. The default logger is a NOP logger that silently
    discards all logging. SIMPLE logger can be turned on which appends logging
    to a selected file. See README for more details.
2. Input data validation has been changed to return all errors encountered in
    the input instead of one error at a time.
3. RIS version 4.2.0 will return warnings associated with bad optional input
    data. RIS 4.2.0 will also return all errors and warnings encountered instead
    of just the first error. Hence, the following methods have been
    added to the class Kount_Ris_Response:
    a. Kount_Ris_Response::getErrors() - Get the errors returned by the
        response.
    b. Kount_Ris_Response::getWarnings() - Get the warnings returned by the
        response.
    c. Kount_Ris_Response::getWarningCount() - Get the number of warnings
        contained in the response.
    d. Kount_Ris_Response::getErrorCount() - Get the number of errors contained
        in the response.
    e. Kount_Ris_Response::hasErrors() - Returns true if the response has
        errors.
    f. Kount_Ris_Response::hasWarnings() - Returns true if the response has
        warnings.
