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
include ("classes/db_testada_classe.php");
include ("classes/db_testpri_classe.php");
include ("classes/db_face_classe.php");
include ("classes/db_lote_classe.php");
include ("classes/db_cfiptu_classe.php");
include ("classes/db_testadanumero_classe.php");
include ("dbforms/db_funcoes.php");

$cltestada = new cl_testada;
$cltestada->rotulo->label();
$cltestpri = new cl_testpri;

$clrotulo = new rotulocampo;
$clrotulo->label("j14_nome");

$clface = new cl_face;
$clface->rotulo->label();

$clcfiptu = new cl_cfiptu;
$clcfiptu->rotulo->label();

$cltestadanumero = new cl_testadanumero;
$cltestadanumero->rotulo->label();

$cllote = new cl_lote;

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

if (isset ($enviar)) {


	$result = $clface->sql_record($clface->sql_query('', 'face.*#ruas.*', '', " j37_setor = '".$j34_setor."' and j37_quadra = '".$j34_quadra."'"));

	$caracte = "";
	$car = "";
	for ($i = 0; $i < $clface->numrows; $i ++) {
		db_fieldsmemory($result, $i);
		$j37_face;
		$j36_testad = "j36_testad".$i;
		$j36_testle = "j36_testle".$i;
		//$j14_codigo;
		$j15_numero = "j15_numero".$i;
		$j15_compl = "j15_compl".$i;

		$caracte .= $car.$j37_face."||".$j14_codigo."||".$$j36_testad."||".$$j36_testle."||".$$j15_numero."||".$$j15_compl;
		$car = "x";

  }
	echo "<script>parent.document.form1.cartestada.value = '".$caracte."';</script>";
	echo "<script>parent.document.form1.cartestpri.value = '".$principal."';</script>";
	echo "<script>parent.db_iframe.hide();</script>"; 
}
$resul = $clface->sql_record($clface->sql_query('', 'face.*#ruas.*', '', " j37_setor = '".$j34_setor."' and j37_quadra = '".$j34_quadra."'"));
$tamanho = $clface->numrows;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>


function js_checa2(id){
  var num=document.form1.total.value; 
  if(num==1){
    var valor = new Number('document.form1.j36_testad0.value');
    if(isNaN(valor)){
       document.form1.principal.checked=false;
    }
  }else{
    var valor = new Number(eval('document.form1.j36_testad'+id+'.value'));
    if(valor==0||valor==""){
      document.form1.principal[id].checked=false;
    }
  }  
}

function js_checa(id){
  var num=document.form1.total.value; 
  if(num==0){
    var valor = (eval('document.form1.j36_testad0.value'));
    if(valor==0){
      alert("Informe um valor no campo Testada MI");  
    }else{
      document.form1.principal.checked=true;
    }
  }else{
    var valor = (eval('document.form1.j36_testad'+id+'.value'));
    if(valor==0){
      alert("Informe um valor no campo Testada MI");  
    }else{
      document.form1.principal.checked=true;
    }
  } 
}

function js_checa3(){
  if(!js_verifica_campos_digitados()){
    return false;
  } 
  var num=document.form1.total.value; 
  var testa=false;
/*if(num==1){
    if(document.form1.principal.checked==true){
      testa=true;  
    }
  }else{*/
    for(i=0;i<=num;i++){
      if(document.form1.principal[i].checked==true && (eval('document.form1.j36_testad'+i+'.value')!=0 && eval('document.form1.j36_testad'+i+'.value')!='')){
        testa=true;  
/*        if(eval('document.form1.j36_testad'+i+'.value')==0 || eval('document.form1.j36_testad'+i+'.value')==''){
            testa=false;  
            break; 
        }*/
      }
   // }
  } 
  if(testa==false){
    alert("Informe a Testada e a rua principal!");
    return false;
  }else{
    return true;
  } 
}

