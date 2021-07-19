<?php

defined('ABSPATH') || exit;

//todo to - need more time to reduce the number of loop & query.


$showPostNo = isset($display_setting['review_show_per']) ? $display_setting['review_show_per'] : 10;
$likeData   = '"xs_post_id":"' . $this->getPostId . '"';
$paged      = isset($_GET['review_page']) ? $_GET['review_page'] : 1;

$args = array(
	'post_type'      => $this->post_type,
	'meta_query'     => array(
		array(
			'key'     => 'xs_public_review_data',
			'value'   => '' . $likeData . '',
			'compare' => 'LIKE',
		),
	),
	'orderby'        => array(
		'post_date' => 'DESC',
	),
	'posts_per_page' => $showPostNo,
	'paged'          => $paged,
);


$argsTotal = array(
	'post_type'  => $this->post_type,
	'meta_query' => array(
		array(
			'key'     => 'xs_public_review_data',
			'value'   => '' . $likeData . '',
			'compare' => 'LIKE',
		),
	),
	'orderby'    => array(
		'post_date' => 'DESC',
	),
);

$the_query      = new \WP_Query($args);
$the_queryTotal = new \WP_Query($argsTotal);


$content_meta_key = 'xs_submit_review_data';
$review_list      = 'Yes';


if($review_list == 'Yes' || isset($post_review_meta->overview->ratting->enable)) {

	/*Start avarage ratting of user review*/
	$overViewTotal      = 0;
	$totalRattingsCount = 0;
	$rattingRatting     = 5;
	$overViewArray      = [];
	$num_of_reviews     = 0;

	if($the_queryTotal->have_posts()) {

		$num_of_reviews = empty($the_queryTotal->found_posts) ? 0 : $the_queryTotal->found_posts;

		/**
		 * Looping through every user review
		 *
		 */
		while($the_queryTotal->have_posts()) {

			$the_queryTotal->the_post();
			$metaReviewID = get_the_ID();

			$getMetaData = \WurReview\App\Wur_Settings::get_xs_post_meta($metaReviewID, 'xs_public_review_data');

			$xs_reviwer_rattingOver = isset($getMetaData->xs_reviwer_ratting) ? $getMetaData->xs_reviwer_ratting : '0';
			$reviwerStyleLimitOver  = isset($getMetaData->review_score_limit) ? $getMetaData->review_score_limit : '5';

			$overViewArray['xs_reviwer_ratting'][] = $xs_reviwer_rattingOver;
			$overViewArray['review_score_limit'][] = $reviwerStyleLimitOver;
		}

		$rattingRatting = max(isset($overViewArray['review_score_limit']) ? $overViewArray['review_score_limit'] : []);
		$rattingRatting = 5;

		$arrayCountValues = array_count_values(isset($overViewArray['xs_reviwer_ratting']) ? $overViewArray['xs_reviwer_ratting'] : []);

		$totalRattingsSum   = array_sum(isset($overViewArray['xs_reviwer_ratting']) ? $overViewArray['xs_reviwer_ratting'] : []);
		$totalRattingsCount = count(isset($overViewArray['xs_reviwer_ratting']) ? $overViewArray['xs_reviwer_ratting'] : []);

		$overViewTotal = round(($totalRattingsSum / $totalRattingsCount), 2);

		wp_reset_postdata();
	}

	?>
    <div class="xs-review-box view-review-list" id="xs-user-review-box">

        <h3 class="total-reivew-headding">
			<?php echo $num_of_reviews; ?>
			<?php printf(_nx(' Review', ' Reviews', $num_of_reviews, 'no of reviews', 'wp-ultimate-review')); ?>
        </h3>

        <div class="xs-review-box-item">

            <div class="xs-review-media <?php echo empty($post_review_meta->overview->ratting->enable) ? 'review-full' : ''; ?>">

				<?php

				if($the_query->have_posts()) {

					$divider = false;

					while($the_query->have_posts()) {

						$the_query->the_post();
						$metaReviewID = get_the_ID();

						$metaDataJson = get_post_meta($metaReviewID, 'xs_public_review_data', false);

						$getMetaData = \WurReview\App\Wur_Settings::get_xs_post_meta($metaReviewID, 'xs_public_review_data');

						if($divider) {
							echo '<div class="border-div"> </div>';
						}

						$divider = true;

						?>

                        <!-- every review-->
                        <div class="xs-reviewer-details">

							<?php

							if($wur_settings->is_reviewer_profile_enabled()):

								$profileImage = isset($getMetaData->xs_post_author) ? $getMetaData->xs_post_author : 0; ?>

                                <div class="review-reviwer-image-section">

									<?php

									if(!empty($profileImage)) {
										?>
                                        <div class="xs-reviewer-author-image">
											<?php echo get_avatar($profileImage); ?>
                                        </div>
										<?php
									}

									?>

                                </div> <?php

							endif;

							?>

                            <div class="review-reviwer-info-section">

								<?php

								if($wur_settings->is_reviewer_name_enabled()):

									if(!empty($getMetaData->xs_reviwer_name)): ?>
                                        <div class="xs-reviewer-author">
                                            <span class="xs_review_name"> <?php echo esc_html($getMetaData->xs_reviwer_name); ?> </span>
											<?php
											if($wur_settings->is_reviewer_email_enabled()):
												if(!empty($getMetaData->xs_reviwer_email)):
													?>
                                                    <span class="xs_review_email"> - <?php echo esc_html($getMetaData->xs_reviwer_email); ?> </span>
													<?php
												endif;
											endif;
											?>
                                        </div>
										<?php
									endif;
								endif;


								if($wur_settings->is_reviewer_website_enabled()) :
									if(!empty($getMetaData->xs_reviwer_website)): ?>
                                        <div class="xs-reviewer-website">
                                            <span> <?php echo esc_html($getMetaData->xs_reviwer_website); ?> </span>
                                        </div>
									<?php endif;
								endif;


								if($wur_settings->is_reviewer_rating_enabled()):
									if(!empty($getMetaData->xs_reviwer_ratting)):
										$reviwerStyleLimit = isset($getMetaData->review_score_limit) ? $getMetaData->review_score_limit : '5';
										$reviwerScoreStyle = isset($getMetaData->review_score_style) ? $getMetaData->review_score_style : 'star';
										if($reviwerScoreStyle == 'star') {
											echo \WurReview\App\Settings::kses(self::wur_ratting_view_star_point($getMetaData->xs_reviwer_ratting, $reviwerStyleLimit));
										} elseif($reviwerScoreStyle == 'point') {
											echo \WurReview\App\Settings::kses(self::wur_ratting_view_point_per($getMetaData->xs_reviwer_ratting, $reviwerStyleLimit));
										} elseif($reviwerScoreStyle == 'percentage') {
											echo \WurReview\App\Settings::kses(self::wur_ratting_view_percentange_per($getMetaData->xs_reviwer_ratting, $reviwerStyleLimit));
										} elseif($reviwerScoreStyle == 'pie') {
											echo \WurReview\App\Settings::kses(self::wur_ratting_view_pie_per($getMetaData->xs_reviwer_ratting, $reviwerStyleLimit));
										} else {
											echo \WurReview\App\Settings::kses(self::wur_ratting_view_star_point($getMetaData->wur_reviwer_ratting, $reviwerStyleLimit));
										}
									endif;
								endif;


								if($wur_settings->is_reviewer_rating_date_enabled()):
									if(!empty($post->post_date)): ?>
                                        <div class="xs-review-date">
                                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>"
                                                  itemprop="datePublished">
												<?php echo esc_html(get_the_date('F d, Y')); ?>
                                            </time>
                                        </div>
									<?php endif;
								endif;


								if($wur_settings->is_review_title_showing_enabled()):
									if(!empty($getMetaData->xs_reviw_title)): ?>
                                        <div class="xs-review-title">
                                            <h3> <?php echo esc_html(get_the_title()); ?> </h3>
                                        </div> <?php
									endif;
								endif;


								if($wur_settings->is_review_text_showing_enabled()):
									if(!empty($getMetaData->xs_reviw_summery)): ?>
                                        <div class="xs-review-summery">
                                            <p> <?php echo esc_html(get_the_content()); ?> </p>
                                        </div>
									<?php endif;
								endif;

								?>
                            </div>

                        </div>

						<?php

					} ?>

                    <div class="xs-review-pagination">
						<?php
						$this->wur_review_pagination($paged, $the_query->max_num_pages);
						?>
                    </div>

					<?php

					wp_reset_postdata();
				} ?>

            </div>

			<?php

			if(!empty($post_review_meta->user_rating->average->enable)):?>
                <div class="total_overview_rattings_value">
					<?php
					echo \WurReview\App\Settings::kses(self::wur_ratting_view_star_point(esc_html($overViewTotal), $rattingRatting));
					?>
                    <span> (<?php echo esc_html($overViewTotal); ?>) </span>
                    <div class="total_overview_rattings_text"> <?php echo esc_html($totalRattingsCount); ?> <?php echo esc_html__('Votes', 'wp-ultimate-review'); ?></div>
                </div>
				<?php

			endif; ?>

        </div>

    </div>

	<?php
}


