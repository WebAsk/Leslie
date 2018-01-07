<h1>Cookie</h1>
<hr>

<p>
Un cookie &egrave; un piccolo file di testo che viene memorizzato sul computer di chi visualizza un sito web allo scopo di registrare alcune informazioni relative alla visita nonché di creare un sistema per riconoscere l'utente anche in momenti successivi. Gli identificatori dei dispositivi, invece, vengono generati raccogliendo ed elaborando alcune informazioni come l'indirizzo IP e/o lo user agent (versione del browser, tipo e versione del sistema operativo) o altre caratteristiche del dispositivo, sempre al fine di ricollegare talune informazioni ad un utente specifico.
</p>

<p>
Un applicazione web pu&ograve; impostare un cookie soltanto se le impostazioni del browser dell'utente lo consentono. E' importante sapere che un browser consente a un determinato sito Web di accedere solo ed esclusivamente ai cookie da esso impostati e non a quelli di altri siti Web.
</p>

<p>
Accedendo a <?php echo $GLOBALS['PROJECT']['NAME'] ?> o ad un altro sito web da noi realizzato, quindi utilizzando uno qualsiasi dei nostri servizi, il nostro sistema o quello di uno dei nostri partner potrebbe salvare o leggere cookie e/o altri tipi di identificatori circa il browser e/o il dispositivo utilizzato.
</p>

<h2>Campi e ragioni di utilizzo dei cookie</h2>

<p>
I motivi per cui <?php echo $GLOBALS['PROJECT']['NAME'] ?> o un altro dei suoi partner potrebbe utilizzare cookie o altri tipo di identificatori sono:
</p>

<ul>
  <li>Offrire accesso ad aree riservate mediante autenticazione, in tal caso i cookie sono detti &quot;tecnici&quot; e servono per mantenere la sessione e facilitare la navigazione.</li>
  <li>Tenere traccia delle preferenze dell'utente.</li>
  <li>Offrire pubblicit&agrave; e contenuti pertinenti agli interessi dei visitatori, in questo caso vengono utilizzati <em>cookie di terze parti</em>.</li>
  <li>Elaborare statistiche tramite analisi del traffico e servizi utilizzati in modo generale (<em>analytics</em>).</li>
  <li>Condurre ricerche per migliorare contenuti, prodotti e servizi.</li>
</ul>

<h2>Cookie di terze parti</h2>

<p>
<strong><?php echo $GLOBALS['PROJECT']['NAME'] ?> <u>NON utilizza direttamente</u> alcun cookie di profilazione</strong>; gli unici cookie generati e gestiti direttamente da noi sono i cosiddetti cookie tecnici per le ragioni precedentemente indicate. Informiamo, tuttavia, gli utenti che attraverso le nostre pagine potrebbero essere generati cookie di profilazione ad opera di terze parti (Rif. &quot;Cookie di altre societ&agrave;&quot;) come spiegato in seguito.
</p>

<p>
<?php echo $GLOBALS['PROJECT']['NAME'] ?> integra, all'interno delle proprie pagine, servizi di terze parti che potrebbero impostare e utilizzare propri cookie e/o tecnologie similari. L'impiego di tali cookie e tecnologie similari da parte di tali aziende è regolato dalle informative sulla privacy di dette societ&agrave; e non dalla presente informativa essendo <?php echo $GLOBALS['PROJECT']['NAME'] ?> totalmente estraneo alla gestione di tali informazioni ed al trattamento dei dati da queste derivanti.</p><p>Forniamo di seguito un elenco (non esaustivo) di alcune delle societ&agrave; partner che potrebbero utilizzare i cookie mentre navighi sul network di <?php echo $GLOBALS['PROJECT']['NAME'] ?></p>

<ul>
  <li>Google Analytics (<a href="https://www.google.com/analytics/learn/privacy.html?hl=it" target="_blank" rel="nofollow">informativa</a>)</li>
  <li>Google Adsense (<a href="http://www.google.com/policies/technologies/ads/" target="_blank" rel="nofollow">informativa</a>)</li>
  <li>Google+ (<a href="http://www.google.com/intl/it_it/policies/technologies/types/" target="_blank" rel="nofollow">informativa</a>)</li>
  <li>Facebook (<a href="https://www.facebook.com/help/cookies/" target="_blank" rel="nofollow">informativa</a>)</li>
  <li>Twitter (<a href="https://support.twitter.com/articles/20170519-uso-dei-cookie-e-di-altre-tecnologie-simili-da-parte-di-twitter" target="_blank" rel="nofollow">informativa</a>)</li>
  <li>YouTube (<a href="https://www.youtube.com/static?template=privacy_guidelines&gl=IT" target="_blank" rel="nofollow">informativa</a>)</li>
</ul>

<h2>Come gestire o disattivare i cookie</h2>

<p>
Puoi configurare il tuo browser in modo da accettare o rifiutare tutti i cookie o particolari tipologie di cookie (ad esempio i cookie di terze parti) oppure puoi scegliere di essere avvertito ogni qualvolta un cookie viene impostato all'interno del tuo computer tramite le impostazioni.
</p>

<p style="background-color: #FFFFE0; padding: 1em">
E' importante sapere che il rifiuto di tutti i cookie potrebbe impedirti di usufruire di una corretta e semplificata navigazione e di accedere ad intere aree e funzionalit&agrave; dei siti. Molti servizi, infatti, richiedono i cookie per poter funzionare correttamente e, l'eventuale blocco ne comprometterebbe l'utilizzo.
</p>

<p style="border: 1px solid #DDD; padding: 1em">
Se desideri <strong>impedire che vengano salvati sul tuo computer i cookie pubblicitari e/o comportamentali</strong> è possibile utilizzare il servizio fornito dal sito <a href="http://www.youronlinechoices.com/it/" target="_blank" rel="nofollow">Your Online Choiches</a>.
</p>

<p>
In caso di dubbi o segnalazioni in merito all'utilizzo dei cookie potete contattarci a <a href="<?php echo $GLOBALS['PROJECT']['URL']['BASE'] ?>/contatti">questa pagina</a>.
</p><br />
<h2>Cancellare i cookie</h2>
<p>
Se lo desideri puoi cancellare tutti i cookie generati direttamente da Webask semplicemente cliccando sul link qui sotto:
</p>

<a href="javascript:delete_all_cookie()">Cancella i cookie del nostro sito</a>

<script>
   function delete_cookie(name) {
     var pathBits = location.pathname.split('/');
     var pathCurrent = ' path=';
     document.cookie = name + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT;';
     // cancello per ogni possibile path
     for (var i = 0; i < pathBits.length; i++) {
       pathCurrent += ((pathCurrent.substr(-1) != '/') ? '/' : '') + pathBits[i];
       document.cookie = name + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT;' + pathCurrent + ';';
     }
     // cancello anche i cookie settati a livello del dominio principale
     var domain;
     var host = location.hostname;
     var domain_array = host.split('.');
     var domain_parts = domain_array.length;
     if (domain_parts == 2) domain = host;    
     else{
       domain = domain_array[domain_parts-2] + '.' + domain_array[domain_parts-1]; 
     }
     document.cookie = name + '=; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=/; domain=' + domain;
   }    
   function delete_all_cookie() {
     if (document.cookie.length > 0) {  
       var cookies = document.cookie.split(';');
       for(var i=0; i < cookies.length; i++) {
         var equals = cookies[i].indexOf('=');
         var name = equals > -1 ? cookies[i].substr(0, equals) : cookies[i];
         delete_cookie(name);
       }
     }
     alert('I cookie trasmessi direttamente dal nostro sito sono stati cancellati');
   }
</script>