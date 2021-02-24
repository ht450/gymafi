<?php
require('includes/session.inc.php');
require('includes/conn.inc.php');

if (!isset($_SESSION['user_id'])) {
	header("Location: login.php");
	exit();
}

?>
<!DOCTYPE html>
<html>

<head>
	<?php
	require('includes/head.inc.php');
	?>
</head>

<body>
	<?php
	require('includes/header.inc.php');
	?>

	<div class="container">
		<h1>Diary Entry</h1>

		<?php
		// get users diary enteries
		$sql_get_diary_entry = "
        SELECT *
        FROM WDP_Diary_Entries
        INNER JOIN WDP_Diary_Types
        ON WDP_Diary_Entries.Diary_Type_ID = WDP_Diary_Types.Diary_Type_ID
        WHERE Diary_Entry_ID=?;
        ";
		$stmt_get_diary_entry = $conn->prepare($sql_get_diary_entry);
		$stmt_get_diary_entry->bind_param('s', $_GET['id']);


		// error check
		if (!$stmt_get_diary_entry->execute()) {
			$_SESSION['errors']['db_query'] = "Database error: failed to run query.";
		} else {
			$result_diary_entry = $stmt_get_diary_entry->get_result();
			$diary_entry = $result_diary_entry->fetch_assoc();

			// check the diary entry belongs to the logged in user
			if ($diary_entry['User_ID'] !== $_SESSION['user_id']) {
				$_SESSION['errors']['access_denied'] = "You do not have permission to access that diary.";
				header("Location: diary.php");
				exit();
			} else {
				// set vars
				$entry_id = $diary_entry['Diary_Entry_ID'];
				$entry_type = $diary_entry['Diary_Type'];
				$entry_type_id = $diary_entry['Diary_Type_ID'];
				$entry_date_string = $diary_entry['Date'];
				$entry_date = date_create_from_format('Y-m-d', $entry_date_string);
				$entry_date_long = $entry_date->format('l jS F Y');
				$entry_title = $diary_entry['Title'];
				$entry_content = $diary_entry['Content'];
				$entry_content_short = substr($entry_content, 0, 100);
			}
		}
		?>

		<?php if (isset($_SESSION['flash_message'])) : ?>
			<!-- Flash message -->
			<div class="row">
				<div class="card-panel <?php echo $_SESSION['flash_message_class']; ?>">
					<?php
					echo $_SESSION['flash_message'];
					unset($_SESSION['flash_message']);
					unset($_SESSION['flash_message_class']);
					?>
				</div>
			</div>
		<?php endif; ?>

		<?php if (isset($_SESSION['errors']) && count($_SESSION['errors']) > 0) : ?>
			<!-- Error Messages -->
			<div class="row">
				<div class="card-panel red lighten-2 white-text">
					<?php foreach ($_SESSION['errors'] as $error) : ?>
						<li><?php echo $error; ?></li>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php unset($_SESSION['errors']); ?>

		<form class="col s12" action="includes/diary.inc.php" method="POST" id="edit_diary_entry_form">

			<input type="hidden" name="entry_id" value="<?php echo $entry_id; ?>" />

			<!-- Diary Type -->
			<div class="row">
				<div class="input-field col s12">
					<select id="diary_type_menu" name="diary_type_menu">
						<option value="" disabled selected>Choose your option</option>
						<?php
						// generate drop-down menu options from SQL table
						$sql_diary_type_request = "SELECT * FROM WDP_Diary_Types";
						$result = $conn->query($sql_diary_type_request);
						if (!$result) {
							echo $conn->error;
						}
						while ($row = $result->fetch_assoc()) {
							$menu_type_id = $row['Diary_Type_ID'];
							$diary_type = $row['Diary_Type'];
							$selected = "";
							if ($entry_type_id == $menu_type_id) {
								$selected = "selected='selected'";
							}
							echo "<option value='$entry_type_id' $selected>$diary_type</option>";
						}
						?>
					</select>
					<label for="diary_type_menu">Select Diary Type</label>
				</div>
			</div>

			<!-- Date -->
			<div class="row">
				<div class="input-field col s12">
					<input name="date" id="date" type="date" <?php echo "value='$entry_date_string'"; ?>>
					<label for="date">Date</label>
				</div>
			</div>

			<!-- Title -->
			<div class="row">
				<div class="input-field col s12">
					<input name="title" id="title" type="text" data-length="255" <?php echo "value='$entry_title'"; ?>>
					<label for="title">Title</label>
				</div>
			</div>

			<!-- Content -->
			<div class="row">
				<div class="input-field col s12">
					<textarea name="content" id="content_box" class="materialize-textarea" data-length="10000"><?php echo $entry_content; ?></textarea>
					<label for="content_box">Content</label>
				</div>
			</div>
		</form>

		<form class="col s12" action="includes/diary.inc.php" method="POST" id="delete_diary_entry_form" onsubmit="return confirm('Do you really want to delete this diary entry?');">
			<input type="hidden" name="entry_id" value="<?php echo $entry_id; ?>" />
		</form>

		<div class="row">
			<div class="col s4">
				<!-- Submit Button -->
				<div class="row">
					<button name="edit_diary_entry_button" class="btn waves-effect waves-light" type="submit" form="edit_diary_entry_form">
						Save Diary
						<i class="material-icons right">save</i>
					</button>
				</div>
			</div>
			<div class="col s4"></div>
			<div class="col s4">
				<!-- Delete Button -->
				<div class="row">
					<button name="delete_diary_entry_button" class="btn waves-effect waves-light" type="submit" form="delete_diary_entry_form">
						Delete Diary
						<i class="material-icons right">delete</i>
					</button>
				</div>
			</div>
		</div>

		<div class="row">
            <a href="diary.php" class="btn waves-effect waves-light">
                Return to Diary
                <i class="material-icons left">arrow_back</i>
            </a>
		</div>
		
	</div> <!-- end of container -->


	<?php
	require('includes/footer.inc.php');
	?>

	<script>
		// when document is ready...
		$(document).ready(function() {
			// print ready to console
			console.log("ready!");

			// for hamburger menu sidebar on mobile
			$('.sidenav').sidenav();

			// for dropdown menu
			$('select').formSelect();

			// for character counter in text area
			$('input#title, textarea#content_box').characterCounter();
		});
	</script>
</body>

</html>