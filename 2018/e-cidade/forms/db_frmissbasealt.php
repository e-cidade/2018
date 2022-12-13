<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

//MODULO: issqn
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("z01_nomefanta");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_incest");
$clrotulo->label("z01_cep");
$clrotulo->label("z01_ident");
$clrotulo->label("z01_munic");
$clrotulo->label("q02_inscr");
$clrotulo->label("q02_numcgm");
$clrotulo->label("q30_quant");
$clrotulo->label("q30_anousu");
$clrotulo->label("q30_mult");
$clrotulo->label("j14_nome");
$clrotulo->label("j14_codigo");
$clrotulo->label("z01_ender");
$clrotulo->label("q02_compl");
$clrotulo->label("q02_numero");
$clrotulo->label("q02_cxpost");
$clrotulo->label("j13_codi");
$clrotulo->label("j13_descr");
$clrotulo->label("z01_bairro");
$clrotulo->label("q14_proces");
$clrotulo->label("q02_inscmu");
$clrotulo->label("q10_numcgm");
$clrotulo->label("q02_memo");
$clrotulo->label("q05_matric");
$clrotulo->label("q05_idcons");
$clrotulo->label("q02_regjuc");
$clrotulo->label("q02_dtcada");
$clrotulo->label("p58_requer");
$clrotulo->label("q40_codporte");
$clrotulo->label("q45_codporte");
$clrotulo->label("q45_descr");
$clrotulo->label("q40_descr");
$clrotulo->label("q02_capit");
$clrotulo->label("q02_obs");
$clrotulo->label("q35_zona");
$clrotulo->label("q35_area");
$clrotulo->label("j50_descr");
$clrotulo->label("y80_codsani");
$clrotulo->label("q02_dtjunta");
$clrotulo->label("q02_dtcada");
$tam_cgccpf=0;
if ($db_opcao==1) {
  $acao="iss1_issbase014.php";
} else if($db_opcao==2 || $db_opcao==22) {

  $db_opcao=2;
  $acao="iss1_issbase015.php";
} else if($db_opcao==3 || $db_opcao==33) {
  $acao="iss1_issbase016.php";
}

$z01_nome_escrito = "";

global $cepIssRuas;
if(@$q02_inscr!="") {

  $sSqlIss  = " select issbase.*, issruas.z01_cep as cepissruas, escrito.q10_numcgm, cgm.z01_nome as z01_nome_escrito ";
  $sSqlIss .= "   from issbase " ;
  $sSqlIss .= "  inner join issruas on issruas.q02_inscr = issbase.q02_inscr " ;
  $sSqlIss .= "   left join escrito on q10_inscr = issbase.q02_inscr " ;
  $sSqlIss .= "   left join cgm on z01_numcgm  = q10_numcgm ";
  $sSqlIss .= "   where issbase.q02_inscr = {$q02_inscr} ";

  $rsIss      = db_query($sSqlIss);
  $iLinhasIss = pg_num_rows($rsIss);
  if ($iLinhasIss > 0){
    db_fieldsmemory($rsIss, 0);

    $cepIssRuas = $cepissruas;
  }
}

?>
<form name="form1" method="post" action="<?=$acao?>">
<input type="hidden" value="" name="atualiza_matric">

<script>

  function js_trocaid(obj) {

   	str = document.getElementById('idcar_' + obj).value;
   	matriz = str.split('XabX');
   	document.form1.j14_codigo.value = matriz[0];
   	document.form1.j14_nome.value = matriz[1];
   	document.form1.q02_numero.value = matriz[2];
   	document.form1.q02_compl.value = matriz[3];
   	document.form1.j13_codi.value = matriz[4];
   	document.form1.j13_descr.value = matriz[5];
   	document.form1.q30_area.value = matriz[6];
  }
</script>

