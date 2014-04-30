<?php
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

/**
 * 
 * @author Iuri Guntchnigg
 * @revision $Author: dbiuri $
 * @version $Revision: 1.3 $
 */
$clrotulo = new rotulocampo;
$clrotulo->label("l20_codigo");
$clrotulo->label("l20_edital");
$clrotulo->label("l11_obs");
?>
<form name="form1" method="post" action="">
<table>
  <tr>
    <td>
      <fieldset>
      <legend><b>Licitação Deserta:</legend>
        <table border='0'>
          <tr> 
            <td  align="left" nowrap title="<?=$Tl20_codigo?>">
            <b>
            <?db_ancora('Licitação',"js_pesquisa_liclicita(true);",1);?>&nbsp;:
            </b> 
            </td>
            <td align="left" nowrap>
              <? 
                db_input("l20_codigo",6,$Il20_codigo,true,"text",3,"onchange='js_pesquisa_liclicita(false);'");
              ?>
            </td>
          </tr>
          <tr> 
            <td  align="left" nowrap title="<?=$Tl20_codigo?>">
            <b>
            <b>Edital:</b>
            </b> 
            </td>
            <td align="left" nowrap>
              <? 
                db_input("l20_edital",6,$Il20_edital,true,"text",3);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <b>Motivo:</b>
            </td>
            <td>
               <?
                 db_textarea("l11_obs",10,60,$Il11_obs,true,"text", 1);
               ?>
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" 
             type="submit" id="db_opcao" onclick="return js_validaAcao(<?= $db_opcao?>)"
             value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>"
              <?=($db_botao==false?"disabled":"")?> >
    </td>
  </tr>
</table>
</form>
<script type="text/javascript">

function js_pesquisa_liclicita(mostra){
  if(mostra==true){
  
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_liclicita',
                        'func_liclicita.php?situacao=<?=$iTipo?>&funcao_js=parent.js_atualizaDados|l20_codigo|l20_edital',
                        'Pesquisa Licitações',true);
  }else{
  
     if(document.form1.l20_codigo.value != ''){ 
  
        js_OpenJanelaIframe('top.corpo',
                            'db_iframe_liclicita',
                            'func_liclicita.php?situacao=<?=$iTipo?>&pesquisa_chave='+
                             document.form1.l20_codigo.value+'&funcao_js=parent.js_mostraliclicita',
                            'Pesquisa',false);
     }else{
       document.form1.l20_codigo.value = ''; 
     }
  }
}
function js_mostraliclicita(chave,erro){
  if(erro==true){ 
  
      alert("Licitacao ja julgada,revogada ou com autorizacao ativa.");
      document.form1.l20_codigo.value = ''; 
      document.form1.l20_codigo.focus();
       
  } else {
  
      document.form1.l20_codigo.value = chave;
       
  }
}
function js_mostraliclicita1(chave1, chave2){

   document.form1.l20_codigo.value = chave1;  
   document.form1.l20_edital.value = chave2;  
   db_iframe_liclicita.hide();
   
}
function js_atualizaDados(iValor) {
  
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+iValor";
  ?>
}
function js_validaAcao(iAcao) {
  
  if (iAcao == 3) {
    var sMsg  = "Verificar se este(s) processo(s) de compra(s) já se encontra incluído(s) ";
        sMsg += "numa compra direta ou numa nova licitação.";
    if (!confirm(sMsg)) {
      return false;
    }
    sMsg  = "Confirma o cancelamento desta licitação como deserta ? ";
    if (!confirm(sMsg)) {
      return false;
    }
  }
  return true;
}
<?
if (!isset($oGet->chavepesquisa) ) {
  echo "js_pesquisa_liclicita(true)\n";
}
?>
</script>