<?php
  $currUserName = '';
  $currentUser  = wp_get_current_user();
if ( $currentUser ) {
	$currUserName = sanitize_text_field( trim( $currentUser->user_login ) );
}
?>
<div class="contact-form ">
	<form method="post" action="<?php echo esc_url( get_site_url() . '/wp-admin/admin-post.php' ); ?>">
		<input type="hidden" name="action" value="gamipress_discord_send_support_mail">
		<input type="hidden" name="current_url" value="<?php echo esc_url( ets_gamipress_discord_get_current_screen_url() ); ?>">
	  <div class="ets-container">
		<div class="top-logo-title">
		  <img src="<?php echo esc_url( CONNECT_GAMIPRESS_DISCORD_PLUGIN_DIR_URL . 'admin/images/ets-logo.png' ); ?>" class="img-fluid company-logo" alt="">
		  <h1><?php esc_html_e( 'ExpressTech Softwares Solutions Pvt. Ltd.', 'connect-gamipress-discord-addon' ); ?></h1>
		  <p><?php esc_html_e( 'ExpressTech Software Solution Pvt. Ltd. is the leading Enterprise WordPress development company.', 'connect-gamipress-discord-addon' ); ?><br>
		  <?php esc_html_e( 'Contact us for any WordPress Related development projects.', 'connect-gamipress-discord-addon' ); ?></p>
		</div>
		<div class="form-fields-box ">
		  <div class="ets-row ets-mt-5 ets-align-items-center">
			<div class="ets-col-7 ets-offset-md-1">
			  <div class="contact-fields pr-100">
				<div class="ets-form-group">
				  <label><?php esc_html_e( 'Full Name', 'connect-gamipress-discord-addon' ); ?></label>
				  <input type="text" name="ets_user_name" value="<?php echo esc_html( $currUserName ); ?>" class="form-control contact-input" placeholder="<?php esc_html_e( 'Write Your Full Name', 'connect-gamipress-discord-addon' ); ?>">
				  <?php wp_nonce_field( 'send_support_mail', 'ets_discord_send_support_mail' ); ?>
				</div>
				<div class="ets-form-group">
				  <label><?php esc_html_e( 'Contact Email', 'connect-gamipress-discord-addon' ); ?></label>
				  <input type="text" name="ets_user_email" class="form-control contact-input" value="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" placeholder="<?php esc_html_e( 'Write Your Email', 'connect-gamipress-discord-addon' ); ?>">
				</div>
				<div class="ets-form-group">
				  <label><?php esc_html_e( 'Subject', 'connect-gamipress-discord-addon' ); ?></label>
				  <input type="text" name="ets_support_subject" class="form-control contact-input" placeholder="<?php esc_html_e( 'Write Your Subject', 'connect-gamipress-discord-addon' ); ?>" required="">
				</div>
				<div class="ets-form-group">
				  <label><?php esc_html_e( 'Message', 'connect-gamipress-discord-addon' ); ?></label>
				  <textarea name="ets_support_msg" class="form-control contact-textarea" required=""></textarea>
				</div>
				<div class="submit-btn d-flex align-items-center w-100 pt-3">
				  <input type="submit" name="support_mail_submit" id="save" class="btn btn-submit ets-bg-green" value="Submit">                  
				  <a href="skype:ravi.soni971?chat" class="btn btn-skype ml-auto"><?php esc_html_e( 'Skype', 'connect-gamipress-discord-addon' ); ?></a>
				</div>
			  </div>
			</div>
			<div class="ets-col-3">
			  <div class="right-side-box">
				<div class="contact-details d-inline-block w-100 mb-4">
				  <div class="top-icon-title d-flex align-items-center w-100">
					<i class="fas fa-envelope title-icon fa-lg fa-inverse" aria-hidden="true"></i>
					<p><?php esc_html_e( 'Email', 'connect-gamipress-discord-addon' ); ?></p>
				  </div>
				  <div class="contact-body mt-3">
					<p><a href="mailto:contact@expresstechsoftwares.com"><?php echo esc_html( 'contact@expresstechsoftwares.com' ); ?></a></p>
					<p><a href="mailto:vinod.tiwari@expresstechsoftwares.com"><?php echo esc_html( 'vinod.tiwari@expresstechsoftwares.com' ); ?></a></p>
				  </div>
				</div>
				<div class="contact-details d-inline-block w-100 mb-4">
				  <div class="top-icon-title d-flex align-items-center w-100">
					<i class="fab fa-skype title-icon fa-lg fa-inverse" aria-hidden="true"></i>
					<p><?php esc_html_e( 'Skype', 'connect-gamipress-discord-addon' ); ?></p>
				  </div>
				  <div class="contact-body mt-3">
					<p><?php esc_html_e( 'ravi.soni971', 'connect-gamipress-discord-addon' ); ?></p>
				  </div>
				</div>
				<div class="contact-details d-inline-block w-100">
				  <div class="top-icon-title d-flex align-items-center w-100">
					<i class="fab fa-whatsapp title-icon fa-lg fa-inverse" aria-hidden="true"></i>
					<p><?php esc_html_e( 'Whatsapp / Phone', 'connect-gamipress-discord-addon' ); ?></p>
				  </div>
				  <div class="contact-body mt-3">
					<p><?php echo esc_html( '+91-9806724185' ); ?></p>
				  </div>
				</div>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
  </form>
</div>
