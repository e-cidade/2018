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
include("classes/db_editalrua_classe.php");
include("classes/db_edital_classe.php");
include("classes/db_editalproj_classe.php");
include("classes/db_editalruaproj_classe.php");
include("classes/db_contlot_classe.php");
include("classes/db_contlotv_classe.php");
include("classes/db_editalserv_classe.php");
include("classes/db_projmelhoriasmatric_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clprojmelhoriasmatric = new cl_projmelhoriasmatric;
$cleditalrua = new cl_editalrua;
$cledital = new cl_edital;
$cleditalproj = new cl_editalproj;
$cleditalruaproj = new cl_editalruaproj;
$clcontlot = new cl_contlot;
$clcontlotv = new cl_contlotv;
$cleditalserv = new cl_editalserv;
$db_opcao = 22;
$db_botao = false;
if((isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"])=="Alterar"){
  db_inicio_transacao();
  $db_opcao = 2;
  $sqlerro=false;
  $mexe=true;
      $clcontlot->sql_record($clcontlot->sql_query($d02_contri,"","d05_contri"));
      if($clcontlot->numrows>0){
        $clcontlotv->d06_contri=$d02_contri;
        $clcontlotv->excluir($d02_contri);
        if($clcontlotv->erro_status=="0"){
	  $clcontlotv->erro(true,false);
          $sqlerro=true;
        } 	
        $clcontlot->d05_contri=$d02_contri;
        $clcontlot->excluir($d02_contri);
        if($clcontlot->erro_status=="0"){
	  $clcontlot->erro(true,false);
          $sqlerro=true;
        } 	
      }	
  
  $cleditalrua->d02_idlog=db_getsession("DB_id_usuario");
  $cleditalrua->d02_data= date("Y-m-d",db_getsession("DB_datausu"));
  $cleditalrua->alterar($d02_contri);
  if($cleditalrua->erro_status=="0"){
    $sqlerro=true;
  } 	
  $cleditalserv->d04_contri=$d02_contri;
  $cleditalserv->excluir($d02_contri);
  if($cleditalserv->erro_status=="0"){
      $sqlerro=true;
   } 	

  $matriz= split("XX",$dados);
  $tam=sizeof($matriz);
  for($i=0; $i<$tam; $i++){
    if($matriz[$i]!=""){
      $dad = split("-",$matriz[$i]);
      $cleditalserv->d04_contri  = $d02_contri;
      $cleditalserv->d04_tipos   = $dad[0];
      $cleditalserv->d04_quant   = $dad[1];
      $cleditalserv->d04_vlrcal  = $dad[2];
      $cleditalserv->d04_vlrval  = $dad[4];
      $cleditalserv->d04_mult    = $dad[5];
      $cleditalserv->d04_forma   = $dad[6];
      $cleditalserv->d04_vlrobra = $dad[7];
      $cleditalserv->incluir($d02_contri,$dad[0]);

      if($cleditalserv->erro_status=="0"){
    	  $sqlerro=true;
      } 	

    } 

  }  
  db_fim_transacao($sqlerro);
  //se $mexe for igual a false, significa que que ele ja existe em contlot com o mesmo edital
    if($mexe==true && isset($d40_codigo)){
      $resulte=$clprojmelhoriasmatric->sql_record($clprojmelhoriasmatric->sql_query($d40_codigo,"","distinct j01_idbql,d41_testada,d41_eixo,d41_pgtopref"));
      $numer=$clprojmelhoriasmatric->numrows;
      if($numer>0){
        db_inicio_transacao();
        for($ii=0;$ii<$numer;$ii++){
          db_fieldsmemory($resulte,$ii);
          if($d41_pgtopref!="t"){
            continue;
          }
          $clcontlot->d05_contri=$d02_contri;
          $clcontlot->d05_idbql=$j01_idbql;
          $clcontlot->d05_testad=$d41_testada+$d41_eixo;
          $clcontlot->incluir($d02_contri,$j01_idbql);
          if($clcontlot->erro_status=='0'){
            $clcontlot->erro(true,false);
            $sqlerro = true;
            break;
          }
          $redital=$cleditalserv->sql_record($cleditalserv->sql_query_file($d02_contri));
          $numrows=$cleditalserv->numrows;
          if($numrows==0){
             db_msgbox("Não foi cadastrado editaserv");  
             $sqlerro=true;
             break;
          }
          $result06=$cleditalrua->sql_record($cleditalrua->sql_query_file($d02_contri,"d02_profun"));
          db_fieldsmemory($result06,0);
	  
	  //rotina que pega o desconto do edital
          $result09 = $cledital->sql_record($cledital->sql_query_file($d02_codedi,"d01_perc"));
	  db_fieldsmemory($result09,0);

          for($j=0; $j<$numrows; $j++){
            db_fieldsmemory($redital,$j); 


            if (1==2) {
							if($d04_vlrcal != $d04_vlrval){
								 /*	      
								 valor calculado = vlrval - d01_perc%
								 valor calculado = 26 - 33.33% = 17.34
								 valor calculado = valor calculado * d02_profun * testada

								 valor calculado = 17.34 * 5 * 13 = 1127.10
								 valor calculado = 1127.10 / 2 = 563.55
								 valorizacao pelo valor para valorizacao = valor calculado + venal
								 valorizacao pelo valor para valorizacao = 996.7165 + 30000 = 30996.7165
								 */
								 $valor_normal = $d04_vlrval - (($d04_vlrval*$d01_perc)/100) ;
								 $valor_contri = ($valor_normal * ($d41_testada+$d41_eixo)*$d02_profun)*$d04_mult;	       
							}else{
								 /*
								 valor normal    = vlrcal - d01_perc%
								 valor normal    = 25 - 33.33% = 16.67

								 valor normal    = valor normal * d02_profun * testada
								 valor normal    = 16.67 * 5 * 13 = 1083.55
								 */              
								 $valor_normal = $d04_vlrcal - (($d04_vlrcal*$d01_perc)/100) ;
								 
								 $valor_contri = ($valor_normal * ($d41_testada+$d41_eixo) * $d02_profun);
							} 
						} else {
								if ($d04_forma == 1) {
									 $valor_normal = $d04_vlrcal - (($d04_vlrcal*$d01_perc)/100) ;
									 $valor_contri = ($valor_normal * ($d41_testada+$d41_eixo) * $d02_profun);
								} elseif ($d04_forma == 2) {
									 $valor_normal = $d04_vlrval - (($d04_vlrval*$d01_perc)/100) ;
									 $valor_contri = ($valor_normal * ($d41_testada+$d41_eixo)*$d02_profun)*$d04_mult;
								} elseif ($d04_forma == 3) {
									 $valor_normal = $d04_vlrcal - (($d04_vlrcal*$d01_perc)/100) ;
									 $valor_contri = ($valor_normal * (($d41_testada+$d41_eixo)/$total_testada*$d04_quant)*$d02_profun*2)*$d04_mult;
								}
						}
	          $valor_contri = number_format($valor_contri,"2",".","");
	    
            $clcontlotv->d06_contri=$d02_contri;
            $clcontlotv->d06_idbql=$j01_idbql;
            $clcontlotv->d06_tipos=$d04_tipos;
            $clcontlotv->d06_fracao=$d41_testada+$d41_eixo;
	    $clcontlotv->d06_valor = $valor_contri;
            $clcontlotv->incluir($d02_contri,$j01_idbql,$d04_tipos);
            if($clcontlotv->erro_status=='0'){
              $clcontlotv->erro(true,false);
              $sqlerro = true;
              break;
            }
  	  }  
        }
      }
      $results=$cleditalruaproj->sql_record($cleditalruaproj->sql_query($d02_contri,"","d11_codproj"));
      if($cleditalruaproj->numrows>0){
        $cleditalruaproj->d11_contri=$d02_contri;
        $cleditalruaproj->excluir($d02_contri);
        if($cleditalruaproj->erro_status=='0'){
           $sqlerro = true;
        }
      } 
      if(isset($d40_codigo)){
        $cleditalruaproj->d11_contri=$d02_contri;
        $cleditalruaproj->d11_codproj= $d40_codigo;
        $cleditalruaproj->incluir($d02_contri,$d40_codigo);
        if($cleditalruaproj->erro_status=='0'){
           $sqlerro = true;
         }
      }
      db_fim_transacao($sqlerro);
    }  
}else if(isset($chavepesquisa) && !isset($numedital)){
   $db_opcao = 2;
   $result = $cleditalrua->sql_record($cleditalrua->sql_query($chavepesquisa)); 
   db_fieldsmemory($result,0);

   $result = $cleditalserv->sql_record($cleditalserv->sql_query($d02_contri,"","d04_tipos,d04_quant,d04_vlrcal,d04_vlrval,d04_mult,d04_forma,d03_descr,d04_vlrobra")); 
   $numi= $cleditalserv->numrows;
   $dados="";
  for($x=0;$x<$numi;$x++){
    db_fieldsmemory($result,$x);
    $dados .= $d04_tipos."-".$d04_quant."-".$d04_vlrcal."-".$d03_descr."-".$d04_vlrval."-".$d04_mult."-".$d04_forma."-".$d04_vlrobra."XX";
  }
  $result05 = $cleditalruaproj->sql_record($cleditalruaproj->sql_query($chavepesquisa)); 
  if($cleditalruaproj->numrows>0){  
    db_fieldsmemory($result05,0);
  }
							      
 $numedital=$d02_codedi;

 $clcontlot->sql_record($clcontlot->sql_query($d02_contri,"","d05_contri"));
 if($clcontlot->numrows>0){
   $db_botao = false;
   $db_opcao="22";
   $naopod="nao";
 }else{
   $db_botao = true;
 }  
}else if(isset($numedital)){
   $db_opcao = 2;
   $result = $cleditalserv->sql_record($cleditalserv->sql_query($d02_contri,"","d04_tipos,d04_quant,d04_vlrcal,d04_vlrval,d04_mult,d04_forma,d03_descr,d04_vlrobra")); 
   $numi= $cleditalserv->numrows;
   $dados="";
  for($x=0;$x<$numi;$x++){
    db_fieldsmemory($result,$x);
    $dados .= $d04_tipos."-".$d04_quant."-".$d04_vlrcal."-".$d03_descr."-".$d04_vlrval."-".$d04_mult."-".$d04_forma."-".$d04_vlrobra."XX";
  }
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmeditalruaalt.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if($cleditalrua->erro_status=="0"){
  $cleditalrua->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cleditalrua->erro_campo!=""){
    echo "<script> document.form1.".$cleditalrua->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$cleditalrua->erro_campo.".focus();</script>";
  };
}else{
  $cleditalrua->erro(true,true);
};
if(isset($naopod)){
  db_msgbox("Alteração não permitida. Os lotes já foram selecionados.");
}
?>