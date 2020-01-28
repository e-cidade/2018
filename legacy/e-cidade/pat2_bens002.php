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
require_once("libs/db_sql.php");
require_once('libs/db_utils.php');
require_once("classes/db_bens_classe.php");
require_once("classes/db_bensmater_classe.php");
require_once("classes/db_bensimoveis_classe.php");
require_once("classes/db_bensbaix_classe.php");
require_once("classes/db_apolitem_classe.php");
require_once("classes/db_lote_classe.php");
require_once("classes/db_cfpatriplaca_classe.php");
require_once("classes/db_db_departorg_classe.php");
require_once("classes/db_cfpatri_classe.php");

$clcfpatric 		= new cl_cfpatri;
$clbens         = new cl_bens;
$clbensmater    = new cl_bensmater;
$clbensimoveis  = new cl_bensimoveis;
$clbensbaix     = new cl_bensbaix;
$clapolitem     = new cl_apolitem;
$cllote         = new cl_lote;
$clcfpatriplaca = new cl_cfpatriplaca;
$cldepartorg 	 	= new cl_db_departorg;

$clrotulo = new rotulocampo;
$clbens->rotulo->label();
$clbensmater->rotulo->label();
$clbensimoveis->rotulo->label();
$cllote->rotulo->label();
$clrotulo->label("t64_descr"); //fornecedor
$clrotulo->label("t64_class"); //classificação
$clrotulo->label("descrdepto");//departamento
$clrotulo->label("t81_codapo");//código da apolice
$clrotulo->label("t81_apolice");//descrição da apólice
$clrotulo->label("t81_venc");//vencimento da apólice
$clrotulo->label("j13_descr"); //descrição do bairro
$clrotulo->label("t80_segura");//observação
$clrotulo->label("z01_nome");//nome no cadastro cgm
$clrotulo->label("t80_contato");//contato na seguradora
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;



$resPesquisaOrgao	= $clcfpatric->sql_record($clcfpatric->sql_query_file(null,'t06_pesqorgao'));	
	if($clcfpatric->numrows > 0) {
		db_fieldsmemory($resPesquisaOrgao,0);
		$lImprimeOrgao = $t06_pesqorgao;
	}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->setfillcolor(235);
$total = 0;
$troca = 1;
$alt = 4;	
	
$aWhere = array();
if (!empty($t52_bem))  {
    $aWhere[] = " t52_bem in $t52_bem ";
}
if (!empty($t52_ident)) {
  $aWhere[] = " t52_ident = '$t52_ident' ";
}
  
$res_cfpatriplaca = $clcfpatriplaca->sql_record($clcfpatriplaca->sql_query_file(db_getsession("DB_instit")));
if ($clcfpatriplaca->numrows > 0){
     db_fieldsmemory($res_cfpatriplaca,0);
}

$sCampos = "distinct
  	 t52_bem,t52_codcla,t52_numcgm,t52_valaqu,t52_dtaqu,t52_ident,t52_descr,t52_obs,t52_depart,t52_instit,t52_bensmarca,
  	 t52_bensmodelo,t52_bensmedida,t30_codigo,t30_descr,descrdepto,t64_class,t64_descr";
  

if (!empty($departamentos)) {
  $aWhere[] = "t52_depart in({$departamentos})";
}

$pesquisa = implode(" and ", $aWhere);
$result = $clbens->sql_record($clbens->sql_query_class(null,$sCampos,"","t52_instit = ".db_getsession("DB_instit")." and $pesquisa"));
//die($clbens->erro_banco);
if($clbens->numrows>0){
  //db_fieldsmemory($result,0);
}else{

  $oParms = new stdClass();
  $oParms->sBem = $t52_bem;
  $sMsg = _M('patrimonial.patrimonio.pat2_bens002.bem_nao_encontrado', $oParms);
  db_redireciona("db_erros.php?fechar=true&db_erro=" . $sMsg);
}

