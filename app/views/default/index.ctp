<font size="2">携帯用Tumblrビューア</font><br>
<br>
<?php echo $form->create('tumblr', array('url' => '/login')) ?>
<font size="2">Email</font><br>
<?php echo $form->input('email', array('value' => $email, 'istyle' => 3, 'mode' => 'alphabet')) ?><br>
<br>
<font size="2">Password</font><br>
<?php echo $form->input('password', array('istyle' => 3, 'mode' => 'alphabet')) ?><br>
<br>
<?php echo $form->end('Login') ?>