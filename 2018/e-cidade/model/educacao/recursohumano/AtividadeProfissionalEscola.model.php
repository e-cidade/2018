<?php
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

/**
 * Atividade do profissional da escola
 * @package    Educacao
 * @subpackage recursohumano
 * @author     Andrio Costa - andrio.costa@dbseller.com.br
 * @version    $Revision: 1.10 $
 */
class AtividadeProfissionalEscola {

  const MSG_ATIVIDADEPROFISSIONALESCOLA = "educacao.escola.AtividadeProfissionalEscola.";

  protected $iCodigo = null;
  protected $lAtivo;

  /**
   * @var ProfissionalEscola
   */
  protected $oProfissionalEscola;

  /**
   * @var AtividadeEscolar
   */
  protected $oAtividadeEscolar = null;

  /**
   * @var AtoLegal
   */
  protected $oAtoLegal = null;

  /**
   * Array com as turnos e horários que o profissional exerce a atividade
   * @var AgendaAtividadeProfissional[]
   */
  protected $aAgendaAtividade  = array();

  /**
   * Relacoes de trabalho vínculada a Função do profissional
   * @var RelacaoTrabalho[]
   */
  protected $aRelacoesTrabalho = array();
  
  public function __construct($iCodigo = null) {

    if ( empty($iCodigo) ) {
      return $this;
    }

    $oDaoRecHumanoAtiv = new cl_rechumanoativ();
    $sSqlAtividade     = $oDaoRecHumanoAtiv->sql_query_file($iCodigo);
    $rsAtividade       = db_query($sSqlAtividade);

    $oMsgErro = new stdClass();
    if (!$rsAtividade) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(self::MSG_ATIVIDADEPROFISSIONALESCOLA . "erro_buscar_atividade", $oMsgErro) );
    }

    if (pg_num_rows($rsAtividade) == 0) {
      return true;
    }

    $oDados = db_utils::fieldsMemory($rsAtividade, 0);

    $this->iCodigo             = $oDados->ed22_i_codigo;
    $this->lAtivo              = $oDados->ed22_ativo == 't';
    $this->oProfissionalEscola = ProfissionalEscolaRepository::getByCodigo($oDados->ed22_i_rechumanoescola);
    $this->oAtividadeEscolar   = AtividadeEscolarRepository::getByCodigo($oDados->ed22_i_atividade );
    if ( !empty($oDados->ed22_i_atolegal) ) {
      $this->oAtoLegal = AtoLegalRepository::getAtoLegalByCodigo( $oDados->ed22_i_atolegal );
    }
  }


  /**
   * Getter codigo
   * @param integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }


  /**
   * Setter ativo
   * @param boolean
   */
  public function setAtivo($lAtivo) {
    $this->lAtivo = $lAtivo;
  }

  /**
   * Verifica se esta ativo
   * @param boolean
   */
  public function isAtivo() {
    return $this->lAtivo;
  }


  /**
   * Setter profissional da escola
   * @param ProfissionalEscola
   */
  public function setProfissionalEscola(ProfissionalEscola $oProfissionalEscola) {
    $this->oProfissionalEscola = $oProfissionalEscola;
  }

  /**
   * Getter profissional da escola
   * @param ProfissionalEscola
   */
  public function getProfissionalEscola() {
    return $this->oProfissionalEscola;
  }


  /**
   * Setter atividade escolar
   * @param AtividadeEscolar
   */
  public function setAtividadeEscolar(AtividadeEscolar $oAtividadeEscolar) {
    $this->oAtividadeEscolar = $oAtividadeEscolar;
  }

  /**
   * Getter atividade escolar
   * @param AtividadeEscolar
   */
  public function getAtividadeEscolar() {
    return $this->oAtividadeEscolar;
  }


  /**
   * Setter ato legal
   * @param AtoLegal
   */
  public function setAtoLegal(AtoLegal $oAtoLegal) {
    $this->oAtoLegal = $oAtoLegal;
  }

  /**
   * Getter ato legal
   * @param AtoLegal
   */
  public function getAtoLegal() {

    return $this->oAtoLegal;
  }

  /**
   * Retorna uma agenda do exercício da atividade do profissional
   * @return AgendaAtividadeProfissional[]
   */
  public function getAgenda( $aDiasSemana = null ) {

    if ( count($this->aAgendaAtividade) == 0) {

      $aAgendaAtividade = AgendaAtividadeProfissionalRepository::getByAtividadeProfissional($this);

      if ( !empty( $aDiasSemana ) ) {

        $aAgendaAtividadeAux = array();
        foreach ($aAgendaAtividade as $oAgendaAtividadeProfissional ) {

          if ( in_array($oAgendaAtividadeProfissional->getDiaSemana(), $aDiasSemana) ) {
            $aAgendaAtividadeAux[] = $oAgendaAtividadeProfissional;
          }
        }
        $aAgendaAtividade = $aAgendaAtividadeAux;
      }

      $this->aAgendaAtividade = $aAgendaAtividade;
    }
    return $this->aAgendaAtividade;
  }

  /**
   * Adiciona uma agenda para a atividade
   * @param AgendaAtividadeProfissional $oAgenda
   */
  public function addAgenda(AgendaAtividadeProfissional $oAgenda) {

    $lAtualizou = false;

    if ($oAgenda->getCodigo() != '') {

      foreach ($this->aAgendaAtividade as $iKey => $oAgendaExistente) {

        if ( $oAgendaExistente->getCodigo() == $oAgenda->getCodigo() ) {

          $this->aAgendaAtividade[$iKey] = $oAgenda;
          $lAtualizou = true;
          break;
        }
      }
    }

    if ( !$lAtualizou ) {
      $this->aAgendaAtividade[] = $oAgenda;
    }
    return true;
  }


  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException( _M(self::MSG_ATIVIDADEPROFISSIONALESCOLA . "sem_transacao") );
    }
    $oDaoRecHumanoAtiv = new cl_rechumanoativ();
    $oDaoRecHumanoAtiv->ed22_i_rechumanoescola = $this->oProfissionalEscola->getCodigo();
    $oDaoRecHumanoAtiv->ed22_i_atividade       = $this->oAtividadeEscolar->getCodigo();
    $oDaoRecHumanoAtiv->ed22_i_atolegal        = 'null';
    if ( !is_null($this->oAtoLegal) ) {
      $oDaoRecHumanoAtiv->ed22_i_atolegal = $this->oAtoLegal->getCodigoAtoLegal();
    }

    $oDaoRecHumanoAtiv->ed22_ativo    = $this->lAtivo ? 'true' : 'false';
    $oDaoRecHumanoAtiv->ed22_i_codigo = null;

    if ( empty($this->iCodigo) ) {
      $oDaoRecHumanoAtiv->incluir(null);
    } else {

      $oDaoRecHumanoAtiv->ed22_i_codigo = $this->iCodigo;
      $oDaoRecHumanoAtiv->alterar($this->iCodigo);
    }

    $oMsgErro = new stdClass();
    if ( $oDaoRecHumanoAtiv->erro_status == 0 ) {

      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M(self::MSG_ATIVIDADEPROFISSIONALESCOLA . "erro_salvar", $oMsgErro) );
    }
    $this->iCodigo = $oDaoRecHumanoAtiv->ed22_i_codigo;

    /**
     * Salva e atualiza as agendas da atividade
     */
    foreach ($this->aAgendaAtividade as $oAgenda) {

      $oAgenda->setAtividadeProfissional($this);
      $oAgenda->salvar();
    }
  }


  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new DBException( _M(self::MSG_ATIVIDADEPROFISSIONALESCOLA . "sem_transacao") );
    }

    $aConflitos = $this->atividadeConflitaHorariosRegencia();
    if (count($aConflitos) > 0) {

      $sMsg  = _M(self::MSG_ATIVIDADEPROFISSIONALESCOLA . "conflito_horario_regencia" ) . "\n";
      $sMsg .= implode("\n", $aConflitos);
      throw new Exception($sMsg);
    }

    foreach ( $this->getAgenda() as $oAgenda ) {
      $oAgenda->excluir();
    }


    $oDaoRecHumanoAtiv = new cl_rechumanoativ();
    $oDaoRecHumanoAtiv->ed22_i_codigo = $this->iCodigo;
    $oDaoRecHumanoAtiv->excluir($this->iCodigo);

    $oMsgErro = new stdClass();
    if ( $oDaoRecHumanoAtiv->erro_status == 0 ) {

      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M(self::MSG_ATIVIDADEPROFISSIONALESCOLA . "erro_excluir", $oMsgErro) );
    }
    AtividadeProfissionalEscolaRepository::removerAtividade($this);

  }

  public function atividadeConflitaHorariosRegencia() {

    if ( ! $this->oAtividadeEscolar->permiteLecionar() ) {
      return array();
    }

    $aCodigosAgendas = array();
    foreach ($this->getAgenda() as $oAgenda) {
      $aCodigosAgendas[] = $oAgenda->getCodigo();
    }

    if (count($aCodigosAgendas) == 0) {
      return array();
    }

    $aWhere   = array();
    $aWhere[] = " ed22_i_rechumanoescola = {$this->oProfissionalEscola->getCodigo()} ";
    $aWhere[] = " ed01_c_docencia        = 'S' ";
    $aWhere[] = " ed129_codigo in (" . implode(", ", $aCodigosAgendas) . ")";
    $aWhere[] = " ed33_ativo is true ";
    $aWhere[] = " ed22_ativo is true ";

    $sWhere  = implode(" and ", $aWhere);

    $sCampos  = " distinct (ed17_h_inicio::time,  ed17_h_fim::time) overlaps (ed129_horainicio::time,  ed129_horafim::time) as conflita, ";
    $sCampos .= " trim(ed08_c_descr) as periodo, ed129_diasemana, ed17_h_inicio, ed17_h_fim, ed129_horainicio,  ed129_horafim";

    $oDaoRecHumanoAtiv = new cl_rechumanoativ();
    $sSqlValida        = $oDaoRecHumanoAtiv->sql_query_horarios_regencia(null, $sCampos, "ed129_diasemana", $sWhere);
    $rsValida          = db_query($sSqlValida);

    $oMsgErro = new stdClass();
    if (!$rsValida) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(self::MSG_ATIVIDADEPROFISSIONALESCOLA . "erro_validar_conflito", $oMsgErro) );
    }

    if (pg_num_rows($rsValida) == 0) {
      return array();
    }

    $iLinha     = pg_num_rows($rsValida);
    $aConflitos = array();
    for ($i = 0; $i < $iLinha; $i++) {

      $oDados = db_utils::fieldsMemory($rsValida, $i);

      if ( $oDados->conflita == 't' ) {

        $oMsgErro              = new stdClass();
        $oMsgErro->sDiaSemana  = DBDate::getLabelDiaSemana($oDados->ed129_diasemana - 1);
        $oMsgErro->sPeriodo    = $oDados->periodo;
        $oMsgErro->sHoraInicio = $oDados->ed17_h_inicio;
        $oMsgErro->sHoraFim    = $oDados->ed17_h_fim;

        $aConflitos[] = _M( self::MSG_ATIVIDADEPROFISSIONALESCOLA . "conflito_horario_periodo", $oMsgErro);

      }
    }
    return $aConflitos;
  }

  /**
   * Valida se a Atividade possui algum vínculo com Relação de Trabalho.
   * @return boolean
   */
  public function possuiRelacaoTrabalhoVinculado() {

    if ( empty($this->iCodigo) ) {
      return false;
    }

    $oDaoRelacao = new cl_rechumanorelacao();
    $sSqlRelacao = $oDaoRelacao->sql_query_file( null, 1, null, "ed03_i_rechumanoativ = {$this->iCodigo}");
    $rsRelacao   = db_query( $sSqlRelacao );

    if ( !$rsRelacao ) {
      throw new DBException( _M(self::MSG_ATIVIDADEPROFISSIONALESCOLA . "erro_buscar_vinculo_relacao_trabalho") );
    }

    if ( pg_num_rows($rsRelacao) > 0 ) {
      return true;
    }

    return false;
  }

  /**
   * Busca as relações de trabalho vínculadas a Função Exercída.
   * 
   * @return RelacaoTrabalho[]
   */
  public function getRelacoesTrabalho() {
    
    if ( count($this->aRelacoesTrabalho) == 0) {
      $this->aRelacoesTrabalho = RelacaoTrabalhoRepository::getByFuncaoExercida( $this );
    }
    return $this->aRelacoesTrabalho; 
  }
}
