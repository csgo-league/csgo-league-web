<?php

namespace B3none\League\Helpers;

use Exception;

class SteamHelper
{
    const SUMMARY = 'https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/';

    /**
     * @var array
     */
    protected $settings = [
        'apikey' => '', // Get yours today from http://steamcommunity.com/dev/apikey
        'domainname' => '', // Displayed domain in the login-screen
        'loginpage' => '', // Returns to last page if not set
        'logoutpage' => '',
        'skipAPI' => false, // true = dont get the data from steam, just return the steamid64
    ];

    /**
     * SteamHelper constructor.
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config)
    {
        if (session_id() == '') {
            session_start();
        }

        $this->settings = array_merge($this->settings, $config);

        // Start a session if none exists
        if ($this->settings['apikey'] == '') {
            throw new Exception('Please supply a valid steam API key');
        }

        if ($this->settings['loginpage'] == '') {
            $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
            $this->settings['loginpage'] = $protocol . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        }

        // Code (c) 2010 ichimonai.com, released under MIT-License
        if (isset($_GET['openid_assoc_handle']) && !isset($_SESSION['steamdata']['steamid'])) {
            // Did we just return from steam login-page? If so, validate idendity and save the data
            $steamId = $this->validate();
            if ($steamId != '') {
                // ID Proven, get data from steam and save them
                if ($this->settings['skipAPI']) {
                    $_SESSION['steamdata']['steamid'] = $steamId;
                    return; // Skip API here
                }

                $response = file_get_contents(self::SUMMARY . "?key={$this->settings['apikey']}&steamids=$steamId");
                @$apiresp = json_decode($response, true);
                foreach ($apiresp['response']['players'][0] as $key => $value) {
                    $_SESSION['steamdata'][$key] = $value;
                }
            }
        }
    }

    /**
     * @return array|null
     */
    public function getAuthorisedUser(): ?array
    {
        if (is_array($_SESSION['steamdata'])) {
            return $_SESSION['steamdata'];
        }

        return null;
    }

    /**
     * Generate SteamLogin-URL
     *
     * @return string
     */
    public function loginUrl(): string
    {
        $params = [
            'openid.ns' => 'http://specs.openid.net/auth/2.0',
            'openid.mode' => 'checkid_setup',
            'openid.return_to' => $this->settings['loginpage'],
            'openid.realm' => (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'],
            'openid.identity' => 'http://specs.openid.net/auth/2.0/identifier_select',
            'openid.claimed_id' => 'http://specs.openid.net/auth/2.0/identifier_select',
        ];

        return 'https://steamcommunity.com/openid/login?' . http_build_query($params, '', '&');
    }

    /**
     * @return int|string
     */
    private static function validate()
    {
        // Star off with some basic params
        $params = [
            'openid.assoc_handle' => $_GET['openid_assoc_handle'],
            'openid.signed' => $_GET['openid_signed'],
            'openid.sig' => $_GET['openid_sig'],
            'openid.ns' => 'http://specs.openid.net/auth/2.0',
        ];

        // Get all the params that were sent back and resend them for validation
        $signed = explode(',', $_GET['openid_signed']);
        foreach ($signed as $item) {
            $val = $_GET['openid_' . str_replace('.', '_', $item)];
            $params['openid.' . $item] = get_magic_quotes_gpc() ? stripslashes($val) : $val;
        }

        // Finally, add the all important mode.
        $params['openid.mode'] = 'check_authentication';

        // Stored to send a Content-Length header
        $data = http_build_query($params);
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' =>
                    'Accept-language: en\r\n' .
                    'Content-type: application/x-www-form-urlencoded\r\n' .
                    'Content-Length: ' . strlen($data) . '\r\n',
                'content' => $data,
            ],
        ]);

        $result = file_get_contents('https://steamcommunity.com/openid/login', false, $context);

        // Validate wheather it's true and if we have a good ID
        preg_match('#^https://steamcommunity.com/openid/id/([0-9]{17,25})#', $_GET['openid_claimed_id'], $matches);
        $steamID64 = is_numeric($matches[1]) ? $matches[1] : 0;

        // Return our final value
        return preg_match('#is_valid\s*:\s*true#i', $result) == 1 ? $steamID64 : '';
    }

    /**
     * Log the user out
     *
     * @return bool
     */
    public function logout(): bool
    {
        if (!$this->loggedIn()) {
            return false;
        }

        unset($_SESSION['steamdata']); // Delete the users info from the cache, DOESNT DESTROY YOUR SESSION!

        if (!isset($_SESSION[0])) {
            session_destroy();
        }

        // End the session if theres no more data in it
        if ($this->settings['logoutpage'] != '') {
            header('Location: ' . $this->settings['logoutpage']);
        }

        // If the logout-page is set, go there
        return true;
    }

    /**
     * Return whether the user is logged in.
     *
     * @return bool
     */
    public function loggedIn(): bool
    {
        $user = $this->getAuthorisedUser();
        return  $user !== null && $user['steamid'] !== '';
    }
}
