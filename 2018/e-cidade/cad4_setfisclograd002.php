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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

require_once("classes/db_lote_classe.php");
require_once("classes/db_face_classe.php");
require_once("classes/db_testada_classe.php");
require_once("classes/db_lotesetorfiscal_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$cliframe_seleciona = new cl_iframe_seleciona;
$clface = new cl_face;
$db_opcao=1;
$db_botao=true;
		
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script>
function js_submit_form(){
  js_gera_chaves();
  return true;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("datagrid.widget.js");
  db_app::load("strings.js");
  db_app::load("grid.style.css");
  db_app::load("estilos.css");
  db_app::load("classes/dbViewAvaliacoes.classe.js");
  db_app::load("widgets/windowAux.widget.js");
  db_app::load("widgets/dbmessageBoard.widget.js");  
  db_app::load("dbcomboBox.widget.js");   
?>
<!--
<style>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
</style>
-->
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_gridSetor();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="cad4_setfisclograd003.php">
<center>
<fieldset style="margin-top: 20px; width: 700px;">
<legend><b>Manutenção Setor Fiscal / Face Quadra</b></legend>

<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <br>
  <br>
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC">     
    <center>
    <table >
<!-- 
    <tr>
    <td>
    <? 
           db_input("j14_codigo",10,"",true,"hidden",3);
    	     $cliframe_seleciona->campos  = "j37_face,j37_setor,j37_quadra";
           $cliframe_seleciona->legenda="Faces de Quadra";            
           $cliframe_seleciona->sql=$clface->sql_query(null,"*","j37_face","j37_codigo = $j14_codigo");                   
           $cliframe_seleciona->iframe_nome ="faces"; 
           $cliframe_seleciona->chaves = "j37_face";
           $cliframe_seleciona->iframe_seleciona(1);
           
    ?>
    </td>
    </tr>
-->
    
    <tr>
      <td> 
        
        <input class='todasfaces' type="checkbox" name ='todas_faces' id = 'todas_faces' value = '1' onclick="oGridSetor.selectAll('mtodositensSetor','checkboxSetor','SetorrowSetor')" >
        <b> Alterar Todas Faces Para o Setor Fiscal</b>
        <?
         $sSqlSetorFiscal = "select 0 as j90_codigo, 'Selecione Setor Fiscal' as j90_descr union all select j90_codigo,j90_descr from setorfiscal ";       
         $rsSetorFiscal = db_query($sSqlSetorFiscal);  
         db_selectrecord("unico_setor",$rsSetorFiscal,true,"text");
        
      ?>
      <b>e Zona:
      <?
         $sSqlZona = " select 0 as j50_zona, 'Nenhum...' as j50_descr union all  select j50_zona,j50_descr from zonas ";       
         $rsZona   = db_query($sSqlZona);  
       
    
         db_selectrecord("unico_zona",$rsZona,true,"text");
         db_ancora("Bairro","js_bairro(true);",$db_opcao);
         db_input('j34_bairro',4,"",true,'text',$db_opcao,"onchange='js_bairro(false);'");
         db_input('j13_descr',30,"",true,'text',3,'');
       ?>      
      </b>
        
      </td>
    </tr>        
    
    <tr>
      <td> &nbsp; </div> </td>
    </tr>
        
    <tr>
      <td> <div id='ctnSetor'> </div> </td>
    </tr>
    
    
    
    </table>
    </center>
    </td>
  </tr>
 </table> 
</fieldset> 
 
  <table border="0" cellspacing="0" cellpadding="0" width="100%" style="margin-top: 10px;">
  <tr>
    <td align="center">
    	 <!--  <input name="Enviar" type="button"  value="teste" onclick="js_gridSetor();" > -->  
    	<input type="button" value="Enviar" onclick="js_atualizaDados();">
    </td>
  </tr>
</table>
</center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_bairro(mostra){
  if(mostra==true){
     js_OpenJanelaIframe('top.corpo','db_iframe','func_bairro.php?funcao_js=parent.js_completabairro1|0|1','Pesquisa',true);
  }else{
     js_OpenJanelaIframe('top.corpo','db_iframe','func_bairro.php?pesquisa_chave='+$('j34_bairro').value+'&funcao_js=parent.js_completabairro','Pesquisa',false);
  }
}
function js_completabairro(chave,erro){
  $('j13_descr').value = chave; 
  if(erro==true){ 
    $('j34_bairro').focus(); 
    $('j34_bairro').value = ''; 
  }
}
function js_completabairro1(chave1,chave2){
  $('j34_bairro').value = chave1;
  $('j13_descr').value = chave2;
  db_iframe.hide();
}




var sUrlRPC = "cad4_setfisclograd.RPC.php";

 /*
  * Inicia a Montagem do grid (sem os registros)
  *
  */
function js_gridSetor() {

  oGridSetor = new DBGrid('Setor');
  oGridSetor.nameInstance = 'oGridSetor';
  oGridSetor.setCheckbox(0);
  //oGridSetor.allowSelectColumns(true);
  oGridSetor.setCellWidth(new Array( '40px'  ,
                                     '40px' ,
                                     '40px' ,
                                     '70px' ,
                                     '130px' ,
                                     '130px' ,
                                     '150px' ,
                                     '300px' ,
                                     '100px'  
                                    ));
  
  oGridSetor.setCellAlign(new Array( 'center'  ,
                                     'center'  ,
                                     'center',
                                     'left'  ,
                                     'center',
                                     'left'  ,
                                     'left'  ,
                                     'left',
                                     'left'
                                    ));
  
  
  oGridSetor.setHeader(new Array( 'Face',
                                  'Setor',
                                  'Quadra',
                                  'Setor Fiscal',
                                  'Novo Setor Fiscal',
                                  'Nova Zona p/ Lotes',
                                  'Bairro Atual',
                                  'Novo Bairro',
                                  'id ' 
                                 ));
                                       
  oGridSetor.aHeaders[9].lDisplayed = false; 
  //oGridSetor.aHeaders[9].lDisplayed = false; 

  oGridSetor.setHeight(300);
  oGridSetor.show($('ctnSetor'));
  oGridSetor.clearAll(true);
  
  lista_Setores();
  
}


/*
 * funcao para montar os registros iniciais da grid
 *
 */ 
function lista_Setores() {


   // codigo do logradouro selecionado
   var iCodigo = <?=$j14_codigo?>;
    
   var msgDiv             = "Aguarde ...";
   var oParametros        = new Object();
   
   oParametros.exec       = 'Face';
   oParametros.iCodigo    = iCodigo;
    
   js_divCarregando(msgDiv,'msgBox');
  
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoCompletaSetores
                                             });
                                            
}
/*
 * funcao para montar a grid com os registros de interessados
 *  retornado do RPC
 *
 */ 
