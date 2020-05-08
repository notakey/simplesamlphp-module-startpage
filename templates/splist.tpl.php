<?php
$this->includeAtTemplateBase('includes/header.php');
?>
<h2 style="break: both">
    <?php
    if ($this->data['config']['pageTitle'] != '') {
        echo htmlspecialchars($this->data['config']['pageTitle']);
    } else {
        echo $this->t('{startpage:startpage:splist_header}');
    } ?>
</h2>
<?php if ($this->data['config']['pageSubtitle'] != '') { ?><p><?php echo htmlspecialchars($this->data['config']['pageSubtitle']); ?></p><?php } ?>
<?php if ($this->data['config']['showLogout']) { ?>
    <div id="sp-logout">
        <a href="<?php echo $this->data['logout_url']; ?>"><?php echo $this->t('{startpage:startpage:logout}') . ' ' . $this->data['username']; ?></a>
    </div>
<?php
}
?>
<div id="sp-listing">
    <?php foreach ($this->data['spl'] as $sp) { ?>
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
