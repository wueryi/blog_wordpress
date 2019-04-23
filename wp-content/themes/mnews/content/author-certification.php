<?php
/*认证作者表单*/
global $salong,$wp_query,$current_user;
$current_id       = $current_user->ID;//当前用户 ID
$current_name     = $current_user->display_name;
$description      = $current_user->description;
$salong_phone     = $current_user->salong_phone;
$salong_company   = $current_user->salong_company;
$salong_position  = $current_user->salong_position;
$salong_certified = $current_user->salong_certified;

//~ 个人资料
if( ( isset($_POST['update']) && wp_verify_nonce( trim($_POST['_wpnonce']), 'check-nonce' ) ) && !$salong_certified ) {
    $message = '<span class="warningbox">'.__('没有发生变化，或者<a href="">'.__('点击刷新','salong').'</a>','salong').'</span>';
    $update = sanitize_text_field($_POST['update']);
    if ( $_POST['are_you_human'] != get_bloginfo( 'name' ) ) {
        $message = '<span class="warningbox">'.sprintf(__('本站名称输入错误，正确名称为：%s','salong'),get_option('blogname')).'</span>';
    }else if($update=='info'){
        $update_user_id = wp_update_user( array(
            'ID'              => $current_id, 
            'display_name'    => sanitize_text_field($_POST['display_name']),
            'salong_phone'    => $_POST['salong_phone'],
            'description'     => $_POST['description'],
            'salong_company'  => $_POST['salong_company'],
            'salong_position' => $_POST['salong_position'],
         ) );
        if ( !is_wp_error( $update_user_id ) ){
            $message = '<span class="successbox">'.__('申请认证作者邮件发送成功，请耐心等待，或者<a href="">'.__('点击刷新','salong').'</a>。','salong').'</span>';
            /*添加一个已经申请的字段*/
            update_user_meta($current_id, 'salong_certified', 1);
            /*邮件内容*/http://localhost/mnews/wp-admin/user-edit.php?user_id=2
            /*发送私信*/
            $message_url = '<a href="'.get_author_posts_url(1).'?tab=message&page=salong_send&recipient='.$current_id.'">'.__('发送私信','salong').'</a>';
            $edit_user_url = '<a href="'.admin_url('user-edit.php').'?user_id='.$current_id.'">'.__('更改用户角色','salong').'</a>';
            $email_content = 
                "<br />".__('姓名：','salong').$_POST['display_name'].
                "<br />".__('电话：','salong').$_POST['salong_phone'].
                "<br />".__('介绍：','salong').$_POST['description'].
                "<br />".__('公司：','salong').$_POST['salong_company'].
                "<br />".__('操作：','salong').$_POST['salong_position'].
                "<br />".__('操作：','salong').__('认证通过','salong').'，'.$edit_user_url.'；'.__('暂不能通过','salong').'，'.$message_url.__('告知用户','salong');
            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail(get_option('admin_email'),get_option('blogname').__('认证作者申请','salong'),$email_content,$headers );
        }else{
            $message = '<span class="warningbox">'.__('申请认证作者失败，请重试，或者<a href="">'.__('点击刷新','salong').'</a>。','salong').'</span>';
        }
    }
}

?>
<a id="certification" class="overlay" rel="external nofollow" href="#m"></a>
<article class="certification popup">
    <section class="popup_main form_secton">
        <?php if(!$salong_certified){ ?>
        <h3>
            <?php _e('申请认证作者','salong');?>
        </h3>
        <?php if($message) echo $message; ?>
        <form class="author_form" role="form" method="POST" action="">
            <input type="hidden" name="update" value="info">
            <input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce( 'check-nonce' );?>">
            <p>
                <label for="display_name"><?php _e('姓名','salong');?></label>
                <input type="text" id="display_name" name="display_name" value="<?php echo $current_name;?>" required>
            </p>
            <p>
                <label for="salong_phone"><?php _e('手机','salong');?></label>
                <input type="text" id="salong_phone" name="salong_phone" value="<?php echo $salong_phone;?>" placeholder="<?php _e('请输入手机号码。','salong');?>" required>
            </p>
            <p>
                <label for="description"><?php _e('介绍','salong');?></label>
                <textarea rows="3" name="description" id="description" placeholder="<?php _e('请输入介绍。','salong');?>" required><?php echo $description;?></textarea>
            </p>
            <p>
                <label for="salong_company"><?php _e('公司','salong');?></label>
                <input type="text" id="salong_company" name="salong_company" value="<?php echo $salong_company;?>" placeholder="<?php _e('请输入公司名称。','salong');?>" required>
            </p>
            <p>
                <label for="salong_position"><?php _e('职位','salong');?></label>
                <input type="text" id="salong_position" name="salong_position" value="<?php echo $salong_position;?>" placeholder="<?php _e('请输入公司名称。','salong');?>" required>
            </p>
            <p><label for="are_you_human"><?php _e('本站名称','salong'); ?></label><input id="are_you_human" class="input" type="text" value="" name="are_you_human" placeholder="<?php echo sprintf(__('请输入：%s','salong'),get_option('blogname')); ?>" required /></p>
            <p>
                <input type="submit" value="提交申请" class="submit" />
            </p>
        </form>
        <?php }else{ ?>
        <span class="infobox"><?php _e('您已申请，请耐心等待审核结果。','salong');?></span>
        <?php } ?>
        <a class="close" rel="external nofollow" href="#m"><?php echo svg_close(); ?></a>
    </section>
</article>
