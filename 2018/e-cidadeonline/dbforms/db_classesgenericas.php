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

//MODULO: issqn
//|00|//cl_iframe_seleciona
//|10|//é gerado uma tabela botão para selecionar  as linhas desejadas
//|15|//[variavel] = new cl_iframe_seleciona;
class cl_iframe_seleciona { 
   var $sql        = null;
   //|30|//sql para montar os campos
   var $sql_marca  = null;
   //sql para dizer quais os campos devem iniciar marcados 
   //indica se todos as linhas devem abrir marcadas ou naum 
   var $chaves     = null;
   //|30|//campos que deseja retorna num input apenas
   var $campos     = null;
   //|30|//campos que serão mostrados
   var $legenda    = "DADOS";
   //|30|//legenda do fieldset 
   var $msg_vazio  = "<small><b>Nenhum registro encontrado.</b><small>";
   //|30|//mensagem a ser mostrada quando o sql não retornar nenhum registro
   var $textocabec = 'darkblue';
   //|30|//cor do texto do cabeçalho
   var $textocorpo = 'black'; 
   //|30|//cor do texto do corpo
   var $fundocabec = '#aacccc ';
   //|30|//cor do fundo do cabeçalho
   var $fundocorpo = '#ccddcc';
   //|30|//cor do fundo do corpo
   var $iframe_width = '750';
   //|30|//largura do iframe
   var $iframe_height = '190';
   //|30|//altura do iframe
   var $iframe_nome =   'nome_iframe '; 
   //|30|//nome do iframe
   var $cabecnowrap     = "false";
   //|30|//quebrar linha ou não do cabeçalho
   var $corponowrap     = "false";
   //|30|//quebrar linha ou não do corpo
   var $tamfontecabec = '10'; 
   //|30|//tamanho da fonte do cabeçalho
   var $tamfontecorpo = '9';
    //|30|//tamanho da fonte do corpo
   var $checked = false;
    //|30|// se o estado inicial do checkbox é checked ou não.
   function iframe_seleciona($db_opcao){
         $arquivo = tempnam ("/tmp", "iframe");   
         $arquivo.=".php";
         umask(74);
	 $fd = fopen($arquivo,"w") or die('Erro ao abrir!');
	 fputs($fd,' <?    '."\n");
	 fputs($fd,' $textocabec="'.$this->textocabec.'";'."\n");
	 fputs($fd,' $textocorpo="'.$this->textocorpo.'";'."\n");
	 fputs($fd,' $fundocabec="'.$this->fundocabec.'";'."\n");
	 fputs($fd,' $fundocorpo="'.$this->fundocorpo.'";'."\n");
	 fputs($fd,' $cabecnowrap="'.$this->cabecnowrap.'";'."\n"); 
	 fputs($fd,' $corponowrap="'.$this->corponowrap.'";'."\n");
	 fputs($fd,' $tamfontecabec="'.$this->tamfontecabec.'";'."\n");
	 fputs($fd,' $tamfontecorpo="'.$this->tamfontecorpo.'";'."\n");
         if($this->sql!=""){
  	   fputs($fd,' $sql="'.base64_encode($this->sql).'";'."\n");
	   if($this->sql_marca!=null){
	     fputs($fd,' $sql_marca='.base64_encode($this->sql_marca).'";'."\n");
	   } 
	   if($this->chaves!=null){
  	     fputs($fd,' $chaves="'.$this->chaves.'";'."\n");
	   } 
	   if(isset($this->checked) && $this->checked==true ){
  	     fputs($fd,' $ckd="true";'."\n");
	   } 
	   fputs($fd,' $msg_vazio="'.base64_encode($this->msg_vazio).'";'."\n");
  	   fputs($fd,' $campos="'.base64_encode($this->campos).'";'."\n");
  	   fputs($fd,' $db_opcao="'.$db_opcao.'";'."\n");
         } 
	 fputs($fd,'?>  ');
	 
	 fclose($fd);
     echo "  
            <fieldset><Legend align=\"center\"><b>".$this->legenda."</b></Legend>
              <iframe id=\"ativ\"  frameborder=\"0\" name=\"".$this->iframe_nome."\"   leftmargin=\"0\" topmargin=\"0\" src=\"dbforms/db_classes_iframe_seleciona.php?arquivo=".base64_encode($arquivo)."\" height=\"".$this->iframe_height."\" width=\"".$this->iframe_width."\">
              </iframe> 
            </fieldset>
        <script>";   
################## quando for setado a propriedade chaves, sera gerado um input contendo todas as chaves#################
        if(isset($this->chaves)){        
           $matriz01=split(",",$this->chaves);       
           echo "  
          function js_gera_chaves(){
            tabela=".$this->iframe_nome.".document.getElementById('tabela_seleciona');\n
            var coluna='';\n  
            var sep=''; 
            for(i=1; i<tabela.rows.length; i++){\n
              id=tabela.rows[i].id.substr(6);\n  
              if(".$this->iframe_nome.".document.getElementById('CHECK_'+id).checked==true){\n";
                echo "coluna+=sep;\n";
                $sep="";
                for($y=0; $y<sizeof($matriz01); $y++){
                  echo  "colu ='$sep'+".$this->iframe_nome.".document.getElementById('".$matriz01[$y]."_'+i).innerHTML;\n
                         coluna+=colu.replace('&nbsp;','');\n
                  ";
                   $sep="-";
                }
             echo "
                sep='#'; \n 
              }
	    } 
            obj=document.createElement('input');
            obj.setAttribute('name','chaves');
            obj.setAttribute('type','hidden');
            obj.setAttribute('value',coluna);
            document.form1.appendChild(obj);
            return true;
          }";
        } 
##########################################################################################################################
####################ao chamar esta função será gerado um input para cada linha com os valores da coluna###################
        echo "     
	  function js_gera_dados(){
            tabela=".$this->iframe_nome.".document.getElementById('tabela_seleciona');
            for(i=1; i<tabela.rows.length; i++){
              id=tabela.rows[i].id.substr(6);  
              if(".$this->iframe_nome.".document.getElementById('CHECK_'+id).checked==true){
  	        y='';
	        colunas=''; 
                for(a=1; a<(tabela.rows[0].cells.length); a++){
  	  	  col=tabela.rows[i].cells[a].innerHTML;
                  col=col.replace('&nbsp;',''); 
		  colunas=colunas+y+col;
	          y='#';
	        } 
               
	        obj=document.createElement('input');
  	        obj.setAttribute('name','linha_'+i);
	        obj.setAttribute('id','linha_'+i);
	        obj.setAttribute('type','hidden');
      	        obj.setAttribute('value',colunas);
	        document.form1.appendChild(obj);
              }
	    }   
            return true;
          }   
        </script>  
          ";   
     }	   
	  
}

