<?php $bsa_options = get_option($this->name); ?>
<script type="text/javascript">
function confirm_reset() {
  var answer = confirm("All of your custom messages will also be reset.  Are you sure you want to reset all settings (even widget settings)?");
  if(answer)
    return true;
  else
    return false;
}

jQuery(document).ready(function($){
  $("#enable_aab").click(function(e){
    if($(this).is(':checked')){
      $("#aab_options").slideDown();
      $(this).attry("checked",false);
    }
    else{
      $("#aab_options").slideUp();
      $(this).attry("checked",true);
    }
  });
});
</script>
<form method="post"><fieldset>
<?php
    // deprecate note
    printf('<div style="background-color:#FFFBCC;border:1px solid #E6DB55;margin:10px 0;padding:5px;">This plugin as been deprecated in favor of the official <a href="http://wordpress.org/extend/plugins/buysellads/">BuySellAds plugin</a>. Please switch over as soon as possible!</div>');

    // check options
    $default_options = $this->get_default_options();
    foreach( $default_options as $option_name => $option_value )
    {
      if ( !array_key_exists($option_name, $bsa_options) ) {
        printf('
          <div style="background-color:#FFFBCC;border:1px solid #E6DB55;margin:10px 0;padding:5px;">%s&nbsp;&nbsp;<input type="submit" name="bsa_options_upgrade_submit" value="%s &#187;"/></div>
          ',
          __('Some options are missing (possibly from plugin upgrade).  Please reactivate.', $this->name),
          __('Reactivate', $this->name)
        );
        break;
      }
    }

    // prepare options
    if ($bsa_options['enable_aab']) {
      $enable_aab_check = 'checked';
      $show_aab = 'block';
    } else {
      $enable_aab_check = '';
      $show_aab = 'none';
    }
    
    printf('
      <h2>%s</h2>
      <p><label>%s &nbsp; <input name="bsa_options_update[show_link]" value="on" type="radio" '.checked(true, $bsa_options['show_link'], false).'/></label></p>
      <p><label>%s <a href="http://omninoggin.com/donate">%s</a> &nbsp; <input name="bsa_options_update[show_link]" value="off" type="radio" '.checked(false, $bsa_options['show_link'], false).'/></label></p>
      ',
      __('Support this plugin!', $this->name),
      __('Display "Powered by Buy Sell Ads Plugin" link at the bottom of the widget', $this->name),
      __('Do not display "Powered by Buy Sell Ads Plugin" link.', $this->name),
      __('I will donate and/or write about this plugin.', $this->name)
    );

    printf('
      <h2>%s</h2>
      <p><label>%s (<a href="http://omninoggin.com/wordpress-plugins/buy-sell-adswordpress-plugin/#sitekey">%s</a>)<br/><input name="bsa_options_update[site_key] type="text" style="width:300px" value="%s" /></label></p>
      ',
      __('Site Key', $this->name),
      __('Your Buy Sell Ads site key', $this->name),
      __('Where do I find this?', $this->name),
      attribute_escape($bsa_options['site_key'])
    );

    printf('
      <h2>%s</h2>
        <div class="bsa_zones">
          <div class="bsa_zone_title">
            <div class="bsa_zone_id">%s</div>
            <div class="bsa_zone_type">%s</div>
            <div class="bsa_zone_size">%s</div>
            <div class="bsa_zone_nads">%s</div>
            <div class="bsa_zone_placement">%s</div>
            <div class="bsa_zone_position">%s</div>
            <br class="bsa_clear" />
          </div>
      ',
      __('Zones', $this->name),
      __('ID', $this->name),
      __('Type', $this->name),
      __('Size', $this->name),
      __('Ad Slots', $this->name),
      __('Display Location', $this->name),
      __('Position on Posts/Pages', $this->name)
    );

    foreach($bsa_options['zones'] as $k => $v) {
      printf('
          <div class="bsa_zone">
            <div class="bsa_zone_id">'.$v['id'].'</div>
            <div class="bsa_zone_type">%s</div>
            <div class="bsa_zone_size">'.$v['width'].'x'.$v['height'].'</div>
            <div class="bsa_zone_nads">'.$v['nads'].'</div>
            <div class="bsa_zone_placement">
              <input type="radio" name="bsa_options_update[zone_settings]['.$v['id'].'][placement]" value="widget" '.checked('widget', $bsa_options['zone_settings'][$v['id']]['placement'], false).' /> %s (<a href="'.trailingslashit(get_option('siteurl')).'wp-admin/widgets.php">%s</a>)<br/>
              <input type="radio" name="bsa_options_update[zone_settings]['.$v['id'].'][placement]" value="posts" '.checked('posts', $bsa_options['zone_settings'][$v['id']]['placement'], false).'/> %s
            </div>
            <div class="bsa_zone_position">
              <select name="bsa_options_update[zone_settings]['.$v['id'].'][position]">
                <option value="before" %s>%s</option>
                <option value="after" %s>%s</option>
              </select>
            </div>
            <br class="bsa_clear" />
          </div>
        ',
        ($v['type'] == '0')?__('Image Ads', $this->name):__('Text Ads', $this->name),
        __('Widget', $this->name),
        __('Configure', $this->name),
        __('Posts/Pages', $this->name),
        ('before' == $bsa_options['zone_settings'][$v['id']]['position'])?'selected':'',
        __('Before', $this->name),
        ('after' == $bsa_options['zone_settings'][$v['id']]['position'])?'selected':'',
        __('After', $this->name)
      );
    }

    if ( function_exists( 'wp_nonce_field' ) && wp_nonce_field( $this->name ) ) {
      printf('
        <p class="submit">
          <input type="submit" name="bsa_options_update_submit" value="%s &#187;" />
        </p>
        ',
        __('Update Zones', $this->name)
      );
    }

    printf('
      </div>
      <h2>%s</h2>
      <p>%s %s
        <ol>
          <li><a href="http://buysellads.com/sell">%s</a></li>
          <li>%s</li>
        </ol>
      </p>
      ',
      __('Presentation', $this->name),
      __('Each zone\'s presentation settings can now be modified in your Buy Sell Ads properties page.', $this->name),
      __('To access these settings:', $this->name),
      __('Go to your Buy Sell Ads properties page.', $this->name),
      __('Click on "Install Ad Code"', $this->name)
    );
    
    printf('
      <h2>%s</h2>
      <input type="checkbox" name="bsa_options_update[hide_empty_widget]" '.checked(true, $bsa_options['hide_empty_widget'], false).' /> %s
      ',
      __('Widget', $this->name),
      __('Hide widget output if no ads are filled? (You\'ll also have to uncheck "Show Ad Here" in your BSA presentation settings)', $this->name)
    );

    printf('
      <h2>%s</h2>
      <p>%s:<br/><strong>&lt;?php bsa_zone(\'1234567\'); ?&gt;</strong></p>
      ',
      __('Theme Insertion', $this->name),
      __('To insert a zone into your theme manually you can specify', $this->name)
    );

    printf('<!--
      <h2>%s</h2>
      <p><label>%s &nbsp; <input id="enable_aab" name="bsa_options_update[enable_aab]" type="checkbox" disabled %s/> &nbsp; (%s)</label></p>
      <span id="aab_options" style="display:%s">
      <h2>%s</h2>
      <p><label>%s<input name="bsa_options_update[nonce_interval]" type="text" size="4" value="%s"/>%s</label>
      <p><label>%s<br/><input type="text" size="16" value="%s" readonly disabled/></label>
      <span class="submit"><input type="submit" name="bsa_options_regenerate_nonce_submit" value="%s"/></span></p>
      </span>-->
      ',
      __('Anti-AdBlock', $this->name),
      __('Enable Anti-AdBlock', $this->name),
      $enable_aab_check,
      __('Anti-AdBlock conflicts with BSA statistics collection. I have forced this off until I figure out how to fix it.', $this->name),
      $show_aab,
      __('Anti-AdBlock', $this->name),
      __('Automatically regenerate nonce every', $this->name),
      attribute_escape($bsa_options['nonce_interval']),
      __('seconds', $this->name),
      __('Your current nonce.', $this->name),
      attribute_escape($bsa_options['nonce_proxy']),
      __('Manually Regenerate Nonce', $this->name)
    );

    if ( function_exists( 'wp_nonce_field' ) && wp_nonce_field( $this->name ) ) {
      printf('
        <p class="submit">
          <input type="submit" name="bsa_options_update_submit" value="%s &#187;" />
          <input type="submit" name="bsa_options_reset_submit" value="Reset ALL Options &#187;" onclick="return confirm_reset()"/>
        </p>
        ',
        __('Update Options', $this->name),
        __('Reset ALL Options', $this->name)
      );
    }
?>
</fieldset></form>
