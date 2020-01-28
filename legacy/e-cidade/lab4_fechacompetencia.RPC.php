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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/JSON.php");
require_once ("std/db_stdClass.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/db_layouttxt.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");

$oJson             = new Services_JSON();
$oParam            = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$oMsgErro = new stdClass();

try {

  switch($oParam->exec) {

    case "processar":

      db_inicio_transacao();

      $oCompetencia = new CompetenciaLaboratorio( $oParam->iCodigo );

      $oCompetencia->setCompetencia(new DBCompetencia($oParam->iAnoCompetencia, $oParam->iMesCompetencia));
      $oCompetencia->setDataInclusao(new DBDate($oParam->dtSistema));
      $oCompetencia->setPeriodoInicial(new DBDate($oParam->dtInicio));
      $oCompetencia->setPeriodoFinal(new DBDate($oParam->dtFim));
      $oCompetencia->setUsuario(new UsuarioSistema(db_getsession("DB_id_usuario")));
      $oCompetencia->setHora(date("H:i"));
      $oCompetencia->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParam->sDescricao));
      $oCompetencia->setFinanciamento(FinanciamentoSaudeRepository::getFinanciamentoSaudeByCodigo($oParam->iFinanciamento));

      $oCompetencia->salvar();

      db_fim_transacao();
      break;
    case 'remover':

      db_inicio_transacao();
      $oCompetencia = new CompetenciaLaboratorio($oParam->iCodigo);
      $oCompetencia->remover();
      db_fim_transacao();

      break;

    case 'getDadosCompetenciaFechadas':

      $sCampos  = " la54_i_codigo, ";
      $sCampos .= " case ";
      $sCampos .= "   when exists (select 1";
      $sCampos .= "                  from lab_bpamagnetico";
      $sCampos .= "                 where lab_bpamagnetico.la55_i_fechamento = lab_fechamento.la54_i_codigo)";
      $sCampos .= "   then true";
      $sCampos .= "   else false";
      $sCampos .= " end as gero_arquivo  ";

      $oDaoLabFechamento = new cl_lab_fechamento();
      $sSqlFechamento    = $oDaoLabFechamento->sql_query_file( null, $sCampos, "la54_d_data desc", null );
      $rsFechamento      = db_query( $sSqlFechamento );

      $iLinhas     = pg_num_rows( $rsFechamento );
      $aFechamento = array();

      if ( $iLinhas > 0 ) {

        for ( $iContador = 0; $iContador < $iLinhas; $iContador++ ) {

          $oDadosFechamento = db_utils::fieldsMemory($rsFechamento, $iContador);
          $oCompetencia     = new CompetenciaLaboratorio($oDadosFechamento->la54_i_codigo);

          $oDados                    = new stdClass();
          $oDados->iCodigoFechamento = $oCompetencia->getCodigo();
          $oDados->iMesCompetencia   = $oCompetencia->getCompetencia()->getMes();
          $oDados->iAnoCompetencia   = $oCompetencia->getCompetencia()->getAno();
          $oDados->dtSistema         = $oCompetencia->getDataInclusao()->convertTo(DBDate::DATA_PTBR);
          $oDados->sHoraSistema      = $oCompetencia->getHora();
          $oDados->iFinanciamento    = $oCompetencia->getFinanciamento()->getCodigo();
          $oDados->dtInicio          = $oCompetencia->getPeriodoInicial()->convertTo(DBDate::DATA_PTBR);
          $oDados->dtFim             = $oCompetencia->getPeriodoFinal()->convertTo(DBDate::DATA_PTBR);
          $oDados->sDescricao        = urlencode($oCompetencia->getDescricao());
          $oDados->sUsuario          = urlencode($oCompetencia->getUsuario()->getNome());
          $oDados->lGerouArquivo     = $oDadosFechamento->gero_arquivo == 't';
          $aFechamento[]             = $oDados;
        }
      }

      $oRetorno->aDados = $aFechamento;
      break;

    case 'gerarArquivo':

      $iTipoLayout = BPAMagnetico::BPA_CONSOLIDADO;
      if ($oParam->sTipo == '02') {
        $iTipoLayout = BPAMagnetico::BPA_INDIVIDUAL;
      }

      $oCompetencia  = new CompetenciaLaboratorio($oParam->iCompetencia);

      $sNomeArquivo            = "/tmp/{$oParam->sNomeArquivo}";
      $sArquivoInconsistencia  = "tmp/erro_bpa_magnetico.json";

      $oBPAMagnetico = new BPAMagnetico($iTipoLayout, $sNomeArquivo, $oCompetencia, new DBLogJSON($sArquivoInconsistencia));
      $oBPAMagnetico->setInstituicao(new Instituicao(db_getsession("DB_instit")));

      /**
       * Adiciona as laboratorios filtrado
       */
      foreach ($oParam->aLaboratorios as $iCodigo) {
        $oBPAMagnetico->adicionarLaboratorio( new Laboratorio($iCodigo) );
      }

      $oBPAMagnetico->setVersaoSistema(DB_VERSION);

      db_inicio_transacao();
      $oBPAMagnetico->escreverArquivo();
      db_fim_transacao();

      /**
       * Retorno dos dados para cliente
       */
      $oRetorno->oDadosBPA              = $oBPAMagnetico->getInformacoesCabecalho();
      $oRetorno->sNomeArquivo           = urlencode($sNomeArquivo);
      $oRetorno->lTemInconsistencia     = $oBPAMagnetico->temInconsistencia();
      $oRetorno->sArquivoInconsistencia = urlencode($sArquivoInconsistencia);

      break;

    case 'buscaLaboratorios':

      $oRetorno->aLaboratorios = array();
      $aLaboratorios           = LaboratorioRepository::getLaboratorios();

      foreach( $aLaboratorios as $oLaboratorio ) {

        $oDadosLaboratorio             = new stdClass();
        $oDadosLaboratorio->iCodigo    = $oLaboratorio->getCodigo();
        $oDadosLaboratorio->sDescricao = urlencode( $oLaboratorio->getDescricao() );
        $oRetorno->aLaboratorios[]     = $oDadosLaboratorio;
      }

      break;

    case 'reabrirCompetencia' :

      $sWhere               = " la58_i_fechamento = {$oParam->iCompetencia} ";
      $oDaoFechaConferencia = new cl_lab_fechaconferencia();
      $sSqlFechaConferencia = $oDaoFechaConferencia->sql_query_file(null, "la58_i_codigo", null, $sWhere);
      $rsFechaConferencia   = db_query($sSqlFechaConferencia);

      $iLinhas = pg_num_rows($rsFechaConferencia);

      for ($i = 0; $i < $iLinhas; $i++) {

        $iCodigo = db_utils::fieldsMemory($rsFechaConferencia, $i)->la58_i_codigo;

        $oDaoFechaConferencia->la58_gerado   = 'false';
        $oDaoFechaConferencia->la58_i_codigo = $iCodigo;
        $oDaoFechaConferencia->alterar($iCodigo);
      }

      break;
  }
} catch ( Exception $oErro ) {

  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());

}

echo $oJson->encode($oRetorno);