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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("classes/db_sau_triagemavulsa_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

db_postmemory($HTTP_POST_VARS);

$oIframeAE                      = new cl_iframe_alterar_excluir();
$oSauConfig                     = loadConfig('sau_config');
$oDaoSauTriagemAvulsa           = db_utils::getdao('sau_triagemavulsa');
$oDaoFarCbos                    = db_utils::getdao('far_cbos');
$oDaoFarCbosProfissional        = db_utils::getdao('far_cbosprofissional');
$oDaoDbUsuaCgm                  = db_utils::getdao('db_usuacgm');
$oDaoMedicos                    = db_utils::getdao('medicos');
$oDaoSauTriagemAvulsaProntuario = db_utils::getdao('sau_triagemavulsaprontuario');
$oDaoProntuarios                = db_utils::getdao('prontuarios');
$oDaoCgm                        = db_utils::getdao('cgm');
$oDaoProntproced                = db_utils::getdao('prontproced');
$oDaoEspecmedico                = db_utils::getdao('especmedico');
$db_opcao                       = 1;
$db_opcao2                      = 1;
$db_opcao3                      = 1;
$db_botao                       = true;

/*Pesquisa por medico */
$sCampos    = "z01_nome,sd03_i_codigo,z01_numcgm";
$sJoins     = "inner join db_usuacgm     on cgmlogin                     = z01_numcgm ";
$sJoins    .= "inner join db_usuarios    on db_usuarios.id_usuario       = db_usuacgm.id_usuario ";
$sJoins    .= "inner join medicos        on medicos.sd03_i_cgm           = cgm.z01_numcgm ";
$sJoins    .= "inner join unidademedicos on unidademedicos.sd04_i_medico = medicos.sd03_i_codigo ";
$sJoins    .= "inner join unidades       on unidades.sd02_i_codigo       = unidademedicos.sd04_i_unidade ";
$sWhere     = " sd02_i_codigo = ".db_getsession("DB_coddepto");
$sWhere    .= " and db_usuacgm.id_usuario = ".db_getsession("DB_id_usuario");
$sSql       = $oDaoCgm->sql_query_file(null,$sCampos);
$sSql      .= $sJoins.' where '.$sWhere;
$rs         = $oDaoCgm->sql_record($sSql);
$lProfSaude = false;
if ($oDaoCgm->numrows > 0) {

  $oProfissional = db_utils::fieldsmemory($rs, 0);
  $z01_nome      = $oProfissional->z01_nome;
  $sd03_i_codigo = $oProfissional->sd03_i_codigo;
  $z01_numcgm    = $oProfissional->z01_numcgm;
  $lProfSaude    = true;

}

if (isset($opcao)) {

  $db_opcao2 = 3;
  if ($opcao == 'alterar') {
    $db_opcao = 2;
  } else {

    $db_opcao  = 3;
    $db_opcao3 = 3;

  }

}

if (isset($lFormTriagem) && $lFormTriagem == 'true') {
  $db_opcao2 = 3;
}

if (isset($lConsulta) && $lConsulta == 'true') {
  $db_opcao = 3;
}

if (isset($lFiltroUnidade) && $lFiltroUnidade == 'true') {

  $db_opcao3      = 3;
  $sd04_i_unidade = db_getsession('DB_coddepto');
  $descrdepto     = db_getsession('DB_nomedepto');

}


if (isset($chavefaa) && !empty($chavefaa)) {

  $s155_i_prontuario = $chavefaa;

  $sSql = $oDaoSauTriagemAvulsaProntuario->sql_query_file(null, 's155_i_triagemavulsa', '',
                                                          's155_i_prontuario = '.$chavefaa
                                                         );
  $rs   = $oDaoSauTriagemAvulsaProntuario->sql_record($sSql);
  if ($oDaoSauTriagemAvulsaProntuario->numrows > 0) {

    $oDados        = db_utils::fieldsmemory($rs, 0);
    $chavepesquisa = $oDados->s155_i_triagemavulsa;
    $opcao         = 2;
    $db_opcao      = isset($lConsulta) && $lConsulta == 'true' ? 3 : 2;

  } else { // Pego info do CGS

    $sSql = $oDaoProntuarios->sql_query_cgs($chavefaa, 'z01_i_cgsund, z01_v_nome');
    $rs   = $oDaoProntuarios->sql_record($sSql);
    if ($oDaoProntuarios->numrows > 0) {

      $oDados        = db_utils::fieldsmemory($rs, 0);
      $s152_i_cgsund = $oDados->z01_i_cgsund;
      $z01_v_nome    = $oDados->z01_v_nome;

    }

  }

}

if (isset($chavepesquisa) && !isset($incluir) && !isset($alterar) && !isset($excluir)) {


  $sCampos  = 's152_i_codigo, s152_i_pressaosistolica, s152_i_cintura, s152_n_peso, s152_n_temperatura, ';
  $sCampos .= 's152_i_altura, s152_i_glicemia, s152_i_alimentacaoexameglicemia, s152_i_cbosprofissional, ';
  $sCampos .= 'sd03_i_codigo, z01_nome, sd04_i_unidade, sd04_i_codigo, descrdepto, z01_v_nome, ';
  $sCampos .= 's152_d_dataconsulta, s152_i_cgsund, s152_i_pressaodiastolica, fa53_i_codigo ';

  $sSql     = $oDaoSauTriagemAvulsa->sql_query_grid(null, $sCampos, ' s152_i_codigo desc limit 1 ',
                                                    ' s152_i_codigo = '.$chavepesquisa
                                                   );
  $rs       = $oDaoSauTriagemAvulsa->sql_record($sSql);
  db_fieldsmemory($rs, 0);

}


if (!isset($chavepesquisa) && !isset($incluir) && !isset($alterar) && !isset($excluir) && !isset($lConsulta)) {

  $sSql = $oDaoDbUsuaCgm->sql_query(null, 'nome, cgmlogin', '',
                                    ' db_usuacgm.id_usuario = '.db_getsession('DB_id_usuario')
                                   );
  $rs   = $oDaoDbUsuaCgm->sql_record($sSql);

  if ($oDaoDbUsuaCgm->numrows > 0) {

    $oDados   = db_utils::fieldsmemory($rs, 0);

    $sCampos  = 'sd03_i_codigo, sd04_i_codigo, (select fa54_i_cbos from far_cbosprofissional ';
    $sCampos .= 'where far_cbosprofissional.fa54_i_unidademedico = sd04_i_codigo limit 1) as fa54_i_cbos ';
    $sSql     = $oDaoMedicos->sql_query_file(null, $sCampos, '',
                                             ' sd03_i_cgm = '.$oDados->cgmlogin.
                                             'and sd04_i_unidade = '.db_getsession('DB_coddepto')
                                            );
    $rs       = $oDaoMedicos->sql_record($sSql);

    if ($oDaoMedicos->numrows > 0) {

      $z01_nome       = $oDados->nome;
      $oDados         = db_utils::fieldsmemory($rs, 0);
      $sd03_i_codigo  = $oDados->sd03_i_codigo;
      $sd04_i_codigo  = $oDados->sd04_i_codigo;
      $fa53_i_codigo  = empty($oDados->fa54_i_cbos) ? '' : $oDados->fa54_i_cbos;
      $sd04_i_unidade = db_getsession('DB_coddepto');
      $descrdepto     = db_getsession('DB_nomedepto');

    }

  }

}

if (isset($incluir) || isset($alterar)) {

  $sSql = $oDaoFarCbosProfissional->sql_query_file(null, 'fa54_i_codigo', '',
                                                   "fa54_i_unidademedico = $sd04_i_codigo ".
                                                   "and fa54_i_cbos = $fa53_i_codigo"
                                                  );
  $rs   = $oDaoFarCbosProfissional->sql_record($sSql);

  db_inicio_transacao();

  if ($oDaoFarCbosProfissional->numrows > 0) { // o profissional ja possui o código CBOS na unidade

    $oDados                                        = db_utils::fieldsmemory($rs, 0);
    $oDaoSauTriagemAvulsa->s152_i_cbosprofissional = $oDados->fa54_i_codigo;

  } else { // tenho que incluir o código CBOS para o profissional na unidade

    $oDaoFarCbosProfissional->fa54_i_unidademedico = $sd04_i_codigo;
    $oDaoFarCbosProfissional->fa54_i_cbos          = $fa53_i_codigo;
    $oDaoFarCbosProfissional->incluir(null);
    if ($oDaoFarCbosProfissional->erro_status == '0') {

      $oDaoSauTriagemAvulsa->erro_status = '0';
      $oDaoSauTriagemAvulsa->erro_msg    = $oDaoFarCbosProfissional->erro_msg;

    } else {
      $oDaoSauTriagemAvulsa->s152_i_cbosprofissional = $oDaoFarCbosProfissional->fa54_i_codigo;
    }


  }

  if ($oDaoSauTriagemAvulsa->erro_status != '0') {

    if (empty($s152_i_glicemia) || $s152_i_glicemia <= 0) {

       $oDaoSauTriagemAvulsa->s152_i_glicemia                 = '0';
       $oDaoSauTriagemAvulsa->s152_i_alimentacaoexameglicemia = '0';

    }

    if (isset($incluir)) {

      $oDaoSauTriagemAvulsa->s152_d_datasistema = date('Y-m-d', db_getsession('DB_datausu'));
      $oDaoSauTriagemAvulsa->s152_c_horasistema = date('H:i');
      $oDaoSauTriagemAvulsa->s152_i_login       = db_getsession('DB_id_usuario');
      $oDaoSauTriagemAvulsa->incluir($s152_i_codigo);

      if ($oDaoSauTriagemAvulsa->erro_status != '0') {

        if (isset($lFormTriagem) && $lFormTriagem == 'true') {

          $oDaoSauTriagemAvulsaProntuario->s155_i_triagemavulsa = $oDaoSauTriagemAvulsa->s152_i_codigo;
          $oDaoSauTriagemAvulsaProntuario->s155_i_prontuario    = $s155_i_prontuario;
          $oDaoSauTriagemAvulsaProntuario->incluir(null);

          if ($oDaoSauTriagemAvulsaProntuario->erro_status == '0') {

            $oDaoSauTriagemAvulsa->erro_status = '0';
            $oDaoSauTriagemAvulsa->erro_msg    = $oDaoSauTriagemAvulsaProntuario->erro_msg;

          } else {

            $s152_i_codigo = $oDaoSauTriagemAvulsa->s152_i_codigo;
            $db_opcao      = 2;
            $opcao         = 2;

          }

        }

      }
      if ($oDaoSauTriagemAvulsa->erro_status != '0' && $lProfSaude == true) {

        /**
         * Buscamos todos os procedimentos de triagem configurados, para incluir um novo registro para cada na tabela
         * prontproced, e armazenamos em um array com os codigos
         */
        $aProcedimentosTriagem     = array();
        $oDaoProcedimentoTriagem   = db_utils::getDao("parametroprocedimentotriagem");
        $sSqlProcedimentoTriagem   = $oDaoProcedimentoTriagem->sql_query(null, "s166_sau_procedimento");
        $rsProcedimentoTriagem     = $oDaoProcedimentoTriagem->sql_record($sSqlProcedimentoTriagem);
        $iTotalProcedimentoTriagem = $oDaoProcedimentoTriagem->numrows;

        if ($iTotalProcedimentoTriagem  > 0 ) {

          for ( $iContador = 0; $iContador < $iTotalProcedimentoTriagem; $iContador++ ) {
            $aProcedimentosTriagem[] = db_utils::fieldsMemory($rsProcedimentoTriagem, $iContador)->s166_sau_procedimento;
          }
        }

          $sWhere  = ' sd27_i_rhcbo   = '.$rh70_sequencial;
          $sWhere .= ' and sd04_i_unidade = '.$sd04_i_unidade;
          $sWhere .= ' and  sd04_i_medico  = '.$sd03_i_codigo;
          $sSql        = $oDaoEspecmedico->sql_query(null,'sd27_i_codigo',null,$sWhere);
          $rs          = $oDaoEspecmedico->sql_record($sSql);
          if ($oDaoEspecmedico->numrows > 0) {

            $oEspecmedico = db_utils::fieldsmemory($rs,0);

            /**
             * Percorremos os procedimentos de triagem configurados
             */
            foreach ( $aProcedimentosTriagem as $iProcedimento ) {

              $oDaoProntproced->sd29_i_prontuario   = $s155_i_prontuario;
              $oDaoProntproced->sd29_i_procedimento = $iProcedimento;
              $oDaoProntproced->sd29_i_profissional = $oEspecmedico->sd27_i_codigo;
              $oDaoProntproced->sd29_i_usuario      = DB_getsession("DB_id_usuario");
              $oDaoProntproced->sd29_d_cadastro     = date("Y-m-d",db_getsession("DB_datausu"));
              $oDaoProntproced->sd29_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
              $oDaoProntproced->sd29_c_hora         = date('H:i');
              $oDaoProntproced->sd29_c_cadastro     = date("H",db_getsession("DB_datausu")).":".date("m",db_getsession("DB_datausu"));
              $oDaoProntproced->sd29_t_diagnostico  = ' ';
              $oDaoProntproced->incluir("");
              if ($oDaoProntproced->erro_status == '0') {

                $oDaoSauTriagemAvulsa->erro_status = '0';
                $oDaoSauTriagemAvulsa->erro_msg    = $oDaoProntproced->erro_msg;

              }
            }
          } else {

            $oDaoSauTriagemAvulsa->erro_status = '0';
            $oDaoSauTriagemAvulsa->erro_msg    = "Erro ao selecionar profissional.";

          }

      }

    } else {

      if ($s152_i_login      == DB_getsession("DB_id_usuario") &&
          s152_d_datasistema == date("Y-m-d",db_getsession("DB_datausu"))
         ) {

        $oDaoSauTriagemAvulsa->s152_i_codigo = $s152_i_codigo;
        $oDaoSauTriagemAvulsa->alterar($s152_i_codigo);

      } else {
        echo "<script>";
        echo " alert('A alteracao da Triagem so pode ser realizada no mesmo dia que foi lançada, e pelo mesmo usuario que lancou');";
        echo "</script>";
      }
    }

  }

  db_fim_transacao($oDaoSauTriagemAvulsa->erro_status == '0' ? true : false);

} elseif (isset($excluir)) {

  db_inicio_transacao();
  $oDaoSauTriagemAvulsa->excluir($s152_i_codigo);
  db_fim_transacao($oDaoSauTriagemAvulsa->erro_status == '0' ? true : false);

}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<style type="text/css">
.estiloLinkAltExc {

  color: blue;
  text-decoration: underline;
  cursor: pointer;

}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
<center>
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
      <fieldset style='width: 100%;'> <legend><b>Triagem Avulsa</b></legend>
        <?
          require_once("forms/db_frmsau_triagemavulsa.php");
        ?>
      </fieldset>
    </center>
  </td>
  </tr>
</table>
</center>
<?
if (!isset($lFormTriagem) || $lFormTriagem != 'true') {

  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
          db_getsession("DB_anousu"), db_getsession("DB_instit")
         );

}
?>
</body>
</html>
<script>
  js_tabulacaoforms("form1", "s152_i_pressaosistolica", true, 1, "s152_i_pressaosistolica", true);
</script>
<?
echo "<script type=\"text/javascript\">";
if ($lProfSaude == false) {
  echo "  alert('Usuário logado não é um profissional da saúde ou não está vinculado ao departamento.');";
} else {
  echo " js_pesquisasd04_i_cbo(true); ";
}
echo "</script>";
if (isset($incluir) || isset($alterar) || isset($excluir)) {

  if ($oDaoSauTriagemAvulsa->erro_status == '0') {

    $oDaoSauTriagemAvulsa->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script> ";
    if ($oDaoSauTriagemAvulsa->erro_campo != '') {

      echo "<script> document.form1.".$oDaoSauTriagemAvulsa->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoSauTriagemAvulsa->erro_campo.".focus();</script>";

    }

  } else {

    $oDaoSauTriagemAvulsa->erro(true, false);

    if (isset($lFormTriagem) && $lFormTriagem == 'true') {
      exit;
    }
    db_redireciona('sau4_sau_triagemavulsa001.php?s152_i_cgsund='.$s152_i_cgsund);

  }

}
?>