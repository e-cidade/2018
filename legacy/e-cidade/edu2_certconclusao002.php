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


require_once("libs/db_stdlibwebseller.php");
require_once("fpdf151/scpdf.php");
require_once("libs/db_utils.php");

$sParamEdu          = eduparametros(db_getsession("DB_coddepto"));
$oDaoEduParametros  = db_utils::getdao("edu_parametros");
$oDaoHistorico      = db_utils::getdao("historico");
$oDaoAluno          = db_utils::getdao("aluno");
$oDaoEscola         = db_utils::getdao("escola");
$oDaoTelefoneEscola = db_utils::getdao("telefoneescola");
$oDaoAprovConselho  = db_utils::getdao("aprovconselho");
$oDaoTrocaSerie     = db_utils::getdao("trocaserie");
$oDaoEduRelatModel  = db_utils::getdao("edu_relatmodel");
$oDaoCursoAto       = db_utils::getdao("cursoato");

/**
 * Função que recebe a data do banco e retorna no padrão brasileiro.
 * 
 * @param date $dData
 * @return date $dData
 */
function getData($dData) { 
  return substr($dData, 8, 2)."/".substr($dData, 5, 2)."/".substr($dData, 0, 4);	
}

/**
 * Função responsável por montar o cabeçalho do Histórico Escolar.
 * Esta função executa automaticamente as funcções montaTopoDisciplinas e montaRodape
 * 
 * @author Thiago A. de Lima - thiago.lima@dbseller.com.br
 * 
 * @param object $oPdf
 * @param object $oDadosRelatModel
 * @param object $oDadosEscola
 * @param object $oDadosAluno
 * @param object $oDadosHist
 * @param integer $iEscola
 * @param integer $iCodigoCurso
 * @param integer $sTelefoneEscola
 * @param integer $iCodigoHist
 * @param string $sCondicaoHistMps
 * @param string $sCondicaoHistMpsFora
 */
function montaCabecalho($oPdf, $oDadosRelatModel, $oDadosEscola, $oDadosAluno, $oDadosHist, $iEscola, 
                        $iCodigoCurso, $sTelefoneEscola, $iCodigoHist, $sCondicaoHistMps, $sCondicaoHistMpsFora) {
  
  $oPdf->SetFillColor(223);
  $oPdf->AddPage('L');
  $oPdf->Image('imagens/brasaohistoricoescolar.jpeg', 10, 10, 25, 25, '');
  $oPdf->SetFont('Arial', 'b', 8);
  $oPdf->SetX(35);
  $oPdf->MultiCell(140, 4, $oDadosRelatModel->ed217_t_cabecalho, 0, "C", 0, 0);
  $oPdf->SetXY(175, 10);
  
  /* Busca o nome da Instituição que está logada */
  $oDaoInstituicao = db_utils::getdao("db_config");
  $sSqlInstituicao = $oDaoInstituicao->sql_query("", " nomeinst ", "", " codigo = ".db_getsession("DB_instit"));
  $rsInstituicao   = $oDaoInstituicao->sql_record($sSqlInstituicao);
  $sInstituicao    = db_utils::fieldsmemory($rsInstituicao, 0)->nomeinst;
  
  /* Busca o Ato Legal do Curso */
  $oDaoCursoAto    = db_utils::getdao("cursoato");
  $sCamposAtoCurso = " ed05_c_finalidade, ed05_c_numero, ed05_d_vigora, ed05_d_publicado ";
  $sWhereAtoCurso  = " ed29_i_codigo IN (".$iCodigoCurso.") AND ed18_i_codigo = ".$iEscola;
  $sWhereAtoCurso .= "   AND ed05_i_aparecehistorico = 1";
  $sSqlAtoLegal    = $oDaoCursoAto->sql_query("", $sCamposAtoCurso, "", $sWhereAtoCurso);
  $rsAtoLegalCurso = $oDaoCursoAto->sql_record($sSqlAtoLegal);
  $iLinhasAtoLegal = $oDaoCursoAto->numrows;
  
  if ($iLinhasAtoLegal > 0) {
  	
  	$sAtoLegal = "";
  	$sSepAto   = "";
  	
  	for ($iContAto = 0; $iContAto < $iLinhasAtoLegal; $iContAto++) {
  	  
  	  $oDadosCursoAto = db_utils::fieldsmemory($rsAtoLegalCurso, $iContAto);
  	  
  	  $sAtoLegal .= $sSepAto.$oDadosCursoAto->ed05_c_finalidade." Nº ".$oDadosCursoAto->ed05_c_numero;
  	  $sAtoLegal .= " Data: ".db_formatar($oDadosCursoAto->ed05_d_vigora, 'd');
  	  $sAtoLegal .= " D.O.: ".db_formatar($oDadosCursoAto->ed05_d_publicado, 'd');
  	  $sSepAto    = " \n ";
  		
  	}
  	
  } else {
  	$sAtoLegal = "";
  }
  
  $sCabecalhoEscola  = $oDadosEscola->nome_escola." \n";
  $sCabecalhoEscola .= "Mantenedora: ".$sInstituicao." \n";
  $sCabecalhoEscola .= "Endereço: ".$oDadosEscola->rua_escola.", ".$oDadosEscola->num_escola." \n";
  $sCabecalhoEscola .= "CEP: ".$oDadosEscola->cep_escola." - ".$oDadosEscola->mun_escola." / ";
  $sCabecalhoEscola .= $oDadosEscola->uf_escola." ".$sTelefoneEscola;
  
  $oPdf->MultiCell(110, 3, $sCabecalhoEscola, 0, "L", 0, 0);
  $oPdf->SetX(175);
  $oPdf->MultiCell(110, 2, "", "", "L", 0, 0);
  $oPdf->SetX(175);
  $oPdf->SetFont('Arial', 'b', 6);
  $oPdf->MultiCell(110, 2, $sAtoLegal, "", "L", 0, 0);
  $oPdf->SetY(36);
  $oPdf->SetFont('Arial', 'b', 7);
  $oPdf->Cell(10, 4, "Nome: ", 0, 0, "L", 0);
  $oPdf->SetFont('Arial', 'b', 9);
  $oPdf->Cell(155, 4, $oDadosAluno->ed47_v_nome, 0, 0, "L", 0);
  $oPdf->SetFont('Arial', 'b', 7);
  
  $aNacionalidade = array(
                           "1" => "BRASILEIRO", 
                           "2" => "ESTRANGEIRO", 
                           "3" => "BRASILEIRO NASCIDO NO EXTERIOR OU NATURALIZADO"
                         );
  
  $oPdf->Cell(20, 4, "Nacionalidade: ", 0, 0, "L", 0);
  $oPdf->Cell(111, 4, $aNacionalidade[$oDadosAluno->ed47_i_nacion], 0, 1, "L", 0);
  $oPdf->Cell(15, 4, "Filho(a) de: ", 0, 0, "L", 0);
  
  if (trim($oDadosAluno->ed47_v_pai) == "" && trim($oDadosAluno->ed47_v_mae) == "") {
    $sFiliacao = "";
  } elseif (trim($oDadosAluno->ed47_v_pai) == "" && trim($oDadosAluno->ed47_v_mae) != "") {
    $sFiliacao = $oDadosAluno->ed47_v_mae;
  } elseif (trim($oDadosAluno->ed47_v_pai) != "" && trim($oDadosAluno->ed47_v_mae) == "") {
    $sFiliacao = $oDadosAluno->ed47_v_pai;
  } elseif (trim($oDadosAluno->ed47_v_pai) !== "" && trim($oDadosAluno->ed47_v_mae) != "") {
    $sFiliacao = "$oDadosAluno->ed47_v_pai e de $oDadosAluno->ed47_v_mae";
  }
  
  $oPdf->Cell(150, 4, $sFiliacao, 0, 0, "L", 0);
  $oPdf->Cell(20, 4, "Nascido(a) em: ", 0, 0, "L", 0);
  $oPdf->Cell(111, 4, db_formatar($oDadosAluno->ed47_d_nasc, 'd')." em ".
              $oDadosAluno->municnat." / ".$oDadosAluno->ufnat, 0, 1, "L", 0);
       
  montaTopoDisciplinas($oPdf);
  montaRodape($oPdf, $oDadosRelatModel, $oDadosAluno, $oDadosHist, $iCodigoHist, 
              $sCondicaoHistMps, $sCondicaoHistMpsFora, $oDadosEscola);
	
}

