<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libcaixa.php");
require_once("libs/db_utils.php");
require_once("std/db_stdClass.php");
require_once("model/agendaPagamento.model.php");

/**
 * Chamada de função criada para bloquear o acesso ao usuário no menu
 */
db_validarMenuPCASP(db_getsession("DB_itemmenu_acessado", false));

//------------------------------------------------------
//   Arquivos que verificam se o boletim já foi liberado ou naum
include ("classes/db_boletim_classe.php");
$clverficaboletim = new cl_verificaboletim(new cl_boletim);
//------------------------------------------------------

include ("classes/db_slipanul_classe.php");
include ("classes/db_cfautent_classe.php");
include ("classes/db_empageslip_classe.php");
include ("classes/db_empagemov_classe.php");
include ("classes/db_saltes_classe.php");
include ("classes/db_slip_classe.php");
include ("classes/db_corconf_classe.php");

$clslipanul = new cl_slipanul;
$clcorconf = new cl_corconf;
$clcfautent = new cl_cfautent;
$clempageslip = new cl_empageslip;
$clempagemov = new cl_empagemov;
$clsaltes = new cl_saltes;
$clslip = new cl_slip;
//impressora
$clautenticar = new cl_autenticar;
$clrotulo = new rotulocampo;
$clrotulo->label('k17_codigo');
$clrotulo->label('k17_debito');
$clrotulo->label('k17_credito');
$clrotulo->label('k17_hist');
$clrotulo->label('k17_valor');
$clrotulo->label('k17_texto');
$clrotulo->label('k17_numcgm');
$clrotulo->label('k17_dtanu');
$clrotulo->label('k18_motivo');

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);

//impressora
$ip = db_getsession("DB_ip");
$porta = 4444;

$sqltipaut = "select k11_tipautent from cfautent where k11_ipterm = '$ip' and k11_instit = ".db_getsession("DB_instit");
$resulttipaut = pg_exec($sqltipaut);
if (pg_numrows($resulttipaut) > 0) {
	db_fieldsmemory($resulttipaut, 0);
}

$db_botao = true;

if (isset ($pesquisar)) {
	$result = $clslip->sql_record($clslip->sql_query_file($k17_codigo, "k17_dtanu,k17_situacao"));
	if ($clslip->numrows > 0) {
		db_fieldsmemory($result, 0);
		if ($k17_situacao == 4) {
			db_msgbox("Slip está anulado!");
			db_redireciona('cai4_auttransf.php');
		}
	} else {
		db_msgbox("Código de Slip inválido!");
		db_redireciona('cai4_auttransf.php');
	}
}

if (isset ($k17_codigo)) {
	$retorno = $k17_codigo;
}

if (isset ($numslip)) {
	$retorno = $numslip;
}

$altera = false;
$db_erro = "";
if (isset ($retorno) || isset ($autentica) || isset ($estorna)) {
	if ($retorno > 0) {
		$sql = "select slip.*,
		                 z01_numcgm ,
						 k17_situacao,
						 z01_nome  as db_nome,
						 c60_descr as descr_debito,
						 p2.k13_descr as descr_credito,
						 c50_codhist as db_hist,
						 c50_descr as descr_hist
				  from slip
				       left outer join slipnum 		on slip.k17_codigo = slipnum.k17_codigo
				       left outer join cgm 		on slipnum.k17_numcgm = cgm.z01_numcgm
				       left outer join conplanoreduz 	on slip.k17_debito = c61_reduz
				              			       and c61_instit = ".db_getsession('DB_instit')."
		                                       and c61_anousu =".db_getsession("DB_anousu")."
				       left outer join conplano 	on c61_codcon = c60_codcon  and c60_anousu=c61_anousu
				       left outer join saltes p2 	on slip.k17_credito = p2.k13_reduz
				       left outer join conhist 		on slip.k17_hist = conhist.c50_codhist
		          where slip.k17_codigo = $retorno and k17_instit = ".db_getsession('DB_instit')."
							  and k17_situacao <> 4";
		$result = @ pg_query($sql);
		if (pg_numrows($result) > 0) {
			db_fieldsmemory($result, 0);
			$credito = $k17_credito;
			$debito = $k17_debito;
			$numslip = $k17_codigo;
			$altera = true;
		}
	}
}

