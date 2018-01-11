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

<div id="embed-api-auth-container"></div>
<div id="chart-container"></div>
<div id="chart-1-container"></div>
<div id="chart-2-container"></div>
<!--<div id="view-selector-container"></div>-->

<script>
(function(w,d,s,g,js,fs){
  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(f){this.q.push(f);}};
  js=d.createElement(s);fs=d.getElementsByTagName(s)[0];
  js.src='https://apis.google.com/js/platform.js';
  fs.parentNode.insertBefore(js,fs);js.onload=function(){g.load('analytics');};
}(window,document,'script'));
</script>

<script>

gapi.analytics.ready(function() {

    /**
    * Authorize the user immediately if the user has already granted access.
    * If no access has been created, render an authorize button inside the
    * element with the ID "embed-api-auth-container".
    */
    gapi.analytics.auth.authorize({

        container: 'embed-api-auth-container',
        clientid: '<?php echo $GLOBALS['PROJECT']['GOOGLE']['API']['CLIENT_ID'] ?>'

    });

    /**
    * Create a new ViewSelector instance to be rendered inside of an
    * element with the id "view-selector-container".
    var viewSelector = new gapi.analytics.ViewSelector({
        container: 'view-selector-container'
    });
     */


    // Render the view selector to the page.
    // viewSelector.execute();


    /**
     * Create a new DataChart instance with the given query parameters
     * and Google chart options. It will be rendered inside an element
     * with the id "chart-container".
     */
    var dataChart = new gapi.analytics.googleCharts.DataChart({
        query: {
            ids: 'ga:<?php echo $GLOBALS['PROJECT']['GOOGLE']['ANALYTICS']['PROFILE'] ?>',
            metrics: 'ga:users',
            dimensions: 'ga:date',
            'start-date': '30daysAgo',
            'end-date': 'yesterday'
        },
        chart: {
            container: 'chart-container',
            type: 'LINE',
            options: {
                width: '100%'
            }
        }
    }).execute();
    
    var dataChart1 = new gapi.analytics.googleCharts.DataChart({
        query: {
            ids: 'ga:<?php echo $GLOBALS['PROJECT']['GOOGLE']['ANALYTICS']['PROFILE'] ?>',
            metrics: 'ga:pageviews,ga:avgTimeOnPage',
            dimensions: 'ga:pageTitle,ga:pagePath',
            'start-date': '30daysAgo',
            'end-date': 'yesterday',
            sort: '-ga:pageviews',
            'max-results': 8
        },
        chart: {
            container: 'chart-1-container',
            type: 'TABLE',
            options: {
                width: '100%',
                pieHole: 4/9
            }
        }
    }).execute();
    
    var dataChart2 = new gapi.analytics.googleCharts.DataChart({
        query: {
            ids: 'ga:<?php echo $GLOBALS['PROJECT']['GOOGLE']['ANALYTICS']['PROFILE'] ?>',
            metrics: 'ga:sessions',
            dimensions: 'ga:deviceCategory',
            'start-date': '30daysAgo',
            'end-date': 'yesterday',
            'max-results': 6,
            sort: '-ga:sessions'
        },
        chart: {
            container: 'chart-2-container',
            type: 'PIE',
            options: {
              width: '100%',
              pieHole: 4/9
            }
        }
    }).execute();


    /**
    * Render the dataChart on the page whenever a new view is selected.
    viewSelector.on('change', function(ids) {
        dataChart.set({query: {ids: ids}}).execute();
    });
    */

    var report = new gapi.analytics.report.Data({
        query: {
            ids: 'ga:<?php echo $GLOBALS['PROJECT']['GOOGLE']['ANALYTICS']['PROFILE'] ?>',
            metrics: 'ga:sessions,ga:users,ga:avgSessionDuration,ga:pageviews,ga:pageviewsPerSession,ga:bounceRate',
            dimensions: 'ga:date',
            'start-date': '30daysAgo',
            'end-date': 'yesterday'
        }
    });
    
    report.on('success', function(response) {
        console.log(response);
        document.getElementById('sessions').innerHTML = response.totalsForAllResults['ga:sessions'];
        document.getElementById('users').innerHTML = response.totalsForAllResults['ga:users'];
        document.getElementById('avgSessionDuration').innerHTML = response.totalsForAllResults['ga:avgSessionDuration'].substr(0, 6).replace(".", ",");
        document.getElementById('pageviews').innerHTML = response.totalsForAllResults['ga:pageviews'];
        document.getElementById('pageviewsPerSession').innerHTML = response.totalsForAllResults['ga:pageviewsPerSession'].substr(0, 5).replace(".", ",");
        document.getElementById('bounceRate').innerHTML = response.totalsForAllResults['ga:bounceRate'].substr(0, 5).replace(".", ",") + ' &percnt;';
    });

    report.execute();

});
</script>