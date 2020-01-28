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
include("classes/db_testada_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_testpri_classe.php");
include("classes/db_face_classe.php");
include("classes/db_lote_classe.php");
include("classes/db_carlote_classe.php");
include("classes/db_lotedist_classe.php");
include("classes/db_setor_classe.php");
include("classes/db_loteam_classe.php");
include("classes/db_loteloc_classe.php");
include("classes/db_loteloteam_classe.php");
include("classes/db_lotesetorfiscal_classe.php");
include("classes/db_cfiptu_classe.php");
include("classes/db_testadanumero_classe.php");
include ("classes/db_tesinter_classe.php");
include ("classes/db_tesinterlote_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$cllotedist = new cl_lotedist;
$clface = new cl_face;
$cllote = new cl_lote;
$clloteloc = new cl_loteloc;
$clcarlote = new cl_carlote;
$cltestada = new cl_testada;
$cltestpri = new cl_testpri;
$clsetor = new cl_setor;
$clloteam = new cl_loteam;
$clloteloteam = new cl_loteloteam;
$cllotesetorfiscal = new cl_lotesetorfiscal;
$clcfiptu = new cl_cfiptu;
$cltestadanumero = new cl_testadanumero;

$cltesinterlote = new cl_tesinterlote;
$cltesinter = new cl_tesinter;

$rsResultmostra = $clcfiptu->sql_record($clcfiptu->sql_query_file(db_getsession('DB_anousu'),'*',"",""));
if ($clcfiptu->numrows>0){
	db_fieldsmemory($rsResultmostra,0);
	$mostrasetfiscal = $j18_utilizasetfisc;
	$numerotestada   = $j18_testadanumero;
}
$db_opcao = 1;
$db_botao = true;
$replote=false;
if(isset($incluir) || isset($alterar)){
  $mesmo=false; 
  
  $result = @$cllote->sql_record($cllote->sql_query("","j34_idbql as tidbql","","j34_setor= '$j34_setor' and j34_quadra='$j34_quadra' and j34_lote=$j34_lote" ));
  $numrows=$cllote->numrows;
  if($result!=false && $numrows!=0){
    if(isset($alterar)){
      for($xi=0; $xi<$numrows; $xi++){
        db_fieldsmemory($result,$xi);
        if($j34_idbql==$tidbql){ 
          $mesmo=true;  
          break;
        }
      }  
    }  
    if($mesmo==false){
      $replote=true; 
      if(isset($incluir)){
        unset($incluir);
        $repete="incluir";
        $db_opcao = 1;
      }else{
        unset($alterar);
        $repete="alterar"; 
        $db_opcao = 2;
      }  
    }  
  }  
}
if(isset($outrolote)&& $outrolote!=""){
 $$outrolote="ok";
}

if($replote==true){


}else if(isset($incluir)){
  db_inicio_transacao();
  $trans_erro=false;
  $j34_lote = str_pad($j34_lote, 4, "0", STR_PAD_LEFT);
  $cllote->j34_lote = $j34_lote;
 $cllote->incluir(null);
   
 if($cllote->erro_status == 0){
	 			db_msgbox("LOTE : ".$cllote->erro_msg);
	 			$trans_erro = true;
}else{
   
   
   $j34_idbql=$cllote->j34_idbql;   
//   die("select * from face where j37_face = $cartestpri");
   $resultado=pg_exec("select * from face where j37_face = $cartestpri");
   $j37_codigo=pg_result($resultado,0,3);  
   $cltestpri->j49_face=$cartestpri;
   $cltestpri->j49_codigo=$j37_codigo;
   $cltestpri->incluir($cllote->j34_idbql,$cartestpri);
       
   if($cltestpri->erro_status=="0"){
     db_msgbox("TESTPRI : ".$cltestpri->erro_msg);
     $trans_erro=true;
   }  
   
   if($j34_loteam!=""){
      $result = $clloteam->sql_record($clloteam->sql_query($j34_loteam,"j34_loteam"));
      $numrows=$clloteam->numrows;
      if($numrows>=1){
         $clloteloteam->j34_idbql=$j34_idbql;
         $clloteloteam->j34_loteam=$j34_loteam;
         $clloteloteam->incluir($j34_idbql,$j34_loteam);
         if($clloteloteam->erro_status=="0"){
           db_msgbox("LOTELOTEAM : ".$clloteloteam->erro_msg);
           $trans_erro=true;
        }  
      }
  } 

  /*============ TESTADAS INTERNAS ============== */
/*
 // COMENTADO POR Karina ATE A CONCLUSAO PARA TAREFA 10400  
       // descomentar para conclusao da tarefa das testadas internas 
 
	 $matriztesinter = split("X", $testadainter);
	 foreach ($matriztesinter as $valor) {
	 	$dadosTestadaInterna = split("-", $valor);
	 	$idbqlInterLote = $dadosTestadaInterna[0];
	 	$j39_idbql      = $cllote->j34_idbql; 
	 	$j39_orientacao = $dadosTestadaInterna[1];
	 	$j39_testad     = $dadosTestadaInterna[2];
     $j39_testle     = $dadosTestadaInterna[3];
     if (($j39_testad != "0" && $j39_testad != "") || ($j39_testle != "0" && $j39_testle != "")) {
	 		$cltesinter->j39_idbql      = $j39_idbql;
	 		$cltesinter->j39_orientacao = $j39_orientacao;
	 		$cltesinter->j39_testad     = $j39_testad;
	 		$cltesinter->j39_testle     = $j39_testle;
	 		$cltesinter->incluir(null);
	 		if($cltesinter->erro_status == 0){
	 			db_msgbox("TESINTER : ".$cltesinter->erro_msg);
	 			$trans_erro = true;
	 		}
	 		if (isset($idbqlInterLote) && $idbqlInterLote <> 0){
	 			$cltesinterlote->j69_tesinter = $cltesinter->j39_sequencial; 
	 			$cltesinterlote->j69_idbql    = $idbqlInterLote;
	 			$cltesinterlote->incluir($cltesinter->j39_sequencial);
	 			if($cltesinterlote->erro_status == 0){
	 			  db_msgbox("TESINTERLOTE :".$cltesinterlote->erro_msg);
	 			  $trans_erro = true;
	 				
	 			}
	 		}
	 	}
	 }
*/
// 	exit;
	 //=============================================


  $matriztesta = explode("x", $cartestada);
  for ($i = 0; $i < sizeof($matriztesta); $i++) {
	$dados = $matriztesta[$i];
	$matrizdados = explode("||", $dados);
    
     $j37_face=$matrizdados[0];   
     $j14_codigo=$matrizdados[1];   
     $j36_testad=$matrizdados[2];   
     $j36_testle=$matrizdados[3];
     
//==============================================================     
     $j15_numero = $matrizdados[4];
     $j15_compl  = $matrizdados[5];
//==============================================================    
          
     if($j36_testad!="0" ||  $j36_testle!="0"){ 
       $cltestada->j36_idbql= $cllote->j34_idbql;
       $cltestada->j36_face=$j37_face;
       $cltestada->j36_codigo=$j14_codigo;
       $cltestada->j36_testad=$j36_testad;
       $cltestada->j36_testle=$j36_testle;
       $cltestada->incluir($cllote->j34_idbql,$j37_face);
       if($cltestada->erro_status=="0"){
         $trans_erro=true;
         db_msgbox("TESTADA : ".$cltestada->erro_msg);
            	
       }  
     }  
     
//================================================================================================
     if(isset($numerotestada) && $numerotestada=='t'){
		 if((isset($j15_numero) && $j15_numero != "") || (isset($j15_compl) && $j15_compl != "")){	 	
	       $cltestadanumero->j15_idbql  = $cllote->j34_idbql;
	       $cltestadanumero->j15_face   = $j37_face;
	       $cltestadanumero->j15_compl  = $j15_compl;
	  //     $cltestadanumero->j15_obs  = "teste";
	       $cltestadanumero->j15_numero = $j15_numero;
	       $cltestadanumero->incluir("");
	       if($cltestadanumero->erro_status=="0"){
              $trans_erro=true;
              db_msgbox("TESTADANUMERO : ".$cltestadanumero->erro_msg);
            	
           }      
	     }
     }
//================================================================================================   
   }
   
   $j34_idbql=$cllote->j34_idbql;
   $clcarlote->j35_idbql=$j34_idbql;
   $matriz= split("X",$caracteristica);
   for($i=0;$i<sizeof($matriz);$i++){
     $j35_caract = $matriz[$i];
     if($j35_caract!=""){
       $clcarlote->incluir($j34_idbql,$j35_caract);
       if($clcarlote->erro_status=="0"){
         db_msgbox("CARLOTE : ".$clcarlote->erro_msg);
         $trans_erro=true;
	     break;
       }  
     }  
   }
  
   if($j54_codigo!=""&&$j54_distan!=""&&$j54_ponto!=""){
     $cllotedist->j54_idbql = $cllote->j34_idbql; 
     $cllotedist->j54_codigo = $j54_codigo; 
     $cllotedist->j54_distan = $j54_distan; 
     //$cllotedist->j54_orientacao = $j54_orientacao;
	 $cllotedist->j54_ponto = $j54_ponto;
     $cllotedist->incluir($j34_idbql);
     if($cllotedist->erro_status=="0"){
       $trans_erro=true;
       db_msgbox("LOTEDIST : ".$cllotedist->erro_msg);
     }  
   }
   //  INCLUSAO  NA TABELA LOTESETORFISCAL
   if(isset($j91_codigo) && $j91_codigo!=""){
     $cllotesetorfiscal->j91_idbql = $cllote->j34_idbql; 
     $cllotesetorfiscal->j91_codigo = $j91_codigo; 
     $cllotesetorfiscal->incluir();
     if($cllotesetorfiscal->erro_status=="0"){
       $trans_erro=true;
       db_msgbox("lotesetorfiscal : ".$cllotesetorfiscal->erro_msg);
     }
   }
 }
 
 db_fim_transacao($trans_erro);
 //============================================
 //INCLUSAO LOTELOC
 if(isset($j06_setorloc) && $j06_setorloc != ""){
  $clloteloc->j06_idbql = $cllote->j34_idbql;
  $clloteloc->incluir($cllote->j34_idbql);
  if($clloteloc->erro_status=="0"){
   $trans_erro=true;
   db_msgbox("loteLOC : ".$clloteloc->erro_msg);
  }
 }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.j34_setor.focus();" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	include("forms/db_frmlote.php");
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
if($replote==true){
 
 echo "<script>";
 if($repete=="incluir"){        
   echo "var confirma=confirm('Este Lote j? foi cadastrado! Deseja cadastrar outro?');";
 }else{
   echo "var confirma=confirm('Este Lote j? foi cadastrado! Deseja continuar a altera??o?');";
   
 }   
 echo "if(confirma){\n
         document.form1.outrolote.value='$repete'; \n
         document.form1.submit(); \n
       }\n
      ";  	  
  echo "</script>";
       exit;

}
/*
if($cllote->erro_status=="0"){
  $cllote->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($cllote->erro_campo!=""){
    echo "<script> document.form1.".$cllote->erro_campo.".style.backgroundColor='#99A9AE';</script>";
    echo "<script> document.form1.".$cllote->erro_campo.".focus();</script>";
  };
}else{
  $cllote->erro(true,false);
};
*/
if(isset($incluir) || isset($alterar)){
	if($trans_erro==false){
	  $cllote->erro(true,true);
	}
}
?>