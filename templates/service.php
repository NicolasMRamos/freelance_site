<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../util/user_class.php');
require_once(__DIR__ . '/../util/session_class.php');
require_once(__DIR__ . '/../util/service_class.php');
require_once(__DIR__ . '/../util/reviews_class.php');
require_once(__DIR__ . '/../templates/common.php');
?>

<?php function drawCreateServiceButton() { ?>

<div class="create_service_button">
  <a href="/create_service.php" class="button">Click here to create a new Service</a>
</div>

<?php } ?>

<?php function drawService(Session $session, Services $service, PDO $db, $average) { ?>

  <section class="service_container">
    <?php if ($session->isLoggedIn() && User::getUser($db, $session->getUser_id())->is_admin): ?>
      <div class="admin-id">ID: <?= $service->service_id ?></div>
    <?php endif; ?>
    <h3><a href="/service.php?id=<?= $service->service_id ?>"><?=htmlspecialchars($service->service_title)?></a></h3>
    <div class ="service_details">
      <p class ="service_description"><?=htmlspecialchars($service->service_desc);?></p>
      <p class ="category"><strong>Category: </strong><?=htmlspecialchars($service->category);?></p>
      <p class ="price"><strong>Price:</strong> <?=$service->price;?> €<br></p>
      <p class ="delivery_time"><strong>Delivery Time:</strong> <?=$service->delivery_time;?> Day(s)</p>
      <p class ="active_status"><strong>Status:</strong> <?= $service->active ? 'Active' : 'Closed' ?>
      <p class ="freelancer_username"><strong>Created by:</strong> <?= htmlspecialchars($service->getFreelancerName($db)) ?>
      <div class="avg_rating">
          <?php if ($average === null): ?>
            <p><strong>Avg Rating:</strong> No reviews.</p>
          <?php else: ?>
            <p><strong>Avg Rating:</strong>
              <?php
                $rounded = (int) round($average);
                for ($i = 0; $i < $rounded;        $i++) echo '★';
                for ($i = $rounded; $i < 5;        $i++) echo '☆';
              ?>
              <span>(<?= number_format($average,1) ?>/5.0)</span>
            </p>
          <?php endif; ?>
      </div>
    </div>
  </section>

<?php } ?>

<?php function drawServiceForm(Session $session, array $categories) { ?>
  
  <section id="create_service">
    <h2>Create a New Service</h2>
    <form action="/action_dispatcher.php?action=create_service" id="formserv" method="post" class="service">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <label for="service_title">Title</label>
      <input type="text" id="service_title" name="service_title" placeholder="Service title" required>
      
      <label for="service_desc">Description</label>
      <textarea id="service_desc" name="service_desc" rows="10" placeholder="Describe the service you're providing" required ></textarea>
      
      <label for="service_category">Category</label>
      <select id="service_category" name="service_category">
        <option value="">< Click here to Select ></option>
        <?php foreach ($categories as $category): ?>
          <option value="<?= htmlspecialchars($category) ?>">
            <?= htmlspecialchars(ucfirst($category)) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <label for="service_price">Price (€)</label>
      <input type="number" id="service_price" name="service_price" min="0" step="0.01" value="0.00" required>
      
      <label for="service_delivery_time">Expected Delivery Time (Days)</label>
      <input type="number" id="service_delivery_time" name="service_delivery_time" min="1" value="1" required>

      <div id="service-result" class="result"></div>

      <button class="confirm_button" form="formserv">Create Service</button>
    </form>
  </section>

<?php } ?>

 <?php function drawFreelancerServices(PDO $db, array $services, Session $session) { ?>
  <div id="fl_track">
    <h2>Track Services</h2>
    <div id="fl_services">
      <?php if(!empty($services)){
        foreach($services as $service){
          $average = Reviews::getAverageRating($db, $service->service_id);
          drawService($session, $service, $db, $average);
        }
      } else {
        ?>
        <div id="empty_serv_fl"> 
          <span id="no_serv_fl">You haven't created any services. Try creating one:</span>
          <?php drawCreateServiceButton(); ?>
        </div>
        <?php
      }
      ?>
    </div>
  </div>
<?php } ?>
