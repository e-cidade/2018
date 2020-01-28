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

//MODULO: caixa
require_once("dbforms/db_classesgenericas.php");

$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo           = new rotulocampo;
$clcancdebitos    ->rotulo->label();
$clcancdebitosproc->rotulo->label();
$clcancdebitosreg ->rotulo->label();
$clrotulo         ->label("k23_obs");
$clrotulo         ->label("nome"); 


?>
<script>
function js_validaSuspensao() {

  var objInput = processados.document.getElementsByTagName("input");

  if ( document.form1.suspensao.value == "s") {
    
    for (i = 0; i < objInput.length; i++) {
      
  	  if ( objInput[i].type == "checkbox" ) {
    	  
  		  objInput[i].checked  = true;
  		  objInput[i].disabled = true;
  	  }
	  }    
  }
}
</script>
<style>

  fieldset {
	  width: 800px;
	  margin-top: 10px;
  }

</style>

<form name="form1" method="post" action="">
<center>

<!-- DADOS DO PROCESSAMENTO -->
  
  <fieldset >
  <Legend align="left"><b> Dados do processamento : </b></Legend>
  <table border="0" align='left'>
    <tr>
      <td nowrap title="<?=@$Tk23_codigo?>">
       <?=@$Lk23_codigo?>
      </td>
      <td> 
        <?
          db_input('k23_codigo',10,$Ik23_codigo,true,'text',3,"");
          db_input('suspensao',10,$Ik23_codigo,true,'hidden',3,"");
        ?>
      </td>
    </tr>
 
    <tr>
      <td nowrap title="<?=@$Tk23_data?>">
        <?=@$Lk23_data?>
      </td>
      <td> 
       <? db_inputdata('k23_data',@$k23_data_dia,@$k23_data_mes,@$k23_data_ano,true,'text',3,"");?>
       <?=@$Lk23_hora?>
       <? db_input('k23_hora',5,$Ik23_hora,true,'text',3,"") ?>

      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Tk23_usuario?>">
        <?=@$Lk23_usuario?>
      </td>
      <td> 
        <? 
          db_input('k23_usuario',10,$Ik23_usuario,true,'text',3,"");
          db_input('nome_proc',35,$Inome,true,'text',3,"") ;
        ?>
      </td>
    </tr>
    <tr>
    </tr>
    <tr>
      <td><strong>Observações:</strong></td><td>
        <? db_textarea('k23_obs',2,47,$Ik23_obs,true,'text',3,"","","")?></td>
    </tr>
    <tr>
	    <td><strong>Tipo:</strong></td>
	    <td><? db_input('cancdebitostipoproc',10,"",true,'text',3,"")?></td>
    </tr>
      <? if(isset($k23_cancdebitostipo) and $k23_cancdebitostipo == 2 ) { ?>
    <tr>
	    <td><strong>Caracteristica Peculiar:</strong></td>
  	  <td><? db_input('tipoproc',10,"",true,'text',3,"");
  	         db_input('caracproc',35,"",true,'text',3,"");
  	      ?>
  	  </td>
    </tr>
        <? } ?>
 </table>
</fieldset>

<!-- DADOS DO  CANCELAMENTO-->

  <fieldset >
  <Legend align="left"><b> Dados do cancelamento : </b></Legend>
  <table border="0" align="left">
    <tr>
      <td nowrap title="<?=@$Tk20_codigo?>" >
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
      <? db_input('k20_usuario',10,$Ik20_usuario,true,'text',3,"");
         db_input('nome',35,$Inome,true,'text',3,"");
      ?>
      </td>
    </tr>
  
    <tr>
	    <td><strong>Tipo:</strong></td>
	    <td><? db_input('cancdebitostipo',10,"",true,'text',3,"")?></td>
    </tr>
      <? 
  	    if (isset($suspensao) && $suspensao == "s" ) {
	   
  	      echo "<tr>	    ";
	        echo "  <td      ";
	        db_ancora("<b>Suspensão:</b>","js_pesquisaSuspensao({$ar19_suspensao})",$db_opcao,"");
	        echo "  </td>	";	   
	        echo "  <td>									 ";
	        db_input('ar19_suspensao',10,"",true,'text',3,"");
	        echo "  </td>	";
	        echo "</tr>	    ";
        }
  
      ?>  
  
      <? if(isset($k20_cancdebitostipo) and $k20_cancdebitostipo == 2 ) { ?>
    <tr>
	    <td><strong>Caracteristica Peculiar:</strong></td>
	    <td><? db_input('tipo',10,"",true,'text',3,"");
	       db_input('caracteristica',35,"",true,'text',3,"");
	    ?>
	    </td>
    </tr>
  <? } ?>
    
 </table>
