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
  $sSql          = $clprontuarios->sql_query_ext ($aChaveProntuarios[$intAgenda] );
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
                                        "*",
                                        "",
                                        " sd29_i_prontuario = $aChaveProntuarios[$intAgenda] ");
  $resProntproced          = $clprontproced->sql_record ($sSql);
  $objRetorno->prontproced = db_utils::getColectionByRecord($resProntproced, true, false);
  
  $dia_nasc = substr ( $objRetorno->prontuario[0]->z01_d_nasc, 0, 2 );
  $mes_nasc = substr ( $objRetorno->prontuario[0]->z01_d_nasc, 3, 2 );
  $ano_nasc = substr ( $objRetorno->prontuario[0]->z01_d_nasc, 6, 4 );
  $idade    = isset ( $agendamentofa ) ? calcage ( $dia_nasc, $mes_nasc, $ano_nasc, date ( "d" ), date ( "m" ), date ( "Y" ) ) : "";
  $idade    = explode(",",$idade);   
  $objRetorno->idade       = $idade[0]; 
  /* DATA E HORA DA EMISSÃO */
  if ($oDadosConfig->s103_i_datahorafaa == 2) {
    
    $objRetorno->prontuario[0]->sd24_d_cadastro = date('d/m/Y', db_getsession('DB_datausu'));
    $objRetorno->prontuario[0]->sd24_c_cadastro = date('H:i');
    
  }

?>
  <tr>
    <td height="865px" valign="top"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" >
    
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="style19" >
      <tr>
        <td width="100%" height="110" colspan="3"></td>
      </tr>
      <tr>
        <td width="60%" height="18" nowrap="nowrap">&nbsp;</td>
        <td width="20%" height="18" nowrap="nowrap">
          <?=$objRetorno->prontuario[0]->sd24_d_cadastro?>
        </td>
        <td width="20%" height="18" nowrap="nowrap">
          <?=$objRetorno->prontuario[0]->sd24_i_codigo?>
        </td>
      <tr>
        <td width="60%" height="20" nowrap="nowrap">&nbsp;</td>
        <td width="20%" height="20" nowrap="nowrap">
          <?=substr($objRetorno->prontuario[0]->sd24_c_cadastro,0,5)?>
        </td>
        <td width="20%" height="20" nowrap="nowrap">&nbsp;</td>
      </tr>
      <tr>
        <td width="100%" height="30" colspan="3"></td>
      </tr>
    </table>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="style19" >
      <tr>
        <td width="10%" height="20" nowrap="nowrap">&nbsp;</td>
        <td width="50%" height="20" nowrap="nowrap">
          <?=$objRetorno->prontuario[0]->z01_v_nome?>
        </td>
        <td width="40%" height="20" nowrap="nowrap">
          <?=$objRetorno->prontuario[0]->z01_d_nasc?>
        </td>
      </tr>
      <tr>
        <td width="10%" height="20" nowrap="nowrap">&nbsp;</td>
        <td width="50%" height="20" nowrap="nowrap">
          <?=$objRetorno->prontuario[0]->z01_v_ender.", ".$objRetorno->prontuario[0]->z01_i_numero ?>
        </td>
        <td width="40%" height="20" nowrap="nowrap">
          <?=$objRetorno->prontuario[0]->z01_v_bairro?>
        </td>
      </tr>
      <tr>
        <td width="10%" height="20" nowrap="nowrap">&nbsp;</td>
        <td width="50%" height="20" nowrap="nowrap">
          <?=$objRetorno->prontuario[0]->z01_v_munic ?>
        </td>
        <td width="40%" height="20" nowrap="nowrap">
          <?=$objRetorno->prontuario[0]->z01_v_cep?>
        </td>
      </tr>
      <tr>
        <td width="10%" height="20" nowrap="nowrap">&nbsp;</td>
        <td width="50%" height="20" nowrap="nowrap">
          <?=$objRetorno->prontuario[0]->z01_v_pai?>
        </td>
        <td width="40%" height="20" nowrap="nowrap">
          <?=$objRetorno->prontuario[0]->z01_v_mae?>
        </td>
      </tr>
    </table>
    <br><br><br><br><br><br>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="style19" >
      <?for($iY=0;$iY<10;$iY++){?>
      <tr>
        <td width="100%" height="20" nowrap="nowrap">
          <?if(isset($objRetorno->prontproced[$iY]->sd29_t_tratamento)){
             echo $objRetorno->prontproced[$iY]->sd29_t_tratamento; 
            }?>
        </td>
      </tr>
      <?}?>
    </table>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="style19" >
      <tr>
        <td width="75%" height="30" nowrap="nowrap">
          <?if(isset($objRetorno->prontproced[0]->sd70_c_nome)){
              echo $objRetorno->prontproced[0]->sd70_c_nome;
            }?>
        </td>
        <td width="25%" height="30" nowrap="nowrap">
          <?if(isset($objRetorno->prontproced[0]->sd70_c_cid)){
              echo $objRetorno->prontproced[0]->sd70_c_cid;
            }?>
        </td>
      </tr>
    </table>
    <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" class="style19" >
      <tr>
        <td width="70%" height="60" nowrap="nowrap">
          <?if($objRetorno->prontproced[0]->sd63_c_nome){
              echo $objRetorno->prontproced[0]->sd63_c_nome;
            }?>
        </td>
        <td width="30%" height="60" nowrap="nowrap">
          <?if($objRetorno->prontproced[0]->sd63_c_procedimento){
              echo $objRetorno->prontproced[0]->sd63_c_procedimento;
            }?>
        </td>
      </tr>
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