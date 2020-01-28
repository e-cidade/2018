<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * classe para controle dos Tipos de transporte
 * @author dbseller
 * @package transporteescolar
 */
class TipoTransporte {

  /**
   * Codigo do tipo de transporte
   * @var integer
   */
  protected $iCodigo = null;

  /**
   * Descrica do tipo
   * @var string
   */
  protected $sDescricao = '';

  /**
   * instancia um tipo de transporte
   * @param integer $iCodigo codigo do tipo de transporte que deve ser instanciado
   */
  public function __construct($iCodigo) {

    if (!empty($iCodigo)) {

      $oDaoTipoTransporte = new cl_tipotransportemunicipal();
      $sSqlTipoTransporte = $oDaoTipoTransporte->sql_query_file($iCodigo);
      $rsTipoTransporte   = $oDaoTipoTransporte->sql_record($sSqlTipoTransporte);
      if ($oDaoTipoTransporte->numrows == 0) {

        $sMensagem                  = "educacao.transportescolar.TipoTransporte.tipo_nao_encontrado";
        $oVariaveis                 = new stdClass();
        $oVariaveis->codigo_veiculo = $iCodigo;
        throw new ParameterException(_M($sMensagem, $oVariaveis));
      }

      $oDadosTipoTransporte = db_utils::fieldsMemory($rsTipoTransporte, 0);
      $this->iCodigo        = $iCodigo;
      $this->sDescricao     = $oDadosTipoTransporte->tre00_descricao;
    }
  }

  /**
   * Retorna o codigo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descricao do tipo de transporte
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
}