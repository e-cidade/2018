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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_verificavalor(){
  if ( document.form1.valorapagar.value == "" ){
    if (document.form1.valoravaliado.value == "" ){
       alert("Você deverá Digitar o valor da Avaliação.");
	   document.form1.valoravaliacao.focus();
    }else if(document.form1.aliquota.value == ""){
       alert("Você deverá Digitar a Alíquota.");
	   document.form1.aliquota.focus();
    }else{
      document.form1.submit();
    }
  }else{
    if (document.form1.data_dia.value == "" || document.form1.data_mes.value == "" || document.form1.data_ano.value == ""  ){
       alert("Você deverá Digitar a data de Vencimento.");
	   document.form1.data_dia.focus();
	}else{
	   document.form1.submit();
	}
  }
}

function js_calculaava() {
  var terr = new Number(document.form1.valoravterr.value);
  if(isNaN(terr)){
    alert('Valor terreno não é Válido.');
    document.form1.valoravterr.value = "";
    document.form1.valoravterr.focus();

  }
  var pred = new Number(document.form1.valoravconst.value) + terr;
  if(isNaN(pred)){
    alert('Valor prédio não é Válido.');
    document.form1.valoravconst.value = "";
    document.form1.valoravconst.focus();

  }
  document.form1.valoravaliado.value = pred.toFixed(2) ;
  js_calculaaliq();
 
}
function js_calculaaliq() {
  var aliq = new Number(document.form1.aliquota.value);
  if(isNaN(aliq)){
    alert('Valor da alíquota não é Válida.');
    document.form1.aliquota.value = "";
    document.form1.aliquota.focus();

  }

  var vlrpag = new Number(document.form1.valoravaliado.value) * (aliq/100);
 
  document.form1.valorapagar.value = vlrpag.toFixed(2) ;
 
}
</script>
<style type="text/css">
<!--
td {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
input {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	height: 17px;
	border: 1px solid #999999;
}
-->
</style>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
<br>
<?	
	
	if(isset($HTTP_POST_VARS["excluir"])) {
  pg_exec("BEGIN");
  pg_exec("delete from db_caritbilan where id_itbi = ".$HTTP_POST_VARS["itbi"]);
  $result = pg_exec("delete from db_itbi where id_itbi = ".$HTTP_POST_VARS["itbi"]);
  if(pg_cmdtuples($result) > 0) {
    pg_exec("COMMIT");
    echo "<script>
	        alert('itbi excluido.');
			location.href='liberaitbi.php';
	      </script>";
    exit;
  } else {
    pg_exec("ROLLBACK");
    echo "<script>
	        alert('Erro excluindo itbi.');
			location.href='liberaitbi.php';
	      </script>";
    exit;
  }
}
  if(isset($HTTP_POST_VARS["itbi"])) {		
     $itbi = $HTTP_POST_VARS["itbi"];
     $valoravaliacao = $HTTP_POST_VARS["valoravaliado"];
     $aliquota = $HTTP_POST_VARS["aliquota"];
     $valorapagar = $HTTP_POST_VARS["valorapagar"];
	 $obsliber = $HTTP_POST_VARS["obsliber"];
     $data = $HTTP_POST_VARS["data_ano"]."-".$HTTP_POST_VARS["data_mes"]."-".$HTTP_POST_VARS["data_dia"];
     pg_exec("BEGIN");
     $result = pg_exec("UPDATE db_itbi SET dataliber = CURRENT_DATE,
                                           valoravterr = $valoravterr,
                                           valoravconst = $valoravconst,
                                           valoravaliacao = $valoravaliacao,
		  								 valorpagamento = $valorapagar,
			  							 aliquota = $aliquota,
				  						 datavencimento = '$data' ,
					  					 liberado = 1,
										 obsliber = '$obsliber' 
				         WHERE id_itbi = $itbi");
     if(pg_cmdtuples($result) <= 0) {
	    pg_exec("ROLLBACK");
	    echo "Erro atualizando tabela db_itbi. <a href=\"\" onclick=\"history.back();return false\">Voltar</a><br>\n";
	    exit;
     } else {
	    pg_exec("COMMIT");
		
		echo "<script>
               window.open('reciboitbi.php?itbi=".$itbi."&itbinumpre=',\"\",\"toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height=\"+(screen.height-100)+\",width=\"+(screen.width-100));
              </script>";
		
	    db_redireciona();
	    exit;
     } 
  } else if(isset($retorno)) {
	  $result = pg_exec("SELECT *,to_char(datasolicitacao,'DD-MM-YYYY') as datasolicit FROM db_itbi WHERE id_itbi = $retorno");
	  if(pg_numrows($result) > 0) {
	    db_fieldsmemory($result,0);
	   /* $sql = "select z01_cgccpf,z01_nome,z01_ender,z01_munic,z01_uf,z01_cep,
	                       z01_bairro,z01_email,v11_bql
						 from cgm
						 inner join ctmbase
						 on v11_numcgm = z01_numcgm
						 where trim(v11_matric) = trim('$matricula')";
		*/
		$sql = "select * from proprietario where j01_matric = $matricula";
	    $dadosp = pg_exec($sql);
	    if(pg_numrows($dadosp) > 0)
	      db_fieldsmemory($dadosp,0);
		 // printfieldsmemory($dadosp,0);
        //$predterr = pg_exec("select v12_matric from edifica where trim(v12_matric) = '$matricula' limit 1");	  
	    //$predterr = (pg_numrows($predterr) > 0)?"Predial":"Territorial";
		$predterr = $j01_tipoimp;
	  } else {
	    db_erro("Erro no select da tabela db_itbi.");
	  }

?>
<center>
  <form name="form1" method="post">
    <table width="90%" border="1" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="25%"><strong>N&uacute;mero da Guia</strong></td>
        <td width="35%"><?=@$id_itbi?></td>
        <td width="20%"><strong>Data Solicita&ccedil;&atilde;o</strong></td>
        <td width="20%"><?=@$datasolicit?></td>
      </tr>
    </table>
    <table width="90%" border="1" cellpadding="0" cellspacing="0">
      <tr bgcolor="#E0E0E0"> 
        <td colspan="5"><div align="center"><font color="#FF0000"><em><strong>Dados do 
            Im&oacute;vel</strong></em></font></div></td>
      </tr>
      <tr> 
        <td width="12%" height="24"><strong>Matr&iacute;cula:</strong></td>
        <td width="13%"><?=@$matricula?></td>
        <td><div align="center"><?=@$predterr?></div></td>
        <td width="23%"><strong>Setor/Quadra/Lote:</strong></td>
        <td width="20%"><?=$j40_refant?></td>		
      </tr>
      <tr>
          <td nowrap><strong>Nome: </strong> </td>
          <td nowrap colspan="4"> &nbsp; 
             <?=@$z01_nome?>
          </td>
      </tr>

    </table>
    <!--
    <table width="90%" border="1" cellpadding="0" cellspacing="0">
      <tr bgcolor="#E0E0E0"> 
        <td colspan="2"><div align="center"><font color="#FF0000"><em><strong>Dados do 
            Propriet&aacute;rio</strong></em></font></div></td>
      </tr>
      <tr> 
        <td width="50%" height="24"><table border="0" cellpadding="0" cellspacing="0">
            <tr> 
              <td nowrap><strong>Nome: </strong> </td>
              <td nowrap> &nbsp; 
                <?=@$z01_nome?>
              </td>
            </tr>
          </table></td>
        <td width="50%"><table border="0" cellpadding="0" cellspacing="0">
          </table></td>
      </tr>
    </table>
    -->
    <table width="90%" border="1" cellpadding="0" cellspacing="0">
      <tr bgcolor="#E0E0E0"> 
        <td colspan="2"><div align="center"><font color="#FF0000" >
		<em><strong><a href="" title="Clique aqui para ver as observações do comprador" class="obs" onClick="jan = window.open('pre4_liberaitbi002.php?itbi=<?=trim($id_itbi)?>','','width=400,height=400');jan.moveTo(200,100);return false">Dados do Comprador</a></strong></em></font>&nbsp;&nbsp; 
</div></td>
      </tr>
      <tr> 
        <td width="50%">
		  <table border="0" cellpadding="0" cellspacing="0">
		    <tr>
			  <td nowrap><strong>Nome: </strong> </td>
              <td nowrap>&nbsp; 
                <?=@$nomecomprador?>
              </td>
			</tr>
            <tr>
			  <Td nowrap><strong>CGCCPF:</strong> </td>
              <td nowrap>&nbsp; 
                <?=@$cgccpfcomprador?>
              </td>
			</tr>
			<tr>
			  <td nowrap><strong> Email:</strong> </td>
			  <td nowrap>&nbsp; 
                <?=@$email?>
              </td>
			</tr>
		  </table>
        </td>
        <td width="50%"><table border="0" cellpadding="0" cellspacing="0">
            <tr> 
              <td nowrap><strong>Endere&ccedil;o:</strong> </td>
              <td nowrap> &nbsp; 
                <?=@$enderecocomprador?>
              </td>
            </tr>
            <tr> 
              <Td nowrap><strong>Município:</strong></td>
              <td nowrap>&nbsp; 
                <?=@$municipiocomprador?>
                - 
                <?=@$ufcomprador?>
              </td>
            </tr>
            <tr> 
              <td nowrap><strong>Bairro:</strong>  </td>
              <td nowrap>&nbsp; 
                <?=@$bairrocomprador?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			  <strong>CEP:</strong>
                <?=@$cepcomprador?>
              </td>
            </tr>
          </table>
          
        </td>
      </tr>
    </table>
    <table width="90%" border="1" cellspacing="0" cellpadding="0">
      <tr align="center" bgcolor="#E0E0E0"> 
        <td><font color="#FF0000"><strong><em>Observa&ccedil;&otilde;es</em></strong></font></td>
      </tr>
      <tr> 
        <td align="center"><textarea name="obsliber" cols="80" rows="2" id="obsliber"></textarea></td>
      </tr>
    </table>
    <table width="90%" border="1" cellpadding="0" cellspacing="0">
      <tr bgcolor="#E0E0E0"> 
        <td colspan="6"><div align="center"><font color="#FF0000"><em><strong>Dados da 
            Transa&ccedil;&atilde;o</strong></em></font></div></td>
      </tr>
      <tr> 
        <td width="17%"><strong>Tipo:</strong></td>
        <td width="20%"><?=@$tipotransacao?></td>
        <td width="13%"><strong>Area Real:</strong></td>
        <td width="12%"><?=@$areareal?></td>
        <td width="21%"><strong>Area Transmitida:</strong></td>
        <td width="17%"><?=@$areaterreno?></td>
      </tr>
      <tr> 
        <td><strong>Valor:</strong></td>
        <td>
          <?=@number_format($valortransacao,2,".",",")?>
        </td>
        <td><strong>Situação:</strong></td>
        <td><?=@$situacao?></td>
        <td><strong>Area Constru&iacute;da Trans.:</strong></td>
        <td><?=@$areaedificada?></td>
      </tr>
    </table>
    <table width="90%" border="1" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="16%"><strong>Medidas:</strong></td>
        <td width="23%"><strong>Frente:</strong></td>
        <td width="24%">
          <?=@$mfrente?>
        </td>
        <td width="20%"><strong>Lado Direito:</strong></td>
        <td width="17%">
          <?=@$mladodireito?>
        </td>
      </tr>
      <tr> 
        <td>&nbsp;</td>
        <td><strong>Fundos:</strong></td>
        <td>
          <?=@$mfundos?>
        </td>
        <td><strong>Lado Esquerdo:</strong></td>
        <td>
          <?=@$mladoesquerdo?>
        </td>
      </tr>
    </table>
    <table width="90%" border="1" cellpadding="0" cellspacing="0">
	<?
    $CAR = pg_exec("select c.descricao,i.area,i.descrcar,i.anoconstr
                    from db_caritbi c,db_caritbilan i
                    where c.codcaritbi = i.codcaritbi
                    and i.area <> 0
                    and i.id_itbi = $id_itbi");
    for($i = 0;$i < pg_numrows($CAR);$i++) {
      ?>  
      <tr> 
        <td width="16%"><? echo $i==0?"<strong>Construções:</strong>":"" ?>&nbsp;</td>
        <td width="23%"><?=@pg_result($CAR,$i,"descrcar")?>&nbsp;</td>
        <td width="24%"><?=@pg_result($CAR,$i,"descricao")?>&nbsp;</td>
        <td width="24%"><?=@pg_result($CAR,$i,"area")?>&nbsp;</td>
        <td width="20%"><?=@pg_result($CAR,$i,"anoconstr")?>&nbsp;</td>
      </tr>
	  <?
	  }
	?>
    </table>
    <table width="90%" border="1" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="27%"><strong>Valor Avalia&ccedil;&atilde;o Terreno:</strong></td>
        <td width="31%"><input name="valoravterr" type="text" class="campos" id="valoravterr" onchange="js_calculaava()"></td>
        <td><strong>Valor Avalia&ccedil;&atilde;o Prédio:</strong></td>
        <td><input name="valoravconst" type="text" class="campos" id="valoravconst" onchange="js_calculaava()"></td>
      </tr>
      <tr> 
        <td width="27%"><strong>Valor Avalia&ccedil;&atilde;o:</strong></td>
        <td width="31%"><input name="valoravaliado" readonly type="text" class="campos" id="valoravaliado" ></td>
        <td><strong>Al&iacute;quota:</strong></td>
        <td><input name="aliquota" type="text" class="campos" id="aliquota2" onchange="js_calculaaliq()"></td>
      </tr>
      <tr> 
        <td><strong>Valor &agrave; Pagar:</strong></td>
        <td><input name="valorapagar" type="text" class="campos" id="valorapagar" readonly="true"></td>
        <td><strong>Vencimento:</strong></td>
        <td nowrap>
		  <!--input name="dia" type="text" class="campos" id="dia" onkeyUp="js_digitadata(this.name)" size="2" maxlength="2">
          /
          <input name="mes" type="text" class="campos" id="mes" onkeyUp="js_digitadata(this.name)" size="2" maxlength="2">
          /
          <input name="ano" type="text" class="campos" id="ano" size="4" maxlength="4"-->
		  <?
		  include("dbforms/db_funcoes.php");
                  $dia = date("d");
                  $mes = db_formatar(date("m")+1,'s','0',2);
                  $ano = date("Y");
                  if($mes>12){
                    $mes = 1;
                    $ano += 1;
                  }
		  db_data("data",$dia,$mes,$ano);
		  ?>
		</td>
      </tr>
    </table>
    <input type="hidden" name="itbi" value="<?=$id_itbi?>">
    <input name="confirma" type="button" id="confirma"  value="Confirma" onclick="js_verificavalor()">
    &nbsp; 
    <input name="limpa" type="reset" id="limpa2" value="Limpa">
    &nbsp; 
    <input name="excluir" type="submit" value="Excluir" onclick="return confirm('Quer realmente excluir esta solicitação?')">
  </form>
</center>
<?
} else {
/*
  if(!isset($offset)) {
  	$result = pg_exec("SELECT id_itbi as Numero, nomecomprador as Comprador FROM db_itbi WHERE datavencimento is null order by id_itbi");
  	if(pg_numrows($result) <= 0) {
  	  echo "<br><Br><Br><Br><br><br><br>Nenhuma Solicitação de ITBI pendente.";
  	  db_menu($id_usuario);
  	  exit;
  	}
  }
  */
    $query = "SELECT id_itbi as Numero, matricula as Matricula , nomecomprador as Comprador FROM db_itbi WHERE datavencimento is null order by id_itbi";

//      db_browse($query,'liberaitbi.php?itbi=',20,$offset,"");
echo "<center>\n";
  db_lovrot($query,100,"()","","js_libera|0");
  echo "</center>\n";
  echo "<script>\n";
  echo "function js_libera(chave){\n";
  echo "  location.href='pre4_liberaitbi001.php?retorno='+chave\n";
  echo "}\n";
  echo "</script>\n";
}
?>
	</td>
  </tr>
</table>
<?	
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>