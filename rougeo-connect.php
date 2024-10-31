<?php
/**
* Plugin Name: Rougeo Connect
* Plugin URI: http://www.rougeo.com/wordpress
* Description: Show Rougeo trails on your website
* Version: 1.0
* Author: Geosho Cyf
* Author URI: http://www.rougeo.com/wordpress
* License: GPL12
*/


add_action( 'admin_menu', 'rougeo_custom_admin_menu' );

function rougeo_custom_admin_menu() {
    add_options_page(
        'Rougeo Connect',
        'Rougeo Connect',
        'manage_options',
        'rougeo-connect',
        'rougeo_options_page'
    );
}

function rougeo_options_page() {
    ?>
    <div class="wrap">
        <h2>Rougeo Connect</h2>
        <p>Please copy the shortcode below and paste it anywhere on the page you would like the feed to show.</p>
          <p><div style="background: #b7e3f2; border: 1px solid #88c3d7; color: #222222; padding: 5px 10px; border-radius: 3px; display: inline-block;">[rougeo publisherid="11" lang="GBR"]</div></p>
        <h3>Customise</h3>
        <ul>
          <li>- Replace the <em>'publisherid'</em> value with your Rougeo Publisher ID (e.g publisherid="11").</li>
          <li>- Replace the <em>'lang'</em> value with your desired language if available. (e.g lang="GBR" or lang="CYM").</li>
        </ul>

          <h3>Support</h3>
            <p>If you would like any assistance with our plugin please do not hesitate to get in touch. <a href="http://www.rougeo.com" target="_blank">Visit our website</a> for support contact details. </p>
    </div>

<?php
}


function rougeo_shortcode_func($atts)
{

  $atts = shortcode_atts(array(
            'publisherid' => '#',
            'lang' => 'GBR'
        ), $atts, 'rougeo');

?>



<style>
#trails {
	background: red;
	min-height: 200px;
	color: #fff;
}

#feature {
	background: ;
	min-height: 200px;
	color: #fff;
}


ul.trails {
	margin: 0;
	padding: 0;
	width: 100%;
	overflow: hidden;
}

ul.trails li {
	padding-bottom: 20px;
	border-bottom: 2px solid #eaeaea;
	width: 44%;
	float: left;
	margin: 3%;
	list-style: none;
}

ul.trails li:nth-child(even) {
}

ul.trails li h3 {
	font-size: 22px;
	margin-bottom: 10px;
	font-weight: 700;
	font-family: 'proxima-nova', sans-serif;
}

ul.trails li span.stars {
	color: #e8dc1a;
}

ul.trails li p.description {
	font-size: 13px;
	padding-bottom: 0px;
	line-height: 1.4em;
	color: #555555;
	min-height: 60px;
}

ul.trails li p.meta {
	font-size: 13px;
	color: #666666;
}

ul.trails li a.button {
	text-decoration: none;
	color: #fff;
	font-weight: 700;
	background: #58aa49;
	padding: 11px 14px;
	border-radius: 4px;
	border-bottom: 3px solid #489739;
	float: left;
	margin-right: 20px;
}

a.downloadbutton {
	background: #EE644F !important;
	border-bottom: 3px solid #d7503c !important;
}

ul.trails li a.button:hover {
	background: #409031;
	border-bottom: 3px solid #338125;
}

ul.trails button {
	text-decoration: none;
	color: #fff;
	font-weight: 700;
	background: #2787AD;
	padding: 11px 14px;
	border: none;
	font-size: 16px;
}

ul.trails button:hover {
	background: #2899c6;
}

ul.trails li div.image {
	height: 200px;
	margin-bottom: 15px;
	background-size: 100% auto !important;
	background-repeat: no-repeat !important;
	background-position: center center !important;
	border-radius: 3px;
	-webkit-transition: all 0.5s ease-in-out;
	-moz-transition: all 0.5s ease-in-out;
	-o-transition: all 0.5s ease-in-out;
	transition: all 0.5s ease-in-out;
}

ul.trails li div.image:hover {
	opacity: 0.8;
}

ul.trails li div.image a {
	display: block;
	width: 100% !important;
	height: 100% !important;
	text-decoration: none;
}

iframe {
	width: 789px;
	height: 438px;
	border: none;
}

ul.trails p {
	text-transform: capitalize;
}

a.selected {
  background-color:#1F75CC;
  color:white;
  z-index:100;
}

.messagepop {
  background-color:#FFFFFF;
  border:1px solid #999999;
  cursor:default;
  display:none;
  margin-top: 15px;
  position:absolute;
  text-align:left;
  width:394px;
  z-index:50;
  padding: 25px 25px 20px;
}

label {
  display: block;
  margin-bottom: 3px;
  padding-left: 15px;
  text-indent: -15px;
}

.messagepop p, .messagepop.div {
  border-bottom: 1px solid #EFEFEF;
  margin: 8px 0;
  padding-bottom: 8px;
}

</style>

<script type="text/javascript">
    $(document).ready(function(){

    var output = $('#output');


    $.ajax({
    url: 'http://rougeo.geosho.com/api/anon_routes/<?php echo $atts['publisherid'];?>/',
    dataType: 'json',
    type: 'GET',
    success: function(data){


    $.each(data.features, function(i,route){
    if (route.properties.trail_language == "<?php echo $atts['lang'];?>") {
    var shortTitle = jQuery.trim(route.properties.name).substring(0, 35)
              .trim(this) + '...';

              var shortText = jQuery.trim(route.properties.description).substring(0, 100)
              .trim(this) + '...';

    var trails = '<li class="basic-modal"><h3>'+shortTitle+'</h3>'
    + '<p class="meta"><strong>'+route.properties.category+':</strong> '+ ((route.properties.length / 1000) / 1.6).toFixed(2) +'m / '+route.properties.duration * 60 + 'mins &nbsp;&nbsp;&nbsp; <strong> Difficulty: </strong>' +route.properties.difficulty+'/5<br>'
    + '<div class="image" style="background: url('+route.properties.cover_image +'?width=1000);"><a href="http://rougeo.geosho.com/RougeoCore/iframe_trail_full_detail/'+route.properties.id+'" target="_blank">&nbsp;</a></div>'
    + '<p class="description">'+shortText+'<br>'
    + '<p><a href="http://rougeo.geosho.com/RougeoCore/iframe_trail_full_detail/'+route.properties.id+'" class="button" target="_blank">View on map</a></p>';



    output.append(trails);
    }
    });

    },
    error: function(){
    output.text('There was an error loading the data.');
    }
    });
    });

</script>

<ul id="output" class="trails"></ul>



<?php }

add_shortcode('rougeo', 'rougeo_shortcode_func');
