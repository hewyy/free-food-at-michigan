<!DOCTYPE html>
<html>
<head>
<title>Free Food at Michigan</title>
<link rel="icon" href="https://am02bpbsu4-flywheel.netdna-ssl.com/wp-content/uploads/2013/07/university-of-michigan-logo.gif">
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div id = "outer">
        <div id = "title">
            <h1>Free Food at Michigan</h1>
        </div>
        <div id = content>
            <div id = "today">

                <t1>Today</t1>
        
                <div id = "today_events">
               

<?php
require __DIR__ . '/google-api-php-client-2.6.0/vendor/autoload.php';

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
  'timeMax' => $tomorrow,
  'timeMin' => $today,
  'orderBy' => 'startTime',
  'singleEvents' => true,
);

    
//retreve results from google calander
$results = $service->events->listEvents('primary', $optParams);
$events = $results->getItems();

if (empty($events)) {
    echo ('<t3> No events today</t3>');
} else {
    foreach ($events as $event) {
        //get the information for each event
        $start = $event->start->dateTime;
        $end = $event->end->dateTime;
        $title = $event->getSummary();
        $link = $event->getDescription();
        
        //convert time to HH:MM AM/PM format
        $start = date('h:iA', strtotime(substr($start,0,18)));
        $end = date('h:iA', strtotime(substr($end,0,18)));
        

        echo('<button type="button" onclick="location.href=\'');
        echo($link);
        echo('\'"><at>');
        echo($title);
        echo('</at><br><p2>');
        echo($start);
        echo(' - ');
        echo($end);
        echo('</p2></button>');
        echo('<br><br>');        
        
    }
}

?>
        
                </div>
            </div>

            <div id = "upcoming">
                
                <t2>Upcoming<br></t2>
        
                <div id = "upcoming_events">
<?php

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
  'orderBy' => 'startTime',
  'singleEvents' => true,
);

    
//retreve results from google calander
$results = $service->events->listEvents('primary', $optParams);
$events = $results->getItems();

if (empty($events)) {
    echo ('<t3> No upcoming events</t3>');
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
        
        echo('<button type="button" onclick="location.href=\'');
        echo($link);
        echo('\'"><at>');
        echo($title);
        echo('</at><br><p2>');
        echo($start);
        echo('</p2></button>');
        echo('<br><br>');
    }
}
?>
                </div>
            </div>
            <div id = "footer">
            <br><br>
            <div id = "google">
            <a href="https://calendar.google.com/calendar/b/4?cid=ZnJlZWZvb2RhdXRvbWF0aW9uQGdtYWlsLmNvbQ">
            <img id = "cal-img" src="google-cal-blue.png" alt="el1">
            </div>

            <div id = "donate">
            <a href="https://www.paypal.com/paypalme/freefoodatmichigan">
            <img id = "donate-img" src="donate.png" alt="el2">
            </div>
            </div>
        </div>


    </div>
    </div>


</body>
</html>
