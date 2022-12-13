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
require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_app.utils.php'));
require_once(modification('dbforms/db_funcoes.php'));

$oJSON              = new Services_JSON();
$oParametros        = $oJSON->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->mensagem = '';
$oRetorno->erro     = false;
$iInstituicao       = db_getsession("DB_instit");

try {

  db_inicio_transacao();

  switch ($oParametros->exec) {

    case "getLicitacoes":

      if (empty($oParametros->dtProcessamento)) {
        throw new ParameterException("Data do Processamento não informado.");
      }
      $oDataProcessamento = new DBDate($oParametros->dtProcessamento);

      $aCamposBusca = array(
        'l18_sequencial',
        'l20_codigo',
        'l20_numero',
        'l20_anousu',
        "case when 
           (select true 
              from liclicitaevento 
             where l46_liclicita = l20_codigo 
               and l46_liclicitatipoevento in (4,5,6,7) limit 1) 
           then true 
             else false 
           end as possui_encerramento"
      );
      $sWhereEncerramento  = "       l18_data = '{$oDataProcessamento->getDate()}'";
      $sWhereEncerramento .= " and l20_instit = {$iInstituicao}";
      $oDaoEncerramento    = new cl_liclicitaencerramentolicitacon();
      $sSqlBuscaEventos    = $oDaoEncerramento->sql_query_licitacao(null, implode(',', $aCamposBusca), null, $sWhereEncerramento);
      $rsBuscaLicitacao    = db_query($sSqlBuscaEventos);
      if (!$rsBuscaLicitacao) {
        throw new DBException("Ocorreu um erro ao buscar as licitações encerradas.");
      }

      $aLicitacoes = array();
      $iTotalRegistros = pg_num_rows($rsBuscaLicitacao);
      for ($iRow = 0; $iRow < $iTotalRegistros; $iRow++) {

        $oStdRegistros = db_utils::fieldsMemory($rsBuscaLicitacao, $iRow);
        $oStdRegistros->possui_encerramento = $oStdRegistros->possui_encerramento == 't';
        array_push($aLicitacoes, $oStdRegistros);

      }
      $oRetorno->licitacoes = $aLicitacoes;

      break;

    case 'excluirEncerramento':

      if (empty($oParametros->codigo)) {
        throw new ParameterException("Código de encerramento não informado.");
      }

      $oDaoEncerramento = new cl_liclicitaencerramentolicitacon();
      $oDaoEncerramento->excluir($oParametros->codigo);
      if ($oDaoEncerramento->erro_status == "0") {
        throw new DBException("Ocorreu um erro ao excluir o registros selecionado.");
      }

      $oRetorno->mensagem = "Licitação excluída com sucesso.";
      break;

  }


  db_fim_transacao(false);

} catch (Exception $e) {

  db_fim_transacao(true);
  $oRetorno->erro    = true;
  $oRetorno->mensagem = $e->getMessage();
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo $oJSON->encode($oRetorno);