<?php
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

require_once('fpdf151/scpdf.php');
require_once('libs/db_utils.php');

$oDaoTfdVeiculoDestino    = new cl_tfd_veiculodestino();
$oDaoTfdPassageiroRetorno = new cl_tfd_passageiroretorno();
$oDaoDbConfig             = new cl_db_config();

$sWhere = " tf18_i_destino = {$coddestino}";

if (isset($codveiculo) && $codveiculo != '') {
	$sWhere .= " and tf18_i_veiculo = {$codveiculo}";
}

$dDataSaida = substr($datasaida, 6, 4) . '-' . substr($datasaida, 3, 2) . '-' . substr($datasaida, 0, 2);
$sWhere    .= " and tf18_d_datasaida = '{$dDataSaida}' ";
$sWhere    .= " and tf18_c_horasaida = '{$hora}'";

$sCampos  = 'z01_v_nome, z01_v_ident, z01_v_telef, z01_v_telcel, tf16_c_horaagendamento, a.z01_nome, ';
$sCampos .= 've01_anofab, ve01_placa, tf18_d_datasaida, tf18_c_horasaida, tf03_c_descr, tf24_c_descr, ';
$sCampos .= 'tf03_f_distancia, tf18_i_veiculo, tf17_c_localsaida, tf18_i_codigo, z01_v_cgccpf, ';
$sCampos .= 'z01_c_certidaonum, tf19_i_fica, empresa.z01_nome as nomeempresa, tf18_d_dataretorno, ';
$sCampos .= 'tf18_c_horaretorno, ve01_quantcapacidad, empresa.z01_nome as nomeempresa, ve02_numcgm';

/**
 * Busca os passageiros normais
 */
$sWhereNormais         = $sWhere . ' and tf19_i_colo = 2 and tf01_i_situacao = 1';
$sSql                  = $oDaoTfdVeiculoDestino->sql_query_lista_daer( null, $sCampos, null,	$sWhereNormais );
$rs                    = $oDaoTfdVeiculoDestino->sql_record($sSql);
$iQtdLinhasPassageiros = $oDaoTfdVeiculoDestino->numrows;

/**
 * Busca os passageiros de colo
 */
$sWhereColo                = $sWhere . ' and tf19_i_colo = 1 and tf01_i_situacao = 1';
$sSql                      = $oDaoTfdVeiculoDestino->sql_query_lista_daer( null, $sCampos, null,	$sWhereColo );
$rsColo                    = $oDaoTfdVeiculoDestino->sql_record($sSql);
$iQtdLinhasPassageirosColo = $oDaoTfdVeiculoDestino->numrows;

/**
 * Busca os passageiros de retorno
 */
$sCampos        = 'z01_v_nome, z01_v_ident, z01_v_telef, z01_v_telcel, z01_c_certidaonum, ';
$sCampos       .= 'z01_v_cgccpf, tf18_i_codigo ';

$sWhereRetorno                = $sWhere . ' and tf31_i_valido = 1';
$sSql                         = $oDaoTfdPassageiroRetorno->sql_query2( null, $sCampos, 'z01_v_nome',	$sWhereRetorno );
$rsRetorno                    = $oDaoTfdPassageiroRetorno->sql_record($sSql);
$iQtdLinhasPassageirosRetorno = $oDaoTfdPassageiroRetorno->numrows;

/**
 * Busca a informação do município em que o script está sendo rodado
 */
$sSql     = $oDaoDbConfig->sql_query(null, 'munic', '', '');
$rsConfig = $oDaoDbConfig->sql_record($sSql);

if ($iQtdLinhasPassageiros == 0) {
	?>
<table width='100%'>
	<tr>
		<td align='center'>
      <font color='#FF0000' face='arial'>
        <label class="bold">Nenhum registro encontrado</label>
        <br>
        <input type='button' value='Fechar' onclick='window.close()'>
		  </font>
		</td>
	</tr>
</table>
<?php
exit;
}

