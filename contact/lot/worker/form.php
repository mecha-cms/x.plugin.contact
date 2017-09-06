<form class="form-contact" id="<?php echo $anchor[1]; ?>" action="<?php echo $url->current; ?>/<?php echo Plugin::state('contact', 'path'); ?>" method="post">
  <?php echo $message; ?>
  <?php echo Form::hidden('token', $token); ?>
  %{topic}%
  <p class="form-contact-input form-contact-input:author">
    <label for="form-contact-input:author"><?php echo $language->contact_author; ?></label>
    <span><?php echo Form::text('*author', null, $language->contact_f_author, ['classes' => ['input', 'block'], 'id' => 'form-contact-input:author']); ?></span>
  </p>
  <p class="form-contact-input form-contact-input:email">
    <label for="form-contact-input:email"><?php echo $language->contact_email; ?></label>
    <span><?php echo Form::email('*email', null, $language->contact_f_email, ['classes' => ['input', 'block'], 'id' => 'form-contact-input:email']); ?></span>
  </p>
  <p class="form-contact-input form-contact-input:link">
    <label for="form-contact-input:link"><?php echo $language->contact_link; ?></label>
    <span><?php echo Form::url('link', null, $language->contact_f_link, ['classes' => ['input', 'block'], 'id' => 'form-contact-input:link']); ?></span>
  </p>
  <div class="form-contact-textarea form-contact-textarea:content p">
    <label for="form-contact-textarea:content"><?php echo $language->contact_content; ?></label>
    <div><?php echo Form::textarea('*content', null, $language->contact_f_content, ['classes' => ['textarea', 'block'], 'id' => 'form-contact-textarea:content']); ?></div>
  </div>
  %{kick}%
  <p class="form-contact-button form-contact-button:state">
    <label for="form-contact-button:state"></label>
    <span><?php echo Form::submit('state', null, $language->contact_submit, ['classes' => ['button', 'button-submit', 'set'], 'id' => 'form-contact-button:state']); ?></span>
  </p>
</form>