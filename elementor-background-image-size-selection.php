<?php
/**
 * Plugin Name:     Elementor - Background image size selection for Featured images
 * Description:     Background image size selection for featured images. (https://github.com/elementor/elementor/issues/6778)
 * Version:         1.0.3
 * Author:          Smeedijzer Internet
 * Author URI:      https://www.smeedijzer.net
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
            $settings = $this->get_settings_for_display();

            if ($thumbnail_id) {
                $image_data = [
                        'url' => \Elementor\Group_Control_Image_Size::get_attachment_image_src( $thumbnail_id, 'image', $settings ),
                ];
            } else {
                $image_data = $this->get_settings('fallback');
				
		        if ($image_data && isset($image_data['id'])) {
                    $attachment_id = $image_data['id'];
                    unset($image_data['id']);
			        $image_data['url'] = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $attachment_id, 'image', $settings );
		        }
            }

            return $image_data;
        }

        protected function _register_controls()
        {
            $this->add_group_control(
                \Elementor\Group_Control_Image_Size::get_type(),
                [
                    'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
                    'default' => 'large',
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
