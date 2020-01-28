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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");

require_once("classes/db_disbanco_classe.php");
require_once("classes/db_disbancoprocesso_classe.php");
require_once("classes/db_disbancoprotprocesso_classe.php");
require_once("classes/db_disarq_classe.php");
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/BusinessException.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$cldisbanco             = new cl_disbanco;
$cldisarq               = new cl_disarq;
$cldisbancoprocesso     = new cl_disbancoprocesso;
$cldisbancoprotprocesso = new cl_disbancoprotprocesso;

$cldisbancoprocesso->rotulo->label();
$cldisbancoprotprocesso->rotulo->label();
$cldisbanco->rotulo->label();

$clrotulo   = new rotulocampo;
$clrotulo->label("p58_requer");

$iInstit  = db_getsession("DB_instit");
$iUsuario = db_getsession("DB_id_usuario");

if (isset($oPost->incluir)) {
  
  try {
  
    $sSQLValidacao = "  select 1                                          ";
    $sSQLValidacao.= "    from arrebanco as subquery                      ";
    $sSQLValidacao.= "   where subquery.k00_numbco = '{$oPost->k00_numbco}'";
    $sSQLValidacao.= "     and subquery.k00_numbco != '0'                 ";

    $rsValidacao   = db_query($sSQLValidacao);
    
    if (!$rsValidacao) {
      throw new DBException("Erro ao Validar NUMBCO lan�ado." . pg_last_error() );
    }
    
    /**
     * S� lan�ar erro quando NUMBCO tiv�r mais de um numpre vinculado (Catia Renata) 
     */
    if ( pg_num_rows($rsValidacao) > 1 ) {
      throw new BusinessException("NUMBCO J� Lan�ado no Arrebanco.");
    }
  	
    /*
     * T. 47777
     *  Se o arquivo da segunda tela (Inclus�o Manual) foi selecionado, faremos somente a inser��o na disbanco
     *  e como codret ficara o codigo do arquivo selecionado variavel $arquivocodret;
     */
          
      db_inicio_transacao();
            
      if (isset($oGet->arquivocodret) && $oGet->arquivocodret != null && $oGet->arquivocodret != '' ) {
  		
        $iCodRet = $oGet->arquivocodret;       
          
  	  } else {
  	
  	      $sAutent = "true";
  	      if (isset($oPost->autent) && $oPost->autent == "f") {
  	        $sAutent = "false";  
  	      }
  	       
  		    $cldisarq->k15_codbco = $oPost->k15_codbco;                             
  		    $cldisarq->k15_codage = $oPost->k15_codage;                             
  		    $cldisarq->arqret     = "INCLUSAO MANUAL";             
  		    $cldisarq->dtretorno  = $oPost->dtarq_ano.'-'.$oPost->dtarq_mes.'-'.$oPost->dtarq_dia;
  		    $cldisarq->dtarquivo  = $oPost->dtarq_ano.'-'.$oPost->dtarq_mes.'-'.$oPost->dtarq_dia;
  		    $cldisarq->k00_conta  = $oPost->conta;                                  
  		    $cldisarq->autent     = $sAutent; 
  		    $cldisarq->id_usuario = $iUsuario;                                
  		    $cldisarq->instit     = $iInstit;
  		    
  		    $cldisarq->incluir(null);
  		    if ($cldisarq->erro_status == "0") {
  		       throw new Exception("[2] - Erro realizando inclus�o do lan�amento Manual do Arquivo(disarq).\\n{$cldisarq->erro_msg}");
  		    }
  		    $iCodRet = $cldisarq->codret;
  		     
  	  }
  		    
  	  $cldisbanco->k00_numbco = $oPost->k00_numbco;                                                                                                                  
      $cldisbanco->k15_codbco = $oPost->k15_codbco;                                                                                                                 
      $cldisbanco->k15_codage = $oPost->k15_codage;                                                                                                               
      $cldisbanco->codret     = $iCodRet;                                       
      $cldisbanco->dtarq      = $oPost->dtarq_ano .'-'.$oPost->dtarq_mes .'-'.$oPost->dtarq_dia;                                                                                           
      $cldisbanco->dtpago     = $oPost->dtpago_ano.'-'.$oPost->dtpago_mes.'-'.$oPost->dtpago_dia;                                                                                        
      $cldisbanco->dtcredito  = $oPost->dtcredito_ano.'-'.$oPost->dtcredito_mes.'-'.$oPost->dtcredito_dia;    
      $cldisbanco->vlrpago    = $oPost->vlrpago+0;                                                                                                                  
      $cldisbanco->vlrjuros   = $oPost->vlrjuros+0;                                                                                                                 
      $cldisbanco->vlrmulta   = $oPost->vlrmulta+0;                                                                                                                 
      $cldisbanco->vlracres   = $oPost->vlracres+0;                                                                                                                 
      $cldisbanco->vlrdesco   = $oPost->vlrdesco+0;                                                                                                                 
      $cldisbanco->vlrtot     = $oPost->vlrpago+$oPost->vlrjuros+$oPost->vlrmulta+$oPost->vlracres-$oPost->vlrdesco+0;                                                                           
      $cldisbanco->cedente    = $oPost->cedente;                                                                                                                    
      $cldisbanco->vlrcalc    = "0";                                                                                                                            
      $cldisbanco->classi     = "false";  // como o arquivo foi selecionado, definimos como false o campo classi, sendo q no form ele entra com disabled
      $cldisbanco->k00_numpre = $oPost->k00_numpre+0 ;                                                                                                                  
      $cldisbanco->k00_numpar = $oPost->k00_numpar+0 ;                                                                                                                  
      $cldisbanco->convenio   = $oPost->convenio     ;                                                                                                                  
      $cldisbanco->instit     = $iInstit;                                                                                                                             
      $cldisbanco->incluir(null);
  	  if ($cldisbanco->erro_status == "0") {
        throw new Exception("[3] - Erro realizando inclus�o do lan�amento Manual(disbanco).\\n{$cldisbanco->erro_msg}");
      }
      
      
      /*
       * Verificamos se foi informado processo de protocolo ou observa��es para o lan�amento e se o processo � ou n�o do sistema
       * Se o processo n�o for do sistema ser� apenas cadastrado na base disbancoprocesso, do contr�rio ser� criado vinculo com 
       * a tabela disbancoprotprocesso
       */
      $cldisbancoprocesso->k142_idret        = $cldisbanco->idret;
      $cldisbancoprocesso->k142_processo     = $oPost->k142_processo; 
      $cldisbancoprocesso->k142_dataprocesso = $oPost->k142_dataprocesso;
      $cldisbancoprocesso->k142_titular      = $oPost->k142_titular;
      $cldisbancoprocesso->k142_observacao   = str_replace("'","\'", $oPost->k142_observacao);
      $cldisbancoprocesso->incluir(null);
      if($cldisbancoprocesso->erro_status == "0") {
        throw new Exception("[4] - Erro realizando inclus�o dos dados do processo da inclus�o manual (disbancoprocesso) .\\n{$cldisbancoprocesso->erro_msg}");      
      }
      
      if ($oPost->lProcessoSistema) {
        
        $cldisbancoprotprocesso->k141_disbancoprocesso = $cldisbancoprocesso->k142_sequencial;
        $cldisbancoprotprocesso->k141_protprocesso     = $oPost->k141_protprocesso;
        $cldisbancoprotprocesso->incluir(null);
        if ($cldisbancoprotprocesso->erro_status == "0") {
          throw new Exception("[5] - Erro realizando vinculo do processo de protocolo do sistema com o idret (disbancoprotprocesso).\\n{$cldisbancoprotprocesso->erro_msg}");
        }      
        
      } 
  	  
      db_fim_transacao(false);
      db_msgbox("Opera��o realizada com Sucesso!");
          
	} catch (Exception $eException) {
    
    db_fim_transacao(true);
    db_msgbox($eException->getMessage());
    
  }

}

