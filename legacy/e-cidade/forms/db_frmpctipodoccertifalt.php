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

//MODULO: issqn
$clpctipodoccertif->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc70_descr");
?>
<form name="form1" method="post" action="">
<center>
<br>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc72_pctipocertif?>" align="right">
       <?
       db_ancora(@$Lpc72_pctipocertif,"js_pesquisapc72_pctipocertif(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('pc72_pctipocertif',4,$Ipc72_pctipocertif,true,'text',$db_opcao," onchange='js_pesquisapc72_pctipocertif(false);'")
?>
       <?
db_input('pc70_descr',40,$Ipc70_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="atualizar" type="button" disabled id="db_opcao" value="Atualizar" onclick="documentos.js_atualizar();" >
    </td>
  </tr>
  <tr>
    <td colspan="2">
       <iframe id="documentos"  frameborder="0" name="documentos"   leftmargin="0" topmargin="0" src="com1_pctipodoccertifalt003.php" height="300" width="500">
       </iframe> 
    </td>  
  </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisapc72_pctipocertif(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_pctipocertif','func_pctipocertif.php?funcao_js=parent.js_mostrapctipocertif1|pc70_codigo|pc70_descr','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe_pctipocertif','func_pctipocertif.php?pesquisa_chave='+document.form1.pc72_pctipocertif.value+'&funcao_js=parent.js_mostrapctipocertif','Pesquisa',false);
    }
  if(document.form1.pc72_pctipocertif.value!=""){
    document.form1.atualizar.disabled=false;
  }else{
    document.form1.pc70_descr.value="";
    document.form1.atualizar.disabled=true;
  }  
}
function js_mostrapctipocertif(chave,erro){
  document.form1.pc70_descr.value = chave; 
  if(erro==true){ 
    document.form1.pc72_pctipocertif.focus(); 
    document.form1.pc72_pctipocertif.value = ''; 
    document.form1.atualizar.disabled=true;
    documentos.location.href="com1_pctipodoccertifalt003.php";
  }else{
    if(document.form1.pc72_pctipocertif.value!=""){
      document.form1.atualizar.disabled=false;
      documentos.location.href="com1_pctipodoccertifalt003.php?pc72_pctipocertif="+document.form1.pc72_pctipocertif.value;
    }  
  }
}
function js_mostrapctipocertif1(chave1,chave2){
  document.form1.pc72_pctipocertif.value = chave1;
  document.form1.pc70_descr.value = chave2;
  document.form1.atualizar.disabled=false;
  documentos.location.href="com1_pctipodoccertifalt003.php?pc72_pctipocertif="+chave1;
  db_iframe_pctipocertif.hide();
}
</script>