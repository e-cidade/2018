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

require_once("fpdf151/pdf.php");
require_once("classes/db_lab_requiitem_classe.php");
require_once("libs/db_utils.php");
require_once("libs/db_stdlibwebseller.php");

$oDaoVacAplica  = db_utils::getdao('vac_aplica');
$dInicioPeriodo = implode('-',array_reverse(explode("/",$dDataIni)));
$dFimPeriodo    = implode('-',array_reverse(explode("/",$dDataFim)));
$sFaixainimesd  = (date("m") - $sFaixainimes) > 0 ?  (date("m") - $sFaixainimes) : (12  - date("m") - $sFaixainimes);
$sFaixafimmesd  = (date("m") - $sFaixafimmes) > 0 ?  (date("m") - $sFaixafimmes) : (12  - date("m") - $sFaixafimmes);
$dFaixaFim      = (date("Y")- $sFaixainiano)."-".$sFaixainimesd."-01";
$dFaixaIni      = (date("Y")- $sFaixafimano)."-".$sFaixafimmesd."-01";
$sCampos        = " vc06_i_codigo, ";
$sCampos       .= " vc06_c_descr, ";
$sCampos       .= " z01_i_cgsund, ";
$sCampos       .= " z01_v_nome, ";
$sCampos       .= " z01_d_nasc, ";
$sCampos       .= " ultimaaplicacao, ";
$sCampos       .= " vc03_c_descr, ";
$sCampos       .= " (now() - (diasatraso||' days')::INTERVAL)::Date as dProximadose, ";
$sCampos       .= " diasatraso, ";
$sOrder         = " z01_v_nome ";
$sGroup         = "";
$sSql           = "";

if ($iGrupo == 2) {
  $sCampos .= " ('null') as z01_v_bairro ";
} else {
  $sCampos .= " z01_v_bairro ";
}
if (isset($sVacinas) && $sVacinas != 0) {
  $sWhere = " vc07_i_vacina in ($sVacinas) "; 
}
if ($iGrupo == 1) {
  
  $sGroup  = " group by vc06_i_codigo,vc06_c_descr,z01_i_cgsund, ";
  $sGroup .= " z01_v_nome, z01_d_nasc, z01_v_bairro , ultimaaplicacao, vc03_c_descr, dproximadose, diasatraso ";

} elseif ($iGrupo == 2) {

  $sGroup  = " group by vc06_i_codigo, vc06_c_descr, z01_i_cgsund, z01_v_nome, ";
  $sGroup .= " z01_d_nasc , ultimaaplicacao, vc03_c_descr, dproximadose, diasatraso ";

} else {

  $sGroup  = " group by z01_v_bairro, vc06_i_codigo, vc06_c_descr, z01_i_cgsund, ";
  $sGroup .= " z01_v_nome, z01_d_nasc, ultimaaplicacao, vc03_c_descr, dproximadose, diasatraso ";

}
$sSql   = $oDaoVacAplica->sql_query_vacinasnaoaplicadas($sCampos, $sOrder, $sGroup, $dInicioPeriodo, 
                                                        $dFimPeriodo, $sVacinas, $dFaixaIni, $dFaixaFim);                                     
