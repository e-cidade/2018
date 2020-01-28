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

//MODULO: caixa
$clcancdebitos->rotulo->label();
$clcancdebitosprot->rotulo->label();
$clcancdebitosreg->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k23_obs");
$clrotulo->label("nome");

?>
<form name="form1" method="post" action="">
<center>
<table border="0">
	<tr>
		<td>
		<fieldset ><Legend align="center"><b> Dados do cancelamento : </b></Legend>
		<table border="0" width="550px">
			<tr>
				<td nowrap title="<?=@$Tk20_codigo?>"><?=@$Lk20_codigo?></td>
				<td><?
				db_input('k20_codigo',10,$Ik20_codigo,true,'text',3,"")
				?></td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tk20_hora?>"><?=@$Lk20_hora?></td>
				<td><?
				db_input('k20_hora',10,$Ik20_hora,true,'text',3,"")
				?></td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tk20_data?>"><?=@$Lk20_data?></td>
				<td><? db_inputdata('k20_data',@$k20_data_dia,@$k20_data_mes,@$k20_data_ano,true,'text',3,"")?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tk25_codproc?>"><?=@$Lk25_codproc?></td>
				<td><? db_input('k25_codproc',20,$Ik25_codproc,true,'text',3,"");?>
				</td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Tk20_usuario?>"><?=@$Lk20_usuario?></td>
				<td><? db_input('k20_usuario',10,$Ik20_usuario,true,'text',3,"");
				       db_input('nome',49,$Inome,true,'text',3,"");
				    ?>
				</td>
			</tr>

			<tr>
				<td><strong>Descrição:</strong></td>
				<td><? db_input('k20_descr',50,$Ik20_descr,true,'text',3,"");
        ?></td>
			</tr>

			<tr>
				<td><strong>Observações:</strong></td>
				<td><? db_textarea('k21_obs',4,60,$Ik21_obs,true,'text',3,"") ?></td>
			</tr>
			<tr>
				<td><strong>Tipo:</strong></td>
				<td><? db_input('cancdebitostipo',10,"",true,'text',3,"")?></td>
			</tr>
            <? if(isset($k20_cancdebitostipo) and $k20_cancdebitostipo == 2 ) { ?>
			<tr>
				<td><strong>Caracteristica Peculiar:</strong></td>
				<td><? db_input('tipo',10,"",true,'text',3,"");
				       db_input('caracteristica',49,"",true,'text',3,"");
				    ?>
				</td>
			</tr>
			<? } ?>
			
		</table>
		</fieldset>
		</td>
	</tr>
</table>

