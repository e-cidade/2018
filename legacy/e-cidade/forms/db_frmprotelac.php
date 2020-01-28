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
$clrotulo = new rotulocampo;
$clrotulo->label("h12_assent");
$clrotulo->label("h12_descr");
$clprotelac->rotulo->label();
$arr_operador = Array("+"=>"+", "-"=>"-", "*"=>"*", "/"=>"/");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <?
  db_input('h19_codigo',6,$Ih19_codigo,true,'hidden',3,"")
  ?>
  <tr>
    <td nowrap title="<?=@$Th19_assent?>">
      <?
      db_ancora(@$Lh19_assent,"js_pesquisah19_assent(true);",$db_opcao);
      ?>
    </td>
    <td> 
      <?
      db_input('h19_assent',6,$Ih19_assent,true,'hidden',3,"")
      ?>
      <?
      db_input('h12_assent',6,$Ih12_assent,true,'text',$db_opcao," onchange='js_pesquisah19_assent(false);'")
      ?>
      <?
      db_input('h12_descr',30,$Ih12_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Th19_tipo?>" align="right">
      <?=@$Lh19_tipo?>
    </td>
    <td> 
      <?
      $x = Array("G"=>"Gratificação", "A"=>"Avanço", "F"=>"Férias");
      db_select('h19_tipo',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <table>
        <tr>
          <td>
            <fieldset>
              <legend>
                <b>Até</b>
              </legend>
              <table>
                <?
                for($i=1; $i<11; $i++){
                  $Tcampodia = "Th19_dia".($i < 10 ? "0" . $i : $i);
                  $Lcampodia = "Lh19_dia".($i < 10 ? "0" . $i : $i);
                  $Icampodia = "Ih19_dia".($i < 10 ? "0" . $i : $i);
                  $Ncampodia = "h19_dia".($i < 10 ? "0" . $i : $i);

                  $Icampoper = "Ih19_per".($i < 10 ? "0" . $i : $i);
                  $Ncampoper = "h19_per".($i < 10 ? "0" . $i : $i);

                  $Ncampoop  = "h19_op".($i < 10 ? "0" . $i : $i);
                ?>
                <tr>
                  <td nowrap title="<?=@$$Tcampodia?>">
                    <?=@$$Lcampodia?>
                  </td>
                  <td> 
                    <?
                    db_input($Ncampodia,3,$$Icampodia,true,'text',$db_opcao,"")
                    ?>
                  </td>
                  <td> 
                    <?
                    db_select($Ncampoop,$arr_operador,true,$db_opcao,"");
                    ?>
                  </td>
                  <td> 
                    <?
                    db_input($Ncampoper,5,$$Icampoper,true,'text',$db_opcao,"")
                    ?>
                  </td>
                </tr>
                <?
                }
                ?>
              </table>
            </fieldset>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <!--
  <tr>
    <td nowrap title="<?=@$Th19_tpcalc?>">
       <?=@$Lh19_tpcalc?>
    </td>
    <td> 
    -->
<?
db_input('h19_tpcalc',1,$Ih19_tpcalc,true,'hidden',$db_opcao,"")
?>
  <!--
    </td>
  </tr>
  -->
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_verificacampos();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_verificacampos(){
  for(var i=1; i<11; i++){
    camposdia = eval("document.form1.h19_dia" + (i < 10 ? ("0" + i) : i) + ".value");
    camposper = eval("document.form1.h19_per" + (i < 10 ? ("0" + i) : i) + ".value");
    if(camposdia != "" && camposper == ""){
      alert("Informe o percentual do dia.");
      eval("document.form1.h19_dia" + (i < 10 ? ("0" + i) : i) + ".focus()");
      eval("document.form1.h19_dia" + (i < 10 ? ("0" + i) : i) + ".select()");
      return false;
    }
  }
  return true;
}
function js_pesquisah19_assent(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_tipoasse','func_tipoasse.php?chave_codigo=true&funcao_js=parent.js_mostratipoasse1|h12_codigo|h12_assent|h12_descr','Pesquisa',true);
  }else{
    if(document.form1.h12_assent.value != ''){ 
       js_OpenJanelaIframe('top.corpo','db_iframe_tipoasse','func_tipoasse.php?chave_assent='+document.form1.h12_assent.value+'&funcao_js=parent.js_mostratipoasse','Pesquisa',false);
    }else{
      document.form1.h12_descr.value = ''; 
      document.form1.h19_assent.value = '';
    }
  }
}
function js_mostratipoasse(chave,chave2,erro){
  document.form1.h12_descr.value = chave2; 
  if(erro==true){ 
    document.form1.h12_assent.focus(); 
    document.form1.h12_assent.value = ''; 
    document.form1.h19_assent.value = '';
  }else{
    document.form1.h19_assent.value = chave;
  }
}
function js_mostratipoasse1(chave1,chave2,chave3){
  document.form1.h19_assent.value = chave1;
  document.form1.h12_assent.value = chave2;
  document.form1.h12_descr.value = chave3;
  db_iframe_tipoasse.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_protelac','func_protelac.php?funcao_js=parent.js_preenchepesquisa|h19_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_protelac.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>