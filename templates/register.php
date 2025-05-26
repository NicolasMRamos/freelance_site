<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../templates/common.php');
?>

<?php function drawRegisterPage(Session $session) { ?>

  <div id="register_page">
    <h2>Register</h2>
    <form action="/action_dispatcher.php?action=register" id="formreg" method="post" class="register">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <input id="reg_name" type="text" name="name" placeholder="Name" required>
      <input id="reg_username" type="text" name="username" placeholder="Username" required>
      <input id="reg_email" type="text" name="email" placeholder="E-mail" required>
      <input id="reg_password" type="password" name="password" placeholder="Password" required>
      <input id="reg_confirm_password" type="password" name="confirm_password" placeholder="Confirm Password" required>
      <p>Select your desired role(s):</p>
      <div class="checkbox_wrapper">
        <input type="checkbox" id="is_fl" name="is_fl" value="1">
        <label for="is_fl">Freelancer</label>
        <input type="checkbox" id="is_cl" name="is_cl" value="1">
        <label for="is_cl">Client</label>
      </div>
      <div id="register-result" class="result"></div>
      <button class="confirm_button">Register</button>
      <p>Have an account already? Login now:</p>
      <?php drawLoginButton() ?>
    </form>
  </div>
  
<?php } ?>
