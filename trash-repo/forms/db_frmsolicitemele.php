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

$clrotulo = new rotulocampo;
$clrotulo->label("pc01_descrmater");
$clrotulo->label("pc80_codproc");
?>
<center>
<form name='form1'>
<table border="0">
  <tr>  
    <td align="center" nowrap>
      <center>
      <iframe name="iframe_solicitemele" id="solicitem" marginwidth="0" marginheight="0" frameborder="0" src="com1_solicitemeleiframe001.php" width="770" height="380"></iframe>
      <?
      db_input('pc80_codproc',8,$Ipc80_codproc,true,'hidden',3);
      ?>
      </center>
    </td>
  </tr>  
  <tr>  
    <td align="center">
      <input name="incluir" type="button" id="incluir" value="Incluir sub-elementos" onclick='js_submit();'>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">      
    </td>
  </tr>  
</table>
</form>
</center>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcproc','func_pcproc.php?funcao_js=parent.js_preenchepesquisa|pc80_codproc','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pcproc.hide();
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&liberaaba=false'";
  ?>
}
function js_submit(){
  erro = 0;
  x = iframe_solicitemele.document.form1;
  for(i=0;i<x.length;i++){
    if(x.elements[i].type == "checkbox"){      
      if(x.elements[i].checked == true){
	erro++;
      }
    }
  }
  if(erro!=0){
    obj=iframe_solicitemele.document.createElement('input');
    obj.setAttribute('name','incluir');
    obj.setAttribute('type','hidden');
    obj.setAttribute('value','incluir');
    iframe_solicitemele.document.form1.appendChild(obj);
    iframe_solicitemele.document.form1.submit();
  }else{
    alert("Selecione um ou mais itens para continuar.");
  }
}
</script>