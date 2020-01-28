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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_libpessoal.php");

require_once("classes/db_certid_classe.php");
require_once("classes/db_certdiv_classe.php");
require_once("classes/db_certter_classe.php");
require_once("classes/db_arreforo_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_inicialcert_classe.php");
require_once("classes/db_acertid_classe.php");
require_once("classes/db_acertdiv_classe.php");
require_once("classes/db_acertter_classe.php");
require_once("classes/db_listacda_classe.php");
require_once("libs/db_app.utils.php");
require_once("libs/JSON.php");

db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js');
db_app::load('estilos.css, grid.style.css');

db_postmemory($_GET);
$cliframe_seleciona = new cl_iframe_seleciona;

$clcertid      = new cl_certid;
$clcertdiv     = new cl_certdiv;
$clcertter     = new cl_certter;
$clarrecad     = new cl_arrecad;
$clarreforo    = new cl_arreforo;
$clinicialcert = new cl_inicialcert;
$clacertid     = new cl_acertid;
$clacertdiv    = new cl_acertdiv;
$clacertter    = new cl_acertter;
$clrotulo      = new rotulocampo;
$clListacda    = new cl_listacda();

$oJson         = new services_json();

$clrotulo->label("v13_certid");

$sqlerro    = false;
$abil       = false;

/**
 * Parâmetros do get
 */
$v13_certidini = $certidaoinicial;
$v13_certidfim = $certidaofinal;
$sObservacao   = $observacao;


$processar     = true;

$aMensagemErros = array();

