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
 * Classe para definiçao de filiacao do cidadao
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @package social
 * @version $Revision: 1.1 $
 */
class CidadaoFiliacao {

	/**
	 * Cidadao que é o pai ou mae
	 * @var Cidadao
	 */
  private $oCidadao = null;

  /**
   *Instancia do tipo familiar
   * @var TipoFamiliar
   */
  private $oTipoFamiliar = null;

  /**
   * Instancia uma nova filiacao
   * @param Cidadao $oCidadao
   * @param TipoFamiliar $oTipoFamiliar
   */
  public function __construct(Cidadao $oCidadao, TipoFamiliar $oTipoFamiliar) {

    $this->oCidadao      = $oCidadao;
    $this->oTipoFamiliar = $oTipoFamiliar;
  }

  /**
   * Retorna o Cidadao
   * @return Cidadao
   */
  public function getCidadao() {
    return $this->oCidadao;
  }


  /**
   * Retorna o tipo Familiar
   * @return TipoFamiliar
   */
  public function getTipoFiliacao() {
    return $this->oTipoFamiliar;
  }
}