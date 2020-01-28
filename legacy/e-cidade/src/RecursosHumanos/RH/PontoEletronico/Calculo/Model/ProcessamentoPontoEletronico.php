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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model;

use ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaServidor;
use ECidade\RecursosHumanos\RH\Efetividade\Model\Periodo;
use ECidade\RecursosHumanos\RH\Efetividade\Repository\Periodo as PeriodoRepository;

use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro\Marcacao;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho as DiaTrabalho;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository\DiaTrabalho as DiaTrabalhoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Manutencao\EspelhoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa as JustificativaModel;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Repository\Justificativa as JustificativaRepository;

use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Importacao;
use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro\Cabecalho    as CabecalhoRegistro;
use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Registro\Marcacao     as MarcacaoRegistro;
use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository\Cabecalho  as CabecalhoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Arquivo\Repository\Marcacao   as MarcacaoRepository;

use ECidade\RecursosHumanos\RH\Assentamento\AssentamentoJustificativa;

/**
 * Classe com as informações referentes ao dia de trabalho de um servidor
 * Class ProcessamentoPontoEletronico
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model
 * @author Renan Pigato Silva <renan.silva@dbseller.com.br>
 */
class ProcessamentoPontoEletronico {

  /**
   * Processa as marcações do ponto, calculando as horas extras, falta e demais cálculos necessários
   * @param array $aMatriculas
   * @param Periodo $oPeriodo
   * @param $aDatasProcessar
   * @throws \BusinessException
   */
  public static function processarMatriculas(array $aMatriculas, Periodo $oPeriodo, $aDatasProcessar) {

    $aDatasEfetividade = \DBDate::getDatasNoIntervalo($oPeriodo->getDataInicio(), $oPeriodo->getDataFim());

    foreach ($aMatriculas as $iMatricula) {

      $oServidor = \ServidorRepository::getInstanciaByCodigo($iMatricula);
      $aEscalas  = $oServidor->getEscalas();

      if(empty($aEscalas)) {
        throw new \BusinessException("Não há escalas configuradas para o servidor: {$oServidor->getMatricula()} - {$oServidor->getCgm()->getNome()}");
      }

      foreach ($aDatasEfetividade as $oDataEfetividade) {

        if(!in_array($oDataEfetividade->getDate(), $aDatasProcessar)) {
          continue;
        }

        $oEscalaServidorNaData  = self::getEscalaNaData($aEscalas, $oDataEfetividade);

        if (empty($oEscalaServidorNaData)) {
          continue;
        }

        $oDiaTrabalhoRepository = new DiaTrabalhoRepository();
        $oDiaTrabalhoRepository->setEscalaServidor($oEscalaServidorNaData);
        $oDiaTrabalhoModel      = $oDiaTrabalhoRepository->getDiaTrabalhoServidor($oServidor, $oDataEfetividade);

        $oDiaTrabalhoRepository->verificaHorasExtrasAutorizadas($oDiaTrabalhoModel);
        $oDiaTrabalhoModel->calcularHoras();
        $oDiaTrabalhoModel->setCodigoArquivo($oPeriodo->getCodigoArquivo());

        $oDiaTrabalhoRepository->persist($oDiaTrabalhoModel);
      }
    }
  }


  /**
   * Retorna as datas em que o servidor faltou para gerar assentamentos de faltas de DSR
   * @param \Servidor $oServidor
   * @param Periodo $oPeriodo
   * @throws \BusinessException
   * @return \String
   */
  public static function getDatasFaltas(\Servidor $oServidor, Periodo $oPeriodo) {

    $aDatasEfetividade = \DBDate::getDatasNoIntervalo($oPeriodo->getDataInicio(), $oPeriodo->getDataFim());

    $aEscalas  = $oServidor->getEscalas();

    if(empty($aEscalas)) {
      throw new \BusinessException("Não há escalas configuradas para o servidor: {$oServidor->getMatricula()} - {$oServidor->getCgm()->getNome()}");
    }

    $datasFaltas = array();

    foreach ($aDatasEfetividade as $oDataEfetividade) {

      $oEscalaServidorNaData  = self::getEscalaNaData($aEscalas, $oDataEfetividade);
      $oDiaTrabalhoRepository = new DiaTrabalhoRepository();
      $oDiaTrabalhoRepository->setEscalaServidor($oEscalaServidorNaData);
      $oDiaTrabalhoModel      = $oDiaTrabalhoRepository->getDiaTrabalhoServidor($oServidor, $oDataEfetividade);

      if($oDiaTrabalhoModel->getJornada()->isDiaTrabalhado()) {

        if($oDiaTrabalhoModel->getMarcacoes()->isEmpty()) {
          $datasFaltas[] = $oDiaTrabalhoModel->getData()->getDate();
        }
      }
    }

    return $datasFaltas;
  }

