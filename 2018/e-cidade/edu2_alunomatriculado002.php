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

require_once("fpdf151/pdfwebseller.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

$oCalendario = CalendarioRepository::getCalendarioByCodigo($iCalendario);
$oEscola     = EscolaRepository::getEscolaByCodigo($iEscola);
$aEtapas     = array($iSerieEscolhida);

if ($iSerieEscolhida == 0) {

  $aEtapas   = array();
  $sCampos   = "distinct ed11_i_codigo ";
  $sWhere    = "     ed57_i_escola     = $iEscola ";
  $sWhere   .= " and ed57_i_calendario = $iCalendario ";

  $oDaoTurma = new cl_turma();
  $sSql      = $oDaoTurma->sql_query_turma(null, $sCampos, null, $sWhere);
  $rs        = db_query($sSql);

  if ($rs && pg_num_rows($rs) > 0) {

    $iLinhas = pg_num_rows($rs);
    for ($i=0; $i < $iLinhas ; $i++) {
      $aEtapas[] = db_utils::fieldsMemory($rs, $i)->ed11_i_codigo;
    }
  }
}

$lPercentual = false;

if ( $iFiltro == 1 ) {
  $lPercentual = true;
}

$oRelatorio = new RelatorioAlunosMatriculados($oCalendario, $aEtapas, $oEscola, $lPercentual);
$oRelatorio->imprimir();