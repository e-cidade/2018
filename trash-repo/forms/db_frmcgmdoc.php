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

//MODULO: protocolo
$clcgm->rotulo->label();
$clcgmdoc->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<form name="form1" method="post" action="">
<center>
<table width="95%" border="0">
 <tr>
 
     <tr>
     <td nowrap title="<?=@$Tz02_i_sequencial?>" align="right">
      <?=@$Lz02_i_sequencial?>
     </td>
     <td>
      <?db_input('z02_i_sequencial',10,$Iz02_i_sequencial,true,'text',3,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tz02_i_cgm?>" align="right">
      <?db_ancora(@$Lz02_i_cgm,"js_pesquisaz02_i_cgm(true);",3);?>
     </td>
     <td>
      <?db_input('z02_i_cgm',10,$Iz02_i_cgm,true,'text',3,"")?>
      <?db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')?>
     </td>
    </tr>
 
   <? /* Dados Gerais */ ?>
   <tr>
     <td width="45%" valign="top">
       <fieldset style="width:95%"><legend><b>Dados Gerais</b></legend>
       <table width="100%" border="0">
         <tr>
          <td nowrap width="30%" title="<?=@$Tz02_i_pis?>">
           <?=@$Lz02_i_pis?>
          </td>
          <td>
           <?db_input('z02_i_pis',11,$Iz02_i_pis,true,'text',$db_opcao,"")?>
<!--          </td>
          <td nowrap title="<?=@$Tz02_i_cns?>">
-->
           <?=@$Lz02_i_cns?>
<!--          </td>
          <td>
-->
           <?db_input('z02_i_cns',15,$Iz02_i_cns,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_c_naturalidade?>">
           <?=@$Lz02_c_naturalidade?>
          </td>
          <td>
           <?db_input('z02_c_naturalidade',40,$Iz02_c_naturalidade,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_c_naturalidadeuf?>">
           <?=@$Lz02_c_naturalidadeuf?>
          </td>
          <td>
           <?db_input('z02_c_naturalidadeuf',2,$Iz02_c_naturalidadeuf,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_c_pais?>">
           <?=@$Lz02_c_pais?>
          </td>
          <td>
           <?db_input('z02_c_pais',40,$Iz02_c_pais,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_d_dataentrada?>">
           <?=@$Lz02_d_dataentrada?>
          </td>
          <td>
           <?db_inputdata('z02_d_dataentrada',@$z02_d_dataentrada_dia,@$z02_d_dataentrada_mes,@$z02_d_dataentrada_ano,true,'text',$db_opcao,"")?>
          </td>
         </tr>

         <tr>
          <td nowrap title="<?=@$Tz02_i_escolaridade?>">
           <?=@$Lz02_i_escolaridade?>
          </td>
          <td>
           <?
           $x = array('1'=>'NÃO SABE LER / ESCREVER','10'=>'MESTRADO','11'=>'DOUTORADO','2'=>'ALFABETIZADO','3'=>'1° GRAU INCOMPLETO','4'=>'1° GRAU COMPLETO','5'=>'2° GRAU INCOMPLETO','6'=>'2° GRAU COMPLETO','7'=>'SUPERIOR INCOMPLETO','8'=>'SUPERIOR COMPLETO','9'=>'ESPECIALIZAÇÃO / RESIDÊNCIA');
           db_select('z02_i_escolaridade',$x,true,$db_opcao,"");
           ?>
          </td>
         </tr>
         </table>
         </fieldset>
     </td>
     <td valign="top">
        <fieldset style="width:95%"><legend><b>Certidão</b></legend>
        <table width="100%" border="0">
         <tr>
          <td nowrap width="25%" title="<?=@$Tz02_i_certidaotipo?>">
           <?=@$Lz02_i_certidaotipo?>
          </td>
          <td>
           <?
           $x = array('91'=>'CERTIDÃO DE NASCIMENTO','92'=>'CERTIDÃO DE CASAMENTO','93'=>'CERTIDÃO DE SEPARAÇÃO / DIVÓRCIO','94'=>'CERTIDÃO DE SEPARAÇÃO JUDICIAL');
           db_select('z02_i_certidaotipo',$x,true,$db_opcao,"");
           ?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_c_certidaocartorio?>">
           <?=@$Lz02_c_certidaocartorio?>
          </td>
          <td>
           <?db_input('z02_c_certidaocartorio',50,$Iz02_c_certidaocartorio,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_c_certidaolivro?>">
           <?=@$Lz02_c_certidaolivro?>
          </td>
          <td>
           <?db_input('z02_c_certidaolivro',20,$Iz02_c_certidaolivro,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_c_folha?>">
           <?=@$Lz02_c_folha?>
          </td>
          <td>
           <?db_input('z02_c_folha',20,$Iz02_c_folha,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_c_termo?>">
           <?=@$Lz02_c_termo?>
          </td>
          <td>
           <?db_input('z02_c_termo',20,$Iz02_c_termo,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_d_certidaodata?>">
           <?=@$Lz02_d_certidaodata?>
          </td>
          <td>
           <?db_inputdata('z02_d_certidaodata',@$z02_d_certidaodata_dia,@$z02_d_certidaodata_mes,@$z02_d_certidaodata_ano,true,'text',$db_opcao,"")?>
          </td>
         </tr>
        </table>
   </fieldset>
    </td>
   </tr>

    
   <? /* Dados Bancários */ ?>
   <tr>
     <td valign="top">
       <fieldset style="width:95%"><legend><b>Dados Bancários</b></legend>
       <table width="100%" border="0">
         <tr>
          <td nowrap width="31%" title="<?=@$Tz02_c_banco?>">
           <?=@$Lz02_c_banco?>
          </td>
          <td>
           <?db_input('z02_c_banco',30,$Iz02_c_banco,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_c_agencia?>">
           <?=@$Lz02_c_agencia?>
          </td>
          <td>
           <?db_input('z02_c_agencia',10,$Iz02_c_agencia,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_c_contacorrente?>">
           <b>Conta Corrente:  </b>
          </td>
          <td>
           <?db_input('z02_c_contacorrente',30,$Iz02_c_contacorrente,true,'text',$db_opcao,"")?>
          </td>
         </tr>
       </table>
       </fieldset>
      </td>
      <? /* Dados Identidade */ ?>
      <td valign="top">
        <fieldset style="width:95%"><legend><b>Identidade</b></legend>
        <table width="100%" border="0">
         <tr>
          <td nowrap width="27%" title="<?=@$Tz02_c_identorgao?>">
           <?=@$Lz02_c_identorgao?>
          </td>
          <td>
           <?db_input('z02_c_identorgao',50,$Iz02_c_identorgao,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_d_identdata?>">
           <b>Expedido em:</b>
          </td>
          <td>
           <?db_inputdata('z02_d_identdata',@$z02_d_identdata_dia,@$z02_d_identdata_mes,@$z02_d_identdata_ano,true,'text',$db_opcao,"")?>
          </td>
         </tr>
         <tr>
          <td nowrap title="<?=@$Tz02_c_identuf?>">
           <?=@$Lz02_c_identuf?>
          </td>
          <td>
           <?db_input('z02_c_identuf',2,$Iz02_c_identuf,true,'text',$db_opcao,"")?>
          </td>
         </tr>
        </table>
        </fieldset>
     </td>
   </tr>
  </td>
  
  <? /* Dados CNH */ ?>
  <td valign="top">
   <fieldset style="width:95%"><legend><b>CNH</b></legend>
   <table width="100%" border="0">
    <tr>
     <td nowrap  width="30%" title=<?=@$Tz01_cnh?>>
      <?=@$Lz01_cnh?>
     </td>
     <td nowrap title="<?=@$Tz01_cnh?>">
      <?db_input('z01_cnh',15,$Iz01_cnh,true,'text',3,"");?>
      <?=@$Lz01_categoria?>
      <?
      $y = array(""=>"","A"=>"A","B"=>"B","C"=>"C","D"=>"D","E"=>"E","AB"=>"AB","AC"=>"AC","AD"=>"AD","AE"=>"AE");
      db_select('z01_categoria',$y,true,3);
      ?>
     </td>
    </tr>
    <tr>
     <td nowrap title=<?=@$Tz01_dtemissao?>>
      <?=@$Lz01_dtemissao?>
     </td>
     <td nowrap title="<?=@$Tz01_dtemissao?>">
      <?db_inputdata('z01_dtemissao',@$z01_dtemissao_dia,@$z01_dtemissao_mes,@$z01_dtemissao_ano,true,'text',3);?>
     </td>
    </tr>
    <tr>
     <td nowrap title=<?=@$Tz01_dthabilitacao?>>
      <?=@$Lz01_dthabilitacao?>
     </td>
     <td nowrap title="<?=@$Tz01_dthabilitacao?>">
      <?db_inputdata('z01_dthabilitacao',@$z01_dthabilitacao_dia,@$z01_dthabilitacao_mes,@$z01_dthabilitacao_ano,true,'text',3);?>
     </td>
    </tr>
    <tr>
     <td nowrap title=<?=@$Tz01_dtvencimento?>>
      <?=@$Lz01_dtvencimento?>
     </td>
     <td nowrap title="<?=@$Tz01_dtvencimento?>">
      <?db_inputdata('z01_dtvencimento',@$z01_dtvencimento_dia,@$z01_dtvencimento_mes,@$z01_dtvencimento_ano,true,'text',3);?>
     </td>
    </tr>
   </table>
   </fieldset>
  </td>
  
  <? /* Dados CTPS */ ?>
  <td valign="top">
   <fieldset style="width:95%"><legend><b>CTPS</b></legend>
   <table width="100%" border="0">
    <tr>
     <td nowrap width="27%" title="<?=@$Tz02_c_ctpsnum?>">
      <?=@$Lz02_c_ctpsnum?>
     </td>
     <td>
      <?db_input('z02_c_ctpsnum',20,$Iz02_c_ctpsnum,true,'text',$db_opcao,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tz02_c_ctpsserie?>">
      <?=@$Lz02_c_ctpsserie?>
     </td>
     <td>
      <?db_input('z02_c_ctpsserie',10,$Iz02_c_ctpsserie,true,'text',$db_opcao,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tz02_c_ctpsuf?>">
      <?=@$Lz02_c_ctpsuf?>
     </td>
     <td>
      <?db_input('z02_c_ctpsuf',2,$Iz02_c_ctpsuf,true,'text',$db_opcao,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap title="<?=@$Tz02_d_ctpsdata?>">
      <b>CTPS Emitido em:</b>
     </td>
     <td>
      <?db_inputdata('z02_d_ctpsdata',@$z02_d_ctpsdata_dia,@$z02_d_ctpsdata_mes,@$z02_d_ctpsdata_ano,true,'text',$db_opcao,"")?>
     </td>
    </tr>
   </table>
   </fieldset>
  </td>
 </tr>
</table>
<br>
<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
</center>
</form>