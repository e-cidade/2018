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
include("dbforms/db_classesgenericas.php");
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clextratolinha->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("k66_descricao");
$clrotulo->label("k85_nomearq");
$clrotulo->label("k13_descr");
$clrotulo->label("db83_descricao");

if (isset($db_opcaoal)) {
  $db_opcao=33;
  $db_botao=false;
} else if(isset($opcao) && $opcao=="alterar") {
  $db_botao=true;
  $db_opcao = 2;
} else if(isset($opcao) && $opcao=="excluir") {
  $db_opcao = 3;
  $db_botao=true;
} else {
	  
  $db_opcao = 1;
  $db_botao=true;
  
  if(isset($novo) || isset($alterar) || isset($excluir) || (isset($incluir) && $sqlerro==false ) ){
    $k86_bancohistmov = "";
    $k86_contabancaria = "";
    $k86_data         = "";
	  $k86_data_ano     = "";
	  $k86_data_mes     = "";
	  $k86_data_dia     = ""; 
    $k86_valor        = "";
    $k86_tipo         = "";
    $k86_historico    = "";
    $k86_documento    = "";
    $k86_lote         = "";
    $k86_loteseq      = "";
	  $k66_descricao    = "";
	  $db83_descricao   = "";
	  $k86_observacao   = "";
  }
  
}

