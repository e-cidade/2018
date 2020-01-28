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
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

require_once("classes/db_processoforo_classe.php");
require_once("classes/db_processoforomov_classe.php");
require_once("classes/db_processoforoinicial_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clprocessoforo        = new cl_processoforo;
$clprocessoforoinicial = new cl_processoforoinicial;
$oDaoProcessoforomov   = new cl_processoforomov();
$db_botao              = false;
$db_opcao              = 33;
$lSqlErro              = false;

if (isset($oPost->anular)) {
  
  if (!$lSqlErro) {
    
    if ( isset( $v70_observacao ) ) {
      unset($v70_observacao, $GLOBALS["HTTP_POST_VARS"]["v70_observacao"]);
    }

   
    db_inicio_transacao();

    $rsProcessoforo = $clprocessoforo->sql_record($clprocessoforo->sql_query_file($oPost->v70_sequencial));
    
    $oProcessoforo  = db_utils::fieldsMemory($rsProcessoforo, 0); 
    
    $clprocessoforo->v70_sequencial      = $oProcessoforo->v70_sequencial      ;
    $clprocessoforo->v70_codforo         = $oProcessoforo->v70_codforo         ;        
    $clprocessoforo->v70_processoforomov = $oProcessoforo->v70_processoforomov ;
    $clprocessoforo->v70_id_usuario      = $oProcessoforo->v70_id_usuario;
    $clprocessoforo->v70_vara            = $oProcessoforo->v70_vara;
    $clprocessoforo->v70_data            = $oProcessoforo->v70_data;
    $clprocessoforo->v70_valorinicial    = $oProcessoforo->v70_valorinicial;
    // $clprocessoforo->v70_observacao   = $oProcessoforo->v70_observacao;
    $clprocessoforo->v70_instit          = $oProcessoforo->v70_instit          ;
    $clprocessoforo->v70_cartorio        = $oProcessoforo->v70_cartorio        ;        
    $clprocessoforo->v70_anulado         = 'true';
    
    $clprocessoforo->alterar($clprocessoforo->v70_sequencial);
    
    $sMsgErro   = $clprocessoforo->erro_msg;
    if ($clprocessoforo->erro_status == 0) {
      $lSqlErro = true;
    }
    
    if (!$lSqlErro) {
      
      $oDaoProcessoforomov->v73_processoforomovsituacao = 1; 
      $oDaoProcessoforomov->v73_id_usuario              = db_getsession('DB_id_usuario');
      $oDaoProcessoforomov->v73_processoforo            = $oPost->v70_sequencial;
      $oDaoProcessoforomov->v73_obs                     = $oPost->v70_observacao;
      $oDaoProcessoforomov->v73_data                    = date('Y-m-d', db_getsession('DB_datausu'));
      $oDaoProcessoforomov->v73_hora                    = date('H:i');

      $oDaoProcessoforomov->incluir(null);
      
      $sMsgErro = $oDaoProcessoforomov->erro_msg;
      if ($oDaoProcessoforomov->erro_status == "0") {
        $lSqlErro = true;                
      }
      
    }

    if (!$lSqlErro) {
    	
    	$sWhere                  = "processoforoinicial.v71_processoforo = {$oPost->v70_sequencial}";
    	$sSqlProcessoForoInicial = $clprocessoforoinicial->sql_query(null, "processoforoinicial.*", "v71_sequencial", $sWhere);
    	$rsProcessoForoInicial   = $clprocessoforoinicial->sql_record($sSqlProcessoForoInicial);
    	$iNumRows                = $clprocessoforoinicial->numrows; 
    	for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
    		
    		$oProcessoForoInicial = db_utils::fieldsMemory($rsProcessoForoInicial, $iInd);
    		
    		$clprocessoforoinicial->v71_sequencial = $oProcessoForoInicial->v71_sequencial;
    		$clprocessoforoinicial->v71_anulado    = 'true';
    	  $clprocessoforoinicial->alterar($clprocessoforoinicial->v71_sequencial);
    	  if ($clprocessoforoinicial->erro_status == 0) {
    	  	
          $lSqlErro = true;
          $sMsgErro = $clprocessoforoinicial->erro_msg;
        }
    	}
    }
    
    if (!$lSqlErro) {
    	
      $v70_sequencial   = '';
      $v70_codforo      = '';
      $v70_vara         = '';
      $v53_descr        = '';
      $v70_valorinicial = '';
      $v70_observacao   = ''; 
    }
    
    db_fim_transacao($lSqlErro);
  }

} else if (isset($oGet->chavepesquisa)) {
   
  $db_opcao          = 3;
  $db_botao          = true;
  $sSqlProcessoForo  = $clprocessoforo->sql_query($oGet->chavepesquisa);
  $rsSqlProcessoForo = $clprocessoforo->sql_record($sSqlProcessoForo);
  if ($clprocessoforo->numrows > 0) {
    
    db_fieldsmemory($rsSqlProcessoForo, 0);
    echo " <script>                                                                                                                 ";
    echo "   parent.iframe_iniciais.location.href          = 'jur1_processoforoiniciais001.php?v70_sequencial={$v70_sequencial}';   ";
    echo "   parent.document.formaba.iniciais.disabled     = true;                                                                 ";
    echo "   parent.document.formaba.processoforo.disabled = false;                                                                 ";
    echo " </script>                                                                                                                ";
    
    $v70_valorinicial = trim(db_formatar($v70_valorinicial,'p',' ',15,'e',2));
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC>
        <?
          include("forms/db_frminicialcodforo.php");
        ?>
</body>
</html>
<?
if (isset($oPost->anular)) {
	
  if ($lSqlErro) {
    db_msgbox($sMsgErro);
  } else {
    db_msgbox(_M('tributario.juridico.db_frminicialcodforo.anulado_com_sucesso'));
  }
}

if ($db_opcao == 33) {
	
  echo " <script>                                                                                                                 ";
  echo "   document.form1.pesquisar.click();                                                                                      ";
  echo "   parent.document.formaba.iniciais.disabled     = true;                                                                  ";
  echo "   parent.document.formaba.processoforo.disabled = true;                                                                  ";
  echo " </script>                                                                                                                ";
}
?>