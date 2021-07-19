<h2><?php esc_html_e( 'Getting LinkedIn Client ID', 'content-locker' ) ?></h2>

<p>
	<?php esc_html_e( 'If you want to enable users to use their LinkedIn accounts to unlock the content, you need to get an API key from LinkedIn to enable that functionality.', 'content-locker' ) ?>
</p>
<ol>
	<li>
		<p>
			<?php echo wp_kses_post( __( 'Go to <a href="https://www.linkedin.com/secure/developer">linkedin.com/secure/developer</a> and click on "Create Application."', 'content-locker' ) ) ?>
		</p>
	</li>
	<li>
		<p>
			<?php esc_html_e( 'On the next screen, add your details like shown in the screenshot below:', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
			<img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/32.jpg" />
		</p>
	</li>
	<li>
		<p>
			<?php esc_html_e( 'Next, you will be shown the Client ID and some options. Set them up like the screenshot below:', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
			<img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/33.jpg" />
		</p>
		<p>
			<?php esc_html_e( 'For the "Accept" URL, the one shown on this page below.', 'content-locker' ) ?>
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
					<td class="mts-cl-title"><?php esc_html_e( 'Company', 'content-locker' ) ?></td>
					<td><?php esc_html_e( 'Select an existing company or create your own one (you can use your website name as a company name).', 'content-locker' ) ?></td>
				</tr>
				<tr>
					<td class="mts-cl-title"><?php esc_html_e( 'Name', 'content-locker' ) ?></td>
					<td><?php esc_html_e( 'The best name is your website name.', 'content-locker' ) ?></td>
				</tr>
				<tr>
					<td class="mts-cl-title"><?php esc_html_e( 'Description', 'content-locker' ) ?></td>
					<td>
						<p><?php esc_html_e( 'Explain what your app does, e.g:', 'content-locker' ) ?></p>
						<p><i><?php esc_html_e( 'This application asks your credentials in order to unlock the content. Please read the Terms of Use to know how these credentials will be used.', 'content-locker' ) ?></i></p>
					</td>
				</tr>
				<tr>
					<td class="mts-cl-title"><?php esc_html_e( 'Application Logo URL', 'content-locker' ) ?></td>
					<td>
						<p><?php esc_html_e( 'Paste an URL to your logo (80x80px). Or use this default logo:', 'content-locker' ) ?></p>
						<p><i><?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/default-logo.png</i></p>
					</td>
				</tr>
				<tr>
					<td class="mts-cl-title"><?php esc_html_e( 'Application Use', 'content-locker' ) ?></td>
					<td>
						<p><?php esc_html_e( 'Select "Other" from the list.', 'content-locker' ) ?></p>
					</td>
				</tr>
				<tr>
					<td class="mts-cl-title"><?php esc_html_e( 'Website URL', 'content-locker' ) ?></td>
					<td>
						<p><?php esc_html_e( 'Paste your website URL:', 'content-locker' ) ?></p>
						<p><i><?php echo get_bloginfo( 'url' ) ?></i></p>
					</td>
				</tr>
				<tr>
					<td class="mts-cl-title"><?php esc_html_e( 'Business Email', 'content-locker' ) ?></td>
					<td>
						<p><?php esc_html_e( 'Enter your email to receive updates regarding your app.', 'content-locker' ) ?></p>
					</td>
				</tr>
				<tr>
					<td class="mts-cl-title"><?php esc_html_e( 'Business Phone', 'content-locker' ) ?></td>
					<td>
						<p><?php esc_html_e( 'Enter your phone. It will not be visible for visitors.', 'content-locker' ) ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<br />
	</li>
	<li>
		<p>
			<?php printf( wp_kses_post( __( 'After setting the Application to "Live" status, copy the client ID and client secret you saw in step 3 and paste them in the <a href="%s">Content Locker settings page</a> like below.', 'content-locker' ) ), cl_get_admin_url( 'settings#linkedin_client_id' ) ) ?>
		</p>
		<p class="mts-cl-img">
			<img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/34.jpg" />
		</p>
	</li>
</ol>
