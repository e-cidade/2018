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
include("libs/db_sql.php");

if(isset($HTTP_POST_VARS['id_itbi'])) {
  db_postmemory($HTTP_POST_VARS);
  if(empty($id_itbi)) {
    $result = pg_exec("select max(id_itbi) from db_itbi");
    $id_itbi = (integer)pg_result($result,0,0) + 1;
    pg_exec("BEGIN");
    //Soma das caracteristicas e grava
    $tam_vetor = sizeof($HTTP_POST_VARS);
    $areaedificada = 0;
    reset($HTTP_POST_VARS);
    for($i = 0;$i < $tam_vetor;$i++) {
      if(db_indexOf(key($HTTP_POST_VARS),"CARAC") > 0) {
        $str = "insert into db_caritbilan values($id_itbi,".db_parse_int(key($HTTP_POST_VARS)).",".$HTTP_POST_VARS[key($HTTP_POST_VARS)].")";
        pg_exec($str);
        $areaedificada += $HTTP_POST_VARS[key($HTTP_POST_VARS)];	
      }
      next($HTTP_POST_VARS);
    }  
    $result = pg_exec("insert into db_itbi(matricula,
                                       areaterreno,
                                       areaedificada,
				   nomecomprador,
				   cgccpfcomprador,
				   enderecocomprador,
			  	   municipiocomprador,
				   bairrocomprador,
				   cepcomprador,
				   ufcomprador,
				   tipotransacao,
				   valortransacao,
				   caracteristicas,
				   mfrente,
				   mladodireito,
				   mfundos,
				   mladoesquerdo,
				   email,
				   obs,
				   id_itbi,
				   datasolicitacao) 
			   values ('$cod_matricula',
			           $areaterreno,
                                    $areaedificada,
				   '$nomecomprador',
				   '$cgccpfcomprador',
				   '$enderecocomprador',
				   '$municipiocomprador',
				   '$bairrocomprador',
				   '$cepcomprador',
				   '$ufcomprador',
				   '$tipotransacao',
				   $valortransacao,
				   'caracteristicas',
				   $mfrente,
				   $mladodireito,
				   $mfundos,
				   $mladoesquerdo,
				   '$email',
				   '$obs',
				   $id_itbi,
				   '".date("Y-m-d")."')
				   ") or die('Erro no Sql');
    pg_exec("COMMIT");
	echo("Solicitação gerada com o número $id_itbi");
	db_redireciona("digitamatricula.php");
	exit;
  }else{
    pg_exec("BEGIN");
    //Soma das caracteristicas e grava
    $tam_vetor = sizeof($HTTP_POST_VARS);
    $areaedificada = 0;
    reset($HTTP_POST_VARS);
    for($i = 0;$i < $tam_vetor;$i++) {
      if(db_indexOf(key($HTTP_POST_VARS),"CARAC") > 0) {
        $str = "update db_caritbilan  set area = ".$HTTP_POST_VARS[key($HTTP_POST_VARS)]."
			   where codigo = ".db_parse_int(key($HTTP_POST_VARS));
        pg_exec($str);
        $areaedificada += $HTTP_POST_VARS[key($HTTP_POST_VARS)];	
      }
      next($HTTP_POST_VARS);
    }  
    $result = pg_exec("update db_itbi set matricula ='$cod_matricula' ,
                                       areaterreno=$areaterreno,
                                       areaedificada=$areaedificada,
									   nomecomprador='$nomecomprador',
									   cgccpfcomprador='$cgccpfcomprador',
									   enderecocomprador='$enderecocomprador',
									   municipiocomprador='$municipiocomprador',
									   bairrocomprador='$bairrocomprador',
									   cepcomprador='$cepcomprador',
									   ufcomprador='$ufcomprador',
									   tipotransacao='$tipotransacao',
									   valortransacao='$valortransacao',
									   caracteristicas='caracteristicas',
									   mfrente=$mfrente,
									   mladodireito=$mladodireito,
									   mfundos=$mfundos,
									   mladoesquerdo=$mladoesquerdo,
									   email='$email',
									   obs='$obs'
							   where id_itbi = $id_itbi") or die('Erro no Sql');
    if(pg_cmdtuples($result) == 0) {
      pg_exec("rollback");
	  echo('Erro ao Gravar Solicitação.');
	  db_redireciona("digitamatricula.php");
	  exit;
    } else {
	  pg_exec("commit");
  	  echo('Solicitação alterada com sucesso');
	  db_redireciona("digitamatricula.php");
	  exit;
	}
  }
}
$matricula = 200;
parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
$cod_matricula = 0 + $matricula;
if ( !is_int($cod_matricula) or $cod_matricula == "" ){
  // db_msgbox("Código da Matrícula Inválido.");
   db_redireciona("digitamatriculaitbi.php");
}

$result = pg_exec("select * from db_itbi where matricula = $cod_matricula and libpref = 't'");
if (pg_numrows($result) > 0){
  echo("Socilitação de Guia de ITBI está em processo de avaliação. Volte mais tarde.");
  // db_logs("$cod_matricula","",0,"Socilitação de Guia de ITBI está em processo de avaliação. Volte mais tarde. Numero: $cod_matricula");
  //db_redireciona("opcoesitbi.php?".base64_encode("matricula=".$cod_matricula));
}
$result = pg_exec("select * from db_itbi where matricula = $cod_matricula and liberado = 1 and ( datavencimento is null or datavencimento >= CURRENT_DATE)");
if (pg_numrows($result) != 0){
   //msgbox("Verifique Liberação de Guia.");
   //db_logs("$cod_matricula","",0,"Verifique Liberacao da Guia. Numero: $cod_matricula");
   db_redireciona("opcoesitbi.php?".base64_encode("matricula=".$cod_matricula));
}
$result = pg_exec("select * from db_itbi where matricula = $cod_matricula and liberado is null");
if (pg_numrows($result) != 0){
  //msgbox("Socilitação Recentemente Encaminhada. Proceda as Alterações.");
  //db_logs("$cod_matricula","",0,"Socilitação Recentemente Encaminhada. Proceda as Alterações. Numero: $cod_matricula");
  db_fieldsmemory($result,0);
}
$result = pg_exec("select p.*,pm.z01_nome as promitente, m.z01_nome as imobiliaria
                   from proprietario p
                   left outer join promitente o 
                     on o.j41_matric = p.j01_matric
                   left outer join cgm pm 
                     on pm.z01_numcgm = o.j41_numcgm
		   left outer join imobil i 
                     on i.j44_matric = p.j01_matric
                   left outer join cgm m
                     on m.z01_numcgm = i.j44_numcgm
                   where j01_matric = $cod_matricula");
if (pg_numrows($result) == 0){
   //msgbox("Matrícula não Cadastrada.");
   //db_logs("$cod_matricula","",0,"Matrícula não Cadastrada. Numero: $cod_matricula");
   db_redireciona("index.php");
   exit;
}
db_fieldsmemory($result,0);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - Prefeitura On - Line</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" src="scripts/db_script.js"></script>
<script>
function jsss_verificadados() {
  for(contador = 0; contador < document.form1.elements.length; contador++) {
    if(document.form1.elements[contador].type != "hidden" && 
       document.form1.elements[contador].name != "tipotransacao" && 
       document.form1.elements[contador].name != "caracteristicas" && 
       document.form1.elements[contador].name != "areaedificada" && 
       document.form1.elements[contador].name != "obs"  )
    {
      if (document.form1.elements[contador].value == "" ){
        alert("Campo Inválido.");
	document.form1.elements[contador].focus(); 
	return;
      }
    }
  }
  if ( document.form1.areaedificada.value == ""  ){
    document.form1.areaedificada.value = '0';
  }
  if (isNaN(document.form1.areaterreno.value) ){
     alert("Area Territorial da Transação Inválida");
	 document.form1.areaterreno.focus(); 
	 return;
  }
  if ( isNaN(document.form1.areaedificada.value) ){
     alert("Area Predial da Transação Inválida");
	 document.form1.areaedificada.focus(); 
	 return;
  }
  document.form1.submit();
}
function js_soma() {
  var F = document.form1;
  F.areaedificada.value = 0;
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == "text") {
      var str = new String(F.elements[i].name);
      if(str.indexOf("CARAC") != -1)
	    F.areaedificada.value = Number(F.areaedificada.value) + Number(F.elements[i].value);
	}
  }
}
</script>
<script>
function js_restaurafundo(obj) {
  document.getElementById(obj.id).style.backgroundColor = '#00436E';
}
function js_trocafundo(obj){
  document.getElementById(obj.id).style.backgroundColor = '#0065A8';
}
function js_link(arq) {
  location.href = arq;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<center>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
	  <form name="form1" method="post" action="">
	         <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" >
                  <tr> 
                    <td  valign="top" > 
		<table width="100%" border="0" cellspacing="0" cellpadding="0" >
                        <tr> 
                        <td ><strong>Matr&iacute;cula:</strong></td>
                          <td align="left" nowrap> &nbsp; 
                            <?=trim($j01_matric)?>
                            </td>
                          
                        <td width="19%" nowrap > <strong>Refer&ecirc;ncia 
                          Anterior:</strong> </td>
                          <td  width="40%" nowrap> &nbsp; 
                            <?=$j40_refant?>
                            </td>
                        </tr>
                        <tr> 
                          <td  colspan="4"> 
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr> 
                                
                              <td  width="12%"><strong>Setor:</strong></td>
                                <td  width="24%" align="left" nowrap> &nbsp; 
                                  <?=$j34_bairro?>
                                  </td>
                                
                              <td  width="16%"><strong>Quadra:</strong></td>
                                <td  width="14%"> &nbsp; 
                                  <?=$j34_quadra?>
                                  </td>
                                
                              <td  width="14%"><strong>Lote:</strong></td>
                                <td width="20%" nowrap> &nbsp; 
                                  <?=$j34_lote?>
                                  </td>
                              </tr>
                            </table></td>
                        </tr>
                        <tr align="left"> 
                          <td  height="19" colspan="4"> 
			  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr> 
                                
                              <td  width="12%" ><strong>Logradouro:</strong></td>
                                <td align="left"> &nbsp; 
                                  <?=$codpri?>
                                  </td>
                                <td  nowrap> &nbsp; 
                                  <?=$nomepri?>
                                  </td>
                                <td  nowrap> &nbsp; 
                                  <?=$j39_numero?>
                                  / 
                                  <?=$j39_compl?>
                                  </td>
                              </tr>
                            </table></td>
                        </tr>
                        <tr align="center" valign="top"> 
                          <td  colspan="4" > 
                             
                            <?=@$id_itbi?>
                            <input type="hidden" name="id_itbi" size="10" readonly value="<?=@$id_itbi?>">
                             <table width="100%" cellspacing="0" cellpadding="0">
                              <tr valign="top"> 
                                <td >
				  <table width="100%" border="0" cellpadding="0" cellspacing="0" >
                                    <tr valign="top"> 
                                    <td  colspan="6" >
                                       <b>IDENTIFICA&Ccedil;&Atilde;O 
                                      DO COMPRADOR:</b></td>
                                    </tr>
                                    <tr> 
                                      
                                    <td valign="top"><strong>Nome: 
                                      </strong>
                                    </td>
                                    <td valign="top"> 
                                        <input name="nomecomprador" type="text" onBlur="js_maiuscula(this)" value="<?=@$nomecomprador?>" size="40" maxlength="40">
                                    </td>
                                      
                                    <td  ><strong>CNPJ 
                                      /CPF:</strong>
                                        </td>
                                     <td  > 
                                        <input name="cgccpfcomprador" type="text" onBlur="js_maiuscula(this)" value="<?=@$cgccpfcomprador?>" size="14" maxlength="14">
                                        </td>
                                     <td></td>
                                     <td></td> 
                                      </tr>
                                    <tr> 
                                      
                                    <td ><strong>Endere&ccedil;o: 
                                      </strong>
                                        </td>
                                     <td > 
                                        <input type="text" name="enderecocomprador" size="40" maxlength="40" value="<?=@$enderecocomprador?>" onBlur="js_maiuscula(this)">
                                        </td>
                                      
                                    <td ><strong>Bairro: 
                                      </strong>
                                        </td>
                                    <td > 
                                        <input type="text" name="bairrocomprador" size="20" maxlength="20" value="<?=@$bairrocomprador?>" onBlur="js_maiuscula(this)">
                                        </td>
                                      <td></td>
                                     <td></td> 
 
                                    </tr>
                                    <tr> 
                                       <td ><strong>Munic&iacute;pio:</strong></td>
                                       <td> 
                                        <input type="text" name="municipiocomprador" size="20" maxlength="20" value="<?=@$municipiocomprador?>" onBlur="js_maiuscula(this)">
                                       </td>
                                       <td ><strong>&nbsp;&nbsp;UF:</strong></td>
                                       <td> 
                                              <select name="ufcomprador">
                                                <option value="RS" <? echo @$ufcomprador=="RS"?"selected":"" ?>> 
                                                RS </option>
                                                <option value="AC" <? echo @$ufcomprador=="AC"?"selected":"" ?>> 
                                                AC </option>
                                                <option value="AL" <? echo @$ufcomprador=="AL"?"selected":"" ?>> 
                                                AL </option>
                                                <option value="AM" <? echo @$ufcomprador=="AM"?"selected":"" ?>> 
                                                AM </option>
                                                <option value="AP" <? echo @$ufcomprador=="AP"?"selected":"" ?>> 
                                                AP </option>
                                                <option value="BA" <? echo @$ufcomprador=="BA"?"selected":"" ?>> 
                                                BA </option>
                                                <option value="CE" <? echo @$ufcomprador=="CE"?"selected":"" ?>> 
                                                CE </option>
                                                <option value="DF" <? echo @$ufcomprador=="DF"?"selected":"" ?>> 
                                                DF </option>
                                                <option value="ES" <? echo @$ufcomprador=="ES"?"selected":"" ?>> 
                                                ES </option>
                                                <option value="GO" <? echo @$ufcomprador=="GO"?"selected":"" ?>> 
                                                GO </option>
                                                <option value="MA" <? echo @$ufcomprador=="MA"?"selected":"" ?>> 
                                                MA </option>
                                                <option value="MG" <? echo @$ufcomprador=="MG"?"selected":"" ?>> 
                                                MG </option>
                                                <option value="MS" <? echo @$ufcomprador=="MS"?"selected":"" ?>> 
                                                MS </option>
                                                <option value="MT" <? echo @$ufcomprador=="MT"?"selected":"" ?>> 
                                                MT </option>
                                                <option value="PA" <? echo @$ufcomprador=="PA"?"selected":"" ?>> 
                                                PA </option>
                                                <option value="PB" <? echo @$ufcomprador=="PB"?"selected":"" ?>> 
                                                PB </option>
                                                <option value="PE" <? echo @$ufcomprador=="PE"?"selected":"" ?>> 
                                                PE </option>
                                                <option value="PI" <? echo @$ufcomprador=="PI"?"selected":"" ?>> 
                                                PI </option>
                                                <option value="PR" <? echo @$ufcomprador=="PR"?"selected":"" ?>> 
                                                PR </option>
                                                <option value="RJ" <? echo @$ufcomprador=="RJ"?"selected":"" ?>> 
                                                RJ </option>
                                                <option value="RN" <? echo @$ufcomprador=="RN"?"selected":"" ?>> 
                                                RN </option>
                                                <option value="RO" <? echo @$ufcomprador=="RO"?"selected":"" ?>> 
                                                RO </option>
                                                <option value="RR" <? echo @$ufcomprador=="RR"?"selected":"" ?>> 
                                                RR </option>
                                                <option value="SC" <? echo @$ufcomprador=="SC"?"selected":"" ?>> 
                                                SC </option>
                                                <option value="SE" <? echo @$ufcomprador=="SE"?"selected":"" ?>> 
                                                SE </option>
                                                <option value="SP" echo @$ufcomprador=="SP"?"selected":"" ?>> 
                                                SP </option>
                                                <option value="TO" echo @$ufcomprador=="TO"?"selected":"" ?>> 
                                                TO </option>
                                              </select>
                                         </td>
                                     <td ><strong>CEP: 
                                      </strong>
                                        </td>
                                    <td > 
                                        <input type="text" name="cepcomprador" size="8" maxlength="8" value="<?=@$cepcomprador?>" onBlur="js_maiuscula(this)">
                                        </td>
                                      </tr>
                                  </table>
                              </td>
                              </tr>
                              <tr> 
                                <td  colspan="3" > 
				<table width="100%" border="0" cellpadding="0" cellspacing="0" >
                                    <tr> 
                                      
                                    <td  colspan="4"><strong>DADOS 
                                      DO IM&Oacute;VEL:</strong></td>
                                    </tr>
                                    <tr> 
                                      
                                    <td  ><strong>&Aacute;rea 
                                      do Terreno: </strong></td>
                                      <td > 
                                        <input type="text" name="areaterreno"  size="20" value="<?=@$areaterreno?>">
                                        </td>
                                      
                                    <td  ><strong>Frente:</strong></td>
                                      <td > 
				  <input type="text" name="mfrente" size="20" value="<?=@$mfrente?>">
				  </td>
			      </tr>
			      <tr> 
				
			      <td  nowrap><strong>Valor da Transa&ccedil;&atilde;o:</strong></td>
				<td> 
				  <input type="text" name="valortransacao" size="20" value="<?=@$valortransacao?>">
				  </td>
				
			      <td  nowrap><strong>Lado Direito:</strong></td>
				<td> 
				  <input type="text" name="mladodireito" size="20" value="<?=@$mladodireito?>">
				  </td>
			      </tr>
			      <tr> 
				
			      <td  nowrap><strong>Tipo de Transa&ccedil;&atilde;o:</strong></td>
				<td> 
				  <select name="tipotransacao">
				    <option <? echo @$tipotransacao=="Venda"?"selected":"" ?>>Venda</option>
				    <option <? echo @$tipotransacao=="Venda Parcial"?"selected":"" ?>>Venda 
				    Parcial</option>
				    <option <? echo @$tipotransacao=="Doacao"?"selected":"" ?>>Doacao</option>
				    <option <? echo @$tipotransacao=="Partilha de bens"?"selected":"" ?>>Partilha 
				    de bens</option>
				  </select>
				  </td>
				
			      <td ><strong>Fundos:</strong></td>
				<td > 
				  <input type="text" name="mfundos" size="20" value="<?=@$mfundos?>">
				  </td>
			      </tr>
			      <tr> 
				<td colspan="2"><strong>&Aacute;rea 
				  Construida:</strong></td>
				
			      <td  nowrap><strong>Lado Esquerdo:</strong></td>
				<td > 
				  <input type="text" name="mladoesquerdo" size="20" value="<?=@$mladoesquerdo?>">
				  </td>
			      </tr>
			      <!--select name="caracteristicas"-->
			      <?
						    if(@$id_itbi == "")
						      $result = pg_exec("select * from db_caritbi");
						    else
						      $result = pg_exec("select c.codigo,c.descricao,i.area 
									     from db_caritbi c,db_caritbilan i
											     where c.codigo = i.codigo
											     and i.id_itbi = $id_itbi");
						    for($i = 0;$i < pg_numrows($result);$i++) {
						      db_fieldsmemory($result,$i);
						      //echo "<option value=\"".pg_result($result,$i,"codigo")."\">".pg_result($result,$i,"descricao")."</option>\n";
			  echo "<tr><td >".$descricao.":</td><td > <input type=\"text\" onkeyup=\"js_soma()\" name=\"CARAC".$descricao.$codcaritbi."\" value=\"".(@$area==""?"0":@$area)."\"></td><td >&nbsp;</td><td>&nbsp;</td></tr>\n";
						    }	
						  ?>
			      <tr> 
				
			      <td height="27" nowrap ><strong>Total 
				de &aacute;rea construida:</strong></td>
				<td> 
				  <input type="text" name="areaedificada" size="20" value="<?=@$areaedificada?>" readonly>
				  </td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			      </tr>
			    </table></td>
			</tr>
		      </table>
		      <table width="100%" border="0">
			<tr> 
			  
			<td  width="33%" height="82" valign="top"><strong>E-mail 
			  para Contato:</strong><br>
			    <input type="text" name="email" size="20" maxlength="40" onblur="js_minuscula(this)" value="<?=@$email?>">
			    </td>
			  
			<td  width="18%" valign="top"><strong>Observa&ccedil;&otilde;es:</strong></td>
			  <td width="49%">  
			    <textarea name="obs" cols="30" rows="4"><?=@$obs?></textarea>
			    </td>
			</tr>
		      </table>
		      </td>
		  </tr>
		  <tr align="center" valign="top"> 
		    <td colspan="4">  
		      <input type="button" name="confirma" value="Confirma Solicita&ccedil;&atilde;o" onclick="jsss_verificadados()">
		      <input type="hidden" name="cod_matricula" value="<?=$cod_matricula?>">
		      </td>
		  </tr>
		</table></td>
	    </tr>
	  </table>
</form>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>