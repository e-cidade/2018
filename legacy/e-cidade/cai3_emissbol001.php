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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_cfautent_classe.php");
include("classes/db_saltes_classe.php");
$rotulocampo = new rotulocampo;
$rotulocampo->label("k11_id");
$rotulocampo->label("k13_conta");
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
  jan = window.open('cai3_emissbol002.php?datai='+datai+'&dataf='+dataf+'&caixa='+document.form1.k11_id.value+'&conta='+document.form1.k13_conta.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_relatorio1() {
  var F = document.form1;
  var datai = F.datai_ano.value+'-'+F.datai_mes.value+'-'+F.datai_dia.value;
  var dataf = F.dataf_ano.value+'-'+F.dataf_mes.value+'-'+F.dataf_dia.value;
  jan = window.open('cai3_emissbol003.php?datai='+datai+'&dataf='+dataf+'&caixa='+document.form1.k11_id.value+'&conta='+document.form1.k13_conta.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"><br><br>
	<center>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td width="22%" height="25" nowrap><strong>Data Inicial:</strong></td>
              <td width="78%" height="25" nowrap>&nbsp;&nbsp;
                <?=db_data("datai")?>
              </td>
            </tr>
            <tr>
              <td height="25" nowrap><strong>Data Final:</strong></td>
              <td height="25" nowrap>&nbsp;&nbsp;
                <?=db_data("dataf")?>
              </td>
            </tr>
            <tr>
              <td height="25" nowrap title="<?=$Tk11_id?>"><?=$Lk11_id?></td>
              <td height="25" nowrap>&nbsp; &nbsp;
                <?
				$clcfautent = new cl_cfautent;
				$result = $clcfautent->sql_record($clcfautent->sql_query("","k11_id#k11_local","k11_local"));
				db_selectrecord("k11_id",$result,true,2,"","","","0");
				?>
              </td>
            </tr>


            <tr>
              <td height="25" nowrap title="<?=$Tk13_conta?>"><?=$Lk13_conta?></td>
              <td height="25" nowrap>&nbsp; &nbsp;
                <?
				$clsaltes = new cl_saltes;
				$result = $clsaltes->sql_record($clsaltes->sql_query("","saltes.k13_conta#k13_descr","k13_descr"));
				db_selectrecord("k13_conta",$result,true,2,"","","","0");
				?>
              </td>
            </tr>


            <tr>
              <td
	          height="25" nowrap><input name="boletim" type="button" id="boletim" onClick="js_relatorio()" value="Boletim">
	      </td>
              <td
	          height="25" nowrap><input name="autentica" type="button" id="autentica" onClick="js_relatorio1()" value="Autenticação">
	      </td>
            </tr>
          </table>
        </form>
      </center>
	</td>
  </tr>
</table>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>