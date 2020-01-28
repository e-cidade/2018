<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once (modification("fpdf151/pdf.php"));
require_once (modification("classes/db_agendamentos_ext_classe.php"));

$cl_agendamentos_ext = new cl_agendamentos_ext;

$sCampos  = " sd23_d_consulta, sd23_c_hora, ";
$sCampos .= " case when s102_i_prontuario is null then ";
$sCampos .= "   'Agendado' ";
$sCampos .= " else ";
$sCampos .= "   'Atendimento' ";
$sCampos .= " end as sd97_c_tipo, ";
$sCampos .= " z01_i_cgsund, z01_v_nome, sd23_i_ficha, sd04_i_medico, z01_numcgm, z01_nome, rh70_estrutural, ";
$sCampos .= " rh70_descr, sd101_c_descr, login";

$sql = $cl_agendamentos_ext->sql_query_ext("", $sCampos, "", "", true);

$primeiro = false;
$sql     .= " and ";
if ($cgs != "") {

	$sql .= "z01_i_numcgs=".$cgs;
	$primeiro=true;
}

if (isset($datai)&&isset($dataf)) {

	if ($datai!="" && $dataf!="") {

		if ($primeiro==true) {
			$sql .= " and ";
		}
		$rest = "";
		$rest = substr($datai, 6);
		$rest .="-";
		$rest .= substr($datai, 3, 2);
		$rest .="-";
		$rest .= substr($datai, 0, 2);
		$sql .= "sd23_d_consulta  BETWEEN '".$rest."' and";

        $rest = "";
		$rest = substr($dataf, 6);
		$rest .="-";
		$rest .= substr($dataf, 3, 2);
		$rest .="-";
		$rest .= substr($dataf, 0, 2);
		$sql .= " '".$rest."'";
	    $primeiro=true;
	}
}

if($primeiro==false || $cgs==""){
	db_msgbox("Preencha no minimo o Campo paciente(CGS)!");
    $sql="";
}

$result = db_query($sql) or die ( pg_errormessage()."<br> $sql");
$linhas = pg_num_rows($result);

if($linhas==0){?>
 <table width='100%'>
  <tr>
   <td align='center'>
    <font color='#FF0000' face='arial'>
     <b>Nenhum registro encontrado.<br>
     <input type='button' value='Fechar' onclick='window.close()'></b>
    </font>
   </td>
  </tr>
 </table>
 <?
 exit;
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
db_fieldsmemory($result,0);
$head3 = "Relatorio de Agendamento";
$head5 = "CGS: ".$cgs;
$head7 = "Nome: ".$z01_v_nome;
if($datai!=""){
	$head4 = "Periodo";
	$head5 = "De ".$datai." à ".$dataf;
}

$cor = "0";
$pdf->setfillcolor(223);

$troca = 1;
for($i=0;$i<$linhas;$i++) {

	db_fieldsmemory($result,$i);

  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ) {

    $pdf->addpage('L');
    $pdf->ln(5);
    $pdf->cell(20,5,"Agenda",1,0,"C",$cor);
    $pdf->cell(10,5,"Hora",1,0,"C",$cor);
    $pdf->cell(20,5,"Tipo",1,0,"C",$cor);
    $pdf->cell(10,5,"Ficha",1,0,"C",$cor);
    $pdf->cell(94,5,"Médico",1,0,"C",$cor);
    $pdf->cell(65,5,"Especialidade",1,0,"C",$cor);
    $pdf->cell(35,5,"Tipo Ficha",1,0,"C",$cor);
    $pdf->cell(25,5,"Usuário Atend.",1,1,"C",$cor);
    $troca = 0;
    $pre = 1;
  }
//	$pdf->setfont('arial','',9);
	$pdf->cell(20,5,substr($sd23_d_consulta, 8,2)."/".substr($sd23_d_consulta, 5,2)."/".substr($sd23_d_consulta, 0,4),0,0,"C",$cor);
	$pdf->cell(10,5,$sd23_c_hora,0,0,"C",$cor);
	$pdf->cell(20,5,$sd97_c_tipo,0,0,"C",$cor);
	$pdf->cell(10,5,trim($sd23_i_ficha),0,0,"C",$cor);
	$pdf->cell(94,5,substr($sd04_i_medico." - ".trim($z01_nome),0,60),0,0,"L",$cor);
	$pdf->cell(65,5,substr(trim($rh70_estrutural)." - ".trim($rh70_descr),0,40),0,0,"L",$cor);
  $pdf->cell(35,5,$sd101_c_descr,0,0,"L",$cor);
  $pdf->cell(25,5,$login,0,1,"L",$cor);

}
$pdf->Output();
?>