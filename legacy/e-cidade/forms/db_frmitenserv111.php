<?
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

//MODULO: cemiterio
$clitenserv->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("cm11_c_descr");
$clrotulo->label("cm01_i_codigo");
$clrotulo->label("nome");
$clrotulo->label("cm28_i_proprietario");
$clrotulo->label("cm28_i_ossoariojazigo");
$clrotulo->label("z01_nome");

$dia=date('d',db_getsession("DB_datausu"));
$mes=date('m',db_getsession("DB_datausu"));
$ano=date('Y',db_getsession("DB_datausu"));
?>
<form name="form1" method="post" action="">
 <input type="hidden" name="cm10_i_numpre" value="<?=@$cm10_i_numpre?>">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tcm10_i_codigo?>">
       <?=@$Lcm10_i_codigo?>
    </td>
    <td>
<?
db_input('cm10_i_codigo',10,$Icm10_i_codigo,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm28_i_ossoariojazigo?>">
       <?=@$Lcm28_i_ossoariojazigo?>
    </td>
    <td>
<?
db_input('cm28_i_ossoariojazigo',10,$Icm28_i_ossoariojazigo,true,'text',3,"readonly")
?>
    </td>
  </tr>


  <tr>
    <td nowrap title="<?=@$Tcm28_i_proprietario?>">
       <?=@$Lcm28_i_proprietario?>
    </td>
    <td>
<?
db_input('cm28_i_proprietario',10,$Icm28_i_proprietario,true,'text',3,"")
?>
       <?
db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm10_d_data?>">
       <?=@$Lcm10_d_data?>
    </td>
    <td>
<?
if(!isset($cm10_d_data_dia) && $db_opcao==1){
  $cm10_d_data_dia = $dia;
  $cm10_d_data_mes = $mes;
  $cm10_d_data_ano = $ano;
}
db_inputdata('cm10_d_data',@$cm10_d_data_dia,@$cm10_d_data_mes,@$cm10_d_data_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
</table>
<fieldset style="width: 600">
 <legend>Valores</legend>
<table>
  <tr>
    <td nowrap title="<?=@$Tcm10_i_taxaserv?>">
       <?=@$Lcm10_i_taxaserv?>
    </td>
    <td>
       <?
       include("classes/db_taxaserv_classe.php");
       $cltaxaserv = new cl_taxaserv;
       $clrotulo->label("cm11_f_valor");

       $result2 = $cltaxaserv->sql_record($cltaxaserv->sql_query());
       $tx=array();
       $tx[0]="Selecione";
       for($q=0; $q < $cltaxaserv->numrows; $q++){
        db_fieldsmemory($result2,$q);
        $tx[$cm11_i_codigo] = $cm11_c_descr;
       }
       db_select("cm10_i_taxaserv",$tx,true,$db_opcao,"onchange=\"submit()\"");
       ?>
    </td>
  </tr>
   <?php

   if(isset($cm10_i_taxaserv)){

      $result3 = @$cltaxaserv->sql_record( $cltaxaserv->sql_query($cm10_i_taxaserv) );
      @db_fieldsmemory($result3,0);
      $cm11_f_valor = '';
      $cm10_f_valor = $cm11_f_valor;

      /**
       * Get Valor da taxa do item de serviço
       */
      $sSqlValorTaxa = $cltaxaservval->sql_query( $cm10_i_taxaserv );
      $rsValorTaxa   = db_query( $sSqlValorTaxa );
      if( $rsValorTaxa && pg_num_rows($rsValorTaxa) > 0 ){
        $cm10_f_valortaxa = number_format( db_utils::fieldsMemory($rsValorTaxa, 0)->cm35_valor, 2 );
      }
    }

    if(!isset($cm10_i_taxaserv)){
     $cm10_f_valor = 0;
    }
   ?>
  <tr>
    <td nowrap title="<?=@$Tcm10_f_valortaxa?>">
       <?=@$Lcm10_f_valortaxa?>
    </td>
    <td>
     <?
       db_input('cm10_f_valortaxa',10,$Icm10_f_valortaxa,true,'text',3,"")
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm10_f_valor?>">
       <?=@$Lcm10_f_valor?>
    </td>
    <td>
     <?
      db_input('cm10_f_valor',10,$Icm10_f_valor,true,'text',$db_opcao,"")
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm10_d_privenc?>">
       <?=@$Lcm10_d_privenc?>
    </td>
    <td>
<?
db_inputdata('cm10_d_privenc',@$cm10_d_privenc_dia,@$cm10_d_privenc_mes,@$cm10_d_privenc_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcm10_t_obs?>">
       <?=@$Lcm10_t_obs?>
    </td>
    <td>
<?
db_textarea('cm10_t_obs',3,50,$Icm10_t_obs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
 </fieldset>
  </center>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_pesquisacm10_i_taxaserv(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_taxaserv','func_taxaserv.php?funcao_js=parent.js_mostrataxaserv1|cm11_i_codigo|cm11_c_descr','Pesquisa',true);
  }else{
     if(document.form1.cm10_i_taxaserv.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_taxaserv','func_taxaserv.php?pesquisa_chave='+document.form1.cm10_i_taxaserv.value+'&funcao_js=parent.js_mostrataxaserv','Pesquisa',false);
     }else{
       document.form1.cm11_c_descr.value = '';
     }
  }
}
function js_mostrataxaserv(chave,erro){
  document.form1.cm11_c_descr.value = chave;
  if(erro==true){
    document.form1.cm10_i_taxaserv.focus();
    document.form1.cm10_i_taxaserv.value = '';
  }
}
function js_mostrataxaserv1(chave1,chave2){
  document.form1.cm10_i_taxaserv.value = chave1;
  document.form1.cm11_c_descr.value = chave2;
  db_iframe_taxaserv.hide();
}
function js_pesquisacm10_i_sepultamento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_sepultamentos','func_sepultamentos.php?funcao_js=parent.js_mostrasepultamentos1|cm01_i_codigo|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.cm10_i_sepultamento.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_sepultamentos','func_sepultamentos.php?pesquisa_chave='+document.form1.cm10_i_sepultamento.value+'&funcao_js=parent.js_mostrasepultamentos','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = '';
     }
  }
}
function js_mostrasepultamentos(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.cm10_i_sepultamento.focus();
    document.form1.cm10_i_sepultamento.value = '';
  }
}
function js_mostrasepultamentos1(chave1,chave2){
  document.form1.cm10_i_sepultamento.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_sepultamentos.hide();
}
function js_pesquisacm10_i_usuario(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?funcao_js=parent.js_mostradb_usuarios1|id_usuario|nome','Pesquisa',true);
  }else{
     if(document.form1.cm10_i_usuario.value != ''){
        js_OpenJanelaIframe('top.corpo','db_iframe_db_usuarios','func_db_usuarios.php?pesquisa_chave='+document.form1.cm10_i_usuario.value+'&funcao_js=parent.js_mostradb_usuarios','Pesquisa',false);
     }else{
       document.form1.nome.value = '';
     }
  }
}
function js_mostradb_usuarios(chave,erro){
  document.form1.nome.value = chave;
  if(erro==true){
    document.form1.cm10_i_usuario.focus();
    document.form1.cm10_i_usuario.value = '';
  }
}
function js_mostradb_usuarios1(chave1,chave2){
  document.form1.cm10_i_usuario.value = chave1;
  document.form1.nome.value = chave2;
  db_iframe_db_usuarios.hide();
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_itenserv','func_itenserv.php?funcao_js=parent.js_preenchepesquisa|cm10_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_itenserv.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>