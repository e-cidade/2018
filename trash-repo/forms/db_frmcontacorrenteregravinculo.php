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

//MODULO: contabilidade
require_once("libs/db_app.utils.php");
$clcontacorrenteregravinculo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("c17_descricao");
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("strings.js");
db_app::load("dbautocomplete.widget.js");
db_app::load("DBViewContaBancaria.js");
db_app::load("dbmessageBoard.widget.js");
db_app::load("estilos.css");
db_app::load("dbtextField.widget.js");
db_app::load("dbcomboBox.widget.js");
db_app::load("prototype.maskedinput.js");
db_app::load("windowAux.widget.js");
?>
<form name="form1" method="post" action="">

<fieldset style="margin-top: 50px; width: 500px;">
<legend><strong>Vincular Regra de Conta Corrente</strong></legend>

<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tc27_sequencial?>">
       <?=@$Lc27_sequencial?>
    </td>
    <td> 
        <?
          db_input('c27_sequencial',10,$Ic27_sequencial,true,'text',3,"")
        ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tc27_contacorrente?>">
       <?
       db_ancora(@$Lc27_contacorrente,"js_pesquisac27_contacorrente(true);",$db_opcao);
       ?>
    </td>
    <td> 
        <?
          db_input('c27_contacorrente',10,$Ic27_contacorrente,true,'text',$db_opcao," onchange='js_pesquisac27_contacorrente(false);'");
          db_input('c17_descricao',40,$Ic17_descricao,true,'text',3,'');
       ?>
    </td>
  </tr>
  
  	  <tr>
  	    <td nowrap="nowrap">
  	       <b>Estrutural Contabilidade:</b>
  	    </td>
  	    <td>
           <?
           $mascara = '0.0.0.0.0.00.00.00.00.00';
           db_input('mascara', 54, $Ic27_estrutural, true, 'text', 3, "", "","", "");
           ?>
        </td>
  	  </tr>  
  
  <tr>
    <td nowrap title="<?=@$Tc27_estrutural?>">
       <?=@$Lc27_estrutural?>
    </td>
    <td> 
      <?
        db_input('c27_estrutural',54,$Ic27_estrutural,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  </table>
  </center>
</fieldset>
  
<div style="margin-top: 10px;">
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</div>  


</form>
<script>
function js_pesquisac27_contacorrente(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_contacorrente','func_contacorrente.php?funcao_js=parent.js_mostracontacorrente1|c17_sequencial|c17_descricao','Pesquisa',true);
  }else{
     if(document.form1.c27_contacorrente.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_contacorrente','func_contacorrente.php?pesquisa_chave='+document.form1.c27_contacorrente.value+'&funcao_js=parent.js_mostracontacorrente','Pesquisa',false);
     }else{
       document.form1.c17_descricao.value = ''; 
     }
  }
}
function js_mostracontacorrente(chave,erro){
  document.form1.c17_descricao.value = chave; 
  if(erro==true){ 
    document.form1.c27_contacorrente.focus(); 
    document.form1.c27_contacorrente.value = ''; 
  }
}
function js_mostracontacorrente1(chave1,chave2){
  document.form1.c27_contacorrente.value = chave1;
  document.form1.c17_descricao.value = chave2;
  db_iframe_contacorrente.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_contacorrenteregravinculo','func_contacorrenteregravinculo.php?funcao_js=parent.js_preenchepesquisa|c27_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_contacorrenteregravinculo.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

/*
new MaskedInput("#c27_estrutural",
    $F('mascara'),
    {placeholder:"0"}
   );
*/   
</script>