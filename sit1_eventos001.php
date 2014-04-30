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

if(isset($retorno)) {
  $result = pg_exec("select s_codigo as codigo,                            
                            s_horainicio as horainicio,
                            s_horafim as horafim,
                            s_secretaria as secretaria,
                            s_descricao as descricao,
                            s_localid as localid,
                            s_telefone as telefone,
                            s_email as email,
                            s_obs as obs,
                            s_intext as intext,
                       to_char(s_datainicio,'DD') as datainicio_dia,
					   to_char(s_datainicio,'MM') as datainicio_mes,
					   to_char(s_datainicio,'YYYY') as datainicio_ano,
					   to_char(s_datafim,'DD') as datafim_dia,
					   to_char(s_datafim,'MM') as datafim_mes,
					   to_char(s_datafim,'YYYY') as datafim_ano
					 from db_calendario where s_codigo = $retorno");
  db_fieldsmemory($result,0);
}
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = pg_exec("select max(s_codigo) + 1 from db_calendario");
  $codigo = pg_result($result,0,0);
  $codigo = $codigo==""?1:$codigo;
  $SQL = "insert into db_calendario values(
    $codigo,
    ".($datainicio_ano==""?"null":"'$datainicio_ano-$datainicio_mes-$datainicio_dia'").",
    '$horainicio',
    ".($datafim_ano==""?"null":"'$datafim_ano-$datafim_mes-$datafim_dia'").",
    '$horafim',
    '$secretaria',
    '$descricao',
    '$localid',
    '$telefone',
    '$email',
	'$obs',
	'$intext')";
  $result = pg_exec($SQL);
  if(pg_cmdtuples($result) > 0) {
    echo "<script>location.href = 'sit1_eventos001.php'</script>\n";
	exit;
  }
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = pg_exec("UPDATE db_calendario SET
                       s_datainicio = ".($datainicio_ano==""?"null":"'$datainicio_ano-$datainicio_mes-$datainicio_dia'").",
                       s_horainicio = '$horainicio',
                       s_datafim = ".($datafim_ano==""?"null":"'$datafim_ano-$datafim_mes-$datafim_dia'").",
                       s_horafim = '$horafim',
					   s_secretaria = '$secretaria',
                       s_descricao = '$descricao',
                       s_localid = '$localid',
                       s_telefone = '$telefone',
                       s_email = '$email',
                       s_obs = '$obs',
					   s_intext = '$intext'
					 WHERE s_codigo = $codigo");
  if(pg_cmdtuples($result) > 0) {
    db_redireciona();
	exit;
  }					
} else if(isset($HTTP_POST_VARS["excluir"])) {
  $result = pg_exec("delete from db_calendario where s_codigo = ".$HTTP_POST_VARS["codigo"]);
  if(pg_cmdtuples($result) > 0) {
    db_redireciona();
	exit;
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_iniciar() {
  if(document.form1)
    document.form1.datainicio_dia.focus();
}
function js_excluir() {
  return confirm("Voce quer realmente excluir este registro?");
}
</script>
<style type="text/css">
<!--
td {
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

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC" onLoad="js_iniciar()">
<? if(!isset($HTTP_POST_VARS["consultar"]) && !isset($HTTP_POST_VARS["priNoMe"]) && !isset($HTTP_POST_VARS["antNoMe"]) && !isset($HTTP_POST_VARS["proxNoMe"]) && !isset($HTTP_POST_VARS["ultNoMe"])) { ?>
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
  <center>
  <form name="form1" method="post">
    <table width="59%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="22%" nowrap><strong>Data Inicial:</strong></td>
        <td width="78%" nowrap>
		<input type="hidden" value="<?=@$codigo?>" name="codigo">
		<?
	    include("dbforms/db_funcoes.php");
		db_data("datainicio",@$datainicio_dia,@$datainicio_mes,@$datainicio_ano);
		?>
		<!--input name="datainicio_dia" type="text" value="<?=@$datainicio_dia?>" size="2" maxlength="2" onkeyUp="js_digitadata(this.name)">
          / 
          <input name="datainicio_mes" type="text" value="<?=@$datainicio_mes?>" size="2" maxlength="2" onkeyUp="js_digitadata(this.name)">
          / 
          <input name="datainicio_ano" type="text" value="<?=@$datainicio_ano?>" size="4" maxlength="4"-->
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <input name="intext" type="radio" value="t" <?=!isset($intext)?"checked":($intext=='t'?"checked":"")?>>
          <strong>Int</strong>&nbsp;&nbsp; 
          <input type="radio" name="intext" value="f" <?=@$intext=='f'?"checked":""?>>
          <strong>Ext</strong></td>
      </tr>
      <tr> 
        <td nowrap><strong>Hora Inicial:</strong></td>
        <td nowrap><input name="horainicio" type="text" value="<?=@$horainicio?>" size="5" maxlength="5">
          <font size="-2">formato: HH:MM&nbsp;</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>Secretaria:</strong></td>
      </tr>
      <tr> 
        <td nowrap><strong>Data Final:</strong></td>
        <td nowrap>
		  <?
			db_data("datafim",@$datafim_dia,@$datafim_mes,@$datafim_ano);
		  ?>
		  <!--input name="datafim_dia" type="text" value="<?=@$datafim_dia?>" size="2" maxlength="2" onkeyUp="js_digitadata(this.name)">
          / 
          <input name="datafim_mes" type="text" value="<?=@$datafim_mes?>" size="2" maxlength="2" onkeyUp="js_digitadata(this.name)">
          / 
          <input name="datafim_ano" type="text" value="<?=@$datafim_ano?>" size="4" maxlength="4"-->
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <select name="secretaria">
		  <option value="0">Evento</option>
		  <?
		  $result = pg_exec($conn,"select cs_odigo,s_descricao from db_secretaria order by s_codigo");
		  $numrows = pg_numrows($result);
		  for($i = 0;$i < $numrows;$i++) {
		    echo "<option value=\"".pg_result($result,$i,"codigo")."\" ".(@$secretaria==pg_result($result,$i,"codigo")?"selected":"").">".pg_result($result,$i,"descricao")."</option>\n";
		  }
		  ?>
          </select>
		  </td>
      </tr>
      <tr> 
        <td nowrap><strong>Hora Final:</strong></td>
        <td nowrap><input name="horafim" type="text" value="<?=@$horafim?>" size="5" maxlength="5"> 
          <font size="-2">formato: HH:MM</font></td>
      </tr>
      <tr> 
        <td nowrap><strong>Descri&ccedil;&atilde;o:</strong></td>
        <td nowrap><textarea name="descricao" cols="50" rows="5"><?=@$descricao?></textarea></td>
      </tr>
      <tr> 
        <td nowrap><strong>Local:</strong></td>
        <td nowrap><input name="localid" type="text" value="<?=@$localid?>" size="50" maxlength="100"></td>
      </tr>
      <tr> 
        <td nowrap><strong>Telefone:</strong></td>
        <td nowrap><input name="telefone" type="text" value="<?=@$telefone?>" size="15" maxlength="15"></td>
      </tr>
      <tr> 
        <td nowrap><strong>Email:</strong></td>
        <td nowrap><input name="email" type="text" value="<?=@$email?>" size="50" maxlength="100"></td>
      </tr>
      <tr>
        <td nowrap><strong>Obs:</strong></td>
        <td nowrap><input name="obs" type="text" value="<?=@$obs?>" size="50"></td>
      </tr>
      <tr> 
        <td nowrap>&nbsp;</td>
        <td nowrap><input name="incluir" type="submit" id="incluir" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>>
          <input name="alterar" type="submit" id="alterar" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>>
          <input name="excluir" type="submit" id="excluir" value="Excluir" onClick="return js_excluir()" <? echo !isset($retorno)?"disabled":"" ?>>
          <input name="consultar" type="submit" id="consultar" onClick="this.form.target = 'consulta'" value="Procurar"></td>
      </tr>
    </table>
  </form>
  <iframe name="consulta" src="" width="770" height="150"></iframe>
</center>
  </td>
</tr>
</table>	
	</td>
  </tr>
</table>
<? } else { ?>
<?
  db_postmemory($HTTP_POST_VARS);
  if(checkdate($datainicio_mes,$datainicio_dia,$datainicio_ano)) {
    $datainicio = $datainicio_ano."-".$datainicio_mes."-".$datainicio_dia;
    $filtro = "and s_datainicio >= '$datainicio'";
  } 
  if(isset($HTTP_POST_VARS["filtro"]))
    $filtro = base64_decode($HTTP_POST_VARS["filtro"]);
    $sql = "select c.s_codigo as db_codigo,(CASE WHEN  c.s_intext = 'f' THEN 'Extranet' ELSE 'Intranet' END) as acesso,
          to_char(c.s_datainicio,'DD-MM-YYYY') as \"Data de Início\",c.s_horainicio as \"Hora de Início\",
          (CASE WHEN s.s_descricao is null THEN 'Evento' ELSE s.s_descricao END) as secretaria,
		  c.s_descricao as \"descrição\",c.s_localid as localidade,c.s_telefone as telefone,
          c.s_email as email,c.s_obs as obs
          from db_calendario c
		  left outer join db_secretaria s
		  on s.s_codigo = c.s_secretaria
		  where 2 > 1
		  ".@$filtro."
		  order by c.s_descricao";
  echo "<center>\n";
  db_lov($sql,100,"sit1_eventos001.php",base64_encode(@$filtro),"corpo");
  //db_lov($query,$numlinhas,$arquivo="",$filtro="%",$aonde="_self",$mensagem="Clique Aqui",$NomeForm="NoMe") { 
  echo "</center>\n";
?>
<? } ?>
	<?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>