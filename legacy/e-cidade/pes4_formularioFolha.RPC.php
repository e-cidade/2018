<?php

/**
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus   = '1';
$oRetorno->sMensagem = '';

try {
  
  switch ($oParam->sExecucao) {
    
    case 'BuscaPrevidencia':
      
        $oDaoInss = db_utils::getDao('inssirf');
        
        $sCampos  = " distinct                                  ";
        $sCampos .= " case r33_codtab                           ";
        $sCampos .= "      when 2 then 0                        ";
        $sCampos .= "      when 1 then 5                        ";
        $sCampos .= "      else (cast(r33_codtab as integer)- 2)";
        $sCampos .= " end as r33_codtab,                        ";
        $sCampos .= " case r33_codtab                           ";
        $sCampos .= "      when 2 then 'Todos'                  ";
        $sCampos .= "      when 1 then 'Sem Prev.'              ";
        $sCampos .= "      else r33_nome                        ";
        $sCampos .= " end as r33_nome                           ";
        
        $sWhere   =  " r33_anousu       = ".db_anofolha();
        $sWhere  .=  "   and r33_mesusu = ".db_mesfolha();
        $sWhere  .=  "   and r33_instit = ".db_getsession('DB_instit');
        
        $sQueryInss  = $oDaoInss->sql_query_file('r33_codtab',null, $sCampos, null, $sWhere);
        
        $rsQueryInss = db_query($sQueryInss);
        
        if ( !$rsQueryInss ) {
          throw new DBException('Erro ao buscar os dados sobre as tabelas de previdencia.');
        }
        
        $oRetorno->aPrevidencias = db_utils::getCollectionByRecord($rsQueryInss, false, false, true);
        
        break;
    case 'BuscaAnoMesFolha':
      
        require_once 'model/pessoal/std/DBPessoal.model.php';
        
        $oRetorno->iAno = DBPessoal::getAnoFolha();
        $oRetorno->iMes = str_pad(DBPessoal::getMesFolha(), 2, "0", STR_PAD_LEFT);
        
        break;
    case 'VerificarFolhaPagamentoAberto':
        
        $iTipoFolha             = $oParam->iTipoFolha;
        
        $iAnoFolha              = isset($oParam->iAnoFolha) ? $oParam->iAnoFolha : DBPessoal::getAnoFolha();
        $iMesFolha              = isset($oParam->iMesFolha) ? $oParam->iMesFolha : DBPessoal::getMesFolha(); 
        $oDBCompetencia         = new DBCompetencia($iAnoFolha, $iMesFolha); 
       
        $lFolhaAberta           = FolhaPagamento::hasFolhaAberta($iTipoFolha, $oDBCompetencia);
        $oRetorno->lFolhaAberta = $lFolhaAberta;
        
        break;
    case 'BuscaFolhas':

        $iTipoFolha         = $oParam->iTipoFolha;
        $lStatus            = $oParam->lStatus;

        $oDaoFolhaPagamento = db_utils::getDao('rhfolhapagamento');
        $sOrdem             = "rh141_codigo asc";
        $sWhere             = " rh141_anousu        = {$oParam->iAno}";
        $sWhere            .= " and rh141_mesusu    = {$oParam->iMes}";
        $sWhere            .= " and rh141_tipofolha = {$iTipoFolha}";
        
        if ($lStatus) {
          $sWhere            .= " and rh141_aberto = true";
        }

        if (!$lStatus) {
          $sWhere            .= " and rh141_aberto = false";
        }

        $sSql             = $oDaoFolhaPagamento->sql_query_file(null,'*', $sOrdem, $sWhere);

        $rsFolhaPagamento = db_query($sSql);
        
        if ( !$rsFolhaPagamento ) {
          throw new DBException('Erro ao buscar os dados da tabela rhfolhapagamento.');
        }
        
        $oRetorno->aNumeroFolhas = db_utils::getCollectionByRecord($rsFolhaPagamento,false,false,true);

        break;

    case 'BuscaTiposReajuste':

      $oDaoReajusteParidade = db_utils::getDao('rhreajusteparidade');
      $sSql                 = $oDaoReajusteParidade->sql_query_file(null, '*', 'rh148_sequencial');
      $rsReajusteParidade   = db_query($sSql);

      if (!$rsReajusteParidade) {
        throw new DBException('Erro ao buscar os dados da tabela rhreajusteparidade.');
      }

      $aTipoReajuste     = array('0' => '');
      $aReajusteParidade = db_utils::getCollectionByRecord($rsReajusteParidade, false, false, true);

      foreach ($aReajusteParidade as $oReajusteParidade) {
        $aTipoReajuste[$oReajusteParidade->rh148_sequencial] = $oReajusteParidade->rh148_descricao;
      }

      $oRetorno->aTiposReajuste = $aTipoReajuste;

      break;
    
    /**
     * Verifica se a folha de pagamento esta aberta ou fechada.
     * 
     * @param Integer $iTipoFolha
     * @param Integer $iAnoUsu
     * @param Integer $iMesUsu
     * @param Integer $lStatus
     * @return Boolean $lFolhaAberta
     */  
    case 'VerificarFolhaPagamento':
      
      $iTipoFolha   = $oParam->iTipoFolha;
      $iAnoFolha    = $oParam->iAnoUsu;
      $iMesFolha    = $oParam->iMesUsu;
      $lStatus      = $oParam->lStatus; 
      
      $oDBCompetencia         = new DBCompetencia($iAnoFolha, $iMesFolha); 
      $lFolhaAberta           = FolhaPagamento::hasFolhaTipo($iTipoFolha, $oDBCompetencia, $lStatus);
      $oRetorno->lFolhaAberta = $lFolhaAberta;
      
      break;
  }
} catch ( Exception $eErro ) {
  
  $oRetorno->iStatus   = '2';
  $oRetorno->sMensagem = $eErro->getMessage();
}

echo $oJson->encode($oRetorno);