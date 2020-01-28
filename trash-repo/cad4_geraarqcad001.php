<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require ("fpdf151/scpdf.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_sql.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_ruas_classe.php");
include ("classes/db_bairro_classe.php");
include ("classes/db_cgm_classe.php");
include ("classes/db_ativid_classe.php");
include ("classes/db_caracter_classe.php");
include ("classes/db_cadtipo_classe.php");
include ("classes/db_iptucale_classe.php");
include ("classes/db_iptucalc_classe.php");
include ("classes/db_iptucalv_classe.php");
include ("classes/db_lote_classe.php");
include ("classes/db_face_classe.php");
include ("classes/db_carlote_classe.php");
include ("classes/db_carface_classe.php");
include ("classes/db_carconstr_classe.php");
include ("classes/db_isscalc_classe.php");
require_once('libs/db_utils.php');
require_once("libs/db_libpostgres.php");

$instit = db_getsession("DB_instit");
db_postmemory($HTTP_POST_VARS);
$vt = $HTTP_POST_VARS;
$tam = sizeof($vt);

$clpostgresqlutils = new PostgreSQLUtils;
$clruas            = new cl_ruas;
$clbairro          = new cl_bairro;
$clcgm             = new cl_cgm;
$clativid          = new cl_ativid;
$clcaracter        = new cl_caracter;
$clcadtipo         = new cl_cadtipo;
$cliptucale        = new cl_iptucale;
$cliptucalc        = new cl_iptucalc;
$cliptucalv        = new cl_iptucalv;
$cllote            = new cl_lote;
$clface            = new cl_face;
$clcarlote         = new cl_carlote;
$clcarface         = new cl_carface;
$clcarconstr       = new cl_carconstr;
$clisscalc         = new cl_isscalc;

