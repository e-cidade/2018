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

//MODULO: Educação
$cl_escolaestrutura->rotulo->label();
$clrotulo = new rotulocampo;
?>
<form name="form1" method="post" action="">
<input type="hidden" name="ed255_i_codigo" value="<?=@$ed255_i_codigo?>">
<table width="100%">
 <tr>
  <td valign="top">
   <?
   if(!isset($ed255_c_dependencias)){
    $ed255_c_dependencias = "000000000000000000";
   }
   ?>
   <fieldset style="padding:0px;height:370px;"><legend><?=$Led255_c_dependencias?></legend>
    <input <?=substr(@$ed255_c_dependencias,0,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="1" onclick="js_dependencias();"> Diretoria<br>
    <input <?=substr(@$ed255_c_dependencias,1,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="2" onclick="js_dependencias();"> Sala de Professores<br>
    <input <?=substr(@$ed255_c_dependencias,2,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="3" onclick="js_dependencias();"> Laboratório de Informática<br>
    <input <?=substr(@$ed255_c_dependencias,3,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="4" onclick="js_dependencias();"> Laboratório de Ciências<br>
    <input <?=substr(@$ed255_c_dependencias,4,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="5" onclick="js_dependencias();"> Sala de recursos multifuncionais para AEE<br>
    <input <?=substr(@$ed255_c_dependencias,5,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="6" onclick="js_dependencias();"> Quadra de Esportes Coberta<br>
    <input <?=substr(@$ed255_c_dependencias,6,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="7" onclick="js_dependencias();"> Quadra de Esportes Descoberta<br>
    <input <?=substr(@$ed255_c_dependencias,7,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="8" onclick="js_dependencias();"> Cozinha<br>
    <input <?=substr(@$ed255_c_dependencias,8,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="9" onclick="js_dependencias();"> Biblioteca<br>
    <input <?=substr(@$ed255_c_dependencias,9,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="10" onclick="js_dependencias();"> Sala de Leitura<br>
    <input <?=substr(@$ed255_c_dependencias,10,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="11" onclick="js_dependencias();"> Parque Infantil<br>
    <input <?=substr(@$ed255_c_dependencias,11,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="12" onclick="js_dependencias();"> Berçario<br>
    <input <?=substr(@$ed255_c_dependencias,12,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="13" onclick="js_dependencias();"> Sanitário fora do Prédio<br>
    <input <?=substr(@$ed255_c_dependencias,13,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="14" onclick="js_dependencias();"> Sanitário dentro do Prédio<br>
    <input <?=substr(@$ed255_c_dependencias,14,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="15" onclick="js_dependencias();"> Sanitário adequado à Educação Infantil<br>
    <input <?=substr(@$ed255_c_dependencias,15,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="16" onclick="js_dependencias();"> Sanitário p/ alunos c/ deficiência.<br>
    <input <?=substr(@$ed255_c_dependencias,16,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="17" onclick="js_dependencias();"> Dependências e vias p/ alunos c/ deficiência.<br>
    <input <?=substr(@$ed255_c_dependencias,17,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_dependencias" name="ed255_c_dependencias[]" type="checkbox" value="18" onclick="js_dependencias();"> Nenhuma das dependências relacionadas<br>
   </fieldset>
  </td>
  <td valign="top" rowspan='8'>
   <?
   if(!isset($ed255_c_localizacao)){
    $ed255_c_localizacao = "00000000";
   }
   ?>
   <fieldset style="padding:0px;"><legend><?=$Led255_c_localizacao?></legend>
    <input <?=substr(@$ed255_c_localizacao,0,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_localizacao" name="ed255_c_localizacao[]" type="checkbox" value="1"> Prédio Escolar<br>
    <input <?=substr(@$ed255_c_localizacao,1,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_localizacao" name="ed255_c_localizacao[]" type="checkbox" value="2"> Templo / Igreja<br>
    <input <?=substr(@$ed255_c_localizacao,2,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_localizacao" name="ed255_c_localizacao[]" type="checkbox" value="3"> Salas de Empresa<br>
    <input <?=substr(@$ed255_c_localizacao,3,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_localizacao" name="ed255_c_localizacao[]" type="checkbox" value="4"> Casa do Professor<br>
    <input <?=substr(@$ed255_c_localizacao,4,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_localizacao" name="ed255_c_localizacao[]" type="checkbox" value="5"> Salas em Outra Escola<br>
    <input <?=substr(@$ed255_c_localizacao,5,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_localizacao" name="ed255_c_localizacao[]" type="checkbox" value="6"> Galpão / Rancho / Paiol<br>
    <input <?=substr(@$ed255_c_localizacao,6,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_localizacao" name="ed255_c_localizacao[]" type="checkbox" value="7"> Unidade de Internação / Prisional<br>
    <input <?=substr(@$ed255_c_localizacao,7,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_localizacao" name="ed255_c_localizacao[]" type="checkbox" value="8"> Outros<br>
   </fieldset>
   <br>
   <?
   if(!isset($ed255_i_formaocupacao)){
    $ed255_i_formaocupacao = "0";
   }
   ?>
   <fieldset style="padding:0px;height:96px;"><legend><?=@$Led255_i_formaocupacao?></legend>
    <input type="radio" id="ed255_i_formaocupacao" name="ed255_i_formaocupacao" value="1" <?=@$ed255_i_formaocupacao=="1"?"checked":""?>> Próprio<br>
    <input type="radio" id="ed255_i_formaocupacao" name="ed255_i_formaocupacao" value="2" <?=@$ed255_i_formaocupacao=="2"?"checked":""?>> Alugado<br>
    <input type="radio" id="ed255_i_formaocupacao" name="ed255_i_formaocupacao" value="3" <?=@$ed255_i_formaocupacao=="3"?"checked":""?>> Cedido<br> 
   </fieldset>
   <br>
   <?
   if(!isset($ed255_c_esgotosanitario)){
    $ed255_c_esgotosanitario = "000";
   }
   ?>
   
   <fieldset style="padding:0px;"><legend><?=$Led255_c_esgotosanitario?></legend>
    <input <?=substr(@$ed255_c_esgotosanitario,0,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_esgotosanitario" name="ed255_c_esgotosanitario[]" type="checkbox" value="1" onclick="js_esgoto();"> Rede Pública<br>
    <input <?=substr(@$ed255_c_esgotosanitario,1,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_esgotosanitario" name="ed255_c_esgotosanitario[]" type="checkbox" value="2" onclick="js_esgoto();"> Fossa<br>
    <input <?=substr(@$ed255_c_esgotosanitario,2,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_esgotosanitario" name="ed255_c_esgotosanitario[]" type="checkbox" value="3" onclick="js_esgoto();"> Inexistente<br>
   </fieldset>
   <br>
   <?
   if(!isset($ed255_c_materdidatico)){
    $ed255_c_materdidatico = "000";
   }
   ?>
   <fieldset style="padding:0px;"><legend><?=$Led255_c_materdidatico?></legend>
    <input <?=substr(@$ed255_c_materdidatico,0,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_materdidatico" name="ed255_c_materdidatico[]" type="checkbox" value="1" onclick="js_mater()"> Não Utiliza<br>
    <input <?=substr(@$ed255_c_materdidatico,1,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_materdidatico" name="ed255_c_materdidatico[]" type="checkbox" value="2" onclick="js_mater()" > Quilombola<br>
    <input <?=substr(@$ed255_c_materdidatico,2,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_materdidatico" name="ed255_c_materdidatico[]" type="checkbox" value="3" onclick="js_mater()"> Indígena<br>
   </fieldset>
  </td>
  <td valign="top">
   <?
   if(!isset($ed255_c_equipamentos)){
    $ed255_c_equipamentos = "0000000";
   }
   ?>
   <fieldset style="padding:0px;"><legend><?=$Led255_c_equipamentos?></legend>
    <input <?=substr(@$ed255_c_equipamentos,0,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_equipamentos" name="ed255_c_equipamentos[]" type="checkbox" value="1"> Aparelho de Televisão<br>
    <input <?=substr(@$ed255_c_equipamentos,1,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_equipamentos" name="ed255_c_equipamentos[]" type="checkbox" value="2"> Videocassete<br>
    <input <?=substr(@$ed255_c_equipamentos,2,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_equipamentos" name="ed255_c_equipamentos[]" type="checkbox" value="3"> DVD<br>
    <input <?=substr(@$ed255_c_equipamentos,3,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_equipamentos" name="ed255_c_equipamentos[]" type="checkbox" value="4"> Antena Parabólica<br>
    <input <?=substr(@$ed255_c_equipamentos,4,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_equipamentos" name="ed255_c_equipamentos[]" type="checkbox" value="5"> Copiadora<br>
    <input <?=substr(@$ed255_c_equipamentos,5,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_equipamentos" name="ed255_c_equipamentos[]" type="checkbox" value="6"> Retroprojetor<br>
    <input <?=substr(@$ed255_c_equipamentos,6,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_equipamentos" name="ed255_c_equipamentos[]" type="checkbox" value="7"> Impressora<br>
   </fieldset>
   <br>
   <?
   if(!isset($ed255_c_destinolixo)){
    $ed255_c_destinolixo = "000000";
   }
   ?>
   <fieldset style="padding:0px;height:174px;"><legend><?=$Led255_c_destinolixo?></legend>
    <input <?=substr(@$ed255_c_destinolixo,0,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_destinolixo" name="ed255_c_destinolixo[]" type="checkbox" value="1"> Coleta Periódica<br>
    <input <?=substr(@$ed255_c_destinolixo,1,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_destinolixo" name="ed255_c_destinolixo[]" type="checkbox" value="2"> Queima<br>
    <input <?=substr(@$ed255_c_destinolixo,2,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_destinolixo" name="ed255_c_destinolixo[]" type="checkbox" value="3"> Joga em outra área<br>
    <input <?=substr(@$ed255_c_destinolixo,3,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_destinolixo" name="ed255_c_destinolixo[]" type="checkbox" value="1"> Recicla<br>
    <input <?=substr(@$ed255_c_destinolixo,4,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_destinolixo" name="ed255_c_destinolixo[]" type="checkbox" value="2"> Enterra<br>
    <input <?=substr(@$ed255_c_destinolixo,5,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_destinolixo" name="ed255_c_destinolixo[]" type="checkbox" value="3"> Outros<br>
   </fieldset>
  </td>
  <td valign="top">
   <?
   if(!isset($ed255_c_abastagua)){
    $ed255_c_abastagua = "00000";
   }
   ?>
   <fieldset style="padding:0px;"><legend><?=$Led255_c_abastagua?></legend>
    <input <?=substr(@$ed255_c_abastagua,0,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_abastagua" name="ed255_c_abastagua[]" type="checkbox" value="1" onclick="js_agua();"> Rede Pública<br>
    <input <?=substr(@$ed255_c_abastagua,1,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_abastagua" name="ed255_c_abastagua[]" type="checkbox" value="2" onclick="js_agua();"> Poço Artesiano<br>
    <input <?=substr(@$ed255_c_abastagua,2,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_abastagua" name="ed255_c_abastagua[]" type="checkbox" value="3" onclick="js_agua();"> Cacimba / Cisterna / Poço<br>
    <input <?=substr(@$ed255_c_abastagua,3,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_abastagua" name="ed255_c_abastagua[]" type="checkbox" value="4" onclick="js_agua();"> Fonte / Rio / Igarapé / Riacho<br>
    <input <?=substr(@$ed255_c_abastagua,4,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_abastagua" name="ed255_c_abastagua[]" type="checkbox" value="5" onclick="js_agua();"> Inexistente<br>
   </fieldset>
   <br>
   <?
   if(!isset($ed255_c_abastenergia)){
    $ed255_c_abastenergia = "0000";
   }
   ?>   
   <fieldset style="padding:0px;height:96px;"><legend><?=$Led255_c_abastenergia?></legend>
    <input <?=substr(@$ed255_c_abastenergia,0,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_abastenergia" name="ed255_c_abastenergia[]" type="checkbox" value="1" onclick="js_energia();"> Rede Pública<br>
    <input <?=substr(@$ed255_c_abastenergia,1,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_abastenergia" name="ed255_c_abastenergia[]" type="checkbox" value="2" onclick="js_energia();"> Gerador<br>
    <input <?=substr(@$ed255_c_abastenergia,2,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_abastenergia" name="ed255_c_abastenergia[]" type="checkbox" value="3" onclick="js_energia();"> Outros (Energia Alternativa)<br>
    <input <?=substr(@$ed255_c_abastenergia,3,1)=="1"?"checked":""?> style="height:13px;" id="ed255_c_abastenergia" name="ed255_c_abastenergia[]" type="checkbox" value="4" onclick="js_energia();"> Inexistente<br>
   </fieldset>
   <br>
   <fieldset style="height:85px;"><legend><?=$Led255_i_compartilhado?></legend>
   <?
   $x = array('0'=>'NÃO','1'=>'SIM');
   db_select('ed255_i_compartilhado',$x,true,$db_opcao," onchange='js_outraescola(this.value);' style='height:15px;font-size:10px;'");
   if(isset($ed255_i_compartilhado) && $ed255_i_compartilhado==1){
    $visible = "visible";
   }else{
    $visible = "hidden";
   }
   ?>
   <br><br>
   <span id="outraescola" style="visibility:<?=$visible?>;">
    <?=$Led255_i_escolacompartilhada?><br>
    <?db_input('ed255_i_escolacompartilhada',8,@$Ied255_i_escolacompartilhada,true,'text',$db_opcao,"")?>
   </span>
   </fieldset>
  </td>
 </tr>
 <tr>
  <td colspan="2" valign="top">
   <fieldset style="width:53%;height:130px;"><legend><?=$Led255_i_computadores?></legend>
    <table>
     <tr>
      <td>
       <?=$Led255_i_computadores?>
       <?
       $x = array('0'=>'NÃO POSSUI','1'=>'POSSUI');
       db_select('ed255_i_computadores',$x,true,$db_opcao," onchange='js_computadores(this.value);' style='height:15px;font-size:10px;'");
       if(isset($ed255_i_computadores) && $ed255_i_computadores==1){
        $visible1 = "visible";
       }else{
        $visible1 = "hidden";
       }
       ?>
       <br>
       <span id="computadores" style="visibility:<?=$visible1?>;">
        <?=$Led255_i_qtdcomp?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?db_input('ed255_i_qtdcomp',4,@$Ied255_i_qtdcomp,true,'text',$db_opcao,"")?>
        <br>
        <?=$Led255_i_qtdcompadm?>
        <?db_input('ed255_i_qtdcompadm',4,@$Ied255_i_qtdcompadm,true,'text',$db_opcao," onchange='js_qtdcomp(this.value,this,document.form1.ed255_i_qtdcompalu.value);'")?>
        <br>
        <?=$Led255_i_qtdcompalu?>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <?db_input('ed255_i_qtdcompalu',4,@$Ied255_i_qtdcompalu,true,'text',$db_opcao," onchange='js_qtdcomp(this.value,this,document.form1.ed255_i_qtdcompadm.value);'")?>
        <br>
        <?=$Led255_i_internet?>
        <input type="radio" name="ed255_i_internet" value="0" onclick='js_internet(this.value);' <?=@$ed255_i_internet=="0"?"checked":""?>> Não
        <input type="radio" name="ed255_i_internet" value="1" onclick='js_internet(this.value);' <?=@$ed255_i_internet=="1"?"checked":""?>> Sim
        <?
        if(isset($ed255_i_internet) && $ed255_i_internet==1){
         $visible2 = "visible";
        }else{
         $visible2 = "hidden";
        }
        ?>
        <br>
        <span id="bandalarga" style="visibility:<?=$visible2?>;">
        <?=$Led255_i_bandalarga?>
        <input type="radio" name="ed255_i_bandalarga" value="0" <?=@$ed255_i_bandalarga=="0"?"checked":""?>> Não Possui
        <input type="radio" name="ed255_i_bandalarga" value="1" <?=@$ed255_i_bandalarga=="1"?"checked":""?>> Possui
        </span>
       </span>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
  <td colspan="2" valign="top">
   <fieldset style="height:145px;"><legend><b>Outras Informações</b></legend>
    <table cellspacing="0">
     <tr>
      <td><?=$Led255_i_aguafiltrada?></td>
      <td>
       <?
       $x = array('1'=>'NÃO FILTRADA','2'=>'FILTRADA');
       db_select('ed255_i_aguafiltrada',$x,true,$db_opcao," style='height:15px;font-size:10px;'");
       ?>
      </td>
     </tr>
     <tr>
      <td><?=$Led255_i_alimentacao?></td>
      <td>
       <?
       $x = array('1'=>'OFERECE','0'=>'NÃO OFERECE');
       db_select('ed255_i_alimentacao',$x,true,$db_opcao," style='height:15px;font-size:10px;'");
       ?>
      </td>
     </tr>
     <tr>
      <td><?=$Led255_i_salaexistente?></td>
      <td>
       <?db_input('ed255_i_salaexistente',4,@$Ied255_i_salaexistente,true,'text',$db_opcao," onchange='js_sala();'")?>
      </td>
     </tr>
     <tr>
      <td><?=$Led255_i_salautilizada?></td>
      <td>
       <?db_input('ed255_i_salautilizada',4,@$Ied255_i_salautilizada,true,'text',$db_opcao,"")?>
      </td>
     </tr>
     <tr>
      <td><?=$Led255_i_ativcomplementar?></td>
      <td>
       <?
       $x = array('0'=>'NÃO OFERECE','1'=>'NÃO EXCLUSIVAMENTE','2'=>'EXCLUSIVAMENTE');
       db_select('ed255_i_ativcomplementar',$x,true,$db_opcao," onchange='js_ativcomp(this.value)'; style='height:15px;font-size:10px;'");
       ?>
      </td>
     </tr>
     <tr>
      <td><?=$Led255_i_aee?></td>
      <td>
       <?
       $x = array('0'=>'NÃO OFERECE','1'=>'NÃO EXCLUSIVAMENTE','2'=>'EXCLUSIVAMENTE');
       db_select('ed255_i_aee',$x,true,$db_opcao," onchange='js_aee(this.value)'; style='height:15px;font-size:10px;'");
       ?>
      </td>
     </tr>
     <tr>
      <td><?=$Led255_i_efciclos?></td>
      <td>
       <?
       $x = array('0'=>'NÃO','1'=>'SIM');
       db_select('ed255_i_efciclos',$x,true,$db_opcao," style='height:15px;font-size:10px;'");
       ?>
      </td>
     </tr>
    </table>
   </fieldset>
  </td>
 </tr>
 <tr>
  <td align="center" colspan="4">
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> onclick=" return js_valida();">
  </td>
 </tr>
</table>
</form>
<script>
function js_outraescola(valor){
 if(valor=="0"){
  document.getElementById("outraescola").style.visibility = "hidden";
  document.form1.ed255_i_escolacompartilhada.value = "";
 }else{
  document.getElementById("outraescola").style.visibility = "visible";
 }
}
function js_computadores(valor){
 if(valor=="0"){
  document.getElementById("computadores").style.visibility = "hidden";
  document.getElementById("bandalarga").style.visibility = "hidden";
  document.form1.ed255_i_qtdcomp.value = "";
  document.form1.ed255_i_qtdcompadm.value = "";
  document.form1.ed255_i_qtdcompalu.value = "";
  document.form1.ed255_i_internet[0].checked = false;
  document.form1.ed255_i_internet[1].checked = false;
  document.form1.ed255_i_bandalarga[0].checked = false;
  document.form1.ed255_i_bandalarga[1].checked = false;
 }else{
  document.getElementById("computadores").style.visibility = "visible";
 }
}
function js_internet(valor){
 if(valor=="0"){
  document.getElementById("bandalarga").style.visibility = "hidden";
  document.form1.ed255_i_bandalarga[0].checked = false;
  document.form1.ed255_i_bandalarga[1].checked = false;
 }else{
  document.getElementById("bandalarga").style.visibility = "visible";
 }
}
function js_valida(){
 tam = document.form1.ed255_c_dependencias.length;
 cont = 0;
 for(i=0;i<tam;i++){
  if(document.form1.ed255_c_dependencias[i].checked==true){
   cont++;
  }
 }
 if(cont==0){
  alert("Campo Dependências Existentes não informado!");
  return false;
 }

 tam = document.form1.ed255_c_esgotosanitario.length;
 cont = 0;
 for(i=0;i<tam;i++){
  if(document.form1.ed255_c_esgotosanitario[i].checked==true){
   cont++;
  }
 }
 if(cont==0){
  alert("Campo Esgoto Sanitario não informado!");
  return false;
 }

 tam = document.form1.ed255_c_abastagua.length;
 cont = 0;
 for(i=0;i<tam;i++){
  if(document.form1.ed255_c_abastagua[i].checked==true){
   cont++;
  }
 }
 if(cont==0){
  alert("Campo Abastecimento de Água não informado!");
  return false;
 }

 tam = document.form1.ed255_c_abastenergia.length;
 cont = 0;
 for(i=0;i<tam;i++){
  if(document.form1.ed255_c_abastenergia[i].checked==true){
   cont++;
  }
 }
 if(cont==0){
  alert("Campo Abastecimento de Energia não informado!");
  return false;
 }

 tam = document.form1.ed255_c_destinolixo.length;
 cont = 0;
 for(i=0;i<tam;i++){
  if(document.form1.ed255_c_destinolixo[i].checked==true){
   cont++;
  }
 }
 if(cont==0){
  alert("Campo Destinação do Lixo não informado!");
  return false;
 }

 tam = document.form1.ed255_c_localizacao.length;
 cont = 0;
 for(i=0;i<tam;i++){
  if(document.form1.ed255_c_localizacao[i].checked==true){
   cont++;
  }
 }
 if(cont==0){
  alert("Campo Local de Funcionamento da Escola não informado!");
  return false;
 }

 tam = document.form1.ed255_c_materdidatico.length;
 cont = 0;
 for(i=0;i<tam;i++){
  if(document.form1.ed255_c_materdidatico[i].checked==true){
   cont++;
  }
 }
 if(cont==0){
  alert("Campo Materais Didáticos Específicos não informado!");
  return false;
 }
 if(document.form1.ed255_i_salautilizada.value==""){
  alert("Campo N° de Salas Utilizadas como Sala de Aula não informado!");
  return false;
 }
 if(document.form1.ed255_i_compartilhado.value==1 && document.form1.ed255_i_escolacompartilhada.value==""){
  alert("Campo Código INEP da Outra Escola não informado!");
  document.form1.ed255_i_escolacompartilhada.style.backgroundColor='#99A9AE';
  document.form1.ed255_i_escolacompartilhada.focus();
  return false;
 }else if(document.form1.ed255_i_compartilhado.value==1 && document.form1.ed255_i_escolacompartilhada.value!=""){
  if(document.form1.ed255_i_escolacompartilhada.value.length<8){
   alert("Campo Código INEP da Outra Escola deve conter 8 dígitos!");
   document.form1.ed255_i_escolacompartilhada.style.backgroundColor='#99A9AE';
   document.form1.ed255_i_escolacompartilhada.focus();
   return false;
  }else{
   if(document.form1.ed255_i_escolacompartilhada.value.substr(0,2)!=<?=$ed18_i_censouf?>){
    alert("Campo Código INEP da Outra Escola deve começar com <?=$ed18_i_censouf?> (Código da UF da escola)!");
    document.form1.ed255_i_escolacompartilhada.style.backgroundColor='#99A9AE';
    document.form1.ed255_i_escolacompartilhada.focus();
    return false;
   }
  }
 }
 if(document.form1.ed255_i_computadores.value==1 && document.form1.ed255_i_qtdcomp.value==""){
  alert("Campo Qtde. de Computadores na Escola não informado!");
  document.form1.ed255_i_qtdcomp.style.backgroundColor='#99A9AE';
  document.form1.ed255_i_qtdcomp.focus();
  return false;
 }else if(document.form1.ed255_i_computadores.value==1 && document.form1.ed255_i_qtdcomp.value!=""){
  if(document.form1.ed255_i_qtdcomp.value==0){
   alert("Campo Qtde. de Computadores na Escola deve ser diferente de zero!");
   document.form1.ed255_i_qtdcomp.style.backgroundColor='#99A9AE';
   document.form1.ed255_i_qtdcomp.focus();
   return false;
  }
 }
 if(document.form1.ed255_i_computadores.value==1 && document.form1.ed255_i_internet[0].checked==false && document.form1.ed255_i_internet[1].checked==false){
  alert("Campo Acesso à Internet não informado!");
  return false;
 }
 if(document.form1.ed255_c_localizacao[0].checked==true && document.form1.ed255_i_salaexistente.value==""){
  alert("Campo N° de Sala de Aula Existentes na Escola deve ser informado quando Prédio Escolar(Local de Funcionamento da Escola) estiver marcado.");
  return false;
 }
 if(document.form1.ed255_c_localizacao[0].checked==false && document.form1.ed255_i_salaexistente.value!=""){
  alert("Campo N° de Sala de Aula Existentes na Escola somente deve ser informado quando Prédio Escolar(Local de Funcionamento da Escola) estiver marcado.");
  return false;
 }
 if(document.form1.ed255_i_internet[1].checked==true && document.form1.ed255_i_bandalarga[0].checked==false && document.form1.ed255_i_bandalarga[1].checked==false){
  alert("Campo Banda Larga não informado.");
  return false;
 }
 if(document.form1.ed255_i_salaexistente.value=="0"){
  alert("Campo N° de Sala de Aula Existentes na Escola deve ser diferente de zero.");
  document.form1.ed255_i_salaexistente.value = "";
  return false;
 }
 if(document.form1.ed255_i_salautilizada.value=="0"){
  alert("Campo N° de Salas Utilizadas como Sala de Aula deve ser diferente de zero.");
  document.form1.ed255_i_salautilizada.value = "";
  return false;
 }
 if(document.form1.ed255_i_ativcomplementar.value=="2" && document.form1.ed255_i_aee.value=="2"){
  alert("Campos Atividade Complementar e Atendimento AEE não podem ser marcados como EXCLUSIVAMENTE ao mesmo tempo.");
  return false;
 }
 return true;
}
function js_energia(){
 if(document.form1.ed255_c_abastenergia[3].checked==true){
  document.form1.ed255_c_abastenergia[0].disabled = true;
  document.form1.ed255_c_abastenergia[0].checked = false;
  document.form1.ed255_c_abastenergia[1].disabled = true;
  document.form1.ed255_c_abastenergia[1].checked = false;
  document.form1.ed255_c_abastenergia[2].disabled = true;
  document.form1.ed255_c_abastenergia[2].checked = false;
 }else{
  document.form1.ed255_c_abastenergia[0].disabled = false;
  document.form1.ed255_c_abastenergia[1].disabled = false;
  document.form1.ed255_c_abastenergia[2].disabled = false;
 }
}
function js_agua(){
 if(document.form1.ed255_c_abastagua[4].checked==true){
  document.form1.ed255_c_abastagua[0].disabled = true;
  document.form1.ed255_c_abastagua[0].checked = false;
  document.form1.ed255_c_abastagua[1].disabled = true;
  document.form1.ed255_c_abastagua[1].checked = false;
  document.form1.ed255_c_abastagua[2].disabled = true;
  document.form1.ed255_c_abastagua[2].checked = false;
  document.form1.ed255_c_abastagua[3].disabled = true;
  document.form1.ed255_c_abastagua[3].checked = false;
 }else{
  document.form1.ed255_c_abastagua[0].disabled = false;
  document.form1.ed255_c_abastagua[1].disabled = false;
  document.form1.ed255_c_abastagua[2].disabled = false;
  document.form1.ed255_c_abastagua[3].disabled = false;
 }
}
function js_esgoto(){
 if(document.form1.ed255_c_esgotosanitario[2].checked==true){
  document.form1.ed255_c_esgotosanitario[0].disabled = true;
  document.form1.ed255_c_esgotosanitario[0].checked = false;
  document.form1.ed255_c_esgotosanitario[1].disabled = true;
  document.form1.ed255_c_esgotosanitario[1].checked = false;
 }else{
  document.form1.ed255_c_esgotosanitario[0].disabled = false;
  document.form1.ed255_c_esgotosanitario[1].disabled = false;
 }
}
function js_dependencias(){
 if(document.form1.ed255_c_dependencias[17].checked==true){
  document.form1.ed255_c_dependencias[0].disabled = true;
  document.form1.ed255_c_dependencias[0].checked = false;
  document.form1.ed255_c_dependencias[1].disabled = true;
  document.form1.ed255_c_dependencias[1].checked = false;
  document.form1.ed255_c_dependencias[2].disabled = true;
  document.form1.ed255_c_dependencias[2].checked = false;
  document.form1.ed255_c_dependencias[3].disabled = true;
  document.form1.ed255_c_dependencias[3].checked = false;
  document.form1.ed255_c_dependencias[4].disabled = true;
  document.form1.ed255_c_dependencias[4].checked = false;
  document.form1.ed255_c_dependencias[5].disabled = true;
  document.form1.ed255_c_dependencias[5].checked = false;
  document.form1.ed255_c_dependencias[6].disabled = true;
  document.form1.ed255_c_dependencias[6].checked = false;
  document.form1.ed255_c_dependencias[7].disabled = true;
  document.form1.ed255_c_dependencias[7].checked = false;
  document.form1.ed255_c_dependencias[8].disabled = true;
  document.form1.ed255_c_dependencias[8].checked = false;
  document.form1.ed255_c_dependencias[9].disabled = true;
  document.form1.ed255_c_dependencias[9].checked = false;
  document.form1.ed255_c_dependencias[10].disabled = true;
  document.form1.ed255_c_dependencias[10].checked = false;
  document.form1.ed255_c_dependencias[11].disabled = true;
  document.form1.ed255_c_dependencias[11].checked = false;
  document.form1.ed255_c_dependencias[12].disabled = true;
  document.form1.ed255_c_dependencias[12].checked = false;
  document.form1.ed255_c_dependencias[13].disabled = true;
  document.form1.ed255_c_dependencias[13].checked = false;
  document.form1.ed255_c_dependencias[14].disabled = true;
  document.form1.ed255_c_dependencias[14].checked = false;
  document.form1.ed255_c_dependencias[15].disabled = true;
  document.form1.ed255_c_dependencias[15].checked = false;
  document.form1.ed255_c_dependencias[16].disabled = true;
  document.form1.ed255_c_dependencias[16].checked = false;
 }else{
  document.form1.ed255_c_dependencias[0].disabled = false;
  document.form1.ed255_c_dependencias[1].disabled = false;
  document.form1.ed255_c_dependencias[2].disabled = false;
  document.form1.ed255_c_dependencias[3].disabled = false;
  document.form1.ed255_c_dependencias[4].disabled = false;
  document.form1.ed255_c_dependencias[5].disabled = false;
  document.form1.ed255_c_dependencias[6].disabled = false;
  document.form1.ed255_c_dependencias[7].disabled = false;
  document.form1.ed255_c_dependencias[8].disabled = false;
  document.form1.ed255_c_dependencias[9].disabled = false;
  document.form1.ed255_c_dependencias[10].disabled = false;
  document.form1.ed255_c_dependencias[11].disabled = false;
  document.form1.ed255_c_dependencias[12].disabled = false;
  document.form1.ed255_c_dependencias[13].disabled = false;
  document.form1.ed255_c_dependencias[14].disabled = false;
  document.form1.ed255_c_dependencias[15].disabled = false;
  document.form1.ed255_c_dependencias[16].disabled = false;
 }
}
function js_mater(){
 if(document.form1.ed255_c_materdidatico[0].checked==true){
  document.form1.ed255_c_materdidatico[1].disabled = true;
  document.form1.ed255_c_materdidatico[1].checked = false;
  document.form1.ed255_c_materdidatico[2].disabled = true;
  document.form1.ed255_c_materdidatico[2].checked = false;
 }else{
  document.form1.ed255_c_materdidatico[1].disabled = false;
  document.form1.ed255_c_materdidatico[2].disabled = false;
  if(document.form1.ed255_c_materdidatico[1].checked==true){
   document.form1.ed255_c_materdidatico[2].disabled = true;
   if(document.form1.ed255_c_materdidatico[2].checked==true){
    document.form1.ed255_c_materdidatico[2].checked = false;
    alert("Opções Quilombola e Indígena não devem ser marcadas ao mesmo tempo!");
   }
  }else{
   document.form1.ed255_c_materdidatico[2].disabled = false;
  }
  if(document.form1.ed255_c_materdidatico[2].checked==true){
   document.form1.ed255_c_materdidatico[1].disabled = true;
   if(document.form1.ed255_c_materdidatico[1].checked==true){
    document.form1.ed255_c_materdidatico[1].checked = false;
    alert("Opções Quilombola e Indígena não devem ser marcadas ao mesmo tempo!");
   }
  }else{
   document.form1.ed255_c_materdidatico[1].disabled = false;
  }
 }
}
function js_qtdcomp(valor,campo,outro){
 if(document.form1.ed255_i_qtdcomp.value==""){
  alert("Informe o campo Qtde. de Computadores na Escola!");
  document.form1.ed255_i_qtdcomp.style.backgroundColor='#99A9AE';
  document.form1.ed255_i_qtdcomp.focus();
  campo.value = "";
 }else{
  if(valor==0){
   alert("Qtde. deve ser diferente de zero!");
   campo.style.backgroundColor='#99A9AE';
   campo.value = "";
   campo.focus();
   return false;
  }else{
   if((parseInt(Number(valor))+parseInt(Number(outro)))>Number(document.form1.ed255_i_qtdcomp.value)){
    alert("Soma de Computadores de Uso Administrativo + Uso de Alunos\ndeve ser menor ou igual a Qtde. de Computadores na Escola!");
    campo.style.backgroundColor='#99A9AE';
    campo.value = "";
    campo.focus();
   }
  }
 }
}
function js_sala(){
 if(document.form1.ed255_c_localizacao[0].checked==false && document.form1.ed255_i_salaexistente.value!=""){
  alert("Opção Prédio Escolar (Local de Funcionamento da Escola) deve estar marcada");
  document.form1.ed255_i_salaexistente.value = "";
 }
}
function js_ativcomp(valor){
 if(valor==2){
  document.form1.ed255_i_aee.value = 0;
 }
 if(document.form1.ed255_i_aee.value==2){
  document.form1.ed255_i_ativcomplementar.value = 0;
 }
}
function js_aee(valor){
 if(valor==2){
  document.form1.ed255_i_ativcomplementar.value = 0;
 }
 if(document.form1.ed255_i_ativcomplementar.value==2){
  document.form1.ed255_i_aee.value = 0;
 }
}
</script>