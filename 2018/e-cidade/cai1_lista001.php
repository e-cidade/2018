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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_lista_classe.php");
include ("classes/db_listadeb_classe.php");
include ("classes/db_listatipos_classe.php");
include ("classes/db_arretipo_classe.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

$clarretipo = new cl_arretipo;
$clrotulo   = new rotulocampo;

$clarretipo->rotulo->label();
$clrotulo->label('k60_codigo');
$clrotulo->label('k60_descr');
$clrotulo->label('k00_tipo');
$clrotulo->label('k00_descr');
$clrotulo->label('DBtxt10');
$clrotulo->label('DBtxt11');

db_postmemory($HTTP_POST_VARS);

$instit     = db_getsession("DB_instit");
$and        = "";
$exercfinal = "";
$tipodeb    = "";

$clpostgresqlutils = new PostgreSQLUtils;
$cllista           = new cl_lista;
$cllistadeb        = new cl_listadeb;
$cllistatipos      = new cl_listatipos;

$db_opcao  = 1;
$db_botao  = true;

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {

  db_msgbox("Problema nos índices da tabela débitos. Entre em contato com CPD.");
  $db_opcao  = 3;
  $db_botao  = false;
} else {

  $db_opcao  = 1;
  $db_botao  = true;
}

$debugando = false;

$txt_where = " and debitos.k22_instit = ".db_getsession('DB_instit');
if ((isset ($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]) == "Incluir") {
	$xmassa1 = "";
	$xmassa2 = "";
	$xloteamento1 = "";
	$xloteamento2 = "";
	$dtini = $dtini_ano."-".$dtini_mes."-".$dtini_dia;
	$dtfim = $dtfim_ano."-".$dtfim_mes."-".$dtfim_dia;

	$dataini = $dataini_ano."-".$dataini_mes."-".$dataini_dia;
	$datafim = $datafim_ano."-".$datafim_mes."-".$datafim_dia;

	//$descondataini = $descondataini_ano."-".$descondataini_mes."-".$descondataini_dia;
	//$descondatafim = $descondatafim_ano."-".$descondatafim_mes."-".$descondatafim_dia;

	$descondataini = "--";
	$descondatafim = "--";

	$tipos = '';
	$dataoper = "";
	$dataoperfinal = "";
	$dataoperfinaldeb = "";
	$dataexerc = "";


 if (($numini != "") && ($numfim != "")) {
   $txt_where .=" and debitos.k22_numpar between $numini and $numfim and k22_dtvenc<'".date("Y-m-d",db_getsession("DB_datausu"))."'";
 } else if ($numini != "") {

	 $txt_where .= " and debitos.k22_numpar >= $numini and k22_dtvenc<'".date("Y-m-d",db_getsession("DB_datausu"))."'";
 } else if ($numfim != "") {
	 $txt_where .=	" and  debitos.k22_numpar <= $numini  and k22_dtvenc<'".date("Y-m-d",db_getsession("DB_datausu"))."'";
 }



	if ($dtini != "--" && $dtfim == "--") {
		$dataoper         .= " and k22_dtoper >= '$dtini' ";
		$dataoperfinal    .= " and k22_dtoper >= '$dtini' ";
		$dataoperfinaldeb .= " and debitos.k22_dtoper >= '$dtini' ";
		$dataexerc        .= " and debitos.k22_exerc >= $exercini ";
	}else if ($dtini != "--" && $dtfim != "--") {
		$dataoper         .= " and k22_dtoper >= '$dtini' and k22_dtoper <= '$dtfim' ";
		$dataoperfinal    .= " and k22_dtoper >= '$dtini' ".($considerar == 'f'?" and k22_dtoper <= '$dtfim'":"");
		$dataoperfinaldeb .= " and debitos.k22_dtoper >= '$dtini'  ".($considerar == 'f'?" and debitos.k22_dtoper <= '$dtfim'":"");
		$dataexerc        .= " and debitos.k22_exerc  >= $exercini ".($considerar == 'f'?" and debitos.k22_exerc  <= $exercfinal":"");
	}else if ($dtini == "--" && $dtfim != "--") {
		$dataoper  .= " and k22_dtoper <= '$dtfim' ";
		$dataexerc .= " and k22_exerc <= $exercfinal";
	}
	if ($dataoper != "") {
		$dataoper .= $and;
	}
	if ($dataoperfinal != "") {
		$dataoperfinal .= $and;
		$dataoperfinaldeb .= $and;
		$dataexerc .= " and ";
	}

	$datavenc         = "";
	$datavencfinal    = "";
	$datavencfinaldeb = "";
	if ($dataini != "--" && $datafim == "--") {
		$datavenc         .= " and k22_dtvenc >= '$dataini' ";
		$datavencfinal    .= " and k22_dtvenc >= '$dataini' ";
		$datavencfinaldeb .= " and k22_dtvenc >= '$dataini' ";
	}
	elseif ($dataini != "--" && $datafim != "--") {
		$datavenc         .= " and k22_dtvenc >= '$dataini' and k22_dtvenc < '$datafim' ";
		$datavencfinal    .= " and k22_dtvenc >= '$dataini' ".($considerar == 'f'?" and k22_dtvenc < '$datafim'":"");
		$datavencfinaldeb .= " and debitos.k22_dtvenc >= '$dataini' ".($considerar == 'f'?" and debitos.k22_dtvenc < '$datafim'":"");
	}
	elseif ($dataini == "--" && $datafim != "--") {
		$datavenc .= " and k22_dtvenc < '$datafim' ";
	}
	if ($datavenc != "") {
		$datavenc         .= $and;
		$datavencfinal    .= $and;
		$datavencfinaldeb .= $and;
	}


	$descondata = "";
	$descondatafinal = "";
	$descondatafinaldeb = "";
	if ($descondataini != "--" && $descondatafim == "--") {
		$descondata         .= " and k22_dtoper >= '$descondataini' ";
		$descondatafinal    .= " and k22_dtoper >= '$descondataini' ";
		$descondatafinaldeb .= " and k22_dtoper >= '$descondataini' ";
	}
	elseif ($descondataini != "--" && $descondatafim != "--") {
		$descondata         .= " and k22_dtoper >= '$descondataini' and k22_dtoper <= '$descondatafim' ";
		$descondatafinal    .= " and k22_dtoper >= '$descondataini' " . ($considerar == 'f'?" and k22_dtoper <= '$descondatafim'":"");
		$descondatafinaldeb .= " and debitos.k22_dtoper >= '$descondataini' " . ($considerar == 'f'?" and debitos.k22_dtoper <= '$descondatafim'":"");
	}
	elseif ($descondataini == "--" && $descondatafim != "--") {
		$descondata .= " k22_dtoper <= '$descondatafim' ";
	}
	if ($descondata != "") {
		$descondata          .= $and;
		$descondatafinal     .= $and;
		$descondatafinaldeb  .= $and;
	}

	$descondata = "";
	$descondatafinal = "";
	$descondatafinaldeb = "";
  if ($considerar == 't') {
		if ($desconexercini != "" and $desconexercfim == "") {
			$descondata         .= " and k22_exerc <> $desconexercini ";
			$descondatafinal    .= " and k22_exerc <> $desconexercini ";
			$descondatafinaldeb .= " and k22_exerc <> $desconexercini ";
		} elseif ($desconexercini != "" and $desconexercfim != "") {
			$descondata         .= " and k22_exerc not between $desconexercini and $desconexercfim ";
			$descondatafinal    .= " and k22_exerc not between $desconexercini and $desconexercfim ";
			$descondatafinaldeb .= " and k22_exerc not between $desconexercini and $desconexercfim ";
		} elseif ($desconexercini == "" and $desconexercfim != "") {
			$descondata .= " and k22_exerc <> $desconexercfim ";
		}
	}

	$exerc = "";
	$exercfinal = "";
	$exercfinaldeb = "";
	if ($exercini != "" && $exercfim == "") {
		$exerc          .= " and k22_exerc between $exercini and 2100 $descondata";
		$exercfinal     .= " and k22_exerc between $exercini and 2100 $descondata";
		$exercfinaldeb  .= " and k22_exerc between $exercini and 2100 $descondata";
	}
	elseif ($exercini != "" && $exercfim != "") {
		$exerc          .= " and k22_exerc between $exercini and $exercfim $descondata ";

 // $exercfinal.="and (k22_exerc between $exercini  and $exercfim ";
       if ($considerar != 'f'){
           //$exercfinal.="and ((k22_exerc between $exercini and $exercfim ) or (k22_exerc between $exercini and $exercfim and k22_exerc > $exercfim)) ";
           //$exercfinal.="and ((k22_exerc between $exercini and $exercfim ) or (k22_exerc between $exercini and $exercfim and k22_exerc > $exercfim)) ";
           $exercfinal.=" and k22_exerc >= $exercini ";
       }else{
           $exercfinal.=" and k22_exerc between $exercini  and $exercfim ";
       }
    $exercfinal.= $descondata;


		$exercfinaldeb  .= " and debitos.k22_exerc between $exercini ".($considerar == 'f'?" and $exercfim":" and 2100") . $descondata;
	}
	elseif ($exercini == "" && $exercfim != "") {
		$exerc .= " and k22_exerc between 1900 and $exercfim $descondata";
	}
	if ($exerc != "") {
		$exerc .= $and;
		$exercfinal .= $and;
		$exercfinaldeb .= $and;
	}

  $data = "";
	db_inicio_transacao();
	$erro1 = false;
	$data1 = $data1_ano.'-'.$data1_mes.'-'.$data1_dia;
	$data = $data_ano.'-'.$data_mes.'-'.$data_dia;

	$filtro  = "";
	$filtro .= " # Descrição: $k60_descr";
  $filtro .= " # Valores de: $DBtxt10 ate $DBtxt11";
	$data    = $data_ano.'-'.$data_mes.'-'.$data_dia;
	$filtro .= " # Data do Cálculo: ".db_formatar($data,"d");
	$filtro .= " # Quantidade a Listar: $numerolista2";

	if($k60_tipo=='M'){
		$filtro .=" # Tipo de Lista: Matrícula";
	}elseif($k60_tipo=='I'){
		$filtro .=" # Tipo de Lista: Inscrição";
	}elseif($k60_tipo=='C'){
		$filtro .=" # Tipo de Lista: Somente CGM";
	}elseif($k60_tipo=='N'){
		$filtro .=" # Tipo de Lista: CGM";
	}

	// Somente para não notificados após

	if($data1!="--" && $data1!=""){

		$data1 = $data1_ano.'-'.$data1_mes.'-'.$data1_dia;
		//$datanot= "# Somente para não notificados após : ".db_formatar($data1,"d");
		$filtro .="# Não Considerar Notificados Até:".db_formatar($data1,"d");
    $datanotif=" and k50_dtemite <= '$data1'";
    if ($notiftipo == 0){
        $filtro.=" - Geral";
    }elseif ($notiftipo == 1){
        $filtro.=" - Tipo de débito";
    }else{
        $filtro.=" - Numpre/Parcela";
    }
	}
  else{
    $data1=null;
    $datanotif=" and k50_dtemite <= '1900-01-01'";

  }
	// massa falida
	if($massa=='f'){
		$filtro .=" # Lista Massa Falida : Não";
	}else{
		$filtro .= " # Lista Massa Falida : Sim";
	}
	// considera loteamento
	if($loteamento=='f'){
		$filtro .= " # Considera loteamentos: Não";
	}else{
		$filtro .= " # Considera loteamentos: Sim";
	}

	//Data de operação de $dtini a $dtfim
		$dtini = db_formatar($dtini,"d");
		$dtfim = db_formatar($dtfim,"d");
		$filtro .=" # Data de operação de $dtini a $dtfim";
		$dataini = db_formatar($dataini,"d");
		$datafim = db_formatar($datafim,"d");
		//$datavenc = " # Vencimento de $dataini a $datafim ";
		$filtro .=" # Vencimento de $dataini a $datafim ";
		$filtro .= " # Exercícios $exercini a $exercfim ";
	// Considerar periodos posteriores: $considerar
	if($considerar	=='f'){
		//$considerar	 = " # Considerar periodos posteriores: Não";
		$filtro .=	" # Considerar periodos posteriores: Não";
	}else{
		//$considerar	 = " # Considerar periodos posteriores: Sim";
		$filtro .=" # Considerar periodos posteriores: Sim";
	}
	//Desconsiderando exercicios
	$filtro .=" # Desconsiderando exercicios $desconexercini a $desconexercfim ";
	$filtro .=" # Quantidade de Parcelas em atraso  $parcini a $parcfim";
	$filtro .=" # Número das Parcelas em atraso  $numini a $numfim";
	//Tipo de débito
	$cllista->k60_datadeb = $data;
	$cllista->k60_usuario = db_getsession("DB_id_usuario");
	$cllista->k60_filtros = $filtro;
	$cllista->k60_instit  = db_getsession("DB_instit");
	$cllista->incluir('');
	if ($cllista->erro_status != "0") {
	  // echo 'parou na lista';
	  $erro1 = true;
	} else {
	  $cllista->erro(true, false);
	}
	$opcaodeb = "";
	if (isset ($campos)) {
		$tipos = ' and debitos.k22_tipo in (';
		$virgula = '';
		//       Sem os Selecionados

		if ($opcaofiltro == 2) {
			$tipodeb = "";
			$opcaodeb = " # Opção: Sem os selecionados";
			$resul = $clarretipo->sql_record($clarretipo->sql_query(null,"*",null));
			if ($clarretipo->numrows != 0) {
				$numrows = $clarretipo->numrows;
				for ($i = 0; $i < $numrows; $i ++) {
					db_fieldsmemory($resul, $i);
					if (!in_array($k00_tipo, $campos)) {
						$cllistatipos->k62_lista = $cllista->k60_codigo;
						$cllistatipos->k62_tipodeb = $k00_tipo;
						$cllistatipos->incluir($cllista->k60_codigo,$k00_tipo);
						$tipodeb .= $virgula.$k00_tipo."-".$k00_descr;
						$tipos .= $virgula.$k00_tipo;
						$virgula = ', ';
					}
				}
			}
		}
		//       Com  os Selecionados
		if ($opcaofiltro == 1) {
			$tipodeb1 = "";
			$opcaodeb = " # Opção: Com os selecionados";
			for ($i = 0; $i < sizeof($campos); $i ++) {
				$cllistatipos->k62_lista = $cllista->k60_codigo;
				$cllistatipos->k62_tipodeb = $campos[$i];
				$cllistatipos->incluir($cllista->k60_codigo, $campos[$i]);
				if ($cllistatipos->erro_status != "0") {
					//            echo 'parou na listatipo';
					$erro1 = true;
				} else {
					$cllistatipos->erro(true, false);
				}

				$tipodeb1 .= $virgula.$campos[$i];
				$tipos .= $virgula.$campos[$i];
				$virgula = ', ';
				$tipodeb = "";


			}
			$sqltip = "select k00_tipo,k00_descr from arretipo where k00_tipo in($tipodeb1)";
				$resulttip = db_query($sqltip);
				$linhastip = pg_num_rows($resulttip);
				$virgula = "";
				for ($x = 0; $x < $linhastip ; $x ++) {
					db_fieldsmemory($resulttip, $x);
					$tipodeb .= $virgula.$k00_tipo."-".$k00_descr;
					$virgula = ", ";
				}
		}
		$tipos .= ')';
	}

	//aki **********************************

	$codlista = $cllista->k60_codigo;
    $tiposdeb = "# Tipos de Débitos:$tipodeb";
    $filtro2 = " $filtro $opcaodeb $tiposdeb ";
    $cllista->k60_codigo  = $codlista;
	$cllista->k60_filtros = $filtro2;
	$cllista->k60_instit  = db_getsession("DB_instit");
	$cllista->alterar($codlista);


		$limite = '';"";
		if ($numerolista2 != '') {
			$limite = " limit ".$numerolista2;
		}
		$xmassa = "";
	if ($k60_tipo == 'M') {
		if ($massa == 'f') {
			$xmassa1 = " left join massamat on debitos.k22_matric = massamat.j59_matric ";
			$xmassa2 = " and massamat.j59_matric is null ";
		} else {
			$xmassa1 = "";
			$xmassa2 = "";
		}
		if ($loteamento == 'f') {
			$xloteamento1 = " left join iptubase on debitos.k22_matric = iptubase.j01_matric
												left join loteloteam on loteloteam.j34_idbql = iptubase.j01_idbql";
			$xloteamento2 = " and loteloteam.j34_idbql is null ";
		} else {
			$xloteamento1 = "";
			$xloteamento2 = "";
		}
    //echo "matricula";exit;
		$xtipo = 'k22_matric ';
		$matinsc = " and debitos.k22_matric is not null and debitos.k22_matric <> 0";
		$leftnoti = " left join notimatric on k55_matric = debitos.k22_matric left join notificacao on k50_notifica = k55_notifica  and notificacao.k50_instit = $instit ";

    }elseif ($k60_tipo == 'I') {
    $xtipo = 'k22_inscr ';
		$matinsc = ' and debitos.k22_inscr is not null and debitos.k22_inscr <>0';
		$leftnoti = " left join notiinscr on k56_inscr = debitos.k22_inscr left join notificacao on k50_notifica = k56_notifica and notificacao.k50_instit = $instit";

  }  elseif ($k60_tipo == 'N') {
		$xtipo = 'k22_numcgm ';
		$leftnoti = " left join notinumcgm on k57_numcgm = debitos.k22_numcgm left join notificacao on k50_notifica = k57_notifica and notificacao.k50_instit = $instit ";
		$matinsc = " and debitos.k22_numcgm is not null";

  }elseif ($k60_tipo == 'C'){
   //echo " Somente CGM";exit;
   $xtipo = 'k22_numcgm ';
   $leftnoti = " left join notinumcgm on k57_numcgm = debitos.k22_numcgm left join notificacao on k50_notifica = k57_notifica and notificacao.k50_instit = $instit ";
   $matinsc = " and debitos.k22_numcgm is not null and debitos.k22_matric=0 and debitos.k22_inscr=0";

   }


    if(!isset($txt_where)){
    	$txt_where="";
    }
  $where_quant = "";
  if ($parcini != "") {
		$parcini = (int) $parcini;
		$parcfim = (int) $parcfim;

            $txt_where .= " and debitos.k22_dtvenc < '".date('Y-m-d',db_getsession('DB_datausu'))."'";
		    $where_quant = " and bbbbb.k22_numpar=yyyyy.k22_numpar
    where ( select count(distinct k00_numpar)
				      from arrecad
				     where k00_numpre = yyyyy.k22_numpre
				       and k00_dtvenc < '".date('Y-m-d',db_getsession('DB_datausu'))."'
    ) between $parcini and $parcfim and bbbbb.k22_dtvenc < '".date('Y-m-d',db_getsession('DB_datausu'))."'";
	}

//
// inicio sql insert na listadeb
//

$testa_considera = "";
$inner_considera = "";
$where_considera = "";
$where_totalzao  = "";
$leftnotidebitos = "";
$xtipoinscr      = "";
$where_tipoinscr = "";

$valor_ini_totalzaoexerc = $DBtxt10;
$valor_fim_totalzaoexerc = $DBtxt11;

$vartotalexerc = "bbb.totalzao";
$vartotalzao   = "yyy.totalgeral";

$sTotalLista   = "totalzao";
$sTotalWhere   = "";

if ($notiftipo == "0") {
  if ($k60_tipo == "N" or $k60_tipo == "C") {
    $testa_considera = "debitos.k22_numcgm";
    $inner_considera = "xxx.k22_numcgm = debitos.k22_numcgm";
    $where_considera = "xxx.k22_numcgm is null";
    if (isset($DBtxt10) && isset($DBtxt11)){
     $where_considera .= " and $vartotalexerc between $valor_ini_totalzaoexerc and $valor_fim_totalzaoexerc";
     $where_totalzao  .= " and $vartotalzao between $DBtxt10 and $DBtxt11";
     $sTotalWhere      = " and $sTotalLista between $DBtxt10 and $DBtxt11";
    }

  } elseif ($k60_tipo == "M") {
    $testa_considera = "debitos.k22_matric";
    $inner_considera = "xxx.k22_matric = debitos.k22_matric";
    $where_considera = "xxx.k22_matric is null";
    if (isset($DBtxt10) && isset($DBtxt11)){
     $where_considera .= " and $vartotalexerc between $valor_ini_totalzaoexerc and $valor_fim_totalzaoexerc";
     $where_totalzao  .= " and $vartotalzao between $DBtxt10 and $DBtxt11";
     $sTotalWhere      = " and $sTotalLista between $DBtxt10 and $DBtxt11";
    }
  } elseif ($k60_tipo == "I") {
    $testa_considera = "debitos.k22_inscr";
    $inner_considera = "xxx.k22_inscr = debitos.k22_inscr";
    $where_considera = "xxx.k22_inscr is null";
     if (isset($DBtxt10) && isset($DBtxt11)){
       $where_considera .= " and $vartotalexerc between $valor_ini_totalzaoexerc and $valor_fim_totalzaoexerc";
       $where_totalzao  .= " and $vartotalzao between $DBtxt10 and $DBtxt11";
       $sTotalWhere      = " and $sTotalLista between $DBtxt10 and $DBtxt11";
     }
  }
} elseif ($notiftipo == "1") {
  if ($k60_tipo == "N" or $k60_tipo == "C") {
    $testa_considera = "debitos.k22_numcgm, debitos.k22_tipo";
    $inner_considera = "xxx.k22_numcgm = debitos.k22_numcgm and xxx.k22_tipo = debitos.k22_tipo";
    $where_considera = "xxx.k22_numcgm is not null";
    if (isset($DBtxt10) && isset($DBtxt11)){
      $where_considera .= " and $vartotalexerc between $valor_ini_totalzaoexerc and $valor_fim_totalzaoexerc";
      $where_totalzao  .= " and $vartotalzao between $DBtxt10 and $DBtxt11";
      $sTotalWhere      = " and $sTotalLista between $DBtxt10 and $DBtxt11";
    }

  } elseif ($k60_tipo == "M") {
    $testa_considera = "debitos.k22_matric, debitos.k22_tipo";
    $inner_considera = "xxx.k22_matric = debitos.k22_matric and xxx.k22_tipo = debitos.k22_tipo";
    $where_considera = "xxx.k22_matric is null";
    if (isset($DBtxt10) && isset($DBtxt11)){
      $where_considera .= " and $vartotalexerc between $valor_ini_totalzaoexerc and $valor_fim_totalzaoexerc";
      $where_totalzao  .= " and $vartotalzao between $DBtxt10 and $DBtxt11";
      $sTotalWhere      = " and $sTotalLista between $DBtxt10 and $DBtxt11";
    }

  } elseif ($k60_tipo == "I") {
    $testa_considera = "debitos.k22_inscr, debitos.k22_tipo";
    $inner_considera = "xxx.k22_inscr = debitos.k22_inscr and xxx.k22_tipo = debitos.k22_tipo";
    $where_considera = "xxx.k22_inscr is null";

    if (isset($DBtxt10) && isset($DBtxt11)){
      $where_considera .= " and $vartotalexerc between $valor_ini_totalzaoexerc and $valor_fim_totalzaoexerc";
      $where_totalzao  .= " and $vartotalzao between $DBtxt10 and $DBtxt11";
      $sTotalWhere      = " and $sTotalLista between $DBtxt10 and $DBtxt11";
    }

  }
} elseif ($notiftipo == "2") {

  if ($k60_tipo == "I"){
      $testa_considera = "debitos.k22_numpre";
      $inner_considera = "xxx.k22_numpre = debitos.k22_numpre ";
      $where_considera = " notidebitos.k53_numpre is null";
      $leftnotidebitos = "left join notidebitos on notidebitos.k53_notifica = k56_notifica and notidebitos.k53_numpre = debitos.k22_numpre and notidebitos.k53_numpar = debitos.k22_numpar";
      $where_tipoinscr = " and yyy.k22_numpre=debitos.k22_numpre and yyy.k22_numpar=debitos.k22_numpar";
      $xtipoinscr=",x.k22_numpre,x.k22_numpar";
      if (isset($DBtxt10) && isset($DBtxt11)){
        $where_considera .= " and $vartotalexerc between $valor_ini_totalzaoexerc and $valor_fim_totalzaoexerc";
        $where_totalzao  .= " and $vartotalzao between $DBtxt10 and $DBtxt11";
        $sTotalWhere      = " and $sTotalLista between $DBtxt10 and $DBtxt11";
      }

 }elseif ($k60_tipo == "M"){
      $testa_considera = " debitos.k22_numpre";
      $inner_considera = " xxx.k22_numpre = debitos.k22_numpre ";
      $where_considera = " notidebitos.k53_numpre is null ";
      $leftnotidebitos = " left join notidebitos on notidebitos.k53_notifica = k55_notifica and notidebitos.k53_numpre = debitos.k22_numpre and notidebitos.k53_numpar = debitos.k22_numpar";
      $where_tipoinscr = " and yyy.k22_numpre=debitos.k22_numpre and yyy.k22_numpar=debitos.k22_numpar";
      $xtipoinscr      = " ,x.k22_numpre,x.k22_numpar";
      if (isset($DBtxt10) && isset($DBtxt11)){
        $where_considera .= " and $vartotalexerc between $valor_ini_totalzaoexerc and $valor_fim_totalzaoexerc";
        $where_totalzao  .= " and $vartotalzao between $DBtxt10 and $DBtxt11";
        $sTotalWhere      = " and $sTotalLista between $DBtxt10 and $DBtxt11";
      }


 }else{
     $testa_considera = "debitos.k22_numpre";
     $inner_considera = "xxx.k22_numpre = debitos.k22_numpre ";
     $where_considera = "xxx.k22_numpre is null";
     if (isset($DBtxt10) && isset($DBtxt11)){
       $where_considera .= " and $vartotalexerc between $valor_ini_totalzaoexerc and $valor_fim_totalzaoexerc";
       $where_totalzao  .= " and $vartotalzao between $DBtxt10 and $DBtxt11";
       $sTotalWhere      = " and $sTotalLista between $DBtxt10 and $DBtxt11";
     }

 }

}


$where_totalzao = " totalzaogeraltipo between $DBtxt10 and $DBtxt11";

// Melhora performance da lista
$sqlbitmapscan="set enable_bitmapscan to on;";
@db_query($sqlbitmapscan);

$sWhereParcelas = "";
if ($parcini != "") {
  $parcini = (int) $parcini;
  $parcfim = (int) $parcfim;
  $sWhereParcelas = " and ( select count(distinct k00_numpar)
                              from arrecad
  							             where k00_numpre = debitos.k22_numpre
	  						               and k00_dtvenc < '".date('Y-m-d',db_getsession('DB_datausu'))."'
								          ) between $parcini and $parcfim ";
}

$sqlcria = "create temporary table w_lista_aaa as

      select distinct aaa.$xtipo,aaa.totalzao from
      (
         select debitos.$xtipo,
                sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto) as totalzao
                from debitos
                $xmassa1
                $xloteamento1
                where k22_data = '$data' and 1=1

                $matinsc

                $xmassa2 $xloteamento2 and 1=1
                $datavenc and 1=1
                $dataoper and 1=1
                $exerc
                and 1=1
                $tipos
                $sWhereParcelas
                $txt_where
                group by debitos.$xtipo";

if ($considerar == true && $exercfim !='' ) {

$sqlcria .= " union all

	       select debitos.$xtipo,
                sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto) as totalzao
                from debitos
          inner join ( select distinct $xtipo
                         from debitos
                        where k22_data = '$data'
                              $exerc
                              and 1=1
                              $tipos
                          and k22_instit = $instit
                     ) as posteriores on posteriores.$xtipo = debitos.$xtipo
                $xmassa1
                $xloteamento1
                where k22_data = '$data' and 1=1
                $matinsc
                $xmassa2 $xloteamento2 and 1=1
                $datavenc and 1=1
                $dataoper
                and k22_exerc >= $exercfim
                and 1=1
                $tipos
                $sWhereParcelas
                $txt_where
                group by debitos.$xtipo";

}


      $sqlcria .= ") as aaa
                        where 1=1 $sTotalWhere ";

