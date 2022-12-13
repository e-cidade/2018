<?php
/**
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

set_time_limit(0);

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_sql.php';
require_once 'libs/db_libpessoal.php';

require_once 'classes/db_iptubase_classe.php';
require_once 'classes/db_issbase_classe.php';
require_once 'classes/db_propri_classe.php';
require_once 'classes/db_promitente_classe.php';

require_once 'dbforms/db_funcoes.php';

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($_POST);

$xtipo = "'x'";

switch ($opcao) {
  case 'salario':
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
      require_once 'pes3_gerfinanc018_salario.php';
      exit;
    }

    $sigla          = 'r14_';
    $arquivo        = 'gerfsal';
    $sTituloCalculo = 'Salário';
    break;

  case 'complementar':
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
      require_once("pes3_gerfinanc018_auxiliar.php");
      exit;
    }

    $sigla          = 'r48_';
    $arquivo        = 'gerfcom';
    $sTituloCalculo = 'Complementar';
    break;

  case 'ferias':
    $sigla          = 'r31_';
    $arquivo        = 'gerffer';
    $xtipo          = ' r31_tpp ';
    $sTituloCalculo = 'Férias';
    break;

  case 'rescisao':
    $sigla          = 'r20_';
    $arquivo        = 'gerfres';
    $xtipo          = ' r20_tpp ';
    $sTituloCalculo = 'Rescisão';
    break;

  case 'adiantamento':
    $sigla          = 'r22_';
    $arquivo        = 'gerfadi';
    $sTituloCalculo = 'Adiantamento';
    break;

  case '13salario':
    $sigla          = 'r35_';
    $arquivo        = 'gerfs13';
    $sTituloCalculo = '13º Salário';
    break;

  case 'fixo':
    $sigla          = 'r53_';
    $arquivo        = 'gerffx';
    $sTituloCalculo = 'Calculo Fixo';
    break;

  case 'previden':
    $sigla          = 'r60_';
    $arquivo        = 'previden';
    $sTituloCalculo = 'Previdência';
    break;

  case 'irf':
    $sigla          = 'r61_';
    $arquivo        = 'ajusteir';
    $sTituloCalculo = 'IRF';
    break;

  case 'gerfprovfer':
    $sigla          = 'r93_';
    $arquivo        = 'gerfprovfer';
    $sTituloCalculo = 'Proventos de Férias';
    break;

  case 'gerfprovs13':
    $sigla          = 'r94_';
    $arquivo        = 'gerfprovs13';
    $sTituloCalculo = 'Proventos de 13º salário';
    break;

  default:
    echo "SEM CALCULO NO MÊS";
    $sTituloCalculo = 'Sem Calculo';
    $opcao = "";
    break;
}

if ($opcao != '') {

if ($opcao != 'previden' && $opcao != 'irf'){
  
  $sql = "  select '1' as ordem ,
                   {$sigla}rubric as rubrica,
                   case 
                     when rh27_pd = 3 then 0 
                     else case 
                            when {$sigla}pd = 1 then {$sigla}valor 
                            else 0 
                          end 
                   end as Provento,
                   case 
                     when rh27_pd = 3 then 0 
                     else case 
                            when {$sigla}pd = 2 then {$sigla}valor 
                            else 0 
                          end 
                   end as Desconto,
                   {$sigla}quant as quant, 
                   rh27_descr, 
                   {$xtipo} as tipo , 
                   case 
                     when rh27_pd = 3 then 'Base' 
                     else case 
                            when {$sigla}pd = 1 then 'Provento' 
            	              else 'Desconto' 
            	            end 
                   end as provdesc
              from {$arquivo} 
                   inner join rhrubricas on rh27_rubric = {$sigla}rubric 
                                        and rh27_instit = ".db_getsession("DB_instit")."
              ".bb_condicaosubpesproc($sigla,$ano."/".$mes)." 
               and {$sigla}regist = $matricula 
               and {$sigla}pd != 3 
  
        union
        
            select '2' as ordem,
                   'R950'::varchar(4) as rubrica,
                   provento,
                   desconto,
                   0 as quant, 
                   'TOTAL'::varchar(40) , 
                   ''::varchar(1) as tipo , 
                   ''::varchar(10) as provdesc
              from ( select sum(case when {$sigla}pd = 1 then {$sigla}valor else 0 end ) as provento,
                            sum(case when {$sigla}pd = 2 then {$sigla}valor else 0 end ) as desconto
                       from {$arquivo}
                            inner join rhrubricas on rh27_rubric = {$sigla}rubric 
                                                 and rh27_instit = ".db_getsession("DB_instit")."
                       ".bb_condicaosubpesproc($sigla,$ano."/".$mes)." 
                        and {$sigla}regist = $matricula 
                        and {$sigla}pd != 3
                   ) as  x
  
        union
  
            select '3' as ordem,
                   {$sigla}rubric as rubrica,
                   {$sigla}valor as Provento,
                   0 as Desconto ,
                   {$sigla}quant as quant, 
                   rh27_descr, 
                   {$xtipo} as tipo , 
                   case 
                     when rh27_pd = 3 then 'Base' 
                     else case 
                            when {$sigla}pd = 1 then 'Provento' 
            	              else 'Desconto' 
            	            end 
                   end as provdesc
              from {$arquivo} 
                   inner join rhrubricas on rh27_rubric = {$sigla}rubric and rh27_instit = ".db_getsession("DB_instit")."
              ".bb_condicaosubpesproc($sigla,$ano."/".$mes)." 
               and {$sigla}regist = $matricula 
               and {$sigla}pd = 3 
    
    order by 1,2 ";  
                   

} else if ($opcao == 'previden') {

  $sql = " select previden.*,
                  rhrubricas.rh27_rubric,
                  rhrubricas.rh27_descr,
                  rhrubricas.rh27_pd 
             from previden
                  inner join rhrubricas on rh27_rubric = r60_rubric 
                                       and rh27_instit = ".db_getsession("DB_instit")." 
                  inner join rhpessoal on rh01_numcgm = $numcgm 
                                      and rh01_instit =  ".db_getsession("DB_instit")."
                                      and r60_regist = rh01_regist
            where r60_anousu = $ano   
              and r60_mesusu = $mes  
              and r60_numcgm = $numcgm
              and r60_tbprev = $tbprev
         order by r60_numcgm, 
                  r60_tbprev, 
                  r60_rubric, 
                  r60_regist, 
                  r60_folha ";

} else if ($opcao == 'irf') {

  $sql = " select ajusteir.*,
                  rhrubricas.rh27_rubric,
                  rhrubricas.rh27_descr,
                  rhrubricas.rh27_pd 
             from ajusteir
                  inner join rhrubricas on rh27_rubric = r61_rubric and rh27_instit = ".db_getsession("DB_instit")."
                  inner join rhpessoal on rh01_numcgm = $numcgm 
                                      and rh01_instit =  ".db_getsession("DB_instit")."
                                      and r61_regist = rh01_regist
            where r61_anousu = $ano   
              and r61_mesusu = $mes  
              and r61_numcgm = $numcgm
         order by r61_numcgm,  
                  r61_rubric, 
                  r61_regist, 
                  r61_folha ";
  
}
//die($sql);
$result = db_query($sql);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<link rel="stylesheet" type="text/css" href="estilos.css">
<style type="text/css">
html, body, table {
  overflow: hidden;
}

#tabela-calculos, #tabela-calculos tr, #tabela-calculos td, #tabela-calculos th{
  border: 1px solid #bbb;
}


#tabela-calculos tr:nth-child(odd) {
  background-color: #EEEEEE !important;
}

#tabela-calculos tr:nth-child(even) {
  background-color: #FFFFFF !important;
}

#tabela-calculos tr:first-child {
  border-right:1px outset #D3D3D3;  
  padding:0;
  margin:0;
  white-space:nowrap;
  overflow: hidden;
}
</style>
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body onload="js_alteraTamanho(); " >
<center>
<form name="form1" method="post">

<table width="100%" id="tabela-calculos" cellspacing="0">
<tr>
<?
if ($opcao != 'previden' && $opcao != 'irf'){
?>
     <th clase="borda" width="25" nowrap> Fórmula(*) </th>
     <th class="borda" width="25" nowrap> Código     </th>
     <th class="borda"            nowrap> Descrição  </th>
     <th class="borda" width="80" nowrap> Quantidade </th>
     <th class="borda" width="80" nowrap> Proventos  </th>
     <th class="borda" width="80" nowrap> Descontos  </th>
     <th class="borda" width="90" nowrap> Prov/Desc  </th>

    <?if ($opcao == 'ferias' || $opcao == 'rescisao'){?>
       <th class="borda" nowrap>Tipo</th>
    <?}?>
   </tr>

<?
  $tam_form = strlen(@$rub_formula);
  $cor      = "";

  for ($x=0; $x < pg_numrows($result); $x++) {
    
    db_fieldsmemory($result,$x,true);
   
    if($ordem == '2'){
?>
         <tr>
           <td align="center" colspan="4" style="font-size:12px" nowrap  bgcolor="#DDDDDD">&nbsp;<strong>TOTAL<strong></td>
           <td align="right"  style="font-size:12px" nowrap  bgcolor="#DDDDDD">&nbsp;<strong><?=db_formatar($provento,'f')?></strong></td>
           <td align="right"  style="font-size:12px" nowrap  bgcolor="#DDDDDD">&nbsp;<strong><?=db_formatar($desconto,'f')?></strong></td>
           <td align="left"   style="font-size:12px" nowrap  bgcolor="#DDDDDD">&nbsp;<strong><?=$provdesc?></strong></td>
           <?if ($opcao == 'ferias' || $opcao == 'rescisao'){?>
              <td align="left" style="font-size:12px" nowrap  bgcolor="#DDDDDD">&nbsp;<?=$tipo?></td>
           <?}?>
           </tr>

           <tr>
           <td align="center" colspan="4"   style="font-size:12px" nowrap  bgcolor="#DDDDDD">&nbsp;<strong>LÍQUIDO<strong></td>
           <td colspan="2"    align="right" style="font-size:12px" nowrap  bgcolor="#DDDDDD">&nbsp;<strong><?=db_formatar($provento-$desconto,'f')?></strong></td>
           <td align="left"   style="font-size:12px"               nowrap  bgcolor="#DDDDDD">&nbsp;<strong><?=$provdesc?></strong></td>
           <?if ($opcao == 'ferias' || $opcao == 'rescisao'){?>
              <td align="left" style="font-size:12px" nowrap  bgcolor="#DDDDDD">&nbsp;<?=$tipo?></td>
           <?}?>
         </tr>
<?
    } else {
?>
         <tr>
<?
      global $subpes;
      $subpes = db_anofolha()."/".db_mesfolha();
      global $basesr;
      $achou  = false;
      
      $condicaoaux  = " where rh54_base = ".db_sqlformat( $bases);
      $condicaoaux .= " and rh54_regist = ".db_sqlformat( $matricula );
      
      if ( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )) {
        
        $condicaoaux .= " and rh54_rubric = ".db_sqlformat( $rubrica );
        
        if ( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )) {
          $achou = true;
        }
        
      } else {
        
        $condicaoaux  = " and r09_base = ".db_sqlformat( $bases );
        $condicaoaux .= " and r09_rubric = ".db_sqlformat( $rubrica );
        
        if ( db_selectmax( "basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )) {
          $achou = true;
        }
      }
      
      $condicao = "1"; 
      $pos      = db_at($rubrica,@$rub_cond);
      
      if ($pos > 0) {
        $condicao = db_substr($rub_cond,$pos+4,1); 
      }
      
      $pos  = db_at($rubrica,@$rub_formula);
      $pos1 = db_at(",",@$rub_formula);
      
      if ($pos > 0) {
        $formula     = db_substr($rub_formula,$pos+5,($pos1-($pos+5))); 
        $rub_formula = db_substr($rub_formula,$pos1+1,$tam_form-($pos+1)); 
      }
      
      ?>
            <?php if(isset($formula)) { ?>
                    <td title="<?=$formula?>" align="center" style="font-size:12px" nowrap ><?=$condicao?></td>
            <?php } else { ?>
                    <td title="" align="center" style="font-size:12px" nowrap >&nbsp</td>
            <?php } ?>
      <?

      if ($achou) {
        
        if(db_at($bases.$rubrica,$rub_bases) > 0){
      ?>
          <td align="left"  style="font-size:12px" nowrap>&nbsp;#B<?db_ancora($rubrica,"js_Pesquisarubrica('$rubrica')",1)?>&nbsp;</td>
      <?} else {?>
          <td align="left"  style="font-size:12px" nowrap>&nbsp;&nbsp;&nbsp;#<?db_ancora($rubrica,"js_Pesquisarubrica('$rubrica')",1)?>&nbsp;</td>
      <?}
      
      } else {
        
        if(db_at($bases.$rubrica,@$rub_bases) > 0){
      ?>
          <td align="left"  style="font-size:12px" nowrap >&nbsp;&nbsp;&nbsp;B<?db_ancora($rubrica,"js_Pesquisarubrica('$rubrica')",1)?>&nbsp;</td>
      <?} else {?>
          <td align="left"  style="font-size:12px" nowrap >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?db_ancora($rubrica,"js_Pesquisarubrica('$rubrica')",1)?>&nbsp;</td>
      <?}
      
      }?> 
          <td align="left"  style="font-size:12px" nowrap >&nbsp;<?=strtoupper($rh27_descr)?></td>
          <td align="right" style="font-size:12px" nowrap >&nbsp;<?=db_formatar($quant,'f')?></td>
          <td align="right" style="font-size:12px" nowrap >&nbsp;<?=db_formatar($provento,'f')?></td>
          <td align="right" style="font-size:12px" nowrap >&nbsp;<?=db_formatar($desconto,'f')?></td>
          <td align="left"  style="font-size:12px" nowrap >&nbsp;<?=$provdesc?></td>
           
     <?if ($opcao == 'ferias' || $opcao == 'rescisao'){?>
          <td align="left"  style="font-size:12px" nowrap >&nbsp;<?=$tipo?></td>
     <?}?>
        </tr>
<?
    }
  }

} else if ($opcao == 'previden') {
?>
     <th class="borda" style="font-size:12px" nowrap>Código</th>
     <th class="borda" style="font-size:12px" nowrap>Descrição</th>
     <th class="borda" style="font-size:12px" nowrap>Matricula</th>
     <th class="borda" style="font-size:12px" nowrap>Tipo</th>
     <th class="borda" style="font-size:12px" nowrap>Base</th>
     <th class="borda" style="font-size:12px" nowrap>Desconto</th>
   </tr>
<?

  $cor          = "#EFE029";
  $tot_base_sal = 0;
  $tot_desc_sal = 0;
  $tot_base_13  = 0;
  $tot_desc_13  = 0;
  $tot_base_fer = 0;
  $tot_desc_fer = 0;
  
  for($x=0;$x<pg_numrows($result);$x++){
    
    db_fieldsmemory($result,$x,true);
    
    if ($cor=="#EFE029") {
      $cor = "#E4F471";
    } else if ($cor=="#E4F471") {
      $cor = "#EFE029";
    } 

    if ($r60_rubric == 'R985') {
      $tot_base_sal += $r60_base;
      $tot_desc_sal += $r60_novod;
    } else if ($r60_rubric == 'R986') {
      $tot_base_13  += $r60_base;
      $tot_desc_13  += $r60_novod;
    } else if ($r60_rubric == 'R987') {
      $tot_base_fer += $r60_base;
      $tot_desc_fer += $r60_novod;
    }
?>
    <tr>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rh27_rubric?></td>
      <td align="left"   style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=strtoupper($rh27_descr)?></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r60_regist?></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r60_folha?></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($r60_base,'f')?></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($r60_novod,'f')?></td>
    </tr>

<?
  }
?>
    <tr>
      <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong>Total Base Salário</strong></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_base_sal,'f')?></strong></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_desc_sal,'f')?></strong></td>
    </tr>
    <tr>
      <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong>Total Base 13 Salário</strong></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_base_13,'f')?></strong></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_desc_13,'f')?></strong></td>
    </tr>
    <tr>
      <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong>Total Base Férias</strong></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_base_fer,'f')?></strong></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_desc_fer,'f')?></strong></td>
    </tr>
<?
} else if ($opcao == 'irf') {
?>
     <th class="borda" style="font-size:12px" nowrap>Código</th>
     <th class="borda" style="font-size:12px" nowrap>Descrição</th>
     <th class="borda" style="font-size:12px" nowrap>Matricula</th>
     <th class="borda" style="font-size:12px" nowrap>Tipo</th>
     <th class="borda" style="font-size:12px" nowrap>Base</th>
     <th class="borda" style="font-size:12px" nowrap>Desconto</th>
   </tr>
<?

  $cor          = "#EFE029";
  $tot_base_sal = 0;
  $tot_desc_sal = 0;
  $tot_base_13  = 0;
  $tot_desc_13  = 0;
  $tot_base_fer = 0;
  $tot_desc_fer = 0;
  
  for ($x=0; $x < pg_numrows($result); $x++) {
    
    db_fieldsmemory($result,$x,true);
    
    if ($cor=="#EFE029") {
      $cor="#E4F471";
    } else if ($cor=="#E4F471") {
      $cor="#EFE029";
    } 

    if ($r61_rubric == 'R981') {
      $tot_base_sal += $r61_base;
      $tot_desc_sal += $r61_novod;
    } else if ($r61_rubric == 'R982') {
      $tot_base_13  += $r61_base;
      $tot_desc_13  += $r61_novod;
    } else if ($r61_rubric == 'R983') {
      $tot_base_fer += $r61_base;
      $tot_desc_fer += $r61_novod;
    }
?>
    <tr>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rh27_rubric?></td>
      <td align="left"   style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=strtoupper($rh27_descr)?></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r61_regist?></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r61_folha?></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($r61_base,'f')?></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($r61_novod,'f')?></td>
    </tr>

<?
  }
?>
    <tr>
      <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong>Total Base Salário</strong></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_base_sal,'f')?></strong></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_desc_sal,'f')?></strong></td>
    </tr>
    <tr>
      <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong>Total Base 13 Salário</strong></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_base_13,'f')?></strong></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_desc_13,'f')?></strong></td>
    </tr>
    <tr>
      <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong>Total Base Férias</strong></td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="center" style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;</td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_base_fer,'f')?></strong></td>
      <td align="right"  style="font-size:12px" nowrap bgcolor="#FFCC66">&nbsp;<strong><?=db_formatar($tot_desc_fer,'f')?></strong></td>
    </tr>
<?

}

}

?>
</table>

    <input type="hidden" name="matricula" value="<?=@$matricula?>">
    <input type="hidden" name="numcgm" value="<?=@$numcgm?>">

</form>
</center>
</body>
</html>
<script>
function js_mostracgm(cgm){
  parent.func_nome.jan.location.href = 'prot3_conscgm002.php?fechar=func_nome&numcgm='+cgm;
  parent.func_nome.mostraMsg();
  parent.func_nome.show();
  parent.func_nome.focus();
}
function js_mostrabic_matricula(matricula){
  parent.func_nome.jan.location.href = 'cad3_conscadastro_002.php?cod_matricula='+matricula;
  parent.func_nome.mostraMsg();
  parent.func_nome.show();
  parent.func_nome.focus();
}
// esta funcao é utilizada quando clicar na inscricao após pesquisar
// a mesma
function js_mostrabic_inscricao(inscricao){
  parent.func_nome.jan.location.href = 'iss3_consinscr003.php?numeroDaInscricao='+inscricao;
  parent.func_nome.mostraMsg();
  parent.func_nome.show();
  parent.func_nome.focus();
}


function js_relatorio(){
  jan = window.open('pes3_gerfinanc017.php?opcao=<?=$opcao?>&numcgm='+document.form1.numcgm.value+'&matricula='+document.form1.matricula.value+'&ano=<?=$ano?>&mes=<?=$mes?>&tbprev=<?=$tbprev?>','sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
	  
}

function js_Pesquisarubrica(rubrica) {
 var janela = js_OpenJanelaIframe('top.corpo','db_iframe_pesquisarubrica','pes1_rhrubricas006.php?tela_pesquisa=true&chavepesquisa='+rubrica,'Pesquisa',true,'20');
 janela.moldura.style.zIndex = 9999;
}


  parent.document.formatu.opcao.value = '<?=$opcao?>';



  /**
   * Altera o Titulo da Folha.
   */
   parent.document.getElementById('tituloFolha').innerHTML = "<?=$sTituloCalculo?>";

   function js_alteraTamanho() {      
      
      var body = document.body,
          html = document.documentElement;

      var height = Math.max( body.scrollHeight, body.offsetHeight, 
                             html.clientHeight, html.scrollHeight, html.offsetHeight );
  
      parent.document.getElementById('calculoFolha').style.height = height + 'px';
      parent.iframeLoaded();
   }

  //js_removeObj('processamento_calculo_ponto');

</script>
