<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
include("../libs/db_sessoes.php");
include("../libs/db_usuariosonline.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table scrolling="auto" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
      <?
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//$query ,$numlinhas,$arquivo="",$filtro="%",$aonde="_self",$mensagem="Clique Aqui",$NomeForm="NoMe" 

echo $query;

  global $BrowSe;
  //cor do cabecalho
  global $db_corcabec;
  $db_corcabec = $db_corcabec==""?"#CDCDFF":$db_corcabec;
  //cor de fundo de cada registro
  global $cor1;
  global $cor2;
  $cor1 = $cor1==""?"#97B5E6":$cor1;
  $cor2 = $cor2==""?"#E796A4":$cor2;
  global $HTTP_POST_VARS;
  $tot_registros = "tot_registros".$NomeForm;
  $offset = "offset".$NomeForm;
  //recebe os valores do campo hidden
  $$tot_registros = $HTTP_POST_VARS["totreg".$NomeForm];
  $$offset = $HTTP_POST_VARS["offset".$NomeForm];  
  // se for a primeira vez que é rodado, pega o total de registros e guarda no campo hidden
  if(empty($$tot_registros)) {
    $Dd1 = "disabled";
    //$tot = pg_exec("select count(*) from ($query) as temp");  
	$$tot_registros = 100;
	//pg_result($tot,0,0);
  }
  // testa qual botao foi pressionado
  if(isset($HTTP_POST_VARS["pri".$NomeForm])) {
    $$offset = 0;
	$Dd1 = "disabled";
  } else if(isset($HTTP_POST_VARS["ant".$NomeForm])) {
    if($$offset <= $numlinhas) {
	  $$offset = 0;
	  $Dd1 = "disabled";
	} else
      $$offset = $$offset - $numlinhas;
  } else if(isset($HTTP_POST_VARS["prox".$NomeForm])) {
    if($numlinhas >= ($$tot_registros - $$offset - $numlinhas)) {
	  $$offset = $$tot_registros - $numlinhas;
	  $Dd2 = "disabled";
	} else 
      $$offset = $$offset + $numlinhas;
  } else if(isset($HTTP_POST_VARS["ult".$NomeForm])) {
    $$offset = $$tot_registros - $numlinhas;  
	$Dd2 = "disabled";
  } else {
    $$offset = $HTTP_POST_VARS["offset".$NomeForm]==""?0:$HTTP_POST_VARS["offset".$NomeForm];
  }
  // executa a query e cria a tabela
  $query .= " limit $numlinhas offset ".$$offset;
  $result = pg_exec($query);  
  $NumRows = pg_numrows($result);
  $NumFields = pg_numfields($result);
  if($NumRows < $numlinhas)
    $Dd1 = $Dd2 = "disabled";
  echo "<table id=\"TabDbLov\" border=\"1\" cellspacing=\"1\" cellpadding=\"0\">\n";
  /**** botoes de navegacao ********/
  echo "<tr><td colspan=\"$NumFields\" nowrap>
  <form name=\"navega_lov".$NomeForm."\" method=\"post\">
    <input type=\"submit\" name=\"pri".$NomeForm."\" value=\"<<\" $Dd1>
    <input type=\"submit\" name=\"ant".$NomeForm."\" value=\"<\" $Dd1>
    <input type=\"submit\" name=\"prox".$NomeForm."\" value=\">\" $Dd2>
    <input type=\"submit\" name=\"ult".$NomeForm."\" value=\">>\" $Dd2>
	<input type=\"button\" name=\"fecha\" value=\"Fecha\" onclick=\"MM_showHideLayersValor('Lista<?=$chave1.$chave2?>','','hide')\" >
	<input type=\"hidden\" name=\"offset".$NomeForm."\" value=\"".$$offset."\">
	<input type=\"hidden\" name=\"totreg".$NomeForm."\" value=\"".$$tot_registros."\">
	<input type=\"hidden\" name=\"filtro\" value=\"$filtro\">
  </form>
  </td></tr>";
  /*********************************/
 /***** Escreve o cabecalho *******/
  if($NumRows > 0) {
    echo "<tr>\n";
    for($i = 0;$i < $NumFields;$i++) {
      if(strlen(strstr(pg_fieldname($result,$i),"db")) == 0)
        echo "<td nowrap bgcolor=\"$db_corcabec\"  style=\"font-size:13px\" align=\"center\"><b><u>".ucfirst(pg_fieldname($result,$i))."</u></b></td>\n";
    }
    echo "</tr>\n";
  }
  /********************************/
  /****** escreve o corpo *******/  
  for($i = 0;$i < $NumRows;$i++) {
  	echo "<tr>\n";
    $cor = $cor==$cor1?$cor2:$cor1;
    for($j = 0;$j < $NumFields;$j++) {
	  if(strlen(strstr(pg_fieldname($result,$j),"db")) == 0)
        echo "<td id=\"I".$i.$j."\" style=\"text-decoration:none;color:#000000;font-size:13px\" bgcolor=\"$cor\" nowrap>
	       ".($arquivo!=""?"<a title=\"$mensagem\" style=\"text-decoration:none;color:#000000;font-size:13px\" href=\"\" ".($arquivo=="()"?"OnClick=\"js_retornaValor('I".$i.$j."');return false\">":"onclick=\"JanBrowse = window.open('".$arquivo."?".base64_encode("retorno=".($BrowSe==1?$i:trim(pg_result($result,$i,0))))."','$aonde','width=800,height=600');return false\">")
		   .(trim(pg_result($result,$i,$j))==""?"&nbsp;":trim(pg_result($result,$i,$j)))."</a>":(trim(pg_result($result,$i,$j))==""?"&nbsp;":trim(pg_result($result,$i,$j))))."</td>\n";  
    }
	echo "</tr>\n";
  }
  /******************************/
  echo "</table>";
 
?>
    </td>
  </tr>
</table>
</body>
</html>