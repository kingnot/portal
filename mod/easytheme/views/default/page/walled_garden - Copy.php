<?php
/**
 * Walled garden page shell
 *
 * Used for the walled garden index page
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

?>

<!DOCTYPE html>
<!--[if IE 7]><html lang="en" class="no-js ie7"><![endif]-->
<!--[if IE 8]><html lang="en" class="no-js ie8"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<meta charset="utf-8" />

<title><?=elgg_echo('login:siteTitle')?></title>

<link rel="shortcut icon" href="../../dist/theme-gcwu-fegc/images/favicon.ico" />
<meta name="description" content="English description / Description en anglais" />
<meta name="dcterms.creator" content="English name of the content author / Nom en anglais de l'auteur du contenu" />
<meta name="dcterms.title" content="English title / Titre en anglais" />
<meta name="dcterms.issued" title="W3CDTF" content="Date published (YYYY-MM-DD) / Date de publication (AAAA-MM-JJ)" />
<meta name="dcterms.modified" title="W3CDTF" content="Date modified (YYYY-MM-DD) / Date de modification (AAAA-MM-JJ)" />
<meta name="dcterms.subject" title="scheme" content="English subject terms / Termes de sujet en anglais" />
<meta name="dcterms.language" title="ISO639-2" content="eng" />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<?php echo elgg_view('page/elements/head', $vars); ?>

<!--[if lte IE 8]>
<script src="../../dist/js/jquery-ie.min.js"></script>
<script src="../../dist/js/polyfills/html5shiv-min.js"></script>
<link rel="stylesheet" href="../../dist/grids/css/util-ie-min.css" />
<link rel="stylesheet" href="../../dist/js/css/pe-ap-ie-min.css" />
<link rel="stylesheet" href="../../dist/theme-gcwu-fegc/css/theme-ie-min.css" />
<noscript><link rel="stylesheet" href="../../dist/theme-gcwu-fegc/css/theme-ns-ie-min.css" /></noscript>
<![endif]-->
<!--[if gt IE 8]><!-->
<script src="../../dist/js/jquery.min.js"></script>
<link rel="stylesheet" href="../../dist/grids/css/util-min.css" />
<link rel="stylesheet" href="../../dist/js/css/pe-ap-min.css" />
<link rel="stylesheet" href="../../dist/theme-gcwu-fegc/css/theme-min.css" />
<noscript><link rel="stylesheet" href="../../dist/theme-gcwu-fegc/css/theme-ns-min.css" /></noscript>
<!--<![endif]-->

<!-- CustomCSSStart -->
<!-- CustomCSSEnd -->

</head>

<body><div id="wb-body">
<div id="wb-skip">
<ul id="wb-tphp">
<li id="wb-skip1"><a href="#wb-cont"><?=elgg_echo('login:skipMain')?></a></li>
<li id="wb-skip2"><a href="#wb-nav"><?=elgg_echo('login:skipFooter')?></a></li>
</ul>
</div>

<div id="wb-head"><div id="wb-head-in"><header>
<!-- HeaderStart -->
<nav role="navigation">
<div id="gcwu-gcnb">
<h2><?=elgg_echo('login:govNavBar')?></h2>
<div id="gcwu-gcnb-in">
<div id="gcwu-gcnb-fip">
<div id="gcwu-sig">
<div id="gcwu-sig-in">
<object data="../../dist/theme-gcwu-fegc/images/sig-eng.svg" role="img" tabindex="-1" aria-label="Government of Canada" type="image/svg+xml">
<img src="../../dist/theme-gcwu-fegc/images/sig-eng.png" alt="Government of Canada" />
</object>
</div>
</div>
<ul>
<li id="gcwu-gcnb1"><a rel="external" href="http://www.canada.gc.ca/menu-eng.html">Canada.gc.ca</a></li>
<li id="gcwu-gcnb2"><a rel="external" href="http://www.servicecanada.gc.ca/eng/home.shtml"><?=elgg_echo('login:services')?></a></li>
<li id="gcwu-gcnb3"><a rel="external" href="http://www.canada.gc.ca/aboutgov-ausujetgouv/depts/menu-eng.html"><?=elgg_echo('login:departments')?></a></li>
<li id="gcwu-gcnb-lang"><a href="./?lang=<?=$_SESSION['ongarde-lang']=="fr"?"en":"fr"?>" lang="fr"><?=elgg_echo('login:toLanguage')?></a></li>

</ul>
</div></div></div></nav>


<div id="gcwu-bnr" role="banner"><div id="gcwu-bnr-in">
<div id="gcwu-wmms"><div id="gcwu-wmms-in"><object data="../../dist/theme-gcwu-fegc/images/wmms.svg" role="img" tabindex="-1" aria-label="Symbol of the Government of Canada" type="image/svg+xml"><img src="../../dist/theme-gcwu-fegc/images/wmms.png" alt="Symbol of the Government of Canada" /></object></div></div>
<div id="gcwu-title"><p id="gcwu-title-in"><a href="./"><?=elgg_echo('login:siteTitle')?></a></p></div>

</div></div>

<!--
<div id="banner" class="grid-8">
	<span></span>
	<img src="<?php echo elgg_get_site_url(); ?>/mod/easytheme/graphics/headimg.jpg" alt="banner" />
</div>
-->
<nav role="navigation">
<div id="gcwu-psnb"><h2><?=elgg_echo('login:siteMenu')?></h2><div id="gcwu-psnb-in"><div class="wet-boew-menubar mb-mega"><div>

<ul class="mb-menu">
<li><div><a target="_blank" class="first" href="http://www.google.com">Section 1</a></div></li>
<li><div><a target="_blank" href="section2/index-eng.html">Section 2</a></div></li>
<li><div><a target="_blank" href="#">Section 3</a></div></li>
<li><div><a target="_blank" href="#">Section 4</a></div></li>
<li><div><a target="_blank" href="#">Section 5</a></div></li>
</ul>
</div></div></div></div>

<div id="gcwu-bc"><h2><?=elgg_echo('breadcrumbs')?></h2><div id="gcwu-bc-in">

<ol>
<li><a href="./"><?=elgg_echo('ongarde:home')?></a></li>
</ol>

</div></div>
</nav>
<!-- HeaderEnd -->
</header></div></div>

<div id="wb-core"><div id="wb-core-in" class="equalize">
<div id="wb-main" role="main"><div id="wb-main-in">
<!-- MainContentStart -->



	<div class="elgg-page-messages">
		<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
	</div>
	<!--<div class="aelgg-body-walledgarden">-->
		<?php 
		echo($vars['body']); 
		?>		
	<!--</div>-->
	


<dl id="gcwu-date-mod" role="contentinfo">
<dt><?=elgg_echo('login:dateModified')?>:</dt><dd><span><time>2013-08-06</time></span></dd>
</dl>
<div class="clear"></div>
<!-- MainContentEnd -->
</div></div>
</div></div>

<div id="wb-foot"><div id="wb-foot-in"><footer><h2 id="wb-nav"><?=elgg_echo('login:footer')?></h2>
<!-- FooterStart -->
<nav role="navigation"><div id="gcwu-sft"><h3><?=elgg_echo('login:siteFooter')?></h3><div id="gcwu-sft-in">
<div id="gcwu-tctr">
<ul>
<li class="gcwu-tc"><a href="http://wet-boew.github.io/wet-boew/License-eng.html" rel="license"><?=elgg_echo('login:termsConditions')?></a></li>
<li class="gcwu-tr"><a href="http://www.tbs-sct.gc.ca/pd-dp/index-eng.asp"><?=elgg_echo('login:transparency')?></a></li>
</ul>
</div>
<div class="clear"></div>
<section><div class="span-2"><h4 class="gcwu-col-head"><?=elgg_echo('login:about')?></h4>
<ul>
<li><a href="#"><?=elgg_echo('login:aboutONGARDE')?></a></li>
</ul>
</div></section>
<section><div class="span-2"><h4 class="gcwu-col-head"><?=elgg_echo('login:contactUs')?></h4>
<ul>
<li><a href="#"><?=elgg_echo('login:questionsComments')?></a></li>
</ul>
</div></section>

</div></div></nav>

<nav role="navigation"><div id="gcwu-gcft"><h3><?=elgg_echo('login:govFooter')?></h3><div id="gcwu-gcft-in"><div id="gcwu-gcft-fip">
<ul>
<li><a rel="external" href="http://healthycanadians.gc.ca/index-eng.php"><span><?=elgg_echo('login:health')?></span><br />healthycanadians.gc.ca</a></li>
<li><a rel="external" href="http://www.voyage.gc.ca/index-eng.asp"><span><?=elgg_echo('login:travel')?></span><br />travel.gc.ca</a></li>
<li><a rel="external" href="http://www.servicecanada.gc.ca/eng/home.shtml"><span><?=elgg_echo('login:serviceCanada')?></span><br />servicecanada.gc.ca</a></li>
<li><a rel="external" href="http://www.jobbank.gc.ca/intro-eng.aspx"><span><?=elgg_echo('login:jobs')?></span><br />jobbank.gc.ca</a></li>
<li><a rel="external" href="http://actionplan.gc.ca/en"><span><?=elgg_echo('login:economy')?></span><br />actionplan.gc.ca</a></li>
<li id="gcwu-gcft-ca"><div><a rel="external" href="http://www.canada.gc.ca/menu-eng.html">Canada.gc.ca</a></div></li>
</ul>
</div></div></div></nav>
<!-- FooterEnd -->
</footer>
</div></div></div>

<!-- ScriptsStart -->
<script src="../../dist/js/settings.js"></script>
<!--[if lte IE 8]>
<script src="../../dist/theme-gcwu-fegc/js/theme-ie-min.js"></script>
<script src="../../dist/js/pe-ap-ie-min.js"></script>
<script src="../../dist/js/jquerymobile/jquery.mobile-ie.min.js"></script>
<![endif]-->
<!--[if gt IE 8]><!-->
<script src="../../dist/theme-gcwu-fegc/js/theme-min.js"></script>
<script src="../../dist/js/pe-ap-min.js"></script>
<script src="../../dist/js/jquerymobile/jquery.mobile.min.js"></script>
<!--<![endif]-->
<!-- ScriptsEnd -->

<!-- CustomScriptsStart -->
<!-- CustomScriptsEnd -->
</body>
</html>