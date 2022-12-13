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

$oDaofar_origemreceitaretirada = db_utils::getdao('far_origemreceitaretirada');

function novoTitulo($oPdf) {

  $lCor = true;
  $oPdf->setfont('arial','B',11);
  $iTam = 5;
  
//$oPDF->cell(largura,altura,texto que aparece,borda,quebra linha(bool),posicionamento do texto(L,C,R),cor)
  $oPdf->cell(60,$iTam,'Origem',1,0,'C',$lCor);
  $oPdf->cell(100,$iTam,'Medicamento',1,0,'C',$lCor);
  $oPdf->cell(30,$iTam,'Quantidade',1,1,'C',$lCor);

}

function novoTitulo2($oPdf) {

  $lCor = true;
  $oPdf->setfont('arial','B',11);
  $iTam = 5;
  
//$oPDF->cell(largura,altura,texto que aparece,borda,quebra linha(bool),posicionamento do texto(L,C,R),cor)
  $oPdf->cell(140,$iTam,'Medicamento',1,0,'C',$lCor);
  $oPdf->cell(50,$iTam,'Quantidade',1,1,'C',$lCor);

}

function novoTituloTotal($oPdf) {

  $lCor = false;
  $oPdf->setfont('arial','B',14);
  $iTam = 10;
  
//$oPDF->cell(largura,altura,texto que aparece,borda,quebra linha(bool),posicionamento do texto(L,C,R),cor)
  $oPdf->cell(190,$iTam,'Totalizações por Medicamento',0,1,'C',$lCor);

}

function novaLinha($oPdf, $iOrigem, $sOrigem, $iMedicamento, $sMedicamento, $iQuantidade, $sUnidade) {

  $lCor = false;
  $oPdf->setfont('arial','',8);

  $iTam = 5;
  
  if(!empty($iOrigem)) {
    $oPdf->cell(60,$iTam,"$iOrigem - $sOrigem",1,0,'L',$lCor);
  } else {
    $oPdf->cell(60,$iTam,'',1,0,'L',$lCor);
  }
  $oPdf->cell(100,$iTam,"$iMedicamento - $sMedicamento",1,0,'L',$lCor);
  $oPdf->cell(30,$iTam,"$iQuantidade $sUnidade",1,1,'C',$lCor);

}

function novaLinha2($oPdf, $iMedicamento, $sMedicamento, $iQuantidade, $sUnidade) {

  $lCor = false;
  $oPdf->setfont('arial','',8);

  $iTam = 5;
  
  $oPdf->cell(140,$iTam,"$iMedicamento - $sMedicamento",1,0,'L',$lCor);
  $oPdf->cell(50,$iTam,"$iQuantidade $sUnidade",1,1,'C',$lCor);

}

function novoTotal($oPdf, $iPacientes, $iMedicamentos) {

  $lCor = false;
  $oPdf->setfont('arial','B',11);
  
  $oPdf->cell(190,1,'','T',1,'C',$lCor);
  $oPdf->cell(190,5,"Total de Pacientes: $iPacientes             Total de Medicamentos: $iMedicamentos ",0,1,'C',$lCor);

}

function formataData($dData, $iTipo = 1) {

  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
 return $dData;

}

$dData_atual = date('Y-m-d', db_getsession('DB_datausu'));
$aDatas = explode(',', $dDatas);

$sWherePeriodo = 'fa04_d_data between \''.formataData($aDatas[0]).'\' and \''.formataData($aDatas[1]).'\'';
$sWhereOrigens = empty($sOrigens) ? '' : " and fa41_i_origemreceita in ($sOrigens)";

$sOrderBy = ' fa41_i_origemreceita, far_retiradaitens.fa06_i_matersaude ';
$sOrderByTotal = ' far_retiradaitens.fa06_i_matersaude ';

$sGroupBy = ' group by far_origemreceitaretirada.fa41_i_origemreceita, far_origemreceita.fa40_c_descr,'. 
            ' far_retiradaitens.fa06_i_matersaude, matmater.m60_descr, matunid.m61_abrev ';
$sGroupByTotal = ' group by far_retiradaitens.fa06_i_matersaude, matmater.m60_descr, matunid.m61_abrev ';

$sCampos =
     " far_origemreceitaretirada.fa41_i_origemreceita,
       far_origemreceita.fa40_c_descr,
       far_retiradaitens.fa06_i_matersaude,
       matmater.m60_descr,
       sum(far_retiradaitens.fa06_f_quant) as fa06_f_quant,
       matunid.m61_abrev ";
$sCamposTotal =
     " far_retiradaitens.fa06_i_matersaude,
       matmater.m60_descr,
       sum(far_retiradaitens.fa06_f_quant) as fa06_f_quant,
       matunid.m61_abrev ";

$sWhere = "$sWherePeriodo $sWhereOrigens $sGroupBy";
$sWhereTotal = "$sWherePeriodo $sWhereOrigens $sGroupByTotal";

$sSql = $oDaofar_origemreceitaretirada->sql_query_retiradas(null, $sCampos, $sOrderBy, $sWhere);
$sSqlTotal = $oDaofar_origemreceitaretirada->sql_query_retiradas(null, $sCamposTotal, $sOrderByTotal, $sWhereTotal);

$rs = $oDaofar_origemreceitaretirada->sql_record($sSql);
$iLinhas = $oDaofar_origemreceitaretirada->numrows;


$rsTotal = $oDaofar_origemreceitaretirada->sql_record($sSqlTotal);
$iLinhasTotal = $oDaofar_origemreceitaretirada->numrows;

if($iLinhas == 0) {
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

$head1 = 'Medicamentos Retirados com Receitas de Origem Externa';
$head2 = '';
$head3 = 'Período: '.$aDatas[0].' a '.$aDatas[1];

$oPdf->Addpage('P'); // L deitado
$oPdf->setfillcolor(223);

$iOrigem = -1;
novoTitulo($oPdf);
$iContQuebra = 1;
for($iCont = 0; $iCont < $iLinhas; $iCont++) {

  $oDados = db_utils::fieldsmemory($rs, $iCont);
  
  if($iOrigem != $oDados->fa41_i_origemreceita) {

    novaLinha($oPdf, $oDados->fa41_i_origemreceita, $oDados->fa40_c_descr, $oDados->fa06_i_matersaude, 
              $oDados->m60_descr, $oDados->fa06_f_quant, $oDados->m61_abrev);
    $iOrigem = $oDados->fa41_i_origemreceita;
    
  } else {

    novaLinha($oPdf, '', '', $oDados->fa06_i_matersaude, 
              $oDados->m60_descr, $oDados->fa06_f_quant, $oDados->m61_abrev);

  }

  $iContQuebra++;
  if($iContQuebra == 47) {

    $oPdf->Addpage('P'); // L deitado
    novoTitulo($oPdf);
    $iContQuebra = 0;
  }
  
}

$oPdf->Addpage('P'); // L deitado
novoTituloTotal($oPdf);

novoTitulo2($oPdf);
$iContQuebra = 3;
for($iCont = 0; $iCont < $iLinhasTotal; $iCont++) {

  $oDados = db_utils::fieldsmemory($rsTotal, $iCont);
  
  novaLinha2($oPdf, $oDados->fa06_i_matersaude, $oDados->m60_descr, $oDados->fa06_f_quant, $oDados->m61_abrev);
    
  $iContQuebra++;
  if($iContQuebra == 47) {

    $oPdf->Addpage('P'); // L deitado
    novoTitulo($oPdf);
    $iContQuebra = 0;
  }
  
}

$oPdf->Output();
?>