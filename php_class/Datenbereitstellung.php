<?php
/**
 * Kümmert sich um die gesamte Bereitstellung der Daten von der "opendata.
 * gelsenkirchen.de" Seite.
 */
class Datenbereitstellung {

  /*--KONSTANTEN--*/
  define("CO_REQUEST_URL", "https://opendata.gelsenkirchen.de//api/dataset/user/login");
  define("CO_BENUTZER", "");
  define("CO_PASSWORT", "");

  /*--FUNKTIONEN--*/

  /**
   * Function log_in
   */
  function log_in {

    // Setup request URL.
    $request_url = CO_REQUEST_URL;

    // Prepare user data.
    $user_data = array(
      'username' => CO_BENUTZER,
      'password' => CO_PASSWORT,
    );

    $user_data = http_build_query($user_data);

    // Setup request.
    $curl = curl_init($request_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: application/json'));

    // Accept JSON response.
    curl_setopt($curl, CURLOPT_POST, 1); // Do a regular HTTP POST.
    curl_setopt($curl, CURLOPT_POSTFIELDS, $user_data); // Set POST data.
    curl_setopt($curl, CURLOPT_HEADER, FALSE);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_FAILONERROR, TRUE);

    // Execute request and get response.
    $response = curl_exec($curl);
    // Process response.
    $logged_user = json_decode($response);
    // Save cookie session to be used on future requests.
    $cookie_session = $logged_user->session_name . '=' . $logged_user->sessid;
  }

}

?>
