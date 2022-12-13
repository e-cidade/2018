<?
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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$head1 = "RELATÓRIO ALUNOS C/ NECESSIDADES ESPECIAIS";
$head2 = "Escola: TODAS";
$head3 = "Ano: {$oGet->iAno}";
$head4 = "Etapa: TODAS";

/**
 * Array com os tipos de Apoio para necessidade especial
 */
$aApoio = array(""=>"",
                "1"=>"SEM APOIO PEDAGÓGICO",
                "2"=>"COM APOIO PEDAGÓGICO",
                "3"=>"COM APOIO PEDAGÓGICO (OUTRO ESTABELECIMENTO)"
               );

/**
 * Array com os tipos de diagnóstico necessidade especial
 */
$aTipo  = array(""=>"",
                "1"=>"SEM DIAGNÓSTICO",
                "2"=>"FICHA DE AVALIAÇÃO",
                "3"=>"LAUDO TÉCNICO"
               );
           

/**
 * Array com os filtros que montaram a clausula where 
 */
$aFiltros   = array();
$aFiltros[] = " calendario.ed52_i_ano = {$oGet->iAno} ";
$aFiltros[] = " ed60_c_situacao       = 'MATRICULADO' ";
$aFiltros[] = " ed60_c_ativa          = 'S' ";
  
if ($oGet->iEscola != 0) {

  $aFiltros[] = "turma.ed57_i_escola = {$oGet->iEscola}";
  $oEscola    = EscolaRepository::getEscolaByCodigo($oGet->iEscola);

  $sNomeEscola       = $oEscola->getNome();
  $iCodigoReferencia = $oEscola->getCodigoReferencia();

  if ( $iCodigoReferencia != null ) {
    $sNomeEscola = "{$iCodigoReferencia} - {$sNomeEscola}";
  }

  $head2        = "Escola: {$sNomeEscola}";
  unset($oEscola);
}

if ($oGet->iSerie != 0) {
	
  $aFiltros[] = "  serie.ed11_i_codigo = {$oGet->iSerie} ";
  $oEtapa     = EtapaRepository::getEtapaByCodigo($oGet->iSerie);
  $head4      = "Etapa: ".$oEtapa->getNome();
  unset($oEtapa);  
}

$sWhere  = implode(" and ", $aFiltros);
$sWhere .= " and matriculaserie.ed221_c_origem = 'S'";

/**
 * Busca todos os alunos alunos que possuem necessidades especiáis
 */
$sSql  = " SELECT ";
$sSql .= "       turma.ed57_i_escola,                                 ";
$sSql .= "       escola.ed18_c_nome,                                  ";
$sSql .= "       aluno.ed47_i_codigo,                                 ";
$sSql .= "       trim(aluno.ed47_v_nome) as ed47_v_nome,              ";
$sSql .= "       trim(aluno.ed47_c_codigoinep) as ed47_c_codigoinep,  ";
$sSql .= "       serie.ed11_i_codigo,                                 ";
$sSql .= "       trim(serie.ed11_c_descr) as ed11_c_descr,            ";
$sSql .= "       ensino.ed10_i_codigo,                                ";
$sSql .= "       trim(ensino.ed10_c_descr) as ed10_c_descr,           ";
$sSql .= "       turma.ed57_i_codigo,                                 ";
$sSql .= "       trim(turma.ed57_c_descr) as ed57_c_descr,            ";
$sSql .= "       trim(calendario.ed52_c_descr) as ed52_c_descr,       ";
$sSql .= "       serie.ed11_i_sequencia,                              ";
$sSql .= "       necessidade.ed48_i_codigo,                           ";
$sSql .= "       trim(necessidade.ed48_c_descr) as ed48_c_descr,      ";
$sSql .= "       alunonecessidade.ed214_i_apoio,                      ";
$sSql .= "       alunonecessidade.ed214_i_tipo                        ";
$sSql .= "  FROM matricula ";
$sSql .= " INNER JOIN aluno            ON aluno.ed47_i_codigo              = matricula.ed60_i_aluno ";
$sSql .= " INNER JOIN turma            ON turma.ed57_i_codigo              = matricula.ed60_i_turma ";
$sSql .= " INNER JOIN escola           ON escola.ed18_i_codigo             = turma.ed57_i_escola ";
$sSql .= " INNER JOIN calendario       ON calendario.ed52_i_codigo         = turma.ed57_i_calendario ";
$sSql .= " INNER JOIN base             ON base.ed31_i_codigo               = turma.ed57_i_base ";
$sSql .= " INNER JOIN cursoedu         ON cursoedu.ed29_i_codigo           = base.ed31_i_curso ";
$sSql .= " INNER JOIN ensino           ON ensino.ed10_i_codigo             = cursoedu.ed29_i_ensino ";
$sSql .= " INNER JOIN matriculaserie   ON matriculaserie.ed221_i_matricula = matricula.ed60_i_codigo ";
$sSql .= " INNER JOIN serie            ON serie.ed11_i_codigo              = matriculaserie.ed221_i_serie ";
$sSql .= " INNER JOIN alunonecessidade ON alunonecessidade.ed214_i_aluno   = aluno.ed47_i_codigo ";
$sSql .= " INNER JOIN necessidade      ON necessidade.ed48_i_codigo        = alunonecessidade.ed214_i_necessidade ";
$sSql .= " where {$sWhere} ";
$sSql .= " ORDER BY turma.ed57_i_escola,    ";
$sSql .= " serie.ed11_c_descr,     ";
$sSql .= " aluno.ed47_v_nome       ";

