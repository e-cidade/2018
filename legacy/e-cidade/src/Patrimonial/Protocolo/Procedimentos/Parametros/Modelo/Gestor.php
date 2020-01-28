<?php
/*
*     E-cidade Software Publico para Gestao Municipal
*  Copyright (C) 2016  DBselller Servicos de Informatica
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

namespace ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Modelo;

use cl_gestaodepartamentoprocesso as GestaoDepartamentoProcesso;
use cl_gestaoprocessovencido as GestaoProcessoVencido;
use DBDepartamento as Departamento;
use UsuarioSistema as Usuario;

class Gestor
{
    /**
     * @var Usuario
     */
    private $oUsuario;

    /**
     * @var array
     */
    private $aDepartamentosGeridos = array();

    /**
     * @var bool
     */
    private $lCargoPrincipal = false;

    public function __construct($id)
    {
        if (empty($id)) {
            throw new \Exception("Id do gestor não informado.");
        }

        $this->oUsuario = new Usuario($id);

        $this->carregarDepartamentos();
    }

    /**
     * Retorna o código do usuario
     * @return int
     */
    public function getCodigo()
    {
        return $this->oUsuario->getCodigo();
    }

    /**
     * Retorna array de departamentos de um gestor
     * @return array
     */
    public function getDepartamentos()
    {
        return $this->aDepartamentosGeridos;
    }

    /**
     * Retorna o nome do gestor
     * @return string
     */
    public function getNome()
    {
        return $this->oUsuario->getNome();
    }

    /**
     * Valida se é o gestor principal, cadastrado na gestaoprocessovencido.
     * @return bool
     */
    public function ehGestorPrincipal()
    {
        return $this->lCargoPrincipal;
    }

    /**
     * Carrega os departamentos do gestor para a variavel aDepartamentosGeridos.
     * @return void
     */
    private function carregarDepartamentos()
    {
        $oGestaoProcessoVencido = new GestaoProcessoVencido();
        $sqlGestao = $oGestaoProcessoVencido->sql_query_file(null, "p102_db_usuarios", null,
            "p102_db_usuarios = {$this->oUsuario->getCodigo()}");
        $rs = db_query($sqlGestao);

        if (pg_num_rows($rs) > 0) {
            $this->lCargoPrincipal = true;
            $this->aDepartamentosGeridos = $this->getTodosDepartamentos();
        } else {
            $oGestaoDepartamentoProcesso = new GestaoDepartamentoProcesso();
            $sqlGestao = $oGestaoDepartamentoProcesso->sql_query(null, "db_depart.coddepto", null,
                "p103_db_usuarios = {$this->oUsuario->getCodigo()}");
            $rs = db_query($sqlGestao);

            if (pg_num_rows($rs) > 0) {
                $this->aDepartamentosGeridos = \db_utils::makeCollectionFromRecord($rs, function ($oDados) {
                    return new Departamento($oDados->coddepto);
                });
            }
        }
    }

    /**
     * Retorna todos os departamentos cadastrados na base.
     * @return array
     */
    private function getTodosDepartamentos()
    {
        $departamentos = array();

        $oDepartamento = new \cl_db_depart();
        $sql = $oDepartamento->sql_query_file(null, "coddepto", null, null);
        $rs = db_query($sql);

        if (pg_num_rows($rs) > 0) {
            $departamentos = \db_utils::makeCollectionFromRecord($rs, function ($dados) {
                return new Departamento($dados->coddepto);
            });
        }

        return $departamentos;
    }
}
