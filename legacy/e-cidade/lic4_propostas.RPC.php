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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$json  = JSON::create();
$stdParam = $json->parse(str_replace("\\","",$_POST["json"]));

$stdRetorno           = new stdClass();
$stdRetorno->mensagem = '';
$stdRetorno->erro     = false;

try {

    db_inicio_transacao();

    switch ($stdParam->exec) {

        case 'getOrcamentoProcessoCompra':

            $stdRetorno->valoresEstimados = array();
            $licitacao = new licitacao($stdParam->codigoLicitacao);
            $itensLicitacao = $licitacao->getItens();
            $fornecedor = new OrcamentoFornecedor($stdParam->codigoFornecedor);
            $codigoCgmFornecedor = $fornecedor->getFornecedor()->getCodigo();
            $daoOrcamentoProcesso = new cl_pcorcamitemproc();
            foreach ($itensLicitacao as $item) {

                $where = implode(' and ', array(
                    "pcorcamitemproc.pc31_pcprocitem = {$item->getItemProcessoCompras()}",
                    "pcorcamforne.pc21_numcgm = {$codigoCgmFornecedor}"
                ));
                $buscaOrcamento = $daoOrcamentoProcesso->sql_query_orcamento_item('coalesce(pc23_vlrun, 0) as pc23_vlrun', $where);
                $resBuscaOrcamento = db_query($buscaOrcamento);
                if (!$resBuscaOrcamento) {
                    throw new DBException("Ocorreu um erro ao consultar o or�amento par ao item {$item->getItemProcessoCompras()}.");
                }

                $stdRetorno->valoresEstimados[] = (object)array(
                    'codigoItemProcesso' => $item->getItemProcessoCompras(),
                    'valorEstimado' => trim(db_formatar(db_utils::fieldsMemory($resBuscaOrcamento, 0)->pc23_vlrun, 'p'))
                );
            }

            break;
    }
    db_fim_transacao(false);

} catch (Exception $e) {

    $stdRetorno->erro     = true;
    $stdRetorno->mensagem = $e->getMessage();
    db_fim_transacao(true);
}
echo $json->stringify($stdRetorno);
