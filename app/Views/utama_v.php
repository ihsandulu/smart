<?php echo $this->include("template/header_v"); ?>

<div class='container'>
	<div class='row'>
		<div class='col'>
			<div class="row">
				<!-- Column -->
				<div class="col-lg-12">
					<div class="card">
						<div class="card-body">
							<div class="card-two">
								<header>
									<div class="avatar">
										<img src="images/user_picture/<?=(session()->get("user_picture"))?session()->get("user_picture"):"no_image.png";?>" alt="<?= session()->get("user_name"); ?>" />
									</div>
								</header>
								<h3>asdf</h3>
								<div class="desc">
									<?= ucfirst(session()->get("nama")); ?>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>				
		</div>
	</div>
</div>

<?php echo  $this->include("template/footer_v"); ?>

<?php //echo $this->endSection(); 
?>