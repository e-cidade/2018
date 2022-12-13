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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Model;
use ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Horas\BaseHora;
use ECidade\RecursosHumanos\RH\PontoEletronico\Evento\Repository\Evento as EventoRepository;
use ECidade\RecursosHumanos\RH\PontoEletronico\Validacao\Evento as EventoValidador;

/**
 * Class Evento
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Evento
 */
class Evento {

  /**
   * @var integer
   */
  protected $codigo;

  /**
   * @var string
   */
  protected $titulo;

  /**
   * @var \DBDate
   */
  protected $dataInicial;

  /**
   * @var \DBDate
   */
  protected $dataFinal;

  /**
   * @var \DateTime
   */
  protected $entradaUm = null;

  /**
   * @var \DateTime
   */
  protected $saidaUm = null;

  /**
   * @var \DateTime
   */
  protected $entradaDois = null;

  /**
   * @var \DateTime
   */
  protected $saidaDois = null;

  /**
   * @var integer
   */
  protected $tipoHoraExtraUm;

  /**
   * @var integer
   */
  protected $tipoHoraExtraDois;

  /**
   * @var \Servidor[]
   */
  protected $servidores = array();

  /**
   * @var \Instituicao
   */
  protected $instituicao;

  /**
   * @var integer
   */
  protected $codigoInstituicao;

