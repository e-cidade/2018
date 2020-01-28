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
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
include_once ("libs/db_sessoes.php");
include_once ("libs/db_usuariosonline.php");
include_once ("libs/db_app.utils.php");
include_once ("libs/db_utils.php");
include_once ("dbforms/db_funcoes.php");
include_once ("libs/JSON.php");
include_once ("classes/db_lab_fechamento_classe.php");

db_postmemory ($HTTP_POST_VARS);
$clrotulo           = new rotulocampo ();
$cllab_bpamagnetico = db_utils::getdao('lab_bpamagnetico');
$cllab_laboratorio  = db_utils::getdao('lab_laboratorio');
$clunidades         = db_utils::getdao('unidades');
$oDaodbconfig       = db_utils::getdao('db_config');
$db_opcao           = 1;
$db_botao           = true;
$iLogin             = DB_getsession ( "DB_id_usuario" );
$dHoje              = date("Y-m-d", db_getsession("DB_datausu"));
$desabilita         = "";
$clrotulo->label("la02_i_codigo");
$clrotulo->label("la54_i_codigo");
$clrotulo->label("la54_i_compmes");
$clrotulo->label("la54_i_compano");

$sSql      = $oDaodbconfig->sql_query_file(db_getsession("DB_instit") ,"nomeinst as snomedepart,cgc as cnpj"); 
$resConfig = $oDaodbconfig->sql_record($sSql);
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
              <fieldset style="width: 30%"><legend><b>Gerador de Arquivo:</b></legend>
                <table border="0" align="left">
                  <tr>
                    <td><b>Tipo de BPA:</b></td>
                    <td>
                      <?
                        $arr_tipo = array ("02" => "Individual", "01" => "Consolidado");
                        db_select('sTipo', $arr_tipo, true, 4);
                      ?>
                    </td>  
                  </tr>
                  <tr>
                    <td colspan="2">
                      <fieldset>
                        <legend><b>Competencia:</b></legend>
                        <table>
                          <tr>
                            <td>
                              <?
                                db_ancora("<b>Competencia:</b>", "js_pesquisala54_i_codigo(true);", $db_opcao);
                              ?>
                            </td>
                            <td colspan="3"> 
                              <?
                                db_input('linhas', 5, @$Ilinhas, true, 'hidden', $db_opcao, "");
                                db_input('la54_i_codigo', 2, @$Ila53_i_codigo, true, 'hidden', 3, "");
                                db_input('la54_i_compmes', 2, @$Ila54_i_compmes, true, 'text', 3, 
                                          'oncange="js_trocaext(this.value)"');
                                db_input('la54_i_compano', 4, @$Ila54_i_compano, true, 'text', 3, "");
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <td nowrap title="<?=@$Tla54_d_dataini?>">
                              <b>Período de Fechamento :</b>
                            </td>
                            <td> 
                          <? 
                          db_inputdata('la54_d_ini', @$la54_d_ini_dia, @$la54_d_ini_mes, @$la54_d_ini_ano, true, 'text',
                                       3
                                      );
                          ?>
                         A
                          <?
                          db_inputdata('la54_d_fim', @$la54_d_fim_dia, @$la54_d_fim_mes, @$la54_d_fim_ano, true, 'text',
                                       3
                                      );
                          ?>
                        </td>
                        </tr>
                      <tr>
                        <td><b>Tipo de Fianciamento:</b></td>
                        <td colspan="3">
                          <?
                            db_input ('la54_i_financiamento', 6, @$sd97_i_financiamento, true, 'hidden', 3, "");
                            db_input ('sd65_c_nome', 60, @$sd65_c_nome, true, 'text', 3, "");
                          ?>
                        </td>
                     </tr>
                    </table>
                    </fieldset>
                  </td>
              </tr>
                  <tr>
                    <td colspan="2">
                      <fieldset>
                        <legend><b>Laboratorio:</b></legend>
                        <?
                          $sSql           = $cllab_laboratorio->sql_query("", "la02_i_codigo,la02_c_descr");
                          $rsLaboratorios = $cllab_laboratorio->sql_record($sSql);
                          db_multiploselect("la02_i_codigo", "la02_c_descr", "nselecionados", "sselecionados",
                                            $rsLaboratorios, array(), 5, 250
                                           );
                          db_input('listaLaboratorios', 100, "", true, 'hidden', 3, "" );
                        ?>
                      </fieldset>
                    </td>
                  </tr>  
                  <tr>
                    <td colspan="3">
                      <fieldset>
                        <legend><b>Orgão Responsavel:</b></legend>
                        <table>
                          <tr>
                            <td>
                              <b>Nome:</b>
                            </td>
                            <td>
                              <?
                                db_input ('snomedepart', 40, @$Lsnomedepart, true, 'text', 3, "");
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <td nowrap title="Sigla"><b>Sigla:<b/></td>
                            <td colspan=3>
                              <?
                                if (!isset ( $sigla )) {
                                  $sigla = "";
                                }
                                db_input ('sSigla', 6, @$siglas, true, 'text', 3, "");
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <td><b>CNPJ:</b></td>
                            <td><?
                                  db_input ('cnpj', 30, @$cnpj, true, 'text', 3, "");
                                ?>
                            </td>
                          </tr>
                        </table>
                      </fieldset>
                    </td>
                  </tr>
                  <tr>
                   <td colspan="2">
                     <fieldset>
                       <legend><b>Secretaria de Saúde de Destino dos B.P.A(s):</b></legend>
                       <table>
                         <tr>
                           <td nowrap title="Sec. de Destino "><B>Sec. de Destino:</b></td>
                           <td colspan=3>
                             <?
                               db_input ('sDestino', 40, @$destino, true, 'text', 3, "");
                             ?>
                           </td>
                         </tr>
                         <tr>
                           <td><b>Orgão:</b></td>
                           <td> 
                             <input name="orgao" id="orgMunicipal" type="radio" value="1" checked >Municipal
                             <br>
                             <input name="orgao" id="orgEstadual" type="radio" value="2">Estadual
                           </td>
                         </tr>  
                       </table>
                     </fieldset>
                   </td>
                 </tr>
                 <tr>
                   <td colspan="2">
                     <fieldset>
                       <legend><b>Arquivo de Produção:</b></legend>
                         <table>
                           <tr>
                             <td><b>Arquivo:</b></td>
                             <td>
                               PA
                               <?
                                 db_input('sNomeArquivo', 8, @$sNomeArquivo, true, 'text', $db_opcao, "", "", "", "", 
                                          8
                                         );
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
               <input name="gerararquivo" type="submit" id="arquivo" <?=$desabilita?> value="Gerar Arquivo" 
                      onclick="return js_listLaboratorios()"> 
               <input name="recibo" type="submit" id="recibo" value="Gerar Recibo" disabled onclick='js_recibo();'>
             </form>
           </center>
         </td>
       </tr>
     </table>
     <center>
       <table>
         <tr>
           <td>
             <?=db_criatermometro ('termometro', 'Concluido...', 'blue', 1);?>
           </td>
         </tr>
       </table>
     </center>
     <?
       db_menu (
                db_getsession ("DB_id_usuario"),
                db_getsession ("DB_modulo"),
                db_getsession ("DB_anousu"),
                db_getsession ("DB_instit") 
               );
     ?>
  </body>
