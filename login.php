<?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/config.inc.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>FulpTube</title>
    <link href="/static/fulptubefull.css" rel="stylesheet">
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <script>function onLogin(token){ document.getElementById('submitform').submit(); }</script>
</head>
<body class="ltr site-left-aligned exp-watch7-comment-ui hitchhiker-enabled guide-enabled guide-expanded page-loaded" dir="ltr">
<div id="body-container">
    <?php require($_SERVER['DOCUMENT_ROOT'] . "/static/important/header.php"); ?>
    <?php
    if($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['password'] && $_POST['username']) {
        $email = htmlspecialchars(@$_POST['email']);
        $username = htmlspecialchars(@$_POST['username']);
        $password = @$_POST['password'];
        $passwordhash = password_hash(@$password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("SELECT password FROM `users` WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if(!mysqli_num_rows($result)){ { $error = "incorrect username or password"; goto skip; } }
        $row = $result->fetch_assoc();
        $hash = $row['password'];

        if(!password_verify($password, $hash)){ $error = "incorrect username or password"; goto skip; }
        $_SESSION['siteusername'] = $username;

        header("Location: manage");
    }
    skip:
    ?>
    <div id="page-container">
        <div id="page" class="  watch   clearfix"> 
            <center>
                <form action="" method="post" id="submitform">
                    <table>
                        <tbody><tr class="email">
                            <td class="label"><label for="email">User Name:</label></td>
                            <td class="input"><input type="text" name="username" id="email"></td>
                        </tr>
                        <tr class="password">
                            <td class="label"><label for="password">Password:</label></td>
                            <td class="input"><input name="password" type="password" id="password"></td>
                        </tr>
                        <tr class="remember">
                            <td colspan="2"><input type="checkbox" name="Remember" value="Remember" id="checkbox">
                                <label for="checkbox">Remember my E-mail</label></td>
                        </tr>
                        <tr class="buttons">
                            <td colspan="2"><input type="submit" value="Login"></td>
                        </tr>
                        <tr class="forgot">

                        </tr>
                        </tbody></table>
                </form>
            </center>
        </div>
        <div id="footer-ads">
            <div id="ad_creative_3" class="ad-div " style="z-index: 1">
                <iframe id="ad_creative_iframe_3" scrolling="no" style="z-index: 1" src="https://web.archive.org/web/20130715000613/http://ad.doubleclick.net/N6762/adi/mkt.ythome_1x1/;sz=1x1;tile=3;plat=pc;kcr=us;kga=-1;kgg=-1;klg=en;kmyd=ad_creative_3;ord=4534036351165981?" width="1" height="1" frameborder="0"></iframe>
                <script>
                    (function() {
                        var ord = Math.floor(Math.random() * 10000000000000000);
                        var adIframe = document.getElementById("ad_creative_iframe_3");
                        adIframe.src = "https://web.archive.org/web/20130715000613/http://ad.doubleclick.net/N6762/adi/mkt.ythome_1x1/;sz=1x1;tile=3;plat=pc;kcr=us;kga=-1;kgg=-1;klg=en;kmyd=ad_creative_3;ord=" + ord + "?";
                    })();
                </script>
            </div>
        </div>
    </div>
</div>
</body>
</html>
