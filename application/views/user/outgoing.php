<script>
    $(document).on("click",".action",function(e){
        e.preventDefault();
        var type = $(this).attr('title');
        if(type == "unfnd"){
            type = 'reject';
        }
        var f_id = $(this).attr('id');
        $.ajax({
            url:'<?php echo $this->config->base_url() ?>user/'+type,
            type:'post',
            data:'f_id='+f_id,
            success:function(result){
                if(type == "reject"){
                    $('.tr_'+f_id).hide();
                }      
                if(type == "block"){
                    $('.block_'+f_id).attr('title','unblock');
                    $('.block_'+f_id).text('unblock');
                }
                if(type == "unblock"){
                    $('.block_'+f_id).attr('title','block');
                    $('.block_'+f_id).text('block');
                }
                toast("Success: change done successfully",4000); 
            }
        });
    });
    
</script>
<main>
    <div id="modal1" style="height:530px;" class="modal modal-fixed-footer">
        <div  class="modal-content">
            <div class="card">
                <div class="card-image waves-effect waves-block waves-light">
                    <img style="height:300px" class="activator model_img" src="images/office.jpg">
                </div>
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4"><i class="mdi-navigation-more-vert right"></i></span>
                    <p class="mod_email"></p>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <form action="<?php echo $this->config->base_url() ?>user/friends_search" method="POST" class="col s12">
            <input type="hidden" value="outgoing" name="type"/>
            <div class="row">
                <div class="input-field col s5">
                    <i  class="mdi-communication-email prefix"></i>
                    <input id="icon_prefix" name="email" value="<?php echo!empty($email) ? $email : ''; ?>" type="email"  class="validate">
                    <label for="icon_prefix">e-mail</label>
                </div>
                <div class="input-field col s5">
                    <i class="mdi-action-account-circle prefix"></i>
                    <input id="icon_telephone" name="name" value="<?php echo!empty($name) ? $name : ''; ?>" type="text" class="validate">
                    <label for="icon_telephone">name</label>
                </div>
                <div class="center-btn col s2">
                    <button type="submit" class="waves-effect waves-light btn #42a5f5 blue lighten-1" id="vstoupit" style="margin-top:25px;">Search</button>
                </div>
            </div>
        </form>
    </div>

    <table class="striped" style="margin-left: 10px">
        <thead>
            <tr>
                <th data-field="id">Avatar</th>
                <th data-field="id">Email</th>
                <th data-field="name">Name</th>
                <th data-field="price">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($result)) {
                foreach ($result as $v) {
                    ?>
                    <tr class="tr_<?php echo $v['f_id'] ?>">
                        <td style="width:10%"> <img style="height:50px;width:50px" src="<?php echo $v['avatar'] ?>" alt="" class="circle"></td>
                        <td style="width:25%;"><?php echo $v['email'] ?></td>
                        <td style="width:20%"><?php echo $v['name'] ?></td>

                <ul id="dropdown<?php echo $v['f_id'] ?>" class="dropdown-content">
                    <li><a class="action" title="unfnd" id="<?php echo $v['f_id'] ?>" href="">Cancel</a></li>
                    <?php if ($v['reject'] == 0) { ?>
                        <li><a class="action block_<?php echo $v['f_id'] ?>" title="block" id="<?php echo $v['f_id'] ?>" href="">Block</a></li>
                    <?php } else { ?>
                        <li><a class="action unblock_<?php echo $v['f_id'] ?>" title="unblock" id="<?php echo $v['f_id'] ?>" href="">UnBlock</a></li>
                    <?php }
                    ?>
                </ul>
                <td class="trclick" style="width:15%"><a class="dropdown-button" href="#!" data-activates="dropdown<?php echo $v['f_id'] ?>"><i class="mdi-navigation-more-vert"></i></a></td>
                </tr>
                <?php
            }
        }
        ?>
        </tbody>
    </table>
    <div class="col s12" style="margin-bottom: 75px">
        <?php echo $links; ?>
    </div>
