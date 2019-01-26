<?php


function register_mysettings() {
    register_setting( 'mfpd-settings-group', 'mfpd_option_name' );
}












function camunda_create_menu() {
    add_menu_page('Camunda Process', 'Camunda', 'administrator', 'sada', 'camunda_settings_page',plugins_url('/images/icon.png', __FILE__), 1);
}

function camunda_settings_page() {
    ?>
    <div class=&quot;wrap&quot;>
    <h2>Tạo trang cài đặt cho plugin</h2>
    <?php if( isset($_GET['settings-updated']) ) { ?>
        <div id=&quot;message&quot; class=&quot;updated&quot;>
            <p><strong><?php _e('Settings saved.') ?></strong></p>
        </div>
    <?php } ?>
    <form method=&quot;post&quot; action=&quot;options.php&quot;>
        <?php settings_fields( 'mfpd-settings-group' ); ?>
        <table class=&quot;form-table&quot;>
            <tr valign=&quot;top&quot;>
            <th scope=&quot;row&quot;>Tùy chọn cài đặt</th>
            <td><input type=&quot;text&quot; name=&quot;mfpd_option_name&quot; value=&quot;<?php echo get_option('mfpd_option_name'); ?>&quot; /></td>
            </tr>
        </table>
        <?php submit_button(); ?>
    </form>
    </div>
    <?php } ?>