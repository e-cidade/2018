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

require_once('fpdf151/pdf.php');
require_once('libs/db_utils.php');

$oDaoFarParametros    = db_utils::getdao('far_parametros');
$oDaoFarRetiradaItens = db_utils::getdao('far_retiradaitens');
$oDaoCgsCartaoSus     = db_utils::getdao('cgs_cartaosus');
$oDaoFarControle      = db_utils::getdao('far_controle');

$dHoje                = date('Y-m-d', db_getsession('DB_datausu'));
$aHoje                = explode('-', $dHoje);
$tHoje                = mktime(0, 0 , 0, $aHoje[1], $aHoje[2], $aHoje[0]);

$sSql                 = $oDaoFarParametros->sql_query();
$rsFarParametros      =  $oDaoFarParametros->sql_record($sSql);
$oDadosFarParametros = db_utils::fieldsmemory($rsFarParametros, 0);

function getDataProx($iCgs, $iMedicamento, $sSql) {

  global $oDaoFarRetiradaItens;
  global $dDataAtual;

  $sSql .= "($iCgs, $iMedicamento, '$dDataAtual', true) as dados"; 
  $rs    = $oDaoFarRetiradaItens->sql_record($sSql);
  
  if ($oDaoFarRetiradaItens->numrows > 0) {

    $oDados = db_utils::fieldsmemory($rs, 0);
    $aDados = explode('#', $oDados->dados);
    
    return @$aDados[1];

  }

  return '';

}

/* Pega o cartao sus do paciente */
function getCns($iCgs) {
  
  global $oDaoCgsCartaoSus;
  
  $sSql           = $oDaoCgsCartaoSus->sql_query(null, ' s115_c_cartaosus ', ' s115_c_tipo asc ',
                                                 ' s115_i_cgs = '.$iCgs
                                                );
  $rsCgsCartaoSus = $oDaoCgsCartaoSus->sql_record($sSql);
  if ($oDaoCgsCartaoSus->numrows != 0) { // se o paciente tem um cartao sus

    $oDadosCgsCartaoSus = db_utils::fieldsmemory($rsCgsCartaoSus, 0);
    $sCartaoSus         = $oDadosCgsCartaoSus->s115_c_cartaosus;

  } else {
    $sCartaoSus = '';
  }
  
  return $sCartaoSus;

}

function novoAlmoxarifado($oPdf, $iCod, $sNome) {

  $lCor = false;
  $oPdf->setfont('arial', 'B', 11);
  $oPdf->cell(190, 12, "$iCod - $sNome ", 0, 1, 'L', $lCor);

}

