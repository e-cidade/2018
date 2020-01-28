<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once(modification("classes/db_iptubase_classe.php"));
require_once(modification("classes/db_propri_classe.php"));
require_once(modification("classes/db_itbipropriold_classe.php"));
require_once(modification("classes/db_itbi_classe.php"));
require_once(modification("classes/db_itbilogin_classe.php"));
require_once(modification("classes/db_itbinome_classe.php"));
require_once(modification("classes/db_itbinomecgm_classe.php"));
require_once(modification("classes/db_itbimatric_classe.php"));
require_once(modification("classes/db_itburbano_classe.php"));
require_once(modification("classes/db_itbirural_classe.php"));
require_once(modification("classes/db_itbiruralcaract_classe.php"));
require_once(modification("classes/db_itbidadosimovel_classe.php"));
require_once(modification("classes/db_itbidadosimovelsetorloc_classe.php"));
require_once(modification("classes/db_itbiformapagamentovalor_classe.php"));
require_once(modification("classes/db_localidaderural_classe.php"));
require_once(modification("classes/db_itbilocalidaderural_classe.php"));
require_once(modification("classes/db_db_config_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$cliptubase                = new cl_iptubase();
$clpropri                  = new cl_propri();
$clitbi                    = new cl_itbi();
$clitbinome                = new cl_itbinome();
$clitbinomecgm             = new cl_itbinomecgm();
$clitbipropriold           = new cl_itbipropriold();
$clitbilogin               = new cl_itbilogin();
$clitbimatric              = new cl_itbimatric();
$clitbirural               = new cl_itbirural();
$clitbiruralcaract         = new cl_itbiruralcaract();
$clitburbano               = new cl_itburbano();
$clitbidadosimovel         = new cl_itbidadosimovel();
$clitbidadosimovelsetorloc = new cl_itbidadosimovelsetorloc();
$clitbiformapagamentovalor = new cl_itbiformapagamentovalor();
$cllocalidaderural         = new cl_localidaderural();
$clitbilocalidaderural     = new cl_itbilocalidaderural();
$cldb_config               = new cl_db_config;

$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;

$iAnoUsu  = db_getsession('DB_anousu');

$iInstitSessao = db_getsession('DB_instit');
$result        = $cldb_config->sql_record($cldb_config->sql_query_file($iInstitSessao, "cgc, db21_codcli"));
db_fieldsmemory($result, 0);

if (isset($oPost->incluir)) {

  db_inicio_transacao();

  $clitbi->it01_tipotransacao = $oPost->it01_tipotransacao;
  $clitbi->it01_areaterreno 	= $oPost->it01_areaterreno;
  $clitbi->it01_areaedificada = "0";
  $clitbi->it01_obs 					= $oPost->it01_obs;
  $clitbi->it01_areatrans     = $oPost->it01_areatrans;
  $clitbi->it01_mail          = $oPost->it01_mail;
  $clitbi->it01_finalizado    = false;
  $clitbi->it01_origem        = 1;
  $clitbi->it01_id_usuario    = db_getsession('DB_id_usuario');
  $clitbi->it01_coddepto      = db_getsession('DB_coddepto');
  $clitbi->it01_data          = date('Y-m-d', db_getsession('DB_datausu'));
  $clitbi->it01_hora          = db_hora();
  $clitbi->it01_envia         = 'false';
  $clitbi->it01_percentualareatransmitida = $oPost->it01_percentualareatransmitida;

  if (isset($oPost->it01_valortransacao)) {

    $clitbi->it01_valorterreno   = null;
    $clitbi->it01_valorconstr    = null;
    $clitbi->it01_valortransacao = $oPost->it01_valortransacao;
  } else {

    $clitbi->it01_valorterreno   = $oPost->it01_valorterreno;
    $clitbi->it01_valorconstr    = $oPost->it01_valorconstr;
    $clitbi->it01_valortransacao = $oPost->it01_valorterreno + $oPost->it01_valorconstr;
  }

  $clitbi->incluir(null);

  if ($clitbi->erro_status == 0) {
    $lSqlErro = true;
  }

  $sMsgErro = $clitbi->erro_msg;

  if (! $lSqlErro) {

    if ($oGet->tipo == "urbano") {

      $clitburbano->it05_guia         = $clitbi->it01_guia;
      $clitburbano->it05_frente       = $oPost->it05_frente;
      $clitburbano->it05_fundos       = $oPost->it05_fundos;
      $clitburbano->it05_esquerdo     = $oPost->it05_esquerdo;
      $clitburbano->it05_direito      = $oPost->it05_direito;
      $clitburbano->it05_itbisituacao = $oPost->it05_itbisituacao;

      $clitburbano->incluir($clitbi->it01_guia);

      if ($clitburbano->erro_status == 0) {
        $lSqlErro = true;
      }

      $sMsgErro = $clitburbano->erro_msg;

      if (! $lSqlErro) {

        $clitbimatric->it06_guia   = $clitbi->it01_guia;
        $clitbimatric->it06_matric = $oPost->j01_matric;
        $clitbimatric->incluir($clitbi->it01_guia, $oPost->j01_matric);

        if ($clitbimatric->erro_status == 0) {
          $lSqlErro = true;
        }

        $sMsgErro = $clitbimatric->erro_msg;

      }

    } else if ($oGet->tipo == "rural") {

      $clitbirural->it18_guia        = $clitbi->it01_guia;
      $clitbirural->it18_frente      = $oPost->it18_frente;
      $clitbirural->it18_fundos      = $oPost->it18_fundos;
      $clitbirural->it18_prof        = $oPost->it18_prof;
      $clitbirural->it18_localimovel = $oPost->it18_localimovel;
      $clitbirural->it18_distcidade  = $oPost->it18_distcidade;

      if (isset($oPost->it18_coordenadas) && $oPost->it18_coordenadas != "") {
        $clitbirural->it18_coordenadas = $oPost->it18_coordenadas;
      } else {
      	$clitbirural->it18_coordenadas = " ";
      }

      if (isset($oPost->it18_nomelograd) && trim($oPost->it18_nomelograd) != "") {
        $clitbirural->it18_nomelograd = $oPost->it18_nomelograd;
      } else {
        $clitbirural->it18_nomelograd = " ";
      }

      $clitbirural->it18_area = $oPost->it01_areaterreno;

      $clitbirural->incluir($clitbi->it01_guia);

      // Excluimos todas as localidades e apos inserimos a nova
      $clitbilocalidaderural->excluir('',' it33_guia = ' . $clitbi->it01_guia );

      $clitbilocalidaderural->it33_guia 					 = $clitbi->it01_guia;
      $clitbilocalidaderural->it33_localidaderural = $oPost->j137_sequencial;
      $clitbilocalidaderural->incluir( null );

      if ($clitbirural->erro_status == 0) {
        $lSqlErro = true;
      }

      $sMsgErro = $clitbirural->erro_msg;

      if (! $lSqlErro) {

        $aListaCaracImovel = explode("|", $oPost->valorCaracImovel);
        if (count($aListaCaracImovel) > 1) {
          foreach ( $aListaCaracImovel as $aChave ) {

            $aListaDadosCaracImovel = explode("X", $aChave);

            // $aListaDadosCaracImovel[0] -- Código da Característica
            // $aListaDadosCaracImovel[1] -- Valor  da Característica
            $clitbiruralcaract->it19_guia       = $clitbi->it01_guia;
            $clitbiruralcaract->it19_codigo     = $aListaDadosCaracImovel [0];
            $clitbiruralcaract->it19_valor      = $aListaDadosCaracImovel [1];
            $clitbiruralcaract->it19_tipocaract = '1';
            $clitbiruralcaract->incluir($clitbi->it01_guia, $aListaDadosCaracImovel [0]);

            $sMsgErro = $clitbiruralcaract->erro_msg;

            if ($clitbiruralcaract->erro_status == 0) {
              $lSqlErro = true;
              break;
            }
          }
        }
      }

      if (! $lSqlErro) {

        $aListaCaracUtil = explode("|", $oPost->valorCaracUtil);
        if (count($aListaCaracUtil) > 1) {

          foreach ( $aListaCaracUtil as $aChave ) {

            $aListaDadosCaracUtil = split("X", $aChave);

            // $aListaDadosCaracUtil[0] -- Código da Característica
            // $aListaDadosCaracUtil[1] -- Valor  da Característica
            $clitbiruralcaract->it19_guia       = $clitbi->it01_guia;
            $clitbiruralcaract->it19_codigo     = $aListaDadosCaracUtil [0];
            $clitbiruralcaract->it19_valor      = $aListaDadosCaracUtil [1];
            $clitbiruralcaract->it19_tipocaract = '2';
            $clitbiruralcaract->incluir($clitbi->it01_guia, $aListaDadosCaracUtil [0]);

            $sMsgErro = $clitbiruralcaract->erro_msg;

            if ($clitbiruralcaract->erro_status == 0) {

              $lSqlErro = true;
              break;
            }
          }
        }
      }
    }
  }

  if(!$lSqlErro){

    $clitbilogin->it13_guia = $clitbi->it01_guia;
    $clitbilogin->it13_id_usuario = db_getsession("DB_id_usuario");
    $clitbilogin->incluir($clitbi->it01_guia);

    if ($clitbilogin->erro_status == 0) {
      $lSqlErro = true;
    }

    $sMsgErro = $clitbilogin->erro_msg;
  }

  if (!$lSqlErro && trim($oPost->j01_matric) != "") {

    $rsPropri = $clpropri->sql_record($clpropri->sql_query($oPost->j01_matric));

    if ($clpropri->numrows > 0) {

      for($iInd = 0; $iInd < $clpropri->numrows; $iInd ++) {

        $oPropri = db_utils::fieldsMemory($rsPropri, $iInd);

        $clitbipropriold->it20_guia   = $clitbi->it01_guia;
        $clitbipropriold->it20_numcgm = $oPropri->j42_numcgm;
        $clitbipropriold->it20_pri    = 'f';

        $clitbipropriold->incluir($clitbi->it01_guia, $oPropri->j42_numcgm);

        $sMsgErro = $clitbipropriold->erro_msg;

        if ($clitbipropriold->erro_status == 0) {

          $lSqlErro = true;
          break;
        }
      }

      if ($lSqlErro) {

        $clitbipropriold->it20_guia = $clitbi->it01_guia;
        $clitbipropriold->it20_numcgm = $oPropri->j01_numcgm;
        $clitbipropriold->it20_pri = 't';

        $clitbipropriold->incluir($clitbi->it01_guia, $oPropri->j01_numcgm);

        if ($clitbipropriold->erro_status == 0) {
          $lSqlErro = true;
        }

        $sMsgErro = $clitbipropriold->erro_msg;
      }
    }
  }

  if (! $lSqlErro) {

    $clitbidadosimovel->it22_itbi        = $clitbi->it01_guia;
    $clitbidadosimovel->it22_setor       = $oPost->it22_setor;
    $clitbidadosimovel->it22_quadra      = $oPost->it22_quadra;
    $clitbidadosimovel->it22_lote        = $oPost->it22_lote;
    $clitbidadosimovel->it22_descrlograd = $oPost->it22_descrlograd;
    $clitbidadosimovel->it22_numero      = $oPost->it22_numero;
    $clitbidadosimovel->it22_compl       = $oPost->it22_compl;
    $clitbidadosimovel->it22_matricri    = $oPost->it22_matricri;
    $clitbidadosimovel->it22_quadrari    = $oPost->it22_quadrari;
    $clitbidadosimovel->it22_loteri      = $oPost->it22_loteri;
    $clitbidadosimovel->incluir(null);

    if ($clitbidadosimovel->erro_status == 0) {
      $lSqlErro = true;
    }

    $sMsgErro = $clitbidadosimovel->erro_msg;
  }

  if (! $lSqlErro) {

    $rsDadosPropri = $clitbimatric->sql_record($clitbimatric->sql_query_propri($clitbi->it01_guia));

    if ($clitbimatric->numrows > 0) {

      $oDadosPropri = db_utils::fieldsMemory($rsDadosPropri, 0);

      $clitbinome->it03_guia     = $clitbi->it01_guia;
      $clitbinome->it03_tipo     = 'T';
      $clitbinome->it03_princ    = 'true';
      $clitbinome->it03_nome     = addslashes($oDadosPropri->z01_nome);
      $clitbinome->it03_sexo     = 'm';
      $clitbinome->it03_cpfcnpj  = $oDadosPropri->z01_cgccpf;
      $clitbinome->it03_endereco = addslashes($oDadosPropri->z01_ender);
      $clitbinome->it03_numero   = $oDadosPropri->z01_numero;
      $clitbinome->it03_compl    = $oDadosPropri->z01_compl;
      $clitbinome->it03_cxpostal = $oDadosPropri->z01_cxpostal;
      $clitbinome->it03_bairro   = addslashes($oDadosPropri->z01_bairro);
      $clitbinome->it03_munic    = $oDadosPropri->z01_munic;
      $clitbinome->it03_uf       = $oDadosPropri->z01_uf;
      $clitbinome->it03_cep      = $oDadosPropri->z01_cep;
      $clitbinome->it03_mail     = $oDadosPropri->z01_email;
      $clitbinome->incluir(null);

      if ($clitbinome->erro_status == 0) {
        $lSqlErro = true;
      }

      $sMsgErro = $clitbinome->erro_msg;

      if (! $lSqlErro) {

        $clitbinomecgm->it21_itbinome = $clitbinome->it03_seq;
        $clitbinomecgm->it21_numcgm   = $oDadosPropri->z01_numcgm;
        $clitbinomecgm->incluir(null);

        if ($clitbinomecgm->erro_status == 0) {
          $lSqlErro = true;
        }

        $sMsgErro = $clitbinomecgm->erro_msg;
      }
    }
  }

  if (! $lSqlErro) {

    $rsDadosOutros = $clitbimatric->sql_record($clitbimatric->sql_query_outros_propri($clitbi->it01_guia));
    $iDadosOutros = $clitbimatric->numrows;

    if ($iDadosOutros > 0) {

      for($iInd = 0; $iInd < $iDadosOutros; $iInd ++) {

        $oDadosOutros = db_utils::fieldsMemory($rsDadosOutros, $iInd);

        $clitbinome->it03_guia     = $clitbi->it01_guia;
        $clitbinome->it03_tipo     = 'T';
        $clitbinome->it03_princ    = 'false';
        $clitbinome->it03_nome     = addslashes($oDadosOutros->z01_nome);
        $clitbinome->it03_sexo     = 'm';
        $clitbinome->it03_cpfcnpj  = $oDadosOutros->z01_cgccpf;
        $clitbinome->it03_endereco = addslashes($oDadosOutros->z01_ender);
        $clitbinome->it03_numero   = $oDadosOutros->z01_numero;
        $clitbinome->it03_compl    = $oDadosOutros->z01_compl;
        $clitbinome->it03_cxpostal = $oDadosOutros->z01_cxpostal;
        $clitbinome->it03_bairro   = addslashes($oDadosOutros->z01_bairro);
        $clitbinome->it03_munic    = "";
        $clitbinome->it03_uf       = $oDadosOutros->z01_uf;
        $clitbinome->it03_cep      = $oDadosOutros->z01_cep;
        $clitbinome->it03_mail     = $oDadosOutros->z01_email;
        $clitbinome->incluir(null);

        if ($clitbinome->erro_status == 0) {
          $lSqlErro = true;
        }

        $sMsgErro = $clitbinome->erro_msg;

        if (! $lSqlErro) {

          $clitbinomecgm->it21_itbinome = $clitbinome->it03_seq;
          $clitbinomecgm->it21_numcgm   = $oDadosOutros->z01_numcgm;
          $clitbinomecgm->incluir(null);

          if ($clitbinomecgm->erro_status == 0) {
            $lSqlErro = true;
          }

          $sMsgErro = $clitbinomecgm->erro_msg;
        }
      }
    }
  }

  if (! $lSqlErro) {

    $aListaFormaPag = explode("|", $oPost->listaFormas);

    foreach ( $aListaFormaPag as $aChave ) {

      $aListaValorFormaPag = split("X", $aChave);

      // $aListaValorFormaPag[0]  -- Código da Forma de Pagamento da Transação
      // $aListaValorFormaPag[1]  -- Valor  da Forma de Pagamento da Transação
      $clitbiformapagamentovalor->it26_guia                  = $clitbi->it01_guia;
      $clitbiformapagamentovalor->it26_itbitransacaoformapag = $aListaValorFormaPag [0];
      $clitbiformapagamentovalor->it26_valor                 = $aListaValorFormaPag [1];
      $clitbiformapagamentovalor->incluir(null);

      $sMsgErro = $clitbiformapagamentovalor->erro_msg;

      if ($clitbiformapagamentovalor->erro_status == 0) {

        $lSqlErro = true;
        break;
      }

    }

  }

  if (! $lSqlErro && isset($oPost->it29_setorloc) && trim($oPost->it29_setorloc) != "") {

    $clitbidadosimovelsetorloc->it29_itbidadosimovel = $clitbidadosimovel->it22_sequencial;
    $clitbidadosimovelsetorloc->it29_setorloc        = $oPost->it29_setorloc;
    $clitbidadosimovelsetorloc->incluir(null);

    if ($clitbidadosimovel->erro_status == 0) {
      $lSqlErro = true;
    }

    $sMsgErro = $clitbidadosimovelsetorloc->erro_msg;
  }

  db_fim_transacao($lSqlErro);

} else if (! isset($oGet->pri) && $oGet->tipo != "rural") {

  include(modification("itb1_itbi004.php"));
  exit();

} else if (isset($oPost->j01_matric) && trim($oPost->j01_matric) != "") {

  $rsConsultaDadosMatric = $cliptubase->sql_record($cliptubase->sql_query_regmovel($oPost->j01_matric));

  if ($cliptubase->numrows > 0) {

    $oDadosMatric = db_utils::fieldsMemory($rsConsultaDadosMatric, 0);
    $it01_areaterreno = $oDadosMatric->j34_area;

    if ($db21_codcli == 19985 || $db21_codcli == 100 ) {

      $it22_setor 	= $oDadosMatric->j05_codigoproprio;
      $it22_quadra	= $oDadosMatric->j06_quadraloc;
      $it22_lote 		= $oDadosMatric->j06_lote;
    } else {

      $it22_setor 	= $oDadosMatric->j34_setor;
      $it22_quadra	= $oDadosMatric->j34_quadra;
      $it22_lote 		= $oDadosMatric->j34_lote;
    }

    $it22_descrlograd = $oDadosMatric->j14_nome;
    $it22_compl 	    = $oDadosMatric->j39_compl;
    $it22_numero 	    = $oDadosMatric->j39_numero;
    $it05_frente 	    = $oDadosMatric->j36_testad;
    $it05_fundos 	    = $oDadosMatric->j36_testad;
    $it01_areatrans   = $oDadosMatric->j34_area;

    $it29_setorloc    = $oDadosMatric->j04_setorregimovel;
    $j05_descr        = $oDadosMatric->j69_descr;

    $it22_matricri    = $oDadosMatric->j04_matricregimo;
    $it22_quadrari    = $oDadosMatric->j04_quadraregimo;
    $it22_loteri      = $oDadosMatric->j04_loteregimo;

    $nLados           = ($oDadosMatric->j36_testad) ? ($oDadosMatric->j34_area / $oDadosMatric->j36_testad) : 0;

    $it05_direito     = round($nLados, 2);
    $it05_esquerdo    = round($nLados, 2);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
 <div class="container">
	<?php
    require_once(modification("forms/db_frmitbidadosimovel.php"));
  ?>
 </div>
</body>
</html>
<?php

if (isset($oGet->pri) && $oGet->tipo != "rural" && ! isset($oPost->incluir) && ! isset($oPost->alterar)) {

  $aDebitosMatric = $cliptubase->consultaDebitosMatricula($oPost->j01_matric);

  if (! empty($aDebitosMatric)) {

    $sMsg = '\n';

    $lTemInicialAberto = 0;

    foreach ( $aDebitosMatric as $oDebitosMatric ) {

      if ($db21_codcli == 19985) {

        if ( $oDebitosMatric->k03_tipo == 13 or $oDebitosMatric->k03_tipo == 18 ) {

          $lTemInicialAberto = 1;
        }
      }
      $sMsg .= "* {$oDebitosMatric->k03_descr}";
      $sMsg .= '\n';
    }

    if ( $lTemInicialAberto == 1 ) {

     	echo "<script>
            alert('Existe débito ajuizado em aberto para esta matrícula - procedimento não pode ser executado!');
            parent.location.href='itb1_itbi001.php?tipo=urbano';
            </script>";
    } else {

      echo " <script> 																		                                           ";
      echo " if( !confirm('Existe débito de: " . $sMsg . "para esta matrícula, deseja continuar?')){ ";
      echo "    parent.location.href='itb1_itbi001.php?tipo=urbano';								                 ";
      echo " }																					                                             ";
      echo " </script>																			                                         ";
    }
  }
}

if (isset($oPost->incluir)) {

  if ($lSqlErro) {

    db_msgbox($sMsgErro);

    $clitbidadosimovel->erro(true, false);

    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clitbidadosimovel->erro_campo != "") {
      echo "<script> document.form1." . $clitbidadosimovel->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1." . $clitbidadosimovel->erro_campo . ".focus();</script>";
    }

  } else {

    db_msgbox($clitbi->erro_msg);

    echo " <script>

			      parent.document.formaba.dados.disabled    = false;
            parent.document.formaba.transm.disabled   = false;
            parent.document.formaba.inter.disabled    = false;
            parent.document.formaba.compnome.disabled = false;
            parent.document.formaba.constr.disabled   = false;

   		      parent.iframe_dados.location.href 	 = 'itb1_itbidadosimovel002.php?chavepesquisa=" . $clitbi->it01_guia . "&abas=1&tipo=" . $oGet->tipo . "';
            parent.iframe_transm.location.href 	 = 'itb1_itbinome001.php?tiponome=t&it03_guia=" . $clitbi->it01_guia . "';
            parent.iframe_compnome.location.href = 'itb1_itbinomecomp001.php?tiponome=c&it03_guia=" . $clitbi->it01_guia . "';
            parent.iframe_constr.location.href 	 = 'itb1_itbiconstr001.php?it08_guia=" . $clitbi->it01_guia . "&tipo=" . $oGet->tipo . "';

			      parent.mo_camada('transm');
          </script>";
  }
}
?>