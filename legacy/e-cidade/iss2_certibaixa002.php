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

require_once("fpdf151/pdf3.php");
require_once("fpdf151/impcarne.php");
require_once("std/db_stdClass.php");
require_once("libs/db_sql.php");
require_once("libs/db_libsys.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_parissqn_classe.php");
require_once("dbagata/classes/core/AgataAPI.class");
require_once("model/documentoTemplate.model.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$sCaminhoMensagem = "tributario.issqn.iss2_certibaixa002.";

/**
 * Busca parametros e tipo da Baixa
 */
$sSqlTabativBaixa  = "select q11_inscr, q11_seq, q11_processo, q11_oficio, ";
$sSqlTabativBaixa .= "       case when q11_oficio = 'f' then 'Normal'     ";
$sSqlTabativBaixa .= "            when 't' then 'Ofício'                  ";
$sSqlTabativBaixa .= "       end as tipo_baixa,                           ";
$sSqlTabativBaixa .= "       q11_login, q11_data, q11_hora, q11_numero    ";
$sSqlTabativBaixa .= "  from tabativbaixa                                 ";
$sSqlTabativBaixa .= " where q11_inscr = " .$inscr;

$rsTabativBaixa    = db_query($sSqlTabativBaixa);
if (pg_numrows($rsTabativBaixa) == 0 || !$rsTabativBaixa ){

  db_msgbox(_M($sCaminhoMensagem."baixa_incricao_nao_encontrada"));
  exit;
}

db_fieldsmemory( $rsTabativBaixa, 0 );

$clparissqn   = new cl_parissqn;
$sSqlParissqn = $clparissqn->sql_query_file(null, "*", "q60_receit limit 1");
$rsParissqn   = $clparissqn->sql_record($sSqlParissqn);

$oParissqn    = db_utils::fieldsMemory($rsParissqn, 0);
$q60_templatebaixaalvaranormal  = $oParissqn->q60_templatebaixaalvaranormal;
$q60_templatebaixaalvaraoficial = $oParissqn->q60_templatebaixaalvaraoficial;

if($q60_templatebaixaalvaranormal == null) {

  db_msgbox(_M($sCaminhoMensagem."documento_template_nao_existe"));
  exit;
}

$iDocumentoTemplate = $q60_templatebaixaalvaranormal;

if($q11_oficio == 't') {

  if($q60_templatebaixaalvaraoficial != null) {

    $iDocumentoTemplate = $q60_templatebaixaalvaraoficial;
  }
}

ini_set("error_reporting","E_ALL & ~NOTICE");

$sDescrDoc        = date("YmdHis").db_getsession("DB_id_usuario");
$sNomeRelatorio   = "tmp/CertidaoBaixaInscricao{$sDescrDoc}.pdf";
$sCaminhoSalvoSxw = "tmp/CertidaoBaixaInscricao_{$sDescrDoc}_{$inscr}.sxw";

$sAgt             = "issqn/certidao_baixa_inscricao.agt";

$aParam           = array();
$aParam['$inscr'] = $inscr;

db_stdClass::oo2pdf(46, $iDocumentoTemplate, $sAgt, $aParam, $sCaminhoSalvoSxw, $sNomeRelatorio);

exit;