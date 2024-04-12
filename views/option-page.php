<?php

defined( 'ABSPATH' ) || exit;

$replies = get_option( 'wpfr_replies' );
$i       = 0;
?>

<div class="wrap">
    <h1><?php echo esc_html__( 'Frequently Replies List', 'frequently-replies' ); ?></h1>

    <form id="frequently-replies-options-form" name="frequently-replies-options-form" method="post" action="<?php echo esc_url( add_query_arg( array( 'page' => 'wpfr-options' ), get_admin_url() . 'edit-comments.php' ) ); ?>">

        <div id="replies-item-wrapper">

            <ul id="replies-item-container">

				<?php
				if ( ! empty( $replies ) ) :
					foreach ( $replies as $reply ) : ?>
                        <li>
                            <div class="form-group row">
                                <label for="replytitle-<?php echo esc_attr( $i ); ?>" class="col-sm-2 col-form-label"><?php echo esc_html__( 'Title', 'frequently-replies' ); ?></label>

                                <div class="col-sm-10">
                                    <input type="text" name="replies[<?php echo esc_attr( $i ); ?>][title]" class="regular-text" id="replytitle-<?php echo esc_attr( $i ); ?>" value="<?php echo esc_attr( $reply['title'] ); ?>">
                                </div>
                            </div>

                            <a class="wpfr-remove notice-dismiss" href="#"><?php echo esc_html__( 'Remove', 'frequently-replies' ); ?></a>

                            <div class="form-group row">
                                <label for="replycontent-<?php echo esc_attr( $i ); ?>" class="col-sm-2 col-form-label"><?php echo esc_html__( 'Content', 'frequently-replies' ); ?></label>

                                <div class="col-sm-10">
                                    <div id="replycontainer-<?php echo esc_attr( $i ); ?>">
                                        <div id="wp-replycontent-wrap-<?php echo esc_attr( $i ); ?>" class="wp-core-ui wp-editor-wrap html-active">
                                            <div id="wp-replycontent-editor-container-<?php echo esc_attr( $i ); ?>r" class="wp-editor-container">
                                                <textarea class="wp-editor-area" rows="5" tabindex="104" cols="40" name="replies[<?php echo esc_attr( $i ); ?>][content]" id="replycontent-<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $reply['content'] ); ?></textarea>
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
                        <label for="replytitle-<?php echo esc_attr( $i ); ?>" class="col-sm-2 col-form-label"><?php echo esc_html__( 'Title', 'frequently-replies' ); ?></label>

                        <div class="col-sm-10">
                            <input type="text" name="replies[<?php echo esc_attr( $i ); ?>][title]" class="regular-text" id="replytitle-<?php echo esc_attr( $i ); ?>" value="">
                        </div>
                    </div>

                    <a class="wpfr-remove notice-dismiss" href="#"><?php echo esc_html__( 'Remove', 'frequently-replies' ); ?></a>

                    <div class="form-group row">
                        <label for="replycontent-<?php echo esc_attr( $i ); ?>" class="col-sm-2 col-form-label"><?php echo esc_html__( 'Content', 'frequently-replies' ); ?></label>

                        <div class="col-sm-10">
                            <div id="replycontainer-<?php echo esc_attr( $i ); ?>">
                                <div id="wp-replycontent-wrap-<?php echo esc_attr( $i ); ?>" class="wp-core-ui wp-editor-wrap html-active">
                                    <div id="wp-replycontent-editor-container-<?php echo esc_attr( $i ); ?>" class="wp-editor-container">
                                        <textarea class="wp-editor-area" rows="5" tabindex="104" cols="40" name="replies[<?php echo esc_attr( $i ); ?>][content]" id="replycontent-<?php echo esc_attr( $i ); ?>"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </li>

            </ul>

        </div>

        <div id="wpfr-snackbar"></div>

        <div class="wpfr-footer">

            <input type="hidden" name="action" value="save_wpfr_options">

			<?php wp_nonce_field( 'wpfr-options-nonce', 'wpfr_nonce' ); ?>

            <a href="#" class="button-secondary alignleft" id="add-another-reply"><?php echo esc_html__( 'Add another reply', 'frequently-replies' ); ?></a>

            <button id="save-options" class="button-primary alignright" type="submit"><?php echo esc_html__( 'Save Options', 'frequently-replies' ); ?></button>

        </div>

    </form>

    <div id="wpfr-blocker"></div>

</div>