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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
include_once(modification("libs/db_sessoes.php"));
require_once(modification('fpdf151/pdf.php'));

db_postmemory($HTTP_GET_VARS);
$oGet = db_utils::postMemory($_GET);

try {

	$sql_prev = "select trim(r33_nome) as descr_prev 
	               from inssirf 
	              where r33_anousu = $anofolha 
	                and r33_mesusu = $mesfolha 
	                and r33_instit = ".db_getsession('DB_instit')." 
	                and r33_codtab = $prev + 2 limit 1";
	$res_prev = db_query($sql_prev);

	if(!$res_prev) {
		throw new DBException("Ocorreu um erro ao recuperar a tabela de previdência para emissão do relatório.");	
	}

	if(pg_num_rows($res_prev) == 0) {
		throw new DBException("Nenhum tabela de previdência configurada para este código.\nVerifique as configurações de tabela de previdência.");
	}

	db_fieldsmemory($res_prev, 0);

	if(isset($listaSel)){
		$listaSel       = explode(",", $listaSel);
		$listaSeleciona = implode("','",$listaSel);
	}

	switch ($ordem){
		case "a":
			if($tipo_res == "m") {
				$orderby   = "z01_nome";             
				$cab_ordem = "Ordenado por Nome"; 
			}else if ($tipo_res == "l"){ 
				$orderby   = "r70_descr, z01_nome";             
				$cab_ordem = "Ordenado por Descrição de Lotação"; 
			}else if ($tipo_res == "t"){
				$orderby   = "rh55_descr, z01_nome";                  
				$cab_ordem = "Ordenado por Descrição de Local de trabalho";
			}else{
			 $orderby   = "z01_nome";                  
			 $cab_ordem = "";
			}
		break;
		
		case "n":
			if($tipo_res == "m") {
				$orderby   = "rh02_regist";             
				$cab_ordem = "Ordenado por Matrícula"; 
			}else if($tipo_res == "l"){
				$orderby   = "r70_codigo, z01_nome";             
				$cab_ordem = "Ordenado por Código Lotação"; 
			}else if($tipo_res == "t"){
				$orderby   = "rh55_estrut, z01_nome";                  
				$cab_ordem = "Ordenado por Código de Local de trabalho";
			}else{
				$orderby   = "rh02_regist";                  
				$cab_ordem = "";
			}
		break;
	}

	$sWhereFiltroTipoResumo = '';
	switch ($tipo_res){
		case "m":
			if($tipo_fil == "i"){
				$sWhereFiltroTipoResumo .= " AND rh01_regist between $campini AND $campfin ";
			}else if($tipo_fil == "s"){
				$sWhereFiltroTipoResumo .= " AND rh01_regist in ('$listaSeleciona' ) ";
			}
		break;
		case "l":
			if($tipo_fil == "i"){                          
				$sWhereFiltroTipoResumo .= " AND r70_codigo between $campini AND $campfin "; 
			}else if($tipo_fil == "s"){
				$sWhereFiltroTipoResumo .= " AND r70_codigo in ( '$listaSeleciona' ) ";
			}  
		break;
		case "t":
			if($tipo_fil == "i"){                          
				$sWhereFiltroTipoResumo .= " AND rh55_estrut between $campini AND $campfin "; 
			}else if($tipo_fil == "s"){
				$sWhereFiltroTipoResumo .= " AND rh55_estrut in ( '$listaSeleciona' ) ";
			}  
		break;
	}

	$oFiltro = new stdClass;
	$oFiltro->sCompetenciaInicio = $anoini.' / '.str_pad($mesini, 2, "0" ,STR_PAD_LEFT);
	$oFiltro->sCompetenciaFim    = $anofin.' / '.str_pad($mesfin, 2, "0" ,STR_PAD_LEFT);

	$rsServidores = db_query(montaSqlServidores($sTipoEmissao, $anofolha, $mesfolha, $prev, $instituicoes, $sWhereFiltroTipoResumo, $orderby));

	if(!$rsServidores) {
		throw new DBException("Ocorreu um erro ao consultar os servidores.");
	}

	$iQtdeServidores = pg_num_rows($rsServidores);
	if($iQtdeServidores == 0) {
		throw new BusinessException("Não há servidores para o filtro selecionado.");
	}

	$aServidores = array();
	$aServidores = db_utils::makeCollectionFromRecord($rsServidores, function($oServidor) use ($oFiltro) {

		$sLocalidade = $oServidor->municipio;

		if(!empty($oServidor->municipio) && !empty($oServidor->uf)) {
			$sLocalidade .= ' - '. $oServidor->uf;
		}

		$sEndereco = $oServidor->endereco;
		
		if(!empty($oServidor->endereco) && !empty($oServidor->end_nro)) {

			$sEndereco .= ', '. $oServidor->end_nro;

			if(!empty($oServidor->end_compl)) {
				$sEndereco .= ' - '. $oServidor->end_compl;
			}
		}

		$oServidor->iMatricula      = $oServidor->matricula;
		$oServidor->sNome           = $oServidor->nome;
		$oServidor->sEndereco       = $oServidor->endereco;
		$oServidor->sLocalidade     = $sLocalidade;
		$oServidor->iCep            = $oServidor->cep;
		$oServidor->sDataAdmissao   = $oServidor->admissao;
		$oServidor->sFunção         = $oServidor->cargo;
		$oServidor->sSetor          = $oServidor->setor;
		$oServidor->sLocalTrabalho  = $oServidor->local_trabalho;
		$oServidor->sInstituicao    = $oServidor->nome_instituicao;
		$oServidor->aDependentes    = array();
		$oServidor->aValores        = array();

		if(preg_match("/.*inss.*/i", $oServidor->previdencia) === 0) { // Mostra dependentes apenas para RPPS

			$rsDependentes = db_query(montaSqlDependentes($oServidor->matricula, $oFiltro->sCompetenciaInicio));

			if(!$rsDependentes) {
				throw new DBException("Ocorreu um erro ao consultar os dependentes do servidor.\nMatricula(".$oServidor->matricula.")");
			}

			if(pg_num_rows($rsDependentes) > 0) {

				$oServidor->aDependentes = db_utils::makeCollectionFromRecord($rsDependentes, function($oDependente){
					return (object)array(
						'sNome'          =>$oDependente->nome,
			      'sTipo'          =>$oDependente->parentesco,
			      'sDataNascimento'=>$oDependente->data_nascimento
					);
				});
			}
		}

		$rsValores = db_query(montaSqlValores($oServidor->matricula, $oFiltro->sCompetenciaInicio, $oFiltro->sCompetenciaFim, $oServidor->tabela_previdencia));

		if(!$rsValores) {
			throw new DBException("Ocorreu um erro ao recuperar os valores de contribuição para a(s) competência(s) informada(s).");
		}

		if(pg_num_rows($rsValores) > 0) {

			$oServidor->aValores = db_utils::makeCollectionFromRecord($rsValores, function($oValor) {
				return (object)array(
					'sCompetencia'         =>$oValor->ano .' / '.str_pad($oValor->mes, 2, "0" ,STR_PAD_LEFT),
					'nSalarioContribuicao' =>$oValor->base,
					'nValorServidor'       =>$oValor->desconto,
					'nPercentualPatronal'  =>$oValor->percentual_patronal,
					'nValorPatronal'       =>$oValor->patronal
				);
			});
		}

		return $oServidor;
	});

	$head2 = "EXTRATO À PREVIDÊNCIA";
	$head4 = "Previdência: {$descr_prev}";

	montaPDF($aServidores, $oFiltro);

} catch (Exception $e) {
	db_redireciona('db_erros.php?db_erro='.$e->getMessage());
}