<fieldset>
  <legend>Alvará</legend>

  <!--
  ********************************************************************************************************************
  ********************************************** CONTAINER DADOS DO CGM **********************************************
  ********************************************************************************************************************
  -->
  <fieldset class='separator'>
    <Legend><strong>Dados do CGM</strong></Legend>
    <table border="0" width="100%"  >
      <tr>
        <td nowrap title="<?=@$Tq02_inscr?>" width="150px">
          <?=@$Lq02_inscr?>
        </td>
        <td colspan="2">
          <?php
            db_input('testasub',4,0,true,'hidden',1);
            db_input('q30_anousu',4,0,true,'hidden',1);
            db_input('q02_inscr',10,$Iq02_inscr,true,'text',3);
            $z01_nome = stripslashes($z01_nome);
          ?>
        </td>
      <tr>
      <tr>
        <td nowrap title="<?=$z01_nome?>" >
           <strong>Número do CGM:</strong>
        </td>
        <td colspan="2">
          <?php
            db_input('q02_numcgm',10,$Iq02_numcgm,true,'text',3);
            db_input('z01_nome',53,$Iz01_nome,true,'text',3,'',null,null,"padding-left:2px;");
          ?>
          </td>
      </tr>

      <tr>
        <td nowrap title="<?=$z01_nomefanta?>" >
          <?=@$Lz01_nomefanta?>
        </td>
        <td colspan="2">
          <?php
            db_input('z01_nomefanta', 67, $Iz01_nomefanta, true, 'text', $db_opcao, '');
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tz01_ender?>">
          <?=@$Lz01_ender?>
        </td>
        <td>
          <?
            db_input('z01_ender',30,$Iz01_ender,true,'text',3);
          ?>
        </td>
        <td align="right">
          <?=@$Lz01_cep?>
          <?php
            db_input('z01_cep', 8,$Iz01_cep,true,'text',3, 'onchange="validaApenasNumeros(\'z01_cep\');"', null, null, null, 8);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tz01_munic?>">
          <?=@$Lz01_munic?>
        </td>
        <td >
          <?
            db_input('z01_munic',30,$Iz01_munic,true,'text',3);
          ?>
        </td>
        <td align="right">
          <?=@$Lz01_cgccpf;
          db_input('z01_cgccpf',18,$Iz01_cgccpf,true,'text',3);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>

  <!--
  ********************************************************************************************************************
  ********************************* CONTAINER ENDEREÇO NO MUNICÍPIO DADOS ********************************************
  ********************************************************************************************************************
  -->

  <fieldset class='separator'>
    <Legend><strong>Endereço no Município</strong></Legend>
    <table border="0" width="100%" >
      <?php
      if($db_opcao==33 || $db_opcao==22 || strtoupper($munic)==strtoupper($z01_munic)){
      ?>
       <tr>
         <td nowrap title="<?=@$Tq05_matric?>" width="150px">  <strong>
           <?
             $Lq05_matric = "Matrícula";
             db_ancora($Lq05_matric,"js_matri(true);",$db_opcao);
           ?>
         </strong>
         </td>
         <td colspan="2">
           <?php
             db_input('q05_matric',10,$Iq05_matric,true,'text',$db_opcao,"onchange='js_matri(false)'");
             db_input('z01_nome',53,0,true,'text',3,"","z01_nome_matric","#E6E4F1");
           ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Tq05_idcons?>">
           <?=$Lq05_idcons?>
         </td>
         <td colspan="2">
           <?php
             if (isset($q05_matric) && $q05_matric!="") {

               $result04  = $cliptuconstr->sql_record($cliptuconstr->sql_query($q05_matric,"","j39_idcons as idcons,j39_codigo,j88_sigla || ' ' || j14_nome as nomerua,j39_numero,j39_compl,j34_bairro,j39_area","j39_idcons"));
               $xxx       =  array();
               $numrows04 = $cliptuconstr->numrows;
               if ($numrows04 > 0) {

                 for ($i = 0; $i < $numrows04; $i++) {

                   db_fieldsmemory($result04,$i);
                   $xxx[$idcons] = $idcons;
                   $wx           = "idcar_".$idcons;
                   if ($j34_bairro!="" && $j34_bairro!=0) {

                     $result29 = $clbairro->sql_record($clbairro->sql_query_file($j34_bairro,"j13_descr"));
                     db_fieldsmemory($result29,0);
                     $$wx = $j39_codigo."XabX".$nomerua."XabX".$j39_numero."XabX".$j39_compl."XabX".$j34_bairro."XabX".$j13_descr."XabX".$j39_area;
                   } else {
                     $$wx=$j39_codigo."XabX".$nomerua."XabX".$j39_numero."XabX".$j39_compl."XabXXabXXabX".$j39_area;
                   }
                   db_input('idcar_'.$idcons,20,0,true,'hidden',1);
                 }
                 if (!isset($chavepesquisa)) {

                   db_fieldsmemory($result04,0);
                   $q05_idcons=$idcons;
                 }
               } else {

                 $str_matric=false;
                 unset($q05_idcons);
               }
             } else {
               unset($q05_idcons);
             }
             if (isset($q05_matric) && $q05_matric!="" && $numrows04>1) {
               db_select('q05_idcons',$xxx,true,$db_opcao,"onchange='js_trocaid(this.value);'");
             } else {

               if(empty($numrows04) && (isset($numrows04) && $numrows04!=1) ){
                 $q05_idcons="";
               }
               db_input('q05_idcons',10,$Iq05_idcons,true,'text',3);
             }
           ?>
         </td>
       </tr>

     <?php
       }
     ?>
     <tr>
       <?php

         $result02 = $cldb_cgmruas->sql_record($cldb_cgmruas->sql_query($q02_numcgm,"ruas.j14_codigo,ruas.j14_nome"));
         $cgmnops  = "true";

         if ($cldb_cgmruas->numrows>0 && empty($j14_codigo)) {
           db_fieldsmemory($result02,0);
         }
         if (isset($q05_matric) && $q05_matric!="" ) {

           echo "<td nowrap title='$Tj14_codigo' width='150px'>";
           echo $Lj14_codigo;
           echo "</td>";
           echo "<td colspan='2'>";
           db_input('j14_codigo',10,$Ij14_codigo,true,'text',3,'',"","#E6E4F1");
         } else {

           echo "<td nowrap title='$Tj14_codigo' width='150px'>";
           db_ancora($Lj14_codigo,'js_pesquisaj14_codigo(true); ',$db_opcao);
           echo "</td>";
           echo "<td colspan='2'>";
           db_input('j14_codigo',10,$Ij14_codigo,true,'text',$db_opcao," onchange='js_pesquisaj14_codigo(false);'",'',"#E6E4F1");
         }
         db_input('j14_nome',53,$Ij14_nome,true,'text',3);
         echo "</td>";
         if ($db_opcao!=33 && $db_opcao!=22 && strtoupper($munic)==strtoupper($z01_munic)) {
         } else {
           $cgmnops="false";
         }
       ?>
     </tr>
     <tr>
       <td nowrap title="<?=@$Tq02_numero?>">
          <strong>Número:</strong>
       </td>
       <td nowrap >
         <?php
           if ($db_opcao!=33 && $db_opcao!=22 && strtoupper($munic)!=strtoupper($z01_munic)) {
             db_input('q02_numero',10,$Iq02_numero,true,'text',1)
           ?>
             </td>
             <td align="right" style="padding-right:5px;">
               <?=@$Lq02_compl?>
         <?php
                 db_input('q02_compl',18,$Iq02_compl,true,'text',1,"","", "", "",20);
           } else {

             if($db_opcao!=1 && $db_opcao!=33 && $db_opcao!=22 && strtoupper($munic)==strtoupper($z01_munic)) {

               $result_issruas=$clissruas->sql_record($clissruas->sql_query_file($q02_inscr,"q02_numero, q02_compl"));
               if ($clissruas->numrows > 0) {
                 db_fieldsmemory($result_issruas,0);
               } else {
                 $q02_numero = @$z01_numero;
                 $q02_compl  = @$z01_compl;
               }
             }
             db_input('q02_numero',10,@$Iq02_numero,true,'text',$db_opcao);
         ?>
           </td>
           <td align="right" style="padding-right:5px;">
             <?=@$Lq02_compl?>
             <?php
               db_input('q02_compl',18,$Iq02_compl,true,'text',$db_opcao,"","","","",20);
           }
             ?>
           </td>
     </tr>

     <tr>
   <?php
     if (!isset($chavepesquisa)) {

       if ($db_opcao == 1) {

         $result03=$cldb_cgmbairro->sql_record($cldb_cgmbairro->sql_query_file($q02_numcgm," * "));
         if ($cldb_cgmbairro->numrows>0) {

           db_fieldsmemory($result03,0);
           $result53 = $clbairro->sql_record($clbairro->sql_query_file($j13_codi));
           db_fieldsmemory($result53,0);
         }
       } else {
         $rsConsultaBairro = $clissbairro->sql_record($clissbairro->sql_query($q02_inscr,"j13_codi,j13_descr",null,""));
         if ($clissbairro->numrows > 0){
           db_fieldsmemory($rsConsultaBairro,0);
         }
       }
     }
     if (isset($q05_matric) && $q05_matric!="" ) {
       echo "<td nowrap title='$Tj13_codi'>";
       echo $Lj13_codi;
       echo "</td>";
       echo "<td colspan='2'>";
       db_input('j13_codi',10,$Ij13_codi,true,'text',3);
       db_input('j13_descr',53,$Ij13_descr,true,'text',3);
     } else {
       echo "<td nowrap title='$Tj13_codi'>";
       db_ancora($Lj13_codi,'js_bairro(true); ',$db_opcao);
       echo "</td>";
       echo "<td colspan='2'>";
       db_input('j13_codi',10,$Ij13_codi,true,'text',$db_opcao," onchange='js_bairro(false);'","","E6E4F1");
       db_input('j13_descr',53,$Ij13_descr,true,'text',3,"","","E6E4F1");
      }
      if ($cgmnops=="true") {
      } else {
      }
   ?>
       </td>
     </tr>
     <tr>
       <td><?=@$Lq02_cxpost?></td>
       <td ><?
           db_input('q02_cxpost',10,$Iq02_cxpost,true,'text',1);
           ?>
       </td>
       <td class="bold" style="text-align: right; padding-right:5px;">
         CEP:
         <?php
           db_input('cepIssRuas', 8, false, false, 'text', $db_opcao, 'onKeydown="return validaApenasNumeros(\'cepIssRuas\')"', null, null, null, 8);
         ?>
       </td>
     </tr>
   </table>
  </fieldset>

  <!--
  ********************************************************************************************************************
  **************************************** CONTAINER OUTROS DADOS ****************************************************
  ********************************************************************************************************************
  -->
  <fieldset class ='separator'>
    <Legend align="left"><strong>Outros Dados</strong></Legend>
    <table border="0" width="100%" >
      <tr>
        <td nowrap title="<?=@$Tq10_numcgm?>" width="150px">
          <?php
            db_ancora(@$Lq10_numcgm,"js_pesquisaq10_numcgm(true);",$db_opcao);
          ?>
        </td>
        <td nowrap title="<?=@$Tq14_proces?>" colspan="2">
          <?php
          db_input('q10_numcgm',10,$Iq10_numcgm,true,'text',$db_opcao," onchange='js_pesquisaq10_numcgm(false);'");
          db_input('z01_nome_escrito',53,$z01_nome_escrito,true,'text',3,'','z01_nome_escrito',"E6E4F1");
          ?>
        <td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tq40_codporte?>" >
          <?php
            $tam_cgccpf=strlen($z01_cgccpf);
            if ($tam_cgccpf == 14) {

              db_ancora(@$Lq40_codporte,"js_pesquisa_porte(true,'j');",$db_opcao);
              echo "</td>";
              echo "<td colspan='2'>";
              db_input('q45_codporte',10,$Iq45_codporte,true,'text',$db_opcao,"onchange=js_pesquisa_porte(false,'j');");
            } else {

              db_ancora(@$Lq40_codporte,"js_pesquisa_porte(true,'f');",$db_opcao);
              echo "</td>";
              echo "<td colspan='2'>";
              db_input('q45_codporte',10,$Iq45_codporte,true,'text',$db_opcao,"onchange=js_pesquisa_porte(false,'f');");
            }
            db_input('q40_descr',53,$Iq40_descr,true,'text',3);
            echo "</td>
          </tr>";
          $tam_cgccpf=strlen($z01_cgccpf);

          if ($tam_cgccpf==14) {

          ?>
      <tr>
        <td>
          <?=@$Lq02_capit?>
        </td>
        <td colspan="2">
          <?php
            $sql = $clsocios->sql_query_socios($q02_numcgm,"","sum(q95_perc) as somaval ");
            $result_testaval=db_query($sql);
            if (pg_numrows($result_testaval)!=0){
              db_fieldsmemory($result_testaval,0);


            }else $somaval=0;
            $somaval=db_formatar($somaval,'f');
            $q02_capit=$somaval;
            db_input('q02_capit',10,$Iq02_capit,true,'text',3);
          ?>
        </td>
      </tr>

      <?php

        }else $q02_capit="0";
      ?>

      <tr>
        <td nowrap title="<?=@$Tq35_zona?>">
          <?php
            db_ancora(@$Lq35_zona,"js_pesquisaq35_zona(true);",$db_opcao);
          ?>
        </td>
        <td colspan="2">
          <?php
            db_input('q35_zona',10,$Iq35_zona,true,'text',$db_opcao," onchange='js_pesquisaq35_zona(false);'");
            db_input('j50_descr',53,$Ij50_descr,true,'text',3,'');
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tq30_quant?>">
          <?=@$Lq30_quant?>
        </td>
        <td >
          <?php
            if (!isset($q30_quant) && $db_opcao!=33 && $db_opcao!=3 && $db_opcao!=2 && $db_opcao!=22) {
               $q30_quant='1';
            } else if(isset($q05_matric) && $q05_matric!="" && isset($j39_area) ) {
               $q30_area=$j39_area;
            }

            !isset($q30_mult)&&$db_opcao!=33&&$db_opcao!=3&&$db_opcao!=2&&$db_opcao!=22?$q30_mult='1':"";
            db_input('q30_quant',10,$Iq30_quant,true,'text',$db_opcao,"")
          ?>
        </td>
        <td align="right">
          <?=@$Lq30_mult?>
          <?php
            db_input('q30_mult',18,$Iq30_mult,true,'text',$db_opcao,"");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=@$Tq30_area?>">
          <strong>Área:</strong>
        </td>
        <td colspan="2">
          <?php
            db_input('q30_area',10,$Iq30_area,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>


      <!--  -->
      <tr>
        <td nowrap title="<?=@$Tq14_proces?>"><strong>
          <?php
            db_ancora("Processo:","js_pesquisaq14_proces(true);",$db_opcao);
          ?>
        </strong>
        </td>
        <td colspan="2">
          <?php
            db_input('q14_proces',10,$Iq14_proces,true,'text',$db_opcao," onchange='js_pesquisaq14_proces(false);'");
            db_input('p58_requer',53,$Ip58_requer,true,'text',3,'');
          ?>
        <td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Tq02_inscmu?>">
          <strong>Referencia anterior:</strong>
        </td>
        <td colspan="2">
          <?php
            db_input('q02_inscmu',10,$Iq02_inscmu,true,'text',$db_opcao,"");
          ?>
        <td>
      </tr>

      <?php
        if ($tam_cgccpf==14) {
          $sStyle = '';
        }else{
          $sStyle = 'display:none';
        }
      ?>
      <tr style='<?=$sStyle?>'>
        <td nowrap title="<?=@$Tq02_dtjunta?>" valign="top">
          <?=$Lq02_dtjunta?>
        </td>
        <td >
          <?php
            db_inputdata('q02_dtjunta', @$q02_dtjunta_dia, @$q02_dtjunta_mes,@$q02_dtjunta_ano,true,'text', $db_opcao);
            echo "</td>
                  <td align='right'>";
            $tam_cgccpf = strlen($z01_cgccpf);
            if ($tam_cgccpf==14) {

          ?>
          <?=$Lq02_regjuc?>
          <?php
              db_input('q02_regjuc',18,$Iq02_regjuc,true,'text',$db_opcao,"");
            }else{
              $q02_regjuc="";
              db_input('q02_regjuc',18,$Iq02_regjuc,true,'hidden',3,"");
            }
          ?>
        </td>
      </tr>
      <tr style=''>
        <td nowrap title="<?=@$Tq02_dtcada?>" valign="top">
          <?=$Lq02_dtcada?>
        </td>
        <td >
          <?php
            if (!isset($q02_dtcada) || $q02_dtcada == '') {
              @list($q02_dtcada_ano, $q02_dtcada_mes, $q02_dtcada_dia) = @explode('-',date('Y-m-d',db_getsession('DB_datausu')));
            }else{
              @list($q02_dtcada_ano, $q02_dtcada_mes, $q02_dtcada_dia) = @explode('-',$q02_dtcada);
            }
            db_inputdata('q02_dtcada', @$q02_dtcada_dia, @$q02_dtcada_mes,@$q02_dtcada_ano,true,'text', $db_opcao);
          ?>
        </td>
      </tr>

      <tr>
        <td nowrap title="<?=@$Ty80_codsani?>" valign="top">
          <?php
            db_ancora(@$Ly80_codsani,"js_pesquisay80_codsani(true);",$db_opcao);
          ?>
        </td>
        <td colspan="2">
          <?php
            if (isset($z01_nome)) {
              $z01_nome = stripslashes($z01_nome);
            }
            db_input('y80_codsani',10,$Iy80_codsani,true,'text',$db_opcao," onchange='js_pesquisay80_codsani(false);'");
            db_input('z01_nome1',53,$Iz01_nome,true,'text',3,'')
          ?>
        </td>
      </tr>
    </table>
  </fieldset>

</fieldset>

<?php
if ($db_opcao==22) {
  $db_botao=false;
}
?>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2 || $db_opcao==22?"alterar":"excluir"))?>"
       type="<?=($db_opcao==1?"button":($db_opcao==2  || $db_opcao==22?"button":"submit"))?>"
       id="db_opcao"
       value="<?=($db_opcao==1?"Incluir":($db_opcao==2 || $db_opcao==22?"Alterar":"Excluir"))?>"
       <?=($db_botao==false?"disabled":"")?>
       <?=$db_opcao==1?"onclick='js_testaproc(1);'":($db_opcao==2?"onclick='js_testaproc(2);'":"") ?> >

