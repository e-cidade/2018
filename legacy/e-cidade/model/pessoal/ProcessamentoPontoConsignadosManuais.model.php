<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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

/**
* 
*/
class ProcessamentoPontoConsignadosManuais extends ProcessamentoPontoConsignados{

  /**
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Realiza a inclusão do desconto
   * @throws \DBException
   */
  public function importarDadosPreponto() {

    $this->oInstituicao = InstituicaoRepository::getInstituicaoSessao();
    $oCompetencia       = new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());
    $aArquivos          = ArquivoConsignadoManualRepository::getContratosAtivos();

    if (empty($aArquivos)) {
      return;
    }

    $oImportacaoArquivoConsignadoManual = new ImportacaoArquivoConsignadoManual($oCompetencia, $this->oInstituicao);
    $oImportacaoArquivoConsignadoManual->processar(true);

    foreach ($aArquivos as $oArquivo) {
      
      if ($oArquivo->isProcessado()) {
        continue;
      }
  
      /**
       * Retorna todas as parcelas a partir desta competência
       */
      $aParcelas = array();
      $aParcelas = ArquivoConsignadoManualParcelaRepository::getParcelasDoFinanciamentoApartirDaCompetencia($oArquivo, $oCompetencia); 

      foreach ($aParcelas as $oParcela) {
        
        /**
         * Ignora as parcelas em que a competência é diferente da atual
         * Ignora as parcelas já processadas
         */
        if(!$oParcela->getCompetencia()->comparar($oCompetencia) || $oParcela->isProcessado()) { 
          continue;
        }

        /**
         * Ignora as parcelas que já tem um motivo preenchido, pois já foram validadas
         */
        if (trim($oParcela->getMotivo()) == false) {

          $oParcela->setValorDescontado($oParcela->getValor());
          $this->salvarNoPrePonto($oParcela);
        }

        $oParcela->setProcessado(true);
        ArquivoConsignadoManualParcelaRepository::persist($oParcela, $oArquivo);
      }

      /**
       * Caso só tenha uma parcela significa que estou na última,
       * logo pode setar o "arquivo"/contrato como processado
       */ 
      if(count($aParcelas) == 1) {
        $oArquivo->setProcessado(true);
        ArquivoConsignadoManualRepository::persist($oArquivo);
      }
    }
    
    return;
  }

  /**
   * @param  \ArquivoConsignadoManualParcela $oParcela
   * @throws \BusinessException
   */
  private function salvarNoPrePonto(ArquivoConsignadoManualParcela $oParcela) {

    $oDaoPrePonto = new cl_rhpreponto();
    $oDaoPrePonto->rh149_instit     = $this->oInstituicao->getSequencial();
    $oDaoPrePonto->rh149_regist     = $oParcela->getServidor()->getMatricula();
    $oDaoPrePonto->rh149_rubric     = $oParcela->getRubrica()->getCodigo();
    $oDaoPrePonto->rh149_valor      = preg_replace(array("/\,(\d{1,2})$/"), array(".$1"), $oParcela->getValorDescontado());
    
    if(preg_match("/(.*)(\.\d{2})$/", $oDaoPrePonto->rh149_valor, $padraoEncontrado)) {
      $oDaoPrePonto->rh149_valor    = str_replace(array(",", "."), "", $padraoEncontrado[1]);
      $oDaoPrePonto->rh149_valor   .= $padraoEncontrado[2];
    } else {
      $oDaoPrePonto->rh149_valor    = str_replace(array(",", "."), "", $oDaoPrePonto->rh149_valor);
    }

    $oDaoPrePonto->rh149_quantidade = 1;
    $oDaoPrePonto->rh149_tipofolha  = FolhaPagamento::TIPO_FOLHA_SALARIO;
    $oDaoPrePonto->incluir(null);
    if ($oDaoPrePonto->erro_status == 0) {
      throw new BusinessException("Erro ao salvar Dados no pre-ponto");
    }
  }
}
