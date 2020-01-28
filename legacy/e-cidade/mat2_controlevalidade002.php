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

require_once("fpdf151/pdf.php");
require_once("classes/materialestoque.model.php");
require_once("libs/db_utils.php");

function novo_departamento($oPDF, $sNome) {

  $lCor = false;
  $oPDF->setfont('arial','B',11);
  $oPDF->ln(5);
  $oPDF->cell(190,15,'Almoxarifado: '.$sNome,0,1,'L',$lCor);
}

function novo_material($oPDF, $sMaterial) {

  $lCor = false;
  $oPDF->setfont('arial','B',10);
  $oPDF->ln(5);
  $oPDF->cell(190,10,'Item: '.$sMaterial,1,1,'C',$lCor);
  $oPDF->setfont('arial','B',9);
}

function novo_cabecalho1($oPDF) {

  $lCor = false;
  $oPDF->setfont('arial','B',10);
  $oPDF->cell(25,5,'Lote',1,0,'C',$lCor);
  $oPDF->cell(25,5,'Qtde. Estoque',1,0,'C',$lCor);
  $oPDF->cell(75,5,'Unidade',1,0,'C',$lCor);
  $oPDF->cell(25,5,'Data Validade',1,0,'C',$lCor);
  $oPDF->cell(20,5,'Situacao',1,0,'C',$lCor);
  $oPDF->cell(20,5,'Dias',1,1,'C',$lCor);
}

function novo_cabecalho2($oPDF) {

  $lCor = false;
  $oPDF->setfont('arial','B',10);

  $oPDF->cell(65,5,'Item',1,0,'C',$lCor);
  $oPDF->cell(25,5,'Lote',1,0,'C',$lCor);
  $oPDF->cell(25,5,'Qtde. Estoque',1,0,'C',$lCor);
  $oPDF->cell(30,5,'Unidade',1,0,'C',$lCor);
  $oPDF->cell(20,5,'D. Validade',1,0,'C',$lCor);
  $oPDF->cell(15,5,'Situacao',1,0,'C',$lCor);
  $oPDF->cell(10,5,'Dias',1,1,'C',$lCor);

}



function nova_linha1($oPDF, $sLote, $sQuantidade, $sUnidade, $dData_validade, $sSituacao, $iDias) {

  $lCor = false;
  $oPDF->setfont('arial','',9);

  //$oPDF->cell(largura,altura,texto que aparece,borda(bool),quebra linha(bool),posicionamento do texto(L,C,R),cor)
  $oPDF->cell(25,5,$sLote,1,0,'C',$lCor);
  $oPDF->cell(25,5,$sQuantidade,1,0,'C',$lCor);
  $oPDF->cell(75,5,$sUnidade,1,0,'C',$lCor);
  $oPDF->cell(25,5,$dData_validade,1,0,'C',$lCor);
  $oPDF->cell(20,5,$sSituacao,1,0,'C',$lCor);
  $oPDF->cell(20,5,$iDias,1,1,'C',$lCor);

}

function nova_linha2($oPDF, $sItem, $sLote, $sQuantidade, $sUnidade, $dData_validade, $sSituacao, $iDias) {

  $lCor = false;
  $oPDF->setfont('arial','',9);

  //$oPDF->cell(largura,altura,texto que aparece,borda(bool),quebra linha(bool),posicionamento do texto(L,C,R),cor)
  $oPDF->cell(65,5,$sItem,1,0,'L',$lCor);
  $oPDF->cell(25,5,$sLote,1,0,'C',$lCor);
  $oPDF->cell(25,5,$sQuantidade,1,0,'C',$lCor);
  $oPDF->cell(30,5,$sUnidade,1,0,'C',$lCor);
  $oPDF->cell(20,5,$dData_validade,1,0,'C',$lCor);
  $oPDF->cell(15,5,$sSituacao,1,0,'C',$lCor);
  $oPDF->cell(10,5,$iDias,1,1,'C',$lCor);

}

function verifica_quebra($oPDF, $iCount_linhas_na_pagina) {

  if($iCount_linhas_na_pagina >= 47) {

    $oPDF->AddPage('P');
    return 0;
  }
  return $iCount_linhas_na_pagina;
}

function formata_data($dData, $iTipo = 1) {

  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  }

  $dData = explode('-',$dData);
  $dData = $dData[2].'/'.$dData[1].'/'.$dData[0];
  return $dData;
}

$departamentos = explode(',',$departamentos);
$nomes_departamentos = str_replace(',',', ',$nomes_departamentos);
$datas = explode(',',$datas);
$dData_inicio = formata_data($datas[0]);
$dData_fim = formata_data($datas[1]);
$data_atual = formata_data($data_atual);

