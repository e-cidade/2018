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
include("classes/db_issvar_classe.php");
include("classes/db_numpref_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_issvarnotas_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_cadvenc_classe.php");
include("classes/db_arrecad_classe.php");
include("classes/db_parissqn_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_arrecant_classe.php");
include("classes/db_arreinscr_classe.php");
include("classes/db_arrenumcgm_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$clissvar      = new cl_issvar;
$clissvarnotas = new cl_issvarnotas;
$clissbase     = new cl_issbase;
$clcgm         = new cl_cgm;
$clcadvenc     = new cl_cadvenc;
$clparissqn    = new cl_parissqn;
$clarrecad     = new cl_arrecad;
$clarrecant    = new cl_arrecant;
$clarrenumcgm  = new cl_arrenumcgm;
$clarreinscr   = new cl_arreinscr;
$clnumpref     = new cl_numpref;
$cliframe_alterar_excluir_html = new cl_iframe_alterar_excluir_html;
$db_botao = true;
$db_opcao=3;
//////////////////////////////////////////////////INICIA ALTERAÇÃO////////////////////////////////////////////////
if(isset($excluir)){
 $sqlerro=false;
 db_inicio_transacao();


  $result10=$clissvar->sql_record($clissvar->sql_query_file($q05_codigo,"q05_numpre,q05_numpar"));
  db_fieldsmemory($result10,0);
  if(!$sqlerro){
    $result88=$clissvarnotas->sql_record($clissvarnotas->sql_query_file($q05_codigo,"","q06_codigo","1=1 limit 1"));
    if($clissvarnotas->numrows>0){
      $clissvarnotas->q06_codigo=$q05_codigo;  
      $clissvarnotas->excluir($q05_codigo); 
      if($clissvarnotas->erro_status==0){
        $erromsg = $clissvarnotas->erro_msg;
        $sqlerro=true;
      }
    }
  }


  if(!$sqlerro){
      $result25=$clarrecant->sql_record($clarrecant->sql_query_file("","k00_numpre,k00_numpar","","k00_numpre=$q05_numpre and k00_numpre=$q05_numpar"));  
      if($clarrecant->numrows>0){
        //se entrar aqui, sera porque ja já foi pago e se já tiver sido naum poderia ter aparecido este codigo de issvar quando foi pesquisado para alterar
        $erromsg="Exclusão Abortada!!Issqn complementar não está em aberto!!"; 
        $sqlerro=true; 	
      }else{
        $clarrecad->k00_numpre=$q05_numpre;
        $clarrecad->k00_numpar=$q05_numpar;
        $clarrecad->excluir_arrecad($q05_numpre,$q05_numpar);
        //$clarrecad->erro(true,false);
        if($clarrecad->erro_status=="0"){
          $erro=$clarrecad->erro_msg; 
          $sqlerro=true; 	
        }
     }
  
 if(!$sqlerro && isset($q02_inscr)){
    $result30=$clarrecad->sql_record($clarrecad->sql_query_file(null,"*",null,"arrecad.k00_numpre=$q05_numpre  "));
    $iNumrows=$clarrecad->numrows;
   if ($iNumrows==0){
       $clarreinscr->k00_numpre=$q05_numpre;
       $clarreinscr->k00_inscr=$q02_inscr;
       $clarreinscr->excluir($q05_numpre,$q02_inscr);
    //$clarreinscr->erro(true,false);
      if($clarreinscr->erro_status==0){
         $erromsg = $clarreinscr->erro_msg;
         $sqlerro=true;
    }
   }
  } 
  
  
  
  
  
  }  
  if(!$sqlerro){
    $clissvar->q05_numpar=$q05_numpar;
    $clissvar->q05_numpre=$q05_numpre;
    $clissvar->q05_codigo=$q05_codigo;
    $clissvar->excluir($q05_codigo,"q05_numpar=$q05_numpar and q05_numpre=$q05_numpre");
    //$clissvar->erro(true,false);
    if($clissvar->erro_status==0){
      $erromsg = $clissvar->erro_msg;
      $sqlerro=true;
    }
  }
  if(!$sqlerro){
    $q05_valor="";
    $q05_bruto="";
    unset($q05_mes);
    unset($q05_histor);
    unset($z01_numcgm);
  }
 db_fim_transacao($sqlerro);
}
/////////////FINALIZA ALTERAÇÃO/////////////////////////////////////////////////////////////////////////////////////////
//die($q05_codigo);
if(empty($excluir)){

if(empty($entrar) && isset($q05_codigo) && $q05_codigo>0){//quando vier o código do ISSVAR
  $result99=$clissvar->sql_record($clissvar->sql_query_file($q05_codigo));
  db_fieldsmemory($result99,0);
  $sql_codigo=$clissvarnotas->sql_query_file($q05_codigo,"","q06_nota,q06_valor","q06_seq");
}else{
  if(isset($entrar) && isset($z01_numcgm) && $z01_numcgm>0){//quando for digitado numcgm 
      $result03 = $clissbase->sql_record($clissbase->sql_query_file("","issbase.q02_inscr","q02_inscr","q02_numcgm=$z01_numcgm"));
      if($clissbase->numrows>0){//se o cgm tiver inscrições
          $result02=$clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome,z01_munic"));  
          db_fieldsmemory($result02,0);
          $varios=true;
      }else{
         $result65=$clarrenumcgm->sql_record($clarrenumcgm->sql_query_file($z01_numcgm,"","k00_numpre"));
         $numrows65=$clarrenumcgm->numrows;
      }
      if($z01_nome==""){
        $result01=$clcgm->sql_record($clcgm->sql_query($z01_numcgm,"z01_nome"));
        db_fieldsmemory($result01,0); 
      }  
  }else if(isset($entrar) && isset($q02_inscr) && $q02_inscr>0){//quando for digiado inscrição
      $result65=$clarreinscr->sql_record($clarreinscr->sql_query("",$q02_inscr,"k00_numpre,z01_nome"));
      $numrows65=$clarreinscr->numrows;
  }  
  ///////////////////////////////////////////////////////////////////////////////////////////  
    //$varios é setado quando há varias inscrições para um cgm  
  if(empty($varios) && empty($excluir)){
    if($numrows65<1){//se não encontrar nenhum registro no arreinscr, volta para a pagina inicial
        if(isset($z01_numcgmx)){
           db_redireciona("iss1_issvar016.php?inscr=$q02_inscr&z01_numcgm=$z01_numcgmx&entrar=denovo");
        }else{   
           if(isset($entrar) && isset($z01_numcgm) && $z01_numcgm>0){//quando for digitado numcgm 
             db_redireciona("iss1_issvar003.php?z01_nome=$z01_nome&registrocgm=invalido&z01_numcgm=$z01_numcgm");
            }else{  
              db_redireciona("iss1_issvar003.php?z01_nomeinscr=$z01_nomeinscr&registro=invalido&q02_inscr=$q02_inscr");
           }
        }  
    }else{
      $codigos=array();
      $codigos_pagos=array();
      for($i=0;$i<$numrows65;$i++){//numero de registro encontrados no arreinscr
        db_fieldsmemory($result65,$i);
        $result77=$clissvar->sql_record($clissvar->sql_query("","q05_codigo","","q05_numpre=$k00_numpre"));
        if($clissvar->numrows>0){//se encontrar registro no ISSVAR
            db_fieldsmemory($result77,0);
            $clarrecant->sql_record($clarrecant->sql_query_file("","k00_numpre","","k00_numpre=$k00_numpre"));
            if($clarrecant->numrows>0){//se já tiver sido pago entra aqui
              $codigos_pagos[$q05_codigo]=$q05_codigo;
            }else{//os numpres que ainda não tiveram nenhuma parcela paga  
              $clarrecad->sql_record($clarrecad->sql_query_file_instit("","arrecad.k00_numpre","","arrecad.k00_numpre=$k00_numpre and k00_instit = ".db_getsession('DB_instit') ));
              if($clarrecad->numrows>0){
                $codigos[$q05_codigo]=$q05_codigo;
      	      }  
            }   
        }
      }
      if(sizeof($codigos)==0 && sizeof($codigos_pagos)==0){//se  nehum registro for encontrado no ISSVAR, volta para a pagina inicial
          if(isset($z01_numcgmx)){
             db_redireciona("iss1_issvar016.php?inscr=$q02_inscr&z01_numcgm=$z01_numcgmx&entrar=denovo");
          }else{   
           if(isset($entrar) && isset($z01_numcgm) && $z01_numcgm>0){//quando for digitado numcgm 
             db_redireciona("iss1_issvar003.php?z01_nome=$z01_nome&registrocgm=invalido&z01_numcgm=$z01_numcgm");
            }else{  
              db_redireciona("iss1_issvar003.php?z01_nomeinscr=$z01_nomeinscr&registro=invalido&q02_inscr=$q02_inscr");
           }
          }  
      }else if(sizeof($codigos)>0){//se tiver sido encontrado algum registro no ISSVAR que ainda não foi pago
          $varios_codigos=$codigos;
          if(sizeof($codigos)==1){
  	    reset($codigos);
  	    $chave=key($codigos);
            $unico_codigo=$codigos[$chave];
          }	
      }else if(sizeof($codigos_pagos)>0){//se tiver sido encontrado algum registro no ISSVAR que já foi pago
          db_redireciona("iss1_issvar003.php?z01_nomeinscr=$z01_nomeinscr&registro=pago&q02_inscr=$q02_inscr");
      }
    }
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
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
if(isset($varios) && $varios==true){//quando tiver várias inscrições para um cgm
$clrotulo= new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("q02_inscr");
      db_fieldsmemory($result03,0);
?>
<form name="form1" method="post" action="iss1_issvar016.php">
 <table>
  <tr>
    <td>
      <?=$Lz01_numcgm?>
    </td>
    <td>
    <?
    $z01_numcgmx=$z01_numcgm;
  db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',3,"","z01_numcgmx")
      ?>
    </td>
  </tr>  
  <tr>  
    <td> 
      <?=$Lz01_nome?>
    </td>
    <td>
    <?
  db_input('z01_nome',40,$Iz01_nome,true,'text',3,"")
      ?>
    </td>
  </tr>
  <tr>
    <td>
       <?=$Lq02_inscr?>
    </td>
    <td>
<?
    for($e=0; $e<$clissbase->numrows; $e++){
      db_fieldsmemory($result03,$e);
      $inscrs[$q02_inscr]=$q02_inscr;
    }
    if(isset($inscr)){
      global $q02_inscr;
      $q02_inscr=$inscr;
    }
   db_select("q02_inscr",$inscrs,true,$db_opcao,"","","","","");
?>
       </td>   	 
     </tr>	 
     <tr>
       <td  colspan="2" align="center">
          <input type="submit" name="entrar" value="Entrar">
          <input type="button" name="voltar" value="Voltar" onclick="js_voltar();">
       </td>
     </tr>  
   </table>  
 </form>
 <script>
   function js_voltar(){
     location.href="iss1_issvar003.php";
   }
   <?
   if(isset($entrar) && $entrar=="denovo"){
     echo  "alert('Nenhum registro de issqn variável encontrado para esta inscrição!')";
   }
   ?>
 </script>
<?
}else if(isset($varios_codigos)){//quando tiver vários códigos de ISSVAR para uma inscrição
  $clrotulo= new rotulocampo;
  $clrotulo->label("z01_nome");
  $clrotulo->label("z01_numcgm");
  $clrotulo->label("q02_inscr");
  $clrotulo->label("q05_codigo");
?> 	
<form name="form1" method="post" action="iss1_issvar016.php">
 <table>
<?   
    if(isset($unico_codigo)){
      if(isset($entrar) && isset($z01_numcgm) && $z01_numcgm>0){//quando for por numcgm 
         db_input('z01_numcgm',6,$Iz01_numcgm,true,'hidden',1,"");
      }else if(isset($entrar) && isset($q02_inscr) && $q02_inscr>0){//quando for por  inscrição
         db_input('q02_inscr',40,$Iq02_inscr,true,'hidden',1,"");
      } 
      db_input('z01_nome',40,$Iz01_nome,true,'hidden',1,"");
      $q05_codigo=$unico_codigo;
    }
    db_input('q05_codigo',6,0,true,'text',1);
?>    
<?   
  if(empty($unico_codigo)){
?>      
  <tr>
<?
if(isset($entrar) && isset($z01_numcgm) && $z01_numcgm>0){//quando for por numcgm 
?>
    <td nowrap title="<?=@$Tz01_numcgm?>" width="25%">
      <?=$Lz01_numcgm?>
    </td>
    <td nowrap title="<?=@$Tz01_numcgm?>">
<?
  db_input('z01_numcgm',6,$Iz01_numcgm,true,'text',3,"")
?>  

    </td>
<?
}else if(isset($entrar) && isset($q02_inscr) && $q02_inscr>0){//quando for por  inscrição
?>
    <td nowrap title="<?=@$Tq02_inscr?>">
      <?=$Lq02_inscr?>
    </td>
    <td nowrap title="<?=@$Tq02_inscr?>">
<?
  db_input('q02_inscr',6,$Iq02_inscr,true,'text',3,"")
?>  

    </td>
<?
}
?>
  </tr>  
  <tr>  
    <td nowrap title="<?=@$Tz01_nome?>">
      <?=$Lz01_nome?>
    </td>
    <td nowrap title="<?=@$Tz01_nome?>">
<?
  db_input('z01_nome',40,$Iz01_nome,true,'text',3,"");
  db_input('varios_codigos',40,"",true,'hidden',3,"");
?>  
    </td>
  </tr>
  <input type="button" name="voltar" value="Voltar" onclick="js_voltar();">
<?
if (!isset($filtroquery)){
    reset($varios_codigos);
    $codis = "";
    $vir   = "";
    for($e=0; $e<sizeof($varios_codigos); $e++){
      $chave =  key($varios_codigos);
      $codis .= $vir.$varios_codigos[$chave];
      next($varios_codigos);
      $vir = ",";
    }
    
    $sql = $clissvar->sql_query_file("","*","","q05_codigo in ($codis)");
    }

//    echo $sql;

    db_lovrot($sql,15,"()","","js_alterar|q05_codigo");
?>
     <?
     }
     ?>
   </table>  
 </form>
 <script>    
   function js_alterar(codigo){
//     alert(codigo);
     //document.form1.q05_codigo.value = codigo;
     document.getElementById('q05_codigo').value = codigo;
//     alert(document.form1.q05_codigo.value);
     document.form1.submit();
   }  
   function js_voltar(){
     location.href="iss1_issvar003.php";
   }
   <?
    if(isset($unico_codigo)){//quando tiver um unico codigo de issvar, ele já entra direto
      echo "document.form1.submit();";
    }  
   ?>   
 </script>    
<? 
}else{
  //echo $q05_codigo;
  include("forms/db_frmissvar.php");
}	
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
if(isset($excluir)){
  if($sqlerro){
     db_msgbox($erromsg);
  }else{
     db_msgbox("Processamento concluido com sucesso !");
  }
  db_redireciona("iss1_issvar003.php");
}  
?>