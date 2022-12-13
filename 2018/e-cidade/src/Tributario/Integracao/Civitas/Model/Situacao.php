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


namespace ECidade\Tributario\Integracao\Civitas\Model;

/**
 * Classe que realiza a importação do arquivos de recadastramento
 * @author Alysson Zanette <alysson.zanette@dbseller.com.br>
 */
class Situacao
{
    /**
     *@var bool
     */
    private static $inicializado = false;
    /**
     *@var \stdClass
     */
    private static $situacao;

    /**
     * Inicializa os parametros da situação de importação do civitas
     * @return void
     */
    public static function inicializar()
    {
        if (self::$inicializado) {
            self::finalizar();
        }

        self::$inicializado = true;
        self::$situacao = new \stdClass();
        self::$situacao->situacao = 1;
        self::$situacao->erros  = array();
    }

    /**
     * Finaliza a situação de importação do civitas
     * @return void
     */
    private static function finalizar()
    {
        self::$inicializado = false;
        self::$situacao = new \stdClass();
    }

    /**
     * Adiciona um erro na situção.
     * @return void
     */
    public static function addErro($msgErro)
    {
        self::setCodigoSituacao(2);
        self::$situacao->erros[] = $msgErro;
    }

    /**
     * Retorna a situação atual.
     * @return \stdClass
     */
    public static function getSituacao()
    {
        return self::$situacao;
    }

    /**
     * Seta o código de erro 1 - Sucesso | 2 - Quando ocorreram erros
     * @param int $codigo
     * @return void
     */
    private static function setCodigoSituacao($codigo)
    {
        self::$situacao->situacao = $codigo;
    }

     /**
     * Função auxilia a lançar uma exceção quando estamos importando os arquivos manualmente
     * ou segue com o fluxo de importação quando estamos importando com o Webservice
     * @param \Exception $excecao
     * @param bool $manual
     * @return void
     */
    public static function lancarExcecao($excecao, $manual = false)
    {
        if ($manual) {
            throw $excecao;
        } else {
            self::addErro($excecao->getMessage());
        }
    }
}
