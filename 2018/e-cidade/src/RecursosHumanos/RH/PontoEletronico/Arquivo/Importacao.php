<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo;

use \ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro\Cabecalho   as CabecalhoRegistro;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository\Cabecalho as CabecalhoRepository;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro\Marcacao    as MarcacaoRegistro;
use \ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository\Marcacao  as MarcacaoRepository;

/**
 * Classe responsável por instanciar o layout a ser importado e chamar as classes de acordo com a linha
 *
 * Class Importacao
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class Importacao {

  const CODIGO_LAYOUT_ARQUIVO   = 278;
  const REGISTRO_CABECALHO      = 1;
  const REGISTRO_MARCACAO_PONTO = 3;

  /**
   * @var \DBLayoutReader
   */
  private $oLayoutArquivo;

  /**
   * @var string
   */
  private $sArquivo;

  /**
   * @var integer
   */
  private $iCodigoArquivo;

  /**
   * @var \StdClass[]
   */
  private $oPeriodoEfetividade;

  /**
   * @var bool
   */
  private $lSobrescreverMarcacao = false;

  /**
   * Importacao constructor.
   * @param $sArquivo
   */
  public function __construct($sArquivo, Periodo $oPeriodo) {

    $this->sArquivo       = $sArquivo;
    $this->oLayoutArquivo = new \DBLayoutReader(Importacao::CODIGO_LAYOUT_ARQUIVO, $sArquivo, true, false);
    $this->oLayoutArquivo->processarArquivo(0, true, true);
    $this->oPeriodoEfetividade = $oPeriodo;
  }

  /**
   * @return int
   */
  public function getCodigoArquivo() {
    return $this->iCodigoArquivo;
  }

  /**
   * @param bool $lSobrescreverMarcacao
   */
  public function setSobrescreverMarcacao($lSobrescreverMarcacao) {
    $this->lSobrescreverMarcacao = $lSobrescreverMarcacao;
  }

  /**
   * Instancia o registro de acordo com o tipo de registro da linha
   *
   * @throws \BusinessException
   */
  public function persistirRegistros() {

    if(count($this->oLayoutArquivo->getLines()) == 0) {
      throw new \BusinessException("Registros não encontrados no arquivo.");
    }

    $oCabecalhoRegistro      = null;
    $aInconsistencias        = array();
    $aControlePISDataRemover = array();

    foreach($this->oLayoutArquivo->getLines() as $oLinha) {

      switch($oLinha->TIPO_REGISTRO) {

        /**
         * Registro 1 - Cabeçalho
         */
        case self::REGISTRO_CABECALHO:

          $oCabecalho = new CabecalhoRegistro();
          $oCabecalho->setArquivo($this->sArquivo);
          $oCabecalho->setLayoutLinha($oLinha);

          $oCabecalhoRepository = new CabecalhoRepository();
          $oCabecalhoRegistro   = $oCabecalhoRepository->add($oCabecalho, $this->oPeriodoEfetividade);

          if(empty($oCabecalhoRegistro)) {
            continue 2;
          }
          
          $this->iCodigoArquivo = $oCabecalhoRegistro->getCodigo();

          break;

        /**
         * Registro 3 - marcação de ponto
         */
        case self::REGISTRO_MARCACAO_PONTO:
          
          $sHora = preg_replace("/(\d{2})(\d{2})/", "$1:$2", $oLinha->HORARIO_MARCACAO);
          $oData = new \DBDate(preg_replace("/(\d{2})(\d{2})(\d{4})/", "$3-$2-$1", $oLinha->DATA_MARCACAO));
          $sPIS  = substr($oLinha->PIS_EMPREGADO, 1);

          if(!$this->validaPeriodo($oData)) {
            continue;
          }

          if(empty($oCabecalhoRegistro)) {
            continue;
          }

          if($this->lSobrescreverMarcacao === true) {

            if((!array_key_exists($sPIS, $aControlePISDataRemover) || $aControlePISDataRemover[$sPIS] != $oData->getDate())) {
              $this->removeRegistros($oCabecalhoRegistro, $sPIS, $oData);
            }
          }

          if($this->lSobrescreverMarcacao === false && $this->verificarMarcacaoNoDia($oData, $sPIS)) {

            if ((!array_key_exists($sPIS, $aControlePISDataRemover) || $aControlePISDataRemover[$sPIS] != $oData->getDate())) {
              continue;
            }
          }

          $oMarcacao = new MarcacaoRegistro();
          $oMarcacao->setData($oData);
          $oMarcacao->setDataVinculo($oData);
          $oMarcacao->setHora($sHora);
          $oMarcacao->setPIS($sPIS);
          $oMarcacao->setCabecalho($oCabecalhoRegistro);

          $oMarcacaoRepository = new MarcacaoRepository();
          $oMarcacaoRepository->add($oMarcacao);

          if(    $oMarcacaoRepository->getPISNaoEncontrado() != null
              && !in_array($oMarcacaoRepository->getPISNaoEncontrado(), $aInconsistencias)) {
            $aInconsistencias[] = $oMarcacaoRepository->getPISNaoEncontrado();
          }

          $aControlePISDataRemover[$sPIS] = $oData->getDate();

          break;
      }
    }

    return empty($aInconsistencias) ? true : $aInconsistencias;
  }

  /**
   * Valida se a data da marcação encontra-se no período da efetividade
   * @param \DBDate $oDataMarcacao
   * @return bool
   */
  private function validaPeriodo(\DBDate $oDataMarcacao) {
    return \DBDate::dataEstaNoIntervalo($oDataMarcacao, $this->oPeriodoEfetividade->getDataInicio(), $this->oPeriodoEfetividade->getDataFim());
  }

  /**
   * Remove os registros de marcações existentes anteriormente para o mesmo período de determinado PIS
   * @param CabecalhoRegistro $oCabecalho
   * @param string $sPIS
   * @throws \DBException
   */
  private function removeRegistros(CabecalhoRegistro $oCabecalho, $sPIS, \DBDate $oData) {

    $sWhereDataPIS  = "rh197_pontoeletronicoarquivo = {$oCabecalho->getCodigo()} AND rh197_pis = '{$sPIS}'";
    $sWhereDataPIS .= " AND rh197_data = '{$oData->getDate()}'";

    $oDaoRegistroJustificativa = new \cl_pontoeletronicoregistrojustificativa();
    $oDaoRegistroJustificativa->excluir(
      null,
      "rh199_pontoeletronicoarquivodataregistro IN (SELECT rh198_sequencial 
                                                      FROM pontoeletronicoarquivodataregistro
                                                           INNER JOIN pontoeletronicoarquivodata on rh197_sequencial = rh198_pontoeletronicoarquivodata
                                                     WHERE {$sWhereDataPIS})"
    );

    if($oDaoRegistroJustificativa->erro_status == '0') {
      throw new \DBException($oDaoRegistroJustificativa->erro_msg);
    }

    $oDaoDataRegistro = new \cl_pontoeletronicoarquivodataregistro();
    $oDaoDataRegistro->excluir(
      null,
      "rh198_pontoeletronicoarquivodata IN (SELECT rh197_sequencial 
                                              FROM pontoeletronicoarquivodata
                                             WHERE {$sWhereDataPIS})"
    );

    $oDaoData = new \cl_pontoeletronicoarquivodata();
    $oDaoData->excluir(null, $sWhereDataPIS);
  }

  /**
   * @param \DBDate $oData
   * @param $sPIS
   * @return bool
   * @throws \DBException
   */
  private function verificarMarcacaoNoDia(\DBDate $oData, $sPIS) {

    $oDaoPontoEletronicoData = new \cl_pontoeletronicoarquivodata();
    $SqlPontoEletronicoData  = $oDaoPontoEletronicoData->sql_query_file(
      null,
      'rh197_sequencial',
      null,
      "rh197_data = '{$oData->getDate()}' AND rh197_pis = '{$sPIS}'"
    );

    $rsPontoEletronicoData = db_query($SqlPontoEletronicoData);

    if(!$rsPontoEletronicoData) {
      throw new \DBException('Erro na validação da data de marcação na importação do ponto eletrônico. Contate o suporte.');
    }

    if(pg_num_rows($rsPontoEletronicoData) > 0) {
      return true;
    }

    return false;
  }
}