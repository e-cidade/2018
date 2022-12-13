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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoundmedhorario = db_utils::getdao('undmedhorario_ext');
$oDaoundmedhorario->rotulo->label("sd30_i_codigo");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
             <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd30_i_codigo?>">
              <?=$Lsd30_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
              db_input("sd30_i_codigo",10,$Isd30_i_codigo,true,"text",4,"","chave_sd30_i_codigo");
              ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_undmedhorario.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sSepEspecmed = '';
      $sEspecmed    = '';
      $sSepDatas    = '';
      $sDatas       = '';
      $sSepDia      = '';
      $sDia         = '';
      
      if (isset($sTipo)) {
        $sTipoFicha = " and sd30_c_tipograde = '".$sTipo."'";
      } else {
      	$sTipoFicha = "";
      }
      if(isset($chave_datas)) {
     
        $aDatas = explode(',', $chave_datas);
        $aDataIni = explode('/', $aDatas[0]);
        $aDataFim = explode('/', $aDatas[1]);
        $dDataIni = mktime(0, 0, 0, $aDataIni[1], $aDataIni[0], $aDataIni[2]);
        $dDataFim = mktime(0, 0, 0, $aDataFim[1], $aDataFim[0], $aDataFim[2]);
       
        $iDiaIni = date('w', $dDataIni) + 1; // somo 1 porque os dias da semana na tabela diasema começam em 1 e não em 0
        $iNumDias = (int)(($dDataFim - $dDataIni) / 86400);
        $iNumDias = $iNumDias > 6 ? 6 : $iNumDias;
        
        $sDias = ' ('.$iDiaIni;

        for($iCont = 0; $iCont < $iNumDias; $iCont++) {

          if($iDiaIni == 7) {
            $iDiaIni = 0;
          }
          $sDias .= ', '.++$iDiaIni;
        }
        $sDias .= ') ';
        
        $sDatas = ' sd30_i_diasemana in '.$sDias;
        $sSepDatas = ' and ';

      }

      if(isset($chave_vinculo)) {

        $sEspecmed = ' sd30_i_undmed = '.$chave_vinculo;
        $sSepEspecmed = ' and ';

      }

      if(isset($chave_dia)) {
     
        $aData      = explode('/', $chave_dia);
        $dData      = mktime(0, 0, 0, $aData[1], $aData[0], $aData[2]);
        $iDia       = date('w', $dData) + 1; // somo 1 porque os dias na tabela diasema começam em 1 e não em 0
        $dDataBanco = $aData[2].'-'.$aData[1].'-'.$aData[0];

        $sDia       = ' sd30_i_diasemana = '.$iDia;
        $sDia      .= ' and ((sd30_d_valinicial is null and sd30_d_valfinal is null)';
        $sDia      .= '   or (sd30_d_valinicial is null and sd30_d_valfinal >= \''.$dDataBanco.'\')';
        $sDia      .= '   or (sd30_d_valfinal is null and sd30_d_valinicial <= \''.$dDataBanco.'\')'; 
        $sDia      .= '   or (\''.$dDataBanco.'\' between sd30_d_valinicial and sd30_d_valfinal))'; 
        $sSepDia    = ' and ';

      }


      if(!isset($pesquisa_chave)) {

        if(isset($sCampos) == false) {

          $sCampos  = " undmedhorario.sd30_i_codigo,";
          $sCampos .= " undmedhorario.sd30_i_undmed,";
          $sCampos .= " unidademedicos.sd04_i_unidade,";
          $sCampos .= " diasemana.ed32_c_descr as sd30_i_diasemana,";
          $sCampos .= " undmedhorario.sd30_c_horaini,";
          $sCampos .= " undmedhorario.sd30_c_horafim,";
          $sCampos .= " undmedhorario.sd30_i_fichas,";
          $sCampos .= " sau_tipoficha.sd101_c_descr,";
          $sCampos .= " undmedhorario.sd30_i_reservas,";
          $sCampos .= " cgm.z01_nome ";

        }
        if(isset($chave_sd30_i_codigo) && (trim($chave_sd30_i_codigo) != '') ) {

          $sSql = $oDaoundmedhorario->sql_query_ext(null, $sCampos, 'sd30_i_codigo', 
                                                    "sd30_i_codigo = $chave_sd30_i_codigo".
                                                    "$sSepEspecmed $sEspecmed $sSepDatas $sDatas $sSepDia ". 
                                                    " $sDia $sTipoFicha ");

        } else {

          $sSql = $oDaoundmedhorario->sql_query_ext(null, $sCampos, 'sd30_i_codigo', "$sEspecmed$sSepDatas$sDatas".
                                                    "$sSepDia$sDia $sTipoFicha");

        }
        //die($sSql);

        if(isset($nao_mostra)) {
          
          $sSep = '';
          $aFuncao = explode('|', $funcao_js);
          $rs = $oDaoundmedhorario->sql_record($sSql);
           if($oDaoundmedhorario->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_sd30_i_codigo.") não Encontrado');</script>");
           } else {
            
             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';
             for($iCont = 1; $iCont < count($aFuncao); $iCont++) {

               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep = ', ';

             }
             $sFuncao = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die("<script>".$sFuncao.'</script>');

          }
        }

        $repassa = array();
        if(isset($chave_sd30_i_codigo)){
          $repassa = array("chave_sd30_i_codigo"=>$chave_sd30_i_codigo,"chave_sd30_i_codigo"=>$chave_sd30_i_codigo);
        }
        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $oDaoundmedhorario->sql_record($oDaoundmedhorario->sql_query($pesquisa_chave));
          if($oDaoundmedhorario->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_nome',false);</script>";
          }else{
                 echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
               echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_sd30_i_codigo",true,1,"chave_sd30_i_codigo",true);
</script>