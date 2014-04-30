<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * @package Habitacao
 *
 */
class DBAttDinamicoAtributo {
  
  public $iCodigo;
  
  public $iGrupoAtributo;
  
  public $sDescricao;
  
  public $oCampoReferencia;
  
  public $sValorDefault;
  
  public $iTipo;
  

  public function __construct($iCodigo = null) {
    
    if (!empty($iCodigo)) {
      
      
      $oDaoCadAttDinamicoAtributo = db_utils::getDao('db_cadattdinamicoatributos');
      
      $sSqlAtributos    = $oDaoCadAttDinamicoAtributo->sql_query_file($iCodigo);
      $rsDadosAtributos = $oDaoCadAttDinamicoAtributo->sql_record($sSqlAtributos);
      
      if ($oDaoCadAttDinamicoAtributo->numrows > 0 ) {
        
        $oAtributo = db_utils::fieldsMemory($rsDadosAtributos,0);
        
        $this->setCodigo       ($oAtributo->db109_sequencial);
        $this->setGrupoAtributo($oAtributo->db109_db_cadattdinamico);
        $this->setDescricao    ($oAtributo->db109_descricao);
        $this->setTipo         ($oAtributo->db109_tipo);
        $this->setValorDefault ($oAtributo->db109_valordefault);
        $this->setCampo        ($oAtributo->db109_codcam);
      }
    }
  }
  
  public function getCodigo() {
    return $this->iCodigo;
  }
  
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  public function getDescricao() {
    return $this->sDescricao;
  }
  
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }
  
  public function getGrupoAtributo() {
    return $this->iGrupoAtributo;
  }
  
  public function setGrupoAtributo($iGrupoAtributo) {
    $this->iGrupoAtributo = $iGrupoAtributo;
  }
  
  public function getCampo() {
    
    if ($this->oCampoReferencia == null) {
      return false;
    } else {
      return $this->oCampoReferencia;      
    }
  }
  
  public function setCampo($iCodCampo) {
    
    if ( trim($iCodCampo) != '') {
      
      $oDaoDBSysArqCamp = db_utils::getDao('db_sysarqcamp');
      $sCampos          = " db_syscampo.codcam,    ";
      $sCampos         .= " db_syscampo.nomecam,   ";
      $sCampos         .= " db_syscampo.descricao, ";
      $sCampos         .= " db_sysarquivo.nomearq  ";
      $sWhereCampos     = " db_sysarqcamp.codcam = {$iCodCampo} ";
      $sSqlDadosCampo   = $oDaoDBSysArqCamp->sql_query(null,null,null,$sCampos,null,$sWhereCampos);
      $rsDadosCampo     = $oDaoDBSysArqCamp->sql_record($sSqlDadosCampo);
      
      if ( $oDaoDBSysArqCamp->numrows > 0 ) {
        
        $oDadosCampo = db_utils::fieldsMemory($rsDadosCampo,0);
        
        $oCampoReferencia = new stdClass();
        $oCampoReferencia->iCodigo    = $oDadosCampo->codcam;
        $oCampoReferencia->sNome      = $oDadosCampo->nomecam;
        $oCampoReferencia->sDescricao = $oDadosCampo->descricao;
        $oCampoReferencia->sTabela    = $oDadosCampo->nomearq;
        
        $this->oCampoReferencia = $oCampoReferencia;
        
      } else {
        throw new Exception("Campo {$iCodCampo} invбlido!"); 
      }
    }
  }
  
  public function getTipo() {
    return $this->iTipo;
  }
  
  public function setTipo($iTipo) {
    $this->iTipo = $iTipo;
  }
  
  public function getValorDefault() {
    return $this->sValorDefault;
  }
  
  public function setValorDefault($sValorDefault) {
    $this->sValorDefault = $sValorDefault;
  }
  
  
  public function salvar() {
    
    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transaзгo com o banco de dados aberta!\\n\\nOperaзгo cancelada.");
    }    
    
    $oDaoCadAttDinamicoAtributo = db_utils::getDao('db_cadattdinamicoatributos');
      
    $oDaoCadAttDinamicoAtributo->db109_sequencial        = $this->getCodigo() ;
    $oDaoCadAttDinamicoAtributo->db109_db_cadattdinamico = $this->getGrupoAtributo();
    $oDaoCadAttDinamicoAtributo->db109_descricao         = $this->getDescricao();
    $oDaoCadAttDinamicoAtributo->db109_tipo              = $this->getTipo();
    $oDaoCadAttDinamicoAtributo->db109_valordefault      = $this->getValorDefault();

    $GLOBALS["HTTP_POST_VARS"]["db109_valordefault"]     = $this->getValorDefault();
    
    if ($this->getCampo()) {
      $oDaoCadAttDinamicoAtributo->db109_codcam          = $this->getCampo()->iCodigo;
    }
    
    
    if (trim($this->getCodigo()) == '') {
      $oDaoCadAttDinamicoAtributo->incluir(null);
    } else {
      $oDaoCadAttDinamicoAtributo->alterar($this->getCodigo());
    }
    
    if ($oDaoCadAttDinamicoAtributo->erro_status == 0) {
      throw new Exception($oDaoCadAttDinamicoAtributo->erro_msg);
    }
  }
  
  
  public function excluir() {
    
    if (!db_utils::inTransaction()) {
      throw new Exception("Nenhuma transaзгo com o banco de dados aberta!\\n\\nOperaзгo cancelada.");
    }    

    if (trim($this->getCodigo()) == '') {
      throw new Exception("Cуdigo do atributo nгo informado!");
    }
    
    $oDaoCadAttDinamicoAtributoValor = db_utils::getDao('db_cadattdinamicoatributosvalor');
    
    $sWhereAtributoValor = "db110_db_cadattdinamicoatributos = {$this->getCodigo()}";
    $sSqlAtributoValor   = $oDaoCadAttDinamicoAtributoValor->sql_query_file(null,"*",null,$sWhereAtributoValor);
    $rsAtributoValor     = $oDaoCadAttDinamicoAtributoValor->sql_record($sSqlAtributoValor);
    
    if ($oDaoCadAttDinamicoAtributoValor->numrows > 0) {
      throw new Exception("Operaзгo cancelada, Existem valores lanзados para o atributo informado!");
    }    

    
    $oDaoCadAttDinamicoAtributo      = db_utils::getDao('db_cadattdinamicoatributos');
    
    $oDaoCadAttDinamicoAtributo->excluir($this->getCodigo());
    
    if ($oDaoCadAttDinamicoAtributo->erro_status == 0) {
      throw new Exception($oDaoCadAttDinamicoAtributo->erro_msg);
    }
    
  }

}
?>