<input name="voltar" type="button" id="voltar" value="Voltar" onclick="js_voltar();" >
</form>
<script>

document.form1.j14_codigo.style.backgroundColor = "#DEB887";
document.form1.q02_numero.style.backgroundColor = "#DEB887";
document.form1.j13_codi.style.backgroundColor   = "#DEB887";
document.form1.j13_descr.style.backgroundColor  = "#DEB887";

if (<?php echo $db_opcao;?> == 2 || <?php echo $db_opcao;?> == 1) {

  document.form1.j14_codigo.style.backgroundColor = "#FFF";
  document.form1.q02_numero.style.backgroundColor = "#FFF";
  document.form1.j13_codi.style.backgroundColor   = "#FFF";
  document.form1.j13_descr.style.backgroundColor  = "#DEB887";
}

function js_pesquisaq35_zona(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframezonas','func_zonas.php?funcao_js=parent.js_mostrazonas1|j50_zona|j50_descr','Pesquisa',true);
  } else {
    if (document.form1.q35_zona.value != '') {
      js_OpenJanelaIframe('','db_iframezonas','func_zonas.php?pesquisa_chave='+document.form1.q35_zona.value+'&funcao_js=parent.js_mostrazonas','Pesquisa',false);
    } else {
      document.form1.j50_descr.value = '';
    }
  }
}
function js_mostrazonas(chave,erro) {

  document.form1.j50_descr.value = chave;
  if (erro==true) {
    document.form1.q35_zona.focus();
    document.form1.q35_zona.value = '';
  }
}

