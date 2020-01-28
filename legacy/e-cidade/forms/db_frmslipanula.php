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

include("classes/db_conplanoexe_classe.php");
include("classes/db_saltes_classe.php");
$clsaltes = new cl_saltes;
$clconplanoexe = new cl_conplanoexe;
$clrotulo = new rotulocampo;
$clrotulo->label("k17_hist");
$clrotulo->label("k17_codigo");
$clrotulo->label("k18_motivo");
//$clrotulo->label("k17_dtanu");
// $clrotulo->label("k17_valor");
$clrotulo->label("c50_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
?>
<form name="form1" method="post" onsubmit="return js_gravar();">
<input type="hidden" name="chaves" value="">
<br>
<center>
<table>
<tr>
<td>
<fieldset>
<legend><b>Anulação de Slip</legend>
<table border=0>  
  <tr>
    <td align="left">    <strong>Código do Slip:</strong>  </td>
    <td><?  db_input("k17_codigo", 10, $Ik17_codigo, true, 'text', 3, "", "numslip");  ?>   </td>
  </tr>
  <tr>
    <td align="left"><strong>
     <? db_ancora('Conta a Debitar (Receber): ',"js_pesquisac01_reduz(true);",3);   ?></strong></td>
    <td nowrap>
     <? db_input("debito", 10, true,"text",3); 
        db_input("descrdebito", 40, true,"text",3)
     ?></td>
  </tr>
  <tr>
    <td align="left"><strong><? db_ancora('Conta a Creditar (Pagar): ',"js_pesquisac01_reduz1(true);",$db_opcao); ?></strong></td>
    <td nowrap>
    <? db_input("credito", 10, true,"text",3); 
        db_input("descrcredito", 40, true,"text",3)
     ?>
    </td>
  </tr>
  <tr>
    <td align="left"><? db_ancora(@$Lk17_hist,"js_pesquisac50_codhist(true);",$db_opcao);  ?></td>
    <td><?
                  db_input('k17_hist',10,$Ik17_hist,true,'text',$db_opcao," onchange='js_pesquisac50_codhist(false);'");
                  db_input('c50_descr',40,$Ic50_descr,true,'text',3);
            ?>
    </td>
  </tr>
  <tr>
    <td align="left">
      <? 
        db_ancora("<b>CGM do Favorecido:</b>","js_pesquisaz01_numcgm(true);",3); 
      ?>
    </td>
    <td><?
                  db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'");
                  db_input('z01_nome',40,$Iz01_nome,true,'text',3);
             ?>
    </td>
  </tr>
  <tr>
    <td align="left"><strong>Valor da Transação:</strong></td>
    <td><?               
               db_input("k17_valor", 10, 4, true,'text',$db_opcao," onBlur=js_atuValor(this.value);");
	     ?>    
    </td>
  </tr>
  <?
   if ($db_opcao == 22 || $db_opcao == 2) {
    ?> 
    <tr>
    <td align="left"><b>Data Anulação:</b></td>
     <td>
     
     <?db_inputdata("k17_dtanu",null,null,null,true,"text",1);?>
     </td>
     </tr>
  <?   
   }
  
  ?>
  <tr>
    <td align="left" valign="top"><strong>Observações:</strong></td>
    <td align="left" valign="top">
      <?
            if((!isset($texto) || (isset($texto) && trim($texto) == "")) && isset($k17_texto) && trim($k17_texto) != ""){
                  $texto = $k17_texto;
           }
           db_textarea("texto", 3, 60, 0, true, 'text', (isset($read_only) && trim($read_only) != "" ? 3 : 1), "onDblClick='document.form1.texto.value=\"\"'");
      ?>
    </td>
  </tr> 
  <tr>
    <td align="left" valign=""><strong>Motivo:</strong></td>
    <td align="left" valign="top">
      <?
            if((!isset($texto) || (isset($texto) && trim($texto) == "")) && isset($k17_texto) && trim($k17_texto) != ""){
                  $texto = $k17_texto;
           }
           db_textarea("k18_obs", 6, 60, 0, true, 'text', 1, "onDblClick='document.form1.texto.value=\"\"'");
      ?>
    </td>
  </tr>   
  <tr>
      <td >  
            <b>Recursos:</b>
      </td>
      <td >
           <div id=tbl style="overflow:auto; border:2px inset white;height:100px;max-height:100px">
              <table cellspacing="0" id=tabRecursos width=100% border=0 style="border:1px solid #000000;background-color: white">
                 <tr stye="font-weight:bold">
                      <td class="table_header" width=10%> RECURSO</td>
                      <td class="table_header" width=75%> DESCRIÇÃO </td>
                      <td class="table_header" width=10%> VALOR </td>
                 </tr>
                 <?
                 if(isset($k17_codigo) && trim($k17_codigo) != ""){
	                  $sSqlRecurso  = "select o15_codigo, o15_descr,k29_valor";
	                  $sSqlRecurso .= "  from sliprecurso ";
	                  $sSqlRecurso .= "       inner join orctiporec on o15_codigo =  k29_recurso";
	                  $sSqlRecurso .=  " where k29_slip =  {$k17_codigo}";
	                  $rsRecuros    = db_query($sSqlRecurso);
	                  $aRecursos    = db_utils::getColectionByRecord($rsRecuros);
	                  foreach ($aRecursos as $oRecurso) {
	                    
	                    echo "<tr>";
	                    echo "<td class='linhagrid' style='text-align:right'>{$oRecurso->o15_codigo}</td>"; 
	                    echo "<td class='linhagrid' style='text-align:left'>{$oRecurso->o15_descr}</td>"; 
	                    echo "<td class='linhagrid' style='text-align:right'>".db_formatar($oRecurso->k29_valor,"f")."</td>"; 
	                    echo "</tr>";
	                    
	                  }
                 }
                 ?>
              </table>  
           </div>
      </td>
  </tr>
  <tr>
    <td colspan=2 align=center>
         <!--<input type="button"  onclick="js_gravar();"  name="confirma" value="Emitir Slip">
         -->
    </td>  
  </tr>
</table>
</fieldset>
</td>
</tr>
</table>
<input type='submit'  name='confirmar' value='Anular' >
<input name="pesquisar" type="button" id="pesquisar" value='Pesquisar' onclick="js_pesquisa()">
</center>
</form>
<script>
</script>