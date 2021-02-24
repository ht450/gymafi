<!-- header -->
<header>
	<!-- navbar -->
	<div class="mynavbar">
		<nav>
			<div class="container">

				<div class="col">
					<a href="#" data-target="sidenav" class="sidenav-trigger"><i class="material-icons">menu</i></a>
				</div>

				<!-- logo -->
				<div class="col">
					<a href="index.php" class="brand-logo">Logo</a>
				</div>

				<!-- Login or Profile button -->
				<div class="col">
					<ul id="nav-mobile" class="right">
						<?php
						// if logged in
						if (isset($_SESSION['user_id'])) {
							// Profile button
							echo "<li><a class='waves-effect waves-light btn' href='profile.php'> Profile <i class='material-icons left'>person_outline</i></a></li>";
							// and Log Out
							echo "<li><a class='waves-effect waves-light btn' href='includes/logout.inc.php'> Log Out <i class='material-icons left'>exit_to_app</i></a></li>";
						}
						// if not
						else {
							// Log In
							echo "<li><a class='waves-effect waves-light btn' href='login.php'> Log In <i class='material-icons left'>lock_outline</i></a></li>";
							// and Register
							echo "<li><a class='waves-effect waves-light btn' href='register.php'> Register <i class='material-icons left'>assignment</i></a></li>";
						}
						?>
					</ul>
				</div>


				<!-- pages -->
				<div class="col">
					<ul id="nav-mobile" class="right hide-on-med-and-down">

						<li><a href="about.php">About</a></li>
						<li><a href="contact.php">Contact</a></li>

						<?php
						// private pages
						if (isset($_SESSION['user_id'])) {
							echo '<li><a href="diary.php">Diary</a></li>';
							echo '<li><a href="classes.php">Classes</a></li>';
						}

						// role restricted
						if (isset($_SESSION['user_role'])) {
							// viewable to coach & admin
							if ($_SESSION['user_role'] == 'coach' || $_SESSION['user_role'] == 'admin') {
								echo '<li><a class="waves-effect waves-light btn" href="dashboard.php">Dashboard <i class="material-icons left">dashboard</i></a></li>';
							}
							// viewable to only admin
							if ($_SESSION['user_role'] == 'admin') {
								//echo '<li><a class="waves-effect waves-light btn" href="dashboard.php">Dashboard</a></li>';
							}
						}
						?>
					</ul>
				</div>

			</div>
		</nav>
	</div>

	<!-- sidenav -->
	<ul class="sidenav" id="sidenav">

		<li><a href="about.php">About</a></li>
		<li><a href="contact.php">Contact</a></li>

		<?php
		// private pages

		// viewable to all registered users
		if (isset($_SESSION['user_id'])) {
			echo '<li><a href="diary.php">Diary</a></li>';
			echo '<li><a href="classes.php">Classes</a></li>';
		}

		// role restricted
		if (isset($_SESSION['user_role'])) {

			// viewable to coach & admin
			if ($_SESSION['user_role'] == 'coach' || $_SESSION['user_role'] == 'admin') {
				echo '<li><a class="waves-effect waves-light btn" href="dashboard.php">Dashboard <i class="material-icons left">dashboard</i> </a></li>';
			}

			// viewable to only admin
			if ($_SESSION['user_role'] == 'admin') {
				//echo '<li><a class="waves-effect waves-light btn" href="dashboard.php">Dashboard <i class="material-icons left">dashboard</i> </a></li>';
			}
		}
		?>

		<li><a class="sidenav-close btn deep-orange lighten-1" href="#!">Close</a></li>

	</ul>


</header>

<!-- main content -->
<main>