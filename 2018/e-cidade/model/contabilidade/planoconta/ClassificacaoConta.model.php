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
 *
 * Classe para Classificações de contas Contabeis
 * @author dbseller
 * @name ClassificacaoConta
 * @package contabilidade
 * @subpackage planoconta
 */
final class ClassificacaoConta {
   
  private $iCodigoClasse;
  private $sDescricao;
  
  public function __construct($iCodigoClasse) {
  
   if ($iCodigoClasse != '') {
   
     $oDaoClassificacaoConta  = db_utils::getDao("conclass");
     $sSqlClassificacaoConta  = $oDaoClassificacaoConta->sql_query_file($iCodigoClasse);
     $rsClassificacaoConta    = $oDaoClassificacaoConta->sql_record($sSqlClassificacaoConta);
   
     if ($oDaoClassificacaoConta->numrows > 0) {
     
       $oClassificacaoConta = db_utils::fieldsMemory($rsClassificacaoConta, 0);
       $this->setCodigoClasse($oClassificacaoConta->c51_codcla);
       $this->setDescricao($oClassificacaoConta->c51_descr);
       unset($oClassificacaoConta);
     }
   }
  }
 
  /**
   *
   * @return
   */
  public function getCodigoClasse() {
      return $this->iCodigoClasse;
  }
  
  /**
   *
   * @param $iCodigoClasse
   */
  public function setCodigoClasse($iCodigoClasse) {
      $this->iCodigoClasse = $iCodigoClasse;
  }
  
  /**
   *
   * @return
   */
  public function getDescricao() {
      return $this->sDescricao;
  }
  
  /**
   *
   * @param $sDescricao
   */
  public function setDescricao($sDescricao)  {
      $this->sDescricao = $sDescricao;
  }
}