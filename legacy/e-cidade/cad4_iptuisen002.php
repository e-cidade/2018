<?php
/**
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_SERVER);

$alterando   = isset($alterando);
$db_botao    = 1;
$db_opcao    = 1;
$pesq        = true;
$clisenproc  = new cl_isenproc;
$clisenexe   = new cl_isenexe;
$clisentaxa  = new cl_isentaxa;
$cliptutaxa  = new cl_iptutaxa;
$cliptubase  = new cl_iptubase;
$cliptuisen  = new cl_iptuisen;
$rotulocampo = new rotulocampo;

$cliptutaxa->rotulo->label();
$cliptubase->rotulo->label();
$cliptuisen->rotulo->label();

$rotulocampo->label("z01_nome");
$rotulocampo->label("j45_descr");
$rotulocampo->label("j34_area");
$rotulocampo->label("p58_codproc");
$rotulocampo->label("p58_requer");

$j45_tipis = 0;
$result    = $cliptubase->sql_record($cliptubase->sql_query("","cgm.z01_nome as z01_nomematri","","j01_matric=$j46_matric"));

/**
 * Buscamos o tipo de isençao na alteraçao para que sejam realizadas as validaçoes quando a isençao for imune ou nao incidente
 */

if ( $cliptubase->numrows == 0 ) {
  db_redireciona("cad4_iptuisen001.php?invalido=true");
}else{
  @db_fieldsmemory($result,0);
}

if ($alterando == 1 && !empty($j46_codigo) ) {

  if( $j46_codigo != "nova" ){

    $resultTipis = $cliptubase->sql_record($cliptuisen->sql_query("", "j45_tipis", "", "j46_codigo = $j46_codigo"));
    $j45_tipis   = db_utils::fieldsMemory($resultTipis,0)->j45_tipis;
  }
}

$data = date("Y-m-d",db_getsession("DB_datausu"));
$dat  = split("-",$data);

if(isset($incluir) || isset($alterar)){

  $cliptuisen->j46_dtinc     = $data;
  $cliptuisen->j46_dtinc_dia = $dat[2];
  $cliptuisen->j55_dtinc_mes = $dat[1];
}