function js_retornoCompletaSetores(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 1) {
      
      oGridSetor.clearAll(true);
      if ( oRetorno.dados.length == 0 ) {
      
        alert('Nenhum registro encontrado!');
        return false;
      } 

      oRetorno.dados.each( 
        function (oDado, iInd) {       
                      
                      
          var aRow      = new Array();  
          var iIdCampos =  oDado.face + oDado.iSetor + oDado.quadra + oDado.iBairro;
          
              aRow[0] = oDado.face;
              aRow[1] = oDado.setor;
              aRow[2] = oDado.quadra;
              aRow[3] = oDado.setor_fiscal.urlDecode();  
                 
              /*
							   percorremos o vetor novo setor
								 para montar o select para a grid
							*/                 
          var sHtmlNovoSetor = "<select name='novosetor_"+iIdCampos+"' id='novosetor_"+iIdCampos+"' style='width:95%;'>";
							oRetorno.dados[0].novo_setor.each(
								function(oValor, iIndice){
														                 
										sHtmlNovoSetor +="<option value='"+oValor.j90_codigo+"'>"+oValor.j90_descr+"</option>";              
							});   
							sHtmlNovoSetor += "</select>";  
							                     
              aRow[4] = sHtmlNovoSetor;
              
              /*
                percorremos o vetor nova zona
              */
          var sHtmlNovaZona = "<select name='novazona_"+iIdCampos+"'  id='novazona_"+iIdCampos+"' style='width:95%;'>";
              oRetorno.dados[0].nova_zona.each(
                function(oValor, iIndice){
                                             
                    sHtmlNovaZona +="<option value='"+oValor.j50_zona+"'>"+oValor.j50_descr+"</option>";              
              });   
              sHtmlNovaZona += "</select>";               
              aRow[5] = sHtmlNovaZona;
              
              aRow[6] = oDado.bairro.urlDecode(); 
              
              //novo bairro
              
          var sHtmlNovoBairro = "<select name='novobairro_"+iIdCampos+"' id='novobairro_"+iIdCampos+"' style='width:95%;'>";
              oRetorno.dados[0].novo_bairro.each(
                function(oValor, iIndice){
                                             
                    sHtmlNovoBairro +="<option value='"+oValor.j13_codi+"'>"+oValor.j13_descr+"</option>";              
              });   
              sHtmlNovoBairro += "</select>";  
                           
			    var sHtmlBairros  = "<a class='dbancora' onclick='js_pesquisaj34_bairro(true,"+iIdCampos+");' style='text-decoration: none;' href='#'>";
			        sHtmlBairros += "<strong>Bairro : </strong> <a> &nbsp;";
			        sHtmlBairros += "<input type='text' onchange='js_pesquisaj34_bairro(false, "+iIdCampos+");' ";
			        sHtmlBairros += " name='j34_bairro_"+iIdCampos+"' id='j34_bairro_"+iIdCampos+"' style='width:50px;' />  &nbsp;";
			        sHtmlBairros += "<input type='text' readonly='readonly' name='j13_descr_"+iIdCampos+"' ";
			        sHtmlBairros += "  id='j13_descr_"+iIdCampos+"'  style='width:200px;background-color:#DEB887;' />  &nbsp;";              
              
              aRow[7] = sHtmlBairros;
              aRow[8] = iIdCampos; 
              
              oGridSetor.addRow(aRow);
                            
      });
      oGridSetor.renderRows(); 
    }
}

