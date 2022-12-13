<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clprontuarios = new cl_prontuarios;
$clprontuarios->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");
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
      <form name="form2" method="post" action="" >
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tsd24_i_codigo?>">
              <?=$Lsd24_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
              db_input( "sd24_i_codigo", 11, $Isd24_i_codigo, true, "text", 4, "", "chave_sd24_i_codigo" );
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tsd24_i_ano.'|'.$Tsd24_i_mes.'|'.$Tsd24_i_seq?>">
              <?=$Lsd24_i_ano.'|'.$Lsd24_i_mes.'|'.$Lsd24_i_seq?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?php
              db_input( "sd24_i_ano", 4, $Isd24_i_ano, true, "text", 4, "", "chave_sd24_i_ano" );
              db_input( "sd24_i_mes", 2, $Isd24_i_mes, true, "text", 4, "", "chave_sd24_i_mes" );
              db_input( "sd24_i_seq", 5, $Isd24_i_seq, true, "text", 4, "", "chave_sd24_i_seq" );
              ?>
            </td>
          </tr>

          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tz01_v_nome?>">
              <?=$Lz01_v_nome?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
              db_input( "z01_v_nome", 40, $Iz01_v_nome, true, "text", 4, "", "chave_z01_v_nome" );
              ?>
            </td>
          </tr>

          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_prontuarios.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)) {

        if(isset($campos)==false) {

          if(file_exists("funcoes/db_func_prontuarios.php")==true) {
            include("funcoes/db_func_prontuarios.php");
          } else {
            $campos = "prontuarios.*";
          }
        }

        $sWhere = "sd24_c_digitada = 'N'";
        $sSep   = ' and ';

        if(isset($lFiltraTfd)) {

          $sSep   = ' and ';
          $sWhere = ' sd24_i_codigo not in (select tf29_i_prontuario from tfd_prontpedidotfd) ';
        }

        if(isset($chave_sd24_i_codigo) && (trim($chave_sd24_i_codigo)!="")) {

          if(isset($chave_profissional) && trim($chave_profissional) != '' && isset($chave_unidade) && !empty($chave_unidade)) {
            $sql = $clprontuarios->sql_query_faas_profissional($chave_sd24_i_codigo,$chave_profissional,$chave_unidade,$campos,"sd24_i_codigo");
          } else {
            $sql = $clprontuarios->sql_query('',$campos,"sd24_i_codigo"," sd24_i_codigo = $chave_sd24_i_codigo $sSep$sWhere");
          }

        } else if(isset($chave_sd24_i_ano) && (trim($chave_sd24_i_ano)!="") &&
                 isset($chave_sd24_i_mes) && (trim($chave_sd24_i_mes)!="") &&
                 isset($chave_sd24_i_seq) && (trim($chave_sd24_i_seq)!="")
                ) {

          if(isset($chave_profissional) && trim($chave_profissional) != '' && isset($chave_unidade) && !empty($chave_unidade)) {
            $sql = $clprontuarios->sql_query_faas_profissional(null,$chave_profissional,$chave_unidade,$campos,"sd24_i_codigo",
                                                                                                     "sd24_i_ano = $chave_sd24_i_ano and
                                                                                                      sd24_i_mes = $chave_sd24_i_mes and
                                                                                                      sd24_i_seq = $chave_sd24_i_seq");
          } else {
            $sql = $clprontuarios->sql_query("",$campos,"sd24_i_codigo"," sd24_i_ano = $chave_sd24_i_ano and
                                                                          sd24_i_mes = $chave_sd24_i_mes and
                                                                          sd24_i_seq = $chave_sd24_i_seq $sSep$sWhere");
          }
        } else if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
          
          if(isset($chave_profissional) && trim($chave_profissional) != '' && isset($chave_unidade) && !empty($chave_unidade)) {
            $sql = $clprontuarios->sql_query_faas_profissional(null,$chave_profissional,$chave_unidade,$campos,"sd24_i_codigo",
                                                                                           "cgs_und.z01_v_nome like '$chave_z01_v_nome%' ");
          } else {
            $sql = $clprontuarios->sql_query("",$campos,"cgs_und.z01_v_nome, sd24_i_codigo","cgs_und.z01_v_nome like '$chave_z01_v_nome%' $sSep$sWhere");
          }
        } else {

          if(isset($chave_profissional) && !empty($chave_profissional) && !isset($chave_sd24_i_ano) &&
            !isset($chave_sd24_i_mes) && !isset($chave_sd24_i_seq) && isset($chave_unidade) && !empty($chave_unidade)) {
            $sql = $clprontuarios->sql_query_faas_profissional(null,$chave_profissional,$chave_unidade,$campos,"sd24_i_codigo");
          }
        }

        $repassa = array();
        if(isset($chave_sd24_i_codigo)) {
          $repassa = array( "chave_sd24_i_codigo" => $chave_sd24_i_codigo, "chave_sd24_i_codigo" => $chave_sd24_i_codigo );
        }

        if(isset($nao_mostra)) {
          
          $sSep    = '';
          $aFuncao = explode('|', $funcao_js);
          $rs      = $clprontuarios->sql_record($sql);

           if($clprontuarios->numrows == 0) {
	           die('<script>'.$aFuncao[0]."('','Chave(".$chave_sd24_i_codigo.") não Encontrado');</script>");
           } else {
            
             db_fieldsmemory($rs, 0);
             $sFuncao = $aFuncao[0].'(';

             for($iCont = 1; $iCont < count($aFuncao); $iCont++) {

               $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
               $sSep     = ', ';
             }

             $sFuncao  = substr($sFuncao, 0, strlen($sFuncao));
             $sFuncao .= ');';
             die('<script>'.$sFuncao.'</script>');
          }
        }

        db_lovrot(@$sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if($pesquisa_chave!=null && $pesquisa_chave!="") {
          
          if(isset($chave_profissional) && trim($chave_profissional) != '' && isset($chave_unidade) && !empty($chave_unidade)) {
            $sql = $clprontuarios->sql_query_faas_profissional($pesquisa_chave,$chave_profissional,$chave_unidade);
          } else {
            $sql = $clprontuarios->sql_query($pesquisa_chave);
          }

          $result = $clprontuarios->sql_record($sql);
          if($clprontuarios->numrows!=0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z01_v_nome',false);</script>";
          } else {
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
          echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_sd24_i_codigo",true,1,"chave_sd24_i_codigo",true);
</script>