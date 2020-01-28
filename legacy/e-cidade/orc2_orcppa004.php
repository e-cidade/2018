<?
include ("fpdf151/pdf.php");
include ("libs/db_utils.php");
include ("libs/db_sql.php");
require_once("classes/db_ppadotacao_classe.php");
require_once("classes/db_ppaestimativa_classe.php");
require_once("classes/db_db_config_classe.php");

//Modificação T25780
require_once("model/ppaVersao.model.php");

$oGet = db_utils::postMemory($_GET);

//$oGet->quadroacao = 3;

$oInstituicao = InstituicaoRepository::getInstituicaoByCodigo(db_getsession("DB_instit"));

$sWhereOrgao = "";
$clppadotacao    = new cl_ppadotacao();
$clppaestimativa = new cl_ppaestimativa();
//Modificação T25780
$sListaInstit    = str_replace("-", ",", $oGet->sListaInstit);

$sWhere = " 1=1";
//Modificação T25780
$sWhere          = " and o05_ppaversao = {$oGet->ppaversao}			 						"; 

$sWhere         .= "  and o05_anoreferencia between {$oGet->anoini} and {$oGet->anofin} ";																	
//Modificação T25780
$oPPAVersao      = new ppaVersao($oGet->ppaversao);

$sTotalizar = $oGet->totalizar;
$iModelo    = $oGet->iModelo; 

if (! empty($oGet->orgao)) {
  $sWhereOrgao = " and o08_orgao in({$oGet->orgao})";  
}

$sSqlEstimativa  = " select distinct";
$sSqlEstimativa .= "       o08_orgao,       "; // Orgão
$sSqlEstimativa .= " 		   o40_descr,			  "; // Descrição Orgão
$sSqlEstimativa .= " 		   o08_unidade,		  "; // Unidade
$sSqlEstimativa .= " 		   o41_descr,			  "; // Descrição Unidade
$sSqlEstimativa .= "  		 o08_funcao,			"; // Função
$sSqlEstimativa .= " 		   o52_descr,			  "; // Descrição Função
$sSqlEstimativa .= " 	     o08_subfuncao,		"; // SubFunção 
$sSqlEstimativa .= " 		   o53_descr,			  "; // Descrição SubFunção
$sSqlEstimativa .= "  		 o08_programa,		"; // Programa
$sSqlEstimativa .= " 		   o54_descr,			  "; // Descrição Programa
$sSqlEstimativa .= " 		   o54_objsetorassociado, "; // Descrição Programa
$sSqlEstimativa .= " 		   o54_finali,			"; // Descrição dos Objetivos do Programa
$sSqlEstimativa .= " 		   o54_publicoalvo	"; // Público Alvo
$sSqlEstimativa .= "  from ppadotacao 																				   	     ";
$sSqlEstimativa .= " 	   inner join ppaestimativadespesa 	  on o08_sequencial        	    = o07_coddot			   		 ";
$sSqlEstimativa .= "	   inner join ppaestimativa        	  on o07_ppaestimativa	        = o05_sequencial		    	 ";
$sSqlEstimativa .= "     inner join orcorgao    		  	  on orcorgao.o40_anousu 	    = ".db_getsession('DB_anousu');
$sSqlEstimativa .= "	 						  		 	 and orcorgao.o40_orgao 		= ppadotacao.o08_orgao	     	 ";
$sSqlEstimativa .= "     inner join orcunidade   		  	  on orcunidade.o41_anousu	    = ".db_getsession('DB_anousu');
$sSqlEstimativa .= "							   			 and orcunidade.o41_orgao	    = ppadotacao.o08_orgao	     	 "; 
$sSqlEstimativa .= "							   		 	 and orcunidade.o41_unidade 	= ppadotacao.o08_unidade   	 	 ";
//$sSqlEstimativa .= "							   		 	 and orcunidade.o41_instit   	= ppadotacao.o08_instit   	 	 ";
$sSqlEstimativa .= "     inner join orcfuncao    		  	  on orcfuncao.o52_funcao       = ppadotacao.o08_funcao    	 	 ";
$sSqlEstimativa .= "     inner join orcsubfuncao  	  	      on orcsubfuncao.o53_subfuncao = ppadotacao.o08_subfuncao 	 	 ";
$sSqlEstimativa .= "     inner join orcprograma   	  	      on orcprograma.o54_anousu 	= ".db_getsession('DB_anousu');
$sSqlEstimativa .= "      						   			 and orcprograma.o54_programa  	= ppadotacao.o08_programa  	 	 ";
$sSqlEstimativa .= " {$sWhereOrgao}";
$sSqlEstimativa .= " where 1=1 {$sWhere}";

//Modificação T25780
$sSqlEstimativa .= " and o08_instit in({$sListaInstit})";

$sSqlEstimativa .= " order by o08_orgao";
//die($sSqlEstimativa);
$rsEstimativa 	   = db_query($sSqlEstimativa); 
$iLinhasEstimativa = pg_num_rows($rsEstimativa);
$aEstimativa	     = array();	 

