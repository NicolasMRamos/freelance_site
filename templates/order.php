<?php
declare(strict_types = 1);
require_once(__DIR__ . '/../util/orders_class.php');
require_once(__DIR__ . '/../util/service_class.php');
require_once(__DIR__ . '/../util/user_class.php');
require_once(__DIR__ . '/../templates/common.php');
?>

<?php function drawCreateOrderButton() { ?>

<div class="create_order_button">
  <a href="/create_order.php" class="button">Place Order</a>
</div>

<?php } ?>

<?php function drawOrder(Session $session, PDO $db, Orders $order, Services $service) { ?>

  <section class="order_container">
    <?php if ($session->isLoggedIn() && User::getUser($db, $session->getUser_id())->is_admin): ?>
      <div class="admin-id">ID: <?= $order->order_id ?></div>
    <?php endif; ?>
    <div class ="order_details">
      <h3>Order for:</h3>
      <a href="/service.php?id=<?= $service->service_id ?>"><?=htmlspecialchars($service->service_title)?></a> 
      <p>Status: <?= $order->status ?></p>
    </div>
  </section>

<?php } ?>

<?php function drawCustomOrder(Session $session, PDO $db, CustomOrders $corder, Services $service) { ?>

  <section class="order_container">
    <?php if ($session->isLoggedIn() && User::getUser($db, $session->getUser_id())->is_admin): ?>
      <div class="admin-id">ID: <?= $corder->custom_order_id ?></div>
    <?php endif; ?>
    <div class ="order_details">
      <h3>Custom Order for:</h3>
      <a href="/service.php?id=<?= $service->service_id ?>"><?=htmlspecialchars($service->service_title)?></a>
      <p>Status: <?= $corder->status ?></p>
    </div>
  </section>

<?php } ?>


<?php function drawClientOrders(PDO $db, array $orders, array $corders, Session $session) { ?>
  <div id="cl_track">
    <div id="cl_orders">
      <h2>Track Orders</h2>
      <?php if(empty($orders)): ?>
        <span id="no_order_cl">You haven't made any orders yet.</span>
      <?php else:
              foreach($orders as $order):
                $service = Services::getServiceFromID($db, $order->service_id);
                drawOrder($session, $db, $order, $service);
              endforeach;
          endif;
      ?>
      </div>
      <div id="cl_corders">
        <h2>Track Custom Orders</h2>
          <?php if(empty($corders)): ?>
            <span id="no_corder_cl">You haven't made any custom orders yet.</span>
          <?php else:
                  foreach($corders as $corder):
                    $service = Services::getServiceFromID($db, $corder->service_id);
                    drawCustomOrder($session, $db, $corder, $service);
                  endforeach;
              endif;
          ?>
      </div>
  </div>
<?php } ?>

<?php function drawOrderForm(Session $session, int $service_id) { ?>
    
  <section id="create_order">
    <h2>Place an Order</h2>
    <form action="/action_dispatcher.php?action=create_order" id="formorder" method="post" class="order">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <input type="hidden" name="service_id" value="<?= $service_id ?>">

      <label for="payment">Choose payment type:</label>
      <select id="payment" name="payment">
        <option value="Cash">Cash</option>
        <option value="Card">Card</option>
        <option value="MBWay">MBWay</option>
      </select>
      <div id="order-result" class="result"></div>

      <button class="confirm_button" form="formorder">Place Order</button>
    </form>
    <h2>Place a Custom Order</h2>
    <form action="/action_dispatcher.php?action=create_order" id="formcorder" method="post" class="order">
      <input type="hidden" name="csrf" value="<?= htmlspecialchars($session->getCSRFToken()) ?>">
      <input type="hidden" name="service_id" value="<?= $service_id ?>">

      <label for="co_title">Title</label>
      <input type="text" id="co_title" name="co_title" placeholder="Title" required>
      
      <label for="co_desc">Description</label>
      <textarea id="co_desc" name="co_desc" rows="10" placeholder="Describe your custom order" required ></textarea>

      <label for="co_price">Price</label>
      <input type="number" id="co_price" name="co_price" placeholder="Price" required>

      <label for="co_deliv_time">Delivery Time</label>
      <input type="number" id="co_deliv_time" name="co_deliv_time" placeholder="Delivery Time" required>

      <label for="payment">Choose payment type:</label>
      <select id="payment" name="payment">
        <option value="Cash">Cash</option>
        <option value="Card">Card</option>
        <option value="MBWay">MBWay</option>
      </select>

      <div id="corder-result" class="result"></div>

      <button class="confirm_button" form="formcorder">Place Custom Order</button>
    </form>
  </section>

<?php
}
?>