if ($debugando == true) {
	echo "<pre>";
  echo "$sqlcria ;<br> <br>";
}

$resultcria = db_query($sqlcria) or die($sqlcria);


$sqlcria = "create temporary table w_lista_bbb as
            select  distinct $testa_considera
            from debitos
                        $leftnoti
                        $xmassa1
                        $xloteamento1
            where k22_data = '$data'
                  $datanotif
                  and 1=1
                  $matinsc
                  $xmassa $xloteamento2
                  and 1=1
                  $tipos
                  $txt_where";
if ($debugando == true) {
  echo "$sqlcria ;<br> <br>";
}

$resultcria = db_query($sqlcria) or die($sqlcria);


$sqlcriaindice="create index w_lista_" . trim($xtipo) . "_matric_in on w_lista_aaa($xtipo)";
if ($debugando == true) {
  echo "$sqlcriaindice ; <br> <br>" ;
}
$resultcriaindice = db_query($sqlcriaindice) or die($sqlcriaindice);


$sqltotalzaogeraltipo = "

select 	sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto) as totalzaogeraltipo
        from nomedatabela
        $xmassa1
        $xloteamento1
        where debitos.k22_data = '$data' and 1=1

        $matinsc

        $xmassa2 $xloteamento2 and 1=1
        $datavenc and 1=1
        $dataoper and 1=1
        $exercfinal
        and 1=1
        $tipos
        $txt_where";

