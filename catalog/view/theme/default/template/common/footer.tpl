<footer>
  <div class="container">
    <ul class="list-inline">
      <li><img src="<?php echo $logo; ?>"
               style="width: 100px;"
               title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" />
      </li>
      <li><a href="<?php echo $informations[0]['href']; ?>"><?php echo $text_about ?></a></li>
      <li><a href="<?php echo $informations[1]['href']; ?>"><?php echo  $informations[1]['title']; ?></a></li>
      <li><a href="<?php echo $informations[4]['href']; ?>"><?php echo  $informations[4]['title']; ?></a></li>
      <li><a href="<?php echo $informations[5]['href']; ?>"><?php echo $informations[5]['title']; ?></a></li>
      <li><?php echo $text_addr; ?></li>
      <li>Социальные сети </li>
      <li>Логотипы принимаемых платежных систем </li>
    </ul>
</footer>

</body></html>