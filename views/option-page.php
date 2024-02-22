<?php

defined( 'ABSPATH' ) || exit;

$replies = get_option( 'wfr_replies' );
$i       = 0;
?>

<div class="wrap">
    <h1><?php echo esc_html__( 'Frequently Replies List', 'frequently-replies' ); ?></h1>

    <form id="frequently-replies-options-form" name="frequently-replies-options-form" method="post" action="<?php echo esc_url( add_query_arg( array( 'page' => 'wfr-options' ), get_admin_url() . 'edit-comments.php' ) ); ?>">

        <div id="replies-item-wrapper">

            <ul id="replies-item-container">

				<?php
				if ( ! empty( $replies ) ) :
					foreach ( $replies as $reply ) : ?>
                        <li>
                            <div class="form-group row">
                                <label for="replytitle-<?php echo $i; ?>" class="col-sm-2 col-form-label"><?php echo esc_html__( 'Title', 'frequently-replies' ); ?></label>

                                <div class="col-sm-10">
                                    <input type="text" name="replies[<?php echo $i; ?>][title]" class="regular-text" id="replytitle-<?php echo $i; ?>" value="<?php echo esc_attr( $reply['title'] ); ?>">
                                </div>
                            </div>

                            <a class="wfr-remove notice-dismiss" href="#"><?php echo esc_html__( 'Remove', 'frequently-replies' ); ?></a>

                            <div class="form-group row">
                                <label for="replycontent-<?php echo $i; ?>" class="col-sm-2 col-form-label"><?php echo esc_html__( 'Content', 'frequently-replies' ); ?></label>

                                <div class="col-sm-10">
                                    <div id="replycontainer-<?php echo $i; ?>">
                                        <div id="wp-replycontent-wrap-<?php echo $i; ?>" class="wp-core-ui wp-editor-wrap html-active">
                                            <div id="wp-replycontent-editor-container-<?php echo $i; ?>r" class="wp-editor-container">
                                                <textarea class="wp-editor-area" rows="5" tabindex="104" cols="40" name="replies[<?php echo $i; ?>][content]" id="replycontent-<?php echo $i; ?>"><?php echo esc_html( $reply['content'] ); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </li>
						<?php
						$i ++;

					endforeach;
				endif;
				?>
                <li>
                    <div class="form-group row">
                        <label for="replytitle-<?php echo $i; ?>" class="col-sm-2 col-form-label"><?php echo esc_html__( 'Title', 'frequently-replies' ); ?></label>

                        <div class="col-sm-10">
                            <input type="text" name="replies[<?php echo $i; ?>][title]" class="regular-text" id="replytitle-<?php echo $i; ?>" value="">
                        </div>
                    </div>

                    <a class="wfr-remove notice-dismiss" href="#"><?php echo esc_html__( 'Remove', 'frequently-replies' ); ?></a>

                    <div class="form-group row">
                        <label for="replycontent-<?php echo $i; ?>" class="col-sm-2 col-form-label"><?php echo esc_html__( 'Content', 'frequently-replies' ); ?></label>

                        <div class="col-sm-10">
                            <div id="replycontainer-<?php echo $i; ?>">
                                <div id="wp-replycontent-wrap-<?php echo $i; ?>" class="wp-core-ui wp-editor-wrap html-active">
                                    <div id="wp-replycontent-editor-container-<?php echo $i; ?>" class="wp-editor-container">
                                        <textarea class="wp-editor-area" rows="5" tabindex="104" cols="40" name="replies[<?php echo $i; ?>][content]" id="replycontent-<?php echo $i; ?>"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </li>

            </ul>

        </div>

        <div id="wfr-snackbar"></div>

        <div class="wfr-footer">

            <input type="hidden" name="action" value="save_wfr_options">

			<?php wp_nonce_field( 'wfr-options-nonce', 'wfr_nonce' ); ?>

            <a href="#" class="button-secondary alignleft" id="add-another-reply"><?php echo esc_html__( 'Add another reply', 'frequently-replies' ); ?></a>

            <button id="save-options" class="button-primary alignright" type="submit"><?php echo esc_html__( 'Save Options', 'frequently-replies' ); ?></button>

        </div>

    </form>

    <div id="wfr-blocker"></div>

</div>