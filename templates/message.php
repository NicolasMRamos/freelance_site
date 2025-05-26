<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../util/session_class.php');
require_once(__DIR__ . '/../util/messages_class.php');
require_once(__DIR__ . '/../templates/common.php');
?>


<?php function drawMessage(PDO $db, Messages $message) {

    $date = date('F j, Y', strtotime($message->message_date));
    ?>
    <section class="message_container">
      <h3 class="message_title">
        <?= htmlspecialchars($message->message_title) ?>
      </h3>
      <div class="message_info">
        <span class="message_date"><?= htmlspecialchars($date) ?></span>
        <span class="message_client">from <?= htmlspecialchars($message->getClientUsername($db)) ?></span>
      </div>
      <?php if ($message->message_text !== ''): ?>
        <div class="message_text">
          <p><?= nl2br(htmlspecialchars($message->message_text)) ?></p>
        </div>
      <?php endif; ?>
    </section>

<?php } ?>

<?php function drawMessages(PDO $db, array $messages) { ?>

  <div id="messages">
    <h2>Messages</h2>
    <?php if (empty($messages)): ?>
      <p id="empty-message-msg">No messages yet.</p>
    <?php else: ?>
      <?php foreach ($messages as $message): ?>
        <?php drawMessage($db, $message); ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

<?php } ?>

<?php function drawMessageForm(Session $session, int $service_id) { ?>

  <section id="leave_message">
    <h2>Leave a Message</h2>
    <form id="formMessage" action="/action_dispatcher.php?action=create_message" method="post" class="message">

      <input type="hidden" name="csrf" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

      <input type="hidden" name="service_id" value="<?= (int)$service_id ?>">

      <label for="message_title"><strong>Subject</strong></label>
      <input type="text" id="message_title" name="message_title" placeholder="Message subject" required>

      <label for="message_text"><strong>Your Message</strong></label>
      <textarea id="message_text" name="message_text" rows="4" placeholder="Write your message here…" required></textarea>

      <div id="message-result" class="result"></div>

      <button type="submit" class="confirm_button" form="formMessage">Send Message</button>
    </form>
  </section>

<?php } ?>
