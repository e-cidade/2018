<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");
require_once('libs/db_app.utils.php');
require_once('libs/db_libcontabilidade.php');
require_once('libs/db_liborcamento.php');
require_once('classes/db_db_config_classe.php');
require_once("dbforms/db_funcoes.php");

db_app::import("linhaRelatorioContabil");
db_app::import("relatorioContabil");
db_app::import("contabilidade.relatorios.AnexoXVBalancoGeral");

$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession('DB_anousu');
$sInstituicoes     = str_replace('-', ',', $oGet->db_selinstit);

$cldb_config       = new cl_db_config;
$oReltorioContabil = new relatorioContabil(111, false);

$oAnexoXVBalancoGeral = new AnexoXVBalancoGeral($iAnoUsu, 111, $oGet->periodo);
$oAnexoXVBalancoGeral->setInstituicoes($sInstituicoes);

$aDados   = $oAnexoXVBalancoGeral->getDados();
$iNumRows = count($aDados);
if ($iNumRows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

/**
 * Adiciona nome abreviado das instituições selecionadas
 */
$sWhere           = "codigo in({$sInstituicoes})";
$sSqlDbConfig     = $cldb_config->sql_query_file(null, "nomeinstabrev", 'codigo', $sWhere);
$rsSqlDbConfig    = $cldb_config->sql_record($sSqlDbConfig); 
$iNumRowsDbConfig = $cldb_config->numrows; 
if ($iNumRowsDbConfig == 0) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Instituição não informada.');
}

$sNomeInstAbrev = "";
$sVirgula       = "";
for ($iInd = 0; $iInd < $iNumRowsDbConfig; $iInd++) {
  
  $oMunicipio      = db_utils::fieldsMemory($rsSqlDbConfig, $iInd);
  $sNomeInstAbrev .= $sVirgula.$oMunicipio->nomeinstabrev;
  $sVirgula        = ", ";
}

/**
 * Adiciona descrição do periodo selecionado
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oReltorioContabil->getPeriodos();
foreach ($aPeriodos as $oPeriodo) {
	
	if ($oPeriodo->o114_sequencial == $oGet->periodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
	}
}

$head2  = "DEMONSTRATIVO DAS VARIAÇÕES PATRIMONIAIS - ANEXO 15";
$head3  = "EXERCÍCIO {$iAnoUsu}";
$head5  = "INSTITUIÇÕES: {$sNomeInstAbrev}";
$head7  = "ANEXO 15 - PERÍODO: {$sDescricaoPeriodo}";

$oPdf   = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetAutoPageBreak(true, 30);
$oPdf->AddPage("P");
$oPdf->SetFillColor(235);

$iTamFonte       = 7;
$iAltCell        = 4;
$iPosX           = 0;
$iPosicaoReceita = 0;
$iPosicaoDespesa = 0;

/**
 * Cabeçalho da página
 */
$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(95, $iAltCell, 'Variações Ativas', 'TBR', 0, "C", 0);
$oPdf->Cell(95, $iAltCell, 'Variações Passivas', 'TBL', 1, "C", 0);

$iPosInicialY = $oPdf->GetY();
$iPosInicialX = $oPdf->GetX();

/**
 * Percorre todos as linhas do relatório
 */
