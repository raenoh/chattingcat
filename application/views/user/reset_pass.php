<script>
    $( document ).ready(function() {      
        var emsg = '<?php echo $this->session->userdata('emsg'); ?>';
        if(emsg != ""){
            toast(emsg,4000);
<?php echo $this->session->unset_userdata('emsg'); ?>
        };
        var msg = '<?php echo $this->session->userdata('msg'); ?>';
        if(msg != ""){
            toast(msg,4000);
<?php echo $this->session->unset_userdata('msg'); ?>
        }
    });
</script>
<div class="row">
    <form action="<?php echo $this->config->base_url() ?>admin/reset" method="post" class="col s12">
        <div class="row">
            <div class="input-field col s12">
                <i class="mdi-action-account-circle prefix"></i>
                <input id="username" name="username" type="text" class="validate" required="true">
                <label for="icon_telephone">Username</label>
            </div>
            <div class="input-field col s12">
                <i class="mdi-editor-mode-edit prefix"></i>
                <input id="o_password" name="o_password" type="password" class="validate" required="true">
                <label for="icon_prefix">Old Password</label>
            </div>

            <div class="input-field col s12">
                <i class="mdi-editor-mode-edit prefix"></i>
                <input id="n_password" name="n_password"  type="password" class="validate" required="true">
                <label for="icon_telephone">New Password</label>
            </div>
            <div class="center-btn">
                <button type="submit" class="waves-effect waves-light btn" id="vstoupit" style="background-color: #5677fc">Reset</button>

            </div>
        </div>
    </form>
</div>