db_postmemory($HTTP_POST_VARS);
if (isset ($autentica) || isset ($estorna)) {
	if (!isset ($e86_codmov) || ($e86_codmov == '')) {
		$e86_codmov = '0';
	}
	pg_exec("begin");
	if (isset ($autentica)) {
		$sql = "select k17_debito,k17_credito
			        from slip
		                where slip.k17_codigo = $numslip and k17_instit = ".db_getsession('DB_instit');
		$result = pg_exec($sql);
		if (pg_numrows($result) == 0) {
			echo "<script>alert('Slip nao Encontrado.');
				                 location.href='cai4_auttransf.php';
						 </script)";
		}
		db_fieldsmemory($result, 0);
		if ($k17_debito == 0) {
			$sql = "update slip set k17_debito = $debito
					     where k17_codigo = $numslip";
			$result = pg_exec($sql);
		}
		if ($k17_credito == 0) {
			$sql = "update slip set k17_credito = $credito
					     where k17_codigo = $numslip";
			$result = pg_exec($sql);
		}
		//Aqui limpa os campos de data e motivo do estorno se ele for autenticado novamente
		$sql = "update slip set k17_dtestorno = null, k17_motivoestorno = ''
					     where k17_codigo = $numslip";
		$result = pg_exec($sql);

		$sql = "select fc_auttransf($numslip,'".date("Y/m/d", db_getsession("DB_datausu"))."','".$ip."',true,$e86_codmov,".db_getsession("DB_instit").")";
	} else {
		// quando for estorno
		$sql = "select fc_auttransf($numslip,'".date("Y/m/d", db_getsession("DB_datausu"))."','".$ip."',false,$e86_codmov, ".db_getsession("DB_instit").")";
	}

	$result03 = $clslip->sql_record($sql);
	if ($clslip->numrows == 0) {
     	 $db_erro = $clslip->erro_msg;
	} else {
		db_fieldsmemory($result03, 0);
		if (substr($fc_auttransf, 0, 1) != 1) {
			$db_erro = $fc_auttransf;
		}
	}

	if (isset ($estorna)) {
		// quando tiver preenchida a data de anulação
		if ($k17_dtanu_ano!=""  && $k17_dtanu_mes!="" && $k17_dtanu_dia!=""){

			$clslipanul->k18_codigo = $numslip;
			$clslipanul->k18_motivo = $k18_motivo;
			$clslipanul->incluir($numslip);
		//	$clslip->alterar($numslip);
		//	db_msgbox($clslip->erro_msg);
			if ($clslipanul->erro_status == 0) {
				db_msgbox("Erro. Contate suporte!");
				db_redireciona("cai4_auttranf.php");
				exit;
			}

		// 	o estorno não é anulação, somente se estornar completando a data de anulação
			$clslip->k17_dtanu = date("Y-m-d", db_getsession("DB_datausu"));
			$clslip->k17_situacao = 4 ;
			$clslip->k17_codigo = $numslip;

			$clslip->alterar($numslip);
			if ($clslip->erro_status == 0) {
				db_msgbox("Erro. Contate suporte!");
				db_redireciona("cai4_auttranf.php");
				exit;
			}
			$oDaoSlipMov  = db_utils::getDao("slipempagemovslips");
            $oDaoSlipMov->excluir(null, "k108_slip = {$numslip}");

		}

		$clslip->k17_dtestorno = date("Y-m-d", db_getsession("DB_datausu"));
		$clslip->k17_motivoestorno = $k18_motivo;
		$clslip->k17_codigo = $numslip;

		$clslip->alterar($numslip);
		if ($clslip->erro_status == 0) {
			db_msgbox("Erro. Contate suporte!");
			db_redireciona("cai4_auttranf.php");
			exit;
		}

		if ($e81_codmov != "") {

  		  $oAgendaPagamento = new agendaPagamento();
		  if (isset($estornarcheque)) {
		    $oAgendaPagamento->cancelarCheque($e81_codmov);
		  }
  		  $oDaoEmpAgeMov = db_utils::getDao("empagemov");
          $oDaoEmpAgeMov->e81_cancelado = date("Y-m-d",db_getsession("DB_datausu"));
          $oDaoEmpAgeMov->e81_codmov    = $e81_codmov;
          $oDaoEmpAgeMov->alterar($e81_codmov);


  		  $oSlipAgenda = new stdClass();
          $oSlipAgenda->iCodigoSlip = $numslip;
          $oSlipAgenda->nValor      = "$k17_valor";
  		  if ($credito != 0 ) {

  		    $oDaoEmpAgeTipo = db_utils::getDao("empagetipo");
            $sSqlConta      = $oDaoEmpAgeTipo->sql_query_file(null,"e83_codtipo", null,"e83_conta = {$credito}");
            $rsConta        = $oDaoEmpAgeTipo->sql_record($sSqlConta);
            if ($oDaoEmpAgeTipo->numrows > 0 ) {
             $oSlipAgenda->iCodTipo = db_utils::fieldsMemory($rsConta,0)->e83_codtipo;
            }
  		  }
          try {
            $oAgendaPagamento->addMovimentoAgenda(2, $oSlipAgenda);
          }
          catch(Exception $eErro) {

           $sqlerro = true;
           $db_erro = str_replace("\n","\\n",$eErro->getMessage());

          }
        }
	}

	if ($db_erro == "") {
       pg_exec('commit');
		//pg_exec('rollback');
		db_fieldsmemory($result, 0);
	} else {
		pg_exec('rollback');
	}
	//rotina que irá retorna a variavel k17_autent....
	//o paulo deve reduzir esse sql... eu tentei mais deu alguns problemas... 18-11-2004
	$sql = "select slip.*,
	                 z01_numcgm ,
					 z01_nome as db_nome,
					 c60_descr as descr_debito,
					 p2.k13_descr as descr_credito,
					 c50_codhist as db_hist,
					 c50_descr as descr_hist
			  from slip
			       left outer join slipnum 		on slip.k17_codigo = slipnum.k17_codigo
			       left outer join cgm 		on slipnum.k17_numcgm = cgm.z01_numcgm
			       left outer join conplanoreduz 	on slip.k17_debito = c61_reduz
			              			       and c61_instit = ".db_getsession('DB_instit')."
			              			       and c61_anousu= ".db_getsession('DB_anousu')."
			       left outer join conplano 	on c61_codcon = c60_codcon and c61_anousu=c60_anousu
			       left outer join saltes p2 	on slip.k17_credito = p2.k13_reduz
			       left outer join conhist 		on slip.k17_hist = conhist.c50_codhist
	          where slip.k17_codigo = $retorno and k17_instit = ".db_getsession("DB_instit");
	//  echo $sql;
	$result = pg_exec($sql);
	db_fieldsmemory($result, 0);
}

