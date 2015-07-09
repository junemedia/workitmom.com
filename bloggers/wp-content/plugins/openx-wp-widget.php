<?php
/***
Plugin Name: openxwpwidget
Plugin URI: http://www.openx.org/
Description: Sidebar-Widget, display a banner in a sidebar and replace magics {openx:zoneid} with calls to a openx adserver
Version: 1.1
Author: Heiko Weber, heiko@wecos.de
Author URI: http://www.wecos.de
Update Server: http://www.openx.org/...
Min WP Version: 2.5.0
Max WP Version: 2.6.1
***/

/***
+---------------------------------------------------------------------------+
| OpenX v2.5                                                                |
| ==========                                                                |
|                                                                           |
| Copyright (c) 2003-2008 OpenX Limited                                     |
| For contact details, see: http://www.openx.org/                           |
|                                                                           |
| This program is free software; you can redistribute it and/or modify      |
| it under the terms of the GNU General Public License as published by      |
| the Free Software Foundation; either version 2 of the License, or         |
| (at your option) any later version.                                       |
|                                                                           |
| This program is distributed in the hope that it will be useful,           |
| but WITHOUT ANY WARRANTY; without even the implied warranty of            |
| MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             |
| GNU General Public License for more details.                              |
|                                                                           |
| You should have received a copy of the GNU General Public License         |
| along with this program; if not, write to the Free Software               |
| Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA |
+---------------------------------------------------------------------------+
$Id$
*/

/** widget initialization moved into one function,
 *  registered below at the end of the file.
 */
