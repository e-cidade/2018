<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_matrequiitem_classe.php"));
include(modification("classes/db_atendrequi_classe.php"));
include(modification("classes/db_atendrequiitem_classe.php"));
include(modification("classes/db_matestoque_classe.php"));
include(modification("classes/db_matestoquedevitem_classe.php"));
include(modification("dbforms/db_funcoes.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clmatrequiitem = new cl_matrequiitem;
$clatendrequi = new cl_atendrequi;
$clatendrequiitem = new cl_atendrequiitem;
$clmatestoque = new cl_matestoque;
$clmatestoquedevitem = new cl_matestoquedevitem;

$clmatrequiitem->rotulo->label();


$clrotulo = new rotulocampo;
$clrotulo->label("m60_descr");



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script>


</script>

<style>

.bordas{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}

.bordas_corp{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
       }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr>
    <td  align="left" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <center>
 <table border='0' cellspacing="0" style="border: 2px inset white;background-color: white">
 <?

 if (isset($m42_codigo) && $m42_codigo!= "") {

  $result=$clatendrequiitem->sql_record($clatendrequiitem->sql_query_inimei(null,"*","","m43_codatendrequi=$m42_codigo"));
  $numrows = $clatendrequiitem->numrows;
	if($numrows>0){
	echo "
	    <tr class='table_header'>
	      <td class='table_header' align='center'><b><small>$RLm41_codmatmater</small></b></td>
	      <td class='table_header' align='center'><b><small>$RLm60_descr</small></b></td>
	      <td class='table_header' align='center'><b><small>$RLm41_obs</small></b></td>
          <td class='table_header' align='center'><b><small>Quant. Atendida</small></b></td>
	      <td class='table_header' align='center'><b><small>Quant. Devolvida</small></b></td>
	      <td class='table_header' align='center'><b><small>Saldo disponível</small></b></td>
	      <td class='table_header' align='center'><b><small>Quantidade</small></b></td>";

	 echo " </tr>";
	      }else echo"<b>Nenhum registro encontrado...</b>";
         for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i);
	    echo "<tr>
   	            <td	 class='linhagrid' align='center'><small>$m41_codmatmater </small></td>
   	            <td	 class='linhagrid' align='center'><small>$m60_descr </small></td>
		    <td	 class='linhagrid' nowrap align='left' title='$m41_obs'><small>".substr($m41_obs,0,20)." - {$m82_codigo}&nbsp;</small></td>";
	      $quant_devolvida=0;

        $sSql = "select m46_quantdev
                   from matestoquedevitem
                        inner join matestoquedevitemmei on m47_codmatestoquedevitem = m46_codigo
                  where m46_codatendrequiitem = {$m43_codigo}
                    and m47_codmatestoqueitem = {$m82_matestoqueitem}";

//        echo $sSql."<br>";
	      $result_devol=$clmatestoquedevitem->sql_record($sSql);
	      $numrows3=$clmatestoquedevitem->numrows;
	      if ($numrows3!=0){
		      for ($w=0;$w<$numrows3;$w++) {
		        db_fieldsmemory($result_devol,$w);
		        $quant_devolvida += $m46_quantdev;
		      }
	      }

	      $quant          = $m82_quant;
	      $quant_sol      = $quant-$quant_devolvida;
//        $m43_quantatend = $quant;

	      if ($quant_devolvida == 0){
		         $quant_sol *= -1;
	      }

        if ($quant_sol < 0){
             $quant_sol *= -1;
        }

	      $quantidade="quant_$m41_codmatmater"."_"."$m41_codigo"."_".$i."_".$m82_codigo;
	      $$quantidade="";
	      $op=1;
	      if ($m43_quantatend==0||$quant_sol==0){
	        $op=3;
	      }

	      echo "<td class='linhagrid' align='center'><small> $m82_quant       </small></td>";
	      echo "<td class='linhagrid' align='center'><small> $quant_devolvida </small></td>";
        echo "<td class='linhagrid' align='center'><small> $quant_sol       </small></td>";
        echo "<td class='linhagrid' align='center'><small> ";
  	      db_input("quant_$m43_codigo"."_"."$m41_codmatmater"."_"."$m41_codigo"."_".$i."_".$m82_codigo."_".$m82_matestoqueitem,6,0,
  	               true,'text',$op,"onchange='js_verifica($quant_sol,this.value,this.name,$quant_sol)' onkeypress='return js_mask(event,\"0-9|.\")'");
        echo "</small></td>";
	      echo"  </tr>";
	    }
	}


?>
 </table>
    </form>
    </center>
    </td>
  </tr>
</table>
<script>
function js_verifica(max,quan,nome,sol){
   if (max<quan || quan <= 0) {
     alert("Informe uma quantidade valida!!\nQuantidade não disponível");
     eval("document.form1."+nome+".value='';");
     eval("document.form1."+nome+".focus();");
     return false;
   }

   if (quan > sol){
        alert("Saldo indisponível!!\nQuantidade não disponível");
        return false;
   }
}
</script>
</body>
</html>