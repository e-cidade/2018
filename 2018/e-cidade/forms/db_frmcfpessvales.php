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
$clcfpess->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh27_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <?
  $r11_anousu = db_anofolha();
  $r11_mesusu = db_mesfolha();
  db_input('r11_anousu',4,$Ir11_anousu,true,'hidden',$db_opcao,"");
  db_input('r11_mesusu',2,$Ir11_mesusu,true,'hidden',$db_opcao,"");
  $arr_SorN = array("t"=>"Sim","f"=>"Não");
  ?>
  <tr>
    <td align="center">
      <fieldset>
        <legend><strong>Vale semanal / diário</strong></legend>
        <table width="100%" >
          <tr>
            <td nowrap align="right" title="<?=@$Tr11_vtprop?>" width="80%">
              <?
              db_ancora(@$Lr11_vtprop,"",3);
              ?>
            </td>
            <td nowrap> 
              <?
              db_select('r11_vtprop',$arr_SorN,true,$db_opcao,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend><strong>Vale mensal</strong></legend>
        <table width="100%" >
          <tr>
            <td nowrap align="right" title="<?=@$Tr11_vtmpro?>" width="80%">
              <?
              db_ancora(@$Lr11_vtmpro,"",3);
              ?>
            </td>
            <td nowrap> 
              <?
              db_select('r11_vtmpro',$arr_SorN,true,$db_opcao,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend><strong>Afastamento férias</strong></legend>
        <table width="100%" >
          <tr>
            <td nowrap align="right" title="<?=@$Tr11_vtfer?>" width="80%">
              <?
              db_ancora(@$Lr11_vtfer,"",3);
              ?>
            </td>
            <td nowrap> 
              <?
              db_select('r11_vtfer',$arr_SorN,true,$db_opcao,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center">
      <fieldset>
        <legend><strong>Consulta funcionário</strong></legend>
        <table width="100%" >
          <tr>
            <td nowrap align="right" title="<?=@$Tr11_vtcons?>" width="80%">
              <?
              db_ancora(@$Lr11_vtcons,"",3);
              ?>
            </td>
            <td nowrap> 
              <?
              db_select('r11_vtcons',$arr_SorN,true,$db_opcao,"");
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisar11_rubdec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubdec1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_rubdec.value != ''){ 
      quantcaracteres = document.form1.r11_rubdec.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_rubdec.value = "0"+document.form1.r11_rubdec.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_rubdec.value+'&funcao_js=parent.js_mostrarubdec','Pesquisa',false);
    }else{
      document.form1.rh27_descr1.value = ''; 
    }
  }
}
function js_mostrarubdec(chave,erro){
  document.form1.rh27_descr1.value = chave; 
  if(erro==true){ 
    document.form1.r11_rubdec.focus(); 
    document.form1.r11_rubdec.value = ''; 
  }
}
function js_mostrarubdec1(chave1,chave2){
  document.form1.r11_rubdec.value = chave1;
  document.form1.rh27_descr1.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_ferias(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostraferias1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_ferias.value != ''){ 
      quantcaracteres = document.form1.r11_ferias.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_ferias.value = "0"+document.form1.r11_ferias.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_ferias.value+'&funcao_js=parent.js_mostraferias','Pesquisa',false);
    }else{
      document.form1.rh27_descr2.value = ''; 
    }
  }
}
function js_mostraferias(chave,erro){
  document.form1.rh27_descr2.value = chave; 
  if(erro==true){ 
    document.form1.r11_ferias.focus(); 
    document.form1.r11_ferias.value = ''; 
  }
}
function js_mostraferias1(chave1,chave2){
  document.form1.r11_ferias.value = chave1;
  document.form1.rh27_descr2.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_fer13(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrafer131|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_fer13.value != ''){ 
      quantcaracteres = document.form1.r11_fer13.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_fer13.value = "0"+document.form1.r11_fer13.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_fer13.value+'&funcao_js=parent.js_mostrafer13','Pesquisa',false);
    }else{
      document.form1.rh27_descr3.value = ''; 
    }
  }
}
function js_mostrafer13(chave,erro){
  document.form1.rh27_descr3.value = chave; 
  if(erro==true){ 
    document.form1.r11_fer13.focus(); 
    document.form1.r11_fer13.value = ''; 
  }
}
function js_mostrafer131(chave1,chave2){
  document.form1.r11_fer13.value = chave1;
  document.form1.rh27_descr3.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_fer13a(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrafer13a1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_fer13a.value != ''){ 
      quantcaracteres = document.form1.r11_fer13a.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_fer13a.value = "0"+document.form1.r11_fer13a.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_fer13a.value+'&funcao_js=parent.js_mostrafer13a','Pesquisa',false);
    }else{
      document.form1.rh27_descr4.value = ''; 
    }
  }
}
function js_mostrafer13a(chave,erro){
  document.form1.rh27_descr4.value = chave; 
  if(erro==true){ 
    document.form1.r11_fer13a.focus(); 
    document.form1.r11_fer13a.value = ''; 
  }
}
function js_mostrafer13a1(chave1,chave2){
  document.form1.r11_fer13a.value = chave1;
  document.form1.rh27_descr4.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_feradi(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostraferadi1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_feradi.value != ''){ 
      quantcaracteres = document.form1.r11_feradi.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_feradi.value = "0"+document.form1.r11_feradi.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_feradi.value+'&funcao_js=parent.js_mostraferadi','Pesquisa',false);
    }else{
      document.form1.rh27_descr5.value = ''; 
    }
  }
}
function js_mostraferadi(chave,erro){
  document.form1.rh27_descr5.value = chave; 
  if(erro==true){ 
    document.form1.r11_feradi.focus(); 
    document.form1.r11_feradi.value = ''; 
  }
}
function js_mostraferadi1(chave1,chave2){
  document.form1.r11_feradi.value = chave1;
  document.form1.rh27_descr5.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_fadiab(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrafadiab1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_fadiab.value != ''){ 
      quantcaracteres = document.form1.r11_fadiab.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_fadiab.value = "0"+document.form1.r11_fadiab.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_fadiab.value+'&funcao_js=parent.js_mostrafadiab','Pesquisa',false);
    }else{
      document.form1.rh27_descr6.value = ''; 
    }
  }
}
function js_mostrafadiab(chave,erro){
  document.form1.rh27_descr6.value = chave; 
  if(erro==true){ 
    document.form1.r11_fadiab.focus(); 
    document.form1.r11_fadiab.value = ''; 
  }
}
function js_mostrafadiab1(chave1,chave2){
  document.form1.r11_fadiab.value = chave1;
  document.form1.rh27_descr6.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_ferant(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostraferant1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_ferant.value != ''){ 
      quantcaracteres = document.form1.r11_ferant.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_ferant.value = "0"+document.form1.r11_ferant.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_ferant.value+'&funcao_js=parent.js_mostraferant','Pesquisa',false);
    }else{
      document.form1.rh27_descr7.value = ''; 
    }
  }
}
function js_mostraferant(chave,erro){
  document.form1.rh27_descr7.value = chave; 
  if(erro==true){ 
    document.form1.r11_ferant.focus(); 
    document.form1.r11_ferant.value = ''; 
  }
}
function js_mostraferant1(chave1,chave2){
  document.form1.r11_ferant.value = chave1;
  document.form1.rh27_descr7.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_feabot(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrafeabot1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_feabot.value != ''){ 
      quantcaracteres = document.form1.r11_feabot.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_feabot.value = "0"+document.form1.r11_feabot.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_feabot.value+'&funcao_js=parent.js_mostrafeabot','Pesquisa',false);
    }else{
      document.form1.rh27_descr8.value = ''; 
    }
  }
}
function js_mostrafeabot(chave,erro){
  document.form1.rh27_descr8.value = chave; 
  if(erro==true){ 
    document.form1.r11_feabot.focus(); 
    document.form1.r11_feabot.value = ''; 
  }
}
function js_mostrafeabot1(chave1,chave2){
  document.form1.r11_feabot.value = chave1;
  document.form1.rh27_descr8.value = chave2;
  db_iframe_rhrubricas.hide();
}
function js_pesquisar11_palime(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrapalime1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
    if(document.form1.r11_palime.value != ''){ 
      quantcaracteres = document.form1.r11_palime.value.length;
      for(i=quantcaracteres;i<4;i++){
        document.form1.r11_palime.value = "0"+document.form1.r11_palime.value;        
      }
      js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.r11_palime.value+'&funcao_js=parent.js_mostrapalime','Pesquisa',false);
    }else{
      document.form1.rh27_descr9.value = ''; 
    }
  }
}
function js_mostrapalime(chave,erro){
  document.form1.rh27_descr9.value = chave; 
  if(erro==true){ 
    document.form1.r11_palime.focus(); 
    document.form1.r11_palime.value = ''; 
  }
}
function js_mostrapalime1(chave1,chave2){
  document.form1.r11_palime.value = chave1;
  document.form1.rh27_descr9.value = chave2;
  db_iframe_rhrubricas.hide();
}
</script>