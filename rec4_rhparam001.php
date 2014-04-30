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
require_once("libs/db_usuariosonline.php");
require_once("classes/db_rhparam_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

$oPost     = db_utils::postmemory($HTTP_POST_VARS);
$clrhparam = new cl_rhparam;
$db_botao  = true;
$lSqlErro  = false;
$sMsgErro  = null;
$h36_ultimaportaria = null;

if(isset($oPost->incluir)){

  db_inicio_transacao();

	$clrhparam->h36_instit 				        = $oPost->h36_instit;
	$clrhparam->h36_modtermoposse 		    = $oPost->h36_modtermoposse;
  $clrhparam->h36_modportariacoletiva	  = $oPost->h36_modportariacoletiva;
  $clrhparam->h36_modportariaindividual = $oPost->h36_modportariaindividual;
  $clrhparam->h36_ultimaportaria 		    = $oPost->h36_ultimaportaria;
  $clrhparam->h36_intersticio   		    = $oPost->h36_intersticio;
  $clrhparam->h36_pontuacaominpromocao  = $oPost->h36_pontuacaominpromocao;
  $clrhparam->incluir($clrhparam->h36_instit);

	if ( $clrhparam->erro_status == '0' ) {
	  $lSqlErro = true;
 	  $sMsgErro = $clrhparam->erro_msg;
 	}

  if ( $oPost->h36_ultimaportaria !== '0' AND $oPost->h36_ultimaportaria !== '' ) {

    $iValorSeq = $oPost->h36_ultimaportaria;
    $sSqlInsereValorSeq = "select setval('rhparam_h36_ultimaportaria_seq',{$iValorSeq});";
    $rsInsereValorSeq   = db_query($sSqlInsereValorSeq);

    if ( !$rsInsereValorSeq ) {

      $sMsgErro = 'Erro ao inserir sequencial "Última Portaria"';
    }
  }

  db_fim_transacao($lSqlErro);

  if( !$lSqlErro ) {
    $db_opcao = 2;
  }else {
    $db_opcao = 1;
  }

} elseif ( isset($oPost->alterar) ) {

  db_inicio_transacao();

  $clrhparam->h36_instit 				         = $oPost->h36_instit;
  $clrhparam->h36_modtermoposse 		     = $oPost->h36_modtermoposse;
  $clrhparam->h36_modportariacoletiva	   = $oPost->h36_modportariacoletiva;
  $clrhparam->h36_modportariaindividual  = $oPost->h36_modportariaindividual;
  $clrhparam->h36_ultimaportaria 		     = $oPost->h36_ultimaportaria;
  $clrhparam->h36_intersticio   		     = $oPost->h36_intersticio;
  $clrhparam->h36_pontuacaominpromocao   = $oPost->h36_pontuacaominpromocao;
  $clrhparam->alterar($oPost->h36_instit);

  if ($clrhparam->erro_status == '0') {
 	  $lSqlErro = true;
 	  $sMsgErro = $clrhparam->erro_msg;
 	}

  if ( $oPost->h36_ultimaportaria !== '0' AND $oPost->h36_ultimaportaria !== '' ) {

    $iValorSeq = $oPost->h36_ultimaportaria;
    $sSqlInsereValorSeq = "select setval('rhparam_h36_ultimaportaria_seq',{$iValorSeq});";
    $rsInsereValorSeq   = db_query($sSqlInsereValorSeq);

    if ( !$rsInsereValorSeq ) {

      $lSqlErro = true;
      $sMsgErro = 'Erro ao inserir sequencial "Última Portaria"';
    }
  }

  db_fim_transacao($lSqlErro);

  $db_opcao = 2;
  $db_botao = true;

} else {

   $sCampos  = "h36_instit,                        			    ";
   $sCampos .= "nomeinst,		 				    	                 	";
   $sCampos .= "h36_modtermoposse, 				    		          ";
   $sCampos .= "a.db63_nomerelatorio as descrModTermo,	    ";
   $sCampos .= "h36_modportariacoletiva,					          ";
   $sCampos .= "b.db63_nomerelatorio as descrModColetiva,   ";
   $sCampos .= "h36_modportariaindividual,		    	       	";
   $sCampos .= "c.db63_nomerelatorio as descrModIndividual, ";
   $sCampos .= "h36_ultimaportaria,				    	           	";
   $sCampos .= "h36_intersticio,				    	           	  ";
   $sCampos .= "h36_pontuacaominpromocao	                  ";

   $sSqlrhparam     = $clrhparam->sql_query_rhparam(null,$sCampos,null," h36_instit = ".db_getsession("DB_instit")."");
   $rsConsultaParam = $clrhparam->sql_record($sSqlrhparam);

   if ( $clrhparam->numrows > 0 ) {

   	 $db_opcao = 2;
     $db_botao = true;
     $oParam   = db_utils::fieldsMemory($rsConsultaParam,0);

     $h36_instit 				        = $oParam->h36_instit;
   	 $nomeinst				          = $oParam->nomeinst;
     $h36_modtermoposse 	      = $oParam->h36_modtermoposse;
   	 $h36_modportariacoletiva   = $oParam->h36_modportariacoletiva;
   	 $h36_modportariaindividual = $oParam->h36_modportariaindividual;
   	 $h36_ultimaportaria 		    = $oParam->h36_ultimaportaria;
   	 $descrModTermo		 	        = $oParam->descrmodtermo;
   	 $descrModColetiva  		    = $oParam->descrmodcoletiva;
   	 $descrModIndividual 		    = $oParam->descrmodindividual;
   	 $h36_intersticio   		    = $oParam->h36_intersticio;
   	 $h36_pontuacaominpromocao  = $oParam->h36_pontuacaominpromocao;

   } else {

   	 $sSqlInstit  = " select codigo,                              ";
   	 $sSqlInstit .= "	    nomeinst 							                  ";
   	 $sSqlInstit .= "   from db_config 			            				  ";
   	 $sSqlInstit .= "  where codigo  = ".db_getsession("DB_instit");

   	 $rsInstit = db_query($sSqlInstit) or die($sSqlInstit);
   	 $oInstit  = db_utils::fieldsMemory($rsInstit,0);

     $h36_instit = $oInstit->codigo;
     $nomeinst   = $oInstit->nomeinst;
   	 $db_opcao   = 1;
     $db_botao   = true;

   }

}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<br /><br />

<center>
  <table>
    <tr>
      <td>
        <?
          include("forms/db_frmrhparam.php");
        ?>
      </td>
    </tr>
  </table>
</center>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if( $lSqlErro ) {
  db_msgbox( $sMsgErro );
}

if ( isset($oPost->alterar) || isset($oPost->incluir) AND !$lSqlErro ) {

  if ( $clrhparam->erro_status == "0" ) {

    $clrhparam->erro(true, false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clrhparam->erro_campo != "") {

      echo "<script> document.form1.".$clrhparam->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhparam->erro_campo.".focus();</script>";
    }
  }else{
    $clrhparam->erro(true, false);
  }
}
?>