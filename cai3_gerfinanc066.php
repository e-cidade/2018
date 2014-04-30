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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
include ("dbforms/db_funcoes.php");
require ("classes/db_arrejust_classe.php");
require ("classes/db_arrejustreg_classe.php");
require ("classes/db_numpref_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

if (isset($oPost->H_DATAUSU)) {
  $sDataVenc = date("Y-m-d",$oPost->H_DATAUSU); 
}

//db_postmemory($HTTP_POST_VARS,2);
$clarrejust = new cl_arrejust;
$clarrejustreg = new cl_arrejustreg;
$clnumpref = new cl_numpref;
$clarrejust->rotulo->label("k27_obs");
$clarrejust->rotulo->label("k27_dias");
$db_opcao = 1;
$sqlerro = false;
$result1 = $clnumpref->sql_record($clnumpref->sql_query('','','k03_diasjust','','k03_anousu ='.db_getsession("DB_anousu")." 
                                   and k03_instit=".db_getsession("DB_instit")));
db_fieldsmemory($result1,0);
$k27_dias = $k03_diasjust;
if ( (isset ($ver_matric) or isset ($ver_inscr) or isset ($ver_numcgm)) ) {
 $vt = $HTTP_POST_VARS;
 $virgula = "";
 $numpar1 = "";
 $numpre1 = "";
// print_r($vt);
if (isset($inicial) && $inicial == true) {
	
  foreach ($vt as $i => $v){
  //  echo "$i --- $v <br>";
    if (substr($i,0,5) == "CHECK"){
    	
      if (isset($oPost->marcarvencidas) && isset($oPost->marcartodas)) {
          
        if ($oPost->marcarvencidas == 'true' && $oPost->marcartodas == 'false') {

          $mat = split("N", $v);
          for ($iInd = 0; $iInd < count($mat); $iInd++) {
                
            if ($mat[$iInd] == "") {
              continue;   
            }
                
            $numpre = split("P", $mat[$iInd]);
            $numpar = split("P", strstr($mat[$iInd], "P"));
            $numpar = split("R",$numpar[1]);
            $receit = @$numpar[1];
            $numpar = $numpar[0];
            $numpre = $numpre[0];
              
            $sSqlArrecad  = "  select *                               ";
            $sSqlArrecad .= "    from arrecad                         "; 
            $sSqlArrecad .= "   where k00_numpre   = {$numpre}        "; 
            $sSqlArrecad .= "     and k00_numpar   = {$numpar}        ";
            $sSqlArrecad .= "     and k00_dtvenc   > '{$sDataVenc}'   ";
              
            $rsSqlArrecad = db_query($sSqlArrecad);
            $iNumRows     = pg_num_rows($rsSqlArrecad);
            if ($iNumRows == 0) {
                  
              $numpar1 .= $virgula.$numpar;
              $numpre1 .= $virgula.$numpre;
              $virgula = ",";
            }
            
          }
        } else {
            
	        //    echo "if if ....  | $i --- $v<br>";
	        $rsnumpre = pg_query("select v59_numpre from inicialnumpre where v59_inicial = $v");
	        //    db_criatabela($rsnumpre);
	        $numrows  = pg_numrows($rsnumpre);
	        for($ii = 0; $ii < $numrows; $ii++ ){
	          db_fieldsmemory($rsnumpre,$ii);
	          $rsArrecad  = pg_query(" select distinct k00_numpre,k00_numpar from arrecad where k00_numpre = $v59_numpre "); 
	          $numArrecad = pg_numrows($rsArrecad);
	          for($ia = 0 ;$ia < $numArrecad; $ia++ ){
	            db_fieldsmemory($rsArrecad,$ia);
	            $numpar1 .= $virgula.$k00_numpar;
	            $numpre1 .= $virgula.$v59_numpre;
	            $virgula = ',';
	          }    
	        }
        }
      } else {
          
	      //    echo "if if ....  | $i --- $v<br>";
	      $rsnumpre = pg_query("select v59_numpre from inicialnumpre where v59_inicial = $v");
	      //    db_criatabela($rsnumpre);
	      $numrows  = pg_numrows($rsnumpre);
	      for($ii = 0; $ii < $numrows; $ii++ ){
	        db_fieldsmemory($rsnumpre,$ii);
	        $rsArrecad  = pg_query(" select distinct k00_numpre,k00_numpar from arrecad where k00_numpre = $v59_numpre "); 
	        $numArrecad = pg_numrows($rsArrecad);
	        for($ia = 0 ;$ia < $numArrecad; $ia++ ){
	          db_fieldsmemory($rsArrecad,$ia);
	          $numpar1 .= $virgula.$k00_numpar;
	          $numpre1 .= $virgula.$v59_numpre;
	          $virgula = ',';
	        }    
	      }
      }
    }
  }
}else{
  for ($i = 0; $i < count($vt); $i ++) {
  	
   if (db_indexOf(key($vt), "CHECK") > 0) {
   	
      if (isset($oPost->marcarvencidas) && isset($oPost->marcartodas)) {
          
        if ($oPost->marcarvencidas == 'true' && $oPost->marcartodas == 'false') {

          $numpres = $vt[key($vt)];
          $mat     = split("N", $numpres);
          for ($iInd = 0; $iInd < count($mat); $iInd++) {
                
            if ($mat[$iInd] == "") {
              continue;   
            }
                
            $numpre = split("P", $mat[$iInd]);
            $numpar = split("P", strstr($mat[$iInd], "P"));
            $numpar = split("R",$numpar[1]);
            $receit = @$numpar[1];
            $numpar = $numpar[0];
            $numpre = $numpre[0];
              
            $sSqlArrecad  = "  select *                               ";
            $sSqlArrecad .= "    from arrecad                         "; 
            $sSqlArrecad .= "   where k00_numpre   = {$numpre}        "; 
            $sSqlArrecad .= "     and k00_numpar   = {$numpar}        ";
            $sSqlArrecad .= "     and k00_dtvenc   > '{$sDataVenc}'   ";
              
            $rsSqlArrecad = db_query($sSqlArrecad);
            $iNumRows     = pg_num_rows($rsSqlArrecad);
            if ($iNumRows == 0) {
                  
              $numpar1 .= $virgula.$numpar;
              $numpre1 .= $virgula.$numpre;
              $virgula = ",";
            }
            
          }
        } else {
            
         $numpres = $vt[key($vt)];
         if ($numpres == "") continue;
         $mat     = split("N", $numpres);
         for ($j = 0; $j < count($mat); $j++) {
           if ($mat[$j] == "") continue;
           $numpre = split("P", $mat[$j]);
           $numpar = split("P", strstr($mat[$j], "P"));
           $numpar = split("R",$numpar[1]);
           $numpar = $numpar[0];
           if (!isset ($inicial)) {
            // $numpar = $numpar[1];
           }
           $numpre = $numpre[0];
           $numpar1 .= $virgula.$numpar;
           $numpre1 .= $virgula.$numpre;
           $virgula = ",";
         }
        }
      } else {
          
		     $numpres = $vt[key($vt)];
		     if ($numpres == "") continue;
		     $mat     = split("N", $numpres);
		     for ($j = 0; $j < count($mat); $j++) {
		       if ($mat[$j] == "") continue;
		       $numpre = split("P", $mat[$j]);
		       $numpar = split("P", strstr($mat[$j], "P"));
		       $numpar = split("R",$numpar[1]);
		       $numpar = $numpar[0];
		       if (!isset ($inicial)) {
		        // $numpar = $numpar[1];
		       }
		       $numpre = $numpre[0];
		       $numpar1 .= $virgula.$numpar;
		       $numpre1 .= $virgula.$numpre;
		       $virgula = ",";
		     }
      }     
     
   }
   next($vt);
  }
 }
}

if(isset($submit)){
 db_inicio_transacao();
 $clarrejust->k27_hora    = db_hora();
 $clarrejust->k27_data    = date("Y-m-d", db_getsession("DB_datausu"));
 $clarrejust->k27_usuario = db_getsession("DB_id_usuario");
 $clarrejust->k27_instit  = db_getsession("DB_instit");
 $clarrejust->incluir(null);
 if ($clarrejust->erro_status == 0) {
  $sqlerro = true;
 }
 $erro_msg = $clarrejust->erro_msg;
 
  $mat = split(",", $numpre);
  $mat1 = split(",", $numpar);

  for ($i = 0; $i < count($mat); $i ++) {
   $numpre = $mat[$i];
   $numpar = $mat1[$i];
   if ($sqlerro == false) {
     $sqlreceita = "select k00_numpre, k00_numpar,k00_receit 
                    from arrecad 
                    where k00_numpre= $numpre and k00_numpar = $numpar";
     $resultreceita = pg_query($sqlreceita);
     $linhasreceita = pg_num_rows($resultreceita);
     for($r=0;$r<$linhasreceita;$r++){
       db_fieldsmemory($resultreceita,$r);
	     //echo "<br>numpre = $numpre - numpar=$numpar - receita = $k00_receit";
	     $clarrejustreg->k28_arrejust = $clarrejust->k27_sequencia;
	     $clarrejustreg->k28_receita  = $k00_receit;
	     $clarrejustreg->k28_numpre   = $numpre;
	     $clarrejustreg->k28_numpar   = $numpar;
	     $clarrejustreg->incluir(null);
	     if ($clarrejustreg->erro_status == 0) {
	       $sqlerro = true;
	       $erro_msg = $clarrejustreg->erro_msg;
	       break;
	     }
     }
   }		
  }	

//  $sqlerro = true; // nao esquecer de comentar depois

  db_fim_transacao($sqlerro);
  db_msgbox($erro_msg);
  if ($sqlerro == false) {
   echo "<script> parent.document.formatu.pesquisar.click();</script>";
  }
 } 
?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden';">
<form name="form1" method="POST">
<?
echo "<input type='hidden' name='numpre' value='".@ $numpre1."'>\n";
echo "<input type='hidden' name='numpar' value='".@ $numpar1."'>\n";
?>
<center>
<table border="2" width="100%">
<input type="hidden" name="matric" value="<?=@$ver_matric?>">
<tr>
  <td  align="center" colspan="2" style='border: 1px outset #cccccc'><b>Justificar débito</b></td>
</tr>
<tr>
 <td valign="top">
  <table  align=center>
  <tr>
    <td>
     <?=$Lk27_obs?><br>
     <?db_textarea('k27_obs',5,55,$Ik27_obs,true,'text',$db_opcao,"")?>
    </td>
   </tr>
   <tr>
    <td><?=$Lk27_dias?><?db_input('k27_dias',5,$Ik27_dias,true,'text',1)?></td>
   </tr>
   <tr>
    <td colspan="2" align="center"><input type="submit" name="submit" value="Confirmar" onClick="js_verifica()"></td>
   </tr>
  </table>
 </td>
</tr>
</table>
</center>
</form>
</body>
</html>