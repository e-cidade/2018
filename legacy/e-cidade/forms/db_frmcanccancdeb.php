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

//MODULO: caixa
$clcancdebitos->rotulo->label();
$clcancdebitosproc->rotulo->label();
$clcancdebitosreg->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k23_obs");
$clrotulo->label("nome"); 

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;

?>
<script>
</script>
<form name="form1" method="post" action="">
<center>
<table border=0>
<tr><td>
<!-- DADOS DO  CANCELAMENTO-->
<fieldset>
<Legend align="left"><b> Dados do cancelamento : </b></Legend>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tk20_codigo?>">
       <?=@$Lk20_codigo?>
    </td>
    <td> 
<?
db_input('k20_codigo',10,$Ik20_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk20_hora?>">
       <?=@$Lk20_hora?>
    </td>
    <td> 
<?
db_input('k20_hora',5,$Ik20_hora,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk20_data?>">
       <?=@$Lk20_data?>
    </td>
    <td> 
<? db_inputdata('k20_data',@$k20_data_dia,@$k20_data_mes,@$k20_data_ano,true,'text',3,"")?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk20_usuario?>">
       <?=@$Lk20_usuario?>
    </td>
    <td> 
     <? 
      db_input('k20_usuario',10,$Ik20_usuario,true,'text',3,""); 
      db_input('nome',40,$Inome,true,'text',3,"");
     ?>
    </td>
  </tr>
  <tr>
	<td><strong>Tipo:</strong></td>
	<td><? db_input('cancdebitostipo',10,"",true,'text',3,"")?></td>
  </tr>
  <? if(isset($k20_cancdebitostipo) and $k20_cancdebitostipo == 2 ) { ?>
  <tr>
	<td><strong>Caracteristica Peculiar:</strong></td>
	<td><? db_input('tipo',10,"",true,'text',3,"");
	       db_input('caracteristica',40,"",true,'text',3,"");
	    ?>
	</td>
  </tr>
  <? } ?>
  
  
  
 </table>
</fieldset>
</td>
</tr>
</center>
<table>
 <tr>
  <td >
	<?
	    if(isset($chavepesquisa) && $chavepesquisa != ""){
  				$sql  = " select distinct ";
				  $sql .= "        k21_sequencia, ";
					$sql .= "        k21_numpre, ";
					$sql .= "        k21_numpar, ";
					$sql .= "        k21_receit, ";
					$sql .= "        case when k00_descr is not null then k00_descr else 'PAGO' end as k00_descr, ";
					$sql .= "        case when k24_vlrhis <> 0 then k24_vlrhis ";
					$sql .= "          else k00_valor ";
					$sql .= "        end as k00_valor ";
					$sql .= "   from cancdebitosreg ";
					$sql .= "        left join cancdebitosproc    on k21_sequencia = k23_codigo ";
					$sql .= "        left join cancdebitosprocreg on k23_codigo = k24_codigo ";
					$sql .= "        left join arrecad           on k21_numpre = k00_numpre ";
					$sql .= "                                    and k21_numpar = k00_numpar ";
					$sql .= "                                    and k21_receit = k00_receit ";
					$sql .= "        left join arretipo           on arretipo.k00_tipo = arrecad.k00_tipo ";
					$sql .= " where k21_codigo = $chavepesquisa";
 // echo $sql;
					$cliframe_seleciona->campos        = " k21_sequencia,k21_numpre,k21_numpar,k21_receit,k00_descr,k00_valor ";
					$cliframe_seleciona->legenda       = " Debitos a cancelar ";
					$cliframe_seleciona->sql           = $sql;
					$cliframe_seleciona->textocabec    = "darkblue";
					$cliframe_seleciona->textocorpo    = "black";
					$cliframe_seleciona->fundocabec    = "#aacccc";
					$cliframe_seleciona->fundocorpo    = "#ccddcc";
					$cliframe_seleciona->iframe_height = "250";
					$cliframe_seleciona->iframe_width  = "500";
					$cliframe_seleciona->iframe_nome   = "Processados";
					$cliframe_seleciona->chaves        = "k21_sequencia,k21_numpre,k21_numpar,k21_receit";
					$cliframe_seleciona->marcador      = true;
					$cliframe_seleciona->iframe_seleciona(@$db_opcao);
			}
	?>
	</td>
 </tr>

</table>
 <input name="processa"  type="submit" id="db_opcao"  value="Processar" <?=($db_botao==false?"disabled":"")?> onclick="return js_submit();">
 <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
 <!-- <input name="chaves"    type="hidden" id="chaves"       value=""> -->
<script>
function js_submit(){
   	js_gera_chaves();
		return true;
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cancdebitos','func_canccancdeb.php?funcao_js=parent.js_preenchepesquisa|k20_codigo','Pesquisa',true);
//document.form1.submit();
}
function js_preenchepesquisa(chave){
  db_iframe_cancdebitos.hide();
  <? echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;"; ?>
}
</script>