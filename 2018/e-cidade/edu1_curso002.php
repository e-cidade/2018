<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once(modification("classes/db_cursoedu_classe.php"));
require_once(modification("classes/db_cursoescola_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));
parse_str($_SERVER["QUERY_STRING"]);
db_postmemory($_POST);
$clcurso       = new cl_curso;
$clcursoescola = new cl_cursoescola;
$db_opcao        = 22;
$db_opcao1       = 3;
$db_botao        = false;

if (isset($alterar)) {

  $db_opcao  = 2;
  $db_opcao1 = 3;
  $db_botao  = true;
  db_inicio_transacao();
  $clcurso->alterar($ed29_i_codigo);
  db_fim_transacao();

} elseif (isset($chavepesquisa)) {

 $db_opcao  = 2;
 $db_opcao1 = 3;
 $db_botao  = true;
 $sSqlCurso = $clcurso->sql_query($chavepesquisa);
 $rsCurso   = $clcurso->sql_record($sSqlCurso);
 db_fieldsmemory($rsCurso, 0);
 ?>
 <script>
  parent.document.formaba.a2.disabled = false;
  parent.document.formaba.a3.disabled = false;
  parent.document.formaba.a4.disabled = false;
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href   = 'edu1_cursoescola001.php?ed71_i_curso=<?=$ed29_i_codigo?>'+
                                        '&ed29_c_descr=<?=$ed29_c_descr?>';
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href   = 'edu1_cursoturno001.php?ed85_i_curso=<?=$ed29_i_codigo?>'+
                                        '&ed29_c_descr=<?=$ed29_c_descr?>';
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href   = 'edu1_cursoato001.php?ed71_i_curso=<?=$ed29_i_codigo?>'+
                                        '&ed29_c_descr=<?=$ed29_c_descr?>';
 </script>
 <?
}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body >
   <?php include(modification("forms/db_frmcursos.php"));?>
  </body>
</html>
<?
if (isset($alterar)) {

  if ($clcurso->erro_status == "0") {

    $clcurso->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clcurso->erro_campo != "") {

      echo "<script> document.form1.".$clcurso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcurso->erro_campo.".focus();</script>";

    }

  } else {

    $clcurso->erro(true,false);
  ?>
    <script>
     parent.document.formaba.a2.disabled = false;
     parent.document.formaba.a3.disabled = false;
     parent.document.formaba.a4.disabled = false;
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href   = 'edu1_cursoescola001.php?ed71_i_curso=<?=$ed29_i_codigo?>'+
                                           '&ed29_c_descr=<?=$ed29_c_descr?>';
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href   = 'edu1_cursoturno001.php?ed85_i_curso=<?=$ed29_i_codigo?>'+
                                           '&ed29_c_descr=<?=$ed29_c_descr?>';
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href   = 'edu1_cursoato001.php?ed71_i_curso=<?=$ed29_i_codigo?>'+
                                           '&ed29_c_descr=<?=$ed29_c_descr?>';
    </script>
  <?
  }

}

if ($db_opcao == 22) {
  echo "<script>document.form1.pesquisar.click();</script>";
}
?>