</html>
<script>
<?
  if (isset($la54_i_compmes)) {
    echo "document.getElementById('nomeExtencao').innerHTML = js_nomeMes($la54_i_compmes,1);";
  } else { 
    echo "document.getElementById('nomeExtencao').innerHTML = js_nomeMes(".date("m",db_getsession("DB_datausu")).",1);";
  }
  if (isset($orgao) && $orgao == 2) {
    echo "$('orgEstadual').checked = true;";  
  } else { 
    echo "$('orgMunicipal').checked = true;";
  }
?>
  function js_listLaboratorios() {

    iTam = document.getElementById('sselecionados').length;
    sStr = '';
    sSep = '';
    for (iX = 0; iX < iTam; iX++) {

      sStr += sSep+document.getElementById('sselecionados').options[iX].value;
      sSep  = ",";

    }
    document.getElementById('listaLaboratorios').value = sStr;
    return true;

  }

  function js_pesquisala54_i_codigo(mostra) {

      js_OpenJanelaIframe('top.corpo','db_iframe_lab_fechamento',
                          'func_lab_fechamento.php?'
                          +'funcao_js=parent.js_mostralab_fechamento1|la54_i_compmes|la54_i_compano|la54_i_codigo|'
                          +'la54_d_ini|la54_d_fim|la54_i_financiamento|sd65_c_nome','Pesquisa',true);

  }

  function js_trocaext(valor) {
    document.getElementById('nomeExtencao').innerHTML = js_nomeMes(valor, 1);
  }

