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

session_start();

include("libs/db_conecta.php");
include("libs/db_sql.php");
include("libs/db_utils.php");
include("libs/db_stdlib.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_certidaoweb_classe.php");
include("classes/db_certidao_classe.php");
include("classes/db_certidaovalidaonline_classe.php");
include("classes/db_certidaovalidaonlinecert_classe.php");
include("classes/db_certidaovalidaonlineusuario_classe.php");
include("classes/db_db_usuarios_classe.php");

$aRetorno = array();
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]),$aRetorno);

$oPost = db_utils::postMemory($_POST,0);
$oGet  = db_utils::postMemory($_GET,0);

$clcertidao             = new cl_certidao();
$clcertidaoweb          = new cl_db_certidaoweb();
$clcertvalonline        = new cl_certidaovalidaonline();
$clcertvalonlinecert    = new cl_certidaovalidaonlinecert();
$clcertvalonlineusuario = new cl_certidaovalidaonlineusuario();
$cldbusuarios           = new cl_db_usuarios();

db_mensagem("certidaoautentica_cab","certidaoautentica_rod");
mens_help();      

$instit  = db_getsession("DB_instit");
$iLogin  = db_getsession("DB_login");

if (isset($oPost->submit) && $oPost->submit = 'Verificar') {
$sqlerro = false;
$sErro   = false;

db_inicio_transacao();

$sData    = date("Y-m-d");
$sHora    = db_hora();
$sIp      = getenv("REMOTE_ADDR");

if (isset($oPost->verificador) && $oPost->verificador != "") {
   $sCodDigitado = $oPost->verificador;
} else {
   $sCodDigitado = "";
}
	
$rsCertidaoWeb = $clcertidaoweb->sql_record($clcertidaoweb->sql_query("","codcert,cerdtvenc","","ceracesso = '".$oPost->verificador."'"));

if ($clcertidaoweb->numrows > 0) {  
   $oCertidaoWeb = db_utils::fieldsMemory($rsCertidaoWeb,0);
   
   /**
   * $sStatus = 1-Vencida, 2-Não Vencida, 3-Inválida
   * @return int
   */
  
   if ($oCertidaoWeb->cerdtvenc < $sData) {
     $sStatus = 1;
   } else if ($oCertidaoWeb->cerdtvenc >= $sData) {
     $sStatus = 2;
   }
} else {
  $oCertidaoWeb->codcert = '000';
  $sStatus = 3;
}

   if ($sqlerro == false) {
      $clcertvalonline->w18_dtvalidacao     = $sData;
      $clcertvalonline->w18_hora            = $sHora;
      $clcertvalonline->w18_codigovalidacao = $sCodDigitado;
      $clcertvalonline->w18_ip              = $sIp;
      $clcertvalonline->w18_status          = $sStatus;
      $clcertvalonline->incluir(null);	
	
	  if ($clcertvalonline->erro_status == 0) {
	     $sqlerro  = true;
	     $erro_msg = $clcertvalonline->erro_msg;           
	  }            
   }

   $rsCertidao = $clcertidao->sql_record($clcertidao->sql_query("","p50_sequencial","","p50_sequencial = '".$oCertidaoWeb->codcert."'"));  

   if ($clcertidao->numrows > 0) {
   	  $oCertidao = db_utils::fieldsMemory($rsCertidao,0);
   	  
      if ($sqlerro == false && $sStatus != 3) {
         $clcertvalonlinecert->w19_certidao             = $oCertidao->p50_sequencial;
   	     $clcertvalonlinecert->w19_certidaovalidaonline = $clcertvalonline->w18_sequencial; 
   	     $clcertvalonlinecert->incluir(null);	
	     
	     if ($clcertvalonlinecert->erro_status == 0) {
	        $sqlerro  = true;
	        $erro_msg = $clcertvalonlinecert->erro_msg;           
	     }            
      }   	
   }

   if (isset($iLogin) && $iLogin != "") {
      $rsDbUsuarios = $cldbusuarios->sql_record($cldbusuarios->sql_query("","id_usuario","","login = '".$iLogin."'"));  

      if ($cldbusuarios->numrows > 0) {
      	 $oDbUsuario = db_utils::fieldsMemory($rsDbUsuarios,0);
      	 
         if ($sqlerro == false && $sStatus != 3) {
            $clcertvalonlineusuario->w20_certidaovalidaonline = $clcertvalonline->w18_sequencial;
   	        $clcertvalonlineusuario->w20_id_usuario           = $oDbUsuario->id_usuario; 
   	        $clcertvalonlineusuario->incluir(null);	
	        
	        if ($clcertvalonlineusuario->erro_status == 0) {
	           $sqlerro  = true;
	           $erro_msg = $clcertvalonlineusuario->erro_msg;           
	        }
         } 	
      }
   }

  db_fim_transacao($sqlerro);
  
  if ($sqlerro == true) {
  	db_msgbox($erro_msg);
  }

  if (isset($oPost->verificador) && $oPost->verificador == "") {
    $sErro = true;
    db_msgbox('Código Identificador dever ser Preenchido!');
  } else {
    if (isset($oPost->verificador) && $oPost->verificador != "") {	  
      $clcertidaoweb->sql_record($clcertidaoweb->sql_query("","codcert,cerdtvenc,ceracesso","","ceracesso = '".$oPost->verificador."'"));

      if ($clcertidaoweb->numrows == 0) {
        $sErro = true;
        db_msgbox('Código de Autenticidade Inválido');
      } else {
  	    $sUrl = "toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height=500,width=700";
        flush();
        echo "
           <script>
              window.open('gerador.php?".base64_encode('cod='.$verificador)."','','".$sUrl."');
           </script>";
      }
    }	
  }  
}
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/db_script.js"></script>
<style type="text/css">
<?db_estilosite();?>
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>" onLoad="" <? mens_OnHelp() ?>>
<br /><br /><br />
<center>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texto">
  <tr>
    <td height="60" align="<?=$DB_align1?>">
      <?=$DB_mens1?>
    </td>
  </tr>
  <tr align="center">
    <td>
      <form name="form1" method="post">
      <table width="100%" border="0" class="texto">
        <tr>
          <td width="42%" align="right">
            Código:&nbsp;
          </td>
          <td width="58%" align="left">
            <input id="verificador" name="verificador" type="text" value="" size="50" maxlength="50">
          </td>
        </tr>
        <tr>
          <td align="center">&nbsp; </td>
          <td align="left">
            <input class="botao" type="submit" id="verificar" name="submit" value="Verificar">
          </td>
        </tr>
      </table>
      </form>
    </td>
  </tr>
  <tr>
    <td height="60" align="<?=$DB_align2?>">
      <?=$DB_mens2?>
    </td>
  </tr>
</table>
</center>
<?
  db_logs("","",0,"Verifica Codigo Autenticidade de Certidão.");
?>
</body>
</html>