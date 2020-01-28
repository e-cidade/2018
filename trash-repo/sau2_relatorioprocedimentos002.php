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

$oDaoProntProced = db_utils::getdao('prontproced');
function novoProfissional($oPdf, $iProfissional, $sNomeProf, $iUnidade, $sNomeUnidade, $iAgrupar) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 9);
  $oPdf->ln(5);
  if ($iAgrupar == '1') {

    $oPdf->cell(190, $iTam, 'Unidade: '.$iUnidade.' - '.$sNomeUnidade , 0, 1, 'L', $lCor);
    $oPdf->cell(190, $iTam, 'Profissional: '.$iProfissional.' - '.$sNomeProf , 0, 1, 'L', $lCor);

  } else {

    $oPdf->cell(190, $iTam, 'Profissional: '.$iProfissional.' - '.$sNomeProf , 0, 1, 'L', $lCor);
    $oPdf->cell(190, $iTam, 'Unidade: '.$iUnidade.' - '.$sNomeUnidade , 0, 1, 'L', $lCor);

  }

}

function novoProcedimento($oPdf, $sEstrutural, $sNome, $lImprimirTotal = true, $iTotal = null) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 9);

  if ($lImprimirTotal) {

    $oPdf->MultiCell(190, $iTam, 'Procedimento: '.$sEstrutural.' - '.$sNome."\nTotal de Registros: ".$iTotal, 
                     0, 1, 'L', false
                    );

  } else {
    $oPdf->MultiCell(190, $iTam, 'Procedimento: '.$sEstrutural.' - '.$sNome, 0, 1, 'L', false);
  }

}

function novoCabecalhoPaciente($oPdf) {

  $lCor = true;
  $iTam = 5;
  $oPdf->setfillcolor(223);
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->cell(30, $iTam, 'Ficha de Atendimento', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Data', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'Hora', 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, 'CGS', 1, 0, 'C', $lCor);
  $oPdf->cell(100, $iTam, 'Paciente', 1, 1, 'L', $lCor);

}

function novoPaciente($oPdf, $iFaa, $dData, $sHora,  $iCgs, $sNome) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(30, $iTam, $iFaa, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $dData, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $sHora, 1, 0, 'C', $lCor);
  $oPdf->cell(20, $iTam, $iCgs, 1, 0, 'C', $lCor);
  $oPdf->cell(100, $iTam, $sNome, 1, 1, 'L', $lCor);

}

