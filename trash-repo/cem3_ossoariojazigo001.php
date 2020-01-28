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
include("classes/db_ossoariojazigo_classe.php");
include("classes/db_propricemit_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clossoariojazigo = new cl_ossoariojazigo;
$clpropricemit = new cl_propricemit;
$clrotulo = new rotulocampo;

$clossoariojazigo->rotulo->label();
$clpropricemit->rotulo->label();

$clrotulo->label("z01_nome");



$db_opcao = 3;
$db_botao = false;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="490" width="100%" align="left" valign="top" bgcolor="#CCCCCC">
    <center><br><Br>
     <?
     if(!isset($pesquisar)){
    ?>
      <table>
      <form name="form1">
       <tr>
        <td nowrap title="<?=@$Tcm25_i_codigo?>">
         <?db_ancora(@$Lcm25_i_codigo,"js_pesquisacm25_i_codigo(true);",1);?>
        </td>
        <td>
           <?
            db_input('cm25_i_codigo',10,$Icm25_i_codigo,true,'text',1," onchange='js_pesquisacm25_i_codigo(false);'");
           ?>
        </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tcm28_i_proprietario?>">
                 <?
            db_ancora(@$Lcm28_i_proprietario,"js_pesquisacm28_i_proprietario(true);",1);
            ?>
         </td>
         <td>
            <?
              db_input('cm28_i_proprietario',10,$Icm28_i_proprietario,true,'text',1," onchange='js_pesquisacm28_i_proprietario(false);'");
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
            ?>
         </td>
       </tr>

       
       <tr>
        <td><input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar"></td>
       </tr>
      </form>
      </table>
    <?
     }else{
        $db_opcao = 3;
        $result = $clossoariojazigo->sql_record( $clossoariojazigo->sql_query( "", "*", "", "cm25_i_codigo = $cm25_i_codigo" ) );
   
	if( $clossoariojazigo->numrows != 0 ){
            db_fieldsmemory($result,0);
            include("forms/db_frmpropricemit.php");

            if( $cm25_c_tipo == 'J' ){
                 echo "<script>";
                 echo " parent.document.formaba.a2.disabled=false; ";
                 echo " top.corpo.iframe_a2.location.href='cem3_proprijazigo001.php?cm28_i_codigo=$cm28_i_codigo&cm28_i_ossoariojazigo=$cm28_i_ossoariojazigo&cm28_i_proprietario=$cm28_i_proprietario&z01_nome=$z01_nome&chavepesquisa=$cm28_i_codigo';";
                 echo "</script>";
            }

            echo "<script>";
            echo " parent.document.formaba.a3.disabled=false; ";
            echo " top.corpo.iframe_a3.location.href='cem3_sepultados001.php?chavepesquisa=$cm25_i_codigo&tipo=$cm25_c_tipo';";
            echo "</script>";

            echo "<script>";
            echo " parent.document.formaba.a4.disabled=false; ";
            echo " top.corpo.iframe_a4.location.href='cem3_taxas001.php?chavepesquisa=$cm25_i_codigo';";
            echo "</script>";

        }
     }
    ?>
    </center>
     </td>
  </tr>
</table>
<script>



function js_pesquisacm25_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_ossoariojazigo','func_ossoariojazigo.php?funcao_js=parent.js_mostraossoariojazigo1|cm25_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.cm25_i_codigo.value != ''){
        js_OpenJanelaIframe('','db_iframe_ossoariojazigo','func_ossoariojazigo.php?pesquisa_chave='+document.form1.cm25_i_codigo.value+'&funcao_js=parent.js_mostraossoariojazigo','Pesquisa',false);
     }else{
       document.form1.cm25_i_codigo.value = '';
     }
  }
}
function js_mostraossoariojazigo(chave,erro){
  document.form1.cm25_i_codigo.value = chave;
  if(erro==true){
    document.form1.cm25_i_codigo.focus();
    document.form1.cm25_i_codigo.value = '';
  }
}
function js_mostraossoariojazigo1(chave1){
  document.form1.cm25_i_codigo.value = chave1;
  db_iframe_ossoariojazigo.hide();
}


function js_pesquisacm28_i_proprietario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_proprietario','func_propricemit.php?funcao_js=parent.js_mostraproprietario1|cm28_i_proprietario|z01_nome|cm28_i_ossoariojazigo','Pesquisa',true);
  }else{
     if(document.form1.cm28_i_proprietario.value != ''){
        js_OpenJanelaIframe('','db_iframe_proprietario','func_propricemit.php?pesquisa_chave='+document.form1.cm28_i_proprietario.value+'&funcao_js=parent.js_mostraproprietario','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostraproprietario(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.cm28_i_proprietario.focus();
    document.form1.cm28_i_proprietario.value = '';
  }
}
function js_mostraproprietario1(chave1,chave2,chave3){
  document.form1.cm28_i_proprietario.value = chave1;
  document.form1.z01_nome.value = chave2;
  document.form1.cm25_i_codigo.value = chave3;

  db_iframe_proprietario.hide();
}


</script>
</body>
</html>