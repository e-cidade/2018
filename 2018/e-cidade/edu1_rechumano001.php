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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rechumano_classe.php");
include("classes/db_rechumanoescola_classe.php");
include("classes/db_rechumanopessoal_classe.php");
include("classes/db_rechumanocgm_classe.php");
include("classes/db_db_uf_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_rhpesdoc_classe.php");
include("classes/db_rhraca_classe.php");
include("classes/db_rhinstrucao_classe.php");
include("classes/db_rhestcivil_classe.php");
include("classes/db_rhnacionalidade_classe.php");
include("classes/db_pais_classe.php");
include("classes/db_censouf_classe.php");
include("classes/db_censomunic_classe.php");
include("classes/db_censoorgemissrg_classe.php");
include("classes/db_censocartorio_classe.php");
include("libs/db_jsplibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$clrechumano        = new cl_rechumano;
$clrechumanopessoal = new cl_rechumanopessoal;
$clrechumanocgm     = new cl_rechumanocgm;
$clrechumanoescola  = new cl_rechumanoescola;
$cldb_uf            = new cl_db_uf;
$clrhpessoal        = new cl_rhpessoal;
$clrhpesdoc         = new cl_rhpesdoc;
$clrhraca           = new cl_rhraca;
$clrhinstrucao      = new cl_rhinstrucao;
$clrhestcivil       = new cl_rhestcivil;
$clrhnacionalidade  = new cl_rhnacionalidade;
$clpais             = new cl_pais;
$clcensouf          = new cl_censouf;
$clcensoorgemissrg  = new cl_censoorgemissrg;
$clcensomunic       = new cl_censomunic;
$clcensocartorio    = new cl_censocartorio;
$db_opcao           = 1;
$db_opcao1          = 1;
$db_botao           = true;
$naotem             = false;

if (isset($incluir)) {

  $ed75_i_escola = db_getsession("DB_coddepto");
  $dia           = date("d",db_getsession("DB_datausu"));
  $mes           = date("m",db_getsession("DB_datausu"));
  $ano           = date("Y",db_getsession("DB_datausu"));
  db_inicio_transacao();

  if ($temregistro == 0) {

    $clrechumano->ed20_c_posgraduacao = "0001";
    $clrechumano->ed20_c_outroscursos = "000001";
    $clrechumano->ed20_i_rhregime     = $rh30_codreg;
    $clrechumano->incluir(null);
    if ($clrechumano->erro_status == "1") {

      if ($ed20_i_tiposervidor == 1) {

        $clrechumanopessoal->ed284_i_rechumano = $clrechumano->ed20_i_codigo;
        $clrechumanopessoal->incluir(null);

      } else {

        $clrechumanocgm->ed285_i_rechumano = $clrechumano->ed20_i_codigo;
        $clrechumanocgm->incluir(null);

      }
      $clrechumanoescola->ed75_d_ingresso   = $ano."-".$mes."-".$dia;
      $clrechumanoescola->ed75_i_escola     = $ed75_i_escola;
      $clrechumanoescola->ed75_i_rechumano  = $clrechumano->ed20_i_codigo;
      $clrechumanoescola->ed75_c_simultaneo = 'N';
      $clrechumanoescola->incluir(null);

    }

  } else {

    $clrechumano->ed20_i_rhregime = $rh30_codreg;
    $clrechumano->alterar($ed20_i_codigo);

    $sWhereRecHumano = " ed75_i_rechumano = {$ed20_i_codigo} AND ed75_i_escola = {$ed75_i_escola}";
    $sSqlRecHumano   = $clrechumanoescola->sql_query( "", "*", "", $sWhereRecHumano );
    $result1         = db_query( $sSqlRecHumano );

    if ( pg_num_rows( $result1 ) == 0 ) {

      $clrechumanoescola->ed75_d_ingresso   = $ano."-".$mes."-".$dia;
      $clrechumanoescola->ed75_i_escola     = $ed75_i_escola;
      $clrechumanoescola->ed75_i_rechumano  = $ed20_i_codigo;
      $clrechumanoescola->ed75_c_simultaneo = 'N';
      $clrechumanoescola->incluir(null);
      $clrechumano->erro_status = $clrechumanoescola->erro_status;
      $clrechumano->erro_msg    = $clrechumanoescola->erro_msg;

    } else {

      db_fieldsmemory( $result1, 0 );
      db_msgbox("Recurso Humano $z01_nome já está vinculado a esta escola!");
    }

  }
  db_fim_transacao();

} else if (isset($chavepesquisa)) {

  $db_opcao  = 1;
  $db_opcao1 = 1;
  $db_botao  = true;
  include("funcoes/db_func_rechumanonovo.php");
  if ($ed20_i_tiposervidor == 1) {

    $sql    = "select $campospessoal ";
    $sql   .= "from rhpessoal ";
    $sql   .= " left  join rhpessoalmov on  rh02_anousu =". db_anofolha();
    $sql   .= "                        and  rh02_mesusu =". db_mesfolha();
    $sql   .= "                        and  rh02_regist = rh01_regist ";
    $sql   .= "                        and  rh02_instit =". db_getsession("DB_instit");
    $sql   .= " left join rhregime as regimerh on  regimerh.rh30_codreg = rhpessoalmov.rh02_codreg ";
    $sql   .= " left  join rhlota       on  rhlota.r70_codigo = rhpessoalmov.rh02_lota ";
    $sql   .= "                        and  rhlota.r70_instit = rhpessoalmov.rh02_instit ";
    $sql   .= " inner join cgm as cgmrh on  cgmrh.z01_numcgm = rhpessoal.rh01_numcgm ";
    $sql   .= " inner join db_config    on  db_config.codigo = rhpessoal.rh01_instit ";
    $sql   .= " inner join rhestcivil   on  rhestcivil.rh08_estciv = rhpessoal.rh01_estciv ";
    $sql   .= " inner join rhraca       on  rhraca.rh18_raca = rhpessoal.rh01_raca ";
    $sql   .= " left  join rhfuncao     on  rhfuncao.rh37_funcao = rhpessoal.rh01_funcao ";
    $sql   .= "                        and  rhfuncao.rh37_instit = rhpessoalmov.rh02_instit ";
    $sql   .= " inner join rhinstrucao  on  rhinstrucao.rh21_instru = rhpessoal.rh01_instru ";
    $sql   .= " inner join rhnacionalidade  on  rhnacionalidade.rh06_nacionalidade = rhpessoal.rh01_nacion ";
    $sql   .= " left join rhpesdoc      on rhpesdoc.rh16_regist = rhpessoal.rh01_regist  ";
    $sql   .= " left join rechumanopessoal on rechumanopessoal.ed284_i_rhpessoal = rh01_regist ";
    $sql   .= " left join rechumano on rechumano.ed20_i_codigo = rechumanopessoal.ed284_i_rechumano ";
    $sql   .= "where rh01_regist = $chavepesquisa ";
    $result = $clrhpessoal->sql_record($sql);
    if ($clrhpessoal->numrows > 0) {

      db_fieldsmemory($result,0);
      $ed284_i_rhpessoal = $chavepesquisa;

    }

  } else {

    $sql1    = " select $camposcgm ";
    $sql1   .= "  from cgm as cgmcgm ";
    $sql1   .= "   left join cgmdoc on cgmdoc.z02_i_cgm = cgmcgm.z01_numcgm ";
    $sql1   .= "   left join rechumanocgm on rechumanocgm.ed285_i_cgm = cgmcgm.z01_numcgm ";
    $sql1   .= "   left join rechumano on rechumano.ed20_i_codigo = rechumanocgm.ed285_i_rechumano ";
    $sql1   .= "   left join rhregime as regimecgm on  regimecgm.rh30_codreg = rechumano.ed20_i_rhregime ";
    $sql1   .= "   where z01_numcgm = $chavepesquisa ";
    $result1 = $clrhpessoal->sql_record($sql1);
    if ($clrhpessoal->numrows > 0) {

      db_fieldsmemory($result1,0);
      $ed285_i_cgm = $chavepesquisa;

    }

  }

}
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top" bgcolor="#CCCCCC">
          <br>
          <fieldset style="width:95%"><legend><b>Inclusão de Recurso Humano</b></legend>
            <?include("forms/db_frmrechumano.php");?>
          </fieldset>
        </td>
      </tr>
    </table>
  </body>
</html>
<script>
js_tabulacaoforms("form1","ed20_i_codigo",true,1,"ed20_i_codigo",true);
</script>
<?
if (isset($incluir)) {

  if ($clrechumano->erro_status == "0") {

    $clrechumano->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clrechumano->erro_campo != "") {

      echo "<script> document.form1.".$clrechumano->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrechumano->erro_campo.".focus();</script>";
    }

  } else {

    if ($temregistro == 0) {
      db_redireciona("edu1_rechumano002.php?chavepesquisa={$clrechumano->ed20_i_codigo}&ed75_i_codigo={$clrechumanoescola->ed75_i_codigo}");
    } else {
      db_redireciona("edu1_rechumano002.php?chavepesquisa={$ed20_i_codigo}&ed75_i_codigo={$ed75_i_codigo}");
    }

  }

}
if (isset($cancelar)) {
  echo "<script>location.href='".$clrechumano->pagina_retorno."'</script>";
}
?>