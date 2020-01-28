<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
 *                    www.dbseller.com.br
 *                 e-cidade@dbseller.com.br
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

class AvaliacaoRespostaRepository
{

    /**
     * Remove avaliacao das tabelas avaliacaresposta e avaliacaogruporesposta
     * @param  AvaliacaoResposta $oAvaliacaoResposta
     * @return boolean
     */
    public static function delete(AvaliacaoResposta $oAvaliacaoResposta)
    {
        $oDaoAvaliacaoResposta = new cl_avaliacaoresposta();
        $oDaoAvaliacaoGrupo    = new cl_avaliacaogrupoperguntaresposta();
        $sWhereAvaliacaoSalva  = "    db108_avaliacaogruporesposta = {$oAvaliacaoResposta->getPergunta()->getAvaliacao()} ";
        $sWhereAvaliacaoSalva .= "and db104_avaliacaopergunta      = {$oAvaliacaoResposta->getPergunta()->getCodigo()}";

        $sSqlAvaliacaoSalva = $oDaoAvaliacaoGrupo->sql_query(null, "db108_sequencial, db106_sequencial", null, $sWhereAvaliacaoSalva);
        $rsAvaliacaoSalva   = db_query($sSqlAvaliacaoSalva);

        db_utils::makeCollectionFromRecord($rsAvaliacaoSalva, function ($oAvaliacaoSalva) use ($oDaoAvaliacaoResposta, $oDaoAvaliacaoGrupo) {

            $oDaoAvaliacaoGrupo->excluir(null, "db108_sequencial = {$oAvaliacaoSalva->db108_sequencial}");
            $oDaoAvaliacaoResposta->excluir(null, "db106_sequencial = {$oAvaliacaoSalva->db106_sequencial}");

            if ($oDaoAvaliacaoResposta->erro_status == 0) {
                throw new DBException("Ocorreu um erro ao salvar os dados da oergunta.");
            }
        });

        return true;
    }

    /**
     * Persiste os dados nas tabelas avaliacaoresposta e na avaliacaogrupoperguntaresposta.
     * @param  AvaliacaoResposta $oAvaliacaoResposta AvaliacaoResposta
     * @return boolean
     */
    public static function persist(AvaliacaoResposta $oAvaliacaoResposta)
    {
        $oDaoAvaliacaoResposta = new cl_avaliacaoresposta();
        $oDaoAvaliacaoGrupo    = new cl_avaliacaogrupoperguntaresposta();

        $oDaoAvaliacaoResposta->db106_avaliacaoperguntaopcao = $oAvaliacaoResposta->getPerguntaOpcao();
        $oDaoAvaliacaoResposta->db106_resposta               = $oAvaliacaoResposta->getResposta();
        $oDaoAvaliacaoResposta->incluir(null);

        if ($oDaoAvaliacaoResposta->erro_status == 0) {
            throw new DBException("Erro ao persistir dados na tabela avaliacaoresposta");
        }

        $oDaoAvaliacaoGrupo->db108_avaliacaoresposta      = $oDaoAvaliacaoResposta->db106_sequencial;
        $oDaoAvaliacaoGrupo->db108_avaliacaogruporesposta = $oAvaliacaoResposta->getPergunta()->getAvaliacao();
        $oDaoAvaliacaoGrupo->incluir(null);

        if ($oDaoAvaliacaoGrupo->erro_status == 0) {
            throw new DBException("Erro ao persistir dados na tabela avaliacaogrupoperguntaresposta");
        }

        return true;
    }
}
