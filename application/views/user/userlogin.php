<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="utf-8">
        <title>Hellow-login</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1, user-scalable=no">
        <meta name="robots" content="noindex,nofollow">
        <meta name="theme-color" content="#ff6f00">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="DiskForYou">
        <meta name="msapplication-TileColor" content="#fa7c1a">
        <meta name="application-name" content="DiskForYou">
        <link type="text/css" rel="stylesheet" href=<?php echo $this->config->base_url() ?>css/materialize.min.css media="screen,projection">
        <link rel="stylesheet" href=<?php echo $this->config->base_url() ?>css/animate.css>
        <link rel="stylesheet" href=<?php echo $this->config->base_url() ?>css/main.css>
        <link href='<?php echo $this->config->base_url(); ?>css/jquery.noty.css' rel='stylesheet'>
        <link href='<?php echo $this->config->base_url(); ?>css/noty_theme_default.css' rel='stylesheet'>
        <style>
            html { background-color:#5677fc }
        </style>
    </head>
    <body>
        <div id="prednacitac">
            <div id="prednacitac_animace">
                <span>
                </span>
                <div id="prednacitac_animace_1" class="prednacitac_animace"></div>
                <div id="prednacitac_animace_2" class="prednacitac_animace"></div>
                <div id="prednacitac_animace_3" class="prednacitac_animace"></div>
                <div id="prednacitac_animace_4" class="prednacitac_animace"></div>
                <div id="prednacitac_animace_5" class="prednacitac_animace"></div>
                <div id="prednacitac_animace_6" class="prednacitac_animace"></div>
                <div id="prednacitac_animace_7" class="prednacitac_animace"></div>
                <div id="prednacitac_animace_8" class="prednacitac_animace"></div>
            </div>
        </div>
        <div class="row hlavnistrankaprihlaseni">
            <div class="row">
                <div class="col l6">
                    <h1>Login</h1>
                </div>
            </div>
            <div class="col center" style="width:500px">
                <div class="prihlasovaci-formular z-depth-3">
                    <?php
                    if (!empty($login)) {
                        if ($login == "admin") {
                            $type = "admin/login";
                            $text = 'Username';
                        }
                    } else {
                        $type = "user/login_web";
                        $text = 'Email';
                    }
                    ?>
                    <form action="<?php echo $this->config->base_url() . '' . $type ?>" autocomplete="off" method="POST">
                        <div class="input-field">
                            <i class="mdi-action-account-circle prefix"></i>
                            <input type="text" autocomplete="off" id="email" name="username" maxlength="30" required>
                            <label for="email"><?php echo $text; ?></label>
                        </div>
                        <div class="input-field">
                            <i class="mdi-communication-vpn-key prefix"></i>
                            <input type="password" id="heslo" name="password" maxlength="30" required>
                            <label for="heslo">password</label>
                        </div>
                        <div class="center-btn">
                            <button type="submit" class="waves-effect waves-light btn" id="vstoupit" style="background-color: #5677fc">login</button>
<!--                            <button class="waves-effect waves-light btn" id="zapomenute-heslo" style="background-color: #5677fc"><i class="mdi-communication-live-help right"></i>Forgot password</button>-->
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <script type="text/javascript" src="<?php echo $this->config->base_url() ?>js/jquery-1.10.2.js"></script>

        <script type="text/javascript" src=<?php echo $this->config->base_url() ?>js/materialize.min.js></script>

        <script type="text/javascript" src=http://diskforyou.com/js/link.safari.fix.js></script>
        <script src="<?php echo $this->config->base_url(); ?>js/jquery.noty.js"></script>

        <script type="text/javascript">
            $('#prednacitac').hide();
            $( document ).ready(function() {      
                var msg = '<?php echo $this->session->userdata('emsg'); ?>';
                if(msg != ""){
                    toast(msg,4000);
<?php echo $this->session->unset_userdata('emsg'); ?>
        }
    });
          

        </script>

    </body>
</html>