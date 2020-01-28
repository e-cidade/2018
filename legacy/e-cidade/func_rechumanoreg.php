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

//MODULO: educação
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_rechumano_classe.php"));
require_once(modification("classes/db_regencia_classe.php"));

db_postmemory($_POST);
parse_str( $_SERVER["QUERY_STRING"] );

$clrechumano = new cl_rechumano;
$clregencia  = new cl_regencia;
$clrotulo    = new rotulocampo;
$clrechumano->rotulo->label("ed20_i_codigo");
$clrotulo->label("z01_nome");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
nextfield = "campo1"; // nome do primeiro campo
netscape  = "";
ver       = navigator.appVersion;
len       = ver.length;
for (iln = 0; iln < len; iln++)
  if (ver.charAt(iln) == "(")
    break;
    netscape = (ver.charAt(iln+1).toUpperCase() != "C");
 function keyDown(DnEvents) {

  k = (netscape) ? DnEvents.which : window.event.keyCode;
  if (k == 13) { // pressiona tecla enter
    if (nextfield == 'done') {
      return true; // envia quando termina os campos
    } else {
      eval(" document.getElementById('"+nextfield+"').focus()" );
      return false;
    }
   }
 }
 document.onkeydown = keyDown;
 if(netscape)
  document.captureEvents(Event.KEYDOWN|Event.KEYUP);
