<h2><?php esc_html_e( 'Getting Google Client ID', 'content-locker' ) ?></h2>

<ol>
	<li>
		<p>
		    <?php echo wp_kses_post( __( 'Head over to <a href="https://console.developers.google.com/project">console.developers.google.com</a>', 'content-locker' ) ) ?>
		</p>

	</li>
	<li>
		<p>
			<?php esc_html_e( 'You will be greeted with a blank screen where you can create a new project.', 'content-locker' ) ?>
		</p>
	</li>
	<li>
		<p>
		    <?php esc_html_e( 'Click on the "Create Project" button and give your Project a name.', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
		    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/26.jpg" />
		</p>
	</li>
	<li>
		<p>
		    <?php esc_html_e( 'Once you do that, head over to the Library and search for "Google+".', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
		    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/27.jpg" />
		</p>
	</li>
	<li>
		<p>
		    <?php esc_html_e( 'Click on the "Google+ API" link and hit the Enable button.', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
		    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/28.jpg" />
		</p>
	</li>
	<li>
		<p>
		    <?php esc_html_e( 'Do the same for "YouTube Data API v3".', 'content-locker' ) ?>
		</p>
	</li>
	<li>
		<p>
		    <?php esc_html_e( 'Then, click on the "Credentials" button on the left and hit "Create Credentials". Then, choose "OAuth client ID."', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
		    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/29.jpg" />
		</p>
	</li>
	<li>
		<p>
		    <?php esc_html_e( 'Enter the details on the next screen. When asked for \'Authorised Redirect URIs\', paste the URL you see below on this page.', 'content-locker' ) ?>
		</p>
		<table class="wp-list-table widefat fixed striped">
		    <thead>
		        <tr>
		            <th><?php esc_html_e( 'Field', 'content-locker' ) ?></th>
		            <th><?php esc_html_e( 'How To Fill', 'content-locker' ) ?></th>
		        </tr>
		    </thead>
		    <tbody>
		        <tr>
		            <td class="mts-cl-title"><?php esc_html_e( 'Application Type', 'content-locker' ) ?></td>
		            <td>
		                <p><?php esc_html_e( 'Web Application', 'content-locker' ) ?></p>
		            </td>
		        </tr>
		        <tr>
		            <td class="mts-cl-title"><?php esc_html_e( 'Authorized Javascript origins', 'content-locker' ) ?></td>
		            <td>
		                <p><?php esc_html_e( 'Add the origins:', 'content-locker' ) ?></p>
		                <p><i>http://yourdomain.com</i></p>
		                <p><i>http://www.yourdomain.com</i></p>
		            </td>
		        </tr>
		        <tr>
		            <td class="mts-cl-title"><?php esc_html_e( 'Authorized redirect URIs', 'content-locker' ) ?></td>
		            <td>
		                <p><?php esc_html_e( 'Paste the URL:', 'content-locker' ) ?></p>
		                <p><i><?php echo get_bloginfo( 'url' ); ?>/wp-admin/admin-ajax.php?action=mts_cl_connect&amp;handler=google</i></p>
		            </td>
		        </tr>
		    </tbody>
		</table>
		<br />
	</li>
	<li>
		<p>
		    <?php esc_html_e( 'On the next screen, you will see the Client ID. Copy it.', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
		    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/30.jpg" />
		</p>
	</li>
	<li>
		<p>
			<?php printf( wp_kses_post( __( 'Paste it in the <a href="%s">Content Locker settings</a> section designated for "Google Client ID".', 'content-locker' ) ), cl_get_admin_url( 'settings#google_client_id' ) ) ?>
		</p>
		<p class="mts-cl-img">
		    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/31.jpg" />
		</p>
	</li>
</ol>
