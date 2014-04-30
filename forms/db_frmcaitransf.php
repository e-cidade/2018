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

//MODULO: caixa
$clcaitransf->rotulo->label();
$clcaitransfdest->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("k13_descr");
$clrotulo->label("k02_descr");

if($db_opcao==1||$db_opcao==11){
    $db_action="cai1_caitransf004.php";
}elseif($db_opcao==2||$db_opcao==22){
         $db_action="cai1_caitransf005.php";
}elseif($db_opcao==3||$db_opcao==33){
         $db_action="cai1_caitransf006.php";
}  
?>
<form name="form1" method="post" action="<?=$db_action?>">
<center>
<fieldset >
<legend><b>Origem</b></legend>

<table border="0" align=center>
  <tr>
   <td colspan=2> &nbsp; </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk91_transf?>"> <?=@$Lk91_transf?> </td>
    <td><? db_input('k91_transf',10,$Ik91_transf,true,'text',3,"") ?> </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk91_descr?>"><?=@$Lk91_descr?></td>
    <td><?db_input('k91_descr',50,$Ik91_descr,true,'text',$db_opcao,"")?>  </td>
  </tr>

  <tr>
    <td nowrap title="<?=@$Tk91_tipo?>"><?=@$Lk91_tipo?></td>
    <td><?   
           $matriz = array('I'=>'Interferência', 'R'=>'Repasse');
           db_select('k91_tipo',$matriz,true,1);    
         ?> 
    </td>
  </tr>


  <tr>
    <td nowrap title="<?=@$Tk91_debito?>"><? db_ancora(@$Lk91_debito,"js_pesquisak91_debito(true);",$db_opcao);?></td>
    <td><? db_input('k91_debito',8,$Ik91_debito,true,'text',$db_opcao," onchange='js_pesquisak91_debito(false);'") ?> 
        <? db_input('debito_descr',40,$Ik13_descr,true,'text',3,'')    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk91_credito?>"><? db_ancora(@$Lk91_credito,"js_pesquisak91_credito(true);",$db_opcao);?> </td>
    <td><? db_input('k91_credito',8,$Ik91_credito,true,'text',$db_opcao," onchange='js_pesquisak91_credito(false);'") ?>
        <? db_input('credito_descr',40,$Ik02_descr,true,'text',3,'')       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk91_finalidade?>"><?=@$Lk91_finalidade?></td>
    <td><?db_textarea('k91_finalidade',2,48,$Ik91_finalidade,true,'text',$db_opcao,"")?>  </td>
  </tr>  
  </table>
</fieldset>
<fieldset>
<legend><b>Destino</b></legend>
<table border=0 align=center>
  <tr>
    <td nowrap title="<?=@$Tk92_instit?>"><?=@$Lk92_instit?></td>
    <td><? 
         // seleciona as instituições, exceto a instituição atual
         $res = pg_query("select codigo,nomeinst from db_config where codigo <> ".db_getsession("DB_instit"));
	 $db_matriz = array();
	 if (pg_numrows($res)>0){
            for ($x=0;$x<pg_numrows($res);$x++){
                 db_fieldsmemory($res,$x);
		 $db_matriz[$codigo]=$nomeinst;
	    }  
	 }  
	 db_select('k92_instit',$db_matriz,'true',$db_opcao,' onchange="js_limpa_instit();" ');
	?>    
    </td>
  </tr>  

  <tr>
    <td nowrap title="<?=@$Tk92_debito?>"><? db_ancora(@$Lk92_debito,"js_pesquisak92_debito(true);",$db_opcao);?></td>
    <td><? db_input('k92_debito',8,$Ik92_debito,true,'text',$db_opcao," onchange='js_pesquisak92_debito(false);'") ?> 
        <? db_input('dest_debito_descr',40,$Ik13_descr,true,'text',3,'','dest_debito_descr')    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tk91_credito?>"><? db_ancora(@$Lk92_credito,"js_pesquisak92_credito(true);",$db_opcao);?> </td>
    <td><? db_input('k92_credito',8,$Ik92_credito,true,'text',$db_opcao," onchange='js_pesquisak92_credito(false);'") ?>
        <? db_input('dest_credito_descr',40,$Ik02_descr,true,'text',3,'','dest_credito_descr')       ?>
    </td>
  </tr>


