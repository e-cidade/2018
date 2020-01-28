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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("std/DBTime.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sessoes.php");

$oJson    = new services_json();
$oRetorno = new stdClass();
$oParam   = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $_POST["json"])));
$oRetorno->status = 1;
$oRetorno->message = '';

db_inicio_transacao();
try {
  
  switch($oParam->exec) {
    
    case "buscaHistoricosExcecoesBancos":
      
      $oDaoBancoshistmovexcecao = db_utils::getDao("bancoshistmovexcecao");
      $sWhere                   = "k66_codbco = {$oParam->iCodigoBanco}";
      $sSql                     = $oDaoBancoshistmovexcecao->sql_query(null, "*", "k166_sequencial desc", $sWhere);
      $rsResultado              = db_query($sSql);
      
      if (!$rsResultado) {
        throw new DBException("Erro ao buscar as exceções de movimentação do banco selecionado");
      }
      
      $iNumeroResultados   = pg_num_rows($rsResultado);
      $aHistoricosExcecoes = array();
      
      for ($iMovimento = 0; $iMovimento < $iNumeroResultados; $iMovimento++) {

        $oStdMovimentoExcecao   = db_utils::fieldsMemory($rsResultado, $iMovimento);
        $aMovimento             = array($oStdMovimentoExcecao->k66_sequencial, $oStdMovimentoExcecao->k66_descricao);
        $aHistoricosExcecoes[]  = $aMovimento;
      }
      
      $oRetorno->aHistoricosExcecoes = $aHistoricosExcecoes;
      
    break;
   
    case 'salvarDadosExcecoes' :
      
      $oDaoBancoshistmovexcecao = db_utils::getDao("bancoshistmovexcecao");
      $sWhere                   = "k166_bancoshistmov in (";
      $sWhere                  .= "select k66_sequencial from bancoshistmov where k66_codbco = {$oParam->iCodigoBanco})";      
      $oDaoBancoshistmovexcecao->excluir(null, $sWhere);
      
      if ( $oDaoBancoshistmovexcecao->erro_status == '0' ) {
        throw new DBException($oDaoBancoshistmovexcecao->erro_msg);
      }
      
      foreach ($oParam->aHistoricos as $oDadosHistorico) {
      
        $oDaoBancoshistmovexcecao                      = db_utils::getDao("bancoshistmovexcecao");
        $oDaoBancoshistmovexcecao->k166_bancoshistmov  = $oDadosHistorico->sCodigo;
        $oDaoBancoshistmovexcecao->incluir(null);
        
        if ($oDaoBancoshistmovexcecao->erro_status == '0') {
          throw new DBException($oDaoBancoshistmovexcecao->erro_msg);
        }
      } 
      $oRetorno->message = urlEncode("Dados Salvos com sucesso");
      db_fim_transacao(false);
      
    break;
    
  }
} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->status = 2;
  $oRetorno->message = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);   
?>