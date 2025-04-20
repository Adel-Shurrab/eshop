<?php
renderHeader($data);

if (isset($data['error'])) {
    echo '<div class="alert alert-danger" style="margin-top: 20px;">';
    if (is_array($data['error'])) {
        foreach ($data['error'] as $error) {
            echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '<br>';
        }
    } else {
        echo nl2br(htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'));
    }
    echo '</div>';
}
?>

<section id="cart_items">
    <div class="container">
        <div class="breadcrumbs" style="background: #f5f5f5; padding: 10px; border-radius: 5px;">
            <ol class="breadcrumb">
                <li><a href="<?= BASE_URL ?>">Home</a></li>
                <li class="active">Check Out</li>
            </ol>
        </div><!--/breadcrums-->

        <?php if (isset($data['cart']) && !empty($data['cart'])): ?>
            <div class="register-req">
                <p>Please use Register And Checkout to easily get access to your order history, or use Checkout as Guest</p>
            </div><!--/register-req-->

            <div class="shopper-informations">
                <form id="checkoutForm" method="post">
                    <div class="shopper-informations">
                        <div class="row">
                            <div class="col-sm-8 clearfix">
                                <div class="bill-to">
                                    <p>Bill To</p>
                                    <div class="form-one">
                                        <input type="text" name="address_1" placeholder="Address 1 *" required autofocus>
                                        <input type="text" name="address_2" placeholder="Address 2">
                                        <input type="text" name="zip" placeholder="Zip / Postal Code *" required>
                                    </div>
                                    <div class="form-two">
                                        <select name="countries" class="js-country" oninput="getStates(this.value)" required>
                                            <option disabled selected value="0">-- Country --</option>
                                            <?php if (isset($data['countries']) && is_array($data['countries'])): ?>
                                                <?php foreach ($data['countries'] as $country): ?>
                                                    <option value="<?= htmlspecialchars($country['id'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($country['country'], ENT_QUOTES, 'UTF-8') ?></option>
                                                <?php endforeach ?>
                                            <?php endif ?>
                                        </select>
                                        <select name="states" class="js-state" required>
                                            <option value="0">-- State / Province / Region --</option>
                                        </select>
                                        <input type="text" name="phone" placeholder="Mobile Phone *" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="order-message">
                                    <p>Shipping Order</p>
                                    <textarea name="message" placeholder="Notes about your order, Special Notes for Delivery" rows="16"></textarea>
                                    <!-- Shipping Options -->
                                    <div class="shipping-options">
                                        <label>
                                            <input type="checkbox" name="shipping_same" checked>
                                            <i class="fa fa-truck"></i>
                                            Shipping address same as billing
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="checkout-actions" style="margin: 30px 0;">
                            <div class="row">
                                <div class="col-sm-6 text-left">
                                    <a href="<?= BASE_URL ?>cart" class="btn btn-default">
                                        <i class="fa fa-arrow-left"></i>
                                        Back to Cart
                                    </a>
                                </div>

                                <div class="col-sm-6 text-right">
                                    <button type="submit" form="checkoutForm" class="btn btn-success">
                                        <i class="fa fa-arrow-right"></i>
                                        Continue
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        <?php else: ?>
            <div class="alert alert-warning" style="margin-top: 20px;">
                <strong>Your cart is empty!</strong> Please add items to your cart before proceeding to checkout.
            </div>
        <?php endif; ?>
    </div>
</section> <!--/#cart_items-->

<script>
    function getStates(id) {
        sendData({
            id: id,
            dataType: 'get_states'
        });
    }

    function sendData(data = {}) {
        const ajax = new XMLHttpRequest();

        ajax.addEventListener('readystatechange', function() {
            if (ajax.readyState == 4) {
                if (ajax.status == 200) {
                    handleResult(ajax.responseText);
                } else {
                    console.error('Error: ' + ajax.status);
                    console.error('Response: ' + ajax.responseText);
                }
            }
        });

        ajax.open("POST", "<?= BASE_URL ?>ajaxOrder", true);
        ajax.setRequestHeader('Content-Type', 'application/json');
        ajax.send(JSON.stringify(data));
    }

    function handleResult(result) {
        try {
            if (result !== '') {
                const obj = JSON.parse(result);
                if (obj.message) {
                    alert(obj.message);
                } else if (obj.states) {
                    const stateSelect = document.querySelector('.js-state');
                    stateSelect.innerHTML = '<option>-- State / Province / Region --</option>';
                    obj.states.forEach(state => {
                        const option = document.createElement('option');
                        option.value = state.id;
                        option.textContent = state.state;
                        stateSelect.appendChild(option);
                    });
                }
            } else {
                console.log('empty response');
            }
        } catch (e) {
            console.error('Error parsing JSON response:', e);
            console.error('Response: ' + result);
        }
    }
</script>

<?php
renderFooter($data);
?>