$oPdf = new SCPDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->setLeftMargin(8.5);
$oPdf->addpage('P');
$oPdf->setfillcolor(235);

$iContLinha        = 0;
$iContLinhaColo    = 0;
$iContLinhaRetorno = 0;
$oDadosConfig      = db_utils::fieldsmemory($rsConfig, 0);

/**
 * Imprimo os cabeçalho da listagem
 */
$oPdf->setfont('arial', 'B', 7);
$iTam = 3;
$lCor = false;

$oPdf->cell(46,  $iTam, '', 'LTR', 0, 'C', $lCor);
$oPdf->cell(148, $iTam, 'SECRETARIA DOS TRANSPORTES', 'LTR', 1, 'C', $lCor);
$oPdf->cell(46,  $iTam, '', 'LR', 0, 'C', $lCor);
$oPdf->cell(148, $iTam, 'DEPARTAMENTO AUTÔNOMO DE ESTRADAS DE RODAGEM', 'LR', 1, 'C', $lCor);
$oPdf->cell(46,  $iTam, '', 'LR', 0, 'C', $lCor);
$oPdf->cell(148, $iTam, 'DIRETORIA DE OPERAÇÃO E CONCESSÕES','LR', 1, 'C', $lCor);
$oPdf->cell(46,  $iTam, '', 'LR', 0, 'C', $lCor);
$oPdf->cell(148, $iTam, 'DEPARTAMENTO DE TRANSPORTE COLETIVO', 'LR', 1, 'C', $lCor);
$oPdf->cell(46,  $iTam, '', 'LBR', 0, 'C', $lCor);
$oPdf->cell(148, $iTam, '', 'LBR', 1, 'C', $lCor);

$oPdf->setfont('arial', 'b', 12);
$oPdf->cell(194, 10, 'RELAÇÃO DE PASSAGEIROS', 1, 1, 'C', $lCor);

$oPdf->image('./imagens/daer.jpeg', $oPdf->GetX()+15, $oPdf->GetY()-23, 12, 12);

imprimeCabecalho($oPdf, 1);

$oPassageiro = db_utils::fieldsmemory($rs, $iContLinha);

if( $iContLinha == 0 ) {

	$iCapacidadeVeiculo =  $oPassageiro->ve01_quantcapacidad;
	
	if( !isset($iCapacidadeVeiculo) ){
		
		/**
     * Valido a capacidade do veiculo nao exista o padrao é o usado anteriormente fixo no fonte de 54 passageiros
     */
		$iCapacidadeVeiculo = 54;
	}
}

for($iContLinha = 0; $iContLinha < $iQtdLinhasPassageiros; $iContLinha++){

	$oPassageiro = db_utils::fieldsmemory($rs, $iContLinha);
	
	/**
   * Imprimo os pasageiros normais
   */
	$sNome     = $oPassageiro->z01_v_nome;
	$sRg       = '';
	$sDestino  = $oPassageiro->z01_nome;
	$sHora     = $oPassageiro->tf16_c_horaagendamento;
	$sTelefone = '';

	if (!empty($oPassageiro->z01_v_ident) && $oPassageiro->z01_v_ident != '0') {
		$sRg = $oPassageiro->z01_v_ident;
	} elseif (!empty($oPassageiro->z01_v_cgccpf) && $oPassageiro->z01_v_cgccpf != '0') {
		$sRg = $oPassageiro->z01_v_cgccpf;
	} elseif (!empty($oPassageiro->z01_c_certidaonum) && $oPassageiro->z01_c_certidaonum != '0') {
		$sRg = $oPassageiro->z01_c_certidaonum;
	}

	if (!empty($oPassageiro->z01_v_telef) && $oPassageiro->z01_v_telef != '0') {
		$sTelefone = $oPassageiro->z01_v_telef;
	} elseif (!empty($oPassageiro->z01_v_telcel) && $oPassageiro->z01_v_telcel != '0') {
		$sTelefone = $oPassageiro->z01_v_telcel;
	}

	if ($oPassageiro->tf19_i_fica == 1) {
		$lFica = true;
	} else {
		$lFica = false;
	}

	imprimePassageiro(1, $oPdf, $iContLinha, $sNome, $sRg, $sTelefone, $sDestino, $sHora, $lFica);
}

