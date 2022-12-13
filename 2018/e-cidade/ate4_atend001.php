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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("classes/db_atendimento_top_classe.php"));
include(modification("classes/db_atendimentoorigem_classe.php"));
include(modification("classes/db_atendimentomod_classe.php"));
include(modification("classes/db_tecnico_classe.php"));
include(modification("classes/db_atendimentolanc_classe.php"));
include(modification("classes/db_clientes_classe.php"));
include(modification("classes/db_db_usuclientes_classe.php"));
include(modification("classes/db_atendimento_classe.php"));
include(modification("classes/db_atendimentousu_classe.php"));
include(modification("classes/db_atendimentocadsituacao_classe.php"));
include(modification("classes/db_atendimentosituacao_classe.php"));
include(modification("classes/db_db_usuarios_classe.php"));
include(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_tipoatend_classe.php"));
db_postmemory($HTTP_POST_VARS);
$cltipoatend         = new cl_tipoatend;
$clatendimento_top   = new cl_atendimento_top;
$clatendimentoorigem = new cl_atendimentoorigem;
$clatendimentomod    = new cl_atendimentomod;
$cltecnico           = new cl_tecnico;
$clatendimentolanc   = new cl_atendimentolanc;
$clclientes          = new cl_clientes;
$cldb_usuclientes    = new cl_db_usuclientes;
$clatendimento       = new cl_atendimento;
$clatendimentousu    = new cl_atendimentousu;
$clatendimentocadsituacao  = new cl_atendimentocadsituacao;
$clatendimentosituacao    = new cl_atendimentosituacao;
$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("at10_usuario");
$clrotulo->label("nome_tecnico");
$clrotulo->label("nome_modulo");
$clrotulo->label("at02_codatend");
$clrotulo->label("at11_origematend");
$clrotulo->label("at04_codtipo");
$clrotulo->label("at05_seq");
$clrotulo->label("at40_sequencial");
$clrotulo->label("at02_observacao");
$clrotulo->label("at16_situacao");

if (isset($incluir)&&$incluir!=""){
	$sqlerro=false;
	db_inicio_transacao();
	$clatendimento->at02_codcli     = $cliente;
	$clatendimento->at02_codtipo    = $at04_codtipo;
	$clatendimento->at02_observacao = $at02_observacao;
	$clatendimento->incluir(null);
	$erro_msg = $clatendimento->erro_msg;
	if ($clatendimento->erro_status=="0"){
		$sqlerro=true;
	}
	if ($sqlerro==false){
		$flag_grava = true;
		$rs_cliente = $clclientes->sql_record($clclientes->sql_query($cliente,"at01_nomecli","at01_codcli",""));
		if($clclientes->numrows>0) {
			db_fieldsmemory($rs_cliente,0);
			if(strcmp(trim(strtoupper($at01_nomecli)),"DBSELLER") == 0) {
				$flag_grava = false;
			}
		}
		if($flag_grava) {
			$clatendimentousu->at20_codatend = $clatendimento->at02_codatend;
			$clatendimentousu->at20_usuario = $usuario;
			$clatendimentousu->incluir(null);
			if ($clatendimentousu->erro_status=="0"){
				$sqlerro=true;
				$erro_msg = $clatendimentousu->erro_msg;
			}
		}
	}
	if ($sqlerro==false){
		$clatendimentolanc->at06_codatend    = $clatendimento->at02_codatend;
		$clatendimentolanc->at06_usuariolanc = db_getsession("DB_id_usuario");
		$clatendimentolanc->at06_datalanc    = date("Y-m-d", db_getsession("DB_datausu"));
		$clatendimentolanc->at06_horalanc    = db_hora();
		$clatendimentolanc->incluir($clatendimento->at02_codatend);
		$erro_msg = $clatendimentolanc->erro_msg;
		if ($clatendimentolanc->erro_status=="0"){
			$sqlerro=true;
		}
	}
	if ($sqlerro==false&&isset($tecnico)&&$tecnico!=""){
		$cltecnico->at03_codatend   = $clatendimento->at02_codatend;
		$cltecnico->at03_id_usuario = $tecnico;
		$cltecnico->incluir($clatendimento->at02_codatend,$tecnico);
		$erro_msg = $cltecnico->erro_msg;
		if ($cltecnico->erro_status=="0"){
			$sqlerro=true;
		}
	}
	if ($sqlerro==false&&isset($at08_modulo)&&$at08_modulo!=""){
		$clatendimentomod->at08_atend   = $clatendimento->at02_codatend;
		$clatendimentomod->at08_modulo  = $at08_modulo;
		$clatendimentomod->incluir();
		$erro_msg = $clatendimentomod->erro_msg;
		if ($clatendimentomod->erro_status=="0"){
			$sqlerro=true;
		}
	}
	if ($sqlerro==false&&isset($at16_situacao)&&$at16_situacao!=""){
		$clatendimentosituacao->at16_atendimento = $clatendimento->at02_codatend;
		$clatendimentosituacao->at16_situacao = $at16_situacao;
		$clatendimentosituacao->incluir(null);
		$erro_msg = $clatendimentosituacao->erro_msg;
		if ($clatendimentosituacao->erro_status=="0"){
			$sqlerro=true;
		}
	}
	if($sqlerro==false&&isset($at02_codatend)&&$at02_codatend!=""){
		$clatendimentoorigem->at11_origematend = $at02_codatend;
		$clatendimentoorigem->at11_novoatend   = $clatendimento->at02_codatend;
		$clatendimentoorigem->incluir(null);
		if($clatendimentoorigem->erro_status=="0") {
			@$sqlerro=true;
			$erro_msg = $clatendimentoorigem->erro_msg;
		}
		else {
			$rs_atend_orig = $clatendimentoorigem->sql_record($clatendimentoorigem->sql_query_file(null,"at11_origematend","at11_origematend","at11_origematend = $at02_codatend"));
			if ($clatendimentoorigem->numrows > 0) {
			  db_fieldsmemory($rs_atend_orig,0);
			}
		}
	}
	if($sqlerro==false&&isset($cliente)&&$cliente!=""&&isset($usuario)&&$usuario!=""){
		$rs_atend_top = $clatendimento_top->sql_record($clatendimento_top->sql_query_file(null,"*","at14_sequencial","at14_codcli = $cliente and at14_usuario = $usuario"));
		if ($clatendimento_top->numrows > 0) {
		  db_fieldsmemory($rs_atend_top,0);
		}

		if($clatendimento_top->numrows == 0) {
			$clatendimento_top->at14_codcli  = $cliente;
			$clatendimento_top->at14_usuario = $usuario;
			$clatendimento_top->at14_qtd     = 1;
			$clatendimento_top->incluir(null);
		}
		else {
			$qtd = $at14_qtd;
			$qtd++;
			$clatendimento_top->at14_sequencial = $at14_sequencial;
			$clatendimento_top->at14_qtd        = $qtd;
			$clatendimento_top->alterar($clatendimento_top->at14_sequencial);
		}
	}

	db_fim_transacao($sqlerro);
	if ($sqlerro==true){
		db_msgbox($erro_msg);
		echo "<script>location.href='ate4_atend001.php';</script>";
	}else{
	    $certo=true;
	}
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_submit(){
	document.form1.usuario.value = "";
	document.form1.nome.value = "";
	document.form1.submit();
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br><br>
  <table  align="center">
    <form name="form1" method="post" action="">
    <?
    $result_clientes = $clclientes->sql_record($clclientes->sql_query_file(null,"at01_codcli,at01_nomecli","at01_nomecli"," at01_ativo is true"));
   	db_input("at02_codatend",10,$Iat02_codatend,true,"hidden",3);
   	db_input("at11_origematend",10,$Iat11_origematend,true,"hidden",3);
   	db_input("at05_seq",10,$Iat05_seq,true,"hidden",3);
   	db_input("at40_sequencial",10,$Iat40_sequencial,true,"hidden",3);
    ?>
      <tr>
      <td ><b>Cliente:</b></td>
         <td >
         <?
//         db_selectrecord("cliente",$result_clientes,true,1,"onblur='js_submit();'");
	       db_selectrecord("cliente",$result_clientes,true,1,"");
         ?>
         </td>

      </tr>

      <?
      if (!isset($cliente)||$cliente==""){
           if($clclientes->numrows>0){
           		db_fieldsmemory($result_clientes,0);
           		$cliente=$at01_codcli;
           }
      }
      if (isset($cliente)&&$cliente!=""){
      	echo "<script>document.form1.cliente.value=$cliente</script>";
      ?>
      <tr>
    	<td  align="left" nowrap><b><?db_ancora('Usuário:',"js_pesquisa_usuario(true);",1);?></b></td>
    	<td align="left" nowrap>
      	<?
      	db_input("usuario",10,$Iat10_usuario,true,"text",4,"onchange='js_pesquisa_usuario(false);'");
      	db_input("nome",40,$Inome,true,"text",3);
        ?>
        </td>
  	  </tr>
      <tr>
    	<td  align="left" nowrap><b><?db_ancora('Técnico:',"js_pesquisa_tecnico(true);",1);?></b></td>
    	<td align="left" nowrap>
      	<?
      	db_input("tecnico",10,"",true,"text",4,"onchange='js_pesquisa_tecnico(false);'");
      	db_input("nome_tecnico",40,"",true,"text",3);
        ?>
        </td>
  	  </tr>
      <tr>
    	<td  align="left" nowrap><b><?db_ancora('Módulo:',"js_pesquisa_modulo(true);",1);?></b></td>
    	<td align="left" nowrap>
      	<?
      	db_input("at08_modulo",10,"",true,"text",4,"onchange='js_pesquisa_modulo(false);'");
      	db_input("nome_modulo",40,"",true,"text",3);
        ?>
        </td>
  	  </tr>
	<tr>
	    <td nowrap title="<?=@$Tat04_codtipo?>">
    	   <?=@$Lat04_codtipo?>
    	</td>
		<td>
		<?
		  $result = $cltipoatend->sql_record($cltipoatend->sql_query(null,"*",null,"at04_codtipo >= 100"));
		  db_selectrecord("at04_codtipo",$result,false,1);
		?>
		</td>
	</tr>






	<tr>
	    <td nowrap title="<?=@$Tat16_situacao?>">
    	   <?=@$Lat16_situacao?>
    	</td>
		<td>
		<?
		  $result = $clatendimentocadsituacao->sql_record($clatendimentocadsituacao->sql_query(null,"*",null,""));
		  db_selectrecord("at16_situacao",$result,false,1);
		?>
		</td>
	</tr>






	<tr>
	    <td nowrap title="<?=@$Tat02_observacao?>">
    	   <?=@$Lat02_observacao?>
    	</td>
		<td>
		<?
		  db_textarea('at02_observacao', 10, 50, $Iat02_observacao, true, 'text', 1, "");
		?>
		</td>
	</tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <?
      }
      ?>
      <tr>
        <td colspan=2 align=center>
        <input type='submit' name='incluir' value='Incluir' <?=(isset($certo)&&$certo==true?"disabled":"") ?> >
        <input type='button' name='origem' value='Origem' <?=(isset($certo)&&$certo==true?"disabled":"") ?> onclick="js_origem();">
        </td>
      </tr>
      <tr>
         <td >&nbsp;</td>
         <td >&nbsp;</td>
      </tr>
      <?
      if (isset($certo)&&$certo==true){
	  ?>
      	<tr>
        	<td colspan=2 align=center>
            <h1>Atendimento Nº <?=$clatendimento->at02_codatend?><? if(isset($at11_origematend)&&$at11_origematend!="") { echo "  Atend. Inicial Nº ".$at11_origematend; } if(isset($at05_seq)&&$at05_seq!="") { echo "  Andamento Nº ".$at05_seq; } if(isset($at40_sequencial)&&$at40_sequencial!="") { echo "  Tarefa Nº ".$at40_sequencial; } ?></h1>
          	</td>
      	</tr>
	   <?
        $codatend=$clatendimento->at02_codatend;
       ?>
       <tr>
         <td align = center><input type='button' name='reset' value='Voltar' onclick="location.href='ate4_atend001.php';" >&nbsp;</td>
         <td align = center><input type='button' name='processa' value='Incluir Andamento' onclick="location.href='ate4_atend002.php?chavepesquisa=<?=$codatend?>';" >&nbsp;</td>
      </tr>
       <?
      }
      ?>
  </form>
    </table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
//--------------------------------
function js_origem() {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_atendimentoorigem','func_atendimentoorigem.php?chave_cliente='+document.form1.cliente.value+'&chave_usuario='+document.form1.usuario.value+'&chave_tecnico='+document.form1.tecnico.value+'&chave_modulo='+document.form1.at08_modulo.value+'&funcao_js=parent.js_mostraatendimentoorigem|at01_codcli|at10_usuario|id_usuario|id_item|at02_codatend','Pesquisa',true);
}
function js_mostraatendimentoorigem(chave_cliente,chave_usuario,chave_tecnico,chave_modulo,chave_atend,erro){
  document.form1.cliente.value       = chave_cliente;
  document.form1.clientedescr.value  = chave_cliente;
  document.form1.usuario.value       = chave_usuario;
  document.form1.tecnico.value       = chave_tecnico;
  document.form1.at08_modulo.value   = chave_modulo;
  document.form1.at02_codatend.value = chave_atend;
  db_iframe_atendimentoorigem.hide();
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_usucliente','func_db_usuclientesalt.php?cliente='+document.form1.cliente.value+'&pesquisa_chave='+document.form1.usuario.value+'&funcao_js=parent.js_mostramatordem','Pesquisa',false);
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.tecnico.value+'&funcao_js=parent.js_mostra_tecnico','Pesquisa',false);
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_modulos','func_db_modulos.php?pesquisa_chave='+document.form1.at08_modulo.value+'&funcao_js=parent.js_mostra_modulo','Pesquisa',false);
}
function js_pesquisa_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_usucliente','func_db_usuclientesalt.php?cliente='+document.form1.cliente.value+'&funcao_js=parent.js_mostramatordem1|at10_usuario|at10_nome','Pesquisa',true);
  }else{
     if(document.form1.usuario.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_usucliente','func_db_usuclientesalt.php?cliente='+document.form1.cliente.value+'&pesquisa_chave='+document.form1.usuario.value+'&funcao_js=parent.js_mostramatordem','Pesquisa',false);
     }else{
       document.form1.usuario.value = '';
       document.form1.nome.value = '';
     }
  }
}
function js_mostramatordem(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.usuario.value = '';
    document.form1.usuario.focus();
  }
}
function js_mostramatordem1(chave1,chave2){
   document.form1.usuario.value = chave1;
   document.form1.nome.value = chave2;
   db_iframe_usucliente.hide();
}
function js_pesquisa_tecnico(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostra_tecnico1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.tecnico.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.tecnico.value+'&funcao_js=parent.js_mostra_tecnico','Pesquisa',false);
     }else{
       document.form1.tecnico.value      = '';
       document.form1.nome_tecnico.value = '';
     }
  }
}
function js_mostra_tecnico(chave,erro){
  document.form1.nome_tecnico.value = chave;
  if(erro==true){
    document.form1.tecnico.value = '';
    document.form1.tecnico.focus();
  }
}
function js_mostra_tecnico1(chave1,chave2){
   document.form1.tecnico.value = chave1;
   document.form1.nome_tecnico.value = chave2;
   db_iframe_db_usuarios.hide();
}
function js_pesquisa_modulo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_modulos','func_db_modulos.php?funcao_js=parent.js_mostra_modulo1|id_item|nome_modulo','Pesquisa',true);
  }else{
     if(document.form1.at08_modulo.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_db_modulos','func_db_modulos.php?pesquisa_chave='+document.form1.at08_modulo.value+'&funcao_js=parent.js_mostra_modulo','Pesquisa',false);
     }else{
       document.form1.at08_modulo.value = '';
       document.form1.nome_modulo.value = '';
     }
  }
}
function js_mostra_modulo(chave,erro){
  document.form1.nome_modulo.value = chave;
  if(erro==true){
    document.form1.at08_modulo.value = '';
    document.form1.at08_modulo.focus();
  }
}
function js_mostra_modulo1(chave1,chave2){
   document.form1.at08_modulo.value = chave1;
   document.form1.nome_modulo.value = chave2;
   db_iframe_db_modulos.hide();
}
//--------------------------------
</script>
