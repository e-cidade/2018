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

include("fpdf151/pdf.php");
include("fpdf151/assinatura.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
include("libs/db_libcontabilidade.php");
include("libs/db_liborcamento.php");
include("classes/db_orcparamrel_classe.php");
include("classes/db_empresto_classe.php");
include("classes/db_orcparamseq_classe.php");
include("libs/db_utils.php");
include("libs/db_app.utils.php");
require_once("model/linhaRelatorioContabil.model.php");
require_once("model/relatorioContabil.model.php");

$iAnousu           = db_getsession("DB_anousu");
$iCodigoPeriodo    = $periodo;
$sListaInstituicao = str_replace('-', ', ', $db_selinstit);
db_app::import("contabilidade.relatorios.AnexoXIIIBalancoGeral");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);


$resultinst = pg_exec("select codigo, nomeinst, nomeinstabrev 
                         from db_config 
                        where codigo in (".str_ireplace('-', ',', $db_selinstit).") ");
$descr_inst = '';
$xvirg      = '';
$flag_abrev = false;
for ($xins = 0; $xins < pg_numrows($resultinst); $xins++) {
	
  db_fieldsmemory($resultinst, $xins);
  if (strlen(trim($nomeinstabrev)) > 0) {
    
    $descr_inst .= $xvirg.$nomeinstabrev;
    $flag_abrev  = true;
  } else {
  $descr_inst .= $xvirg.$nomeinst;
  }
  $xvirg = ', ';
}
if ($flag_abrev == false) {
	
  if (strlen($descr_inst) > 42) {
    $descr_inst = substr($descr_inst, 0, 150);
  }
}

try {
	
  $oAnexoXIII           = new AnexoXIIIBalancoGeral($iAnousu, $codrel, $iCodigoPeriodo);
  
  if ($consolidado == 1) {
    $oAnexoXIII->setConsolidado(true);
  }
  
  $oNotasXIII           = new relatorioContabil($codrel, false);
  $oAnexoXIII->setInstituicoes($sListaInstituicao);
  $aLinhas              = $oAnexoXIII->getDados();
} catch (Exception $eErro) {
  db_redireciona("db_erros.php?fechar=true&db_erro={$eErro->getMessage()}");
}

/**
 * Adiciona descrição do periodo selecionado
 */
$sDescricaoPeriodo = "";
$aPeriodos         = $oNotasXIII->getPeriodos();

foreach ($aPeriodos as $oPeriodo) {
  
  if ($oPeriodo->o114_sequencial == $iCodigoPeriodo) {
    $sDescricaoPeriodo = $oPeriodo->o114_descricao;
  }
}

$head3 = "BALANÇO FINANCEIRO";
$head4 = "EXERCÍCIO ".$iAnousu;
//$head5 = "INSTITUIÇÕES : ".$descr_inst;
if ($consolidado == 1) {
  
  $head5 = "INSTITUIÇÕES : CONSOLIDAÇÃO GERAL";
} else {
  $head5 = "INSTITUIÇÕES : ".$descr_inst;
}
$head6 = "ANEXO 13 - PERÍODO : ".$sDescricaoPeriodo;

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(true);
$oPdf->setfillcolor(235);
$oPdf->setfont('arial', 'b', 7);
$iAlt            = 4;
$iPagina         = 1;
$iTamFonte       = 8;
$iFonte          = 6;
$iColunaDescr    = 60;
$iColunaValor    = 25;

$oPdf->addpage();
$oPdf->setfont('arial', 'b', $iFonte);
$oPdf->cell(95, $iAlt, "R E C E I T A S", 0, 0, "C", 0);
$oPdf->cell(95, $iAlt, "D E S P E S A S", 0, 1, "C", 0);
$iYInicial     = $oPdf->getY()+3;
$iValrYDespesa = 0;
$iValrYReceita = 0;
$oPdf->ln(3);

$oPdf->setfont('arial','', $iFonte);

/*
 * Percorre o objeto para as linhas de parametros
 */
foreach ($aLinhas as $iIndice => $oDadosRelatorio) {
 
	/*
	 * Verfica se a descrição eh um totalizador
	 * caso seja atribui bold e tamanho fonte 8
	 */
	if ($oDadosRelatorio->totalizar == 1) {
		$sBold = "B";
	} else {
		$sBold = "";
	}
	/*
	 * Imprime os valores até a linha 18 para a primeira coluna da esquerda
	 * Validando o Titulo da Coluna para que seja impresso maior e em negrito
	 */
	if ($iIndice <= 18) {
		
    if ($iIndice == 1) {
    	
    	$oPdf->setfont('arial','B',$iFonte);
    	$oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 1, "L", 0);
    } else {
    	
    	$oPdf->setfont('arial',$sBold, $iFonte);
		  $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 0, "L", 0,'','.');
		  $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosRelatorio->valor,'f')                              , 0, 1, "R", 0);
    }  
    
    $iValrYReceita = $oPdf->GetY();		
	}
	/*
	 * Guarda o Valor de Y do pdf
	 */

  /*
   * Imprime os valores até da linha 39 para a primeira coluna da direita
   * Validando o Titulo da Coluna para que seja impresso maior e em negrito
   */	
	if ($iIndice == 39) {
		
	   $oPdf->setfont('arial','B',$iFonte);
		 $oPdf->SetY($iYInicial);
		 $oPdf->SetX(100);
     $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 1, "L", 0);
                                                    
     foreach ($oDadosRelatorio->subfuncao as $indSub => $oSubFuncao) {
      
     	 $oPdf->setfont('arial','', $iFonte);
     	 $oPdf->SetX(100);
       $oPdf->cell($iColunaDescr, $iAlt, setIdentacao(4).$oSubFuncao->descricao, 0, 0, "L", 0, '', '.');
       $oPdf->cell($iColunaValor, $iAlt, db_formatar($oSubFuncao->valor,'f')   , 0, 1, "R", 0);
     }
     
     $iValrYDespesa = $oPdf->GetY();
	}
	
  /*
   * Subtotal das Receitas Orçamentarias
   */
	if ($iValrYDespesa > $iValrYReceita) {
	  $iValrYReceita = $iValrYDespesa;
	}
}



