<?
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_solicita_classe.php"));

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost = db_utils::postMemory($_POST,0);
$oGet  = db_utils::postMemory($_GET,0);

$clsolicita = new cl_solicita;
$clsolicita->rotulo->label("pc10_numero");
$clsolicita->rotulo->label("pc10_data");
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
	     <form name="form2" method="post" action = "" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc10_numero?>">
              <?=$Lpc10_numero?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("pc10_numero",10,$Ipc10_numero,true,"text",4,"","chave_pc10_numero");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tpc10_data?>">
              <?=$Lpc10_data?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
	         db_input("pc10_data",10,$Ipc10_data,true,"text",4,"","chave_pc10_data");
           db_input("param",10,"",false,"hidden",3);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_solicita.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sWhereInstituicao = "";

      if (!empty($oGet->lFiltraInstituicao) && $oGet->lFiltraInstituicao == true) {
        $sWhereInstituicao = " and solicita.pc10_instit = ".db_getsession("DB_instit");
      }

       $where_depart = "";
      if (isset($departamento) && trim($departamento)!="") {
        $where_depart .= " and pc10_depto=".db_getsession("DB_coddepto");
      }

      if (isset($nada)) {
        $where_depart = "";
      }

      if (isset($comcompilacaoprocessada)) {

        if ($comcompilacaoprocessada == 1) {
          $sExists = " not ";
        } else {
          $sExists = "  ";
        }
        $where_depart .= "  and {$sExists} exists(select 1
                                  from pcprocitem
                                       inner join solicitem on pc81_solicitem = pc11_codigo
                                 where pc11_numero = solicita.pc10_numero

                                    )";
      }

      if (isset ($anuladas)) {

        if ($anuladas  == 1) {
          $where_depart .= " and pc67_sequencial is null ";
        } else {
          $where_depart .= " and pc67_sequencial is not null ";
        }
      }
      if (isset ($estimativadepto)) {

         $where_depart .= "  and exists(select *
                                        From solicitavinculo solcom
                                             inner join solicita solabert on solcom.pc53_solicitafilho = solabert.pc10_numero
                                             inner join (select pc10_numero, pc10_depto ,pc53_solicitapai
                                                           from solicitavinculo solesti
                                                                inner join solicita estimativa on estimativa.pc10_numero = solesti.pc53_solicitafilho
                                                          where estimativa.pc10_solicitacaotipo = 4
                                                            and estimativa.pc10_depto = ".db_getsession("DB_coddepto")."
                                                            ) as estimativas on estimativas.pc53_solicitapai = solcom.pc53_solicitapai

                                     where solcom.pc53_solicitafilho = solicita.pc10_numero) ";
      }
      if (!isset($pesquisa_chave)) {
        if (isset($campos)==false) {
          if (file_exists("funcoes/db_func_solicitacompilacao.php")==true) {
            include(modification("funcoes/db_func_solicitacompilacao.php"));
          } else {
            $campos = "solicita.*";
          }
        }


        if(empty($lDesabilitaFiltroInstituicaoCompilacao)) {
          $where_depart .= " and pc10_instit          = ". db_getsession("DB_instit");
        }

        $where_depart .= " and pc10_solicitacaotipo = 6";

        $campos = " distinct ".$campos;
        if (isset($trazapenascomlicitacao)) {

          $where_depart .= "and l20_licsituacao in (" . SituacaoLicitacao::SITUACAO_JULGADA . ", " . SituacaoLicitacao::SITUACAO_HOMOLOGADA . ")";
          $campos       .= " , l20_codtipocom";
        }

        if (isset($apenaslicitacaojulgada)) {

          $where_depart .= "and l20_licsituacao in (" . SituacaoLicitacao::SITUACAO_JULGADA . ", " . SituacaoLicitacao::SITUACAO_HOMOLOGADA . ")";
        }
        if (isset($validavigencia)) {

          $sDataDia = date("Y-m-d", db_getsession("DB_datausu"));
          $where_depart .= "and '{$sDataDia}' between  pc54_datainicio and pc54_datatermino";
        }
        if (!empty($formacontrole)) {
          $where_depart .= "and pc54_formacontrole = {$formacontrole}";
        }

        $campos .=  " , l20_numero || '/' || l20_anousu as dl_número";
        $campos .=  " ,(select l03_descr from cflicita where l03_codigo = l20_codtipocom) as dl_modalidade ";

        if (isset($chave_pc10_numero) && (trim($chave_pc10_numero)!="") ) {
          $sql = $clsolicita->sql_query_estregistro(null,$campos,"pc10_numero desc "," pc10_numero=$chave_pc10_numero $where_depart ");
        } else if (isset($chave_pc10_data) && (trim($chave_pc10_data)!="") ) {
          $sql = $clsolicita->sql_query_estregistro("",$campos,"pc10_numero desc "," pc10_data like '$chave_pc10_data%' $where_depart ");
        } else {
          $sql = $clsolicita->sql_query_estregistro("",$campos,"pc10_numero desc "," 1=1 $where_depart");
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",array(),false);
      } else {
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
          $result = $clsolicita->sql_record($clsolicita->sql_query_estregistro(null,"distinct *",""," pc10_numero=$pesquisa_chave $where_depart "));
          if ($clsolicita->numrows!=0) {
            db_fieldsmemory($result,0);
            if ($tipobusca == 1) {
            	echo "<script>".$funcao_js."('$pc10_numero',false);</script>";
            } else {
              echo "<script>".$funcao_js."('$pc10_data',false);</script>";
            }
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
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
