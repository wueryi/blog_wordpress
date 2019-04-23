//评论星级
function clearSelected(){$(".star-rating a.zero-star").removeClass("zero-selected"),$(".star-rating a.one-star").removeClass("current-rating"),$(".star-rating a.two-stars").removeClass("current-rating"),$(".star-rating a.three-stars").removeClass("current-rating"),$(".star-rating a.four-stars").removeClass("current-rating"),$(".star-rating a.five-stars").removeClass("current-rating")}function rateClick(t){switch(clearSelected(),t){case 0:$(".star-rating a.zero-star").addClass("zero-selected"),$("#rate").val("0");break;case 1:$(".star-rating a.one-star").addClass("current-rating"),$("#rate").val("1");break;case 2:$(".star-rating a.two-stars").addClass("current-rating"),$("#rate").val("2");break;case 3:$(".star-rating a.three-stars").addClass("current-rating"),$("#rate").val("3");break;case 4:$(".star-rating a.four-stars").addClass("current-rating"),$("#rate").val("4");break;case 5:$(".star-rating a.five-stars").addClass("current-rating"),$("#rate").val("5");break}}$(".comment_stars a").click(function(){$(this).addClass("active"),$(this).siblings().removeClass("active"),$(".comment_stars").addClass("selected")}),
/*精简阅读*/
$(".post_simplify a.hide").click(function(){setTimeout(function(){$("body").addClass("simplify_hide")},800),setTimeout(function(){$(".content_left").addClass("simplify_on")},1200)}),$(".post_simplify a.goback").click(function(){setTimeout(function(){$("body").removeClass("simplify_hide")},800),setTimeout(function(){$(".content_left").removeClass("simplify_on")},200)}),
//回顶部
$(function(){
//当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失
$(window).scroll(function(){300<$(window).scrollTop()?$(".side_btn").addClass("active"):$(".side_btn").removeClass("active")}),
//当点击跳转链接后，回到页面顶部位置
$("#back-to-top,.post_simplify a.hide").click(function(){return $("body,html").animate({scrollTop:0},800),!1}),
//跳转到评论框
$("#back-to-comment").click(function(){$("html,body").animate({scrollTop:$("#respond").offset().top},800)}),
//跳转到评论框
$("span.comment a").click(function(){$("html,body").animate({scrollTop:$("#comments").offset().top},800)})}),
// TABS
$(document).ready(function(){$("#tabs li").bind("click",function(){var t=$(this).index(),e=$("#tabs_body > ul,#tabs_body > section");$(this).parent().children("li").attr("class","tab_nav"),//将所有选项置为未选中
$(this).attr("class","tab_nav_action"),//设置当前选中项为选中样式
e.hide(),//隐藏所有选中项内容
e.eq(t).show()})}),
//代码高亮
jQuery(document).ready(function(){0<jQuery("pre").length&&(jQuery("pre").addClass("prettyprint linenums"),prettyPrint())}),
//添加媒体库按钮到投稿中，并直接添加链接到输入框
jQuery(document).ready(function(e){var a,t;
//upload
e(".salong_field_area").on("click","a.salong_upload_button",function(t){t.preventDefault(),upload_btn=e(this),a||(a=wp.media({title:"插入图片",button:{text:"插入"},multiple:!1})).on("select",function(){var t=a.state().get("selection").first().toJSON();upload_btn.parent(".salong_file_button").find(".salong_field_upload").val(t.url).trigger("change")}),a.open()}),
/*图片预览*/
e(".salong_field_area").on("change focus blur onblur input",".salong_field_upload",function(){if(preview_div=e(this).parent().find(".salong_file_preview"),file_button=e(this).parent(".salong_file_button"),upload_button_name=e(this).parent().find(".salong_upload_button span"),file_uri=e(this).val(),file_uri){var t='<img src ="'+file_uri+'" />';preview_div.html("").append(t),file_button.addClass("active"),upload_button_name.text("更换图片")}else preview_div.html(""),file_button.removeClass("active"),upload_button_name.text("上传图片")})}),
/*站内信 checkbox 选择*/
$(document).ready(function(){
// 选择或取消选择所有筛选框
$(".checkall").change(function(){var t;$(this).is(":checked")?$(".checkbox").each(function(){$(this).prop("checked",!0),$(".checkall").prop("checked",!0)}):$(".checkbox").each(function(){$(this).prop("checked",!1),$(".checkall").prop("checked",!1)})}),
// 更改 checkall 复选框的状态
$(".checkbox").click(function(){$(".checkbox").length==$(".checkbox:checked").length?$(".checkall").prop("checked",!0):$(".checkall").removeAttr("checked")})}),
/*开关灯*/
$("a#light").click(function(){$(this).toggleClass("active"),$(".video_player,.bg.light").toggleClass("active"),$("#video_width").width($(".content").width())}),$(".bg.light").click(function(){$(".video_player,.bg.light").removeClass("active")}),
/*目录列表*/
$("a#catlist").click(function(){$(this).toggleClass("active"),$(".videocat_list").toggleClass("active")}),
/*购物车*/
$("a.cart_btn").click(function(){$(".ajax_cart").toggleClass("active"),$(".bg.cart").toggleClass("active")}),$(".bg.cart").click(function(){$(".ajax_cart,.bg.cart,.video_player").removeClass("active")}),
/*响应式*/
$(window).width()<1200&&(
/*移动导航标签*/
$("nav.header_menu").attr("class","mobile_header_menu"),$("div.header_menu").attr("class","mobile_header_user"),
/*点击弹出导航*/
$("button.menu").click(function(){$(".mobile_header_menu").toggleClass("active"),$(".circle.menu").toggleClass("active"),$(this).toggleClass("active"),$("button.user").toggleClass("hide")}),
/*弹出用户菜单*/
$("button.user").click(function(){$(".mobile_header_user").toggleClass("active"),$(".circle.user").toggleClass("active"),$(this).toggleClass("active"),$("button.menu").toggleClass("hide")}),
/*禁止浏览器滚动*/
$("button.user,button.menu").toggle(function(){$("body").css("overflow","hidden")},function(){$("body").attr("style","")}),
/*二级菜单前添加标签*/
jQuery(function(){$("nav .sub-menu").before('<span class="menu_btn"></span>'),
/*二级菜单显示*/
$("span.menu_btn").click(function(){$(this).toggleClass("active"),$(this).siblings(".sub-menu").toggleClass("active")})}));