for ( $iInd=0; $iInd < $iLinhasEstimativa; $iInd++ ) {
  $oDadosEstimativa = db_utils::fieldsMemory($rsEstimativa,$iInd);	

  $oDados = new stdClass();
  
  $oDados->iOrgao 	       = $oDadosEstimativa->o08_orgao;
  $oDados->sOrgao	       	 = $oDadosEstimativa->o40_descr;
  $oDados->iUnidade      	 = $oDadosEstimativa->o08_unidade;
  $oDados->sUnidade      	 = $oDadosEstimativa->o41_descr;
  $oDados->iFuncao      	 = $oDadosEstimativa->o08_funcao;
  $oDados->sFuncao 	     	 = $oDadosEstimativa->o52_descr;
  $oDados->iSubFuncao      = $oDadosEstimativa->o08_subfuncao;
  $oDados->sSubFuncao    	 = $oDadosEstimativa->o53_descr;
  $oDados->iPrograma     	 = $oDadosEstimativa->o08_programa;
  if ((int)$oInstituicao->getCodigoCliente() != 19985) {
    $oDados->sPrograma     	 = $oDadosEstimativa->o54_descr;
  } else {
    $oDados->sPrograma     	 = $oDadosEstimativa->o54_objsetorassociado;
  }
  $oDados->sObjetivo     	 = $oDadosEstimativa->o54_finali;
  $oDados->sPublicoAlvo  	 = $oDadosEstimativa->o54_publicoalvo;

  $sSqlindicadores  = "select distinct o10_indica, ";
  $sSqlindicadores .= "       o10_descr, ";
  $sSqlindicadores .= "       o10_descrunidade, ";
  $sSqlindicadores .= "       o10_obs, ";
  $sSqlindicadores .= "       o10_basegeografica ";
  $sSqlindicadores .= "  from orcindica ";
  $sSqlindicadores .= "       inner join orcindicaprograma on orcindicaprograma.o18_orcindica = o10_indica ";
  $sSqlindicadores .= "       left join orcindicaindiceesperado on orcindicaindiceesperado.o25_orcindica = orcindica.o10_indica";
  $sSqlindicadores .= " where o18_orcprograma = {$oDados->iPrograma} and o18_anousu = ".db_getsession('DB_anousu');
  $rsIndicadores    = db_query($sSqlindicadores);
  $aIndicadores     = db_utils::getColectionByRecord($rsIndicadores);
  $aIndicadorAnos					 = array();
  
  if (count($aIndicadores) > 0)  {
    
    $iTotalIndicadores = count($aIndicadores);
    for ($i = 0; $i < $iTotalIndicadores; $i++) {

      $sSqlindicadores  = "   select distinct o25_anousu, 																			 				";
      $sSqlindicadores .= "          		      o25_valor										  	    							";
      $sSqlindicadores .= "     from ppadotacao 												    	       					     		   		";
      $sSqlindicadores .= "          inner join ppaestimativadespesa 	on o08_sequencial   		    		 = o07_coddot	   		            ";
      $sSqlindicadores .= "	         inner join ppaestimativa        	on o07_ppaestimativa 	    		     = o05_sequencial 		     	    ";
      $sSqlindicadores .= "          inner join orcprograma   	  	    on orcprograma.o54_anousu 		         = ".db_getsession('DB_anousu');
      $sSqlindicadores .= "      						   			   and orcprograma.o54_programa  			 = ppadotacao.o08_programa  	 	";
      $sSqlindicadores .= "          left  join orcindicaprograma 	    on orcindicaprograma.o18_orcprograma     = orcprograma.o54_programa 	 	";
      $sSqlindicadores .= "      						   		       and orcindicaprograma.o18_anousu          = ".db_getsession('DB_anousu');
      $sSqlindicadores .= "          left  join orcindica      	        on orcindica.o10_indica  			 	 = orcindicaprograma.o18_orcindica  ";
      $sSqlindicadores .= "          left  join orcindicaindiceesperado on orcindicaindiceesperado.o25_orcindica = orcindica.o10_indica 		    ";  
      $sSqlindicadores .= "    where 1=1 {$sWhere} ";
      $sSqlindicadores .= "   and   o25_orcindica = {$aIndicadores[$i]->o10_indica}";   																									
      $sSqlindicadores .= "      and o08_orgao     = {$oDados->iOrgao}	  													     					";
      $sSqlindicadores .= "      and o08_unidade   = {$oDados->iUnidade}    													     				";
      $sSqlindicadores .= "      and o08_funcao    = {$oDados->iFuncao}	  													     					";
      $sSqlindicadores .= "      and o08_subfuncao = {$oDados->iSubFuncao}  												    	 				";
      $sSqlindicadores .= "      and o08_programa  = {$oDados->iPrograma}   													     				";	
//Modificação T25780
     $sSqlindicadores .= "      and o08_instit in({$sListaInstit})";
     $sSqlindicadores .= "      order by o25_anousu";
      $rsConsultaIndicador 			 = db_query($sSqlindicadores);  
      $iLinhasIndicador	  			 = pg_num_rows($rsConsultaIndicador);
      $oDados->iLinhasIndicador 	 = $iLinhasIndicador;
      
      
      if ( $iLinhasIndicador > 0 ) {
      	
        for ( $iSeq=0; $iSeq < $iLinhasIndicador; $iSeq++ ) {
      		
          $oDadosIndicador = db_utils::fieldsMemory($rsConsultaIndicador,$iSeq);
     	    $aIndicadorAnos[$oDadosIndicador->o25_anousu]['nValor'] = $oDadosIndicador->o25_valor;
      	  
        }
      }
      $aIndicadores[$i]->indicadoresAnos = $aIndicadorAnos;
    }
  }
  
  $oDados->aIndicadores = $aIndicadores;

  $sSqlAcoes  = "   select distinct o08_projativ, 												                                   ";
  $sSqlAcoes .= "          o55_descr, 																		                       ";
  $sSqlAcoes .= "          o55_finali, 																		                       ";
  $sSqlAcoes .= "          o05_anoreferencia,															                           ";
  $sSqlAcoes .= "          ( select sum(o28_valor) 
  							   from orcprojativprogramfisica 
  							  where o28_orcprojativ = ppadotacao.o08_projativ  
  							    and o28_anoref		= o05_anoreferencia ) as o28_valor, 			                               ";
  $sSqlAcoes .= "          o22_descrprod, 																			               ";
  $sSqlAcoes .= "          o55_descrunidade, 																		               ";
  $sSqlAcoes .= "          o55_valorunidade, 																               	       ";
  $sSqlAcoes .= "          o11_descricao,																  	    		           ";
  /**
   * Opcao do quadro de acoes sinteticas
   */
  if ($oGet->quadroacao == 1 or $oGet->quadroacao == 3) {
    
    $sSqlAcoes .= "          case when o15_tipo = 1 then round(sum(o05_valor),2) else 0 end as \"livre\",		                       ";
    $sSqlAcoes .= "          case when o15_tipo = 2 then round(sum(o05_valor),2) else 0 end as \"vinculado\"                         ";
    
  } else {
    
    $sSqlAcoes .= "          round(sum(o05_valor),2) as valor,  ";
    $sSqlAcoes .= "          o15_codigo,";                         
    $sSqlAcoes .= "          o15_descr,";                         
    $sSqlAcoes .= "          o15_tipo";                         
    
  }
  $sSqlAcoes .= "     from ppadotacao 												    	       					     		   ";
  $sSqlAcoes .= " 	       inner join ppaestimativadespesa 	       on o08_sequencial   		    = o07_coddot	                   ";
  $sSqlAcoes .= "	       inner join ppaestimativa        	  	   on o07_ppaestimativa 	    = o05_sequencial     	           ";
  $sSqlAcoes .= "          inner join orcprojativ   	   	   	   on orcprojativ.o55_projativ  = ppadotacao.o08_projativ          ";
  $sSqlAcoes .= "         								  	 	  and orcprojativ.o55_anousu    = o08_ano                          ";
  $sSqlAcoes .= "		   inner join orcproduto          		   on orcproduto.o22_codproduto = orcprojativ.o55_orcproduto 	   ";  
  $sSqlAcoes .= "		   inner join orctiporec         	 	   on orctiporec.o15_codigo     = ppadotacao.o08_recurso	 	   ";
  $sSqlAcoes .= "		   left  join ppasubtitulolocalizadorgasto on o11_sequencial 			= ppadotacao.o08_localizadorgastos ";
  $sSqlAcoes .= "    where 1=1 $sWhere								 					";
  $sSqlAcoes .= "      and o08_orgao     = {$oDados->iOrgao}	  				    ";
  $sSqlAcoes .= "      and o08_unidade   = {$oDados->iUnidade}    				    ";
  $sSqlAcoes .= "      and o08_funcao    = {$oDados->iFuncao}	  					";
  $sSqlAcoes .= "      and o08_subfuncao = {$oDados->iSubFuncao}  					";
  $sSqlAcoes .= "      and o08_programa  = {$oDados->iPrograma}   					";
  
