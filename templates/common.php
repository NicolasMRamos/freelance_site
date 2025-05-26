<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../util/session_class.php');
?>

<?php function drawIndexButton() { ?>

  <div class="index_button">
    <a href="/index.php" class="button">Back to Main Page</a>
  </div>

<?php } ?>

<?php function drawLoginButton() { ?>
 
 <div class="login_button">
   <a href="/login.php" class="button">Login</a>
 </div>

<?php } ?>

<?php function drawLogoutButton() { ?>

  <div class="logout_button">
    <a href='/action_dispatcher.php?action=logout' class="button">Logout</a>
  </div>

<?php } ?>

<?php function drawRegisterButton() { ?>
  
  <div class="register_button">
    <a href="/register.php" class="button">Register</a>
  </div>

<?php } ?>

<?php function drawNamedButton(Session $session) { ?>

  <div class="named_button">
    <a href="/profile.php" class="button"><?=htmlspecialchars($session->getName())?></a>
  </div>
  
<?php } ?>

<?php function drawHeader(Session $session, array $scripts=[]) { ?>

  <!DOCTYPE html>
  <html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/common.css">
    <link rel="stylesheet" href="/css/buttons.css">
    <link rel="stylesheet" href="/css/forms.css">
    <link rel="stylesheet" href="/css/login.css">
    <link rel="stylesheet" href="/css/register.css">
    <link rel="stylesheet" href="/css/profile.css">
    <link rel="stylesheet" href="/css/edit_profile.css">
    <link rel="stylesheet" href="/css/service.css">
    <link rel="stylesheet" href="/css/create_service.css">
    <link rel="stylesheet" href="/css/order.css">
    <link rel="stylesheet" href="/css/reviews.css">
    <link rel="stylesheet" href="/css/responsive.css">
    <link rel="stylesheet" href="/css/messages.css">
    <title>Freelancing Net</title>

    <?php if (!empty($scripts)): 
            foreach($scripts as $script): ?>
            <script src="/js/<?= htmlspecialchars($script) ?>.js" defer></script>
            <?php endforeach;
          endif; ?>
  </head>
  <body>
    <header>
      <h1>Freelancing Net</h1>
      <h2>For all your needs</h2>
      <?php
        if ($session->isLoggedIn()) {
          drawNamedButton($session);
          drawLogoutButton();
        } else {
          drawLoginButton();
          drawRegisterButton();
        }
        drawIndexButton();
      ?>
    </header>

<?php } ?>

<?php function drawFooter() { ?>
  
    <footer>
      <p>&copy; Freelancing Net</p>
      <?php drawIndexButton(); ?>
    </footer>
    </body>
  </html>

<?php } ?>
