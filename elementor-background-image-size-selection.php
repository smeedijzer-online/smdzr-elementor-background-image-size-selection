<?php
/**
 * Plugin Name:     Elementor - Background image size selection
 * Plugin URI:      #
 * Description:     Background image size selection. (https://github.com/elementor/elementor/issues/6778)
 * Version:         1.0
 * Author:          Smeedijzer
 * Author URI:      #
 **/
 
 if ( ! defined( 'ABSPATH' ) ) {
	die( 'Invalid request.' );
}

/**
 * Elementor featured image with custom size
 */
add_action('elementor/dynamic_tags/register_tags', function ($dynamic_tags) {
    class Featured_Image_Custom_Size extends \ElementorPro\Modules\DynamicTags\Tags\Base\Data_Tag
    {
        public function get_name()
        {
            return 'post-featured-image-custom-size';
        }

        public function get_group()
        {
            return \ElementorPro\Modules\DynamicTags\Module::POST_GROUP;
        }

        public function get_categories()
        {
            return [\ElementorPro\Modules\DynamicTags\ACF\Module::IMAGE_CATEGORY];
        }

        public function get_title()
        {
            return 'Featured image (Custom Size)';
        }

        public function get_value(array $options = [])
        {
            $thumbnail_id = get_post_thumbnail_id();

            if ($thumbnail_id) {
                $size = $this->get_settings('size');
                $image_data = [
                        'id' => $thumbnail_id,
                        'url' => wp_get_attachment_image_src($thumbnail_id, $size)[0],
                ];
            } else {
                $image_data = $this->get_settings('fallback');
            }

            return $image_data;
        }

        protected function _register_controls()
        {
            $this->add_control(
                    'size',
                    [
                            'label' => __('Size', 'elementor-pro'),
                            'type' => \Elementor\Controls_Manager::TEXT,
                    ]
            );

            $this->add_control(
                    'fallback',
                    [
                            'label' => __('Fallback', 'elementor-pro'),
                            'type' => \Elementor\Controls_Manager::MEDIA,
                    ]
            );
        }
    }

    $dynamic_tags->register_tag('Featured_Image_Custom_Size');
});