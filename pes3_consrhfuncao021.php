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


set_time_limit(0);
require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_sql.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_rhfuncao_classe.php");
include ("classes/db_rhregime_classe.php");
$clrhfuncao = new cl_rhfuncao();
$clrhregime = new cl_rhregime();
$clrhfuncao->rotulo->label();
/*
include("classes/db_gerfsal_classe.php");
include("classes/db_gerfadi_classe.php");
include("classes/db_gerffx_classe.php");
include("classes/db_gerfcom_classe.php");
include("classes/db_gerffer_classe.php");
include("classes/db_gerfs13_classe.php");
include("classes/db_gerfres_classe.php");
*/

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_SERVER_VARS);

$where = " ";
if(isset($colunas1) && $colunas1!=""){
   $where = " and rh30_codreg in (".$colunas1.") ";
}
      $arr_valtotal = Array();
      $arr_valinati = Array();
      $arr_valativo = Array();
      $arr_valpensi = Array();
      if(isset($funcao) && trim($funcao)!=""){
	  	$porfuncao = true;
  	 	$result_funcionarios =	  	 
	  	$clrhfuncao->sql_record($clrhfuncao->sql_query_cgm(null,"
           rh01_regist as r01_regist,
           z01_nome,
           rh30_descr,
           rh30_codreg,
           case when rh30_vinculo='A' 
                then 'ATIVO' 
                else case when rh30_vinculo='I' 
                           then 'INATIVO' 
                           else 'PENSIONISTA' 
                end 
           end as vinculo,
           r70_estrut as r13_codigo,
           r70_descr as r13_descr",
           "z01_nome",
           "    rh02_anousu  = $ano
            and rh02_mesusu  = $mes
					 and rh02_instit = ".db_getsession("DB_instit")."
           and rh37_funcao = $funcao
			     and rh37_instit = ".db_getsession("DB_instit")."
           $where
           and rh05_seqpes is null
           ")); 
        $sql1 = "
				select 
           rh01_regist as r01_regist,
           z01_nome,
           rh30_descr,
           rh30_codreg,
           case when rh30_vinculo='A' 
                then 'ATIVO' 
                else case when rh30_vinculo='I' 
                           then 'INATIVO' 
                           else 'PENSIONISTA' 
                end 
           end as vinculo,
           r70_estrut as r13_codigo,
           r70_descr  as r13_descr
     from rhfuncao 
          inner join rhpessoalmov  on rhpessoalmov.rh02_funcao  = rhfuncao.rh37_funcao
		                              and rhpessoalmov.rh02_anousu  = $ano
		                              and rhpessoalmov.rh02_mesusu  = $mes
		                              and rhpessoalmov.rh02_instit  = ".db_getsession("DB_instit")."
		      inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist 
          left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
          inner join rhregime  on rhregime.rh30_codreg  = rhpessoalmov.rh02_codreg
		                          and rhregime.rh30_instit  = rhpessoalmov.rh02_instit 
          inner join cgm       on cgm.z01_numcgm        = rhpessoal.rh01_numcgm 
          inner join rhlota    on rhlota.r70_codigo     = rhpessoalmov.rh02_lota
		                          and rhlota.r70_instit     = rhpessoalmov.rh02_instit 
          where rh37_funcao = $funcao
			      and rh37_instit = ".db_getsession("DB_instit")."
           $where
           and rh05_seqpes is null
					  order by z01_nome ";
		    $result_funcionarios = pg_query($sql1);
        if(pg_numrows($result_funcionarios) == 0){
      	  db_msgbox("Cargo não encontrado");
      	  echo "<script>parent.location.href = 'pes3_consrhfuncao001.php'</script>";
        }
	  } else {
	  	$porfuncao = false;
        $result_funcoes = 
        $clrhfuncao->sql_record($clrhfuncao->sql_query_cgm(null,"
           rh37_funcao,
           rh37_descr,
           rh30_vinculo as r01_tpvinc,
           rh37_vagas,
           count(rh01_regist) as ocupados",
          "rh37_funcao",
          "    rh02_anousu  = $ano
           and rh02_mesusu  = $mes
					 and rh02_instit = ".db_getsession("DB_instit")."
			     and rh37_instit = ".db_getsession("DB_instit")."
           and rh05_seqpes is null
           group by 
               rh37_funcao,
               rh37_descr,
               rh30_vinculo,
               rh37_vagas"));

         $sql1 = "select funcao,
                        rh37_descr,
                        rh37_vagas,
                        sum(ocupados)                 as ocupados,
                        sum(tot_ativos)               as tot_ativos, 
                        sum(tot_inativos)             as tot_inativos,
                        sum(tot_pensionistas)         as tot_pensionistas,
                        (rh37_vagas - sum(ocupados))  as saldo
                   from ( select rh37_funcao as funcao,
                                 rh37_descr,
                                 rh37_vagas,
                                 count(rh01_regist) as ocupados,
                                 sum(case when rh30_vinculo = 'A' then 1 else 0 end) as tot_ativos,
                                 sum(case when rh30_vinculo = 'I' then 1 else 0 end) as tot_inativos,
                                 sum(case when rh30_vinculo = 'P' then 1 else 0 end) as tot_pensionistas
                           from rhfuncao 
                           inner join rhpessoalmov  on rhpessoalmov.rh02_funcao  = rhfuncao.rh37_funcao
                                                   and rhpessoalmov.rh02_anousu  = $ano
                                                   and rhpessoalmov.rh02_mesusu  = $mes
                                                   and rhpessoalmov.rh02_instit  = ".db_getsession("DB_instit")."
                           inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist 
                           left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                           inner join rhregime      on rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg
                                                   and rhregime.rh30_instit      = rhpessoalmov.rh02_instit 
                           inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm 
                           inner join rhlota        on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                   and rhlota.r70_instit         = rhpessoalmov.rh02_instit 
                           where rh37_instit = ".db_getsession("DB_instit")."
                    $where
                and rh05_seqpes is null
              group by rh37_funcao,
                       rh37_descr,
                       rh30_vinculo,
                       rh37_vagas
              order by rh37_funcao) as x 
              group by funcao,
                       rh37_descr,
                       rh37_vagas
              order by funcao ";
		    $result_funcoes = pg_query($sql1);
			  $numrows = pg_numrows($result_funcoes);	
        if($numrows == 0){
      	  db_msgbox("Nenhum cargo encontrado");
      	  echo "<script>parent.location.href = 'pes3_consrhfuncao001.php'</script>";
        }
	  }
    
$result_regime = $clrhregime->sql_record($clrhregime->sql_query_file(null, "rh30_vinculo","", " rh30_instit = ".db_getsession('DB_instit')." and rh30_codreg in (".@$colunas1.")"));
$colunas = "";    
$virgula = "";
for($x = 0; $x < $clrhregime->numrows; $x ++) {
  db_fieldsmemory($result_regime, $x);
  $colunas .= $virgula.strtolower($rh30_vinculo);
  $virgula = ",";
}

//echo $sql;die();
//db_criatabela($result);exit;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style>
.fonte {
  font-family:Arial, Helvetica, sans-serif;
  font-size:12px;
}
td {
  font-family:Arial, Helvetica, sans-serif;
  font-size:12px;

}
th {
  font-family:Arial, Helvetica, sans-serif;
  font-size:12px;
}
</style>
<script>
function MM_reloadPage(init){  //reloads the window if Nav4 resized
  if(init==true) with (navigator){
    if((appName=="Netscape")&&(parseInt(appVersion)==4)) {
      document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; 
  }
}else if(innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH)
   location.reload();
}
MM_reloadPage(true);
</script>
</head>
<body bgcolor=#CCCCCC onload="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>

<form name="form1" method="post">

<tr>
<!--<td colspan="5" align="center"><font face="Arial" size="3"><strong>Outras Matrículas</strong><font><br></td>-->
</tr>

<table border="1" cellpadding="0" cellspacing="0">
<?


if ($porfuncao == true) {
?>
   <tr bgcolor="#FFCC66">
     <th class="borda" style="font-size:12px" nowrap>Registro</th>
     <th class="borda" style="font-size:12px" nowrap>Nome</th>
     <th class="borda" style="font-size:12px" nowrap>Lotação</th>
     <th class="borda" style="font-size:12px" nowrap>Descrição</th>
     <th class="borda" style="font-size:12px" nowrap>Vínculo</th>
   </tr>
    <?
	$cor = "#EFE029";
	$totalvalor = 0;
	$totalquant = 0;
	$totalregis = 0;
	for ($x = 0; $x < pg_numrows($result_funcionarios); $x ++) {
		db_fieldsmemory($result_funcionarios, $x);
		if ($cor == "#EFE029")
			$cor = "#E4F471";
		else if ($cor == "#E4F471")
			$cor = "#EFE029";

    ?>
    <tr>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
        <?db_ancora($r01_regist,"js_consultaregistro('$r01_regist','$funcao');","1");?>
        &nbsp;
      </td>
      <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$z01_nome?></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$r13_codigo?></td>
      <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$r13_descr?></td>
      <td align="left" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rh30_codreg." - ".$rh30_descr?></td>
    </tr>
    <?
	}
	?>
    <?
}else{
    ?>	
   <tr bgcolor="#FFCC66">
     <th class="borda" style="font-size:12px" nowrap>Cargo</th>
     <th class="borda" style="font-size:12px" nowrap>Descrição</th>
     <th class="borda" style="font-size:12px" nowrap>Vagas</th>
     <th class="borda" style="font-size:12px" nowrap>Ativos</th>
     <th class="borda" style="font-size:12px" nowrap>Inativos</th>
     <th class="borda" style="font-size:12px" nowrap>Pensionistas</th>
     <th class="borda" style="font-size:12px" nowrap>Ocupadas</th>
     <th class="borda" style="font-size:12px" nowrap>Saldo</th>
   </tr>
    <?
	$cor = "#EFE029";
	$totalvagas = 0;
	$totalocupa = 0;
	$totalsaldo = 0;
    $totalfunc = 0;
    $totalvaga = 0;
    $totalocup = 0;
    $totalativ = 0;
    $totalinat = 0;
    $totalpens = 0;
    $totalsald = 0;

	$index      = 0;
	$anterior   = "";
  $saldo      = 0;
	for ($x = 0; $x < pg_numrows($result_funcoes); $x ++) {
		db_fieldsmemory($result_funcoes, $x);
        
		if ($cor == "#EFE029")
			$cor = "#E4F471";
		else if ($cor == "#E4F471")
			$cor = "#EFE029";

    $totalfunc += 1;
    $totalvaga += $rh37_vagas; 
    $totalativ += $tot_ativos;
    $totalinat += $tot_inativos; 
    $totalpens += $tot_pensionistas;
    $totalocup += $ocupados;
    $totalsald += $saldo;
    ?>
    <tr>
      <td align="center" style="font-size:12px" nowrap bgcolor="<?=$cor?>">
        <?db_ancora($funcao,"js_consultafuncao('$funcao');","1");?>
        &nbsp;
      </td>
      <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$rh37_descr?></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<?=$rh37_vagas?></td>
      <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$tot_ativos?></td>
      <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$tot_inativos?></td>
      <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$tot_pensionistas?></td>
      <td align="left" style="font-size:12px" bgcolor="<?=$cor?>">&nbsp;<?=$ocupados?></td>
      <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>">&nbsp;<font color="<?=$corsaldo?>"><b><?=$saldo?></b></font></td>
    </tr>
    <?
	}
    ?>
    <tr bgcolor="#FFCC66">
      <td align="right" style="font-size:12px" class="borda" colspan="2"><b>Totais</b></td>
      <td align="right"  style="font-size:12px" class="borda">&nbsp;<b><?=$totalvaga?></b></td>
      <td align="right"  style="font-size:12px" class="borda">&nbsp;<b><?=$totalativ?></b></td>
      <td align="right"  style="font-size:12px" class="borda">&nbsp;<b><?=$totalinat?></b></td>
      <td align="right"  style="font-size:12px" class="borda">&nbsp;<b><?=$totalpens?></b></td>
      <td align="right"  style="font-size:12px" class="borda">&nbsp;<b><?=$totalocup?></b></td>
      <td align="right"  style="font-size:12px" class="borda">&nbsp;<b><?=$totalsald?></b></td>
    </tr>
    <tr bgcolor="#FFCC66">
      <td align="right" style="font-size:12px" class="borda" colspan="2"><b>Total de cargos</b></td>
      <td align="right"  style="font-size:12px" class="borda">&nbsp;<b><?=$totalfunc?></b></td>
    </tr>

    <?
}
    ?>
</table>
</form>
</center>
</body>
<script>
function js_consultaregistro(registro,funcao){
  js_OpenJanelaIframe('top.corpo','db_iframe_conspessoal','pes3_conspessoal002.php?regist='+registro,'Visualização das matriculas cadastradas',true);
}
function js_consultafuncao(funcao){
  parent.location.href = "pes3_consrhfuncao002.php?ano=<?=($ano)?>&mes=<?=($mes)?>&funcao="+funcao;
}
</script>
</html>