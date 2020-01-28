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
include("dbforms/db_funcoes.php");
include("classes/db_relrub_classe.php");
include("classes/db_relrubmov_classe.php");
include("classes/db_selecao_classe.php");
include("classes/db_db_sysarquivo_classe.php");
include("classes/db_db_relat_classe.php");
include("classes/db_db_relattabelas_classe.php");
include("classes/db_db_relatselecionados_classe.php");
include("classes/db_db_relatfiltros_classe.php");
include("classes/db_db_relatcabec_classe.php");
include("classes/db_db_relatsoma_classe.php");
include("classes/db_db_relatquebra_classe.php");
include("dbforms/db_geraformulario.php");

$clrelrub = new cl_relrub;
$clrelrubmov = new cl_relrubmov;
$clselecao = new cl_selecao;
$cldb_sysarquivo = new cl_db_sysarquivo;
$cldb_relat = new cl_db_relat;
$cldb_relattabelas = new cl_db_relattabelas;
$cldb_relatselecionados = new cl_db_relatselecionados;
$cldb_relatfiltros = new cl_db_relatfiltros;
$cldb_relatcabec = new cl_db_relatcabec;
$cldb_relatsoma = new cl_db_relatsoma;
$cldb_relatquebra = new cl_db_relatquebra;
$g = new cl_formulario_relcampos;
$clrotulo = new rotulocampo;
$clrotulo->label("rh45_codigo");
$clrotulo->label("rh45_descr");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);
$db_opcao = 1;
$db_botao = true;
if(isset($salvarrelatorio)){
	db_inicio_transacao();
	$sqlerro = false;

	$db91_codrel = null;
  if(isset($campo_auxilio_codigorel) && trim($campo_auxilio_codigorel) != ""){
 		$db91_codrel = $campo_auxilio_codigorel;
	  $quebra = "false";
	  if(isset($qbrapag)){
	  	$quebra = "true";
	  }
	  $todos = "false";
	  if(isset($qbratod)){
	  	$todos = "true";
	  }
	  $arqui = "";
	  if(isset($gerafon)){
	  	if(trim($campo_camporecb_nomearq) == ""){
	  		$campo_camporecb_nomearq = "relatorio_configuravel.php";
	  	}
	  	$arqui = $campo_camporecb_nomearq;
	  }
	  $cldb_relat->db91_codrel = $db91_codrel;
	  $cldb_relat->db91_descr  = $campo_camporecb_cabecal;
	  $cldb_relat->db91_quebra = $quebra;
	  $cldb_relat->db91_todos  = $todos;
	  $cldb_relat->db91_nomearq= $arqui;
	  $cldb_relat->alterar($db91_codrel);
	  $erro_msg = $cldb_relat->erro_msg;
		if($cldb_relat->erro_status == 0) {
			$sqlerro = true;
		}
	
	  if($sqlerro == false){
		  $cldb_relattabelas->excluir(null,"db92_codrel = ".$db91_codrel);
			if($cldb_relattabelas->erro_status == 0) {
				$erro_msg = $cldb_relattabelas->erro_msg;
				$sqlerro = true;
				break;
			}
	  }
	
	  if($sqlerro == false){
		  $cldb_relatselecionados->excluir(null,"db93_codrel = ".$db91_codrel);
			if($cldb_relatselecionados->erro_status == 0) {
				$erro_msg = $cldb_relatselecionados->erro_msg;
				$sqlerro = true;
				break;
			}
	  }
	
	  if($sqlerro == false){
		  $cldb_relatfiltros->excluir(null,"db94_codrel = ".$db91_codrel);
			if($cldb_relatfiltros->erro_status == 0) {
				$erro_msg = $cldb_relatfiltros->erro_msg;
				$sqlerro = true;
				break;
			}
	  }
	
	  if($sqlerro == false){
			  $cldb_relatcabec->excluir(null,"db95_codrel = ".$db91_codrel);
				if($cldb_relatcabec->erro_status == 0) {
					$erro_msg = $cldb_relatcabec->erro_msg;
					$sqlerro = true;
					break;
				}
	  }
	
	  if($sqlerro == false){
		  $cldb_relatsoma->excluir(null,"db96_codrel = ".$db91_codrel);
			if($cldb_relatsoma->erro_status == 0) {
				$erro_msg = $cldb_relatsoma->erro_msg;
				$sqlerro = true;
				break;
			}
	  }
	
	  if($sqlerro == false){
		  $cldb_relatquebra->excluir(null,"db97_codrel = ".$db91_codrel);
			if($cldb_relatquebra->erro_status == 0) {
				$erro_msg = $cldb_relatquebra->erro_msg;
				$sqlerro = true;
				break;
			}
	  }
  }else{
	  if(trim($campo_camporecb_cabecal) == ""){
	  	if(trim($db91_codrel) == ""){
				$result = @pg_query("select nextval('db_relat_db91_codrel_seq') as db91_codrel");
				if($result && pg_numrows($result) > 0){
					db_fieldsmemory($result,0);
				}
	  	}
			$campo_camporecb_cabecal = "Relatório configurável ".$db91_codrel;
		}
	
	  $quebra = "false";
	  if(isset($qbrapag)){
	  	$quebra = "true";
	  }
	  $todos = "false";
	  if(isset($qbratod)){
	  	$todos = "true";
	  }
	  $arqui = "";
	  if(isset($gerafon)){
	  	if(trim($campo_camporecb_nomearq) == ""){
	  		$campo_camporecb_nomearq = "relatorio_configuravel.php";
	  	}
	  	$arqui = $campo_camporecb_nomearq;
	  }
	
	  $cldb_relat->db91_descr  = $campo_camporecb_cabecal;
	  $cldb_relat->db91_quebra = $quebra;
	  $cldb_relat->db91_todos  = $todos;
	  $cldb_relat->db91_nomearq= $arqui;
	  $cldb_relat->incluir($db91_codrel);
	  $db91_codrel = $cldb_relat->db91_codrel;
	  $erro_msg = $cldb_relat->erro_msg;
		if($cldb_relat->erro_status == 0) {
			$sqlerro = true;
		}
  }

  if($sqlerro == false){
  	$arr_tabelas_selecionadas = split(",",$campo_auxilio_tabelasel);
  	for($i=0; $i<count($arr_tabelas_selecionadas); $i++){
		  $cldb_relattabelas->db92_codrel = $db91_codrel;
		  $cldb_relattabelas->db92_codarq = $arr_tabelas_selecionadas[$i];
		  $cldb_relattabelas->incluir(null);
			if($cldb_relattabelas->erro_status == 0) {
				$erro_msg = $cldb_relattabelas->erro_msg;
				$sqlerro = true;
				break;
			}
  	}
  }

  if($sqlerro == false){
  	$arr_camposs_selecionados = split(",",$campo_auxilio_sselecion);
  	for($i=0; $i<count($arr_camposs_selecionados); $i++){
		  $cldb_relatselecionados->db93_codrel = $db91_codrel;
		  $cldb_relatselecionados->db93_codcam = $arr_camposs_selecionados[$i];
		  $cldb_relatselecionados->incluir(null);
			if($cldb_relatselecionados->erro_status == 0) {
				$erro_msg = $cldb_relatselecionados->erro_msg;
				$sqlerro = true;
				break;
			}
  	}
  }

  for($i=1; $i<4; $i++){
	  if($sqlerro == false){
	  	$variavelteste = "campo_camporecb_filtro".$i;
	  	if(trim($$variavelteste) != ""){
		  	$arr_filtros_selecionados1 = split("#",$$variavelteste);
		  	$campofiltro01 = $arr_filtros_selecionados1[0];
		  	$valorfiltro11 = $arr_filtros_selecionados1[1];
		  	$valorfiltro12 = "";
		  	if(isset($arr_filtros_selecionados1[2])){
		  	  $valorfiltro12 = $arr_filtros_selecionados1[2];
		  	}
			  $cldb_relatfiltros->db94_codrel = $db91_codrel;
			  $cldb_relatfiltros->db94_codcam = $campofiltro01;
			  $cldb_relatfiltros->db94_valini = $valorfiltro11;
			  $cldb_relatfiltros->db94_valfim = $valorfiltro12;
			  $cldb_relatfiltros->incluir(null);
				if($cldb_relatfiltros->erro_status == 0) {
					$erro_msg = $cldb_relatfiltros->erro_msg;
					$sqlerro = true;
					break;
				}
	  	}
	  }
  }

  for($i=1; $i<7; $i++){
	  if($sqlerro == false){
	  	$variavelteste = "campo_camporecb_comple".$i;
	  	if(trim($$variavelteste) != ""){
        $linha = ($i+1);
			  $cldb_relatcabec->db95_codrel = $db91_codrel;
			  $cldb_relatcabec->db95_compl  = $$variavelteste;
			  $cldb_relatcabec->db95_linha  = $linha;
			  $cldb_relatcabec->incluir(null);
				if($cldb_relatcabec->erro_status == 0) {
					$erro_msg = $cldb_relatcabec->erro_msg;
					$sqlerro = true;
					break;
				}
	  	}
	  }
  }

  if($sqlerro == false){
  	$arr_camposs_somatoriocam = split(",",$campo_camporecb_somator);
  	for($i=0; $i<count($arr_camposs_somatoriocam); $i++){
		  $cldb_relatsoma->db96_codrel = $db91_codrel;
		  $cldb_relatsoma->db96_codcam = $arr_camposs_somatoriocam[$i];
		  $cldb_relatsoma->incluir(null);
			if($cldb_relatsoma->erro_status == 0) {
				$erro_msg = $cldb_relatsoma->erro_msg;
				$sqlerro = true;
				break;
			}
  	}
  }

  if($sqlerro == false){
  	$arr_camposs_quebrapagcam = split(",",$campo_camporecb_qbrapor);
  	$arr_camposs_totalizacams = split(",",$campo_camporecb_totaliz);
  	for($i=0; $i<count($arr_camposs_quebrapagcam); $i++){
  		$totaliza = "false";
  		if(in_array($arr_camposs_quebrapagcam[$i],$arr_camposs_totalizacams)){
  			$totaliza = "true";
  		}
		  $cldb_relatquebra->db97_codrel = $db91_codrel;
		  $cldb_relatquebra->db97_codcam = $arr_camposs_quebrapagcam[$i];
		  $cldb_relatquebra->db97_totaliza = $totaliza;
		  $cldb_relatquebra->incluir(null);
			if($cldb_relatquebra->erro_status == 0) {
				$erro_msg = $cldb_relatquebra->erro_msg;
				$sqlerro = true;
				break;
			}
  	}
  }
	db_fim_transacao($sqlerro);
}else if(isset($chavepesquisa)){
  $result = $cldb_relat->sql_record($cldb_relat->sql_query($chavepesquisa)); 
  db_fieldsmemory($result,0);
	$campo_auxilio_codigorel = $db91_codrel;
  $campo_camporecb_cabecal = $db91_descr;
  if($db91_quebra == 't'){
  	$qbrapag = "qbrarpagina";
  }
  if($db91_todos == 't'){
  	$qbratod = "qbrartodos";
  }
  if($db91_nomearq != ''){
  	$gerafon = "geracodfon";
  	$campo_camporecb_nomearq = $db91_nomearq;
  }

  $virgul = "";
  $campo_auxilio_tabelasel = "";
  $result_tabelas = $cldb_relattabelas->sql_record($cldb_relattabelas->sql_query_file(null,"*","db92_codigo","db92_codrel=".$chavepesquisa));
  for($i=0; $i<$cldb_relattabelas->numrows; $i++){
  	db_fieldsmemory($result_tabelas, $i);
  	if($i==0){
  		$seleciona = $db92_codarq;
  	}
  	$campo_auxilio_tabelasel.= $virgul.$db92_codarq;
  	$arr_alter[$db92_codarq] = $db92_codarq;
  	$virgul = ",";
  }
  $g->arr_alter = $arr_alter;
  $g->cam_alter = $campo_auxilio_tabelasel;

  $virgul = "";
  $campo_auxilio_sselecion = "";
  $result_selecionados = $cldb_relatselecionados->sql_record($cldb_relatselecionados->sql_query_file(null,"*","db93_codigo","db93_codrel=".$chavepesquisa));
  for($i=0; $i<$cldb_relatselecionados->numrows; $i++){
  	db_fieldsmemory($result_selecionados, $i);
  	$campo_auxilio_sselecion.= $virgul.$db93_codcam;
  	$virgul = ",";
  }

  $virgul = "";
  $campo_seleciona_filtros = "";
  $result_filtros = $cldb_relatfiltros->sql_record($cldb_relatfiltros->sql_query_file(null,"*","db94_codigo","db94_codrel=".$chavepesquisa));
  for($i=0; $i<$cldb_relatfiltros->numrows; $i++){
  	db_fieldsmemory($result_filtros, $i);
  	$campo_seleciona_filtros.= $virgul.$db94_codcam;
  	$camporecebefiltro = "campo_camporecb_filtro".($i+1);
  	$camporecebedadosf = "filtro".($i+1);
  	$$camporecebefiltro = $db94_codcam."#".$db94_valini;
  	$$camporecebedadosf = $db94_codcam;
  	if(trim($db94_valfim) != ""){
  	  $$camporecebefiltro.= "#".$db94_valfim;
  	}
  	$virgul = ",";
  }

  $campo_camporecb_comple1 = "";
  $campo_camporecb_comple2 = "";
  $campo_camporecb_comple3 = "";
  $campo_camporecb_comple4 = "";
  $campo_camporecb_comple5 = "";
  $campo_camporecb_comple6 = "";
  $result_cabec = $cldb_relatcabec->sql_record($cldb_relatcabec->sql_query_file(null,"*","db95_linha","db95_codrel=".$chavepesquisa));
  for($i=0; $i<$cldb_relatcabec->numrows; $i++){
  	db_fieldsmemory($result_cabec, $i);
  	$campocomplementar = "campo_camporecb_comple".($db95_linha-1);
  	$$campocomplementar = $db95_compl;
  }

  $virgul = "";
  $campo_camporecb_somator = "";
  $result_soma = $cldb_relatsoma->sql_record($cldb_relatsoma->sql_query_file(null,"*","db96_codigo","db96_codrel=".$chavepesquisa));
  for($i=0; $i<$cldb_relatsoma->numrows; $i++){
  	db_fieldsmemory($result_soma, $i);
  	$campo_camporecb_somator.= $virgul.$db96_codcam;
  	$virgul = ",";
  }

  $virgul = "";
  $virgulT = "";
  $campo_camporecb_qbrapor = "";
  $campo_camporecb_totaliz = "";
  $result_quebra = $cldb_relatquebra->sql_record($cldb_relatquebra->sql_query_file(null,"*","db97_codigo","db97_codrel=".$chavepesquisa));
  for($i=0; $i<$cldb_relatquebra->numrows; $i++){
  	db_fieldsmemory($result_quebra, $i);
  	$campo_camporecb_qbrapor.= $virgul.$db97_codcam;
  	if($db97_totaliza == 't'){
 		  $campo_camporecb_totaliz.= $virgulT.$db97_codcam;;
  	  $virgulT = ",";
  	}
  	$virgul = ",";
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
			<?
			/*
<center>
<table width="60%" border="0" cellspacing="4" cellpadding="0">
  <tr><td colspan="2">&nbsp;</td></tr>
  <form name="form1" method="post">
  <tr>
    <td align="center">
      <table border="0">
			include("dbforms/db_classesgenericas.php");
			$geraform = new cl_formulario_rel_pes;
			$geraform->manomes = false;
			$geraform->usaregi = true;
			$geraform->usaorga = true;
			$geraform->usalota = true;

			$geraform->intregi = true;

			$geraform->arr_mostord = Array('a'=>"Alfabética",'n'=>"Numérica");

		  $geraform->onchpad = true;
		  $geraform->qbrapag = true;
      $geraform->strngtipores = "gol";

			$geraform->gera_form();

      $arr_pontosgerfs_inicial = Array(
                                   "00" =>"Salário",
                                   "01" =>"Adiantamento",
                                   "02" =>"Férias",
                                   "03" =>"Rescisão",
                                   "04" =>"Saldo do 13o",
                                   "05" =>"Complementar",
                                   "06" =>"Ponto Fixo",
                                   "07" =>"Ponto Salário",
                                   "08" =>"Ponto Complementar",
                                   "09" =>"Ponto Rescisão",
                                   "10" =>"Ponto 13o"
                                  );
      $arr_pontosgerfs_final   = Array();
      db_multiploselect("valor","descr", "", "", $arr_pontosgerfs_inicial, $arr_pontosgerfs_final, 11, 250, "", "", false);
      */
		  $g->sqltabelas = $cldb_sysarquivo->sql_query_arqmod(null,"db_sysarquivo.codarq,rotulo","rotulo","db_sysmodulo.codmod=28 and db_sysarquivo.nomearq in ( 'rhpessoal' , 'rhpessoalmov' ) and db_sysarquivo.rotulo <> '' and db_sysarquivo.rotulo is not null");
		  $g->urlproxarq = ""; // Arquivo que receberá URL
		  $g->varcodigo = "codarq";  // Nome da variável que será o value do SELECT
		  $g->vardescri = "rotulo";  // Nome da variável que será a descrição do SELECT
		
			$g->gera_arquivo();
			?>
  <!--
      </table>
    </td>
  </tr>
  <tr>
    <td colspan='2' align='center'>
      <input type="button" name="processar" value="Processar" onclick="js_gerar_consrel();">
    </td>
  </tr>
  </form>
</table>
</center>
  -->
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
function js_trocaopcao(){
	document.form1.submit();
}
js_trocacordeselect();
</script>
</html>
<?
if(isset($salvarrelatorio)){
	if($sqlerro == true){
		db_msgbox($erro_msg);
	}else{
		echo "<script>js_actionform(false);</script>";
	}
}
?>