</fieldset>

	<?
     if(isset($chavepesquisa) && $chavepesquisa != ""){
					 $sql = $clcancdebitos->sql_pendentesproc(" distinct k21_sequencia,
                                                     k21_numpre,
                                                     k21_numpar,
																										 k21_receit,
                                                     arrecant.k00_receit,
																										 tabrec.k02_descr,
																										 arrecant.k00_dtoper,
																										 arrecant.k00_dtvenc,
                                                     arrecant.k00_valor,
																										 arretipo.k00_tipo,
																										 arretipo.k00_descr,
																										 arrematric.k00_matric,
																										 arreinscr.k00_inscr",
                                                     "k21_numpre,k21_numpar,k21_receit",
                                                     " k20_codigo = $chavepesquisa and k20_instit = ".db_getsession("DB_instit"));

					$resultcancdebitos = $clcancdebitos->sql_record($sql);
					if($clcancdebitos->numrows > 0){
					  db_fieldsmemory($result,0);
					  $db_botao = true;
					  $db_opcao = 1;
					}
					$cliframe_seleciona->campos        = "k21_sequencia,k21_numpre,k21_numpar,k21_receit,k02_descr,k00_dtoper,k00_dtvenc,k00_valor,k00_tipo,k00_descr,k00_matric,k00_inscr ";
					$cliframe_seleciona->legenda       = " Debitos processados a cancelar ";
					$cliframe_seleciona->sql           = $sql;
					$cliframe_seleciona->textocabec    = "darkblue";
					$cliframe_seleciona->textocorpo    = "black";
					$cliframe_seleciona->fundocabec    = "#aacccc";
					$cliframe_seleciona->fundocorpo    = "#ccddcc";
					$cliframe_seleciona->iframe_height = "250";
					$cliframe_seleciona->iframe_width  = "800";
					$cliframe_seleciona->iframe_nome   = "processados";
					$cliframe_seleciona->chaves        = "k21_sequencia,k21_numpre,k21_numpar,k21_receit";
					$cliframe_seleciona->dbscript      = "";
					$cliframe_seleciona->marcador      = true;
					$cliframe_seleciona->iframe_seleciona(@$db_opcao);
        
        
       echo "<script>js_validaSuspensao();</script>";
     }
	?>
 <div style="margin-top: 10px;">  
   <input name="processa"  type="submit" id="db_opcao"  value="Processar" <?=($db_botao==false?"disabled":"")?> onclick="return js_submit();">
   <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
 </div>
</form>
 
<script>
	   

	    
function js_submit() {
  
	if ( document.form1.suspensao.value == "s") {

	  if (confirm("A origem deste débito é uma suspensão, serão cancelados todos débitos processados desse cancelamento. Deseja continuar? ")){

			var objInput = processados.document.getElementsByTagName("input");
		  for (i=0; i < objInput.length; i++)  {
		    if ( objInput[i].type == "checkbox" ) {
			    objInput[i].checked = true;
		    }
		  }
	  } else {
	    return false;
	  }
	}  
  js_gera_chaves();
	return true;

}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_cancdebitos',
                      'func_canceladebitosanulaprocessamento.php?funcao_js=parent.js_preenchepesquisa|k20_codigo',
                      'Pesquisa',
                      true);
  //js_OpenJanelaIframe('top.corpo','db_iframe_cancdebitos','func_canccancproc.php?funcao_js=parent.js_preenchepesquisa|k20_codigo','Pesquisa',true);

}

function js_pesquisaSuspensao(iCodSuspensao){
  js_OpenJanelaIframe('top.corpo','db_iframe_consultasusp'+iCodSuspensao,'cai3_consultasusp001.php?suspensao='+iCodSuspensao,'Consulta Suspensão',true);
}

function js_preenchepesquisa(chave){

  db_iframe_cancdebitos.hide();
  <?
   echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  ?>
}

</script>