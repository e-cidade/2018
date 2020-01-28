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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_jsplibwebseller.php");

//include("classes/db_undmedhorario_classe.php");
include("classes/db_undmedhorario_ext_classe.php");
include("classes/db_medicos_classe.php");
include("classes/db_unidademedicos_classe.php");
include("classes/db_sau_tipoficha_classe.php");
include("classes/db_diasemana_classe.php");

include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clmedicos                = new cl_medicos;
$clunidademedicos         = new cl_unidademedicos;
$clsau_tipoficha          = new cl_sau_tipoficha;
$cldiasemana              = new cl_diasemana;
$clundmedhorario          = new cl_undmedhorario_ext;


$db_botao = true;
$db_opcao = 1;
$db_opcao2= 1;
$datausu  = date("Y",db_getsession( "DB_datausu" ) )."/".
            date("m",db_getsession( "DB_datausu" ) )."/".
            date("d",db_getsession( "DB_datausu" ) );
if(isset($incluir) || isset($alterar) ){
	$str_query = $clundmedhorario->sql_query_ext("","*",null, 
					"sd04_i_medico = $sd04_i_medico
					".(isset($alterar)||@$opcao=="alterar"?" and sd30_i_codigo <> $sd30_i_codigo":"")."
	               	 and sd30_i_diasemana = $sd30_i_diasemana
	                 and ( '$sd30_c_horaini' between sd30_c_horaini and sd30_c_horafim
	                    or '$sd30_c_horafim' between sd30_c_horaini and sd30_c_horafim  
	                    or sd30_c_horaini between  '$sd30_c_horaini' and '$sd30_c_horafim' 
	                    or sd30_c_horafim between  '$sd30_c_horaini' and '$sd30_c_horafim' ) " 
	                  );

	$str_query2 = "select * 
					from agendamentos 
					where sd23_i_undmedhor = $sd30_i_codigo  
					  and sd23_d_consulta >= '$sd30_d_valfinal_ano/$sd30_d_valfinal_mes/$sd30_d_valfinal_dia' "; 

	$res_horario = pg_query( $str_query ) or die( ">>>> $str_query ");
	$res_agenda  = @pg_query( $str_query2 );
	$db_opcao    = isset($alterar)?2:$db_opcao;	
	$db_opcao2   = isset($alterar)?22:$db_opcao;	
	if( pg_num_rows( $res_horario ) > 0   ) {
		$clundmedhorario->erro_status = "0";
		$clundmedhorario->erro_msg    = "Profissional ja possui horário nesse intervalo.";
	}elseif( $sd30_d_valinicial_ano != "" && @pg_num_rows($res_agenda) > 0  ) {
		$clundmedhorario->erro_status = "0";
		$clundmedhorario->erro_msg    = "Profissional ja possui horário agendado nesse intervalo.";
	}else{             
		if(isset($incluir)){
			db_inicio_transacao();
			$clundmedhorario->incluir(null);
			db_fim_transacao();
		}
		
		if(isset($alterar)){
			db_inicio_transacao();
			$clundmedhorario->alterar($sd30_i_codigo);
			db_fim_transacao();
		}
	}
}

if(isset($excluir)){
	$sql = "select *
           from agendamentos
          inner join especmedico    on sd27_i_codigo = sd23_i_especmed 
          inner join unidademedicos on sd04_i_codigo = sd27_i_undmed
          where sd23_d_consulta >= '$datausu'
            and sd23_i_unidmed = $sd30_i_undmed
            and extract(dow from sd23_d_consulta ) = $sd30_i_diasemana ";
	$result = pg_exec( $sql );
	if( pg_numrows( $result ) > 0 ){
		echo "<script>alert('Profissional tem agendamentos efetuadas posteriormente. Não permitindo a exclusão do horário')</script>";
	}else{
		db_inicio_transacao();
		$clundmedhorario->excluir($sd30_i_codigo);
		db_fim_transacao();
	}
}


//Botões Alterar/Excluir
if(isset($opcao)){
	$db_botao1 = true;
	$db_opcao  = $opcao=="alterar"?2:3;
	$db_opcao2 = $opcao=="alterar"?22:3;
	
	$result = $clundmedhorario->sql_record($clundmedhorario->sql_query_ext($sd30_i_codigo));
	if( $clundmedhorario->numrows > 0 ){
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
        <?
        include("forms/db_frmundmedhorario006.php");
        ?>
    </center>
        </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd30_i_undmed",true,1,"sd30_i_undmed",true);
</script>
<?
if(isset($incluir)||isset($alterar)){
	if($clundmedhorario->erro_status=="0"){
		$clundmedhorario->erro(true,false);
		$db_botao=true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if($clundmedhorario->erro_campo!=""){
			echo "<script> document.form1.".$clundmedhorario->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clundmedhorario->erro_campo.".focus();</script>";
		}
	}else{
		$clundmedhorario->erro(true,false);
		db_redireciona("sau1_undmedhorario006.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
	}
}
if(isset($excluir)){
	if($clundmedhorario->erro_status=="0"){
		$clundmedhorario->erro(true,false);
	}else{
		$clundmedhorario->erro(true,true);
		db_redireciona("sau1_undmedhorario006.php?sd04_i_medico=$sd04_i_medico&z01_nome=$z01_nome");
	}
}
?>