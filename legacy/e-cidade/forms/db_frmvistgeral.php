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

$cltipovistorias->rotulo->label();
$dbwhere = '';

$clrotulo = new rotulocampo;
$clrotulo->label("nome");
$clrotulo->label("q02_numcgm");
$clrotulo->label("z01_nome");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1">
     <?php

       if(!empty($processa)){
         db_criatermometro('termometro','Concluido...','blue',1);
       }
     ?>
     <div id="filtro" style="visibility: visible">
     <fieldset>
      <legend>Cálculo de Vistorias (Geral)</legend>
    <table border="0">
      <?php

        $sStyle = "";
        if (isset($oParfiscal->y32_calcvistanosanteriores) && $oParfiscal->y32_calcvistanosanteriores == 't'){
          $sStyle = "style = 'display:none'";
        }
      ?>
      <tr <?=$sStyle?> >
        <td nowrap title="<?=@$Tq02_inscr?>" align="right">
          <?php
            db_ancora($Lq02_inscr,"js_pesquisaq02_inscr(true);",$db_opcao);
          ?>
        </td>
        <td>
          <?php

            db_input('q02_inscr',10,$Iq02_inscr,true,'text',$db_opcao," onchange='js_pesquisaq02_inscr(false);'");
            db_input('z01_nome',35,$Iz01_nome,true,'text',3);
          ?>
        </td>
      </tr>

      <tr id="anoscalculo" style="display:none">

        <td align="right">
          <label for="anoini"><strong>Anos de cálculo:</strong></label>
        </td>
        <td>
        <?php

          $sSqlAnoCalculo = $clcissqn->sql_query_file(null,"q04_anousu","q04_anousu desc"," q04_anousu <= ".db_getsession('DB_anousu'));
          $rsAnosCalculo  = $clcissqn->sql_record($sSqlAnoCalculo);
          $aAnos          = array();
          for ($iIndice=0; $iIndice < $clcissqn->numrows; $iIndice++) {

            $oAnos = db_utils::fieldsMemory($rsAnosCalculo, $iIndice);
            $aAnos[$oAnos->q04_anousu] = $oAnos->q04_anousu;
          }

          db_select('anoini', $aAnos, true, 2,"");
          echo " <strong>à</strong> ";
          db_select('anofim', $aAnos, true, 2,"");
        ?>
       </td>
     </tr>

     <tr>
       <td>
         <label for="duplica"><strong>Gerar Vistoria já Calculada no Exercício:</strong></label>
       </td>
       <td>
       <?php

         $aTipoDuplica = array ("f" => "Não","t" => "Sim");
         db_select('duplica', $aTipoDuplica, true, 2,"");
       ?>
       </td>
     </tr>

     <tr>
       <td align='right'>
         <label for="tipooriem"><strong>Origem:</strong></label>
       </td>
       <td>
       <?php

         $aOpcoes = array ("1" => "Localização", "2" => "Sanitário");
         db_select('tipoorigem', $aOpcoes, true, 2,"style='width:270px'");
         if(!isset($tipoorigem) || $tipoorigem != ""){
           $origem = 0;
         }else{
           $origem = $tipoorigem;
         }
       ?>
      </td>
     </tr>

     <tr>
       <td align='right'>
         <label for="tipovist"><strong>Tipo de Vistoria:</strong></label>
       </td>
       <td>
        <?php

          $iCodDepto     = $_SESSION['DB_coddepto'];
          $sSqlTipovist  = "  select *              ";
          $sSqlTipovist .= "    from tipovistorias  ";
          $sSqlTipovist .= "   where y77_coddepto = ".$iCodDepto;
          $sSqlTipovist .= "order by y77_codtipo    ";

          $rsTipovist  = db_query($sSqlTipovist);
          $intTipovist = pg_numrows($rsTipovist);
          $aOpcoes = array ("0" => "Escolha o tipo de vistoria");
          for ($ivist=0;$ivist < $intTipovist; $ivist++){

            db_fieldsmemory($rsTipovist,$ivist);
            $aOpcoes[$y77_codtipo] = $y77_descricao;
          }

          db_select('tipovist', $aOpcoes, true, 2,"style='width:270px'");
          if(!isset($tipovist) || $tipovist != ""){
            $tipovist = 0;
          }
       ?>
      </td>
     </tr>

     <tr>
              <td align='right'>
                <label for="iExercicio"><strong>Exercício:</strong></label>
              </td>
              <td>
                <?php

                  /**
                   * Verificamos se há vencimentos cadastrados para o próximo exercício, para que possamos
                   * coloca-lo como opção para gerar vistorias.
                   */
                  $iExercicioSeguinte = db_getsession("DB_anousu") + 1;
                  $aOpcoesVencimento  = array($iExercicioSeguinte - 1 => $iExercicioSeguinte - 1 );

                  $sWhere         = "extract(year from q82_venc) = {$iExercicioSeguinte}";
                  $sSqlVencimento = $oDaoCadVenc->sql_query_file_exercicio_cissqn($sWhere);
                  $rsVencimento   = $oDaoCadVenc->sql_record($sSqlVencimento);

                  if ( !empty($rsVencimento) ) {
                    $aOpcoesVencimento[$iExercicioSeguinte] = $iExercicioSeguinte;
                  }

                  db_select('iExercicio', $aOpcoesVencimento, true, 2);
                ?>
              </td>
            </tr>

            <tr>
       <td colspan="2">
       <?php

         if(isset($codigos) && $codigos != ""){
           $dbwhere = " where q12_classe in ($codigos)";
         }

         /**
          * Todas as classes ativas
          */
         $sql  = " select q12_classe,                                  ";
         $sql .= "        q12_descr                                    ";
         $sql .= "   from clasativ                                     ";
         $sql .= "        inner join classe on q82_classe = q12_classe ";
         $sql .= "  group by q12_classe,                               ";
         $sql .= "           q12_descr                                 ";

         /**
          * Campos a virem marcados no componente
          */
         $sqlmarca = "select q12_classe, q12_descr
                        from clasativ
                             inner join classe on q82_classe=q12_classe
                             $dbwhere
                    group by q12_classe, q12_descr";
         $cliframe_seleciona->sql           = $sql;
         $cliframe_seleciona->sql_marca     = $sqlmarca;
         $cliframe_seleciona->campos        = "q12_classe, q12_descr";
         $cliframe_seleciona->legenda       = "Classes";
         $cliframe_seleciona->alignlegenda  = "left";
         $cliframe_seleciona->textocabec    = "darkblue";
         $cliframe_seleciona->textocorpo    = "black";
         $cliframe_seleciona->fundocabec    = "#aacccc";
         $cliframe_seleciona->fundocorpo    = "#ccddcc";
         $cliframe_seleciona->iframe_width  = "100%";
         $cliframe_seleciona->iframe_nome   = "classe";
         $cliframe_seleciona->chaves        = "q12_classe";
         $cliframe_seleciona->marcador      = true;
         $cliframe_seleciona->dbscript      = "onClick='parent.js_mandadados();'";
         $cliframe_seleciona->js_marcador   = "parent.js_mandadados();";
         $cliframe_seleciona->iframe_seleciona($db_opcao);
       ?>
       </td>
     </tr>
  </table>
