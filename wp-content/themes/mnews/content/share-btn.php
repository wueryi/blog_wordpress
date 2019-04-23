<?php
global $salong,$post;
$metas = $salong[ 'share_metas'];
if(!$metas)return;
$cover = get_post_meta( $post->ID, 'share_cover', true );
if (has_excerpt()) {
    $excerpt = strip_tags(get_the_excerpt());
} else{
    $excerpt = strip_tags(wp_trim_words(get_the_content(),66));
}
?>
    <div class="<?php echo (get_post_type()=='kx' && in_the_loop()) ? 'kx_share' : 'post_share'; ?> share">
        <span><?php _e('分享：','salong'); ?></span>
        <?php if(!wp_is_mobile() && in_array( 'wechat', $metas)){ ?>
            <a href="#qr" title="<?php _e('扫描分享到社交APP','salong'); ?>" class="weixin"><?php echo svg_wechat(); ?>
            <div class="qrpopup"><div id="qrcode<?php echo $post->ID; ?>" class="qr"></div><span><?php _e('扫描分享到社交APP','salong'); ?></span></div>
            </a>
            <script type="text/javascript">$("#qrcode<?php echo $post->ID; ?>").qrcode({width:120,height:120,text:"<?php the_permalink(); ?>"});</script>
        <?php } if(in_array( 'facebook', $metas)){ ?>
            <a target="_blank" onClick='window.open("https://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>&t=<?php echo get_the_title(); ?>_<?php echo bloginfo('name'); ?>&pic=<?php echo post_thumbnail_src(); ?>")' title="<?php _e('分享到 FaceBook','salong'); ?>" class="facebook"><?php echo svg_facebook(); ?></a>
        <?php } if(in_array( 'twitter', $metas)){ ?>
            <a target="_blank" onClick='window.open("https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>_<?php echo bloginfo('name'); ?><?php echo get_permalink(); ?>&pic=<?php echo post_thumbnail_src(); ?>")' title="<?php _e('分享到 Twitter','salong'); ?>" class="twitter"><?php echo svg_twitter(); ?></a>
        <?php } if(in_array( 'weibo', $metas)){ ?>
            <a target="_blank" onClick='window.open("http://service.weibo.com/share/share.php?url=<?php echo get_permalink(); ?>&amp;title=【<?php echo get_the_title(); ?>】<?php echo $excerpt; ?>@<?php echo bloginfo('name'); ?>&amp;appkey=<?php echo $salong['weibo_key']; ?>&amp;pic=<?php echo post_thumbnail_src(); ?>&amp;searchPic=true")' title="<?php _e('分享到新浪微博','salong'); ?>" class="weibo"><?php echo svg_sina(); ?></a>
        <?php } if(in_array( 'qq', $metas)){ ?>
            <a target="_blank" onClick='window.open("http://connect.qq.com/widget/shareqq/index.html?url=<?php echo get_permalink(); ?>&title=<?php echo get_the_title(); ?>&pics=<?php echo post_thumbnail_src(); ?>&summary=<?php echo $excerpt; ?>&site=<?php echo bloginfo('name'); ?>")' title="<?php _e('分享到QQ好友','salong'); ?>" class="qq"><?php echo svg_qq(); ?></a>
        <?php } if(in_array( 'qqzone', $metas)){ ?>
            <a target="_blank" onClick='window.open("https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php echo get_permalink(); ?>&title=<?php echo get_the_title(); ?>&desc=<?php echo $excerpt; ?>&summary=&site=<?php echo get_home_url(); ?>")' title="<?php _e('分享到QQ空间','salong'); ?>" class="qqzone"><?php echo svg_qqzone(); ?></a>
        <?php } if(in_array( 'cover', $metas) && (!in_the_loop() || is_singular( 'product' ) ) ){ ?>
            <div class="post_cover">
                <a class="share_cover_btn" data-module="coverpopup" data-selector="#share_cover" href="javascript:;"><?php echo svg_cover(); ?><span><?php if($cover){ echo __('分享封面','salong'); }else{ echo __('生成封面','salong'); } ?></span></a>
            </div>
        <?php } if(in_array( 'simplify', $metas) && is_singular( 'post' ) && !wp_is_mobile() && !in_the_loop()){ ?>
            <div class="post_simplify">
                <a href="#read" class="hide" rel="nofollow">
                    <?php echo svg_post(); ?>
                    <span><?php _e('精简阅读','salong'); ?></span>
                </a>
                <a href="#read" class="goback"><?php echo svg_goback(); ?></a>
            </div>
        <?php } ?>
    </div>