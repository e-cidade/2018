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

//MODULO: Acordos
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_utils.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;
$clacordocomissaomembro->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac08_sequencial");
$clrotulo->label("ac07_descricao");
$clrotulo->label("z01_nome");


if(isset($ac07_acordocomissao)) {
	$oAcordo    = new cl_acordocomissao();
	$sSqlAcordo = $oAcordo->sql_query_file(null, "ac08_descricao", "", "ac08_sequencial={$ac07_acordocomissao}");
	$rsAcordo   = $oAcordo->sql_record($sSqlAcordo);
	$ac08_descricao = db_utils::fieldsMemory($rsAcordo,0)->ac08_descricao;	
}

?>
<form name="form1" method="post" action="">

<center>

<div style="margin-top:50px; width:750px;">

	<fieldset style="width: 100%;">
		<legend><b>Membros</b></legend>

		<table border="0" align='left'>
  		<?
  		  db_input('ac07_sequencial',10,$Iac07_sequencial,true,'hidden',3,"")
  		?>    
      <tr>
        <td nowrap title="<?=@$Tac07_acordocomissao?>">
          <?
            db_ancora("<b>Comissão</b>:","js_pesquisaac07_acordocomissao(true);",3);
          ?>
        </td>
        <td> 
    			<?
    			  db_input('ac07_acordocomissao',10,$Iac07_acordocomissao,true,'text',3, "")
    			?>
          <?
		        db_input('ac08_descricao',60,$Iac07_acordocomissao,true,'text',3,'')
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tac07_numcgm?>">
          <?
            db_ancora(@$Lac07_numcgm,"js_pesquisaac07_numcgm(true);",2);
          ?>
        </td>
        <td> 
          <?
					  db_input('ac07_numcgm',10,$Iac07_numcgm,true,'text',2," onchange='js_pesquisaac07_numcgm(false);'");
					  db_input('z01_nome',60,$Iz01_nome,true,'text',3,'');
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tac07_tipomembro?>">
          <?=@$Lac07_tipomembro?>
        </td>
        <td> 
          <?
            $oDaoAcordoComissaoTipoMembro = db_utils::getDao("acordocomissaotipomembro");
            $sSqlBuscaTipoMembro          = $oDaoAcordoComissaoTipoMembro->sql_query_file(null, "*", "ac42_sequencial");
            $rsBuscaTipoMembro            = $oDaoAcordoComissaoTipoMembro->sql_record($sSqlBuscaTipoMembro);
            
            $aTipo = array("0" => "Selecione");
            if ($oDaoAcordoComissaoTipoMembro->numrows > 0) {

              $aTipo= array();
              for ($iRowTipoMembro = 0; $iRowTipoMembro < $oDaoAcordoComissaoTipoMembro->numrows; $iRowTipoMembro++) {
                
                $oDadoTipoMembro = db_utils::fieldsMemory($rsBuscaTipoMembro, $iRowTipoMembro);
                $aTipo[$oDadoTipoMembro->ac42_sequencial] = $oDadoTipoMembro->ac42_descricao;
                unset($oDadoTipoMembro);
              }
            }
      			db_select('ac07_tipomembro', $aTipo, true, 1, "");			
    			?>
        </td>
      </tr>
    </table>
</fieldset>

	<div style="margin-top:10px;">
	  <input type="button" id="botao_controle" value="Incluir" onclick="js_incluiMembro();"> <!--  -->
	  <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_limpa()" >
	</div>


	<fieldset style="margin-top:10px;width: 100%;">
	  <legend align=center><b>Membros Cadastrados</b></legend>
	  <div id='cntGridMembros'></div>
	</fieldset>


</div>

