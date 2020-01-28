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
$clcgm->rotulo->label();
$clitbi->rotulo->label();
$clitbimatric->rotulo->label();
$clitburbano->rotulo->label();
$clitbilogin->rotulo->label();
$clitbicgm->rotulo->label();
$clitbiavalia->rotulo->label();
$clrotulo = new rotulocampo();
$clrotulo->label("j14_nome");
$clrotulo->label("j34_setor");
$clrotulo->label("j34_quadra");
$clrotulo->label("j34_lote");
$clrotulo->label("j40_refant");
$clrotulo->label("it15_numpre");
$clrotulo->label("it09_caract");
$clrotulo->label("it04_descr");
?>
<form name="form1" method="post" action="">
<center>

<table border="0" width="50%">
  <tr>
    <td> </td>
  </tr>
</table>

<center> <b> Transmitente(s) : </b> </center>
<hr width="75%" >
<? 
 //select para pegar os tranmitentes
 $sql  = " select case when it21_numcgm is not null then it21_numcgm::text else 'Sem CGM cadastrado' end as it21_numcgm, ";
 $sql .= "        it03_nome,";
 $sql .= "        it03_cpfcnpj,";
 $sql .= "        case when it03_princ is true then 'Principal' else 'Secundario' end as it03_princ ";
 $sql .= "   from itbinome ";
 $sql .= "        left join itbinomecgm on it21_itbinome = it03_seq ";
 $sql .= " where it03_guia = $it01_guia and upper(it03_tipo) = 'T' ";
 $sql .= " order by it03_princ ";
 $rsItbinome = $clitbinome->sql_record($sql);
 $intNumrows = $clitbinome->numrows;
 echo "<table width='60%'  class='tab1' >";
 echo "<tr><th>CGM</th> <th>NOME</th> <th>CPF/CNPJ</th> <th>TIPO</th>   </tr>";
 if($intNumrows > 0){
   for($i = 0 ; $i < $intNumrows; $i++){
     db_fieldsmemory($rsItbinome,$i);  
     echo " <tr> ";
     echo "   <td width='10%' > ";
     echo "     $it21_numcgm ";
     echo "   </td>";
     echo "   <td width='50%'> ";
     echo "     $it03_nome";  
     echo "   </td> ";
     echo "   <td> ";
     echo "     ".(strlen($it03_cpfcnpj)>11?db_formatar($it03_cpfcnpj,'cnpj'):db_formatar($it03_cpfcnpj,'cpf'));
     echo "   </td> ";
     echo "   <td> ";
     echo "     $it03_princ";
     echo "   </td> ";
     echo " </tr>";
   }
 }else{
     echo " <tr> ";
     echo "   <td colspan='4' > ";
     echo "     <center><b> Sem adquirente cadastrado </b></center>";
     echo "   </td>";
     echo " </tr>";
   
 }
 echo "</table >";
 
?>
<center> <b> Adquirente(s) : </b> </center>
<hr width="75%" >
<?
 $sql  = " select case when it21_numcgm is not null then it21_numcgm::text else 'Sem CGM cadastrado' end as it21_numcgm, ";
 $sql .= "        it03_nome,";
 $sql .= "        it03_cpfcnpj,";
 $sql .= "        case when it03_princ is true then 'Principal' else 'Secundario' end as it03_princ ";
 $sql .= "   from itbinome ";
 $sql .= "        left join itbinomecgm on it21_itbinome = it03_seq ";
 $sql .= " where it03_guia = $it01_guia and upper(it03_tipo) = 'C' ";
 $sql .= " order by it03_princ ";
 $rsItbinome = $clitbinome->sql_record($sql);
 $intNumrows = $clitbinome->numrows;
 echo "<table width='60%'  class='tab1' >";
 echo "<tr><th>CGM</th> <th>NOME</th> <th>CPF/CNPJ</th> <th>TIPO</th>   </tr>";
 if($intNumrows > 0){
   for($i = 0 ; $i < $intNumrows; $i++){
     db_fieldsmemory($rsItbinome,$i);  
     echo " <tr> ";
     echo "   <td width='10%' > ";
     echo "     $it21_numcgm ";
     echo "   </td>";
     echo "   <td  width='50%'> ";
     echo "     $it03_nome";  
     echo "   </td> ";
     echo "   <td> ";
//     echo "     ".db_formatar($it03_cpfcnpj,'cpf');
     echo "     ".(strlen($it03_cpfcnpj)>11?db_formatar($it03_cpfcnpj,'cnpj'):db_formatar($it03_cpfcnpj,'cpf'));
     echo "   </td> ";
     echo "   <td> ";
     echo "     $it03_princ";
     echo "   </td> ";
     echo " </tr>";
   }
 }else{
     echo " <tr> ";
     echo "   <td colspan='4' > ";
     echo "    <center><b> Sem adquirente cadastrado </b></center>";
     echo "   </td>";
     echo " </tr>";
   
 }
 echo "</table >";
?>

<center> <b> Dados do Imóvel : </b> </center>
<hr width="75%" >

