<?php
/**
 *     E-cidade Software protectedo para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca protecteda Geral GNU, conforme
 *  protectedada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca protecteda Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca protecteda Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */


namespace ECidade\Tributario\Integracao\Civitas\Repository;

use ECidade\Tributario\Integracao\Civitas\Model\Importador as ImportadorModel;

/**
* Repository do importador de arquivos do Civitas
* @author Alysson Zanette <alysson.zanette@dbseller.com.br>
*/
class Importador
{
    const CODIGO_PENDENTE = 3;
    const CODIGO_ERRO = 2;
    const CODIGO_SUCESSO  = 1;

    /**
     * Retorna um Importador de arquivos do civitas
     * @param array $aArquivos
     * @return ImportadorModel
     */
    public static function getImportador($aArquivos = array())
    {
        return new ImportadorModel($aArquivos);
    }

    public static function atualizarSituacao($situacao, $sequecialRequisicao = null)
    {
        $daoSituacao = new \cl_requisicaocivitassituacao();
        $querySituacao = $daoSituacao->sql_query_file(null, "rq02_sequencial", null, "rq02_codigo = $situacao");
        $recordSituacao = $daoSituacao->sql_record($querySituacao);

        if (empty($recordSituacao)) {
            throw new \DBException("Erro ao buscar a situação da requisição");
        }

        $resultadoSituacao = \db_utils::fieldsMemory($recordSituacao, 0);

        $daoRequisicao = new \cl_requisicaocivitas();
        $daoRequisicao->rq01_situacao = $resultadoSituacao->rq02_sequencial;
        $daoRequisicao->rq01_dataenvio = date("Y-m-d");

        if (empty($sequecialRequisicao)) {

            $resultado = $daoRequisicao->incluir();
            $operacao = "incluir";
        } else {

            $daoRequisicao->rq01_sequencial = $sequecialRequisicao;
            $resultado = $daoRequisicao->alterar($sequecialRequisicao);
            $operacao = "alterar";
        }

        if (empty($resultado)) {
            throw new \DBException("Erro ao $operacao dados da requisição.");
        }

        return $daoRequisicao->rq01_sequencial;
    }
}
