<?php
require_once( ABSPATH . 'wp-admin/admin.php' );

// Load all the nav menu interface functions
require_once( ABSPATH . 'wp-admin/includes/nav-menu.php' );







wp_nav_menu_setup();
wp_initial_nav_menu_meta_boxes();
$action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : 'edit';
$process_selected_id = isset( $_REQUEST['process'] ) ? (int) $_REQUEST['process'] : 0;
$process_selected=null;

function getProcessBySelect()
{
	$process_selected_id = isset( $_REQUEST['process'] ) ? (int) $_REQUEST['process'] : 0;
	$per_page = 50;
	$args = array(
		'offset' => 0,
		'order' => 'ASC',
		'orderby' => 'title',
		'posts_per_page' => 50,
		'post_type' => 'process',
		'suppress_filters' => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false
	);
	if ( isset( $box['args']->_default_query ) )
		$args = array_merge($args, (array) $box['args']->_default_query );
	$get_processes = new WP_Query;
	$processes = $get_processes->query( $args );
	if ( ! $get_processes->post_count ) {
		return null;
	}
	foreach ( $processes as $process ) {
		if($process_selected_id==$process->ID)
		return $process;
	}
	return null;
}

function wp_nav_menu_item_process_type_meta_box($box) {
	$post_type_name = $box['args']->name;
	$process_selected_id = isset( $_REQUEST['process'] ) ? (int) $_REQUEST['process'] : 0;

	// Paginate browsing for large numbers of post objects.
	$per_page = 50;
	$pagenum = isset( $_REQUEST[$post_type_name . '-tab'] ) && isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : 1;
	$offset = 0 < $pagenum ? $per_page * ( $pagenum - 1 ) : 0;

	$args = array(
		'offset' => $offset,
		'order' => 'ASC',
		'orderby' => 'title',
		'posts_per_page' => $per_page,
		'post_type' => $post_type_name,
		'suppress_filters' => true,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false
	);
	if ( isset( $box['args']->_default_query ) )
		$args = array_merge($args, (array) $box['args']->_default_query );
	$get_processes = new WP_Query;
	$processes = $get_processes->query( $args );
	if ( ! $get_processes->post_count ) {
		return;
	}
	foreach ( $processes as $process ) {
		if($process_selected_id==0||$process->ID== $process_selected_id)
			$process_selected_id=$process->ID;
			$process_selected=$process;
		?>

	<option value="<?php echo esc_attr( $process->ID ); ?>" <?php selected( $process->ID, $process_selected_id ); ?>>
		<?php echo esc_html($process->post_title) ?>
		<?php echo ' ('.  esc_html($process->post_name) . ')' ;?>	
	</option>
	<?php
	}
}

function wp_get_processes($context)
{
	global $wp_meta_boxes;

	if ( empty( $screen ) )
		$screen = get_current_screen();
	elseif ( is_string( $screen ) )
		$screen = convert_to_screen( $screen );
	$page ='nav-menus';
	$first_open = false;
	
	if ( isset( $wp_meta_boxes[$page][ $context ] ) ) {

		foreach ( array( 'high', 'core', 'default', 'low' ) as $priority ) {
			if ( isset( $wp_meta_boxes[ $page ][ $context ][ $priority ] ) ) {
				foreach ( $wp_meta_boxes[ $page ][ $context ][ $priority ] as $box ) {
					if ( false == $box || ! $box['title'] )
						continue;
					if($box['args']->name=='process')
					{
						wp_nav_menu_item_process_type_meta_box($box);
					}
					?>
					<?php
				}
			}
		}
	}
}


