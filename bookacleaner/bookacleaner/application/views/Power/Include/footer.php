
				</div>
			</div>
		</div>
		<?php
		if(isset($_GET['success'])) {
		?>
			<script type="text/javascript">
			Swal.fire(
				'Good job!',
				'Successfully!',
				'success'
			);
			</script>
		<?php
		}
		?>

		<?php
		if(isset($_GET['error'])) {
			$error = ($_GET['error'] != "") ? $_GET['error'] : "There\'s something wrong!";
		?>
			<script type="text/javascript">
			Swal.fire(
				'Error!',
				'<?php echo $error; ?>',
				'error'
			);
			</script>
		<?php
		}
		?>
	</body>
</html>