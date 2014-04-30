<?php
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

require_once 'libs/db_stdlib.php';
require_once 'libs/db_conecta.php';
require_once 'libs/db_sessoes.php';
require_once 'libs/db_usuariosonline.php';
require_once 'libs/JSON.php';
require_once 'libs/db_utils.php';
include("classes/db_unidades_classe.php" );

$objJson    = new services_json();
$clunidades      = new cl_unidades;
$departamento    = db_getsession("DB_coddepto");



$str        = $_POST["string"];
$str2       = crossUrlDecode($str);
$sName      = html_entity_decode($str2);
//echo"str = [$str] -> [$str2] -> [$sName]";
$tipo       = $_GET["tipo"];
$where      = $_GET["where"];


$sd02_i_codigo        = db_getsession("DB_coddepto");
$result_unidades      = $clunidades->sql_record( $clunidades->sql_query($sd02_i_codigo,"sd02_c_centralagenda,descrdepto",null,"") );
if( $clunidades->numrows != 0 ){
	@db_fieldsmemory($result_unidades,0);
}else{
	//db_msgbox("Departamento atual não é uma UPS.");
    //return false;
}
$sql="";
if($tipo==1){
	if($where==""){
		$sql = "SELECT distinct sd03_i_codigo as cod,z01_nome as label FROM medicos 
		           inner join cgm on cgm.z01_numcgm = medicos.sd03_i_cgm
		           inner join unidademedicos on unidademedicos.sd04_i_medico = medicos.sd03_i_codigo
		           inner join especmedico on especmedico.sd27_i_undmed = unidademedicos.sd04_i_codigo		            
		        WHERE sd04_c_situacao='A' 
		              and sd27_c_situacao='A' 
		              and sd04_i_unidade = $departamento
		              and z01_nome ilike '".$sName."%'";
	}else{
		$sql = "select sd03_i_codigo as cod, a.z01_nome as label from especmedico 
                   inner join rhcbo on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo 
                   inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed 
                   inner join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico 
                   inner join cgm as a on a.z01_numcgm = medicos.sd03_i_cgm 
                   where sd04_c_situacao='A' 
                     and sd27_c_situacao = 'A' 
                     and sd04_i_unidade = $departamento 
                     and rh70_estrutural = '$where'
                     and a.z01_nome ilike '".$sName."%' 
                     and exists ( select * from undmedhorario where sd30_i_undmed = sd27_i_codigo )";
	}
}elseif($tipo==2){
	if($where==""){
		$sql      = "select distinct rh70_estrutural as cod, rh70_descr as label from especmedico 
		                  inner join rhcbo on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo 
		                  inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed
		             where rh70_descr ilike '%".$sName."%' 
		                   and sd27_c_situacao = 'A' 
		                   and sd04_i_unidade = $departamento
		             order by rh70_descr";
	}else{
		$sql      = "select distinct rh70_estrutural as cod, rh70_descr as label from especmedico 
		                  inner join rhcbo on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo 
		                  inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed 
		             where rh70_descr ilike '%".$sName."%' 
		                   and sd27_c_situacao = 'A' 
		                   and sd04_i_unidade = $departamento
		                   and sd04_i_medico = $where 
		             order by rh70_descr";
	}
}elseif($tipo==3){
	$sql      = "SELECT z01_numcgm as cod,z01_nome as label FROM cgm WHERE z01_nome ilike '".$sName."%'";
}
/*
if($tipo==4){
	$sql      = "select distinct sau_proccbo.sd96_i_codigo, 
	                             sau_proccbo.sd96_i_procedimento, 
	                             sau_procedimento.sd63_c_procedimento, 
	                             sau_procedimento.sd63_c_nome, 
	                             sau_proccbo.sd96_i_cbo, 
	                             sau_proccbo.sd96_i_anocomp, 
	                             sau_proccbo.sd96_i_mescomp 
	             from sau_proccbo 
	                 inner join rhcbo on rhcbo.rh70_sequencial = sau_proccbo.sd96_i_cbo 
	                 inner join sau_procedimento on sau_procedimento.sd63_i_codigo = sau_proccbo.sd96_i_procedimento 
	                 left join sau_procmodalidade on sau_procmodalidade.sd83_i_procedimento = sau_procedimento.sd63_i_codigo 
	                 left join sau_modalidade on sau_modalidade.sd82_i_codigo = sau_procmodalidade.sd83_i_modalidade 
	                 left join sau_financiamento on sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento 
	                 left join sau_rubrica on sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica 
	                 left join sau_complexidade on sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade 
	             where sd96_i_cbo = 0 and sau_procedimento.sd63_i_codigo in ( select sd88_i_procedimento from sau_procservico inner join (select (select sd87_i_codigo from sau_servclassificacao where sd87_c_classificacao = x.sd87_c_classificacao and sd87_i_servico in (select sd86_i_codigo from sau_servico where sd86_c_servico = x.sd86_c_servico order by sd86_i_anocomp desc , sd86_i_mescomp desc limit 1 ) ) as sd87_i_codigo from (select sd87_c_classificacao, sd86_c_servico from unidadeservicos inner join sau_servclassificacao on sau_servclassificacao.sd87_i_codigo = unidadeservicos.s126_i_servico inner join sau_servico on sau_servico.sd86_i_codigo = sau_servclassificacao.sd87_i_servico where unidadeservicos.s126_i_unidade = 135016 ) as x ) as y on sau_procservico.sd88_i_classificacao = y.sd87_i_codigo) order by sd96_i_anocomp desc , sd96_i_mescomp desc, sd96_i_codigo";
}*/
$array="";
if($sql!=""){
   //echo"SQL [$sql]";
   $result   = pg_query($sql);
   $iNumRows = pg_num_rows($result);
   $array    = db_utils::getColectionByRecord($result,false,false,true);
}
echo $objJson->encode($array);

function crossUrlDecode($source) {
    $decodedStr = '';
    $pos = 0;
    $len = strlen($source);

    while ($pos < $len) {
        $charAt = substr ($source, $pos, 1);
        if ($charAt == 'Ã') {
            $char2 = substr($source, $pos, 2);
            $decodedStr .= htmlentities(utf8_decode($char2),ENT_QUOTES,'ISO-8859-1');
            $pos += 2;
        }
        elseif(ord($charAt) > 127) {
            $decodedStr .= "&#".ord($charAt).";";
            $pos++;
        }
        elseif($charAt == '%') {
            $pos++;
            $hex2 = substr($source, $pos, 2);
            $dechex = chr(hexdec($hex2));
            if($dechex == 'Ã') {
                $pos += 2;
                if(substr($source, $pos, 1) == '%') {
                    $pos++;
                    $char2a = chr(hexdec(substr($source, $pos, 2)));
                    $decodedStr .= htmlentities(utf8_decode($dechex . $char2a),ENT_QUOTES,'ISO-8859-1');
                }
                else {
                    $decodedStr .= htmlentities(utf8_decode($dechex));
                }
            }
            else {
                $decodedStr .= $dechex;
            }
            $pos += 2;
        }
        else {
            $decodedStr .= $charAt;
            $pos++;
        }
    }

    return $decodedStr;
}
?>