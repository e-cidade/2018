<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: pessoal
$clrotulo = new rotulocampo;
$clpadroes->rotulo->label();
$clrotulo->label("r07_descr");
$r02_anousu = db_anofolha();
$r02_mesusu = db_mesfolha();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="center">
      <fieldset>
        <table>
          <tr>
            <td nowrap align="right" title="<?=@$Tr02_regime?>">
              <?=@$Lr02_regime?>
            </td>
            <td> 
              <?
              $result_regime = $clrhcadregime->sql_record($clrhcadregime->sql_query_file(null,"*"));
              db_selectrecord("r02_regime",$result_regime,true,($db_opcao == 1?1:3));
              db_input('r02_anousu',4,$Ir02_anousu,true,'hidden',3,"");
              db_input('r02_mesusu',2,$Ir02_mesusu,true,'hidden',3,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="<?=@$Tr02_codigo?>">
              <?=@$Lr02_codigo?>
            </td>
            <td> 
              <?
              db_input('r02_codigo',10,$Ir02_codigo,true,'text',($db_opcao == 1?1:3),"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="<?=@$Tr02_descr?>">
              <?=@$Lr02_descr?>
            </td>
            <td> 
              <?
              db_input('r02_descr',30,$Ir02_descr,true,'text',$db_opcao,"", "", "", "", 25)
              ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  <tr>
    <td align="center">
      <fieldset>
        <table>
          <tr>
            <td nowrap align="right" title="<?=@$Tr02_tipo?>">
              <?=@$Lr02_tipo?>
            </td>
            <td> 
              <?
	      if(!isset($r02_tipo)){
		$r02_tipo = "M";
              }
              $arr_tipo = array("H"=>"Horas","M"=>"Mês");
              db_select('r02_tipo',$arr_tipo,true,$db_opcao,"onchange='js_trancaform(this.value,true);'");
              ?>
            </td>
            <td nowrap align="right" title="<?=@$Tr02_hrssem?>">
              &nbsp;&nbsp;&nbsp;<?=@$Lr02_hrssem?>
            </td>
            <td> 
              <?
              db_input('r02_hrssem',4,$Ir02_hrssem,true,'text',$db_opcao,"")
              ?>
            </td>
            <td nowrap align="right" title="<?=@$Tr02_hrsmen?>">
              &nbsp;&nbsp;&nbsp;<?=@$Lr02_hrsmen?>
            </td>
            <td> 
              <?
              db_input('r02_hrsmen',4,$Ir02_hrsmen,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  <tr>
    <td align="center">
      <fieldset>
        <table>
          <tr>
            <td nowrap align="right" title="<?=@$Tr02_form?>">
              <?=@$Lr02_form?>
            </td>
            <td> 
              <?
              db_input('r02_form',47,$Ir02_form,true,'text',$db_opcao,"onclick='js_calculavalorform(false);'");
              db_input('r02_form',47,$Ir02_form,true,'hidden',3,"","formulaanterior");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="<?=@$Tr02_valor?>">
              <?=@$Lr02_valor?>
            </td>
            <td> 
              <?
              @$r02_valor = str_replace(',','.',str_replace('.','',trim(db_formatar(@$r02_valor,'f'))));
              db_input('r02_valor',15,$Ir02_valor,true,'text',$db_opcao,"")
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="<?=@$Tr02_minimo?>">
              <?db_ancora($Lr02_minimo,"js_pesquisar02_minimo(true)",$db_opcao);?>
            </td>
            <td> 
              <?
              db_input('r02_minimo',4,$Ir02_minimo,true,'text',$db_opcao,"onchange='js_pesquisar02_minimo(false);'");
              db_input('r07_descr',40,$Ir07_descr,true,'text',3,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" onclick="return js_validarFormulario();" <?=($db_botao==false?"disabled":"")?> >
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
    </td>
  </tr>
  <tr>
    <td id="mensagem" align="center">
    </td>
  </tr>
</table>
</center>
</form>
<script>  
function js_trancaform(valor,change){
  <?if($db_opcao == 1 || $db_opcao == 2){?>
  if(valor == "H"){
    document.form1.formulaanterior.value          = document.form1.r02_form.value;
    document.form1.r02_form.value                 = "";
    document.form1.r02_form.readOnly              = true;
    document.form1.r02_form.style.backgroundColor = "#DEB887";
  }else if(change == true){
    document.form1.r02_form.value                 = document.form1.formulaanterior.value;
    document.form1.formulaanterior.value          = "";
    document.form1.r02_form.readOnly              = false;
    document.form1.r02_form.style.backgroundColor = "";
  }
  js_tabulacaoforms("form1","r02_regime",false,1,"r02_regime",false);
  <?}?>
}
function js_calculavalorform(submita){
  <?
  echo "opcao = ".$db_opcao.";\n"; 
  ?>
  if(document.form1.r02_form.value != "" && opcao != 3){
    document.getElementById("db_opcao").disabled = true;
    document.getElementById("mensagem").innerHTML = "<font color='red'><b>Calculando valor da fórmula</b></font>";
    erro = 0;
    arr_div = document.form1.r02_form.value.split("D");
    qry_diversos = "";
    virgula = "";
//    var expr = new RegExp("[D0-9\.-+\*/()]+");
//    if(!document.form1.r02_form.value.match(expr)){
//      erro ++;
//      mensagem = "Expressão informada não válida, verifique.";
//    }else 
    if(arr_div.length > 1){
      for(var i=1; i<arr_div.length; i++){
        diverso = "D"+arr_div[i].substr(0,3);
        test = new Number(arr_div[i].substr(0,3));
        if(isNaN(test) || test == 0){
          mensagem = "Diverso ("+diverso+") informado não é válido. ";
          erro ++;
          break;
        }
	qry_diversos+= virgula+diverso;
	virgula = ","
      }
    }else{
      valor = eval(document.form1.r02_form.value);
      if(isNaN(valor)){
	erro ++;
	mensagem = "Expressão informada não válida, verifique.";
      }else{
	document.form1.r02_valor.value = valor.toFixed(2);
      }
    }
    if(erro == 0){
      if(arr_div.length > 1){
        qry = 'opcao=dadosdiversos';
	qry+= '&div='+qry_diversos;
        js_OpenJanelaIframe('top.corpo','db_iframe_faltas','func_scriptsdb.php?'+qry,'Pesquisa',false,10,10,10,10);
      }
    }else{
      alert(mensagem);
      document.form1.r02_form.select();
      document.form1.r02_form.focus();
    }
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_padroes','func_padroes.php?funcao_js=parent.js_preenchepesquisa|r02_anousu|r02_mesusu|r02_regime|r02_codigo','Pesquisa',true);
}
function js_pesquisar02_minimo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pesdiver','func_pesdiver.php?funcao_js=parent.js_mostraminimo1|r07_codigo|r07_descr&instit=<?=(db_getsession("DB_instit"))?>&chave_r07_mesusu=<?=$r02_anousu?>&chave_r07_anousu=<?=$r02_mesusu?>','Pesquisa',true);
  }else{
     if(document.form1.r02_minimo.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pesdiver','func_pesdiver.php?pesquisa_chave='+document.form1.r02_minimo.value+'&funcao_js=parent.js_mostraminimo&instit=<?=(db_getsession("DB_instit"))?>&chave_r07_mesusu=<?=$r02_anousu?>&chave_r07_anousu=<?=$r02_mesusu?>','Pesquisa',false);
     }else{
       document.form1.r07_descr.value = ''; 
     }
  }
}
function js_mostraminimo(chave,erro){
  document.form1.r07_descr.value = chave; 
  if(erro==true){ 
    document.form1.r02_minimo.focus(); 
    document.form1.r02_minimo.value = ''; 
  }
}
function js_mostraminimo1(chave1,chave2){
  document.form1.r02_minimo.value = chave1;
  document.form1.r07_descr.value = chave2;
  db_iframe_pesdiver.hide();
}
function js_preenchepesquisa(chave,chave1,chave2,chave3){
  db_iframe_padroes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2+'&chavepesquisa3='+chave3";
  }
  ?>
}

/**
 * Válida o formulário antes de submeter
 * 
 * @returns {Boolean}
 */
function js_validarFormulario() {
  
  var sValorPadrao = document.getElementById("r02_valor").value;
  var fValorPadrao = parseFloat(sValorPadrao);
  if (fValorPadrao < 0) {
    
    alert("O valor do padrão é negativo.");
    return false;
  }
  
  return true;
}

js_trancaform("<?=$r02_tipo?>",false);
</script>