<table width="60%"  class="tab1" >
  <tr>
    <th align="right"  nowrap title="<?=@$Tj14_nome?>" width="35%" >
       <?=@$Lj14_nome?>
    </th>
    <td> 
     <?=@$it22_descrlograd?>
    </td>
  </tr>
  <tr>
    <th align="right"  nowrap title="<?=@$Tj40_refant?>" bgcolor="#CCCCCC"  >
       <?=@$Lj40_refant?>
    </th>
    <td bgcolor="#CCCCCC"> 
    <?=@$j40_refant?>
    </td>
  </tr>
  <tr>
    <th align="right"  nowrap title="<?=@$Tit06_matric?>">
    <input name="oid" type="hidden" value="<?=@$oid?>">
       <?
       db_ancora($Lit06_matric,"js_JanelaAutomatica('iptubase','".@$it06_matric."')",2)
       ?>
    </th>
    <td> 
     <?=@$it06_matric?>
    </td>
  </tr>
  <tr>
    <th align="right"  nowrap title="<?=@$Tit01_areaterreno?>">
       <?=@$Lit01_areaterreno?>
    </th>
    <td> 
     <?=@($it01_areaterreno>0?db_formatar($it01_areaterreno,'f'):"")?>
    </td>
  </tr>
  <tr>
    <th align="right"  nowrap title="<?=@$Tit01_areaedificada?>">
       <?=@$Lit01_areaedificada?>
    </th>
    <td> 
    <?=@($it01_areaedificada>0?db_formatar($it01_areaedificada,'f'):"")?>
    </td>
  </tr>
</table>

<center> <b> Dados da ITBI : </b> </center>
<hr width="75%" >

<table border="0" width="60%" class="tab1" >
  <tr>
    <th align="right"  nowrap title="<?=@$Tit01_guia?>">
       <?=@$Lit01_guia?>
    </th>
    <td colspan=7> 
    <?=@$it01_guia?>
    </td>
  </tr>
  <tr>
    <th align="right"  nowrap title="<?=@$Tit01_tipotransacao?>" width="35%">
       <?=@$Lit01_tipotransacao?>
    </th>
    <td colspan=7> 
    <?=@$it04_descr?>
    </td>
  </tr>
  <tr>
    <th align="right"  nowrap title="<?=@$Tit01_valortransacao?>">
       <?=@$Lit01_valortransacao?>
    </th>
    <td colspan=7> 
    <?=@($it01_valortransacao>0?db_formatar($it01_valortransacao,'f'):"")?>
    </td>
  </tr>
  <tr>
    <th align="right"  nowrap title="<?=@$Tit09_caract?>">
       <?=@$Lit09_caract?>
    </th>
    <td colspan=7> 
    <?=@$it09_caract?>
    </td>
  </tr>

  <tr>
    <th align="right"  nowrap title="<?=@$Tit05_frente?>">
       <?=@$Lit05_frente?>
    </th>
    <td> 
    <?=@($it05_frente>0?db_formatar($it05_frente,'f'):"")?>
    </td>
    <th align="right"  nowrap title="<?=@$Tit05_direito?>">
       <?=@$Lit05_direito?>
    </th>
    <td> 
    <?=@($it05_direito>0?db_formatar($it05_direito,'f'):"")?>
    </td>
    <th align="right"  nowrap title="<?=@$Tit05_fundos?>">
       <?=@$Lit05_fundos?>
    </th>
    <td> 
    <?=@($it05_fundos>0?db_formatar($it05_fundos,'f'):"")?>
    </td>
    <th align="right"  nowrap title="<?=@$Tit05_esquerdo?>">
       <?=@$Lit05_esquerdo?>
    </th>
    <td> 
    <?=@($it05_esquerdo>0?db_formatar($it05_esquerdo,'f'):"")?>
    </td>
  </tr>

  <tr>
    <th align="right"  nowrap title="<?=@$Tit01_mail?>">
       <?=@$Lit01_mail?>
    </th>
    <td colspan=7> 
    <?=@$it01_mail?>
    </td>
  </tr>
  <tr>
    <th align="right"  nowrap title="<?=@$Tit01_obs?>">
       <?=@$Lit01_obs?>
    </th>
    <td colspan=7> 
    <?=@$it01_obs?>
    </td>
  </tr>
  <tr>
    <th align="right"  nowrap title="<?=@$Tit14_dtliber?>">
       <?=@$Lit14_dtliber?>
    </th>
    <td> 
    <?=(isset($it14_dtliber_dia)?$it14_dtliber_dia."/":"").(isset($it14_dtliber_mes)?$it14_dtliber_mes."/":"").(isset($it14_dtliber_ano)?$it14_dtliber_ano:"")?>
    </td>
    <th align="right"  nowrap title="<?=@$Tit14_dtvenc?>">
       <?=@$Lit14_dtvenc?>
    </th>
    <td> 
    <?=(isset($it14_dtvenc_dia)?$it14_dtvenc_dia."/":"").(isset($it14_dtvenc_mes)?$it14_dtvenc_mes."/":"").(isset($it14_dtvenc_ano)?$it14_dtvenc_ano:"")?>
    </td>
  </tr>



  <tr>
    <th align="right"  nowrap title="<?=@$Tit14_aliquota?>">
       <?=@$Lit14_aliquota?>
    </th>
    <td> 
    <?=@($it14_aliquota>0?$it14_aliquota."%":"")?>
    </td>
    <th align="right"  nowrap title="<?=@$Tit14_valoraval?>">
       <?=@$Lit14_valoraval?>
    </th>
    <td> 
    <?=@db_formatar($it14_valoraval,'f')?>
    </td>
    <th align="right"  nowrap title="<?=@$Tit14_valorpaga?>">
       <?=@$Lit14_valorpaga?>
    </th>
    <td> 
    <?=@db_formatar($it14_valorpaga,'f')?>
    </td>
  </tr>
  </table>
  </center>

<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>