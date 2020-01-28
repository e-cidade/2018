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
 * Convenção lançada para amparos
 * 
 * @author andrio <andrio.costa@dbseller.com.br>
 * @package educacao
 * @version $Revision: 1.1 $
 */
class Convencao {
  
  /**
   * Código da convenção no sistema 
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Descrição da convenção
   * @var string
   */
  private $sDescricao;
  
  /**
   * Descrição abreviada 
   * @var string
   */
  private $sAbreviatura;

  /**
   * Busca e seta os dados da convenção caso informada
   * @param string $iCodigo
   */
  public function __construct( $iCodigo = null ) {
    
    if (!empty($iCodigo)) {
      
      $oDaoConvencao = new cl_convencaoamp();
      $sSqlConvencao = $oDaoConvencao->sql_query_file($iCodigo);
      $rsConvencao   = $oDaoConvencao->sql_record($sSqlConvencao);
      
      if ($oDaoConvencao->numrows == 1) {
        
        $oDado = db_utils::fieldsMemory($rsConvencao, 0);
        
        $this->iCodigo      = $oDado->ed250_i_codigo;
        $this->sDescricao   = $oDado->ed250_c_descr; 
        $this->sAbreviatura = $oDado->ed250_c_abrev; 
      }
    }
  }

  /**
   * retorna o código da convenção no sistema 
   * @return integer 
   */
  public function getCodigo() {
    
    return $this->iCodigo;
  }

  /**
   * retorna a descrição da convenção
   * @return string
   */
  public function getDescricao() {
    
    return $this->sDescricao;
  }

  /**
   * seta a descrição da convenção 
   * @param $sDescricao
   */
  public function setDescricao($sDescricao) {
    
    $this->sDescricao = $sDescricao;
  }

  /**
   * retorna a descrição abreviada da convenção
   * @return string
   */
  public function getAbreviatura() {
    
    return $this->sAbreviatura;
  }

  /**
   * seta a descrição abreviada da convenção 
   * @param $sAbreviatura
   */
  public function setAbreviatura($sAbreviatura) {
    
    $this->sAbreviatura = $sAbreviatura;
  }
}