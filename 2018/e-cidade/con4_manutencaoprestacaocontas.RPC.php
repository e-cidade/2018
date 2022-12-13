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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/JSON.php");
require_once ("libs/exceptions/BusinessException.php");
require_once ("libs/exceptions/DBException.php");
require_once ("libs/exceptions/ParameterException.php");
require_once ("dbforms/db_funcoes.php");


$oJson                = new services_json();
$oParam               = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno             = new stdClass();
$oRetorno->erro       = false;
$oRetorno->mensagem   = '';

$iInstituicaoSessao = db_getsession('DB_instit');
$iAnoSessao         = db_getsession('DB_anousu');

try {

  db_inicio_transacao();
  switch ($oParam->exec) {

    case 'getEmpenhos':

      $sSqlBuscaEmpenhos = "select empempenho.*, z01_nome
                              from empempenho
                                   inner join cgm on z01_numcgm = e60_numcgm
                             where e60_numemp in (select x.e60_numemp
                                                    from (select e60_numemp, count(*)
                                                            from empempenho
                                                                 inner join emppresta on e45_numemp = e60_numemp
                                                           where e60_anousu = {$iAnoSessao}
                                                           group by e60_numemp having count(*) > 1) as x)";
      $rsBuscaEmpenhos = db_query($sSqlBuscaEmpenhos);
      $iTotalRegistros = pg_num_rows($rsBuscaEmpenhos);
      if ($iTotalRegistros == 0) {
        throw new Exception("Nenhum empenho encontrado.");
      }
      $aEmpenhos = array();
      for ($iRow = 0; $iRow < $iTotalRegistros; $iRow++) {

        $oStdEmpenho = db_utils::fieldsMemory($rsBuscaEmpenhos, $iRow);
        $oStdDadosRetorno = new stdClass();
        $oStdDadosRetorno->sequencial = $oStdEmpenho->e60_numemp;
        $oStdDadosRetorno->codigo     = $oStdEmpenho->e60_codemp;
        $oStdDadosRetorno->ano        = $oStdEmpenho->e60_anousu;
        $oStdDadosRetorno->fornecedor = urlencode($oStdEmpenho->z01_nome);
        $aEmpenhos[] = $oStdDadosRetorno;
      }
      $oRetorno->aEmpenhos = $aEmpenhos;

      break;

    case "getPrestacaoDeContas":

      $sSqlBuscaPrestacao = "
        select emppresta.*, e44_descr
          from emppresta
               inner join empprestatip on e44_tipo = e45_tipo
         where e45_numemp in (select x.e60_numemp
                                from (select e60_numemp, count(*)
                                        from empempenho
                                             inner join emppresta on e45_numemp = e60_numemp
                                       where e60_numemp = {$oParam->sequencial}
                                       group by e60_numemp having count(*) > 1) as x) ";
      $rsBuscaPrestacao = db_query($sSqlBuscaPrestacao);
      $oRetorno->aPrestacao = db_utils::getCollectionByRecord($rsBuscaPrestacao);
      foreach ($oRetorno->aPrestacao as $iIndice => $oStdPrestacao) {
        $oRetorno->aPrestacao[$iIndice]->e44_descr = urlencode($oStdPrestacao->e44_descr);
      }
      break;


    case 'excluir':

      $rsDeletaItem = db_query("delete from empprestaitem where e46_emppresta = {$oParam->codigo}");
      $rsDeletaPrestacao = db_query("delete from emppresta where e45_sequencial = {$oParam->codigo}");
      if (!$rsDeletaPrestacao || !$rsDeletaItem) {
        throw new Exception("Impossível excluir o registro selecionado.");
      }
      $oRetorno->mensagem = "Prestação excluída com sucesso.";
      break;
  }

  db_fim_transacao(false);

} catch (Exception $eErro) {

  db_fim_transacao(true);

  $oRetorno->erro       = true;
  $oRetorno->mensagem = $eErro->getMessage();
}
$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo $oJson->encode($oRetorno);