function montaSqlServidores($sTipoEmissao, $iAno, $iMes, $iCodigoPrevidencia, $aCodigosInstituicao, $sWhereFiltroTipoResumo, $sOrderby) {

	if($sTipoEmissao=='a') {

		$iAno = DBPessoal::getAnoFolha();
		$iMes = DBPessoal::getMesFolha();
	}

	$sCamposListaServidores  = ' rh02_regist as matricula,                          ';
	$sCamposListaServidores .= ' to_Char(rh01_admiss, \'DD/MM/YYYY\') as admissao,  ';
	$sCamposListaServidores .= ' (rh02_tbprev + 2) as tabela_previdencia,           ';
	$sCamposListaServidores .= ' (   select r33_nome                                ';
  $sCamposListaServidores .= '      from inssirf                                  ';
  $sCamposListaServidores .= '     where r33_anousu = rh02_anousu                 ';
  $sCamposListaServidores .= '       and r33_mesusu = rh02_mesusu                 ';
  $sCamposListaServidores .= '       and r33_instit = rh02_instit                 ';
  $sCamposListaServidores .= '       and r33_codtab = (rh02_tbprev+2)             ';
  $sCamposListaServidores .= '  group by r33_nome) as previdencia,                ';
	$sCamposListaServidores .= ' z01_nome as nome,                                  ';
	$sCamposListaServidores .= ' z01_ender as endereco,                             ';
	$sCamposListaServidores .= ' z01_compl as end_compl,                            ';
	$sCamposListaServidores .= ' z01_numero as end_nro,                             ';
	$sCamposListaServidores .= ' z01_cep as cep,                                    ';
	$sCamposListaServidores .= ' z01_munic as municipio,                            ';
	$sCamposListaServidores .= ' z01_uf as uf,                                      ';
	$sCamposListaServidores .= ' rh37_descr as cargo,                               ';
	$sCamposListaServidores .= ' r70_codigo codigo_setor,                           ';
	$sCamposListaServidores .= ' r70_descr as setor,                                ';
	$sCamposListaServidores .= ' rh55_estrut as estrutural_local_trabalho,          ';
	$sCamposListaServidores .= ' rh55_descr as local_trabalho,                      ';
	$sCamposListaServidores .= ' nomeinst as nome_instituicao                       ';

	$sFromListaServidores  = '   rhpessoal                                    ';
	$sFromListaServidores .= ' INNER JOIN                                     ';
	$sFromListaServidores .= '   db_config ON codigo = rh01_instit            ';
	$sFromListaServidores .= ' INNER JOIN                                     ';
	$sFromListaServidores .= '   rhpessoalmov ON rh02_regist = rh01_regist    ';
	$sFromListaServidores .= '               AND rh02_instit = rh01_instit    ';
	$sFromListaServidores .= ' LEFT JOIN                                      ';
	$sFromListaServidores .= '   rhpesrescisao ON rh05_seqpes = rh02_seqpes   ';
	$sFromListaServidores .= ' INNER JOIN                                     ';
	$sFromListaServidores .= '   cgm ON z01_numcgm = rh01_numcgm              ';
	$sFromListaServidores .= ' INNER JOIN                                     ';
	$sFromListaServidores .= '   rhfuncao ON rh37_funcao = rh02_funcao        ';
	$sFromListaServidores .= '           AND rh37_instit = rh01_instit        ';
	$sFromListaServidores .= ' LEFT JOIN                                      ';
	$sFromListaServidores .= '   rhpeslocaltrab ON rh56_seqpes = rh02_seqpes  ';
	$sFromListaServidores .= ' LEFT JOIN                                      ';
	$sFromListaServidores .= '   rhlocaltrab ON rh55_codigo = rh56_localtrab  ';
	$sFromListaServidores .= '              AND rh55_instit = rh01_instit     ';
	$sFromListaServidores .= ' INNER JOIN rhlota ON r70_codigo = rh02_lota    ';
	$sFromListaServidores .= '                  AND r70_instit = rh02_instit  ';

	$sWhereListaServidores  = "     rh02_anousu = {$iAno}                       ";
	$sWhereListaServidores .= " AND rh02_mesusu = {$iMes}                       ";
	$sWhereListaServidores .= " AND rh02_tbprev = {$iCodigoPrevidencia}         ";
	$sWhereListaServidores .= ' AND (   rh05_seqpes IS NULL                     ';
	$sWhereListaServidores .= "      or rh05_recis >= '{$iAno}-{$iMes}-01')     ";
	$sWhereListaServidores .= ' AND rh02_instit IN ('. $aCodigosInstituicao .') ';
	$sWhereListaServidores .= $sWhereFiltroTipoResumo;

	$sGroupListaServidores  = ' rh02_regist, ';
	$sGroupListaServidores .= ' rh01_admiss, ';
	$sGroupListaServidores .= ' rh02_tbprev, ';
	$sGroupListaServidores .= ' previdencia, ';
	$sGroupListaServidores .= ' z01_nome,    ';
	$sGroupListaServidores .= ' z01_ender,   ';
	$sGroupListaServidores .= ' z01_compl,   ';
	$sGroupListaServidores .= ' z01_numero,  ';
	$sGroupListaServidores .= ' z01_cep,     ';
	$sGroupListaServidores .= ' z01_uf,      ';
	$sGroupListaServidores .= ' z01_munic,   ';
	$sGroupListaServidores .= ' rh37_descr,  ';
	$sGroupListaServidores .= ' r70_codigo,  ';
	$sGroupListaServidores .= ' r70_descr,   ';
	$sGroupListaServidores .= ' rh55_estrut, ';
	$sGroupListaServidores .= ' rh55_descr,  ';
	$sGroupListaServidores .= ' nomeinst     ';

	$sOrderListaServidores  = $sOrderby;

	$sSqlServidores = "   SELECT {$sCamposListaServidores}
	                        FROM {$sFromListaServidores}
	                       WHERE {$sWhereListaServidores}
	                    GROUP BY {$sGroupListaServidores}
	                    ORDER BY {$sOrderListaServidores} ";

  return $sSqlServidores;
}

