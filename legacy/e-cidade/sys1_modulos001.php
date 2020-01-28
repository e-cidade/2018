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

db_postmemory($_POST);
db_postmemory($_GET);

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));
if(isset($retorno)) {
  $sql = "select *,
                 to_char(dataincl,'DD') as dataincl_dia,
                 to_char(dataincl,'MM') as dataincl_mes,
                 to_char(dataincl,'YYYY') as dataincl_ano
            from db_sysmodulo
	         where codmod = $retorno";
  $result = pg_exec($sql);
  db_fieldsmemory($result,0);
}

//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["incluir"])) {
  db_postmemory($_POST);  
  if(!checkdate($dataincl_mes,$dataincl_dia,$dataincl_ano))
    db_erro("Data inválida(insert)");
  else
    $data = $dataincl_ano."-".$dataincl_mes."-".$dataincl_dia;
  pg_exec($conn,"insert into db_sysmodulo values (nextval('db_sysmodulo_codmod_seq'),'$nomemod','$descricao','$data','$ativo')") or die("Erro inserindo em db_sysmodulos");
  db_redireciona();
////////////////ALTERAR////////////////  
} else if(isset($HTTP_POST_VARS["alterar"])) {
  db_postmemory($_POST);
  if(!checkdate($dataincl_mes,$dataincl_dia,$dataincl_ano))
    db_erro("Data inválida(update)");
  else
    $data = $dataincl_ano."-".$dataincl_mes."-".$dataincl_dia;  
  pg_exec("update db_sysmodulo set nomemod   = '$nomemod',
			                       descricao = '$descricao',
			                       dataincl  = '$data',
					       ativo = '$ativo'
	      	where codmod  =  $codmod") or die("Erro atualizando db_sysmodulo");
  db_redireciona();
////////////////EXCLUIR//////////////
} else if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("delete from db_sysmodulo where codmod = ".$HTTP_POST_VARS["codmod"]) or die("Erro deletando tabela db_sysmodulo");
 db_redireciona();
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
Botao = 'incluir';
function js_submeter(obj) {
  if(Botao != 'procurar') {  
    if(obj.nomemod.value == "") {
      alert("Campo nome do módulo é obrigatório");
	  obj.nomemod.focus();
	  return false;
    }
	if(obj.descricao.value == "") {
      alert("Campo descrição é obrigatório");
	  obj.descricao.focus();
	  return false;
    }
	if(obj.dataincl_dia.value == "" || obj.dataincl_mes.value == "" || obj.dataincl_ano.value == "") {
	  alert("Campo data vazio ou inválido!");
	  obj.dataincl_dia.focus();
	  return false;
	}
  }
  return true;
}
function js_iniciar() {
if(document.form1)
  document.form1.nomemod.focus();
}

function js_Voltar(iCodMod){

  location.href = 'sys1_modulos001.php?retorno='+iCodMod;

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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar()" >
<?
$cl_modulo = new rotulo("db_sysmodulo");
$cl_modulo->label(); 
?>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br>
<br>
  
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top" bgcolor="#CCCCCC">
	<?
      if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
	  
	     $sql = "SELECT codmod    as db_codmod,
                      codmod    ,
                      nomemod   , 
                      descricao 
              FROM db_sysmodulo
			  WHERE nomemod like '".$HTTP_POST_VARS["nomemod"]."%'
              ORDER BY nomemod";
//  		db_lov($sql,15,"sys1_modulos001.php");
      db_lovrot($sql,15,"()","","js_Voltar|codmod","","NoMe");
      echo '<input type="button" name="voltar" value="Voltar" onClick="js_Voltar();">';
	  } else {
	 ?>
	 <form name="form1" method="post" onSubmit="return js_submeter(this)">
      <fieldset style="width:400px">
        <legend>
          <b>Dados do módulo : </b>
        </legend>
        <table border="0" cellspacing="0" cellpadding="0">
      	  <br>
          <tr> 
            <td width="16%" nowrap title="<?=$Tnomemod?>"><?=$Lnomemod?></td>
            <td width="84%"><input name="nomemod" title="<?=$Tnomemod?>" type="text" id="nomemod" value="<?=@$nomemod?>"></td>
          </tr>
          <tr> 
            <td valign="top" nowrap title="<?=$Tdescricao?>"><?=$Ldescricao?></td>
            <td><textarea name="descricao" cols="50" title="<?=$Tdescricao?>" rows="7" id="descricao"><?=@$descricao?></textarea></td>
          </tr>
          <tr> 
            <td nowrap title="<?=$Tdataincl?>"><?=$Ldataincl?></td>
            <td title="<?=$Tdataincl?>"> 
              <?
	            include("dbforms/db_funcoes.php");
	            $dataincl_dia = date("d");
	            $dataincl_mes = date("m");
	            $dataincl_ano = date("Y");
              db_inputdata("dataincl",@$dataincl_dia,@$dataincl_mes,@$dataincl_ano,1,'text',2);
              ?>
            </td>
          </tr>
          <tr>
            <td height="25" nowrap>
              <strong>Ativo:</strong>
            </td>
	          <td>
	          <?
	            $xx = array("t"=>"SIM","f"=>"NAO");
	            db_select('ativo',$xx,true,1,"");
            ?>
	          </td>
          </tr>
        </table>
        </fieldset>
        <input name="incluir" onClick="Botao = 'incluir'" accesskey="i" type="submit" id="incluir2" value="Incluir" <? echo isset($retorno)?"disabled":"" ?>> 
        <input name="alterar" accesskey="a" type="submit" id="alterar2" value="Alterar" <? echo !isset($retorno)?"disabled":"" ?>> 
        <input name="excluir" accesskey="e" type="submit" id="excluir2" value="Excluir" onClick="return confirm('Quer realmente excluir este registro?')" <? echo !isset($retorno)?"disabled":"" ?>> 
        <input name="procurar"onClick="Botao = 'procurar'" accesskey="p" type="submit" id="procurar2" value="Procurar">			
		    <input type="button"  onClick="location.href='sys3_tabelas001.php?<? echo base64_encode("codmod=$retorno&manutabela=true") ?>'" value="Ver Tabelas" <? echo !isset($retorno)?"disabled":"" ?>>
	      <input type="hidden" name="codmod" value="<?=@$codmod?>">
      </form> 
      <?
	  } // fim do else do if(isset($HTTP_POST_VARS["procurar"]) || isset($HTTP_POST_VARS["priNoMe"]) || isset($HTTP_POST_VARS["antNoMe"]) || isset($HTTP_POST_VARS["proxNoMe"]) || isset($HTTP_POST_VARS["ultNoMe"])) {
    ?>
    </td>
  </tr>
</table>
<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>	
</body>
</html>