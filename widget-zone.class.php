<?php
class BSAZoneWidget extends WP_Widget {
  function BSAZoneWidget() {
    /* Widget settings. */
    $widget_ops = array( 'classname' => 'bsa_zone', 'description' => __('Buy Sell Ads Zone', 'buy_sell_ads') );

    /* Widget control settings. */
    $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'bsa-zone-widget' );

    /* Create the widget. */
    $this->WP_Widget( 'bsa-zone-widget', __('Buy Sell Ads Zone', 'buy_sell_ads'), $widget_ops, $control_ops );
  }

  /**
   * How to display the widget on the screen.
   */
  function widget( $args, $instance ) {
    extract( $args );

    /* Our variables from the widget settings. */
    $title = apply_filters('widget_title', $instance['title'] );
    $name = $instance['name'];
    $zone = $instance['zone'];

    $bsa_options = get_option("buy_sell_ads");

    // should we show widget code if no ads are filled in this zone?
    if ($bsa_options['hide_empty_widget'] && count($bsa_options['zones'][$zone]['filters']) == 0) {
      $should_show = false;
    } else {
      $should_show = true;
    }
    
    if ($should_show) {
      /* Before widget (defined by themes). */
      echo $before_widget;

      /* Display the widget title if one was input (before and after defined by themes). */
      if ( $title )
        echo $before_title . $title . $after_title;
    }

    bsa_zone($zone); // we still need to print zone for statistics purposes.

    if ($should_show) {
      /* After widget (defined by themes). */
      echo $after_widget;
    }
  }

  /**
   * Update the widget settings.
   */
  function update( $new_instance, $old_instance ) {
    $instance = $old_instance;

    /* Strip tags for title and name to remove HTML (important for text inputs). */
    $instance['title'] = strip_tags( $new_instance['title'] );

    /* No need to strip tags for zone. */
    $instance['zone'] = $new_instance['zone'];

    return $instance;
  }

  /**
   * Displays the widget settings controls on the widget panel.
   * Make use of the get_field_id() and get_field_name() function
   * when creating your form elements. This handles the confusing stuff.
   */
  function form( $instance ) {

    /* Set up some default widget settings. */
    $bsa_options = get_option("buy_sell_ads");
    $zones = array();
    foreach ($bsa_options['zones'] as $k => $v) {
      if($bsa_options['zone_settings'][$v['id']]['placement'] == 'widget') {
        $zones[] = $v;
      }
    }
    $defaults = array( 'title' => __('Sponsors', 'buy_sell_ads'), 'zone' => $zones[0]['id'] );
    $instance = wp_parse_args( (array) $instance, $defaults ); ?>

    <!-- Widget Title: Text Input -->
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'buy_sell_ads'); ?></label>
      <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
    </p>

    <!-- Zones: Select Box -->
    <p>
      <label for="<?php echo $this->get_field_id( 'zone' ); ?>"><?php _e('Zone to Display:', 'buy_sell_ads'); ?></label> 
      <select id="<?php echo $this->get_field_id( 'zone' ); ?>" name="<?php echo $this->get_field_name( 'zone' ); ?>" class="widefat" style="width:100%;">
        <?php foreach ($zones as $k => $v): ?>
        <?php $ad_type = ($v['type']=='1')?'Text Ads':'Image Ads'; ?>
        <option <?php if ( $v['id'] == $instance['zone'] ) echo 'selected="selected"'; ?> value="<?php echo $v['id']; ?>"><?php echo $v['id'].', '.$ad_type.', '.$v['width'].'x'.$v['height'].', '.$v['nads'].' Slots'; ?></option>
        <?php endforeach; ?>
      </select>
    </p>

  <?php
  }
}
?>
