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
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");
$oGet  = db_utils::postMemory($_GET);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<?php
  db_app::load("estilos.css");
  db_app::load("scripts.js");
  db_app::load("strings.js");
  
  db_app::load("prototype.js"); 
  
  db_app::load("datagrid.widget.js");
  db_app::load("dbtextField.widget.js");
  db_app::load("dbtextFieldData.widget.js");
  db_app::load("dbcomboBox.widget.js");
  db_app::load("grid.style.css");
  db_app::load("classes/DBViewCadastroUnica.js");
?>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">


<BR><BR>
<div style="width:600; margin-left:50%; position: absolute; left: -300px; " id="base">
  <fieldset>
    <legend><b>Origem Primária:</b></legend>
    
            <table align="center" border="0" cellspacing="0" cellpadding="2">
              <?php if($oGet->tipo == "individual") {?>
               <tr>
                  <td nowrap="nowrap" width="150">
                    <b><? db_ancora ( "CGM :", "js_pesquisacgm(true)", 1 )?></b>
                  </td>
                  <td>
                    <? 
                      db_input ( "z01_numcgm", 10, "", true, "text", 1, " onchange='js_pesquisacgm(false);' " );
                      db_input ( "z01_nome", 40, "", true, "text", 3 );
                    ?>
                    
                </td>
               </tr>  
               
              <tr> 
                <td>     
                  <b><? db_ancora("Matricula :",' js_matri(true); ',1); ?></b>
                </td>
                <td> 
                  <?
                    db_input('j01_matric', 10, 0, true, 'text', 1, "onchange='js_matri(false)'");
                    //db_input('z01_nome'  , 40, 0, true, 'text', 3, "");
                  ?>
                </td>
              </tr>
              
              <tr> 
                <td>     
                 <b>
                   <?db_ancora("Inscrição :",' js_inscr(true); ',1); ?>
                 </b>
                </td>
                <td> 
                 <?
                  db_input('q02_inscr',10,"",true,'text',1,"onchange='js_inscr(false)'");
                  //db_input('z01_nome',40,0,true,'text',3,"");
                 ?>
                </td>
              </tr> 
          <?php } else {
            echo "<TR>";
            echo "  <TD><B>Tipo de Pesquisa: </B></TD>";
            echo "  <TD>";
            db_select("cboOrigem", array("0" => "Selecione", "C"=>"CGM","M"=>"Matrícula","I"=>"Inscrição"),true, 1, "onChange =\"js_montaForm(this.value);\"");
            echo "  </TD>";
            echo "</TR>";
          }
          ?>
          </table>      
    
  </fieldset>
    <center>


    <?php
    if($oGet->tipo == "individual") {
      echo "<input type='button' value='Pesquisar' onclick='js_montaForm();'>";
    }
    ?>
    </center>
    <div id="ctnContainer"></div>
  <br>

  
</div>



<?
db_menu(db_getsession("DB_id_usuario"),
        db_getsession("DB_modulo"),
        db_getsession("DB_anousu"),
        db_getsession("DB_instit")
       );

?>
</body>
</html>
<script>

var me = this;

me.sTipoPesquisa  = null;
me.sChavePesquisa = null;
me.sDataUsu       = '<?= date("Y-m-d",db_getsession("DB_datausu"));?>';
/**
 * Monta formulário
 */
function js_montaForm(iTipoPesquisa) {


  $("ctnContainer").innerHTML = "";
  if (sTipoPesquisa != 0) {
    
    me.oCotaUnica = new DBViewCadastroUnica("me.oCotaUnica");
    
    if(iTipoPesquisa == null) {
      me.oCotaUnica.setTipoPesquisa (me.sTipoPesquisa);
    	me.oCotaUnica.setChavePesquisa(me.sChavePesquisa);
    } else {
      me.oCotaUnica.setTipoPesquisa (iTipoPesquisa);    
    }
    me.oCotaUnica.setDataUsu(me.sDataUsu);
  	me.oCotaUnica.show($("ctnContainer"));
    var oBotaoPesquisa   = document.createElement("input");
    oBotaoPesquisa.id    = "pesquisar";
    oBotaoPesquisa.value = "Processar";
    oBotaoPesquisa.type  = "button";
    oBotaoPesquisa.setAttribute("onClick", "js_enviaDados();");
    var oCenter          = document.createElement("center");
    
    oCenter.appendChild(oBotaoPesquisa);
    $("ctnContainer").appendChild(oCenter);
  } 

}
function js_enviaDados() {
  
  var oValidacao = me.oCotaUnica.oAcoes.validaCampos();
  
  if(oValidacao.iStatus == 2){
    alert(oValidacao.sMensagem);
  } else {
    
    var oParam            = new Object();
    oParam.exec           = 'processaDados';
    oParam.oDados         = me.oCotaUnica.getDados();
    
    var oExec            = new Object();
    oExec.method         = 'post';
    oExec.parameters     = 'json=' + Object.toJSON(oParam);
    oExec.asynchronous   = false;
    oExec.onComplete     = function(oAjax) {
      
      js_removeObj('msgBox');
      var oRetorno = eval("("+oAjax.responseText+")");

      if (oRetorno.status == "2") {

        alert(oRetorno.msg.urlDecode());
        return false;

      } else {
        alert(oRetorno.msg.urlDecode().replace(/\\n/g,"\n"));
        window.location = window.location;
      }
    };
    js_divCarregando('Processando Dados ...', 'msgBox');
    this.oAjax       = new Ajax.Request("arr4_recibounicaGeracao.RPC.php", oExec);
  }
}
     