$iAlturaRel = $iValrYReceita+$iAlt;
/*
 * Trata a altura, para que os subtotal das primeiras colunas fiquei alinhados
 * sendo que a coluna da esquerda (despesa orçamentaria), podera vir com mais registros
 */
$oPdf->SetY($iAlturaRel);

$oPdf->setfont('arial','B',$iFonte);

$oPdf->Cell($iColunaDescr,$iAlt,setIdentacao($aLinhas[19]->nivellinha).$aLinhas[19]->descricao ,0,0,"L",'','.');
$oPdf->Cell($iColunaValor,$iAlt,db_formatar($aLinhas[19]->valor,'f')                           ,0,0,"R",0);

$oPdf->SetX(100);

$oPdf->Cell($iColunaDescr,$iAlt,setIdentacao($aLinhas[40]->nivellinha).$aLinhas[40]->descricao ,0,0,"L",'','.');
$oPdf->Cell($iColunaValor,$iAlt,db_formatar($aLinhas[40]->valor,'f')                           ,0,1,"R",0);

$oPdf->setfont('arial','',$iFonte);


$iYInicial = $oPdf->GetY();

  /**
   * Inicio das colunas: Interferencias Ativas e Passivas
   * Caso os arrays 20 e 41 tenham algum conteúdo, imprime os mesmos.
   */