/*
   função para pesquisa de bairros
*/
function js_pesquisaj34_bairro(mostra, iFace){

  if(mostra==true){
     js_OpenJanelaIframe('top.corpo','db_iframe','func_bairroFaces.php?iFace='+iFace+'&funcao_js=parent.js_mostrabairro1|0|1|dl_Face','Pesquisa',true);
  }else{
     js_OpenJanelaIframe('top.corpo','db_iframe','func_bairroFaces.php?dl_Face='+iFace+'&pesquisa_chave='+$F('j34_bairro_'+iFace)+'&funcao_js=parent.js_mostrabairro','Pesquisa',false);
  }
}
function js_mostrabairro(chave,erro, iFace){

  $('j13_descr_'+iFace).value = chave;
  if(erro==true){ 
    $('j34_bairro_'+iFace).focus(); 
    $('j34_bairro_'+iFace).value = ''; 
  }
}
function js_mostrabairro1(chave1,chave2, iFace){

  $('j34_bairro_'+iFace).value = chave1;
  $('j13_descr_'+iFace).value = chave2;
  db_iframe.hide();
}


function js_atualizaDados(){

   var aListaCheckbox     = oGridSetor.getSelection();
   var aListaMarcados     = new Array();
   var sListaMarcados     = "";
   var msgDiv             = "Aguarde ...";
   var oParametros        = new Object();
   var aValoresFinais     = new Array();
   var aCheckbox = $('todas_faces').checked; 
   var iTotalSelecionados = aListaCheckbox.length;
   
   if (iTotalSelecionados == 0) {
   
      alert("Selecione Um Registro");
      return false;
   }
   
       
   aListaCheckbox.each(
   
     function ( aRow ) {
       
       var oDadosSelecionado          = new Object();
           oDadosSelecionado.rua      = <?=$j14_codigo?>;        
           oDadosSelecionado.face     = aRow[1];
           oDadosSelecionado.setor    = aRow[2];
           oDadosSelecionado.quadra   = aRow[3];
           
        if (aCheckbox) { // altera para todos iguais
        
           oDadosSelecionado.n_fiscal = $F('unico_setor');  //aRow[5];
           oDadosSelecionado.n_zona   = $F('unico_zona');  //aRow[6];
           oDadosSelecionado.n_bairro = $F('j34_bairro');  //aRow[8];  
     
        } else {        //altera somente selecionados
           
           //alert( aRow[9]); return false;
           oDadosSelecionado.n_fiscal = $F('novosetor_'  + aRow[9]);  //aRow[5];
           oDadosSelecionado.n_zona   = $F('novazona_'   + aRow[9]);  //aRow[6];
           oDadosSelecionado.n_bairro = $F('j34_bairro_' + aRow[9]);  //aRow[8];
          
        }           
           
       aValoresFinais.push(oDadosSelecionado);
   });
   
  
//alert(aValoresFinais.toSource());
//return false;  
   
   oParametros.exec       = 'Atualizar';
   oParametros.aValores   = aValoresFinais;
    
   js_divCarregando(msgDiv,'msgBox');
   var oAjaxLista  = new Ajax.Request(sUrlRPC,
                                             {method: "post",
                                              parameters:'json='+Object.toJSON(oParametros),
                                              onComplete: js_retornoAtualizacao
                                             });
}



function js_retornoAtualizacao(oAjax) {

//alert('voltei');

js_removeObj('msgBox');

    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 1) {
      
      alert("Processamento Efetuado");
      lista_Setores();
      
    } else {
    
      alert(oRetorno.message.urlDecode());
    }  

}

</script>