function widget_openxwpwidget_init() {

    global $widget_openxwpwidget_version;
    
    $widget_openxwpwidget_version = 110;
    
    if ( !function_exists('register_sidebar_widget')
      || !function_exists('register_widget_control') )
            return;

    /** this widget callback function gets called to output
     *  the "widget content" to the sidebar, so this are actually
     *  one or more banners (invocationcodes)
     */
    function widget_openxwpwidget($args, $widget_args = 1) {
        extract($args, EXTR_SKIP );
        if (is_numeric($widget_args))
            $widget_args = array('number' => $widget_args);
        $widget_args = wp_parse_args($widget_args, array('number' => -1));
        extract($widget_args, EXTR_SKIP);

        // Data should be stored as array: array(number => data for that instance of the widget, ...)
        $options = get_option('widget_openxwpwidget');
        if (!isset($options['options'][$number]) )
            return;

        // zonecount should be set
        $values = $options['options'][$number];
        if (!isset($values['zonecount']) )
            return;

        // retrieve our widget options and settings
        $location = stripslashes(get_option('openxwpwidget_url2openx'));
        if (!isset($location))
            return;

        // how many banners/zones should we show
        $zoneCount = empty($values['zonecount']) ? 0 : $values['zonecount'];

        // loop over there ...
        $bannercode = '';
        for ($n = 0; $n < intval($zoneCount); $n++) {
            $zoneID = empty($values['zoneid'.$n]) ? '' : $values['zoneid'.$n];
            $bannercode .= _openxwpwidget_get_invocation($location, $zoneID);
        }

        // done, most of the echo's are framework
        echo $before_widget;
        echo $before_title;
        echo $bannercode;
        echo $after_title;
        echo $after_widget;
    }

    /** small helper function, returns a javascript invocationcode
     *
     *  @param string  $location path to the adservers delivery directory
     *  @param integer $zoneID   ID of the zone
     *
     *  @return string javascript invocation code
     */
    function _openxwpwidget_get_invocation($location, $zoneID)
    {
        if (empty($location) || $location == '' || intval($zoneID) == 0)
            return '';

        $random = md5(rand(0, 999999999));
        $n = substr(md5(rand(0, 999999999)), 0, 6);

        return "";
    }


    /** this function install the callback-function openxwp_adminsection
     *  for a admin setup page for this plugin
     */
    function openxwpwidget_admin_menuitem()
    {
        if (function_exists('add_options_page')) {
            add_options_page('options-general.php', 'OpenX-WP', 8, basename(__FILE__), 'openxwpwidget_adminsection');
            add_action( "admin_print_scripts", 'openxwpwidget_admin_head' );
        }
    }

    /** this callback function adds some javascript to
     *  the head section (wp-admin-pages only). I've tried
     *  to add this code only for openxwpwidget-pages, but
     *  didn't find a reference-guide how this really work.
     *  So, it gets always added ...
     */
    function openxwpwidget_admin_head()
    {
        ?>
<script type="text/javascript"><!--//<![CDATA[
function openxwpwidget_returnObjByName(name)
{
    if (document.getElementsByName)
       var returnVar = document.getElementsByName(name);
    else if (document.all)
       var returnVar = document.all[name];
    else if (document.layers)
       var returnVar = document.layers[name];
  return returnVar;
}
function openxwpwidget_toggle_visible(whichLayer, on_off) {
  var elem = openxwpwidget_returnObjByName(whichLayer);
  if (elem && elem.length > 0) {
    var item = elem[0];
    var vis = item.style;
    // if the style.display value is blank we try to figure it out here
    if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
      vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'inline':'none';
    if (!on_off)
      on_off = (vis.display!='none') ? -1 : 1;
    vis.display = (on_off < 0)?'none':'inline';
  }
}
function openxwpwidget_showhide_zones(box, widget) {
    var zonecount = box.options[box.selectedIndex].value;
    for (n = 0; n < 10; n++) {
        openxwpwidget_toggle_visible('widget-openxwpwidget-div-zoneid'+n+'-'+widget, (zonecount > n) ? 1 : -1);
    }
}
//]]>-->
</script>
        <?php
    }

    /**
     * this callback function gets called for every real content
     * delivered to normal users. The callback will be installed
     * below (near end-of-file)
     *
     * @param string the content
     *
     * @return string the (maybe un-) modified content
     */
    function openxwpwidget_replace_magic($content)
    {
       // find the magic zone-tags somehow, we replace {openx:NNN}
       // with a invocationcode, whereas NNN is a zoneID
       if (($matches = preg_match_all('/\{openx\:(\d+)\}/', $content, $aResult)) !== false) {
           $content = _openxwpwidget_replace_zones($content, $aResult);
       }

       return $content;
    }

    /**
     * this function replace any magic openx-zones in the given content
     *
     * @param string the content
     * @param array of strings with zone-numbers found in content
     *
     * @return string the (maybe un-) modified content
     */
    function _openxwpwidget_replace_zones($content, $aZones)
    {
        $url2openx = get_option('openxwpwidget_url2openx');
        $url2openx = stripslashes($url2openx);

        // prepare our search/replacement, with perl I would have
        // used a closure to replace it in a single-path
        $from = array();
        $to = array();

        foreach ($aZones as $hits) {
            $zoneID = $hits[0];
            $random = md5(rand(0, 999999999));
            $from[] = '{openx:' . $zoneID . '}';
            $to[]   = _openxwpwidget_get_invocation($url2openx, $zoneID);
        }
        return str_replace($from, $to, $content);
    }


    /** this function represents the admin setup page for this
     *  plugin.
     */
    function openxwpwidget_adminsection()
    {
        add_option('openxwpwidget_url2openx', '');

        if (isset($_POST['openxwpwidget_url2openx'])) {
            $url2openx = $_POST['openxwpwidget_url2openx'];
            // remove a trailing http://, internally we use it without
            $url2openx = preg_replace('/^https?:\/\//', '', $url2openx);
            update_option('openxwpwidget_url2openx', $url2openx);
        }
        $url2openx = stripslashes(get_option('openxwpwidget_url2openx'));

        // uh, I am not a web-designer, so someone else pick up this
        // part please ...
        ?>

<STYLE TYPE="TEXT/CSS">
div#openxwpwidget b {
        color: red;
}
</STYLE>

<DIV CLASS="wrap">
  <DIV id="poststuff">
    <DIV id="openxwpwidget">
       <p>
       Type the path to your adservers delivery directory into the
       textfield.</p>
       <p>Sample path to adserver: <b>ads.openx.org/delivery.</b></p>
       <p>Now you simply add ad-code into your content like
       <b>{openx:N}</b>, whereas <b>N</b> is a zoneID at your
       adserver.</p>
       <p>In addition, this plugin acts as a widget, so you can add
       it to a sidebar.</p>

      <FORM name="openxwpwidget_form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?page=openx-wp-widget.php&updated=true">
         <p>Url to OpenX-AdServer:</p>
         <input type=textfield size="60" name="openxwpwidget_url2openx" id="url2openx" value="<?php echo $url2openx; ?>">
         <p>&nbsp;</p>
         <input type="submit" name="submit" value="Save" />
      </FORM>
    </DIV>
  </DIV>
</DIV>

      <?php
    }

    /** This function controls the sidebar functionality,
     *  admin-part. Show and save of the options.
     */
    function widget_openxwpwidget_control($widget_args = 1)
    {
        global $wp_registered_widgets;
        static $updated = false; // Whether or not we have already updated the data after a POST submit

        if (is_numeric($widget_args))
            $widget_args = array('number' => $widget_args);
        
        $widget_args = wp_parse_args($widget_args, array('number' => -1));
        extract($widget_args, EXTR_SKIP);

        // Data should be stored as array:  array(number => data for that instance of the widget, ...)
        $options = get_option('widget_openxwpwidget');
        if (!is_array($options))
            $options = widget_openxwpwidget_default_options();

        // We need to update the data
        if (!$updated && !empty($_POST['sidebar'])) {
		    // Tells us what sidebar to put the data in
            $sidebar = (string) $_POST['sidebar'];

            $sidebars_widgets = wp_get_sidebars_widgets();
            if (isset($sidebars_widgets[$sidebar]))
                $this_sidebar =& $sidebars_widgets[$sidebar];
            else
                $this_sidebar = array();

            foreach ($this_sidebar as $_widget_id) {
               // Remove all widgets of this type from the sidebar.
               // We'll add the new data in a second.
               // This makes sure we don't get any duplicate data
			   // since widget ids aren't necessarily persistent across multiple updates
                if ('widget_openxwpwidget' == $wp_registered_widgets[$_widget_id]['callback']
                && isset($wp_registered_widgets[$_widget_id]['params'][0]['number'])) {
                    $widget_number = $wp_registered_widgets[$_widget_id]['params'][0]['number'];
                    if (!in_array("widget_openxwpwidget-$widget_number", $_POST['widget-id'])) {
                        // the widget has been removed. "widget_openxwpwidget-$widget_number" is "{id_base}-{widget_number}
                        unset($options['options'][$widget_number]);
                    }
                }
            }

            foreach ( (array) $_POST['widget-widget_openxwpwidget'] as $widget_number => $widget_instance) {
                // compile data from $widget_instance
                if (!isset($widget_instance['zonecount']) && isset($options['options'][$widget_number]) ) // user clicked cancel
                    continue;
                $newoptions['zonecount'] = strip_tags(stripslashes($widget_instance['zonecount']));
                for ($n = 0; $n < intval($newoptions['zonecount']); $n++)
                    $newoptions['zoneid'.$n] = strip_tags(stripslashes($widget_instance['zoneid'.$n]));
                
                $options['options'][$widget_number] = $newoptions;
                // Even simple widgets should store stuff in array, rather than in scalar
            }

            update_option('widget_openxwpwidget', $options);
            
            $updated = true; // So that we don't go through this more than once
        }

        if ($number == -1) {
            $number = '%i%';
            $values = array('zonecount' => 0);
        }
        else {
            $values = $options['options'][$number];
        }

        // prepare our settings to show them at the admin-page
        // we prepare up to 10 zones, and use javascript/style to
        // show or hide them, because if the users changes the
        // total zonecode he like to use, we can't submit to
        // change the number of offered zones.

        $zonecount = htmlspecialchars($values['zonecount'], ENT_QUOTES);
        for ($n = 0; $n < intval($zonecount); $n++)
            $zoneID[$n] = htmlspecialchars($values['zoneid'.$n], ENT_QUOTES);
        ?>
        <div>
          <label for="widget_openxwpwidget-zonecount-<?php echo $number; ?>">
            Number of banners:<br />
            <select name="widget-widget_openxwpwidget[<?php echo $number; ?>][zonecount]"
                      id="widget_openxwpwidget-zonecount-<?php echo $number; ?>"
                onchange="openxwpwidget_showhide_zones(this, <?php echo $number; ?>);">
              <?php
                for ($n = 0; $n <= 10; $n++) {
                    $sel = ($n == $zonecount) ? 'selected' : '';
                    echo "<option value='$n' $sel>$n</option>\n";
                }
              ?>
            </select>
          </label>
          <br /><br />
          <?php
            for ($n = 0; $n < 10; $n++) {
                $showhide = (intval($zonecount) > $n) ? 'inline' : 'none';
                $n1 = $n+1;
                echo "<div id='widget-openxwpwidget-div-zoneid$n-$number' name='widget-openxwpwidget-div-zoneid$n-$number' style='display:$showhide;'>\n";
                echo "<label for='widget_openxwpwidget-zoneid-$number'>ZoneID for Banner $n1:<br />\n";
                echo "<input type='text' id='widget-openxwpwidget-zoneid$n-$number' name='widget-widget_openxwpwidget[$number][zoneid$n]' value='$zoneID[$n]' />\n";
                echo "</label>\n";
                echo "<br /></div>\n";
            }
          ?>
          <input type="hidden" name="widget-openxwpwidget-submit-<?php echo $number; ?>" id="openxwpwidget-submit-<?php echo $number; ?>" value="true" />
       </div>
       <?php
    }

    function widget_openxwpwidget_default_options()
    {
        global $widget_openxwpwidget_version;
        
        return array('version' => $widget_openxwpwidget_version,
                     'options' => array(1 => array('zonecount' => 0)));
    }
    /** upgrade our options from single instance
     */
    function widget_openxwpwidget_upgrade_from_1_0($options)
    {
        global $widget_openxwpwidget_version;
        
        return array('version' => $widget_openxwpwidget_version,
                     'options' => array(1 => $options));
    }

    /** check the stored options, upgrade if from older
     *  version
     */
    function widget_openxwpwidget_upgrade_check()
    {
        global $widget_openxwpwidget_version;
        
        $options = get_option('widget_openxwpwidget');
        if (!is_array($options)) {
            $options = widget_openxwpwidget_default_options();
        } else if (!isset($options['version'])) {
            $options = widget_openxwpwidget_upgrade_from_1_0($options);
        } else if (isset($options['version']) && $options['version'] == $widget_openxwpwidget_version) {
            return $options;
        }
        // save the modified options
        update_option('widget_openxwpwidget', $options);
        return $options;
    }
    
    /** local init-function, register widget and control
     */
    function widget_openxwpwidget_local_init()
    {
        $options = widget_openxwpwidget_upgrade_check();
        $widget_ops = array('classname' => 'widget_openxwpwidget', 'description' => __('Widget to serve OpenX banners from sidebars'));
        $control_ops = array('width' => 200, 'height' => 250, 'id_base' => 'widget_openxwpwidget');
        $name = __('OpenX Widget');
        $values = $options['options'];
        $registered = false;
        foreach (array_keys($values) as $o ) {
            // Old widgets can have null values for some reason
            if (!isset($values[$o]['zonecount']))
                continue;

            // $id should look like {$id_base}-{$o}
            $id = "widget_openxwpwidget-$o"; // Never never never translate an id
            $registered = true;
            wp_register_sidebar_widget( $id, $name, 'widget_openxwpwidget', $widget_ops, array( 'number' => $o ) );
            wp_register_widget_control( $id, $name, 'widget_openxwpwidget_control', $control_ops, array( 'number' => $o ) );
        }

	    // If there are none, we register the widget's existance with a generic template
        if ( !$registered ) {
            wp_register_sidebar_widget( 'widget_openxwpwidget-1', $name, 'widget_openxwpwidget', $widget_ops, array('number' => -1));
            wp_register_widget_control( 'widget_openxwpwidget-1', $name, 'widget_openxwpwidget_control', $control_ops, array('number' => -1));
        }
    }

    // register the widget, check our options
    widget_openxwpwidget_local_init();
    
    // and finally install our admin-setup-callback and content-filter
    add_action('admin_menu', 'openxwpwidget_admin_menuitem');
    add_filter('the_content', 'openxwpwidget_replace_magic');
}

add_action('plugins_loaded', 'widget_openxwpwidget_init');
?>
