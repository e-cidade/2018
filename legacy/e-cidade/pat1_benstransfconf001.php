<?
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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_benstransfconf_classe.php");
include ("classes/db_benstransfcodigo_classe.php");
include ("classes/db_benstransfdes_classe.php");
include ("classes/db_histbemtrans_classe.php");
include ("classes/db_db_usuarios_classe.php");
include ("classes/db_histbem_classe.php");
include ("classes/db_bens_classe.php");
include ("classes/db_departdiv_classe.php");
include ("classes/db_histbemdiv_classe.php");
include ("classes/db_bensdiv_classe.php");
include("classes/db_histbensocorrencia_classe.php");
db_postmemory($HTTP_POST_VARS);
$cldb_usuarios = new cl_db_usuarios;
$clbenstransfconf = new cl_benstransfconf;
$clbenstransfcodigo = new cl_benstransfcodigo;
$clbenstransfdes = new cl_benstransfdes;
$clhistbemtrans = new cl_histbemtrans;
$clhistbem = new cl_histbem;
$clbens = new cl_bens;
$cldepartdiv = new cl_departdiv;
$clhistbemdiv = new cl_histbemdiv;
$clbensdiv = new cl_bensdiv;
$clhistbemocorrencia = new cl_histbensocorrencia;
$db_opcao = 1;
$db_botao = true;
if (isset ($incluir)) {
	$sqlerro = false;
	db_inicio_transacao();
	if ($sqlerro == false) {
		$clbenstransfconf->incluir($t96_codtran);
		if ($clbenstransfconf->erro_status == 0) {
			$sqlerro = true;
		}
		$erro_msg = $clbenstransfconf->erro_msg;
	}
	if ($sqlerro == false) {
		//rotina que ira incluir na tabela histbem
		$result_dpto = $clbenstransfdes->sql_record($clbenstransfdes->sql_query_file($t96_codtran, "t94_depart"));

		$result = $clbenstransfcodigo->sql_record($clbenstransfcodigo->sql_query_file($t96_codtran));
		$numrows = $clbenstransfcodigo->numrows;

		if ($clbenstransfdes->numrows == 0) {
			$erro_msg = _M("patrimonial.patrimonio.db_frmbenstransfconf.departamento_não_informado");
			$sqlerro = true;
		} else
			if ($clbenstransfcodigo->numrows == 0) {
				$erro_msg = _M("patrimonial.patrimonio.db_frmbenstransfconf.nenhum_bem_informado");
				$sqlerro = true;
			} else {
				db_fieldsmemory($result_dpto, 0);
			}
		if ($sqlerro == false) {
			for ($i = 0; $i < $numrows; $i ++) {
				if ($sqlerro == false) {
					db_fieldsmemory($result, $i);
					$clhistbem->t56_codbem = $t95_codbem;
					$clhistbem->t56_data = $t96_data_ano."-".$t96_data_mes."-".$t96_data_dia;
					$clhistbem->t56_depart = $t94_depart;
					$clhistbem->t56_situac = $t95_situac;
					$clhistbem->t56_histor = $t95_histor;
					$clhistbem->incluir(null);
					$t97_histbem = $clhistbem->t56_histbem;
					$erro_msg = $clhistbem->erro_msg;
					if ($clhistbem->erro_status == 0) {
						$sqlerro = true;
						break;
					}
				}
				//Inseri na tabela histbensocorrencia
			if ($sqlerro == false) {
				$sQueryOrigemDestino = "select dp1.descrdepto as origem, dp2.descrdepto as destino 
																	from benstransf as bt1 
																	inner join db_depart as dp1 on bt1.t93_depart = dp1.coddepto
																	inner join benstransfdes as btd1 on bt1.t93_codtran = btd1.t94_codtran
																	inner join db_depart as dp2 on dp2.coddepto = btd1.t94_depart 
																	where bt1.t93_codtran = $t96_codtran";
				$rsQueryOrigemDestino = db_query($sQueryOrigemDestino);
				if (pg_num_rows($rsQueryOrigemDestino) == 1){
					$rowQueryOrigemDestino = pg_fetch_object($rsQueryOrigemDestino);
					$origem 	= $rowQueryOrigemDestino->origem;
					$destino 	= $rowQueryOrigemDestino->destino;					
				}
				//$t56_codbem	
				//$this->t69_sequencial 			= null; 
		    $clhistbemocorrencia->t69_codbem 					=	$t95_codbem; 
		    $clhistbemocorrencia->t69_ocorrenciasbens	=	1;					// valor vem direto da tabela
		    $clhistbemocorrencia->t69_obs	 						=	substr("Bem transferido de $origem para $destino",0,50);
		    $clhistbemocorrencia->t69_dthist 					= date('Y-m-d',db_getsession('DB_datausu'));
		    $clhistbemocorrencia->t69_hora						= db_hora();
				$clhistbemocorrencia->incluir(null);
				if($clhistbemocorrencia->erro_status==0){
							$sqlerro=true;
							$erro_msg=$clhistbemocorrencia->erro_msg;
						}
			}
				
				
				// altera na tabela bens
				if ($sqlerro == false) {
					$clbens->t52_bem = $t95_codbem;
					$clbens->t52_depart = $t94_depart;
					$clbens->alterar($t95_codbem);
					$erro_msg = $clbens->erro_msg;
					if ($clbens->erro_status == 0) {
						$sqlerro = true;
						break;
					}
				}
				//incluir na tabela histbemtrans
				if ($sqlerro == false) {
					$clhistbemtrans->t97_histbem = $t97_histbem;
					$clhistbemtrans->t97_codtran = $t96_codtran;
					$clhistbemtrans->incluir($t97_histbem, $t96_codtran);
					$erro_msg = $clhistbemtrans->erro_msg;
					if ($clhistbemtrans->erro_status == 0) {
						$sqlerro = true;
						break;
					}
				}
				$info = "t31_divisao_$t95_codbem";
				if ($$info!=""){	
					if ($sqlerro == false) {				
						$clhistbemdiv->t32_histbem=$t97_histbem;
						$clhistbemdiv->t32_divisao=$$info;
						$clhistbemdiv->incluir(null);
						if ($clhistbemdiv->erro_status == 0) {
							$sqlerro = true;
							$erro_msg = $clhistbemdiv->erro_msg;
							break;
						}						
					}
					if ($sqlerro == false) {
						$result_bensdiv=$clbensdiv->sql_record($clbensdiv->sql_query_file($t95_codbem));
						if ($clbensdiv->numrows>0){
							$clbensdiv->excluir($t95_codbem);
							if($clbensdiv->erro_status==0){
								$sqlerro=true;
								$erro_msg=$clbensdiv->erro_msg;
							} 
						}
						if ($sqlerro == false) {
							$clbensdiv->t33_divisao=$$info;
							$clbensdiv->incluir($t95_codbem);
							if($clbensdiv->erro_status==0){
								$sqlerro=true;
								$erro_msg=$clbensdiv->erro_msg;
							} 
						}
					}
				}else{
					if ($sqlerro == false) {
						$result_bensdiv=$clbensdiv->sql_record($clbensdiv->sql_query_file($t95_codbem));
						if ($clbensdiv->numrows>0){
							$clbensdiv->excluir($t95_codbem);
							if($clbensdiv->erro_status==0){
								$sqlerro=true;
								$erro_msg=$clbensdiv->erro_msg;
							} 
						}						
					}
				}				
			}
		}
	}
	//fim
	db_fim_transacao($sqlerro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }

</style>

</head>
<body bgcolor=#CCCCCC >
        <?
          include ("forms/db_frmbenstransfconf.php");
        ?>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if (isset ($incluir)) {
	db_msgbox($erro_msg);
	if ($sqlerro == true) {
		if ($clbenstransfconf->erro_campo != "") {
			echo "<script> document.form1.".$clbenstransfconf->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clbenstransfconf->erro_campo.".focus();</script>";
		};
	};
	if ($sqlerro == false) {

    ?>
    <script>
      if(confirm(_M('patrimonial.patrimonio.db_frmbenstransfconf.deseja_imprimir'))) {
	      
	      jan = window.open('pat2_relbenstransf002.php?t96_codtran='+document.form1.t96_codtran.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	      document.form1.t96_codtran.style.backgroundColor='';
	      jan.moveTo(0,0);
      }
      document.form1.t96_codtran.value='';
      document.form1.nome_transf.value='';
          location.href='pat1_benstransfconf001.php';      
    </script>
    <?php
	}
};
?>