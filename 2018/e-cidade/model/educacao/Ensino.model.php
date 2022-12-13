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

define( 'MENSAGEM_ENSINO', 'educacao.escola.Ensino.' );

/**
 * Classe para tipo de ensino da Educacao
 * @author Iuri Guntchnigg
 * @package Educacao
 * @version $Revision: 1.11 $
 */
class Ensino {
  
  /**
   * Constante para Tipo de Ensino
   */
  const ENSINO_REGULAR = 1; 
  const ENSINO_EJA     = 3;

  /**
   * Codigo do Ensino;
   * var integer
   */
  protected $iCodigo;
  
  /**
   * Nome do Ensino;
   * @var string
   */
  protected $sNome;
  
  /**
   * Abreviatura do ensino
   * @var string
   */
  protected $sAbreviatura;

  /**
   * Código do tipo de Ensino
   *   1 | ENSINO REGULAR               | ER
   *   2 | EDUCAÇÃO ESPECIAL            | ES
   *   3 | EDUCAÇÃO DE JOVENS E ADULTOS | EJ
   *   4 | EDUCAÇÃO PROFISSIONAL        | EP
   * @var integer
   */ 
  protected $iCodigoTipoEnsino;
  
  /**
   * Construtor da classe. Recebe o código de ensino
   * @param integer $iCodigo
   */
  public function __construct($iCodigo = null) {
    
    if (!empty($iCodigo)) {
      
      $oDaoEnsino = db_utils::getDao("ensino");
      $sSqlEnsino = $oDaoEnsino->sql_query_file($iCodigo);
      $rsEnsino   = $oDaoEnsino->sql_record($sSqlEnsino);
      
      if ($oDaoEnsino->numrows == 1) {
        
        $oEnsino                 = db_utils::fieldsMemory($rsEnsino, 0);
        $this->sNome             = $oEnsino->ed10_c_descr;
        $this->sAbreviatura      = $oEnsino->ed10_c_abrev;
        $this->iCodigoTipoEnsino = $oEnsino->ed10_i_tipoensino;
      }
      
    }
    $this->iCodigo = $iCodigo;
  }
  
  
  /**
   * Retorna o codigo do ensino
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * Define o nome do ensino
   * @param string $sNome nome do ensino
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }
  
  /**
   * Retorna o nome do ensino
   * @return string
   */
  public function getNome() {
    return $this->sNome;    
  }
  
  /**
   * Retorna a abreviatura do ensino
   * @return string
   */
  public function getAbreviatura() {
    return $this->sAbreviatura;
  }
  
  /**
   * Seta uma abreviatura para o ensino
   * @param string $sAbreviatura
   */
  public function setAbreviatura($sAbreviatura) {
    $this->sAbreviatura = $sAbreviatura;
  }


  /**
   * Verifica se o ensino esta informado como ensino infantil
   * @return boolean 
   */
  public function isInfantil() {

    $oDaoEnsino   = new cl_ensinoinfantil();
    $sWhere       = " ed117_ensino  = {$this->iCodigo} ";
    $sSqlInfantil = $oDaoEnsino->sql_query_file(null, "1", null, $sWhere);
    $rsInfantil   = db_query($sSqlInfantil);

    if( !$rsInfantil ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error( $rsInfantil );
      throw new DBException( _M( MENSAGEM_ENSINO . 'erro_verificar_ensino_infantil', $oErro ) );
    }

    if ($rsInfantil &&  pg_num_rows($rsInfantil) == 1) {
      return true;
    } 
    return false;
  }

  
  /**
   * Verifica se ensino possui turma ativa
   * @throw DBException
   * @return boolean
   */
  public function temTurmaAtiva() {

    $oDaoRegencia = new cl_regencia();
    $sWhere       = "     ed10_i_codigo = {$this->iCodigo}";
    $sWhere      .= " and ed59_c_encerrada = 'N'";

    $sSqlTurmaAtiva = $oDaoRegencia->sql_query ( null, " 1 ", null, $sWhere );
    $rsTurmaAtiva   = db_query( $sSqlTurmaAtiva );
    
    if ( !$rsTurmaAtiva ) {
      throw new DBException(_M('educacao.escola.Ensino.erro_verificar_ensino_possui_turma_ativa'));
    }

    if ( pg_num_rows($rsTurmaAtiva) > 1) {
      return true;
    }

    return false;
  }

