<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once("classes/db_itburbano_classe.php");

$oGet = db_utils::postmemory($_GET);
$clitburbano = new cl_itburbano();
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body class="body-default">
<div class="container">
  <?php
     
    $sCampos  = " distinct		 	  ";
    $sCampos .= " it06_matric,		  ";
    $sCampos .= " it22_setor,  		  ";
    $sCampos .= " it22_quadra,   	  ";
    $sCampos .= " it22_lote,  		  ";
    $sCampos .= " it22_descrlograd,   ";
    $sCampos .= " it22_numero,  	  ";
    $sCampos .= " it22_compl,  	 	  ";
    $sCampos .= " it22_matricri,	  ";
    $sCampos .= " it22_setorri, 	  ";
    $sCampos .= " it22_quadrari,	  ";
    $sCampos .= " it22_loteri  	 	  ";
    $sSqlDadosImovel = $clitburbano->sql_query_dados($oGet->guia,$sCampos);
 		$funcao_js = "js_consultaDetalhes|it06_matric";
    db_lovrot($sSqlDadosImovel,50,"()","",$funcao_js,"");

  ?>
</div>
</body>
</html>
<script>
  function js_consultaDetalhes(iMatric){
    js_OpenJanelaIframe('top.corpo',"db_iframe_procjur",'cad3_conscadastro_002.php?cod_matricula='+iMatric,'Detalhes da Pesquisa',true);
  }
</script>