</script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
    <tr>
      <td height="63" align="center" valign="top">
        <form name="form2" method="post" action="" >
          <table width="55%" border="0" align="center" cellspacing="0">
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Ted20_i_codigo?>">
               <?=$Led20_i_codigo?>
              </td>
              <td width="96%" align="left" nowrap>
               <?db_input("ed20_i_codigo",10,$Ied20_i_codigo,true,"text",4,
                          "onFocus=\"nextfield='pesquisar2'\"","chave_ed20_i_codigo");?>
              </td>
            </tr>
            <tr>
              <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
               <?=$Lz01_nome?>
              </td>
              <td width="96%" align="left" nowrap>
               <?db_input("z01_nome",50,$Iz01_nome,true,"text",4,"onFocus=\"nextfield='pesquisar2'\"","chave_z01_nome");?>
              </td>
            </tr>
            <tr>
              <td colspan="2" align="center">
                <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" onFocus="nextfield='done'">
                <input name="limpar" type="reset" id="limpar" value="Limpar" >
                <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rechumano.hide();">
              </td>
            </tr>
          </table>
        </form>
      </td>
    </tr>
    <tr>
      <td align="center" valign="top">
        <?php
        $sCamposRegencia = "distinct ed59_i_disciplina, ed57_i_turno, ed246_i_turno";
        $sSqlRegencia    = $clregencia->sql_query_turma_turno( "", $sCamposRegencia, "", "ed59_i_codigo = {$regencia}" );
        $rsRegencia      = $clregencia->sql_record($sSqlRegencia);

        db_fieldsmemory( $rsRegencia, 0 );

        $escola = db_getsession("DB_coddepto");
        if (isset($campos) == false) {

          if (file_exists("funcoes/db_func_rechumano.php") == true) {
            include(modification("funcoes/db_func_rechumano.php"));
          } else {
            $campos = "rechumano.*";
          }
        }

        if (isset($chave_ed20_i_codigo) && (trim($chave_ed20_i_codigo) != "")) {
          $where = " AND ed20_i_codigo = $chave_ed20_i_codigo";
        } else if (isset($chave_z01_nome) && (trim($chave_z01_nome) != "")) {
          $where = " AND case when ed20_i_tiposervidor = 1 then cgmrh.z01_nome else cgmcgm.z01_nome end like '$chave_z01_nome%'";
        } else {
          $where = "";
        }

        $sCampos  = " DISTINCT ed20_i_codigo, ";
        $sCampos .= " case  ";
        $sCampos .= "      when ed20_i_tiposervidor = 1 ";
        $sCampos .= "           then cgmrh.z01_nome ";
        $sCampos .= "           else cgmcgm.z01_nome ";
        $sCampos .= "       end as z01_nome, ";
        $sCampos .= " case  ";
        $sCampos .= "      when ed20_i_tiposervidor = 1  ";
        $sCampos .= "           then rechumanopessoal.ed284_i_rhpessoal ";
        $sCampos .= "           else rechumanocgm.ed285_i_cgm ";
        $sCampos .= "       end as dl_identificacao, ";
        $sCampos .= " case  ";
        $sCampos .= "      when ed75_c_simultaneo = 'S' ";
        $sCampos .= "          then 'SIM' ";
        $sCampos .= "          else 'NÃO' ";
        $sCampos .= "      end as ed75_c_simultaneo ";

        $sDataDia = date("Y-m-d", db_getsession("DB_datausu"));
        $sTurnos  = $ed57_i_turno;
        $sTurnos .= !empty( $ed246_i_turno ) ? ", {$ed246_i_turno}" : "";

        $sql  = " SELECT $sCampos  ";
        $sql .= "   FROM rechumano ";
        $sql .= "        left  join rechumanopessoal on rechumanopessoal.ed284_i_rechumano     = rechumano.ed20_i_codigo ";
        $sql .= "        left  join rhpessoal        on rhpessoal.rh01_regist                  = rechumanopessoal.ed284_i_rhpessoal ";
        $sql .= "        left  join cgm as cgmrh     on cgmrh.z01_numcgm                       = rhpessoal.rh01_numcgm ";
        $sql .= "        left  join rechumanocgm     on rechumanocgm.ed285_i_rechumano         = rechumano.ed20_i_codigo ";
        $sql .= "        left  join cgm as cgmcgm    on cgmcgm.z01_numcgm                      = rechumanocgm.ed285_i_cgm ";
        $sql .= "        inner join rechumanoescola  on rechumanoescola.ed75_i_rechumano       = rechumano.ed20_i_codigo ";
        $sql .= "        inner join relacaotrabalho  on relacaotrabalho.ed23_i_rechumanoescola = rechumanoescola.ed75_i_codigo ";
        $sql .= "        inner join rechumanoativ    on rechumanoativ.ed22_i_rechumanoescola   = rechumanoescola.ed75_i_codigo ";
        $sql .= "        inner join atividaderh      on atividaderh.ed01_i_codigo              = rechumanoativ.ed22_i_atividade ";
        $sql .= "  WHERE rechumanoescola.ed75_i_escola = $escola ";
        $sql .= "    AND atividaderh.ed01_c_regencia = 'S' ";
        $sql .= "    AND relacaotrabalho.ed23_i_disciplina = $ed59_i_disciplina ";
        $sql .= "    AND not exists (select 1 ";
        $sql .= "                      from docenteausencia  ";
        $sql .= "                     where ed321_rechumano = ed20_i_codigo";
        $sql .= "                       and (ed321_final is null or ed321_final > '{$sDataDia}')";
        $sql .= "                       and ed321_escola = {$escola})";
        $sql .= "    AND EXISTS (SELECT 1 ";
        $sql .= "                  FROM rechumanohoradisp ";
        $sql .= "                 inner join periodoescola on ed17_i_codigo = ed33_i_periodo ";
        $sql .= "                  WHERE ed33_rechumanoescola = ed75_i_codigo ";
        $sql .= "                    and ed17_i_turno in( {$sTurnos} ) ) ";
        $sql .= "  {$where} ";

        if (!isset($pesquisa_chave)) {

          $repassa = array();
          if (isset($chave_ed20_i_codigo)) {
            $repassa = array("chave_ed20_i_codigo"=>$chave_ed20_i_codigo,"chave_ed20_i_codigo"=>$chave_ed20_i_codigo);
          }

          $sql .= " ORDER BY z01_nome";
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
          $resul = db_query($sql);
          if (pg_num_rows($resul) == 0) {

            echo "<b>Nenhum professor com relações de trabalho cadastradas <br>ou nenhuma disponibilidade cadastrada.<br>
                  (Cadastros / Recursos Humanos / Alteração / Aba Relação de Trabalho)<br>
                  (Cadastros / Recursos Humanos / Alteração / Aba Função Exercida) <br>
                  (Cadastros / Recursos Humanos / Alteração / Aba Horários da Regência)	</b>";
          }
        } else {

          if (isset($pesquisa_chave) && $pesquisa_chave != "") {

            $sql .= " AND ed20_i_codigo = $pesquisa_chave ORDER BY z01_nome";
            $resul = db_query($sql);

            if (pg_num_rows($resul) != 0) {

              db_fieldsmemory($resul,0);
              echo "<script>".$funcao_js."('$z01_nome',false);</script>";

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
js_tabulacaoforms("form2","chave_ed20_i_codigo",true,1,"chave_ed20_i_codigo",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