foreach ($aDados as $iIndice => $oDadosRelatorio) {

  /**
   * Verifica quais são as linhas totalizadoras
   */
  if (!$oDadosRelatorio->totalizar) {
    $oPdf->SetFont('arial', '', $iTamFonte);
  } else {
    $oPdf->SetFont('arial', 'b', $iTamFonte);
  }
  
  /**
   * Descrição e valor das linhas
   */
  $sDescricao = setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao;
  if (!isset($oDadosRelatorio->valor) && empty($oDadosRelatorio->valor)) {
    $oDadosRelatorio->valor = 0;
  }
  
  /**
   * Bloco das linhas 1 á 21 e 32 á 41
   */
  if ($iIndice >= 1 && $iIndice <= 21 || $iIndice >= 32 && $iIndice <= 41) {
    
    if ($iIndice >= 1 && $iIndice <= 21) {
      
      $oPdf->Cell(70, $iAltCell, $sDescricao,                                'R', 0, "L", 0);  
      $oPdf->Cell(25, $iAltCell, db_formatar(@$oDadosRelatorio->valor, "f"), 'LR', 0, "R", 0);
      $oPdf->Cell(70, $iAltCell, '',                                         'RL', 0, "L", 0);
      $oPdf->Cell(25, $iAltCell, '',                                         'L', 1, "R", 0);
    }
    
    if ($iIndice >= 32 && $iIndice <= 41) {
            
      if ($iIndice == 32) {
        $oPdf->SetY($iPosInicialY);
      }
    	
      $oPdf->Cell(70, $iAltCell, '',                                         'R', 0, "L", 0);  
      $oPdf->Cell(25, $iAltCell, '',                                         'LR', 0, "R", 0);
      $oPdf->Cell(70, $iAltCell, $sDescricao,                                'RL', 0, "L", 0);
      $oPdf->Cell(25, $iAltCell, db_formatar(@$oDadosRelatorio->valor, "f"), 'L', 1, "R", 0);
    	
      if ($iIndice == 38) {     
        setLinhaEmBranco($oPdf, $iPosInicialX, $iAltCell, true, 1);
      }      

      if ($iIndice == 41) {
        setLinhaEmBranco($oPdf, $iPosInicialX, $iAltCell, true, 10);
      }
    }
  }
}

$iPosInicialX = $oPdf->GetX();
$iPosInicialY = $oPdf->GetY();

$oPdf->SetAutoPageBreak(false);
$aGrupoLinhas    = array();
$aGrupoLinhas[1] = array(22, 42);
$aGrupoLinhas[2] = array(23, 43);
$aGrupoLinhas[3] = array(24, 44);
$aGrupoLinhas[4] = array(25, 45);
$aGrupoLinhas[5] = array(26, 46);
$aGrupoLinhas[6] = array(27, 47);
foreach ($aGrupoLinhas as $aLinha) {
	
	foreach ($aLinha as $iIndice) {
		
		$oDadosRelatorio = $aDados[$iIndice];

	  imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte);
      
    /**
     * Verifica quais são as linhas totalizadoras
     */
    if (!$aDados[$iIndice]->totalizar) {
      $oPdf->SetFont('arial', '', $iTamFonte);
    } else {
      $oPdf->SetFont('arial', 'b', $iTamFonte);
    }
		
	  $sDescricao = setIdentacao($aDados[$iIndice]->nivellinha).$aDados[$iIndice]->descricao;
    if (!isset($aDados[$iIndice]->valor) && empty($aDados[$iIndice]->valor)) {
      $aDados[$iIndice]->valor = 0;
    }
        
    /**
     * Bloco das linhas 22, 23, 24, 25, 26, 27
     * Bloco Contas Análiticas
     */
    if ($iIndice >= 22 && $iIndice <= 27) {
 
      if ($iIndice == 22) {
        $oPdf->SetY($iPosInicialY);
      }
    	
    	$iPosicaoInicial = $oPdf->GetY();
    	
      $oPdf->Cell(70, $iAltCell, $sDescricao,                                 'R', 0, "L", 0);  
      $oPdf->Cell(25, $iAltCell, db_formatar(@$aDados[$iIndice]->valor, "f"), 'LR', 0, "R", 0);
      $oPdf->Cell(70, $iAltCell, '',                                          'RL', 0, "L", 0);
      $oPdf->Cell(25, $iAltCell, '',                                          'L', 1, "R", 0);
      
      /**
       * Contas Análiticas
       */
      if ($aDados[$iIndice]->desdobrar == true) {
         
        foreach ($aDados[$iIndice]->contas as $oDadosConta) {
            
          $oPdf->SetFont('arial', '', $iTamFonte);
            
          $oPdf->Cell(70, $iAltCell, substr('                '.$oDadosConta->descricao, 0, 51), 'R', 0, "L", 0);  
          $oPdf->Cell(25, $iAltCell, db_formatar(@$oDadosConta->valor, "f"),                    'LR', 0, "R", 0);
          $oPdf->Cell(70, $iAltCell, '',                                                        'RL', 0, "L", 0);
          $oPdf->Cell(25, $iAltCell, '',                                                        'L', 1, "R", 0);
            
          imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte);
        }
      }

      imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte);
      $iPosicaoReceita = $oPdf->GetY();
    }
    
    /**
     * Bloco das linhas 42, 43, 44, 45, 46, 47
     * Bloco Contas Análiticas
     */
    if ($iIndice >= 42 && $iIndice <= 47) {
    	
    	$oPdf->SetY($iPosicaoInicial);
          	
      $oPdf->Cell(70, $iAltCell, '',                                          'R', 0, "L", 0);  
      $oPdf->Cell(25, $iAltCell, '',                                          'LR', 0, "R", 0);
      $oPdf->Cell(70, $iAltCell, $sDescricao,                                 'RL', 0, "L", 0);
      $oPdf->Cell(25, $iAltCell, db_formatar(@$aDados[$iIndice]->valor, "f"), 'L', 1, "R", 0);
      
      /**
       * Contas Análiticas
       */
      if ($aDados[$iIndice]->desdobrar == true) {
          
        foreach ($aDados[$iIndice]->contas as $oDadosConta) {
            
          $oPdf->SetFont('arial', '', $iTamFonte);
            
          $oPdf->Cell(70, $iAltCell, '',                                                        'R', 0, "L", 0);  
          $oPdf->Cell(25, $iAltCell, '',                                                        'LR', 0, "R", 0);
          $oPdf->Cell(70, $iAltCell, substr('                '.$oDadosConta->descricao, 0, 51), 'RL', 0, "L", 0);  
          $oPdf->Cell(25, $iAltCell, db_formatar(@$oDadosConta->valor, "f"),                    'L', 1, "R", 0);
          
          imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte);
        }
      }
      
      imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte);
      
      $iPosicaoDespesa = $oPdf->GetY(); 
     
      /**
       * Trata a posição GetY() no relatório, para poder incluir um abaixo do outro
       */
      if ( $iPosicaoDespesa < $iPosicaoInicial || $iPosicaoDespesa > $iPosicaoReceita) {
        $iPosicaoReceita = $iPosicaoDespesa;
      }
    }
	}
	
	/*
	 * Trata a altura, para que os subtotal das primeiras colunas fiquei alinhados
	 * sendo que a coluna da esquerda (despesa), podera vir com mais registros
	 */
  $iAlturaRel = $iPosicaoReceita;
  $oPdf->SetY($iAlturaRel);  
}

