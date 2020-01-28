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

namespace ECidade\RecursosHumanos\ESocial\Migracao;

class Rubrica extends Migracao implements MigracaoInterface
{

    protected $nomeFormulario = 'Rubrica';

    /**
     * Busca o última carga de rubricas
     *
     * @param integer $codigoFormulario
     * @return \stdClass[]
     */
    public function buscarUltimoPreenchimento($codigoFormulario)
    {
        $where = " db101_sequencial = {$codigoFormulario} ";
        $campos = 'distinct db107_sequencial as preenchimento, ';
        $campos .= '(select db106_resposta';
        $campos .= '   from avaliacaoresposta as ar ';
        $campos .= '   join avaliacaogrupoperguntaresposta as preenchimento on preenchimento.db108_avaliacaoresposta = ar.db106_sequencial ';
        $campos .= '   join avaliacaoperguntaopcao as apo on apo.db104_sequencial = ar.db106_avaliacaoperguntaopcao ';
        $campos .= '   join avaliacaopergunta as ap on ap.db103_sequencial = apo.db104_avaliacaopergunta ';
        $campos .= '  where ap.db103_perguntaidentificadora is true ';
        $campos .= '    and preenchimento.db108_avaliacaogruporesposta = db107_sequencial ';
        $campos .= ') as pk ';
        $dao = new \cl_avaliacaogruporesposta;
        $sql = $dao->sql_avaliacao_preenchida(null, $campos, null, $where);
        $rs = \db_query($sql);

        if (!$rs) {
            throw new \Exception("Erro ao buscar os preenchimentos dos formulários das rubricas.");
        }

        return \db_utils::getCollectionByRecord($rs);
    }
}
