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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

use ECidade\Patrimonial\Protocolo\Procedimentos\Parametros\Repositorio\GestaoProcesso;

$oJson = new services_json();
$oParam = JSON::create()->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMessage = '';
$iUsuario = db_getsession("DB_id_usuario");

try {
    db_inicio_transacao();
    $lErroTransacao = false;

    switch ($oParam->exec) {
        case 'salvar':

            if (!empty($oParam->iGestorProcesso)) {
                $oDaoGestaoProcessos = new cl_gestaoprocessovencido();
                $oDaoGestaoProcessos->p102_sequencial = $oParam->iSequencialGestorProcesso;
                $oDaoGestaoProcessos->p102_db_usuarios = $oParam->iGestorProcesso;

                $sAcao = empty($oParam->iSequencialGestorProcesso) ? 'incluir' : 'alterar';
                $oDaoGestaoProcessos->$sAcao($oParam->iSequencialGestorProcesso);

                if ($oDaoGestaoProcessos->erro_status == '0') {
                    throw new DBException("Erro ao tentar salvar a configuração de gestor.");
                }
            } else {
                $sqlLimpar = 'DELETE FROM gestaoprocessovencido';
                db_query($sqlLimpar);
            }

            $oDaoGestaoDepartamentoProcesso = new cl_gestaodepartamentoprocesso();

            if (!empty($oParam->aResponsaveisExistentes)) {
                $sResponsaveisExclusao = implode(',', $oParam->aResponsaveisExistentes);
                $oDaoGestaoDepartamentoProcesso->excluir(null, "p103_db_usuarios in ({$sResponsaveisExclusao})");
            }

            foreach ($oParam->aResponsaveisDepartamento as $oResponsavelDepartamento) {
                $oDaoGestaoDepartamentoProcesso->p103_sequencial = null;
                $oDaoGestaoDepartamentoProcesso->p103_db_usuarios = $oResponsavelDepartamento->iResponsavel;
                $oDaoGestaoDepartamentoProcesso->p103_db_depart = $oResponsavelDepartamento->iDepartamento;
                $oDaoGestaoDepartamentoProcesso->incluir(null);

                if ($oDaoGestaoDepartamentoProcesso->erro_status == '0') {
                    throw new DBException("Erro ao vincular gestor ao departamento.");
                }
            }

            $oRetorno->sMessage = "Informações salvas com sucesso.";
            break;

        case 'buscar':

            $oRetorno->iSequencialGestorProcesso = null;
            $oRetorno->iGestorProcesso = null;
            $oRetorno->aResponsaveisDepartamento = array();

            $oDaoGestaoProcessos = new cl_gestaoprocessovencido();
            $sSqlGestaoProcessos = $oDaoGestaoProcessos->sql_query();
            $rsGestaoProcessos = db_query($sSqlGestaoProcessos);

            if (!$rsGestaoProcessos) {
                throw new DBException("Erro ao buscar o gestor do processo.");
            }

            if (pg_num_rows($rsGestaoProcessos) > 0) {
                $oDados = db_utils::fieldsMemory($rsGestaoProcessos, 0);
                $oRetorno->iSequencialGestorProcesso = $oDados->p102_sequencial;
                $oRetorno->iGestorProcesso = $oDados->p102_db_usuarios;
                $oRetorno->sNomeGestorProcesso = $oDados->nome;
            }

            $oDaoGestaoDepartamentoProcesso = new cl_gestaodepartamentoprocesso();
            $sSqlGestaoDepartamentoProcesso = $oDaoGestaoDepartamentoProcesso->sql_query();
            $rsGestaoDepartamentoProcesso = db_query($sSqlGestaoDepartamentoProcesso);

            if (!$rsGestaoDepartamentoProcesso) {
                throw new DBException("Erro ao buscar os responsáveis por departamento.");
            }

            $oRetorno->aResponsaveisDepartamento = \db_utils::makeCollectionFromRecord($rsGestaoDepartamentoProcesso,
                function ($oDados) {
                    $oResponsavel = new stdClass();
                    $oResponsavel->registro = $oDados->p103_sequencial;
                    $oResponsavel->codigoResponsavel = $oDados->id_usuario;
                    $oResponsavel->nomeResponsavel = $oDados->nome;
                    $oResponsavel->codigoDepartamento = $oDados->coddepto;
                    $oResponsavel->nomeDepartamento = $oDados->descrdepto;
                    return $oResponsavel;
                });
            break;

        case 'buscarPorUsuario':

            $oGestor = GestaoProcesso::getById($iUsuario);
            $oRetorno->lGestorPrincipal = $oGestor->ehGestorPrincipal();
            $oRetorno->aDepartamentos = array();

            if (!$oGestor->ehGestorPrincipal()) {
                foreach ($oGestor->getDepartamentos() as $oDepartamento) {
                    $stdDepartamento = new stdClass();
                    $stdDepartamento->iCodigo = $oDepartamento->getCodigo();
                    $stdDepartamento->sNome = $oDepartamento->getNomeDepartamento();
                    $oRetorno->aDepartamentos[] = $stdDepartamento;
                }
            }

            break;
    }

    db_fim_transacao($lErroTransacao);
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);
