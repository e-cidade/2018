<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();
include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$result = db_query("SELECT distinct m_publico,m_arquivo,m_descricao
                       FROM db_menupref 
		       WHERE m_arquivo = 'digitadae.php'
		       ORDER BY m_descricao
		       ");
db_fieldsmemory($result,0);
if($m_publico != 't'){
  if(!session_is_registered("DB_acesso"))
    echo"<script>location.href='index.php?".base64_encode('erroscripts=3')."'</script>";
}
mens_help();
$dblink="index.php";
db_logs("","",0,"Digita Codigo do Contribuinte.");
db_mensagem("contribuinte_cab","contribuinte_rod");
postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
js_verificapagina("opcoesdae.php,sociosdae.php,enderecodae.php,valoresdae.php,enviadae.php");
function js_veri(){
  if(document.form1.valor.value.indexOf(",")!=-1){
    var  vals= new Number(document.form1.valor.value.replace(",","."));
    document.form1.valor.value = vals.toFixed(2);
  }else{
    var vals = new Number(document.form1.valor.value);
    document.form1.valor.value = vals.toFixed(2);
  }
  if(isNaN(vals)){
    alert("verifique o valor da receita!");
    document.form1.valor.focus();
    return false;
  } 
  var aliquota = new Number(document.form1.aliquota.value);
  vals = new Number((vals *(aliquota/100))); 
  document.form1.imposto.value=vals.toFixed(2);
}
function maiusculo(obj) {
  var maiusc = new String(obj.value);
  obj.value = maiusc.toUpperCase();
}
function js_data(dia,mes,ano){
  diaval = new Number(dia.value);
  mesval = new Number(mes.value);
  anoval = new Number(ano.value);
  if(isNaN(diaval)){
    alert('dia Inválido');
    dia.value = '';
    dia.focus();
    return false;
  }    
  if(isNaN(mesval)){
    alert('Data Inválida');
    mes.value = '';
    mes.focus();
    return false;
  }  
  if(isNaN(anoval)){
    alert('Data Inválida');
    ano.value = '';
    ano.focus();
    return false;
  }  
  data = new Date(anoval,(mesval-1),diaval);
  if((data.getMonth() + 1) != mesval || data.getFullYear() != anoval){
    alert('Data Inválida');
    dia.focus();
    dia.select();
    return false;
  }
return true;
}  
var contador = 0;
function js_vericampos(){
    var alerta="";
    mes=document.form1.mes.value;
    valor=document.form1.valor.value;
    aliquota=document.form1.aliquota.value;
    imposto=document.form1.imposto.value;
    datad=document.form1.dia.value;
    datam=document.form1.mes1.value;
    dataa=document.form1.ano.value;
    if(datad!="" || datam!="" || dataa!=""){
      if(datad.length == "" || isNaN(datad)){
        alerta +="Dia de Pagamento\n";
      }
      if(datam.length == "" || isNaN(datam)){
        alerta +="Mês de Pagamento\n";
      }
      if(dataa.length == "" || isNaN(dataa)){
        alerta +="Ano de Pagamento\n";
      }
      if(!js_data(document.form1.dia,document.form1.mes1,document.form1.ano)){
        return false; 
      }  
    }
    if(valor==""){
      alerta +="Valor da Receita\n";
    }
    if(mes=="mes"){
      alerta +="Mês\n";
    }
    if(aliquota==""){
      alerta +="Alíquota\n";
    }
    if(imposto==""){
      alerta +="Imposto\n";
    }
    if(alerta!=""){
      alert("Verifique os seguintes campos:\n"+alerta);
      return false;
    }else{
      return true;
    }  
return false;
}
</script>
<style type="text/css">
<?
db_estilosite();
?>
td{
      font-family: Arial;
      font-size:12px;
      }
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<?
mens_div();
?>
<center>
<form name="form1" method="post" action="valoresdae.php?<?=base64_encode('inscricaow='.$inscricaow.'&codigo='.$codigo)?>">
<input type="hidden" name="tamanho">
<table width="766" border="0" cellpadding="0" cellspacing="0" bgcolor="<?$w01_corbody?>">
<tr>
    <td align="left" valign="top">
      <table width="100%" height="313" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td align="left" valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr align="left">
                  <td>
                    <table width="89%" border="0" cellspacing="0" cellpadding="0">
                        <tr> 
                          <td colspan="5">
			    <table width="490" border="0">
                              <tr> 
                                <td align="center">
				  <b><small></small></b>
				</td>
                                <td align="center" >
				  <b><small>Mês</small></b>
				</td>
                                <td align="center" nowrap >
				  <b><small>Valor Rec.</small></b>
				</td>
                                <td align="center" >
				  <b><small>Aliquota</small></b>
				</td>
                                <td align="center" >
				  <b><small>Imposto</small></b>
				</td>
                                <td nowrap align="center" >
				  <b><small>Dt. pgto</small></b>
				</td>
                              </tr>
                              <tr> 
                                <td>
				  <input name="item" type="hidden" maxlength="3" size="2">
			        </td>
                                <td align="center" nowrap>
                                  <select class="digitacgccpf" name="mes" id="mes">
	                            <option value="mes">Mês</option>
                                  </select>
                                </td>
	                        <script>
                                for(j=1;j<13;j++){
	     	                  document.form1.mes.options[j] = new Option(db_mes(j),j);
			        }	 
		                </script>
                                <td align="center" nowrap >
				  <small>R$</small>
				  <input name="valor" type="text" onChange="return js_veri();"  size="10"> 
                                </td>
                                <td align="center" nowrap >
				  <select name="aliquota" id="select" onChange="return js_veri()">
                                    <option value="0">0%</option>
                                    <option value="1" <?=(isset($aliquota)&&$aliquota=="1"?"selected":"")?>>1%</option>
                                    <option value="2" <?=(isset($aliquota)&&$aliquota=="2"?"selected":"")?> selected>2%</option>
                                    <option value="3" <?=(isset($aliquota)&&$aliquota=="3"?"selected":"")?>>3%</option>
                                    <option value="4" <?=(isset($aliquota)&&$aliquota=="4"?"selected":"")?>>4%</option>
                                    <option  value="5" <?=(isset($aliquota)&&$aliquota=="5"?"selected":"")?>>5%</option>
                                    <option value="6" <?=(isset($aliquota)&&$aliquota=="6"?"selected":"")?>>6%</option>
                                    <option value="7" <?=(isset($aliquota)&&$aliquota=="7"?"selected":"")?>>7%</option>
                                    <option value="8" <?=(isset($aliquota)&&$aliquota=="8"?"selected":"")?>>8%</option>
                                    <option value="9" <?=(isset($aliquota)&&$aliquota=="9"?"selected":"")?>>9%</option>
                                    <option value="10" <?=(isset($aliquota)&&$aliquota=="10"?"selected":"")?>>10%</option>
                                  </select>
				</td>
				<td nowrap>
                                  <small>R$ 
                                    <input name="imposto" type="text" size="10" readonly>
                                  </small>
				</td>
				<td nowrap>
                                  <input name="dia" type="text" size="2" maxlength="2"> /
                                  <input name="mes1" type="text" size="2" maxlength="2"> /
                                  <input name="ano" type="text" size="4" maxlength="4">
				</td>
                              </tr>
                            </table>
			  </td>
                        </tr>
                        <tr> 
                          <td > 
 			    <input name="salvavalores" class="botao" type="submit"  value="Salvar" onClick="js_itens();return js_vericampos();"> 
                          </td>
                        </tr>
	        </table>
	      </td>
	    </tr>  
          </td>
        </tr>
	<tr>
	  <td>
            <table id="linhas" width="490" cellpadding="0" cellspacing="0" border="1" >
	      <script>
	      function js_itens(){
	        var numero = document.getElementById('linhas').rows.length;
	        document.form1.linhas.value = (numero-1);
	      }	
	      </script>
	      <input type="hidden" name="linhas">
	      <tr bgcolor="<?=$w01_corfundomenuativo?>" align="center">
	        <td width="20%" >
		  Mês
		</td>
	        <td width="20%">
		  Valor Rec.
		</td>
	        <td width="10%">
		  Alíquota
		</td>
	        <td width="20%">
		  Imposto
		</td>
	        <td width="30%">
		  Data pgto.
		</td>
	      </tr>
	      <?
              $data = @$ano.@$mes1.@$dia;
              if($data == ""){
                $data = "null";
              }else{
                $data = @$ano."-".@$mes1."-".@$dia;
              }
if(@$w07_dtpaga != ""){
                db_formatar($w07_dtpaga,'d');
              }
	      if(isset($primeira)){
		$result = db_query("select * from db_daevalores where w07_codigo = $codigo");
		if(pg_numrows($result) == 0){
                  db_redireciona("valoresdae.php?".base64_encode("inscricaow=".$inscricaow."&codigo=".$codigo));
		}else{  
		  for($i=0;$i<pg_numrows($result);$i++){
		    db_fieldsmemory($result,$i);
		    echo"<tr align=\"center\">
	                   <td width=\"20%\" >
		             ".db_mes($w07_mes)."
		           </td>
	                   <td width=\"20%\">
		             $w07_valor
		           </td>
	                   <td width=\"10%\">
		             $w07_aliquota
		           </td>
	                   <td width=\"20%\">
		             $w07_imposto
		           </td>
	                   <td width=\"30%\">
		             ".$w07_dtpaga."
		           </td>
			   <td>
			     <input class=\"botao\" name=\"alterar\" type=\"submit\" value=\"Alterar\" onClick=\"document.form1.qual.value='$w07_item'\">
			     <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"document.form1.qual.value='$w07_item'\">
	                   </td>
			 </tr>
			 <input type=\"hidden\" name=\"item$i\" value=\"$w07_item#$w07_mes#$w07_valor#$w07_aliquota#$w07_imposto#$w07_dtpaga\">
			 ";
		  }
		}
	      }	
	      if(isset($salvavalores)){
		db_query("delete from db_daevalores where w07_codigo = $codigo");
		if($linhas == 0){
		  $linhas = 0;
                    if($data == ""){
                      $data = "null";
                    }else{
                      if($data != "null")
                        $data = "'$data'";
                    }
		  db_query("insert into db_daevalores values($codigo,$linhas,'$mes',$valor,$aliquota,$imposto,$data)");
		}else{
                    if($data == ""){
                      $data = "null";
                    }else{
                      if($data != "null")
                        $data = "'$data'";
                    }
		  db_query("insert into db_daevalores values($codigo,$linhas,'$mes',$valor,$aliquota,$imposto,$data)");
		  for($x=0;$x<$linhas;$x++){
		    $input = "item".$x;
		    $matriz = split('#',$$input);
		    $item = $matriz[0];
		    $mes = $matriz[1];
		    $valor = $matriz[2];
		    $aliquota = $matriz[3];
		    $imposto = $matriz[4];
		    $data = $matriz[5];
                    if($data == ""){
                      $data = "null";
                    }else{
                      $data = "'$data'";
                    }
		    $result = db_query("insert into db_daevalores values($codigo,$item,'$mes',$valor,$aliquota,$imposto,$data)");
		  }
		}  
		$result = db_query("select * from db_daevalores where w07_codigo = $codigo");
		if(pg_numrows($result) == 0){
                  db_redireciona("valoresdae.php?".base64_encode("inscricaow=".$inscricaow."&codigo=".$codigo));
		}else{  
		  for($i=0;$i<pg_numrows($result);$i++){
		    db_fieldsmemory($result,$i);
		    echo"<tr align=\"center\">
	                   <td width=\"20%\" >
		             ".db_mes($w07_mes)."
		           </td>
	                   <td width=\"20%\">
		             $w07_valor
		           </td>
	                   <td width=\"10%\">
		             $w07_aliquota
		           </td>
	                   <td width=\"20%\">
		             $w07_imposto
		           </td>
	                   <td width=\"30%\">
		             ".$w07_dtpaga."
		           </td>
			   <td>
			     <input class=\"botao\" name=\"alterar\" type=\"submit\" value=\"Alterar\" onClick=\"document.form1.qual.value='$w07_item'\">
			     <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"document.form1.qual.value='$w07_item'\">
	                   </td>
			 </tr>
			 <input type=\"hidden\" name=\"item$i\" value=\"$w07_item#$w07_mes#$w07_valor#$w07_aliquota#$w07_imposto#$w07_dtpaga\">
			 ";
		  }
		}   	 
	      }elseif(isset($alterar)){
		$result = db_query("select * from db_daevalores where w07_codigo = $codigo and w07_item = $qual");
		db_fieldsmemory($result,0);
	        db_query("delete from db_daevalores where w07_item = $qual and w07_codigo = $codigo");
		echo "<script>document.form1.mes.value = $w07_mes</script>";
		echo "<script>document.form1.valor.value = $w07_valor</script>";
		echo "<script>document.form1.aliquota.value = $w07_aliquota</script>";
		echo "<script>document.form1.imposto.value = $w07_imposto</script>";
		if($w07_dtpaga != ""){
                  $ano = substr($w07_dtpaga,0,4);
		  $mes = substr($w07_dtpaga,5,2); 
		  $dia = substr($w07_dtpaga,8,2);
                }else{
                  $ano = "";
		  $mes = ""; 
		  $dia = "";
                }
		echo "<script>document.form1.dia.value = $dia</script>";
		echo "<script>document.form1.mes1.value = $mes</script>";
		echo "<script>document.form1.ano.value = $ano</script>";
		$result = db_query("select * from db_daevalores where w07_codigo = $codigo");
		if(pg_numrows($result) == 0 && $qual == ""){
                  db_redireciona("valoresdae.php?".base64_encode("inscricaow=".$inscricaow."&codigo=".$codigo));
		}else{  
		  db_query("delete from db_daevalores where w07_codigo = $codigo");
		  for($i=0;$i<pg_numrows($result);$i++){
		    db_fieldsmemory($result,$i);
		    echo"<tr align=\"center\">
	                   <td width=\"20%\" >
		             ".db_mes($w07_mes)."
		           </td>
	                   <td width=\"20%\">
		             $w07_valor
		           </td>
	                   <td width=\"10%\">
		             $w07_aliquota
		           </td>
	                   <td width=\"20%\">
		             $w07_imposto
		           </td>
	                   <td width=\"30%\">
		             ".$w07_dtpaga."
		           </td>
			   <td>
			     <input class=\"botao\" name=\"alterar\" type=\"submit\" value=\"Alterar\" onClick=\"document.form1.qual.value='$w07_item'\">
			     <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"document.form1.qual.value='$w07_item'\">
	                   </td>
			 </tr>
			 <input type=\"hidden\" name=\"item$i\" value=\"$i#$w07_mes#$w07_valor#$w07_aliquota#$w07_imposto#$w07_dtpaga\">
			 ";
		  }
		}   	 
	      }elseif(isset($excluir)){
	        //db_postmemory($HTTP_POST_VARS,2);
		db_query("delete from db_daevalores where w07_codigo = $codigo and w07_item = $qual");
		$result = db_query("select * from db_daevalores where w07_codigo = $codigo");
		if(pg_numrows($result) == 0){
                  db_redireciona("valoresdae.php?".base64_encode("inscricaow=".$inscricaow."&codigo=".$codigo));
		}else{  
		  for($i=0;$i<pg_numrows($result);$i++){
		    db_fieldsmemory($result,$i);
		    echo"<tr align=\"center\">
	                   <td width=\"20%\" >
		             ".db_mes($w07_mes)."
		           </td>
	                   <td width=\"20%\">
		             $w07_valor
		           </td>
	                   <td width=\"10%\">
		             $w07_aliquota
		           </td>
	                   <td width=\"20%\">
		             $w07_imposto
		           </td>
	                   <td width=\"30%\">
		             ".$w07_dtpaga."
		           </td>
			   <td>
			     <input class=\"botao\" name=\"alterar\" type=\"submit\" value=\"Alterar\" onClick=\"document.form1.qual.value='$w07_item'\">
			     <input class=\"botao\" name=\"excluir\" type=\"submit\" value=\"Excluir\" onClick=\"document.form1.qual.value='$w07_item'\">
	                   </td>
			 </tr>
			 <input type=\"hidden\" name=\"item$i\" value=\"$i#$w07_mes#$w07_valor#$w07_aliquota#$w07_imposto#$w07_dtpaga\">
			 ";
		  }
		}   	 
	      }	
	      ?>
	      <input type="hidden" name="qual" value="">
	    </table>
	  </td>
	</tr>
      </table>
    </td>
  </tr>
</table>
</form>
</center>
</form>
</body>
</html>