function montaSqlDependentes($iMatricula, $sCompetenciaInicio) {

	$sCompetenciaInicio  = preg_replace("/^(\d{4})[\/ ]*(\d{1,2})/", "$1-$2-", $sCompetenciaInicio);
	$sCompetenciaInicio .= '01';

	$sSqlDependentes  = "  SELECT ";
	$sSqlDependentes .= "    rh31_nome as nome,                                    ";
	$sSqlDependentes .= '    case rh31_gparen                                      ';
  $sSqlDependentes .= "      when 'C' then 'Cônjuge'                             ";
  $sSqlDependentes .= "      when 'F' then 'Filho'                               ";
  $sSqlDependentes .= "      when 'P' then 'Pai'                                 ";
  $sSqlDependentes .= "      when 'M' then 'Mae'                                 ";
  $sSqlDependentes .= "      when 'A' then 'Avó'                                 ";
  $sSqlDependentes .= "      else 'Outros'                                       ";
  $sSqlDependentes .= "    end as parentesco,                                    ";
	$sSqlDependentes .= "    to_Char(rh31_dtnasc, 'DD/MM/YYYY') as data_nascimento ";
	$sSqlDependentes .= "    FROM rhdepend                                         ";
	$sSqlDependentes .= "   WHERE rh31_regist = ".$iMatricula;
	$sSqlDependentes .= "     AND (    rh31_gparen  = 'C'                                                                                    ";
  $sSqlDependentes .= "           OR (    rh31_gparen  = 'F'                                                                               ";
  $sSqlDependentes .= "               AND extract(year from age('{$sCompetenciaInicio}'::timestamp, rh31_dtnasc::timestamp))::int <= 21    ";
  $sSqlDependentes .= "              )                                                                                                     ";
  $sSqlDependentes .= "          )                                                                                                         ";
	$sSqlDependentes .= "   ORDER BY parentesco, data_nascimento                                                                             ";
	
	return $sSqlDependentes;
}

