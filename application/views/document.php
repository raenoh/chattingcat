<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <div>
            <h3>1) unzip the Hellow_Admin_Panel Source folder:-</h3>
            <span class="code">find Hellow_Admin_Panel.zip and unzip into your hosting panel.</div>
            <img src="<?php echo $this->config->base_url() ?>document_img/unzip_hellowv1.png"/>
            <br/>
            <br/>
            <h3>2) Import Sql file to database:-</h3>
            <div> Create Database by importing database file. you can find database file under DATABASE folder in source package.</div>
            <img src="<?php echo $this->config->base_url() ?>document_img/sql_import.png"/>
            <br/>
            <br/>
            <h3>3) Setting up your Admin Panel</h3>
            <div> Here , Set the Root directory of our admin panel [Base Url where our admin panel is located]</div>
            <img style="width:1200px;height:600px" src="<?php echo $this->config->base_url() ?>document_img/config_hellowv1.png"/>
            <br/>
            <br/>

            <h3>4) Connect your database with Admin panel</h3>
            <div> Apply the appropriate database settings. as shown below.</div>
            <img style="width:1200px;height:600px" src="<?php echo $this->config->base_url() ?>document_img/database_hellowv1.png"/>
        </div>
    </body>
</html>
