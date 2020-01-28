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
 * Classe responsavel pelos débidos do CGM
 * @package ISSQN
 * @author Renan Melo <renan@dbseller.com.br>
 */
class Debitos{

  /**
   * Número de inscrição
   * @var integer
   */
  private $iInscricao;

  public function __construct($iInscricao = null) {

    if (!$iInscricao) {
      return false;
    }

    $this->setInscricao($iInscricao);
  }

  /**
   * Define a inscrição
   * @param integer $iInscricao
   */
  public function setInscricao($iInscricao) {
    $this->iInscricao = $iInscricao;
  }

  /**
   * Retorna a Inscrição
   * @return integer $iInscricao
   */
  public function getInscricao() {
    return $iInscricao;
  }

  /**
   * Retorna os Débitos que estão vencidos a partir da data informada.
   * @param  String $sDataVencimento Data de sDataVencimento.
   * @return Object                  Débitos.
   */
  public function getDebitosVencidos($sDataVencimento) {

    $oDaoArreInscr  = db_utils::getDao('arreinscr');

    $sWhereDebitos  = " arreinscr.k00_inscr = {$this->iInscricao}      ";
    $sWhereDebitos .= " and arrecad.k00_dtvenc < '$sDataVencimento'";

    $sSqlDebitos  = $oDaoArreInscr->sql_query_arrecad(null,null, "arrecad.*", null, $sWhereDebitos);
    $rsSqlDebitos = $oDaoArreInscr->sql_record($sSqlDebitos);

    $oDebitosVencidos = db_utils::getColectionByRecord($rsSqlDebitos);

    return $oDebitosVencidos;
  }
}

?>