function novoTotalProcedimento($oPdf, $iTotal) {

  $lCor = false;
  $iTam = 5;
  $oPdf->setfont('arial', 'B', 8);
  $oPdf->cell(150, $iTam, '', 'LTB', 0, 'C', $lCor);
  $oPdf->cell(40, $iTam, 'Total de Registros: '.$iTotal, 'RTB', 1, 'L', $lCor);
  $oPdf->ln(3);

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

$aDatas   = explode(',', $sDatas);
$aDatas2  = array(formataData($aDatas[0]), formataData($aDatas[1]));

$sOrderBy = $iAgrupar == 1 ? ' coddepto ' : ' z01_nome ';
if ($iOrdem == 1) {

  $sOrderBy .= ' ,sd63_c_procedimento, sd29_d_data, sd29_c_hora ';

} else {

  $sOrderBy .= ' ,sd63_c_procedimento, z01_v_nome ';

}

$sWhere  = " sd29_d_data between '".$aDatas2[0]."' and '".$aDatas2[1]."'";
$sWhere .= " and coddepto in ($sUnidades)";
if (!empty($sProfissionais)) {
  $sWhere .= " and sd03_i_codigo in ($sProfissionais)";
}

if (!empty($sProcedimentos)) {
   
  $sProcedimentos = str_replace('|', "'", $sProcedimentos);
  $sWhere .= " and sd63_c_procedimento in ($sProcedimentos)";

}
$sCampos  = ' sau_procedimento.sd63_c_procedimento, sau_procedimento.sd63_c_nome, medicos.sd03_i_codigo,';
$sCampos .= ' cgm.z01_nome, db_depart.coddepto, db_depart.descrdepto, prontuarios.sd24_i_codigo,';
$sCampos .= ' prontproced.sd29_d_data, prontproced.sd29_c_hora, cgs_und.z01_i_cgsund, cgs_und.z01_v_nome ';

$sSql     = $oDaoProntProced->sql_query_procedimentos(null, $sCampos, $sOrderBy, $sWhere);
$sSql     = 'declare pCursor cursor for '.$sSql; 

db_query('begin');
db_query($sSql);
$rs      = db_query('fetch forward 1000 from pCursor;');
$iLinhas = pg_numrows($rs);
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

$head1 = 'Profissional x Unidade x Procedimento';
$head2 = '';
$head3 = 'Período: '.$aDatas[0].' a '.$aDatas[1];

$oPdf->Addpage('P'); // L deitado

$lImprimirPacientes = $iPacientes == 1 ? true : false;

$iProfissional      = -1;
$sProcedimento      = '-1';
$iUnidade           = -1;
$iTotalProced       = 0;

while($iLinhas > 0) {

  for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

    $oDados = db_utils::fieldsmemory($rs, $iCont);
  
    if ($iProfissional != $oDados->sd03_i_codigo || $iUnidade != $oDados->coddepto) {
  
      if ($lImprimirPacientes && $iProfissional != -1) {
        
        novoTotalProcedimento($oPdf, $iTotalProced);
        $iTotalProced = 0;
      
      }
  
      if ($oPdf->getY() > $oPdf->h - 60) {
        $oPdf->Addpage('P');
      }
      novoProfissional($oPdf, $oDados->sd03_i_codigo, $oDados->z01_nome, $oDados->coddepto, 
                       $oDados->descrdepto, $iAgrupar
                      );
  
      $iProfissional = $oDados->sd03_i_codigo;
      $sProcedimento = $oDados->sd63_c_procedimento;
      $iUnidade      = $oDados->coddepto;
  
      if (!$lImprimirPacientes) {
  
        
        while ($iProfissional == $oDados->sd03_i_codigo 
               && $iUnidade == $oDados->coddepto
               && $sProcedimento == $oDados->sd63_c_procedimento) {
         
          $iCont++;
          if ($iCont >= $iLinhas) {
  
            $rs      = db_query('fetch forward 1000 from pCursor;');
            $iLinhas = pg_numrows($rs);
            $iCont   = 0;
            if ($iLinhas == 0) {

              $iTotalProced++;
              break;

            }
  
          }
          $oDadosTmp     = db_utils::fieldsmemory($rs, $iCont);
          $iProfissional = $oDadosTmp->sd03_i_codigo;
          $sProcedimento = $oDadosTmp->sd63_c_procedimento;
          $iUnidade      = $oDadosTmp->coddepto;
          $iTotalProced++;
  
        }
  
        novoProcedimento($oPdf, $oDados->sd63_c_procedimento, $oDados->sd63_c_nome, 
                         !$lImprimirPacientes, $iTotalProced
                        );
        $iProfissional = -1;
        $sProcedimento = '-1';
        $iUnidade      = -1;
        $iTotalProced  = 0;
        $iCont--;
        continue;
  
      } else {
  
        novoProcedimento($oPdf, $oDados->sd63_c_procedimento, $oDados->sd63_c_nome, !$lImprimirPacientes);
        novoCabecalhoPaciente($oPdf);
  
      }
  
    }
    
    /* Só chega aqui se for imprimir pacientes */
    if ($oPdf->getY() > $oPdf->h - 30) {
  
      $oPdf->Addpage('P');
      if ($lImprimirPacientes) {
  
        novoCabecalhoPaciente($oPdf);
  
      }
  
    }
  
    if ($sProcedimento != $oDados->sd63_c_procedimento) {
  
      novoTotalProcedimento($oPdf, $iTotalProced);
      novoProcedimento($oPdf, $oDados->sd63_c_procedimento, $oDados->sd63_c_nome, !$lImprimirPacientes);
      $iTotalProced  = 0;
      $sProcedimento = $oDados->sd63_c_procedimento;
  
    }
    
    novoPaciente($oPdf, $oDados->sd24_i_codigo, formataData($oDados->sd29_d_data, 2), $oDados->sd29_c_hora, 
                 $oDados->z01_i_cgsund, $oDados->z01_v_nome 
                );
    $iTotalProced++;

  }

  if ($iLinhas != 0) {

    $rs      = db_query('fetch forward 1000 from pCursor;');
    $iLinhas = pg_numrows($rs);

  }
  
}

db_query('end');

if ($lImprimirPacientes) {
  novoTotalProcedimento($oPdf, $iTotalProced);
}

$oPdf->Output();
?>