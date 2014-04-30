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
include("classes/db_procandamint_classe.php");
include("classes/db_procandamintand_classe.php");
include("classes/db_procandamintusu_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_proctransferint_classe.php");
include("classes/db_proctransferintand_classe.php");

$clproctransferint = new cl_proctransferint;
$clproctransferintand = new cl_proctransferintand;
$clprocandamint = new cl_procandamint;
$clprocandamintand = new cl_procandamintand;
$clprocandamintusu = new cl_procandamintusu;
$clprotprocesso = new cl_protprocesso;

$clprocandamint->rotulo->label();
$clprotprocesso->rotulo->label();
$clproctransferint->rotulo->label(); 

$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("");
$clrotulo->label("");
$clrotulo->label("");
$clrotulo->label("");


db_postmemory($HTTP_POST_VARS);

if (isset($incluir)){
  
  db_inicio_transacao();
  $sqlerro=false;
  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  for($i = 0; $i < $ta; $i++) {
    
    $chave = key($vt);
    if (substr($chave, 0, 5) == "CHECK") {
      
      $dados = split("_", $chave); 
      $result_despacho = $clproctransferint->sql_record($clproctransferint->sql_query_file(null,
                                                                                           "p88_despacho as despacho,
                                                                                            p88_publico as publico",
                                                                                            null,
                                                                                            "p88_codigo=".$dados[1])
                                                                                            );
      db_fieldsmemory($result_despacho, 0);
      $sql3    = $clproctransferintand->sql_query_file(null, 
                                                      "p87_codandam", 
                                                       null, 
                                                       "p87_codtransferint=".$dados[1]
                                                       );
      $result3  = pg_exec($sql3);
      $numrows3 = pg_numrows($result3); 
      for($y = 0; $y < $numrows3; $y++) {
        
      	db_fieldsmemory($result3, $y);
      	$data = date("Y-m-d",db_getsession("DB_datausu"));
      	if ($sqlerro == false) {
      	  
      	  $clprocandamint->p78_codandam = $p87_codandam;
      	  $clprocandamint->p78_data     = $data;
      	  $clprocandamint->p78_hora     = db_hora();
      	  $clprocandamint->p78_usuario  = db_getsession("DB_id_usuario");
      	  $clprocandamint->p78_despacho = addslashes($despacho);
      	  $clprocandamint->p78_publico  = ($publico == "t"?"1":"0");
      	  $clprocandamint->p78_transint = "true";
      	  $clprocandamint->incluir(null);
      	  
      	  $erro_msg = $clprocandamint->erro_msg;
      	  if ($clprocandamint->erro_status == 0) {
      	    $sqlerro=true;
      	  }
      	} 
      	if ($sqlerro==false) {
      	  
      	  $clprocandamintand->p86_codtrans = $dados[1];
      	  $clprocandamintand->p86_codandam = $p87_codandam;
      	  $clprocandamintand->incluir();
      	} 
      }
    }
    $proximo=next($vt);
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
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
function js_desabilita(){
  document.form1.incluir.disabled = true;
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
<form name="form1" method="post" target="" action="pro4_recebinter001.php">
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
  <td colspan=2 align='center'>
    <input name="incluir" type="submit"   value="Receber">
  </td>
  </tr>
    <td colspan=2 align='center' >
  <?   $soma=0;
       $usuario=db_getsession("DB_id_usuario");
       $depto=db_getsession("DB_coddepto");
       $sql=$clproctransferint->sql_query_andusu(null,"distinct p88_codigo,p88_data,p88_hora,p88_usuario,atual.nome",null,"p89_usuario=$usuario and p61_coddepto=$depto ");
       $result = pg_exec($sql);
       $numrows=pg_numrows($result);
       for($i=0; $i<$numrows; $i++){
         db_fieldsmemory($result,$i);
	 $sql2 = $clprocandamintand->sql_query_file(null,"*",null,"p86_codtrans=$p88_codigo");
	 $result2=pg_exec($sql2);
	 $numrows2=pg_numrows($result2);
	 if ($numrows2!=0){
	   $soma++;
	 }
       }
       if ($soma!=$numrows){
	 
	 if($numrows>0){ 
	    echo "
	    <br><br>
	    <table>
	     <tr>
	       <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	       <td class='cabec' align='center'  title='$Tp88_codigo'>".str_replace(":","",$Lp88_codigo)."</td>
	       <td class='cabec' align='center'  title='$Tp88_data'>".str_replace(":","",$Lp88_data)."</td>
	       <td class='cabec' align='center'  title='$Tp88_hora'>".str_replace(":","",$Lp88_hora)."</td>
	       <td class='cabec' align='center'  title='Cod. Usuário'><b>Cod. Usuário</b></td>
	       <td class='cabec' align='center'  title='Nome Usuário'><b>Nome Usuário</b></td>
	       <td class='cabec' align='center'  title='Nome Usuário'><b>Info.</b></td>
	     </tr>
	    "; 	   
	 }else{
	   echo"<script>js_desabilita();</script> ";
	   echo "<br><br><b>Sem Transferências Internas para Receber!!</b>";
	 }
	 for($i=0; $i<$numrows; $i++){
	   db_fieldsmemory($result,$i);
	   $sql2 = $clprocandamintand->sql_query_file(null,"*",null,"p86_codtrans=$p88_codigo");
	   $result2=pg_exec($sql2);
	   $numrows2=pg_numrows($result2);
	   
	   if ($numrows2==0){
	     echo"
	       <tr>
		  <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$p88_codigo' id='CHECK_".$p88_codigo."'></td>
		  <td  class='corpo'  align='center' title='$Tp88_codigo'><label style=\"cursor: hand\"><small>$p88_codigo</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp88_data'><label style=\"cursor: hand\"><small>".db_formatar($p88_data,'d')."</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp88_hora'><label style=\"cursor: hand\"><small>$p88_hora</small></label></td>
		  <td  class='corpo'  align='center' title='$Tp88_usuario'><label style=\"cursor: hand\"><small>$p88_usuario</small></label></td>
		  <td  class='corpo'  align='center' title='$Tnome'><label style=\"cursor: hand\"><small>$nome</small></label></td>
		  <td  class='corpo'  align='center' title='Informações da transferência'><label style=\"cursor: hand\"><small><a href='#'  onclick='js_verinfo($p88_codigo);'>ver</a></small></label></td>
	       </tr>";
	   }
	 }
       }else {
	 echo"<script>js_desabilita();</script> ";
         echo"<br><br> <b>Sem Tranferências Internas para Receber!!</b>";
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
      echo "<script> document.form1.".$clprocandamint->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clprocandamint->erro_campo.".focus();</script>";
    }else{ 
      echo"<script>top.corpo.location.href='pro4_recebinter001.php';</script>";
    }
}

?>
</body>
</html>
<script>
function js_verinfo(codigo){
  js_OpenJanelaIframe('top.corpo','db_iframe_recebinterinfo','pro4_recebinterinfo.php?p88_codigo='+codigo,'Info.',true);
}
</script>