</fieldset>

  <input name="proc"     type="button" value="Processar" onclick="js_valida()">
  <input name="codigos"  type="hidden" value="" onclick="">
  <input name="processa" type="hidden" value="">
  <input name="tipo"     type="hidden" value="">
  <input name="origem"   type="hidden" value="">
  <input name="procerro" type="hidden" value="">
  <input name="emiterel" type="hidden" value="">

  </div>
  </form>
</div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">

function js_mandadados(){

   dados   = '';
   virgula = '';
   for(i = 0;i < classe.document.form1.elements.length;i++){
      if(classe.document.form1.elements[i].type == "checkbox" &&  classe.document.form1.elements[i].checked){
         dados += virgula+classe.document.form1.elements[i].value;
         virgula = ',';
      }
    }
  document.form1.codigos.value = dados;
}

function js_valida(){

   var confirma;
   conf = 't';
   obj = document.form1;
   obj.processa.value = 't';
   obj.tipo.value = document.form1.tipovist.value;
   obj.origem.value = document.form1.tipoorigem.value;
   js_mandadados();
   if(document.form1.tipo.value == 0){
      alert("Selecione o tipo de vistoria !");
      return false;
      conf = 'f';
   }

   if(document.form1.codigos.value == '' && document.form1.q02_inscr.value == ''){

      alert("Selecione pelo menos uma classe para continuar !");
      conf = 'f';
   }else{
    if(conf=='t'){
      if(confirm('Tem certeza que deseja lançar vistoria para todas classes selecionadas?')==true){

        document.getElementById('filtro').style.visibility='hidden';
        document.form1.submit();
      }
    }
   }
}

function js_termo555(atual){

  atual = new Number(atual);
  document.getElementById('termometro').value = ' '+atual.toFixed(0)+'%'+' Concluido';
  document.getElementById('barra').size = atual;
  document.getElementById('barra').style.visibility = 'visible';
}

function termoold(qual,total,numrows,i){

  if(i==numrows-1){
    for (cont=qual;cont<=100;cont++){
      document.getElementById('termometro').value = ' '+cont+'%'+' Concluido';
      document.getElementById('barra').size += 1;
    }
  }else{
      document.getElementById('termometro').value = ' '+qual+'%'+' Concluido';
      document.getElementById('barra').size += 1;
  }
}

function js_pesquisaq02_inscr(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|z01_nome','Pesquisa',true);
  }else{

     if(document.form1.q02_inscr.value != ''){
        js_OpenJanelaIframe('','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}

function js_mostraissbase(chave,erro){

  document.form1.z01_nome.value = chave;
  if(erro==true){

    document.form1.q02_inscr.focus();
    document.form1.q02_inscr.value = '';
    var lDesahabilitar = false;
    document.getElementById("anoscalculo").style.display = 'none';
  }else{

    var lDesahabilitar = true;
    document.getElementById("anoscalculo").style.display = '';
  }

  var aColectionCheck = classe.document.getElementsByTagName("input");
  for (var i = 0; i < aColectionCheck.length; i++) {

    if( aColectionCheck[i].type == 'checkbox' ) {
      aColectionCheck[i].checked  = !lDesahabilitar;
      aColectionCheck[i].disabled = lDesahabilitar;
    }
  }
}

function js_mostraissbase1(chave1,chave2){

  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nome.value = chave2;

  var aColectionCheck = classe.document.getElementsByTagName("input");
  for (var i = 0; i < aColectionCheck.length; i++) {
    if( aColectionCheck[i].type == 'checkbox' ) {
      aColectionCheck[i].checked  = false;
      aColectionCheck[i].disabled = true;
    }
  }

  db_iframe_issbase.hide();
}
</script>