function js_mostrazonas1(chave1,chave2) {

  document.form1.q35_zona.value = chave1;
  document.form1.j50_descr.value = chave2;
  db_iframezonas.hide();
}

/**
 * @todo
 */
function validaApenasNumeros( sCampo ){

  var oRegex = /^[0-9]+$/;

   if ( $F(sCampo) != "" && !oRegex.test( $F(sCampo) ) ) {

     alert( 'Campo CEP deve ser preenchido apenas com números' );
     document.form1.cepIssRuas.value = '';
     return false;
   }
}

function js_testaproc(opc) {

  var oRegex = /^[0-9]+$/;

  if (opc==1) {

    if (document.form1.j14_codigo.value=="") {

      alert("Campo Código do Logradouro é de preenchimento obrigatório.");
      document.form1.j14_codigo.focus();
    } else if (document.form1.j13_codi.value=="") {

      alert("Campo Bairro é de preenchimento obrigatório.");
      document.form1.j13_codi.focus();
    } else if(document.form1.cepIssRuas.value == "" || !oRegex.test( $F('cepIssRuas') )) {

      document.form1.cepIssRuas.value = '';
      alert("Campo Cep é de preenchimento obrigatório.");
      document.form1.cepIssRuas.focus();
    } else if(document.form1.cepIssRuas.value != "" && $F('cepIssRuas').length < 8) {

      alert("Campo Cep deve possuir no mínimo oito (8) digitos.");
      document.form1.cepIssRuas.focus();
    }else if (document.form1.q14_proces.value=="") {

      alert("Campo Código do processo é de preenchimento obrigatório.");
      document.form1.q14_proces.focus();
    } else if (document.form1.q45_codporte.value=="") {

      alert("Campo Porte é de preenchimento obrigatório.");
      document.form1.q45_codporte.focus();
    } else {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe','iss4_testaender.php?rua='+document.form1.j14_codigo.value+'&numero='+document.form1.q02_numero.value+'&compl='+document.form1.q02_compl.value+'&bairro='+document.form1.j13_codi.value+'&inscr='+document.form1.q02_inscr.value+'&funcao_js=parent.js_submit','Pesquisa',false,0);
    }
  } else if (opc==2) {

    if(document.form1.cepIssRuas.value == ""|| !oRegex.test( $F('cepIssRuas') )) {

      document.form1.cepIssRuas.value = '';
      alert("Campo Cep é de preenchimento obrigatório.");
      document.form1.cepIssRuas.focus();
    } else if(document.form1.cepIssRuas.value != "" && $F('cepIssRuas').length < 8) {

      alert("Campo Cep deve possuir no mínimo oito (8) digitos.");
      document.form1.cepIssRuas.focus();
    }else {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe','iss4_testaender.php?rua='+document.form1.j14_codigo.value+'&numero='+document.form1.q02_numero.value+'&compl='+document.form1.q02_compl.value+'&bairro='+document.form1.j13_codi.value+'&inscr='+document.form1.q02_inscr.value+'&funcao_js=parent.js_submit','Pesquisa',false,0);
    }
  }

}
function js_submit(retorno) {

  var inp = document.createElement("INPUT");
  inp.setAttribute("type","hidden");
  <?if ($db_opcao==1){
  	echo "
  inp.setAttribute('name','incluir');
  inp.setAttribute('value','Incluir');
  tipo = 'incluir'
					";
  }else if ($db_opcao==2){
  	echo "
  inp.setAttribute('name','alterar');
  inp.setAttribute('value','Alterar');
  tipo = 'alterar'
					";
  }?>
  document.form1.appendChild(inp);
  if (retorno=='f'){
    document.form1.submit();
  }else if (retorno=='t'){
    if(confirm('Já existe Inscrição para endereço selecionado! Deseja '+tipo+' assim mesmo?')){
      document.form1.submit();
	  }
  }
}

