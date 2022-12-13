<?
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

require_once('fpdf151/pdf.php');
require_once('libs/db_sql.php');
require_once('libs/db_utils.php');

$oGet = db_utils::postMemory($_GET);


$sOrdem = "";
$aWhere = array();

$aWhere[] = " rh02_instit = ".db_getsession("DB_instit");


/**
 * Seleciona a ordem
 */
if( $oGet->sOrdem == 'm' ) {
	
  $sOrdem       = " rh01_regist ";
  $sHeaderOrdem = "Matrícula";
} else {
	
  $sOrdem       = " z01_nome ";
  $sHeaderOrdem = "Nome";
} 


/*
 * Seleciona as matriculas e criar o Header de modo de pesquisa
 */
if (isset($oGet->iMatriculaInicial) && isset($oGet->iMatriculaFinal) && $oGet->iMatriculaInicial != "" 
   && $oGet->iMatriculaFinal != "" ) {
   	
  $aWhere[] = " rh01_regist between {$oGet->iMatriculaInicial} and {$oGet->iMatriculaFinal} ";
  $sHeaderModo = "Matrícula";
  
} else {
  $sHeaderModo = "Geral";
}

/**
 * Seleciona as matriculas e criar o Header de modo de pesquisa 
 */
if (isset($oGet->sSelecaoMatriculas) && $oGet->sSelecaoMatriculas != "" ) {
	
  $aWhere[] = " rh01_regist IN {$oGet->sSelecaoMatriculas}";
  $sHeaderModo = "Matrícula";
    
}

/**
 * Verifica se foi selecionada quebra de pagina e cria a variavel de Header 
 */

if ($oGet->sQuebra == "s"){
	$sHeaderQuebra = "Sim";
} else {
  $sHeaderQuebra = "Não";
}


/**
 *  Header da data para titulo do relatorio
 */
$sHeaderData = "{$oGet->dMesIni}/{$oGet->dAnoIni} até {$oGet->dMesFini}/{$oGet->dAnoFini}";


$aWhere[] = " rh02_anousu between '{$oGet->dAnoIni}' and '{$oGet->dAnoFini}' ";
$aWhere[] = " ( case 
                  when rh02_anousu = '{$oGet->dAnoIni}' then 
                    case
                       when rh02_mesusu >= '{$oGet->dMesIni}' then true
                       else false
                    end 
                  else true
                end
            and case 
                  when rh02_anousu = '{$oGet->dAnoFini}' then 
                    case
                       when rh02_mesusu <= '{$oGet->dMesFini}' then true
                       else false
                    end 
                  else true
                end 
              )";


$sWhere   = implode(" and ",$aWhere);


$sSql    = " select rh01_regist,                                                                   "; 
$sSql   .= "        z01_nome,                                                                      ";
$sSql   .= "        rh01_admiss,                                                                   "; 
$sSql   .= "        rh02_mesusu,                                                                   ";
$sSql   .= "        rh02_anousu ,                                                                  ";
$sSql   .= "        r70_estrut,                                                                    ";
$sSql   .= "        r70_descr,                                                                     ";
$sSql   .= "        rh03_padrao,                                                                   ";
$sSql   .= "        rh02_salari,                                                                   ";
$sSql   .= "        rh02_funcao,                                                                   ";
$sSql   .= "        rh37_descr                                                                     ";
$sSql   .= "   from rhpessoal                                                                      ";
$sSql   .= "        inner join rhpessoalmov on rh02_regist = rh01_regist                           "; 
$sSql   .= "        inner join cgm          on z01_numcgm  = rh01_numcgm                           ";
$sSql   .= "        left  join rhlota       on rh02_lota   = r70_codigo                            ";
$sSql   .= "        inner join rhregime     on rh30_codreg = rh02_codreg                           ";
$sSql   .= "                               and rh30_instit = rh02_instit                           ";
$sSql   .= "        left  join rhpespadrao  on rh03_seqpes = rh02_seqpes                           ";
$sSql   .= "        left  join rhpescargo   on rh20_seqpes = rh02_seqpes                           ";
$sSql   .= "        inner join rhfuncao     on rh02_funcao = rh37_funcao                           ";
$sSql   .= "                               and rh37_instit = rh02_instit                           ";
$sSql   .= "  where {$sWhere}                                                                      ";
$sSql   .= "  order by {$sOrdem},                                                                  "; 
$sSql   .= "           rh02_anousu,                                                                ";
$sSql   .= "           rh02_mesusu;                                                                ";

$rsSql     = pg_query($sSql);
$iNumRows  = pg_num_rows($rsSql);

if ($iNumRows == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=O filtro selecionado não retornou nenhum registro.');
}


/**
 *  Pegar dados do SQL e forma a estrutura de dados
 */
$aPessoa = array();

/**
 * Passa os resultados da query para o Array $aPessoa já fazendo o teste de salario e tipo
 */
