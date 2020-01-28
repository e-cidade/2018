<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
//parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$erro = false;
if (isset($atualiza)) {
	
  $datatime = mktime(12, 0, 0, $data_mes, $data_dia, $data_ano);
  $verdata  = checkdate($data_mes, $data_dia, $data_ano);
  if ($verdata) {
  	
    db_putsession("DB_datausu", $datatime);
    db_putsession("DB_anousu", date("Y", $datatime));
    
    // atualiza arquivo com a data para posterior verificacao
    $sSqlDataUsuarios  = "delete from db_datausuarios where id_usuario = ".db_getsession("DB_id_usuario");
    $rsSqlDataUsuarios = db_query($sSqlDataUsuarios);

    $sSqlDataUsuarios  = "insert into db_datausuarios( id_usuario,                             "; 
    $sSqlDataUsuarios .= "                             data )                                  ";
    $sSqlDataUsuarios .= "                    values ( ".db_getsession("DB_id_usuario").",     ";
    $sSqlDataUsuarios .= "                             '{$data_ano}-{$data_mes}-{$data_dia}' ) ";
    $rsSqlDataUsuarios = db_query($sSqlDataUsuarios);
  } else {
    $erro = true;
  }
}

if (isset($adata)) {
	
  $datatime = mktime(12, 0, 0, date("m"), date("d"), date("Y"));
  db_putsession("DB_datausu", $datatime);
  db_putsession("DB_anousu", date("Y", $datatime));
  
  $sSqlDataUsuarios  = "delete from db_datausuarios where id_usuario = ".db_getsession("DB_id_usuario");
  $rsSqlDataUsuarios = db_query($sSqlDataUsuarios);
}

$lMostrarMenu = true;
if (!empty($lParametroExibeMenu) && $lParametroExibeMenu === "false") {
  $lMostrarMenu = false;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<form name="form1" method="post">
<br>
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
<td ></td>
</tr>
  <tr> 
    <td align="left" nowrap >
      <strong>Data do Sistema:</strong>
    </td>
    <td align="left" nowrap>
      <?
	      $sSqlDataUsuarios  = "select * from db_datausuarios where id_usuario = ".db_getsession("DB_id_usuario");
	      $rsSqlDataUsuarios = db_query($sSqlDataUsuarios);
	      if (pg_numrows($rsSqlDataUsuarios) > 0){ 
	      	
	      	$anousu = substr(pg_result($rsSqlDataUsuarios, 0, 'data'), 0, 4);
	      	$mesusu = substr(pg_result($rsSqlDataUsuarios, 0, 'data'), 5, 2);
	      	$diausu = substr(pg_result($rsSqlDataUsuarios, 0, 'data'), 8, 2);
	      } else {
	      	
	      	$anousu = date("Y",db_getsession("DB_datausu"));
	      	$mesusu = date("m",db_getsession("DB_datausu"));
	      	$diausu = date("d",db_getsession("DB_datausu"));
	      }
	      
	      db_inputdata('data', $diausu, $mesusu, $anousu, true, 'text', 1, "");   		          
      ?>

    </td>    
    <td align="left" >
    <input name="atualiza" type="submit" value="Atualizar">
    </td>
  </tr>
<tr height="20px">
<td ><br></td>
<td ></td>
<td ></td>
</tr>
  <tr> 
    <td  align="left" nowrap ><strong>Data do Servidor:</strong></td>
    <td align="left" nowrap>
      <?
	      $datatime = mktime(12, 0, 0, date("m"), date("d"), date("Y"));
	
	      $anousu   = date("Y", $datatime);
	      $mesusu   = date("m", $datatime);
	      $diausu   = date("d", $datatime);
	      
	      db_inputdata('dataservidor', $diausu, $mesusu, $anousu, true, 'text', 3, "");   		          
      ?>
    </td>    
    <td align="left" nowrap>
       <input name="adata" type="submit" value="Atualizar">
    </td>
  </table>
</form>
</center>
<? 
if ($lMostrarMenu) {
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
}
?>
</body>
</html>
<?
if (isset($atualiza) || isset($adata)) {

  $sParentAdicional = "";
  if (!$lMostrarMenu) {
    $sParentAdicional = "parent.";
  }
	
  echo "<script>";
  echo "parent.{$sParentAdicional}bstatus.document.getElementById('dtatual').innerHTML = '".date("d/m/Y",db_getsession("DB_datausu"))."';";   
  echo "parent.{$sParentAdicional}bstatus.document.getElementById('dtanousu').innerHTML = '".db_getsession("DB_anousu")."' ;";   
  echo "</script>";
}

if ($erro == true) {
  echo "<script>alert('Data Inválida. Verifique.')</script>";
}
?>