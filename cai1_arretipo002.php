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
require_once("libs/db_utils.php");
require_once("classes/db_arretipo_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clarretipo = new cl_arretipo;

$db_opcao   = 22;
$db_botao   = false;
$lErro      = false;

if (isset($alterar)) {
	
  $db_opcao = 2;
  db_inicio_transacao();
  
  $clarretipo->alterar($k00_tipo);
  if ($clarretipo->erro_status == '0') {
    $lErro = true;
  }
  
  db_fim_transacao($lErro);
  
} else if (isset($chavepesquisa)) {
	
   $db_opcao = 2;
   $result   = $clarretipo->sql_record($clarretipo->sql_query($chavepesquisa)); 
   $db_botao = true;
   
   db_fieldsmemory($result,0);
   
   $receitacreditodescr = $k02_descr;
   $tipodescr           = $k00_descr;
}
?>

<html>
<head>
<?php

	db_app::load('prototype.js, scripts.js, DBAbas.widget.js, DBAbasItem.widget.js');
	db_app::load("estilos.css, DBtab.style.css");
?>

<style>
body {
  padding: 0;
  margin: 16px 0 0 0;
}

.fieldsetPrincipal{
	width: 750px;
	margin: 20px auto 0 auto;
}

.fieldsetInterno{
	width: 750px;
	margin: 25px auto 0 auto;
}

.fieldsetSecundario{
	width: 700px;
	margin: 0 auto 0 auto;
}

fieldset legend {
 font-weight: bold;
}

</style>
</head>
<body bgcolor=#CCCCCC>
  <?
    include("forms/db_frmarretipo001.php");
		db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
	?>
</body>
<?
if (isset($alterar)) {
	
  if ($clarretipo->erro_status == "0") {
  	
    $clarretipo->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clarretipo->erro_campo != "") {
    	
      echo "<script> document.form1.".$clarretipo->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clarretipo->erro_campo.".focus();</script>";
    }
  } else {
    $clarretipo->erro(true,true);
  }
}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>
<script>
js_tabulacaoforms("form1","k00_codage",true,1,"k00_codage",true);
</script>
</html>