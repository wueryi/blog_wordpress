<!doctype html>
<html <?php language_attributes(); ?>>
<?php global $salong;?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="author" content="jishuai">
    <meta name="viewport" content="width=device-width,height=device-height, initial-scale=1.0, user-scalable=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta http-equiv="Cache-Control" content="no-transform">
    <meta http-equiv="Cache-Control" content="no-siteapp">
    <title><?php echo salong_wptheme_title(); ?></title>
    <link href="<?php echo $salong['custom_favicon']['url']; ?>" rel="shortcut icon" />
    <?php if($salong[ 'switch_seo']){ get_template_part( 'includes/seo'); } ?>
    <?php wp_head(); ?>
</head>
<?php flush(); ?>

<body <?php body_class(); ?>>
    <header class="header">
        <section<?php if($salong[ 'switch_header_show_hide']){ ?> id="header_main"<?php } ?> class="header_main">
            <section class="wrapper">
                
                <a href="<?php echo get_home_url(); ?>" class="logo" title="<?php bloginfo('name'); ?>-<?php bloginfo('description'); ?>"><img src="<?php echo $salong['logo']['url']; ?>" alt="<?php bloginfo('name'); ?>-<?php bloginfo('description'); ?>"></a>
                
                <?php wp_nav_menu( array( 'container'=>'nav','container_class'=>'header_menu', 'theme_location' => 'header-menu','items_wrap'=>'<ul class="menu">%3$s</ul>' , 'fallback_cb'=>'Salong_header_nav_fallback') ); ?>
                
                <div class="header_menu header_btn">
                    <ul class="menu">
                        <?php echo salong_add_search_to_wp_menu(); ?>
                    </ul>
                </div>
                <button class="btn menu"><?php echo svg_menu().svg_close(); ?></button>
                <div class="circle menu"></div>
                <?php if(is_user_logged_in()){ ?>
                <button class="btn user"><?php echo svg_user().svg_close(); ?></button>
                <div class="circle user"></div>
                <?php }else{ ?>
                <a href="#login" class="btn login"><?php echo svg_user(); ?></a>
                <?php } ?>
            </section>
        </section>
    </header>