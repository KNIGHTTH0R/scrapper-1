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

			<form id="form" action="<?= base_url(); ?>home/process" method="post">
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label for="txtUrl" class="control-label">Enter Url</label>
							<input type="url" id="txtUrl" name="txtUrl" required placeholder="Enter Url" class="form-control">
						</div>
					</div>
				</div>
				<button id="btnSubmit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</div>
	<script src="<?= base_url(); ?>/assets/js/jquery.min.js"></script>
	<script src="<?= base_url(); ?>/assets/js/jquery.validate.min.js"></script>
	<script>
		$(document).ready(function(){
			$("#form").validate({
				rules: {
					"txtUrl" : "required"
				}
			})

			$("#btnSubmit").click(function(){

				if($("#form").valid()){
					$("#form").submit();
				}
			})
		});
	</script>
</body>
</html>