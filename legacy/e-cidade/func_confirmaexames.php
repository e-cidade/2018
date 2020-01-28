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

/**
 * 
 * @author I
 * @revision $Author: dbandre.mello $
 * @version $Revision: 1.4 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_sau_agendaexames_classe.php");
$oAgendaExames = new cl_sau_agendaexames;
$oRotulo = new rotulocampo;
$oRotulo->label('z01_i_cgsund');
$oRotulo->label('z01_v_nome');
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td align="center" valign="top">
        <table  border="0" align="center" cellspacing="0">
             <form name="form2" method="post" action="" >
          <tr> 
            <td  align="right" nowrap>
              <b>CGS:</b>
            </td>
            <td align="left" nowrap> 
               <?
                 db_input("z01_i_cgsund",10,$Iz01_i_cgsund,true,"text",4,"","chave_z01_i_cgsund");
              ?>
              
            </td>
            <td  align="right" nowrap>
              <b>Nome</b>
            </td>
            <td  align="left" nowrap> 
               <?
                db_input("z01_v_nome",30,$Iz01_v_nome,true,"text",4,"","chave_z01_v_nome");
               ?>
            </td>
          </tr>
          <tr>
            <td>
              <b>Data do Exame:</b>
            </td>
            <td>
               <?
               if (!isset($chave_s113_d_exame)) {
                 
                 $dia  = date("d", db_getsession("DB_datausu"));  
                 $mes  = date("m", db_getsession("DB_datausu"));  
                 $ano  = date("Y", db_getsession("DB_datausu"));
                 
               } else {
                 
                 $data = explode("/",$chave_s113_d_exame);
                 $dia  = $data[0];  
                 $mes  = $data[1];  
                 $ano  = $data[2];  
               }
               db_inputdata('s113_d_exame', $dia, $mes, $ano,true,3,"","chave_s113_d_exame");
               ?>
            </td>
          </tr>
          <tr> 
            <td colspan="4" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.lkp_exames.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_abonofalta.php")==true){
             include("funcoes/db_func_abonofalta.php");
           }else{
           $campos = "abonofalta.*";
           }
        }
        $sWhere = ""; 
        $campos = " distinct z01_i_cgsund, z01_v_nome, z01_v_cgccpf,z01_d_nasc ";
        if (isset($chave_z01_i_cgsund) && (trim($chave_z01_i_cgsund)!="") ){
          $sWhere  .= "s113_i_numcgs = {$chave_z01_i_cgsund}";
        }
        if(isset($chave_z01_v_nome) && (trim($chave_z01_v_nome)!="") ){
          
          if ($sWhere != "") {
            $sWhere .= " and ";
          }
         $sWhere .= "z01_v_nome like '$chave_z01_v_nome%'";
        }
        if (isset($chave_s113_d_exame) && trim($chave_s113_d_exame) != "") {
          
          $data = implode("-",array_reverse(explode("/",$chave_s113_d_exame)));
          if ($sWhere != "") {
            $sWhere .= " and ";
          }
          $sWhere .= "s113_d_exame = '{$data}'"; 
        }
        $sql = $oAgendaExames->sql_query(null, $campos,"z01_v_nome", $sWhere);
        $repassa = array();
        if (isset($chave_z01_i_cgsund)) { 
          $repassa = array("chave_z01_i_cgsund"=>$chave_z01_i_cgsund);
        }
        if (count($_POST) > 0) {
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clabonofalta->sql_record($clabonofalta->sql_query($pesquisa_chave));
          if($clabonofalta->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed80_i_codigo',false);</script>";
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
js_tabulacaoforms("form2","chave_ed80_i_codigo",true,1,"chave_ed80_i_codigo",true);
</script>