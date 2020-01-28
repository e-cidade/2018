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

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_sql.php"));
include(modification("libs/db_utils.php"));
include(modification("classes/db_inssirf_classe.php"));
include(modification("classes/db_rhcadregime_classe.php"));
include(modification("classes/db_rhbasesr_classe.php"));
include(modification("classes/db_cfpess_classe.php"));
include(modification("classes/db_rhautonomolanc_classe.php"));
include(modification("classes/db_selecao_classe.php"));

$clselecao        = new cl_selecao();
$clinssirf        = new cl_inssirf();
$clrhcadrefime    = new cl_rhcadregime();
$clrhbasesr       = new cl_rhbasesr();
$clcfpess         = new cl_cfpess();
$clrhautonomolanc = new cl_rhautonomolanc(); 

db_postmemory($HTTP_GET_VARS);

$oGet = db_utils::postMemory($_GET);


/**
 * Verificamos se a instituição é do tipo RPPS, se for só executa o cálculo dos patronais se o servidor possuir
 * desconto de previdencia. Outras instituições realizam o cálculo Patronal mesmo o servidor nao tendo o desconto de precidencia.
 */
$oDaoDbConfig     = new cl_db_config();
$sSqlDbConfig     = $oDaoDbConfig->sql_query_file(db_getsession('DB_instit'), 'db21_tipoinstit');
$rsDbConfig       = db_query($sSqlDbConfig);
$iTipoInstituicao = db_utils::fieldsMemory($rsDbConfig, 0)->db21_tipoinstit;
$lInstituicaoRPPS = false;

if ($iTipoInstituicao == 6 || $iTipoInstituicao == 5) {
  $lInstituicaoRPPS = true;  
}

$where = " ";
if(trim($selecao) != ""){
  $result_selecao = $clselecao->sql_record($clselecao->sql_query_file($selecao,db_getsession("DB_instit")));
  if($clselecao->numrows > 0){
    db_fieldsmemory($result_selecao, 0);
    $where = " and ".$r44_where;
    $head8 = "SELEÇÃO: ".$selecao." - ".$r44_descr;
  }
}

$lQuebraLote = false;

$res_cfpess = $clcfpess->sql_record($clcfpess->sql_query_file($ano, $mes, db_getsession("DB_instit")));
db_fieldsmemory($res_cfpess,0);

$res_prev = $clinssirf->sql_record($clinssirf->sql_query_file(null,null,"r33_ppatro,r33_nome,r33_rubmat","r33_nome limit 1","r33_anousu = $ano and r33_mesusu = $mes and r33_codtab = $prev+2 and r33_instit=".db_getsession("DB_instit")));
db_fieldsmemory($res_prev,0);

$mater_ferias = $r33_rubmat + 2000;
$mater_13     = $r33_rubmat + 4000;

$sql_in = $clrhbasesr->sql_query_file("B995",null,"rh33_rubric::char(4)");

$gera_sql = new cl_gera_sql_folha;
$gera_sql->usar_atv  = true;
$gera_sql->usar_cgm  = true;
$gera_sql->usar_ger  = true;
$gera_sql->inner_ger = false;
$gera_sql->usar_res  = true;
$gera_sql->inner_res = false;
$gera_sql->usar_lot  = true;

$headinfo = "";
$dborderby = "z01_nome";

$rubricas_selecionadas = "''";
if(isset($R918)){
  $rubricas_selecionadas.= ", 'R918'";
}
if(isset($R919)){
  $rubricas_selecionadas.= ", 'R919'";
}
if(isset($R920)){
  $rubricas_selecionadas.= ", 'R920'";
}

$rubricas_selecionadas = str_replace("'',","",$rubricas_selecionadas);

// Verifica Rubricas e se tem ou nao Calculo
if(isset($calc) && $calc<>2) {

  $dbwhere = "
            (
                #s#_rubric in ('".$r33_rubmat."','".$mater_ferias."','".$mater_13."','R990','R993','R985', 'R986', 'R987' ".($rubricas_selecionadas != "" ? "," : "").$rubricas_selecionadas.") 
             or #s#_rubric in (select r09_rubric 
                               from basesr 
                               where r09_base = 'B995' 
                                 and r09_anousu = ".$ano."
                                 and r09_mesusu = ".$mes."
                              ) ";

  if(isset($calc) && $calc==3) {
    $dbwhere .= " or #s#_rubric is null ";
  }

  $dbwhere .= " ) ";

} else {
  $dbwhere = " #s#_rubric is null ";

}

