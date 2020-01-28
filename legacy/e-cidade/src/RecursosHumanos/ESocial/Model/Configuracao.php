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
 * Retorna a configuração dos layouts do eSocial
 * @package  ECidade\RecursosHumanos\ESocial\Model
 * @author   Andrio Costa - andrio.costa@dbseller.com.br
 */
class Configuracao
{
    private $versao;

    /**
     * Retorna a versão configurada
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
                throw new \Exception('Não foi possível buscar a versão configurada do e-Social');
            }
            $this->versao = \db_utils::fieldsMemory($rs, 0)->rh210_versao;
        }

        return $this->versao;
    }

    /**
     * Retorna o formulário da ultima versão conforme o tipo informado
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
            throw new \Exception('Não foi possível buscar formulários do e-Social');
        }

        return \db_utils::fieldsMemory($rs, 0)->rh211_avaliacao;
    }

    /**
     * Retorna todos formulários da versão informada
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
            throw new \Exception('Não foi possível buscar formulários do e-Social');
        }

        return \db_utils::getCollectionByRecord($rs);
    }

    /**
     * Retorna um array com as versões disponíveis para atualização
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
            throw new \Exception('Não foi possível buscar as versões do e-Social');
        }

        return \db_utils::makeCollectionFromRecord($rs, function ($data) {
            return $data->rh211_versao;
        });
    }

    /**
     * Retorna todos formulários da versão atual
     *
     * @return \stdClass[]
     */
    public static function getFormulariosVersaoAtual()
    {
        $configuracao = new Configuracao();
        return $configuracao->getFormulariosPorVersao($configuracao->getVersao());
    }
}
