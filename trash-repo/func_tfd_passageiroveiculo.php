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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_tfd_passageiroveiculo_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoTfdPassageiroVeiculo = new cl_tfd_passageiroveiculo;
$oRotulo                  = new rotulocampo;
$oDaoTfdPassageiroVeiculo->rotulo->label('tf19_i_cgsund');
$oRotulo->label('z01_v_nome');

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
	    <form name="form2" method="post" action='' >
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ttf19_i_codigo?>">
              <?=$Ltf19_i_cgsund?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input('tf19_i_cgsund', 10, $Itf19_i_cgsund, true, 'text', 4, '', 'chave_tf19_i_cgsund');
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Ttf19_i_codigo?>">
              <?=$Lz01_v_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		          db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 4, '', 'chave_z01_v_nome');
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" 
                onClick="parent.db_iframe_tfd_passageiroveiculo.hide();">
             </td>
          </tr>
        </table>
      </form>
    </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sValidos        = '';
      $sSepValidos     = '';
      $sFica           = '';
      $sSepFica        = '';
      $sVeiculoDest    = '';
      $sSepVeiculoDest = '';
      $sJaIncluidos    = '';
      $sSepJaIncuidos  = '';
      $sValidHora      = '';
      $sSepValidaHora  = '';

      if (isset($chave_validos)) {

        $sValidos    = ' tf19_i_valido = 1';
        $sSepValidos = ' and ';
        
      }

      if (isset($chave_fica)) {

        $sFica    = ' tf19_i_fica = 1';
        $sSepFica = ' and ';
        
      }

      if (isset($chave_tf19_i_veiculodestino)) {

        $sVeiculoDest    = ' tf19_i_veiculodestino != '.$chave_tf19_i_veiculodestino;
        $sSepVeiculoDest = ' and ';
        
      }

      if (isset($chave_ja_incluidos)) {

        $sJaIncluidos    = ' not exists (select suba.tf31_i_codigo from tfd_passageiroretorno as suba ';
        $sJaIncluidos   .= ' inner join tfd_passageiroveiculo subb on suba.tf31_i_passageiroveiculo = subb.tf19_i_codigo ';
        $sJaIncluidos   .= ' where subb.tf19_i_codigo = tfd_passageiroveiculo.tf19_i_codigo and suba.tf31_i_valido = 1) ';
        $sSepJaIncluidos = ' and ';
        
      }

      if (isset($chave_valida_hora)) {
          
        /*
        A data do retorno do veiculo tem que ser maior que a data de saída do paciente, ou seja, 
        o paciente já tem que ter saído para poder retornar no veículo. (óbvio)
        */
        $sValidaHora    = ' exists (select subc.tf18_i_codigo ';
        $sValidaHora   .= '           from tfd_veiculodestino as subc ';
        $sValidaHora   .= '              where subc.tf18_i_codigo =  '.$chave_tf19_i_veiculodestino;
        $sValidaHora   .= '                and ((tfd_veiculodestino.tf18_d_datasaida < subc.tf18_d_dataretorno) ';
        $sValidaHora   .= '                   or (tfd_veiculodestino.tf18_d_datasaida = subc.tf18_d_dataretorno ';
        $sValidaHora   .= '                      and tfd_veiculodestino.tf18_c_horasaida < subc.tf18_c_horaretorno)))';
        $sSepValidaHora = ' and ';
        
      }

      if (!isset($pesquisa_chave)) {

        if (isset($campos) == false) {

          if (file_exists("funcoes/db_func_tfd_passageiroveiculo.php") == true) {
            require_once("funcoes/db_func_tfd_passageiroveiculo.php");
          } else {
            $campos = 'tfd_passageiroveiculo.*';
          }

        }
        if (isset($chave_tf19_i_cgsund) && (trim($chave_tf19_i_cgsund) != '')) {

          $sSql = $oDaoTfdPassageiroVeiculo->sql_query(null, $campos, 'cgs_und.z01_v_nome', 
                                                       'tf19_i_cgsund = '.$chave_tf19_i_cgsund.
                                                       $sSepValidos.$sValidos.
                                                       $sSepVeiculoDest.$sVeiculoDest.
                                                       $sSepFica.$sFica.
                                                       $sSepJaIncluidos.$sJaIncluidos.
                                                       $sSepValidaHora.$sValidaHora
                                                      );

        } elseif (isset($chave_z01_v_nome) && (trim($chave_z01_v_nome) != '')) {

	        $sSql = $oDaoTfdPassageiroVeiculo->sql_query('', $campos, 'cgs_und.z01_v_nome', 
                                                       " cgs_und.z01_v_nome like '$chave_z01_v_nome%' ".
                                                       $sSepValidos.$sValidos.
                                                       $sSepVeiculoDest.$sVeiculoDest.
                                                       $sSepFica.$sFica.
                                                       $sSepJaIncluidos.$sJaIncluidos.
                                                       $sSepValidaHora.$sValidaHora
                                                      );

        } else {
         
          $sSep1 = ' and ';
          $sSep2 = ' and ';
          $sSep3 = ' and ';
          $sSep4 = ' and ';
          if (empty($sVeiculoDest)) {
            $sSep1 = '';
          } 
          if (empty($sFica)) {
            $sSep2 = '';
          } 
          if (empty($sJaIncluidos)) {
            $sSep3 = '';
          }
          if (empty($sValidaHora)) {
            $sSep4 = '';
          }
          $sSql = $oDaoTfdPassageiroVeiculo->sql_query('', $campos, 'cgs_und.z01_v_nome',
                                                       $sValidos.$sSep1.$sVeiculoDest.
                                                       $sSep2.$sFica.
                                                       $sSep3.$sJaIncluidos.
                                                       $sSep4.$sValidaHora
                                                      );

        }

        if (isset($nao_mostra)) {
          
          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $oDaoTfdPassageiroVeiculo->sql_record($sSql);
           if ($oDaoTfdPassageiroVeiculo->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_tf19_i_cgsund.") não Encontrado');</script>");
           } else {
            
             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';
             for($iCont = 1; $iCont < count($aFuncao); $iCont++) {

               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep     = ', ';

             }

             $sFuncao  = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die("<script>".$sFuncao.'</script>');

          }

        }

        $repassa = array();
        if (isset($chave_tf19_i_codigo)) {
          $repassa = array('chave_tf19_i_cgsund' => $chave_tf19_i_cgsund);
        }
        db_lovrot($sSql, 15, "()", '', $funcao_js, '', "NoMe", $repassa);

      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != '') {

          $sSql   = $oDaoTfdPassageiroVeiculo->sql_query($pesquisa_chave);
          $result = $oDaoTfdPassageiroVeiculo->sql_record();
          if ($oDaoTfdPassageiroVeiculo->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."('$tf19_i_codigo', false);</script>";

          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
          }

        } else {
	        echo "<script>".$funcao_js."('', false);</script>";
        }

      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if (!isset($pesquisa_chave)) {
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2", "chave_tf19_i_codigo", true, 1, "chave_tf19_i_codigo", true);
</script>