  /**
   * Retorna a instância de EscalaServidor conforme a data da efetividade verificada
   * @param $aEscalas
   * @param \DBDate $oDataEfetividade
   * @return EscalaServidor|null
   */
  public static function getEscalaNaData($aEscalas, \DBDate $oDataEfetividade) {

    foreach($aEscalas as $oEscala) {

      $oIntervaloDatas = \DBDate::getIntervaloEntreDatas($oEscala->getDataEscala(), $oDataEfetividade);

      if(!$oIntervaloDatas->invert && $oIntervaloDatas->days >= 0) {

        if($oEscala->getEscalaPosterior() == null) {
          return $oEscala;
        }

        $oData = clone $oEscala->getEscalaPosterior()->getDataEscala();
        $oData->modificarIntervalo('-1 day');

        if(   $oEscala->getEscalaPosterior() instanceof EscalaServidor
          && \DBDate::dataEstaNoIntervalo($oDataEfetividade, $oEscala->getDataEscala(), $oData)
        ) {
          return $oEscala;
        }
      }
    }

    return null;
  }

  /**
   * cria as Marcacoes do servidor em uma data ou periodo especifico
   * @param $iMatricula
   * @param $aDatas
   * @param bool $aHorarios
   * @param bool $lSobrescrever
   * @throws \ParameterException
   */
  static function criarMarcacoesNasDatas($iMatricula, $aDatas, $aHorarios = false, $lSobrescrever = false) {

    if(empty($aDatas)) {
      throw new \ParameterException("Não foram informadas as datas a processar.");
    }

    if(empty($iMatricula)) {
      throw new \ParameterException("Informe a matrícula do servidor.");
    }

    $oServidor = \ServidorRepository::getInstanciaByCodigo($iMatricula);
    $aEscalas  = $oServidor->getEscalas();

    if(empty($aEscalas)){

      $mensagem  = "Servidor (Matrícula: {$iMatricula}) não possui escala configurada.";
      $mensagem .= " Para configurar acesse:\nRH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários";
      throw new \ParameterException($mensagem, 4);
    }

    $primeiraData = $aDatas[0]->data;
    $ultimaData   = $aDatas[count($aDatas)-1]->data;

    $oPeriodoRepository = new PeriodoRepository(null, null, true);
    $aPeriodos          = $oPeriodoRepository->getPeriodosEntreDatas(new \DBDate($primeiraData), new \DBDate($ultimaData));
    $aCodigosArquivos   = array();

    foreach ($aPeriodos as $oPeriodo) {

      $oCabecalhoRepository = new CabecalhoRepository();
      $oCabecalhoRegistro   = $oCabecalhoRepository->add(new CabecalhoRegistro(), $oPeriodo);

      $iCodigoArquivo     = $oCabecalhoRegistro->getCodigo();
      $aCodigosArquivos[$oPeriodo->getExercicio().$oPeriodo->getCompetencia()] = $iCodigoArquivo;
    }

    foreach ($aDatas as $data) {

      $oData = new \DBDate($data->data);

      $oCabecalhoRegistro = new CabecalhoRegistro();
      $oCabecalhoRegistro->setCodigo(self::getCodigoArquivoPorPeriodosEData($aCodigosArquivos, $aPeriodos, $oData));

      $oEscalaServidorNaData  = self::getEscalaNaData($aEscalas, $oData);

      if (empty($oEscalaServidorNaData)) {

        $mensagem  = "Servidor não possui escala na data.";
        $mensagem .= " Para configurar acesse:\nRH > Procedimentos > Efetividade > Manutenção da Escala de Funcionários";
        throw new \ParameterException($mensagem, 5);
      }

      $oDiaTrabalhoRepository = new DiaTrabalhoRepository();
      $oDiaTrabalhoRepository->setEscalaServidor($oEscalaServidorNaData);
      $oDiaTrabalhoModel      = $oDiaTrabalhoRepository->getDiaTrabalhoServidor($oServidor, $oData);
      $oDiaTrabalhoModel->setCodigoArquivo($oCabecalhoRegistro->getCodigo());
      $oDiaTrabalhoModel->setAfastamento(null);
      $oAfastamentoNaData = null;
      $aAfastamentos      = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($oServidor, 'A', $oData);

      if(!empty($aAfastamentos)){

        $oAfastamentoNaData = $aAfastamentos[0];
        $oDiaTrabalhoModel->setAfastado(true);
        $oDiaTrabalhoModel->setAfastamento($oAfastamentoNaData);
      }

      $oDiaTrabalhoRepository->persist($oDiaTrabalhoModel);

      $oDiaTrabalhoModel = $oDiaTrabalhoRepository->getDiaTrabalhoServidor($oServidor, $oData);
      $iCodigoData       = $oDiaTrabalhoRepository->getCodigoData($oDiaTrabalhoModel);

      if($iCodigoData != null) {
        self::removerJustificativaNaData($iCodigoData);
      }

      for ($iMarcacoes=1; $iMarcacoes <= 6; $iMarcacoes++) {

        $oMarcacao = new MarcacaoRegistro();
        $oMarcacao->setData($oData);

        $sHora   = !empty($aHorarios[$iMarcacoes-1]) ? $aHorarios[$iMarcacoes-1] : '';
        $lManual = !empty($aHorarios[$iMarcacoes-1]) ? true : false;

        if(count($oDiaTrabalhoModel->getMarcacoes()->getMarcacoes()) > 0 && $iCodigoData != null) {

          if($oDiaTrabalhoModel->getMarcacoes()->getMarcacao($iMarcacoes) != null) {

            $oMarcacaoDia = $oDiaTrabalhoModel->getMarcacoes()->getMarcacao($iMarcacoes);
            $lManual      = empty($aHorarios[$iMarcacoes-1]) ? $oMarcacaoDia->isManual() : $aHorarios[$iMarcacoes-1];

            if($oMarcacaoDia->getMarcacao() != null || $oMarcacaoDia->getCodigo() != null) {

              $oMarcacao->setCodigo($oMarcacaoDia->getCodigo());
              $oMarcacao->setData($oMarcacaoDia->getData());
            }

            if($oMarcacaoDia->getMarcacao() != null) {

              if(!$lSobrescrever) {

                $sHora   = $oMarcacaoDia->getMarcacao()->format('H:i');
                $lManual = $oMarcacaoDia->isManual();
              }

              if($lSobrescrever && empty($aHorarios[$iMarcacoes-1])) {

                $sHora   = $oMarcacaoDia->getMarcacao()->format('H:i');
                $lManual = $oMarcacaoDia->isManual();
              }
            }
          }
        }

        $oMarcacao->setHora($sHora);
        $oMarcacao->setManual($lManual);

        if(!$oServidor->registraPontoEletronico() && $oDiaTrabalhoModel->getMarcacoes()->isEmpty()) {
          self::criarMarcacoesComBaseNaJornada($oDiaTrabalhoModel, $oMarcacao, $iMarcacoes);
        }

        $oMarcacao->setDataVinculo($oData);
        $oMarcacao->setPIS($oServidor->getPISPASEP());
        $oMarcacao->setMatricula($oServidor->getMatricula());
        $oMarcacao->setCabecalho($oCabecalhoRegistro);

        $oMarcacaoRepository = new MarcacaoRepository();
        $oMarcacaoRepository->setOrdem($iMarcacoes);
        $oMarcacao = $oMarcacaoRepository->add($oMarcacao);

        $aAssentamentosNaData = $aAfastamentos = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza(
          \ServidorRepository::getInstanciaByCodigo($oServidor->getMatricula()), 'S', $oData
        );

        self::vincularJustificativa($oMarcacao, $aAssentamentosNaData, $iMarcacoes);

        if(!$oServidor->registraPontoEletronico()) {
          $oMarcacaoRepository->add($oMarcacao);
        }
      }
    }
  }

