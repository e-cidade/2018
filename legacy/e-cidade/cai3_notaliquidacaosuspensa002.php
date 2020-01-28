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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet = db_utils::postMemory($_GET);

$iAnoSessao = db_getsession('DB_anousu');
$iInstituicaoSessao = db_getsession('DB_instit');

try {

  $oRelatorio = new RelatorioNotaLiquidacaoSuspensaoPagamento();
  $oRelatorio->setSituacao($oGet->situacao);
  if (!empty($oGet->numero_empenho)) {

    $aEmpenho = explode('/', $oGet->numero_empenho);
    $iCodigoEmpenho = $aEmpenho[0];
    $iAnoEmpenho = count($aEmpenho) == 2 ? $aEmpenho[1] : $iAnoSessao;
    $oEmpenho = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorCodigoAno($iCodigoEmpenho, $iAnoEmpenho, InstituicaoRepository::getInstituicaoByCodigo($iInstituicaoSessao));
    $oRelatorio->setEmpenho($oEmpenho);
  }

  if (!empty($oGet->codigo_credor)) {
    $oRelatorio->setCredor(CgmFactory::getInstanceByCgm($oGet->codigo_credor));
  }

  if (!empty($oGet->data_inicial)) {
    $oRelatorio->setDataInicial(new DBDate($oGet->data_inicial));
  }

  if (!empty($oGet->data_final)) {
    $oRelatorio->setDataFinal(new DBDate($oGet->data_final));
  }

  if (!empty($oGet->lista_classificacao)) {

    $aCodigosListaClassificacao = explode(',', $oGet->lista_classificacao);
    foreach ($aCodigosListaClassificacao as $iCodigo) {
      $oRelatorio->adicionarListaClassificacao(ListaClassificacaoCredorRepository::getPorCodigo($iCodigo));
    }
  }
  $oRelatorio->emitir();

} catch (Exception $eErro) {
  db_redireciona('db_erros.php?fechar=true&db_erro='.$eErro->getMessage()); exit;
}