if (isset($processar)&&$processar!=""){

  db_inicio_transacao();

  $sqlerro  = false;
  $mensagem = "";


  if ($v13_certidini == "") {
    $v13_certidini = $v13_certidfim;
  }

  db_criatermometro("termometro", "Concluido...", "blue", 1);
  flush();

  $iContador = 0;

  for ($certidao = $v13_certidini; $certidao <= $v13_certidfim; $certidao++) {


    db_atutermometro($iContador , ($v13_certidfim - $v13_certidini) + 1, "termometro", 1, "Processando certidão $certidao.");

    $iContador++;

    $oCertidao           = new Certidao( $certidao );
    $iCertidaoSequencial = $oCertidao->getSequencial();

    if ( !empty($iCertidaoSequencial) ) {

      if ( $oCertidao->isCobrancaExtrajudicial() ) {

        $oErro             = new stdClass();
        $oErro->sTipo      = "ERRO";
        $oErro->sDescricao = utf8_encode("CDA $certidao está sob Cobrança Extrajudicial.");

        $aMensagemErros[]  = $oErro;

        continue;
      }
    }


    $result_inicial=$clinicialcert->sql_record($clinicialcert->sql_query(null,null, "v51_inicial",null," v50_situacao = 1 and v51_certidao = $certidao "));

    if ($clinicialcert->numrows>0){
      db_fieldsmemory($result_inicial,0);
      $mensagem         .= "ERRO: CDA $certidao faz parte da inicial: $v51_inicial! ";

      $oErro             =  new stdClass();

      $oErro->sTipo      = "ERRO";
      $oErro->sDescricao = "CDA $certidao faz parte da inicial: $v51_inicial.";

      $aMensagemErros[]  = $oErro;

      continue;
    }

    $result_forotip=$clarreforo->sql_record($clarreforo->sql_query_file(null,"distinct k00_numpre,k00_numpar,k00_tipo",null,"k00_certidao=$certidao"));
    if ($clarreforo->numrows > 0){
      db_fieldsmemory($result_forotip,0);
    } else {

      $mensagem                   .= "INCONSIST&Ecirc;NCIA: CDA $certidao inconsistente! ";

      $oInconsistencia             = new stdClass();

      $oInconsistencia->sTipo      = "INCONSIST&Ecirc;NCIA";
      $oInconsistencia->sDescricao = "CDA $certidao inconsistente.";

      $aMensagemErros[]            = $oInconsistencia;

      continue;
    }

    $tipo="";
    $result_certdiv=$clcertdiv->sql_record($clcertdiv->sql_query_deb($certidao,null,"distinct certdiv.*, divida.*, certid.*, cgm.*, proced.* ",null,"certid.v13_certid = $certidao and divida.v01_instit = ".db_getsession('DB_instit')." and certid.v13_instit = ".db_getsession('DB_instit')  ));
    if ($clcertdiv->numrows > 0) {
      $tipo="divida";
      $quantcertdiv=$clcertdiv->numrows;
    }

    $result_certter=$clcertter->sql_record($clcertter->sql_query_deb($certidao,null,"distinct *",null,"certid.v13_certid = $certidao and certid.v13_instit = ".db_getsession('DB_instit') ." and termo.v07_instit = ".db_getsession('DB_instit') ));
    if ($clcertter->numrows > 0) {
      $tipo="parc";
      $quantcertter=$clcertter->numrows;
    }

    if ($tipo == "") continue;

    $clacertid->v15_certid     = $certidao;
    $clacertid->v15_data       = date('Y-m-d',db_getsession("DB_datausu"));
    $clacertid->v15_hora       = db_hora();
    $clacertid->v15_usuario    = db_getsession("DB_id_usuario");
    $clacertid->v15_instit     = db_getsession('DB_instit') ;
    $clacertid->v15_observacao = $sObservacao;
    $parcial=0;

    $clacertid->v15_parcial="$parcial";
    $clacertid->incluir(null);
    $v15_codigo=$clacertid->v15_codigo;
    if ($clacertid->erro_status==0){
      $sqlerro=true;
      $erro_msg=$clacertid->erro_msg;
    }

    if($tipo=="divida"){

      for($w=0;$w<$quantcertdiv;$w++){
        db_fieldsmemory($result_certdiv,$w);

        if ($sqlerro==false){
          $clacertdiv->v14_certid=$v14_certid;
          $clacertdiv->v14_coddiv=$v14_coddiv;
          $clacertdiv->v14_vlrcor=$v14_vlrcor;
          $clacertdiv->v14_vlrhis=$v14_vlrhis;
          $clacertdiv->v14_vlrjur=$v14_vlrjur;
          $clacertdiv->v14_vlrmul=$v14_vlrmul;
          $clacertdiv->v14_codacertid=$v15_codigo;
          $clacertdiv->incluir($v14_certid,$v14_coddiv);
          if ($clacertdiv->erro_status==0){
            die($clacertdiv->erro_msg);
            $sqlerro=true;
            $erro_msg=$clacertdiv->erro_msg;
          }
        }

        if ($sqlerro==false){
          $clcertdiv->v14_certid=$certidao;
          $clcertdiv->v14_coddiv=$v01_coddiv;
          $clcertdiv->excluir($certidao,$v01_coddiv);
          if ($clcertdiv->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clcertdiv->erro_msg;
          }
        }
      }

      if ($sqlerro==false){
        $clarrecad->k00_tipo=$k00_tipo;
        for ($arreforo=0; $arreforo < pg_numrows($result_forotip); $arreforo++) {
          db_fieldsmemory($result_forotip,$arreforo);
          $clarrecad->alterar_arrecad("k00_numpre=$k00_numpre and k00_numpar=$k00_numpar");
          if ($clarrecad->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clarrecad->erro_msg;
            break;
          }
        }
      }

      $clarreforo->excluir(null,"k00_certidao=$certidao");
      if ($clarreforo->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clarreforo->erro_msg;
        break;
      }

      $clListacda->excluir(null, "v81_certid = $certidao");
      if ($clListacda->erro_status == "0") {
        $sqlerro  = true;
        $erro_msg = $clListacda->erro_msg;
      }

      $clcertid->excluir($certidao);
      if ($clcertid->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clcertid->erro_msg;
      }

    }elseif($tipo=="parc"){

      for($w=0;$w<$quantcertter;$w++){
        db_fieldsmemory($result_certter,$w);
        $clacertter->excluir($v14_certid,$v14_parcel);
        $clacertter->v14_certid=$v14_certid;
        $clacertter->v14_parcel=$v14_parcel;
        $clacertter->v14_vlrcor=$v14_vlrcor;
        $clacertter->v14_vlrhis=$v14_vlrhis;
        $clacertter->v14_vlrjur=$v14_vlrjur;
        $clacertter->v14_vlrmul=$v14_vlrmul;
        $clacertter->v14_codacertid=$v15_codigo;
        $clacertter->incluir($v14_certid,$v14_parcel);
        if ($clacertter->erro_status==0){
          $sqlerro=true;
          $erro_msg=$clacertter->erro_msg;
        }

        if ($sqlerro==false){
          $clcertter->v14_certid=$certidao;
          $clcertter->v14_parcel=$v07_parcel;
          $clcertter->excluir($certidao,$v07_parcel);
          if ($clcertter->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clcertter->erro_msg;
          }
        }
      }

      $result_forotip=$clarreforo->sql_record($clarreforo->sql_query_file(null,"distinct k00_numpre, k00_tipo",null,"k00_certidao=$certidao"));
      if ($clarreforo->numrows>0){
        db_fieldsmemory($result_forotip,0);
      }

      if ($sqlerro==false){
        $clarrecad->k00_tipo=$k00_tipo;

        for ($arreforo=0; $arreforo < pg_numrows($result_forotip); $arreforo++) {
          db_fieldsmemory($result_forotip,$arreforo);
          $clarrecad->alterar_arrecad("k00_numpre=$k00_numpre");
          if ($clarrecad->erro_status==0){
            $sqlerro=true;
            $erro_msg=$clarrecad->erro_msg;
            break;
          }
        }


      }

      $clarreforo->excluir(null,"k00_certidao=$certidao");
      if ($clarreforo->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clarreforo->erro_msg;
        break;
      }

      $clListacda->excluir(null, "v81_certid = $certidao");
      if ($clListacda->erro_status == "0") {
        $sqlerro  = true;
        $erro_msg = $clListacda->erro_msg;
      }

      $clcertid->excluir($certidao);
      if ($clcertid->erro_status==0){
        $sqlerro=true;
        $erro_msg=$clcertid->erro_msg;
      }
    }
  }

  if ($mensagem != "") {
    //db_msgbox("Erro: $mensagem\n");
    $erro_msg = $mensagem;
  }
  db_fim_transacao($sqlerro);

}

