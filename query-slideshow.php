<?php
/*

******************************************************************

Contributors:      daggerhart
Plugin Name:       Query Slideshow
Plugin URI:        http://www.daggerhart.com
Description:       Query Slideshow is a plugin that adds 'Slideshow' as a Template Style for Query Wrangler.
Author:            Jonathan Daggerhart
Author URI:        http://www.daggerhart.com
Version:           1.2

******************************************************************

Copyright 2010  Jonathan Daggerhart  (email : jonathan@daggerhart.com)
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

******************************************************************
*/

/*
 * Add slideshow style
 */
function query_slideshow_styles($styles){
  $styles['slideshow'] = array(
    'title' => 'Slideshow',
    'template' => 'query-slideshow',
    'default_path' => dirname(__FILE__).'/templates', // do not include last slash
    'settings_callback' => 'query_slideshow_settings_form',
  );

  return $styles;
}
// add default field styles to the filter
add_filter('qw_styles', 'query_slideshow_styles', 20);

/*
 * Hook qw_pre_render to enable jquery.cycle
 */
function query_slideshow_pre_render($options){
  if($options['display']['style'] == 'slideshow'){
    $settings = $options['display']['slideshow_settings'];

    // don't let blank options override jquery.cycle defaults
    foreach($settings as $k => $setting){
      if (empty($setting)){
        unset($settings[$k]);
      }
    }

    $settings_output = json_encode($settings);
    $options['display']['slideshow_settings']['output'] = $settings_output;
    // only enqueue script if slideshow is present
    wp_enqueue_script('jquery.cycle', plugins_url('/js/jquery.cycle.all.js', __FILE__ ), array('jquery'));
  }
  return $options;
}
add_action('qw_pre_render', 'query_slideshow_pre_render');

/*
 * Hook qw_preview to add cycle js
 */
function query_slideshow_preview($options){
  if($options['display']['style'] == 'slideshow'){
    print '<script type="text/javascript" src="'.plugins_url('/js/jquery.cycle.all.js', __FILE__ ).'"></script>';
  }
  return $options;
}
add_action('qw_pre_preview', 'query_slideshow_preview');

/*
 * Hook qw_pre_save
 */
function query_slideshow_pre_save($options){
  return $options;
}
add_action('qw_pre_save', 'query_slideshow_pre_save');

/*
 * Settings form
 */
function query_slideshow_settings_form($style)
{
  ob_start();
  ?>
    <div>
      <label class="qw-label">Speed:</label>
      <input name="<?php print $style['form_prefix']; ?>[speed]"
             type="text"
             value="<?php print $style['values']['speed']; ?>" />
      <p class="description">
        The speed option defines the number of milliseconds it will take to transition from one slide to the next.
      </p>
    </div>

    <div>
      <label class="qw-label">Timeout:</label>
      <input name="<?php print $style['form_prefix']; ?>[timeout]"
             type="text"
             value="<?php print $style['values']['timeout']; ?>" />
      <p class="description">
        The timeout option specifies how many milliseconds will elapse between the start of each transition.
      </p>
    </div>
    <div>
      <label class="qw-label">Fx:</label>
      <select name="<?php print $style['form_prefix']; ?>[fx]" />
        <?php
          $a = array(
            'fade',
            'zoom',
            'scrollDown',
            'scrollUp',
            'scrollLeft',
            'scrollRight',
            'shuffle',
            'slideY',
            'slideX',
            'turnDown',
            'turnUp',
          );

          foreach($a as $fx)
          {
            $fx_selected = ($style['values']['fx'] == $fx) ? 'selected="selected"' : '';
            ?>
            <option value="<?php print $fx; ?>" <?php print $fx_selected; ?> ><?php print ucfirst($fx); ?></option>
            <?php
          }
        ?>
      </select>
      <p class="description">
        The name of transition effect.
      </p>
    </div>
  <?php
  return ob_get_clean();
}