function js_mostralab_fechamento1(chave1, chave2, chave3, ini, fim, iFinanciamento ,sFinanciamento) {

  document.form1.la54_i_compmes.value       = chave1;
  js_trocaext(chave1);
  document.form1.la54_i_compano.value       = chave2;
  document.form1.la54_i_codigo.value        = chave3;
  document.form1.la54_d_ini.value           = ini.split('-').reverse().join('/');
  aVet                                      = ini.split('-');
  document.form1.la54_d_ini_dia.value       = aVet[2];
  document.form1.la54_d_ini_mes.value       = aVet[1];
  document.form1.la54_d_ini_ano.value       = aVet[0];
  document.form1.la54_d_fim.value           = fim.split('-').reverse().join('/');
  aVet                                      = fim.split('-');
  document.form1.la54_d_fim_dia.value       = aVet[2];
  document.form1.la54_d_fim_mes.value       = aVet[1];
  document.form1.la54_d_fim_ano.value       = aVet[0];
  document.form1.la54_i_financiamento.value = iFinanciamento;
  document.form1.sd65_c_nome.value          = sFinanciamento;
  db_iframe_lab_fechamento.hide();

}
function js_pesquisa() {

  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_sau_fechapront',
                      'func_sau_fechapront.php?funcao_js=parent.js_preenchepesquisa|sd98_i_codigo',
                      'Pesquisa',true);

}

