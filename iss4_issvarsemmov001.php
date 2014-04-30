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


require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_utils.php');

$db_opcao      = 1;
$db_botao      = true;
$oPost         = db_utils::postMemory($_POST);
$bSucesso      = false;
$sMensagemErro = "Erro no Cancelamento de ISSQN Variável! ";

if (isset($oPost->lancar)) {
  
  try {
    
    $aItensIssVarSemMov = explode('#', $oPost->chaves);
    $bErro              = true;        
    
    db_inicio_transacao();
    
    $oCancelamentoISSQNVariavel = new CancelamentoISSQNVariavel();
    $oCancelamentoISSQNVariavel->setObservacao($oPost->k00_histtxt);
    $oCancelamentoISSQNVariavel->setEmpresa(new Empresa($oPost->inscricao));
    
    if (count($aItensIssVarSemMov) > 0) {
      
      foreach ($aItensIssVarSemMov as $sItemIssVarSemMov) {
        
        list($iNumpre, $iNumpar) = explode('-', $sItemIssVarSemMov);
        
        $oCancelamentoISSQNVariavel->addDebito($iNumpre, $iNumpar);
      }
    }
    
    $bSucesso = $oCancelamentoISSQNVariavel->incluirCancelamento();
    
    db_fim_transacao( false );
  } catch ( ParameterException $oErro ) {

    $sMensagemErro .= $oErro->getMessage();
    db_msgbox( $sMensagemErro );
    db_fim_transacao( true );
  } catch ( BusinessException $oErro ) {
  
    $sMensagemErro .= $oErro->getMessage();
    db_msgbox( $sMensagemErro );
    db_fim_transacao( true );
  } catch ( DBException $oErro ) {
  
    $sMensagemErro .= $oErro->getMessage();
    db_msgbox( $sMensagemErro );
    db_fim_transacao( true );
  }
}
?>
<html>
<head>
  <title>DBSeller Informática Ltda - Página Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="margin:30px 0 0;background:#CCC" onload="document.form1.q07_inscr.focus();">
  <div style="width:770px;margin: 0 auto">
      <div style="height:430px"><?php include('forms/db_frmissvarsemmov.php') ?></div>
  </div>
  <?php 
  db_menu(db_getsession('DB_id_usuario'),
          db_getsession('DB_modulo'),
          db_getsession('DB_anousu'),
          db_getsession('DB_instit')); 
  ?>
</body>
</html>
<?php 
if (isset($lancar)) {
  
  if ($bSucesso) {
    db_msgbox('Inclusão do Cancelamento de ISSQN Variável efetuada com sucesso!');    
  }
}
?>