$rsData = $oDaoVacAplica->sql_record($sSql);
if ($oDaoVacAplica->numrows == 0) {
  
?>
<table width='100%'>
  <tr>
    <td align='center'>
      <font color='#FF0000' face='arial'>
        <b>
          Nenhum registro encontrado
          <br>
          <input type='button' value='Fechar' onclick='window.close()'>
        </b>
      </font>
    </td>
  </tr>
</table>
<?
exit;

}
$oPdf = new pdf();
$oPdf->Open();
$oPdf->AliasNbPages();
if ($iTipo == 1) {
  $sStr = "Faltosos";
} else {
  $sStr = "Aprazamento"; 
}
$head1        = "Relatório de $sStr";
$head2        = "Período: $dDataIni á $dDataFim ";
$head3        = "Faixa etária: $sFaixainiano ano(s) $sFaixainimes mes(es) até $sFaixafimano ano(s) $sFaixafimmes mes(es)";
if ($iGrupo == 1) {
  $head4 = "Agrupado por: Vacina";
} elseif ($iGrupo == 2) {
  $head4 = "Agrupado por: Nome";
}  else {
  $head4 = "Agrupado por: Bairro";
}
$iTotalVacina = 0;
$iTotalBairro = 0;
$iTotalPessoa = 0;
$sBairro      = "";
$iVacina      = 0;
$iCgs         = 0;
$lFirst       = true;
for ($iX = 0; $iX < $oDaoVacAplica->numrows; $iX++) {

  $oDados = db_utils::fieldsmemory($rsData, $iX);
  if ($oPdf->GetY() > $oPdf->h - 25 
      || ($oDados->vc06_i_codigo != $iVacina && $iGrupo == 1)
      || ($oDados->z01_i_cgsund  != $iCgs   && $iGrupo == 2)
      || (($oDados->z01_v_bairro != $sBairro || $oDados->vc06_i_codigo != $iVacina) && $iGrupo == 3)) {

    if ($oPdf->GetY() > $oPdf->h -25 || $lFirst == true) {

      $oPdf->ln(5);
      $oPdf->addpage('L');
      $oPdf->setfont('arial', 'b', 10);
      $lFirst = false;

    }
    if ($oDados->vc06_i_codigo != $iVacina && ($iGrupo == 1 || $iGrupo == 3)) {

      $iVacina = $oDados->vc06_i_codigo;
      if ($iTotalVacina > 0) {
        
        $oPdf->cell(245, 4, "Total Vacina: $iTotalVacina", 1, 1, "L", 0);
        $iTotalVacina = 0;
        
      }

    }
    if ($oDados->z01_i_cgsund != $iVacina && $iGrupo == 2) {

      $iCgs = $oDados->z01_i_cgsund;
      if ($iTotalPessoa > 0) {

        $oPdf->cell(265, 4, "Total Paciente: $iTotalPessoa", 1, 1, "L", 0);
        $iTotalPessoa = 0;

      }

    }
    if ($oDados->z01_v_bairro != $sBairro && $iGrupo == 3) {

      $sBairro = $oDados->z01_v_bairro;
      if ($iTotalVacina > 0) {

        $oPdf->cell(245, 4, "Total Vacina: $iTotalVacina", 1, 1, "L", 0);
        $iTotalVacina = 0;

      }
      if ($iTotalBairro > 0) {

        $oPdf->cell(245, 4, "Total Bairro:$iTotalBairro", 1, 1, "L", 0);
        $iTotalBairro = 0;

      }
      
    }
    
    $oPdf->setfont('arial', 'b', 8);
    $oPdf->setfillcolor(200);
    if ($iGrupo == 3) {
      $oPdf->cell(245, 4, "Bairro: ".$oDados->z01_v_bairro, 1, 1, "L", 1);
    }
    if ($iGrupo == 1 || $iGrupo == 3) {
      $oPdf->cell(245, 4, "Vacina: ".$oDados->vc06_c_descr, 1, 1, "L", 1);
    }
    if ($iGrupo == 2) {
      $oPdf->cell(210, 4,"Nome: ".$oDados->z01_v_nome, 1, 0, "L", 1);
      $oPdf->cell(55,4,"Dt. Nasc.: ".implode('/',array_reverse(explode("-",$oDados->z01_d_nasc))), 1, 1, "L", 1);
    }
    $oPdf->cell(100, 4, "Nome", 1, 0, "L", 1);
    if ($iGrupo == 2) {
      $oPdf->cell(50, 4, "Vacina ", 1, 0, "L", 1);
    } else {
      $oPdf->cell(30, 4, "Data de Nascimento ", 1, 0, "L", 1);
    }
    $oPdf->cell(30, 4, "Data Última Aplic.", 1, 0, "L", 1);
    $oPdf->cell(30, 4, "Próxima Dose", 1, 0, "L", 1);
    if ($iTipo == 1) {
      
      $oPdf->cell(30, 4, "Data em Atraso", 1, 0, "L", 1);
      $oPdf->cell(25, 4, "Dias em Atraso", 1, 1, "L", 1);
      
    } else {
      
      $oPdf->cell(30, 4, "Data de Aplica.", 1, 0, "L", 1);
      $oPdf->cell(25, 4, "Dias Adiantado", 1, 1, "L", 1);
      
    }
    
  }
  $oPdf->setfont('arial', '', 8);
  $oPdf->cell(100, 4, $oDados->z01_v_nome, 1, 0, "L", 0);
  if ($iGrupo == 2) {
    
    $oPdf->cell(50, 4, $oDados->vc06_c_descr, 1, 0, "L", 0);
    
  } else {
    
    $oDados->z01_d_nasc = implode('/',array_reverse(explode("-",$oDados->z01_d_nasc)));
    $oPdf->cell(30, 4, $oDados->z01_d_nasc, 1, 0, "C", 0);
   
  }
  $oPdf->cell(30, 4, implode('/',array_reverse(explode("-",$oDados->ultimaaplicacao))), 1, 0, "C", 0);
  $oPdf->cell(30, 4, $oDados->vc03_c_descr, 1, 0, "L", 0);
  $oPdf->cell(30, 4, implode('/',array_reverse(explode("-",$oDados->dproximadose))), 1, 0, "C", 0);
  $oPdf->cell(25, 4, $oDados->diasatraso >= 0 ? $oDados->diasatraso : ($oDados->diasatraso * -1), 1, 1, "C", 0);
  $iTotalVacina++;
  $iTotalBairro++;
  $iTotalPessoa++;
  
}
$oPdf->setfillcolor(255);
if ($iGrupo == 3 || $iGrupo == 1) {
  $oPdf->cell(245, 4, "Total Vacina: $iTotalVacina", 1, 1, "L", 1);
}
if ($iGrupo == 2) {
  $oPdf->cell(265, 4, "Total Paciente: $iTotalPessoa", 1, 1, "L", 1);
}
if ($iGrupo == 3) {
  $oPdf->cell(245, 4, "Total Bairro: $iTotalBairro", 1, 1, "L", 1);
}
$oPdf->Output();
?>