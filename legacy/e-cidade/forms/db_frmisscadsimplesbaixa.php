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

//MODULO: issqn
$clisscadsimplesbaixa->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("q42_descr");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
<td><fieldset><legend><b>Baixa de Simples</b></legend><table>
  <tr>
    <td nowrap title="<?=@$Tq39_sequencial?>">
       <?=@$Lq39_sequencial?>
    </td>
    <td> 
<?
db_input('q39_sequencial',10,$Iq39_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq39_isscadsimples?>">
       <?
       db_ancora(@$Lq39_isscadsimples,"js_pesquisaq39_isscadsimples(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q39_isscadsimples',10,$Iq39_isscadsimples,true,'text',$db_opcaoinscr," onchange='js_pesquisaq39_isscadsimples(false);'")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq39_dtbaixa?>">
       <?=@$Lq39_dtbaixa?>
    </td>
    <td> 
<?
db_inputdata('q39_dtbaixa',@$q39_dtbaixa_dia,@$q39_dtbaixa_mes,@$q39_dtbaixa_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq39_issmotivobaixa?>">
       <?=@$Lq39_issmotivobaixa?>
    </td>
    <td> 
       <?
       include("classes/db_issmotivobaixa_classe.php");
       $clissmotivobaixa = new cl_issmotivobaixa;
       $result = $clissmotivobaixa->sql_record($clissmotivobaixa->sql_query("","*"));
       db_selectrecord("q39_issmotivobaixa",$result,true,$db_opcao);
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tq39_obs?>">
       <?=@$Lq39_obs?>
    </td>
    <td> 
<?
db_textarea('q39_obs',8,60,$Iq39_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
	</fieldset></td></table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisaq39_isscadsimples(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_isscadsimples','func_isscadsimples.php?sbaixa=1&funcao_js=parent.js_mostraisscadsimples1|q38_sequencial|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.q39_isscadsimples.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_isscadsimples','func_isscadsimples.php?sbaixa=1&pesquisa_chave='+document.form1.q39_isscadsimples.value+'&funcao_js=parent.js_mostraisscadsimples','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostraisscadsimples(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.q39_isscadsimples.focus(); 
    document.form1.q39_isscadsimples.value = ''; 
  }
}
function js_mostraisscadsimples1(chave1,chave2){
  document.form1.q39_isscadsimples.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_isscadsimples.hide();
}
function js_pesquisaq39_issmotivobaixa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_issmotivobaixa','func_issmotivobaixa.php?funcao_js=parent.js_mostraissmotivobaixa1|q42_sequencial|q42_descr','Pesquisa',true);
  }else{
     if(document.form1.q39_issmotivobaixa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_issmotivobaixa','func_issmotivobaixa.php?pesquisa_chave='+document.form1.q39_issmotivobaixa.value+'&funcao_js=parent.js_mostraissmotivobaixa','Pesquisa',false);
     }else{
       document.form1.q42_descr.value = ''; 
     }
  }
}
function js_mostraissmotivobaixa(chave,erro){
  document.form1.q42_descr.value = chave; 
  if(erro==true){ 
    document.form1.q39_issmotivobaixa.focus(); 
    document.form1.q39_issmotivobaixa.value = ''; 
  }
}
function js_mostraissmotivobaixa1(chave1,chave2){
  document.form1.q39_issmotivobaixa.value = chave1;
  document.form1.q42_descr.value = chave2;
  db_iframe_issmotivobaixa.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_isscadsimplesbaixa','func_isscadsimplesbaixa.php?funcao_js=parent.js_preenchepesquisa|q39_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_isscadsimplesbaixa.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>