function camunda_settings_page()
{

    $locations = get_registered_nav_menus();
$menu_locations = get_nav_menu_locations();
$num_locations = count( array_keys( $locations ) );
$nav_menus = wp_get_nav_menus();
$menu_count = count( $nav_menus );



$messages = array();
$locations_screen = ( isset( $_GET['action'] ) && 'locations' == $_GET['action'] ) ? true : false;

    ?>
    <div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( __( 'Process' ) ); ?></h1>
    
    <?php
	if ( current_user_can( 'customize' ) ) :
		$focus = $locations_screen ? array( 'section' => 'menu_locations' ) : array( 'panel' => 'nav_menus' );
		printf(
			' <a class="page-title-action hide-if-no-customize" href="%1$s">%2$s</a>',
			esc_url( add_query_arg( array(
				array( 'autofocus' => $focus ),
				'return' => urlencode( remove_query_arg( wp_removable_query_args(), wp_unslash( $_SERVER['REQUEST_URI'] ) ) ),
			), admin_url( 'customize.php' ) ) ),
			__( 'Manage with Live Preview' )
		);
	endif;
	?>
	<hr class="wp-header-end">
	<h2 class="nav-tab-wrapper wp-clearfix">
		<a href="<?php echo admin_url( 'admin.php' ) .'?page=wordpress-camunda'; ?>" class="nav-tab<?php if ( ! isset( $_GET['action'] ) || isset( $_GET['action'] ) && 'locations' != $_GET['action'] ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Chỉnh sửa Process' ); ?></a>
        <?php if ( $num_locations && $menu_count ) : ?>
			<a href="<?php echo esc_url( add_query_arg( array( 'action' => 'locations' ), admin_url( 'admin.php' ) .'?page=wordpress-camunda' ) ); ?>" class="nav-tab<?php if ( $locations_screen ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Quản Lý Process' ); ?></a>
		<?php
			endif;
		?>
	</h2>

    <?php
	foreach ( $messages as $message ) :
		echo $message . "\n";
	endforeach;
	?>
    <?php
        if ( $locations_screen ):
            if ( 1 == $num_locations ) {
                echo '<p>' . __( 'Your theme supports one menu. Select which menu you would like to use.' ) . '</p>';
            } else {
                echo '<p>' .  sprintf( _n( 'Your theme supports %s menu. Select which menu appears in each location.', 'Your theme supports %s menus. Select which menu appears in each location.', $num_locations ), number_format_i18n( $num_locations ) ) . '</p>';
            }
	?>
            <div id="menu-locations-wrap">
                <form method="post" action="<?php echo esc_url( add_query_arg( array( 'action' => 'locations' ), admin_url( 'nav-menus.php' ) ) ); ?>">
                    <table class="widefat fixed" id="menu-locations-table">
                        <thead>
                        <tr>
                            <th scope="col" class="manage-column column-locations"><?php _e( 'Theme Location' ); ?></th>
                            <th scope="col" class="manage-column column-menus"><?php _e( 'Assigned Menu' ); ?></th>
                        </tr>
                        </thead>
                        <tbody class="menu-locations">
                        <?php foreach ( $locations as $_location => $_name ) { ?>
                            <tr class="menu-locations-row">
                                <td class="menu-location-title"><label for="locations-<?php echo $_location; ?>"><?php echo $_name; ?></label></td>
                                <td class="menu-location-menus">
                                    <select name="menu-locations[<?php echo $_location; ?>]" id="locations-<?php echo $_location; ?>">
                                        <option value="0"><?php printf( '&mdash; %s &mdash;', esc_html__( 'Select a Menu' ) ); ?></option>
                                        <?php foreach ( $nav_menus as $menu ) : ?>
                                            <?php $selected = isset( $menu_locations[$_location] ) && $menu_locations[$_location] == $menu->term_id; ?>
                                            <option <?php if ( $selected ) echo 'data-orig="true"'; ?> <?php selected( $selected ); ?> value="<?php echo $menu->term_id; ?>">
                                                <?php echo wp_html_excerpt( $menu->name, 40, '&hellip;' ); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="locations-row-links">
                                        <?php if ( isset( $menu_locations[ $_location ] ) && 0 != $menu_locations[ $_location ] ) : ?>
                                        <span class="locations-edit-menu-link">
                                            <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'menu' => $menu_locations[$_location] ), admin_url( 'nav-menus.php' ) ) ); ?>">
                                                <span aria-hidden="true"><?php _ex( 'Edit', 'menu' ); ?></span><span class="screen-reader-text"><?php _e( 'Edit selected menu' ); ?></span>
                                            </a>
                                        </span>
                                        <?php endif; ?>
                                        <span class="locations-add-menu-link">
                                            <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'edit', 'menu' => 0, 'use-location' => $_location ), admin_url( 'nav-menus.php' ) ) ); ?>">
                                                <?php _ex( 'Use new menu', 'menu' ); ?>
                                            </a>
                                        </span>
                                    </div><!-- .locations-row-links -->
                                </td><!-- .menu-location-menus -->
                            </tr><!-- .menu-locations-row -->
                        <?php } // foreach ?>
                        </tbody>
                    </table>
                    <p class="button-controls wp-clearfix"><?php submit_button( __( 'Save Changes' ), 'primary left', 'nav-menu-locations', false ); ?></p>
                    <?php wp_nonce_field( 'save-menu-locations' ); ?>
                    <input type="hidden" name="menu" id="nav-menu-meta-object-id" value="<?php echo esc_attr( $nav_menu_selected_id ); ?>" />
                </form>
            </div><!-- #menu-locations-wrap -->
    <?php
	do_action( 'after_menu_locations_table' ); ?>
      <?php else: ?>

          	<div class="manage-menus">
              <?php if ( $menu_count < 2 ) : ?>
              <span class="add-edit-menu-action">
			<?php printf( __( 'Edit your menu below, or <a href="%s">create a new menu</a>.' ), esc_url( add_query_arg( array( 'action' => 'edit', 'menu' => 0 ), admin_url( 'nav-menus.php' ) ) ) ); ?>
		</span><!-- /add-edit-menu-action -->
		<?php else : ?>
			<form method="get" action="<?php echo admin_url( 'admin.php' ); ?>">
			<input type="hidden" name="page" value="wordpress-camunda" />
			<input type="hidden" name="action" value="edit" />
			<label for="select-menu-to-edit" class="selected-menu"><?php _e( 'Select a process to edit:' ); ?></label>
            <select name="process" id="select-menu-to-edit">
							<?php wp_get_processes('side'); ?>

			</select>
			<span class="submit-btn"><input type="submit" class="button" value="<?php esc_attr_e( 'Select' ); ?>"></span>
			<span class="submit-btn"><input type="button" class="button" onclick="ClearFormmat()" value="<?php esc_attr_e( 'Clear' ); ?>"></span>

		</form>
           
	<?php endif; ?>
	</div>
	<div id="nav-menus-frame" class="wp-clearfix">
	<div id="menu-settings-column" class="metabox-holder<?php if ( isset( $_GET['menu'] ) && '0' == $_GET['menu'] ) { echo ' metabox-holder-disabled'; } ?>">

		Show control

	</div><!-- /#menu-settings-column -->
	<div id="menu-management-liquid">
	<div id="renderHTML">
			Render

	<!-- /#menu-management-liquid -->
	</div><!-- /#nav-menus-frame -->
	</div>

	<script>
		function ClearFormmat()
		{
			var myNode = document.getElementById("renderHTML");
myNode.innerHTML = '';
		}
	</script>
    <?php
     endif;
    ?>
    </div>
    <?php
}
function echoHTML(){

	$process_selected = getProcessBySelect();
	echo $process_selected->post_content;
}
?>