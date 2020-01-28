<?php
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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("classes/db_protparamglobal_classe.php");
require_once("classes/db_protprocesso_classe.php");
require_once("classes/db_protprocessonumeracao_classe.php");
require_once("dbforms/db_classesgenericas.php");
require_once("dbforms/db_funcoes.php");

$sMsgErro                = "";

$oGet                    = db_utils::postMemory($_GET);
$clprotprocesso          = new cl_protprocesso; 
$clprotparamglobal       = new cl_protparamglobal;
$clprotprocessonumeracao = new cl_protprocessonumeracao;

$clprotparamglobal->rotulo->label();
$clprotprocessonumeracao->rotulo->label();

$clrotulo                = new rotulocampo;
$clrotulo->label("p06_sequencial");
$clrotulo->label("p06_tipo");
$clrotulo->label("p07_sequencial");
$clrotulo->label("p07_instit");
$clrotulo->label("p07_ano");
$clrotulo->label("p07_proximonumero");

$sSqlProtParamGlobal     = "SELECT * FROM protparamglobal";
$rsProtParamGlobal       = $clprotparamglobal->sql_record($sSqlProtParamGlobal);
$oProtParamGlobal        = db_utils::fieldsMemory($rsProtParamGlobal, 0);

/**
 * Tipo do Parametro do Cadastro global do protocolo
 * No caso já esta sendo forçado o tipo "Sequencial do Ano"
 */ 
$iTipoParamGlobal        = 2;
$db_opcao                = 1;

// Verifica qual o tipo setado no Cadastro global
if ($oProtParamGlobal->p06_tipo == 1) {
  $iTipoParamGlobal = $oProtParamGlobal->p06_tipo;
  $db_opcao = 22;
}

$sBotao = "Salvar";

if (isset($oGet->opcao)){
  switch (trim($oGet->opcao)){
    
    case "alterar": // Vem da Grid
      $sSqlProtProcessoNumeracao = "SELECT * FROM protprocessonumeracao WHERE p07_sequencial = {$oGet->p07_sequencial}"; 
      $rsProtProcessoNumeracao   = $clprotprocessonumeracao->sql_record($sSqlProtProcessoNumeracao);
      $oProtProcessoNumeracao    = db_utils::fieldsMemory($rsProtProcessoNumeracao, 0);
      
      $p07_sequencial    = $oProtProcessoNumeracao->p07_sequencial;
      $p07_instit        = $oProtProcessoNumeracao->p07_instit;
      $p07_ano           = $oProtProcessoNumeracao->p07_ano;
      $p07_proximonumero = $oProtProcessoNumeracao->p07_proximonumero;
      
      $sBotao = "Alterar";
      
      break;
    case "Alterar": // Vem do Form
      
      db_inicio_transacao();
      
      $iInstit = db_getsession("DB_instit");
      $iAno    = db_getsession("DB_anousu");
      $iNumero = $oGet->p07_proximonumero;
      
      $sWhereProtProcesso  = " trim(p58_numero) <> '' and p58_instit = {$iInstit} ";
      $sWhereProtProcesso .= " and p58_ano = {$iAno} and coalesce(cast(p58_numero as integer), 0) >= {$iNumero} "; 
      $sOrderProtProcessso = " p58_numero desc limit 1 ";      
      
      $sSqlProcessoNumeracao = $clprotprocesso->sql_query_file(null, "p58_numero", $sOrderProtProcessso, $sWhereProtProcesso);
      $rsProcessoNumeracao   = $clprotprocesso->sql_record($sSqlProcessoNumeracao);
      
      if ($clprotprocesso->numrows > 0) {
        
        $sMsgErro = "Não é possivel alterar numeração para o número informado ({$iNumero}), existe protocolo com numeração maior";
        db_msgbox($sMsgErro);
        db_fim_transacao(true);
      } else {
        
        $clprotprocessonumeracao->p07_sequencial    = $oGet->p07_sequencial;
        $clprotprocessonumeracao->p07_proximonumero = $oGet->p07_proximonumero;
        $clprotprocessonumeracao->alterar($clprotprocessonumeracao->p07_sequencial);
        
        if ($clprotprocessonumeracao->erro_status == 0) {
        db_fim_transacao(true);
      }
      
      db_fim_transacao(false);  
      db_msgbox($clprotprocessonumeracao->erro_msg);  
      }
      
      
      break;
    case "Salvar":
      $clprotprocessonumeracao->p07_proximonumero = $oGet->p07_proximonumero;
      $clprotprocessonumeracao->p07_ano           = $oGet->p07_ano;
      $clprotprocessonumeracao->p07_instit        = db_getsession("DB_instit");
      db_inicio_transacao();
      $clprotprocessonumeracao->incluir(null);
      if ($clprotprocessonumeracao->erro_status == 0) { 
        
        
        db_fim_transacao(true);
      }
      db_fim_transacao(false);
      db_msgbox($clprotprocessonumeracao->erro_msg);
      break;
  } 
}

?>


<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<style type="text/css">
  input:disabled { text-align: right; }
</style>
<?
  db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js ");
  db_app::load("estilos.css,grid.style.css");
?>
<style type="text/css">
#frm_content{
  margin-top: 30px;
}
.label{
  font-weight: bold;
}
</style>
<script type="text/javascript">
  function js_verificaForm(){
   var sBotao = $('botao').value;
    if (sBotao == "Salvar"){
      $('p07_proximonumero').value = "";
      $('p07_ano').value = "";
    }
   
  }
  
</script>

</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_verificaForm();">

<div align="center" id='frm_content'>
  <?
    include("forms/db_frmcontrolenumeracao.php");
  ?>
</div>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>