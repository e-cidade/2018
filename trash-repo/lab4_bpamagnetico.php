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

require ("libs/db_stdlib.php");
require ("libs/db_stdlibwebseller.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_app.utils.php");
include_once ("libs/db_utils.php");
include ("dbforms/db_funcoes.php");
include("libs/JSON.php");
include ("classes/db_lab_laboratorio_classe.php");
include ("classes/db_lab_fechamento_classe.php");
include ("classes/db_lab_bpamagnetico_classe.php");
include ("classes/db_unidades_classe.php");
//include ("model/saudeBPA.model.php");

db_postmemory ( $HTTP_POST_VARS );
$cllab_bpamagnetico = new cl_lab_bpamagnetico();
$cllab_laboratorio  = new cl_lab_laboratorio();
$clrotulo           = new rotulocampo ();
$clunidades         = new cl_unidades ();
$db_opcao           = 1;
$db_botao           = true;
$iLogin             = DB_getsession ( "DB_id_usuario" );
$dHoje              = date("Y-m-d", db_getsession("DB_datausu"));
$desabilita         = "";
$sSql               = $oDaodbconfig->sql_query_file(db_getsession("DB_instit"),"nomeinst as snomedepart,cgc as cnpj"); 
$resConfig          = $oDaodbconfig->sql_record($sSql);
db_fieldsmemory ( $resConfig, 0 );
$clrotulo->label("la02_i_codigo");
$clrotulo->label("la54_i_codigo");
$clrotulo->label("la54_i_compmes");
$clrotulo->label("la54_i_compano");

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <?php
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
      <fieldset style="width: 30%"><legend><b>Gerador de Arquivo SIA</b></legend>
      <table border="0" align="left">
        <tr>
          <td><b>Tipo de BPA:</b></td>
          <td>
            <?
            if (! isset ( $tipo )) {
              $tipo = "02";
            }
            $arr_tipo = array ("02" => "Individual", "01" => "Consolidado" );
            db_select ( 'tipo', $arr_tipo, true, 4 );
            ?>
          </td>  
        </tr>
        <tr>
          <td colspan="2">
            <fieldset><legend>Competencia</legend>
            <table>
              <tr>
                <td>
                  <?db_ancora ( "<b>Competencia</b>", "js_pesquisasd98_i_fechamento(true);", $db_opcao );?>
                </td>
                <td colspan="3"> 
                  <?db_input ( 'linhas', 5, @$Ilinhas, true, 'hidden', $db_opcao, "" );
                    db_input ( 'la54_i_compmes', 2, @$Isd97_i_compmes, true, 'text', 3, "" );
                    db_input ( 'la54_i_compano', 4, @$Isd97_i_compano, true, 'text', 3, "");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Tla54_d_dataini?>">
                  <b>Período de Fechamento :</b>
                </td>
                <td> 
                <? db_inputdata('la54_d_dataini',
                                @$la54_d_dataini_dia,
                                @$la54_d_dataini_mes,
                                @$la54_d_dataini_ano,
                                true,
                                'text',
                                3);?>
                </td>
                <td>
                A
                </td>
                <td> 
                  <? db_inputdata('la54_d_datafim',
                                  @$la54_d_datafim_dia,
                                  @$la54_d_datafim_mes,
                                  @$la54_d_datafim_ano,
                                  true,
                                  'text',
                                  3);?>
                </td>
              </tr>
              <tr>
                <td><b>Tipo de fianciamento</b></td>
                <td colspan="3">
                  <?
                    db_input ( 'la54_i_financiamento', 6, @$sd97_i_financiamento, true, 'hidden', 3, "" );
                    db_input ( 'sd65_c_nome', 30, @$sd65_c_nome, true, 'text', 3, "" );
                  ?>
                </td>
              </tr>
            </table>
            </fieldset>
          </td>
        </tr>  
        <tr>
          <td>
            <fieldset><legend>Laboratorio</legend>
              <?
              $sSql           = $cllab_laboratorio->sql_query("","la02_i_codigo,la02_c_descr");
              $rsLaboratorios = $cllab_laboratorio->sql_record($sSql);
              db_multiploselect("la02_i_codigo",
                                "la02_c_descr",
                                "nselecionados",
                                "sselecionados",
                                $rsLaboratorios,
                                array(),
                                5,
                                250);
              ?>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="3">
            <fieldset><legend>Orgão Responsavel</legend>
            <table>
              <tr>
                <td><b>Nome:</b></td>
                <td><?db_input ( 'snomedepart', 30, @$Lsnomedepart, true, 'text', 3, "" ); ?></td>
              </tr>
              <tr>
                <td nowrap title="Sigla"><b>Sigla<b/></td>
                <td colspan=3>
                  <?
                    if (! isset ( $sigla )) {
                      $sigla = "";
                    }
                    db_input ( 'sigla', 6, @$siglas, true, 'text', $db_opcao, "" );
                  ?>
                </td>
              </tr>
              <tr>
                <td><b>CNPJ:</b></td>
                <td><? db_input ( 'cnpj', 30, @$cnpj, true, 'text', 3, "" ); ?></td>
              </tr>
            </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="2">
          <fieldset><legend>Secretaria de Saúde de destino dos B.P.A(s)</legend>
          <table>
            <tr>
              <td nowrap title="Sec. de Destino "><B>Sec. de Destino<B /></td>
              <td colspan=3>
               <?
                 if (! isset ( $destino )) {
                  $destino = "";
                 }
                 db_input ( 'destino', 40, @$destino, true, 'text', $db_opcao, "" );
               ?>
              </td>
            </tr>
            <tr>
              <td><b>Orgão:</b></td>
              <td>
                <input name="orgao" type="radio" value="1" checked >Municipal
                <br>
                <input name="orgao" type="radio" value="2">Estadual
              </td>
            </tr>  
          </table>
          </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset><legend>Arquivo de Produção</legend>
              <table>
                <tr>
                  <td><b>Arquivo:</b></td>
                  <td>
                    PA
                    <?db_input('sNomeArquivo', 20, @$sNomeArquivo, true, 'text', $db_opcao, "" );?>
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
        if (isset ( $sd97_i_compmes )) {
          $result = $clsau_fechamento->sql_record ( $clsau_fechamento->sql_query ( "", "sd97_i_codigo", "", "sd97_i_compmes=$sd97_i_compmes and sd97_i_compano=$sd97_i_compano" ) );
          if ($clsau_fechamento->numrows > 0) {
            db_fieldsmemory ( $result, 0 );
            $result1 = $clsau_fecharquivo->sql_record ( $clsau_fecharquivo->sql_query ( "", "sd99_i_codigo", "", "sd99_i_fechamento=$sd97_i_codigo" ) );
            if ($clsau_fecharquivo->numrows > 0) {
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
      <input name="gerararquivo" type="submit" id="arquivo" <?=$desabilita?> value="<?=$regerararquivo?>"> 
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
      <?=db_criatermometro ( 'termometro', 'Concluido...', 'blue', 1 );?>
    </td>
  </tr>
</table>
<?
db_menu ( db_getsession ( "DB_id_usuario" ), 
          db_getsession ( "DB_modulo" ),
          db_getsession ( "DB_anousu" ),
          db_getsession ( "DB_instit" ) );
?>
  </body>
</html>
<script>
  <?
	if(isset($sd97_i_compmes)){
	  echo "document.getElementById('nomeExtencao').innerHTML = js_nomeMes($sd97_i_compmes,1);";
	}
	?>
  function js_pesquisala54_i_codigo(mostra){
    if (mostra == true) {
      js_OpenJanelaIframe('top.corpo','db_iframe_lab_fechamento',
                          'func_lab_fechamento.php?'
                          +'funcao_js=parent.js_mostralab_fechamento1|'
                          +'la54_i_compmes|la54_i_compano|la54_i_codigo|'
                          +'la54_d_ini|la54_d_fim','Pesquisa',true);
    } else {
      if (document.form1.sd98_i_fechamento.value != '') { 
        js_OpenJanelaIframe('top.corpo','db_iframe_lab_fechamento',
                            'func_lab_fechamento.php?pesquisa_chave='
                            +document.form1.la54_i_codigo.value+'&funcao_js='
                            +'parent.js_mostralab_fechamento','Pesquisa',false);
      } else {
        document.form1.sd97_i_compmes.value = ''; 
      }
    }
  }
  function js_mostralab_fechamento(chave,erro){

    document.form1.sd97_i_compmes.value = chave; 
    if (erro == true) { 
      document.form1.sd98_i_fechamento.focus(); 
      document.form1.sd98_i_fechamento.value = ''; 
    }

  }
  function js_mostralab_fechamento1(chave1,chave2,chave3,ini,fim){

    document.form1.la54_i_compmes.value = chave1;
    document.form1.la54_i_compano.value = chave2;
    document.form1.la54_i_codigo.value = chave3;
    document.form1.la54_d_ini.value = ini;
    document.form1.la54_d_fim.value = fim;
    db_iframe_lab_fechamento.hide();

  }
  function js_pesquisa(){
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_sau_fechapront',
                        'func_sau_fechapront.php?funcao_js=parent.js_preenchepesquisa|sd98_i_codigo',
                        'Pesquisa',true);
  }
</script>
<?
  if (isset ( $gerararquivo )) {

    $oDados->iCompano     = $la54_i_compano;
    $oDados->icompmes     = $la54_i_compmes;
    $oDados->dIni         = $la54_d_ini;
    $oDados->dFim         = $la54_d_fim;
    $oDados->iUnidade     = $la02_i_codigo;
    $oDados->iCidade      = $iCidade;
    $oDados->iPab         = $iPab;
    $oDados->sTipo        = $sTipo;
    $oDados->sSigla       = $sSigla;
    $oDados->sDestino     = $sDestino;
    $oDados->sVersao      = $sVersao;
    $oDados->iFechamento  = $la54_i_codigo;

    $sSql = $cllab_bpamagnetico->sql_querry_prd_bpa($oDados);
    die("<br><br>".$sSql);
    
    $rsProducao      = pg_query ( $sSql ) or die ( "Erro ao selecionar registros. <p>Comunique o adminstrador. <br> " );
    $iLinhasProducao = pg_num_rows ( $rsProducao );

    $oDados->iLinhas      = $iLinhasProducao;

    $sSql=$cllab_bpamagnetico->sql_querry_cbr_bpa($oDados);
    $rsCabecalho = pg_query ( $sSql ) or die ( "Erro ao selecionar o Cabeçalho. <p>Comunique o adminstrador." );

    /* Parte Generica */
    $lBpa = geraArquivoBPA($oDados,$rsCabecalho,$rsProducao,true,"tmp/filebpa.txt");
    
    if ($lBpa == true) {

      db_inicio_transacao ();
      $cllab_bpamagnetico->la55_i_codigo     = "";
      $cllab_bpamagnetico->la55_i_usuario    = $iLogin;
      $cllab_bpamagnetico->la55_i_fechamento = $la54_i_codigo;
      $cllab_bpamagnetico->la55_d_data       = $dHoje;
      $cllab_bpamagnetico->la55_c_hora       = date("H:i");
      $cllab_bpamagnetico->la55_t_arquivo    = 'tmp/filebpa.txt';
      $oidgrava                              = db_geraArquivoOidfarmacia('tmp/filebpa.txt',"",1,$conn); 
      $cllab_bpamagnetico->la55_o_arquivo    = $oidgrava;
      $cllab_bpamagnetico->incluir ( "" );
      db_fim_transacao ();

      if($cllab_bpamagnetico->erro_status=="0"){
        $cllab_bpamagnetico->erro(true,false);
      }else{

        ?><script>
          document.form1.recibo.disabled= false;
          function js_recibo(){

            jan = window.open('sau2_recibobpa001.php?iLab=1&linhas=<?=$iLinhasProducao?>&sd97_i_compmes='
                               +document.form1.la54_i_compmes.value+
                               '&sd97_i_compano='+document.form1.la54_i_compano.value,
                               '','width='+(screen.availWidth-5)+',
                               height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
            jan.moveTo(0,0);

          }
          </script>
        <?
        $cllab_bpamagnetico->erro(true,false);

      }

    }

  }
?>