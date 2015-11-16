<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="msapplication-tap-highlight" content="no">
        <meta name="description" content="Hellow Chat! Real Time Messaging System with Admin Panel">
        <title>Hello! Chat Admin Panel</title>
        <!-- Favicons-->
        <link rel="apple-touch-icon-precomposed" href="images/favicon/apple-touch-icon-152x152.png">
        <meta name="msapplication-TileColor" content="#FFFFFF">
        <meta name="msapplication-TileImage" content="images/favicon/mstile-144x144.png">
        <link rel="icon" href="images/favicon/favicon-32x32.png" sizes="32x32">
        <!--  Android 5 Chrome Color-->
        <meta name="theme-color" content="#EE6E73">
        <!-- CSS-->
        <link href="<?php echo $this->config->base_url() ?>css/prism.css" rel="stylesheet">
        <link href="<?php echo $this->config->base_url() ?>css/ghpages-materialize.css" type="text/css" rel="stylesheet" media="screen,projection">
        <link href="http://fonts.googleapis.com/css?family=Inconsolata" rel="stylesheet" type="text/css">
        <link href='<?php echo $this->config->base_url(); ?>css/jquery.noty.css' rel='stylesheet'>
        <link href='<?php echo $this->config->base_url(); ?>css/noty_theme_default.css' rel='stylesheet'>
        <style>
            body {
                display: flex;
                min-height: 100vh;
                flex-direction: column;
            }
            main {
                flex: 1 0 auto;
            }
        </style>
        <script src="<?php echo $this->config->base_url() ?>/js/jquery-1.10.2.js"></script>
        <script src="//cdn.transifex.com/live.js"></script>
        <script src="<?php echo $this->config->base_url(); ?>js/jquery.noty.js"></script>
        <script>
            $( document ).ready(function(){
                $('.modal-trigger').leanModal();
                $(".dropdown-button").dropdown();
                $(document).on("click",".search_new",function(e){
                    e.preventDefault();
                    var term = $('.term').val();
                   
                    $.ajax({
                        url:'<?php echo $this->config->base_url() ?>user/search_web',
                        type:'get',
                        data:'term='+term,
                        success:function(result){
                            $('#response').html(result);
                            $(".dropdown-button").dropdown(); 
                            
                        }
                    });
                    
                });
                
                $(document).on("click",".action_head",function(e){
                    e.preventDefault();
                    var type = $(this).attr('title');
                    var u_id = $(this).parent().parent().attr('id');
                    var new_id = u_id.replace('dropdown','');
                    if(type == "unfnd"){
                        type = 'reject';
                    }
                    var j_id = $(this).attr('id');
                    $.ajax({
                        url:'<?php echo $this->config->base_url() ?>user/'+type,
                        type:'post',
                        data:'f_id='+j_id,
                        success:function(result){
                            $('.tr'+new_id).hide();
                            toast("Success: change done successfully",4000);
                        }
                    });
                
                });
            });
          
        </script>

    </head>
    <body>
        <div id="modal_add" class="modal">
            <div class="modal-content">
                <h4>Search Friends</h4>
                <div class="row">
                    <div class="input-field col s10">
                        <i  class="mdi-action-search prefix"></i>
                        <input id="icon_prefix" name="term" value="<?php echo!empty($email) ? $email : ''; ?>" type="text"  class="validate term">
                        <label for="icon_prefix">Search from here</label>
                    </div>
                    <div class="center-btn col s2">
                        <button type="submit" class="waves-effect waves-light btn blue lighten-1 search_new" id="vstoupit" style="margin-top:25px;">Search</button>
                    </div>
                </div>
                <div class="row" id="response">

                </div>
            </div>

        </div>
        <header >
            <ul style="margin-top:20px;" id="dropdown1" class="dropdown-content">
                <li ><a href="<?php echo $this->config->base_url() ?>user/logout">Logout</a></li>

            </ul>
            <nav class="nav-wrapper blue lighten-1">
                <div style="margin-right:20px">
                    <a style="margin-left:20px;" href="#!" class="brand-logo"><img style="width:50px;height:50px;margin-top:8px" src="<?php echo $this->config->base_url() ?>avatar/ic_launcher.png"/><h5 style="margin-top:-60px;margin-left:60px">Hellow</h5></a>
                    <ul class="right hide-on-med-and-down">
                        <li style="margin-right: 100px;"><a class="dropdown-button" href="#!" data-activates="dropdown1"><i class="small mdi-navigation-more-vert"></i></a></li>
                    </ul>
                </div>
            </nav>