</table>
</fieldset>
</center>
<br>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_limpa_instit(){
   document.form1.k92_debito.value='';
   document.form1.k92_credito.value='';
   document.form1.dest_debito_descr.value='';
   document.form1.dest_credito_descr.value='';				
}  
function js_pesquisak91_debito(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_transf','db_iframe_conplanoexe','func_conplanoexe.php?funcao_js=parent.js_mostrasaltes1|c62_reduz|c60_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.k91_debito.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_transf','db_iframe_conplanoexe','func_conplanoexe.php?pesquisa_chave='+document.form1.k91_debito.value+'&funcao_js=parent.js_mostrasaltes','Pesquisa',false);
     }else{
       document.form1.debito_descr.value = ''; 
     }
  }
}
function js_mostrasaltes(chave,erro){
  document.form1.debito_descr.value = chave; 
  if(erro==true){ 
    document.form1.k91_debito.focus(); 
    document.form1.k91_debito.value = ''; 
  }
}
function js_mostrasaltes1(chave1,chave2){
  document.form1.k91_debito.value = chave1;
  document.form1.debito_descr.value = chave2;
  db_iframe_conplanoexe.hide();
}
function js_pesquisak91_credito(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_transf','db_iframe_conplanoexe','func_conplanoexe.php?funcao_js=parent.js_mostratabrec1|c62_reduz|c60_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.k91_credito.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_transf','db_iframe_conplanoexe','func_conplanoexe.php?pesquisa_chave='+document.form1.k91_credito.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }else{
       document.form1.credito_descr.value = ''; 
     }
  }
}
function js_mostratabrec(chave,erro){
  document.form1.credito_descr.value = chave; 
  if(erro==true){ 
    document.form1.k91_credito.focus(); 
    document.form1.k91_credito.value = ''; 
  }
}
function js_mostratabrec1(chave1,chave2){
  document.form1.k91_credito.value = chave1;
  document.form1.credito_descr.value = chave2;
  db_iframe_conplanoexe.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo.iframe_transf','db_iframe_caitransf','func_caitransf.php?funcao_js=parent.js_preenchepesquisa|k91_transf','Pesquisa',true,'0');
}
function js_preenchepesquisa(chave){
  db_iframe_caitransf.hide();
  <? 
    if($db_opcao!=1){
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    };
  ?>

}

function js_pesquisak92_debito(mostra){
  db_instit = document.form1.k92_instit.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_transf','db_iframe_conplanoexe','func_conplanoexe.php?db_instit='+db_instit+'&funcao_js=parent.js_dest_mostrasaltes1|c62_reduz|c60_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.k92_debito.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_transf','db_iframe_conplanoexe','func_conplanoexe.php?db_instit='+db_instit+'&pesquisa_chave='+document.form1.k92_debito.value+'&funcao_js=parent.js_dest_mostrasaltes','Pesquisa',false);
     }else{
       document.form1.dest_debito_descr.value = ''; 
     }
  }
}
function js_dest_mostrasaltes(chave,erro){
  document.form1.dest_debito_descr.value = chave; 
  if(erro==true){ 
    document.form1.k92_debito.focus(); 
    document.form1.k92_debito.value = ''; 
  }
}
function js_dest_mostrasaltes1(chave1,chave2){
  document.form1.k92_debito.value = chave1;
  document.form1.dest_debito_descr.value = chave2;
  db_iframe_conplanoexe.hide();
}
function js_pesquisak92_credito(mostra){
  db_instit = document.form1.k92_instit.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_transf','db_iframe_conplanoexe','func_conplanoexe.php?db_instit='+db_instit+'&funcao_js=parent.js_dest_mostratabrec1|c62_reduz|c60_descr','Pesquisa',true,'0');
  }else{
     if(document.form1.k92_credito.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_transf','db_iframe_conplanoexe','func_conplanoexe.php?db_instit='+db_instit+'&pesquisa_chave='+document.form1.k92_credito.value+'&funcao_js=parent.js_dest_mostratabrec','Pesquisa',false);
     }else{
        document.form1.dest_credito_descr.value = ''; 
     }
  }
}
function js_dest_mostratabrec(chave,erro){
  document.form1.dest_credito_descr.value = chave; 
  if(erro==true){ 
    document.form1.k92_credito.focus(); 
    document.form1.k92_credito.value = ''; 
  }
}
function js_dest_mostratabrec1(chave1,chave2){
  document.form1.k92_credito.value = chave1;
  document.form1.dest_credito_descr.value = chave2;
  db_iframe_conplanoexe.hide();
}
</script>