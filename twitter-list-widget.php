<?php
/*
Plugin Name: Twitter List Widget
Plugin URI: http://yorik.uncreated.net
Description: A widget to display a feed from a twitter list such as those generated from http://twiterlist2rss.appspot.com/ or from a comma-separated list of twitter feeds. In fact, any RSS feed where you want the links, the @someone and the #something to be turned into clickable links.
Version: 0.2
Author: Yorik van Havre
Author URI: http://yorik.uncreated.net
*/

/*  Copyright 2009 Yorik van Havre  (email : yorik at uncreated dot net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Twitter_List_Widget extends WP_Widget {
  function Twitter_List_Widget() {
    $widget_ops = array('classname' => 'twitter_list_widget', 'description' => 'A widget to display a feed from a twitter list such as those generated from http://twiterlist2rss.appspot.com/ or from a comma-separated list of twitter feeds. In fact, from any RSS feed where you want the links, the @someone and the #something to be turned into clickable links.' );
    $this->WP_Widget('twitter_list_widget', 'Twitter List Widget', $widget_ops);
  }
 
  function widget($args, $instance) {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;

    $title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
    $feed_url = empty($instance['feed_url']) ? '&nbsp;' : $instance['feed_url'];
    $maxnumber = empty($instance['maxnumber']) ? '&nbsp;' : $instance['maxnumber'];
    $link_first_word = empty($instance['link_first_word']) ? '&nbsp;' : $instance['link_first_word'];
 
    if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };

    if ( !empty( $feed_url ) ) {

      if ( empty( $maxnumber ) ) { $maxnumber = 10; }

      // Get RSS Feed(s)
      include_once(ABSPATH . WPINC . '/feed.php');
      
      // Get a SimplePie feed object from the specified feed source
      $feedsarray = split(',',$feed_url);
      $rss = fetch_feed($feedsarray);

      // Figure out how many total items there are, but limit it to maxnumber. 
      $maxitems = $rss->get_item_quantity((int)$maxnumber);

      // Build an array of all the items, starting with element 0 (first element).
      $rss_items = $rss->get_items(0,$maxitems);

      ?>

      <ul class="twitter-list-feed">
	 <?php if ($maxitems == 0) echo '<li>Nenhuma foto por enquanto.</li>';
             else
	       // Loop through each feed item and display each item as a hyperlink.
	       foreach ( $rss_items as $item ) : 
	         $message = $item->get_title();
                 $author = $item->get_author();
                 if ($author) { 
		   $author = $author->get_name();
		   $author = explode(' ',$author);
		   $message = $author[0].': '.$message;
		 }
                 $message = preg_replace("/(\S*)(:\/\/)(\S*)/","<a href=\"\\1\\2\\3\">\\1\\2\\3</a>",$message);
                 $message = preg_replace("/(@)(\S*)/","<a href=\"http://www.twitter.com/\\2\">\\1\\2</a>",$message);
                 $message = preg_replace("/(#)(\S*)/","<a href=\"http://www.twitter.com/#search?q=\\2\">\\1\\2</a>",$message);

		 if ( $link_first_word == "1" ) {
		   $msglist = explode(' ',$message);
		   $first = str_ireplace(':','',$msglist[0]);
		   $first = '<a href="http://www.twitter.com/'.$first.'">'.$first.'</a>:';
		   $msglist[0] = $first;
                   $message = implode(' ',$msglist);
		 }

?>
		 <li>
		 <?php echo $message; ?>&nbsp;
	           <abbr><?php echo $item->get_date('d M Y'); ?></abbr>
		 </li>
	       <?php endforeach; ?>
       </ul>

    <?php }

    echo $after_widget;
    }
 
  function update($new_instance, $old_instance) {
    $instance = $old_instance;
    $instance['title'] = strip_tags($new_instance['title']);
    $instance['feed_url'] = strip_tags($new_instance['feed_url']);
    $instance['maxnumber'] = strip_tags($new_instance['maxnumber']);
    $instance['link_first_word'] = strip_tags($new_instance['link_first_word']);
 
    return $instance;
  }
 
  function form($instance) {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'feed_url' => '', 'maxnumber' => '', 'link_first_word' => '' ) );
    $title = strip_tags($instance['title']);
    $feed_url = strip_tags($instance['feed_url']);
    $maxnumber = strip_tags($instance['maxnumber']);
    $link_first_word = strip_tags($instance['link_first_word']);
?>
      <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
								    
      <p><label for="<?php echo $this->get_field_id('feed_url'); ?>">Comma-separated feeds list: <input class="widefat" id="<?php echo $this->get_field_id('feed_url'); ?>" name="<?php echo $this->get_field_name('feed_url'); ?>" type="text" value="<?php echo attribute_escape($feed_url); ?>" /></label></p>
														     
      <p><label for="<?php echo $this->get_field_id('maxnumber'); ?>">Max number of tweets to display: <input class="widefat" id="<?php echo $this->get_field_id('maxnumber'); ?>" name="<?php echo $this->get_field_name('maxnumber'); ?>" type="text" value="<?php echo attribute_escape($maxnumber); ?>" /></label></p>

      <p><label for="<?php echo $this->get_field_id('link_first_word'); ?>">Turn first word into a link? <input class="widefat" id="<?php echo $this->get_field_id('link_first_word'); ?>" name="<?php echo $this->get_field_name('link_first_word'); ?>" type="checkbox" value="1" <?php if($link_first_word) { echo 'checked="checked"';} ?> /></label></p>

<?php
																			}
}

// register_widget('Twitter_List_Widget');
add_action( 'widgets_init', create_function('', 'return register_widget("Twitter_List_Widget");') );

?>