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


$clrotulo = new rotulocampo;
$clrotulo->label("j34_lote");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_setor");

db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);

$clruas = new cl_ruas;
$clbairro = new cl_bairro;
$clcgm = new cl_cgm;
$clativid = new cl_ativid;
$clcaracter = new cl_caracter;
$clcadtipo = new cl_cadtipo;
$cliptucale = new cl_iptucale;
$cliptucalc = new cl_iptucalc;
$cliptucalv = new cl_iptucalv;
$cllote = new cl_lote;
$clface = new cl_face;
$clcarlote = new cl_carlote;
$clcarface = new cl_carface;
$clcarconstr = new cl_carconstr;
$clisscalc = new cl_isscalc;
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

           $sql = "  select j01_matric,
                            z01_nome,
                            z01_ender,
                            j34_setor,
			    j34_quadra,
			    j34_lote,
                            j34_zona,
                            j01_idbql,
                            j39_idcons,
                            j39_codigo,
                            b.j14_nome as rua_iptuconstr,
                            j39_numero,
                            j39_compl,
                            j49_codigo,
                            a.j14_nome as rua_testada,
                            j36_testad,
                            j15_numero,
                            j15_compl,
                            j49_face
                     from iptubase
                          inner join lote on j01_idbql = j34_idbql
                          inner join cgm on j01_numcgm = z01_numcgm
                          left join testpri on j49_idbql = j01_idbql
                          left join testada on j36_idbql = j49_idbql and j36_face  = j49_face
                          left join testadanumero on j01_idbql = j15_idbql
                          left join ruas as a on a.j14_codigo=j49_codigo
                          left join iptuconstr on j01_matric = j39_matric and j39_dtdemo is null
			  left join ruas as b on b.j14_codigo = j39_codigo
                     where j01_baixa is null
				";
		if($j34_setor!="")
		  $sql .= " and j34_setor = '$j34_setor' ";
		if($j34_quadra!="")
		  $sql .= " and j34_quadra = '$j34_quadra' ";
		if($j34_lote!="")
		  $sql .= " and j34_lote = '$j34_lote'";
		
		$sql .=	"   order by j01_matric, j39_idcons   ";
           
	        $result = pg_exec($sql);

			$numrows = pg_numrows($result);
			if ($result == false || $numrows == 0) {
				$erro = true;
				$descricao_erro .= "Não existe matricula cadastrada!";
			}
		}
			$nomedoarquivo = "tmp/".$gerar."_matricula".$anousu."_".date("Y-m-d_His", db_getsession("DB_datausu")).".txt";
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
								//----------  CAD. MATRICULAS ------------------------------------------------------

								fputs($clabre_arquivo->arquivo, str_pad(@ $j01_matric, 10));
								fputs($clabre_arquivo->arquivo, str_pad(@ $z01_nome, 40));
								fputs($clabre_arquivo->arquivo, str_pad(@ $z01_ender, 40));
								fputs($clabre_arquivo->arquivo, str_pad(@ $j34_setor,4));
								fputs($clabre_arquivo->arquivo, str_pad(@ $j34_quadra,4));
								fputs($clabre_arquivo->arquivo, str_pad(@ $j34_lote,4));
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
								/*
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
									fputs($clabre_arquivo->arquivo, str_pad(@ round($j23_vlrter,2), 15));
								} else {
									fputs($clabre_arquivo->arquivo, str_pad("00", 15));
								}
								$result_vlriptu = $cliptucalv->sql_record($cliptucalv->sql_query_hist(null, "sum(j21_valor) as vlr_iptu", null, "j21_anousu=".db_getsession("DB_anousu")." and j21_matric=$j01_matric and j21_codhis=1"));
								if ($cliptucalv->numrows > 0) {
									db_fieldsmemory($result_vlriptu, 0);
									fputs($clabre_arquivo->arquivo, str_pad(@ round($vlr_iptu,2), 15));
								} else {
									fputs($clabre_arquivo->arquivo, str_pad("00", 15));
								}
								$result_vlrtaxa = $cliptucalv->sql_record($cliptucalv->sql_query_hist(null, "sum(j21_valor) as vlr_taxa", null, "j21_anousu=".db_getsession("DB_anousu")." and j21_matric=$j01_matric and j21_codhis<>1"));
								if ($cliptucalv->numrows > 0) {
									db_fieldsmemory($result_vlrtaxa, 0);
									fputs($clabre_arquivo->arquivo, str_pad(@ round($vlr_taxa,2), 15));
								} else {
									fputs($clabre_arquivo->arquivo, str_pad("00", 15));
								}
								*/
								fputs($clabre_arquivo->arquivo, str_pad(@ round($j36_testad,2), 15));
								fputs($clabre_arquivo->arquivo, str_pad(@ $j34_zona, 5));
								fputs($clabre_arquivo->arquivo, str_pad(@ $j49_face, 5));

								fputs($clabre_arquivo->arquivo, "\n");
								//---------------------------------------------------------------------------------------														
							//break;

				}
						$erro = false;
						$descricao_erro = "Informações $info gerados com sucesso no diretorio /tmp do servidor.";
	
					fclose($clabre_arquivo->arquivo);

				} else {
					$erro = true;
					$descricao_erro = "Erro ao Criar arquivo: $arquivo";
				}
		
		if (@ $erro == true) {
			echo "<script>alert('$descricao_erro');</script>";
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
<br>
<br>
<br>
<table align="center">
   <tr title="<?=@$Tj34_setor?>"> 
   <td nowrap>
         <?=str_replace(":","",$Lj34_setor)."/".str_replace(":","",$Lj34_quadra)."/".$Lj34_lote?>
   </td>
   <td nowrap>
   <?
   db_input('j34_setor',5,$Ij34_setor,true,'text',1)
   ?>/
   <?
   unset($j34_quadra);
   db_input('j34_quadra',5,$Ij34_quadra,true,'text',1)
   ?>/
   <?
   unset($j34_lote);
   db_input('j34_lote',5,$Ij34_lote,true,'text',1)
   ?>
   </td>
   </tr>
 
  <tr>   
    <td colspan=3 align="center"><br><br>
      <input type="submit" name="gerar" value="Gerar Arquivo">
    </td>      
  </tr>

<?
if(isset($gerar)){
  echo "<tr>   
    <td colspan=3 align=\"center\"><br><br>
      <a href=\"$nomedoarquivo\">Clique com o Botão Direito do Mouse e Salve</a>
    </td>      
  </tr>";
} 
?>

</table>
</form>
</body>
</html>