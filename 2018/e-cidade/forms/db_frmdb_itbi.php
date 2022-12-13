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

//MODULO: prefeitura
$cldb_itbi->rotulo->label();
$clrotulo = new rotulocampo();
$clrotulo->label("j14_nome");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("j40_refant");
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tj14_nome?>">
       <?=@$Lj14_nome?>
    </td>
    <td> 
<?
db_input('j14_nome',40,$Ij14_nome,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj34_setor?>">
       <?=@$Lj34_setor?>
    </td>
    <td> 
<?
db_input('j34_setor',10,$Ij34_setor,true,'text',$db_opcao,"")
?>
       <?=@$Lj34_quadra?>
<?
db_input('j34_quadra',10,$Ij34_quadra,true,'text',$db_opcao,"")
?>
       <?=@$Lj34_lote?>
<?
db_input('j34_lote',10,$Ij34_lote,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tj40_refant?>">
       <?=@$Lj40_refant?>
    </td>
    <td> 
<?
db_input('j40_refant',40,$Ij40_refant,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tmatricula?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora($Lmatricula,"js_JanelaAutomatica('iptubase','$j01_matric')",2)
       ?>
    </td>
    <td> 
<?
db_input('matricula',10,$Imatricula,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tareaterreno?>">
       <?=@$Lareaterreno?>
    </td>
    <td> 
<?
db_input('areaterreno',10,$Iareaterreno,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tareaedificada?>">
       <?=@$Lareaedificada?>
    </td>
    <td> 
<?
db_input('areaedificada',10,$Iareaedificada,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tnomecomprador?>">
       <?=@$Lnomecomprador?>
    </td>
    <td> 
<?
db_input('nomecomprador',40,$Inomecomprador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcgccpfcomprador?>">
       <?=@$Lcgccpfcomprador?>
    </td>
    <td> 
<?
db_input('cgccpfcomprador',14,$Icgccpfcomprador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tenderecocomprador?>">
       <?=@$Lenderecocomprador?>
    </td>
    <td> 
<?
db_input('enderecocomprador',40,$Ienderecocomprador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tmunicipiocomprador?>">
       <?=@$Lmunicipiocomprador?>
    </td>
    <td> 
<?
db_input('municipiocomprador',20,$Imunicipiocomprador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tbairrocomprador?>">
       <?=@$Lbairrocomprador?>
    </td>
    <td> 
<?
db_input('bairrocomprador',20,$Ibairrocomprador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcepcomprador?>">
       <?=@$Lcepcomprador?>
    </td>
    <td> 
<?
db_input('cepcomprador',8,$Icepcomprador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tufcomprador?>">
       <?=@$Lufcomprador?>
    </td>
    <td> 
<?
db_input('ufcomprador',2,$Iufcomprador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Ttipotransacao?>">
       <?=@$Ltipotransacao?>
    </td>
    <td> 
<?
db_input('tipotransacao',20,$Itipotransacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvalortransacao?>">
       <?=@$Lvalortransacao?>
    </td>
    <td> 
<?
db_input('valortransacao',10,$Ivalortransacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcaracteristicas?>">
       <?=@$Lcaracteristicas?>
    </td>
    <td> 
<?
db_input('caracteristicas',20,$Icaracteristicas,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tmfrente?>">
       <?=@$Lmfrente?>
    </td>
    <td> 
<?
db_input('mfrente',10,$Imfrente,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tmladodireito?>">
       <?=@$Lmladodireito?>
    </td>
    <td> 
<?
db_input('mladodireito',10,$Imladodireito,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tmfundos?>">
       <?=@$Lmfundos?>
    </td>
    <td> 
<?
db_input('mfundos',10,$Imfundos,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tmladoesquerdo?>">
       <?=@$Lmladoesquerdo?>
    </td>
    <td> 
<?
db_input('mladoesquerdo',10,$Imladoesquerdo,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Temail?>">
       <?=@$Lemail?>
    </td>
    <td> 
<?
db_input('email',50,$Iemail,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tobs?>">
       <?=@$Lobs?>
    </td>
    <td> 
<?
db_textarea('obs',3,50,$Iobs,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tliberado?>">
       <?=@$Lliberado?>
    </td>
    <td> 
<?
db_input('liberado',10,$Iliberado,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdatavencimento?>">
       <?=@$Ldatavencimento?>
    </td>
    <td> 
<?
db_inputdata('datavencimento',@$datavencimento_dia,@$datavencimento_mes,@$datavencimento_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Taliquota?>">
       <?=@$Laliquota?>
    </td>
    <td> 
<?
db_input('aliquota',10,$Ialiquota,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tid_itbi?>">
       <?=@$Lid_itbi?>
    </td>
    <td> 
<?
db_input('id_itbi',10,$Iid_itbi,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdataliber?>">
       <?=@$Ldataliber?>
    </td>
    <td> 
<?
db_inputdata('dataliber',@$dataliber_dia,@$dataliber_mes,@$dataliber_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvaloravaliacao?>">
       <?=@$Lvaloravaliacao?>
    </td>
    <td> 
<?
db_input('valoravaliacao',10,$Ivaloravaliacao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvalorpagamento?>">
       <?=@$Lvalorpagamento?>
    </td>
    <td> 
<?
db_input('valorpagamento',10,$Ivalorpagamento,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tobsliber?>">
       <?=@$Lobsliber?>
    </td>
    <td> 
<?
db_textarea('obsliber',3,50,$Iobsliber,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tloginn?>">
       <?=@$Lloginn?>
    </td>
    <td> 
<?
db_input('loginn',10,$Iloginn,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tnumpre?>">
       <?=@$Lnumpre?>
    </td>
    <td> 
<?
db_input('numpre',15,$Inumpre,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tdatasolicitacao?>">
       <?=@$Ldatasolicitacao?>
    </td>
    <td> 
<?
db_inputdata('datasolicitacao',@$datasolicitacao_dia,@$datasolicitacao_mes,@$datasolicitacao_ano,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tlibpref?>">
       <?=@$Llibpref?>
    </td>
    <td> 
<?
db_input('libpref',10,$Ilibpref,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvaloravterr?>">
       <?=@$Lvaloravterr?>
    </td>
    <td> 
<?
db_input('valoravterr',15,$Ivaloravterr,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tvaloravconst?>">
       <?=@$Lvaloravconst?>
    </td>
    <td> 
<?
db_input('valoravconst',15,$Ivaloravconst,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tnumerocomprador?>">
       <?=@$Lnumerocomprador?>
    </td>
    <td> 
<?
db_input('numerocomprador',10,$Inumerocomprador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcomplcomprador?>">
       <?=@$Lcomplcomprador?>
    </td>
    <td> 
<?
db_input('complcomprador',20,$Icomplcomprador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tcxpostcomprador?>">
       <?=@$Lcxpostcomprador?>
    </td>
    <td> 
<?
db_input('cxpostcomprador',20,$Icxpostcomprador,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  </table>
  </center>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>