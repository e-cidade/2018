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

//MODULO: pessoal
$clpesdiver->rotulo->label();
$r07_instit = db_getsession("DB_instit");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td align="left" nowrap title="Digite o Ano / Mes de competência" >
      <strong>Ano / Mês :&nbsp;&nbsp;</strong>
    </td>
    <td>
      <?
      
      if(!isset($r07_anousu)){
        $r07_anousu = db_anofolha();
      }
      db_input('r07_anousu',4,$Ir07_anousu,true,'text',3,'');
      ?>
      &nbsp;/&nbsp;
      <?
      if(!isset($r07_mesusu)){
        $r07_mesusu = db_mesfolha();
      }
      db_input('r07_mesusu',2,$Ir07_mesusu,true,'text',3,'');
      db_input('r07_instit',2,$Ir07_instit,true,'hidden',3,'');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr07_codigo?>">
      <?=@$Lr07_codigo?>
    </td>
    <td> 
      <?
      db_input('r07_codigo',4,$Ir07_codigo,true,'text',($db_opcao==1?"1":"3"))
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr07_descr?>">
      <?=@$Lr07_descr?>
    </td>
    <td> 
      <?
      db_input('r07_descr',30,$Ir07_descr,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tr07_valor?>">
      <?=@$Lr07_valor?>
    </td>
    <td> 
      <?
      db_input('r07_valor',15,$Ir07_valor,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
</table>
</center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  onclick="return js_verificacodigo();">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_verificacodigo(){
  CodDiv = document.form1.r07_codigo.value;
  DDiv   = CodDiv.substr(0,1);
  NDiv   = CodDiv.substr(1,3);
  if(DDiv != "D" || isNaN(NDiv) || document.form1.r07_codigo.value.length < 4){
    alert("Código inválido.\nCódigo deve ter 4 (quatro) caracteres onde:\n- O caractere inicial deve ser 'D'.\n- Os 3 (três) caracteres posteriores devem ser numéricos.\n\nVerifique.");
    document.form1.r07_codigo.select();
    document.form1.r07_codigo.focus();
  }else if(document.form1.r07_descr.value == ""){
    alert("Informe a descrição.");
    document.form1.r07_descr.focus();
  }else{
    if(document.form1.r07_valor.value == ""){
      document.form1.r07_valor.value = 0;
    }
    return true;
  }
  return false;
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pesdiver','func_pesdiver.php?funcao_js=parent.js_preenchepesquisa|r07_anousu|r07_mesusu|r07_codigo&instit=<?=(db_getsession("DB_instit"))?>&chave_r07_mesusu='+document.form1.r07_mesusu.value+'&chave_r07_anousu='+document.form1.r07_anousu.value,'Pesquisa',true);
}
function js_preenchepesquisa(chave,chave1,chave2){
  db_iframe_pesdiver.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&chavepesquisa1='+chave1+'&chavepesquisa2='+chave2";
  }
  ?>
}
</script>