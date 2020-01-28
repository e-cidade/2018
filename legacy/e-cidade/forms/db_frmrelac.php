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
$clrelac->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh27_rubric");
$clrotulo->label("rh27_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tr55_codeve?>">
       <?=@$Lr55_codeve?>
    </td>
    <td> 
<?
db_input('r55_codeve',4,$Ir55_codeve,true,'text',($db_opcao==1?"1":"3"),"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr55_descr?>">
       <?=@$Lr55_descr?>
    </td>
    <td> 
<?
db_input('r55_descr',47,$Ir55_descr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr55_rubr01?>">
       <?
       db_ancora(@ $Lr55_rubr01, "js_pesquisar55_rubric(true,'01');", $db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('r55_rubr01',4,$Ir55_rubr01,true,'text',$db_opcao,"onchange='js_pesquisar55_rubric(false,\"01\")'");
db_input('rh27_descr01',40,$Irh27_descr,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr55_rubr02?>">
       <?
       db_ancora(@ $Lr55_rubr02, "js_pesquisar55_rubric(true,'02');", $db_opcao );
       ?>
    </td>
    <td> 
<?
db_input('r55_rubr02',4,$Ir55_rubr02,true,'text',$db_opcao,"onchange='js_pesquisar55_rubric(false,\"02\")'");
db_input('rh27_descr02',40,$Irh27_descr,true,'text',3);
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr55_rubr03?>">
       <?
       db_ancora(@ $Lr55_rubr03, "js_pesquisar55_rubric(true,'03');", $db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('r55_rubr03',4,$Ir55_rubr03,true,'text',$db_opcao,"onchange='js_pesquisar55_rubric(false,\"03\")'");
db_input('rh27_descr03',40,$Irh27_descr,true,'text',3);
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisar55_rubric(mostra,campo){
  if(mostra==true){
     if(campo == '01'){
        js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
     }else if(campo == '02'){
        js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubricas2|rh27_rubric|rh27_descr&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
     }else if(campo == '03'){
        js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubricas3|rh27_rubric|rh27_descr&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',true);
     }
  }else{
     if(campo == '01'){
        if(document.form1.r55_rubr01.value != ''){
           quantcaracteres = document.form1.r55_rubr01.value.length;
           for(i=quantcaracteres;i<4;i++){
             document.form1.r55_rubr01.value = "0"+document.form1.r55_rubr01.value;        
           } 
           js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r55_rubr01.value+'&funcao_js=parent.js_mostrarubricas11&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
        }else{
           document.form1.rh27_descr01.value = '';
        }
     }else if(campo == '02'){
        if(document.form1.r55_rubr02.value != ''){ 
           quantcaracteres = document.form1.r55_rubr02.value.length;
           for(i=quantcaracteres;i<4;i++){
             document.form1.r55_rubr02.value = "0"+document.form1.r55_rubr02.value;        
           } 
           js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r55_rubr02.value+'&funcao_js=parent.js_mostrarubricas12&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
        }else{
           document.form1.rh27_descr02.value = '';
        }
     }else if(campo == '03'){
        if(document.form1.r55_rubr03.value != ''){
           quantcaracteres = document.form1.r55_rubr03.value.length;
           for(i=quantcaracteres;i<4;i++){
             document.form1.r55_rubr03.value = "0"+document.form1.r55_rubr03.value;        
           } 
           js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r55_rubr03.value+'&funcao_js=parent.js_mostrarubricas13&instit=<?=(db_getsession("DB_instit"))?>','Pesquisa',false);
        }else{
           document.form1.rh27_descr03.value = '';
        }
     }
  }
}
function js_mostrarubricas11(chave,erro){
  document.form1.rh27_descr01.value = chave; 
  if(erro==true){ 
    document.form1.r55_rubr01.focus(); 
    document.form1.r55_rubr01.value = ''; 
  }
}
function js_mostrarubricas12(chave,erro){
  document.form1.rh27_descr02.value = chave;
  if(erro==true){ 
    document.form1.r55_rubr02.focus(); 
    document.form1.r55_rubr02.value = ''; 
  }
}
function js_mostrarubricas13(chave,erro){
  document.form1.rh27_descr03.value = chave;
  if(erro==true){ 
    document.form1.r55_rubr03.focus(); 
    document.form1.r55_rubr03.value = ''; 
  }
}

function js_mostrarubricas1(chave1,chave2){
  document.form1.r55_rubr01.value = chave1;
  document.form1.rh27_descr01.value= chave2;
  db_iframe_rhrubricas.hide();
}
function js_mostrarubricas2(chave1,chave2){
  document.form1.r55_rubr02.value = chave1;
  document.form1.rh27_descr02.value= chave2;
  db_iframe_rhrubricas.hide();
}
function js_mostrarubricas3(chave1,chave2){
  document.form1.r55_rubr03.value = chave1;
  document.form1.rh27_descr03.value= chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_relac','func_relac.php?funcao_js=parent.js_preenchepesquisa|r55_codeve','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_relac.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>