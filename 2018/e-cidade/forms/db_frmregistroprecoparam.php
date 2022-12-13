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

//MODULO: compras
$clregistroprecoparam->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("nomeinst");
require_once("libs/db_libdicionario.php");
?>
<form name="form1" method="post" action="">
  <table>
    <tr>
      <td>
        <fieldset>
          <legend><b>Parâmetros Registro Preço</b></legend>
          <table border="0">
            <tr>
              <td nowrap title="<?=@$Tpc08_incluiritemestimativa?>">
                 <?=@$Lpc08_incluiritemestimativa?>
              </td>
              <td> 
          <?
          $x = array("f"=>"NAO","t"=>"SIM");
          db_select('pc08_incluiritemestimativa',$x,true,$db_opcao,"");
          ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tpc08_alteraabertura?>">
                 <?=@$Lpc08_alteraabertura?>
              </td>
              <td> 
          <?
          $x = array("f"=>"NAO","t"=>"SIM");
          db_select('pc08_alteraabertura',$x,true,$db_opcao,"");
          ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tpc08_percentuquantmax?>">
                 <?=@$Lpc08_percentuquantmax?>
              </td>
              <td> 
              <?
              db_input('pc08_percentuquantmax',10,$Ipc08_percentuquantmax,true,'text',$db_opcao,"onchange='js_validaPercentual(this);'")
              ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?=@$Tpc08_ordemitensestimativa?>">
                 <?=@$Lpc08_ordemitensestimativa?>
              </td>
              <td> 
                  <?
                  $x = getValoresPadroesCampo('pc08_ordemitensestimativa');
                  db_select('pc08_ordemitensestimativa',$x,true,$db_opcao,"");
                  ?>
              </td>
            </tr>
            </table>
       </fieldset>
     </td>
   </tr>
   <tr>
     <td style='text-align: center'>
       <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
              type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
                  <?=($db_botao==false?"disabled":"")?> >
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
     </td> 
   </tr>
  </table>          
</form>
<script>
iMin = 0;
iMax = 100;
function js_pesquisapc08_instit(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst','Pesquisa',true);
  }else{
     if(document.form1.pc08_instit.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_db_config','func_db_config.php?pesquisa_chave='+document.form1.pc08_instit.value+'&funcao_js=parent.js_mostradb_config','Pesquisa',false);
     }else{
       document.form1.nomeinst.value = ''; 
     }
  }
}
function js_mostradb_config(chave,erro){
  document.form1.nomeinst.value = chave; 
  if(erro==true){ 
    document.form1.pc08_instit.focus(); 
    document.form1.pc08_instit.value = ''; 
  }
}
function js_mostradb_config1(chave1,chave2){
  document.form1.pc08_instit.value = chave1;
  document.form1.nomeinst.value = chave2;
  db_iframe_db_config.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_registroprecoparam','func_registroprecoparam.php?funcao_js=parent.js_preenchepesquisa|pc08_instit','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_registroprecoparam.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
function js_validaPercentual(input) {
   
   if (input.value < iMin) {
     input.value = iMin;
   }
   if (input.value > iMax) {
     input.value = iMax;
   }
}

$('pc08_percentuquantmax').onfocus = function(event) {
  if (this.value == "") {
    this.value = iMin;
  }
}

$('pc08_percentuquantmax').onkeydown = function(event) {
  
  if (!this.readOnly) {
   
    if (event.which == 40) {
    
     this.value = new Number(this.value)+1;
     js_validaPercentual(this);
     event.preventDefault();
    } else if(event.which == 38) {
      
      this.value -= 1;
      js_validaPercentual(this);
      event.preventDefault();
    }
  }
}
</script>