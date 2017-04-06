<?php
/**
 * Add/Edit Subscription Reminder
 *
 * @package     Restrict Content Pro
 * @subpackage  Admin/Reminders/Subscription Reminders View
 * @copyright   Copyright (c) 2017, Restrict Content Pro
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.9
 */

$reminder_type = isset( $_GET['rcp_reminder_type'] ) ? $_GET['rcp_reminder_type'] : 'renewal';
$notices       = new RCP_Reminders();
$notice_id     = isset( $_GET['notice'] ) ? absint( $_GET['notice'] ) : 0;
$default       = array( 'type' => $reminder_type, 'subject' => '', 'send_period' => 'today', 'message' => '' );
$notice        = ! empty( $notice_id ) ? $notices->get_notice( $notice_id ) : $default;
?>
<div class="wrap">
	<h1>
		<?php echo $notice_id ? __( 'Edit Reminder Notice', 'rcp' ) : __( 'Add Reminder Notice', 'rcp' ); ?> -
		<a href="<?php echo esc_url( admin_url( 'admin.php?page=rcp-settings#emails' ) ); ?>" class="add-new-h2"><?php _e( 'Go Back', 'rcp' ); ?></a>
	</h1>

	<form id="rcp-edit-reminder-notice" method="POST">
		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row" valign="top">
					<label for="rcp-notice-type"><?php _e( 'Notice Type', 'rcp' ); ?></label>
				</th>
				<td>
					<select id="rcp-notice-type" name="type">
						<?php foreach ( $notices->get_notice_types() as $type => $label ) : ?>
							<option value="<?php echo esc_attr( $type ); ?>" <?php selected( $type, $notice['type'] ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>

					<p class="description"><?php _e( 'Is this a renewal notice or an expiration notice?', 'rcp' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top">
					<label for="rcp-notice-subject"><?php _e( 'Email Subject', 'rcp' ); ?></label>
				</th>
				<td>
					<input type="text" name="subject" id="rcp-notice-subject" class="regular-text" value="<?php echo esc_attr( stripslashes( $notice['subject'] ) ); ?>"/>

					<p class="description"><?php _e( 'The subject line of the reminder notice email', 'rcp' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top">
					<label for="rcp-notice-period"><?php _e( 'Email Period', 'rcp' ); ?></label>
				</th>
				<td>
					<select name="period" id="rcp-notice-period">
						<?php foreach ( $notices->get_notice_periods() as $period => $label ) : ?>
							<option value="<?php echo esc_attr( $period ); ?>"<?php selected( $period, $notice['send_period'] ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>

					<p class="description"><?php _e( 'When should this email be sent?', 'rcp' ); ?></p>
				</td>
			</tr>
			<tr>
				<th scope="row" valign="top">
					<label for="rcp-notice-message"><?php _e( 'Email Message', 'rcp' ); ?></label>
				</th>
				<td>
					<?php wp_editor( wpautop( wp_kses_post( wptexturize( $notice['message'] ) ) ), 'message', array( 'textarea_name' => 'message' ) ); ?>
					<p class="description"><?php _e( 'The email message to be sent with the reminder notice. The following template tags can be used in the message:', 'rcp' ); ?></p>
					<?php echo rcp_get_emails_tags_list(); ?>
				</td>
			</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="hidden" name="rcp-action" value="add_edit_reminder_notice"/>
			<input type="hidden" name="notice-id" value="<?php echo esc_attr( $notice_id ); ?>"/>
			<?php wp_nonce_field( 'rcp_add_edit_reminder', 'rcp_add_edit_reminder_nonce' ); ?>
			<input type="submit" value="<?php echo $notice_id ? esc_attr__( 'Edit Reminder Notice', 'rcp' ) : esc_attr__( 'Add Reminder Notice', 'rcp' ); ?>" class="button-primary"/>
		</p>
	</form>
</div>