// Tabela de Previdencia
$dbwhere .= " and rh02_tbprev = ".$prev." ".$where;


if(isset($lotaci) && trim($lotaci) != "" && isset($lotacf) && trim($lotacf) != ""){
  $lQuebraLote = true;
  $dborderby = "r70_estrut, z01_nome";
  $dbwhere.= " and r70_estrut between '".$lotaci."' and '".$lotacf."' ";
  $headinfo = "LOTAÇÕES COM ESTRUTURAL ENTRE: ".$lotaci." E ".$lotacf;
}else if(isset($lotaci) && trim($lotaci) != ""){
  $lQuebraLote = true;
  $dborderby = "r70_estrut, z01_nome";
  $dbwhere.= " and r70_estrut >= '".$lotaci."' ";
  $headinfo = "LOTAÇÕES COM ESTRUTURAL POSTERIORES A ".$lotaci;
}else if(isset($lotacf) && trim($lotacf) != ""){
  $lQuebraLote = true;
  $dborderby = "r70_estrut, z01_nome";
  $dbwhere.= " and r70_estrut <= '".$lotacf."' ";
  $headinfo = "LOTAÇÕES COM ESTRUTURAL ANTERIORES A ".$lotacf;
}else if(isset($sellot) && trim($sellot) != ""){
  $lQuebraLote = true;
  $dborderby = "r70_estrut, z01_nome";
  $dbwhere.= " and r70_estrut in ('".str_replace(",","','",$sellot)."')";
  $headinfo = "LOTAÇÕES COM ESTRUTURAIS SELECIONADOS ";
}else if((isset($lotaci) && trim($lotaci) == "" && isset($lotacf) && trim($lotacf) == "") || (isset($sellot) && trim($sellot) == "")){
  $lQuebraLote = true;
  $dborderby = "r70_estrut, z01_nome";
  $headinfo = "ORDENAÇÃO POR LOTAÇÕES";
}


if($codreg != ''){
  $dbwhere.= " and rh30_codreg in ($codreg)";
}

/*
 * Modificacao tarefa 42515
 * acrescentar opcao de todas nas folhas de pagamento
 * opcoes colocadas no array $aFolhas
 */

$aDadosPrev = Array();
if ($tfol == 'todas') {
  $aFolhas = array ('r14',
                    'r48',
                    'r35',
                    'r20'  
  );
} else {
  $aFolhas = array ($tfol);
}

/**
 * Se folha for complementar e semeste for maior que 0, passamos o numero da complementar no sql, o r48_semest
 */
if ( $tfol == 'r48' && !empty($oGet->complementar) && $oGet->complementar > 0 ) {
  $dbwhere .= " and r48_semest = " . $oGet->complementar;
}