//|00|//cl_criatabela
//|10|//Cria um iframe com as opções de alterar e excluir por java script. Quando usar a função "js_criaelementos()" será criado um input com os dados de cada linha 
//|15|//[variavel] = new cl_alterar_excluir_html;
class cl_iframe_alterar_excluir_html{ 
   var $load = '1=1';
   //|30|//função a ser executada no onload do iframe
   var $fontecabec = '10px';
   //|30|//tamanho do texto do cabeçalho
   var $fontecorpo = '10px';
   //|30|//tamanho do texto do corpo
   var $textocabec = 'darkblue';
   //|30|//cor do texto do cabeçalho
   var $textocorpo = 'black';  
   //|30|//cor do texto do corpo
   var $fundocabec = '#BDC6BD';
   //|30|//cor do fundo do cabeçalho
   var $fundocorpo = '#cccccc';
   //|30|//cor do fundo do corpo
   var $iframe_width = '750';
   //|30|//largura do iframe
   var $iframe_height = '190';
   //|30|//altura do iframe
   var $iframe_nome = 'criatabela';
   //|30|//nome do iframe
   var $colunas     = null; //nome da colunas
   //|30|//nome das colunas
   var $js_ex01     = null;
   //|30|//função executada antes de excluir uma linha, caso esta função retorne false, a exclusão da linha será abortada
   var $js_ex02     = null;
   //|30|//função executada depois de excluir uma linha
   var $sql         = null;
   //|30|//sql com dados a seresm colocados na tabela por PHP
   var $db_opcao    =  null;  
   //|30|//não é obrogatório
   var $tamfontecabec = '10'; 
   //|30|//tamanho da fonte do cabeçalho
   var $tamfontecorpo = '9';
    //|30|//tamanho da fonte do corpo
    //|30|//js_incluirlinhas(campos que deseja incluir);
    //|30|//js_alterarlinhas(campos); funcao que retorna todos os camos
    //|30|//js_numlinhas funcao que retorna o numero de lmnha da tabela
    //|30|//js_dados();funcao que cria input com todas as linhas da tabela com o nome de  linha_XX XX=numero da linha
   function iframe_alterar_excluir_html(){
     $quais_colunas="";
     $cerca="";
     reset($this->colunas);

     $arquivo = tempnam ("/tmp", "iframe");   
     $arquivo.=".php";
     umask(74);
     $fd = fopen($arquivo,"w") or die('Erro ao abrir!');
     fputs($fd,' <?    '."\n");
     fputs($fd,' $textocabec="'.$this->textocabec.'";'."\n");
     fputs($fd,' $textocorpo="'.$this->textocorpo.'";'."\n");
     fputs($fd,' $fundocabec="'.$this->fundocabec.'";'."\n");
     fputs($fd,' $fundocorpo="'.$this->fundocorpo.'";'."\n");
     fputs($fd,' $tamfontecabec="'.$this->tamfontecabec.'";'."\n");
     fputs($fd,' $tamfontecorpo="'.$this->tamfontecorpo.'";'."\n");
     fputs($fd,' $load="'.$this->load.'";'."\n");
     if(isset($this->db_opcao)){  
       fputs($fd,' $db_opcao="'.$this->db_opcao.'";'."\n");
     } 
     if($this->sql!=null){
        fputs($fd,' $sql="'.base64_encode($this->sql).'";'."\n");
     }
     for($q=0; $q<sizeof($this->colunas); $q++){
       fputs($fd,' $x_'.key($this->colunas).'="'.$this->colunas[key($this->colunas)].'";'."\n");
       $quais_colunas.=$cerca.key($this->colunas);
       next($this->colunas);
       $cerca = "#";
     }  
     fputs($fd,' $quais_colunas="'.$quais_colunas.'";'."\n");
     fputs($fd,'?>  ');
     fclose($fd);
     echo" <iframe id=\"\"  frameborder=\"0\" name=\"".$this->iframe_nome."\"   leftmargin=\"0\" topmargin=\"0\" src=\"dbforms/db_classes_iframe_alterar_excluir_html.php?arquivo=".base64_encode($arquivo)."\" height=\"".$this->iframe_height."\" width=\"".$this->iframe_width."\">
            </iframe>
	    
	  ";   
     echo"            
	  <script>
	  function js_dados(){
            tabela=".$this->iframe_nome.".document.getElementById('tab');
            for(i=1; i<tabela.rows.length; i++){
	      y='';
	      colunas=''; 
              for(a=0; a<(tabela.rows[0].cells.length-1); a++){
		col=tabela.rows[i].cells[a].innerHTML;
		if(col=='&nbsp;'){
		  col='';
		}
		colunas=colunas+y+col;
	        y='#';
	      } 
	      obj=document.createElement('input');
	      obj.setAttribute('name','linha_'+i);
	      obj.setAttribute('id','linha_'+i);
	      obj.setAttribute('type','hidden');
      	      obj.setAttribute('value',colunas);
	      document.form1.appendChild(obj);
	    }   
          }
            function js_excluirlinhas(linha) {";
	   if($this->js_ex01){
	     echo $this->js_ex01; 
	   }   
     echo " 
              if(confirm(\"Deseja realmente excluir esta linha?\")){
                var tab =".$this->iframe_nome.".document.getElementById('tab');
                for(i=0;i<tab.rows.length;i++){
                  if(\"id_\"+linha == tab.rows[i].id){
                    tab.deleteRow(i);
              	    break;
                  }
                }
              }";  
	      if($this->js_ex02!=null){
   	       echo $this->js_ex02; 
	      }   
     echo "  	    
            }
            function js_numlinhas(){
              var tab = ".$this->iframe_nome.".document.getElementById('tab');
              return tab.rows.length;
            }";
	    
	echo "      
            function js_alterarlinhas(linha) {
	
              var tab = ".$this->iframe_nome.".document.getElementById('tab');
              var colunas=\"\";
              var virgula=\"\";
              for(i=0;i<tab.rows.length;i++){
                if(\"id_\"+linha == tab.rows[i].id){
                  for(x=0;x<(tab.rows[i].cells.length-1);x++){
                    colunas+=virgula+\"'\"+tab.rows[i].cells[x].innerHTML+\"'\";
          	    virgula=\",\";
                  }	 
                  tab.deleteRow(i);
                  break;
                }
              }
              eval('js_alterar('+colunas+')');
            }
	  ";  
     $coluna="";
     $virgula="";
     $colunas= split("#",$quais_colunas);
     $totcol=sizeof($colunas);
     for($i=0; $i<$totcol; $i++){
       $coluna.=$virgula.$colunas[$i];
       $virgula=",";
     } 
     echo"
          function js_incluirlinhas_disabled($coluna){
            var conta_linha=".$this->iframe_nome.".document.form1.conta_linha.value;  
            var tab = ".$this->iframe_nome.".document.getElementById('tab');
            var NovaLinha = tab.insertRow(tab.rows.length);
            var dados = new Array($coluna);
            NovaLinha.id = 'id_'+conta_linha;
            for(i=0;i< $totcol;i++){
              NovaColuna = NovaLinha.insertCell(i);
              NovaColuna.style.fontSize = '10px';
              NovaColuna.id = 'idcol_'+i;
              NovaColuna.align = 'left';
              if(dados[i]!=\"\"){
                NovaColuna.innerHTML = dados[i];
              }else{	
                NovaColuna.innerHTML = \"&nbsp;\";
              }	
            }
            NovaColuna = NovaLinha.insertCell($totcol);
            NovaColuna.align = 'center';
            NovaColuna.innerHTML = '<a title=\'ALTERAR CONTEÚDO DA LINHA\' href=\'\' onclick=\"return false;\">&nbsp;A&nbsp;</a><a title=\'EXCLUIR CONTEÚDO DA LINHA\' href=\'\' onclick=\"return false;\">&nbsp;E&nbsp;</a>';
            conta_linha++;
          }
	  \n";
	  echo "
          function js_incluirlinhas($coluna){
            var conta_linha=".$this->iframe_nome.".document.form1.conta_linha.value;  
            var tab = ".$this->iframe_nome.".document.getElementById('tab');
            var NovaLinha = tab.insertRow(tab.rows.length);
            var dados = new Array($coluna);
            NovaLinha.id = 'id_'+conta_linha;
            for(i=0;i< $totcol;i++){
              NovaColuna = NovaLinha.insertCell(i);
              NovaColuna.style.fontSize = '10px';
              NovaColuna.id = 'idcol_'+i;
              NovaColuna.align = 'left';
              if(dados[i]!=\"\"){
                NovaColuna.innerHTML = dados[i];
              }else{	
                NovaColuna.innerHTML = \"&nbsp;\";
              }	
            }
            NovaColuna = NovaLinha.insertCell($totcol);
            NovaColuna.align = 'center';
            NovaColuna.innerHTML = '<a title=\'ALTERAR CONTEÚDO DA LINHA\' href=\'\' onclick=\"parent.js_alterarlinhas(\''+conta_linha+'\');return false;\">&nbsp;A&nbsp;</a><a title=\'EXCLUIR CONTEÚDO DA LINHA\' href=\'\' onclick=\"parent.js_excluirlinhas(\''+conta_linha+'\');return false;\">&nbsp;E&nbsp;</a>';
            conta_linha++;
          }
	  \n";

     echo" </script>";
   }

}   
##############################################3CRIA ABAS########################################################
################################################################################################################
//MODULO: issqn
//|00|//cl_criaabas
//|10|//Cria abas com  iframe 
//|10|//Cria abas com  iframe 
//|15|//[variavel] = new cl_criaabas;
class cl_criaabas { 
   var $identifica = null;
   //|30|//nome e label do iframes. É passado por um array, é passado com array
   var $abas_top  = "44";
   //|30|//Distãncia  à que os iframes ficarão em relação as abas
   var $abas_left  = "0";
   //|30|//Distãncia  à que os iframes ficarão em relação ao canto esquerdo
   var $src       = null;
   //|30|//src dos iframes
   var $title     = null;
   //|30|//title dos iframes
   var $cortexto  = null;
   //|30|//cor do texto
   var $corfundo  = null;
   //|30|//cor do fundo
   var $sizecampo = null;
   //|30|//largura da aba
   var $disabled = null;
   //|30|//habilita ou naum a aba
   var $iframe_width = '790';
   //|30|//largura do iframe
   var $iframe_height = '405';
   //|30|//altura do iframe
   var $scrolling = "no"; 
   //|30|//Se o iframe terá ou não scrolling
   //|30|//Nunca chame a função dentro de outro formulario
  function cria_abas(){
?> 
        <script>
          function mo_camada(idtabela){
	    var camada="div_"+idtabela;
            var tabela = document.getElementById(idtabela);
            var divs = document.getElementsByTagName('DIV');
            var tab  = document.getElementsByTagName('TABLE');
            var aba = eval('document.formaba.'+idtabela+'.name');
            var input = eval('document.formaba.'+idtabela);
            var alvo = document.getElementById(camada);
            for (var j = 0; j < divs.length; j++){
              if(alvo.id == divs[j].id){
                divs[j].style.visibility = 'visible' ;
              }else{
	        if(divs[j].className == 'tabela'){
	          divs[j].style.visibility = 'hidden';
	        }
 	      }
            }
            for(var x = 0; x < tab.length; x++){
              if(tab[x].className == 'bordas'){
                for(y=0; y < document.forms['formaba'].length; y++){
     	          tab[x].style.border = '1px outset #cccccc';
 	          tab[x].style.borderBottomColor = '#000000';
 <?
    reset($this->identifica);
 for($w=0; $w<sizeof($this->identifica); $w++){
       $chave=key($this->identifica);
 ?>     
  	          document.formaba.<?=$chave?>.style.color ='<?=(isset($this->cortexto[$chave])&&$this->cortexto[$chave]!=""?$this->cortexto[$chave]:"black")?>';;
  	          document.formaba.<?=$chave?>.style.fontWeight = 'normal';
<?
     next($this->identifica);
   }   
?>		  
                }
                if(aba == tab[x].id){
	          tab[x].style.border = '3px outset #999999';
	          tab[x].style.borderBottomWidth = '0px';
 	          tab[x].style.borderRightWidth = '1px';
 	          tab[x].style.borderLeftColor =  '#000000';
 	          tab[x].style.borderTopColor =  '#3c3c3c';
	          tab[x].style.borderRightColor =  '#000000';
 	          tab[x].style.borderRightStyle =  'inset';
                }
                input.style.color = 'black';
                input.style.fontWeight = 'bold';
              }  	 
            }
          }
        </script>
        <style>
          a {
	      text-decoration:none;
	    }
          a:hover {
	    text-decoration:none;
            color: #666666;
          }
          a:visited {
	    text-decoration:none;
            color: #999999;
          }
          a:active {
             color: black;
             font-weight: bold; 
          }  
          .nomes {
             border:none;
             text-align: center;
             font-size: 11px;
             font-weight:normal;
             cursor: hand;
          }
          .nova {background-color: transparent;
             border:none;
             text-align: center;
             font-size: 11px;
             color: darkblue;
             font-weight:bold;
             cursor: hand;
             height:14px; 
          }
          .bordas{
	     border: 1px outset #cccccc;
             border-bottom-color: #000000;
          } 
          .bordasi{
	     border: 0px outset #cccccc;
          }
          .novamat{
	     border: 2px outset #cccccc;
             border-right-color: darkblue;
             border-bottom-color: darkblue;
             background-color: #999999;
          }
     </style>
     <table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0" >
      <form name="formaba" method="post" id="formaba" >
      <tr> 
        <td align="left" valign="top" bgcolor="#CCCCCC">
	  <table border="0" cellpadding="0" cellspacing="0" marginwidth="0" >
   	    <tr>
 <?
    reset($this->identifica);
    for($w=0; $w<sizeof($this->identifica); $w++){
       $chave=key($this->identifica);
       $cortexto=(isset($this->cortexto[$chave])&&$this->cortexto[$chave]!=""?$this->cortexto[$chave]:'black');
       $corfundo=(isset($this->corfundo[$chave])&&$this->corfundo[$chave]!=""?$this->corfundo[$chave]:'#cccccc');
       $sizecampo=(isset($this->sizecampo[$chave])&&$this->sizecampo[$chave]!=""?$this->sizecampo[$chave]:'10');
       $disabled=(isset($this->disabled[$chave])&&$this->disabled[$chave]=="true"?'disabled':'');

 ?>     
	      <td>
                <table class="bordas" id="<?=$chave?>" border="0" style="border: 3px outset #666666; border-bottom-width: 0px; border-right-width: 1px ;border-right-color: #000000; border-top-color: #3c3c3c; border-right-style: inset; " id="lote"  cellpadding="3" cellspacing="0" > 
                  <tr>
                    <td nowrap>
	              <input readonly <?=$disabled?>  name="<?=$chave?>" class="nomes"  style="font-weight:bold; color:<?=$cortexto?>; background-color:<?=$corfundo?>;" type="text"  value="<?=$this->identifica[$chave]?>" title="<?=$this->title[$chave]?>" size="<?=$sizecampo?>"  onClick="mo_camada('<?=$chave?>');">
		    </td>
                  </tr>
                </table>
              </td>
<?	      
     next($this->identifica);
    } 	      
?>	      
        	      
	    </tr>
 	  </table>
        </td>
      </tr>
      </form>
      <form name="form_iframes" method="post" id="form_iframes" >
      <tr>
        <td height="340" align="center">   
 <?
    reset($this->identifica);
    for($w=0; $w<sizeof($this->identifica); $w++){
       $chave=key($this->identifica);
       $src=(isset($this->src[$chave]) && $this->src[$chave]!=null?"src=\"".$this->src[$chave]."\"":"");
       if($src==""){
	 /*echo "<script>
                 document.formaba.$chave.disabled=true;
	       </script>";*/
       }
 ?>     
          <div class="tabela" id="div_<?=$chave?>" style="position:absolute; left:<?=$this->abas_left?>px; top:<?=$this->abas_top?>px;  visibility: visible;">
            <iframe  id='<?=$chave?>' name="iframe_<?=$chave?>" class="bordasi" <?=$src?> frameborder="0" marginwidth="0" leftmargin="0" topmargin="0"   height="<?=$this->iframe_height?>" scrolling="<?=$this->scrolling?>"  width="<?=$this->iframe_width?>">
	      </iframe>
	  </div>    
<?
     next($this->identifica);
   }
?>	    
	  </div>
        </td>
      </tr>
      </form>
      </table>
 <?     
  reset($this->identifica);
  $chave=key($this->identifica);
  echo "
       <script>  
  	mo_camada('$chave');
       </script>  
       ";
   }  
}





//|00|//cl_iframe_alterar_excluir
//|10|//Cria um iframe com as opções de alterar e excluir por PHP.
//|15|//[variavel] = new cl_iframe_alterar_excluir;
class cl_iframe_alterar_excluir { 
   var $sql        = null;
   //sql para montar os campos
   //|30|//nome e label dos campos
   var $sql_disabled    = null;
   //sql para desabilitar os campos que nao podem ser alterados ou excluidos
   //|30|//nome e label dos campos
   var $campos     = null;
   //|30|//campos que serão mostrados
   var $opcoes    = 1;
   //|30|//quais opcoes poderão ter. Se for só alterar é 2, se for só excluir é 3 se for os dois é 1
   var $legenda    = "DADOS";
   //|30|//legenda do fieldset 
   var $chavepri   = null;
   //|30|//chaves que serão usadas para identificar
   var $msg_vazio  = "Nenhum registro encontrado.";
   //|30|//mensagem a ser mostrada quando o sql não retornar nenhum registro
   var $textocabec = 'darkblue';
   //|30|//cor do texto do cabeçalho
   var $textocorpo = 'black'; 
   //|30|//cor do texto do corpo
   var $fundocabec = '#aacccc ';
   //|30|//cor do fundo do cabeçalho
   var $fundocorpo = '#ccddcc';
   //|30|//cor do fundo do corpo
   var $iframe_width = '750';
   //|30|//largura do iframe
   var $iframe_height = '190';
   //|30|//altura do iframe
   var $iframe_nome =   'nome_iframe '; 
   //|30|//nome do iframe
   var $cabecnowrap     = "false";
   //|30|//quebrar linha ou não do cabeçalho
   var $corponowrap     = "false";
   //|30|//quebrar linha ou não do corpo
   var $tamfontecabec = '10'; 
   //|30|//tamanho da fonte do cabeçalho
   var $tamfontecorpo = '9';
    //|30|//tamanho da fonte do corpo

