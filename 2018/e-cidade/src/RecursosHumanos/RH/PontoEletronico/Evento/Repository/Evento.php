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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Repository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model\Evento as EventoModel;
use ECidade\RecursosHumanos\RH\PontoEletronico\Validacao\Evento as EventoValidador;

/**
 * Class Evento
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Repository
 */
class Evento extends \BaseClassRepository {

  /**
   * Sobrescreve o atributo da classe pai para
   * manter apenas as referências da classe atual
   */
  protected static $oInstance;

  /**
   * @var \cl_pontoeletronicoevento
   */
  private $daoEvento;

  /**
   * Evento constructor.
   */
  protected function __construct() {
    $this->daoEvento = new \cl_pontoeletronicoevento();
  }

  /**
   * @param $codigo
   * @return EventoModel
   * @throws \DBException
   * @throws \ParameterException
   */
  public function getPorCodigo($codigo) {
    return self::getInstanciaPorCodigo($codigo);
  }

  public function make($codigo) {

    if (empty($codigo)) {
      throw new \ParameterException("Não foi informado o parâmetro Código");
    }

    $buscaEvento    = $this->daoEvento->sql_query_file($codigo);
    $resBuscaEvento = $this->daoEvento->sql_record($buscaEvento);
    if (!$resBuscaEvento) {
      throw new \DBException("Ocorre um erro ao buscar o evento de código {$codigo}.");
    }

    if ($this->daoEvento->numrows == 0) {
      return null;
    }

    $stdEvento = \db_utils::fieldsMemory($resBuscaEvento, 0);
    $evento = new EventoModel();
    $evento->setCodigo($stdEvento->rh207_sequencial);
    $evento->setTitulo($stdEvento->rh207_titulo);
    $evento->setDataInicial(new \DBDate($stdEvento->rh207_datainicial));
    $evento->setDataFinal(new \DBDate($stdEvento->rh207_datafinal));
    $evento->setEntradaUm(new \DateTime($stdEvento->rh207_datafinal .' '. $stdEvento->rh207_entrada_1));

    $oSaidaUm = new \DateTime($stdEvento->rh207_datafinal .' '. $stdEvento->rh207_saida_1);
    if($evento->getEntradaUm()->diff($oSaidaUm)->invert) {
      $oSaidaUm->modify('+1 day');
    }
    $evento->setSaidaUm($oSaidaUm);

    if(!empty($stdEvento->rh207_entrada_2)) {
      $oEntradaDois = new \DateTime($stdEvento->rh207_datafinal .' '. $stdEvento->rh207_entrada_2);
      if($evento->getEntradaUm()->diff($oEntradaDois)->invert) {
        $oEntradaDois->modify('+1 day');
      }

      $evento->setEntradaDois($oEntradaDois);
    }

    if(!empty($stdEvento->rh207_saida_2)) {
      $oSaidaDois = new \DateTime($stdEvento->rh207_datafinal .' '. $stdEvento->rh207_saida_2);
      if($evento->getEntradaUm()->diff($oSaidaDois)->invert) {
        $oSaidaDois->modify('+1 day');
      }

      $evento->setSaidaDois($oSaidaDois);
    }

    $evento->setTipoHoraExtraUm($stdEvento->rh207_horasextras_1);
    $evento->setTipoHoraExtraDois($stdEvento->rh207_horasextras_2);
    $evento->setCodigoInstituicao($stdEvento->rh207_instit);
    return $evento;
  }

