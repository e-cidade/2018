<?
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

class mail {
  
  public $sClass     = 0;
  public $sUserMail  = '';
  public $sPassMail  = '';
  public $sHostMail  = '';
  public $sPortMail  = 0;
  public $sEmailFrom = '';
  public $sEmailTo   = ''; 
  public $sMsg       = '';
  
  
  function __construct() {
    /**
     * Declaramos as variáveis da classe de acordo com o que está configurado no arquivo config.mail.php
     */
     include_once(modification('libs/config.mail.php'));
    
     $this->sUserMail        = $sUser;
     $this->sPassMail        = $sPass;
     $this->sHostMail        = $sHost;
     $this->sPortMail        = (int)$sPort;
    
     $oConfigDBpref    = db_utils::getDao("configdbpref");
     $rsConfigDBpref   = $oConfigDBpref->sql_record($oConfigDBpref->sql_query_file(db_getsession('DB_instit'),"w13_emailadmin"));
     $this->sEmailFrom = db_utils::fieldsMemory($rsConfigDBpref,0)->w13_emailadmin; 
  }
  
  function setUserMail($sUserMail) {
    $this->sUserMail = $sUserMail;
  }
  
  function setPassMail($sPassMail) {
    $this->sPassMail = $sPassMail;
  }  
  
  function setHostMail($sHostMail) {
    $this->sHostMail = $sHostMail;
  }  
  
  function setPortMail($sPortMail) {
    $this->sPortMail = (int)$sPortMail;
  }  
  
  function setsEmailFrom($sEmailFrom) {
    $this->sEmailFrom = $sEmailFrom;    
  }

  function setsEmailTo($sEmailTo) {
    $this->sEmailTo = $sEmailTo;    
  }
  
  function setsClass($sClass) {
    $this->sClass = $sClass;
  }

  function setsMsg($sMsg) {
    $this->sMsg = $sMsg;
  }  
  
  function setsSubject($sSubject) {
    $this->sSubject = $sSubject;
  }
  
  function Send() {
    
  	try {
  		
      switch($this->sClass) {
        
        case 1:
        	
          include(modification("libs/mail/db_smtp1_class.php"));
          $oClassMail = new Smtp1();
          $oClassMail->Send($this->sEmailTo,$this->sEmailFrom,$this->sSubject,$this->sMsg);
          
        break;
          
        case 2:
        	
          include(modification("libs/mail/db_smtp2_class.php"));
          
          $oClassMail = new SMTP();
          $oClassMail->Delivery('relay');
          $oClassMail->Relay($this->sHostMail, $this->sUserMail, $this->sPassMail, $this->sPortMail, 'login', false);
          $oClassMail->From($this->sEmailFrom);
          $oClassMail->AddTo($this->sEmailTo);
          $oClassMail->Html($this->sMsg);
          $oClassMail->Send($this->sSubject);
          
        break;
        
        default:
        
        	$sHeader = "From: {$this->sEmailFrom} <{$this->sEmailFrom}>";
        	if ( !mail($this->sEmailTo,$this->sSubject,$this->sMsg, $sHeader) ) {
        		throw Exception("Função mail");
        	}
        	
        break;
              
      }
    
      return "Uma mensagem foi encaminha para o e-mail informado";
      
    } catch (Exception $eException) {
    	return "01 - Erro ao enviar E-mail. ".$eException->getMessage();
    }	
    	
  }
  
  function Close($connection) {
    
    try {
      fclose($connection);       
    } catch (Exception $eException){
      return "02 - Erro ao fechar conexão";    
    }
    
  }
  
}
?>