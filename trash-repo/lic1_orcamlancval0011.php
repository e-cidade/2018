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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_pcorcamitem_classe.php");
include("classes/db_pcorcamval_classe.php");
include("classes/db_liclicitemlote_classe.php");
include("classes/db_empparametro_classe.php");

$clpcorcamitem    = new cl_pcorcamitem;
$clpcorcamval     = new cl_pcorcamval;
$clliclicitemlote = new cl_liclicitemlote;
$clrotulo         = new rotulocampo;
$clempparametro = new cl_empparametro;

$clpcorcamitem->rotulo->label();
$clrotulo->label('pc23_valor');
$clrotulo->label('pc23_vlrun');
$clrotulo->label('pc23_quant');
$clrotulo->label('pc23_obs');
$clrotulo->label('pc23_validmin');
$clrotulo->label('pc32_motivo');
$clrotulo->label('pc11_vlrun');
$clrotulo->label('pc11_quant');
$clrotulo->label('pc11_resum');

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
//echo "1 => ".$HTTP_SERVER_VARS["QUERY_STRING"]."<br>";
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);

$arr_vlnomesitens = Array();
$arr_valoresitens = Array();
$arr_quantitens = Array();
$arr_vtnomesitens = Array();

$res_empparametro = $clempparametro->sql_record($clempparametro->sql_query(db_getsession("DB_anousu"),"e30_numdec")); if ($clempparametro->numrows > 0){ 
  db_fieldsmemory($res_empparametro,0); 
  if (trim($e30_numdec) == "" || $e30_numdec == 0){ 
           $numdec = 2; 
     } 
  else { 
           $numdec = $e30_numdec; 
      } 
} 
else { 
  $numdec = 2; } 





?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_verquant(nome,val,max,param){
  val = new Number(val);
  max = new Number(max);
  erro= 0;
  if(val>max){
    erro++;
    alert('Usuário: \n\nQuantidade orçada deve ser menor que a quantidade do pedido.\n\nAdministrador:');
  }
  if(val<0){
    erro++;
    alert('Usuário: \n\nQuantidade orçada deve ser maior que 0 (zero).\n\nAdministrador:');
  }
  if(erro>0){
    eval("document.form1."+nome+".value='"+max+"';");
    eval("document.form1."+nome+".focus();");
  }else{
    valorunit  = eval("document.form1.vlrun_"+param+".value");  
    valortotal = eval("document.form1.valor_"+param+".value");
    verpos = 0;
    vltot  = 0;
    if(valorunit!=""){
      valorunit = valorunit.replace(',','.');
      verpos = 1;    
    }else if(valortotal!=""){
      valortotal = valortotal.replace(',','.');
      verpos = 2;
    }

    if(verpos==1){
      valpos = valorunit;
    }else if(verpos==2){
      valpos = valortotal;
    }

    dec = 2;
    if(verpos!=0){
      pos = valpos.indexOf('.');
      if(pos!=-1){
	tam = new Number(valpos.length);
	qts = valpos.slice((pos+1),tam);
	dec = 2;
      }
      if(dec<=1){
	dec = 2;
      }
      valpos = new Number(valpos);
    }
    if(verpos==1){
      vltot = new Number(valpos*val);
      eval("document.form1.valor_"+param+".value='"+vltot.toFixed(2)+"'");
    }else if(verpos==2){
      vltot = new Number(valpos/val);
      eval("document.form1.vlrun_"+param+".value='"+vltot.toFixed(<?=$numdec?>)+"'");
    }
  }
}

function js_calcvaltot(valor,param,nome){
  if(!isNaN(valor)){
    dec = 2;
    pos = valor.indexOf('.');
    if(pos!=-1){
      tam = new Number(valor.length);
      qts = valor.slice((pos+1),tam);
      dec = qts.length;      
    }
    if(dec<=1){
      dec = 2;
    }
    quant = eval("document.form1.qtde_"+param+".value");
    valortotal = new Number(eval("document.form1.valor_"+param+".value"));
    if(valor!='' && quant!=''){
      valor = new Number(valor);
      quant = new Number(quant);    
      valortotal = new Number(quant*valor);
    }
    if(valor==""){
      valor = 0;
    }

    eval("document.form1.valor_"+param+".value='"+valortotal.toFixed(2)+"'");
    eval("document.form1."+nome+".value='"+valor.toFixed(<?=$numdec?>)+"'");
    if(valortotal==0){      
      eval("document.form1."+nome+".value='0.00'");
    }
  }else{
    eval("document.form1.vlrun_"+param+".value=''");
  }
}

