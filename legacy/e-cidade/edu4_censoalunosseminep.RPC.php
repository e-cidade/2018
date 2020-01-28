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
require_once ("libs/db_utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/JSON.php");
require_once ("dbforms/db_layouttxt.php");
require_once ("classes/db_db_layoutcampos_classe.php");
require_once ("model/dbLayoutReader.model.php");
require_once ("model/dbLayoutLinha.model.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_app.utils.php");

db_app::import("exceptions.*");
$iModulo           = db_getsession("DB_modulo");
$iModuloEscola     = 1100747;
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

$oJson  = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
switch ($oParam->exec) {

  case 'gerarArquivosCensoSemInep':

    $sNomeArquivoAluno    = "tmp/arquivoaluno.txt";
    $sNomeArquivoDocentes = "tmp/arquivodocentes.txt";
    $aArquivosGerados  = array();

    try {

    if ($oParam->iTipoGeracao == 1 || $oParam->iTipoGeracao == 3) {

        if (gerarDadosAlunoSemInep($oParam, $sNomeArquivoAluno)) {
        $aArquivosGerados[] = $sNomeArquivoAluno;
      }
    }
    if ($oParam->iTipoGeracao == 1 || $oParam->iTipoGeracao == 2) {

      if (gerarDadosDocentesSemInep($oParam, $sNomeArquivoDocentes)) {
        $aArquivosGerados[] = $sNomeArquivoDocentes;
      }
    }
    $oRetorno->arquivos  = $aArquivosGerados;

    } catch (ParameterException $oErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($oErro->getMessage());
    }
    break;

  case 'processarArquivo':

    try {

      if (!isset($oParam->arquivo)) {
        throw new Exception('Arquivo não informado.');
      }
      if (!file_exists($oParam->arquivo)) {
        throw new Exception('Arquivo informado não foi encontrado para realizar a importação.');
      }

      if (strtolower((substr($oParam->arquivo, -3, 3))) != "txt") {
        throw new Exception('Arquivo informado não é válido para a importação.');
      }
      $rArquivo       = fopen($oParam->arquivo, "r");
      $sPrimeiraLinha = fgets($rArquivo);
      $iTamanhoLinha  = strlen(str_replace(array("\r", "\n"), "", $sPrimeiraLinha));

      db_inicio_transacao();
      switch ($oParam->tiporetorno) {

        case 1:

          if ($iTamanhoLinha != 351) {
            throw new Exception('o Arquivo informado não é um arquivo de alunos válido.');
          }
          $oDaoAluno               = db_utils::getDao("aluno");
          $oLayoutReader           = new DBLayoutReader(182, $oParam->arquivo, false);
          $aLinhas                 = $oLayoutReader->getLines();
          $iTotalLinhasAtualizadas = 0;
          foreach ($aLinhas as $iLinhas => $oLinha) {

            if ((int)$oLinha->codigoalunoinep == 0) {
              continue;
            }
            if (trim($oLinha->idalunoinep) == "") {
              continue;
            }
            $oDaoAluno->ed47_i_codigo     = $oLinha->codigoalunoinep;
            $oDaoAluno->ed47_c_codigoinep = $oLinha->idalunoinep;
            $oDaoAluno->alterar($oLinha->codigoalunoinep);
            if ($oDaoAluno->erro_status == 0) {

              $sErroMsg  = "Erro ao cadastrar o codigo do inep do aluno {$oLinha->nomealuno}.\n";
              $sErroMsg .= "Erro Técnico:\n{$oDaoAluno->erro_msg}";
              throw new Exception($sErroMsg);
            }
            $iTotalLinhasAtualizadas++;
          }
          $oRetorno->linhasatualizadas = $iTotalLinhasAtualizadas;
          break;

         case 2:

           if ($iTamanhoLinha != 262) {
             throw new Exception('o Arquivo informado não é um arquivo de docentes válido.');
           }
           $oDaoRecHumano           = db_utils::getDao("rechumano");
           $oLayoutReader           = new DBLayoutReader(183, $oParam->arquivo, false);
           $aLinhas                 = $oLayoutReader->getLines();
           $iTotalLinhasAtualizadas = 0;
           foreach ($aLinhas as $iLinhas => $oLinha) {


             if ((int)$oLinha->idinep == 0) {
               continue;
             }
             $iCodigoCgmDocente  = (int)$oLinha->codigodocenteescola;
             $sWhere              = "cgmrh.z01_numcgm = {$iCodigoCgmDocente} or cgmcgm.z01_numcgm = {$iCodigoCgmDocente} ";
             $sSqlCodigoDocente   = $oDaoRecHumano->sql_query_censomodel(null, "ed20_i_codigo", null, $sWhere);
             $rsCodigoDocente     = $oDaoRecHumano->sql_record($sSqlCodigoDocente);
             $iTotalLinhaDocentes = $oDaoRecHumano->numrows;
             $aDocentes           = db_utils::getCollectionByRecord($rsCodigoDocente);

             foreach ($aDocentes as $oDocente) {

               $oDaoRecHumano->ed20_i_codigo     = $oDocente->ed20_i_codigo;
               $oDaoRecHumano->ed20_i_codigoinep = (int)$oLinha->idinep;
               $oDaoRecHumano->alterar($oDocente->ed20_i_codigo);
               if ($oDaoRecHumano->erro_status == 0) {

                 $sErroMsg  = "Erro ao cadastrar o codigo do inep do aluno {$oLinha->nomedocente}.\n";
                 $sErroMsg .= "Erro Técnico:\n{$oDaoRecHumano->erro_msg}";
                 throw new Exception($sErroMsg);
               }
             }

           }
           break;
      }
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;

  case 'getEscolas':

    try {

      $sFiltraModulo   = false;
      if (isset($oParam->filtraModulo)) {
        $sFiltraModulo = $oParam->filtraModulo;
      }
      $sWhereEscola = '';
      if ($sFiltraModulo && db_getsession("DB_modulo") == $iModuloEscola) {
        $sWhereEscola = ' ed18_i_codigo = '.db_getsession("DB_coddepto");
      }

      $oDaoEscola       = db_utils::getDao('escola');
      $sCampoEscola     = ' ed18_i_codigo as codigo_escola, ed18_c_nome as nome_escola';
      $sSqlEscola       = $oDaoEscola->sql_query(null, $sCampoEscola, null, $sWhereEscola);

      $rsEscola               = $oDaoEscola->sql_record($sSqlEscola);
      $oRetorno->iTotalLinhas = $oDaoEscola->numrows;
      $oRetorno->aDados = db_utils::getCollectionByRecord($rsEscola, false, false, true);
      $oRetorno->status = 1;
    } catch (BusinessException $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
    break;
}
echo $oJson->encode($oRetorno);

function gerarDadosAlunoSemInep($oParam, $sNomeArquivo) {

  if (count($oParam->aEscola) == 0) {
    throw new ParameterException('Não foi informado nenhuma escola.');
  }

  $iEscola        = implode(", ", $oParam->aEscola);
  $sCampos        = " ed47_d_nasc as datanascimento,";
  $sCampos       .= " fc_remove_acentos(ed47_v_nome) as nomealuno,";
  $sCampos       .= " fc_remove_acentos(ed47_v_pai) as nomepaialuno, ";
  $sCampos       .= " fc_remove_acentos(ed47_v_mae) as nomemaealuno,";
  $sCampos       .= " ed47_i_codigo as codigoalunoinep, ";
  $sCampos       .= " ed47_i_censomunicnat as municipionascimento,";
  $sCampos       .= " ed47_i_censoufnat as ufnascimento,";
  $sCampos       .= " ed47_c_codigoinep as idalunoinep";
  $sWhere         = " ed47_c_codigoinep = '' ";
  $sWhere        .= " and ed18_i_codigo  in ({$iEscola})";
  $sWhere        .= " and ed52_i_ano      = {$oParam->iAno}";
  $sWhere        .= " and ed60_c_situacao ='MATRICULADO' ";
  $oDaoAluno      = db_utils::getDao("aluno");
  $sSqlDadosAluno = $oDaoAluno->sql_query_censo_inep(null, $sCampos, "ed47_i_codigo", $sWhere);


  $rsDadosAluno   = $oDaoAluno->sql_record($sSqlDadosAluno);
  $iLinhas        = $oDaoAluno->numrows;
  if ($iLinhas == 0) {
    return false;
  }

  $iTipoLinha = 1;
  $iLayout    = 182;
  if ( $oParam->iAno == 2015 ) {

    $iTipoLinha = 3;
    $iLayout    = 228;
  }

  $oDBLayout  = new db_layouttxt($iLayout, $sNomeArquivo, "");
  for ($iContador = 0; $iContador < $iLinhas; $iContador++) {

    /**
     * Seta o objeto do layout com os nomes dos campos criados,
     * recebendo com a formatação do str_pad os valores dos campos vindos do banco de dados.
     */
    $oDados                 = db_utils::fieldsmemory($rsDadosAluno, $iContador);
    $oDados->nomealuno      = trim(tiraAcento($oDados->nomealuno));
    $oDados->nomepaialuno   = trim(tiraAcento($oDados->nomepaialuno));
    $oDados->datanascimento = db_formatar($oDados->datanascimento, 'd');
    if ( $oParam->iAno < 2015 ) {
      $oDados->idalunoinep    = str_repeat(" ", 12);
    }

    $oDBLayout->setByLineOfDBUtils($oDados, $iTipoLinha);

  }
  $oDBLayout->fechaArquivo();
  return true;
}

function gerarDadosDocentesSemInep($oParam, $sNomeArquivo) {

  if (count($oParam->aEscola) == 0) {
    throw new ParameterException('Não foi informado nenhuma escola.');
  }

  $iEscola          = implode(", ", $oParam->aEscola);
  $iAno             = $oParam->iAno;
  $oDaoRecHumano    = db_utils::getdao("rechumano");
  $sCampos          = "z01_numcgm, ";
  $sCampos         .= "to_char(z01_nasc, 'dd/mm/yyyy') as datadenascimento, ";
  $sCampos         .= "fc_remove_acentos(z01_mae)    as nomemaedocente, ";
  $sCampos         .= "z01_cgccpf as numerocpf, ";
  $sCampos         .= "ed20_i_censomunicender  as municipionascimento, ";
  $sCampos         .= "ed20_i_censoufender as ufnascimento,";
  $sCampos         .= "fc_remove_acentos(z01_nome) as nomedocente, ";
  $sCampos         .= "ed20_i_codigo as codigodocenteescola,";
  $sCampos         .= "ed20_i_codigoinep as idinep";
  $sWhere            = "      ed18_i_codigo in ({$iEscola})";
  $sWhere           .= " and ed20_i_codigoinep is null ";
  $sWhere           .= " and ed52_i_ano = {$iAno} ";
  $sWhere           .= " and ed01_c_regencia = 'S' ";
  $sSqlDadosDocente = $oDaoRecHumano->sql_query_solicitaseminep("", $sCampos, "", $sWhere);
  $rsDadosDocente   = $oDaoRecHumano->sql_record($sSqlDadosDocente);
  $iLinhas          = $oDaoRecHumano->numrows;
  /**
   * Agrupamos os dados do docente por codigo de CGM.
   */
  $aDocentesSemInep = array();
  for ($iContador = 0; $iContador < $iLinhas; $iContador++) {

    $oDadosDocente = db_utils::fieldsmemory($rsDadosDocente, $iContador);
    if (!isset($aDocentesSemInep[$oDadosDocente->z01_numcgm])) {
      $aDocentesSemInep[$oDadosDocente->z01_numcgm] = $oDadosDocente;
    }
  }
  if ($iLinhas == 0) {
    return false;
  }
  $oDBLayout  = new db_layouttxt(183, $sNomeArquivo, "");
  foreach ($aDocentesSemInep as $oDocente) {

    /**
     * Seta o objeto do layout com os nomes dos campos criados,
     * recebendo com a formatação do str_pad os valores dos campos vindos do banco de dados.
     */
    $oDocente->codigodocenteescola = $oDocente->z01_numcgm;
    $oDocente->idinep              = str_repeat(" ", 12);
    $oDBLayout->setByLineOfDBUtils($oDocente, 1);

  }
  $oDBLayout->fechaArquivo();
  return true;
}
?>