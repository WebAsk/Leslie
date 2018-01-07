SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE `items`;
INSERT INTO `items` (`singular`, `plural`, `contents`, `joints`, `documents`, `sales`, `icon`, `active`, `order`) VALUES
('content', 'contents', 1, 0, 0, 0, 'file-text-o', 1, 1),
('joint', 'joints', 0, 1, 0, 0, 'sitemap', 1, 2),
('document', 'documents', 0, 0, 1, 0, 'file-image-o', 1, 3),
('account', 'accounts', 0, 0, 0, 1, 'briefcase', 1, 4);

TRUNCATE TABLE `item_types`;
INSERT INTO `item_types` (`item`, `singular`, `plural`, `accounts`, `notice`, `view`, `primary`, `joint`, `intro`, `description`, `joints`, `multiple`, `permalink`, `permanent`, `active`, `navigation`) VALUES
(1, 'page', 'pages', 0, 0, 1, 0, 0, 1, 1, 0, 0, 1, 0, 1, 0),
(1, 'article', 'articles', 0, 0, 1, 1, 1, 1, 1, '3,4,5', 1, 1, 0, 1, 1),
(2, 'category', 'categories', 0, 0, 1, 0, 0, 0, 0, 2, 0, 1, 0, 1, 1),
(2, 'tag', 'tags', 0, 0, 1, 0, 0, 0, 0, 2, 1, 1, 0, 1, 0),
(3, 'image', 'images', 0, 0, 1, 0, 0, 0, 0, 2, 0, 0, 0, 1, 0),
(3, 'slide', 'slides', 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0),
(4, 'profile', 'profiles', 1, 0, 1, 0, 0, 1, 1, 0, 0, 0, 0, 1, 0);

TRUNCATE TABLE `items_list`;
INSERT INTO `items_list` (`id_type`, `id_user`, `name`, `code`) VALUES
(7, 1, 'WebAsk', '06591130486'),
(1, 1, 'Privacy', 'privacy'),
(1, 1, 'Cookie', 'cookie'),
(3, 1, 'Categoria 1', 'cat1'),
(4, 1, 'Tag 1', 'tag1'),
(2, 1, 'Articolo 1', 'art1');

TRUNCATE TABLE `items_permalinks`;
INSERT INTO `items_permalinks` (`item`, `type`, `value`) VALUES
(2, 1, 'privacy'),
(3, 1, 'cookie'),
(4, 3, 'categoria-1'),
(5, 4, 'tag-1'),
(6, 2, 'articolo-1');

TRUNCATE TABLE `item_states`;
INSERT INTO `item_states` (`item`, `value`, `view`, `permits`, `order`) VALUES
(2, 'draft', 0, 2, 1),
(2, 'published', 1, 2, 2);