if ( isset($aLinhas[20]) || isset($aLinhas[41]) ) {
  
  foreach ($aLinhas as $iIndice => $oDadosRelatorio) {
   
    /*
     * Verfica se a descrição eh um totalizador
     * caso seja atribui bold e tamanho fonte 8
     */
    if ($oDadosRelatorio->totalizar == 1) {
      $sBold = "B";
    } else {
      $sBold = "";
    }
  
    if ( in_array($iIndice,array(20,21) )) {
      
      if ($iIndice == 20) {
  
        // Titulo
        $oPdf->setfont('arial','B',$iFonte);
        $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 1, "L", 0);
      } else {
  
        // Conteúdo
        $oPdf->setfont('arial',$sBold, $iFonte);
        $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 0, "L", 0,'','.');
        $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosRelatorio->valor,'f')                              , 0, 1, "R", 0);
        
        /**
         * Verifica se é par imprimir as contas
         */      
        if ($oDadosRelatorio->desdobrar) {
          foreach ($oDadosRelatorio->contas as $oDadosContaAtivas ) {
          
            $oPdf->setfont('arial','', $iFonte);
            $iNivelLinhasAtivas = $oDadosRelatorio->nivellinha + 2;       
            $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($iNivelLinhasAtivas).ucwords(strtolower($oDadosContaAtivas->descricao)), 0, 0, "L", 0, '', '.');
            $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosContaAtivas->valor,'f')                        , 0, 1, "R", 0);          
          }
        }
      }  
      
      $iValrYReceita = $oPdf->GetY();   
    }
  
  
    // Imprime a coluna Interferencias PASSIVAS
    if ( in_array($iIndice,array(41,42) )) {
           
       if ($iIndice == 41) {
         
         $oPdf->SetY($iYInicial);
         $oPdf->SetX(100);
         $oPdf->setfont('arial','B',$iFonte);
         $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 1, "L", 0);
                
       } else {
  
         $oPdf->SetX(100);
         $oPdf->setfont('arial',$sBold, $iFonte);       
         $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 0, "L", 0,'','.');
         $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosRelatorio->valor,'f')                              , 0, 1, "R", 0);
        
         if ($oDadosRelatorio->desdobrar) {
           
           foreach ($oDadosRelatorio->contas as $oDadosContaPassiva ) {
             
             $oPdf->setfont('arial','', $iFonte);
             $oPdf->SetX(100);
             $iIdentacaoPassiva = $oDadosRelatorio->nivellinha + 2;
             $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($iIdentacaoPassiva).ucwords(strtolower($oDadosContaPassiva->descricao)), 0, 0, "L", 0, '', '.');
             $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosContaPassiva->valor,'f')                                         , 0, 1, "R", 0);
           }
         }
         
       }
      
       $iValrYDespesa = $oPdf->GetY();
    }
    
    if ($iValrYDespesa > $iValrYReceita) {
      $iValrYReceita = $iValrYDespesa;
    }
  }

  
  
  // Configurações para apresentar o SUBTOTAL
  $iAlturaRel = $iValrYReceita+$iAlt;
  $oPdf->SetY($iAlturaRel);
  
  $oPdf->setfont('arial','B',$iFonte);
  
  $oPdf->Cell($iColunaDescr,$iAlt,setIdentacao($aLinhas[22]->nivellinha).$aLinhas[22]->descricao ,0,0,"L",'','.');
  $oPdf->Cell($iColunaValor,$iAlt,db_formatar($aLinhas[22]->valor,'f')                           ,0,0,"R",0);
  
  $oPdf->SetX(100);
  
  $oPdf->Cell($iColunaDescr,$iAlt,setIdentacao($aLinhas[43]->nivellinha).$aLinhas[43]->descricao ,0,0,"L",'','.');
  $oPdf->Cell($iColunaValor,$iAlt,db_formatar($aLinhas[43]->valor,'f')                           ,0,1,"R",0);
  
  $iYInicial = $oPdf->GetY();
}

/**
 * Imprime as colunas Acrecimos Patrimoniais e Decrecimos Patrimoniais
 */

foreach ($aLinhas as $iIndice => $oDadosRelatorio) {
 
  if ($oDadosRelatorio->totalizar == 1) {
    $sBold = "B";
  } else {
    $sBold = "";
  }  
  
  // Coluna Acrescimos Patrimoniais
  if (in_array($iIndice,array(23,24))) {
    
    if ($iIndice == 23) {
      
      // Titulo
      $oPdf->setfont('arial','B',$iFonte);
      $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 1, "L", 0);      
    } else {
      
      // Imprime conteúdo
      $oPdf->setfont('arial',$sBold, $iFonte);
      $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 0, "L", 0,'','.');
      $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosRelatorio->valor,'f')                              , 0, 1, "R", 0);
      
      // Verifica se é necessário abrir contas
      if ($oDadosRelatorio->desdobrar) {
        
         foreach ($oDadosRelatorio->contas as $oDadosAcrescimo ) {
        
          $oPdf->setfont('arial','', $iFonte);
          $iIdentacaoAcrescimo = $oDadosRelatorio->nivellinha + 2;
          $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($iIdentacaoAcrescimo).ucwords(strtolower($oDadosAcrescimo->descricao)), 0, 0, "L", 0, '', '.');
          $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosAcrescimo->valor,'f')                        , 0, 1, "R", 0);          
        }
      }
    }
    $iValrYReceita = $oPdf->GetY();
  }
  
  // Coluna Descrescimos Patrimoniais
  if ( in_array($iIndice,array(44,45) )) {

    if ($iIndice == 44) {
       // Imprime Titulo
       $oPdf->SetY($iYInicial);
       $oPdf->SetX(100);
       $oPdf->setfont('arial','B',$iFonte);
       $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 1, "L", 0);
    } else {
       $oPdf->SetX(100);
       $oPdf->setfont('arial',$sBold, $iFonte);       
       $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 0, "L", 0,'','.');
       $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosRelatorio->valor,'f')                              , 0, 1, "R", 0);
      
       if ($oDadosRelatorio->desdobrar) {

         foreach ($oDadosRelatorio->contas as $oDadosDecrescimo) {
           
           $oPdf->setfont('arial','', $iFonte);
           $oPdf->SetX(100);
           $iIdentacaoDecrescimo = $oDadosRelatorio->nivellinha + 2;
           $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($iIdentacaoDecrescimo).
                                             ucwords(strtolower($oDadosDecrescimo->descricao)), 0, 0, "L", 0, '', '.');
           $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosDecrescimo->valor,'f')        , 0, 1, "R", 0);
         }
       }
    }
    
    $iValrYDespesa = $oPdf->GetY();    
  }
  
  if ($iValrYDespesa > $iValrYReceita) {
    $iValrYReceita = $iValrYDespesa;
  }
}


