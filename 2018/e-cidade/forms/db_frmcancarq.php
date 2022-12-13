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

$clrotulo = new rotulocampo;
$clempagegera->rotulo->label();
$clempagetipo->rotulo->label();
?>
<form name="form1" method="post">

<fieldset style="margin-top:30px; width: 500px;">
<legend><strong>Cancelamento de Arquivo</strong></legend>

<table border='0'>

<tr> 
    <td  align="left" nowrap title="<?=$Te87_codgera?>"> <? db_ancora(@$Le87_codgera,"js_pesquisa_gera(true);",$db_opcao);?> </td>
    <td  align="left" nowrap>
    <?
    db_input("e87_codgera",8,$Ie87_codgera,true,"text",$db_opcao,"onchange='js_pesquisa_gera(false);'"); 
    db_input("e87_descgera",40,$Ie87_descgera,true,"text",3);
    db_input("movimentos",40,0,true,"hidden",3);
    ?>
    </td>
  </tr>
  
  <?
  $db_opcao = 1;
  if(isset($e87_codgera)){
        $db_opcao = 3;
  	echo "<tr>
            <td colspan='2' align='center'>
              <iframe name='ordem' src='emp4_empagecancarq001_ordem.php?lCancelado=0&codarq=$e87_codgera' width='760' height='320' marginwidth='0' marginheight='0' frameborder='0'>
              </iframe>
            </td>
          </tr>";         
  }
  ?>
</table>
</fieldset>

<div style="margin-top: 10px;">
  <input name="<?=($db_opcao=='1')?'mostra':'cancela'?>" type="<?=($db_opcao=='1')?'submit':'button'?>" value="<?=($db_opcao=="1")?"Mostrar arquivo":"Cancelar selecionados"?>" onclick="<?=($db_opcao=="1")?'':'js_cancelar();'?>">
  <?
  if($db_opcao == 3){
    echo "<input type='button' name='voltar' value='Voltar' onclick='location.href=\"emp4_empagecancarq001.php\"'>";
  }
  ?>
</div>

</form>





<script>
function js_cancelar(){
  x = ordem.document.form1;
  valorescancela = "";
  virgulavalores = "";
  for(i=0;i<x.length;i++){
    if(x.elements[i].type=='checkbox'){
      if(x.elements[i].checked==true){
        valorescancela += virgulavalores+x.elements[i].value;
        virgulavalores = ",";
      }      
    }
  }
  if(valorescancela == ""){
    alert("Selecione os movimentos a cancelar");
  }else{
    document.form1.movimentos.value = valorescancela;
    document.form1.submit();
  }
}
function js_pesquisa_gera(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?lCancelado=0&funcao_js=parent.js_mostragera1|e87_codgera|e87_descgera','Pesquisa',true);
  }else{
    if(document.form1.e87_codgera.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_empagegera','func_empagegera.php?lCancelado=0&pesquisa_chave='+document.form1.e87_codgera.value+'&funcao_js=parent.js_mostragera','Pesquisa',false);
    }else{
      document.form1.e87_descgera.value = ''; 
    }
  }
}
function js_mostragera(chave,erro){
  document.form1.e87_descgera.value = chave; 
  if(erro==true){ 
    document.form1.e87_codgera.focus(); 
    document.form1.e87_codgera.value = ''; 
  }
}
function js_mostragera1(chave1,chave2){
  document.form1.e87_codgera.value = chave1;
  document.form1.e87_descgera.value = chave2;
  db_iframe_empagegera.hide();
}
</script>