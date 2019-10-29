<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
<table class="table table-striped table-bordered">
  <tr>
    <td style="width: 50%;"><strong><?php echo $review['author']; ?></strong></td>
    <td class="text-right"><?php echo $review['date_added']; ?></td>
  </tr>
  <tr>
    <td colspan="2"><p><b>Достоинства</b>: <?php echo $review['good']; ?></p>
  </tr>
  <tr>
    <td colspan="2"><p><b>Недостатки</b>: <?php echo $review['bad']; ?></p>
  </tr>
  <tr>
    <td colspan="2"><p><?php echo $review['text']; ?></p>
  </tr>

</table>
<?php } ?>
<div class="text-right"><?php echo $pagination; ?></div>
<?php } else { ?>
<p><?php echo $text_no_reviews; ?></p>
<?php } ?>
