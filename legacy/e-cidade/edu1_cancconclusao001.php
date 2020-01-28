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
require_once(modification("classes/db_cursoedu_classe.php"));

db_postmemory( $_POST );

$clcurso      = new cl_curso;
$clalunocurso = new cl_alunocurso;

$db_opcao = 1;
$db_botao = false;
$escola   = db_getsession("DB_coddepto");
?>

<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<?php
if( isset( $cancelar ) ) {

  $tam = sizeof( $alunos );

  for( $x = 0; $x < $tam; $x++ ) {

    $arrayaluno = explode( "#", $alunos[$x] );

    $sql   = "UPDATE alunocurso                       ";
    $sql  .= "   SET ed56_c_situacao = 'ENCERRADO'    ";
    $sql  .= " WHERE ed56_i_codigo = {$arrayaluno[0]} ";
    $query = db_query( $sql );

    $sql   = "UPDATE historico                        ";
    $sql  .= "   SET ed61_i_anoconc     = null,       ";
    $sql  .= "       ed61_i_periodoconc = null        ";
    $sql  .= " WHERE ed61_i_aluno  = {$arrayaluno[1]} ";
    $sql  .= "   AND ed61_i_curso  = {$cursoedu}      ";
    $sql  .= "   AND ed61_i_escola = {$escola}        ";
    $query = db_query( $sql );
  }

  db_msgbox("Cancelamento concluído!");
  db_redireciona("edu1_cancconclusao001.php");
}
?>

<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr>
      <td>&nbsp;</td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
        <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
        <br>
        <center>
        <fieldset style="width:95%">
          <legend>
            <label class="bold">Cancelamento de Conclusão de Curso</label>
          </legend>
          <?php
          require_once(modification("forms/db_frmcancconclusao.php"));
          ?>
        </fieldset>
        </center>
      </td>
    </tr>
  </table>
  <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>