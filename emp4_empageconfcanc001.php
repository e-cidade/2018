<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("classes/db_empageconf_classe.php");
include("classes/db_empageconfche_classe.php");
include("classes/db_empageconfchecanc_classe.php");
include("classes/db_empageconfgera_classe.php");
include("classes/db_empageconfcanc_classe.php");
include("classes/db_corconf_classe.php");
include("classes/db_empagenotasordem_classe.php");
$clcorconf = new cl_corconf;
$clempageconf = new cl_empageconf;
$clempageconfche = new cl_empageconfche;
$clempageconfchecanc = new cl_empageconfchecanc;
$clempageconfgera = new cl_empageconfgera;
$clempageconfcanc = new cl_empageconfcanc;
$oDaoEmpAgeNota   = new cl_empagenotasordem;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;

if(isset($atualizar)){

  db_inicio_transacao();
  $sqlerro=false;
  
    

    $arr =  split("XX",$movs);
    $tot_valor ='';
    for($i=0; $i<count($arr); $i++ ){
       $mov = $arr[$i];  
       //--------------------------------- 
       //inclui na tabela empageconfchecanc
         //echo "<br><br>".($clempageconfche->sql_query_file(null,"*","","e91_codmov=$mov"));
         $result = $clempageconfche->sql_record($clempageconfche->sql_query_file(null,"*","","e91_codmov=$mov"));
	 $numrows = $clempageconfche->numrows;
	 if($numrows > 0){
           for($t=0; $t<$numrows; $t++){
	      db_fieldsmemory($result,$t); 
              
	      // echo "<BR><BR>".($clcorconf->sql_query_file(null,"k12_codmov","","k12_codmov=$e91_codcheque"));
	      $clcorconf->sql_record($clcorconf->sql_query_file(null, null, null,"k12_codmov","","k12_codmov=$e91_codcheque and k12_ativo is true"));
	      if($clcorconf->numrows>0){
		$sqlerro = true;
	      	$erro_msg = "Já foi pago empenho com o cheque $e91_cheque. Cancelamento abortado!";
		break;
	      }
	      if($sqlerro == false){
		$clempageconfchecanc->e93_codcheque = $e91_codcheque;
		$clempageconfchecanc->e93_codmov    = $e91_codmov;
		$clempageconfchecanc->e93_cheque    = $e91_cheque;
		$clempageconfchecanc->e93_valor     = $e91_valor;
		$clempageconfchecanc->incluir($e91_codcheque);
		$erro_msg = $clempageconfchecanc->erro_msg;
		if($clempageconfchecanc->erro_status==0){
		  $sqlerro = true;
		}     
              }		
	   }
           if($sqlerro == false){
	     $clempageconfche->excluir(null,"e91_codmov=$mov");
	     $erro_msg = $clempageconfche->erro_msg;
	     if($clempageconfche->erro_status==0){
		 $sqlerro = true;
	     }     
	  }   
         }  
        //-------------------------------------- 
       
       
       //-----------------------------------
       //inclui na tabela empageconfcanc
       if($sqlerro==false){
          $result = $clempageconf->sql_record($clempageconf->sql_query_file(null,"e86_codmov,e86_data,e86_cheque","","e86_codmov=$mov "));
          if($clempageconf->numrows>0){
	        db_fieldsmemory($result,0);
	      }

          $result = $clempageconfgera->sql_record($clempageconfgera->sql_query_file(null,null,"e90_codgera","","e90_codmov=$mov and e90_correto='t'"));
	  if($clempageconfgera->numrows > 0){
   	    db_fieldsmemory($result,0);
	  }else{
	    $e90_codgera = '0';
	  }  

	 $clempageconfcanc->e88_codmov  = $mov;
	 $clempageconfcanc->e88_data    = "$e86_data";
	 $clempageconfcanc->e88_cheque  = $e86_cheque;
	 $clempageconfcanc->e88_codgera = $e90_codgera;
 	 $clempageconfcanc->e88_seqerro = '0';
	 $clempageconfcanc->incluir($mov);
	 $erro_msg = $clempageconfcanc->erro_msg;
	 if($clempageconfcanc->erro_status==0){
	 	//db_msgbox($erro_msg);
	       $sqlerro = true;
	 }     
       }  
       //-----------------------------------
       
       //-----------------------------------
       if($sqlerro==false){
          $result = $clempageconfgera->sql_record($clempageconfgera->sql_query_file(null,null,"e90_codmov as movimento,e90_codgera as gerado","","e90_codmov=$mov and e90_correto='t'"));
          if($clempageconfgera->numrows > 0 ){
	     db_fieldsmemory($result,0);
	     $clempageconfgera->excluir($movimento,$gerado);
	     $erro_msg = $clempageconfgera->erro_msg;
	     if($clempageconfgera->erro_status==0){
	        $sqlerro = true;
	     }     
	  }   
       }  
       //------------------------------------------------ 
      
       //-------------------------------------
       //exclui no empageconf
       if($sqlerro==false){
	 $clempageconf->excluir($movimento);
	 $erro_msg = $clempageconf->erro_msg;
	 if($clempageconf->erro_status==0){
	       $sqlerro = true;
	 }     
       }  
       //---------------
       /**
        * Exlcuimos o movimento da ordem de pagamento;
        * 
        */
       
      $sSqlEmpAgeNota= $oDaoEmpAgeNota->sql_query_file(null,"*",null,"e43_empagemov= {$mov}");
      $rsEmpageNota  = $oDaoEmpAgeNota->sql_record($sSqlEmpAgeNota); 
      if ($oDaoEmpAgeNota->numrows  > 0) {
        
        $oDaoEmpAgeNota->excluir(null,"e43_empagemov = {$mov}");
        if ($oDaoEmpAgeNota->erro_status == 0) {
      
          $sqlerro  = true;
          $erro_msg = $oDaoEmpAgeNota->erro_msg; 
        }
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
<link href="estilos.css" rel="stylesheet" type="text/css">

<script>
function js_atualizar(){
	if(canc.document.form1){
          obj = canc.document.form1;
	  var coluna='';
	  var sep=''; 
	  for(i=0; i<obj.length; i++){
	    nome = obj[i].name.substr(0,5);  
	    
	    if(nome=="CHECK" && obj[i].checked==true){
	      ord = obj[i].name.substring(6);
	      coluna += sep+obj[i].value;
	      sep= "XX";
	    }
	  } 
	  if(coluna==''){
	    alert("Selecione um movimento!");
	    return false;
	  }
	  document.form1.movs.value = coluna;
	  return true;
        }else{
	  return false;
	}	  
	//return coluna ;

}


function js_pesquisar(){
  canc.location.href = "emp4_empageconfcanc002.php?e80_codage=<?=$e80_codage?>";
}
function js_pesquisar_slip(){
  canc.location.href = "emp4_empageconfcancslip002.php?e80_codage=<?=$e80_codage?>";
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name='form1' method="post">
<table width="765" border="0" cellspacing="0" cellpadding="0">
  <?=db_input('movs',10,'',true,'hidden',10);?>
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
      <table>
	<tr>
	   <td colspan='2' align='center'>
		<input name="atualizar" type="submit"  value="Cancelar cheques" onclick='return js_atualizar();'>
		<input name="fechr" type="button" id="pesquisar" value="Pesquisar de ordem" onclick='js_pesquisar();'>
		<input name="fech" type="button" id="pesquisar" value="Pesquisar de slip" onclick='js_pesquisar_slip();'>
		<input name="fechar" type="button" id="pesquisar" value="Fechar" onclick='parent.db_iframe_cheque.hide();'>
	    <b>Total: </b>
	     <?=db_input('total',10,'',true,'text',3)?>
	   </td>  
	</tr>     
	<tr>
	  <td>
           <iframe name="canc"  src="emp4_empageconfcanc002.php?e80_codage=<?=$e80_codage?>"  width="755" height="340" marginwidth="0" marginheight="0" frameborder="0">
              
           </iframe>
	  </td>
	</tr>
  <tr>
    <td>
      * Já autenticado.
    </dt>
  </tr>
      </table>
    </td>
  </tr>
</table>
</form
</body>
</html>
<?
if(isset($atualizar)){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }else{
           
  echo "<script>";
echo "           parent.location.href='emp4_empage001.php?e80_codage=$e80_codage';\n";
  echo "</script>";

  /*
  db_msgbox($erro_msg);
  echo "<script>";
  echo "  parent.db_iframe_cheque.hide();";
  
  echo "</script>";
  */
  }
}
?>