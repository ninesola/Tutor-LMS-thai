<?php
$submitted_assignment = tutor_utils()->get_assignment_submit_info($assignment_submitted_id);
$max_mark = tutor_utils()->get_assignment_option($submitted_assignment->comment_post_ID, 'total_mark');

$given_mark = get_comment_meta($assignment_submitted_id, 'assignment_mark', true);
$instructor_note = get_comment_meta($assignment_submitted_id, 'instructor_note', true);

/**
 * @since 1.8.0
 */
$assignment_page_url = admin_url('/admin.php?page=tutor-assignments');
$assignment_id = $submitted_assignment->comment_post_ID;

?>

<div class="submitted-assignment-wrap">
    <a class="back-link" href="<?php echo esc_url($assignment_page_url)?>"><span>&leftarrow;</span> <?php _e('Back','tutor-pro');?></a>
    <!--assignment-info-->
    <div class="tutor-assignment-info">
        <h4>
            <?php _e(get_the_title($submitted_assignment->comment_post_ID),'tutor-pro'); ?>
        </h4>

        <div class="tutor-assignment-info-menu-wrap">
            <div class="tutor-assignment-info-menu">
                <span><?php _e('Course', 'tutor-pro'); ?>:</span>
                <span><?php _e(get_the_title($submitted_assignment->comment_parent));?></span>
            </div>

            <div class="tutor-assignment-info-menu">
                <span><?php _e('Student', 'tutor-pro'); ?>:</span>
                <span><?php _e($submitted_assignment->comment_author);?></span>
            </div>

            <div class="tutor-assignment-info-menu">
                <span><?php _e('Submitted Date', 'tutor-pro'); ?>:</span>
                <span><?php _e(date('F j Y g:i a', strtotime($submitted_assignment->comment_date)), 'tutor-pro');?></span>
            </div>
        </div>
    </div>
    <!--assignment-info end-->

</div> <!-- submitted-assignment-wrap -->

