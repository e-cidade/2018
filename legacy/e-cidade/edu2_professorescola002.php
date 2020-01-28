<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once ("fpdf151/FpdfMultiCellBorder.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_libdocumento.php");
require_once ("libs/db_libparagrafo.php");
require_once ("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$lMostrarDisciplinas = $oGet->disciplina == 'S';
try {

  $oPdf = new FpdfMultiCellBorder("P");
  $oPdf->exibeHeader(true);
  $oPdf->Open();
  $oPdf->AliasNbPages();
  $oPdf->setfillcolor(223);
  $oPdf->SetMargins(10, 10);
  $oPdf->SetAutoPageBreak(true, 10);

  switch ($oGet->iModelo) {

    case 1:

      $oModelo = new RelatorioProfessorEscolaSintetico($oPdf, $oGet->escola, $oGet->area, $oGet->iTipoHora);
      break;
    case 2:

      $oModelo = new RelatorioProfessorEscolaAnalitico($oPdf, $oGet->escola, $oGet->area, $oGet->iTipoHora);
      break;
    default:
      throw new Exception( _M ( RelatorioProfessorEscola::MSG_RELATORIOPROFESSORESCOLA . "impossivel_localizar_modelo") );
      break;
  }
  $oModelo->setTipoTotalizador($oGet->iTotalizador);
  $oModelo->setMostrarDisciplinas($lMostrarDisciplinas);

  $oModelo->imprimir();

  $oPdf->Output();

} catch (Exception $oErro) {
  db_redireciona('db_erros.php?fechar=true&db_erro=' . $oErro->getMessage());
}
