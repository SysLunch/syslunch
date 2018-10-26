<?php
  $a = file_get_contents("https://code.responsivevoice.org/getvoice.php?t=".urlencode($_GET["texto"])."&tl=pt-BR&sv=g2&vn=&pitch=0.5&rate=1&vol=2");
  header("Content-Type: audio/mpeg");
  echo $a;
