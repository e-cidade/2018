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

//MODULO: Trânsito
$clacidentes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("j13_descr");
$clrotulo->label("tr02_descr");
$clrotulo->label("tr03_descr");
$clrotulo->label("tr04_descr");
$clrotulo->label("j14_nome");
$clrotulo->label("tr01_descr");
      if($db_opcao==1){
 	   $db_action="tra1_acidentes004.php";
      }else if($db_opcao==2||$db_opcao==22){
 	   $db_action="tra1_acidentes005.php";
      }else if($db_opcao==3||$db_opcao==33){
 	   $db_action="tra1_acidentes006.php";
      }  
?>

<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ttr07_id?>">
       <?=@$Ltr07_id?>
    </td>
    <td> 
<?
db_input('tr07_id',5,$Itr07_id,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr07_tipoacid?>">
       <?
       db_ancora(@$Ltr07_tipoacid,"js_pesquisatr07_tipoacid(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('tr07_tipoacid',5,$Itr07_tipoacid,true,'text',$db_opcao," onchange='js_pesquisatr07_tipoacid(false);'")
?>
       <?
db_input('tr01_descr',35,$Itr01_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
   <tr>
    <td nowrap title="<?=@$Ttr07_idpista?>">
       <?
       db_ancora(@$Ltr07_idpista,"js_pesquisatr07_idpista(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('tr07_idpista',5,$Itr07_idpista,true,'text',$db_opcao," onchange='js_pesquisatr07_idpista(false);'")
?>
       <?
db_input('tr03_descr',35,$Itr03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr07_idtempo?>">
       <?
       db_ancora(@$Ltr07_idtempo,"js_pesquisatr07_idtempo(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('tr07_idtempo',5,$Itr07_idtempo,true,'text',$db_opcao," onchange='js_pesquisatr07_idtempo(false);'")
?>
       <?
db_input('tr04_descr',35,$Itr04_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr07_hora?>">
       <?=@$Ltr07_hora?>
    </td>
    <td>
<?
db_input('tr07_hora',10,$Itr07_hora,true,'text',$db_opcao,"onkeyPress=\" return vl_time(this,event)\"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr07_data?>">
       <?=@$Ltr07_data?>
    </td>
    <td>
<?
db_inputdata('tr07_data',@$tr07_data_dia,@$tr07_data_mes,@$tr07_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr07_local1?>">
       <?
       db_ancora(@$Ltr07_local1,"js_pesquisatr07_local1(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('tr07_local1',5,$Itr07_local1,true,'text',$db_opcao," onchange='js_pesquisatr07_local1(false);'")
?>
       <?
db_input('j14_nome',40,$Ij14_nome,true,'text',3,'')
       ?>
      <input type="checkbox" value="1" name="tr07_esquina" onclick="hab_local2()">Esquina
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr07_local2?>">
       <a id="func2"><?=@$Ltr07_local2?></a>
    </td>
    <td> 
<input name="tr07_local2" size="5" type='text' id='tr07_local2'
       onKeyUp="js_ValidaCampos(this,1,'Local do Acidente','f','f',event);"
       onKeyDown="return js_controla_tecla_enter(this,event);" value="<?=@$tr07_local2;?>">
  <input type="text" name="tr07locdescr" id="local2" style="visibility:hidden"
  readonly size="40" style="background-color:#DEB887">
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr07_idcausa?>">
       <?
       db_ancora(@$Ltr07_idcausa,"js_pesquisatr07_idcausa(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('tr07_idcausa',5,$Itr07_idcausa,true,'text',$db_opcao," onchange='js_pesquisatr07_idcausa(false);'")
?>
       <?
db_input('tr02_descr',35,$Itr02_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttr07_idbairro?>">
       <?
       db_ancora(@$Ltr07_idbairro,"js_pesquisatr07_idbairro(true);",$db_opcao);
       ?>
    </td>
    <td>
<?
db_input('tr07_idbairro',5,$Itr07_idbairro,true,'text',$db_opcao," onchange='js_pesquisatr07_idbairro(false);'")
?>
       <?
db_input('j13_descr',40,$Ij13_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisatr07_tipoacid(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_tipo_acidentes','func_tipo_acidentes.php?funcao_js=parent.js_mostratipo_acidentes1|tr01_id|tr01_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_tipo_acidentes','func_tipo_acidentes.php?pesquisa_chave='+document.form1.tr07_tipoacid.value+'&funcao_js=parent.js_mostratipo_acidentes','Pesquisa',false);
  }
}
function js_mostratipo_acidentes(chave,erro){
  document.form1.tr01_descr.value = chave;
  if(erro==true){
    document.form1.tr07_tipoacid.focus();
    document.form1.tr07_tipoacid.value = '';
  }
}
function js_mostratipo_acidentes1(chave1,chave2){
  document.form1.tr07_tipoacid.value = chave1;
  document.form1.tr01_descr.value = chave2;
  db_iframe_tipo_acidentes.hide();
}
function js_pesquisatr07_idtempo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_tipo_tempo','func_tipo_tempo.php?funcao_js=parent.js_mostratipo_tempo1|tr04_id|tr04_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_tipo_tempo','func_tipo_tempo.php?pesquisa_chave='+document.form1.tr07_idtempo.value+'&funcao_js=parent.js_mostratipo_tempo','Pesquisa',false);
  }
}
function js_mostratipo_tempo(chave,erro){
  document.form1.tr04_descr.value = chave; 
  if(erro==true){ 
    document.form1.tr07_idtempo.focus(); 
    document.form1.tr07_idtempo.value = ''; 
  }
}
function js_mostratipo_tempo1(chave1,chave2){
  document.form1.tr07_idtempo.value = chave1;
  document.form1.tr04_descr.value = chave2;
  db_iframe_tipo_tempo.hide();
}
function js_pesquisatr07_idpista(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_tipo_pista','func_tipo_pista.php?funcao_js=parent.js_mostratipo_pista1|tr03_id|tr03_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_tipo_pista','func_tipo_pista.php?pesquisa_chave='+document.form1.tr07_idpista.value+'&funcao_js=parent.js_mostratipo_pista','Pesquisa',false);
  }
}
function js_mostratipo_pista(chave,erro){
  document.form1.tr03_descr.value = chave;
  if(erro==true){
    document.form1.tr07_idpista.focus();
    document.form1.tr07_idpista.value = '';
  }
}
function js_mostratipo_pista1(chave1,chave2){
  document.form1.tr07_idpista.value = chave1;
  document.form1.tr03_descr.value = chave2;
  db_iframe_tipo_pista.hide();
}
function js_pesquisatr07_local1(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas1|j14_codigo|j14_nome&rural=1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.tr07_local1.value+'&funcao_js=parent.js_mostraruas&rural=1','Pesquisa',false);
  }
}
function js_pesquisatr07_local2(mostra){
  if(mostra==false){
  js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.tr07locdescr.value+'&funcao_js=parent.js_mostraruas&rural=1','Pesquisa',false);

  }else{
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_ruas','func_ruas.php?funcao_js=parent.js_mostraruas3|j14_codigo|j14_nome&rural=1','Pesquisa',true)
  }
}
function js_pesquisatr07_local45(){
   js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_ruas','func_ruas.php?pesquisa_chave='+document.form1.tr07_local2.value+'&funcao_js=parent.js_mostraruas2&rural=1','Pesquisa',false);
}
function js_mostraruas(chave,erro){
  document.form1.j14_nome.value = chave;
  if(erro==true){
    document.form1.tr07_local1.focus();
    document.form1.tr07_local1.value = '';
  }
}
function js_mostraruas1(chave1,chave2){
  document.form1.tr07_local1.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}
function js_mostraruas2(chave,erro){
  document.form1.tr07locdescr.value = chave;
  if(erro==true){
    document.form1.tr07_local2.focus();
    document.form1.tr07_local2.value = '';
  }
}
function js_mostraruas3(chave1,chave2){
  document.form1.tr07_local2.value = chave1;
  document.form1.tr07locdescr.value = chave2;
  db_iframe_ruas.hide();
}
function js_pesquisatr07_idcausa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_causas','func_causas.php?funcao_js=parent.js_mostracausas1|tr02_id|tr02_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_causas','func_causas.php?pesquisa_chave='+document.form1.tr07_idcausa.value+'&funcao_js=parent.js_mostracausas','Pesquisa',false);
  }
}
function js_mostracausas(chave,erro){
  document.form1.tr02_descr.value = chave; 
  if(erro==true){ 
    document.form1.tr07_idcausa.focus(); 
    document.form1.tr07_idcausa.value = ''; 
  }
}
function js_mostracausas1(chave1,chave2){
  document.form1.tr07_idcausa.value = chave1;
  document.form1.tr02_descr.value = chave2;
  db_iframe_causas.hide();
}
function js_pesquisatr07_idbairro(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|j13_codi|j13_descr','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.tr07_idbairro.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
  }
}
function js_mostrabairro(chave,erro){
  document.form1.j13_descr.value = chave; 
  if(erro==true){ 
    document.form1.tr07_idbairro.focus(); 
    document.form1.tr07_idbairro.value = ''; 
  }
}
function js_mostrabairro1(chave1,chave2){
  document.form1.tr07_idbairro.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe_bairro.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_acidente','db_iframe_acidentes','func_acidentes.php?funcao_js=parent.js_preenchepesquisa|tr07_id','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_acidentes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave;";
  }
  ?>
}
    function vl_time(objeto,evt){
      tecla = document.all ? event.keyCode : evt.which;
      vvalor  = objeto.value.length;
      if (vvalor == ""){
          if(tecla > 47 && tecla < 51){
            return true;
          }else{
            if (tecla == 8){
                return true;
              }else{
                return false;
              }
          }
      }
      if (vvalor == 1){
          if(tecla > 47 && tecla < 58){
             return true;
          }else{
               if (tecla == 8){
                return true;
              }else{
                return false;
              }
          }

     }
     if (vvalor == 2){
        if (tecla == 8){
           return true;
        }else{
           objeto.value += ":";
        }
       }
       if (vvalor == 3){
        if(tecla > 47 && tecla < 54){
            return true;
          }else{
            if (tecla == 8){
                return true;
              }else{
                return false;
              }
          }
      }
      if (vvalor == 4){
          if(tecla > 47 && tecla < 58 ){
             return true;
          }else{
              if (tecla == 8){
                return true;
              }else{
                return false;
              }
           }
       }
   }
</script>