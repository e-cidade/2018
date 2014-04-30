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

//MODULO: selecao
$clcadferia->rotulo->label();
$clselecao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="right" nowrap title="<?=@$Tr44_selec?>">
      <?
      db_ancora(@$Lr44_selec, "js_pesquisar44_selec(true);", $db_opcao);
      ?>
    </td>
    <td colspan="3"> 
      <?
      db_input('r44_selec', 8, $Ir44_selec, true, 'text', $db_opcao, " onchange='js_pesquisar44_selec(false);'")
      ?>
      <?
      db_input('r44_descr', 60, $Ir44_descr, true, 'text', 3);
      ?>
    </td>
  </tr>
</table>
</center>
<input name="excluir" value="Excluir férias" type="submit" <?=($db_botao==false?"disabled":"")?> onblur="document.form1.r44_selec.focus();" onclick="return js_verificacampos();">
</form>
<script>
function js_verificacampos(){
  if(document.form1.r44_selec.value == ""){
    alert("Seleção não informada. Verifique!");
    document.form1.r44_selec.focus();
    return false;
  }

  return true;
}
function js_pesquisar44_selec(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?funcao_js=parent.js_mostraselecao1|r44_selec|r44_descr','Pesquisa',true);
  }else{
    if(document.form1.r44_selec.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_selecao','func_selecao.php?pesquisa_chave='+document.form1.r44_selec.value+'&funcao_js=parent.js_mostraselecao','Pesquisa',false);
    }else{
      document.form1.r44_descr.value = '';
    }
  }
}

function js_mostraselecao(chave,erro){
  document.form1.r44_descr.value = chave;
  if(erro == true){
    document.form1.r44_selec.focus(); 
    document.form1.r44_selec.value = '';
  }
}

function js_mostraselecao1(chave1,chave2){
  document.form1.r44_selec.value = chave1;
  document.form1.r44_descr.value = chave2;
  db_iframe_selecao.hide();
}
</script>