if (isset($oPost->alterar)) {
  
  try {
  	
  	if (isset($oPost->k00_numbco) and $oPost->k00_numbco != '') {
	    
	    $sSQLValidacao = "  select 1                                            ";
	    $sSQLValidacao.= "    from arrebanco as subquery                        ";
	    $sSQLValidacao.= "   where subquery.k00_numbco = '{$oPost->k00_numbco}' "; 
	    $sSQLValidacao.= "     and subquery.k00_numbco != '0'                   ";
	    $rsValidacao   = db_query($sSQLValidacao);
	
	    if (!$rsValidacao) {
	      throw new DBException("Erro ao Validar NUMBCO lan�ado." . pg_last_error() );
	    }
	    
	    if ( pg_num_rows($rsValidacao) > 1 ) {
	      throw new BusinessException("NUMBCO J� Lan�ado no Arrebanco.");
	    }
    
  	}
  	
    $cldisbanco->k15_codbco = $oPost->k15_codbco;                                       
    $cldisbanco->k15_codage = $oPost->k15_codage;                                     
    $cldisbanco->k00_numbco = $oPost->k00_numbco;                                     
    $cldisbanco->cedente    = $oPost->cedente;                                        
    $cldisbanco->dtarq      = $oPost->dtarq_ano ."-".$oPost->dtarq_mes ."-".$oPost->dtarq_dia;                                          
    $cldisbanco->dtpago     = $oPost->dtpago_ano."-".$oPost->dtpago_mes."-".$oPost->dtpago_dia;
    $cldisbanco->dtcredito  = $oPost->dtcredito_ano.'-'.$oPost->dtcredito_mes.'-'.$oPost->dtcredito_dia;
    $cldisbanco->vlrpago    = $oPost->vlrpago+0;                                        
    $cldisbanco->vlrjuros   = $oPost->vlrjuros+0;                                       
    $cldisbanco->vlrmulta   = $oPost->vlrmulta+0;                                       
    $cldisbanco->vlracres   = $oPost->vlracres+0;                                       
    $cldisbanco->vlrdesco   = $oPost->vlrdesco+0;                                       
    $cldisbanco->vlrtot     = $oPost->vlrpago+$oPost->vlrjuros+$oPost->vlrmulta+$oPost->vlracres-$oPost->vlrdesco+0;
    $cldisbanco->classi     = $oPost->classi;                                         
    $cldisbanco->convenio   = $oPost->convenio;                                       
    $cldisbanco->k00_numpre = $oPost->k00_numpre+0;                                     
    $cldisbanco->k00_numpar = $oPost->k00_numpar+0;                                      
    $cldisbanco->alterar($oPost->idret);
    if ($cldisbanco->erro_status == "0"){
      throw new Exception("[4] - Erro realizando altera��o do lan�amento Manual(disbanco).\\n{$cldisbanco->erro_msg}");
    }
    
    /*
     * Verificamos se foi informado processo de protocolo ou observa��es para o lan�amento e se o processo � ou n�o do sistema
     * Se o processo n�o for do sistema ser� apenas cadastrado na base disbancoprocesso, do contr�rio ser� criado vinculo com 
     * a tabela disbancoprotprocesso
     * 
     * Primeiramente exclu�mos os dados dos processos existentes para a inclus�o manual ap�s inclu�mos novamente
     */
    $sWhere  = "k141_disbancoprocesso in (select k142_sequencial              ";
    $sWhere .= "                            from disbancoprocesso             ";
    $sWhere .= "                           where k142_idret = {$oPost->idret})";
    $cldisbancoprotprocesso->excluir(null,$sWhere);
    if($cldisbancoprotprocesso->erro_status == "0") {
      throw new Exception("[5] - Erro realizando altera��o dos dados do processo da inclus�o manual.\\n{$cldisbancoprotprocesso->erro_msg}");
    }
    
    $cldisbancoprocesso->excluir(null,"k142_idret = {$oPost->idret}");
    if($cldisbancoprocesso->erro_status == "0") {
      throw new Exception("[6] - Erro realizando altera��o dos dados do processo da inclus�o manual.\\n{$cldisbancoprocesso->erro_msg}");
    }
    
    $cldisbancoprocesso->k142_idret        = $oPost->idret;
    $cldisbancoprocesso->k142_processo     = $oPost->k142_processo; 
    $cldisbancoprocesso->k142_dataprocesso = $oPost->k142_dataprocesso;
    $cldisbancoprocesso->k142_titular      = $oPost->k142_titular;
    $cldisbancoprocesso->k142_observacao   = str_replace("'","\'", $oPost->k142_observacao);
    $cldisbancoprocesso->incluir(null);
    if($cldisbancoprocesso->erro_status == "0") {
      throw new Exception("[7] - Erro realizando altera��o dos dados do processo da inclus�o manual (disbancoprocesso) .\\n{$cldisbancoprocesso->erro_msg}");      
    }
    
    if ( $oPost->lProcessoSistema == true && $oPost->k141_protprocesso != "") {
      
      $cldisbancoprotprocesso->k141_disbancoprocesso = $cldisbancoprocesso->k142_sequencial;
      $cldisbancoprotprocesso->k141_protprocesso     = $oPost->k141_protprocesso;
      $cldisbancoprotprocesso->incluir(null);
      if ($cldisbancoprotprocesso->erro_status == "0") {
        throw new Exception("[8] - Erro realizando vinculo do processo de protocolo do sistema com o idret (disbancoprotprocesso).\\n{$cldisbancoprotprocesso->erro_msg}");
      }      
      
    }    

    db_fim_transacao(false);
    db_msgbox("Opera��o realizada com Sucesso!");
    echo "<script>window.parent.location = window.parent.location;</script>";
    
  } catch (Exception $eException) {
    
     db_fim_transacao(true);
     db_msgbox($eException->getMessage());
    
  }
  
}

