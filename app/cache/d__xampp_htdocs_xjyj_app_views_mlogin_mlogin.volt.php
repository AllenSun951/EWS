
<h1>Sorry!</h1>

<p>很遗憾，注意输入正确的“用户名、密码、验证码、类别的选择”! 如果您是新用户，请点击注册链接进行注册，谢谢！</p>

<?php
  use Phalcon\Tag;

  echo "<h1>Back!</h1>"

  //$this->tag->linkTo("signup","Sign Up Here!");

?>
<?=$this->tag->linkTo("public/login.php","点击这里返回!") ?>
