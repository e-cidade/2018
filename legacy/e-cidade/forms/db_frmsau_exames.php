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
?
//MODULO: Ambulatorial
$clsau_exames->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("s130_i_codigo");
$clrotulo->label("s130_c_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Ts108_i_codigo?>">
       <?=@$Ls108_i_codigo?>
    </td>
    <td> 
<?
db_input('s108_i_codigo',10,$Is108_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts108_c_exame?>">
       <?=@$Ls108_c_exame?>
    </td>
    <td> 
<?
db_input('s108_c_exame',40,$Is108_c_exame,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ts108_i_grupoexame?>">
       <? db_ancora('<b>Grupo:</b>',"js_pesquisa_s108_i_grupoexame(true)",$db_opcao)?>
    </td>
    <td> 
			<?
        db_input('s108_i_grupoexame',10,$Is108_i_grupoexame,true,'text',$db_opcao," onchange='js_pesquisa_s108_i_grupoexame(false);' onFocus=\"nextfield='db_opcao'\" ");
      ?>
      <?
        db_input('s130_c_descricao',40,$Is130_c_descricao,true,'text',3,'');
      ?>
    </td>
  </tr>
  </table>
<p>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </center>
</form>
<script type="text/javascript">
function js_pesquisa_s108_i_grupoexame(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_sau_grupoexames','func_sau_grupoexames.php?funcao_js=parent.js_mostragrupoexame1|s130_i_codigo|s130_c_descricao','Pesquisa',true);
  }else{
     if(document.form1.s108_i_grupoexame.value != ''){ 
        //js_OpenJanelaIframe('top.corpo','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form2.z01_i_cgsund.value+'&funcao_js=parent.IFdb_iframe_agendamento.js_mostracgs','Pesquisa',false);
        js_OpenJanelaIframe('top.corpo.iframe_a1','db_iframe_sau_grupoexames','func_sau_grupoexames.php?pesquisa_chave='+document.form1.s108_i_grupoexame.value+'&funcao_js=parent.js_mostragrupoexame','Pesquisa',false);
     }else{
       document.form1.s130_i_codigo.value = '';
     }
  }
}

function js_mostragrupoexame(chave,erro){
  document.form1.s130_c_descricao.value = chave; 
  if(erro=='true'){ 
    document.form1.s108_i_grupoexame.focus(); 
    document.form1.s108_i_grupoexame.value = ''; 
  }
  
}

function js_mostragrupoexame1(chave2,chave1){

  	document.form1.s108_i_grupoexame.value = chave2;
  	document.form1.s130_c_descricao.value = chave1;
  	db_iframe_sau_grupoexames.hide();
}

function js_pesquisa(){
  js_OpenJanelaIframe('','db_iframe_sau_exames','func_sau_exames.php?funcao_js=parent.js_preenchepesquisa|s108_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_sau_exames.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>