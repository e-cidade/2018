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
$clrotulo->label("pc74_codigo");

if($db_opcao == 1 || $db_opcao == 11) {
	
	$DB_instit = db_getsession("DB_instit");
	$oParam    = new cl_pcparam();
	$sSqlParam = $oParam->sql_query($DB_instit);
	$rsParam   = $oParam->sql_record($sSqlParam);
	$oParam    = db_utils::fieldsMemory($rsParam,0);
	    
	$valid = explode("-", date('Y-m-d', db_getsession("DB_datausu")));
	   
	if ($oParam->pc30_validadepadraocertificado > 0) {
	     
	  switch ($oParam->pc30_tipovalidade) {
	    case 1:
	      $valid[2] += $oParam->pc30_validadepadraocertificado;
	      break;
	    case 2:
	      $valid[1] += $oParam->pc30_validadepadraocertificado;
	      break;
	    case 3:
	      $valid[0] += $oParam->pc30_validadepadraocertificado;
	      break;
	  }
	      
	  $pc74_validade_dia = date("d", mktime(0, 0, 0, $valid[1], $valid[2], $valid[0]));
	  $pc74_validade_mes = date("m", mktime(0, 0, 0, $valid[1], $valid[2], $valid[0]));
	  $pc74_validade_ano = date("Y", mktime(0, 0, 0, $valid[1], $valid[2], $valid[0]));       
	}
}
?>
<form name="form1" method="post" action="">
<center>
<br>

<table border=0 style="margin-top: 15px;">
<tr><td>

<fieldset>
<legend><b>Cadastro de Certificados</b></legend>

<table border="0">

<?
db_input('pc74_codigo', 8, '', true, 'hidden', 3);
?>


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
       <b>Solicitante:</b>
    </td>
    <td> 
       <?
         // db_input('pc74_solicitante',47,@$Ipc74_solicitante,true,'text',$db_opcao,'')
         db_input('pc74_solicitante',47,"",true,'text',$db_opcao,'')
       ?>
    </td>
  </tr>
  
    <tr>
    <td nowrap title="<?=@$Tpc74_validade?>" align="left">
       <b>Validade do Certificado: </b>
    </td>
    <td> 
       <?
db_inputdata('pc74_validade', @$pc74_validade_dia, @$pc74_validade_mes, @$pc74_validade_ano, true, 'text', $db_opcao, "");
       ?>
    </td>
  </tr>
  
  
  <tr>
    <td nowrap title="<?=@$Tpc72_pctipocertif?>" align="left">
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
    <td align="left"><b>Imprimir Objeto Social:</b></td>
    <td >
      <?
      $db_matriz = array("0"=>'Sim',"1"=>"Não");
      db_select('oSocial',$db_matriz,true,1); 
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
      <input name="atualizar" type="button" disabled id="db_opcao" value="Incluir" onclick="documentos.js_atualizar();" >
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    </td>
  </tr>
  <tr>
    <td colspan="2">
      
       <iframe id="documentos"  frameborder="0" name="documentos"   leftmargin="0" topmargin="0" src="com4_lancadoc002.php" height="400" width="900">
       </iframe> 
    </td>  
  </tr>
    
  </tr>
  
  </table>
  </center>
</form>
<script>

var aDocs = new Array();

function js_verificaPorFornecedor(){
    
  js_divCarregando('Aguarde, Verificando Certificados do Fornecedor...','msgBox');
  
  var url    = 'geraCertificado.RPC.php';  
  var oParam = new Object(); 
    
  oParam.exec         = "verificaPorFornecedor";
  oParam.pcforne      = $F('pc60_numcgm');
  oParam.pctipocertif = $F('pc72_pctipocertif');
    
  var oAjax = new Ajax.Request( url, {
                                       method: 'post', 
                                       parameters: "json="+js_objectToJson(oParam),
                                       onComplete: js_retornoVerificaPorFornecedor
                                     }
                              );    
}

