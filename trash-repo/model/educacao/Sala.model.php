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
 * Informacoes da sala
 * @package   Educacao
 * @author    Fabio Esteves - fabio.esteves@dbseller.com.br
 * @version   $Revision: 1.1 $
 */
class Sala {
  
  /**
   * Codigo da sala
   * @var integer
   */
  private $iCodigoSala;
  
  /**
   * Descricao da sala
   * @var string
   */
  private $sDescricao;
  
  public function __construct($iCodigoSala = null) {
    
    if (!empty($iCodigoSala)) {
      
      $oDaoSala = db_utils::getDao("sala");
      $sSqlSala = $oDaoSala->sql_query_file($iCodigoSala);
      $rsSala   = $oDaoSala->sql_record($sSqlSala);
      
      if ($oDaoSala->numrows > 0) {
        
        $oDadosSala        = db_utils::fieldsMemory($rsSala, 0);
        $this->iCodigoSala = $oDadosSala->ed16_i_codigo;
        $this->sDescricao  = $oDadosSala->ed16_c_descr;
      }
    }
  }
  
  /**
   * Retorna o codigo da sala
   * @return integer
   */
  public function getCodigoSala() {
    return $this->iCodigoSala;
  }
  
  /**
   * Retorna a descricao da sala
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
}
?>