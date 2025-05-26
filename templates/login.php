<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../templates/common.php');
?>

<?php function drawLoginPage(Session $session) { ?>
  
  <div id="login_page">
    <h2>Login</h2>
    <form action="/action_dispatcher.php?action=login" id="formlog" method="post" class="login">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <input id="login_username" type="text" name="username" placeholder="Username" required/>
      <input id="login_password" type="password" name="password" placeholder="Password" required/>
      <div id="login-result" class="result"></div>
      <button class="confirm_button" form="formlog">Login</button>
      <p>Don't have an account? Register now:</p>
      <?php drawRegisterButton() ?>
    </form>
  </div>

<?php } ?>