$sqltotalzaogeraltipo = str_replace("debitos","deb2",$sqltotalzaogeraltipo);
$sqltotalzaogeraltipo = str_replace("nomedatabela","debitos deb2",$sqltotalzaogeraltipo);

$sqltotalzaogeraltipo .= " and deb2.$xtipo = debitos.$xtipo";


$sql = "insert into listadeb";

	$sql .= "
	        select 	distinct ".$cllista->k60_codigo.",
					yyyyy.k22_numpre,
					yyyyy.k22_numpar from
				  (
					select 	debitos.$xtipo,

              (

              $sqltotalzaogeraltipo


              ) as totalzaogeraltipo,

							(sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto)) as totalgeral,
							debitos.k22_dtoper,
							debitos.k22_dtvenc,
							debitos.k22_numpre,
							debitos.k22_numpar
							from
              (

						select 	x.$xtipo,
							(sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto)) as total,
              x.k22_numpre,
              x.k22_numpar

								from
								(

									select distinct	debitos.*
										from debitos
													$leftnoti
                          $leftnotidebitos
													inner join
												(

                        select $xtipo,totalzao from w_lista_aaa

												) as bbb on bbb.$xtipo = debitos.$xtipo and k22_data = '$data'

												left join	(
													select 	* from w_lista_bbb
												) as xxx on
                        $inner_considera
												$xmassa1
												$xloteamento1
												where
                                $where_considera and
																k22_data = '$data' and 1=1

                                $matinsc

																$xmassa2 $xloteamento2 and 1=1
																$datavenc  and 1=1
																$dataoper  and 1=1
																$exercfinal and 1=1
																$tipos
									              $txt_where
								) as x


								where 1=1
											$dataoperfinal and 1=1
											$datavencfinal and 1=1
											group by x.$xtipo,x.k22_numpre,x.k22_numpar
											$limite




						) as yyy
						inner join debitos on k22_data = '$data' and yyy.$xtipo = debitos.$xtipo $where_tipoinscr
						$xmassa1
						$xloteamento1
					  where
                  1=1
									$tipos and 1=1

                  $matinsc

									$exercfinal and 1=1
									$xmassa2 $xloteamento2 and 1=1
									$datavenc and 1=1
									$dataoper and 1=1
									$txt_where
						group by 	debitos.$xtipo,
											debitos.k22_dtoper,
											debitos.k22_dtvenc,
											debitos.k22_numpre,
											debitos.k22_numpar
											$limite
					) as yyyyy";



  // segunda parte da desconsiderar exercicios

	$sql .= " " .	($descondata == ""?"":
					"left join
								(
									select $xtipo
									from debitos
									$xmassa1
									$xloteamento1
									where k22_data = '$data' and 1=1

                  $matinsc

									$descondata and 1=1
									$xmassa2 $xloteamento2 and 1=1
									$tipos
									$txt_where
								)
					as desconsiderar on desconsiderar.$xtipo = yyyyy.$xtipo ") .

					($descondata == ""?"":" and desconsiderar.$xtipo is null");

  // quantidade de parcelas em atraso e numero de parcelas em atraso

  $sql .= " " . ($where_quant != ""?"

						inner join
				  (
					select 	debitos.k22_numpre,
                  debitos.k22_numpar,
                  debitos.k22_dtvenc,
							count(distinct debitos.k22_numpar) as quantparc
							from
						(
						select 	x.$xtipo,
							(sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto)) as total
								from
								(
									select distinct	debitos.*
										from debitos
													$leftnoti
													inner join
												(
													select aaa.$xtipo,totalzao from
													(
														select 	debitos.$xtipo,
																		sum(k22_vlrcor)+sum(k22_juros)+sum(k22_multa)-sum(k22_desconto) as totalzao
																		from debitos
																		$leftnoti
																		$xmassa1
																		$xloteamento1
																		where k22_data = '$data' and 1=1

                                    $matinsc

																		$xmassa2 $xloteamento2 and 1=1
																		$datavenc and 1=1
																		$dataoper and 1=1
																		$exerc and 1=1
																		$tipos
									                  $txt_where
																		group by debitos.$xtipo
													) as aaa

												) as bbb
												on bbb.$xtipo = debitos.$xtipo and k22_data = '$data'

												left join	(
													select 	distinct $testa_considera
																	from debitos
																	$leftnoti
																	$xmassa1
																	$xloteamento1
																	where k22_data = '$data'
                                                   $datanotif
                                                   and 1=1
																				$xmassa2 $xloteamento2 and 1=1

                                        $matinsc

																				$tipos
									                      $txt_where
																	) as xxx on $inner_considera
												$xmassa1
												$xloteamento1
												where
                                $where_considera and

																k22_data = '$data' and 1=1
																$xmassa2 $xloteamento2 and 1=1
																$datavenc  and 1=1
																$dataoper  and 1=1
																$exercfinal and 1=1

                                $matinsc

																$tipos
									              $txt_where
								) as x
								where 1=1
											$dataoperfinal and 1=1
											$datavencfinal and 1=1
											group by x.$xtipo
											$limite
						) as yyy
						inner join debitos on k22_data = '$data' and yyy.$xtipo = debitos.$xtipo
						$xmassa1
						$xloteamento1
					  where total between $DBtxt10 and $DBtxt11 and 1=1

                  $matinsc

									$tipos and 1=1
									$exercfinal and 1=1
									$xmassa2 $xloteamento2 and 1=1
									$datavenc and 1=1
									$dataoper and 1=1
									$txt_where
									and k22_dtvenc < '" . date("Y-m-d",db_getsession("DB_datausu")) . "'

						group by 	debitos.k22_numpre,
						        	debitos.k22_numpar,
						        	debitos.k22_dtvenc
											$limite
					) as bbbbb
					on bbbbb.k22_numpre = yyyyy.k22_numpre
					$where_quant and ":"where") . "  $where_totalzao";

//
// final listadeb
//

if ($debugando == true) {
  die(trim($sql));
}


	$resultlistadeb = db_query($sql) or die($sql);
	if ($resultlistadeb == false) {
		echo "<script>alert('Ocorreu algum erro durante o processamento dos registros! Contate suporte!')</script>";
		db_redireciona("cai1_lista001.php");
		exit;
	}

    if ($opcaofiltro == 1) {
		$resultlistadeb = db_query("select * from listadeb where k61_codigo = ".$cllista->k60_codigo." limit 1");
		// 	db_criatabela($resultlistadeb);exit;
		if (pg_numrows($resultlistadeb) == 0) {
			echo "<script>alert('Não existem devedores para as opções escolhidas')</script>";
			db_redireciona("cai1_lista001.php");
			exit;
		}
    }
	if (1 == 2) {
		for ($ii = 0; $ii < pg_numrows($resultlistadeb); $ii ++) {
			db_fieldsmemory($resultlistadeb, $ii);
			$cllistadeb->k61_codigo = $cllista->k60_codigo;
			$cllistadeb->k61_numpre = $k22_numpre;
			$cllistadeb->k61_numpar = $k22_numpar;
			$cllistadeb->incluir($cllista->k60_codigo, $k22_numpre, $k22_numpar);
			if ($cllistadeb->erro_status != "0" && $erro1 == true) {
				$erro1 = true;
			} else {
				$cllistadeb->erro(true, false);
			}
		}
	}



 if (!isset($campos)){
      $opcaodeb = " # Opção: todos os tipos de débitos.";
      $resul = $cllistadeb->sql_record($cllistadeb->sql_query_tipodeb(null,"distinct(k22_tipo) as k22_tipo",null,"k22_data='$data'"));
      if ($cllistadeb->numrows != 0) {
        $numrows = $cllistadeb->numrows;
        for ($i = 0; $i < $numrows; $i ++) {
          db_fieldsmemory($resul, $i);
          $cllistatipos->k62_lista = $cllista->k60_codigo;
          $cllistatipos->k62_tipodeb = $k22_tipo;
          $cllistatipos->incluir($cllista->k60_codigo,$k22_tipo);
        }
      }
  }




	if ($erro1 == true) {

    $sqldropa="drop table w_lista_aaa";
    $resultdropa = db_query($sqldropa) or die($sqldropa);

    $sqldropa="drop table w_lista_bbb";
    $resultdropa = db_query($sqldropa) or die($sqldropa);

		db_msgbox('Processamento Concluído Com Sucesso! Lista gerada: ' . $cllista->k60_codigo);
		db_fim_transacao();
	}
}
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>


