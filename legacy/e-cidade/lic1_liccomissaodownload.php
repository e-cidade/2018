<?php
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet         = db_utils::postMemory($_GET);
$oDaoComissao = new cl_liccomissao;
$sSqlComissao = $oDaoComissao->sql_query((int) $oGet->iCodigoComissao);
$rsResultado  = db_query($sSqlComissao);

if ($rsResultado && pg_num_rows($rsResultado) !== 0) {

  $oComissao = db_utils::fieldsMemory($rsResultado, 0);
  $sCaminhoArquivo = "tmp/{$oComissao->l30_nomearquivo}";

  if (file_exists($sCaminhoArquivo)) {
    unlink($sCaminhoArquivo);
  }

  db_inicio_transacao();
  if (!empty($oComissao->l30_arquivo) && pg_lo_export($oComissao->l30_arquivo, $sCaminhoArquivo)) {

    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Content-Type: application/force-download');
    header('Content-Transfer-Encoding: binary');
    header('Content-Disposition: attachment; filename="' . $oComissao->l30_nomearquivo . '";');
    header('Content-Length: ' . filesize($sCaminhoArquivo));
    readfile($sCaminhoArquivo);
    exit;
  }
  db_fim_transacao(false);
}
?>

<script type="text/javascript">
alert('Não foi possível efetuar o download do arquivo.');
</script>
