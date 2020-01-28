<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpessoalmov_classe.php");
$clrhpessoal = new cl_rhpessoal;
$clrhpessoalmov = new cl_rhpessoalmov;
db_postmemory($HTTP_POST_VARS);

$anofolha = db_anofolha();
$mesfolha = db_mesfolha();


$oReajusteSalarial = unserialize(base64_decode(db_getsession('DBReajusteSalarial')));
$aServidores = $oReajusteSalarial->getServidores();

if (isset($processar)) {

  db_inicio_transacao();
  $sMensagem = '';

  foreach ($aServidores as $oServidor) {
    
    $iMovimentacao = $oServidor->getCodigoMovimentacao();
    
    $iValor        = ${"valor_{$iMovimentacao}"};
    $iPercentual  = ${"perce_{$iMovimentacao}"};

    if (empty($iValor) && empty($iPercentual)){
      continue;
    }

    try{

      $oReajusteSalarial = new ReajusteSalarial();
      $oReajusteSalarial->adicionaServidor($oServidor);

      if (!empty($iValor)) {
        $oReajusteSalarial->setValor($iValor);
      }

      if (!empty($iPercentual)) {
        $oReajusteSalarial->setPercentual($iPercentual);
      }

      $oReajusteSalarial->reajustaSalario();
      $sMensagem = 'Salários atualizados com sucesso.';
    } catch(Exception $oException) {

      $sMensagem = $oException->getMessage();
      db_fim_transacao(true);    
    }
  }

  db_fim_transacao(false);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
include("forms/db_frmreajustesal001.php");
?>
</body>
</html>

<?
if(isset($processar)){
  db_msgbox($sMensagem);
  echo '<script>parent.janelaReajuste.hide();</script>';
}
?>
