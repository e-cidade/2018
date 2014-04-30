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

require_once("fpdf151/pdf.php");
require_once("libs/db_utils.php");
//include("fpdf151/pdfwebseller2.php");


function novoTitulo($oPdf, $dIni, $dFim) {

  $lCor = false;
  $oPdf->setfont('arial','B',11);

  if($dIni != $dFim) {
    $sSep = ' a ';
  } else {

    $sSep = '';
    $dFim = '';
    
  }
  
  $oPdf->cell(190,10,'LISTA GERAL: '.$dIni.$sSep.$dFim,0,1,'L',$lCor);

}
/*
function novoSetor($oPdf, $sSetor) {

  $lCor = false;
  $oPdf->setfont('arial','B',10);
  
  $oPdf->ln(5);
  $oPdf->cell(190,10,$sSetor,1,1,'C',$lCor);

  $oPdf->setfont('arial','B',9);

}
*/
function novoPaciente($oPdf, $iNum, $sNome, $iCgs, $iIdade, $sMedico, $sExames, $dData, $iCont,$aCodigoExames,
                                                                  $aCodigoAtributos,$aNomeExames,$iAtributo) {

  $lCor = false;
  $oPdf->setfont('arial','',10);

  $oPdf->cell(190,1,'----------------------------------------------------------------------------------------'.
              "-----------------------------------------------< $dData >--------",0,1,'L',$lCor);
  $oPdf->cell(190,5,"$iCont - Nome: $sNome   $iIdade ano(s)   CGS: $iCgs",0,1,'L',$lCor);
  $oPdf->cell(190,5,"Médico: $sMedico",0,1,'L',$lCor);
  $oPdf->multiCell(190,5,"Exames: $sExames",0,1,'L',$lCor);

  //implementar lista de atributos
  if($iAtributo==1){
  	 $oDaolab_atributoComponente = db_utils::getdao('lab_atributo_componente');
  	 for($x=0;$x<count($aCodigoExames);$x++){
                 $oPdf->setfont('arial','b',10);
  	 	 $oPdf->cell(190,5,"Exame:$aNomeExames[$x] ",1,1,'L',$lCor);
  	 	 $oDaolab_atributoComponente->atributosMapaPDF($oPdf,$aCodigoExames[$x],$aCodigoAtributos[$x],1);
  	 }
  }
  $oPdf->multiCell(190,5," ",0,1,'L',$lCor);
}

function verifica_quebra($oPdf, $iCount_linhas_na_pagina) {

  if($iCount_linhas_na_pagina >= 47) {

    $oPdf->AddPage('P');
    return 0;

  }
  return $iCount_linhas_na_pagina;

}

function formataData($dData, $iTipo = 1) {
  
  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = $dData[2].'/'.$dData[1].'/'.$dData[0];
 return $dData;

}

$oDaolab_requisicao = db_utils::getdao('lab_requisicao');

$datas = explode(',',$datas);
$dData_inicio = formataData($datas[0]);
$dData_fim = formataData($datas[1]);
$dDataAtual = date('Y-m-d', db_getsession('DB_datausu'));

$sCampos = " la21_d_data,
             z01_i_cgsund, 
             z01_v_nome, 
             case 
               when z01_d_nasc is null 
                 then null 
               else
                 fc_idade(z01_d_nasc, '$dDataAtual')
             end as idade,
             z01_nome,
             la22_c_medico,
             la22_i_codigo,
             la08_c_descr,
             la08_i_codigo,
             la42_i_atributo";

$sWhere = " la21_d_data between '$dData_inicio' and '$dData_fim' "; //and la09_i_labsetor = $labsetor ";
if(isset($laboratorio) && $laboratorio!=""){
  $sWhere .= " and la02_i_codigo=$laboratorio";
  
}
if(isset($labsetor) && $labsetor!=""){
  $sWhere .= " and la24_i_codigo=$labsetor";
  
}
if(isset($exame) && $exame!=""){
  $sWhere .= " and la08_i_codigo=$exame";
  
}
$sOrderBy = ' la21_d_data, la22_i_codigo ';

$sSql = $oDaolab_requisicao->sql_query_requiitem(null, $sCampos, $sOrderBy, $sWhere);
$rs = $oDaolab_requisicao->sql_record($sSql);
$iLinhas = $oDaolab_requisicao->numrows;

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

$head1 = "Mapa de trabalho";
$head3 = "Setor: $nomesetor";
if($dData_inicio != $dData_fim) {
  $head4 = 'Período: '.formataData($dData_inicio, 2).' a '.formataData($dData_fim, 2);
} else {
  $head4 = 'Período: '.formataData($dData_inicio, 2);
}

$oPdf->Addpage('P'); // L deitado
if ($iAtributo==1) {

 $alt = $oPdf->getY();
 $oPdf->rect(10,61,190,220,'D');

}
$lCor = false;
$oPdf->setfillcolor(223);
$oPdf->setfont('arial','',11);

novoTitulo($oPdf, formataData($dData_inicio, 2), formataData($dData_fim, 2));

$oDados = db_utils::fieldsmemory($rs,0);
$iRequisicao = $oDados->la22_i_codigo;
$dDataColeta = $oDados->la21_d_data;
$sExames = $oDados->la08_c_descr.';   ';
$aCodigoAtributos = Array();
$aCodigoExames = Array();
$aNomeExames = Array();
$aCodigoExames[] = $oDados->la08_i_codigo;
$aCodigoAtributos[] = $oDados->la42_i_atributo;
$aNomeExames[] = $oDados->la08_c_descr;
$iLinhas2 = $iLinhas + 1;
$iNum = 1;

for($iCont = 0; $iCont < $iLinhas2; $iCont++) {

  if($iCont < $iLinhas) {
    $oDados2 = db_utils::fieldsmemory($rs, $iCont);
  } else {
    $iRequisicao = -1;
  }

  if($iRequisicao != $oDados2->la22_i_codigo || $dDataColeta != $oDados2->la21_d_data) {

    $iRequisicao = $oDados2->la22_i_codigo;
    $dDataColeta = $oDados2->la21_d_data;
    $sMedico = empty($oDados->z01_nome) ? $oDados->la22_c_medico : $oDados->z01_nome;
    
    novoPaciente($oPdf, $iCont + 1, $oDados->z01_v_nome, $oDados->z01_i_cgsund, $oDados->idade, $sMedico,
                 $sExames, formataData($oDados->la21_d_data, 2),$iNum,$aCodigoExames,$aCodigoAtributos,
                                                                                    $aNomeExames,$iAtributo);
    $oPdf->Addpage('P');
    if ($iAtributo==1) {

      $alt = $oPdf->getY();
      $oPdf->rect(10,51,190,230,'D');

    }
    $sExames = '';
    $aCodigoAtributos = Array();
    $aNomeExames = Array();
    $aCodigoExames = Array();
    $iNum++;

  } 
  $oPdf->setfont('arial','',11); 
  $oDados = $oDados2;
  if($iCont != 0) {
    $sExames .= $oDados->la08_c_descr.';   ';
    $aNomeExames[] = $oDados->la08_c_descr;
    $aCodigoExames[] = $oDados->la08_i_codigo;
    $aCodigoAtributos[] = $oDados->la42_i_atributo;
  }
 
}
$oPdf->Output();
?>