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
require_once("classes/db_itbi_classe.php");

$oGet   = db_utils::postmemory($_GET);
$clitbi = new cl_itbi();
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
     
    $sCampos  = " distinct		 	                                                  ";
    $sCampos .= " it01_tipotransacao,                                             ";
    $sCampos .= " it04_descr,  		                                                ";
    $sCampos .= " it27_sequencial,                                                ";
    $sCampos .= " it27_descricao,  	                                              ";
    $sCampos .= " it26_valor,		                                                  ";
    $sCampos .= " it27_aliquota,   	                                              ";
    $sCampos .= " round((it26_valor * (it27_aliquota/100) ),2) as dl_Valor_Imposto";      
    $sSqlDadosForma = $clitbi->sql_query_pag($oGet->guia,$sCampos);
    db_lovrot($sSqlDadosForma,50,"()","","","");
  ?>
</div>
</body>
</html>