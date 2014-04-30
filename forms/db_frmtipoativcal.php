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
$clativtipo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q03_descr");
?>
<form name="form1" method="post" action="">
<center>
<br>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tq80_ativ?>" align="right">
       <?
       db_ancora(@$Lq80_ativ,"js_pesquisaq80_ativ(true);",$db_opcao);
       ?>
    </td>
    <td> 
<?
db_input('q80_ativ',4,$Iq80_ativ,true,'text',$db_opcao," onchange='js_pesquisaq80_ativ(false);'")
?>
       <?
db_input('q03_descr',40,$Iq03_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <input name="atualizar" type="button" disabled id="db_opcao" value="Atualizar" onclick="calculos.js_atualizar();" >
    </td>
  </tr>
  <tr>
    <td colspan="2">
       <iframe id="calculos"  frameborder="0" name="calculos"   leftmargin="0" topmargin="0" src="iss1_tipoativcal001.php" height="300" width="500">
       </iframe> 
    </td>  
  </tr>
  </table>
  </center>
</form>
<script>
function js_pesquisaq80_ativ(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_ativid','func_ativid.php?funcao_js=parent.js_mostraativid1|q03_ativ|q03_descr','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('top.corpo','db_iframe_ativid','func_ativid.php?pesquisa_chave='+document.form1.q80_ativ.value+'&funcao_js=parent.js_mostraativid','Pesquisa',false);
    }
  if(document.form1.q80_ativ.value!=""){
    document.form1.atualizar.disabled=false;
  }else{
    document.form1.q03_descr.value="";
    document.form1.atualizar.disabled=true;
  }  
}
function js_mostraativid(chave,erro){
  document.form1.q03_descr.value = chave; 
  if(erro==true){
   
    document.form1.q80_ativ.focus(); 
    document.form1.q80_ativ.value = ''; 
    document.form1.atualizar.disabled=true;
  
  }else{
    if(document.form1.q80_ativ.value!=""){
      document.form1.atualizar.disabled=false;
      calculos.location.href="iss1_ativtipo014.php?q80_ativ="+document.form1.q80_ativ.value;
    }  
  }
}
function js_mostraativid1(chave1,chave2){
  document.form1.q80_ativ.value = chave1;
  document.form1.q03_descr.value = chave2;
  calculos.location.href="iss1_ativtipo014.php?q80_ativ="+chave1;
  db_iframe_ativid.hide();
}
</script>