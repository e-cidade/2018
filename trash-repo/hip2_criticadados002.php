<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

$oDaoFarAcompPacHiperdia = db_utils::getdao('far_cadacomppachiperdia');

/* Cabeçalho dos passageiros normais */
function novoCabecalho($oPdf) {

  $oPdf->setfont('arial', 'B', 8);
  $iTam = 3.5;
  $lCor = true;

  $oPdf->cell(20, $iTam, 'CGS', 1, 0, 'C', $lCor);
  $oPdf->cell(75, $iTam, 'Nome', 1, 0, 'C', $lCor);
  $oPdf->cell(100, $iTam, 'Campo Obrigatorio', 1, 1, 'C', $lCor);

}

/* Linha Normal */
function novaLinha($oPdf, $oDados) {

  $oPdf->setfont('arial', '', 7);
  $iTam = 3.5;
  $lCor = false;

  $oPdf->cell(20, $iTam, $oDados->iCgs, 1, 0, 'C', $lCor);
  $oPdf->cell(75, $iTam, $oDados->sNome, 1, 0, 'L', $lCor);
  $oPdf->cell(100, $iTam, $oDados->sCampo, 1, 1, 'L', $lCor);

}

 $aCampos    = array();
 $aCampos[ ] = "z01_i_cgsund";
 $aCampos[ ] = "z01_v_nome";
 $aCampos[ ] = "z01_v_ender";
 $aCampos[ ] = "z01_i_numero";
 $aCampos[ ] = "z01_v_bairro";
 $aCampos[ ] = "z01_v_cep";
 $aCampos[ ] = "s152_i_pressaosistolica";
 $aCampos[ ] = "s152_i_pressaodiastolica";
 $aCampos[ ] = "s152_i_cintura";
 $aCampos[ ] = "s152_n_peso";
 $aCampos[ ] = "s152_i_altura"; 
 $aCampos[ ] = "s152_i_glicemia";
 $aCampos[ ] = "fa50_i_outrosmedicamentos";
 $aCampos[ ] = "fa50_i_naomedicamentoso";
 $aCampos[ ] = "z01_v_sexo";
 $aCampos[ ] = "z01_d_nasc";
 $aCampos[ ] = "z01_v_mae";
 $aCampos[ ] = "z01_i_estciv";
 
 $aCampos2    = array();
 $aCampos2[ ] = "CGS";
 $aCampos2[ ] = "Nome";
 $aCampos2[ ] = "Endereço";
 $aCampos2[ ] = "Numero";
 $aCampos2[ ] = "Bairro";
 $aCampos2[ ] = "CEP";
 $aCampos2[ ] = "Pressão Sistolica";
 $aCampos2[ ] = "Pressão Diastolica";
 $aCampos2[ ] = "Cintura";
 $aCampos2[ ] = "Peso";
 $aCampos2[ ] = "Altura"; 
 $aCampos2[ ] = "Glicemia";
 $aCampos2[ ] = "Outros Medicamentos";
 $aCampos2[ ] = "Não medicamentoso";
 $aCampos2[ ] = "Sexo";
 $aCampos2[ ] = "Data de Nascimento";
 $aCampos2[ ] = "Nome da Mãe";
 $aCampos2[ ] = "Estado Civil";

$sSql     = $oDaoFarAcompPacHiperdia->sql_query2(null,
                                                 "distinct on (z01_v_nome)".implode(",",$aCampos),
                                                 $aCampos[1]);
$rs       = $oDaoFarAcompPacHiperdia->sql_record($sSql);

if ($oDaoFarAcompPacHiperdia->numrows == 0) {

  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>Nenhum registro encontrado</b><br>
          <input type='button' value='Fechar' onclick='window.close()'></b>
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
$oPdf->setLeftMargin(8.5);
$oPdf->setfillcolor(235);
$lQuebra     = true;

//Cabeçalho
$head1 = "RELATÓRIO DE CRITICA DADOS PARA EXPOTAÇÂO";


for ($iInd = 0; $iInd < $oDaoFarAcompPacHiperdia->numrows; $iInd++) {

  $oDados = db_utils::fieldsmemory($rs, $iInd, true);
  $aDados = array();

  for ($iInd2=0; $iInd2 < count($aCampos); $iInd2++) {
    if ($oDados->$aCampos[$iInd2] == '' || $oDados->$aCampos[$iInd2] == null) {

      $iIndex                  = count($aDados);
      $aDados[$iIndex]->iCgs   = $oDados->$aCampos[0];
      $aDados[$iIndex]->sNome  = $oDados->$aCampos[1];
      $aDados[$iIndex]->sCampo = 'Campo '.$aCampos2[$iInd2].' Não informado';

    }
  }
  for ($iInd2 = 0; $iInd2 < count($aDados); $iInd2++) {

    if (($oPdf->GetY() > $oPdf->h -25) || $lQuebra == true) {

      $oPdf->addpage('P');
      novoCabecalho($oPdf);
      $lQuebra = false;

    }
    novaLinha($oPdf, $aDados[$iInd2]);

  }

}

$oPdf->Output();
?>