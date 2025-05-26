<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../util/reviews_class.php');
require_once(__DIR__ . '/../util/user_class.php');
require_once(__DIR__ . '/../templates/common.php');
?>

<?php function drawReviews(PDO $db, Session $session, array $reviews) { ?>
  <section id="reviews">
    <h2>Reviews</h2>
    <?php if (empty($reviews)):  ?>
      <span id="empty-review-msg">No reviews to show yet.</span>
      <?php else:
      foreach ($reviews as $review) {
          drawReview($db, $session,$review);
      }
    endif ?>
  </section>
<?php } ?>


<?php function drawReview(PDO $db, Session $session, Reviews $review){ 

    $date = date('F j, Y', strtotime($review->review_date));?>
    <section class="review_container">
      <?php if ($session->isLoggedIn() && User::getUser($db, $session->getUser_id())->is_admin): ?>
        <div class="admin-id">ID: <?= $review->review_id ?></div>
      <?php endif; ?>
      <div class="review_header">
        <h3 class="review_title">
          <?= htmlspecialchars($review->review_title) ?>
        </h3>
        <div class="review_rating">
          <?php 
            for ($i = 0; $i < $review->rating; $i++) echo '★';
            for ($i = $review->rating; $i < 5;      $i++) echo '☆';
          ?>
        </div>
      </div>
      <div class="review_info">
        <span class="review_date"><?= htmlspecialchars($date) ?></span>
        <span class="review_client">by <?= htmlspecialchars($review->getClientName($db)) ?></span>
      </div>
      <?php if ($review->review_text !== ''): ?>
        <div class="review_text">
          <p><?= nl2br(htmlspecialchars($review->review_text)) ?></p>
        </div>
      <?php endif; ?>
    </section>
<?php } ?>

<?php function drawReviewForm(Session $session, int $service_id) { ?>
  
  <section id="leave-review">
    <h2>Leave a Review</h2>

    <form id="formReview"  action="/action_dispatcher.php?action=create_review" method="post" class="review">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

      <input type="hidden" name="service_id" value="<?= (int)$service_id ?>">

      <input type="hidden" name="rating" id="rating-input"value="">
      <div class="star-rating">
        <label><strong>Rating:</strong></label>
        <div id="star-container">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <span class="star" data-value="<?= $i ?>">☆</span>
          <?php endfor; ?>
        </div>
      </div>

      <label for="review_title">Title</label>
      <input type="text" id="review_title" name="review_title" placeholder="Review Title" required>

      <label for="review_text">Your Review</label>
      <textarea id="review_text" name="review_text" rows="4" placeholder="Describe your experience..." required></textarea>

      <div id="review-result" class="result"></div>

      <button class="confirm_button" form="formReview">Submit Review</button>

    </form>
  </section>
<?php
}
?>