function js_numcompl(id,teste,valor){
    if (teste==true){
	    for(i=0;i<=id;i++){
	         if(eval('document.form1.j36_testad'+i+'.value') != 0 ){
		         eval('document.form1.j15_numero'+i+'.disabled = false');
		         eval('document.form1.j15_compl'+i+'.disabled = false');
	         }else{
	             eval('document.form1.j15_numero'+i+'.value = null');
		         eval('document.form1.j15_compl'+i+'.value = null');
	             
	         	 eval('document.form1.j15_numero'+i+'.disabled = true');
		         eval('document.form1.j15_compl'+i+'.disabled = true');
	         }
	         if(eval('document.form1.j36_testad'+i+'.value') == ''){
		         eval('document.form1.j36_testad'+i+'.value = 0');
	         }
	         if(eval('document.form1.j36_testle'+i+'.value') == ''){
	         	eval('document.form1.j36_testle'+i+'.value = 0');
	         }	         
	    }
    }else{
      if (valor==0){
            eval('document.form1.j15_numero'+id+'.value = null');
		    eval('document.form1.j15_compl'+id+'.value = null');
	        eval('document.form1.j15_numero'+id+'.disabled = true');
	        eval('document.form1.j15_compl'+id+'.disabled = true');
      }else{
            eval('document.form1.j15_numero'+id+'.disabled = false');
	        eval('document.form1.j15_compl'+id+'.disabled = false');
       
      }
      if(eval('document.form1.j36_testad'+id+'.value') == ''){
		eval('document.form1.j36_testad'+id+'.value = 0');
	  }
	  if(eval('document.form1.j36_testle'+id+'.value') == ''){
	  	eval('document.form1.j36_testle'+id+'.value = 0');
	  } 
    }
}

</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <form name="form1" method="post">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?