function montaSqlValores($iMatricula, $sCompetenciaInicio, $sCompetenciaFim, $iTabelaPrevidencia) {

	$sCompetenciaFim = str_replace(" ", "", $sCompetenciaFim);
	if(!preg_match("/^(\d{4})(?:\/*)(\d{2})/", $sCompetenciaFim, $aCompetenciaFim)) {
		throw new ParameterException("Ocorreu um erro ao obter o ano da competência");
	}
	$sAnoFim = $aCompetenciaFim[1];
	$sMesFim = $aCompetenciaFim[2];

	$sCompetenciaInicio = str_replace(" ", "", $sCompetenciaInicio);
	if(!preg_match("/^(\d{4})(?:\/)(\d{2})/", $sCompetenciaInicio, $aCompetenciaInicio)) {
		throw new ParameterException("Ocorreu um erro ao obter o mês da competência");
	}	
	$sAnoInicio = $aCompetenciaInicio[1];
	$sMesInicio = $aCompetenciaInicio[2];

	$aTabelas = array();

	$oTabela = new stdClass();
	$oTabela->sTabela = 'gerfsal'; 
	$oTabela->sSigla  = 'r14';
	$aTabelas[] = $oTabela;

	$oTabela = new stdClass();
	$oTabela->sTabela = 'gerfres'; 
	$oTabela->sSigla  = 'r20';
	$aTabelas[] = $oTabela;  

	$oTabela = new stdClass();
	$oTabela->sTabela = 'gerfs13'; 
	$oTabela->sSigla  = 'r35';
	$aTabelas[] = $oTabela;

	$oTabela = new stdClass();
	$oTabela->sTabela = 'gerfcom'; 
	$oTabela->sSigla  = 'r48';
	$aTabelas[] = $oTabela;

	$sSqlBase = "";  
		
	foreach ( $aTabelas as $iInd => $oTabela ) {

		if ( $iInd != 0 ) {
			$sSqlBase .= " union all ";		
		}
			
		$sSqlBase .= "     SELECT {$oTabela->sSigla}_anousu as anousu,                        ";  
    $sSqlBase .= "            {$oTabela->sSigla}_mesusu as mesusu,                        ";
    $sSqlBase .= "            {$oTabela->sSigla}_regist as matricula,                     ";
    $sSqlBase .= "            r33_ppatro as percentual_patronal,                          ";
    $sSqlBase .= "            {$oTabela->sSigla}_instit ,                                 ";
    $sSqlBase .= "            case                                                        ";
    $sSqlBase .= "              when {$oTabela->sSigla}_rubric = 'R992'                   ";
    $sSqlBase .= "                then {$oTabela->sSigla}_valor                           ";
    $sSqlBase .= "              else 0                                                    ";
    $sSqlBase .= "            end as base,                                                ";
    $sSqlBase .= "            case                                                        ";
    $sSqlBase .= "              when {$oTabela->sSigla}_rubric between 'R901' and 'R912'  ";
    $sSqlBase .= "                then {$oTabela->sSigla}_valor                           ";
    $sSqlBase .= "              else 0                                                    ";
    $sSqlBase .= "            end as desconto                                             ";
    $sSqlBase .= "       FROM {$oTabela->sTabela} as {$oTabela->sSigla}                                ";
    $sSqlBase .= " INNER JOIN (select distinct                                                         ";
    $sSqlBase .= "		           r33_anousu,                                                           ";
    $sSqlBase .= "			         r33_mesusu,                                                           ";
    $sSqlBase .= "			 				 r33_codtab,                                                           ";
    $sSqlBase .= "			 				 r33_ppatro,                                                           ";
    $sSqlBase .= "			 				 r33_instit                                                            ";
    $sSqlBase .= "					   FROM inssirf ) as inssirf ON r33_anousu = {$oTabela->sSigla}_anousu     ";
    $sSqlBase .= "											                AND r33_mesusu = {$oTabela->sSigla}_mesusu     ";
    $sSqlBase .= "											                AND r33_codtab = {$iTabelaPrevidencia}         ";
    $sSqlBase .= "											                AND r33_instit = {$oTabela->sSigla}_instit     ";
    $sSqlBase .= "  WHERE {$oTabela->sSigla}_regist = {$iMatricula}                                    ";
    $sSqlBase .= "    AND (   {$oTabela->sSigla}_rubric = 'R992'                                       ";
    $sSqlBase .= "			   or {$oTabela->sSigla}_rubric between 'R901' AND 'R912' )                    ";
	        
		if ($sAnoInicio == $sAnoFim) {   
		  $sSqlBase .= " AND {$oTabela->sSigla}_anousu between {$sAnoInicio} AND {$sAnoFim} ";
		  $sSqlBase .= " AND {$oTabela->sSigla}_mesusu between {$sMesInicio} AND {$sMesFim} ";
		} else {  
		  $sSqlBase .= " AND case                                                                                             ";
		  $sSqlBase .= "       when   {$oTabela->sSigla}_anousu =  {$sAnoInicio}                                              ";
		  $sSqlBase .= "         then {$oTabela->sSigla}_mesusu >= {$sMesInicio}                                              ";
		  $sSqlBase .= "		   when   {$oTabela->sSigla}_anousu =  {$sAnoFim}                                                 ";
		  $sSqlBase .= "         then {$oTabela->sSigla}_mesusu <= {$sMesFim}                                                 ";
		  $sSqlBase .= "		   when   {$oTabela->sSigla}_anousu >  {$sAnoInicio} AND {$oTabela->sSigla}_anousu <  {$sAnoFim}  ";
		  $sSqlBase .= "         then {$oTabela->sSigla}_mesusu > 0                                                           ";
		  $sSqlBase .= "     end                                                                                              ";
		}                                                                                                                  
	}
	
	$sSqlValores="SELECT
									anousu as ano,
									mesusu as mes,
									matricula,
									sum(base) as base,
									sum(desconto) as desconto,
									percentual_patronal,
									round((sum(base)/100 * percentual_patronal), 2) as patronal
	              FROM
               		({$sSqlBase}) as valores
                GROUP BY
									anousu,
									mesusu,
									matricula,
									percentual_patronal
								ORDER BY ano, mes, matricula ";

	return $sSqlValores;
}

