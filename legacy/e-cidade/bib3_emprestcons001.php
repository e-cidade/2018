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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_acervo_classe.php"));

$clacervo = new cl_acervo;
$clacervo->rotulo->label('bi06_seq');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, strings.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<?php MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto").""); ?>

<form name="form1" method="post">
  <center>

   <fieldset style="width:700px;margin-top:25px">
     <legend><b>Consulta de Empréstimos por Acervo</b></legend>
     <table border="0" cellpadding="0" cellspacing="2" bgcolor="#CCCCCC" align="center">
      <tr>
       <td><label for="data_ini"><b>Informe o período <small><i>(Opcional) </i></small>:</b></label></td>
       <td>
        <?php db_inputdata('data_ini','','','',true,'text',1,""); ?>
        <label for="data_fim">Até:</label>
        <?php db_inputdata('data_fim','','','',true,'text',1,""); ?>
       </td>
      </tr>
      <tr>
       <td>
         <label for="bi06_seq"><?php db_ancora(@$Lbi06_seq,"js_pesquisabi06_seq(true);",1); ?></label>
        </td>
       <td>
         <?php db_input('bi06_seq', 10, @$Ibi06_seq, true,' text', 1, " onchange='js_pesquisabi06_seq(false);'"); ?>
         <?php db_input('bi06_titulo', 40, @$Ibi06_titulo, true, 'text', 3, ''); ?>
       </td>
      </tr>
     </table>
   </fieldset>
   <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">

   <fieldset style="width:95%;">
     <legend><b>Consulta de Empréstimos por Acervo</b></legend>

     <table width="100%">
      <tr height="350">
       <td align="center" colspan="3">
        <iframe src="bib3_emprestcons002.php" name="framemarca" id="framemarca" width="100%" height="90%" frameborder="0"></iframe>
       </td>
      </tr>
     </table>
   </fieldset>

  </center>
</form>
<script>
function js_pesquisa() {

  if (document.form1.bi06_seq.value == "") {

    alert("Selecione um acervo para pesquisa");
    document.form1.bi06_seq.style.backgroundColor="#99A9AE";
    document.form1.bi06_seq.focus();
  } else {
    framemarca.location.href="bib3_emprestcons002.php?todos=false"
                                                   +"&valor="+document.form1.bi06_seq.value
                                                   +"&data_ini="+document.form1.data_ini_ano.value
                                                                +"-"+document.form1.data_ini_mes.value
                                                                +"-"+document.form1.data_ini_dia.value
                                                   +"&data_fim="+document.form1.data_fim_ano.value
                                                                +"-"+document.form1.data_fim_mes.value
                                                                +"-"+document.form1.data_fim_dia.value;
  }
}

function js_pesquisabi06_seq(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_acervo',
                        'func_acervo.php?funcao_js=parent.js_mostracodbarras1|bi06_seq|bi06_titulo',
                        'Pesquisa',
                        true
                       );
  } else {

    if (document.form1.bi06_seq.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo',
                          'db_iframe_acervo',
                          'func_acervo.php?pesquisa_chave='+document.form1.bi06_seq.value
                                        +'&funcao_js=parent.js_mostracodbarras',
                          'Pesquisa',
                          false
                         );
    } else {

      document.form1.bi06_seq.value    = '';
      document.form1.bi06_titulo.value = '';
    }
  }
}

function js_mostracodbarras(chave,erro) {

  document.form1.bi06_titulo.value = chave;
  if (erro == true) {

    document.form1.bi06_seq.focus();
    document.form1.bi06_seq.value = '';
    return false;
  }
  document.form1.pesquisar.onclick();
}

function js_mostracodbarras1(chave1,chave2) {

  document.form1.bi06_seq.value    = chave1;
  document.form1.bi06_titulo.value = chave2;
  document.form1.pesquisar.onclick();
  db_iframe_acervo.hide();
}
</script>
  <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>