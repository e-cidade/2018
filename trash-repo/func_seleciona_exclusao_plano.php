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
include("classes/db_orcparamseq_classe.php");
include("classes/db_orcparamelemento_classe.php");


db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);


$clorcparamseq = new cl_orcparamseq;
$clorcparamelemento = new cl_orcparamelemento;


$clorcparamseq->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("o42_descrrel");

?>
<script>
function js_voltar(){
  document.location.href="con2_conrelparametros.php?c83_codrel=<?=$o69_codparamrel?>";
}
</script>
<?

if (isset ($atualizar) && $atualizar == "atualizar") {
	db_inicio_transacao();
	$erro = false;
	$msg = "";
	$instit = db_getsession("DB_instit");
	$clorcparamelemento->o44_instit = $instit;
	$clorcparamelemento->sql_record("select * from orcparamelemento 
                                   where o44_codparrel=$c83_codrel and 
				         o44_sequencia=$c69_codseq and 
					 o44_instit=$instit and 
					 o44_anousu=".db_getsession("DB_anousu")." and
					 o44_exclusao='t' ");
	if ($clorcparamelemento->numrows > 0) {
		$clorcparamelemento->excluir(
		   null, null, null, null, 
		   "o44_codparrel=$c83_codrel and 
		    o44_sequencia=$c69_codseq and 
		    o44_anousu =".db_getsession("DB_anousu")." and
		    o44_instit=$instit and o44_exclusao='t' ");
		if ($clorcparamelemento->erro_status == 0) {
			$erro = true;
			$msg = $clorcparamelemento->erro_msg;
		}
	}
	$matriz = explode("#", $lista); //gera matriz com as chaves
	for ($i = 0; $i < sizeof($matriz); $i ++) {
		// o teste abaixo e necessario porque quando desmerca todos os itens na tela, o expode acima gera 1 vazio
		if ($matriz[$i] != "") {
			// db_msgbox($matriz[$i]);
			$clorcparamelemento->o44_exclusao='t';
			$clorcparamelemento->incluir(db_getsession("DB_anousu"), $c83_codrel, $c69_codseq, $matriz[$i], $instit);
			if ($clorcparamelemento->erro_status == 0) {
				$erro = true;
				$msg = $clorcparamelemento->erro_msg;
			}
		}
	}
	db_fim_transacao($erro);
	if ($erro == true) {
		db_msgbox($msg);
	}

	echo "<script>
         js_voltar();
	       parent.js_refresh(); 
	      </script>";

}
// ---------------------------------------------------------------------------------


function espaco($estrutural=""){
    $espaco ="";
    if(substr($estrutural,1,14)     == '00000000000000'){
       $espaco="";    
    }elseif(substr($estrutural,2,13)== '0000000000000'){  
       $espaco="&nbsp;&nbsp;";    
    }elseif(substr($estrutural,3,12)== '000000000000'){   
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;";    
    }elseif(substr($estrutural,4,11) == '00000000000'){	
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";    
    }elseif(substr($estrutural,5,10) == '0000000000'){  
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";    
    }elseif(substr($estrutural,7,8)  == '00000000'){   
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";   
    }elseif(substr($estrutural,9,6)  == '000000'){   
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";   
    }elseif(substr($estrutural,11,4) == '0000'){ 	
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";    
    }elseif(substr($estrutural,12,3) == '000'){
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";    
    }elseif(substr($estrutural,13,2) == '00'){ 
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
    }elseif(substr($estrutural,14,1) == '0'){ 
       $espaco="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"; 
    }

    
    return $espaco;
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
function js_emite(){
  c69_codseq ="<?=$o69_codparamrel?>";
  obj = document.form1;
  
  jantes = window.open('con2_imprimeseqelemento002.php?c69_codseq='+c69_codseq,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jantes.moveTo(0,0);
}

function js_getChaves(){
   // usada no botão submit para capturar as chaves do iframe 
   // usar um campo hidden no form com o nome chaves
   lista = '';
   sep='';
   obj = document.form1;   
   for(i=0;i < obj.length;i++){
     if (obj[i].type == 'checkbox'){         
        if (obj[i].checked == true){
	      lista = lista+ sep + obj[i].value;
	      sep ='#';
	    } 	  
     } 
   }
   obj.lista.value = lista;   
    var op= document.createElement("input");
        op.setAttribute("type","hidden");
	op.setAttribute("name","atualizar");
  	op.setAttribute("value","atualizar");
   document.form1.appendChild(op);       
   
   obj.submit();   
}  
function js_desmarcarTodos(){
   obj = document.form1;   
   for(i=0;i < obj.length;i++){
     if (obj[i].type == 'checkbox'){         
          obj[i].checked = false;
     } 
   }
}  

function js_select(tipo){
  if(tipo=='ativo')
    document.getElementById('div_ativo').style.visibility = document.getElementById('div_ativo').style.visibility='visible'; 
  else
    document.getElementById('div_ativo').style.visibility = document.getElementById('div_ativo').style.visibility='hidden'; 
  if(tipo=='passivo')
    document.getElementById('div_passivo').style.visibility = document.getElementById('div_passivo').style.visibility='visible'; 
  else
    document.getElementById('div_passivo').style.visibility = document.getElementById('div_passivo').style.visibility='hidden';
  if(tipo=='rec')
    document.getElementById('div_rec').style.visibility = document.getElementById('div_rec').style.visibility='visible'; 
  else
    document.getElementById('div_rec').style.visibility = document.getElementById('div_rec').style.visibility='hidden';
  if(tipo=='desp')
    document.getElementById('div_desp').style.visibility = document.getElementById('div_desp').style.visibility='visible'; 
  else
    document.getElementById('div_desp').style.visibility = document.getElementById('div_desp').style.visibility='hidden';
  if(tipo=='outros')
    document.getElementById('div_outros').style.visibility = document.getElementById('div_outros').style.visibility='visible'; 
  else
    document.getElementById('div_outros').style.visibility = document.getElementById('div_outros').style.visibility='hidden';

}
</script>
</head>

<body>

<form name=form1 action=""  method="POST">

<input type="hidden" name="lista" value="">
 <input type="hidden" name="c83_codrel" value="<?=$o69_codparamrel ?>">
 <input type="hidden" name="c69_codseq" value="<?=$o69_codseq ?>">

<table border="1" align=center>
 <tr>
   <td colspan=1>
     <? $s = "select o69_descr 
              from orcparamseq 
	      where o69_codparamrel = $o69_codparamrel
	             and o69_codseq = $o69_codseq 
              ";
	$r = pg_exec($s);
	if (pg_numrows($r)>0){
            db_fieldsmemory($r,0);
	    echo  "<b>Parametro: $o69_descr </b>";	
	}  
     ?>
   </td>
 </tr>
 <tr>
   <td colspan="2" nowrap align="center">
      <input type="button" value="Gravar Parametros" onclick="js_getChaves();" <?=($flag_permissao=="false"?"disabled":"")?>>
      <input type="button" value="Desmarcar Todos" onclick="js_desmarcarTodos();" <?=($flag_permissao=="false"?"disabled":"")?>>
      <input type="button" value="Imprimir" onclick="js_emite();">
      <input type="button" value="Voltar" onclick="js_voltar();">
   </td>
 </tr>
 <tr>
 <td>
 <table border="0"  align="left" cellspacing="1" bgcolor="#CCCCCC">
 <tr> 
   <td colspan="3" valign="middle" align=left nowrap>
   <? if (isset($grupo) && $grupo !='' && $grupo !='0') {   
      switch($grupo){
	 case 1:echo "<input id=ativo type=button value=ATIVO onclick=\"js_select('ativo');return false\" >";
	        break;
         case 2:echo "<input id=passivo type=button value=PASSIVO onclick=\"js_select('passivo');return false\" >";
	        break;
         case 3:echo " <input id=desp type=button value=DESP onclick=\"js_select('desp');return false\" >";
                break;
         case 4:echo " <input id=rec type=button value=REC onclick=\"js_select('rec');return false\" >";
	        break;
         default: echo "<input id=outros type=button value=OUTROS onclick=\"js_select('outros');return false\" >";
      }	 
   } else {  ?>    
      <input id="ativo" type="button" value="ATIVO" onclick="js_select('ativo');return false" >
      <input id="passivo" type="button" value="PASSIVO" onclick="js_select('passivo');return false" >
      <input id="rec" type="button" value="RECEITA" onclick="js_select('rec');return false" >
      <input id="desp" type="button" value="DESPESA" onclick="js_select('desp');return false" >
      <input id="outros" type="button" value="OUTROS" onclick="js_select('outros');return false" >
   <? } ?>
   </td>
  </tr>
 </table>
 </td></tr></table>
<!--   -->
<?
  if (!isset($o69_codparamrel) || $o69_codparamrel ==""){
         $o69_codparamrel=0;
         $o69_codseq=0;  	 
  }        
  $sql="select distinct c60_codcon,c60_estrut,c52_descrred,c60_descr,o44_codele, c61_codigo as recurso,c61_reduz
        from conplano 
           inner join consistema on c52_codsis = conplano.c60_codsis
           left join conplanoreduz on c61_codcon = c60_codcon and 
                                      c61_anousu = c60_anousu  and
				      c61_instit = ".db_getsession("DB_instit")."
           left join orcparamelemento on o44_codparrel = $o69_codparamrel  and
	                                       o44_sequencia = $o69_codseq and
	                                       o44_anousu = c60_anousu  and
	                                       o44_instit =".db_getsession("DB_instit")." and	
					       o44_codele = conplano.c60_codcon and
					       o44_exclusao ='t'					       

        where c60_anousu = ".db_getsession("DB_anousu");
  if (isset($grupo) && $grupo!='' && $grupo!='0'){	
      $sql .=" and c60_estrut like '$grupo%' ";      
  }	
  $sql .=" order by c60_estrut ";           					                      
   
  $result=$clorcparamelemento->sql_record($sql);
  $numrows = $clorcparamelemento->numrows;
  $a_codcon = $p_codcon = $r_codcon = $d_codcon = $o_codcon  = array();
  $a_estrut = $p_estrut = $r_estrut = $d_estrut = $o_estrut  = array();
  $a_descred= $p_descred= $r_descred= $d_descred= $o_descred = array();
  $a_descr  = $p_descr  = $r_descr  = $d_descr  = $o_descr   = array();
  $a_codele = $p_codele = $r_codele = $d_codele = $o_codele  = array();
  $a_recurso= $p_recurso= $r_recurso= $d_recurso= $o_recurso = array();
  $a_reduz  = $p_reduz  = $r_reduz  = $d_reduz  = $o_reduz   = array();
  $a = 0;
  $p = 0;
  $d = 0;
  $r = 0;
  $o = 0;
  for($i = 0;$i < $numrows;$i++) {
     db_fieldsmemory($result,$i); 
     if (substr($c60_estrut,0,1) =='1' ){
       $a_codcon  [$a] = $c60_codcon;
       $a_estrut  [$a] = $c60_estrut;
       $a_descred [$a] = $c52_descrred; // sistema da conta [F/P/C/O]
       $a_descr   [$a] = $c60_descr;
       $a_codele  [$a] = $o44_codele; // elemento da tabela orcparamelemento
       $a_recurso [$a] = $recurso;
       $a_reduz   [$a] = $c61_reduz;
       $a++;
     } elseif (substr($c60_estrut,0,1) =='2' ){
       $p_codcon  [$p] = $c60_codcon;
       $p_estrut  [$p] = $c60_estrut;
       $p_descred [$p] = $c52_descrred; // sistema da conta [F/P/C/O]
       $p_descr   [$p] = $c60_descr;
       $p_codele  [$p] = $o44_codele; // elemento da tabela orcparamelemento
       $p_recurso [$p] = $recurso;
       $p_reduz   [$p] = $c61_reduz;
       $p++;
     } elseif (substr($c60_estrut,0,1) =='3' ){
       $d_codcon  [$d] = $c60_codcon;
       $d_estrut  [$d] = $c60_estrut;
       $d_descred [$d] = $c52_descrred; // sistema da conta [F/P/C/O]
       $d_descr   [$d] = $c60_descr;
       $d_codele  [$d] = $o44_codele; // elemento da tabela orcparamelemento
       $d_recurso [$d] = $recurso;
       $d_reduz   [$d] = $c61_reduz;
       $d++;
     } elseif (substr($c60_estrut,0,1) =='4' ){
       $r_codcon  [$r] = $c60_codcon;
       $r_estrut  [$r] = $c60_estrut;
       $r_descred [$r] = $c52_descrred; // sistema da conta [F/P/C/O]
       $r_descr   [$r] = $c60_descr;
       $r_codele  [$r] = $o44_codele; // elemento da tabela orcparamelemento
       $r_recurso [$r] = $recurso;
       $r_reduz   [$r] = $c61_reduz;
       $r++;       
     } else {
       $o_codcon  [$o] = $c60_codcon;
       $o_estrut  [$o] = $c60_estrut;
       $o_descred [$o] = $c52_descrred; // sistema da conta [F/P/C/O]
       $o_descr   [$o] = $c60_descr;
       $o_codele  [$o] = $o44_codele; // elemento da tabela orcparamelemento
       $o_recurso [$o] = $recurso;
       $o_reduz   [$o] = $c61_reduz;
       $o++;
     }      
  }
?>
<!-- inicia divs -->

  <div id='div_ativo' style='position:absolute;visibility:hidden;' >
  <table border=1 cellspacing=0 width="100%" bgcolor="#CCCCCC" align="center"> 
  <?// se alteração     
     echo "<tr><th>&nbsp;</th><th>Estrutural</th><th>Sistema</th><th>descr<th></tr>";
     for($i = 0;$i < sizeof($a_codcon);$i++) {
         echo "<tr>";
         echo "<td><input type=\"checkbox\" name=\"chaves\" value=\"$a_codcon[$i]\" ";
         if ($a_codele[$i]!="")
              echo "checked";             	
         echo "></td>";	      	    
	 if ($a_reduz[$i]!=''){
            echo "<td>".espaco($a_estrut[$i])."$a_estrut[$i]</td>";
    	    echo "<td>$a_descred[$i]</td>";              
            echo "<td nowrap>$a_descr[$i] </td>";
            echo "</tr> ";            
	 } else {
	    echo "<td><b>".espaco($a_estrut[$i])."$a_estrut[$i] </b></td>";
   	    echo "<td><b>$a_descred[$i]</b></td>";              
            echo "<td nowrap><b>$a_descr[$i]</b> </td>";
            echo "</tr> ";            
    
	 }  
     }
  ?>   
  </table>
  </div> 
  <div id='div_passivo' style='position:absolute; visibility:hidden' >
  <table border=1 cellspacing=0 width="100%" bgcolor="#CCCCCC">
   <?// se alteração     
     echo "<tr><th>&nbsp;</th><th>Estrutural</th><th>Sistema</th><th>descr<th></tr>";
     for($i = 0;$i < sizeof($p_codcon);$i++) {
         echo "<tr>";
         echo "<td><input type=\"checkbox\" name=\"chaves\" value=\"$p_codcon[$i]\" ";
         if ($p_codele[$i]!="")
              echo "checked";             	
         echo "></td>";	      	    
	 if ($p_reduz[$i]!=''){
            echo "<td>".espaco($p_estrut[$i])."$p_estrut[$i]</td>";
    	    echo "<td>$p_descred[$i]</td>";              
            echo "<td nowrap>$p_descr[$i] </td>";
            echo "</tr> ";            
	 } else {
	    echo "<td><b>".espaco($p_estrut[$i])."$p_estrut[$i] </b></td>";
	    echo "<td><b>$p_descred[$i] </b></td>";              
            echo "<td nowrap><b>$p_descr[$i] </b></td>";
            echo "</tr> ";          
	 }  	 
     }
  ?>   
 
  </table>
  </div>
  <div id='div_rec' style='position:absolute; visibility:hidden' >
  <table border=1 cellspacing=0 width="100%" bgcolor="#CCCCCC"> 
   <?// se alteração     
     echo "<tr><th>&nbsp;</th><th>Estrutural</th><th>Sistema</th><th>descr<th></tr>";
     for($i = 0;$i < sizeof($r_codcon);$i++) {
         echo "<tr>";
         echo "<td><input type=\"checkbox\" name=\"chaves\" value=\"$r_codcon[$i]\" ";
         if ($r_codele[$i]!="")
              echo "checked";             	
         echo "></td>";	      	    
	 if ($r_reduz[$i]!=''){
            echo "<td>".espaco($r_estrut[$i])."$r_estrut[$i]</td>";
  	    echo "<td>$r_descred[$i]</td>";              
            echo "<td nowrap>$r_descr[$i] </td>";
            echo "</tr> ";            
	 } else {
	    echo "<td><b>".espaco($r_estrut[$i])."$r_estrut[$i] </b></td>";
	    echo "<td><b>$r_descred[$i]  </b></td>";              
            echo "<td nowrap><b>$r_descr[$i] </b></td>";
            echo "</tr> ";            
	 }  
     }
  ?>   

  </table>
  </div>
  <div id='div_desp' style='position:absolute; visibility:hidden' >
  <table border=1 cellspacing=0 width="100%" bgcolor="#CCCCCC"> 
  <?
   // se alteração     
     echo "<tr><th>&nbsp;</th><th>Estrutural</th><th>Sistema</th><th>descr<th></tr>";
     for($i = 0;$i < sizeof($d_codcon);$i++) {
         echo "<tr>";
         echo "<td><input type=\"checkbox\" name=\"chaves\" value=\"$d_codcon[$i]\" ";
         if ($d_codele[$i]!="")
              echo "checked";             	
         echo "></td>";	      	    
	 if ($d_reduz[$i]!=''){
            echo "<td>".espaco($d_estrut[$i])."$d_estrut[$i]</td>";
	    echo "<td>$d_descred[$i]</td>";              
            echo "<td nowrap>$d_descr[$i] </td>";
            echo "</tr> ";            
	 } else {
	    echo "<td><b>".espaco($d_estrut[$i])."$d_estrut[$i] </b></td>";
    	    echo "<td><b>$d_descred[$i] </b></td>";              
            echo "<td nowrap><b>$d_descr[$i]</b> </td>";
            echo "</tr> ";            

	 }  
     }
  ?>   

  </table>
  </div>
  <div id='div_outros' style='position:absolute; visibility:hidden' >
  <table border=1 cellspacing=0 width="100%" bgcolor="#CCCCCC"> 
   <?// se alteração     
     echo "<tr><th>&nbsp;</th><th>Estrutural</th><th>Sistema</th><th>descr<th></tr>";
     for($i = 0;$i < sizeof($o_codcon);$i++) {
         echo "<tr>";
         echo "<td><input type=\"checkbox\" name=\"chaves\" value=\"$o_codcon[$i]\" ";
         if ($o_codele[$i]!="")
              echo "checked";             	
         echo "></td>";	      	    
	 if ($o_reduz[$i]!=''){
            echo "<td>".espaco($o_estrut[$i])."$o_estrut[$i]</td>";
	    echo "<td>$o_descred[$i]</td>";              
            echo "<td nowrap>$o_descr[$i] </td>";
            echo "</tr> ";            
	 } else {
	    echo "<td><b>".espaco($o_estrut[$i])."$o_estrut[$i] </b></td>";
 	    echo "<td><b> $o_descred[$i] </b></td>";              
            echo "<td nowrap><b> $o_descr[$i]</b> </td>";
            echo "</tr> ";            

	 }  	
     }
  ?>   
  </table>
  </div>  
</form>
</center>
</body>

<script>
function js_pesquisao69_codparamrel(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?funcao_js=parent.js_mostraorcparamrel1|o42_codparrel|o42_descrrel','Pesquisa',true);
  }else{
     if(document.form1.o69_codparamrel.value != ''){ 
        js_OpenJanelaIframe('top.corpo','db_iframe_orcparamrel','func_orcparamrel.php?pesquisa_chave='+document.form1.o69_codparamrel.value+'&funcao_js=parent.js_mostraorcparamrel','Pesquisa',false);
     }else{
       document.form1.o42_descrrel.value = ''; 
     }
  }
}
function js_mostraorcparamrel(chave,erro){
  document.form1.o42_descrrel.value = chave; 
  if(erro==true){ 
    document.form1.o69_codparamrel.focus(); 
    document.form1.o69_codparamrel.value = ''; 
  }
}
function js_mostraorcparamrel1(chave1,chave2){
  document.form1.o69_codparamrel.value = chave1;
  document.form1.o42_descrrel.value = chave2;
  db_iframe_orcparamrel.hide();
}
<?
 if (isset($grupo) && $grupo !='' && $grupo !='0') {   
    switch($grupo){
       case 1:echo "js_select('ativo');>";
	        break;
       case 2:echo "js_select('passivo')>";
               break;
       case 3:echo " js_select('desp'); ";
                break;
       case 4:echo " js_select('rec'); ";
              break;
       default: echo "js_select('outros'); ";
    }	 
 }    
?>
</script>
</html>