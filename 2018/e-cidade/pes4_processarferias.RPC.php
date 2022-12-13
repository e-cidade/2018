<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';
$oRetorno->erro         = false;

const MENSAGENS = 'recursoshumanos.pessoal.pes4_processarferias.';

try {

 switch ($oParam->exec) {

   case 'getFeriasDisponiveis':

     $oPeriodosGozo    = new PeriodoGozoFerias();
     $oServidor        = null;
     $oDataInicioGozo  = null;
     if (!empty($oParam->servidor) && DBNumber::isInteger($oParam->servidor)) {
       $oServidor  = ServidorRepository::getInstanciaByCodigo($oParam->servidor);
     }

     if (!empty($oParam->datainicio)) {
       $oDataInicioGozo = new DBDate($oParam->datainicio);
     }

     $aPeriodos        = $oPeriodosGozo->getPeriodosGozo($oServidor, $oDataInicioGozo, null, true);
     $oRetorno->ferias = array();

     foreach ($aPeriodos as $oPeriodoFerias) {

       /**
        * Ferias já processadas = PeriodoGozoFerias::SITUACAO_CALCULADO_PREVIDENCIA 
        */
       if ($oPeriodoFerias->getSituacao() == PeriodoGozoFerias::SITUACAO_CALCULADO_PREVIDENCIA) {
         continue;
       }

       $sPeriodo = $oPeriodoFerias->getPeriodoInicial()->getDate(DBDate::DATA_PTBR);
       $sPeriodo .= " a ".$oPeriodoFerias->getPeriodoFinal()->getDate(DBDate::DATA_PTBR);

       $oStdPeriodo                      = new stdClass();
       $oStdPeriodo->servidor            = urlencode($oPeriodoFerias->getPeriodoAquisitivo()->getServidor()->getCgm()->getNome());
       $oStdPeriodo->matricula           = $oPeriodoFerias->getPeriodoAquisitivo()->getServidor()->getMatricula();
       $oStdPeriodo->periodo             = $sPeriodo;
       $oStdPeriodo->codigo_periodo_gozo = $oPeriodoFerias->getCodigoPeriodo();
       $oStdPeriodo->tipo_processamento  = 2;
       $nDiasGozoFeriasPeriodo           = $oPeriodoFerias->getDiasGozo();

       /**
        * Periodo é apenas pecunia e 1/3 de férias, nao existindo período de gozo. esse periodo nao deve constar como gozo
        * e apenas entrar no primeiro mes
        *
        *  - Caso a primeira escala seja apenas pecunia aparece como pecunia no tipo
        */
       if ($oPeriodoFerias->isPrimeiroPeriodo() &&!$oPeriodoFerias->tercoFeriasJaPago() && $oPeriodoFerias->getDiasAbono() > 0 && empty($nDiasGozoFeriasPeriodo)) {

         $oStdPeriodo->tipo_processamento  = 3;
         $oRetorno->ferias[]               = $oStdPeriodo;

         /*
          * Caso a primeira escala seja gozo e pecunia, clona para que apareça lançamento de 1/3
          */
       } else if ($oPeriodoFerias->isPrimeiroPeriodo() && !$oPeriodoFerias->tercoFeriasJaPago()) {

         $oStdPeriodoFeriasUmTerco                      = clone $oStdPeriodo;
         $oStdPeriodoFeriasUmTerco->tipo_processamento  = 1;
         $oRetorno->ferias[]                            = $oStdPeriodoFeriasUmTerco;
       }

       /**
        * Validar data de inicio das férias com a competencia inicial.
        * caso for apenas o desconto da previdência, devemos colocar apenas no mes de competencia do gozo das férias
        */
       $oCompetenciaInicioGozo = new DBCompetencia($oPeriodoFerias->getPeriodoInicial()->getAno(), $oPeriodoFerias->getPeriodoInicial()->getMes());
       if ($oCompetenciaInicioGozo->comparar(DBPessoal::getCompetenciaFolha()) && $nDiasGozoFeriasPeriodo > 0) {

         $oStdPeriodo->tipo_processamento  = 2;
         $oRetorno->ferias[]               = $oStdPeriodo;
       }
     }
     break;

   case "processarFerias" :

     db_inicio_transacao();

     if(!is_array($oParam->ferias) || count($oParam->ferias) == 0) {
       throw new BusinessException(_M(MENSAGENS .'ferias_vazio'));
     }

     foreach ($oParam->ferias as $oFerias) {

       $oPeriodosGozo = new PeriodoGozoFerias((int)$oFerias->codigo);
       $oPeriodosGozo->processarDadosFinanceiros((int)$oFerias->tipo);

     }

     $oRetorno->sMessage = _M(MENSAGENS. 'processado_sucesso');

     db_fim_transacao();
     break;
 }

} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->iStatus  = false;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
}
echo $oJson->encode($oRetorno);