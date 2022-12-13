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

use \ECidade\Tributario\Cadastro\Iptu\EmissaoTXT;
use \ECidade\Tributario\Arrecadacao\EmissaoGeral\Repository as EmissaoGeralRepository;


require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_libtributario.php"));

$oParams = \db_utils::postMemory($_REQUEST);

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default" >
    <div class="container">
      <?php \db_criatermometro('termometro', 'Concluido...', 'blue', 1); ?>
    </div>
  </body>
</html>
<?php
  set_time_limit(0);
  
  try {

    /**
     * Geração do arquivo TXT
     */
    db_inicio_transacao();

    /**
     * Busca os dados da emissão geral
     */
    $oEmissaoRepository = new EmissaoGeralRepository();
    $oEmissaoGeral = $oEmissaoRepository->getEmissao($oParams->emissao);

    $oEmissao = new EmissaoTXT();

    $aRetorno = $oEmissao->gerar(
      $oEmissaoGeral,
      $oParams->quantidade_registros_real,
      ($oParams->mensagemanosanteriores == "s"),
      $oParams->tipo,
      ($oParams->opVenc == 1),
      function ($i, $tot) {
        \db_atutermometro($i,$tot,'termometro');
        flush();
      }
    );

    if (!empty($aRetorno)) {
      $sArquivos = '';

      foreach($aRetorno as $oArquivo) {
        $sArquivos .= $oArquivo->getFilePath() . "# Download do Arquivo - " . $oArquivo->getFileName() . "|";
      }

      echo "<script>";
      echo "  listagem = '{$sArquivos}';";
      echo "  parent.js_montarlista(listagem, 'form1');";
      echo "</script>";
    }

    db_fim_transacao();

  } catch (Exception $e) {

    db_fim_transacao(true);
    echo "<script>alert('{$e->getMessage()}')</script>";
  }
?>
