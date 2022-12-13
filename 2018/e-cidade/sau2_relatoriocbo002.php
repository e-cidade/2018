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

$oDaoProntproced = db_utils::getdao('prontproced');

function novoTitulo($oPdf, $sTitulo) {

  $lCor = false;
  $oPdf->setfont('arial','B',12);
  $iTam = 8;
  
  $oPdf->cell(279, $iTam, $sTitulo, 0, 1, 'C', $lCor);

}

function novoCabecalho($oPdf, $lMostraProfissional) {

  $oPdf->setfont('arial','B',10);
  $lCor = true;
  $iTam = 5;
  
  $oPdf->cell(15, $iTam, 'CBO', 1, 0, 'L', $lCor);
  $oPdf->cell(70, $iTam, 'Descrição', 1, 0, 'L', $lCor);

  if ($lMostraProfissional) {

    $oPdf->cell(125, $iTam, 'Procedimento', 1, 0, 'L', $lCor);
    $oPdf->cell(69, $iTam, 'Profissional', 1, 1, 'L', $lCor);

  } else {
    $oPdf->cell(194, $iTam, 'Procedimento', 1, 1, 'L', $lCor);
  }

}

function novaLinha($oPdf, $sCbo, $sNomeCbo, $sProcedimento, $lMostraProfissional, $sProfissional) {

  $oPdf->setfont('arial', '', 8);
  $lCor = false;
  $iTam = 5;
  
  $oPdf->cell(15, $iTam, $sCbo, 1, 0, 'L', $lCor);
  $oPdf->cell(70, $iTam, substr($sNomeCbo, 0, 52), 1, 0, 'L', $lCor);

  if ($lMostraProfissional) {

    $oPdf->cell(125, $iTam, substr($sProcedimento, 0 , 72), 1, 0, 'L', $lCor);
    $oPdf->cell(69, $iTam, $sProfissional, 1, 1, 'L', $lCor);

  } else {
    $oPdf->cell(194, $iTam, $sProcedimento, 1, 1, 'L', $lCor);
  }

}

function novoTotal($oPdf, $iTotalEspecialidades, $iTotalProcedimentos, $lMostraProfissional, $iTotalProfissionais) {

  $oPdf->setfont('arial', 'B', 8);
  $lCor = false;
  $iTam = 4;

  $oPdf->cell(210, $iTam, 'TOTAL DE PROCEDIMENTOS: ' , 0, 0, 'R', $lCor);
  $oPdf->cell(69, $iTam, $iTotalProcedimentos, 0, 1, 'L', $lCor);
  $oPdf->cell(210, $iTam, 'TOTAL DE ESPECIALIDADES: ' , 0, 0, 'R', $lCor);
  $oPdf->cell(69, $iTam, $iTotalEspecialidades, 0, 1, 'L', $lCor);

  if ($lMostraProfissional) {

    $oPdf->cell(210, $iTam, 'TOTAL DE PROFISSIONAIS: ' , 0, 0, 'R', $lCor);
    $oPdf->cell(69, $iTam, $iTotalProfissionais, 0, 1, 'L', $lCor);

  }

}

function formataData($dData, $iTipo = 1) {

  if(empty($dData)) {
    return '';
  }

  if($iTipo == 1) {

    $dData = explode('/',$dData);
    $dData = $dData[2].'-'.$dData[1].'-'.$dData[0];
    return $dData;
  
  }
 
 $dData = explode('-',$dData);
 $dData = @$dData[2].'/'.@$dData[1].'/'.@$dData[0];
 return $dData;

}

$dIniBd   = formataData($dataIni);
$dFimBd   = formataData($dataFim);

$sCampos  = ' distinct on (sau_financiamento.sd65_c_financiamento, rhcbo.rh70_estrutural,';
$sCampos .= ' sau_procedimento.sd63_c_procedimento, medicos.sd03_i_codigo)';
$sCampos .= ' rhcbo.rh70_estrutural,';
$sCampos .= ' rhcbo.rh70_descr,';
$sCampos .= ' sau_procedimento.sd63_c_procedimento,';
$sCampos .= ' sau_procedimento.sd63_c_nome,';
$sCampos .= ' sau_financiamento.sd65_c_financiamento,';
$sCampos .= ' medicos.sd03_i_codigo,';
$sCampos .= ' cgm.z01_nome ';

