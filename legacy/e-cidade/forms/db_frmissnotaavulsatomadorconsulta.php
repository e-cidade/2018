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

//MODULO: issqn
$clissnotaavulsatomador->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("q51_numnota");
$clrotulo->label("q51_dtemiss");
$clrotulo->label("q54_inscr");
$clrotulo->label("q02_inscr");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_cgccpf");
$clrotulo->label("z01_bairro");
$clrotulo->label("z01_munic");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_ender");
$clrotulo->label("z01_numero");
$clrotulo->label("z01_cep");
$clrotulo->label("z01_uf");
$clrotulo->label("z01_telef");
$clrotulo->label("z01_email");
$db_opcao=3;
?>
 <table border='0'>
   <tr>
    <td nowrap title="<?=@$Tz01_cgccpf?>">
       <?=@$Lz01_cgccpf?>
    </td>
    <td colspan='2'> 
      <?
       db_input('q53_cgccpf',17,$Iz01_cgccpf,true,'text',$db_opcao,"onblur='document.form1.submit()'")
      ?>
     </td>
		 </tr>
		 <tr>
     <td nowrap title="<?=@$Tq54_inscr?>">
       <?
       db_ancora(@$Lq54_inscr,"js_pesquisaq52_inscr(true);",$db_opcao);
       ?>
    </td>
    <td> 
      <?
       db_input('q54_inscr',10,$Iq54_inscr,true,'text',$db_opcao," onchange='js_pesquisaq52_inscr(false);'")
      ?>
    </td>
		</tr>
   <tr>
    <td nowrap title="<?=@$Tz01_nome?>">
    <?
		db_ancora(@$Lz01_nome,"js_pesquisa_tomador(true);",$db_opcao);
		?>
    </td>
    <td colspan='5'> 
   <?
   db_input('q61_numcgm',70,'',true,'hidden',3,"");
   db_input('q53_nome',70,$Iz01_nome,true,'text',3,"")
   ?>
    </td>
    </tr>
  <tr>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_ender?>">
       <?=@$Lz01_ender?>
    </td>
    <td colspan='2'> 
    <?
     db_input('q53_endereco',50,$Iz01_ender,true,'text',3,"")
    ?>
    </td>
    <td nowrap title="<?=@$Tz01_numero?>">
       <?=@$Lz01_numero?>
    </td>
    <td> 
    <? 
     db_input('q53_numero',15,$Iz01_numero,true,'text',3,"")
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_bairro?>">
       <?=@$Lz01_bairro?>
    </td>
    <td colspan='4'> 
       <?
        db_input('q53_bairro',50,$Iz01_bairro,true,'text',3,"")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_munic?>">
       <?=@$Lz01_munic?>
    </td>
    <td colspan='4'> 
       <?
        db_input('q53_municipio',50,$Iz01_munic,true,'text',3,"")
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_uf?>">
       <?=@$Lz01_uf?>
    </td>
    <td colspan='2'> 
<?
db_input('q53_uf',2,$Iz01_uf,true,'text',3,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_cep?>">
       <?=@$Lz01_cep?>
    </td>
    <td> 
    <?
     db_input('q53_cep',8,$Iz01_cep,true,'text',3,"")
   ?>
    </td>
    <td nowrap title="<?=@$Tz01_email?>">
       <?=@$Lz01_email?>
    </td>
    <td colspan='2'> 
    <?
     db_input('q53_email',60,$Iz01_email,true,'text',3,"")
    ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tz01_telef?>">
       <?=@$Lz01_telef?>
    </td>
    <td> 
     <?
      db_input('q53_fone',15,$Iz01_telef,true,'text',3,"")
     ?>
    </td>
    <td nowrap title="<?=@$Tq53_dtservico?>">
       <?=@$Lq53_dtservico?>
    </td>
    <td colspan='3'> 
    <?
    db_inputdata('q53_dtservico',@$q53_dtservico_dia,@$q53_dtservico_mes,@$q53_dtservico_ano,true,'text',$db_opcao,"")
    ?>
    </td>
   </tr>
  </table>
	</fieldset>
	<td></tr>
	</table>