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
include("classes/db_iptubase_classe.php");
include("classes/db_setor_classe.php");


db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);


$cllotedist = new cl_lotedist;
$clface = new cl_face;
$cllote = new cl_lote;
$clcarlote = new cl_carlote;
$cltestada = new cl_testada;
$cltestpri = new cl_testpri;
$cliptubase = new cl_iptubase;
$clsetor = new cl_setor;

$cllote->rotulo->label();
$cllotedist->rotulo->label();
$clcarlote->rotulo->tlabel();
$cltestada->rotulo->tlabel();
$cllotedist->rotulo->tlabel();
$clrotulo = new rotulocampo;
$clrotulo->label("j30_descr");
$clrotulo->label("j13_descr");
$clrotulo->label("j14_nome");

$cllotedist->rotulo->tlabel();



if(isset($incluquadra) && $incluquadra!="" ){
  $resulta=$clsetor->sql_record($clsetor->sql_query($j34_setor,"j30_descr"));
  db_fieldsmemory($resulta,0);
  $db_opcao = $incluquadra;
}else{
  $db_opcao = 1;
}
$db_botao = true;
$selface = false;
$testasetor=false; 
$replote=false; 

if(isset($j01_matric)){
  $idmatricu=$j01_matric;
}


if(isset($incluir) || isset($alterar)){
  $mesmo=false;  
  $result = @$cllote->sql_record($cllote->sql_query("","j34_idbql as tidbql","","j34_setor= $j34_setor and j34_quadra=$j34_quadra and j34_lote=$j34_lote" ));
  $numrows=$cllote->numrows;
  if($result!=false && $numrows!=0){
    if(isset($alterar)){
      for($xi=0; $xi<$numrows; $xi++){
        db_fieldsmemory($result,$xi);
        if($j34_idbql==$tidbql){ 
          $mesmo=true;  
          break;
           //proseguir sem mensagem
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
  
  
}else if(isset($j34_setor)&& !isset($incluir) && !isset($alterar)){
   $resultface = $clface->sql_record($clface->sql_query("","distinct j37_quadra","","j37_setor=$j34_setor"));
   $clface->numrows==0;
   $selface = true;
}else if(isset($incluir)){
  db_inicio_transacao();
 if($cllote->incluir(null)==true){
   $j34_idbql=$cllote->j34_idbql;

  if($idmatricu!=""){
    $cliptubase->j01_idbql = $j34_idbql;
    $cliptubase->j01_matric = $idmatricu;
    $cliptubase->alterar($idmatricu);
  }
  $resultado=pg_exec("select * from face where j37_face = $cartestpri");
   $j37_codigo=pg_result($resultado,0,3);
   $cltestpri->j49_face=$cartestpri;
   $cltestpri->j49_codigo=$j37_codigo;
   $cltestpri->incluir($cllote->j34_idbql,$cartestpri);

   $matriztesta= split("X",$cartestada);
   for($i=0;$i<sizeof($matriztesta);$i++){
     $dados=$matriztesta[$i];
     $matrizdados= split("-",$dados);
 
     $j37_face=$matrizdados[0];   
     $j14_codigo=$matrizdados[1];   
     $j36_testad=$matrizdados[2];   
     $j36_testle=$matrizdados[3];   
    
     if($j36_testad!="" && $j36_testle!=""){
       $cltestada->j36_idbql= $cllote->j34_idbql;
       $cltestada->j36_face=$j37_face;
       $cltestada->j36_codigo=$j14_codigo;
       $cltestada->j36_testad=$j36_testad;
       $cltestada->j36_testle=$j36_testle;
       $cltestada->incluir($cllote->j34_idbql,$j37_face);
     }  
   }
   
   $j34_idbql=$cllote->j34_idbql;
   $clcarlote->j35_idbql=$j34_idbql;
   $matriz= split("X",$caracteristica);
   for($i=0;$i<sizeof($matriz);$i++){
     $j35_caract = $matriz[$i];
     if($j35_caract!=""){
      $clcarlote->incluir($j34_idbql,$j35_caract);
     }  
   }	
   if($j54_codigo!=""&&$j54_distan!=""&&$j54_ponto!=""){ 
     $cllotedist->j54_idbql = $cllote->j34_idbql; 
     $cllotedist->j54_codigo = $j54_codigo; 
     $cllotedist->j54_distan = $j54_distan; 
     $cllotedist->j54_ponto = $j54_ponto;
     $cllotedist->incluir($j34_idbql);
    } 
 }
   db_fim_transacao();
}else if(isset($alterar)){

  db_inicio_transacao();


  
  if($idmatricu!=""){
    $cliptubase->j01_idbql = $j34_idbql;
    $cliptubase->j01_matric = $idmatricu;
    $cliptubase->alterar($idmatricu);
  }
   
  $cllote->alterar($j34_idbql);
  if($cllote->erro_status==1){   


  $result = $clcarlote->sql_record($clcarlote->sql_query_file($j34_idbql));
  $xx=$clcarlote->numrows;
  for($i=0; $i<$xx; $i++){
    db_fieldsmemory($result,$i);
    $clcarlote->j35_idbql = $j35_idbql;
    $clcarlote->j35_caract = $j35_caract;
    $clcarlote->excluir($j35_idbql,$j35_caract);

  }

 if($clcarlote->erro_status==1){   

    $result = $cltestada->sql_record($cltestada->sql_query($j34_idbql));
    $xx = $cltestada->numrows;
    for($i=0; $i<$xx; $i++){
      db_fieldsmemory($result,$i);
      $cltestada->j36_idbql = $j36_idbql;
      $cltestada->j36_face = $j36_face;
      $cltestada->excluir($j36_idbql,$j36_face);
    }
    $cltestpri->j49_idbql=$j34_idbql;
    $cltestpri->excluir($j34_idbql);
  }
    $result=$clface->sql_record($clface->sql_query_file("","j37_codigo","","j37_face=$cartestpri"));
    $num=pg_numrows($result);
    if($num!=0){

     db_fieldsmemory($result,0);
  
     $cltestpri->j49_face=$cartestpri;
     $cltestpri->j49_codigo=$j37_codigo;
     $cltestpri->incluir($j34_idbql,$cartestpri);

    }
	
    $matriztesta= split("X",$cartestada);
    for($i=0;$i<sizeof($matriztesta);$i++){
      $dados=$matriztesta[$i];
      $matrizdados= split("-",$dados);

      $j37_face=$matrizdados[0];   
      $j14_codigo=$matrizdados[1];   
      $j36_testad=$matrizdados[2];   
      $j36_testle=$matrizdados[3];   
     
     if($j36_testad!="" && $j36_testle!=""){
       $cltestada->j36_idbql= $cllote->j34_idbql;
       $cltestada->j36_face=$j37_face;
       $cltestada->j36_codigo=$j14_codigo;
       $cltestada->j36_testad=$j36_testad;
       $cltestada->j36_testle=$j36_testle;
       $cltestada->incluir($cllote->j34_idbql,$j37_face);
     }  
   }

    $j34_idbql=$cllote->j34_idbql;
    $clcarlote->j35_idbql=$j34_idbql;
    $matriz= split("X",$caracteristica);
    for($i=1;$i<sizeof($matriz);$i++){
     $j35_caract = $matriz[$i]; 
     if($j35_caract!=""){
       $clcarlote->j35_caract=$j35_caract;
       $clcarlote->incluir($j34_idbql,$j35_caract);
     }  
  
    if($j54_codigo!=""&&$j54_distan!=""&&$j54_ponto!=""){ 
      $cllotedist->j54_idbql=$j34_idbql;
      $cllotedist->excluir($j34_idbql);
      $cllotedist->j54_idbql = $cllote->j34_idbql; 
      $cllotedist->j54_codigo = $j54_codigo; 
      $cllotedist->j54_distan = $j54_distan; 
      $cllotedist->j54_ponto = $j54_ponto;
      $cllotedist->incluir($j34_idbql);
    }
  }
}
   db_fim_transacao();
   $db_opcao = 2;


}else if(isset($j34_idbql) || isset($alterando) || isset($chavepesquisa) && !isset($incluquadra)){


  if(isset($chavepesquisa)){
    $j34_idbql = $chavepesquisa; 
  }

  if(isset($alterando)){
    $result=$cliptubase->sql_record($cliptubase->sql_query("","j01_idbql","","j01_matric=$j01_matric"));
    db_fieldsmemory($result,0);

    $result=$cllote->sql_record($cllote->sql_query($j01_idbql,"j34_idbql","",""));
    db_fieldsmemory($result,0);
  }


   $result = $cllote->sql_record($cllote->sql_query($j34_idbql));
   db_fieldsmemory($result,0);
   
   $testasetor=true; 

   $result = $cllotedist->sql_record($cllotedist->sql_query($j34_idbql));
   if($cllotedist->numrows!=0){
     db_fieldsmemory($result,0);
   }else{
     $j54_codigo="";
     $j54_distan="";
     $j54_ponto="";
     $j14_nome="";
   }
   $result = $cltestpri->sql_record($cltestpri->sql_query_file($j34_idbql));
   if($result==false){
     //echo "Não foi cadastrado nenhum rua principal, na tabela testpri...";
   }else{
     db_fieldsmemory($result,0);
     $cartestpri=$j49_face;
   } 
   $result = $cltestada->sql_record($cltestada->sql_query_file($j34_idbql));

   $cartestada = null;
   $cart="";
   for($i=0; $i < $cltestada->numrows; $i++){
     db_fieldsmemory($result,$i);
     $cartestada .= $cart.$j36_face."-".$j36_codigo."-".$j36_testad."-".$j36_testle;
     $cart="X   ";
   }

   $result = $clcarlote->sql_record($clcarlote->sql_query($j34_idbql));
   $caracteristica = null;
   $car="X";
   for($i=0; $i<$clcarlote->numrows; $i++){
     db_fieldsmemory($result,$i);
     $caracteristica .= $car.$j35_caract ;
     $car="X";
   }
   $caracteristica .= $car;
   $db_opcao = 2;

   $db_botao = true;
}
if(isset($j34_setor)&& $j34_setor==""){
  $j30_descr="";
  
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
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('load').style.visibility='hidden'">
<table width="790" border="0" cellspacing="0" cellpadding="0">
<form name="form1" method="post" action="" onSubmit="return js_verifica_campos_digitados();"   >
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <input type="hidden" name="outrolote">
    <center>
     <?

     include("forms/db_frmlotealt.php");     
     ?>  
    </center>
    </td>
  </tr>
</form>
</table>
</body>
</html>
<?
if($replote==true){
 
 echo "<script>";
 if($repete=="incluir"){        
   echo "var confirma=confirm('Este Lote já foi cadastrado!  Deseja cadastrar outro?');";
 }else{
   echo "var confirma=confirm('Este Lote já foi cadastrado!  Deseja continuar a alteração?');";
   
 }   
 echo "if(confirma){\n
         document.form1.outrolote.value='$repete'; \n
         document.form1.submit(); \n
       }\n
      ";  	  
  echo "</script>";
       exit;


}
if(isset($incluir) || isset($alterar)){
  if($cllote->erro_status=="0"){
    $cllote->erro(true,false);
    if($cllote->erro_campo!=""){
      echo "<script> document.form1.".$cllote->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cllote->erro_campo.".focus();</script>";
    }
  }else{
     $cllote->erro(true,false);
        echo "<script>
         parent.document.form1.idlote.value='".$j34_idbql."'; \n
         parent.document.form1.idsetor.value='".$j34_setor."'; \n
         parent.document.form1.idquadra.value='".$j34_quadra."'; \n
         parent.js_parentiframe('lote',true); 
 
         </script>
        ";
      if(isset($idmatricu)){
        db_redireciona("cad1_lotealt.php?j34_idbql=$j34_idbql&idmatricu=$idmatricu");
      }else{
        db_redireciona("cad1_lotealt.php?j34_idbql=$j34_idbql");
      } 
  }
}
  if(isset($chavepesquisa)&& !isset($incluquadra)){
    if($idmatricu!=""){
      $cliptubase->j01_idbql = $j34_idbql;
      $cliptubase->j01_matric = $idmatricu;
      $cliptubase->alterar($idmatricu);
    }
        echo "<script>
         parent.document.form1.idlote.value='".$j34_idbql."'; \n
         parent.document.form1.idsetor.value='".$j34_setor."'; \n
         parent.document.form1.idquadra.value='".$j34_quadra."'; \n
         parent.js_parentiframe('lote',true); 
         </script>
        ";
  }
  if(isset($alterando)|| isset($novolote)){
        echo "<script>
         parent.document.form1.idsetor.value='".$j34_setor."'; \n
         parent.document.form1.idquadra.value='".$j34_quadra."';";
            
          echo " parent.js_parentiframe('alterando',true); 
          </script>
        ";
  }
  
?>