// Configurações para apresentar o SUBTOTAL
$iAlturaRel = $iValrYReceita+$iAlt;
$oPdf->SetY($iAlturaRel);

$oPdf->setfont('arial','B',$iFonte);
// Subtotal Acrescimos Patrimoniais
$oPdf->Cell($iColunaDescr,$iAlt,setIdentacao($aLinhas[25]->nivellinha).$aLinhas[25]->descricao ,0,0,"L",'','.');
$oPdf->Cell($iColunaValor,$iAlt,db_formatar($aLinhas[25]->valor,'f')                           ,0,0,"R",0);

$oPdf->SetX(100);
// Subtotal Descrescimos Patrimoniais
$oPdf->Cell($iColunaDescr,$iAlt,setIdentacao($aLinhas[46]->nivellinha).$aLinhas[46]->descricao ,0,0,"L",'','.');
$oPdf->Cell($iColunaValor,$iAlt,db_formatar($aLinhas[46]->valor,'f')                           ,0,1,"R",0);

$iYInicial = $oPdf->GetY();

/**
 * Imprime Colunas Receita/Despesa Extra-Orçamentária 
 */
foreach ($aLinhas as $iIndice => $oDadosRelatorio) {
  
  if ($oDadosRelatorio->totalizar == 1) {
    $sBold = "B";
  } else {
    $sBold = "";
  }
  
  // Imprime Coluna Receita Extra-Orçamentária
  if (in_array($iIndice,array(26,27,28,29,30))) {
    
    if ($iIndice == 26) {
      
      // Titulo
      $oPdf->setfont('arial','B',$iFonte);
      $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 1, "L", 0);      
    } else {
      
      // Imprime conteúdo
      $oPdf->setfont('arial',$sBold, $iFonte);
      $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 0, "L", 0,'','.');
      $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosRelatorio->valor,'f')                              , 0, 1, "R", 0);

    }
    $iValrYReceita = $oPdf->GetY();
  }
  
  // Imprime Coluna Despesa Extra-Orçamentária
  if (in_array($iIndice,array(47,48,49,50,51))) {
    if ($iIndice == 47) {
       // Imprime Titulo
       $oPdf->SetY($iYInicial);
       $oPdf->SetX(100);
       $oPdf->setfont('arial','B',$iFonte);
       $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 1, "L", 0);
    } else {
       
       $oPdf->SetX(100);
       $oPdf->setfont('arial',$sBold, $iFonte);       
       $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 0, "L", 0,'','.');
       $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosRelatorio->valor,'f')                              , 0, 1, "R", 0);
    }
    
    $iValrYDespesa = $oPdf->GetY();
  }
  
}

// Configurações para apresentar o SUBTOTAL Despesas/Receitas Extra-Orçamentárias
$iAlturaRel = $iValrYReceita+$iAlt;
$oPdf->SetY($iAlturaRel);

$oPdf->setfont('arial','B',$iFonte);
// Subtotal Acrescimos Patrimoniais
$oPdf->Cell($iColunaDescr,$iAlt,setIdentacao($aLinhas[31]->nivellinha).$aLinhas[31]->descricao ,0,0,"L",'','.');
$oPdf->Cell($iColunaValor,$iAlt,db_formatar($aLinhas[31]->valor,'f')                           ,0,0,"R",0);

$oPdf->SetX(100);
// Subtotal Descrescimos Patrimoniais
$oPdf->Cell($iColunaDescr,$iAlt,setIdentacao($aLinhas[52]->nivellinha).$aLinhas[52]->descricao ,0,0,"L",'','.');
$oPdf->Cell($iColunaValor,$iAlt,db_formatar($aLinhas[52]->valor,'f')                           ,0,1,"R",0);

$iYInicial = $oPdf->GetY();

