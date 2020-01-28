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

require_once("model/CgmBase.model.php");
require_once("libs/exceptions/DBException.php");

/**
 * Classe utilizada para representar um Escritório. 
 * @author Rafael Nery <rafael.nery@dbseller.com.br>
 * @author Renan Melo  <renan@dbseller.com.br>
 */
class EscritorioContabil extends CgmBase {

  /**
   * Reescreve o construtor, validando se cgm existe
   *
   * @param integer $iCgm
   * @access public
   * @return void
   */
  public function __construct($iCgm = 0) {

    if ( empty($iCgm) ) {
      return;
    }

    $oDaoCgm = db_utils::getDao("cgm");
    $sSqlCgm = $oDaoCgm->sql_query_file($iCgm);
    $rsCgm   = $oDaoCgm->sql_record($sSqlCgm);

    if ($oDaoCgm->numrows == 0) {
      throw new Exception('Número do CGM não existe: ' . $iCgm);
    }

    parent::__construct($iCgm);
  }

  /**
   * Salvar escritorio
   *
   * @access public
   * @return bool
   */
  public function salvar() {
    
    /**
     * Salva ou altera CGM 
     */
    parent::save();
      
    $oDaoCadEscrito = db_utils::getDao('cadescrito');
    $sSqlCadEscrito = $oDaoCadEscrito->sql_query_file($this->iCodigo, 'q86_numcgm');
    $rsCadEscrito   = db_query($sSqlCadEscrito);

    if ( !$rsCadEscrito ) {
      throw new Exception("Erro ao Buscar validar existencia de Escritório contábil". pg_last_error());  
    }
    
    /**
     * Se já existe vinculação do CGM com escritório
     * Conclui execução
     */
    if ( pg_num_rows($rsCadEscrito) > 0 ) {
      return true;
    }

    $oDaoCadEscrito->q86_numcgm = $this->iCodigo;
    $oDaoCadEscrito->incluir($this->iCodigo);

    if ( $oDaoCadEscrito->erro_status == "0" ) {
      throw new DBException("Não Foi Possivel Definir CGM Como Escritório Contábil." . $oDaoCadEscrito->erro_msg);
    }

    return true; 
  } 
  

  /**
   * Retorna as empresas vinculadas ao escritório
   * @return $oEmpresasVinculadas
   */
  public static function getInscricaoVinculadaEscritorio($iCgmEscritorio, $iIcricaoMunicipal) {

    $oEscrito    = new cl_escrito();
    $sWhere      = "q10_numcgm = {$iCgmEscritorio} and q10_inscr = {$iIcricaoMunicipal}" ;
    $sSqlEscrito = $oEscrito->sql_query_file(null, '*', null, $sWhere);
    $rsEscrito   = $oEscrito->sql_record($sSqlEscrito);
    
    if (pg_numrows($rsEscrito) == 0) {
      return false;
    }
    
    return true;
  }
}