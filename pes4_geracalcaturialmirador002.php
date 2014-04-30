<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_selecao_classe.php");
require_once("classes/db_rhdepend_classe.php");
require_once("libs/db_utils.php");
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0"  topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table>
<tr height=25><td>&nbsp;</td></tr>
</table>
<?
if ($_POST) {

	db_criatermometro('termometro','Concluido...','blue',1);
  flush();

  $oPost = db_utils::postMemory($_POST);

  $ano          = $oPost->ano;
  $mes          = $oPost->mes;
  $tipo_arquivo = $oPost->tipo_arquivo;
  $data_mudanca = implode("-", array_reverse(explode("/",$oPost->data_mudanca)));

  if(isset($oPost->rubricas) && count($oPost->rubricas)>0) {
    $sRubricas    = "'".implode("','", $oPost->rubricas)."'";
  }

  $sDBdatausu = date('Y-m-d',db_getsession("DB_datausu"));
  $iDBinstit  = db_getsession("DB_instit");

  $sInsalubre = "false as insalubre ";
  if (isset($sRubricas)) {

    $sInsalubre  = "exists (select 1 ";
    $sInsalubre .= "        from gerfsal ";
    $sInsalubre .= "       where r14_regist = rh01_regist ";
    $sInsalubre .= "         and r14_anousu={$ano} ";
    $sInsalubre .= "         and r14_mesusu={$mes} ";
    $sInsalubre .= "         and r14_rubric in({$sRubricas}) ";
    $sInsalubre .= "         and r14_instit={$iDBinstit}) as insalubre ";
  }

  switch ($tipo_arquivo) {

  	case 'A':

  		$sSomaTempoAnterior  = "(select round(sum(h16_dtterm - h16_dtconc)/30) as tempo_contrib ";
      $sSomaTempoAnterior .= "          from assenta ";
      $sSomaTempoAnterior .= "               inner join tipoasse on h12_codigo = h16_assent ";
      $sSomaTempoAnterior .= "         where h12_reltot > 0 ";
			$sSomaTempoAnterior .= "           and h16_regist = rh01_regist ";
			$sSomaTempoAnterior .= "        having sum(h16_dtterm - h16_dtconc) <> 0 ) as tempocontrib ";

			$sTempoServicoAtual  = "date_part('year',age(cast('{$sDBdatausu}' as date), rh01_admiss)) as temposervico  ";
			$sTempoCargoAtual    = " ( (extract(year from '{$sDBdatausu}'::date) - extract(year from rh01_admiss)) * 12 + ";
      $sTempoCargoAtual   .= "   (extract(month from '{$sDBdatausu}'::date) - extract(month from rh01_admiss)) ) as tempocargoatual";

			$sWhere  = "rh30_vinculo = 'A' and rh30_regime = 1 ";

			break;

  	case 'I':

			$sSomaTempoAnterior  = "(select round(sum(h16_dtterm - h16_dtconc)/30) ";
			$sSomaTempoAnterior .= "          from assenta ";
			$sSomaTempoAnterior .= "               inner join tipoasse on h12_codigo = h16_assent ";
			$sSomaTempoAnterior .= "         where h12_reltot > 0 ";
			$sSomaTempoAnterior .= "           and h16_regist = rh01_regist ";
			$sSomaTempoAnterior .= "        having sum(h16_dtterm - h16_dtconc) <> 0 ) as tempocontrib ";

			$sTempoServicoAtual  = "date_part('year',age(cast('{$sDBdatausu}' as date), rh01_progres)) as temposervico  ";
			$sTempoCargoAtual    = " ( (extract(year from '{$sDBdatausu}'::date) - extract(year from rh01_admiss)) * 12 + ";
			$sTempoCargoAtual   .= "   (extract(month from '{$sDBdatausu}'::date) - extract(month from rh01_admiss)) ) as tempocargoatual";

			$sWhere  = "rh30_vinculo = 'I' and rh30_regime = 1";

  		break;

  	case 'P':

  		$sSomaTempoAnterior  = "(select round(sum(h16_dtterm - h16_dtconc)/30) as tempocontrib ";
			$sSomaTempoAnterior .= "          from assenta ";
			$sSomaTempoAnterior .= "               inner join tipoasse on h12_codigo = h16_assent ";
			$sSomaTempoAnterior .= "         where h12_reltot > 0 ";
			$sSomaTempoAnterior .= "           and h16_regist = rh01_regist ";
			$sSomaTempoAnterior .= "        having sum(h16_dtterm - h16_dtconc) <> 0 ) as tempocontrib ";

			$sTempoServicoAtual = "null as temposervico ";
			$sTempoCargoAtual   = "null as tempocargoatual ";
			$sInsalubre         = "false as insalubre ";

			$sWhere  = "rh30_vinculo = 'P' and rh30_regime = 1";

  		break;
  }


  $sCategoriaServidor = '1 as categoria_servidor';

  if (isset($oPost->r44_selec)) {

  	$oSelecao = new cl_selecao();
    $rsSelecao = $oSelecao->sql_record($oSelecao->sql_query_file($oPost->r44_selec, $iDBinstit, "r44_where"));

    if ($rsSelecao) {
	    $r44_where = db_utils::fieldsMemory($rsSelecao,0)->r44_where;

	  	if ($r44_where != '') {
	  	  $sCategoriaServidor = "case when $r44_where then 02 else 1 end as categoria_servidor ";
	  	}
    }
  }

  /*
   *  QUERY PRINCIPAL
   */
  $sSqlPrincipal  = " select rh01_regist as matricula, ";
  $sSqlPrincipal .= "     case when rh01_sexo = 'M' then 1 else 2 end as sexo, ";
  $sSqlPrincipal .= "     date_part('year',age(cast('{$sDBdatausu}' as date), rh01_nasc)) as idade, ";
  $sSqlPrincipal .= "     round(prov, 2) as  valorbruto, ";
  $sSqlPrincipal .= "     round(base, 2) as  previdencia, ";
  $sSqlPrincipal .= "     round(descontoprev, 2) as  descontoprevidencia, ";
  $sSqlPrincipal .= "     rh01_admiss as admissao, ";
  $sSqlPrincipal .= "     (select date_part('year',age(cast('{$sDBdatausu}' as date), rh31_dtnasc)) ";
  $sSqlPrincipal .= "        from rhdepend ";
  $sSqlPrincipal .= "       where rh31_regist = rh01_regist ";
  $sSqlPrincipal .= "        and rh31_gparen = 'C' ";
  $sSqlPrincipal .= "     ) as idadeconjuge, ";
  $sSqlPrincipal .= "     (select count(*) ";
  $sSqlPrincipal .= "        from rhdepend ";
  $sSqlPrincipal .= "       where rh31_regist = rhpessoal.rh01_regist ";
  $sSqlPrincipal .= "        and (rh31_gparen = 'C' ";
  $sSqlPrincipal .= "        or (rh31_gparen = 'F' and date_part('year',age(cast('{$sDBdatausu}' as date), rh31_dtnasc)) < 21 )) ";
  $sSqlPrincipal .= "     )as totaldependentes, ";
  $sSqlPrincipal .= "     {$sSomaTempoAnterior}, ";
  $sSqlPrincipal .= "     {$sTempoServicoAtual}, ";
  $sSqlPrincipal .= "     {$sTempoCargoAtual}, ";
  $sSqlPrincipal .= "     {$sInsalubre}, ";
  $sSqlPrincipal .= "     case when rh30_regime = 1 then 1 ";
  $sSqlPrincipal .= "          when rh30_regime = 3 then 2 ";
  $sSqlPrincipal .= "          when rh30_regime = 2 then 1 end as tiposervidor, ";
  $sSqlPrincipal .= "     {$sCategoriaServidor} ";
  $sSqlPrincipal .= " from  rhpessoal ";
  $sSqlPrincipal .= "       inner join rhpessoalmov on rh02_regist = rh01_regist ";
  $sSqlPrincipal .= "                              and rh02_anousu = {$ano} ";
  $sSqlPrincipal .= "                              and rh02_mesusu = {$mes} ";
  $sSqlPrincipal .= "                              and rh02_instit = {$iDBinstit} ";
  $sSqlPrincipal .= "       inner join rhlota       on r70_codigo  = rh02_lota ";
  $sSqlPrincipal .= "                              and r70_instit  = rh02_instit ";
  $sSqlPrincipal .= "       inner join rhregime     on rh30_codreg = rh02_codreg ";
  $sSqlPrincipal .= "                              and rh30_instit = rh02_instit ";
  $sSqlPrincipal .= "       inner join  (select r14_regist, ";
  $sSqlPrincipal .= "                           sum(case when r14_pd = 1 then r14_valor else 0 end) as prov, ";
  $sSqlPrincipal .= "                           sum(case when r14_pd = 2 then r14_valor else 0 end) as desco, ";
  $sSqlPrincipal .= "                           sum(case when r14_rubric = 'R992' then r14_valor else 0 end ) as base, ";
  $sSqlPrincipal .= "                           sum(case when r14_rubric between 'R901' and 'R912'   then r14_valor else 0 end ) as descontoprev ";
  $sSqlPrincipal .= "                      from gerfsal ";
  $sSqlPrincipal .= "                     where r14_anousu = {$ano} ";
  $sSqlPrincipal .= "                       and r14_mesusu = {$mes} ";
  $sSqlPrincipal .= "                       and r14_instit = {$iDBinstit} ";
  $sSqlPrincipal .= "                     group by r14_regist ) as sal on r14_regist = rh01_regist ";
  $sSqlPrincipal .= " where {$sWhere} ";


  $rsCalculo = db_query($sSqlPrincipal);

  if ($rsCalculo) {

	  $iTotalLinha = pg_num_rows($rsCalculo);

	  if ($tipo_arquivo == "A") {


	  	$arq  = "tmp/calc_ativos_mirador_apos.csv";
	  	$arq2 = "tmp/calc_ativos_mirador_antes.csv";

	  	$arquivo  = fopen($arq, 'w');
	  	$arquivo2 = fopen($arq2, 'w');

		  for ($i = 0; $i < $iTotalLinha; $i++) {

		  	$oLinhaCalculo = db_utils::fieldsMemory($rsCalculo, $i);

		  	/*
		  	 * Cria String para os 4 Filhos
		  	 */
		  	$oRhDepend    = new cl_rhdepend();
		  	$sCampoIdade  = "date_part('year',age(cast('{$sDBdatausu}' as date), rh31_dtnasc)) AS idade";
		  	$sWhereIdade  = "rh31_regist = {$oLinhaCalculo->matricula} AND rh31_gparen = 'F' ";
		  	$sWhereIdade .= " AND date_part('year',age(cast('{$sDBdatausu}' as date), rh31_dtnasc)) < 21";
		  	$sSqlRhDepend = $oRhDepend->sql_query_file(null, $sCampoIdade, "rh31_dtnasc ASC LIMIT 4", $sWhereIdade);
		  	$rsRhDepend   = $oRhDepend->sql_record($sSqlRhDepend);

		  	$aIdadeFilhos = array(";",";",";",";");
		  	if ($oRhDepend->numrows > 0) {

		  		$aDepend = db_utils::getColectionByRecord($rsRhDepend);
		  		foreach ($aDepend as $k=>$oDep) {
		  			$aIdadeFilhos[$k] = "{$oDep->idade};";
		  		}
		  	}
		  	$sIdadeFilhos = implode($aIdadeFilhos);

		  	$iTempoServico = $oLinhaCalculo->temposervico?$oLinhaCalculo->temposervico:"0";

  		  $sLinhaCalculo  = "";
        $sLinhaCalculo .= "{$oLinhaCalculo->matricula};";
        $sLinhaCalculo .= "{$oLinhaCalculo->idade};";
        $sLinhaCalculo .= "{$oLinhaCalculo->admissao};";
        $sLinhaCalculo .= "{$oLinhaCalculo->sexo};";
        $sLinhaCalculo .= ($oLinhaCalculo->insalubre=='t'?"1":"2").";";
        $sLinhaCalculo .= ($oLinhaCalculo->tempocontrib?$oLinhaCalculo->tempocontrib:"0").";";
        $sLinhaCalculo .= ($oLinhaCalculo->tempocargoatual).";";
        $sLinhaCalculo .= "{$iTempoServico};";
        $sLinhaCalculo .= "{$iTempoServico};";
        $sLinhaCalculo .= "{$oLinhaCalculo->previdencia};";
        $sLinhaCalculo .= "{$oLinhaCalculo->previdencia};";
        $sLinhaCalculo .= ";"; //reserva poupan�a
        $sLinhaCalculo .= "{$oLinhaCalculo->totaldependentes};";
        $sLinhaCalculo .= "{$oLinhaCalculo->idadeconjuge};";
        $sLinhaCalculo .= $sIdadeFilhos;
        $sLinhaCalculo .= "{$oLinhaCalculo->tiposervidor};";
        $sLinhaCalculo .= "{$oLinhaCalculo->categoria_servidor}";
        $sLinhaCalculo .= "\n";

        if (strtotime($oLinhaCalculo->admissao) < strtotime($data_mudanca)) {
          fputs($arquivo2, $sLinhaCalculo);
		  	} else {
		  	  fputs($arquivo, $sLinhaCalculo);
		    }

		  	db_atutermometro($i, $iTotalLinha, 'termometro');
		  }

		  fclose($arquivo);
		  fclose($arquivo2);

	  } else {

	  	/*
	  	 * INATIVOS/PENSIONISTAS
	  	 */

	  	$arq  = "tmp/calc_".($tipo_arquivo=="I"?"inativos":"Pensionistas")."_mirador_apos.csv";
	  	$arquivo  = fopen($arq, 'w');

	  	for ($i = 0; $i < $iTotalLinha; $i++) {

        $oLinhaCalculo = db_utils::fieldsMemory($rsCalculo, $i);

        /*
         * Cria Query para os 4 Filhos
         */
        $oRhDepend    = new cl_rhdepend();
        $sCampoIdade  = "date_part('year',age(cast('{$sDBdatausu}' as date), rh31_dtnasc)) AS idade";
        $sWhereIdade  = "rh31_regist = {$oLinhaCalculo->matricula} AND rh31_gparen = 'F' ";
        $sWhereIdade .= " AND date_part('year',age(cast('{$sDBdatausu}' as date), rh31_dtnasc)) < 21";
        $sSqlRhDepend = $oRhDepend->sql_query_file(null, $sCampoIdade, "rh31_dtnasc ASC LIMIT 4", $sWhereIdade);
        $rsRhDepend   = $oRhDepend->sql_record($sSqlRhDepend);

        $aIdadeFilhos = array(";",";",";",";");
        if ($oRhDepend->numrows > 0) {

          $aDepend = db_utils::getColectionByRecord($rsRhDepend);
          foreach ($aDepend as $k=>$oDep) {
            $aIdadeFilhos[$k] = "{$oDep->idade};";
          }
        }
        $sIdadeFilhos = implode($aIdadeFilhos);

        $sLinhaCalculo  = "";
        $sLinhaCalculo .= "{$oLinhaCalculo->matricula};";
        $sLinhaCalculo .= "{$oLinhaCalculo->idade};";
        $sLinhaCalculo .= "{$oLinhaCalculo->admissao};"; // adicionada coluna admissao para que seja aceito no upload do arquivo
        $sLinhaCalculo .= "{$oLinhaCalculo->sexo};";
        $sLinhaCalculo .= "{$oLinhaCalculo->valorbruto};";
        $sLinhaCalculo .= ($tipo_arquivo=="I"?"1":"3").";";
        $sLinhaCalculo .= ";";
        $sLinhaCalculo .= "{$oLinhaCalculo->totaldependentes};";
        $sLinhaCalculo .= "{$oLinhaCalculo->idadeconjuge};";
        $sLinhaCalculo .= $sIdadeFilhos;
        $sLinhaCalculo .= "\n";

        fputs($arquivo, $sLinhaCalculo);


        db_atutermometro($i, $iTotalLinha, 'termometro');
      }

      fclose($arquivo);
	  }

	} else {

  	db_msgbox("Erro ao processar os dados.\\nVerifique o cadastro da selec�o de professores");
  }
}

db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<form name='form1' id='form1'></form>
<?
if ($rsCalculo) {

  echo "<script>";

  switch ($tipo_arquivo) {
  	case "A":
  		echo "  listagem  = '$arq2#Download arquivo TXT (Ativos Anteriores Data)|';";
	    echo "  listagem += '$arq#Download arquivo TXT (Ativos Ap�s a Data)';";
      break;
  	case "I":
  		echo "  listagem  = '$arq#Download arquivo TXT (Inativos)';";
  		break;
  	case "P":
  		echo "  listagem  = '$arq#Download arquivo TXT (Pensionistas)';";
      break;
  }

  echo "  js_montarlista(listagem,'form1');";
  echo "</script>";
}
  ?>

<script>
function js_manda(){
    location.href='pes4_geracalcaturialmirador001.php';
}
setTimeout(js_manda,300);
</script>
</body>
</html>