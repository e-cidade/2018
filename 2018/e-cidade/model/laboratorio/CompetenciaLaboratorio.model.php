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

require_once 'model/ambulatorial/ICompetenciaSaude.interface.php';

define("MSG_COMPETENCIA", "saude.laboratorio.CompetenciaLaboratorio.");


/**
 * Class CompetenciaLaboratorio
 * 
 * @implements ICompetenciaSaude
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @author André Mello <andre.mello@dbseller.com.br>
 * @package laboratorio
 * @version   $Revision: 1.3 $
 */
class CompetenciaLaboratorio implements ICompetenciaSaude {


  /**
   * Codigo de fechamento da competência do laboatorio
   * @var integar
   */
  private $iCodigo;

  /**
   * Hora do fechamento (H:i)
   * @var string
   */
  private $sHora;

  /**
   * Descrição informada ao realizar fechamento
   * @var string
   */
  private $sDescricao;

  /**
   * Usuário que encerrou a competência
   * @var UsuarioSistema
   */
  private $oUsuarioSistema;

  /**
   * Data do sistema que foi encerrada a competência
   * @var DBDate
   */
  private $oDataInclusao;

  /**
   * Período inicial de abrangencia ao encerrar a competência
   * @var DBDate
   */
  private $oPeriodoInicial;

  /**
   * Período final de abrangencia ao encerrar a competência
   * @var DBDate
   */
  private $oPeriodoFinal;

  /**
   * Competência
   * @var DBCompetencia
   */
  private $oCompetencia;

  /**
   * Instancia do Financiamento
   * @var FinanciamentoSaude
   */
  private $oFinanciamentoSaude;

  /**
   * Procedimentos encerrados para Competência
   * @var array
   */
  private $aProcedimentos = array();

  /**
   * Lista de filtros usados para buscar os procedimentos
   * @var array
   */
  private $aFiltrosProcedimentos = array();

  public function __construct ( $iCodigo = null ) {

    if ( empty($iCodigo) ) {
      return $this;
    }

    $oDaoCompetencia = new cl_lab_fechamento();
    $sSqlCompetencia = $oDaoCompetencia->sql_query_file( $iCodigo );
    $rsCompetencia   = db_query($sSqlCompetencia);

    if ( $rsCompetencia && pg_num_rows( $rsCompetencia ) == 1 ) {

      $oDados = db_utils::fieldsMemory( $rsCompetencia, 0 );

      $this->iCodigo             = $iCodigo;
      $this->sHora               = $oDados->la54_c_hora;
      $this->sDescricao          = $oDados->la54_c_descr;
      $this->oUsuarioSistema     = UsuarioSistemaRepository::getPorCodigo( $oDados->la54_i_login );
      $this->oDataInclusao       = new DBDate( $oDados->la54_d_data );
      $this->oPeriodoInicial     = new DBDate( $oDados->la54_d_ini );
      $this->oPeriodoFinal       = new DBDate( $oDados->la54_d_fim );
      $this->oCompetencia        = new DBCompetencia( $oDados->la54_i_compano, $oDados->la54_i_compmes );
      $this->oFinanciamentoSaude = FinanciamentoSaudeRepository::getFinanciamentoSaudeByCodigo( $oDados->la54_i_financiamento );
    }

  }

  /**
   * Retorna o código
   * @return integar
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Setter Hora
   * @param string
   */
  public function setHora ($shora) {
    $this->sHora = $shora;
  }

  /**
   * Getter Hora
   * @return string
   */
  public function getHora () {
    return $this->sHora;
  }


