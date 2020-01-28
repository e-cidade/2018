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

include("classes/db_pagordem_classe.php");
include("classes/db_pagordemconta_classe.php");
include("classes/db_empagetipo_classe.php");
include("classes/db_empord_classe.php");
include("classes/db_empagemov_classe.php");
include("classes/db_empagepag_classe.php");
include("classes/db_pcfornecon_classe.php");
include("classes/db_empageforma_classe.php");

$clempagetipo = new cl_empagetipo;
$clpagordem   = new cl_pagordem;
$clpagordemconta   = new cl_pagordemconta;
$clempord     = new cl_empord;
$clempagemov  = new cl_empagemov;
$clempagepag  = new cl_empagepag;
$clpcfornecon  = new cl_pcfornecon;
$clempageforma = new cl_empageforma;

//echo ($HTTP_SERVER_VARS["QUERY_STRING"]);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = false;

$clrotulo = new rotulocampo;
$clrotulo->label("e50_codord");
$clrotulo->label("o15_descr");
$clrotulo->label("o15_codigo");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_emiss");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e80_codage");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e53_valor");
$clrotulo->label("e53_vlranu");
$clrotulo->label("e53_vlrpag");
$clrotulo->label("e95_codigo");

$dbwhere = "(e53_valor-e53_vlranu-e53_vlrpag)>0";

$sql   = $clpagordem->sql_query_pagordemele2(null,"e80_codage,e50_data,o15_codigo,o15_descr,e60_emiss,e60_anousu,e60_numemp,e50_codord,z01_numcgm, z01_nome,sum(e53_valor) as e53_valor,sum(e53_vlranu) as e53_vlranu,sum(e53_vlrpag) as e53_vlrpag",""," e86_codmov is null and e60_instit = " . db_getsession("DB_instit") . " group by e80_codage,e60_numemp,e50_codord,e50_data,z01_numcgm,z01_nome,e60_emiss,o15_codigo,o15_descr,e60_anousu"); 

$sql02 =  "select * from ($sql) as x
	   where $dbwhere 
   	   order by e80_codage,e50_codord
	";
//echo($sql02);

$result09 = $clpagordem->sql_record($sql02); 
$numrows09= $clpagordem->numrows; 

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>
<script>
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].disabled==false){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
     if(OBJ.elements[i].checked==true){
         js_colocaval(OBJ.elements[i]);      
     }
   }
   return false;
}
function js_confere(campo){
	erro     = false;
	erro_msg = '';

	vlrgen= new Number(campo.value);
        
	
	if(isNaN(vlrgen)){
	    erro = true;
	}
	nome = campo.name.substring(6);
	
	vlrlimite = new Number(eval("document.form1.disponivel_"+nome+".value"));
	if(vlrgen > vlrlimite){
	  erro_msg = "Valor digitado é maior do que o disponível!";
	  erro=true;
	}  
        
        if(vlrgen == ''){
	   eval("document.form1."+campo.name+".value = '0.00';");
        }
        if(vlrgen == 0){
	  eval("document.form1.CHECK_"+nome+".checked=false");
	}else{
	  eval("document.form1.CHECK_"+nome+".checked=true");
	}
	
	if(erro==false){
	   eval("document.form1."+campo.name+".value = vlrgen.toFixed(2);");
	}else{  
	   if(erro_msg != ''){
	     //alert(erro_msg);
	   }
	   eval("document.form1."+campo.name+".focus()");
	   eval("document.form1."+campo.name+".value = vlrlimite.toFixed(2);");
	   return false;
	}  
  
}

function js_colocaval(campo){
  if(campo.checked==true){
    valor = new Number(eval('document.form1.disponivel_'+campo.value+'.value')); 
    v = valor.toFixed(2);
   eval('document.form1.valor_'+campo.value+'.value='+v);
  }else{
 //   eval('document.form1.valor_'+campo.value+'.value='+valor);
  }
}