function novoPaciente($oPdf, $iCgs, $sNome, $sSexo, $sCpf, $dNasc, $sMae, $iCns, $sObs) {

  $lCor = false;
  $oPdf->setfont('arial', '', 9);
  
  $iTam = 4;

  $oPdf->cell(190, 1, '', 0, 1, 'L', $lCor);

  $oPdf->cell(190, 1, '', 'T', 1, 'L', $lCor);

  $oPdf->cell(115, $iTam, $sNome, 0, 0, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(9, $iTam, 'CPF:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(23, $iTam, $sCpf, 0, 0, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(9, $iTam, 'CNS:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(28, $iTam, $iCns, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(9, $iTam, 'Mãe:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(91, $iTam, $sMae, 0, 0, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(10, $iTam, 'Sexo:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(5, $iTam, $sSexo, 0, 0, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(9, $iTam, 'CGS:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(23, $iTam, $iCgs, 0, 0, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Nascimento:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(17, $iTam, $dNasc, 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(20, $iTam, 'Observação:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->MultiCell(170, $iTam, $sObs, 0, 1, 'L', $lCor);

}

function novoMedicamento($oPdf, $iCod, $sNome, $iUltQtde, $sUnidade, $dDataUlt, $dDataProx) {

  $lCor = false;
  $oPdf->setfont('arial', '', 9);
  
  $iTam = 4;
  
  $oPdf->cell(190, 1.5, '', 0, 1, 'L', $lCor);

  $oPdf->cell(190, $iTam, "$iCod - $sNome", 0, 1, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(38, $iTam, 'Última qtde dispensada:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(34, $iTam, "$iUltQtde  $sUnidade", 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(9, $iTam, 'Data:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(34, $iTam, $dDataUlt, 0, 0, 'L', $lCor);

  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(35, $iTam, 'Próxima dispensação:', 0, 0, 'L', $lCor);
  $oPdf->setfont('arial', '', 9);
  $oPdf->cell(40, $iTam, $dDataProx, 0, 1, 'L', $lCor);

}

function novoTotal($oPdf, $iPacientes, $iMedicamentos) {

  $lCor = false;
  $oPdf->setfont('arial', 'B', 11);
  
  $oPdf->cell(190, 1, '', 'T', 1, 'C', $lCor);
  $oPdf->cell(190, 5, "Total de Pacientes: $iPacientes             Total de Medicamentos: $iMedicamentos ", 0, 1, 'C', $lCor);

}

function formataData($dData, $iTipo = 1) {

  if ($iTipo == 1) {

    $dData = explode('/', $dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
 return $dData;

}

$sSubMovimentacao    = '';
$nomes_departamentos = str_replace(',', ', ', $nomes_departamentos);
$dDataAtual          = date('Y-m-d', db_getsession('DB_datausu'));
$iDepartamentos      = 0;
$bDepartamentos       = false;
$sDepartamentos      = $departamentos;
$sMedicamentos       = $medicamentos;
$sWhereAlmoxarifados = '';
$sWhereMedicamentos  = '';
$sExatoMedicamento   = '';
$sSeparador          = '';
$sOrderBy            = ' far_retiradaItensDepartamento.coddepto asc, cgs_und.z01_v_nome asc, cgs_und.z01_i_cgsund asc, ';
$sOrderBy           .= ' far_matersaude.fa01_i_codigo asc, far_retiradaItensDepartamento.fa04_d_data ';

if (!empty($departamentos)) {

  $sWhereAlmoxarifados .= 'far_retiradaItensDepartamento.fa04_i_unidades in ( '.$departamentos.' )';
  $sSeparador           = ' and ';
  $bDepartamentos      = true;
  $aDepartamentos     = explode(",",$sDepartamentos);
  $iDepartamentos     = count ($aDepartamentos);
}

/*
 * Se existe medicamentos estes são inclusos na condição where
*/
if (!empty($medicamentos)) {

  $sExatoMedicamento  = "";
  $sInMedicamentos    = "";
  if ($iExato != 2 ) {
  	
  	$sInMedicamentos    = ' far_controlemed.fa10_i_medicamento in ( '.$medicamentos.' ) ';
    $sExatoMedicamento .= " select count (*) ";
    $sExatoMedicamento .= "           from far_controlemed";
    $sExatoMedicamento .= "                inner join far_controle on fa11_i_codigo = fa10_i_controle ";
    $sExatoMedicamento .= "           where fa11_i_cgsund = z01_i_cgsund ";
    $sWhereMedicamentos = $sSeparador.$sInMedicamentos.$sSeparador."({$sExatoMedicamento} and ".$sInMedicamentos.") >= 1 ";
    $sSeparador = ' and ';
    
  } else {
    $sWhereMedicamentos = " and far_retiradaItensDepartamento.fa06_i_matersaude in ({$sMedicamentos}) ";
  }
}

$sCampos  = ' far_retiradaItensDepartamento.coddepto,far_retiradaItensDepartamento.fa06_f_quant,far_retiradaItensDepartamento.fa04_d_data,';
$sCampos .= ' far_retiradaItensDepartamento.descrdepto, cgs_und.z01_i_cgsund, cgs_und.z01_v_nome, cgs_und.z01_v_sexo';
$sCampos .= ' ,case when cgs_und.z01_v_cgccpf = \'0\' then null else cgs_und.z01_v_cgccpf end as z01_v_cgccpf, ';
$sCampos .= ' cgs_und.z01_d_nasc, cgs_und.z01_v_mae, far_matersaude.fa01_i_codigo,  matmater.m60_descr, ';
$sCampos .= ' matunid.m61_abrev,fa11_i_cgsund, fa10_i_medicamento, ';
$sCampos .= ' far_controle.fa11_t_obs, far_controlemed.fa10_d_dataini,';
$sCampos .= ' far_controlemed.fa10_d_datafim ';


/* Cálculo da data a partir da qual, se não foram ralizadas retiradas, o cadastro de continuados 
  é dito sem movimentaçao (cancelado) */
if ($oDadosFarParametros->fa02_i_numdiasmedcontinativo > 0) {

  $tDataInativo      = $tHoje - ($oDadosFarParametros->fa02_i_numdiasmedcontinativo * 86400); // hoje - N dias
  $dDataInativo      = date('Y-m-d', $tDataInativo);
}

$sWhere       = " $sWhereAlmoxarifados $sWhereMedicamentos ".$sSeparador;
$sWhereData   = " (far_controlemed.fa10_d_datafim is null or far_controlemed.fa10_d_datafim >= '$dDataAtual') ";
$sWhereData  .= " and ((far_controlemed.fa10_d_datafim is null and far_retiradaItensDepartamento.fa04_d_data >= ";
$sWhereData  .= " far_controlemed.fa10_d_dataini) or (far_controlemed.fa10_d_datafim is not null ";
$sWhereData  .= " and far_retiradaItensDepartamento.fa04_d_data between far_controlemed.fa10_d_dataini ";
$sWhereData  .= " and far_controlemed.fa10_d_datafim)) ";
$sWhere      .=  $sWhereData;
$sSeparador   = " and ";

/*
 * Verifica se foi solicitado o relatorio de medicamento com ou sem movimentação
 **/
switch ($iMovimentacao) {

  case 1:

    $head4            = 'Movimentação: Todos';
    $sSubMovimentacao = '';
    
    break;

  case 2:

    $head4             = 'Movimentação: Ativos';
    $sSubMovimentacao  = ' and fa04_i_codigo is not null ';
    $sWhere           .= $sSubMovimentacao;
    break;

  case 3:

    $head4             = 'Movimentação: Inativos';
    $sSubMovimentacao  = ' and fa04_i_codigo is null ';
    $sWhere           .= $sSubMovimentacao;

}

$sSql          = $oDaoFarControle->sql_query_admmedicamentos(null, $sCampos, $sOrderBy, $sWhere);
//echo $sSql;
$rsFarControle = $oDaoFarControle->sql_record($sSql);
$iLinhas       = $oDaoFarControle->numrows;
if ($iLinhas == 0) {
?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado.<br>
            <input type='button' value='Fechar' onclick='window.close()'>
          </b>
        </font>
      </td>
    </tr>
  </table>
<?
  exit;
}

/*Trecho que verifica qual funcao de saldo dos continuados devo usar (de acordo com os parametros) */
$sSql                = $oDaoFarParametros->sql_query(null, 'fa02_i_tipoperiodocontinuado as fixo');
$rsFarParametros     = $oDaoFarParametros->sql_record($sSql);
$oDadosFarParametros = db_utils::fieldsmemory($rsFarParametros, 0);

if ((int)$oDadosFarParametros->fixo == 1) {
  $sSqlpl = 'select fc_saldocontinuado_periodo_fixo';
} else {
  $sSqlpl = 'select fc_saldocontinuado_periodo_dinamico';
}


$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

$nomes_departamentos = empty($nomes_departamentos) ? 'Todos' : $nomes_departamentos;

$head1 = 'Relação de Pacientes em Tratamento';
$head2 = '';
$head3 = 'Data: '.formataData($dDataAtual, 2);
$head4 = 'Almoxarifado(s): '.$nomes_departamentos;

$lCor  = false;
$oPdf->setfillcolor(223);
$oPdf->setfont('arial','',11);

$iAlmoxarifado      = -1;
$iPaciente          = -1;
$iMedicamento       = -1;
$iTotalPacientes    = 0;
$iTotalMedicamentos = 0;
$aMedicamentosDep   = array();

for ($iCount = 0; $iCount < $iLinhas; $iCount++) {

  $oDados = db_utils::fieldsmemory($rsFarControle,$iCount);
  $iAlmoxAntes = $iAlmoxarifado;
  if ($iAlmoxarifado != $oDados->coddepto) {
    
    if ($iTotalPacientes != 0) {
      novoTotal($oPdf, $iTotalPacientes, $iTotalMedicamentos);
    }

    $oPdf->Addpage('P');
    $iAlmoxarifado =  $oDados->coddepto;

    novoAlmoxarifado($oPdf, $oDados->coddepto, $oDados->descrdepto);

    $iTotalPacientes = 0;
    $iTotalMedicamentos = 0;
    $aMedicamentosDep = array();

  }
  
  if ($iPaciente != $oDados->z01_i_cgsund || $iAlmoxAntes != $oDados->coddepto) {

    $iPaciente =  $oDados->z01_i_cgsund;
    $iCns = getCns($iPaciente);
    if (!empty($oDados->z01_d_nasc)) {
      $oDados->z01_d_nasc = formataData($oDados->z01_d_nasc, 2);
    }

    novoPaciente($oPdf, $oDados->z01_i_cgsund, $oDados->z01_v_nome, $oDados->z01_v_sexo, $oDados->z01_v_cgccpf,
                 $oDados->z01_d_nasc, $oDados->z01_v_mae, $iCns, $oDados->fa11_t_obs);
    
    $iTotalPacientes++;
    $iMedicamento = -1;

  }

  if ($iMedicamento != $oDados->fa01_i_codigo) {

    $iMedicamento =  $oDados->fa01_i_codigo;
    $dDataProx = getDataProx($oDados->z01_i_cgsund, $iMedicamento, $sSqlpl);
    $dDataProx = empty($dDataProx) ? '' : formataData($dDataProx, 2);

    novoMedicamento($oPdf, $oDados->fa01_i_codigo, $oDados->m60_descr, $oDados->fa06_f_quant, $oDados->m61_abrev,
                    formataData($oDados->fa04_d_data, 2), $dDataProx);

    if (!in_array($iMedicamento, $aMedicamentosDep)) {

      $iTotalMedicamentos++;
      $aMedicamentosDep[count($aMedicamentosDep)] = $iMedicamento;

      }

  }

}
novoTotal($oPdf, $iTotalPacientes, $iTotalMedicamentos);

$oPdf->Output();
?>