  /**
   * @param EventoModel $evento
   * @return bool
   * @throws \DBException|\ParameterException|\BusinessException
   */
  public function salvar(EventoModel $evento) {

    $servidoresIgnorados = $evento->validarServidores();

    $this->daoEvento->rh207_sequencial    = $evento->getCodigo();
    $this->daoEvento->rh207_titulo        = $evento->getTitulo();
    $this->daoEvento->rh207_datainicial   = $evento->getDataInicial()->getDate();
    $this->daoEvento->rh207_datafinal     = $evento->getDataFinal()->getDate();
    $this->daoEvento->rh207_entrada_1     = $evento->getEntradaUm() instanceof \DateTime ? $evento->getEntradaUm()->format('H:i') : null;
    $this->daoEvento->rh207_saida_1       = $evento->getSaidaUm() instanceof \DateTime ? $evento->getSaidaUm()->format('H:i') : null;
    $this->daoEvento->rh207_horasextras_1 = $evento->getTipoHoraExtraUm();
    $this->daoEvento->rh207_entrada_2     = $evento->getEntradaDois() instanceof \DateTime ? $evento->getEntradaDois()->format('H:i') : null;
    $this->daoEvento->rh207_saida_2       = $evento->getSaidaDois() instanceof \DateTime ? $evento->getSaidaDois()->format('H:i') : null;
    $this->daoEvento->rh207_horasextras_2 = $evento->getTipoHoraExtraDois();
    $this->daoEvento->rh207_instit        = $evento->getInstituicao()->getCodigo();
    $this->daoEvento->salvar();

    if ($this->daoEvento->erro_status === "0") {
      throw new \DBException("Ocorreu um erro ao salvar os dados do evento.");
    }
    $evento->setCodigo($this->daoEvento->rh207_sequencial);

    $daoEventoMatricula = new \cl_pontoeletronicoeventomatricula();
    $daoEventoMatricula->excluir(null, "rh208_pontoeletronicoevento = {$evento->getCodigo()}");
    foreach ($evento->getServidores() as $servidor) {

      if (in_array($servidor->getMatricula(), $servidoresIgnorados)) {
        continue;
      }

      $daoEventoMatricula->rh208_sequencial = null;
      $daoEventoMatricula->rh208_pontoeletronicoevento = $evento->getCodigo();
      $daoEventoMatricula->rh208_rhpessoal = $servidor->getMatricula();
      $daoEventoMatricula->salvar();
      if ($daoEventoMatricula->erro_status === "0") {
        throw new \DBException("Ocorreu um erro ao víncular os servidores selecionados no evento.");
      }
    }

    if ( count($servidoresIgnorados) === count($evento->getServidores()) ) {
      throw new \BusinessException('O evento não pode ser salvo. Foram encontradas inconsistências em todos servidores. Deseja imprimí-las?');
    }
    return true;
  }

  /**
   *
   * Verifica se existe evento cadastrado para o servidor em que haja conflito entre as datas do evento
   * @param EventoModel $evento
   * @param \Servidor $servidor
   * @return bool
   * @throws \DBException
   */
  public function existeConflitoEntreDataServidor(EventoModel $evento, \Servidor $servidor) {

    $daoEventoMatricula = new \cl_pontoeletronicoeventomatricula();
    $camposOverlaps  = "(cast(rh207_datainicial as date), cast(rh207_datafinal as date))";
    $camposOverlaps .= " overlaps ";
    $camposOverlaps .= "(cast('{$evento->getDataInicial()->getDate()}' as date), cast('{$evento->getDataFinal()->getDate()}' as date)) as resultado";
    $whereOverlaps   = "     rh208_rhpessoal = {$servidor->getMatricula()}";
    $whereOverlaps  .= " and rh207_instit = {$evento->getInstituicao()->getCodigo()}";
    $codigoEvento = $evento->getCodigo();
    if (!empty($codigoEvento)) {
      $whereOverlaps  .= " and rh207_sequencial <> {$evento->getCodigo()}";
    }
    $buscaPeriodoConflitante = $daoEventoMatricula->sql_query(null, $camposOverlaps, null, $whereOverlaps);
    $resPeriodoConflitante   = db_query($buscaPeriodoConflitante);
    if (!$resPeriodoConflitante) {
      throw new \DBException("Ocorreu um erro ao verificar conflito de datas para o servidor.");
    }

    $totalRegistros = pg_num_rows($resPeriodoConflitante);
    for ($rowConflito = 0; $rowConflito < $totalRegistros; $rowConflito++) {

      if (\db_utils::fieldsMemory($resPeriodoConflitante,  $rowConflito)->resultado == 't') {
        return true;
      }
    }
    return false;
  }

  /**
   * @param EventoModel $evento
   * @return EventoModel
   * @throws \DBException
   */
  public function carregarServidores(EventoModel $evento) {

    if ($evento->getCodigo() === null) {
      return $evento;
    }

    $daoMatricula = new \cl_pontoeletronicoeventomatricula();
    $buscaMatricula = $daoMatricula->sql_query_file(null, 'rh208_rhpessoal', 'rh208_sequencial', 'rh208_pontoeletronicoevento = '.$evento->getCodigo());
    $resBuscaMatriculas = db_query($buscaMatricula);
    if (!$resBuscaMatriculas) {
      throw new \DBException("Ocorreu um erro ao buscar as matrículas vinculadas ao evento.");
    }
    $totalRegistros = pg_num_rows($resBuscaMatriculas);

    for ($rowServidor = 0; $rowServidor < $totalRegistros; $rowServidor++) {

      $evento->adicionarServidor(
        \db_utils::makeFromRecord($resBuscaMatriculas, function ($stdServidor) {
          return \ServidorRepository::getInstanciaByCodigo($stdServidor->rh208_rhpessoal);
        }, $rowServidor)
      );
    }
    return $evento;
  }

