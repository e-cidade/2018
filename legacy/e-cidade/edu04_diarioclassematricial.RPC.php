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

require("libs/db_stdlib.php");
require("libs/db_stdlibwebseller.php");
require("std/DBLargeObject.php");
require("std/db_stdClass.php");
require("libs/db_app.utils.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("dbforms/db_funcoes.php");
require_once('model/DBProcessaTemplateTXT.model.php');

include("libs/JSON.php");

$oJson       = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->iEscola = db_getsession("DB_coddepto");
$iModuloEscola     = 1100747;
switch ($oParam->exec) {

  case 'processarImpressaoRegistroClasse':

    try {

      $sDataDia           = date("Y-m-d", db_getsession("DB_datausu"));
      $sWhereTurma        = "ed57_i_codigo in(".implode(",", $oParam->aTurmas).")";
      $oDaoMatriculaSerie = db_utils::getDao("matriculaserie");
      $oDaoTurma          = db_utils::getDao("turma");
      $sSqlTurma          = $oDaoTurma->sql_query_turmaserie(null,
                                                 "distinct ed57_i_codigo as codigo_turma,
                                                  ed57_c_descr as turma,
                                                  ed15_c_nome as turno,
                                                  ed52_i_ano as ano,
                                                  ed18_c_nome as escola,
                                                  ed29_c_descr as grau,
                                                  ed18_codigoreferencia",
                                                 "2",
                                                 $sWhereTurma
                                                );

      $rsTurma        = $oDaoTurma->sql_record($sSqlTurma);
      $aTurmas        = array();
      $iTotalTurmas   = $oDaoTurma->numrows;
      for ($iTurma = 0; $iTurma < $iTotalTurmas; $iTurma++) {

        $oTurma          = db_utils::fieldsMemory($rsTurma, $iTurma);

        if ( $oTurma->ed18_codigoreferencia != null ) {
          $oTurma->escola = "{$oTurma->ed18_codigoreferencia} - {$oTurma->escola}";
        }

        $oTurma->aAlunos = array();

        $sCampos         = "ed60_c_situacao as situacao,";
        $sCampos         = "ed60_i_aluno as matricula,";
        $sCampos        .= "ed47_v_nome  as nome,";
        $sCampos        .= "ed60_i_numaluno  as numero,";
        $sCampos        .= "ed47_v_sexo  as sexo,";
        $sCampos        .= "substr(fc_idade_anomesdia(ed47_d_nasc, cast('{$sDataDia}' as date), false), 1, 5) as idade";
        $sSqlAlunos      = $oDaoMatriculaSerie->sql_query(null,
                                                          $sCampos,
                                                          "to_ascii(ed47_v_nome, 'LATIN1')",
                                                          "ed60_i_turma = {$oTurma->codigo_turma} and ed60_c_situacao in ( 'MATRICULADO' , 'REMATRICULADO')"
                                                         );
        $rsAlunos        = $oDaoMatriculaSerie->sql_record($sSqlAlunos);
        $iTotalAlunos    = $oDaoMatriculaSerie->numrows;
        for ($iAluno = 0; $iAluno < $iTotalAlunos; $iAluno++) {
          $oAluno             = db_utils::fieldsMemory($rsAlunos, $iAluno);

          $aPartesIdade       = explode(",",$oAluno->idade);
          $oAluno->numero     = $iAluno + 1;
          $oAluno->idade      = str_pad(trim($aPartesIdade[0]), 2, "0", STR_PAD_LEFT)."|";
          $oAluno->idade     .= str_pad(trim($aPartesIdade[1]), 2, "0", STR_PAD_LEFT);
          $oTurma->aAlunos[] = $oAluno;

        }

        $aTurmas[] = $oTurma;

      }

      $sModelo  = 'documentos/templates/txt/registro_turma.txt';
      $oGerador = new DBProcessaTemplateTXT($sModelo);
      foreach ($aTurmas as $oTurma) {

        unset($aDados);
        unset($aAlunos);

        $aDados[]  = array($oTurma);
        $aDados[]  = $oTurma->aAlunos;
        $oGerador->setDados($aDados);
        $oGerador->gerarArquivo();
        $aArquivos[]  = TiraAcento($oGerador->getArquivo(), false);

      }
      unset($aTurmas);
      $sSessionNome = "registro_turma";
      if (isset ($_SESSION [$sSessionNome])) {
       unset ($_SESSION [$sSessionNome]);
      }
      $_SESSION[$sSessionNome] = $aArquivos;
      $oRetorno->sSessionNome  = $sSessionNome;
      $oRetorno->aArquivo      = $aArquivos;
      $oCfauntent       = db_utils::getdao('cfautent');
      $sCampos          = "k11_id, k11_ipimpcheque, k11_local";
      $sSql             = $oCfauntent->sql_query(null,$sCampos);
      $rs               = $oCfauntent->sql_record($sSql);
      $iTam             = $oCfauntent->numrows;
      $aImpressoraId    = array();
      $aImpressoraDescr = array();
      $iIpPadrao        = 0;
      for ($iInd = 0; $iInd < $iTam; $iInd++) {

        $oImpressora         = db_utils::fieldsmemory($rs, $iInd);
        $aImpressoraId[ ]    = $oImpressora->k11_id;
        $aImpressoraDescr[ ] = $oImpressora->k11_ipimpcheque.' - '.urlencode($oImpressora->k11_local);
        //verifica impressora padrão
        $iIp = $_SERVER['REMOTE_ADDR'];
        if ($iIp == $oImpressora->k11_ipimpcheque) {
          $iIpPadrao = $oImpressora->k11_id;
        }

      }
      $oRetorno->iIpPadrao        = $iIpPadrao;
      $oRetorno->aImpressoraId    = $aImpressoraId;
      $oRetorno->aImpressoraDescr = $aImpressoraDescr;

    } catch (Exception $oExcecao) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace('"', '\"', $oExcecao->getMessage()));
    }

  break;

  case 'processarImpressaoDiarioClasse':

    $aAlunos = array();
    $oTurma  = TurmaRepository::getTurmaByCodigo($oParam->iTurma);

    foreach( $oTurma->getUltimaMatriculaAlunos() as $oMatricula ) {

      if( $oMatricula->getSituacao() == 'TROCA DE TURMA' ) {
        continue;
      }

      $oDadosAluno            = new stdClass();
      $oDadosAluno->matricula = $oMatricula->getAluno()->getCodigoAluno();
      $oDadosAluno->nome      = $oMatricula->getAluno()->getNome();
      $oDadosAluno->numero    = $oMatricula->getNumeroOrdemAluno();
      $aAlunos[]              = $oDadosAluno;
    }

    foreach ($aAlunos as $iIndice => $oAluno) {
      $oAluno->numero = ($iIndice + 1);
    }

    $sWhere        = "ed57_i_codigo   = {$oParam->iTurma} ";
    $sWhere       .= " and ed232_i_codigo in (".implode(",", $oParam->aDisciplinas).")";
    $oDaoRegencia  = db_utils::getdao('regencia');
    $sSqlRegencia  = $oDaoRegencia->sql_query("",
                                                 "distinct
                                                  ed232_i_codigo as codigo_disciplina,
                                                  ed232_c_descr  as nome_disciplina,
                                                  ed57_c_descr as turma,
                                                  ed15_c_nome as turno,
                                                  ed52_i_ano as ano,
                                                  ed11_c_descr as serie,
                                                  ed18_c_nome as escola,
                                                  ed18_codigoreferencia,
                                                  '' as periodo,
                                                  ed29_c_descr as grau",
                                                  "",
                                                  $sWhere
                                             );
    $rsRegencia   = $oDaoRegencia->sql_record($sSqlRegencia);
    $aDisciplinas = db_utils::getCollectionByRecord($rsRegencia);
    try {

      switch ($oParam->iModelo) {

        case 1:
          $sModelo = 'documentos/templates/txt/diario_classe_educacao_infantil_matricial.txt';
        break;
        case 2:
          $sModelo = 'documentos/templates/txt/diario_classe_anos_iniciais_matricial.txt';
        break;
        case 3:
          $sModelo = 'documentos/templates/txt/diario_classe_anos_finais_matricial.txt';
        break;

        default:

          throw new Exception('Modelo de impressão não identificado');
          break;
      }

      $oGerador = new DBProcessaTemplateTXT($sModelo);
      foreach ($aDisciplinas as $oDisciplina) {

        if ( $oDisciplina->ed18_codigoreferencia != null ) {
          $oDisciplina->escola = "{$oDisciplina->ed18_codigoreferencia} - {$oDisciplina->escola}";
        }

        if (trim($oParam->sPeriodo)) {
          $oDisciplina->periodo = db_stdClass::normalizeStringJson($oParam->sPeriodo);
        }
        $aDados    = array();
        $aDados[]  = array($oDisciplina);
        $aDados[]  = $aAlunos;
        $oGerador->setDados($aDados);
        $oGerador->gerarArquivo();
        $aArquivos[]  = TiraAcento($oGerador->getArquivo(), false);
      }
      $sSessionNome = "diario_classe_turma_{$oParam->iTurma}";
      if (isset ($_SESSION [$sSessionNome])) {
       unset ($_SESSION [$sSessionNome]);
      }
      $_SESSION[$sSessionNome] = $aArquivos;
      $oRetorno->sSessionNome  = $sSessionNome;
      $oRetorno->aArquivo      = $aArquivos;
      $oCfauntent       = db_utils::getdao('cfautent');
      $sCampos          = "k11_id, k11_ipimpcheque, k11_local";
      $sSql             = $oCfauntent->sql_query(null,$sCampos);
      $rs               = $oCfauntent->sql_record($sSql);
      $iTam             = $oCfauntent->numrows;
      $aImpressoraId    = array();
      $aImpressoraDescr = array();
      $iIpPadrao        = 0;
      for ($iInd = 0; $iInd < $iTam; $iInd++) {

        $oImpressora         = db_utils::fieldsmemory($rs, $iInd);
        $aImpressoraId[ ]    = $oImpressora->k11_id;
        $aImpressoraDescr[ ] = $oImpressora->k11_ipimpcheque.' - '.urlencode($oImpressora->k11_local);
        //verifica impressora padrão
        $iIp = $_SERVER['REMOTE_ADDR'];
        if ($iIp == $oImpressora->k11_ipimpcheque) {
          $iIpPadrao = $oImpressora->k11_id;
        }
      }
      $oRetorno->iIpPadrao        = "192.168.0.107";
      $oRetorno->aImpressoraId    = $aImpressoraId;
      $oRetorno->aImpressoraDescr = $aImpressoraDescr;
    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode(str_replace('"', '\"', $eErro->getMessage()));
    }

}
echo $oJson->encode($oRetorno);
?>