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
include("classes/db_pagordemnota_classe.php");
include("classes/db_pagordem_classe.php");
include("classes/db_pagordemconta_classe.php");
include("classes/db_pagordemele_classe.php");
include("classes/db_empempenho_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_empnota_classe.php");
include("classes/db_empord_classe.php");
include("classes/db_empnotaele_classe.php");

$clpagordem = new cl_pagordem;
$clpagordemconta= new cl_pagordemconta;
$clpagordemele  = new cl_pagordemele;
$clempempenho   = new cl_empempenho;
$clempnota      = new cl_empnota;
$clempord       = new cl_empord;
$clempnotaele   = new cl_empnotaele;
$clpagordemnota = new cl_pagordemnota;

db_postmemory($HTTP_POST_VARS);

$db_opcao = 33;
$db_botao = false;

$procedimento = "anulacao";

if(isset($anular)){
  $sqlerro=false;
  db_inicio_transacao();

   $sql = "update empparametro set e39_anousu = e39_anousu where e39_anousu = ".db_getsession("DB_anousu");
   $res = pg_query($sql);
  
  //rotina que inclui em pagordemele 
  if($sqlerro==false){
    $arr_dados = split("#",$dados);
    $tam = count($arr_dados);
    for($i=0; $i<$tam; $i++){
         $arr_ele = split("-",$arr_dados[$i]);
         $elemento    = $arr_ele[0];
	 $vlrord      = $arr_ele[1];

          //------------------------------------
         //rotina que atualiza o valor pago para somar com o que o usuario digitar
            $result = $clpagordemele->sql_record($clpagordemele->sql_query_file($e50_codord,$elemento)); 
   	    $numrows = $clpagordemele->numrows;
	  //------------------------------------

	 if($numrows >0){
	   db_fieldsmemory($result,0);
	   $tem=true;  
	 }else{
	   $e53_valor  ='0';
           $e53_vlranu ='0';
           $e53_vlrpag ='0';
	 }
	 
	 $valor_anu = $e53_vlranu + $vlrord ; 

	//%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%
	//	verifica os valor disponivel para anualação
       $soma = db_formatar($e53_valor-$e53_vlrpag,"p");
		
	
	if(round($valor_anu,2) > round($soma+0,2)){
	     $sqlerro=true;
	     $erro_msg = " Não é possível anular  $valor_anu do elemento $elemento . Verifique!";
	     break;
	}  
        //=================== 

	 
	 //rotina que anula os valores da ordem
         if($sqlerro==false){
	    $clpagordemele->e53_codord  = $e50_codord;
	    $clpagordemele->e53_codele  = $elemento;
	    $clpagordemele->e53_vlranu =  "$valor_anu"; 
	    $clpagordemele->alterar($e50_codord,$elemento);
	    $erro_msg = $clpagordemele->erro_msg;
	    if($clpagordemele->erro_status==0){
		$sqlerro=true;
	    }
	 }   
     } 	  

     //rotina pega as notas marcadas para anular
     if($sqlerro==false && isset($chaves) && $chaves!=''){
	$arr_notas = split("#",$chaves);
	$tam = count($arr_notas);
	for($i=0; $i<$tam; $i++){
	        $nota = $arr_notas[$i];

                $clpagordemnota->sql_record($clpagordemnota->sql_query_file($e50_codord,$nota,"e71_codord",'',"e71_anulado='f'"));
                if($clpagordemnota->numrows==0){
		   $erro_msg = "Nota $nota já anulada!";
                   $sqlerro=true;
		} 
	        if($sqlerro==false){
		  $clpagordemnota->e71_codord  = $e50_codord;
		  $clpagordemnota->e71_codnota = $nota;
		  $clpagordemnota->e71_anulado = "true";
		  $clpagordemnota->alterar($e50_codord,$nota);
		  $erro_msg=$clpagordemnota->erro_msg;
		  if($clpagordemnota->erro_status==0){
		    $sqlerro=true;
		    break;
		  }
		}  
	}
     }	
  }
  /*
   if($sqlerro == false){   
     //rotina que traz os dados do pagordem
     $result = $clpagordemconta->sql_record($clpagordemconta->sql_query($e50_codord,"z01_numcgm as numcgm_of")); 
     if($clpagordemconta->numrows>0){
       db_fieldsmemory($result,0);
       if( (isset($z01_numcgm2) && $z01_numcgm2 != '') && $z01_numcgm2 != $numcgm_of){
	 $clpagordemconta->e49_codord = $e50_codord;
	 $clpagordemconta->e49_numcgm = $z01_numcgm2;
	 $clpagordemconta->alterar($e50_codord);
	 if($clpagordemconta->erro_status==0){
	   $sqlerro=true;
	   $erro_msg = $clpagordemconta->erro_msg;
	 }  
       }else{
	 $clpagordemconta->e49_codord = $e50_codord;
	 $clpagordemconta->excluir($e50_codord);
	 if($clpagordemconta->erro_status==0){
	   $sqlerro=true;
	   $erro_msg = $clpagordemconta->erro_msg;
	 }  
       }
     }else if(isset($z01_numcgm2) && $z01_numcgm2 != '' ){
       $clpagordemconta->e49_codord = $e50_codord;
       $clpagordemconta->e49_numcgm = $z01_numcgm2;
       $clpagordemconta->incluir($e50_codord);
       if($clpagordemconta->erro_status==0){
	 $sqlerro=true;
	 $erro_msg = $clpagordemconta->erro_msg;
       }  
     }  
   }
   */
  db_fim_transacao($sqlerro);

  $db_opcao = 3;
  $db_botao = true;
}
if(isset($chavepesquisa) || isset($e50_codord)){
   if(isset($chavepesquisa)){
       $e50_codord=$chavepesquisa;
   }
   
   $db_opcao = 3;
   $db_botao = true;
   
   //rotina que traz os dados do pagordem
   $result = $clpagordem->sql_record($clpagordem->sql_query($e50_codord)); 
   db_fieldsmemory($result,0);
   
   //rotina que traz os dados do empenho
   $result = $clempempenho->sql_record($clempempenho->sql_query_file($e50_numemp)); 
   db_fieldsmemory($result,0);
   
   
   //rotina que traz os dados do pagordem
   $result = $clpagordemconta->sql_record($clpagordemconta->sql_query($e50_codord,"z01_numcgm as z01_numcgm2,z01_nome as z01_nome2")); 
   if($clpagordemconta->numrows>0){
     db_fieldsmemory($result,0);
   }  
   
   
   //verifica se ja naum tem agenda
     $res_agenda = $clempord->sql_record($clempord->sql_query(null,$e50_codord,"e81_codage,e80_data"));
     if($clempord->numrows>0){
         db_fieldsmemory($res_agenda,0);
      // $db_opcao = 3;
      // $db_botao = false;
       $agendado = true;
     } 
   //-=========================================--------------
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
  <?if(isset($agendado)){?>
     <tr><td>&nbsp;</td></tr>
  <?}?>  
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmpagordem.php");
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?
if(isset($anular)){
    db_msgbox($erro_msg);
    if($clpagordem->erro_campo!=""){
      echo "<script> document.form1.".$clpagordem->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clpagordem->erro_campo.".focus();</script>";
    }
}
if(isset($e50_codord)){
    echo "
           <script>
	    function js_bloqueia(){
	        parent.document.formaba.pagordemrec.disabled=false;\n
	        top.corpo.iframe_pagordemrec.location.href='emp1_pagordemrec001.php?db_opcaoal=33&e52_codord=$e50_codord';\n
	    }
	    js_bloqueia();
	 </script>
       ";  
}

  if($db_opcao==22||$db_opcao==33){
    echo "<script>js_pesquisa_ordem();</script>\n";
  }
?>