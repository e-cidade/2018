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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_utils.php"));
require(modification("libs/db_app.utils.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_rhpessoal_classe.php"));
include(modification("classes/db_rhpesfgts_classe.php"));
include(modification("classes/db_rhpesdoc_classe.php"));
include(modification("classes/db_rhpessoalmov_classe.php"));
include(modification("classes/db_rhraca_classe.php"));
include(modification("classes/db_rhinstrucao_classe.php"));
include(modification("classes/db_rhestcivil_classe.php"));
include(modification("classes/db_rhnacionalidade_classe.php"));
include(modification("classes/db_cfpess_classe.php"));
include(modification("classes/db_rhfotos_classe.php"));
include(modification("classes/db_rhpesorigem_classe.php"));
include(modification("classes/db_cgm_classe.php"));
include(modification("classes/db_rhdepend_classe.php"));
include(modification("classes/db_rhcfpessmatr_classe.php"));
include(modification("classes/db_rhcontratoemergencial_classe.php"));
include(modification("classes/db_rhcontratoemergencialrenovacao_classe.php"));
include(modification("libs/db_libpessoal.php"));

$clrhpessoal                      = new cl_rhpessoal;
$clrhpesfgts                      = new cl_rhpesfgts;
$clrhpesdoc                       = new cl_rhpesdoc;
$clrhpessoalmov                   = new cl_rhpessoalmov;
$clrhdepend                       = new cl_rhdepend;
$clrhraca                         = new cl_rhraca;
$clrhinstrucao                    = new cl_rhinstrucao;
$clrhestcivil                     = new cl_rhestcivil;
$clrhnacionalidade                = new cl_rhnacionalidade;
$clcfpess                         = new cl_cfpess;
$clrhfotos                        = new cl_rhfotos;
$clrhpesorigem                    = new cl_rhpesorigem;
$clcgm                            = new cl_cgm;
$clrhcfpessmatr                   = new cl_rhcfpessmatr;
$clrhcontratoemergencial          = new cl_rhcontratoemergencial;
$clrhcontratoemergencialrenovacao = new cl_rhcontratoemergencialrenovacao;

db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;

$ano = db_anofolha();
$mes = db_mesfolha();
$visibilityContratoEmergencial = false;

