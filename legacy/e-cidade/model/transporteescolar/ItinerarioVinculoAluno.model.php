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
 * V�nculo entre Itiner�rio e Aluno
 * @author Andr� Mello
 * @package transporteescolar
 * @version $Revision: 1.4 $
 */
Class ItinerarioVinculoAluno{
  
  private $iCodigo;
  /**
   * Inst�ncia de Aluno
   * @var Aluno
   */
  private $oAluno;

  /**
   * Inst�ncia de ItinerarioPontoParada
   * @var ItinerarioPontoParada
   */
  private $oItinerarioPontoParada;

  /**
   * Inst�ncia de Hor�rio
   * @var LinhaItinerarioHorario
   */
  private $oLinhaItinerarioHorario;

  /**
   * Inst�ncia de VeiculoTransporte
   * @var VeiculoTransporte
   */
  private $oVeiculo;

  /**
   * C�digo do v�nculo entre hor�rio e ve�culo
   * @var integer
   */
  private $iLinhaTransporteHorarioVeiculo;

  /**
   * Observa��es do v�nculo do aluno
   * @var String
   */
  private $sObservacao;

  /**
   * Construtor da classe
   * @param integer $iLinhaTransportePontoParadaAluno
   */
  public function __construct($iLinhaTransportePontoParadaAluno = null) {

    if (!empty($iLinhaTransportePontoParadaAluno)) {

      $sCampos = "linhatransportepontoparadaaluno.*, tre08_linhatransportehorario, tre08_veiculotransportemunicipal";

      $oDaoVinculoAluno = new cl_linhatransportepontoparadaaluno();
      $sSqlVinculoAluno = $oDaoVinculoAluno->sql_query( $iLinhaTransportePontoParadaAluno, $sCampos ); 
      $rsVinculoAluno   = db_query($sSqlVinculoAluno);

      if ($rsVinculoAluno && pg_num_rows($rsVinculoAluno) > 0) {

        $oDados                               = db_utils::fieldsMemory( $rsVinculoAluno , 0);
        $this->iCodigo                        = $oDados->tre12_sequencial;
        $this->oItinerarioPontoParada         = new ItinerarioPontoParada($oDados->tre12_linhatransportepontoparada);
        $this->oAluno                         = AlunoRepository::getAlunoByCodigo($oDados->tre12_aluno);
        $this->iLinhaTransporteHorarioVeiculo = $oDados->tre12_linhatransportehorarioveiculo;
        $this->sObservacao                    = $oDados->tre12_observacao;
        $this->oLinhaItinerarioHorario        = new LinhaItinerarioHorario($oDados->tre08_linhatransportehorario);
        $this->oVeiculo                       = new VeiculoTransporte($oDados->tre08_veiculotransportemunicipal);
      }
    }
  }

  /**
   * Retorna o c�digo da inst�ncia
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna um objeto Aluno
   * @return Aluno
   */
  public function getAluno() {
    return $this->oAluno;
  }

  /**
   * Seta uma inst�ncia de Aluno
   * @param Aluno $oAluno
   */
  public function setAluno( Aluno $oAluno ) {
    $this->oAluno = $oAluno;
  }

  /**
   * Retorna um objeto ItinerarioPontoParada
   * @return ItinerarioPontoParada
   */
  public function getItinerarioPontoParada() {
    return $this->oItinerarioPontoParada;
  }

  /**
   * Seta uma inst�ncia de ItinerarioPontoParada
   * @param ItinerarioPontoParada $oItinerarioPontoParada
   */
  public function setItinerarioPontoParada( ItinerarioPontoParada $oItinerarioPontoParada) {
    $this->oItinerarioPontoParada = $oItinerarioPontoParada;
  } 

  /**
   * Retorna uma inst�ncia de LinhaItinerarioHorario
   * @return LinhaItinerarioHorario
   */
  public function getLinhaItinerarioHorario() {
    return $this->oLinhaItinerarioHorario;
  }

  /**
   * Seta uma inst�ncia de LinhaItinerarioHorario
   * @param LinhaItinerarioHorario $oLinhaItinerarioHorario
   */
  public function setLinhaItinerarioHorario( LinhaItinerarioHorario $oLinhaItinerarioHorario) {
    $this->oLinhaItinerarioHorario = $oLinhaItinerarioHorario;
  }

  /**
   * Retorna inst�ncia de VeiculoTransporte
   * @return VeiculoTransporte
   */
  public function getVeiculo() {
    return $this->oVeiculo;
  }

  /**
   * Seta uma inst�ncia de VeiculoTransporte
   * @param VeiculoTransporte $oVeiculo
   */
  public function setVeiculo( VeiculoTransporte $oVeiculo ) {
    $this->oVeiculo = $oVeiculo;
  }

  /**
   * Retorna o c�digo da LinhaTransporteHorarioVeiculo
   * @return integer
   */
  public function getLinhaTransporteHorarioVeiculo() {
    return $this->iLinhaTransporteHorarioVeiculo;
  }

  /**
   * Seta o c�digo da tabela LinhaTransporteHorarioVinculo
   * @param integer $iLinhaTransporteHorarioVeiculo 
   */
  public function setLinhaTransporteHorarioVeiculo( $iLinhaTransporteHorarioVeiculo ) {
    $this->iLinhaTransporteHorarioVeiculo = $iLinhaTransporteHorarioVeiculo;
  }

  /**
   * Retorna uma observa��o do v�nculo
   * @return String
   */
  public function getObservacao() { 
    return $this->sObservacao;
  }

  /**
   * Seta uma observa��o para o v�nculo
   * @param String $sObservacao
   */
  public function setObservacao( $sObservacao ) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * M�todo respons�vel por salvar o v�nculo de Itiner�rio e Aluno
   */
  public function salvar() {

    /**
     * Verifica se o aluno possui v�nculo para o itiner�rio, ponto de parada e linha selecionados
     */
    $oDaoVerificaVinculo    = new cl_linhatransportepontoparadaaluno();
    $sWhereVerificaVinculo  = "     tre12_linhatransportepontoparada = {$this->oItinerarioPontoParada->getCodigo()} ";
    $sWhereVerificaVinculo .= " AND tre12_aluno = {$this->oAluno->getCodigoAluno()} ";
    $sSqlVerificaVinculo    = $oDaoVerificaVinculo->sql_query_file( null, '1', null, $sWhereVerificaVinculo );
    $rsVerificaVinculo      = db_query( $sSqlVerificaVinculo );

    if( $rsVerificaVinculo && pg_num_rows( $rsVerificaVinculo ) > 0 ) {
      throw new BusinessException( "Aluno j� vinculado a linha, ponto de parada e itiner�rio selecionados." );
    }

    $oDaoVinculoAluno                                      = new cl_linhatransportepontoparadaaluno();
    $oDaoVinculoAluno->tre12_sequencial                    = $this->iCodigo;
    $oDaoVinculoAluno->tre12_linhatransportepontoparada    = $this->oItinerarioPontoParada->getCodigo();
    $oDaoVinculoAluno->tre12_aluno                         = $this->oAluno->getCodigoAluno();
    $oDaoVinculoAluno->tre12_observacao                    = "{$this->sObservacao}";
    $oDaoVinculoAluno->tre12_linhatransportehorarioveiculo = $this->iLinhaTransporteHorarioVeiculo;

    if ( empty($this->iCodigo) ) {

      $oDaoVinculoAluno->incluir(null);
      $this->iCodigo = $oDaoVinculoAluno->tre12_sequencial;
    } else {

      $oDaoVinculoAluno->tre12_sequencial = $this->getCodigo();
      $oDaoVinculoAluno->alterar($this->getCodigo());
    }

    if ( $oDaoVinculoAluno->erro_status == 0) {
      throw new DBException("Erro ao salvar os dados do v�nculo: \n {$oDaoVinculoAluno->erro_msg}", 1);
      
    }
  }

  /**
   * Exclui o vinculo do aluno com a linha no hor�rio selecionado
   * @throws BusinessException
   * @throws DBException
   */
  public function remover() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException("Sem transa��o");
    }

    $oDaoVinculoAluno = new cl_linhatransportepontoparadaaluno();
    $oDaoVinculoAluno->excluir($this->iCodigo);

    if ( $oDaoVinculoAluno->erro_status == 0 ) {
      throw new BusinessException("Erro ao excluir vinculo aluno. \n{$oDaoVinculoAluno->erro_msg}");
    }

  }
}