// PESQUISA DE INSCRICAO

function js_inscr(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframeInscr','func_issbase.php?funcao_js=parent.js_mostraInscr|q02_inscr|z01_nome|q02_dtbaix','Pesquisa Inscriçao',true);
  }else{
    js_OpenJanelaIframe('','db_iframeInscr','func_issbase.php?pesquisa_chave='+$F("q02_inscr")+'&funcao_js=parent.js_mostraInscr1','Pesquisa Inscriçao',false);
  }
}
function js_mostraInscr(chave1,chave2,baixa){
  if (baixa!=""){
    db_iframeInscr.hide();
    alert("Inscrição já  Baixada");
  }else{
  
    $("q02_inscr").value  = chave1;
    $("z01_nome").value   = chave2;
    $("j01_matric").value = '';
    $("z01_numcgm").value = '';

    me.sTipoPesquisa  = "I";
    me.sChavePesquisa = chave1;
    db_iframeInscr.hide();
  }
}
function js_mostraInscr1(chave,erro,baixa){

  if (erro==true) {
    
    $("q02_inscr").focus();
    $("z01_nome").value  = chave;
    $("q02_inscr").value = '';
  }else if (baixa!="") {
    
    alert("Inscrição já  Baixada");
    $("q02_inscr").value = "" ;
  } else {
    $("z01_nome").value   = chave;
    $("j01_matric").value = '';
    $("z01_numcgm").value = '';    
    me.sTipoPesquisa  = "I";
    me.sChavePesquisa = $("q02_inscr").value;
  }
}



// PESQUISA MATRICULA

function js_matri(mostra){

  var matri = $("j01_matric").value;
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframeMatric','func_iptubase.php?funcao_js=parent.js_mostraMatric|0|2','Pesquisa Matricula',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostraMatric1','Pesquisa Matricula',false);
  }
}

function js_mostraMatric(chave1,chave2){

  $("j01_matric").value = chave1;
  $('z01_nome').value   = chave2;
  $("q02_inscr").value  = "" ; 
  $("z01_numcgm").value = '';
  me.sTipoPesquisa  = "M";
  me.sChavePesquisa = chave1;
  db_iframeMatric.hide();
}

function js_mostraMatric1(chave,erro){

  $("z01_nome").value   = chave;
  $("q02_inscr").value  = "" ; 
  $("z01_numcgm").value = '';
  
  me.sTipoPesquisa  = "M";
  me.sChavePesquisa = $("j01_matric").value;
     
  if(erro == true){ 
    $("j01_matric").focus(); 
    $("j01_matric").value = ''; 
  }
}

// PESQUISA CGM

function js_pesquisacgm(lMostra){

    if (lMostra) {
       js_OpenJanelaIframe('', 
                           'db_iframe_cgm', 
                           'func_nome.php?funcao_js=parent.js_mostracgm1|z01_nome|z01_numcgm',
                           'Pesquisar CGM',
                           true);
    } else {
      if($('z01_numcgm').value != ''){ 
         js_OpenJanelaIframe('',
                             'db_iframe_acordogrupo',
                             'func_nome.php?pesquisa_chave='+$F('z01_numcgm')+
                             '&funcao_js=parent.js_mostracgm',
                             'Pesquisa',
                             false);
      } else {
        $("z01_numcgm").value = ''; 
      }
    }
  }

  function js_mostracgm(erro, chave){

    if(erro == true) { 
    
      $('z01_numcgm').focus(); 
      $("z01_numcgm").value = '';
      $('z01_nome').value = chave; 
    } else {
    
      $('z01_nome').value = chave;
      $("j01_matric").value = '';
      $("q02_inscr").value = "" ;
      me.sTipoPesquisa  = "C";
      me.sChavePesquisa = $("z01_numcgm").value;
    }
  }

  function js_mostracgm1(chave1, chave2) {
    
    $('z01_nome').value   = chave1;
    $('z01_numcgm').value = chave2;
    $("j01_matric").value = '';
    $("q02_inscr").value = "" ;    
    db_iframe_cgm.hide();
    me.sTipoPesquisa  = "C";
    me.sChavePesquisa = chave2;
  }     
     
     
</script>