$sWhere_departamentos = '( ';
$sWhere_materiais = '';
$sWhere_situacao = '';
$sWhere_zerado = '';
$sOrder_by = 'order by m70_coddepto, ';

for($i = 0; $i < count($departamentos) - 1 ; $i++) { // for para a definicao do where_departamentos

  $sWhere_departamentos .= 'm70_coddepto = '.$departamentos[$i].' or ';

}
$sWhere_departamentos .= 'm70_coddepto = '.$departamentos[$i].' ) and ';

if(!empty($materiais)) {

  $sWhere_materiais = '( ';
  $materiais = explode(',',$materiais);
  for($i = 0; $i < count($materiais) - 1; $i++) { // for para a definicao do $where_materiais
    $sWhere_materiais .= 'm60_codmater = '.$materiais[$i].' or ';
  }
  $sWhere_materiais .= 'm60_codmater = '.$materiais[$i].' ) and ';
}

switch($situacao) { // switch para definicao do where_situacao

case 2:

  $sWhere_situacao .= "m77_dtvalidade < '$data_atual' and "; // Vencidos
  break;

case 3:

  $sWhere_situacao .= "(m77_dtvalidade - '$data_atual') <= $m90_prazovenc and (m77_dtvalidade - '$data_atual') > -1 and "; // A vencer
  break;

case 4:

  $sWhere_situacao .= "(m77_dtvalidade - '$data_atual') > $m90_prazovenc and "; // No prazo
  break;

default: // todas as situacoes, portanto, nao ha filtro
  break;

}

if($zerado == 1) {

  $sWhere_zerado .= " ( case when m84_quantidade is null then";
  $sWhere_zerado .= "       m71_quant - m71_quantatend";
  $sWhere_zerado .= "     else (m71_quant - m71_quantatend) + m84_quantidade";
  $sWhere_zerado .= "     end";
  $sWhere_zerado .= ") > 0 and ";
}

switch($ordenacao) { // switch para a definicao do order by

case 1:

  $sOrder_by .= 'm77_dtvalidade desc'; // ordena pela data de validade
  $sOrdem = 'Data de Validade';
  break;

case 2:

  $sOrder_by .= 'm77_dtvalidade asc'; // ordena pela situacao
  $sOrdem = 'Situacao';
  break;

case 3:

  $sOrder_by .= 'm77_lote asc'; // ordena pelo lote
  $sOrdem = 'Lote';
  break;

case 4:

  $sOrder_by .= 'm60_descr asc'; // ordena pela descricao dos itens
  $sOrdem = 'Alfabetica';
  break;

default: // case 5

  $sOrder_by .= 'm60_codmater asc'; // ordena pelo codigo dos itens
  $sOrdem = 'Codigo';
  break;

}

$sSQL  = "select m60_codmater,";
$sSQL .= "       m60_descr,";
$sSQL .= "       m70_coddepto,";
$sSQL .= "       descrdepto,";
$sSQL .= "       m77_dtvalidade,";
$sSQL .= "       m77_lote,";
$sSQL .= "       case";
$sSQL .= "       when m71_quant is null then 0";
$sSQL .= "       else";
$sSQL .= "         case";
$sSQL .= "           when m84_quantidade is null then";
$sSQL .= "             ( m71_quant - m71_quantatend )";
$sSQL .= "           else";
$sSQL .= "             coalesce (( m71_quant - m71_quantatend ) + m84_quantidade, 0)";
$sSQL .= "         end";
$sSQL .= "       end                           as m70_quant,";
$sSQL .= "       m61_descr                     as unidade,";
$sSQL .= "       case when m77_dtvalidade < '{$data_atual}' then";
$sSQL .= "         'Vencido'";
$sSQL .= "         when m77_dtvalidade >= '{$data_atual}' then";
$sSQL .= "           case when (m77_dtvalidade - '{$data_atual}') <= {$m90_prazovenc} then";
$sSQL .= "             'A Vencer'";
$sSQL .= "           when (m77_dtvalidade - '{$data_atual}') > {$m90_prazovenc} then";
$sSQL .= "             'No prazo'";
$sSQL .= "           end";
$sSQL .= "       end as situacao,";
$sSQL .= "       m70_codigo,";
$sSQL .= "       m77_dtvalidade - '{$data_atual}' as dias";
$sSQL .= "  from matmater";
$sSQL .= "       inner join matunid                 on matmater.m60_codmatunid    = matunid.m61_codmatunid";
$sSQL .= "       inner join matestoque              on matmater.m60_codmater      = matestoque.m70_codmatmater";
$sSQL .= "       inner join db_depart               on matestoque.m70_coddepto    = db_depart.coddepto";
$sSQL .= "       inner join matestoqueitem          on matestoque.m70_codigo      = matestoqueitem.m71_codmatestoque";
$sSQL .= "       inner join matestoqueitemlote      on matestoqueitem.m71_codlanc = matestoqueitemlote.m77_matestoqueitem";
$sSQL .= "       left  join matestoquetransferencia on matestoqueitem.m71_codlanc = matestoquetransferencia.m84_matestoqueitem";
$sSQL .= "                                         and matestoquetransferencia.m84_transferido is false";
$sSQL .= "                                         and matestoquetransferencia.m84_ativo is true";
$sSQL .= " where matestoqueitemlote.m77_dtvalidade is not null";
$sSQL .= "   and {$sWhere_departamentos} {$sWhere_materiais} {$sWhere_situacao} {$sWhere_zerado} matmater.m60_ativo is true";
$sSQL .= "   and m77_dtvalidade between '{$dData_inicio}' and '{$dData_fim}'";
$sSQL .= "  {$sOrder_by}";
$rs      = db_query($sSQL);


