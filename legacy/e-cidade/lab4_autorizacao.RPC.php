<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
define('MENSAGENS_LAB4_AUTORIZACAO_RPC', 'saude.laboratorio.lab4_autorizacao_RPC.');

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->erro = false;
$oRetorno->sMensagem = '';

try {
    switch ($oParam->exec) {
        case 'autorizaExames':
            db_inicio_transacao();

            $oDaoAutoriza = new cl_lab_autoriza();
            $oDaoRequisicao = new cl_lab_requisicao();
            $oDaoRequiItem = new cl_lab_requiitem();

            /**
             * Inclui autorização
             */
            $oDaoAutoriza->la48_i_requisicao = $oParam->iRequisicao;
            $oDaoAutoriza->la48_d_data = date('Y-m-d', db_getsession("DB_datausu"));
            $oDaoAutoriza->la48_c_hora = db_hora();
            $oDaoAutoriza->la48_i_usuario = db_getsession("DB_id_usuario");
            $oDaoAutoriza->incluir(null);

            if ($oDaoAutoriza->erro_status == "0") {
                throw new Exception("Ocorreu um erro ao salvar a autorização das requisições selecionadas.");
            }

            /**
             * Altera a requisição para autorizada
             */
            $oDaoRequisicao->la22_i_autoriza = 2;
            $oDaoRequisicao->la22_i_codigo = $oParam->iRequisicao;
            $oDaoRequisicao->alterar($oParam->iRequisicao);

            if ($oDaoRequisicao->erro_status == "0") {
                $oErro = new stdClass();
                $oErro->sErro = pg_last_error();
                throw new Exception(_M(MENSAGENS_LAB4_AUTORIZACAO_RPC . 'erro_autorizar_requisicao', $oErro));
            }

            /**
             * Busca os itens da requisição e altera a situação para autorizado
             */
            $sExames = implode(", ", $oParam->aCodigosExames);
            $sWhere = " la21_i_requisicao = {$oParam->iRequisicao} and la21_i_setorexame in ({$sExames}) ";
            $sSqlItem = $oDaoRequiItem->sql_query_file(null, "la21_i_codigo", null, $sWhere);
            $rsItem = $oDaoRequiItem->sql_record($sSqlItem);
            $iLinhas = $oDaoRequiItem->numrows;

            for ($i = 0; $i < $iLinhas; $i++) {
                $iCodigoItem = db_utils::fieldsMemory($rsItem, $i)->la21_i_codigo;
                $oDaoRequiItem->la21_c_situacao = "8 - Autorizado";
                $oDaoRequiItem->la21_i_codigo = $iCodigoItem;
                $oDaoRequiItem->alterar($iCodigoItem);

                if ($oDaoRequiItem->erro_status == 0) {
                    $oErro = new stdClass();
                    $oErro->sErro = pg_last_error();
                    throw new Exception(_M(MENSAGENS_LAB4_AUTORIZACAO_RPC . 'erro_autorizar_item_requisicao', $oErro));
                }
            }

            foreach ($oParam->aExames as $oExame) {
                if (empty($oExame->sDataColeta)) {
                    throw new Exception(_M(MENSAGENS_LAB4_AUTORIZACAO_RPC . 'informe_data_coleta'));
                }

                $oDataColeta = new DBDate($oExame->sDataColeta);
                $sDataConvertida = $oDataColeta->convertTo(DBDate::DATA_EN);
                $sCampos = 'sum(la21_i_quantidade * (sd63_f_sa + la53_n_acrescimo)) as valor_autorizado, la56_n_limite as limite, la21_i_setorexame as exame, la08_c_descr as descricao_exame';

                $aWhere = array(
                  "substring(la21_c_situacao, 1, 1) = '8'",
                  "lab_requiitem.la21_i_setorexame = {$oExame->iExame}",
                  "la56_i_depto = " . db_getsession("DB_coddepto"),
                  "la22_i_departamento = " . db_getsession("DB_coddepto"),
                  "la21_d_data = '{$sDataConvertida}' ",
                  "la21_d_data BETWEEN la56_d_ini AND la56_d_fim",
                  "CASE WHEN la56_i_periodo = 1 THEN lab_requiitem.la21_d_data = '{$sDataConvertida}' ELSE true END"
                );
                $oDaoItemRequisicao = new cl_lab_requiitem();
                $sSqlBuscaValorItem = $oDaoItemRequisicao->sql_query_requisicao_exames_autorizados(
                    $sCampos,
                    implode(' and ', $aWhere) . " group by la56_n_limite, la21_i_setorexame, la08_c_descr"
                );
                $rsBuscaSaldo = db_query($sSqlBuscaValorItem);

                if (!$rsBuscaSaldo) {
                    throw new Exception("Ocorreu um erro ao consultar o saldo autorizado do item {$oExame->iExame}.");
                }

                if (pg_num_rows($rsBuscaSaldo) == 1) {
                    $oStdSaldo = db_utils::fieldsMemory($rsBuscaSaldo, 0);

                    if ($oStdSaldo->valor_autorizado > $oStdSaldo->limite) {
                        $nValorAutorizado = trim(db_formatar($oStdSaldo->valor_autorizado, 'f'));
                        $nLimiteDisponivel = trim(db_formatar($oStdSaldo->limite, 'f'));
                        $oErro = (object)array(
                          "valor_disponivel" => $nLimiteDisponivel,
                          "valor_autorizado" => $nValorAutorizado,
                          "exame"            => $oStdSaldo->descricao_exame
                        );
                        throw new Exception(_M(MENSAGENS_LAB4_AUTORIZACAO_RPC . 'saldo_insuficiente', $oErro));
                    }
                }
            }

            $oRetorno->sMensagem = urlencode(_M(MENSAGENS_LAB4_AUTORIZACAO_RPC . 'exames_autorizados'));
            db_fim_transacao(false);

            break;
    }
} catch (Exception $oErro) {
    $oRetorno->iStatus = 2;
    $oRetorno->erro = true;
    $oRetorno->sMensagem = urlencode($oErro->getMessage());
    db_fim_transacao(true);
}

echo $oJson->encode($oRetorno);
