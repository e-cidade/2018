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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_libpessoal.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$aFuncoesExercidas   = array();
$oDocente            = DocenteRepository::getDocenteByCodigo( $oGet->cgm  );
$aProfissionalEscola = ProfissionalEscolaRepository::getEscolasProfissionalByCGM( $oDocente->getCgm() );

foreach ($aProfissionalEscola as $oProfissional) {

  $iEscola = $oProfissional->getEscola()->getCodigo();
  $sEscola = $oProfissional->getEscola()->getNome();
  foreach ($oProfissional->getAtividades() as $oAtividade) {

    $oDadosAtividade = new stdClass();
    $oDadosAtividade->iCodigo          = $oAtividade->getCodigo();
    $oDadosAtividade->iCodigoAtividade = $oAtividade->getAtividadeEscolar()->getCodigo();
    $oDadosAtividade->sEscola          = utf8_encode($sEscola);
    $oDadosAtividade->sDescricao       = utf8_encode( $oAtividade->getAtividadeEscolar()->getDescricao() );

    $sSaida = " Em Andamento ";
    if ( $oProfissional->getDataSaida() instanceof DBDate) {
      $sSaida = $oProfissional->getDataSaida()->convertTo(DBDate::DATA_PTBR);
    }

    $oDadosAtividade->sAdimicao  = $oProfissional->getDataIngresso()->convertTo(DBDate::DATA_PTBR);
    $oDadosAtividade->sAdimicao .= " - {$sSaida} ";
    $oDadosAtividade->sAdimicao  = utf8_encode($oDadosAtividade->sAdimicao);

    $oAtoLegal                      = $oAtividade->getAtoLegal();
    $oDadosAtividade->iCodigoAto    = "";
    $oDadosAtividade->sDescricaoAto = "";
    if ( !is_null($oAtoLegal) ) {

      $oDadosAtividade->iCodigoAto    = $oAtividade->getAtoLegal()->getCodigoAtoLegal();
      $oDadosAtividade->sDescricaoAto = utf8_encode( $oAtividade->getAtoLegal()->getFinalidade() );
    }

    $oDadosAtividade->aResumoTurno = array();
    $oDadosAtividade->aAgendas     = array();

    foreach ( $oAtividade->getAgenda() as $oAgenda ) {

      $oDadosAgenda              = new stdClass();
      $oDadosAgenda->iCodigo     = $oAgenda->getCodigo();
      $oDadosAgenda->iDiaSemana  = $oAgenda->getDiaSemana();
      $oDadosAgenda->sDiaSemana  = utf8_encode( $oAgenda->getNomeDiaSemana() );
      $oDadosAgenda->iTurno      = $oAgenda->getTurnoReferente();
      $oDadosAgenda->sTurno      = utf8_encode( $oAgenda->getDescricaoTurno() );
      $oDadosAgenda->sHoraInicio = $oAgenda->getHoraInicio();
      $oDadosAgenda->sHoraFim    = $oAgenda->getHoraFim();

      $oDadosAgenda->iTipoHoraTrabalho = $oAgenda->getTipoHoraTrabalho()->getCodigo();
      $oDadosAgenda->sTipoHoraTrabalho = utf8_encode( $oAgenda->getTipoHoraTrabalho()->getDescricao() );

      $oDadosAtividade->aAgendas[]     = $oDadosAgenda;

      /**
       * Cria um resumo da agenda contendo o turno e o tipo de hora de trabalho
       */
      $sHash   = $oDadosAgenda->iTurno . "#" . $oDadosAgenda->iTipoHoraTrabalho;
      $oResumo = new stdClass();

      $oResumo->sTurno            = $oDadosAgenda->sTurno;
      $oResumo->sTipoHoraTrabalho = utf8_encode( $oDadosAgenda->sTipoHoraTrabalho );
      $oResumo->iTurno            = $oDadosAgenda->iTurno;
      $oResumo->iTipoHoraTrabalho = $oDadosAgenda->iTipoHoraTrabalho;

      if ( !array_key_exists($sHash, $oDadosAtividade->aResumoTurno) ) {
        $oDadosAtividade->aResumoTurno[$sHash] = $oResumo;
      }
    }

    $aFuncoesExercidas[$iEscola][] = $oDadosAtividade;
  }

}

$aJsonFuncoesExercidas = json_encode($aFuncoesExercidas);

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <link type="text/css" rel="stylesheet" href="estilos.css">
</head>
<body  style=" background-color: #f3f3f3">

  <fieldset style="border: 2px solid">

    <legend class='bold'>Funções Exercidas</legend>

    <div id='ctnGridFuncoes'>

    </div>
  </fieldset>

</body>
</html>
<script type="text/javascript">

var oJanelaHorarios = null;
var aFuncoesExercidas = <?=$aJsonFuncoesExercidas?>;

var oGridFuncoes   = new DBGrid('gridFuncoes');
var aHeadersGrid    = ["Escola", "Função/Atividade", "Turno", "Tipo Hora", "Horários"];
var aCellWidthGrid  = ["32%", "30%", "10%", "20%", "8%"];
var aCellAlign      = ["left", "left", "center", "left", "center"];

oGridFuncoes.nameInstance = 'oGridFuncoes';
oGridFuncoes.setCellWidth(aCellWidthGrid);
oGridFuncoes.setCellAlign(aCellAlign);
oGridFuncoes.setHeader(aHeadersGrid);
oGridFuncoes.setHeight(200);
oGridFuncoes.show($('ctnGridFuncoes'));
oGridFuncoes.clearAll(true);

