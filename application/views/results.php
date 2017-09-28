<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>TalentBase</title>
	<link rel="stylesheet" href="<?= base_url(); ?>/assets/css/bootstrap.css">
	<style>
		body{
			background: #f5f5f5;
		}

		.container{
			background: #fff;
			margin-top: 40px;
			margin-bottom: 40px;
			padding: 15px;
		}
	</style>
</head>
<body>
	
	<div class="container">
		<div class="wrapper">
			<table class="table table-striped table-bordered table-condensed">
				<thead>
					<th width="20">Username</th>
					<th width="10">Date</th>
					<th width="10">Star Rating</th>
					<th width="30%">Review Comment</th>
					<th width="30%">Link</th>
				</thead>
				<tbody>
					<?php if(!empty($reviews)): ?>
						<?php foreach ($reviews as $row): ?>
							<tr>
								<td><?= $row["username"]; ?></td>
								<td><?= isset($row["date"]) ? $row["date"] : "N/A"; ?></td>
								<td><?= $row["rating"] ?></td>
								<td><?= $row["comment"]; ?></td>
								<td><?= $row["link"]; ?></td>
							</tr>
						<?php endforeach; ?>
					<?php else: ?>
						<tr>
							<td colspan="5">No reviews were found</td>
						</tr>
					<?php endif; ?>
				</tbody>
			</table>			
		</div>
	</div>
	<script src="<?= base_url(); ?>/assets/js/jquery.min.js"></script>
	<script src="<?= base_url(); ?>/assets/js/jquery.validate.min.js"></script>
</body>
</html>