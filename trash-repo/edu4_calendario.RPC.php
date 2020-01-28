<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
require_once ("libs/db_conecta.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("std/db_stdClass.php");
require_once ("libs/JSON.php");
require_once ("std/DBDate.php");
require_once ("dbforms/db_funcoes.php");
require_once("model/educacao/avaliacao/iElementoAvaliacao.interface.php");
require_once("model/educacao/avaliacao/iFormaObtencao.interface.php");
require_once("model/educacao/censo/DadosCenso.model.php");
require_once("classes/db_cursoedu_classe.php");
require_once("model/CgmFactory.model.php");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"])); 

$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;
$oRetorno->message  = "";

/**
 * Caminho padr�o das mensagens
 */
$sCaminhoMensagens  = "educacao.escola.edu4_calendario.";

/**
 * Objeto para armazenar atributos a serem enviados nas mensagens
 */
$oMensagem = new stdClass();

try {

  switch ($oParam->exec) {
    
    case 'vinculaCalendarioBaseEscola':
            
      db_inicio_transacao();
      
      $iCodigoCalendario = null;
      
      if (isset($oParam->iCodigoCalendario)) {
        
        $iCodigoCalendario = $oParam->iCodigoCalendario;
      }
      
      $oCalendario   = new Calendario($iCodigoCalendario);
      
      /**
       * Dados do calendario
       */
      if (db_getsession("DB_modulo") == 1100747) {
        
        $oCalendario->setEscola          (new Escola(db_getsession("DB_coddepto")));
      }
      
      $oCalendario->setDescricao         (db_stdClass::normalizeStringJsonEscapeString($oParam->sDescricao));
      $oCalendario->setPeriodicidade     ($oParam->iPeriodicidade);
      $oCalendario->setPeriodo           ($oParam->iPeriodo);
      $oCalendario->setAnoExecucao       ($oParam->iAno);
      $oCalendario->setDataInicio        (new DBDate($oParam->dDataInicio));
      $oCalendario->setDataFim           (new DBDate($oParam->dDataFim));
      $oCalendario->setDataResultadoFinal(new DBDate($oParam->dDataResultadoFinal));
      $oCalendario->setDiasLetivos       ($oParam->iDiasLetivos);
      $oCalendario->setSemanasLetivos    ($oParam->iSemanasLetivas);
      
      $oCalendarioAnterior = new Calendario($oParam->iCodigoCalendarioAnterior);
      $oCalendario->setCalendarioAnterior($oCalendarioAnterior);
      
      if ($oParam->sOpcao != "Incluir") {
        
        $oCalendario->setTipoOperacao('Importar');
      }
      
      if ($oParam->sOpcao == "Incluir" && !empty($oParam->iCodigoCalendarioBase)) {
        $oCalendario->setTipoOperacao('Salvar');
        $oCalendarioBase = new Calendario($oParam->iCodigoCalendarioBase);
        
        /**
         * Dados dos periodos de avalia��o
         */
        foreach ($oCalendarioBase->getPeriodos() as $oPeriodo) {
          
          $oCalendario->setPeriodos($oPeriodo);
        }
         
        /**
         * Dados dos feriados e eventos
         */
        if ($oCalendarioBase->getEventos() != null) {
           
          foreach ($oCalendarioBase->getEventos() as $oEvento) {
            
            $oCalendario->setEventos($oEvento);
          }
        }
      } 
      
      /**
       * Salva Calendario novo juntamente com os periodos de avalia��o e feriados ou eventos
       */
      $oCalendario->save();
      
      /**
       * Ap�s salvar o calendario, salvo os feriados padr�es caso n�o tenha sido eles j� definidos
       */
      
      if ($oParam->sOpcao == "Incluir" && $oParam->iCodigoCalendarioBase == null) {
        
        $Y = $oCalendario->getAnoExecucao();      // Ano
        $G = ($Y % 19) + 1;                       // Numero �ureo
        $C = intval(($Y / 100) + 1);              // Seculo
        $X = intval(((3 * $C) / 4) - 12);         // Primeira Corre��o
        $Z = intval((((8 * $C) + 5) / 25) - 5);   // Epacta
        $E = ((11 * $G) + 20 + $Z - $X) % 30;
        
        if ((($E == 25) AND ($G > 11)) OR ($E == 24)) {
        
          $E += 1;
        }
        
        $N= 44 - $E; // Lua Cheia
        
        if ($N < 21) {
          $N += 30;
        }
        
        $D = intval( ( (5 * $Y) / 4) ) - ($X + 10); //Domingo
        $N = ($N + 7) - ($D + $N) % 7;
        
        if ($N > 31) {
        
          $diapascoa = $N - 31;
          $diames    = 4;
        } else {
        
          $diapascoa = $N;
          $diames    = 3;
        }
        
        $datas  = "CARNAVAL|"                   . date("Y-m-d", mktime (0, 0, 0, $diames , $diapascoa - 47, $Y)) . "|N#";
        $datas .= "PAIX�O DE CRISTO|"           . date("Y-m-d", mktime (0, 0, 0, $diames , $diapascoa - 2, $Y))  . "|N#";
        $datas .= "P�SCOA|"                     . date("Y-m-d", mktime (0, 0, 0, $diames , $diapascoa, $Y))      . "|N#";
        $datas .= "CORPUS CHRISTI|"             . date("Y-m-d", mktime (0, 0, 0, $diames , $diapascoa + 60, $Y)) . "|N#";
        $datas .= "CONFRATERNIZA��O UNIVERSAL|" . $oCalendario->getAnoExecucao() . "-01-01|N#";
        $datas .= "TIRADENTES|"                 . $oCalendario->getAnoExecucao() . "-04-21|N#";
        $datas .= "DIA DO TRABALHO|"            . $oCalendario->getAnoExecucao() . "-05-01|N#";
        $datas .= "INDEPEND�NCIA DO BRASIL|"    . $oCalendario->getAnoExecucao() . "-09-07|N#";
        $datas .= "NOSSA SENHORA APARECIDA|"    . $oCalendario->getAnoExecucao() . "-10-12|N#";
        $datas .= "FINADOS|"                    . $oCalendario->getAnoExecucao() . "-11-02|N#";
        $datas .= "PROCLAMA��O DA REP�BLICA|"   . $oCalendario->getAnoExecucao() . "-11-15|N#";
        $datas .= "NATAL|"                      . $oCalendario->getAnoExecucao() . "-12-25|N";
        
        $array_datas = explode("#", $datas);
        
        $ed52_d_inicio = $oCalendario->getDataInicio();
        $ed52_d_fim    = $oCalendario->getDataFinal();
        
        
        for($x = 0; $x < count($array_datas); $x++) {
          
          $array_dados = explode("|", $array_datas[$x]);
        
          $dd = substr($array_dados[1],8,2);
          $mm = substr($array_dados[1],5,2);
          $aa = substr($array_dados[1],0,4);
          $diasemana = date("w", mktime (0, 0, 0, $mm , $dd, $aa));
          if($diasemana==0) $diasemana = "DOMINGO";
          if($diasemana==1) $diasemana = "SEGUNDA";
          if($diasemana==2) $diasemana = "TER�A";
          if($diasemana==3) $diasemana = "QUARTA";
          if($diasemana==4) $diasemana = "QUINTA";
          if($diasemana==5) $diasemana = "SEXTA";
          if($diasemana==6) $diasemana = "S�BADO";
          
          $oDaoEvento = new CalendarioEvento();
        
          $oDaoEvento->setDataEvento(new DBDate($array_dados[1]));
          $oDaoEvento->setDescricao($array_dados[0]);
          $oDaoEvento->setDiaLetivo($array_dados[2] == 'S' ? true : false);
          $oDaoEvento->setDiaSemana($diasemana);
          $oDaoEvento->setTipoEvento(1);
          
          $oDaoEvento->salvar($oCalendario); 
        
        }
      }
      
      db_fim_transacao(false);

      $oRetorno->status = 1;
      $oRetorno->iCalendario = $oCalendario->getCodigo();
      
    break;
      
    case 'excluirCalendario':
        
      if (isset($oParam->iCodigoCalendario)) {
       
        db_inicio_transacao();
        
        $oDaoCalendarioEscola  = new cl_calendarioescola();
        $oDaoFeriado           = new cl_feriado();
        $oDaoPeriodoCalendario = new cl_periodocalendario();
        $oDaoCalendario        = new cl_calendario();
        $oDaoTurma             = new cl_turma();

        /**
         * Verifica se existe alguma turma vinculada ao calend�rio. Caso exista, retorna mensagem
         */
        $sSqlTurma = $oDaoTurma->sql_query_file( null, "ed57_i_codigo", null, "ed57_i_calendario = {$oParam->iCodigoCalendario}" );
        $rsTurma   = db_query( $sSqlTurma );

        if ( !$rsTurma ) {

          $oMensagem->sErro = $oDaoTurma->erro_msg;
          throw new DBException( _M( $sCaminhoMensagens."erro_query_turma", $oMensagem ) );
        }

        if ( pg_num_rows( $rsTurma ) > 0 ) {
          throw new BusinessException( _M( $sCaminhoMensagens."turmas_vinculadas" ) );
        }

        $sSqlUpdateCalendarioAnterior = "update calendario set ed52_i_calendant = null
                                          where ed52_i_calendant = {$oParam->iCodigoCalendario}";
        
        $rsQuery = db_query($sSqlUpdateCalendarioAnterior);
        
        $oDaoCalendarioEscola->excluir(""," ed38_i_calendario = {$oParam->iCodigoCalendario}");
        $oDaoFeriado->excluir(""," ed54_i_calendario = {$oParam->iCodigoCalendario}");
        $oDaoPeriodoCalendario->excluir(""," ed53_i_calendario = {$oParam->iCodigoCalendario}");
        $oDaoCalendario->excluir($oParam->iCodigoCalendario);
        
        $oRetorno->message = urlencode ( _M( $sCaminhoMensagens."calendario_excluido" ) );
        
        db_fim_transacao( false );
      }
    
    break;
      
    case 'carregaDadosCalendario':
      
      $oCalendarioBase   = new Calendario($oParam->iCalendario);
    
      $oCalendarioClone  = clone $oCalendarioBase;
      
      $oDadosCalendario->iCodigo                   = $oCalendarioClone->getCodigo();
      
      $oDadosCalendario->sDescricao                = urlencode($oCalendarioClone->getDescricao());
      $oDadosCalendario->iPeriodicidade            = $oCalendarioClone->getPeriodicidade();
      $oDadosCalendario->iPeriodo                  = $oCalendarioClone->getPeriodo();
      
      if (!empty($oDadosCalendario->iPeriodicidade)) {
        
        $oDaoDuracaoCal = db_utils::getDao('duracaocal');
        $sSqlDuracaoCal = $oDaoDuracaoCal->sql_query_file($oCalendarioClone->getPeriodicidade());
        $rsDuracaoCal   = $oDaoDuracaoCal->sql_record($sSqlDuracaoCal);
        
        $oDadosCalendario->sDescricaoPeriodicidade      = urlencode(db_utils::fieldsMemory($rsDuracaoCal,0)->ed55_c_descr);
      } else {
        $oDadosCalendario->sDescricaoPeriodicidade = null;
      }
      $oDadosCalendario->iAno                         = $oCalendarioClone->getAnoExecucao();
      $oDadosCalendario->dDataInicio                  = db_formatar($oCalendarioClone->getDataInicio()->getDate(DBDate::DATA_EN), "d");
      $oDadosCalendario->dDataFim                     = db_formatar($oCalendarioClone->getDataFinal()->getDate(DBDate::DATA_EN), "d");
      $oDadosCalendario->dDataResultadoFinal          = db_formatar($oCalendarioClone->getDataResultadoFinal()->getDate(DBDate::DATA_EN), "d");
      $oDadosCalendario->iDiasLetivos                 = $oCalendarioClone->getDiasLetivos();
      $oDadosCalendario->iSemanasLetivas              = $oCalendarioClone->getSemanasLetivas();
      $oDadosCalendario->iCodigoCalendarioAnterior    = $oCalendarioClone->getCalendarioAnterior()->getCodigo();
      $oDadosCalendario->sDescricaoCalendarioAnterior = urlencode($oCalendarioClone->getCalendarioAnterior()->getDescricao());
      
      $oRetorno->oDadosCalendarioClone = $oDadosCalendario;
      $oRetorno->iCodigoBaseCalendario = $oParam->iCalendario;
      
      $oRetorno->status = 1;
    
      break;
  }

} catch (Exception $oErro) {

  db_fim_transacao(true);
  $oRetorno->status  = 2;
  $oRetorno->message = urlencode($oErro->getMessage());
}
echo $oJson->encode($oRetorno);