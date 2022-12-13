<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$cliptuender = new cl_iptuender;

$db_opcao = 1;
$db_botao = true;

if (isset($incluir)) {

  db_inicio_transacao();

  try {

    if (!empty($j43_matric)) {

      $sSqlValidacao = $cliptuender->sql_query_file($j43_matric, "j43_matric");
      $rsValidacao   = $cliptuender->sql_record($sSqlValidacao);

      if ($cliptuender->numrows > 0) {
        throw new Exception("Endereço de entrega já cadastrado para a matrícula {$j43_matric}.");
      }
    }

    /**
     * Caso o endereço seja do municipio seta a UF e Municipio
     */
    if ($iEnderecoMunicipio == 1) {

      $oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();

      if (empty($oPrefeitura)) {
        $oPrefeitura = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));
      }

      $j43_uf    = $oPrefeitura->getUf();
      $j43_munic = $oPrefeitura->getMunicipio();
    }

    $cliptuender->j43_uf    = $j43_uf;
    $cliptuender->j43_munic = $j43_munic;
    $lResulado              = $cliptuender->incluir($j43_matric);

    if (!$lResulado) {
      throw new DBException($cliptuender->erro_msg);
    }

    db_fim_transacao(false);

  } catch ( Exception $oErro ) {

    $cliptuender->erro_msg = $oErro->getMessage();
    $cliptuender->erro_status = 0;
    db_fim_transacao(true);
  }
}

$cliptuender->rotulo->label();

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
  	<?php
      include modification("forms/db_frmiptuender.php");
      db_menu();
    ?>
    <script type="text/javascript">
      js_tabulacaoforms("form1","j43_matric",true,1,"j43_matric",true);
    </script>
  </body>
</html>
<?php
  if (isset($incluir)) {

    if ($cliptuender->erro_status == "0") {

      $cliptuender->erro(true, false);

      if ($cliptuender->erro_campo != "") {

        echo "<script>document.form1.".$cliptuender->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script>document.form1.".$cliptuender->erro_campo.".focus();</script>";
      }
    } else {
      $cliptuender->erro(true, true);
    }
  }
?>