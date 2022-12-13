<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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

/**
 * @property integer eso04_sequencial
 * @property integer eso04_avaliacaogruporesposta
 * @property integer eso04_cgm
 */
class cl_avaliacaogruporespostalotacao extends DAOBasica
{

    public function __construct()
    {
        parent::__construct("esocial.avaliacaogruporespostalotacao");
    }


    public function buscaRespostasPorPergunta($iCodigoPergunta = null, $iCgm = null, $campos = "*", $ordem = null, $dbwhere = "")
    {

        $sql  = "select {$campos}";
        $sql .= "  from avaliacaogruporespostalotacao ";
        $sql .= "      inner join avaliacaogruporesposta on db107_sequencial = eso04_avaliacaogruporesposta";
        $sql .= "      inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial";
        $sql .= "      inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta";
        $sql .= "      inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao";
        $sql .= "      inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta";
        $sql2 = "";

        if (empty($dbwhere)) {
            $sql2 .=" where ";
            $aWhere = array();

            if (!empty($iCodigoPergunta)) {
                $aWhere[] = " db103_sequencial = {$iCodigoPergunta} ";
            }
            if (!empty($iCgm)) {
                $aWhere[] = "eso04_cgm = {$iCgm}";
                $aWhere[] = "eso04_avaliacaogruporesposta = (select max(eso04_avaliacaogruporesposta)
                from avaliacaogruporespostalotacao
                inner join avaliacaogruporesposta on db107_sequencial = eso04_avaliacaogruporesposta
                inner join avaliacaogrupoperguntaresposta on db108_avaliacaogruporesposta = db107_sequencial
                inner join avaliacaoresposta on db106_sequencial = db108_avaliacaoresposta
                inner join avaliacaoperguntaopcao on db104_sequencial = db106_avaliacaoperguntaopcao
                inner join avaliacaopergunta on db103_sequencial = db104_avaliacaopergunta
                where db103_sequencial = {$iCodigoPergunta} and eso04_cgm = {$iCgm})";
            }
            $sql2 .= implode("and ", $aWhere);
        } else if (!empty($dbwhere)) {
            $sql2 = " where {$dbwhere}";
        }
        $sql .= $sql2;
        if (!empty($ordem)) {
            $sql .= " order by {$ordem}";
        }
        return $sql;
    }
}
