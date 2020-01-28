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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_divida_classe.php");
include("classes/db_pardiv_classe.php");
include("classes/db_certidlivro_classe.php");
include("classes/db_certidlivrofolha_classe.php");
include("dbforms/db_funcoes.php");

$oGet                 = db_utils::postMemory($_GET);
$clrotulo             = new rotulocampo;
$cldivida             = new cl_divida;
$oDaoCertidLivro      = new cl_certidlivro;
$oDaoCertidLivroFolha = new cl_certidlivrofolha;
$oDaoParDiv           = new cl_pardiv;
$clrotulo->label("v01_livro");
$clrotulo->label("v01_dtoper");
$clrotulo->label("v14_certid");
$db_opcao = 1;
db_postmemory($HTTP_POST_VARS);
$v13_dtemis_dia = "";
$v13_dtemis_mes = "";
$v13_dtemis_ano = "";
$livro          = "";
$pagina         = "";

/**
 * Consultamos os livros existentes da instituição
 */
$sSqlLivros = "SELECT distinct v25_numero from certidlivro where v25_instit = ".db_getsession("DB_instit");
$rsLIvros   = $oDaoCertidLivro->sql_record($sSqlLivros);
$aLivros    = array();
$aLivros[0] = "Selecione";
for ($i = 0; $i < $oDaoCertidLivro->numrows; $i++) {
  
  $oLivro = db_utils::fieldsMemory($rsLIvros, $i);
  $aLivros[$oLivro->v25_numero] = $oLivro->v25_numero;
  
}
$db_opcaofolhalivro = 1;
if (isset($oGet->chave_pesquisa)) {
   
  $sSqlCertidao = $oDaoCertidLivroFolha->sql_query_certidao($chave_pesquisa);
  $rsCertidao   = $oDaoCertidLivroFolha->sql_record($sSqlCertidao);
  if ($oDaoCertidLivroFolha->numrows > 0) {

    $oDadosCertidao = db_utils::fieldsMemory($rsCertidao, 0);
    $v13_certid     = $oDadosCertidao->v13_certid;
    $aDataEmissao   = explode("-", $oDadosCertidao->v13_dtemis);
    $v13_dtemis_dia = $aDataEmissao[2];
    $v13_dtemis_mes = $aDataEmissao[1];
    $v13_dtemis_ano = $aDataEmissao[0];
    $v26_sequencial = $oDadosCertidao->v26_sequencial;
    $livro          = $oDadosCertidao->v25_numero;
    $pagina         = $oDadosCertidao->v26_numerofolha;
   
    /**
     * Verificamos se a certidao está em uma inicial.
     */
    $oDaoInicialCert = db_utils::getDao("inicialcert");
    $sSqlInicialCert = $oDaoInicialCert->sql_query_file(null,$oDadosCertidao->v13_certid);
    $rsInicial       = $oDaoInicialCert->sql_record($sSqlInicialCert);
    if ($v26_sequencial != "" && $oDaoInicialCert->numrows > 0) {
      $db_opcaofolhalivro = 3;
    }
  }
}

?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
   db_app::load("scripts.js, estilos.css, prototype.js, strings.js");
  ?>
 </head>
 <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" 
       onLoad="if(document.form1) document.form1.elements[1].focus()" >

  <form class="container" name='form1' method="post">
    <fieldset>
      <legend> Alterar CDA </legend>         
      <table class="form-container">
        <tr>
          <td>
            <?
              db_ancora(@$Lv14_certid,"js_pesquisaparcel(true)",3)
            ?>
          </td>
          <td>        
            <?
              db_input('v13_certid',10,$Iv14_certid,true,'text',3,
                       "onchange='js_pesquisaparcel(false);'");
              db_input('v26_sequencial',10,$Iv14_certid,true,'hidden',3);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            Data Emissão: 
          </td>
          <td>
            <?=db_inputdata('v13_dtemis',$v13_dtemis_dia,$v13_dtemis_mes,$v13_dtemis_ano,true,'text',4)?>
          </td>            
        </tr>
        <tr>
          <td>
            Livro:
          </td> 
          <td>
            <?
              db_select('livro', $aLivros, true, $db_opcaofolhalivro, "");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            Folha:
          </td> 
          <td>
            <?
              db_input("pagina",10,4,true,"text", $db_opcaofolhalivro);
            ?>
          </td>
        </tr>
      </table>          
    </fieldset>      
    <input type='button' value='Alterar CDA' id='processar'>
    <input type='button' value='Pesquisar CDA' id='pesquisar' onclick='js_pesquisaparcel(true)'>
  </form>

  <? 
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
 </body>