function js_calcvalunit(valor,param,nome){
  if(!isNaN(valor)){
    dec = 2;
    pos = valor.indexOf('.');
    if(pos!=-1){
      tam = new Number(valor.length);
      qts = valor.slice((pos+1),tam);
      dec = qts.length;      
    }
    if(dec<=1){
      dec = 2;
    }
    quant = eval("document.form1.qtde_"+param+".value");
    valorunit = new Number(eval("document.form1.vlrun_"+param+".value"));
    if(valor!='' && quant!=''){
      valor = new Number(valor);
      quant = new Number(quant);    
      valorunit = new Number(valor/quant);
    }
    if(valor==""){
      valor = 0;
    }
    eval("document.form1.vlrun_"+param+".value='"+valorunit.toFixed(<?=$numdec?>)+"'");
    eval("document.form1."+nome+".value='"+valor.toFixed(2)+"'");
    if(valorunit==0){
      eval("document.form1.vlrun_"+param+".value='0.00'");
    }
  }else{
    eval("document.form1.valor_"+param+".value=''")
  }
}

function js_passacampo(campo,vsubtr){
  index = 0;
  for(i=0;i<document.form1.length;i++){
    if(document.form1.elements[i].type=='text'){      
      if(document.form1.elements[i].name==campo){
	index = i;
	break;
      }
    }
  }
  if(index != 0){
    if((index+4)<=document.form1.length){
      document.form1.elements[index+4].select();
      document.form1.elements[index+3].focus();
    }else{
      if(vsubtr=="vlrun_"){
        document.form1.elements[2].select();
        document.form1.elements[2].focus();
      }else{
        document.form1.elements[3].select();
        document.form1.elements[3].focus();
      }
    }
  }
}
function js_somavalor(){
  obj=document.form1;
  somavalor=0;
  for (i=0;i<obj.elements.length;i++){
    if (obj.elements[i].name.substr(0,6)=="valor_"){
        if (obj.elements[i].value!=""&&obj.elements[i].value!=0){
          var objvalor=new Number(obj.elements[i].value);      
          somavalor+=objvalor;
        }
      }
    }
  
  document.form1.somavalor.value=somavalor.toFixed(2);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }
<?$corfundo="E4F471"?>       
.bordas_corp_descla{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: <?=$corfundo?>;
       }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post">
