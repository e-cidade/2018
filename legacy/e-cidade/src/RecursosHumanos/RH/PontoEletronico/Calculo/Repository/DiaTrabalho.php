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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository;

use ECidade\Configuracao\Cadastro\Model\Feriado;
use ECidade\Configuracao\Cadastro\Repository\Feriado as FeriadoRepository;
use ECidade\RecursosHumanos\RH\Efetividade\Model\EscalaServidor;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Factory\TipoHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho as DiaTrabalhoModel;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\Falta;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosGerais;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\MarcacoesPontoCollection;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\ParametrosRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao\Repository\MarcacaoPonto;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Repository\Justificativa as JustificativaRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Repository\Evento as EventoRepository;

/**
 * Classe responsável pelas ações na base de dados
 * Class DiaTrabalho
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Repository
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class DiaTrabalho {

  /**
   * @var EscalaServidor
   */
  private $oEscalaServidor;

  /**
   * @var bool
   */
  private $lBuscaJustificativaMarcacoes = false;

  /**
   * DiaTrabalho constructor.
   */
  public function __construct() {
  }

  /**
   * @param EscalaServidor $oEscalaServidor
   */
  public function setEscalaServidor(EscalaServidor $oEscalaServidor) {
    $this->oEscalaServidor = $oEscalaServidor;
  }

  /**
   * @param bool $lBuscaJustificativaMarcacoes
   */
  public function setBuscaJustificativaMarcacoes($lBuscaJustificativaMarcacoes) {
    $this->lBuscaJustificativaMarcacoes = $lBuscaJustificativaMarcacoes;
  }

  /**
   * @param \Servidor $oServidor
   * @param \DBDate $oDataPonto
   * @return DiaTrabalhoModel
   * @throws \BusinessException
   * @throws \DBException
   */
  public function getDiaTrabalhoServidor(\Servidor $oServidor, \DBDate $oDataPonto) {
    return $this->getDiaTrabalho($oServidor, $oDataPonto);
  }

  /**
   * @param \Servidor $oServidor
   * @param \DBDate $oDataPonto
   * @return DiaTrabalhoModel
   * @throws \BusinessException
   * @throws \DBException
   */
  private function getDiaTrabalho(\Servidor $oServidor, \DBDate $oDataPonto, $lHorasProcessadas = false) {

    $oCollectionMarcacoes = new MarcacoesPontoCollection;

    if(empty($this->oEscalaServidor)) {
      throw new \BusinessException("Não há escalas para o servidor: {$oServidor->getMatricula()}\n\nConfigura uma escala em RH > Cadastros > Efetividade > Escala de Trabalho", 1);
    }

    $oDiaTrabalho = new DiaTrabalhoModel();
    $oDiaTrabalho->setServidor($oServidor);
    $oDiaTrabalho->setData($oDataPonto);

    $oFeriadoRepository = new FeriadoRepository($oServidor->getInstituicao(), $oServidor->getCodigoLotacao());
    $oFeriadoModel      = $oFeriadoRepository->getFeriadoNaData($oDataPonto);

    if($oFeriadoModel instanceof Feriado) {
      $oDiaTrabalho->setFeriado($oFeriadoModel);
    }

    $oJornada = $this->getJornada($oDiaTrabalho, $this->oEscalaServidor);
    $oJornada->ajustarDatasJornada(clone $oDiaTrabalho->getData());
    $aHoras   = $oJornada->getHoras();

    if(!empty($aHoras)) {

      $oMarcacaoPontoRepository = new MarcacaoPonto();
      $oMarcacaoPontoRepository->setJornada($oJornada);
      $oMarcacaoPontoRepository->setBuscaJustificativa($this->lBuscaJustificativaMarcacoes);

      $oCollectionRetornada = $oMarcacaoPontoRepository->getCollectionMarcacaoPonto($oDiaTrabalho);

      if($oCollectionRetornada instanceof MarcacoesPontoCollection) {
        $oCollectionMarcacoes = $oCollectionRetornada;
      }
    }

    if($lHorasProcessadas) {
      $oDiaTrabalho = $this->getHorasProcessadas($oDiaTrabalho);
    }

    $oDiaTrabalho->setConfiguracoesLotacao($this->getConfiguracoes($oServidor));
    $oDiaTrabalho->setMarcacoes($oCollectionMarcacoes);
    $oDiaTrabalho->setJornada($oJornada);
    $oDiaTrabalho->setCodigo($this->getCodigoData($oDiaTrabalho));
    $oDiaTrabalho->setAfastamento($this->getAfastamentoNaData($oDiaTrabalho));
    
    $evento = EventoRepository::getInstance()->possuiEventoNoDiaParaServidor($oDataPonto, $oServidor);
    if(!empty($evento)) {
      $oDiaTrabalho->setEvento($evento);
    }
    
    $aAssentamentosHoraExtraManual = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza(
      $oServidor,
      'S',
      $oDataPonto,
      \Assentamento::NATUREZA_HE_MANUAL
    );
    if(!empty($aAssentamentosHoraExtraManual)) {
      $oDiaTrabalho->setAssentamentosHoraExtraManual($aAssentamentosHoraExtraManual);
    }

    return $oDiaTrabalho;
  }

  /**
   * @param DiaTrabalhoModel $oDiaTrabalho
   * @param EscalaServidor $oEscalaServidor
   * @return \ECidade\RecursosHumanos\RH\Efetividade\Model\Jornada
   * @throws \BusinessException
   * @throws \DBException
   */
  private function getJornada(DiaTrabalhoModel $oDiaTrabalho, EscalaServidor $oEscalaServidor) {

    $sSqlOrdem = "select (('{$oDiaTrabalho->getData()->getDate()}' - '{$oEscalaServidor->getEscalaTrabalho()->getDataBase()->getDate()}'::date) ";
    $sSqlOrdem .= "    % (select max(rh191_ordemhorario) ";
    $sSqlOrdem .= "            from gradeshorariosjornada ";
    $sSqlOrdem .= "           where rh191_gradehorarios = {$oEscalaServidor->getEscalaTrabalho()->getCodigo()}) + 1) as ordem ";
    $sSqlOrdem .= "  from ( select (select rh192_sequencial ";
    $sSqlOrdem .= "                   from escalaservidor ";
    $sSqlOrdem .= "                  where rh192_regist = {$oDiaTrabalho->getServidor()->getMatricula()} ";
    $sSqlOrdem .= "    and rh192_dataescala <= '{$oDiaTrabalho->getData()->getDate()}' ";
    $sSqlOrdem .= "                  order by rh192_dataescala desc limit 1) as codigo_escala, ";
    $sSqlOrdem .= "                 '{$oDiaTrabalho->getData()->getDate()}' as data ) as escalasperiodo ";
    $rsOrdem    = db_query($sSqlOrdem);

    if(!$rsOrdem) {
      throw new \DBException("Erro ao buscar a ordem da jornada.");
    }

    if(pg_num_rows($rsOrdem) == 0) {
      throw new \BusinessException("Ordem da grade de horário não encontrada.");
    }

    $iOrdem = \db_utils::fieldsMemory($rsOrdem, 0)->ordem;

    if ($iOrdem == 0) {
      throw new \BusinessException("Verifique a data base da escala de trabalho.");
    }

    $aJornadas = $oEscalaServidor->getEscalaTrabalho()->getJornadas();

    return $aJornadas[$iOrdem];
  }

  /**
   * Persiste na base de dados um dia de trabalho com usas horas calculadas
   * @param DiaTrabalhoModel $oDiaTrabalho
   * @throws \BusinessException
   * @throws \DBException
   */
  public function persist(DiaTrabalhoModel $oDiaTrabalho) {

    $oDaoPontoEletronicoData = new \cl_pontoeletronicoarquivodata();
    $sWherePontoEletronicoData      = "     rh197_data      = '{$oDiaTrabalho->getData()->getDate()}'";
    $sWherePontoEletronicoData     .= " AND rh197_matricula = {$oDiaTrabalho->getServidor()->getMatricula()}";
    $sSqlPontoEletronicoData        = $oDaoPontoEletronicoData->sql_query_file(
      null,
      '*',
      null,
      $sWherePontoEletronicoData
    );

    $rsPontoEletronicoData = db_query($sSqlPontoEletronicoData);

    if(!$rsPontoEletronicoData) {
      throw new \DBException('Erro ao buscar as informações do ponto no dia.');
    }

    $oDadosRetorno                                = new \stdClass();
    $oDadosRetorno->rh197_pontoeletronicoarquivo  = $oDiaTrabalho->getCodigoArquivo();
    $oDadosRetorno->rh197_data                    = $oDiaTrabalho->getData()->getDate();
    $oDadosRetorno->rh197_matricula               = $oDiaTrabalho->getServidor()->getMatricula();
    $oDadosRetorno->rh197_pis                     = $oDiaTrabalho->getServidor()->getDocumentos()->sPIS;
    $oDadosRetorno->rh197_sequencial              = null;

    if(pg_num_rows($rsPontoEletronicoData) > 0) {
      $oDadosRetorno = \db_utils::fieldsMemory($rsPontoEletronicoData, 0);
    }

    $sAcao = empty($oDadosRetorno->rh197_sequencial) ? 'incluir' : 'alterar';

    $oDaoPontoEletronicoData->rh197_pontoeletronicoarquivo = $oDadosRetorno->rh197_pontoeletronicoarquivo;
    $oDaoPontoEletronicoData->rh197_data                   = $oDadosRetorno->rh197_data;
    $oDaoPontoEletronicoData->rh197_matricula              = $oDadosRetorno->rh197_matricula;
    $oDaoPontoEletronicoData->rh197_pis                    = $oDadosRetorno->rh197_pis;
    $oDaoPontoEletronicoData->rh197_horas_trabalhadas      = $oDiaTrabalho->getHorasTrabalho();
    $oDaoPontoEletronicoData->rh197_horas_falta            = $oDiaTrabalho->getHorasFalta();
    $oDaoPontoEletronicoData->rh197_horas_extras_50_d      = $oDiaTrabalho->getHorasExtra50();
    $oDaoPontoEletronicoData->rh197_horas_extras_75_d      = $oDiaTrabalho->getHorasExtra75();
    $oDaoPontoEletronicoData->rh197_horas_extras_100_d     = $oDiaTrabalho->getHorasExtra100();
    $oDaoPontoEletronicoData->rh197_horas_extras_50_n      = $oDiaTrabalho->getHorasExtra50Noturna();
    $oDaoPontoEletronicoData->rh197_horas_extras_75_n      = $oDiaTrabalho->getHorasExtra75Noturna();
    $oDaoPontoEletronicoData->rh197_horas_extras_100_n     = $oDiaTrabalho->getHorasExtra100Noturna();
    $oDaoPontoEletronicoData->rh197_horas_adicinal_noturno = $oDiaTrabalho->getHorasAdicionalNoturno();
    $oDaoPontoEletronicoData->rh197_sequencial             = $oDadosRetorno->rh197_sequencial;
    $oDaoPontoEletronicoData->rh197_afastamento            = "null";
    if ($oDiaTrabalho->getAfastamento() != '') {
      $oDaoPontoEletronicoData->rh197_afastamento = $oDiaTrabalho->getAfastamento()->getCodigo();
    }
    $oDaoPontoEletronicoData->{$sAcao}($oDadosRetorno->rh197_sequencial);

    if($oDaoPontoEletronicoData->erro_status == '0') {
      throw new \DBException($oDaoPontoEletronicoData->erro_msg);
    }
  }

  /**
   * @param \Servidor $oServidor
   * @param \DBDate $oDataPonto
   * @return DiaTrabalhoModel
   * @throws \BusinessException
   * @throws \DBException
   */
  public function getDiaTrabalhoProcessadoServidor(\Servidor $oServidor, \DBDate $oDataPonto) {
    return $this->getDiaTrabalho($oServidor, $oDataPonto, true);
  }

  /**
   * Retorna o sequencial da data
   * @param DiaTrabalhoModel $oDiaTrabalho
   * @return mixed
   * @throws \BusinessException
   * @throws \DBException
   */
  public function getCodigoData(DiaTrabalhoModel $oDiaTrabalho) {

    $oDaoPontoEletronicoArquivoData = new \cl_pontoeletronicoarquivodata();
    $sSqlPontoEletronicoArquivoData = $oDaoPontoEletronicoArquivoData->sql_query_file(
      null,
      'rh197_sequencial',
      null,
      "rh197_data = '{$oDiaTrabalho->getData()->getDate()}' AND rh197_matricula = {$oDiaTrabalho->getServidor()->getMatricula()}"
    );
    $rsPontoEletronicoArquivoData = db_query($sSqlPontoEletronicoArquivoData);

    if(!$rsPontoEletronicoArquivoData) {
      throw new \DBException('Erro ao buscar o código referente a data.');
    }

    if(pg_num_rows($rsPontoEletronicoArquivoData) == 0) {
      return null;
    }

    return \db_utils::fieldsMemory($rsPontoEletronicoArquivoData, 0)->rh197_sequencial;
  }

  /**
   * @param \Servidor $oServidor
   * @return mixed|null
   * @throws \BusinessException
   */
  private function getConfiguracoes(\Servidor $oServidor) {

    $iCodigoLotacaoServidor = $oServidor->getCodigoLotacao();

    if(empty($iCodigoLotacaoServidor)) {

      $mensagemLotacao  = "Não há lotação configurada para o servidor: {$oServidor->getMatricula()} - {$oServidor->getCgm()->getNome()}.";
      $mensagemLotacao .= " Para configurar acesse:\nPessoal > Cadastro > Servidores > aba Movimentações.";
      throw new \BusinessException($mensagemLotacao, 2);
    }

    $oConfiguracoesLotacao = ParametrosRepository::create()->getConfiguracoesLotacao($iCodigoLotacaoServidor);

    if(empty($oConfiguracoesLotacao)) {

      $mensagemLotacao  = "A lotação ({$oServidor->getCodigoLotacao()}) do servidor: {$oServidor->getMatricula()} - {$oServidor->getCgm()->getNome()} não está configurada.\n\n";
      $mensagemLotacao .= "Para configurar acesse:\nRH > Procedimentos > Ponto Eletrônico > Configurações > Lotação";
      throw new \BusinessException($mensagemLotacao, 3);
    }

    return $oConfiguracoesLotacao;
  }

  /**
   * @param DiaTrabalhoModel $oDiaTrabalho
   * @return DiaTrabalhoModel
   * @throws \DBException
   */
  private function getHorasProcessadas(DiaTrabalhoModel $oDiaTrabalho, $lApenasHorasCalculadasPorServidorNaData = false) {

    $oDaoArquidoData            = new \cl_pontoeletronicoarquivodata();

    $aCamposHorasConsolidadas   = array();
    $aCamposHorasConsolidadas[] = 'rh197_horas_trabalhadas';
    $aCamposHorasConsolidadas[] = 'rh197_horas_falta';
    $aCamposHorasConsolidadas[] = 'rh197_horas_extras_50_d';
    $aCamposHorasConsolidadas[] = 'rh197_horas_extras_75_d';
    $aCamposHorasConsolidadas[] = 'rh197_horas_extras_100_d';
    $aCamposHorasConsolidadas[] = 'rh197_horas_extras_50_n';
    $aCamposHorasConsolidadas[] = 'rh197_horas_extras_75_n';
    $aCamposHorasConsolidadas[] = 'rh197_horas_extras_100_n';
    $aCamposHorasConsolidadas[] = 'rh197_horas_adicinal_noturno as rh197_horas_adicional_noturno';
    
    if($lApenasHorasCalculadasPorServidorNaData === false) {
      $aCamposHorasConsolidadas[] = 'h12_codigo';
    }

    $sSqlHorasConsolidadas  = $oDaoArquidoData->sql_query_com_assentamento_tipo_assentamento(
      null,
      implode(' , ', $aCamposHorasConsolidadas),
      null,
      "      rh197_matricula = {$oDiaTrabalho->getServidor()->getMatricula()}
         and rh197_data = '".$oDiaTrabalho->getData()->getDate()."'");

    if($lApenasHorasCalculadasPorServidorNaData) {

      $sSqlHorasConsolidadas  = $oDaoArquidoData->sql_query_file(
        null,
        implode(' , ', $aCamposHorasConsolidadas),
        null,
        "      rh197_matricula = {$oDiaTrabalho->getServidor()->getMatricula()}
           and rh197_data = '".$oDiaTrabalho->getData()->getDate()."'");
    }

    $rsHorasConsolidadas    = db_query($sSqlHorasConsolidadas);

    if(!$rsHorasConsolidadas) {
      throw new \DBException("Ocorreu um erro ao buscar os totais consolidados das horas.\nContate o suporte.\n\n". pg_last_error());
    }

    if(pg_num_rows($rsHorasConsolidadas) > 0) {

      \db_utils::makeFromRecord($rsHorasConsolidadas, function($oRetorno) use ($oDiaTrabalho) {

        $oDiaTrabalho->setHorasTrabalho($oRetorno->rh197_horas_trabalhadas);
        $oDiaTrabalho->setHorasFalta($oRetorno->rh197_horas_falta);
        $oDiaTrabalho->setHorasExtra50($oRetorno->rh197_horas_extras_50_d);
        $oDiaTrabalho->setHorasExtra75($oRetorno->rh197_horas_extras_75_d);
        $oDiaTrabalho->setHorasExtra100($oRetorno->rh197_horas_extras_100_d);
        $oDiaTrabalho->setHorasExtra50Noturna($oRetorno->rh197_horas_extras_50_n);
        $oDiaTrabalho->setHorasExtra75Noturna($oRetorno->rh197_horas_extras_75_n);
        $oDiaTrabalho->setHorasExtra100Noturna($oRetorno->rh197_horas_extras_100_n);
        $oDiaTrabalho->setHorasAdicionalNoturno($oRetorno->rh197_horas_adicional_noturno);

        if(!empty($oRetorno->h12_codigo)) {

          $oJustificativaRepository = new JustificativaRepository();

          $oDiaTrabalho->setAfastado(true);
          $oDiaTrabalho->setJustificativaAfastamento($oJustificativaRepository->getJustificativaPorTipoAssentamentoAfastamento($oRetorno->h12_codigo));
        }
      }, 0);
    }

    return $oDiaTrabalho;
  }

  /**
   * Retorna a instancia do afastamento
   * @param \ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model\DiaTrabalho $diaTrabalho
   * @return \Afastamento|null
   * @throws \DBException
   */
  public function getAfastamentoNaData(DiaTrabalhoModel $diaTrabalho) {
   
    if ($diaTrabalho->getCodigo() == '') {
      return null;
    }
    $oDaoPontoEletronicoArquivoData = new \cl_pontoeletronicoarquivodata();
    $sSqlPontoEletronicoArquivoData = $oDaoPontoEletronicoArquivoData->sql_query_file(
      null,
      '*',
      null,
      "rh197_sequencial = {$diaTrabalho->getCodigo()} and rh197_afastamento is not null"
    );
    $rsPontoEletronicoArquivoData = db_query($sSqlPontoEletronicoArquivoData);

    if(!$rsPontoEletronicoArquivoData) {
      throw new \DBException('Erro ao buscar dados do assentamento referente a data.');
    }

    if(pg_num_rows($rsPontoEletronicoArquivoData) == 0) {
      return null;
    }

    $codigoAfastamento = \db_utils::fieldsMemory($rsPontoEletronicoArquivoData, 0)->rh197_afastamento;
    return \AssentamentoRepository::getInstanceByCodigo($codigoAfastamento); 
  }

  /**
   * @param DiaTrabalhoModel $oDiaTrabalhoModel
   */
  public function verificaHorasExtrasAutorizadas(DiaTrabalhoModel $oDiaTrabalhoModel) {

    $oHorasExtrasAutorizadas    = null;
    $oParametrosPontoEletronico = null;

    /**
     * Busca os parâmetros gerais do ponto eletrônico, para verificar se as horas extras são permitidas somente com
     * autorização.
     * Caso sim, verifica se há assentamento 7 - 'Autorização H.E.' no dia, guardando o número de horas extras permitidas
     */
    $oParametrosPontoEletronico = ParametrosRepository::create()->getConfiguracoesGerais(
      $oDiaTrabalhoModel->getServidor()->getInstituicao()->getCodigo()
    );

    if($oParametrosPontoEletronico instanceof ParametrosGerais) {

      $oDiaTrabalhoModel->setParametrosPontoEletronico($oParametrosPontoEletronico);

      $aAssentamentos = \AssentamentoRepository::getAssentamentosServidorPorTipoENatureza(
        $oDiaTrabalhoModel->getServidor(), 'S', $oDiaTrabalhoModel->getData(), 7
      );

      if($oParametrosPontoEletronico->horaExtraSomenteComAutorizacao() && count($aAssentamentos) > 0) {

        $oAssentamentoHora         = new \DateTime($aAssentamentos[0]->getHora());
        $oHorasExtrasAutorizadas   = new \DateTime("{$oDiaTrabalhoModel->getData()->getDate()} 00:00");
        $oHorasExtrasAutorizadas->add(new \DateInterval("PT{$oAssentamentoHora->format('H')}H{$oAssentamentoHora->format('i')}M"));

        $oDiaTrabalhoModel->setCalculaHoraExtra(true);
        $oDiaTrabalhoModel->setHorasExtrasAutorizadas($oHorasExtrasAutorizadas);
      }
    }
  }

  /**
   * Retorna as horas 
   *
   * @param \Servidor $oServidor
   * @param \DBDate $oDataPonto
   * @return DiaTrabalhoModel
   * @throws \BusinessException
   * @throws \DBException   
   */
  public function getApenasHorasCalculadasPorServidorNaData(\Servidor $oServidor, \DBDate $oDataPonto) {

    $oDiaTrabalho = new DiaTrabalhoModel();
    $oDiaTrabalho->setServidor($oServidor);
    $oDiaTrabalho->setData($oDataPonto); 
    $oDiaTrabalho = $this->getHorasProcessadas($oDiaTrabalho, true);

    return $oDiaTrabalho;
  }
}