</html>
<script>

 sUrlRPC        = 'div4_certidlivro.RPC.php';
 sLookUp        = 'func_alteracda.php';
 iUltimaPagina  = 0;
 iLivroOriginal = '<?=$livro ?>';
 iFolhaOriginal = '<?=$pagina?>';
 function js_pesquisaparcel(mostra){
     
     if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe',sLookUp+'?funcao_js=parent.js_mostratermo|0','Pesquisa',true);      
     }else{
       js_OpenJanelaIframe('top.corpo','db_iframe',sLookUp+'?pesquisa_chave='+document.form1.v13_certid.value+'&funcao_js=parent.js_mostratermo','Pesquisa',false);       
     }
}
function js_mostratermo(chave) {
  
   location.href='<?=$_SERVER["PHP_SELF"]?>?chave_pesquisa='+chave;
}

function getProximaPagina() {

  if ($F('livro') == 0) {
    
    $('pagina').value = 0;
    return ;
    
  }
  
  if ($F('livro') == iLivroOriginal) {
  
   $('pagina').value = iFolhaOriginal;
   return false;
   
  }
  var oParam          = new Object();
      oParam.exec     = "getProximaPagina";
      oParam.livro    = $F('livro');
      
      var oAjax = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(oParam), 
                          onComplete: js_retornoGetProximaPagina
                          }
                        );
      
}

function js_retornoGetProximaPagina(oAjax) {

   var oRetorno = eval("("+oAjax.responseText+")");
   if (oRetorno.status == 1) {
     $('pagina').value = oRetorno.proximapagina;
   }
}

function js_alterarCda() {
   
   
   if ($F('v13_dtemis') == '') {
      
     alert('Informe a data da CDA!');
     return false;
     
   }
   if (($F('pagina') != '0' || $F('pagina') == "") && $F('livro') == 0) {
   
     alert('Escolha um livro para incluir a CDA!');
     return false;
     
   }
   
   if ($F('livro') != "0" && ($F('pagina') == 0 ||$F('pagina') == "")) {
   
     alert('Escolha uma folha para incluir a CDA!');
     return false;
     
   }
   
   if ($F('v26_sequencial') != "" && ($F('livro') == '0' || ($F('pagina') == ""|| $F('pagina') == "0"))) {
      
     var sMsg  = 'Informando um livro ou uma página inválida, a cda sera retirada do livro em que ela se encontra.\n'; 
         sMsg += 'Confirma a alteração?'; 
     if (!confirm(sMsg)) {
         return false;
     } 
     
     $('livro').value  = 0;
     $('pagina').value = 0;
     
   }
   
   var oParam        = new Object();
   oParam.exec       = "alterarCDA";
   oParam.livro      = $F('livro');
   oParam.pagina     = $F('pagina');
   oParam.v13_certid = $F('v13_certid');
   oParam.v13_dtemis = $F('v13_dtemis');
   
   js_divCarregando('Aguarde, Alterando informações da CDA', 'msgbox');
   
   var oAjax = new Ajax.Request(
                        sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+Object.toJSON(oParam), 
                          onComplete: js_retornoAlterarCDA
                          }
                        );
   
}

function js_retornoAlterarCDA(oAjax) {
  
   js_removeObj('msgbox');
   var oRetorno = eval("("+oAjax.responseText+")");
   if (oRetorno.status == 1) {
   
      alert('CDA alterada com sucesso');
      location.href='div4_alteracda001.php';
      
   } else {
     alert(oRetorno.message.urlDecode());
   }
}
$('livro').observe("change",getProximaPagina);
$('processar').observe("click",js_alterarCda);

</script>
<script>

$("v13_certid").addClassName("field-size2");
$("v13_dtemis").addClassName("field-size2");
$("pagina").addClassName("field-size2");

</script>