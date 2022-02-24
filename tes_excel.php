<html>
<body>

<?php

  include "lib/excel.php";
  echo '<h1>Tiobe Index August 2019</h1><pre>';

  if ( $xlsx = SimpleXLSX::parse('excel/contoh.xlsx') ) {
    echo '<table><tbody>';
    $i = 0;

    foreach ($xlsx->rows() as $elt) {
      if ($i == 0) {
        echo "<tr><th>" . $elt[0] . "</th><th>" . $elt[1] . "</th></tr>";
      } else {
        echo "<tr><td>" . $elt[0] . "</td><td>" . $elt[1] . "</td></tr>";
      }      

      $i++;
    }

    echo "</tbody></table>";

  } else {
    echo SimpleXLSX::parseError();
  }

?>

</body>
</html>	