$rsAlunos = db_query($sSql);

$iLinhasMatricula = pg_num_rows($rsAlunos);

if ($iLinhasMatricula == 0) {
  db_redireciona("db_erros.php?fechar=true&db_erro=Nenhum registro encontrado para os filtros selecionados.");
}

/**
 * Organiza os dados retornado pela query
 */
$aTotalAlunoEscola  = array();
$aAlunosNecessidade = array();
for ($i = 0; $i < $iLinhasMatricula; $i++) {
  
  $oDadosMatricula = db_utils::fieldsMemory($rsAlunos, $i);
  
  $iEscola = $oDadosMatricula->ed57_i_escola;
  $iEnsino = $oDadosMatricula->ed10_i_codigo;
  $iEtapa  = $oDadosMatricula->ed11_i_codigo;
  
  if ( !array_key_exists($iEscola, $aAlunosNecessidade) ) {
  
    $oEscola          = new stdClass();
    $oEscola->iCodigo = $oDadosMatricula->ed57_i_escola;
    $oEscola->sEscola = $oDadosMatricula->ed18_c_nome;
    $oEscola->aEtapa  = array();
  
    $aAlunosNecessidade[$iEscola] = $oEscola;
  }
  
  $iChaveEnsinoEtapa = "{$iEscola}#{$iEnsino}#{$iEtapa}";
  
  if ( !array_key_exists($iChaveEnsinoEtapa, $aAlunosNecessidade[$iEscola]->aEtapa) ) {
  
    $oEnsino                = new stdClass();
    $oEnsino->iCodigoEnsino = $oDadosMatricula->ed10_i_codigo;
    $oEnsino->sEnsino       = $oDadosMatricula->ed10_c_descr;
    $oEnsino->iCodigoEtapa  = $oDadosMatricula->ed11_i_codigo;
    $oEnsino->sEtapa        = $oDadosMatricula->ed11_c_descr;
    $oEnsino->aAlunos       = array();
  
    $aAlunosNecessidade[$iEscola]->aEtapa[$iChaveEnsinoEtapa] = $oEnsino;
  }
  
  $oAluno                = new stdClass();
  $oAluno->iCodigo       = $oDadosMatricula->ed47_i_codigo;
  $oAluno->sNome         = $oDadosMatricula->ed47_v_nome;
  $oAluno->sTurma        = $oDadosMatricula->ed57_c_descr;
  $oAluno->sEtapa        = $oDadosMatricula->ed11_c_descr;
  $oAluno->sInep         = $oDadosMatricula->ed47_c_codigoinep;
  $oAluno->iEscola       = $oDadosMatricula->ed57_i_escola;
  $oAluno->aNecessidades = array();
  
  $oNecessidade               = new stdClass();
  $oNecessidade->iNecessidade = $oDadosMatricula->ed48_i_codigo;
  $oNecessidade->sNecessidade = $oDadosMatricula->ed48_c_descr;
  $oNecessidade->sApoio       = $aApoio[$oDadosMatricula->ed214_i_apoio];
  $oNecessidade->sTipo        = $aTipo[$oDadosMatricula->ed214_i_tipo];
  
  $oAluno->aNecessidades[] = $oNecessidade;
  
  if ( array_key_exists( $oDadosMatricula->ed47_i_codigo, $aAlunosNecessidade[$iEscola]->aEtapa[$iChaveEnsinoEtapa]->aAlunos ) ) {
    $aAlunosNecessidade[$iEscola]->aEtapa[$iChaveEnsinoEtapa]->aAlunos[$oAluno->iCodigo]->aNecessidades[] = $oNecessidade;
  } else {
    
    $aTotalAlunoEscola[$iEscola][] = true;
    $aAlunosNecessidade[$iEscola]->aEtapa[$iChaveEnsinoEtapa]->aAlunos[$oAluno->iCodigo] = $oAluno;
  }
}

$oPdf = new Pdf("P");
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->SetFillColor(225, 225, 225);

$iHeight = 4;

/**
 * Percorre os dados e imprime o relatório
 */
