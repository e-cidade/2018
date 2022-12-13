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
require_once("libs/db_sql.php");
require_once("libs/db_utils.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$oDaosaucadsus = db_utils::getdao('sau_cadsus');
$oDaosaucadsus->rotulo->label();

$sCampos  = " z01_i_cgsund, z01_v_nome, z01_d_nasc, z01_v_mae, s137_i_cadsus, ";
$sCampos .= " s115_c_tipo,s137_i_situacao,s138_b_tipo ";
$sWhere   = "";
$sSep     = "";
if ((isset($dataini)) && (isset($datafim))) {
    
  $sWhere .= $sSep." s136_d_data bentween $dataini and $datafim ";
  $sSep    = " and ";
    
} else {
  
  if (isset($dataini)) {
    
    $sWhere .= $sSep." s136_d_data>=$dataini ";
    $sSep    = " and ";  
  
  }
  if (isset($datafim)) {
    
    $sWhere .= $sSep." s136_d_data<=$datafim ";
    $sSep    = " and ";
    
  }

}

if (isset($codigo)) {

  $sWhere .= $sSep." s136_i_codigo = $codigo ";
  $sSep    = " and ";

}

if (isset($user)) {
  
  $sWhere .= $sSep." s136_i_user = $user ";
  $sSep    = " and ";

}

if (isset($tipo)) {
  
  if ($tipo == 1) {
    $sWhere .= $sSep." s138_b_tipo = true ";
  }
  if ($tipo == 2) {
    $sWhere .= $sSep." s138_b_tipo = false ";
  }
  $sSep = " and ";

}
$sSql     = $oDaosaucadsus->sql_query_coferenciaimportacaocartaosus(null, $sCampos, null, $sWhere);
$rsResult = $oDaosaucadsus->sql_record($sSql);
if ($oDaosaucadsus->numrows == 0) {
  
  ?>
  <table width='100%'>
    <tr>
      <td align='center'>
        <font color='#FF0000' face='arial'>
          <b>
            Nenhum Registro para o Relatório<br>
            <input type='button' value='Fechar' onclick='window.close()'>
          </b>
        </font>
      </td>
    </tr>
  </table>";
  <?
  exit;

}
$oPdf = new PDF();
$oPdf->Open(); 
$oPdf->AliasNbPages(); 
$head2  = "Relatório de Conferência da Importação";
$lPri   = true;
for ($iI = 0; $iI < $oDaosaucadsus->numrows; $iI++) {
  
   $oData = db_utils::fieldsmemory($rsResult, $iI);
   if ($oPdf->gety() > ($oPdf->h - 30)  || $lPri == true) {
      
      $oPdf->addpage('L');
      $oPdf->setfillcolor(235);
      $oPdf->setfont('arial', 'b', 7);
      $oPdf->cell(140, 4, "Cartão SUS", 1, 0, "L", 0);
      $oPdf->cell(100, 4, "CGS", 1, 0, "L", 0);
      $oPdf->cell(30, 4, "Importação", 1, 1, "L", 0);
      $oPdf->cell(25, 4, "Provisório", 1, 0, "L", 0);
      $oPdf->cell(25, 4, "Definitivo", 1, 0, "L", 0);
      $oPdf->cell(90, 4, "Nome", 1, 0, "L", 0);
      $oPdf->cell(25, 4, "Nascimento", 1, 0, "L", 0);
      $oPdf->cell(15, 4, "CGS", 1, 0, "L", 0);
      $oPdf->cell(60, 4, "Mãe", 1, 0, "L", 0); 
      $oPdf->cell(15, 4, "Importado", 1, 0, "L", 0);
      $oPdf->cell(15, 4, "Motivo(log)", 1, 1, "L", 0);
      $lPri = false;
      
   }
   if ($oData->s115_c_tipo == "P") {
     
     $provisorio = $oData->s137_i_cadsus;
     $definitivo = "";
   
   } else {

     $provisorio = "";
     $definitivo = $oData->s137_i_cadsus;
   
   }
   $oPdf->cell(25, 4, "$provisorio", 1, 0, "C", 0);
   $oPdf->cell(25, 4, "$definitivo", 1, 0, "C", 0);
   $oPdf->cell(90, 4, "$oData->z01_v_nome", 1, 0, "L", 0); 
   $oPdf->cell(25, 4, "$oData->z01_d_nasc", 1, 0, "C", 0);
   $oPdf->cell(15, 4, "$oData->z01_i_cgsund", 1 ,0, "C", 0);
   $oPdf->cell(60, 4, "$oData->z01_v_mae", 1, 0, "L", 0);
   $oPdf->cell(15, 4, $oData->s138_b_tipo == true ? "Sim" : "Não", 1, 0, "L", 0);
   $oPdf->cell(15, 4, "$oData->s137_i_situacao", 1, 1, "L", 0);

}
$oPdf->cell(150, 4, "", 0, 1, "L", 0);
$oPdf->setfont('arial', 'b', 8);
$oPdf->cell(150, 4, "Legenda", 1, 1, "C", 1);
$oPdf->cell(50, 4, "Codigo", 1, 0, "C", 1);
$oPdf->cell(100, 4, "Descrição", 1, 1, "C", 1);
$oPdf->cell(50, 4, "1", 1, 0, "C", 1);
$oPdf->cell(100, 4, "Dados atualizados pelo provisorio", 1, 1, "C", 1);
$oPdf->cell(50, 4, "2", 1, 0, "C", 1);
$oPdf->cell(100, 4, "Dados atualizados pelo provisorio, definitivo registrado", 1, 1, "C", 1);
$oPdf->cell(50, 4, "3", 1, 0, "C", 1);
$oPdf->cell(100, 4, "Dados atualizados pelo provisorio, conflito com definitivo", 1, 1, "C", 1);
$oPdf->cell(50, 4, "4", 1, 0, "C", 1);
$oPdf->cell(100, 4, "Dados atualizados pelo definitivo", 1, 1, "C", 1);
$oPdf->cell(50, 4, "5", 1, 0, "C", 1);
$oPdf->cell(100, 4, "Dados atualizados pelo RG", 1, 1, "C", 1);
$oPdf->cell(50, 4, "6", 1, 0, "C", 1);
$oPdf->cell(100, 4, "Dados atualizados pelo RG, conflito com definitivo", 1, 1, "C", 1);
$oPdf->cell(50,4, "7", 1, 0, "C", 1);
$oPdf->cell(100, 4, "Dados atualizados pela certidão", 1, 1, "C", 1);
$oPdf->cell(50, 4, "8", 1, 0, "C", 1);
$oPdf->cell(100, 4, "dados atualizados pela identidade", 1, 1, "C", 1);
$oPdf->cell(50, 4, "9", 1, 0, "C", 1);
$oPdf->cell(100, 4, "criado novo CGS", 1, 1, "C", 1);
$oPdf->cell(50, 4, "10", 1, 0, "C", 1);
$oPdf->cell(100, 4, "Dados atualizados pelo provisorio, inconsistencia de tipo", 1, 1, "C", 1);
$oPdf->Output();
?>