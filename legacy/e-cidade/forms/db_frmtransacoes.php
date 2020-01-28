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
?>
<form name="form1" method="post" action="">
<center>
<?if(!isset($sepultamento)||$sepultamento==""){?>
     <table border="0">
       <tr>
         <td nowrap title="<?=@$sepultamento?>">
           <?db_ancora("<strong>Sepultamento</strong>","js_pesquisasepultamento();",$db_opcao);?>
         </td>
         <td>
          <?
             db_input('sepultamento',10,@$sepultamento,true,'text',3)
          ?>
          <?
             db_input('nome',50,@$nome,true,'text',3,'')
          ?>
         </td>
       </tr>
       <tr>
        <td colspan="2" align="center"><input type="submit" value="Processar" name="processar"></td>
       </tr>
     </table>
<?}else{?>
      <style>
        #proprietario, #nome_cemiterio, #nome_cemit, #nome_sepultamento, #proprietario, #nome_cemiterio {
          width: 290px;
        }

        #cm19_c_descr, #cm06_t_obs{
          width: 383px;
        }

        #cm27_c_ossoario {
          width: 90px;
        }
      </style>
     <fieldset style="width: 100%">
     <legend>Localização</legend>
     <table>
      <tr>
       <td>
         <?
          //busca a localidade do sepultamento
          $db_opcao = 3;
          $db_opcao1 = 3;
          $tipoant = "";
          $codigo = 0;

          require(modification("funcoes/db_func_sepulta.php"));

          $result = $clsepulta->sql_record($clsepulta->sql_query("",$campos,"","cm24_i_sepultamento = $sepultamento"));
          if($clsepulta->numrows > 0){
           db_fieldsmemory($result,0);

           $result2 = $clrenovacoes->sql_record($clrenovacoes->sql_query("","cm07_d_vencimento","cm07_d_vencimento desc limit 1","cm07_i_sepultamento = $sepultamento"));
            @db_fieldsmemory($result2,0);

            if(@$cm07_d_vencimento == ""){
             $cm07_d_vencimento = (substr($cm24_d_entrada,0,4)+5)."/".substr($cm24_d_entrada,5,2)."/".substr($cm24_d_entrada,8,2);
            }

                $db_botao = false;
                //echo "<strong>Sepultura</strong>";
                $arquivo = 'forms/db_frmsepulta.php';
                $tipoant = "sepulta";
                $local   = 1;
                $codigo = $cm24_i_codigo;
          }else{

            $result = $clossoario->sql_record($clossoario->sql_query("","ossoario.*,cgm.z01_nome as nome_sepultamento","","cm06_i_sepultamento = $sepultamento"));
            if($clossoario->numrows > 0){

               db_fieldsmemory($result,0);
               $db_botao=false;
               //echo "<strong>Ossoario Geral</strong>";
               $arquivo = "forms/db_frmossoario.php";
               $tipoant = "ossoariogeral";
               $local   = 2;
               $codigo  = $cm06_i_codigo;
            }else{

               $result = $clrestosgavetas->sql_record($clrestosgavetas->sql_query("","*","","cm26_i_sepultamento = $sepultamento and not exists(select cm27_i_codigo from gavetas where cm27_i_restogaveta=cm26_i_codigo)"));
               if( $clrestosgavetas->numrows > 0){

                   db_fieldsmemory($result,0);
                   require(modification("funcoes/db_func_ossoariojazigo.php"));
                   $result = $clossoariojazigo->sql_record( $clossoariojazigo->sql_query( $cm26_i_ossoariojazigo, $campos ) );
                   db_fieldsmemory($result,0);
                   $db_botao = false;
                   // echo "<strong>Ossoario Particular</strong>";
                   $cm26_i_sepultamento = $sepultamento;
                   $tipo='O';
                   $arquivo="forms/db_frmrestosgavetas.php";
                   $tipoant = "ossoariopart";
                   $local   = 3;
                   $codigo=$cm26_i_codigo;
               }else{

                   $result = $clgavetas->sql_record($clgavetas->sql_query("","*","","cm26_i_sepultamento = $sepultamento"));
                   if( $clgavetas->numrows>0){

                       db_fieldsmemory($result,0);
                       require(modification("funcoes/db_func_ossoariojazigo.php"));
                       $result = $clossoariojazigo->sql_record( $clossoariojazigo->sql_query( $cm26_i_ossoariojazigo, $campos ) );
                       db_fieldsmemory($result,0);
                       $db_botao = false;
                       // echo "<strong>Jazigo/Capela</strong>";
                       $cm26_i_sepultamento = $sepultamento;
                       $tipo='J';
                       $arquivo="forms/db_frmrestosgavetas.php";
                       $tipoant = "jazigo";
                       $local   = 4;
                       $codigo = $cm26_i_codigo;
                   }else{

                       $result = $clretiradas->sql_record($clretiradas->sql_query("","*", null, "cm08_i_sepultamento = {$sepultamento}"));
                       if( $clretiradas->numrows>0){
                           db_fieldsmemory($result,0);
                           $db_botao = false;
                           echo "<strong>Retirada</strong>";
                           $arquivo='forms/db_frmretiradas.php';
                           $tipoant = "retirada";
                           $codigo = $cm08_i_codigo;
                       }else{
                           echo "<strong>EM ABERTO</strong>";
                       } //else clretiradas
                   } //else clossoariojazigo
               } //else restosgavetas
            } //else clossoario
          } //else clsepulta
          if( $tipoant != "" ){
             ?>
                 <input name="tipoant" type="hidden" value="<?=$tipoant?>">
                 <input name="codigo" type="hidden" value="<?=$codigo?>">
             <?
           include(modification($arquivo));
          }
        ?>
       </td>
      </tr>
     </table>
     </fieldset>
<?}?>
</form>
</center>
<script>
 if( parent.document.formaba.a4 != null ){
  parent.document.formaba.a4.disabled=false;
  if(parent.document.formaba.a4.value != "Débitos"){
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href='cem1_transacao002.php?sepultamento=<?=$sepultamento?>&tipoant=<?=$tipoant?>&lotecemit=<?=@$cm23_i_codigo?>';
  }
 }

function js_pesquisasepultamento(){
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a1','db_iframe_sepultamentos','func_sepultamentos.php?funcao_js=parent.js_mostrasepultamentos1|cm01_i_codigo|z01_nome','Pesquisa',true);
}
function js_mostrasepultamentos1(chave1,chave2){
  document.form1.sepultamento.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_sepultamentos.hide();
}
</script>