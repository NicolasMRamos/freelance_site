<?php
declare(strict_types = 1);
?>

<?php function drawEditButton() { ?>

  <div class="edit_button">
    <a href="/editprof.php" class="button">Edit Profile</a>
  </div>

<?php } ?>

<?php function drawBackProfileButton() { ?>

  <div class="backprofile_button">
    <a href="/profile.php" class="button">Back to Profile</a>
  </div>

<?php } ?>

<?php function drawProfileInfo(User $user) { ?>

  <div id="user_info">
    <h2>Account Information</h2><br>
    <p>Username: <?=htmlspecialchars($user->username);?></p><br>
    <p>Name: <?=htmlspecialchars($user->name);?></p><br>
    <p>Email: <?=htmlspecialchars($user->email);?></p><br>
    <p>Account Created on <?=htmlspecialchars($user->register_date);?></p><br>
    <?php drawEditButton(); ?>
  </div>

<?php } ?>


<?php function drawEditProfile(Session $session) { ?>
  
  <div id="edit_profile">
    <h2>Edit Your Information</h2>
    <form action="/action_dispatcher.php?action=edit_profile" id="formedit" method="post" class="edit">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <div id="can_edit">
        <div id="left_side_edit">
          <div id="email_edit">
            <p>Change Email</p>
            <input id="ep_email" type="text" name="email" placeholder="Enter new e-mail">
          </div>
          <div id="name_edit">
            <p>Change Name</p>
            <input id="ep_name" type="text" name="name" placeholder="Enter new name">
          </div>
          <div id="username_edit">
            <p>Change Username</p>
            <input id="ep_username" type="text" name="username" placeholder="Enter new username">
          </div>
        </div>
        <div id="pass_edit">
          <p>Change Password</p>
          <input id="ep_password" type="password" name="password" placeholder="Enter new password">
          <input id="ep_confirm_password" type="password" name="confirm_password" placeholder="Confirm new password">
        </div>
      </div>
      <div id="pass_conf_edit">
        <p>Confirm your identity to apply changes:</p>
        <input id="ep_cur_password" type="password" name ="current_password" placeholder="Enter current password">
      </div>
      <div id="edit-result" class="result"></div>
      <button class="confirm_button" form="formedit">Confirm Changes</button>
    </form>
    <?php drawBackProfileButton() ?>
  </div>

<?php } ?>

<?php function drawAdminCentral(Session $session) { ?>

  <div id="admin_central">
    <h2>Manage Site</h2>
    <form action="/action_dispatcher.php?action=admin" id="formadmin" method="post" class="admin">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <div id="admin_actions">
        <div id="admin_delete" class="indiv_action">
          <h3>Deletion Actions</h3>
          <input type="text" name="del_cat" placeholder="Insert category name">
          <input type="text" name="del_serv" placeholder="Insert service id">
          <input type="text" name="del_review" placeholder="Insert review id">
          <input type="text" name="del_mes" placeholder="Insert message id">
          <input type="text" name="del_ord" placeholder="Insert order id">
          <input type="text" name="del_cus_ord" placeholder="Insert custom order id">
        </div>
        <div id="admin_user_manage" class="indiv_action">
          <h3>Promote User</h3>
          <input type="text" name="pro_user" placeholder="Insert username">
          <h3>Demote User</h3>
          <input type="text" name="dem_user" placeholder="Insert username">
          <h3>Delete User</h3>
          <input type="text" name="del_user" placeholder="Insert username">
        </div>
        <div id="admin_add" class="indiv_action">
          <h3>Add Category</h3>
          <input type="text" name="add_cat" placeholder="Insert category name">
        </div>
      </div>
      <div id="admin-result" class="result"></div>
      <button class="confirm_button" form="formadmin">Apply Actions</button>
    </form>
  </div>

<?php } ?>
