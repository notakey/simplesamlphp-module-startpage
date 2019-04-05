<?php
$this->includeAtTemplateBase('includes/header.php');
?>
<?php if($this->data['config']['pageTitle'] != ''){?><h2 style="break: both"><?php echo htmlspecialchars($this->data['config']['pageTitle']); ?></h2><?php } ?>
<?php if($this->data['config']['pageSubtitle'] != ''){?><p><?php echo htmlspecialchars($this->data['config']['pageSubtitle']); ?></p><?php } ?>

<div id="sp-listing">
	<?php foreach($this->data['spl'] as $sp){ ?>
	<div class="sp-block">
		<div class="sp-block-image">
			<a class="sp-block-image-link" href="<?php echo $sp['link']; ?>">
	        	<img src="<?php echo htmlspecialchars($sp['logo']); ?>">
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
