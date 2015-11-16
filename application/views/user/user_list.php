<script>
    $(document).on("click",".check",function(){
         toast("You can not change status in demo version",4000);
        //        var ida = $(this).attr('id'); 
        //        if($(this).prop("checked") == true){
        //            $.ajax({
        //                url:'<?php //echo $this->config->base_url() ?>user/update/true',
        //                type:'post',
        //                data:'j_id='+ida,
        //                success:function(result){
        //                     
        //                }
        //            });
        //        }
        //        else if($(this).prop("checked") == false){
        //            $.ajax({
        //                url:'<?php //echo $this->config->base_url() ?>user/update/false',
        //                type:'post',
        //                data:'j_id='+ida,
        //                success:function(result){
        //                       
        //                }
        //            });
        //        }
    }); 
    $(document).on("click",".trclick",function(){
       
        var img = $(this).parent().find('td:not(:empty):first').children().attr('src');
        var email = $(this).parent().find('td:nth-child(2)').text();
        var name =$(this).parent().find('td:nth-child(3)').text();
        var status = $(this).parent().find('td:nth-child(5)').text();
     
        $('#modal1').openModal();
        $('.model_img').attr("src", img);
        $('.card-title').text(name);
        $('.mod_email').text(email);
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
        <form action="<?php echo $this->config->base_url() ?>user/search" method="POST" class="col s12">
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
                    <button type="submit" class="waves-effect waves-light btn blue lighten-1" id="vstoupit" style="margin-top:25px;">Search</button>
                </div>
            </div>
        </form>
    </div>

    <table class="striped" style="margin-left: 10px">
        <thead>
            <tr>
                <th data-field="id" style="width:10%">Avatar</th>
                <th data-field="id" style="width:25%">Email</th>
                <th data-field="name" style="width:15%">Name</th>
                <th data-field="name" style="width:15%">Is active</th>
                <th data-field="price" style="width:15%">status</th>
                <th data-field="price" style="width:15%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($result)) {
                foreach ($result as $v) {
                    ?>
                    <tr>
                        <td style="width:10%"> <img style="height:50px;width:50px" src="<?php echo $v['avatar'] ?>" alt="" class="circle"></td>
                        <td style="width:25%;"><?php echo $v['email'] ?></td>
                        <td style="width:15%"><?php echo $v['name'] ?></td>
                        <td style="width:15%"><?php echo $v['is_active'] == 0 ? '<label class="label" style="background-color:red;">Email not confirm</label>' : '<label class="label" style="background-color:green;">Email confirm</label>' ?></td>
                        <td  style="width:15%">
                            <div class="switch">
                                <label>
                                    Off
                                    <input id="<?php echo $v['j_id'] ?>" class="check" <?php echo $v['status'] == 1 ? 'checked' : '' ?> type="checkbox">
                                    <span class="lever"></span>
                                    On
                                </label>
                            </div>
                            <?php ?>
                        </td>
                        <td class="trclick" style="width:15%"><i class="small mdi-navigation-more-vert"></i></td>
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