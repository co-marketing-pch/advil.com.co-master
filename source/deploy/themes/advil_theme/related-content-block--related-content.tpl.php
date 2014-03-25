<?php if(!empty($nodes)): ?>
<h2 class="related-content-h2-title avenir-medium"><?php print t('Related Content') ?></h2>
<div class="related-content-block">
  <ul>
    <?php foreach ($nodes as $node_info) : ?>
    <li class="related-content-item node-type-<?php print $node_info['type']; ?>">
      <?php
      //Get all (not empty) fields
      $field_prefix = 'field_';
      foreach($node_info as $field_key => $field_value) {
        if((strpos($field_key, $field_prefix) !== FALSE) && (strpos($field_key, 'fid') !== FALSE) && !empty($field_value)) {
          $file = file_load($field_value);
          ?>
          <div class="video-wrapper">
          <span class="play-video"></span>
          <?php
          print theme('image', array(
                  'path'  => file_create_url($file->uri),
                  'alt'   => $node_info['field_video_thumb_image_alt'],
                  'title' => $node_info['field_video_thumb_image_title']
                ));
          ?>
          </div>
          <h3 class="title-related-content avenir-medium"><?php print $node_info['title']; ?></h3>
          <?php
        }
        elseif((strpos($field_key, $field_prefix) !== FALSE) && !empty($field_value) && $node_info['type'] == 'article') {
          ?>
          <h3 class="title-related-content avenir-medium"><?php print $node_info['title']; ?></h3>
          <?php
          $div_class = str_replace('_', '-', $field_key);
          print '<div class="'. $div_class .'">'. $field_value .'</div>';
        }
      }

      global $base_url;
      
      // Printing the "Read More"
      print l($config['content_types'][$node_info['type']]['link_text'], 'node/'. $node_info['nid'], array(
        'attributes' => array(
          'class' => array(
            'read-more',
            'read-more-'. $node_info['type']
          ),
          'rel' => $base_url .'/related-content/'. $node_info['nid'],
          'title' => $config['content_types'][$node_info['type']]['link_text']
        )
      ));
      ?>
    </li>
    <?php endforeach; ?>
  </ul>
</div>
<div id="related-content-iframe-wrapper">
  <iframe id="related-content-iframe" width="100%" height="0" marginWidth="0" marginHeight="0" frameBorder="0" scrolling="no" allowTransparency="true"></iframe>
</div>
<?php endif; ?>