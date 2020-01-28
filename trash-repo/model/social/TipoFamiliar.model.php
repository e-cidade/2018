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
 * Tipo de composicao de uma familia
 * @author dbseller
 *
 */
class TipoFamiliar {

  private $iCodigo;

  private $sDescricao;

  public function __construct($iTipoFamiliar) {

    if (!empty($iTipoFamiliar)) {

      if (!DBNumber::isInteger($iTipoFamiliar)) {
        throw new ParameterException('Código do tipo familiar deve ser um inteiro');
      }
      $oDaoTipoFamiliar = new cl_tipofamiliar();
      $sSqlTipoFamiliar = $oDaoTipoFamiliar->sql_query_file($iTipoFamiliar);
      $rsTipoFamiliar   = $oDaoTipoFamiliar->sql_record($sSqlTipoFamiliar);
      if (!$rsTipoFamiliar || $oDaoTipoFamiliar->numrows == 0) {
        throw new BusinessException("Tipo familiar de Codigo {$iTipoFamiliar} não cadastrado no sistema");
      }

      $this->iCodigo    = $iTipoFamiliar;
      $this->sDescricao = db_utils::fieldsMemory($rsTipoFamiliar, 0)->z14_descricao;
    }
  }

  /**
   * Retorna o codigo do tipo familiar
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descricao do tipo familiar
   */
  public function getDescricao() {
    return $this->sDescricao;
  }
}
?>