$iLinhas = pg_num_rows($rs);

if($iLinhas == 0 || count($departamentos) <= 0) {
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
<?php
  exit;
}

$oPDF = new PDF();
$oPDF->Open();
$oPDF->AliasNbPages();

$head1 = 'Relatorio Controle de Validade';
$head2 = 'Data da Situacao: '.formata_data($data_atual,2);
$head3 = 'Departamento(s): '.$nomes_departamentos;
$head4 = 'Ordem - '.$sOrdem;

$oPDF->Addpage('P'); // L deitado
$lCor = false;
$oPDF->setfillcolor(223);
$oPDF->setfont('arial','',11);

$iCount_linhas_na_pagina = 0;
$iDepartamento = '';
$iMaterial = '';

for($iCount_linhas = 0; $iCount_linhas < $iLinhas; $iCount_linhas++) {

  db_fieldsmemory($rs,$iCount_linhas);

  if($iDepartamento != $m70_coddepto) {

    $iDepartamento = $m70_coddepto;

    if($quebra == 1) {
      $iCount_linhas_na_pagina += 8;
    } else {
      $iCount_linhas_na_pagina += 5;
    }

    $iCount_linhas_na_pagina = verifica_quebra($oPDF, $iCount_linhas_na_pagina);
    novo_departamento($oPDF,$m70_coddepto.' - '.$descrdepto);

    if($iCount_linhas_na_pagina == 0) {
      $iCount_linhas_na_pagina = 4;
    } else {

      if($quebra == 1) {
        $iCount_linhas_na_pagina -= 4;
      } else {
        novo_cabecalho2($oPDF);
      }
    }

  }

  if($iMaterial != $m60_codmater && $quebra == 1) {

    $iMaterial = $m60_codmater;
    $iCount_linhas_na_pagina += 4;
    $iCount_linhas_na_pagina = verifica_quebra($oPDF, $iCount_linhas_na_pagina);
    novo_material($oPDF,$m60_codmater.' - '.$m60_descr);
    novo_cabecalho1($oPDF);

    if($iCount_linhas_na_pagina == 0) {
      $iCount_linhas_na_pagina = 4;
    }

  }

  if($quebra == 2 && $iCount_linhas_na_pagina == 0) {

    $iCount_linhas_na_pagina += 1;
    $iCount_linhas_na_pagina = verifica_quebra($oPDF, $iCount_linhas_na_pagina);
    novo_cabecalho2($oPDF);

    if($iCount_linhas_na_pagina == 0) {
      $iCount_linhas_na_pagina = 1;
    }

  }

  if($iMaterial == $m60_codmater && $iCount_linhas_na_pagina == 0) {

    if($quebra == 1) {

      $iCount_linhas_na_pagina = 4;
      novo_material($oPDF,$m60_codmater.' - '.$m60_descr);
      novo_cabecalho1($oPDF);

    }

  }

  if($quebra == 1) {
    nova_linha1($oPDF,$m77_lote,$m70_quant,$unidade,formata_data($m77_dtvalidade,2),$situacao,$dias);
  } else {

    $sItem = $m60_codmater.' - '.$m60_descr;
    while($oPDF->GetStringWidth($sItem) > 65) { // while que corta a string no tamanho disponivel se ela for maior

      $sItem = substr($sItem,0,strlen($sItem) - 2);

    }
    nova_linha2($oPDF,$sItem,$m77_lote,$m70_quant,$unidade,formata_data($m77_dtvalidade,2),$situacao,$dias);
  }

  $iCount_linhas_na_pagina++;
  $iCount_linhas_na_pagina = verifica_quebra($oPDF, $iCount_linhas_na_pagina);

}
$oPDF->Output();
?>