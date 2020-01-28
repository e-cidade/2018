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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("model/educacao/ArredondamentoNota.model.php"));
require_once(modification("libs/db_utils.php"));


parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);
$oDaoProcedimento       = new cl_procedimento();
$db_opcao               = 22;
$db_opcao1              = 3;
$db_botao               = false;
$possuiTurmasEncerradas = 'N';

if (isset($alterar)) {

  $db_opcao  = 2;
  $db_opcao1 = 3;
  $db_botao  = true;
  db_inicio_transacao();
  $oDaoProcedimento->alterar($ed40_i_codigo);
  db_fim_transacao();

} elseif (isset($chavepesquisa)) {

  $db_opcao         = 2;
  $db_opcao1        = 3;
  $sCampos  = " *, ";
  $sCampos .= " exists (select 1       ";
  $sCampos .= "          from regencia ";
  $sCampos .= "         where ed59_procedimento = procedimento.ed40_i_codigo           ";
  $sCampos .= "           and ed59_c_encerrada  = 'S' limit 1) AS possuiturmaencerrada ";
  $sSqlProcedimento = $oDaoProcedimento->sql_query_origem_procedimento($chavepesquisa, $sCampos);
  $rsProcedimento   = $oDaoProcedimento->sql_record($sSqlProcedimento);
  db_fieldsmemory($rsProcedimento, 0);

  $possuiTurmasEncerradas = ($possuiturmaencerrada == 't') ? 'S' : 'N';
  $db_botao               = !($possuiTurmasEncerradas == 'S');
  ?>
  <script>
    parent.document.formaba.a2.disabled    = false;
    parent.document.formaba.a2.style.color = "black";
    parent.document.formaba.a3.disabled    = false;
    parent.document.formaba.a3.style.color = "black";

    var hrefAvaliacoes  = 'edu1_avaliacoes.php?procedimento=<?=$ed40_i_codigo?>&ed40_c_descr=<?=$ed40_c_descr?>';
        hrefAvaliacoes += '&forma=<?=trim($ed37_c_tipo)?>&possuiTurmasEncerradas=<?=$possuiTurmasEncerradas;?>';

    var hrefProcescola  = 'edu1_procescola001.php?ed86_i_procedimento=<?=$ed40_i_codigo?>';
        hrefProcescola += '&ed40_c_descr=<?=$ed40_c_descr?>&possuiTurmasEncerradas=<?=$possuiTurmasEncerradas;?>';

    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href = hrefAvaliacoes;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href = hrefProcescola;
  </script>
  <?php
}
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <br>
     <center>
      <fieldset style="width:95%"><legend><b>Alteração de Procedimento de Avaliação</b></legend>
       <?php include(modification("forms/db_frmprocedimento.php"));?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<script>
js_tabulacaoforms("form1","ed40_c_descr",true,1,"ed40_c_descr",true);
</script>
<?php

  if (isset($chavepesquisa)) {

    if ($possuiTurmasEncerradas == "S") {
      db_msgbox('Este procedimento de avaliação não pode ser alterado ou excluído, pois existem turmas encerradas vinculadas a ele.');
    }
?>
  <script>
    iframe_aval.location.href = "edu1_procedimento004.php?codigo=<?=$ed40_i_formaavaliacao?>&possuiTurmasEncerradas=<?=$possuiTurmasEncerradas;?>";
  </script>
<?php
}

if (isset($alterar)) {

  if ($oDaoProcedimento->erro_status == "0") {

    $oDaoProcedimento->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoProcedimento->erro_campo != "") {

      echo "<script> document.form1.".$oDaoProcedimento->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoProcedimento->erro_campo.".focus();</script>";

    }

  } else {

    $oDaoProcedimento->erro(true,false);
    ?>
    <script>
     parent.document.formaba.a2.disabled = false;
     parent.document.formaba.a3.disabled = false;

     var hrefAvaliacoes  = 'edu1_avaliacoes.php?procedimento=<?=$ed40_i_codigo?>&ed40_c_descr=<?=$ed40_c_descr?>';
         hrefAvaliacoes += '&forma=<?=trim($ed37_c_tipo)?>&possuiTurmasEncerradas=<?=$possuiTurmasEncerradas;?>';

     var hrefProcescola  = 'edu1_procescola001.php?ed86_i_procedimento=<?=$ed40_i_codigo?>';
         hrefProcescola += '&ed40_c_descr=<?=$ed40_c_descr?>&possuiTurmasEncerradas=<?=$possuiTurmasEncerradas;?>';
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href = hrefAvaliacoes;
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href = hrefProcescola;
    </script>
    <?php

  }

}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>