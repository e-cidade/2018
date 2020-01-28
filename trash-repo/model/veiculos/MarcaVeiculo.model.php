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
 * Marcas de Veiculos
 * @author Iuri Guntchnigg
 * @package Veiculos
 * @version $Revision: 1.1 $
 *
 */
class MarcaVeiculo {

  /**
   * codigo da marca
   * @var integer
   */
  protected $iCodigo;

  /**
   * nome da marca
   * @var string
   */
  protected $sNome;

  /**
   * Instancia uma marca
   * @param integer $iCodigo codigo da marca
   */
  public function __construct($iCodigo) {

    if (!empty($iCodigo)) {

      $oDaoMarca = new cl_veiccadmarca();
      $sSqlMarca = $oDaoMarca->sql_query_file($iCodigo);
      $rsMarca   = $oDaoMarca->sql_record($sSqlMarca);
      if ($oDaoMarca->numrows > 0) {

        $oDadosMarca   = db_utils::fieldsMemory($rsMarca, 0);
        $this->iCodigo = $oDadosMarca->ve21_codigo;
        $this->sNome   = $oDadosMarca->ve21_descr;
      }
    }
  }

  /**
   * retorna o codigo da marca
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o nome da marca
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }
}