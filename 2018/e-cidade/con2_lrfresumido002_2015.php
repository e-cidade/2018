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

set_time_limit(0);

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'fpdf151/assinatura.php';
require_once 'libs/db_sql.php';
require_once 'libs/db_utils.php';
require_once 'libs/db_app.utils.php';
require_once 'libs/db_libtxt.php';
require_once 'dbforms/db_funcoes.php';
require_once 'libs/db_libpostgres.php';
require_once 'libs/db_libcontabilidade.php';
require_once 'libs/db_liborcamento.php';
require_once 'fpdf151/PDFDocument.php';
require_once 'fpdf151/pdf.php';

$oGET = db_utils::postMemory($_GET);
$sInstituicao  = str_replace("-", ",", $oGET->db_selinstit);

$oAnexoXVIII = new AnexoXVIIIResumido(db_getsession('DB_anousu'), AnexoXVIIIResumido::CODIGO_RELATORIO, $oGET->bimestre);
$oAnexoXVIII->setInstituicoes($sInstituicao);
$oAnexoXVIII->setExibirRelatorios(array(
    AnexoXVIIIResumido::EMITIR_BALANCO_ORCAMENTARIO       => $oGET->emite_balorc,
    AnexoXVIIIResumido::EMITIR_DESPESA_FUNCAO_SUBFUNCAO   => $oGET->emite_desp_funcsub,
    AnexoXVIIIResumido::EMITIR_RECEITA_CORRENTE_LIQUIDA   => $oGET->emite_rcl,
    AnexoXVIIIResumido::EMITIR_DESPESAS_RECEITAS_RPPS     => $oGET->emite_rec_desp,
    AnexoXVIIIResumido::EMITIR_RESULTADO_NOMINAL_PRIMARIO => $oGET->emite_resultado,
    AnexoXVIIIResumido::EMITIR_RESTOS_A_PAGAR             => $oGET->emite_rp,
    AnexoXVIIIResumido::EMITIR_DESPESAS_MDE               => $oGET->emite_mde,
    AnexoXVIIIResumido::EMITIR_DESPESAS_SAUDE             => $oGET->emite_saude,
    AnexoXVIIIResumido::EMITIR_OPERACAO_DE_CREDITO        => $oGET->emite_oper,
    AnexoXVIIIResumido::EMITIR_PROJECAO_ATUARIAL_RPPS     => $oGET->emite_proj,
    AnexoXVIIIResumido::EMITIR_ALIENACAO_ATIVOS           => $oGET->emite_alienacao,
    AnexoXVIIIResumido::EMITIR_PPP                        => $oGET->emite_ppp,
));
$oAnexoXVIII->emitir();




