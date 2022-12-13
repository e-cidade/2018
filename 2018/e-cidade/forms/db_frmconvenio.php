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

//MODULO: pessoal
$clconvenio->rotulo->label();
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tr56_codrel?>">
       <?=@$Lr56_codrel?>
    </td>
    <td> 
<?
db_input('r56_codrel',4,$Ir56_codrel,true,'text',($db_opcao==1?"1":"3"),"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr56_descr?>">
       <?=@$Lr56_descr?>
    </td>
    <td colspan="3"> 
<?
db_input('r56_descr',40,$Ir56_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr56_local?>">
       <?=@$Lr56_local?>
    </td>
    <td colspan="3"> 
<?
db_input('r56_local',40,$Ir56_local,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr56_dirarq?>">
       <?=@$Lr56_dirarq?>
    </td>
    <td colspan="3"> 
<?
db_input('r56_dirarq',40,$Ir56_dirarq,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr56_linhasheader?>">
       <?=@$Lr56_linhasheader?>
    </td>
    <td> 
<?
db_input('r56_linhasheader',2,$Ir56_linhasheader,true,'text',$db_opcao,"")
?>
    </td>
    <td nowrap title="<?=@$Tr56_linhastrailler?>" align="right">
       <?=@$Lr56_linhastrailler?>
    </td>
    <td> 
<?
db_input('r56_linhastrailler',2,$Ir56_linhastrailler,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr56_posano?>">
       <?=@$Lr56_posano?>
    </td>
    <td colspan="3"> 
<?
db_input('r56_posano1',4,$Ir56_posano,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição de leitura do ano\",\"f\",\"t\",event);'");
db_input('r56_posano2',4,$Ir56_posano,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição de leitura do ano\",\"f\",\"t\",event);'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr56_posmes?>">
       <?=@$Lr56_posmes?>
    </td>
    <td colspan="3"> 
<?
db_input('r56_posmes1',4,$Ir56_posmes,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição de leitura do mês\",\"f\",\"t\",event);'");
db_input('r56_posmes2',4,$Ir56_posmes,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição de leitura do mês\",\"f\",\"t\",event);'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr56_posreg?>">
       <?=@$Lr56_posreg?>
    </td>
    <td colspan="3"> 
<?
db_input('r56_posreg1',4,$Ir56_posreg,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição de leitura do funcionário\",\"f\",\"t\",event);'");
db_input('r56_posreg2',4,$Ir56_posreg,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição de leitura do funcionário\",\"f\",\"t\",event);'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr56_poseve?>">
       <?=@$Lr56_poseve?>
    </td>
    <td colspan="3"> 
<?
db_input('r56_poseve1',4,$Ir56_poseve,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição do código do relacionamento\",\"f\",\"t\",event);'");
db_input('r56_poseve2',4,$Ir56_poseve,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição de código do relacionamento\",\"f\",\"t\",event);'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap colspan="4" align="center">
      <fieldset>
        <legend><strong>POSIÇÃO VALOR / QUANTIDADE</strong></legend>
        <table>
          <tr>
            <td nowrap title="<?=@$Tr56_posq01?>">
               <strong>01:</strong>
            </td>
            <td> 
        <?
        db_input('r56_posq011',4,$Ir56_posq01,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição da rubrica 1\",\"f\",\"t\",event);'");
        db_input('r56_posq012',4,$Ir56_posq01,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição da rubrica 1\",\"f\",\"t\",event);'");
        ?>
            </td>
            <td> 
        <?
        $arr_quantval = array("f"=>"Valor","t"=>"Quantidade");
        db_select('r56_vq01',$arr_quantval,true,$db_opcao,"");
        ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr56_posq02?>">
               <strong>02:</strong>
            </td>
            <td> 
        <?
        db_input('r56_posq021',4,$Ir56_posq02,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição da rubrica 2\",\"f\",\"t\",event);'");
        db_input('r56_posq022',4,$Ir56_posq02,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição da rubrica 2\",\"f\",\"t\",event);'");
        ?>
            </td>
            <td> 
        <?
        db_select('r56_vq02',$arr_quantval,true,$db_opcao,"");
        ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tr56_posq03?>">
               <strong>03:</strong>
            </td>
            <td> 
        <?
        db_input('r56_posq031',4,$Ir56_posq03,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição da rubrica 3\",\"f\",\"t\",event);'");
        db_input('r56_posq032',4,$Ir56_posq03,true,'text',$db_opcao,"onChange='js_preenche_zeros(this.name);' onKeyUp='js_controla_campo(this.name);js_ValidaCampos(this,1,\"Posição da rubrica 3\",\"f\",\"t\",event);'");
        ?>
            </td>
            <td> 
        <?
        db_select('r56_vq03',$arr_quantval,true,$db_opcao,"");
        ?>
            </td>
          </tr>
        </table>
      </fieldset>  
    </td>
  </tr>  
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick="return js_verifica_posicoes();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_verifica_posicoes(){
  x = document.form1;
  if(x.r56_codrel.value == ""){
    alert("Informe o código do convênio.");
    x.r56_codrel.select();
    x.r56_codrel.focus();
    return false; 
  }else if(x.r56_descr.value == ""){
    alert("Informe a descrição do convênio.");
    x.r56_descr.select();
    x.r56_descr.focus();
    return false;
  }else if(x.r56_local.value == ""){
    alert("Informe o local de leitura do convênio.");
    x.r56_local.select();
    x.r56_local.focus();
    return false;
  }else if(x.r56_dirarq.value == ""){
    alert("Informe o caminho do convênio.");
    x.r56_dirarq.select();
    x.r56_dirarq.focus();
    return false;
  }else if((x.r56_posano1.value != "" && x.r56_posano2.value == "") || (x.r56_posano1.value == "" && x.r56_posano2.value != "") || x.r56_posano1.value == "000" || x.r56_posano2.value == "000"){
    alert("Posição do ano incorreta.");
    x.r56_posano1.select();
    x.r56_posano1.focus();
    return false;
  }else if((x.r56_posmes1.value != "" && x.r56_posmes2.value == "") || (x.r56_posmes1.value == "" && x.r56_posmes2.value != "") || x.r56_posmes1.value == "000" || x.r56_posmes2.value == "000"){
    alert("Posição do mes incorreta.");
    x.r56_posmes1.select();
    x.r56_posmes1.focus();
    return false;
  }else if((x.r56_posreg1.value != "" && x.r56_posreg2.value == "") || (x.r56_posreg1.value == "" && x.r56_posreg2.value != "") || x.r56_posreg1.value == "000" || x.r56_posreg2.value == "000"){
    alert("Posição do funcionário incorreta.");
    x.r56_posreg1.select();
    x.r56_posreg1.focus();
    return false;
  }else if((x.r56_poseve1.value != "" && x.r56_poseve2.value == "") || (x.r56_poseve1.value == "" && x.r56_poseve2.value != "") || x.r56_poseve1.value == "000" || x.r56_poseve2.value == "000"){
    alert("Posição do relacionamento incorreta.");
    x.r56_poseve1.select();
    x.r56_poseve1.focus();
    return false;
  }else if((x.r56_posq011.value != "" && x.r56_posq012.value == "") || (x.r56_posq011.value == "" && x.r56_posq012.value != "") || x.r56_posq011.value == "000" || x.r56_posq012.value == "000"){
    alert("Posição do valor / quantidade (01) incorreto.");
    x.r56_posq011.select();
    x.r56_posq011.focus();
    return false;
  }else if((x.r56_posq021.value != "" && x.r56_posq022.value == "") || (x.r56_posq021.value == "" && x.r56_posq022.value != "") || x.r56_posq021.value == "000" || x.r56_posq022.value == "000"){
    alert("Posição do valor / quantidade (02) incorreto.");
    x.r56_posq021.select();
    x.r56_posq021.focus();
    return false;
  }else if((x.r56_posq031.value != "" && x.r56_posq032.value == "") || (x.r56_posq031.value == "" && x.r56_posq032.value != "") || x.r56_posq031.value == "000" || x.r56_posq032.value == "000"){
    alert("Posição do valor / quantidade (03) incorreto.");
    x.r56_posq031.select();
    x.r56_posq031.focus();
    return false;
  }
  return true;
}
function js_preenche_zeros(campo){
  x = document.form1;
  eval("caracteres = document.form1."+campo+".value.length;");
  eval("valorcampo = document.form1."+campo+".value;");
  if(caracteres > 0){
    if(caracteres < 3){
      for(i=caracteres; i < 3; i++){
        valorcampo = "0"+valorcampo;
      }
      eval("document.form1."+campo+".value = '"+valorcampo+"';");
     }
  }
}
function js_controla_campo(campo){
  eval("caracteres = document.form1."+campo+".value.length;");
  if(caracteres > 3){
    eval("document.form1."+campo+".value = document.form1."+campo+".value.substr(0,3);");
  }
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_convenio','func_convenio.php?funcao_js=parent.js_preenchepesquisa|r56_codrel','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_convenio.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>