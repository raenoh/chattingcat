
<ul style="height:300px;overflow:auto" class="collection">
    <?php
    if (!empty($result)) {
        foreach ($result as $v) {
            ?>
    <li style="height: auto" class="collection-item avatar tr<?php echo !empty($v['user_id'])?$v['user_id']:'' ?>">
                <img src="<?php echo !empty($v['avatar'])?$v['avatar']:'' ?>" alt="" class="circle">
                <span class="title"><?php echo !empty($v['name'])?$v['name']:'' ?></span>
                <p><?php echo !empty($v['email'])?$v['email']:'' ?> <br>

                <div style="margin-left:200px;margin-top:-30px;"><?php
        if ($v['is_block'] == "true") {
            echo '<label class="label" style="background-color:red;">blocked</label>';
        } elseif ($v['is_friend_in'] == "true" && $v['is_friend_out'] == "false" && $v['is_friend'] == "false") {
            echo ' &nbsp;&nbsp;<label class="label">incoming request</label>';
        } elseif ($v['is_friend_in'] == "false" && $v['is_friend_out'] == "true") {
            echo '&nbsp;&nbsp;<label class="label" style="">outgoing request</label>';
        } elseif ($v['is_friend'] == "true") {
            echo '&nbsp;&nbsp;<label class="label" style="background-color:green">you are friend</label>';
        } else {
            echo '&nbsp;&nbsp;<label class="label" style="background-color:green;opacity:0">new</label>';
        }
            ?></div>     
                </p>
                <ul id="dropdown<?php echo !empty($v['user_id'])?$v['user_id']:'' ?>" class="dropdown-content">
                    <?php if ($v['is_friend'] == "true") { ?>
                        <li><a class = "action_head" title = "unfnd" id = "<?php echo $v['f_id'] ?>" href = "">unfriend</a></li>
                        <?php
                        if ($v['is_block'] == "true") {
                            ?>
                            <li><a class = "action_head" title = "unblock" id = "<?php echo $v['f_id'] ?>" href = "">unblock</a></li>
                        <?php } else {
                            ?>
                            <li><a class = "action_head" title = "block" id = "<?php echo $v['f_id'] ?>" href = "">block</a></li>
                            <?php
                        }
                    } elseif ($v['is_friend_in'] == "false" && $v['is_friend_out'] == "false") {
                        ?>
                        <li><a class = "action_head" title = "add" id = "<?php echo $v['j_id'] ?>" href = "">send</a></li>

                        <?php if ($v['is_block'] == "true") {
                            ?>
                            <li><a class = "action_head" title = "unblock" id = "<?php echo $v['j_id'] ?>" href = "">unblock</a></li>
                        <?php } else {
                            ?>
                            <li><a class = "action_head" title = "addblock" id = "<?php echo $v['j_id'] ?>" href = "">block</a></li>
                            <?php
                        }
                    } elseif ($v['is_friend_in'] == "true" && $v['is_friend_out'] == "false") {
                        ?>
                        <li><a class = "action_head" title = "accept" id = "<?php echo $v['f_id'] ?>" href = "">accept</a></li>
                        <li><a class = "action_head" title = "reject" id = "<?php echo $v['f_id'] ?>" href = "">reject</a></li>
                        <?php if ($v['is_block'] == "true") {
                            ?>
                            <li><a class = "action_head" title = "unblock" id = "<?php echo $v['f_id'] ?>" href = "">unblock</a></li>
                        <?php } else {
                            ?>
                            <li><a class = "action_head" title = "block" id = "<?php echo $v['f_id'] ?>" href = "">block</a></li>
                            <?php
                        }
                    } elseif ($v['is_friend_in'] == "false" && $v['is_friend_out'] == "true") {
                        ?>
                        <li><a class = "action_head" title = "reject" id = "<?php echo $v['f_id'] ?>" href = "">cancel</a></li>
                        <?php
                        if ($v['is_block'] == "true") {
                            ?>
                            <li><a class = "action_head" title = "unblock" id = "<?php echo $v['f_id'] ?>" href = "">unblock</a></li>
                        <?php } else {
                            ?>
                            <li><a class = "action_head" title = "block" id = "<?php echo $v['f_id'] ?>" href = "">block</a></li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <a class="dropdown-button right" style="margin-top: -50px" href="#!" data-activates="dropdown<?php echo $v['user_id'] ?>"><i class="small mdi-navigation-more-vert"></i></a>
            </li>

            <?php
        }
    } else {
        echo 'result not found';
    }
    ?>

</ul>

