<?php
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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_liclicitem_classe.php");
require_once("classes/db_liclicita_classe.php");
require_once("classes/db_liclicitaitemlog_classe.php");
require_once("model/licitacao.model.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clliclicitem = new cl_liclicitem;
$clrotulo = new rotulocampo;
$clrotulo->label("l21_ordem");
$clrotulo->label("l21_codigo");
$clrotulo->label("pc11_quant");
$clrotulo->label("pc11_vlrun");
$clrotulo->label("m61_descr");
$clrotulo->label("pc01_codmater");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("pc11_resum");
$clrotulo->label("pc23_obs");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<link href="../estilos/grid.style.css" rel="stylesheet" type="text/css">

</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1'>
    <center>
 <table width='100%' cellpading="0" cellspacing="0" style="border:2px inset white;">   
<?
  
  if (isset($l20_codigo) && $l20_codigo!= "") {

    $sSqlLog = "select l14_liclicita  from liclicitaitemlog where l14_liclicita = {$l20_codigo}";
    $rslog   = db_query($sSqlLog);
    $lLog    = false;
    if (pg_num_rows($rslog) > 0)  {
      
      $lLog       = true; 
      $oLicitacao = new licitacao($l20_codigo);
      $oInfoLog   = $oLicitacao->getInfoLog();
      $numrows    = count($oInfoLog->item);
      
    } else {
      
      $sCampos  = " distinct l21_ordem, l21_codigo, pc81_codprocitem, pc11_seq, pc11_codigo, pc11_quant, pc11_vlrun, ";
      $sCampos .= " m61_descr, pc01_codmater, pc01_descrmater, pc11_resum, pc23_obs";
      $sOrdem   = "l21_ordem";
      $sWhere   = "l21_codliclicita = {$l20_codigo} ";
     // die($clliclicitem->sql_query_inf(null, $sCampos, $sOrdem, $sWhere));
      $sSqlItemLicitacao = $clliclicitem->sql_query_inf(null, $sCampos, $sOrdem, $sWhere);
      $result=$clliclicitem->sql_record($sSqlItemLicitacao);    
      $numrows = $clliclicitem->numrows;
      
    }
	  if ($numrows > 0) {
	    
	    echo "
	    <thead>
	    <tr>
	      <th style ='width: 60px;'  class='table_header' align='center'>$RLl21_ordem		    </th>	      
	      <th style ='width: 60px;'  class='table_header' align='center'>$RLl21_codigo	  	</th>	      
	      <th style ='width: 70px;'  class='table_header' align='center'>$RLpc11_quant	  	</th>
	      <th style ='width: 80px;'  class='table_header' align='center'>$RLpc11_vlrun	  	</th>
		    <th style ='width: 50px;'  class='table_header' align='center'>$RLm61_descr	    	</th>
        <th style ='width: 70px;'  class='table_header' align='center'>$RLpc01_codmater		</th>
		    <th style ='width: 150px;' class='table_header' align='center'>$RLpc01_descrmater	</th>
	  	  <th style ='width: 150px;' class='table_header' align='center'>$RLpc11_resum	  	</th>
	  	  <th style ='width: 100px;' class='table_header' align='center'>$RLpc23_obs        </th>
		  
        ";
    } else {
      echo"<b>Nenhum registro encontrado...</b>";
    }
	  echo " </tr> ";
	  echo " </thead> ";
	  echo " <tbody style='background-color:#FFFFFF'> ";
    for ($i = 0; $i < $numrows; $i++) {
       
      if (!$lLog) {
	      db_fieldsmemory($result,$i);
      } else {
        
        $l21_ordem       = utf8_decode($oInfoLog->item[$i]->l21_ordem);
        $l21_codigo      = utf8_decode($oInfoLog->item[$i]->l21_codigo);
  	  	$pc01_codmater   = utf8_decode($oInfoLog->item[$i]->pc01_codmater);
  	  	$pc01_descrmater = utf8_decode($oInfoLog->item[$i]->pc01_descrmater);
  	  	$pc11_quant      = utf8_decode($oInfoLog->item[$i]->pc11_quant);
  	  	$pc11_vlrun      = utf8_decode($oInfoLog->item[$i]->pc11_vlrun);
  	  	$pc01_servico    = utf8_decode($oInfoLog->item[$i]->pc01_servico);
  	  	$m61_descr       = utf8_decode($oInfoLog->item[$i]->m61_descr);
  	  	$m61_usaquant    = utf8_decode($oInfoLog->item[$i]->m61_usaquant);
  	  	$pc17_quant      = utf8_decode($oInfoLog->item[$i]->pc17_quant);
  	  	$pc11_resum      = utf8_decode($oInfoLog->item[$i]->pc11_resum);
  	  	$pc23_obs        = utf8_decode($oInfoLog->item[$i]->pc23_obs);
      }
	    echo "
	     			<tr >  	            
      				<td	class='linhagrid' align='center'>$l21_codigo </td>
      				<td	class='linhagrid' align='center'>$l21_ordem  </td>
              <td	class='linhagrid' align='center'>$pc11_quant </td>
      				<td	class='linhagrid' align='right'> ".db_formatar($pc11_vlrun,'f')."</td>
      				<td	class='linhagrid' align='center'>$m61_descr</td>
              <td	class='linhagrid' align='center'>$pc01_codmater</td>          
              <td	class='linhagrid' nowrap align='left' title='$pc01_descrmater'>".substr($pc01_descrmater,0,40)."&nbsp;</td>
              <td	class='linhagrid' nowrap align='left' title='$pc11_resum'>     ".substr($pc11_resum,0,40)."&nbsp;     </td>
	            <td class='linhagrid' nowrap align='left' title='$pc23_obs'>       ".substr($pc23_obs,0,25)."...&nbsp;    </td>
            </tr> 
	    		";
	  }
	  echo "</tbody>";
 }
?>     
 </table>
    </form> 
    </center>
    </td>
  </tr>
</table>
<script>
</script>
</body>
</html>