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
if(!isset($anousu)){
  $anousu = db_anofolha();
}
if(!isset($mesusu)){
  $mesusu = db_mesfolha();
}
?>
<form name="form1" method="post" action="" enctype="multipart/form-data">
<center>
<table border="0">
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
    <td align="right" nowrap title="Digite o Ano / Mes de competência" >
      <strong>Ano / Mês :&nbsp;&nbsp;</strong> 
    </td>
    <td>
      <?
      db_input('anousu', 4, $anousu, true, 'text', 1, "");
      ?>
      &nbsp;/&nbsp;
      <?
      db_input('mesusu', 2, $mesusu, true, 'text', 1, "");
      ?>
    </td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
  <tr>
	<td colspan="2" nowrap align='left'>
<?

db_input('r56_dirarq',49,'' ,true,'file',1,"onblur='document.form1.r54_codeve.focus();'");
db_input('texto',20,0,true,'hidden',3);
?>
	</td>
  </tr>
  <tr>
    <td >&nbsp;</td>
    <td >&nbsp;</td>
  </tr>
</table>
</center>
<input name="gerar" type="submit" id="db_opcao" value="Importar" <?=($db_botao==false?"disabled":"")?> onclick="return js_verifica_campos();">
</form>
<script>
function js_verifica_campos(){
  if(document.form1.anousu.value == "" || document.form1.mesusu.value == ""){
    alert("Informe o ano/mês de competência.");
    document.form1.anousu.select();
    document.form1.anousu.focus();
    return false;
  }

  document.form1.texto.value = parent.bstatus.document.getElementById('st').innerHTML;
  parent.bstatus.document.getElementById('st').innerHTML = '&nbsp;&nbsp;<blink><strong><font color="red">GERANDO ARQUIVO</font></strong></blink>' ;

  return true;
}


function js_pesquisa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_layouttxt','func_db_layouttxt.php?funcao_js=parent.js_mostra1|db50_codigo|db50_descr','Pesquisa',true);
  }else{
    if(document.form1.db50_codigo.value != ''){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_layouttxt','func_db_layouttxt.php?pesquisa_chave='+document.form1.db50_codigo.value+'&funcao_js=parent.js_mostra2','Pesquisa',false);
    }else{
      document.form1.db50_codigo.value = '';
      document.form1.db50_descr.value = '';
      location.href = 'pes2_sapimportarelogio001.php';
    }
  }
}

</script>