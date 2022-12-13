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
 * Comprovante de Entrega de Medicamento
 *
 */
define( "MSG_COMPROVANTE_ENTREGA_MEDICAMENTO", "saude.farmacia.ComprovanteEntregaMedicamento.");
class ComprovanteEntregaMedicamento {
  
  protected $iCodigo;
  
  protected $iTipoReceita;
  
  protected $oSolicitante;
  
  protected $aMedicamentos;
  
  protected $sHora;
  
  protected $sData;
  
  protected $iDepartamento;
  
  protected $sDescricaoDepartamento;
  
  protected $iCodigoMedico;
  
  protected $sNomeMedico;

  protected $oModeloComprovante;

  protected $iTipoRetirada;

  protected $sTipoRetirada = "Não Padronizada";

  /**
   * Data da retirada
   * @var DBDate
   */
  private $oDtRetirada;

  private $oParametrosFarmacia = null;

  /**
   * 
   */
  function __construct($iCodigo) {

    $oDaoFarRetirada = db_utils::getDao("far_retirada");
    $sSqlRetirada    = $oDaoFarRetirada->sql_query_dados_retirada($iCodigo);
    $rsDadosRetirada = $oDaoFarRetirada->sql_record($sSqlRetirada);
    if ($oDaoFarRetirada->numrows > 0) {

      $oDadosRetirada               = db_utils::fieldsMemory($rsDadosRetirada, 0);
      $this->iCodigo                = $iCodigo;
      $this->iCodigoMedico          = $oDadosRetirada->fa04_i_profissional;
      $this->sNomeMedico            = $oDadosRetirada->z01_nome;
      $this->sData                  = db_formatar($oDadosRetirada->fa04_d_data, "d");
      $this->sHora                  = $oDadosRetirada->m40_hora;  
      $this->oSolicitante              = new Cgs($oDadosRetirada->fa04_i_cgsund);
      $this->iDepartamento          = $oDadosRetirada->coddepto;
      $this->sDescricaoDepartamento = $oDadosRetirada->descrdepto;
      unset($oDadosRetirada);
    }

  }
  
  /**
   * Retorna os medicamentos dispensados
   * @return unknown
   */
  public function getMedicamentos() {
    
    if (count($this->aMedicamentos) == 0) {
      
      $oDaoFarRetirada = db_utils::getDao("far_retirada"); 
      $sCampos    = " distinct ";
      $sCampos   .= "fa04_i_codigo    as retirada_codigo,";
      $sCampos   .= "m60_codmater     as codigo,";
      $sCampos   .= "m60_descr        as nome,";
      $sCampos   .= "c.m61_abrev      as unidade,";
      $sCampos   .= "fa06_f_quant     as quantidade,";
      $sCampos   .= "fa06_t_posologia as posologia";
      $sWhere     = "fa04_i_codigo = {$this->iCodigo}";
      $sSqlItens  = $oDaoFarRetirada->sql_query_geral(null,$sCampos,'fa04_i_codigo', $sWhere);
      $rsItens    = $oDaoFarRetirada->sql_record($sSqlItens);

      if ( !$rsItens ) {
        throw new Exception( _M(MSG_COMPROVANTE_ENTREGA_MEDICAMENTO."erro_buscar_medicamentos"));
      }

      $oDaoMaterSaude = new cl_far_matersaude();
      $iLinhas = pg_num_rows($rsItens);
      for ($i = 0; $i < $iLinhas; $i++) {

        $oMedicamento   = db_utils::fieldsMemory($rsItens, $i);
        $lDinamico      = ParametrosFarmacia::tipoPeriodoContinuado() == 2;
        $lAcumularSaldo = $oParametrosFarmacia = ParametrosFarmacia::acumularSaldoContinuado() == 1;

        $sSqlSaldo = $oDaoMaterSaude->sql_saldo_continuado_periodo($lDinamico, $this->getSolicitante()->getCodigo(),
                                                                   $oMedicamento->codigo, $this->oDtRetirada->getDate(),
                                                                   $lAcumularSaldo );

        $rsSaldo = db_query($sSqlSaldo);
        if ( !$rsSaldo ) {
          throw new DBException( _M(MSG_COMPROVANTE_ENTREGA_MEDICAMENTO."erro_verificar_dispensacao"));
        }

        $aDadosSaldo = explode("#", db_utils::fieldsMemory($rsSaldo, 0)->proxima_dispensacao);

        $oMedicamento->oDtProximaDisponibilidade = null;
        $oMedicamento->sDtProximaDisponibilidade = null;
        if ( isset($aDadosSaldo[1]) && !empty($aDadosSaldo[1])) {

          $oMedicamento->oDtProximaDisponibilidade = new DBDate($aDadosSaldo[1]);
          $oMedicamento->sDtProximaDisponibilidade = $oMedicamento->oDtProximaDisponibilidade->convertTo(DBDate::DATA_PTBR);
        }

        $this->aMedicamentos[] = $oMedicamento;
      }

    }

    return $this->aMedicamentos;
  }
  
  /**
   * @return unknown
   */
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  /**
   * @return unknown
   */
  public function getDepartamento() {
    return $this->iDepartamento;
  }
  
  /**
   * Retorna o Solicitante do medicamento
   * @return Cgs
   */
  public function getSolicitante() {
    return $this->oSolicitante;
  }
  
  /**
   * @return unknown
   */
  public function getTipoReceita() {
    return $this->iTipoReceita;
  }
  
  /**
   * @return unknown
   */
  public function getCodigoMedico() {
    return $this->sCodigoMedico;
  }
  
  /**
   * @return unknown
   */
  public function getData() {
    return $this->sData;
  }
  
  /**
   * @return unknown
   */
  public function getDescricaoDepartamento() {
    return $this->sDescricaoDepartamento;
  }
  
  /**
   * @return unknown
   */
  public function getHora() {
    return $this->sHora;
  }
  
  /**
   * @return unknown
   */
  public function getNomeMedico() {
    return $this->sNomeMedico;
  }

  public function getTelefoneDepartamento() {
    return '';
  }
  /**
   * Imprime o Documento conforme regra
   * impressao apenas sera realizada caso a impressora tenha um modelo
   * vinculado
   */ 
  public function imprimir() {
    
    $this->oModeloComprovante = new ModeloComprovanteMedicamento();
    $this->oModeloComprovante->imprimir($this);
  }

  /**
   * Define o tipo de retirada
   * @param integer $iTipoRetirada
   */
  public function setTipoRetirada ($iTipoRetirada) {

    $this->iTipoRetirada = $iTipoRetirada;

    if ( $iTipoRetirada == 1 ) {
      $this->sTipoRetirada = "Normal";
    }
  }

  public function getDescricaoTipoRetirada() {

    return $this->sTipoRetirada;
  }


  /**
   * Define a data de retirada
   * @param DBDate $oDtRetirada
   */
  public function setDataRetirada( DBDate $oDtRetirada) {

    $this->oDtRetirada = $oDtRetirada;
  }


}