function js_retornoVerificaPorFornecedor(oAjax) {

  js_removeObj("msgBox");
  
  var oRetorno          = eval("("+oAjax.responseText+")");
  var possuiCertificado = oRetorno.possuiCertificado; 
  var oForneCertif      = oRetorno.oForneCertif;
  
  if (oRetorno.possuiCertificado == 1) {

    var txt  = "Já existe o certificado " + oForneCertif.pc74_codigo + " valido até "; 
        txt += js_formatar(oForneCertif.pc74_validade,"d") + " para este fornecedor.\n"; 
        txt += "Se deseja incluir um novo clique em Cancelar ou se deseja renovar o existente clique em OK.\n"; 
        txt += "Para reimprimir o certificado basta acessar o menu Compras > Relatórios > Emissão do certificado.";
    
    var altera = confirm(txt);
    
    if (altera == true) {
      
      $('pc74_codigo').value       = oForneCertif.pc74_codigo;
      $('pc74_solicitante').value  = oForneCertif.pc74_solicitante; 
      $('pc74_validade').value     = js_formatar(oForneCertif.pc74_validade,"d");
      $('pc72_pctipocertif').value = oForneCertif.pc74_pctipocertif;
      $('pc70_descr').value        = oForneCertif.pc70_descr;
      
      aDocs = oForneCertif.aDocs;
      
      document.form1.atualizar.disabled=false;
      documentos.location.href="com4_lancadoc002.php?pc74_pcforne="+$F('pc60_numcgm')+"&pc72_pctipocertif="+$F('pc72_pctipocertif');
            
    }
  }     
}

function js_loadFielDocs () {
      
      
      aDocs.each(function(doc) {
          documentos.$('DATA_'+doc.pc71_codigo).value        = js_formatar(doc.pc75_validade,"d");
          
          if (documentos.$F('DATA_'+doc.pc71_codigo) != "") {
          
            documentos.$('DATA_'+doc.pc71_codigo+'_dia').value = doc.pc75_validade.substr(8,2);
            documentos.$('DATA_'+doc.pc71_codigo+'_mes').value = doc.pc75_validade.substr(5,2);
            documentos.$('DATA_'+doc.pc71_codigo+'_ano').value = doc.pc75_validade.substr(0,4);   
          }
          
          documentos.$('EMISSAO_'+doc.pc71_codigo).value     = js_formatar(doc.pc75_dataemissao,"d");
          
          if (documentos.$F('EMISSAO_'+doc.pc71_codigo) != "") {
          
            documentos.$('EMISSAO_'+doc.pc71_codigo+'_dia').value = doc.pc75_dataemissao.substr(8,2);
            documentos.$('EMISSAO_'+doc.pc71_codigo+'_mes').value = doc.pc75_dataemissao.substr(5,2);
            documentos.$('EMISSAO_'+doc.pc71_codigo+'_ano').value = doc.pc75_dataemissao.substr(0,4);
          }
          
          documentos.$('APRESENTADO_'+doc.pc71_codigo).value = doc.pc75_apresentado;
          documentos.$('OBS_'+doc.pc71_codigo).value         = doc.pc75_obs;
          
      });  
}
  
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
    
    js_verificaPorFornecedor();
     
    document.form1.atualizar.disabled=true;
    documentos.location.href="com4_lancadoc002.php";
  }else{
    if(document.form1.pc72_pctipocertif.value!=""){
      
      js_verificaPorFornecedor();
      
      document.form1.atualizar.disabled=false;
      documentos.location.href="com4_lancadoc002.php?pc74_pcforne="+document.form1.pc60_numcgm.value+"&pc72_pctipocertif="+document.form1.pc72_pctipocertif.value;
    }  
  }
}
function js_mostrapctipocertif1(chave1,chave2){
  document.form1.pc72_pctipocertif.value = chave1;
  document.form1.pc70_descr.value = chave2;
  
  js_verificaPorFornecedor();
  
  document.form1.atualizar.disabled=false;
  documentos.location.href="com4_lancadoc002.php?pc72_pctipocertif="+chave1+"&pc74_pcforne="+document.form1.pc60_numcgm.value;
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

<? if (isset($chavepesquisa) && $chavepesquisa != "") { ?>
     //js_verificaPorFornecedor();
<? } ?>
</script>