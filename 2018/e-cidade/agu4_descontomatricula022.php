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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_arretipo_classe.php");
require_once("classes/db_arrecad_classe.php");
require_once("classes/db_tabrec_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");
require_once("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once("classes/db_iptubase_classe.php");

db_postmemory($HTTP_POST_VARS);

$cliframe_seleciona = new cl_iframe_seleciona;
$clarretipo         = new cl_arretipo;
$clarrecad          = new cl_arrecad;
$cltabrec           = new cl_tabrec;
$clrotulo           = new rotulocampo;
$oPost              = db_utils::postMemory($_POST);
$oGet               = db_utils::postMemory($_GET);

$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();

?>

<html>
<head>
<title>DBSeller Informática Ltda - Página Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<?
  db_app::load('scripts.js, prototype.js, strings.js, datagrid.widget.js');
  db_app::load('estilos.css, grid.style.css');
?>
</head>
  
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
      marginheight="0" bgcolor="#cccccc">
  
  <center>
    <form name="form1" method="post">
      <fieldset style="margin-top: 50px; width: 790px;">
        <legend>
          <strong>Desconto (Lança Desconto)</strong>
        </legend>
        
        <table border='0' style="margin-top: 10px;">
          <tr>
            <td align="left" nowrap title="<?=$Tz01_nome?>"><b>Matricula:</b>
            </td>
            <td>
              <?
                db_input('j01_matric', 7, $Ij01_matric, true, 'text', 3, "onchange='js_matri(false)'");
                db_input('z01_nome', 50, 0, true, 'text', 3, "", "z01_nomematri");
              ?>
            </td>
          </tr>
          <tr>
            <td align="left" nowrap title="<?=$Tk00_tipo?>"><b>Tipo de origem:</b>
            </td>
            <td align="left" nowrap>
              <select name="tipo" id="tipo" onchange='js_passainfo(this.value, 0);' /> <?
                $sSql = "SELECT DISTINCT
                                arretipo.k00_tipo,
                                arretipo.k00_descr
                           FROM arretipo
                          WHERE arretipo.k00_instit = ".db_getsession('DB_instit')."
                            AND EXISTS (SELECT arrematric.*
                                          FROM arrematric
                                               INNER JOIN arreinstit ON arreinstit.k00_numpre = arrematric.k00_numpre
                                                                    AND arreinstit.k00_instit = ".db_getsession('DB_instit')."
                                         WHERE arrematric.k00_matric = $j01_matric
                            AND EXISTS (SELECT arrecad.k00_numpre
                                          FROM arrecad
                                         WHERE arrecad.k00_numpre = arrematric.k00_numpre
                                           AND arrecad.k00_tipo   = arretipo.k00_tipo))";
                
                $result       = $clarretipo->sql_record($sSql);
                $numrowsTipos = $clarretipo->numrows;
                
                if ($numrowsTipos == 0) {
                  
                  db_msgbox('Não existem débitos em aberto!');
                  echo "<script>location.href='agu4_descontomatricula011.php'</script>";
                }
                
                $entra = false;
                
                if ($numrowsTipos > 1) {
                
                  echo "<option value=\"0\" >Escolha origem</option>\n";
                } else {
                
                  $entra = true;
                }
                
                for($i = 0; $i < $numrowsTipos; $i++) {
                  
                  db_fieldsmemory($result, $i);
                  
                  if ($entra == true) {
                    $iTipo = $k00_tipo;
                  }
                  
                  echo "<option ".(($iTipo == $k00_tipo) ? 'selected' : '' )." value=\"$k00_tipo\" >$k00_descr</option>\n";
                }
              ?>
              </select>
            </td>
          </tr>
          <tr id="combo" style="display: none;">
            <td align="left" nowrap title="<?=$Tk00_tipo?>">
              <b>Receita:</b>
            </td>
            <td align="left" nowrap>
              <select name="receita" id="receita" onchange='js_passainfo(0, this.value);' />
                <?
                  
                  $sSql = "SELECT DISTINCT
                                  tabrec.k02_codigo,
                                  tabrec.k02_descr
                             FROM tabrec
                            WHERE EXISTS (SELECT arrematric.*
                                            FROM arrematric
                                                 INNER JOIN arreinstit ON arreinstit.k00_numpre = arrematric.k00_numpre
                                                                      AND arreinstit.k00_instit = ".db_getsession('DB_instit')."
                                           WHERE arrematric.k00_matric = $j01_matric
                                             AND EXISTS (SELECT arrecad.k00_numpre
                                                           FROM arrecad
                                                          WHERE arrecad.k00_numpre = arrematric.k00_numpre
                                                            AND arrecad.k00_receit = tabrec.k02_codigo
                                                            AND arrecad.k00_tipo   = $iTipo))";
                  
                  $result          = $cltabrec->sql_record($sSql);
                  $numrowsReceitas = $cltabrec->numrows;
                  
                  $entra = false;
                  
                  if ($numrowsReceitas > 1) {
                    
                    echo "<option value=\"0\" >Escolha a Receita</option>\n";
                  } else {
                    
                   $entra = true;
                  }
                  
                  for($i = 0; $i < $numrowsReceitas; $i++) {
                    
                    db_fieldsmemory($result, $i);
                    
                    if ($entra == true) {
                      $iReceita = $k02_codigo;
                    }
                    
                    echo "<option ".(($iReceita == $k02_codigo) ? 'selected' : '' )." value=\"$k02_codigo\" >$k02_descr</option>\n";
                  }
                ?>
              </select>
            </td>
          </tr>
          <?
            db_input('iTipo'   , 10, '', true, 'hidden', 3);
            db_input('iReceita', 10, '', true, 'hidden', 3);
          ?>
          <?
            if (isset($iTipo) && $iTipo != "" && $iTipo != '0') {
          ?>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr id="grid" style="display: none;">
            <td colspan="4" align="center">
              <div id="oGridDebitos"></div>
            <td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="4">
              <table>
                <tr>
                  <td align="right">Desconto Máximo:</td>
                    <td>
                      <?
                        $clrotulo->label("k00_valor");
                        @$k00_valor = @$tvlrhist;
                        db_input('k00_valor', 15, $Ik00_valor, true, 'text', 3);
                      ?>
                    </td>
                  </tr>
                <tr>
                  <td align="right">Percentual:</td>
                  <td>
                    <?
                      $clrotulo->label("DBtxt8");
                      db_input('DBtxt8', 15, $IDBtxt8, true, 'text', 2, " onchange='js_calcula()'");
                    ?>
                  </td>
                </tr>
                <tr>
                  <td align="right">Valor:</td>
                  <td>
                    <?
                      $clrotulo->label("DBtxt9");
                      db_input('DBtxt9', 15, $IDBtxt9, true, 'text', 2, " onchange='js_calculavalor()'");
                    ?>
                  </td>
                </tr>
                <tr>
                  <td align="right">Observação:</td>
                  <td>
                    <?
                      $clrotulo->label("k00_histtxt");
                      db_textarea('k00_histtxt', 5, 70, $Ik00_histtxt, true, 'text', 2);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>
                    <input type="button" name="processar" id="processar" 
                           value="Processar" onclick="js_processarDesconto()" />
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <?
            }
          ?>
        </table>
        
        <?
          db_input('z01_numcgm', 10, '', true, 'hidden', 3);
          db_input('j01_matric', 10, '', true, 'hidden', 3);
          db_input('q02_inscr' , 10, '', true, 'hidden', 3);
          db_input('inner'     , 10, '', true, 'hidden', 3);
          db_input('where'     , 10, '', true, 'hidden', 3);
        ?>
      </fieldset>
    </form>
  </center>
  
  <?
    db_menu(db_getsession("DB_id_usuario"),
    db_getsession("DB_modulo"),
    db_getsession("DB_anousu"),
    db_getsession("DB_instit"));
  ?>
</body>
</html>

<script>
  
  js_matri(false);
  
  function js_matri(mostra){
    
    var matri=$F('j01_matric');
    
    if (mostra == true) {
     
      js_OpenJanelaIframe('top.corpo', 'db_iframe3',
        'func_iptubase.php?funcao_js=parent.js_mostramatri|j01_matric|z01_nome', 'Pesquisa', true);
    } else {
     
     js_OpenJanelaIframe('top.corpo', 'db_iframe3', 'func_iptubase.php?pesquisa_chave=' +
        matri + '&funcao_js=parent.js_mostramatri1', 'Pesquisa', false);
    }
  }
  
  
  function js_mostramatri(chave1, chave2) {

    document.form1.j01_matric.value    = chave1;
    document.form1.z01_nomematri.value = chave2;
    
    db_iframe3.hide();
  }
  
  
  function js_mostramatri1(chave, erro) {
    
    document.form1.z01_nomematri.value = chave; 
    
    if (erro == true) { 
      
      document.form1.j01_matric.focus(); 
      document.form1.j01_matric.value = '';
    }
  }
  
  
  var sUrlRPC = 'agu4_descontomatricula.RPC.php';
  
  js_init_table();
  js_pesquisaDebitos();
  
  function js_init_table() {
    
    oGridDebitos              = new DBGrid('oGridDebitos');
    oGridDebitos.nameInstance = 'oGridDebitos';
    
    var sMsg                  = null;
    
    oGridDebitos.selectSingle = function (oCheckbox,sRow,oRow) {
    
      if (oRow.getClassName() == 'comMov') {
      
        oCheckbox.checked = false;
      }
      
      if (oCheckbox.checked) {
        
        js_verificaDescontoMaximo('marcado', oRow);
        oRow.isSelected   = true;
        $(sRow).className = 'marcado';
        oRow.isSelected   = true;
        
      } else {
        
        js_verificaDescontoMaximo('desmarcado', oRow);
        $(sRow).className = oRow.getClassName();
        oRow.isSelected   = false;
      }
    }
    
    if (sMsg != null) {
      alert(sMsg);
    }
    
    oGridDebitos.setHeight(150);
    oGridDebitos.setCheckbox(0);
    oGridDebitos.setCellAlign(new Array('center', 
                                        'center', 
                                        'center', 
                                        'right' , 
                                        'center',
                                        'center', 
                                        ''));
    
    oGridDebitos.setCellWidth(new Array('15%'   , 
                                        '10%'   , 
                                        '10%'   , 
                                        '10%'   , 
                                        '15%'   ,
                                        '40%'   ,
                                        ''));
      
    oGridDebitos.setHeader   (new Array('Numpre'   , 
                                        'Parcela'  , 
                                        'Receita'  , 
                                        'Valor'    , 
                                        'Data Venc',
                                        'Histórico',
                                        'Numpre/Numpar/receita(hidden)'));
    
    
      
    oGridDebitos.aHeaders[7].lDisplayed = false;
    
    oGridDebitos.show($('oGridDebitos'));
    
  }
  
  
  
  function js_verificaDescontoMaximo(sOperacao, oLinha) {
    
    switch (sOperacao) {
      case 'marcado':
        
        if (document.getElementById('k00_valor').value == 0) {
          
          document.getElementById('k00_valor').value = oLinha.aCells[4].getValue();
        }
        
        if (parseFloat(oLinha.aCells[4].getValue().replace(" ", ""))
          < parseFloat(document.getElementById('k00_valor').value))
        {
          
          document.getElementById('k00_valor').value = oLinha.aCells[4].getValue();
          document.getElementById('DBtxt8').value    = 0;
          document.getElementById('DBtxt9').value    = 0;
        }
      
      break;
      case 'desmarcado':
      
        var aDebitosSelecionados = oGridDebitos.getSelection();
        //vejo quem é o maior valor
        aDebitosSelecionados.each(
          function ( aRow ) {
            
            if (parseFloat(aRow[4].replace(" ", ""))
              > parseFloat(document.getElementById('k00_valor').value))
            {
              
              document.getElementById('k00_valor').value = aRow[4];
            }
          }
        );
        
        //vejo qual o menor valor para setar como desconto máximo
        if (oGridDebitos.getSelection().length > 1) {
          
          aDebitosSelecionados.each(
            function ( aRow ) {
             
              if (oLinha.aCells[2].getValue() != aRow[2]) {
              
                if (parseFloat(aRow[4].replace(" ", ""))
                  < parseFloat(document.getElementById('k00_valor').value))
                {
                  
                  document.getElementById('k00_valor').value = aRow[4];
                  document.getElementById('DBtxt8').value    = 0;
                  document.getElementById('DBtxt9').value    = 0;
                }
              
              }
            
              aDebitosSelecionados.push(oGridDebitos);
            }
          );
        } else {
          
          document.getElementById('k00_valor').value = 0; 
          document.getElementById('DBtxt8').value    = 0;
          document.getElementById('DBtxt9').value    = 0;
        }
        
      break;
      
      default:
    
        document.form1.k00_valor.value = 0;
        document.form1.DBtxt8.value    = 0;
        document.form1.DBtxt9.value    = 0;
    }
  }
  
  
  
  function js_pesquisaDebitos() {
    
    var oParam         = new Object();
    
    oParam.iMatricula     = $F('j01_matric');
    oParam.iTipoDebito    = $F('iTipo');
    oParam.iReceitaDebito = $F('iReceita');
    oParam.sExec          = 'getDebitosPorTipo';
    
    if (oParam.iMatricula == '') {
      
      alert('Nenhuma matrícula ou Tipo de Débito selecionado.');
      return false;
    }
    
    js_divCarregando('Pesquisando débitos, aguarde.', 'msgbox');
    
    var oAjax = new Ajax.Request(sUrlRPC,
                                { 
                                 method    : 'POST',
                                 parameters: 'json=' + Object.toJSON(oParam), 
                                 onComplete: js_retornaDebitos
                                });
  }
  
  
  
  function js_retornaDebitos(oAjax) {
    
    js_removeObj('msgbox');
    
    var oRetorno  = eval("(" + oAjax.responseText + ")");
    
    oGridDebitos.clearAll(true);
    
    if (oRetorno.status == 1) {
      
      $('grid').style.display = '';
      $('combo').style.display = '';
      
      
      for (var i = 0; i < oRetorno.aDebitos.length; i++) {
        
        with (oRetorno.aDebitos[i]) {
       
          aLinha     = new Array();
           
          aLinha[0]  = k00_numpre;
          aLinha[1]  = k00_numpar;
          
          if ($F('iReceita') == 0) {
            aLinha[2]  = '';
          } else {
            aLinha[2]  = k00_receit;
          }
          
          aLinha[3]  = k00_valor;
          aLinha[4]  = js_formatar(k00_dtvenc, 'd');
          aLinha[5]  = '';
          aLinha[6]  = '';
          
          aLinha[5] += '<select id="procdiver' + k00_numpre + '_' + k00_numpar + '" name="procdiver'
                       + k00_numpre + '_' + k00_numpar + '" style="width: 200px; font-size: 12px; margin: 3px;">'; 
          
          aLinha[5] += '<option value="">Selecione...</option>';
          
          for (var p = 0; p < oRetorno.aHistoricos.length; p++) {
            
            with (oRetorno.aHistoricos[p]) {
              
              if (k01_codigo == k00_hist) {
                aLinha[5] += '<option title="' + k01_descr + '" selected="" value="' + k01_codigo + '">' + k01_codigo
                  + ' - ' + k01_descr.urlDecode() + '</option>';
              } else { 
                aLinha[5] += '<option title="' + k01_descr + '" value="' + k01_codigo + '">' + k01_codigo
                  + ' - ' + k01_descr.urlDecode() + '</option>';
              }
              
            }
          }
          aLinha[5] += '</select>';
          
        }
        oGridDebitos.addRow(aLinha);
        
      }
      
      oGridDebitos.renderRows();
      
    } else {

      alert('Nenhum registro encontrado.');
    }
  }
  
  
  
  function js_passainfo(iTipo, iReceita) {
    
    if (iTipo != 0) {
      document.form1.iTipo.value    = iTipo;
      document.form1.iReceita.value = "";
    }
    
    
    document.form1.iReceita.value = iReceita;
    
    document.form1.submit();
  }
  
  
  
  function js_calcula() {

    var perce = new Number(document.form1.DBtxt8.value);
    var perce = new Number(document.form1.DBtxt8.value);
    var valor = new Number(document.form1.k00_valor.value);
    
    if (perce >= 100 ) {
      
      alert('Para cancelamento total de um debito, use a opção Cancelamento de débito \n na consulta geral financeira.');
      document.form1.DBtxt8.value = "";
      document.form1.DBtxt9.value = "";
      return false;
    }
    
    valor = valor * (perce / 100);
    document.form1.DBtxt9.value = valor.toFixed(2);
  }
  
  
  
  function js_calculavalor() {
    
    var valor = new Number(document.form1.DBtxt9.value);
    
    if (valor > document.form1.k00_valor.value) {
    
      alert('Valor maior que o permitido.');
      document.form1.DBtxt9.value = "";
      document.form1.DBtxt8.value = "";
      return false;
    }
    
    var valor1 = new Number(document.form1.k00_valor.value);
    var valor  = new Number(document.form1.DBtxt9.value);
    
    if (valor1.toFixed(2) <= valor.toFixed(2)) {
      
      alert("Valor do desconto igual ou maior que o valor máximo permitido, \n Para cancelamento total de um debito, use a opção Cancelamento de débito \n na consulta geral financeira.");
      document.form1.DBtxt9.value = "";
      document.form1.DBtxt8.value = "";
      return false;
    }
    
    perce =  (valor * 100) / valor1;
    document.form1.DBtxt8.value = perce.toFixed(2);
    
    var nValorCalculado = new Number(document.form1.DBtxt9.value);
    var nValorHistorico = new Number(document.form1.k00_valor.value);
    
    if (nValorCalculado >= nValorHistorico) {
      
      alert('Valor calculado para o desconto igual ao valor máximo permitido. \n Para cancelamento total de um debito, use a opção Cancelamento de débito \n na consulta geral financeira.');
      document.form1.DBtxt9.value = "";
      document.form1.DBtxt8.value = "";
      return false;
    }
  }
  
  
  
  function js_processarDesconto() {
      
    var aDebitosSelecionados = oGridDebitos.getSelection();
    var aNumpresSelecionados = new Array();
    var lErro                = false;
    var sMsgErro             = "Erro: \n";
      
    if (aDebitosSelecionados.length == 0) {
      
      alert('Nenhum débito selecionado.');
      return false;
    }
    
    if ((document.getElementById('DBtxt8').value == 0) ||
        (document.getElementById('DBtxt9').value == 0) ||
        (document.getElementById('k00_histtxt').value == ""))
    {
      
      alert('Preencha corretamente os campos para o efetuar o desconto.');
      return false;
    }
    
    aDebitosSelecionados.each(
      function ( aRow ) {
          
        var oDebito          = new Object();
        oDebito.iNumpre      = aRow[1];
        oDebito.iNumpar      = aRow[2];
        oDebito.iHistorico   = aRow[6];
        
        if (oDebito.iHistorico == "") {
         
         lErro     = true;
         sMsgErro += "- Nenhum histórico selecionado para o NUMPRE: " + oDebito.iNumpre + ","
                    + " PARCELA: " + oDebito.iNumpar + "\n";
         
        }
        
        aNumpresSelecionados.push(oDebito);
      }
    );
      
    if (lErro) {
      alert(sMsgErro);
       return false;
    }
    
    if(!confirm('Deseja Lançar desconto nos itens selecionados?')) {
      return false;
    }
    
    js_divCarregando('Processando Descontos, aguarde.', 'msgbox');
      
    var oParam          = new Object();
    oParam.iMatricula   = $F('j01_matric');
    oParam.sExec        = 'processaDescontos';
    oParam.aDebitos     = aDebitosSelecionados;
    oParam.fValorDesc   = document.getElementById('DBtxt9').value;
    oParam.fPercentDesc = document.getElementById('DBtxt8').value;
    oParam.sObs         = document.getElementById('k00_histtxt').value;
    
    var oAjax = new Ajax.Request(sUrlRPC,
                                { 
                                  method    : 'POST',
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: js_retornoProcessamento
                                });
  }
    
  function js_retornoProcessamento(oAjax) {
    
    js_removeObj('msgbox');
    
    var oRetorno  = eval("(" + oAjax.responseText + ")");
    
    if (oRetorno.status == 1) {
     
      alert('Desconto(s) Lançado(s) com sucesso!');
    } else {
      
      alert('Desconto não realizado!');
      
    }
    document.location.href = 'agu4_descontomatricula011.php';
  }

</script>