/**
 * Completo a listagem com espaçoes em branco de acordo com a capacidade do veiculo
 */
if($iQtdLinhasPassageiros < $iCapacidadeVeiculo){

	$iTotalEspacosBranco = $iCapacidadeVeiculo - $iQtdLinhasPassageiros;
	$iProximoCont				 = $iContLinha;
	
	for($iCont = 0; $iCont < $iTotalEspacosBranco; $iCont++){
    imprimePassageiro(1, $oPdf, $iProximoCont++, null, null, null, null, null, false);
	}
}

/**
 * Adiciono a segunda página (passageiros de colo, retorno e informações gerais)
 */
$oPdf->addpage('P');
	
imprimeTitulo($oPdf, 'colo');
imprimeCabecalho($oPdf);

/**
 * Imprimo os pasageiros de colo
 */
for ($iCont = 0; $iCont < $iContLinhaColo; $iCont++) {

	$oPassageiroColo = db_utils::fieldsmemory($rsColo, $iContLinhaColo);
	
	$sNome     = $oPassageiroColo->z01_v_nome;
	$sRg       = '';
	$sTelefone = '';

	if (!empty($oPassageiroColo->z01_v_ident) && $oPassageiroColo->z01_v_ident != '0') {
		$sRg = $oPassageiroColo->z01_v_ident;
	} elseif (!empty($oPassageiroColo->z01_v_cgccpf) && $oPassageiroColo->z01_v_cgccpf != '0') {
		$sRg = $oPassageiroColo->z01_v_cgccpf;
	} elseif (!empty($oPassageiroColo->z01_c_certidaonum) && $oPassageiroColo->z01_c_certidaonum != '0') {
		$sRg = $oPassageiroColo->z01_c_certidaonum;
	}

	if (!empty($oPassageiroColo->z01_v_telef) && $oPassageiroColo->z01_v_telef != '0') {
		$sTelefone = $oPassageiroColo->z01_v_telef;
	} elseif (!empty($oPassageiroColo->z01_v_telcel) && $oPassageiroColo->z01_v_telcel != '0') {
		$sTelefone = $oPassageiroColo->z01_v_telcel;
	}

	imprimePassageiro(2, $oPdf, $iCont, $sNome, $sRg, $sTelefone);
}

/**
 * Completo a listagem com espaçoes em branco de acordo com a capacidade do veiculo
 */
if( $iQtdLinhasPassageirosColo < 10 ){

	$iTotalEspacosBranco = 10 - $iQtdLinhasPassageirosColo;
	$iProximoCont				 = $iContLinhaColo;

	for($iCont = 0; $iCont < $iTotalEspacosBranco; $iCont++){
		imprimePassageiro(2, $oPdf, $iProximoCont++, null, null, null);
	}
}

/**
 * Imprimo os cabelos de retorno
 */
imprimeTitulo($oPdf, 'retorno');
imprimeCabecalho($oPdf);

/**
 * Imprimo os pasageiros de retorno
 */
