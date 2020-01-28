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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libcaixa.php");
require_once("classes/db_corrente_classe.php");
require_once("classes/db_cfautent_classe.php");

$clcorrente   = new cl_corrente;
$clcfautent   = new cl_cfautent;
$clautenticar = new cl_autenticar;
$ip    = db_getsession("DB_ip");
$porta = 5001;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#AAB7D5">
<table width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><font id="numeros" size="2">Processando...</font></td>
  </tr>
</table>
</body>
</html>
<?php
$clautenticar->verifica($ip,$porta);
if($clautenticar->erro==true){
 db_msgbox($clautenticar->erro_msg);
 echo "<script>parent.db_iframe_imprime.hide();</script>";
}else{
    if($tipo=='cabecalho'){
	$clautenticar->conectar($ip,$porta);
	$clautenticar->data_dia=$dia;
	$clautenticar->data_mes=$mes;
	$clautenticar->data_ano=$ano;
	$clautenticar->cabecalho();
	$clautenticar->fechar();
	if($clautenticar->erro==true){
	  db_msgbox($clautenticar->erro_msg);
	}
        echo "<script>parent.db_iframe_imprime.hide();</script>";
    }else if($tipo='autenticacao'){
         $result=$clcfautent->sql_record($clcfautent->sql_query_file(null,"k11_id",null,"k11_ipterm='$ip'"));
	 db_fieldsmemory($result,0);

	 $result = $clcorrente->sql_record($clcorrente->sql_query_file($k11_id,"$ano-$mes-$dia",null,"*","k12_autent"));
	 $clautenticar->conectar($ip,$porta);
	 for($i=0; $i<$clcorrente->numrows; $i++){
	   db_fieldsmemory($result,$i);
	   $clautenticar->imprimir_ln("$k12_valor");
	 }
	 $clautenticar->fechar();

 	 if($clautenticar->erro==true){
	    db_msgbox($clautenticar->erro_msg);
	 }

	 echo "<script>parent.db_iframe_imprime.hide();</script>";
    }else if($tipo='fechamento'){
	  echo "<script>parent.db_iframe_imprime.hide();</script>";
    }
}
?>