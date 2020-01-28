<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clempelemento = new cl_empelemento;
$clpagordemele = new cl_pagordemele;
$clpagordem = new cl_pagordem;
$clempnotaele = new cl_empnotaele;
$clempnota = new cl_empnota;
$clrotulo = new rotulocampo;
$clrotulo->label("o56_descr");
$clrotulo->label("o56_descr");
$clrotulo->label("e64_vlrliq");
$clrotulo->label("e64_vlranu");
$clrotulo->label("e64_vlremp");
$clrotulo->label("e64_vlrpag");
$clrotulo->label("e53_vlrpag");
$clrotulo->label("e53_vlranu");
$clrotulo->label("e53_valor");
$clrotulo->label("o56_elemento");
if($desabilita==true || $db_opcao==3){
  $db_opcao_disab=33;
}else{
  $db_opcao_disab=1;
}
?>
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
	     tot  = tot.toFixed(2);
	         eval("document.form1.generico_"+elemento+".value="+tot);


	   }
	js_calcular();
      }


      function js_coloca(tot){
	dados='';
	vir='';
        tot= new Number(tot);
	obj = document.form1.elements;

	for(i=0; i<obj.length; i++ ){
          nome=obj[i].name.substring(0,8);
          nomefim=obj[i].name.substring(0,13);

	  if(nome=="generico" && nomefim != "generico_nota"){
	    codele = obj[i].name.substring(9);

            vlrlimite = new Number(eval("document.form1.disponivel_"+codele+".value"));

            saldo     = new Number(eval("document.form1.saldo_nota_"+codele+".value"));
	    liberado = new Number(vlrlimite - saldo);

	    if(liberado<0 || liberado==0){
               liberado = 0;
	    }

	    if(tot >= liberado  && ((liberado!=0 && tot!=0) || (liberado==0 && tot==0))){
	      obj[i].value = liberado.toFixed(2);
	      tot = (tot-liberado);
	    }else{
	      obj[i].value=tot.toFixed(2);
	      tot= new Number(0);
	    }

	     dados += vir+codele+"-"+obj[i].value;
	     vir='#';
	  }
	}
	//parent.document.form1.dados.value=dados7;
	js_calcular();

      }



      function js_calcular(){
	vir='';
	dados='';
	obj = document.form1.elements;
	tot= 0;
	for(i=0; i<obj.length; i++ ){
          nome=obj[i].name.substring(0,8);
          nomefim=obj[i].name.substring(0,13);
	  if(nome=="generico" && nomefim != "generico_nota"){
	     codele = obj[i].name.substring(9);

	     soma = new Number(obj[i].value);
	     obj[i].value = soma.toFixed(2);
	     dados += vir+codele+"-"+obj[i].value;


	     vir='#';
	     tot = (soma+tot);
	  }
	}
	val = new Number(tot);
        if(val==0 && parent.document.form1.vlrdis.value != "" && parent.document.form1.vlrdis.value != 0){
          val = new Number(parent.document.form1.vlrdis.value);
          js_coloca(val.toFixed(2),false,false);
        }

	parent.document.form1.vlrpag.value = val.toFixed(2);
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


	nome    = obj.name.substring(9);
        vlrdis  = new Number(eval("document.form1.disponivel_"+nome+".value"));
	if(vlrpag > vlrdis){
	  erro=true;
	}

	saldo  = new Number(eval("document.form1.saldo_"+nome+".value"));
	saldo_nota  = new Number(eval("document.form1.saldo_nota_"+nome+".value"));

	if(vlrpag>saldo){
	  alert('Já existe nota para este valor!');
	  erro=true;
	  vlrdis = saldo;
	}


	if(erro==false){
           js_calcular();
	  return true;
	}else{
	  eval("document.form1."+obj.name+".focus()");
	  eval("document.form1."+obj.name+".value= vlrdis.toFixed(2);");
	  js_calcular();
	  return false;
	}
      }
