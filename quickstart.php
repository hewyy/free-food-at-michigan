<?php
require __DIR__ . '/google-api-php-client-2.6.0/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
    $client->setAuthConfig('credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Calendar($client);


//get todays date
$today = date('Y-m-d');

//find tomorrows day
$tom = new DateTime($today);
$tom->modify('+1 day');
$tomorrow = $tom->format('Y-m-d');

//add correct formatting
$tomorrow .= "T00:00:00-04:00";
$today .= "T00:00:00-04:00";

$optParams = array(
  'maxResults' => 5,
  'timeMin' => $tomorrow,
  'singleEvents' => true,
);

    
//retreve results from google calander
$results = $service->events->listEvents('primary', $optParams);
$events = $results->getItems();

if (empty($events)) {
    print "No events today.\n";
} else {
    foreach ($events as $event) {
        //get the information for each event
        $start = $event->start->dateTime;
        $end = $event->end->dateTime;
        $title = $event->getSummary();
        $link = $event->getDescription();
        
        
        //convert time to HH:MM AM/PM format
        $start = date('l, F d', strtotime(substr($start,0,18)));
        $end = date('h:iA', strtotime(substr($end,0,18)));
        
        print($title);
        print($start);
        print('/n');
        
        
    }
}
