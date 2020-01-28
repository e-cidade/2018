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

$sqltipoparc = "select distinct k40_codigo, k40_descr,k40_ordem
				from tipoparc
				inner join cadtipoparc on cadtipoparc = k40_codigo
				                      and k40_instit = ".db_getsession('DB_instit') ."
				where maxparc > 1
				   	  and '".date("Y-m-d",db_getsession("DB_datausu")) . "' >= k40_dtini
					  and '".date("Y-m-d",db_getsession("DB_datausu")) . "' <= k40_dtfim
					  order by k40_ordem";
$resulttipoparc = db_query($sqltipoparc) or die($sqltipoparc);
$linhastipoparc = pg_num_rows($resulttipoparc);
if($linhastipoparc > 0 ){

?>
<br><br>

<script>
function js_sobe() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex > 0) {
    var SI = F.selectedIndex - 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI + 1].text,F.options[SI + 1].value);
	F.options[SI + 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_desce() {
  var F = document.getElementById("campos");
  if(F.selectedIndex != -1 && F.selectedIndex < (F.length - 1)) {
    var SI = F.selectedIndex + 1;
    var auxText = F.options[SI].text;
	var auxValue = F.options[SI].value;
	F.options[SI] = new Option(F.options[SI - 1].text,F.options[SI - 1].value);
	F.options[SI - 1] = new Option(auxText,auxValue);
	js_trocacordeselect();
	F.options[SI].selected = true;
  }
}
function js_excluir() {
  var F = document.getElementById("campos");
  var SI = F.selectedIndex;
  if(F.selectedIndex != -1 && F.length > 0) {
    F.options[SI] = null;
	js_trocacordeselect();
    if(SI <= (F.length - 1))
      F.options[SI].selected = true;
  }
}

function js_submit() {

  var F = document.getElementById("campos");
  var iTotalLinhas = F.length;

  var sOrdem    = "";
  var sSepardor = "";

  for (var i = 0; i < iTotalLinhas; i++) {
    F.options[i].selected = true;
  }

}

</script>
<form name="form1" method="post" onSubmit="return js_submit()">
<table  border="0">
  <tr>
    <td nowrap >
      <fieldset><Legend><b>Ordem das regras de parcelamento</b></legend>
      <table border="0">
      	<tr><td>
      	<select name="campos[]" id="campos" size="10" style="width:250px" multiple>
      	<?
      	for($i = 0;$i < $linhastipoparc;$i++) {
		  db_fieldsmemory($resulttipoparc,$i);
          echo "<option value='{$k40_codigo}'>{$k40_descr} </option>";
      	}
		?>
      	</td>
      	<td align="left" valign="middle" >
		      <img style="cursor:hand" onClick="js_sobe();return false;" src="skins/img.php?file=Controles/seta_up.png" />
          <br/><br/>
          <img style="cursor:hand" onClick="js_desce()" src="skins/img.php?file=Controles/seta_down.png" />
          <br/><br/>
	      </td>
      	</tr>

      </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td colspan = "2" align= "center">
      <br><input name="db_opcao" type="submit" id="db_opcao" value="Processar" >
    </td>
  </tr>
</table>
</form>

<?
}else{
  db_msgbox("Nunhuma regra de parcelamento encontrada.");
}
?>