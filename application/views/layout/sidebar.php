
<style>
    .label{ background: none repeat scroll 0 0 #42a5f5;
            border-radius: 4px;
            color: white;
            display: inline-block;
            font-size: 1em;
            line-height: 1;
            margin: 9px 0 0.6em;
            padding: 6px 10px;
    }
</style>
<div class="container"><a href="#" data-activates="nav-mobile" class="button-collapse top-nav"><i class="mdi-navigation-menu"></i></a></div>
<ul id="nav-mobile" class="side-nav fixed">

    <ul class="collapsible collapsible-accordion">
        <?php $avatar = $this->session->userdata('avatar'); ?>
        <?php $status = $this->session->userdata('user_status'); ?>
        <?php
        $j_id = $this->session->userdata('j_id');
        if (!empty($j_id)) {
            ?>
            <li style="height:280px" class="logo"><a class="brand-logo" href="#" id="logo-container"><img class="responsive-img circle"  src="<?php echo $avatar ?>"/></a><br/>
                <h6  style="margin-top: 80px;margin-right:170px;color:gray">Status</h6>
                <h6 class="left" style="margin-left:15px;"><?php echo $status; ?></h6>
            </li>
            <li class="bold"><a href="<?php echo $this->config->base_url() ?>user/friends" class="waves-effect waves-teal">Friends</a></li>
            <li class="bold"><a href="<?php echo $this->config->base_url() ?>user/incoming" class="waves-effect waves-teal">Incoming</a></li>
            <li class="bold"><a href="<?php echo $this->config->base_url() ?>user/outgoing" class="waves-effect waves-teal">Outgoing</a></li>
        <?php
        } else {
            $CI = &get_instance();
            $CI->load->model('nj_user');
            $user_cnt = $CI->nj_user->record_count();
            ?>
            <li class="bold"><a href="<?php echo $this->config->base_url() ?>user/all" class="waves-effect waves-teal">Users &nbsp;&nbsp;<label class="label right" style="background-color:#33691e"><?php echo $user_cnt   ?></label></a></li>
            <li class="bold"><a href="<?php echo $this->config->base_url() ?>admin/reset" class="waves-effect waves-teal">Reset Password</a></li>

<?php } ?>

    </ul>
</ul>
</header>
