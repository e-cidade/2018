<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clcairetordem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e50_numemp");
?>
<form name="form1" method="post" action="">
<table border="0">
   <tr>
      <td>
        <fieldset><legend><b>Cadastro de Código de Arrecação</b></legend> 
        <table>
          <tr>
            <td nowrap title="<?=@$Tk32_sequencia?>">
              <?=@$Lk32_sequencia?>
            </td>
            <td> 
             <?
             db_input('k32_sequencia',10,$Ik32_sequencia,true,'text',3,"")
            ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=@$Tk32_numpre?>">
               <?=@$Lk32_numpre?>
             </td>
             <td> 
              <?
              db_input('k32_numpre',10,$Ik32_numpre,true,'text',$db_opcao,"onchange='js_validanumpre(this.value)'");
              db_input('k00_valor',20,null,true,'text',3);
              db_input('tipo',20,null,true,'text',3);
              ?>
             </td>
          </tr>
         <tr>
           <td nowrap title="<?=@$Tk32_ordpag?>">
           <?
             db_ancora(@$Lk32_ordpag,"js_pesquisak32_ordpag(true);",$db_opcao);
           ?>
        </td>
        <td> 
        <?
         db_input('k32_ordpag',10,$Ik32_ordpag,true,'text',$db_opcao," onchange='js_pesquisak32_ordpag(false);'")
        ?>
        <?
         db_input('e50_numemp',20,$Ie50_numemp,true,'text',3,'')
        ?>
      </td>
    </tr>
    </table>
    </fieldset>
    </td>
    </tr>
    <table>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </form>
<script>
function js_pesquisak32_ordpag(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord|e60_codemp','Pesquisa',true);
  }else{
     if(document.form1.k32_ordpag.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?pesquisa_chave='+document.form1.k32_ordpag.value+'&funcao_js=parent.js_mostrapagordem','Pesquisa',false);
     }else{
       document.form1.e50_numemp.value = ''; 
     }
  }
}
function js_mostrapagordem(chave,erro){
  document.form1.e50_numemp.value = chave; 
  if(erro==true){ 
    document.form1.k32_ordpag.focus(); 
    document.form1.k32_ordpag.value = ''; 
  }
}
function js_mostrapagordem1(chave1,chave2){
  document.form1.k32_ordpag.value = chave1;
  document.form1.e50_numemp.value = chave2;
  db_iframe_pagordem.hide();
}
function js_validanumpre(numpre){

    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','cai1_cairetordemvalida.php?iNumpre='+numpre+'&funcao_js=parent.js_retornanumpre','Pesquisa',false);
}
function js_retornanumpre(retorno, valor,tipo){

  if (retorno){
     document.form1.k00_valor.value   = valor;
     document.form1.db_opcao.disabled = false;
     document.form1.tipo.value        = tipo;
  }else{
     document.form1.k32_numpre.value  = '';
     document.form1.k00_valor.value   = 'Cod. Arrecadação Inválido';
     document.form1.db_opcao.disabled = true;
    
  }

}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cairetordem','func_cairetordem.php?funcao_js=parent.js_preenchepesquisa|k32_sequencia','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_cairetordem.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>