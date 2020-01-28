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
include ("libs/db_liborcamento.php");
include ("classes/db_matordem_classe.php");
include ("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clmatordem = new cl_matordem;
//die($clmatordem->sql_query_infoemp(null,"*","m51_codordem limit 10"));
$result=$clmatordem->sql_record($clmatordem->sql_query_infoemp(null,"*","m51_codordem"));
if ($clmatordem->numrows == 0) {
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
	exit;
}

$clabre_arquivo = new cl_abre_arquivo("/tmp/info_ordem_compra.txt");
if ($clabre_arquivo->arquivo != false) {
	$vir = ';';
	/*
	fputs($clabre_arquivo->arquivo, "\n");
	fputs($clabre_arquivo->arquivo, "".$vir);	
  	fputs($clabre_arquivo->arquivo, "\n");
  	*/
	for ($w = 0; $w < $clmatordem->numrows; $w ++) {
		db_fieldsmemory($result, $w);
		$cont = $w +1;
        fputs($clabre_arquivo->arquivo, substr($m51_data,0,10).$vir);
        fputs($clabre_arquivo->arquivo, substr($m51_codordem,0,8).$vir);
        $arr_data=split("-",$m51_data);
        fputs($clabre_arquivo->arquivo, substr($arr_data[0],0,4).$vir);
        fputs($clabre_arquivo->arquivo, substr("ORDINARIO",0,20).$vir);
        fputs($clabre_arquivo->arquivo, substr($z01_nome,0,60).$vir);
        fputs($clabre_arquivo->arquivo, substr($z01_numcgm,0,8).$vir);
        fputs($clabre_arquivo->arquivo, substr($z01_munic,0,30).$vir);
        fputs($clabre_arquivo->arquivo, substr($z01_uf,0,2).$vir);
        /*
        $info_dot=db_dotacaosaldo(8,2,2,true,"o58_coddot=$e60_coddot",db_getsession("DB_anousu"));
        db_fieldsmemory($info_dot,0);
        */
        fputs($clabre_arquivo->arquivo, substr($o40_descr,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($o41_descr,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($o52_descr,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($o53_descr,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($o54_descr,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($o55_descr,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($o56_descr,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($o56_descr,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($o15_descr,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($e54_conpag,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($m51_obs,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($e54_praent,0,40).$vir);
        fputs($clabre_arquivo->arquivo, substr($descrdepto,0,40).$vir);
		fputs($clabre_arquivo->arquivo, substr($pc01_descrmater,0,60).$vir);
		fputs($clabre_arquivo->arquivo, substr("marca",0,5).$vir);//Unidade
		fputs($clabre_arquivo->arquivo, substr("unidade",0,30).$vir);//Marca
		fputs($clabre_arquivo->arquivo, substr($pc04_descrsubgrupo,0,40).$vir);
		fputs($clabre_arquivo->arquivo, substr($pc03_descrgrupo,0,40).$vir);
		fputs($clabre_arquivo->arquivo, substr($m52_numemp,0,20).$vir);
		fputs($clabre_arquivo->arquivo, substr($m52_quant,0,8).$vir);
		fputs($clabre_arquivo->arquivo, substr($m52_valor,0,15).$vir);
        fputs($clabre_arquivo->arquivo, "\n");
	}
	
	fclose($clabre_arquivo->arquivo);
	echo "<script>jan = window.open('db_download.php?arquivo=".$clabre_arquivo->nomearq."','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);</script>";
}

?>