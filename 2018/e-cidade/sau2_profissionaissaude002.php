<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

$oDaoUndMedHorario = db_utils::getdao('undmedhorario_ext');

function novoProfissional($oPdf, $iProfissional, $sNomeProf, $sEstruturalEspec, $sNomeEspec, 
                          $iUnidade, $sNomeUnidade) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->ln(5);

//$oPDF->cell(largura,altura,texto que aparece,borda,quebra linha(bool),posicionamento do texto(L,C,R),cor)
  $oPdf->cell(190, $iTam, 'Profissional: '.$iProfissional.' - '.$sNomeProf , 0, 1, 'L', $lCor);
  $oPdf->cell(190, $iTam, 'Especialidade: '.$sEstruturalEspec.' - '.$sNomeEspec , 0, 1, 'L', $lCor);
  $oPdf->cell(190, $iTam, 'Unidade: '.$iUnidade.' - '.$sNomeUnidade , 0, 1, 'L', $lCor);

}

function novoCabecalho($oPdf) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfillcolor(223);
  
//$oPDF->cell(largura,altura,texto que aparece,borda,quebra linha(bool),posicionamento do texto(L,C,R),cor)
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->cell(190, $iTam, 'Grade de Horário', 0, 1, 'C', false);

  $oPdf->setfont('arial', 'B', 8);
  $oPdf->cell(11, $iTam, 'Código', 1, 0, 'C', $lCor);
  $oPdf->cell(16, $iTam, 'Tipo grade', 1, 0, 'C', $lCor);
  $oPdf->cell(19, $iTam, 'Dia semana', 1, 0, 'C', $lCor);
  $oPdf->cell(16, $iTam, 'Hora início', 1, 0, 'C', $lCor);
  $oPdf->cell(16, $iTam, 'Hora final', 1, 0, 'C', $lCor);
  $oPdf->cell(22, $iTam, 'Número fichas', 1, 0, 'C', $lCor);
  $oPdf->cell(41, $iTam, 'Tipo ficha', 1, 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, 'Reservas', 1, 0, 'C', $lCor);
  $oPdf->cell(17, $iTam, 'Início val.', 1, 0, 'C', $lCor);
  $oPdf->cell(17, $iTam, 'Fim val.', 1, 1, 'C', $lCor);

}

function novaLinha($oPdf, $iCodigo, $sTipoGrade, $sDiaSemana, $sHoraIni, $sHoraFim, $iFichas, 
                   $sTipoFicha, $iReservas, $dValInicio, $dValFim) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 8);

  $oPdf->cell(11, $iTam, $iCodigo, 1, 0, 'C', $lCor);
  $oPdf->cell(16, $iTam, $sTipoGrade, 1, 0, 'C', $lCor);
  $oPdf->cell(19, $iTam, $sDiaSemana, 1, 0, 'C', $lCor);
  $oPdf->cell(16, $iTam, $sHoraIni, 1, 0, 'C', $lCor);
  $oPdf->cell(16, $iTam, $sHoraFim, 1, 0, 'C', $lCor);
  $oPdf->cell(22, $iTam, $iFichas, 1, 0, 'C', $lCor);
  $oPdf->cell(41, $iTam, $sTipoFicha, 1 , 0, 'C', $lCor);
  $oPdf->cell(15, $iTam, $iReservas, 1, 0, 'C', $lCor);
  $oPdf->cell(17, $iTam, $dValInicio, 1, 0, 'C', $lCor);
  $oPdf->cell(17, $iTam, $dValFim, 1, 1, 'C', $lCor);


}

