<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_db_formulas_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$oDaoDb_formulas = new cl_db_formulas;
$db_opcao    = 1;
$db_botao    = true;
$sPosScripts = "";

if (isset($incluir)) {

  db_inicio_transacao();

  $sWhereDaoDbformulas  = " db148_nome ilike '{$db148_nome}' ";
  $sSqlDaoDbformulas    = $oDaoDb_formulas->sql_query(null, "*", null, $sWhereDaoDbformulas);
  $rsDaoDbformulas      = db_query($sSqlDaoDbformulas);

  $oDaoDb_formulas->erro_status = "0";
  if(!$rsDaoDbformulas || pg_num_rows($rsDaoDbformulas) > 0) {

    $oDaoDb_formulas->erro_msg    = "Não foi possível recuperar a fórmula do banco.";

    if(pg_num_rows($rsDaoDbformulas) > 0) {
      
      $oDaoDb_formulas->erro_status = "1";
      $oDaoDb_formulas->erro_msg    = "Variável já cadastrada, informe um nome diferente.";
    }
  }

  $oDaoDb_formulas->db148_ambiente = '0';
  if (  $oDaoDb_formulas->erro_status == "0" ) {

    $db148_nome = trim($db148_nome);
    $oDaoDb_formulas->db148_formula = pg_escape_string(str_replace(array("\r\n", "\\"), array("\n", ""), $db148_formula));
    $oDaoDb_formulas->incluir($db148_sequencial);
  }

  db_fim_transacao(!$oDaoDb_formulas->erro_status);

  $sPosScripts = 'alert("Incluído com sucesso.");' . "\n";

  if($oDaoDb_formulas->erro_status == '0') {
    $sPosScripts = 'alert("' . $oDaoDb_formulas->erro_msg . '");' . "\n";
  }

  if ($oDaoDb_formulas->erro_status == '0') {

    $db_botao = true;
    $sPosScripts .= "document.form1.db_opcao.disabled = false;\n";

    if ($oDaoDb_formulas->erro_campo != "") {
      $sPosScripts .= "document.form1.{$oDaoDb_formulas->erro_campo}.classList.add('form-error');\n";
      $sPosScripts .= "document.form1.{$oDaoDb_formulas->erro_campo}.focus();\n";
    }
  } else {
    $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
  }
}

$sPosScripts .=  'js_tabulacaoforms("form1", "db148_nome", true, 1, "db148_nome", true);';
include(modification("forms/db_frmdb_formulas.php"));
