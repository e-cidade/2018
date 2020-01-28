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
 * Medicamento usado para análise dos exames
 * @author  Andrio Costa <andrio.costa@dbseller.com.br>
 * @package laboratorio
 * @version $Revision: 1.2 $
 */
class MedicamentoLaboratorio {

  const MSG_MEDICAMENTOLABORATORIO = "saude.laboratorio.MedicamentoLaboratorio.";

  /**
   * Código sequencial
   * @var integer
   */
  private $iCodigo = null;

  /**
   * Nome do medicamento
   * @var string
   */
  private $sNome;

  /**
   * Abreviatura do nome do medicamento
   * @var string
   */
  private $sAbreviatura;

  /**
   * @param  integer     $iCodigo primary key
   * @throws DBException
   */
  function __construct($iCodigo = null) {

    if ( empty($iCodigo) ) {
      return $this;
    }

    $oDaoMedicamento = new cl_medicamentoslaboratorio();
    $sSqlMedicamento = $oDaoMedicamento->sql_query_file($iCodigo);
    $rsMedicamento   = db_query($sSqlMedicamento);

    $oMsgErro = new stdClass();
    if ( !$rsMedicamento ) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(self::MSG_MEDICAMENTOLABORATORIO . "erro_buscar_medicamento", $oMsgErro) );
    }

    if ( pg_num_rows($rsMedicamento) > 0 ) {

      $oDados             = db_utils::fieldsMemory($rsMedicamento, 0);
      $this->iCodigo      = $oDados->la43_sequencial;
      $this->sNome        = $oDados->la43_nome;
      $this->sAbreviatura = $oDados->la43_abreviatura;
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
   * Setter nome
   * @param string
   */
  public function setNome ($sNome) {
    $this->sNome = $sNome;
  }

  /**
   * Getter nome
   * @param string
   */
  public function getNome () {
    return $this->sNome;
  }

  /**
   * Setter abreviatura
   * @param string
   */
  public function setAbreviatura ($sAbreviatura) {
    $this->sAbreviatura = $sAbreviatura;
  }

  /**
   * Getter abreviatura
   * @param string
   */
  public function getAbreviatura () {
    return $this->sAbreviatura;
  }
  
  /**
   * Salvar um medicamento
   * @return boolean
   * @throws DBException
   * @throws BusinessException
   */
  public function salvar () {
    
    $oDaoMedicamentosLaboratorio                   = new cl_medicamentoslaboratorio();
    $oDaoMedicamentosLaboratorio->la43_nome        = $this->sNome;
    $oDaoMedicamentosLaboratorio->la43_abreviatura = strtoupper( $this->sAbreviatura );

    $aWhereVerificaAbreviatura = array();
    $lAlterar                  = false;

    $aWhereVerificaAbreviatura[] = "la43_abreviatura = '{$this->sAbreviatura}'";    

    if( !empty($this->iCodigo) ) {

      $lAlterar = true;
      $aWhereVerificaAbreviatura[] = "la43_sequencial <> {$this->iCodigo}";
    }

    $sWhereVerificaAbreviatura = implode("and ", $aWhereVerificaAbreviatura);      
    $sSqlVerificaAbreviatura   = $oDaoMedicamentosLaboratorio->sql_query_file(null, 'la43_nome', null, $sWhereVerificaAbreviatura);
    $rsVerificaAbreviatura     = db_query($sSqlVerificaAbreviatura);

    if ( !$rsVerificaAbreviatura ){          
      throw new DBException( _M(self::MSG_MEDICAMENTOLABORATORIO . "erro_verificar_abreviatura") . "\n {$oDaoMedicamentosLaboratorio->erro_msg}" );
    }

    // Verifica se a abreviatura já está em uso por outro medicamento
    if ( pg_num_rows($rsVerificaAbreviatura) > 0 ){

      $oDadosMedicamento          = db_utils::fieldsMemory($rsVerificaAbreviatura, 0);
      $oMensagem                  = new stdClass();
      $oMensagem->nomeMedicamento = $oDadosMedicamento->la43_nome;

      throw new BusinessException( _M(self::MSG_MEDICAMENTOLABORATORIO . "abreviatura_ja_existe", $oMensagem) );
    }

    if ( $lAlterar ) {

      $oDaoMedicamentosLaboratorio->la43_sequencial = $this->iCodigo;
      $oDaoMedicamentosLaboratorio->alterar($this->iCodigo);        
    } else {
      $oDaoMedicamentosLaboratorio->incluir(null);        
    }      

    if ( $oDaoMedicamentosLaboratorio->erro_status == 0 ){
      throw new DBException( _M(self::MSG_MEDICAMENTOLABORATORIO . "erro_salvar_medicamento") . "\n {$oDaoMedicamentosLaboratorio->erro_msg}" );
    }    
    
    return true;
  }
}