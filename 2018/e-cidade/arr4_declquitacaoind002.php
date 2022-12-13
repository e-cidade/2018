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
include("dbforms/db_funcoes.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_libsys.php");
require_once("dbagata/classes/core/AgataAPI.class");
require_once("model/documentoTemplate.model.php");
require("model/DeclaracaoQuitacao.model.php");
require("model/DeclaracaoQuitacaoExporta.model.php");

$oGet          = db_utils::postMemory($_GET);

$oDeclQuitacao = new DeclaracaoQuitacao();
$oDeclQuitacaoExporta = new DeclaracaoQuitacaoExporta();

if(isset($oGet->z01_numcgm) and $oGet->z01_numcgm != '') {

  $oDeclQuitacao->setOrigem(1);
  $oDeclQuitacao->setCgm($oGet->z01_numcgm);
  $oDeclQuitacao->setTipoCgm($oGet->regra_cgm == 1 ? true : false);
  
}else if (isset($oGet->j01_matric) and ($oGet->j01_matric != '')) {
  
  $oDeclQuitacao->setOrigem(2);
  $oDeclQuitacao->setMatricula($oGet->j01_matric);
  
}else if (isset($oGet->q02_inscr) and ($oGet->q02_inscr != '')) {
  
  $oDeclQuitacao->setOrigem(3);
  $oDeclQuitacao->setInscricao($oGet->q02_inscr);
  
}

$aExercicios = explode(',', $oGet->exercicios);

$lErro = false;

db_inicio_transacao();

if (count($aExercicios) > 0) {
  
  for ($i = 0; $i < count($aExercicios); $i++) {
      
    $oDeclQuitacao->setExercicio($aExercicios[$i]);

    try {
      
      $oDeclQuitacao->salvar();
      
    }catch (Exception $sExcepion) {
      
      db_msgbox($sExcepion->getMessage());
      $lErro = true;
      break;
      
    }
    
  }
  
}

db_fim_transacao($lErro);

if(!$lErro) {

  $oDeclQuitacaoExporta->setDeclaracoes($oDeclQuitacao->getArrayDeclaracoes());
	
	try {
	  
	  $oDeclQuitacaoExporta->geraPDF();
	  
	} catch (Exception $sExcepion) {
	  
	  db_msgbox($sExcepion->getMessage());
	  
  }
  
} else {
  
  echo '<script>window.close();</script>';
  
}

?>