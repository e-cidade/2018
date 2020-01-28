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
include("../dbforms/db_funcoes.php");
include("../classes/db_empelemento_classe.php");
include("../classes/db_pagordemele_classe.php");
include("../classes/db_empnotaele_classe.php");
include("../classes/db_empnota_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clempelemento = new cl_empelemento;
$clpagordemele = new cl_pagordemele;
$clempnotaele = new cl_empnotaele;
$clempnota = new cl_empnota;
$clrotulo = new rotulocampo;
$clrotulo->label("o56_descr");
$clrotulo->label("o56_elemento");
$clrotulo->label("e64_vlrliq");
$clrotulo->label("e64_vlranu");
$clrotulo->label("e64_vlremp");
$clrotulo->label("e64_vlrpag");
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
      function js_coloca_notas(notas,mais){
	   arr = notas.split('#'); 
	   soma_total =new Number();
	   for(i=0; i<arr.length; i++){
	     arr_val = arr[i].split('-');
	     nota  = arr_val[0];
	     elemento  = arr_val[1];
	     valor = new Number(arr_val[2]);
	      atu = new Number(eval("document.form1.generico_"+elemento+".value;"));
	      if(mais==true){
 	         tot=atu+valor;
	       }else{	 
 	         tot=atu-valor;
	       } 
	     tot  = new Number(tot); 
	     soma_total += tot;
	     tot  = tot.toFixed(2);
	      eval("document.form1.generico_"+elemento+".value="+tot);
	   }
          <?if(isset($jafoipago) && $jafoipago==true){?>	
  	      parent.document.form1.vlrliq_estornar.value = soma_total.toFixed(2);
          <?}else{?>
  	      parent.document.form1.vlrliq_estornar_nota.value = soma_total.toFixed(2);
          <?}?>
      }

       function js_coloca(tot){  //se vier true é porque o valor passado eh notas
	dados='';
	vir='';
        tot= new Number(tot);  
	obj = document.form1.elements;
	soma = 0;
	for(i=0; i<obj.length; i++ ){
          nome=obj[i].name.substring(0,8);
	  if(nome=="generico"){
	    codele = obj[i].name.substring(9);
  	    vlrlimite = new Number(eval("document.form1.disponivel_"+codele+".value"));//sem nota
  	    saldo  = new Number(eval("document.form1.saldo_"+codele+".value"));//liquidado com notas...

	    
  	    total  = new Number(eval("document.form1.e64_vlrliq_"+codele+".value"));
	    //variavel notas vem true quando a função for chamada pelos valores sem notas...
         
              liberado = new Number(vlrlimite - saldo);
             

            
            if( liberado<0 || liberado>vlrlimite){
	      liberado = vlrlimite;
	    }

	    if(tot >= (liberado)){
	      valor = liberado;
	      obj[i].value = valor.toFixed(2); 
	      tot = (tot-(liberado));
	    }else{
	      obj[i].value=tot.toFixed(2); 
	      tot= new Number(0);
	    }  
            d =new Number(obj[i].value);
	    soma = new Number(d+soma);
	    
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
	soma_total =  new Number();
	for(i=0; i<obj.length; i++ ){
          nome=obj[i].name.substring(0,8);
	  if(nome=="generico"){
	     codele = obj[i].name.substring(9);
  	     total  = new Number(eval("document.form1.e64_vlrliq_"+codele+".value"));
	     soma = new Number(obj[i].value); 
	     obj[i].value = soma.toFixed(2);
	     dados += vir+codele+"-"+soma+"-"+total;
	     vir='#';
	     tot = (soma+tot);
	     soma_total +=soma;
	  }
	}
	val = new Number(tot);
	if(parent.document.form1.vlrliq_estornar.readOnly==false){
  	  parent.document.form1.vlrliq_estornar.value = val.toFixed(2);
	}  
        parent.document.form1.dados.value=dados;
      }


      function js_verificar(obj,vlrdis){
	erro=false;
        vlrdis = new Number(vlrdis)
	vlrliq_estorna = new Number(obj.value);
	if(isNaN(vlrliq_estorna)){
	  eval("document.form1."+obj.name+".value=vlrdis.toFixed(2);");
	  js_calcular();
	  return false;
	}
	nome = obj.name.substring(9);
	vlrlimite = eval("document.form1.disponivel_"+nome+".value");
	if(vlrliq_estorna > vlrlimite){
	  erro=true;
	}  
	if(erro==false){
	   document.form1.verificador.value='ok';
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
 <table border='1' cellspacing="0" cellpadding="0">   
 <?
function db_transf($campo,$cod,$valor){
    $e=$campo."_".$cod;
    global $$e;
    $$e = number_format($valor,"2",".","");		       
}
 
 if(isset($e60_numemp) && $e60_numemp!= ""){

      $result = $clempnota->sql_record($clempnota->sql_query_file(null,"e69_numemp","","e69_numemp = $e60_numemp "));
      $numrows_nota = $clempnota->numrows;
      if($numrows_nota>0){
          $db_opcao_gen = "3";
      }else{
          $db_opcao_gen = $db_opcao;
      }      
      
      $result = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp,null,"*","e64_codele"));
      $numrows = $clempelemento->numrows;
      if($numrows>0){
	echo "
	    <tr class='bordas'>
	      <td class='bordas' align='center'><b><small>$RLo56_elemento</small></b></td>
	      <td class='bordas' align='center'><b><small>$RLo56_descr</small></b></td>

	      <td class='bordas' align='center'><b><small>$RLe64_vlremp</small></b></td>
	      <td class='bordas' align='center'><b><small>$RLe64_vlrliq</small></b></td>
	      <td class='bordas' align='center'><b><small>$RLe64_vlranu</small></b></td>
              <td class='bordas' align='center'><b><small>$RLe64_vlrpag</small></b></td>
	      
              <td class='bordas' align='center'><b><small>Ordem</small></b></td>
              <td class='bordas' align='center'><b><small>Pago ordem</small></b></td>
              <td  class='bordas'align='center'><b><small>Anulado</small></b></td>
	      
	      <td class='bordas' align='center'><b><small>Dísponivel</small></b></td>
	      <td class='bordas' align='center'><b><small>Estornar</small></b></td>
	    </tr>
	";
         for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i,true);
          //rotina que gera campos com o total de nota para o elemento do momento 
          $result02 = $clempnotaele->sql_record($clempnotaele->sql_query_ordem(null,null,"sum(e70_valor) as tot_valorn, sum(e70_vlrliq) as tot_vlrliqn, sum(e70_vlranu) as tot_vlranun","","e69_numemp=$e60_numemp and e70_codele=$o56_codele  and ((e71_codnota is   null  or  e71_anulado='t') ) ")); 
          db_fieldsmemory($result02,0,true);  

          $result02 = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,"sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu","","e50_numemp=$e60_numemp and e53_codele=$o56_codele")); 
	  db_fieldsmemory($result02,0,true);  
	  
	    
   
     //=======================================================================================> 
    //rotina que procura pega o total de ordens com  notas
	    $sql = $clempnotaele->sql_query_ordem(null,null," sum(e70_valor) as tot_valornord, sum(e70_vlrliq) as tot_vlrliqnord, sum(e70_vlranu) as    tot_vlranunord","","e69_numemp=$e60_numemp   and e70_vlrliq <> 0 and e70_codele=$o56_codele  and ((e71_codnota is  not  null  and e71_anulado='f') ) "); 
	    $result06 = $clempnotaele->sql_record($sql);
	    db_fieldsmemory($result06,0,true);
	    $tot_ordem_notas    =  $tot_valornord; 
	    $anu_ordem_notas    =  $tot_vlranunord; 
	    
	    $tot_ordem_semnotas =  $tot_valor  - $tot_ordem_notas;
	    $anu_ordem_semnotas =  $tot_vlranu - $tot_vlranunord;

    //=====================================================>
          
        	  
	            db_transf('saldo',$o56_codele,$tot_vlrliqn);
                    db_input("saldo_$o56_codele",8,0,true,'hidden',3);


                    /*tava assim
                    $empenho = $e64_vlrliq - $e64_vlrpag;  
                    $ordens  = $tot_valor - $tot_vlranu - $tot_vlrpag;
                    $vlrdis  = $empenho-$ordens;  
		    */
		    
                       
	            $vlrdis  =  $e64_vlrliq - ($tot_valor -$tot_vlranu);

		    if(isset($jafoipago) && $jafoipago==true){
                       $vlrdis = $vlrdis - ($e64_vlrpag-$tot_vlrpag);
		    } 
		    
                    if($vlrdis<0){
		      $vlrdis='0.00';
		    }
		      

		     


                     db_transf('e64_vlrliq',$o56_codele,$e64_vlrliq);
                     db_transf('e64_vlrpag',$o56_codele,$e64_vlrpag);
                     db_transf('e64_vlranu',$o56_codele,$e64_vlranu);
		     db_transf('e64_vlremp',$o56_codele,$e64_vlremp);

          	     db_transf('disponivel',$o56_codele,$vlrdis);
		      
                     //rotina que indica o  valor disponivel... 
		     if($numrows_nota>0){
		        db_transf('generico',$o56_codele,'0.00');
		     }else{
		        db_transf('generico',$o56_codele,$vlrdis);
		     }  

		      
		 //rotina que pega os valores de pagordemele
                        $result02  = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,"sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu","","e60_numemp=$e60_numemp and e53_codele=$o56_codele ")); 
		        db_fieldsmemory($result02,0,true);	

			db_transf('e53_vlrpag',$o56_codele,$tot_vlrpag);
			db_transf('e53_vlranu',$o56_codele,$tot_vlranu);
			db_transf('e53_valor',$o56_codele,$tot_valor);
                //fim

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
                    <td	 class='bordas_corp' align='center'><small>";db_input("generico_$o56_codele",8,4,true,'text',$db_opcao_gen,"onchange='js_verificar(this,$vlrdis);'")."</small></td>
	          </tr> 
	         ";
         }
      }	 
  }    
 ?>
 </table>
    </form> 
    </td>
  </tr>
</table>
</body>
</html>