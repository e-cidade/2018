<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once('libs/db_utils.php');
require_once('libs/db_stdlibwebseller.php');
require_once('libs/db_stdlib.php');
require_once("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$oDaoMatreQuiItem      = new cl_matrequiitem();      //$clmatrequiitem = new cl_matrequiitem;
$oDaoAtendRequi        = new cl_atendrequi();        //$clatendrequi  = new cl_atendrequi;
$clatendrequiitem      = new cl_atendrequiitem;
$oDaoMatEstoque        = new cl_matestoque();        //$clmatestoque = new cl_matestoque;
$oDaoMatEstoqueDevItem = new cl_matestoquedevitem(); //$clmatestoquedevitem = new cl_matestoquedevitem

$oDaoMatreQuiItem->rotulo->label();
$clRotulo = new rotulocampo;
$clRotulo->label("m60_descr");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>
.bordas {
  border: 2px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #999999;
}
.bordas_corp {
  border: 1px solid #cccccc;
  border-top-color: #999999;
  border-right-color: #999999;
  border-left-color: #999999;
  border-bottom-color: #999999;
  background-color: #cccccc;
}
[disabled] {
 background-color: #DEB887;
 color:#696969;
}

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr>
    <td  align="left" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <center>
 <table border='0' cellspacing="0" style="border: 2px inset white;background-color: white">
<?

if (    isset( $fa22_i_cgsund ) && !empty( $fa22_i_cgsund ) 
     && isset( $fa23_i_matersaude ) && !empty( $fa23_i_matersaude ) ) {

  /**
   *  Dados: Num CGS, Nome do remedio
   *  Com esses dois dados encontrar o codigo dos itens que serão listados
   * */
  $oFarRetirada = new cl_far_retirada();
  $sCampos      = "distinct on(fa04_i_codigo) fa06_i_codigo, fa04_i_codigo, fa04_i_unidades, m41_codmatmater";
  $sCampos     .= " ,fa06_f_quant, fa04_tiporetirada,m60_descr, m41_obs, m41_codigo, m82_codigo, m43_codigo, m43_quantatend";
  $sCampos     .= " ,m77_lote, m77_dtvalidade,fa23_i_codigo";
  $sWhere       = "    fa04_i_cgsund = {$fa22_i_cgsund}";
  $sWhere      .= "and fa06_i_matersaude = {$fa23_i_matersaude}";
  $sOrder       = "fa04_i_codigo desc";
  $sSql         = $oFarRetirada->sql_query_devov("", $sCampos, $sOrder, $sWhere);
  $rsSql        = $oFarRetirada->sql_record($sSql);
  $iLinhas      = $oFarRetirada->numrows;
  
  if ($iLinhas > 0) {
  
    echo "
        <tr class='table_header'>
          <td class='table_header' align='center'><b><small>Cod. Retirda</small></b></td>
          <td class='table_header' align='center'><b><small>UPS</small></b></td>
          <td class='table_header' align='center'><b><small>Lote</small></b></td>
          <td class='table_header' align='center'><b><small>Validade</small></b></td>
          <td class='table_header' align='center'><b><small>Quant. Atendida</small></b></td>
          <td class='table_header' align='center'><b><small>Quant. Devolvida</small></b></td>
          <td class='table_header' align='center'><b><small>Saldo disponível</small></b></td>
          <td class='table_header' align='center'><b><small>Quantidade</small></b></td>
          <td class='table_header' align='center'><b><small>Motivo</small></b></td>
          <td class='table_header' align='center'><b><small>Tipo</small></b></td>";
  
     echo " </tr>";
  } else {
    echo"<b>Nenhum registro encontrado...</b>"; 
  }
  
  for ($iConta = 0; $iConta < $iLinhas; $iConta++) {
  
    $oVariaveis =  db_utils::fieldsmemory($rsSql, $iConta);
    
    echo "<tr>      
            <td class='linhagrid' align='center'>
              <small>$oVariaveis->fa04_i_codigo</small>
            </td>
            <td class='linhagrid' align='center'>
              <small>$oVariaveis->fa04_i_unidades</small>
            </td>
            <td class='linhagrid' align='center'>
              <small>$oVariaveis->m77_lote </small>
            </td>
            <td   class='linhagrid' align='center'>
              <small>";
                $dValidade = db_formatar($oVariaveis->m77_dtvalidade,'d');
                echo"$dValidade
             </small>
           </td>
          ";

    if ( !empty( $oVariaveis->m43_codigo ) ) {
      
      $iQuant_Devolvida      = 0;
      $sSql2                 = $oDaoMatEstoqueDevItem->sql_query_file( null, "*", null, "m46_codatendrequiitem = {$oVariaveis->m43_codigo}" );
      $rsDevol               = $oDaoMatEstoqueDevItem->sql_record( $sSql2 );
      $iLinhasMatEstoDevItem = $oDaoMatEstoqueDevItem->numrows;
      
      if ($iLinhasMatEstoDevItem != 0) {
    
        for ($iContar = 0; $iContar < $iLinhasMatEstoDevItem; $iContar++) {
          
          $oDevolvid         =  db_utils::fieldsmemory( $rsDevol, $iContar );
          $iQuant_Devolvida += $oDevolvid->m46_quantdev;
        }
      }
    }
    
    if ($oVariaveis->fa04_tiporetirada == 1) {
  
      $iQuant     = $oVariaveis->m43_quantatend;
      $iQuant_sol = $iQuant-$iQuant_Devolvida;
    } else {
   
      $quant                       = $oVariaveis->fa06_f_quant;
      $oVariaveis->m43_quantatend  = $oVariaveis->fa06_f_quant;
      $iQuant_sol                  = $oVariaveis->fa06_f_quant;
  
      if ($oVariaveis->fa23_i_codigo != null) {
  
        $iQuant_Devolvida = $oVariaveis->fa06_f_quant;
        $iQuant_sol       = 0;
      }
    }
  
    if ($iQuant_Devolvida == 0) {
      $iQuant_sol *= -1;
    }
  
    if ($iQuant_sol < 0) {
      $iQuant_sol *= -1;
    }
  
    $iQuantidade  = "quant_$oVariaveis->m41_codmatmater";
    $iQuantidade .="_"."$oVariaveis->m41_codigo"."_".$iConta."_".$oVariaveis->m82_codigo;
    $iQuantidade = "";
    $iOp         = 1;
          
    if ($oVariaveis->m43_quantatend == 0 || $iQuant_sol == 0) {
      $iOp = 3;
    }
    
    echo "
         <td class='linhagrid' align='center'>
           <small>$oVariaveis->m43_quantatend</small>
         </td>
         <td class='linhagrid' align='center'><small>$iQuant_Devolvida</small></td>";
    echo "<td class='linhagrid' align='center'>
            <small>$iQuant_sol</small>
         </td>";
    echo "<td class='linhagrid' align='center'>
            <small>";
              $sNomeQuant  = "quant_$oVariaveis->m43_codigo"."_"."$oVariaveis->m41_codmatmater";
              $sNomeQuant .= "_"."$oVariaveis->m41_codigo"."_".$iConta."_".$oVariaveis->m82_codigo.'_'.$oVariaveis->fa06_i_codigo;
              $sDisabled   = "";
  
    if ($oVariaveis->fa04_tiporetirada == 2) {
      $sDisabled = "disabled";
    }
    
    $sNomeCancel  = "cancelamento_";
    $sNomeCancel .= $oVariaveis->m43_codigo;
    
    db_input($sNomeQuant,6,0,true,'text',$iOp,
    
    $sDisabled.' onchange="js_verifica('.$iQuant_sol.',this.value,this.name,'.$iQuant_sol.','.$sNomeCancel.');"');
    echo "</small></td>";
    echo "<td   class='linhagrid' align='center'><small>";
    
    db_input("motivo_$oVariaveis->m43_codigo",12,0,true,'text',$iOp,"", '', '', '', 40);
    echo "</small></td>";
    echo "<td   class='linhagrid' align='center'><small>";
  
    if ($oVariaveis->fa04_tiporetirada == 1) {
      $aX = array('2' => 'Devolução', '1' => 'Cancelamento');
    } else{
      $aX = array('0'=>'Selecione::','1' => 'Cancelamento');
    }
    
    db_select('cancelamento_'.$oVariaveis->m43_codigo, $aX, true, $iOp,
              ' onchange="js_cancelamento('.$iQuant_sol.',this.value,\''.$sNomeQuant.'\');"');
    echo"  </td>";
    echo"  </tr>";
  }
} else {
  echo"<b>Nenhum registro encontrado...</b>"; 
}
?>
</table>
    </form>
    </center>
    </td>
  </tr>
</table>
<script>
/*iQuant->Quantidade devolvida;
 *iMax ->Quantidade de medicamentos retirados pelo paciente
 *iSaldo->Saldo disponivel para devolução
 *sNomeCampoQtd-> nome do campo quantidade
 * sNomeCampoSel-> objeto do campo tipo de devolução, 
 */

function js_verifica(iMax, iQuant, sNomeCampoQtd, iSaldo, sNomeCampoSel) {
  
  if (iMax < iQuant) {

    alert('Informe uma quantidade valida!!\nQuantidade não disponível');
    eval("document.form1."+sNomeCampoQtd+".value='';");
    eval("document.form1."+sNomeCampoQtd+".focus();");
  }

  if (iQuant > iSaldo) {

    alert("Saldo indisponível!!\nQuantidade não disponível");
    return false;
  }

  if (iSaldo == iQuant) {

    alert("Tipo de devolução CANCELAMENTO");
    js_cancelamento(iSaldo,1,sNomeCampoQtd,sNomeCampoSel);
  }
}

function js_cancelamento(iQtdeDisp, iCancelamento, sNomeCampoQtde, sNomeCampo) {
   
  oCampoQtde = document.getElementById(sNomeCampoQtde);
  
  /* foi selecionado DEVOLUÇÃO no select do cancelamento */
  if (iCancelamento == 1) {
    
    if (sNomeCampo != null) {
      sNomeCampo.value     = 1;
    }
    
    oCampoQtde.value    = iQtdeDisp;
    oCampoQtde.disabled = true;
  } else if ((iCancelamento == 2)) { /* foi selecionado CANCELAMENTO no select do cancelamento */
    oCampoQtde.disabled = false;
  } else {
    
    oCampoQtde.disabled = true;
    oCampoQtde.value    = '';
  } 
}
</script>
</body>
</html>