//caso a opção seja alteração, não permite alterar o valor deste campo 
$ativo = $db_opcao;
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td >
    </td>
    <td> 
      <?
      db_input('k86_sequencial',10,$Ik86_sequencial,true,'hidden',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk86_extrato?>">
      <?
      db_ancora(@$Lk86_extrato,"js_pesquisak86_extrato(true);",3);
      ?>
    </td>
    <td> 
      <?
      db_input('k86_extrato',10,$Ik86_extrato,true,'text',3," onchange='js_pesquisak86_extrato(false);'")
      ?>
       <?
       db_input('k85_nomearq',50,$Ik85_nomearq,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk86_bancohistmov?>">
       <?
       db_ancora(@$Lk86_bancohistmov,"js_pesquisak86_bancohistmov(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('k86_bancohistmov',10,$Ik86_bancohistmov,true,'text',$db_opcao," onchange='js_pesquisak86_bancohistmov(false);'")
?>
       <?
db_input('k66_descricao',50,$Ik66_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk86_contabancaria?>">
       <?
       db_ancora(@$Lk86_contabancaria,"js_pesquisak86_contabancaria(true);",$db_opcao);
       ?>
    </td>
    <td nowrap> 
<?
db_input('k86_contabancaria',10,$Ik86_contabancaria,true,'text',$ativo," onchange='js_pesquisak86_contabancaria(false);'")
?>
       <?
db_input('db83_descricao',50,$Idb83_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk86_data?>">
       <?=@$Lk86_data?>
    </td>
    <td> 
<?
db_inputdata('k86_data',@$k86_data_dia,@$k86_data_mes,@$k86_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
	  <td>
		  <b>Tipo :</b>
		</td>
		<td>
		<?
		  $arraytipo = array('D'=>'Débito','C'=>'Crédito'); 
      db_select('k86_tipo',$arraytipo,true,$ativo);
		?>
		</td>
	</tr>

  <tr>
    <td nowrap title="<?=@$Tk86_valor?>">
       <?=@$Lk86_valor?>
    </td>
    <td> 
    <?
      db_input('k86_valor',10,$Ik86_valor,true,'text',$db_opcao,"", "", "", "", 15);
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk86_historico?>">
       <?=@$Lk86_historico?>
    </td>
    <td> 
<?
db_input('k86_historico',64,$Ik86_historico,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk86_documento?>">
       <?=@$Lk86_documento?>
    </td>
    <td> 
      <?
      db_input('k86_documento',64,$Ik86_documento,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>

<? if ($ativo != 3) { ?>
  <tr>
    <td nowrap>
      <strong>Recalcula Saldo do Extrato: </strong>
    </td>
    <td> 
<?
 $x = array("t"=>"SIM","f"=>"NÃO");
 db_select("recalcula",$x, true, 1);
?>
    </td>
  </tr>  
<? } ?>  

  <tr>
    <td nowrap title="<?=@$Tk86_observacao?>" colspan="2">
      <fieldset>
        <legend><b>Observação</b></legend>
        <?
          db_textarea('k86_observacao',2,90,$Ik86_observacao,true,'text',$db_opcao,"");
        ?>
      </fieldset>
    </td>
  </tr>





  <tr>
    <td colspan="2" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
      <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1||isset($db_opcaoal)?"style='visibility:hidden;'":"")?> >
    </td>
  </tr>
  </table>
 <table>
  <tr>
    <td valign="top"  align="center">  
    <?
	 $chavepri= array("k86_sequencial"=>@$k86_sequencial);
	 $cliframe_alterar_excluir->chavepri      = $chavepri;
	 $cliframe_alterar_excluir->sql           = $clextratolinha->sql_query_file(null,"*","extratolinha.k86_data, extratolinha.k86_sequencial"," k86_extrato = $k86_extrato ");
	 $cliframe_alterar_excluir->sql_disabled  = "select * from extratolinha inner join conciliaextrato on k86_sequencial = k87_extratolinha where k86_extrato = $k86_extrato ";
	 $cliframe_alterar_excluir->campos        = "k86_sequencial,k86_extrato,k86_bancohistmov,k86_contabancaria,k86_data,k86_valor,k86_tipo,k86_historico,k86_documento,k86_lote,k86_loteseq";
	 $cliframe_alterar_excluir->legenda       = "ITENS LANÇADOS";
	 $cliframe_alterar_excluir->iframe_height = "180";
	 $cliframe_alterar_excluir->iframe_width  = "710";
	 $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
    ?>
    </td>
   </tr>
 </table>
  </center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisak86_bancohistmov(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_extratolinha','db_iframe_bancoshistmov','func_bancoshistmov.php?funcao_js=parent.js_mostrabancoshistmov1|k66_sequencial|k66_descricao','Pesquisa',true,'0');
  }else{
     if(document.form1.k86_bancohistmov.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_extratolinha','db_iframe_bancoshistmov','func_bancoshistmov.php?pesquisa_chave='+document.form1.k86_bancohistmov.value+'&funcao_js=parent.js_mostrabancoshistmov','Pesquisa',false);
     }else{
       document.form1.k66_descricao.value = ''; 
     }
  }
}
function js_mostrabancoshistmov(chave,erro){
  document.form1.k66_descricao.value = chave; 
  if(erro==true){ 
    document.form1.k86_bancohistmov.focus(); 
    document.form1.k86_bancohistmov.value = ''; 
  }
}
function js_mostrabancoshistmov1(chave1,chave2){
  document.form1.k86_bancohistmov.value = chave1;
  document.form1.k66_descricao.value = chave2;
  db_iframe_bancoshistmov.hide();
}
function js_pesquisak86_extrato(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_extratolinha','db_iframe_extrato','func_extrato.php?funcao_js=parent.js_mostraextrato1|k85_sequencial|k85_nomearq','Pesquisa',true,'0');
  }else{
     if(document.form1.k86_extrato.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_extratolinha','db_iframe_extrato','func_extrato.php?pesquisa_chave='+document.form1.k86_extrato.value+'&funcao_js=parent.js_mostraextrato','Pesquisa',false);
     }else{
       document.form1.k85_nomearq.value = ''; 
     }
  }
}
function js_mostraextrato(chave,erro){
  document.form1.k85_nomearq.value = chave; 
  if(erro==true){ 
    document.form1.k86_extrato.focus(); 
    document.form1.k86_extrato.value = ''; 
  }
}
function js_mostraextrato1(chave1,chave2){
  document.form1.k86_extrato.value = chave1;
  document.form1.k85_nomearq.value = chave2;
  db_iframe_extrato.hide();
}
function js_pesquisak86_contabancaria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_extratolinha','db_iframe_contabancaria','func_contabancaria.php?funcao_js=parent.js_mostracontabancaria1|db83_sequencial|db83_descricao|db83_tipoconta','Pesquisa',true,'0');
  }else{
     if(document.form1.k86_contabancaria.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_extratolinha','db_iframe_contabancaria','func_contabancaria.php?tp=1&pesquisa_chave='+document.form1.k86_contabancaria.value+'&funcao_js=parent.js_mostracontabancaria','Pesquisa',false);     
     }else{
       document.form1.db83_descricao.value = ''; 
     }
  }
}
function js_mostracontabancaria(erro,chave1, chave2, chave3, chave4, chave5, chave6){
  document.form1.db83_descricao.value = chave1; 
  if(erro==true){
    document.form1.k86_contabancaria.focus(); 
    document.form1.k86_contabancaria.value = ''; 
  } 
}
function js_mostracontabancaria1(chave1,chave2,chave3){
  document.form1.k86_contabancaria.value = chave1;
  document.form1.db83_descricao.value = chave2;
  db_iframe_contabancaria.hide();
}
</script>