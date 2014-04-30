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
require_once("dbforms/db_funcoes.php");
require_once('libs/db_utils.php');
require_once("libs/JSON.php");

$oPost = db_utils::postMemory($_POST);

$clportaria                = new cl_portaria;
$classenta                 = new cl_assenta;
$clrhparam		             = new cl_rhparam;
$clrhpessoal               = new cl_rhpessoal;
$clportariaassenta         = new cl_portariaassenta;
$clportariatipo            = new cl_portariatipo;
$clportariatipodoccoletiva = new cl_portariatipodoccoletiva;
$oJson                     = new services_json();

$db_opcao        = 1;
$db_opcao_numero = 3;
$lSqlErro        = false;

if (isset($oPost->incluir)) {

  db_inicio_transacao();

    // Inclui tabela Portaria
    
	$clportaria->h31_portariatipo = $oPost->h31_portariatipo;  
  $clportaria->h31_usuario	    = db_getsession('DB_id_usuario');
  $clportaria->h31_anousu	      = $oPost->h31_anousu;
  $clportaria->h31_dtportaria   = $oPost->h31_dtportaria_ano."-".$oPost->h31_dtportaria_mes."-".$oPost->h31_dtportaria_dia;
  $clportaria->h31_dtinicio	    = $oPost->h31_dtinicio_ano."-".$oPost->h31_dtinicio_mes."-".$oPost->h31_dtinicio_dia;
  $clportaria->h31_dtlanc		    = date("Y-m-d",db_getsession("DB_datausu"));
  $clportaria->h31_amparolegal  = $oPost->h31_amparolegal;
  	
  // Consulta TipoAsse

  $rsConsultaTipoAsse = $clportariatipo->sql_record($clportariatipo->sql_query($oPost->h31_portariatipo,"h30_tipoasse",null,""));
    
  $oTipoAsse = db_utils::fieldsMemory($rsConsultaTipoAsse,0);
    
    // Inclui tabela Assenta
    
	$classenta->h16_assent = $oTipoAsse->h30_tipoasse;
	$classenta->h16_dtconc = $oPost->h16_dtconc_ano."-".$oPost->h16_dtconc_mes."-".$oPost->h16_dtconc_dia;
	$classenta->h16_histor = $oPost->h16_histor; 
	$classenta->h16_atofic = substr($oPost->h16_atofic,0,15); 
	$classenta->h16_quant  = $oPost->h16_quant; 
	$classenta->h16_perc   = "0";
	
	if ( isset($oPost->h16_dtterm) && trim($oPost->h16_dtterm) != '' ) { 
 	  $classenta->h16_dtterm = $oPost->h16_dtterm_ano."-".$oPost->h16_dtterm_mes."-".$oPost->h16_dtterm_dia;
	}
	 
	$classenta->h16_hist2  = "";
	$classenta->h16_login  = db_getsession("DB_id_usuario");
	$classenta->h16_dtlanc = date("Y-m-d",db_getsession("DB_datausu"));
	$classenta->h16_conver = "false";

	$sWhereRhParam  = " h36_ultimaportaria is not null and h36_instit = ".db_getsession("DB_instit");
	$sSqlRhParam    = $clrhparam->sql_query_file(null, "h36_ultimaportaria", null, $sWhereRhParam);
  $rsDadosRhParam = $clrhparam->sql_record($sSqlRhParam);
	
	if ( $clrhparam->numrows > 0 ) {
		$lSeqAutomatico = true;
	} else {
		$lSeqAutomatico = false;
	}
	
  $aObjFunctionarios = $oJson->decode(str_replace("\\","",$oPost->listaFuncionarios));
  
  if ($oPost->selTipoPortaria == "l") {
  	
		foreach ($aObjFunctionarios->aRetorno as $iInd => $oFuncionario ) {
			  	
	  	if ( $lSeqAutomatico ) {
	  	  
			  $sSqlSequence       = " select nextval('rhparam_h36_ultimaportaria_seq') as seq ";	
				$rsConsultaSequence = db_query($sSqlSequence);
				$oSeqPortaria       = db_utils::fieldsMemory($rsConsultaSequence,0);
				$iNroPort           = $oSeqPortaria->seq;
	  	} else {
	  		$iNroPort           = $oPost->h31_numero++;
	  	}
	  	
	  	if ( !isset($iNroPortInicial) ) {
	  		$iNroPortInicial = $iNroPort;
	  	}
	  	
		  $clportaria->h31_numero = $iNroPort;	  
		  $clportaria->incluir(null);
		  
		  if ($clportaria->erro_status == 0) {
		    
		   	$sMsgErro = $clportaria->erro_msg;
		  	$lSqlErro = true;
		  }	
		  
		  if (!$lSqlErro) {
		    
		  	$classenta->h16_regist = $oFuncionario->iMatric;
		    $classenta->h16_histor = trim($oPost->h16_histor." ".$oFuncionario->sObs);
		    $classenta->h16_nrport = $iNroPort;	  
		    $classenta->h16_anoato = db_getsession("DB_anousu");
		    
		  	$classenta->incluir(null);
		  	
		  	if ($classenta->erro_status == 0) {
		  	  
			    $sMsgErro = $classenta->erro_msg;
		  	  $lSqlErro = true;	  		
		  	}
		  }
	
		  
		  if (!$lSqlErro) {
		    
		    $clportariaassenta->h33_assenta  = $classenta->h16_codigo;
		    $clportariaassenta->h33_portaria = $clportaria->h31_sequencial;
		    $clportariaassenta->incluir(null);
		    	  
		  	if ($clportariaassenta->erro_status == 0) {
		  	  
			    $sMsgErro = $clportariaassenta->erro_msg;
		  	  $lSqlErro = true;	  		
		  	}	    
		  }
		}
		
  } else if ($oPost->selTipoPortaria == "c") {
  	
    if ( $lSeqAutomatico ) {
      
      $sSqlSequence       = " select nextval('rhparam_h36_ultimaportaria_seq') as seq ";  
      $rsConsultaSequence = db_query($sSqlSequence);
      $oSeqPortaria       = db_utils::fieldsMemory($rsConsultaSequence,0);
      $iNroPort           = $oSeqPortaria->seq;
    } else {
      $iNroPort           = $oPost->h31_numero;
    }  	
  	
    if ( !isset($iNroPortInicial) ) {
      $iNroPortInicial = $iNroPort;
    }
        
  	$clportaria->h31_numero = $iNroPort;
  	$clportaria->incluir(null);
  	
		if ($clportaria->erro_status == 0) {
		  
		  $sMsgErro = $clportaria->erro_msg;
		  $lSqlErro = true;
		}	

		foreach ($aObjFunctionarios->aRetorno as $iInd => $oFuncionario ) {
		  
		  if (!$lSqlErro) {
		  	
		  	$classenta->h16_regist = $oFuncionario->iMatric;
		  	$classenta->h16_histor = trim($oPost->h16_histor." ".$oFuncionario->sObs);
		    $classenta->h16_nrport = $iNroPort;
		    $classenta->h16_anoato = db_getsession("DB_anousu");
		    
		  	$classenta->incluir(null);
		  	
		  	if ($classenta->erro_status == 0) {
		  	  
			    $sMsgErro = $classenta->erro_msg;
		  	  $lSqlErro = true;	  		
		  	}
		  }
		  
		  if (!$lSqlErro) {
	
		  	// Inclui tabela Portariaassenta
		
		    $clportariaassenta->h33_assenta  = $classenta->h16_codigo;
		    $clportariaassenta->h33_portaria = $clportaria->h31_sequencial;
		    $clportariaassenta->incluir(null);
		    
		  	if ($clportariaassenta->erro_status == 0) {
		  	  
			    $sMsgErro = $clportariaassenta->erro_msg;
		  	  $lSqlErro = true;	  		
		  	}	    
		  }  
		}  	
  }
  
  if (!$lSqlErro && $lSeqAutomatico) {
  	
    $sSqlSequence       = " select last_value as seq from rhparam_h36_ultimaportaria_seq";  
    $rsConsultaSequence = db_query($sSqlSequence);
    $oSeqPortaria       = db_utils::fieldsMemory($rsConsultaSequence,0);
  	
  	$clrhparam->h36_ultimaportaria = $oSeqPortaria->seq;
    $clrhparam->h36_instit         = db_getsession("DB_instit");
  	$clrhparam->alterar(db_getsession("DB_instit"));
  	
  	if ( $clrhparam->erro_status == "0" ) {
  	  
  		$lSqlErro = true;
  		$sMsgErro = $clrhparam->erro_msg;
  	}
  }
  
  db_fim_transacao($lSqlErro);
} else {
	
  $sSqlConsultaParametros = $clrhparam->sql_query_file(null,"h36_ultimaportaria ",null," h36_ultimaportaria is not null and h36_instit = ".db_getsession("DB_instit"));
  $rsConsultaParametros   = $clrhparam->sql_record($sSqlConsultaParametros);

  if ($clrhparam->numrows == 0) {
	  $db_opcao_numero = 1;
  } else {
    
    $oParametros = db_utils::fieldsMemory($rsConsultaParametros,0);
    $h31_numero  = $oParametros->h36_ultimaportaria + 1;
  }
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/libjson.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/geradorrelatorios.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
  <table>
    <tr> 
      <td> 
	    <?
	      include("forms/db_frmportarialotecol.php");
	    ?>
	  </td>
    </tr>
  </table>
</center>
</body>
</html>
<script>
js_tabulacaoforms("form1","h31_portariatipo",true,1,"h31_portariatipo",true);
</script>
<?
if(isset($incluir)){
  
  if ($lSqlErro){
  	
  	db_msgbox($sMsgErro);
  	
  } else {
  	
    $rsConsultaModelo = $clportariatipodoccoletiva->sql_record($clportariatipodoccoletiva->sql_query_file(null,"h38_modportariacoletiva",null," h38_portariatipo = {$oPost->h31_portariatipo}"));
    
    if ( $clportariatipodoccoletiva->numrows > 0 ) {
      $oModColetiva = db_utils::fieldsMemory($rsConsultaModelo,0);
      $iModelo      = $oModColetiva->h38_modportariacoletiva;
    } else {
	  $rsConsultaModelo = $clrhparam->sql_record($clrhparam->sql_query_file(null,"h36_modportariacoletiva",null," h36_instit = ".db_getsession("DB_instit"))); 
      if ($clrhparam->numrows > 0){
      	$oModColetiva = db_utils::fieldsMemory($rsConsultaModelo,0);
        $iModelo      = $oModColetiva->h36_modportariacoletiva;
      } else {
      	$iModelo = "";
      }
    	
    }
    
    echo "<script>";
    echo " document.form1.db_opcao.disabled  = true;";
    echo " js_imprimeConf('".$iNroPortInicial."','".$iNroPort."','".$iModelo."');";
    echo "</script>";
  }
  
}
?>