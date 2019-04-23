<?php
/*
聚合专题小工具
*/

class Slongtopic extends WP_Widget {

/*  构造函数
/* ------------------------------------ */
	function __construct() {
		parent::__construct( false, __('Salong-专题聚合','salong'), array('description' => __('专题聚合小工具','salong'), 'classname' => 'widget_salong_topic') );;	
	}
	
/*  小工具
/* ------------------------------------ */
	public function widget($args, $instance) {
		extract( $args );
		$instance['title']?NULL:$instance['title']='';
		$title = apply_filters('widget_title',$instance['title']);
		$output = $before_widget."\n";
		if($title)
			$output .= $before_title.$title.$after_title;
		ob_start();
	
?>

	<?php
        if($instance['posts_orderby'] == 'salong_post_like_count') {
            $meta_key = 'salong_post_like_count';
            $orderby = 'meta_value_num';
        }else if($instance['posts_orderby'] == 'views') {
            $meta_key = 'views';
            $orderby = 'meta_value_num';
        } else {
            $meta_key = '';
            $orderby = $instance['posts_orderby'];
        }
        if($instance['posts_cat_id'] == 0) {
		$posts = new WP_Query( array(
			'showposts'				=> $instance['posts_num'],
			'cat'					=> $instance['posts_cat_id'],
			'ignore_sticky_posts'	=> true,
            'post_type'             => 'topic',
            'meta_key'              => $meta_key,
			'orderby'				=> $orderby,
			'order'					=> 'desc',
			'date_query' => array(
				array(
					'after' => $instance['posts_time'],
				),
			),
		) );
        } else {
		$posts = new WP_Query( array(
			'showposts'				=> $instance['posts_num'],
			'ignore_sticky_posts'	=> true,
            'post_type'             => 'topic',
            'meta_key'              => $meta_key,
			'orderby'				=> $orderby,
			'order'					=> 'desc',
            'tax_query'             => array(
                array(
                    'taxonomy' => 'tcat',
                    'field'    => 'id',
                    'terms'    => $instance['posts_cat_id'],
                )
            ),
			'date_query' => array(
				array(
					'after' => $instance['posts_time'],
				),
			),
		) );
        }
	?>
	<ul>
		<?php if ( $posts->have_posts() ) : while ( $posts->have_posts() ) : $posts->the_post();
        global $post,$salong;

        $topic_post_id = explode(',',get_post_meta( $post->ID, 'topic_post_id', 'true' ));
        foreach($topic_post_id as $post_count=>$post_id){
            $post_count++;
        }
        ?>
		<li>
            <article class="topic_popup">
                <a href="<?php the_permalink() ?>" class="imgeffect" title="<?php the_title(); ?>" <?php echo new_open_link(); ?>>
                    <?php post_thumbnail(); ?>
                    <h3><?php the_title(); ?></h3>
                </a>
                <span class="is_category"><?php the_terms( $post->ID, 'tcat','' );?></span>
                <span class="post_count"><?php echo sprintf(__('%s 篇','salong'),$post_count); ?></span>
            </article>
		</li>
		<?php endwhile; endif; ?>
		<?php wp_reset_query();?>
	</ul>

<?php
		$output .= ob_get_clean();
		$output .= $after_widget."\n";
		echo $output;
	}
	
/*  更新小工具
/* ------------------------------------ */
	public function update($new,$old) {
		$instance = $old;
		$instance['title'] = strip_tags($new['title']);
		$instance['posts_num'] = strip_tags($new['posts_num']);
		$instance['posts_cat_id'] = strip_tags($new['posts_cat_id']);
		$instance['posts_orderby'] = strip_tags($new['posts_orderby']);
		$instance['posts_time'] = strip_tags($new['posts_time']);
		return $instance;
	}

/*  小工具表单
/* ------------------------------------ */
	public function form($instance) {
		// 默认设置
		$defaults = array(
			'title' 			=> __('专题聚合','salong'),
			'posts_num' 		=> '4',
			'posts_cat_id' 		=> '0',
			'posts_orderby' 	=> 'date',
			'posts_time' 		=> '0',
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
?>

	<style>
	.widget .widget-inside .postform { width: 100%; }
	</style>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('标题：','salong'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance["title"]); ?>" />
		</p>

		<p>
			<label style="width: 100%; display: inline-block;" for="<?php echo $this->get_field_id("posts_num"); ?>"><?php _e('文章数量：','salong'); ?></label>
			<input style="width:100%;" id="<?php echo $this->get_field_id("posts_num"); ?>" name="<?php echo $this->get_field_name("posts_num"); ?>" type="number" value="<?php echo $instance["posts_num"]; ?>" size='3' />
		</p>
		<p>
			<label style="width: 100%; display: inline-block;" for="<?php echo $this->get_field_id("posts_cat_id"); ?>"><?php _e('分类：','salong'); ?></label>
			<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("posts_cat_id"), 'taxonomy' => 'tcat', 'selected' => $instance["posts_cat_id"], 'show_option_all' => __('全部','salong'), 'show_count' => true ) ); ?>
		</p>
		<p>
			<label style="width: 100%; display: inline-block;" for="<?php echo $this->get_field_id("posts_orderby"); ?>"><?php _e('排序：','salong'); ?></label>
			<select style="width: 100%;" id="<?php echo $this->get_field_id("posts_orderby"); ?>" name="<?php echo $this->get_field_name("posts_orderby"); ?>">
			  <option value="date"<?php selected( $instance["posts_orderby"], "date" ); ?>><?php _e('日期','salong'); ?></option>
			  <option value="title"<?php selected( $instance["posts_orderby"], "title" ); ?>><?php _e('标题','salong'); ?></option>
			  <option value="views"<?php selected( $instance["posts_orderby"], "views" ); ?>><?php _e('浏览','salong'); ?></option>
			  <option value="comment_count"<?php selected( $instance["posts_orderby"], "comment_count" ); ?>><?php _e('热评','salong'); ?></option>
			  <option value="salong_post_like_count"<?php selected( $instance["posts_orderby"], "salong_post_like_count" ); ?>><?php _e('点赞','salong'); ?></option>
			  <option value="rand"<?php selected( $instance["posts_orderby"], "rand" ); ?>><?php _e('随机','salong'); ?></option>
			</select>	
		</p>
		<p>
			<label style="width: 100%; display: inline-block;" for="<?php echo $this->get_field_id("posts_time"); ?>"><?php _e('时间：','salong'); ?></label>
			<select style="width: 100%;" id="<?php echo $this->get_field_id("posts_time"); ?>" name="<?php echo $this->get_field_name("posts_time"); ?>">
			  <option value="0"<?php selected( $instance["posts_time"], "0" ); ?>><?php _e('全部','salong'); ?></option>
			  <option value="1 year ago"<?php selected( $instance["posts_time"], "1 year ago" ); ?>><?php _e('一年','salong'); ?></option>
			  <option value="1 month ago"<?php selected( $instance["posts_time"], "1 month ago" ); ?>><?php _e('一月','salong'); ?></option>
			  <option value="1 week ago"<?php selected( $instance["posts_time"], "1 week ago" ); ?>><?php _e('一周','salong'); ?></option>
			  <option value="1 day ago"<?php selected( $instance["posts_time"], "1 day ago" ); ?>><?php _e('一天','salong'); ?></option>
			</select>	
		</p>

<?php

}

}

/*  注册小工具
/* ------------------------------------ */
if ( ! function_exists( 'salong_register_widget_topic' ) ) {

	function salong_register_widget_topic() { 
		register_widget( 'Slongtopic' );
	}
	
}
add_action( 'widgets_init', 'salong_register_widget_topic' );
