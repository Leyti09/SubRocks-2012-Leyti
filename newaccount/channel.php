<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/config.inc.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/db_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/time_manip.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/user_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/video_helper.php"); ?>
<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/s/classes/page_builder.php"); ?>
<?php $__video_h = new video_helper($__db); ?>
<?php $__page_b = new page_builder("templates/m"); ?>
<?php $__user_h = new user_helper($__db); ?>
<?php $__db_h = new db_helper(); ?>
<?php $__time_h = new time_helper(); ?>
<?php ob_start(); ?>
<?php
	$__server->page_embeds->page_title = "Betatube";
	$__server->page_embeds->page_description = "Betatube is a website that is cool";
	$__server->page_embeds->page_url = "https://betatube.net/";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<script>
			var yt = yt || {};yt.timing = yt.timing || {};yt.timing.data_ = yt.timing.data_ || {};yt.timing.tick = function(label, opt_time) {var tick = yt.timing.data_['tick'] || {};tick[label] = opt_time || new Date().getTime();yt.timing.data_['tick'] = tick;};yt.timing.info = function(label, value) {var info = yt.timing.data_['info'] || {};info[label] = value;yt.timing.data_['info'] = info;};yt.timing.reset = function() {yt.timing.data_ = {};};if (document.webkitVisibilityState == 'prerender') {yt.timing.info('prerender', 1);document.addEventListener('webkitvisibilitychange', function() {yt.timing.tick('start');}, false);}yt.timing.tick('start');try {var externalPt = (window.gtbExternal && window.gtbExternal.pageT() ||window.external && window.external.pageT);if (externalPt) {yt.timing.info('pt', externalPt);}} catch(e) {}if (window.chrome && window.chrome.csi) {yt.timing.info('pt', Math.floor(window.chrome.csi().pageT));}    
		</script>
		<title><?php echo $__server->page_embeds->page_title; ?></title>
		<link rel="search" type="application/opensearchdescription+xml" href="http://www.youtube.com/opensearch?locale=en_US" title="YouTube Video Search">
		<link rel="shortcut icon" href="/yts/img/favicon-vfldLzJxy.ico" type="image/x-icon">
		<link rel="icon" href="//s.ytimg.com/yts/img/favicon_32-vflWoMFGx.png" sizes="32x32">
		<link rel="alternate" media="handheld" href="http://m.youtube.com/index?&amp;desktop_uri=%2F">
		<link rel="alternate" media="only screen and (max-width: 640px)" href="http://m.youtube.com/index?&amp;desktop_uri=%2F">
		<meta name="description" content="<?php echo $__server->page_embeds->page_description; ?>">
		<meta property="og:image" content="/yts/imgbin/www-embed.png">
		<link id="css-2955892050" rel="stylesheet" href="/yts/cssbin/www-core-vflEJosKh.css">
		<link id="css-151587203" rel="stylesheet" href="/yts/cssbin/www-home-vfl_Eri60.css">
		<script>
			if (window.yt.timing) {yt.timing.tick("ct");}    
		</script>
	</head>
	<body dir="ltr" class="  ltr      site-left-aligned  exp-new-site-width  exp-watch7-comment-ui  hitchhiker-enabled      guide-enabled    guide-expanded  ">
		<div id="body-container">
			<form name="logoutForm" method="POST" action="/logout"><input type="hidden" name="action_logout" value="1"></form>
			<?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_header.php"); ?>
			<div id="alerts"></div>
			<div id="header">
				<div id="masthead_child_div"></div>
				<div id="ad_creative_expand_btn_1" class="masthead-ad-control masthead-ad-control-lohp open hid">
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8485961531031395"
     crossorigin="anonymous"></script>
<!-- betatube index 2013 -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8485961531031395"
     data-ad-slot="3833600933"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
				</div>
			</div>