for ($iInd = 0; $iInd < $iNumRows; $iInd++) {
    
    $oRetorno = db_utils::fieldsMemory($rsSql, $iInd);
    
    /**
     * Regras de Salário e Tipo 
     */  
    if ($oRetorno->rh02_salari > 0){
      
      $oRetorno->salario = db_formatar( $oRetorno->rh02_salari , "f");
      $oRetorno->tipo    = "Salário"; 
      
    } else {
        
      $oRetorno->salario = $oRetorno->rh03_padrao;
      $oRetorno->tipo    = "Padrão"; 
     
    }
    
    /**
     * Para o primeiro indice não é necessario checar mudança 
     */
    if ($iInd > 0){
    
      $oRetornoAnt = db_utils::fieldsMemory($rsSql,$iInd-1);
      
      if ( $oRetorno->rh02_funcao  != $oRetornoAnt->rh02_funcao 
        || $oRetorno->rh02_salari  != $oRetornoAnt->rh02_salari
        || $oRetorno->r70_estrut   != $oRetornoAnt->r70_estrut 
        || $oRetorno->rh03_padrao  != $oRetornoAnt->rh03_padrao 
	    ) {
	       
        $aPessoa[$oRetorno->rh01_regist]->texto   = $oRetorno->rh01_regist." - ".$oRetorno->z01_nome;
        $aPessoa[$oRetorno->rh01_regist]->itens[] = $oRetorno;
	    
      }

    } else {
        
      $aPessoa[$oRetorno->rh01_regist]->texto   = $oRetorno->rh01_regist." - ".$oRetorno->z01_nome;
      $aPessoa[$oRetorno->rh01_regist]->itens[] = $oRetorno;
    
    }
    
}

$oPdf = new PDF("L"); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->SetFont('arial','b',8);
$oPdf->SetFillColor(235);

$head2           = "Relatório de Histórico Cadastral";
$head3           = "Data : {$sHeaderData}";
$head4           = "Modo : {$sHeaderModo}";
$head5           = "Quebra : {$sHeaderQuebra}";
$head6           = "Ordem : {$sHeaderOrdem}";

$oPdf->AddPage();

$iAlt   = 4;
$corfundo = 236;


//echo "<pre>";
//var_dump($aPessoa);
//echo "</pre>";
//exit;

/**
 * Gera o relatorio
 */
foreach ( $aPessoa as $oPessoa ) {

	/**
	 * Variavel para fazer o teste se o label foi escrito
	 */
  $lTexto = false;
      	
  /**
   * Pega os itens de cada matricula
   */


	  foreach ( $oPessoa->itens as $oItens ){
	  
	
	  	
	  	/**
	  	 * Teste para saber se o label já foi escrito
	  	 */
	    if (!$lTexto){
	            
	      $oPdf->SetFont('arial','B',8);
	      $oPdf->cell(100, 5, $oPessoa->texto, 0,1,"L");
	      $oPdf->SetFont('arial','b',8);
	      $oPdf->SetFillColor(240);
	      $oPdf->cell(25,$iAlt,"Admissão"     ,1,0,"C",1);
	      $oPdf->cell(35,$iAlt,"Competência"  ,1,0,"C",1);
	      $oPdf->cell(75,$iAlt,"Lotação"      ,1,0,"C",1);
	      $oPdf->cell(55,$iAlt,"Cargo"        ,1,0,"C",1);
	      $oPdf->cell(30,$iAlt,"Valor"        ,1,0,"C",1); 
	      $oPdf->cell(45,$iAlt,"Tipo"         ,1,1,"C",1); 
	      $oPdf->Ln(2);
	      
	      /**
	       * Marca como true para não passar o label denovo 
	       */ 
	      $lTexto = true;
	      
	    }
	    
	    $oPdf->SetFont('arial','',7);
	    $oPdf->cell(25,$iAlt,db_formatar( $oItens->rh01_admiss , "d")                    ,0,0,"C",0);
	    $oPdf->cell(35,$iAlt,db_mes($oItens->rh02_mesusu)."/".$oItens->rh02_anousu       ,0,0,"L",0); 
	    $oPdf->cell(75,$iAlt,$oItens->r70_estrut." - ".substr($oItens->r70_descr,0,35)   ,0,0,"L",0); 
	    $oPdf->cell(55,$iAlt,$oItens->rh02_funcao." - ".substr($oItens->rh37_descr,0,25) ,0,0,"L",0); 
	    $oPdf->cell(30,$iAlt,$oItens->salario                                            ,0,0,"R",0); 
	    $oPdf->cell(45,$iAlt,$oItens->tipo                                               ,0,1,"C",0);  
	    
	
	    }

    /**
     * Testa a variavel sQuebra e cria uma pagina se a quebra foi selecionada como sim
     */
    if ($oGet->sQuebra == "s"){
    	$oPdf->AddPage();
    } else {
    	
    	$oPdf->Line($oPdf->GetX(),$oPdf->GetY(),275,$oPdf->GetY());    
      $oPdf->Ln(2);
      
    }
    
}
  
$oPdf->output();

?>