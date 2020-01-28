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
?>
<form name="form1" method="post" action="">
  <table width="90%" border="1" align="center" cellpadding="0" cellspacing="0">
    <tr> 
      <td colspan="4" nowrap bgcolor="#CDCDFF" align="center"><strong>&nbsp;Inclusão 
        de impressoras por usuário MAX&nbsp; </strong> </td>
    </tr>
    <tr> 
      <td width="32%"  align="center" nowrap> <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td align="center">&nbsp;Usuários&nbsp;</td>
          </tr>
          <tr> 
            <td  align="center" > <input name="desmarcarUsuarios" type="button" id="desmarcarUsuarios" onClick="javascript: js_desmarcar('db_usuarios')" value="Desmarcar todos"> 
            </td>
          </tr>
          <tr> 
            <td  align="center" > 
			<select name="usuarios[]" id="select" size="10" multiple   onChange="document.form1.submit()">
                <?
	$sql = "
		select id_usuario, nome
		from db_usuarios
	";
	$result = pg_exec($sql);
	$numrows = pg_numrows($result);
	for ($i=0;$i<$numrows;$i++) {
	  $db_select = ""; 
  	  if (isset($usuarios) && sizeof($usuarios)!=0){
		if (in_array(pg_result($result,$i,"id_usuario"),$usuarios)==1){
			$db_select =  " selected ";
		}
	  }else{
		if (isset($usuarios) && $usuarios == pg_result($result,$i,"id_usuario")){
			$db_select = " selected ";
		}
	  }

      ?>
                <option value="<?=@pg_result($result,$i,"id_usuario")?>" <?=$db_select?> >&nbsp; 
                <?=@pg_result($result,$i,"nome")?>
                &nbsp;</option>
                <?
	}
    ?>
              </select> </td>
          </tr>
          <tr> 
            <td  align="center" > <input name="marcarUsuarios" type="button" id="marcarUsuarios" onClick="javascript: js_marcar('db_usuarios')" value="Marcar todos"> 
            </td>
          </tr>
        </table></td>
      <td width="33%"  align="center" nowrap> <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td align="center">&nbsp;Departamentos:&nbsp;</td>
          </tr>
          <tr> 
            <td  align="center" > <input name="desmarcar" type="button" id="desmarcar" onClick="js_desmarcar('db_depart')" value="Desmarcar todos"> 
            </td>
          </tr>
          <tr> 
            <td  align="center" > <select name="depto[]" id="select" size="10" multiple onClick="document.form1.submit()">
                <?
	$sql = "
		select coddepto, descrdepto
		from db_depart
	";
	$result = pg_exec($sql);
	$numrows = pg_numrows($result);
	for ($i=0;$i<$numrows;$i++) {
?>
                <option value="<?=@pg_result($result,$i,"coddepto")?>" <?
	if (isset($depto) && sizeof($depto)!=0){
		if (in_array(pg_result($result,$i,"coddepto"),$depto)==1){
			echo " selected ";
		}
	}else{
		if (isset($depto) && $depto == pg_result($result,$i,"coddepto")){
			echo " selected ";
		}
	}
?>>&nbsp; 
                <?=@pg_result($result,$i,"descrdepto")?>
                &nbsp;</option>
                <?
	}
