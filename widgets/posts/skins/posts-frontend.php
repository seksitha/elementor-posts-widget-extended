<?php

class me {

    protected function render_thumbnail_angular (){

        $thumbnail = $this->get_instance_value('thumbnail');

        if ('none' === $thumbnail && !Plugin::elementor()->editor->is_edit_mode()) {
            return;
        }

        $settings = $this->parent->get_settings();
        $setting_key = $this->get_control_id('thumbnail_size');
        $settings[$setting_key] = [
            'id' => get_post_thumbnail_id(),
        ];

        $thumbnail_html = Group_Control_Image_Size::get_attachment_image_html($settings, $setting_key);

        if (empty($thumbnail_html)) {
            return;
        } 
        ?>
            <a class="elementor-post__thumbnail__link" href="<?php echo $this->current_permalink; ?>">
                <div class="elementor-post__thumbnail">
                    <img
                        width="<?php echo $_wp_additional_image_sizes[$thumbnail]['width']; ?>"
                        height="<?php echo $_wp_additional_image_sizes[$thumbnail]['height']; ?>"
                        src="{{post.thumbnail}}"
                        class="attachment-<?php echo $thumbnail; ?> size-<?php echo $thumbnail;?>"
                        alt=""
                        srcset="{{post.thumbnail}} 300w"
                        sizes="100vw"
					/>
                </div>
            </a>
		<?php
    }
}