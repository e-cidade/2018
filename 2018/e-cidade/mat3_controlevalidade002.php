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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
require("libs/db_app.utils.php");
include("dbforms/db_funcoes.php");

db_app::load("estilos.css");

function novo_cabecalho($sItem) {

  echo "<tr>
          <td colspan='5'>
            &nbsp;
          </td>
        </tr>
        <tr>
          <td colspan='5' align='left'>
            <b>$sItem</b>
          </td>
        </tr>
        <tr bgcolor='#efefef'>
          <td align='center' style=\"border: 1px solid #000000\" nowrap>
            <b>Lote</b>
          </td>
          <td align='center' style=\"border: 1px solid #000000\" nowrap>
            <b>Quantidade</b>
          </td>
          <td align='center' style=\"border: 1px solid #000000\" nowrap>
            <b>Unidade</b>
          </td>
          <td align='center' style=\"border: 1px solid #000000\" nowrap>
            <b>Data de Validade</b>
          </td>
          <td align='center' style=\"border: 1px solid #000000\" nowrap>
            <b>Situa&ccedil;&atilde;o</b>
          </td>
          <td align='center' style=\"border: 1px solid #000000\" nowrap>
            <b>Dias</b>
          </td>
        </tr>";

}

function nova_linha($sLote, $sQuantidade, $sUnidade, $dData_validade, $sSituacao, $iDias) {

  echo '<tr bgcolor=\'#ffffff\'>
          <td align=\'center\' style="border: 1px solid #000000" nowrap>'.$sLote.'</td>
          <td align=\'right\' style="border: 1px solid #000000" nowrap>'.$sQuantidade.'</td>
          <td align=\'left\' style="border: 1px solid #000000" nowrap>'.$sUnidade.'</td>
          <td align=\'center\' style="border: 1px solid #000000" nowrap>'.$dData_validade.'</td>
          <td align=\'center\' style="border: 1px solid #000000" nowrap>'.$sSituacao.'</td>
          <td align=\'center\' style="border: 1px solid #000000" nowrap>'.$iDias.'</td>
        </tr>';

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

$datas = explode(',',$datas);
$dData_inicio = formata_data($datas[0]);
$dData_fim = formata_data($datas[1]);
$data_atual = formata_data($data_atual);

$sWhere_departamento = 'm70_coddepto = '.$departamento.' and ';
$sWhere_materiais = '';
$sWhere_situacao = '';
$sOrder_by = 'order by ';

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

switch($ordenacao) { // switch para a definicao do order by

  case 2:   
    
    $sOrder_by .= 'm60_descr asc'; // ordena pela descricao dos itens
    $sOrdem = 'Alfabetica';
    break;

  default: // case 1
   
    $sOrder_by .= 'm60_codmater asc'; // ordena pelo codigo dos itens
    $sOrdem = 'Codigo';
    break;

}

$sSQL = "select m60_codmater,
                m60_descr,
                m77_dtvalidade,
                m77_lote,
                case when m71_quant is null then 0 else m71_quant - m71_quantatend end as m70_quant,
                m61_descr as unidade,
                case when m77_dtvalidade < '$data_atual'
                       then 'Vencido'
                     when m77_dtvalidade >= '$data_atual'
                       then case when (m77_dtvalidade - '$data_atual') <= $m90_prazovenc
                                   then 'A Vencer'
                                  when (m77_dtvalidade - '$data_atual') > $m90_prazovenc
                                    then 'No prazo'
                            end
                end as situacao,
                m77_dtvalidade - '$data_atual' as dias
           from matmater
             inner join matunid on matunid.m61_codmatunid = matmater.m60_codmatunid
             inner join matestoque on m70_codmatmater = m60_codmater
             inner join db_depart on m70_coddepto = coddepto
             inner join matestoqueitem on m71_codmatestoque = m70_codigo
             inner join matestoqueitemlote on m77_matestoqueitem = m71_codlanc
               where $sWhere_departamento $sWhere_materiais $sWhere_situacao  m60_ativo = 't'
                 and (m77_dtvalidade between '$dData_inicio' and '$dData_fim' or m77_dtvalidade is null)
                  $sOrder_by";

//echo $sSQL;
//exit;
$rs= pg_query($sSQL);
$iLinhas = pg_num_rows($rs);

if($iLinhas == 0)
{
?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado.<br>
            <input type='button' value='Fechar' onclick='parent.db_iframe_controlevalidade.hide()'>
          </b>
        </font>
      </td>
    </tr>
  </table>
<?
  exit;
}
?>

<center>
  <table border='0' cellspacing='0' width='730'>
    <tr>
      <td align='left' nowrap>
        Departamento
      </td>
      <td>
        &nbsp;
      </td>
    </tr>
    <tr>
      <td nowrap width='12%'>
        <?db_input('m60_codmater',10,'Descri&ccedil;&atilde; do material',true,'text',3,'')?>
      </td>
      <td align='left' nowrap>
        <?db_input('m60_descr',75,'Descri&ccedil;&atilde; do material',true,'text',3,'')?>
        <script>
          document.getElementById('m60_descr').value = "<? echo $nome_departamento?>";
          document.getElementById('m60_codmater').value = "<? echo $departamento?>";
        </script>
      </td>
    </tr>
  </table>
  <table width='730' cellspacing='0' border='0'>
   
<?
$iMaterial = '';

for($iCount_linhas = 0; $iCount_linhas < $iLinhas; $iCount_linhas++) {

  db_fieldsmemory($rs,$iCount_linhas);
  
  if($iMaterial != $m60_codmater) {

    $iMaterial = $m60_codmater;
    novo_cabecalho($m60_codmater.' - '.$m60_descr);

  }
  nova_linha(empty($m77_lote) ? '&nbsp;' : $m77_lote ,
             empty($m70_quant) ? '&nbsp;' : $m70_quant,
             empty($unidade) ? '&nbsp;' : $unidade,
             empty($m77_dtvalidade) ? '&nbsp;' : formata_data($m77_dtvalidade,2),
             empty($situacao) ? '&nbsp;' : $situacao,
             empty($dias) ? '&nbsp;' : $dias);
  
}
?>