function js_voltar() {

  <?php
  if ($db_opcao==1) {
    echo "parent.location.href='iss1_issbase004.php';";
  } else if ($db_opcao==2 || $db_opcao==22) {
    echo "parent.location.href='iss1_issbase005.php';";
  } else {
    echo "parent.location.href='iss1_issbase006.php';";
  }
  ?>
}
<?
if (!isset($chavepesquisa) && isset($q05_idcons) && $q05_idcons!="" && 	$atualiza_matric == "true") {
     echo "js_trocaid($q05_idcons);\n";
}
?>

function js_matri(mostra) {

  matric=document.form1.q05_matric.value;
  if (mostra==true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe1','func_iptubasealt.php?funcao_js=parent.js_mostramatric|0|1|j39_idcons','Pesquisa',true,0);
  } else {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe1','func_iptubasealt.php?pesquisa_chave='+matric+'&funcao_js=parent.js_mostramatric1','Pesquisa',false,0);
  }
}
function js_mostramatric(chave1,chave2,chave3) {

  document.form1.q05_matric.value = chave1;
  document.form1.z01_nome_matric.value = chave2;
  document.form1.q05_idcons.value = chave3;
  document.form1.testasub.value = "ok";
  document.form1.atualiza_matric.value = "true";
  document.form1.submit();
  db_iframe1.hide();
}

