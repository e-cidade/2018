<?
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

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_app.utils.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/JSON.php'));
require_once(modification('dbforms/db_funcoes.php'));

db_app::import("exceptions.*");

$iModulo = db_getsession("DB_modulo");
function formataData($dData, $iTipo = 1) {

  if (empty($dData)) {
    return '';
  }

  if ($iTipo == 1) {

    $dData = explode('/', $dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];

    return $dData;

  }

 $dData = explode('-', $dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];

 return $dData;

}

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';
$oRetorno->erro     = false;
$iModuloEscola      = 1100747;
$iEscola            = db_getsession("DB_coddepto");

if ($oParam->exec == 'getDadosUltimaMatriculaAluno') {

  $oDaoMatricula  = db_utils::getdao('matricula');

  if (isset($oParam->iAluno)) {

    $sCampos   = " ed60_i_codigo,ed11_c_descr,ed57_c_descr,ed57_i_base,ed57_i_calendario,ed60_c_situacao, ";
    $sCampos  .= " ed60_c_concluida,ed60_d_datamatricula,ed60_d_datamodif,ed52_c_descr,ed52_d_inicio,ed52_d_fim, ";
    $sCampos  .= " ed57_i_codigo ";

    $sWhere    = " ed60_i_aluno = ".$oParam->iAluno." and ed221_c_origem = 'S' ";

    if (isset($oParam->iEscola)) {

      $sWhere .= " and ed57_i_escola = ".$oParam->iEscola;

    }

    $sSqlQuery = $oDaoMatricula->sql_query_transferenciarede("",
                                                             $sCampos,
                                                             "ed60_i_codigo desc limit 1",
                                                             $sWhere
                                                            );
    $rsResultMatricula = $oDaoMatricula->sql_record($sSqlQuery);

    if ($oDaoMatricula->numrows > 0) {

      $oDadosMatricula                = db_utils::fieldsmemory($rsResultMatricula, 0);

      $oRetorno->ed60_i_codigo        = $oDadosMatricula->ed60_i_codigo;
      $oRetorno->ed11_c_descr         = urlencode($oDadosMatricula->ed11_c_descr);
      $oRetorno->ed57_i_codigo        = $oDadosMatricula->ed57_i_codigo;
      $oRetorno->ed57_c_descr         = urlencode($oDadosMatricula->ed57_c_descr);
      $oRetorno->ed57_i_base          = $oDadosMatricula->ed57_i_base;
      $oRetorno->ed57_i_calendario    = $oDadosMatricula->ed57_i_calendario;
      $oRetorno->ed60_c_situacao      = urlencode($oDadosMatricula->ed60_c_situacao);
      $oRetorno->ed60_c_concluida     = urlencode($oDadosMatricula->ed60_c_concluida);
      $oRetorno->ed60_d_datamatricula = formataData($oDadosMatricula->ed60_d_datamatricula, 0);
      $oRetorno->ed60_d_datamodif     = formataData($oDadosMatricula->ed60_d_datamodif, 0);
      $oRetorno->ed52_c_descr         = urlencode($oDadosMatricula->ed52_c_descr);
      $oRetorno->ed52_d_inicio        = formataData($oDadosMatricula->ed52_d_inicio, 0);
      $oRetorno->ed52_d_fim           = formataData($oDadosMatricula->ed52_d_fim, 0);

    } else { //Se não encontrou nenhuma matricula para o aluno

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar nenhuma matrícula para o aluno: ".
                                      $oParam->iAluno."!");

    }

  } else { //Se não exitir o parametro iAluno

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos dados da matricula do aluno!");

  }

} elseif ($oParam->exec == 'getEscolaForaTransferencia') {

  if (isset($oParam->iEscola)) {

    $oDaoEscolaProc = db_utils::getdao('escolaproc');
    $sSqlEscolaProc = $oDaoEscolaProc->sql_query("", "*", "", " ed82_i_codigo = ".$oParam->iEscola);
    $rsResult       = $oDaoEscolaProc->sql_record($sSqlEscolaProc);

    if ($oDaoEscolaProc->numrows > 0) {

      $oRetorno->lAchou = true;

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar a escola de destino.");

    }

  } else { //Se não existir o parametro iEscola

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca da escola de destino da transferência!");

  }

} elseif ($oParam->exec == 'getCartorioMatricula') {

  if (isset($oParam->iCartorio)) {

    $oDaoCensoCartorio = db_utils::getdao('censocartorio');

    $sWhere            = " ed291_i_serventia = ".$oParam->iCartorio;

    $sSql              = $oDaoCensoCartorio->sql_query("", "*", "", $sWhere);
    $rsResultCartorio  = $oDaoCensoCartorio->sql_record($sSql);

    if ($oDaoCensoCartorio->numrows > 0) {

      $oDadosCartorio = db_utils::fieldsmemory($rsResultCartorio, 0);

      $oRetorno->ed291_i_codigo     = $oDadosCartorio->ed291_i_codigo;
      $oRetorno->ed291_c_nome       = urlencode($oDadosCartorio->ed291_c_nome);
      $oRetorno->ed291_i_serventia  = $oDadosCartorio->ed291_i_serventia;
      $oRetorno->ed291_i_censomunic = $oDadosCartorio->ed291_i_censomunic;

      $oDaoCensoMunic               = db_utils::getdao('censomunic');
      $sSqlCensoMunic               = $oDaoCensoMunic->sql_query("",
                                                                 "*",
                                                                 "",
                                                                 "ed261_i_codigo = ".$oRetorno->ed291_i_censomunic
                                                                );
      $rsResultCensoMunic           = $oDaoCensoMunic->sql_record($sSqlCensoMunic);

      if ($oDaoCensoMunic->numrows > 0) {

        $oDadosMunicipio          = db_utils::fieldsmemory($rsResultCensoMunic, 0);

        $oRetorno->ed261_i_codigo = $oDadosMunicipio->ed261_i_codigo;
        $oRetorno->ed261_c_nome   = urlencode($oDadosMunicipio->ed261_c_nome);

        $oDaoCensoUf              = db_utils::getdao('censouf');
        $sSqlCensoUf              = $oDaoCensoUf->sql_query("",
                                                            "*",
                                                            "",
                                                            " ed260_i_codigo = ".$oDadosMunicipio->ed261_i_censouf
                                                           );
        $rsResultCensoUf          = $oDaoCensoUf->sql_record($sSqlCensoUf);

        if ($oDaoCensoUf->numrows > 0) {

          $oDadosUf = db_utils::fieldsmemory($rsResultCensoUf, 0);

          $oRetorno->ed260_i_codigo = $oDadosUf->ed260_i_codigo;
          $oRetorno->ed260_c_sigla  = urlencode($oDadosUf->ed260_c_sigla);
          $oRetorno->ed260_c_nome   = urlencode($oDadosUf->ed260_c_nome);

        } else { //Se não encontrar o UF do cartorio
          $oRetorno->iStatus  = 0;
          $oRetorno->sMessage = urlencode("Não foi possível localizar a UF do cartório!");
        }

      } else { //Se não encontrou o municipio do cartorio
        $oRetorno->iStatus  = 0;
        $oRetorno->sMessage = urlencode("Não foi possível localizar o município do cartório!");
      }

    } else { //Se não encontrou nenhum cartório com esta serventia
      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar nenhum cartório com o código: ".
                                      $oParam->iCartorio);
    }

  } else { //Se não existir o parametro iCartorio
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos dados do cartório!");
  }

} elseif ($oParam->exec == "getUFs") {

  $oDaoCensoUf = db_utils::getdao('censouf');

  $sSqlUf               = $oDaoCensoUf->sql_query_file("", "ed260_i_codigo,ed260_c_nome", "ed260_c_nome");
  $rsResultUf           = $oDaoCensoUf->sql_record($sSqlUf);

  $oRetorno->aResultado = db_utils::getCollectionByRecord($rsResultUf, false, false, true);

} elseif ($oParam->exec == "getMatriculaAluno") {

  if (isset($oParam->iAluno)) {

    $oDaoAluno = db_utils::getdao('aluno');

    $sSql      = $oDaoAluno->sql_query("", "ed47_certidaomatricula", "", " ed47_i_codigo = ".$oParam->iAluno);
    $rsResult  = $oDaoAluno->sql_record($sSql);

    if ($oDaoAluno->numrows > 0) {

      $oDadosAluno                      = db_utils::fieldsmemory($rsResult, 0);
      $oRetorno->ed47_certidaomatricula = $oDadosAluno->ed47_certidaomatricula;

    } else { //Se não encontrar o aluno
      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar este aluno!");
    }

  } else { //Se não existir o parametro iAluno
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca da matrícula do aluno!");
  }

} elseif ($oParam->exec == "getDependenciaTurmaAc") {

  if (isset($oParam->iDependencia)) {

    $oDaoTurmaAc = db_utils::getdao('sala');

    $sCampos     = " ed16_i_codigo, ed16_c_descr, ed16_i_capacidade ";
    $sWhere      = " ed16_i_codigo = ".$oParam->iDependencia;
    $sSqlTurmaAc = $oDaoTurmaAc->sql_query("", $sCampos, "", $sWhere);
    $rsTurmaAc   = $oDaoTurmaAc->sql_record($sSqlTurmaAc);

    if ($oDaoTurmaAc->numrows > 0) {

      $oDadosTurmaAc               = db_utils::fieldsmemory($rsTurmaAc, 0);

      $oRetorno->ed16_i_codigo     = $oDadosTurmaAc->ed16_i_codigo;
      $oRetorno->ed16_i_capacidade = $oDadosTurmaAc->ed16_i_capacidade;
      $oRetorno->ed16_c_descr      = urlencode($oDadosTurmaAc->ed16_c_descr);

    } else { //Turma não encontrada
      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar a dependência de código: ".
                                       $oParam->iDependencia);
    }

  } else { //Se não existir o parametro iDependencia
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca da dependência!");
  }

} elseif ($oParam->exec == "getAnexosAtoLegal") {

  if (isset($oParam->iAtoLegal)) {

    $oDaoAnexoAtoLegal    = db_utils::getdao('edu_anexoatolegal');

    $sWhere               = " ed292_atolegal = ".$oParam->iAtoLegal;
    $sOrder               = " ed292_ordem ";
    $sSqlAnexoAtoLegal    = $oDaoAnexoAtoLegal->sql_query("", "*", $sOrder, $sWhere);
    $rsAnexoAtoLegal      = $oDaoAnexoAtoLegal->sql_record($sSqlAnexoAtoLegal);

    $oRetorno->aResultado = db_utils::getCollectionByRecord($rsAnexoAtoLegal, false, false, true);

  } else { //Se não existir o parametro iAtoLegal
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos anexos!");
  }

}  elseif ($oParam->exec == "getDownloadAnexoAtoLegal") {

  if (isset($oParam->iAnexo)) {

    $oDaoAnexoAtoLegal = db_utils::getdao('edu_anexoatolegal');
    $sCampos           = " ed292_sequencial,ed292_nomearquivo,ed292_arquivo ";
    $sWhere            = " ed292_sequencial = ".$oParam->iAnexo;
    $sSqlAnexo         = $oDaoAnexoAtoLegal->sql_query("", $sCampos, "", $sWhere);
    $rsAnexo           = $oDaoAnexoAtoLegal->sql_record($sSqlAnexo);

    if ($oDaoAnexoAtoLegal->numrows > 0) {

      $oResultado   = db_utils::fieldsmemory($rsAnexo, 0);
      $sNomeArquivo = "tmp/".$oResultado->ed292_nomearquivo;

      $lExportAnexo = false;

      db_query("begin");
      $lExportAnexo = pg_lo_export($oResultado->ed292_arquivo, $sNomeArquivo, $conn);
      db_query("commit");

      if ($lExportAnexo) {

        if (file_exists($sNomeArquivo)) {
          $oRetorno->sArquivo = urlencode($sNomeArquivo);
        } else {
          $oRetorno->iStatus  = 0;
          $oRetorno->sMessage = urlencode("Erro ao exportar o anexo da base de dados!");
        }

      } else {
        $oRetorno->iStatus  = 0;
        $oRetorno->sMessage = urlencode("Erro ao exportar o anexo da base de dados!");
      }

    } else {
      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar o arquivo solicitado!");
    }

  } else {
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca do arquivo no sistema!");
  }

} elseif ($oParam->exec == "getDadosCursosTreeViewAtoLegal") {

  if (isset($oParam->iAtoLegal) && isset($oParam->iEscola)) {

  	$iRegistros   = 0;
  	$aCursos      = array();
  	$aBases       = array();
  	$aEtapas      = array();
    $oDaoAtoLegal = db_utils::getdao('atolegal');

    $sSql  = "SELECT ed29_i_codigo,ed29_c_descr FROM cursoedu ";
    $sSql .= "         INNER JOIN cursoescola ON cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo ";
    $sSql .= "         INNER JOIN cursoato ON cursoato.ed215_i_cursoescola = cursoescola.ed71_i_codigo ";
    $sSql .= "         INNER JOIN atolegal ON atolegal.ed05_i_codigo = cursoato.ed215_i_atolegal ";
    $sSql .= "   WHERE cursoato.ed215_i_atolegal = ".$oParam->iAtoLegal;
    $sSql .= "      AND cursoescola.ed71_i_escola = ".$oParam->iEscola;
    $rsSql = $oDaoAtoLegal->sql_record($sSql);
    $iLinhasCurso = $oDaoAtoLegal->numrows;

    if ($oDaoAtoLegal->numrows > 0) {

      /* Percorro todos os cursos vinculados a este ato legal. */
      for ($iCont1 = 0; $iCont1 < $iLinhasCurso; $iCont1++) {

      	$oDaoAtoLegalBase = db_utils::getdao('atolegal');
      	$oDadosCurso      = db_utils::fieldsmemory($rsSql, $iCont1);

      	if (!isset($aCursos[$oDadosCurso->ed29_i_codigo])) {

      	  $aTemp                                = array();
      	  $aTemp['descricao']                   = urlencode($oDadosCurso->ed29_c_descr);
      	  $aTemp['codigo']                      = 'C_'.$oDadosCurso->ed29_i_codigo;
      	  $aCursos[$oDadosCurso->ed29_i_codigo] = $aTemp;

      	}

      	$sSqlBase  = "SELECT ed31_i_codigo,ed31_c_descr FROM base ";
      	$sSqlBase .= "         INNER JOIN escolabase ON escolabase.ed77_i_base = base.ed31_i_codigo ";
      	$sSqlBase .= "         INNER JOIN baseato on baseato.ed278_i_escolabase = escolabase.ed77_i_codigo ";
      	$sSqlBase .= "   WHERE baseato.ed278_i_atolegal = ".$oParam->iAtoLegal;
      	$sSqlBase .= "         AND base.ed31_i_curso = ".$oDadosCurso->ed29_i_codigo;
      	$sSqlBase .= "         AND escolabase.ed77_i_escola = ".$oParam->iEscola;
      	$rsBase    = $oDaoAtoLegalBase->sql_record($sSqlBase);

      	/* Percorro todas as bases vinculadas a este ato legal */
      	if ($oDaoAtoLegalBase->numrows > 0) {

      	  for ($iCont2 = 0; $iCont2 < $oDaoAtoLegalBase->numrows; $iCont2++) {

      	  	$oDaoAtoLegalEtapas = db_utils::getdao('atolegal');
      	  	$oDadosBase         = db_utils::fieldsmemory($rsBase, $iCont2);

      	  	if (!isset($aBases['B_'.$oDadosBase->ed31_i_codigo])) {

      	  	  $aTemp                                   = array();
      	  	  $aTemp['descricao']                      = urlencode($oDadosBase->ed31_c_descr);
      	  	  $aTemp['codigo']                         = 'B_'.$oDadosBase->ed31_i_codigo;
      	  	  $aTemp['node_pai']                       = 'C_'.$oDadosCurso->ed29_i_codigo;
      	  	  $aBases['B_'.$oDadosBase->ed31_i_codigo] = $aTemp;

      	  	}

      	  	/* Busca a Etapa Inicial e Final */
      	  	$sSqlInFim   = "SELECT inicio.ed11_i_sequencia AS first, fim.ed11_i_sequencia AS last ";
      	  	$sSqlInFim  .= "   FROM baseserie ";
      	  	$sSqlInFim  .= "      INNER JOIN serie AS inicio ON inicio.ed11_i_codigo = baseserie.ed87_i_serieinicial ";
      	  	$sSqlInFim  .= "      INNER JOIN serie AS fim ON fim.ed11_i_codigo = baseserie.ed87_i_seriefinal ";
      	  	$sSqlInFim  .= "   WHERE ed87_i_codigo = ".$oDadosBase->ed31_i_codigo;
      	  	$rsInFim     = $oDaoAtoLegalEtapas->sql_record($sSqlInFim);
      	  	$oDadosTemp  = db_utils::fieldsmemory($rsInFim, 0);
      	  	$iInicio     = $oDadosTemp->first;
      	  	$iFinal      = $oDadosTemp->last;

      	  	$sSqlEtapas  = "SELECT ed11_i_codigo, ed11_c_descr, ed31_i_codigo FROM serie ";
      	  	$sSqlEtapas .= "        INNER JOIN ensino ON ensino.ed10_i_codigo = serie.ed11_i_ensino ";
      	  	$sSqlEtapas .= "        INNER JOIN cursoedu ON cursoedu.ed29_i_ensino = ensino.ed10_i_codigo ";
      	  	$sSqlEtapas .= "        INNER JOIN base ON base.ed31_i_curso = cursoedu.ed29_i_codigo ";
      	  	$sSqlEtapas .= "  WHERE ed31_i_codigo = ".$oDadosBase->ed31_i_codigo;
      	  	$sSqlEtapas .= "      AND ed11_i_sequencia >= $iInicio ";
      	  	$sSqlEtapas .= "      AND ed11_i_sequencia <= $iFinal ";
      	  	$sSqlEtapas .= "  ORDER BY ed11_c_descr ASC ";
      	  	$rsEtapas    = $oDaoAtoLegalEtapas->sql_record($sSqlEtapas);
      	  	$iLinhas3    = $oDaoAtoLegalEtapas->numrows;

      	  	if ($iLinhas3 > 0) {

      	  	  for ($iCont3 = 0; $iCont3 < $iLinhas3; $iCont3++) {

      	  	  	$oDadosEtapa = db_utils::fieldsmemory($rsEtapas, $iCont3);

      	  	  	if (!isset($aEtapas['E_'.$oDadosEtapa->ed11_i_codigo])) {

      	  	  	  $aTemp              = array();
      	  	  	  $aTemp['descricao'] = urlencode($oDadosEtapa->ed11_c_descr);
      	  	  	  $aTemp['codigo']    = 'E_'.$oDadosEtapa->ed11_i_codigo;
      	  	  	  $aTemp['node_pai']  = 'B_'.$oDadosBase->ed31_i_codigo;
      	  	  	  $aEtapas['E_'.$oDadosEtapa->ed11_i_codigo] = $aTemp;

      	  	  	}

      	  	  } //End FOR etapas

      	  	} //End IF etapas

      	  } //End FOR bases

      	} //End IF bases

      } //End FOR cursos

      sort($aCursos);
      sort($aBases);
      sort($aEtapas);

      $oRetorno->iRegistros   = 1;
  	  $oRetorno->aCursos      = $aCursos;
  	  $oRetorno->aBases       = $aBases;
  	  $oRetorno->aEtapas      = $aEtapas;

    } else { //Não encontrou resultados
      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi localizado nenhum curso para este ato legal.");
    }

  } else { //Não encontrou o parametro iAtoLegal
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos dados!");
  }

} elseif ($oParam->exec == "getRecHumanoAtoLegal") {

  if (isset($oParam->iAtoLegal)) {

    $oDaoRecHumano = db_utils::getdao('rechumano');

    $sCamposRH     = " * ";
    $sWhereRH      = " ed05_i_codigo = ".$oParam->iAtoLegal;
    $sSqlRHAto     = $oDaoRecHumano->sql_query_atolegal("", $sCamposRH, "", $sWhereRH);
    $rsRHAto       = $oDaoRecHumano->sql_record($sSqlRHAto);

    if ($oDaoRecHumano->numrows > 0) {

      $aAtividades = array();
      $aRH         = array();

      for ($iCont = 0; $iCont < $oDaoRecHumano->numrows; $iCont++) {

        $oResultado = db_utils::fieldsmemory($rsRHAto, $iCont);

        if (!isset($aAtividades[$oResultado->ed01_i_codigo])) {
          $aAtividades[$oResultado->ed01_i_codigo] = array(
                                                            "codigo"    => $oResultado->ed01_i_codigo,
                                                            "descricao" => urlencode($oResultado->ed01_c_descr)
                                                          );
        }

        if (!isset($aRH[$oResultado->z01_numcgm])) {
          $aRH[$oResultado->z01_numcgm] = array(
                                                 "codigo"    => $oResultado->z01_numcgm,
                                                 "descricao" => $oResultado->z01_nome,
                                                 "node_pai"  => $oResultado->ed01_i_codigo
                                               );
        }

      }

      sort($aAtividades);
      sort($aRH);

      $oRetorno->iAtividades = count($aAtividades);
      $oRetorno->aAtividades = $aAtividades;
      $oRetorno->iRH         = count($aRH);
      $oRetorno->aRH         = $aRH;

    } else {
      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar Recursos Humanos ligados a este ato legal!");
    }

  } else {
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos recursos humanos!");
  }

} elseif ($oParam->exec == 'PesquisaCalendario') {

  if (isset($oParam->escola)) {
    $iEscola = $oParam->escola;
  }

  $oDaoCalendario     = db_utils::getdao('calendario');
  $sSqlCalendario     = $oDaoCalendario->sql_query_calendariorelatorio("",
                                                                       "ed52_i_codigo,ed52_c_descr,ed52_i_ano",
                                                                       "ed52_i_ano desc",
                                                                       "ed38_i_escola = {$iEscola}
                                                                        AND ed52_c_passivo = 'N'"
                                                                      );
  $rsResultCalendario = $oDaoCalendario->sql_record($sSqlCalendario);

  if ($oDaoCalendario->numrows > 0) {

    $oRetorno->iEscola  = $iEscola;
    $oRetorno->aResult  = db_utils::getCollectionByRecord($rsResultCalendario, false, false, true);

  } else {
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Não foi possível localizar o Calendário escolhido!");
  }
} elseif ($oParam->exec == 'PesquisaAnosCalendario') {

  if (isset($oParam->escola)) {
    $iEscola = $oParam->escola;
  }

  $sWhere  = ($iEscola > 0) ?  "ed38_i_escola = {$iEscola} AND ed52_c_passivo = 'N'" : "ed52_c_passivo = 'N'";

  $oDaoCalendario     = db_utils::getdao('calendario');
  $sSqlCalendario     = $oDaoCalendario->sql_query_calendariorelatorio("",
                                                                       "distinct ed52_i_ano",
                                                                       "ed52_i_ano desc",
                                                                       $sWhere
                                                                      );
  $rsResultCalendario = $oDaoCalendario->sql_record($sSqlCalendario);

  if ($oDaoCalendario->numrows > 0) {

    $oRetorno->iEscola  = $iEscola;
    $oRetorno->aResult  = db_utils::getCollectionByRecord($rsResultCalendario, false, false, true);

  } else {
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Não foi possível localizar o Calendário escolhido!");
  }
} elseif ($oParam->exec == 'PesquisaCalendarioEncerrado') {

  if (isset($oParam->escola)) {
    $iEscola = $oParam->escola;
  }

  $oDaoCalendario     = db_utils::getdao('serie');
  $sWhereCalendario   = "ed38_i_escola = {$iEscola} AND ed52_c_passivo = 'N'";
  $sWhereCalendario  .= " and EXISTS( select 1 from regencia where ed59_c_encerrada = 'S' and ed59_i_turma = ed57_i_codigo) ";
  $sSqlCalendario     = $oDaoCalendario->sql_query_relatorio("",
                                                             "DISTINCT ed52_i_codigo,ed52_c_descr,ed52_i_ano",
                                                             "ed52_i_ano desc",
                                                             $sWhereCalendario
                                                            );
  $rsResultCalendario = $oDaoCalendario->sql_record($sSqlCalendario);

  if ($oDaoCalendario->numrows > 0) {

    $oRetorno->iEscola  = $iEscola;
    $oRetorno->aResult  = db_utils::getCollectionByRecord($rsResultCalendario, false, false, true);

  } else {
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Não foi possível localizar o Calendário escolhido!");
  }

} elseif ($oParam->exec == 'PesquisaEtapa') {

  if (isset($oParam->escola) && isset($oParam->calendario)) {

  	$oDaoTurma          = db_utils::getdao('turma');
    $sSqlEtapa          = $oDaoTurma->sql_query_relatorio("",
                                                          "DISTINCT ed11_i_codigo,ed11_c_descr,ed11_i_ensino,ed11_i_sequencia",
                                                          "ed11_i_ensino,ed11_i_sequencia",
                                                          "ed57_i_calendario = ".$oParam->calendario.
                                                          " AND ed57_i_escola = ".$oParam->escola
                                                         );
    $rsResultEtapa      = $oDaoTurma->sql_record($sSqlEtapa);

    if ($oDaoTurma->numrows > 0) {

      $oRetorno->aResult1 = db_utils::getCollectionByRecord($rsResultEtapa, false, false, true);

    } else {
      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar as etapas solicitadas!");
    }

  } else {

	  $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca da etapa!");

  }

} elseif ($oParam->exec == 'PesquisaTurma') {

  if (isset($oParam->escola) && isset($oParam->calendario)) {

  	$oDaoTurma          = db_utils::getdao('turma');
    $sSqlTurma          = $oDaoTurma->sql_query_relatorio("",
                                                          "DISTINCT ed220_i_codigo,ed57_c_descr,ed11_c_descr",
                                                          "ed57_c_descr,ed11_c_descr",
                                                          "ed57_i_calendario = ".$oParam->calendario.
                                                          " AND ed57_i_escola = ".$oParam->escola.
                                                           " AND ed221_c_origem = 'S'"
                                                         );
    $rsResultTurma      = $oDaoTurma->sql_record($sSqlTurma);

    if ($oDaoTurma->numrows > 0) {

      $oRetorno->aResult1 = db_utils::getCollectionByRecord($rsResultTurma, false, false, true);

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar as turmas solicitadas!");

    }

  } else {

	  $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca das turmas!");

  }

} elseif ($oParam->exec == 'getDiretor') {

  if (isset($oParam->escola)) {

  	$oDaoEscolaDiretor  = db_utils::getdao('escoladiretor');
  	$sCampos            = " distinct ed20_i_codigo, ";
  	$sCampos           .= " 'DIRETOR' as funcao, ";
  	$sCampos           .= "          case when ed20_i_tiposervidor = 1 then ";
  	$sCampos           .= "                  cgmrh.z01_nome ";
  	$sCampos           .= "               else cgmcgm.z01_nome ";
  	$sCampos           .= "            end as nome,";
  	$sCampos           .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'D' as tipo";
  	$sWhere             = " ed254_i_escola = ".$oParam->escola." AND ed254_c_tipo = 'A' ";
  	$sWhere            .= " AND ed01_i_funcaoadmin = 2 ";
    $sSqlDiretor        = $oDaoEscolaDiretor->sql_query_resultadofinal("",$sCampos,"",$sWhere);
    $rsDiretor          = $oDaoEscolaDiretor->sql_record($sSqlDiretor);

    if ($oDaoEscolaDiretor->numrows > 0) {
      $oRetorno->aResultDiretor = db_utils::getCollectionByRecord($rsDiretor, false, false, true);
    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar os diretores solicitados!");
    }
  } else {

	  $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos diretores!");
  }

} elseif ($oParam->exec == 'getSecretario') {

  if (isset($oParam->escola)) {

  	$oDaoRechumanoAtiv  = db_utils::getdao('rechumanoativ');
  	$sCamposSec         = " DISTINCT ed01_c_descr as funcao, ";
  	$sCamposSec        .= " ed20_i_codigo, ";
  	$sCamposSec        .= "          case when ed20_i_tiposervidor = 1 then ";
  	$sCamposSec        .= "                  cgmrh.z01_nome ";
  	$sCamposSec        .= "               else cgmcgm.z01_nome ";
  	$sCamposSec        .= "            end as nome,";
  	$sCamposSec        .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'O' as tipo";
  	$sWhereSec          = " ed75_i_escola = ".$oParam->escola." AND ed01_i_funcaoadmin = 3 ";
    $sSqlSec            = $oDaoRechumanoAtiv->sql_query_resultadofinal("",$sCamposSec,"",$sWhereSec);
    $rsSec              = $oDaoRechumanoAtiv->sql_record($sSqlSec);

    if ($oDaoRechumanoAtiv->numrows > 0) {

      $oRetorno->aResultSec = db_utils::getCollectionByRecord($rsSec, false, false, true);

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar os secretários solicitados!");

    }

  } else {

	$oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos secretarios!");

  }

} elseif ($oParam->exec == 'PesquisaTurmaAta') {

  if (isset($oParam->escola) && isset($oParam->calendario)) {

  	$oDaoTurma          = db_utils::getdao('turma');
  	$sCamposAta         = " DISTINCT ed220_i_codigo,ed57_c_descr,ed11_c_descr";
  	$sWhereAta          = " ed57_i_calendario = ".$oParam->calendario." AND ed57_i_escola = ".$oParam->escola;
  	$sWhereAta         .= " AND ed221_c_origem = 'S' AND ed59_c_encerrada = 'S'";
    $sSqlAta            = $oDaoTurma->sql_query_atafinal("",$sCamposAta,"ed57_c_descr,ed11_c_descr",$sWhereAta);
    $rsResultAta        = $oDaoTurma->sql_record($sSqlAta);

    if ($oDaoTurma->numrows > 0) {

      $oRetorno->aResultAta = db_utils::getCollectionByRecord($rsResultAta, false, false, true);

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Calendário sem turmas encerradas!");
    }

  } else {

	$oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca da turma!");

  }

} elseif ($oParam->exec == 'PesquisaEnsino') {

  if (isset($oParam->escola) && isset($oParam->calendario)) {

  	$oDaoTurma      = db_utils::getdao('turma');
  	$sCamposEnsino  = " distinct ed10_i_codigo, ed10_c_descr";
  	$sWhereEnsino   = " ed57_i_calendario = ".$oParam->calendario." AND ed57_i_escola = ".$oParam->escola;
    $sSqlEnsino     = $oDaoTurma->sql_query_ensino("",$sCamposEnsino,"ed10_i_codigo",$sWhereEnsino);
    $rsResultEnsino = $oDaoTurma->sql_record($sSqlEnsino);

    if ($oDaoTurma->numrows > 0) {

      $oRetorno->aResultEnsino = db_utils::getCollectionByRecord($rsResultEnsino, false, false, true);

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar as modalidades de ensinos solicitados!");

    }

  } else {

	$oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca das modalidades de ensino!");

  }

} elseif ($oParam->exec == 'PesquisaTipoEnsino') {

  if (isset($oParam->escola)) {

  	$oDaoTipoEnsino      = db_utils::getdao('tipoensino');
    $sSqlTipoEnsino     = $oDaoTipoEnsino->sql_query_file("","ed36_i_codigo,ed36_c_descr,ed36_c_abrev","ed36_i_codigo","");
    $rsResultTipoEnsino = $oDaoTipoEnsino->sql_record($sSqlTipoEnsino);

    if ($oDaoTipoEnsino->numrows > 0) {

      $oRetorno->aResultTipoEnsino = db_utils::getCollectionByRecord($rsResultTipoEnsino, false, false, true);

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar os ensinos solicitados!");

    }

  } else {

	$oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos ensinos!");

  }

} elseif ($oParam->exec == 'PesquisaNivelEnsino') {

  if (isset($oParam->tipoensino)) {

  	$oDaoEnsino      = db_utils::getdao('ensino');
    $sSqlNivelEnsino     = $oDaoEnsino->sql_query("","distinct ed10_i_codigo,ed10_c_descr","","ed10_i_tipoensino =".$oParam->tipoensino);
    $rsResultNivelEnsino = $oDaoEnsino->sql_record($sSqlNivelEnsino);

    if ($oDaoEnsino->numrows > 0) {

      $oRetorno->aResultNivelEnsino = db_utils::getCollectionByRecord($rsResultNivelEnsino, false, false, true);

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar os níveis de ensino solicitados!");

    }

  } else {

	$oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos níveis de ensino!");

  }

} elseif ($oParam->exec == 'PesquisaAlunoHist') {

  try {

    if ( empty($oParam->escola) ) {
      throw new ParameterException("Escola não informada.");
    }

    if ( empty($oParam->tipoaluno) ) {
      throw new ParameterException("Tipo de aluno não informado.");
    }

    if ($oParam->tipoaluno == "3") {

      $oDaoTransfEscolaRede = new cl_transfescolarede;
      $sCampos = "distinct ed47_i_codigo,to_ascii(ed47_v_nome) as ed47_v_nome,ed60_i_codigo,escola.ed18_i_codigo as escola";
      $sWhere  = "ed60_c_situacao='TRANSFERIDO REDE' and  ed103_i_escolaorigem = ".$oParam->escola;

      if ( !empty($oParam->iAno) ) {
        $sWhere .= " AND ed52_i_ano = {$oParam->iAno} ";
      }

      $sSql    = $oDaoTransfEscolaRede->sql_query_historico( "", $sCampos, "to_ascii(ed47_v_nome)", $sWhere);
      $rs      = db_query($sSql);
    }

    if ($oParam->tipoaluno == "1" || $oParam->tipoaluno == "2") {

      $sCondicao = " ( ed62_i_escola = {$oParam->escola} OR ed56_i_escola = {$oParam->escola} )" ;

      if ($oParam->tipoaluno == "2") {
        $sCondicao = " ed56_i_escola is null ";
      }

      if ( !empty($oParam->iAno) ) {


        $sCondicao .= "  AND ";
        $sCondicao .= "    (SELECT ed62_i_anoref AS ano ";
        $sCondicao .= "     FROM historico ";
        $sCondicao .= "     LEFT JOIN historicomps ON historicomps.ed62_i_historico = historico.ed61_i_codigo ";
        $sCondicao .= "     LEFT JOIN historicompsfora ON historicompsfora.ed99_i_historico = ed61_i_codigo ";
        $sCondicao .= "     WHERE ed61_i_aluno = ed47_i_codigo ";
        $sCondicao .= "       AND ed62_i_anoref IS NOT NULL ";
        $sCondicao .= "     UNION SELECT ed99_i_anoref AS ano ";
        $sCondicao .= "     FROM historico ";
        $sCondicao .= "     LEFT JOIN historicomps ON historicomps.ed62_i_historico = historico.ed61_i_codigo ";
        $sCondicao .= "     LEFT JOIN historicompsfora ON historicompsfora.ed99_i_historico = ed61_i_codigo ";
        $sCondicao .= "     WHERE ed61_i_aluno = ed47_i_codigo ";
        $sCondicao .= "       AND ed99_i_anoref IS NOT NULL ";
        $sCondicao .= "     ORDER BY 1 DESC LIMIT 1) = {$oParam->iAno} ";
      }

      $oDaoHistorico = new cl_historico;
      $sCamposHist   = " DISTINCT ed47_i_codigo,to_ascii(ed47_v_nome) as ed47_v_nome, ed56_i_escola as escola ";
      $sSql          =  $oDaoHistorico->sql_query_aluno("",$sCamposHist,
                                                      "to_ascii(ed47_v_nome)", $sCondicao
                                                     );
      $rs            =  db_query($sSql);
    }

    if ( !$rs ) {
      throw new DBException("Erro ao buscar os alunos.");
    }

    $iLinhas = pg_num_rows($rs);
    if ( $iLinhas == 0 ) {
      throw new BusinessException("Não foi possível localizar os alunos  solicitados!");
    }

    $oRetorno->aResultHistorico = db_utils::getCollectionByRecord($rs, false, false, true);
    $oRetorno->iEscola          = db_utils::fieldsmemory($rs,0)->escola;

  } catch (Exception $oErro) {

    $oRetorno->iStatus  = 2;
    $oRetorno->sMessage = urlencode($oErro->getMessage());
  }

} elseif ($oParam->exec == 'PesquisaAlunoCert') {

      try {

        if ( empty($oParam->escola)) {
          throw new ParameterException("Escola não informada.");
        }

        if ( empty($oParam->tipoaluno)) {
          throw new ParameterException("Tipo de Aluno não informado.");
        }

        $sWhereCurso = '';
        if ( !empty($oParam->iCurso) ) {
          $sWhereCurso = " AND ed29_i_codigo = {$oParam->iCurso} ";
        }

        $sWhereAno = " AND ed61_i_anoconc > 0 ";
        if ( !empty($oParam->iAno) ) {
          $sWhereAno = " AND ed61_i_anoconc = {$oParam->iAno} ";
        }

        $oDaoHistorico    = new cl_historico;
        $sSqlAlunos       = "sql_query_relatorio";
        $sCondicao        = " ed61_i_escola = $oParam->escola {$sWhereAno} {$sWhereCurso}";
        $sCamposHistorico = " DISTINCT ed47_i_codigo,to_ascii(ed47_v_nome) as ed47_v_nome, ed61_i_escola as escola ";

        if ( $oParam->tipoaluno == "1" ) {

          $sSqlAlunos = "sql_query_alunos_vinculados_escola";
          $sCondicao .= " AND ed56_c_situacao = 'CONCLUÍDO' ";
        }

        $sSqlCert = $oDaoHistorico->$sSqlAlunos( "", $sCamposHistorico, "to_ascii(ed47_v_nome)", $sCondicao );
        $rsCert   =  db_query($sSqlCert);

        if ( !$rsCert ) {
          throw new DBException("Erro ao buscar os alunos.");
        }

        if ( pg_num_rows($rsCert) == 0 ) {
          throw new BusinessException("Não foi possível localizar os alunos solicitados!");
        }

        $oRetorno->aResultCert = db_utils::getCollectionByRecord($rsCert, false, false, true);
        $oRetorno->iEscola     = db_utils::fieldsmemory($rsCert,0)->escola;

    } catch (Exception $oErro) {

      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode($oErro->getMessage());
    }

} elseif ($oParam->exec == "getAreasConhecimento") {

  $oDaoAreasConhecimento = db_utils::getdao('areaconhecimento');

  $sCampos               = " ed293_sequencial, ed293_descr, ";
  $sCampos              .= " case when ed293_ativo = '1' then 'SIM' ";
  $sCampos              .= "      when ed293_ativo = '2' then 'NÃO' ";
  $sCampos              .= "   end as ed293_ativo ";
  $sOrderBy              = " ed293_sequencial ASC ";
  $sWhere                = "";
  $sSqlAreasConhecimento = $oDaoAreasConhecimento->sql_query("", $sCampos, $sOrderBy, $sWhere);
  $rsAreasConhecimento   = $oDaoAreasConhecimento->sql_record($sSqlAreasConhecimento);

  if ($oDaoAreasConhecimento->erro_status == 0) {

    $oRetorno->aResultado = db_utils::getCollectionByRecord($rsAreasConhecimento, false, false, true);
    $oRetorno->iResultado = $oDaoAreasConhecimento->numrows;

  } else {
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro na busca por Áreas de Conhecimento!");
  }

} elseif ($oParam->exec == "getDisciplinas") {

  if (isset($oParam->iCodigo)) {

    $oDaoCadDisciplinas = db_utils::getdao('censocaddisciplina');

    $sWhereDisciplinas  = " ed294_caddisciplina = ".$oParam->iCodigo;
    $sSqlDisciplinas    = $oDaoCadDisciplinas->sql_query("", "*", "", $sWhereDisciplinas);
    $rsDisciplinas      = $oDaoCadDisciplinas->sql_record($sSqlDisciplinas);

    if ($oDaoCadDisciplinas->numrows > 0) {

      $oRetorno->iTotalRegistros = $oDaoCadDisciplinas->numrows;
      $oRetorno->aResultado      = db_utils::getCollectionByRecord($rsDisciplinas, false, false, true);

    } else {
      $oRetorno->iTotalRegistros = 0;
      $oRetorno->sMensagem       = urlencode("Nenhuma disciplina encontrada!");
    }

  } else {
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao buscar as disciplinas!");
  }

} elseif ($oParam->exec == "getEtapas") {

  if (isset($oParam->iCurso) && isset($oParam->iEscola)) {

    $oDaoSerie    = db_utils::getdao('serie');

    $sCamposSerie = " distinct ed11_i_codigo,ed11_c_descr,ed11_c_abrev,ed11_i_ensino,ed29_c_descr,ed10_i_codigo,ed11_i_sequencia";

    $sWhereSerie = ' 1=1';
    $sWhere      = '';
    $sInner      = '';

    if ($oParam->iCurso != 0) {
      $sWhereSerie  .= " and ed11_i_ensino IN (".$oParam->iCurso.") ";
    }

    if (isset($oParam->lTurmasEncerradas) && $oParam->lTurmasEncerradas) {

      $sInner      .= " INNER JOIN serieregimemat      on ed223_i_serie          = ed11_i_codigo";
      $sInner      .= " INNER JOIN turmaserieregimemat on ed220_i_serieregimemat = ed223_i_codigo";
      $sInner      .= " INNER JOIN turma as turma1     on ed57_i_codigo          = ed220_i_turma";
      $sInner      .= " INNER JOIN regencia            on ed59_i_turma           = ed57_i_codigo";

      $sWhereSerie .= " and exists (select 1 from regencia as regencia1";
      $sWhereSerie .= "                     where regencia1.ed59_i_turma = turma1.ed57_i_codigo";
      $sWhereSerie .= "                       and regencia1.ed59_c_encerrada = 'S')";
    }

    if (isset($oParam->iEtapas)) {
      $sWhereSerie .= " AND ed11_i_codigo NOT IN (".$oParam->iEtapas.") ";
    }

    if ($iModulo == 110770747 || $oParam->iEscola != 0) {

      $sInner .= " INNER JOIN cursoescola ON cursoescola.ed71_i_curso = cursoedu.ed29_i_codigo ";
      $sWhere  = " AND ed71_i_escola = ".$oParam->iEscola;

    }

    $sSqlSerie    = " SELECT ".$sCamposSerie;
    $sSqlSerie   .= "    FROM serie ";
    $sSqlSerie   .= "         INNER JOIN ensino      ON ensino.ed10_i_codigo = ed11_i_ensino ";
    $sSqlSerie   .= "         INNER JOIN cursoedu    ON cursoedu.ed29_i_ensino = ensino.ed10_i_codigo ".$sInner;
    $sSqlSerie   .= "    WHERE ".$sWhereSerie.''.$sWhere;
    $sSqlSerie   .= " ORDER BY ed10_i_codigo,ed11_i_sequencia ";
    $rsSerie      = $oDaoSerie->sql_record($sSqlSerie);

    if ($oDaoSerie->numrows > 0) {

      $oRetorno->iTotalRegistros = $oDaoSerie->numrows;
      $oRetorno->aResultado      = db_utils::getCollectionByRecord($rsSerie, false, false, true);

    } else {
      $oRetorno->iTotalRegistros = 0;
      $oRetorno->sMessage        = urlencode("Nenhuma etapa encontrada para este curso!");
    }

  } else {
    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao buscar as etapas!");
  }
} elseif ($oParam->exec == "getOrientacao") {

  if (isset($oParam->escola)) {

    $oDaoEduRelatModel  = db_utils::getdao('edu_relatmodel');
    $sCampos            = "ed217_orientacao,ed217_i_codigo, case when ed217_orientacao = 2 then 'Retrato' end as nome";
    $sWhereOrientacao   = "ed217_i_codigo = ". $oParam->iRelatorio." AND ed217_orientacao = 2";
    $sSqlSec            = $oDaoEduRelatModel->sql_query("",$sCampos,"",$sWhereOrientacao);
    $rsSec              = $oDaoEduRelatModel->sql_record($sSqlSec);

    if ($oDaoEduRelatModel->numrows > 0) {

      $oRetorno->aResultOrientacao = db_utils::getCollectionByRecord($rsSec, false, false, true);

    } else {

      $oRetorno->iStatus  = 0;
      //$oRetorno->sMessage = urlencode("Orientação do modelo  paisagem!");

    }

  } else {

    $oRetorno->iStatus  = 0;
    $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos relatorios!");

  }

} elseif ($oParam->exec == "getAssinatura") {

    if (isset($oParam->escola)) {

      $oDaoEscolaDiretor  = db_utils::getdao('escoladiretor');
      $sCamposDiretor     = " 'DIRETOR' as funcao, ";
      $sCamposDiretor    .= "         case when ed20_i_tiposervidor = 1 then ";
      $sCamposDiretor    .= "                 cgmrh.z01_nome ";
      $sCamposDiretor    .= "              else cgmcgm.z01_nome ";
      $sCamposDiretor    .= "         end as nome, ";
      $sCamposDiretor    .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'D' as tipo ";
      $sWhereDiretor      = " ed254_i_escola = ".$oParam->escola." AND ed254_c_tipo = 'A' AND ed01_i_funcaoadmin = 2 ";
      $sSqlDiretor        = $oDaoEscolaDiretor->sql_query_resultadofinal("", $sCamposDiretor, "", $sWhereDiretor);

      $oDaoRechumanoAtiv  = db_utils::getdao('rechumanoativ');
      $sCamposSec         = " DISTINCT ed01_c_descr as funcao, ";
      $sCamposSec        .= "         case when ed20_i_tiposervidor = 1 then ";
      $sCamposSec        .= "                 cgmrh.z01_nome ";
      $sCamposSec        .= "              else cgmcgm.z01_nome ";
      $sCamposSec        .= "         end as nome,";
      $sCamposSec        .= " ed83_c_descr||' n°: '||ed05_c_numero::varchar as descricao,'O' as tipo ";
      $sWhereSec          = " ed75_i_escola = ".$oParam->escola." AND ed01_i_funcaoadmin = 3 ";
      $sSqlSec            = $oDaoRechumanoAtiv->sql_query_resultadofinal("", $sCamposSec, "", $sWhereSec);

      $sSqlUnion          = $sSqlDiretor;
      $sSqlUnion         .= " UNION ";
      $sSqlUnion         .= $sSqlSec;

      $rsAssinatura       = $oDaoEscolaDiretor->sql_record($sSqlUnion);
      $iLinhas            = $oDaoEscolaDiretor->numrows;


      if ($iLinhas > 0) {

        $oRetorno->aResultAssinatura = db_utils::getCollectionByRecord($rsAssinatura, false, false, true);

      } else {

        $oRetorno->iStatus  = 0;
        $oRetorno->sMessage = urlencode("Não foi possível localizar Diretores/Secretários Para a Escola Solicitada!");

      }

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos Diretores/Secretários!");

    }


  } elseif ($oParam->exec == "getTipoHistorico") {

    if (isset($oParam->escola)) {

      $oDaoEduRelatModel = db_utils::getdao('edu_relatmodel');
      $sCampos           = "ed217_i_codigo, ed217_c_nome";
      $sSqlRelatModel    = $oDaoEduRelatModel->sql_query("", $sCampos ,"ed217_c_nome", "ed217_i_relatorio = 1");
      $rsRelatModel      = $oDaoEduRelatModel->sql_record($sSqlRelatModel);

      if ($oDaoEduRelatModel->numrows > 0) {

        $oRetorno->aResultTipoHistorico = db_utils::getCollectionByRecord($rsRelatModel, false, false, true);

      } else {

        $oRetorno->iStatus  = 0;
        $oRetorno->sMessage = urlencode("Nenhum modelo cadastrado!");

      }

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos modelos cadastrados!");


    }

  } elseif ($oParam->exec == "getTipoCertificado") {

    if (isset($oParam->escola)) {

      $oDaoRelatModel = db_utils::getdao('edu_relatmodel');
      $sCampos        = "ed217_i_codigo,ed217_c_nome";
      $sSqlModelo     = $oDaoRelatModel->sql_query("",$sCampos,"ed217_c_nome","ed217_i_relatorio = 2");
      $rsModelo       = $oDaoRelatModel->sql_record($sSqlModelo);

      if ($oDaoRelatModel->numrows > 0) {

        $oRetorno->aResultTipoCertificado = db_utils::getCollectionByRecord($rsModelo, false, false, true);

      } else {

        $oRetorno->iStatus  = 0;
        $oRetorno->sMessage = urlencode("Nenhum modelo cadastrado!");

      }

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos modelos cadastrados!");


    }
  } elseif ($oParam->exec == "getEscola") {

     $sFiltraModulo        = false;
     if (isset($oParam->filtraModulo)) {
       $sFiltraModulo        = $oParam->filtraModulo;
     }
     $sWhere = '';
     if ($sFiltraModulo && db_getsession("DB_modulo") == $iModuloEscola) {
       $sWhere = " ed18_i_codigo = ".db_getsession("DB_coddepto");
     }
     $oDaoEscola           = db_utils::getdao('escola');
     $sCamposEscola        = "ed18_i_codigo as codigo_escola,ed18_c_nome as nome_escola";
     $sSqlEscola           = $oDaoEscola->sql_query_file("", $sCamposEscola, "ed18_c_nome", $sWhere);
     $rsResultEscola       = $oDaoEscola->sql_record($sSqlEscola);

     $oRetorno->itens = db_utils::getCollectionByRecord($rsResultEscola, false, false, true);

  } elseif ($oParam->exec == 'PesquisaCurso') {

	  if (isset($oParam->iCodigoEscola)) {

      $oDaoEnsino          = db_utils::getdao('ensino');
      $sCampos             = "distinct ed10_i_codigo as codigo_curso,ed29_c_descr as nome_curso";
      $sWhere              = ($oParam->iCodigoEscola > 0) ? "ed71_i_escola = {$oParam->iCodigoEscola}" : '';
      $sSqlCursoEscola     = $oDaoEnsino->sql_query_curso("",$sCampos,'ed29_c_descr',$sWhere);
	    $rsResultCursoEscola = $oDaoEnsino->sql_record($sSqlCursoEscola);

      if ($oDaoEnsino->numrows > 0) {

        $oRetorno->aResultCursoEscola  = db_utils::getCollectionByRecord($rsResultCursoEscola, false, false, true);

      } else {

        $oRetorno->iStatus  = 0;
        $oRetorno->sMessage = urlencode("Não foi possível localizar o Curso escolhido!");

      }

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Erro ao realizar a busca dos cursos!");

    }

  }else if ($oParam->exec == 'getTurmas') {

    $oDaoTurma   = db_utils::getdao('serie');
    $sWhereTurma = "ed221_c_origem = 'S'";
    if (isset($oParam->iCurso) && trim($oParam->iCurso) != "") {
      $sWhereTurma .= " and ed11_i_ensino= {$oParam->iCurso}";
    }
    if (isset($oParam->iCalendario) && trim($oParam->iCalendario) != "") {
      $sWhereTurma .= " and ed52_i_codigo= {$oParam->iCalendario}";
    }
		if (isset($oParam->iEscola) && trim($oParam->iEscola) != "") {
     $sWhereTurma  .= " and ed57_i_escola= {$oParam->iEscola}";
		}
		if (isset($oParam->lEncerrada) && trim($oParam->lEncerrada) == "true") {

		  $sWhereTurma  .= " and EXISTS( select 1 from regencia where ed59_c_encerrada = 'S' and ed59_i_turma = ed57_i_codigo) ";
		}
    $sCampos =  "distinct ed57_i_codigo as codigo_turma, ed57_c_descr as nome_turma";
    $sOrder  =  "ed57_c_descr";
    if (isset($oParam->lListarTurmasComEtapa) && $oParam->lListarTurmasComEtapa == 'true') {

      $sCampos .= ", ed11_i_codigo as codigo_etapa, ed11_c_descr as nome_etapa";
      $sOrder  .= ", ed11_c_descr";
    }
    $sSqlTurma = $oDaoTurma->sql_query_relatorio("",
                                                 $sCampos,
                                                 $sOrder,
                                                 $sWhereTurma
                                                );
    $rsResultTurma     = $oDaoTurma->sql_record($sSqlTurma);
    $oRetorno->aTurmas = db_utils::getCollectionByRecord($rsResultTurma,false,false,true);

  } else if ($oParam->exec == 'getDisciplinasTurma') {

    $sWhere = "";
    if (isset($oParam->iTurma) && $oParam->iTurma != "") {
      $sWhere .= "ed59_i_turma = {$oParam->iTurma}";
    }

    $oDaoRegencia = db_utils::getdao('regencia');
    $sSqlRegencia = $oDaoRegencia->sql_query("",
                                                 "distinct
                                                  ed232_i_codigo as codigo_disciplina,
                                                  ed232_c_descr  as nome_disciplina",
                                                 "ed232_c_descr",
                                                 $sWhere );

    $rsDisciplinasTurma     = $oDaoRegencia->sql_record($sSqlRegencia);
    $oRetorno->aDisciplinas = db_utils::getCollectionByRecord($rsDisciplinasTurma, false, false, true);
  } else if ($oParam->exec == 'getPeriodosAvaliacaoEscola') {

    $sWhere = '';
    if (isset($oParam->iCalendario) && $oParam->iCalendario != "") {
      $sWhere = "ed53_i_calendario = {$oParam->iCalendario}";
    }

    $oDaoPeriodoCalendario = db_utils::getDao("periodocalendario");
    $sCampos               = " ed09_i_codigo as codigo_periodo, ed09_c_descr as descricao_periodo";
    $sSqlPeriodos          = $oDaoPeriodoCalendario->sql_query(null, $sCampos, "ed09_i_sequencia", $sWhere);
    $rsPeriodos            = $oDaoPeriodoCalendario->sql_record($sSqlPeriodos);
    $oRetorno->aPeriodos   = db_utils::getCollectionByRecord($rsPeriodos, false, false, true);

  } else if ($oParam->exec == 'PesquisaCalendarioAnoAgrupado') {

    $oDaoCalendario     = db_utils::getdao('calendario');
    $sWhere             = " ed52_c_passivo = 'N' ";
    if (!empty($oParam->escola)) {
      $sWhere .= " and ed38_i_escola = {$oParam->escola}";
    }
    $sSqlCalendario = $oDaoCalendario->sql_query_calendariorelatorio("",
                                                                     "distinct ed52_i_ano",
                                                                     "ed52_i_ano desc",
                                                                      $sWhere
                                                                    );
    $rsResultCalendario = $oDaoCalendario->sql_record($sSqlCalendario);

    if ($oDaoCalendario->numrows > 0) {

      $oRetorno->iEscola  = $oParam->escola;
      $oRetorno->aResult  = db_utils::getCollectionByRecord($rsResultCalendario, false, false, true);

    } else {
      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Não foi possível localizar o Calendário escolhido!");
    }
  } elseif ($oParam->exec == 'PesquisaEtapaAno') {

    $sWhere = "";

    if (isset($oParam->escola) && isset($oParam->calendario)) {

      $oDaoTurma          = db_utils::getdao('turma');
      $sSqlEtapa          = $oDaoTurma->sql_query_relatorio("",
                                                            "DISTINCT ed11_i_codigo,
                                                             ed11_c_descr,
                                                             ed11_i_ensino,ed11_i_sequencia",
                                                            "ed11_i_ensino,ed11_i_sequencia",
                                                            "ed52_i_ano = ".$oParam->calendario.
                                                            " AND ed57_i_escola = ".$oParam->escola
                                                           );
      $rsResultEtapa      = $oDaoTurma->sql_record($sSqlEtapa);

      if ($oDaoTurma->numrows > 0) {

        $oRetorno->aResult1 = db_utils::getCollectionByRecord($rsResultEtapa, false, false, true);

      } else {
        $oRetorno->iStatus  = 0;
        $oRetorno->sMessage = urlencode("Não foi possível localizar as etapas solicitadas!");
      }

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode("Erro ao realizar a busca da etapa!");

    }

  } elseif ($oParam->exec == 'getPeriodosAvaliacaoPorTurma') {

    $oRetorno->aPeriodos = array();
    if(isset($oParam->iTurma)) {

      $oDaoPeriodoTurma   = db_utils::getDao("turma");
      $sWherePeriodoTurma = "ed57_i_codigo = {$oParam->iTurma}";
      $sSqlPeriodoTurma   = $oDaoPeriodoTurma->sql_query_periodoavaliacao(null,
                                                                          "ed09_i_codigo as codigo_periodo,
                                                                           ed09_c_descr as descricao_periodo",
                                                                          "ed09_i_codigo",
                                                                          $sWherePeriodoTurma
                                                                         );
      $rsPeriodoTurma     = $oDaoPeriodoTurma->sql_record($sSqlPeriodoTurma);
      if($oDaoPeriodoTurma->numrows > 0) {
        $oRetorno->aPeriodos = db_utils::getCollectionByRecord($rsPeriodoTurma, false, false, true);
      }
    }
  } else if ($oParam->exec == 'verificaSituacaoDocumentacao') {

    $oRetorno->iSituacaoDocumentacao = 0;
    $oRetorno->lBloqueiaDocumentacao = true;

    $oDaoAluno    = db_utils::getDao("aluno");
    $sWhereAluno  = "ed47_i_codigo = {$oParam->iAluno}";
    $sCamposAluno = "ed47_situacaodocumentacao, ed47_v_ident, ed47_c_certidaonum, ed47_v_cnh, ed47_v_cpf, ed47_c_passaporte";
    $sSqlAluno    = $oDaoAluno->sql_query_file(null, $sCamposAluno, null, $sWhereAluno);
    $rsAluno      = $oDaoAluno->sql_record($sSqlAluno);

    if ($oDaoAluno->numrows > 0) {

      $oDadosAluno = db_utils::fieldsMemory($rsAluno, 0);
      $oRetorno->iSituacaoDocumentacao = $oDadosAluno->ed47_situacaodocumentacao;

      if (empty($oDadosAluno->ed47_v_ident) &&
          empty($oDadosAluno->ed47_c_certidaonum) &&
          empty($oDadosAluno->ed47_v_cnh) &&
          empty($oDadosAluno->ed47_v_cpf) &&
          empty($oDadosAluno->ed47_c_passaporte)) {
        $oRetorno->lBloqueiaDocumentacao = false;
      }

    } else {

      $oRetorno->iStatus  = 0;
      $oRetorno->sMessage = urlencode($oDaoAluno->erro_msg);
    }
  } else if ($oParam->exec == 'salvaSituacaoDocumentacao') {

    try {

      db_inicio_transacao();

      $oDaoAluno   = db_utils::getDao("aluno");
      $sWhereAluno = "ed47_i_codigo = {$oParam->iAluno}";
      $sSqlAluno   = $oDaoAluno->sql_query_file(null, "*", null, $sWhereAluno);
      $rsAluno     = $oDaoAluno->sql_record($sSqlAluno);

      if ($oDaoAluno->numrows > 0) {

        $oDaoAlteracaoAluno                            = db_utils::getDao("aluno");
        $oDaoAlteracaoAluno->ed47_situacaodocumentacao = $oParam->iSituacaoDocumentacao;
        $oDaoAlteracaoAluno->ed47_i_codigo             = $oParam->iAluno;
        $oDaoAlteracaoAluno->alterar($oParam->iAluno);

        if ($oDaoAluno->erro_status == "0") {
          throw new DBException($oDaoAluno->erro_msg);
        }
      }

      db_fim_transacao();
    } catch (DBException $oErro) {

      db_fim_transacao(true);
      $oRetorno->iStatus  = 2;
      $oRetorno->sMessage = urlencode($oErro->getMessage());
    }
  }
echo $oJson->encode($oRetorno);
?>