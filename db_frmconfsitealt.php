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
$clconfsite->rotulo->label();
$tambordas = array("0px"=>"0px","1px"=>"1px","2px"=>"2px","3px"=>"3px","4px"=>"4px","5px"=>"5px");
$estilobordas = array("none"=>"none","dashed"=>"dashed","solid"=>"solid","inset"=>"inset","outset"=>"outset","double"=>"double");
$fontes = array("Arial, Helvetica, sans-serif"=>"Arial","Times New Roman, Times, serif"=>"Times","Courier New, Courier, mono"=>"Courier","Georgia, Times New Roman, Times, serif"=>"Georgia","Verdana, Arial, Helvetica, sans-serif"=>"Verdana","Geneva, Arial, Helvetica, san-serif"=>"Geneva");
$tamfontes = array("8px"=>"8px","9px"=>"9px","10px"=>"10px","11px"=>"11px","12px"=>"12px","13px"=>"13px","14px"=>"14px","15px"=>"15px","16px"=>"16px");
$estilo = array("normal"=>"normal","bold"=>"bold","bolder"=>"bolder","lighter"=>"lighter");
$estilofontes = array("normal"=>"normal","italic"=>"itálico");
$linhafontes = array("underline"=>"sim","none"=>"não");
?>
<form enctype="multipart/form-data" name="form1" method="post" action="">
<center>
<table border="0">
  <tr>
    <td nowrap title="<?=@$Tw01_cod?>">
       <?=@$Lw01_cod?>
    </td>
    <td> 
