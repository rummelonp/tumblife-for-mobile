<?php
// sanitize
function s($text)
{
  $text = str_replace('<p>', '', $text);
  $text = str_replace('</p>', '<br>', $text);
  $text = str_replace('<blockquote>', '', $text);
  $text = str_replace('</blockquote>', '', $text);
  return $text;
}
?>
<font size="2">[<a href="#top" name="top" accesskey="*">*</a>] <?php echo $pager['page'] + 1 ?> page.</font>
<hr>
<?php foreach ($posts as $index => $post) : ?>
  <font size="2">
    <?php $accesskey = ($index == 9)? 0: ($index + 1)  ?>
    [<a href="#<?php echo $accesskey ?>" name="<?php echo $accesskey ?>" accesskey="<?php echo $accesskey ?>"><?php echo $accesskey ?></a>] 
    <?php echo ($pager['page'] * $pager['num']) + ($index + 1) ?>: 
    <a href="<?php echo $post['tumblelog']['attributes']['url'] ?>"><?php echo $post['tumblelog']['attributes']['name'] ?></a>:
  </font><br>
  <?php switch ($post['attributes']['type']) :
          case 'regular' : ?>
      <?php if (array_key_exists('regular-title', $post)) : ?>
        <font size="2"><?php echo s($post['regular-title']) ?></font><br>
      <?php endif ?>
      <font size="2"><?php echo s($post['regular-body']) ?></font><br>
      <?php break ?>
    <?php case 'link' : ?>
      <font size="2"><a href="<?php echo $post['link-url'] ?>"><?php echo $post['link-text'] ?></a></font><br>
      <?php if (array_key_exists('link-description', $post)) : ?>
        <font size="2"><?php echo s($post['link-description']) ?></font><br>
      <?php endif ?>
      <?php break; ?>
    <?php case 'quote' : ?>
      <font size="2"><?php echo s($post['quote-text']) ?></font><br>
      <?php if (array_key_exists('quote-source', $post)) : ?>
        <font size="2"><?php echo s($post['quote-source']) ?></font><br>
      <?php endif ?>
      <?php break ?>
    <?php case 'photo' : ?>
      <font size="2"><a href="<?php echo $post['photo-url'][0] ?>"><img src="<?php echo $post['photo-url'][3] ?>" width="220px"></a></font><br>
      <?php if (array_key_exists('photo-caption', $post)) : ?>
        <font size="2"><?php echo s($post['photo-caption']) ?></font><br>
      <?php endif ?>
      <?php break ?>
    <?php case 'conversation' : ?>
      <font size="2"><?php echo s($post['conversation-text']) ?></font><br>
      <?php break ?>
    <?php case 'video' : ?>
      <font size="2">[Video] <?php echo s($post['video-caption']) ?></font><br>
      <?php break ?>
    <?php case 'audio' : ?>
      <font size="2">[Audio] <?php echo s($post['audio-caption']) ?></font><br>
      <?php break ?>
    <?php case 'answer' : ?>
      <font size="2"><?php echo s($post['question']) ?></font><br>
      <font size="2"><?php echo s($post['answer']) ?></font><br>
      <?php break ?>
  <?php endswitch ?>
  <?php
    echo
      $form->create('tumblr', array('url' => '/like/'.$post['attributes']['id'])).
      $form->hidden('email', array('value' => $email)).
      $form->hidden('password', array('value' => $password)).
      $form->hidden('post-id', array('value' => $post['attributes']['id'])).
      $form->hidden('reblog-key', array('value' => $post['attributes']['reblog-key'])).
      $form->hidden('page', array('value' => $pager['page'])).
      $form->end('like');
    echo
      $form->create('tumblr', array('url' => '/reblog/'.$post['attributes']['id'])).
      $form->hidden('email', array('value' => $email)).
      $form->hidden('password', array('value' => $password)).
      $form->hidden('post-id', array('value' => $post['attributes']['id'])).
      $form->hidden('reblog-key', array('value' => $post['attributes']['reblog-key'])).
      $form->hidden('page', array('value' => $pager['page'])).
      $form->end('reblog');
   ?>
  <hr>
<?php endforeach ?>
<font size="2">[<a href="#bottom" name="bottom" accesskey="#">#</a>] <?php echo $pager['page'] + 1 ?> page.</font><br>
<br>
<?php
  if ($pager['has_previous']) {
    echo
      $form->create('tumblr', array('url' => '/dashboard/page/'.($pager['page'] - 1))).
      $form->hidden('email', array('value' => $email)).
      $form->hidden('password', array('value' => $password)).
      $form->hidden('page', array('value' => $pager['page'] - 1)).
      $form->end('previous');
  }
  if ($pager['has_next']) {
    echo
      $form->create('tumblr', array('url' => '/dashboard/page/'.($pager['page'] + 1))).
      $form->hidden('email', array('value' => $email)).
      $form->hidden('password', array('value' => $password)).
      $form->hidden('page', array('value' => $pager['page'] + 1)).
      $form->end('next');
  }
?>
<br>
<br>
<?php
  if ($pager['has_previous']) {
    echo
      $form->create('tumblr', array('url' => '/dashboard/page/0')).
      $form->hidden('email', array('value' => $email)).
      $form->hidden('password', array('value' => $password)).
      $form->hidden('page', array('value' => 0)).
      $form->end('first');
  }
  if ($pager['has_next']) {
    echo
      $form->create('tumblr', array('url' => '/dashboard/page/25')).
      $form->hidden('email', array('value' => $email)).
      $form->hidden('password', array('value' => $password)).
      $form->hidden('page', array('value' => 25)).
      $form->end('last');
  }
  echo
    $form->create('tumblr', array('url' => '/')).
    $form->end('top');
?>