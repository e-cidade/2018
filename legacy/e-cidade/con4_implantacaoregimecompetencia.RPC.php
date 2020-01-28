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


use ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\RegimeCompetencia as RegimeCompetenciaModel;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\RegimeCompetencia;

require_once(modification("model/ProgramacaoFinanceira.model.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$oJson               = JSON::create();
$oRetorno            = new stdClass();
$oParam              = $oJson->parse(str_replace("\\","",$_POST["json"]));
$oRetorno->erro      = false;
$iIdUsuario          = db_getsession('DB_id_usuario');

$iCodigoInstituicao = db_getSession("DB_instit");
$oRegimeCompetenciaRepository = new RegimeCompetencia();
try {
  db_inicio_transacao();
  
  switch ($oParam->exec) {
    
    case 'getAcordos':
    
      $where = array("ac16_acordosituacao = 4", "ac16_instit  = {$iCodigoInstituicao}");
      if (!empty($oParam->acordo)) {
        $where[] = "ac16_sequencial = {$oParam->acordo}";
      }
      
      if (!empty($oParam->categoria)) {
        $where[] = "ac16_acordocategoria = {$oParam->categoria}";
      }
      
      if (!empty($oParam->data_inicial)) {
        
        $dataInicial = new DBDate($oParam->data_inicial);
        $where[] = "ac16_datainicio >= '".$dataInicial->getDate()."'";
      }
      
      if (!empty($oParam->data_final)) {

        $dataFinal = new DBDate($oParam->data_final);
        $where[] = "ac16_datainicio <= '".$dataFinal->getDate()."'";
      }
      
      $aAcordos = AcordoRepository::getAcordosPorFiltro(implode(" and ", $where));
      $oRetorno->acordos = array();

      foreach ($aAcordos as $oAcordo) {
        
        $valorImplantado    = 0;
        $oRegimeCompetencia = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);
        
        $nValorProgramado = 0;
        if (!empty($oRegimeCompetencia)) {
        
          $aParcelas        = $oRegimeCompetenciaRepository->getParcelasDoRegime($oRegimeCompetencia);
          if (count($aParcelas) > 0 && $aParcelas[0]->getNumero() == 0) {            
            $valorImplantado = $aParcelas[0]->getValor();            
          }
          
          foreach ($aParcelas as $oParcela) {
            if ($oParcela->getNumero() == 0) {
              continue;
            }
            $nValorProgramado += $oParcela->getValor();
          }
        }
        

        $oAcordoRetorno                   = new \stdClass();
        $oAcordoRetorno->codigo           = $oAcordo->getCodigo();
        $oAcordoRetorno->numero           = $oAcordo->getNumeroAcordo()."/".$oAcordo->getAno();
        $oAcordoRetorno->resumo           = $oAcordo->getResumoObjeto();
        $oAcordoRetorno->valor_liquidado  = $oAcordo->getValorLiquidado();
        $oAcordoRetorno->valor_programado = $nValorProgramado;
        $oAcordoRetorno->valor_total      = $oAcordo->getValoresItens()->valoratual;
        $oAcordoRetorno->valor_implantado = $valorImplantado;
        $oRetorno->acordos[] = $oAcordoRetorno;
      }      
      break;
      
    case 'salvar':
      
        if (empty($oParam->acordos)) {
          throw new BusinessException("Não foram iinformados acordos para implantar.");          
        }
        
        foreach ($oParam->acordos as $acordoImplantar) {
            
          $oAcordo            = AcordoRepository::getByCodigo($acordoImplantar->codigo);
          $oRegimeCompetencia = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);

          if (empty($oRegimeCompetencia)) {
            
            $oRegimeCompetencia  = new RegimeCompetenciaModel();
            $oRegimeCompetencia->setAcordo($oAcordo);
            $oRegimeCompetencia->setDespesaAntecipada(false);
            $oRegimeCompetenciaRepository->persist($oRegimeCompetencia);
          }          

          $oDataInicial  = new DBDate($oAcordo->getDataInicial());
          $oCompetencia  = $oDataInicial->getCompetencia();
          $oParcela      = new Parcela();
          $aParcelasDoRegime      = $oRegimeCompetenciaRepository->getParcelasDoRegime($oRegimeCompetencia);
          $nValorAtualDoContrato  = $oAcordo->getValoresItens()->valoratual;
          $nValorParcelasLancadas = 0;

          $oParcelaImplantacao    = null;
          if (count($aParcelasDoRegime) > 0 && $aParcelasDoRegime[0]->getNumero() == 0) {            

            $oParcela = $aParcelasDoRegime[0];

            foreach ($aParcelasDoRegime as $oParcelaLancada) {

              if ($oParcelaLancada->getNumero() == 0) {

                $oParcelaImplantacao = $oParcelaLancada;                
                continue;
              }
              $nValorParcelasLancadas += $oParcelaLancada->getValor();
            }
          }

          /**
           * Total da implantação nao pode ser maior que o valor do atualizado do contrato
           */
          $nValorProgramado      = $nValorParcelasLancadas + $acordoImplantar->valor;
          $nValorProgramado      = floatval(trim($nValorProgramado));
          $nValorAtualDoContrato = floatval(trim($nValorAtualDoContrato));

          if ($nValorProgramado > $nValorAtualDoContrato) {            

            $numeroAcordo = $oAcordo->getNumeroAcordo()."/".$oAcordo->getAno();
            $sMensagem  = 'O valor total programado R$ '.trim(db_formatar($nValorProgramado, 'f'));            
            $sMensagem .= ' é maior que o saldo atual R$ '.trim(db_formatar($nValorAtualDoContrato, 'f'))." do contrato {$numeroAcordo}";
            throw new BusinessException($sMensagem);
          }

          /**
           * Removemos a parcela de implantação
           */
          if (!empty($oParcelaImplantacao)) {

            $oDaoParcelas   = new \cl_programacaofinanceiraparcela();
            $sWhereParcelas = "k118_sequencial = {$oParcelaImplantacao->getCodigo()}";
            $oDaoParcelas->excluir(null, $sWhereParcelas);
            if ($oDaoParcelas->erro_status == 0) {
              throw new BusinessException("Erro ao remover parcela de implantação do item");
            }           
          }

          /**
           * Incluimos a parcela para o regime como reconhecida, e numero de parcela = 0;
           */
          if ($acordoImplantar->valor > 0) {
            
            $oParcela = new Parcela();
            $oParcela->setReconhecida(true);
            $oParcela->setNumero(0);
            $oParcela->setCompetencia($oCompetencia);
            $oParcela->setValor($acordoImplantar->valor);
            $oRegimeCompetenciaRepository->persistirParcela($oRegimeCompetencia, $oParcela);
          }
          $oRetorno->message = "Implantação do regime de competência efetuada com sucesso.";
          
        }
      break;
  }

  db_fim_transacao(false);
} catch (Exception $e) {

  db_fim_transacao(true);
  $oRetorno->erro    = true;
  $oRetorno->message = $e->getMessage();
}
echo $oJson->stringify($oRetorno);