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


class convenio {
	
  private $iModalidadeConvenio  = null;
  private $iCodConvenio		      = null;
  private $iFormatoVenc 	      = null;	
  private $sLinhaDigitavel 	    = "";
  private $sSegmento  		      = "";
  private $sCodigoBarra	 	      = "";
  private $iCodBanco	 	        = "";
  private $iCodAgencia	 	      = "";
  private $iDigAgencia          = "";
  private $sCarteira	 	        = "";
  private $sVariacao	 	        = "";
  private $sConvenioArrecadacao	= "";
  private $sConvenioCobranca	  = "";  
  private $sCedente  		        = "";
  private $sNossoNumero			    = "";
  private $sCampoLivre			    = "";
  private $sTipoConvenio        = "";
  private $iCadTipoConvenio     = "";
  private $sDigitoCedente       = "";
  private $sOperacao            = "";
  private $sEspecie             = "";
  
  
  function __construct($iCodConvenio="",$iNumpre,$iNumpar,$sValor,$sVlrbar,$dDataVenc,$iTercDig) {
	
  	if(empty($iCodConvenio)){
      throw new Exception("Nenhum cуdigo de convкnio informado!");
  	}
  	
  	$sSqlConvenio  = " select ar12_cadconveniomodalidade,	                                                                         		         ";
  	$sSqlConvenio .= "        ar12_sigla,                                                                                                      ";
    $sSqlConvenio .= "        ar12_sequencial,                                                                                                 ";
  	$sSqlConvenio .= "        ar11_sequencial,                                                         									                       ";  	
    $sSqlConvenio .= "        ar13_carteira,                                                         						                   			       ";    
    $sSqlConvenio .= "        ar13_variacao,                                                                         									         ";    
    $sSqlConvenio .= "        ar13_operacao,                                                                                                   ";
    $sSqlConvenio .= "        ar13_cedente,                                                         									                         ";
    $sSqlConvenio .= "        ar13_especie,                                                         									                         ";
    $sSqlConvenio .= "        ar13_digcedente,                                                                                                 ";
    $sSqlConvenio .= "        ar13_convenio,                                                          									                       ";    
    $sSqlConvenio .= "        ar16_convenio,                                                          									                       ";  	
    $sSqlConvenio .= "        ar16_segmento,                                                         									   	                     ";
    $sSqlConvenio .= "        ar16_formatovenc,                                                           									                   ";
    $sSqlConvenio .= "        case 																											                                                       ";
	  $sSqlConvenio .= "          when ar13_sequencial is not null then a.db89_db_bancos  else b.db89_db_bancos								                   ";
 	  $sSqlConvenio .= "        end as codbco, 																								                                                   ";
	  $sSqlConvenio .= "        case 																											                                                       ";
	  $sSqlConvenio .= "          when ar13_sequencial is not null then a.db89_codagencia                     								                   ";
	  $sSqlConvenio .= "          else b.db89_codagencia								   									                                                     ";
	  $sSqlConvenio .= "        end as codagencia, 																							                                                 ";
    $sSqlConvenio .= "        case                                                                                                             ";
    $sSqlConvenio .= "          when ar13_sequencial is not null then a.db89_digito                                                            ";
    $sSqlConvenio .= "          else b.db89_digito                                                                                             ";
    $sSqlConvenio .= "        end as digagencia                                                                                                ";    	      
    $sSqlConvenio .= "   from cadconvenio                                                               									                     ";
    $sSqlConvenio .= "        inner join cadtipoconvenio     on cadtipoconvenio.ar12_sequencial      = cadconvenio.ar11_cadtipoconvenio        ";
    $sSqlConvenio .= "        left  join conveniocobranca    on conveniocobranca.ar13_cadconvenio    = cadconvenio.ar11_sequencial      	     ";
	  $sSqlConvenio .= "        left  join bancoagencia  a     on a.db89_sequencial                    = conveniocobranca.ar13_bancoagencia	     ";
    $sSqlConvenio .= "        left  join convenioarrecadacao on convenioarrecadacao.ar14_cadconvenio = cadconvenio.ar11_sequencial     		     ";
	  $sSqlConvenio .= "        left  join bancoagencia  b     on b.db89_sequencial                    = convenioarrecadacao.ar14_bancoagencia   ";    
    $sSqlConvenio .= "        left  join cadarrecadacao      on cadarrecadacao.ar16_sequencial 		   = convenioarrecadacao.ar14_cadarrecadacao ";
	  $sSqlConvenio .= "  where ar11_sequencial = {$iCodConvenio}																				                                         ";    

		$rsConvenio = pg_query($sSqlConvenio);
		$iNroLinhas = pg_num_rows($rsConvenio);
		
		if ( $iNroLinhas > 0 ) {
		  
		  $oConvenio = db_utils::fieldsMemory($rsConvenio,0); 	
	
		  $this->iModalidadeConvenio  = $oConvenio->ar12_cadconveniomodalidade;
		  $this->iCodConvenio  		    = $oConvenio->ar11_sequencial;
		  $this->sSegmento    		    = $oConvenio->ar16_segmento;
		  $this->iFormatoVenc 		    = $oConvenio->ar16_formatovenc;
		  $this->sConvenioArrecadacao = $oConvenio->ar16_convenio;
	   	$this->iCodBanco	 	        = $oConvenio->codbco;
	   	$this->sCarteira	 	        = $oConvenio->ar13_carteira;
	   	$this->sVariacao	 	        = $oConvenio->ar13_variacao;
		  $this->sConvenioCobranca    = $oConvenio->ar13_convenio;
	   	$this->sCedente  		        = $oConvenio->ar13_cedente;
	   	$this->sDigitoCedente       = $oConvenio->ar13_digcedente;
	   	$this->sOperacao            = $oConvenio->ar13_operacao;
	   	$this->iCadTipoConvenio     = $oConvenio->ar12_sequencial; 
	   	$this->iDigAgencia          = $oConvenio->digagencia;
	   	$this->sEspecie             = $oConvenio->ar13_especie;
	   	 
	   	if ( $this->iCadTipoConvenio == 5 ) {
	   		if ( $oConvenio->ar13_carteira == '9' ) {
	   		  $this->sTipoConvenio = 'CR';
	   		} else {
	   			$this->sTipoConvenio = 'SR';
	   		}
	   		$this->iCodAgencia   = substr(str_pad($oConvenio->codagencia,5,"0",STR_PAD_LEFT),1,4);

	   	} else if ( $this->iCadTipoConvenio == 6 ) {
	   	  
        $this->sTipoConvenio = $oConvenio->ar12_sigla;
        $this->iCodAgencia   = $oConvenio->codagencia;	   		
            
        /**
         *  Calcula dнgito do cedente
         */
        $sSqlDigCedente  = " select 11 - fc_modulo11('{$oConvenio->ar13_cedente}',2,9) as digito ";
        $rsDigCedente    = db_query($sSqlDigCedente);
        $iDigitoCendente = db_utils::fieldsMemory($rsDigCedente,0)->digito;
        
        if ( $iDigitoCendente > 9 ) {
          $iDigitoCendente = 0;
        }
        
        $this->sDigitoCedente = $iDigitoCendente;
        
	   	} else {
		   	$this->sTipoConvenio = $oConvenio->ar12_sigla;
	  	  $this->iCodAgencia	 = $oConvenio->codagencia."-".$oConvenio->digagencia;
	   	}
		  
		} else {
		  throw new Exception("Nenhum convкnio encontrado!");
		}
		
		$this->geraLinhaBarra($iNumpre,$iNumpar,$sValor,$sVlrbar,$dDataVenc,$iTercDig);
	
	
  }
  

