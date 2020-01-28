<?php
/*
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

/**
 * Classe para processamento do arquivo de consignacao em folha
 *
 */
class ArquivoConsignado {

  /**
   * Codigo do Arquivo
   * @var Integer
   */
  private $iCodigo;

  /**
   * Array com os Registros do ponto
   * @var RegistroConsignado[]
   */
  private $aRegistros = array();

  /**
   * Nome do arquivo
   * @var String
   */
  private $sNome;

  /**
   * Competencia do Arquivo
   * @var DBCompetencia
   */
  private $oCompetencia;

  /**
   * Instituição do Arquivo
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   * Representa o OID do relatório da importação
   * @var Integer
   */
  private $iRelatorio;

  /**
   * OID do arquivo importado
   * @var Integer
   */
  private $iArquivo;

  /**
   * @var Banco
   */
  private $oBanco;

  /**
   * Tipo do consignado
   * @var string
   */
  private $sTipo = 'N';

  /**
   * Representa o estado do arquivo, se processado ou não (lançado no ponto)
   * @var Boolean
   */
  private $lProcessado = false;

  /**
   * Consigando via arquivo
   */
  const TIPO_ARQUIVO  = 'A';

  /**
   * consigando lancado manuaal
   */
  const TIPO_MANUAL= 'M';

  public function __construct() {

  }

  /**
   * @return int
   */
  public function getCodigo() {

    return $this->iCodigo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {

    $this->iCodigo = $iCodigo;
  }

  /**
   * @return RegistroConsignado[]
   */
  public function getRegistros() {

    if (empty($this->aRegistros)) {
      $this->aRegistros = RegistroConsignadoRepository::getRegistrosDoArquivo($this);
    }
    return $this->aRegistros;
  }

  /**
   * @return RegistroConsignado[]
   */
  public function getRegistrosDaMatricula($iMatricula = null) {

    if (!empty($iMatricula)) {

      $this->aRegistros = RegistroConsignadoRepository::getRegistroByMatricula($iMatricula, $this);
      return $this->aRegistros;
    }
    if (empty($this->aRegistros)) {
      $this->aRegistros = RegistroConsignadoRepository::getRegistrosDoArquivo($this);
    }
    return $this->aRegistros;
  }

  /**
   * @param RegistroConsignado[] $aRegistros
   */
  public function setRegistros(array $aRegistros) {

    $this->aRegistros = $aRegistros;
  }

  /**
   * @return String
   */
  public function getNome() {

    return $this->sNome;
  }

  /**
   * @param String $sNome
   */
  public function setNome($sNome) {

    $this->sNome = $sNome;
  }

  /**
   * @return DBCompetencia
   */
  public function getCompetencia() {

    return $this->oCompetencia;
  }

  /**
   * @param DBCompetencia $oCompetencia
   */
  public function setCompetencia(DBCompetencia $oCompetencia) {

    $this->oCompetencia = $oCompetencia;
  }

  /**
   * @return Instituicao
   */
  public function getInstituicao() {

    return $this->oInstituicao;
  }

  /**
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {

    $this->oInstituicao = $oInstituicao;
  }

  /**
   * @return int
   */
  public function getRelatorio() {

    return $this->iRelatorio;
  }

  /**
   * @param int $iRelatorio
   */
  public function setRelatorio($iRelatorio) {

    $this->iRelatorio = $iRelatorio;
  }

  /**
   * @return int
   */
  public function getArquivo() {

    return $this->iArquivo;
  }

  /**
   * @param int $iArquivo
   */
  public function setArquivo($iArquivo) {

    $this->iArquivo = $iArquivo;
  }

  /**
   * @return boolean
   */
  public function isProcessado() {

    return $this->lProcessado;
  }

  /**
   * @param boolean $lProcessado
   */
  public function setProcessado($lProcessado) {

    $this->lProcessado = $lProcessado;
  }

  public function adicionarRegistro( $oRegistro) {
    $this->aRegistros[] = $oRegistro;
  }

  /**
   * Retorna todos os registros que estão com o motivo valido
   *
   * @return RegistroConsignado[]
   * @throws \BusinessException
   * @throws \DBException
   */
  public function getRegistrosValidos($sOrdenacao = '') {

    $aRegistrosValidos = array();
    $aRegistros        = RegistroConsignadoRepository::getRegistrosDoArquivo($this, $sOrdenacao);

    foreach ($aRegistros as $oRegistro) {

      $sMotivo = $oRegistro->getMotivo();
      if (empty($sMotivo)) {
        $aRegistrosValidos[] = $oRegistro;
      }
    }
    return $aRegistrosValidos;
  }

  /**
   * @return Banco
   */
  public function getBanco() {

    return $this->oBanco;
  }

  /**
   * @param Banco $oBanco
   */
  public function setBanco(Banco $oBanco) {

    $this->oBanco = $oBanco;
  }

  /**
   * @return string
   */
  public function getTipo() {

    return $this->sTipo;
  }

  /**
   * @param string $sTipo
   */
  public function setTipo($sTipo) {
    $this->sTipo = $sTipo;
  }


}