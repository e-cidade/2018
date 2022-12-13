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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_solicita_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clsolicita = new cl_solicita;
$clsolicita->rotulo->label("pc10_numero");
$clsolicita->rotulo->label("pc10_data");

if (!isset($pesquisar)) {
  $iDia = date("d", db_getsession("DB_datausu"));
  $iMes = date("m", db_getsession("DB_datausu"));
  $iAno = date("Y", db_getsession("DB_datausu"));

  $chave_pc10_data_dia = $iDia;
  $chave_pc10_data_mes = $iMes;
  $chave_pc10_data_ano = $iAno;

  $chave_pc10_data = "{$chave_pc10_data_ano}-{$chave_pc10_data_mes}-{$chave_pc10_data_dia}";

}

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
	         db_inputdata("pc10_data",@$chave_pc10_data_dia, @$chave_pc10_data_mes, @$chave_pc10_data_ano, true, "text", 4, "", "chave_pc10_data");
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


      $sSolicitaAnulada = "not exists (select 1 from solicitaanulada where pc67_solicita = solicita.pc10_numero)";

      if (!isset($passar)) {
        $where_depart = " and (pc81_solicitem ";
        if (isset($param) && $param == "") {
          $nulo = " is null or pc10_solicitacaotipo = 5) ";
        } else {
          $nulo = "";
        }

        if (trim($nulo) == "") {
          $where_depart  = " and (e55_sequen is null or (e55_sequen is not null and e54_anulad is not null))";
        } else {
          $where_depart .= $nulo;
        }
      }
      if (isset($anular) && $anular=="true") {
        $where_depart = " and e54_autori is not null and e54_anulad is null and (e61_numemp is null or (e60_numemp is not null and e60_vlremp=e60_vlranu))";
      }
      if (isset($anular) && $anular=="false") {
        $where_depart .= " and ( e54_autori is null or ( e54_autori is not null and e54_anulad is null and (e61_numemp is null or (e60_numemp is not null and e60_vlremp=e60_vlranu))))";
      }

      if (isset($anular)) {
        $where_depart .= " and pc11_codigo is not null ";
      }

      if (isset($departamento) && trim($departamento)!="") {

      	$where_depart .= " and case
				                 when pc49_protprocesso is not null then
				                   case
				                     when ( exists ( select *
				                                     from proctransfer
				                                     	  inner join proctransand on p64_codtran = p62_codtran
				                                    where p62_codtran  = ( select max(p63_codtran)
				                                                             from proctransferproc
				                                                            where p63_codproc =  protprocesso.p58_codproc )
				                                      and p62_coddeptorec = {$departamento}
				                                      and p64_codandam	  = protprocesso.p58_codandam )
				                          ) then true
				                     else case
				                     		when p63_codtran is null and p58_codandam = 0 then true
				                     		else case
				                     				when exists ( select *
				                     							    from proctransfer
				                     							    	 left join proctransand on p64_codtran = p62_codtran
				                     							   where p62_codtran = ( select max(p63_codtran)
				                                                   	      			 	    from proctransferproc
				                                                       	  				   where p63_codproc =  protprocesso.p58_codproc )
				                                                     and p64_codtran is not null
				                                                 ) and p58_codandam  = 0 then true else false
				                     			 end
				                     	  end
				                   end
				                 else
				                   case
				                     when pc10_depto = {$departamento} then true
				                     else false
				                   end
				               end ";

      }
      if (isset($gerautori)) {
        $where_depart .= " and pc10_correto='t' ";
      }

      if (isset($proc) and $proc=="true" and $param != "alterar") {
        $where_depart .= " and pc81_codproc is not null";
      }

      if (db_getsession("DB_id_usuario") != 1) {
      	$where_depart .= " and pc10_solicitacaotipo not in(6,4,3)";
      }


      if (isset($nada)) {
        $where_depart = "";
      }
      if (!isset($pesquisa_chave)) {
        if (isset($campos)==false) {
          if (file_exists("funcoes/db_func_solicita.php")==true) {
            include("funcoes/db_func_solicita.php");
          } else {
            $campos = "solicita.*";
          }
        }
        $where_depart .= " and pc10_instit = " . db_getsession("DB_instit") . " and {$sSolicitaAnulada} ";
        $campos = " distinct ".$campos;
        if (isset($chave_pc10_numero) && (trim($chave_pc10_numero)!="") ) {
          $sql = $clsolicita->sql_query_solprot(null,$campos,"pc10_numero desc "," pc10_numero={$chave_pc10_numero} {$where_depart} ");
        } else if (isset($chave_pc10_data) && (trim($chave_pc10_data)!="") ) {
          $data = "{$chave_pc10_data_ano}-{$chave_pc10_data_mes}-{$chave_pc10_data_dia}";
          //echo "<br>data: $data<br>";
          $sql = $clsolicita->sql_query_solprot("",$campos,"pc10_numero desc "," pc10_data = '{$data}' {$where_depart} ");
        } else {
          if(!isset($pesquisar)) {
            $sql = "";
          } else {
            $sql = $clsolicita->sql_query_solprot("",$campos,"pc10_numero desc "," 1=1 {$where_depart}");
          }
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",array(),false);

      } else {
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
          $result = $clsolicita->sql_record($clsolicita->sql_query_solprot(null,"distinct *",""," pc10_numero=$pesquisa_chave $where_depart  and {$sSolicitaAnulada}"));
          if ($clsolicita->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc10_data',false);</script>";
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