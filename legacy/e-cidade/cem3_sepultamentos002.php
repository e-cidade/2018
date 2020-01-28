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
require_once(modification("classes/db_sepultamentos_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clsepultamentos = new cl_sepultamentos;
$db_opcao = 3;

$sCampos = "cm01_i_codigo,cgm.z01_numcgm,cgm.z01_nome,cgm.z01_pai, cgm.z01_mae, cm01_c_conjuge, cm01_d_falecimento, cm01_c_cor,
           cm01_c_livro,
           cm01_i_folha,
           cm01_i_registro,
           cm01_i_medico,
           cm01_i_causa,
           cm04_c_descr,
           cm01_c_local,
           cm01_c_cartorio,
           cm01_i_hospital,
           cm01_i_funeraria,
           cm01_i_declarante,
           cgm3.z01_nome as nome_declarante,
           case when cm16_i_cemiterio is not null
                then cm16_c_nome
                when cm15_i_cemiterio is not null
                then cgm5.z01_nome
           end as nome_cemiterio,
           case when cm16_i_cemiterio is not null
                then cm16_i_cemiterio
                when cm15_i_cemiterio is not null
                then cm15_i_cemiterio
           end as cm01_i_cemiterio,
           case when cm01_i_medico is not null
                then cgm4.z01_nome
                else cm01_c_nomemedico
           end as cm32_nome,
           case when cm01_i_hospital is not null
                then cgm1.z01_nome
                else cm01_c_nomehospital
           end as nome_hospital,
           case when cm01_i_funeraria is not null
                then cgm2.z01_nome
                else cm01_c_nomefuneraria
           end as nome_funeraria ";

$result = $clsepultamentos->sql_record($clsepultamentos->sql_query($sepultamento, $sCampos));

db_fieldsmemory($result, 0);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default abas">
  <div class="container">
   <?php
    include(modification("forms/db_frmsepultamentos1.php"));
   ?>
  </div>
</body>
</html>