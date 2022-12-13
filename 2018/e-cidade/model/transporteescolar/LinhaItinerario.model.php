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

/**
 * Linha Itinerário
 * @author Trucolo <trucolo@dbseller.com.br>
 * @package transporteescolar
 * @version $Revision: 1.11 $
 */
class LinhaItinerario {

  /**
   * Codigo da linha itinerário
   * @var integer
   */
  protected $iCodigo;

  /**
   * Constante IDA
   * @var integer
   */
  const IDA = 1;

  /**
   * Constante VOLTA
   * @var integer
   */
  const VOLTA = 2;

  /**
   * Array de instâncias de logradouros
   * @var array
   */
  protected $aLogradouros = array();

  /**
   * Array de instâncias de horários
   * @var array
   */
  protected $aHorarios = array();

  /**
   * Tipo de itinerário
   *  1 - Ida
   *  2 - Volta
   * @var integer
   */
  protected $iTipo = null;

  /**
   * Instância de LinhaTransporte
   * @var LinhaTransporte
   */
  protected $oLinhaTransporte = null;

  static private $aTipos = array(
    self::IDA   => "Ida",
    self::VOLTA => "Retorno",
  );

  /**
   * Instancia uma linha itinerário
   * @param integer $iCodigo Codigo da linha itinerário
   * @throws ParameterException Codigo não é do tipo inteiro
   * @throws BusinessException Codigo informado não existe no sistema
   */
  function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      if (!DBNumber::isInteger($iCodigo)) {
        throw new ParameterException('Parâmetro $iCodigo deve ser um inteiro.');
      }
      $oDaoLinhaItinerario = new cl_linhatransporteitinerario();
      $sSqlLinhaItinerario = $oDaoLinhaItinerario->sql_query($iCodigo);
      $rsLinhaItinerario   = $oDaoLinhaItinerario->sql_record($sSqlLinhaItinerario);
      if ($oDaoLinhaItinerario->numrows == 0) {

        $oVariaveis         = new stdClass();
        $oVariaveis->codigo = $iCodigo;
        throw new BusinessException(_M('educacao.transporteescolar.LinhaItinerario.linha_itinerario_nao_cadastrado', $oVariaveis));
      }
      $oDadosLinhaItinerario = db_utils::fieldsMemory($rsLinhaItinerario, 0);
      $this->iCodigo         = $oDadosLinhaItinerario->tre09_sequencial;
      $this->setLinhaTransporte(new LinhaTransporte($oDadosLinhaItinerario->tre09_linhatransporte));
      $this->setTipo($oDadosLinhaItinerario->tre09_tipo);
    }
  }

  /**
   * retorna o codigo da linha itinerário
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna um array de objetos de logradouros do itinerário
   * @return LinhaItinerarioLogradouro
   */
  public function getLogradouros() {

    if (count($this->aLogradouros) == 0) {

      $oDaoItinerarioLogradouro   = new cl_itinerariologradouro();
      $sWhereItinerarioLogradouro = "tre10_linhatransporteitinerario = {$this->getCodigo()}";
      $sSqlItinerarioLogradouro   = $oDaoItinerarioLogradouro->sql_query_file(
                                                                          null,
                                                                          "tre10_sequencial",
                                                                          "tre10_ordem",
                                                                          $sWhereItinerarioLogradouro
                                                                        );


      $rsItinerarioLogradouro     = $oDaoItinerarioLogradouro->sql_record($sSqlItinerarioLogradouro);
      $iTotalItinerarioLogradouro = $oDaoItinerarioLogradouro->numrows;

      if ($iTotalItinerarioLogradouro > 0) {

        for ($iContador = 0; $iContador < $iTotalItinerarioLogradouro; $iContador++) {

          $iItinerarioLogradouro = db_utils::fieldsMemory($rsItinerarioLogradouro, $iContador)->tre10_sequencial;
          $oItinerarioLogradouro = new LinhaItinerarioLogradouro($iItinerarioLogradouro);
          $this->aLogradouros[]  = $oItinerarioLogradouro;
        }
      }
    }
    return $this->aLogradouros;
  }

  /**
   * Retorna um array de objetos de horários do itinerário
   * @return array
   */
  public function getHorarios() {

    $oDaoItinerarioHorario    = new cl_linhatransportehorario();
    $sWhereItinerarioHorario  = "    tre07_linhatransporteitinerario = {$this->getCodigo()} ";
    $sSqlItinerarioHorario    = $oDaoItinerarioHorario->sql_query_file(
                                                                   null,
                                                                   "tre07_sequencial",
                                                                   "tre07_sequencial",
                                                                   $sWhereItinerarioHorario
                                                                 );
    $rsItinerarioHorario     = $oDaoItinerarioHorario->sql_record($sSqlItinerarioHorario);
    $iTotalItinerarioHorario = $oDaoItinerarioHorario->numrows;

    if ($iTotalItinerarioHorario > 0) {

      for ($iContador = 0; $iContador < $iTotalItinerarioHorario; $iContador++) {

        $iLinhaItinerarioHorario = db_utils::fieldsMemory($rsItinerarioHorario, $iContador)->tre07_sequencial;
        $oLinhaItinerarioHorario = new LinhaItinerarioHorario($iLinhaItinerarioHorario);
        $this->aHorarios[]       = $oLinhaItinerarioHorario;
      }
    }
    return $this->aHorarios;
  }

  /**
   * Retorna o tipo do itinerário
   * @return integer
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * Define o tipo do itinerário se é ida ou volta
   * @param integer
   */
  public function setTipo($iTipo = null) {
    $this->iTipo = $iTipo;
  }

  /**
   * Adiciona um objeto LinhaItinerarioLogradouro no array
   * @param object LinhaItinerarioLogradouro
   */
  public function adicionarLogradouros(LinhaItinerarioLogradouro $oLinhaItinerarioLogradouro) {

    if (!in_array($oLinhaItinerarioLogradouro, $this->aLogradouros) && is_object($oLinhaItinerarioLogradouro)) {
      $this->aLogradouros[] = $oLinhaItinerarioLogradouro;
    }
  }

  /**
   * Adiciona LinhaItinerarioHorario
   * @param LinhaItinerarioHorario
   */
  public function adicionarHorarios(LinhaItinerarioHorario $oLinhaItinerarioHorario) {

    if (!in_array($LinhaItinerarioHorario, $this->aHorarios) && is_object($oLinhaItinerarioHorario)) {
      $this->aHorarios[] = $oLinhaItinerarioHorario;
    }
  }

  /**
   * Define a LinhaTransporte
   * @param LinhaTransporte LinhaTransporte
   */
  public function setLinhaTransporte(LinhaTransporte $oLinhaTransporte) {

    if (is_object($oLinhaTransporte)) {
      $this->oLinhaTransporte = $oLinhaTransporte;
    }
  }

  /**
   * Retorna um objeto LinhaTransporte
   * @return LinhaTransporte LinhaTransporte
   */
  public function getLinhaTransporte() {
    return $this->oLinhaTransporte;
  }

  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação com o banco de dados.');
    }

    $oDaoLinhaItinerario    = new cl_linhatransporteitinerario();
    $sWhereLinhaItinerario  = "    tre09_linhatransporte = {$this->getLinhaTransporte()->getCodigo()} ";
    $sWhereLinhaItinerario .= "and tre09_tipo = {$this->getTipo()}";
    $sSqlLinhaItinerario    = $oDaoLinhaItinerario->sql_query_file(
                                                                    null,
                                                                    'tre09_sequencial',
                                                                    'tre09_sequencial',
                                                                    $sWhereLinhaItinerario
                                                                  );
    $rsLinhaItinerario      = $oDaoLinhaItinerario->sql_record($sSqlLinhaItinerario);

    if ($oDaoLinhaItinerario->numrows == 0) {

      $oDaoLinhaItinerario->tre09_linhatransporte = $this->getLinhaTransporte()->getCodigo();
      $oDaoLinhaItinerario->tre09_tipo            = $this->getTipo();
      if ($this->iCodigo != null) {

        $oDaoLinhaItinerario->tre09_sequencial = $this->getCodigo();
        $oDaoLinhaItinerario->alterar($this->getCodigo());
      } else {

        $oDaoLinhaItinerario->incluir(null);
        $this->iCodigo = $oDaoLinhaItinerario->tre09_sequencial;
      }

      if ($oDaoLinhaItinerario->erro_status == 0) {

        $oVariaveis            = new stdClass();
        $oVariaveis->sMensagem = $oDaoLinhaItinerario->erro_msg;
        throw new BusinessException(_M('educacao.transporteescolar.LinhaItinerario.linha_itinerario_erro_salvar',
                                       $oVariaveis));
      }
    } else {
      $this->iCodigo = db_utils::fieldsMemory($rsLinhaItinerario, 0)->tre09_sequencial;
    }
  }

  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new DBException('Não existe transação com o banco de dados.');
    }

    if (!empty($this->iCodigo)) {

      $oDaoLinhaItinerario = db_utils::getDao('linhatransporteitinerario');
      $oDaoLinhaItinerario->excluir($this->getCodigo());

      if ($oDaoLinhaItinerario->erro_status == 0) {

        $sMensagem            = 'educacao.transporteescolar.LinhaItinerario.linha_itinerario_erro_excluir';
        $oVariaveis           = new stdClass();
        $oVariaveis->erro_dao = $oDaoPontoParada->erro_msg;
        throw new BusinessException(_M($sMensagem, $oVariaveis));
      }
    }

  }

  /**
   * Retorna o tipo do itinerário
   * @return string
   */
  public function getDescricaoTipo() {
    return self::$aTipos[$this->iTipo];
  }
}