if (count($clpostgresqlutils->getTableIndexes('debitos')) == 0) {
  
  db_msgbox("Problema nos índices da tabela débitos. Entre em contato com CPD.");
  $db_botao = false;
} else {
  $db_botao = true;
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
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC" >
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<?
if (isset ($gerar)) {
	$descricao_erro = ""; 
	$erro = false;
	$anousu = db_getsession("DB_anousu");
	for ($w = 0; $w < $tam; $w ++) {
		$info = key($vt);
		if ($info == "gerar") {
			continue;
		}
		if ($info == "ruas") {
			$result = $clruas->sql_record($clruas->sql_query_file(null, "*", "j14_codigo"));
			$numrows = $clruas->numrows;
			if ($result == false || $clruas->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe rua cadastrada!";
			}
		}
		if ($info == "bairro") {
			$result = $clbairro->sql_record($clbairro->sql_query_file(null, "*", "j13_codi"));
			$numrows = $clbairro->numrows;
			if ($result == false || $clbairro->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe bairro cadastrado!";
			}
		}
		if ($info == "cgm") {
			$sSql    = $clcgm->sql_query_ender(null, "cgm.*,db_cgmruas.j14_codigo as j14_codigo_cgm,db_cgmbairro.j13_codi as j13_codi_cgm", "z01_numcgm");
			$result  = $clcgm->sql_record();
			$numrows = $clcgm->numrows;
			if ($result == false || $clcgm->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe cgm cadastrado!";
			}
		}
		if ($info == "ativid") {
			$result = $clativid->sql_record($clativid->sql_query_file(null, "*", "q03_ativ"));
			$numrows = $clativid->numrows;
			if ($result == false || $clativid->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe atividade cadastrada!";
			}
		}
		if ($info == "caracter") {
			$result = $clcaracter->sql_record($clcaracter->sql_query_file(null, "*", "j31_codigo"));
			$numrows = $clcaracter->numrows;
			if ($result == false || $clcaracter->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe caracteristica cadastrada!";
			}
		}
		if ($info == "cadtipo") {			
			$result = $clcadtipo->sql_record($clcadtipo->sql_query_file(null, "*", "k03_tipo"));
			$numrows = $clcadtipo->numrows;
			if ($result == false || $clcadtipo->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe tipo de débito cadastrado!";
			}
		}
		if ($info == "matricula") {
			$result = pg_exec("  select j01_matric,
								        j01_idbql,
								        j39_idcons,
										j39_codigo,
										b.j14_nome as rua_iptuconstr,
										j39_numero,
										j39_compl,
										j49_codigo,
										a.j14_nome as rua_testada,
										j15_numero,
										j15_compl
								 from iptubase 
									inner join lote on j01_idbql = j34_idbql 
								    left join testpri on j49_idbql = j01_idbql 
								    left join testadanumero on j01_idbql = j15_idbql 
									left join ruas as a on a.j14_codigo=j49_codigo  
									left join iptuconstr on j01_matric = j39_matric and j39_dtdemo is null 
									left join ruas as b on b.j14_codigo = j39_codigo 
								where j01_baixa is null  
								order by j01_matric, j39_idcons");
			$numrows = pg_numrows($result);
			if ($result == false || $numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe matricula cadastrada!";
			}
		}
		if ($info == "lote") {
			$result = $cllote->sql_record($cllote->sql_query_file(null, "*", "j34_idbql"));
			$numrows = $cllote->numrows;
			if ($result == false || $cllote->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe lote cadastrado!";
			}
		}
		if ($info == "face") {
			$result = $clface->sql_record($clface->sql_query_file(null, "*", "j37_face"));
			$numrows = $clface->numrows;
			if ($result == false || $clface->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe face cadastrada!";
			}
		}
		if ($info == "carlote") {
			$result = $clcarlote->sql_record($clcarlote->sql_query_file(null, "*", "j35_idbql"));
			$numrows = $clcarlote->numrows;
			if ($result == false || $clcarlote->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe caracteristica do lote cadastrada!";
			}
		}
		if ($info == "carface") {
			$result = $clcarface->sql_record($clcarface->sql_query_file(null, "*", "j38_face"));
			$numrows = $clcarface->numrows;
			if ($result == false || $clcarface->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe caracteristica do face cadastrada!";
			}
		}
		if ($info == "carconstr") {
			$result = $clcarconstr->sql_record($clcarconstr->sql_query_file(null, "*", "j48_matric,j48_idcons"));
			$numrows = $clcarconstr->numrows;
			if ($result == false || $clcarconstr->numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe caracteristica da construção cadastrada!";
			}
		}
		if ($info == "inscricao") {
			$result = pg_exec("select * 
							   from issbase
						      inner join tabativ on tabativ.q07_inscr = issbase.q02_inscr
						      inner join cgm on cgm.z01_numcgm = issbase.q02_numcgm
									inner join ativprinc on ativprinc.q88_inscr = tabativ.q07_inscr
                                                        and ativprinc.q88_seq   = tabativ.q07_seq
									left join issbairro on issbase.q02_inscr = 	issbairro.q13_inscr
									left join issruas on issbase.q02_inscr = 	issruas.q02_inscr
								    left join issquant on issquant.q30_inscr = 	issbase.q02_inscr 
                                                      and issquant.q30_anousu = ".db_getsession("DB_anousu")."        																 
							   where q02_dtbaix is null and q07_databx is null and q07_datafi>".date("Y-m-d", db_getsession("DB_datausu"))."  
							   order by issbase.q02_inscr");
			
			$numrows = pg_numrows($result);
			if ($result == false || $numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe inscrição cadastrada!";
			}
		}
		if ($info == "debitos") {
			$result_data=pg_exec("select k22_data as data_ult from debitos where k22_instit = $instit order by k22_data desc limit 1");
			db_fieldsmemory($result_data,0);
			$result=pg_exec("select k22_numcgm,z01_nome,z01_ender,z01_munic,z01_uf,k22_matric,
			                        k22_inscr,k22_tipo,
						k03_descr,k22_numpre,k22_numpar,
						k22_dtvenc,sum(k22_vlrhis) as k22_vlrhis,
						sum(k22_vlrcor) as k22_vlrcor,
						sum(k22_juros) as k22_juros,
						sum(k22_multa) as k22_multa 
					from debitos 
					     inner join arretipo on k00_tipo = k22_tipo 
					     inner join cadtipo on cadtipo.k03_tipo = arretipo.k03_tipo 
					     inner join cgm on z01_numcgm = k22_numcgm
				        where     k22_data   = '$data_ult'
                              and k22_instit = $instit
					group by k22_numcgm,z01_nome,z01_ender,z01_munic,z01_uf,k22_matric,k22_inscr,k22_tipo,k03_descr,
					k22_numpre,k22_numpar,k22_dtvenc ");
			$numrows = pg_numrows($result); 
			if ($result == false || $numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe debito!";
			}
		}
		
			for ($vez = 0; $vez <= 1; $vez ++) {
				if ($vez == 0) {
					$gerar = "layout";
				}
				if ($vez == 1) {
					$gerar = "dados";
				}
				if ($info == "ruas") {
					$nomedoarquivo = "/tmp/".$gerar."_ruas".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "bairro") {
					$nomedoarquivo = "/tmp/".$gerar."_bairro".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "cgm") {
					$nomedoarquivo = "/tmp/".$gerar."_cgm".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "ativid") {
					$nomedoarquivo = "/tmp/".$gerar."_ativid".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "caracter") {
					$nomedoarquivo = "/tmp/".$gerar."_caracter".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "cadtipo") {
					
					$nomedoarquivo = "/tmp/".$gerar."_cadtipo".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "matricula") {
					$nomedoarquivo = "/tmp/".$gerar."_matricula".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "lote") {
					$nomedoarquivo = "/tmp/".$gerar."_lote".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "face") {
					$nomedoarquivo = "/tmp/".$gerar."_face".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "carlote") {
					$nomedoarquivo = "/tmp/".$gerar."_carlote".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "carface") {
					$nomedoarquivo = "/tmp/".$gerar."_carface".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "carconstr") {
					$nomedoarquivo = "/tmp/".$gerar."_carconstr".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "inscricao") {
					$nomedoarquivo = "/tmp/".$gerar."_inscricao".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}
				if ($info == "debitos") {
					$nomedoarquivo = "/tmp/".$gerar."_debitos".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
				}

				$erro = false;
				$descricao_erro = false;
				set_time_limit(0);

				$clabre_arquivo = new cl_abre_arquivo($nomedoarquivo);
				if ($clabre_arquivo->arquivo != false) {
					global $contador;
					$contador = 0;
					for ($i = 0; $i < $numrows; $i ++) {
						db_fieldsmemory($result, $i);
						flush();
						if ($gerar == "dados") {
							if ($info == "ruas") {
								//----------  CAD. RUAS ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($j14_codigo, 7));
								fputs($clabre_arquivo->arquivo, str_pad($j14_nome, 40));
								fputs($clabre_arquivo->arquivo, str_pad($j14_tipo, 1));
								fputs($clabre_arquivo->arquivo, str_pad($j14_rural, 1));
								fputs($clabre_arquivo->arquivo, str_pad($j14_lei, 20));
								fputs($clabre_arquivo->arquivo, str_pad($j14_dtlei, 10));
								fputs($clabre_arquivo->arquivo, str_pad($j14_bairro, 30));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------
							}
							if ($info == "bairro") {
								//----------  CAD. BAIRRO ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($j13_codi, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j13_descr, 40));
								fputs($clabre_arquivo->arquivo, str_pad($j13_codant, 10));
								fputs($clabre_arquivo->arquivo, str_pad($j13_rural, 1));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------					
							}
							if ($info == "cgm") {
								//----------  CAD. CGM ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($z01_numcgm, 10));
								fputs($clabre_arquivo->arquivo, str_pad($z01_cgccpf, 14));
								fputs($clabre_arquivo->arquivo, str_pad($z01_nome, 40));
								fputs($clabre_arquivo->arquivo, str_pad($z01_nomefanta, 80));
								fputs($clabre_arquivo->arquivo, str_pad($z01_nomecomple, 100));
								fputs($clabre_arquivo->arquivo, str_pad($j14_codigo_cgm, 7));
								fputs($clabre_arquivo->arquivo, str_pad($z01_ender, 100));
								fputs($clabre_arquivo->arquivo, str_pad($z01_numero, 6));
								fputs($clabre_arquivo->arquivo, str_pad($z01_compl, 20));
								fputs($clabre_arquivo->arquivo, str_pad($j13_codi_cgm, 4));
								fputs($clabre_arquivo->arquivo, str_pad($z01_bairro, 20));
								fputs($clabre_arquivo->arquivo, str_pad($z01_munic, 20));
								fputs($clabre_arquivo->arquivo, str_pad($z01_uf, 2));
								fputs($clabre_arquivo->arquivo, str_pad($z01_cep, 8));
								fputs($clabre_arquivo->arquivo, str_pad($z01_cxpostal, 20));
								fputs($clabre_arquivo->arquivo, str_pad($z01_cadast, 8));
								fputs($clabre_arquivo->arquivo, str_pad($z01_telef, 12));
								fputs($clabre_arquivo->arquivo, str_pad($z01_ident, 20));
								fputs($clabre_arquivo->arquivo, str_pad($z01_login, 8));
								fputs($clabre_arquivo->arquivo, str_pad($z01_incest, 15));
								fputs($clabre_arquivo->arquivo, str_pad($z01_telcel, 12));
								fputs($clabre_arquivo->arquivo, str_pad($z01_email, 100));
								fputs($clabre_arquivo->arquivo, str_pad($z01_nacion, 4));
								fputs($clabre_arquivo->arquivo, str_pad($z01_estciv, 4));
								fputs($clabre_arquivo->arquivo, str_pad($z01_profis, 40));
								fputs($clabre_arquivo->arquivo, str_pad($z01_tipcre, 4));
								fputs($clabre_arquivo->arquivo, str_pad($z01_fax, 12));
								fputs($clabre_arquivo->arquivo, str_pad($z01_nasc, 12));
								fputs($clabre_arquivo->arquivo, str_pad($z01_pai, 40));
								fputs($clabre_arquivo->arquivo, str_pad($z01_mae, 40));
								fputs($clabre_arquivo->arquivo, str_pad($z01_sexo, 1));
								fputs($clabre_arquivo->arquivo, str_pad($z01_contato, 40));
								fputs($clabre_arquivo->arquivo, str_pad($z01_hora, 5));
								fputs($clabre_arquivo->arquivo, str_pad($z01_cnh, 20));
								fputs($clabre_arquivo->arquivo, str_pad($z01_categoria, 2));
								fputs($clabre_arquivo->arquivo, str_pad($z01_dtemissao, 10));
								fputs($clabre_arquivo->arquivo, str_pad($z01_dthabilitacao, 10));
								fputs($clabre_arquivo->arquivo, str_pad($z01_dtvencimento, 10));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------					
							}
							if ($info == "ativid") {
								//----------  CAD. ATIVIDADE ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($q03_ativ, 8));
								fputs($clabre_arquivo->arquivo, str_pad($q03_descr, 40));
								fputs($clabre_arquivo->arquivo, str_pad($q03_atmemo, 4));
								fputs($clabre_arquivo->arquivo, str_pad($q03_limite, 10));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------
							}
							if ($info == "caracter") {
								//----------  CAD. CARACTERISTICAS ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($j31_codigo, 10));
								fputs($clabre_arquivo->arquivo, str_pad($j31_descr, 40));
								fputs($clabre_arquivo->arquivo, str_pad($j31_grupo, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j31_pontos, 4));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------
							}
							if ($info == "cadtipo") {
								//----------  CAD. TIPOS DE DÉBITOS ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad(@ $k03_tipo, 3));
								fputs($clabre_arquivo->arquivo, str_pad(@ $k03_descr, 40));
								fputs($clabre_arquivo->arquivo, str_pad(@ $k03_parcano, 1));
								fputs($clabre_arquivo->arquivo, str_pad(@ $k03_parcelamento, 1));
								fputs($clabre_arquivo->arquivo, str_pad(@ $k03_permparc, 1));
								fputs($clabre_arquivo->arquivo, "\n");
								//---------------------------------------------------------------------------------------
							}
							if ($info == "matricula") {
								//----------  CAD. MATRICULAS ------------------------------------------------------

								fputs($clabre_arquivo->arquivo, str_pad(@ $j01_matric, 10));
								if ($j39_codigo != "") {
									fputs($clabre_arquivo->arquivo, str_pad(@ $j39_codigo, 10));
									fputs($clabre_arquivo->arquivo, str_pad(@ $rua_iptuconstr, 40));
									fputs($clabre_arquivo->arquivo, str_pad(@ $j39_numero, 10));
									fputs($clabre_arquivo->arquivo, str_pad(@ $j39_compl, 50));
								} else {
									fputs($clabre_arquivo->arquivo, str_pad(@ $j49_codigo, 10));
									fputs($clabre_arquivo->arquivo, str_pad(@ $rua_testada, 40));
									fputs($clabre_arquivo->arquivo, str_pad(@ $j15_numero, 10));
									fputs($clabre_arquivo->arquivo, str_pad(@ $j15_compl, 50));
								}
								fputs($clabre_arquivo->arquivo, str_pad(@ $j34_area, 15));
								fputs($clabre_arquivo->arquivo, str_pad(@ $j34_totcon, 15));
								if ($j39_codigo != "") {
									$result_vlrvenal_con = $cliptucale->sql_record($cliptucale->sql_query_file(db_getsession("DB_anousu"), $j01_matric, $j39_idcons, "sum(j22_valor) as vlr_venal_con"));
									if ($cliptucale->numrows > 0) {
										db_fieldsmemory($result_vlrvenal_con, 0);
										fputs($clabre_arquivo->arquivo, str_pad(@ $vlr_venal_con, 15));
									} else {
										fputs($clabre_arquivo->arquivo, str_pad("00", 15));
									}
								} else {
									fputs($clabre_arquivo->arquivo, str_pad("00", 15));
								}
								$result_vlrvenal_ter = $cliptucalc->sql_record($cliptucalc->sql_query_file(db_getsession("DB_anousu"), $j01_matric, "j23_vlrter"));
								if ($cliptucalc->numrows > 0) {
									db_fieldsmemory($result_vlrvenal_ter, 0);
									fputs($clabre_arquivo->arquivo, str_pad(@ $j23_vlrter, 15));
								} else {
									fputs($clabre_arquivo->arquivo, str_pad("00", 15));
								}
								$result_vlriptu = $cliptucalv->sql_record($cliptucalv->sql_query_hist(null, "sum(j21_valor) as vlr_iptu", null, "j21_anousu=".db_getsession("DB_anousu")." and j21_matric=$j01_matric and j21_codhis=1"));
								if ($cliptucalv->numrows > 0) {
									db_fieldsmemory($result_vlriptu, 0);
									fputs($clabre_arquivo->arquivo, str_pad(@ $vlr_iptu, 15));
								} else {
									fputs($clabre_arquivo->arquivo, str_pad("00", 15));
								}
								$result_vlrtaxa = $cliptucalv->sql_record($cliptucalv->sql_query_hist(null, "sum(j21_valor) as vlr_taxa", null, "j21_anousu=".db_getsession("DB_anousu")." and j21_matric=$j01_matric and j21_codhis<>1"));
								if ($cliptucalv->numrows > 0) {
									db_fieldsmemory($result_vlrtaxa, 0);
									fputs($clabre_arquivo->arquivo, str_pad(@ $vlr_taxa, 15));
								} else {
									fputs($clabre_arquivo->arquivo, str_pad("00", 15));
								}

								fputs($clabre_arquivo->arquivo, "\n");
								//---------------------------------------------------------------------------------------														
							}
							if ($info == "lote") {
								//----------  CAD. LOTE ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($j34_idbql, 6));
								fputs($clabre_arquivo->arquivo, str_pad($j34_setor, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j34_quadra, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j34_lote, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j34_area, 15));
								fputs($clabre_arquivo->arquivo, str_pad($j34_bairro, 10));
								fputs($clabre_arquivo->arquivo, str_pad($j34_areal, 15));
								fputs($clabre_arquivo->arquivo, str_pad($j34_totcon, 15));
								fputs($clabre_arquivo->arquivo, str_pad($j34_zona, 5));
								fputs($clabre_arquivo->arquivo, str_pad($j34_quamat, 10));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------
							}
							if ($info == "face") {
								//----------  CAD. FACE ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($j37_face, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j37_setor, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j37_quadra, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j37_codigo, 10));
								fputs($clabre_arquivo->arquivo, str_pad($j37_lado, 1));
								fputs($clabre_arquivo->arquivo, str_pad($j37_valor, 15));
								fputs($clabre_arquivo->arquivo, str_pad($j37_exten, 15));
								fputs($clabre_arquivo->arquivo, str_pad($j37_profr, 15));
								fputs($clabre_arquivo->arquivo, str_pad($j37_outros, 40));
								fputs($clabre_arquivo->arquivo, str_pad($j37_vlcons, 15));
								fputs($clabre_arquivo->arquivo, str_pad($j37_zona, 5));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------
							}
							if ($info == "carlote") {
								//----------  CAD. CARLOTE ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($j35_idbql, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j35_caract, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j39_dtlanc, 10));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------
							}
							if ($info == "carface") {
								//----------  CAD. CARFACE  ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($j38_face, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j38_caract, 4));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------
							}
							if ($info == "carconstr") {
								//----------  CAD. CARCONSTR ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($j48_matric, 10));
								fputs($clabre_arquivo->arquivo, str_pad($j48_idcons, 4));
								fputs($clabre_arquivo->arquivo, str_pad($j48_caract, 4));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------
							}
							if ($info == "inscricao") {
								//----------  CAD. INSCRICAO ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($q02_inscr, 8));
								fputs($clabre_arquivo->arquivo, str_pad($q02_numcgm, 10));
								fputs($clabre_arquivo->arquivo, str_pad($z01_nomefanta, 50));
								fputs($clabre_arquivo->arquivo, str_pad($q02_capit, 15));
								fputs($clabre_arquivo->arquivo, str_pad($q02_obs, 1));
								fputs($clabre_arquivo->arquivo, str_pad($q02_inscmu, 14));
								fputs($clabre_arquivo->arquivo, str_pad($q02_tiplic, 2));
								fputs($clabre_arquivo->arquivo, str_pad($q02_regjuc, 14));
								fputs($clabre_arquivo->arquivo, str_pad($q02_dtcada, 8));
								fputs($clabre_arquivo->arquivo, str_pad($q02_memo, 60));
								fputs($clabre_arquivo->arquivo, str_pad($q02_dtinic, 8));
								fputs($clabre_arquivo->arquivo, str_pad($q02_dtbaix, 8));
								fputs($clabre_arquivo->arquivo, str_pad($q02_dtjunta, 10));
								fputs($clabre_arquivo->arquivo, str_pad($q02_ultalt, 10));								
								fputs($clabre_arquivo->arquivo, str_pad($q02_dtalt, 10));
								fputs($clabre_arquivo->arquivo, str_pad($j14_codigo, 7));
								fputs($clabre_arquivo->arquivo, str_pad($q02_numero, 10));
								fputs($clabre_arquivo->arquivo, str_pad($q02_compl, 40));
								fputs($clabre_arquivo->arquivo, str_pad($q02_cxpost, 20));
								fputs($clabre_arquivo->arquivo, str_pad($q02_cep, 8));
								fputs($clabre_arquivo->arquivo, str_pad($q13_bairro, 4));
								fputs($clabre_arquivo->arquivo, str_pad($q07_ativ, 10));
								if ($q07_perman == 't') {
									fputs($clabre_arquivo->arquivo, str_pad("Permanente", 12));
								} else {
									fputs($clabre_arquivo->arquivo, str_pad("Provisorio", 12));
								}
								fputs($clabre_arquivo->arquivo, str_pad($q30_quant, 15));
								fputs($clabre_arquivo->arquivo, str_pad($q30_area, 15));
								$result_vlrfixo = $clisscalc->sql_record($clisscalc->sql_query_file(db_getsession("DB_anousu"), $q02_inscr,2,null,null,"sum(q01_valor) as vlrfixo"));
								if ($clisscalc->numrows > 0) {
									db_fieldsmemory($result_vlrfixo, 0);
									fputs($clabre_arquivo->arquivo, str_pad(@ $vlrfixo, 15));
								} else {
									fputs($clabre_arquivo->arquivo, str_pad("00", 15));
								}
								$result_tipocal=pg_exec("select  q85_descr, q85_var from tabativ inner join ativtipo on q07_ativ = q80_ativ inner join tipcalc on q81_codigo = q80_tipcal inner join cadcalc on q81_cadcalc = q85_codigo where q07_inscr = $q02_inscr and q81_tipo = 1 and q07_databx is null and q07_datafi>".date("Y-m-d", db_getsession("DB_datausu")));
								if (pg_numrows($result_tipocal)>0){
									db_fieldsmemory($result_tipocal,0);
									fputs($clabre_arquivo->arquivo, str_pad($q85_descr, 40));
									if ($q85_var=='t'){
										$var="Sim";
									}else{
										$var="Não";
									}
									fputs($clabre_arquivo->arquivo, str_pad($var, 5));
								}else{ 
                     				fputs($clabre_arquivo->arquivo, str_pad("", 40));
									fputs($clabre_arquivo->arquivo, str_pad("", 5));
								}
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------
							}
							if ($info == "debitos") {
								//----------  CAD. DEBITOS ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, str_pad($k22_numcgm, 10));
								fputs($clabre_arquivo->arquivo, str_pad($z01_nome  , 60));
								fputs($clabre_arquivo->arquivo, str_pad($z01_ender , 60));
								fputs($clabre_arquivo->arquivo, str_pad($z01_munic , 20));
								fputs($clabre_arquivo->arquivo, str_pad($z01_uf    ,  2));
								fputs($clabre_arquivo->arquivo, str_pad($k22_matric, 10));
								fputs($clabre_arquivo->arquivo, str_pad($k22_inscr , 10));
								fputs($clabre_arquivo->arquivo, str_pad($k22_tipo  , 10));
								fputs($clabre_arquivo->arquivo, str_pad($k03_descr , 40));
								fputs($clabre_arquivo->arquivo, str_pad($k22_numpre, 10));
								fputs($clabre_arquivo->arquivo, str_pad($k22_numpar, 10));
								fputs($clabre_arquivo->arquivo, str_pad($k22_dtvenc, 10));
								fputs($clabre_arquivo->arquivo, str_pad($k22_vlrhis, 15));
								fputs($clabre_arquivo->arquivo, str_pad($k22_vlrcor, 15));
								fputs($clabre_arquivo->arquivo, str_pad($k22_juros , 15));
								fputs($clabre_arquivo->arquivo, str_pad($k22_multa , 15));
								fputs($clabre_arquivo->arquivo, "\n");
								//----------------------------------------------------------------------------------------
							}

						} else {
							if ($info == "ruas") {
								//----------  CAD. RUAS ------------------------------------------------------					
								fputs($clabre_arquivo->arquivo, db_contador("j14_codigo", "Código do logradouro cadastrado no sistema", $contador, 7));
								fputs($clabre_arquivo->arquivo, db_contador("j14_nome", "Descricao do logradouro do municipio", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("j14_tipo", "Tipo do logradouro /R-Rua/A-Avenida/T-Travessa/O-Outros/", $contador, 1));
								fputs($clabre_arquivo->arquivo, db_contador("j14_rural", "Indica se a rua cadastrada é do meio Rural", $contador, 1));
								fputs($clabre_arquivo->arquivo, db_contador("j14_lei", "Lei", $contador, 20));
								fputs($clabre_arquivo->arquivo, db_contador("j14_dtlei", "Data Lei", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("j14_bairro", "Bairro", $contador, 30));
								//----------------------------------------------------------------------------------------										
							}
							if ($info == "bairro") {
								//----------  CAD. BAIRRO ------------------------------------------------------					
								fputs($clabre_arquivo->arquivo, db_contador("j13_codi", "Código do bairro", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j13_descr", "Descrição do bairro", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("j13_codant", "Código anterior do bairro ( Utilizado para conversão de sistemas )", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("j13_rural", "Indica se o bairro é do meio Rural", $contador, 1));
								//----------------------------------------------------------------------------------------					
							}
							if ($info == "cgm") {
								//-----------------  CAD. CGM ------------------------------------------------------					
								fputs($clabre_arquivo->arquivo, db_contador("z01_numcgm 	   ", "Numero de Identificação do Contribuinte ou Empresa no Cadastro geral do Município", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("z01_cgccpf 	   ", "Código do CNPJ quando empresa ou Código do CPF quando pessoa física", $contador, 14));
								fputs($clabre_arquivo->arquivo, db_contador("z01_nome", "Nome da pessoa ou Razao Social se for Empresa", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("z01_nomefanta", "Nome Fantasia da empresa", $contador, 80));
								fputs($clabre_arquivo->arquivo, db_contador("z01_nomecomple", "Nome Completo", $contador, 100));
								fputs($clabre_arquivo->arquivo, db_contador("j14_codigo_cgm", "Quando CGM do municipio, codigo da rua", $contador, 7));
								fputs($clabre_arquivo->arquivo, db_contador("z01_ender", "Endereço	", $contador, 100));
								fputs($clabre_arquivo->arquivo, db_contador("z01_numero", "Numero do endereco", $contador, 6));
								fputs($clabre_arquivo->arquivo, db_contador("z01_compl", "Complemento do numero do endereco", $contador, 20));
								fputs($clabre_arquivo->arquivo, db_contador("j13_codi_cgm", "Quando CGM do municipio, codigo da bairro", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("z01_bairro", "Bairro", $contador, 20));
								fputs($clabre_arquivo->arquivo, db_contador("z01_munic", "Município", $contador, 20));
								fputs($clabre_arquivo->arquivo, db_contador("z01_uf", "Unidade Federativa (estado)", $contador, 2));
								fputs($clabre_arquivo->arquivo, db_contador("z01_cep", "CEP", $contador, 8));
								fputs($clabre_arquivo->arquivo, db_contador("z01_cxpostal", "Caixa postal do contribuinte cadastrado", $contador, 20));
								fputs($clabre_arquivo->arquivo, db_contador("z01_cadast", "Dia em que este registro foi cadastrado", $contador, 8));
								fputs($clabre_arquivo->arquivo, db_contador("z01_telef", "Telefone", $contador, 12));
								fputs($clabre_arquivo->arquivo, db_contador("z01_ident", "Identidade (pessoa fisica)", $contador, 20));
								fputs($clabre_arquivo->arquivo, db_contador("z01_login", "Login do usuario que cadastrou este registro", $contador, 8));
								fputs($clabre_arquivo->arquivo, db_contador("z01_incest", "Inscricao Estadual (pessoa juridica)	", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("z01_telcel", "Telefone Celular", $contador, 12));
								fputs($clabre_arquivo->arquivo, db_contador("z01_email", "email", $contador, 100));
								fputs($clabre_arquivo->arquivo, db_contador("z01_nacion", "Codigo da nacionalidade", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("z01_estciv", "Estado civil (pessoa fisica)", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("z01_profis", "Profissao (pessoa fisica)", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("z01_tipcre", "1=administracao publica 2=privada", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("z01_fax", "Fax", $contador, 12));
								fputs($clabre_arquivo->arquivo, db_contador("z01_nasc", "Data de Nascimento", $contador, 12));
								fputs($clabre_arquivo->arquivo, db_contador("z01_pai", "Pai", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("z01_mae", "Mãe", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("z01_sexo", "Sexo", $contador, 1));
								fputs($clabre_arquivo->arquivo, db_contador("z01_contato", "Contato,nome do responsavel.", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("z01_cnh", "Número da carteira de motorista	", $contador, 5));
								fputs($clabre_arquivo->arquivo, db_contador("z01_categoria", "Categoria da carteira de motorista", $contador, 20));
								fputs($clabre_arquivo->arquivo, db_contador("z01_dtemissao", "Data emissao da carteira de motorista	", $contador, 2));
								fputs($clabre_arquivo->arquivo, db_contador("z01_dthabilitacao", "Data da primeira CNH", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("z01_dtvencimento","Data Vencimento CNH", $contador, 10));
								//----------------------------------------------------------------------------------------
							}
							if ($info == "ativid") {
								//----------  CAD. ATIVIDADE ------------------------------------------------------					
								fputs($clabre_arquivo->arquivo, db_contador("q03_ativ  ", "Codigo da atividade               ", $contador, 8));
								fputs($clabre_arquivo->arquivo, db_contador("q03_descr ", "Descricao da atividade            ", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("q03_atmemo", "Observacoes referentes a atividade", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("q03_limite", "Data Limite                       ", $contador, 10));
								//----------------------------------------------------------------------------------------
							}
							if ($info == "caracter") {
								//----------  CAD. CARACTERISTICAS ------------------------------------------------------					
								fputs($clabre_arquivo->arquivo, db_contador("j31_codigo", "Codigo da caracteristica                                 ", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("j31_descr ", "Descricao da caracteristica                              ", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("j31_grupo ", "Codigo do grupo da caracteristica                        ", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j31_pontos", "Numero de pontos a serem somados para esta característica", $contador, 4));
								//----------------------------------------------------------------------------------------
							}
							if ($info == "cadtipo") {
								//----------  CAD. TIPO DE DÉBITOS------------------------------------------------------					
								fputs($clabre_arquivo->arquivo, db_contador("k03_tipo        ", "Tipo de débito                                                                                                                                                                                                                                                                                 ", $contador, 3));
								fputs($clabre_arquivo->arquivo, db_contador("k03_descr       ", "Descrição do tipo de débito                                                                                                                                                                                                                                                                    ", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("k03_parcano     ", "Se parcela débito somente no ano atual ou nao. Se true, significa que as parcelas só poderão ser feitos no ano atual. Se false pode ser parcelado sem limite.                                                                                                                                  ", $contador, 1));
								fputs($clabre_arquivo->arquivo, db_contador("k03_parcelamento", "Se tipo de débito é parcelamento ou não. Se true, significa que o tipo de débito é parcelamento e o mesmo não poderá ser reparcelado junto com outro numpre. Se não for parcelamento, no caso de divida ativa nao parcelada, ou melhorias, dai sim pode ser parcelado junto com outros numpres.", $contador, 1));
								fputs($clabre_arquivo->arquivo, db_contador("k03_permparc    ", "Se true, permite parcelar este tipo de débito, se false, nao permite que parcele este tipo de débito.                                                                                                                                                                                          ", $contador, 1));
								//----------------------------------------------------------------------------------------						
							}
							if ($info == "matricula") {
								//----------  CAD. MATRICULAS------------------------------------------------------					
								fputs($clabre_arquivo->arquivo, db_contador("j01_matric", "Codigo da matrícula do imovel para identificar o proprietário de um determinadolote.", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("j14_codigo", "Código do logradouro cadastrado no sistema", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("j14_nome", "Descricao do logradouro do municipio", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("j39_numero", "Numero imovel", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("j39_compl", "Complemento imovel", $contador, 50));
								fputs($clabre_arquivo->arquivo, db_contador("j34_area", "Área do lote em metros quadrados", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j34_totcon", "Total construído no lote", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j22_valor", "Valor venal da construção", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j23_vlrter", "Valor venal do terreno", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j21_valor_iptu", "Valor do IPTU", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j21_valor_taxa", "Valor das taxas", $contador, 15));
								//----------------------------------------------------------------------------------------
							}
							if ($info == "lote") {
								//----------  CAD. LOTE ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, db_contador("j34_idbql ", "Codigo de identificacao do lote                     ", $contador, 6));
								fputs($clabre_arquivo->arquivo, db_contador("j34_setor ", "Codigo do Setor                                     ", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j34_quadra", "Identificacao da Quadra                             ", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j34_lote  ", "Identificacao do Lote                               ", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j34_area  ", "Área do lote em metros quadrados                    ", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j34_bairro", "Codigo do bairro a que pertence o lote              ", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("j34_areal ", "Area medida pela prefeitura. Diferente da escritura.", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j34_totcon", "Total construído no lote                            ", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j34_zona  ", "Zona Fiscal                                         ", $contador, 5));
								fputs($clabre_arquivo->arquivo, db_contador("j34_quamat", "Numero de matrículas cadastradas para este lote.    ", $contador, 10));
								//----------------------------------------------------------------------------------------
							}
							if ($info == "face") {
								//----------  CAD. FACE ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, db_contador("j37_face  ", "Codigo da face de quadra que identifica um setor, quadra e rua                              ", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j37_setor ", "Codigo do setor                                                                             ", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j37_quadra", "Codigo da Quadra                                                                            ", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j37_codigo", "Codigo da rua para identificacao da face                                                    ", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("j37_lado  ", "Identificacao do lado da face em relacao a numeracao I - Impar P - Par                      ", $contador, 1));
								fputs($clabre_arquivo->arquivo, db_contador("j37_valor ", "Valor do m2 da face de quadra para calculo do iptu                                          ", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j37_exten ", "Extensao da face de quadra em metros lineares                                               ", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j37_profr ", "Valor em metros lineares da profundidade da quadra                                          ", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j37_outros", "Identificar dados nao constantes nos arquivos.Utilizado normalmente em conversao de sistemas", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("j37_vlcons", "Valor do m2 a ser calculado no iptu para as construcoes de uma determinada face de quadra   ", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("j37_zona  ", "Zona fiscal                                                                                 ", $contador, 5));
								//----------------------------------------------------------------------------------------
							}
							if ($info == "carlote") {
								//----------  CAD. CARLOTE ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, db_contador("j35_idbql ", "Codigo de Identificacao do Lote", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j35_caract", "Codigo da caracteristica       ", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j39_dtlanc", "Data de lancamento 		   ", $contador, 10));
								//----------------------------------------------------------------------------------------	
							}
							if ($info == "carface") {
								//----------  CAD. CARFACE ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, db_contador("j38_face", "Codigo da Face de Quadra", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j38_caract", "Codigo da caracteristica", $contador, 4));
								//----------------------------------------------------------------------------------------
							}
							if ($info == "carconstr") {
								//----------  CAD. CARCONSTR ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, db_contador("j48_matric", "Codigo da matricula do imovel", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("j48_idcons", "Codigo da construcao         ", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("j48_caract", "Codigo da caracteristica     ", $contador, 4));
								//----------------------------------------------------------------------------------------	
							}
							if ($info == "inscricao") {
								//----------  CAD. INSCRICAO ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, db_contador("q02_inscr  ", "Inscricao Municipal no cadastro de alvará                                                                                                             ", $contador, 8));
								fputs($clabre_arquivo->arquivo, db_contador("q02_numcgm ", "Numero do CGM para interligacao do cadastro do issqn com o cadastro geral do municipio                                                                ", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("z01_nomefanta  ", "Nome fantasia da empresa                                                                                                                              ", $contador, 50));
								fputs($clabre_arquivo->arquivo, db_contador("q02_capit  ", "Capital social da empresa                                                                                                                             ", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("q02_obs    ", "Observacoes gerais sobre a inscricao                                                                                                                  ", $contador, 1));
								fputs($clabre_arquivo->arquivo, db_contador("q02_inscmu ", "Inscricao municipal da inscricao                                                                                                                      ", $contador, 14));
								fputs($clabre_arquivo->arquivo, db_contador("q02_tiplic ", "Tipo de licenca da inscricao                                                                                                                          ", $contador, 2));
								fputs($clabre_arquivo->arquivo, db_contador("q02_regjuc ", "Registro na Junta Comercial da inscricao                                                                                                              ", $contador, 14));
								fputs($clabre_arquivo->arquivo, db_contador("q02_dtcada ", "dia em que a inscricao foi cadastrada                                                                                                                 ", $contador, 8));
								fputs($clabre_arquivo->arquivo, db_contador("q02_memo   ", "Espaco para texto referente a inscricao para utilizacao na impressao do alvara                                                                        ", $contador, 60));
								fputs($clabre_arquivo->arquivo, db_contador("q02_dtinic ", "data em que a primeira atividade daquela inscricao foi lancada. Nao é utilizada para fins de calculo, da data para calculo é a da atividade lancada.  ", $contador, 8));
								fputs($clabre_arquivo->arquivo, db_contador("q02_dtbaix ", "Caso todas as atividades desta inscricoes sejam baixadas, a data da ultima baixa é colocada aqui, para facilitar na geracao de relatorios e consultas.", $contador, 8));								
								fputs($clabre_arquivo->arquivo, db_contador("q02_dtjunta", "Data da junta comercial                                                                                                                               ", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("q02_ultalt ", "Ultima alteracao                                                                                                                                      ", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("q02_dtalt  ", "Data da última alteração                                                                                                                              ", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("q02_dtalt  ", "Data da última alteração                                                                                                                              ", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("j14_codigo", "Código do logradouro cadastrado no sistema", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("q02_numero ", "numero do estabelecimento                                                                                                                             ", $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("q02_compl  ", "complemento do numero                                                                                                                                 ", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("q02_cxpost ", "caixa postal																																		  ", $contador, 20));
								fputs($clabre_arquivo->arquivo, db_contador("q02_cep    ", "CEP																																					  ", $contador, 8));
								fputs($clabre_arquivo->arquivo, db_contador("q13_bairro ", "Bairro da inscrição																																	  ", $contador, 4));
								fputs($clabre_arquivo->arquivo, db_contador("q07_ativ   ", "Codigo da atividade principal																														  ",  $contador, 10));
								fputs($clabre_arquivo->arquivo, db_contador("q07_tipo   ", "Atividade Permanente/Provisoria																												         ", $contador, 12));
								fputs($clabre_arquivo->arquivo, db_contador("q30_quant  ", "Informe nesse campo o número de funcionários da empresa. ", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("q30_area   ", "Informe nesse campo a área do prédio ocupado pela empresa.", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("q01_valor   ", "Valor calculado de issqn fixo", $contador, 15));
								fputs($clabre_arquivo->arquivo, db_contador("q85_descr   ", "Tipo de calculo", $contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("q85_var   ", "ISSQN Variavel", $contador, 5));
								//----------------------------------------------------------------------------------------
							}
							if ($info == "debitos") {
								//----------  CAD. DEBITOS ------------------------------------------------------
								fputs($clabre_arquivo->arquivo, db_contador("k22_numcgm", "CGM                     ", $contador,10));
								fputs($clabre_arquivo->arquivo, db_contador("z01_nome  ", "NOME                    ", $contador,60));
								fputs($clabre_arquivo->arquivo, db_contador("z01_ender ", "ENDERECO                ", $contador,60));
								fputs($clabre_arquivo->arquivo, db_contador("z01_munic ", "MUNICIPIO               ", $contador,20));
								fputs($clabre_arquivo->arquivo, db_contador("z01_uf    ", "UF                      ", $contador,2));
								fputs($clabre_arquivo->arquivo, db_contador("k22_matric", "Matrícula               ", $contador,10));
								fputs($clabre_arquivo->arquivo, db_contador("k22_inscr ", "Inscrição               ", $contador,10));
								fputs($clabre_arquivo->arquivo, db_contador("k22_tipo  ", "Tipo de débito          ", $contador,10));
								fputs($clabre_arquivo->arquivo, db_contador("k03_descr  ","Descrição do tipo de débito",$contador, 40));
								fputs($clabre_arquivo->arquivo, db_contador("k22_numpre", "Carne                   ", $contador,10));
								fputs($clabre_arquivo->arquivo, db_contador("k22_numpar", "Parcela                 ", $contador,10));
								fputs($clabre_arquivo->arquivo, db_contador("k22_dtvenc", "Vencimento              ", $contador,10));
								fputs($clabre_arquivo->arquivo, db_contador("k22_vlrhis", "Valor histórico/original", $contador,15));
								fputs($clabre_arquivo->arquivo, db_contador("k22_vlrcor", "Valor corrigido         ", $contador,15));
								fputs($clabre_arquivo->arquivo, db_contador("k22_juros ", "Juros                   ", $contador,15));
								fputs($clabre_arquivo->arquivo, db_contador("k22_multa ", "Multa                   ", $contador,15));								
								//----------------------------------------------------------------------------------------
							}
							break;

						}
						$erro = true;
						$descricao_erro = "Informações $info gerados com sucesso no diretorio /tmp do servidor.";
						/*
						if (isset ($local)) {
							echo "<script>jan = window.open('db_download.php?arquivo=".$clabre_arquivo->nomearq."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
												    jan.moveTo(0,0);</script>";
						}
						*/
					}

					fclose($clabre_arquivo->arquivo);

				} else {
					$erro = true;
					$descricao_erro = "Erro ao Criar arquivo: $arquivo";
				}
			}
		
		if (@ $erro == true) {
			echo "<script>alert('$descricao_erro');</script>";
		}		
		next($vt);
	}
}
function db_contador($apelido, $expressao, $contador, $valor) {
	global $contador;
	//  echo "x: $contador - valor: $valor<br>";
	$contadorant = $contador +1;
	$contador += $valor;
	return str_pad($apelido, 30)." - ".str_pad($expressao, 80)." - ".str_pad($valor, 4, "0", STR_PAD_LEFT)." - ".str_pad($contadorant, 4, "0", STR_PAD_LEFT)." - ".str_pad($contador, 4, "0", STR_PAD_LEFT)."\n";
}
?>
<form name="form1" action="" method="post" >
<table align="center">
  <tr>
  	<td><b>Gerar TXT referente a:</b></td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="ruas" value="X">Cadastro de Ruas</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="bairro" value="X">Cadastro de Bairro</td>  	
  </tr> 	
  <tr>
  	<td><input type="checkbox" name="cgm" value="X">Cadastro de CGM</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="caracter" value="X">Cadastro de Caracteristicas</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="ativid" value="X">Cadastro de Atividades</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="cadtipo" value="X">Cadastro Tipos de Débitos</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="matricula" value="X">Dados do IPTU</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="lote" value="X">Cadastro de Lote</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="face" value="X">Cadastro de Face</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="carlote" value="X">Caracteristicas do Lote</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="carface" value="X">Caracteristicas da Face</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="carconstr" value="X">Caracteristicas da Construção</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="inscricao" value="X">Dados do ISSQN</td>  	
  </tr>
  <tr>
  	<td><input type="checkbox" name="debitos" value="X">Dados dos Débitos</td>  	
  </tr>  
  <tr>   
    <td align="center">
      <input type="submit" name="gerar" value="Gerar"
             <?=($db_botao ? '' : 'disabled')?>>
    </td>      
  </tr>
</table>
</form>
</body>
</html>