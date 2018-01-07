<button id="auth-button" hidden>Authorize</button>

<div class="row tile_count">
   <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-sign-in"></i> Sessioni</span>
    <div class="count" id="sessions">0</div>
    <span class="count_bottom">Ultimi 30 giorni</span>
  </div>
  <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-user"></i> Utenti</span>
    <div class="count green" id="users">0</div>
    <span class="count_bottom">Ultimi 30 giorni</span>
  </div>
   <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-files-o"></i> Pagine</span>
    <div class="count" id="pageviews">0</div>
    <span class="count_bottom">Ultimi 30 giorni</span>
  </div>
   <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
      <span class="count_top"><i class="fa fa-user"></i> Pagine/Sessioni</span>
      <div class="count" id="pageviewsPerSession">0</div>
      <span class="count_bottom">Ultimi 30 giorni</span>
   </div>
  <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-clock-o"></i> Durata sessione media</span>
    <div class="count" id="avgSessionDuration">0</div>
    <span class="count_bottom">Secondi</span>
  </div>  
  
  <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
    <span class="count_top"><i class="fa fa-sign-out"></i> Frequenza di rimbalzo</span>
    <div class="count" id="bounceRate">0 &percnt;</div>
    <span class="count_bottom">Ultimi 30 giorni</span>
  </div>
</div>

<script>

  // Replace with your client ID from the developer console.
  var CLIENT_ID = '<?php echo $GLOBALS['PROJECT']['GOOGLE']['API']['CLIENT_ID'] ?>';

  // Set authorized scope.
  var SCOPES = ['https://www.googleapis.com/auth/analytics.readonly'];


  function authorize(event) {
    // Handles the authorization flow.
    // `immediate` should be false when invoked from the button click.
    var useImmdiate = event ? false : true;
    var authData = {
      client_id: CLIENT_ID,
      scope: SCOPES,
      immediate: useImmdiate
    };

    gapi.auth.authorize(authData, function(response) {
      var authButton = document.getElementById('auth-button');
      if (response.error) {
        authButton.hidden = false;
      }
      else {
        authButton.hidden = true;
        queryAccounts();
      }
    });
  }


function queryAccounts() {
  // Load the Google Analytics client library.
  gapi.client.load('analytics', 'v3').then(function() {

    // Get a list of all Google Analytics accounts for this user
    gapi.client.analytics.management.accounts.list().then(handleAccounts);
  });
}


function handleAccounts(response) {
  // Handles the response from the accounts list method.
  if (response.result.items && response.result.items.length) {
    // Get the first Google Analytics account.
    var firstAccountId = response.result.items[0].id;

    // Query for properties.
    queryProperties(firstAccountId);
  } else {
    console.log('No accounts found for this user.');
  }
}


function queryProperties(accountId) {
  // Get a list of all the properties for the account.
  gapi.client.analytics.management.webproperties.list(
      {'accountId': accountId})
    .then(handleProperties)
    .then(null, function(err) {
      // Log any errors.
      console.log(err);
  });
}


function handleProperties(response) {
  // Handles the response from the webproperties list method.
  if (response.result.items && response.result.items.length) {

    // Get the first Google Analytics account
    var firstAccountId = response.result.items[0].accountId;

    // Get the first property ID
    var firstPropertyId = response.result.items[0].id;

    // Query for Views (Profiles).
    queryProfiles(firstAccountId, firstPropertyId);
  } else {
    console.log('No properties found for this user.');
  }
}


function queryProfiles(accountId, propertyId) {
  // Get a list of all Views (Profiles) for the first property
  // of the first Account.
  gapi.client.analytics.management.profiles.list({
      'accountId': accountId,
      'webPropertyId': propertyId
  })
  .then(handleProfiles)
  .then(null, function(err) {
      // Log any errors.
      console.log(err);
  });
}


function handleProfiles(response) {
  // Handles the response from the profiles list method.
  if (response.result.items && response.result.items.length) {
    // Get the first View (Profile) ID.
    var firstProfileId = response.result.items[0].id;

    // Query the Core Reporting API.
    queryCoreReportingApi(firstProfileId);
  } else {
    console.log('No views (profiles) found for this user.');
  }
}


function queryCoreReportingApi(profileId) {
  // Query the Core Reporting API for the number sessions for
  // the past seven days.
  gapi.client.analytics.data.ga.get({
    'ids': 'ga:' + profileId,
    'start-date': '30daysAgo',
    'end-date': 'yesterday',
    'metrics': 'ga:sessions,ga:users,ga:avgSessionDuration,ga:pageviews,ga:pageviewsPerSession,ga:bounceRate'
  })
  .then(function(response) {
    var formattedJson = JSON.stringify(response.result, null, 2);
    console.log(response.result);
    document.getElementById('sessions').innerHTML = response.result.totalsForAllResults['ga:sessions'];
    document.getElementById('users').innerHTML = response.result.totalsForAllResults['ga:users'];
    document.getElementById('avgSessionDuration').innerHTML = response.result.totalsForAllResults['ga:avgSessionDuration'].substr(0, 6).replace(".", ",");
    document.getElementById('pageviews').innerHTML = response.result.totalsForAllResults['ga:pageviews'];
    document.getElementById('pageviewsPerSession').innerHTML = response.result.totalsForAllResults['ga:pageviewsPerSession'].substr(0, 5).replace(".", ",");
    document.getElementById('bounceRate').innerHTML = response.result.totalsForAllResults['ga:bounceRate'].substr(0, 5).replace(".", ",") + ' &percnt;';
  })
  .then(null, function(err) {
      // Log any errors.
      console.log(err);
  });
}

  // Add an event listener to the 'auth-button'.
  document.getElementById('auth-button').addEventListener('click', authorize);
</script>

<script src="https://apis.google.com/js/client.js?onload=authorize"></script>

