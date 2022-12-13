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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

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

if( isset( $incluir ) || isset( $alterar ) ) {

  $sWhere    = "s112_i_prestadorvinc = {$s112_i_prestadorvinc}
					     ".(isset($alterar)||@$opcao=="alterar"?" and s112_i_codigo <> $s112_i_codigo":"")."

	               	 and s112_i_diasemana = $s112_i_diasemana
	                 and ( '$s112_c_horaini' between s112_c_horaini and s112_c_horafim
	                    or '$s112_c_horafim' between s112_c_horaini and s112_c_horafim
	                    or s112_c_horaini between  '$s112_c_horaini' and '$s112_c_horafim'
	                    or s112_c_horafim between  '$s112_c_horaini' and '$s112_c_horafim' )
	                    and s112_c_tipograde <> 'M'";
	$str_query = $clsau_prestadorhorarios->sql_query( "", "*", null, $sWhere );

	if ( $s112_i_diasemana_atual != $s112_i_diasemana ) {
		$iDiaSemana = ($s112_i_diasemana_atual - 1);
	} else {
		$iDiaSemana = null;
	}

	$str_query2    = "select * ";
  $str_query2   .= "  from sau_agendaexames ";
  $str_query2   .= " where s113_d_exame >= '{$datausu}' ";
  $str_query2   .= "   and s113_i_prestadorhorarios = {$s112_i_codigo} ";
  $str_query2   .= "   and extract(dow from s113_d_exame ) = {$iDiaSemana} ";
	$res_horario = db_query( $str_query ) or die( ">>>> $str_query ");
	$res_agenda  = @db_query( $str_query2 );

	$db_opcao    = isset( $alterar ) ? 2 : $db_opcao;
	$db_opcao2   = isset( $alterar ) ? 22 : $db_opcao;

	if( pg_num_rows( $res_horario ) > 0   ) {

		$clsau_prestadorhorarios->erro_status = "0";
		$clsau_prestadorhorarios->erro_msg    = "Prestadora já possui horário nesse intervalo.";
	} else if( $s112_d_valinicial_ano != "" && @pg_num_rows($res_agenda) > 0  ) {

		$clsau_prestadorhorarios->erro_status 				= "0";
		$clsau_prestadorhorarios->erro_campo_select  	= "s112_i_diasemana";
		$clsau_prestadorhorarios->erro_msg    				= "Prestadora já possui horário agendado nesse intervalo.";
	} else {

		if( isset( $incluir ) ) {

			db_inicio_transacao();
			$clsau_prestadorhorarios->incluir(null);
			db_fim_transacao();
		}

		if( isset( $alterar ) ) {

			db_inicio_transacao();
			$clsau_prestadorhorarios->alterar($s112_i_codigo);
			db_fim_transacao();
		}
	}
}

if( isset( $excluir ) ) {

	$sql    = "select 1 ";
  $sql   .= "  from sau_agendaexames ";
  $sql   .= " where s113_i_prestadorhorarios = {$s112_i_codigo} ";
	$result = db_query( $sql );

	if( pg_numrows( $result ) > 0 ) {
		echo "<script>alert('Prestadora tem agendamentos efetuadas posteriormente, não sendo permitida a exclusão do horário')</script>";
	} else {

		db_inicio_transacao();
		$clsau_prestadorhorarios->excluir($s112_i_codigo);
		db_fim_transacao();
	}
}

//Botões Alterar/Excluir
if( isset( $opcao ) ) {

	$db_botao1 = true;
	$db_opcao  = $opcao == "alterar" ? 2 : 3;
	$db_opcao2 = $opcao == "alterar" ? 22 : 3;

	$result = $clsau_prestadorhorarios->sql_record( $clsau_prestadorhorarios->sql_query( $s112_i_codigo ) );

	if( $clsau_prestadorhorarios->numrows > 0 ) {
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
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBInputHora.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
      <center>
        <?php
        include(modification("forms/db_frmsau_prestadorhorarios.php"));
        ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","sd63_c_procedimento",true,1,"sd63_c_procedimento",true);
</script>
<?php
if(isset($incluir)||isset($alterar)){
	if($clsau_prestadorhorarios->erro_status=="0"){
		$clsau_prestadorhorarios->erro(true,false);
		$db_botao=true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if($clsau_prestadorhorarios->erro_campo!=""){
			echo "<script> document.form1.".$clsau_prestadorhorarios->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clsau_prestadorhorarios->erro_campo.".focus();</script>";
		}
		if($clsau_prestadorhorarios->erro_campo_select!=""){
			echo "<script> document.form1.".$clsau_prestadorhorarios->erro_campo_select.".value='" . $s112_i_diasemana_atual . "';</script>";
		}
    db_redireciona("sau1_sau_prestadorhorarios001.php?s111_i_prestador=$s111_i_prestador&z01_nome=$z01_nome");
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