<div class="wrap tutor-assignment-wrap">

    <!--assignment details-->
    <div class="tutor-assignment-details-wrap">

        <div class="tutor-assignment-details">
            <div class="assignment-details">
                <h4>
                    <?php _e('Assignment Description', 'tutor-pro');?>
                </h4>
                <p>
                    <?php 
                        $context = 'post';
                        $allowed_html = wp_kses_allowed_html($context);
                        echo wp_kses($submitted_assignment->comment_content, $allowed_html);
                    ?>
                </p>
            </div>
            <?php
            $attached_files = get_comment_meta($submitted_assignment->comment_ID, 'uploaded_attachments', true);
            if($attached_files) {
                ?>
                <div class="assignment-files">
                    <h4><?php _e('Attach assignment file(s)', 'tutor-pro'); ?></h4>
                    <div class="tutor-assignment-files">
                        <?php
                        $attached_files = json_decode($attached_files, true);
                        if (tutor_utils()->count($attached_files)){
                            $upload_dir = wp_get_upload_dir();
                            $upload_baseurl = trailingslashit(tutor_utils()->array_get('baseurl', $upload_dir));
                            foreach ($attached_files as $attached_file){
                                ?>
                                <div class="uploaded-files">
                                    <!-- // NineSola Edit  -->
                                    <?php
                                        
                                        $n_filename = tutor_utils()->array_get('name', $attached_file);
                                        $n_filepath = $upload_baseurl.tutor_utils()->array_get('uploaded_path', $attached_file);
                                        

                                        if (!function_exists('codepoint_encode')) {
                                            function codepoint_encode($str) {
                                                return substr(json_encode($str), 1, -1);
                                            }
                                        }
                                        
                                        if (!function_exists('codepoint_decode')) {
                                            function codepoint_decode($str) {
                                                return json_decode(sprintf('"%s"', $str));
                                            }
                                        }
                                        
                                        if (!function_exists('mb_internal_encoding')) {
                                            function mb_internal_encoding($encoding = NULL) {
                                                return ($from_encoding === NULL) ? iconv_get_encoding() : iconv_set_encoding($encoding);
                                            }
                                        }
                                        
                                        if (!function_exists('mb_convert_encoding')) {
                                            function mb_convert_encoding($str, $to_encoding, $from_encoding = NULL) {
                                                return iconv(($from_encoding === NULL) ? mb_internal_encoding() : $from_encoding, $to_encoding, $str);
                                            }
                                        }
                                        
                                        if (!function_exists('mb_chr')) {
                                            function mb_chr($ord, $encoding = 'UTF-8') {
                                                if ($encoding === 'UCS-4BE') {
                                                    return pack("N", $ord);
                                                } else {
                                                    return mb_convert_encoding(mb_chr($ord, 'UCS-4BE'), $encoding, 'UCS-4BE');
                                                }
                                            }
                                        }
                                        
                                        if (!function_exists('mb_ord')) {
                                            function mb_ord($char, $encoding = 'UTF-8') {
                                                if ($encoding === 'UCS-4BE') {
                                                    list(, $ord) = (strlen($char) === 4) ? @unpack('N', $char) : @unpack('n', $char);
                                                    return $ord;
                                                } else {
                                                    return mb_ord(mb_convert_encoding($char, 'UCS-4BE', $encoding), 'UCS-4BE');
                                                }
                                            }
                                        }
                                        
                                        if (!function_exists('mb_htmlentities')) {
                                            function mb_htmlentities($string, $hex = true, $encoding = 'UTF-8') {
                                                return preg_replace_callback('/[\x{80}-\x{10FFFF}]/u', function ($match) use ($hex) {
                                                    return sprintf($hex ? '&#x%X;' : '&#%d;', mb_ord($match[0]));
                                                }, $string);
                                            }
                                        }
                                        
                                        if (!function_exists('mb_html_entity_decode')) {
                                            function mb_html_entity_decode($string, $flags = null, $encoding = 'UTF-8') {
                                                return html_entity_decode($string, ($flags === NULL) ? ENT_COMPAT | ENT_HTML401 : $flags, $encoding);
                                            }
                                        }

                                        

                                        $n_filename =  str_replace("u0e","\u0e",$n_filename);
                                        $n_filepath =  str_replace("u0e","\u0e",$n_filepath);
                                        //echo "<br>\u{0e19}.\u{0e2a}";
                                    ?>

                                    <a href="<?php echo codepoint_decode($n_filepath); ?>" target="_blank"><?php echo codepoint_decode($n_filename); ?> <i class="tutor-icon-download"></i></a>
                                    <!-- // NineSola Edit  -->
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="tutor-assignment-evaluation">
            <h4>
                <?php _e('Evaluation', 'tutor-pro');?>
            </h4>
            <form action="" method="post" class="tutor-form-submit-through-ajax" data-toast_success_message="<?php _e('Assignment evaluated', 'tutor'); ?>">

                <?php wp_nonce_field( tutor()->nonce_action, tutor()->nonce ); ?>
                <input type="hidden" value="tutor_evaluate_assignment_submission" name="tutor_action"/>
                <input type="hidden" value="<?php echo $assignment_submitted_id; ?>" name="assignment_submitted_id"/>

                <div class="tutor-form-group">
                    <label for="evaluate_assignment_mark"><?php _e('Your Points', 'tutor-pro'); ?></label>  
                    <div class="tutor-assignment-mark-desc">
                        <input type="text" class="tutor-small-input" id="evaluate_assignment_mark" name="evaluate_assignment[assignment_mark]" value="<?php echo $given_mark ? $given_mark : 0; ?>" pattern="[0-9]+" title="<?php _e('Only number is allowed', 'tutor-pro');?>" required>
                        <p class="desc"><?php echo sprintf(__('Evaluate this assignment out of %s', 'tutor-pro'), "<code>{$max_mark}</code>" ); ?></p>
                    </div>
                </div>

                <div class="tutor-form-group">
                    <label for="evaluate_assignment_instructor"><?php _e('Write a feedback', 'tutor-pro'); ?></label>
                   <textarea name="evaluate_assignment[instructor_note]" id="evaluate_assignment_instructor" rows="6"><?php echo esc_html($instructor_note); ?></textarea>
                    
                </div>

                <div class="tutor-form-group"> 
                    <button type="submit" class="button button-primary"><?php _e('Evaluate this submission', 'tutor-pro'); ?></button>
                </div>

            </form>

        </div>
        <!--assignment evaluation end-->

    </div>
    <!--assignment details-->

</div> <!--wrap end -->