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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));

include(modification("classes/db_sau_cgscorreto_classe.php"));
include(modification("classes/db_sau_cgserrado_classe.php"));
include(modification("classes/db_cgs_und_classe.php"));

parse_str ( $HTTP_SERVER_VARS ['QUERY_STRING'] );

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript"
	src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" onLoad="a=1">
<?
db_inicio_transacao ();
$clsau_cgscorreto = new cl_sau_cgscorreto ( );
$clsau_cgserrado = new cl_sau_cgserrado ( );
$clcgs_und = new cl_cgs_und ( );

$clsau_cgscorreto->s127_i_numcgs = $principal;
$clsau_cgscorreto->s127_d_data = date ( "Y-m-d", db_getsession ( "DB_datausu" ) );
$clsau_cgscorreto->s127_c_hora = date ( "H:m" );
$clsau_cgscorreto->s127_i_login = db_getsession ( "DB_id_usuario" );
$clsau_cgscorreto->s127_i_instit = db_getsession ( "DB_instit" );
$clsau_cgscorreto->s127_b_proc = 'false';
$clsau_cgscorreto->incluir ( 0 );
$erro = false;
if ($clsau_cgscorreto->erro_status == '1') {
	$sec = split ( "XX", $segundo );
	for($i = 0; $i < sizeof ( $sec ); $i ++) {

		$res = $clcgs_und->sql_record ( $clcgs_und->sql_query ( $sec [$i], 'z01_v_nome' ) );
		db_fieldsmemory ( $res, 0, 0 );

		$clsau_cgserrado->s128_v_nome = $z01_v_nome;
		$clsau_cgserrado->incluir ( $clsau_cgscorreto->s127_i_codigo, $sec [$i] );
		if ($clsau_cgserrado->erro_status == '0') {
			db_msgbox ( 'okok' );
			$erro_msg = $clsau_cgserrado->erro_msg;
			$erro = true;
			break;
		}
	}

} else {
	$erro_msg = $clsau_cgscorreto->erro_msg;
	$erro = true;
}
db_fim_transacao ( $erro );
?>
</body>
</html>
<?
if ($erro == true) {
	db_msgbox ( $erro_msg );
} else {
	db_msgbox ( 'Duplos salvo com sucesso.');
}
?>