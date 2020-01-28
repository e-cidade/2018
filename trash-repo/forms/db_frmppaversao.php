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

//MODULO: orcamento
$clppaversao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("o01_descricao");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$To119_sequencial?>">
       <?=@$Lo119_sequencial?>
    </td>
    <td> 
<?
db_input('o119_sequencial',10,$Io119_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To119_ppalei?>">
       <?
       db_ancora(@$Lo119_ppalei,"js_pesquisao119_ppalei(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o119_ppalei',10,$Io119_ppalei,true,'text',$db_opcao," onchange='js_pesquisao119_ppalei(false);'")
?>
       <?
db_input('o01_descricao',100,$Io01_descricao,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To119_idusuario?>">
       <?
       db_ancora(@$Lo119_idusuario,"js_pesquisao119_idusuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('o119_idusuario',10,$Io119_idusuario,true,'text',$db_opcao," onchange='js_pesquisao119_idusuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To119_versao?>">
       <?=@$Lo119_versao?>
    </td>
    <td> 
<?
db_input('o119_versao',10,$Io119_versao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To119_finalizada?>">
       <?=@$Lo119_finalizada?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('o119_finalizada',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To119_datainicio?>">
       <?=@$Lo119_datainicio?>
    </td>
    <td> 
<?
db_inputdata('o119_datainicio',@$o119_datainicio_dia,@$o119_datainicio_mes,@$o119_datainicio_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To119_datatermino?>">
       <?=@$Lo119_datatermino?>
    </td>
    <td> 
<?
db_inputdata('o119_datatermino',@$o119_datatermino_dia,@$o119_datatermino_mes,@$o119_datatermino_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To119_versaofinal?>">
       <?=@$Lo119_versaofinal?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('o119_versaofinal',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao119_idusuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.o119_idusuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.o119_idusuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.o119_idusuario.focus(); 
    document.form1.o119_idusuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.o119_idusuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisao119_ppalei(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_ppalei','func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao','Pesquisa',true);
  }else{
     if(document.form1.o119_ppalei.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_ppalei','func_ppalei.php?pesquisa_chave='+document.form1.o119_ppalei.value+'&funcao_js=parent.js_mostrappalei','Pesquisa',false);
     }else{
       document.form1.o01_descricao.value = ''; 
     }
  }
}
function js_mostrappalei(chave,erro){
  document.form1.o01_descricao.value = chave; 
  if(erro==true){ 
    document.form1.o119_ppalei.focus(); 
    document.form1.o119_ppalei.value = ''; 
  }
}
function js_mostrappalei1(chave1,chave2){
  document.form1.o119_ppalei.value = chave1;
  document.form1.o01_descricao.value = chave2;
  db_iframe_ppalei.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_ppaversao','func_ppaversao.php?funcao_js=parent.js_preenchepesquisa|o119_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ppaversao.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>