//Modificação T25780
  $sSqlAcoes .= " and o08_instit in({$sListaInstit})";

  $sSqlAcoes .= " group by o08_projativ, 											";
  $sSqlAcoes .= " 		   o15_tipo,												";
  $sSqlAcoes .= "          o55_descr, 												";	
  $sSqlAcoes .= "          o55_finali, 												";	
  $sSqlAcoes .= "          o22_descrprod, 											";
  $sSqlAcoes .= "          o55_descrunidade,  										";
  $sSqlAcoes .= "          o55_valorunidade, 										";
  $sSqlAcoes .= "          o11_descricao,											";
  $sSqlAcoes .= "          o05_anoreferencia,										";
  $sSqlAcoes .= "          o28_valor		 										";
  if ($oGet->quadroacao == 2) {
     
    $sSqlAcoes .= "         ,o15_codigo,             ";
    $sSqlAcoes .= "         o15_descr             ";
    
  }
  $sSqlAcoes .= " order by o08_projativ, 											";
  $sSqlAcoes .= "          o05_anoreferencia;										";

  $rsConsultaAcoes 	= db_query($sSqlAcoes);  
  $iLinhasAcoes    	= pg_num_rows($rsConsultaAcoes);
  $aAcoes 		    	= array();
  $nValorTotalGrupo = 0;
  if ( $iLinhasAcoes > 0 ) {

  	for ( $iIndAcao=0; $iIndAcao < $iLinhasAcoes; $iIndAcao++ ) {
  		
  	  $oDadosAcao = db_utils::fieldsMemory($rsConsultaAcoes,$iIndAcao);

  	  $oAcao = new stdClass();
  	  
      if ((int)$oInstituicao->getCodigoCliente() == 19985 and $oGet->quadroacao == 3) {
  	    $aAcoes[$oDadosAcao->o08_projativ]['sDescricao']   = $oDadosAcao->o55_finali;
      } else {
  	    $aAcoes[$oDadosAcao->o08_projativ]['sDescricao']   = $oDadosAcao->o55_descr;
      }
  	  $aAcoes[$oDadosAcao->o08_projativ]['sLocalizador'] = $oDadosAcao->o11_descricao;
  	  $aAcoes[$oDadosAcao->o08_projativ]['sProduto']     = $oDadosAcao->o22_descrprod;
  	  $aAcoes[$oDadosAcao->o08_projativ]['sUnidade']     = $oDadosAcao->o55_descrunidade;
  	  $aAcoes[$oDadosAcao->o08_projativ]['nUnidade']     = $oDadosAcao->o55_valorunidade;
  	  if ($oGet->quadroacao == 2 ) {
  	     $aAcoes[$oDadosAcao->o08_projativ]['sFinalidade']     = $oDadosAcao->o55_finali;
  	  }
  	  if ($iModelo == 2) {
  	    
  	    if ($oDadosAcao->o05_anoreferencia != $oGet->iAno) {
  	      
  	      $oDadosAcao->vinculado = "";
  	      $oDadosAcao->livre     = "";
  	      $oDadosAcao->o28_valor = "";
  	      $oDadosAcao->valor = "";
  	      
  	    }
  	  }
  	  $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nQuantFisica'] = $oDadosAcao->o28_valor;
  	  if ($oGet->quadroacao == 1 or $oGet->quadroacao == 3) {
  	    
    	  if (isset( $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nLivre'])) {
   	      $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nLivre']     += $oDadosAcao->livre;
    	  } else {
    	    $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nLivre']      = $oDadosAcao->livre;
    	  }
    	  if (isset( $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nVinculado'])) {
   	      $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nVinculado'] += $oDadosAcao->vinculado;
    	  } else { 
    	    $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia]['nVinculado']  = $oDadosAcao->vinculado;
    	  }
    	  
    	  if ( isset($aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]) ) {
  	      $aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]['nLivre']     += $oDadosAcao->livre;     	  
  	      $aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]['nVinculado'] += $oDadosAcao->vinculado;
    	  } else {
  	      $aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]['nLivre']      = $oDadosAcao->livre;        
  	      $aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]['nVinculado']  = $oDadosAcao->vinculado;
    	  }
        
        if ( isset($aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]) ) {
          
  	  	  $aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]['nLivre']     += $oDadosAcao->livre;
  	  	  $aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]['nVinculado'] += $oDadosAcao->vinculado;
  	  	  
        } else {
          
          $aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]['nLivre']      = $oDadosAcao->livre;
          $aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]['nVinculado']  = $oDadosAcao->vinculado;
                	
        }
        $nValorTotalGrupo += $oDadosAcao->livre+$oDadosAcao->vinculado;
  	  } else {

        /**
         * Totalizador por recurso
         */ 
  	    $sIndex = $oDadosAcao->o15_codigo."-".$oDadosAcao->o15_descr;  	    
  	    if (isset($aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia][$sIndex])) {
  	      $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia][$sIndex] += $oDadosAcao->valor;
  	    } else {
  	      $aAcoes[$oDadosAcao->o08_projativ]['aExercicio'][$oDadosAcao->o05_anoreferencia][$sIndex] = $oDadosAcao->valor;
  	    }
  	    
  	    if ( isset($aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]) ) {
  	       
  	      if ($oDadosAcao->o15_tipo == 1) {
           $aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]['nLivre']     += $oDadosAcao->valor;
  	      } else {        
            $aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]['nVinculado'] += $oDadosAcao->valor;
  	      }
        } else {
          
          $aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]['nLivre']     = 0;
          $aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]['nVinculado'] = 0;
          
          if ($oDadosAcao->o15_tipo == 1) { 
            $aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]['nLivre']      = $oDadosAcao->valor;
          } else {        
            $aTotalOrgao[$oDados->iOrgao][$oDadosAcao->o05_anoreferencia]['nVinculado']  = $oDadosAcao->valor;;
          }
           
        }
        
        if ( isset($aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]) ) {
          
          if ($oDadosAcao->o15_tipo == 1) {
            $aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]['nLivre']     += $oDadosAcao->valor;
          } else {
            $aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]['nVinculado'] += $oDadosAcao->valor;
          }
          
        } else {
          
         $aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]['nLivre']     = 0;
         $aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]['nVinculado'] = 0;
          
          if ($oDadosAcao->o15_tipo == 1) {
            $aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]['nLivre']      = $oDadosAcao->valor;
          } else {
            $aTotalUnidade[$oDados->iOrgao][$oDados->iUnidade][$oDadosAcao->o05_anoreferencia]['nVinculado']  = $oDadosAcao->valor;
          }            
        }
        $nValorTotalGrupo += $oDadosAcao->valor;
  	  }
  	}
  }
  
  $oDados->iTotalEstimativa = $nValorTotalGrupo;
  $oDados->iLinhasAcoes     = $iLinhasAcoes;
  $oDados->aAcoes 		      = $aAcoes;
  $aEstimativa[]            = $oDados;
  
}