$iNumRows = $clbens->numrows;
//die($iNumRows);
for($iInd = 0; $iInd < $iNumRows; $iInd++) {

  db_fieldsmemory($result,$iInd);
    $resOrgaoUnidade = $cldepartorg->sql_record($cldepartorg->sql_query_orgunid($t52_depart,db_getsession('DB_anousu'),"o40_orgao,o40_descr,o41_unidade,o41_descr"));
    if($cldepartorg->numrows > 0){
      db_fieldsmemory($resOrgaoUnidade,0);
    }

    $result_mater = $clbensmater->sql_record($clbensmater->sql_query_bensmater($t52_bem));
    if($clbensmater->numrows>0){
      $definicao = "MATERIAL";
      db_fieldsmemory($result_mater,0);
    }else{
      $result_imov = $clbensimoveis->sql_record($clbensimoveis->sql_query_file($t52_bem));
      if($clbensimoveis->numrows>0){
        $definicao = "IMÓVEL";
        db_fieldsmemory($result_imov,0);
        $result_lote = $cllote->sql_record($cllote->sql_query($t54_idbql));
        if($cllote->numrows>0){
    db_fieldsmemory($result_lote,0);
        }
      }else{
        $definicao = "MATERIAL";
      }
    }
    $clbensbaix->sql_record($clbensbaix->sql_query_file($t52_bem));
    if($clbensbaix->numrows>0){
      $baix = "Baixado";
    }else{
      $baix = "Não baixado";
    }
    $result_apolitem = $clapolitem->sql_record($clapolitem->sql_query(null,null,"*","t82_codapo"," t82_codbem=$t52_bem and t81_venc >='".date("Y-m-d",db_getsession("DB_datausu"))."'" ));
    $numrows = $clapolitem->numrows;
    if($numrows>0){
      $item_apolice = "S";
    }else{
      $item_apolice = "N";
    }
    //Verifica se utiliza pesquisa por orgão sim ou não




  $head3 = "BEM";
  $head5 = "CÓDIGO: $t52_bem";
  $head6 = "DESCRIÇÃO: $t52_descr";
  $pdf->addpage();
  $pdf->setfont('arial','b',8);
  $pdf->cell(90,$alt,'DADOS DO BEM',0,1,"L",0);
  //Testa para verificar se utiliza pesquisa por orgão
  if($lImprimeOrgao == 't'){
    $pdf->setfont('arial','b',7);
    $pdf->cell(30,$alt,'Orgão',0,0,"L",0);
    $pdf->setfont('arial','',7);
    $pdf->cell(0,$alt,$o40_orgao." - ".$o40_descr,0,1,"L",0);

    $pdf->setfont('arial','b',7);
    $pdf->cell(30,$alt,'Unidade',0,0,"L",0);
    $pdf->setfont('arial','',7);
    $pdf->cell(0,$alt,$o41_unidade." - ".$o41_descr,0,1,"L",0);
  }

  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLt52_depart,0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$t52_depart." - ".$descrdepto,0,1,"L",0);

  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,"Divisão",0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$t30_codigo." - ".$t30_descr,0,1,"L",0);

  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLt64_class,0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$t64_class." - ".$t64_descr,0,1,"L",0);

  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLt52_dtaqu,0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,db_formatar($t52_dtaqu,"d"),0,1,"L",0);

  if (strlen(trim($t52_ident)) > 0){
       if ($t07_confplaca == 4){
            $t52_ident = db_formatar($t52_ident,"s","0",$t07_digseqplaca,"e",0);
       }
  }

  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,$RLt52_ident,0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$t52_ident,0,1,"L",0);

  $pdf->setfont('arial','b',7);
  $pdf->cell(30,$alt,"Situação",0,0,"L",0);
  $pdf->setfont('arial','',7);
  $pdf->cell(0,$alt,$baix,0,1,"L",0);

  $pdf->setfont('arial','b',8);
  if($definicao == "INDEFINIDO"){
    $pdf->cell(90,$alt,'BEM '.$definicao,0,1,"L",0);
  }
  if($item_apolice == "N"){
    $pdf->cell(90,$alt,'BEM NÃO CADASTRADO EM APÓLICES',0,1,"L",0);
  }

  if ($opcao_obs == "S"){
       if (trim($t52_obs)!=""){
            $pdf->setfont('arial','b',7);
            $pdf->cell(50,$alt,"Características adicionais do bem",0,0,"L",0);
            $pdf->setfont('arial','',7);
            $pdf->multicell(145,$alt,$t52_obs,0,"L",0);
       }
  }


  /*
   *  Consulta o codemp e anousu, se o empenho for do sistema
   *  se nao exibe somento o numero do empenho
   */
  $sSqlEmpen = $clbensmater->sql_query_bensmater("", "e60_codemp, e60_anousu, t53_empen", "", "t53_codbem = {$t52_bem}");
  $rsEmpen   = $clbensmater->sql_record($sSqlEmpen);
  if ($clbensmater->numrows > 0) {

    db_fieldsmemory($rsEmpen,0);
    if ($e60_codemp != "" && $e60_anousu != "") {
      $sEmpenho = $e60_codemp." / ".$e60_anousu ;
    } else {
       $sEmpenho = $t53_empen;
    }
  }

  $pdf->ln(10);
  if($definicao != "INDEFINIDO"){
    $pdf->cell(90,$alt,'DADOS DO '.$definicao,0,1,"L",0);
    if($definicao=="MATERIAL"){
      $pdf->setfont('arial','b',7);
      $pdf->cell(30,$alt,$RLt53_empen,0,0,"L",0);
      $pdf->setfont('arial','',7);
      $pdf->cell(0,$alt,@$sEmpenho,0,1,"L",0);

      $pdf->setfont('arial','b',7);
      $pdf->cell(30,$alt,$RLt53_garant,0,0,"L",0);
      $pdf->setfont('arial','',7);
      $pdf->cell(0,$alt,db_formatar(@$t53_garant,"d"),0,1,"L",0);

      $pdf->setfont('arial','b',7);
      $pdf->cell(30,$alt,$RLt53_ordem,0,0,"L",0);
      $pdf->setfont('arial','',7);
      $pdf->cell(0,$alt,@$t53_ordem,0,1,"L",0);

      $pdf->setfont('arial','b',7);
      $pdf->cell(30,$alt,$RLt53_ntfisc,0,0,"L",0);
      $pdf->setfont('arial','',7);
      $pdf->cell(0,$alt,@$t53_ntfisc,0,1,"L",0);
    }else if($definicao=="IMÓVEL"){
      $pdf->setfont('arial','b',7);
      $pdf->cell(30,$alt,$RLt54_idbql,0,0,"L",0);
      $pdf->setfont('arial','',7);
      $pdf->cell(0,$alt,$t54_idbql,0,1,"L",0);

      $pdf->setfont('arial','b',7);
      $pdf->cell(30,$alt,$RLt54_obs,0,0,"L",0);
      $pdf->setfont('arial','',7);
      $pdf->multicell(0,$alt,$t54_obs,0,"J",0);
    }
  }

  $pdf->ln(10);
  //$alt = 30;
  if($item_apolice == "S"){
    for($x = 0; $x<$numrows; $x++){
      db_fieldsmemory($result_apolitem,$x);
      if($pdf->gety() > $pdf->h - 30 || $troca!=0){
        if($pdf->gety() > $pdf->h - 30){
          $pdf->addpage();
        }
        $pdf->setfont('arial','b',8);
        $pdf->cell(12,$alt,$RLt81_codapo,1,0,"C",1);
        $pdf->cell(60,$alt,$RLt81_apolice,1,0,"C",1);
        $pdf->cell(19,$alt,$RLt81_venc,1,0,"C",1);
        $pdf->cell(50,$alt,$RLz01_nome,1,0,"C",1);
        $pdf->cell(50,$alt,$RLt80_contato,1,1,"C",1);

        $troca = 0;
      }
      $pdf->setfont('arial','',6);
      $pdf->cell(12,$alt,$t81_codapo,0,0,"C",0);
      $pdf->cell(60,$alt,$t81_apolice,0,0,"L",0);
      $pdf->cell(19,$alt,db_formatar($t81_venc,"d"),0,0,"C",0);
      $pdf->cell(50,$alt,$z01_nome,0,0,"L",0);
      $pdf->cell(50,$alt,$t80_contato,0,1,"L",0);
      $total++;
    }
    $pdf->setfont('arial','b',8);
    $pdf->cell(191,$alt,'TOTAL DE APÓLICES COM ESTE BEM :  '.$total,"T",0,"L",0);
  //  $result_apolitem = $clapolitemi
  }
}
$pdf->Output();
?>