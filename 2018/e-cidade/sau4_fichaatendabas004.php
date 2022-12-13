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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlibwebseller.php");

$z01_d_cadast_dia = date( "d", db_getsession("DB_datausu") );
$z01_d_cadast_mes = date( "m", db_getsession("DB_datausu") );
$z01_d_cadast_ano = date( "Y", db_getsession("DB_datausu") );
$z01_i_login      = DB_getsession("DB_id_usuario");

$oSauConfig = loadConfig("sau_config");

db_postmemory( $_POST );

$clprontuarios = new cl_prontuarios;
$clmedicocid   = new cl_medicocid;
$clprontcid    = new cl_prontcid;

$db_opcao = !isset($sd24_t_diagnostico)||empty($sd24_t_diagnostico)?1:2;
$db_botao = true;

if( isset( $lancar ) ) {

  $sSqlProntCid = $clprontcid->sql_query( "", "*", "sd55_i_codigo", "sd55_i_prontuario = {$chavepesquisaprontuario}" );
	$clprontcid->sql_record( $sSqlProntCid );

	if( isset($sd55_b_principal) && $sd55_b_principal == 'true' && $clprontcid->numrows > 0 ) {

    $sSqlProntCid = "update prontcid set sd55_b_principal = false where sd55_i_prontuario = {$chavepesquisaprontuario}";
		$clprontcid->sql_record( $sSqlProntCid );
	} else if( !isset($sd55_b_principal) && $clprontcid->numrows == 0 ) {
		$sd55_b_principal = "true";
	}

  $clprontcid->sd55_i_prontuario = $chavepesquisaprontuario;
  $clprontcid->sd55_b_principal  = isset( $sd55_b_principal ) ? 'true' : 'false';

  db_inicio_transacao();

  $clprontcid->incluir("");
  $incluir = true;

  db_fim_transacao();
  
	if( isset( $chaveprofissional ) ) {

    $sWhereMedicoCid = "sd56_i_profissional = {$chaveprofissional} and sd56_i_cid = {$sd55_i_cid}";
    $sSqlMedicoCid   = $clmedicocid->sql_query( "", "*", "sd56_i_codigo", $sWhereMedicoCid );
    $clmedicocid->sql_record( $sSqlMedicoCid );

    if(  $clmedicocid->numrows == 0 && (int)$sd55_i_cid != 0 ) {

      $clmedicocid->sd56_i_profissional = $chaveprofissional;
      $clmedicocid->sd56_i_cid          = $sd55_i_cid;

      db_inicio_transacao();

      $clmedicocid->incluir("");

      db_fim_transacao();
    }
	}
}

if( isset( $opcao ) && $opcao == "excluir" ){

  db_inicio_transacao();

  if( $sd55_b_principal == 't'){
  	$clprontcid->sql_record( "update prontcid 
  	                             set sd55_b_principal = true 
  	                           where sd55_i_codigo = ( select sd55_i_codigo 
  	                                                     from prontcid 
  	                                                    where sd55_i_prontuario = $chavepesquisaprontuario 
  	                                                      and sd55_i_codigo != $sd55_i_codigo 
  	                                                    limit 1 ) ");	
  }

  $clprontcid->excluir( $sd55_i_codigo );
  db_fim_transacao();
}

if( isset( $incluir ) || isset( $alterar ) ) {

  db_inicio_transacao();
  
  if( !empty( $chavepesquisaprontuario ) ) {

    $clprontuarios->sd24_i_codigo = $chavepesquisaprontuario;
    $clprontuarios->alterar($chavepesquisaprontuario);
  }

  db_fim_transacao();
} else if( isset( $chavepesquisaprontuario ) && !empty( $chavepesquisaprontuario ) ) {

  $result = $clprontuarios->sql_record($clprontuarios->sql_query($chavepesquisaprontuario));
  db_fieldsmemory($result,0);
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <?
        include("forms/db_frmfichaatenddiag.php");
        ?>
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
  js_tabulacaoforms("form1","sd70_c_cid",true,1,"sd70_c_cid",true);
</script>
<?php
if( isset( $lancar ) ) {

  if( $clprontcid->erro_status == "0" ) {

    $clprontcid->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if( $clprontcid->erro_campo != "" ) {

      echo "<script> document.form1.".$clprontcid->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprontcid->erro_campo.".focus();</script>";
    }
  } else {
    $clprontcid->erro( true, false );
  }
}

if( isset( $incluir ) || isset( $alterar ) ) {

  if( $clprontuarios->erro_status == "0" ) {

    $clprontuarios->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if( $clprontuarios->erro_campo != "" ) {

      echo "<script> document.form1.".$clprontuarios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprontuarios->erro_campo.".focus();</script>";
    }
  } else {

    if( $clprontuarios->sd24_c_digitada == "S" ) {

    	echo "<script>
    	        parent.document.formaba.a4.disabled = true;
              if( parent.iframe_a4 == undefined ) {

                //Consulta médica
                parent.mo_camada('a1');
              } else {

                //FAA
                js_novaficha();
              }
    	     </script>";
    }
  }
}