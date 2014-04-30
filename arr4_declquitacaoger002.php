<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

set_time_limit(0);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("dbforms/db_funcoes.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_utils.php");
include("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require("libs/db_libsys.php");
require("dbagata/classes/core/AgataAPI.class");
require("model/documentoTemplate.model.php");
require("model/DeclaracaoQuitacao.model.php");
require("model/DeclaracaoQuitacaoExporta.model.php");
require("dbforms/db_layouttxt.php");

$oPost = db_utils::postMemory($_POST);

$oDeclQuitacao        = new DeclaracaoQuitacao();
$oDeclQuitacaoExporta = new DeclaracaoQuitacaoExporta();  


$iExercicio   = $oPost->ar30_exercicio;
$sOrigem      = $oPost->origem;
$sTipoEmissao = $oPost->tipo;
$dData        = $oPost->arquivo == 'S' ? date('Y-m-d', db_getsession('DB_datausu')) : null;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 

  db_app::load('scripts.js');
  db_app::load('estilos.css');

?>
</head>

<body bgcolor=#CCCCCC style="margin: 50px auto;">

<form name="form1" action="" method="post">

<?

db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));

if ($sOrigem == 'cgm' or $sOrigem == 'somentecgm') {
  
  $oDeclQuitacao->setOrigem(1);
  $oDeclQuitacao->setTipoCgm($sOrigem == 'somentecgm' ? true : false);
  
  $sReferencia = 'CGM';
  
} elseif($sOrigem == 'matric') {
  
  $oDeclQuitacao->setOrigem(2);
  
  $sReferencia = 'Matrícula';
  
} elseif($sOrigem == 'inscr') {
  
  $oDeclQuitacao->setOrigem(3);
  
  $sReferencia = 'Inscrição';
  
}

db_inicio_transacao();

db_criatermometro("termometro", "Concluido...", "blue", 1, "<div id='processando'><blink>Aguarde, processando...</blink></div>");
flush();

db_atutermometro(0, 100, 'termometro', 1, "Carregando informações para inicio do processo");

$oDeclQuitacao->setExercicio($iExercicio);

try {

  $rDaoOrigem = $oDeclQuitacao->carregaOrigem();

} catch (Exception $sException) {
  
  db_msgbox($sException->getMessage());
  exit;
  
}

if (pg_num_rows($rDaoOrigem) > 0) {
  
  $lErro = false;
  
	for ($i = 0; $i < pg_num_rows($rDaoOrigem); $i++) {
	  
	  $oOrigem = db_utils::fieldsMemory($rDaoOrigem, $i);
	  
	  db_atutermometro($i, pg_num_rows($rDaoOrigem), 'termometro', 1, "Processando registros {$sReferencia} - {$oOrigem->codigo_origem} (" . ($i + 1) . "/ " . pg_num_rows($rDaoOrigem) . ") ");
	  
    if($sOrigem == 'cgm' or $sOrigem == 'somentecgm') {
  
      $oDeclQuitacao->setCgm($oOrigem->codigo_origem);
      
    } elseif($sOrigem == 'matric') {
  
      $oDeclQuitacao->setMatricula($oOrigem->codigo_origem);
    
    } elseif($sOrigem == 'inscr') {
  
      $oDeclQuitacao->setInscricao($oOrigem->codigo_origem);
  
    }
    
    try {
      
      $oDeclQuitacao->salvar();
      
    } catch (Exception $sExcepion) {
      
      db_msgbox($sExcepion->getMessage());
      
      $lErro = true;
      
      break;
      
    }
    
  }
  
}	 

db_fim_transacao($lErro);

if (!$lErro) {
  
	if($sTipoEmissao == 'pdf') {
	  
	     
	  $oDeclQuitacaoExporta->setDeclaracoes($oDeclQuitacao->getArrayDeclaracoes());
	  
	  try {
	    
	    $oDeclQuitacaoExporta->geraPDF();
	    
	  } catch (Exception $sExcepion) {
	    
	    db_msgbox($sExcepion->getMessage());
	    
	  }
	  
	} else if($sTipoEmissao == 'txt') {
	  
	  try{
	      
	    $sArquivo       = $oDeclQuitacaoExporta->geraTXT($oDeclQuitacao->getOrigem(), $iExercicio, $sOrigem == 'somentecgm' ? true : false, $dData);
	    $sArquivoLayout = $oDeclQuitacaoExporta->geraLayoutTxt();
	    
	  } catch (Exception $sExcepion) {
	    
	    db_msgbox($sExcepion->getMessage());
	    
	  }
	  /*
	  echo '<script>';
	  echo 'var listagem;';
	  echo "listagem  = '$sArquivo#Download arquivo TXT (Declarações de Quitacao $iExercicio)|';";
	  echo "listagem += '$sArquivoLayout#Download arquivo TXT (Layout Declarações de Quitacao $iExercicio)|';";
	  echo "js_montarlista(listagem, 'form1')";
	  echo '</script>'; */

	  echo "<table align='center' style='margin-top: 20px; border: 1px solid; border-collapse: collapse; width: 500px;'>";
	  echo "<tr>";
	  echo "  <td colspan='2' align='center' style='background-color: blue; color: #FFF; font-weight: bold;padding: 2px;'>";
	  echo "    DOWNLOAD DOS ARQUIVOS";
	  echo "  </td>";
	  echo "</tr>";
	  
	  echo "<tr>";
	  echo " <td>TXT Declara&ccedil;&atilde;o de Quita&ccedil;&atilde;o</td>";
	  echo " <td style='color: #0000FF;text-decoration: underline; padding: 2px;'>";
	  echo "  <a href=\"#\" onclick=\"js_arquivo_abrir('$sArquivo')\">(Download) declaracao_quitacao_$iExercicio</a>";
	  echo " </td>";
	  echo "</tr>";
	  
    echo "<tr>";
    echo " <td>TXT Layout Declara&ccedil;&atilde;o de Quita&ccedil;&atilde;o</td>";
    echo " <td style='color: #0000FF;text-decoration: underline;padding: 2px;'>";
    echo "  <a href=\"#\" onclick=\"js_arquivo_abrir('$sArquivoLayout')\">(Download) layout_declaracao_quitacao_$iExercicio</a>";
    echo " </td>";
    echo "</tr>";
	  
	  echo "</table>";
	  
	  echo "<script>";
	  echo " document.getElementById('processando').innerHTML = 'Concluído'";
	  echo "</script>";
	  
	}
	
}

?>
</form>
</body>
</html>