for($iCont = 0; $iCont < $iQtdLinhasPassageirosRetorno; $iCont++){

	$oPassageiroRetorno = db_utils::fieldsmemory($rsRetorno, $iCont);

	$sNome     = $oPassageiroRetorno->z01_v_nome;
	$sRg       = '';
	$sTelefone = '';

	if (!empty($oPassageiroRetorno->z01_v_ident) && $oPassageiroRetorno->z01_v_ident != '0') {
		$sRg = $oPassageiroRetorno->z01_v_ident;
	} elseif (!empty($oPassageiroRetorno->z01_v_cgccpf) && $oPassageiroRetorno->z01_v_cgccpf != '0') {
		$sRg = $oPassageiroRetorno->z01_v_cgccpf;
	} elseif (!empty($oPassageiroRetorno->z01_c_certidaonum) && $oPassageiroRetorno->z01_c_certidaonum != '0') {
		$sRg = $oPassageiroRetorno->z01_c_certidaonum;
	}

	if (!empty($oPassageiroRetorno->z01_v_telef) && $oPassageiroRetorno->z01_v_telef != '0') {
		$sTelefone = $oPassageiroRetorno->z01_v_telef;
	} elseif (!empty($oPassageiroRetorno->z01_v_telcel) && $oPassageiroRetorno->z01_v_telcel != '0') {
		$sTelefone = $oPassageiroRetorno->z01_v_telcel;
	}

	imprimePassageiro(2, $oPdf, $iCont, $sNome, $sRg, $sTelefone);
}

/**
 * Completo a listagem com espaçoes em branco de acordo com a capacidade do veiculo
 */
if($iQtdLinhasPassageirosRetorno < $iCapacidadeVeiculo){

	$iTotalEspacosBranco = $iCapacidadeVeiculo - $iQtdLinhasPassageirosRetorno;
	$iProximoCont				 = $iCont;
	
	for($iCont = 0; $iCont < $iTotalEspacosBranco; $iCont++){
		imprimePassageiro(2, $oPdf, $iProximoCont++, null, null, null, null);
	}
}

/**
 * Impressão dos dados gerais
 */
$oPassageiro = db_utils::fieldsmemory($rs, 0);

imprimeRodape( $oPdf, 
							 $oPassageiro->nomeempresa, 
							 $oPassageiro->ve01_placa,
							 db_formatar($oPassageiro->tf18_d_datasaida, 'd'), 
							 $oPassageiro->tf18_c_horasaida,
							 db_formatar($oPassageiro->tf18_d_dataretorno, 'd'), 
							 $oPassageiro->tf18_c_horaretorno,
							 $oDadosConfig->munic, 
							 $oPassageiro->tf03_c_descr, 
							 $oPassageiro->tf17_c_localsaida,
							 $oPassageiro->tf03_f_distancia.' '.$oPassageiro->tf24_c_descr,
							 $oPassageiro->ve02_numcgm
	  			  );

/**
 * Imprime o Cabeçalho das listagens
 */
function imprimeCabecalho($oPdf , $iTipo=2) {

	$lCor = false;

	if( $iTipo == 2){
		
		$oPdf->setfont('arial', 'B', 7);
		$iTam = 3.5;
		
		$oPdf->cell(6, $iTam,   'Nº', 1, 0, 'C', $lCor);
		$oPdf->cell(128, $iTam, 'Nome do Passageiro', 1, 0, 'L', $lCor);
		$oPdf->cell(28, $iTam,  'Nº Documento', 1, 0, 'L', $lCor);
		$oPdf->cell(30, $iTam,  'Telefone', 1, 1, 'L', $lCor);
	} else {
		
		$oPdf->setfont('arial', 'B', 8);
		$iTam = 3.5;
		
		$oPdf->cell(6,  $iTam, 'Nº', 1, 0, 'C', $lCor);
		$oPdf->cell(74, $iTam, 'Nome do Passageiro', 1, 0, 'L', $lCor);
		$oPdf->cell(23, $iTam, 'Nº Documento', 1, 0, 'L', $lCor);
		$oPdf->cell(61, $iTam, 'Destino', 1, 0, 'L', $lCor);
		$oPdf->cell(10, $iTam, 'Hora', 1, 0, 'L', $lCor);
		$oPdf->cell(20, $iTam, 'Telefone', 1, 1, 'L', $lCor);
	}
}

/**
 * Adiciona linha com os dados do passageiro ou linha em branco
 */