<table  border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td  align="center" valign="top" bgcolor="#CCCCCC"> 
      <table border='1' cellspacing="0" cellpadding="0">   
      <?
      $codprocant="";
        $camposelected = "";
        if(isset($pc20_codorc) && trim($pc20_codorc) != ''){
	  $ok = false;
	  if(isset($pc21_orcamforne) && trim($pc21_orcamforne)!=''){
	    $ok = true;
	  }
	 
    $dbwhere = "";
    if(isset($l20_codigo)&&trim($l20_codigo)!=""){
        $res_liclicitemlote = $clliclicitemlote->sql_record($clliclicitemlote->sql_query_licitacao(null,"l04_liclicitem",null,"l20_codigo = $l20_codigo and l21_situacao = 0 and l04_descricao = '".@$descricao."'"));
        if ($clliclicitemlote->numrows > 0){
             $numrows = $clliclicitemlote->numrows;

             $dbwhere     = "and l21_codigo in (";
             $lista_itens = "";
             $virgula     = "";
             for($i = 0; $i < $numrows; $i++){
                  db_fieldsmemory($res_liclicitemlote,$i);
                  $lista_itens .= $virgula.$l04_liclicitem;
                  $virgula      = ", ";
             }

             $dbwhere .= $lista_itens.")";
        }
    }

// Mostra descricao do lote - TODOS
    if (!isset($descricao)&&trim(@$descricao)==""&&isset($l20_codigo)&&trim($l20_codigo)!="") {
      
       $sSqlLote = $clliclicitemlote->sql_query_licitacao(null,
                                                          "l04_liclicitem,
                                                           l04_descricao",
                                                           null,
                                                           "l20_codigo = $l20_codigo
                                                            and l21_situacao = 0");
        $res_liclicitemlote = $clliclicitemlote->sql_record($sSqlLote);
        $numrows_lote       = $clliclicitemlote->numrows;
        $mostra_lote        = true;
        $cols               = 11;
        $ordem              = "l04_descricao,l21_ordem,pc81_codproc,pc22_orcamitem";
        
    } else {
        $mostra_lote        = false;
        $cols               = 10;
        $ordem              = "pc81_codproc,l21_ordem,l04_descricao,pc22_orcamitem";
    }
    $sSqlItens     = $clpcorcamitem->sql_query_pcmaterlic(null, 
                                                          "distinct l21_ordem,
                                                           pc81_codprocitem, 
                                                           pc81_codproc,
                                                           pc11_seq,
                                                           pc11_resum,
                                                           pc11_codigo,
                                                           pc11_vlrun,
                                                           pc11_quant,
                                                           pc01_descrmater,
                                                           pc22_orcamitem,
                                                           l21_codigo,
                                                           l20_usaregistropreco,
                                                           pc11_resum,
                                                           pc32_orcamitem,
                                                           pc32_orcamforne,
                                                           l04_descricao as descr_lote",
                                                           $ordem,
                                                           "pc20_codorc={$pc20_codorc}
                                                            and pc21_orcamforne = {$pc21_orcamforne}
                                                            and l21_situacao = 0 {$dbwhere}");
	  $result_itens  = $clpcorcamitem->sql_record($sSqlItens);
	  $numrows_itens = $clpcorcamitem->numrows;

	  //echo($clpcorcamitem->sql_query_pcmaterlic(null,"distinct l21_ordem,pc81_codprocitem, pc81_codproc,pc11_seq,pc11_resum,pc11_codigo,pc11_vlrun,pc11_quant,pc01_descrmater,pc22_orcamitem,l21_codigo,pc11_resum,pc32_orcamitem,pc32_orcamforne,l04_descricao as descr_lote",$ordem,"pc20_codorc=$pc20_codorc and pc21_orcamforne = $pc21_orcamforne and l21_situacao = 0 $dbwhere"));
    //exit;
//    db_criatabela($result_itens); exit;

          if($numrows_itens>0){
	    echo "
	    <tr class='bordas_corp'>
	      <td class='bordas_corp' colspan='$cols' align='center' nowrap ><b> LANÇAR VALORES DOS ORÇAMENTOS </b></td>
	    </tr>
	  ";
            for($i=0;$i<$numrows_itens;$i++){
              db_fieldsmemory($result_itens,$i);

              $str_lote = "";
              if (substr($descr_lote,0,9) == "AUTO_LOTE"){
                   $str_lote   = str_replace("_","",$descr_lote);
              } elseif (substr($descr_lote,0,13) == "LOTE_AUTOITEM"){
                   $str_lote   = str_replace("_","",$descr_lote);
              }

              if (strlen($str_lote) > 0){
                   $descr_lote = $str_lote;
              }

              

              if ($codprocant!=$pc81_codproc){
             echo "
<tr class='bordas_corp'>
	      <td class='bordas_corp' colspan='$cols' align='left' nowrap ><b> Processo de Compras Nº $pc81_codproc </b></td>
	    </tr> 
              <tr class='bordas'>
	      <td nowrap class='bordas' align='center'><a href='#' onClick='js_pcorcamdescla();' title='Desclassificar itens'>M</a></td>
	      <td nowrap class='bordas' align='center'>Item</td>
	      <td nowrap class='bordas' align='center'>Seq. Item</td>
	      <td nowrap class='bordas' align='center'><b>Descrição</b></td>";

        if ($mostra_lote == true){
             echo "<td nowrap class='bordas' align='center'><b>Lote</b></td>";
        }

        echo "<td nowrap class='bordas' align='center'><b>Obs</b></td> ";
		    echo "<td nowrap class='bordas' align='center'><b>$RLpc23_validmin</b></td>";
	      echo "<td nowrap class='bordas' align='center'><b>Quantidade</b></td>";
	      if ($l20_usaregistropreco == 't') {
	        
	        echo "<td nowrap class='bordas' align='center'><b>Qtde. min</b></td>";
          echo "<td nowrap class='bordas' align='center'><b>Valor Max.</b></td>";
          
	      }
	      echo "<td nowrap class='bordas' align='center'><b>Qtde. orçada</b></td>";
	      echo "<td nowrap class='bordas' align='center'><b>Valor Unit.</b></td>";
	      echo "<td nowrap class='bordas' align='center'><b>Valor total</b></td>";
	      echo "</tr>";
	    $codprocant=$pc81_codproc;
              }
	      if($ok == true){
                $result_lancados = $clpcorcamval->sql_record($clpcorcamval->sql_query_file(@$pc21_orcamforne,@$pc22_orcamitem,"pc23_valor as valor_$pc22_orcamitem,pc23_vlrun as vlrun_$pc22_orcamitem,pc23_obs as obs_$pc22_orcamitem, pc23_quant as qtde_$pc22_orcamitem,pc23_validmin  as pc23_validmin_$pc22_orcamitem","pc23_orcamitem"));


		if($clpcorcamval->numrows>0){
		  db_fieldsmemory($result_lancados,0);
		}
              }
	      if(trim($pc01_descrmater)==""){
		$pc01_descrmater = $pc11_resum;
              }
	      if($i==0){
		$camposelected = "vlrun_$pc22_orcamitem";
	      }

        if (isset($pc32_orcamitem)  && trim($pc32_orcamitem) != "" && 
            isset($pc32_orcamforne) && trim($pc32_orcamitem) != "" &&
            $pc32_orcamitem == $pc22_orcamitem && $pc32_orcamforne == $pc21_orcamforne){
             $disabled = "disabled";
             $class    = "bordas_corp_descla";
        } else {
             $disabled = "";
             $class    = "bordas_corp";
        }

        $check = "chk_".$pc22_orcamitem."_".$descr_lote;

	      echo "
        <tr class='$class' width='15%'>
      		<td align='center'  class='$class' width='15%'>
           <input name='chk_$pc22_orcamitem' type='checkbox' value='$check' onClick='js_desclassifica(\"$descr_lote\");' $disabled>
          </td>

  	      <td align='left'    class='$class' width='15%'>
            $pc81_codprocitem
          </td>
          
          <td align='center' class='$class' width='15%'>
            $l21_ordem
          </td>

  	      <td align='left' nowrap class='$class' width='55%' onMouseOver=\"js_mostra_text(true,'div_text_$pc22_orcamitem',event,this);\" onMouseOut=\"js_mostra_text(false,'div_text_$pc22_orcamitem',event,this);\">
            $pc01_descrmater
          </td>";

    if ($mostra_lote == true) {
         for($ii = 0; $ii < $numrows_lote; $ii++){
              db_fieldsmemory($res_liclicitemlote,$ii);

              if ($l04_liclicitem == $l21_codigo){
                   echo "<td align='center' class='bordas_corp' width='55%'>".$l04_descricao."</td>";
                   break;
              }
         }
    }

    echo "
		<td align='center'  class='$class'>";
		db_input("obs_$pc22_orcamitem",30,$Ipc23_obs,true,'text',$db_opcao,"onchange='document.form1.vlrun_$pc22_orcamitem.select();' $disabled");
	      echo "
	        </td>
<td align='center' nowrap  class='$class'>";
$dia = "pc23_validmin_".$pc22_orcamitem."_dia";
$mes = "pc23_validmin_".$pc22_orcamitem."_mes";
$ano = "pc23_validmin_".$pc22_orcamitem."_ano";
db_inputdata("pc23_validmin_$pc22_orcamitem",@$$dia,@$$mes,@$$ano,true,"text",$db_opcao,$disabled);
 echo "</td> <td align='center'  class='$class' width='15%'>";
   ${"qtdeOrcada_$pc22_orcamitem"}=$pc11_quant;
   db_input("qtdeOrcada_$pc22_orcamitem",10,$Ipc11_quant,true,'text',3);
   echo "</td>";
   if ($l20_usaregistropreco == 't') {
     
     $sSqlQuantidades = $clpcorcamitem->sql_query_pcmaterregistro($pc22_orcamitem,"pc57_quantmin,pc57_quantmax");
     $rsQuantidades   = $clpcorcamitem->sql_record($sSqlQuantidades);
     $nQuantMin = 0;
     $nQuantMax = 0;
     if ($clpcorcamitem->numrows > 0) {
       
       $oInfoRegistroPreco = db_utils::fieldsMemory($rsQuantidades, 0);
       $nQuantMax          = $oInfoRegistroPreco->pc57_quantmax;
       $nQuantMin          = $oInfoRegistroPreco->pc57_quantmin;
     }
     echo "<td class='$class'>{$nQuantMin}</td>";
     echo "<td class='$class'>{$nQuantMax}</td>";        
     
   }
   echo "<td align='center'  class='bordas_corp' >";

		$qtd   = "qtde_$pc22_orcamitem";
		$vlrun = "vlrun_$pc22_orcamitem";
		$valor = "valor_$pc22_orcamitem";
    $qtdeOrcada="qtdeOrcada_$pc22_orcamitem";

		$arr_vlnomesitens[$i] = "vlrun_$pc22_orcamitem";
		$arr_valoresitens[$i] = $pc11_vlrun;
		$arr_quantitens[$i] = $pc11_quant;
    $arr_vtnomesitens[$i] = "valor_$pc22_orcamitem";
    $arr_quantitensOrcada[$i] ="qtdeOrcada_$pc22_orcamitem"; 
	
  if($clpcorcamval->numrows>0){
		  if(strpos($$valor,".")==""){
		    $$valor .= ".00";
        
		  }
		}

		if(!isset($$qtd) || (isset($$qtd) && $$qtd=='')){
		  $$qtd = $pc11_quant;
		}
	$db_opcaoquant = 1;
	if ($l20_usaregistropreco == 't') {
	  $db_opcaoquant = 3;
	}
  db_input("qtde_$pc22_orcamitem",10,$Ipc23_quant,true,'text',$db_opcaoquant,"onchange='js_verquant(this.name,this.value,$pc11_quant,$pc22_orcamitem);js_somavalor();' $disabled");
	echo "</td>	"; 
	echo "<td align='center'  class='$class'>";
	db_input("vlrun_$pc22_orcamitem",10,$Ipc23_valor,true,'text',$db_opcao,"onchange='js_calcvaltot(this.value,$pc22_orcamitem,this.name); js_passacampo(this.name,this.name.substr(0,6)); js_somavalor();' $disabled");
	      echo "
	        </td>	      
		<td align='center'  class='$class' width='15%'>";
		db_input("valor_$pc22_orcamitem",10,$Ipc23_valor,true,'text',$db_opcao,"onchange=\"js_calcvalunit(this.value,$pc22_orcamitem,this.name);js_passacampo(this.name,this.name.substr(0,6));js_somavalor();\" $disabled");
	      echo" 
	        </td>
	      </tr>";
	      if(isset($$qtd) && $$qtd!=""){
		echo "<script>js_verquant('".$qtd."','".$$qtd."','".$pc11_quant."','".$pc22_orcamitem."');</script>";
	      }
	    }
	    if($clpcorcamval->numrows>0){
//         echo "<input name='gera' type='submit' id='gera' value='Gerar relatório' onclick='js_gerarel();'>";
	    }
	  }else{
	    echo "
            <tr>
	      <td nowrap align='center'><b> Não existem itens para este orçamento. </b></td>
	    </tr>";
	  }
	}
      ?>
      </table>
      <table border=1 align='right' >
      <tr>
      <td  class="bordas" align='right'>
      <b>Total:</b>
      <?
      db_input("somavalor",10,"",true,'text',3,"");
      ?>
      </td>
      </tr>
      </table>

<?
           for($i = 0; $i < $numrows_itens; $i++){
                 db_fieldsmemory($result_itens,$i);
                 echo "<div id='div_text_".$pc22_orcamitem."' style='visibility:hidden ; top:0px; left:0px; background-color:#6699CC ; border:2px outset #ccc
                           <table>
                             <tr>
                               <td align='left'>
                                   <font color='black' face='arial' size='2'><strong>".$RLpc11_resum."</strong>:</font><br>
                                   <font color='black' face='arial' size='1'>".$pc11_resum."</font>
                               </td>
                             </tr>
                           </table>
                      </div>";
            }


      ?>




    </td>
  </tr>
</table>
</form>
<?
if(isset($camposelected) && $camposelected != ""){
  echo "
    <script>
      document.form1.$camposelected.select();
      document.form1.$camposelected.focus();
    </script>
  ";
}
?>
<script>
function js_mostra_text(liga,nomediv,evt,el){
  evt = (evt)?evt:(window.event)?window.event:''; 
//  alert(window.availWidth);
  if(liga==true){

	    document.getElementById(nomediv).style.position = 'absolute';

	    document.getElementById(nomediv).style.top  = ( getPageOffsetTop(el) - 30 )+'px';
	    document.getElementById(nomediv).style.left = ( getPageOffsetLeft(el) + 50 )+'px';

	    document.getElementById(nomediv).style.visibility = 'visible';
  }else {
	    document.getElementById(nomediv).style.visibility = 'hidden';
  }
}  
<?
   if (isset($ok)&&$ok==true){
?>
js_somavalor();
<?
   }
?>
function js_importar(TouF){
  <?
  for($i=0;$i<count($arr_vlnomesitens);$i++){
    echo "if(TouF==true){";
    echo "  document.form1.".$arr_vlnomesitens[$i].".value = '".trim(db_formatar($arr_valoresitens[$i],"p"))."';";
    echo "  document.form1.".$arr_vtnomesitens[$i].".value = '".trim(db_formatar(($arr_valoresitens[$i]*$arr_quantitens[$i]),"p"))."';";
    echo "}else{";
    echo "  document.form1.".$arr_vlnomesitens[$i].".value = '0.00';";
    echo "  document.form1.".$arr_vtnomesitens[$i].".value = '0.00';";
    echo "}";
  }
  ?>
}
function js_abrejan(){
  var tam       = document.form1.elements.length;
  var orcamitem = new String("");
  var separador = "";

  for(i = 0; i < tam; i++) {
        if (document.form1.elements[i].type == "checkbox"){
             if (document.form1.elements[i].checked == true){
                  valor      = new String(document.form1.elements[i].value);
                  vetor      = valor.split("_");
                  orcamitem += separador+vetor[1];
                  separador  = ", ";
             }
        }
  }

  orcamforn = new String("<?=@$pc21_orcamforne?>");

  if (orcamitem.length > 0 && orcamforn.length > 0){
       js_OpenJanelaIframe('top.corpo','db_iframe_descla','com1_pcorcamdescla001.php?orcamitem='+orcamitem+'&pc32_orcamforne='+orcamforn,'Motivo da Desclassificacao',true);
  } else {
       if (orcamitem.length == 0){
            alert("Selecione um item");
       } else {
            if (orcamforn.length == 0){
                 alert("Inclua orçamento de fornecedor para poder desclassificar item(ns)");
            }
       }
  }
}
function js_pcorcamdescla(){
  var tam = document.form1.elements.length;

  for(i = 0; i < tam; i++) {
        if (document.form1.elements[i].type == "checkbox"){
             if (document.form1.elements[i].disabled == false){
                  if (document.form1.elements[i].checked == true){
                       document.form1.elements[i].checked = false;
                  } else {
                       document.form1.elements[i].checked = true;
                  }
             }
        }
  }      
}
function js_desclassifica(lote){
  var tam   = document.form1.elements.length;
  var campo = lote;

  for(i = 0; i < tam; i++) {
       if (document.form1.elements[i].type == "checkbox"){
            if (document.form1.elements[i].disabled == false){
                 var str = new String(document.form1.elements[i].value);
                 var vet = str.split("_");

                 if (campo == vet[2]){
                      document.form1.elements[i].checked = true;
                 }
            }
       }
  }      
}
function js_cancdescla(orcamento,licitacao){
  js_OpenJanelaIframe('top.corpo','db_iframe_cancdescla','lic1_pcorcamdesclacanc001.php?pc20_codorc='+orcamento+'&l20_codigo='+licitacao,'Cancelamento da desclassificacao',true);
}
</script>
</body>
</html>