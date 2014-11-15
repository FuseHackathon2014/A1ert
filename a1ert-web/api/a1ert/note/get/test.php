<?php
/**
 * Created by IntelliJ IDEA.
 * User: pc
 * Date: 11/15/2014
 * Time: 12:01
*/
$url = 'https://api.sendgrid.com/';
$user = "iismathwizard";
$pass = "Hotfeedmathwizard36696";

$params = array(
    'api_user'  => $user,
    'api_key'   => $pass,
    'to'        => 'exampledoctor@mailinator.com',
    'subject'   => 'Client Issue',
    'html'      => '<p>A client (id: {id}) has created a note which we\'ve determined may need further review by a physician.<br/><p style=\"font-size: 18px;\">{note}</p></p>',
    'text'      => '',
    'from'      => 'a1ert@sendgrid.com',
);
$request =  $url.'api/mail.send.json';

echo "end";
// Generate curl request
$session = curl_init($request);

echo "end";
// Tell curl to use HTTP POST
curl_setopt ($session, CURLOPT_POST, true);
// Tell curl that this is the body of the POST
curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
// Tell curl not to return headers, but do return the response
curl_setopt($session, CURLOPT_HEADER, false);
// Tell PHP not to use SSLv3 (instead opting for TLS)
curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

// obtain response
$response = curl_exec($session);
curl_close($session);

// print everything out
print_r($response);

?>