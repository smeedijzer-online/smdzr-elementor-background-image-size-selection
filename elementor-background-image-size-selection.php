<?php
/**
 * Plugin Name:     Elementor - Background image size selection
 * Plugin URI:      #
 * Description:     Background image size selection. (https://github.com/elementor/elementor/issues/6778)
 * Version:         1.0.1
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
    class Image_Custom_Size extends \ElementorPro\Modules\DynamicTags\Tags\Base\Data_Tag
    {
        public function get_name()
        {
            return 'post-image-custom-size';
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
            return 'Image (Custom Size)';
        }

        public function get_value(array $options = [])
        {
            $thumbnail_id = $this->get_settings('bg_image')['id'];

            $settings = $this->get_settings_for_display();

            if ($thumbnail_id) {
                $size = $this->get_settings('size');
                $image_data = [
                    'id' => $thumbnail_id,
                    // 'url' => wp_get_attachment_image_src($thumbnail_id, $size)[0],
                    'url' =>  \Elementor\Group_Control_Image_Size::get_attachment_image_src(  $thumbnail_id, 'bg_size', $settings ),
                ];
            }

            return $image_data;
        }

        protected function _register_controls()
        {
            $this->add_control(
                'size',
                [
                    'label' => __('Size', 'elementor-pro'),
                    'type' => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
                ]
            );

            $this->add_group_control(
                \Elementor\Group_Control_Image_Size::get_type(),
                [
                    'name' => 'bg', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `thumbnail_size` and `thumbnail_custom_dimension`.
                    'default' => 'full',
                ]
            );

            $this->add_control(
                'bg_image',
                [
                    'label' => __('Image', 'elementor-pro'),
                    'type' => \Elementor\Controls_Manager::MEDIA,
                ]
            );
        }
    }

    $dynamic_tags->register_tag('Image_Custom_Size');
});