  /**
   * @param MarcacaoRegistro $oMarcacao
   * @param \Assentamento[] $aAssentamentosNaData
   * @param $iOrdem
   */
  static function vincularJustificativa(&$oMarcacao, $aAssentamentosNaData, $iOrdem) {

    if(!is_array($aAssentamentosNaData) || count($aAssentamentosNaData) == 0) {
      return;
    }

    foreach($aAssentamentosNaData as $oAssentamentoNaData ) {

      $oJustificativaRepository = new JustificativaRepository();
      $oJustificativaModel      = $oJustificativaRepository->getJustificativaPorTipoAssentamento($oAssentamentoNaData->getTipoAssentamento());

      if($oJustificativaModel != null) {

        if($oAssentamentoNaData->isTotal()) {

          self::salvarJustificativaMarcacao($oJustificativaModel, $oMarcacao->getCodigo(), 'T');

          if(!$oAssentamentoNaData->getServidor()->registraPontoEletronico() && !$oMarcacao->isManual()) {
            $oMarcacao->setHora('');
          }
        }

        if(!$oAssentamentoNaData->isTotal()) {

          if(in_array($iOrdem, array(1,2)) && $oAssentamentoNaData->getPeriodo1() != null) {

            self::salvarJustificativaMarcacao($oJustificativaModel, $oMarcacao->getCodigo());

            if(!$oAssentamentoNaData->getServidor()->registraPontoEletronico() && !$oMarcacao->isManual()) {
              $oMarcacao->setHora('');
            }
          }

          if(in_array($iOrdem, array(3,4)) && $oAssentamentoNaData->getPeriodo2() != null) {

            self::salvarJustificativaMarcacao($oJustificativaModel, $oMarcacao->getCodigo());

            if(!$oAssentamentoNaData->getServidor()->registraPontoEletronico() && !$oMarcacao->isManual()) {
              $oMarcacao->setHora('');
            }
          }

          if(in_array($iOrdem, array(5,6)) && $oAssentamentoNaData->getPeriodo3() != null) {

            self::salvarJustificativaMarcacao($oJustificativaModel, $oMarcacao->getCodigo());

            if(!$oAssentamentoNaData->getServidor()->registraPontoEletronico()) {
              $oMarcacao->setHora('');
            }
          }
        }
      }
    }
  }

