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

include_once ("libs/db_sql.php");
include_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
include_once ("libs/db_stdlibwebseller.php");
include_once ("libs/db_utils.php");
include_once ("classes/db_prontuarios_ext_classe.php");
include_once ("classes/db_agendamentos_ext_classe.php");
include_once ("classes/db_prontagendamento_classe.php");
include_once ("classes/db_sau_config_ext_classe.php");
include_once ("classes/db_sau_proccbo_classe.php");
include_once ("classes/db_prontproced_ext_classe.php");
include_once ("classes/db_prontprofatend_ext_classe.php");

include_once ("dbforms/db_funcoes.php");

parse_str ( $HTTP_SERVER_VARS ["QUERY_STRING"] );
db_postmemory ( $HTTP_POST_VARS );

set_time_limit ( 0 );

$clprontuarios  = new cl_prontuarios_ext ( );
$clprontproced  = new cl_prontproced_ext ( );
$clagendamentos = new cl_agendamentos_ext ( );
$oDaoSauConfig  = db_utils::getdao('sau_config_ext');
/* BUSCAR PARÂMETROS DE CONFIGURAÇÃO */
$sSql     = $oDaoSauConfig->sql_query_ext();
$rsConfig = $oDaoSauConfig->sql_record($sSql);

if ($oDaoSauConfig->numrows > 0) {

  $oDadosConfig = db_utils::fieldsmemory($rsConfig, 0);

}

?>
<html>
<head>
<title></title>
<style type="text/css">
<!--
.style12 {
  font-size: 12px;
  font-weight: bold; 
  font-family: "Monospace"; 
}
.style19 {font-size: 10px; font-family: "Monospace"; }
.style37 {font-size: 9px;}
body {
  margin-bottom: 0px;
  margin-top: 0px;
}
-->
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</style>
</head>
<body>
<table width="100%"  border="0">

