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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_ppadotacao_classe.php");
require_once("dbforms/db_funcoes.php");
$clppadotacao = new cl_ppadotacao();
$oPost        = db_utils::postMemory($_POST);
$db_opcao     = 1;
$db_botao = true;
$clrotulo = new rotulocampo;
$clrotulo->label("o01_descricao");
$clrotulo->label("o01_anoinicio");
$clrotulo->label("o01_anofinal");
$clrotulo->label("o01_descricao");
$clrotulo->label("o119_sequencial");
$clrotulo->label("o119_versao");
$clrotulo->label("o05_ppaversao");
$clrotulo->label("o01_sequencial");
$clrotulo->label("o01_descricao");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("ppaUserInterface.js");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table>
    <tr height="25px">
      <td>&nbsp;</td>
    </tr>
  </table>
  <form name='form1'>
    <center>
     <table>
       <tr>
         <td>
           <fieldset>
             <legend><b>Relatórios de Integração</b></legend>
             <table>
               <tr>
                 <td nowrap title="<?=@$To05_ppalei?>">
                   <?
                   db_ancora("<b>Lei do PPA</b>","js_pesquisao05_ppalei(true);",$db_opcao);
                   ?>
                 </td>
                 <td nowrap>
                   <?
                   db_input('o05_ppalei',10,$Io01_sequencial,true,'text',$db_opcao," onchange='js_pesquisao05_ppalei(false);'")
                   ?>
                   <?
                   db_input('o01_descricao',40,$Io01_descricao,true,'text',3,'');
                   db_input('codrel',40,'',true,'hidden',3,'');
                   ?>
                 </td>
               </tr>
               <tr>
                 <td nowrap title="<?=@$To05_ppaversao?>">
                   <b>Perspectiva:</b>
                 </td>
                 <td id='verppa'>
                 </td>
              </tr>
            </table>
          </fieldset>
         </td>
       </tr>
       <tr>
         <td colspan="2" align="center">
           <input type="button" value="Visualizar" onclick="js_visualizarRelatorio()">
         </td>
       </tr>
     </table>
    </center>
  </form>
</body>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
  function js_pesquisao05_ppalei(mostra){
    if(mostra==true){
      js_OpenJanelaIframe('',
                          'db_iframe_ppalei',
                          'func_ppalei.php?funcao_js=parent.js_mostrappalei1|o01_sequencial|o01_descricao',
                          'Pesquisa de Leis para o PPA',
                          true);
    }else{
       if(document.form1.o05_ppalei.value != ''){
          js_OpenJanelaIframe('',
                              'db_iframe_ppalei',
                              'func_ppalei.php?pesquisa_chave='
                              +document.form1.o05_ppalei.value+'&funcao_js=parent.js_mostrappalei',
                              'Leis PPA',
                              false);
       }else{
         document.form1.o01_descricao.value = '';
       }
    }
  }

  function js_mostrappalei(chave, erro) {

    document.form1.o01_descricao.value = chave;
    if(erro==true){
      document.form1.o05_ppalei.focus();
      document.form1.o05_ppalei.value = '';
      js_limpaComboBoxPerspectivaPPA();
      } else {
        js_getVersoesPPA($F('o05_ppalei'), 0);
      }
  }

  function js_mostrappalei1(chave1,chave2){

    document.form1.o05_ppalei.value = chave1;
    document.form1.o01_descricao.value = chave2;
      js_getVersoesPPA(chave1, 0);
    db_iframe_ppalei.hide();

  }

  function js_visualizarRelatorio() {

     if ($F('o05_ppaversao') == 0) {

       alert('Selecione uma perspectiva!');
       return false;

     }

     var sUrl = "orc2_ppaintegracao002.php?o05_ppaversao="+$F('o05_ppaversao');
     jan = window.open(sUrl,'',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jan.moveTo(0,0);
  }
  js_drawSelectVersaoPPA($('verppa'));
</script>