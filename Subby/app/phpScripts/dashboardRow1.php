<?php require('functions.php') ?>
<div class="overviewItem">
	Total subscriptions: <br> <?php countAllUserSubscriptions($conn); ?>
</div>

<div class="overviewItem">
	Total to pay for <?php echo date('F') . "<br>"; $coreFunctions->getTotalToPayThisMonth(); ?>

</div>

<div class="overviewItem">
Total left to pay for <?php echo date('F') . "<br>"; $coreFunctions->getTotalLeftToPayThisMonth(); ?>
</div>

<div class="overviewItem">
	Total to pay this year <br> <?php $coreFunctions->getTotalToPayThisYear(); ?>
</div>

<div class="overviewItem">
	Total left to pay for <?php echo date('Y') . "<br>"; $coreFunctions->getTotalLeftToPayThisYear(); ?>
</div>