function imprimePassageiro($iTipo=2, $oPdf, $iCont, $sNome=null, $sRg=null, $sTelefone=null, $sDestino=null, $sHora=null, $lFica=false) {

	$oPdf->setfont('arial', '', 7);
	
	$lCor = false;
	$iCont++;
	$iTam = 3.5;
	$iW   = $oPdf->getStringWidth($sNome) + 1.0;
		
	$oPdf->cell(6, $iTam, str_pad($iCont, 2, '0', STR_PAD_LEFT), 1, 0, 'C', 0);
	
	if( $iTipo == 2 ){

		$oPdf->cell(128, $iTam, $sNome, 1, 0, 'L', 0);
		$oPdf->cell(28, $iTam, $sRg, 1, 0, 'L', 0);
		$oPdf->cell(30, $iTam, $sTelefone, 1, 1, 'L', 0);
	} else {
		
		if ($lFica) {
		
			$sFica = ' - FICA';
		} else {
			$sFica = '';
		}
		
		$oPdf->cell($iW, $iTam, $sNome, 'LBT', 0, 'L', 0);
		
		$oPdf->setfont('arial', 'B', 8);
		$oPdf->cell(74.0 - $iW, $iTam, $sFica, 'RBT', 0, 'L', 0);
		
		$oPdf->setfont('arial', '', 7);
		$oPdf->cell(23, $iTam, $sRg, 1, 0, 'L', 0);
		$oPdf->cell(61, $iTam, substr($sDestino, 0, 38), 1, 0, 'L', 0);
		$oPdf->cell(10, $iTam, $sHora, 1, 0, 'L', 0);
		$oPdf->cell(20, $iTam, $sTelefone, 1, 1, 'L', 0);
	}
}

function imprimeTitulo($oPdf, $sTipo) {

	$iTam = 8;
	$lCor = false;

	if( $sTipo == 'colo'){
	
		$oPdf->setfont('arial', 'B', 9);
		$oPdf->cell(194, $iTam, 'COLO (ATÉ 5 ANOS)', 0, 1, 'L', $lCor);
	} else {

		$oPdf->setfont('arial', 'B', 12);
		$oPdf->cell(194, $iTam, 'RETORNO', 0, 1, 'C', $lCor);
	}
}

function imprimeRodape($oPdf, $sEmpresa=null, $sPlaca=null, $dDataIni=null, $sHoraIni=null, 
											 $dDataRet=null, $sHoraRet=null, $sOrigem=null, $sDestino=null, 
											 $sSaida=null, $sDistancia=null, $iCgmEmpresa=null) {

	$oPdf->setfont('arial', 'B', 10);
	$iTam = 6;
	$lCor = false;

	$oPdf->ln(5);
	
	if( isset($iCgmEmpresa) && $iCgmEmpresa <> 0 ){
		$oPdf->cell(194, $iTam, 'Nome Empresa: '.$sEmpresa, 0, 1, 'L', 0);
	}
	
	$oPdf->cell(194, $iTam, 'Placa: '.$sPlaca, 0, 1, 'L', 0);
	$oPdf->cell(194, $iTam, 'DATA DE INÍCIO DA VIAGEM: '.$dDataIni.'    SAÍDA: '.$sHoraIni.' HS', 0, 1, 'L', 0);
	$oPdf->cell(194, $iTam, 'DATA DE RETORNO: '.$dDataRet.'    RETORNO: '.$sHoraRet.' HS', 0, 1, 'L', 0);
	$oPdf->cell(194, $iTam, 'CIDADE DE ORIGEM: '.$sOrigem, 0, 1, 'L', 0);
	$oPdf->cell(194, $iTam, 'CIDADE DE DESTINO: '.$sDestino, 0, 1, 'L', 0);
	$oPdf->cell(194, $iTam, 'Endereço de Saída: '.$sSaida, 0, 1, 'L', 0);
	$oPdf->cell(194, $iTam, 'Distância da Viagem: '.$sDistancia, 0, 1, 'L', 0);
	$oPdf->cell(194, $iTam, 'Local:___________________________________________  Data: ___/___/___', 0, 0, 'L', 0);

	$iAltura = $oPdf->GetY();
	$oPdf->SetY($iAltura + 20);
}

$oPdf->Output();