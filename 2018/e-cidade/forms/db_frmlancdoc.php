<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

//MODULO: Configuracoes

$clrotulo = new rotulocampo;

$clrotulo->label("z06_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("db44_descricao");

?>

<form name="form1" method="post" action="">
<center>
<table align=center style="margin-top: 15px">
  <tr>
    <td>
      <fieldset>
        <legend>
          <b>Documento</b>
        </legend>
        <table border="0">
          <tr>
            <td nowrap title="<?=@$Tz06_cgm?>">
            <?
              if (isset($z06_numcgm) && $z06_numcgm > 0) {
                db_ancora(@$Lz06_numcgm,"js_pesquisacgm(true);",3);
              } else {
                db_ancora(@$Lz06_numcgm,"js_pesquisacgm(true);",$db_opcao);
              }
            ?>
            </td>
            <td> 
            <?
               if (isset($z06_numcgm) && $z06_numcgm > 0) {
                 
                 db_input('z06_numcgm',10,$Iz06_numcgm,true,'text',3,"");
                 db_input('z01_nome',35,$Iz01_nome,true,'text',3,'');
                 
               } else {
                 
                 db_input('z06_numcgm',10,$Iz06_numcgm,true,'text',$db_opcao,"onchange='js_pesquisacgm(false);'");
                 db_input('z01_nome',35,$Iz01_nome,true,'text',3,'');
               }
            ?>
            </td>
          </tr>
          <tr id='lancaDoc'>
            <td nowrap title="<?=@$Tdb44_descricao?>">
              <b>Documento:</b>
            </td>
            <td> 
            <? 
               $where         = "db44_cadtipodocumento = {$tipoDocumento}";
               $oDocumento    = new cl_caddocumento();
               $sSqlDocumento = $oDocumento->sql_query_buscaDocTipo(null, "*", "", $where);
               $rsDocumento   = $oDocumento->sql_record($sSqlDocumento);
               $aDocumento    = db_utils::getCollectionByRecord($rsDocumento);
               $aDoc          = array(""=>"Selecione...");  
               
               foreach ($aDocumento as $doc) {         
                 $aDoc[$doc->db44_sequencial] = $doc->db44_descricao;
               }
            
               db_select('caddocumento', $aDoc, true, $db_opcao, "style='width:300px;'");
            ?>
              <input name="lancar" type="button" id="lancar" value="Lançar" onclick="js_lancaDocumento();" >
            </td>
          </tr>
        
        </table>
      </fieldset>
      <br>
      <fieldset>
        <legend>
          <b>Documentos Lançados</b>
        </legend>
        <div id="cntDBGrid">
        </div>
       </fieldset>
     </td>
   </tr>
</table>
<div id='btnVoltarCgm'>
<?
  if(!isset($lMostrarBotaoVoltar)) {
    $lMostrarBotaoVoltar = false;
  }
  if (isset($z06_numcgm) && $z06_numcgm > 0 && $lMostrarBotaoVoltar) {
    
?>
      <input name="voltar" type="button" id="voltar" value="Voltar Cadastro CGM" onclick="js_voltarCadCgm();" > 
<?
  }
?>
</div>

</center>
</form>
<script>
// Recebe a Url 
var sUrl = window.location.search;
var oUrl = null;
//Seta a flag "sConsula" como false
var sConsulta = false;


if (sUrl) {
  
  oUrl = js_urlToObject(sUrl);
  //Se exitir a flag consulta na URL seta ela com o valor passado
  if (oUrl.consulta) {
    sConsulta = new Boolean(oUrl.consulta);
  }
} 

/*
 * Se for consulta oculta dados de cadastro
 */
if (sConsulta) {

  $('lancaDoc').style.display     = "none";
  $('btnVoltarCgm').style.display = "none";
}
 

function js_voltarCadCgm() {
  location.href = 'prot1_cadcgm002.php?chavepesquiza='+$F('z06_numcgm');
}

function js_pesquisacgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z06_numcgm.value != ''){ 
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.z06_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}

function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave;
  js_consultaDocumentos(); 
  if(erro==true){ 
    document.form1.z06_numcgm.focus(); 
    document.form1.z06_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.z06_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
  js_consultaDocumentos();
}




function js_init() {

  oDBGrid              = new DBGrid("gridDocumentos");
  oDBGrid.nameInstance = "oDBGrid";
  oDBGrid.aWidths      = new Array("20%","65%","15%");
  oDBGrid.setCellAlign(new Array("center", "left", "center"));
  oDBGrid.setHeader(new Array("Código", "Descrição", "Opções"));  
  oDBGrid.show($('cntDBGrid'));
}

js_init();




var sUrl = 'prot1_lancadoc.RPC.php';
if (oUrl.createOnParent) {
  var oDBViewCadDocumento = new parent.dbViewCadastroDocumento();
} else {
  var oDBViewCadDocumento = new dbViewCadastroDocumento();
}

  
function js_consultaDocumentos() {
  
  js_divCarregando('Consultando Documentos...','msgBox');
  
  var oJson     = new Object();
  oJson.sMethod = "getDocumentosByCgm";
  oJson.iNumCgm = $F('z06_numcgm');
            
  var oAjax   = new Ajax.Request( sUrl, {
                                           method: 'post', 
                                           parameters: 'json='+ js_objectToJson(oJson), 
                                           onComplete: js_retorno_consultaDocumentos 
                                         }
                                 );    
}

