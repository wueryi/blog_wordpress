<!--分享到微信 JDK-->
<?php
global $salong,$post;

$weixin_appid     = $salong['weixin_appid'];
$weixin_appsecret = $salong['weixin_appsecret'];
/*微信分享 JDK*/
$jssdk        = new JSSDK($weixin_appid, $weixin_appsecret);
$signPackage  = $jssdk->GetSignPackage();
?>

<script type="text/javascript" src="https://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    /*
     * 注意：
     * 1. 所有的JS接口只能在公众号绑定的域名下调用，公众号开发者需要先登录微信公众平台进入“公众号设置”的“功能设置”里填写“JS接口安全域名”。
     * 2. 如果发现在 Android 不能分享自定义内容，请到官网下载最新的包覆盖安装，Android 自定义分享接口需升级至 6.0.2.58 版本及以上。
     * 3. 常见问题及完整 JS-SDK 文档地址：http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html
     *
     * 开发中遇到问题详见文档“附录5-常见错误及解决办法”解决，如仍未能解决可通过以下渠道反馈：
     * 邮箱地址：weixin-open@qq.com
     * 邮件主题：【微信JS-SDK反馈】具体问题
     * 邮件内容说明：用简明的语言描述问题所在，并交代清楚遇到该问题的场景，可附上截屏图片，微信团队会尽快处理你的反馈。
     */
    wx.config({
        debug: false,
        appId: '<?php echo $signPackage["appId"];?>',
        timestamp: <?php echo $signPackage["timestamp"];?>,
        nonceStr: '<?php echo $signPackage["nonceStr"];?>',
        signature: '<?php echo $signPackage["signature"];?>',
        jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage','onMenuShareQQ','onMenuShareWeibo','onMenuShareQZone']
    });
    wx.ready(function() {
        //分享到朋友圈
        wx.onMenuShareTimeline({
            title: '<?php the_title(); ?>', // 分享标题
            link: '<?php the_permalink() ?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?php echo post_thumbnail_src(); ?>', // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
                var ajax_data = {
                    action: "wechat_share",
                    postid: <?php echo get_the_ID();?>
                }
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url();?>/admin-ajax.php', //你的admin-ajax.php地址
                    data: ajax_data,
                    dataType: 'json',
                    success: function(data) {

                    }
                });
            },
        });
        //分享给朋友
        wx.onMenuShareAppMessage({
            title: '<?php the_title(); ?>', // 分享标题
            desc: '<?php if (has_excerpt()) { ?><?php echo strip_tags(get_the_excerpt()); ?><?php } else{ echo strip_tags(wp_trim_words(get_the_content(),66)); } ?>', // 分享描述
            link: '<?php the_permalink() ?>', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
            imgUrl: '<?php echo post_thumbnail_src(); ?>', // 分享图标
            type: '', // 分享类型,music、video或link，不填默认为link
            dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
            success: function() {
                // 用户确认分享后执行的回调函数
                var ajax_data = {
                    action: "wechat_share",
                    postid: <?php echo get_the_ID();?>
                }
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url();?>/admin-ajax.php', //你的admin-ajax.php地址
                    data: ajax_data,
                    dataType: 'json',
                    success: function(data) {

                    }
                });
            },
        });
        //分享到QQ
        wx.onMenuShareQQ({
            title: '<?php the_title(); ?>', // 分享标题
            desc: '<?php if (has_excerpt()) { ?><?php echo strip_tags(get_the_excerpt()); ?><?php } else{ echo strip_tags(wp_trim_words(get_the_content(),66)); } ?>', // 分享描述
            link: '<?php the_permalink() ?>', // 分享链接
            imgUrl: '<?php echo post_thumbnail_src(); ?>', // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
                var ajax_data = {
                    action: "wechat_share",
                    postid: <?php echo get_the_ID();?>
                }
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url();?>/admin-ajax.php', //你的admin-ajax.php地址
                    data: ajax_data,
                    dataType: 'json',
                    success: function(data) {

                    }
                });
            },
        });
        //分享到腾讯微博
        wx.onMenuShareWeibo({
            title: '<?php the_title(); ?>', // 分享标题
            desc: '<?php if (has_excerpt()) { ?><?php echo strip_tags(get_the_excerpt()); ?><?php } else{ echo strip_tags(wp_trim_words(get_the_content(),66)); } ?>', // 分享描述
            link: '<?php the_permalink() ?>', // 分享链接
            imgUrl: '<?php echo post_thumbnail_src(); ?>', // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
                var ajax_data = {
                    action: "wechat_share",
                    postid: <?php echo get_the_ID();?>
                }
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url();?>/admin-ajax.php', //你的admin-ajax.php地址
                    data: ajax_data,
                    dataType: 'json',
                    success: function(data) {

                    }
                });
            },
        });
        //分享到QQ空间
        wx.onMenuShareQZone({
            title: '<?php the_title(); ?>', // 分享标题
            desc: '<?php if (has_excerpt()) { ?><?php echo strip_tags(get_the_excerpt()); ?><?php } else{ echo strip_tags(wp_trim_words(get_the_content(),66)); } ?>', // 分享描述
            link: '<?php the_permalink() ?>', // 分享链接
            imgUrl: '<?php echo post_thumbnail_src(); ?>', // 分享图标
            success: function() {
                // 用户确认分享后执行的回调函数
                var ajax_data = {
                    action: "wechat_share",
                    postid: <?php echo get_the_ID();?>
                }
                $.ajax({
                    type: "POST",
                    url: '<?php echo admin_url();?>/admin-ajax.php', //你的admin-ajax.php地址
                    data: ajax_data,
                    dataType: 'json',
                    success: function(data) {

                    }
                });
            },
        });
    });

</script>