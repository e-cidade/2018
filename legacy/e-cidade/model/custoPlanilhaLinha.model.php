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
 * linha de uma planilha de custos
 * @package custos
 */
class custoPlanilhaLinha {
  
  protected $iCodigo            = null;
  
  protected $nValor             = null;
  
  protected $nQuantidade        = null;
  
  protected $iDesdobramento     = null;
  
  protected $iOrigem            = null;
  
  protected $sDescricaoElemento = null;
  
  protected $sDescricaoOrigem   = null;
  
  protected $iCodigoOrigem      = null;
  
  protected $lAutomatico        = true;
  
  protected $iContaPlano        = null;
  /**
   * 
   */
  function __construct($id = null,  $nQuantidade = 0, $nValor = 0, $iContaPlano = null, $iDesdobramento = null) {
   
     $this->nValor         = $nValor;
     $this->nQuantidade    = $nQuantidade;
     $this->iDesdobramento = $iDesdobramento;
     $this->iContaPlano    = $iContaPlano;
     if (!empty($id)) {
       
       $oDaoCustoLinha  = db_utils::getdao("custoplanilhaapuracao");
       $sSqlDadosLinha  = $oDaoCustoLinha->sql_query_custo($id,"*");
       $rsDadosLinha    = $oDaoCustoLinha->sql_record($sSqlDadosLinha);
       if ($oDaoCustoLinha->numrows  == 1) {

         $oDadosLinha          = db_utils::fieldsMemory($rsDadosLinha, 0);
         $this->nValor         = $oDadosLinha->cc17_valor;
         $this->nQuantidade    = $oDadosLinha->cc17_quantidade;
         $this->iDesdobramento = $oDadosLinha->cc17_valor;
         $this->iContaPlano    = $oDadosLinha->cc17_custoplanoanalitica;
         $this->iOrigem        = $oDadosLinha->cc17_custoplanilhaorigem;
         $this->iCodigoOrigem  = null;
         switch ($oDadosLinha->cc17_custoplanilhaorigem) {
           
           
         	case 3:
         	
         	  $this->iCodigoOrigem  = $oDadosLinha->cc18_custoapropria;
         	  break;
         	  
         	case 4:
          
            $this->iCodigoOrigem  = $oDadosLinha->cc20_matordemitemcustocriterio;
            break;
              
         	case 5:
          
            $this->iCodigoOrigem  = $oDadosLinha->cc20_matordemitemcustocriterio;
            break;

          case 6:
          
            $this->iCodigoOrigem  = $oDadosLinha->cc20_matordemitemcustocriterio;
            break;
         }
         $this->iCodigo        = $oDadosLinha->cc17_sequencial;
       }
     }
     return $this;
  }
  
  /**
   * @return unknown
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * @return unknown
   */
  public function getDesdobramento() {

    return $this->iDesdobramento;
  }
  
  /**
   * @param unknown_type $iDesdobramento
   */
  public function setDesdobramento($iDesdobramento) {

    $this->iDesdobramento = $iDesdobramento;
  }
  
  /**
   * @return unknown
   */
  public function getOrigem() {

    return $this->iOrigem;
  }
  /**
   * Define a origem do custo
   *
   * @param  integr $iOrigem codigo da origem do custo
   * @return custoPlanilhaLinha
   */
  public function setOrigem($iOrigem) {

    $this->iOrigem = $iOrigem;
    return $this;
    
  }
  /**
   * @return unknown
   */
  public function getQuantidade() {

    return $this->nQuantidade;
  }
  
  /**
   * @return unknown
   */
  public function getValor() {

    return $this->nValor;
  }
  
  /**
   * @return unknown
   */
  public function getDescricaoElemento() {

    return $this->sDescricaoElemento;
  }

  /**
   * @return unknown
   */
  public function setDescricaoElemento($sDescricaoElemento) {
    
    return $this->sDescricaoElemento = $sDescricaoElemento;
  }
  