$sWhere   = " prontproced.sd29_d_data between '$dIniBd' and '$dFimBd' ";
// Se o filtro for PAB
if ($pab == 1) {
  $sWhere .= " and sau_financiamento.sd65_c_financiamento = '01' ";
}
// Se o filtro for NÃO PAB
if ($pab == 2) {
  $sWhere .= " and sau_financiamento.sd65_c_financiamento <> '01' ";
}

$sOrderBy  = ' sau_financiamento.sd65_c_financiamento, rhcbo.rh70_estrutural,';
$sOrderBy .= ' sau_procedimento.sd63_c_procedimento, medicos.sd03_i_codigo ';

$sSql      = $oDaoProntproced->sql_query_relatorio_cbo(null, $sCampos, $sOrderBy, $sWhere);
//die($sSql);
$rs       = $oDaoProntproced->sql_record($sSql);
$iLinhas  = $oDaoProntproced->numrows;

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

$head1 = 'Relatório CBO';
$head2 = '';
$head3 = 'Período: '.$dataIni.' - '.$dataFim;
$oPdf->Addpage('L');
$oPdf->setfillcolor(223);

$iTotal         = 0;
$lImprimeTitulo = $pab == 3 ? true : false; // controla se algum titulo ainda pode ser impresso
$oDados         = db_utils::fieldsmemory($rs, 0);
$lProfissional  = $mostrarProfissional == 1 ? true : false; // controla se algum profissional será enviado

if ($oDados->sd65_c_financiamento == '01') {
  novoTitulo($oPdf, 'PROCEDIMENTOS PAB');
} else {

  novoTitulo($oPdf, 'PROCEDIMENTOS NÃO PAB');
  $lImprimeTitulo = false;

}

$sProcedimento       = '';
$sEspecialidade      = '';
$iContEspecialidades = 0;

