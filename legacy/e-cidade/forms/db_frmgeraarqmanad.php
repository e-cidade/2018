<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
?>
<form name="form1" method="post" action="">
<table width="100%" border="0">
  <tr>  
    <td>
      <fieldset>
        <legend><b>Geração de arquivos para MANAD</b></legend>
        <table cellpadding="0" cellspacing="0">
          <tr>
				    <td nowrap title="Data inicial do periodo">
				       <b>Data inicial : </b>
				    </td>
				    <td> 
							<?
							db_inputdata('dtini',null,null,null,true,'text',1,"")
							?>
				    </td>    
				  </tr>
          <tr>
            <td nowrap title="Data final do periodo">
               <b>Data final : </b>
            </td>
            <td> 
              <?
              db_inputdata('dtfim',null,null,null,true,'text',1,"")
              ?>
            </td>    
          </tr>
          <tr>   
			      <td>
			        <?
			         db_ancora($Lz01_nome,' js_cgm(true); ',1);
			        ?>
		        </td>
			      <td> 
				      <?
				       db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
				       db_input('z01_nome',40,0,true,'text',3,"");
				      ?>
			      </td>
			    </tr>
          <tr>
			      <td nowrap title="Código do municipio">
			        <b>Código do municipio : </b>
			      </td>
			      <td> 
			        <?
			        db_input('codigomunicipio',10,null,true,'text','');
			        ?>
			      </td>    
			    </tr>
          <tr>
            <td nowrap title="Código da finalidade">
              <b>Código da finalidade : </b>
            </td>
            <td> 
              <?
              $aFinalidades = array("61" => "Solicitação de Auditor-Fiscal da Secretaria da Receita Previdenciária através de MPF",
                                    "62" => "Entrega na Secretaria da Receita Previdenciária - Movimento anual de órgão publico, conforme intimação",
                                    "90" => "Dados Internos UF");
              
              db_select("finalidade",$aFinalidades,true,"");
              ?>
            </td>    
          </tr>
          <tr>
            <td nowrap title="Nome do arquivo a ser gerado">
              <b>Nome do Arquivo : </b>
            </td>
            <td> 
              <?
              db_input('nomearq',54,null,true,'text','');
              ?>
            </td>    
          </tr>
            <tr>
					    <td align="center" colspan=2>
					      <fieldset>
					        <legend><b>TABELAS DE PREVIDÊNCIA</b></legend>
					        <table width="100%">
					          <?
					          $anousu = db_anofolha();
					          $mesusu = db_mesfolha();
					          $result_tbprev = $clinssirf->sql_record($clinssirf->sql_query_file(null,db_getsession('DB_instit')," distinct cast(r33_codtab as integer)-2 as r33_codtab,r33_nome","r33_codtab","r33_codtab between 3 and 6 and r33_mesusu=$mesusu and r33_anousu=$anousu "));
								    for($i=0, $cont = 1; $i<$clinssirf->numrows; $i++){
								      db_fieldsmemory($result_tbprev, $i);
								      if(($i % 2) == 0 || $i == 0){
								        echo "<tr>";
								      }
					            echo "<td nowrap align='center' title='".$r33_nome."' width='10%'>
								              <input name='tab_".$r33_codtab."' value='".$r33_codtab."' type='checkbox' class='tabelas'>
								            </td>
								            <td nowrap align='left' title='".$r33_nome."' width='40%'>
								              <b>".$r33_nome."</b>
								            </td>";			
								      if($cont == 2 || ($i + 1) == $clinssirf->numrows){
								        echo "</tr>";
								        $cont = 0;
								      }
								      $cont ++;
								    }
					          ?>
					       </table>
					      </fieldset>
					    </td>
					  </tr>
					     </table>
        <br>
        <div id="divArquivo"></div>
        
      </fieldset>
    </td>
  </tr>
  <tr>
    <td align="center"><input type="button" name="btnGerar" value="Gerar Arquivo" onclick="js_GerarArquivo();"></td>
  </tr>
</table>
</form>


<script type="text/javascript">