<?php	$_subslol = $__user_h->fetch_subs_count($_SESSION['siteusername']);	?>
			<div id="page-container">
				<div id="page" class="  home clearfix">
					<div id="guide"><?php require($_SERVER['DOCUMENT_ROOT'] . "/newaccount/left.php"); ?></div>
					<div id="content" class="">
						<div class="branded-page-v2-container enable-fancy-subscribe-button  branded-page-v2-secondary-column-hidden">
							<div class="branded-page-v2-col-container clearfix">
								<div class="branded-page-v2-primary-col">
									<div class="branded-page-v2-primary-col-header-container">
										<div id="context-source-container" data-context-source="Popular on Betatube" style="display:none;"></div>
									</div>
									<div class="branded-page-v2-body" id="gh-activityfeed">
										<div class="context-data-container">
											<div class="lohp-vbox-list lohp-left-vbox-list">
												<div>
												</div>
												<div>
													<div>
														<div>
														<?php $_user = $__user_h->fetch_user_username($_SESSION['siteusername']); ?>
														<script src="/s/js/channelEdit.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="/yt/jsbin/plupload.full.min.js"></script>
														
<div class="channel-customization-bg">
    <br>
    <div class="channel-custom-top">
        <h1 style="color: white;font-weight: bolder;font-weight:normal;display:inline-block;">Channel Settings</h1>
    </div>
    <br>
    <div class="channel-customization-base" id="channel-customize">
        <div class="user-header-bottom">
            <table id="pictures-table" style="width: 970px;padding: 10px;">
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                    <form method="post" id="picturesform" action="/d/channel_update" enctype="multipart/form-data">
                    <td class="left-side-customization">
                        <h2>Avatar</h2>
                        <p style="font-size: 11px;color: grey;">
                            Choose image. Non-square images wil be cropped.<br>
                            Suggested dimensions: 800x800 pixels. Max size: 1MB.
                        </p>
                        <?php if($_user['pfp'] != "default.png") { ?>
                            <a style="font-size: 11px;" href="/get/remove_profile_pic">Remove Profile Picture</a><br>
                        <?php } ?>
                        <br>
                        <a id="browse" href="javascript:;">
                            <button class="yt-uix-button yt-uix-button-default">
                                Browse
                            </button>
                        </a>  
                        <a id="start-upload" href="javascript:;">                                    
                            <button class="yt-uix-button yt-uix-button-default">
                                Upload
                            </button>
                        </a>
                        <div class="customization-module" id="pfp" action="/d/channel_update" enctype="multipart/form-data">
                        </div><br><br>
                        <ul id="filelist"></ul>
                            <pre id="console"></pre>

                            <script type="text/javascript">
                            var alerts = 0;

                            var uploader = new plupload.Uploader({
                                browse_button: 'browse', 
                                url: '/d/channel_update?n=pfp',
                                multi_selection: false,
                                
                                filters: {
                                    ime_types : [
                                        { title : "Image files", extensions : "jpg,gif,png" },
                                    ],
                                    max_file_size: "1024kb"
                                },

                                resize: {
                                    width: 100,
                                    height: 100,
                                    preserve_headers: false
                                }
                            });
                            
                            uploader.init();
                            
                            uploader.bind('FilesAdded', function(up, files) {
                                var html = '';
                                plupload.each(files, function(file) {
                                    console.log("file added");
                                });
                            });
                            
                            uploader.bind('UploadFile', function(up, file) {

                            });

                            uploader.bind('FileUploaded', function(up, file, response) {
                                alerts++;  
                                response = JSON.parse(response.response);
                                $("#photo-update").attr("src", "/dynamic/pfp/" + response.profile_picture);
                            });
                            
                            uploader.bind('Error', function(up, err) {
                                document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
                            });
                            
                            document.getElementById('start-upload').onclick = function() {
                            uploader.start();
                            };
                            
                            </script>
                            <!--<button class="yt-uix-button yt-uix-button-default" id="av-uplod">Select File</button>-->
                            <br>
							<?php    if($_user['channelversion'] !== 'one') { ?>
                            <h3 style="color: grey;">Background Options</h3>
                            <span style="font-size: 11px;color:grey;">Choose image (Max file size: 1MB)</span><br><br>
                            <div id="backgroundoptions" method="post" action="/d/channel_update" enctype="multipart/form-data">
                                <select class="yt-uix-button yt-uix-button-default" name="bgoption">
                                    <option value="repeatxy">Repeat</option>
                                </select>
                                <input style="vertical-align: middle;" type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['primary_color']); ?>">
                            </div><br><?php	}	?>
                            <h2><?php    if($_user['channelversion'] !== 'one') { ?>Background<?php	} else{	?>Banner<?php	}	?></h2>
                            <span style="font-size: 11px;color:grey;" class="grey-text">Choose Image (Max file size: 10MB)</span><br>
                            <div id="backgroundimage" method="post" action="/d/channel_update" enctype="multipart/form-data">
                                <input type="file" name="backgroundbgset" id="background-upload">
						<a id="start-upload" href="javascript:;">                                    
                            <button class="yt-uix-button yt-uix-button-default">
                                Upload
                            </button>
                       </a>
						<h3 style="color: grey;">Channel layout</h3>
                            <div id="featuredvid" action="/d/channel_update" enctype="multipart/form-data">
                            <select style="width: 246px;" class="yt-uix-button yt-uix-button-default" id="country_input" name="layout">
								<option<?php    if($_user['channelversion'] == "one") { ?> selected <?php } ?> value="one">One Channel (2013)</option>
								<option<?php    if($_user['channelversion'] == "cosmic") { ?> selected <?php } ?> value="cosmic">Cosmic Panda (2012)</option>
							</select>
                        <h3 style="color: grey;">Channel information & Settings</h3>
                        <span style="font-size: 10px;color: grey;">Featured Video</span><br>
                        <input class="yt-uix-form-input-text" style="width: 225px;" id="biomd" placeholder="Video ID" value="<?php echo htmlspecialchars($_user['featured']);?>" name="videoid"><br><br>
                        <span style="font-size: 10px;color: grey;">Description</span><br>
                        <div id="bio" action="/d/channel_update" enctype="multipart/form-data">
                            <textarea class="yt-uix-form-input-text" style="resize:none;height: 55px;width: 225px;background-color:white;border: 1px solid #d3d3d3;" id="readthisbio" placeholder="Bio" name="bio"><?php echo htmlspecialchars($_user['bio']); ?></textarea><br>
                        </div>
                        <h3 style="color: grey;">Advanced</h3>
                        <span style="font-size: 10px;color: grey;">Website URL</span><br>
                        <div id="featuredvid" action="/d/channel_update" enctype="multipart/form-data">
                        <input class="yt-uix-form-input-text" style="width: 225px;" id="websiteurlinp" placeholder="Website URL" value="<?php echo htmlspecialchars($_user['website']);?>" name="website">
                        </div><br>
                        <div>
                            <span style="font-size: 10px;color: grey;">Featured Channels</span>
                            <div id="featuredvid" action="/d/channel_update" enctype="multipart/form-data">
                            <input class="yt-uix-form-input-text" style="width: 291px;"  id="biomd" placeholder="Seperate by commas!" value="<?php echo htmlspecialchars($_user['featured_channels']);?>" name="featuredchannels">
                            </div>
                        </div><br>

                        <span style="font-size: 10px;color: grey;">Country</span><br>
                        <div id="countryselect" action="/d/channel_update" enctype="multipart/form-data">
                            <select style="width: 246px;" class="yt-uix-button yt-uix-button-default" id="country_input" name="country" value="<?php echo $_user['country']?>">
                            <?php
                            $countries = ["Earth","Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe"];

                            $countryLength = sizeof($countries);
                            $i = 0;
                            for($i = 0;$i <= $countryLength; $i++)
                            {
                                $c = $countries[$i];
                                if ($c == $_user['country'])
                                //country is the same as in database
                                {
                                ?>
                                <option value="<?php echo $c; ?>" selected="selected"><?php echo $c; ?></option>
                                <?php
                                }
                                else
                                {
                                ?>
                                <option value="<?php echo $c;?>"><?php echo $c; ?></option>
                                <?php
                                }
                            }
                            ?>
                            </select>
                        </div>

                        <?php $categories = ["None", "Director", "Musician", "Comedian", "Guru", "Nonprofit"]; ?>
                        <div style="position: relative;top: 7px;padding-bottom: 6px;">
                            <span style="font-size: 10px;color: grey;">Channel Genre</span><br>
                            <div id="channellayout" action="/d/channel_update" enctype="multipart/form-data">
                                <select style="width: 246px;" class="yt-uix-button yt-uix-button-default" name="genre">
                                    <?php foreach($categories as $category) { ?>
                                        <option value="<?php echo $category; ?>"><?php echo $category; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div><br><br>
                    </td>
                    <td class="right-side-customization">
					    <?php if($__user_h->if_partner($_user['username'])) { ?>
						<h2>Partner Settings</h2>
                            <h3 style="color: grey;">Channel title</h3>
                            <div id="featuredvid" action="/d/channel_update" enctype="multipart/form-data">
                            <input class="yt-uix-form-input-text" style="width: 291px;"  id="biomd" placeholder="Channel title" value="<?php echo htmlspecialchars($_user['title']);?>" name="title">
							<br>
							<?php    if($_user['hasRedirect'] !== '1') { ?>
							<h3 style="color: grey;">Redirect</h3>
                            <span style="font-size: 11px;color:grey;">Just type the part after the domain</span><br><br>
                            <div id="featuredvid" action="/d/channel_update" enctype="multipart/form-data">
                            <input class="yt-uix-form-input-text" style="width: 291px;"  id="biomd" placeholder="url" value="<?php echo htmlspecialchars($_user['redirect']);?>" name="redir">
                            </div>
							<?php	}	?>
						<?php	}	?>
                    </td>
                    </form>
                </tr>
            </table>

            <table id="layout-table" style="width: 970px;padding: 10px;display: none;">
                <tr>
                    <th></th>
                </tr>
                <tr>
                    <form method="post" id="layoutform" action="/d/channel_update" enctype="multipart/form-data">
                    <td>
                        <center>
                            <div class="channel-layout-selector">
                                <button onclick=";upload_layout('feed');return false;">
                                    <img src="/s/img/creator.png">
                                    <h2>Feed</h2>
                                    <p>
                                        A list of recent comments<br>
                                        and videos from you
                                    </p>
                                </button>
                            </div>
                            <div class="channel-layout-selector">
                                <button onclick=";upload_layout('featured');return false;">
                                    <img src="/s/img/blogger.png">
                                    <h2>Blogger</h2>
                                    <p>
                                        A reverse chronological list of<br>
                                        your recent uploads or a<br>
                                        featured playlist<br>
                                    </p>
                                </button>
                            </div>
                            <div class="channel-layout-selector">
                                <button onclick=";upload_layout('playlists');return false;">
                                    <img src="/s/img/network.png">
                                    <h2>Network</h2>
                                    <p>
                                        A featured video from a playlist <br>
                                        with a group of featured<br>
                                        channels
                                    </p>
                                </button>
                            </div>
                            <div class="channel-layout-selector">
                                <button onclick=";upload_layout('everything');return false;">
                                    <img src="/s/img/everything.png">
                                    <h2>Everything</h2>
                                    <p>
                                        A featured video from a playlist<br>
                                        with a group of featured playlists<br>
                                        and channels.
                                    </p>
                                </button>
                            </div>
                        </center>
                    </td>
                    </form>
                </tr>
            </table>
            <table id="misc-table" style="width: 970px;padding: 10px;display:none;">
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                <tr>
                <form method="post" id="miscform" action="/d/channel_update" enctype="multipart/form-data">
                    <td class="left-side-customization">
                        <b>Primary Color</b><br>
                        <span style="font-size: 11px;display: inline-block;width: 256px;">This will change the text color of your channel ribbon.</span>
                        <div class="customization-module" id="primarycolor" style="float: right;position: relative;bottom: 15px;" action="/d/channel_update" enctype="multipart/form-data">
                            <input type="color" id="solidcolor" name="solidcolor" value="<?php echo htmlspecialchars($_user['primary_color']); ?>">
                        </div><br><hr class="thin-line-darker" style="width: unset !important;">
                        <b>Channel Box Color</b><br>
                        <span style="font-size: 11px;display: inline-block;width: 256px;">This will change the background color of the channel info box and the channel ribbon at top.</span><br>
                        <div class="customization-module" id="channelboxcolor" style="float: right;position: relative;bottom: 30px;" action="/d/channel_update" enctype="multipart/form-data">
                            <input type="color" id="channelboxcolorpicker" name="channelboxcolor" value="<?php echo htmlspecialchars($_user['secondary_color']); ?>">
                        </div><br><hr class="thin-line-darker" style="width: unset !important;">
                        <b>Border Color</b><br>
                        <span style="font-size: 11px;display: inline-block;width: 256px;">This will change the border color of all the elements.</span><br>
                        <div class="customization-module" id="bordercolor" style="float: right;position: relative;bottom: 30px;" action="/d/channel_update" enctype="multipart/form-data">
                            <input type="color" id="bordercolorpicker" name="bordercolor" value="<?php echo htmlspecialchars($_user['border_color']); ?>">
                        </div><br><hr class="thin-line-darker" style="width: unset !important;"><br><br><br>
                    </td>
                    <td class="right-side-customization">
                        <b>Background Color</b><br>
                        <span style="font-size: 11px;display: inline-block;width: 256px;">This will change the background of all the other boxes including the top featured area.</span><br>
                        <div class="customization-module" id="boxbackgroundcolor" style="float: right;position: relative;bottom: 30px;" action="/d/channel_update" enctype="multipart/form-data">
                            <input type="color" id="solidcolorbackground" name="backgroundcolor" value="<?php echo htmlspecialchars($_user['third_color']); ?>">
                        </div><br><hr class="thin-line-darker">
                        <b>Text Main Color</b><br>
                        <span style="font-size: 11px;display: inline-block;width: 256px;">This will change the color of the text for boxes.</span><br>
                        <div class="customization-module" id="textmaincolor" style="float: right;position: relative;bottom: 30px;" action="/d/channel_update" enctype="multipart/form-data">
                            <input type="color" id="textmaincolor" name="textmaincolor" value="<?php echo htmlspecialchars($_user['primary_color_text']); ?>">
                        </div><br><hr class="thin-line-darker">
                    </td>
                </form>
						<input class="yt-uix-button yt-uix-button-primary" style="" type="submit" value="Save Changes">
                </tr>
            </table>

            <table>
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                <tr>

                </tr>
            </table>
        </div>
    </div>
</div>
<script>
    var alerts = 0; 
    $('#picturesform').submit(
        function( e ) {
            var data = new FormData(this);
            d = 0;
            $.each($("input[type='file']")[0].files, function(i, file) {
                data.append('file', file + "_" + d);
                d++;
            });

            $.ajax( {
                url: '/d/channel_update',
                type: 'POST',
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function(result){
                    alerts++;
                    addAlert("editsuccess_" + alerts, "Changes applied");
                    showAlert("#editsuccess_" + alerts);
                    $("#bio-changeme").text($("#biom").val());
                }
            } );
            e.preventDefault();
        } 
    );

    $('#miscform').submit(
        function( e ) {
            var data = new FormData(this);

            $.ajax( {
                url: '/d/channel_update',
                type: 'POST',
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function(result){
                    alerts++;
                    addAlert("editsuccess_" + alerts, "Successfully updated your channel!");
                    showAlert("#editsuccess_" + alerts);
                    $("#bio-changeme").text($("#biom").val());
                }
            } );
            e.preventDefault();
        } 
    );

    $('#bgform').submit(
        function( e ) {
            var data = new FormData(this);

            $.ajax( {
                url: '/d/channel_update',
                type: 'POST',
                data: data,
                cache: false,
                processData: false,
                contentType: false,
                success: function(result){
                    alerts++;
                    addAlert("alert__" + alerts, "Successfully updated your channel!");
                    showAlert("#editsuccess_" + alerts);
                    $("#bio-changeme").text($("#readthisbio").val());
                    $("#website_url_change").text($("#websiteurlinp").val());
                    $("#country_change").text($("#country_input").val());
                }
            } );
            e.preventDefault();
        } 
    );

    function upload_layout(layout) {
        $.post("/d/channel_update",
        {
            layout_channel: layout
        },
        function(data, status){
            alerts++;
            addAlert("editsuccess_" + alerts, "Successfully updated your channel!");
            showAlert("#editsuccess_" + alerts);
        });
    }
</script>
<script src="/s/js/channelEdit.js"></script>
<script src="/s/js/alert.js"></script>

														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="footer-container"><?php require($_SERVER['DOCUMENT_ROOT'] . "/s/mod/2013_footer.php"); ?></div>
		<div class="yt-dialog hid" id="feed-privacy-lb">
			<div class="yt-dialog-base">
				<span class="yt-dialog-align"></span>
				<div class="yt-dialog-fg">
					<div class="yt-dialog-fg-content">
						<div class="yt-dialog-loading">
							<div class="yt-dialog-waiting-content">
								<div class="yt-spinner-img"></div>
								<div class="yt-dialog-waiting-text">Loading...</div>
							</div>
						</div>
						<div class="yt-dialog-content">
							<div id="feed-privacy-dialog">
							</div>
						</div>
						<div class="yt-dialog-working">
							<div id="yt-dialog-working-overlay">
							</div>
							<div id="yt-dialog-working-bubble">
								<div class="yt-dialog-waiting-content">
									<div class="yt-spinner-img"></div>
									<div class="yt-dialog-waiting-text">Working...</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="shared-addto-watch-later-login" class="hid">
			Watch later on BetaTube? Are you crazy? I am too lazy to add that.
		</div>
		<div id="shared-addto-menu" style="display: none;" class="hid sign-in">
			<div class="addto-menu">
				<div id="addto-list-panel" class="menu-panel active-panel">
					<span class="yt-uix-button-menu-item yt-uix-tooltip sign-in" data-possible-tooltip="" data-tooltip-show-delay="750"><a href="https://accounts.google.com/ServiceLogin?passive=true&amp;continue=http%3A%2F%2Fwww.youtube.com%2Fsignin%3Faction_handle_signin%3Dtrue%26feature%3Dplaylist%26hl%3Den_US%26next%3D%252F%26nomobiletemp%3D1&amp;uilel=3&amp;hl=en_US&amp;service=youtube" class="sign-in-link">Sign in</a> to add this to a playlist
					</span>
				</div>
				<div id="addto-list-saved-panel" class="menu-panel">
					<div class="panel-content">
						<div class="yt-alert yt-alert-naked yt-alert-success  ">
							<div class="yt-alert-icon">
								<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
							</div>
							<div class="yt-alert-content" role="alert">
								<span class="yt-alert-vertical-trick"></span>
								<div class="yt-alert-message">
									<span class="message">Added to <span class="addto-title yt-uix-tooltip yt-uix-tooltip-reverse" title="More information about this playlist" data-tooltip-show-delay="750"></span></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="addto-list-error-panel" class="menu-panel">
					<div class="panel-content">
						<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
						<span class="error-details"></span>
						<a class="show-menu-link">Back to list</a>
					</div>
				</div>
				<div id="addto-note-input-panel" class="menu-panel">
					<div class="panel-content">
						<div class="yt-alert yt-alert-naked yt-alert-success  ">
							<div class="yt-alert-icon">
								<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="icon master-sprite" alt="Alert icon">
							</div>
							<div class="yt-alert-content" role="alert">
								<span class="yt-alert-vertical-trick"></span>
								<div class="yt-alert-message">
									<span class="message">Added to playlist:</span>
									<span class="addto-title yt-uix-tooltip" title="More information about this playlist" data-tooltip-show-delay="750"></span>
								</div>
							</div>
						</div>
					</div>
					<div class="yt-uix-char-counter" data-char-limit="150">
						<div class="addto-note-box addto-text-box"><textarea id="addto-note" class="addto-note yt-uix-char-counter-input" maxlength="150"></textarea><label for="addto-note" class="addto-note-label">Add an optional note</label></div>
						<span class="yt-uix-char-counter-remaining">150</span>
					</div>
					<button disabled="disabled" type="button" class="playlist-save-note yt-uix-button yt-uix-button-default" onclick=";return false;" role="button"><span class="yt-uix-button-content">Add note </span></button>
				</div>
				<div id="addto-note-saving-panel" class="menu-panel">
					<div class="panel-content loading-content">
						<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
						<span>Saving note...</span>
					</div>
				</div>
				<div id="addto-note-saved-panel" class="menu-panel">
					<div class="panel-content">
						<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
						<span class="message">Note added to:</span>
					</div>
				</div>
				<div id="addto-note-error-panel" class="menu-panel">
					<div class="panel-content">
						<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif">
						<span class="message">Error adding note:</span>
						<ul class="error-details"></ul>
						<a class="add-note-link">Click to add a new note</a>
					</div>
				</div>
				<div class="close-note hid">
					<img src="//s.ytimg.com/yts/img/pixel-vfl3z5WfW.gif" class="close-button">
				</div>
			</div>
		</div>
		<script>
			if (window.yt.timing) {yt.timing.tick("js_head");}    
		</script>
		<script id="js-3960859142" src="//s.ytimg.com/yts/jsbin/www-core-vflKz5-wF.js" data-loaded="true"></script>
		<script>
			var searchBox = document.getElementById('masthead-search-term');
			if (searchBox) {
			  searchBox.focus();
			}
			  yt.setConfig('FEED_DEBUG', true);
			
		</script>
		<script>
			// yt.setMsg('FLASH_UPGRADE', "\u003cdiv class=\"yt-alert yt-alert-default yt-alert-error  yt-alert-player\"\u003e  \u003cdiv class=\"yt-alert-icon\"\u003e\n    \u003cimg s\u0072c=\"\/\/s.ytimg.com\/yts\/img\/pixel-vfl3z5WfW.gif\" class=\"icon master-sprite\" alt=\"Alert icon\"\u003e\n  \u003c\/div\u003e\n\u003cdiv class=\"yt-alert-buttons\"\u003e\u003c\/div\u003e\u003cdiv class=\"yt-alert-content\" role=\"alert\"\u003e    \u003cspan class=\"yt-alert-vertical-trick\"\u003e\u003c\/span\u003e\n    \u003cdiv class=\"yt-alert-message\"\u003e\n            You need to upgrade your Adobe Flash Player to watch this video. \u003cbr\u003e \u003ca href=\"http:\/\/get.adobe.com\/flashplayer\/\"\u003eDownload it from Adobe.\u003c\/a\u003e\n    \u003c\/div\u003e\n\u003c\/div\u003e\u003c\/div\u003e");
			yt.setConfig({
			'PLAYER_CONFIG': {"url": "\/\/s.ytimg.com\/yts\/swf\/masthead_child-vflRMMO6_.swf", "url_v9as2": "", "url_v8": "", "params": {"bgcolor": "#FFFFFF", "allowfullscreen": "false", "allowscriptaccess": "always"}, "attrs": {"width": "1", "id": "masthead_child", "height": "1"}, "min_version": "8.0.0", "args": {"enablejsapi": 1}, "html5": false}
			});
			
			// yt.flash.embed("masthead_child_div", yt.getConfig('PLAYER_CONFIG'));
		</script>
		<script id="js-90506381" src="//s.ytimg.com/yts/jsbin/www-home-vflk-sIPg.js" data-loaded="true"></script>
		<script>
			yt.setConfig({
			  'GUIDE_SELECTED_ITEM': "youtube"
			});
		</script>
		<script>yt.setConfig({'EVENT_ID': "7pFAUZzAG52shAGGr4DACw",'LOGGED_IN': false,'SESSION_INDEX': null,'CURRENT_URL': "http:\/\/www.youtube.com\/",'SAFETY_MODE_PENDING': false,'WATCH_CONTEXT_CLIENTSIDE': true,'FEEDBACK_BUCKET_ID': "Home",'FEEDBACK_LOCALE_LANGUAGE': "en",'FEEDBACK_LOCALE_EXTRAS': {"logged_in": false, "guide_subs": 8, "accept_language": null, "experiments": "906378,925005,919359,910207,914061,916611,920704,912806,902000,919512,929901,913605,925006,906938,931202,931401,908529,930803,920201,930101,930603,906834,926403", "is_branded": "", "is_partner": ""}});yt.setMsg({'ADDTO_WATCH_LATER_ADDED': "Added",'ADDTO_WATCH_LATER_ERROR': "Error"});yt.setAjaxToken('addto_ajax_logged_out', "H6seGTii3HNcaaSYiOcuR3-DGLF8MTM2MzI3MjU1OEAxMzYzMTg2MTU4");yt.setAjaxToken('channel_details_ajax', "TwF1IzDuM74TMIFat4yLZSiVCVB8MTM2MzI3MjU1OEAxMzYzMTg2MTU4");  yt.setConfig('FEED_PRIVACY_CSS_URL', "\/\/s.ytimg.com\/yts\/cssbin\/www-feedprivacydialog-vflQ4FT2R.css");
			yt.setAjaxToken('feed_privacy_ajax', "");
			  yt.pubsub.subscribe('init', yt.www.account.FeedPrivacyDialog.init);
			yt.setConfig({'SBOX_JS_URL': "\/\/s.ytimg.com\/yts\/jsbin\/www-searchbox-vflzZmr_k.js",'SBOX_SETTINGS': {"SESSION_INDEX": null, "SHOW_CHIP": false, "USE_HTTPS": false, "PSUGGEST_TOKEN": null, "HAS_ON_SCREEN_KEYBOARD": false, "REQUEST_LANGUAGE": "en", "IS_HH": true, "EXPERIMENT_ID": -1, "REQUEST_DOMAIN": "us", "CHIP_PARAMETERS": {}, "CLOSE_ICON_URL": "\/\/s.ytimg.com\/yts\/img\/icons\/close-vflrEJzIW.png"},'SBOX_LABELS': {"SUGGESTION_DISMISS_LABEL": "Dismiss", "SUGGESTION_DISMISSED_LABEL": "Suggestion dismissed"}});
		</script>    <script>
			yt.setConfig({'TIMING_ACTION': "glo",'TIMING_INFO': {"mod_li": 0, "mod_spf": 0, "e": "906378,925005,919359,910207,914061,916611,920704,912806,902000,919512,929901,913605,925006,906938,931202,931401,908529,930803,920201,930101,930603,906834,926403", "mod_lt": "cold"}});    
		</script>
		<script>yt.setConfig({'XSRF_TOKEN': "S0uwk0EgvxoAOX_v0c0U9_twFVh8MTM2MzI3MjU1OEAxMzYzMTg2MTU4",'XSRF_REDIRECT_TOKEN': "5MaT5zwJCAslCgglIiPwGx8NqXZ8MTM2MzIwMDU1OEAxMzYzMTg2MTU4",'XSRF_FIELD_NAME': "session_token"});</script><script>yt.setConfig('THUMB_DELAY_LOAD_BUFFER', 300);</script>    <script>
			if (window.yt.timing) {yt.timing.tick("js_foot");}    
		</script>
		<div id="debug"></div>
	</body>
</html>
