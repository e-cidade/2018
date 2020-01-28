<?
/*
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

set_time_limit ( 0 );
require_once(modification("fpdf151/scpdf.php"));
require_once(modification("fpdf151/impcarne.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_layouttxt.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("classes/db_db_docparag_classe.php"));
require_once(modification("classes/db_arrematric_classe.php"));
require_once(modification("classes/db_conveniocobranca_classe.php"));
require_once(modification("classes/db_cadarrecadacao_classe.php"));
require_once(modification("classes/db_listadoc_classe.php"));
require_once(modification("classes/db_db_layouttxtgeracao_classe.php"));
require_once(modification("model/convenio.model.php"));
require_once(modification("model/regraEmissao.model.php"));
require_once(modification("model/recibo.model.php"));

parse_str ( $HTTP_SERVER_VARS ["QUERY_STRING"] );
db_postmemory ( $HTTP_POST_VARS );

$cldb_config = new cl_db_config ( );
$cldb_docparag = new cl_db_docparag ( );
$clarrematric = new cl_arrematric ( );
$cllistadoc = new cl_listadoc ( );
$clconveniocobranca = new cl_conveniocobranca ( );
$clcadarrecadacao = new cl_cadarrecadacao ( );
$cldb_layouttxtgeracao = new cl_db_layouttxtgeracao ( );

$instit = db_getsession ( "DB_instit" );
$sqlerro = false;

if ($lista == '') {
  
  $sMsg = _M('tributario.notificacoes.not2_geratxtgeral002.lista_nao_encontrada');
	db_redireciona ( "db_erros.php?fechar=true&db_erro={$sMsg}" );
	exit ();
}

$resultlistadoc = $cllistadoc->sql_record ( $cllistadoc->sql_query_file ( $lista ) );
if ($cllistadoc->numrows == 0) {
  
  $sMsg = _M('tributario.notificacoes.not2_geratxtgeral002.nenhum_documento_configurado');
	db_redireciona ("db_erros.php?fechar=true&db_erro={$sMsg}");
	exit ();
}

db_fieldsmemory ( $resultlistadoc, 0 );

$result = db_query ( "begin" ) or die ( "erro begin" );

$sqlinst = "select * from db_config where codigo = " . db_getsession ( "DB_instit" );
db_fieldsmemory ( db_query ( $sqlinst ), 0, true );
$segmentocodbar = $segmento;

$sqlvenc = "select current_date + '30 days'::interval as db_datausu";
$resultvenc = db_query ( $sqlvenc ) or die ( $sqlvenc );
db_fieldsmemory ( $resultvenc, 0 );

$DB_DATACALC = mktime ( 0, 0, 0, substr ( $db_datausu, 5, 2 ), substr ( $db_datausu, 8, 2 ), substr ( $db_datausu, 0, 4 ) );

$sql = "select * from lista where k60_codigo = $lista and k60_instit = $instit";
$result = db_query ( $sql );
db_fieldsmemory ( $result, 0 );

if ($k60_tipo != "M") {
  
  $sMsg = _M('tributario.notificacoes.not2_geratxtgeral002.emissao_preparada_lista_por_matricula');
	db_redireciona ( "db_erros.php?fechar=true&db_erro={$sMsg}" );
	exit ();
}

$sqllistatipo = "	select listatipos.*, arretipo.k03_tipo, k03_descr 
									from listatipos 
									inner join arretipo on k00_tipo = k62_tipodeb 
									inner join cadtipo on arretipo.k03_tipo = cadtipo.k03_tipo 
									where k62_lista = $lista";
$resultlistatipo = db_query ( $sqllistatipo );
$virgula = '';
$tipos = '';
$descrtipo = '';
$somentedivida = true;
$somenteparc = true;
$somenteiptu = true;
for($yy = 0; $yy < pg_numrows ( $resultlistatipo ); $yy ++) {
	db_fieldsmemory ( $resultlistatipo, $yy );
	$tipos .= $virgula . $k62_tipodeb;
	$descrtipo .= $virgula . trim ( $k03_descr );
	$virgula = ' , ';
	
	if ($k03_tipo != 6 and $k03_tipo != 13 and $k03_tipo != 16) {
		$somenteparc = false;
	}
	if ($k03_tipo != 1) {
		$somenteiptu = false;
	}
	if ($k03_tipo != 5) {
		$somentedivida = false;
	}
}

if ($k60_tipo == 'M') {
	$xtipo = 'Matrícula';
	$xcodigo = 'k22_matric';
	$xcodigo1 = 'j01_matric';
	$xxcodigo1 = 'k55_matric';
	$xcampos = ' substr(fc_proprietario_nome,1,7) as z01_numcgm, substr(fc_proprietario_nome,8,40) as z01_nome ';
	$xxmatric = ' inner join notimatric on k22_matric = k55_matric ';
	$xxmatric2 = '';
	$xxcodigo = 'k55_notifica';
} elseif ($k60_tipo == 'I') {
	$xtipo = 'Inscrição';
	$xcodigo = 'k22_inscr';
	$xcodigo1 = 'q02_inscr';
	$xxcodigo1 = 'k56_inscr';
	$xxmatric = ' inner join notiinscr on k22_inscr = k56_inscr ';
	$xxmatric2 = ' inner join issbase on q02_inscr = k22_inscr inner join cgm on z01_numcgm = q02_numcgm';
	$xxcodigo = 'k56_notifica';
	$xcampos = ' z01_numcgm, z01_nome ';
} elseif ($k60_tipo == 'N') {
	$xtipo = 'Numcgm';
	$xcodigo = 'k22_numcgm';
	$xcodigo1 = 'j01_numcgm';
	$xxcodigo1 = 'k57_numcgm';
	$xxmatric = ' inner join notinumcgm on k22_numcgm = k57_numcgm ';
	$xxmatric2 = ' inner join cgm on k22_numcgm = z01_numcgm ';
	$xxcodigo = 'k57_notifica';
	$xcampos = ' z01_numcgm, z01_nome ';
}

if ($ordem == 'a') {
	$xxordem = ' order by z01_nome ';
	$xxxordem = ' order by substr(fc_proprietario_nome,8,40)';
} elseif ($ordem == 't') {
	$xxordem = ' order by ' . $xxcodigo;
	$xxxordem = ' order by notifica';
} else {
	$xxordem = ' order by ' . $xxcodigo1;
	$xxxordem = ' order by ' . $xxcodigo1;
}

$sWhere = " and exists ( select 1 from arrecad where k00_numpre = k61_numpre and k00_numpar = k61_numpar limit 1) ";
if ($k60_tipo == 'M') {
	$sqlanos = "select min(v01_exerc), max(v01_exerc)
					from listadeb
					inner join lista on k61_codigo = k60_codigo  and k60_instit = $instit
					inner join divida on k61_numpre = v01_numpre and k61_numpar = v01_numpar
					inner join arrematric on k61_numpre = arrematric.k00_numpre
					where k61_codigo = $lista $sWhere";
	$sql = "select * from listadeb 
					inner join lista on k61_codigo = k60_codigo and k60_instit = $instit
					inner join arrematric on k61_numpre = arrematric.k00_numpre
					where k61_codigo = $lista $sWhere
					order by k00_matric, k61_numpre, k61_numpar";
	
	$resultlistadeb = db_query ( $sql ) or die ( $sql );
	if (pg_numrows ( $resultlistadeb ) == 0) {
	  
	  $oParms = new stdClass();
	  $oParms->sLista = $lista;
	  $sMsg = _M('tributario.notificacoes.not2_geratxtgeral002.nenhuma_notificacao_gerada', $oParms);
		db_redireciona ( "db_erros.php?fechar=true&db_erro={$sMsg}" );
		exit ();
	}
	$resultlistaanos = db_query ( $sqlanos ) or die ( $sqlanos );
	db_fieldsmemory ( $resultlistaanos, 0 );
	
	$anos = $min . "/" . $max;

}

$complementa_nome_arquivo = "_" . ( $tipo == "f"?"producao":"teste" ) . "_base_" . db_getsession("DB_NBASE");

$nomearq = "tmp/emissaogeral_" . str_replace ( "-", "", date ( "Y-m-d", db_getsession ( "DB_datausu" ) ) ) . "_" . str_replace ( ":", "", db_hora () ) . $complementa_nome_arquivo . ".txt";

if ($modelo == 1) {
	$db55_layouttxt = 10;
} elseif ($modelo == 2) {
	$db55_layouttxt = 13;
}

if ($modelo == 1) {
	$cldb_layouttxr = new db_layouttxt ( $db55_layouttxt, $nomearq, "01 02 03 04 05 11 99" );
} elseif ($modelo == 2) {
	$cldb_layouttxr = new db_layouttxt ( $db55_layouttxt, $nomearq, "A B C D E F G K L S T U V W X Y Z" );
} else {
  
	db_msgbox ( _M('tributario.notificacoes.not2_geratxtgeral002.modelo_nao_configurado') );
	exit ();
}

$db55_obs = "LISTA: $lista\n";

db_sel_instit ( null, "nomeinst, munic, uf, ender, cgc, cep, bairro, segmento" );

$sql = "select k62_tipodeb,tipoparc.*, cadtipoparc.*, cadtipoparc as desconto
					from cadtipoparc 
					inner join tipoparc on cadtipoparc = k40_codigo 
					inner join cadtipoparcdeb on k41_cadtipoparc = k40_codigo 
					inner join listatipos on k62_tipodeb = k41_arretipo 
				where maxparc = 1 and cadtipoparc.k40_dtfim > current_date and
							k62_lista = $lista
							order by k40_dtini";

$resulttipoparc = db_query ( $sql ) or die ( $sql );

if (pg_numrows ( $resulttipoparc ) == 0) {
  
  $sMsg = _M('tributario.notificacoes.not2_geratxtgeral002.nenhum_desconto_configurado');
	db_redireciona ( "db_erros.php?fechar=true&db_erro={$sMsg}" );
	exit ();
}

$desconto = pg_result ( $resulttipoparc, pg_numrows ( $resulttipoparc ) - 1, "cadtipoparc" );
$maxvenc  = pg_result ( $resulttipoparc, pg_numrows ( $resulttipoparc ) - 1, "k40_dtfim" );

$resultgeracao = $cldb_layouttxtgeracao->sql_record ( $cldb_layouttxtgeracao->sql_query_file ( null, "max(db55_seqlayout) as db55_seqlayout", null, " db55_layouttxt = $db55_layouttxt group by db55_layouttxt" ) );
if ($cldb_layouttxtgeracao->numrows == 0) {
	$db55_seqlayout = 0;
} else {
	db_fieldsmemory ( $resultgeracao, 0 );
}
$db55_seqlayout ++;

try {
	$oRegraEmissao = new regraEmissao ( $k62_tipodeb, 11, db_getsession ( 'DB_instit' ), date ( "Y-m-d", db_getsession ( "DB_datausu" ) ), db_getsession ( 'DB_ip' ) );
} catch ( Exception $eExeption ) {
	db_redireciona ( "db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}" );
	exit ();
}

if ($modelo == 1) {
	
	if ($tipo == "f") {
		$identificador = "CBR454"; // acertar para CBR454
	} else {
		$identificador = "TST454"; // acertar para CBR454
	}
	
	$db55_obs .= "IDENTIFICADOR: $identificador\n";
	$db55_obs .= "QUANTIDADE DE REGISTROS A PROCESSAR: $quantidade\n";
	$convenio7posicoes = 0;

} elseif ($modelo == 2) {
	
	$rsDadosConvenioCobranca = $clconveniocobranca->sql_record ( $clconveniocobranca->sql_query ( $oRegraEmissao->getCodConvenioCobranca () ) );
	$oConvenioCobranca = db_utils::fieldsMemory ( $rsDadosConvenioCobranca, 0 );
	$numeroconvenio = $oConvenioCobranca->ar13_convenio;
	
	$rsDadosArrecadacao = $clcadarrecadacao->sql_record ( $clcadarrecadacao->sql_query_file ( null, 'ar16_convenio', null, ' ar16_instit = ' . db_getsession ( 'DB_instit' ) ) );
	
	if (!$oRegraEmissao->isArrecadacao()) {
	  
	  $sMsg = _M('tributario.notificacoes.not2_geratxtgeral002.opcao_valida_somente_convenios_arrecadacao');
	  db_redireciona ("db_erros.php?fechar=true&db_erro={$sMsg}");
    exit ();
	}
	if ($rsDadosArrecadacao && $clcadarrecadacao->numrows > 0) {
		$oDadosArrecadacao = db_utils::fieldsMemory ( $rsDadosArrecadacao, 0 );
		$identfebraban = $oDadosArrecadacao->ar16_convenio;
	} else {
	  
	  $sMsg = _M('tributario.notificacoes.not2_geratxtgeral002.convenio_arrecadacao_nao_cadastrado');
		db_redireciona ( "db_erros.php?fechar=true&db_erro={$sMsg}" );
		exit ();
	}
	
	$numeroremessa = $db55_seqlayout;
	$datageracao = date ( "Y-m-d", db_getsession ( "DB_datausu" ) );
	$tipoformulario = 2;
	$indicadorpostagem = "S";
	$cnpjconvenente = $cgc;
	$nomeconvenente = $nomeinst;
	$enderecoconvenente = $ender;
	$cepconvenente = $cep;
	$cidadeconvenente = $munic;
	$bairroconvenente = $bairro;
	$ufconvenente = $uf;
	
	$segmentofebraban = $segmento;
	$moedaconvenio = 6;
	$casasdecimais = 2;
	$autorizarecaposvcto = "S";
	
	if ($formvencfebraban == 1 || $oRegraEmissao->isCobranca ()) {
		$formatovcto = 4;
	} elseif ($formvencfebraban == 2) {
		$formatovcto = 2;
	} else {
		$formatovcto = 0;
	}
	
	$denominacaocontrib = "CONTRIBUINTE";
	
	if ($k60_tipo == 'M') {
		$siglacontrib = "MATRICULA";
		$identificacaoobj = "MATRICULA";
		$significadosigla = "MATRICULA=MATRICULA DO CADASTRO IMOBILIARIO";
	} elseif ($k60_tipo == 'I') {
		$siglacontrib = "INSCRICAO";
		$identificacaoobj = "INSCRICAO";
		$significadosigla = "INSCRICAO=INSCRICAO DO CADASTRO MOBILIARIO";
	} elseif ($k60_tipo == 'C') {
		$siglacontrib = "CGM";
		$identificacaoobj = "CGM";
		$significadosigla = "CGM=CADASTRO GERAL DO MUNICIPIO";
	} else {
		$siglacontrib = "SIGLAXXX";
		$identificacaoobj = "SIGLAXXX";
		$significadosigla = "SIGLAXXX=SIGLA NAO DETERMINADA";
	}
	$tipoidentificador = "N"; // numerico
	$tamanhoidentificador = 17;
	$mascaraidentificador = str_repeat ( "9", $tamanhoidentificador );
	$denominacaotransm = ""; // nao utilizado
	

	if ($somentedivida == true) {
		$nomerecebimento = "DIVIDA ATIVA";
		$siglarecebimento = "DIV ATIVA";
	} elseif ($somenteparc == true) {
		$nomerecebimento = "PARCELAMENTO DE DIVIDA";
		$siglarecebimento = "PARC DIV";
	} elseif ($somenteiptu == true) {
		$nomerecebimento = "IMPOSTO PREDIAL E TERRITORIAL URBANO";
		$siglarecebimento = "IPTU";
	} else {
		$nomerecebimento = "TIPO DE DEBITO NAO IDENTIFICADO";
		$siglarecebimento = "NAO IDENTIF";
	}
	
	$resultparag = $cldb_docparag->sql_record ( $cldb_docparag->sql_query_doc ( null, null, "db02_texto", null, " db03_tipodoc = 1017" ) );
	$unidadeconvenente = "";
	if ($cldb_docparag->numrows > 0) {
		db_fieldsmemory ( $resultparag, 0 );
		$unidadeconvenente = $db02_texto;
	}
	
	$denominacaoexercicio = $anos;
	$exercicio = $anos;
	$totalopcoespagamento = pg_numrows ( $resulttipoparc );
	$parcelamentoreceb = pg_numrows ( $resulttipoparc );
	$valorminimo = 0;
	$juros = 0;
	$quantidadedatas = 0;
	$numerodiasentredatas = 0;
	$titulocomposicao = "COMPOSICAO ATE " . db_formatar ( $k60_datadeb, "d" );
	$formacalculo = "";
	$identificacaobarra = "P";
	$denominacaoidentif = "";
	$zerarvalorbarra = "N";
	$prazoexpurgo = 0;
	$checardatabarras = "S";
	$checarvalorbarras = "S";
	
	if ($tipo == "f") {
		$tiporemessa = 2; // producao
	} else {
		$tiporemessa = 1; // teste
	}

}

global $quantidadegeral, $sequencialregistro;
$quantidadegeral = 1;
$sequencialregistro = 1;

if ($modelo == 1) {
	
	$processados = 0;
	
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "01" );
	
	$textotipo02 = "";
	$textotipo03 = "";
	$textotipo04 = "";
	$textotipo05 = "";
	
	$resultparag = $cldb_docparag->sql_record ( $cldb_docparag->sql_query ( $k64_docum ) );
	for($parag = 0; $parag < $cldb_docparag->numrows; $parag ++) {
		db_fieldsmemory ( $resultparag, $parag );
		
		if ($db02_descr == "TIPO02") {
			$textotipo02 = db_geratexto ( $db02_texto );
		} elseif ($db02_descr == "TIPO03") {
			$textotipo03 = db_geratexto ( $db02_texto );
		} elseif ($db02_descr == "TIPO04") {
			$textotipo04 = db_geratexto ( $db02_texto );
		} elseif ($db02_descr == "TIPO05") {
			$textotipo05 = db_geratexto ( $db02_texto );
		}
	
	}
	
	db_separainstrucao ( $textotipo02, 0, $cldb_layouttxr, 1, "02", 4, $quantidadegeral );
	db_separainstrucao ( $textotipo03, 0, $cldb_layouttxr, 1, "03", 3, $quantidadegeral );
	
	global $matricatual;
	
	$matricatual = pg_result ( $resultlistadeb, 0, "k00_matric" );
	db_preparageratxt ( $lista );
	
	for($x = 0; $x < pg_numrows ( $resultlistadeb ); $x ++) {
		
		db_fieldsmemory ( $resultlistadeb, $x );
		
		for($parag = 0; $parag < $cldb_docparag->numrows; $parag ++) {
			
			db_fieldsmemory ( $resultparag, $parag );
			
			if ($db02_descr == "TIPO02") {
				$textotipo02 = $db02_texto;
			} elseif ($db02_descr == "TIPO03") {
				$textotipo03 = $db02_texto;
			} elseif ($db02_descr == "TIPO04") {
				$textotipo04 = $db02_texto;
			} elseif ($db02_descr == "TIPO05") {
				$textotipo05 = $db02_texto;
			}
		
		}

		
		if ( $k00_matric != $matricatual or ($x == pg_numrows ( $resultlistadeb ) - 1)) {

			$resultarrematric = $clarrematric->sql_record ( $clarrematric->sql_query ( null, $k00_matric, "*" ) );
			db_fieldsmemory ( $resultarrematric, 0 );
			
			$cnpjcpf = $z01_cgccpf;
			
			if (strlen ( $z01_cgccpf ) == 11) {
				$tipodocumento = 1;
			} elseif (strlen ( $z01_cgccpf ) == 14) {
				$tipodocumento = 2;
			} else {
				$tipodocumento = 3;
				$cnpjcpf = "";
			}
			
			$nomesacado = $z01_nome;
			$enderecosacado = trim ( $z01_ender ) . (trim ( $z01_numero ) != "" ? ", " . $z01_numero : "") . (trim ( $z01_compl ) != "" ? "/" . $z01_compl : "");
			$cepsacado = $z01_cep;
			$pracasacado = $z01_munic;
			$ufsacado = $z01_uf;
			$dataemissao = date ( "dmy", db_getsession ( "DB_datausu" ) );
			$vencimento = substr ( $maxvenc, 8, 2 ) . substr ( $maxvenc, 5, 2 ) . substr ( $maxvenc, 2, 2 );
			$aceite = "N";
			$especietitulo = "DP";
			$nossonumero = $k03_numpre;
			$titulo = "";
			$tipomoeda = "09";
			$quantidademoeda = 0;
			$valor = 0;
			$prazoprotesto = 0;
			$totalparcelas = 0;
			
			if ($x == pg_numrows ( $resultlistadeb ) - 1) {
				$aNumpres [] = $k61_numpre . "#" . $k61_numpar;
			}
			
			try {
				$oRecibo = new recibo ( 2, null, 22 );
			} catch ( Exception $eException ) {
				db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
				exit ();
			}
			
			for($numpre = 0; $numpre < sizeof ( $aNumpres ); $numpre ++) {
				
				$valores = split ( "#", $aNumpres [$numpre] );
				try {
					
					$oRecibo->addNumpre ( $valores [0], $valores [1] );
					$oRecibo->setDescontoReciboWeb ( $valores [0], $valores [1], $desconto );
				} catch ( Exception $eException ) {
					db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
					exit ();
				}
			
			}
			
			db_inicio_transacao ();
			try {
				$oRecibo->setNumBco ( 0 );
				$oRecibo->setDataRecibo ($maxvenc);
				$oRecibo->setDataVencimentoRecibo ( db_vencimento () );
				$oRecibo->emiteRecibo ();
				$k03_numpre = $oRecibo->getNumpreRecibo ();
			} catch ( Exception $eException ) {
				db_fim_transacao ( true );
				db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
				exit ();
			}
			
			db_fim_transacao ();
			
			$sqltotal = "select fc_calcula(k22_numpre,k22_numpar,0,'" . date ( "Y-m-d", db_getsession ( "DB_datausu" ) ) . "','" . date ( "Y-m-d", db_getsession ( "DB_datausu" ) ) . "'," . db_getsession ( "DB_anousu" ) . ")
									from listadeb
									inner join lista on k61_codigo = k60_codigo
									inner join debitos on k22_numpre = k61_numpre and
														  k22_numpar = k61_numpar and
														  k22_data   = '$k60_datadeb' and
                              k22_instit = $instit
									where k22_matric = $matricatual and k61_codigo = $lista $sWhere";
			
			$resulttotal = db_query ( $sqltotal ) or die ( $sqltotal );
			db_fieldsmemory ( $resulttotal, 0 );
			
			$k22_vlrcor = 0;
			$k22_encargos = 0;
			for($contax = 0; $contax < pg_numrows ( $resulttotal ); $contax ++) {
				db_fieldsmemory ( $resulttotal, $contax );
				$k22_vlrcor += ( float ) substr ( $fc_calcula, 14, 13 );
				$k22_encargos += ( float ) substr ( $fc_calcula, 27, 13 ) + ( float ) substr ( $fc_calcula, 40, 13 );
			}
			
			global $registrooriginal;
			$registrooriginal = "VALOR CORRIGIDO: " . trim ( db_formatar ( $k22_vlrcor, 'f' ) ) . " - ENCARGOS: " . trim ( db_formatar ( $k22_encargos, 'f' ) ) . " - TOTAL GERAL: " . trim ( db_formatar ( $k22_vlrcor + $k22_encargos, 'f' ) );
			
			$instrucao1 = "";
			$instrucao2 = "";
			$instrucao3 = "";
			$instrucao4 = "";
			
			$opcoesdepagamento = "";
			for($contador = 0; $contador < pg_numrows ( $resulttipoparc ); $contador ++) {
				
				db_fieldsmemory ( $resulttipoparc, $contador );
				
				try {
					$oRecibo = new recibo ( 2, null, 22 );
				} catch ( Exception $eException ) {
					db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
					exit ();
				}
				
				for($numpre = 0; $numpre < sizeof ( $aNumpres ); $numpre ++) {
					
					$valores = split ( "#", $aNumpres [$numpre] );
					try {
						$oRecibo->addNumpre ( $valores [0], $valores [1] );
						$oRecibo->setDescontoReciboWeb ( $valores [0], $valores [1], $desconto ); // alterado robson
					} catch ( Exception $eException ) {
						db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
						exit ();
					}
				
				}
				
				db_inicio_transacao ();
				
				try {
					$oRecibo->setDataRecibo ( $k40_dtfim );
					$oRecibo->setDataVencimentoRecibo ( db_vencimento () );
					$oRecibo->emiteRecibo ();
					$k03_numpre_calcula = $oRecibo->getNumpreRecibo ();
				} catch ( Exception $eException ) {
					db_fim_transacao ( true );
					db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
					exit ();
				}
				
				db_fim_transacao ();
				
				$sql = "select sum(k00_valor) as k00_valor from recibopaga where k00_numnov = $k03_numpre_calcula";
				$resultrecibo = db_query ( $sql ) or die ( $sql );
				db_fieldsmemory ( $resultrecibo, 0 );
				
				$nomevar = "instrucao" . ($contador + 1);
				$$nomevar = "PAGAMENTO ATE " . db_formatar ( $k40_dtfim, "d" ) . ": R$ " . db_formatar ( $k00_valor, "f", " ", 10 );
				$opcoesdepagamento .= $$nomevar . ($contador == pg_numrows ( $resulttipoparc ) - 1 ? "" : "|");
			}
			
			db_separainstrucao ( $textotipo04, 0, $cldb_layouttxr, 1, "04", 3, $quantidadegeral );
			
			db_separainstrucao ( $textotipo05, 0, $cldb_layouttxr, 3, "05", 4, $quantidadegeral );
			
			$quantidadegeral ++;
			db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 3, "11" );
			
			db_preparageratxt ( $lista );
			
			$aNumpres [] = $k61_numpre . "#" . $k61_numpar;
			
			$processados ++;
			
			if (( int ) $quantidade > 0) {
				
				if ($processados == $quantidade) {
					break;
				}
			
			}
		
		} else {
			$aNumpres [] = $k61_numpre . "#" . $k61_numpar;
		}
		
		$matricatual = $k00_matric;
	
	}
	
	$quantidaderegistros = $quantidadegeral;
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 5, "99" );

} elseif ($modelo == 2) {
	
	$identificacao = "0000";
	$segmento = "A";
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "A" );
	$sequencialregistro ++;
	
	$identificacao = "0000";
	$segmento = "B";
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 2, "B" );
	$sequencialregistro ++;
	
	$identificacao = "0000";
	$segmento = "C";
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "C" );
	$sequencialregistro ++;
	
	$identificacao = "0000";
	$segmento = "D";
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "D" );
	$sequencialregistro ++;
	
	$identificacao = "0000";
	$segmento = "E";
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "E" );
	$sequencialregistro ++;
	
	// segmento F
	if ($somentedivida == true) {
		$denominacaoobj = "DIVIDA ATIVA";
		$siglaidentobj = "DIV ATIVA";
		$significadoidentobj = "DIVIDA ATIVA";
	} elseif ($somenteparc == true) {
		$denominacaoobj = "PARC DIVIDA";
		$siglaidentobj = "PARC DIV";
		$significadoidentobj = "PARCELAMENTO DE DIVIDA";
	} elseif ($somenteiptu == true) {
		$denominacaoobj = "IPTU";
		$siglaidentobj = "IPTU";
		$significadoidentobj = "IMPOSTO PREDIAL E TERRITORIAL URBANO";
	} else {
		$denominacaoobj = "NAO IDENTIF";
		$siglaidentobj = "NAO IDENT";
		$significadoidentobj = "SIGNIFICADO NAO IDENTIFICADO";
	}
	
	$identificacao = "0000";
	$tipoident = "N";
	$tamanhoidentobj = 17;
	$mascaraidentobj = str_repeat ( "9", $tamanhoidentobj );
	$titulocaractobj = "EXERCICIOS ENVOLVIDOS";
	$segmento = "F";
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "F" );
	$sequencialregistro ++;
	
	$identificacao = "0001";
	$numerocaractobj = 1;
	$denominacaocaractobj = "INICIAL";
	$segmento = "G";
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "G" );
	$sequencialregistro ++;
	
	$identificacao = "0002";
	$numerocaractobj = 2;
	$denominacaocaractobj = "FINAL";
	$segmento = "G";
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "G" );
	$sequencialregistro ++;
	
	// primeira composicao
	$identificacao = "0001";
	$numeroreceita = 1;
	$codigoreceita = 1;
	$descricaoreceita = "VALOR CORRIGIDO";
	$valorpercentualaliq = 0;
	$segmento = "K";
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "K" );
	$sequencialregistro ++;
	
	// segunda composicao
	$identificacao = "0002";
	$numeroreceita = 2;
	$codigoreceita = 1;
	$descricaoreceita = "ENCARGOS";
	$valorpercentualaliq = 0;
	$segmento = "K";
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "K" );
	$sequencialregistro ++;
	
	// segmento L
	for($contador = 0; $contador < pg_numrows ( $resulttipoparc ); $contador ++) {
		db_fieldsmemory ( $resulttipoparc, $contador );
		
		$identificacao = $contador + 1;
		$denominacaopag = "DESCONTO: " . trim ( db_formatar ( $descmul, "s" ) ) . "%";
		$compldenominacaopag = "NOS ENCARGOS";
		$tipopag = "P";
		$numeropag = $contador + 1;
		$vencimentopag = $k40_dtfim;
		$incidenciapag = $anos;
		$descontoconcedpag = 0;
		$segmento = "L";
		db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 1, "L" );
		$sequencialregistro ++;
	
	}
	
	global $matricatual;
	$matricatual = pg_result ( $resultlistadeb, 0, "k00_matric" );
	db_preparageratxt ( $lista );
	
	$processados = 1;
	for($x = 0; $x < pg_numrows ( $resultlistadeb ); $x ++) {
		db_fieldsmemory ( $resultlistadeb, $x );
		
		if ($k00_matric != $matricatual or ($x == pg_numrows ( $resultlistadeb ) - 1)) {
			
			if ($x == pg_numrows ( $resultlistadeb ) - 1) {
				$aNumpres [] = $k61_numpre . "#" . $k61_numpar;
			}
			
			try {
				$oRecibo = new recibo ( 2, null, 22 );
			} catch ( Exception $eException ) {
				db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
				exit ();
			}
			
			for($numpre = 0; $numpre < sizeof ( $aNumpres ); $numpre ++) {
				
				$valores = split ( "#", $aNumpres [$numpre] );
				try {
					
					$oRecibo->addNumpre ( $valores [0], $valores [1] );
					$oRecibo->setDescontoReciboWeb ( $valores [0], $valores [1], $desconto );
				} catch ( Exception $eException ) {
					db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
					exit ();
				}
			
			}
			
			db_inicio_transacao ();
			
			try {
				$oRecibo->setDataRecibo($maxvenc);
				$oRecibo->setDataVencimentoRecibo ( db_vencimento () );
				$oRecibo->emiteRecibo ();
				$k03_numpre = $oRecibo->getNumpreRecibo ();
			} catch ( Exception $eException ) {
				db_fim_transacao ( true );
				db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
				exit ();
			}
			
			db_fim_transacao ();
			
			$sqltotal = "select fc_calcula(k22_numpre,k22_numpar,0,'{$k60_datadeb}','{$k60_datadeb}'," . db_getsession ( "DB_anousu" ) . ")
									from listadeb
									inner join lista on k61_codigo = k60_codigo
									inner join debitos on k22_numpre = k61_numpre and
																				k22_numpar = k61_numpar and
																				k22_data = '$k60_datadeb' and
                                        k22_instit = $instit
									where k22_matric = $matricatual and k61_codigo = $lista $sWhere";
			$resulttotal = db_query ( $sqltotal ) or die ( $sqltotal );
			db_fieldsmemory ( $resulttotal, 0 );
			
			$sqltotalanos = "select min(k22_exerc) as k22_exerc_ini, max(k22_exerc) as k22_exerc_fim
									from listadeb
									inner join lista on k61_codigo = k60_codigo
									inner join debitos on k22_numpre = k61_numpre and
																				k22_numpar = k61_numpar and
																				k22_data = '$k60_datadeb' and 
                                        k22_instit = $instit
									where k22_matric = $matricatual and k61_codigo = $lista $sWhere";
			$resulttotalanos = db_query ( $sqltotalanos ) or die ( $sqltotalanos );
			db_fieldsmemory ( $resulttotalanos, 0 );
			
			$k22_vlrcor = 0;
			$k22_encargos = 0;
			for($contax = 0; $contax < pg_numrows ( $resulttotal ); $contax ++) {
				db_fieldsmemory ( $resulttotal, $contax );
				$k22_vlrcor += ( float ) substr ( $fc_calcula, 14, 13 );
				$k22_encargos += ( float ) substr ( $fc_calcula, 27, 13 ) + ( float ) substr ( $fc_calcula, 40, 13 );
			}
			
			global $registrooriginal;
			$registrooriginal = "VALOR CORRIGIDO: " . trim ( db_formatar ( $k22_vlrcor, 'f' ) ) . " - ENCARGOS: " . trim ( db_formatar ( $k22_encargos, 'f' ) ) . " - TOTAL GERAL: " . trim ( db_formatar ( $k22_vlrcor + $k22_encargos, 'f' ) );
			
			$identificacaoguia = $processados;
			$emissaoguia = date ( "Y-m-d", db_getsession ( "DB_datausu" ) );
			$validadeguia = $maxvenc;
			$totalopcoespag = pg_numrows ( $resulttipoparc );
			$parcelasrecebimento = pg_numrows ( $resulttipoparc );
			$valortotalreceb = $k22_vlrcor + $k22_encargos;
			$indicadorendcorresp = "C";
			$codatividadecontrib = "";
			$descratividcontrib = "";
			
			$resultarrematric = $clarrematric->sql_record ( $clarrematric->sql_query ( null, $matricatual, "iptubase.*, cgm.*" ) );
			db_fieldsmemory ( $resultarrematric, 0 );
			$cnpjcpf = $z01_cgccpf;
			if (strlen ( $z01_cgccpf ) == 11) {
				$tipopessoatransmit = 1;
			} elseif (strlen ( $z01_cgccpf ) == 14) {
				$tipopessoatransmit = 2;
			} else {
				$tipopessoatransmit = 0;
				$cnpjcpf = "";
			}
			
			$identtransmreceita = $z01_cgccpf;
			$nometransmitente = "";
			$nomecartorio = "";
			$identificacao = "0000";
			$segmento = "S";
			db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 3, "S" );
			$sequencialregistro ++;
			
			$nomecontrib = $z01_nome;
			
			if ($k60_tipo == 'M') {
				$identificacaocontrib = $matricatual;
			} elseif ($k60_tipo == 'I') {
				$identificacaocontrib = $inscratual;
			} elseif ($k60_tipo == 'C') {
				$identificacaocontrib = $cgmatual;
			} else {
				$identificacaocontrib = 0;
			}
			
			$tipopessoa = $tipopessoatransmit;
			$identcontribreceita = $cnpjcpf;
			$enderecocontrib = db_translate ( trim ( $z01_ender ) . (trim ( $z01_numero ) != "" ? ", " . $z01_numero : "") . (trim ( $z01_compl ) != "" ? "/" . $z01_compl : "") );
			$cepcontrib = $z01_cep;
			$cidadecontrib = db_translate ( $z01_munic );
			$bairrocontrib = db_translate ( trim ( $z01_munic ) . (strlen ( trim ( $z01_bairro ) ) == 0 ? "" : "/B: " . trim ( $z01_bairro )) );
			$ufcontrib = db_translate ( $z01_uf );
			$codativcontrib = 0;
			$cod2ativcontrib = 0;
			$cod3ativcontrib = 0;
			$identificacao = "0000";
			$segmento = "T";
			db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 3, "T" );
			$sequencialregistro ++;
			
			// segmento U //
			$identificacao = "0000";
			
			$valor1rec = $k22_vlrcor;
			$aliq1rec = 0;
			
			$valor2rec = $k22_encargos;
			$aliq2rec = 0;
			
			$segmento = "U";
			db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 3, "U" );
			$sequencialregistro ++;
			
			$segmento = "V";
			db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 3, "V" );
			$sequencialregistro ++;
			
			$identificacao = "0000";
			$conteudo1caract = $k22_exerc_ini;
			$conteudo2caract = $k22_exerc_fim;
			$segmento = "W";
			db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 3, "W" );
			$sequencialregistro ++;
			
			$mensagem1guia = "";
			$mensagem2guia = "";
			$mensagem3guia = "";
			
			$resultparag = $cldb_docparag->sql_record ( $cldb_docparag->sql_query ( $k64_docum ) );
			
			$segmento = "X";
			$identificacao = 1;
			for($parag = 0; $parag < $cldb_docparag->numrows; $parag ++) {
				db_fieldsmemory ( $resultparag, $parag );
				
				if ($db02_descr == "MENSAGEM1") {
					db_separainstrucao ( $db02_texto, 0, $cldb_layouttxr, 3, "X", 4, 0 );
					$sequencialregistro ++;
					$identificacao ++;
				} elseif ($db02_descr == "MENSAGEM2") {
					db_separainstrucao ( $db02_texto, 0, $cldb_layouttxr, 3, "X", 4, 0 );
					$sequencialregistro ++;
					$identificacao ++;
				} elseif ($db02_descr == "MENSAGEM3") {
					db_separainstrucao ( $db02_texto, 0, $cldb_layouttxr, 3, "X", 4, 0 );
					$sequencialregistro ++;
					$identificacao ++;
				}
			
			}
			
			for($contador2 = 0; $contador2 < pg_numrows ( $resulttipoparc ); $contador2 ++) {
				
				db_fieldsmemory ( $resulttipoparc, $contador2 );
				
				try {
					$oRecibo = new recibo ( 2, null, 22 );
				} catch ( Exception $eException ) {
					db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
					exit ();
				}
				
				for($numpre = 0; $numpre < sizeof ( $aNumpres ); $numpre ++) {
					
					$valores = split ( "#", $aNumpres [$numpre] );
					try {
						$oRecibo->addNumpre ( $valores [0], $valores [1] );
						$oRecibo->setDescontoReciboWeb ( $valores [0], $valores [1], $desconto );
					} catch ( Exception $eException ) {
						db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
						exit ();
					}
				
				}
				
				db_inicio_transacao ();
				
				try {
					$oRecibo->setDataRecibo ( $k40_dtfim );
					$oRecibo->setDataVencimentoRecibo ( $k40_dtfim );
					$oRecibo->emiteRecibo ();
					$k03_numpre_calcula = $oRecibo->getNumpreRecibo ();
				} catch ( Exception $eException ) {
					db_fim_transacao ( true );
					db_redireciona ( "db_erros.php?fechar=true&db_erro={$eException->getMessage()}" );
					exit ();
				}
				
				db_fim_transacao ();
				
				$sql = "select sum(k00_valor) as k00_valor from recibopaga where k00_numnov = $k03_numpre_calcula";
				$resultrecibo = db_query ( $sql ) or die ( $sql );
				db_fieldsmemory ( $resultrecibo, 0 );
				
				$identificacao = $processados;
				$tipopag = "P";
				$numeropag = $contador2 + 1;
				$vencimentopag = $k40_dtfim;
				$valorpag = $k00_valor;
				
				// codigo de barras
				

				$db_numpre = $k03_numpre_calcula;
				$db_vlrbar = db_formatar ( str_replace ( '.', '', str_pad ( number_format ( $valorpag, 2, "", "." ), 11, "0", STR_PAD_LEFT ) ), 's', '0', 11, 'e' );
				$datavencimento = $k40_dtfim;
				
				$sqlvalor = "select k00_tercdigrecnormal 
			    			   from arretipo 
 									inner join listatipos on k00_tipo = k62_tipodeb
							  where k62_lista = $lista
							  limit 1";
				
				$resultvalor = db_query ( $sqlvalor ) or die ( $sqlvalor );
				db_fieldsmemory ( $resultvalor, 0 );
				if (! isset ( $k00_tercdigrecnormal ) || $k00_tercdigrecnormal == "") {
					
				  $sMsg = _M('tributario.notificacoes.not2_geratxtgeral002.configure_terceiro_digito');
					db_redireciona ( "db_erros.php?fechar=true&db_erro={$sMsg}" );
				}
				
				try {
					$oConvenio = new convenio ( $oRegraEmissao->getConvenio (), $db_numpre, 0, $valorpag, $db_vlrbar, $datavencimento, $k00_tercdigrecnormal );
				} catch ( Exception $eExeption ) {
					db_redireciona ( "db_erros.php?fechar=true&db_erro={$eExeption->getMessage()}" );
					exit ();
				}
				
				$codigobarras = $oConvenio->getCodigoBarra ();
				$linhadigitavel = $oConvenio->getLinhaDigitavel ();
				
				$matrizbarras = split ( " ", $linhadigitavel );
				$barras = $matrizbarras [0] . $matrizbarras [1] . $matrizbarras [2] . $matrizbarras [3];
				
				$parte1codbarraspag = substr ( $barras, 00, 11 );
				$digitoverifparte1 = substr ( $barras, 11, 01 );
				$parte2codbarraspag = substr ( $barras, 12, 11 );
				$digitoverifparte2 = substr ( $barras, 23, 01 );
				$parte3codbarraspag = substr ( $barras, 24, 11 );
				$digitoverifparte3 = substr ( $barras, 35, 01 );
				$parte4codbarraspag = substr ( $barras, 36, 11 );
				$digitoverifparte4 = substr ( $barras, 47, 01 );
				
				$identificacao = $contador2 + 1;
				$segmento = "Y";
				
				db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 3, "Y" );
				$sequencialregistro ++;
			
			}
			
			db_preparageratxt ( $lista );
			
			$aNumpres [] = $k61_numpre . "#" . $k61_numpar;
			
			$processados ++;
			
			if (( int ) $quantidade > 0) {
				
				if ($processados == $quantidade) {
					break;
				}
			
			}
		
		} else {
			$aNumpres [] = $k61_numpre . "#" . $k61_numpar;
		}
		
		$matricatual = $k00_matric;
	
	}
	
	$quantidaderegistros = $sequencialregistro;
	$identificacao = "9999";
	$segmento = "Z";
	
	db_setaPropriedadesLayoutTxt ( $cldb_layouttxr, 5, "Z" );

}

$gravarconteudo = file ( $nomearq );
$gravarconteudo = implode ( $gravarconteudo );

$cldb_layouttxtgeracao->db55_layouttxt = $db55_layouttxt;
$cldb_layouttxtgeracao->db55_seqlayout = $db55_seqlayout;
$cldb_layouttxtgeracao->db55_data = date ( "Y-m-d", db_getsession ( "DB_datausu" ) );
$cldb_layouttxtgeracao->db55_hora = db_hora ();
$cldb_layouttxtgeracao->db55_usuario = db_getsession ( "DB_id_usuario" );
$cldb_layouttxtgeracao->db55_nomearq = $nomearq;
$cldb_layouttxtgeracao->db55_obs = $db55_obs;
$cldb_layouttxtgeracao->db55_conteudo = $gravarconteudo;
$cldb_layouttxtgeracao->incluir ( null );

if ($cldb_layouttxtgeracao->erro_status == "0") {
	$sqlerro = true;
}

if ($sqlerro == true) {
	die ( "erro: " . $cldb_layouttxtgeracao->erro_msg );
} else {
	$result = db_query ( "commit" ) or die ( "erro ao comitar" );
}

echo "<script>";
echo "  listagem = '$nomearq#Download arquivo TXT';";
echo "  parent.js_montarlista(listagem,'form1');";
echo "</script>";

?>