   function iframe_alterar_excluir($db_opcao){
     $query_string = "a=1";
     $quais_chaves = "";
     reset($this->chavepri);
     $cerca = "";
     $arquivo = tempnam ("/tmp", "iframe");   
     $arquivo.=".php";
     umask(74);
     $fd = fopen($arquivo,"w") or die('Erro ao abrir!');
     fputs($fd,' <?    '."\n");
     fputs($fd,' $textocabec="'.$this->textocabec.'";'."\n");
     fputs($fd,' $textocorpo="'.$this->textocorpo.'";'."\n");
     fputs($fd,' $fundocabec="'.$this->fundocabec.'";'."\n");
     fputs($fd,' $fundocorpo="'.$this->fundocorpo.'";'."\n");
     fputs($fd,' $cabecnowrap="'.$this->cabecnowrap.'";'."\n"); 
     fputs($fd,' $corponowrap="'.$this->corponowrap.'";'."\n");
     fputs($fd,' $tamfontecabec="'.$this->tamfontecabec.'";'."\n");
     fputs($fd,' $tamfontecorpo="'.$this->tamfontecorpo.'";'."\n");
     fputs($fd,' $sql="'.base64_encode($this->sql).'";'."\n");
     fputs($fd,' $msg_vazio="'.base64_encode($this->msg_vazio).'";'."\n");
     fputs($fd,' $campos="'.base64_encode($this->campos).'";'."\n");
     fputs($fd,' $db_opcao="'.$db_opcao.'";'."\n");
     for($q=0; $q<sizeof($this->chavepri); $q++){
       fputs($fd,' $x_'.key($this->chavepri).'="'.$this->chavepri[key($this->chavepri)].'";'."\n");
       $quais_chaves.=$cerca.key($this->chavepri);
       next($this->chavepri);
       $cerca = "#";
     }  
     fputs($fd,' $quais_chaves="'.$quais_chaves.'";'."\n");

     if($this->opcoes!=1){ 
       fputs($fd,' $opcoes="'.$this->opcoes.'";'."\n");
     }  
     if($this->sql_disabled!=null){ 
       fputs($fd,' $sql_disabled="'.base64_encode($this->sql_disabled).'";'."\n");
     } 
     
     fputs($fd,'?>  ');
     
     fclose($fd);
     echo "  
            <form name='form1' method='post' action='' >
            <fieldset><Legend align=\"center\"><b>".$this->legenda."</b></Legend>
              <iframe id=\"ativ\"  frameborder=\"0\" name=\"".$this->iframe_nome."\"   leftmargin=\"0\" topmargin=\"0\" src=\"dbforms/db_classes_iframe_alterar_excluir.php?arquivo=".base64_encode($arquivo)."\" height=\"".$this->iframe_height."\" width=\"".$this->iframe_width."\">
              </iframe> 
            </fieldset>
	    </form>
          ";   
     }	   
	  
}




class cl_arquivo_auxiliar {
//|00|//cl_arquivo_auxiliar
//|10|//Gera no formulario um select multiple com um campo de ancora para inclusao e selecao de item
//|15|//[variavel] = new cl_arquivo_auxiliar;
  var $cabecalho = null;
//|30|//Cabecalho : Descrição que será utilizada no FieldSet
  var $codigo = null;
//|30|//Código    : Nome do campo para o código da âncora
  var $descr  = null;
//|30|//Descrição : Nome do campo da descrição para a descrição do código âncora
  var $nomeobjeto = 'itens_selecao';
//|30|//Nome do objeto javascript para o select multiple
  var $funcao_js = null;
//|30|//Nome da função javascript que será utilizada quando clicar na âncora
  var $funcao_js_hide = null;
//|30|//Nome da função javascript que será utilizada quando colocar um código e sair do campo
  var $sql_exec  = null;
//|30|//Sql que será executado quando entrar em alteração do formulário
  var $func_arquivo = null;
//|30|//Função que será incluída no iframe quando clicado na âncora
  var $nomeiframe = "";
//|30|//Nome do objeto Javascript do Iframe para manipulação do mesmo
  var $db_opcao = 2;
//|30|//Código da opção do programa Padrão = 2
  var $tipo = 1;
//|30|//Tipo de montagem do formulário, 1=vertical ou 2=horizontal
   var $linhas = 15;
//|30|//Numero de linhas do objeto select Padrão = 15
   var $vwidth = 250;
//|30|//Largura do objeto select Padrão = 250
  
