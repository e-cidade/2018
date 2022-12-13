<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_aidof_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$claidof = new cl_aidof;

$claidof->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("q09_descr");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script>
</script>

<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
}
.bordas_corp2{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: red;
}
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr>
    <td  align="left" valign="top" bgcolor="#CCCCCC">
      <form name='form1'>
      <center>
<table border='1' cellspacing="0" cellpadding="0">
  <?php

    if ( !empty($inscr) ) {
      $result = $claidof->sql_record($claidof->sql_query(null,"*","y08_codigo","y08_inscr=$inscr"));
    }

    $numrows= $claidof->numrows;

   if($numrows>0){
     echo"<b>AIDOF's Liberados(por inscrição)</b>";
     echo "<tr class='bordas'>
	      <td class='bordas' align='center'><b><small>$RLy08_codigo  </small></b></td>
		  <td class='bordas' align='center'><b><small>$RLy08_nota  </small></b></td>
		  <td class='bordas' align='center'><b><small>Descr. nota  </small></b></td>
	      <td class='bordas' align='center'><b><small>$RLy08_dtlanc  </small></b></td>
	      <td class='bordas' align='center'><b><small>$RLy08_notain  </small></b></td>
	      <td class='bordas' align='center'><b><small>$RLy08_quantsol</small></b></td>
	      <td class='bordas' align='center'><b><small>$RLy08_quantlib</small></b></td>
	      <td class='bordas' align='center'><b><small>$RLy08_notafi  </small></b></td>
	      <td class='bordas' align='center'><b><small>$RLy08_obs </small></b></td>
	      <td class='bordas' align='center'><b><small>$RLy08_numcgm  </small></b></td>
	       <td class='bordas' align='center'><b><small>$RLy08_cancel  </small></b></td>";
     echo "</tr>";
   }else if ($numrows == 0){
     echo "<br><br><br><b>Nenhum registro encontrado...</b>";
   }
   for($i=0; $i<$numrows; $i++){
     db_fieldsmemory($result,$i,true);
     $bordas_corp="bordas_corp";
     $cancel="Não";
     if ($y08_cancel=="t"){
     	$cancel="Sim";
     	$bordas_corp="bordas_corp2";
     }
     echo "<tr>

             <td class='$bordas_corp' align='center'><small>$y08_codigo  </small></td>
<td class='$bordas_corp' align='center'><small>$y08_nota </small></td>
<td class='$bordas_corp' align='center'><small>$q09_descr </small></td>
	     <td class='$bordas_corp' align='center'><small>$y08_dtlanc  </small></td>
	     <td class='$bordas_corp' align='center'><small>$y08_notain  </small></td>
	     <td class='$bordas_corp' align='center'><small>$y08_quantsol</small></td>
	     <td class='$bordas_corp' align='center'><small>$y08_quantlib</small></td>
	     <td class='$bordas_corp' align='center'><small>$y08_notafi  </small></td>
	     <td class='$bordas_corp' align='center'><small>$y08_obs  </small></td>
	     <td class='$bordas_corp' align='center'><small>$y08_numcgm  </small></td>
		 <td class='$bordas_corp' align='center'><small>$cancel  </small></td>
	   </tr>
	     ";
   }
?>
 </table>
     </form>
     </center>
    </td>
  </tr>
</table>
</body>
</html>