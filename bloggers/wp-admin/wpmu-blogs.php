<?php
require_once('admin.php');

$title = __('WordPress MU &rsaquo; Admin &rsaquo; Blogs');
$parent_file = 'wpmu-admin.php';

wp_enqueue_script( 'admin-forms' );

require_once('admin-header.php');
if( is_site_admin() == false ) {
    wp_die( __('You do not have permission to access this page.') );
}
$id = intval( $_GET['id'] );

if ( $_GET['updated'] == 'true' ) {
	?>
	<div id="message" class="updated fade"><p>
		<?php
		switch ($_GET['action']) {
			case 'all_notspam':
				_e('Blogs mark as not spam !');
			break;
			case 'all_spam':
				_e('Blogs mark as spam !');
			break;
			case 'all_delete':
				_e('Blogs deleted !');
			break;
			case 'delete':
				_e('Blog deleted !');
			break;
			case 'add-blog':
				_e('Blog added !');
			break;
			case 'archive':
				_e('Blog archived !');
			break;
			case 'unarchive':
				_e('Blog unarchived !');
			break;
			case 'activate':
				_e('Blog activated !');
			break;
			case 'deactivate':
				_e('Blog deactivated !');
			break;
			case 'unspam':
				_e('Blog mark as not spam !');
			break;
			case 'spam':
				_e('Blog mark as spam !');
			break;
			case 'umature':
				_e('Blog mark as not mature !');
			break;
			case 'mature':
				_e('Blog mark as mature !');
			break;
			default:
				_e('Options saved !');
			break;
		}
		?>
	</p></div>
	<?php
}