<?
db_input('w01_cod',4,$Iw01_cod,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw01_descricao?>">
       <?=@$Lw01_descricao?>
    </td>
    <td> 
<?
db_input('w01_descricao',50,$Iw01_descricao,true,'text',$db_opcao,"")
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tw01_corbody?>">
       <?
         db_ancora(@$Lw01_corbody,"js_corbody();",$db_opcao);
       ?>
    </td>
    <td nowrap> 
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
	  <td>
<?
db_input('w01_corbody',10,$Iw01_corbody,true,'text',$db_opcao,"")
?>
          </td>
	  <td>
            <table id="corbody" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corbody:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
              <tr>
                <td>
                &nbsp;
                &nbsp;
                &nbsp;
                </td>
             </tr>
           </table>
         </td>
       </tr>
     </td> 
   </table>
</tr>
  <tr>
    <td nowrap title="<?=@$Tw01_cortexto?>">
       <?
         db_ancora(@$Lw01_cortexto,"js_cortexto();",$db_opcao);
       ?>
    </td>
    <td nowrap> 
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
	  <td>
<?
db_input('w01_cortexto',10,$Iw01_cortexto,true,'text',$db_opcao,"")
?>
         </td>
	 <td>
         <table id="cortexto" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_cortexto:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
           <tr>
              <td>
               &nbsp;
               &nbsp;
               &nbsp;
              </td>
           </tr>
         </table>
         </td>
       </tr>
     </table>
   </td>
</tr>
<tr>
  <td nowrap title="<?=$Tw01_titulo?>">
    <?=$Lw01_titulo?>
  </td>
  <td>
<?
db_input('w01_titulo',80,$Iw01_titulo,true,'text',$db_opcao,"")
?>
  </td>
</tr>
<tr>
  <td nowrap title="Imagem do cabeçalho do site">
    <strong>Imagem do Cabeçalho: </strong>
  </td>
  <td>
    <input name="arquivo" type="file" class="txt" size="50"  accept="image/jpeg">
  </td>
</tr>
  <tr>
    <td nowrap title="Estilo dos Menus">
       <strong>Estilo do Menu: </strong>
    </td>
    <td nowrap>
    <table cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td><strong>Borda: </strong>
    <?
      db_select('w01_bordamenu',$tambordas,true,$db_opcao);
    ?>
    </td>
    <td nowrap title="<?=@$Tw01_estilomenu?>">
      <?=@$Lw01_estilomenu?>
    </td>
    <td>
    <?
     db_select('w01_estilomenu',$estilobordas,true,$db_opcao)
    ?>
    </td>
    <td nowrap title="<?=@$Tw01_corbordamenu?>">
       <?
         db_ancora(@$Lw01_corbordamenu,"js_corbordamenu();",$db_opcao);
       ?>
    </td>
    <td> 
    <?
      db_input('w01_corbordamenu',0,$Iw01_corbordamenu,true,'hidden',$db_opcao,"")
    ?>
    </td>
    <td>
       <table id="corbordamenu" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corbordamenu:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
         <tr>
            <td>
             &nbsp;
             &nbsp;
             &nbsp;
            </td>
         </tr>
       </table>
    </td>
    <td nowrap title="<?=@$Tw01_corfundomenu?>">
       <?
         db_ancora(@$Lw01_corfundomenu,"js_corfundomenu();",$db_opcao);
       ?>
    </td>
    <td> 
    <?
      db_input('w01_corfundomenu',0,$Iw01_corfundomenu,true,'hidden',$db_opcao,"")
    ?>
    </td>
    <td>
       <table id="corfundomenu" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corfundomenu:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
         <tr>
            <td>
             &nbsp;
             &nbsp;
             &nbsp;
            </td>
         </tr>
       </table>
    </td>
    </td>  
  </tr>
  </table>
</td>
</tr>
<tr>
  <td>
    <strong>Estilo do Menu Ativo: </strong>
  </td>
    <td nowrap>
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td nowrap title="<?=@$Tw01_corfundomenuativo?>">
            <?
              db_ancora(@$Lw01_corfundomenuativo,"js_corfundomenuativo();",$db_opcao);
            ?>
          </td>
          <td nowrap title="<?=@$Tw01_corfundomenuativo?>">
          <?
            db_input('w01_corfundomenuativo',0,$Iw01_corfundomenuativo,true,'hidden',$db_opcao,"")
          ?>
          </td>
          <td>
            <table id="corfundomenuativo" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corfundomenuativo:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
              <tr>
                <td>
	        &nbsp;
                &nbsp;
                &nbsp;
                </td>
             </tr>
           </table>
         </td>
       </tr>
     </table>
   </td>  
</tr>
  <tr>
    <td nowrap title="Estilo da Fonte do Menu">
       <strong>Estilo Fonte Menu: </strong>
    </td>
<td>
  <table>
    <tr>
    <td nowrap title="<?=@$Tw01_fontemenu?>">
       <?=@$Lw01_fontemenu?>
    </td>
    <td> 
      <?
        db_select('w01_fontemenu',$fontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_tamfontemenu?>">
       <?=@$Lw01_tamfontemenu?>
    </td>
    <td> 
      <?
        db_select('w01_tamfontemenu',$tamfontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_wfontemenu?>">
       <?=@$Lw01_wfontemenu?>
    </td>
    <td> 
      <?
        db_select('w01_wfontemenu',$estilo,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_estilofontemenu?>">
       <?=@$Lw01_estilofontemenu?>
    </td>
    <td> 
      <?
        db_select('w01_estilofontemenu',$estilofontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_linhafontemenu?>">
       <?=@$Lw01_linhafontemenu?>
    </td>
    <td> 
      <?
        db_select('w01_linhafontemenu',$linhafontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_corfontemenu?>">
       <?
         db_ancora(@$Lw01_corfontemenu,"js_corfontemenu();",$db_opcao);
       ?>
    </td>
    <td> 
      <?
        db_input('w01_corfontemenu',0,$Iw01_corfontemenu,true,'hidden',$db_opcao,"")
       ?>
    </td>
          <td>
            <table id="corfontemenu" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corfontemenu:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
              <tr>
                <td>
	        &nbsp;
                &nbsp;
                &nbsp;
                </td>
             </tr>
           </table>
         </td>

  </tr>
</table>
</td>
</tr>
<tr>
    <td nowrap title="Estilo da Fonte dos Links do Site">
       <strong>Estilo Fonte Links: </strong>
    </td>
<td>
  <table>
    <tr>
    <td nowrap title="<?=@$Tw01_fontesite?>">
       <?=@$Lw01_fontesite?>
    </td>
    <td> 
      <?
        db_select('w01_fontesite',$fontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_tamfontesite?>">
       <?=@$Lw01_tamfontesite?>
    </td>
    <td> 
      <?
        db_select('w01_tamfontesite',$tamfontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_wfontesite?>">
       <?=@$Lw01_wfontesite?>
    </td>
    <td> 
      <?
        db_select('w01_wfontesite',$estilo,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_estilofontesite?>">
       <?=@$Lw01_estilofontesite?>
    </td>
    <td> 
      <?
        db_select('w01_estilofontesite',$estilofontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_linhafontesite?>">
       <?=@$Lw01_linhafontesite?>
    </td>
    <td> 
      <?
        db_select('w01_linhafontesite',$linhafontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_corfontesite?>">
       <?
         db_ancora(@$Lw01_corfontesite,"js_corfontesite();",$db_opcao);
       ?>
    </td>
    <td> 
      <?
        db_input('w01_corfontesite',0,$Iw01_corfontesite,true,'hidden',$db_opcao,"")
       ?>
    </td>
          <td>
            <table id="corfontesite" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corfontesite:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
              <tr>
                <td>
	        &nbsp;
                &nbsp;
                &nbsp;
                </td>
             </tr>
           </table>
         </td>

  </tr>
</table>
</td>
</tr>
<tr>
  <td nowrap title="Estilo da Fonte dos Links Ativos do site">
    <strong>Estilo Fonte Links On: </strong>
  </td>
  <td>
  <table>
    <tr>
    <td nowrap title="<?=@$Tw01_fonteativo?>">
       <?=@$Lw01_fonteativo?>
    </td>
    <td> 
      <?
        db_select('w01_fonteativo',$fontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_tamfonteativo?>">
       <?=@$Lw01_tamfonteativo?>
    </td>
    <td> 
      <?
        db_select('w01_tamfonteativo',$tamfontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_wfonteativo?>">
       <?=@$Lw01_wfonteativo?>
    </td>
    <td> 
      <?
        db_select('w01_wfonteativo',$estilo,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_estilofonteativo?>">
       <?=@$Lw01_estilofonteativo?>
    </td>
    <td> 
      <?
        db_select('w01_estilofonteativo',$estilofontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_linhafonteativo?>">
       <?=@$Lw01_linhafonteativo?>
    </td>
    <td> 
      <?
        db_select('w01_linhafonteativo',$linhafontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_corfonteativo?>">
       <?
         db_ancora(@$Lw01_corfonteativo,"js_corfonteativo();",$db_opcao);
       ?>
    </td>
    <td> 
      <?
        db_input('w01_corfonteativo',0,$Iw01_corfonteativo,true,'hidden',$db_opcao,"")
       ?>
    </td>
          <td>
            <table id="corfonteativo" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corfonteativo:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
              <tr>
                <td>
	        &nbsp;
                &nbsp;
                &nbsp;
                </td>
             </tr>
           </table>
         </td>

  </tr>
</table>
</td>
</tr>
<tr>
  <td colspan="8"><fieldset style="border: 2px outset #000000">
   <legend><strong>Estilo da Tag Input:</strong></legend>
    <table cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td>
          <tr>
            <td nowrap title="<?=@$Tw01_fonteinput?>">
            <?=@$Lw01_fonteinput?>
            </td>
            <td>&nbsp; 
             <?
             db_select('w01_fonteinput',$fontes,true,$db_opcao)
             ?>
            </td>
            <td nowrap title="<?=@$Tw01_tamfonteinput?>">
            &nbsp;<?=@$Lw01_tamfonteinput?>
            </td>
            <td> 
            <?
            db_select('w01_tamfonteinput',$tamfontes,true,$db_opcao)
            ?>
            </td>
            <td nowrap title="<?=@$Tw01_estilofonteinput?>">
              <?=@$Lw01_estilofonteinput?>
            </td>
            <td> 
            <?
              db_select('w01_estilofonteinput',$estilofontes,true,$db_opcao)
            ?>
            </td>
            <td nowrap title="<?=@$Tw01_corfonteinput?>">
            <?
              db_ancora(@$Lw01_corfonteinput,"js_corfonteinput();",$db_opcao);
            ?>
            </td>
            <td> 
            <?
              db_input('w01_corfonteinput',0,$Iw01_corfonteinput,true,'hidden',$db_opcao,"")
            ?>
            <table id="corfonteinput" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corfonteinput:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
              <tr>
                <td>
                 &nbsp;
                 &nbsp;
                 &nbsp;
                </td>
              </tr>
            </table>
          </td>
        </tr>
	</table>
	<table cellpadding="0" cellspacing="0" border="0">
      <tr>
        <br>
        <td nowrap title="<?=@$Tw01_bordainput?>">
         <?=@$Lw01_bordainput?>
        </td>
        <td> 
        <?
          db_select('w01_bordainput',$tambordas,true,$db_opcao)
        ?>
        </td>
        <td nowrap title="<?=@$Tw01_estiloinput?>">
          <?=@$Lw01_estiloinput?>
        </td>
        <td>
        <?
         db_select('w01_estiloinput',$estilobordas,true,$db_opcao)
        ?>
        </td>
        <td nowrap title="<?=@$Tw01_corbordainput?>">
         <?
           db_ancora(@$Lw01_corbordainput,"js_corbordainput();",$db_opcao);
         ?>
         <?
           db_input('w01_corbordainput',0,$Iw01_corbordainput,true,'hidden',$db_opcao,"")
         ?>
        </td>
        <td>
          <table id="corbordainput" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corbordainput:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
            <tr>
              <td>
	        &nbsp;
                &nbsp;
                &nbsp;
              </td>
            </tr>
          </table>
        </td>
        <td nowrap title="<?=@$Tw01_corfundoinput?>">
        <?
          db_ancora(@$Lw01_corfundoinput,"js_corfundoinput();",$db_opcao);
        ?>
        <?
          db_input('w01_corfundoinput',0,$Iw01_corfundoinput,true,'hidden',$db_opcao,"")
        ?>
        </td>
        <td>
          <table id="corfundoinput" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corfundoinput:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
            <tr>
              <td>
	        &nbsp;
                &nbsp;
                &nbsp;
              </td>
            </tr>
            </table>
          </td>
        </tr>
     </table>
    </fieldset>
  </td>
</tr>


<tr>
  <td colspan="10"><fieldset style="border: 2px outset #000000">
       <legend><strong>Estilo dos Botões :</strong></legend>
  <table cellpadding="0" cellspacing="0" border="0">
    <tr>
    <td>
        <tr>
          <td nowrap title="<?=@$Tw01_fontebotao?>">
          <?=@$Lw01_fontebotao?>
          </td>
          <td>&nbsp; 
           <?
             db_select('w01_fontebotao',$fontes,true,$db_opcao)
           ?>
         </td>
         <td nowrap title="<?=@$Tw01_tamfontebotao?>">
        &nbsp;<?=@$Lw01_tamfontebotao?>
        </td>
        <td> 
        <?
          db_select('w01_tamfontebotao',$tamfontes,true,$db_opcao)
        ?>
        </td>
    <td nowrap title="<?=@$Tw01_estilofontebotao?>">
       <?=@$Lw01_estilofontebotao?>
    </td>
    <td> 
      <?
        db_select('w01_estilofontebotao',$estilofontes,true,$db_opcao)
      ?>
    </td>
    <td nowrap title="<?=@$Tw01_wfontebotao?>">
       <?=@$Lw01_wfontebotao?>
    </td>
    <td> 
      <?
        db_select('w01_wfontebotao',$estilo,true,$db_opcao)
      ?>
    </td>
        <td nowrap title="<?=@$Tw01_corfontebotao?>" >
        <?
          db_ancora(@$Lw01_corfontebotao,"js_corfontebotao();",$db_opcao);
        ?>
        </td>
	<td>
        <?
          db_input('w01_corfontebotao',0,$Iw01_corfontebotao,true,'hidden',$db_opcao,"")
        ?>
         <table id="corfontebotao" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corfontebotao:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
           <tr>
             <td>
             &nbsp;
             &nbsp;
             &nbsp;
             </td>
           </tr>
         </table>
       </td>
    </tr>
    </table>
    <table cellpadding="0" cellspacing="0" border="0">
    <tr>
      <br>
      <td nowrap title="<?=@$Tw01_bordabotao?>">
         <?=@$Lw01_bordabotao?>
      </td>
      <td> 
      <?
        db_select('w01_bordabotao',$tambordas,true,$db_opcao)
      ?>
      </td>
      <td nowrap title="<?=@$Tw01_estilobotao?>">
        <?=@$Lw01_estilobotao?>
      </td>
      <td>
      <?
       db_select('w01_estilobotao',$estilobordas,true,$db_opcao)
      ?>
      </td>
      <td nowrap title="<?=@$Tw01_corbordabotao?>">
       <?
         db_ancora(@$Lw01_corbordabotao,"js_corbordabotao();",$db_opcao);
       ?>
        <?
          db_input('w01_corbordabotao',0,$Iw01_corbordabotao,true,'hidden',$db_opcao,"")
        ?>
      </td>
          <td>
            <table id="corbordabotao" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corbordabotao:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
              <tr>
                <td>
	        &nbsp;
                &nbsp;
                &nbsp;
                </td>
             </tr>
           </table>
         </td>
      <td nowrap title="<?=@$Tw01_corfundobotao?>">
       <?
         db_ancora(@$Lw01_corfundobotao,"js_corfundobotao();",$db_opcao);
       ?>
      <?
        db_input('w01_corfundobotao',0,$Iw01_corfundobotao,true,'hidden',$db_opcao,"")
      ?>
      </td>
          <td>
            <table id="corfundobotao" bgcolor="<?=($db_opcao==1?"#cccccc":($db_opcao==2 || $db_opcao == 3?$w01_corfundobotao:"#cccccc"))?>"  border="0" style="border: 2px outset #cccccc; border-right-width: 2px ; border-right-style: outset; " cellpadding="0" cellspacing="0" >
              <tr>
                <td>
	        &nbsp;
                &nbsp;
                &nbsp;
                </td>
             </tr>
           </table>
         </td>
    </tr>
  </table>
  </fieldset>
  </td>
</tr>
</table>
</center>
<br>
<input name="db_opcao" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>
<script>
function js_corfundobotao(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corfundobotao1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corfundobotao1(cor){
  db_iframecor.hide();
  document.form1.w01_corfundobotao.value = cor;
  document.getElementById('corfundobotao').bgColor = cor;
}
function js_corbordabotao(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corbordabotao1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corbordabotao1(cor){
  db_iframecor.hide();
  document.form1.w01_corbordabotao.value = cor;
  document.getElementById('corbordabotao').bgColor = cor;
}
function js_corfontebotao(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corfontebotao1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corfontebotao1(cor){
  db_iframecor.hide();
  document.form1.w01_corfontebotao.value = cor;
  document.getElementById('corfontebotao').bgColor = cor;
}
function js_corfundoinput(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corfundoinput1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corfundoinput1(cor){
  db_iframecor.hide();
  document.form1.w01_corfundoinput.value = cor;
  document.getElementById('corfundoinput').bgColor = cor;
}
function js_corbordainput(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corbordainput1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corbordainput1(cor){
  db_iframecor.hide();
  document.form1.w01_corbordainput.value = cor;
  document.getElementById('corbordainput').bgColor = cor;
}
function js_corfonteinput(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corfonteinput1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corfonteinput1(cor){
  db_iframecor.hide();
  document.form1.w01_corfonteinput.value = cor;
  document.getElementById('corfonteinput').bgColor = cor;
}
function js_corfontesite(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corfontesite1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corfontesite1(cor){
  db_iframecor.hide();
  document.form1.w01_corfontesite.value = cor;
  document.getElementById('corfontesite').bgColor = cor;
}
function js_corfonteativo(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corfonteativo1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corfonteativo1(cor){
  db_iframecor.hide();
  document.form1.w01_corfonteativo.value = cor;
  document.getElementById('corfonteativo').bgColor = cor;
}
function js_corfontemenu(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corfontemenu1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corfontemenu1(cor){
  db_iframecor.hide();
  document.form1.w01_corfontemenu.value = cor;
  document.getElementById('corfontemenu').bgColor = cor;
}
function js_corfundomenuativo(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corfundomenuativo1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corfundomenuativo1(cor){
  db_iframecor.hide();
  document.form1.w01_corfundomenuativo.value = cor;
  document.getElementById('corfundomenuativo').bgColor = cor;
}
function js_corbordamenu(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corbordamenu1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corbordamenu1(cor){
  db_iframecor.hide();
  document.form1.w01_corbordamenu.value = cor;
  document.getElementById('corbordamenu').bgColor = cor;
}
function js_corfundomenu(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corfundomenu1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corfundomenu1(cor){
  db_iframecor.hide();
  document.form1.w01_corfundomenu.value = cor;
  document.getElementById('corfundomenu').bgColor = cor;
}
function js_corbody(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_corbody1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_corbody1(cor){
  db_iframecor.hide();
  document.form1.w01_corbody.value = cor;
  document.getElementById('corbody').bgColor = cor;
}
function js_cortexto(){
  db_iframecor.jan.location.href = 'func_cores.php?funcao_js=parent.js_cortexto1';
  db_iframecor.mostraMsg();
  db_iframecor.show();
  db_iframecor.focus();
}
function js_cortexto1(cor){
  db_iframecor.hide();
  document.form1.w01_cortexto.value = cor;
  document.getElementById('cortexto').bgColor = cor;
}
function js_pesquisa(){
  db_iframe.jan.location.href = 'func_confsite.php?funcao_js=parent.js_preenchepesquisa|0';
  db_iframe.mostraMsg();
  db_iframe.show();
  db_iframe.focus();
}
function js_preenchepesquisa(chave){
  db_iframe.hide();
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
}
</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

$func_iframe = new janela('db_iframecor','');
$func_iframe->posX=100;
$func_iframe->posY=20;
$func_iframe->largura=550;
$func_iframe->altura=250;
$func_iframe->titulo='Cores';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>