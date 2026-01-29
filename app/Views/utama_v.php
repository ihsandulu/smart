<?php echo $this->include("template/header_v"); ?>
<style>.product-btn {
  width: 160px;
  transition: transform .2s ease, box-shadow .2s ease;
}
.product-btn:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px rgba(0,0,0,.15);
}
</style>
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
										<img src="images/user_picture/<?= (session()->get("user_picture")) ? session()->get("user_picture") : "no_image.png"; ?>" alt="<?= session()->get("user_name"); ?>" />
									</div>
								</header>
								<h3>asdf</h3>
								<?php //dd(session()->get()); 
								?>
								<div class="desc">
									<?= ucfirst(session()->get("nama")); ?>
								</div>
								<?php $product = $this->db->table('product')->orderBy('product_name', 'ASC')->get();
								foreach ($product->getResult() as $p) { ?>
									
									<button class="btn p-0 border-0 bg-transparent">
										<div class="card shadow-sm product-btn">
											<img src="images/product_picture/<?= $p->product_picture; ?>" class="card-img-top" alt="<?= $p->product_name; ?>">
											<div class="card-body text-center">
												<h6 class="mb-0"><?= $p->product_name; ?></h6>
											</div>
										</div>
									</button>

								<?php } ?>
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