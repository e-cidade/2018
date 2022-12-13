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

//MODULO: recursoshumanos
$clrhestagioresultado->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h57_sequencial");
$clrotulo->label("h31_sequencial");
$clrotulo->label("h31_numero");
$clrotulo->label("z01_nome");
  ?>
<form name="form1" method="post" action="">
<center>
<table>
<tr>
 <td>
  <fieldset><legend><b>Dados do Resultado Final</b>
<table border="0">
  <tr >
    <td nowrap title="<?=@$Th65_sequencial?>">
       <?=@$Lh65_sequencial?>
    </td>
    <td> 
<?
db_input('h65_sequencial',10,$Ih65_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th65_rhestagioagenda?>">
       <?
       db_ancora(@$Lh65_rhestagioagenda,"js_pesquisah65_rhestagioagenda(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('h65_rhestagioagenda',10,$Ih65_rhestagioagenda,true,'text',3," onchange='js_pesquisah65_rhestagioagenda(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th65_data?>">
       <?=@$Lh65_data?>
    </td>
    <td> 
<?
db_inputdata('h65_data',@$h65_data_dia,@$h65_data_mes,@$h65_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="">
      <b>Número da Portaria</b>
    </td>
    <td> 
      <?
      db_input('h31_numero',10,$Ih31_numero,true,'text',$db_opcao)
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th65_resultado?>">
       <?=@$Lh65_resultado?>
    </td>
    <td> 
<?
$x = array('A'=>'Aprovado','R'=>'Reprovado');
db_select('h65_resultado',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th65_pontos?>">
       <?=@$Lh65_pontos?>
    </td>
    <td> 
<?
db_input('h65_pontos',10,$Ih65_pontos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th65_observacao?>">
       <?=@$Lh65_observacao?>
    </td>
    <td> 
<?
db_textarea('h65_observacao',8,60,$Ih65_observacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </td>
  </tr>
  </table>
  </fieldset>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
<?
if (isset($h65_sequencial) && $h65_sequencial != null){

  echo "<input name='relatorio' type='button' id='pesquisar' value='Emitir Ata de Resultado' onclick='js_relatorio({$h65_rhestagioagenda});' >";

}

?>
</form>
<script>
function js_pesquisah65_rhestagioagenda(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhestagioagenda','func_rhestagioagenda.php?funcao_js=parent.js_mostrarhestagioagenda1|h57_sequencial|h57_sequencial','Pesquisa',true);
  }else{
     if(document.form1.h65_rhestagioagenda.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_rhestagioagenda','func_rhestagioagenda.php?pesquisa_chave='+document.form1.h65_rhestagioagenda.value+'&funcao_js=parent.js_mostrarhestagioagenda','Pesquisa',false);
     }else{
       document.form1.h57_sequencial.value = ''; 
     }
  }
}
function js_mostrarhestagioagenda(chave,erro){
  document.form1.h57_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.h65_rhestagioagenda.focus(); 
    document.form1.h65_rhestagioagenda.value = ''; 
  }
}
function js_mostrarhestagioagenda1(chave1,chave2){
  document.form1.h65_rhestagioagenda.value = chave1;
  document.form1.h57_sequencial.value = chave2;
  db_iframe_rhestagioagenda.hide();
}
function js_pesquisah65_rhportaria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_portaria','func_portaria.php?funcao_js=parent.js_mostraportaria1|h31_sequencial|h31_sequencial','Pesquisa',true);
  }else{
     if(document.form1.h65_rhportaria.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_portaria','func_portaria.php?pesquisa_chave='+document.form1.h65_rhportaria.value+'&funcao_js=parent.js_mostraportaria','Pesquisa',false);
     }else{
       document.form1.h31_sequencial.value = ''; 
     }
  }
}
function js_mostraportaria(chave,erro){
  document.form1.h31_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.h65_rhportaria.focus(); 
    document.form1.h65_rhportaria.value = ''; 
  }
}
function js_mostraportaria1(chave1,chave2){
  document.form1.h65_rhportaria.value = chave1;
  document.form1.h31_sequencial.value = chave2;
  db_iframe_portaria.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhestagioresultado','func_rhestagioresultado.php?funcao_js=parent.js_preenchepesquisa|h57_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_rhestagioresultado.hide();
  <?
//  if (!isset($oGet->chavepesquisa)){
    $name = basename($PHP_SELF);
    echo "location.href='{$name}?chavepesquisa='+chave";
 // }
  ?>
}
function js_relatorio(iCodExame){
  window.open('rec3_estagioAta002.php?iCodExame='+iCodExame,'','location=0');
}
<?
 if (!isset($oGet->chavepesquisa)){
   echo "js_pesquisa();\n";
 }

?>
</script>