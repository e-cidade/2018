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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_orcdotacaocontr_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clorcdotacaocontr = new cl_orcdotacaocontr;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  $clorcdotacaocontr->incluir(null);
  db_fim_transacao();
  if ($clorcdotacaocontr->erro_status == 0) {
    db_msgbox($clorcdotacaocontr->erro_msg);
  }
} else if (isset($alterar)) {
  
  db_inicio_transacao();
  $clorcdotacaocontr->o61_sequencial= $o61_sequencial;
  $clorcdotacaocontr->alterar($o61_sequencial);
  db_fim_transacao();
  if ($clorcdotacaocontr->erro_status == 0) {
    db_msgbox($clorcdotacaocontr->erro_msg);
  }
  
} else if (isset($excluir)) {
  
  db_inicio_transacao();
  $clorcdotacaocontr->o61_sequencial= $o61_sequencial;
  $clorcdotacaocontr->excluir($o61_sequencial);
  db_fim_transacao();
  if ($clorcdotacaocontr->erro_status == 0) {
    db_msgbox($clorcdotacaocontr->erro_msg);
  }
  
}
if (isset($alterar) || isset($incluir) || isset($excluir)) {
  
  $o61_codigo     = null;
  $o15_descr      = null;
  $o61_sequencial = null;
  
}

if (isset ($opcao) && ($opcao == "alterar")) {
  
  $rsDot = $clorcdotacaocontr->sql_record($clorcdotacaocontr->sql_query_rec(null, null,"*", null, "o61_sequencial = {$o61_sequencial}"));
  if ($clorcdotacaocontr->numrows > 0) {
    
    $oContrapartida = db_utils::fieldsMemory($rsDot, 0);
    $o61_codigo     = $oContrapartida->o61_codigo;
    $o15_descr      = $oContrapartida->o15_descr;
    $o61_sequencial = $oContrapartida->o61_sequencial;
    $o61_anousu     = $oContrapartida->o61_anousu;
    $o61_coddot     = $oContrapartida->o61_coddot;
    $db_opcao       = 2;
    
  }
} 
if (isset ($opcao) && ($opcao == "excluir")) {
  
  $rsDot = $clorcdotacaocontr->sql_record($clorcdotacaocontr->sql_query_rec(null, null,"*", null, "o61_sequencial = {$o61_sequencial}"));
  if ($clorcdotacaocontr->numrows > 0) {
    
    $oContrapartida = db_utils::fieldsMemory($rsDot, 0);
    $o61_codigo     = $oContrapartida->o61_codigo;
    $o15_descr      = $oContrapartida->o15_descr;
    $o61_sequencial = $oContrapartida->o61_sequencial;
    $o61_anousu     = $oContrapartida->o61_anousu;
    $o61_coddot     = $oContrapartida->o61_coddot;
    $db_opcao       = 3;
    
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
	<?
	include("forms/db_frmorcdotacaocontr.php");
	?>
    </center>
</body>
</html>