if ( $filtro == 0 || $filtro == 1 ) {
  
  
  for ($i = 0; $i < sizeof($aFolhas); $i++) {

    $sql = $gera_sql->gerador_sql($aFolhas[$i],
                                  $ano,
                                  $mes,
                                  null,
                                  null,
                                  "
                                   rh01_regist, z01_nome, rh30_regime, r70_codigo, r70_descr, r70_estrut,
                                   sum(case when #s#_rubric in (".$rubricas_selecionadas.")
                                            then #s#_quant
                                            else 0
                                       end) as quantsf,
                                   sum(case when #s#_rubric in (".$rubricas_selecionadas.")
                                            then #s#_valor
                                            else 0
                                       end) as valsf,
                                   sum(case when #s#_rubric in ('R990')
                                            then #s#_valor
                                            else 0
                                       end) as R990,
                                   sum(case when #s#_rubric in ('R985', 'R986', 'R987')
                                            then #s#_valor
                                            else 0
                                       end) as R992,
                                   sum(case when #s#_rubric in ('R993')
                                            then #s#_valor
                                            else 0
                                       end) as R993,
                                   sum(case when #s#_rubric in ('".$r33_rubmat."', '".$mater_ferias."','".$mater_13."' )
                                            then #s#_valor
                                            else 0
                                       end) as mater,
                                   sum(case when #s#_rubric not in ('R990','R993', 'R985', 'R986', 'R987')
                                            then #s#_valor
                                            else 0
                                       end) as ded_inss
                                  ",
                                  $dborderby,
                                  $dbwhere.
                                  "
                                   group by rh01_regist, z01_nome, rh30_regime, r70_codigo, r70_descr, r70_estrut
                                  "
                                 );


    $rsDadosPrev = db_query($sql);



    $xxnum = pg_numrows($rsDadosPrev);
    
    /* 
     * T.42515
     * SE A OPCAO DE FOLHA FOR DIF. DE TODAS E NAO ENCONTRAR NADA REDIRECIONA PARA O ERRO DE VAZIO
     * SE FOR TODAS , TESTARA O VALOR DE TODAS OPCOES DE FOLHAS ANTES DE SAIR SEM NENHUM RESULTADO
     */   
    if ($tfol != 'todas') {
      if($xxnum == 0){
        db_redireciona('db_erros.php?fechar=true&db_erro=Não existem cálculos para o período de '.$mes.' / '.$ano);
      }
    } 
    
    $iLinhasPrev = pg_num_rows($rsDadosPrev); 
    
    for ( $iInd = 0; $iInd < $iLinhasPrev; $iInd++ ){
      
      $oDadosPrev = db_utils::fieldsMemory($rsDadosPrev,$iInd);

      if ($oDadosPrev->r993 == 0 && $lInstituicaoRPPS) {
        continue;
      }


      
      if($oDadosPrev->r990 > 0){
        $nBase = $oDadosPrev->r990;
      } else {
        $nBase = $oDadosPrev->r992;
      } 
      
      if ( $lQuebraLote == 'l') {
        $sAgrupa = $oDadosPrev->r70_codigo." - ".$oDadosPrev->r70_descr." (".$oDadosPrev->r70_estrut.")";
      } else {
        $sAgrupa = 0;
      }

      if (isset($aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist])) {

        $oDadosRegist = $aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist];
      } else {

        $oDadosRegist                      = new stdClass();
        $oDadosRegist->sNome               = $oDadosPrev->z01_nome;
        $oDadosRegist->iRegime             = $oDadosPrev->rh30_regime;
        $oDadosRegist->iFil                = $oDadosPrev->quantsf;
        $oDadosRegist->nSalarioFamilia     = 0;
        $oDadosRegist->nSalarioMaternidade = 0;
        $oDadosRegist->nBase               = 0;
        $oDadosRegist->nDesconto           = 0;
        $oDadosRegist->nDeducao            = 0;
        $oDadosRegist->nPatronal           = 0;
      }
      
      if ( isset($aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist]) && 
                 $aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist]->iRegime == $oDadosPrev->rh30_regime && 
                 $aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist]->iFil    == $oDadosPrev->quantsf ) {
        
        $aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist]->nSalarioFamilia     += $oDadosPrev->valsf;
        $aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist]->nSalarioMaternidade += $oDadosPrev->mater;
        $aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist]->nBase               += $nBase;
        $aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist]->nDesconto           += $oDadosPrev->r993;
        $aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist]->nDeducao            += $oDadosPrev->ded_inss;

        $aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist]->nPatronal           += $nBase/100*$r33_ppatro;
      } else {
        $oDadosRegist->nSalarioFamilia     += $oDadosPrev->valsf;
        $oDadosRegist->nSalarioMaternidade += $oDadosPrev->mater;
        $oDadosRegist->nBase               += $nBase;
        $oDadosRegist->nDesconto           += $oDadosPrev->r993;
        $oDadosRegist->nDeducao            += $oDadosPrev->ded_inss;
        $oDadosRegist->nPatronal           += ($nBase/100 * $r33_ppatro);
        
        $aDadosPrev[$sAgrupa][$oDadosPrev->rh01_regist] = $oDadosRegist;
      } 
    }
  }
}