/**
 * Função para montar a topo das disciplinas, monta as 3 (três) colunas.
 *
 * @param object $oPdf
 */
function montaTopoDisciplinas($oPdf) {
  
  $oPdf->SetY(44);
  $oPdf->SetFont('Arial', 'b', 7);
	
  for ($iCont = 0; $iCont < 3; $iCont++) {
    
  	$oPdf->Cell(36, 4, "Disciplina", 1, 0, "C", 0);
    $oPdf->Cell(8, 4, "Etapa", 1, 0, "C", 0);
    $oPdf->Cell(10, 4, "Ap.", 1, 0, "C", 0);
    $oPdf->Cell(10, 4, "CH", 1, 0, "C", 0);
    $oPdf->Cell(8, 4, "RF", 1, 0, "C", 0);
    $oPdf->Cell(10, 4, "PE", 1, 0, "C", 0);
    $oPdf->Cell(10, 4, "ESC", 1, 0, "C", 0);
    
  }
  
  $oPdf->SetXY(10, 48);
  
  for ($iContMaster = 0; $iContMaster < 18; $iContMaster++) {
  	
    for ($iContLinha = 0; $iContLinha < 3; $iContLinha++) {
  		
  	  $oPdf->Cell(36, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell(8, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell(10, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell(10, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell(8, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell(10, 4, "", "LR", 0, "C", 0);
      $oPdf->Cell(10, 4, "", "LR", ($iContLinha == 2 ? 1 : 0), "C", 0);
  	
    }
  
  }
	
}

/**
 * Função para montar a parte inferior do Histórico Escola
 * 
 * @param PDF $oPdf
 * @param object $oDadosRelatModel
 * @param object $oDadosAluno
 * @param object $oDadosHist
 * @param integer $iCodigoHist
 * @param string $sCondicaoHistMps
 * @param string $sCondicaoHistMpsFora
 * @param object $oDadosEscola
 */
function montaRodape($oPdf, $oDadosRelatModel, $oDadosAluno, $oDadosHist, $iCodigoHist, 
                     $sCondicaoHistMps, $sCondicaoHistMpsFora, $oDadosEscola) {
  
  $sConvencoes  = " Convenções: CH = Carga Horária RF = Resultado Final PL = Período Letivo ";
  $sConvencoes .= " ESC = Escola DL = Dias Letivos Aprov. = Aproveitamento";
  
  if ($_GET['sDiretor'] != "") {
	
    $aDiretor       = explode("-", $_GET['sDiretor']);
    $sNomeDiretor   = $aDiretor[1];
    $sFuncaoDiretor = $aDiretor[0].(trim($aDiretor[2]) != "" ? " ($aDiretor[2])" : "");
  
  } else {
	
    $sNomeDiretor   = "Diretor(a)";
    $sFuncaoDiretor = "";
  
  }

  if ($_GET['sSecretario'] != "") {
	
    $aSecretario       = explode("-", $_GET['sSecretario']);
    $sNomeSecretario   = $aSecretario[1];
    $sFuncaoSecretario = $aSecretario[0].(trim($aSecretario[2]) != "" ? " ($aSecretario[2])" : "");
  
  } else {
	
    $sNomeSecretario   = "Secretário(a)";
    $sFuncaoSecretario = "";
  
  }
	
  $iAlturaSerie = 120;
  $oPdf->SetY(120);
  $oPdf->SetFont('Arial', '', 6);
  $oPdf->Cell(15, 3, "Etapa", 1, 0, "C", 0);
  $oPdf->Cell(15, 3, "Ano", 1, 0, "C", 0);
  $oPdf->Cell(15, 3, "Dias", 1, 0, "C", 0);
  $oPdf->Cell(15, 3, "CH", 1, 0, "C", 0);
  $oPdf->Cell(15, 3, "Resultado", 1, 0, "C", 0);
  $oPdf->Cell(20, 3, "Mínimo", 1, 0, "C", 0);
  $oPdf->Cell(105, 3, "Escola", 1, 0, "C", 0);
  $oPdf->Cell(66, 3, "Cidade", 1, 0, "C", 0);
  $oPdf->Cell(10, 3, "UF", 1, 1, "C", 0);
  
  $sSqlSerie    = " SELECT ed11_i_sequencia,  ed11_c_abrev,  ed62_i_anoref,  ed62_i_qtdch,  ed62_i_diasletivos,  ";
  $sSqlSerie   .= "        ed62_c_resultadofinal,  ed62_i_escola,  ed62_i_turma,  ed260_c_sigla,  ed261_c_nome,  ";
  $sSqlSerie   .= "        ed18_i_codigo, ed18_c_nome,  ed62_c_minimo";
  $sSqlSerie   .= "      FROM historicomps ";
  $sSqlSerie   .= "           inner join serie      ON ed11_i_codigo             = ed62_i_serie ";
  $sSqlSerie   .= "           inner join escola     ON escola.ed18_i_codigo      = historicomps.ed62_i_escola";
  $sSqlSerie   .= "           inner join censouf    ON censouf.ed260_i_codigo    = escola.ed18_i_censouf ";
  $sSqlSerie   .= "           inner join censomunic ON censomunic.ed261_i_codigo = escola.ed18_i_censomunic ";
  $sSqlSerie   .= "      WHERE ed62_i_historico IN ($iCodigoHist) $sCondicaoHistMps";      
  $sSqlSerie   .= " UNION";
  $sSqlSerie   .= " SELECT ed11_i_sequencia,  ed11_c_abrev,  ed99_i_anoref,  ed99_i_qtdch,  ed99_i_diasletivos, ";
  $sSqlSerie   .= "        ed99_c_resultadofinal,  ed99_i_escolaproc,  ed99_c_turma,  ed260_c_sigla,  ed261_c_nome,  ";
  $sSqlSerie   .= "        ed82_i_codigo,  ed82_c_nome,  ed99_c_minimo";
  $sSqlSerie   .= "      FROM historicompsfora ";
  $sSqlSerie   .= "           inner join serie      ON ed11_i_codigo              = ed99_i_serie ";
  $sSqlSerie   .= "           inner join escolaproc ON  ed82_i_codigo             = ed99_i_escolaproc ";
  $sSqlSerie   .= "           left join censouf     ON  censouf.ed260_i_codigo    = escolaproc.ed82_i_censouf ";
  $sSqlSerie   .= "           left join censomunic  ON  censomunic.ed261_i_codigo = escolaproc.ed82_i_censomunic ";
  $sSqlSerie   .= "      WHERE ed99_i_historico IN ($iCodigoHist) $sCondicaoHistMpsFora";      
  $sSqlSerie   .= "      ORDER BY ed62_i_anoref ASC ";
  $rsSerie      = db_query($sSqlSerie); 	         
  $iLinhasSerie = pg_num_rows($rsSerie);
  $iContSerie   = 0;
  
  for ($iContRodape = 0; $iContRodape < $iLinhasSerie; $iContRodape++) {
  	
  	$oDadosSerie = db_utils::fieldsmemory($rsSerie, $iContRodape);
  	
  	$oPdf->SetFont('Arial', '', 6);
  	$oPdf->Cell(15, 3, $oDadosSerie->ed11_c_abrev, "LR", 0, "C", 0);
  	$oPdf->Cell(15, 3, $oDadosSerie->ed62_i_anoref, "LR", 0, "C", 0);
  	$oPdf->Cell(15, 3, $oDadosSerie->ed62_i_diasletivos, "LR", 0, "C", 0);
  	$oPdf->Cell(15, 3, $oDadosSerie->ed62_i_qtdch, "LR", 0, "C", 0);
  	$oPdf->Cell(15, 3, $oDadosSerie->ed62_c_resultadofinal == "A" ? "APR" : "REP", "LR", 0, "C", 0);
  	$oPdf->Cell(20, 3, $oDadosSerie->ed62_c_minimo, "LR", 0, "C", 0);
  	$oPdf->Cell(105, 3, $oDadosSerie->ed18_i_codigo." - ".$oDadosSerie->ed18_c_nome, "LR", 0, "L", 0);
  	$oPdf->Cell(66, 3, $oDadosSerie->ed261_c_nome, "LR", 0, "L", 0);
  	$oPdf->Cell(10, 3, $oDadosSerie->ed260_c_sigla, "LR", 1, "L", 0);
  	$iContSerie++;
  	
  }
  
  $iLimiteObs = 1500 - ($iLinhasSerie * 100);
  $oPdf->SetFont('Arial', 'b', 6);
  $iPosY = $oPdf->GetY();
  $oPdf->Multicell(138, 3, substr($oDadosRelatModel->ed217_t_rodape, 0, $iLimiteObs), "LR", "L", 0, 0);
  
  $sObsConselho      = "";
  $sCamposAprovCons  = " cgm.z01_nome,ed253_i_data,ed232_c_descrcompleta as disc_conselho,ed253_t_obs,";
  $sCamposAprovCons .= " ed47_v_nome,ed11_c_descr as serie_conselho,ed59_i_ordenacao ";
  $sWhereAprovCons   = " ed95_i_aluno = ".$oDadosHist->ed61_i_aluno." AND ed31_i_curso = ".$oDadosHist->ed61_i_curso;
  $oDaoAprovConselho = db_utils::getdao("aprovconselho");
  $sSqlAprovCons     = $oDaoAprovConselho->sql_query("", $sCamposAprovCons, "ed59_i_ordenacao", $sWhereAprovCons);
  $rsAprovConselho   = $oDaoAprovConselho->sql_record($sSqlAprovCons);
  $iLinhasAprovCons  = $oDaoAprovConselho->numrows;
  
  if ($iLinhasAprovCons > 0) {
  	
  	$sSepObs = "";
  	
  	for ($iContObs = 0; $iContObs < $iLinhasAprovCons; $iContObs++) {
  	  
  	  $oDadosAprovConselho = db_utils::fieldsmemory($rsAprovConselho, $iContObs);

  	  $sObsConselho .= $sSepObs."-Disciplina ".$oDadosAprovConselho->disc_conselho." na Etapa ";
  	  $sObsConselho .= $oDadosAprovConselho->serie_conselho." foi aprovado pelo Conselho de Classe. ";
  	  $sObsConselho .= "Justificativa: ".$oDadosAprovConselho->ed253_t_obs;
  	  
  	  $sSepObs       = "\n";
  		
  	}
  	
  }
  
  $sObsProg       = "";
  $sSepProg       = "";
  $sCamposProg    = " serieorig.ed11_c_descr as ed11_c_origem, seriedest.ed11_c_descr as ed11_c_destino, ";
  $sCamposProg   .= " trocaserie.ed101_d_data,trocaserie.ed101_c_tipo ";
  $sWhereProg     = " ed101_i_aluno = ".$oDadosHist->ed61_i_aluno;
  $oDaoTrocaSerie = db_utils::getdao("trocaserie");
  $sSqlProg       = $oDaoTrocaSerie->sql_query_certificado_conclusao("", $sCamposProg, "ed101_d_data", $sWhereProg);
  $rsProg         = $oDaoTrocaSerie->sql_record($sSqlProg);
  $sObsTroca          = "";
  $sSepTroca          = "";
  if ($oDaoTrocaSerie->numrows > 0) {
  	
    for ($iContTrocaSerie = 0; $iContTrocaSerie < $oDaoTrocaSerie->numrows; $iContTrocaSerie++) {
    	
      $oDadosTrocaSerie  = db_utils::fieldsmemory($rsTrocaSerie, $iContTrocaSerie) ;
      $sObsTroca        .= $sSepTroca."-".($oDadosTrocaSerie->ed101_c_tipo == "A" ? 
                                           "AVANÇADO" : "CLASSIFICADO")."(A) DA ETAPA ".
                                           (trim($oDadosTrocaSerie->ed11_c_origem))." PARA ETAPA ".
                                           (trim($oDadosTrocaSerie->ed11_c_destino))." EM ".
                                            substr($oDadosTrocaSerie->ed101_d_data, 8, 2)."/".substr
                                           ($oDadosTrocaSerie->ed101_d_data, 5, 2)."/".substr
                                           ($oDadosTrocaSerie->ed101_d_data, 0, 4);
      $sObsTroca        .= ",  CONFORME LEI FEDERAL N° 9394/96 - ARTIGO 23,  § 1o ,  ";
      $sObsTroca        .= " PARECER CEED N° 740/99 E REGIMENTO ESCOLAR";
      $sSepTroca         = "\n";
      
    }
    
  }
  
  $sObsHist      = "";
  $sSepHist      = "";
  $sCamposObs    = " ed61_t_obs ";
  $sWhereObs     = " ed61_i_aluno IN (".$oDadosAluno->ed47_i_codigo.") ";
  $oDaoHistorico = db_utils::getdao("historico");
  $sSqlObs       = $oDaoHistorico->sql_query("", $sCamposObs, "", $sWhereObs);
  $rsObs         = $oDaoHistorico->sql_record($sSqlObs);
  $iLinhasObs    = $oDaoHistorico->numrows;
  
  if ($iLinhasObs > 0) {
  	
  	for ($iContHist = 0; $iContHist < $iLinhasObs; $iContHist++) {
  	  
  	  $oDadosObs = db_utils::fieldsmemory($rsObs, $iContHist);
  	  $sObsHist .= $sSepHist."".$oDadosObs->ed61_t_obs;
  	  $sSepObs   = "\n";
  		
  	}
  	
  }
  
  $oPdf->SetXY(148, $iPosY);
  
  $oPdf->multicell(138, 3, substr($sConvencoes."\n".($sObsHist != "" ? $sObsHist."\n" : "")
                       .($oDadosRelatModel->ed217_t_obs != "" ? $oDadosRelatModel->ed217_t_obs."\n" : "").
                        ($sObsTroca != "" ? $sObsTroca."\n" : "").($sObsConselho != "" ? $sObsConselho."\n" : ""),
                         0, $iLimiteObs), "LR", "J", 0, 0);
  $oPdf->Rect(10, $iPosY, 138, (170 - $iPosY));
  $oPdf->Rect(148, $iPosY, 138, (170 - $iPosY));
  $oPdf->SetXY(10, 175);
  $oPdf->SetFont('Arial', 'b', 6);
  
  $sTextCell  = $oDadosEscola->mun_escola.", ".date("d",db_getsession("DB_datausu"))." de ";
  $sTextCell .= db_mes(date("m", db_getsession("DB_datausu")), 1)." de ".date("Y",db_getsession("DB_datausu")).".";
  $oPdf->Cell(72, 5, $sTextCell, 0, 0, "L", 0);
  $oPdf->Cell(102, 5, "______________________________________________________________________", 0, 0, "C", 0);
  $oPdf->Cell(102, 5, "______________________________________________________________________", 0, 1, "C", 0);
  $oPdf->Cell(72, 5, "", 0, 0, "L", 0);
  
  $oPdf->Cell(102, 5, $sNomeSecretario." - ".$sFuncaoSecretario, 0, 0, "C", 0);
  $oPdf->Cell(102, 5, $sNomeDiretor." - ".$sFuncaoDiretor, 0, 0, "C", 0);
  $oPdf->Line(10, 48, 286, 48);
	
}

$sCamposTelefone    = " ed26_i_ddd, ed26_i_numero, ed26_i_ramal";
$sSqlTelefoneEscola = $oDaoTelefoneEscola->sql_query("", $sCamposTelefone, "", "ed26_i_escola = $iEscola LIMIT 1");
$rsTelefoneEscola   = $oDaoTelefoneEscola->sql_record($sSqlTelefoneEscola);

if ($oDaoTelefoneEscola->numrows > 0) {
	
  db_fieldsmemory($rsTelefoneEscola, 0);
  $sTelefoneEscola = "- Fone: ($ed26_i_ddd) $ed26_i_numero ".($ed26_i_ramal!=""?" Ramal: $ed26_i_ramal":"");
  
} else {
  $sTelefoneEscola = "";
}

$sCamposEscola  = "ed18_c_nome as nome_escola, j14_nome as rua_escola, ed18_c_cep as cep_escola, ed18_codigoreferencia,";
$sCamposEscola .= "ed18_i_numero as num_escola, ed261_c_nome as mun_escola, ed260_c_sigla as uf_escola";
$sSqlEscola     = $oDaoEscola->sql_query("", $sCamposEscola, "", "ed18_i_codigo = $iEscola");
$rsEscola       = $oDaoEscola->sql_record($sSqlEscola);
$oDadosEscola   = db_utils::fieldsmemory($rsEscola, 0);

/**
 * Valida se a escola possui código referência e o adiciona na frente do nome
 */
if ( $oDadosEscola->ed18_codigoreferencia != null ) {
  $oDadosEscola->nome_escola = "{$oDadosEscola->ed18_codigoreferencia} - {$oDadosEscola->nome_escola}";
}

$sCamposRelatModel = "ed217_t_cabecalho, ed217_t_rodape, ed217_t_obs";
$sSqlEduRelatModel = $oDaoEduRelatModel->sql_query("", $sCamposRelatModel, "", "ed217_i_codigo = $iTipoRelatorio");
$rsEduRelatModel   = $oDaoEduRelatModel->sql_record($sSqlEduRelatModel);

if ($oDaoEduRelatModel->numrows > 0) {
  $oDadosRelatModel = db_utils::fieldsmemory($rsEduRelatModel, 0);
}

$sCamposAluno  = " aluno.*, censoufident.ed260_c_sigla as ufident, censoufnat.ed260_c_sigla as ufnat,  ";
$sCamposAluno .= " censoufcert.ed260_c_sigla as ufcert, censoufend.ed260_c_sigla as ufend,  ";
$sCamposAluno .= " censomunicnat.ed261_c_nome as municnat, censomuniccert.ed261_c_nome as municcert,  ";
$sCamposAluno .= " censomunicend.ed261_c_nome as municend,  censoorgemissrg.ed132_c_descr as orgemissrg ";
$sSqlAluno     = $oDaoAluno->sql_query("", "$sCamposAluno", "ed47_v_nome", " ed47_i_codigo IN ($alunos)");
$rsAluno       = $oDaoAluno->sql_record($sSqlAluno);
$iLinhasAluno  = $oDaoAluno->numrows;

if ($iLinhasAluno == 0) {
	
  echo " <table width='100%'> ";
  echo "   <tr> ";
  echo "     <td align='center'> ";
  echo "       <font color='#FF0000' face='arial'> ";
  echo "         <b>Nenhum histórico para o(s) aluno(s) selecionados<br> ";
  echo "         <input type='button' value='Fechar' onclick='window.close()'></b> ";
  echo "       </font> ";
  echo "     </td> ";
  echo "   </tr> ";
  echo " </table> ";
  exit;
 
}

$oPdf = new FPDF();
$oPdf->Open();
$oPdf->AliasNbPages();

for ($iContPrincipal = 0; $iContPrincipal < $iLinhasAluno; $iContPrincipal++) {
	
  $oDadosAluno      = db_utils::fieldsmemory($rsAluno, $iContPrincipal); 
  
  $sWhereHistorico  = " ed61_i_aluno in (".$oDadosAluno->ed47_i_codigo.") ";
  $sSqlHistorico    = $oDaoHistorico->sql_query("", "*", " ed47_v_nome ", $sWhereHistorico);
  $rsHistorico      = $oDaoHistorico->sql_record($sSqlHistorico);
  $iLinhasHistorico = $oDaoHistorico->numrows;
  
  $iCodigoHist      = "";
  $iCodigoCurso     = "";
  $sSeparador       = "";
  
  for ($iContHist = 0; $iContHist < $iLinhasHistorico; $iContHist++) {
  	
  	$oDadosHist    = db_utils::fieldsmemory($rsHistorico, $iContHist);
  	
  	$iCodigoHist  .= $sSeparador.$oDadosHist->ed61_i_codigo;
  	$iCodigoCurso .= $sSeparador.$oDadosHist->ed61_i_curso; 
  	
  	$sSeparador    = ", ";
  	
  }
  
  $sCamposAno  = "max(ed62_i_anoref) as ultimoanorede,  max(ed99_i_anoref) as ultimoanofora";
  $sSqlAnoHist = $oDaoHistorico->sql_query_historico("",  $sCamposAno,  "",  " ed61_i_codigo in ($iCodigoHist)");
  $rsAnoHist   = $oDaoHistorico->sql_record($sSqlAnoHist);
  $iLinhasAno  = $oDaoHistorico->numrows;
  
  if ($iLinhasAno > 0) {
  	
  	$oDadosAnoHist = db_utils::fieldsmemory($rsAnoHist, 0);
  	
    if (trim($oDadosAnoHist->ultimoanorede) == "" && trim($oDadosAnoHist->ultimoanofora) == "") {
      $iUltimoAno = date("Y",  db_getsession("DB_datausu"));
    } else if (trim($oDadosAnoHist->ultimoanorede) == "" && trim($oDadosAnoHist->ultimoanofora) != "") {
      $iUltimoAno = $oDadosAnoHist->ultimoanofora;
    } else if (trim($oDadosAnoHist->ultimoanorede) != "" && trim($oDadosAnoHist->ultimoanofora) == "") {
      $iUltimoAno = $oDadosAnoHist->ultimoanorede;
    } else if (trim($oDadosAnoHist->ultimoanorede) != "" && trim($oDadosAnoHist->ultimoanofora) != "") {
    	
      if (trim($oDadosAnoHist->ultimoanorede) > trim($oDadosAnoHist->ultimoanofora)) {
        $iUltimoAno = $oDadosAnoHist->ultimoanorede;
      } else {
        $iUltimoAno = $oDadosAnoHist->ultimoanofora;
      }
      
    }
  	
  } else {
    $iUltimoAno = date("Y",  db_getsession("DB_datausu"));;
  }
  
  /* Carga Horária */
  $sWhereCarga  = " ed61_i_codigo IN ($iCodigoHist) AND ed62_c_resultadofinal = 'A' AND ed62_i_anoref <= $iUltimoAno ";
  $sCamposCR    = "sum(ed62_i_qtdch) as chtotalrede";
  $sSqlCarga    = $oDaoHistorico->sql_query_historicomps("", $sCamposCR, "", $sWhereCarga);
  $rsCarga      = $oDaoHistorico->sql_record($sSqlCarga);
  $iLinhasCarga = $oDaoHistorico->numrows;
  $oDadosCarga  = db_utils::fieldsmemory($rsCarga, 0);
  
  /* Carga Horária Fora */
  $sWhereCargaFora  = " ed61_i_codigo IN ($iCodigoHist) AND ed99_c_resultadofinal = 'A' AND ed99_i_anoref <= $iUltimoAno ";
  $sCamposCF        = " sum(ed99_i_qtdch) as chtotalfora ";
  $sSqlCargaFora    = $oDaoHistorico->sql_query_historicompsfora("", $sCamposCF, "", $sWhereCargaFora);
  $rsCargaFora      = $oDaoHistorico->sql_record($sSqlCargaFora);
  $iLinhasCargaFora = $oDaoHistorico->numrows;
  $oDadosCargaFora  = db_utils::fieldsmemory($rsCargaFora, 0);
  
  if ($iTipoRegistro == "A") {
  	
    //somente registros aprovados
    $sCondicaoHistMps     = " AND ed62_i_anoref <= $iUltimoAno AND ed62_c_resultadofinal = 'A' ";
    $sCondicaoHistMpsFora = " AND ed99_i_anoref <= $iUltimoAno AND ed99_c_resultadofinal = 'A' ";
    
  } else if ($iTipoRegistro == "AR") {
  	
    //registros aprovados e reprovados
    $sCondicaoHistMps     = " AND ed62_i_anoref <= $iUltimoAno ";
    $sCondicaoHistMpsFora = " AND ed99_i_anoref <= $iUltimoAno ";
    
  } else if ($iTipoRegistro == "U") {

    /* Exibe todos os registros com aprovado,  exceto o último,  que exibe de qualquer forma (aprovado ou não). */
    $sCondicaoHistMps      = " AND ed62_i_anoref <= $iUltimoAno ";
    $sCondicaoHistMps     .= " AND (ed62_c_resultadofinal = 'A' OR ed62_i_anoref = $iUltimoAno)";
    $sCondicaoHistMpsFora  = " AND ed99_i_anoref <= $iUltimoAno ";
    $sCondicaoHistMpsFora .= "AND (ed99_c_resultadofinal = 'A' OR ed99_i_anoref = $iUltimoAno)";
    
  }
  
  montaCabecalho($oPdf, $oDadosRelatModel, $oDadosEscola, $oDadosAluno, $oDadosHist, $iEscola, 
                 $iCodigoCurso, $sTelefoneEscola, $iCodigoHist, $sCondicaoHistMps, $sCondicaoHistMpsFora);
  
  $sSqlUnion    = " SELECT ed11_i_sequencia,  ed62_i_anoref,  ed62_i_escola,  ed232_c_descrcompleta,  ed11_c_abrev,  ";
  $sSqlUnion   .= "        ed65_i_disciplina,  ed65_i_justificativa,  ed65_i_qtdch,  ed65_c_resultadofinal,  ";
  $sSqlUnion   .= "        ed65_c_situacao,  ed65_c_tiporesultado,  ed65_t_resultobtido, ed29_c_historico";
  $sSqlUnion   .= "       FROM histmpsdisc ";
  $sSqlUnion   .= "            inner join disciplina on ed12_i_codigo = ed65_i_disciplina ";
  $sSqlUnion   .= "            inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
  $sSqlUnion   .= "            inner join historicomps on ed62_i_codigo = ed65_i_historicomps ";
  $sSqlUnion   .= "            inner join serie on ed11_i_codigo = ed62_i_serie ";
  $sSqlUnion   .= "            inner join historico on ed61_i_codigo = ed62_i_historico ";
  $sSqlUnion   .= "            inner join cursoedu on ed29_i_codigo = ed61_i_curso ";
  $sSqlUnion   .= "       WHERE ed61_i_codigo in ($iCodigoHist) and ed29_c_historico = 'S' $sCondicaoHistMps";
  $sSqlUnion   .= " UNION ";
  $sSqlUnion   .= " SELECT ed11_i_sequencia,  ed99_i_anoref,  ed99_i_escolaproc,  ed232_c_descrcompleta,  ed11_c_abrev,  ";
  $sSqlUnion   .= "        ed100_i_disciplina,  ed100_i_justificativa,  ed100_i_qtdch,  ed100_c_resultadofinal,  ";
  $sSqlUnion   .= "        ed100_c_situacao, ed100_c_tiporesultado, ed100_t_resultobtido, ed29_c_historico";
  $sSqlUnion   .= "       FROM histmpsdiscfora ";
  $sSqlUnion   .= "            inner join disciplina on ed12_i_codigo = ed100_i_disciplina ";
  $sSqlUnion   .= "            inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
  $sSqlUnion   .= "            inner join historicompsfora on ed99_i_codigo = ed100_i_historicompsfora ";
  $sSqlUnion   .= "            inner join serie on ed11_i_codigo = ed99_i_serie ";
  $sSqlUnion   .= "            inner join historico on ed61_i_codigo = ed99_i_historico "; 
  $sSqlUnion   .= "            inner join cursoedu on ed29_i_codigo = ed61_i_curso ";
  $sSqlUnion   .= "       WHERE ed61_i_codigo in ($iCodigoHist) and ed29_c_historico = 'S' $sCondicaoHistMpsFora";
  $sSqlUnion   .= "       ORDER BY ed62_i_anoref ASC ";
  $rsUnion      = db_query($sSqlUnion);
  $iLinhasUnion = pg_num_rows($rsUnion);
  
  $oPdf->SetY(48);
  
  $lCor          = true;
  $iCont         = 0;
  $iTopo         = 48;
  $iPassou       = 0;
  $iAlturaColuna = 72;
  $iAlturaLinha  = 4;
  $iAlturaAntes  = 0;
  $iAlturaAtual  = 0;
  $iLinhasAdd    = 0;
  
  /* Percorre as disciplinas encontradas no histórico do aluno */
  for ($iContDisc = 0; $iContDisc < $iLinhasUnion; $iContDisc++) {
  	
  	$oDadosDisciplina = db_utils::fieldsmemory($rsUnion, $iContDisc);
  	
  	/* Variável que irá 'zebrar' as linhas */
  	if ($lCor) {
  	  $lCor = false;
  	} else {
  	  $lCor = true;
  	}
  	
  	/* Verifico (superficialmente) se a linha irá ser suportada pela coluna */
    $iTeste = ceil(strlen($oDadosDisciplina->ed232_c_descrcompleta) / 32) * 4; 
   
    /* Se o teste retornar que a disciplina dará em uma linha */
    if ($iTeste == 4) {
    	
      if (($iAlturaAtual + 4) > $iAlturaColuna) {
      	
      	if ($iPassou == 2) {
      	
      	  montaCabecalho($oPdf, $oDadosRelatModel, $oDadosEscola, $oDadosAluno, $oDadosHist, $iEscola, 
                         $iCodigoCurso, $sTelefoneEscola, $iCodigoHist, $sCondicaoHistMps, $sCondicaoHistMpsFora);
                 
          $oPdf->SetY(48);
          $iPassou = 0;
                 
        } else {
      	  $iPassou++;
        }
    	
        $iTopo        = 48;
        $iAlturaAtual = 0;
        $iAltura      = 4;
        $iCont = 0;
      	
      }	
    /* Se o teste retornar que a linha dará em mais de uma linha */
    } elseif (($iAlturaAtual + $iTeste) >= $iAlturaColuna) {
      
      if ($iPassou == 2) {
      	
      	montaCabecalho($oPdf, $oDadosRelatModel, $oDadosEscola, $oDadosAluno, $oDadosHist, $iEscola, 
                 $iCodigoCurso, $sTelefoneEscola, $iCodigoHist, $sCondicaoHistMps, $sCondicaoHistMpsFora);
                 
        $oPdf->SetY(48);
        $iPassou = 0;
                 
      } else {
      	$iPassou++;
      }
    	
      $iTopo        = 48;
      $iAlturaAtual = 0;
      $iAltura      = 4;
      $iCont = 0;	
    	
    }
  	
  	/* Posiciona na próxima coluna */
  	if ($iPassou == 1) {
  	  
  	  if ($iCont > 0) {
  	    $iTopo = $iTopo + $iAlturaLinha;
  	  }
  	  
  	  $oPdf->SetXY(102, $iTopo);
  	  $iAlturaAntes = $iAlturaLinha;
  		
  	} 	
  	if ($iPassou == 2) {
  	  
  	  if ($iCont > 0) {
  	    $iTopo = $iTopo + $iAlturaLinha;
  	  }
  	  
  	  $oPdf->SetXY(194, $iTopo);
  	  $iAlturaAntes = $iAlturaLinha;
  		
  	}
  	
  	if (is_numeric($oDadosDisciplina->ed65_t_resultobtido)) {
  	  
  	  if ($sParamEdu == 'S') {
        $sResultado = number_format($oDadosDisciplina->ed65_t_resultobtido, 2, ".", ".");
      } else {
        $sResultado = floor($oDadosDisciplina->ed65_t_resultobtido);
      }
  		
  	} else {
  	  $sResultado = $oDadosDisciplina->ed65_t_resultobtido;
  	}
  	
  	$iAltAnterior = $oPdf->getY();
    $oPdf->setfont('arial','',7);
    $oPdf->multicell(36, 4, $oDadosDisciplina->ed232_c_descrcompleta, "LR", "L", $lCor, 0);
    
    $iAltDepois    = $oPdf->getY();
    $iAltura       = $iAltDepois - $iAltAnterior;
    $iAlturaAtual += $iAltura;
    $iAlturaLinha  = $iAltura;

    $iLinhasAdd   += $iAltura / 4;
    
    if ($iPassou == 0) {
      $oPdf->setXY($oPdf->getX() + 36, $iAltDepois - $iAltura);
    } elseif ($iPassou == 1) {
      $oPdf->setXY($oPdf->getX() + 128, $oPdf->getY() - $iAltura);
    } else {
      $oPdf->setXY($oPdf->getX() + 220, $iAltDepois - $iAltura);
    }
    
    $iCont++;
    
    $oPdf->Cell(8, $iAltura, $oDadosDisciplina->ed11_c_abrev, "LR", 0, "C", $lCor);
    $oPdf->SetFont('arial', '', 6);
    $oPdf->Cell(10, $iAltura, ($oDadosDisciplina->ed65_c_situacao != "CONCLUÍDO") ? "Amparo" :
                ($sResultado == "" ? "-" : $sResultado), "LR", 0, "C", $lCor);
    $oPdf->SetFont('arial', '', 7);
    $oPdf->Cell(10, $iAltura, $oDadosDisciplina->ed65_i_qtdch == "" ? "0" : 
                              $oDadosDisciplina->ed65_i_qtdch, "LR", 0, "C", $lCor);
    $oPdf->Cell(8, $iAltura, $oDadosDisciplina->ed65_c_resultadofinal == "A" ? "APR" : 
                            ($oDadosDisciplina->ed65_c_situacao!="CONCLUÍDO")?"APR":"REP", "LR", 0, "C", $lCor);
    $oPdf->Cell(10, $iAltura, $oDadosDisciplina->ed62_i_anoref, "LR", 0, "C", $lCor);
    $oPdf->Cell(10, $iAltura, $oDadosDisciplina->ed62_i_escola, "LR", 1, "C", $lCor);
    
    if ($iAlturaAtual > $iAlturaColuna) {
      
      $iTopo        = 48;
      $iAltura      = 4;
      $iAlturaAtual = 0;
      $iPassou++;
    	
    }
  
  } //End FOR Percorre Disciplinas
  
} //End FOR $oDaoAluno->numrows

$oPdf->Output();

?>