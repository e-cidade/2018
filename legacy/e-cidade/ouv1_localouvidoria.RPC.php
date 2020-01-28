<?
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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");

$oPost    = db_utils::postMemory($_POST);

$oJson    = new services_json();

$lErro    = false;
$sMsgErro = '';

require_once("classes/db_ouvidoriacadlocal_classe.php");
$clOuvidoriaCadLocal       = new cl_ouvidoriacadlocal();

require_once("classes/db_ouvidoriacadlocalgeral_classe.php");
$clOuvidoriaCadLocalGeral  = new cl_ouvidoriacadlocalgeral();

require_once("classes/db_ouvidoriacadlocalender_classe.php");
$clOuvidoriaCadLocalEnder  = new cl_ouvidoriacadlocalender();

require_once("classes/db_ouvidoriacadlocaldepart_classe.php");
$clOuvidoriaCadLocalDepart = new cl_ouvidoriacadlocaldepart();

require_once("classes/db_ruas_classe.php");
$oDaoRuas = new cl_ruas();


if ( $oPost->sMethod == 'incluirLocal') {

  $oLocal = $oJson->decode(str_replace("\\","",$oPost->oDadosLocal));	
  
  
  db_inicio_transacao();

  
  $clOuvidoriaCadLocal->ov25_descricao = utf8_decode($oLocal->ov25_descricao);
  
  if ( trim($oLocal->ov25_validade) != '' ) {
    $clOuvidoriaCadLocal->ov25_validade  = implode("-",array_reverse(explode("/",$oLocal->ov25_validade)));
  } else {
  	$clOuvidoriaCadLocal->ov25_validade  = "null";
  }
  
  $clOuvidoriaCadLocal->incluir(null);
  
  if ( $clOuvidoriaCadLocal->erro_status == 0 ) {
  	$lErro    = true;
  	$sMsgErro = $clOuvidoriaCadLocal->erro_msg;
  }
    
  if ( !$lErro ) {
  	
    if ( $oLocal->sTipoLocal == 'g' ) {
    	
    	$clOuvidoriaCadLocalGeral->ov28_descricao         = utf8_decode($oLocal->ov28_descricao);
    	$clOuvidoriaCadLocalGeral->ov28_ouvidoriacadlocal = $clOuvidoriaCadLocal->ov25_sequencial;
    	$clOuvidoriaCadLocalGeral->incluir(null);

    	if ( $clOuvidoriaCadLocalGeral->erro_status == 0 ) {
    		$lErro    = true;
    		$sMsgErro = $clOuvidoriaCadLocalGeral->erro_msg;
    	}
    	
    } else if ( $oLocal->sTipoLocal == 'e' ) {

    	$clOuvidoriaCadLocalEnder->ov26_ruas              = $oLocal->ov26_ruas;
    	$clOuvidoriaCadLocalEnder->ov26_numero            = $oLocal->ov26_numero;
    	$clOuvidoriaCadLocalEnder->ov26_complemento       = utf8_decode($oLocal->ov26_complemento);
    	$clOuvidoriaCadLocalEnder->ov26_ouvidoriacadlocal = $clOuvidoriaCadLocal->ov25_sequencial;
    	$clOuvidoriaCadLocalEnder->ov26_observacao        = utf8_decode($oLocal->ov26_observacao);
    	$clOuvidoriaCadLocalEnder->incluir(null);
    	
      if ( $clOuvidoriaCadLocalEnder->erro_status == 0 ) {
        $lErro    = true;
        $sMsgErro = $clOuvidoriaCadLocalEnder->erro_msg;
      }    	
    	
    } else {
    	
    	$clOuvidoriaCadLocalDepart->ov27_depart            = $oLocal->ov27_depart;
    	$clOuvidoriaCadLocalDepart->ov27_ouvidoriacadlocal = $clOuvidoriaCadLocal->ov25_sequencial;
    	$clOuvidoriaCadLocalDepart->incluir(null);
    	
    	if ( $clOuvidoriaCadLocalDepart->erro_status == 0 ) {
        $lErro    = true;
        $sMsgErro = $clOuvidoriaCadLocalDepart->erro_msg;
      }
           
    }
    
  }
  
  
  db_fim_transacao($lErro);

  
  if ( !$lErro ) {
     $sMsgErro = "Inclusуo feita com sucesso!";
  }
  
  $aRetorno = array("lErro"=>$lErro,
                    "sMsg" =>urlencode($sMsgErro));   

  echo $oJson->encode($aRetorno);
  
  
} else if ( $oPost->sMethod == 'alterarLocal') {

  $oLocal = $oJson->decode(str_replace("\\","",$oPost->oDadosLocal)); 
  
  db_inicio_transacao();
  
  $clOuvidoriaCadLocal->ov25_sequencial = $oLocal->ov25_sequencial;
  $clOuvidoriaCadLocal->ov25_descricao  = utf8_decode("{$oLocal->ov25_descricao}");
  
  if ( trim($oLocal->ov25_validade) != '' ) {
    $clOuvidoriaCadLocal->ov25_validade = implode("-",array_reverse(explode("/",$oLocal->ov25_validade)));
  } else {
  	$clOuvidoriaCadLocal->ov25_validade = "null";
  }
  
  $clOuvidoriaCadLocal->alterar($oLocal->ov25_sequencial);
  
  if ( $clOuvidoriaCadLocal->erro_status == 0 ) {
    $lErro    = true;
    $sMsgErro = $clOuvidoriaCadLocal->erro_msg;
    echo $sMsgErro."\n\n\n\n";
  }

  $sCampos  = " case                                                    ";
  $sCampos .= "   when ov28_sequencial is not null then 'g'             ";
  $sCampos .= "   when ov26_sequencial is not null then 'e'             ";
  $sCampos .= "   when ov27_sequencial is not null then 'd'             ";
  $sCampos .= " end as tipolocal,                                       ";
  $sCampos .= " case                                                    ";
  $sCampos .= "   when ov28_sequencial is not null then ov28_sequencial ";
  $sCampos .= "   when ov26_sequencial is not null then ov26_sequencial ";
  $sCampos .= "   when ov27_sequencial is not null then ov27_sequencial ";
  $sCampos .= " end as codlocal                                         ";  
  
  $sSqlOuvidoriaCadLocal = $clOuvidoriaCadLocal->sql_query_tipo($oLocal->ov25_sequencial,$sCampos);
  $rsConsultaTipo        = $clOuvidoriaCadLocal->sql_record($sSqlOuvidoriaCadLocal);
  $oDadosTipo            = db_utils::fieldsMemory($rsConsultaTipo,0);

  if ( $oLocal->sTipoLocal != $oDadosTipo->tipolocal ) {

    if ( $oDadosTipo->tipolocal == 'g') {
      
    	$clOuvidoriaCadLocalGeral->excluir(null," ov28_ouvidoriacadlocal = {$oLocal->ov25_sequencial} ");
      if ( $clOuvidoriaCadLocalGeral->erro_status == 0 ) {
      	$lErro    = true;
      	$sMsgErro = $clOuvidoriaCadLocalGeral->erro_msg;
      }
          	  	
    } else if ( $oDadosTipo->tipolocal == 'e' ) {
    	
      $clOuvidoriaCadLocalEnder->excluir(null," ov26_ouvidoriacadlocal = {$oLocal->ov25_sequencial} ");
      if ( $clOuvidoriaCadLocalEnder->erro_status == 0 ) {
        $lErro    = true;
        $sMsgErro = $clOuvidoriaCadLocalEnder->erro_msg;
      }    	
    	
    } else {
    	
      $clOuvidoriaCadLocalDepart->excluir(null," ov27_ouvidoriacadlocal = {$oLocal->ov25_sequencial} ");
      if ( $clOuvidoriaCadLocalDepart->erro_status == 0 ) {
        $lErro    = true;
        $sMsgErro = $clOuvidoriaCadLocalDepart->erro_msg;
      }
    	
    }

    $lAltera = false;
    
  } else {
  	
  	$lAltera = true;
  	
  }
  
  if ( !$lErro ) {
    
    if ( $oLocal->sTipoLocal == 'g' ) {
      
      $clOuvidoriaCadLocalGeral->ov28_descricao         = utf8_decode($oLocal->ov28_descricao);
      $clOuvidoriaCadLocalGeral->ov28_ouvidoriacadlocal = $clOuvidoriaCadLocal->ov25_sequencial;
      
      if ( $lAltera ) {
      	$clOuvidoriaCadLocalGeral->ov28_sequencial = $oDadosTipo->codlocal;
      	$clOuvidoriaCadLocalGeral->alterar($oDadosTipo->codlocal);
      } else {
        $clOuvidoriaCadLocalGeral->incluir(null);
      }

      if ( $clOuvidoriaCadLocalGeral->erro_status == 0 ) {
        $lErro    = true;
        $sMsgErro = $clOuvidoriaCadLocalGeral->erro_msg;
      }
      
    } else if ( $oLocal->sTipoLocal == 'e' ) {
      
      $clOuvidoriaCadLocalEnder->ov26_ruas              = $oLocal->ov26_ruas;
      $clOuvidoriaCadLocalEnder->ov26_numero            = $oLocal->ov26_numero;
      $clOuvidoriaCadLocalEnder->ov26_complemento       = utf8_decode($oLocal->ov26_complemento);
      $clOuvidoriaCadLocalEnder->ov26_ouvidoriacadlocal = $clOuvidoriaCadLocal->ov25_sequencial;
      $clOuvidoriaCadLocalEnder->ov26_observacao         = utf8_decode($oLocal->ov26_observacao);

      if ( $lAltera ) {
      	$clOuvidoriaCadLocalEnder->ov26_sequencial = $oDadosTipo->codlocal;
        $clOuvidoriaCadLocalEnder->alterar($oDadosTipo->codlocal);
      } else {
	      $clOuvidoriaCadLocalEnder->incluir(null);
      }      
      
      if ( $clOuvidoriaCadLocalEnder->erro_status == 0 ) {
        $lErro    = true;
        $sMsgErro = $clOuvidoriaCadLocalEnder->erro_msg;
      }     
      
    } else {
      
      $clOuvidoriaCadLocalDepart->ov27_depart            = $oLocal->ov27_depart;
      $clOuvidoriaCadLocalDepart->ov27_ouvidoriacadlocal = $clOuvidoriaCadLocal->ov25_sequencial;
      
      if ( $lAltera ) {
      	$clOuvidoriaCadLocalDepart->ov27_sequencial = $oDadosTipo->codlocal;
        $clOuvidoriaCadLocalDepart->alterar($oDadosTipo->codlocal);
      } else {
	      $clOuvidoriaCadLocalDepart->incluir(null);
      }      
      
      if ( $clOuvidoriaCadLocalDepart->erro_status == 0 ) {
        $lErro    = true;
        $sMsgErro = $clOuvidoriaCadLocalDepart->erro_msg;
      }
           
    }
    
  }
  
  
  db_fim_transacao($lErro);

  
  if ( !$lErro ) {
     $sMsgErro = "Alteraчуo feita com sucesso!";
  }
  
  $aRetorno = array("lErro"=>$lErro,
                    "sMsg" =>urlencode($sMsgErro));   

  echo $oJson->encode($aRetorno);
  
  
  
} else if ( $oPost->sMethod == 'excluirLocal') {


	
  $oLocal = $oJson->decode(str_replace("\\","",$oPost->oDadosLocal)); 
  
  db_inicio_transacao();
  
  $clOuvidoriaCadLocalGeral->excluir(null," ov28_ouvidoriacadlocal = {$oLocal->ov25_sequencial} ");
  if ( $clOuvidoriaCadLocalGeral->erro_status == 0 ) {
     $lErro    = true;
     $sMsgErro = $clOuvidoriaCadLocalGeral->erro_msg;
  }

  if ( !$lErro ) {
	  $clOuvidoriaCadLocalEnder->excluir(null," ov26_ouvidoriacadlocal = {$oLocal->ov25_sequencial} ");
	  if ( $clOuvidoriaCadLocalEnder->erro_status == 0 ) {
	    $lErro    = true;
	    $sMsgErro = $clOuvidoriaCadLocalEnder->erro_msg;
	  }     
  }
  
  if ( !$lErro ) {        
	  $clOuvidoriaCadLocalDepart->excluir(null," ov27_ouvidoriacadlocal = {$oLocal->ov25_sequencial} ");
	  if ( $clOuvidoriaCadLocalDepart->erro_status == 0 ) {
	    $lErro    = true;
	    $sMsgErro = $clOuvidoriaCadLocalDepart->erro_msg;
	  }
  }
  
  if ( !$lErro ) {
  	$clOuvidoriaCadLocal->ov25_sequencial = $oLocal->ov25_sequencial;
	  $clOuvidoriaCadLocal->excluir($oLocal->ov25_sequencial);
	  if ( $clOuvidoriaCadLocal->erro_status == 0 ) {
	    $lErro    = true;
	    $sMsgErro = $clOuvidoriaCadLocal->erro_msg;
	  }
  }
  
  db_fim_transacao($lErro);

  
  if ( !$lErro ) {
     $sMsgErro = "Exclusуo feita com sucesso!";
  }
  
  $aRetorno = array("lErro"=>$lErro,
                    "sMsg" =>urlencode($sMsgErro));   

  echo $oJson->encode($aRetorno);
  
}

  
?>