function js_nomeMes(iNumero, iTipo) {

  if (iTipo == undefined) {
    iTipo=0;
  }
  aNome = new Array();
  switch (parseInt(iNumero,10)) {

    case 1 : aNome[0] = 'janeiro';
             aNome[1] = 'JAN';
             break;

    case 2 : aNome[0] = 'fevereiro';
             aNome[1] = 'FEV';
             break;

    case 3 : aNome[0] = 'março';
             aNome[1] = 'MAR';
             break;

    case 4 : aNome[0] = 'abril';
             aNome[1] = 'ABR';
             break;

    case 5 : aNome[0] = 'maio';
             aNome[1] = 'MAI';
             break;

    case 6 : aNome[0] = 'junho';
             aNome[1] = 'JUN';
             break;

    case 7 : aNome[0] = 'julho';
             aNome[1] = 'JUL';
             break;
             
    case 8 : aNome[0] = 'agosto';
             aNome[1] = 'AGO';
             break;
             
    case 9 : aNome[0] = 'setembro';
             aNome[1] = 'SET';
             break;

    case 10 : aNome[0] = 'outubro';
              aNome[1] = 'OUT';
              break;

    case 11 : aNome[0] = 'novembro';
              aNome[1] = 'NOV';
              break;

    case 12 : aNome[0] = 'dezembro';
              aNome[1] = 'DEZ';
              break;
  default:
    return '';
  }
  return aNome[iTipo]; 
}
</script>
<?
  if (isset($gerararquivo)) {

    /* PEGA A ULTIMA RELEASE */
    $sqlCodRelease         = "select db30_codrelease as ultimarelease from db_versao order by db30_codver desc limit 1";
    $rsCodRelease          = pg_query($sqlCodRelease);
    $oDados->iCompano      = $la54_i_compano;
    $oDados->iCompmes      = $la54_i_compmes;
    $oDados->dIni          = $la54_d_ini;
    $oDados->dFim          = $la54_d_fim;
    $oDados->iUnidade      = $listaLaboratorios;
    $oDados->iCidade       = $iCidade;
    $oDados->financiamento = $la54_i_financiamento;
    $oDados->sTipo         = $sTipo;
    $oDados->sSigla        = $sSigla;
    $oDados->sDestino      = $sDestino;
    $oDados->sVersao       = pg_result($rsCodRelease, 0, "ultimarelease");
    $oDados->iFechamento   = $la54_i_codigo;
    $oDados->orgao         = $orgao;
    $oDados->sOrgResp      = $snomedepart;
    $sSql                  = $cllab_bpamagnetico->sql_querry_prd_bpa($oDados);
    $sErro                 = "Erro ao selecionar registros. <p>Comunique o adminstrador. <br> $sSql;";
    $rsProducao            = pg_query($sSql) or die($sErro);
    $iLinhasProducao       = pg_num_rows($rsProducao);
    
    if ($iLinhasProducao == 0) {
      $iBpa = -1;
    } else {

      $oDados->iLinhas      = $iLinhasProducao;
      $sSql                 = $cllab_bpamagnetico->sql_querry_cbr_bpa($oDados, $sSql);
      $sErro                = "Erro ao selecionar o Cabeçalho. <p>Comunique o adminstrador.";
      $rsCabecalho          = pg_query($sSql) or die ($sErro);
      $sAbrevMes            = data_farmacia($la54_i_compano, $la54_i_compmes."M");
      $sAbrevMes            = strtoupper(substr($sAbrevMes['periodo'], 0, 3));
      $pont                 = "/tmp/PA".$sNomeArquivo.".".$sAbrevMes;

      /* Parte Generica */
      $oCabecalho  = db_utils::fieldsmemory($rsCabecalho, 0);
      $cbc_smt_vrf = $oCabecalho->cbc_smt_vrf;  
      $iBpa        = geraArquivoBPA($oDados, $rsCabecalho, $rsProducao, true, $pont);

    }
    if ($iBpa != -1) {

      db_inicio_transacao ();
      $cllab_bpamagnetico->la55_i_codigo     = "";
      $cllab_bpamagnetico->la55_i_usuario    = $iLogin;
      $cllab_bpamagnetico->la55_i_fechamento = $la54_i_codigo;
      $cllab_bpamagnetico->la55_d_data       = $dHoje;
      $cllab_bpamagnetico->la55_c_hora       = date("H:i");
      $cllab_bpamagnetico->la55_t_arquivo    = $pont;
      $oidgrava                              = db_geraArquivoOidfarmacia($pont, "", 1, $conn); 
      $cllab_bpamagnetico->la55_o_arquivo    = $oidgrava;
      $cllab_bpamagnetico->incluir ("");
      db_fim_transacao ();

      if ($cllab_bpamagnetico->erro_status == "0") {
        $cllab_bpamagnetico->erro(true, false);
      } else {

        ?>
          <script>
            document.form1.recibo.disabled= false;
            function js_recibo() {
                
              oF=document.form1;
              jan = window.open('sau2_recibobpa001.php?iLab=1&linhas=<?=$iLinhasProducao?>&sd97_i_compmes='+
                                oF.la54_i_compmes.value +'&iBpa=<?=$iBpa?>'+
                                '&sNomeorg='+ $('snomedepart').value + '&sSigla='+ $('sSigla').value + 
                                '&iOrgao=' + <?=$orgao?> + '&sNomearq='+ $('sNomeArquivo').value +
                                '&iCnpj=' + $('cnpj').value + '&sDestino=' + $('sDestino').value + 
                                '&iCntrl=<?=$cbc_smt_vrf?>' + 
                                '&sd97_i_compano='+oF.la54_i_compano.value,
                                '',
                                'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+
                                ',scrollbars=1,location=0 ');
                                jan.moveTo(0,0);

          }
          </script>
        <?
        $cllab_bpamagnetico->erro(true, false);

      }

    } else {
      if ($iLinhasProducao == 0) {
        db_msgbox("Nenhum Registro encontrado!");
      } else {
        db_msgbox("Erro durante a geração do arquivo!");
      }
    }

  }

/**
 * Função para descobrir o laboratorio que o usuario esta logado
 * @return inteiro Codigo do laboratorio logado 
 */
function laboratorioLogado() {
  
  require_once('libs/db_utils.php');
  $iUsuario        = db_getsession('DB_id_usuario');
  $iDepto          = db_getsession('DB_coddepto');
  $oLab_labusuario = db_utils::getdao('lab_labusuario');
  $oLab_labdepart  = db_utils::getdao('lab_labdepart');
  $sCampos         = 'la02_i_codigo, la02_c_descr';
  $sSql            = $oLab_labusuario->sql_query(null, $sCampos, "la02_i_codigo", " la05_i_usuario = $iUsuario ");
  $rResult         = $oLab_labusuario->sql_record($sSql);
  if ($oLab_labusuario->numrows == 0) {

    $sCampos = 'la02_i_codigo, la02_c_descr';
    $sSql    = $oLab_labdepart->sql_query(null, $sCampos, "la02_i_codigo", " la03_i_departamento = $iDepto ");
    $rResult = $oLab_labdepart->sql_record($sSql);
    if ($oLab_labdepart->numrows == 0) {
      return 0;
    }
  }
  $oLab = db_utils::getColectionByRecord($rResult);
  return $oLab[0]->la02_i_codigo;
  
}

?>