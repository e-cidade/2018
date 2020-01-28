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

include("classes/db_empageconf_classe.php");
include("classes/db_empageconfgera_classe.php");
include("classes/db_empagegera_classe.php");
include("classes/db_empageconfcanc_classe.php");

$clempageconf = new cl_empageconf;
$clempagegera = new cl_empagegera;
$clempageconfgera = new cl_empageconfgera;
$clempageconfcanc = new cl_empageconfcanc;

include("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;

if(isset($atualizar)){

  db_inicio_transacao();
  $sqlerro=false;
  
       
    

    

    $sql = $clempageconfgera->sql_query_inf(null,null,"e81_codmov",'',"e90_codgera=$e87_codgera");
    $result02 =  $clempageconfgera->sql_record($sql); 
    $numrows02 = $clempageconfgera->numrows;
    for($i=0; $i< $numrows02; $i++ ){
        db_fieldsmemory($result02,$i);
	$mov = $e81_codmov;
       //-----------------------------------
       //inclui na tabela empageconfcanc
       
       if($sqlerro==false){

         $result = $clempageconf->sql_record($clempageconf->sql_query_file($mov,"e86_codmov,e86_data,e86_cheque"));
	 if($clempageconf->numrows>0){
	   db_fieldsmemory($result,0);
	 }else{
	   //db_msgbox('Erro.');
	   $erro_msg = 'Erro. Contate suporte.';
	   $sqlerro=true;
	 }  
	 if($sqlerro == false){
	   $clempageconfcanc->e88_codmov = $mov;
	   $clempageconfcanc->e88_data   = "$e86_data";
	   $clempageconfcanc->e88_cheque = $e86_cheque;
	   $clempageconfcanc->e88_codgera = $e87_codgera;
	   $clempageconfcanc->e88_seqerro = '0';
	   $clempageconfcanc->incluir($mov);
	   $erro_msg = $clempageconfcanc->erro_msg;
	   if($clempageconfcanc->erro_status==0){
		 $sqlerro = true;
	   }   
	 }  
       }  
       //-----------------------------------
       
       //-----------------------------------
       if($sqlerro==false){
          $result = $clempageconfgera->sql_record($clempageconfgera->sql_query_file(null,null,"e90_codmov","e90_codgera=$e87_codgera and e90_codmov=$mov"));
          if($clempageconfgera->numrows > 0 ){
	     $clempageconfgera->e90_codgera = $e87_codgera;
	     $clempageconfgera->e90_codmov  = $mov;
	     $clempageconfgera->excluir($mov,$e87_codgera);
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
	 $clempageconf->e86_codmov = $mov;
	 $clempageconf->excluir($mov);
	 $erro_msg = $clempageconf->erro_msg;
	 if($clempageconf->erro_status==0){
	       $sqlerro = true;
	 }     
       }  
       //---------------
   }  
       
       //-------------------------------------
       //exclui no empagegera
       if($sqlerro==false){
	 $clempagegera->e87_codgera = $e87_codgera;
	 $clempagegera->excluir($e87_codgera);
	 $erro_msg = $clempagegera->erro_msg;
	 if($clempagegera->erro_status==0){
	       $sqlerro = true;
	 }     
       }  
       //---------------
  db_fim_transacao($sqlerro);


}



$clrotulo = new rotulocampo;
$clrotulo->label("e87_codgera");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">



<script>
function js_pesquisar(){
  canc.location.href = "emp4_empageconflaycanc002.php?e83_codtipo=<?=$e83_codtipo?>&e80_codage=<?=$e80_codage?>";
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<form name='form1' method="post">
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <?=db_input('e84_codmod',10,'',true,'hidden',1);?>
  <?=db_input('movs',10,'',true,'hidden',10);?>
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <table>
        <tr>
	  <td>
 	    <?=$Le87_codgera?>
	  </td>
	  <td>
         <?
	   $result    = $clempageconfgera->sql_record($clempageconfgera->sql_query(null,null,"distinct e87_codgera,e87_descgera",'e87_descgera',"e80_codage=$e80_codage"));
	   $numrows02 = $clempageconfgera->numrows;
	   if($clempageconfgera->numrows > 0 ){  
   	     db_selectrecord("e87_codgera",$result,true,1,"","","","","");
	   }else{
	     db_input('nada',10,'',true,'text',3);
	   }  
	 ?>
	  </td>
	</tr>
	<tr>
	   <td colspan='2' align='center'>
		<input name="atualizar" type="submit"  value="Cancelar arquivo">
		<input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar" >
		<input name="fechar" type="button" id="pesquisar" value="Fechar" onclick='parent.db_iframe_anula.hide();'>
	    <b>Total: </b>
<?
     if(isset($pesquisar)){
       $sql = $clempageconfgera->sql_query_inf(null,null,"sum(e81_valor) as total",'',"e80_codage=$e80_codage");
       $result05 = $clempageconfgera->sql_record($sql); 
       db_fieldsmemory($result05,0,true);
     }  
?>      
	     <?=db_input('total',10,'',true,'text',3)?>
	     
	   </td>  
	</tr>     
      </table>	
      <table>	
	<?if(isset($pesquisar)){?>

	<tr>
	  <td>
<?	  
      $sql = $clempageconfgera->sql_query_inf(null,null,"e81_codmov,e60_codemp,e82_codord,z01_nome,e81_valor",'e87_descgera',"e87_codgera=$e87_codgera");
      $clempageconfgera->sql_record($sql); 
      $numrows = $clempageconfgera->numrows;
      if($numrows>0){
	  
	  $cliframe_seleciona->textocabec ="black";
	  $cliframe_seleciona->textocorpo ="black";
	  $cliframe_seleciona->fundocabec ="#999999";
	  $cliframe_seleciona->fundocorpo ="#cccccc";
	  $cliframe_seleciona->iframe_height ="280";
	  $cliframe_seleciona->iframe_width ="450";
	  $cliframe_seleciona->iframe_nome ="canc";
	  $cliframe_seleciona->fieldset =false;

	  $cliframe_seleciona->marcador = false;
	  $cliframe_seleciona->checked = true;
	  
	  
	  $cliframe_seleciona->campos  = "e81_codmov,e60_codemp,e82_codord,z01_nome,e81_valor";
	  $cliframe_seleciona->sql = $sql;
	  $cliframe_seleciona->chaves ="e81_codmov";
	  $cliframe_seleciona->iframe_seleciona(3);    
      } 
     
?>
	  
	  </td>
	</tr>
	<?}?>
      </table>
    </center>
    </td>
  </tr>
</table>
</form
</body>
</html>
<?
if($numrows02 == 0){
  echo "<script>";
  echo "document.form1.atualizar.disabled = true;";
  echo "</script>";
}  
if(isset($atualizar) ){
  if($sqlerro == true){
    db_msgbox($erro_msg);
  }else{  
    echo "<script>";


    echo "           parent.location.href='emp4_empageconflay001.php?e80_codage=$e80_codage&e84_codmod=$e84_codmod';\n";


    
    echo "</script>";
    echo "<script>";
    echo "  parent.db_iframe_cheque.hide();";
    echo "</script>";
  }
}
?>