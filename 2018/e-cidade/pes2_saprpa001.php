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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_cfautent_classe.php");
require_once("classes/db_saltes_classe.php");

$rotulocampo = new rotulocampo;
$rotulocampo->label("k11_id");
$rotulocampo->label("k13_conta");

$k00_dtoper     = date('Y-m-d',db_getsession("DB_datausu"));
$k00_dtoper_dia = date('d',db_getsession("DB_datausu"));
$k00_dtoper_mes = date('m',db_getsession("DB_datausu"));
$k00_dtoper_ano = date('Y',db_getsession("DB_datausu"));

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_relatorio() {
  var F = document.form1;
  var datai = F.datai_ano.value+'-'+F.datai_mes.value+'-'+F.datai_dia.value;
  var dataf = F.dataf_ano.value+'-'+F.dataf_mes.value+'-'+F.dataf_dia.value;

  jan = window.open('pes2_saprpa002.php?dataini='+datai+'&datafin='+dataf,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" >
  <div class="container">
    <form name="form1" method="post" action="">
      <fieldset>
        <legend>Emite RPA</legend>
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td align="right" nowrap><strong>Data inicial:</strong></td>
              <td align="left"  nowrap>&nbsp;&nbsp;&nbsp;
                <?=db_data("datai",$k00_dtoper_dia,$k00_dtoper_mes,$k00_dtoper_ano)?>
              </td>
            </tr>
            <tr>
              <td align="right" nowrap><strong>Data final:</strong></td>
              <td align="left"  nowrap>&nbsp;&nbsp;&nbsp;
                <?=db_data("dataf",$k00_dtoper_dia,$k00_dtoper_mes,$k00_dtoper_ano)?>
              </td>
            </tr>
          </table>
      </fieldset>
      <input name="emite" type="button" id="emite" onClick="js_relatorio()" value="Emite"> &nbsp; &nbsp;&nbsp; &nbsp;
    </form>
  </div>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>