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
include("classes/db_levinscr_classe.php");
include("classes/db_levcgm_classe.php");
include("classes/db_levanta_classe.php");
include("classes/db_levantanotas_classe.php");
include("classes/db_levvalor_classe.php");
include("classes/db_levusu_classe.php");
include("classes/db_levvalorpgtos_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cllevanta = new cl_levanta;
$cllevantanotas = new cl_levantanotas;
$cllevvalor = new cl_levvalor;
$cllevusu = new cl_levusu;
$cllevinscr = new cl_levinscr;
$cllevcgm = new cl_levcgm;
$cllevvalorpgtos = new cl_levvalorpgtos;
$db_opcao = 33;
$db_botao = false;
if(isset($excluir)){

  $sqlerro=false;
  db_inicio_transacao();
     $db_botao = true;
     $result = $cllevvalor->sql_record($cllevvalor->sql_query_file("","y63_sequencia","","y63_codlev=$y60_codlev")); 
     $numrows=$cllevvalor->numrows;
     if($numrows>0){
     for($i=0; $i<$numrows; $i++){
        db_fieldsmemory($result,$i);
	      //excluir da levantanotas
       $resNotas = $cllevantanotas->sql_record($cllevantanotas->sql_query_file(null,'*',null,"y79_sequencia=$y63_sequencia"));
			 if ($cllevantanotas->numrows > 0){
      	 	 $cllevantanotas->excluir(null,"y79_sequencia=$y63_sequencia");
 		       if ($cllevantanotas->erro_status==0){
              $sqlerro = true;
					 }
 
       }
       $result02 = $cllevvalorpgtos->sql_record($cllevvalorpgtos->sql_query_file("$y63_sequencia","","y68_sequencia")); 
       $numrows02=$cllevvalorpgtos->numrows;
       if($numrows02>0 && $sqlerro==false){
         db_fieldsmemory($result02,0);
        //rotina para excluir do levvalorpgtos se existir
     	  $cllevvalorpgtos->y63_sequencia=$y63_sequencia;
        $cllevvalorpgtos->excluir($y63_sequencia);
        $erro_msg= $cllevvalorpgtos->erro_msg;  
        if($cllevvalorpgtos->erro_status==0){
          $sqlerro=true;
        }  
      }
      //rotina para excluir do levvalor se existir
      if($sqlerro==false){
      	$cllevvalor->y63_sequencia=$y63_sequencia;
       	$cllevvalor->excluir($y63_sequencia);
        $erro_msg= $cllevvalor->erro_msg;  
      	if($cllevvalor->erro_status==0){
  	      $sqlerro=true;
	      }    
      }  	
    }
   }
  //rotina para excluir do levusu se existir
    $result = $cllevusu->sql_record($cllevusu->sql_query_file($y60_codlev,"","y61_codlev")); 
    if($cllevusu->numrows>0 && $sqlerro==false){
    $cllevusu->y61_sequencia=$y60_codlev;
    $cllevusu->excluir($y60_codlev);
    $erro_msg= $cllevusu->erro_msg;  
    if($cllevusu->erro_status==0){
      $sqlerro=true;
    }  
  }

  //rotina para excluir do levinscr se existir
  $result = $cllevinscr->sql_record($cllevinscr->sql_query_file($y60_codlev,"y62_inscr")); 
  if($cllevinscr->numrows>0 && $sqlerro==false){
    $cllevinscr->y61_sequencia=$y60_codlev;
    $cllevinscr->excluir($y60_codlev);
    $erro_msg= $cllevinscr->erro_msg;  
    if($cllevinscr->erro_status==0){
      $sqlerro=true;
    }  
  }
  //rotina para excluir do levcgm se existir
  $result = $cllevcgm->sql_record($cllevcgm->sql_query_file($y60_codlev,"y93_numcgm")); 
  if($cllevcgm->numrows>0 && $sqlerro==false){
    $cllevcgm->y61_sequencia=$y60_codlev;
    $cllevcgm->excluir($y60_codlev);
    $erro_msg= $cllevcgm->erro_msg;  
    if($cllevcgm->erro_status==0){
      $sqlerro=true;
    }  
  }
  
  if(!$sqlerro){
    $cllevanta->excluir($y60_codlev);
    $erro_msg= $cllevanta->erro_msg;  
    if($cllevanta->erro_status==0){
      $sqlerro=true;
    }  
  } 
  db_fim_transacao($sqlerro);
  $db_opcao = 3;

}else if(isset($chavepesquisa)){
   $db_opcao = 3;
   $result = $cllevanta->sql_record($cllevanta->sql_query_inf($chavepesquisa)); 
   db_fieldsmemory($result,0);
   $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmlevanta.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if (@$y60_importado == 't'){

	echo "<script>document.getElementById('db_opcao').disabled=true;
	     alert('Levantamento já exportado!');
			 </script>";

}
if(isset($excluir)){
  if($cllevanta->erro_status=="0"){
    $cllevanta->erro(true,false);
    if($cllevanta->erro_campo!=""){
      echo "<script> document.form1.".$cllevanta->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllevanta->erro_campo.".focus();</script>";
    }
  }else{
    db_msgbox($erro_msg);
    echo "
         <script>
	   parent.location.href='fis4_levanta006.php';
         </script>
    ";
  }
}else if(isset($chavepesquisa)){
     echo "
           <script>
              function js_xy(){
                parent.document.formaba.levvalor.disabled=false;\n
                parent.document.formaba.levusu.disabled=false;\n
		top.corpo.iframe_levvalor.location.href='fis1_levvalor001.php?db_opcaoal=true&y60_contato=$y60_contato&y63_codlev=$y60_codlev';\n
		top.corpo.iframe_levusu.location.href='fis1_levusu001.php?db_opcaoal=true&y60_contato=$y60_contato&y61_codlev=$y60_codlev';\n
              }
              js_xy();
           </script>
         ";
 
}  
?>