<?php
/**
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

set_time_limit(0);

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_propri_classe.php");
require_once("classes/db_promitente_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$xtipo = "'x'";

switch ($opcao) {
  case 'salario':
    $sigla          = 'r10_';
    $arquivo        = 'pontofs';
    $sTituloCalculo = 'Salário';
    
    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
      include_once 'pes3_consponto021_salario.php';
      exit;
    }
    break;

  case 'ferias':
    $sigla          = 'r29_';
    $arquivo        = 'pontofe';
    $xtipo          = ' r29_tpp ';
    $sTituloCalculo = 'Férias';
    break;

  case 'rescisao':
    $sigla          = 'r19_';
    $arquivo        = 'pontofr';
    $xtipo          = ' r19_tpp ';
    $sTituloCalculo = 'Rescisão';
    break;

  case 'adiantamento':
    $sigla          = 'r21_';
    $arquivo        = 'pontofa';
    $sTituloCalculo = 'Adiantamento';
    break;

  case '13salario':
    $sigla          = 'r34_';
    $arquivo        = 'pontof13';
    $sTituloCalculo = '13º Salário';
    break;

  case 'complementar2':
    $sigla          = 'r47_';
    $arquivo        = 'pontocom';
    $sTituloCalculo = 'Complementar';

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
      include_once 'pes3_consponto021_complementar.php';
      exit;
    }
    break;

  case 'suplementar':
    $sigla          = 'r10_';
    $arquivo        = 'pontofs';
    $sTituloCalculo = 'Suplementar';

    if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
      include_once 'pes3_consponto021_suplementar.php';
      exit;
    }
    break;

  case 'fixo':
    $sigla          = 'r90_';
    $arquivo        = 'pontofx';
    $sTituloCalculo = 'Ponto Fixo';
    break;

  case 'provfer':
    $sigla          = 'r91_';
    $arquivo        = 'pontoprovfe';
    $sTituloCalculo = 'Proventos de Férias';
    break;

  case 'provs13':
    $sigla          = 'r92_';
    $arquivo        = 'pontoprovf13';
    $sTituloCalculo = 'Proventos de 13º salário';
    break;

  default:
    echo "SEM CALCULO NO MÊS";
    break;
}

if ($opcao) {
  
  $sql  = " select * from (                                                                                                 ";
  $sql .= "   select {$sigla}rubric as rubrica,                                                                             ";
  $sql .= "        case when rh27_pd = 3 then 0 else case when rh27_pd = 1 then ".$sigla."valor else 0 end end as Provento, ";
  $sql .= "        case when rh27_pd = 3 then 0 else case when rh27_pd = 2 then ".$sigla."valor else 0 end end as Desconto, ";
  $sql .= "        {$sigla}quant as quant,                                                                         ";
  $sql .= "        rh27_descr,                                                                                     ";
  $sql .= "        {$xtipo} as tipo ,                                                                              "; 
  $sql .= "        case                                                                                            ";
  $sql .= "          when rh27_pd = 3 then 'Base'                                                                  "; 
  $sql .= "          else case                                                                                     ";
  $sql .= "                 when rh27_pd = 1 then 'Provento'                                                       "; 
  $sql .= "	               else 'Desconto'                                                                         ";
  $sql .= "	             end                                                                                       ";
  $sql .= "        end as provdesc,                                                                                ";
  $sql .= "        rh27_pd                                                                                         ";
  $sql .= "   from {$arquivo}                                                                                      ";
  $sql .= "        inner join rhrubricas on rh27_rubric = {$sigla}rubric                                           ";
  $sql .= "	  	                        and rh27_instit = {$sigla}instit                                           ";   
  $sql .= "    where {$sigla}regist = {$matricula}                                                                 ";
  $sql .= "      and {$sigla}anousu = {$ano}                                                                       "; 
  $sql .= "      and {$sigla}mesusu = {$mes}                                                                       ";
  $sql .= "      and rh27_pd <> 3                                                                                  ";
  $sql .= " union                                                                                                  ";
  $sql .= " select 'R950'::varchar(4) as rubrica,                                                                  ";
  $sql .= "        provento,                                                                                       ";
  $sql .= "        desconto,                                                                                       ";
  $sql .= "        0 as quant,                                                                                     ";
  $sql .= "        'TOTAL'::varchar(40) ,                                                                          "; 
  $sql .= "        ''::varchar(1) as tipo ,                                                                        ";
  $sql .= "        ''::varchar(10) as provdesc,                                                                    ";
  $sql .= "        '999' as rh27_pd                                                                                ";
  $sql .= "        from                                                                                            ";
  $sql .= "   (select sum(case when rh27_pd = 1 then {$sigla}valor else 0 end ) as provento,                       ";
  $sql .= "           sum(case when rh27_pd = 2 then {$sigla}valor else 0 end ) as desconto                        ";
  $sql .= "   from {$arquivo}                                                                                      ";
  $sql .= "        inner join rhrubricas on rh27_rubric = {$sigla}rubric                                           "; 
  $sql .= "   where {$sigla}regist = $matricula                                                                    ";
  $sql .= "     and {$sigla}anousu = $ano                                                                          ";
  $sql .= "     and {$sigla}mesusu = $mes                                                                          ";
  $sql .= "     and rh27_pd <> 3                                                                                   ";
  $sql .= "   	 ) as  x                                                                                           ";
  $sql .= "union                                                                                                   ";
  $sql .= "  select {$sigla}rubric as rubrica,                                                                     ";
  $sql .= "         {$sigla}valor as Provento,                                                                     ";
  $sql .= "         0 as Desconto ,                                                                                ";
  $sql .= "         {$sigla}quant as quant,                                                                        "; 
  $sql .= "         rh27_descr,                                                                                    ";
  $sql .= "         {$xtipo} as tipo ,                                                                             "; 
  $sql .= "         case                                                                                           ";
  $sql .= "           when rh27_pd = 3 then 'Base'                                                                 "; 
  $sql .= "           else case                                                                                    ";
  $sql .= "                  when rh27_pd = 1 then 'Provento'                                                      "; 
  $sql .= "  	              else 'Desconto'                                                                        ";
  $sql .= "  	           end                                                                                       "; 
  $sql .= "         end as provdesc,                                                                               ";
  $sql .= "         '99999' as rh27_pd                                                                             ";
  $sql .= "  from {$arquivo}                                                                                       ";
  $sql .= "     inner join rhrubricas on rh27_rubric = {$sigla}rubric                                              "; 
  $sql .= "		                       and rh27_instit = {$sigla}instit                                              ";  
  $sql .= "    where {$sigla}regist = $matricula                                                                   ";
  $sql .= "      and {$sigla}anousu = $ano                                                                         ";
  $sql .= "      and {$sigla}mesusu = $mes                                                                         ";
  $sql .= "      and rh27_pd = 3                                                                                   ";
  $sql .= "   ) as x                                                                                               ";
  $sql .= " order by rh27_pd, rubrica                                                                              ";
}
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

  #tabela-calculos, #tabela-calculos tr, #tabela-calculos td, #tabela-calculos th {
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

function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);


</script>
</head>
<body onload="js_alteraTamanho();">
<center>

<form name="form1" method="post">

<table width="100%" id="tabela-calculos" cellspacing="0">

<tr>
<?
if ($opcao != 'previden' && $opcao != 'irf'){
?>
     <th class="borda" width="80" nowrap>Código</th>
     <th class="borda" nowrap>Descrição</th>
     <th class="borda" width="80" nowrap>Quantidade</th>
     <th class="borda" width="80" nowrap>Proventos</th>
     <th class="borda" width="80" nowrap>Descontos</th>
     <th class="borda" width="80" nowrap>Prov/Desc</th>
    <?if ($opcao == 'ferias' || $opcao == 'rescisao'){?>
       <th class="borda" style="font-size:12px" nowrap>Tipo</th>
    <?
     }
     ?>
   </tr>
<?

$cor="#F0F0F0";
    for($x=0;$x<pg_numrows($result);$x++){
	  db_fieldsmemory($result,$x,true);
        if($cor=="#F0F0F0")
           $cor="#FFF";
        else if($cor=="#FFF")
	   $cor="#F0F0F0";
	if($rubrica == 'R950'){
	
?>
           <tr>
           <td align="center" colspan="3" style="font-size:12px" nowrap  bgcolor="#ddd">&nbsp;<strong>TOTAL<strong></td>
           <td align="right" style="font-size:12px" nowrap  bgcolor="#ddd">&nbsp;<strong><?=db_formatar($provento,'f')?></strong></td>
           <td align="right" style="font-size:12px" nowrap  bgcolor="#ddd">&nbsp;<strong><?=db_formatar($desconto,'f')?></strong></td>
           <td align="left" style="font-size:12px" nowrap  bgcolor="#ddd">&nbsp;<strong><?=$provdesc?></strong></td>
           <?if ($opcao == 'ferias' || $opcao == 'rescisao'){?>
              <td align="left" style="font-size:12px" nowrap  bgcolor="#ddd">&nbsp;<?=$tipo?></td>
	   <?}?>
           </tr>
           <tr>
           <td align="center" colspan="3" style="font-size:12px" nowrap  bgcolor="#ddd">&nbsp;<strong>LÍQUIDO<strong></td>
           <td colspan="2" align="right" style="font-size:12px" nowrap  bgcolor="#ddd">&nbsp;<strong><?=db_formatar($provento-$desconto,'f')?></strong></td>
           <td align="left" style="font-size:12px" nowrap  bgcolor="#ddd">&nbsp;<strong><?=$provdesc?></strong></td>
           <?if ($opcao == 'ferias' || $opcao == 'rescisao'){?>
              <td align="left" style="font-size:12px" nowrap  bgcolor="#ddd">&nbsp;<?=$tipo?></td>
	   <?}?>
           </tr>
<?
         }else{
?>
           <tr>
           <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rubrica?>&nbsp;</td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rh27_descr?>&nbsp;</td>
           <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($quant,'f')?>&nbsp;</td>
           <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($provento,'f')?>&nbsp;</td>
           <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($desconto,'f')?>&nbsp;</td>
           <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$provdesc?>&nbsp;</td>
           <?if ($opcao == 'ferias' || $opcao == 'rescisao'){?>
              <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$tipo?></td>
	   <?}?>
           </tr>
<?
        }
    }

}elseif ($opcao == 'previden'){
?>
     <th class="borda" style="font-size:12px" nowrap>Código</th>
     <th class="borda" style="font-size:12px" nowrap>Descrição</th>
     <th class="borda" style="font-size:12px" nowrap>Matricula</th>
     <th class="borda" style="font-size:12px" nowrap>Tipo</th>
     <th class="borda" style="font-size:12px" nowrap>Base</th>
     <th class="borda" style="font-size:12px" nowrap>Desconto</th>
   </tr>
<?

$cor="#EFE029";
    $tot_base_sal = 0;
    $tot_desc_sal = 0;
    $tot_base_13  = 0;
    $tot_desc_13  = 0;
    $tot_base_fer = 0;
    $tot_desc_fer = 0;
    for($x=0;$x<pg_numrows($result);$x++){
	  db_fieldsmemory($result,$x,true);
        if($cor=="#EFE029")
           $cor="#E4F471";
        else if($cor=="#E4F471")
	   $cor="#EFE029";

	if($r60_rubric == 'R985'){
          $tot_base_sal += $r60_base;
          $tot_desc_sal += $r60_novod;
	}elseif($r60_rubric == 'R986'){
          $tot_base_13  += $r60_base;
          $tot_desc_13  += $r60_novod;
	}elseif($r60_rubric == 'R987'){
          $tot_base_fer += $r60_base;
          $tot_desc_fer += $r60_novod;
	}
?>
        <tr>
          <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rh27_rubric?></td>
          <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rh27_descr?></td>
          <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r60_regist?></td>
          <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r60_folha?></td>
          <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($r60_base,'f')?></td>
          <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($r60_novod,'f')?></td>
	</tr>

<?
   }
?>
        <tr>
          <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong>Total Base Salário</strong></td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_base_sal,'f')?></strong></td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_desc_sal,'f')?></strong></td>
	</tr>
        <tr>
          <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong>Total Base 13 Salário</strong></td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_base_13,'f')?></strong></td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_desc_13,'f')?></strong></td>
	</tr>
        <tr>
          <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong>Total Base Férias</strong></td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_base_fer,'f')?></strong></td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_desc_fer,'f')?></strong></td>
	</tr>
<?
}elseif ($opcao == 'irf'){
?>
     <th class="borda" style="font-size:12px" nowrap>Código</th>
     <th class="borda" style="font-size:12px" nowrap>Descrição</th>
     <th class="borda" style="font-size:12px" nowrap>Matricula</th>
     <th class="borda" style="font-size:12px" nowrap>Tipo</th>
     <th class="borda" style="font-size:12px" nowrap>Base</th>
     <th class="borda" style="font-size:12px" nowrap>Desconto</th>
   </tr>
<?

$cor="#EFE029";
    $tot_base_sal = 0;
    $tot_desc_sal = 0;
    $tot_base_13  = 0;
    $tot_desc_13  = 0;
    $tot_base_fer = 0;
    $tot_desc_fer = 0;
    for($x=0;$x<pg_numrows($result);$x++){
	  db_fieldsmemory($result,$x,true);
        if($cor=="#EFE029")
           $cor="#E4F471";
        else if($cor=="#E4F471")
	   $cor="#EFE029";

	if($r61_rubric == 'R981'){
          $tot_base_sal += $r61_base;
          $tot_desc_sal += $r61_novod;
	}elseif($r61_rubric == 'R982'){
          $tot_base_13  += $r61_base;
          $tot_desc_13  += $r61_novod;
	}elseif($r61_rubric == 'R983'){
          $tot_base_fer += $r61_base;
          $tot_desc_fer += $r61_novod;
	}
?>
        <tr>
          <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rh27_rubric?></td>
          <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rh27_descr?></td>
          <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r61_regist?></td>
          <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r61_folha?></td>
          <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($r61_base,'f')?></td>
          <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=db_formatar($r61_novod,'f')?></td>
	</tr>

<?
   }
?>
        <tr>
          <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong>Total Base Salário</strong></td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_base_sal,'f')?></strong></td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_desc_sal,'f')?></strong></td>
	</tr>
        <tr>
          <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong>Total Base 13 Salário</strong></td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_base_13,'f')?></strong></td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_desc_13,'f')?></strong></td>
	</tr>
        <tr>
          <td colspan="2" align="left" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong>Total Base Férias</strong></td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="center" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;</td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_base_fer,'f')?></strong></td>
          <td align="right" style="font-size:12px" nowrap bgcolor="#ddd">&nbsp;<strong><?=db_formatar($tot_desc_fer,'f')?></strong></td>
	</tr>
<?

}

//}

?>
</table>

<input type="hidden" name="matricula" value="<?=@$matricula?>">
<input type="hidden" name="numcgm" value="<?=@$numcgm?>">

</form>
</center>
</body>
</html>
<script>
  /**
   * Altera o Titulo da Folha.
   */
   parent.document.getElementById('tituloFolha').innerHTML = "<?=$sTituloCalculo?>";

   function js_alteraTamanho() {      
      
      var body = document.body,
          html = document.documentElement;

      
  
      parent.document.getElementById('calculoFolha').style.height = html.scrollHeight + 'px';
      parent.iframeLoaded();
   }

</script>