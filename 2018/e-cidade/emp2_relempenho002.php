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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("classes/db_empempenho_classe.php"));
require_once(modification("classes/db_cgm_classe.php"));
require_once(modification("classes/db_orctiporec_classe.php"));
require_once(modification("classes/db_orcdotacao_classe.php"));
require_once(modification("classes/db_orcorgao_classe.php"));
require_once(modification("classes/db_empemphist_classe.php"));
require_once(modification("classes/db_emphist_classe.php"));
require_once(modification("classes/db_orcelemento_classe.php"));
require_once(modification("classes/db_conlancamemp_classe.php"));
require_once(modification("classes/db_conlancamdoc_classe.php"));
require_once(modification("classes/db_empempitem_classe.php"));
require_once(modification("classes/db_empresto_classe.php"));
require_once(modification("classes/db_empelemento_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clselorcdotacao = new cl_selorcdotacao();
$clorcelemento   = new cl_orcelemento;
$clemphist       = new cl_emphist;
$clconlancamemp  = new cl_conlancamemp;
$clconlancamdoc  = new cl_conlancamdoc;
$clempempenho    = new cl_empempenho;
$clcgm           = new cl_cgm;
$clorctiporec    = new cl_orctiporec;
$clorcdotacao    = new cl_orcdotacao;
$clorcorgao      = new cl_orcorgao;
$clempemphist    = new cl_empemphist;
$clempempitem    = new cl_empempitem;
$clempresto      = new cl_empresto;
$clempelemento   = new cl_empelemento;

$clorcelemento->rotulo->label();
$clemphist->rotulo->label();
$clempemphist->rotulo->label();
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clorctiporec->rotulo->label();
$clorcdotacao->rotulo->label();
$clorcorgao->rotulo->label();
$clempelemento->rotulo->label();
$clrotulo = new rotulocampo;

$tipo = "a"; // sempre analitico

$clselorcdotacao->setDados($filtra_despesa); // passa os parametros vindos da func_selorcdotacao_abas.php

$instits = str_replace('-',', ',$db_selinstit);

$sele_work = $clselorcdotacao->getDados(false);

$sele_desdobramentos="";
$desdobramentos = $clselorcdotacao->getDesdobramento(); // coloca os codele dos desdobramntos no formato (x,y,z)
if ($desdobramentos != "") {
	$sele_desdobramentos = " and empelemento.e64_codele in ".$desdobramentos; // adiciona desdobramentos
}

//////////////////////////////////////////////////////////////////
$resultinst = db_query("select munic from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
db_fieldsmemory($resultinst,0);

$head1 = "MUNICÍPIO DE ".strtoupper($munic);

//////////////////////////////////////////////////////////////////
// echo $sele_work;exit;

$clrotulo->label("pc50_descr");
///////////////////////////////////////////////////////////////////////
$campos  = "distinct e60_numemp, to_number(e60_codemp::text,'9999999999') as e60_codemp, e60_resumo, e60_emiss";
$campos .= ", e60_numcgm, z01_nome, z01_cgccpf, z01_munic, e60_vlremp, e60_vlranu, e60_vlrliq, e63_codhist, e40_descr";
$campos .= ", e60_vlrpag, e60_anousu, e60_coddot, o58_coddot, o58_orgao, o40_orgao, o40_descr, o58_unidade, o41_descr";
$campos .= ", o15_codigo, o15_descr, fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural, e60_codcom";
$campos .= ", pc50_descr,e60_concarpeculiar";

//---------
// monta sql
$txt_where = "e60_instit in ( $instits )";
if ($listaitem != "") {
	if (isset ($veritem) and $veritem == "com") {
		$txt_where = $txt_where." and e62_item in  ($listaitem)";
	} else {
		$txt_where = $txt_where." and e62_item not in  ($listaitem)";
	}
}
$sValoresEmpenho = "";
if ($nValorEmpenhoInicial != "") {

	$nValorEmpenhoInicial  = str_replace(',', '.', $nValorEmpenhoInicial);
	$sValoresEmpenho      .= " and e60_vlremp >= {$nValorEmpenhoInicial} ";
}
if ($nValorEmpenhoFinal != "") {
	$nValorEmpenhoFinal  = str_replace(',', '.', $nValorEmpenhoFinal);
	$sValoresEmpenho    .= " and e60_vlremp <= {$nValorEmpenhoFinal} ";
}


if ($listasub != "") {
	$resultado = db_query("select pc01_codmater from pcmater where pc01_codsubgrupo in ($listasub)");

	if (pg_numrows($resultado) > 0){
		$virgula = "";
		$listar  = "";
		for ($i = 0; $i < pg_numrows($resultado); $i++){
			db_fieldsmemory($resultado, $i);
			$listar  .= $virgula.$pc01_codmater;
			$virgula  = ", ";
		}

		if (isset ($veritem) and $veritem == "com") {
			$txt_where = $txt_where." and e62_item in  ($listar)";
		} else {
			$txt_where = $txt_where." and e62_item not in  ($listar)";
		}
	} else {
		$listasub = "";
	}
}
if ($listacredor != "") {
	if (isset ($ver) and $ver == "com") {
		$txt_where = $txt_where." and e60_numcgm in  ($listacredor)";
	} else {
		$txt_where = $txt_where." and e60_numcgm not in  ($listacredor)";
	}
}
if ($listahist != "") {
	if (isset ($verhist) and $verhist == "com") {
		$txt_where = $txt_where." and e63_codhist in  ($listahist)";
	} else {
		$txt_where = $txt_where." and e63_codhist not in  ($listahist)";
	}
}
if ($listaevento != ""){
	if (isset($verhist) && $verhist == "com"){

		$sBuscaTipoEvento = " exists (select 1 from emppresta where emppresta.e45_numemp = empempenho.e60_numemp and emppresta.e45_tipo in ($listaevento))";
//		$txt_where = $txt_where." and e60_codtipo in ($listaevento)";
		$txt_where = $txt_where." and {$sBuscaTipoEvento} ";
	} else {
		$txt_where = $txt_where." and e60_codtipo not in ($listaevento)";
	}
}
if ($listacom != "" ) {
	if (isset ($vercom) and $vercom == "com") {
		$txt_where = $txt_where." and e60_codcom in  ($listacom)";
	} else {
		$txt_where = $txt_where." and e60_codcom not in  ($listacom)";
	}
}

if (($datacredor != "--") && ($datacredor1 != "--")) {
	$txt_where = $txt_where." and e60_emiss  between '$datacredor' and '$datacredor1'  ";
	//        $datacredor=db_formatar($datacredor,"d");
	//        $datacredor1=db_formatar($datacredor1,"d");
	$info = "De ".db_formatar($datacredor, "d")." até ".db_formatar($datacredor1, "d").".";
} else
	if ($datacredor != "--") {
		$txt_where = $txt_where." and e60_emiss >= '$datacredor'  ";
		//          $datacredor=db_formatar($datacredor,"d");
		$info = "Apartir de ".db_formatar($datacredor, "d").".";
	} else
		if ($datacredor1 != "--") {
			$txt_where = $txt_where."    e60_emiss <= '$datacredor1'   ";
			//         $datacredor1=db_formatar($datacredor1,"d");
			$info = "Até ".db_formatar($datacredor1, "d").".";
		}

if ($tipoemp == "todos") {
	$txt_where = $txt_where." ";
}
elseif ($tipoemp == "somemp") {
	$txt_where = $txt_where." and (round(yyy.e60_vlremp,2) - round(yyy.e60_vlranu,2) > 0) and round(yyy.e60_vlrliq,2) = 0 ";
}
elseif ($tipoemp == "saldo") {
	// com saldo a pagar geral
	$txt_where = $txt_where." and (round(round(yyy.e60_vlremp,2) - round(yyy.e60_vlranu,2),2) - round(yyy.e60_vlrpag,2) > 0 ) ";
}
elseif ($tipoemp == "saldoliq") {
	$txt_where = $txt_where." and (round(yyy.e60_vlrliq,2) - round(yyy.e60_vlrpag,2) > 0) and round(yyy.e60_vlrliq,2) > 0";
}
elseif ($tipoemp == "saldonaoliq") {
	//	$txt_where = $txt_where." and (round(yyy.e60_vlrliq,2) - round(yyy.e60_vlrpag,2) > 0) and round(yyy.e60_vlrliq,2) = 0";
	$txt_where = $txt_where." and (round(yyy.e60_vlremp,2) - round(yyy.e60_vlranu,2) - round(yyy.e60_vlrliq,2) > 0)";
}
elseif ($tipoemp == "anul") {
	$txt_where = $txt_where." and round(yyy.e60_vlranu,2) > 0 ";
}
elseif ($tipoemp == "anultot") {
	$txt_where = $txt_where." and round(yyy.e60_vlremp,2) = round(yyy.e60_vlranu,2)";
}
elseif ($tipoemp == "anulparc") {
	$txt_where = $txt_where." and round(yyy.e60_vlranu,2) > 0 and round(yyy.e60_vlremp,2) <> round(yyy.e60_vlranu,2)";
}
elseif ($tipoemp == "anulsem") {
	$txt_where = $txt_where." and round(yyy.e60_vlranu,2) = 0";
}
elseif ($tipoemp == "liq") {
	$txt_where = $txt_where." and round(yyy.e60_vlrliq,2) > 0";
}
elseif ($tipoemp == "liqtot") {
	$txt_where = $txt_where." and ((round(yyy.e60_vlremp,2) - round(yyy.e60_vlranu,2)) = round(yyy.e60_vlrliq,2))";
}
elseif ($tipoemp == "liqparc") {
	$txt_where = $txt_where." and round(yyy.e60_vlrliq,2) > 0 and ((round(yyy.e60_vlremp,2) - round(yyy.e60_vlranu,2)) <> round(yyy.e60_vlrliq,2))";
}
elseif ($tipoemp == "liqsem") {
	$txt_where = $txt_where." and round(yyy.e60_vlrliq,2) = 0";
}
elseif ($tipoemp == "pag") {
	$txt_where = $txt_where." and round(yyy.e60_vlrpag,2) > 0 ";
}
elseif ($tipoemp == "pagtot") {
	$txt_where = $txt_where." and round(yyy.e60_vlrpag,2) > 0 and (round(yyy.e60_vlrliq,2) = round(yyy.e60_vlrpag,2))";
}
elseif ($tipoemp == "pagparc") {
	$txt_where = $txt_where." and round(yyy.e60_vlrpag,2) > 0 and (round(yyy.e60_vlrliq,2) <> round(yyy.e60_vlrpag,2))";
}
elseif ($tipoemp == "pagsem") {
	$txt_where = $txt_where." and round(yyy.e60_vlrpag,2) = 0";
}
elseif ($tipoemp == "pagsemcomliq") {
	$txt_where = $txt_where." and round(yyy.e60_vlrpag,2) = 0 and round(yyy.e60_vlrliq,2) > 0";
}

if ($emptipo != 0) {
	$txt_where = $txt_where . " and e60_codtipo = {$emptipo} ";
}


if (isset($listaconcarpeculiar) && $listaconcarpeculiar != ""){

	$listaconcarpeculiar = "'".str_replace(",", "','",$listaconcarpeculiar)."'" ;

	if (isset($verconcarpeculiar) && $verconcarpeculiar == "com"){
		$txt_where = $txt_where." and e60_concarpeculiar in ($listaconcarpeculiar)";
	} else {
		$txt_where = $txt_where." and e60_concarpeculiar not in ($listaconcarpeculiar)";
	}
}

$txt_where .= " and $sele_work {$sValoresEmpenho}";

//echo $txt_where; die();

/////////////////////////////////////////////
$ordem = "z01_nome, e60_emiss";

//echo $ordem; exit;
//if ($tipo=="a"){

if ($agrupar == "a") { // fornecedor
	$ordem = "z01_nome, e60_emiss";
} elseif ($agrupar == "orgao") { // orgao
	$ordem = "o58_orgao, e60_emiss";
} elseif ($agrupar == "r") { // recurso
	$ordem = "o15_codigo, e60_emiss";
} elseif ($agrupar == "d") { // desdobramento
	//	$ordem = " e64_codele";
} else {
	// $ordem = "";
}
if ($agrupar !="orgao" && $agrupar!="r" && $agrupar!="d"){
	if ($chk_ordem != "0"){
		if ($chk_ordem== "E") {
			$ordem = "e60_vlremp desc ";
		}elseif ($chk_ordem== "L"){
			$ordem = "e60_vlrliq desc ";
		}elseif ($chk_ordem== "P"){
			$ordem = "e60_vlrpag desc ";
		}
	}
}

if ($agrupar == "oo"){
	$ordem = "e60_emiss, z01_nome, e60_anousu, e60_codemp ";
	$ordem = "e60_numemp, to_number(e60_codemp::text,'9999999999') ";
}
if ($processar == "a") {

	$txt_where = str_replace("yyy.", "", $txt_where);

	//die($clempempenho->sql_query_hist(null,$campos,$ordem,$txt_where));
	$sqlrelemp = $clempempenho->sql_query_relatorio(null, $campos, $ordem, $txt_where);

	if ($agrupar == "d" or 1==1) {
		$sqlrelemp = "select 	  x.e60_resumo,
					  x.e60_numemp,
					  x.e60_codemp,
					  x.e60_emiss,
					  x.e60_numcgm,
					  x.z01_nome,
					  x.z01_cgccpf,
					  x.z01_munic,
 					  x.e63_codhist,
					  x.e40_descr,
 				  	  x.e60_anousu,
					  x.e60_coddot,
					  x.o58_coddot,
					  x.o58_orgao,
					  x.o40_orgao,
					  x.o40_descr,
					  x.o58_unidade,
					  x.o41_descr,
					  x.o15_codigo,
					  x.o15_descr,
					  x.dl_estrutural,
					  x.e60_codcom,
					  x.pc50_descr,
					  empelemento.e64_codele,
					  orcelemento.o56_descr,
                                          x.e60_vlremp,
					  x.e60_vlranu,
					  x.e60_vlrliq,
                                          x.e60_vlrpag,
					  empelemento.e64_vlremp,
					  empelemento.e64_vlrliq,
					  empelemento.e64_vlranu,
					  empelemento.e64_vlrpag,
            x.e60_concarpeculiar
				  from ($sqlrelemp) as x
			               inner join empelemento on x.e60_numemp = e64_numemp  ".$sele_desdobramentos."
				       inner join orcelemento on o56_codele = e64_codele and o56_anousu = x.e60_anousu
				       group by
                                              x.e60_resumo,
					      x.e60_numemp,
					      x.e60_codemp,
					      x.e60_emiss,
					      x.e60_numcgm,
					      x.z01_nome,
					      x.z01_cgccpf,
					      x.z01_munic,
 					      x.e63_codhist,
					      x.e40_descr,
 				  	      x.e60_anousu,
					      x.e60_coddot,
					      x.o58_coddot,
					      x.o58_orgao,
					      x.o40_orgao,
					      x.o40_descr,
					      x.o58_unidade,
					      x.o41_descr,
					      x.o15_codigo,
					      x.o15_descr,
					      x.dl_estrutural,
					      x.e60_codcom,
					      x.pc50_descr,
					      empelemento.e64_codele,
					      orcelemento.o56_descr,
                                              x.e60_vlremp,
					      x.e60_vlranu,
					      x.e60_vlrliq,
                                              x.e60_vlrpag,
					      empelemento.e64_vlremp,
					      empelemento.e64_vlrliq,
					      empelemento.e64_vlranu,
					      empelemento.e64_vlrpag,
                x.e60_concarpeculiar";
	}
	$sqlrelemp = "select * from ($sqlrelemp) as x " . ($agrupar == "d"?" order by e64_codele, e60_emiss ":" order by $ordem ");

	//echo $sqlrelemp;exit;

	$res = $clempempenho->sql_record($sqlrelemp);

	if ($clempempenho->numrows > 0) {
		$rows = $clempempenho->numrows;
	} else {
		db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta (A21) !');
	}

} else {

	$sqlperiodo = "
			      select 	empempenho.e60_numemp,
					e60_resumo,
					e60_codemp,
					e60_emiss,
					e60_numcgm,
					z01_nome,
					z01_cgccpf,
					z01_munic,
					yyy.e60_vlremp,
					yyy.e60_vlranu,
					yyy.e60_vlrliq,
					e63_codhist,
					e40_descr,
					yyy.e60_vlrpag,
					e60_anousu,
					e60_coddot,
					o58_coddot,
					o58_orgao,
					o40_orgao,
					o40_descr,
					o58_unidade,
					o41_descr,
					o15_codigo,
					o15_descr,
					fc_estruturaldotacao(e60_anousu,e60_coddot) as dl_estrutural,
					e60_codcom,
					pc50_descr,
          e60_concarpeculiar
			   from (
			  select e60_numemp,
					sum(case when c53_tipo = 10 then c70_valor else 0 end) as e60_vlremp,
					sum(case when c53_tipo = 11 then c70_valor else 0 end) as e60_vlranu,
					sum(case when c53_tipo = 20 then c70_valor else 0 end) - sum(case when c53_tipo = 21 then c70_valor else 0 end) as e60_vlrliq,
					sum(case when c53_tipo = 30 then c70_valor else 0 end) - sum(case when c53_tipo = 31 then c70_valor else 0 end) as e60_vlrpag
				from (

				select  e60_numemp,
						c53_tipo,
						sum(c70_valor) as c70_valor
				from (
				      select e60_numemp,
						    e60_anousu,
						    e60_coddot
				      from empempenho
				      where 	e60_instit in ($instits) and
						e60_emiss between '$datacredor' and '$datacredor1'
				      ) as xxx
					  inner join orcdotacao 		on orcdotacao.o58_anousu 	= xxx.e60_anousu and orcdotacao.o58_coddot = xxx.e60_coddot
					  inner join orcelemento 		on  orcelemento.o56_codele = orcdotacao.o58_codele
									       and  orcelemento.o56_anousu = orcdotacao.o58_anousu
					      inner join conlancamemp 	on c75_numemp = xxx.e60_numemp
					      inner join conlancam	on c70_codlan = c75_codlan and c70_data <= '$dataesp22'
					      inner join conlancamdoc 	on c71_codlan = c70_codlan
					      inner join conhistdoc 	on c53_coddoc = c71_coddoc and c53_tipo in (10,11,20,21,30,31)
					      inner join conlancamdot   on c73_codlan = c75_codlan
					 group by e60_numemp, c53_tipo
				) as xxx
			group by e60_numemp) as yyy
					inner join empempenho		on empempenho.e60_numemp	= yyy.e60_numemp
					inner join cgm 			on cgm.z01_numcgm 		= empempenho.e60_numcgm
					inner join db_config 		on db_config.codigo 		= empempenho.e60_instit
					inner join orcdotacao 		on orcdotacao.o58_anousu 	= empempenho.e60_anousu and orcdotacao.o58_coddot = empempenho.e60_coddot
					inner join emptipo 		on emptipo.e41_codtipo 		= empempenho.e60_codtipo
					inner join db_config as a 	on a.codigo 			= orcdotacao.o58_instit
					inner join orctiporec 		on orctiporec.o15_codigo 	= orcdotacao.o58_codigo
					inner join orcfuncao 		on orcfuncao.o52_funcao 	= orcdotacao.o58_funcao
					inner join orcsubfuncao 	on orcsubfuncao.o53_subfuncao 	= orcdotacao.o58_subfuncao
					inner join orcprograma 		on orcprograma.o54_anousu 	= orcdotacao.o58_anousu
													   and orcprograma.o54_programa = orcdotacao.o58_programa
					inner join orcelemento 		on orcelemento.o56_codele = orcdotacao.o58_codele
								       and orcelemento.o56_anousu = orcdotacao.o58_anousu
					inner join orcprojativ 		on orcprojativ.o55_anousu 	= orcdotacao.o58_anousu and orcprojativ.o55_projativ = orcdotacao.o58_projativ
					inner join orcorgao 		on orcorgao.o40_anousu 		= orcdotacao.o58_anousu and orcorgao.o40_orgao = orcdotacao.o58_orgao
					inner join orcunidade 		on orcunidade.o41_anousu 	= orcdotacao.o58_anousu
								 and orcunidade.o41_orgao = orcdotacao.o58_orgao and orcunidade.o41_unidade = orcdotacao.o58_unidade
					left join  empemphist 		on empemphist.e63_numemp = empempenho.e60_numemp
					left join  emphist 		on emphist.e40_codhist = empemphist.e63_codhist
					inner join pctipocompra 	on pctipocompra.pc50_codcom = empempenho.e60_codcom

					";
	if ($listaitem != "" or $listasub != "") {
		if ($listaitem != ""){
			$sqlperiodo .="  inner join empempitem on e62_numemp=empempenho.e60_numemp and e62_item in ($listaitem) ";
		}

		if ($listasub != "") {
			$sqlperiodo .="  left join empempitem on e62_numemp=empempenho.e60_numemp and e62_item in ($listar) ";
		}
	}
	$sqlperiodo .=" where $txt_where ";

	if ($listaitem != ""){
		$sqlperiodo .= " group by empempenho.e60_numemp,  empempenho.e60_resumo, empempenho.e60_codemp,
		                            empempenho.e60_emiss,   empempenho.e60_numcgm,
			                    cgm.z01_nome,           cgm.z01_cgccpf,        cgm.z01_munic,
				            yyy.e60_vlremp,         yyy.e60_vlranu,        yyy.e60_vlrliq,
					    empemphist.e63_codhist, emphist.e40_descr,     yyy.e60_vlrpag,
					    empempenho.e60_anousu,  empempenho.e60_coddot, orcdotacao.o58_coddot,
					    orcdotacao.o58_orgao,   orcorgao.o40_orgao,    orcorgao.o40_descr,
					    orcdotacao.o58_unidade, orcunidade.o41_descr,  orctiporec.o15_codigo,
					    orctiporec.o15_descr,   empempenho.e60_codcom, pctipocompra.pc50_descr,empempenho.e60_concarpeculiar ";

	}
	$sqlperiodo .=" order by $ordem  ";

	if ($agrupar == "d" || $sele_desdobramentos != "") {
		$sqlperiodo =  "
			      select 	e60_numemp,
					e60_resumo,
					e60_codemp,
					e60_emiss,
					e60_numcgm,
					z01_nome,
					z01_cgccpf,
					z01_munic,
					e60_vlremp,
					e60_vlranu,
					e60_vlrliq,
					e63_codhist,
					e40_descr,
					e60_vlrpag,
					e60_anousu,
					e60_coddot,
					o58_coddot,
					o58_orgao,
					o40_orgao,
					o40_descr,
					o58_unidade,
					o41_descr,
					o15_codigo,
					o15_descr,
					dl_estrutural,
					e60_codcom,
					pc50_descr,
					empelemento.e64_codele,
					orcelemento.o56_descr,
          e60_concarpeculiar
		      from ($sqlperiodo) as x
			    /* inner join empelemento on x.e60_numemp = e64_numemp */

	 	            inner join empelemento on x.e60_numemp = e64_numemp  ".$sele_desdobramentos."

			    inner join orcelemento on o56_codele = e64_codele and o56_anousu = x.e60_anousu
		      group by  e60_numemp,
					e60_resumo,
					e60_codemp,
					e60_emiss,
					e60_numcgm,
					z01_nome,
					z01_cgccpf,
					z01_munic,
					e60_vlremp,
					e60_vlranu,
					e60_vlrliq,
					e63_codhist,
					e40_descr,
					e60_vlrpag,
					e60_anousu,
					e60_coddot,
					o58_coddot,
					o58_orgao,
					o40_orgao,
					o40_descr,
					o58_unidade,
					o41_descr,
					o15_codigo,
					o15_descr,
					dl_estrutural,
					e60_codcom,
					pc50_descr,
					empelemento.e64_codele,
					orcelemento.o56_descr,
          e60_concarpeculiar";
		if ($agrupar == "d" ) {
			$sqlperiodo .= "
                       order by  empelemento.e64_codele
			    ";
		}else{
			$sqlperiodo .= "
                       order by  $ordem, empelemento.e64_codele
			    ";

		}

	}

	$res = $clempempenho->sql_record($sqlperiodo);
	//db_criatabela($res);exit;
	if ($clempempenho->numrows > 0) {
		$rows = $clempempenho->numrows;
	} else {
		db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dados para gerar a consulta!');
	}
}

//////////////////////////////////////////////////////////////////////

$head3 = "Relatório de Empenhos";

if (isset ($tipoemp) && $tipoemp != "") {
	if ($tipoemp == "todos") {
		$head4 = "Todos os empenhos";
	}
	elseif ($tipoemp == "saldo") {
		$head4 = "Com saldo a pagar geral";
	}
	elseif ($tipoemp == "saldoliq") {
		$head4 = "Com saldo a pagar liquidados";
	}
	elseif ($tipoemp == "saldonaoliq") {
		$head4 = "Com saldo a pagar nao liquidados";
	}
	elseif ($tipoemp == "anul") {
		$head4 = "Com anulação";
	}
	elseif ($tipoemp == "anultot") {
		$head4 = "Apenas os totalmente anulados";
	}
	elseif ($tipoemp == "anulparc") {
		$head4 = "Apenas os anulados parcialmente";
	}
	elseif ($tipoemp == "anulsem") {
		$head4 = "Apenas os sem anulação";
	}
	elseif ($tipoemp == "liq") {
		$head4 = "Com liquidação";
	}
	elseif ($tipoemp == "liqtot") {
		$head4 = "Apenas os liquidados totalmente";
	}
	elseif ($tipoemp == "liqparc") {
		$head4 = "Apenas os liquidados parcialmente";
	}
	elseif ($tipoemp == "liqsem") {
		$head4 = "Apenas os sem liquidação";
	}
	elseif ($tipoemp == "pag") {
		$head4 = "Com pagamentos";
	}
	elseif ($tipoemp == "pagtot") {
		$head4 = "Apenas os pagos totalmente";
	}
	elseif ($tipoemp == "pagparc") {
		$head4 = "Apenas os pagos parcialmente";
	}
	elseif ($tipoemp == "pagsem") {
		$head4 = "Apenas os sem pagamento";
	}
	elseif ($tipoemp == "pagsemsemliq") {
		$head4 = "Apenas os sem pagamento e sem liquidação";
	}
	elseif ($tipoemp == "pagsemcomliq") {
		$head4 = "Apenas os sem pagamento e com liquidação";
	}
}

$head5 = "$info";

if ($processar == "a") {
	$head6 = "Posição atual";
} else {
	$head6 = "Periodo especificado: ".db_formatar($dataesp11, "d")." a ".db_formatar($dataesp22, "d");
}

if ($chk_ordem == "C"){
	$head7 = "Ordenado crescente por valor ";
	if ($chk_valor == "E") {
		$head7 .= "empenhado";
	} else if ($chk_valor == "L"){
		$head7 .= "liquidado";
	} else if ($chk_valor == "P"){
		$head7 .= "pago";
	}
}

if ($chk_ordem == "D"){
	$head7 = "Ordenado decrescente por valor ";
	if ($chk_valor == "E") {
		$head7 .= "empenhado";
	} else if ($chk_valor == "L"){
		$head7 .= "liquidado";
	} else if ($chk_valor == "P"){
		$head7 .= "pago";
	}
}

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage('L'); // adiciona uma pagina
$pdf->SetTextColor(0, 0, 0);
$pdf->SetFillColor(235);
$pdf->setleftmargin(3);


if ($agrupar != "d") {
	$e64_codele = "99999";
}

$tam = '04';
$imprime_header = true;
$contador = 0;
$repete_r = "";
$repete_d = "";
$repete = "";
$t_emp1 = 0;
$t_liq1 = 0;
$t_anu1 = 0;
$t_pag1 = 0;
$t_total1 = 0;
$t_emp2 = 0;
$t_liq2 = 0;
$t_anu2 = 0;
$t_pag2 = 0;
$t_total2 = 0;
$t_emp3 = 0;
$t_liq3 = 0;
$t_anu3 = 0;
$t_pag3 = 0;
$t_total3 = 0;
$t_emp = 0;
$t_liq = 0;
$t_anu = 0;
$t_pag = 0;
$t_total = 0;
$g_emp = 0;
$g_liq = 0;
$g_anu = 0;
$g_pag = 0;
$g_total = 0;
$tg_emp = 0;
$tg_liq = 0;
$tg_anu = 0;
$tg_pag = 0;
$tg_total = 0;
$p = 0;
$t_emp5 = 0;
$t_liq5 = 0;
$t_anu5 = 0;
$t_pag5 = 0;
$t_total5 = 0;
$t_emp6 = 0;
$t_liq6 = 0;
$t_anu6 = 0;
$t_pag6 = 0;
$t_total6 = 0;
$quantimp = 0;

$lanctotemp = 0;
$lanctotanuemp = 0;
$lanctotliq = 0;
$lanctotanuliq = 0;
$lanctotpag = 0;
$lanctotanupag = 0;
$iBorda        = 0;
/*  geral analitico */
if ($tipo == "a" or 1 == 1) {
	$pdf->SetFont('Arial', '', 7);
	$totalforne = 0;
	for ($x = 0; $x < $rows; $x ++) {
		db_fieldsmemory($res, $x, true);
		// testa novapagina
		if ($pdf->gety() > $pdf->h - 30) {
			$pdf->addpage("L");
			$imprime_header = true;
		}

		if ($imprime_header == true) {
			$pdf->Ln();

			$pdf->SetFont('Arial', 'B', 7);

			if ($agrupar == "a") {
				if ($sememp == "n") {
					$pdf->Cell(45, $tam, strtoupper($RLo15_codigo), 1, 0, "C", 1);
					$pdf->Cell(120, $tam, strtoupper($RLo15_descr), 1, 0, "C", 1);
					$pdf->Cell(72, $tam, "MOVIMENTAÇÃO", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				} else {
					$pdf->Cell(45, $tam, strtoupper($RLo15_codigo), 1, 0, "C", 1);
					$pdf->Cell(80, $tam, strtoupper($RLo15_descr), 1, 0, "C", 1);
					$pdf->Cell(97, $tam, "MOVIMENTAÇÃO", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);

					$pdf->Cell(125, $tam, '', 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				}
			}

			if ($agrupar == "d") {
				if ($sememp == "n") {
					$pdf->Cell(45, $tam, strtoupper($RLo56_codele), 1, 0, "C", 1);
					$pdf->Cell(120, $tam, strtoupper($RLo56_descr), 1, 0, "C", 1);
					$pdf->Cell(72, $tam, "MOVIMENTAÇÃO", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				} else {
					$pdf->Cell(45, $tam, strtoupper($RLo56_codele), 1, 0, "C", 1);
					$pdf->Cell(80, $tam, strtoupper($RLo56_descr), 1, 0, "C", 1);
					$pdf->Cell(97, $tam, "MOVIMENTAÇÃO", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);

					$pdf->Cell(125, $tam, '', 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				}
			}

			if ($agrupar == "r") {
				if ($sememp == "n") {
					$pdf->Cell(45, $tam, strtoupper($RLo15_codigo), 1, 0, "C", 1);
					$pdf->Cell(120, $tam, strtoupper($RLo15_descr), 1, 0, "C", 1);
					$pdf->Cell(72, $tam, "MOVIMENTAÇÃO", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				} else {
					$pdf->Cell(45, $tam, strtoupper($RLo15_codigo), 1, 0, "C", 1);
					$pdf->Cell(80, $tam, strtoupper($RLo15_descr), 1, 0, "C", 1);
					$pdf->Cell(97, $tam, "MOVIMENTAÇÃO", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);

					$pdf->Cell(125, $tam, '', 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				}
			}

			if ($agrupar == "orgao") {
				if ($sememp == "n") {
					$pdf->Cell(45, $tam, strtoupper($RLo58_codigo), 1, 0, "C", 1);
					$pdf->Cell(120, $tam, strtoupper($RLo40_descr), 1, 0, "C", 1);
					$pdf->Cell(72, $tam, "MOVIMENTAÇÃO", 1, 0, "C", 1);
					$pdf->Cell(54 , $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				} else {
					$pdf->Cell(45, $tam, strtoupper($RLo58_codigo), 1, 0, "C", 1);
					$pdf->Cell(80, $tam, strtoupper($RLo40_descr), 1, 0, "C", 1);
					$pdf->Cell(97, $tam, "MOVIMENTAÇÃO", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);

					$pdf->Cell(125, $tam, '', 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
					$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				}
			}

			//*/
			if ($tipo == "a" and $sememp == "n") {
				if ($agrupar == "oo") {
					$pdf->Cell(165, $tam, '', 1, 0, "C", 1);
					$pdf->Cell(72, $tam, "MOVIMENTAÇÃO", 1, 0, "C", 1);
					$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				}
				$pdf->Cell(15, $tam, "N°", 1, 0, "C", 1);
				$pdf->Cell(15, $tam, "EMP.", 1, 0, "C", 1);
				$pdf->Cell(15, $tam, "EMISSÃO", 1, 0, "C", 1);

				if ($agrupar == "a") {
					if ($mostrar == "r") {
						$pdf->Cell(40, $tam, strtoupper($RLo15_codigo), 1, 0, "C", 1); // recurso
					} else
						if ($mostrar == "t") {
							$pdf->Cell(40, $tam, strtoupper('Tipo de Compra'), 1, 0, "C", 1); // tipo de compra
						}
				}

				if ($agrupar == "d") {
					if ($mostrar == "r") {
						$pdf->Cell(40, $tam, strtoupper($RLz01_nome), 1, 0, "C", 1); // recurso
					} else
						if ($mostrar == "t") {
							$pdf->Cell(40, $tam, strtoupper('Tipo de Compra'), 1, 0, "C", 1); // tipo de compra
						}
				}

				if ($agrupar == "r") {
					if ($mostrar == "r") {
						$pdf->Cell(40, $tam, strtoupper($RLz01_nome), 1, 0, "C", 1); // recurso
					} else
						if ($mostrar == "t") {
							$pdf->Cell(40, $tam, strtoupper('Tipo de Compra'), 1, 0, "C", 1); // tipo de compra
						}
				}

				if ($agrupar == "orgao") {
					if ($mostrar == "r") {
						$pdf->Cell(40, $tam, strtoupper($RLo40_descr), 1, 0, "C", 1); // recurso
					} elseif ($mostrar == "t") {
						$pdf->Cell(40, $tam, strtoupper('Tipo de Compra'), 1, 0, "C", 1); // tipo de compra
					}
				}

				if ($agrupar == "oo") {
					$pdf->Cell(40, $tam, strtoupper($RLz01_nome), 1, 0, "C", 1);
				}

				$pdf->Cell(65, $tam, strtoupper($RLe60_coddot), 1, 0, "L", 1); // cod+estrut dotatao // quebra linha
				$pdf->Cell(15, $tam, "CP", 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
				$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha

				if ($mostralan == "m") {
					$pdf->Cell(40, $tam, "", 0, 0, "C", 0);
					$pdf->Cell(20, $tam, "DATA", 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "LANÇAMENTO", 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "DOCUMENTO", 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "VALOR", 1, 1, "C", 1); // quebra linha1
				}
				if ($mostraritem == "m") {
					$pdf->Cell(40, $tam, "", 0, 0, "C", 0);
					$pdf->Cell(20, $tam, "ITEM", 1, 0, "C", 1);
					$pdf->Cell(75, $tam, "DESCRIÇÃO DO ITEM", 1, 0, "C", 1);
					$pdf->Cell(20, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(20, $tam, "VALOR TOTAL", 1, 0, "C", 1);
					$pdf->Cell(102, $tam, "COMPLEMENTO", 1, 1, "C", 1); // quebra linha1
				}
			} else if ($tipo == "a" and $sememp == "s" and $agrupar == "oo" ) {

				$pdf->Cell(150, $tam, '', 1, 0, "C", 1);
				$pdf->Cell(72, $tam, "MOVIMENTAÇÃO", 1, 0, "C", 1);
				$pdf->Cell(54, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				$pdf->Cell(150, $tam, "", 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(18, $tam, "LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(18, $tam, "NAO LIQUID", 1, 0, "C", 1);
				$pdf->Cell(18, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				if ($mostralan == "m") {

					$pdf->Cell(40, $tam, "", 0, 0, "C", 0);
					$pdf->Cell(20, $tam, "DATA", 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "LANÇAMENTO", 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "DOCUMENTO", 1, 0, "C", 1);
					$pdf->Cell(25, $tam, "VALOR", 1, 1, "C", 1); // quebra linha1
				}
				if ($mostraritem == "m") {

					$pdf->Cell(40, $tam, "", 0, 0, "C", 0);
					$pdf->Cell(20, $tam, "ITEM", 1, 0, "C", 1);
					$pdf->Cell(75, $tam, "DESCRIÇÃO DO ITEM", 1, 0, "C", 1);
					$pdf->Cell(20, $tam, "QUANTIDADE", 1, 0, "C", 1);
					$pdf->Cell(20, $tam, "VALOR TOTAL", 1, 0, "C", 1);
					$pdf->Cell(102, $tam, "COMPLEMENTO", 1, 1, "C", 1); // quebra linha1

				}

			}
			$pdf->SetFont('Arial', '', 7);
			$imprime_header = false;

		}
		/* ----------- */
		if ($repete != $e60_numcgm and $agrupar == "a") {
			if ($quantimp > 1 or ($sememp == "s" and $quantimp > 0)) {

				if (($quantimp > 1 and $sememp == "n") or ($quantimp > 0 and $sememp == "s")) {
					//$pdf->setX(125);
					$pdf->SetFont('Arial', 'B', 7);
					if ($sememp == "n") {
						$base = "B";
						$preenche = 1;
						$iTamanhoCelula = 40;
					} else {
						$base = "";
						$preenche = 0;
						$iTamanhoCelula = 25;
					}
					//$base = 1;
					$pdf->Cell(125, $tam, '', $base, 0, "R", $preenche);
					$pdf->Cell($iTamanhoCelula, $tam, ($sememp == "n" ? "TOTAL DE " : "").db_formatar($quantimp, "s")." EMPENHO". ($quantimp == 1 ? "" : "S"), $base, 0, "L", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_anu, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq - $t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_liq, 'f'), $base, 0, "R", $preenche); //quebra linha
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_pag, 'f'), $base, 1, "R", $preenche); //quebra linha
					$pdf->SetFont('Arial', '', 7);
				}
			}
			$t_emp = 0;
			$t_liq = 0;
			$t_anu = 0;
			$t_pag = 0;
			$t_total = 0;
			$repete = $e60_numcgm;
			$repete_r = $o15_codigo;
			$quantimp = 0;
			if ($sememp == "n") {
				$pdf->Ln();
			}
			$pdf->SetFont('Arial', 'B', 8);
			$totalforne ++;
			if ($agrupar == "a") {
				$pdf->Cell(45, $tam, "$e60_numcgm", $iBorda, 0, "C", 0);
				$pdf->Cell(80, $tam, "$z01_nome", $iBorda, 1, "L", 0);
				if ($sememp == "n") {
					$pdf->Cell(25, $tam, $z01_cgccpf, $iBorda, 0, "C", 0);
					$pdf->Cell(72, $tam, $z01_munic, $iBorda, 1, "L", 0);
				}
			}
			if ($agrupar == "d") {
				$pdf->Cell(45, $tam, "$o56_codele", $iBorda, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o56_descr", $iBorda, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "r") {
				$pdf->Cell(45, $tam, "$o15_codigo", $iBorda, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o15_descr", $iBorda, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}

			$pdf->SetFont('Arial', '', 7);
		}
		/* ----------- */
		if ($repete_d != $e64_codele and $agrupar == "d") {
			if ($quantimp > 1 or ($sememp == "s" and $quantimp > 0)) {
				if (($quantimp > 1 and $sememp == "n") or ($quantimp > 0 and $sememp == "s")) {
					//$pdf->setX(125);
					$pdf->SetFont('Arial', 'B', 7);
					if ($sememp == "n") {
						$base = "B";
						$preenche = 1;
						$iTamanhoCelula = 40;
					} else {
						$base = "";
						$preenche = 0;
						$iTamanhoCelula = 25;
					}
					$pdf->Cell(125, $tam, '', $base, 0, "R", $preenche);
					$pdf->Cell($iTamanhoCelula, $tam, ($sememp == "n" ? "TOTAL DE " : "").db_formatar($quantimp, "s")." EMPENHO". ($quantimp == 1 ? "" : "S"), $base, 0, "L", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_anu, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq - $t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_liq, 'f'), $base, 0, "R", $preenche); //quebra linha
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_pag, 'f'), $base, 1, "R", $preenche); //quebra linha
					$pdf->SetFont('Arial', '', 7);
				}
			}
			$t_emp = 0;
			$t_liq = 0;
			$t_anu = 0;
			$t_pag = 0;
			$t_total = 0;
			$repete = $e60_numcgm;
			$repete_d = $e64_codele;
			$quantimp = 0;
			if ($sememp == "n") {
				$pdf->Ln();
			}
			$pdf->SetFont('Arial', 'B', 8);
			$totalforne ++;
			if ($agrupar == "d") {
				$pdf->Cell(45, $tam, "$e64_codele", $iBorda, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o56_descr", $iBorda, 1, "L", 0);
			}
			$pdf->SetFont('Arial', '', 7);
		}
		if ($repete_r != $o15_codigo and $agrupar == "r") {
			if ($quantimp > 1 or ($sememp == "s" and $quantimp > 0)) {
				if (($quantimp > 1 and $sememp == "n") or ($quantimp > 0 and $sememp == "s")) {
					//$pdf->setX(125);
					$pdf->SetFont('Arial', 'B', 7);
					if ($sememp == "n") {
						$base = "B";
						$preenche = 1;
						$iTamanhoCelula = 40;
					} else {
						$base = "";
						$preenche = 0;
						$iTamanhoCelula = 25;
					}
					$pdf->Cell(125, $tam, '', $base, 0, "R", $preenche);
					$pdf->Cell($iTamanhoCelula, $tam, ($sememp == "n" ? "TOTAL DE " : "").db_formatar($quantimp, "s")." EMPENHO". ($quantimp == 1 ? "" : "S"), $base, 0, "L", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_anu, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq - $t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_liq, 'f'), $base, 0, "R", $preenche); //quebra linha
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_pag, 'f'), $base, 1, "R", $preenche); //quebra linha
					$pdf->SetFont('Arial', '', 7);
				}
			}
			$t_emp = 0;
			$t_liq = 0;
			$t_anu = 0;
			$t_pag = 0;
			$t_total = 0;
			$repete = $e60_numcgm;
			$repete_r = $o15_codigo;
			$quantimp = 0;
			if ($sememp == "n") {
				$pdf->Ln();
			}
			$pdf->SetFont('Arial', 'B', 8);
			$totalforne ++;
			if ($agrupar == "a") {
				$pdf->Cell(45, $tam, "$e60_numcgm", $iBorda, 0, "C", 0);
				$pdf->Cell(80, $tam, "$z01_nome", $iBorda, 0, "L", 0);
				if ($sememp == "n") {
					$pdf->Cell(25, $tam, $z01_cgccpf, $iBorda, 0, "C", 0);
					$pdf->Cell(72, $tam, $z01_munic, $iBorda, 1, "L", 0);
				}
			}
			if ($agrupar == "d") {

				$pdf->Cell(45, $tam, "$e64_codele", $iBorda, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o56_descr", $iBorda, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "r") {
				$pdf->Cell(45, $tam, "$o15_codigo", $iBorda, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o15_descr", $iBorda, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			$pdf->SetFont('Arial', '', 7);
		}
		/* ----------- */
		if ($repete_r != $o58_orgao and $agrupar == "orgao") {
			if ($quantimp > 1 or ($sememp == "s" and $quantimp > 0)) {
				if (($quantimp > 1 and $sememp == "n") or ($quantimp > 0 and $sememp == "s")) {
					//$pdf->setX(125);
					$pdf->SetFont('Arial', 'B', 7);
					if ($sememp == "n") {
						$base = "B";
						$preenche = 1;
						$iTamanhoCelula = 40;
					} else {
						$base = "";
						$preenche = 0;
						$iTamanhoCelula = 25;
					}
					$pdf->Cell(125, $tam, '', $base, 0, "R", $preenche);
					$pdf->Cell($iTamanhoCelula, $tam, ($sememp == "n" ? "TOTAL DE " : "").db_formatar($quantimp, "s")." EMPENHO". ($quantimp == 1 ? "" : "S"), $base, 0, "L", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_anu, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_liq - $t_pag, 'f'), $base, 0, "R", $preenche);
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_liq, 'f'), $base, 0, "R", $preenche); //quebra linha
					$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_pag, 'f'), $base, 1, "R", $preenche); //quebra linha
					$pdf->SetFont('Arial', '', 7);
				}
			}
			$t_emp = 0;
			$t_liq = 0;
			$t_anu = 0;
			$t_pag = 0;
			$t_total = 0;
			$repete = $e60_numcgm;
			$repete_r = $o58_orgao; // trocado
			$quantimp = 0;
			if ($sememp == "n") {
				$pdf->Ln();
			}
			$pdf->SetFont('Arial', 'B', 8);
			$totalforne ++;
			if ($agrupar == "a") {
				$pdf->Cell(45, $tam, "$e60_numcgm",$iBorda, 0, "C", 0);
				$pdf->Cell(80, $tam, "$z01_nome", $iBorda, 0, "L", 0);
				if ($sememp == "n") {
					$pdf->Cell(25, $tam, $z01_cgccpf, $iBorda, 0, "C", 0);
					$pdf->Cell(72, $tam, $z01_munic, $iBorda, 1, "L", 0);
				}
			}
			if ($agrupar == "d") {
				$pdf->Cell(45, $tam, "$e64_codele", $iBorda, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o56_descr",$iBorda, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "r") {
				$pdf->Cell(45, $tam, "$o15_codigo", $iBorda, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o15_descr", $iBorda, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			if ($agrupar == "orgao") {
				$pdf->Cell(45, $tam, "$o58_orgao", $iBorda, 0, "C", 0);
				$pdf->Cell(105, $tam, "$o40_descr", $iBorda, 1, "L", 0);
				if ($sememp == "n") {
					//$pdf->Cell(25, $tam, $z01_cgccpf, 0, 0, "C", 0);
					//$pdf->Cell(72, $tam, $z01_munic, 0, 1, "L", 0);
				}
			}
			$pdf->SetFont('Arial', '', 7);
		}



		/* --------  */

		if ($agrupar == "a") {
			$preenche = 1;
		}
		if ($agrupar == "d") {
			if ($mostralan == 'm') {
				$preenche = 1;
			} else {
				$preenche = 0;
			}
		} else {
			$preenche = 0;
		}
		if ($agrupar == "r") {
			if ($mostralan == 'm') {
				$preenche = 1;
			} else {
				$preenche = 0;
			}
		} else {
			$preenche = 0;
		}
		if ($agrupar == "orgao") {
			if ($mostralan == 'm') {
				$preenche = 1;
			} else {
				$preenche = 0;
			}
		} else {
			$preenche = 0;
		}

		$quantimp ++;
		// caso o exercicio do empenho for maior que o do exercicio do resto nao gerar

		if(substr($dataesp22,0,4)<db_getsession("DB_anousu")){

			$resresto = $clempresto->sql_record($clempresto->sql_query(db_getsession("DB_anousu"), $e60_numemp, "*", "", ""));
			if ($clempresto->numrows > 0) {
				db_fieldsmemory($resresto, 0, true);
				if ($processar != "a") {
					$e60_vlremp += $e91_vlremp;
					$e60_vlranu += $e91_vlranu;
					$e60_vlrliq += $e91_vlrliq;
					$e60_vlrpag += $e91_vlrpag;
				}
			}

		}

		$total = $e60_vlrliq - $e60_vlrpag;

		// o tipo sempre é == "A"
		if ($tipo == "a" and $sememp == "n") {
			$pdf->Cell(15, $tam, "$e60_numemp", $iBorda, 0, "R", $preenche);
			$pdf->Cell(15, $tam, "$e60_codemp", $iBorda, 0, "R", $preenche);
			$pdf->Cell(15, $tam, $e60_emiss, $iBorda, 0, "C", $preenche);

			if ($agrupar == "a") {
				if ($mostrar == "r") {
					$pdf->Cell(40, $tam, db_formatar($o15_codigo, 'recurso')." - ".substr($o15_descr, 0, 20), $iBorda, 0, "L", $preenche); // recurso
				} else
					if ($mostrar == "t") {
						$pdf->Cell(40, $tam, $e60_codcom." - $pc50_descr", $iBorda, 0, "L", $preenche); // tipo de compra
					}
			}
			if ($agrupar == "d") {
				if ($mostrar == "r") {
					$pdf->Cell(40, $tam, substr($z01_nome, 0, 25), $iBorda, 0, "L", $preenche); // recurso
				} elseif ($mostrar == "t") {
					$pdf->Cell(40, $tam, $e60_codcom." - $pc50_descr", $iBorda, 0, "L", $preenche); // tipo de compra
				}
			}
			if ($agrupar == "r") {
				if ($mostrar == "r") {
					$pdf->Cell(40, $tam, substr($z01_nome, 0, 25), $iBorda, 0, "L", $preenche); // recurso
				} else
					if ($mostrar == "t") {
						$pdf->Cell(40, $tam, $e60_codcom." - $pc50_descr", $iBorda, 0, "L", $preenche); // tipo de compra
					}
			}
			if ($agrupar == "orgao") {
				if ($mostrar == "r") {
					$pdf->Cell(40, $tam, substr($z01_nome, 0, 25), $iBorda, 0, "L", $preenche); // recurso
				} else
					if ($mostrar == "t") {
						$pdf->Cell(40, $tam, $e60_codcom." - $pc50_descr", $iBorda, 0, "L", $preenche); // tipo de compra
					}
			}
			if ($agrupar == "oo") {
				$pdf->Cell(40, $tam, substr($z01_nome,0,20),$iBorda, 0, "L", 0);
			}

			$pdf->Cell(65, $tam, str_pad($e60_coddot, 4, '0', STR_PAD_LEFT)." -  $dl_estrutural", $iBorda, 0, "L", $preenche); //quebra linha
			$pdf->Cell(15, $tam, $e60_concarpeculiar, 0, 0, "C", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlremp, 'f'), 'B', 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlranu, 'f'), 'B', 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlrliq, 'f'), 'B', 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlrpag, 'f'), 'B', 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlrliq - $e60_vlrpag, 'f'), 'B', 0, "R", $preenche); //quebra linha
			$pdf->Cell(18, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq, 'f'), 'B', 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag, 'f'), 'B', 1, "R", $preenche);
			if ($mostrarobs == "m") {
				$pdf->multicell(270, 4, $e60_resumo);
			}




			if (1 == 1) {

				$reslancam = $clconlancamemp->sql_record($clconlancamemp->sql_query("", "*", "c75_codlan", " c75_numemp = $e60_numemp ". ($processar == "a" ? "" : " and c75_data between '$dataesp11' and '$dataesp22'")));
				$rows_lancamemp = $clconlancamemp->numrows;
				for ($lancemp = 0; $lancemp < $rows_lancamemp; $lancemp ++) {
					db_fieldsmemory($reslancam, $lancemp, true);
					$reslancamdoc = $clconlancamdoc->sql_record($clconlancamdoc->sql_query($c70_codlan, "*"));
					db_fieldsmemory($reslancamdoc, 0, true);
					if ($mostralan == "m") {
						$preenche = ($lancemp % 2 == 0 ? 0 : 1);
						$pdf->Cell(40, $tam, "", $iBorda, 0, "R", $preenche);
						$pdf->Cell(20, $tam, $c70_data, $iBorda, 0, "C", $preenche);
						$pdf->Cell(25, $tam, $c70_codlan, $iBorda, 0, "R", $preenche);
						$pdf->Cell(25, $tam, $c53_descr, $iBorda, 0, "L", $preenche);
						$pdf->Cell(25, $tam, db_formatar($c70_valor, 'f'), $iBorda, 1, "R", $preenche);
					}

					if ($c53_tipo == 10) {
						$lanctotemp += $c70_valor;
					}
					elseif ($c53_tipo == 11) {
						$lanctotanuemp += $c70_valor;
					}
					elseif ($c53_tipo == 20) {
						$lanctotliq += $c70_valor;
					}
					elseif ($c53_tipo == 21) {
						$lanctotanuliq += $c70_valor;
					}
					elseif ($c53_tipo == 30) {
						$lanctotpag += $c70_valor;
					}
					elseif ($c53_tipo == 31) {
						$lanctotanupag += $c70_valor;
					}

				}
			}

			if ($mostraritem == "m") {
				$dbwhere = "e62_numemp = $e60_numemp ";
				if ($listaitem != "" or $listasub != "") {
					if ($listaitem != ""){
						$dbwhere .= "and e62_item in ($listaitem) ";
					}

					if ($listasub != "") {
						$dbwhere .= "and e62_item in ($listar) ";
					}
				}
				$resitem = $clempempitem->sql_record($clempempitem->sql_query(null, null, "e62_item,pc01_descrmater,e62_quant,e62_vltot,e62_descr",null,$dbwhere));
				$rows_item = $clempempitem->numrows;
				for ($item = 0; $item < $rows_item; $item ++) {
					db_fieldsmemory($resitem, $item, true);
					if (strlen($pc01_descrmater) > 55) {
					  $pc01_descrmater = trim(substr($pc01_descrmater, 0, 55)) . '...';
					}
					$preenche = ($item % 2 == 0 ? 0 : 1);
					$pdf->Cell(40, $tam, "", $iBorda, 0, "R", $preenche);
					$pdf->Cell(20, $tam, "$e62_item", $iBorda, 0, "R", $preenche);
					$pdf->Cell(75, $tam, "$pc01_descrmater", $iBorda, 0, "L", $preenche);
					$pdf->Cell(20, $tam, db_formatar($e62_quant, 'f'), $iBorda, 0, "R", $preenche);
					$pdf->Cell(20, $tam, db_formatar($e62_vltot, 'f'), $iBorda, 0, "R", $preenche);
					$pdf->Cell(80, $tam, substr($e62_descr, 0, 70), $iBorda, 1, "L", $preenche);
					$pdf->Cell(20, $tam, "", 0, 1, "R", $preenche);
				}
			}

		}






		if ($sememp == "n") {
			$pdf->Ln(1);
		}
		/* somatorio  */
		$t_emp += $e60_vlremp;
		$t_liq += $e60_vlrliq;
		$t_anu += $e60_vlranu;
		$t_pag += $e60_vlrpag;
		$t_total += $total;
		$g_emp += $e60_vlremp;
		$g_liq += $e60_vlrliq;
		$g_anu += $e60_vlranu;
		$g_pag += $e60_vlrpag;
		$g_total += $total;
		/*  */
		if ($x == ($rows -1)) {
			//$pdf->setX(125);
			/* imprime totais -*/
			$pdf->SetFont('Arial', 'B', 7);
			if ($sememp == "n") {
				$base = "B";
				$preenche = 1;
				$iTamanhoCelula = 40;
				$iCelulaFornec  = 80;

			} else {

				$base = "";
				$preenche = 0;
				$iTamanhoCelula = 25;
				$iCelulaFornec  = 65;
			}
			$pdf->Cell(125, $tam, '', $base, 0, "R", $preenche);
			$pdf->Cell($iTamanhoCelula, $tam, ($sememp == "n" ? "TOTAL DE " : "").db_formatar($quantimp, "s")." EMPENHO". ($quantimp == 1 ? "" : "S"), $base, 0, "L", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_emp, 'f'), $base, 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_anu, 'f'), $base, 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_liq, 'f'), $base, 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_pag, 'f'), $base, 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_liq - $t_pag, 'f'), $base, 0, "R", $preenche);
			$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_liq, 'f'), $base, 0, "R", $preenche); //quebra linha
			$pdf->Cell(18, $tam, db_formatar($t_emp - $t_anu - $t_pag, 'f'), $base, 1, "R", $preenche); //quebra linha
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(60, $tam, "TOTAL DE EMPENHOS: ".db_formatar($rows, "s"), "T", 0, "L", 1);
			if ($totalforne > 0) {
				$pdf->Cell($iCelulaFornec, $tam, "TOTAL DE FORNECEDORES: ".db_formatar($totalforne, "s"), "T", 0, "L", 1);
			} else {
				$pdf->Cell($iCelulaFornec, $tam, "", "T", 0, "L", 1);
			}
			$pdf->Cell(25, $tam, "TOTAL GERAL", "T", 0, "L", 1);
			$pdf->Cell(18, $tam, db_formatar($g_emp, 'f'), "T", 0, "R", 1); //totais globais
			$pdf->Cell(18, $tam, db_formatar($g_anu, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($g_liq, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($g_pag, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($g_liq - $g_pag, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($g_emp - $g_anu - $g_liq, 'f'), "T", 0, "R", 1); //quebra linha
			$pdf->Cell(18, $tam, db_formatar($g_emp - $g_anu - $g_pag, 'f'), "T", 1, "R", 1); //quebra linha

			$pdf->Ln();
			$iTam = $sememp == "n"?165:150;
			$pdf->Cell($iTam, $tam, "MOVIMENTAÇÃO CONTABIL NO PERIODO", "T", 0, "L", 1);
			$pdf->Cell(18, $tam, db_formatar($lanctotemp, 'f'), "T", 0, "R", 1); //totais globais
			$pdf->Cell(18, $tam, db_formatar($lanctotanuemp, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($lanctotliq - $lanctotanuliq, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($lanctotpag - $lanctotanupag, 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar(($lanctotliq - $lanctotanuliq) - ($lanctotpag - $lanctotanupag), 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar(($lanctotemp - ($lanctotanuemp + ($lanctotpag - $lanctotanupag))) - (($lanctotliq - $lanctotanuliq) - ($lanctotpag - $lanctotanupag)), 'f'), "T", 0, "R", 1);
			$pdf->Cell(18, $tam, db_formatar($lanctotemp - ($lanctotanuemp + ($lanctotpag - $lanctotanupag)), 'f'), "T", 1, "R", 1);
			$pdf->SetFont('Arial', '', 7);
		}
	}
}

/* geral sintetico */
if ($tipo == "s") {

	$pdf->SetFont('Arial', '', 7);
	for ($x = 0; $x < $rows; $x ++) {
		db_fieldsmemory($res, $x, true);
		// testa nova pagina
		if ($pdf->gety() > $pdf->h - 30) {
			$pdf->addpage("L");
			$imprime_header = true;
		}

		if ($imprime_header == true) {
			$pdf->Ln();
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(20, $tam, strtoupper($RLe60_numcgm), 1, 0, "C", 1);
			$pdf->Cell(100, $tam, strtoupper($RLz01_nome), 1, 0, "C", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
			$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
			$pdf->Cell(30, $tam, "TOTAL A PAGAR", 1, 1, "C", 1); //quebra linha
			$pdf->Ln();
			$pdf->SetFont('Arial', '', 7);
			$imprime_header = false;
		}
		/* ----------- */
		$pdf->Ln(1);
		$pdf->Cell(20, $tam, $e60_numcgm, $iBorda, 0, "R", $p);
		$pdf->Cell(100, $tam, $z01_nome, $iBorda, 0, "L", $p);
		$pdf->Cell(20, $tam, $e60_vlremp, $iBorda, 0, "R", $p);
		$pdf->Cell(20, $tam, $e60_vlranu, $iBorda, 0, "R", $p);
		$pdf->Cell(20, $tam, $e60_vlrliq, $iBorda, 0, "R", $p);
		$pdf->Cell(20, $tam, $e60_vlrpag, $iBorda, 0, "R", $p);
		$total = $e60_vlremp - $e60_vlranu - $e60_vlrpag;
		$pdf->Cell(30, $tam, db_formatar($total, 'f'), $iBorda, 1, "R", $p); //quebra linha
		$t_emp += $e60_vlremp;
		$t_liq += $e60_vlrliq;
		$t_anu += $e60_vlranu;
		$t_pag += $e60_vlrpag;
		$t_total += $total;
		if ($p == 0) {
			$p = 1;
		} else
			$p = 0;
		if ($x == ($rows -1)) {
			$pdf->Ln();
			$pdf->setX(110);
			/* imprime totais -*/
			$pdf->SetFont('Arial', 'B', 7);
			$pdf->Cell(25, $tam, "TOTAL ", "T", 0, "L", 1);
			$pdf->Cell(20, $tam, db_formatar($t_emp, 'f'), "T", 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($t_anu, 'f'), "T", 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($t_liq, 'f'), "T", 0, "R", 1);
			$pdf->Cell(20, $tam, db_formatar($t_pag, 'f'), "T", 0, "R", 1);
			$pdf->Cell(22, $tam, db_formatar($t_total, 'f'), "T", 1, "R", 1); //quebra linha
			$pdf->SetFont('Arial', '', 7);

		}
		/* */

	}
} /* fim geral sintetico */

if ($hist == "h") {

	if ($processar == "a") {
		$sql = "select case when e63_codhist is null then 0               else e63_codhist end as e63_codhist,
														       case when e40_descr   is null then 'SEM HISTORICO' else e40_descr   end as e40_descr,
														       e60_vlremp, e60_vlranu, e60_vlrliq, e60_vlrpag from (
														select e63_codhist,e40_descr,
												                       sum(e60_vlremp) as e60_vlremp,
												                       sum(e60_vlranu) as e60_vlranu,
												          	       sum(e60_vlrliq) as e60_vlrliq,
												       	               sum(e60_vlrpag) as e60_vlrpag
												   	        from empempenho
															inner join orcdotacao  on  orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot  = empempenho.e60_coddot
															inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele
															                       and  orcelemento.o56_anousu = orcdotacao.o58_anousu
												                       	left join empemphist on empemphist.e63_numemp = empempenho.e60_numemp
												                       	left join emphist on emphist.e40_codhist = empemphist.e63_codhist
														where 	$txt_where
												                      ";
		$sql = $sql." group by e63_codhist,e40_descr order by e63_codhist) as x";
	} else {
		$sql = "select case when x.e63_codhist is null then 0               else x.e63_codhist end as e63_codhist,
														       case when x.e40_descr   is null then 'SEM HISTORICO' else x.e40_descr   end as e40_descr,
														       sum(e60_vlremp) as e60_vlremp, sum(e60_vlranu) as e60_vlranu, sum(e60_vlrliq) as e60_vlrliq, sum(e60_vlrpag) as e60_vlrpag from
														       ($sqlperiodo) as x
												                       left join empemphist on empemphist.e63_numemp = x.e60_numemp
												                       left join emphist on emphist.e40_codhist = empemphist.e63_codhist
														       group by x.e63_codhist,x.e40_descr order by x.e63_codhist";
	}
	$result = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0) {
		$pdf->addpage("L");
		$imprime_header = true;
		$rows = $clempempenho->numrows;

		$pdf->SetFont('Arial', '', 7);
		for ($x = 0; $x < $rows; $x ++) {
			db_fieldsmemory($result, $x, true);
			// testa nova pagina
			if ($pdf->gety() > $pdf->h - 30) {
				$pdf->addpage("L");
				$imprime_header = true;
			}

			if ($imprime_header == true) {
				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->cell(200, $tam, "TOTALIZAÇÃO DOS HISTÓRICOS", 1, 0, "C", 1);
				$pdf->cell(66, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, strtoupper($RLe63_codhist), 1, 0, "C", 1);
				$pdf->Cell(100, $tam, strtoupper($RLe40_descr), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "LIQUIDADO", 1, 0, "C", 1); //quebra linha
				$pdf->Cell(22, $tam, "NAO LIQUIDADO", 1, 0, "C", 1); //quebra linha
				$pdf->Cell(22, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				$pdf->Ln();
				$pdf->SetFont('Arial', '', 7);
				$imprime_header = false;
			}
			/* ----------- */
			$pdf->Ln(1);
			$pdf->Cell(20, $tam, $e63_codhist, $iBorda, 0, "R", $p);
			$pdf->Cell(100, $tam, $e40_descr, $iBorda, 0, "L", $p);
			$pdf->Cell(20, $tam, $e60_vlremp, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlranu, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrliq, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrpag, $iBorda, 0, "R", $p);
			$total = $e60_vlrliq - $e60_vlrpag;
			$pdf->Cell(22, $tam, db_formatar($e60_vlrliq - $e60_vlrpag, 'f'), $iBorda, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq, 'f'), $iBorda, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag, 'f'), $iBorda, 1, "R", $p); //quebra linha
			$t_emp1 += $e60_vlremp;
			$t_liq1 += $e60_vlrliq;
			$t_anu1 += $e60_vlranu;
			$t_pag1 += $e60_vlrpag;
			$t_total1 += $total;
			if ($p == 0) {
				$p = 1;
			} else
				$p = 0;
			if ($x == ($rows -1)) {
				$pdf->Ln();
				$pdf->setX(110);
				/* imprime totais -*/
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, "TOTAL ", "T", 0, "L", 1);
				$pdf->Cell(20, $tam, db_formatar($t_emp1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_anu1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_liq1 - $t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_pag1, 'f'), "T", 1, "R", 1); //quebra linha
				$pdf->SetFont('Arial', '', 7);
			}
			/* */

		}
	}

}

if ($hist == "h") {

	if ($processar == "a") {
		$sql = "select case when o58_codigo is null then 0               else o58_codigo end as o58_codigo,
														       case when o15_descr  is null then 'SEM RECURSO'  else o15_descr   end as o15_descr,
														       e60_vlremp, e60_vlranu, e60_vlrliq, e60_vlrpag from (
														select o58_codigo,o15_descr,
												                       sum(e60_vlremp) as e60_vlremp,
												                       sum(e60_vlranu) as e60_vlranu,
												          	       sum(e60_vlrliq) as e60_vlrliq,
												       	               sum(e60_vlrpag) as e60_vlrpag
												   	        from empempenho
															inner join orcdotacao   on orcdotacao.o58_anousu = empempenho.e60_anousu and  orcdotacao.o58_coddot  = empempenho.e60_coddot
															inner join orcelemento  on orcelemento.o56_codele = orcdotacao.o58_codele
															                       and orcelemento.o56_anousu = orcdotacao.o58_anousu
												            left join orctiporec on orctiporec.o15_codigo = orcdotacao.o58_codigo
														where 	$txt_where
												                      ";
		$sql = $sql." group by o58_codigo,o15_descr order by o58_codigo) as x";
	} else {
		$sql = "select case when x.o58_codigo is null then 0               else x.o58_codigo end as o58_codigo,
														       case when x.o15_descr  is null then 'SEM RECURSO'   else x.o15_descr   end as o15_descr,
														       sum(e60_vlremp) as e60_vlremp, sum(e60_vlranu) as e60_vlranu, sum(e60_vlrliq) as e60_vlrliq, sum(e60_vlrpag) as e60_vlrpag from
														       ($sqlperiodo) as x
												                       left join orctiporec    on orctiporec.o15_codigo = orcdotacao.o58_codigo
														       group by x.o58_codigo,x.o15_descr order by x.o58_codigo";
	}
	//     die($sqlperiodo)
	$result = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0) {
		$pdf->addpage("L");
		$imprime_header = true;
		$rows = $clempempenho->numrows;

		$pdf->SetFont('Arial', '', 7);
		for ($x = 0; $x < $rows; $x ++) {
			db_fieldsmemory($result, $x, true);
			// testa nova pagina
			if ($pdf->gety() > $pdf->h - 30) {
				$pdf->addpage("L");
				$imprime_header = true;
			}

			if ($imprime_header == true) {
				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->cell(200, $tam, "TOTALIZAÇÃO DOS RECURSOS", 1, 0, "C", 1);
				$pdf->cell(66, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, strtoupper($RLo58_codigo), 1, 0, "C", 1);
				$pdf->Cell(100, $tam, strtoupper($RLo15_descr), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "LIQUIDADO", 1, 0, "C", 1); //quebra linha
				$pdf->Cell(22, $tam, "NAO LIQUIDADO", 1, 0, "C", 1); //quebra linha
				$pdf->Cell(22, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				$pdf->Ln();
				$pdf->SetFont('Arial', '', 7);
				$imprime_header = false;
			}
			/* ----------- */
			$pdf->Ln(1);
			$pdf->Cell(20, $tam, $o58_codigo, $iBorda, 0, "R", $p);
			$pdf->Cell(100, $tam, $o15_descr, $iBorda, 0, "L", $p);
			$pdf->Cell(20, $tam, $e60_vlremp, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlranu, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrliq,$iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrpag, $iBorda, 0, "R", $p);
			$total = $e60_vlrliq - $e60_vlrpag;
			$pdf->Cell(22, $tam, db_formatar($e60_vlrliq - $e60_vlrpag, 'f'), $iBorda, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq, 'f'), $iBorda, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag, 'f'), $iBorda, 1, "R", $p); //quebra linha
			$t_emp1 += $e60_vlremp;
			$t_liq1 += $e60_vlrliq;
			$t_anu1 += $e60_vlranu;
			$t_pag1 += $e60_vlrpag;
			$t_total1 += $total;
			if ($p == 0) {
				$p = 1;
			} else
				$p = 0;
			if ($x == ($rows -1)) {
				$pdf->Ln();
				$pdf->setX(110);
				/* imprime totais -*/
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, "TOTAL ", "T", 0, "L", 1);
				$pdf->Cell(20, $tam, db_formatar($t_emp1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_anu1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_liq1 - $t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_pag1, 'f'), "T", 1, "R", 1); //quebra linha
				$pdf->SetFont('Arial', '', 7);
			}
			/* */

		}

	} else {

	}

}

if ($hist == "h") {

	if ($processar == "a") {
		$sql = "select e60_codcom, pc50_descr,
														    sum(e60_vlremp) as e60_vlremp,
														    sum(e60_vlranu) as e60_vlranu,
														    sum(e60_vlrliq) as e60_vlrliq,
														    sum(e60_vlrpag) as e60_vlrpag
													     from empempenho
														    inner join pctipocompra on empempenho.e60_codcom = pctipocompra.pc50_codcom
														    inner join orcdotacao on orcdotacao.o58_anousu = empempenho.e60_anousu
																	 and orcdotacao.o58_coddot = empempenho.e60_coddot
														    inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele
														                           and  orcelemento.o56_anousu = orcdotacao.o58_anousu
														    where $txt_where
															  ";
		$sql = $sql."group by e60_codcom, pc50_descr order by e60_codcom";
	} else {
		$sql = "select 	x.e60_codcom, x.pc50_descr,
   sum(x.e60_vlremp) as e60_vlremp,
   sum(x.e60_vlranu) as e60_vlranu,
   sum(x.e60_vlrliq) as e60_vlrliq,
   sum(x.e60_vlrpag) as e60_vlrpag
   from
  ($sqlperiodo) as x
 	inner join pctipocompra on x.e60_codcom = pctipocompra.pc50_codcom
 	inner join orcdotacao on orcdotacao.o58_anousu = x.e60_anousu
   		   and orcdotacao.o58_coddot = x.e60_coddot
 	inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele
                               and  orcelemento.o56_anousu = orcdotacao.o58_anousu
  	group by x.e60_codcom, x.pc50_descr order by x.e60_codcom";
	}
	$result1 = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0) {
		$pdf->addpage("L");
		$imprime_header = true;
		$rows = $clempempenho->numrows;

		$pdf->SetFont('Arial', '', 7);
		for ($x = 0; $x < $rows; $x ++) {
			db_fieldsmemory($result1, $x, true);
			// testa nova pagina
			if ($pdf->gety() > $pdf->h - 30) {
				$pdf->addpage("L");
				$imprime_header = true;
			}

			if ($imprime_header == true) {
				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->cell(200, $tam, "TOTALIZAÇÃO POR TIPO DE COMPRA", 1, 0, "C", 1);
				$pdf->cell(66, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, 'Codigo', 1, 0, "C", 1);
				$pdf->Cell(100, $tam, strtoupper($RLpc50_descr), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "NÃO LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				$pdf->Ln();
				$pdf->SetFont('Arial', '', 7);
				$imprime_header = false;
			}
			/* ----------- */
			$pdf->Ln(1);
			$pdf->Cell(20, $tam, $e60_codcom, $iBorda, 0, "R", $p);
			$pdf->Cell(100, $tam, $pc50_descr, $iBorda, 0, "L", $p);
			$pdf->Cell(20, $tam, $e60_vlremp, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlranu, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrliq, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrpag, $iBorda, 0, "R", $p);
			$total = $e60_vlrliq - $e60_vlrpag;
			$pdf->Cell(22, $tam, db_formatar($e60_vlrliq - $e60_vlrpag, 'f'), $iBorda, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq, 'f'), $iBorda, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag, 'f'), $iBorda, 1, "R", $p); //quebra linha
			$t_emp5 += $e60_vlremp;
			$t_liq5 += $e60_vlrliq;
			$t_anu5 += $e60_vlranu;
			$t_pag5 += $e60_vlrpag;
			$t_total5 += $total;
			if ($p == 0) {
				$p = 1;
			} else
				$p = 0;
			if ($x == ($rows -1)) {
				$pdf->Ln();
				$pdf->setX(110);
				/* imprime totais -*/
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, "TOTAL ", "T", 0, "L", 1);
				$pdf->Cell(20, $tam, db_formatar($t_emp5, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_anu5, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_liq5, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_pag5, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_liq1 - $t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_pag1, 'f'), "T", 1, "R", 1); // quebra linha
				$pdf->SetFont('Arial', '', 7);

			}
			/* */

		}

	}

}