TRUNCATE TABLE `items_languages`;
INSERT INTO `items_languages` (`id_content`, `title`, `intro`, `description`) VALUES
(1, 'WebAsk', 'do you Web? Ask it!', 'Web Agency Firenze. Sviluppo: siti web, portali, blog, e-commerce, gestionali web-based, landing page e siti vetrina. Web Marketing: gestione di campagne di advertising e social media. Servizi grafici: loghi, volantini e biglietti da visita'),
(2, 'Privacy', 'Informativa sulla privacy', '<p>La presente informativa ha lo scopo di descrivere le modalit&agrave; di gestione e di utilizzo da parte del sito internet WebAsk.it dei dati personali degli utenti che usufruiscono del servizio.</p>
<p>Per Privacy si intende comunemente il diritto della persona di impedire che le informazioni che la riguardano possano essere trattate o visionate, a meno che il soggetto non abbia volontariamente ed espressamente dato il proprio consenso.</p>
<p>In Italia il comportamento legato al trattamento dei dati personali &egrave; regolamentato dal Decreto Legislativo n&deg; 196 del 30 giugno 2003 intitolato: Codice in materia di protezione dei dati personali.</p>
<p>La informiamo che ai sensi dell&#39; articolo 13 del suddetto decretto i dati personali da lei forniti all&#39; atto della registrazione al servizio, diventeranno oggetto di trattamento per finalit&agrave; connesse alla fornitura del servizio stesso.</p>
<p>Il conferimento del consenso al trattamento dei dati personali da parte sua &egrave; assolutamente facoltativo; tuttavia l&#39;eventuale rifiuto rende impossibile l&#39;utilizzo del sevizio offerto nell&#39; ambito del sito web WebAsk.it.</p>
<p>Per trattamento si intende: la raccolta, registrazione, organizzazione, conservazione, elaborazione, modifica, selezione, estrazione, raffronto, utilizzo, diffusione, cancellazione, distribuzione, interconnessione, e quant&#39; altro sia utile per l&#39;esecuzione del servizio, compresa la combinazione di due o pi&ugrave; di tali operazioni, sempre nell&#39; osservanza e nel rispetto dei diritti, delle libert&agrave; e della dignit&agrave; delle persone fisiche.</p>
<p>La informiamo, che inoltre, ai sensi dell&#39; articolo n&deg; 7 di tale decreto, che riguarda il diritto di accesso ai propri dati ed altri diritti, potr&agrave; in qualsiasi momento modificare, opporsi, far cancellare o esercitare tutti i diritti citati nell&#39;articolo inviando una e-mail al nostro indirizzo di posta elettronica <a href="mailto:info@webask.it">info@webask.it</a>.</p>
<p>Per consultare il testo completo del codice in materia di protezione dei dati personali o per avere maggiori informazioni al riguardo la invitiamo a visitare il sito ufficiale dell&#39;Autorit&agrave; Garante <a href="http://www.garanteprivacy.it" rel="nofollow" target="_blank">www.garanteprivacy.it</a>.</p>
<p>Le informazioni relative agli eventi e ai profili inseriti su WebAsk.it saranno visibili nelle ricerche effettuate nel motore di ricerca interno e potrebbero essere messe a dispozione di motori di ricerca terzi in quanto WebAsk.it consente l&#39;indicizzazione dei propri contenuti da parte di motori terzi.</p>'),

(3, 'Cookie', 'Politica sui cookie', '<h2>Finalit&agrave; di questa informativa sui cookie</h2>
<p>Questa informativa &egrave; redatta con lo scopo di informare l&#39;utente in merito alle modalit&agrave; di utilizzo dei cookie da parte nostra e di tutti i siti web da noi realizzati, in conformit&agrave; alle disposizioni del provvedimento del <a href="http://www.garanteprivacy.it" rel="nofollow" target="_blank">Garante per la protezione dei dati personali</a> dell&#39;8 maggio 2014. Precisiamo che il testo in questione deve essere inteso come allegato alla pi&ugrave; generale <a href="http://www.webask.it/info/privacy">informativa sulla privacy</a>.</p>
<p style="background-color: #FFFFE0; padding: 10px">Se sei stato/a reindirizzato/a su questa pagina da un altro sito significa che &egrave; stato realizzato da noi e puoi verificare recandoti nella sezione &quot;<a href="http://www.webask.it/portfolio">portfolio</a>&quot;, dove sono elencati tutti i nostri progetti.</p>
<h2>Informazioni generali sui cookie</h2>
<p>Un cookie &egrave; un piccolo file di testo che viene memorizzato sul computer di chi visualizza un sito web allo scopo di registrare alcune informazioni relative alla visita nonch&eacute; di creare un sistema per riconoscere l&#39;utente anche in momenti successivi. Gli identificatori dei dispositivi, invece, vengono generati raccogliendo ed elaborando alcune informazioni come l&#39;indirizzo IP e/o lo user agent (versione del browser, tipo e versione del sistema operativo) o altre caratteristiche del dispositivo, sempre al fine di ricollegare talune informazioni ad un utente specifico.</p>
<p>Un applicazione web pu&ograve; impostare un cookie soltanto se le impostazioni del browser dell&#39;utente lo consentono. E&#39; importante sapere che un browser consente a un determinato sito Web di accedere solo ed esclusivamente ai cookie da esso impostati e non a quelli di altri siti Web.</p>
<p>Accedendo a WebAsk.it o ad un altro sito web da noi realizzato, quindi utilizzando uno qualsiasi dei nostri servizi, il nostro sistema o quello di uno dei nostri partner potrebbe salvare o leggere cookie e/o altri tipi di identificatori circa il browser e/o il dispositivo utilizzato.</p>
<h2>Campi e ragioni di utilizzo dei cookie</h2>
<p>I motivi per cui WebAsk.it o un altro dei suoi partner potrebbe utilizzare cookie o altri tipo di identificatori sono:</p>
<ul>
    <li>Offrire accesso ad aree riservate mediante autenticazione, in tal caso i cookie sono detti &quot;tecnici&quot; e servono per mantenere la sessione e facilitare la navigazione.</li>
    <li>Tenere traccia delle preferenze dell&#39;utente.</li>
    <li>Offrire pubblicit&agrave; e contenuti pertinenti agli interessi dei visitatori, in questo caso vengono utilizzati <em>cookie di terze parti</em>.</li>
    <li>Elaborare statistiche tramite analisi del traffico e servizi utilizzati in modo generale (<em>analytics</em>).</li>
    <li>Condurre ricerche per migliorare contenuti, prodotti e servizi.</li>
</ul>
<h2>Cookie di terze parti</h2>
<p><strong>WebAsk.it<u> NON utilizza direttamente</u> alcun cookie di profilazione</strong>; gli unici cookie generati e gestiti direttamente da noi sono i cosiddetti cookie tecnici per le ragioni precedentemente indicate. Informiamo, tuttavia, gli utenti che attraverso le nostre pagine potrebbero essere generati cookie di profilazione ad opera di terze parti (Rif. &quot;Cookie di altre societ&agrave;&quot;) come spiegato in seguito.</p>
<p>WebAsk.it integra, all&#39;interno delle proprie pagine, servizi di terze parti che potrebbero impostare e utilizzare propri cookie e/o tecnologie similari. L&#39;impiego di tali cookie e tecnologie similari da parte di tali aziende &egrave; regolato dalle informative sulla privacy di dette societ&agrave; e non dalla presente informativa essendo WebAsk.it totalmente estraneo alla gestione di tali informazioni ed al trattamento dei dati da queste derivanti.</p>
<p>Forniamo di seguito un elenco (non esaustivo) di alcune delle societ&agrave; partner che potrebbero utilizzare i cookie mentre navighi sul network di WebAsk.it</p>
<ul>
    <li>Google Analytics (<a href="https://www.google.com/analytics/learn/privacy.html?hl=it" rel="nofollow" target="_blank">informativa</a>)</li>
    <li>Google Adsense (<a href="http://www.google.com/policies/technologies/ads/" rel="nofollow" target="_blank">informativa</a>)</li>
    <li>Google+ (<a href="http://www.google.com/intl/it_it/policies/technologies/types/" rel="nofollow" target="_blank">informativa</a>)</li>
    <li>Facebook (<a href="https://www.facebook.com/help/cookies/" rel="nofollow" target="_blank">informativa</a>)</li>
    <li>Twitter (<a href="https://support.twitter.com/articles/20170519-uso-dei-cookie-e-di-altre-tecnologie-simili-da-parte-di-twitter" rel="nofollow" target="_blank">informativa</a>)</li>
    <li>YouTube (<a href="https://www.youtube.com/static?template=privacy_guidelines&amp;gl=IT" rel="nofollow" target="_blank">informativa</a>)</li>
</ul>
<h2>Come gestire o disattivare i cookie</h2>
<p>Puoi configurare il tuo browser in modo da accettare o rifiutare tutti i cookie o particolari tipologie di cookie (ad esempio i cookie di terze parti) oppure puoi scegliere di essere avvertito ogni qualvolta un cookie viene impostato all&#39;interno del tuo computer tramite le impostazioni.</p>
<p style="background-color: #FFFFE0; padding: 10px">E&#39; importante sapere che il rifiuto di tutti i cookie potrebbe impedirti di usufruire di una corretta e semplificata navigazione e di accedere ad intere aree e funzionalit&agrave; dei siti. Molti servizi, infatti, richiedono i cookie per poter funzionare correttamente e, l&#39;eventuale blocco ne comprometterebbe l&#39;utilizzo.</p>
<p style="border: 1px solid #DDD; padding: 10px">Se desideri <strong>impedire che vengano salvati sul tuo computer i cookie pubblicitari e/o comportamentali</strong> &egrave; possibile utilizzare il servizio fornito dal sito <a href="http://www.youronlinechoices.com/it/" rel="nofollow" target="_blank">Your Online Choiches</a>.</p>
<p>In caso di dubbi o segnalazioni in merito all&#39;utilizzo dei cookie potete contattarci a <a href="http://www.webask.it/contatti">questa pagina</a>.</p>
<h2>Cancellare i cookie</h2>
<p>Se lo desideri puoi cancellare tutti i cookie generati direttamente da Webask semplicemente cliccando sul link qui sotto:</p>
<p><a href="javascript:cancella_tutti_cookie()">Cancella i cookie del nostro sito</a><script>function cancella_cookie(name) {  var pathBits = location.pathname.split("/");  var pathCurrent = " path=";  document.cookie = name + "=; expires=Thu, 01-Jan-1970 00:00:01 GMT;";  for (var i = 0; i <pathBits.length; i++) {    pathCurrent += ((pathCurrent.substr(-1) != "/") ? "/" : "") + pathBits[i];    document.cookie = name + "=; expires=Thu, 01-Jan-1970 00:00:01 GMT;" + pathCurrent + ";";  }   var domain;  var host = location.hostname;  var domain_array = host.split(".");  var domain_parts = domain_array.length;  if (domain_parts == 2) domain = host;      else{    domain = domain_array[domain_parts-2] + "." + domain_array[domain_parts-1];   } document.cookie = name + "=; expires=Thu, 01-Jan-1970 00:00:01 GMT; path=/; domain=" + domain;} function cancella_tutti_cookie() {  if (document.cookie.length> 0) {      var cookies = document.cookie.split(";");    for(var i=0; i <cookies.length; i++) {      var equals = cookies[i].indexOf("=");      var name = equals> -1 ? cookies[i].substr(0, equals) : cookies[i]; cancella_cookie(name); }  }  alert("I cookie trasmessi direttamente dal nostro sito sono stati cancellati");}</script></p>'),
(4, 'Categoria 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse euismod sagittis placerat. Morbi interdum nisi in arcu eleifend, quis fringilla tellus sollicitudin. Vivamus quis nulla dictum, gravida augue hendrerit, condimentum orci. Nam massa nunc.', '
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed tincidunt mauris. Proin vel iaculis libero. Cras eleifend nibh tellus, at iaculis arcu porta posuere. Proin pharetra, ligula vitae facilisis ornare, urna risus egestas massa, a tempor nisl odio ut ipsum. Curabitur nec enim et nibh rutrum semper. Ut enim felis, semper varius elementum vitae, dapibus nec risus. Praesent ac pharetra velit. Aliquam sit amet felis at augue varius hendrerit. Nam ex nisi, consectetur at congue ac, cursus nec enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
</p>'),
(5, 'Tag 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse euismod sagittis placerat. Morbi interdum nisi in arcu eleifend, quis fringilla tellus sollicitudin. Vivamus quis nulla dictum, gravida augue hendrerit, condimentum orci. Nam massa nunc.', '
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed tincidunt mauris. Proin vel iaculis libero. Cras eleifend nibh tellus, at iaculis arcu porta posuere. Proin pharetra, ligula vitae facilisis ornare, urna risus egestas massa, a tempor nisl odio ut ipsum. Curabitur nec enim et nibh rutrum semper. Ut enim felis, semper varius elementum vitae, dapibus nec risus. Praesent ac pharetra velit. Aliquam sit amet felis at augue varius hendrerit. Nam ex nisi, consectetur at congue ac, cursus nec enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
</p>'),
(6, 'Articolo 1', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse euismod sagittis placerat. Morbi interdum nisi in arcu eleifend, quis fringilla tellus sollicitudin. Vivamus quis nulla dictum, gravida augue hendrerit, condimentum orci. Nam massa nunc.', '
<p>
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sed tincidunt mauris. Proin vel iaculis libero. Cras eleifend nibh tellus, at iaculis arcu porta posuere. Proin pharetra, ligula vitae facilisis ornare, urna risus egestas massa, a tempor nisl odio ut ipsum. Curabitur nec enim et nibh rutrum semper. Ut enim felis, semper varius elementum vitae, dapibus nec risus. Praesent ac pharetra velit. Aliquam sit amet felis at augue varius hendrerit. Nam ex nisi, consectetur at congue ac, cursus nec enim. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas.
</p>
<p>
Vestibulum molestie dui ex, at molestie augue maximus sed. Sed ornare sem non facilisis interdum. Curabitur at dui magna. Cras scelerisque, odio sed ultrices ornare, justo metus vehicula ante, a semper ipsum odio eu velit. Aliquam vel justo quis lectus porttitor lacinia eu eu urna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer rhoncus ante augue, ut ornare justo porta eu. Sed sollicitudin, massa a lobortis lobortis, orci nunc porta orci, sit amet vulputate sem urna nec ipsum. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Quisque tincidunt tellus sapien, eu euismod risus semper eget. Sed ornare venenatis rhoncus. Aenean eu elementum massa. Phasellus quis aliquet ex, eget laoreet nisl. Suspendisse hendrerit scelerisque erat, eget suscipit odio mollis id.
</p>
<p>
Maecenas imperdiet egestas quam, nec dictum mauris gravida sit amet. Nam leo arcu, convallis auctor ultricies eget, pulvinar a erat. Fusce tempor odio pretium ligula iaculis, varius blandit ante blandit. Phasellus non nisi erat. Aliquam porttitor non magna a fringilla. Proin volutpat tempus scelerisque. Integer pretium posuere imperdiet. Donec volutpat lorem sed nisi porta, vitae hendrerit sapien viverra. Suspendisse dictum quam dolor, et pharetra ante egestas sit amet. Curabitur sagittis et sem interdum tincidunt. Phasellus diam elit, egestas a convallis porttitor, auctor sit amet augue. Aenean aliquam dui ac diam imperdiet, in sagittis sapien ultricies. Quisque varius urna et mauris scelerisque, at eleifend dolor consequat.
</p>
<p>
Praesent ut rhoncus eros. Maecenas non eros justo. Vestibulum non augue ex. Vivamus vel est euismod, molestie odio sit amet, mollis urna. Duis in tincidunt sapien, eu luctus mauris. Aenean leo eros, consectetur in lorem in, dapibus maximus libero. Aenean pretium aliquet metus, eget tincidunt tellus gravida vitae. Mauris mollis mauris nec eros pellentesque, ac dapibus odio consequat. Duis porttitor lectus lacus, eget condimentum urna varius et. Curabitur mollis arcu non sem imperdiet consectetur. Donec ac placerat ipsum, quis tincidunt libero. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
</p>
<p>
Mauris venenatis, eros sit amet porttitor tincidunt, magna metus tincidunt sapien, nec fringilla mi elit tincidunt eros. Cras volutpat, risus eget elementum feugiat, nibh orci sodales velit, sit amet placerat velit odio eget ex. Mauris non tempus ligula. Nulla nec mauris rutrum urna molestie efficitur. Etiam lacus dolor, aliquam finibus tellus eget, venenatis lobortis ipsum. Curabitur ut nisi sed justo auctor interdum sit amet semper lorem. Nam interdum finibus velit ut tincidunt. Ut pulvinar nulla elit, ac cursus nisi imperdiet at. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Nulla facilisi. Phasellus et sapien quis enim scelerisque semper sit amet rutrum nunc. Aliquam vulputate erat non orci faucibus, ac ullamcorper libero iaculis. Donec dictum ullamcorper hendrerit. Nullam mattis massa ex, ut vulputate diam vulputate ultrices. Morbi tincidunt, felis vel eleifend iaculis, turpis justo mattis justo, laoreet condimentum ex nisi nec nisi.
<p>');

TRUNCATE TABLE `items_joints`;
INSERT INTO `items_joints` (`type`, `id_joint`, `id_content`) VALUES
(3, 4, 6),
(4, 5, 6);

TRUNCATE TABLE `documents`;
INSERT INTO `documents` (`item`, `folder`, `width`, `height`) VALUES
(5, 'small', 550, 350),
(5, 'medium', 950, 550),
(5, 'large', 1200, 650),
(6, NULL, 1280, 640);

TRUNCATE TABLE `languages`;
INSERT INTO `languages` (`name`, `sign`, `default`, `active`) VALUES
('italian', 'it', 1, 1),
('english', 'en', 0, 0);

TRUNCATE TABLE `user_types`;
INSERT INTO `user_types` (`name`, `admin`, `delete`, `super`, `order`) VALUES
('administrator', 1, 1, 1, 1),
('editor', 1, 0, 0, 2),
('collaborator', 1, 0, 0, 3),
('user', 0, 0, 0, 4);

TRUNCATE TABLE `users`;
INSERT INTO `users` (`type`, `content`, `email`, `password`, `code`, `active`) VALUES
(1, 1, 'doyou@webask.it', 'e2ad332d705723b391920d16b90864b933e6f8bc689f3cb56d53a78735bfa95a', '06591130486', 1);

SET FOREIGN_KEY_CHECKS = 1;