novoCabecalho($oPdf, $lProfissional);
for ($iCont = 0; $iCont < $iLinhas; $iCont++) {

  $oDados = db_utils::fieldsmemory($rs, $iCont);

  /* 
     A única ocasião em que um novo título poderá ser impresso é quando não foi realizado
     filtro por PAB nem NÃO PAB, ou seja, ambos foram selecionados. Então somente quando for
     encontrado um registro com financiamento NÃO PAB (sd65_i_financiamento != '01') 
     o título será impresso novamente 
  */
  if ($lImprimeTitulo && $oDados->sd65_c_financiamento != '01') {
    
    /* busco o total de procedimentos PAB */
    $sCampos             = ' distinct on (sau_procedimento.sd63_c_procedimento) sd63_c_procedimento ';
    $sWhere2             = " and sau_financiamento.sd65_c_financiamento = '01' ";
    $sSql                = $oDaoProntproced->sql_query_relatorio_cbo(null, $sCampos, 
                                                                     ' sau_procedimento.sd63_c_procedimento',
                                                                     $sWhere.$sWhere2
                                                                    );
    $oDaoProntproced->sql_record($sSql);
    $iTotalProcedimentos = $oDaoProntproced->numrows;

    /* busco o total de profissionais que realizaram procedimentos PAB */
    $iTotalProfissionais = 0;
    if ($lProfissional) {

      $sCampos             = ' distinct on (medicos.sd03_i_codigo) sd03_i_codigo ';
      $sSql                = $oDaoProntproced->sql_query_relatorio_cbo(null, $sCampos, 
                                                                       ' medicos.sd03_i_codigo',
                                                                       $sWhere.$sWhere2
                                                                      );
      $oDaoProntproced->sql_record($sSql);
      $iTotalProfissionais = $oDaoProntproced->numrows;

    }

    novoTotal($oPdf, $iContEspecialidades, $iTotalProcedimentos, $lProfissional, $iTotalProfissionais);

    $oPdf->Addpage('L');
    novoTitulo($oPdf, 'PROCEDIMENTOS NÃO PAB');
    novoCabecalho($oPdf, $lProfissional);
    $lImprimeTitulo      = false;
    $iContEspecialidades = 0;
    $sEspecialidade      = '';
    $pab                 = 2; // quando sair do for vai fazer o total dos Não Pab

  }

  if ($oPdf->getY() >$oPdf->h - 30) {

    $oPdf->Addpage('L');
    novoCabecalho($oPdf, $lProfissional);

  }

  // if que impede a impressão do mesmo CBO mais de uma vez
  if ($sEspecialidade == $oDados->rh70_estrutural) {

    $sEstruturalImpressao    = '';
    $sEspecialidadeImpressao = '';

  } else {

    $sEstruturalImpressao    = $oDados->rh70_estrutural;
    $sEspecialidadeImpressao = $oDados->rh70_descr;
    $iContEspecialidades++;
   
    /* quando muda a especialidade o procedimento deve ser impresso mesmo que seja igual ao anterior */
    $sProcedimento           = '';

  }
  // if que impede a impressão do mesmo procedimento mais de uma vez
  if ($sProcedimento == $oDados->sd63_c_procedimento) {
    $sProcedImpressao = '';
  } else {
    $sProcedImpressao = $oDados->sd63_c_procedimento.' - '.$oDados->sd63_c_nome;
  }

  novaLinha($oPdf, $sEstruturalImpressao, $sEspecialidadeImpressao, $sProcedImpressao, 
            $lProfissional, $oDados->z01_nome
           );

  $sProcedimento  = $oDados->sd63_c_procedimento;
  $sEspecialidade = $oDados->rh70_estrutural;

  if (!$lProfissional) {

    /* Laço que elimina os registros com os mesmos procedimentos dentro de uma especialidade, pois nestes
       registros só o que difere é o profissional e neste if só entra se o profissional não for exibido. */
    while ($sProcedimento == $oDados->sd63_c_procedimento && $sEspecialidade == $oDados->rh70_estrutural) {
    
      $iCont++;
      if ($iCont < $iLinhas) {
        $oDados = db_utils::fieldsmemory($rs, $iCont);
      } else {
        break;
      }

    }
    $iCont--; // diminuo 1 porque o $iCont será acrescido de 1 no fim do laço for

  }

}

/* busco o total de procedimentos  */
$sCampos             = ' distinct on (sau_procedimento.sd63_c_procedimento) sd63_c_procedimento ';
if ($pab == 1) {
  $sWhere2             = " and sau_financiamento.sd65_c_financiamento = '01' ";
} else {
  $sWhere2             = " and sau_financiamento.sd65_c_financiamento <> '01' ";
}
$sSql                = $oDaoProntproced->sql_query_relatorio_cbo(null, $sCampos, 
                                                                 ' sau_procedimento.sd63_c_procedimento',
                                                                 $sWhere.$sWhere2
                                                                );
$oDaoProntproced->sql_record($sSql);
$iTotalProcedimentos = $oDaoProntproced->numrows;

/* busco o total de profissionais que realizaram os procedimentos */
$iTotalProfissionais = 0;
if ($lProfissional) {

  $sCampos             = ' distinct on (medicos.sd03_i_codigo) sd03_i_codigo ';
  $sSql                = $oDaoProntproced->sql_query_relatorio_cbo(null, $sCampos, 
                                                                   ' medicos.sd03_i_codigo',
                                                                   $sWhere.$sWhere2
                                                                  );
  $oDaoProntproced->sql_record($sSql);
  $iTotalProfissionais = $oDaoProntproced->numrows;

}
novoTotal($oPdf, $iContEspecialidades, $iTotalProcedimentos, $lProfissional, $iTotalProfissionais);

$oPdf->Output();  
?>