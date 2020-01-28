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

//MODULO: Ambulatorial
$clunidadeservicos->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd87_c_nome");

?>
<fieldset style="width:75%"><legend><b>Serviço</b></legend>

<form name="form1" method="post" action="">
<center>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Ts126_i_codigo?>">
       <?=@$Ls126_i_codigo?>
    </td>
    <td> 
<?
db_input('s126_i_codigo',10,$Is126_i_codigo,true,'text',3,"")
?>
<?
db_input('s126_i_unidade',10,$Is126_i_unidade,true,'hidden',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts126_i_servico?>">
       <?
       db_ancora(@$Ls126_i_servico,"js_pesquisas126_i_servico(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('s126_i_servico',10,$Is126_i_servico,true,'text',$db_opcao," onchange='js_pesquisas126_i_servico(false);'")
?>
       <?
db_input('sd87_c_nome',80,$Isd87_c_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts126_d_ativado?>">
       <?=@$Ls126_d_ativado?>
    </td>
    <td> 
<?
db_inputdata('s126_d_ativado',@$s126_d_ativado_dia,@$s126_d_ativado_mes,@$s126_d_ativado_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts126_d_desativado?>">
       <?=@$Ls126_d_desativado?>
    </td>
    <td> 
<?
db_inputdata('s126_d_desativado',@$s126_d_desativado_dia,@$s126_d_desativado_mes,@$s126_d_desativado_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  
  
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?
if( $db_opcao!=1){
	?>
	<input name="cancelar" type="submit" value="Cancelar" >
	
	<?
}
?>
</form>

<table>
  <tr>
    <td>
      <?
      $chavepri = array ("s126_i_codigo" => @$s126_i_codigo );
      $cliframe_alterar_excluir->chavepri = $chavepri;
      @$cliframe_alterar_excluir->sql = $clunidadeservicos->sql_query ( "", "*", "s126_i_codigo", "s126_i_unidade = " . ( int )$chavepesquisa );

      $cliframe_alterar_excluir->campos = "s126_i_codigo, sd87_c_nome, s126_d_ativado, s126_d_desativado";
      $cliframe_alterar_excluir->legenda = "";
      $cliframe_alterar_excluir->alignlegenda = "left";
      $cliframe_alterar_excluir->msg_vazio = "Não foi encontrado nenhum registro.";
      $cliframe_alterar_excluir->textocabec = "#DEB887";
      $cliframe_alterar_excluir->textocorpo = "#444444";
      $cliframe_alterar_excluir->fundocabec = "#444444";
      $cliframe_alterar_excluir->fundocorpo = "#eaeaea";
      $cliframe_alterar_excluir->tamfontecabec = 9;
      $cliframe_alterar_excluir->tamfontecorpo = 9;
      $cliframe_alterar_excluir->formulario = false;
      $cliframe_alterar_excluir->iframe_alterar_excluir ( $db_opcao==1?1:22 );
      ?>
    
    </td>
  </tr>

</table>

</fieldset>

<script>
function js_pesquisas126_i_servico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_servclassificacao','func_sau_servclassificacao.php?funcao_js=parent.js_mostrasau_servclassificacao1|sd87_i_codigo|sd87_c_nome','Pesquisa',true);
  }else{
     if(document.form1.s126_i_servico.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_sau_servclassificacao','func_sau_servclassificacao.php?pesquisa_chave='+document.form1.s126_i_servico.value+'&funcao_js=parent.js_mostrasau_servclassificacao','Pesquisa',false);
     }else{
       document.form1.sd87_c_nome.value = ''; 
     }
  }
}
function js_mostrasau_servclassificacao(chave,erro){
  document.form1.sd87_c_nome.value = chave; 
  if(erro==true){ 
    document.form1.s126_i_servico.focus(); 
    document.form1.s126_i_servico.value = ''; 
  }
}
function js_mostrasau_servclassificacao1(chave1,chave2){
  document.form1.s126_i_servico.value = chave1;
  document.form1.sd87_c_nome.value = chave2;
  db_iframe_sau_servclassificacao.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_unidadeservicos','func_unidadeservicos.php?funcao_js=parent.js_preenchepesquisa|s126_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_unidadeservicos.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>