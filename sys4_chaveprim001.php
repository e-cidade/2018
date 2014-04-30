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

db_postmemory($_GET);
db_postmemory($_POST);

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
function js_campos_mostra(){
  js_OpenJanelaIframe('top.corpo','db_iframe_campo','sys4_chaveprim002.php?<?=base64_encode("tabela=$tabela")?>','Pesquisa campo',true,"20","160","300","300");
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
<br><br>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="center" valign="top" bgcolor="#CCCCCC">
		  <?
// verifica se esta clicando no botao para atualizar, se nao ele
// escreve os campos
if(!isset($HTTP_POST_VARS["atualizar"]) && !isset($HTTP_POST_VARS["excluir"])) {
  $resultc = pg_exec("select c.nomecam, c.codcam
                     from db_syscampo c
                     inner join db_sysarqcamp p
                     on p.codcam = c.codcam
                     where p.codarq = $tabela
					 order	by p.seqarq
					 ");
  $result = pg_exec("select c.nomecam,p.camiden
                     from db_syscampo c
                     inner join db_sysprikey p
                     on p.codcam = c.codcam
                     where p.codarq = $tabela
					 order by p.sequen
					 ");
  ?>
  <form method="post" name="f_campo">
  <input type="hidden" name="tabela" value="<?=$tabela?>">

    <fieldset style="width:550px">
      <legend>
      <b>Tabela: <?=$nomearq?></b>
      </legend>
        <table border="0" cellpadding="3" cellspacing="0">
          <tr> 
            <td align="right" valign="top"><b>Nome : <b></td>
            <td> 
              <?
              $numrows = pg_numrows($result);
	            if($numrows > 0) {      
                echo "<textarea name=alt_ind rows='7' cols='40'>";
                for($i = 0;$i < $numrows;$i++) {
                  echo "#".trim(pg_result($result,$i,"nomecam"))."#"."\n";
                }
	              echo "</textarea>";            
            	} else {	  
                echo "<textarea name=alt_ind rows='7' cols='40'></textarea> ";
            	}
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <b>Campo que Identifica Chave:<b>
            </td>
            <td>
              <select name="camiden" style="width:320px">
              <?
              $camiden = pg_result($result,0,"camiden");
              for($i = 0;$i < pg_numrows($resultc);$i++) {
                echo "<option value='".pg_result($resultc,$i,"codcam")."' ".($camiden==pg_result($resultc,$i,"codcam")?"selected":"").">".trim(pg_result($resultc,$i,"nomecam"))."</options>"."\n";
              }
              ?>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>

        <center>
          <input type="submit" name="atualizar" value="Atualizar">
          <input type="submit" name="excluir" value="Excluir" Onclick="return confirm('Quer realmente excluir este registro?')"> 
          <input type=button   value="Procurar Campo" OnClick="js_campos_mostra();"> 
          <input type="button" name="voltar" value="Voltar" onClick="location.href='sys3_campos001.php?<?=base64_encode("tabela=".$tabela)?>'">
        </center>

	</form>
	<?
// se clicou no botao de atualizacao, ele atualiza os campos
} else if(isset($HTTP_POST_VARS["atualizar"])) {
  db_postmemory($HTTP_POST_VARS);
  pg_exec("BEGIN");
  $alt_ind = split("\r\n",$alt_ind);
  $result = pg_exec("delete from db_sysprikey where codarq = $tabela") or die("Erro(94) deletando db_sysprikey");
  for($i = 0;$i < sizeof($alt_ind) - 1;$i++) {
    if($alt_ind[$i] != "" && $alt_ind[$i] != " " && $alt_ind[$i] != "  " ) {
      $alt_ind[$i] = trim(str_replace("#","",$alt_ind[$i]));
	  $alt_ind[$i] = trim(str_replace("#","",$alt_ind[$i]));
      $result = pg_exec("
				select codcam
				from db_syscampo
				where nomecam = '".$alt_ind[$i]."'") or die("Erro(100) selecionando db_syscampo");
      $s = $i + 1;
      $result = pg_exec("insert into db_sysprikey values($tabela,".pg_result($result,0,'codcam').",$s,$camiden)") or die("Erro(102) inserindo em db_sysprikey");
    }
  }
  pg_exec("END");
  db_redireciona("sys3_campos001.php?".base64_encode("tabela=$tabela"));
} else if(isset($HTTP_POST_VARS["excluir"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = pg_exec("delete from db_sysprikey where codarq = $tabela");
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