  /**
   * Setter descrição da competência
   * @param string
   */
  public function setDescricao ($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Getter descrição da competência
   * @return string
   */
  public function getDescricao () {
    return $this->sDescricao;
  }


  /**
   * Setter usuário do sistema
   * @param UsuarioSistema
   */
  public function setUsuario (UsuarioSistema $oUsuario) {
    $this->oUsuarioSistema = $oUsuario;
  }

  /**
   * Getter usuário do sistema
   * @return UsuarioSistema
   */
  public function getUsuario () {
    return $this->oUsuarioSistema;
  }

  /**
   * Setter data que foi realizado o encerramento
   * @param DBDate $oDtInclusao
   */
  public function setDataInclusao (DBDate $oDtInclusao) {
    $this->oDataInclusao = $oDtInclusao;
  }

  /**
   * Getter data que foi realizado o encerramento
   * @return DBDate $oDtInclusao
   */
  public function getDataInclusao () {
    return $this->oDataInclusao;
  }

  /**
   * Setter periodo inicial da competência
   * @param DBDate $oPeriodoInicial
   */
  public function setPeriodoInicial (DBDate $oPeriodoInicial) {
    $this->oPeriodoInicial = $oPeriodoInicial;
  }

  /**
   * Getter periodo inicial da competência
   * @return DBDate $oPeriodoInicial
   */
  public function getPeriodoInicial () {
    return $this->oPeriodoInicial;
  }

  /**
   * Setter periodo final da competência
   * @param DBDate $oPeriodoFinal
   */
  public function setPeriodoFinal (DBDate $oPeriodoFinal) {
    $this->oPeriodoFinal = $oPeriodoFinal;
  }

  /**
   * Getter periodo final da competência
   * @return DBDate $oPeriodoFinal
   */
  public function getPeriodoFinal () {
    return $this->oPeriodoFinal;
  }

  /**
   * Setter competência
   * @param DBCompetencia $oCompetencia
   */
  public function setCompetencia (DBCompetencia $oCompetencia) {
    $this->oCompetencia = $oCompetencia;
  }

  /**
   * Getter competência
   * @return DBCompetencia $oCompetencia
   */
  public function getCompetencia () {
    return $this->oCompetencia;
  }

  /**
   * Setter Financiamento encerrado
   * @param FinanciamentoSaude oFinanciamentoSaude
   */
  public function setFinanciamento (FinanciamentoSaude $oFinanciamentoSaude) {
    $this->oFinanciamentoSaude = $oFinanciamentoSaude;
  }

  /**
   * Getter Financiamento encerrado
   * @param FinanciamentoSaude
   */
  public function getFinanciamento () {
    return $this->oFinanciamentoSaude;
  }


  /**
   * Adiciona um filtro para ser usado no where do sql que buscará os procedimentos
   * @param string $sFiltro
   */
  public function adicionaFiltroBuscaProcedimentos($sFiltro) {

    $this->aFiltrosProcedimentos[] = $sFiltro;
  }

  /**
   * Salva o encerramento da competencia
   */
  public function salvar() {

    $aExamesConferidos = $this->getExamesConferidos();

    $oDaoFechaConferencia = new cl_lab_fechaconferencia();
    $oDaoLabFechamento    = new cl_lab_fechamento();

    $oDaoLabFechamento->la54_d_data          = $this->oDataInclusao->getDate();
    $oDaoLabFechamento->la54_c_hora          = $this->sHora;
    $oDaoLabFechamento->la54_i_compano       = $this->oCompetencia->getAno();
    $oDaoLabFechamento->la54_i_compmes       = $this->oCompetencia->getMes();
    $oDaoLabFechamento->la54_i_login         = $this->oUsuarioSistema->getCodigo();
    $oDaoLabFechamento->la54_c_descr         = $this->sDescricao;
    $oDaoLabFechamento->la54_d_ini           = $this->oPeriodoInicial->getDate();
    $oDaoLabFechamento->la54_d_fim           = $this->oPeriodoFinal->getDate();
    $oDaoLabFechamento->la54_i_financiamento = $this->oFinanciamentoSaude->getCodigo();

    if ( empty($this->iCodigo) ) {

      $oDaoLabFechamento->incluir(null);
      $this->iCodigo = $oDaoLabFechamento->la54_i_codigo;
    } else {

      $oDaoLabFechamento->la54_i_codigo = $this->iCodigo;
      $oDaoLabFechamento->alterar($this->iCodigo);
    }

    if ($oDaoLabFechamento->erro_status == 0) {
      throw new BusinessException( _M(MSG_COMPETENCIA."nao_foi_possivel_salvar")) ;
    }


    $sWhere = "la58_i_fechamento = {$this->iCodigo}";
    $oDaoFechaConferencia->excluir( null, $sWhere );


    foreach ( $aExamesConferidos as $iExameConferido ) {

      $oDaoFechaConferencia->la58_i_codigo      = null;
      $oDaoFechaConferencia->la58_i_fechamento  = $this->iCodigo;
      $oDaoFechaConferencia->la58_i_conferencia = $iExameConferido;
      $oDaoFechaConferencia->la58_gerado        = 'false';

      $oDaoFechaConferencia->incluir( null );

      if ( $oDaoFechaConferencia->erro_status == 0 ) {

        $oErro = new stdClass();
        $oErro->sql_erro = str_replace('\\n', "\n", $oDaoFechaConferencia->erro_sql);
        throw new BusinessException( _M(MSG_COMPETENCIA."erro_ao_conferir_exame", $oErro) );
      }
    }
  }

  /**
   * Remove o fechamento e todos os vinculos
   * @throws BusinessException
   */
  public function remover() {

    $oDaoBpaMagnetico     = new cl_lab_bpamagnetico();
    $oDaoFechaConferencia = new cl_lab_fechaconferencia();
    $oDaoFechamento       = new cl_lab_fechamento();


    $oDaoBpaMagnetico->excluir(null, " la55_i_fechamento = {$this->iCodigo}");
    if ($oDaoBpaMagnetico->erro_status == 0) {
      throw new BusinessException(_M(MSG_COMPETENCIA."erro_ao_excluir_arquivo"));
    }

    $oDaoFechaConferencia->excluir(null, " la58_i_fechamento = {$this->iCodigo}");
    if ($oDaoFechaConferencia->erro_status == 0) {
      throw new BusinessException(_M(MSG_COMPETENCIA."erro_ao_excluir_procedimentos"));
    }

    $oDaoFechamento->excluir($this->iCodigo);

    if ($oDaoFechamento->erro_status == 0) {
      throw new BusinessException(_M(MSG_COMPETENCIA."erro_ao_excluir_fechamento"));
    }


  }

  /**
   * Retornar os dados dos procedimentos de uma competencia fechada
   * @return array
   * @throws BusinessException
   */
  public function getProcedimentos() {

    if (count($this->aProcedimentos) == 0) {

      $sWhere = " la58_i_fechamento = {$this->iCodigo} ";
      if (count($this->aFiltrosProcedimentos) > 0) {
        $sWhere .= " and " . implode(" and ", $this->aFiltrosProcedimentos);
      }

      $oDaoProcedimentoFechado = new cl_lab_fechamento();
      $sSqlProcedimento        = $oDaoProcedimentoFechado->sql_query_programas($sWhere);
      $rsProcedimento          = $oDaoProcedimentoFechado->sql_record($sSqlProcedimento);
      $iLinhas                 = $oDaoProcedimentoFechado->numrows;
      if ($iLinhas == 0) {
        throw new BusinessException(_M(MSG_COMPETENCIA."nenhum_procedimento_encontrado"));
      }

      $this->aProcedimentos = db_utils::getCollectionByRecord($rsProcedimento);
    }

    return $this->aProcedimentos;
  }

  /**
   * Busca os exames que já estão conferidos de acordo com o período e o código do financiamento informados
   * @return array
   * @throws BusinessException
   */
  private function getExamesConferidos() {

    $sDataInicial      = $this->oPeriodoInicial->convertTo(DBDate::DATA_EN);
    $sDataFinal        = $this->oPeriodoFinal->convertTo(DBDate::DATA_EN);
    $aExamesConferidos = array();

    $oDaoConferencia      = new cl_lab_conferencia();
    $sWhereConferencia = "la47_d_data between '{$sDataInicial}' and '{$sDataFinal}' ";

    $iFinanciamento = $this->oFinanciamentoSaude->getCodigo();

    if ( $iFinanciamento != 0 ) {
      $sWhereConferencia .= " and sd65_c_financiamento in (select sd65_c_financiamento from sau_financiamento ";
      $sWhereConferencia .= "                                where sd65_i_codigo = {$iFinanciamento})";
    }

    $sWhereConferencia .= " and not exists (select 1 from lab_fechaconferencia where la58_i_conferencia = la47_i_codigo) ";
    $sSqlConferencia    = $oDaoConferencia->sql_query_file( null, " la47_i_codigo ", null, $sWhereConferencia);
    $rsConferencia      = db_query($sSqlConferencia);

    $iLinha = pg_num_rows( $rsConferencia );

    if ( !$rsConferencia || $iLinha == 0 ) {
      throw new BusinessException( _M(MSG_COMPETENCIA."sem_exames_conferidos") );
    }

    for ( $iContador = 0; $iContador < $iLinha; $iContador++ ) {
      $aExamesConferidos[] = db_utils::fieldsMemory( $rsConferencia, $iContador )->la47_i_codigo;
    }

    return $aExamesConferidos;
  }

} 