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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
include("../libs/db_sessoes.php");
include("../libs/db_usuariosonline.php");
include("../classes/db_empelemento_classe.php");
include("../classes/db_pagordemele_classe.php");
include("../dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clempelemento = new cl_empelemento;
$clpagordemele = new cl_pagordemele;
$clrotulo = new rotulocampo;
$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
$clempelemento->rotulo->label();
?>
<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }
</style>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script>
      function js_coloca(tot){
	dados='';
	vir='';
        tot= new Number(tot);  
	obj = document.form1.elements;
	for(i=0; i<obj.length; i++ ){
          nome=obj[i].name.substring(0,8);
	  if(nome=="generico"){
	    codele = obj[i].name.substring(9);
  	    vlrlimite = new Number(eval("document.form1.disponivel_"+codele+".value"));
  	    total  = new Number(eval("document.form1.e64_vlrpag_"+codele+".value"));
	    if(tot >= vlrlimite){
	      obj[i].value = vlrlimite.toFixed(2); 
	      tot = (tot-vlrlimite);
	    }else{
	      obj[i].value=tot.toFixed(2); 
	      tot=new Number(0);
	    }  
	    dados += vir+codele+"-"+obj[i].value+"-"+total;
	    vir='#';
	  }
	}
	parent.document.form1.dados.value=dados;
      }
      function js_calcular(){
	vir='';
	dados='';
	obj = document.form1.elements;
	tot= 0;
	for(i=0; i<obj.length; i++ ){
          nome=obj[i].name.substring(0,8);
	  if(nome=="generico"){
	     codele = obj[i].name.substring(9);
  	     total  = new Number(eval("document.form1.e64_vlrpag_"+codele+".value"));
	     soma = new Number(obj[i].value); 

	     obj[i].value = soma.toFixed(2);
	     dados += vir+codele+"-"+soma+"-"+total;
	     vir='#';
	     tot = (soma+tot);
	  }
	}
	val = new Number(tot);
	parent.document.form1.vlrpag_estornar.value = val.toFixed(2);
	parent.document.form1.dados.value=dados;
      }
      function js_verificar(obj,vlrdis){
	erro=false;
        vlrdis = new Number(vlrdis)
	vlrpag= new Number(obj.value);
	if(isNaN(vlrpag)){
	  eval("document.form1."+obj.name+".value=vlrdis.toFixed(2);");
	  js_calcular();
	  return false;
	}
	nome = obj.name.substring(9);
	vlrlimite = eval("document.form1.disponivel_"+nome+".value");
	if(vlrpag > vlrlimite){
	  erro=true;
	}  
	if(erro==false){
           js_calcular()
	  return true;
	}else{  
	  eval("document.form1."+obj.name+".focus()");
	  eval("document.form1."+obj.name+".value=vlrdis.toFixed(2);");
	  js_calcular();
	  return false;
	}  
      }
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='js_calcular();'>
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1'>
    <input name='verificador' type='hidden' value='ok'>
    <center>
 <table border='1' cellspacing="0" cellpadding="0">   
 <?
 if(isset($e60_numemp) && $e60_numemp!= ""){
      $result = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp,null,"*","e64_codele"));
      $numrows = $clempelemento->numrows;
      if($numrows>0){
	echo "
	    <tr>
	      <td  class='bordas' align='center'><b><small>$RLo56_elemento</small></b></td>
	      <td  class='bordas' align='center'><b><small>$RLo56_descr</small></b></td>
	      <td  class='bordas' align='center'><b><small>$RLe64_vlremp</small></b></td>
	      <td  class='bordas' align='center'><b><small>$RLe64_vlrliq</small></b></td>
	      <td  class='bordas' align='center'><b><small>$RLe64_vlranu</small></b></td>
	      <td  class='bordas' align='center'><b><small>$RLe64_vlrpag</small></b></td>
				 
              <td  class='bordas' align='center'><b><small>Ordem</small></b></td>
              <td   class='bordas align='center'><b><small>Pago ordem</small></b></td>
              <td  class='bordas' align='center'><b><small>Anulado</small></b></td>
				 
	      <td  class='bordas' align='center'><b><small>Disponivel</small></b></td>
	      <td  class='bordas' align='center'><b><small>Estornar</small></b></td>
	    </tr>
	";
	  $eles = '';
	  $vir = "";
         for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i,true);

	     $eles .=$vir.$o56_codele;
 	     $vir = "#";
	    
		    $e="e64_vlremp_$o56_codele";
		    $$e = $e64_vlremp;		       
		    $e="e64_vlrliq_$o56_codele";
		    $$e = $e64_vlrliq;		       
		    $e="e64_vlranu_$o56_codele";
		    $$e = $e64_vlranu;		       
		    $e="e64_vlrpag_$o56_codele";
		    $$e = $e64_vlrpag;		       

                    $result02  = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,"sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu","","e60_numemp=$e60_numemp and e53_codele=$o56_codele")); 
		    if($clpagordemele->numrows>0){
		      db_fieldsmemory($result02,0);
		    }

		      $e="e53_valor_$o56_codele";
		      $$e = number_format($tot_valor,"2",".","");		       
		      $e="e53_vlrpag_$o56_codele";
		      $$e = number_format($tot_vlrpag,"2",".","");		       
		      $e="e53_vlranu_$o56_codele";
		      $$e = number_format($tot_vlranu,"2",".","");		       

                      $vlrdis= $e64_vlrpag-$tot_vlrpag;  
		     
		      $e="disponivel_$o56_codele";
		      $$e = number_format($vlrdis,"2",".","");		       
		      $e="generico_$o56_codele";
		      $$e = number_format($vlrdis,"2",".","");		       
	    echo "<tr>	    
   	            <td	 class='bordas_corp' align='center'><small>$o56_elemento </small></td>
	            <td	 class='bordas_corp' align='center' title='$o56_descr'><small>".ucfirst(strtolower(substr($o56_descr,0,8)))."...</small></td>
       	            <td	 class='bordas_corp' align='center'><small>";db_input("e64_vlremp_$o56_codele",7,0,true,'text',3);echo "\n</small></td>
	            <td	 class='bordas_corp' align='center'><small>";db_input("e64_vlrliq_$o56_codele",7,0,true,'text',3);echo "</small></td>
                    <td	 class='bordas_corp' align='center'><small>";db_input("e64_vlranu_$o56_codele",7,0,true,'text',3);echo "</small></td>
                    <td	 class='bordas_corp' align='center'><small>";db_input("e64_vlrpag_$o56_codele",7,0,true,'text',3);echo "</small></td>

                    <td class='bordas_corp' align='center'><small>";db_input("e53_valor_$o56_codele",7,0,true,'text',3);echo "</small></td>
                    <td class='bordas_corp' align='center'><small>";db_input("e53_vlrpag_$o56_codele",7,0,true,'text',3);echo "</small></td>
                    <td class='bordas_corp' align='center'><small>";db_input("e53_vlranu_$o56_codele",7,0,true,'text',3);echo "</small></td>
		    
                    <td	 class='bordas_corp' align='center'><small>";db_input("disponivel_$o56_codele",8,0,true,'text',3);echo "</small></td>
                    <td	 class='bordas_corp' align='center'><small>";db_input("generico_$o56_codele",8,4,true,'text',$db_opcao,"onchange='js_verificar(this,$vlrdis);'")."</small></td>
	          </tr> 
	         ";
         }
      }	 
  }    
 ?>
 </table>
    </form> 
    </center>
    </td>
  </tr>
</table>
</body>
</html>
<?
if(isset($eles)){
?>
 <script>
 function js_tranca(){
   var eles = "<?=$eles?>";
   arr = eles.split("#");
   for(i=0; i<arr.length; i++){
     elemento =  arr[i];
     eval("document.form1.generico_"+elemento+".readOnly=true;");
     eval("document.form1.generico_"+elemento+".style.backgroundColor = '#DEB887'");
   }
 }
 function js_libera(){
   var eles = "<?=$eles?>";
   arr = eles.split("#");
   for(i=0; i<arr.length; i++){
     elemento =  arr[i];
     if(elemento!=''){
       eval("document.form1.generico_"+elemento+".readOnly=false;");
       eval("document.form1.generico_"+elemento+".style.backgroundColor = 'white'");
     }  
   }
 }
 </script>

<?  
}
?>