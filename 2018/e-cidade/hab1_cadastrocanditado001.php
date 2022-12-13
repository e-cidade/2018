<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_habitgrupoprograma_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("z01_nome");
$oRotuloCampos->label("z01_numcgm");
$db_opcao   = 1;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
                 dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,
                 datagrid.widget.js");
   db_app::load("estilos.css,grid.style.css");
   ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <form method="post" name='form1'>
    <center>
       <table>
         <tr>
           <td>
             <fieldset>
               <table>
                 <tr>
                   <td>
                     <?db_ancora($Lz01_nome, "js_pesquisacgm(true)", 1)?>
                   </td>
                   <td>
                     <?
                      db_input("z01_numcgm", 10, $Iz01_numcgm, true, "text", 1, "onchange='js_pesquisacgm(false)'");
                      db_input("z01_nome", 40, $Iz01_nome, true, "text", 3);
                     ?>
                     <input type='button' value='Novo' onclick="js_novoCgm()"> 
                     <input type='button' value='alterar' onclick="js_alterarCgm($F('z01_numcgm'))"> 
                   </td>
                 </tr>
               </table>
             </fieldset>
           </td>
         </tr>
       </table>
    </center>
    </form>
  </body>
</html>  
<script>

function js_pesquisacgm(mostra) {
  if (mostra == true) {
     js_OpenJanelaIframe('', 
                         'db_iframe_cgm', 
                         'func_nome.php?funcao_js=parent.js_mostracgm1|z01_nome|z01_numcgm&filtro=1',
                         'Pesquisar CGM',
                         true,'0');
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('',
                            'db_iframe_acordogrupo',
                            'func_nome.php?pesquisa_chave='+$F('z01_numcgm')+
                            '&funcao_js=parent.js_mostracgm&filtro=1',
                            'Pesquisa',
                            false,
                            
                            
                            '0');
     }else{
       document.form1.z01_numcgm.value = ''; 
     }
  }
}

function js_mostracgm(erro, chave){
  document.form1.z01_nome.value = chave; 
  if(erro == true) { 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}

function js_mostracgm1(chave1, chave2) {

  $('z01_numcgm').value = chave2;
  $('z01_nome').value  = chave1;
  db_iframe_cgm.hide();
}

function setDados(oCandidato) {

  $('z01_numcgm').value = oCandidato.iNumCgm;
  $('z01_nome').value  = oCandidato.sNome.urlDecode();
   
}

function js_novoCgm() {
  js_OpenJanelaIframe('', 
                      'db_iframe_novocgm', 
                      'prot1_cadgeralmunic001.php?lMenu=false&lFisico=true&funcaoRetorno=parent.CurrentWindow.corpo.iframe_candidato.teste',
                      'Novo CGM',
                         true,'0');
}
function js_alterarCgm(iCgm) {

  if (iCgm != "") {
  js_OpenJanelaIframe('', 
                      'db_iframe_novocgm', 
                      'prot1_cadgeralmunic002.php?chavepesquisa='+iCgm+
                      '&lMenu=false&lCpf=true&funcaoRetorno=parent.CurrentWindow.corpo.iframe_candidato.teste',
                      'Novo CGM',
                         true,'0');
 }
}

function teste(iCgm) {
  
  db_iframe_novocgm.hide();
  $('z01_numcgm').value = iCgm;
  js_pesquisacgm(false); 
}
</script>
