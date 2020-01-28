<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
 * Tipos de resultados das Avaliacoes
 * NOTA FINAL, PARECER FINAL, CONCEITO FINAL
 * @package educacao
 * @subpackage avaliacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 *         Iuri Guntchnigg <iuri@dbseller.com.br>  
 * @version $Revision: 1.1 $
 */
class TipoResultado {
  
  private $iCodigo; 
  private $sDescricao;  
  private $sDescricaoAbreviada;
  
  /**
   * Construtor da classe
   * @param integer $iCodigo do tipo de resultado
   */
  public function __construct($iCodigo = '') {
    
    if (!empty($iCodigo)) {
      
      $oDaoResultado = db_utils::getDao("resultado");
      $sSqlResultado = $oDaoResultado->sql_query_file($iCodigo);
      $rsResultado   = $oDaoResultado->sql_record($sSqlResultado);
      if ($oDaoResultado->numrows > 0) {
        
        $oDadosResultado = db_utils::fieldsMemory($rsResultado, 0);
        $this->setCodigo($oDadosResultado->ed42_i_codigo);
        $this->setDescricao($oDadosResultado->ed42_c_descr);
        $this->setDescricaoAbreviada($oDadosResultado->ed42_c_abrev);
        unset($oDadosResultado);
      }
    }
  }
 

  /**
   * Define o codigo do tipo do resultado
   * @param integer $iCodigo codigo do Resultado
   */
  protected function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  /**
   * Retorna o codigo do tipo de resultado
   * @return integer
   */
  public function getCodigo() {
    
    return $this->iCodigo;
  }
  /**
   * Define a descricao do tipo de resultado
   * @param string $sDescricao string com a descricao do resultado. ex.:(nota final, resultado parcial)
   */
  public function setDescricao($sDescricao) {

    $this->sDescricao = $sDescricao;
  }
  
  /**
   * Retorna a descricao do tipo de resultado
   * @return string
   */
  public function getDescricao() {
  
    return $this->sDescricao;
  }
  
  /**
   * Define a descricao abreviada do tipo
   * @param string $sDescricaoAbreviada abreviatura do tipo
   */
  public function setDescricaoAbreviada($sDescricaoAbreviada){
  
    $this->sDescricaoAbreviada = $sDescricaoAbreviada;
  }
  
  /**
   * Retorna a abreviatura do tipo do resultado
   * @return string
   */
  public function getDescricaoAbreviada() {
  
    return $this->sDescricaoAbreviada;
  }
  
}