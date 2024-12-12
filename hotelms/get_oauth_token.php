<?php
/**
 * PHPMailer - PHP email creation and transport class.
 * PHP Version 5.5+
 * @package PHPMailer
 * @see https://github.com/PHPMailer/PHPMailer/ The PHPMailer GitHub project
 */

/**
 * Get an OAuth2 token from an OAuth2 provider.
 * Install this script on your server so that it's accessible
 * as [https/http]://<yourdomain>/<folder>/get_oauth_token.php
 */

namespace PHPMailer\PHPMailer;

// Required OAuth2 providers
use League\OAuth2\Client\Provider\Google;
use Hayageek\OAuth2\Client\Provider\Yahoo;
use Stevenmaguire\OAuth2\Client\Provider\Microsoft;

session_start();

require 'vendor/autoload.php';

if (!isset($_GET['code']) && !isset($_GET['provider'])) {
    ?>
    <html>
    <body>
    Select Provider:<br/>
    <a href='?provider=Google'>Google</a><br/>
    <a href='?provider=Yahoo'>Yahoo</a><br/>
    <a href='?provider=Microsoft'>Microsoft/Outlook/Hotmail/Live/Office365</a><br/>
    </body>
    </html>
    <?php
    exit;
}

$providerName = '';

if (isset($_GET['provider'])) {
    $providerName = $_GET['provider'];
    $_SESSION['provider'] = $providerName;
} elseif (isset($_SESSION['provider'])) {
    $providerName = $_SESSION['provider'];
}

if (!in_array($providerName, ['Google', 'Microsoft', 'Yahoo'])) {
    exit('Only Google, Microsoft, and Yahoo OAuth2 providers are supported in this script.');
}

// Client ID and Secret obtained from the provider's developer console
$clientId = 'RANDOMCHARS-----duv1n2.apps.googleusercontent.com';  // Replace with your actual Client ID
$clientSecret = 'RANDOMCHARS-----lGyjPcRtvP';  // Replace with your actual Client Secret

// Automatically set the redirect URI
$redirectUri = (isset($_SERVER['HTTPS']) ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];

$params = [
    'clientId' => $clientId,
    'clientSecret' => $clientSecret,
    'redirectUri' => $redirectUri,
    'accessType' => 'offline'  // For getting refresh tokens
];

$options = [];
$provider = null;

switch ($providerName) {
    case 'Google':
        $provider = new Google($params);
        $options = [
            'scope' => ['https://mail.google.com/']
        ];
        break;
    case 'Yahoo':
        $provider = new Yahoo($params);
        break;
    case 'Microsoft':
        $provider = new Microsoft($params);
        $options = [
            'scope' => [
                'wl.imap',
                'wl.offline_access'
            ]
        ];
        break;
}

if ($provider === null) {
    exit('Provider missing');
}

// If there's no authorization code, start the OAuth2 flow
if (!isset($_GET['code'])) {
    $authUrl = $provider->getAuthorizationUrl($options);
    $_SESSION['oauth2state'] = $provider->getState();
    header('Location: ' . $authUrl);
    exit;
// Check the state for CSRF prevention
} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
    unset($_SESSION['oauth2state']);
    unset($_SESSION['provider']);
    exit('Invalid state');
} else {
    // Remove provider session as the flow is now complete
    unset($_SESSION['provider']);

    // Try to get an access token using the authorization code
    try {
        $token = $provider->getAccessToken(
            'authorization_code',
            ['code' => $_GET['code']]
        );

        // Output the refresh token (for long-term usage)
        echo 'Refresh Token: ', $token->getRefreshToken();
    } catch (Exception $e) {
        exit('Failed to get access token: ' . $e->getMessage());
    }
}
?>
