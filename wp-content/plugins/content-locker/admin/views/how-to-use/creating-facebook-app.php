<h2>
	<?php esc_html_e( 'Creating a Facebook App', 'content-locker' ) ?>
</h2>
<p>
	<?php esc_html_e( 'Creating social apps and connecting them to the Content Locker plugin is essential for the proper functioning of the Sign in Locker feature. Don\'t be scared, though, this process is very simple, and we will show you exactly how to do this.', 'content-locker' ) ?>
</p>
<p>
	<?php esc_html_e( 'To make the Facebook Sign in Locker feature work, you need to create a Facebook app by following these steps:', 'content-locker' ) ?>
</p>
<ol>
	<li>
		<p>
			<?php echo wp_kses_post( __( 'Go to <a href="https://developers.facebook.com/">developers.facebook.com</a> and log in, if you haven\'t already. Click on the "Create App" button as shown in the screenshot below:', 'content-locker' ) ) ?>
		</p>
		<p class="mts-cl-img">
			<img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/15.jpg" />
		</p>
	</li>
	<li>
		<p>
			<?php esc_html_e( 'A new popup asking for your app details will open up. Enter the app\'s display name, your contact email and your app\'s category. Once you are done, click the "Create App ID" button as shown in the screenshot:', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
			<img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/16.jpg" />
		</p>
	</li>
	<li>
		<p>
			<?php esc_html_e( 'Once your app ID is created, you want to go to the "Settings" section on the next screen:', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
			<img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/17.jpg" />
		</p>
	</li>
	<li>
		<p>
			<?php esc_html_e( 'Add Your Website\'s URL, both with and without the starting \'www\'. Then, select "website" as the platform and enter your website\'s URL.', 'content-locker' ) ?>
		</p>
	</li>
	<li>
		<p>
					<?php esc_html_e( 'Head over to the "App Review" section and make the App public like shown in the screenshot:', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
			<img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/18.jpg" />
		</p>
	</li>
	<li>
		<p>
			<?php esc_html_e( 'Once that is done, Head back to the Dashboard of the Facebook App and grab the App ID. Copy this App so you can paste it in the Content Locker plugin.', 'content-locker' ) ?>
		</p>
		<p class="mts-cl-img">
			<img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/19.jpg" />
		</p>
	</li>
	<li>
		<p>
			<?php printf( wp_kses_post( __( 'Go back to the <a href="%s">Content Locker settings page</a> and paste the App ID you copied in the last step.', 'content-locker' ) ), cl_get_admin_url( 'settings#facebook_appid' ) ) ?>
		</p>
		<p class="mts-cl-img">
			<img src="<?php echo cl()->plugin_url() ?>/admin/views/how-to-use/img/20.jpg" />
		</p>
	</li>
</ol>
