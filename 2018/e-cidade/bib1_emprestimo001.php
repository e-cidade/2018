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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_acervo_classe.php"));
require_once(modification("classes/db_reserva_classe.php"));
require_once(modification("classes/db_leitor_classe.php"));
require_once(modification("classes/db_carteira_classe.php"));
require_once(modification("classes/db_emprestimo_classe.php"));
require_once(modification("classes/db_emprestimoacervo_classe.php"));
require_once(modification("classes/db_devolucaoacervo_classe.php"));
require_once(modification("classes/db_exemplar_classe.php"));
require_once(modification("classes/db_bib_parametros_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));

$bi18_devolucao_dia = '';
$bi18_devolucao_mes = '';
$bi18_devolucao_ano = '';
db_postmemory($_POST);

$aux                = new cl_arquivo_auxiliar;
$clemprestimo       = new cl_emprestimo;
$clacervo           = new cl_acervo;
$clreserva          = new cl_reserva;
$clleitor           = new cl_leitor;
$clcarteira         = new cl_carteira;
$clexemplar         = new cl_exemplar;
$clemprestimoacervo = new cl_emprestimoacervo;
$cldevolucaoacervo  = new cl_devolucaoacervo;
$clbib_parametros   = new cl_bib_parametros;
$oRotulo            = new rotulocampo;

$clemprestimo->rotulo->label();
$oRotulo->label('bi06_seq');
$oRotulo->label('bi06_titulo');
$oRotulo->label('bi07_qtdlivros');
$oRotulo->label('bi07_tempo');
$oRotulo->label('ov02_nome');


$db_opcao = 1;
$db_botao = true;
$depto    = db_getsession("DB_coddepto");

$sql    = "SELECT bi17_codigo, bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result = db_query($sql);;
$linhas = pg_num_rows($result);

if($linhas != 0) {

  db_fieldsmemory($result, 0);
  $sSqlBibParametros = $clbib_parametros->sql_query("", "bi26_leitorbarra", "", " bi26_biblioteca = $bi17_codigo");
  $result1           = $clbib_parametros->sql_record($sSqlBibParametros);

  if ($clbib_parametros->numrows > 0) {
    db_fieldsmemory($result1,0);
  } else {
    $bi26_leitorbarra = "N";
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
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
<body >

<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<?
if (isset($acumula)) {

  //grava emprestimo
  $clemprestimo->bi18_retirada  = date("Y-m-d");
  $clemprestimo->bi18_devolucao = $dev;
  $clemprestimo->bi18_carteira  = $leitor;
  $clemprestimo->bi18_usuario   = db_getsession("DB_id_usuario");
  $clemprestimo->incluir(null);

  //grava emprestimoacervo
  $array_emprestimo = explode("|",$acumula);
  for ($i = 0; $i < count($array_emprestimo); $i++) {

    $bi19_emprestimo                     = $clemprestimo->bi18_codigo;
    $clemprestimoacervo->emite           = $emite;
    $clemprestimoacervo->bi19_emprestimo = $bi19_emprestimo;
    $clemprestimoacervo->bi19_exemplar   = $array_emprestimo[$i];
    $clemprestimoacervo->incluir(null);

    if (isset($reserva) && $reserva != "") {

      $sSqlReserva = "update reserva set bi14_retirada = '".date("Y-m-d")."',bi14_situacao = 'R' where bi14_codigo = $reserva";
      $clreserva->sql_record($sSqlReserva);
    }
  }

  if ($clemprestimoacervo->erro_status == "0") {
    $clemprestimoacervo->erro(true,false);
  } else {
    $clemprestimoacervo->erro(true,true);
  };
}

if (isset($leitor)&&$leitor != "") {

  $sSqlDevolucaoAcervo = "select bi23_codigo, bi06_titulo, bi18_retirada, bi18_devolucao
                            from emprestimoacervo
                                 inner join emprestimo      on bi18_codigo = bi19_emprestimo
                                 inner join carteira        on bi16_codigo = bi18_carteira
                                 inner join leitor          on bi10_codigo = bi16_leitor
                                 inner join leitorcategoria on bi07_codigo = bi16_leitorcategoria
                                 inner join biblioteca      on bi17_codigo = bi07_biblioteca
                                 inner join exemplar        on bi23_codigo = bi19_exemplar
                                 inner join acervo          on bi06_seq    = bi23_acervo
                           where bi18_carteira = $leitor
                             and bi07_biblioteca = $bi17_codigo
                             and not exists(select *
                                              from devolucaoacervo
                                             where devolucaoacervo.bi21_codigo = emprestimoacervo.bi19_codigo
                                           )";
  $resultX = $cldevolucaoacervo->sql_record($sSqlDevolucaoAcervo);

  $sCamposCarteira = "bi16_leitor as codleitor, bi16_validade, bi07_qtdlivros";
  $sWhereCarteira  = " bi16_codigo = $leitor AND bi07_biblioteca = $bi17_codigo";
  $sSqlCarteira    = $clcarteira->sql_query("", $sCamposCarteira, "bi16_validade desc", $sWhereCarteira);
  $resultY         = $clcarteira->sql_record($sSqlCarteira);

  if ($clcarteira->numrows == 0) {

    ?>
    <script>
      if (confirm("Leitor NÃO possui Carteira cadastrada. Deseja cadastrar carteira para o leitor?")) {
        location.href = "bib1_leitor000.php?opcao=2&chavepesquisa=<?=$codleitor?>";
      } else {
        location.href = "bib1_emprestimo001.php";
      }
    </script>
    <?
    exit;
  } else if ($clcarteira->numrows > 0 && str_replace("-", "", pg_result($resultY, 0, 'bi16_validade')) - date("Ymd") < 0) {

    db_fieldsmemory($resultY, 0);
    ?>
    <script>
      if (confirm("Leitor está com Carteira VENCIDA. Deseja validar outra carteira para o leitor?")) {
        location.href = "bib1_leitor000.php?opcao=2&chavepesquisa=<?=$codleitor?>";
      } else {
        location.href="bib1_emprestimo001.php";
      }
    </script>
    <?
    exit;
  }
  $permitido = pg_result($resultY, 0, 'bi07_qtdlivros');
  ?>
  <table border="0" width="90%" align="center">
    <tr>
      <td colspan="2">
        <br>
        <fieldset width="100%"><legend><b>Confirmação de Empréstimo:</b></legend>
          <b>Leitor:</b> <?=$nomeleitor?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <b>Empréstimos abertos:</b> <?=$cldevolucaoacervo->numrows?>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <b>Limite Permitido:</b> <?=$permitido?>
          <table border="1" width="100%" cellspacing="0">
            <form name="form1" method="post">
              <tr bgcolor="#999999" align="center">
                <td width="20%"><b>Código</b></td>
                <td><b>Título</b></td>
                <td>&nbsp;</td>
              </tr>
              <?
              $cor1       = "#dbdbdb";
              $cor2       = "#f3f3f3";
              $cor        = "";
              $contador   = 0;
              $lista      = !isset( $lista ) ? '' : $lista;
              $qtd_lista  = explode("|",$lista);

              for($x = 0; $x < count($qtd_lista); $x++) {

                if( empty( $qtd_lista[$x] ) ) {
                  continue;
                }

                $result = $clexemplar->sql_record($clexemplar->sql_query("", "*", "", " bi23_codigo = $qtd_lista[$x]"));
                db_fieldsmemory($result, 0);

                if ($cor == $cor1) {
                  $cor = $cor2;
                } else {
                  $cor = $cor1;
                }
                $contador++;
                ?>
                <tr bgcolor="<?=$cor?>">
                  <td align="center">
                    <input type='hidden' value='<?=$bi23_codigo?>' name='emprestimo' id='emprestimo'>
                    <?=$bi23_codigo?>
                  </td>
                  <td>
                    <?=$bi06_titulo?>
                  </td>
                  <td width="10%">
                    <input type="button"
                           name="excluiritem"
                           id="excluiritem"
                           value="Excluir"
                           onclick="js_excluiritem(<?=$bi23_codigo?>,<?=count($qtd_lista)?>)"
                           <?=count($qtd_lista)==1?"style='visibility:hidden'":""?>>
                  </td>
                </tr>
                <?
              }
              ?>
              <tr bgcolor="#999999">
                <td colspan="3">Retirada: <b><?=date("d/m/Y")?></b><br>Devolução: <b><?=db_formatar($dev,'d')?></b></td>
              </tr>
              <tr>
                <td colspan="4">
                  <input name="reserva" type="hidden" id="reserva" value="<?=@$reserva?>" />
                  <input name="leitor" type="hidden" id="leitor" value="<?=$leitor?>">
                  <input name="dev" type="hidden" id="dev" value="<?=$dev?>">
                  <input name="nome_leitor" type="hidden" id="nome_leitor" value="<?=@$nome_leitor?>">
                  <input name="lista" type="hidden" id="lista" value="<?=$lista?>">
                  <input name="qtd_lista" type="hidden" id="qtd_lista" value="<?=$contador?>">
                  <input name="confirmar" type="button" id="confirmar" value="Confirmar"
                         onclick="js_confirma('<?=$permitido?>',<?=$cldevolucaoacervo->numrows?>)"
                         <?if($cldevolucaoacervo->numrows>=$permitido){echo 'disabled';}?>>
                  <input name="cancelar" type="button" id="cancelar" value="Cancelar" onclick="location='bib1_emprestimo001.php'">
                  <input type="checkbox" name="emite" value="true"> Emitir Comprovante
                </td>
              </tr>
            </form>
          </table>
        </fieldset>
        <?
        if ($cldevolucaoacervo->numrows >= $permitido) {
          db_msgbox("N° máximo de empréstimos($permitido) para este leitor já atingido!");
        }
        ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <fieldset width="100%"><legend><font color='red'><b>Empréstimos pendentes:</b></font></legend>
        <?
        if ($cldevolucaoacervo->numrows == 0) {
          echo "<center><font color='green'><b>Nenhum empréstimo pendente.</b></font></center>";
        } else {

          ?>
          <table border="1" width="100%" cellspacing="0">
            <tr bgcolor='#999999'>
              <td><b>Código</b></td>
              <td><b>Nome</b></td>
              <td><b>Emprestado</b></td>
              <td><b>Devolver</b></td>
              <td><b>Situação</b></td>
            </tr>
            <?
            for ($x = 0; $x < $cldevolucaoacervo->numrows; $x++) {

              db_fieldsmemory($resultX,$x);

              $cor      = ($x % 2 == 0) ? $cor2 : $cor1;
              $situacao = "green";
              $texto    = "NORMAL";
              if (str_replace("-", "", $bi18_devolucao) - date("Ymd") < 0) {

                $situacao = "red";
                $texto    = "ATRASADO";
              }
              ?>
              <tr bgcolor='<?=$cor?>'>
                <td><?=$bi23_codigo?></td>
                <td><?=$bi06_titulo?></td>
                <td><?=db_formatar($bi18_retirada, 'd')?></td>
                <td><?=db_formatar($bi18_devolucao, 'd')?></td>
                <td align="center" bgcolor="<?=$situacao?>" style="color:#FFFFFF;"><?=$texto?></td>
              </tr>
            <?}?>
           </table>
       <?}?>
       </fieldset>
      </td>
    </tr>
  </table>
  <?
} else {

  if (empty($bi18_retirada_dia)) {

    $bi18_retirada_dia = date("d",db_getsession("DB_datausu"));
    $bi18_retirada_mes = date("m",db_getsession("DB_datausu"));
    $bi18_retirada_ano = date("Y",db_getsession("DB_datausu"));
  }
  ?>
  <form name="form1" method="post" action="">
    <table  align="center">
       <tr>
         <td colspan="2">
           <br>
           <fieldset width="95%"><legend><b>Dados do Leitor</b></legend>
             <table border="0">
               <tr>
                 <td nowrap title="<?=$Tbi18_carteira?>">
                    <?php db_ancora($Lbi18_carteira, "js_pesquisabi18_carteira(true);", $db_opcao);?>
                 </td>
                 <td>
                    <?php
                      db_input('bi18_carteira', 10, $Ibi18_carteira, true, 'text',
                              $db_opcao, " onchange='js_pesquisabi18_carteira(false);' onKeyPress='tab(event,12)'");
                      db_input('ov02_nome', 50, $Iov02_nome, true, 'text', 3, " ");
                    ?>
                 </td>
               </tr>
               <tr>
                 <td nowrap title="<?=$Lbi07_qtdlivros?>">
                    <?=$Lbi07_qtdlivros?>
                 </td>
                 <td>
                    <?php
                      db_input('qtd', 10, "", true, 'text', 3, "");
                      echo $Lbi07_tempo;
                      db_input('tempo', 10, "", true, 'text', 3, "");
                    ?>
                 </td>
               </tr>
               <tr>
                 <td nowrap title="<?=$Tbi18_retirada?>">
                    <?=$Lbi18_retirada?>
                 </td>
                 <td>
                    <?php
                      db_inputdata('bi18_retirada', $bi18_retirada_dia, $bi18_retirada_mes, $bi18_retirada_ano, true,
                                  'text', 3, "")?>
                 </td>
               </tr>
               <tr>
                 <td nowrap title="<?=$Tbi18_devolucao?>">
                   <?=$Lbi18_devolucao?>
                 </td>
                 <td>
                    <?php
                      db_inputdata('bi18_devolucao', $bi18_devolucao_dia, $bi18_devolucao_mes, $bi18_devolucao_ano, true,
                                   'text', 1,
                                   " onchange=\"js_diasemana(this);\"","",""," parent.js_diasemana();");
                      db_input('diasemana', 10, "", true, 'text', 3, "")
                    ?>
                 </td>
               </tr>
             </table>
           </fieldset>
           <iframe src="" name="iframe_verificadata" id="iframe_verificadata" width="0" height="0" frameborder="0"></iframe>
         </td>
       </tr>
     <tr>
       <td colspan=2  align="center">
       </td>
     </tr>
     <tr>
       <td colspan="2" align="center">
         <b><a href="#" onclick="js_abrepopup()">Pesquisar por assunto<a></b><br>
          <?php if ($bi26_leitorbarra == "S") {?>

             <br>
             <b>Pesquisar por Código de Barras:</b>
             <input type="text" name="bi23_codbarras"  id='bi23_codbarras' value="" size="20">
             <input type="button" name="lancarbarras" value="Lançar" size="" onClick="js_codbarras();">
             <br>
          <?php
          }
          ?>
          <?php
            $aux->cabecalho      = "<strong>Empréstimo</strong>";
            $aux->codigo         = "bi23_codigo"; //chave de retorno da func
            $aux->descr          = "bi06_titulo";   //chave de retorno
            $aux->nomeobjeto     = 'emprestimo';
            $aux->funcao_js      = 'js_mostratitulo';
            $aux->funcao_js_hide = 'js_mostratitulo1';
            $aux->sql_exec       = "";
            $aux->func_arquivo   = "func_exemplar.php";  //func a executar
            $aux->nomeiframe     = "db_iframe_exemplar";
            $aux->localjan       = "";
            $aux->onclick        = "";
            $aux->db_opcao       = 2;
            $aux->tipo           = 2;
            $aux->top            = 0;
            $aux->linhas         = 7;
            $aux->vwidth         = 400;
            $aux->nome_botao     = 'db_lanca_emprestimo';
            $aux->funcao_gera_formulario();
          ?>
       </td>
     </tr>
     <tr>
       <td colspan="2" align = "center">
         <input name="emite2" id="emite2" type="button" value="Processar" onclick="js_emite();" >
       </td>
     </tr>
    </table>
  </form>
 <script>
   js_tabulacaoforms("form1","bi18_carteira",true,1,"bi18_carteira",true);
 </script>
<?php
}
?>
<?php db_menu();?>
</body>
</html>
<script>

var oGet = js_urlToObject();

$('bi23_codigo').addEventListener('input', function (event) {
  js_ValidaCampos(this,1,'Código do Exemplar','f','f',event);
});


function js_pesquisabi18_carteira(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_leitor',
                        'func_leitorproc.php?funcao_js=parent.js_mostraleitor1|bi16_codigo|ov02_nome|bi07_tempo|bi07_qtdlivros',
                        'Pesquisa',
                        true);
  } else {

    if (document.form1.bi18_carteira.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo',
                          'db_iframe_leitor',
                          'func_leitorproc.php?pesquisa_chave2='+document.form1.bi18_carteira.value
                                            +'&funcao_js=parent.js_mostraleitor',
                          'Pesquisa',
                          false);
    } else {

      $('ov02_nome').value           = '';
      $('qtd').value                 = '';
      $('tempo').value               = '';
      $('bi18_devolucao').value      = '';
      $('bi18_devolucao_dia').value  = '';
      $('bi18_devolucao_ano').value  = '';
      $('bi18_devolucao_mes').value  = '';
      $('diasemana').value           = '';
    }
  }
}

function js_abrepopup() {
  js_OpenJanelaIframe('CurrentWindow.corpo',
                      'db_iframe_acervo2',
                      'bib3_assunto001.php?pop&funcao_js=parent.js_mostratitulopop',
                      'Pesquisa',
                      true);
}

function js_mostratitulopop(chave, chave1) {

  document.form1.bi23_codigo.value = chave;
  document.form1.bi06_titulo.value = chave1;
  db_iframe_acervo2.hide();
  document.form1.db_lanca_emprestimo.onclick = js_insSelectemprestimo;
  document.form1.db_lanca_emprestimo.click();
  document.form1.db_lanca_emprestimo.onclick = js_insSelectemprestimo;
}

function js_mostraleitor(chave1, chave2, chave3, erro) {

  document.form1.ov02_nome.value = chave1;
  document.form1.tempo.value     = chave2;
  document.form1.qtd.value       = chave3;

  if (erro == true) {

    document.form1.bi18_carteira.focus();
    document.form1.bi18_carteira.value             = '';
    document.form1.bi18_devolucao.value            = '';
    document.form1.diasemana.value                 = '';
    return false;
  }
  somadata(chave2);
}

function js_mostraleitor1(chave1, chave2, chave3, chave4) {

  document.form1.bi18_carteira.value  = chave1;
  document.form1.ov02_nome.value      = chave2;
  document.form1.tempo.value          = chave3;
  document.form1.qtd.value            = chave4;
  somadata(chave3);
  db_iframe_leitor.hide();
}

function somadata(dias) {

  var dia = "<?=date('d')?>";
  var mes = "<?=date('m')?>";
  var ano = "<?=date('Y')?>";
  var i   = dias;

  for (i = 0; i < dias; i++) {

    if (mes == "01" || mes == "03" || mes == "05" || mes == "07" || mes == "08" || mes == "10" || mes == "12") {

      if (mes == "12" && dia == "31") {

        mes = "01";
        ano++;
        dia = "00";
      }

      if (dia == "31" && mes != "12") {

        mes++;
        dia = "00";
      }
    }

    if (mes == "04" || mes == "06" || mes == "09" || mes == "11") {

      if (dia == "30") {

        dia =  "00";
        mes++;
      }
    }

    if (mes == "02") {

      if (ano % 4 == 0) {

        if (dia == "29") {

          dia = "00";
          mes++;
        }
      } else {

        if (dia == "28") {

          dia = "00";
          mes++;
        }
      }
    }
    dia++;
  }

  dia = new String(dia).lpad('0', 2);
  mes = new String(mes).lpad('0', 2);

  document.form1.bi18_devolucao.disabled         = false;
  document.form1.bi18_devolucao.style.background = "#FFFFFF";
  iframe_verificadata.location                   = "bib1_emprestimo002.php?ano="+ano+"&mes="+mes+"&dia="+dia;
}

function js_emite() {

  var qtd = 0;

  for (i = 0; i < document.form1.length; i++) {

    if (document.form1.elements[i].name == "emprestimo[]") {

      vir   = "";
      lista = "";

      for (x = 0; x < document.form1.elements[i].length; x++) {

        qtd                                            = qtd+1;
        document.form1.elements[i].options[x].selected = true;
        lista += vir+document.form1.emprestimo.options[x].value;
        vir    = "|";
      }
    }
  }

  leitor     = document.form1.bi18_carteira;
  nomeleitor = document.form1.ov02_nome;
  devolucao  = document.form1.bi18_devolucao_ano.value
          +'-'+document.form1.bi18_devolucao_mes.value
          +'-'+document.form1.bi18_devolucao_dia.value;

  if (leitor.value == "") {

    alert("Preencha o campo Leitor corretamente!")
    leitor.style.backgroundColor = "#99A9AE";
    leitor.focus();
    return false;
  }

  //-- ve se tem lista
  if (qtd == 0) {

    alert('Lista de Empréstimo não pode ser vazia ! ');
    document.form1.bi23_codigo.style.backgroundColor = "#99A9AE";
    document.form1.bi23_codigo.focus();
    return false;
  }

  if (qtd > document.form1.qtd.value) {

    alert('Limite de empréstimos para este leitor é de '+document.form1.qtd.value+' unidade(s)!');
    document.form1.bi23_codigo.style.backgroundColor = "#99A9AE";
    document.form1.bi23_codigo.focus();
    return false;
  }

  if (devolucao == "--") {

    alert("Preencha o campo Devolução corretamente!")
    document.form1.bi18_devolucao_dia.focus();
    return false;
  }
  location = 'bib1_emprestimo001.php?leitor='+leitor.value
                                  +'&nomeleitor='+nomeleitor.value
                                  +'&lista='+lista
                                  +'&dev='+devolucao;
}

function tab(event, form) {

  e = event;
  k = e.keyCode;

  if (k == 13) {
    document.form1[form].focus()
  }
}

function js_confirma(permitido, jaemprestado) {

  var reserva     = document.form1.reserva.value;
  var leitor      = document.form1.leitor.value;
  var nome_leitor = document.form1.nome_leitor.value;
  var dev         = document.form1.dev.value;
  var qtd_lista   = document.form1.qtd_lista.value;
  var acumula     = '';
  var qtd         = 0;
  var sep         = '';

  if (qtd_lista == 1) {

    acumula = document.form1.emprestimo.value;
    qtd++;
  } else {

    for (i = 0; i < qtd_lista; i++) {

      acumula += sep+document.form1.emprestimo[i].value;
      sep = "|";
      qtd ++;
    }
  }

  if (document.form1.emite.checked == true) {
    emite = true;
  } else {
    emite = false;
  }

  if ((qtd + jaemprestado) > permitido) {

    restante = permitido - jaemprestado;
    alert("Leitor já possui "+jaemprestado+" empréstimo(s), podendo efetuar somente mais "+restante+".");
  } else {

    document.form1.confirmar.disabled = true;
    location = 'bib1_emprestimo001.php?leitor='+leitor
                                    +'&dev='+dev
                                    +'&acumula='+acumula
                                    +'&nome_leitor='+nome_leitor
                                    +'&emite='+emite
                                    +'&reserva='+reserva;
  }
}

function js_excluiritem(codigo, linhas) {

  if (linhas == 1) {
    location = "bib1_emprestimo001.php";
  } else {

    clista    = document.form1.lista.value.split("|");
    novalista = "";
    sep       = "";

    for (i = 0; i < linhas; i++) {

      if (clista[i] != codigo) {

        novalista += sep+clista[i];
        sep = "|";
      }
    }

    var reserva     = document.form1.reserva.value;
    var leitor      = document.form1.leitor.value;
    var nomeleitor  = document.form1.nome_leitor.value;
    var devolucao   = document.form1.dev.value;
    location        = 'bib1_emprestimo001.php?leitor='+leitor
                                           +'&nomeleitor='+nomeleitor
                                           +'&lista='+novalista
                                           +'&dev='+devolucao
                                           +'&reserva='+reserva;
  }
}

function js_diasemana(oElement) {

  if ( !js_validaDbData( oElement ) ) {
    return false;
  }

  if (document.form1.bi18_devolucao_ano.value != "") {

    d1 = document.form1.bi18_devolucao_dia.value;
    m1 = document.form1.bi18_devolucao_mes.value;
    a1 = document.form1.bi18_devolucao_ano.value;

    if (d1 == "" || m1 == "" || a1 == "") {
      alert("Preencha todos os campos da data!");
    } else {

      dev = parseInt(a1+m1+d1);
      ret = parseInt(document.form1.bi18_retirada_ano.value+document.form1.bi18_retirada_mes.value+document.form1.bi18_retirada_dia.value);

      if (dev < ret) {

        alert("Data de Devolução deve ser maior ou igual a Data de Retirada!");
        document.form1.diasemana.value          = "";
        document.form1.bi18_devolucao.value     = "";
        document.form1.bi18_devolucao_dia.value = "";
        document.form1.bi18_devolucao_mes.value = "";
        document.form1.bi18_devolucao_ano.value = "";
      } else {
        iframe_verificadata.location = "bib1_emprestimo002.php?ano="+a1+"&mes="+m1+"&dia="+d1;
      }
    }
  } else {
    document.form1.diasemana.value = "";
  }
}

function js_codbarras() {

  if (document.form1.bi23_codbarras.value != "") {
    iframe_verificadata.location = "bib1_emprestimo003.php?bi23_codbarras="+document.form1.bi23_codbarras.value;
  }
}

<?if ((isset($bi23_codigo) && isset($leitor) && $leitor == "")) {?>

    document.form1.db_lanca_emprestimo.onclick = js_insSelectemprestimo;
    document.form1.db_lanca_emprestimo.click();
<?}?>

if ( oGet.bi23_codigo ) {
  js_BuscaDadosArquivoemprestimo(false);
}

if ( $('bi23_codbarras') ) {

  $('bi23_codbarras').observe('keyup', function(event) {

    if (event.which == 13) {
      js_codbarras();
    }

  });

}



</script>