$result_conta1 = $clsaltes->sql_record($clsaltes->sql_query());
/*
"select 0 as c01_reduz,'Nenhuma...' as c01_descr,'' as c01_estrut
                          union
                          select c01_reduz,c01_descr,c01_estrut
 	                      from plano
						  where c01_reduz <> 0 and c01_anousu = ".db_getsession('DB_anousu').
						  " order by c01_estrut";
*/

if (pg_numrows($result_conta1) == 0) {
	echo "<script>parent.alert('Sem Contas Cadastradas no Plano de Contas.');</script>";
	exit;
}
$result_conta2 = $result_conta1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

      <?


if (!isset ($pesquisa) && ($altera == false)) {
?>
  	    <form name="form1" method="post" action="" >
        <center>
        <table width="100%" border='0'>
	    <tr>
	      <td align='center' colspan='2' >
	      <br>
	       <b> <? db_ancora("SLIP","js_slip(true);",1);  ?></b>
	       <?=db_input('k17_codigo',8,'',true,'text',1,"onchange='js_slip(false);'")?>
	       </td>
	    </tr>
          <tr>
	  <!--
          <tr>
           <td align="right" nowrap title="<?=@$Tk17_debito?>"><?=@$Lk17_debito?> </td>
            <td align="left"><input name="debito" type="text" id="debito" size="10"></td>
          </tr>
          <tr>
           <td align="right" nowrap title="<?=@$Tk17_credito?>"><?=@$Lk17_credito?> </td>
            <td align="left"><input name="credito" type="text" id="credito" size="10"></td>
          </tr>
	  -->
          <tr >
            <td colspan="2" align='center'>
	    <input name="pesquisar" type="submit" id="pesquisa" value="Pesquisar" <?=($db_botao == true?"disabled":"")?>></td>
          </tr>
        </table>
        </center>
      </form>
      <script>
        document.form1.k17_codigo.focus();
      </script>
      <?

} else {
	if (isset ($pesquisa)) {
?>
		   <center>
		   <?

		$sql = "select slip.k17_codigo as Codigo,
		               k17_data as Data,
									 k17_debito as Debito,
									 k17_credito as Credito,
									 k17_hist as Hist,
									 k17_texto as Texto,
									 k17_numcgm,
									 z01_nome as db_nome
				      from slip
						       left outer join slipnum on slip.k17_codigo = slipnum.k17_codigo
							   	 left outer join cgm on slipnum.k17_numcgm = cgm.z01_numcgm
				     where k17_autent = 0
						   and k17_instit = ".db_getsession('DB_instit')."
							 and k17_situacao <> 4";
									    ;
		$sql2 = "";
		$sqlwhere = " ";
		if ($numslip != "") {
			$sql2 = " slip.k17_codigo = $numslip";
			db_redireciona("cai4_auttransf.php?".base64_encode("retorno=".$numslip));
		} else {
			if ($debito != "") {
				$sql2 = $sqlwhere." k17_debito = $debito";
				$sqlwhere = " and ";
			}
			if ($credito != "") {
				$sql2 = $sqlwhere." k17_credito = $credito";
				$sqlwhere = " and ";
			}
			if ($dbh_nome != "") {
				$sql2 = $sqlwhere." k17_numcgm = $dbh_nome";
			}
			if ($sql2 != "")
				$sql .= $sql2;
		}
		$sql .= " order by k17_data desc ";
		db_lov($sql, 30, "cai4_auttransf.php");
?>
		   </center>
		   <?


	} else {
		$read_only = "readonly";
		include ("forms/db_frmslipcons.php");
	}
}

