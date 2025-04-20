<?php
renderHeader($data);
?>

<section id="form" style="margin-top: 5px"><!--form-->
	<div class="container">
		<div class="row" style="text-align: center;">
			<?php displaySessionMessage(); ?>
			<div class="col-sm-4" style="float: none; display: inline-block;">
				<div class="signup-form"><!--sign up form-->
					<h2>New User Signup!</h2>
					<form method="post">
						<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
						<input type="text" name="username" placeholder="Name" required />
						<input type="email" value="" name="email" placeholder="Email Address" required />
						<input type="password" name="password" placeholder="Password" required />
						<input type="password" name="confirm_password" placeholder="Confirm password" required />
						<button type="submit" class="btn btn-default">Signup</button>
					</form>
				</div><!--/sign up form-->
			</div>
		</div>
	</div>
</section><!--/form-->
</body>

</html>

<?php
renderFooter($data);
?>