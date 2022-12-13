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

$codigoInstituicao = db_getsession('DB_instit');
$anoSessao         = db_getsession('DB_anousu');
$stdParam            = JSON::create()->parse( str_replace("\\","",$_POST["json"]) );
$stdRetorno          = new stdClass();
$stdRetorno->mensagem = '';
$stdRetorno->erro    = false;

try {

    switch ($stdParam->exec) {

        /*
         * @todo alterar mensagens para json
         */
        case 'getLivro':

            if (empty($stdParam->exercicio)) {
                throw new ParameterException("Exercício é de preenchimento obrigatório.");
            }

            $campos = implode(',', array(
                'distinct v01_livro as livro',
                'extract(year from v01_dtinclusao) as ano_inclusao'
            ));

            $where = implode(' and ', array(
                "divida.v01_instit = {$codigoInstituicao}",
                "extract(year from divida.v01_dtinclusao) = {$anoSessao}",
            ));

            $daoDivida = new cl_divida();
            $buscaInformacoes = db_query($daoDivida->sql_query_file(null, $campos, null, $where));
            if (!$buscaInformacoes) {
                throw new DBException("Ocorreu um erro ao consultar o livro para o exercício.");
            }

            $totalRegistros = pg_num_rows($buscaInformacoes);
            if ($totalRegistros === 0) {
                throw new BusinessException("Não foram encontradas dividas importadas.");
            }

            $stdRetorno->dadosLivro = db_utils::getCollectionByRecord($buscaInformacoes);
            break;

        case 'getFolha':

            $where = implode(' and ', array(
                "divida.v01_instit = {$codigoInstituicao}",
                "extract(year from divida.v01_dtinclusao) = {$stdParam->anoLivro}",
                "divida.v01_livro = {$stdParam->codigoLivro}",
            ));

            $daoDivida = new cl_divida();
            $buscaFolhaDivida = db_query($daoDivida->sql_query_file(null, 'max(v01_folha) as folha', null, $where));
            if (!$buscaFolhaDivida) {
                throw new DBException("Ocorreu um erro ao consultar a folha para o exercício.");
            }
            $folhaAtual = (int)db_utils::fieldsMemory($buscaFolhaDivida, 0)->folha;
            $stdRetorno->folha = $folhaAtual === 0 ? 1 : $folhaAtual;

            break;

        case 'processarLivroFolha':

            db_inicio_transacao();
            if (empty($stdParam->ano_inclusao)) {
                throw new ParameterException("Ano do Exercício é de preenchimento obrigatório.");
            }

            if (empty($stdParam->livro)) {
                throw new ParameterException("Livro é de preenchimento obrigatório.");
            }

            if (empty($stdParam->folha)) {
                throw new ParameterException("Folha é de preenchimento obrigatório.");
            }

            if (empty($stdParam->dividas) ||  count($stdParam->dividas) === 0) {
                throw new ParameterException("Dívidas é de preenchimento obrigatório.");
            }

            list($codigoLivro, $anoLivro) = explode('-', $stdParam->livro);
            $where = implode(' and ', array(
                "divida.v01_instit = {$codigoInstituicao}",
                "extract(year from divida.v01_dtinclusao) = {$stdParam->ano_inclusao}",
                "divida.v01_livro = {$codigoLivro}",
                "divida.v01_folha = {$stdParam->folha}",
            ));

            $daoDivida = new cl_divida();
            $buscaFolha = db_query($daoDivida->sql_query_file(null, 'max(v01_folha) as folha_atual, count(*) as total_registros_folha', null, $where));
            if (!$buscaFolha || pg_num_rows($buscaFolha) === 0) {
                throw new DBException("Não foi possível buscar a folha que será adicionado as dívidas.");
            }

            $stdDadosFolha = db_utils::fieldsMemory($buscaFolha, 0);
            $folhaAtual = (int)$stdDadosFolha->folha_atual === 0 ? 1 : $stdDadosFolha->folha_atual;
            $totalRegistros = $stdDadosFolha->total_registros_folha;

            foreach ($stdParam->dividas as $stdDivida) {

                if ($totalRegistros > 30) {
                    $totalRegistros = 0;
                    $folhaAtual++;
                }

                $daoDivida->v01_coddiv = $stdDivida->sCodigo;
                $daoDivida->v01_livro  = $codigoLivro;
                $daoDivida->v01_folha  = $folhaAtual;
                $daoDivida->alterar($daoDivida->v01_coddiv);
                if ($daoDivida->erro_status === '0') {
                    throw new DBException("Não foi possível salvar os dados de livro/folha.");
                }
                $totalRegistros++;
            }

            $stdRetorno->mensagem = "Implantação realizada com sucesso.";

            db_fim_transacao(false);
            break;
    }


} catch (Exception $e) {

    db_fim_transacao(true);
    $stdRetorno->mensagem = $e->getMessage();
    $stdRetorno->erro = true;
}
echo JSON::create()->stringify($stdRetorno);