if (isset($j46_codigo) && $j46_codigo=="nova") {

  $result = $cliptubase->sql_record($cliptubase->sql_query($j46_matric,"z01_nome",""));
  @db_fieldsmemory($result,0);
  $j46_codigo = "";
} else if(isset($incluir)) {

  $j46_dtinc_ano = $cliptuisen->j55_dtinc_ano=$dat[2];

  if (empty($j46_dtfim)) {

    $j46_dtfim_dia = $j46_dtini_dia;
    $j46_dtfim_mes = $j46_dtini_mes;
    $j46_dtfim_ano = $j46_dtini_ano + 50;

    $cliptuisen->j46_dtfim_dia = $j46_dtfim_dia;
    $cliptuisen->j46_dtfim_mes = $j46_dtfim_mes;
    $cliptuisen->j46_dtfim_ano = $j46_dtfim_ano;
  }

  db_inicio_transacao();
  $trans_erro = false;
  $cliptuisen->incluir($j46_codigo);
  $erro_msg=$cliptuisen->erro_msg;

  if($cliptuisen->erro_status=="0"){
    $trans_erro = true;
  }else{

    if (isset($p58_codproc)&&$p58_codproc!=""){

      $clisenproc->j61_codigo  = $cliptuisen->j46_codigo;
      $clisenproc->j61_codproc = $p58_codproc;
      $clisenproc->incluir();

      if($clisenproc->erro_status=="0"){

       $trans_erro = true;
       $erro_msg   = $clisenproc->erro_msg;
      }
    }

    if($trans_erro==false){

      for($ano = $j46_dtini_ano; $ano <= $j46_dtfim_ano; $ano++){

        $j47_codigo            = $cliptuisen->j46_codigo;
        $clisenexe->j47_codigo = $j47_codigo;
        $clisenexe->anousu     = $ano;
        $clisenexe->incluir($j47_codigo,$ano);

        if($clisenexe->erro_status=="0"){

          $trans_erro = true;
          $erro_msg   = $clisenproc->erro_msg;
          break;
        }
      }
    }
    if($trans_erro==false){

      $taxa = split("X",$dadostaxa);

      for($r=0; $r<sizeof($taxa); $r++){

       if($taxa[$r]!=""){
    	    $dad = split("yy",$taxa[$r]);
          $clisentaxa->j56_codigo = $cliptuisen->j46_codigo;
          $clisentaxa->j56_receit = $dad[1];
          $clisentaxa->j56_perc   = $dad[0];
          $clisentaxa->incluir($cliptuisen->j46_codigo,$dad[1]);
          if($clisentaxa->erro_status=="0"){

            $trans_erro = true;
            $erro_msg   = $clisentaxa->erro_msg;
            break;
          }
        }
      }
    }
  }

  db_fim_transacao($trans_erro);
}else if(isset($excluir)){

  db_inicio_transacao();
  $clisenexe->j47_codigo  = $j46_codigo;
  $clisenexe->excluir($j46_codigo);

  $clisentaxa->j56_codigo = $j46_codigo;
  $clisentaxa->excluir($j46_codigo);

  $result_proc = $clisenproc->sql_record($clisenproc->sql_query(null,"p58_codproc,p58_requer",null,"j61_codigo=$j46_codigo"));

  if ($clisenproc->numrows!=0){
    $clisenproc->excluir(null,"j61_codigo=$j46_codigo and j61_codproc=$p58_codproc");
  }

  $cliptuisen->excluir($j46_codigo);
  db_fim_transacao();
}else if(isset($alterar)){

  if (empty($j46_dtfim)) {

    $j46_dtfim_dia = $j46_dtini_dia;
    $j46_dtfim_mes = $j46_dtini_mes;
    $j46_dtfim_ano = $j46_dtini_ano + 50;

    $cliptuisen->j46_dtfim_dia = $j46_dtfim_dia;
    $cliptuisen->j46_dtfim_mes = $j46_dtfim_mes;
    $cliptuisen->j46_dtfim_ano = $j46_dtfim_ano;
  }

  db_inicio_transacao();
  $clisenexe->j47_codigo=$j46_codigo;
  $clisenexe->excluir($j46_codigo);

  $j46_dtinc_ano = $cliptuisen->j55_dtinc_ano=$dat[2];
  for($ano=$j46_dtini_ano;$ano<=$j46_dtfim_ano;$ano++){

    $clisenexe->j47_codigo = $j46_codigo;
    $clisenexe->anousu     = $ano;
    $clisenexe->incluir($j46_codigo,$ano);

    if($clisenexe->erro_status=="0"){

      $trans_erro = true;
      break;
    }
  }

  $clisentaxa->j56_codigo = $j46_codigo;
  $clisentaxa->excluir($j46_codigo);

  $taxa = split("X",$dadostaxa);

  for($r=0; $r<sizeof($taxa); $r++){

    if($taxa[$r]!=""){

	    $dad                    = split("yy",$taxa[$r]);
      $clisentaxa->j56_codigo = $cliptuisen->j46_codigo;
      $clisentaxa->j56_receit = $dad[1];
      $clisentaxa->j56_perc   = $dad[0];
      $clisentaxa->incluir($j46_codigo,$dad[1]);

      if($clisentaxa->erro_status=="0"){

        $trans_erro = true;
        break;
      }
    }
  }

  $cliptuisen->alterar($j46_codigo);
  $clisenproc->j61_codproc = $p58_codproc;
  $clisenproc->j61_codigo  = $j46_codigo;
  if ($p58_codproc == "") {
    $clisenproc->excluir(null,"j61_codigo=$j46_codigo");
  } else {

    $result_proc = $clisenproc->sql_record($clisenproc->sql_query_file(null,"*",null,"j61_codigo=$j46_codigo"));
    if ($clisenproc->numrows > 0) {

      $clisenproc->alterar_where("j61_codproc=$p58_codproc and j61_codigo=$j46_codigo");
      if($clisenproc->erro_status=="0"){
        $sqlerro=true;
      }
    } else {

      $clisenproc->incluir();
      if($clisenproc->erro_status=="0"){
        $sqlerro=true;
      }
    }
  }
  db_fim_transacao();

}else if(isset($j46_matric) && isset($j46_codigo)){

  $sql    = $cliptuisen->sql_query("$j46_codigo","iptuisen.*,j45_descr,j56_receit,j56_perc","","");
  $result = $cliptuisen->sql_record($sql);
  @db_fieldsmemory($result,0);
  $db_opcao    = "2";
  $recoloca    = "ok";//libera para recolocar os valores de iptu taxa
  $codigo      = $j46_codigo;
  $result_proc = $clisenproc->sql_record($clisenproc->sql_query(null,"p58_codproc,p58_requer",null,"j61_codigo=$j46_codigo"));

  if ($clisenproc->numrows!=0){
    db_fieldsmemory($result_proc,0);
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
  <script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script type="text/javascript">

  <?if(isset($j46_matric)){?>

    function js_trocaid(valor){

      if(valor!=""){
        location.href = "cad4_iptuisen002.php?<?=$alterando?'':'alterando=true&'?>j46_matric=<?=$j46_matric?>&j46_codigo="+valor;
      }
    }
  <?}?>

    function js_carreg(){

      document.form1.j46_tipo.focus();
      js_trocacordeselect();
    }
  </script>
</head>
<body class="body-default" onLoad="js_carreg();">
  <div class="container">
    <form name="form1" method="post" action="">
      <input name="dadostaxa" type="hidden" value="">
      <input name="j45_tipis" type="hidden" value="<?php echo $j45_tipis ?>">

      <fieldset style="width:520px;">
      <legend>Dados de Isenção</legend>
      <table border="0" align="center">
        <tr>
          <td nowrap title="<?=@$Tj46_matric?>">
            <label for="j46_matric">
              <?php
                if ($alterando) {
                  echo @$Lj46_matric;
                } else {
              ?>
                <a href='' onclick='js_mostrabic_matricula();return false;'><?=@$Lj46_matric?></a>
              <?php } ?>
            </label>
          </td>
          <td>
            <?php
              db_input('j46_matric',10,$Ij46_matric,true,'text',3," onchange='js_pesquisaj46_matric(false);'");
              db_input('z01_nome',40,$Iz01_nome,true,'text',3,'','z01_nomematri');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj46_codigo?>">
            <label for="j46_codigo"> <?=@$Lj46_codigo?></label>
          </td>
          <td>
            <?php db_input('j46_codigo',4,"",true,'text',3,""); ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj46_tipo?>">
            <label for="j46_tipo">
              <?php db_ancora(@$Lj46_tipo,"js_pesquisaj46_tipo(true);document.form1.j45_descr.value='';",$db_opcao); ?>
            </label>
          </td>
          <td>
            <?php
              db_input('j46_tipo',4,$Ij46_tipo,true,'text',$db_opcao,"onchange='js_pesquisaj46_tipo(false);js_limpanome();'");
              db_input('j45_descr',40,$Ij45_descr,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj46_dtini?>">
            <label for="j46_dtini"><?=@$Lj46_dtini?></label>
          </td>
          <td>
            <?php
              db_inputdata('j46_dtini',@$j46_dtini_dia,@$j46_dtini_mes,@$j46_dtini_ano,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj46_dtfim?>">
            <label for="j46_dtfim"><?=@$Lj46_dtfim?></label>
          </td>
          <td>
            <?php
              db_inputdata('j46_dtfim',@$j46_dtfim_dia,@$j46_dtfim_mes,@$j46_dtfim_ano,true,'text',$db_opcao)
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj46_perc?>">
            <label for="j46_perc"> <?=@$Lj46_perc?></label>
          </td>
          <td>
            <?php
              db_input('j46_perc',10,$Ij46_perc,true,'text',$db_opcao,"onChange='js_validapercentual(this);'")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Área total do lote">
            <label for="j34_area"><strong>Área do lote:</strong></label>
          </td>
          <td colspan="2">
            <?php
              $sql_areatot    = "select j34_area from iptubase inner join lote on j34_idbql = j01_idbql where j01_matric = $j46_matric;";
              $result_areatot = $cliptubase->sql_record($sql_areatot);

              if($cliptubase->numrows>0){
                db_fieldsmemory($result_areatot,0);
              }
              db_input('j34_area',10,$Ij34_area,true,'text',3,"");
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="Área a isentar">
            <label for="j46_dif"><strong>Área isenta:</strong></label>
          </td>
          <td>
            <?php
              db_input('j46_arealo',10,$Ij46_arealo,true,'text',$db_opcao,"onchange = 'js_preenchedif(this.name,this.value,document.form1.j34_area.value);'");
            ?>
            <strong>Diferença:</strong>
            <?php
              db_input('j46_dif',10,$Ij46_arealo,true,'text',3,"");

              if (!empty($j34_area) && !empty($j46_arealo)) {

                $j46_dif = $j34_area - $j46_arealo;
                echo "<script>document.form1.j46_dif.value = $j46_dif;</script>";
              }
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <?php
              $j46_idusu = db_getsession("DB_id_usuario");
              db_input('j46_idusu',4,$Ij46_idusu,true,'hidden',$db_opcao,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tp58_codproc?>">
            <label for="p58_codproc"><?php db_ancora(@$Lp58_codproc,"js_pesquisap58_codproc(true);",$db_opcao); ?></label>
          </td>
          <td>
            <?php
              db_input('p58_codproc',10,$Ip58_codproc,true,'text',$db_opcao," onchange='js_pesquisap58_codproc(false);'");
              db_input('p58_requer',40,$Ip58_requer,true,'text',3,'');
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Tj46_hist?>">
            <label for="j46_hist"><?=@$Lj46_hist?></label>
          </td>
          <td>
            <?php
              db_textarea('j46_hist',5,52,$Ij46_hist,true,'text',$db_opcao,"")
            ?>
          </td>
        </tr>
      </table>
    </td>
    <td align="left" width="40%" valign="top">
      <table border=0>
        <?php
        if(@$j46_tipo==""||isset($incluir)){
          $j46_codigo="";
        }
        ?>
        <tr>
          <td>
            <fieldset style="width:500px;" class="separator">
              <legend>Taxas</legend>
              <?php
              $data       = date("Y",db_getsession("DB_datausu"));

              $sSqlTaxas  = " select distinct  ";
              $sSqlTaxas .= "        k02_descr, ";
              $sSqlTaxas .= "        k02_codigo as j19_receit ";
              $sSqlTaxas .= "   from iptucadtaxa  ";
              $sSqlTaxas .= "        inner join iptucadtaxaexe on iptucadtaxa.j07_iptucadtaxa = iptucadtaxaexe.j08_iptucadtaxa ";
              $sSqlTaxas .= "                                 and iptucadtaxaexe.j08_anousu   = ".db_getsession('DB_anousu');
              $sSqlTaxas .= "        inner join tabrec         on tabrec.k02_codigo           = iptucadtaxaexe.j08_tabrec ";
              $sSqlTaxas .= "  order by k02_codigo ";
              $result     = $cliptutaxa->sql_record($sSqlTaxas);
              $numrows    = $cliptutaxa->numrows;

              echo "<table>";

              for($n=0; $n<$numrows; $n++){

                db_fieldsmemory($result,$n);
                if(isset($recoloca) && $recoloca!=""){

                  $resulta = $clisentaxa->sql_record($clisentaxa->sql_query_file($j46_codigo,$j19_receit,"j56_perc"));
                  if($clisentaxa->numrows>0){
                    db_fieldsmemory($resulta,0);
                  } else {
                    $j56_perc = 0;
                  }
                }

                $idReceita = "receit_{$j19_receit}";
                echo "<tr><td style='width:200px;'> <label for='$idReceita'>$j19_receit - $k02_descr </label></td><td><input id='$idReceita' class='receit' name='".$k02_descr."xx".$j19_receit."' type='text' size='10' onChange=\"js_validapercentual(this);\"  onKeyUp=\"js_ValidaCampos(this,4,'$k02_descr','f','f',event);\" value='".@$j56_perc."'  ".($db_opcao==3?"disabled":"")."  ></td></tr>";
              }
              echo "</table>";
              ?>
            </fieldset>
          </td>
        </tr>
        <tr>
          <?php
            if(isset($j46_matric)){

              if(!isset($excluir)){

                $result = $cliptuisen->sql_record($cliptuisen->sql_query_file("","j46_codigo as codigo","","j46_matric=$j46_matric"));
                if($cliptuisen->numrows>0){
                  db_fieldsmemory($result,0);
                }
              }

              $num=$cliptuisen->numrows;

              if($num!=0){
          ?>
                <td>
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr align="center" >
                      <td>
                        <label for="isencoes_cadastradas"><strong>Isenções já Cadastradas</strong></label>
                      </td>
                    </tr>
                    <tr>
                      <td align="center">
                        <?php
                        echo "<select id='isencoes_cadastradas' name='selcod' onchange='js_trocaid(this.value)' style='width:520px;' size='".($num>4?5:($num+1))."'>";
                        echo "<option value='nova' ".(!isset($j46_matric)?"selected":"").">Nova</option>";
                        if (isset($recoloca) && $recoloca!="") {
                          $idcod = $j46_codigo;
                        } else {
                          $idcod = "";
                        }

                        for ($i=0; $i<$num; $i++) {

                          db_fieldsmemory($result,$i);
                          if($codigo!=$idcod){
                            echo "<option  value='".$codigo."' ".($codigo==$idcod?"selected":"").">".$codigo."</option>";
                          }
                        }
                        ?>
                      </td>
                    </tr>
                  </table>
                </td>
          <?
              }
            }
          ?>
        </tr>
      </table>
    </td>
    </fieldset>

    <input name="incluir" type="submit" id="incluir" value="Incluir"      onclick="return js_pegare()"  <?=($db_opcao!=1?"disabled":"")?> />
    <input name="alterar" type="submit" id="alterar" value="Alterar"      onclick="return js_pegare()"  <?=($db_opcao!=2?"disabled":"")?> />
    <input name="excluir" type="submit" id="excluir" value="Excluir"      onclick="return js_pegare()"  <?=($db_opcao!=2?"disabled":"")?> />
    <input name="nova"    type="button" id="nova"    value="Nova Isenção" onclick="js_trocaid('nova')">

    <?php if (!$alterando) { ?>
      <input name="voltar" type="button" id="volta" value="Voltar" onclick="js_volta()">
    <?php } ?>
  </form>
</div>
  <?php db_menu(); ?>
</body>
</html>
<script type="text/javascript">

  var tipoIsencao = document.form1.j45_tipis.value;
  var alterando   = <?php echo ($alterando == true) ? 1 : 0;?>;

  if (alterando == 1 && (tipoIsencao == 1 || tipoIsencao == 2)) {

    var elementosTaxas = document.getElementsByClassName('receit');
    document.form1.j46_perc.readOnly = 'true';
    document.form1.j46_perc.classList.add("readonly");

    for (i in elementosTaxas) {

      if (isNumeric(i)) {
        elementosTaxas[i].readOnly = 'true';
        elementosTaxas[i].classList.add("readonly")
      }
    }
  }

  /**
   * Validamos as obrigatoriedades dos campos
   *
   * @return boolean
   */
  function js_validarCampo() {

    tipoIsencao = document.form1.j45_tipis.value;

    if (document.getElementById('j46_tipo').value == "") {

      alert(_M('tributario.cadastro.cad4_iptuisen.tipo_isencao_obrigatorio'));
      return false;
    }

    if (document.getElementById('j46_dtini').value == "") {

      alert(_M('tributario.cadastro.cad4_iptuisen.data_inicio_obrigatorio'));
      return false;
    }

    if ( (tipoIsencao != 1 && tipoIsencao != 2) && alterando != 1) {

      if (document.getElementById('j46_dtfim').value == "") {

        alert(_M('tributario.cadastro.cad4_iptuisen.data_fim_obrigatorio'));
        return false;
      }
    }

    if (document.getElementById('j46_dtfim').value != "") {

      var dtfim = document.form1.j46_dtfim.value;
      var dtini = document.form1.j46_dtini.value;

      if (dtfim.substr(6,4) == dtini.substr(6,4)) {

        if (dtfim.substr(3,2) == dtini.substr(3,2)) {

          if (dtfim.substr(0,2) < dtini.substr(0,2)) {

            alert(_M('tributario.cadastro.cad4_iptuisen.erro_data_fim_menor'));
            return false;
          }
        } else if (dtfim.substr(3,2) < dtini.substr(3,2)) {

          alert(_M('tributario.cadastro.cad4_iptuisen.erro_data_fim_menor'));
          return false;
        }
      } else if (dtfim.substr(6,4) < dtini.substr(6,4)) {

        alert(_M('tributario.cadastro.cad4_iptuisen.erro_data_fim_menor'));
        return false;
      }
    }

    if (document.getElementById('j46_perc').value == "") {

      alert(_M('tributario.cadastro.cad4_iptuisen.percentual_obrigatorio'));
      return false;
    }

    if (document.getElementById('p58_codproc').value == "") {

      alert(_M('tributario.cadastro.cad4_iptuisen.controle_obrigatorio'));
      return false;
    }

    if (document.getElementById('j46_hist').value == "") {

      alert(_M('tributario.cadastro.cad4_iptuisen.historico_obrigatorio'));
      return false;
    }

    return true;
  }

  function js_pegare() {

    if (!js_validarCampo()) {
      return false;
    }

    var obj   = document.getElementsByTagName("INPUT");
    var val   = "";
    var valor = "";
    var x     = "";
    var expr  = new RegExp("[^0-9\.]+");

    if(document.form1.j45_descr.value == '' || document.form1.j46_tipo.value == ''){

      alert('Tipo de isenção não encontrado no cadastro de isenções');
      document.form1.j46_tipo.value = '';
      document.form1.j46_tipo.focus();
      return false;
    }

    for(var i=0; i<obj.length; i++){

      var matri = obj[i].name.split("xx");

      if( obj[i].hasClassName("receit")){

        valor = obj[i].value;
        if(obj[i].value.match(expr)) {

          alert(matri[0]+" deve ser preenchido somente com números decimais!");
          obj[i].select();
          return false;
        }

        if(obj[i].value=="") {

          alert(matri[0]+" deve ser preenchido!");
          obj[i].select();
          return false;
        }

        /**
         * valor percentual yy numero da receita
         */
        val += x + obj[i].value + "yy" + matri[1];
        x    = "X";
      }
    }

    document.form1.dadostaxa.value=val;
    return true;
  }

  function js_validapercentual(obj){

    valor = new Number(obj.value);
    if(valor > 100 ){
      alert('Percentual nao pode ser maior que 100 !');
      obj.value = '0';
      obj.focus();
      obj.select();
    }
  }

  function js_limpanome(){

    document.form1.j45_descr.value='';
  }

  function js_mostrabic_matricula(){
    js_OpenJanelaIframe('','db_iframe_cadastro','cad3_conscadastro_002.php?cod_matricula=<?=@$j46_matric?>','Pesquisa',true);
  }

  function js_pesquisap58_codproc(mostra){

    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso.php?funcao_js=parent.js_mostraprotprocesso1|p58_codproc|p58_requer','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','db_iframe_cgm','func_protprocesso.php?pesquisa_chave='+document.form1.p58_codproc.value+'&funcao_js=parent.js_mostraprotprocesso','Pesquisa',false);
    }
  }

  function js_mostraprotprocesso(chave,chave1,erro){

    document.form1.p58_requer.value = chave1;
    if(erro==true){

      document.form1.p58_codproc.focus();
      document.form1.p58_codproc.value = '';
    }
  }

  function js_mostraprotprocesso1(chave1,chave2){

    document.form1.p58_codproc.value = chave1;
    document.form1.p58_requer.value  = chave2;
    db_iframe_cgm.hide();
  }

  function js_volta(){
    location.href = 'cad4_iptuisen001.php ';
  }

  function js_pesquisaj46_tipo(mostra){

    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe','func_tipoisen.php?funcao_js=parent.js_mostratipoisen1|0|1|2|3','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','db_iframe','func_tipoisen.php?pesquisa_chave='+document.form1.j46_tipo.value+'&funcao_js=parent.js_mostratipoisen','Pesquisa',false);
    }

  }

  function js_alteraValidacaoDataFim(j45_tipis) {

    document.form1.j46_perc.value    = '';
    document.form1.j46_perc.readOnly = '';
    document.form1.j46_perc.classList.remove("readonly");


    if (j45_tipis == 1) {

      document.form1.j46_perc.value    = '100';
      document.form1.j46_perc.readOnly = 'true';
      document.form1.j46_perc.classList.add("readonly");
    }

    if (j45_tipis == 1 || j45_tipis == 2) {
      document.form1.j46_dtfim.style = "background-color:#e6e4f1;";
    } else {
      document.form1.j46_dtfim.style = "background-color:#FFFFFF;";
    }
  }

  function js_mostratipoisen(chave, tipo, erro, lIsentaTaxa){

    document.form1.j45_descr.value = chave;
    document.form1.j45_tipis.value = tipo;
    if(erro==true){

      document.form1.j46_tipo.focus();
      document.form1.j46_tipo.value = '';
    }

    validaIsencaoTaxas(+tipo, (lIsentaTaxa == 't' || lIsentaTaxa == undefined));
    js_alteraValidacaoDataFim(document.form1.j45_tipis.value);
  }

  function js_mostratipoisen1(chave1, chave2, chave3, lIsentaTaxa){

    document.form1.j46_tipo.value  = chave1;
    document.form1.j45_descr.value = chave2;
    document.form1.j45_tipis.value = chave3;
    db_iframe.hide();

    validaIsencaoTaxas(+chave3, (lIsentaTaxa == 't' || lIsentaTaxa == undefined));
    js_alteraValidacaoDataFim(document.form1.j45_tipis.value);
  }

  /**
   * Valida se as isenções devem ser bloqueadas ou se o tipo é imune para as taxas
   */
  function validaIsencaoTaxas(iTipo, lIsenta) {

    for (oElemento of document.querySelectorAll('.receit')) {

      oElemento.readOnly = (!lIsenta);

      oElemento.readOnly && oElemento.classList.add("readonly");
      !oElemento.readOnly && oElemento.classList.remove("readonly");

      oElemento.value = '';

      if (!lIsenta) {
        oElemento.value = '0';
      }
    }
  }

  function js_pesquisa(){
    js_OpenJanelaIframe('','','func_iptuisen.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
  }

  function js_preenchepesquisa(chave){
    location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
  }

  function js_pesquisaj46_matric(mostra){

    if(mostra==true){
      js_OpenJanelaIframe('','','func_iptubase.php?funcao_js=parent.js_mostraiptubase1|0|1','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','','func_iptubase.php?pesquisa_chave='+document.form1.j46_matric.value+'&funcao_js=parent.js_mostraiptubase','Pesquisa',false);
    }
  }

  function js_mostraiptubase(chave,erro){

    document.form1.z01_nome.value = chave;
    if(erro==true){

      document.form1.j46_matric.focus();
      document.form1.j46_matric.value = '';
    }
  }
  function js_mostraiptubase1(chave1,chave2){
    document.form1.j46_matric.value = chave1;
    document.form1.z01_nome.value = chave2;
    db_iframe.hide();
  }

  function js_pesquisa(){
    js_OpenJanelaIframe('','','func_iptuisen.php?funcao_js=parent.js_preenchepesquisa|0','Pesquisa',true);
  }

  function js_preenchepesquisa(chave){

    db_iframe.hide();
    location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
  }

  function js_cgm(mostra){

    if(mostra==true){
      js_OpenJanelaIframe('','','func_nome.php?funcao_js=parent.js_mostra1|0|1','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','','func_nome.php?pesquisa_chave='+document.form1.j46_numcgm.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
    }
  }

  function js_mostra1(chave1,chave2){

    document.form1.j46_numcgm.value = chave1;
    document.form1.z01_nome.value   = chave2;
    db_iframe.hide();
  }

  function js_mostra(erro,chave){

    document.form1.z01_nome.value = chave;

    if(erro==true){

      document.form1.j46_numcgm.focus();
      document.form1.j46_numcgm.value="";
    }
  }

  function js_preenchedif(nome,valor1,valor2){

    valor1 = parseInt(valor1);
    valor2 = parseInt(valor2);

    if(valor1>valor2){

      alert("A área a isentar deve ser menor que a área total do lote.");
      eval('document.form1.'+nome+'.value = "";');
      eval('document.form1.'+nome+'.focus();');
      document.form1.j46_dif.value = "";
    } else {

      if((valor1 != "" || valor1==0) && !isNaN(valor1)){
        document.form1.j46_dif.value = valor2 - valor1;
      }else{
        document.form1.j46_dif.value = "";
      }
    }
  }
</script>
<?
if(isset($incluir)||isset($excluir)||isset($alterar)){

  if($cliptuisen->erro_status=="0"){

    db_msgbox($erro_msg);
    if($cliptuisen->erro_campo!=""){

      echo "<script> document.form1.".$cliptuisen->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$cliptuisen->erro_campo.".focus();</script>";
    }
  }else{

    $cliptuisen->erro(true,false);
    db_redireciona("cad4_iptuisen002.php?j46_matric=$j46_matric&j46_codigo=nova".($alterando?"&alterando=true":""));
  }
}
?>
