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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once('libs/db_utils.php');
db_postmemory($HTTP_POST_VARS);

if (!isset($lPopup)) {
  $db_opcao = 1;
} else {
  $db_opcao = 3;
}
$db_botao = true;
$sSql     = "";

$oDaoProntprocedcid = db_utils::getdao('prontprocedcid');
$oRotulo            = new rotulocampo;
$oDaoProntprocedcid->rotulo->label();
$oRotulo->label("z01_i_cgsund");
$oRotulo->label("z01_v_nome");


if (isset($z01_i_cgsund)) {
  
  $sCampos = "sd29_d_data as dl_atendimento, sd70_c_cid, sd70_c_nome";
  $sWhere  = " sd24_i_numcgs = $z01_i_cgsund ";
  $sSql    = $oDaoProntprocedcid->sql_query("", $sCampos, "", $sWhere);
  
}

?>
<html>
  <head>
     <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
     <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
     <meta http-equiv="Expires" CONTENT="0">
     <?
      $sLib  = "scripts.js,prototype.js,datagrid.widget.js,strings.js,grid.style.css,";
      $sLib .= "estilos.css,/widgets/dbautocomplete.widget.js,webseller.js";
      db_app::load($sLib);
     ?>
     <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
     <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <form name="form1" method="post" action="">   
   
   <center>
   <?
   if (!isset($lPopup)) { 
   ?>
     <br><br><br><br>
     <fieldset style="width:500px;" ><legend><b>Paciente:</b></legend>
       <table>
         <tr>
           <td nowrap title="$Tjs_pesquisaz01_i_cgsund">
             <?
             db_ancora("$Lz01_i_cgsund", "js_pesquisaz01_i_cgsund(true);", $db_opcao);
             ?>
           </td>
           <td>
             <?
             db_input('z01_i_cgsund', 10, $Iz01_i_cgsund, true, 'text', $db_opcao, 
                      "onchange='js_pesquisaz01_i_cgsund(false);'");
             ?> 
           </td>
           <td>
             <?
             db_input('z01_v_nome',40,$Iz01_v_nome,true,'text',3,$db_opcao,"");
             ?>
           </td>
         </tr>
       </table>
       <?
       if (!isset($lPopup)) {
       ?>
         <input type="submit" name="pesquisar" value="Pesquisar">
       <?
       } else {
       ?>
         <input type="button" name="fechar" value="Fechar" onclick="js_fechar()">
       <?
       }
       ?>
     </fieldset>
     <? 
     } 
     ?>
     <br>
   
   <fieldset style="width:600px;" ><legend><b>CID's</b></legend>
     <?      
     if ($sSql != "") {
     	
     	 global $cor1;
       global $cor2;
       $cor1 = "#FFFAF0";
       $cor2 = "#FFFAF0";
       if (!isset($iLinhas)) {
         $iLinhas = 5;
       }
       db_lovrot($sSql, $iLinhas, "()", "", "");
       
     } else {
       echo "Selecione um paciente!";
     }
    ?>         
   </fieldset>
   
   </center>
  </form>
  <?
    if (!isset($lPopup)) {
      
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),
              db_getsession("DB_instit"));
              
    }
  ?>  
  </body>
</html>
<script>
  function js_valida() {
    if ($F('z01_i_cgsund') == '') {
        
      alert('CGS não informado!');
      return false;
      
    }
    return true;
  } 
  function js_pesquisaz01_i_cgsund(mostra) {
    
    if (mostra == true) {      
      js_OpenJanelaIframe('',
                          'db_iframe_cgs',
                          'func_cgs_und.php?funcao_js=parent.js_agendamento1|z01_i_cgsund|z01_v_nome',
                          'Pesquisa Pacientes',
                          true);
    } else {
        
      if (document.form1.z01_i_cgsund.value != '') {
        js_OpenJanelaIframe('','db_iframe_cgs',
                            'func_cgs_und.php?pesquisa_chave='+$F('z01_i_cgsund')+'&funcao_js=parent.js_agendamento',
                            'Pesquisa Pacientes',
                            false);   
      } else {
          document.form1.z01_v_nome.value = '';
      }
      
    }
     
  }
  function js_agendamento(chave, erro) {
     
    $('z01_v_nome').value = chave;
    if (erro == true) {
       
      $('z01_i_cgsund').focus();
      $('z01_i_cgsund').value = '';
        
    }
     
  }
  function js_agendamento1(chave1, chave2) {
     
    $('z01_i_cgsund').value = chave1;
    $('z01_v_nome').value   = chave2;
    db_iframe_cgs.hide();
     
  }
</script>