if ($iModelo == 1) {
  
  $head2  = "ANEXO DE OBJETIVOS , DIRETRIZES E METAS";
  $head3  = "PPA - {$oGet->anoini} - {$oGet->anofin}";

  //Modificação T25780
  $head4  = "Versão: ".$oPPAVersao->getVersao()."(".db_formatar($oPPAVersao->getDatainicio(),"d").")";
  //
  
} else {
  $head2  = "LEI DE DIRETRIZES ORÇAMENTÁRIAS - EXERCÍCIO DE $oGet->iAno";
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage("L");
$pdf->SetAutoPageBreak(false,1);
$pdf->setfillcolor(255);

if ( $oGet->imprimerodape == "n" ) {
  $pdf->imprime_rodape = false;
}

$pdf->setfont('arial','B', 8);

$iOrgaoAnt   = 0;
$iUnidadeAnt = 0;
$iPagina     = 0;
$alt         = "4";
$mostra      = "";
$Ativs       = array();

 /*********
 *Faz a verificação das atividades que poderao ser mostradas
 * - Deverá  ter valor
 * - Deverão ter ações
 **********/
foreach ( $aEstimativa as $iInd => $oEstimativa ) {
  foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ) { 
    
    if ($oGet->quadroacao == 1 or $oGet->quadroacao == 3) { 
      
      foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
        if ($aDadosExerc['nLivre']+$aDadosExerc['nVinculado'] > 0 && !in_array($iProjAtiv,$Ativs)) {
          array_push($Ativs,$iProjAtiv);
        }
      }
    } else {
      
      foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
        if (array_sum($aDadosExerc) > 0 && !in_array($iProjAtiv,$Ativs)) {
          array_push($Ativs,$iProjAtiv);
        }
      }
    }
  }
}
 
