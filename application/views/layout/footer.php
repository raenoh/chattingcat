<?php
$j_id = $this->session->userdata('j_id');
if (!empty($j_id)) {
    ?>
    <a style="margin-right:20px;margin-bottom:-10px" href="#modal_add" class="btn-floating modal-trigger btn-large waves-effect waves-light right red"><i class="mdi-content-add"></i></a>
<?php } ?>
</main>
<footer class="page-footer #42a5f5 blue lighten-1" >
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">Hellow! Chat V1.0 by !CanStudioZ.</h5>
                <p class="grey-text text-lighten-4">Hellow is a real time chat messaging app using XMPP standards with powerfull admin backend. also an Social network where user can contact each other. we are happy to work with you. any queries? contact us on skype: icanstudioz.</p>
            </div>
            <div class="col l4 offset-l2 s12">
               
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            © 2014 !CanStudioZ.
            <a class="grey-text text-lighten-4 right" href="http://www.icanstudioz.com">www.icanstudioz.com</a>
            Theme powered by http://materializecss.com/
        </div>
    </div>
</footer>
<!--  Scripts-->
<!--<script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script>if (!window.jQuery) { document.write('<script src="bin/jquery-2.1.1.min.js"><\/script>'); }
</script>-->
<script src="<?php echo $this->config->base_url() ?>js/jquery.timeago.min.js"></script>  
<script src="<?php echo $this->config->base_url() ?>js/prism.js"></script>
<script src="<?php echo $this->config->base_url() ?>js/materialize.js"></script>
<script src="<?php echo $this->config->base_url() ?>js/init.js"></script>

</body>
</html>