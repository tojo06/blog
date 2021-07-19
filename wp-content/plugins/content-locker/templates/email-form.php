<div class="mts-cl-subscription-message">
	<?php esc_html_e( 'Cannot sign in via social networks? Enter your email manually.', 'content-locker' ); ?>
</div>

<form class="mts-cl-subscription-form">

    <div class="mts-cl-subscription-form-inner-wrap">

        <div class="mts-cl-field mts-cl-field-email">
            <div class="mts-cl-field-control">
                <input type="email" class="mts-cl-input mts-cl-input-required" id="mts-cl-input-email" placeholder="<?php esc_html_e( 'Please enter your email address.', 'content-locker' ) ?>" autocomplete="off" name="email">
            </div>
            <?php if(cl()->settings->get( 'trans_consent_input_placeholder')) { ?>
                <div class="mts-cl-field-control mts-cl-consent-control">
                    <input type="checkbox" class="mts-cl-input-required" id="mts-cl-input-consent" name="consent" required>
                    <label for="mts-cl-input-consent"><?php echo cl()->settings->get( 'trans_consent_input_placeholder'); ?></label>
                </div>
            <?php } ?>
        </div>

        <div class="mts-cl-field mts-cl-field-submit">
            <button class="mts-cl-button mts-cl-form-button mts-cl-submit"><?php esc_html_e( 'Sign-In to Unlock	', 'content-locker' ) ?></button>
            <div class="mts-cl-note mts-cl-nospa"><?php esc_html_e( 'Your email address is 100% safe from spam!', 'content-locker' ) ?></div>
        </div>

    </div>

</form>