$oPdf->SetAutoPageBreak(true, 30);

$oPdf->Cell(70, $iAltCell+12, '', 'R', 0, "L", 0);  
$oPdf->Cell(25, $iAltCell+12, '', 'LR', 0, "R", 0);   
$oPdf->Cell(70, $iAltCell+12, '', 'RL', 0, "L", 0);  
$oPdf->Cell(25, $iAltCell+12, '', 'L', 1, "R", 0);

$iPosInicialY = $oPdf->GetY();
$iPosInicialX = $oPdf->GetX();

foreach ($aDados as $iIndice => $oDadosRelatorio) {

  /**
   * Verifica quais são as linhas totalizadoras
   */
  if (!$oDadosRelatorio->totalizar) {
    $oPdf->SetFont('arial', '', $iTamFonte);
  } else {
    $oPdf->SetFont('arial', 'b', $iTamFonte);
  }
  
  /**
   * Descrição e valor das linhas
   */
  $sDescricao = setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao;
  if (!isset($oDadosRelatorio->valor) && empty($oDadosRelatorio->valor)) {
    $oDadosRelatorio->valor = 0;
  }

  /**
   * Bloco das linhas 28, 29, 30, 31 e 48, 49, 50, 51
   */
  if ($iIndice >= 28 && $iIndice <= 31 || $iIndice >= 48 && $iIndice <= 51) {
    
    if ($iIndice >= 28 && $iIndice <= 31) {
      
      $sBordaEsqA = 'R';
      $sBordaEsqB = 'LR';
      $sBordaDirA = 'RL';
      $sBordaDirB = 'L';
      if ($iIndice == 28 || $iIndice == 31) {
        
        $sBordaEsqA = 'BTR';
        $sBordaEsqB = 'BTLR';
        $sBordaDirA = 'BTRL';
        $sBordaDirB = 'BTL';
      }
      
      if ($iIndice == 28) {
        $oPdf->SetY($iPosInicialY);
      }
      
      $oPdf->Cell(70, $iAltCell, $sDescricao,                                $sBordaEsqA, 0, "L", 0);  
      $oPdf->Cell(25, $iAltCell, db_formatar(@$oDadosRelatorio->valor, "f"), $sBordaEsqB, 0, "R", 0);
      $oPdf->Cell(70, $iAltCell, '',                                         $sBordaDirA, 0, "L", 0);
      $oPdf->Cell(25, $iAltCell, '',                                         $sBordaDirB, 1, "R", 0);
      
      if ($iIndice == 28 || $iIndice == 30) {     
        setLinhaEmBranco($oPdf, $iPosInicialX, $iAltCell, false, 1);
      }
    }
    
    if ($iIndice >= 48 && $iIndice <= 51) {
      
      $sBordaEsqA = 'R';
      $sBordaEsqB = 'LR';
      $sBordaDirA = 'RL';
      $sBordaDirB = 'L';
      if ($iIndice == 48 || $iIndice == 51) {
        
        $sBordaEsqA = 'BTR';
        $sBordaEsqB = 'BTLR';
        $sBordaDirA = 'BTRL';
        $sBordaDirB = 'BTL';
      }
      
      if ($iIndice == 48) {
        $oPdf->SetY($iPosInicialY);
      }
      
      $oPdf->Cell(70, $iAltCell, '',                                         $sBordaEsqA, 0, "L", 0);  
      $oPdf->Cell(25, $iAltCell, '',                                         $sBordaEsqB, 0, "R", 0);
      $oPdf->Cell(70, $iAltCell, $sDescricao,                                $sBordaDirA, 0, "L", 0);
      $oPdf->Cell(25, $iAltCell, db_formatar(@$oDadosRelatorio->valor, "f"), $sBordaDirB, 1, "R", 0);
      
      if ($iIndice == 48 || $iIndice == 50) {     
        setLinhaEmBranco($oPdf, $iPosInicialX, $iAltCell, false, 1);
      }
    }
  }
}

