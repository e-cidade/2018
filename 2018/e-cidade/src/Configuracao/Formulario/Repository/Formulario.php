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

namespace ECidade\Configuracao\Formulario\Repository;

use ECidade\Configuracao\Formulario\Model\Formulario as FormularioModel;
use ECidade\RecursosHumanos\ESocial\Model\Configuracao;

/**
 * Class Formulario
 * @package ECidade\Configuracao\Formulario\Repository
 */
class Formulario
{
    /**
     * @var Formulario
     */
    private static $instance = null;

    /**
     * @var FormularioModel[]
     */
    public $formularios;

    /**
     * @return Formulario
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    /**
     * Retorna a instancia do formulário por ID
     * @param $codigo
     * @return FormularioModel
     * @throws \BusinessException
     */
    public static function getById($codigo)
    {
        if (!empty(self::getInstance()->formularios[$codigo])) {
            return self::getInstance()->formularios[$codigo];
        }

        $oDaoAvaliacao = new \cl_avaliacao();
        $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file((int)$codigo);
        $rsAvaliacao   = db_query($sSqlAvaliacao);
        if (!$rsAvaliacao) {
            throw new \BusinessException("Erro ao pesquisar avaliações por Tipo");
        }
        if (pg_num_rows($rsAvaliacao) == 0) {
            throw new \BusinessException("Formulário de código {$codigo} não existe no cadastro do e-cidade.");
        }

        self::getInstance()->formularios[$codigo] = self::getInstance()->make(\db_utils::fieldsMemory($rsAvaliacao, 0));
        return self::getInstance()->formularios[$codigo];
    }

    /**
     * @param $iTipo
     * @throws \BusinessException
     * @return FormularioModel[]
     */
    public static function getByTipo($iTipo)
    {
        $oDaoAvaliacao = new \cl_avaliacao();
        $sWhere        = "db101_avaliacaotipo = ".(int)$iTipo;
        $sSqlAvaliacao = $oDaoAvaliacao->sql_query_file(null, "*", null, $sWhere);
        $rsAvaliacao   = db_query($sSqlAvaliacao);
        if (!$rsAvaliacao) {
            throw new \BusinessException("Erro ao pesquisar avaliações por Tipo");
        }
        $instancia    = self::getInstance();
        $aFormularios = \db_utils::makeCollectionFromRecord($rsAvaliacao, function ($dados) use ($instancia) {

            $instancia->formularios[$dados->db101_sequencial] = $instancia->make($dados);
            return $instancia->formularios[$dados->db101_sequencial];
        });

        return $aFormularios;
    }

    /**
     * Retorna os formulários do ESocial na versão atualmente configurada
     *
     * @return FormularioModel[]
     */
    public static function getByVersaoAtual()
    {
        //Busca os formulários da versão configurada
        $formulariosVersao = Configuracao::getFormulariosVersaoAtual();
        $formularios = array();
        foreach ($formulariosVersao as $formulario) {
            $formularios[] = self::getById($formulario->formulario);
        }
        return $formularios;
    }

    /**
     * @param $dados
     * @return FormularioModel
     */
    public function make($dados)
    {
        $oFormulario = new FormularioModel();
        $oFormulario->setCodigo($dados->db101_sequencial);
        $oFormulario->setTipo($dados->db101_avaliacaotipo);
        $oFormulario->setNome($dados->db101_descricao);
        $oFormulario->setAtivo($dados->db101_ativo == 't');
        $oFormulario->setIdentificador($dados->db101_identificador);
        $oFormulario->setCarga($dados->db101_cargadados);
        return $oFormulario;
    }
}
