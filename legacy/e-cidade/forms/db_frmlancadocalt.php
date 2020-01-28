<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("pc60_numcgm");
$clrotulo->label("pc74_solicitante");
$clrotulo->label("pc74_pctipocertif");
?>
<form name="form1" method="post" action="">
<?
db_input('pc74_codigo',40,"",true,'hidden',3,'')
?>
<center>

<table align=center style="margin-top:15px;">
<tr><td>

<fieldset>
<legend><b>Cadastro de Certificados</b></legend>

<table border="0">
  <tr>
    <td nowrap title="<?=@$Tpc60_numcgm?>" align="left">
       <?
       db_ancora(@$Lpc60_numcgm,"js_pesquisapc60_numcgm(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('pc60_numcgm',4,$Ipc60_numcgm,true,'text',3," onchange='js_pesquisapc60_numcgm(false);'")
?>
       <?
db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tpc74_solicitante?>" align="left">
       <b>Solicitante :</b>
    </td>
    <td> 
       <?db_input('pc74_solicitante',47,@$Ipc74_solicitante,true,'text',$db_opcao,'')?>
    </td>
  </tr>
  
  <tr>
    <td nowrap title="<?=@$Tpc74_validade?>" align="left">
       <b>Validade do Certificado: </b>
    </td>
    <td> 
       <?
       if(isset($pc74_validade) && $pc74_validade!="") {
       	 $pc74_validade_dia = date("d", strtotime($pc74_validade));
       	 $pc74_validade_mes = date("m", strtotime($pc74_validade));
       	 $pc74_validade_ano = date("Y", strtotime($pc74_validade));
       }       
       
db_inputdata('pc74_validade', @$pc74_validade_dia, @$pc74_validade_mes, @$pc74_validade_ano, true, 'text', $db_opcao, "");
       ?>
    </td>
  </tr>
    
  
  
  <tr>
    <td nowrap title="<?=@$Tpc74_pctipocertif?>" align="left">
       <?
       db_ancora(@$Lpc74_pctipocertif,"js_pesquisapc72_pctipocertif(true);",3);
       ?>
    </td>
    <td> 
<?
db_input('pc74_pctipocertif',4,$Ipc74_pctipocertif,true,'text',3," onchange='js_pesquisapc72_pctipocertif(false);'")
?>
       <?
db_input('pc70_descr',40,$Ipc70_descr,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td align="left"><b>Imprimir Objeto Social:</b></td>
    <td >
      <?
      $db_matriz = array("0"=>'Sim',"1"=>"Não");
      db_select('oSocial',$db_matriz,TRUE,1); 
      ?>
    </td>
  </tr>
 </table>
 
 </fieldset>
 
 </td></tr>
 </table>
 
 <table border=0>
  <tr>
    <td colspan="2" align="center">
      <input name="atualizar" type="button" disabled id="db_opcao" value="Alterar" onclick="documentos.js_atualizar();" >
      <!--
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      -->
    </td>
  </tr>
  <tr>
    <td colspan="2">
      
       <iframe id="documentos"  frameborder="0" name="documentos"   leftmargin="0" topmargin="0" src="com4_lancadocalt002.php" height="400" width="900">
       </iframe> 
    </td>  
  </tr>
 </table>
  </center>
</form>
<script>


<?if (isset($pc74_pctipocertif)&&$pc74_pctipocertif!=""){?>
      document.form1.atualizar.disabled=false;
      documentos.location.href="com4_lancadocalt002.php?pc72_pctipocertif="+document.form1.pc74_pctipocertif.value+"&pc74_codigo="+document.form1.pc74_codigo.value;
<?}?>
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
    documentos.location.href="com4_lancadoc002.php";
  }else{
    if(document.form1.pc72_pctipocertif.value!=""){
      document.form1.atualizar.disabled=false;
      documentos.location.href="com4_lancadoc002.php?pc72_pctipocertif="+document.form1.pc72_pctipocertif.value;
    }  
  }
}
function js_mostrapctipocertif1(chave1,chave2){
  document.form1.pc72_pctipocertif.value = chave1;
  document.form1.pc70_descr.value = chave2;
  document.form1.atualizar.disabled=false;
  documentos.location.href="com4_lancadoc002.php?pc72_pctipocertif="+chave1;
  db_iframe_pctipocertif.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcforne','func_pcforne.php?funcao_js=parent.js_preenchepesquisa|pc60_numcgm','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_pcforne.hide();
  <?
  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  ?>
}
</script>