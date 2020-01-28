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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("classes/db_aguahidromatric_classe.php"));
require_once (modification("classes/db_aguahidromatricleitura_classe.php"));
require_once (modification("classes/db_agualeitura_classe.php"));
require_once (modification("dbforms/db_funcoes.php"));

db_postmemory($HTTP_POST_VARS);

$claguahidromatric = new cl_aguahidromatric;
$claguahidromatricleitura = new cl_aguahidromatricleitura;
$clagualeitura = new cl_agualeitura;

$db_opcao = 1;
$db_botao = true;
$sql_erro = false;

$existe_leitura_posterior = 1;

if (isset($incluir)) {

  db_inicio_transacao();

  if (!empty($x04_matric)) {

    // Verifica se já não existe hidrometro instalado
    $sqlativo = "select fc_agua_hidrometroinstalado($x04_matric) as ativo";
    $resultativo = db_query($sqlativo);

    if (pg_numrows($resultativo) > 0) {

      db_fieldsmemory($resultativo, 0);

      if ($ativo == "t") {
        $erro_msg = "Erro ao Cadastrar Hidrometro!!\\nJa existe hidrometro Instalado e Ativo para Matricula " .
                     $x04_matric . "!\\nTente outra Matricula!";
        $sql_erro = true;
      }

    } else {
      $erro_msg = 'Hidrometro';
      $sql_erro = true;
    }
  }

  if (!empty($x04_matric)) {

    $oColetorExportacao = new clExpDadosColetores();
    if ($oColetorExportacao->getImportacaoPendente($x04_matric)) {
      $erro_msg = 'Existe uma Importação de dados pendente, favor verificar!';
      $erro_msg = true;
    }
  }


  // Inclui Hidrometro
  if ($sql_erro == false) {

    $claguahidromatric->incluir($x04_codhidrometro);
    $erro_msg = $claguahidromatric->erro_msg;

    if ($claguahidromatric->erro_status == "0") {
      $sql_erro = true;
    }
  }

  // Lanca Leitura Inicial
  if ($sql_erro == false) {

    $clagualeitura->x21_codhidrometro = $claguahidromatric->x04_codhidrometro;
    $clagualeitura->x21_numcgm        = "0"; // Leiturista ADM do Sistema

    $clagualeitura->x21_dtleitura_dia = $x04_dtinst_dia;
    $clagualeitura->x21_dtleitura_mes = $x04_dtinst_mes;
    $clagualeitura->x21_dtleitura_ano = $x04_dtinst_ano;

    $clagualeitura->x21_usuario       = db_getsession("DB_id_usuario");

    $clagualeitura->x21_dtinc_dia     = date("d", db_getsession("DB_datausu"));
    $clagualeitura->x21_dtinc_mes     = date("m", db_getsession("DB_datausu"));
    $clagualeitura->x21_dtinc_ano     = date("Y", db_getsession("DB_datausu"));

    $clagualeitura->x21_leitura       = $x04_leitinicial;
    $clagualeitura->x21_consumo       = "0";
    $clagualeitura->x21_excesso       = "0";
    $clagualeitura->x21_virou         = "false";
    $clagualeitura->x21_tipo          = "1";
    $clagualeitura->x21_status        = "1";
    $clagualeitura->x21_situacao      = $x21_situacao;

    $clagualeitura->incluir(null);

    if ($clagualeitura->erro_status == "0") {
      $erro_msg = $clagualeitura->erro_msg;
      $sql_erro = true;
    }

    if ($sql_erro == false) {
      $claguahidromatricleitura->x05_codhidrometro = $claguahidromatric->x04_codhidrometro;
      $claguahidromatricleitura->x05_codleitura    = $clagualeitura->x21_codleitura;
      $claguahidromatricleitura->incluir(null);

      if ($claguahidromatricleitura->erro_status == "0") {
        $erro_msg = $claguahidromatricleitura->erro_msg;
        $sql_erro = true;
      }
    }
  }

  db_fim_transacao($sql_erro);
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
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <center>
      <table width="790" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td>
            <fieldset style="margin-top: 50px;">
              <legend><b>Cadastro Hidrometros - Inclusão</b></legend>
              <center>
                <?
                  include(modification("forms/db_frmaguahidromatric.php"));
                ?>
              </center>
            </fieldset>
          </td>
        </tr>
      </table>
    </center>
    <?php db_menu() ?>
  </body>
</html>

<script>
  js_tabulacaoforms("form1", "x04_codhidrometro", true, 1, "x04_codhidrometro", true);
</script>

<?php
if (isset($incluir)) {

  db_msgbox($erro_msg);

  if ($sql_erro == false) {
    echo "<script>location.href = 'agu1_aguahidromatric001.php'</script>";
  }
}
?>