foreach ( $aEstimativa as $iInd => $oEstimativa ) {

 /********
 *Faz a verificação dos registros que poderão ser mostrados	
 * - Deverá  ter valor
 * - Deverão ter ações 
 *********/
  $mostra = 0;
  
  foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ) { 
    foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
      $mostra += array_sum($aDadosExerc);
    }
  }

  
  if ( $oEstimativa->iLinhasAcoes > 0 && $mostra > 0 && $oEstimativa->iTotalEstimativa > 0) {
  
		if ( $iOrgaoAnt != $oEstimativa->iOrgao && $iOrgaoAnt != 0){
			
			if ( $sTotalizar == "u") {
	      mostrarTotal($pdf,$alt,$aTotalUnidade[$iOrgaoAnt][$iUnidadeAnt],$sTotalizar);			
			}
			
		  mostrarTotal($pdf,$alt,$aTotalOrgao[$iOrgaoAnt],"o");
		  $pdf->addpage("L");
		  
		} else {
		  
			if ( $sTotalizar == "u") {
			  if ( $iUnidadeAnt != $oEstimativa->iUnidade  && $iUnidadeAnt != 0 ){
		      mostrarTotal($pdf,$alt,$aTotalUnidade[$oEstimativa->iOrgao][$iUnidadeAnt],$sTotalizar);
			    $pdf->addpage("L");  
			  }
			}
	  
		}
  
	  validaNovaPagina($pdf); 	
	  
	  $pdf->Cell(32,$alt,"Orgão"    										 		                          ,"TB",0,"L",0);
	  $pdf->Cell(0 ,$alt,":  {$oEstimativa->iOrgao} - {$oEstimativa->sOrgao}"	 	      ,"TB",1,"L",0);
    if ((int)$oInstituicao->getCodigoCliente() != 19985) {
      $pdf->Cell(32,$alt,"Unidade"  										  	  	                      ,"TB",0,"L",0);
      $pdf->Cell(0 ,$alt,":  {$oEstimativa->iUnidade} - {$oEstimativa->sUnidade}"	    ,"TB",1,"L",0);
    }
	  $pdf->Cell(32,$alt,"Função"   												                          ,"TB",0,"L",0);
	  $pdf->Cell(0 ,$alt,":  {$oEstimativa->iFuncao} - {$oEstimativa->sFuncao}" 	    ,"TB",1,"L",0);
	  $pdf->Cell(32,$alt,"Subfunção"											  	                        ,"TB",0,"L",0);
	  $pdf->Cell(0 ,$alt,":  {$oEstimativa->iSubFuncao} - {$oEstimativa->sSubFuncao}" ,"TB",1,"L",0);
	  $pdf->Cell(32,$alt,"Programa do Governo"								 		                    ,"TB",0,"L",0);
	  $pdf->Cell(0 ,$alt,":  ".str_pad($oEstimativa->iPrograma,4,"0",STR_PAD_LEFT)." - {$oEstimativa->sPrograma}"   ,"TB",1,"L",0);
	
	  $pdf->Cell(32,$alt,"Objetivos "                           ,"T",0,"L",0);
	  $pdf->setfont('arial','' , 8);
	  $pdf->multicell(0,$alt,": ".$oEstimativa->sObjetivo 		  ,"TB","L",1);
	  $pdf->setfont('arial','B', 8);
	  $pdf->Cell(32,$alt,"Público Alvo"                         ,"TB",0,"L",0);
	  $pdf->setfont('arial','' , 8);
	  $pdf->multicell(0,$alt,": ".$oEstimativa->sPublicoAlvo	  ,"TB","L",1);
	  $pdf->setfont('arial','B', 8);
 
	  foreach ($oEstimativa->aIndicadores as $oIndicador) {	
	
		  validaNovaPagina($pdf);
		  
		  $pdf->Cell(0,$alt,""                                                        ,"TB",1,"L",0);
		  $pdf->Cell(75,$alt,"Nome do Indicador estabelecido no plano plurianual:"    ,"TBR",0,"L",0);
		  if ((int)$oInstituicao->getCodigoCliente() == 19985) {
		    $pdf->Cell(0,$alt,$oIndicador->o10_basegeografica  ,"TB", 1, "L", 0);
		  } else {
  		  $pdf->Cell(0,$alt,$oIndicador->o10_descr, "TB", 1, "L", 0);
		  }
		  
		  if ($iModelo == 1) {
		    
		    $pdf->Cell(75,$alt,"Unidade de medida do indicador de desempenho:"		      ,"TBR",0,"L",0);
		    $pdf->Cell(0 ,$alt,$oIndicador->o10_descrunidade 					                  ,"TB",1,"L",0);
		    
		    $pdf->Cell(75,$alt,"Índice de Referência:"		      ,"TBR",0,"L",0);
		    $pdf->Cell(0 ,$alt,$oIndicador->o10_obs 	          ,"TB",1,"L",0);

		    $iPosYAntes  = $pdf->GetY();
		    $pdf->multicell(187,$alt*2,"Indicador (índice) pretendido ao final de cada exercício :","TB","L",1);
		    $iPosYDepois = $pdf->GetY();
		    $iPosXIndicador = 162;
		      
		    foreach ( $oIndicador->indicadoresAnos as $iAno => $aDadosIndicador ) {
		          	
		      $iPosXIndicador = $iPosXIndicador + 25 ;
		      $pdf->SetXY($iPosXIndicador,$iPosYAntes);
		      $pdf->Cell(25,$alt,$iAno ,"TBL",1,"C",0);
		      $iAltValor = ( $iPosYDepois - $iPosYAntes ) - $alt ;
		      $pdf->SetX($iPosXIndicador);
		      $pdf->setfont('arial','', 8);
		      $pdf->multicell(25,$iAltValor,db_formatar($aDadosIndicador["nValor"],"f"),"TBL","R",1);
		      $pdf->setfont('arial','B', 8);
		            
		    }  
		      
		    for ($iSeq = $oEstimativa->iLinhasIndicador; $iSeq < 4; $iSeq++ ) {
		      	
		      $iPosXIndicador = $iPosXIndicador + 25 ;
		      	
		      $pdf->SetXY($iPosXIndicador,$iPosYAntes);
		      $pdf->Cell(25,$alt,"","TBL",1,"C",0);
		      $iAltValor = ( $iPosYDepois - $iPosYAntes ) - $alt ;
		      $pdf->SetX($iPosXIndicador);
		      $pdf->setfont('arial','', 8);
		      $pdf->multicell(25,$iAltValor,db_formatar("","f"),"TBL","L",1);
		      $pdf->setfont('arial','B', 8);
		        
		    }
		  }
	  }

	  $iOrgaoAnt   = $oEstimativa->iOrgao;
    $iUnidadeAnt = $oEstimativa->iUnidade;
  
  }
  
  // Cabeçalho Descrição das Ações 
  if($mostra > 0 && $oEstimativa->iTotalEstimativa > 0) {
  	
    $pdf->ln();  
    validaNovaPagina($pdf);
    $pdf->Cell(0,$alt*2,"DESCRIÇÃO DAS AÇÕES","TB",1,"C",0);
	
  }

	foreach ( $oEstimativa->aAcoes as $iProjAtiv => $aDadosAcoes ) {
	
	  $nTotalValorAcao = 0;
	  
	  foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
	    foreach ($aDadosExerc as $sIndice => $nValor) {
	      if ($sIndice != "nQuantFisica"){
	        $nTotalValorAcao += $nValor;
	      }
	    }
	  }
	  
	  if ($nTotalValorAcao > 0) {
      
	    validaNovaPagina($pdf);   	  	 
	    if ($oGet->quadroacao == 1) {
  	
  	    $pdf->Cell(78,$alt,"Ação"	  						              	,"TBR",0,"C",0);
  			$pdf->Cell(20,$alt,"Produto"  						              ,"TR" ,0,"C",0);
  			$pdf->Cell(20,$alt,"Unidade de"						              ,"TR" ,0,"C",0);
  			$pdf->Cell(20,$alt,"Preço"	 							              ,"TR" ,0,"C",0);
  			$pdf->Cell(40,$alt,"Meta"							                  ,"TBR",0,"C",0);
  			$pdf->Cell(99,$alt,"Custo direto previsto p/ o execício","TB" ,1,"C",0);
  			
  			
  			$pdf->Cell(26,$alt,"Código"	  		 			                ,"TR" ,0,"C",0);
  			$pdf->Cell(26,$alt,"Título"  		  			                ,"TR" ,0,"C",0);
  			$pdf->Cell(26,$alt,"Subtítulo/"		 			                ,"TR" ,0,"C",0);
  			$pdf->Cell(20,$alt,""	 			 				                    ,"R"  ,0,"C",0);
  			$pdf->Cell(20,$alt,"Medida"	 		  			                ,"R"  ,0,"C",0);
  			$pdf->Cell(20,$alt,"Unitário"	 	 				                ,"R"  ,0,"C",0);
  			$pdf->Cell(20,$alt,"Ano"	 		  				                ,"TR" ,0,"C",0);
  			$pdf->Cell(20,$alt,"Quant."	 		  			                ,"TR" ,0,"C",0);
  			$pdf->Cell(75,$alt,"Fonte de Recurso" 	                ,"TBR",0,"C",0);
  			$pdf->Cell(24,$alt,"Total"			  			                ,"TB" ,1,"C",0);
  			
  			
  			$pdf->Cell(26,$alt,""		   					 	                  ,"BR" ,0,"C",0);
  			$pdf->Cell(26,$alt,""  		    					                ,"BR" ,0,"C",0);
  			$pdf->Cell(26,$alt,"Localizador"				                ,"BR" ,0,"C",0);
  			$pdf->Cell(20,$alt,""		    						                ,"BR" ,0,"C",0);
  			$pdf->Cell(20,$alt,""		    						                ,"BR" ,0,"C",0);
  			$pdf->Cell(20,$alt,""	 	    						                ,"BR" ,0,"C",0);
  			$pdf->Cell(20,$alt,""	 	   							                ,"BR" ,0,"C",0);
  			$pdf->Cell(20,$alt,"Física"	    				                ,"BR" ,0,"C",0);
  			$pdf->Cell(40,$alt,"Livres"	   					                ,"TBR",0,"C",0);
  			$pdf->Cell(35,$alt,"Vinculados"    			                ,"TBR",0,"C",0);
  			$pdf->Cell(24,$alt,""		   							                ,"TB" ,1,"C",0);
  			
  			$iPosYAntes  = $pdf->GetY();
  			
  			$pdf->setfont('arial','', 8);
  			$iAltDescr = ($alt);
  			
  			//$pdf->multicell(26,$iAltDescr,str_pad(substr($iProjAtiv,0,100),100," ",STR_PAD_BOTH),"TR","J",1);
  			$pdf->multicell(26,$iAltDescr,str_pad($iProjAtiv, 4, "0", STR_PAD_LEFT),"TR","J",1);
  			$pdf->SetXY(36,$iPosYAntes);
  			$pdf->setfont('arial','', 6);
  			$pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sDescricao'],0,40),70," ",STR_PAD_RIGHT),"TRL","L",1);
  			$pdf->setfont('arial','', 8);
  			$pdf->SetXY(62,$iPosYAntes);
  			$pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sLocalizador'],0,45),90," ",STR_PAD_RIGHT),"TLR","J",1);
  			$pdf->SetXY(88,$iPosYAntes);
  			$pdf->multicell(20,$iAltDescr,substr($aDadosAcoes['sProduto'],0,45),"TLR","J",1);
  			$pdf->SetXY(108,$iPosYAntes);
  			$pdf->multicell(20,$iAltDescr,str_pad(substr($aDadosAcoes['sUnidade'],0,45),70," ",STR_PAD_RIGHT),"TLR","J",1);
  			$pdf->SetXY(128,$iPosYAntes);
  			$pdf->multicell(20,$iAltDescr,str_pad(substr($aDadosAcoes['nUnidade'],0,45),70," ",STR_PAD_RIGHT),"TLR","J",1);
  			
  			$pdf->SetY($iPosYAntes);
  			
  			$nTotalLivre       = 0;
  			$nTotalVinculado   = 0;
  			$nTotalGeral       = 0;	
  			$iLinhasExerc	     = 0;
  			
  			foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
  			  	
  			  $nTotalRecurso    = $aDadosExerc['nLivre'] + $aDadosExerc['nVinculado'];
  			  $nTotalLivre     += $aDadosExerc['nLivre'];
  			  $nTotalVinculado += $aDadosExerc['nVinculado'];
  		 	  $nTotalGeral	   += $nTotalRecurso;	  	 
  		 	  
  		 	  if ($iModelo == 2) {
  		 	    if ($iExercicio != $oGet->iAno) {
  		 	      $iExercicio = "";
  		 	    }
  		 	  }
  		 	  
  			  $pdf->SetX(148);
  			  $pdf->Cell(20,$alt,$iExercicio  			     		           	   	,"BR" ,0,"C",0);
  			  $pdf->Cell(20,$alt,$aDadosExerc['nQuantFisica']			          ,"BR" ,0,"C",0);
  			  $pdf->Cell(40,$alt,db_formatar($aDadosExerc['nLivre'],"f")	  ,"TBR",0,"R",0);
  			  $pdf->Cell(35,$alt,db_formatar($aDadosExerc['nVinculado'],"f"),"TBR",0,"R",0);
  			  $pdf->Cell(24,$alt,db_formatar($nTotalRecurso,"f")			      ,"TB" ,1,"R",0);  
  		  	$iLinhasExerc++;
  		
  			}	
  		  
  			$pdf->SetY($iPosYAntes);
  			
      	for ($iSeq=0; $iSeq < 4; $iSeq++ ) {
  		
    	    $sBorda = "";
    	    
    	    if ($iSeq == 3) {
    	      $sBorda = "B";
    	    }
    	    
    	    $pdf->Cell(26,$alt,"",$sBorda ,0);
    	    $pdf->Cell(26,$alt,"",$sBorda ,0);
    	    $pdf->Cell(26,$alt,"",$sBorda ,0);
    	    $pdf->Cell(20,$alt,"",$sBorda ,0);
          $pdf->Cell(20,$alt,"",$sBorda ,0);
          $pdf->Cell(20,$alt,"",$sBorda ,1);
        }
  			
  			
  			$pdf->setfont('arial','B', 8);	
  			$pdf->Cell(178,$alt,"Total da ação para os exercícios" ,"TBR",0,"R",0);
  			$pdf->setfont('arial','' , 8);
  			$pdf->Cell(40 ,$alt,db_formatar($nTotalLivre,"f")	     ,"TBR",0,"R",0);
  			$pdf->Cell(35 ,$alt,db_formatar($nTotalVinculado,"f")  ,"TBR",0,"R",0);
  			$pdf->Cell(24 ,$alt,db_formatar($nTotalGeral,"f")	     ,"TBL",1,"R",0);  	
  	    $pdf->setfont('arial','B', 8);
  	     
  	  } elseif ($oGet->quadroacao == 3) {

        $iSomaColuna = 10;

  	    $pdf->Cell(120,$alt,"Ação"	  						              	,"TBR",0,"C",0);
    	  $pdf->Cell(30,$alt,"Produto"  						              ,"TR" ,0,"C",0);
    		$pdf->Cell(22,$alt,"Unidade de"						              ,"TR" ,0,"C",0);
  			$pdf->Cell(30,$alt,"Meta"							                  ,"TBR",0,"C",0);
  			$pdf->Cell(75,$alt,"Custo direto previsto p/ o execício","TB" ,1,"C",0);
  			 			
  			$pdf->Cell(10,$alt,"Código"	  		 			                ,"TR" ,0,"C",0);
  			$pdf->Cell(84,$alt,"Título"  		  			                ,"TR" ,0,"C",0);
  			$pdf->Cell(26,$alt,"Subtítulo/"		 			                ,"TR" ,0,"C",0);
    		$pdf->Cell(30,$alt,""	 			 				                    ,"R"  ,0,"C",0);
    		$pdf->Cell(22,$alt,"Medida"	 		  			                ,"R"  ,0,"C",0);
  			$pdf->Cell(10,$alt,"Ano"	 		  				                ,"TR" ,0,"C",0);
  			$pdf->Cell(20,$alt,"Quant."	 		  			                ,"TR" ,0,"C",0);
  			$pdf->Cell(50,$alt,"Fonte de Recurso" 	                ,"TBR",0,"C",0);
  			$pdf->Cell(24,$alt,"Total"			  			                ,"TB" ,1,"C",0);

  			$pdf->Cell(10,$alt,""		   					 	                  ,"BR" ,0,"C",0);
  			$pdf->Cell(84,$alt,""  		    					                ,"BR" ,0,"C",0);
  			$pdf->Cell(26,$alt,"Localizador"				                ,"BR" ,0,"C",0);
    		$pdf->Cell(30,$alt,""		    						                ,"BR" ,0,"C",0);
    		$pdf->Cell(22,$alt,""	 	   							                ,"BR" ,0,"C",0);
  			$pdf->Cell(10,$alt,""	 	    						                ,"BR" ,0,"C",0);
  			$pdf->Cell(20,$alt,"Física"	    				                ,"BR" ,0,"C",0);
  			$pdf->Cell(25,$alt,"Livres"	   					                ,"TBR",0,"C",0);
  			$pdf->Cell(25,$alt,"Vinculados"    			                ,"TBR",0,"C",0);
  			$pdf->Cell(25,$alt,""		   							                ,"TB" ,1,"C",0);
  			
  			$iPosYAntes  = $pdf->GetY();
  			
  			$pdf->setfont('arial','', 8);
  			$iAltDescr = ($alt);
  			
  			//$pdf->multicell(26,$iAltDescr,str_pad(substr($iProjAtiv,0,100),100," ",STR_PAD_BOTH),"TR","J",1);
  			$pdf->multicell(10,$iAltDescr,str_pad($iProjAtiv, 4, "0", STR_PAD_LEFT),"TR","J",1);
  			$pdf->SetXY(20,$iPosYAntes);
  			$pdf->setfont('arial','', 8);
			  $pdf->multicell(84,$iAltDescr,str_pad(substr($aDadosAcoes['sDescricao'],0,300),70," ",STR_PAD_RIGHT),"TRL","L",1);
  			$pdf->setfont('arial','', 8);
  			$pdf->SetXY(62+42,$iPosYAntes);
  			$pdf->multicell(26,$iAltDescr,str_pad(substr($aDadosAcoes['sLocalizador'],0,45),90," ",STR_PAD_RIGHT),"TLR","J",1);
  			$pdf->SetXY(88+42,$iPosYAntes);
        $pdf->multicell(30,$iAltDescr,substr($aDadosAcoes['sProduto'],0,45),"TLR","J",1);
        $pdf->SetXY(118+42,$iPosYAntes);
        $pdf->multicell(22,$iAltDescr,str_pad(substr($aDadosAcoes['sUnidade'],0,45),70," ",STR_PAD_RIGHT),"TLR","J",1);
        $pdf->SetXY(118+42,$iPosYAntes);
  			
  			$pdf->SetY($iPosYAntes);
  			
  			$nTotalLivre       = 0;
  			$nTotalVinculado   = 0;
  			$nTotalGeral       = 0;	
  			$iLinhasExerc	     = 0;
  			
  			foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
  			  	
  			  $nTotalRecurso    = $aDadosExerc['nLivre'] + $aDadosExerc['nVinculado'];
  			  $nTotalLivre     += $aDadosExerc['nLivre'];
  			  $nTotalVinculado += $aDadosExerc['nVinculado'];
  		 	  $nTotalGeral	   += $nTotalRecurso;	  	 
  		 	  
  		 	  if ($iModelo == 2) {
  		 	    if ($iExercicio != $oGet->iAno) {
  		 	      $iExercicio = "";
  		 	    }
  		 	  }
  		 	  
  			  $pdf->SetX(136+46);
  			  $pdf->Cell(10,$alt,$iExercicio  			     		           	   	,"LBR" ,0,"C",0);
  			  $pdf->Cell(20,$alt,$aDadosExerc['nQuantFisica']			          ,"BR" ,0,"C",0);
  			  $pdf->Cell(25,$alt,db_formatar($aDadosExerc['nLivre'],"f")	  ,"TBR",0,"R",0);
  			  $pdf->Cell(25,$alt,db_formatar($aDadosExerc['nVinculado'],"f"),"TBR",0,"R",0);
  			  $pdf->Cell(25,$alt,db_formatar($nTotalRecurso,"f")			      ,"TB" ,1,"R",0);  
  		  	$iLinhasExerc++;
  		
  			}	
  		  
  			$pdf->SetY($iPosYAntes);
  			
      	for ($iSeq=0; $iSeq < 4; $iSeq++ ) {
  		
    	    $sBorda = "";
    	    
    	    if ($iSeq == 3) {
    	      $sBorda = "B";
    	    }
    	    
    	    $pdf->Cell(26,$alt,"",$sBorda ,0);
    	    $pdf->Cell(26,$alt,"",$sBorda ,0);
    	    $pdf->Cell(26,$alt,"",$sBorda ,0);
    	    $pdf->Cell(30,$alt,"",$sBorda ,0);
          $pdf->Cell(30,$alt,"",$sBorda ,0);
          $pdf->Cell(20,$alt,"",$sBorda ,1);

        }
  			
  			$pdf->setfont('arial','B', 8);
  			$pdf->Cell(156+46,$alt,"Total da ação para os exercícios" ,"TBR",0,"R",0);
  			$pdf->setfont('arial','' , 8);
  			$pdf->Cell(25,$alt,db_formatar($nTotalLivre,"f")	     ,"TBR",0,"R",0);
  			$pdf->Cell(25,$alt,db_formatar($nTotalVinculado,"f")  ,"TBR",0,"R",0);
  			$pdf->Cell(25,$alt,db_formatar($nTotalGeral,"f")	     ,"TBL",1,"R",0);  	
  	    $pdf->setfont('arial','B', 8);

  	  } elseif ($oGet->quadroacao == 2) {
  	    
  	    /**
  	     * Quadro da descriçao da açao
  	     */
  	    $pdf->setfont('arial','B', 8);
  	    $pdf->cell(118,$alt, 'Ação',1,0,"C");
  	    $pdf->cell(40,$alt, '',1,0,"C");
  	    $pdf->cell(40,$alt, '',1,0,"C");
  	    $pdf->cell(40,$alt, '',1,0,"C");
  	    $pdf->cell(40,$alt, '',1,1,"C");
  	    $pdf->cell(10,$alt, 'Código',1,0,"C");
  	    $pdf->cell(108,$alt, 'Título',1,0,"C");
  	    $pdf->cell(40,$alt, 'Subtítulo/Localizador',1,0,"C");
  	    $pdf->cell(40,$alt, 'Produto',1,0,"C");
  	    $pdf->cell(40,$alt, 'Unidade de Medida',1,0,"C");
  	    $pdf->cell(40,$alt, 'Preço Unitário',1,1,"C");
        $pdf->setfont('arial','', 7);
        $pdf->cell(10, $alt, str_pad($iProjAtiv, 4, "0", STR_PAD_LEFT),1,0,"R");
        $pdf->cell(108,$alt, $aDadosAcoes['sDescricao'],1,0,"L");
        $pdf->cell(40, $alt, substr($aDadosAcoes['sLocalizador'],0,25),1,0,"L");
        $pdf->cell(40, $alt, substr($aDadosAcoes['sProduto'],0,25),1,0,"L");
        $pdf->cell(40, $alt, substr($aDadosAcoes['sUnidade'],0,25),1,0,"C");
        $pdf->cell(40, $alt, substr($aDadosAcoes['nUnidade'],0,25),1,1,"C");
        if ($aDadosAcoes['sFinalidade'] != "") {

          $pdf->setfont('arial','b', 8);
          $pdf->cell(20,$alt,"Finalidade:",0,0);
          $pdf->setfont('arial','', 7);
    	    $texto = $pdf->Row_multicell(array('','','',stripslashes($aDadosAcoes['sFinalidade']),'',''),
                                                $alt,false,5,0,true,true,3,($pdf->h - 25));
         if ($texto != "") {
  
           $pdf->addpage("L");
           $pdf->setfont('arial','', 8);
           $pdf->cell(60,$alt, "",0,0,"L");
           $pdf->multicell(210,$alt, $texto, 0,"L");
           
          }
          $pdf->line(10,$pdf->getY(),288,$pdf->getY());
        } 
  	   /**
         * Montamos a lista de recursos existentes nos exercicios
         */
        $aRecursos = array();
        foreach ( $aDadosAcoes['aExercicio'] as $iExercicio => $aDadosExerc ) {
         
          foreach ($aDadosExerc as $sIndice => $nValor) {
            
            if ($sIndice != "nQuantFisica") {
               $aRecursos[$sIndice][$iExercicio] = $nValor;          
             }
           }
        }
        /**
         * Verifica a altura do quadro, e faz o controle da quabre de página
         */
        $iTamanhaQuadroEstimativas = (count($aRecursos)*$alt)+20;
        validaNovaPagina($pdf, $iTamanhaQuadroEstimativas);
        /**
         * Inicio da Descrição dos Recursos
         */ 
        $pdf->ln();
        $pdf->setfont('arial','B', 8);
        $pdf->cell(100,$alt, 'Metas Físicas',1,0,"C");
        $pdf->cell(178,$alt, 'Metas Financeiras',1,1,"C");
        $iAlturaInicial = $pdf->GetY();
        
        foreach ($aDadosAcoes['aExercicio'] as $iExercicio => $aDados) {
          $pdf->cell(25,$alt*2, $iExercicio,1,0,"C");
        }
        $pdf->ln();
         
  	    foreach ($aDadosAcoes['aExercicio'] as $iExercicio => $aDados) {
          $pdf->cell(25,$alt, $aDados["nQuantFisica"],"LR",0,"C");
        }
        
        
        $pdf->setXY(110,$iAlturaInicial);
        $pdf->cell(78, $alt*2, "Recursos",1,0,"C");
        $pdf->cell(100, $alt, "Exercícios",1,0,"C");
        $pdf->setXY(188,$iAlturaInicial+$alt);
  	    foreach ($aDadosAcoes['aExercicio'] as $iExercicio => $aDados) {
          $pdf->cell(25,$alt, $iExercicio,1,0,"C");
        }
        $pdf->ln();
        $iLinhasExerc  = 0; 
        $pdf->setfont('arial','', 7);
        /**
         * Escrevemos os Recursos no relatorio
         */
        foreach ( $aRecursos as $sRecurso => $aAnos ) {
          $pdf->setx(110);
          $pdf->cell(78, $alt, $sRecurso,1,0,"L");
          foreach ($aAnos as $iAno => $nValor ) {
            $pdf->cell(25, $alt, db_formatar($nValor,"f"),1,0,"R");
          }
          $pdf->ln();
        }
        /**
         * Criamos linhas para completar o quadro das metas físicas
         */
        /*
         * 1ª linha
         */
        $pdf->Line(10,$iAlturaInicial,10,$pdf->GetY());
        /*
         * 2ª linha
         */
        $pdf->Line(35,$iAlturaInicial,35,$pdf->GetY());
        /*
         * 3ª linha
         */
        $pdf->Line(60,$iAlturaInicial,60,$pdf->GetY());
        /*
         * 4ª linha
         */
        $pdf->Line(85,$iAlturaInicial,85,$pdf->GetY());
        /*
         * linha vertical
         */
        $pdf->Line(10,$pdf->GetY(),188,$pdf->GetY());
        $pdf->ln(); 
      }
      $pdf->setfont('arial','B', 8);
	  }
	}

	$pdf->Ln($alt*2); 
	$mostra = "";
 
}

