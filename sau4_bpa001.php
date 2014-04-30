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
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("libs/db_stdlibwebseller.php");
db_postmemory($HTTP_POST_VARS);
$clrotulo           = new rotulocampo ();
$oDaoSauFechamento  = db_utils::getdao('sau_fechamento');
$oDaoSauFecharquivo = db_utils::getdao('sau_fecharquivo');
$oDaoSauFechapront  = db_utils::getdao('sau_fechapront');
$oDaoUnidades       = db_utils::getdao('unidades');
$oDaodbconfig       = db_utils::getdao('db_config');
$db_opcao           = 1;
$db_botao           = true;
$sd99_i_login       = db_getsession ( "DB_id_usuario" );
$dHoje              = date("Y-m-d", db_getsession("DB_datausu"));
$desabilita         = "";
$sSql               = $oDaodbconfig->sql_query_file(db_getsession("DB_instit") ,"nomeinst as snomedepart,cgc as cnpj");
$resConfig          = $oDaodbconfig->sql_record($sSql);
db_fieldsmemory ($resConfig, 0);

$oSauConfig = loadConfig("sau_config");
if ($oSauConfig != false) {

  $sSigla   = $oSauConfig->s103_c_bpasigla;
  $sDestino = $oSauConfig->s103_c_bpasecrdestino;
  $iCidade  = $oSauConfig->s103_c_bpaibge;

}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <?
      db_app::load("scripts.js");
      db_app::load("prototype.js");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
    <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
      <tr>
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"><br>
          <center>
          
          <form name="form1" method="post" action="">
            <center>
            <fieldset style="width: 40%"><legend><b>Gerador de Arquivo:</b></legend>
              <table border="0" align="center">
                <tr>
                  <td colspan="2">
                    <b>Tipo de BPA:</b>
                    <?
                      $arr_tipo = array ("02" => "Individual", "01" => "Consolidado" );
                      db_select('sTipo', $arr_tipo, true, 4 );
                    ?>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <fieldset><legend><b>Competência:</b></legend>
                      <table>
                        <tr>
                          <td>
                            <?
                              db_ancora("<b>Competencia:</b>", "js_pesquisasd98_i_fechamento(true);", $db_opcao );
                            ?>
                          </td>
                          <td colspan="3"> 
                            <?
                              db_input('linhas', 5, @$Ilinhas, true, 'hidden', $db_opcao, "");
                              db_input('campocontrole', 2, "" , false, 'hidden', 3, "");
                              db_input('sd97_i_codigo', 2, @$Isd97_i_compmes, true, 'hidden', 3,'');
                              db_input('sd97_i_compmes', 2, @$Isd97_i_compmes, true, 'text', 3,'');
                              db_input('sd97_i_compano', 4, @$Isd97_i_compano, true, 'text', 3, "");
                              if (isset ( $sd97_i_codigo)) {

                                if ($sd97_i_financiamento == '') {
                                  $strfi  = " and sd97_i_financiamento = $sd97_i_financiamento";
                                } else {
                                  $strfi  = "";
                                }
                                $sSql    = $oDaoSauFechamento->sql_query("", "sd97_c_tipo", "", 
                                                                       " sd97_i_codigo=$sd97_i_codigo ");
                                $result1 = $oDaoSauFechamento->sql_record($sSql);
                                $sSql    = $oDaoSauFechapront->sql_query("", "sd98_i_codigo", "", 
                                                                          " sd97_i_codigo=$sd97_i_codigo ");
                                $result2 = $oDaoSauFechapront->sql_record ($sSql);
                                if ($oDaoSauFechamento->numrows > 0) {

                                  db_fieldsmemory ( $result1, 0 );
                                  if ($sd97_c_tipo == "Aberta") {

                                    $desabilita = "disabled";
                                    db_msgbox ( "Competência está Aberta" );

                                  } else {
                                     $desabilita = "";
                                  }

                                }
                                if ($oDaoSauFechapront->numrows > 0) {

                                  db_fieldsmemory ( $result2, 0 );
                                  $desabilita = "";
                                         
                                } else {

                                  $desabilita = "disabled";
                                  db_msgbox("Nenhum registro para gerar o arquivo");

                                }
                               }
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td nowrap title="<?=@$Tsd97_d_dataini?>">
                            <b>Período de Fechamento :</b>
                          </td>
                          <td> 
                            <? db_inputdata('sd97_d_dataini', @$sd97_d_dataini_dia, @$sd97_d_dataini_mes, 
                                            @$sd97_d_dataini_ano, true, 'text', 3);?>

                            A

                            <? db_inputdata('sd97_d_datafim', @$sd97_d_datafim_dia, @$sd97_d_datafim_mes,
                                  @$sd97_d_datafim_ano, true, 'text', 3);?>
                          </td>
                        </tr>
                        <tr>
                          <td><b>Tipo de Fianciamento:</b></td>
                          <td colspan="3">
                            <?
                              db_input('sd97_i_financiamento', 6, @$sd97_i_financiamento, true, 'hidden', 3, "");
                              db_input('sd65_c_nome', 60, @$sd65_c_nome, true, 'text', 3, "");
                            ?>
                          </td>
                        </tr>
                      </table>
                    </fieldset>
                  </td>
                </tr>  
                <tr>
                  <td colspan="2">
                    <fieldset><legend><b>UPS:</b></legend>
                      <?
                        $sSql       = $oDaoUnidades->sql_query("", "sd02_i_codigo,descrdepto");
                        $rsUnidades = $oDaoUnidades->sql_record($sSql);
                        db_multiploselect("sd02_i_codigo", "descrdepto", "nselecionados", "sselecionados", $rsUnidades,
                                          array(), 5, 250);
                        db_input ('sd24_i_unidade', 100, "", true, 'hidden', 1, "" );
                      ?>
                    </fieldset>
                  </td>
                </tr>
                <tr>
                  <td colspan="3">
                    <fieldset><legend><b>Orgão Responsável pela informação:</b></legend>
                      <table>
                        <tr>
                          <td>
                            <b>Nome:</b>
                          </td>
                          <td>
                            <?
                              db_input('snomedepart', 40, @$Lsnomedepart, true, 'text', 3, ""); 
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td nowrap title="Sigla">
                            <b>Sigla:</b>
                          </td>
                          <td colspan=3>
                            <?
                              db_input('sSigla', 6, @$siglas, true, 'text', 3, "");
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <b>CNPJ:</b>
                          </td>
                          <td>
                            <?
                              db_input('cnpj', 30, @$cnpj, true, 'text', 3, "");
                            ?>
                          </td>
                        </tr>
                      </table>
                    </fieldset>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <fieldset><legend><b>Secretaria de Saúde de Destino dos B.P.A(s):</b></legend>
                      <table>
                        <tr>
                          <td nowrap title="Sec. de Destino ">
                            <B>Sec. de Destino:</b>
                          </td>
                          <td colspan=3>
                            <?
                              db_input('sDestino', 40, @$destino, true, 'text', 3, "");
                            ?>
                          </td>
                        </tr>
                        <tr>
                          <td>
                            <b>Orgão:</b>
                          </td>
                          <td>
                            <input name="iOrgao" id="orgMunicipal" type="radio" value="1" checked >Municipal
                            <br>
                            <input name="iOrgao" id="orgEstadual"  type="radio" value="2">Estadual
                          </td>
                        </tr>  
                      </table>
                    </fieldset>
                  </td>
                </tr>
                <tr>
                  <td colspan="2">
                    <fieldset><legend><b>Arquivo de Produção:</b></legend>
                      <table>
                        <tr>
                          <td>
                            <b>Arquivo:</b>
                          </td>
                          <td>
                            PA
                            <?
                              db_input('sNomeArquivo', 8, @$sNomeArquivo, true, 'text', $db_opcao, "","","","",8);
                            ?>
                            .<span id="nomeExtencao" ></span>
                          </td>
                        </tr>
                      </table>
                    </fieldset>
                  </td>
                </tr>
              </table>
            </fieldset>
            </center>
            <?
              $regerar = false;
              if (isset($sd97_i_codigo)) {

                $sSql   = $oDaoSauFechamento->sql_query("", "sd97_i_codigo", "", "sd97_i_codigo=$sd97_i_codigo");
                $result = $oDaoSauFechamento->sql_record($sSql);
                if ($oDaoSauFechamento->numrows > 0) {
                  
                  db_fieldsmemory ($result, 0);
                  $sSql    = $oDaoSauFecharquivo->sql_query("", "sd99_i_codigo", "", "sd99_i_fechamento=$sd97_i_codigo");
                  $result1 = $oDaoSauFecharquivo->sql_record ($sSql);
                  if ($oDaoSauFecharquivo->numrows > 0) {

                    db_fieldsmemory ( $result1, 0 );
                    $regerar = true;

                  }
                  
                }
              }
              if ($regerar == true) {
                $regerararquivo = "Regerar Arquivo";
              } else {
                $regerararquivo = "Gerar Arquivo";
              }
            ?>
            <center>
            <input name="gerararquivo" type="submit" id="arquivo" <?=$desabilita?> 
                   value="<?=$regerararquivo?>" onclick="return js_listUnidades();"> 
            <input name="recibo" type="submit" id="recibo" value="Gerar Recibo" disabled onclick='js_recibo();'>
            </center>
          </form>
        </td>
      </tr>
    </table>
    <center>
    <table>
      <tr>
        <td>
          <?=db_criatermometro ('termometro' ,'Concluido...', 'blue', 1);?>
        </td>
      </tr>
    </table>
    </center>
