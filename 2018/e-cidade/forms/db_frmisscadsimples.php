<?php
/*
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

$clisscadsimples->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q02_numcgm");
$clrotulo->label("q39_dtbaixa");
$clrotulo->label("q39_issmotivobaixa");
$clrotulo->label("q39_obs");
?>
<form name="form1" method="post" action="">
<fieldset style="min-width:590px;">
  <legend>Dados do Cadastro</legend>

<table border="0">
  <tr>
    <td nowrap title="<?php echo $Tq38_sequencial?>">
       <?php echo $Lq38_sequencial?>
    </td>
    <td>
    <?php
    db_input('q38_sequencial',10,$Iq38_sequencial,true,'text',3,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tq38_inscr?>">
       <?php
       db_ancora($Lq38_inscr,"js_pesquisaq38_inscr(true);",$db_opcaoinscr);
       ?>
    </td>
    <td>
      <?php
        db_input('q38_inscr',10,$Iq38_inscr,true,'text',$db_opcaoinscr," onchange='js_pesquisaq38_inscr(false);'");
        db_input('z01_nome',50,$Iq02_numcgm,true,'text',3,'');
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tq38_dtinicial?>">
       <?php echo $Lq38_dtinicial?>
    </td>
    <td>
      <?php
      db_inputdata('q38_dtinicial',$q38_dtinicial_dia,$q38_dtinicial_mes,$q38_dtinicial_ano,true,'text',$db_opcao,"")
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tq38_categoria?>">
       <?php echo $Lq38_categoria?>
    </td>
    <td>
      <?php
        $x = array('1'=>'Micro Empresa','2'=>'Empresa de pequeno porte','3'=>'MEI');
        db_select('q38_categoria',$x,true,$db_opcao,"");
      ?>
    </td>
  </tr>
  </table>
	</fieldset>

	<?php

	if ($baixado == true){
	?>

	 <fieldset>
        <legend>Dados Da Baixa</legend>
      <table>
        <tr>
          <td nowrap title="<?php echo $Tq39_dtbaixa?>">
             <?php echo $Lq39_dtbaixa?>
          </td>
          <td>
            <?php
            db_inputdata('q39_dtbaixa', $q39_dtbaixa_dia, $q39_dtbaixa_mes, $q39_dtbaixa_ano,true,'text',3,"")
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tq39_issmotivobaixa?>">
             <?php echo $Lq39_issmotivobaixa?>
          </td>
          <td>
             <?php

               $clissmotivobaixa = new cl_issmotivobaixa;
               $result           = $clissmotivobaixa->sql_record($clissmotivobaixa->sql_query("","*"));
               if(!$result){
                 db_redireciona("db_erros.php?fechar=false&db_erro=Erro aos consultar motivos da baixa");
               }
               if(pg_num_rows($result) > 0 ){
                 db_selectrecord("q39_issmotivobaixa",$result,true,3);
               }
             ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?php echo $Tq39_obs?>">
             <?php echo $Lq39_obs?>
          </td>
          <td>
             <?php
               db_textarea('q39_obs',8,60,$Iq39_obs,true,'text',3,"")
             ?>
          </td>
        </tr>
      </table>
    </fieldset>
	<?
   }//fecha if baixados.
	?>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" onclick="return js_envia();" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> />
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
</form>
<script type="text/javascript">

function js_envia(){

  if (document.form1.q38_dtinicial.value == ''){
      alert('Campo Data Inicial é de preenchimento obrigatório.');
      return false;
  }
}
function js_pesquisaq38_inscr(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostraissbase1|q02_inscr|z01_nome|q02_dtbaix','Pesquisa',true);
  }else{

     if(document.form1.q38_inscr.value != ''){
        js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_issbase','func_issbase.php?pesquisa_chave='+document.form1.q38_inscr.value+'&funcao_js=parent.js_mostraissbase','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostraissbase(chave,erro,chave2){

  if (chave2 == ''){
      document.form1.z01_nome.value = chave;
	}else{

		document.form1.z01_nome.value = '';
    document.form1.q38_inscr.value = '';
		alert('Inscrição já baixada');

	}
  if (erro==true){
    document.form1.q38_inscr.focus();
    document.form1.q38_inscr.value = '';
  }
}
function js_mostraissbase1(chave1,chave2,chave3){

	if (chave3 == ''){

     document.form1.q38_inscr.value = chave1;
     document.form1.z01_nome.value = chave2;
     db_iframe_issbase.hide();
	}else{

    alert('Inscrição '+chave1+' já baixada!');
	}
}
function js_pesquisa(){
  js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_isscadsimples','func_isscadsimples.php?funcao_js=parent.js_preenchepesquisa|q38_sequencial','Pesquisa',true);
}
function js_preenchepesquisa(chave){

  db_iframe_isscadsimples.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>