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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_cidadaofamilia_classe.php");

$iDepartamento = db_getsession("DB_coddepto");

db_postmemory($_POST);
db_postmemory($_GET);
$oGet = db_utils::postMemory($_GET);

/**
 * Para ambas forma de pesquisa:
 *   $lSomenteResponsavel = traz somente os responsáveis da família
 * 
 * Variáveis de filtro quando informado {$pesquisa_chave}:
 *   $lCidadao           = código do cidadao
 *   $lNis               = código do nis
 *   $lCadastroUnico     = cadastro único
 *   $lFamiliaCadUnico   = familia do cadastro único
 */

$clcidadaofamilia = new cl_cidadaofamilia;
$oRotuloCampo     = new rotulocampo();
$oRotuloCampo->label("ov02_sequencial");
$oRotuloCampo->label("ov02_nome");
$oRotuloCampo->label("as02_nis");
$oRotuloCampo->label("as02_codigounicocidadao");
$oRotuloCampo->label("as15_codigofamiliarcadastrounico");
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
       <table width="35%" align="center" >
	     <form name="form2" method="post" action="" >
          <tr title="Pesquise um cidadão">  
            <td align="right" nowrap="nowrap" class="bold" >Cidadão:</td>
            <td nowrap="nowrap"> 
              <?
		            db_input("ov02_sequencial", 10, $Iov02_sequencial, true, "text", 4, "", "chave_ov02_sequencial");
		          ?>
            </td>
            <td nowrap="nowrap"> 
              <?
		            db_input("ov02_nome", 30, $Iov02_nome, true, "text", 4, "", "chave_ov02_nome");
		          ?>
            </td>
          </tr>
          <tr title="Nis do Cidadão">  
            <td align="right" nowrap="nowrap" class="bold" >NIS:</td>
            <td align="left" nowrap="nowrap" colspan="2"> 
              <?
		            db_input("as02_nis", 10, $Ias02_nis, true, "text", 4, "", "chave_as02_nis");
		          ?>
            </td>
          </tr>
          <tr title="código do cadastro único">  
            <td align="right" nowrap="nowrap" class="bold" >Cadastro único:</td>
            <td align="left" nowrap="nowrap" colspan="2"> 
              <?
		            db_input("as02_codigounicocidadao", 10, $Ias02_codigounicocidadao, true, "text", 4, "", 
		                     "chave_as02_codigounicocidadao");
		          ?>
            </td>
          </tr>
          <tr title="Código da família no Cadastro Único"> 
            <td align="right" nowrap="nowrap" class="bold" >
              Família no Cadastro Único
            </td>
            <td align="left" nowrap="nowrap" colspan="2"> 
              <?
		            db_input("as15_codigofamiliarcadastrounico", 10, $Ias15_codigofamiliarcadastrounico, true, "text", 4,
		                     "", "chave_as15_codigofamiliarcadastrounico");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="3" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cidadao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $campos  = "distinct cidadaofamilia.as04_sequencial, cidadaofamiliacadastrounico.as15_codigofamiliarcadastrounico, ";      
      $campos .= "cidadao.ov02_sequencial, cidadao.ov02_nome, ";
      $campos .= "cidadaocadastrounico.as02_sequencial, cidadaocadastrounico.as02_codigounicocidadao, ";
      $campos .= "cidadaocadastrounico.as02_nis";
      
      $sOrder = "as04_sequencial";
      if (!isset($pesquisa_chave)) {
        
        
        $aWhere = array();
        /**
         * Adiciona os filtros informados em um array
         */
        if (isset($chave_ov02_sequencial) && !empty($chave_ov02_sequencial)) {
          $aWhere[] = "ov02_sequencial = {$chave_ov02_sequencial}";
        } 
        if (isset($chave_ov02_nome) && !empty($chave_ov02_nome)) {
          $aWhere[] = " trim(ov02_nome) ilike trim('{$chave_ov02_nome}%')";
        } 
        if (isset($chave_as02_nis) && !empty($chave_as02_nis)) {
          $aWhere[] = " trim(as02_nis) ilike trim('{$chave_as02_nis}%')";
        } 
        if (isset($chave_as02_codigounicocidadao) && !empty($chave_as02_codigounicocidadao)) {
          $aWhere[] = " trim(as02_codigounicocidadao) ilike trim('%{$chave_as02_codigounicocidadao}%')";
        } 
        if (isset($chave_as15_codigofamiliarcadastrounico) && !empty($chave_as15_codigofamiliarcadastrounico)) {
          $aWhere[] = " trim(as15_codigofamiliarcadastrounico) ilike trim('%{$chave_as15_codigofamiliarcadastrounico}%')";
        }
        
        /**
         * Adiciona filtro para trazer somente os responsáveis da família
         */
        if (isset($oGet->lSomenteResponsavel)) {
          $aWhere[] = " as03_tipofamiliar = 0 ";
        }
        
        /**
         * Filtro para trazer somente cidadaos vinculados ao departamento
         */
        if (isset($oGet->lSomenteCidadaoDepartamento)) {
          $aWhere[] = " as16_db_depart = {$iDepartamento}";
        }
        
        /**
         * Filtro para trazer somente familias que possuam vinculo com um departamento
         */
        if (isset($oGet->lSomenteFamiliaVinculada)) {
          $aWhere[] = " as23_sequencial is not null and as23_ativo is true";
        }
        
        $sWhere  = implode(" and ", $aWhere);
        $sSql    = $clcidadaofamilia->sql_query_completa(null, $campos, $sOrder, $sWhere);
        $repassa = array();

        db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $sWhere = '';

          /**
           * Quando realizado a pesquisa pela {$pesquisa_chave}, validamos identificamos pelo parâmetro setado 
           * quem é {$pesquisa_chave}.
           */
          if (isset($lCidadao)) {
            $sWhere = " ov02_sequencial = {$pesquisa_chave}";  
          } else if (isset($lNis)) {
            $sWhere = " trim(as02_nis) = trim('{$pesquisa_chave}')";
          } else if (isset($lCadastroUnico)) {
            $sWhere = " trim(as02_codigounicocidadao) ilike trim('%{$pesquisa_chave}')";
          } else if (isset($lFamiliaCadUnico)) {
            $sWhere  = " trim(as15_codigofamiliarcadastrounico) ilike trim('%$pesquisa_chave}')";
          } else if (isset($lFamilia)) {
            $sWhere  = " as04_sequencial = {$pesquisa_chave}";
          }
          
          /**
           * Adiciona filtro para trazer somente os responsáveis da família
           */
          if (isset($oGet->lSomenteResponsavel)) {
            $sWhere .= " and as03_tipofamiliar = 0"; // Retorna o responsável da família
          }
          
          /**
           * Filtro para trazer somente cidadaos vinculados ao departamento
           */
          if (isset($oGet->lSomenteCidadaoDepartamento)) {
            $sWhere .= " and as16_db_depart = {$iDepartamento}";
          }
          
          /**
           * Filtro para trazer somente familias que possuam vinculo com um departamento
           */
          if (isset($oGet->lSomenteFamiliaVinculada)) {
            $sWhere .= " and as23_sequencial is not null and as23_ativo is true";
          }
          
          $sSql   = $clcidadaofamilia->sql_query_completa(null, $campos, $sOrder, $sWhere);
          $result = $clcidadaofamilia->sql_record($sSql);
          
          if ($clcidadaofamilia->numrows > 0) {
          	
            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."(false, 
                                         '$ov02_sequencial', 
                                         '$ov02_nome', 
                                         '$as02_nis', 
                                         '$as02_codigounicocidadao', 
                                         '$as15_codigofamiliarcadastrounico', 
                                         '$as04_sequencial', 
                                         '$as02_sequencial');</script>";
          } else {
	         echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado');</script>";
          }
        } else {
	        echo "<script>".$funcao_js."(true, '');</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>