if ( $sTotalizar == "u") {
  mostrarTotal($pdf,$alt,$aTotalUnidade[$iOrgaoAnt][$iUnidadeAnt],$sTotalizar);     
}
    
mostrarTotal($pdf,$alt,$aTotalOrgao[$iOrgaoAnt],"o");
if ((int)$oInstituicao->getCodigoCliente() != 19985) {
  $pdf->addpage("L");
} else {
  $pdf->ln(10);
}

mostrarTotalGeral($pdf,$alt,$aTotalOrgao);
    
$pdf->Output();

function validaNovaPagina($pdf, $altura = 40){
	
 if ($pdf->getY() > $pdf->h - $altura){
    $alt = 4;
    $pdf->addpage("L");
  }	
	
}

function mostrarTotal($pdf,$alt,$aExercDados,$sTipo){
  
  validaNovaPagina($pdf);
  
  if ( $sTipo == 'o') {
  	$sDescricao = 'DO ORGÃO';
  } else if ( $sTipo == 'u' ) {
  	$sDescricao = 'DA UNIDADE';
  }
  
  $nTotalLivre     = 0;
  $nTotalVinculado = 0; 
  $nTotalGeral     = 0; 
  
  $pdf->Cell(138,$alt,""          ,"TBR",0,"C",0);
  $pdf->Cell(40 ,$alt,"Ano"       ,"TBR",0,"C",0);
  $pdf->Cell(40 ,$alt,"Livres"    ,"TBR",0,"C",0);
  $pdf->Cell(35 ,$alt,"Vinculados","TBR",0,"C",0);
  $pdf->Cell(24 ,$alt,"Total"     ,"TB" ,1,"C",0);  
  
  $iY = $pdf->getY();
  $pdf->Cell(138,$alt*4," TOTAL {$sDescricao}","TBR",1,"C",0);
  $pdf->setY($iY);

  foreach ( $aExercDados as $iExercicio => $aDados ) {
      
    $nTotalColuna     = $aDados['nLivre'] + $aDados['nVinculado'];
    $nTotalLivre     += $aDados['nLivre'];
    $nTotalVinculado += $aDados['nVinculado'];
    $nTotalGeral     += $nTotalColuna;
      
    $pdf->SetX(148);
    $pdf->Cell(40,$alt,$iExercicio                           ,"BR" ,0,"C",0);
    $pdf->Cell(40,$alt,db_formatar($aDados['nLivre'],"f")    ,"TBR",0,"R",0);
    $pdf->Cell(35,$alt,db_formatar($aDados['nVinculado'],"f"),"TBR",0,"R",0);
    $pdf->Cell(24,$alt,db_formatar($nTotalColuna,"f")        ,"TB" ,1,"R",0);
      
  }
    
  $pdf->setfont('arial','B', 8);  
  $pdf->Cell(178,$alt,"","TBR",0,"R",0);
  $pdf->setfont('arial','' , 8);
  $pdf->Cell(40 ,$alt,db_formatar($nTotalLivre,"f")      ,"TBR",0,"R",0);
  $pdf->Cell(35 ,$alt,db_formatar($nTotalVinculado,"f")  ,"TBR",0,"R",0);
  $pdf->Cell(24 ,$alt,db_formatar($nTotalGeral,"f")      ,"TBL",1,"R",0);   
  $pdf->setfont('arial','B', 8); 

}

