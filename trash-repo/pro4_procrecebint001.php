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
include("dbforms/db_funcoes.php");
include("classes/db_proctransferint_classe.php");
include("classes/db_proctransferintand_classe.php");
include("classes/db_proctransferintusu_classe.php");
include("classes/db_protprocesso_classe.php");

$clproctransferint = new cl_proctransferint;
$clproctransferintand = new cl_proctransferintand;
$clproctransferintusu = new cl_proctransferintusu;
$clprotprocesso = new cl_protprocesso;

$clproctransferint->rotulo->label();
$clprotprocesso->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("p61_id_usuario");
$clrotulo->label("p68_codproc");
$clrotulo->label("p89_usuario");
$clrotulo->label("nome");


db_postmemory($HTTP_POST_VARS);
/*
if (isset($incluir)){
  
  db_inicio_transacao();
  $sqlerro=false;
  $data= date("Y-m-d",db_getsession("DB_datausu"));
  $clproctransferint->p88_despacho=$p88_despacho;
  $clproctransferint->p88_data=$data;
  $clproctransferint->p88_hora=db_hora();
  $clproctransferint->p88_usuario=db_getsession("DB_id_usuario");
  $clproctransferint->incluir(null);
  $erro_msg = $clproctransferint->erro_msg;
  if ($clproctransferint->erro_status==0){
    db_msgbox("Erro - 11");
    $sqlerro=true;
  } 
  
  $codigo = $clproctransferint->p88_codigo;
  
  if ($sqlerro==false){
    $clproctransferintusu->p89_codtransferint=$codigo;
    $clproctransferintusu->p89_usuario=$p89_usuario ;
    $clproctransferintusu->incluir();
    $erro_msg = $clproctransferintusu->erro_msg;
    if ($clproctransferintusu->erro_status==0){
    db_msgbox("Erro - 21");
      $sqlerro=true;
    } 
  }
  if ($sqlerro==false){
  
  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,5)=="CHECK"){
      $dados=split("_",$chave); 
      $result1=$clprotprocesso->sql_record($clprotprocesso->sql_query_file($dados[1],"p58_codandam"));
      db_fieldsmemory($result1,0);
      $clproctransferintand->p87_codtransferint=$codigo;
      $clproctransferintand->p87_codandam=$p58_codandam;
      $clproctransferintand->incluir();
      $erro_msg = $clproctransferintand->erro_msg;
      if ($clproctransferintand->erro_status==0){
	$sqlerro=true;
      } 
    }
    $proximo=next($vt);
  }
  }
  db_fim_transacao($sqlerro);
}*/
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
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
<form name="form1" method="post" target="" action="pro4_tranferinter001.php">
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
    <tr>
       <td >
       </td>
       <td >
       </td>
    </tr>
  </tr>
  <tr>
  <td colspan=2 align='center'>
    <input name="incluir" type="submit"   value="Receber">
  </td>
  </tr>
    <td colspan=2 align='center' >
  <?   $usuario = db_getsession("DB_id_usuario");
       $result=($clproctransferint->sql_query(null,"p88_codigo,p88_data,p88_hora,p88_usuario,atual.id_usuario as usu_atual",null,"p89_usuario = $usuario "));
       $numrows=$clproctransferint->numrows;
       if($numrows>0){ 
          echo "
	  <br><br>
	  <table>
           <tr>
	     <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	     <td class='cabec' align='center'  title='$Tp88_codigo'>".str_replace(":","",$Lp88_codigo)."</td>
	     <td class='cabec' align='center'  title='$Tp88_data'>".str_replace(":","",$Lp88_data)."</td>
	     <td class='cabec' align='center'  title='$Tp88_hora'>".str_replace(":","",$Lp88_hora)."</td>
	     <td class='cabec' align='center'  title='$Tp88_usuario'>".str_replace(":","",$Lp88_usuario)."</td>
	     <td class='cabec' align='center'  title='$Tp88_usuario'>".str_replace(":","",$Lp88_usuario)."</td>
	   </tr>
          "; 	   
       }else{
         echo "<br><br><b>Sem Transferências Internas!!</b>";
       }
       for($i=0; $i<$numrows; $i++){
         db_fieldsmemory($result,$i);
         $mostratrans = $clprocandamintand->sql_record($clprocandamintand->sql_query_file(null,"*",null,"p86_codtrans = $p88_codigo"));
	 if ($mostratrans->numrows==0){
	   echo"
	     <tr>
		<td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$p88_codigo' id='CHECK_".$p58_codproc."'></td>
		<td  class='corpo'  align='center' title='$Tp88_codigo'><label style=\"cursor: hand\"><small>$p88_codigo</small></label></td>
		<td  class='corpo'  align='center' title='$Tp88_data'><label style=\"cursor: hand\"><small>".db_formatar($p88_data,'d')."</small></label></td>
		<td  class='corpo'  align='center' title='$Tp88_hora'><label style=\"cursor: hand\"><small>$p88_hora</small></label></td>
		<td  class='corpo'  align='center' title='$Tp88_usuario'><label style=\"cursor: hand\"><small>$p88_usuario</small></label></td>
		<td  class='corpo'  align='center' title='$Tp88_usuario'><label style=\"cursor: hand\"><small>$usu_atual</small></label></td>
	     </tr>
		";
	 }
       }
	 echo"
	   </table>";	        
       

  ?>
  </td>
  </tr>
  </table>
  </form>
</center>
<? 

db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

if (isset($incluir)){
    db_msgbox($erro_msg);
    if($sqlerro==true){
      echo "<script> document.form1.".$clproctransferint->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clproctransferint->erro_campo.".focus();</script>";
    }else{ 
      echo"<script>top.corpo.location.href='pro4_tranferinter001.php';</script>";
    }
}

?>
</body>
</html>