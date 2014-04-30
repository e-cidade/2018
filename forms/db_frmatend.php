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
//MODULO: atendimento
include ("classes/db_tipoatend_classe.php");
include ("classes/db_db_usuarios_classe.php");
include ("classes/db_clientes_classe.php");
$cldb_usuarios = new cl_db_usuarios;
$clclientes = new cl_clientes;
$cltecnico = new cl_tecnico;
$cltecnico->rotulo->label();
$cltipoatend = new cl_tipoatend;
$cltipoatend->rotulo->label();
$clatendimento->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("at01_nomecli");
$clrotulo->label("nome");
$clrotulo->label("at03_codatend");
$clrotulo->label("at03_id_usuario");
$clrotulo->label("at05_data");
$clrotulo->label("at05_perc");
$clrotulo->label("at05_feito");
$clrotulo->label("at05_solicitado");
$clrotulo->label("at08_modulo");
?>
<form name="form1" method="post" action="">
<center>
<?
if(!isset($horaini)) {
	$horaini = db_hora();
}
db_input("horaini",10,"",false,"hidden",3);

if(isset($opcao)&&$opcao!="") {
	db_input("opcao",10,"",false,"hidden",3);
}
else {
	$opcao = "incluir";
	db_input("opcao",10,"",false,"hidden",3);
}
db_input("codatend",10,"",true,"hidden",3);
?>
<tr>
<td colspan=4 align=center>
<b>Usuário envolvidos :</b>
</td>
</tr>
<tr>
<td colspan=4 align=center>
 
  <select name="usuorigem[]" multiple size="7">
<?
$rs_atend = $clatendimento->sql_record($clatendimento->sql_query_inc(null,"at10_usuario","at02_codatend desc","at02_codatend = $codatend"));
if($clatendimento->numrows>0) {
	db_fieldsmemory($rs_atend,0);
}

$sql4   = "select distinct at10_codcli, at10_nome, at10_usuario from db_usuclientes
           where at10_codcli = $clientes order by at10_nome";
$result_usucliente = pg_exec($sql4);
$numlinha = pg_numrows($result_usucliente);
for ($z = 0; $z < $numlinha; $z ++) {
	if(isset($at10_usuario)||$at10_usuario!="") {
		if($at10_usuario == Pg_result($result_usucliente, $z, "at10_usuario")) {
			$selected = "SELECTED";
		}
		else {
			$selected = "";
		}
	}
	else {
		$selected = "";
	}
	$usucliente_codigo = pg_result($result_usucliente, $z, "at10_codcli");
	$usucliente_nome = pg_result($result_usucliente, $z, "at10_nome");
	$usucliente_usuario = Pg_result($result_usucliente, $z, "at10_usuario");
	echo "<option value=$usucliente_usuario $selected>$usucliente_nome</option>";
}
?>
  </select>
</td>
</tr>
<tr>
	<td colspan=4 align=center>&nbsp;</td>
</tr>
<tr align=center>
    <td nowrap align=center title="<?=@$Tat02_solicitado?>" valign=top>
       <?=@$Lat02_solicitado?>
    </td>
    <td align=center> 
<?
db_textarea('at05_solicitado', 10, 50, $Iat05_solicitado, true, 'text', $db_opcao, "")
?>
    </td>
    <td nowrap align=center title="<?=@$Tat05_feito?>" valign=top>
       <?=@$Lat05_feito?>
    </td>
    <td align=center> 
<?
db_textarea('at05_feito', 10, 50, $Iat05_feito, true, 'text', $db_opcao, "")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tat05_perc?>">
       <?=@$Lat05_perc?>
    </td>
    <td> 
<?
//db_input('at05_perc',10,$Iat05_perc,true,'text',$db_opcao,"")
  $matriz = array("0"=>"0%",
                  "10"=>"10%", 
                  "20"=>"20%",
                  "30"=>"30%",
                  "40"=>"40%",
                  "50"=>"50%", 
                  "60"=>"60%",
                  "70"=>"70%",
                  "80"=>"80%",
                  "90"=>"90%",
                  "100"=>"100%");             
  db_select("at05_perc", $matriz,true,$db_opcao); 
?>
    </td>
  </tr>
  
  <tr align=center>
  <td align=center>
  <b>Modulo: Verificado</b></td>
        <td align=center><select name="modulo">
<?
echo $at08_modulo;
$sqlmod = "select id_item, nome_modulo from db_modulos order by nome_modulo";
echo "<option value=''>Selecione o modulo</option>";
$result_modulo = pg_exec($sqlmod);
$numlinha = pg_numrows($result_modulo);
echo ($sqlmod);
for ($a = 0; $a < $numlinha; $a ++) {
	$modulo_iditem = pg_result($result_modulo, $a, "id_item");
	$modulo_nome = pg_result($result_modulo, $a, "nome_modulo");
	
	if(isset($at08_modulo)&&$at08_modulo!="") {
		if($modulo_iditem == $at08_modulo) {
			$selected = "SELECTED";
		}
		else {
			$selected = "";
		}
	}
	else {
		$selected = "";
	}
	
	echo "<option value=$modulo_iditem $selected>$modulo_nome</option>";
}
?>
         </select>
         </td>  
    </tr>



  <tr>
    <td nowrap title="<?=@$Tat41_proced?>"><b>Procedimento:</b></td>
    <td> 
<?
if (isset($at41_proced) and $at41_proced == 0) {
	unset($at41_proced);
}
db_selectrecord('at41_proced',($cldb_proced->sql_record($cldb_proced->sql_query(($db_opcao==2?null:@$at41_proced),"at30_codigo,at30_descr","at30_codigo",null))),true,$db_opcao,"","","","0-Nenhum");
?>
    </td>
  </tr>





		
    <tr align=center >
    <td align=center nowrap title="<?=@$Tat05_data?>">
       <?=@$Lat05_data?>
    </td>
    <td align=center> 
<?
if ($db_opcao == 1) {
//	$at05_data_dia = date("d", db_getsession("DB_datausu"));
//	$at05_data_mes = date("m", db_getsession("DB_datausu"));
//	$at05_data_ano = date("Y", db_getsession("DB_datausu"));
}
db_inputdata('at05_data', @ $at05_data_dia, @ $at05_data_mes, @ $at05_data_ano, true, 'text', $db_opcao, "")
?>
    </td>
  </tr>
  
  <tr align=center >
    <td align=center nowrap colspan=2>
  <input name="<?=$opcao?>" type="submit"  value="<?=($opcao=="incluir"?"Incluir":"Alterar")?>"<?=($db_botao==false?"disabled":"")?> >
<!--<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >-->
    </td>
</tr>
 </table>
  </center>

</form>
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_atend','func_atendimentoinc.php?opcao=<?=$opcao?>&funcao_js=parent.js_preenchepesquisa|at02_codatend','Pesquisa',true);
  document.form1.opcao.value=<?=$opcao?>;	
}
function js_preenchepesquisa(chave){
  <?
  if($db_opcao!=1||$db_opcao!=2) {
	  echo " db_iframe_atend.hide();";
	  echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave+'&opcao=".$opcao."'";
  }
?>
}
</script>