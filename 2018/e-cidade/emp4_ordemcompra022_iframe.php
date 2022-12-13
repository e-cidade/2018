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
include("classes/db_empempenho_classe.php");
include("classes/db_matordemitem_classe.php");
include("classes/db_matordemanu_classe.php");
include("dbforms/db_funcoes.php");

$clempempenho   = new cl_empempenho;
$clmatordemitem = new cl_matordemitem;
$clmatordemanu  = new cl_matordemanu;

$clempempenho->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

db_postmemory($HTTP_POST_VARS);
$lista="";
$vir="";
if (isset($processar)&&$processar!=""){    
  $vt=$HTTP_POST_VARS;
  $ta=sizeof($vt);
  reset($vt);
  for($i=0; $i<$ta; $i++){
    $chave=key($vt);
    if(substr($chave,0,5)=="CHECK"){
      $dados=split("_",$chave);
      $lista.=$vir.$dados[1];
      $vir="_";
    }
    $proximo=next($vt);
  }


echo "<script>parent.location.href='emp4_ordemcompra033.php?listagem_empenhos=$lista&emitir=$emitir'</script>";
exit;
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
<form name="form1" method="post" target="" action="">
<table border='0'>
<tr height="20px">
<td ></td>
<td ></td>
</tr>
<tr>
    <td colspan=2 align='center' >
  <? 
    db_input("processar","10","",true,"hidden",3);
    db_input("emitir","10","",true,"hidden",3);  
  $txt_where = " e60_instit = ".db_getsession("DB_instit");
	$ordem     = "";
	//$data=$data_ano."-".$data_mes."-".$data_dia;  
	//$data1=$data1_ano."-".$data1_mes."-".$data1_dia; 
	if (($data!="--")&&($data1!="--")) {
	    $txt_where = $txt_where." and e60_emiss  between '$data' and '$data1'  ";
	    $ordem     = "e60_emiss desc";
	} else if ($data!="--"){
	      $txt_where = $txt_where."and e60_emiss >= '$data'  ";
	      $ordem     = "e60_emiss desc";
	} else if ($data1!="--"){
	     $txt_where = $txt_where."and e60_emiss <= '$data1'   ";  
	     $ordem     = "e60_emiss desc";
	}
	if (isset($e60_numcgm)&&$e60_numcgm!=""){
	     $txt_where = $txt_where." and e60_numcgm = $e60_numcgm   ";  
	     $ordem     = "e60_numcgm desc";
	}
	
  
  
       $result=$clempempenho->sql_record($clempempenho->sql_query_empnome(null,"*","$ordem"," $txt_where "));
       $numrows=$clempempenho->numrows;
       if($numrows>0){ 
          echo "
	  
	  <table>
           <tr>
	     <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	     <td class='cabec' align='center'  title='$Te60_numemp'>".str_replace(":","",$Le60_numemp)."</td>
	     <td class='cabec' align='center'  title='$Te60_codemp'>".str_replace(":","",$Le60_codemp)."</td>
	     <td class='cabec' align='center'  title='$Tz01_nome'>".str_replace(":","",$Lz01_nome)."</td>
	     <td class='cabec' align='center'  title='$Te60_emiss'>".str_replace(":","",$Le60_emiss)."</td>
	   </tr>
          "; 	   
       }else{
         echo "<br><br><b>Sem Empenhos!!</b>";
       }
       for($i=0; $i<$numrows; $i++){
         db_fieldsmemory($result,$i);
	 $somaval=0;
	 $result_matordemitem=$clmatordemitem->sql_record($clmatordemitem->sql_query_ordem(null,"distinct m51_codordem,m51_valortotal",null,"m52_numemp=$e60_numemp")); 
	 for ($y=0;$y<$clmatordemitem->numrows;$y++ ){
	   db_fieldsmemory($result_matordemitem,$y);
	   $somaval+=$m51_valortotal;
	 }
	 $flag_anu = false;
	 for ($ii = 0; $ii < $clmatordemitem->numrows; $ii++){
	       db_fieldsmemory($result_matordemitem,$ii);
               $result_matordemanu = $clmatordemanu->sql_record($clmatordemanu->sql_query($m51_codordem));
	       if ($clmatordemanu->numrows > 0){
		    $flag_anu = true;
		    break;
	       }
	 }

	 if ($flag_anu==false&&$clmatordemitem->numrows>0){	// Existe ordem de compra e nao foi anulada
	      continue;
	 }

	 if ($somaval > $e60_vlremp){	    
	   continue;
	 }else{
  	   echo"
		   <tr>
		      <td  class='corpo' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$e60_numemp' id='CHECK_".$e60_numemp."'></td>
		      <td  class='corpo'  align='center' title='$Te60_numemp'><label style=\"cursor: hand\"><small>$e60_numemp</small></label></td>
		      <td  class='corpo'  align='center' title='$Te60_codemp'><label style=\"cursor: hand\"><small>$e60_codemp</small></label></td>
		      <td  class='corpo'  align='center' title='$Tz01_nome'><label style=\"cursor: hand\"><small>$z01_nome</small></label></td>
		      <td  class='corpo'  align='center' title='$Te60_emiss'><label style=\"cursor: hand\"><small>".db_formatar($e60_emiss,'d')."</small></label></td>
		   </tr>";
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
 // db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
</script>
</body>
</html>