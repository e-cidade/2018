<?php
/**
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

require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

define('CAMINHO_MENSAGENS', 'financeiro.orcamento.orc2_acompanhamentocronograma.');

$oParametros                 = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno                    = new stdClass();
$oRetorno->erro              = false;
$oRetorno->caminho_relatorio = '';
$oRetorno->mensagem          = '';

set_time_limit(0);

$aInstituicoes = array();
foreach ($oParametros->aInstituicoes as $oInstituicao) {
  $aInstituicoes[] = $oInstituicao->codigo;
}

try {

  db_inicio_transacao();

  switch ($oParametros->exec) {

    case "emitirRelatorioReceita":

      $oAcompanhamento = new AcompanhamentoCronograma($oParametros->iPerspectiva);
      $oRelatorio = new RelatorioAcompanhamentoCronogramaReceita(
        $oAcompanhamento, $aInstituicoes
      );
      $oRelatorio->emitirRelatorio();

      $oRetorno->caminho_relatorio = $oRelatorio->getArquivo()->getFilePath();
      break;

    case "emitirRelatorioDespesa":

      $oCronograma = new cronogramaFinanceiro($oParametros->iPerspectiva);
      $oRelatorio  = new RelatorioAcompanhamentoCronogramaDespesa(
        $oCronograma, $aInstituicoes, $oParametros->iNivel
      );
      $oRelatorio->gerarCSV();
      $oRetorno->caminho_relatorio = $oRelatorio->getArquivo()->getFilePath();

      break;

    default:
      throw new Exception(_M(CAMINHO_MENSAGENS . "opcao_indefinida", (object) array('exec' => $oParam->exec)));
  }

  db_fim_transacao(false);

} catch (Exception $e) {

  db_fim_transacao(true);
  $oRetorno->mensagem = $e->getMessage();
  $oRetorno->erro     = true;
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);