</script>
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
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload='js_calcular();' >
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr>
    <td  align="center" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <input name='verificador' type='hidden' value='ok'>
    <center>
  <table border='1' cellspacing="0" cellpadding="1" class='bordas02'>
 <?
 if(isset($e60_numemp) && $e60_numemp!= ""){
//     echo $clempelemento->sql_query($e60_numemp,null,"*","e64_codele");
      $result = $clempelemento->sql_record($clempelemento->sql_query($e60_numemp,null,"*","e64_codele"));
      $numrows = $clempelemento->numrows;


      if($numrows>0){
	echo "
	    <tr class='bordas'>
	      <td class='bordas' align='center'><b><small>$RLo56_elemento</small></b></td>

	      <td class='bordas' align='center'><b><small>Empenho</small></b></td>
	      <td class='bordas' align='center'><b><small>Liquidado</small></b></td>
	      <td class='bordas' align='center'><b><small>Anulado</small></b></td>
              <td class='bordas' align='center'><b><small>Pago</small></b></td>";
	      //quando for inclusão não será mostrado as ordens existentes
	      if($db_opcao!=1){
 	 	echo "
        	      <td class='bordas' align='center'><b><small>Ordem</small></b></td>
	              <td class='bordas' align='center'><b><small>Pago</small></b></td>
	 	      <td class='bordas' align='center'><b><small>Anulado </small></b></td>
		";
	       }
	     if($db_opcao==2){
                $nome =   "Adic. s/nota  " ;
	     }else if($db_opcao==3){
                $nome =  "Anular";
	     }else{
	       $nome="Valor s/nota";
             }
           echo "<td class='bordas' align='center'><b><small>Dísponivel</small></b></td>
		 ";

       echo " <td class='bordas' align='center'><b><small>$nome </small></b></td>";
   echo "  </tr>";
         $eles =  '';
	 $vir='';
         for($i=0; $i<$numrows; $i++){
	    db_fieldsmemory($result,$i);

	     $eles .=$vir.$o56_codele;
 	     $vir = "#";

	     //rotina que traz as notas que ainda nao foram liquidadar
	      $sql_notasliq = $clempnotaele->sql_query(null,null,"(sum(e70_valor)  - sum(e70_vlranu))  as saldo_notasliq","","e60_numemp=$e60_numemp and e70_codele=$o56_codele and e70_vlrliq = 0  group by e70_codnota ");
	      $result_notasliq = $clempnotaele->sql_record($sql_notasliq);
	      $numrows_notasliq = $clempnotaele->numrows;
	      if($numrows_notasliq > 0){
		db_fieldsmemory($result_notasliq,0,true);
	      }else{
		$saldo_notasliq = '0.00';
	      }
	     //--------------- // ------------ // --------------------------


           //rotina que traz os dados do empnotaele
	     if($db_opcao==3){
	        $sql = $clempnotaele->sql_query_ordem(null,null," e70_codele,e71_anulado,sum(e70_valor) as tot_valor_nota, sum(e70_vlrliq) as tot_vlrliq_nota, sum(e70_vlranu) as tot_vlranu_nota","","e69_numemp=$e60_numemp and e71_codord = $e50_codord and  e70_codele=$e64_codele and e70_vlrliq <> 0 and ((e71_codnota is not  null and e71_anulado='f') ) group  by e70_codele,e71_anulado  ");
	     }else{
	        $sql = $clempnotaele->sql_query_ordem(null,null," e70_codele,e71_anulado,sum(e70_valor) as tot_valor_nota, sum(e70_vlrliq) as tot_vlrliq_nota, sum(e70_vlranu) as tot_vlranu_nota","","e69_numemp=$e60_numemp and e70_codele=$e64_codele and e70_vlrliq <> 0 and ((e71_codnota is  null) or (e71_codnota is not null and e71_anulado='t') ) group  by e70_codele,e71_anulado ");
	     }
	     $result65 = $clempnotaele->sql_record($sql);
	     if($clempnotaele->numrows>0){
		db_fieldsmemory($result65,0,true);

	     }else{
	       $tot_valor_nota = '0.00';
	       $tot_vlrliq_nota = '0.00';
	       $tot_vlranu_nota = '0.00';
	     }
	   //--------------------------------------------------------------


             $result09 = $clempelemento->sql_record($clempelemento->sql_query_file($e60_numemp,$e64_codele,"sum(e64_vlrliq) as total_vlrliq ,sum(e64_vlrpag) as total_vlrpag,sum(e64_vlranu) as total_vlranu"));
             db_fieldsmemory($result09,0);

		 //rotina que pega os valores de pagordemele
                        $result02  = $clpagordemele->sql_record($clpagordemele->sql_query(null,null,"sum(e53_valor) as tot_valor, sum(e53_vlrpag) as tot_vlrpag, sum(e53_vlranu) as tot_vlranu","","e60_numemp=$e60_numemp and e53_codele=$o56_codele "));
		          db_fieldsmemory($result02,0,true);
                //fim

		//rotina que pega os valores de cada elemento
		    if($db_opcao==2 || $db_opcao==3){
			$result05 = $clpagordemele->sql_record($clpagordemele->sql_query_file($e50_codord,$o56_codele));
			if($clpagordemele->numrows>0){
			  db_fieldsmemory($result05,0);
		        }else{
 			    $e53_vlrpag  = '0.00';
          	            $e53_vlranu = '0.00';
	                    $e53_valor  = '0.00';
			}
		    }
		//final

		    if($db_opcao!=1 && $e50_codord!=""){//quando naum for inclusao
		      $e="e53_vlrpag_$o56_codele";
		      $$e = number_format($e53_vlrpag,"2",".","");
		      $e="e53_vlranu_$o56_codele";
		      $$e = number_format($e53_vlranu,"2",".","");
		      $e="e53_valor_$o56_codele";
		      $$e = number_format($e53_valor,"2",".","");
                   }else{
		      $e="e53_vlrpag_$o56_codele";
		      $$e = "0.00";
		      $e="e53_vlranu_$o56_codele";
		      $$e = "0.00";
		      $e="e53_valor_$o56_codele";
		      $$e = "0.00";
		   }
		     $e="e64_vlremp_$o56_codele";
		     $$e = number_format($e64_vlremp,"2",".","");

		     $e="e64_vlrliq_$o56_codele";
		     $$e = number_format($e64_vlrliq,"2",".","");

		     $e="e64_vlranu_$o56_codele";
		     $$e = number_format($e64_vlranu,"2",".","");

		     $e="e64_vlrpag_$o56_codele";
		     $$e = number_format($e64_vlrpag,"2",".","");

                    //rotina que calcula os valores disponiveis
		   if($db_opcao==1 ){
		       $vlrdis = number_format( ( ($total_vlrliq-$total_vlrpag) -   ($tot_valor - $tot_vlranu - $tot_vlrpag)  ),"2",".","");

		       $saldo_nota = ($tot_valor_nota-$tot_vlranu_nota) ;
               	       if($saldo_nota>$vlrdis){
                	  $saldo = '0.00';
             	       }else{
	                  $saldo = $vlrdis-$saldo_nota;
               	       }



		   }else if($db_opcao==2 ){
		     /*
                       $vlrdis = ($total_vlrliq-$total_vlrpag) -   ($tot_valor - $tot_vlranu - $tot_vlrpag) - ($tot_valor_nota-$tot_vlranu_nota) ;
		       $vlrdis_nota = $tot_valor_nota-$tot_vlranu_nota;
		     */

		       $vlrdis = number_format( ( ($total_vlrliq-$total_vlrpag) -   ($tot_valor - $tot_vlranu - $tot_vlrpag)  ),"2",".","");

		       $saldo_nota = ($tot_valor_nota-$tot_vlranu_nota) ;
               	       if($saldo_nota>$vlrdis){
                	  $saldo = '0.00';
             	       }else{
	                  $saldo = $vlrdis-$saldo_nota;
               	       }

		   }else if($db_opcao==3){

		       $vlrdis = number_format((  ($e53_valor-$e53_vlranu-$e53_vlrpag) ),"2",".","");

                       $saldo_nota = $tot_valor_nota-$tot_vlranu_nota;
		       $saldo = $vlrdis - $saldo_nota;


/*

    		       $vlrdis_geral = number_format((  ($e53_valor-$e53_vlranu-$e53_vlrpag) ),"2",".","");
                       $vlrdis =  $vlrdis_geral;

                       $vlrdis_nota = $vlrdis_geral;

    		       $vlrdis_snota =  ($e53_valor-$e53_vlranu) - ($tot_valor_nota-$tot_vlranu_nota);

*/
      		   }

		   $a="saldo_$o56_codele";
  		   $$a = number_format($saldo,"2",".","");

		   $a="saldo_nota_$o56_codele";
  		   $$a = number_format($saldo_nota,"2",".","");

	           db_input("saldo_$o56_codele",7,0,true,'hidden',3);
	           db_input("saldo_nota_$o56_codele",7,0,true,'hidden',3);




		     $a="disponivel_$o56_codele";
  		     $$a = number_format($vlrdis,"2",".","");

		   $a="generico_$o56_codele";
                   $$a = '0.00';

	    echo "<tr>
   	            <td class='bordas_corp' align='center' title='jose'><small>$o56_elemento </small></td>
       	            <td class='bordas_corp' align='center'><small>";db_input("e64_vlremp_$o56_codele",7,0,true,'text',3);echo "\n</small></td>
	            <td class='bordas_corp' align='center'><small>";db_input("e64_vlrliq_$o56_codele",7,0,true,'text',3);echo "</small></td>
                    <td class='bordas_corp' align='center'><small>";db_input("e64_vlranu_$o56_codele",7,0,true,'text',3);echo "</small></td>
                    <td class='bordas_corp' align='center'><small>";db_input("e64_vlrpag_$o56_codele",7,0,true,'text',3);echo "</small></td>";

	      //quando for inclusão não será mostrado as ordens existentes
	      if($db_opcao!=1){
                    echo "
                    <td class='bordas_corp' align='center'><small>";db_input("e53_valor_$o56_codele",7,0,true,'text',3);echo "</small></td>
                    <td class='bordas_corp' align='center'><small>";db_input("e53_vlrpag_$o56_codele",7,0,true,'text',3);echo "</small></td>
                    <td class='bordas_corp' align='center'><small>";db_input("e53_vlranu_$o56_codele",7,0,true,'text',3);echo "</small></td>";
	      }
               if($db_opcao==3){
	            echo "
                    <td class='bordas_corp' align='center'><small>";db_input("disponivel_$o56_codele",7,0,true,'text',3);echo "</small></td>";
                    db_input("disponivel_nota_$o56_codele",7,0,true,'hidden',3);
 	       }else{
	            echo "
                    <td class='bordas_corp' align='center'><small>";db_input("disponivel_$o56_codele",7,0,true,'text',3);echo "</small></td>";
	       }
           echo "   <td class='bordas_corp' align='center'><small>";db_input("generico_$o56_codele",7,4,true,'text',$db_opcao_disab,"onchange='js_verificar(this,$vlrdis);'")."</small></td>
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
<script>
<?
if(isset($eles)){
?>

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
 function js_zeracampos(notas){
   var eles = "<?=$eles?>";
   arr = eles.split("#");
   for(i=0; i<arr.length; i++){
     elemento =  arr[i];
     if(elemento!=''){
       if(notas==true){
         eval("document.form1.generico_"+elemento+".value = '0.00'");
       }else{
         eval("document.form1.generico_"+elemento+".value = '0.00'");
       }
     }
   }
 }
<?
}
?>
</script>