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


if (@$k11_local == "") {

  $sql = "select nome from db_usuarios where id_usuario = ".db_getsession("DB_id_usuario");
  $result = pg_exec($sql);
  if (pg_numrows($result) > 0) {
    db_fieldsmemory($result,0);
    $k11_local = "MICRO DO USUARIO " . $nome;
  }

  $sql = "select nomeinst from db_config where codigo = " . db_getsession("DB_instit");
  $result = pg_exec($sql) or die($sql);
  if (pg_numrows($result) > 0) {
    db_fieldsmemory($result,0);
    $palavras = split(" ",$nomeinst);
    $conta=0;
    for ($i=0; $i < sizeof($palavras); $i++) {
//    echo $i . "-" . $palavras[$i] . "- conta: $conta<br>";
      if ($palavras[$i] == "DE") {
        	continue;
      }
      if ($conta == 0) {
	      $k11_ident1 = substr($palavras[$i],0,1);
       	$conta++;
      } else if ($conta == 1) {
	$k11_ident2 = substr($palavras[$i],0,1);
	$conta++;
      } else if ($conta == 2) {
	$k11_ident3 = substr($palavras[$i],0,1);
	$conta++;
      }
    }
  }
}

if(@$k11_ipterm == "") {
  $k11_ipterm=db_getsession('DB_ip');
}

if (@$k11_aut1 == "") {
  $k11_aut1=1;
}

if (@$k11_aut2 == "") {
  $k11_aut2=1;
}

?>

<center>
<form name="form1" method="post" onSubmit="return js_submeter()">
    <table width="59%" border="0" cellspacing="0" cellpadding="0">
      <tr> 
        <td width="25%" height="25"><strong>C&oacute;digo:</strong></td>
        <td width="75%" height="25"><input name="k11_id" type="text" id="k11_id" value="<?=@$k11_id?>" size="10" readonly></td>
      </tr>
      <tr> 
        <td height="25"><strong>Identifica&ccedil;&atilde;o 1:</strong></td>
        <td height="25"><input name="k11_ident1" type="text" id="k11_ident1" value="<?=@$k11_ident1?>" size="2" maxlength="1"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Identifica&ccedil;&atilde;o 2:</strong></td>
        <td height="25"><input name="k11_ident2" type="text" id="k11_ident2" value="<?=@$k11_ident2?>" size="2" maxlength="1"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Identifica&ccedil;&atilde;o 3:</strong></td>
        <td height="25"><input name="k11_ident3" type="text" id="k11_ident3" value="<?=@$k11_ident3?>" size="2" maxlength="1"></td>
      </tr>
      <tr> 
        <td height="25"><strong>IP Terminal Caixa:</strong></td>
        <td height="25"><input name="k11_ipterm" type="text" id="k11_ipterm" value="<?=@$k11_ipterm?>" size="20" maxlength="20"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Local:</strong></td>
        <td height="25"><input name="k11_local" type="text" id="k11_local" value="<?=@$k11_local?>" size="50" maxlength="30"></td>
      </tr>
      <tr> 
        <td height="25"><strong>Sequencia 1:</strong></td>
        <td height="25"><input name="k11_aut1" type="text" id="k11_aut1" value="<?=@$k11_aut1?>" size="20" maxlength="20"></td>
      </tr>
      <tr>
        <td height="25"><strong>Sequencia 2:</strong></td>
        <td height="25"><input name="k11_aut2" type="text" id="k11_aut2" value="<?=@$k11_aut2?>" size="20" maxlength="20"></td>
      </tr>

      <tr>
        <td height="25"><strong>Nome/Cargo</strong></td>
        <td height="25"><input name="k11_tesoureiro" 
	                       type="text" id="k11_teroureiro"
   	                       value="<?=@$k11_tesoureiro?>" 
			       size="40" maxlength="40"></td>
      </tr>

      
      <tr>
        <td height="25"><strong>Tipo da impressora de cheques :</strong></td>
	<td>
	<?
	$x = array("1"=>"CHRONOS","2"=>"Bematech","3"=>"Schalter");
	db_select('k11_tipoimp',$x,true,4,'');
	?>
	</td>
      </tr>
      <tr>
        <td height="25"><strong>Tipo de Autenticação:</strong></td>
	<td>
	<?
	$x = array("1"=>"Autentica e Imprime","2"=>"Autentica e Não Imprime","3"=>"Não Autentica e Não Imprime(somente Empenho)");
	db_select('k11_tipautent',$x,true,4,'');
	?>
	</td>
      </tr>
      <tr> 
        <td height="25">&nbsp;</td>
        <td height="25"><input name="enviar" type="submit" id="enviar" value="Enviar"></td>
      </tr>
    </table>
</form>
</center>
<script>
function js_submeter() {
  var str = new String(document.form1.k11_ipterm.value);
  var expr1 = /\./g;
  var expr2 = /\d{1,3}\.\d{1,3}\.\d{1,3}\.[0-9]{1,3}/;  
  if(str.match(expr1) != ".,.,." || str.match(expr2) == null) {
    alert("Endereço IP inválido!\n Formato xxx.xxx.xxx.xxx");
	document.form1.k11_ipterm.select();
	return false;
  } 
 
  return true;
}
js_Ipassacampo();
if(document.form1)
  document.form1.elements[1].focus();
</script>