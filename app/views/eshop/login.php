<?php
renderHeader($data);
?>

<section id="form" style="margin-top: 5px"><!--form-->
	<div class="container">
		<?php displaySessionMessage(); ?>
		<div class="row" style="text-align: center;">
			<div class="col-sm-4" style="float: none; display: inline-block;">
				<div class="login-form"><!--login form-->
					<h2>Login to your account</h2>
					<form action="login" method="post">
						<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" />
						<input type="email" name="email" placeholder="Email Address" required />
						<input type="password" name="password" placeholder="Password" required />
						<div class="remember">
							<input type="checkbox" name="remember" id="remember" class="checkbox">
							<label for="remember">Keep me signed in</label>
						</div>
						<button type="submit" class="btn btn-default">Login</button>
					</form>
					<a href="<?= BASE_URL ?>signup" style="float: left; margin-top: 4px;">Don't have an account? Signup here</a>
				</div><!--/login form-->
			</div>
		</div>
	</div>
</section><!--/form-->
</body>

</html>

<?php
renderFooter($data);
?>