if (isset($oPost->excluir)) {
  
  try {
    
    db_inicio_transacao();
    
    $sWhere  = "k141_disbancoprocesso in (select k142_sequencial              ";
    $sWhere .= "                            from disbancoprocesso             ";
    $sWhere .= "                           where k142_idret = {$oPost->idret})";
    $cldisbancoprotprocesso->excluir(null,$sWhere);
    if($cldisbancoprotprocesso->erro_status == "0") {
      throw new Exception("[5] - Erro realizando exclus�o dos dados do processo da inclus�o manual.\\n{$cldisbancoprotprocesso->erro_msg}");
    }
    
    $cldisbancoprocesso->excluir(null,"k142_idret = {$oPost->idret}");
    if($cldisbancoprocesso->erro_status == "0") {
      throw new Exception("[6] - Erro realizando exclus�o dos dados do processo da inclus�o manual.\\n{$cldisbancoprocesso->erro_msg}");
    }
    
    $cldisbanco->excluir($oPost->idret);
    if ($cldisbanco->erro_status == "0") {
      throw new Exception("[1] - Erro realizando exclus�o do lan�amento Manual.\\n{$cldisbanco->erro_msg}");
    }
    
    db_fim_transacao(false);
    
    db_msgbox("Opera��o realizada com sucesso!");
    echo "<script>window.parent.location = window.parent.location;</script>";
    
  } catch (Exception $eException) {
    
    db_fim_transacao(true);
    db_msgbox($eException->getMessage());
    
  }
  
}

