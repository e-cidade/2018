<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

$config = db_query("select nomeinst,logo,tx_banc from db_config where codigo = ".db_getsession("DB_instit"));
db_fieldsmemory($config,0);
?>
<table width="630" height="55">
  <tr> 
    <td width="17%" align="center"><a href="" onClick="print();return false" title="Clique aqui para imprimir">
	<img border="0" src="imagens/<?=@$logo?>" width="49" height="44"></a></td>
    <td width="83%" align="center"><font color="#000000" size="4">
	<strong><?=$nomeinst?></strong></font> <br>
        <font color="#000099" size="3">Guia de Recolhimento de ITBI N&uacute;mero 
        : <?=$itbi?></font></td>
  </tr>
</table>

<table width="630" border="1" cellspacing="0" cellpadding="0">
  <tr> 
    <td colspan="2" nowrap><strong>&nbsp;Indentifica&ccedil;&atilde;o do Transmitente:</strong></td>
  </tr>
  <tr> 
    <td nowrap>&nbsp;CNPJ:&nbsp; 
      <?=$z01_cgccpf?>
    </td>
    <td nowrap>&nbsp;Nome:&nbsp; 
      <?=$z01_nome?>
    </td>
  </tr>
  <tr> 
    <td width="158" nowrap>&nbsp;Matr&iacute;cula:&nbsp; 
      <?=$matricula?>
    </td>
    <td width="466" nowrap>&nbsp;Refer&ecirc;ncia Anterior:&nbsp; 
      <?=$j40_refant?>
    </td>
  </tr>
  <tr> 
    <td nowrap>&nbsp;Lote:&nbsp; 
      <?=$j34_lote?>
    </td>
    <td nowrap>&nbsp;Bairro:&nbsp; 
      <?=$j34_bairro?>
    </td>
  </tr>
  <tr>
    <td nowrap>&nbsp;Quadra:&nbsp; 
      <?=$j34_quadra?>
    </td>
    <td nowrap> &nbsp;Logradouro:&nbsp; 
      <?=$j14_nome?>
    </td>
  </tr>
</table>
<table width="630" border="1" cellpadding="0" cellspacing="0">
  <tr valign="top"> 
    <td colspan="2" nowrap><b>&nbsp;Identifica&ccedil;&atilde;o do Comprador:</b></td>
  </tr>
  <tr> 
    <td width="48%" valign="top" nowrap>&nbsp;Nome:&nbsp; 
      <?=$nomecomprador?>
    </td>
    <td width="52%" nowrap>&nbsp;CNPJ /CPF:&nbsp; 
      <?=$cgccpfcomprador?>
    </td>
  </tr>
  <tr> 
    <td nowrap>&nbsp;Endere&ccedil;o:&nbsp; 
      <?=$enderecocomprador?>
      <br> </td>
    <td nowrap>&nbsp;Bairro:&nbsp; 
      <?=$bairrocomprador?>
    </td>
  </tr>
  <tr> 
    <td nowrap style="border-bottom-style: none">&nbsp;Munic&iacute;pio:&nbsp; 
      <?=@$municipiocomprador?>
      <br> </td>
    <td nowrap style="border-bottom-style: none">&nbsp;CEP: &nbsp; 
      <?=$cepcomprador?>
      Estado: 
      <?=$ufcomprador?>
    </td>
  </tr>
