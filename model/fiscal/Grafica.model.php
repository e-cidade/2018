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
 * Grafica
 * 
 * @uses CgmBase
 * @package Fiscal 
 * @author Jeferson Belmiro <jeferson.belmiro@dbseller.com.br> 
 */
class Grafica extends CgmBase {

  private $iCgm;
  
  
  
  /**
   * Reescreve o construtor, validando se cgm existe
   *
   * @param integer $iCgm
   * @access public
   * @return void
   */
  public function __construct($iCgm = null) {
		
    if ( empty($iCgm) ) {
      return;
    }

    $this->iCgm = $iCgm;
    
    /**
     * Verifica se o iCgm informado existe, e verifica se a data limite da grafica informada, é valida.s
     */
    $oDaoGrafica = db_utils::getDao("cgm");
    $sSqlGrafica = $oDaoGrafica->sql_query_file($this->iCgm);
    $rsGrafica   = $oDaoGrafica->sql_record($sSqlGrafica);

    if ( $oDaoGrafica->numrows == 0 ) {
      throw new Exception('Gráfica não encontrada: ' . $this->iCgm);
    }

    parent::__construct($iCgm);
  }

  public function validarGrafica() {

    /**
     * Verifica se o iCgm informado existe, e verifica se a data limite da grafica informada, é valida.s
     */
    $oDaoGrafica = db_utils::getDao("graficas");
    $sWhere  = " graficas.y20_grafica = '" . $this->iCgm . "'";
    $sWhere .= " AND (graficas.y20_datalimiteimpressao >= '" . date('Y-m-d') . "'";
    $sWhere .= " OR graficas.y20_datalimiteimpressao IS NULL)";
    $sSqlGrafica = $oDaoGrafica->sql_query_file($this->iCgm, '*', null, $sWhere);
    $rsGrafica   = $oDaoGrafica->sql_record($sSqlGrafica);

    if ( $oDaoGrafica->numrows == 0 ) {
      throw new Exception('Gráfica não encontrada: ' . $this->iCgm);
    }

  }

  /**
   * Salvar grafica
   *
   * @access public
   * @return bool 
   */
  public function salvar() {

    /**
     * Salva ou altera CGM 
     */
    parent::save();

    $oDaoGraficas = db_utils::getDao('graficas');
    $sSqlGraficas = $oDaoGraficas->sql_query_file($this->iCodigo);
    $rsGraficas   = db_query($sSqlGraficas);

    if ( !$rsGraficas ) {
      throw new Exception("Erro ao Buscar Gráfica: ". pg_last_error());  
    } 

    /**
     * Se já existe vinculação do CGM com Gráfica
     * Conclui execução
     */
    if ( pg_num_rows($rsGraficas) > 0 ) {
      return true;
    } 

    $oDaoGraficas                 = new cl_graficas();
    $oDaoGraficas->y20_grafica    = $this->iCodigo;
    $oDaoGraficas->y20_id_usuario = db_getsession('DB_id_usuario');
    $oDaoGraficas->y20_data       = date('Y-m-d', db_getsession('DB_datausu'));
    $oDaoGraficas->incluir($this->iCodigo);

    if ( $oDaoGraficas->erro_status == '0' ) {
      throw new DBException("Não Foi Possivel Definir CGM Como Gráfica." . $oDaoGraficas->erro_msg);
    } 

    return true;
  }

}