function mostrarTotalGeral($pdf,$alt,$aTotalOrgao){
  
  validaNovaPagina($pdf);
  
  $aTotalGeral = array(); 
  
  $pdf->Cell(138,$alt,""          ,"TBR",0,"C",0);
  $pdf->Cell(40 ,$alt,"Ano"       ,"TBR",0,"C",0);
  $pdf->Cell(40 ,$alt,"Livres"    ,"TBR",0,"C",0);
  $pdf->Cell(35 ,$alt,"Vinculados","TBR",0,"C",0);
  $pdf->Cell(24 ,$alt,"Total"     ,"TB" ,1,"C",0);  
  
  $iY = $pdf->getY();
  $pdf->Cell(138,$alt*4," TOTAL GERAL ","TBR",1,"C",0);
  $pdf->setY($iY);

  foreach ( $aTotalOrgao as $iOrgao => $aExercDados ) {
    foreach ( $aExercDados as $iExercicio => $aDados ) {
      if ( isset($aTotalGeral[$iExercicio]) ) {
        $aTotalGeral[$iExercicio]['nLivre']     += $aDados['nLivre'];
        $aTotalGeral[$iExercicio]['nVinculado'] += $aDados['nVinculado'];
        $aTotalGeral[$iExercicio]['nGeral']     += $aDados['nLivre'] + $aDados['nVinculado'];
      } else {
        $aTotalGeral[$iExercicio]['nLivre']     = $aDados['nLivre'];
        $aTotalGeral[$iExercicio]['nVinculado'] = $aDados['nVinculado'];
        $aTotalGeral[$iExercicio]['nGeral']     = $aDados['nLivre'] + $aDados['nVinculado'];        
      }
    }
  }

  $nTotalLivre     = 0;
  $nTotalVinculado = 0;
  $nTotalGeral     = 0;  
  
  foreach ( $aTotalGeral as $iExercicio => $aDados) {
    
    $pdf->SetX(148);
    $pdf->Cell(40,$alt,$iExercicio                           ,"BR" ,0,"C",0);
    $pdf->Cell(40,$alt,db_formatar($aDados['nLivre'],"f")    ,"TBR",0,"R",0);
    $pdf->Cell(35,$alt,db_formatar($aDados['nVinculado'],"f"),"TBR",0,"R",0);
    $pdf->Cell(24,$alt,db_formatar($aDados['nGeral'],"f")    ,"TB" ,1,"R",0);
    
    $nTotalLivre     += $aDados['nLivre'];
    $nTotalVinculado += $aDados['nVinculado'];
    $nTotalGeral     += $aDados['nGeral'];
    
  }   
    
  $pdf->setfont('arial','B', 8);  
  $pdf->Cell(178,$alt,"","TBR",0,"R",0);
  $pdf->setfont('arial','' , 8);
  $pdf->Cell(40 ,$alt,db_formatar($nTotalLivre,"f")      ,"TBR",0,"R",0);
  $pdf->Cell(35 ,$alt,db_formatar($nTotalVinculado,"f")  ,"TBR",0,"R",0);
  $pdf->Cell(24 ,$alt,db_formatar($nTotalGeral,"f")      ,"TBL",1,"R",0);   
  $pdf->setfont('arial','B', 8);  
  
}

?>