function montaPDF($aServidores, $oFiltro){

	/**
   * Inicialização do objeto PDF
   */
  $pdf = new Pdf();
  $pdf->Open();
  $pdf->AliasNbPages();
  $pdf->SetFillColor(230);
  $pdf->SetAutoPageBreak(false);


  foreach ($aServidores as $oServidor) {

  	$oServidor->sContribuinte = $oServidor->iMatricula .' '. $oServidor->sNome;
		$pdf->AddPage();

  	montaSubCabecalhoServidores($pdf, $oServidor, $oFiltro);
  }

  $pdf->Output();
}

function montaSubCabecalhoServidores($pdf, $oServidor, $oFiltro) {

	$iAlturaWrapperDadosServidor = 35;
	if(count($oServidor->aDependentes) > 0) {
		$iAlturaWrapperDadosServidor = 47 + (count($oServidor->aDependentes)*5);
	}

	$pdf->Rect(7, 35, 195, $iAlturaWrapperDadosServidor);

	$pdf->bold();
	$pdf->Cell(18 ,5, "Contribuinte:",             0, 0, "L");
	$pdf->endbold();
	$pdf->Cell(177,5, $oServidor->sContribuinte,   0, 1, "L");
	
	$pdf->bold();
	$pdf->Cell(18 ,5, "Endereço:",                   0, 0, "L");
	$pdf->endbold();
	$pdf->Cell(130,5, $oServidor->sEndereco,         0, 0, "L");
	$pdf->bold();
	$pdf->Cell(27 ,5, "Ano / Mês Início:",           0, 0, "R");
	$pdf->endbold();
	$pdf->Cell(21 ,5, $oFiltro->sCompetenciaInicio,  0, 1, "L");
	
	$pdf->bold();
	$pdf->Cell(18 ,5, "Cidade:",                  0, 0, "L");
	$pdf->endbold();
	$pdf->Cell(130,5, $oServidor->sLocalidade,    0, 0, "L");
	$pdf->bold();
	$pdf->Cell(27 ,5, "Ano / Mês Fim:",           0, 0, "R");
	$pdf->endbold();
	$pdf->Cell(21 ,5, $oFiltro->sCompetenciaFim,  0, 1, "L");

	$pdf->bold();
	$pdf->Cell(18 ,5, "CEP:",                     0, 0, "L");
	$pdf->endbold();
	$pdf->Cell(130,5, $oServidor->iCep,           0, 0, "L");
	$pdf->bold();
	$pdf->Cell(27 ,5, "Data de Admissão:",        0, 0, "R");
	$pdf->endbold();
	$pdf->Cell(21 ,5, $oServidor->sDataAdmissao,  0, 1, "L");
	
	$pdf->bold();
	$pdf->Cell(18 ,5, "Função:",                0, 0, "L");
	$pdf->endbold();
	$pdf->Cell(177,5, $oServidor->sFunção,      0, 1, "L");
	
	$pdf->bold();
	$pdf->Cell(18, 5, "Setor:",                    0, 0, "L");
	$pdf->endbold();
	$pdf->Cell(75, 5, $oServidor->sSetor,          0, 0, "L");
	$pdf->bold();
	$pdf->Cell(25, 5, "Local de Trabalho:",        0, 0, "R");
	$pdf->endbold();
	$pdf->Cell(77, 5, $oServidor->sLocalTrabalho,  0, 1, "L");

	$pdf->bold();
	$pdf->Cell(18 ,5, "Instituição:",                0, 0, "L");
	$pdf->endbold();
	$pdf->Cell(177,5, $oServidor->sInstituicao,      0, 1, "L");

	$nSubTotalContribuicaoServidor = 0;
  $nSubTotalContribuicaoPatronal = 0;
  $nSubTotalContribuicaoGeral    = 0;

  $iQuantidadeDepentendes = count($oServidor->aDependentes);

  if($iQuantidadeDepentendes == 0) {
		$pdf->ln();
  }

	montaSecaoDependentes($pdf, $oServidor->aDependentes);
	montaValoresContribuicao($pdf, $oServidor->aValores, $iQuantidadeDepentendes);
}