function js_mostramatric1(chave,erro) {

  document.form1.z01_nome_matric.value = chave;
  if (erro==true) {
    document.form1.q05_matric.focus();
    document.form1.q05_matric.value = '';
  } else {
	  document.form1.atualiza_matric.value = "true";
    document.form1.testasub.value = "ok";
    document.form1.submit();
  }
}

function js_pesquisaq10_numcgm(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe','func_cadescritoalt.php?funcao_js=parent.js_mostraescrito|0|1','Pesquisa',true,0);
  } else {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe','func_cadescritoalt.php?pesquisa_chave='+document.form1.q10_numcgm.value+'&funcao_js=parent.js_mostraescrito1','Pesquisa',false,0);
  }
}

function js_mostraescrito(chave1,chave2) {

  document.form1.q10_numcgm.value = chave1;
  document.form1.z01_nome_escrito.value = chave2;
  db_iframe.hide();
}
function js_mostraescrito1(chave,erro) {

  document.form1.z01_nome_escrito.value = chave;
  if (erro==true) {
    document.form1.q10_numcgm.focus();
    document.form1.q10_numcgm.value = '';
  }
}
function js_bairro(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe_bairro','func_bairro.php?funcao_js=parent.js_mostrabairro1|0|1','Pesquisa',true,0);
  } else {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe_bairro','func_bairro.php?pesquisa_chave='+document.form1.j13_codi.value+'&funcao_js=parent.js_mostrabairro','Pesquisa',false,0);
  }
}
function js_mostrabairro(chave,erro) {

  document.form1.j13_descr.value = chave;
  if (erro==true) {
    document.form1.j13_codi.focus();
    document.form1.j13_codi.value = '';
  }
}

