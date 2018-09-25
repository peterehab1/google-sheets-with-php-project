

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">

    <title>Google Sheets Project</title>
  </head>
  <body>
    <nav class="navbar navbar-dark bg-primary">
      <a class="navbar-brand" href="index.php">Google Sheets Project</a>   
    </nav>

    <div class="backG">
        
        <?php


if ($_POST['code']) {
    
    
    require __DIR__ . '/vendor/autoload.php';


    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes(Google_Service_Sheets::SPREADSHEETS_READONLY);
        $client->setAuthConfig('credentials.json');
        $client->setAccessType('offline');

        // Load previously authorized credentials from a file.
        $credentialsPath = 'token.json';
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }


    // Get the API client and construct the service object.
    $client = getClient();
    $service = new Google_Service_Sheets($client);

    // Prints the names and majors of students in a sample spreadsheet:
    // https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
    $spreadsheetId = '13Z4fEmGYKrXGfl3JFe9sXS61VKiMcLaOPXMbuwF5jk0';
    $range = 'الورقة1';
    $response = $service->spreadsheets_values->get($spreadsheetId, $range);
    $values = $response->getValues();

    if (empty($values)) {
        print "No data found.\n";
    } else {
        
?>


        <table class="table">
          <thead>
            <tr>
              <th scope="col">Code</th>
              <th scope="col">Name</th>
              <th scope="col">Date</th>
            </tr>
          </thead>
          <tbody>
            <?php
           
        $arr = array();
        foreach ($values as $row) {
           

           if ($row[0] == $_POST['code']) {

            echo "<tr>
              <th scope='row'>$row[0]</th>
              <td>$row[1]</td>
              <td>$row[2]</td>
            </tr>";
            array_push($arr, $row[0]);
            
           }
        }

         if ($arr) {
               echo "Results (".count($arr).")";
           }else{
            echo "Results (".count($arr).")" . "<br>";
            echo "Didn't find anything";
           }

        
    }

    ?>

          </tbody>
        </table>
        <?php


}else{
    header("Location: index.php");
}

?>

    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>