  /**
   * @return unknown
   */
  public function getDescricaoOrigem() {

    return $this->sDescricaoOrigem;
  }
  
  /**
   * @return unknown
   */
  public function setDescricaoOrigem($sDescricaoOrigem) {
    $this->sDescricaoOrigem = $sDescricaoOrigem;
  }
  
  public function setAutomatico($lAutomatico) {
    $this->lAutomatico = $lAutomatico;
  }
  
  public function getAutomatico() {
    
    return $this->lAutomatico;
  }
  /**
   * @return unknown
   */
  public function getContaCusto() {

    return $this->iContaPlano;
  }
  
 /**
   * @return unknown
   */
  public function setCodigoOrigem($iCodigoOrigem) {
    $this->iCodigoOrigem = $iCodigoOrigem;  
  }
  /**
   * 
   */
  function __destruct() {

  }
  
  function save($iPlanilha) {
    
    
    $lIncluir = true;
    $oDaoPlanilhaLinha  = db_utils::getDao("custoplanilhaapuracao");
    $oDaoPlanilhaLinha->cc17_custoplanilha       = $iPlanilha;
    $oDaoPlanilhaLinha->cc17_custoplanilhaorigem = $this->getOrigem();
    $oDaoPlanilhaLinha->cc17_custoplanoanalitica = $this->getContaCusto();
    $oDaoPlanilhaLinha->cc17_quantidade          = $this->getQuantidade();
    $oDaoPlanilhaLinha->cc17_valor               = $this->getValor();
    if ($this->getCodigo() != null) {
      
      $oDaoPlanilhaLinha->cc17_sequencial = $this->getCodigo();
      $oDaoPlanilhaLinha->alterar($this->getCodigo());
      $lIncluir = false;
      
    } else {
      
      $oDaoPlanilhaLinha->incluir(null);
      $this->iCodigo = $oDaoPlanilhaLinha->cc17_sequencial;
      
    }
    
    if ($oDaoPlanilhaLinha->erro_status == 0) {
      throw new Exception("Nao foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".$oDaoPlanilhaLinha->erro_msg);
    }
    /**
     * Incluimos o elemento para o custo 
     */
    $oDaoPlanilhaLinhaElemento = db_utils::getDao("custoplanilhaapuracaoelemento");
    if ($this->getCodigo() != null) {
     
      $sWhere = "cc19_custoplanilhaapuracao = {$this->getCodigo()}";
      $sSqlVerificaElemento = $oDaoPlanilhaLinhaElemento->sql_query_file(null,"*", null, $sWhere);
      $rsVerificaElemento   = $oDaoPlanilhaLinhaElemento->sql_record($sSqlVerificaElemento);
      if ($oDaoPlanilhaLinhaElemento->numrows > 0) {

        $oElementoCusto = db_utils::fieldsMemory($rsVerificaElemento, 0);
        $oDaoPlanilhaLinhaElemento->cc19_codele = $this->getDesdobramento();
        $oDaoPlanilhaLinhaElemento->cc19_anousu = db_getsession("DB_anousu");
        $oDaoPlanilhaLinhaElemento->cc19_sequencial = $oElementoCusto->cc19_sequencial;
        $oDaoPlanilhaLinhaElemento->alterar($oElementoCusto->cc19_sequencial);
        if ($oDaoPlanilhaLinhaElemento->erro_status == 0) {
          throw new Exception("Nao foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".$oDaoPlanilhaLinhaElemento->erro_msg);
        }
        
      } else if ($this->getDesdobramento() != null) {

        $oDaoPlanilhaLinhaElemento->cc19_codele     = $this->getDesdobramento();
        $oDaoPlanilhaLinhaElemento->cc19_custoplanilhaapuracao= $this->getCodigo();
        $oDaoPlanilhaLinhaElemento->cc19_anousu     = db_getsession("DB_anousu");
        $oDaoPlanilhaLinhaElemento->cc19_automatico = $this->getAutomatico()==true?"true":"false";
        $oDaoPlanilhaLinhaElemento->incluir(null);
        if ($oDaoPlanilhaLinhaElemento->erro_status == 0) {
          throw new Exception("Nao foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".$oDaoPlanilhaLinhaElemento->erro_msg);
        }  
      }
      
    }
    /**
     * Incluimos a Origem do custo
     */
    if ($this->getCodigo() != null  && $lIncluir) {
      
      switch ($this->getOrigem()) {
      	
        
        case 1:
          
          $oDaoCustoLocalTrab = db_utils::getDao("custoplanilhaapuracaolocaltrab");
          $oDaoCustoLocalTrab->cc21_custoplanilhaapuracao =  $this->getCodigo();
          $oDaoCustoLocalTrab->cc21_rhpeslocaltrab        = $this->iCodigoOrigem;
          $oDaoCustoLocalTrab->incluir(null);
          if ($oDaoCustoLocalTrab->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;
          
        case 2:
          
          $oDaoCustoLocalTrab = db_utils::getDao("custoplanilhaapuracaolocaltrab");
          $oDaoCustoLocalTrab->cc21_custoplanilhaapuracao =  $this->getCodigo();
          $oDaoCustoLocalTrab->cc21_rhpeslocaltrab        = $this->iCodigoOrigem;
          $oDaoCustoLocalTrab->incluir(null);
          if ($oDaoCustoLocalTrab->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;
        case 3:
      	 
          $oDaoPlanilhaLinhaAproria                             = db_utils::getDao("custoplanilhacustoapropria");
          $oDaoPlanilhaLinhaAproria->cc18_custoapropria         = $this->iCodigoOrigem;
          $oDaoPlanilhaLinhaAproria->cc18_custoplanilhaapuracao = $this->getCodigo();
          $oDaoPlanilhaLinhaAproria->incluir(null);
          if ($oDaoPlanilhaLinhaAproria->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
      	  break;
      	  
        case 4:
          
          $oDaoPlanilhaLinhaOrdemCompra = db_utils::getDao("custoplanilhamatordemitem");
          $oDaoPlanilhaLinhaOrdemCompra->cc20_matordemitemcustocriterio = $this->iCodigoOrigem;
          $oDaoPlanilhaLinhaOrdemCompra->cc20_custoplanilhaapuracao     = $this->getCodigo();
          $oDaoPlanilhaLinhaOrdemCompra->incluir(null);
          if ($oDaoPlanilhaLinhaOrdemCompra->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;
          
        case 5:
          
          $oDaoPlanilhaLinhaOrdemCompra = db_utils::getDao("custoplanilhamatordemitem");
          $oDaoPlanilhaLinhaOrdemCompra->cc20_matordemitemcustocriterio = $this->iCodigoOrigem;
          $oDaoPlanilhaLinhaOrdemCompra->cc20_custoplanilhaapuracao     = $this->getCodigo();
          $oDaoPlanilhaLinhaOrdemCompra->incluir(null);
          if ($oDaoPlanilhaLinhaOrdemCompra->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;
          
        case 6:
          
          $oDaoPlanilhaLinhaOrdemCompra = db_utils::getDao("custoplanilhamatordemitem");
          $oDaoPlanilhaLinhaOrdemCompra->cc20_matordemitemcustocriterio = $this->iCodigoOrigem;
          $oDaoPlanilhaLinhaOrdemCompra->cc20_custoplanilhaapuracao     = $this->getCodigo();
          $oDaoPlanilhaLinhaOrdemCompra->incluir(null);
          if ($oDaoPlanilhaLinhaOrdemCompra->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;
          
        case 7:
          
          $oDaoPlanilhaLinhaOrdemCompra = db_utils::getDao("custoplanilhamatordemitem");
          $oDaoPlanilhaLinhaOrdemCompra->cc20_matordemitemcustocriterio = $this->iCodigoOrigem;
          $oDaoPlanilhaLinhaOrdemCompra->cc20_custoplanilhaapuracao     = $this->getCodigo();
          $oDaoPlanilhaLinhaOrdemCompra->incluir(null);
          if ($oDaoPlanilhaLinhaOrdemCompra->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;  
      	default:
      		
      	break;
      }
    }
  }
  
  public function remover() {
    
    if ($this->iCodigo != null) {
      
      $oDaoPlanilhaLinhaElemento = db_utils::getDao("custoplanilhaapuracaoelemento");
      $oDaoPlanilhaLinhaElemento->excluir(null,"cc19_custoplanilhaapuracao = {$this->iCodigo}");
      if ($oDaoPlanilhaLinhaElemento->erro_status == 0) {
        throw new Exception("Erro ao remover Custo!\n{$oDaoPlanilhaLinhaElemento->erro_msg}");
      }
      
      switch ($this->getOrigem()) {
        
        
        case 1:
          
         $oDaoPlanilhaLinhaLocalTrab   = db_utils::getDao("custoplanilhaapuracaolocaltrab");
         $oDaoPlanilhaLinhaLocalTrab->excluir(null,"cc21_custoplanilhaapuracao = {$this->getCodigo()}");
         if ($oDaoPlanilhaLinhaLocalTrab->erro_status == 0){
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
         }
         break;
         
       case 2:
          
         $oDaoPlanilhaLinhaLocalTrab   = db_utils::getDao("custoplanilhaapuracaolocaltrab");
         $oDaoPlanilhaLinhaLocalTrab->excluir(null,"cc21_custoplanilhaapuracao = {$this->getCodigo()}");
         if ($oDaoPlanilhaLinhaLocalTrab->erro_status == 0){
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
         }
         break;
         
        case 3:
         
          $oDaoPlanilhaLinhaAproria   = db_utils::getDao("custoplanilhacustoapropria");
          $oDaoPlanilhaLinhaAproria->excluir(null,"cc18_custoplanilhaapuracao = {$this->getCodigo()}");
          if ($oDaoPlanilhaLinhaAproria->erro_status == 0){
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;
          
        case 4:
          
          $oDaoPlanilhaLinhaOrdemCompra = db_utils::getDao("custoplanilhamatordemitem");
          $oDaoPlanilhaLinhaOrdemCompra->excluir(null,"cc20_custoplanilhaapuracao = {$this->getCodigo()}");
          if ($oDaoPlanilhaLinhaOrdemCompra->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;
          
        case 5:
          
          $oDaoPlanilhaLinhaOrdemCompra = db_utils::getDao("custoplanilhamatordemitem");
          $oDaoPlanilhaLinhaOrdemCompra->excluir(null,"cc20_custoplanilhaapuracao = {$this->getCodigo()}");
          if ($oDaoPlanilhaLinhaOrdemCompra->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;
          
        case 6:
          
          $oDaoPlanilhaLinhaOrdemCompra = db_utils::getDao("custoplanilhamatordemitem");
          $oDaoPlanilhaLinhaOrdemCompra->excluir(null,"cc20_custoplanilhaapuracao = {$this->getCodigo()}");
          if ($oDaoPlanilhaLinhaOrdemCompra->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;
            
        case 7:
          
          $oDaoPlanilhaLinhaOrdemCompra = db_utils::getDao("custoplanilhamatordemitem");
          $oDaoPlanilhaLinhaOrdemCompra->excluir(null,"cc20_custoplanilhaapuracao = {$this->getCodigo()}");
          if ($oDaoPlanilhaLinhaOrdemCompra->erro_status == 0) {
             throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
          }
          break;      
      }
      $oDaoPlanilhaLinha  = db_utils::getDao("custoplanilhaapuracao");
      $oDaoPlanilhaLinha->excluir($this->getCodigo());
      if ($oDaoPlanilhaLinha->erro_status == 0) {
        throw new Exception("Erro [2]  - Não foi possivel salvar custo para a conta {$this->getContaCusto()}.\n".pg_last_error());
      }
    }
   return true;    
  }
}

?>