<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_portariaassinatura_classe.php");
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoPortariaassinatura = new cl_portariaassinatura;
$oDaoPortaria           = db_utils::getDao('portaria');
$db_botao    = false;
$db_opcao    = 33;
$sPosScripts = "";

if (isset($excluir)) {

  $db_opcao = 3;
  $oDaoPortaria->sql_record( $oDaoPortaria->sql_query_file(null, "*", null, "h31_portariaassinatura = {$rh136_sequencial}") );

  if ($oDaoPortaria->numrows > 0) {

    $sPosScripts .= 'alert("' . _M("recursoshumanos.rh.rec1_portariaassinatura.dependencia_portaria") . '");' . "\n";
    $db_botao     = true;
  } else {

    db_inicio_transacao();
    $oDaoPortariaassinatura->excluir($rh136_sequencial);
    db_fim_transacao();

    $sPosScripts .= 'alert("' . $oDaoPortariaassinatura->erro_msg . '");' . "\n";

    if ($oDaoPortariaassinatura->erro_status != "0") {
      $sPosScripts .= "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "';\n";
    }
  }

} else if(isset($chavepesquisa)) {

  $db_opcao = 3;
  $db_botao = true;
  $result   = $oDaoPortariaassinatura->sql_record( $oDaoPortariaassinatura->sql_query($chavepesquisa) );
  db_fieldsmemory($result, 0);
}

if ($db_opcao == 33) {
  $sPosScripts .= "document.form1.pesquisar.click();";
}

$sPosScripts .=  'js_tabulacaoforms("form1", "rh136_nome", true, 1, "rh136_nome", true);';

include("forms/db_frmportariaassinatura.php");
?>
