<?php
namespace WpfpInterface;

// if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// This file is for interfacing with the Wordpress favorite posts plugin if a new plugin is used you can just create a new interface with the same API and switch to it

class Wrapper{
    public $posts;
    function __construct() {
        $this->posts= get_my_favs();
    }

    public function all_posts() {
        
        return $this->posts;
      }
}
    
// Get the users favorite classes
function get_my_favs($user = ""){
    $user = isset($_REQUEST['user']) ? $_REQUEST['user'] : "";
    // wpfp_get_user_meta($user);

    if ( !empty($user) ) {
        if ( wpfp_is_user_favlist_public($user) )
            $favorite_post_ids = wpfp_get_users_favorites($user);

    } else {
        $favorite_post_ids = wpfp_get_users_favorites();
    }
    return $favorite_post_ids;
}

function wpfp_get_user_id() {
    global $current_user;
    get_currentuserinfo();
    return $current_user->ID;
    }

    function wpfp_get_user_meta($user = "") {
    if (!empty($user)):
        $userdata = get_user_by( 'login', $user );
        $user_id = $userdata->ID;
        //IMPORTKEY
        return get_user_meta($user_id, WPFP_META_KEY, true);
    else:
        //IMPORT
        return get_user_meta(wpfp_get_user_id(), WPFP_META_KEY, true);
    endif;
}


function wpfp_get_users_favorites($user = "") {
    $favorite_post_ids = array();

    if (!empty($user)):
        //IMPORT
        return wpfp_get_user_meta($user);
    endif;

    # collect favorites from cookie and if user is logged in from database.
    if (is_user_logged_in()):
        $favorite_post_ids = wpfp_get_user_meta();
    else:
        //IMPORT
        if (wpfp_get_cookie()):
            foreach (wpfp_get_cookie() as $post_id => $post_title) {
                array_push($favorite_post_ids, $post_id);
            }
        endif;
    endif;
return $favorite_post_ids;
}

function wpfp_is_user_favlist_public($user) {
    $user_opts = wpfp_get_user_options($user);
    if (empty($user_opts)) return WPFP_DEFAULT_PRIVACY_SETTING;
    if ($user_opts["is_wpfp_list_public"])
        return true;
    else
        return false;

}