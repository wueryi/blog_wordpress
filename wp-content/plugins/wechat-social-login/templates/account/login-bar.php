<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$data = XH_Social_Temp_Helper::clear('atts','templates');
$redirect=$data['redirect'];
$channels =XH_Social::instance()->channel->get_social_channels(array('login'));    
?>
<div class="xh-social" style="clear:both;">
   <?php 
   
   $disable_wechat =XH_Social_Helper_Uri::is_app_client()&& !XH_Social_Helper_Uri::is_wechat_app();
   
    foreach ($channels as $channel){
        if($disable_wechat&&$channel->id==='add_ons_social_wechat'){
            continue;
        }
        ?>
        <a title="<?php echo esc_attr($channel->title)?>" href="<?php echo XH_Social::instance()->channel->get_authorization_redirect_uri($channel->id);?>" class="xh-social-item <?php echo $channel->svg?>" rel="noflow"></a>
        <?php 
    }?>
</div><?php 