function js_mostrabairro1(chave1,chave2) {

  document.form1.j13_codi.value = chave1;
  document.form1.j13_descr.value = chave2;
  db_iframe_bairro.hide();
}

function js_pesquisaj14_codigo(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe_ruas','func_ruas.php?rural=1&funcao_js=parent.js_mostraruas1|0|1','Pesquisa',true,0);
  } else {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe_ruas','func_ruas.php?rural=1&pesquisa_chave='+document.form1.j14_codigo.value+'&funcao_js=parent.js_mostraruas','Pesquisa',false,0);
  }
}

function js_mostraruas1(chave1,chave2) {

  document.form1.j14_codigo.value = chave1;
  document.form1.j14_nome.value = chave2;
  db_iframe_ruas.hide();
}

function js_mostraruas(chave,erro) {

  document.form1.j14_nome.value = chave;
  if (erro==true) {
    document.form1.j14_codigo.focus();
    document.form1.j14_codigo.value = '';
  }
}

function js_pesquisaq14_proces(mostra) {
 
  if (mostra==true) {

    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe_proc','func_protprocesso.php?funcao_js=parent.js_mostraprocesso1|p58_codproc|p58_requer','Pesquisa',true);
  } else {
    if (document.form1.q14_proces.value != '') {

      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe_proc','func_protprocesso.php?pesquisa_chave='+document.form1.q14_proces.value+'&funcao_js=parent.js_mostraprocesso','Pesquisa',false);
    } else {
      document.form1.q14_proces.value = '';
    }
  }
}