<?
$aChaveProntuarios = explode(",",$chave_sd29_i_prontuario);
$iTam              = count($aChaveProntuarios);
for( $intAgenda=0; $intAgenda < $iTam; $intAgenda++ ){

  $objRetorno    = new stdClass();
  $sSql          = $clprontuarios->sql_query_ext ($aChaveProntuarios[$intAgenda], "*, fc_idade(z01_d_nasc, current_date )||' anos' as idade " );
  $resProntuario = $clprontuarios->sql_record($sSql);
  if ($clprontuarios->numrows == 0) {
    echo "<table width='100%'>
            <tr>
              <td align='center'>
                <font color='#FF0000' face='arial'><b>Prontuario não encontrado<br>
                <input type='button' value='Fechar' onclick='window.close()'></b></font>
              </td>
            </tr>
          </table>";
    exit;
  }
  $objRetorno->prontuario  = db_utils::getColectionByRecord($resProntuario, true, false);
  $sSql = $clprontproced->sql_query_ext(null,
                                        "*, m.z01_nome as nome_profissional ",
                                        "",
                                        " sd29_i_prontuario = $aChaveProntuarios[$intAgenda] ");
  $resProntproced          = $clprontproced->sql_record ($sSql);
  $resProntprocedConsultas = $clprontproced->sql_record ( 
                  $clprontproced->sql_query_ext(null,
                    "*, m.z01_nome as nome_profissional"," sd29_d_data desc limit 11", 
                    " sd24_i_numcgs = {$objRetorno->prontuario[0]->sd24_i_numcgs} and 
                      substr( sd63_c_procedimento, 1, 2 ) = '03' and 
                      sd29_i_prontuario != $aChaveProntuarios[$intAgenda] 
                    ")
                );
  $resProntprocedExames    = $clprontproced->sql_record( 
                  $clprontproced->sql_query_ext(null,
                    "*, m.z01_nome as nome_profissional"," sd29_d_data desc limit 21", 
                    " sd24_i_numcgs = {$objRetorno->prontuario[0]->sd24_i_numcgs} and 
                      substr( sd63_c_procedimento, 1, 2 ) = '02' and 
                      sd29_i_prontuario != $aChaveProntuarios[$intAgenda] 
                    ")
                  );
  $objRetorno->prontproced = db_utils::getColectionByRecord($resProntproced, true, false);
  $objRetorno->prontprocedCon = db_utils::getColectionByRecord($resProntprocedConsultas, true, false);
  $objRetorno->prontprocedExa = db_utils::getColectionByRecord($resProntprocedExames, true, false);
  
  //$dia_nasc = substr ( $objRetorno->prontuario[0]->z01_d_nasc, 0, 2 );
  //$mes_nasc = substr ( $objRetorno->prontuario[0]->z01_d_nasc, 3, 2 );
  //$ano_nasc = substr ( $objRetorno->prontuario[0]->z01_d_nasc, 6, 4 );
  //$idade    = calcage ( $dia_nasc, $mes_nasc, $ano_nasc, date ( "d" ), date ( "m" ), date ( "Y" ) );
  //$idade    = explode(",",$idade);   
  //$objRetorno->idade       = $idade[0]; 
  /* DATA E HORA DA EMISSÃO */
  if ($oDadosConfig->s103_i_datahorafaa == 2) {
    
    $objRetorno->prontuario[0]->sd24_d_cadastro = date('d/m/Y', db_getsession('DB_datausu'));
    $objRetorno->prontuario[0]->sd24_c_cadastro = date('H:i');
    
  }

?>
  <tr>
    <td height="935" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="style19" >
      <tr>
        <td width="100%" height="15" colspan="4"></td>
      </tr>
      <tr>
        <td width="10%" height="18"></td>
        <td width="40%" height="18" nowrap="nowrap"><?=$objRetorno->prontuario[0]->sd02_c_siasus ?></td>
        <td width="40%" height="18" nowrap="nowrap"><?=$objRetorno->prontuario[0]->z01_v_nome ?></td>
        <td width="08%" height="18" nowrap="nowrap"><?=$objRetorno->prontuario[0]->idade ?></td>
        <td width="02%" height="18" nowrap="nowrap" align="right"><?=$objRetorno->prontuario[0]->z01_v_sexo ?></td>      
      </tr>
      <tr>
        <td width="10%" height="18"></td>
        <td width="40%" height="18"></td>
        <td width="40%" height="18"></td>
        <td width="08%" height="18"></td>
        <td width="02%" height="18"></td>      
      </tr>
      <tr>
        <td width="10%" height="18"></td>
        <td width="40%" height="18" nowrap="nowrap"><?=$objRetorno->prontuario[0]->descrdepto ?></td>
        <td width="40%" height="18" nowrap="nowrap"><?=$objRetorno->prontuario[0]->z01_v_ender.", ".$objRetorno->prontuario[0]->z01_i_numero ?></td>
        <td width="08%" height="18"></td>
        <td width="02%" height="18"></td>      
      </tr>
      <tr>
        <td width="10%" height="18"></td>
        <td width="40%" height="18" nowrap="nowrap"><?=$objRetorno->prontuario[0]->sd24_d_cadastro."  ".$objRetorno->prontuario[0]->sd24_c_cadastro."  ".$objRetorno->prontuario[0]->sd24_i_codigo ?></td>
        <td width="40%" height="18" nowrap="nowrap"><?=$objRetorno->prontuario[0]->z01_v_munic ?></td>
        <td width="08%" height="18" align="center"><?=$objRetorno->prontuario[0]->z01_v_uf ?></td>
        <td width="02%" height="18" align="right"><?=$objRetorno->prontuario[0]->z01_i_cgsund ?></td>      
      </tr>
      <tr>
        <td width="100%" height="15" colspan="4" class="style12"><?=($intAgenda+1) ?></td>
      </tr>
      <tr>
        <td width="50%" height="15"  nowrap="nowrap" colspan="2"><?=$objRetorno->prontproced[0]->nome_profissional?></td>
        <td width="40%" height="15"></td>
        <td width="08%" height="15"></td>
        <td width="02%" height="15"></td>      
      </tr>
      <tr>
        <td width="50%" height="15" colspan="2" class="style37" nowrap="nowrap"></td>
        <td width="40%" height="15"></td>
        <td width="08%" height="15"></td>
        <td width="2%" height="15"></td>      
      </tr>
      <tr>
        <td width="10%" height="18"  nowrap="nowrap" class="style37"><?=@$objRetorno->prontproced[0]->sd63_c_procedimento ?></td>
        <td width="40%" height="18"  nowrap="nowrap" class="style37"><?=@$objRetorno->prontproced[0]->sd63_c_nome ?></td>
        <td width="40%" height="18"></td>
        <td width="08%" height="18"></td>
        <td width="02%" height="18"></td>      
      </tr>
      <tr>
        <td width="100%" height="18" colspan="4"></td>
      </tr>
      <tr>
        <td width="100%" height="18" colspan="4"></td>
      </tr>
      <tr>
        <td width="100%" height="18" colspan="4"></td>
      </tr>
      <? for( $x=0; $x<10; $x++){ ?>
      <tr>
        <td width="10%" height="25" class="style37"> <?=@$objRetorno->prontprocedCon[$x]->sd29_d_data ?></td>
        <td width="90%" height="25" class="style37" colspan="3"> <?=@$objRetorno->prontprocedCon[$x]->nome_profissional ?></td>
      </tr>
      <?} ?>
      <tr>
        <td width="100%" height="18" colspan="4"></td>
      </tr>
      <? for( $x=0; $x<17; $x++){ ?>
      <tr>
        <td width="10%" height="25" class="style37"><?=@$objRetorno->prontprocedExa[$x]->sd29_d_data ?></td>
        <td width="90%" height="25" class="style37" colspan="3" nowrap="nowrap"><?=@substr( $objRetorno->prontprocedExa[$x]->sd63_c_nome, 0, 40 ) ?></td>
      </tr>
      <?} ?>
    </table>
  </td>
  </tr>
<?} ?>  
</table>

<script language="JavaScript">
  self.print();
</script>  
  
</body>
</html>