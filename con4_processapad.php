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


require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
include ("libs/db_sql.php");
include ("libs/db_liborcamento.php");
include ("libs/db_libcontabilidade.php");
include ("libs/db_libtxt.php");
// classes do pad
include ("con4_padbal_rec.php");
include ("con4_padbal_desp.php");
include ("con4_padbal_ver.php");
include ("con4_padcta_disp.php");
include ("con4_padcta_oper.php");
include ("con4_padrd_extra.php");
include ("con4_padreceita.php");
include ("con4_padrubrica.php");
include ("con4_padempenho.php");
include ("con4_padliquidac.php");
include ("con4_padpagament.php");
include ("con4_paddecreto.php");
include ("con4_padorgao.php");
include ("con4_paduniorcam.php");
include ("con4_padfuncao.php");
include ("con4_padsubfunc.php");
include ("con4_padprograma.php");
include ("con4_padprojativ.php");
include ("con4_padcredor.php");
include ("con4_padrecurso.php");
include ("con4_padsubprog.php");
include ("con4_padbrec_ant.php");
include ("con4_padrec_ant.php");
include ("con4_padbrub_ant.php");
include ("con4_padbver_ant.php");
include ("con4_padbvmovant.php");
include ("classes/db_conarquivospad_classe.php");
include ("classes/db_orcparametro_classe.php");
include ("classes/db_db_config_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$cldownload = new cl_download;

$cldb_config = new cl_db_config;

$instit   = "";
$separa   = "";
$sArquivo = "siapc";
if (isset($tipo) && $tipo != '') {
  $sArquivo = "mgs";
}
$resinst = $cldb_config->sql_record($cldb_config->sql_query_file(null, 'codigo,codtrib', 'tribinst, codigo', 'tribinst = '.db_getsession("DB_instit")));
if ($cldb_config->numrows > 0) {

	global $instituicoes;

	$instituicoes[0] = "0000";
	for ($i = 0; $i < $cldb_config->numrows; $i ++) {
		$instituicoes[pg_result($resinst, $i, 'codigo')] = pg_result($resinst, $i, 'codtrib');
		$instit .= $separa.pg_result($resinst, $i, 'codigo');
		$separa = ",";
	}

	$anousu = db_getsession("DB_anousu");
	$header = "falhou header: verifique con4_processapad.php";
	if (isset ($processar)) {
		$erro = "false";
		echo "<font size='1'>";
		echo "Iniciando processamento ...<br>";
		echo "instituição : $instit  ...<br>";
		echo "Período : ".db_formatar($data_ini, "d")." à ".db_formatar($data_fim, "d")."<br>";
		echo "Arquivos     : </font>";
		$matriz = split('\.', $lista);
		flush();

		if (count($matriz) > 1) {
			// monta header
			$res = pg_exec("select nomeinst,cgc from db_config where codigo=".db_getsession("DB_instit"));
			db_fieldsmemory($res, 0);
			$ini = split("-", $data_ini);
			$ini = "$ini[2]$ini[1]$ini[0]";
			$fim = split("-", $data_fim);
			$fim = "$fim[2]$fim[1]$fim[0]";
			$dt = split("-", $data_pro);
			$dt = "$dt[2]$dt[1]$dt[0]";
			$header = formatar($cgc, 14, 'n').$ini.$fim.$dt.formatar($nomeinst, 80, 'c');

			// verifica se o orcamento foi feito no elemento ou subelemento
			$clorcparametro = new cl_orcparametro;
			$res = $clorcparametro->sql_record($clorcparametro->sql_query_file($anousu));
			db_fieldsmemory($res, 0);
			if ($o50_subelem == 't') {
				$subelemento = 'sim'; // true 
			} else {
				$subelemento = 'nao'; // false, evitar problemas no select
			}
			$tribinst  = db_getsession("DB_instit");
			// carrega classes
			for ($x = 0; $x < sizeof($matriz) - 1; $x ++) {
				$contador = 0;
				$classe = $matriz[$x];
				echo "<br><b><font size='1'>".strtoupper($matriz[$x]).".TXT</font></b>";
				$cl_classe = new $classe ($header);
				$teste = $cl_classe->processa($instit, $data_ini, $data_fim, $tribinst, $subelemento);
				if ($teste == "true") {
					$cldownload->arquivo = strtoupper($matriz[$x]).".TXT";
					//$cldownload->texto = 'Clique Aqui para Baixar';
					echo "... ";
					$cldownload->download();
					echo "  Ok";
				} else {
					echo "...Erro";
					$erro = "true";
				}
				flush();
			}
			// aqui todos os testes = "true"
			if ($erro == "false") {
				// zipa e faz link
				//system("bin/rar -inul a siapc.rar tmp/*.TXT");
				$arqs = "";
				for ($x = 0; $x < sizeof($matriz) - 1; $x ++) {
					$arqs .= " tmp/".strtoupper($matriz[$x]).".TXT ";
				}
				system("rm -f tmp/{$sArquivo}.zip");
				system("bin/zip -q tmp/{$sArquivo}.zip $arqs");
				echo "<br>";
				echo "<a href='tmp/{$sArquivo}.zip'>Arquivos ".strtoupper($sArquivo)." (zip)</a>";
			}
		} else {
			echo "<strong>Nenhum Arquivo selecionado.</strong>";
		}
	}
} else {
	echo "<strong>Instituição não configurada para geração do PAD.</strong>";
}
?>