</center>
</form>
<script>
function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
function js_pesquisaac07_acordocomissao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_acordocomissaomembro','db_iframe_acordocomissao','func_acordocomissao.php?funcao_js=parent.js_mostraacordocomissao1|ac08_sequencial|ac08_sequencial','Pesquisa',true,'0','1');
  }else{
     if(document.form1.ac07_acordocomissao.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_acordocomissaomembro','db_iframe_acordocomissao','func_acordocomissao.php?pesquisa_chave='+document.form1.ac07_acordocomissao.value+'&funcao_js=parent.js_mostraacordocomissao','Pesquisa',false);
     }else{
       document.form1.ac08_sequencial.value = ''; 
     }
  }
}
function js_mostraacordocomissao(chave,erro){
  document.form1.ac08_sequencial.value = chave; 
  if(erro==true){ 
    document.form1.ac07_acordocomissao.focus(); 
    document.form1.ac07_acordocomissao.value = ''; 
  }
}
function js_mostraacordocomissao1(chave1,chave2){
  document.form1.ac07_acordocomissao.value = chave1;
  document.form1.ac08_sequencial.value = chave2;
  db_iframe_acordocomissao.hide();
}
function js_pesquisaac07_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo.iframe_acordocomissaomembro','db_iframe_cgm','func_cgm.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true,'0','1');
  }else{
     if(document.form1.ac07_numcgm.value != ''){ 
        js_OpenJanelaIframe('top.corpo.iframe_acordocomissaomembro','db_iframe_cgm','func_cgm.php?pesquisa_chave='+document.form1.ac07_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}
function js_mostracgm(erro, chave) {

  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.ac07_numcgm.focus(); 
    document.form1.ac07_numcgm.value = ''; 
  }
}
function js_mostracgm1(chave1,chave2){
  document.form1.ac07_numcgm.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_cgm.hide();
}


/*
 * GRID
 */
function js_init() {

  oGridMembrosComissao              = new DBGrid("gridMembros");
  oGridMembrosComissao.nameInstance = "oGridMembrosComissao";

  oGridMembrosComissao.setCellWidth(new Array( '70px' ,
																		           '70px',
																		           '280px',
																		           '200px',
																		           '80px'
																		         ));

  oGridMembrosComissao.setCellAlign(new Array("center",
		                                          "center",
		                                          "left",
		                                          "left",
		                                          "center")
                                    );
  oGridMembrosComissao.setHeader(new Array("Código",
		                                       "Cgm",
		                                       "Membro",
		                                       "Responsabilidade",
		                                       "Ação")
                                );
  oGridMembrosComissao.show($('cntGridMembros'));
}
js_init();



/*
 *
 * AJAX's da pagina;
 *
 */
 
// CARREGA MEMBROS NA GRID 
var sUrl = 'con4_contratos.RPC.php';
  
 function js_consultaMembros(iAcordo){
	 
   var sMsg = _M("patrimonial.patrimonio.db_frmacordocomissaomembro.consultando_membros");
   js_divCarregando(sMsg,'msgBox');
   
   var strJson = '{"exec":"getMembros","iAcordo":"'+iAcordo+'"}';
             
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: 'json='+strJson, 
                                            onComplete: js_completaGrid 
                                          }
                                  );    
 }
 
 function js_completaGrid(oAjax){

   js_removeObj("msgBox");
   
   var oRetorno = eval("("+oAjax.responseText+")");   
   var aMembros = oRetorno.oAcordo.aMembros;   
   
   oGridMembrosComissao.clearAll(true);
      
   aMembros.each(function (oMembro, id) {
     
     sResponsabilidade = "";
     
     switch (oMembro.iResponsabilidade) {
      case "1":
        sResponsabilidade = "Principal";
        break;
      case "2":
        sResponsabilidade = "Secundário";
        break;
      case "3":
        sResponsabilidade = "Suplente";
        break;
      case "4":
        sResponsabilidade = "Fiscal";
        break;
     }
     
     var aLinha = new Array();
     
     aLinha[0] = oMembro.iCodigo;
     aLinha[1] = oMembro.iCodigoCgm;
     aLinha[2] = oMembro.sNome.urlDecode();
     aLinha[3] = sResponsabilidade;
     aLinha[4] = "<input type='button' value='A' onclick='js_carrega("+oMembro.iCodigo+", \"alt\")' width='1'>"+
                 "<input type='button' value='E' onclick='js_carrega("+oMembro.iCodigo+", \"exc\")' width='1'>";
     
     oGridMembrosComissao.addRow(aLinha);     
     
   });
   
   oGridMembrosComissao.renderRows();   
 }
 
 
 // INCLUSÃO DE MEMBROS //
 function js_incluiMembro() {
   
   if($F('ac07_numcgm') != "") {

	   var sMsg = _M("patrimonial.patrimonio.db_frmacordocomissaomembro.incluindo_membro");
	   js_divCarregando(sMsg,'msgBox');
	      
	   var strJson  = '{';
	       strJson += '"exec":"incluiMembro",';
	       strJson += '"iCodigoComissao":"'+$F('ac07_acordocomissao')+'",';       
	       strJson += '"iCodigoCgm":"'+$F('ac07_numcgm')+'",';
	       strJson += '"iResponsabilidade":"'+$F('ac07_tipomembro')+'"';       
	       strJson += '}';
	             
	   var oAjax   = new Ajax.Request( sUrl, {
	                                            method: 'post', 
	                                            parameters: 'json='+strJson, 
	                                            onComplete: js_concluiInclusao 
	                                          }
	                                  );
	 } else {
	   alert(_M("patrimonial.patrimonio.db_frmacordocomissaomembro.selecione_cgm"));
	   $('ac07_numcgm').focus();
	 }                                                                    
 }
 
 function js_concluiInclusao(oAjax) {
   js_removeObj("msgBox");   
   var oRetorno = eval("("+oAjax.responseText+")");
   var sMsg    = oRetorno.message;
   
   alert(sMsg.urlDecode());
   
   js_limpa();
   js_consultaMembros(oRetorno.iCodigo);
 }
   
 //ECLUIR
 function js_excluirMembro(iCodigo){

	 var sMsg = _M("patrimonial.patrimonio.db_frmacordocomissaomembro.excluindo_membro");
   js_divCarregando(sMsg,'msgBox');
   
   var strJson = '{"exec":"exluiMembro", "iCodigo":"'+iCodigo+'"}';
             
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: 'json='+strJson, 
                                            onComplete: js_concluiExclusao
                                          }
                                  );    
 }

 function js_concluiExclusao(oAjax) {
   js_removeObj("msgBox");   
   var oRetorno = eval("("+oAjax.responseText+")");
   var sMsg    = oRetorno.message;
   
   alert(sMsg.urlDecode());
   
   js_limpa();
   js_consultaMembros(oRetorno.iCodigo);
 }
  
 // CARREGA OS DADOS PARA EDIÇÂO OU EXCLUSÃO
 function js_carrega(iCodigo, sAcao){

	 var sMsg = _M("patrimonial.patrimonio.db_frmacordocomissaomembro.carregando_membro");
   js_divCarregando(sMsg,'msgBox');
   
   var strJson = '{"exec":"carregaMembro", "iCodigo":"'+iCodigo+'", "sAcao":"'+sAcao+'"}';
             
   var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: 'json='+strJson, 
                                            onComplete: js_setCampos 
                                          }
                                  );    
 }
 
 function js_setCampos(oAjax) {
   
   js_removeObj("msgBox");   
   var oRetorno = eval("("+oAjax.responseText+")");
   var sAcao    = oRetorno.sAcao; 
   var oMembro  = oRetorno.oMembro;
   
   $('ac07_sequencial').value = oMembro.iCodigo;
   $('ac07_numcgm').value = oMembro.iCodigoCgm;
   $('z01_nome').value = oMembro.sNome.urlDecode();
   $('ac07_tipomembro').value = oMembro.iResponsabilidade; 
   
   $('botao_controle').stopObserving("click");
   
   if(sAcao == 'alt') {
   
     $('botao_controle').value   = "Alterar";     
     $('botao_controle').onclick =  function(){ js_alteraMembro($F('ac07_sequencial')) };     
     $('botao_controle').disabled = false;
     $('ac07_numcgm').style.backgroundColor = 'white';
     
   } else {
   
     $('botao_controle').value    = "Excluir";
     $('botao_controle').onclick = function(){ js_excluirMembro($F('ac07_sequencial')) };     
     $('botao_controle').disabled = false;
     $('ac07_numcgm').readOnly    = true;
     $('ac07_numcgm').style.backgroundColor = 'rgb(222, 184, 135)';
     
   }
 }
 
 function js_alteraMembro(iCodigo) {
   
   if($F('ac07_numcgm') != "") {

	   var sMsg = _M("patrimonial.patrimonio.db_frmacordocomissaomembro.alterando_membro"); 
	   js_divCarregando(sMsg,'msgBox');
	   
	   var strJson  = '{';
	       strJson += '"exec":"alteraMembro",';
	       strJson += '"iCodigo":"'+iCodigo+'",';       
	       strJson += '"iCodigoCgm":"'+$F('ac07_numcgm')+'",';
	       strJson += '"iResponsabilidade":"'+$F('ac07_tipomembro')+'"';       
	       strJson += '}';
	             
	   var oAjax   = new Ajax.Request( sUrl, {
	                                            method: 'post', 
	                                            parameters: 'json='+strJson, 
	                                            onComplete: js_concluiAlteracao 
	                                          }
	                                  );
	 } else {
	   alert(_M("patrimonial.patrimonio.db_frmacordocomissaomembro.selecione_cgm"));
	   $('ac07_numcgm').focus();
	 }    
 }
 
 function js_concluiAlteracao(oAjax) {
   js_removeObj("msgBox");   
   var oRetorno = eval("("+oAjax.responseText+")");
   var sMsg    = oRetorno.message;
   
   alert(sMsg.urlDecode());
   
   js_limpa();
   js_consultaMembros(oRetorno.iCodigo);
 }
 
 // LIMPA CAMPOS PARA INCLUIR NOVO 
 function js_limpa() {
  
   $('ac07_sequencial').value = '';
   $('ac07_numcgm').value = '';
   $('z01_nome').value = '';
   $('ac07_tipomembro').value = '';
   
   with ($('ac07_numcgm')) { 
     readOnly  = false;
     value     = '';
     style.backgroundColor = 'white';
   }
   
   $('botao_controle').value    = "Incluir";
   $('botao_controle').onclick =  function(){ js_incluiMembro() };
   $('botao_controle').disabled = false;
 }
 
 js_consultaMembros(<?=$ac07_acordocomissao?>);

</script>