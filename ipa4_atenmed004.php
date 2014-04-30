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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
session_register("COD_atendimento");
db_putsession("COD_atendimento","$codigo");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_paginar(obj) {
  if(obj.id == "anterior") {
	document.getElementById("proximo").disabled = false;
    atendimento.form1.antprox.value = new Number(atendimento.form1.antprox.value) + 1;
	if(atendimento.form1.antprox.value == (atendimento.form1.totreg.value - 1))
	  document.getElementById("anterior").disabled = true;
  } else if(obj.id == "proximo") {  
    atendimento.form1.antprox.value = new Number(atendimento.form1.antprox.value) - 1;	
	if(atendimento.form1.antprox.value == "0")
	  document.getElementById("proximo").disabled = true;
  }
  atendimento.form1.submit();
}
function js_abrir(obj,R) {
  jan = window.open('ipa4_atenmed006.php?nome=' + obj.name + '&readonly=' + R,'','width=600,height=500');
  jan.moveTo(100,5);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}

input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <?
$sql = "select dependente.w03_codigo,w01_regist,dependente.w03_nome as conjuge,fc_idade(dependente.w03_dtnasc,'".date("Y-m-d",db_getsession("DB_datausu"))."') as idadeconj,ag30_codigo,ag30_codage,ag30_data,ag30_hora,j01_nome,depen.w03_nome,ag30_regist,ag30_depend,fc_idade(depen.w03_dtnasc,'".date("Y-m-d",db_getsession("DB_datausu"))."') as idadedepen,fc_idade(w01_dtnasc,'".date("Y-m-d",db_getsession("DB_datausu"))."') as idade
                     from agenate
					 inner join cadastro
					 on w01_regist = ag30_regist
					 inner join cgipa
					 on j01_numero = w01_numcgi
					 left outer join depen
					 on w03_codigo = ag30_depend
		                         left outer join depen dependente
					 on (dependente.w03_resp1 = cadastro.w01_numcgi 
					 or dependente.w03_resp2 = cadastro.w01_numcgi)
					 and (dependente.w03_gparen = '3' 
					 or dependente.w03_gparen = '4') 
                     where ag30_codage = $codage
		                         and ag30_codigo = $codigo 
					 and ag30_data = '$dataini'
					 ";
	$result = pg_exec($sql);
	db_fieldsmemory($result,0);
	db_putsession("w03_codigo","".@$w03_codigo."");
        db_putsession("w01_regist","".@$w01_regist."");
	?><Br>
	<table border="0" cellpadding="0" cellspacing="5">
        <tr> 
          <td nowrap>&nbsp;<strong>Funcionário:</strong></td>
          <td width="200" nowrap bgcolor="#FFFFFF">&nbsp; 
            <?=$j01_nome?>
            &nbsp;</td>
          <td nowrap><strong>Idade:</strong></td>
          <td nowrap bgcolor="#FFFFFF">&nbsp; 
            <?=$idade?>
            &nbsp;</td>
          <td nowrap><strong>&nbsp;Paciente:</strong></td>
          <td width="200" nowrap bgcolor="#FFFFFF">&nbsp; 
            <?=@$w03_nome?>
	    <?=($w03_nome != ""?db_putsession("w03_depen","".@$w03_codigo.""):"")?>
            &nbsp;</td>
          <td nowrap><strong>Idade:</strong></td>
          <td nowrap bgcolor="#FFFFFF">&nbsp; 
            <?=@$idadedepen?>
            &nbsp;</td>
        </tr>
        <tr> 
          <td nowrap><strong>&nbsp;Conjuge:</strong></td>
          <td width="200" nowrap bgcolor="#FFFFFF">&nbsp; 
            <?=@$conjuge?>
            &nbsp;</td>
          <td nowrap><strong>Idade:</strong></td>
          <td nowrap bgcolor="#FFFFFF">&nbsp; 
            <?=@$idadeconj?>
            &nbsp;</td>
          <td nowrap><strong>&nbsp;Tutor:</strong></td>
          <td width="200" nowrap bgcolor="#FFFFFF">&nbsp; 
            <?=@$tutordepen?>
            &nbsp;</td>
          <td nowrap>&nbsp;</td>
          <Td nowrap>&nbsp;</Td>
        </tr>
        <?
	  /*
	  echo trim($nomeconj)!=""?"<td><strong>Conjuge:&nbsp;&nbsp;</strong></td><td>$nomeconj</td><td>Idade:</td><td>$idadeconj</td>":"";
	  echo trim($nomedepen)!=""?"<td><strong>Dependente:</strong></td><td>$nomedepen</td><td>Idade:</td><td>$idadedepen</td>":"";
	  echo trim($tutordepen)!=""?"<td><strong>Tutor:</strong></td><td>$tutordepen</td>":"";
	  */
	  ?>
      </table>
      <br>
 <center>	
 <input type="hidden" name="codigo" value="<?=@$codigo?>" id="codigo">
<iframe src="ipa4_atenmed0041.php?codigo=<?=$codigo?>&funcionario=<?=$regist?>&dependente=<?=$depend?>" height="350" width="750" name="atendimento" scrolling="yes" frameborder="0"></iframe>
  </center>
	</td>
  </tr>
</table>
<?
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));				   
?>
</body>
</html>