   var $sTarget = "top.corpo";
   
  function funcao_gera_formulario() {

    echo "<tr>\n";
    echo "<td colspan=\"2\">\n";
    echo "<table align=\"center\" >\n";
    echo "   <tr>\n";
    echo "    <td nowrap title=\"\" > \n";
    echo "      <fieldset><Legend>".$this->cabecalho."</legend>\n";
    echo "      <table border=\"0\">\n";
    echo "         <tr>\n";
    echo "           <td nowrap >\n";
    
    $clrotulocampo = new rotulocampo;
    $clrotulocampo->label($this->codigo);
    $clrotulocampo->label($this->descr);
    $codfilho = "L".$this->codigo;
    db_ancora($GLOBALS["$codfilho"],"js_BuscaDadosArquivo(true);",$this->db_opcao);
    
    db_input($this->codigo,8,'',true,'text',$this->db_opcao," onchange='js_BuscaDadosArquivo(false);'");
    if($this->tipo==1)
      echo "<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
    db_input($this->descr,25,'',true,'text',3);
    if($this->tipo==1)
      echo "<br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
    echo "  <input name=\"db_lanca\" type=\"button\" value=\"Lançar\" >\n";
    echo "  </td>\n";
    echo "</tr> \n";
    echo "<tr>  \n ";
    echo "  <td align=\"center\" >\n";
    echo "            <select name=\"".$this->nomeobjeto."[]\" id=\"".$this->nomeobjeto."\" size=\"".$this->linhas."\" style=\"width:".$this->vwidth."px\" multiple onDblClick=\"js_excluir_item()\">\n";
    if(!empty($this->sql_exec)){
       $result = db_query($this->sql_exec);
       for($i=0;$i<pg_numrows($result);$i++){
         echo "            <option value='".pg_result($result,$i,$this->codigo)."'>".pg_result($result,$i,$this->descr)."</option>\n";
       }
    }
    echo "             </select> \n";
    echo "           </td>\n";
    echo "         </tr>\n";
    echo "         </tr>\n";
    echo " 	   <td align=\"center\"><strong>\n";
    echo " 		   Dois Clicks sobre o ítem Exclui</strong>\n";
    echo "	   </td>\n";
    echo "         </tr>\n";
    echo "       </table>\n";
    echo "      </fieldset>\n";
    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "</td>\n";
    echo "</tr>	\n";
    echo "<script>\n";
    echo "function js_atualiza_item(){\n";
    echo "  var F = document.getElementById(\"".$this->nomeobjeto."\").options;\n";
    echo "  if(F.length==0){\n";
    echo "    alert('Cadastre um ítem para proceguir.');\n";
    echo "    document.form1.".$this->codigo.".focus();\n";
    echo "    return false;\n";
    echo "  }else{  \n";
    echo "    for(var i = 0;i < F.length;i++) {\n";
    echo "      F[i].selected = true;\n";
    echo "    }\n";
    echo "  }\n";
    echo "  return true;\n";
    echo "}\n";
    					       
    echo "function js_excluir_item() {\n";
    echo " var F = document.getElementById(\"".$this->nomeobjeto."\");\n";
    echo " if(F.length == 1)\n";
    echo "    F.options[0].selected = true;\n";
    echo " var SI = F.selectedIndex;\n";
    echo " if(F.selectedIndex != -1 && F.length > 0) {\n";
    echo "    F.options[SI] = null;\n";
    echo "    js_trocacordeselect();\n";
    echo "    if(SI <= (F.length - 1))\n";
    echo "      F.options[SI].selected = true;\n";
    echo " }\n";
    echo "}\n";
    echo "function js_insSelect() {\n";
    echo " var texto=document.form1.".$this->descr.".value;\n";
    echo " var valor=document.form1.".$this->codigo.".value;\n";
    echo " if(texto != \"\" && valor != \"\"){\n";
    echo "    var F = document.getElementById(\"".$this->nomeobjeto."\");\n";
    echo "    var testa = false;\n";
    echo "    for(var x = 0; x < F.length; x++){\n";
    echo "       if(F.options[x].value == valor || F.options[x].text == texto){\n";
    echo "         testa = true;\n";
    echo "         break;\n";
    echo "       }\n";
    echo "    }\n";
    echo "    if(testa == false){\n";
    echo "      F.options[F.length] = new Option(texto,valor);\n";
    echo "      js_trocacordeselect();\n";
    echo "    }\n";
    echo " }\n";
    echo " texto=document.form1.".$this->descr.".value=\"\";\n";
    echo " valor=document.form1.".$this->codigo.".value=\"\";\n";
    echo " document.form1.db_lanca.onclick = '';\n";
    echo "}\n";
    echo "function js_BuscaDadosArquivo(chave){\n";
    echo " document.form1.db_lanca.onclick = '';\n";
    echo " if(chave){\n";
    echo "   js_OpenJanelaIframe('{$this->sTarget}','".$this->nomeiframe."','".$this->func_arquivo."?funcao_js=parent.".$this->funcao_js."|".$this->codigo."|".$this->descr."','Pesquisa',true);\n";
    echo " }else{\n";
    echo "   js_OpenJanelaIframe('{$this->sTarget}','db_arquivos_iframe','".$this->func_arquivo."?pesquisa_chave='+document.form1.".$this->codigo.".value+'&funcao_js=parent.".$this->funcao_js_hide."','Pesquisa',false);\n";
    echo " }\n";
    echo "}\n";
    
    echo "function ".$this->funcao_js."(chave,chave1){\n";
    echo " document.form1.".$this->codigo.".value = chave;\n";
    echo " document.form1.".$this->descr.".value = chave1;\n";
    echo " ".$this->nomeiframe.".hide();\n";
    echo " document.form1.db_lanca.onclick = js_insSelect;\n";
    echo "}\n";
    echo "function ".$this->funcao_js_hide."(chave,chave1){\n";
    echo " document.form1.".$this->descr.".value = chave;\n";
    echo " if(chave1){\n";
    echo "   document.form1.".$this->codigo.".select();\n";
    echo "   document.form1.".$this->codigo.".focus();\n";
    echo " }else{\n";
    echo "   document.form1.db_lanca.onclick = js_insSelect;\n";
    echo " }\n";
    echo " ".$this->nomeiframe.".hide();\n";
    echo "}\n";
    echo "</script>\n";

  }
}


?>