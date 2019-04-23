<?php
global $salong,$wp_query,$wpdb;
$curauth    = $wp_query->get_queried_object();//当前用户
$curauth_id = $curauth->ID;//当前用户 ID
$get_tab    = $_GET['tab'];//获取连接中 tab 后面的参数

$message_recipient = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'pm WHERE `recipient` = "' . $curauth_id . '" AND `deleted` != 2' );
$message_sender = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $wpdb->prefix . 'pm WHERE `sender` = "' . $curauth_id . '" AND `deleted` != 1' );

?>

<section class="author_subtabs">
    <ul class="tabs">
        <li<?php if($get_tab=='message' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo add_query_arg('tab', 'message', get_author_posts_url($curauth_id)); ?>">
                <h5>
                    <?php _e('发送私信','salong'); ?>
                </h5>
            </a>
        </li>
        <li<?php if( $get_tab=='message-inbox' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo add_query_arg('tab', 'message-inbox', get_author_posts_url($curauth_id)); ?>">
                <h5>
                    <?php _e('收件箱','salong'); ?>
                </h5>
                <?php if($message_recipient){ ?>
                <span class="count">（<?php echo $message_recipient; ?>）</span>
                <?php } ?>
            </a>
        </li>
        <li<?php if( $get_tab=='message-outbox' ){ echo ' class="current"'; } ?>>
            <a href="<?php echo add_query_arg('tab', 'message-outbox', get_author_posts_url($curauth_id)); ?>">
                <h5>
                    <?php _e('发件箱','salong'); ?>
                </h5>
                <?php if($message_sender){ ?>
                <span class="count">（<?php echo $message_sender; ?>）</span>
                <?php } ?>
            </a>
        </li>
    </ul>
    <section class="salong_message">
        <?php if($get_tab == 'message'){
            salong_send();
        }else if($get_tab == 'message-inbox'){
            salong_inbox();
        }else if($get_tab == 'message-outbox'){
            salong_outbox();
        } ?>
    </section>
</section>