$show_user_review_form = true; // from pro we will handle different settings!

if(!empty($global_setting['require_login'])) {

	$show_user_review_form = is_user_logged_in();
}

if($show_user_review_form): ?>

    <form action="<?php echo esc_url(get_permalink($post->ID)); ?>" name="xs_review_form_public_data" method="post"
          id="xs_review_form_public_data">

        <div class="xs-review-box public-xs-review-box" id="xs-review-box">
            <h3 class="write-reivew-headding">
				<?php echo esc_html__('Write a Review ', 'wp-ultimate-review'); ?></h3>
			<?php
			if(isset($_COOKIE['xs_review_message']) AND strlen($_COOKIE['xs_review_message']) > 4 && isset($_POST['xs_review_form_public_data'])){
			?>
            <div class="review_message_show">
                <p> <?php echo esc_html__($_COOKIE['xs_review_message'], 'wp-ultimate-review');
					unset($_COOKIE['xs_review_message']); ?></p>
            </div>
            <div class="wur-review-fields">
				<?php
				}
				if(is_array($this->controls) AND sizeof($this->controls) > 0) {

					$showTextFiledWIthOutLogin = ['xs_reviwer_name', 'xs_reviwer_email'];

					foreach($this->controls AS $metaKey => $metaValue):

						// CHeck filed enable
						$checkEnable = (isset($display_setting['form'][$metaKey]) && $display_setting['form'][$metaKey] == 'Yes') ? 'Yes' : 'No';
						if(!is_array($display_setting)) {
							$checkEnable = 'Yes';
						}

						$metaData     = '';
						$displayFiled = '';
						// check login user or not

						if(in_array($metaKey, $showTextFiledWIthOutLogin)) {
							if($checkEnable == 'Yes') {
								$displayFiled = 'display:block;';
								$checkEnable  = 'Yes';
							} else {
								$displayFiled = 'display:none;';
							}

							if(is_user_logged_in()) {
								// current user information
								$current_user = wp_get_current_user();
								if($metaKey == 'xs_reviwer_name') {
									$metaData = (isset($current_user->display_name) && strlen($current_user->display_name) > 0) ? $current_user->display_name : $current_user->first_name . ' ' . $current_user->last_name;
								} elseif($metaKey == 'xs_reviwer_email') {
									$metaData = isset($current_user->user_email) ? $current_user->user_email : '';
								}
							} else {
								$displayFiled = 'display:block;';
								$checkEnable  = 'Yes';
							}
						}


						// check enable filed
						if($checkEnable === 'Yes') {

							// input type, Example: text, checkbox, radio
							$inputType = (isset($metaValue) AND is_array($metaValue) AND array_key_exists('type', $metaValue)) ? $metaValue['type'] : 'text';

							// input name
							$inputName = (isset($metaValue) AND is_array($metaValue) AND array_key_exists('name', $metaValue)) ? $metaValue['name'] : $metaKey;

							// input id
							$inputId = (isset($metaValue) AND is_array($metaValue) AND array_key_exists('id', $metaValue)) ? $metaValue['id'] : $metaKey;

							// input class
							$inputClass = (isset($metaValue) AND is_array($metaValue) AND array_key_exists('class', $metaValue)) ? $metaValue['class'] : $metaKey;

							// Input Ttitle
							$inputTitle   = (isset($metaValue) AND is_array($metaValue) AND array_key_exists('title_name', $metaValue)) ? $metaValue['title_name'] : '';
							$inputRequire = (isset($metaValue) AND is_array($metaValue) AND array_key_exists('require', $metaValue)) ? $metaValue['require'] : 'No';

							// dynamic title
							$inputTitle = (isset($display_setting['form'][$metaKey . '_data']['label']['name']) && $display_setting['form'][$metaKey . '_data']['label']['name'] != '') ? $display_setting['form'][$metaKey . '_data']['label']['name'] : $inputTitle;

							// set require option in fileds
							$requireSet = '';
							if($inputRequire === 'Yes') {
								//$inputTitle .= '<em>(Required)</em> ';
								$requireSet = 'required';
							}
							// Input Options
							$inputOptions = (isset($metaValue) AND is_array($metaValue) AND array_key_exists('options', $metaValue)) ? $metaValue['options'] : [];

							if($metaKey == 'xs_reviwer_ratting') {
								$review_score_style       = isset($global_setting['review_score_style']) ? $global_setting['review_score_style'] : 'star';
								$review_score_style_input = isset($global_setting['review_score_input']) ? $global_setting['review_score_input'] : 'star';

								$review_score_limit = isset($global_setting['review_score_limit']) ? $global_setting['review_score_limit'] : 5;

								if(in_array($review_score_style, ['percentage', 'pie'])):
									$review_score_style_input = 'slider';
								endif;
								?>
                                <div class="xs-review xs-<?php echo esc_attr($inputType); ?>"
                                     style="<?php echo esc_attr($displayFiled); ?>">
									<?php if(in_array($review_score_style_input, array(
										'star',
										'square',
										'movie',
										'bar',
										'pill',
									))): ?>
                                        <div class="xs-review-rating-stars text-center">
                                            <ul id="xs_review_stars">
												<?php for($ratting = 1; $ratting <= $review_score_limit; $ratting++): ?>
                                                    <li class="star-li <?php echo esc_attr($review_score_style_input); ?>  <?php if($ratting == 1) {
														echo esc_html('selected');
													} ?>" data-value="<?php echo esc_attr(intval($ratting)); ?>">
														<?php if($review_score_style_input == 'star') { ?>
                                                            <i class="xs-star dashicons-before dashicons-star-filled"></i>
														<?php } else {
															echo '<span>' . esc_html($ratting) . '<span>';
														} ?>
                                                    </li>
												<?php endfor; ?>
                                            </ul>
                                            <div id="review_data_show"></div>
                                            <input type="hidden" id="ratting_review_hidden"
                                                   name="<?php echo esc_attr($content_meta_key); ?>[<?php echo esc_attr($inputName); ?>]"
                                                   value="1" <?php echo esc_attr($requireSet); ?> />
                                        </div>
									<?php endif;
									if($review_score_style_input == 'slider'):?>
                                        <div class="xs-review-rating-slider text-center">
                                            <div class="xs-slidecontainer">
                                                <input type="range" min="1"
                                                       max="<?php echo intval($review_score_limit); ?>" value="1"
                                                       name="<?php echo esc_attr($content_meta_key); ?>[<?php echo esc_attr($inputName); ?>]"
                                                       class="xs-slider-range" id="xs_review_range">

                                            </div>
                                            <div id="review_data_show"></div>
                                        </div>
									<?php endif;

									?>
                                </div>
								<?php
							} elseif($inputType == 'select' && $metaKey != 'xs_reviwer_ratting') {
								?>
                                <div class="xs-review xs-<?php echo esc_attr($inputType); ?>"
                                     style="<?php echo esc_html($displayFiled); ?>">
                                    <label for="<?php echo esc_attr($inputId); ?>"> <?php echo esc_html($inputTitle) ?>
                                        <select name="<?php echo esc_attr($content_meta_key); ?>[<?php echo esc_attr($inputName); ?>]"
                                                id="<?php echo esc_attr($inputId); ?>"
                                                class="widefat <?php echo esc_attr($inputClass); ?>" <?php echo esc_attr($requireSet); ?> >
											<?php
											if(is_array($inputOptions) AND sizeof($inputOptions) > 0):
												foreach($inputOptions AS $optionsKey => $optionsValue):
													?>
                                                    <option value="<?php echo esc_html($optionsKey); ?>" <?php echo (isset($optionsKey) && $optionsKey == $metaData) ? 'selected' : '' ?> > <?php echo esc_html($optionsValue); ?> </option>
													<?php
												endforeach;
											endif;
											?>
                                        </select>
                                    </label>
                                </div>
								<?php
							} elseif(($inputType == 'radio' OR $inputType == 'checkbox') && $metaKey != 'xs_reviwer_ratting') {
								?>
                                <div class="xs-review xs-<?php echo esc_attr($inputType); ?>"
                                     style="<?php echo esc_attr($displayFiled); ?>">
                                    <label for="<?php echo esc_attr($inputId); ?>"> <?php echo esc_html($inputTitle) ?></label><br/>
									<?php
									if(is_array($inputOptions) AND sizeof($inputOptions) > 0):
										foreach($inputOptions AS $optionsKey => $optionsValue):
											?>
                                            <label for="<?php echo esc_attr($optionsKey); ?>">
                                                <input type="<?php echo esc_attr($inputType); ?>"
                                                       id="<?php echo esc_attr($optionsKey); ?>"
                                                       class="widefat <?php echo esc_attr($inputClass); ?>"
                                                       name="<?php echo esc_attr($content_meta_key); ?>[<?php echo esc_attr($inputName); ?>]"
                                                       value="<?php echo esc_html($optionsKey) ?>" <?php echo (isset($optionsKey) && $optionsKey == $metaData) ? 'checked' : '' ?> <?php echo esc_attr($requireSet); ?> />
												<?php echo esc_html($optionsValue) ?>
                                            </label>
											<?php
										endforeach;
									endif;
									?>
                                </div>
								<?php
							} elseif($inputType == 'textarea' && $metaKey != 'xs_reviwer_ratting') {
								?>
                                <div class="xs-review xs-<?php echo esc_attr($inputType); ?>"
                                     style="<?php echo esc_attr($displayFiled); ?>">
                                    <!-- <label for="<?php echo esc_attr($inputId); ?>" > <?php echo esc_html($inputTitle) ?> -->
                                    <textarea id="<?php echo esc_attr($inputId); ?>"
                                              class="widefat <?php echo esc_attr($inputClass); ?>"
                                              name="<?php echo esc_attr($content_meta_key); ?>[<?php echo esc_attr($inputName); ?>]" <?php echo esc_attr($requireSet); ?>
                                              placeholder="<?php echo esc_attr($inputTitle); ?>"><?php echo esc_attr($metaData); ?></textarea>
                                    <!--</label>-->
                                </div>
								<?php
							} else {
								?>
                                <div class="xs-review xs-<?php echo esc_attr($inputType); ?>"
                                     style="<?php echo esc_attr($displayFiled); ?>">
                                    <!-- <label for="<?php echo esc_attr($inputId); ?>" > <?php echo esc_html($inputTitle) ?> -->
                                    <input type="<?php echo esc_attr($inputType); ?>"
                                           placeholder="<?php echo esc_html($inputTitle); ?>"
                                           id="<?php echo esc_attr($inputId); ?>"
                                           class="widefat <?php echo esc_attr($inputClass); ?>"
                                           name="<?php echo esc_attr($content_meta_key); ?>[<?php echo esc_attr($inputName); ?>]"
                                           value="<?php echo esc_attr($metaData); ?>" <?php echo esc_attr($requireSet); ?> />
                                    <!--</label>-->
                                </div>
							<?php }
						}
					endforeach;
				}
				?>
                <input type="hidden" value="<?php echo esc_attr($this->getPostId); ?>"
                       name="<?php echo esc_attr($content_meta_key); ?>[xs_post_id]"/>
                <input type="hidden" value="<?php echo esc_attr($this->getPostType); ?>"
                       name="<?php echo esc_attr($content_meta_key); ?>[xs_post_type]"/>
                <input type="hidden" value="<?php echo get_current_user_id(); ?>"
                       name="<?php echo esc_attr($content_meta_key); ?>[xs_post_author]"/>

                <div class="xs-review xs-save-button">
                    <button type="submit" name="xs_review_form_public_data"
                            class="xs-btn primary"><?php echo esc_html__('Submit Review', 'wp-ultimate-review'); ?></button>
                </div>

				<?php if(isset($_COOKIE['xs_review_message']) AND strlen($_COOKIE['xs_review_message']) > 4 && isset($_POST['xs_review_form_public_data'])) : ?>
            </div>
		<?php endif; ?>
        </div>
    </form>
	<?php

endif;