  private function geraLinhaBarra($iNumpre,$iNumpar,$sValor,$sVlrbar,$dDataVenc,$iTercDig) {
  	
    $sDataVencimento = str_replace("-","",$dDataVenc);
  	
    if ( $this->iModalidadeConvenio == 1 ) {
      
      $sSqlFichaCompensacao  = "select * 																	                ";
      $sSqlFichaCompensacao .= "  from fc_fichacompensacao( {$this->iCodConvenio},			  ";
      $sSqlFichaCompensacao .= " 				             		    {$iNumpre},										";
      $sSqlFichaCompensacao .= " 					            	    {$iNumpar},										";
      $sSqlFichaCompensacao .= " 						               '{$dDataVenc}',					  		";
      $sSqlFichaCompensacao .= " 						                {$sValor})										";
      
      $rsFichaCompensacao    = pg_query($sSqlFichaCompensacao); 
      $oFichaCompensacao     = db_utils::fieldsMemory($rsFichaCompensacao,0);
      
      if($oFichaCompensacao->erro == 'f'){
      	
        $this->sCodigoBarra    = $oFichaCompensacao->codigobarras;
        $this->sLinhaDigitavel = $oFichaCompensacao->linhadigitavel;
        $this->sNossoNumero    = $oFichaCompensacao->nossonumero;
        $this->sCampoLivre	   = $oFichaCompensacao->campolivre;
        
      } else {
	      throw new Exception("Ficha Compensaзгo: ".$oFichaCompensacao->mensagem);	
      }
      
    } else if($this->iModalidadeConvenio == 2) {
    	
      if ( $this->iFormatoVenc == 1 ) {
        $sVencBar	       = $sDataVencimento.'000000';
      } else if ($this->iFormatoVenc == 2) {
        
        $sDataVencimento = substr($sDataVencimento, 6, 2).substr($sDataVencimento, 4, 2).substr($sDataVencimento, 2, 2);
        $sVencBar  	     = $sDataVencimento.'00000000';
      }
    
      $sInibar      = "8".$this->sSegmento.$iTercDig;
      $iNumpre      = db_numpre($iNumpre,0).db_formatar($iNumpar, 's', "0", 3, "e");
      $sSqlFebraban = " select fc_febraban('$sInibar'||'$sVlrbar'||'".$this->sConvenioArrecadacao."'||'".$sVencBar."'||'$iNumpre')";
      
      $rsFebraban   = pg_query($sSqlFebraban);
      $oFebraban    = db_utils::fieldsMemory($rsFebraban,0);
        
      if ($oFebraban->fc_febraban == "") {
        throw new Exception("Erro ao gerar cуdigo de barras(2)");
      }
        
      $this->sCodigoBarra     = substr($oFebraban->fc_febraban,0,strpos($oFebraban->fc_febraban, ','));
      $this->sLinhaDigitavel  = substr($oFebraban->fc_febraban, strpos($oFebraban->fc_febraban, ',') + 1);
    } else {
    	
      $this->sCodigoBarra     = str_pad($iNumpre,8,'0',STR_PAD_LEFT).str_pad($iNumpar,3,'0',STR_PAD_LEFT);
      $this->sLinhaDigitavel  = str_pad($iNumpre,8,'0',STR_PAD_LEFT).str_pad($iNumpar,3,'0',STR_PAD_LEFT);      
    }
    
    /**
     * Verificamos se o numpre й um numpre de recibo
     * Se a condiзгo for verdadeira, inserimos registro do numpre, cуdigo de barra e linha digitavel na tabela recibocodbar
     */
    $sSqlRecibo  = "select 1                       ";
    $sSqlRecibo .= "  from recibopaga              ";
    $sSqlRecibo .= " where k00_numnov = {$iNumpre} ";
    $sSqlRecibo .= " union                         ";
    $sSqlRecibo .= "select 1                       ";
    $sSqlRecibo .= "  from recibo                  ";
    $sSqlRecibo .= " where k00_numpre = {$iNumpre} ";
    $rsRecibo    = db_query($sSqlRecibo);    

    if (pg_num_rows($rsRecibo) > 0) {
      /**
       * Valida se existe a tabela
       */
      $rsVerificaExistenciaTabela = db_query("select * from pg_class where relname = 'recibocodbar' and relkind = 'r'");
      if (pg_num_rows($rsVerificaExistenciaTabela)>0) {
      
        $oDaoReciboCodBar         = db_utils::getDao("recibocodbar");
        /**
         * Valida se existem registros na tabela recibocodbar
         */
        $sSqlVerificaReciboCodBar = $oDaoReciboCodBar->sql_query_file( null,"1",null,"k00_numpre = {$iNumpre} or k00_codbar = '{$this->sCodigoBarra}' ");
        $rsVerificaReciboCodBar   = db_query($sSqlVerificaReciboCodBar);
        
        if (!$rsVerificaReciboCodBar){
          throw new Exception("Codigo de Barras: ".$oDaoReciboCodBar->erro_banco);
        }
        /**
         * Caso nгo exista tenta incluir
         */
        if (pg_num_rows($rsVerificaReciboCodBar) == 0) {
          
          $oDaoReciboCodBar->k00_numpre          = $iNumpre;
          $oDaoReciboCodBar->k00_codbar          = $this->sCodigoBarra;
          $oDaoReciboCodBar->k00_linhadigitavel  = $this->sLinhaDigitavel;
          $oDaoReciboCodBar->incluir($iNumpre);
          /**
           * Nгo conseguindo incluir dispara erro
           */
          if ($oDaoReciboCodBar->erro_status == "0") {
            
            $sMsgErro  = "Erro ao incluir registros do numpre na tabela recibocodbar.\\n\\n";
            $sMsgErro .= "Erro da Classe:\\n";
            $sMsgErro .= "{$oDaoReciboCodBar->erro_msg}";
            throw new Exception($sMsgErro);
          }
        }
      }
    }
  }

  
  function getImagemBanco(){
	
    $sSqlBanco  = " select  * 						 			                  "; 
    $sSqlBanco .= "   from db_bancos							                "; 
	  $sSqlBanco .= "  where db90_codban = '{$this->getCodBanco()}' ";
	
    $rsBanco    	   = pg_query($sSqlBanco);
    $iNroLinhasBanco = pg_num_rows($rsBanco);
    
    if($iNroLinhasBanco > 0 ){
		
      $oBanco = db_utils::fieldsMemory($rsBanco,0);	

      if($oBanco->db90_digban=="" || $oBanco->db90_abrev=="" || $oBanco->db90_logo=="")	{
     	  throw new Exception("Configure o banco no Cadastro de Bancos!");
   	  }
 
  	  pg_query("begin");
	  
  	  $sCaminho = "tmp/".$this->getCodBanco().".jpg";
      
  	  global $conn;
	    pg_lo_export  ( "$oBanco->db90_logo",$sCaminho,$conn);
      
      pg_query("commit");
   
	    return $sCaminho;
  
  	} else {
  	  throw new Exception("Nгo existe Banco cadastrado para o cуdigo {$this->getCodBanco()}!");
    }
    
  }
  