function js_sobe() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex > 0) {
    var SI = F.selectedIndex - 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
	F.options[SI + 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_desce() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
    var SI = F.selectedIndex + 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
	F.options[SI - 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_excluir() {
  var F = document.getElementById("campos");
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {
    F.options[SI] = null;
	js_trocacordeselect();
    if(SI <= (F.length - 1))
      F.options[SI].selected = true;
  }
}
function js_insSelect() {
  var texto=document.form1.k00_descr.value;
  var valor=document.form1.k00_tipo.value;
  if(texto != "" && valor != ""){
    var F = document.getElementById("campos");
    var testa = false;

    for(var x = 0; x < F.length; x++){

      if(F.options[x].value == valor || F.options[x].text == texto){
        testa = true;
	break;
      }
    }
    if(testa == false){
      F.options[F.length] = new Option(texto,valor);
      js_trocacordeselect();
    }
 }
   texto=document.form1.k00_descr.value="";
   valor=document.form1.k00_tipo.value="";
 document.form1.lanca.onclick = '';
}

function js_valor(){

  if (document.form1.quebrar.value == 'f'){
    document.getElementById('lordem3').style.visibility='visible';
  }else{
    document.getElementById('lordem3').style.visibility='hidden';
  }

}
function js_verifica(){

  var val1 = new Number(document.form1.DBtxt10.value);
  var val2 = new Number(document.form1.DBtxt11.value);

  var nomelista = document.form1.k60_descr.value;

  if (nomelista==""){
      alert('Informe a descrição.');
      return false;
  }

  if(val1.valueOf() >= val2.valueOf()){
    alert('Valor máximo menor que o valor mínimo.');
    return false;
  }

  var F = document.getElementById("campos").options;

  for(var i = 0;i < F.length;i++) {
    F[i].selected = true;
  }

  return true;

}

function js_emite(){
  itemselecionado = 0;
  numElems = document.form1.grupo.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.grupo[i].checked) itemselecionado = i;
  }
  grupo = document.form1.grupo[itemselecionado].value;


  itemselecionado = 0;
  numElems = document.form1.ordemtipo.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.ordemtipo[i].checked) itemselecionado = i;
  }
  ordemtipo = document.form1.ordemtipo[itemselecionado].value;


  itemselecionado = 0;
  numElems = document.form1.ordem.length;
  for (i=0;i<numElems;i++) {
      if (document.form1.ordem[i].checked) itemselecionado = i;
  }
  ordem = document.form1.ordem[itemselecionado].value;


  var H = document.getElementById("campos").options;
  if(H.length > 0){
     campo = 'campo=';
     virgula = '';
     for(var i = 0;i < H.length;i++) {
       campo += virgula+H[i].value;
       virgula = '-';
     }
  }else{
     campo = '';
  }

  jan = window.open('cai2_devedores_002.php?'+campo+'&massa='+document.form1.massa.value+'&loteamento='+document.form1.loteamento.value+'&ordemtipo='+ordemtipo+'&data1='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&data='+document.form1.data_ano.value+'-'+document.form1.data_mes.value+'-'+document.form1.data_dia.value+'&quebrar='+document.form1.quebrar.value+'&grupo='+grupo+'&ordem='+ordem+'&numerolista='+document.form1.numerolista2.value+'&valormaximo='+document.form1.DBtxt11.value+'&valorminimo='+document.form1.DBtxt10.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

</script>

<?



if (isset ($ordem)) {
	if (isset ($campos)) {
		$xcampo = '';
		$tamanho = sizeof($campos);
		$virgula = '';
		for ($i = 0; $i < $tamanho; $i ++) {
			$xcampo .= $virgula.$campos[$i];
			$virgula = "-";
		}
	}
?>
<script>

function js_emite1(){
  jan = window.open('cai2_devedores_002.php?<?=(isset($xcampo)?'campo='.$xcampo.'&':'')?>ordemtipo=<?=$ordemtipo?>&data=<?=$data_ano.'-'.$data_mes.'-'.$data_dia?>&quebrar='+document.form1.quebrar.value+'&grupo=<?=$grupo?>&ordem=<?=$ordem?>&numerolista=<?=$numerolista2?>&valormaximo=<?=$DBtxt11?>&valorminimo=<?=$DBtxt10?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<?


}
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="0">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
  <table  border="0" align="center">
    <form name="form1" method="post" action="" onSubmit="return js_verifica();" >
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>

<!-- > Inicio fieldset 1 <-->
<tr>
<td colspan="2">
<table width="970" border="0" align="center">
  <tr>
    <td >&nbsp;</td>
  </tr>
  <tr>
  <td>
  <fieldset>
    <Legend align="left">
    <b>Dados da Lista</b>
    </Legend>
    <table border="0" width="100%"  align="center">
    <tr><td>
    <table border="0"  width="100%" align="left">
     <tr>
        <td  width="29%" nowrap title="<?=@$Tk60_codigo?>">
          <input name="oid" type="hidden" value="<?=@$oid?>">
	        <b>Código/Descrição:</b>
        </td>
        <td colspan="3">
	        <?
          if($db_opcao == 1) {
 	          $xopcao = 3;
          } else {
	          $xopcao = $db_opcao;
          }
          db_input('k60_codigo', 10, $Ik60_codigo, true, 'text', $xopcao, "");
          db_input('k60_descr', 85, $Ik60_descr, true, 'text', $db_opcao, "");
           ?>
        </td>
      </tr>
</table>
</td>
</tr>

<tr><td>
<table border="0" width="100%" >
      <tr>
        <td title="Data da Geração da tabela débitos"><strong>Data Débitos :</strong>&nbsp;&nbsp;
        </td><td>
         <?

$sql = "select k115_data as k22_data from datadebitos where k115_instit = ".db_getsession("DB_instit")."order by k115_data desc limit 1";
$result = db_query($sql);
if (pg_numrows($result) > 0) {
	db_fieldsmemory($result, 0);
	$data_ano = substr($k22_data, 0, 4);
	$data_mes = substr($k22_data, 5, 2);
	$data_dia = substr($k22_data, 8, 2);
} else {
	$data_ano = '';
	$data_mes = '';
	$data_dia = '';
}
db_inputdata('data', $data_dia, $data_mes, $data_ano, true, 'text', 4)
?>
        </td>
        <td title="Quantidade de contribuintes a ser listado, ou zero para todos"><strong>Quantidade a Listar :</strong>&nbsp;&nbsp;
        </td><td>
          <input name="numerolista2" type="text" id="numerolista22" size="23">
        </td>

      </tr>
      <tr>
        <td title="Intervalo de valores a serem listados"><strong>Valores :</strong>&nbsp;&nbsp;
        </td><td>
          <?
           db_input('DBtxt10', 10, $IDBtxt10, true, 'text', $db_opcao);
          ?>
          &nbsp;<b> à </b> &nbsp;
          <?
          db_input('DBtxt11', 10, $IDBtxt11, true, 'text', $db_opcao);
          ?>
        </td>
        <td align="left" >
        <strong>Tipo de Lista :&nbsp;&nbsp;</strong>
        </td><td>
           <?
           $x = array("N"=>"Nome (  CGM Geral  )","C"=>"Somente por CGM","M"=>"Matrícula","I"=>"Inscrição");
           db_select('k60_tipo',$x,true,1,"");
          ?>
        </td>
      </tr>
      <tr>
        <td title="Não lista os contribuintes notificados após esta data">
	  <strong>Não Considerar Notificados Até:</strong>&nbsp;&nbsp;
    </td><td>
         <?


$data1_ano = substr(date('Y'), 0, 4);
$data1_mes = substr(date('m'),0,2);
$data1_dia =  substr(date('d'),0,2);
db_inputdata('data1', $data1_dia, $data1_mes, $data1_ano, true, 'text', 4);
$xx= array ("0" => "Geral", "1" => "Tipo de débito","2"=>"Numpre/Parcela");
    db_select('notiftipo', $xx, true, 4, "");

?>
        </td>
	<td><strong>Massa Falida :</strong>&nbsp;&nbsp;
        <?
	  $x = array ("f" => "NÃO", "t" => "SIM");
	  db_select('massa', $x, true, 4, "");
	?>
  </td><td>
	<strong>Loteamentos:</strong>&nbsp;&nbsp;
        <?
	  $x = array ("t" => "SIM", "f" => "NÃO");
	  db_select('loteamento', $x, true, 4, "");
	?>
	</td>
     </tr>

</table>
</td></tr>


  </table>
  </fieldset>
  </td>
  </tr>
  </table>
</td>
</tr>
<!-- fim primeiro fieldset 1 >  <-->
<tr>
</td>
<table border="0" width="1000" align="center">
<!-- > Inicio fieldset 2 <-->
<tr>
<td>
<table  border="0" align="center">
  <tr>
  <td>
  <fieldset>
    <Legend align="left">
    <b>Filtros</b>
    </Legend>
  <table border="0" align="center">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC" colspan="2">
	  <br>
	<center>
          <table  height="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td nowrap title="Data de operação do débito">
	    <b>Data de operação: </b>
	  </td>
	  <td>
    <?
     db_inputdata('dtini', "", "", "", true, 'text', $db_opcao, "")
    ?>
    </td><td>
	  <b> à </b>
    <?
     db_inputdata('dtfim', "", "", "", true, 'text', $db_opcao, "")
    ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Td40_codigo?>">
	    <b>Data do Vencimento: </b>
	  </td>
	  <td>
    <?
     db_inputdata('dataini', "", "", "", true, 'text', $db_opcao, "")
    ?>
    </td><td>
	  <b> à </b>
    <?
     db_inputdata('datafim', "", "", "", true, 'text', $db_opcao, "")
    ?>
     </td>
    </tr>
    <?
    ?>
     <tr>
          <td nowrap title="<?=@$Tk22_exerc?>">
	    <b>Exercicio:</b>
	  </td>
	  <td>
<?

 db_input('exercini', 10, "Exercicio inicial", true, 'text', $db_opcao);
?>
</td><td>
	  <b> à </b>
<?

 db_input('exercfim', 10, "Exercicio final", true, 'text', $db_opcao);
?>

          </td>
        </tr>

<tr>
		<td nowrap title="<?=@$Tk22_exerc?>">
	    <b>Desconsidera Exercícios:</b>
	  </td>

	  <td>
<?

 db_input('desconexercini', 10, "Exercicio inicial", true, 'text', $db_opcao);
?>
</td><td>
	  <b> à </b>
<?

 db_input('desconexercfim', 10, "Exercicio final", true, 'text', $db_opcao);
?>

		</td>



        <tr>
          <td nowrap title="<?=@$Tk22_exerc?>">
					<b>Qtde de Parcelas em Atraso:</b>
				</td>
				<td>
					<?
					 db_input('parcini', 10, "Parcela inicial", true, 'text', $db_opcao);
					?>
          </td><td>
							<b> à </b>
					<?
					 db_input('parcfim', 10, "Parcela final", true, 'text', $db_opcao);
					?>

          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tk22_exerc?>">
					<b>Número das Parcelas em Atraso:</b>
				</td>
				<td>
					<?
					 db_input('numini', 10, "Número parcela inicial", true, 'text', $db_opcao);
					?>
          </td><td>
							<b> à </b>
					<?
					 db_input('numfim', 10, "Número parcela final", true, 'text', $db_opcao);
					?>

          </td>
        </tr>

        <tr>
          <td nowrap title="<?=@$Tk22_exerc?>">
	    <b>Considerar além dos filtros:</b>
	  </td>
	  <td colspan="2">
<?

 	  $x = array ("f" => "NÃO", "t" => "SIM");
	  db_select('considerar', $x, true, 4, "");

?>

    </td>
    </tr>

  </table>
  <tr><td>&nbsp;<tr><td>
  </fieldset>
  </td>
  </tr>
  </table>

</td>
</tr>
<!-- fim primeiro fieldset 2 >  <-->

 </table>
</td><td>

<!-- > Inicio fieldset 3 <-->
<table border="0" align="center">
   <tr>
    <td nowrap title="Escolha os tipos de débitos a serem listados ou deixe em branco para listar todos" >
      <fieldset><b><Legend>Tipos de Débito</legend></b>
      <table border="0">
          <tr>
          <td colspan=2 nowrap><b>Opção::</b><select name=opcaofiltro>
               <option value=1>Os Selecionados         </option>
               <option value=2>Sem os Selecionados</option>
            </select>
        </td>
          </tr>
         <tr>
           <td nowrap title="<?=@$Tk00_tipo?>" colspan="2">
            <?

 db_ancora(@ $Lk00_tipo, "js_pesquisadb02_idparag(true);", $db_opcao);
?>
            <?


db_input('k00_tipo', 8, $Ik00_tipo, true, 'text', $db_opcao, " onchange='js_pesquisadb02_idparag(false);'")
?>
            <?

 db_input('k00_descr', 25, $Ik00_descr, true, 'text', 3, '')
?>
	    <input name="lanca" type="button" value="Lançar" >
           </td>
	 </tr>
         <tr>
	   <td align="right" colspan="" width="80%">

              <select name="campos[]" id="campos" size="7" style="width:250px" multiple>
              <?

 if (isset ($chavepesquisa)) {

	$resulta = $clarretipo->sql_record($clarretipo->sql_query($chavepesquisa, "", "k00_tipo,k00_descr", ""));
	if ($clarretipo->numrows != 0) {
		$numrows = $clarretipo->numrows;
		for ($i = 0; $i < $numrows; $i ++) {
			db_fieldsmemory($resulta, $i);
			echo "<option value=\"$k00_tipo \">$k00_descr</option>";
		}

	}

}
?>

             </select>
	   </td>
            <td align="left" valign="middle" width="20%">
 	     			 <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
              <br/><br/>
             <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
              <br/><br/>
             <img style="cursor:hand" onClick="js_excluir()" src="skins/img.php?file=Controles/bt_excluir.png" />
	   				</td>
         </tr>
      </table>
      </fieldset>
    </td>
  </tr>
<!-- fim primeiro fieldset 3 >  <-->
</td>
</tr>
</table>


      <tr height="40">
         <td align="center" colspan="2">
  	   <input name="db_opcao" type="submit" id="db_opcao"
  	          value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>"
  	          <?=($db_botao==false?"disabled":"")?> >
	 </td>
      </tr>
  </form>
    </table>

<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>

<script>

function js_vercampos(){
  if(document.form1.valores.value == ""){
    alert("escolha os tipos de valores a serem listados ");
    var x = new String('Lista Valores:');
    x = x.blink();
    document.getElementById('teste').innerHTML = x.fontcolor('red');
    tempo = setInterval("document.getElementById('teste').innerHTML='Lista Valores:'",3000)
    return false;
  }else{
    return true;
  }
return false;
}

function js_pesquisadb02_idparag(mostra){
  document.form1.lanca.onclick = "";
  parent.bstatus.document.getElementById('st').innerHTML = '<font size="2" color="darkblue"><b>Processando<blink>...</blink></b></font>' ;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?funcao_js=parent.js_mostradb_paragrafo1|k00_tipo|k00_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_arretipo','func_arretipo.php?pesquisa_chave='+document.form1.k00_tipo.value+'&funcao_js=parent.js_mostradb_paragrafo','Pesquisa',false);
  }
}
function js_mostradb_paragrafo(chave,erro){
  document.form1.k00_descr.value = chave;
  if(erro==true){
    document.form1.k00_tipo.focus();
    document.form1.k00_tipo.value = '';
  }else{
    document.form1.lanca.onclick = js_insSelect;
  }
  parent.bstatus.document.getElementById('st').innerHTML = "Configuração -> Documentos" ;

}

function js_mostradb_paragrafo1(chave1,chave2){
  document.form1.k00_tipo.value = chave1;
  document.form1.k00_descr.value = chave2;
  db_iframe_arretipo.hide();
  document.form1.lanca.onclick = js_insSelect;
}
function js_pesquisa(){
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}

</script>
<?


$func_iframe = new janela('db_iframe', '');
$func_iframe->posX = 1;
$func_iframe->posY = 20;
$func_iframe->largura = 780;
$func_iframe->altura = 430;
$func_iframe->titulo = 'Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

if (isset ($ordem)) {
	echo "<script>
	       js_emite();
	       </script>";
}

?>