function js_padrao(val){
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     nome = OBJ.elements[i].name;
     tipo = OBJ.elements[i].type;
     if( tipo.substr(0,6) == 'select' && nome.substr(0,11)=='e83_codtipo'){
       ord = nome.substr(12);
       checa = eval("document.form1.CHECK_"+ord+".checked");
       if(checa==false){
	 continue;
       } 
      for(q=0; q<OBJ.elements[i].options.length; q++){
	if(OBJ.elements[i].options[q].value==val){
	   OBJ.elements[i].options[q].selected=true;
	   break;
	 }
      }
    }
   }
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
<form name="form1" method="post" action="">
    <center>
      <table  class='bordas'>
        <tr>
          <td class='bordas02' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
          <td class='bordas02' align='center' ><small><b><?=$RLe80_codage?></b></small></td>
          <td class='bordas02' align='center' ><small><b><?=$RLe60_codemp?></b></small></td>
          <td class='bordas02' align='center'><small><b><?=$RLe50_codord?></b></small></td>
          <td class='bordas02' align='center'><small><b><?=$RLo15_descr?></b></small></td>
          <td class='bordas02' align='center'><small><b><?=$RLz01_nome?></b></small></td>
          <td class='bordas02' align='center'><small><b><?/*=$RLe60_emiss*/?>Conta</b></small></td>
          <td class='bordas02' align='center'><small><b>Forma</b></small></td>
          <td class='bordas02' align='center'><small><b>Total</b></small></td>
          <td class='bordas02' align='center'><small><b>Disp.</b></small></td>
          <td class='bordas02' align='center'><small><b>Valor</b></small></td>
          <td class='bordas02' align='center'><small><b><?=$RLe83_codtipo?></b></small></td>
	</tr>
        <?
	   $nords =  '';
	   $nvirg ='';
	   $arr_forma = Array();
	  $result_forma = $clempageforma->sql_record($clempageforma->sql_query_file(null,"e95_codigo,e95_descr","e95_codigo"));	  
	  for($i=0;$i<$clempageforma->numrows;$i++){
	    db_fieldsmemory($result_forma,$i);
	    $arr_forma[$e95_codigo] = $e95_descr;
	  }
	  for($i=0; $i<$numrows09; $i++){
            $dbwhere02='';
	    db_fieldsmemory($result09,$i,true);

	    $x= "e60_numemp_$e50_codord";
	    $$x = $e60_numemp;
	    

	   
           //--------------------------------------
	   //rotina que verifica se tem movimento para a ordem nesta agenda.. se tiver ele marca o campo checkbox
           $xeque = '';   
	   $result01 = $clempagemov->sql_record($clempagemov->sql_query_ord(null,'e81_codmov,e81_valor','',"e80_instit = " . db_getsession("DB_instit") . " and e82_codord=$e50_codord and e81_codage is not null"));
	   if($clempagemov->numrows>0){
	     $xeque = "checked";
	     db_fieldsmemory($result01,0,true);
             
	     //rotina que verifica quais movimentos eh para trazer.. se todos,selecionados e naum selecionados
	       if(isset($ordens) && $ordens == 'n'){
		 if($xeque != ''){
		   continue;
		 }
	       }
	       if(isset($ordens) && $ordens == 's'){
		 if($xeque == ''){
		   continue;
		 }
	       }

	     

               //---------------------------------------------------------
	       //pega o tipo do movimento
		 $result01 = $clempagepag->sql_record($clempagepag->sql_query_file(null,null,"e85_codtipo",null," e80_instit = " . db_getsession("DB_instit") . " and e85_codmov = $e81_codmov"));
		 if($clempagepag->numrows>0){
		   db_fieldsmemory($result01,0,true);
		   $x= "e83_codtipo_$e50_codord";
		   $$x = $e85_codtipo;
                   
		   $dbwhere02 = " or e83_codtipo=$e85_codtipo";
		   
		 }
              //-------------------------------------------------------------	 
	   }else{
	     //verifica se eh para trazer apenas os selecionados
	     if(isset($ordens) && $ordens == 's'){
	       continue;
	     }
 	     $e81_valor = '0.00';
          } 
	     
         //coloca o valor com campo
	   $x= "valor_$e50_codord";
	   $$x = number_format($e81_valor,"2",".","");

	  //rotina que verifica se existe valor disponivel 
	     $result03  = $clempagemov->sql_record($clempagemov->sql_query_ord(null,"e82_codord,sum(e81_valor) as tot_valor",""," e80_instit = " . db_getsession("DB_instit") . " and e82_codord = $e50_codord group by e82_codord "));
	     $numrows03 = $clempagemov->numrows;
	     if($numrows03 > 0){
	       db_fieldsmemory($result03,0);
	     }else{
	       $tot_valor ='0.00';
	     }  
	   
	    $total = $e53_valor - $e53_vlrpag - $e53_vlranu;
             
//	    $disponivel = $total - ($tot_valor - $e81_valor);
	    $disponivel = $total;
	    
	    
	    $x= "disponivel_$e50_codord";
	    $$x = number_format($disponivel,"2",".","");
	   //=-------------------------------------------
	   if($disponivel == 0 || $disponivel < 0  ){
	      // echo $e50_codord." sem valor disponivel!";
              $nords .= $nvirg.$e50_codord; 
	      $nvirg = " ,";
              continue;
	   }
	    
//	    echo "$disponivel = $total - ($tot_valor - $e81_valor);<br><br>";







          //pega os tipos
          $result05  = $clempagetipo->sql_record($clempagetipo->sql_query(null,"e83_codtipo as codtipo,e83_descr","e83_descr"));





	  
	  $numrows05 = $clempagetipo->numrows;
	  $arr['0']="Nenhum";
	  for($r=0; $r<$numrows05; $r++){
	    db_fieldsmemory($result05,$r);
            $arr[$codtipo] = $e83_descr;
	  }
          flush();
        
       

	    if(isset($e83_codtipo)  && $xeque == '' ){
	      $t = "e83_codtipo_$e50_codord";
	      $$t = $e83_codtipo;
	    }  

         //rotina que verifica se o fornecedor possui conta cadastrada para pagamento eletrônico
	 $outr = '';
         $result = $clpagordemconta->sql_record($clpagordemconta->sql_query($e50_codord,"e49_numcgm")); 
         if($clpagordemconta->numrows>0){
          db_fieldsmemory($result,0);
	   $numcgm = $e49_numcgm;
	     $outr = "<span style=\"color:red;\">**</span>";
         }else{
	   $numcgm = $z01_numcgm;
	 }
         
	 $result78 = $clpcfornecon->sql_record($clpcfornecon->sql_query_padrao(null,"pc63_banco,pc63_agencia,pc63_conta",'',"pc63_numcgm=$numcgm"));
	 if($clpcfornecon->numrows > 0){
             db_fieldsmemory($result78,0); 
	 }else{ 
	   $pc63_conta ='0';
	   $pc63_banco ='0';
	   $pc63_agencia ='0';

	 }
	?>
        <tr>
          <td class='bordas' align='right'
           onMouseOut='parent.js_labelconta(false);' onMouseOver="parent.js_labelconta(true,'<?=$pc63_banco?>','<?=$pc63_agencia?>','<?=$pc63_conta?>');"
	  ><input value="<?=$e50_codord?>" <?=$xeque?> name="CHECK_<?=$e50_codord?>" type='checkbox' onclick='js_colocaval(this);'></td>
          <td class='bordas' align='center'><small id="e80_codage_<?=$e50_codord?>">
	  <?
	  $codigodaagenda = 'e80_codage_'.$e50_codord;
	  $$codigodaagenda = $e80_codage;
	  db_input('e80_codage_'.$e50_codord,5,$Ie80_codage,true,'text',3);
	  ?>
	  </small></td>
          <td class='bordas' align='right' title="Data de emissão:<?=$e60_emiss?>"><small id="e60_numemp_<?=$e50_codord?>"> <?=$e60_numemp?></small></td>
          <td class='bordas' align='right' title="Data de emissão:<?=$e50_data?>"><small><?=$outr?><?=$e50_codord?></small></td>
          <td class='bordas' align='right'><small><?=$o15_descr?></small></td>
          <td class='bordas' label="Numcgm:<?=$z01_numcgm?>" style='cursor:help'  id="ord_<?=$e50_codord?>"
           onMouseOut='parent.js_labelconta(false);' onMouseOver="parent.js_labelconta(true,'<?=$pc63_banco?>','<?=$pc63_agencia?>','<?=$pc63_conta?>');"	  
	    ><small><?=$z01_nome?>  </small></td>
          <td class='bordas'><small><input type='button' name='con_<?=$e50_codord?>' value="Conta" onclick="js_conta('<?=$numcgm?>');">  </small></td>
          <td class='bordas' nowrap><small>  
	  <?
          db_select("for_$e50_codord",$arr_forma,$Ie95_codigo,1);
	  ?>
	  </small></td>
          <td class='bordas' align='right'  style='cursor:help' onMouseOut='parent.js_label(false);' onMouseOver="parent.js_label(true,'<?=$e53_vlrpag?>','<?=$e53_vlranu?>');"><small><?=$e53_valor?> </small></td>

          <td class='bordas' align='right'><small><?=db_input("disponivel_$e50_codord",6,$Iz01_numcgm,true,'text',3)?></small></td>
          <td class='bordas' align='right'><small><?=db_input("valor_$e50_codord",6,$Ie53_valor,true,'text',$db_opcao,"onChange='js_confere(this);'")?></small></td>
          <td class='bordas' align='right'><small><?=db_select("e83_codtipo_$e50_codord",$arr,true,1)?></small></td>
	</tr>
        <?
	  }
	?>
	<!--
	<tr>
	  <td class='bordas' align='left' colspan='11'>
	      <b>Ordens em outras agendas: <small></b><?=$nords?></small>
	  </td>
	</tr>
	-->
      </table>
    </center>
    </form>
    </td>
  </tr>
</table>
</body>
</html>
<script>
function js_conta(cgm){
  js_OpenJanelaIframe('top.corpo','db_iframe_pcfornecon','com1_pcfornecon001.php?novo=true&z01_numcgm='+cgm,'Pesquisa',true);
}


</script>