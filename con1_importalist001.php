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
include("libs/db_sql.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_projmelhorias_classe.php");
include("classes/db_projmelhoriasresp_classe.php");
include("classes/db_projmelhoriasmatric_classe.php");
include("dbforms/db_funcoes.php");

$clprojmelhorias = new cl_projmelhorias;
$clprojmelhoriasmatric = new cl_projmelhoriasmatric;
$clprojmelhoriasresp = new cl_projmelhoriasresp;

$clrotulo = new rotulocampo;
$clrotulo->label("d40_codigo");

db_postmemory($HTTP_POST_VARS);

if (isset($incluir)){
  db_inicio_transacao();
  $sqlerro=false;
  $cod=$d40_codigo;
  $result=$clprojmelhorias->sql_record($clprojmelhorias->sql_query_file($cod));
  db_fieldsmemory($result,0);

  $clprojmelhorias->d40_data=$d40_data;
  $clprojmelhorias->d40_login=$d40_login;
  $clprojmelhorias->d40_codlog=$d40_codlog;
  $clprojmelhorias->d40_trecho=$d40_trecho;
  $clprojmelhorias->d40_profun=$d40_profun;
  $clprojmelhorias->incluir(null);
  $erro_msg = $clprojmelhorias->erro_msg;
  if($clprojmelhorias->erro_status=='0'){
    $sqlerro = true;
  } 
  $codigo=$clprojmelhorias->d40_codigo;
  
  $result_resp=$clprojmelhoriasresp->sql_record($clprojmelhoriasresp->sql_query_file($cod));
  if ($clprojmelhoriasresp->numrows!=0){
    db_fieldsmemory($result_resp,0); 
  }
  if(isset($d42_numcgm)&&$d42_numcgm!=""){
    $clprojmelhoriasresp->d42_numcgm=$d42_numcgm;
    $clprojmelhoriasresp->d42_codigo=$codigo;
    $clprojmelhoriasresp->incluir($codigo);
    if($clprojmelhoriasresp->erro_status=='0'){
      $erro_msg = $clprojmelhoriasresp->erro_msg;
      $sqlerro = true;
    } 
  }  
  $result_matric=$clprojmelhoriasmatric->sql_record($clprojmelhoriasmatric->sql_query_file($cod));
  $numrows=$clprojmelhoriasmatric->numrows;
  for ($y=0;$y<$numrows;$y++){
    db_fieldsmemory($result_matric,$y);
    $clprojmelhoriasmatric->d41_codigo = $codigo;
    $clprojmelhoriasmatric->d41_matric = $d41_matric;
    $clprojmelhoriasmatric->d41_testada= $d41_testada;
    $clprojmelhoriasmatric->d41_eixo   = $d41_eixo;
    $clprojmelhoriasmatric->d41_obs    = $d41_obs;
    $clprojmelhoriasmatric->d41_auto    = $d41_auto;
    if ($d41_pgtopref=='f'){
      $clprojmelhoriasmatric->d41_pgtopref ='0';
    }else{
      $clprojmelhoriasmatric->d41_pgtopref ='1';
    }
    $clprojmelhoriasmatric->incluir($codigo,$d41_matric);
    if($clprojmelhoriasmatric->erro_status=='0'){
      $erro_msg = $clprojmelhoriasmatric->erro_msg;
      $sqlerro = true;
      break;
    } 
  }
  db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
</script>  
<style>
.cabec {
text-align: center;
color: darkblue;
background-color:#aacccc;       
border-color: darkblue;
}
.corpo {
color: black;
background-color:#ccddcc;       
}
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<center>
<form name="form1" method="post" target="" action="con1_importalist001.php">
<table border='0'>
 <tr height="20px">
   <td ></td>
   <td ></td>
  </tr>
  <tr> 
    <td  align="left" nowrap title="<?=$Td40_codigo?>"><?db_ancora(@$Ld40_codigo,"js_pesquisa_lista(true);",1);?></td>
    <td align="left" nowrap>
      <? db_input("d40_codigo",6,$Id40_codigo,true,"text",4,"onchange='js_pesquisa_lista(false);'");
         ?></td>
  </tr>
  <tr>
  <td colspan=2>
    <input name="incluir" type="submit" value="Importar Lista" disabled >
    </td>
  </tr>
</table>
</form>
</center>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
<script>
function js_pesquisa_lista(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_lista','func_projmelhorias.php?funcao_js=parent.js_mostralista1|d40_codigo','Pesquisa',true);
  }else{
     if(document.form1.d40_codigo.value != ''){ 
        js_OpenJanelaIframe('','db_iframe_lista','func_projmelhorias.php?pesquisa_chave='+document.form1.d40_codigo.value+'&funcao_js=parent.js_mostralista','Pesquisa',false);
     }else{
     }
  }
}
function js_mostralista(chave,erro){
  if(erro==true){ 
    document.form1.incluir.disabled="true";
    document.form1.d40_codigo.focus(); 
    document.form1.d40_codigo.value = ''; 
  }else{
    document.form1.incluir.disabled="";
  }
}
function js_mostralista1(chave1){
  document.form1.d40_codigo.value = chave1;
  db_iframe_lista.hide();
  document.form1.incluir.disabled="";
}
</script>
<?
if (isset($incluir)){
    db_msgbox($erro_msg);
    if($sqlerro==true){
      echo "<script> document.form1.".$clprojmelhorias->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprojmelhorias->erro_campo.".focus();</script>";
    }else{ 
      echo"<script>top.corpo.location.href='con1_importalist001.php';</script>";
    }
}
?>
</body>
</html>