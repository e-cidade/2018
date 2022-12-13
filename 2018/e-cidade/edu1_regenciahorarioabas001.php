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
require_once(modification("dbforms/db_classesgenericas.php"));

$clcriaabas            = new cl_criaabas;
$clturmaserieregimemat = new cl_turmaserieregimemat;

$db_opcao = 1;
$result   = $clturmaserieregimemat->sql_record(
  $clturmaserieregimemat->sql_query("", "ed223_i_serie, ed11_c_descr", "ed11_i_sequencia", " ed220_i_turma = {$ed59_i_turma}")
);

if($clturmaserieregimemat->numrows == 1) {

  db_fieldsmemory($result, 0);
  db_redireciona("edu1_regenciahorario001.php?ed59_i_turma=$ed59_i_turma&ed59_i_serie=$ed223_i_serie&ed11_c_descr=" . rawurlencode($ed11_c_descr) ."&ed57_c_descr=" . rawurlencode($ed57_c_descr) . "&ed57_i_turno=$ed57_i_turno");
} else {

  for($x = 0; $x < $clturmaserieregimemat->numrows; $x++) {

    db_fieldsmemory($result, $x);

    $num               = $x + 1;
    $ident["b$num"]    = $ed11_c_descr;
    $tamcampo["b$num"] = 11;
    $pagina["b$num"]   = "edu1_regenciahorario001.php?ed59_i_turma=$ed59_i_turma&ed59_i_serie=$ed223_i_serie&ed11_c_descr=" .rawurlencode($ed11_c_descr) ."&ed57_c_descr=".rawurlencode($ed57_c_descr)."&ed57_i_turno=$ed57_i_turno";
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
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
  <form name="formaba">
    <table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td align="center" valign="top" bgcolor="#CCCCCC">
          <fieldset style="width:95%"><legend><b>Etapas da Turma <?=@$ed57_c_descr?></b></legend>
            <?php
            $clcriaabas->identifica    = $ident;
            $clcriaabas->sizecampo     = $tamcampo;
            $clcriaabas->src           = $pagina;
            $clcriaabas->abas_left     = 10;
            $clcriaabas->iframe_width  = '100%';
            $clcriaabas->iframe_height = 1000;
            $clcriaabas->scrolling     = "no";
            $clcriaabas->cria_abas();
            ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </form>
  </body>
  </html>
  <?php
}
?>