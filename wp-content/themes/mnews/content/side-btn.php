<?php global $salong,$post;
$feedback_page = $salong['feedback_page'];
$question_page = $salong['question_page'];
?>
<?php if ($salong[ 'side_metas'] !=0 ) { ?>
<section class="side_btn">
    <?php if (in_array( 'feedback', $salong[ 'side_metas']) && $feedback_page) { ?>
    <!--意见反馈-->
    <a class="btn" href="<?php echo get_page_link($feedback_page); ?>" rel="external nofollow"<?php echo new_open_link(); ?>><?php echo svg_feedback(); ?><span><?php _e( '意见反馈', 'salong' ); ?></span></a>
    <?php } if (in_array( 'question', $salong[ 'side_metas']) && $question_page) { ?>
    <!--提交问题-->
    <a class="btn" href="<?php echo get_page_link($question_page); ?>" rel="external nofollow"<?php echo new_open_link(); ?>><?php echo svg_question(); ?><span><?php _e( '提交问题', 'salong' ); ?></span></a>
    <?php } if (in_array( 'qq', $salong[ 'side_metas'])) { ?>
    <a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $salong['qq_num']; ?>&site=<?php echo get_bloginfo('name'); ?>&menu=yes" class="btn" rel="external nofollow" target="_blank"><?php echo svg_qq_line(); ?><span><?php _e( '在线咨询', 'salong' ); ?></span></a>
    <?php } if (in_array( 'gb2big5', $salong[ 'side_metas'])) { ?>
    <!--简繁切换-->
    <a name="gb2big5" id="gb2big5" class="btn gb2big5" rel="external nofollow"><?php _e( '简', 'salong'); ?><span><?php _e('简繁切换','salong'); ?></span></a>
    <?php } if (in_array( 'comment', $salong[ 'side_metas']) && 'open'==$post->comment_status && is_singular()) { ?>
    <!--去评论-->
    <a class="btn" id="back-to-comment" href="#respond" rel="external nofollow">
        <?php echo svg_comment(); ?>
        <span><?php _e('返回评论','salong'); ?></span>
    </a>
    <?php } if (in_array( 'top', $salong[ 'side_metas'])) { ?>
    <!--回顶部-->
    <a class="btn top" id="back-to-top" href="#top" title="<?php _e( '返回顶部', 'salong' ); ?>" rel="external nofollow">
        <?php echo svg_more(); ?>
        <span><?php _e('返回顶部','salong'); ?></span>
    </a>
    <?php } ?>
</section>
<?php } ?>
