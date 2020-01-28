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
 * Classe responsável por gerir as transferências dos alunos após o encerramento do ano letivo.
 * @package    educacao
 * @subpackage transferencia
 * @author     Andrio Costa <andrio.costa@dbseller.com.br>
 * @version    $Revision: 1.3 $
 */
class TransferenciaLote {

  const MSG_TRANSFERENCIA_LOTE    = "educacao.escola.TransferenciaLote.";
  const MOVIMENTACAO_PROCEDIMENTO = "TRANSFERÊNCIA APÓS ENCERRAMENTO";

  /**
   * codigo do lote
   * @var integer
   */
  private $iCodigo;

  /**
   * Escola de origem, quem realizou a transferência
   * @var Escola
   */
  private $oEscolaOrigem;

  /**
   * Usuário que realizou a transferência
   * @var UsuarioSistema
   */
  private $oUsuario;


  /**
   * Se a escola de destino é da Rede ou de Fora da rede
   * @var boolean
   */
  private $lEscolaRede = true;

  /**
   * Escola para qual o(s) alunos foi/foram encaminhado(s)
   * ATENÇÃO : nem todos metodos da escola estão assinados na interface.
   * @var IEscola
   */
  private $oEscolaDestino = null;

  /**
   * Data no formato timestamp
   * @example 2016-01-01 22:00:11
   * @var string
   */
  private $sDateTime;

  /**
   * Todos alunos que foram transferidos neste lote
   * @var Matricula[]
   */
  private $aMatriculas = array();

  function __construct($iCodigo = null) {

    if (empty($iCodigo)) {
      return $this;
    }

    $oDao = new cl_transferencialote();
    $sSql = $oDao->sql_query_file($iCodigo);
    $rs   = db_query($sSql);

    if ( !$rs ) {
      throw new DBException( _M(self::MSG_TRANSFERENCIA_LOTE . "erro_buscar_dados") );
    }

    if ( pg_num_rows($rs) == 0 ) {
      throw new BusinessException( _M(self::MSG_TRANSFERENCIA_LOTE . "nenhuma_transferencia_encotrada_para_codigo") );
    }

    $oDados              = db_utils::fieldsMemory($rs, 0);
    $this->iCodigo       = $oDados->ed137_sequencial;
    $this->oEscolaOrigem = EscolaRepository::getEscolaByCodigo($oDados->ed137_escolaorigem);
    $this->oUsuario      = UsuarioSistemaRepository::getPorCodigo($oDados->ed137_usuario);
    $this->lEscolaRede   = $oDados->ed137_escolarede == 't';
    $this->sDateTime     = $oDados->ed137_data;

    if ($this->lEscolaRede ) {
      $this->oEscolaDestino = EscolaRepository::getEscolaByCodigo($oDados->ed137_escola);
    } else {
      $this->oEscolaDestino = EscolaProcedenciaRepository::getEscolaByCodigo($oDados->ed137_escola);
    }
  }

  /**
   * Getter codigo
   * @param integer
   */
  public function getCodigo () {
    return $this->iCodigo;
  }

  /**
   * Setter escola de origem
   * @param Escola
   */
  public function setEscolaOrigem (Escola $oEscola) {
    $this->oEscolaOrigem = $oEscola;
  }

  /**
   * Getter escola de origem
   * @param Escola
   */
  public function getEscola () {
    return $this->oEscolaOrigem;
  }

  /**
   * Setter usuário que realizou transferência
   * @param UsuarioSistema
   */
  public function setUsuario ($oUsuario) {
    $this->oUsuario = $oUsuario;
  }

  /**
   * Getter usuário que realizou transferência
   * @param UsuarioSistema
   */
  public function getUsuario () {
    return $this->oUsuario;
  }

  /**
   * Setter escola de Destino
   * @param IEscola
   */
  public function setEscolaDestino ( IEscola $oEscola) {

    $this->oEscolaDestino = $oEscola;
    $this->lEscolaRede    = false;

    if ($oEscola instanceof Escola ) {
      $this->lEscolaRede = true;
    }
  }

  /**
   * Getter escola de Destino
   * @param IEscola
   */
  public function getEscolaDestino () {
    return $this->oEscolaDestino;
  }

  /**
   * Retorna true se a escola de destino é da rede, false se de fora da rede
   * @return boolean  true se da rede
   */
  public function isEscolaDestinoRede() {
    return $this->lEscolaRede;
  }

