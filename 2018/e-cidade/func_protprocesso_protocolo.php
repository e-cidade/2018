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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_utils.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";
require_once "classes/db_protprocesso_classe.php";

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clprotprocesso = new cl_protprocesso;
$clprotprocesso->rotulo->label("p58_codproc");
$clprotprocesso->rotulo->label("p58_requer");
$clprotprocesso->rotulo->label("p58_numero");
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  </head>
<body class="body-default">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="left" nowrap title="<?=$Tp58_codproc?>">
              <?=$Lp58_codproc?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
    		       db_input("p58_codproc",10,$Ip58_codproc,true,"text",4,"","chave_p58_codproc");
    		      ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="left" nowrap title="<?=$Tp58_numero?>">
              <?=$Lp58_numero?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
               db_input("p58_numero",10,$Ip58_numero,true,"text",4,"","chave_p58_numero");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="left" nowrap title="<?=$Tp58_requer?>">
              <?=$Lp58_requer?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
      		      db_input("p58_requer",50,$Ip58_requer,true,"text",4,"","chave_p58_requer");
      		    ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_proc.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sLeft = "";
      $where = " p58_instit = ".db_getsession("DB_instit");

      if(isset($grupo) && trim($grupo) != '' ){
      	$where .= " and tipoproc.p51_tipoprocgrupo = $grupo";
      }
      if ( isset($tipo) && trim($tipo) != '' ) {
        $where .= " and p58_codigo = {$tipo} ";
      }

      /**
       * Se a variável "$lAnoAtual" for true, filtra os processos do ano setado na sessão
       */
      if(!empty($lAnoAtual) && $lAnoAtual){
      	$where .= " and p58_ano = " . db_getsession("DB_anousu");
      }

      if ( isset($apensado) && trim($apensado) != '' ) {
        $where .= " and not exists ( select *
                                       from processosapensados
                                      where p30_procapensado  = p58_codproc
                                         or p30_procprincipal = p58_codproc limit 1)
                    and p58_codproc != {$apensado} ";
      }

      $sCampoInstituicao  = " case when (p58_codandam = 0 or p58_codandam is null)              ";
      $sCampoInstituicao .= "   then (select nomeinst from db_config where codigo = p58_instit) ";
      $sCampoInstituicao .= "   else                                                            ";
      $sCampoInstituicao .= "     (select nomeinst                                              ";
      $sCampoInstituicao .= "        from procandam                                             ";
      $sCampoInstituicao .= "             inner join db_depart on p61_coddepto = coddepto       ";
      $sCampoInstituicao .= "             inner join db_config on instit = codigo               ";
      $sCampoInstituicao .= "       where p61_codandam = p58_codandam)                          ";
      $sCampoInstituicao .= " end as instit                                                     ";

      if (!isset($pesquisa_chave)) {

        $campos  = "p58_codproc as dl_código_do_processo, cast(p58_numero||'/'||p58_ano as varchar) as p58_numero,z01_numcgm as DB_p58_numcgm,";
        $campos .= "z01_nome as dl_nome_ou_razão_social,z01_nome, p58_dtproc,p51_descr,p58_obs,p58_requer as DB_p58_requer, $sCampoInstituicao";

        /**
         * Campo de pesquisa
         * Informou variavel pelo $_GET 'p58_codproc'
         */
        if ( !empty($oGet->sCampoPesquisa) && $oGet->sCampoPesquisa == 'p58_codproc' ) {

          $campos  = "p58_codproc, cast(p58_numero||'/'||p58_ano as varchar) as p58_numero,z01_numcgm as DB_p58_numcgm,";
          $campos .= "z01_nome, p58_dtproc,p51_descr,p58_obs,p58_requer as DB_p58_requer, $sCampoInstituicao";
        }

        if (isset($chave_p58_numcgm) && (trim($chave_p58_numcgm)!="") ) {
	         $sql = $clprotprocesso->sql_query(null,$campos,"p58_codproc desc","p58_numcgm = $chave_p58_numcgm  and $where");
        } else if(isset($chave_p58_codproc) && (trim($chave_p58_codproc)!="") ) {

        	 if(trim($where) != "") {
        	 	$where .= " and p58_codproc = ".$chave_p58_codproc;
        	 } else {
        	 	$where .= " p58_codproc = ".$chave_p58_codproc;
        	 }

	         $sql = $clprotprocesso->sql_query($chave_p58_codproc,$campos,"p58_codproc desc", $where);

        } else if(isset($chave_p58_requer) && (trim($chave_p58_requer)!="") ){
	         $sql = $clprotprocesso->sql_query("",$campos,"p58_codproc desc"," p58_requer like '$chave_p58_requer%'  and $where");
	      } else if (isset($chave_p58_numero) && (trim($chave_p58_numero)!="")) {

           $aPartesNumero = explode("/", $chave_p58_numero);
           $iAno = db_getsession("DB_anousu");
           if (count($aPartesNumero) > 1) {
              $iAno = $aPartesNumero[1];
           }
           $iNumero = $aPartesNumero[0];
           $where  .= " and p58_ano = {$iAno} and p58_numero = '{$iNumero}'";
           $sql     = $clprotprocesso->sql_query("",$campos,"p58_codproc desc",
                                                "$where ");
	      } else if(isset($chave_unica) and ($chave_unica != '')) {

	         $sql = $clprotprocesso->sql_query($chave_unica, $campos);
        } else {
           $sql = $clprotprocesso->sql_query("",$campos,"p58_codproc desc",$where);
        }
      	$repassa = array();
        if (isset($chave_p58_codproc)) {
        	$repassa = array("chave_p58_codproc"=>$chave_p58_codproc);
        }

        db_lovrot($sql." ",15,"()","",$funcao_js,"","NoMe",$repassa);


      } else {

        if ( $pesquisa_chave != null && $pesquisa_chave != "" ) {

          $aPesquisa = explode("/", $pesquisa_chave);
          $iAno = db_getsession("DB_anousu");

          if (count($aPesquisa) > 1) {
            $iAno = $aPesquisa[1];
          }

          $sCampoPesquisa = 'p58_numero';

          /**
           * Campo de pesquisa
           * Informou variavel pelo $_GET 'p58_codproc'
           */
          if ( !empty($oGet->sCampoPesquisa) ) {
            $sCampoPesquisa = $oGet->sCampoPesquisa;
          }

          $sSql   = $clprotprocesso->sql_query("","*","","{$sCampoPesquisa} = '{$aPesquisa[0]}' and p58_ano = {$iAno} and $where");
          $result = $clprotprocesso->sql_record($sSql);

          if($clprotprocesso->numrows!=0) {

            db_fieldsmemory($result,0);

	    			if (isset($retobs) ) {
	    				echo "<script>".$funcao_js."('$p58_numcgm','$p58_obs',false);</script>";

	    			} else {

              $sCampoRetorno = $p58_numero . '/' . $p58_ano;

              if ( !empty($oGet->sCampoRetorno) ) {

                $sCampoRetorno = $oGet->sCampoRetorno;
                $sCampoRetorno = $$sCampoRetorno;
              }
             	echo "<script>". $funcao_js . "('$sCampoRetorno', '$z01_nome', false); </script> ";
	    			}
          } else {
	          echo "<script>".$funcao_js."('','Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }

        } else {
	       echo "<script>".$funcao_js."('','',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>