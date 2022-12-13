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

namespace ECidade\RecursosHumanos\ESocial\Model;

/**
 * Retorna a configura��o dos layouts do eSocial
 * @package  ECidade\RecursosHumanos\ESocial\Model
 * @author   Andrio Costa - andrio.costa@dbseller.com.br
 */
class Configuracao
{
    private $versao;

    /**
     * Retorna a vers�o configurada
     *
     * @return integer
     */
    public function getVersao()
    {
        if (empty($this->versao)) {
            $dao = new \cl_esocialversao();
            $sql = $dao->sql_query_file(null, 'rh210_versao', '1 desc limit 1');
            $rs  = \db_query($sql);

            if (!$rs) {
                throw new \Exception('N�o foi poss�vel buscar a vers�o configurada do e-Social');
            }
            $this->versao = \db_utils::fieldsMemory($rs, 0)->rh210_versao;
        }

        return $this->versao;
    }

    /**
     * Retorna o formul�rio da ultima vers�o conforme o tipo informado
     *
     * @param integer $tipo
     * @return integer
     */
    public function getFormulario($tipo)
    {
        $where  = "    rh211_esocialformulariotipo = {$tipo}";
        $where .= "and rh211_versao = '" . $this->getVersao() . "'";

        $dao = new \cl_esocialversaoformulario();
        $sql = $dao->sql_query_file(null, 'rh211_avaliacao', null, $where);
        $rs  = db_query($sql);

        if (!$rs) {
            throw new \Exception('N�o foi poss�vel buscar formul�rios do e-Social');
        }

        return \db_utils::fieldsMemory($rs, 0)->rh211_avaliacao;
    }

    /**
     * Retorna todos formul�rios da vers�o informada
     *
     * @param string $sVersao
     * @return \stdClass[]
     */
    public function getFormulariosPorVersao($sVersao)
    {
        $where = "rh211_versao = '{$sVersao}'";
        $campos = 'rh211_avaliacao as formulario, rh211_esocialformulariotipo as tipo, rh211_versao as versao';
        $dao = new \cl_esocialversaoformulario();
        $sql = $dao->sql_query_file(null, $campos, null, $where);
        $rs  = db_query($sql);

        if (!$rs) {
            throw new \Exception('N�o foi poss�vel buscar formul�rios do e-Social');
        }

        return \db_utils::getCollectionByRecord($rs);
    }

    /**
     * Retorna um array com as vers�es dispon�veis para atualiza��o
     *
     * @return array
     */
    public function getVersoesAtualizar()
    {
        $where = " rh211_versao::float >= " . $this->getVersao();
        $dao = new \cl_esocialversaoformulario();
        $sql = $dao->sql_query_file(null, 'distinct rh211_versao', 'rh211_versao', $where);
        $rs = db_query($sql);

        if (!$rs) {
            throw new \Exception('N�o foi poss�vel buscar as vers�es do e-Social');
        }

        return \db_utils::makeCollectionFromRecord($rs, function ($data) {
            return $data->rh211_versao;
        });
    }

    /**
     * Retorna todos formul�rios da vers�o atual
     *
     * @return \stdClass[]
     */
    public static function getFormulariosVersaoAtual()
    {
        $configuracao = new Configuracao();
        return $configuracao->getFormulariosPorVersao($configuracao->getVersao());
    }
}
