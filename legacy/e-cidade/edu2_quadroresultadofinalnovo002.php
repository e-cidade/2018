<?php

/* 
 *      E-cidade Software Publico para Gestao Municipal
 *   Copyright (C) 2014  DBSeller Servicos de Informatica
 *                             www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 * 
 *   Este programa e software livre; voce pode redistribui-lo e/ou
 *   modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versao 2 da
 *   Licenca como (a seu criterio) qualquer versao mais nova.
 * 
 *   Este programa e distribuido na expectativa de ser util, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *   COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *   PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *   detalhes.
 * 
 *   Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *   junto com este programa; se nao, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 * 
 *   Copia da licenca no diretorio licenca/licenca_en.txt
 *                 licenca/licenca_pt.txt
 */

require_once ("fpdf151/FpdfMultiCellBorder.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_libdocumento.php");
require_once ("model/educacao/DBEducacaoTermo.model.php");
require_once ("libs/JSON.php");

$oJson = new Services_JSON();

$oGet              = db_utils::postMemory($_GET);
$oGet->aTurmas     = $oJson->decode(str_replace("\\", "", $oGet->aTurmas));
$oGet->sDiretor    = $oJson->decode(str_replace("\\", "", $oGet->sDiretor));
$oGet->sSecretario = $oJson->decode(str_replace("\\", "", $oGet->sSecretario));

$iModelo = $oGet->iModelo == 9999 ? null : $oGet->iModelo;

try {
  
  $lBrasao    = $oGet->sBrasao == 'S';
  $oRelatorio = new RelatorioQuadroResultadosFinais($iModelo, $lBrasao);
  $oRelatorio->setExibirTrocaTurma($oGet->sExibirTrocaTurma == 'S');
  $oRelatorio->setDiretor($oGet->sDiretor);
  $oRelatorio->setSecretario($oGet->sSecretario);
  
  foreach ($oGet->aTurmas as $oTurmaEtapa ) {
    $oRelatorio->addTurmaEtapa(TurmaRepository::getTurmaByCodigo($oTurmaEtapa->iTurma), EtapaRepository::getEtapaByCodigo($oTurmaEtapa->iEtapa));
  }

  $oRelatorio->imprimir();
  
} catch (Exception $oErro) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$oErro->getMessage()}");
}