db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<script>

//------------SLIP
function js_slip(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slip.php?valida=<>4&funcao_js=parent.js_mostraslip|k17_codigo','Pesquisa',true);
  }else{
    codigo  =  document.form1.k17_codigo.value;
    if(codigo != ''){
       js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slip.php?valida=<>4&pesquisa_chave='+codigo+'&funcao_js=parent.js_mostraslip02','Pesquisa',false);
    }
  }
}
function js_mostraslip(codage){
  db_iframe_slip.hide();
  document.form1.k17_codigo.value =  codage;
  document.form1.pesquisar.disabled = false;
}

function js_mostraslip02(chave,erro){
  if(erro==true){
      document.form1.k17_codigo.focus();
      document.form1.k17_codigo.value = '';
  } else {
      document.form1.pesquisar.disabled = false;
  }
}
</script>
<?


if ($db_erro != "") {
	echo "<script>alert('".$db_erro."')</script>";
	//db_redireciona("cai4_auttransf.php");
}
if ($k11_tipautent == 1) {
	//----------------------------------------------//
	//---rotina verifica se impressora esta ligada--//
	//---------------------------------------------//
	//rotina que verifica se o ip do usuario irá imprimir autenticar ou naum ira fazer nada
	$result99 = $clcfautent->sql_record($clcfautent->sql_query_file(null, "k11_tipautent", '', "k11_ipterm = '".$ip."'"));
	if ($clcfautent->numrows > 0) {
		db_fieldsmemory($result99, 0);
	} else {
		db_msgbox("Cadastre o ip ".$ip." como um caixa.");
		db_redireciona('cai4_auttransf.php');
	}
	//------
	//  if($k11_tipautent != 3 && isset($retorno)){
	//    $clautenticar->verifica($ip,$porta);
	//    if($clautenticar->erro==true){
	//      db_msgbox($clautenticar->erro_msg);
	//      db_redireciona("cai4_auttransf.php");
	//    }
	//  }
	//--final------------------------------------//
}
/////////////////////////////////////////////////////////////////////////////////
//---------------------rotina de autenticação----------------------------------//
/////////////////////////////////////////////////////////////////////////////////

