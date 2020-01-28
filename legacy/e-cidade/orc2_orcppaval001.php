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
include("libs/db_liborcamento.php");
include("classes/db_orcppalei_classe.php");


$clorcppalei = new cl_orcppalei;

$clrotulo = new rotulocampo;
$clrotulo->label("o21_codleippa");
$clrotulo->label("o21_descr");



?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
variavel = 1;
function js_emite(){
  if(document.form1.o21_codleippa.value==''){
    alert("Usuário: \n\nInforme a lei a ser impressa!\n\nAdministrador:");
  }else{
    exercicio =0;
    if (document.form1.exercicio.value != 0 ){
        exercicio = document.form1.exercicio.value;
    }      
    qry = 'nivel='+document.form1.nivel.value;
    qry+= '&lei='+document.form1.o21_codleippa.value;
    qry+= '&exercicio='+exercicio;
    qry+= '&impzero='+document.form1.impzero.value;

    jan = window.open('orc2_orcppaval002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table  align="center" border="0">
<form name="form1" method="post" action="">
  <tr>
     <td >&nbsp;</td>
     <td >&nbsp;</td>
  </tr>
  <tr>
    <td nowrap title="<?=@$To21_codleippa?>" align='right'>
      <?
      db_ancora(@$Lo21_codleippa,"js_pesquisao21_codleippa(true);",1);
      ?>
    </td>
    <td align='left' colspan='1'> 
      <?
      db_input('o21_codleippa',8,$Io21_codleippa,true,'text',1," onchange='js_pesquisao21_codleippa(false);'")
      ?>
      <?
      db_input('o21_descr',40,$Io21_descr,true,'text',3,'')
      ?>
    </td>
  </tr>
  <tr>
    <td align="right" ><strong>Nível :</strong></td>
    <td>
      <?
       $xy = array('1'=>'Órgão','2'=>'Unidade','3'=>'Função','4'=>'Subfunção','5'=>'Programa','6'=>'Ação','8'=>'Recurso');
       db_select('nivel',$xy,true,2,"");
      ?>
    </td>
  </tr>


    <tr>
    <td align="right"><strong>Anos :<strong></td>
    <td>
    <?
    $result_anosescolha = $clorcppalei->sql_record($clorcppalei->sql_query_file(@$o21_codleippa,"o21_anoini,o21_anofim"));
    $arr_indexdescr = Array();

    echo "<select name=exercicio>";
    echo "<option value=0>Todos</option>";
    if ($clorcppalei->numrows > 0){
      db_fieldsmemory($result_anosescolha,0);
      for($i=$o21_anoini;$i<=$o21_anofim;$i++){
	  $arr_indexdescr[$i] = $i;
          echo "<option value=$i> $i</option> "   ;      
      }
    }
      echo "</select>";
    ?>  
    </td>
    </tr>
  <tr>
    <td><b>Imprimir Valores Zerados:</b></td>
    <td>
    <?
       $x = array("n"=>"NÃO","s"=>"SIM");

       db_select("impzero",$x,true,4);
    ?>
    </td>
 </tr>
  <tr>
     <td height=150px> &nbsp;  </td>
  </tr>
  <tr>
    <td colspan="2" align = "center"> 
      <input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
      <input  name="orgaos" id="orgaos" type="hidden" value="" >
      <input  name="vernivel" id="vernivel" type="hidden" value="" >
    </td>
  </tr>
</form>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_pesquisao21_codleippa(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcppalei','func_orcppalei.php?funcao_js=parent.js_mostraorcppalei1|o21_codleippa|o21_descr','Pesquisa',true);
  }else{
     if(document.form1.o21_codleippa.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcppalei','func_orcppalei.php?pesquisa_chave='+document.form1.o21_codleippa.value+'&funcao_js=parent.js_mostraorcppalei','Pesquisa',false);
     }else{
       document.form1.o21_descr.value = ''; 
     }
  }
}
function js_mostraorcppalei(chave,erro){
  document.form1.o21_descr.value = chave; 
  if(erro==true){ 
    document.form1.o21_codleippa.focus(); 
    document.form1.o21_codleippa.value = ''; 
  }
}
function js_mostraorcppalei1(chave1,chave2){
  document.form1.o21_codleippa.value = chave1;
  document.form1.o21_descr.value = chave2;
  db_iframe_orcppalei.hide()
}
</script>
</html>