  /**
   * Verifica se existe evento para a data desejada
   * @param \DBDate $data
   * @return bool|EventoModel
   * @throws \DBException
   */
  public function possuiEventoNoDia(\DBDate $data) {

    $instituicao = \InstituicaoRepository::getInstituicaoSessao();
    $daoEvento      = new \cl_pontoeletronicoevento();
    $buscaEventoDia = $daoEvento->sql_query_file(null, 'rh207_sequencial', null, "rh207_datainicial = '{$data->getDate()}' and rh207_instit = {$instituicao->getCodigo()}");
    $resBuscaEventoDia = db_query($buscaEventoDia);
    if (!$resBuscaEventoDia) {
      throw new \DBException("Ocorreu um erro ao verificar a existência evento para a data".$data->getDate(\DBDate::DATA_PTBR).".");
    }

    if (pg_num_rows($resBuscaEventoDia) === 0) {
      return false;
    }

    return $this->getPorCodigo(\db_utils::fieldsMemory($resBuscaEventoDia, 0)->rh207_sequencial);
  }

  /**
   * Verifica se existe evento para a data desejada e para o servidor informado
   * @param \DBDate $data
   * @param \Servidor $servidor
   * @return bool|EventoModel
   * @throws \DBException
   */
  public function possuiEventoNoDiaParaServidor(\DBDate $data, \Servidor $servidor) {

    $instituicao = \InstituicaoRepository::getInstituicaoSessao();

    $daoEvento      = new \cl_pontoeletronicoeventomatricula();
    $where[]        = "rh207_datainicial = '{$data->getDate()}'";
    $where[]        = "rh208_rhpessoal   = {$servidor->getMatricula()}";
    $where[]        = "rh207_instit      = {$instituicao->getCodigo()}";
    $buscaEventoDia = $daoEvento->sql_query(null, 'rh207_sequencial', null, implode(' and ', $where));
    $resBuscaEventoDia = db_query($buscaEventoDia);
    if (!$resBuscaEventoDia) {
      $mensagem  = "Ocorreu um erro ao verificar a existência evento com os dados:\n";
      $mensagem .= "Data: ".$data->getDate(\DBDate::DATA_PTBR)."\n";
      $mensagem .= "Servidor: ".$servidor->getMatricula() ." - ". $servidor->getCgm()->getNome();
      throw new \DBException($mensagem);
    }

    if (pg_num_rows($resBuscaEventoDia) === 0) {
      return false;
    }

    return $this->getPorCodigo(\db_utils::fieldsMemory($resBuscaEventoDia, 0)->rh207_sequencial);
  }

  /**
   * Retorna todos os eventos cadastrados
   * @return bool|EventoModel[]
   * @throws \DBException
   */
  public function getTodos() {

    $daoEvento         = new \cl_pontoeletronicoevento();
    $codigoInstituicao = \InstituicaoRepository::getInstituicaoSessao()->getCodigo();
    $whereEventoEfetividadeAberta[] = "rh186_instituicao = {$codigoInstituicao}";
    $whereEventoEfetividadeAberta[] = "rh186_processado = FALSE";

    $campos = array(
      'distinct rh207_sequencial',
      'rh207_datainicial',
      "
      ( 
        ( (cast(rh186_datainicioefetividade as date), cast(rh186_datafechamentoefetividade as date)) overlaps (rh207_datainicial::date, rh207_datafinal::date) ) is true
          OR
        ( extract(year FROM rh207_datainicial) > (SELECT max(rh186_exercicio) FROM configuracoesdatasefetividade WHERE rh186_instituicao = {$codigoInstituicao}) )
      ) as conflito_data "
    );

    $buscaEventoDia = $daoEvento->sql_query_join_configuracoesdatasefetividade(
      null,
      implode(', ', $campos),
      'rh207_datainicial',
      implode(' AND ', $whereEventoEfetividadeAberta)
    );

    $resBuscaEventoDia = db_query($buscaEventoDia);
    if (!$resBuscaEventoDia) {
      throw new \DBException("Ocorreu um erro ao verificar a existência de eventos.");
    }

    if (pg_num_rows($resBuscaEventoDia) === 0) {
      return false;
    }

    $eventoRepository = $this;
    return \db_utils::makeCollectionFromRecord($resBuscaEventoDia, function($retorno) use ($eventoRepository) {

      if ($retorno->conflito_data === 'f' || $retorno->conflito_data === false) {
        return $eventoRepository->getPorCodigo($retorno->rh207_sequencial);
      }
    });
  }

  /**
   * Exclui um evento
   * @return bool
   * @throws \DBException
   */
  public function excluir($codigo) {

    $daoEventoMatriculas = new \cl_pontoeletronicoeventomatricula();

    if(!$daoEventoMatriculas->excluir(null, "rh208_pontoeletronicoevento = {$codigo}")) {
      throw new \DBException($daoEventoMatriculas->erro_msg);
    }

    if(!$this->daoEvento->excluir($codigo)) {
      throw new \DBException($this->daoEvento->erro_msg);
    }

    return true;
  }
}
