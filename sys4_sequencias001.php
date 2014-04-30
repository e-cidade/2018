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
include("dbforms/db_funcoes.php");

db_postmemory($_POST);
db_postmemory($_GET);

//////////INCLUIR/////////////
if(isset($HTTP_POST_VARS["atualizar"])) {
  db_postmemory($HTTP_POST_VARS);
  if(!isset($campos)) {
    pg_exec("BEGIN");
    pg_exec("update db_sysarqcamp set codsequencia = 0 where codsequencia = $codsequencia") or die("Erro(15) atualizando db_sysarqcamp");
    pg_exec("delete from db_syssequencia where codsequencia = $codsequencia") or die("Erro(16) excluindo em db_syssequencia");
    pg_exec("END");
    db_redireciona();	
  } else {
    $aux = split("#",$campos);
    $codcampo = $aux[0];
    $nomecampo = $aux[2];
    if($nomesequencia=="")
      $nomesequencia = $db_tabela."_".$nomecampo."_seq";
    pg_exec("BEGIN");
    //pg_exec("update db_sysarqcamp set codsequencia = 0
    //         where codsequencia = $codsequencia") or die("Erro(18) atualizando db_sysarqcamp");
    //if($codsequencia != "0")
    //  pg_exec("delete from db_syssequencia where codsequencia = $codsequencia") or die("Erro(17) excluindo em db_syssequencia");
      if($codsequencia == "0") {//sequencia não existe, criar uma
        //$result = pg_exec("select max(codsequencia) + 1 from db_syssequencia");
        $result = pg_exec("select nextval('db_syssequencia_codsequencia_se')");
        $codsequencia = pg_result($result,0,0);
        pg_exec("insert into db_syssequencia values($codsequencia,
                                                  '$nomesequencia',
                                                  $incrseq,
                                                  $minvalueseq,
                                                  $maxvalueseq,
                                                  $startseq,
                                                  $cacheseq)") or die("Erro(18) inserindo em db_syssequencia");    
      } else if($codsequencia != "0") {//sequencia existe, dá update
        pg_exec("update db_syssequencia set nomesequencia = '$nomesequencia',
                                          incrseq = $incrseq,
                                          minvalueseq = $minvalueseq,
                                          maxvalueseq = $maxvalueseq,
                                          startseq = $startseq,
                                          cacheseq = $cacheseq
			  where codsequencia = $codsequencia") or die("Erro(32) alterando em db_syssequencia");
      } else {
      pg_exec("ROLLBACK");
      db_erro("Erro na variável codsequencia");
    }
    pg_exec("update db_sysarqcamp set codsequencia = $codsequencia
             where codarq = $dbh_tabela and codcam = $codcampo") or die("Erro(38) alterando db_sysarqcamp");
    pg_exec("END");
    db_redireciona();
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
function js_submeter() {
  var F = document.form1;
  
  if(F.dbh_tabela.value == '') {
    alert("Clique em tabela para escolher uma");
	F.db_tabela.focus();
	return false;
  }
  if(F.incrseq.value == '') {
    alert("Campo 'Incrementar com' não pode ser vazio");
	F.incrseq.focus();
	return false;
  }
  if(F.minvalueseq.value == '') {
    alert("Campo 'Valor Mínimo' não pode ser vazio");
	F.minvalueseq.focus();
	return false;
  }  
  if(F.maxvalueseq.value == '') {
    alert("Campo 'Valor Máximo' não pode ser vazio");
	F.maxvalueseq.focus();
	return false;
  }  
  if(F.startseq.value == '') {
    alert("Campo 'Iniciar Com' não pode ser vazio");
	F.startseq.focus();
	return false;
  }    
  if(F.cacheseq.value == '') {
    alert("Campo 'Cache' não pode ser vazio");
	F.cacheseq.focus();
	return false;
  }    
  return true;
}
function js_iniciar() {
  var F = document.form1;
  
  if(F.campos.selectedIndex == -1)
    F.atualizar.value = "Excluir Sequência";
  F.atualizar.disabled = false;
}
function js_retsel() {
  var F = document.form1.campos;
  for(i = 0;i < F.length;i++)
    F.options[i] = new Option(F.options[i].text,F.options[i].value);
  js_trocacordeselect();
  document.form1.atualizar.value = "Excluir Sequência";
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_iniciar();js_trocacordeselect();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td height="430" align="center" valign="middle" bgcolor="#CCCCCC"> 
	<form name="form1" method="post" onSubmit="return js_submeter()">
        <table width="55%" border="0" cellspacing="0" cellpadding="5">
          <tr> 
            <td width="90%"> 
              <?
			  db_label("db_sysarquivo","tabela");
			  ?>
              <? 
			    if(isset($HTTP_POST_VARS["dbh_tabela"])) {
				  $result = pg_exec("select codarq,nomearq from db_sysarquivo where codarq = $dbh_tabela");
				  db_fieldsmemory($result,0);			    
			    }
			    echo db_text("tabela",40,40,@trim($nomearq),@$codarq,3)
			  ?>
            </td>
	  <td width="90%"></td>
          </tr> 
          <tr> 
            <td> <strong>Campos:</strong><br> <select name="campos" id="campos" onChange="document.form1.atualizar.value = 'Atualizar'" size="17" style="width:250px">
                <?
                if(isset($HTTP_POST_VARS["dbh_tabela"])) {
                  $result = pg_exec("select c.codcam,c.nomecam,ac.codsequencia from db_syscampo c inner join db_sysarqcamp ac on ac.codcam = c.codcam  where ac.codarq = $dbh_tabela");
                  $numrows = pg_numrows($result);
                  if($numrows > 0) {
                    for($i = 0;$i < $numrows;$i++) {
                      echo "<option value=\"".pg_result($result,$i,"codcam")."#".pg_result($result,$i,"codsequencia")."#".trim(pg_result($result,$i,"nomecam"))."\" ".(pg_result($result,$i,"codsequencia")!=0?"selected":"").">".pg_result($result,$i,"nomecam")."</option>\n";
                    }
                  }
                }
                if($numrows > 0) {
                  $codsequencia = 0;
                  for($i = 0;$i < $numrows;$i++) {
                    if(pg_result($result,$i,"codsequencia") != "0") {
                      $codsequencia = pg_result($result,$i,"codsequencia");
                      break;
                    }
                  }
                } else {
                  $codsequencia = 0;
                }
                if($codsequencia == "0") {
                  $incrseq     = "1";
                  $minvalueseq = "1";
                  $maxvalueseq = "9223372036854775807";
                  $startseq    = "1";
                  $cacheseq    = "1";  
                  $nomesequencia = "";
                } else {
                  $result = pg_exec("select incrseq,minvalueseq,maxvalueseq,startseq,cacheseq,nomesequencia from db_syssequencia where codsequencia = $codsequencia");
                  db_fieldsmemory($result,0);
                }
       		     ?>
              </select> <input type="hidden" name="codsequencia" value="<?=@$codsequencia?>"> 
            </td>
            <td valign="top">
	       <table width="100%" border="0" cellspacing="0" cellpadding="3">
                <tr> 
                  <td nowrap>
                    <strong>Sequencia:</strong>
                  </td>
                  <td nowrap>
                    <input name="nomesequencia" value="<?=@$nomesequencia?>" type="text" size="40" readonly>
                  </td>
                </tr>


                <tr> 
                  <td width="53%" nowrap><strong>Incrementar com:</strong></td>
                  <td width="47%" nowrap><input name="incrseq" type="text" id="incrseq" value="<?=@$incrseq?>" size="28" maxlength="20"></td>
                </tr>
                <tr> 
                  <td nowrap><strong>Valor M&iacute;nimo:</strong></td>
                  <td nowrap><input name="minvalueseq" type="text" id="minvalueseq" value="<?=@$minvalueseq?>" size="28" maxlength="20"></td>
                </tr>
                <tr> 
                  <td nowrap><strong>Valor M&aacute;ximo:</strong></td>
                  <td nowrap><input name="maxvalueseq" type="text" id="maxvalueseq" value="<?=@$maxvalueseq?>" size="28" maxlength="20"></td>
                </tr>
                <tr> 
                  <td nowrap><strong>Iniciar Com:</strong></td>
                  <td nowrap><input name="startseq" type="text" id="startseq" value="<?=@$startseq?>" size="28" maxlength="20"></td>
                </tr>
                <tr> 
                  <td nowrap><strong>Cache:</strong></td>
                  <td nowrap><input name="cacheseq" type="text" id="cacheseq" value="<?=@$cacheseq?>" size="28" maxlength="20"></td>
                </tr>

		
              </table></td>
          </tr>
          <tr> 
            <td colspan="2" nowrap><input name="atualizar" onClick="return confirm('Atualizar sequencia?')" accesskey="a" type="submit" value="Atualizar" disabled> 
              &nbsp;&nbsp; <input type="button" name="Button" onClick="js_retsel()" value="Retirar Sele&ccedil;&atilde;o de Campos"></td>
          </tr>
        </table>
      </form> 
    </td>
  </tr>
</table>
      <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>