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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/JSON.php"));

db_postmemory($HTTP_SERVER_VARS);

$oPost   = db_utils::postMemory($_POST);
$oGet    = db_utils::postMemory($_GET);
$oJson   = new services_json();
$oParam  = $oJson->decode(str_replace("\\","",$_GET["oProcesso"]));

$oProcesso = new stdClass();
$oProcesso->lProcessoSistema = $oParam->lProcessoSistema ;
$oProcesso->iProcesso        = $oParam->iProcesso        ;
$oProcesso->sTitular         = $oParam->sTitular         ;
$oProcesso->dDataProcesso    = implode("-", array_reverse(explode("/",$oParam->dDataProcesso)));

db_putsession("oDadosProcesso", $oProcesso);
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  </head>
  <body class="body-default">
    <div class="container">
     <form name="form1">
      <fieldset>
        <legend>Selecione a procedência para cada receita</legend>
        <table>

  	     <tr>
  	       <td>
              <br>
  	         <iframe name="iframe" id="iframe"
  	                 marginwidth="0"
  	                 marginheight="0"
  	                 frameborder="0"
  	                 src="div4_importadivida003.php?chave_origem=<?=$k00_tipo_or?>&chave_destino=<?=$k00_tipo_des?>&tipoparc=<?=$tipoparc?>&uni=<?=$uni?>&datavenc=<?=$datavenc?>" width="850" height="300"></iframe>
  	       </td>
  	     </tr>

         <tr>
  	       <td width="100%" align="center" valign="top">
              <div id='process' style='visibility:visible'><strong><blink>Processando...</blink></strong></div>
  	       </td>
  	     </tr>

       </table>
    </fieldset>

	  <input disabled name="gerar" type="button" id="gerar" value="Gerar Dados" onClick="js_verificar()" />

    </form>
   </div>
  </body>
</html>
<script type="text/javascript">
function js_verificar(){

  cont = 0;
  pass = 'f';
  for (i = 0; i < iframe.document.form1.length; i++) {

    if (iframe.document.form1.elements[i].type == "select-one") {

      if (iframe.document.form1.elements[i].value != 0) {

	      cont++;
        pass = 't';
      }
    }
  }
  if (pass == 't') {

    iframe.document.form1.procreg.value = 't';
    iframe.document.form1.submit();
    document.getElementById('process').style.visibility='visible';
  }
}
</script>