foreach ($aAlunosNecessidade as $oEscola) {
  
  $oPdf->AddPage();
  validaQuebraPagina($oPdf);
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(15, $iHeight, "Escola: ", 0, 0, "L");
  $oPdf->SetFont('arial', '', 7);
  $oPdf->Cell(165, $iHeight, "{$oEscola->iCodigo} - {$oEscola->sEscola}", 0, 1, "L");
  
  foreach ($oEscola->aEtapa as $oEtapa) {
    
    validaQuebraPagina($oPdf);
    
    $oPdf->SetFont('arial', 'b', 8);
    $oPdf->Cell(15, $iHeight, "Etapa: ", 0, 0, "L");
    $oPdf->SetFont('arial', '', 7);
    $oPdf->Cell(165, $iHeight, $oEtapa->sEtapa, 0, 1, "L");
    
    $iTotalAlunosEtapa = 0;
    $lPrimeiraPagina   = true;
    foreach ($oEtapa->aAlunos as $oAluno) {
      
      if ($lPrimeiraPagina) {
        
        $lPrimeiraPagina = false;
        imprimeCabecalho($oPdf, $iHeight);
      }
      
      $iTotalAlunosEtapa ++;
      validaQuebraPagina($oPdf);
      
      $oPdf->SetFont('arial', '', 7);
      $oPdf->cell(10, $iHeight, $oAluno->iCodigo, "T", 0, "C", 0);
      $oPdf->cell(80, $iHeight, $oAluno->sNome  , "T", 0, "L", 0);
      $oPdf->cell(30, $iHeight, $oAluno->sTurma , "T", 0, "C", 0);
      $oPdf->cell(30, $iHeight, $oAluno->sEtapa , "T", 0, "C", 0);
      $oPdf->cell(30, $iHeight, $oAluno->sInep  , "T", 0, "C", 0);
      $oPdf->cell(10, $iHeight, $oAluno->iEscola, "T", 1, "C", 0);
      
      $oPdf->setfont('arial', 'b', 6);
      $oPdf->cell(10, $iHeight, "",                    0, 0, "L", 0);
      $oPdf->cell(75, $iHeight, "Necessidade",         0, 0, "L", 0);  
      $oPdf->cell(70, $iHeight, "Apoio Pedagógico",    0, 0, "L", 0);
      $oPdf->cell(35, $iHeight, "Tipo de Diagnóstico", 0, 1, "L", 0);
                         
      foreach ( $oAluno->aNecessidades as $oNecessidade ) {
        
        $oPdf->setfont('arial', '', 6);
        $oPdf->cell(10, $iHeight, "",                                                              0, 0, "L", 0);
        $oPdf->cell(75, $iHeight, "{$oNecessidade->iNecessidade} - {$oNecessidade->sNecessidade}", "C", 0, "L", 0);
        $oPdf->cell(70, $iHeight, $oNecessidade->sApoio,                                     "C", 0, "L", 0);
        $oPdf->cell(35, $iHeight, $oNecessidade->sTipo,                                      "C", 1, "L", 0);
      }
    }
    
    validaQuebraPagina($oPdf);
    $oPdf->SetFont('arial', 'b', 8);
    $oPdf->Cell(160, $iHeight, "Total de alunos para etapa: {$oEtapa->sEtapa}", "TBR", 0, "R");
    $oPdf->Cell(30, $iHeight, $iTotalAlunosEtapa, "TBL", 1, "R");
    $oPdf->Ln(2);
  }
  
  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->Cell(160, $iHeight, "Total de alunos na Escola ", "TBR", 0, "R");
  $oPdf->Cell(30, $iHeight, count($aTotalAlunoEscola[$oEscola->iCodigo]), "TBL", 1, "R");
  $oPdf->Ln();
  
}


/**
 * imprime cabeçalho
 * @param FPDF $oPdf
 * @param integer $iHeight
 */
function imprimeCabecalho(FPDF $oPdf, $iHeight) {

  $oPdf->SetFont('arial', 'b', 8);
  $oPdf->cell(10, $iHeight, "Código",      "TBR", 0, "C", 1);
  $oPdf->cell(80, $iHeight, "Aluno",           1, 0, "L", 1);
  $oPdf->cell(30, $iHeight, "Turma",           1, 0, "C", 1);
  $oPdf->cell(30, $iHeight, "Etapa",           1, 0, "C", 1);
  $oPdf->cell(30, $iHeight, "Código INEP",     1, 0, "C", 1);
  $oPdf->cell(10, $iHeight, "Escola",      "TBL", 1, "C", 1);
}



/**
 * Valida se deve ser quebrado pagina
 * @param FPDF $oPdf
 */
function validaQuebraPagina(FPDF $oPdf) {

  if ($oPdf->GetY() > $oPdf->h - 25) {
    $oPdf->AddPage();
  }
}

$oPdf->Output();
?>