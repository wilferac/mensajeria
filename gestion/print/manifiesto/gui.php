<?php
   $idMani = $_REQUEST['id'];


   echo("<h2>Imprimiendo Manifiesto N. $idMani</h2>");
?>


<a href='print.php?option=0&id=<?= $idMani ?>' target='_blank'>Resumido</a>

<br><br>
<a href='print.php?option=1&id=<?= $idMani ?>' target='_blank'>Completo</a>
<div id="response">
</div>

<script type="text/javascript" language="javascript" src="../../../js/jquery-1.9.1.js"></script>
<script language="javascript">
    function llamar(num)
    {
//        $('#response').load('print.php?option=' + num + '&id=' + <?= $idMani ?>);
        var dataString = 'option=' + num + '&id=' + <?= $idMani ?>;

        $.ajax({
            type: "POST",
            url: "print.php",
            data: dataString,
            success: function(data) {
                window.open(data);

            }});

    }
</script>

