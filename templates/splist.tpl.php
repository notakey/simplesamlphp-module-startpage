<?php


$this->includeAtTemplateBase('includes/header.php');
?>
<!--  <h2><?php echo $this->t('{startpage:startpage:block_header}'); ?></h2> -->
<div id="sp-listing">
	<?php foreach($this->data['spl'] as $sp){ ?>
	<div class="sp-block">
		<div class="sp-block-image">
			<a class="sp-block-image-link" href="<?php echo $sp['link']; ?>">
	        	<img src="/userlogos/<?php echo $sp['logo']; ?>">
	        </a>
	    </div>
		<div class="sp-block-title">
			<?php echo $sp['name']; ?>
		</div>
	</div>
	<?php } ?>

</div>
<div style="clear:both;"></div>
<?php



$this->includeAtTemplateBase('includes/footer.php');
