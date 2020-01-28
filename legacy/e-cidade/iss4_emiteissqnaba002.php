<?php
/**
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

set_time_limit(0);

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$cliframe_seleciona = new cl_iframe_seleciona;

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;

$sSql  = "  select *                                         ";
$sSql .= "    from cadescrito                                ";
$sSql .= "         inner join cgm on z01_numcgm = q86_numcgm ";
$sSql .= "order by z01_nome                                  ";

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default abas">
<form name="form1" method="post" action="" target=''>

  <table width="50%" height="100%" align="center" border="0">
    <tr>
       <td height="100%" valign="top" width="90%">
       <?php

         $cliframe_seleciona->sql           = $sSql;
         $cliframe_seleciona->campos        = "q86_numcgm, z01_nome";
         $cliframe_seleciona->legenda       = "Escritórios";
         $cliframe_seleciona->textocabec    = "darkblue";
         $cliframe_seleciona->textocorpo    = "black";
         $cliframe_seleciona->fundocabec    = "#aacccc";
         $cliframe_seleciona->fundocorpo    = "#ccddcc";
         $cliframe_seleciona->iframe_height = "400px";
         $cliframe_seleciona->iframe_width  = "100%";
         $cliframe_seleciona->iframe_nome   = "escrito";
         $cliframe_seleciona->chaves        = "q86_numcgm";
         $cliframe_seleciona->marcador      = true;
         $cliframe_seleciona->dbscript      = "onclick='parent.js_mandadados();'";
         $cliframe_seleciona->js_marcador   = "parent.js_mandadados();";
         $cliframe_seleciona->iframe_seleciona($db_opcao);
       ?>
       </td>
     </tr>
  </table>
</form>
</body>
</html>
<script type="text/javascript">

function js_mandadados(){

   var virgula = '';
   var dados   = '';
   for(i = 0;i < escrito.document.form1.elements.length;i++){

      if(escrito.document.form1.elements[i].type == "checkbox" &&  escrito.document.form1.elements[i].checked){
        dados   = dados + virgula + escrito.document.form1.elements[i].value;
	      virgula = ', ';
      }
   }
   parent.iframe_g1.document.form1.cgmescrito.value = dados;
}
</script>