if (isset($incluir)) {
  $sqlerro = false;
  db_inicio_transacao();
  if (trim($rh01_anoche) == "" && $rh01_nacion != 10) {
    $sqlerro = true;
    $erro_msg = "Ano de chegada inválido.";
  }
  if ($sqlerro == false) {
    $result_parametros = $clcfpess->sql_record($clcfpess->sql_query_file($ano, $mes, db_getsession("DB_instit"), "*", "r11_concatdv", null));
    if ($clcfpess->numrows > 0) {
      db_fieldsmemory($result_parametros, 0);
      //   if(trim($r11_ultreg)!="" && $r11_ultreg!=0){
    // $rh01_regist = $r11_ultreg;
    // $registparam = $r11_ultreg+1;
    //   }
    }
    $result_parametros_matr = $clrhcfpessmatr->sql_record($clrhcfpessmatr->sql_query_file(null,
                                                                                          "rh13_matricula,
                                                                                           rh13_unificada",
                                                                                           null,
                                                                                           "rh13_instit=" . db_getsession("DB_instit")));
    if ($clrhcfpessmatr->numrows > 0) {
      db_fieldsmemory($result_parametros_matr, 0);
      if (trim($rh13_matricula) != "" && $rh13_matricula != 0) {
        $rh01_regist = $rh13_matricula;
        $registparam = $rh13_matricula + 1;
      }
    }

    if (isset($r11_concatdv) && $r11_concatdv == "t") {
      $rh01_regist .= db_calculaDV($rh01_regist);
    }

    if (!isset($rh01_reajusteparidade)) {
      $clrhpessoal->rh01_reajusteparidade = '0';
    }

    $clrhpessoal->rh01_anoche = "$rh01_anoche";
    $clrhpessoal->incluir($rh01_regist);
    $rh01_regist = $clrhpessoal->rh01_regist;
    $erro_msg = $clrhpessoal->erro_msg;
    if ($clrhpessoal->erro_status == 0) {
      $sqlerro = true;
    }

    if ($sqlerro == false) {
      if (trim($rh15_banco) != "") {
        $clrhpesfgts->incluir($rh01_regist);
        if ($clrhpesfgts->erro_status == 0) {
          $erro_msg = $clrhpesfgts->erro_msg;
          $sqlerro = true;
        }
      }
    }

    if ($sqlerro == false && isset($registparam) && trim($registparam) != "") {
      $db_where = "";
      if ($rh13_unificada == "t") {
        $db_where = " rh13_unificada='t' ";
      } else {
        $db_where = " rh13_instit=" . db_getsession("DB_instit");
      }

      $clrhcfpessmatr->rh13_matricula = $registparam;
      $clrhcfpessmatr->alterar_where(null, $db_where);
      if ($clrhcfpessmatr->erro_status == 0) {
        $erro_msg = $clrhcfpessmatr->erro_msg;
        $sqlerro = true;
      }
      /*
		  $clcfpess->r11_anousu = $ano;
      $clcfpess->r11_mesusu = $mes;
      $clcfpess->r11_ultreg = $registparam;
      //$clcfpess->r11_instit = db_getsession("DB_instit");
      $clcfpess->alterar($ano,$mes,null);
      if($clcfpess->erro_status==0){
        $erro_msg = $clcfpess->erro_msg;
        $sqlerro=true;
      }
			*/
    }

    if ($sqlerro == false && trim($localrecebefoto) != "") {
      // Abre o arquivo
      $arquivograva = fopen($localrecebefoto, "rb");
      // Lê o arquivo inteiro
      $dados = fread($arquivograva, filesize($localrecebefoto));
      // Fecha o arquivo
      fclose($arquivograva);

      // Criando o Objeto.
      $oidgrava = pg_lo_create();
      $clrhfotos->rh50_oid = $oidgrava;
      $clrhfotos->rh50_numcgm = $rh01_numcgm;
      $clrhfotos->incluir($rh01_numcgm);
      if ($clrhfotos->erro_status == 0) {
        $erro_msg = $clrhfotos->erro_msg;
        $sqlerro = true;
      }

      // Abrindo o objeto
      $objeto = pg_lo_open($conn, $oidgrava, "w");

      // Inserindo Dados no arquivo
      pg_lo_write($objeto, $dados);

      // Fechando a conexao com o objeto
      pg_lo_close($objeto);
    }

    if(isset($contratoEmergencial) && $contratoEmergencial) {

      if(empty($rh164_datafim)) {
        $erro_msg = "Preencha a data de término do contrato emergencial.";
        $sqlerro = true;
      }

      $dataFim = new DBDate($rh164_datafim);

      if($dataFim->getDate() <= $clrhpessoal->rh01_admiss) {
        $erro_msg = "A data de término deve ser maior que a data de admissão.";
        $sqlerro = true;
      }

      if ($sqlerro == false) {

        $clrhcontratoemergencial->rh163_matricula = $clrhpessoal->rh01_regist;
        $clrhcontratoemergencial->incluir(null);

        if ($clrhcontratoemergencial->erro_status == 0) {
          $erro_msg = $clrhcontratoemergencial->erro_msg;
          $sqlerro = true;
        }
      }

      if ($sqlerro == false) {

        $clrhcontratoemergencialrenovacao->rh164_contratoemergencial = $clrhcontratoemergencial->rh163_sequencial;
        $clrhcontratoemergencialrenovacao->rh164_descricao           = 'Contrato';
        $clrhcontratoemergencialrenovacao->rh164_datainicio          = $clrhpessoal->rh01_admiss;
        $clrhcontratoemergencialrenovacao->rh164_datafim             = $dataFim->getDate();
        $clrhcontratoemergencialrenovacao->incluir(null);

        if ($clrhcontratoemergencialrenovacao->erro_status == 0) {
          $erro_msg = $clrhcontratoemergencialrenovacao->erro_msg;
          $sqlerro = true;
        }
      }
    }

  }
  if ($rhimp != '') {

    $rsdep = $clrhdepend->sql_record($clrhdepend->sql_query(null, 'rhdepend.*', '', "rh31_regist =$rhimp"));
    $numrows = $clrhdepend->numrows;
    if ($clrhdepend->numrows > 0) {
      for($i = 0; $i < $numrows; $i ++) {
        db_fieldsmemory($rsdep, $i);
        $clrhdepend->rh31_regist = $rh01_regist;
        $clrhdepend->rh31_nome = $rh31_nome;
        $clrhdepend->rh31_dtnasc = $rh31_dtnasc;
        $clrhdepend->rh31_gparen = $rh31_gparen;
        $clrhdepend->rh31_depend = $rh31_depend;
        $clrhdepend->rh31_irf = $rh31_irf;
        $clrhdepend->rh31_especi = $rh31_especi;
        $clrhdepend->incluir(null);
        if ($clrhdepend->erro_status == 0) {

          $sqlerro = true;
          $erro_msg = $clrhdepend->erro_msg . "\\nImportaçao cancelada.\\nDependente com erro.";
        }

      }
    }
      /**
       * Importamos as movimentações do funcionário
       */
    if (!$sqlerro) {

      $oDaoPesMov = new cl_rhpessoalmov;
      $sSqlPesMov = $oDaoPesMov->sql_query_file(null,
                                                null,
                                                "*",
                                                null,
                                                "rh02_anousu    = ".db_anofolha()."
                                                and rh02_mesusu = ".db_mesfolha()."
                                                and rh02_regist = {$rhimp}");
      $rsPesMov    = $oDaoPesMov->sql_record($sSqlPesMov);
      $iNumRowsMov = $oDaoPesMov->numrows;
      if ($iNumRowsMov > 0) {

        for ($iInd  = 0; $iInd < $iNumRowsMov; $iInd++) {

          $oMovimento = db_utils::fieldsMemory($rsPesMov, $iInd);
          $oDaoPesMov->rh02_anousu   = $oMovimento->rh02_anousu;
          $oDaoPesMov->rh02_mesusu   = $oMovimento->rh02_mesusu;
          $oDaoPesMov->rh02_codreg   = $oMovimento->rh02_codreg;
          $oDaoPesMov->rh02_equip    = $oMovimento->rh02_equip;
          $oDaoPesMov->rh02_folha    = $oMovimento->rh02_folha;
          $oDaoPesMov->rh02_fpagto   = $oMovimento->rh02_fpagto;
          $oDaoPesMov->rh02_hrsmen   = $oMovimento->rh02_hrsmen;
          $oDaoPesMov->rh02_hrssem   = $oMovimento->rh02_hrssem;
          $oDaoPesMov->rh02_instit   = $oMovimento->rh02_instit;
          $oDaoPesMov->rh02_lota     = $oMovimento->rh02_lota;
          $oDaoPesMov->rh02_ocorre   = $oMovimento->rh02_ocorre;
          $oDaoPesMov->rh02_regist   = $rh01_regist;
          $oDaoPesMov->rh02_equip    = str_replace("f","false",$oMovimento->rh02_equip);
          $oDaoPesMov->rh02_salari   = $oMovimento->rh02_salari;
          $oDaoPesMov->rh02_tbprev   = $oMovimento->rh02_tbprev;
          $oDaoPesMov->rh02_tipsal   = $oMovimento->rh02_tipsal;
          $oDaoPesMov->rh02_tpcont   = $oMovimento->rh02_tpcont;
          $oDaoPesMov->rh02_vincrais = $oMovimento->rh02_vincrais;
          $oDaoPesMov->rh02_funcao   = $oMovimento->rh02_funcao;
          $oDaoPesMov->rh02_deficientefisico = str_replace("f","false",$oMovimento->rh02_deficientefisico);
          $oDaoPesMov->rh02_portadormolestia = str_replace("f","false",$oMovimento->rh02_portadormolestia);
          $oDaoPesMov->rh02_diasgozoferias   = $oMovimento->rh02_diasgozoferias;
          $oDaoPesMov->rh02_cedencia         = $oMovimento->rh02_cedencia;
          $oDaoPesMov->rh02_cnpjcedencia     = $oMovimento->rh02_cnpjcedencia;
          $oDaoPesMov->rh02_datacedencia     = $oMovimento->rh02_datacedencia;
          $oDaoPesMov->rh02_onus             = $oMovimento->rh02_onus;
          $oDaoPesMov->rh02_ressarcimento    = $oMovimento->rh02_ressarcimento;
          $oDaoPesMov->rh02_horasdiarias     = $oMovimento->rh02_horasdiarias;
          $oDaoPesMov->incluir(null,$oMovimento->rh02_instit);
          if ($oDaoPesMov->erro_status == 0) {

            $sqlerro  = true;
            $erro_msg = "pes_mov".$oDaoPesMov->erro_msg;
            break;

          }
        }
      }
    }

    /**
     * Importamos a tabela rhpesorigem.
     */
    if (!$sqlerro) {

      $oDaoPesOrigem = db_utils::getDao("rhpesorigem");
      $rsPesOrigem   = $oDaoPesOrigem->sql_record($oDaoPesOrigem->sql_query_file($rhimp));
      if ($oDaoPesOrigem->numrows > 0) {

        $oOrigemPes = db_utils::fieldsMemory($rsPesOrigem, 0) ;
        $oDaoPesOrigem->rh21_regist = $rh01_regist;
        $oDaoPesOrigem->rh21_regpri = $oOrigemPes->rh21_regpri;
        $oDaoPesOrigem->incluir($rh01_regist);
        if ($oDaoPesOrigem->erro_status == 0) {

          $sqlerro  = true;
          $erro_msg = $oDaoPesOrigem->erro_msg;

        }
      }
    }

    /**
     * Importamos o cadastro do banco
     */
    if (!$sqlerro) {

      $oDaoPesBanco = db_utils::getDao("rhpesbanco");
      $rsPesBanco   = $oDaoPesBanco->sql_record($oDaoPesBanco->sql_query_file($oMovimento->rh02_seqpes));
      if ($oDaoPesBanco->numrows > 0) {

        $oPesBanco = db_utils::fieldsMemory($rsPesBanco, 0);
        $oDaoPesBanco->rh44_agencia   = $oPesBanco->rh44_agencia;
        $oDaoPesBanco->rh44_codban    = $oPesBanco->rh44_codban;
        $oDaoPesBanco->rh44_conta     = $oPesBanco->rh44_conta;
        $oDaoPesBanco->rh44_dvagencia = $oPesBanco->rh44_dvagencia;
        $oDaoPesBanco->rh44_dvconta   = $oPesBanco->rh44_dvconta;
        $oDaoPesBanco->rh44_seqpes    = $oDaoPesMov->rh02_seqpes;
        $oDaoPesBanco->incluir($oDaoPesMov->rh02_seqpes);
        if ($oDaoPesBanco->erro_status == 0) {

          $sqlerro  = true;
          $erro_msg = $oDaoPesBanco->erro_msg;

        }
      }
    }
    /**
     * Importamos o padrao de pagamento (rhpespadrao)
     */
    if (!$sqlerro) {

      $oDaoPesPadrao = db_utils::getDao("rhpespadrao");
      $rsPesPadrao   = $oDaoPesPadrao->sql_record($oDaoPesPadrao->sql_query_file($oMovimento->rh02_seqpes));
      if ($oDaoPesPadrao->numrows > 0) {

        $oPesPadrao = db_utils::fieldsMemory($rsPesPadrao, 0);
        $oDaoPesPadrao->rh03_anousu = $oPesPadrao->rh03_anousu;
        $oDaoPesPadrao->rh03_mesusu = $oPesPadrao->rh03_mesusu;
        $oDaoPesPadrao->rh03_padrao = $oPesPadrao->rh03_padrao;
        $oDaoPesPadrao->rh03_regime = $oPesPadrao->rh03_regime;
        $oDaoPesPadrao->rh03_seqpes = $oDaoPesMov->rh02_seqpes;
        $oDaoPesPadrao->incluir($oDaoPesMov->rh02_seqpes);
        if ($oDaoPesPadrao->erro_status == 0) {

          $sqlerro  = true;
          $erro_msg = $oDaoPesPadrao->erro_msg;

        }
      }
    }
    /**
     * importamos o cargo do movimento.(rhpescargo)
     */
    if (!$sqlerro) {

      $oDaoPesCargo = db_utils::getDao("rhpescargo");
      $rsCargo      = $oDaoPesCargo->sql_record($oDaoPesCargo->sql_query_file($oMovimento->rh02_seqpes));
      if ($oDaoPesCargo->numrows > 0) {

        $oCargo = db_utils::fieldsMemory($rsCargo, 0);
        $oDaoPesCargo->rh20_cargo  = $oCargo->rh20_cargo;
        $oDaoPesCargo->rh20_instit = $oCargo->rh20_instit;
        $oDaoPesCargo->rh20_seqpes = $oDaoPesMov->rh02_seqpes;
        $oDaoPesCargo->incluir($oDaoPesMov->rh02_seqpes);
        if ($oDaoPesCargo->erro_status == 0 ) {

          $sqlerro  = true;
          $erro_msg = $oDaoPesCargo->erro_msg;

        }
      }
    }
    if (!$sqlerro) {

      $oDaoPesProp = db_utils::getDao("rhpesprop");
      $rsPesProp   = $oDaoPesProp->sql_record($oDaoPesProp->sql_query_file($rhimp));
      if ($oDaoPesProp->numrows > 0) {

        $oPesProp = db_utils::fieldsMemory($rsPesProp, 0);
        $oDaoPesProp->rh19_propi = $oPesProp->rh19_propi;
        $oDaoPesProp->rh19_regist = $rh01_regist;
        $oDaoPesProp->incluir($rh01_regist);
        if ($oDaoPesProp->erro_status  == 0) {

          $sqlerro  = true;
          $erro_msg = $oDaoPesProp->erro_msg;
        }
      }
    }

    if (!$sqlerro) {

      $oDaoLocaltrabalho = db_utils::getDao("rhpeslocaltrab");
      $sSqlLocaltrabalho = $oDaoLocaltrabalho->sql_query_file(null,"*",null,"rh56_seqpes = {$oMovimento->rh02_seqpes}");
      $rsLocalTrabalho   = $oDaoLocaltrabalho->sql_record($sSqlLocaltrabalho);
      $iNumRowsTrabalho  = $oDaoLocaltrabalho->numrows;
      if ($iNumRowsTrabalho > 0) {

        for ($iInd = 0; $iInd < $iNumRowsTrabalho; $iInd++) {

          $oLocalTrabalho = db_utils::fieldsMemory($rsLocalTrabalho, $iInd);
          $oDaoLocaltrabalho->rh56_localtrab = $oLocalTrabalho->rh56_localtrab;
          $oDaoLocaltrabalho->rh56_princ     = str_replace("f","false",$oLocalTrabalho->rh56_princ);
          $oDaoLocaltrabalho->rh56_seqpes    = $oDaoPesMov->rh02_seqpes;
          $oDaoLocaltrabalho->incluir(null);
          if ($oDaoLocaltrabalho->erro_status == 0) {

            $sqlerro  = true;
            $erro_msg = $oDaoLocaltrabalho->erro_msg;
            break;

          }
        }
      }
    }
    if (!$sqlerro) {

      //Importamos os documentos do servidor;
      $oDaoPesDoc = new cl_rhpesdoc;
      $rsPesDoc    = $oDaoPesDoc->sql_record($oDaoPesDoc->sql_query($rhimp));
      if ($oDaoPesDoc->numrows > 0) {

        $oPesDoc = db_utils::fieldsMemory($rsPesDoc, 0);
        $oDaoPesDoc->rh16_carth_n   = $oPesDoc->rh16_carth_n;
        $oDaoPesDoc->rh16_carth_val = $oPesDoc->rh16_carth_val;
        $oDaoPesDoc->rh16_catres    = $oPesDoc->rh16_catres;
        $oDaoPesDoc->rh16_ctps_d    = $oPesDoc->rh16_ctps_d;
        $oDaoPesDoc->rh16_ctps_n    = $oPesDoc->rh16_ctps_n;
        $oDaoPesDoc->rh16_ctps_s    = $oPesDoc->rh16_ctps_s;
        $oDaoPesDoc->rh16_ctps_uf   = $oPesDoc->rh16_ctps_uf;
        $oDaoPesDoc->rh16_pis       = $oPesDoc->rh16_pis;
        $oDaoPesDoc->rh16_regist    = $rh01_regist;
        $oDaoPesDoc->rh16_reserv    = $oPesDoc->rh16_reserv;
        $oDaoPesDoc->rh16_secaoe    = $oPesDoc->rh16_secaoe;
        $oDaoPesDoc->rh16_titele    = $oPesDoc->rh16_titele;
        $oDaoPesDoc->rh16_zonael    = $oPesDoc->rh16_zonael;
        $oDaoPesDoc->incluir($rh01_regist);
        if ($oDaoPesDoc->erro_status == 0) {

            $sqlerro  = true;
            $erro_msg = $oDaoPesDoc->erro_msg;
        }
      }
    }
  }
  db_fim_transacao($sqlerro);
} else if (isset($rh01_numcgm)) {
  $limpar = true;
  if (trim($rh01_numcgm) != "") {
    $result_dados = $clcgm->sql_record($clcgm->sql_query_file($rh01_numcgm, "z01_sexo as rh01_sexo, z01_estciv as rh01_estciv, z01_nacion as rh01_nacion, z01_nasc as rh01_nasc, z01_nome, z01_numcgm as rh01_numcgm"));
    if ($clcgm->numrows > 0) {
      db_fieldsmemory($result_dados, 0);
      $limpar = false;
      if (trim($rh01_nasc) != "") {
        $rh01_nasc_dia = db_subdata($rh01_nasc, "d");
        $rh01_nasc_mes = db_subdata($rh01_nasc, "m");
        $rh01_nasc_ano = db_subdata($rh01_nasc, "a");
      }
    } else {
      $z01_nome = "CÓDIGO (" . $rh01_numcgm . ") NÃO ENCONTRADO";
    }
  }
  if ($limpar == true) {
    unset($rh01_sexo, $rh01_estciv, $rh01_nacion, $rh01_nasc, $rh01_numcgm);
  }
} else if (isset($chavepesquisa)) {
  $db_opcao = 1;
  $db_botao = true;

  $result = $clrhpessoal->sql_record($clrhpessoal->sql_query($chavepesquisa));
  if ($clrhpessoal->numrows > 0) {
    db_fieldsmemory($result, 0);
    $result_rhpesfgts = $clrhpesfgts->sql_record($clrhpesfgts->sql_query_banco($rh01_regist, "rh15_data,rh15_banco,rh15_agencia,rh15_agencia_d,rh15_contac,rh15_contac_d,db90_descr"));
    if ($clrhpesfgts->numrows > 0) {
      db_fieldsmemory($result_rhpesfgts, 0);
    }
    $rhimp = $rh01_regist;
    $rh01_regist = null;
    $rh01_admiss_dia = null;
    $rh01_admiss_mes = null;
    $rh01_admiss_ano = null;
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js,dates.js,
               datagrid.widget.js");
  db_app::load("estilos.css,grid.style.css");
?>
<style type="text/css">
  .fieldset-hr {
    border:none;
    border-top: 1px outset #000;
  }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
      <?
      include(modification("forms/db_frmrhpessoal.php"));
      ?>
      </center>
    </td>
  </tr>
</table>
</body>
</html>
<script>
// form - formulário onde estão os campos
// foco - campo que receberá foco no início
// tfoco- true se programador quer que campo informado receba o foco e false se não quer
// inicio - índice inicial da tabulação. Caso passado 0 (zero), a função começará do 1 (um)
// campo  - campo que receberá o foco ao sair do último campo
// tcampo - true se programador quer usar a variável campo
// VER COM PAULO
function js_tabulacao(form,foco,tfoco,inicio,campo,tcampo){
  eval("x = document."+form+";");

  if(inicio == 0){  // Seta índice inicial
    indx = 1;
  }else{
    indx = inicio;
  }

  mark = 0;
  for(i=0; i<x.length; i++){
    if(x.elements[i].disabled == false){                // Se campo estiver desabilitado, não recebe tabIndex
      if(x.elements[i].type == 'select-one'){           // Testa se campo é um select
        if(x.elements[i].name == "rh01_sexo"){
          if(x.rh01_regist.readOnly == true){
            x.elements[i].tabIndex = 2;
          }else{
            x.elements[i].tabIndex = 3;
          }
        }else{
          x.elements[i].tabIndex = indx;                // Seta índice da tabulação
        }
        mark = i;
        indx ++;                                        // Valor do próximo índice
      }else if(x.elements[i].type == 'text'){           // Testa se campo é um text e não é readOnly
        if(x.elements[i].readOnly == false){
          if(x.elements[i].name == "rh01_numcgm"){
            if(x.rh01_regist.readOnly == true){
              x.elements[i].tabIndex = 1;
            }else{
              x.elements[i].tabIndex = 2;
            }
          }else{
            x.elements[i].tabIndex = indx;
          }
          indx ++;
          mark = i;
        }else{
          x.elements[i].tabIndex = x.length;
        }
      }else if(x.elements[i].type == 'checkbox'){       // Testa se campo é um checkbox
        x.elements[i].tabIndex = indx;
        indx ++;
        mark = i;
      }else if(x.elements[i].type == 'button'){         // Testa se é um botão, se for, testa se é botão ao lado das datas
        if(x.elements[i].value != "D"){
          x.elements[i].tabIndex = indx;
          indx ++;
          mark = i;
        }
      }else if(x.elements[i].type == 'submit'){         // Testa se é um botão do tipo submit
        x.elements[i].tabIndex = indx;
        indx ++;
        mark = i;
      }else if(x.elements[i].type == 'reset'){          // Testa se é um botão do tipo reset
        x.elements[i].tabIndex = indx;
        indx ++;
        mark = i;
      }
    }
  }
  if(tfoco == true){                                    // Se programador quer focar o campo informado, entrará
    eval("x."+foco+".focus();");
  }
  if(mark > 0 && 1==2){
    if(x.elements[mark]){
      if(x.elements[mark].onblur){
        x.elements[mark].onblur+= eval("x."+campo+".focus()");
      }else{
        x.elements[mark].onblur = eval("x."+campo+".focus()");
      }
    }
  }
}
if(document.form1.rh01_regist.readOnly == true){
  js_tabulacao("form1","rh01_numcgm",true,0,"rh01_numcgm",true);
}else{
  js_tabulacao("form1","rh01_regist",true,0,"rh01_regist",true);
}
</script>
<?
if(isset($incluir)){
  if($sqlerro==true){
    db_msgbox($erro_msg);
    if($clrhpessoal->erro_campo!=""){
      echo "<script> document.form1.".$clrhpessoal->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clrhpessoal->erro_campo.".focus();</script>";
    };
  }else{
   db_msgbox($erro_msg);
   db_redireciona("pes1_rhpessoal005.php?liberaaba=true&chavepesquisa=$rh01_regist&rhimp=$rhimp");
  }
}