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

//MODULO: recursos humanos
$clleis->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0" width="35%">
  <tr>
    <td nowrap title="<?=@$Th08_numero?>">
       <?=@$Lh08_numero?>
    </td>
    <td> 
<?
db_input('h08_numero',6,$Ih08_numero,true,'text',$db_opcao,"");
db_input('h08_codlei',6,$Ih08_codlei,true,'hidden',3,"");
db_input('anos_perc_inf',6,0,true,'hidden',3,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th08_tipo?>">
       <?=@$Lh08_tipo?>
    </td>
    <td>
<?
$x = Array("A"=>"Avanço", "G"=>"Gratificação", "C"=>"Cargos", "O"=>"Outros");
db_select('h08_tipo',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th08_dtlanc?>">
       <?=@$Lh08_dtlanc?>
    </td>
    <td nowrap> 
<?
if((!isset($h08_dtlanc) || (isset($h08_dtlanc) && trim($h08_dtlanc) == "")) && (!isset($h08_dtlanc_dia) || (isset($h08_dtlanc_dia) && trim($h08_dtlanc_dia) == ""))){
  $h08_dtlanc_dia = date("d",db_getsession("DB_datausu"));
  $h08_dtlanc_mes = date("m",db_getsession("DB_datausu"));
  $h08_dtlanc_ano = date("Y",db_getsession("DB_datausu"));
}
db_inputdata('h08_dtlanc',@$h08_dtlanc_dia,@$h08_dtlanc_mes,@$h08_dtlanc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th08_dtini?>">
       <?=@$Lh08_dtini?>
    </td>
    <td nowrap> 
<?
db_inputdata('h08_dtini',@$h08_dtini_dia,@$h08_dtini_mes,@$h08_dtini_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th08_dtfim?>">
       <?=@$Lh08_dtfim?>
    </td>
    <td nowrap> 
<?
db_inputdata('h08_dtfim',@$h08_dtfim_dia,@$h08_dtfim_mes,@$h08_dtfim_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td colspan="2" width="100%">
      <iframe name="anos_perc_cargo" id="anos_perc_cargo" marginwidth="0" marginheight="0" frameborder="0" src="rec1_leis_iframe001.php?db_opcao=<?=$db_opcao?>&h08_codlei=<?=((isset($h08_codlei) && trim($h08_codlei) != "") ? $h08_codlei : "")?><?=(isset($anos_perc_inf) && trim($anos_perc_inf) != "") ? "&valores=".$anos_perc_inf : ""?>" width="100%" height="250"></iframe>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="<?=($db_opcao != 3 ? "return js_validarDados();" : "return true")?>">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_validarDados(){
  datai = new Date(document.form1.h08_dtini_ano.value, document.form1.h08_dtini_mes.value, document.form1.h08_dtini_dia.value);
  dataf = new Date(document.form1.h08_dtfim_ano.value, document.form1.h08_dtfim_mes.value, document.form1.h08_dtfim_dia.value);
  if(document.form1.h08_numero.value == ""){
    alert("Informe o número da lei.");
    document.form1.h08_numero.focus();
    return false;
  }else if(document.form1.h08_dtlanc_dia.value == "" || document.form1.h08_dtlanc_mes.value == "" || document.form1.h08_dtlanc_ano.value == ""){
    alert("Informe a data de lançamento");
//    document.form1.h08_dtlanc_dia.select();
//    document.form1.h08_dtlanc_dia.focus();
    document.form1.h08_dtlanc.select();
    document.form1.h08_dtlanc.focus();
    return false;
  }else if(document.form1.h08_dtini_ano.value == "" || document.form1.h08_dtini_mes.value == "" || document.form1.h08_dtini_dia.value == ""){
    alert("Informe a data inicial.");
//    document.form1.h08_dtini_dia.select();
//    document.form1.h08_dtini_dia.focus();
    document.form1.h08_dtini.select();
    document.form1.h08_dtini.focus();
    return false;
  }else if(document.form1.h08_dtfim_ano.value != "" && document.form1.h08_dtfim_mes.value != "" && document.form1.h08_dtfim_dia.value != ""){
    if(datai > dataf){
      alert("A data final deve ser superior à inicial.");
      document.form1.h08_dtfim_ano.value = "";
      document.form1.h08_dtfim_mes.value = "";
      document.form1.h08_dtfim_dia.value = "";
      document.form1.h08_dtfim.value = "";
//      document.form1.h08_dtfim_dia.focus();
      document.form1.h08_dtfim.focus();
      return false;
    }
  }

  var iframe = anos_perc_cargo.document.form1;
  campotesta = -1;
  for(var i = 0; i < iframe.length; i++){
    campo = iframe.elements[i].name.substr(0,8);

    if(campo == "h08_anos"){
      valor = new Number(iframe.elements[i].value);
      if(iframe.elements[i].value != "" && valor > 0){
        natu = new Number(iframe.elements[i].name.substr(8,2));
        nant = natu - 1;
        if(campo == "h08_anos" && valor <= campotesta){
	  if(campotesta < 999999){
            alert("Ano " + natu + " (" + valor + ") deve ser superior ao ano " + nant + " (" + campotesta + ").");
          }else{
            alert("Ano anterior em branco ao ano " + natu + " (" + valor + ").");
          }
          iframe.elements[i].select();
          iframe.elements[i].focus();
          return false;
        }
      }else{
        valor = 999999;
      }
      campotesta = valor;
    }
  }

  stringrepassa = "";
  separadorvals = "";
  for(var i = 0; i < iframe.length; i++){
    campo = iframe.elements[i].name.substr(0,8);
    valor = iframe.elements[i].value;

    if(campo == "h08_anos"){
      valor = new Number(valor);
      verifica = false;
      if(valor > 0){
        verifica = true;
	separadorvals = (stringrepassa == "" ? "" : "|");
      }
    }else{
      separadorvals = "-";
    }

    if(verifica == true){
      if(campo == "h08_perc" && valor == ""){
	alert("Ano informado sem seu percentual, verifique.");
	iframe.elements[i].focus();
	return false;
      }
      stringrepassa += separadorvals + valor;
    }
  }

  if(stringrepassa == ""){
    alert("Informe pelo menos um ano e seu percentual.");
    return false;
  }

  document.form1.anos_perc_inf.value = stringrepassa;
  return true;

}
function js_atualixaIframe(stringatu){
  var    iframe = anos_perc_cargo.document.form1;
  arr_stringatu = stringatu.split("|");
  for(var i = 0; i < arr_stringatu.length; i++){
    arr_valores = arr_stringatu[i].split("-");
    eval("iframe.h08_anos" + (i + 1) + ".value = '" + arr_valores[0] + "'");
    eval("iframe.h08_perc" + (i + 1) + ".value = '" + arr_valores[1] + "'");
    eval("iframe.h08_car"  + (i + 1) + ".value = '" + arr_valores[2] + "'");
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_leis','func_leis.php?funcao_js=parent.js_preenchepesquisa|h08_codlei','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_leis.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>