if ( ( $filtro == 0 || $filtro == 2 ) && $tfol == 'r14' ) {
  
  $sWhereAutonomo   = "     rh90_anousu = {$ano} ";
  $sWhereAutonomo  .= " and rh90_mesusu = {$mes} ";
  $sWhereAutonomo  .= " and rh90_ativa is true   ";
  $sSqlAutonomos    = $clrhautonomolanc->sql_query_sefip(null,
                                                        "rhautonomolanc.*,z01_nome",
                                                        null,
                                                        $sWhereAutonomo);
  
  $rsDadosAutonomos = $clrhautonomolanc->sql_record($sSqlAutonomos);
  $iLinhasAutonomos = $clrhautonomolanc->numrows;                                                       
  
  
  for ( $iInd = 0; $iInd < $iLinhasAutonomos; $iInd++ ){
    
    $oDadosAutonomo = db_utils::fieldsMemory($rsDadosAutonomos,$iInd);
    
    $oDadosRegist = new stdClass();
    $oDadosRegist->sNome               = $oDadosAutonomo->z01_nome; 
    $oDadosRegist->iRegime             = 0;
    $oDadosRegist->iFil                = 0;
    $oDadosRegist->nSalarioFamilia     = 0;
    $oDadosRegist->nSalarioMaternidade = 0;
    $oDadosRegist->nDeducao            = 0;
    
    $sAgrupa = 'Autônomos';
    
    if ( isset($aDadosPrev[$sAgrupa][$oDadosAutonomo->rh89_numcgm]) ) {
      
      $aDadosPrev[$sAgrupa][$oDadosAutonomo->rh89_numcgm]->nBase     += $oDadosAutonomo->rh89_valorserv;
      $aDadosPrev[$sAgrupa][$oDadosAutonomo->rh89_numcgm]->nDesconto += $oDadosAutonomo->rh89_valorretinss;
      $aDadosPrev[$sAgrupa][$oDadosAutonomo->rh89_numcgm]->nPatronal += ($oDadosAutonomo->rh89_valorserv/100*20);
    } else {
      
      $oDadosRegist->nBase     = $oDadosAutonomo->rh89_valorserv;
      $oDadosRegist->nDesconto = $oDadosAutonomo->rh89_valorretinss;
      $oDadosRegist->nPatronal = ($oDadosAutonomo->rh89_valorserv/100*20);
      
      $aDadosPrev[$sAgrupa][$oDadosAutonomo->rh89_numcgm] = $oDadosRegist;
    }  
  }
}

//if ($tfol != 'todas') { 
  if ( count($aDadosPrev) == 0 ) {
    db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum registro encontrado!');
  }
//}

if($tfol == 'r14'){
  $head6 = 'ARQUIVO: SALÁRIO';
}else if($tfol == 'r48'){
  $head6 = 'ARQUIVO: COMPLEMENTAR';
}else if($tfol == 'r35'){
  $head6 = 'ARQUIVO: 13. SALÁRIO';
}else if($tfol == 'todas'){
  $head6 = 'ARQUIVO: Todas Folhas';
}else{
  $head6 = 'ARQUIVO: RESCISÃO';
}

$head6 .= "    CÁLCULO: ";
$head6 .= ($calc==1)?"Com Cálculo":($calc==2?"Sem Cálculo":"Todos");

$head3 = "RELATÓRIO ".strtoupper($r33_nome);
$head5 = "PATRONAL: ".$r33_ppatro."%";
$head7 = "PERÍODO: ".$mes." / ".$ano;
$head8 = $headinfo;

$oPdf = new PDF(); 
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$oPdf->setfillcolor(235);
$oPdf->setfont('arial','b',8);

$iPre      = 1;
$iAlt      = 4;

$iTotalRegistros = 0;
$nPatronalIndividualQuebra = 0;

$aTotalGeral['nSalarioFamilia']     = 0;
$aTotalGeral['nSalarioMaternidade'] = 0;
$aTotalGeral['nBase']               = 0;
$aTotalGeral['nDesconto']           = 0;
$aTotalGeral['nDeducao']            = 0;
$aTotalGeral['nPatronal']           = 0;
$lPrimeiro = true;


