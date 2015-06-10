<?php
include '../../Piece.php';

$obj = new Piece('../img.jpg', 50, 10);

for ($i = 0; $i <= $obj->getMaxElementsX(); $i++) {
    for ($j = 0; $j <= $obj->getMaxElementsY(); $j++) {
        $obj->output($i, $j, 'output' . DIRECTORY_SEPARATOR . $i . '-' . $j . '.gif');
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Test puzzle</title>
</head>
<body>
<table>
    <tbody>
    <?php for ($i = 0; $i <= $obj->getMaxElementsY(); $i++) { ?>
        <tr>
            <?php for ($j = 0; $j <= $obj->getMaxElementsX(); $j++) { ?>
                <td>
                    <img src="output/<?php echo $j . '-' . $i . '.gif'; ?>"/>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>