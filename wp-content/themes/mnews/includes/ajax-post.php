<?php

global $salong;
if(!array_key_exists('home_new_filter_cate', $salong))return;


function vb_mt_ajax_pager( $query = null, $paged = 1 ) {
    if (!$query)
        return;
    $paginate = paginate_links([
        'base'      => '%_%',
        'type'      => 'array',
        'total'     => $query->max_num_pages,
        'format'    => '#page=%#%',
        'current'   => max( 1, $paged ),
        'prev_text' => __('上一页','salong'),
        'next_text' => __('下一页','salong'),
    ]);
    if ($query->max_num_pages > 1) : ?>
    <div class="pagination" id="pagination">
        <?php foreach ( $paginate as $page ) :?>
        <?php echo $page; ?>
        <?php endforeach; ?>
    </div>
    <?php endif;
}



function vb_filter_posts_mt_sc($atts) {
    global $salong;
	$a = shortcode_atts( array(
		'tax'      => 'category',
		'terms'    => false,
		'active'   => false,
		'per_page' => '',
        'include'  => '',
		'pager'    => ''
	), $atts );

    $terms  = get_terms(array(
        'taxonomy' => $a['tax'],
        'include' => $a['include'],
    ));

	if (count($terms)) :
		ob_start(); ?>
			<div id="container-async" data-paged="<?= $a['per_page']; ?>" class="ajax_filter">
			<section class="home_title">
                <div class="mobile_scroll">
                    <ul class="nav-filter">
                        <li class="active">
                            <a href="#" data-filter="<?= $terms[0]->taxonomy; ?>" data-term="all-terms" data-page="1"><?php echo $salong['home_new_title']; ?></a>
                        </li>
                        <?php foreach ($terms as $term) : ?>
                            <li<?php if ($term->term_id == $a['active']) :?> class="active"<?php endif; ?>>
                                <a href="<?= get_term_link( $term, $term->taxonomy ); ?>" data-filter="<?= $term->taxonomy; ?>" data-term="<?= $term->slug; ?>" data-page="1">
                                    <?= $term->name; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
               </div>
            <div id="status" style="display: none;"></div>
        </section>

        <div id="ajax_content">
           <ul class="ajaxposts">
            <?php $paged=( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;$args=array( 'post_type'=> 'post','category__not_in'=> $salong[ 'exclude_new_cat'],'ignore_sticky_posts' => 1,'paged' => $paged );
            $wp_query = new WP_Query( $args );if ( $wp_query->have_posts() ) : while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
            <li class="ajaxpost">
                <?php get_template_part( 'content/list', 'post'); ?>
            </li>
            <?php endwhile;endif; ?>
            <?php posts_pagination(); ?>
            </ul>
        </div>
        <?php if ( $a['pager'] == 'infscr' ) : ?>
            <nav class="infscr-pager" id="pagination" style="display: none;">
                <a href="#page-2" class="page-numbers"><?php echo $salong['loadmore_text']; ?></a>
            </nav>
        <?php endif; ?>
			</div>
		
		<?php $result = ob_get_clean();
	endif;

	return $result;
}
add_shortcode( 'ajax_filter_posts_mt', 'vb_filter_posts_mt_sc');


function vb_filter_posts_mt() {
    global $salong;
    
	if( !isset( $_POST['nonce'] ) || !wp_verify_nonce( $_POST['nonce'], 'salong_ajax' ) )
		die('Permission denied');

	$all     = false;
	$terms   = $_POST['params']['terms'];
	$page    = intval($_POST['params']['page']);
	$qty     = intval($_POST['params']['qty']);
	$pager   = isset($_POST['pager']) ? $_POST['pager'] : 'pager';
	$tax_qry = [];
	$msg     = '';


    foreach ($terms as $tax => $slugs) :

        if (in_array('all-terms', $slugs)) {
            $all = true;
        }

        $tax_qry[] = [
            'taxonomy' => $tax,
            'field'    => 'slug',
            'terms'    => $slugs,
        ];
    endforeach;

	$args = [
		'paged'          => $page,
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $qty,
        'category__not_in'=> $salong[ 'exclude_new_cat']
	];

	if ($tax_qry && !$all) :
        $args['tax_query'] = $tax_qry;
		$args['category__not_in'] = '';
	endif;

	$qry = new WP_Query($args);
    ob_start();
    if ($qry->have_posts()) : ?>
    <script>
    $("#ajax_content img.thumb,#ajax_content img.avatar").lazyload({
        effect: "fadeIn",
        failure_limit: 10
    });
    </script>
    <ul>
        <?php while ($qry->have_posts()) : $qry->the_post(); ?>
        <li>
            <?php get_template_part( 'content/list', 'post'); ?>
        </li>

			<?php endwhile;
			if ( $pager == 'pager' )
				vb_mt_ajax_pager($qry,$page);


			foreach ($tax_qry as $tax) :
				$msg .= 'Displaying terms: ';

				foreach ($tax['terms'] as $trm) :
					$msg .= $trm . ', ';
				endforeach;

				$msg .= ' from taxonomy: ' . $tax['taxonomy'];
				$msg .= '. Found: ' . $qry->found_posts . ' posts';
			endforeach;

			$response = [
				'status'  => 200,
				'found'   => $qry->found_posts,
				'message' => $msg,
				'method'  => $pager,
				'next'    => $page + 1
			];

			
		else :

			$response = [
				'status'  => 201,
				'message' => 'No posts found',
				'next'    => 0
			]; ?>
    </ul>
    <?php endif;

	$response['content'] = ob_get_clean();

	die(json_encode($response));

}
add_action('wp_ajax_do_filter_posts_mt', 'vb_filter_posts_mt');
add_action('wp_ajax_nopriv_do_filter_posts_mt', 'vb_filter_posts_mt');
