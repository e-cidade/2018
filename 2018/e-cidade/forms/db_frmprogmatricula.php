<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

//MODULO: educação
$clprogmatricula->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("rh01_numcgm");
$clrotulo->label("ed107_i_codigo");
$clrotulo->label("nome");
if(isset($ed112_c_situacao) && $ed112_c_situacao!="A"){
 $db_botao = false;
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0" align="left">
 <tr>
  <td nowrap title="<?=@$Ted112_i_codigo?>">
   <?=@$Led112_i_codigo?>
  </td>
  <td>
   <?db_input('ed112_i_codigo',10,$Ied112_i_codigo,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_i_rhpessoal?>">
   <?db_ancora(@$Led112_i_rhpessoal,"js_pesquisaed112_i_rhpessoal(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed112_i_rhpessoal',10,$Ied112_i_rhpessoal,true,'text',3," onchange='js_pesquisaed112_i_rhpessoal(false);'")?>
   <?db_input('z01_nome',40,@$Iz01_nome,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_i_progclasse?>">
   <?db_ancora(@$Led112_i_progclasse,"js_pesquisaed112_i_progclasse(true);",$db_opcao1);?>
  </td>
  <td>
   <?db_input('ed112_i_progclasse',10,$Ied112_i_progclasse,true,'text',$db_opcao1," onchange='js_pesquisaed112_i_progclasse(false);'")?>
   <?db_input('ed107_c_descr',20,@$Ied107_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_i_nivel?>">
   <?db_ancora(@$Led112_i_nivel,"js_pesquisaed112_i_nivel(true);",$db_opcao);?>
  </td>
  <td>
   <?db_input('ed112_i_nivel',10,$Ied112_i_nivel,true,'text',$db_opcao," onchange='js_pesquisaed112_i_nivel(false);'")?>
   <?db_input('ed124_c_descr',20,@$Ied124_c_descr,true,'text',3,'')?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_c_classeesp?>">
   <?=@$Led112_c_classeesp?>
  </td>
  <td>
   <?
   $x = array(''=>'','N'=>'NÃO','S'=>'SIM');
   db_select('ed112_c_classeesp',$x,true,$db_opcao," style='width:80px;height:15px;font-size:10px;padding:0px;'");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_c_dedicacao?>">
   <?=@$Led112_c_dedicacao?>
  </td>
  <td>
   <?
   $x = array(''=>'','N'=>'NÃO','S'=>'SIM');
   db_select('ed112_c_dedicacao',$x,true,$db_opcao," style='width:80px;height:15px;font-size:10px;padding:0px;'");
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap valign="top">
   <b>Difícil Acesso:</b>
  </td>
  <td>
   <?
   if($db_opcao!=1 && isset($ed112_i_rhpessoal)){
    $sql1 = "SELECT ed18_i_codigo,ed18_c_nome,ed125_c_descr
             FROM rechumanoescola
              inner join escoladifacesso on ed126_i_escola = ed75_i_escola
              inner join tipoacesso on ed125_i_codigo = ed126_i_tipoacesso
              inner join escola on ed18_i_codigo = ed126_i_escola
             WHERE ed75_i_rechumano = $ed112_i_rhpessoal
            ";
    $result1 = pg_query($sql1);
    $linhas1 = pg_num_rows($result1);
    if($linhas1>0){
     $x = array('S'=>'SIM');
     db_select('ed112_c_dacesso',$x,true,$db_opcao," style='width:80px;height:15px;font-size:10px;padding:0px;'");
     for($x=0;$x<$linhas1;$x++){
      db_fieldsmemory($result1,$x);
      echo "<br><b>Escola:</b> ".$ed18_i_codigo." - ".$ed18_c_nome." <b>Tipo:</b> ".$ed125_c_descr;
     }
    }else{
     $x = array('N'=>'NÃO');
     db_select('ed112_c_dacesso',$x,true,$db_opcao," style='width:80px;height:15px;font-size:10px;padding:0px;'");
    }
   }else{
    echo "-------------";
   }
   ?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_d_database?>">
   <?=@$Led112_d_database?>
  </td>
  <td>
   <?db_inputdata('ed112_d_database',@$ed112_d_database_dia,@$ed112_d_database_mes,@$ed112_d_database_ano,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_d_datainicio?>">
   <?=@$Led112_d_datainicio?>
  </td>
  <td>
   <?db_inputdata('ed112_d_datainicio',@$ed112_d_datainicio_dia,@$ed112_d_datainicio_mes,@$ed112_d_datainicio_ano,true,'text',$db_opcao,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=@$Ted112_d_datafinal?>">
   <?=@$Led112_d_datafinal?>
  </td>
  <td>
   <?db_inputdata('ed112_d_datafinal',@$ed112_d_datafinal_dia,@$ed112_d_datafinal_mes,@$ed112_d_datafinal_ano,true,'text',3,"")?>
  </td>
 </tr>
 <tr>
  <td nowrap title="<?=$Ted112_c_situacao?>">
   <?=$Led112_c_situacao?>
  </td>
  <td nowrap>
   <?
   $x = array(''=>'','A'=>'ABERTA','E'=>'ENCERRADA','I'=>'INTERROMPIDA');
   db_select('ed112_c_situacao',$x,true,3,"");
   ?>
  </td>
 </tr>
 <?
 if(isset($ed112_c_situacao) && $ed112_c_situacao=="I" && $db_opcao!=1){
  $result = $clproginterrompe->sql_record($clproginterrompe->sql_query("","*",""," ed123_i_progmatricula = $chavepesquisa"));
  db_fieldsmemory($result,0);
  ?>
  <tr>
   <td><b>Interrompida em:</b></td>
   <td><?=db_formatar($ed123_d_data,'d')?></td>
  </tr>
  <tr>
   <td><b>Motivo:</b></td>
   <td><?=nl2br($ed123_t_motivo)?></td>
  </tr>
  <?
 }elseif(isset($ed112_c_situacao) && $ed112_c_situacao=="A" && $db_opcao!=1){
  $total_dias_prog = $ed110_i_intervalo*365;
  $ed112_d_dataprevisao = strftime("%Y-%m-%d",mktime(0,0,0,$ed112_d_datainicio_mes,$ed112_d_datainicio_dia+$total_dias_prog,$ed112_d_datainicio_ano));
  $ed112_d_dataprevisao_dia = substr($ed112_d_dataprevisao,8,2);
  $ed112_d_dataprevisao_mes = substr($ed112_d_dataprevisao,5,2);
  $ed112_d_dataprevisao_ano = substr($ed112_d_dataprevisao,0,4);
  $result3 = $clproglicencamatr->sql_record($clproglicencamatr->sql_query("","ed121_c_descr,ed121_i_tempolimite,ed122_d_inicio,ed122_d_final",""," ed122_i_progmatricula = $ed112_i_codigo AND ed122_d_inicio BETWEEN '$ed112_d_datainicio' AND '$ed112_d_dataprevisao' AND ed121_c_suspensao = 'S'"));
  $soma_licenca = 0;
  if($clproglicencamatr->numrows>0){
   for($x=0;$x<$clproglicencamatr->numrows;$x++){
    $dias_licenca = 0;
    db_fieldsmemory($result3,$x);
    $data_inicio = mktime(0,0,0,substr($ed122_d_inicio,5,2),substr($ed122_d_inicio,8,2),substr($ed122_d_inicio,0,4));
    $data_final = mktime(0,0,0,substr($ed122_d_final,5,2),substr($ed122_d_final,8,2),substr($ed122_d_final,0,4));
    $data_entre = $data_final - $data_inicio;
    $dias = ceil($data_entre/86400);
    if($ed121_i_tempolimite>0){
     if($dias>$ed121_i_tempolimite){
      $dias_licenca = $dias;
     }
    }else{
     $dias_licenca = $dias;
    }
    $soma_licenca += $dias_licenca;
   }
  }
  $ed112_d_dataprevisao = strftime("%Y-%m-%d",mktime(0,0,0,$ed112_d_dataprevisao_mes,$ed112_d_dataprevisao_dia+$soma_licenca,$ed112_d_dataprevisao_ano));
  ?>
  <tr>
   <td><b>Pontos na Classe <?=$ed107_c_descr?>:</b></td>
   <td>
    <?
    $total_antiguidade = 0;
    $total_convocacao = 0;
    $total_avaladmin = 0;
    $total_avalpedag = 0;
    $total_desempenho = 0;
    $total_conhec = 0;
    for($x=$ed112_d_datainicio_ano+1;$x<=$ed112_d_dataprevisao_ano;$x++){
     $soma_ano = 0;
     $soma_desempenho = 0;
     $result1 = $clprogantig->sql_record($clprogantig->sql_query("","ed113_f_pontuacao",""," ed113_i_progmatricula = $ed112_i_codigo AND ed113_i_ano = $x"));
     if($clprogantig->numrows>0){
      db_fieldsmemory($result1,0);
      $ptantiguidade =  $ed113_f_pontuacao;
     }else{
      $ptantiguidade = 0;
     }
     $soma_ano += $ptantiguidade;
     $total_antiguidade += $ptantiguidade;
     $result3 = $clprogconvocacaores->sql_record($clprogconvocacaores->sql_query("","ed127_i_nconvoca as qtdconvocacao,ed127_i_nparticipa as qtdparticipacao,ed127_i_nfaltajust as qtdft",""," ed127_i_ano = $x AND ed127_i_progmatricula = $ed112_i_codigo"));
     if($clprogconvocacaores->numrows>0){
      db_fieldsmemory($result3,0);
      if($qtdparticipacao==0){
       $ptconvocacao = 0;
      }else{
       $ptconvocacao = (($qtdparticipacao+$qtdft)/$qtdconvocacao);
       $ptconvocacao = $ed110_i_ptconvocacao*$ptconvocacao;
      }
     }else{
      $ptconvocacao = 0;
     }
     $total_convocacao += $ptconvocacao;
     $soma_desempenho += $ptconvocacao;
     $result1 = $clopcaoquestao->sql_record($clopcaoquestao->sql_query("","max(ed106_f_pontuacao) as maiorpt","","ed106_c_ativo = 'S'"));
     db_fieldsmemory($result1,0);
     $result2 = $clprogavaladmin->sql_record($clprogavaladmin->sql_query("","count(*) as qtdquestao",""," ed116_i_ano = $x AND ed116_i_progmatricula = $ed112_i_codigo"));
     db_fieldsmemory($result2,0);
     $result3 = $clprogavaladmin->sql_record($clprogavaladmin->sql_query("","sum(ed106_f_pontuacao) as somapt",""," ed116_i_ano = $x AND ed116_i_progmatricula = $ed112_i_codigo"));
     db_fieldsmemory($result3,0);
     $maximopt = $maiorpt*$qtdquestao;
     if($somapt==""){
      $somapt = 0;
     }
     if($maximopt==0){
      $ptavaladmin = 0;
     }else{
      $ptavaladmin = ($somapt/$maximopt);
      $ptavaladmin = $ed110_i_ptavaladmin*$ptavaladmin;
     }
     $total_avaladmin += $ptavaladmin;
     $soma_desempenho += $ptavaladmin;
     $result1 = $clopcaoquestao->sql_record($clopcaoquestao->sql_query("","max(ed106_f_pontuacao) as maiorpt","","ed106_c_ativo = 'S'"));
     db_fieldsmemory($result1,0);
     $result2 = $clprogavalpedag->sql_record($clprogavalpedag->sql_query("","count(*) as qtdquestao",""," ed117_i_ano = $x AND ed117_i_progmatricula = $ed112_i_codigo"));
     db_fieldsmemory($result2,0);
     $result3 = $clprogavalpedag->sql_record($clprogavalpedag->sql_query("","sum(ed106_f_pontuacao) as somapt",""," ed117_i_ano = $x AND ed117_i_progmatricula = $ed112_i_codigo"));
     db_fieldsmemory($result3,0);
     $maximopt = $maiorpt*$qtdquestao;
     if($somapt==""){
      $somapt = 0;
     }
     if($maximopt==0){
      $ptavalpedag = 0;
     }else{
      $ptavalpedag = ($somapt/$maximopt);
      $ptavalpedag = $ed110_i_ptavalpedag*$ptavalpedag;
     }
     $total_avalpedag += $ptavalpedag;
     $soma_desempenho += $ptavalpedag;
     $soma_ano += $soma_desempenho;
     $total_desempenho += $soma_desempenho;
     $result3 = $clprogconhec->sql_record($clprogconhec->sql_query("","sum(ed114_f_cargahoraria) as somach",""," ed114_i_ano = $x AND ed114_i_progmatricula = $ed112_i_codigo"));
     db_fieldsmemory($result3,0);
     if($somach==""){
      $somach = 0;
     }
     $total_conhec += $somach;
     $soma_ano += $somach;
    }
    $total_conhec = $total_conhec>200?200:$total_conhec;
    $soma_total = $total_antiguidade+$total_desempenho+$total_conhec;
    $cor = $soma_total>=$ed110_i_ptgeral?"green":"red";
    ?>
    <font color="<?=$cor?>"><b><?=number_format($soma_total,2,".",".")?></b></font>
   </td>
  </tr>
  <tr>
   <?$cor = str_replace("-","",$ed112_d_dataprevisao)-date("Ymd")<0?"green":"red";?>
   <td><b>Próxima Progressão:</b></td>
   <td><font color="<?=$cor?>"><b><?=db_formatar($ed112_d_dataprevisao,'d')?></b></font></td>
  </tr>
  <tr>
   <td colspan="2">
    <?if(str_replace("-","",$ed112_d_dataprevisao)-date("Ymd")<0 && $soma_total>=$ed110_i_ptgeral  && $ed112_c_situacao=="A"){?>
    <form name="form1" method="post" action="">
    <fieldset style="width:80%;">
     <table align="center" border="0" cellspacing="0" cellpadding="2">
        <?
        $result = $clprogclasse->sql_record($clprogclasse->sql_query("","max(ed107_i_sequencia) as ultimaclasse","",""));
        db_fieldsmemory($result,0);
        if($ultimaclasse==$ed107_i_sequencia){
         ?>
         <tr>
          <td align="center" style="text-decoration:blink;">
           <b>Matrícula <?=$ed112_i_rhpessoal?> encerrou última classe na progressão.</b><br>
          </td>
         </tr>
         <tr>
          <td align="center">
           <input type="button" name="confirmar" value="Encerrar Progressão" onclick="js_encerrar(<?=$ed112_i_codigo?>)">
          </td>
         </tr>
         <?
        }else{
         $result = $clprogclasse->sql_record($clprogclasse->sql_query("","ed107_i_codigo as prxcod, ed107_c_descr as prxclasse",""," ed107_i_sequencia = ".($ed107_i_sequencia+1).""));
         db_fieldsmemory($result,0);
         ?>
         <tr>
          <td align="center" style="text-decoration:blink;">
          <b>Matrícula <?=$ed112_i_rhpessoal?> está apta para progressão à classe <?=$prxclasse?>.</b>
          </td>
         </tr>
         <tr>
          <td align="center">
           <input type="button" name="confirmar" value="Confirmar Progressão" onclick="js_progredir(<?=$ed112_i_codigo?>)">
          </td>
         </tr>
         <?
        }
        ?>
     </table>
    </fieldset>
    </form>
    </center>
   <?}?>
  </td>
 </tr>
 <?
 }
 ?>
 <tr>
  <td colspan="2">
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
   <?if(isset($ed112_i_codigo) && $ed112_i_codigo!="" && $db_opcao!=1){?>
    <input type="button" name="confirmar" value="Planilha" onclick="location.href='edu3_progmatricula001.php?chavepesquisa=<?=$ed112_i_codigo?>'">
   <?}?>
   <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
  </td>
 </tr>
</table>
</center>
</form>
<script>
function js_pesquisaed112_i_rhpessoal(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoaleducacao.php?funcao_js=parent.js_mostrarhpessoal1|rh01_regist|z01_nome|rh01_admiss','Pesquisa',true);
 }else{
  if(document.form1.ed112_i_rhpessoal.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_rhpessoal','func_rhpessoaleducacao.php?pesquisa_chave='+document.form1.ed112_i_rhpessoal.value+'&funcao_js=parent.js_mostrarhpessoal','Pesquisa',false);
  }else{
   document.form1.z01_nome.value = '';
  }
 }
}
function js_mostrarhpessoal(chave,erro){
 document.form1.z01_nome.value = chave;
 if(erro==true){
  document.form1.ed112_i_rhpessoal.focus();
  document.form1.ed112_i_rhpessoal.value = '';
 }
}
function js_mostrarhpessoal1(chave1,chave2,chave3){
 document.form1.ed112_i_rhpessoal.value = chave1;
 document.form1.z01_nome.value = chave2;
 document.form1.ed112_d_database_ano.value = chave3.substr(0,4);
 document.form1.ed112_d_database_mes.value = chave3.substr(5,2);
 document.form1.ed112_d_database_dia.value = chave3.substr(8,2);
 db_iframe_rhpessoal.hide();
}
function js_pesquisaed112_i_progclasse(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_progclasse','func_progclasse.php?funcao_js=parent.js_mostraprogclasse1|ed107_i_codigo|ed107_c_descr','Pesquisa',true);
 }else{
  if(document.form1.ed112_i_progclasse.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_progclasse','func_progclasse.php?pesquisa_chave='+document.form1.ed112_i_progclasse.value+'&funcao_js=parent.js_mostraprogclasse','Pesquisa',false);
  }else{
   document.form1.ed107_c_descr.value = '';
  }
 }
}
function js_mostraprogclasse(chave,erro){
 document.form1.ed107_c_descr.value = chave;
 if(erro==true){
  document.form1.ed112_i_progclasse.focus();
  document.form1.ed112_i_progclasse.value = '';
 }
}
function js_mostraprogclasse1(chave1,chave2){
 document.form1.ed112_i_progclasse.value = chave1;
 document.form1.ed107_c_descr.value = chave2;
 db_iframe_progclasse.hide();
}
function js_pesquisaed112_i_nivel(mostra){
 if(mostra==true){
  js_OpenJanelaIframe('top.corpo','db_iframe_nivel','func_prognivel.php?funcao_js=parent.js_mostranivel1|ed124_i_codigo|ed124_c_descr','Pesquisa de Níveis',true);
 }else{
  if(document.form1.ed112_i_nivel.value != ''){
   js_OpenJanelaIframe('top.corpo','db_iframe_nivel','func_prognivel.php?pesquisa_chave='+document.form1.ed112_i_nivel.value+'&funcao_js=parent.js_mostranivel','Pesquisa',false);
  }else{
   document.form1.ed124_c_descr.value = '';
  }
 }
}
function js_mostranivel(chave,erro){
 document.form1.ed124_c_descr.value = chave;
 if(erro==true){
  document.form1.ed112_i_nivel.focus();
  document.form1.ed112_i_nivel.value = '';
 }
}
function js_mostranivel1(chave1,chave2){
 document.form1.ed112_i_nivel.value = chave1;
 document.form1.ed124_c_descr.value = chave2;
 db_iframe_nivel.hide();
}
function js_encerrar(codigo){
 location.href = "edu3_progmatricula001.php?chavepesquisa="+codigo;
}
function js_progredir(codigo,matricula,classe){
 location.href = "edu3_progmatricula001.php?chavepesquisa="+codigo;
}
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_progmatricula','func_progmatricula2.php?funcao_js=parent.js_preenchepesquisa|ed112_i_codigo','Pesquisa',true);
}
function js_preenchepesquisa(chave){
  db_iframe_progmatricula.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>