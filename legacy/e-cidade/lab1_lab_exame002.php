<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_lab_exame_classe.php"));
include(modification("classes/db_lab_exasinonima_classe.php"));
include(modification("classes/db_lab_sinonima_classe.php"));
include(modification("dbforms/db_funcoes.php"));
require(modification("libs/db_app.utils.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllab_exame = new cl_lab_exame;
$cllab_exasinonima = new cl_lab_exasinonima;
$cllab_sinonima = new cl_lab_sinonima;
$db_opcao = 22;
$db_botao = false;
if(isset($alterar)){

  $x = isset($chk_masc)?$chk_masc:0;
  $y = isset($chk_fem)?$chk_fem:0;
  $fator = $x+$y;  
  $cllab_exame->la08_i_sexo=$fator;
  $x = isset($chk_mapa)?$chk_mapa:0;
  $y = isset($chk_etiqueta1)?$chk_etiqueta1:0;
  $z = isset($chk_etiqueta2)?$chk_etiqueta2:0;
  $fator = $x+$y+$z;
  $cllab_exame->la08_i_gerar=$fator;

  db_inicio_transacao();
     $db_opcao = 2;
     $cllab_exame->alterar($la08_i_codigo);  
     $iExame=$cllab_exame->la08_i_codigo;

     
     if ($cllab_exame->erro_status != "0") {
         db_query("delete from lab_exasinonima where la18_i_exame=$iExame");
         $vet=explode(",",$str_sinonimia);
         $vet2=explode(",",$str_sinonimia2);
         //die("STR1:$str_sinonimia STR2:$str_sinonimia2 ");
         $cllab_exasinonima->la18_i_exame=$iExame;
         for($x=0;$x<count($vet);$x++){
             if($cllab_exasinonima->erro_status!="0"){ 
                   if($vet[$x]==0){
                       //inclui sinonimia
                       $cllab_sinonima->la10_c_descr=$vet2[$x];
                       $cllab_sinonima->incluir(null);
                       $iCod=$cllab_sinonima->la10_i_codigo;
                   }else{
                       $iCod=$vet[$x];
                   }
                   $cllab_exasinonima->la18_i_sinonima=$iCod;
                   $cllab_exasinonima->incluir(null);
                   if ($cllab_exasinonima->erro_status == "0"){

                      $cllab_exame->erro_status=0;
                      $cllab_exame->erro_sql   = $cllab_exasinonima->erro_sql;
                      $cllab_exame->erro_campo = $cllab_exasinonima->erro_campo;
                      $cllab_exame->erro_banco = $cllab_exasinonima->erro_banco;
                      $cllab_exame->erro_msg   = $cllab_exasinonima->erro_msg;

                   }
             }
         }
     }

     
  db_fim_transacao();
}else if(isset($chavepesquisa)){
   $db_opcao = 2;
   $result = $cllab_exame->sql_record($cllab_exame->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);
      $sql=$cllab_exasinonima->sql_query("","*",""," la18_i_exame = $chavepesquisa ");
      $rResult = $cllab_exasinonima->sql_record($sql);
      for($x=0;$x<$cllab_exasinonima->numrows;$x++){
         db_fieldsmemory($rResult,$x);
         $aSinonimia[$x][1]=$la10_i_codigo;
         $aSinonimia[$x][2]=$la10_c_descr;
      }
   $db_botao = true;
   ?>
  <script>
  parent.document.formaba.a2.disabled = false;
  parent.document.formaba.a3.disabled = false;
  parent.document.formaba.a4.disabled = false;
  parent.document.formaba.a5.disabled = false;
  parent.document.formaba.a6.disabled = false;
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href='lab1_lab_examematerial001.php?la19_i_exame=<?=$la08_i_codigo?>&la08_c_descr=<?=$la08_c_descr?>';
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a3.location.href='lab1_lab_exame004.php?la19_i_exame=<?=$la08_i_codigo?>&la08_i_codigo=<?=$la08_i_codigo?>&la08_c_descr=<?=$la08_c_descr?>';
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href='lab1_lab_examerequisito001.php?la20_i_exame=<?=$la08_i_codigo?>&la08_c_descr=<?=$la08_c_descr?>';
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a5.location.href='lab1_lab_exameatributo001.php?la42_i_exame=<?=$la08_i_codigo?>&la08_c_descr=<?=$la08_c_descr?>';
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a6.location.href='lab1_lab_exameproced001.php?la53_i_exame=<?=$la08_i_codigo?>&la08_c_descr=<?=$la08_c_descr?>';
 </script>
<?}
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
db_app::load("/widgets/dbautocomplete.widget.js");
?>
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <fieldset style='width: 75%;'> <legend><b>Exames</b></legend>
	<?
	include(modification("forms/db_frmlab_exame.php"));
	?>
	</fieldset>
    </center>
	</td>
  </tr>
</table>
</body>
</center>
</html>
<?
if(isset($alterar)){
  if($cllab_exame->erro_status=="0"){
    $cllab_exame->erro(true,false);
    $db_botao=true;
    echo "<script>
          document.form1.db_opcao.disabled=false;
          </script>  ";
    if($cllab_exame->erro_campo!=""){
      echo "<script> document.form1.".$cllab_exame->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllab_exame->erro_campo.".focus();</script>";
    }
  }else{
    $cllab_exame->erro(true,false);
    db_redireciona("lab1_lab_exame002.php?chavepesquisa=$la08_i_codigo");
  }
}
if($db_opcao==22){
  echo "<script>
        document.form1.pesquisar.click();
        document.form1.la18_i_sinonima.value = '';
        document.form1.la10_c_descr.value = '';
        </script>";
}
if($db_opcao==2){
  echo "<script>
        document.form1.la18_i_sinonima.value = '';
        document.form1.la10_c_descr.value = '';
        </script>";
}
?>
<script>
js_tabulacaoforms("form1","la08_c_sigla",true,1,"la08_c_sigla",true);
</script>