if (isset ($digita_testada)) {
	//  include("forms/db_frmtestada001.php");
} else {
	// $result = $cllote->sql_record($cllote->sql_query_file($testa));
	// if($cllote->numrows==0){
	//   echo "lote nao existe";
	//  }else{
	//  db_fieldsmemory($result,0);
	$result = $clface->sql_record($clface->sql_query('', 'face.*#ruas.*', '', " j37_setor = '".$j34_setor."' and j37_quadra = '".$j34_quadra."'"));
	if ($clface->numrows > 0) {
		echo '<table width="100%" border="0" cellspacing="0">';
		echo '<tr width="100%">'."\n";
		echo "<td>$Lj36_testad</td>";
		echo "<td>$Lj36_testle</td>";
		//=============================================================
		if(isset($mostranum) && $mostranum == 't'){
			echo "<td><b>Número:</b></td>";
			echo "<td><b>$Lj15_compl</b></td>";
		}
		//=============================================================		  
		echo "<td><b>Principal</b></td>";
		echo "<td>$Lj37_setor</td>";
		echo "<td>$Lj37_quadra</td>";
		echo "<td>$Lj36_codigo</td>";
		echo "<td>$Lj14_nome</td>";
		echo "</tr>\n";

    if (isset ($matrizvolta)) {
      $matrizvolta = explode("x", $matrizvolta);
    }
    for ($fq = 0; $fq < $clface->numrows; $fq ++) {
			db_fieldsmemory($result, $fq);
			$temvalor = false;
			if (isset ($matrizvolta)) {
				for ($f = 0; $f < sizeof($matrizvolta); $f ++) {
					$matrizdados = explode("||", $matrizvolta[$f]);
					if ($matrizdados[0] == $j37_face) {
						$temvalor = true;
						break;
					}
				}
			}
			if ($db_opcao != 1) {
				$resulttestada = $cltestada->sql_record($cltestada->sql_query_file($testa, $j37_face));
				if ($cltestada->numrows > 0) {
					db_fieldsmemory($resulttestada, 0);
				} else {
					$j36_testad = 0;
					$j36_testle = 0;
				}
//========================================================================================================================================
                if(isset($mostranum) && $mostranum == 't'){
                    $j15_numero = "";
$j15_compl = "";				
	//              echo($cltestadanumero->sql_query_file("","*","","j15_idbql = $testa and j15_face = $j37_face"));
					$resulttestadanumero = $cltestadanumero->sql_record($cltestadanumero->sql_query_file("","*","","j15_idbql = $testa and j15_face = $j37_face"));
                    //echo $cltestadanumero->sql_query_file("","*","","j15_idbql = $testa and j15_face = $j37_face")."<br>";
					if ($cltestadanumero->numrows > 0) {
	//					echo "<br><br>entrou ; ".$j15_compl." - ".$j15_compl;
						db_fieldsmemory($resulttestadanumero, 0);
						
					} else {
	//					$j15_numero = "";
	//	     			$j15_compl = "";
					}
                }
//========================================================================================================================================				
				$resulttestpri = $cltestpri->sql_record($cltestpri->sql_query_file($testa, $j37_face));
				if ($cltestpri->numrows > 0) {
					db_fieldsmemory($resulttestpri, 0);
				} else {
					$j49_idbql = 0;
				}
			} else {
				$j36_testad = 0;
				$j36_testle = 0;
				$j49_idbql = 0;

			}


			echo "<tr>\n";
			echo "<td>";
		
      if (isset ($temvalor) && $temvalor == true) {
				$j14_codigo = $matrizdados[1];
				$j36_testad = $matrizdados[2];
				$j36_testle = $matrizdados[3];
				if (isset($matrizdados[4]) && $matrizdados[4] != ""){
					$j15_numero = $matrizdados[4];
				}
				if (isset($matrizdados[5]) && $matrizdados[5] != ""){
					$j15_compl  = $matrizdados[5];
				}
				$j49_face = $voltapri;

			}
			$x = "j36_testad".$fq;
			$$x = $j36_testad;

			db_input('j36_testad', 16, $Ij36_testad, true, 'text', '','onchange="js_checa2(\''.$fq.'\');js_numcompl(\''.$fq.'\',false,this.value);"', 'j36_testad'.$fq);
			
			echo "</td>";
			echo "<td>";
			$x = "j36_testle".$fq;
			$$x = $j36_testle;
			db_input('j36_testle', 16, $Ij36_testad, true, 'text', '','onchange="js_numcompl(\''.$fq.'\',false,this.value);"', 'j36_testle'.$fq);
			echo "</td>";

          if(isset($mostranum) && $mostranum == 't'){  
			//==========================================================================
			echo "<td>";
			$x = "j15_numero".$fq;
			$$x = @$j15_numero;
			db_input('j15_numero', 5, $Ij15_numero, true, 'text', '', '', 'j15_numero'.$fq);
			echo "</td>";
			//==========================================================================
			echo "<td>";
			$x = "j15_compl".$fq;
			$$x = @$j15_compl;
      db_input('j15_compl', 20, $Ij15_compl, true, 'text', '', '','j15_compl'.$fq);
      echo "</td>";
			echo "<script>document.form1.j15_numero$fq.disabled = true;</script>";
	        echo "<script>document.form1.j15_compl$fq.disabled = true;</script>";
	        if(isset($j15_numero)){ 
	            echo "<script>document.form1.j15_numero$fq.value = $j15_numero;</script>";
	        }
	       /* if(isset($j15_compl)){
             echo "<script>document.form1.j15_compl$fq.value = $j15_compl;</script>";
          } */
			
			//==========================================================================
          }
			echo "<td>";
			echo "<input name='principal' id='".$fq."' type='radio' onmousedown=\"js_checa('".$fq."')\" value='$j37_face' ". (isset ($j49_face) ? ($j49_face == $j37_face ? "checked" : "") : "")." >";
			echo "</td>";
			echo "<td>$j37_setor</td>";
			echo "<td>$j37_quadra</td>";
			echo "<td>$j14_codigo</td>";
			echo "<td>$j14_nome</td>";
			echo "</tr>\n";
		}
		$fq--;
        echo "<script>js_numcompl($fq,true,0);</script>";
		echo "<input name='total' id='".$fq."' type='hidden' value='".$fq."'>";
            ?>
	          <tr> 
	            <td colspan="9" align="center">
	            <input type="submit" name="enviar" value="Enviar" onclick="return js_checa3();">
	            <input type="button" name="Fechar" value="Fechar" onClick="parent.db_iframe.hide();">
	            </td>
	          </tr>
	          </table>
			<?
	} else {
		echo "Face de Quadra não Incluída para este Setor/Quadra";
	}
//	 }
}
?>
    </center>
	</td>
  </tr>
  </form>
</table>
</body>
</html>