function montaSecaoDependentes($pdf, $aDependentes) {

	if(count($aDependentes) > 0) {

		$iAlturaWrapperDependentes = (count($aDependentes)*5)+5;

		$pdf->bold();
		$pdf->Cell(191,5, "Dependentes",     0, 1, "L");
		$pdf->Line(28, 72.5, 199, 72.5);
		$pdf->Cell(94,5, "Nome",               0, 0, "L");
		$pdf->Cell(53,5, "Grau de Parentesco", 0, 0, "L");
		$pdf->Cell(44,5, "Data de Nascimento", 0, 1, "L");
		$pdf->endbold();
			
		foreach ($aDependentes as $oDependente) {

			$pdf->Cell(94,5, $oDependente->sNome,            0, 0, "L");
			$pdf->Cell(51,5, $oDependente->sTipo,            0, 0, "L");
			$pdf->Cell(28,5, $oDependente->sDataNascimento,  0, 1, "R");
		}

		$pdf->ln();
	}
}

function montaValoresContribuicao($pdf, $aValores, $iQtdeDependentes) {
	
	$nSubtotalServidorPatronal     = 0;
	$nSubTotalContribuicaoServidor = 0;
	$nSubTotalContribuicaoPatronal = 0;
	$nSubTotalContribuicaoGeral    = 0;

	$iEixoYLinhaCabecalhoValores = 75;
	if($iQtdeDependentes > 0) {
		$iEixoYLinhaCabecalhoValores = 85 + ($iQtdeDependentes*5);
	}	

	$lEscreverCabecalho = true;
  $pdf->Rect(7, $iEixoYLinhaCabecalhoValores, 195, 5);
  $pdf->bold();
  $pdf->Cell(32 ,5, "COMPETÊNCIA",       0, 0, "C");
  $pdf->Cell(32 ,5, "SAL. CONTRIBUIÇÃO", 0, 0, "C");
  $pdf->Cell(32 ,5, "SERVIDOR",          0, 0, "C");
  $pdf->Cell(33 ,5, "% PATRONAL",        0, 0, "C");
  $pdf->Cell(33 ,5, "PATRONAL",          0, 0, "C");
  $pdf->Cell(33 ,5, "TOTAL",             0, 1, "C");
  $pdf->endbold();
	foreach ($aValores as $oValor) {

		$nSubtotalServidorPatronal = ($oValor->nValorServidor + $oValor->nValorPatronal);
		
    if ($pdf->getY() > $pdf->h - 20) {   
      
      $pdf->AddPage();      
      $pdf->Rect(7, $pdf->getY(), 195, 5);
      $pdf->bold();
      $pdf->Cell(32 ,5, "COMPETÊNCIA",       0, 0, "C");
      $pdf->Cell(32 ,5, "SAL. CONTRIBUIÇÃO", 0, 0, "C");
      $pdf->Cell(32 ,5, "SERVIDOR",          0, 0, "C");
      $pdf->Cell(33 ,5, "% PATRONAL",        0, 0, "C");
      $pdf->Cell(33 ,5, "PATRONAL",          0, 0, "C");
      $pdf->Cell(33 ,5, "TOTAL",             0, 1, "C");
      $pdf->endbold();
    }
		
		$pdf->Cell(32, 5, $oValor->sCompetencia,                                  0, 0, "C");
		$pdf->Cell(32, 5, 'R$'. db_formatar($oValor->nSalarioContribuicao, 'f'),  0, 0, "C");
		$pdf->Cell(32, 5, 'R$'. db_formatar($oValor->nValorServidor, 'f'),        0, 0, "C");
		$pdf->Cell(33, 5, $oValor->nPercentualPatronal,                           0, 0, "C");
		$pdf->Cell(33, 5, 'R$'. db_formatar($oValor->nValorPatronal, 'f'),        0, 0, "C");
		$pdf->Cell(33, 5, 'R$'. db_formatar($nSubtotalServidorPatronal, 'f'),     0, 1, "C");

		$nSubTotalContribuicaoServidor += $oValor->nValorServidor;
  	$nSubTotalContribuicaoPatronal += $oValor->nValorPatronal;
  	$nSubTotalContribuicaoGeral    += $nSubtotalServidorPatronal;
	}

	$iEixoYLinhaSubtotais = ($iEixoYLinhaCabecalhoValores+5) + (count($aValores)*5);

	$pdf->Rect(7, $iEixoYLinhaSubtotais, 195, 5);

	$pdf->Cell(42, 5, "Total Contribuição do Servidor:",                      0, 0, "R");
	$pdf->Cell(30, 5, 'R$'. db_formatar($nSubTotalContribuicaoServidor, 'f'), 0, 0, "L");
	$pdf->Cell(35, 5, "Total Contribuição do Patronal:",                      0, 0, "R");
	$pdf->Cell(30, 5, 'R$'. db_formatar($nSubTotalContribuicaoPatronal, 'f'), 0, 0, "L");
	$pdf->Cell(33, 5, "Total do Servidor:",                                   0, 0, "R");
	$pdf->Cell(25, 5, 'R$'. db_formatar($nSubTotalContribuicaoGeral, 'f'),    0, 1, "L");
}