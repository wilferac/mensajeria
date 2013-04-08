<?php
   $idMani = $_REQUEST['id'];


   echo("<h2>Imprimiendo Manifiesto N. $idMani</h2>");
?>


<input type="button" value="Resumido" onclick='llamar(0)'></input>
<br><br>
<input type="button" value="Detallado" onclick='llamar(1)'></input>
<div id="response">
</div>

<script type="text/javascript" language="javascript" src="../../../js/jquery-1.9.1.js"></script>
<script language="javascript">
    function llamar(num)
    {
        $('#response').load('print.php?option=' + num + '&id=' + <?= $idMani ?>);
    }
</script>

