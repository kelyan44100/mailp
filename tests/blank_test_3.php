<?php
$row = 1;
if (($handle = fopen("./../castel/codes2018-09-03.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        $num = count($data);
        echo "<p> $num champs à la ligne $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br />\n";
        }
        $idPresent = explode('_', $data[10]);
        echo 'ID => '.$idPresent[1];
    }
    fclose($handle);
}

echo '<hr/>';

for ($i = 0; $i < 5; ++$i) {
    if ($i == 2) {
        continue 1;
    }
    print $i.'<br/>';
}
?>