$oPdf->Ln();

/**
 * Adiciona as notas explicativas
 */
$oReltorioContabil->getNotaExplicativa($oPdf, $oGet->periodo);

/**
 * Adiciona as assinaturas
 */
$oReltorioContabil->assinatura($oPdf, 'BG');

$oPdf->Output();

/**
 * Seta identação das linhas
 *
 * @param integer_type $iNivel
 * @return $sEspaco
 */
function setIdentacao($iNivel) {
	
	$sEspaco = "";
	if ($iNivel > 1) {
		$sEspaco = str_repeat("   ", $iNivel);
	}
	
	return $sEspaco;
}

/**
 * Seta as linhas em branco
 *
 * @param object_type $oPdf
 * @param integer_type $iPosInicialX
 * @param integer_type $iAltCell
 * @param boolean_type $lColunas
 * @param integer_type $iQnt
 */
function setLinhaEmBranco($oPdf, $iPosInicialX, $iAltCell, $lColunas, $iQnt=0) {
    
  for ($iInd = 0; $iInd < $iQnt; $iInd++) {
    
    if (!$lColunas) {
      
      $oPdf->Cell(70, $iAltCell, '', 'R', 0, "C", 0);
      $oPdf->Cell(25, $iAltCell, '', 'LR', 0, "C", 0);
      $oPdf->Cell(70, $iAltCell, '', 'RL', 0, "C", 0);
      $oPdf->Cell(25, $iAltCell, '', 'L', 1, "C", 0);
    } else {
      
    	$oPdf->SetX($iPosInicialX+95);
    	
      $oPdf->Cell(70, $iAltCell, '', 'RL', 0, "C", 0);
      $oPdf->Cell(25, $iAltCell, '', 'L', 1, "C", 0);
    }
    
    imprimeInfoProxPagina($oPdf, $iAltCell, 7);
  }
}

/**
 * Impime informacao da proxima pagina no relatorio
 *
 * @param object type $oPdf
 * @param integer type $iAltCell
 * @param integer type $iTamFonte
 */
function imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte) {
  
  if ( $oPdf->GetY() > $oPdf->h - 31) {
    
    $oPdf->SetFont('arial', 'b', $iTamFonte);

    $oPdf->Cell(190, ($iAltCell*3), 'Continua na página '.($oPdf->PageNo()+1)."/{nb}",    'T', 1, "R", 0);     
    $oPdf->AddPage("P");
     
    $oPdf->Cell(190, ($iAltCell*2), 'Continuação '.($oPdf->PageNo())."/{nb}",             'T', 1, "R", 0);
    
    $oPdf->Cell(190, 1, '',    'B', 1, "C", 0);
  }
}
?>