switch( $_GET['action'] ) {
	// Edit blog
	case "editblog":
		$options = $wpdb->get_results( "SELECT * FROM {$wpdb->base_prefix}{$id}_options WHERE option_name NOT LIKE 'rss%' AND option_name NOT LIKE '%user_roles'", ARRAY_A );
		$details = $wpdb->get_row( "SELECT * FROM {$wpdb->blogs} WHERE blog_id = '{$id}'", ARRAY_A );
		$editblog_roles = get_blog_option( $id, "{$wpdb->base_prefix}{$id}_user_roles" );
		?>
		<div class="wrap">
		<h2><?php _e('Edit Blog'); ?> - <a href='http://<?php echo $details['domain'].$details['path']; ?>'><?php echo $details['domain'].$details['path']; ?></a></h2>		
		<form method="post" action="wpmu-edit.php?action=updateblog"> 
			<?php wp_nonce_field('editblog'); ?>
			<input type="hidden" name="id" value="<?php echo $id ?>" /> 
			<table>
			<tr>
				<td valign="top">
					<div class="wrap">
						<h3><?php _e('Blog info (wp_blogs)'); ?></h3>
						<table class="form-table">
							<tr class="form-field form-required">
								<th scope="row"><?php _e('Domain') ?></th> 
								<td>http://<input name="blog[domain]" type="text" id="domain" value="<?php echo $details['domain'] ?>" size="33" /></td>
							</tr> 
							<tr class="form-field form-required">
								<th scope="row"><?php _e('Path') ?></th> 
								<td><input name="blog[path]" type="text" id="path" value="<?php echo $details['path'] ?>" size="40" />
								<br />(<?php _e( 'siteurl and home will be modified too' ); ?>)</td> 
							</tr> 
							<tr class="form-field">
								<th scope="row"><?php _e('Registered') ?></th> 
								<td><input name="blog[registered]" type="text" id="blog_registered" value="<?php echo $details['registered'] ?>" size="40" /></td> 
							</tr> 
							<tr class="form-field">
								<th scope="row"><?php _e('Last Updated') ?></th> 
								<td><input name="blog[last_updated]" type="text" id="blog_last_updated" value="<?php echo $details['last_updated'] ?>" size="40" /></td> 
							</tr> 
							<tr class="form-field">
								<th scope="row"><?php _e('Public') ?></th> 
								<td>
									<input type='radio' name='blog[public]' value='1' <?php if( $details['public'] == '1' ) echo 'checked="checked"'; ?> /> <?php _e('Yes') ?>
									<input type='radio' name='blog[public]' value='0' <?php if( $details['public'] == '0' ) echo 'checked="checked"'; ?> /> <?php _e('No') ?>
								</td> 
							</tr> 
							<tr class="form-field">
								<th scope="row"><?php _e( 'Archived' ); ?></th> 
								<td>
									<input type='radio' name='blog[archived]' value='1' <?php if( $details['archived'] == '1' ) echo 'checked="checked"'; ?> /> <?php _e('Yes') ?>
									<input type='radio' name='blog[archived]' value='0' <?php if( $details['archived'] == '0' ) echo 'checked="checked"'; ?> /> <?php _e('No') ?>
								</td> 
							</tr> 
							<tr class="form-field">
								<th scope="row"><?php _e( 'Mature' ); ?></th> 
								<td>
									<input type='radio' name='blog[mature]' value='1' <?php if( $details['mature'] == '1' ) echo 'checked="checked"'; ?> /> <?php _e('Yes') ?>
									<input type='radio' name='blog[mature]' value='0' <?php if( $details['mature'] == '0' ) echo 'checked="checked"'; ?> /> <?php _e('No') ?>
								</td> 
							</tr> 
							<tr class="form-field">
								<th scope="row"><?php _e( 'Spam' ); ?></th> 
								<td>
									<input type='radio' name='blog[spam]' value='1' <?php if( $details['spam'] == '1' ) echo 'checked="checked"'; ?> /> <?php _e('Yes') ?>
									<input type='radio' name='blog[spam]' value='0' <?php if( $details['spam'] == '0' ) echo 'checked="checked"'; ?> /> <?php _e('No') ?>
								</td> 
							</tr> 
							<tr class="form-field">
								<th scope="row"><?php _e( 'Deleted' ); ?></th> 
								<td>
									<input type='radio' name='blog[deleted]' value='1' <?php if( $details['deleted'] == '1' ) echo 'checked="checked"'; ?> /> <?php _e('Yes') ?>
									<input type='radio' name='blog[deleted]' value='0' <?php if( $details['deleted'] == '0' ) echo 'checked="checked"'; ?> /> <?php _e('No') ?>
								</td> 
							</tr> 
						</table>
						
						<h3><?php printf( __('Blog options (wp_%s_options)'), $id ); ?></h3>
						<table class="form-table">
							<?php
							$editblog_default_role = 'subscriber';
							foreach ( $options as $key => $val ) {
								if( $val['option_name'] == 'default_role' ) {
									$editblog_default_role = $val['option_value'];
								}
								$disabled = '';
								if ( is_serialized($val['option_value']) ) {
									if ( is_serialized_string($val['option_value']) ) {
										$val['option_value'] = wp_specialchars(maybe_unserialize($val['option_value']), 'single');
									} else {
										$val['option_value'] = "SERIALIZED DATA";
										$disabled = ' disabled="disabled"';
									}
								}
								if ( stristr($val['option_value'], "\r") || stristr($val['option_value'], "\n") || stristr($val['option_value'], "\r\n") ) {
								?>
									<tr class="form-field">
										<th scope="row"><?php echo ucwords( str_replace( "_", " ", $val['option_name'] ) ) ?></th> 
										<td><textarea rows="5" cols="40" name="option[<?php echo $val['option_name'] ?>]" type="text" id="<?php echo $val['option_name'] ?>"<?php echo $disabled ?>><?php echo wp_specialchars( stripslashes( $val['option_value'] ), 1 ) ?></textarea></td>
									</tr>
								<?php
								} else {
								?>
									<tr class="form-field">
										<th scope="row"><?php echo ucwords( str_replace( "_", " ", $val['option_name'] ) ) ?></th> 
										<td><input name="option[<?php echo $val['option_name'] ?>]" type="text" id="<?php echo $val['option_name'] ?>" value="<?php echo wp_specialchars( stripslashes( $val['option_value'] ), 1 ) ?>" size="40" <?php echo $disabled ?> /></td> 
									</tr> 
								<?php
								}
							} // End foreach
							?>
						</table>
						<p class="submit">
							<input type="submit" name="Submit" value="<?php _e('Update Options &raquo;') ?>" /></p>
					</div>
				</td>
				<td valign="top">
					<?php
					// Blog Themes
					$themes = get_themes();
					$blog_allowed_themes = wpmu_get_blog_allowedthemes( $id );
					$allowed_themes = get_site_option( "allowedthemes" );
					if( $allowed_themes == false ) {
						$allowed_themes = array_keys( $themes );
					}
					$out = '';
					foreach( $themes as $key => $theme ) {
						$theme_key = wp_specialchars( $theme['Stylesheet'] );
						if( isset($allowed_themes[$theme_key] ) == false ) {
							$checked = ( isset($blog_allowed_themes[ $theme_key ]) ) ? 'checked="checked"' : '';							
							$out .= '<tr class="form-field form-required"> 
									<th title="'.htmlspecialchars( $theme["Description"] ).'" scope="row">'.$key.'</th> 
									<td><input name="theme['.$theme_key.']" type="checkbox" value="on" '.$checked.'/></td> 
								</tr>';
						}
					}
					
							
					if( $out != '' ) {
						echo "<h3>" . __('Blog Themes') . "</h3>";
						echo '<table class="form-table">';						
							echo '<tr class=""><th>' . __('Theme') . '</th><th>' . __('Enable') . '</th></tr>';
							echo $out;
						echo "</table>";
					}
					
					// Blog users
					$blogusers = get_users_of_blog( $id );
					echo '<h3>' . __('Blog Users') . '</h3>';
					if( is_array( $blogusers ) ) {
						echo '<table class="form-table">';
						echo "<tr><th>" . __('User') . "</th><th>" . __('Role') . "</th><th>" . __('Password') . "</th><th>" . __('Remove') . "</th></tr>";
						reset($blogusers);
						foreach ( (array) $blogusers as $key => $val ) {
							$t = @unserialize( $val->meta_value );
							if( is_array( $t ) ) {
								reset( $t );
								$existing_role = key( $t );
							}
							echo '<tr><td><a href="user-edit.php?user_id=' . $val->user_id . '">' . $val->user_login . '</a></td>';
							if( $val->user_id != $current_user->data->ID ) {
								?>
								<td>
									<select name="role[<?php echo $val->user_id ?>]" id="new_role"><?php 
										foreach( $editblog_roles as $role => $role_assoc ){
											$name = translate_with_context($role_assoc['name']);
											$selected = ( $role == $existing_role ) ? 'selected="selected"' : '';
											echo "<option {$selected} value=\"{$role}\">{$name}</option>";
										}
										?>
									</select>
								</td>
								<td>
										<input type='text' name='user_password[<?php echo $val->user_id ?>]' />
								</td>
								<?php
								echo '<td><input title="' . __('Click to remove user') . '" type="checkbox" name="blogusers[' . $val->user_id . ']" /></td>';
							} else {
								echo "<td><strong>" . __ ('N/A') . "</strong></td><td><strong>" . __ ('N/A') . "</strong></td><td><strong>" . __('N/A') . "</strong></td>";
							}
							echo '</tr>';
						}
						echo "</table>";
					}
					
					// New blog user
					echo "<h3>" . __('Add a new user') . "</h3>"; ?>
					<p><?php _e('As you type WordPress will offer you a choice of usernames.<br /> Click them to select and hit <em>Update Options</em> to add the user.') ?></p>
					<table class="form-table">
						<tr>
							<th scope="row"><?php _e('User&nbsp;Login:') ?></th>
							<td><input type="text" name="newuser" id="newuser" /></td>
						</tr>
						<tr>
							<th scope="row"><?php _e('Role:') ?></th>
							<td>
								<select name="new_role" id="new_role">
								<?php 
								reset( $editblog_roles );
								foreach( $editblog_roles as $role => $role_assoc ){
									$name = translate_with_context($role_assoc['name']);
									$selected = ( $role == $editblog_default_role ) ? 'selected="selected"' : '';
									echo "<option {$selected} value=\"{$role}\">{$name}</option>";
								}
								?>
								</select>
							</td>
						</tr>
					</table>
					
					<h3><?php _e('Misc Blog Actions') ?></h3>
					<table class="form-table">
						<?php do_action( 'wpmueditblogaction', $id ); ?>
					</table>
					
					<p class="submit">
						<input type="submit" name="Submit" value="<?php _e('Update Options &raquo;') ?>" /></p>				
				</td>
			</tr>
			</table>
		</form>
		</div>
		<?php
	break;
	
	// List blogs
	default:
		$apage = isset( $_GET['apage'] ) ? intval( $_GET['apage'] ) : 1;
		$num = isset( $_GET['num'] ) ? intval( $_GET['num'] ) : 15;
		
		$query = "SELECT * FROM {$wpdb->blogs} WHERE site_id = '{$wpdb->siteid}' ";
		
		if( isset($_GET['blog_name']) ) {
			$s = trim($_GET['s']);
			$query = "SELECT blog_id, {$wpdb->blogs}.domain, {$wpdb->blogs}.path, registered, last_updated
				FROM {$wpdb->blogs}, {$wpdb->site}
				WHERE site_id = '{$wpdb->siteid}'
				AND {$wpdb->blogs}.site_id = {$wpdb->site}.id
				AND ( {$wpdb->blogs}.domain LIKE '%{$s}%' OR {$wpdb->blogs}.path LIKE '%{$s}%' )";
		} elseif( isset($_GET['blog_id']) ) {
			$query = "SELECT * 
				FROM {$wpdb->blogs}
				WHERE site_id = '{$wpdb->siteid}'
				AND   blog_id = '".intval($_GET['s'])."'";
		} elseif( isset($_GET['blog_ip']) ) {
			$query = "SELECT *
				FROM {$wpdb->blogs}, {$wpdb->registration_log}
				WHERE site_id = '{$wpdb->siteid}'
				AND {$wpdb->blogs}.blog_id = {$wpdb->registration_log}.blog_id
				AND {$wpdb->registration_log}.IP LIKE ('%".$_GET['s']."%')";
		}
		
		if( isset( $_GET['sortby'] ) == false ) {
			$_GET['sortby'] = 'id';
		}
		
		if( $_GET['sortby'] == 'registered' ) {
			$query .= ' ORDER BY registered ';
		} elseif( $_GET['sortby'] == 'id' ) {
			$query .= ' ORDER BY ' . $wpdb->blogs . '.blog_id ';
		} elseif( $_GET['sortby'] == 'lastupdated' ) {
			$query .= ' ORDER BY last_updated ';
		} elseif( $_GET['sortby'] == 'blogname' ) {
			$query .= ' ORDER BY domain ';
		}

		$query .= ( $_GET['order'] == 'DESC' ) ? 'DESC' : 'ASC';
		
		if( !empty($_GET['s']) ) {
			$blog_list = $wpdb->get_results( $query, ARRAY_A );	
			$total = count($blog_list);	
		} else {
			$total = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->blogs} WHERE site_id = '{$wpdb->siteid}' ");	
		}
		
		$query .= " LIMIT " . intval( ( $apage - 1 ) * $num) . ", " . intval( $num );
			
		$blog_list = $wpdb->get_results( $query, ARRAY_A );	

		// Pagination
		$url2 = "&order=" . $_GET['order'] . "&amp;sortby=" . $_GET['sortby'] . "&amp;s=" . $_GET['s'] . "&ip_address=" . $_GET['ip_address'];
		$blog_navigation = paginate_links( array(
			'base' => add_query_arg( 'apage', '%#%' ).$url2,
			'format' => '',
			'total' => ceil($total / $num),
			'current' => $apage
		));
		?>

		<div class="wrap" style="position:relative;">
		<h2><?php _e('Blogs') ?></h2>
		
		<form id="searchform" action="wpmu-blogs.php" method="get" style="position:absolute;right:0;top:0;">
			<input type="hidden" name="action" value="blogs" />			
			<input type="text" name="s" value="<?php if (isset($_GET['s'])) echo stripslashes(wp_specialchars($_GET['s'], 1)); ?>" size="17" />
			<input type="submit" class="button" name="blog_name" value="<?php _e('Search blogs by name') ?>" />
			<input type="submit" class="button" name="blog_id" value="<?php _e('by blog ID') ?>" />		
			<input type="submit" class="button" name="blog_ip" value="<?php _e('by IP address') ?>" />		
		</form>
	
		<form id="form-blog-list" action="wpmu-edit.php?action=allblogs" method="post">
		
		<div class="tablenav">
			<?php if ( $blog_navigation ) echo "<div class='tablenav-pages'>$blog_navigation</div>"; ?>	

			<div class="alignleft">
				<input type="submit" value="<?php _e('Delete') ?>" name="allblog_delete" class="button-secondary delete" />
				<input type="submit" value="<?php _e('Mark as Spam') ?>" name="allblog_spam" class="button-secondary" />
				<input type="submit" value="<?php _e('Not Spam') ?>" name="allblog_notspam" class="button-secondary" />
				<?php wp_nonce_field( 'allblogs' ); ?>
				<br class="clear" />
			</div>
		</div>

		<br class="clear" />
		
		<?php if( isset($_GET['s']) && !empty($_GET['s']) ) : ?>
			<p><a href="wpmu-users.php?action=users&s=<?php echo stripslashes(wp_specialchars($_GET['s'], 1)) ?>"><?php _e('Search Users:') ?> <strong><?php echo stripslashes(wp_specialchars($_GET['s'], 1)); ?></strong></a></p>
		<?php endif; ?>		

		<?php
		// define the columns to display, the syntax is 'internal name' => 'display name'
		$blogname_columns = ( constant( "VHOST" ) == 'yes' ) ? __('Domain') : __('Path');
		$posts_columns = array(
			'id'           => __('ID'),
			'blogname'     => $blogname_columns,
			'lastupdated'  => __('Last Updated'),
			'registered'   => __('Registered'),
			'users'        => __('Users'),
			'plugins'      => __('Actions')
		);
		$posts_columns = apply_filters('manage_posts_columns', $posts_columns);

		// you can not edit these at the moment
		$posts_columns['control_edit']      = '';
		$posts_columns['control_backend']   = '';
		$posts_columns['control_deactivate']= '';
		$posts_columns['control_archive']   = '';
		$posts_columns['control_spam']   	= '';
		$posts_columns['control_delete']    = '';

		$sortby_url = "s=" . $_GET['s'] . "&amp;ip_address=" . $_GET['ip_address'];
		?>
		
		<table width="100%" cellpadding="3" cellspacing="3" class="widefat">
			<thead>
				<tr>
				<th scope="col" class="check-column"><input type="checkbox" onclick="checkAll(document.getElementById('form-blog-list'));" /></th>
				<?php foreach($posts_columns as $column_id => $column_display_name) {
					$column_link = "<a href='wpmu-blogs.php?{$sortby_url}&amp;sortby={$column_id}&amp;";
					if( $_GET['sortby'] == $column_id ) { 
						$column_link .= $_GET[ 'order' ] == 'DESC' ? 'order=ASC&amp;' : 'order=DESC&amp;';
					}
					$column_link .= "apage={$apage}'>{$column_display_name}</a>";
					
					$col_url = ($column_id == 'users' || $column_id == 'plugins') ? $column_display_name : $column_link;
					?>
					<th scope="col"><?php echo $col_url ?></th>
				<?php } ?>
				</tr>
			</thead>
			<tbody id="the-list">
			<?php
			if ($blog_list) {
				$bgcolor = $class = '';
				$status_list = array( "archived" => "#fee", "spam" => "#faa", "deleted" => "#f55" );
				foreach ($blog_list as $blog) { 
					$class = ('alternate' == $class) ? '' : 'alternate';
					reset( $status_list );
					
					$bgcolour = "";
					foreach ( $status_list as $status => $col ) {
						if( get_blog_status( $blog['blog_id'], $status ) == 1 ) {
							$bgcolour = "style='background: $col'";
						}
					}
					echo "<tr $bgcolour class='$class'>";
					
					$blogname = ( constant( "VHOST" ) == 'yes' ) ? str_replace('.'.$current_site->domain, '', $blog['domain']) : $blog['path']; 
					foreach( $posts_columns as $column_name=>$column_display_name ) {
						switch($column_name) {
							case 'id': ?>
								<th scope="row" class="check-column">
									<input type='checkbox' id='blog_<?php echo $blog['blog_id'] ?>' name='allblogs[]' value='<?php echo $blog['blog_id'] ?>' />
								</th>
								<th scope="row">
									<?php echo $blog['blog_id'] ?>
								</th>
							<?php
							break;
 
							case 'blogname': ?>
								<td valign="top">
									<a href="http://<?php echo $blog['domain']. $blog['path']; ?>" rel="permalink"><?php echo $blogname; ?></a>
								</td>
							<?php
							break;
 
							case 'lastupdated': ?>
								<td valign="top">
									<?php echo ( $blog['last_updated'] == '0000-00-00 00:00:00' ) ? __("Never") : mysql2date(__('Y-m-d \<\b\r \/\> g:i:s a'), $blog['last_updated']); ?>
								</td>
							<?php
							break;
							case 'registered': ?>
								<td valign="top">
									<?php echo mysql2date(__('Y-m-d \<\b\r \/\> g:i:s a'), $blog['registered']); ?>
								</td>
							<?php
							break;

							case 'users': ?>
								<td valign="top">
									<?php
									$blogusers = get_users_of_blog( $blog['blog_id'] ); 
									if( is_array( $blogusers ) ) {
										if( $blog['blog_id'] == 1 && count( $blogusers ) > 10 ) {
											$blogusers = array_slice( $blogusers, 0, 10 );
										}
										foreach ( $blogusers as $key => $val ) {
											echo '<a href="user-edit.php?user_id=' . $val->user_id . '">' . $val->user_login . '</a> ('.$val->user_email.')<br />'; 
										}
									}
									?>
								</td>
							<?php
							break;
							case 'control_edit': ?>
								<td valign="top">
									<?php echo "<a href='wpmu-blogs.php?action=editblog&amp;id=".$blog['blog_id']."' class='edit'>" . __('Edit') . "</a>"; ?>
								</td>
							<?php
							break;
							case 'control_backend':
							?>
								<td valign="top">
									<?php echo "<a href='http://" . $blog['domain'] . $blog['path'] . "wp-admin/' class='edit'>" . __('Backend') . "</a>"; ?>
								</td>
							<?php
							break;

							case 'control_spam': 
								if( get_blog_status( $blog['blog_id'], "spam" ) == '1' ) { ?>
									<td valign="top">
										<a class='delete' href="wpmu-edit.php?action=confirm&amp;action2=unspamblog&amp;id=<?php echo $blog['blog_id'] ?>&amp;msg=<?php echo urlencode( sprintf( __( "You are about to unspam the blog %s" ), $blogname ) ) ?>"><?php _e("Not Spam") ?></a>
									</td>
								<?php } else { ?>
									<td valign='top'>
										<a class='delete' href="wpmu-edit.php?action=confirm&amp;action2=spamblog&amp;id=<?php echo $blog['blog_id'] ?>&amp;msg=<?php echo urlencode( sprintf( __( "You are about to mark the blog %s as spam" ), $blogname ) ) ?>""><?php _e("Spam") ?></a>
									</td>
								<?php }
							break;

							case 'control_deactivate':
								if( get_blog_status( $blog['blog_id'], "deleted" ) == '1' ) { ?>
									<td valign="top">
										<a class='delete' href="wpmu-edit.php?action=confirm&amp;action2=activateblog&amp;ref=<?php echo urlencode( $_SERVER['REQUEST_URI'] ) ?>&amp;id=<?php echo $blog['blog_id'] ?>&amp;msg=<?php echo urlencode( sprintf( __( "You are about to activate the blog %s" ), $blogname ) ) ?>"><?php _e("Activate") ?></a>
									</td>
								<?php } else { ?>
									<td valign="top">
										<a class='delete' href="wpmu-edit.php?action=confirm&amp;action2=deactivateblog&amp;ref=<?php echo urlencode( $_SERVER['REQUEST_URI'] ) ?>&amp;id=<?php echo $blog['blog_id'] ?>&amp;msg=<?php echo urlencode( sprintf( __( "You are about to deactivate the blog %s" ), $blogname ) ) ?>"><?php _e("Deactivate") ?></a>
									</td>
								<?php }
							break;

							case 'control_archive':
								if( get_blog_status( $blog['blog_id'], "archived" ) == '1' ) { ?>
									<td valign="top">
										<a class='delete' href="wpmu-edit.php?action=confirm&amp;action2=unarchiveblog&amp;id=<?php echo $blog['blog_id'] ?>&amp;msg=<?php echo urlencode( sprintf( __( "You are about to unarchive the blog %s" ), $blogname ) ) ?>"><?php _e("Unarchive") ?></a>
									</td>
								<?php } else { ?>
									<td valign="top">
										<a class='delete' href="wpmu-edit.php?action=confirm&amp;action2=archiveblog&amp;id=<?php echo $blog['blog_id'] ?>&amp;msg=<?php echo urlencode( sprintf( __( "You are about to archive the blog %s" ), $blogname ) ) ?>"><?php _e("Archive") ?></a>
									</td>
								<?php }
							break;

							case 'control_delete': ?>
								<td valign="top">
									<a class='delete' href="wpmu-edit.php?action=confirm&amp;action2=deleteblog&amp;id=<?php echo $blog['blog_id'] ?>&amp;msg=<?php echo urlencode( sprintf( __( "You are about to delete the blog %s" ), $blogname ) ) ?>"><?php _e("Delete") ?></a>
								</td>
							<?php break;

							case 'plugins': ?>
								<td valign="top">
									<?php do_action( "wpmublogsaction", $blog['blog_id'] ); ?>
								</td>
							<?php break;
 
							default: ?>
								<td valign="top">
									<?php do_action('manage_blogs_custom_column', $column_name, $blog['blog_id']); ?>
								</td>
							<?php break;
						}
					}
					?>
					</tr>
					<?php
				}
			} else { ?>
				<tr style='background-color: <?php echo $bgcolor; ?>'> 
					<td colspan="8"><?php _e('No blogs found.') ?></td> 
				</tr> 
			<?php
			} // end if ($blogs)
			?>

			</tbody>
		</table>
		</form>		
		</div>
		
		<div class="wrap">
			<h2><?php _e('Add Blog') ?></h2>
			<form method="post" action="wpmu-edit.php?action=addblog">
				<?php wp_nonce_field('add-blog') ?>
				<table class="form-table">
					<tr class="form-field form-required">	
						<th style="text-align:center;" scope='row'><?php _e('Blog Address') ?></th>
						<td>
						<?php if( constant( "VHOST" ) == 'yes' ) : ?>
							<input name="blog[domain]" type="text" title="<?php _e('Domain') ?>"/>.<?php echo $current_site->domain;?>
						<?php else:
							echo $current_site->domain . $current_site->path ?><input name="blog[domain]" type="text" title="<?php _e('Domain') ?>"/>
						<?php endif; ?>
						</td>
					</tr>
					<tr class="form-field form-required">
						<th style="text-align:center;" scope='row'><?php _e('Blog Title') ?></th>
						<td><input name="blog[title]" type="text" size="20" title="<?php _e('Title') ?>"/></td>
					</tr>
					<tr class="form-field form-required">	
						<th style="text-align:center;" scope='row'><?php _e('Admin Email') ?></th>
						<td><input name="blog[email]" type="text" size="20" title="<?php _e('Email') ?>"/></td>
					</tr>
					<tr class="form-field">
						<td colspan='2'><?php _e('A new user will be created if the above email address is not in the database.') ?><br /><?php _e('The username and password will be mailed to this email address.') ?></td>
					</tr>
				</table>
				<p class="submit">
					<input class="button" type="submit" name="go" value="<?php _e('Add Blog') ?>" /></p>
			</form>
		</div>
		<?php
	break;
} // end switch( $action )

include('admin-footer.php'); ?>
