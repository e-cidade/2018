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
include("classes/db_tarefaparam_classe.php");
include("classes/db_atendimentomod_classe.php");
include("classes/db_db_usuclientes_classe.php");
include("classes/db_atendimento_classe.php");
include("classes/db_atenditem_classe.php");
include("classes/db_atenditemmod_classe.php");
include("classes/db_atenditemusu_classe.php");
include("classes/db_tecnico_classe.php");
include("classes/db_tarefa_classe.php");
include("classes/db_tarefa_agenda_classe.php");
include("classes/db_tarefaproced_classe.php");
include("classes/db_db_proced_classe.php");
include("classes/db_tarefausu_classe.php");
include("classes/db_tarefaenvol_classe.php");
include("classes/db_tarefa_lanc_classe.php");
include("classes/db_tarefaclientes_classe.php");
include("classes/db_tarefamodulo_classe.php");
include("classes/db_tarefamotivo_classe.php");
include("classes/db_tarefasituacao_classe.php");
include("classes/db_tarefaitem_classe.php");
include("classes/db_tarefalog_classe.php");
include("classes/db_tarefalogsituacao_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$clatendimento = new cl_atendimento;
$cltecnico = new cl_tecnico;
$cltarefa = new cl_tarefa;
$cltarefa_agenda = new cl_tarefa_agenda;
$cltarefaparam    = new cl_tarefaparam;
$cltarefamodulo = new cl_tarefamodulo;
$cltarefamotivo = new cl_tarefamotivo;
$cltarefasituacao = new cl_tarefasituacao;
$cltarefaitem = new cl_tarefaitem;
$cltarefausu = new cl_tarefausu;
$cltarefaenvol = new cl_tarefaenvol;
$cltarefaproced = new cl_tarefaproced;
$cldb_proced = new cl_db_proced;
$cltarefa_lanc = new cl_tarefa_lanc;
$cltarefaclientes = new cl_tarefaclientes;
$clatenditem = new cl_atenditem;
$clatenditemmod = new cl_atenditemmod;
$clatenditemusu = new cl_atenditemusu;
$clatendimentomod = new cl_atendimentomod;
$cltarefalog = new cl_tarefalog;
$cltarefalogsituacao = new cl_tarefalogsituacao;

$db_opcao = 11;
if(isset($opcao)&&$opcao!="") {
	if($opcao=="incluir") {
		$db_opcao = 11;
	}
	if($opcao=="alterar") {
		$db_opcao = 22;
	}
}
$db_botao = true;
if (isset($incluir)&&$incluir!=""){
	db_inicio_transacao();
	$sqlerro=false;
	if($sqlerro==false){
	$clatenditem->at05_codatend=$codatend;
	$clatenditem->at05_solicitado=$at05_solicitado;
	$clatenditem->at05_tipo=1;
	if(isset($at05_data_dia)&&$at05_data_dia!="") {
		$clatenditem->at05_data=$at05_data_ano."-".$at05_data_mes."-".$at05_data_dia;
	}	
	$clatenditem->incluir(null,$codatend);
	$erro_msg=$clatenditem->erro_msg;
	if ($clatenditem->erro_status=="0"){
		$sqlerro=true;
	}
	}
	if (isset($modulo)&&$modulo!="" and $sqlerro == false){
		if($sqlerro==false){
		$clatenditemmod->at22_atenditem=$clatenditem->at05_seq;
		$clatenditemmod->at22_codatend=$codatend;
		$clatenditemmod->at22_modulo=$modulo;
		$clatenditemmod->incluir(null);
		if ($clatenditemmod->erro_status=="0"){
			$sqlerro=true;
			$erro_msg=$clatenditemmod->erro_msg;
		}
		
		if($sqlerro==false) {
		    $clatendimentomod->at08_atend=$codatend; 			
		    $clatendimentomod->at08_modulo=$modulo;
		    $clatendimentomod->incluir();
			if ($clatendimentomod->erro_status=="0"){
				$sqlerro=true;
				$erro_msg=$clatendimentomod->erro_msg;
			}
		}
		}
	}
	if (isset($usuorigem)&&$usuorigem!="" and $sqlerro == false){
		reset($usuorigem);
		for($w=0;$w<count($usuorigem);$w++){
			if ($usuorigem[$w]!=""){
				if($sqlerro==false){
			    $clatenditemusu->at21_atenditem=$clatenditem->at05_seq;
			    $clatenditemusu->at21_codatend=$codatend;
			    $clatenditemusu->at21_usuario=$usuorigem[$w];
				$clatenditemusu->incluir(null);
				if ($clatenditemusu->erro_status=="0"){
					$sqlerro=true;
					$erro_msg=$clatenditemusu->erro_msg;
					break;
				}
				}
			}
			next($usuorigem);	
		}		
	}	
	if ($at05_perc == 100 and $sqlerro == false) {
		
		if (isset($at41_proced) and ($at41_proced == "0") and $sqlerro == false) {
			$sqlerro = true;
			$erro_msg = "Preencha o procedimento!";
		}

    if ($sqlerro == false) {
			$result        = $clatendimento->sql_record($clatendimento->sql_query_inc(null,"at01_codcli","","at02_codatend = $codatend"));
			if($clatendimento->numrows > 0) {
				db_fieldsmemory($result,0);
			}
		}
		
    if ($sqlerro == false) {
			$cltarefa->at40_responsavel		= db_getsession("DB_id_usuario");
			$cltarefa->at40_descr					= $at05_solicitado;
			$cltarefa->at40_diaini				= date("Y-m-d",db_getsession("DB_datausu"));
			$cltarefa->at40_diafim				= date("Y-m-d",db_getsession("DB_datausu"));
			$cltarefa->at40_tipoprevisao	= "h";
			$cltarefa->at40_previsao			= 1;
			$cltarefa->at40_horainidia		= $horaini;
			$cltarefa->at40_horafim				= db_hora();
			$cltarefa->at40_progresso			= 100;
			$cltarefa->at40_prioridade		= 1;
			$cltarefa->at40_obs						= $at05_feito;
			$cltarefa->at40_autorizada		= 't';
			$cltarefa->at40_ativo					= 't';
			$cltarefa->incluir(null);
			if ($cltarefa->erro_status == 0) {
				$sqlerro = true;
				$erro_banco = $cltarefa->erro_msg;
			}
		}
		
		if ($sqlerro == false) {
		  $cltarefaproced->incluir($cltarefa->at40_sequencial,$at41_proced);
			if($cltarefaproced->erro_status==0) {
				$sqlerro = true;
				$erro_msg = $cltarefaproced->erro_msg;
			}
		}
				
	  if (isset($modulo)&&$modulo!="" and $sqlerro == false) {
      $cltarefamodulo->at49_tarefa = $cltarefa->at40_sequencial;
			$cltarefamodulo->at49_modulo = $modulo;
			$cltarefamodulo->incluir(null);
			if ($cltarefamodulo->erro_status == 0) {
				$sqlerro = true;
				$erro_banco = $cltarefamodulo->erro_msg;
			}
		}

    if ($sqlerro == false) {
			$cltarefamotivo->at55_tarefa = $cltarefa->at40_sequencial;
			$cltarefamotivo->at55_motivo = 12;
			$cltarefamotivo->incluir();
			if ($cltarefamotivo->erro_status == 0) {
				$sqlerro = true;
				$erro_banco = $cltarefamotivo->erro_msg;
			}
		}

    if ($sqlerro == false) {
			$cltarefasituacao->at47_situacao = 3;
			$cltarefasituacao->at47_tarefa   = $cltarefa->at40_sequencial;
			$cltarefasituacao->incluir(null);
			if($cltarefasituacao->erro_status==0) {
				$sqlerro = true;
				$erro_msg = $cltarefasituacao->erro_msg;
			}
		}

    if ($sqlerro == false) {
			$cltarefaitem->at44_atenditem = $clatenditem->at05_seq;
			$cltarefaitem->at44_tarefa	  = $cltarefa->at40_sequencial;
			$cltarefaitem->incluir($cltarefa->at40_sequencial);
			if($cltarefaitem->erro_status==0) {
				$sqlerro = true;
				$erro_msg = $cltarefaitem->erro_msg;
			}
		}

    if ($sqlerro == false) {
			$cltarefausu->at42_tarefa  = $cltarefa->at40_sequencial;
			$cltarefausu->at42_usuario = $cltarefa->at40_responsavel;
			$cltarefausu->at42_perc    = 100;
			$cltarefausu->incluir(null);
			if($cltarefausu->erro_status==0) {
				$sqlerro = true;
				$erro_msg = $cltarefausu->erro_msg;
			}
		}

    if ($sqlerro == false) {
			$cltarefaenvol->at45_tarefa  = $cltarefa->at40_sequencial;
			$cltarefaenvol->at45_usuario = $cltarefa->at40_responsavel;
			$cltarefaenvol->at45_perc    = 100;
			$cltarefaenvol->incluir(null);
			if($cltarefaenvol->erro_status==0) {
						$sqlerro = true;
						$erro_msg = $cltarefaenvol->erro_msg;
			}
		}

    if ($sqlerro == false) {
			$cltarefaclientes->at70_tarefa  = $cltarefa->at40_sequencial;
			$cltarefaclientes->at70_cliente = $at01_codcli;
			$cltarefaclientes->incluir(null);
			if($cltarefaclientes->erro_status==0) {
				$sqlerro = true;
				$erro_msg = $cltarefaclientes->erro_msg;
			}
		}

		if($sqlerro==false) {
			$cltarefa_lanc->at36_data    = date("Y", db_getsession("DB_datausu"))."-".
																		 date("m", db_getsession("DB_datausu"))."-".
																		 date("d", db_getsession("DB_datausu"));
			$cltarefa_lanc->at36_hora    = db_hora();
			$cltarefa_lanc->at36_ip      = db_getsession("DB_ip");
			$cltarefa_lanc->at36_tarefa  = $cltarefa->at40_sequencial;
			$cltarefa_lanc->at36_usuario = db_getsession("DB_id_usuario");
			$cltarefa_lanc->at36_tipo    = "I";
			$cltarefa_lanc->incluir(null);
			if($cltarefa_lanc->erro_status==0) {
				$sqlerro = true;
				$erro_msg = $cltarefa_lanc->erro_msg;
			}	  	  
		}
   
		if($sqlerro==false) {
		  $sqlerro = $cltarefa_agenda->gera_agenda($cltarefaparam,$cltarefa,&$erro_msg);
		}
		
		if($sqlerro==false) {
			$cltarefalog->at43_tarefa			= $cltarefa->at40_sequencial;
			$cltarefalog->at43_descr			= $at05_solicitado;
			$cltarefalog->at43_obs				= $at05_feito;
			$cltarefalog->at43_problema		= "false";
			$cltarefalog->at43_avisar			= "0";
			$cltarefalog->at43_progresso	= 100;
			$cltarefalog->at43_usuario		= db_getsession("DB_id_usuario");
			$cltarefalog->at43_diaini			= $cltarefa->at40_diaini;
			$cltarefalog->at43_diafim			= $cltarefa->at40_diafim;
			$cltarefalog->at43_horainidia	= $cltarefa->at40_horainidia;
			$cltarefalog->at43_horafim		= $cltarefa->at40_horafim;
			$cltarefalog->incluir(null);
			if($cltarefalog->erro_status==0) {
				$sqlerro = true;
				$erro_msg = $cltarefalog->erro_msg;
			}	  	  

		  if($sqlerro==false) {
				$cltarefalogsituacao->at48_tarefalog = $cltarefalog->at43_sequencial;
				$cltarefalogsituacao->at48_situacao  = 3;
				$cltarefalogsituacao->incluir(null);
				if($cltarefalogsituacao->erro_status==0) {
					$sqlerro = true;
					$erro_msg = $cltarefalogsituacao->erro_msg;
				}
			}

		}
		
	}
	db_fim_transacao($sqlerro);
	if($sqlerro) {
		db_msgbox($erro_msg);
	}
	if ($sqlerro==false){
		$certo=true;
		//echo "<script>location.href='ate4_atend002.php';</script>";
	}
}
if (isset($alterar)&&$alterar!=""){
	db_inicio_transacao();
	$sqlerro=false;
	if($sqlerro==false){
	$rs_atenditem = $clatenditem->sql_record($clatenditem->sql_query(null,"at05_seq","at05_seq","at05_codatend=$codatend"));
	
	if($clatenditem->numrows > 0) {
		db_fieldsmemory($rs_atenditem,0);
		$clatenditem->at05_seq=$at05_seq;
		$clatenditem->at05_codatend=$codatend;
		$clatenditem->at05_solicitado=$at05_solicitado;
		$clatenditem->at05_tipo=1;
		if(isset($at05_data_dia)&&$at05_data_dia!="") {
			$clatenditem->at05_data=$at05_data_ano."-".$at05_data_mes."-".$at05_data_dia;
		}	
		$clatenditem->alterar($at05_seq);
		$erro_msg=$clatenditem->erro_msg;
		if ($clatenditem->erro_status==0){
			$sqlerro=true;
		}
	}
	else {
		$clatenditem->at05_codatend=$codatend;
		$clatenditem->at05_solicitado=$at05_solicitado;
		$clatenditem->at05_tipo=1;
		if(isset($at05_data_dia)&&$at05_data_dia!="") {
			$clatenditem->at05_data=$at05_data_ano."-".$at05_data_mes."-".$at05_data_dia;
		}	
		$clatenditem->incluir(null,$codatend);
		$erro_msg=$clatenditem->erro_msg;
		if($clatenditem->erro_status==0){
			$sqlerro=true;
		}
	}
	}
	if (isset($modulo)&&$modulo!=""){
		if($sqlerro==false){
			$rs_modulo = $clatenditemmod->sql_record($clatenditemmod->sql_query(null,"at22_sequencial","at22_sequencial","at22_atenditem=$clatenditem->at05_seq"));
			if($clatenditemmod->numrows > 0) {
				db_fieldsmemory($rs_modulo,0);
				$clatenditemmod->at22_sequencial=$at22_sequencial;
				$clatenditemmod->at22_atenditem=$clatenditem->at05_seq;
				$clatenditemmod->at22_codatend=$codatend;
				$clatenditemmod->at22_modulo=$modulo;
				$clatenditemmod->alterar($at22_sequencial);
				if ($clatenditemmod->erro_status==0){
					$sqlerro=true;
					$erro_msg=$clatenditemmod->erro_msg;
				}
				if($sqlerro==false) {
					$rs_modulo = $clatendimentomod->sql_record($clatendimentomod->sql_query($codatend));
				    $clatendimentomod->at08_atend=$codatend; 			
				    $clatendimentomod->at08_modulo=$modulo;
					if($clatendimentomod->numrows > 0) {
					    $clatendimentomod->alterar($codatend);
					}
					else {
					    $clatendimentomod->incluir();
					}
					if ($clatendimentomod->erro_status=="0"){
						$sqlerro=true;
						$erro_msg=$clatendimentomod->erro_msg;
					}
				}
			}
			else {
				$clatenditemmod->at22_atenditem=$clatenditem->at05_seq;
				$clatenditemmod->at22_codatend=$codatend;
				$clatenditemmod->at22_modulo=$modulo;
				$clatenditemmod->incluir(null);
				if ($clatenditemmod->erro_status==0){
					$sqlerro=true;
					$erro_msg=$clatenditemmod->erro_msg;
				}
				if($sqlerro==false) {
				    $clatendimentomod->at08_atend=$codatend; 			
				    $clatendimentomod->at08_modulo=$modulo;
				    $clatendimentomod->incluir();
					if ($clatendimentomod->erro_status=="0"){
						$sqlerro=true;
						$erro_msg=$clatendimentomod->erro_msg;
					}
				}
			} 
		}
	}

	db_fim_transacao($sqlerro);
//	if($sqlerro) {
		db_msgbox($erro_msg);
//	}
}
if (isset($chavepesquisa)&&$chavepesquisa!=""){
	$db_opcao = 1;
	if(isset($opcao)&&$opcao!="") {
		if($opcao=="incluir") {
			$db_opcao = 1;
		}
		if($opcao=="alterar") {
			$db_opcao = 2;
		}
	}

	$result_atendimento= $clatendimento->sql_record($clatendimento->sql_query_file($chavepesquisa));
	if ($clatendimento->numrows>0){
		db_fieldsmemory($result_atendimento,0);
		$codatend=$at02_codatend;
		$clientes=$at02_codcli;
	}
	
	if($db_opcao==2) {
		$rs_atenditem = $clatenditem->sql_record($clatenditem->sql_query(null,"*","","at05_codatend=$at02_codatend"));
		if($clatenditem->numrows > 0) {
			db_fieldsmemory($rs_atenditem,0);
		}
	}
	
	$rs_tecnico= $cltecnico->sql_record($cltecnico->sql_query($chavepesquisa,null,"at03_id_usuario",null,""));
	if ($cltecnico->numrows>0){
		db_fieldsmemory($rs_tecnico,0);
		$tecnico = $at03_id_usuario;
	}
	$rs_modulo= $clatendimentomod->sql_record($clatendimentomod->sql_query($chavepesquisa,"at08_modulo","at08_modulo",null,""));
	if ($clatendimentomod->numrows>0){
		db_fieldsmemory($rs_modulo,0);
	}
	
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
<table align=center>
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC">
    <br><br> 
    <center>
    <?
    imprime_cabecalho(@$codatend);
    if (isset($certo)&&$certo==true){
		$db_opcao = 1;
		if(isset($opcao)&&$opcao!="") {
			if($opcao=="incluir") {
				$db_opcao = 1;
			}
			if($opcao=="alterar") {
				$db_opcao = 2;
			}
		}
      	echo "<tr>
        		<td colspan=2 align=center>
                <h1>Atenditem Nº".$clatenditem->at05_seq."</h1>
             	</td>
      		  </tr>
        ";

				if (isset($incluir)&&$incluir!=""){
					if ($at05_perc == 100) {

						echo "<tr>
								<td colspan=2 align=center>
										<h1>Tarefa criada automaticamente: ".$cltarefa->at40_sequencial."</h1>
									</td>
								</tr>
						";

					}
				}

        $codatenditem=$clatenditem->at05_seq;
       ?>
       <tr>
         <td align = center><input type='button' name='reset' value='Voltar' onclick="location.href='ate4_atend002.php';" >&nbsp;</td>         
         <td align = center><input type='button' name='processa' value='Incluir Tarefa' onclick="location.href='ate1_tarefa001.php?tipo=A&at05_seq=<?=$codatenditem?><?if(isset($tecnico)&&$tecnico!="") { ?>&at40_responsavel=<? echo $tecnico; } ?><?if(isset($at08_modulo)&&$at08_modulo!="") { ?>&at49_modulo=<? echo $at08_modulo; } ?>';" <? if(isset($at05_perc)&&$at05_perc!="") { if($at05_perc == 100) { echo "disabled"; } }?>>&nbsp;</td>
      </tr>
       <?
      }else{
	include("forms/db_frmatend.php");
    }
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if ($db_opcao==11||$db_opcao==22){
	echo "<script>js_pesquisa();</script>";
}
function imprime_cabecalho($codatend=null) {
	global $at02_codatend, $at01_codcli, 
	       $at01_nomecli,  $at04_codtipo, 
	       $at04_descr,    $id_usuario, 
	       $nome,          $at10_usuario,  $at10_nome; 

	if($codatend != null) {
	    $clatendimento = new cl_atendimento;
		$result        = $clatendimento->sql_record($clatendimento->sql_query_inc(null,"at02_codatend,at01_codcli,at01_nomecli,at04_codtipo,at04_descr,id_usuario,nome,at10_usuario,at10_nome","at02_codatend desc","at02_codatend = $codatend"));
		if($clatendimento->numrows > 0) {
			db_fieldsmemory($result,0);
			
   			echo "<tr>
       				<td colspan=3 align=left><b>Atendimento Nº&nbsp;&nbsp;$at02_codatend</b></td>
   		  	  	  </tr>";
   		  	echo "<tr>
       				<td colspan=3 align=left><b>Cliente:&nbsp;&nbsp;$at01_codcli&nbsp;&nbsp;-&nbsp;&nbsp;$at01_nomecli</b></td>
				  </tr>";	  	  
   		  	echo "<tr>
       				<td colspan=3 align=left><b>Solicitado pelo usuário:&nbsp;&nbsp;$at10_usuario&nbsp;&nbsp;-&nbsp;&nbsp;".strtoupper($at10_nome)."</b></td>
				  </tr>";	  	  
   		  	echo "<tr>
       				<td colspan=3 align=left><b>Tipo de atendimento:&nbsp;&nbsp;$at04_codtipo&nbsp;&nbsp;-&nbsp;&nbsp;$at04_descr</b></td>
				  </tr>";
			if(isset($id_usuario)&&$id_usuario!="") {
	   		  	echo "<tr>
	       				<td colspan=3 align=left><b>Técnico:&nbsp;&nbsp;$id_usuario&nbsp;&nbsp;-&nbsp;&nbsp;$nome</b></td>
					  </tr>";
			}	  	  
		}
	}
}
?>