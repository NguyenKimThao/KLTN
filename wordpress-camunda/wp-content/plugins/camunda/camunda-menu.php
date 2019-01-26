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
function echoHTML(){

	$process_selected = getProcessBySelect();
	echo $process_selected->post_content;
}


function camunda_settings_page()
{

	wp_enqueue_style('vendor');
	wp_enqueue_style('body');
	wp_enqueue_script('jquerycamunda');
	wp_enqueue_script('bootstrapcamunda');
	wp_enqueue_style('bootstrap');
    $locations = get_registered_nav_menus();
$menu_locations = get_nav_menu_locations();
$num_locations = count( array_keys( $locations ) );
$nav_menus = wp_get_nav_menus();
$menu_count = count( $nav_menus );



$messages = array();
$locations_screen = ( isset( $_GET['action'] ) && 'locations' == $_GET['action'] ) ? true : false;

if (isset($_GET['action'])) {

    switch ($_GET['action']) {

		case "editform" :
			$data = array();
			wp_update_post( array( 'ID' => $_POST['process'], 'post_content' => $_POST['data'] ) );
            // We send the response
            echo json_encode($data);

			exit();
		}
	}
    ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

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
			<span class="submit-btn"><input type="button" class="button" onclick="SaveDoiTuong()" value="<?php esc_attr_e( 'Save' ); ?>"></span>
			<span class="submit-btn"><input type="button" class="button" onclick="ClearFormmat()" value="<?php esc_attr_e( 'Clear' ); ?>"></span>

		</form>
           
	<?php endif; ?>
	</div>
	<div id="nav-menus-frame" class="wp-clearfix">
        <div id="menu-settings-column" class="metabox-holder">
          <!-- /#menu-settings-column -->
          <div class="form-wrap form-builder container">
            <div class="header"></div>
            <div class="body" style="cursor: auto;">
                <div class="col-xs-9 container-components" id="container-components">
							<?php echo echoHTML() ?>
                </div>
                <div class="col-xs-3 menu-components">
                    <div class="cb-wrap pull-left">
                        <ul id="frmb-1547368915478-control-box" class="frmb-control ui-sortable">
                            <li class="icon-button input-control input-control-1 ui-sortable-handle" data-type="button" id="button"
                                onclick="CreateModal('button')"><span>Button</span></li>
                            <li class="icon-select input-control input-control-5 ui-sortable-handle" data-type="select" id="select"
                                onclick="CreateModal('select')"><span>Select</span></li>
                            <li class="icon-text input-control input-control-9 ui-sortable-handle" data-type="text" id="text"
                                onclick="CreateModal('textfield')"><span>Text Field</span></li>
                            <li class="icon-date input-control input-control-11 ui-sortable-handle" data-type="date" id="date" style="display: none;"
                                onclick="CreateModal('datefield')"><span>Date Field</span></li>
                            <li class="icon-radio-group input-control input-control-7 ui-sortable-handle" data-type="radio-group" style="display: none;"
                                id="radio-group" onclick="CreateModal('radiogroup')"><span>Radio Group</span></li>
                            <li class="icon-checkbox-group input-control input-control-6 ui-sortable-handle" data-type="checkbox-group" style="display: none;"
                                id="checkbox-group" onclick="CreateModal('checkboxgroup')"><span>Checkbox Group</span></li>
                            <li class="icon-number input-control input-control-12 ui-sortable-handle" data-type="number" id="number" style="display: none;"
                                onclick="CreateModal('number')"><span>Number</span></li>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-default margin-save-btn save">Save Form</button>
                </div>
            </div>
            <div class="footer">
            </div>
            <div class="modal fade item-tmp" role="dialog" style="overflow: auto;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Detail Components</h4>
                        </div>
                        <div class="modal-body">
                            <div class="frmb stage-wrap ui-sortable">
                                <div class="frm-holder" data-field-id="frmb-1547363610495-fld-1" style="display: block;">
                                    <div class="form-elements">
                                        <div class="form-group required-wrap" style="display: none">
                                            <label for="required-frmb">Required</label>
                                            <div class="input-wrap">
                                                <input type="checkbox" class="fld-required" name="required" value="" id="required-wrap"
                                                    onclick="SetValueForRequired(this)">
                                            </div>
                                        </div>
                                        <div class="form-group label-wrap" style="display: none">
                                            <label for="label-frmb">Label</label>
                                            <div class="input-wrap">
                                                <input name="label" placeholder="Label" class="fld-label form-control" id="label-wrap"
                                                    contenteditable="true" value="">
                                            </div>
                                        </div>
                                        <div class="form-group subtype-wrap type-button" style="display: none">
                                            <label for="type-button">Type Button</label>
                                            <div class="input-wrap">
                                                <select id="type-button" name="type-button" class="fld-subtype form-control">
                                                    <option label="Button" value="button">Button</option>
                                                    <option label="Submit" value="submit">Submit</option>
                                                    <option label="Reset" value="reset">Reset</option>
                                                </select></div>
                                        </div>
                                        <div class="form-group style-wrap" style="display: none">
                                            <label>Style</label>
                                            <input value="default" type="hidden" class="btn-style">
                                            <div class="input-wrap" id="style-wrap" value="">
                                                <!-- <select id="style-wrap" name="type-button" class="fld-subtype form-control">
                                                    <option value="danger">danger</option>
                                                    <option value="info">info</option>
                                                    <option value="primary">primary</option>
                                                    <option value="info">info</option>
                                                    <option value="primary">primary</option>
                                                </select> -->
                                                <button value="default" type="button" class="btn-xs btn btn-default"
                                                    onclick="SetValueForStyle(this)">Default</button><button value="danger"
                                                    type="button" class="btn-xs btn btn-danger " onclick="SetValueForStyle(this)">Danger</button><button
                                                    value="info" type="button" class="btn-xs btn btn-info" onclick="SetValueForStyle(this)">Info</button><button
                                                    value="primary" type="button" class="btn-xs btn btn-primary" onclick="SetValueForStyle(this)">Primary</button><button
                                                    value="success" type="button" class="btn-xs btn btn-success" onclick="SetValueForStyle(this)">Success</button><button
                                                    value="warning" type="button" class="btn-xs btn btn-warning" onclick="SetValueForStyle(this)">Warning</button>
                                            </div>
                                        </div>
                                        <div class="form-group description-wrap" style="display: none">
                                            <label for="description-frmb">Help Text</label>
                                            <div class="input-wrap"><input name="description" placeholder="" class="fld-description form-control"
                                                    id="description-wrap" value="" type="text"></div>
                                        </div>
                                        <div class="form-group toggle-wrap" style="display: none">
                                            <label for="toggle-frmb">Toggle</label>
                                            <div class="input-wrap">
                                                <input type="checkbox" class="fld-toggle" name="toggle" id="toggle-wrap"></div>
                                        </div>
                                        <div class="form-group inline-wrap" style="display: none">
                                            <label for="inline-frmb">Inline</label>
                                            <div class="input-wrap">
                                                <input type="checkbox" class="fld-inline" name="inline" id="inline-wrap">
                                                <label for="inline-frmb">Display
                                                    checkbox inline</label></div>
                                        </div>
                                        <div class="form-group other-wrap" style="display: none">
                                            <label for="other-frmb">Enable</label>
                                            <div class="input-wrap">
                                                <input type="checkbox" class="fld-other" name="other" id="other-wrap">
                                                <label for="other-frmb">Let users to enter an unlisted
                                                    option</label></div>
                                        </div>
                                        <div class="form-group placeholder-wrap" style="display: none">
                                            <label for="placeholder-frmb">Placeholder</label>
                                            <div class="input-wrap">
                                                <input name="placeholder" placeholder="" class="fld-placeholder form-control"
                                                    id="placeholder-wrap" value="" type="text"></div>
                                        </div>
                                        <div class="form-group className-wrap" style="display: none">
                                            <label for="className-frmb">Class</label>
                                            <div class="input-wrap"><input name="className" placeholder="" class="fld-className form-control"
                                                    id="className-wrap" type="text"></div>
                                        </div>
                                        <div class="form-group name-wrap" style="display: none">
                                            <label for="name-frmb">Name</label>
                                            <div class="input-wrap"><input name="name" placeholder="" class="fld-name form-control"
                                                    id="name-wrap" value="" type="text"></div>
                                        </div>
                                        <div class="form-group camunda-name-wrap" style="display: none">
                                          <label for="camunda-name">camunda-name</label>
                                          <div class="input-wrap">
                                              <select id="camunda-name-wrap" name="camunda-name" class="fld-subtype form-control">
                                                  <option label="Button" value="button">Button</option>
                                                  <option label="Submit" value="submit">Submit</option>
                                                  <option label="Reset" value="reset">Reset</option>
                                              </select></div>
                                      </div>
                                        <div class="form-group access-wrap" style="display: none">
                                            <label for="access-frmb">Access</label>
                                            <div class="input-wrap">
                                                <input type="checkbox" class="fld-access" name="access" id="access-wrap"
                                                    value="">
                                                <label for="access-frmb">Limit access to one or more of the following
                                                    roles:</label>
                                                <div class="available-roles" style="display: none;">
                                                    <label for="fld-frmb">
                                                        <input type="checkbox" name="roles[]" value="1" id="available-roles"
                                                            class="roles-field"> Administrator</label></div>
                                            </div>
                                        </div>
                                        <div class="form-group multiple-wrap" style="display: none">
                                            <label for="multiple-frmb"></label>
                                            <div class="input-wrap">
                                                <input type="checkbox" class="fld-multiple" name="multiple" id="multiple-wrap">
                                                <label for="multiple-frmb">Allow Multiple Selections</label></div>
                                        </div>
                                        <div class="form-group value-wrap" style="display: none">
                                            <label for="value-frmb">Value</label>
                                            <div class="input-wrap"><input name="value" placeholder="Value" class="fld-value form-control"
                                                    id="value-wrap" value="" type="text"></div>
                                        </div>
                                        <div class="form-group min-wrap" style="display: none"><label for="min-frmb8">min</label>
                                            <div class="input-wrap"><input type="number" name="min" min="0" class="fld-min form-control form-control"
                                                    id="min-wrap"></div>
                                        </div>
                                        <div class="form-group max-wrap" style="display: none"><label for="max-frmb">max</label>
                                            <div class="input-wrap"><input type="number" name="max" min="0" class="fld-max form-control form-control"
                                                    id="max-wrap"></div>
                                        </div>
                                        <div class="form-group step-wrap" style="display: none"><label for="step-frmb">step</label>
                                            <div class="input-wrap"><input type="number" name="step" min="0" class="fld-step form-control form-control"
                                                    id="sstep-wrap"></div>
                                        </div>
                                        <div class="form-group subtype-wrap type-input" style="display: none">
                                            <label for="type-input">Type
                                                Input</label>
                                            <div class="input-wrap"><select id="type-input" name="subttype-inputype" class="fld-subtype form-control">
                                                    <option label="Text Field" value="text">Text field</option>
                                                    <option label="Password" value="password">Password</option>
                                                    <option label="Email" value="email">Email</option>
                                                    <option label="Color" value="color">Color</option>
                                                    <option label="Tel" value="tel">Tel</option>
                                                </select></div>
                                        </div>
                                        <div class="form-group maxlength-wrap" style="display: none">
                                            <label for="maxlength-frmb">Max Length</label>
                                            <div class="input-wrap">
                                                <input type="number" name="maxlength" min="0" class="fld-maxlength form-control form-control"
                                                    id="maxlength-wrap"></div>
                                        </div>
                                        <div class="form-group field-options" style="display: none">
                                            <label class="false-label">Options</label>
                                            <div class="sortable-options-wrap">
                                                <ol class="container-options sortable-options ui-sortable">
                                                    <li class="ui-sortable-handle itemp-tmp-option" style="display: none"><input
                                                            type="radio" class="option-selected" value="false" name="autocomplete-1547363612799-option"
                                                            placeholder=""><input type="text" class="option-label" value="Option 1"
                                                            name="autocomplete-1547363612799-option" placeholder=""><input
                                                            type="text" class="option-value" value="option-1" name="autocomplete-1547363612799-option"
                                                            placeholder=""><a class="remove btn icon-cancel" title="Remove Element"></a></li>
                                                    <li class="ui-sortable-handle"><input type="radio" class="option-selected"
                                                            value="false" name="autocomplete-1547363612799-option"
                                                            placeholder=""><input type="text" class="option-label" value="Option 1"
                                                            name="autocomplete-1547363612799-option" placeholder=""><input
                                                            type="text" class="option-value" value="option-1" name="autocomplete-1547363612799-option"
                                                            placeholder=""><a class="remove btn icon-cancel" title="Remove Element"></a></li>
                                                    <li class="ui-sortable-handle"><input type="radio" class="option-selected"
                                                            value="false" name="autocomplete-1547363612799-option"
                                                            placeholder=""><input type="text" class="option-label" value="Option 2"
                                                            name="autocomplete-1547363612799-option" placeholder=""><input
                                                            type="text" class="option-value" value="option-2" name="autocomplete-1547363612799-option"
                                                            placeholder=""><a class="remove btn icon-cancel" title="Remove Element"></a></li>
                                                    <li class="ui-sortable-handle"><input type="radio" class="option-selected"
                                                            value="false" name="autocomplete-1547363612799-option"
                                                            placeholder=""><input type="text" class="option-label" value="Option 3"
                                                            name="autocomplete-1547363612799-option" placeholder=""><input
                                                            type="text" class="option-value" value="option-3" name="autocomplete-1547363612799-option"
                                                            placeholder=""><a class="remove btn icon-cancel" title="Remove Element"></a></li>
                                                </ol>
                                                <div class="option-actions"><a class="add add-opt">Add Option +</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="create" type="button" class="btn btn-default" data-dismiss="modal" style="display: block;">Create</button>
                            <button class="edit" type="button" class="btn btn-default" data-dismiss="modal" style="display: none;">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		
          <!-- /#nav-menus-frame -->
        </div>

        <div class="clear"></div>
      </div>
	</div>
	</div>

	<script>
				$(".help-block").addClass('hidden');
				$(".has-error").addClass('hidden');
  
	function SaveDoiTuong(){
			var contai=$("#container-components");
			var url="admin.php?page=wordpress-camunda&action=editform&process="+$("#select-menu-to-edit").val();
					$.post(url,
				{
					data: contai.html(),
					process: $("#select-menu-to-edit").val()
				},
				function(data, status){
					alert("Data: " + data + "\nStatus: " + status);
				});
	}
	function ClearFormmat()
		{
			var myNode = document.getElementById("container-components");
			myNode.innerHTML = '';
		}
	</script>
	<script>
	
	var obj = {
        "button": "label-wrap,type-button,style-wrap,className-wrap,name-wrap,value-wrap",
        "textfield": "label-wrap,placeholder-wrap,className-wrap,name-wrap,camunda-name-wrap,value-wrap,type-input,maxlength-wrap",
        "select": "label-wrap,placeholder-wrap,className-wrap,name-wrap,camunda-name-wrap",
        "datefield": "required-wrap,label-wrap,description-wrap,placeholder-wrap,className-wrap,name-wrap,camunda-name-wrap,access-wrap,value-wrap",
        "radiogroup": "required-wrap,label-wrap,description-wrap,inline-wrap,className-wrap,name-wrap,camunda-name-wrap,access-wrap,other-wrap,field-options",
        "checkboxgroup": "required-wrap,label-wrap,description-wrap,toggle-wrap,inline-wrap,className-wrap,name-wrap,camunda-name-wrap,access-wrap,other-wrap,field-options",
        "number": "required-wrap,label-wrap,description-wrap,placeholder-wrap,className-wrap,name-wrap,camunda-name-wrap,access-wrap,value-wrap,min-wrap,max-wrap,step-wrap"
    };
  var str = JSON.stringify(obj);
  var listControl = JSON.parse(str);
  var page = $(".container");
  var modalTmp = page.find(".modal.fade");
  var modalDetail = null, modalFooter = null;
  var listValue = [];
  var thisComponents = "";
  var modal, code;
  $(document).ready(function () {
	  page.initialize();
	  $("#wpfooter").addClass('hidden');
  });
  page.initialize = function () {
      page.detail = page.find(".body");
      page.detail.find(".item-tmp-box").on("mouseenter", TurnOnLightBorder);
      page.detail.find(".item-tmp-box").on("mouseleave", TurnOffLightBorder);
      page.detail.find(".save").on("click",page.SaveFrom);
  };
  page.SaveFrom = function() {
      var form = page.find(".menu-components").html();
  }
  TurnOnLightBorder = function () {
      $(this).css('border-color', '#66afe9');
      $(this).css('box-shadow', 'inset 0 1px 1px rgba(0,0,0,.1), 0 0 8px rgba(102,175,233,.6)');
  }
  TurnOffLightBorder = function () {
      $(this).css('border', 'none');
      $(this).css('box-shadow', 'none');
  }
  CreateModal = function (str) {
      thisComponents = str;
      var d = new Date();
      code = d.yyyymmddhhmmss();
      modal = modalTmp.clone();
      modal.removeClass("item-tmp");
      modal.attr("id", code);
      modal.appendTo(page);
      initialize();
      Show();

  };
  Show = function () {
      //modal.Clear();
      HideFiled();
      ShowFiled();
      modal.addClass("in");
      modal.css("display", "block");
      // modal.modal({ backdrop: 'static',
      //     keyboard: false });
  }
  initialize = function () {
      modalDetail = modal.find(".modal-body");
      modalDetail.find(".add-opt").on("click", modal.AddOption);
      modalDetail.find(".container-options .icon-cancel").on("click", modal.RemoveOption);
      modalFooter = modal.find(".modal-footer");
      modalFooter.find(".create").on("click", Create);
      modalFooter.find(".edit").on("click", Edit);
  };
  Clear = function () {
      var input = modal.find("input")
          .val('')
          .end()
          .find("input[type=checkbox], input[type=radio]")
          .prop("checked", "")
          .end();
      var select = modal.find("select :nth-child(1)").prop('selected', true);
  };
  Create = function () {
      var arr = listControl[thisComponents].split(',');
      listValue.length = 0;
      for (var item in arr) {
          listValue.push(modal.find("#" + arr[item]).val());
      }
      BindFiled();
      modal.removeClass("in");
      modal.css("display", "none");
      modal.find(".create").css("display", "none");
      modal.find(".edit").css("display", "block");
  };
  Edit = function () {
      var componentOld = page.find("#"+thisComponents + "-" + code).html();
      var parent = document.getElementById(thisComponents + "-" + code);
      var componentNew = "";
      var arr = listControl[thisComponents].split(',');
      listValue.length = 0;
      for (var item in arr) {
          listValue.push(modal.find("#" + arr[item]).val());
      }
      if (thisComponents == "button") {
          componentNew = `<div class="prev-holder">
                              <div class="fb-button form-group field-button-1547442090077-preview">
                                  <div class="field-actions">
                                      <a type="button" class="del-button btn icon-cancel edit-confirm" value="` + code + `"></a>
                                      <a type="button" class="del-button btn icon-cancel delete-confirm" value="` + code + `"></a>
                                  </div>
                                  <button name="`+ listValue[4] + `" access="` + listValue[6] + `" class=" btn-` + listValue[2] + " " + listValue[3] + `" value="` + listValue[5] + `" type="` + listValue[1] + `" class="btn-default btn">` + listValue[0] + `</button>
                              </div>
                          </div>`
      }
      else if (thisComponents == "textfield") {
            componentNew = `<div class="prev-holder">
                                <div>
                                    <div class="field-actions">
                                        <a type="textfield" class="del-button btn icon-cancel edit-confirm" value="` + code + `"></a>
                                        <a type="textfield" class="del-button btn icon-cancel delete-confirm" value="` + code + `"></a>
                                    </div>
                                    <label for="text-1547442003735-preview" class="fb-text-label">`+ listValue[0] + `</label>
                                </div>
                                <div class="fb-text form-group field-text-1547453257239-preview">
                                    <input placeholder="` + listValue[1] + `" class= "form-control ` + listValue[2] + `" name="` + listValue[3] + `" value="` + listValue[5] + `"
                                                maxlength="`+ listValue[7] + `" type="` + listValue[6] + `" id="text-1547453257239-preview" camunda-name="`+listValue[4]+`" title="">
                                </div>
                            </div>`
        }
      parent.innerHTML = componentNew;
      page.detail.find(".item-tmp-box").on("mouseenter", TurnOnLightBorder);
      page.detail.find(".item-tmp-box").on("mouseleave", TurnOffLightBorder);
      page.detail.find(".edit-confirm").on("click", page.EditComponent);
      page.detail.find(".delete-confirm").on("click", page.DeleteComponent);
      modal.removeClass("in");
      modal.css("display", "none");
  };
  ShowFiled = function () {
      var arr = listControl[thisComponents].split(',');
      for (var item in arr) {
          // var dt = arr[item].split(':');
          // modal.find("." + dt[0]).css("display", "block");
          // model.find('#'+dt[0]).val('tếdt');
          if (modal.find("." + arr[item]).length > 0) {
              modal.find("." + arr[item]).css("display", "block");
          }
          // model.find('#'+arr[item]).val('tếdt');
      }

  };
  HideFiled = function () {
      var temp = modal.find(".form-group").css("display", "none");
  };
  AddOption = function () {
      var fieldOptions = modalDetail.find(".container-options");
      var newOption = fieldOptions.find(".itemp-tmp-option").clone();
      newOption.removeClass("itemp-tmp-option")
          .css("display", "block")
          .appendTo(fieldOptions)
          .find(".icon-cancel")
          .on("click", modal.RemoveOption);
  };
  RemoveOption = function () {
      this.parentElement.remove();
  };
  BindFiled = function () {
      var d = new Date();
      var html = "";
      if (thisComponents == "button") { 
          html = `<div class="box-button item-tmp-box button-style" id = "` + thisComponents + `-` + code + `" ondrop="drop(event)" ondragover="allowDrop(event)">
                      <div class="prev-holder" >
                          <div class="fb-button form-group field-button-1547442090077-preview" id="`+d.getTime()+`" ondragstart="drag(event, `+d.getTime()+`)">
                              <div class="field-actions">
                                  <a type="button" class="del-button btn icon-cancel edit-confirm" value="` + code + `"></a>
                                  <a type="button" class="del-button btn icon-cancel delete-confirm" value="` + code + `"></a>
                              </div>
                              <button name="`+ listValue[4] + `" access="` + listValue[6] + `" class=" btn-` + listValue[2] + " " + listValue[3] + `" value="` + listValue[5] + `" type="` + listValue[1] + `" class="btn-default btn">` + listValue[0] + `</button>
                          </div>
                      </div>
               </div>`
      }
      else if (thisComponents == "textfield") {
          html = `<div class="box item-tmp-box" id = "` + thisComponents + `-` + code + `"  ondrop="drop(event)" ondragover="allowDrop(event)">
                      <div class="prev-holder"  id = "` + code + `" ondragstart="drag(event, ` + code + `)">
                          <div>
                              <div class="field-actions">
                                  <a type="textfield" class="del-button btn icon-cancel edit-confirm" value="` + code + `"></a>
                                  <a type="textfield" class="del-button btn icon-cancel delete-confirm" value="` + code + `"></a>
                              </div>
                              <label for="text-1547442003735-preview" class="fb-text-label">`+ listValue[0] + `</label>
                          </div>
                          <div class="fb-text form-group field-text-1547453257239-preview">
                            <input placeholder="` + listValue[1] + `" class= "form-control ` + listValue[2] + `" name="` + listValue[3] + `" value="` + listValue[5] + `"
                                                maxlength="`+ listValue[7] + `" type="` + listValue[6] + `" id="text-1547453257239-preview" camunda-name="`+listValue[4]+`" title="">
                          </div>
                      </div>
                  </div>`
      }
      var temp = document.getElementById("container-components");
      temp.innerHTML = temp.innerHTML + html;
      page.detail.find(".item-tmp-box").on("mouseenter", TurnOnLightBorder);
      page.detail.find(".item-tmp-box").on("mouseleave", TurnOffLightBorder);
      page.detail.find(".edit-confirm").on("click", page.EditComponent);
      page.detail.find(".delete-confirm").on("click", page.DeleteComponent);
  };
  page.EditComponent = function () {
      code = this.getAttribute("value");
      thisComponents = this.getAttribute("type");
      modal = page.find("#" + code);
      Show(modal);
  };
  page.DeleteComponent = function () {
      code = this.getAttribute("value");
      thisComponents = this.getAttribute("type");
      page.find("#" + thisComponents + "-" + code).remove();
      page.find("#" + code).remove();
  };
  SetValueForStyle = function (e) {
      modalDetail.find("#style-wrap").val(e.value);
  };
  SetValueForRequired = function (e) {
      if (e.checked) {
          modalDetail.find("#required-wrap").val(`<span class="fb-required">*</span>`);
      }
      else {
          modalDetail.find("#required-wrap").val("");
      }
  };
  Date.prototype.yyyymmddhhmmss = function () {
      var yyyy = this.getFullYear();
      var mm = this.getMonth() < 9 ? "0" + (this.getMonth() + 1) : (this.getMonth() + 1); // getMonth() is zero-based
      var dd = this.getDate() < 10 ? "0" + this.getDate() : this.getDate();
      var hh = this.getHours() < 10 ? "0" + this.getHours() : this.getHours();
      var min = this.getMinutes() < 10 ? "0" + this.getMinutes() : this.getMinutes();
      var ss = this.getSeconds() < 10 ? "0" + this.getSeconds() : this.getSeconds();
      return "".concat(yyyy).concat(mm).concat(dd).concat(hh).concat(min).concat(ss);
  };
  function allowDrop(ev) {
      ev.preventDefault();
    }

    function drag(ev,id) {
      ev.dataTransfer.setData("text", id);
    }

function drop_handler(ev) {
  ev.preventDefault();
  // Get the id of the target and add the moved element to the target's DOM
  var data = ev.dataTransfer.getData("text");
  ev.target.appendChild(document.getElementById(data));
}
    function drop(ev) {
      // var name = ev.target.id.substring(0,4)
      // //(document.getElementById(ev.target.id).firstElementChild
      // if (name == "drag" ) {
        dropwap(ev);
      // } else {
      //   ev.preventDefault();
      //   var data = ev.dataTransfer.getData("text");
      //   ev.target.appendChild(document.getElementById(data));
      //   isNotNull = false;
      // }
    }
    function dropwap(ev) {
      // ev.preventDefault();
      // var string = ev.dataTransfer.getData("text");
      // var src = createElementFromHTML(string);
      var src = document.getElementById(ev.dataTransfer.getData("text"));
      var srcParent = src.parentNode;
      console.log(src)
      var tgt = ev.currentTarget.firstElementChild;
      console.log(tgt);
      var tgtParent = tgt.parentNode;
      // console.log(srcParent, tgt, src)
      srcParent.replaceChild(tgt, src);

      // console.log(tgtParent, src, tgt)
      // tgtParent.appendChild(src);
      // tgt.replaceWith(src);
      // src.replaceWith(tgt);
      tgtParent.appendChild(src);
    }

</script>
    <?php
     endif;
    ?>
    </div>
    <?php
}
?>