if ((isset ($autentica) || isset ($estorna)) && isset ($fc_auttransf)) {
	$autent = pg_exec("select k11_aut1,k11_aut2 from cfautent where k11_instit = ".db_getsession("DB_instit")." and k11_ipterm = '".$ip."'");
	$aut1 = split(",", pg_result($autent, 0, 0));
	$aut2 = split(",", pg_result($autent, 0, 1));
	$str_aut1 = "";
	$str_aut2 = "";
	if (trim(pg_result($autent, 0, 0)) != "") {
		for ($i = 0; $i < sizeof($aut1); $i ++) {
			$str_aut1 .= chr($aut1[$i]);
		}
	}
	if (trim(pg_result($autent, 0, 1)) != "") {
		for ($i = 0; $i < sizeof($aut2); $i ++) {
			$str_aut2 .= chr($aut2[$i]);
		}
	}
	$str_aut1 = $fc_auttransf;

}
//----------------------------------------------//
//----rotina que imprime na impressora----------//
//----------------------------------------------//
if (isset ($reautentica) || (isset ($autentica) || isset ($estorna)) && isset ($fc_auttransf)) {

	if (isset ($reautentica)) {
		$str_aut1 = $reautentica;
	}
	if ($k11_tipautent == 1) {

		//die($fc_auttransf);

    require_once 'model/impressaoAutenticacao.php';
    $oImpressao = new impressaoAutenticacao($str_aut1);
    $oModelo = $oImpressao->getModelo();
		$oModelo->imprimir();

		// abre o socket da impressora
		/*
    db_msgbox($numslip);
		$fd = fsockopen(db_getsession('DB_ip'),4444);
		// grava a autenticacao
		fputs($fd, chr(15)."$str_aut1".chr(18).chr(10).chr(13));
		// fecha a conecção
		fclose($fd);
    */
	}

	echo "<script>";
	echo "if(parent.confirm('Autenticar o slip ".$numslip." Novamente?')==false){\n";
	echo "  //document.location.href = 'cai4_auttransf.php';\n";
	echo "}else{\n";
	echo "  var obj = document.createElement('input');\n";
	echo "  obj.setAttribute('name','reautentica');\n";
	echo "  obj.setAttribute('type','hidden');\n";
	echo "  obj.setAttribute('value','".$str_aut1."');\n";
	echo "  document.form1.appendChild(obj);\n";
	echo "  document.form1.submit();\n";
	echo "}\n";
	echo "</script>\n";
	//--------------------------------------------------//
}
?>