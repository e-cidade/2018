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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");

db_app::import('exceptions.*');

$oPost    = db_utils::postMemory($_POST);

$oJson    = new services_json();
$lErro    = false;
$sMsgErro = '';

try {
  
  switch ($oPost->sMethod) {
    
    case 'consultaSituacoesHidrometro':

      $aListaSituacao = array();
      
      $oDaoSituacaoLeitura = new cl_aguasitleitura();
      
      $sCampos = "x17_codigo, x17_descr";
      
      $sSql        = $oDaoSituacaoLeitura->sql_query(null, $sCampos, 'x17_descr', '');
      $rsSituacoes = $oDaoSituacaoLeitura->sql_record($sSql);

      if ($oDaoSituacaoLeitura->numrows) {
        $aSituacoes = db_utils::getCollectionByRecord($rsSituacoes);
      }
      
      $aRetornoSituacao = array();
      
      foreach ($aSituacoes as $iIndice => $oSituacao) {
        
        $aRetornoSituacao[$iIndice] = new StdClass();
        
        $aRetornoSituacao[$iIndice]->codigo    = $oSituacao->x17_codigo;
        $aRetornoSituacao[$iIndice]->descricao = utf8_encode($oSituacao->x17_descr);
      }
      
      $aRetorno = array("lErro"      => false,
                        "aSituacoes" => $aRetornoSituacao); 
    break;
  } 
  
} catch (Exception $eErro){
  
  db_fim_transacao(true);
  
  $aRetorno = array("lErro" => true,
                    "sMsg"  => urlencode($eErro->getMessage()));
} 

echo $oJson->encode($aRetorno);