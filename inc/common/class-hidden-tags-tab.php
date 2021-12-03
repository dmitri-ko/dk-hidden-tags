<?php

namespace DK_Hidden_Tags\Inc\Common;

/**
 * Product tabs tab class
 */
class Hidden_Tags_Tab {
	/**
	 * Metadata associated with tab
	 *
	 * @var Meta
	 */
	private Meta $meta;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->meta = new Meta(
			Settings::get_instance()->meta_key,
			__( 'Hidden tags', 'dk-hidden-tags' ),
		);
	}

	/**
	 * Add data field for hidden tags
	 */
	public function add_hidden_tags_data_fields() {
		?>

		<div id="hidden_tags_data" class="panel woocommerce_options_panel">
			<?php
			woocommerce_wp_textarea_input(
				array(
					'id'          => $this->meta->get_name(),
					'class'       => 'hidden-tags',
					'label'       => __( 'Hidden tags', 'dk-hidden-tags' ),
					'description' => __( 'Hidden tags for Product search', 'dk-hidden-tags' ),
					'desc_tip'    => true,
					'rows'        => 30,
				)
			);
			?>
		</div>
		<?php
	}

	/**
	 * Add new tab to product tabs
	 *
	 * @param array $product_data_tabs the tabs array.
	 *
	 * @return array
	 */
	public function add_hidden_tags_data_tab( $product_data_tabs ) {
		$product_data_tabs['hidden-tags-tab'] = array(
			'label'  => __( 'Hidden search tags', 'dk-hidden-tags' ),
			'target' => 'hidden_tags_data',
		);

		return $product_data_tabs;
	}


	/**
	 *  Save data for hidden tags field
	 *
	 * @param int $post_id the post_id.
	 */
	public function process_hidden_tags_meta_fields_save( $post_id ) {
		/* TODO: Implement nonce check */
		$this->meta->set( $post_id, sanitize_text_field( wp_unslash( $_POST['_dk_hidden_tags'] ?? '' ) ) );
	}

}