<?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), 
          db_getsession("DB_instit"));
?>
</body>
</html>
<script>
<?
  if (isset($sd97_i_compmes)) {
    echo "document.getElementById('nomeExtencao').innerHTML = js_nomeMes($sd97_i_compmes,1);";
  }
  if (isset($orgao) && $orgao == 2) {
    echo "$('orgEstadual').checked = true;";  
  } else { 
    echo "$('orgMunicipal').checked = true;";
  }
?>
function js_listUnidades() {
    
    iTam = document.getElementById('sselecionados').length;
    sStr = '';
    sSep = '';
    for (iX = 0; iX < iTam; iX++) {

      sStr += sSep+document.getElementById('sselecionados').options[iX].value;
      sSep=",";

    }
    document.getElementById('sd24_i_unidade').value = sStr; 
    return true;

}
function js_pesquisasd98_i_fechamento(mostra) {

  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_sau_fechamento',
                      'func_sau_fechamento.php?funcao_js=parent.js_mostrasau_fechamento1|sd97_i_compmes|sd97_i_compano'+
                      '|sd97_i_financiamento|sd65_c_nome|sd97_d_dataini|sd97_d_datafim|sd97_i_codigo','Pesquisa',true);
  
}
function js_mostrasau_fechamento1(chave1, chave2, iFinanciamento, sFinanciamento, sd97_d_dataini,
                                                                                               sd97_d_datafim, codigo) {

  document.form1.sd97_i_codigo.value  = codigo;
  document.form1.sd97_i_compmes.value = chave1;
  document.form1.sd97_i_compano.value = chave2;
  if (iFinanciamento == '' || iFinanciamento == null || iFinanciamento == undefined) {
    iFinanciamento = 0;
  }
  document.form1.sd97_i_financiamento.value = iFinanciamento;
  if (sFinanciamento == '' || sFinanciamento == null || sFinanciamento == undefined) {
    sFinanciamento = 'Todos';
  }
  document.form1.sd97_i_financiamento.value         = iFinanciamento;
  document.form1.sd65_c_nome.value                  = sFinanciamento;
  document.form1.sd97_d_dataini.value               = sd97_d_dataini.split('-').reverse().join('/');
  aVet                                              = sd97_d_dataini.split('-');
  document.form1.sd97_d_dataini_dia.value           = aVet[2];
  document.form1.sd97_d_dataini_mes.value           = aVet[1];
  document.form1.sd97_d_dataini_ano.value           = aVet[0];
  document.form1.sd97_d_datafim.value               = sd97_d_datafim.split('-').reverse().join('/');
  aVet                                              = sd97_d_datafim.split('-');
  document.form1.sd97_d_datafim_dia.value           = aVet[2];
  document.form1.sd97_d_datafim_mes.value           = aVet[1];
  document.form1.sd97_d_datafim_ano.value           = aVet[0];
  document.getElementById('nomeExtencao').innerHTML = js_nomeMes(chave1,1);
  
  db_iframe_sau_fechamento.hide();
  document.form1.submit();
  
}
function js_nomeMes(iNumero,iTipo) {

  if (iTipo == undefined) {
    iTipo=0;
  }
  aNome = new Array();
  switch(parseInt(iNumero,10)) {
    case 1:aNome[0]='janeiro';
           aNome[1]='JAN';
           break;
    case 2:aNome[0]='fevereiro';
           aNome[1]='FEV';
           break;
    case 3:aNome[0]='março';
           aNome[1]='MAR';
           break;
    case 4:aNome[0]='abril';
           aNome[1]='ABR';
           break;
    case 5:aNome[0]='maio';
           aNome[1]='MAI';
           break;
    case 6:aNome[0]='junho';
           aNome[1]='JUN';
           break;
    case 7:aNome[0]='julho';
           aNome[1]='JUL';
           break;
    case 8:aNome[0]='agosto';
           aNome[1]='AGO';
           break;
    case 9:aNome[0]='setembro';
           aNome[1]='SET';
           break;
    case 10:aNome[0]='outubro';
            aNome[1]='OUT';
            break;
    case 11:aNome[0]='novembro';
            aNome[1]='NOV';
            break;
    case 12:aNome[0]='dezembro';
            aNome[1]='DEZ';
            break;              
  default:
    return '';
  }
  return aNome[iTipo]; 
}
function js_detectaarquivo(arquivo, pdf, sintetico) {

  alert(pdf + ' - ' + sintetico);
  listagem = arquivo+"#Download arquivo TXT (pagamento eletrônico)|";
  js_montarlista(listagem,"form1");

}
</script>
<?
if (isset($gerararquivo)) {

  /* Seleciona o codigo da ultima reliase para informar a versão so programa */
  $sqlCodRelease = " select db30_codrelease as ultimarelease from db_versao order by db30_codver desc limit 1 ";
  $rsCodRelease  = pg_query($sqlCodRelease);

  /* Seta filtros e opções da geração */
  $oDados->iFechamento    = $sd97_i_codigo;
  $oDados->iCompano       = $sd97_i_compano;
  $oDados->iCompmes       = $sd97_i_compmes;
  $oDados->dIni           = $sd97_d_dataini;
  $oDados->dFim           = $sd97_d_datafim;
  $oDados->iFinanciamento = $sd97_i_financiamento;
  $oDados->iUnidade       = $sd24_i_unidade;
  $oDados->iCidade        = $iCidade;
  $oDados->sTipo          = $sTipo;
  $oDados->sSigla         = $sSigla;
  $oDados->sDestino       = $sDestino;
  $oDados->sVersao        = pg_result($rsCodRelease, 0, "ultimarelease");
  $oDados->orgao          = $iOrgao;
  $oDados->sOrgResp       = $snomedepart;

  /*select da produção do BPA*/
  $sSql                   = $oDaoSauFecharquivo->sql_query_prd_bpa($oDados);
  $sErro                  = "Erro ao selecionar registros. <p>Comunique o adminstrador.";
  $rsProducao             = pg_query ($sSql) or die ($sErro);
  $iLinhasProducao        = pg_num_rows ($rsProducao);

  if ($iLinhasProducao == 0) {
    $iBpa = -1;
  } else {

    $oDados->iLinhas = $iLinhasProducao;

    /* select do cabeçalho */
    $sSql            = $oDaoSauFecharquivo->sql_query_cbr_bpa($oDados,$sSql);
    $sErro           = "Erro ao selecionar o Cabeçalho. <p>Comunique o adminstrador.";
    $rsCabecalho     = pg_query($sSql) or die ($sErro);

    /* Monta nome do arquivo */
    $sAbrevMes       = data_farmacia($sd97_i_compano,$sd97_i_compmes."M");
    $sAbrevMes       = strtoupper(substr($sAbrevMes['periodo'], 0, 3));
    $sArquivo        = "/tmp/PA".$sNomeArquivo.".".$sAbrevMes;

    /* Pega o numero de controle gerado*/
    $oCabecalho      = db_utils::fieldsmemory($rsCabecalho, 0);
    $cbc_smt_vrf     = $oCabecalho->cbc_smt_vrf;

    $iBpa            = geraArquivoBPA($oDados, $rsCabecalho, $rsProducao, true, $sArquivo);

  }
  /* se não ouve erro armazena arquivo no banco */
  if ($iBpa != -1) {

    db_inicio_transacao ();

    /* Incluir - sau_fecharquivo */
    $oDaoSauFecharquivo->sd99_i_codigo     = "";
    $oDaoSauFecharquivo->sd99_i_login      = $sd99_i_login;
    $oDaoSauFecharquivo->sd99_i_fechamento = $sd97_i_codigo;
    $oDaoSauFecharquivo->sd99_d_data       = $dHoje;
    $oDaoSauFecharquivo->sd99_c_hora       = date("H:i");
    $oDaoSauFecharquivo->sd99_t_arquivo    = $sArquivo;
    $oidgrava                              = db_geraArquivoOidfarmacia($sArquivo, "", 1, $conn); 
    $oDaoSauFecharquivo->sd99_objarquivo   = $oidgrava;
    $oDaoSauFecharquivo->incluir ("");
    db_fim_transacao ();

    if ($oDaoSauFecharquivo->erro_status == "0") {
      $oDaoSauFecharquivo->erro(true, false);
    } else {

      /* Função que gera o recibo */
    ?>
      <script>
        document.form1.recibo.disabled = false;
        function js_recibo() {

          oF=document.form1;
          jan = window.open('sau2_recibobpa001.php?&linhas=<?=$iLinhasProducao?>&sd97_i_compmes='+
                             oF.sd97_i_compmes.value +'&iBpa=<?=$iBpa?>'+
                            '&sNomeorg='+ $('snomedepart').value + '&sSigla='+ $('sSigla').value + 
                            '&iOrgao=' + <?=$iOrgao?> + '&sNomearq='+ $('sNomeArquivo').value +
                            '&iCnpj=' + $('cnpj').value + '&sDestino=' + $('sDestino').value + 
                            '&iCntrl=<?=$cbc_smt_vrf?>' + 
                            '&sd97_i_compano='+oF.sd97_i_compano.value,
                            '',
                            'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+
                            ',scrollbars=1,location=0 ');
          jan.moveTo(0,0);

        }
        </script>
      <?
      $oDaoSauFecharquivo->erro(true, false);

    }

  } else {

    if ($iLinhasProducao == 0) {
      db_msgbox("Nenhum Registro encontrado!");
    } else {
      db_msgbox("Erro durante a geração do arquivo!");
    }

  }

}
?>