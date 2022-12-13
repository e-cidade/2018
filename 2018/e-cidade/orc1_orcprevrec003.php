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
include("libs/db_liborcamento.php");
include("classes/db_orcprevrec_classe.php");
include("classes/db_orcreceita_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clorcprevrec = new cl_orcprevrec;
$clorcreceita = new cl_orcreceita;
$clrotulo = new rotulocampo;
$clrotulo->label("o34_valor");
$semregistros = true;
if(isset($receita) && trim($receita) != ""){
  $semregistros = false;
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
<table width="100%" border="1" cellpadding="0" cellspacing="0">
  <form name='form1'>
  <?
  if($semregistros == true){
    ?>
    <tr>
      <td align='center'>
        <b>Informe a receita</b>
      </td>
    </tr>
    <?
	}else{
		$bimestre = ($bimestre * 2);

		$dia = 1;
    $dataini = db_getsession("DB_anousu")."-".($bimestre-1)."-".$dia;
    $datafim = db_getsession("DB_anousu")."-".($bimestre-1)."-".$dia;

    $result_saldo_rec = db_receitasaldo(11, 2, 3, true, "o70_codrec = ".$receita, db_getsession("DB_anousu"), $dataini, $datafim);
    if(pg_numrows($result_saldo_rec) > 0){
    	db_fieldsmemory($result_saldo_rec, 0);
	$saldo_a_arrecadar = $saldo_inicial_prevadic - $saldo_arrecadado_acumulado;
    	$saldo_a_arrecadar = round($saldo_a_arrecadar,2);
    	$saldo_frente = $saldo_a_arrecadar / (14 - $bimestre);
    	$saldo_ultimo = $saldo_a_arrecadar - (round($saldo_frente,2) * (13 - $bimestre));
    }

    $arr_valin = Array();

		$arr_meses = Array(
                        1=>"Jan",
                        2=>"Fev",
                        3=>"Mar",
                        4=>"Abr",
                        5=>"Mai",
                        6=>"Jun",
                        7=>"Jul",
                        8=>"Ago",
                        9=>"Set",
                       10=>"Out",
                       11=>"Nov",
                       12=>"Dez"
                      );

    ?>
    <tr>
			<td align='center'>
	      <b>Meses</b>
	    </td>
    <?
		for($i=0; $i<6; $i++){
			?>
			<td align="center">
			  <b>
			  <?=$arr_meses[($i+1)]?> (R$)
			  </b>
			</td>
			<?
		}
    ?>
    </tr>
    <tr>
		  <td align='center'>
        <b>Valores</b>
      </td>
    <?
		for($i=0; $i<6; $i++){
      $bloquear = 3;
      $campo    = 'o34_valor_'.$arr_meses[($i+1)];
      $$campo   = "0.00";
      if(($i+1) > ($bimestre) - 2){
      	$bloquear = 1;
      	$$campo   = trim(db_formatar($saldo_frente,"p"));
      	if(!isset($campofok)){
      		$campofok = $campo;
      	}
      }
      $result_valor = $clorcprevrec->sql_record($clorcprevrec->sql_query_file(null,"o34_valor","","o34_anousu=".db_getsession("DB_anousu")." and o34_codrec=".$receita." and o34_mes=".($i+1)));
		  $numrows_novos = $clorcprevrec->numrows;
		  if($numrows_novos > 0){
		  	db_fieldsmemory($result_valor, 0);
		  	$$campo = $o34_valor;
		  }
		  $arr_valin[$i] = $$campo;
			?>
			<td align="center">
			  <?
			  db_input("o34_valor",15,$Io34_valor,true,'text',$bloquear,"onchange='js_alteravalor(this.name);'",$campo);
			  ?>
			</td>
			<?
		}
    ?>
    </tr>
    <tr>
		  <td align='center' colspan='7'>
        &nbsp;
      </td>
    </tr>
    <tr>
			<td align='center'>
	      <b>Meses</b>
	    </td>
    <?
		for($i=6; $i<12; $i++){
			?>
			<td align="center">
			  <b>
			  <?=$arr_meses[($i+1)]?> (R$)
			  </b>
			</td>
			<?
		}
    ?>
    </tr>
    <tr>
		  <td align='center'>
        <b>Valores</b>
      </td>
    <?
		for($i=6; $i<12; $i++){
      $bloquear = 3;
      $campo    = 'o34_valor_'.$arr_meses[($i+1)];
      $$campo   = "0.00";
			$campoblr = "onchange='js_alteravalor(\"".$campo."\");'";
      if((($i+1) > ($bimestre) - 2) && ($i+1) < 12){
      	$bloquear = 1;
      	$$campo   = trim(db_formatar($saldo_frente,"p"));
      	if(!isset($campofok)){
      		$campofok = $campo;
      	}
      	if(($i+2) == 12){
      	  $campoblr.= " onblur='parent.js_setarfoco(2);' ";
      	}
      }else if(($i+1) == 12){
      	$$campo   = trim(db_formatar($saldo_ultimo,"p"));
      	if(!isset($campofok)){
      		$campofok = $campo;
      	}
      }
      $result_valor = $clorcprevrec->sql_record($clorcprevrec->sql_query_file(null,"o34_valor","","o34_anousu=".db_getsession("DB_anousu")." and o34_codrec=".$receita." and o34_mes=".($i+1)));
		  $numrows_novos = $clorcprevrec->numrows;
		  if($numrows_novos > 0){
		  	db_fieldsmemory($result_valor, 0);
		  	$$campo = $o34_valor;
		  }
		  $arr_valin[$i] = $$campo;
			?>
			<td align="center">
			  <?
			  db_input('o34_valor',15,$Io34_valor,true,'text',$bloquear,$campoblr,$campo);
			  ?>
			</td>
			<?
		}
		?>
    </tr>
    <tr>
		  <td align='center' colspan='7'>
        &nbsp;
      </td>
    </tr>
    <tr>
		  <td align='right' colspan='6'>
        <b>Saldo a arrecadar:</b>
      </td>
		  <td align='center' colspan='1'>
		    <?
		    $saldo_a_arrecadar = trim(db_formatar($saldo_a_arrecadar,"p"));;
		    db_input('o34_valor',15,$Io34_valor,true,'text',$bloquear,"",'saldo_a_arrecadar');
		    ?>
      </td>
    </tr>
		<?
	}
  ?>
  </form>
</table>
</body>
</html>
<script>
function js_valorinicial(campo){
	x = document.form1;
	arr = new Array();
	<?
	for($i=0; $i<count($arr_valin); $i++){
    echo "  arr[".$i."] = ".$arr_valin[$i].";\n";
	}
	?>
	valor = new Number(arr[campo]);
  x.elements[campo].value = valor.toFixed(2);
}
function js_alteravalor(campo){
  x = document.form1;
  y = eval("x."+campo);
  ok = false; // Se já encontrou o campo passado para a função
  ct = 0;     // Posição no formulário do campo passado
  vs = 0;     // Somatório dos campos (somente até o campo passado)
  vm = new Number(x.saldo_a_arrecadar.value);  // Valor máximo para ser os campos após o campo passado para a função
  vt = new Number(x.saldo_a_arrecadar.value);  // Valor total a arrecadar
  ft = 0;                                      // Quantidade de campos após o campo passado para a função
  for(i=0; i<(x.length-2); i++){

    valor = new Number(x.elements[i].value);
    x.elements[i].value = valor.toFixed(2);

    vs+= new Number(x.elements[i].value);

    if(x.elements[i].name == campo){
      ok = true;
      ct = i;
    }
  }

  if(ok == true){
    ft = (x.length-2);
    vv = new Number(vm);
    vm = new Number(vm-vs);
    vs+= new Number(vv-vs);
    if(vs > vt){
      alert("Somatório do valor de todos os meses deve ser inferior ao Saldo a arrecadar ("+vt+").");
      js_valorinicial(ct);
    }else{
      vm = vm.toFixed(2);
      x.elements[ft].value = vm;
    }
  }
}
<?
if($semregistros == false){
  echo "
        function js_setarfoco(){
          js_tabulacaoforms('form1','".$campofok."',true,1,'".$campofok."',true);
        }
        js_setarfoco();
       ";
}
?>
function js_tofixed(){
  for(i=0; i<document.form1.length; i++){
    valor = new Number(document.form1.elements[i].value);
    document.form1.elements[i].value = valor.toFixed(2);
  }
}
js_tofixed();
js_alteravalor("o34_valor_Jan");
</script>