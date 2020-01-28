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
 * Classe para controle de VisitaTipo, referente a uma rotina de visita a uma familia
 * @author Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package social
 */
class VisitaTipo {
  
  /**
   * Codigo de VisitaTipo
   * @var integer
   */
  private $iCodigo;
  
  /**
   * Descricao de VisitaTipo
   * @var string
   */
  private $sDescricao;
  
  /**
   * Controla se exige encaminhamento
   * @var boolean
   */
  private $lExigeEncaminhamento;
  
  /**
   * Construtor da classe. Recebe como parametro um codigo de VisitaTipo. Caso seja diferente de vazio, setamos as 
   * demais propriedades
   * @param integer $iCodigo
   */
  public function __construct($iCodigo) {
    
    if (!empty($iCodigo)) {
      
      $oDaoVisitaTipo = new cl_visitatipo();
      $sSqlVisitaTipo = $oDaoVisitaTipo->sql_query_file($iCodigo);
      $rsVisitaTipo   = $oDaoVisitaTipo->sql_record($sSqlVisitaTipo);
      
      if ($oDaoVisitaTipo->numrows > 0) {
        
        $oDadosRetorno              = db_utils::fieldsMemory($rsVisitaTipo, 0);
        $this->iCodigo              = $oDadosRetorno->as13_sequencial;
        $this->sDescricao           = $oDadosRetorno->as13_descricao;
        $this->lExigeEncaminhamento = $oDadosRetorno->as13_exigeencaminhamento;
      }
    }
  }

  /**
   * Retorna o codigo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o codigo
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna a descricao
   * @return
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta uma descricao
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna se exige encaminhamento
   * @return boolean
   */
  public function getExigeEncaminhamento() {
    return $this->lExigeEncaminhamento;
  }

  /**
   * Seta se exige encaminhamento
   * @param boolean
   * @param unknown $lExigeEncaminhamento
   */
  public function setExigeEncaminhamento($lExigeEncaminhamento) {
    $this->lExigeEncaminhamento = $lExigeEncaminhamento;
  }
}