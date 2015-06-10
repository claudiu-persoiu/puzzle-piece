<!DOCTYPE html>
<html>
<head></head>
<body>
<table>
    <tbody>
        <?php for($i = 0; $i < 5; $i++) { ?>
            <tr>
               <?php for($j = 0; $j < 5; $j++) { ?>
                   <td>
                       <img src="img.php?x=<?php echo $j; ?>&y=<?php echo $i; ?>&piceSize=90&margin=17&img=1" />
                   </td>
               <?php } ?>
            </tr>
        <?php } ?>
    </tbody>

</table>

</body>
</html>