?>
              </select> </td>
          </tr>
          <tr> 
            <td  align="center" > <input name="marcar" type="button" id="marcar" onClick="js_marcar('db_depart')" value="Marcar todos"> 
            </td>
          </tr>
        </table></td>
      <td width="33%" align="center" nowrap> <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
          <tr> 
            <td align="center">&nbsp;Impressoras:&nbsp;</td>
          </tr>
          <tr> 
            <td  align="center" > <input name="desmarcarImpressoras" type="button" id="desmarcarImpressoras" onClick="js_desmarcar('impres')" value="Desmarcar todos"> 
            </td>
          </tr>
          <tr> 
            <td  align="center" > <select name="impressoras[]" id="select" size="10" multiple onClick="document.form1.submit()">
                <?
	$sql = "
		select d50_codigo, d50_descr
		from impres
	";
	$result = pg_exec($sql);
	$numrows = pg_numrows($result);
	if (sizeof($usuarios)==1){
		$sqlPesqisaImpressorasDoUsuario = "
			select d51_usuario, d51_impres
			from perimp
			where d51_usuario = $usuarios[0]
		";
		$result_sqlPesqisaImpressorasDoUsuario = pg_exec($sqlPesqisaImpressorasDoUsuario);
		$num = pg_numrows($result_sqlPesqisaImpressorasDoUsuario);
	}
	for ($i=0;$i<$numrows;$i++) {
?>
                <option value="<?=@pg_result($result,$i,"d50_codigo")?>"
<?
	$escreveuSelected = false;
	if (isset($usuarios) && sizeof($usuarios)==1){
		for ($a=0;$a<$num;$a++) {
			if (pg_result($result,$i,"d50_codigo") == pg_result($result_sqlPesqisaImpressorasDoUsuario,$a,"d51_impres")){
				echo " selected ";
				$escreveuSelected = true;
			}
		}
	}
	if (!$escreveuSelected){
		if (isset($impressoras) && sizeof($impressoras)!=0){
			if (in_array(pg_result($result,$i,"d50_codigo"),$impressoras)==1){
				echo " selected ";
			}
		}else{
			if (isset($impressoras) && $impressoras == pg_result($result,$i,"d50_codigo")){
				echo " selected ";
			}
		}
	}
?>
>&nbsp; 
                <?=@pg_result($result,$i,"d50_descr")?>
                &nbsp;</option>
                <?
	}
?>
              </select> </td>
          </tr>
          <tr> 
            <td  align="center" > <input name="marcarImpressoras" type="button" id="marcarImpressoras" onClick="js_marcar('impres')" value="Marcar todos"> 
            </td>
          </tr>
        </table></td>
    </tr>
    <tr> 
      <td colspan="3" align="center">
	   <input name="incluir" type="submit" id="incluir" onclick="return js_validaCampos()"  value="Incluir">&nbsp;&nbsp;&nbsp;
       <input name="excluir" type="submit" id="excluir" onclick="return js_validaCamposExclusao()" value="Excluir">
  
	  </td>
    </tr>
  </table>
</form>
<script>
function js_desmarcar(lista) {
	if (lista == "impres"){
		var F = document.form1.elements['impressoras[]'];
	}else if (lista == "db_depart"){
		var F = document.form1.elements['depto[]'];
	}else if (lista == "db_usuarios"){
		var F = document.form1.elements['usuarios[]'];
	}
	if(F.selectedIndex != -1) {
		for(i = 0;i < F.length;i++) {
			F.options[i] = new Option(F.options[i].text,F.options[i].value);
		}
	}
	js_trocacordeselect();
}

function js_marcar(lista) {
	if (lista == "impres"){
		var F = document.form1.elements['impressoras[]'];
	}else if (lista == "db_depart"){
		var F = document.form1.elements['depto[]'];
	}else if (lista == "db_usuarios"){
		var F = document.form1.elements['usuarios[]'];
	}
	for(i = 0;i < F.length;i++) {
		F.options[i].selected = true;
	}
	js_trocacordeselect();
}

function js_validaCampos(){
	var F = document.form1;

	var qtd_usuarios = 0;
	for (i=0;i<F.elements["usuarios[]"].length;i++){
		if (F.elements["usuarios[]"].options[i].selected){
			qtd_usuarios++;
		}
	}

	var qtd_depto = 0;
	for (i=0;i<F.elements["depto[]"].length;i++){
		if (F.elements["depto[]"].options[i].selected){
			qtd_depto++;
		}
	}

	var qtd_impressoras = 0;
	for (i=0;i<F.elements["impressoras[]"].length;i++){
		if (F.elements["impressoras[]"].options[i].selected){
			qtd_impressoras++;
		}
	}

	if ((qtd_usuarios==0)&&(qtd_depto==0)){
		alert("Selecione ao menos um usuário ou departamento.");
		return false;
	}

	if (qtd_impressoras==0){
		alert("Selecione ao menos uma impressora.");
		return false;
	}
}

function js_validaCamposExclusao(){
	var F = document.form1;
	var qtd_impressoras = 0;
	for (i=0;i<F.elements["impressoras[]"].length;i++){
		if (F.elements["impressoras[]"].options[i].selected){
			qtd_impressoras++;
		}
	}
	if (qtd_impressoras==0){
		alert("Selecione ao menos uma impressora.");
		return false;
	}
}
</script>