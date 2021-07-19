<h2><?php esc_html_e( 'Creating Twitter App', 'content-locker' ) ?></h2>
<ol>
	<li>
		<p>
		    <?php esc_html_e( 'Head over to apps.twitter.com and login if you aren\'t logged in already.', 'content-locker' ) ?>
		</p>
	</li>
	<li>
		<p>
		    <?php esc_html_e( 'You will see a new button saying "Create New App". Click that button.', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
		    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/21.jpg" />
		</p>
	</li>
	<li>
		<p>
		    <?php esc_html_e( 'On the next screen, you will be asked for a few details. Enter those details as required.', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
		    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/22.jpg" />
		</p>
		<p>
		    <?php esc_html_e( 'When asked for a Callback URL, scroll down on this page and copy the URL from below section as seen the screenshot:', 'content-locker' ) ?>
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
		            <td class="mts-cl-title"><?php esc_html_e( 'Name', 'content-locker' ) ?></td>
		            <td><?php esc_html_e( 'The best app name is your website name.', 'content-locker' ) ?></td>
		        </tr>
		        <tr>
		            <td class="mts-cl-title"><?php esc_html_e( 'Description', 'content-locker' ) ?></td>
		            <td>
		                <p><?php esc_html_e( 'Explain why you ask for the credentials, e.g:', 'content-locker' ) ?></p>
		                <p><i><?php esc_html_e( 'This application asks your credentials in order to unlock the content. Please read the TOS.', 'content-locker' ) ?></i></p>
		            </td>
		        </tr>
		        <tr>
		            <td class="mts-cl-title"><?php esc_html_e( 'Website', 'content-locker' ) ?></td>
		            <td>
		                <p><?php esc_html_e( 'Paste your website URL:', 'content-locker' ) ?></p>
		                <p>
		                    <?php echo get_bloginfo( 'url' ); ?>
		                </p>
		            </td>
		        </tr>
		        <tr>
		            <td class="mts-cl-title"><?php esc_html_e( 'Callback URL', 'content-locker' ) ?></td>
		            <td>
		                <p><?php esc_html_e( 'Paste the URL:', 'content-locker' ) ?></p>
		                <p><i><?php echo get_bloginfo( 'url' ); ?>/wp-admin/admin-ajax.php?action=mts_cl_connect&amp;handler=twitter</i></p>
		            </td>
		        </tr>
		    </tbody>
		</table>
		<br />
	</li>
	<li>
		<p>
			<?php esc_html_e( 'Next, click the Settings tab and scroll down to check the option that says "Allow this application to be used to \'sign in with Twitter\'".', 'content-locker' ) ?>
		</p>
	</li>
	<li>
		<p>
			<?php esc_html_e( 'Head over the Permissions tab and ensure "Read and Write" as well as the "Request email addresses from users" option is selected.', 'content-locker' ) ?>
		</p>
	</li>
	<li>
		<p>
			<?php esc_html_e( 'Go to "Keys and Access Tokens" tab and grab the "API Key" as well as the "API Secret". You will need them in the next step.', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
		    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/24.jpg" />
		</p>
	</li>
	<li>
		<p>
			<?php printf( wp_kses_post( __( 'Go back to the <a href="%s">Content Locker settings page</a> and paste the codes as seen in the screenshot below:', 'content-locker' ) ), cl_get_admin_url( 'settings#twitter_consumer_key' ) ) ?>
		</p>
		<p class="mts-cl-img">
		    <img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/25.jpg" />
		</p>
	</li>
</ol>
