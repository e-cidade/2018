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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_stdlibwebseller.php");
include("classes/db_far_retiradaitens_classe.php");
include("classes/db_far_programa_classe.php");
include("classes/db_far_farmacia_classe.php");
include("classes/db_db_config_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$clrotulo            = new rotulocampo;
$clfar_programa      = new cl_far_programa;
$clfar_retiradaitens = new cl_far_retiradaitens;
$clfar_farmacia      = new cl_far_farmacia;
$cldb_config         = new cl_db_config;
$sDepto              = db_getsession("DB_nomedepto");
$iCoddepto           = db_getsession("DB_coddepto");
$iExer               = date('Y');
$dDatar              = date("d/m/y");

function somardata($dData, $iDias= 0, $iMeses = 0, $iAno = 0) {

  $aData     = explode("/", $dData);
  $dNovadata = date("d/m/Y", mktime(0, 0, 0, $aData[1] + $iMeses,   $aData[0] + $iDias, $aData[2] + $iAno) );
  return $dNovadata;
   
}

$dDatas    = data_farmacia($ano, $semestre);
$dIni1     = converte_data($dDatas[0]);
$dFin1     = converte_data($dDatas[1]);
$sCampos   = " fa03_c_descr, descrdepto, z01_nome, far_retirada.*, fa06_i_codigo, fa06_i_retirada, m60_descr,m77_lote,";
$sCampos  .= " m77_dtvalidade, m61_descr, ";
$sCampos  .= " case when fa09_f_quant is null then fa06_f_quant else fa09_f_quant end as fa09_f_quant ";
$sOrdem    = " fa04_i_codigo desc ";
$sWhere    = " fa04_d_data BETWEEN '".$dDatas[0]."' AND '".$dDatas[1]."' ";
$sSql      = $clfar_retiradaitens->sql_query_retiradaitens(null, $sCampos, $sOrdem, $sWhere);
$rsResult  = $clfar_retiradaitens->sql_record($sSql);
                                                                                            
if ($clfar_retiradaitens->numrows == 0) {
  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>
            Nenhum Registro para o Relatório
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
	
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$head1 = "RELAÇÃO MENSAL DE NOTIFICAÇÃO";
$head2 = "Data:  ".$dIni1. " A " .$dFin1;
$pdf->ln(5);
$pdf->addpage('L');
$iTotal = 0;
$iCont  = 0;
$pdf->setfont('arial', 'b', 8);
$pdf->rect(220, 36, 63, 36, "D");
$sWhere      = " fa13_i_departamento = '".$iCoddepto."' ";
$sSql        = $clfar_farmacia->sql_query(null, "*", "", $sWhere);
$rsResultfar = $clfar_farmacia->sql_record($sSql);
if ($clfar_farmacia->numrows > 0) {
  db_fieldsmemory($rsResultfar, 0);
}
$rsResultconfig = $cldb_config->sql_record($cldb_config->sql_query(null, "*", "", ""));
if ($cldb_config->numrows > 0) {
  db_fieldsmemory($rsResultconfig, 0);
}
@$fa13_c_autosanitaria = $fa13_c_autosanitaria;
$pdf->setY(35);
$pdf->setX(52);
$pdf->cell(150, 5, "SECRETARIA DE SAÚDE: ".$munic, 0, 1, "C", 0);
$pdf->setY(45);
$pdf->setX(50);
$pdf->cell(150, 2, "AUTORIDADE SANITÁRIA: ".$fa13_c_autosanitaria, 0, 1, "C", 0);
$pdf->setY(50);
$pdf->setX(50);
$pdf->cell(167, 4, "NOME DA UNIDADE DE SAÚDE: ".$sDepto, 0, 1, "L", 0);
$pdf->line(98, 54, 210, 54);
$pdf->setY(55);
$pdf->setX(30);
$pdf->line(51, 61, 210, 61);
$pdf->setY(63);
$pdf->setX(51);
$pdf->cell(167, 4, "CÓDIGO: ", 0, 1, "L", 0);
$pdf->line(65, 67, 210, 67);
$pdf->setY(70);
$pdf->setX(51);
$pdf->cell(167, 4, "ENDEREÇO:  ".$ender, 0, 1, "L", 0);
$pdf->line(70, 74, 210, 74);
$pdf->setY(75);
$pdf->setX(30);
$pdf->line(51, 81, 210, 81);
$pdf->setY(83);
$pdf->setX(51);
$pdf->cell(167, 4, "MUNICÍPIO E UNIDADE FEDERAL:  ".$munic, 0, 1, "L", 0);
$pdf->line(99, 87, 210, 87);
$pdf->setY(97);
$pdf->setX(181);
$pdf->cell(175, 5, "EXERCÍCIO: ".$iExer, 0, 1, "C", 0);
$pdf->setY(104);
$pdf->setX(162);
$pdf->cell(175, 5, "PERÍODO TRIMESTRAL: ".$dIni1. ' A ' .$dFin1, 0, 1, "C", 0);
$pdf->setfont('arial', 'b', 10);
$pdf->setY(90);
$pdf->setX(51);
$pdf->cell(167, 5, "MAPA TRIMESTRAL DO CONSOLIDADO DAS", 0, 1, "C", 0);
$pdf->setY(95);
$pdf->setX(51);
$pdf->cell(167, 5, "PRESCRIÇÕES DE MEDICAMENTOS SUJEITOS", 0, 1, "C", 0);
$pdf->setY(100);
$pdf->setX(51);
$pdf->cell(167, 5, "A CONTROLE ESPECIAL - MCPM", 0, 1, "C", 0);
$pdf->setY(110);
$pdf->setX(75);
$pdf->setfont('arial', '', 10);
$pdf->cell(167, 5, "TALIDOMIDA", 0, 1, "C", 0);
$pdf->setY(115);    
$pdf->cell(17, 10, "", 1, 0, "L", 0);
$pdf->cell(73, 10, "N° DE ATENDIMENTOS", 1, 0, "C", 0); 
/* Quantidade de programas */
$rsResult = $clfar_programa->sql_record($clfar_programa->sql_query("", "*", "", ""));
$iFator = 0;
if ($clfar_programa->numrows > 0) {
  $iFator = 170 / $clfar_programa->numrows;
}
$iTam = ($clfar_programa->numrows * $iFator);
$pdf->cell($iTam, 10, "QUANTIDADE DE COMPRIMIDOS POR PROGRAMA", 1, 1, "C", 0);
$pdf->cell(17, 8, "MESES", "L", 0, "C", 0);//1
$pdf->cell(33, 8, "N° DE PACIENTES ", "L", 0, "C", 0);//2
$pdf->cell(40, 8, "N° DE NOTIFICAÇÕES ", "L", 0, "C", 0);//3
$pdf->cell($iTam, 8, "PROGRAMAS", 1, 1, "C", 0);//3
$pdf->cell(17, 6, "", "BL", 0, "C", 0);//1
$pdf->cell(33, 6, "ATENDIDOS"," BL", 0, "C", 0);//2
$pdf->cell(40, 6, "ATENDIDAS", "BLR", 0, "C", 0);//10
for ($iI = 0; $iI < 5; $iI++) {
  $aVetotal[$iI] = 0;
}
for ($iI = 0; $iI < $clfar_programa->numrows; $iI++) {
    
  db_fieldsmemory($rsResult, $iI);
  $iLen    = strlen($fa12_c_descricao);
  $iLength = $iLen;
  while (true) {
  	
  	if ($pdf->GetStringWidth(substr($fa12_c_descricao, 0, $iLength)) < ($iFator - 3)) {

      if ($iLen != $iLength) {
  	    $fa12_c_descricao = substr($fa12_c_descricao, 0, $iLength)."..";
  	  }
  	  break;
  	
  	}
  	$iLength--;
  	
  }
  $pdf->cell($iFator, 6, $fa12_c_descricao, 1, 0, "L", 0);
  $aVet_prog[$iI] = 0;

}
$pdf->cell(22, 6, "TOTAL", 1, 1, "L", 0);
$dData  = converte_data($dDatas[0]);
$iTotal = 0;
for ($iI = 0; $iI < $clfar_programa->numrows + 1; $iI++) {
  $aVetotal[$iI] = 0;
}
for ($iI = 0; $iI < 3; $iI++) {
	
  $dData    = converte_data($dData);
  $dDataini = $dData;
  $aVet     = explode("-", $dData);
  $iMes     = $aVet[1];
  $iAno     = $aVet[0];
  $dDatafim = date("Y-m-t", mktime(0, 0, 0, $iMes, 1, $iAno));
  /* 
   * Para sair as informações nesse relatorio é necessario que seja 
   * cadastrado as carteirinhas dos medicamentos continuados
   * medicamento da lista A1
   * ==============================================================================
   *   SELECIONA E PERCORRE TODOS OS PROGRAMAS LISTADOS ENTRE AS DATAS INFORMADAS 
   * ==============================================================================
   */ 
   $clfar_retiradaitens->erro_msg     = "";
   $clfar_retiradaitens->erro_status  = 0;
   $clfar_retiradaitens->numrows      = 0;
   $sCampos                           = " fa06_i_matersaude, fa10_i_programa, fa04_d_data, fa06_f_quant ";
   $sWhere                            = " fa04_d_data between '".$dDataini."' and '".$dDatafim."' ";
   $sWhere                           .= " and trim(upper(substr(fa15_c_listacontrolado,0,9))) = 'LISTA C3' ";     
   $sSql                              = $clfar_retiradaitens->sql_query_mapaconsolidado(null, $sCampos, "",  $sWhere);
   $rsResult2                         = $clfar_retiradaitens->sql_record($sSql);
   $iTotal                            = 0;
   $iLimit                            = $clfar_retiradaitens->numrows;
   //Percorre todos os registros de atendimento e enseguida verifica se pertence a algum programa
   for ($iW = 0; $iW < $clfar_programa->numrows; $iW++) {
     	
   	 db_fieldsmemory($rsResult, $iW); 
     $aVet_prog[$iW]    = 0;
     for($iJ = 0; $iJ < $iLimit; $iJ++) {
       
       db_fieldsmemory($rsResult2, $iJ);  	
       if ($fa12_i_codigo == $fa10_i_programa && $fa12_i_codigo != "") {

         $aVet_prog[$iW] += $fa06_f_quant;
     	 $iTotal         += $fa06_f_quant;
     	
       }
       
     }
     
   }
   $pdf->cell(17, 6, db_mes($iMes), 1, 0, "R", 0);
   $pdf->cell(33, 6, "$iLimit", 1, 0, "R", 0);
   $aVetotal[0] += $iLimit; 
   $pdf->cell(40, 6, "$iLimit", 1, 0, "R", 0);   
   for ($iY = 0; $iY < $clfar_programa->numrows; $iY++) {
   	
     $pdf->cell($iFator, 6, "$aVet_prog[$iY]", 1, 0, "R", 0);
     $aVetotal[$iY + 1]  += $aVet_prog[$iY];
     
   }
   $pdf->cell(22, 6, "$iTotal", 1, 1, "R", 0);
   $dData = somardata(converte_data($dData), 0, 1);

}

$pdf->cell(17, 6, "TOTAL", 1, 0, "R", 0);
$pdf->cell(33, 6, "$aVetotal[0]", 1, 0, "R", 0);
$pdf->cell(40, 6, "$aVetotal[0]", 1, 0, "R", 0);  
for ($iY = 1; $iY < $clfar_programa->numrows; $iY++) {
  $pdf->cell($iFator, 6, "".$aVetotal[$iY]."", 1, 0, "R", 0);
} 
$pdf->cell($iFator, 6, "".$aVetotal[$iY]."", 1, 1, "R", 0); 
$pdf->setfont('arial','b',8);
$pdf->setY(165); 
$pdf->cell(100, 7, "NOME/RG DO RESPONSÁVEL TÉCNICO: ".@$fa13_c_resptecnico, 0, 1, "L", 0);
$pdf->cell(70, 7, "RECEBIDO POR : ", 0, 0, "L", 0);
$pdf->cell(80, 7, "RG : ", 0, 0, "L", 0);
$pdf->cell(90, 7, "ÓRGÃO/SETOR :", 0, 0, "L", 0);
$pdf->cell(110, 7, "DATA :", 0, 1, "L", 0); 
$pdf->cell(70, 7, "CONFERIDO POR: ", 0, 0, "L", 0);
$pdf->cell(80, 7, "RG :", 0, 0, "L", 0);
$pdf->cell(90, 7, "ÓRGÃO/SETOR : ", 0, 0, "L", 0);
$pdf->cell(110, 7, "DATA :", 0, 1, "L", 0);
$pdf->Output();
?>