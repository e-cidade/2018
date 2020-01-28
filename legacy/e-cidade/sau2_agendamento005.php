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

$dHoje     = date("Y-m-d",db_getsession("DB_datausu"));
$iInstitui = db_getsession("DB_instit");
$sModelo   = "documentos/templates/txt/sau_modelo_comprovante_agendamento.txt";
try {
  $oGerador = new dbModeloArquivoTexto($sModelo);
} catch (Exception $oExcecao) {

  echo '<script> alert("'.str_replace('"', '\"', $oExcecao->getMessage()).'"); window.close(); </script>';
  exit;

}
$oAgendamentos = db_utils::getdao('agendamentos');
$ad23_i_codigo = explode(",",$sd23_i_codigo);
$iTam          = count($ad23_i_codigo);
$aArquivos     = array();
for ($iInd = 0; $iInd < $iTam; $iInd++) {

  $sCampos  = " (select munic from db_config where codigo = $iInstitui) as municipio, ";
  $sCampos .= " sd23_i_codigo      as nro_agendamento, ";
  $sCampos .= " fc_formatadata(sd23_d_agendamento) as data_agendamento, ";
  $sCampos .= " fc_formatadata(sd23_d_consulta) as data_atendimento, ";
  $sCampos .= " sd23_c_hora        as hora, ";
  $sCampos .= " ed32_c_descr       as dia_semana, ";
  $sCampos .= " sd23_i_turno       as turno, ";
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
  $sCampos .= " fc_formatadata(z01_d_nasc) as data_nasc_pac, ";
  $sCampos .= " fc_idade(z01_d_nasc,'$dHoje') as idade_pac, ";
  $sCampos .= " case when z01_v_sexo = 'M' then 'MASCULINO' when z01_v_sexo = 'F' then 'FEMININO' else z01_v_sexo end as sexo_pac, ";
  $sCampos .= " sd03_i_codigo      as cod_medico, ";
  $sCampos .= " cgm_med.z01_nome   as nome_medico, "; 
  $sCampos .= " sd03_i_crm         as crm ";

  $sSql     = $oAgendamentos->sql_query_comprovante($ad23_i_codigo[$iInd], $sCampos);
  $rs       = $oAgendamentos->sql_record($sSql);
  if ($oAgendamentos->numrows < 0) {

    echo "<table width='100%'>
            <tr>
              <td align='center'>
                <font color='#FF0000' face='arial'><b>Nenhum Registro para o Relatório<br>
                <input type='button' value='Fechar' onclick='window.close()'></b></font> 
              </td>
            </tr>
          </table>";
    exit;

  }
  
  try {

    $oGerador->setSql($sSql);
    $oGerador->gerarArquivo();
    $aArquivos[] = TiraAcento($oGerador->getArquivo(), false);

  } catch (Exception $oExcecao) {

    echo '<script> alert("'.str_replace('"', '\"', $oExcecao->getMessage()).'"); window.close(); </script>';
    exit;

  }
}

try {

  $oVisualizador = new dbVisualizadorImpressaoTexto('visualizador', implode("", $aArquivos));
  $oVisualizador->setAltura('80%');
  $oVisualizador->visualizar();

} catch (Exception $oExcecao) {

  echo '<script> alert("'.str_replace('"', '\"', $oExcecao->getMessage()).'"); window.close(); </script>';
  exit;

}

?>
    </div>
  </body>
</html>