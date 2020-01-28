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

include("classes/db_sau_prestadorhorarios_classe.php");
include("classes/db_sau_tipoficha_classe.php");
include("classes/db_diasemana_classe.php");

include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clsau_tipoficha          = new cl_sau_tipoficha;
$cldiasemana              = new cl_diasemana;
$clsau_prestadorhorarios  = new cl_sau_prestadorhorarios;


$db_botao = true;
$db_opcao = 1;
$db_opcao2= 1;
$datausu  = date("Y",db_getsession( "DB_datausu" ) )."/".
            date("m",db_getsession( "DB_datausu" ) )."/".
            date("d",db_getsession( "DB_datausu" ) );
            
if(isset($incluir) || isset($alterar) ){
	$str_query = $clsau_prestadorhorarios->sql_query("","*",null, 
					"s112_i_prestadorvinc = $s112_i_prestadorvinc
					".(isset($alterar)||@$opcao=="alterar"?" and s112_i_codigo <> $s112_i_codigo":"")."
	               	 and s112_i_diasemana = $s112_i_diasemana
	                 and ( '$s112_c_horaini' between s112_c_horaini and s112_c_horafim
	                    or '$s112_c_horafim' between s112_c_horaini and s112_c_horafim  
	                    or s112_c_horaini between  '$s112_c_horaini' and '$s112_c_horafim' 
	                    or s112_c_horafim between  '$s112_c_horaini' and '$s112_c_horafim' ) " 
	                  );

	$str_query2 = "select * 
					from sau_agendaexames 
					where s113_i_prestadorhorarios = $s112_i_codigo  
					  and s113_d_exame >= '$s112_d_valfinal_ano/$s112_d_valfinal_mes/$s112_d_valfinal_dia' "; 

	$res_horario = pg_query( $str_query ) or die( ">>>> $str_query ");
	$res_agenda  = @pg_query( $str_query2 );
	
	$db_opcao    = isset($alterar)?2:$db_opcao;	
	$db_opcao2   = isset($alterar)?22:$db_opcao;	
	if( pg_num_rows( $res_horario ) > 0   ) {
		$clsau_prestadorhorarios->erro_status = "0";
		$clsau_prestadorhorarios->erro_msg    = "Profissional ja possui hor�rio nesse intervalo.";
	}elseif( $s112_d_valinicial_ano != "" && @pg_num_rows($res_agenda) > 0  ) {
		$clsau_prestadorhorarios->erro_status = "0";
		$clsau_prestadorhorarios->erro_msg    = "Profissional ja possui hor�rio agendado nesse intervalo.";
	}else{             
		if(isset($incluir)){
			db_inicio_transacao();
			$clsau_prestadorhorarios->incluir(null);
			db_fim_transacao();
		}
		
		if(isset($alterar)){
			db_inicio_transacao();
			$clsau_prestadorhorarios->alterar($s112_i_codigo);
			db_fim_transacao();
		}
	}
}

if(isset($excluir)){
	$sql = "select *
           from sau_agendaexames
          where s113_d_exame >= '$datausu'
            and s113_i_prestadorhorarios = $s112_i_codigo
            and extract(dow from s113_d_exame ) = $s112_i_diasemana ";
	$result = pg_exec( $sql );
	if( pg_numrows( $result ) > 0 ){
		echo "<script>alert('Profissional tem agendamentos efetuadas posteriormente. N�o permitindo a exclus�o do hor�rio')</script>";
	}else{
		db_inicio_transacao();
		$clsau_prestadorhorarios->excluir($s112_i_codigo);
		db_fim_transacao();
	}
}


//Bot�es Alterar/Excluir
if(isset($opcao)){
	$db_botao1 = true;
	$db_opcao  = $opcao=="alterar"?2:3;
	$db_opcao2 = $opcao=="alterar"?22:3;
	
	$result = $clsau_prestadorhorarios->sql_record($clsau_prestadorhorarios->sql_query($s112_i_codigo));
	if( $clsau_prestadorhorarios->numrows > 0 ){
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
        include("forms/db_frmsau_prestadorhorarios.php");
        ?>
    </center>
        </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","s112_i_undmed",true,1,"s112_i_undmed",true);
</script>
<?
if(isset($incluir)||isset($alterar)){
	if($clsau_prestadorhorarios->erro_status=="0"){
		$clsau_prestadorhorarios->erro(true,false);
		$db_botao=true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if($clsau_prestadorhorarios->erro_campo!=""){
			echo "<script> document.form1.".$clsau_prestadorhorarios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clsau_prestadorhorarios->erro_campo.".focus();</script>";
		}
	}else{
		$clsau_prestadorhorarios->erro(true,false);
		db_redireciona("sau1_sau_prestadorhorarios001.php?s111_i_prestador=$s111_i_prestador&z01_nome=$z01_nome");
	}
}
if(isset($excluir)){
	$clsau_prestadorhorarios->erro(true,false);
	if($clsau_prestadorhorarios->erro_status!="0"){
		db_redireciona("sau1_sau_prestadorhorarios001.php?s111_i_prestador=$s111_i_prestador&z01_nome=$z01_nome");
	}
}
?>