  /**
   * @param $oServidor
   * @param $sDataProcessar
   * @param $aMarcacoes
   * @param $oDiaTrabalho
   */
  static function salvarMarcacaoEVincularJustificativa(\Servidor $oServidor, $sDataProcessar, $aMarcacoes, $oDiaTrabalho){

    $aAssentamentosNaData = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza($oServidor, 'S', new \DBDate($sDataProcessar), \Assentamento::NATUREZA_JUSTIFICATIVA);

    self::removerJustificativaNaData($oDiaTrabalho->getCodigo());

    $iOrdem     = 1;
    $sDataMaior = $sDataProcessar;

    foreach ($aMarcacoes as $oStdMarcacao) {

      $oDataMaior    = new \DBDate($sDataMaior);
      $oDataMarcacao = new \DBDate($oStdMarcacao->data);

      if(!empty($oStdMarcacao->data) && $oDataMaior->getTimeStamp() < $oDataMarcacao->getTimeStamp()) {
        $sDataMaior = $oStdMarcacao->data;
      }

      $oCabecalhoRegistro = new CabecalhoRegistro();
      $oCabecalhoRegistro->setCodigo($oDiaTrabalho->getCodigo());

      $oMarcacao = new MarcacaoRegistro();
      $oMarcacao->setCodigo($oStdMarcacao->codigo);
      $oMarcacao->setHora($oStdMarcacao->hora);
      $oMarcacao->setManual($oStdMarcacao->alterado);
      $oMarcacao->setDataVinculo(new \DBDate($sDataProcessar));
      $oMarcacao->setData(new \DBDate($sDataMaior));
      $oMarcacao->setPIS($oServidor->getPISPASEP());
      $oMarcacao->setMatricula($oServidor->getMatricula());
      $oMarcacao->setServidor($oServidor);
      $oMarcacao->setCabecalho($oCabecalhoRegistro);

      $oMarcacaoRepository = new MarcacaoRepository();
      $oMarcacaoRepository->setOrdem($iOrdem);
      $oMarcacaoRepository->add($oMarcacao);

      self::vincularJustificativa($oMarcacao, $aAssentamentosNaData, $iOrdem);

      if(!$oServidor->registraPontoEletronico() && $oDiaTrabalho->getMarcacoes()->isEmpty()) {

        ProcessamentoPontoEletronico::criarMarcacoesComBaseNaJornada($oDiaTrabalho, $oMarcacao, $iOrdem);
        $oMarcacaoRepository->add($oMarcacao, false);
      }

      $iOrdem++;
    }
  }

  /**
   * @param $iCodigoData
   * @throws \DBException
   */
  static function removerJustificativaNaData($iCodigoData) {

    $oDaoRegistroJustificativa = new \cl_pontoeletronicoregistrojustificativa();
    $oDaoPontoData             = new \cl_pontoeletronicoarquivodataregistro();

    $sSqlPontoData = $oDaoPontoData->sql_query(null, 'rh198_sequencial', null, "rh197_sequencial = {$iCodigoData}");
    $rsPontoData   = db_query($sSqlPontoData);

    if(!$rsPontoData) {
      throw new \DBException('Erro ao buscar os registros do dia.');
    }

    if(pg_num_rows($rsPontoData) > 0) {

      $iTotalRegistros   = pg_num_rows($rsPontoData);
      $aRegistrosExcluir = \db_utils::makeCollectionFromRecord($rsPontoData, function($oRetorno) {
        return $oRetorno->rh198_sequencial;
      });

      $oDaoRegistroJustificativa->excluir(
        null,
        "rh199_pontoeletronicoarquivodataregistro IN (". implode(', ', $aRegistrosExcluir) .")"
      );

      if($oDaoRegistroJustificativa->erro_status == '0') {
        throw new \DBException($oDaoRegistroJustificativa->erro_msg);
      }
    }
  }

