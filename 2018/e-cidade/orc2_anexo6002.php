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

include(modification("fpdf151/pdf.php"));
include(modification("fpdf151/assinatura.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_utils.php"));
include(modification("libs/db_liborcamento.php"));
include(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$classinatura = new cl_assinatura;

//$tipo_agrupa = substr($nivel,0,1);

$qorgao = 0;
$qunidade = 0;

$xinstit = split("-", $db_selinstit);
$resultinst = db_query("select codigo,nomeinstabrev from db_config where codigo in (".str_replace('-', ', ', $db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
for ($xins = 0; $xins < pg_numrows($resultinst); $xins ++) {
	db_fieldsmemory($resultinst, $xins);
	$descr_inst .= $xvirg.$nomeinstabrev;
	$xvirg = ', ';
}

$xtipo = 0;
if ($origem == "O") {
	$xtipo = "ORÇAMENTO";
} else {
	$xtipo = "BALANÇO";
	if ($opcao == 3)
		$head6 = "PERÍODO : ".db_formatar($perini, 'd')." A ".db_formatar($perfin, 'd');
	else
		$head6 = "PERÍODO : ".strtoupper(db_mes(substr($perini, 5, 2)))." A ".strtoupper(db_mes(substr($perfin, 5, 2)));
}
$head2 = "DESPESA POR PROJ/ATIVIDADE";
$head3 = "POR ORGÃO E UNIDADE ";
$head4 = "ANEXO (6) EXERCÍCIO: ".db_getsession("DB_anousu")." - ".$xtipo;
$head5 = "INSTITUIÇÕES : ".$descr_inst;

$nivela = substr($nivel, 0, 1);
$sele_work = ' w.o58_instit in ('.str_replace('-', ', ', $db_selinstit).') ';
if ($nivela >= 1) {
	$sele_work .= " and exists (select 1 from t where t.o58_orgao = w.o58_orgao) ";
}
if ($nivela >= 2) {
	$sele_work .= " and exists (select 1 from t where t.o58_unidade = w.o58_unidade) ";
}
if ($nivela >= 3) {
	$sele_work .= " and exists (select 1 from t where t.o58_funcao = w.o58_funcao) ";
}
if ($nivela >= 4) {
	$sele_work .= " and exists (select 1 from t where t.o58_subfuncao = w.o58_subfuncao) ";
}
if ($nivela >= 5) {
	$sele_work .= " and exists (select 1 from t where t.o58_programa = w.o58_programa) ";
}
if ($nivela >= 6) {
	$sele_work .= " and exists (select 1 from t where t.o58_projativ = w.o58_projativ) ";
}
if ($nivela >= 7) {
	$sele_work .= " and exists (select 1 from t where t.o58_elemento = e.o56_elemento) ";
}
if ($nivela >= 8) {
	$sele_work .= " and exists (select 1 from t where t.o58_codigo = w.o58_codigo) ";
}

db_query("begin");
db_query("create temp table t(o58_orgao int8,o58_unidade int8,o58_funcao int8,o58_subfuncao int8,o58_programa int8,o58_projativ int8,o58_elemento int8,o58_codigo int8)");

$xcampos = split("-", $orgaos);

for ($i = 0; $i < sizeof($xcampos); $i ++) {
	$where = '';
	$virgula = '';
	$xxcampos = split("_", $xcampos[$i]);
	for ($ii = 0; $ii < sizeof($xxcampos); $ii ++) {
		if ($ii > 0) {
			$where .= $virgula.$xxcampos[$ii];
			$virgula = ', ';
		}
	}
	if ($nivela == 1)
		$where .= ",0,0,0,0,0,0,0";
	if ($nivela == 2)
		$where .= ",0,0,0,0,0,0";
	if ($nivela == 3)
		$where .= ",0,0,0,0,0";
	if ($nivela == 4)
		$where .= ",0,0,0,0";
	if ($nivela == 5)
		$where .= ",0,0,0";
	if ($nivela == 6)
		$where .= ",0,0";
	if ($nivela == 7)
		$where .= ",0";
	db_query("insert into t values($where)");
}

db_query("commit");

$anousu = db_getsession("DB_anousu");
$dataini = $perini;
$datafin = $perfin;

if ($tipo_agrupa == 1) {

  $sNomeQuebra   = "o40_descr";
  $sCodigoQuebra = "o58_orgao";
	$grupoini = 0;
	$grupofin = 3;
}
elseif ($tipo_agrupa == 2) {

  $grupoini = 1;
	$grupofin = 3;
} else {
  
  $sNomeQuebra   = "o40_descr";
  $sCodigoQuebra = "o58_orgao";
	$grupoini = 8;
	$grupofin = 0;
}
//echo $nivela;
//db_criatabela(db_query("select * from t"));exit;

// o contador disse que deve sair o empenhado quando emitir a posição contábil

$tipo_balanco = 2; // empenhado
if ($origem == "O") {
  $tipo_balanco = 1;
}

//echo $sele_work." => ".$anousu." => ".$dataini." => ".$datafin." => ".$grupoini." >> ".$grupofin." >>> ".$tipo_balanco; exit;

$result = db_dotacaosaldo(8, 1, 3, true, $sele_work, $anousu, $dataini, $datafin, $grupoini, $grupofin,false,$tipo_balanco);
//$result = db_dotacaosaldo(8,1,2,true,$sele_work,$anousu,$dataini,$datafin,1,6);
//db_criatabela($result);exit;
$iTotalLinhas     = pg_num_rows($result);
$aLinhasRelatorio = array();
if ($tipo_agrupa == 1) {
  
  $oOrgao             = new stdClass();
  $oOrgao->descricao  = '';
  $oOrgao->codigo     = '';
  $oOrgao->nos        = array();
  $aLinhasRelatorio[0] = $oOrgao;
}

for ($i = 0; $i < $iTotalLinhas; $i++) {
  
  $oLinha = db_utils::fieldsMemory($result, $i);
  $sHash  = '';
  if ($tipo_agrupa == 2) {
    
    $sHash = "o{$oLinha->o58_orgao}";
    $sHashindice = $sHash;
    if ($oLinha->o58_orgao != 0 && $oLinha->o58_funcao == 0) {
      
      if (!isset($aLinhasRelatorio[$sHash])) {
         
         $oOrgao                   = new stdClass();
         $oOrgao->descricaoorgao   = $oLinha->o40_descr;
         $oOrgao->codigoorgao      = $oLinha->o58_orgao;
         $oOrgao->nos              = array();
         $oOrgao->projeto          = $oLinha->proj; 
         $oOrgao->atividade        = $oLinha->ativ; 
         $oOrgao->operacao         = $oLinha->oper;
         $aLinhasRelatorio[$sHash] = $oOrgao;
      }
    }
  } else if ($tipo_agrupa == 3) {
    
    $sHash       = "o{$oLinha->o58_orgao}{$oLinha->o58_unidade}";
    $sHashindice = $sHash;
    if ($oLinha->o58_unidade != 0 && $oLinha->o58_funcao == 0) {
      
      $oOrgao                    = new stdClass();
      $oOrgao->descricaounidade  = $oLinha->o41_descr;
      $oOrgao->descricaoorgao    = $oLinha->o40_descr;
      $oOrgao->codigounidade     = $oLinha->o58_unidade;
      $oOrgao->codigoorgao       = $oLinha->o58_orgao;
      $oOrgao->projeto           = $oLinha->proj; 
      $oOrgao->atividade         = $oLinha->ativ; 
      $oOrgao->operacao          = $oLinha->oper;
      $oOrgao->nos               = array();
      $aLinhasRelatorio[$sHash] = $oOrgao;
    }
  }
  
  $sHash .= "F{$oLinha->o58_funcao}";
  if ($oLinha->o58_funcao != 0 && $oLinha->o58_subfuncao == 0) {
    
    $oFuncao = new stdClass();
    $oFuncao->descricao = $oLinha->o52_descr; 
    $oFuncao->codigo    = db_formatar($oLinha->o58_funcao, "orgao");
    $oFuncao->tipo      = "funcao"; 
    $oFuncao->projeto   = $oLinha->proj; 
    $oFuncao->atividade = $oLinha->ativ; 
    $oFuncao->operacao  = $oLinha->oper;
    
    /**
     * verifica se existe existe a quebra e adiciona a linha
     */
    if ($tipo_agrupa <> 1) {
      if (isset($aLinhasRelatorio[$sHashindice])) {
        $aLinhasRelatorio[$sHashindice]->nos[$sHash] = $oFuncao;
      } 
    } else {
      $aLinhasRelatorio[0]->nos[$sHash] = $oFuncao;
    }
  }
  $sHash .= "SF{$oLinha->o58_subfuncao}";
  if ($oLinha->o58_subfuncao != 0 && $oLinha->o58_programa == 0) {
    
    $oFuncao = new stdClass();
    $oFuncao->descricao = $oLinha->o53_descr; 
    $oFuncao->codigo    = db_formatar($oLinha->o58_funcao,"orgao").".".db_formatar($oLinha->o58_subfuncao,"orgao"); 
    $oFuncao->tipo      = "subfuncao"; 
    $oFuncao->projeto   = $oLinha->proj; 
    $oFuncao->atividade = $oLinha->ativ; 
    $oFuncao->operacao  = $oLinha->oper;
    
    /**
     * verifica se existe existe a quebra e adiciona a linha
     */
    if ($tipo_agrupa <> 1) {
      if (isset($aLinhasRelatorio[$sHashindice])) {
        $aLinhasRelatorio[$sHashindice]->nos[$sHash] = $oFuncao;
      } 
    } else {
      $aLinhasRelatorio[0]->nos[$sHash] = $oFuncao;
    }
  }
  $sHash .= "P{$oLinha->o58_programa}";
  if ($oLinha->o58_programa != 0 && $oLinha->o58_projativ == 0) {
    
    $oFuncao = new stdClass();
    $oFuncao->descricao = $oLinha->o54_descr; 
    $oFuncao->codigo    = db_formatar($oLinha->o58_funcao,"orgao").".".db_formatar($oLinha->o58_subfuncao,"orgao").".".db_formatar($oLinha->o58_programa,"orgao");  
    $oFuncao->tipo      = "programa"; 
    $oFuncao->projeto   = $oLinha->proj; 
    $oFuncao->atividade = $oLinha->ativ; 
    $oFuncao->operacao  = $oLinha->oper;
    
    /**
     * verifica se existe existe a quebra e adiciona a linha
     */
    if ($tipo_agrupa <> 1) {
      if (isset($aLinhasRelatorio[$sHashindice])) {
        $aLinhasRelatorio[$sHashindice]->nos[$sHash] = $oFuncao;
      } 
    } else {
      $aLinhasRelatorio[0]->nos[$sHash] = $oFuncao;
    }
  }
  /**
   * verifica os dados do projetp/atividade
   */
  $sHash .= "PJ{$oLinha->o58_projativ}";  
  if ($oLinha->o58_projativ != 0 && $oLinha->o58_elemento == 0) {
    
    $oFuncao = new stdClass();
    $oFuncao->descricao  = $oLinha->o55_descr; 
    $sCodigo             = db_formatar($oLinha->o58_funcao,"orgao").".".db_formatar($oLinha->o58_subfuncao,"orgao").".";
    $sCodigo            .= db_formatar($oLinha->o58_programa,"orgao").".".db_formatar($oLinha->o58_projativ,"projativ");; 
    $oFuncao->codigo     = $sCodigo; 
    $oFuncao->tipo       = "projativ"; 
    $oFuncao->projeto    = $oLinha->proj; 
    $oFuncao->atividade  = $oLinha->ativ; 
    $oFuncao->operacao   = $oLinha->oper;
    
    /**
     * verifica se existe existe a quebra e adiciona a linha
     */
    if ($tipo_agrupa <> 1) {
      if (isset($aLinhasRelatorio[$sHashindice])) {
        $aLinhasRelatorio[$sHashindice]->nos[$sHash] = $oFuncao;
      } 
    } else {
      $aLinhasRelatorio[0]->nos[$sHash] = $oFuncao;
    }
  }
  $sHash .= "EL{$oLinha->o58_elemento}";
  if ($oLinha->o58_elemento != 0 && $oLinha->o58_codigo == 0) {
    
    $oFuncao = new stdClass();
    $oFuncao->descricao = $oLinha->o56_descr; 
    $oFuncao->codigo    = db_formatar($oLinha->o58_elemento,"elemento"); 
    $oFuncao->tipo      = "elemento"; 
    $oFuncao->projeto   = $oLinha->proj; 
    $oFuncao->atividade = $oLinha->ativ; 
    $oFuncao->operacao  = $oLinha->oper;
    
    /**
     * verifica se existe existe a quebra e adiciona a linha
     */
    if ($tipo_agrupa <> 1) {
      if (isset($aLinhasRelatorio[$sHashindice])) {
        $aLinhasRelatorio[$sHashindice]->nos[$sHash] = $oFuncao;
      } 
    } else {
      $aLinhasRelatorio[0]->nos[$sHash] = $oFuncao;
    }
  }
  $sHash .= "R{$oLinha->o58_codigo}";
  if ($oLinha->o58_codigo != 0) {
    
    $oFuncao = new stdClass();
    $oFuncao->descricao = $oLinha->o15_descr; 
    $oFuncao->codigo    = db_formatar($oLinha->o58_codigo, "recurso"); 
    $oFuncao->tipo      = "recurso"; 
    $oFuncao->projeto   = $oLinha->proj; 
    $oFuncao->atividade = $oLinha->ativ; 
    $oFuncao->operacao  = $oLinha->oper;
    
    /**
     * verifica se existe existe a quebra e adiciona a linha, agrupa o recurso, dentro do nivel caso já exista
     */
    if ($tipo_agrupa <> 1) {
      if (isset($aLinhasRelatorio[$sHashindice])) {
        if (isset($aLinhasRelatorio[$sHashindice]->nos[$sHash])) {

          $aLinhasRelatorio[$sHashindice]->nos[$sHash]->projeto   += $oLinha->proj; 
          $aLinhasRelatorio[$sHashindice]->nos[$sHash]->atividade += $oLinha->ativ; 
          $aLinhasRelatorio[$sHashindice]->nos[$sHash]->operacao  += $oLinha->oper;
        } else {
          $aLinhasRelatorio[$sHashindice]->nos[$sHash] = $oFuncao;
        }
      } 
    } else {
      
      if (isset($aLinhasRelatorio[0]->nos[$sHash])) {
        
        $aLinhasRelatorio[0]->nos[$sHash]->projeto   += $oLinha->proj; 
        $aLinhasRelatorio[0]->nos[$sHash]->atividade += $oLinha->ativ; 
        $aLinhasRelatorio[0]->nos[$sHash]->operacao  += $oLinha->oper;
      } else {
        $aLinhasRelatorio[0]->nos[$sHash] = $oFuncao;
      }
    }
  }
}
$pdf   = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial', 'b', 7);
$troca  = 1;
$alt    = 4;
$pagina = 1;

$nTotalProjeto   = 0;
$nTotalAtividade = 0;
$nTotalOperacao  = 0;
/**
 * Desenhos o layout do relatorio.
 */
foreach ($aLinhasRelatorio as $oLinha) {
  
  foreach ($oLinha->nos as $oDetalhe) {

    $nTotalLinha = $oDetalhe->projeto +$oDetalhe->atividade + $oDetalhe->operacao;
    if ($nTotalLinha == 0) {
      continue;
    }
    if ($pdf->gety() > $pdf->h - 30 || $pagina == 1) {
   
      $pagina = 0;
      $pdf->addpage();
      $pdf->setfont('arial', 'b', 7);
  
      if ($tipo_agrupa != 1) {
        
        $pdf->cell(0, 0.5, '', "TB", 1, "C", 0);
        $sDescricaoOrgao   = db_formatar($oLinha->codigoorgao, 'orgao').'  -  '.$oLinha->descricaoorgao;
        $pdf->cell(10, $alt, "ÓRGÃO  -  ".$sDescricaoOrgao, 0, 1, "L", 0);
        if ($tipo_agrupa == 2) {
          $pdf->cell(0, 0.5, '', "TB", 1, "C", 0);
        }
      }
      if ($tipo_agrupa == 3) {
        $sDescricaoUnidade  = db_formatar($oLinha->codigoorgao, 'orgao').db_formatar($oLinha->codigounidade, 'orgao');
        $sDescricaoUnidade .= "  -  ".$oLinha->descricaounidade;
        $pdf->cell(10, $alt, "UNIDADE ORÇAMENTÁRIA  -  ".$sDescricaoUnidade, 0, 1, "L", 0);
        $pdf->cell(0, 0.5, '', "TB", 1, "C", 0);
      }
      $pdf->ln(2);
      $pdf->cell(25, $alt, "CÓDIGO", 0, 0, "L", 0);
      $pdf->cell(80, $alt, "E S P E C I F I C A Ç Ã O", 0, 0, "C", 0);
      $pdf->cell(20, $alt, "PROJETOS", 0, 0, "R", 0);
      $pdf->cell(20, $alt, "ATIVIDADES", 0, 0, "R", 0);
      $pdf->cell(20, $alt, "OPER.ESPEC", 0, 0, "R", 0);
      $pdf->cell(20, $alt, "TOTAL", 0, 1, "R", 0);
      $pdf->cell(0, $alt, '', "T", 1, "C", 0);
    }
      
    $sAlinhamento = "L";
    $sTipoFonte =  ""; 
    if ($oDetalhe->tipo == 'recurso') {
      $sAlinhamento = "R";
    }
    if ($oDetalhe->tipo == "projativ") {
      $sTipoFonte =  "B";
    }
    $pdf->setfont('arial', $sTipoFonte, 6);
    $pdf->cell(25, $alt, $oDetalhe->codigo, 0, 0, $sAlinhamento, 0);
    $pdf->cell(80, $alt, $oDetalhe->descricao, 0, 0, "L", 0);
    $pdf->setfont('arial', $sTipoFonte, 6);
    $pdf->cell(20, $alt, db_formatar($oDetalhe->projeto, 'f'), 0, 0, "R", 0);
    $pdf->cell(20, $alt, db_formatar($oDetalhe->atividade, 'f'), 0, 0, "R", 0);
    $pdf->cell(20, $alt, db_formatar($oDetalhe->operacao, 'f'), 0, 0, "R", 0);
    $pdf->cell(20, $alt, db_formatar($nTotalLinha, 'f'), 0, 1, "R", 0);
    /*
     * soma totalizadores
     */
    if ($oDetalhe->tipo == "funcao") {
      
      $nTotalProjeto   += $oDetalhe->projeto; 
      $nTotalAtividade += $oDetalhe->atividade; 
      $nTotalOperacao  += $oDetalhe->operacao;
     
    }
  }
  $pagina = 1;
  
  if ($tipo_agrupa == 2 || $tipo_agrupa == 3) {
    
    $nTotalLinha = $oLinha->projeto + $oLinha->atividade + $oLinha->operacao;
    $pdf->setfont('arial', 'b', 6);
    $pdf->ln(3);
    $pdf->cell(25, $alt, '', 0, 0, "r", 0);
    $pdf->cell(80, $alt, 'TOTAL '.($tipo_agrupa ==2?" DO ORGÃO": " DA UNIDADE"), 0, 0, "L", 0);
    $pdf->setfont('arial', 'b', 6);
    $pdf->cell(20, $alt, db_formatar($oLinha->projeto, 'f'), 0, 0, "R", 0);
    $pdf->cell(20, $alt, db_formatar($oLinha->atividade, 'f'), 0, 0, "R", 0);
    $pdf->cell(20, $alt, db_formatar($oLinha->operacao, 'f'), 0, 0, "R", 0);
    $pdf->cell(20, $alt, db_formatar($nTotalLinha, 'f'), 0, 1, "R", 0);
  }
}
$nTotalLinha = $nTotalAtividade + $nTotalOperacao + $nTotalProjeto;
$pdf->setfont('arial', 'b', 6);
$pdf->ln(3);
$pdf->cell(25, $alt, '', 0, 0, "r", 0);
$pdf->cell(80, $alt, 'TOTAL GERAL', 0, 0, "L", 0);
$pdf->setfont('arial', 'B', 6);
$pdf->cell(20, $alt, db_formatar($nTotalProjeto, 'f'), 0, 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($nTotalAtividade, 'f'), 0, 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($nTotalOperacao, 'f'), 0, 0, "R", 0);
$pdf->cell(20, $alt, db_formatar($nTotalLinha, 'f'), 0, 1, "R", 0);
$pdf->ln(14);
if ($origem != "O") {
  assinaturas($pdf, $classinatura,'BG');
}
include(modification("fpdf151/geraarquivo.php"));
