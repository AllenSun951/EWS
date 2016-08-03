
<?php
  use Phalcon\Tag;
  echo "<h2>Sign up Using this form</h2>";
?>
<?=$this->tag->form("signup/register") ?>

<p>
 <label for="name">Name</label>
 <?=$this->tag->textField("name") ?>
</p>
<p>
 <label for="email">Email</label>
 <?=$this->tag->textField("email") ?>
</p>
<p>
 <?=$this->tag->submitButton("Register") ?>   
</p>
</form> 
