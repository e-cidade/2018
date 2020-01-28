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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("classes/db_agendamentos_classe.php"));

parse_str( $_SERVER['QUERY_STRING'] );
set_time_limit(0);

$oClagendamentos = new cl_agendamentos;
$oClrotulo       = new rotulocampo;

$oClagendamentos->rotulo->label();
$oClrotulo->label("z01_v_nome");
$oClrotulo->label("z01_v_telef");

$sWhere = " sd27_i_codigo = $sd27_i_codigo";

if(isset($sDatai) && isset($sDataf)) {

  if($sDatai!="" && $sDataf!="") {

    $sWhere   .= " and ";

    $sRest     = "";
    $sRest     = substr($sDatai, 6);
    $sRest    .="-";
    $sRest    .= substr($sDatai, 3, 2);
    $sRest    .="-";
    $sRest    .= substr($sDatai, 0, 2);
    $sWhere   .= "sd23_d_consulta  BETWEEN '".$sRest."' and";
    $sRest     = "";
    $sRest     = substr($sDataf, 6);
    $sRest    .="-";
    $sRest    .= substr($sDataf, 3, 2);
    $sRest    .="-";
    $sRest    .= substr($sDataf, 0, 2);
    $sWhere   .= " '".$sRest."'";
  }
}

if(isset($s114_i_situacao)) {

  if($s114_i_situacao =='0') {
    $sWhere .= "";
  } else{
    $sWhere .= "and s114_i_situacao = $s114_i_situacao ";
  }
}

$sOrder   = " sd23_d_consulta, sd23_c_hora, sd23_i_ficha ";
$sCampos  = " sd23_d_consulta, sd101_c_descr, sd23_i_ficha, s114_i_situacao, ";
$sCampos .= " sd23_i_codigo,sd24_i_codigo,sd23_c_hora, sd23_i_numcgs, z01_v_nome, z01_v_telef, z01_v_telcel ";
$sSql     = $oClagendamentos->sql_query_prontuarios("",$sCampos,$sOrder,$sWhere);

$rsAgendamentos = $oClagendamentos->sql_record($sSql);
if($oClagendamentos->numrows == 0) {

  echo"
  <table width='100%'>
    <tr>
      <td align='center'><font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br><input type='button'
      value='Fechar' onclick='window.close()'></b></font></td>
    </tr>
  </table>";
  exit;
}

$oPdf = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetWidths( array( 16, 10, 20, 9, 16, 12, 12, 55, 41 ) );
$oPdf->SetAligns( array( "C", "C", "C", "C", "C", "C", "C", "L", "C" ) );

/* Setando as variaveis do Row_multicell */
$iAlturaRow     = $oPdf->h - 32;
$iAltura        = 4;
$bBorda         = true;
$iEspaco        = 4; // = altura da linha
$bPreenchimento = false;
$bNaoUsarEspaco = true; //variavel espaco obs: se não usar da problema!!!
$bUsarQuebra    = true;
$iCampoTestar   = null;
$iLaguraFixa    = 0; //false

$head2 = "RELATÓRIO DE AGENDAMENTOS POR PERÍODO";
$head4 = "DOUTOR: $z01_nome";

$aSituacao = array(
            "0"=>"Não",
            "1"=>"Cancelado",
            "2"=>"Faltou",
            "3"=>"Outros"
          );
$lPri = true;

for ($iX = 0; $iX < $oClagendamentos->numrows; $iX++) {

  db_fieldsmemory($rsAgendamentos, $iX);

  if($s114_i_situacao == "") {
    $s114_i_situacao = 0;
  }

  if (($oPdf->gety() > $oPdf->h -30)  || $lPri == true ){

    $oPdf->addpage();
    $oPdf->setfillcolor(235);
    $oPdf->setfont('arial','b',7);

    $oPdf->cell(16, 4, "Data"      ,1 ,0, "C", 1);
    $oPdf->cell(10, 4, "Hora"      ,1, 0, "C", 1);
    $oPdf->cell(20, 4, "Tipo Ficha",1 ,0, "C", 1);
    $oPdf->cell(9 , 4, "Ficha"     ,1 ,0, "C", 1);
    $oPdf->cell(16, 4, "Anulado"   ,1 ,0, "C", 1);
    $oPdf->cell(12, 4, "FAA"       ,1, 0, "C", 1);
    $oPdf->cell(12, 4, "CGS"       ,1, 0, "C", 1);
    $oPdf->cell(55, 4, "Nome"      ,1, 0, "C", 1);
    $oPdf->cell(41, 4, "Contato"   ,1, 1, "C", 1);
    $lPri = false;
  }

  $sData_Consulta = "";
  $sData_Consulta = substr($sd23_d_consulta,8,2);
  $sData_Consulta .="/";
  $sData_Consulta .= substr($sd23_d_consulta, 5,2 );
  $sData_Consulta .="/";
  $sData_Consulta .= substr($sd23_d_consulta, 0, 4);
  $oPdf->setfont('arial','',7);

  $aTelefones = array();
  if ( !empty($z01_v_telef) ) {
    $aTelefones[] = DBString::formatarTelefone($z01_v_telef);
  }
  if ( !empty($z01_v_telcel) ) {
    $aTelefones[] = DBString::formatarTelefone($z01_v_telcel);
  }

  $aDados   = array();
  $aDados[] = $sData_Consulta;
  $aDados[] = $sd23_c_hora;
  $aDados[] = $sd101_c_descr;
  $aDados[] = $sd23_i_ficha;
  $aDados[] = $aSituacao[$s114_i_situacao];
  $aDados[] = $sd24_i_codigo;
  $aDados[] = $sd23_i_numcgs;
  $aDados[] = $z01_v_nome;
  $aDados[] = implode(' / ', $aTelefones);

  $iLines = 0;

  for ($iCont = 0; $iCont < count($aDados); $iCont++) {

    if ($iLines < $oPdf->NbLines($oPdf->widths[$iCont], $aDados[$iCont])) {
      $iLines = $oPdf->NbLines($oPdf->widths[$iCont], $aDados[$iCont]);
    }
  }

  $iHeight = $iLines * $iEspaco;
  $oPdf->Row_multicell(
                        $aDados,
                        $iAltura,
                        $bBorda,
                        $iHeight,
                        $bPreenchimento,
                        $bNaoUsarEspaco,
                        $bUsarQuebra,
                        $iCampoTestar,
                        $iAlturaRow,
                        $iLaguraFixa
                      );

  $iQdte_Registros=1;
  for($iReg = 0; $iReg < $iX; $iReg++){
    $iQdte_Registros++;
  }
}

$oPdf->cell( 190, 4, "", 0, 1, "C", 0 );
$oPdf->setfillcolor(235);
$oPdf->setfont( 'arial', 'b', 8 );
$oPdf->cell( 80, 6, "TOTAL DE REGISTROS",         1, 1, "C", 1 );
$oPdf->cell( 40, 5, "Período",                    1, 0, "C", 1 );
$oPdf->cell( 40, 5, $sDatai." até " .$sDataf,     1, 1, "C", 0 );
$oPdf->cell( 40, 5, "Qtde. Registros no Período", 1, 0, "C", 1 );
$oPdf->cell( 40, 5, $iQdte_Registros,             1, 1, "C", 0 );

$lPri = false;
$oPdf->Output();