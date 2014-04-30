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
$clppalei->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
?>
<form name="form1" method="post" action="">
<table>
<tr>
<td>
 <fieldset><legend><b>Lei o para PPA</b></legend>
<table border="0">
  <tr >
    <td nowrap title="<?=@$To01_sequencial?>">
       <?=@$Lo01_sequencial?>
    </td>
    <td> 
<?
db_input('o01_sequencial',10,$Io01_sequencial,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To01_anoinicio?>">
       <?=@$Lo01_anoinicio?>
    </td>
    <td> 
<?
db_input('o01_anoinicio',10,$Io01_anoinicio,true,'text',$db_opcao,"onchange='js_adicionaAno()'");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To01_anofinal?>">
       <?=@$Lo01_anofinal?>
    </td>
    <td> 
<?
db_input('o01_anofinal',10,$Io01_anofinal,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To01_descricao?>">
       <?=@$Lo01_descricao?>
    </td>
    <td> 
<?
db_input('o01_descricao',50,$Io01_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To01_numerolei?>">
       <?=@$Lo01_numerolei?>
    </td>
    <td> 
<?
db_input('o01_numerolei',10,$Io01_numerolei,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
  </table>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>"
       type="submit" id="db_opcao" onclick="return js_validarCadastro()"; 
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" 
       <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisao01_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.o01_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.o01_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.o01_instit.focus(); 
    document.form1.o01_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.o01_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_ppalei','func_ppalei.php?funcao_js=parent.js_preenchepesquisa|o01_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_ppalei.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_adicionaAno() {

  var iAnoInicial = new Number(document.getElementById('o01_anoinicio').value); 
  var iAnoFinal   = iAnoInicial+3;
  
  if (document.getElementById('o01_anofinal').value == "" && document.getElementById('o01_anoinicio').value != "") {
    
    document.getElementById('o01_anofinal').value = iAnoFinal;
    
  }
  
}
function js_validarCadastro() {
  
  var iAnoInicial = new Number(document.getElementById('o01_anoinicio').value); 
  var iAnoFinal   = new Number(document.getElementById('o01_anofinal').value); 
  if (iAnoFinal <= iAnoInicial) {
  
    alert('Ano Final deve ser maior que o ano inicial');
    return false;
    
  }
  
  if ((iAnoFinal - iAnoInicial) != 3) {
    
    alert('A diferença entre o ano final e ano inicial deve ser 4 anos');
    return false;
    
  }
}
</script>