<table>
	<tr>
		<td><?
		if(isset($chavepesquisa) && $chavepesquisa != ""){
		  
		  $sql = $clcancdebitos->sql_pendentes("k21_sequencia,k21_numpre,k21_numpar,sum(k00_valor) as k00_valor,k21_receit,(select k00_numcgm from arrenumcgm where k00_numpre = k21_numpre limit 1) as k00_numcgm, (select k00_matric from arrematric where k00_numpre = k21_numpre limit 1) as k00_matric, (select k00_inscr from arreinscr where k00_numpre = k21_numpre limit 1) as k00_inscr","k21_numpre,k21_numpar"," k20_codigo = $chavepesquisa  and k20_instit = ".db_getsession("DB_instit")."GROUP BY k21_sequencia,k21_numpre,k21_numpar,k21_receit");
		  
		  $result = pg_query($sql);
		  $linhas = pg_num_rows($result);
		  if($linhas>0){
		    $total = 0;
		    ?>
		   <fieldset >
                  <Legend align='center'><b> Dados do processamento </b></Legend>
            <table width='550px'>
            <tr>
				<td><strong>Observações:</strong></td>
				<td><? db_textarea('k23_obs',4,60,$Ik23_obs,true,'text',$db_opcao,"","","#FFFFFF; text-transform:uppercase")?></td>
			</tr>
			<tr>
				<td><strong>Tipo de cancelamento:</strong></td>
				<td><?
				  $resulttipo = pg_query("select k73_sequencial,k73_descricao from cancdebitostipo order by k73_sequencial");
				  $linhasTipo = pg_num_rows($resulttipo);
				  $tipo = array();
				  if($linhasTipo > 0 ){
				    for($t=0;$t<$linhasTipo;$t++){
				    	db_fieldsmemory($resulttipo, $t);
						$tipo[$k73_sequencial] = $k73_descricao;
					
				    }	
				  }
				  db_select("tipoDebito",$tipo,true,1,"onChange='js_mostraRenuncia(document.form1.tipoDebito.value);'");			
			
				?>
				</td>
			</tr>
			<tr  id="renuncia" style="display:none">
				<td><b><? db_ancora("Caracteristica peculiar:","js_pesquisac58_sequencial(true);",$db_opcao); ?></b></td>
				<td><? 
				  db_input("c58_sequencial",10,$Ic58_sequencial,true,"text",$db_opcao,"onChange='js_pesquisac58_sequencial(false);'");
                  db_input("c58_descr",49,0,true,"text",3);
				    ?>
				</td>
			</tr>
			
			
			</table>
			<br>
		    <table width='550px' class=tab>
		                   <tr>
                              <th align='center'>Sequencia</th>
		                      <th align='center'>Numpre   </th>
							  <th align='center'>Numpar   </th>
                              <th align='center'>Receita  </th>
                              <th align='center'>CGM </th>
                              <th align='center'>Matrícula </th>
                              <th align='center'>Inscrição </th>
							  <th align='center'>Valor    </th>
						   </tr>
			<?
		    for($x = 0; $x < $linhas; $x++) {
		      db_fieldsmemory($result,$x);
		      echo "       <tr>
                              <td align='center'>$k21_sequencia</td>
		                      <td align='center'>$k21_numpre   </td>
							  <td align='center'>$k21_numpar   </td>
                              <td align='center'>$k21_receit   </td>
                              <td align='center'>$k00_numcgm </td>
                              <td align='center'>$k00_matric </td>
                              <td align='center'>$k00_inscr </td>
							  <td align='center'>$k00_valor    </td>
						   </tr>
                   ";
		      $total+=$k00_valor;
            }
            echo "        <tr>
                              <td colspan=7 align='right'> Total  </td>
							  <td align='center'>$total</td>
                          </tr>
                   </table></fieldset";

		  }
		  
		}
		?></td>
	</tr>
		
</table>
<input name="processa" type="submit" id="db_opcao" value="Processar"
<?=($db_botao==false?"disabled":"")?>> <input name="pesquisar"
	type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
</form>

</center>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cancdebitos','func_cancdebitos.php?funcao_js=parent.js_preenchepesquisa|k20_codigo','Pesquisa',true);
}

function js_mostraRenuncia(id){
	
	if(id==2){
		document.getElementById("renuncia").style.display='';	
	}else{
		document.getElementById("renuncia").style.display='none';
	}
	
}
function js_pesquisac58_sequencial(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_concarpeculiar','func_concarpeculiar.php?funcao_js=parent.js_mostraconcarpeculiar1|c58_sequencial|c58_descr&filtro=receita','Pesquisa',true,'0','1');
  }else{
     if(document.form1.c58_sequencial.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_concarpeculiar','func_concarpeculiar.php?pesquisa_chave='+document.form1.c58_sequencial.value+'&funcao_js=parent.js_mostraconcarpeculiar&filtro=receita','Pesquisa',false);
     }else{
       document.form1.c58_descr.value = ''; 
     }
  }
}
function js_mostraconcarpeculiar(chave,erro){
  document.form1.c58_descr.value = chave; 
  if(erro==true){ 
    document.form1.c58_sequencial.focus(); 
    document.form1.c58_sequencial.value = ''; 
  }
}
function js_mostraconcarpeculiar1(chave1,chave2){
  document.form1.c58_sequencial.value = chave1;
  document.form1.c58_descr.value          = chave2;
  db_iframe_concarpeculiar.hide();
}
<?
if(isset($tipoDebito) and $tipoDebito==2){
  echo "js_mostraRenuncia(2);";	
}
?>

function js_preenchepesquisa(chave){
  db_iframe_cancdebitos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>