  /**
   * Método responsável por vincular ensino a ensino infantil
   * @throw DBException
   * @throw BusinessException
   * @return boolean
   */
  public function salvarVinculoInfantil() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException(_M('educacao.escola.Ensino.sem_transacao_ativa'));
    }

    if ( $this->isInfantil() ) {

      $oMensagem              = new stdClass();
      $oMensagem->sNomeEnsino = $this->sNome;
      throw new BusinessException(_M('educacao.escola.Ensino.ensino_ja_possui_vinculo', $oMensagem));
    }

    $oDaoEnsinoInfantil                   = new cl_ensinoinfantil();
    $oDaoEnsinoInfantil->ed117_sequencial = null;
    $oDaoEnsinoInfantil->ed117_ensino     = $this->iCodigo;

    $oDaoEnsinoInfantil->incluir(null);
    
    if ( $oDaoEnsinoInfantil->erro_status == 0 ) {
      throw new DBException(_M('educacao.escola.Ensino.ensino_infantil_nao_vinculado'));
    }

    return true;
  }

  /**
   * Remove o vínculo do ensino com o ensinoinfantil
   * @throw DBException
   * @throw BusinessException
   * @return boolean
   */
  public function removerVinculoInfantil() {

    if ($this->isInfantil() && $this->temTurmaAtiva()) {

      $oMensagem              = new stdClass();
      $oMensagem->sNomeEnsino = $this->sNome;
      throw new BusinessException(_M('educacao.escola.Ensino.ensino_com_turma_ativa', $oMensagem));
    }

    $sWhere = "ed117_ensino = {$this->iCodigo}";

    $oDaoEnsinoInfantil = new cl_ensinoinfantil();
    $oDaoEnsinoInfantil->excluir(null, $sWhere);

    if ( $oDaoEnsinoInfantil->erro_status == 0) {

      $oMensagem              = new stdClass();
      $oMensagem->sNomeEnsino = $this->sNome;
      throw new BDException( _M('educacao.escola.Ensino.erro_excluir_vinculo', $oMensagem) );
    }
    return true;
  }
  
  /**
   * Retorna os termos de resultado final para o ensino
   * @param integer $iAno - Ano para dos termos a serem verificados
   * @return array
   */
  public function getTermosResultadoFinal ($iAno) {
    
    $sCampos    = "ed110_descricao, ed110_abreviatura, ed110_referencia";
    $sWhere     = "     ed110_ensino = {$this->iCodigo} ";
    $sWhere    .= " and ed110_ano = {$iAno}";
    $oDaoTermo  = new cl_termoresultadofinal();
    $sSqlTermos = $oDaoTermo->sql_query_file(null, $sCampos, null, $sWhere);
    $rsTermos   = db_query($sSqlTermos);

    if( !$rsTermos ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error( $rsTermos );
      throw new DBException( _M( MENSAGEM_ENSINO . 'erro_buscar_termos', $oErro ) );
    }

    $aTermos = array();
    if ( $rsTermos && pg_num_rows($rsTermos) > 0 ) {
      
      $iLinhas = pg_num_rows($rsTermos);
      
      for ( $i = 0; $i < $iLinhas; $i ++ ) {
        $aTermos[] = db_utils::fieldsMemory($rsTermos, $i);
      }
    }
      
    return $aTermos;
  }

  /**
   * Retorna o Tipo de Ensino
   * @return integer
   */
  public function getCodigoTipoEnsino() {
    return $this->iCodigoTipoEnsino;
  }

  /**
   * Define o Tipo de Ensino
   * @param integer $iCodigoTipoEnsino
   */
  public function setCodigoTipoEnsino( $iCodigoTipoEnsino ) {
    $this->iCodigoTipoEnsino = $iCodigoTipoEnsino;
  }
}
?>