if ($oGet->opcao != 5 ) {
  
  $sWhere     = " disbanco.idret = {$oGet->idret} and disbanco.instit = {$iInstit} and trim(arqret) like 'INCLU%' ";
  $rsDisbanco = $cldisbanco->sql_record($cldisbanco->sql_query_cadastro(null,'*',null,$sWhere));
  if ($cldisbanco->numrows == 0 ) {
    $podeexcluir = 'f';
  } else {
    $podeexcluir = 't';
  }
  
  /*
   * Buscamos os dados do processo da inclus�o manual
   */
  $lProcessoSistema = true;
  $sCamposDisbancoProcesso = " disbancoprocesso.*, 
                               (select 1 
                                  from disbancoprotprocesso 
                                 where k141_disbancoprocesso = k142_sequencial) as sistema";
  $sSqlDisbancoProcesso    = $cldisbancoprocesso->sql_query(null,$sCamposDisbancoProcesso,null,"k142_idret= {$oGet->idret}");
  $rsDisbancoProcesso      = $cldisbancoprocesso->sql_record($sSqlDisbancoProcesso);
  if ($cldisbancoprocesso->numrows > 0) {
    db_fieldsmemory($rsDisbancoProcesso, 0);  
  
    /*
     * Caso o cadastro do processo da inclus�o manual esteja ligad a um processo do sistema atrav�s da tabela disbancoprotprocesso
     * setamos a vari�vel lProcessoSistema para true e setamos o valor das vari�veis do formul�rio
     */
    if($sistema == 1) {
      $lProcessoSistema = true;
      $k141_protprocesso = $k142_processo;
      $p58_requer        = $k142_titular; 
    } else {
      $lProcessoSistema = false;
    }
    
  }
  
  if (!isset($oGet->idret) ) {
    echo "C�digo do arquivo de Retorno Inv�lido.";
    exit;
  }
  
  $rsDisbanco = $cldisbanco->sql_record($cldisbanco->sql_query_cadastro($oGet->idret,'*'));
  if ($cldisbanco->numrows == 0) {
    echo "<script>window.parent.location = window.parent.location;</script>";
  } else {
    db_fieldsmemory($rsDisbanco,0);
  }
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
  db_app::load('scripts.js, prototype.js, strings.js');
  db_app::load('estilos.css');
?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center">
  <tr> 
    <td>
    <?
      include("forms/db_frmbaixabanco.php");
    ?>
    </td>
  </tr>
</table>
</body>
</html>