function js_pesquisay80_codsani(mostra) {

  if (mostra==true) {

    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe_sanitario','func_sanitario.php?funcao_js=parent.js_mostrasanitario|y80_codsani|z01_nome &tp=iss','Pesquisa',true);
  } else {
    if (document.form1.y80_codsani.value != '') {

      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe_sanitario','func_sanitario.php?pesquisa_chave='+document.form1.y80_codsani.value+'&funcao_js=parent.js_mostrasanitario&tp=iss','Pesquisa',false);
    } else {

      document.form1.y80_codsani.value = '';
    }
  }
}

  function js_mostrasanitario(chave1,chave2) {

    document.form1.y80_codsani.value = chave1;
	  document.form1.z01_nome1.value    = chave2;
    db_iframe_sanitario.hide();
  }


function js_mostraprocesso(chave1,chave,erro) {

  document.form1.p58_requer.value = chave;
  if (erro==true) {
    document.form1.q14_proces.focus();
    document.form1.q14_proces.value = '';
  }
}
  function js_mostraprocesso1(chave1,chave2) {

    document.form1.q14_proces.value = chave1;
    document.form1.p58_requer.value = chave2;
    db_iframe_proc.hide();
  }

  function js_pesquisa_porte(mostra,pessoa) {

    if (mostra==true) {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe_issporte','func_issporte.php?pessoa='+pessoa+'&funcao_js=parent.js_mostraporte1|q40_codporte|q40_descr','Pesquisa',true);
    } else {
      if (document.form1.q45_codporte.value != '') {
        js_OpenJanelaIframe('CurrentWindow.corpo.iframe_issbase','db_iframe_issporte','func_issporte.php?pessoa='+pessoa+'&pesquisa_chave='+document.form1.q45_codporte.value+'&funcao_js=parent.js_mostraporte','Pesquisa',false);
      } else {
        document.form1.q45_codporte.value = '';
      }
    }
  }

  function js_mostraporte(chave,erro)  {

    document.form1.q40_descr.value = chave;
    if (erro==true) {
      document.form1.q45_codporte.focus();
      document.form1.q45_codporte.value = '';
    }
  }
  function js_mostraporte1(chave1,chave2) {

    document.form1.q45_codporte.value = chave1;
    document.form1.q40_descr.value = chave2;
    db_iframe_issporte.hide();
  }

  <?
  if (isset($testasub)) {
    echo "document.form1.testasub.value='';
    \n";
  }
  if (!isset($excluir) && !isset($alterar) && !isset($incluir)) {
    if ($db_opcao==1 && strtoupper($munic)!=strtoupper($z01_munic) && empty($q05_matric)) {
      echo "alert('CGM de outra cidade.');";
    }
    if (isset($str_matric) && $str_matric==false) {
      echo "alert('Matricula não é predial, portanto não poderá ser usada.');\n";
    }
    if (($db_opcao==22 || $db_opcao==33)) {
      echo "js_pesquisa();\n";
    }
  }
  ?>

</script>