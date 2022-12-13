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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("model/dbModeloArquivoTexto.model.php");
require_once("model/dbVisualizadorImpressaoTexto.model.php");
require_once("libs/db_stdlibwebseller.php");
db_postmemory($HTTP_POST_VARS);
?>
<html>
  <head>
    <link href="./estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" id='visualizador'>
  <center>
    <big>Pré-Visualização da Impressão</big>
  </center> 
<?

$dHoje      = date("Y-m-d",db_getsession("DB_datausu"));
$iInstitui  = db_getsession("DB_instit");
$sModelo    = "documentos/templates/txt/sau_modelo_faa.txt";

try {
  $oGerador = new dbModeloArquivoTexto($sModelo);
} catch (Exception $oExcecao) {
  echo '<script> alert("'.str_replace('"', '\"', $oExcecao->getMessage()).'"); window.close(); </script>';
}

$oProntuarios      = db_utils::getdao('prontuarios');
$oProntproced      = db_utils::getdao('prontproced');
$aChaveProntuarios = explode(",", $chave_sd29_i_prontuario);
$iTam              = count($aChaveProntuarios);

/* Sub sql para obter os procedimentos (prontproced) */
$sSubProc  = ' from prontproced as a ';
$sSubProc .= '   inner join sau_procedimento as b on b.sd63_i_codigo = a.sd29_i_procedimento ';
$sSubProc .= '     where a.sd29_i_prontuario = prontuarios.sd24_i_codigo ';
$sSubProc .= '       order by a.sd29_i_codigo limit 1 ';

/* Sub sql para obter os profissionais (vindos da prontproced) */
$sSubProf  = ' from prontproced as a ';
$sSubProf .= '   inner join sau_procedimento as b on b.sd63_i_codigo = a.sd29_i_procedimento ';
$sSubProf .= '   inner join especmedico as c on c.sd27_i_codigo = a.sd29_i_profissional ';
$sSubProf .= '   inner join unidademedicos as d on d.sd04_i_codigo = c.sd27_i_undmed ';
$sSubProf .= '   inner join medicos as e on e.sd03_i_codigo = d.sd04_i_medico ';
$sSubProf .= '   inner join cgm as f on f.z01_numcgm = e.sd03_i_cgm ';
$sSubProf .= '     where a.sd29_i_prontuario = prontuarios.sd24_i_codigo ';
$sSubProf .= '       order by a.sd29_i_codigo limit 1 ';

/* Campos a serem buscados para emitir a FAA */
$sCampos  = " (select munic from db_config where codigo = $iInstitui) as municipio, ";
$sCampos .= " sd24_i_codigo as nro_faa, ";
$sCampos .= " fc_formatadata(sd24_d_cadastro) as data_faa, ";
$sCampos .= " sd24_c_cadastro  as hora_faa, ";
$sCampos .= " sd02_i_codigo      as cod_ups, ";
$sCampos .= " descrdepto         as nome_ups, ";
$sCampos .= " cgm_und.z01_ender  as rua_rua, ";
$sCampos .= " cgm_und.z01_compl  as complemanto_end_ups, ";
$sCampos .= " cgm_und.z01_numero as nro_end_ups, ";
$sCampos .= " cgm_und.z01_bairro as bairro_ups, ";
$sCampos .= " cgm_und.z01_cep    as cep_ups, ";
$sCampos .= " sd02_c_siasus      as sia_sus, ";
$sCampos .= " nome               as login, ";
$sCampos .= " z01_i_cgsund       as nro_cgs, ";
$sCampos .= " z01_v_nome         as nome_cgs, ";
$sCampos .= " z01_v_ender        as rua_pac, ";
$sCampos .= " z01_i_numero       as nro_pac, ";
$sCampos .= " z01_v_compl        as complemento_end_pac, ";
$sCampos .= " z01_v_bairro       as bairro_pac, ";
$sCampos .= " z01_v_munic        as cidade_pac, ";
$sCampos .= " z01_v_cep          as cep_pac, ";
$sCampos .= " z01_v_mae          as pai_pac, ";
$sCampos .= " z01_v_pai          as mae_pac, ";
$sCampos .= " fc_formatadata(z01_d_nasc) as data_nasc_pac, ";
$sCampos .= " fc_idade_anomesdia(z01_d_nasc, '$dHoje') as idade_pac, ";
$sCampos .= " case when z01_v_sexo = 'M' then 'MASCULINO' when z01_v_sexo = 'F' then 'FEMININO' ";
$sCampos .= " else z01_v_sexo end as sexo_pac, ";
$sCampos .= " case when sd03_i_codigo is null then (select e.sd03_i_codigo $sSubProf)";
$sCampos .= "      else sd03_i_codigo end as cod_medico, ";
$sCampos .= " case when cgm_med.z01_nome is null then (select f.z01_nome $sSubProf)";
$sCampos .= "      else cgm_med.z01_nome end as nome_medico, ";
$sCampos .= " case when sd03_i_crm is null then (select e.sd03_i_crm $sSubProf) else sd03_i_codigo end as crm, ";
$sCampos .= " case when s144_c_descr <> '' then s144_c_descr else sd24_v_motivo end as motivo_atend, ";
$sCampos .= " sd24_t_diagnostico as diagnostico,";
$sCampos .= " sd24_i_tipo        as tipo_atend,";
$sCampos .= " fc_formatadata(sd23_d_consulta) as data_atend, ";
$sCampos .= " sd23_c_hora        as hora_atend ";

$sCamposProc  = "sd63_c_procedimento as cod_proc,";
$sCamposProc .= "sd63_c_nome         as nome_proc,";
$sCamposProc .= "sd29_t_tratamento   as tratamento,";
$sCamposProc .= "sd70_c_cid          as cod_cid,";
$sCamposProc .= "sd70_c_nome         as nome_cid ";

for ($iInd = 0; $iInd < $iTam; $iInd++) {
  
  $sSql     = $oProntuarios->sql_query_faa($aChaveProntuarios[$iInd], $sCampos);
  $sSqlProc = $oProntproced->sql_query_faa("", $sCamposProc, "", " sd29_i_prontuario = ".$aChaveProntuarios[$iInd]);
  
  try {

    $oGerador->setSql(array($sSql, $sSqlProc));
    $oGerador->gerarArquivo();
    $aArquivos[] = TiraAcento($oGerador->getArquivo(), false);

  } catch (Exception $oExcecao) {

    echo '<script> alert("'.str_replace('"', '\"', $oExcecao->getMessage()).'"); window.close(); </script>';
    exit;

  }

}

try {

  $oVisualizador = new dbVisualizadorImpressaoTexto('visualizador', $aArquivos);
  $oVisualizador->setAltura('80%');
  $oVisualizador->visualizar();

} catch (Exception $oExcecao) {

  echo '<script> alert("'.str_replace('"', '\"', $oExcecao->getMessage()).'"); window.close(); </script>';
  exit;

}

?>