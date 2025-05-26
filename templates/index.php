<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../util/session_class.php');
require_once(__DIR__ . '/../templates/common.php');
require_once(__DIR__ . '/../templates/service.php');
require_once(__DIR__ . '/../templates/review.php');
?>

<?php function drawHeaderIndex(Session $session) { ?>

  <!DOCTYPE html>
  <html lang="en-US">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/index_hbf.css">
    <link rel="stylesheet" href="/css/buttons.css">
    <link rel="stylesheet" href="/css/services_index.css">
    <link rel="stylesheet" href="/css/responsive_index.css">
    <title>Freelancing Net</title>
    <script src="/js/filter.js"></script>
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
    ?>
  </header>

<?php } ?>

<?php function drawFooterIndex() { ?>

  <footer>
    <p>&copy; Freelancing Net</p>
  </footer>
  </body>
</html>

<?php } ?>

<?php function drawIndexPage(PDO $db, Session $session, array $services, ?User $user, array $categories) { ?>

  <div id="services">
    <div id="service_header">
      <?php if ($user && $user->isFreelancer()): ?>
        <h2>Create Service</h2>
        <?php drawCreateServiceButton(); ?>
      <?php endif; ?>
      <h2>Browse Services</h2>
    </div>

    <div id="service_filter">
      <h2>Filter Services</h2>
      <form method="post" action="/index.php" class="filter">
        <input type="hidden" name="action" value="filter">
        <input type="hidden" name="csrf" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">

        <div class="filter_container">
          <input id="category_filter" name="category_filter" type="checkbox" onchange="toggleSection('category_section')">
          <label for="category_filter">Category</label>
          <div id="category_section" style="display:none;" class="filter-option">
            <select id="service_category" name="service_category">
              <option value="">< Click here to Select ></option>
              <?php foreach ($categories as $category): ?>
                <option value="<?= htmlspecialchars($category) ?>">
                  <?= htmlspecialchars(ucfirst($category)) ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>

        <div class="filter_container">
          <input id="price_filter" name="price_filter" type="checkbox" onchange="toggleSection('price_section')">
          <label for="price_filter">Max Price</label>
          <div id="price_section" style="display:none;" class="filter-option">
            <input type="number" min="1" max="5000" name="max_price" placeholder="Price (1-5000)">
          </div>
        </div>

        <div class="filter_container">
          <input id="delivtime_filter" name="delivtime_filter" type="checkbox" onchange="toggleSection('delivery_section')">
          <label for="delivtime_filter">Max Delivery Time</label>
          <div id="delivery_section" style="display:none;" class="filter-option">
            <input type="number" min="1" max="99" name="max_delivtime" placeholder="Delivery Time (1-99)">
          </div>
        </div>

        <div class="filter_container">
          <input id="rating_filter" name="rating_filter" type="checkbox" onchange="toggleSection('rating_section')">
          <label for="rating_filter">Min Rating</label>
          <div id="rating_section" style="display:none;" class="filter-option">
            <input type="number" min="1" max="5" name="min_rating" placeholder="Rating (1-5)">
          </div>
        </div>

        <button type="submit" class="confirm_button">Apply Filters</button>
      </form>
    </div>

    <?php if (empty($services)): ?>
      <span id="empty-serv-msg">No services to show currently. Try changing the filter or wait until someone adds a new service.</span>
    <?php else: ?>
      <?php foreach ($services as $service): ?>
        <?php $average = Reviews::getAverageRating($db, $service->service_id); ?>
        <?php drawService($session,$service, $db, $average); ?>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

<?php } ?>