for (var sIndexEscola in aFuncoesExercidas ) {

  if ( typeof aFuncoesExercidas[sIndexEscola] == 'function' ) {
    continue;
  }

  for ( var sIndexEscolaProfissional in aFuncoesExercidas[sIndexEscola] ) {

    var oProfissionalEscola = aFuncoesExercidas[sIndexEscola][sIndexEscolaProfissional];

    if ( typeof oProfissionalEscola == 'function' ) {
      continue;
    }

    var oBtnHorarios = new Element('input', {type:'button', value: 'Horários', disabled:true});

    var aLinha = [];
    aLinha.push(oProfissionalEscola.sEscola.urlDecode() + ' - ' + oProfissionalEscola.sAdimicao.urlDecode());
    aLinha.push(oProfissionalEscola.sDescricao.urlDecode() );
    aLinha.push('');
    aLinha.push('');
    aLinha.push(oBtnHorarios.outerHTML);

    if ( !empty(oProfissionalEscola.aResumoTurno) ) {

      for (var sIndexTurno  in oProfissionalEscola.aResumoTurno) {

        var oTurnoAtividade = oProfissionalEscola.aResumoTurno[sIndexTurno];

        if ( typeof oTurnoAtividade == 'function' ) {
          continue;
        }

        var oBtnHorarios = new Element('input', {type:'button', value: 'Horários'});
        oBtnHorarios.setAttribute('onclick', 'abrirGradeHorarios(' + sIndexEscola + ', ' + sIndexEscolaProfissional
                                  + ', '+ oTurnoAtividade.iTurno +', ' + oTurnoAtividade.iTipoHoraTrabalho + ' )');

        var aLinhaClone = aLinha;
        aLinhaClone[2]  = oTurnoAtividade.sTurno.urlDecode();
        aLinhaClone[3]  = oTurnoAtividade.sTipoHoraTrabalho.urlDecode() ;
        aLinhaClone[4]  = oBtnHorarios.outerHTML;
        oGridFuncoes.addRow( aLinhaClone );

      }
    } else {
      oGridFuncoes.addRow( aLinha );
    }
  }
}

oGridFuncoes.renderRows();

oGridFuncoes.aRows.each( function (oLinha, iLinha) {

  oGridFuncoes.setHint(iLinha, 0, oGridFuncoes.aRows[iLinha].aCells[0].getContent());
  oGridFuncoes.setHint(iLinha, 1, oGridFuncoes.aRows[iLinha].aCells[1].getContent());
});

function abrirGradeHorarios( sIndexEscola, sIndexEscolaProfissional, iTurno, iTipoHora ) {

  if ($('wndGradeHorarios')) {

    oJanelaHorarios.destroy()
    oJanelaHorarios = null;
  }

  oJanelaHorarios = new windowAux( "wndGradeHorarios","Grade de Horários","700", '400' );
  oJanelaHorarios.allowCloseWithEsc(false);

  var sConteudo  = "<div id='gradeHorarios'>                             ";
      sConteudo += "    <fieldset  style='width:100%'>                                                    ";
      sConteudo += "      <legend><b>Horários</b></legend>                                                                ";
      sConteudo += "      <div id='ctnGridHorarios'></div>                                                              ";
      sConteudo += "    </fieldset>                                                                                     ";
      sConteudo += "</div>                                                                                              ";

  oJanelaHorarios.setShutDownFunction( function() {
    oJanelaHorarios.destroy();
  });

  var sMsg        = 'Horários de exercício da Função/Atividade'
  var sHelpMsgBox = '';

  oJanelaHorarios.setContent(sConteudo);
  var oMessageBoard = new DBMessageBoard('msgBoardAvaliacao', sMsg, sHelpMsgBox, oJanelaHorarios.getContentContainer());
  oJanelaHorarios.show(0,250);

  var oGridHorarios   = new DBGrid('gridHorarios');
  var aHeadersGrid    = ["Dia da Semana", "Hora de Inicio", "Hora de Fim"];
  var aCellWidthGrid  = ["50%", "25%", "25%"];
  var aCellAlign      = ["left", "center", "center"];

  oGridHorarios.nameInstance = 'oGridHorarios';
  oGridHorarios.setCellWidth(aCellWidthGrid);
  oGridHorarios.setCellAlign(aCellAlign);
  oGridHorarios.setHeader(aHeadersGrid);
  oGridHorarios.setHeight(150);
  oGridHorarios.show($('ctnGridHorarios'));
  oGridHorarios.clearAll(true);

  for ( var sIndex in aFuncoesExercidas[sIndexEscola][sIndexEscolaProfissional].aAgendas ) {

    var oAgenda = aFuncoesExercidas[sIndexEscola][sIndexEscolaProfissional].aAgendas[sIndex];

    if ( iTurno != oAgenda.iTurno || iTipoHora != oAgenda.iTipoHoraTrabalho ) {
      continue;
    }

    var aLinha = [];
    aLinha.push( oAgenda.sDiaSemana.urlDecode() );
    aLinha.push( oAgenda.sHoraInicio.urlDecode() );
    aLinha.push( oAgenda.sHoraFim.urlDecode() );

    oGridHorarios.addRow(aLinha);
  };
  oGridHorarios.renderRows();
}

</script>