foreach ( $aDadosPrev as $sAgrupa => $aDadosRegist ) {
  
  $aSubTotal['nSalarioFamilia']     = 0;
  $aSubTotal['nSalarioMaternidade'] = 0;
  $aSubTotal['nBase']               = 0;
  $aSubTotal['nDesconto']           = 0;
  $aSubTotal['nDeducao']            = 0;
  $aSubTotal['nPatronal']           = 0;

  if ( $oPdf->gety() > $oPdf->h - 30 || $lPrimeiro || $quebra_pagina == "s" ) {
      
    $oPdf->addpage();
    $oPdf->setfont('arial','b',8);
      
    $oPdf->cell(15,$iAlt,'MATRIC'             ,1,0,"C",1);
    $oPdf->cell(60,$iAlt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
    $oPdf->cell(10,$iAlt,'REG'                ,1,0,"C",1);
    $oPdf->cell(10,$iAlt,'FIL'                ,1,0,"C",1);
    $oPdf->cell(20,$iAlt,'SAL.FAM.'           ,1,0,"C",1);
    $oPdf->cell(20,$iAlt,'MATERN.'            ,1,0,"C",1);
    $oPdf->cell(20,$iAlt,'BASE'               ,1,0,"C",1);
    $oPdf->cell(20,$iAlt,'DESCONTO'           ,1,1,"C",1);
     
      
    $lPrimeiro = false;
  }  
  
  if ( $lQuebraLote == 'l' || $sAgrupa == 'Autônomos' ) {
    
    $oPdf->setfont('arial','b',8);
    $oPdf->ln(3);
    $oPdf->cell(175,$iAlt,$sAgrupa,1,1,"L",1);
    $oPdf->ln(1);
  }       
  
  foreach ( $aDadosRegist as $iRegist => $oDadosRegist ) {
    
    
    if ( $oPdf->gety() > $oPdf->h - 30 ) {
      
      $oPdf->addpage();
      $oPdf->setfont('arial','b',8);
      
      $oPdf->cell(15,$iAlt,'MATRIC'             ,1,0,"C",1);
      $oPdf->cell(60,$iAlt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      $oPdf->cell(10,$iAlt,'REG'                ,1,0,"C",1);
      $oPdf->cell(10,$iAlt,'FIL'                ,1,0,"C",1);
      $oPdf->cell(20,$iAlt,'SAL.FAM.'           ,1,0,"C",1);
      $oPdf->cell(20,$iAlt,'MATERN.'            ,1,0,"C",1);
      $oPdf->cell(20,$iAlt,'BASE'               ,1,0,"C",1);
      $oPdf->cell(20,$iAlt,'DESCONTO'           ,1,1,"C",1);
      
      if ( $lQuebraLote == 'l' || $sAgrupa == 'Autônomos' ) {
        $oPdf->ln(3);
        $oPdf->cell(175,$iAlt,$sAgrupa,1,1,"L",1);
      }
              
    }
    
    if ( $iPre == 0 ) {
      $iPre = 1;
    } else {
      $iPre = 0;
    }
    
    $oPdf->setfont('arial','',7);
    
    $oPdf->cell(15,$iAlt,$iRegist                                           ,0,0,"C",$iPre);
    $oPdf->cell(60,$iAlt,$oDadosRegist->sNome                               ,0,0,"L",$iPre);
    $oPdf->cell(10,$iAlt,$oDadosRegist->iRegime                             ,0,0,"R",$iPre);
    $oPdf->cell(10,$iAlt,$oDadosRegist->iFil                                ,0,0,"R",$iPre);
    $oPdf->cell(20,$iAlt,db_formatar($oDadosRegist->nSalarioFamilia    ,'f'),0,0,"R",$iPre);
    $oPdf->cell(20,$iAlt,db_formatar($oDadosRegist->nSalarioMaternidade,'f'),0,0,"R",$iPre);
    $oPdf->cell(20,$iAlt,db_formatar($oDadosRegist->nBase              ,'f'),0,0,"R",$iPre);
    $oPdf->cell(20,$iAlt,db_formatar($oDadosRegist->nDesconto          ,'f'),0,1,"R",$iPre);    
    
    $aSubTotal['nSalarioFamilia']       += $oDadosRegist->nSalarioFamilia;
    $aSubTotal['nSalarioMaternidade']   += $oDadosRegist->nSalarioMaternidade;
    $aSubTotal['nBase']                 += $oDadosRegist->nBase;
    $aSubTotal['nDesconto']             += $oDadosRegist->nDesconto;
    $aSubTotal['nDeducao']              += $oDadosRegist->nDeducao;
    $aSubTotal['nPatronal']             += $oDadosRegist->nPatronal;
    
    $aTotalGeral['nSalarioFamilia']     += $oDadosRegist->nSalarioFamilia;
    $aTotalGeral['nSalarioMaternidade'] += $oDadosRegist->nSalarioMaternidade;
    $aTotalGeral['nBase']               += $oDadosRegist->nBase;
    $aTotalGeral['nDesconto']           += $oDadosRegist->nDesconto;
    $aTotalGeral['nPatronal']           += $oDadosRegist->nPatronal;    
    
  }

  $iTotalRegistros += count($aDadosRegist);
  
  if ( $lQuebraLote == 'l' || $sAgrupa == 'Autônomos' ) {
    
    $oPdf->ln(1);
    $oPdf->cell(95,$iAlt,'TOTAL : '.count($aDadosRegist).' FUNCIONÁRIOS '  ,"T",0,"R",0);
    $oPdf->cell(20,$iAlt,db_formatar($aSubTotal['nSalarioFamilia']    ,'f'),"T",0,"R",0);
    $oPdf->cell(20,$iAlt,db_formatar($aSubTotal['nSalarioMaternidade'],'f'),"T",0,"R",0);
    $oPdf->cell(20,$iAlt,db_formatar($aSubTotal['nBase']              ,'f'),"T",0,"R",0);
    $oPdf->cell(20,$iAlt,db_formatar($aSubTotal['nDesconto']          ,'f'),"T",1,"R",0);
    
    $oPdf->ln(3);
    $oPdf->cell(50,6,'DEDUÇÕES .... :  '.db_formatar($aSubTotal['nDeducao'] ,'f'),0,0,"R",0);
    $oPdf->cell(50,6,'BASE BRUTA .. :  '.db_formatar($aSubTotal['nBase']    ,'f'),0,0,"R",0);
    $oPdf->cell(50,6,'PERC.PATRONAL :  '.db_formatar($aSubTotal['nPatronal'],'f'),0,1,"R",0);
  
    $nPatronalIndividualQuebra += round($aSubTotal['nPatronal'],2);
    
    if( $prev != $r11_tbprev ){
      $oPdf->cell(50,6,'',0,0,"R",0);
      $oPdf->cell(50,6,'TX. ADMIN   :  '.db_formatar(($aSubTotal['nBase']/100*$r11_txadm ),'f'),0,0,"R",0);
      $oPdf->cell(50,6,'TOTAL       :  '.db_formatar(($aSubTotal['nBase']/100*$r11_txadm )+$aSubTotal['nPatronal']+$aSubTotal['nDesconto'],'f'),0,1,"R",0);
    }
  }
}

$oPdf->ln(3);
$oPdf->setfont('arial','b',8);
$oPdf->cell(95,$iAlt,"TOTAL GERAL: {$iTotalRegistros} FUNCIONÁRIOS "     ,"T",0,"C",0);
$oPdf->cell(20,$iAlt,db_formatar($aTotalGeral['nSalarioFamilia']    ,'f'),"T",0,"R",0);
$oPdf->cell(20,$iAlt,db_formatar($aTotalGeral['nSalarioMaternidade'],'f'),"T",0,"R",0);
$oPdf->cell(20,$iAlt,db_formatar($aTotalGeral['nBase']              ,'f'),"T",0,"R",0);
$oPdf->cell(20,$iAlt,db_formatar($aTotalGeral['nDesconto']          ,'f'),"T",1,"R",0);

$oPdf->ln(3);
$oPdf->cell(50,6,'DEDUÇÕES .... :  '.db_formatar($aTotalGeral['nDeducao']   ,'f'),0,0,"R",0);
$oPdf->cell(50,6,'BASE BRUTA .. :  '.db_formatar($aTotalGeral['nBase']      ,'f'),0,0,"R",0);
$oPdf->cell(50,6,'PERC.PATRONAL :  '.db_formatar($aTotalGeral['nPatronal']  ,'f'),0,1,"R",0);


if ( $nPatronalIndividualQuebra == 0 ) {
  $oPdf->cell(50,6,'PERC.PATRONAL :  '.db_formatar($aTotalGeral['nPatronal']  ,'f'),0,1,"R",0);
} else {
  $oPdf->cell(50,6,'PERC.PATRONAL :  '.db_formatar($nPatronalIndividualQuebra ,'f'),0,1,"R",0);
}

if( $prev != $r11_tbprev){
  $oPdf->cell(50,6,'',0,0,"R",0);
  $oPdf->cell(50,6,'TX. ADMIN   :  '.db_formatar(($aTotalGeral['nBase']/100*$r11_txadm ),'f'),0,0,"R",0);
  $oPdf->cell(50,6,'TOTAL       :  '.db_formatar(($aTotalGeral['nBase']/100*$r11_txadm )+$aTotalGeral['nPatronal']+$aTotalGeral['nDesconto'],'f'),0,1,"R",0);
}

$oPdf->Output();
?>