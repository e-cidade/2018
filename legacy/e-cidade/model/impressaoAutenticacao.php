<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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


class impressaoAutenticacao {
  
	public   $oModelo             = null;
	
	private  $sStringAutenticacao = '';
	private  $iId                 = '';
	private  $sData               = '';
	private  $sAutent             = '';
	
  /**
   * Metodo construtor, responsavel pela criacao do objeto de manipulacao do modelo de impressao 
   *
   * @param  string   k11_id da cfautent (codigo indentificador da autenticadora) $iId
   * @param  string   endereco de ip da autenticadora                             $sIp
   * @param  string   porta onde esta rodando o servico da autenticadora          $sPorta
   * @param  string   tipo de autenticacao SLIP, ARRECADACAO OU EMPENHO           $sTipoAutenticacao
   * 
   * @return void 
   */
  
	function __construct($sStringAutenticacao) {

    $sSqlAutenticadora  = "select k11_id ";
    $sSqlAutenticadora .= "  from cfautent ";
    $sSqlAutenticadora .= " where k11_instit = ".db_getsession("DB_instit");
    $sSqlAutenticadora .= "   and k11_ipterm = '".db_getsession("DB_ip")."'";
    $rsAutenticadora    = db_query($sSqlAutenticadora);
    $oAutenticadora     = db_utils::fieldsMemory($rsAutenticadora,0);
    if (pg_num_rows($rsAutenticadora) == 0 || $rsAutenticadora == false) {
    	throw new Exception("Autenticadora nao encontrada.");
    }
    
   // echo("|".str_replace(' ','x',$sStringAutenticacao)."|");
    $this->sStringAutenticacao = trim($sStringAutenticacao);
    $this->iId     = $oAutenticadora->k11_id;
    $this->sData   = trim(substr($sStringAutenticacao,8,10));
    $this->sAutent = trim(substr($sStringAutenticacao,2,6));
    
  //  echo ("<br><br> 1111 Term:{$this->iId} Data:{$this->sData} Autent:{$this->sAutent} |{$sStringAutenticacao}|");
    
  }
  
  function getModelo() {
  	
//  	echo ("<br><br> 2222 Term:{$this->iId} Data:{$this->sData} Autent:{$this->sAutent} ||");
  	
    $oImpressao = null;
    
    $sSqlAutenticao  = " select distinct ";
    $sSqlAutenticao .= "        cfautent.*, ";
    $sSqlAutenticao .= "        corrente.*, ";
    $sSqlAutenticao .= "        case ";
    $sSqlAutenticao .= "          when cornump.k12_id is not null then 'ARRECADACAO' ";
    $sSqlAutenticao .= "          when coremp.k12_id  is not null then 'EMPENHO' ";
    $sSqlAutenticao .= "          when corlanc.k12_id is not null then 'SLIP' ";
    $sSqlAutenticao .= "          else ''";
    $sSqlAutenticao .= "        end as tipo_autenticacao";    
    $sSqlAutenticao .= "   from corrente ";
    $sSqlAutenticao .= "        inner join cfautent on cfautent.k11_id    = corrente.k12_id";
    $sSqlAutenticao .= "        left  join cornump  on cornump.k12_id     = corrente.k12_id";
    $sSqlAutenticao .= "                           and cornump.k12_data   = corrente.k12_data";
    $sSqlAutenticao .= "                           and cornump.k12_autent = corrente.k12_autent";
    $sSqlAutenticao .= "        left  join corlanc  on corlanc.k12_id     = corrente.k12_id";
    $sSqlAutenticao .= "                           and corlanc.k12_data   = corrente.k12_data";
    $sSqlAutenticao .= "                           and corlanc.k12_autent = corrente.k12_autent";
    $sSqlAutenticao .= "        left  join coremp   on coremp.k12_id      = corrente.k12_id";
    $sSqlAutenticao .= "                           and coremp.k12_data    = corrente.k12_data";
    $sSqlAutenticao .= "                           and coremp.k12_autent  = corrente.k12_autent";
    $sSqlAutenticao .= "  where corrente.k12_id     = {$this->iId} ";
    $sSqlAutenticao .= "    and corrente.k12_data   = cast('{$this->sData}' as date)";
    $sSqlAutenticao .= "    and corrente.k12_autent = {$this->sAutent}";

    if (empty($this->sAutent) || empty($this->sData) || empty($this->iId)) {
      throw new Exception("Não foi encontrado o ID, Autent ou Data da autenticação corrente.");
    }
    
   // echo $sSqlAutenticao. " --- ".pg_last_error();
    
    $rsAutenticacao = db_query($sSqlAutenticao);
    
    //db_criatabela($rsAutenticacao);
    
    $oAutenticacao  = db_utils::fieldsMemory($rsAutenticacao,0);
    if ($rsAutenticacao == false || pg_num_rows($rsAutenticacao) == 0) {
    	throw new Exception("Modelo de impressao nao encontrado. Erro : Autenticacao nao encontrada. Term:{$this->iId} Data:{$this->sData} Autent:{$this->sAutent} {$sSqlAutenticao}");
    }
    
    if ($oAutenticacao->k11_tipoimp == '7') {
	    switch ( strtoupper($oAutenticacao->tipo_autenticacao) ) {
	      case 'ARRECADACAO':             
	        if ( !class_exists('modeloAutentTermicaArrecadacao') ) {
	          require_once(modification("model/modeloAutentTermicaArrecadacao.model.php"));
	        }
	        $oImpressao = new modeloAutentTermicaArrecadacao(db_getsession('DB_ip'),4444,$this->iId,$this->sData,$this->sAutent);
	        break;
	      case 'EMPENHO':
	        if ( !class_exists('modeloAutentTermicaEmpenho') ) {
	          require_once(modification("model/modeloAutentTermicaEmpenho.model.php"));  
	        }
	        $oImpressao = new modeloAutentTermicaEmpenho(db_getsession('DB_ip'),4444,$this->iId,$this->sData,$this->sAutent);
	        break;
	      case 'SLIP':
	        if ( !class_exists('modeloAutentTermicaSlip') ) {
	          require_once(modification("model/modeloAutentTermicaSlip.model.php"));  
	        }
	        $oImpressao = new modeloAutentTermicaSlip(db_getsession('DB_ip'),4444,$this->iId,$this->sData,$this->sAutent);
	        break;
	    } 
    }else if ($oAutenticacao->k11_tipoimp == 11) {
      if ( !class_exists('modeloAutenticadoraElginXprint') ) {
        require_once(modification("model/modeloAutenticadoraElginXprint.model.php"));  
      }
      $oImpressao = new modeloAutenticadoraElginXprint($this->sStringAutenticacao,db_getsession('DB_ip'),4444);
    }else{
      if ( !class_exists('modeloAutentGenerica') ) {
        require_once(modification("model/modeloAutentGenerica.model.php"));  
      }
    	$oImpressao = new modeloAutentGenerica($this->sStringAutenticacao,db_getsession('DB_ip'),4444);
    }
    $this->oModelo = $oImpressao;
  	return $this->oModelo;
  }
  
  
}

?>