function js_GerarArquivo(){

  var aCodigos      = new Array();
  var aTabelas      = new Array();
  
  var aInputs = document.getElementsByTagName('input');
  for (iInputs = 0; iInputs < aInputs.length; iInputs++) {
    var obj = aInputs[iInputs];
    if (obj.type == 'checkbox' && obj.className == 'tabelas' && obj.checked == true){
      aTabelas.push(obj.value);
    }
  } 
  
  if (aTabelas.length == 0) {
    alert("Selecione a(s) tabela(s) de previdencia !")
    return false;
  }
  
  var sDataini     = $('dtini').value;
  if (sDataini == ''){
    alert("Selecione a data inicial !")
    return false;
  }
  var sDatafim     = $('dtfim').value;
  if (sDatafim == ''){
    alert("Selecione a data final !")
    return false;
  }  
  var iCgm         = $('z01_numcgm').value;
  if (sDataini == ''){
    alert("Selecione o CGM da prestadora de serviço !")
    return false;
  }  
  var iFinalidade  = $('finalidade').value;
  var sNomeArquivo = $('nomearq').value;
  if (sNomeArquivo == ''){
    alert("Selecione o nome do arquivo !")
    return false;
  }  
  var sMunic       = $('codigomunicipio').value;
  if (sMunic == ''){
    alert("Selecione o código do municipio !")
    return false;
  }  
  
    
  var oObj           = new Object();  
  oObj.aTabelas      = aTabelas;
  oObj.aCodigoLinhas = aCodigos;
  oObj.sDataini      = sDataini;
  oObj.sDatafim      = sDatafim;
  oObj.iCgm          = iCgm;
  oObj.iFinalidade   = iFinalidade;
  oObj.sNomeArquivo  = sNomeArquivo;
  oObj.sMunic        = sMunic;
  
  strJson = Object.toJSON(oObj);
    
  js_OpenJanelaIframe('',
                      'db_iframe_gerararquivos',
                      'pes4_geraarquivosmanad.php?sStrJson='+strJson,
                      'Processando arquivos',
                      true);

}

  function js_LoadGrid(){
  
    oDBGridArquivo = new DBGrid('arquivo');
    oDBGridArquivo.nameInstance   = 'oDBGridArquivo';
    //oDBGridArquivo.setCheckbox(0);
    aHeader    = new Array();
    aHeader[0] = 'Código';
    aHeader[1] = 'Tabela';
    aHeader[2] = 'OBS';
    oDBGridArquivo.setHeader(aHeader);
    oDBGridArquivo.setHeight(250);
    var aAligns = new Array();
    aAligns[0] = 'center';
    aAligns[1] = 'left';
    aAligns[2] = 'left';
    oDBGridArquivo.setCellAlign(aAligns);
    oDBGridArquivo.show($('divArquivo'));
    
  }

  function js_consultaArquivo() {
  
    js_divCarregando("Aguarde, buscando registros","msgBox");  
    
    strJson = '{"exec":"getLinhasArquivo"}';
   
    var url     = 'pes4_geraarqmanadRPC.php';
    var oAjax   = new Ajax.Request( url, {
                                           method: 'post', 
                                           parameters: 'json='+strJson, 
                                           onComplete: js_saida
                                         }
                                  );

  }
  
function js_saida(oAjax) {

    var obj = eval("(" + oAjax.responseText + ")");
  
    if (obj.status && obj.status == 2){
       js_removeObj("msgBox");
       alert(obj.sMensagem.urlDecode());
       return false ;
    }
    
    oDBGridArquivo.clearAll(true);
    
    var aLinha = new Array();
    
    if (obj) {
      for (var iInd = 0; iInd < obj.length; iInd++) {
        with (obj[iInd]){
          aLinha[0]  = db51_codigo;
          aLinha[1]  = db51_descr.urlDecode();
          aLinha[2]  = db51_obs.urlDecode();
        }
        oDBGridArquivo.addRow(aLinha, false, false, false);
      }
      
      oDBGridArquivo.renderRows();
    }
    
    js_removeObj("msgBox");
}

function js_init(){
  
  js_LoadGrid();
  js_consultaArquivo();
  
  $('finalidade').style.width = '400px';

}

function js_cgm(mostra){
  var numcgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe','func_nome.php?testanome=true&funcao_js=parent.js_mostra|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_nome.php?testanome=true&pesquisa_chave='+numcgm+'&funcao_js=parent.js_mostra1','Pesquisa',false);
  }
}
function js_mostra(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe.hide();
}
function js_mostra1(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
  }
}

  
</script>