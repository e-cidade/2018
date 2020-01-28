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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

//------------------------------------------------------
//   Arquivos que verificam se o boletim já foi liberado ou naum
  include("classes/db_boletim_classe.php");
  $clverficaboletim =  new cl_verificaboletim(new cl_boletim);
//------------------------------------------------------

$erro = "";
$pesquisar = false;
if(isset($pesquisa)){
   $np = substr($HTTP_POST_VARS["numpre"],0,8);
   $pa = substr($HTTP_POST_VARS["numpre"],8,3);
   if($np=="")
      $erro = "Numpre Inválido.";
   else
      if($pa=="" || $pa=="000")
         $erro = "Parcela Inválida.";
	  else{  
         $sql = "select k00_receit,k02_drecei,k00_dtoper,z01_nome,sum(k00_valor) as k00_valor
		        from arrepaga
				     left outer join cgm on k00_numcgm = z01_numcgm
					 ,tabrec 
					  inner join tabrecjm on tabrecjm.k02_codjm = tabrec.k02_codjm
                where k00_numpre = $np and k00_numpar = $pa
				      and k00_receit = k02_codigo 
		        group by k00_receit,k02_drecei,k00_dtoper,z01_nome";
        $result = pg_exec($sql);
        if(pg_numrows($result)==0){
          $erro = "Código de Arrecadação não Pago.";
        }else{
          $pesquisar = true;
		}
	  }
}else{
  if(isset($autenticar)){
    $np = substr($HTTP_POST_VARS["numpre"],0,8);
    $pa = substr($HTTP_POST_VARS["numpre"],8,3);
    if($np=="")
       $erro = "Numpre Inválido.";
    else
      if($pa=="")
        $erro = "Parcela Inválida.";
      else{  
        $sql = "select arrepaga.*,z01_nome
	            from arrepaga
				     left outer join cgm on k00_numcgm = z01_numcgm
                where k00_numpre = $np and k00_numpar = $pa
				      and k00_receit = ".$HTTP_POST_VARS["receitas"];
        $result = pg_exec($sql);
        if(pg_numrows($result)==0){
          $erro = "Código de Arrecadação não Pago.";
        }else{
           
		}
	  }
  }
}
$result_conta = pg_exec(
                "select saltes.k13_conta as c01_reduz,k13_descr as c01_descr,c60_estrut 
		         from saltes
				      inner join conplanoexe on c62_reduz = k13_conta and c62_anousu=". db_getsession("DB_anousu")."
				      inner join conplanoreduz on c61_reduz = k13_conta and c61_anousu=c62_anousu
				      inner join conplano on c60_codcon = c61_codcon and c60_anousu=c61_anousu 
				 order by k13_conta");
if(pg_numrows($result_conta) == 0){
  echo "<script>parent.alert('Sem Contas Cadastradas.');</script>";
  exit;
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
function js_verifica(){
  if(document.form1.numpre.value == '') {
     alert('Numpre nao Digitado');
	 document.form1.numpre.focus();
  }else{
     document.form1.action = 'cai4_difarrec001.php?acao=<?=$acao?>&pesquisa=0';
     document.form1.submit();
  }
}
function js_autenticar(){
  if(document.form1.valor.value == '') {
     alert('valor nao Digitado');
	 document.form1.valor.focus();
  }else{
     document.form1.action = 'cai4_difarrec002.php?acao=<?=$acao?>&autenticar=0';
     document.form1.submit();
  }
}
function js_atualizarec(qual) {
  if(qual=='receitas')
  document.form1.descr.options[document.form1.receitas.selectedIndex].selected = true;
  if(qual=='descr')
  document.form1.receitas.options[document.form1.descr.selectedIndex].selected = true;
}
function js_atualizaconta(qual) {
  if(qual=='reduz')
  document.form1.descrconta.options[document.form1.reduz.selectedIndex].selected = true;
  if(qual=='descrconta')
  document.form1.reduz.options[document.form1.descrconta.selectedIndex].selected = true;
}
</script>
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.numpre.focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td valign="top" bgcolor="#CCCCCC"> 
	<form name="form1" method="post" action="">
        <table width="100%" height="53%">
          <tr> 
            <td height="38" valign="top"> 
			<table width="100%">
                <tr> 
                  <td > 
                    <?
				  if($acao==1)
				     echo "Arrecadação a Maior";
				  else
				     echo "Arrecadação a Menor";
				  ?>
                  </td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td align="right">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr> 
                  <td align="right">Conta:</td>
                  <td><select onChange="js_atualizaconta(this.name)" name="reduz" id="reduz">
                      <?
		for($i=0;$i<pg_numrows($result_conta);$i++){
		  db_fieldsmemory($result_conta,$i);
  	      echo "<option value=\"$c01_reduz\" ".(isset($HTTP_POST_VARS["reduz"])?($HTTP_POST_VARS["reduz"]==$c01_reduz?"selected":""):"").">$c01_reduz</option>";
		}
	
		?>
                    </select>
                    &nbsp;&nbsp;
                    <select onChange="js_atualizaconta(this.name)" name="descrconta" id="descrconta">
                      <?
		for($i=0;$i<pg_numrows($result_conta);$i++){
		  db_fieldsmemory($result_conta,$i);
  	      echo "<option value=\"$c01_reduz\" ".(isset($HTTP_POST_VARS["descr"])?($HTTP_POST_VARS["descrconta"]==$c01_reduz?"selected":""):"").">$c01_descr</option>";
		}
		?>
                    </select></td>
                </tr>
                <tr> 
                  <td width="39%" align="right">C&oacute;digo Arrecada&ccedil;&atilde;o:</td>
                  <td width="61%"> 
                    <?
					if($pesquisar != true){
					?>
                    <input name="numpre" type="text" id="numpre2" size="30" maxlength="100"> 
                    <input name="pesquisa" type="button" id="pesquisa" value="Pesquisa" onClick="js_verifica()"> 
                    <?
					}else{
					?>
                    <input name="numpre" type="text" id="numpre2" readonly size="30" maxlength="100" value="<?=$HTTP_POST_VARS["numpre"]?>"> 
                    <?
					}
					?>
                  </td>
                </tr>
              </table></td>
          </tr>
          <tr> 
		    <?
            if($pesquisar == true)
  		      db_fieldsmemory($result,0); 
			?>
            <td valign="top"><table width="100%">
                <tr> 
                  <td width="39%" align="right">Nome Contribuinte:</td>
                  <td width="61%"><input name="nome" type="text" readonly id="nome" size="40" maxlength="80" value="<?=@$z01_nome?>"></td>
                </tr>
                <tr> 
                  <td align="right">Data pagamento:</td>
                  <td><input name="dtpago" type="text" id="dtpago" readonly size="10" value="<?=@$k00_dtoper?>"></td>
                </tr>
                <tr> 
                  <td align="right">Receita:</td>
                  <td>
				  <select onChange="js_atualizarec(this.name)" name="receitas" id="receitas">
                      <?
					  $vlrtot = "";
                    if($pesquisar == true){
					  for($x=0;$x < pg_numrows($result);$x++){
					    db_fieldsmemory($result,$x);
					    $vlrtot = $vlrtot + $k00_valor;
					    echo "<option value=\"$k00_receit\">".$k00_receit."</option>";
					  }
					  $vlrtot = $vlrtot * -1;
					}
					?>
                    </select>
					<select onChange="js_atualizarec(this.name)" name="descr" id="descr">
                      <?
                    if($pesquisar == true){
					  for($x=0;$x < pg_numrows($result);$x++){
					    db_fieldsmemory($result,$x);
					    echo "<option value=\"$k02_drecei\">".$k02_drecei."</option>";
					  }
					}
					?>
                    </select>
                  </td>
                </tr>
                <tr> 
                  <td align="right">Valor Pago:</td>
                  <td><input name="valorpago" type="text" id="valorpago2" readonly  size="20" maxlength="60" value="<?=@$vlrtot?>"> 
                  </td>
                </tr>
                <tr> 
                  <td align="right">Valor a Lan&ccedil;ar</td>
                  <td><input name="valor" type="text" id="valor" size="20" maxlength="60"></td>
                </tr>

                <tr>
                  <td align=right><b>Historico: </b></td>
		  <td><textarea name=historico cols=50></textarea></td>
		</tr>


                <tr> 
                  <td height="21">&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
				<?
				if($pesquisar == true){
				?>
                <tr> 
                  <td align="right">
                      <input name="autenticar" type="button" id="autenticar" value="Autenticar" onClick="js_autenticar()">
                  </td>
                  <td align="left">
				      <input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="location.href='cai4_difarrec001.php?acao=<?=$acao?>'"> 
                  </td>
                </tr>
				<?
				}
				?>
              </table>
            </td>
          </tr>
        </table>
      </form></td>
  </tr>
</table>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
 if($erro!="")
    echo "<script>alert('".$erro."')</script>";
?>