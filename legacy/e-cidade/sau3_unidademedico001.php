<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_unidademedicos_classe.php");
$cl_unidademedicos = new cl_unidademedicos;
$clrotulo = new rotulocampo;
$clrotulo->label("sd02_i_codigo");
$clrotulo->label("sd02_c_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" height='18' border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <table>
     <tr>
      <td nowrap title="Unidade">
       <?db_ancora("Unidade","js_pesquisasd02_i_unidade(true);",@$db_opcao);?>
       <?db_input('unidade',10,@$Isd08_i_unidade,true,'text',@$db_opcao," onchange='js_pesquisasd02_i_unidade(false);'")?>
       <?db_input('sd02_c_nome',40,@$Isd02_c_nome,true,'text',3,'')?>
      </td>
     </tr>
     <tr>
      <td colspan='6' align='center' >
       <input name='Processar' type='button' value='Processar' onclick="EnviaForm()">
      </td>
     </tr>
    </table>
    </form>
    <?
     if(isset($Processar)){
      $sql = "select sd03_i_id, z01_nome from unidademedicos
              inner join medicos on medicos.sd03_i_id = unidademedicos.sd04_i_medico
              inner join cgm on cgm.z01_numcgm = sd03_i_codigo
              where sd04_i_unidade = $unidade";
      db_lovrot($sql,15,"()");
     }else{
      echo "<center><br><br>Informe a Unidade e clique em <b>Processar</b>...</center>";
     }
    ?>
    <script>
    function js_pesquisasd02_i_unidade(mostra){
     if(mostra==true){
      js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?funcao_js=parent.js_mostraunidades1|sd02_i_codigo|sd02_c_nome','Pesquisa',true);
     }else{
     if(document.form1.unidade.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_unidades','func_unidades.php?pesquisa_chave='+document.form1.unidade.value+'&funcao_js=parent.js_mostraunidades','Pesquisa',false);
     }else{
       document.form1.sd02_c_nome.value = '';
     }
     }
    }
    function js_mostraunidades(chave,erro){
    document.form1.sd02_c_nome.value = chave;
    if(erro==true){
     document.form1.unidade.focus();
     document.form1.unidade.value = '';
    }
    }
    function js_mostraunidades1(chave1,chave2){
    document.form1.unidade.value = chave1;
    document.form1.sd02_c_nome.value = chave2;
    db_iframe_unidades.hide();
    }

     function EnviaForm(){
      if(document.form1.unidade.value==""){
       alert("Preencha a Unidade");
       document.form1.unidade.focus();
       return false;
      }
      location.href="sau3_unidademedico001.php?Processar&unidade="+document.form1.unidade.value;
     }
    </script>
  </td>
 </tr>
</table>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>