if ($hist == "h") {

	if ($processar == "a") {
		$sql = "select o58_orgao,
														    o40_descr,
														    sum(e60_vlremp) as e60_vlremp,
														    sum(e60_vlranu) as e60_vlranu,
														    sum(e60_vlrliq) as e60_vlrliq,
														    sum(e60_vlrpag) as e60_vlrpag
													     from empempenho
														    inner join pctipocompra on empempenho.e60_codcom = pctipocompra.pc50_codcom
														    inner join orcdotacao 	on orcdotacao.o58_anousu = empempenho.e60_anousu and
																	       orcdotacao.o58_coddot = empempenho.e60_coddot
														    inner join orcorgao 	on o40_orgao = o58_orgao and o40_anousu = o58_anousu
														    inner join orcunidade 	on o41_orgao = o40_orgao and o41_unidade = o58_unidade and o41_anousu = o40_anousu
														    where $txt_where
															  ";
		$sql = $sql."group by o58_orgao, o40_descr";
	} else {
		$sql = "select x.o58_orgao,
														    x.o40_descr,
														    sum(e60_vlremp) as e60_vlremp,
														    sum(e60_vlranu) as e60_vlranu,
														    sum(e60_vlrliq) as e60_vlrliq,
														    sum(e60_vlrpag) as e60_vlrpag
														    from
														    ($sqlperiodo) as x
														    inner join orcdotacao 	on 	orcdotacao.o58_anousu = x.e60_anousu and
																	       		orcdotacao.o58_coddot = x.e60_coddot
														    inner join orcorgao 	on 	orcorgao.o40_orgao = orcdotacao.o58_orgao and orcorgao.o40_anousu = orcdotacao.o58_anousu
														    inner join orcunidade 	on 	o41_orgao = orcorgao.o40_orgao and o41_unidade = orcdotacao.o58_unidade and o41_anousu = orcorgao.o40_anousu
														    group by x.o58_orgao, x.o40_descr
														    ";
	}
	//     echo $sql;exit;
	$result1 = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0) {
		$pdf->addpage("L");
		$imprime_header = true;
		$rows = $clempempenho->numrows;

		$pdf->SetFont('Arial', '', 7);
		for ($x = 0; $x < $rows; $x ++) {
			db_fieldsmemory($result1, $x, true);
			// testa nova pagina
			if ($pdf->gety() > $pdf->h - 30) {
				$pdf->addpage("L");
				$imprime_header = true;
			}

			if ($imprime_header == true) {
				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->cell(200, $tam, "TOTALIZAÇÃO POR ORGAO", 1, 0, "C", 1);
				$pdf->cell(66, $tam, "SALDO A PAGAR", 1, 1, "C", 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, 'ORGAO', 1, 0, "C", 1);
				$pdf->Cell(100, $tam, "DESCRICAO", 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "NÃO LIQUIDADO", 1, 0, "C", 1);
				$pdf->Cell(22, $tam, "GERAL", 1, 1, "C", 1); //quebra linha
				$pdf->Ln();
				$pdf->SetFont('Arial', '', 7);
				$imprime_header = false;
			}
			/* ----------- */
			$pdf->Ln(1);
			$pdf->Cell(20, $tam, $o58_orgao, $iBorda, 0, "R", $p);
			$pdf->Cell(100, $tam, $o40_descr, $iBorda, 0, "L", $p);
			$pdf->Cell(20, $tam, $e60_vlremp, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlranu, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrliq, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrpag, $iBorda, 0, "R", $p);
			$total = $e60_vlrliq - $e60_vlrpag;
			$pdf->Cell(22, $tam, db_formatar($e60_vlrliq - $e60_vlrpag, 'f'), $iBorda, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrliq, 'f'), $iBorda, 0, "R", $p);
			$pdf->Cell(22, $tam, db_formatar($e60_vlremp - $e60_vlranu - $e60_vlrpag, 'f'), $iBorda, 1, "R", $p); // quebra linha
			$t_emp6 += $e60_vlremp;
			$t_liq6 += $e60_vlrliq;
			$t_anu6 += $e60_vlranu;
			$t_pag6 += $e60_vlrpag;
			$t_total6 += $total;
			if ($p == 0) {
				$p = 1;
			} else
				$p = 0;
			if ($x == ($rows -1)) {
				$pdf->Ln();
				$pdf->setX(110);
				/* imprime totais -*/
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(20, $tam, "TOTAL ", "T", 0, "L", 1);
				$pdf->Cell(20, $tam, db_formatar($t_emp6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_anu6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_liq6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_pag6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_liq1 - $t_pag1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_liq1, 'f'), "T", 0, "R", 1);
				$pdf->Cell(22, $tam, db_formatar($t_emp1 - $t_anu1 - $t_pag1, 'f'), "T", 1, "R", 1); // quebra linha
				$pdf->SetFont('Arial', '', 7);
			}
		}

	}

	$t_emp6 = 0;
	$t_liq6 = 0;
	$t_anu6 = 0;
	$t_pag6 = 0;
	$t_total6 = 0;

	if ($processar == "a") {
		$sql = "select o58_orgao,
														    o58_unidade,
														    o40_descr,
														    o41_descr,
														    sum(e60_vlremp) as e60_vlremp,
														    sum(e60_vlranu) as e60_vlranu,
														    sum(e60_vlrliq) as e60_vlrliq,
														    sum(e60_vlrpag) as e60_vlrpag
													     from empempenho
														    inner join pctipocompra on empempenho.e60_codcom = pctipocompra.pc50_codcom
														    inner join orcdotacao 	on orcdotacao.o58_anousu = empempenho.e60_anousu
																	 and orcdotacao.o58_coddot = empempenho.e60_coddot
														    inner join orcorgao 	on o40_orgao = o58_orgao and o40_anousu = o58_anousu
														    inner join orcunidade 	on o41_orgao = o40_orgao and o41_unidade = o58_unidade and o41_anousu = o40_anousu
														    inner join orcelemento  on  orcelemento.o56_codele = orcdotacao.o58_codele
														                           and  orcelemento.o56_anousu = orcdotacao.o58_anousu
														    where $txt_where
															  ";
		$sql = $sql."group by o58_orgao, o58_unidade, o40_descr, o41_descr";
	} else {
		$sql = "select x.o58_orgao,
														    x.o58_unidade,
														    x.o40_descr,
														    x.o41_descr,
														    sum(e60_vlremp) as e60_vlremp,
														    sum(e60_vlranu) as e60_vlranu,
														    sum(e60_vlrliq) as e60_vlrliq,
														    sum(e60_vlrpag) as e60_vlrpag
													     from ($sqlperiodo) as x
														    inner join orcdotacao 	on orcdotacao.o58_anousu = x.e60_anousu
																	 	and orcdotacao.o58_coddot = x.e60_coddot
														    inner join orcorgao 	on orcorgao.o40_orgao = orcdotacao.o58_orgao and o40_anousu = orcdotacao.o58_anousu
														    inner join orcunidade 	on o41_orgao = orcorgao.o40_orgao and o41_unidade = orcdotacao.o58_unidade and o41_anousu = orcorgao.o40_anousu
														    group by x.o58_orgao, x.o58_unidade, x.o40_descr, x.o41_descr";
	}
	//	 echo $sql;exit;
	$result1 = $clempempenho->sql_record($sql);
	if ($clempempenho->numrows > 0 and 1 == 2) {
		$pdf->addpage("L");
		$imprime_header = true;
		$rows = $clempempenho->numrows;

		$pdf->SetFont('Arial', '', 7);
		for ($x = 0; $x < $rows; $x ++) {
			db_fieldsmemory($result1, $x, true);
			// testa nova pagina
			if ($pdf->gety() > $pdf->h - 30) {
				$pdf->addpage("L");
				$imprime_header = true;
			}

			if ($imprime_header == true) {
				$pdf->Ln();
				$pdf->SetFont('Arial', 'B', 8);
				$pdf->cell(275, $tam, "TOTALIZAÇÃO POR ORGAO/UNIDADE", 1, 1, "C", 1);
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(10, $tam, 'ORGAO', 1, 0, "C", 1);
				$pdf->Cell(60, $tam, "DESCRICAO", 1, 0, "C", 1);
				$pdf->Cell(15, $tam, "UNIDADE", 1, 0, "C", 1);
				$pdf->Cell(80, $tam, "DESCRICAO", 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlremp), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlranu), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrliq), 1, 0, "C", 1);
				$pdf->Cell(20, $tam, strtoupper($RLe60_vlrpag), 1, 0, "C", 1);
				$pdf->Cell(30, $tam, "TOTAL A PAGAR", 1, 1, "C", 1); //quebra linha
				$pdf->Ln();
				$pdf->SetFont('Arial', '', 7);
				$imprime_header = false;
			}
			/* ----------- */
			$pdf->Ln(1);
			$pdf->Cell(10, $tam, $o58_orgao, $iBorda, 0, "R", $p);
			$pdf->Cell(60, $tam, substr($o40_descr, 0, 50), $iBorda, 0, "L", $p);
			$pdf->Cell(15, $tam, $o58_unidade, $iBorda, 0, "L", $p);
			$pdf->Cell(80, $tam, $o41_descr, $iBorda, 0, "L", $p);
			$pdf->Cell(20, $tam, $e60_vlremp, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlranu, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrliq, $iBorda, 0, "R", $p);
			$pdf->Cell(20, $tam, $e60_vlrpag, $iBorda, 0, "R", $p);
			$total = $e60_vlrliq - $e60_vlrpag;
			$pdf->Cell(30, $tam, db_formatar($total, 'f'), $iBorda, 1, "R", $p); //quebra linha
			$t_emp6 += $e60_vlremp;
			$t_liq6 += $e60_vlrliq;
			$t_anu6 += $e60_vlranu;
			$t_pag6 += $e60_vlrpag;
			$t_total6 += $total;
			if ($p == 0) {
				$p = 1;
			} else
				$p = 0;
			if ($x == ($rows -1)) {
				$pdf->Ln();
				//  $pdf->setX(110);
				/* imprime totais -*/
				$pdf->SetFont('Arial', 'B', 7);
				$pdf->Cell(140, $tam, "", "T", 0, "L", 0);
				$pdf->Cell(25, $tam, "TOTAL ", "T", 0, "L", 1);
				$pdf->Cell(20, $tam, db_formatar($t_emp6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_anu6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_liq6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(20, $tam, db_formatar($t_pag6, 'f'), "T", 0, "R", 1);
				$pdf->Cell(30, $tam, db_formatar($t_total6, 'f'), "T", 1, "R", 1); //quebra linha
				$pdf->SetFont('Arial', '', 7);
			}
		}

	}

}
$pdf->output();
?>
