<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: configuracoes
$cldb_registrosinconsistentes->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("nomearq");
?>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tdb136_sequencial?>">
       <?=@$Ldb136_sequencial?>
    </td>
    <td> 
<?
db_input('db136_sequencial',10,$Idb136_sequencial,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb136_data?>">
       <?=@$Ldb136_data?>
    </td>
    <td> 
<?
db_inputdata('db136_data',@$db136_data_dia,@$db136_data_mes,@$db136_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb136_usuario?>">
       <?
       db_ancora(@$Ldb136_usuario,"js_pesquisadb136_usuario(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db136_usuario',10,$Idb136_usuario,true,'text',$db_opcao," onchange='js_pesquisadb136_usuario(false);'")
?>
       <?
db_input('nome',40,$Inome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb136_tabela?>">
       <?
       db_ancora(@$Ldb136_tabela,"js_pesquisadb136_tabela(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('db136_tabela',10,$Idb136_tabela,true,'text',$db_opcao," onchange='js_pesquisadb136_tabela(false);'")
?>
       <?
db_input('nomearq',40,$Inomearq,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdb136_processado?>">
       <?=@$Ldb136_processado?>
    </td>
    <td> 
<?
$x = array("f"=>"NAO","t"=>"SIM");
db_select('db136_processado',$x,true,$db_opcao,"");
?>
    </td>
  </tr>
  </table>

<script>
function js_pesquisadb136_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.db136_usuario.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.db136_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = ''; 
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave; 
  if(erro==true){ 
    document.form1.db136_usuario.focus(); 
    document.form1.db136_usuario.value = ''; 
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.db136_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisadb136_tabela(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_sysarquivo','func_db_sysarquivo.php?funcao_js=parent.js_mostradb_sysarquivo1|codarq|nomearq','Pesquisa',true);
  }else{
     if(document.form1.db136_tabela.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_sysarquivo','func_db_sysarquivo.php?pesquisa_chave='+document.form1.db136_tabela.value+'&funcao_js=parent.js_mostradb_sysarquivo','Pesquisa',false);
     }else{
       document.form1.nomearq.value = ''; 
     }
  }
}
function js_mostradb_sysarquivo(chave,erro){
  document.form1.nomearq.value = chave; 
  if(erro==true){ 
    document.form1.db136_tabela.focus(); 
    document.form1.db136_tabela.value = ''; 
  }
}
function js_mostradb_sysarquivo1(chave1,chave2){
  document.form1.db136_tabela.value = chave1;
  document.form1.nomearq.value = chave2;
  db_iframe_db_sysarquivo.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_db_registrosinconsistentes','func_db_registrosinconsistentes.php?funcao_js=parent.js_preenchepesquisa|db136_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_db_registrosinconsistentes.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>