  public function addMatricula(Matricula $oMatricula) {

    if ( !in_array($oMatricula->getCodigo(), $this->aMatriculas) ) {
      $this->aMatriculas[$oMatricula->getCodigo()] = $oMatricula;
    }
  }

  /**
   * Retorna as matrículas transferidas
   */
  public function getMatriculas() {

    if ( empty($this->iCodigo) ) {
      return array();
    }

    if ( count($this->aMatriculas) > 0 ) {
      return $this->aMatriculas;
    }

    $oDao = new cl_transferencialotematricula();
    $sSql = $oDao->sql_query_file(null, "ed138_matricula", null, "ed138_transferencialote = $this->iCodigo");
    $rs   = db_query($sSql);

    if ( !$rs ) {
      throw new DBException( _M(self::MSG_TRANSFERENCIA_LOTE . "erro_buscar_alunos") );
    }

    $iLinhas = pg_num_rows($rs);
    for ($i = 0; $i < $iLinhas; $i++) {
      $this->addMatricula(MatriculaRepository::getMatriculaByCodigo(db_utils::fieldsMemory($rs, $i)->ed138_matricula));
    }

    return $this->aMatriculas;
  }


  public function salvar() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException("Sem transação ativa com o banco de dados.");
    }

    if ( count($this->aMatriculas) == 0 ) {
      throw new BusinessException(_M(self::MSG_TRANSFERENCIA_LOTE . "nenhum_aluno_informado"));
    }

    $this->sDateTime = date('Y-m-d H:i:s');

    $oDaoTransferencia                     = new cl_transferencialote();
    $oDaoTransferencia->ed137_sequencial   = null;
    $oDaoTransferencia->ed137_escolaorigem = $this->oEscolaOrigem->getCodigo();
    $oDaoTransferencia->ed137_usuario      = $this->oUsuario->getCodigo();
    $oDaoTransferencia->ed137_escolarede   = $this->lEscolaRede ? 't' : 'f';
    $oDaoTransferencia->ed137_data         = $this->sDateTime;
    $oDaoTransferencia->ed137_escola       = $this->oEscolaDestino->getCodigo();

    $oDaoTransferencia->incluir(null);
    if ( $oDaoTransferencia->erro_status == 0 ) {
      throw new DBException( _M(self::MSG_TRANSFERENCIA_LOTE . "erro_salvar") . $oDaoTransferencia->erro_msg);
    }

    $this->iCodigo = $oDaoTransferencia->ed137_sequencial;
    $this->transferirAlunos();
  }

  /**
   * Realiza a movimentação da transferência do aluno
   */
  private function transferirAlunos() {

    $oDaoMatriculaTransferida = new cl_transferencialotematricula();
    $this->validarSeAlunoJaNaoFoiTransferido();

    $aMatriculas = $this->getMatriculas();
    foreach ($aMatriculas as $oMatricula) {

      $oDaoMatriculaTransferida->ed138_sequencial        = null;
      $oDaoMatriculaTransferida->ed138_transferencialote = $this->iCodigo;
      $oDaoMatriculaTransferida->ed138_matricula         = $oMatricula->getCodigo();
      $oDaoMatriculaTransferida->incluir(null);

      if ( $oDaoMatriculaTransferida->erro_status == 0 ) {
        throw new DBException( _M(self::MSG_TRANSFERENCIA_LOTE . "erro_salvar_aluno") );
      }

      $sEscolaOrigem  = $this->oEscolaOrigem->getNome();
      $sEscolaDestino = $this->oEscolaDestino->getNome();

      $sTipoDestino = "de fora da rede";
      if ($this->lEscolaRede) {
        $sTipoDestino = "da rede";
      }

      $sMovimentacao  = "Aluno foi transferido após encerramento das avaliações da escola {$sEscolaOrigem} para ";
      $sMovimentacao .= "escola {$sTipoDestino} {$sEscolaDestino}.";

      $oMatricula->atualizarMovimentacao($sMovimentacao, self::MOVIMENTACAO_PROCEDIMENTO, new DBDate(date('Y-m-d')));
    }
  }


  /**
   * Valida se os alunos adicionados já forão transferidos
   * @throws DBException
   * @throws BusinessException
   * @return boolean se tudo ok
   */
  private function validarSeAlunoJaNaoFoiTransferido() {

    $aMatriculas       = $this->getMatriculas();
    $aCodigoMatriculas = array_keys( $aMatriculas );

    $sWhere = " ed138_matricula in (" . implode(",", $aCodigoMatriculas) . ")";
    $oDao   = new cl_transferencialotematricula();
    $sSql   = $oDao->sql_query_file(null, "ed138_matricula", null, $sWhere);
    $rs     = db_query($sSql);

    if ( !$rs ) {
      throw new DBException( _M(self::MSG_TRANSFERENCIA_LOTE . "erro_ao_validar_matriculas") );
    }

    $iLinhas = pg_num_rows($rs);
    if ( $iLinhas == 0 ) {
      return true;
    }

    $aAlunosJaTransferidas = array();
    for ($i = 0; $i < $iLinhas; $i++) {

      $iCodigoMatricula        = db_utils::fieldsMemory($rs, $i)->ed138_matricula;
      $oMatricula              = $aMatriculas[$iCodigoMatricula];
      $aAlunosJaTransferidas[] = $oMatricula->getAluno()->getCodigoAluno() ." - " . $oMatricula->getAluno()->getNome();
    }

    $oMsgErro         = new stdClass();
    $oMsgErro->sAluno = implode(", ", $aAlunosJaTransferidas);
    throw new BusinessException( _M(self::MSG_TRANSFERENCIA_LOTE . "alunos_transferidos", $oMsgErro) );
  }

  /**
   * Alunar transferência após o encerramento.
   * @param  Matricula $oMatricula
   */
  public function anularTranferenciaMatricula( Matricula $oMatricula ) {

    $oMsgErro         = new stdClass();
    $oMsgErro->sAluno = $oMatricula->getAluno()->getNome();
    if ( !array_key_exists($oMatricula->getCodigo(), $this->getMatriculas()) ) {
      throw new BusinessException( _M(self::MSG_TRANSFERENCIA_LOTE . "aluno_nao_encontrado_nesta_transeferencia", $oMsgErro) );
    }

    // valida se aluno já não possui uma matrícula posterior
    $oUltimaMatricula  = MatriculaRepository::getUltimaMatriculaAluno($oMatricula->getAluno());
    $oMsgErro->sEscola = $oUltimaMatricula->getTurma()->getEscola()->getNome();
    $oMsgErro->sTurma  = $oUltimaMatricula->getTurma()->getDescricao();
    if ( $oUltimaMatricula->getCodigo() > $oMatricula->getCodigo() ) {
      throw new BusinessException( _M(self::MSG_TRANSFERENCIA_LOTE . "aluno_ja_possui_nova_matricula", $oMsgErro) );
    }

    $sWhere = " ed138_matricula = {$oMatricula->getCodigo()} ";
    $oDaoMatriculaTransferida = new cl_transferencialotematricula();
    $oDaoMatriculaTransferida->excluir(null, $sWhere);
    if ( $oDaoMatriculaTransferida->erro_status == 0 ) {
      throw new DBException( _M(self::MSG_TRANSFERENCIA_LOTE . "erro_anular_transferencia", $oMsgErro) );
    }

    // gerar movimentação de anulação -> $oMatricula->atualizarMovimentacao
    $sMovimentacao = "Transferência cancelada.";
    $oMatricula->atualizarMovimentacao($sMovimentacao, self::MOVIMENTACAO_PROCEDIMENTO, new DBDate(date('Y-m-d')));

    // se não sobrou alunos no lote, excluir lote
    $this->excluirLoteTransferencia();
  }

  /**
   * Remove o lote da transferência quando não existe mais alunos transferidos.
   * @return boolean
   */
  private function excluirLoteTransferencia() {

    $sWhere = " ed138_transferencialote = {$this->getCodigo()} ";

    $oDaoMatriculaTransferida = new cl_transferencialotematricula();

    $sSqlMatricula = $oDaoMatriculaTransferida->sql_query_file( null, "1", null, $sWhere);
    $rsMatricula   = db_query($sSqlMatricula);
    if ( !$rsMatricula ) {
      throw new DBException( _M(self::MSG_TRANSFERENCIA_LOTE . "erro_buscar_alunos") );
    }
    // se tem registros significa que ainda tem alunos transferidos no lote
    if ( pg_num_rows($rsMatricula) > 0) {
      return;
    }

    $oDaoTransferencia = new cl_transferencialote();
    $oDaoTransferencia->excluir($this->getCodigo());

    if ( $oDaoTransferencia->erro_status == 0 ) {
      throw new DBException( _M(self::MSG_TRANSFERENCIA_LOTE . "erro_excluir_lote") );
    }

    return true;
  }

}