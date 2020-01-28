<?
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

require("libs/db_stdlib.php");
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_jsplibwebseller.php");

include("classes/db_agendamentos_ext_classe.php");
include("classes/db_ausencias_ext_classe.php");

include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

include("classes/db_sau_motivo_ausencia_classe.php");
$clmotivo_ausencia = new cl_sau_motivo_ausencia;

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
//$clmedicos                = new cl_medicos;
//$clunidademedicos         = new cl_unidademedicos;
//$clsau_tipoficha          = new cl_sau_tipoficha;
//$cldiasemana              = new cl_diasemana;
//$clundmedhorario          = new cl_undmedhorario_ext;

$clausencias    = new cl_ausencias_ext;
$clagendamentos = new cl_agendamentos_ext;

$db_botao = true;
$db_opcao = 1;
$db_opcao2= 1;
$datausu  = date("Y",db_getsession( "DB_datausu" ) )."/".
            date("m",db_getsession( "DB_datausu" ) )."/".
            date("d",db_getsession( "DB_datausu" ) );
            
if(isset($incluir)||isset($alterar)){
	$db_opcao = isset($alterar)?2:$db_opcao;	
	$db_opcao2 = isset($alterar)?22:$db_opcao;	
	
	$inicio_ano = substr( $sd06_d_inicio, 6, 4 );
	$inicio_mes = substr( $sd06_d_inicio, 3, 2 );
	$inicio_dia = substr( $sd06_d_inicio, 0, 2 );
	            
	$fim_ano = substr( $sd06_d_fim, 6, 4 );
	$fim_mes = substr( $sd06_d_fim, 3, 2 );
	$fim_dia = substr( $sd06_d_fim, 0, 2 );
	
	$clagendamentos->sql_record( $clagendamentos->sql_query_ext("","*",null,"sd27_i_codigo = $sd06_i_especmed  and sd23_d_consulta between '$inicio_ano/$inicio_mes/$inicio_dia'  and  '$fim_ano/$fim_mes/$fim_dia' ") );
	if( $clagendamentos->numrows > 0 ){
		$clausencias->erro_status = "0";
		$clausencias->erro_msg    = "Profissional não poderá ter o intervalo de Folga/Férias pois possui agendamento nesse período.";
		//$clausencias->erro_campo  = "sd23_d_consulta";
	}else{ 
	
		if(isset($incluir)){
			$res_agendamentos = $clagendamentos->sql_record( $clagendamentos->sql_query_ext("","*",null,"sd23_i_especmed = $sd06_i_especmed  and sd23_d_consulta between '$inicio_ano/$inicio_mes/$inicio_dia'  and  '$fim_ano/$fim_mes/$fim_dia' ") );
			 
			db_inicio_transacao();
			$clausencias->incluir(null);
			db_fim_transacao();
		}
		
		if(isset($alterar)){
			db_inicio_transacao();
			$clausencias->alterar($sd06_i_codigo);
			db_fim_transacao();
		}
	}
}

if(isset($excluir)){
	$db_opcao = 3;	
	db_inicio_transacao();
	$clausencias->excluir($sd06_i_codigo);
	db_fim_transacao();
}


//Botões Alterar/Excluir
if(isset($opcao)){
	$db_botao1 = true;
	$db_opcao  = $opcao=="alterar"?2:3;
	$db_opcao2 = $opcao=="alterar"?22:3;
	
	$result = $clausencias->sql_record($clausencias->sql_query_ext($sd06_i_codigo,"*, (sd06_d_fim - sd06_d_inicio) + 1 as sd06_i_qtd"));
	if( $clausencias->numrows > 0 ){
		db_fieldsmemory($result,0);
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
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC">
    <center>
        <br><br>
        <?
        include("forms/db_frmausencia.php");
        ?>
    </center>
        </td>
  </tr>
</table>
<?
db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
?>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd04_i_medico",true,1,"sd04_i_medico",true);
</script>
<?
if(isset($incluir)||isset($alterar)){
	if($clausencias->erro_status=="0"){
		$clausencias->erro(true,false);
		$db_botao=true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if($clausencias->erro_campo!=""){
			echo "<script> document.form1.".$clausencias->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clausencias->erro_campo.".focus();</script>";
		}
	}else{
		$clausencias->erro(true,false);
		db_redireciona("sau4_ausencia.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
	}
}
if(isset($excluir)){
	if($clausencias->erro_status=="0"){
		$clausencias->erro(true,false);
	}else{
		$clausencias->erro(true,false);
		db_redireciona("sau4_ausencia.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
	}
}
?>