  /**
   * @param JustificativaModel $oJustificativaModel
   * @param $iMarcacao
   * @param string $sTipo
   * @throws \DBException
   */
  static function salvarJustificativaMarcacao(JustificativaModel $oJustificativaModel, $iMarcacao, $sTipo = 'P') {

    $oDaoRegistroJustificativa = new \cl_pontoeletronicoregistrojustificativa();

    $oDaoRegistroJustificativa->rh199_sequencial                         = null;
    $oDaoRegistroJustificativa->rh199_pontoeletronicoarquivodataregistro = $iMarcacao;
    $oDaoRegistroJustificativa->rh199_pontoeletronicojustificativa       = $oJustificativaModel->getCodigo();
    $oDaoRegistroJustificativa->rh199_tipo                               = $sTipo;

    $oDaoRegistroJustificativa->incluir($oDaoRegistroJustificativa->rh199_sequencial);

    if($oDaoRegistroJustificativa->erro_status == '0') {
      throw new \DBException($oDaoRegistroJustificativa->erro_msg);
    }
  }

  /**
   * @param array $aCodigosArquivos
   * @param array $aPeriodos
   * @param \DBDate $data
   * @return mixed|null
   */
  static function getCodigoArquivoPorPeriodosEData(array $aCodigosArquivos, array $aPeriodos, \DBDate $data) {

    foreach ($aPeriodos as $oPeriodo) {
      if(\DBDate::dataEstaNoIntervalo($data, $oPeriodo->getDataInicio(), $oPeriodo->getDataFim())) {
        return isset($aCodigosArquivos[$oPeriodo->getExercicio().$oPeriodo->getCompetencia()]) ? $aCodigosArquivos[$oPeriodo->getExercicio().$oPeriodo->getCompetencia()] : null;
      }
    }

    return null;
  }

  /**
   * @param \ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho $oDiaTrabalhoModel
   * @param MarcacaoRegistro $oMarcacao
   * @param $iMarcacoes
   */
  static function criarMarcacoesComBaseNaJornada(DiaTrabalho $oDiaTrabalhoModel, MarcacaoRegistro &$oMarcacao, $iMarcacoes) {

    $aHorasJornada = $oDiaTrabalhoModel->getJornada()->getHoras();
    $oMarcacaoDia  = $oDiaTrabalhoModel->getMarcacoes()->getMarcacao($iMarcacoes);

    if(    ProcessamentoPontoEletronico::validaDiaTrabalhoCriarMarcacoes($oDiaTrabalhoModel)
        && isset($aHorasJornada[$iMarcacoes-1])
        && ProcessamentoPontoEletronico::validaMarcacaoDiaCriarMarcacoes($oMarcacaoDia)) {

      $oMarcacao->setHora($aHorasJornada[$iMarcacoes-1]->oHora->format('H:i'));

      /**
       * Quando a jornada começa em um dia e termina no outro, é necessário atualizar também a data
       */
      $intervaloEntreData = \DBDate::getIntervaloEntreDatas(
        $oDiaTrabalhoModel->getData(),
        new \DBDate($aHorasJornada[$iMarcacoes-1]->oHora->format('Y-m-d'))
      );

      if($intervaloEntreData->days > 0) {
        $oMarcacao->setData(new \DBDate($aHorasJornada[$iMarcacoes-1]->oHora->format('Y-m-d')));
      }
    }
  }

  /**
   * @param \ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho $oDiaTrabalhoModel
   * @return bool
   */
  static function validaDiaTrabalhoCriarMarcacoes(DiaTrabalho $oDiaTrabalhoModel) {

    if(    $oDiaTrabalhoModel->getJornada()->isDiaTrabalhado()
      && !$oDiaTrabalhoModel->isAfastado()) {
      return true;
    }

    return false;
  }

  /**
   * @param $oMarcacaoDia
   * @return bool
   */
  static function validaMarcacaoDiaCriarMarcacoes($oMarcacaoDia) {

    if($oMarcacaoDia == null) {
      return true;
    }

    if(     $oMarcacaoDia != null
      && !$oMarcacaoDia->isManual()
      && $oMarcacaoDia->getJustificativa() == null) {
      return true;
    }

    return false;
  }
}
