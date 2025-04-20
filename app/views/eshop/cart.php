<?php
renderHeader($data);
?>

<section id="cart_items">
	<div class="container">
		<div class="breadcrumbs" style="background: #f5f5f5; padding: 10px; border-radius: 5px; margin-bottom: 11px;">
			<ol class="breadcrumb">
				<li><a href="<?= BASE_URL ?>">Home</a></li>
				<li class="active">Shopping Cart</li>
				<div class="clear_cart_button pull-right">
					<button class="btn btn-danger" onclick="clearCart()">
						<i class="fa fa-trash-o"></i> Clear Cart
					</button>
				</div>
			</ol>
		</div>
		<div class="table-responsive cart_info">
			<table class="table table-condensed">
				<thead>
					<tr class="cart_menu">
						<td class="image">Item</td>
						<td class="description"></td>
						<td class="price">Price</td>
						<td class="quantity">Quantity</td>
						<td class="total">Total</td>
						<td>

						</td>
					</tr>
				</thead>
				<tbody id="table_body">

				</tbody>
			</table>
		</div>

		<div class="row">
			<div class="col-sm-6 col-sm-offset-6">
				<div class="total_area">
					<ul>
						<li>Sub Total <span class="subtotal_amount">$0.00</span></li>
					</ul>
				</div>
			</div>
		</div>

	<div class="text-center buttons-cart" style="margin-top: 20px;">
		<a href="<?= BASE_URL ?>" class="btn btn-default">
			<i class="fa fa-arrow-left"></i> Continue Shopping
		</a>
		<a href="<?= BASE_URL ?>checkout" class="btn btn-success check_out">
			<i class="fa fa-shopping-cart"></i> Proceed to Checkout
		</a>
	</div>
	</div>
</section> <!--/#cart_items-->


<script type="text/javascript">
	function increaseQuantity(id, maxQty, cartQty) {
		if (cartQty < maxQty) {
			sendData({
				id: id,
				maxQuantity: maxQty,
				cartQuantity: cartQty,
				dataType: 'increase_quantity'
			});
		} else {
			alert('Cannot exceed stock quantity.');
		}
	}

	function decreaseQuantity(id) {
		sendData({
			id: id,
			dataType: 'decrease_quantity'
		});
	}

	function editQuantity(qty, id, maxQty) {
		if (isNaN(qty) || isNaN(id) || qty <= 0 || qty > maxQty)
			return;

		sendData({
			quantity: qty,
			id: id,
			dataType: 'edit_quantity'
		});
	}

	function deleteItem(id) {
		sendData({
			id: id,
			dataType: 'delete_from_cart'
		});
	}

	function getCartProducts() {
		sendData({
			dataType: 'get_cart_products'
		});
	}

	function clearCart() {
		sendData({
			dataType: 'clear_cart'
		});
	}

	function displayTotal() {
		sendData({
			dataType: 'get_total'
		});
	}

	function sendData(data = {}) {
		const ajax = new XMLHttpRequest();

		ajax.addEventListener('readystatechange', function() {
			if (ajax.readyState == 4 && ajax.status == 200) {
				handleResult(ajax.responseText, data.dataType);
			}
		});

		ajax.open("POST", "<?= BASE_URL ?>AjaxCart", true);
		ajax.setRequestHeader('Content-Type', 'application/json');
		ajax.send(JSON.stringify(data));
	}

	function handleResult(result, dataType) {
		try {
			if (result !== '') {
				const obj = JSON.parse(result);
				if (obj.message) {
					alert(obj.message);
				}
				if (obj.cart) {
					document.querySelector("#table_body").innerHTML = obj.cart;
				}
				if (obj.isEmpty) {
					document.querySelector("#table_body").innerHTML = '<tr><td colspan="6" class="text-center">Your cart is empty. <a href="<?= BASE_URL ?>">Continue Shopping</a></td></tr>';
				}
				if (obj.maxQty) {
					alert('Cannot exceed stock quantity.');
				}
				if (obj.total) {
					document.querySelector(".subtotal_amount").innerText = '$' + obj.total;
				}
			} else {
				console.log('empty response');
			}
		} catch (e) {
			console.error('Error parsing JSON response:', e);
		}
	}

	document.addEventListener('DOMContentLoaded', function() {
		getCartProducts();
		displayTotal();
	});
</script>
<?php
renderFooter($data);
?>