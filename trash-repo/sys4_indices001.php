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

db_postmemory($_GET);
db_postmemory($_POST);

parse_str(base64_decode($HTTP_SERVER_VARS['QUERY_STRING']));

$tabela = isset($HTTP_POST_VARS["tabela"])?$HTTP_POST_VARS["tabela"]:$tabela;
$nomearq = pg_exec("select nomearq from db_sysarquivo where codarq = $tabela");
$nomearq = pg_result($nomearq,0,0);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="5000">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
Botao = "";
function js_submeter(obj) {
  if(Botao == 'atualizar') {
    if(obj.nome_ind.value == "") {
	  alert("Campo nome do indice é obrigatório");
	  obj.nome_ind.focus();
	  return false;
	}
	if(obj.alt_ind.value == '') {
	  alert("Campo 'campos' não pode ser vazio.");
	  obj.alt_ind.focus();
	  return false;
	}
  }
  return true;
}


function js_campos_mostra(){
  js_OpenJanelaIframe('top.corpo','db_iframe_campo','sys4_chaveprim002.php?<? echo base64_encode("tabela=$tabela") ?>','Pesquisa campo',true,"20","160","300","300");
}

</script>
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

<link href="estilos.css" rel="stylesheet" type="text/css">
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
<br>
<br>
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top" bgcolor="#CCCCCC">
<?
if(!isset($HTTP_POST_VARS["b_campo_ind"]) && !isset($HTTP_POST_VARS["excluir"])) {
  if(isset($ind)) {
    $result = pg_exec("select i.campounico,i.nomeind as nome_indice,c.nomecam as nome_campo
			           from db_sysindices i
		               inner join db_syscadind ci
			           on ci.codind = i.codind
			           inner join db_syscampo c
			           on c.codcam = ci.codcam
			           where i.codind = $ind
			           order by ci.sequen");		
    $num_linhas = pg_numrows($result);
    $nome_ind = pg_result($result,0,"nome_indice");
  }
?>
	<form method="post" name="f_campo" onSubmit="return js_submeter(this)">
  <fieldset style="width:500px">
    <legend><b>Tabela: <?=@$nomearq?></b></legend>
	    <table>
        <tr> 
          <td>
            <b>Nome : </b>
          </td>
          <td>
            <input type="text" size="40" name="nome_ind" value="<?=@$nome_ind?>">
          </td>
        </tr>
        <tr> 
          <td valign="top">
            <b>Campos : </b>
          </td>
          <td>
            <textarea rows="7" cols="37" name="alt_ind"><?if(isset($ind)) {for($i = 0;$i < $num_linhas;$i++) {echo trim(pg_result($result,$i,"nome_campo"))."\n";}}?>
            </textarea> 
          </td>
        </tr>
        <tr>
          <td>
            <b>Unico : </b>
          </td>
          <td>
            <input name="campounico" type="checkbox" id="campounico" value="unico" <?echo @pg_result($result,0,"campounico")=="0" || @pg_result($result,0,"campounico")==""?"":"checked" ?>>
          </td>            
        </tr>            
      </table>
  </fieldset>
    <input type="submit" onClick="Botao='atualizar'" name="b_campo_ind" value="Atualizar">
    <input type="submit" name="excluir" value="Excluir" OnClick="return confirm('Voce quer realmente excluir este registro?')">
    <input type="button" value="Procurar" onClick="js_campos_mostra();" name="button"> 
    <input type="button" name="voltar" value="Voltar" onClick="location.href='sys3_campos001.php?<?=base64_encode("tabela=".$GLOBALS["tabela"])?>'">
	  <input type="hidden" name="tabela" value="<?=@$tabela ?>">
	  <input type="hidden" name="ind" value="<?=@(isset($HTTP_POST_VARS["ind"])?$HTTP_POST_VARS["ind"]:$ind)?>">
	</form>
<?
} else if(!isset($HTTP_POST_VARS["excluir"])) {
    db_postmemory($HTTP_POST_VARS);
    if(isset($campounico)){
  	  $campounico = $campounico==""?"0":"1";
	}else{
	  $campounico = "0";
	}
	if($ind == "") {
		pg_exec("BEGIN");
		pg_exec("insert into db_sysindices values(nextval('db_sysindices_codind_seq'),'$nome_ind',$tabela,'$campounico')") or die("Erro(94) inserindo em db_sysindices");
		$result = pg_exec("select codind 
                           from db_sysindices 
                           where nomeind = '$nome_ind'") or die("Erro(97) selecionando db_sysindices");
		$ind = pg_result($result,0,0);
		$alt_ind = split("\r\n",$alt_ind);
		for($i = 0;$i < sizeof($alt_ind) - 1;$i++) {
			if($alt_ind[$i] != "" && $alt_ind[$i] != " " && $alt_ind[$i] != "  ") {
                $alt_ind[$i] = trim(str_replace("#","",$alt_ind[$i]));
	            $alt_ind[$i] = trim(str_replace("#","",$alt_ind[$i]));
				$result = pg_exec("select codcam from db_syscampo where nomecam = '$alt_ind[$i]'") or die("Erro(102) selecionando db_syscampo");
				$s = $i + 1;
				pg_exec("insert into db_syscadind values($ind,".pg_result($result,0,"codcam").",$s)") or die("Erro(104) inserindo em db_syscadind");
			}
		}	
		pg_exec("END");
		db_redireciona("sys3_campos001.php?".base64_encode("tabela=$tabela"));
	} else {
		pg_exec("BEGIN");
		pg_exec("update db_sysindices set nomeind = '$nome_ind',campounico = '$campounico' where codind = $ind") or die("Erro(111) atualizando db_sysindices");
		pg_exec("delete from db_syscadind where codind = $ind") or die("Erro(112) excluindo db_syscadind");
		$alt_ind = split("\r\n",$alt_ind);
		for($i = 0;$i < sizeof($alt_ind) - 1;$i++) {
			if($alt_ind[$i] != "" && $alt_ind[$i] != " " && $alt_ind[$i] != "  ") {
				$result = pg_exec("
					select codcam
					from db_syscampo
					where nomecam = '$alt_ind[$i]'");
				$s = $i + 1;
				$result = pg_exec($conn,"
					insert into db_syscadind 
					values($ind,".pg_result($result,0,"codcam").",$s)") or die("Erro(156) inserindo em db_syscadind");
			}
		}
		pg_exec("END");
        db_redireciona("sys3_campos001.php?".base64_encode("tabela=$tabela"));
	}
} else if(isset($HTTP_POST_VARS["excluir"])) {
	pg_exec("BEGIN");
	pg_exec("delete from db_syscadind	where codind = $ind") or die("Erro(131) excluindo db_syscadind");
	pg_exec("delete from db_sysindices where codind = $ind") or die("Erro(132) excluindo db_sysindices");
	pg_exec("END");
    db_redireciona("sys3_campos001.php?".base64_encode("tabela=$tabela"));
}
 ?>
		  </td>
        </tr>
      </table>
	</td>
  </tr>
</table>
	<?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>