if (isset($processar)&&$processar!=""){
  if($sqlerro==true){
    //db_msgbox($erro_msg);
  }else{
    if(!empty($erro_msg)){
     //db_msgbox($erro_msg);
    } else {
      //db_msgbox('Cancelamento efetuado com Sucesso!!');
    }
//     /echo "<script>location.href='div4_cancelcdageral001.php';</script>";
  }
}
?>
<form name="form1" id="form1">
<div id="gridErros" style="margin: 20px auto; width: 720px">


</div>

<div style="text-align:center;">

  <input type="button" value="Fechar" onclick="parent.js_fecharJanela()" />

</div>

</form>

<?php
if (count($aMensagemErros) > 0) {

?>
<script>

gridDebitos                      = new DBGrid("dataGridDebitos");
gridDebitos.nameInstance         = "gridDebitos";
gridDebitos.setHeight            ( 200 );
gridDebitos.setCellAlign         ( new Array("left", "left") );
gridDebitos.setCellWidth         ( new Array("30%", "70%") );
gridDebitos.setHeader            ( new Array('Tipo', 'Descrição') );
gridDebitos.show                 ( $('gridErros') );
gridDebitos.clearAll             ( true );

var aRetorno  = <?php echo $oJson->encode($aMensagemErros); ?>;

aRetorno.each(function(oErro) {
  gridDebitos.addRow(new Array('&nbsp;&nbsp;&nbsp;' + oErro.sTipo,
                               '&nbsp;&nbsp;&nbsp;' + oErro.sDescricao));
});

gridDebitos.renderRows();
</script>
<?php
} else {
  db_msgbox("CDAs {$certidaoinicial} até {$certidaofinal} anuladas com sucesso.");
  echo "<script>";
  echo "parent.js_fecharJanela()";
  echo "</script>";
}
?>