function formataData($dData, $iTipo = 1) {
  
  if (empty($dData)) {
    return '';
  }

  if ($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
 return $dData;

}


$aDatas  = explode(',', $sDatas);
$aDatas2 = array(formataData($aDatas[0]), formataData($aDatas[1]));

if ($iSituacao == 1) {

  $sChar     =  'A';
  $sSituacao = 'Ativo';

} else {

  $sChar     =  'D';
  $sSituacao = 'Inativo';

}

$sWhere  = 'sd04_i_unidade in ('.$sUnidades.") and sd27_c_situacao = '$sChar' ";

if (!empty($sProfissionais)) {
  $sWhere .= 'and sd03_i_codigo in ('.$sProfissionais.') ';
}

$sPeriodo = '';
if (!empty($aDatas2[0]) && !empty($aDatas2[1])) {

  $sWhere  .= "and ((sd30_d_valinicial >= '".$aDatas2[0]."') and (sd30_d_valfinal <= '";
  $sWhere  .= $aDatas[1]."')) ";
  $sPeriodo = $aDatas[0].' a '.$aDatas[1];

} elseif (!empty($aDatas2[0])) {

  $sWhere  .= "and sd30_d_valinicial >= '".$aDatas2[0]."' ";
  $sPeriodo = 'a partir de '.$aDatas[0];

} elseif (!empty($aDatas2[1])) {

  $sWhere  .= "and sd30_d_valfinal <= '".$aDatas[1]."' ";
  $sPeriodo = 'até '.$aDatas[1];

} else { // as duas datas não foram informadas

  $sWhere  .= "and (sd30_d_valfinal is null or sd30_d_valfinal >= '".date('Y-m-d', db_getsession('DB_datausu'))."') ";
  $sPeriodo = 'registros com validade';

}

$sCampos   = 'sd30_i_codigo, sd04_i_unidade, descrdepto, rh70_descr, sd30_d_valinicial, sd30_d_valfinal, ';
$sCampos  .= "case sd30_c_tipograde when 'I' then 'Intervalo' when 'P' then 'Período' ";
$sCampos  .= "else 'Não Informado' end as sd30_c_tipograde, sd101_c_descr, ed32_c_descr, rh70_estrutural, "; 
$sCampos  .= 'sd30_c_horaini, sd30_c_horafim, sd30_i_fichas, sd30_i_reservas, sd03_i_codigo, z01_nome';

$sOrderBy  = 'descrdepto, sd04_i_unidade, z01_nome, sd03_i_codigo, rh70_descr, rh70_estrutural, ';
$sOrderBy .= 'sd30_i_diasemana, sd30_d_valinicial, sd30_c_horaini';

$sSql      = $oDaoUndMedHorario->sql_query_ext(null, $sCampos, $sOrderBy, $sWhere);
//die($sSql);
$rs        = $oDaoUndMedHorario->sql_record($sSql);
$iLinhas   = $oDaoUndMedHorario->numrows;

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

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();

$head1 = 'Grade de Horário de Profissionais de Saúde';
$head2 = '';
$head3 = 'Período: '.$sPeriodo.'.';
$head4 = 'Situação: '.$sSituacao;

$oPdf->Addpage('P'); // L deitado

$iProfissional    = -1;
$sEstruturalEspec = '-1';
$iUnidade         = -1;
for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

  $oDados = db_utils::fieldsmemory($rs, $iCont);

  if ($iProfissional != $oDados->sd03_i_codigo || $sEstruturalEspec != $oDados->rh70_estrutural 
      || $iUnidade != $oDados->sd04_i_unidade) {

    if ($oPdf->getY() > $oPdf->h - 60) {
      $oPdf->Addpage('P');
    }

    novoProfissional($oPdf, $oDados->sd03_i_codigo, $oDados->z01_nome, $oDados->rh70_estrutural, 
                     $oDados->rh70_descr, $oDados->sd04_i_unidade, $oDados->descrdepto
                    );
    novoCabecalho($oPdf);

    $iProfissional    = $oDados->sd03_i_codigo;
    $sEstruturalEspec = $oDados->rh70_estrutural;
    $iUnidade         = $oDados->sd04_i_unidade;

  }

  if ($oPdf->getY() > $oPdf->h - 30) {

    $oPdf->Addpage('P');
    novoCabecalho($oPdf);

  }

  novaLinha($oPdf, $oDados->sd30_i_codigo, $oDados->sd30_c_tipograde, $oDados->ed32_c_descr, 
            $oDados->sd30_c_horaini, $oDados->sd30_c_horafim, $oDados->sd30_i_fichas, 
            $oDados->sd101_c_descr, $oDados->sd30_i_reservas, formataData($oDados->sd30_d_valinicial, 2), 
            formataData($oDados->sd30_d_valfinal, 2)
           );
  
}

$oPdf->Output();
?>