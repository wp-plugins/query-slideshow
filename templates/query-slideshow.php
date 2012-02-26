<?php
/*
 * Note: For post queries, the post content (compete or excerpt) will appear
 * as the only field within a row.
 *
 * $style - field style
 * $style_settings - style settings from form_callback
 * $rows - a processed array of rows fields and classes
 * $options -  other query details
 */
?>
<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('.query-<?php print $slug; ?>').cycle(<?php print $style_settings['output']; ?>);
});
</script>
<div class="query-slideshow query-<?php print $slug; ?>">
  <?php foreach($rows as $row): ?>

    <div class="<?php print $row['row_classes']; ?>">
      <?php if ($row['fields']) : ?>

        <?php foreach($row['fields'] as $field): ?>
          <?php if(isset($field['output'])): ?>
            <div class="<?php print $field['classes']; ?>">
              <?php print $field['output']; ?>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>

      <?php endif; ?>
    </div>

  <?php endforeach; ?>
</div>