  function getDigitoAgencia(){
  	return $this->iDigAgencia;
  }
  
  function getDigitoCedente(){
    return $this->sDigitoCedente;
  }  
  
  function getOperacao(){
  	return $this->sOperacao;
  }
  
  function getLinhaDigitavel(){
    return $this->sLinhaDigitavel;
  }

  function getCodigoBarra(){
  	return $this->sCodigoBarra;
  }
  
  function getCodBanco(){
  	return $this->iCodBanco;
  }

  function getCodAgencia(){
  	return $this->iCodAgencia;
  }
  
  function getCarteira(){
	  return $this->sCarteira."-".str_pad($this->sVariacao.db_CalculaDV($this->sVariacao, 11),4,"0",STR_PAD_LEFT);
  }
  
  function getCedente(){
  	return $this->sCedente;
  }  
  
  function getConvenioCobranca(){
  	return $this->sConvenioCobranca;
  }
  
  function getConvenioArrecadacao(){
  	return $this->sConvenioArrecadacao;
  }  
  
  function getNossoNumero(){
  	return $this->sNossoNumero;
  }
  
  function getCampoLivre(){
  	return $this->sCampoLivre;
  }
  
  function getTipoConvenio(){
  	return $this->sTipoConvenio;
  }
  
  function getiCadTipoConvenio(){
  	return $this->iCadTipoConvenio;
  }  
  
  function getAgenciaCedente(){
    switch ($this->iCadTipoConvenio) {
    	case 5:
    	  $sCedente        = $this->getOperacao().$this->getCedente()."-".$this->getDigitoCedente();
        $sAgenciaCedente = $this->getCodAgencia()."/".$sCedente;
    	break;
      case 6:
      case 7:
        $sCedente        = $this->getCedente()."-".$this->getDigitoCedente();
        $sAgenciaCedente = $this->getCodAgencia()."/".$sCedente;      
      break;    	
    	default:
        $sCedente        = substr($this->getCedente(),0,strlen($this->getCedente())-1)."-". substr($this->getCedente(),strlen($this->getCedente())-1,1);
        $sAgenciaCedente = $this->getCodAgencia()."/".$sCedente;    		
    	break;
    }
    
    return $sAgenciaCedente;
    
  }
  
  /**
   * Retorna Espйcie do documento gerado.
   * @return string - Especie do documento
   */
  function getEspecieDocumento() {
    return $this->sEspecie;
  }
}

?>