/**
 * Imprime Colunas Saldo - Exercicio Anterior e Exercício Seguinte 
 */
foreach ($aLinhas as $iIndice => $oDadosRelatorio) {
  
  if ($oDadosRelatorio->totalizar == 1) {
    $sBold = "B";
  } else {
    $sBold = "";
  }
  
  // Imprime Coluna Saldo Exercicio Anterior
  if (in_array($iIndice,array(32,33,34,35,36))) {
    
    if ($iIndice == 32) {
      
      // Titulo
      $oPdf->setfont('arial','B',$iFonte);
      $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 1, "L", 0);      
    } else {
      
      // Imprime conteúdo
      $oPdf->setfont('arial',$sBold, $iFonte);
      $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 0, "L", 0,'','.');
      $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosRelatorio->valor,'f')                              , 0, 1, "R", 0);

    }
    $iValrYReceita = $oPdf->GetY();
  }
  
  // Imprime Coluna Despesa Extra-Orçamentária
  if (in_array($iIndice,array(53,54,55,56,57))) {
    if ($iIndice == 53) {
       // Imprime Titulo
       $oPdf->SetY($iYInicial);
       $oPdf->SetX(100);
       $oPdf->setfont('arial','B',$iFonte);
       $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 1, "L", 0);
    } else {
       
       $oPdf->SetX(100);
       $oPdf->setfont('arial',$sBold, $iFonte);       
       $oPdf->cell($iColunaDescr, $iAlt, setIdentacao($oDadosRelatorio->nivellinha).$oDadosRelatorio->descricao, 0, 0, "L", 0,'','.');
       $oPdf->cell($iColunaValor, $iAlt, db_formatar($oDadosRelatorio->valor,'f')                              , 0, 1, "R", 0);
    }

    $iValrYDespesa = $oPdf->GetY();
  }
  
  if ($iValrYDespesa > $iValrYReceita) {
    $iValrYReceita = $iValrYDespesa;
  }
}

// Configurações para apresentar o SUBTOTAL Exercicio Seguinte/Anterior
$iAlturaRel = $iValrYReceita+$iAlt;
$oPdf->SetY($iAlturaRel);

$oPdf->setfont('arial','B',$iFonte);
// Subtotal Exercicio Anterior
$oPdf->Cell($iColunaDescr,$iAlt,setIdentacao($aLinhas[37]->nivellinha).$aLinhas[37]->descricao ,0,0,"L",'','.');
$oPdf->Cell($iColunaValor,$iAlt,db_formatar($aLinhas[37]->valor,'f')                           ,0,0,"R",0);

$oPdf->SetX(100);
// Subtotal Exercicio Seguinte
$oPdf->Cell($iColunaDescr,$iAlt,setIdentacao($aLinhas[58]->nivellinha).$aLinhas[58]->descricao ,0,0,"L",'','.');
$oPdf->Cell($iColunaValor,$iAlt,db_formatar($aLinhas[58]->valor,'f')                           ,0,1,"R",0);

$iYInicial = $oPdf->GetY();

$oPdf->SetY($iYInicial);
$oPdf->setfont('arial','B', $iFonte);       
$oPdf->cell($iColunaDescr, $iAlt, setIdentacao($aLinhas[38]->nivellinha).$aLinhas[38]->descricao, 0, 0, "L", 0,'','.');
$oPdf->cell($iColunaValor, $iAlt, db_formatar($aLinhas[38]->valor,'f')                          , 0, "R", 0);      

$oPdf->SetX(100);
$oPdf->setfont('arial','B', $iFonte);       
$oPdf->cell($iColunaDescr, $iAlt, setIdentacao($aLinhas[59]->nivellinha).$aLinhas[59]->descricao, 0, 0, "L", 0,'','.');
$oPdf->cell($iColunaValor, $iAlt, db_formatar($aLinhas[59]->valor,'f')                          , 0, "R", 0);
 

if ($oPdf->GetY() > $oPdf->h - 30) {
  $oPdf->AddPage('P');
}
  
//Notas Explicativas
$oPdf->ln();
$oNotasXIII->getNotaExplicativa($oPdf, $iCodigoPeriodo);
$oPdf->ln();


//Assinaturas
$oPdf->setfont('arial', '', $iFonte);
$oNotasXIII->assinatura($oPdf, 'BG');

$oPdf->Output();

function setIdentacao($iNivel) {
  
  $sEspaco = "";
  if ($iNivel > 1) {
    $sEspaco = str_repeat("   ", $iNivel);
  }
  return $sEspaco;
}
?>