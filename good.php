<?php
/**
 * Plugin Name: Good Listener
 * Plugin URI: https://dtlytics.com/2020/11/does-not-use-passive-listeners-solved/
 * Description: Unregisters a core script in WordPress (js/comment-reply.min.js) and only calling it if required
 * Version: 1.0
 * Author: Datalytics HQ
 * Author URI: https://dtlytics.com
 * Text Domain: good-listener
 * License: GPLv2 or later
 */

/*

Copyright (C) 2020, Yair Weil <yair@dtlytics.com>

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function gl_dereg_script_comment_reply(){
	wp_deregister_script( 'comment-reply' );
}
add_action('init','gl_dereg_script_comment_reply');

function gl_js_footer() {
    ?>
<script type="text/javascript">
//Function checks if a given script is already loaded
function isScriptLoaded(src){
    return document.querySelector('script[src="' + src + '"]') ? true : false;
}

//When a reply link is clicked, check if reply-script is loaded. If not, load it and emulate the click
jQuery(document).ready(function($){
$('.comment-reply-link').click(function(){ 
    if(!(isScriptLoaded("/wp-includes/js/comment-reply.min.js"))){
        var script = document.createElement('script');
        script.src = "/wp-includes/js/comment-reply.min.js"; 
    script.onload = emRepClick($(this).attr('data-commentid'));        
        document.head.appendChild(script);
    } 
});
});
//Function waits 50 ms before it emulates a click on the relevant reply link now that the reply script is loaded
function emRepClick(comId) {
sleep(50).then(() => {
document.querySelectorAll('[data-commentid="'+comId+'"]')[0].dispatchEvent(new Event('click'));
});
}
//Function does nothing, for a given amount of time
function sleep (time) {
  return new Promise((resolve) => setTimeout(resolve, time));
}
</script>
    <?php
}
add_action('wp_footer', 'gl_js_footer');
