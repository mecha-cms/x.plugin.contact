<form class="form-contact" id="<?php echo $anchor[1]; ?>" action="<?php echo $url->clean; ?>/<?php echo Plugin::state('contact', 'path'); ?>" method="post">
  <?php echo $message; ?>
  <?php if (isset($lot['topic'])): ?>
  <?php if (is_array($lot['topic']) || is_object($lot['topic'])): ?>
  <p class="form-contact-select form-contact-select:title">
    <label for="form-contact-select:title"><?php echo $language->contact_title; ?></label>
    <span><?php echo Form::select('*title', (array) $lot['topic'], null, ['class[]' => ['select', 'block'], 'id' => 'form-contact-select:title']); ?></span>
  </p>
  <?php else: ?>
  <?php echo Form::hidden('title', $lot['topic']); ?>
  <?php endif; ?>
  <?php else: ?>
  <p class="form-contact-input form-contact-input:title">
    <label for="form-contact-input:title"><?php echo $language->contact_title; ?></label>
    <span><?php echo Form::text('*title', null, $language->contact_f_title, ['class[]' => ['input', 'block'], 'id' => 'form-contact-input:title']); ?></span>
  </p>
  <?php endif; ?>
  <p class="form-contact-input form-contact-input:author">
    <label for="form-contact-input:author"><?php echo $language->contact_author; ?></label>
    <span><?php echo Form::text('*author', null, $language->contact_f_author, ['class[]' => ['input', 'block'], 'id' => 'form-contact-input:author']); ?></span>
  </p>
  <p class="form-contact-input form-contact-input:email">
    <label for="form-contact-input:email"><?php echo $language->contact_email; ?></label>
    <span><?php echo Form::email('*email', null, $language->contact_f_email, ['class[]' => ['input', 'block'], 'id' => 'form-contact-input:email']); ?></span>
  </p>
  <p class="form-contact-input form-contact-input:link">
    <label for="form-contact-input:link"><?php echo $language->contact_link; ?></label>
    <span><?php echo Form::url('link', null, $language->contact_f_link, ['class[]' => ['input', 'block'], 'id' => 'form-contact-input:link']); ?></span>
  </p>
  <div class="form-contact-textarea form-contact-textarea:content p">
    <label for="form-contact-textarea:content"><?php echo $language->contact_content; ?></label>
    <div><?php echo Form::textarea('*content', null, $language->contact_f_content, ['class[]' => ['textarea', 'block'], 'id' => 'form-contact-textarea:content']); ?></div>
  </div>
  <?php echo Form::hidden('token', $token); ?>
  <?php if (isset($lot['kick'])): ?>
  <?php echo Form::hidden('kick', $lot['kick']); ?>
  <?php endif; ?>
  <p class="form-contact-button form-contact-button:state">
    <label for="form-contact-button:state"></label>
    <span><?php echo Form::submit('state', null, $language->contact_submit, ['class[]' => ['button', 'button-submit'], 'id' => 'form-contact-button:state']); ?></span>
  </p>
</form>