</table>
<table width="630" border="1" cellpadding="0" cellspacing="0">
  <tr> 
    <td colspan="4" nowrap><strong>&nbsp;Dados da Transa&ccedil;&atilde;o:</strong></td>
  </tr>
  <tr> 
    <td width="25%" nowrap>&nbsp;Territorial: </td>
    <td width="24%" nowrap> &nbsp; 
      <?=number_format($areaterreno,2,".","")?>
      &nbsp; <font size="1">m2</font> </td>
    <td colspan="2" nowrap><b>&nbsp;Medidas do Im&oacute;vel:</b> </td>
  </tr>
  <tr> 
    <td nowrap>&nbsp;Predial:</td>
    <td nowrap> &nbsp; 
      <?=number_format($areaedificada,2,".","")?>
      &nbsp; <font size="1">m2</font> </td>
    <td width="22%" nowrap>&nbsp;Frente:</td>
    <td width="29%" nowrap> &nbsp; 
      <?=number_format($mfrente,2,".","")?>
      &nbsp; <font size="1">m2</font></td>
  </tr>
  <tr> 
    <td nowrap>&nbsp;Tipo de Transa&ccedil;&atilde;o:</td>
    <td nowrap>&nbsp; 
      <?=$tipotransacao?>
    </td>
    <td nowrap>&nbsp;Lado Direito:</td>
    <td nowrap>&nbsp; 
      <?=number_format($mladodireito,2,".","")?>
      &nbsp; <font size="1">m2</font></td>
  </tr>
  <tr> 
    <td nowrap>&nbsp;Valor da Transa&ccedil;&atilde;o:</td>
    <td nowrap>&nbsp; 
      <?=number_format($valortransacao,2,".",",")?>
    </td>
    <td nowrap>&nbsp;Fundos:</td>
    <td nowrap>&nbsp; 
      <?=number_format($mfundos,2,".","")?>
      &nbsp; <font size="1">m2</font></td>
  </tr>
  <tr> 
    <td nowrap>&nbsp;Valor da Avalia&ccedil;&atilde;o:</td>
    <td nowrap>&nbsp; 
      <?=number_format($valoravaliacao,2,".",",")?>
    </td>
    <td nowrap>&nbsp;Lado Esquerdo:</td>
    <td nowrap>&nbsp; 
      <?=number_format($mladoesquerdo,2,".","")?>
      &nbsp; <font size="1">m2</font></td>
  </tr>
  <tr> 
    <td colspan="4" nowrap><b>&nbsp;Características:</b>&nbsp;&nbsp;</td>
  </tr>
  <tr> 
    <td colspan="4"> 
      <Table border="0" cellpadding="0" cellspacing="0">
        <?
    $CAR = db_query($conn,"select c.descricao,i.area 
                    from db_caritbi c,db_caritbilan i
                    where c.codigo = i.codigo
                    and i.area <> 0
                    and i.id_itbi = $itbi");
    for($i = 0;$i < pg_numrows($CAR);$i += 4) {
	  $des1 = trim(@pg_result($CAR,$i,"descricao"));
	  $are1 = trim(@pg_result($CAR,$i,"area"));
      $des2 = trim(@pg_result($CAR,($i+1),"descricao"));
	  $are2 = trim(@pg_result($CAR,($i+1),"area"));
	  
  	  $des3 = trim(@pg_result($CAR,($i+2),"descricao"));
	  $are3 = trim(@pg_result($CAR,($i+2),"area"));
      $des4 = trim(@pg_result($CAR,($i+3),"descricao"));
	  $are4 = trim(@pg_result($CAR,($i+3),"area"));
	  
      ?>
        <TR> 
          <TD style="border: none"> 
            <?=($des1 != ""?"$des1:":"")?>
            &nbsp;</TD>
          <td style="border: none"> 
            <?=($are1 != ""?number_format($are1,2,".",","):"")?>
            &nbsp;</td>
          <TD style="border: none"> 
            <?=($des2 != ""?"$des2:":"")?>
            &nbsp;</TD>
          <td style="border: none"> 
            <?=($are2 != ""?number_format($are2,2,".",","):"")?>
            &nbsp;</td>
          <TD style="border: none"> 
            <?=($des3 != ""?"$des3:":"")?>
            &nbsp;</TD>
          <td style="border: none"> 
            <?=($are3 != ""?number_format($are3,2,".",","):"")?>
            &nbsp;</td>
          <TD style="border: none"> 
            <?=($des4 != ""?"$des4:":"")?>
            &nbsp;</TD>
          <td style="border: none"> 
            <?=($are4 != ""?number_format($are4,2,".",","):"")?>
            &nbsp;</td>
        </TR>
        <?
    }
    ?>
      </table></td>
  </tr>
</table>
<table width="630" border="1" cellpadding="0" cellspacing="0">
<tr>
    <td><strong>&nbsp;Observações:</strong></td>
</tr>
  <tr> 
    <td style="font-size:10px"><?=str_replace("\n","<br>",$obsliber)?></td>
</tr>
</table>
<table width="630" border="1" cellpadding="0" cellspacing="0">
<tr>
    <td nowrap>&nbsp;Vencimento:&nbsp; 
      <?=@$datavenctochar?>
    </td>
    <td nowrap>&nbsp;C&oacute;digo da Arrecada&ccedil;&atilde;o:&nbsp; 
      <?=@$k03_numpre?>
    </td>
    <td nowrap>&nbsp;Valor apagar:&nbsp; 
      <?=number_format(($valorpagamento + $tx_banc),2,".",",")?>
    </td>	
</tr>
</table>