function js_retorno_consultaDocumentos(oAjax) {
  
  js_removeObj("msgBox");
   
  var oRetorno    = eval("("+oAjax.responseText+")");
  var aDocumentos = oRetorno.aDocumentos;
  
  oDBGrid.clearAll(true);
  
  if ( aDocumentos.length > 0 ) {
    oDBGrid.setStatus("");
    
    aDocumentos.each(function (oDoc) {
      
      var aLinha = new Array();
      
      aLinha[0] = oDoc.db58_sequencial;
      aLinha[1] = oDoc.db44_descricao.urlDecode();
      
      if (sConsulta == true) {
        aLinha[2] = "<input type='button' value='Ver' onclick='js_lancaDocumentoAlt("+oDoc.db58_sequencial+", "+sConsulta+");'>";
                    
      } else {
      
        aLinha[2] = "<input type='button' value='A' onclick='js_lancaDocumentoAlt("+oDoc.db58_sequencial+");'>"+
                    "<input type='button' value='E' onclick='js_excluiDocumento("+oDoc.db58_sequencial+")'>";
      }
   
      
      
      
      oDBGrid.addRow(aLinha);     
      
    });
    
    oDBGrid.renderRows();
  
  } else {
    oDBGrid.setStatus("Nenhum Registro Encontrado");
  } 

}

function js_excluiDocumento(iCodDocumento) {
  
  if (confirm("Você realmente deseja desvincular o documento ( "+iCodDocumento+" )?")) {
  
    js_divCarregando('Excluindo Documento...','msgBox');
  
    var oJson           = new Object();
    oJson.sMethod       = "excluiDocumento";
    oJson.iCodDocumento = iCodDocumento; 
    oJson.iNumCgm       = $F('z06_numcgm');
    
            
    var oAjax   = new Ajax.Request( sUrl, {
                                             method: 'post', 
                                             parameters: 'json='+ js_objectToJson(oJson), 
                                             onComplete: js_retorno_excluiDocumento 
                                           }
                                   );
  }
}

function js_retorno_excluiDocumento(oAjax) {
  
  js_removeObj("msgBox");
   
  var oRetorno = eval("("+oAjax.responseText+")");
  
  alert(oRetorno.sMsg.urlDecode());
  
  if (oRetorno.iStatus == 1 ) {
    js_consultaDocumentos();
  }
       
}

function js_lancaDocumento(){
  
  var iCodCadDocumento = $F('caddocumento');
  
  if( iCodCadDocumento == "" || $F('z06_numcgm') == "" ) {
  
    alert("Você deve selecionar CGM e Documento.");
  } else {

    oDBViewCadDocumento.newDocument(iCodCadDocumento);
    oDBViewCadDocumento.setSaveCallBackFunction(js_incluirDocumento);
  }       
} 

function js_incluirDocumento(iCodDocumento) {
 
  js_divCarregando('incluindo Documento ...','msgBox');
  
  var oJson           = new Object();
  oJson.sMethod       = "incluirDocumento";
  oJson.iCodDocumento = iCodDocumento; 
  oJson.iNumCgm       = $F('z06_numcgm');
  //oJson.iCpfCnpj
            
  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: 'json='+ Object.toJSON(oJson), 
                                          onComplete: js_retornoIncluirDocumento 
                                        }
                                 );
}



function js_retornoIncluirDocumento(oAjax) {
  
  js_removeObj("msgBox");
   
  var oRetorno = eval("("+oAjax.responseText+")");
  
  alert(oRetorno.sMsg.urlDecode());
  
  if (oRetorno.iStatus == 2) {
    return false;
  } else {
    js_consultaDocumentos();
  }  
  
}



function js_lancaDocumentoAlt(iCodDocumento, sConsulta) {
  
  oDBViewCadDocumento.loadDocument(iCodDocumento, sConsulta);
}


function js_pesquisacgmdoc(mostra){
  if(document.form1.z06_numcgm.value != ''){ 
   js_OpenJanelaIframe('','db_iframe_doc',
                       'func_cgm.php?pesquisa_chave='+$F('z06_numcgm')+'&funcao_js=parent.js_mostracgmdoc',
                        'Pesquisa',false);
  }else{
   document.form1.z01_nome.value = ''; 
  }
  
}
function js_mostracgmdoc(erro,chave) {
  document.form1.z01_nome.value = chave;
   
  if (erro) {
   
    document.form1.z06_numcgm.focus(); 
    document.form1.z06_numcgm.value = ''; 
  }
  js_consultaDocumentos();
}

function js_buscaCgm() {

  var dbOpcao = <?=$db_opcao;?>;
  if (dbOpcao == 3 || dbOpcao == 33) {
    
    $('lancar').disabled = true;
   
    
  }
  
  $('z06_numcgm').disabled              = true;
  $('z06_numcgm').style.backgroundColor = 'rgb(222, 184, 135)';
  $('z06_numcgm').style.color           = "#000000";
  js_pesquisacgmdoc(false);
}

</script>