  /**
   * Contém os tipos de horas permitidas para cadastro do evento
   * @var array
   */
  public static $horasExtrasPermitidas =
    array(
      BaseHora::HORAS_EXTRA50,
      BaseHora::HORAS_EXTRA75,
      BaseHora::HORAS_EXTRA100,
      BaseHora::HORAS_EXTRA50_NOTURNA,
      BaseHora::HORAS_EXTRA75_NOTURNA,
      BaseHora::HORAS_EXTRA100_NOTURNA
    );

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->codigo;
  }

  /**
   * @param int $codigo
   */
  public function setCodigo($codigo) {
    $this->codigo = $codigo;
  }

  /**
   * @return string
   */
  public function getTitulo() {
    return $this->titulo;
  }

  /**
   * @param string $titulo
   */
  public function setTitulo($titulo) {
    $this->titulo = $titulo;
  }

  /**
   * @return \DBDate
   */
  public function getDataInicial() {
    return $this->dataInicial;
  }

  /**
   * @param \DBDate $dataInicial
   */
  public function setDataInicial(\DBDate $dataInicial) {
    $this->dataInicial = $dataInicial;
  }

  /**
   * @return \DBDate
   */
  public function getDataFinal() {
    return $this->dataFinal;
  }

  /**
   * @param \DBDate $dataFinal
   */
  public function setDataFinal(\DBDate $dataFinal) {
    $this->dataFinal = $dataFinal;
  }

  /**
   * @return \DateTime
   */
  public function getEntradaUm() {
    return $this->entradaUm;
  }

  /**
   * @param \DateTime $entradaUm
   */
  public function setEntradaUm(\DateTime $entradaUm) {
    $this->entradaUm = $entradaUm;
  }

  /**
   * @return \DateTime
   */
  public function getSaidaUm() {
    return $this->saidaUm;
  }

  /**
   * @param \DateTime $saidaUm
   */
  public function setSaidaUm(\DateTime $saidaUm) {
    $this->saidaUm = $saidaUm;
  }

  /**
   * @return \DateTime
   */
  public function getEntradaDois() {
    return $this->entradaDois;
  }

  /**
   * @param \DateTime $entradaDois
   */
  public function setEntradaDois(\DateTime $entradaDois) {
    $this->entradaDois = $entradaDois;
  }

  /**
   * @return \DateTime
   */
  public function getSaidaDois() {
    return $this->saidaDois;
  }

  /**
   * @param \DateTime $saidaDois
   */
  public function setSaidaDois(\DateTime $saidaDois) {
    $this->saidaDois = $saidaDois;
  }

  /**
   * @return int
   */
  public function getTipoHoraExtraUm() {
    return $this->tipoHoraExtraUm;
  }

  /**
   * @param int $tipoHoraExtraUm
   * @throws \ParameterException
   */
  public function setTipoHoraExtraUm($tipoHoraExtraUm) {

    if (!empty($tipoHoraExtraUm) && !in_array($tipoHoraExtraUm, self::$horasExtrasPermitidas)) {
      throw new \ParameterException("Tipo de Hora Extra informada para o primeiro período é inválido.");
    }
    $this->tipoHoraExtraUm = $tipoHoraExtraUm;
  }

  /**
   * @return int
   */
  public function getTipoHoraExtraDois() {
    return $this->tipoHoraExtraDois;
  }

  /**
   * @param int $tipoHoraExtraDois
   * @throws \ParameterException
   */
  public function setTipoHoraExtraDois($tipoHoraExtraDois) {

    if (!empty($tipoHoraExtraDois) && !in_array($tipoHoraExtraDois, self::$horasExtrasPermitidas)) {
      throw new \ParameterException("Tipo de Hora Extra informada para o primeiro período é inválido.");
    }
    $this->tipoHoraExtraDois = $tipoHoraExtraDois;
  }

  /**
   * @param \Servidor $servidor
   */
  public function adicionarServidor(\Servidor $servidor) {
    $this->servidores[$servidor->getMatricula()] = $servidor;
  }

  /**
   * @param \Servidor $servidor
   */
  public function removerServidor(\Servidor $servidor) {
    unset($this->servidores[$servidor->getMatricula()]);
  }

  /**
   * @return \Servidor[]
   */
  public function getServidores() {

    if (empty($this->servidores)) {

      $repository = EventoRepository::getInstance();
      $repository->carregarServidores($this);
    }
    return $this->servidores;
  }

  /**
   * Retorna o intervalor das horas no período 1
   *
   * @return \DateInterval
   */
  public function getIntervaloUm() {
    return !empty($this->saidaUm) && !empty($this->entradaUm) ? $this->entradaUm->diff($this->saidaUm) : new \DateInterval('PT0H0M');
  }

  /**
   * Retorna o intervalor das horas no período 1
   *
   * @return \DateInterval
   */
  public function getIntervaloDois() {
    return !empty($this->entradaDois) && !empty($this->saidaDois) ? $this->entradaDois->diff($this->saidaDois) : new \DateInterval('PT0H0M');
  }

  /**
   * @param \Instituicao $instituicao
   */
  public function setInstituicao(\Instituicao $instituicao) {

    $this->setCodigoInstituicao($instituicao->getCodigo());
    $this->instituicao = $instituicao;
  }

  /**
   * @return \Instituicao
   */
  public function getInstituicao() {

    if (empty($this->instituicao) && !empty($this->codigoInstituicao)) {
      $this->setInstituicao(\InstituicaoRepository::getInstituicaoByCodigo($this->codigoInstituicao));
    }
    return $this->instituicao;
  }

  /**
   * @param integer $codigoInstituicao
   */
  public function setCodigoInstituicao($codigoInstituicao) {
    $this->codigoInstituicao = $codigoInstituicao;
  }

  /**
   * @return array
   */
  public function validarServidores() {

    $servidoresIgnorados = array();

    $nomeArquivo = 'tmp/servidores_inconsistencia.json';
    $errosArquivo = array();

    foreach ($this->servidores as $servidor) {
      
      $validador = EventoValidador::create();
      $validador->setEvento($this);
      $validador->setServidor($servidor);

      if ( !$validador->validar() ) {

        $servidoresIgnorados[] = $servidor->getMatricula();
        foreach ($validador->getErros() as $codigoErro => $titulo) {

          if (empty($errosArquivo[$codigoErro])) {

            $errosArquivo[$codigoErro] = new \stdClass();
            $errosArquivo[$codigoErro]->titulo = $titulo;
            $errosArquivo[$codigoErro]->matriculas = array();
          }
          $errosArquivo[$codigoErro]->matriculas[] = (object)array('matricula' => $servidor->getMatricula(), 'nome' => $servidor->getCgm()->getNome());
        }
      }
    }

    if(file_exists($nomeArquivo)) {
      unlink($nomeArquivo);
    }

    if (count($errosArquivo) > 0) {
      file_put_contents($nomeArquivo, json_encode(\DBString::utf8_encode_all($errosArquivo)));
    }
    return $servidoresIgnorados;
  }
}
