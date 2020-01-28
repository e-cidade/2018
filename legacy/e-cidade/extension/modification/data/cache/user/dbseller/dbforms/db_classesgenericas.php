<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
//|16|// passando db_ voce pode colocar o nome que quiser...
class cl_iframe_seleciona {
   var $input_hidden    = false;
   //|30|//coloca um input hidden
   var $desabilitados = true;
   //|30|//quando for false, ele não irá retornar as chaves que estiverem desabilitas
   var $fieldset    = true;
   //|30|//coloca o iframe entre quatro linhas
   var $sql        = null;
   //|30|//sql para montar os campos
   var $sql_disabled  = null;
   //sql para dizer quais os campos devem iniciar marcados
   var $sql_marca  = null;
   //sql para dizer quais os campos devem iniciar marcados
   var $checked    = null;
   //indica se todos as linhas devem abrir marcadas ou naum
   var $chaves     = null;
   //|30|//campos que deseja retorna num input apenas
   var $campos     = null;
   //|30|//campos que serão mostrados
   var $legenda    = "DADOS";
   //|30|//legenda do fieldset
   var $alignlegenda = "center";
   //|30|//Alinhamento da legenda no fieldset
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
   var $dbscript = null;
    //|30|// função java script no campo de marcar
   var $marcador = true;
    //|30|// desabilita a opcao de clicar no marcador e inveter os checados
   var $js_marcador = null;
    //|30|// função java script quando clicar no marcador
   var $mostra_totalizador  = "N";
   // Opcao para mostrar total de registro da consulta
   var $posicao_totalizador = "A";
   // Onde vai ficar o totalizador - A - Acima e B - Abaixo
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
   fputs($fd,' $marcador="'.$this->marcador.'";'."\n");
         if($this->sql!=""){
       fputs($fd,' $sql="'.base64_encode($this->sql).'";'."\n");
     if($this->sql_disabled!=null){
       fputs($fd,' $sql_disabled="'.base64_encode($this->sql_disabled).'";'."\n");
     }
     if($this->sql_marca!=null){
       fputs($fd,' $sql_marca="'.base64_encode($this->sql_marca).'";'."\n");
     }
     if($this->js_marcador!=null){
         fputs($fd,' $js_marcador="'.$this->js_marcador.'";'."\n");
     }
     if($this->dbscript!=null){
         fputs($fd,' $dbscript="'.$this->dbscript.'";'."\n");
     }
     if($this->input_hidden == true){
         fputs($fd,' $input_hidden  = "true";'."\n");
     }
     if($this->chaves!=null){
         fputs($fd,' $chaves="'.$this->chaves.'";'."\n");
     }
     if(isset($this->checked) && $this->checked==true ){
         fputs($fd,' $ckd="true";'."\n");
     }

     fputs($fd,' $mostra_totalizador="'.$this->mostra_totalizador.'";'."\n");
     fputs($fd,' $posicao_totalizador="'.$this->posicao_totalizador.'";'."\n");

     fputs($fd,' $msg_vazio="'.base64_encode($this->msg_vazio).'";'."\n");
       fputs($fd,' $campos="'.base64_encode($this->campos).'";'."\n");
       fputs($fd,' $db_opcao="'.$db_opcao.'";'."\n");
         }
   fputs($fd,'?>  '."\n");

   fclose($fd) or die('erro');

     if($this->fieldset==true){
        echo " <fieldset><Legend align=\"".$this->alignlegenda."\"><b>".$this->legenda."</b></Legend>";
     }

      echo "
              <iframe id=\"ativ\"  frameborder=\"0\" name=\"".$this->iframe_nome."\"   leftmargin=\"0\" topmargin=\"0\" src=\"dbforms/db_classes_iframe_seleciona.php?arquivo=".base64_encode($arquivo)."\" height=\"".$this->iframe_height."\" width=\"".$this->iframe_width."\">
              </iframe> ";
     if($this->fieldset==true){
           echo " </fieldset>";
     }

echo "
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

           //função que irá retornar as chaves selecionadas
           echo "

          function js_retorna_chaves(){
            tabela=".$this->iframe_nome.".document.getElementById('tabela_seleciona');\n
            var coluna='';\n
            var sep='';
            for(i=1; i<tabela.rows.length; i++){\n
              id=tabela.rows[i].id.substr(6);\n";
              if($this->desabilitados == true){
                echo "if(".$this->iframe_nome.".document.getElementById('CHECK_'+id).checked==true ){\n";
              }else{
                echo "if(".$this->iframe_nome.".document.getElementById('CHECK_'+id).checked==true && ".$this->iframe_nome.".document.getElementById('CHECK_'+id).disabled == false){\n";
              }
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

            return coluna ;
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
class cl_iframe_alterar_excluir_html_novo{
   var $js_mouseover     = null;
   //|30|//função executada quando o mouse for passado por cima da linha, é retornado para ela as chaves primarias...
   var $js_mouseout     = null;
   //|30|//função executada quando o mouse for passado por cima da linha, é retornado para ela as chaves primarias...
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
   function iframe_alterar_excluir_html_novo(){
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

     if($this->js_mouseover!=null){
       fputs($fd,' $js_mouseover="'.$this->js_mouseover.'";'."\n");
     }
     if($this->js_mouseout!=null){
       fputs($fd,' $js_mouseout="'.$this->js_mouseout.'";'."\n");
     }

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
     echo" <iframe id=\"\"  frameborder=\"0\" name=\"".$this->iframe_nome."\"   leftmargin=\"0\" topmargin=\"0\" src=\"dbforms/db_classes_iframe_alterar_excluir_htmlnovo.php?arquivo=".base64_encode($arquivo)."\" height=\"".$this->iframe_height."\" width=\"".$this->iframe_width."\">
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
              eval('criatabela.js_alterar('+colunas+')');
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

//|00|//cl_criatabela
//|10|//Cria um iframe com as opções de alterar e excluir por java script. Quando usar a função "js_criaelementos()" será criado um input com os dados de cada linha
//|15|//[variavel] = new cl_alterar_excluir_html;
class cl_iframe_alterar_excluir_html{
   var $js_mouseover     = null;
   //|30|//função executada quando o mouse for passado por cima da linha, é retornado para ela as chaves primarias...
   var $js_mouseout     = null;
   //|30|//função executada quando o mouse for passado por cima da linha, é retornado para ela as chaves primarias...
   var $load = '';
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

     $arquivo = tempnam ("tmp/", "iframe");
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

     if($this->js_mouseover!=null){
       fputs($fd,' $js_mouseover="'.$this->js_mouseover.'";'."\n");
     }
     if($this->js_mouseout!=null){
       fputs($fd,' $js_mouseout="'.$this->js_mouseout.'";'."\n");
     }

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
    function js_retorna_dados(){
            tabela=".$this->iframe_nome.".document.getElementById('tab');
      y='';
      colunas ='';
            for(i=1; i<tabela.rows.length; i++){
        col=tabela.rows[i].cells[0].innerHTML;
        colunas += y+col;
         y='#';
      }
        return  colunas;
          }



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
            ".$this->iframe_nome.".document.form1.conta_linha.value= new Number(conta_linha)+1;
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
   var $identifica    = null;
   var $abas_top      = "44";
   var $abas_left     = "0";
   var $src           = null;
   var $title         = null;
   var $cortexto      = null;
   var $corfundo      = null;
   var $funcao_js     = null;
   var $sizecampo     = null;
   var $disabled      = null;
   var $iframe_width  = "100%";
   var $iframe_height = "100%";
   // var $scrolling = "no";
   var $scrolling = "yes";

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
                divs[j].style.zIndex = 2;
                divs[j].style.width  = (screen.availWidth-10);
                divs[j].style.height = (screen.availHeight-184);
              }else{
                if(divs[j].className == 'tabela'){
                  divs[j].style.visibility = 'hidden';
                  divs[j].style.zIndex = 1;
                  divs[j].style.width = (screen.availWidth-10);
                  divs[j].style.height= (screen.availHeight-184);
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
              document.formaba.<?=$chave?>.style.color ='<?=(isset($this->cortexto[$chave])&&$this->cortexto[$chave]!=""?$this->cortexto[$chave]:"black")?>';
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
          .input-abas::-moz-focus-inner {
            border: 0;
          }
          .input-abas {
             outline : none;

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
        <td align="left" valign="top">
        <table border="0" cellpadding="0" cellspacing="0" marginwidth="0">
        <tr>
 <?
    reset($this->identifica);
    for($w=0; $w<sizeof($this->identifica); $w++){
       $chave     = key($this->identifica);
       $cortexto  = (isset($this->cortexto[$chave])&&$this->cortexto[$chave]!=""?$this->cortexto[$chave]:'black');
       $corfundo  = (isset($this->corfundo[$chave])&&$this->corfundo[$chave]!=""?$this->corfundo[$chave]:'#cccccc');

       $funcao_js = (isset($this->funcao_js[$chave])&&$this->funcao_js[$chave]!=""?$this->funcao_js[$chave]:'');

       $sizecampo = (isset($this->sizecampo[$chave])&&$this->sizecampo[$chave]!=""?$this->sizecampo[$chave]:'10');
       $disabled  = (isset($this->disabled[$chave])&&$this->disabled[$chave]=="true"?'disabled':''); ?>
       <td>
         <table class="bordas" id="<?=$chave?>" border="0" style="border: 3px outset #666666; border-bottom-width: 0px; border-right-width: 1px ;border-right-color: #000000; border-top-color: #3c3c3c; border-right-style: inset; " cellpadding="3" cellspacing="0" >
           <tr>
             <td nowrap>
               <input readonly <?=$disabled?> framename="iframe_<?=$chave?>" name="<?=$chave?>" class="input-abas"  style="font-weight:bold; color:<?=$cortexto?>; background-color:<?=$corfundo?>;" type="button"  value="<?=$this->identifica[$chave]?>" title="<?=$this->title[$chave]?>" size="<?=$sizecampo?>"  onClick="<?=$funcao_js?> mo_camada('<?=$chave?>'); ">
             </td>
           </tr>
         </table>
       </td>
<?     next($this->identifica);
    } ?>

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
         /*echo "<script> document.formaba.$chave.disabled=true; </script>";*/
       } ?>
       <div class="tabela" id="div_<?=$chave?>" style="position:absolute; left:<?=$this->abas_left?>px; top:<?=$this->abas_top?>px;  visibility: visible;">
          <iframe  id='<?=$chave?>' name="iframe_<?=$chave?>" class="bordasi" <?=$src?> frameborder="0" marginwidth="0" leftmargin="0" topmargin="0"   height="<?=$this->iframe_height?>" scrolling="<?=$this->scrolling?>"  width="<?=$this->iframe_width?>" >
          </iframe>
       </div>
 <?    echo " <script>  mo_camada('$chave'); </script> ";
       next($this->identifica);
    } ?>
    </div>
        </td>
      </tr>
      </form>
      </table>
 <?
  reset($this->identifica);
  $chave=key($this->identifica);
  echo " <script> mo_camada('$chave'); </script> ";
   }
}





//|00|//cl_iframe_alterar_excluir
//|10|//Cria um iframe com as opções de alterar e excluir por PHP.
//|15|//[variavel] = new cl_iframe_alterar_excluir;
class cl_iframe_alterar_excluir {
   var $js_mouseover     = null;
   //|30|//função executada quando o mouse for passado por cima da linha, é retornado para ela as chaves primarias...
   var $js_mouseout     = null;
   //|30|//função executada quando o mouse for passado por cima da linha, é retornado para ela as chaves primarias...
   var $formulario   = true;
   //sql para montar os campos
   //|30|//nome e label dos campos
   var $fieldset   = true;
   //sql para montar os campos
   //|30|//nome e label dos campos
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
   var $alignlegenda = "center";
   //|30|//Alinhamento da legenda no fieldset
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
   var $sql_comparar     = "";
   var $sql_servico      = "";
   var $sql_reservasaldo = "";
   var $campos_comparar  = "";
   var $strFormatar      = "1"; #se db_fieldsmemory ira formatar os valores

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
     fputs($fd,' $strFormatar="'.$this->strFormatar.'";'."\n");
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
     if ($this->sql_comparar != ""){
          fputs($fd,' $sql_comparar="'.base64_encode($this->sql_comparar).'";'."\n");
          fputs($fd,' $sql_servico="'.base64_encode($this->sql_servico).'";'."\n");
          fputs($fd,' $sql_reservasaldo="'.base64_encode($this->sql_reservasaldo).'";'."\n");
          fputs($fd,' $campos_comparar="'.base64_encode($this->campos_comparar).'";'."\n");
     }

     if($this->js_mouseover!=null){
       fputs($fd,' $js_mouseover="'.$this->js_mouseover.'";'."\n");
     }
     if($this->js_mouseout!=null){
       fputs($fd,' $js_mouseout="'.$this->js_mouseout.'";'."\n");
     }

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

     if($this->formulario == true){
        echo " <form name='form1' method='post' action='' >";
     }

     if($this->fieldset == true){
       echo "   <fieldset><Legend align=\"".$this->alignlegenda."\"><b>".$this->legenda."</b></Legend>";
     }
     echo "
               <iframe id=\"ativ\"  frameborder=\"0\" name=\"".$this->iframe_nome."\"   leftmargin=\"0\" topmargin=\"0\" src=\"dbforms/db_classes_iframe_alterar_excluir.php?arquivo=".base64_encode($arquivo)."\" height=\"".$this->iframe_height."\" width=\"".$this->iframe_width."\">
               </iframe> ";
     if($this->fieldset == true){
       echo "   </fieldset>";
     }
       if($this->formulario == true){
         echo "   </form>";
       }
     }

}




class cl_arquivo_auxiliar {
//|00|//cl_arquivo_auxiliar
//|10|//Gera no formulario um select multiple com um campo de ancora para inclusao e selecao de item
//|15|//[variavel] = new cl_arquivo_auxiliar;
  var $nome_botao = "db_lanca";
// Incluido para poder ter 2 arquivos auxiliares devido ao nome do botão estar fixo como db_lanca
  var $cabecalho = null;
//|30|//Cabecalho : Descrição que será utilizada no FieldSet
  var $top = null;
//|30|//Tipo de montagem do formulário, 1=vertical ou 2=horizontal
  var $localjan = "(window.CurrentWindow || parent.CurrentWindow).corpo";
//|30|//Cabecalho : Descrição que será utilizada no FieldSet
  var $codigo = null;
//|30|//Código    : Nome do campo para o código da âncora
  var $isfuncnome = false;
//|30|//isfuncnome: Verifica se é a func_nome.php ou func_cgm.php, inverterá a ordem dos campos no retorno da função JavaScript quando informado o código
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
   var $onclick = "";
//|30|//funcao que será executada quando for incluido um objeto
   var $vwidth = 250;
//|30|//Largura do objeto select Padrão = 250
   var $passar_query_string_para_func = "";
//|30|//Serve para caso seja necessário passar uma query string para a func de pesquisa... Ex.: Para filtrar por instituição
   var $ordenar_itens = false;
//|30|//True se desejar ordenar os itens por seu value dentro do SELECT
   var $concatenar_codigo = false;
//|30|//True se desejar concatenar código dos itens com sua descrição no SELECT
   var $obrigarselecao  = true;
//|30|//True se desejar obrigar a seleção de itens ao utilizar a função js_campo_recebe_valores
   var $tamanho_campo_descricao  = 25;
//|30|//Setar o tamanho do campo da descrição
   var $parametros = "";
//|30|//Setar o nome da janela
   var $nomejanela = "Pesquisa";
// Quando preenchido usado para passar como parameto ao campo func_arquivo

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//|30|//
//|30|//VARIÁVEIS ABAIXO, CRIADAS ESPECIALMENTE PARA UTILIZAÇÃO NO MÓDULO PESSOAL
   var $executa_script_apos_incluir = "";
//|30|//Script definido pelo programador que será executado após clicar em lançar
   var $executa_script_lost_focus_campo = "";
//|30|//Script definido pelo programador que será executado após o campo do código ter perdido o foco
   var $mostrar_botao_lancar = true;
//|30|//Se true: mostrará o botão lançar; Se false: Não mostrará o botão lançar. Default = true;
   var $completar_com_zeros_codigo = false;
//|30|//A princípio, utilizado somente para quando for rubrica (Módulo Pessoal), setando true, completará com zeros à esquerda formando um código com 4 caracteres
   var $Labelancora = "";
//|30|//Em caso de true, é gerada um função js_campo_recebe_valores concatenando com o nome para cada objeto criado
   var $lFuncaoPersonalizada = false;


  function funcao_gera_formulario( $sClassAuxiliar=null ) {

    echo "<tr id='tr_inicio_{$this->nomeobjeto}'>\n";
    echo "<td colspan=\"2\">\n";
    echo "<table align=\"center\" $sClassAuxiliar>\n";
    echo "  <tr>\n";
    echo "    <td nowrap title=\"\" > \n";
    echo "      <fieldset id='fieldset_{$this->nomeobjeto}'><Legend>".$this->cabecalho."</legend>\n";
    echo "      <table border=\"0\">\n";
    echo "        <tr>\n";
    echo "          <td nowrap >\n<b>";

    $clrotulocampo = new rotulocampo;
    $clrotulocampo->label($this->codigo);
    $clrotulocampo->label($this->descr);
    $codfilho = "L".$this->codigo;

    $ancora = trim($this->Labelancora);
    if( empty($ancora) ) {
      $labelAncora = $GLOBALS["$codfilho"];
    } else {
      $labelAncora = $this->Labelancora;
    }

    db_ancora($labelAncora,"js_BuscaDadosArquivo".$this->nomeobjeto."(true);",$this->db_opcao);
    db_input($this->codigo,8,'',true,'text',$this->db_opcao," onchange='js_BuscaDadosArquivo".$this->nomeobjeto."(false);' tabIndex='0'");

    if($this->tipo==1)
      echo "            <br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";
    db_input($this->descr,$this->tamanho_campo_descricao,'',true,'text',3);
    if($this->tipo==1)
      echo "            <br>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp";

    if($this->mostrar_botao_lancar == true)
      echo "            <input id=\"".$this->nome_botao."\" name=\"".$this->nome_botao."\" type=\"button\" value=\"Lançar\" >\n";
    echo "        </b></td>\n";
    echo "        </tr>\n";
    echo "        <tr>\n ";
    echo "          <td align=\"center\" >\n";
    echo "            <select name=\"".$this->nomeobjeto."[]\" id=\"".$this->nomeobjeto."\" size=\"".$this->linhas."\" style=\"width:".$this->vwidth."px\" multiple onDblClick=\"js_excluir_item".$this->nomeobjeto."()\">\n";
    if(!empty($this->sql_exec)){
       $result = db_query($this->sql_exec);
       for($i=0;$i<pg_numrows($result);$i++){
         echo "              <option value='".pg_result($result,$i,$this->codigo)."'>".pg_result($result,$i,$this->descr)."</option>\n";
       }
    }
    echo "            </select> \n";
    echo "          </td>\n";
    echo "        </tr>\n";
    echo "        <tr>\n";
    echo "          <td align=\"center\"><strong>\n";
    echo "            Dois Cliques sobre o item o exclui.</strong>\n";
    echo "          </td>\n";
    echo "        </tr>\n";
    echo "      </table>\n";
    echo "      </fieldset>\n";
    echo "    </td>\n";
    echo "  </tr>\n";
    echo "</table>\n";
    echo "<script>\n";
    echo "function js_atualiza_item".$this->nomeobjeto."(){\n";
    echo "  var F = document.getElementById(\"".$this->nomeobjeto."\").options;\n";
    echo "  if(F.length==0){\n";
    echo "    alert('Cadastre um item para prosseguir.');\n";
    echo "    document.form1.".$this->codigo.".focus();\n";
    echo "    return false;\n";
    echo "  }else{  \n";
    echo "    for(var i = 0;i < F.length;i++) {\n";
    echo "      F[i].selected = true;\n";
    echo "    }\n";
    echo "  }\n";
    echo "  return true;\n";
    echo "}\n";
    // esta função retorna os valores do objeto para uma variável
    echo "\n\n\n// esta função abaixo, retorna os valores do objeto ".$this->nomeobjeto." para uma variável\n";
    echo "// utlize o seguinte javaScript:\n";
    echo "// variavel_exempo = js_campo_recebe_valores(); *** a variavel_exempo receberá os valores selecionados\n\n";

    if ($this->lFuncaoPersonalizada) {
      echo "function js_campo_recebe_valores_".$this->nomeobjeto." (){\n";
    } else {
      echo "function js_campo_recebe_valores (){\n";
    }
    echo "  var F = document.getElementById(\"".$this->nomeobjeto."\").options;\n";
    echo "  variavel_recebe_valores = ''; \n";
    echo "  virgula = '';\n";
    echo "  lengthcampo = F.length;\n";
    if($this->obrigarselecao == false){
      echo "  lengthcampo = 1;\n";
    }
    echo "  if(lengthcampo==0){\n";
    echo "    alert('Cadastre um item para prosseguir.');\n";
    echo "    document.form1.".$this->codigo.".focus();\n";
    echo "    return false;\n";
    echo "  }else{  \n";
    echo "    for(var i = 0;i < F.length;i++) {\n";
    echo "      variavel_recebe_valores += virgula+F[i].value;\n";
    echo "      virgula = ',';\n";
    echo "    }\n";
    echo "  }\n";
    echo "  return variavel_recebe_valores;\n";
    echo "}\n";

    echo "function js_excluir_item".$this->nomeobjeto."(){\n";
    echo "  var F = document.getElementById(\"".$this->nomeobjeto."\");\n";
    echo "  if(F.length == 1)\n";
    echo "    F.options[0].selected = true;\n";
    echo "  var SI = F.selectedIndex;\n";
    echo "  if(F.selectedIndex != -1 && F.length > 0) {\n";
    echo "    F.options[SI] = null;\n";
    echo "    js_trocacordeselect();\n";

    echo "    if(SI <= (F.length - 1))\n";
    echo "      F.options[SI].selected = true;\n";

    echo "  }\n";
    echo "}\n";

    echo "function js_insSelect".$this->nomeobjeto."(){\n";
    echo "  ".$this->onclick."\n";
    echo "  var texto=document.form1.".$this->descr.".value;\n";
    echo "  var valor=document.form1.".$this->codigo.".value;\n";

    $variavel_text01 = "F.options[F.length-1].text";
    $variavel_text02 = "F.options[y-1].text";
    $variavel_text03 = "texto";
    if($this->concatenar_codigo == true){
      $variavel_text01 = "F.options[F.length-1].value+' - '+F.options[F.length-1].text";
      $variavel_text02 = "F.options[y-1].value+' - '+F.options[y-1].text";
      $variavel_text03 = "valor+' - '+texto";
    }
    echo "  if(texto != \"\" && valor != \"\"){\n";
    echo "    var F = document.getElementById(\"".$this->nomeobjeto."\");\n";
    echo "    var valor_default_novo_option = F.length;\n";
    echo "    var testa = false;\n";
    echo "    for(var x = 0; x < F.length; x++){\n";
    echo "      if(F.options[x].value == valor){\n";
    echo "        testa = true;\n";
    echo "        break;\n";
    echo "      }\n";
    echo "    }\n";
    echo "    if(testa == false){\n";
    if($this->ordenar_itens == true){
    echo "      if(F.length > 0){;\n";
    echo "        for(valor_default_novo_option=0;valor_default_novo_option<F.length;valor_default_novo_option++){\n";
    echo "          testavalor1 = new Number(valor);";
    echo "          testavalor2 = new Number(F.options[valor_default_novo_option].value);";
    echo "          if(testavalor1 < testavalor2){\n";
    echo "            break;\n";
    echo "          }\n";
    echo "        }\n";
    echo "        F.options[F.length] = new Option(".$variavel_text01.",F.options[F.length-1].value);\n";
    echo "        for(y=F.length-1;valor_default_novo_option<y;y--){\n";
    echo "          F.options[y] = new Option(".$variavel_text02.",F.options[y-1].value);\n";
    echo "        }\n";
    echo "      }\n";
    }
    echo "      F.options[valor_default_novo_option] = new Option(".$variavel_text03.",valor);\n";
    echo "      for(i=0;i<F.length;i++){\n";
    echo "        F.options[i].selected = false;\n";
    echo "      }\n";
    echo "      F.options[valor_default_novo_option].selected = true;\n";
    echo "      js_trocacordeselect();\n";
    echo "    }\n";
    echo "  }\n";
/*
      if(obj2.length > 0){
      }
      // Inclui o item que esta vindo do select EMISSOR
      obj2.options[x] = new Option(obj1.options[i].text,obj1.options[i].value);
*/

    echo "  texto=document.form1.".$this->descr.".value=\"\";\n";
    echo "  valor=document.form1.".$this->codigo.".value=\"\";\n";

    if($this->mostrar_botao_lancar == true)
      echo "  document.form1.".$this->nome_botao.".onclick = '';\n";
    echo "  ".$this->executa_script_apos_incluir.";\n";
    echo "}\n";

    echo "function js_BuscaDadosArquivo".$this->nomeobjeto."(chave){\n";
    if($this->mostrar_botao_lancar == true)
      echo "  document.form1.".$this->nome_botao.".onclick = '';\n";
    echo "  if(chave){\n";
    echo "    js_OpenJanelaIframe('".($this->localjan != "CurrentWindow.corpo"?$this->localjan:"CurrentWindow.corpo")."','".$this->nomeiframe."','".$this->func_arquivo."?funcao_js=parent.".$this->funcao_js."|".$this->codigo."|".$this->descr.$this->passar_query_string_para_func."'".($this->parametros != ""?$this->parametros:"").",'$this->nomejanela',true".($this->top!=null?",'".$this->top."'":"").");\n";
    echo "  }else{\n";
    if($this->completar_com_zeros_codigo == true){

      echo "    quantcaracteres = document.form1.".$this->codigo.".value.length;\n";
      echo "    for(i=quantcaracteres;i<4;i++){\n";
      echo "      document.form1.".$this->codigo.".value = '0'+document.form1.".$this->codigo.".value;\n";
      echo "    }\n";
    }

    echo "    js_OpenJanelaIframe('".($this->localjan != "CurrentWindow.corpo"?$this->localjan:"CurrentWindow.corpo")."','".$this->nomeiframe."','".$this->func_arquivo."?pesquisa_chave='+document.form1.".$this->codigo.".value+'&funcao_js=parent.".$this->funcao_js_hide.$this->passar_query_string_para_func."'".($this->parametros != ""?$this->parametros:"").",'Pesquisa',false);\n";
    echo "  }\n";
    echo "}\n";


    echo "function ".$this->funcao_js."(chave,chave1){\n";
    echo "  document.form1.".$this->codigo.".value = chave;\n";
    echo "  document.form1.".$this->descr.".value = chave1;\n";
    echo "  ".$this->nomeiframe.".hide();\n";
    echo "  ".$this->executa_script_lost_focus_campo.";\n";

    if($this->mostrar_botao_lancar == true)
      echo "  document.form1.".$this->nome_botao.".onclick = js_insSelect".$this->nomeobjeto.";\n";
    echo "}\n";


    echo "function ".$this->funcao_js_hide."(chave,chave1){\n";
    if($this->isfuncnome==false){
    echo "  document.form1.".$this->descr.".value = chave;\n";
    echo "  if(chave1){\n";
    }else{
    echo "    document.form1.".$this->descr.".value = chave1;\n";
    echo "  if(chave){\n";
    }
    echo "    document.form1.".$this->codigo.".value = '';\n";
    echo "    document.form1.".$this->codigo.".focus();\n";
    echo "  }else{\n";
    echo "    ".$this->executa_script_lost_focus_campo.";\n";
    if($this->mostrar_botao_lancar == true)
      echo "    document.form1.".$this->nome_botao.".onclick = js_insSelect".$this->nomeobjeto.";\n";
    echo "  }\n";
    echo "  ".$this->nomeiframe.".hide();\n";
    echo "}\n";
    echo "</script>\n";

  }
}

class cl_db_estrut{
  var $nivel = null;
  var $mae   = null;
  var $mae_cut   = null;
  var $str   = null;
  var $str_cut= null;
  var $formatado = null;
  var $erro_msg = null;


   // cria variaveis para a função que gera input
   var $nomeform     = "form1";
   var $reload       = false;
   var $size         = '50';
   var $mascara      = true;
   var $input        = false;
   var $nome         = "db_picture";
   var $db_opcao     = 1;
   var $funcao_onchange = null;
   var $autocompletar=false;

  function db_nivel($codigo,$mascara,$formata=false){

    $codigo = str_replace(".","",$codigo);
    $tamanho02   = strlen(str_replace(".","",$mascara));
    $arr_mascara = split("\.",$mascara);
    $tamanho =  count($arr_mascara);

    //rotina que salva em array onde inicia e qual o tamanho de cada nivel
      $arr_tam = array();
      $arr_ini = array();
      $inicio = 0;
      for($i=0; $i<$tamanho; $i++){
     $arr_tam[$i] = strlen($arr_mascara[$i]);
     $arr_ini[$i] = $inicio;
     $inicio += strlen($arr_mascara[$i]);
      }
    //fim

    //rotina que  retorna o nivel
      $cont=($tamanho-1);
  for($i=$cont; $i>= 0;  $i--){
    $tam = $arr_tam[$i];
    $ini = $arr_ini[$i];

    //rotina que monta o numero de zeros que precisa para a comparação do nivel
    $zero="";
    for($h=0; $h<$tam; $h++){
      $zero .="0";
    }

    //rotina que compara o código fornecido com os parametro gerados da mascara
    if(substr($codigo,$ini,$tam) == "$zero"){
      $nivel = $i;
    }else{
      if(empty($nivel)){
        $nivel=$i+1;
      }
      break;
    }
    $this->nivel = $nivel;
  }
    //final
     $this->db_monta($codigo,$mascara,$nivel-1);
     if($formata==false){
       $this->mae = $this->str;
       $this->mae_cut = $this->str_cut;
     }else{
       $this->db_estrutformata($this->str,$mascara);
       $this->mae = $this->formatado;
       $this->db_estrutformata($this->str_cut,$mascara);
       $this->mae_cut = $this->formatado;
     }
  }

  function db_monta($codigo,$mascara,$nivel){
    $codigo = str_replace(".","",$codigo);
    $arr_mascara = split("\.",$mascara);
    $tamanho =  count($arr_mascara);
    $tamanho02   = strlen(str_replace(".","",$mascara));

    //rotina que salva em array onde inicia e qual o tamanho de cada nivel
      $arr_tam = array();
      $arr_ini = array();
      $inicio = 0;
      for($i=0; $i<$tamanho; $i++){
     $arr_tam[$i] = strlen($arr_mascara[$i]);
     $arr_ini[$i] = $inicio;
     $inicio += strlen($arr_mascara[$i]);
      }
    //fim
    //rotina que atualiza a mãe
      $cont=($tamanho-1);
      $str='';
      $cont02="0";
      for($i=0; $i<$nivel; $i++){
  $str .= substr($codigo,$arr_ini[$i],$arr_tam[$i]);
      }
      $cont02 = $tamanho02- strlen($str);
      $zero="";
      for($h=0; $h<$cont02; $h++){
        $zero .="0";
      }

      $this->str     = $str."$zero";
      $this->str_cut = $str;
      return true;
    //final
  }

  function db_estrutformata($codigo,$mascara){
    $codigo = str_replace(".","",$codigo);
    $arr_mascara = split("\.",$mascara);
    $tamanho =  count($arr_mascara);
    $tamanho02   = strlen(str_replace(".","",$mascara));

    //rotina que salva em array onde inicia e qual o tamanho de cada nivel
      $arr_tam = array();
      $arr_ini = array();
      $inicio = 0;
      for($i=0; $i<$tamanho; $i++){
     $arr_tam[$i] = strlen($arr_mascara[$i]);
     $arr_ini[$i] = $inicio;
     $inicio += strlen($arr_mascara[$i]);
      }
      $ponto="";
      $formatado='';
      for($i=0; $i<$tamanho; $i++){
  $formatado .= $ponto.substr($codigo,$arr_ini[$i],$arr_tam[$i]);
  $ponto = ".";
      }
      $this->formatado = $formatado;
  }

  function db_estrut_inclusao($codigo,$mascara,$tabela,$campo,$tipomae){

        $codigo = str_replace(".","",$codigo);

       //rotina que verifica se o estrutural já não foi incluido
   $sql= " select $campo from $tabela where $campo = '".$codigo."' ";
   $result  =  @db_query($sql);
   $this->numrows = pg_numrows($result);
   if($this->numrows>0){
      $this->erro_msg = 'Inclusão abortada. Estrutural já incluido!';
            $this->erro_status = 0;
      return false;
   }
       //
       $this->db_nivel($codigo,$mascara);
       if($this->nivel == "1"){
          $this->erro_msg = 'Estrutural liberado!';
          $this->erro_status = 1;
          return true;
       }

       //rotina que verifica se o código fornecido possui mae
   $sql= " select $tipomae as tipo from $tabela where $campo = '".$this->mae."' ";
   $result  =  @db_query($sql);
   $this->numrows = pg_numrows($result);
   if($this->numrows<1){
      $this->erro_msg = 'Inclusão abortada. Estrutural  acima não encontrado!';
            $this->erro_status = 0;
      return false;
   }else{
      global $tipo;
      db_fieldsmemory($result,0);
      if($tipo=="t"){
        $this->erro_msg = 'Inclusão abortada. Estrutural  acima é uma conta analitica!';
              $this->erro_status = 0;
        return false;
      }
   }
       //fim

       //rotina que verifica se não existe conta de nivel inferior cadastrado
  // $this->db_monta($codigo,$mascara,$this->nivel);

    //rotina que monta o numero de zeros que precisa para a comparação do nivel
          $this->db_monta($codigo,$mascara,$this->nivel);


   $sql= " select $campo from $tabela where  $campo like '".$this->str_cut."%'";
   $result  =  @db_query($sql);
   $this->numrows = pg_numrows($result);
   if($this->numrows>0){
      $this->erro_msg = 'Inclusão abortada. Existe um estrutural  de nível inferior cadastrado!';
            $this->erro_status = 0;
      return false;
   }
       //fim
          $this->erro_msg = 'Estrutural liberado!';
            $this->erro_status = 1;
          return true;

   }

  function db_estrut_exclusao($codigo,$mascara,$tabela,$campo){


  $codigo = str_replace(".","",$codigo);

       //rotina que verifica se o estrutural já não foi incluido
   $sql= " select $campo from $tabela where $campo = '".$codigo."' ";
   $result  =  @db_query($sql);
   $this->numrows = pg_numrows($result);
   if($this->numrows==0){
      $this->erro_msg = 'Exclusão abortada. Estrutural não existe!';
            $this->erro_status = 0;
      return false;
   }
       //
       //verifica o nivel,
       $this->db_nivel($codigo,$mascara);
       if($this->nivel == 9){
          $this->erro_msg = 'Estrutural liberado!';
            $this->erro_status = 1;
          return true;
       }
       //rotina que verifica se não existe conta de nivel inferior cadastrado
   $this->db_monta($codigo,$mascara,$this->nivel);

    //rotina que monta o numero de zeros que precisa para a comparação do nivel
    $resto = strlen($this->str)-strlen($this->str_cut);
    $zero="";
    for($h=0; $h<$resto; $h++){
      $zero .="0";
    }

   $sql= " select $campo from $tabela where  $campo like '".$this->str_cut."%' and substr($campo,".(strlen($this->str_cut)+1).",".$resto.")<>'$zero' ";
   $result  =  @db_query($sql);
   $this->numrows = pg_numrows($result);
   if($this->numrows>0){
      $this->erro_msg = 'Exclusão abortada. Existe um estrutural  de nível inferior cadastrado!';
            $this->erro_status = 0;
      return false;
   }
       //fim
          $this->erro_msg = 'Estrutural liberado!';
            $this->erro_status = 1;
          return true;

   }


//|00|//cl_estrutura
//|10|//pega a picture de um determinado campo do orcparametro e gera um input text com a formatacao da mesma
//|15|//[variavel] = new cl_estrutura;
  function db_mascara($id){
    $nome = $this->nome;
    $x = "L".$nome;
    $y = "T".$nome;
    global $mascara,$db77_descr,$$nome,$$x,$$y;
    $clrotulocampo = new rotulocampo;
    $clrotulocampo->label($nome);
    $result  = db_query("select db77_descr, db77_estrut as mascara from db_estrutura where db77_codestrut=$id");
    $numrows = pg_num_rows($result);
    if ($numrows >0){
        db_fieldsmemory($result,0);
        $tamanho=strlen($mascara);
    } else {
        db_msgbox('Não existe estrutural com o código fornecido. Verifique! ');
        exit;
    }


    if($this->funcao_onchange!=null){
      if($this->autocompletar==false && $this->reload == false){
        $funcao = $this->funcao_onchange;
      }else{
        $funcao = "onChange='js_mascara02_$nome(this.value);".$this->funcao_onchange.";'";
      }
    }else{
      $funcao="onChange=\"js_mascara02_$nome(this.value);\"";
    }
    if($this->mascara==true){
?>
  <tr>
    <td nowrap title="Máscara do campo <?=@$db77_descr?>">
      <b>Máscara:</b>
    </td>
    <td>

     <input name="mascara" style="background-color:#DEB887" readonly size='<?=$this->size?>' type="text"  value="<?=$mascara?>"    >
    </td>
  </tr>
<?
   }
   if($this->input==false){
?>
   <tr>
    <td nowrap title="<?=$$y?>">
      <b> <?=@$$x?></b>
    </td>
    <td title="<?=$this->nome?>">
<?

   }
?>
      <input title="<?=@$$title?>" name="<?=$this->nome?>" value="<?=@$$nome?>" maxlength='<?=$tamanho?>' size='<?=$this->size?>' type="text"   onKeyPress="return js_mascara01_<?=$nome?>(event,this.value);"  <?=$funcao?> <?=($this->db_opcao==22||$this->db_opcao==33||$this->db_opcao==3?"readonly style=\"background-color:#DEB887\" ":"")?> >
<?
   if($this->input==false){
?>
    </td>
   </tr>
<?
    }
?>
    <script>
    function js_mascara01_<?=$nome?>(evt,obj){
      var evt = (evt) ? evt : (window.event) ? window.event : "";
      if(evt.charCode >47 && evt.charCode <58 ){//8:backspace|46:delete|190:.
  str='<?=$mascara?>';
  tam=obj.length;
  dig=str.substr(tam,1);
  if(dig=="."){
    document.<?=$this->nomeform?>.<?=$this->nome?>.value=obj+".";
  }
  return true;
      }else if(evt.charCode=='0'){
  return true;
      }else{
  return false;
      }
    }
    function js_mascara02_<?=$nome?>(obj){
      obj=document.<?=$this->nomeform?>.<?=$this->nome?>.value;
       while(obj.search(/\./)!='-1'){
   obj=obj.replace(/\./,'');
       }
<?
   if($this->autocompletar==true){
?>
     tam=<?=strlen(str_replace(".","",$mascara))?>;
     for(i=obj.length; i<tam; i++){
       obj=obj+"0";
     }
<?
   }
?>
      //analise da estrutura passada
       str='<?=$mascara?>';
       nada='';
       matriz=str.split(nada);
       tam=matriz.length;
       arr=new Array();
       cont=0;
       for(i=0; i<tam; i++){
   if(matriz[i]=='.'){
     arr[cont]=i;
       cont++;
   }
       }
       //fim
       for(i=0; i<arr.length; i++){
   pos=arr[i];
   strpos=obj.substr(pos,1);
   if(strpos!='' && strpos!='.'){
     ini=obj.slice(0,pos);
     fim=obj.slice(pos);
     obj=ini+"."+fim;
   }
       }
      document.<?=$this->nomeform?>.<?=$this->nome?>.value=obj;
<?
    if($this->reload==true){
?>
      obj=document.createElement('input');
      obj.setAttribute('name','db_atualizar');
      obj.setAttribute('type','hidden');
      obj.setAttribute('value',"atualizar");
      document.<?=$this->nomeform?>.appendChild(obj);
      document.<?=$this->nomeform?>.submit();
<?
    }
?>
    }
    function js_mascara03_<?=$nome?>(obj){
      obj=document.<?=$this->nomeform?>.<?=$this->nome?>.value;
       while(obj.search(/\./)!='-1'){
   obj=obj.replace(/\./,'');
       }
<?
   if($this->autocompletar==true){
?>
     tam=<?=strlen(str_replace(".","",$mascara))?>;
     for(i=obj.length; i<tam; i++){
       obj=obj+"0";
     }
<?
   }
?>
      //analise da estrutura passada
       str='<?=$mascara?>';
       nada='';
       matriz=str.split(nada);
       tam=matriz.length;
       arr=new Array();
       cont=0;
       for(i=0; i<tam; i++){
   if(matriz[i]=='.'){
     arr[cont]=i;
       cont++;
   }
       }
       //fim
       for(i=0; i<arr.length; i++){
   pos=arr[i];
   strpos=obj.substr(pos,1);
   if(strpos!='' && strpos!='.'){
     ini=obj.slice(0,pos);
     fim=obj.slice(pos);
     obj=ini+"."+fim;
   }
       }
  document.<?=$this->nomeform?>.<?=$this->nome?>.value=obj;
    }
    </script>

<?
  }
}

// CLASSE PARA GERAR FORM PARA RELATÓRIOS E CONSULTAS DA FOLHA
class cl_formulario_rel_pes {

  var $anonome = "anofolha"; // Nome do campo ANOFOLHA.
  var $mesnome = "mesfolha"; // Nome do campo MESFOLHA.
  var $js_anomes = "";       // JS script para ano e mes.

  var $re1nome = "registro1"; // Nome do campo Registro 1.
  var $re2nome = "registro2"; // Nome do campo Registro 2.
  var $re3nome = "selregist"; // Nome do objeto de seleção de registros.
  var $re4nome = "Matrícula"; // Nome para o Label do resumo , intervalo e selecao.

  var $or1nome = "orgao1"; // Nome do campo ÓRGÃO 1.
  var $or2nome = "orgao2"; // Nome do campo ÓRGÃO 2.
  var $or3nome = "selorg"; // Nome do objeto de seleção de órgãos.
  var $or4nome = "Órgão";  // Nome para o Label do resumo , intervalo e selecao.

  var $rc1nome = "recur1"; // Nome do campo RECURSO 1.
  var $rc2nome = "recur2"; // Nome do campo RECURSO 2.
  var $rc3nome = "selrec"; // Nome do objeto de seleção de recurso.
  var $rc4nome = "Recurso";  // Nome para o Label do resumo , intervalo e selecao.

  var $lo1nome = "lotacao1"; // Nome do campo LOTAÇÃO 1.
  var $lo2nome = "lotacao2"; // Nome do campo LOTAÇÃO 2.
  var $lo3nome = "sellotac"; // Nome do objeto de seleção de lotações.
  var $lo4nome = "Lotação";  // Nome para o Label do resumo , intervalo e selecao.

  var $ru1nome = "rubrica1"; // Nome do campo RUBRICA 1.
  var $ru2nome = "rubrica2"; // Nome do campo RUBRICA 2.
  var $ru3nome = "selrubri"; // Nome do objeto de seleção de rubricas.
  var $ru4nome = "Rubrica";  // Nome para o Label do resumo , intervalo e selecao.

  var $tr1nome = "local1"; // Nome do campo LOCAL 1.
  var $tr2nome = "local2"; // Nome do campo LOCAL 2.
  var $tr3nome = "sellocal"; // Nome do objeto de seleção de locais.
  var $tr4nome = "Locais de trabalho";  // Nome para o Label do resumo , intervalo e selecao.

  var $ca1nome = "cargo1";  // Nome do campo Cargo 1.
  var $ca2nome = "cargo2";  // Nome do campo Cargo 2.
  var $ca3nome = "selcargo"; // Nome do objeto de seleção de Cargos.
  var $ca4nome = "Função";  // Nome para o Label do resumo , intervalo e selecao.

  var $tfonome = "tipofol";  // Nome do campo TIPO DE FOLHA.
  var $tponome = "tipopon";  // Nome do campo TIPO DE PONTO.
  var $trenome = "tipores";  // Nome do campo RESUMO.
  var $tfinome = "tipofil";  // Nome do campo TIPO DE FILTRO.
  var $mornome = "mostord";  // Nome do campo ORDEM.
  var $masnome = "mostasc";  // Nome do campo TIPO DE ORDEM.
  var $mtonome = "mosttot";  // Nome do campo TOTALIZAÇÃO.
  var $qbrnome = "qbrapag";  // Nome do campo Quebrar por página.
  var $aignome = "atinpen";  // Nome do campo Inativos / Pensionistas, Ativos ou Geral.
  var $selnome = "selecao";  // Nome do campo SELECAO.
  var $previdnome = "previd"; // Nome do campo com as tabelas da previdência e INSS
  var $tipoarqnome = "tipoarquivo";

  var $campo_auxilio_regi = "campo_auxilio_regi";  // Nome do campo de auxílio dos registros selecionados.
  var $campo_auxilio_orga = "campo_auxilio_orga";  // Nome do campo de auxílio dos órgãos selecionados.
  var $campo_auxilio_recu = "campo_auxilio_recu";  // Nome do campo de auxílio dos recursos selecionados.
  var $campo_auxilio_lota = "campo_auxilio_lota";  // Nome do campo de auxílio das lotações selecionadas.
  var $campo_auxilio_rubr = "campo_auxilio_rubr";  // Nome do campo de auxílio das rubricas selecionadas.
  var $campo_auxilio_loca = "campo_auxilio_loca";  // Nome do campo de auxílio dos locais selecionados.
  var $campo_auxilio_carg = "campo_auxilio_carg";  // Nome do campo de auxílio das cargos selecionadas.

  var $onchres = "";    // JavaScript rodado no onChange do campo tipo de filtro.
  var $onchpad = false; // True para usar onChange padrão do campo tipo de filtro.

  var $manomes = true;  // Mostrar ano e mês no formulário.
  var $desabam = false; // Desabilitar ano e mês
  var $valpadr = true;  // Mostrar data atual nos campos que tem data, ano ou mês

  var $usaregi = false; // Usar registro.
  var $usaorga = false; // Usar órgao.
  var $usarecu = false; // Usar recursos.
  var $usalota = false; // Usar lotação.
  var $usarubr = false; // Usar rubrica.
  var $usaloca = false; // Usar local de trabalho.
  var $usacarg = false; // Usar cargo.

  var $uniregi = false; // Mostrar um campo para registro.
  var $intregi = false; // Mostrar um intervalo de registros com registro inicial ou final.
  var $selregi = false; // Mostrar um objeto para registros selecionados.

  var $uniorga = false; // Mostrar um campo para órgao.
  var $intorga = false; // Mostrar um intervalo de órgao com órgao inicial ou final.
  var $selorga = false; // Mostrar um objeto para órgãos selecionados.

  var $unirecu = false; // Mostrar um campo para recurso.
  var $intrecu = false; // Mostrar um intervalo de recurso com recurso inicial ou final.
  var $selrecu = false; // Mostrar um objeto para recurso selecionados.

  var $unilota = false; // Mostrar um campo para lotação.
  var $intlota = false; // Mostrar um intervalo de lotações com lotação inicial ou final.
  var $sellota = false; // Mostrar um objeto para lotações selecionadas.

  var $unirubr = false; // Mostrar um campo para rubrica.
  var $intrubr = false; // Mostrar um intervalo de rubricas com rubrica inicial ou final.
  var $selrubr = false; // Mostrar um objeto para rubricas selecionadas.

  var $uniloca = false; // Mostrar um campo para local de trabalho.
  var $intloca = false; // Mostrar um intervalo de local de trabalho com local inicial ou final.
  var $selloca = false; // Mostrar um objeto para locais de trabalho selecionados.

  var $unicarg = false; // Mostrar um campo para cargo.
  var $intcarg = false; // Mostrar um intervalo de cargos com cargo inicial ou final.
  var $selcarg = false; // Mostrar um objeto para cargos selecionados.

  var $tipofol = true;  // Mostrar o tipo de folha (gerfsal, gerfres, gerffer, etc...).
  var $tipopon = true;  // Mostrar o tipo de ponto (pontofs, pontofx, pontofa, etc...).
  var $tipores = true;  // Mostrar tipo de resumo (geral, por lotação, por registro, etc...).
  var $tipoarq = false; // Lista de extensões de arquivos para o gerador de relatório
  var $mostord = true;  // Mostrar ordem (alfabética, numérica, etc...).
  var $mosttot = true;  // Mostrar totalização (por conta, por registro, etc...).
  var $mostasc = false; // Mostrar se é em ordem ordem ascendente ou descendente.
  var $mostaln = false; // Mostrar se é em ordem ordem alfabética ou numérica.
  var $mostnal = false; // Mostrar se é em ordem ordem numérica ou alfabética.
  var $qbrapag = false; // Quebrar por página (Sim / Não).
  var $atinpen = false; // Mostrar Inativos / Pensionistas, Ativos ou Geral.
  var $selecao = false; // Mostrar seleção.
  var $usarprevid = false; // Mostrar tabelas da previdência e INSS

  var $arr_tipofol = Array(); // Array com values e tipos de folha que deseja mostrar.
  var $arr_tipopon = Array(); // Array com values e tipos de ponto que deseja mostrar.
  var $arr_tipores = Array(); // Array com values e tipos de resumo que deseja mostrar.
  var $arr_mostord = Array(); // Array com values e tipos de ordem em que deseja mostrar.
  var $arr_mosttot = Array(); // Array com values e tipos de totalização.
  var $arr_tipoarq = Array(); //Array com os tipos de arquivos

  var $mbgerar = false;       // Colocar um botão gerar no final, já buscando os dados.
  var $jsgerar = "js_gerar_consrel();"; // JavaScript que será chamado ao clicar no botão gerar.
  var $relarqu = "";          // Nome do arquivo que será chamado
  var $formnam = "form1";     // Nome do formulário do arquivo
  var $jsconsr = "";          // JavaScript chamado na função js_gerar_consrel().

  var $arr_tiposel = Array(); // Array que guarda o tipo de seleção (Por matrícula, por lotação ou por rubricas).
                              // Depois, testará se mostrará somente os selecionados ou intervalo.
  var $valortipores = "g";    // Tipo de seleção para quando tiver intervalos no form
  var $strngtipores = "";     // String com os tipos de resumo:
                              // "g" => "Geral"
                              // "l" => "Lotação"
                              // "m" => "Matrícula"
                              // "o" => "Órgão"
                              // "s" => "Recurso"
                              // "r" => "Rubrica"
                              // "t" => "Local de trabalho"

  var $selregime    = false;  // Se o usuário poderá selecionar o regime
  var $nomregime    = "regime"; // Nome do campo de seleção do regime
  var $resumopadrao = "";     // Qual tipo de resumo será selecionado como default
  var $filtropadrao = "";     // Qual tipo de filtro será selecionado como default
  var $complementar = "";     // Value do gerfcom no tipo de folha ou ponto
  var $comnome      = "complementar";     // Nome do campo com as complementares
  var $tipresumo    = "Tipo de Resumo";   // Label do tipo de resumo
  var $tipordem     = "Ordem";            // Label da ordem
  var $testarescisaoregi = "";            // Testar se funcionário foi rescindido ou não.
  var $whereprevid  = "";     // Where que será usado ao buscar tabelas da previdência e INSS
  var $camposprevid = "r33_codtab, r33_nome";     // Campos que serão usados ao buscar tabelas da previdência e INSS
  var $linhasSelecion = 12;
  var $usaLotaFieldsetClass = false;      // Opacao para estilização adicional do fieldset
  var $suplementar = "";      // Value do gerfsal no tipo de folha ou ponto

  function cl_formulario_rel_pes(){
    $this->rotulo = new rotulocampo;
    $this->clarquivo_auxiliar = new cl_arquivo_auxiliar;
  }

  function atualiza_variaveis_filtro(){
    // $arr_qualres[index] = 'm'; -> Indice equivale à tabela rhpessoal
    // $arr_qualres[index] = 'o'; -> Indice equivale à tabela orcorgao
    // $arr_qualres[index] = 'l'; -> Indice equivale à tabela lotacao
    // $arr_qualres[index] = 'r'; -> Indice equivale à tabela rhrubricas
    // $arr_qualres[index] = 't'; -> Indice equivale à tabela rhlocaltrab
    // $arr_qualres[index] = 'c'; -> Indice equivale à tabela rhfuncao
    // $arr_qualres[index] = 's'; -> Indice equivale à tabela orctiporec
    if((isset($GLOBALS[$this->trenome]) && trim($GLOBALS[$this->trenome]) != "") || trim($this->resumopadrao) != ""){
      $index = isset($GLOBALS[$this->trenome]) ? $GLOBALS[$this->trenome] : $this->resumopadrao;
      if((isset($GLOBALS[$this->tfinome])) || trim($this->filtropadrao) != ""){
        $tipo_de_selecao = isset($GLOBALS[$this->tfinome]) ? $GLOBALS[$this->tfinome] : $this->filtropadrao;
        $this->selregi = false;
        $this->selorga = false;
        $this->sellota = false;
        $this->selrubr = false;
        $this->selloca = false;
        $this->selcarg = false;
        $this->selrecu = false;
        if($index == "m"){
          if($tipo_de_selecao == "i"){
            $this->limpa_camp_selecionad("");
            $this->intregi = true;
          }else if($tipo_de_selecao == "s"){
            $this->limpa_camp_selecionad("regi");
            $this->selregi = true;
            $this->usaregi = false;
          }else{
            $this->usaregi = true;
          }
        }else if($index == "o"){
          if($tipo_de_selecao == "i"){
            $this->limpa_camp_selecionad("");
            $this->intorga = true;
          }else if($tipo_de_selecao == "s"){
            $this->limpa_camp_selecionad("orga");
            $this->selorga = true;
            $this->usaorga = false;
          }else{
            $this->usaorga = true;
          }
        }else if($index == "s"){
          if($tipo_de_selecao == "i"){
            $this->limpa_camp_selecionad("");
            $this->intrecu = true;
          }else if($tipo_de_selecao == "s"){
            $this->limpa_camp_selecionad("recu");
            $this->selrecu = true;
            $this->usarecu = false;
          }else{
            $this->usarecu = true;
          }
        }else if($index == "l"){
          if($tipo_de_selecao == "i"){
            $this->limpa_camp_selecionad("");
            $this->intlota = true;
          }else if($tipo_de_selecao == "s"){
            $this->limpa_camp_selecionad("lota");
            $this->sellota = true;
            $this->usalota = false;
          }else{
            $this->usalota = true;
          }
        }else if($index == "r"){
          if($tipo_de_selecao == "i"){
            $this->limpa_camp_selecionad("");
            $this->intrubr = true;
          }else if($tipo_de_selecao == "s"){
            $this->limpa_camp_selecionad("rubr");
            $this->selrubr = true;
            $this->usarubr = false;
          }else{
            $this->usarubr = true;
          }
        }else if($index == "t"){
          if($tipo_de_selecao == "i"){
            $this->limpa_camp_selecionad("");
            $this->intloca = true;
          }else if($tipo_de_selecao == "s"){
            $this->limpa_camp_selecionad("loca");
            $this->selloca = true;
            $this->usaloca = false;
          }else{
            $this->usaloca = true;
          }
        }else if($index == "c"){
          if($tipo_de_selecao == "i"){
            $this->limpa_camp_selecionad("");
            $this->intcarg = true;
          }else if($tipo_de_selecao == "s"){
            $this->limpa_camp_selecionad("carg");
            $this->selcarg = true;
            $this->usacarg = false;
          }else{
            $this->usacarg = true;
          }
        }
      }else{
        $tipo_de_selecao = $this->valortipores;
        $tipo_de_selecao = $GLOBALS[$this->tfinome] = $this->valortipores;
        $this->limpa_camp_selecionad("");
        if($index == "m"){
          $this->usaregi = true;
        }else if($index == "s"){
          $this->usarecu = true;
        }else if($index == "o"){
          $this->usaorga = true;
        }else if($index == "l"){
          $this->usalota = true;
        }else if($index == "r"){
          $this->usarubr = true;
        }else if($index == "t"){
          $this->usaloca = true;
        }else if($index == "c"){
          $this->usacarg = true;
        }
      }
    }
  }

  function limpa_camp_selecionad($tabela){
//    $campo = '$this->campo_auxilio_'.$tabela;
//     if(isset($GLOBALS[eval($campo)])){
//       $valor_variavel = eval($campo);
//       global $$valor_variavel;
//       $$valor_variavel = "";
//    }
    if($tabela != "regi"){
      if(isset($GLOBALS[$this->campo_auxilio_regi])){
        $valor_variavel = $this->campo_auxilio_regi;
        global $$valor_variavel;
        $$valor_variavel = "";
      }
    }
    if($tabela != "recu"){
      if(isset($GLOBALS[$this->campo_auxilio_recu])){
        $valor_variavel = $this->campo_auxilio_recu;
        global $$valor_variavel;
        $$valor_variavel = "";
      }
    }
    if($tabela != "orga"){
      if(isset($GLOBALS[$this->campo_auxilio_orga])){
        $valor_variavel = $this->campo_auxilio_orga;
        global $$valor_variavel;
        $$valor_variavel = "";
      }
    }
    if($tabela != "lota"){
      if(isset($GLOBALS[$this->campo_auxilio_lota])){
        $valor_variavel = $this->campo_auxilio_lota;
        global $$valor_variavel;
        $$valor_variavel = "";
      }
    }
    if($tabela != "rubr"){
      if(isset($GLOBALS[$this->campo_auxilio_rubr])){
        $valor_variavel = $this->campo_auxilio_rubr;
        global $$valor_variavel;
        $$valor_variavel = "";
      }
    }
    if($tabela != "loca"){
      if(isset($GLOBALS[$this->campo_auxilio_loca])){
        $valor_variavel = $this->campo_auxilio_loca;
        global $$valor_variavel;
        $$valor_variavel = "";
      }
    }
    if($tabela != "carg"){
      if(isset($GLOBALS[$this->campo_auxilio_carg])){
        $valor_variavel = $this->campo_auxilio_carg;
        global $$valor_variavel;
        $$valor_variavel = "";
      }
    }
  }

  function monta_form_unicocampo($campo,$ncampo,$dcampo,$tabela,$tamanho=8){
    $this->rotulo->label($ncampo);
    $this->rotulo->label($dcampo);
    $Tncampo = "T".$ncampo;
    $Incampo = "I".$ncampo;
    $Lncampo = "L".$ncampo;
    $Idcampo = "I".$dcampo;
    global $$Tncampo, $$Incampo, $$Lncampo, $$Idcampo;
    echo "
          <tr>
            <td align='left' nowrap title='".$$Tncampo."' >
         ";
         db_ancora(@$$Lncampo, "js_geraform_pesquisa".$tabela."(true,1);", 1);
    echo "
            </td>
            <td align='left' nowrap>
         ";
         db_input($ncampo, $tamanho, $$Incampo, true, 'text', 1, " onchange='js_geraform_pesquisa".$tabela."(false,1);'",$campo);
         db_input($dcampo, 45, $Idcampo, true, 'text', 3, '');
    echo "
            </td>
          </tr>
         ";
  }

  function monta_form_intervalos($campo1,$campo2,$campo4,$ncampo,$tabela,$tamanho=8){
    $this->rotulo->label($ncampo);
    $Tncampo = "T".$ncampo;
    $Incampo = "I".$ncampo;
    $Lncampo = "L".$ncampo;
    global $$Tncampo, $$Incampo, $$Lncampo;
    echo "
          <tr>
            <td align='left' nowrap title='".$campo4."' ><b>
         ";
         db_ancora($campo4, "js_geraform_pesquisa".$tabela."(true,1);", 1);
    echo "
           </b></td>
            <td align='left' nowrap>
         ";
         db_input($ncampo, $tamanho, $$Incampo, true, 'text', 1, " onchange='js_geraform_pesquisa".$tabela."(false,1);js_copiavalor".$tabela."();'",$campo1);
    echo "
              <strong>&nbsp;&nbsp;&nbsp;
         ";
         db_ancora("a", "js_geraform_pesquisa".$tabela."(true,2);", 1);
    echo "
              &nbsp;&nbsp;&nbsp;</strong>
         ";
         db_input($ncampo, $tamanho, $$Incampo, true, 'text', 1, " onchange='js_geraform_pesquisa".$tabela."(false,2);'",$campo2);
    echo "
            </td>
          </tr>
         ";
  }

  function monta_select_tdrtable($arraydados,$nomecampo,$labelimp,$titleimp="",$onchang=""){
    $variavelresumo = $this->trenome;
    $variavelfiltro = $this->tfinome;
    $$variavelresumo = isset($GLOBALS[$this->trenome])?$GLOBALS[$this->trenome]:$this->resumopadrao;
    $$variavelfiltro = isset($GLOBALS[$this->tfinome])?$GLOBALS[$this->tfinome]:$this->filtropadrao;
    global $$variavelresumo, $$variavelfiltro;
    if(count($arraydados) > 1){
      echo "
            <tr>
              <td align='left' nowrap title='".$titleimp."' >
                <strong>".$labelimp."</strong>
              </td>
              <td align='left'>
           ";
      db_select($nomecampo,$arraydados,true,1,$onchang);
      echo "
              </td>
            </tr>
           ";
    }
  }
  function monta_script_pesquisa($tabela,$dcampo1,$dcampo2,$ncampo1,$ncampo2,$ndescri,$formula){
    $testar_rescis = "";
    if($tabela == "rhpessoal" && trim($this->testarescisaoregi) != ""){
      $testar_rescis = "testarescisao=".$this->testarescisaoregi."&";
    }
    $variavel_retorno = "
      <script>
        function js_geraform_pesquisa".$tabela."(mostra,opcao){
          if(mostra==true){
            if(opcao == 1){
              js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_".$tabela."','func_".$tabela.".php?".$testar_rescis."funcao_js=parent.js_geraform_mostra".$tabela."1|".$dcampo1."|".$dcampo2."&instit=".(db_getsession("DB_instit"))."','Pesquisa',true);
            }else{
              js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_".$tabela."','func_".$tabela.".php?".$testar_rescis."funcao_js=parent.js_geraform_mostra".$tabela."2|".$dcampo1."|".$dcampo2."&instit=".(db_getsession("DB_instit"))."','Pesquisa',true);
            }
          }else{
    ";

    if($tabela == "rhrubricas"){
      $variavel_retorno.= "
                 if(js_completa_rubricas){
                   if(opcao == 1){
                     js_completa_rubricas('".$ncampo1."','".$formula."');
                   }else{
                     js_completa_rubricas('".$ncampo2."','".$formula."');
                   }
                 }
      ";
    }

    $variavel_retorno.= "
             if(opcao == 1){
               if(document.".$formula.".".$ndescri."){
                 if(document.".$formula.".".$ncampo1.".value != ''){
                   js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_".$tabela."','func_".$tabela.".php?".$testar_rescis."pesquisa_chave='+document.".$formula.".".$ncampo1.".value+'&funcao_js=parent.js_geraform_mostra".$tabela."&instit=".db_getsession("DB_instit")."','Pesquisa',false);
                 }else{
                   document.".$formula.".".$ndescri.".value = '';
                 }
               }
             }
          }
        }
        function js_geraform_mostra".$tabela."(chave,erro){
          if(document.".$formula.".".$ndescri."){
            document.".$formula.".".$ndescri.".value = chave;
            if(erro==true){
              document.".$formula.".".$ncampo1.".focus();
              document.".$formula.".".$ncampo1.".value = '';
            }
          }
        }
        function js_geraform_mostra".$tabela."1(chave1,chave2){
          document.".$formula.".".$ncampo1.".value = chave1;
          if(document.".$formula.".".$ndescri."){
            document.".$formula.".".$ndescri.".value = chave2;
          }
          db_iframe_".$tabela.".hide();
        }
        function js_geraform_mostra".$tabela."2(chave1,chave2){
          document.".$formula.".".$ncampo2.".value = chave1;
          db_iframe_".$tabela.".hide();
        }
        function js_copiavalor".$tabela."(){
    if(document.".$formula.".".$ncampo2.".value == ''){
      document.".$formula.".".$ncampo2.".value = document.".$formula.".".$ncampo1.".value;
    }
  }
      </script>
    ";
    return $variavel_retorno;
  }

  function monta_script_buscdados($formula,$ncampo,$qcampo){
    $aspas = "";
    if($qcampo == $this->campo_auxilio_lota || $qcampo == $this->campo_auxilio_loca){
      $aspas = "+\"'\"";
    }
    $variavel_retorno = "
      if(document.".$formula.".".$ncampo."){
          valores = '';
          virgula = '';
          for(i=0; i < document.".$formula.".".$ncampo.".length; i++){
            valores+= virgula".$aspas."+document.".$formula.".".$ncampo.".options[i].value".$aspas.";
            virgula = ',';
          }
          document.".$formula.".".$qcampo.".value = valores;
          document.".$formula.".".$ncampo.".selected = 0;
      }
    ";
    return $variavel_retorno;
  }

  function monta_script_gerarif($formula,$ncampo,$qcampo=""){
    if(trim($qcampo) == ""){
      $variavel_retorno = "
        if(document.".$formula.".".$ncampo."){
          qry += com+'".$ncampo."='+document.".$formula.".".$ncampo.".value;
          com = '&';
        }
      ";
    }else{
      $variavel_retorno = "
        if(document.".$formula.".".$ncampo."){
          valores = '';
          virgula = '';
          for(i=0; i < document.".$formula.".".$ncampo.".length; i++){
            valores+= virgula+document.".$formula.".".$ncampo.".options[i].value;
            virgula = ',';
          }
          document.".$formula.".".$qcampo.".value = valores;
          qry += com+'".$ncampo."='+valores;
          com = '&';
        }
      ";
    }
    return $variavel_retorno;
  }

  function gera_form($ano="",$mes=""){
    $this->atualiza_variaveis_filtro();

    if($this->onchpad == true){
      $this->onchres = "onChange='js_geraform_trocaopcao();'";
    }

    $this->rotulo->label("DBtxt23");
    $this->rotulo->label("DBtxt25");
    global $IDBtxt23, $IDBtxt25;
    if($this->manomes == true){
      $opcao = 1;
      if($this->desabam == true){
        $opcao = 3;
      }

      if($this->valpadr == true){
        $campoano = $this->anonome;
        $campomes = $this->mesnome;

        eval('global $S'.$campoano.',$S'.$campomes.';');
        eval('$S'.$campoano.' = "Ano";');
        eval('$S'.$campomes.' = "Mês";');
        global $$campoano, $$campomes ;

        $$campoano = $ano;
        $$campomes = $mes;
        if(trim($ano) == ""){
          $$campoano = db_anofolha();
        }
        if(trim($mes) == ""){
          $$campomes = db_mesfolha();
        }
                $$campoano = db_formatar($$campoano,'s','0',4,'e',0);
                $$campomes = db_formatar($$campomes,'s','0',2,'e',0);
      }

      echo "
            <tr>
              <td align='left' nowrap title='Digite o Ano / Mês de competência' >
                <strong>Ano / Mês:</strong>
              </td>
              <td align='left' nowrap>
           ";
      db_input($this->anonome,4,$IDBtxt23,true,"text",$opcao,$this->js_anomes);
      echo "
              &nbsp;/&nbsp;
           ";
      db_input($this->mesnome,2,$IDBtxt25,true,"text",$opcao,$this->js_anomes);
      echo "
              </td>
            </tr>
           ";
    }
    /*
// inicio
    if($this->usaregi == true){
      if($this->intregi == true){
        $this->monta_form_intervalos($this->re1nome,$this->re2nome,$this->re4nome,"rh01_regist","rhpessoal");
      }else if($this->uniregi == true){
        $this->monta_form_unicocampo($this->re1nome,"rh01_regist","z01_nome","rhpessoal");
      }
      echo $this->monta_script_pesquisa("rhpessoal","rh01_regist","z01_nome",$this->re1nome,$this->re2nome,"z01_nome",$this->formnam);
    }

    if($this->usaorga == true){
      if($this->intorga == true){
        $this->monta_form_intervalos($this->or1nome,$this->or2nome,$this->or4nome,"o40_orgao","orcorgao");
      }else if($this->uniorga == true){
        $this->monta_form_unicocampo($this->or1nome,"o40_orgao","o40_descr","orcorgao");
      }
      echo $this->monta_script_pesquisa("orcorgao","o40_orgao","o40_descr",$this->or1nome,$this->or2nome,"o40_descr",$this->formnam);
    }

    if($this->usarecu == true){
      if($this->intrecu == true){
        $this->monta_form_intervalos($this->rc1nome,$this->rc2nome,$this->rc4nome,"o15_codigo","orctiporec");
      }else if($this->unirecu == true){
        $this->monta_form_unicocampo($this->rc1nome,"o15_codigo","o15_descr","orctiporec");
      }
      echo $this->monta_script_pesquisa("orctiporec","o15_codigo","o15_descr",$this->rc1nome,$this->rc2nome,"o15_descr",$this->formnam);
    }

    if($this->usalota == true){
      if($this->intlota == true){
        $this->monta_form_intervalos($this->lo1nome,$this->lo2nome,$this->lo4nome,"r70_estrut","rhlota",15);
      }else if($this->unilota == true){
        $this->monta_form_unicocampo($this->lo1nome,"r70_estrut","r70_descr","rhlota",15);
      }
      echo $this->monta_script_pesquisa("rhlota","r70_estrut","r70_descr",$this->lo1nome,$this->lo2nome,"r70_descr",$this->formnam);
    }

    if($this->usarubr == true){
      if($this->intrubr == true){
        $this->monta_form_intervalos($this->ru1nome,$this->ru2nome,$this->ru4nome,"rh27_rubric","rhrubricas");
      }else if($this->unirubr == true){
        $this->monta_form_unicocampo($this->ru1nome,"rh27_rubric","rh27_descr","rhrubricas");
      }
      echo $this->monta_script_pesquisa("rhrubricas","rh27_rubric","rh27_descr",$this->ru1nome,$this->ru2nome,"rh27_descr",$this->formnam);
    }

    if($this->usaloca == true){
      if($this->intloca == true){
        $this->monta_form_intervalos($this->tr1nome,$this->tr2nome,$this->tr4nome,"rh55_estrut","rhlocaltrab");
      }else if($this->uniloca == true){
        $this->monta_form_unicocampo($this->tr1nome,"rh55_estrut","rh55_descr","rhlocaltrab");
      }
      echo $this->monta_script_pesquisa("rhlocaltrab","rh55_estrut","rh55_descr",$this->tr1nome,$this->tr2nome,"rh55_descr",$this->formnam);
    }

    if($this->usacarg == true){
      if($this->intcarg == true){
        $this->monta_form_intervalos($this->ca1nome,$this->ca2nome,$this->ca4nome,"rh37_funcao","rhfuncao");
      }else if($this->unicarg == true){
        $this->monta_form_unicocampo($this->ca1nome,"rh37_funcao","rh37_descr","rhfuncao");
      }
      echo $this->monta_script_pesquisa("rhfuncao","rh37_funcao","rh37_descr",$this->ca1nome,$this->ca2nome,"rh37_descr",$this->formnam);
    }
/// fim
*/
    if($this->selecao == true){
        $this->monta_form_unicocampo($this->selnome,"r44_selec","r44_descr","selecao");
          echo $this->monta_script_pesquisa("selecao","r44_selec","r44_descr",$this->selnome,"campo4","r44_descr",$this->formnam);
    }

    if($this->selregime == true){
      $result_regimes = db_query("select rh52_regime, rh52_descr from rhcadregime");
      if(pg_num_rows($result_regimes) > 0){
          $arr_regimes[0] = "Todos";
        for($i=0; $i<pg_num_rows($result_regimes); $i++){
          $regime_for = pg_result($result_regimes, $i, "rh52_regime");
          $descrr_for = pg_result($result_regimes, $i, "rh52_descr");
          $arr_regimes[$regime_for] = $regime_for." - ".$descrr_for;
        }
        $this->monta_select_tdrtable($arr_regimes,$this->nomregime,"Regime:","Selecão de regime","");
      }
    }

    if($this->tipofol == true){
      $this->monta_select_tdrtable($this->arr_tipofol,$this->tfonome,"Tipo de Folha:","Tipo de Folha de Pagamento","onchange='js_testa_complementar(this.value,\"g\");'");
    }

    if($this->tipopon == true){
      $this->monta_select_tdrtable($this->arr_tipopon,$this->tponome,"Tipo de Ponto:","Tipo de Ponto","onchange='js_testa_complementar(this.value,\"p\");'");
    }

    if($this->tipofol == true || $this->tipopon == true){
      if(trim($this->complementar) != ""){
        if((isset($GLOBALS[$this->tfonome]) && $GLOBALS[$this->tfonome] == $this->complementar) || (isset($GLOBALS[$this->tponome]) && $GLOBALS[$this->tponome] == $this->complementar)){
          if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
            $result_complementar = db_query("select distinct rh141_codigo as semestralidade from rhfolhapagamento where rh141_tipofolha = 3 and rh141_anousu = ".$$campoano." and rh141_mesusu = ".$$campomes." order by semestralidade");
          } else {
            $result_complementar = db_query("select distinct r48_semest as semestralidade from gerfcom  where r48_anousu = ".$$campoano." and r48_mesusu = ".$$campomes);
          }
          
          if(pg_numrows($result_complementar) > 0){
            $arr_selcomplementar[0] = "Todos ...";
            for($icompl=0; $icompl<pg_numrows($result_complementar); $icompl++){
              db_fieldsmemory($result_complementar, $icompl);
              global $semestralidade;
                    $arr_selcomplementar[$semestralidade] = $semestralidade;
            }
                  $this->monta_select_tdrtable($arr_selcomplementar,$this->comnome,"Nro.Complementar:","Número da complementar");
          }else{
            db_input($this->comnome,'2',true,0,'hidden');
            echo "
                  <tr><td colspan=2 align='center'><font color='red'>Sem complementar para este período</font></td></tr>
                 ";
          }
        }
      }

      if(trim($this->suplementar) != ""){
        if((isset($GLOBALS[$this->tfonome]) && $GLOBALS[$this->tfonome] == $this->suplementar) || (isset($GLOBALS[$this->tponome]) && $GLOBALS[$this->tponome] == $this->suplementar)){

          $result_suplementar = db_query("select distinct rh141_codigo as semestralidade from rhfolhapagamento where rh141_tipofolha = 6 and rh141_anousu = ".$$campoano." and rh141_mesusu = ".$$campomes." order by semestralidade");
          if(pg_numrows($result_suplementar) > 0){
            $arr_selsuplementar[0] = "Todos ...";
            for($isupl=0; $isupl<pg_numrows($result_suplementar); $isupl++){
              db_fieldsmemory($result_suplementar, $isupl);
              global $semestralidade;
                    $arr_selsuplementar[$semestralidade] = $semestralidade;
            }
                  $this->monta_select_tdrtable($arr_selsuplementar,$this->comnome,"Nro.Suplementar:","Número da suplementar");
          }else{
            db_input($this->comnome,'2',true,0,'hidden');
            echo "
                  <tr><td colspan=2 align='center'><font color='red'>Sem suplementar para este período</font></td></tr>
                 ";
          }
        }
      }
    }
    if($this->tipores == true && (trim($this->strngtipores) != "" || count($this->arr_tipores) > 0)){
      if(trim($this->strngtipores) != ""){
        $numero_de_letras = strlen($this->strngtipores);
        for($i=0; $i<$numero_de_letras; $i++){
          $indice = $this->strngtipores[$i];
          if($indice == "g"){
            $resumo = "Geral";
          }else if($indice == "l"){
            $resumo = $this->lo4nome;
          }else if($indice == "m"){
            $resumo = $this->re4nome;
          }else if($indice == "o"){
            $resumo = $this->or4nome;
          }else if($indice == "s"){
            $resumo = $this->rc4nome;
          }else if($indice == "r"){
            $resumo = $this->ru4nome;
          }else if($indice == "t"){
            $resumo = $this->tr4nome;
          }else if($indice == "c"){
            $resumo = $this->ca4nome;
          }else{
            $resumo = "Outros";
          }
          $this->arr_tipores[$indice] = $resumo;
        }
      }else{
        if($this->onchpad == true){
          $this->onchres = "";
        }
      }
      if((trim($this->strngtipores) != "" || count($this->arr_tipores) > 0)){
        $this->monta_select_tdrtable($this->arr_tipores,$this->trenome,$this->tipresumo.":",$this->tipresumo,$this->onchres);
        $this->arr_tipofil = Array("0"=>"----------","i"=>"Intervalo","s"=>"Selecionados");
        $valortipres = $this->trenome;
        global $$valortipres;
        if(trim($this->strngtipores) != "" && $$valortipres != "g"){
          if((isset($GLOBALS[$this->trenome]) && $GLOBALS[$this->trenome] != $this->valortipores) || trim($this->resumopadrao) != "g"){
            $this->monta_select_tdrtable($this->arr_tipofil,$this->tfinome,"Tipo de Filtro:","Tipo de Filtro",$this->onchres);
          }
        }
      }
    }

    if($this->tipoarq == true) {
      $this->monta_select_tdrtable($this->arr_tipoarq, $this->tipoarqnome, 'Tipo de Arquivo', 'Tipo de Arquivo');
    }
    if($this->mostord == true){
  $this->monta_select_tdrtable($this->arr_mostord,$this->mornome,$this->tipordem.":",$this->tipordem);
    }

    if($this->mostasc == true){
      $arr_mostasc = Array("a"=>"Ascendente","d"=>"Descendente");
      $this->monta_select_tdrtable($arr_mostasc,$this->masnome,"Tipo de Ordem:","Tipo de Ordem");
    }

    if($this->mostaln == true){
      $arr_mostasc = Array("a"=>"Alfabética","d"=>"Numérica");
      $this->monta_select_tdrtable($arr_mostasc,$this->masnome,"Tipo de Ordem:","Tipo de Ordem");
    }

    if($this->mostnal == true){
      $arr_mostasc = Array("d"=>"Numérica","a"=>"Alfabética");
      $this->monta_select_tdrtable($arr_mostasc,$this->masnome,"Tipo de Ordem:","Tipo de Ordem");
    }

    if($this->mosttot == true){
      $this->monta_select_tdrtable($this->arr_mosttot,$this->mtonome,"Totalização:","Totalização");
    }

    if($this->qbrapag == true){
      $arr_qbrapag = Array("s"=>"Sim","n"=>"Não");
      $this->monta_select_tdrtable($arr_qbrapag,$this->qbrnome,"Quebrar por Página:","Quebrar por Página");
    }

    if($this->atinpen == true){
       $arr_atinpen = Array("g"=>"Geral","a"=>"Ativos","i"=>"Inativos","p"=>"Pensionistas","ip"=>"Inativos / Pensionistas");
      $this->monta_select_tdrtable($arr_atinpen,$this->aignome,"Vínculo:","Mostrar Inativos/Pensionistas, Ativos ou Geral");
    }
// monta a parte das tabelas de previdência
    if($this->usarprevid == true){
      $whereprevid = "";
      if(trim($this->whereprevid) != ""){
        $whereprevid = " where ".$this->whereprevid;
      }
      $result_previd = db_query("select distinct ".$this->camposprevid." from inssirf ".$whereprevid." order by r33_codtab");
      if(pg_num_rows($result_previd) > 0){
        echo "
              <tr>
                <td align='left' nowrap title='Tabelas de Previdência / INSS' >
                  <strong>Previdência / INSS:</strong>
                </td>
                <td align='left'>
             ";
        db_selectrecord($this->previdnome, $result_previd, true, 1);
        echo "
                </td>
              </tr>
             ";
      }
    }


// monta a parte do intervalo da opo escolhida
    $sFieldsetClass = '';
    if( $this->usaLotaFieldsetClass ){
      $sFieldsetClass = "class = 'form-container'";
    }
    if($this->usaregi == true){
      if($this->intregi == true){
        $this->monta_form_intervalos($this->re1nome,$this->re2nome,$this->re4nome,"rh01_regist","rhpessoal");
      }else if($this->uniregi == true){
        $this->monta_form_unicocampo($this->re1nome,"rh01_regist","z01_nome","rhpessoal");
      }
      echo $this->monta_script_pesquisa("rhpessoal","rh01_regist","z01_nome",$this->re1nome,$this->re2nome,"z01_nome",$this->formnam);
    }

    if($this->usaorga == true){
      if($this->intorga == true){
        $this->monta_form_intervalos($this->or1nome,$this->or2nome,$this->or4nome,"o40_orgao","orcorgao");
      }else if($this->uniorga == true){
        $this->monta_form_unicocampo($this->or1nome,"o40_orgao","o40_descr","orcorgao");
      }
      echo $this->monta_script_pesquisa("orcorgao","o40_orgao","o40_descr",$this->or1nome,$this->or2nome,"o40_descr",$this->formnam);
    }

    if($this->usarecu == true){
      if($this->intrecu == true){
        $this->monta_form_intervalos($this->rc1nome,$this->rc2nome,$this->rc4nome,"o15_codigo","orctiporec");
      }else if($this->unirecu == true){
        $this->monta_form_unicocampo($this->rc1nome,"o15_codigo","o15_descr","orctiporec");
      }
      echo $this->monta_script_pesquisa("orctiporec","o15_codigo","o15_descr",$this->rc1nome,$this->rc2nome,"o15_descr",$this->formnam);
    }

    if($this->usalota == true){
      if($this->intlota == true){
        $this->monta_form_intervalos($this->lo1nome,$this->lo2nome,$this->lo4nome,"r70_estrut","rhlota",15);
      }else if($this->unilota == true){
        $this->monta_form_unicocampo($this->lo1nome,"r70_estrut","r70_descr","rhlota",15);
      }
      echo $this->monta_script_pesquisa("rhlota","r70_estrut","r70_descr",$this->lo1nome,$this->lo2nome,"r70_descr",$this->formnam);
    }

    if($this->usarubr == true){
      if($this->intrubr == true){
        $this->monta_form_intervalos($this->ru1nome,$this->ru2nome,$this->ru4nome,"rh27_rubric","rhrubricas");
      }else if($this->unirubr == true){
        $this->monta_form_unicocampo($this->ru1nome,"rh27_rubric","rh27_descr","rhrubricas");
      }
      echo $this->monta_script_pesquisa("rhrubricas","rh27_rubric","rh27_descr",$this->ru1nome,$this->ru2nome,"rh27_descr",$this->formnam);
    }

    if($this->usaloca == true){
      if($this->intloca == true){
        $this->monta_form_intervalos($this->tr1nome,$this->tr2nome,$this->tr4nome,"rh55_estrut","rhlocaltrab");
      }else if($this->uniloca == true){
        $this->monta_form_unicocampo($this->tr1nome,"rh55_estrut","rh55_descr","rhlocaltrab");
      }
      echo $this->monta_script_pesquisa("rhlocaltrab","rh55_estrut","rh55_descr",$this->tr1nome,$this->tr2nome,"rh55_descr",$this->formnam);
    }

    if($this->usacarg == true){
      if($this->intcarg == true){
        $this->monta_form_intervalos($this->ca1nome,$this->ca2nome,$this->ca4nome,"rh37_funcao","rhfuncao");
      }else if($this->unicarg == true){
        $this->monta_form_unicocampo($this->ca1nome,"rh37_funcao","rh37_descr","rhfuncao");
      }
      echo $this->monta_script_pesquisa("rhfuncao","rh37_funcao","rh37_descr",$this->ca1nome,$this->ca2nome,"rh37_descr",$this->formnam);
    }

// monta a parte do seleção da opção escolhida

    if($this->selregi == true || $this->selrecu == true || $this->selorga == true || $this->sellota == true || $this->selrubr == true || $this->selloca == true || $this->selcarg == true){
      echo "
            <tr>
              <td align='center' nowrap colspan='2'>
                <table width='100%'>
           ";
      if($this->selregi == true){
        $this->clarquivo_auxiliar->Labelancora= $this->re4nome;
        $this->clarquivo_auxiliar->cabecalho  = "<strong>".$this->re4nome." Selecionadas</strong>";
        $this->clarquivo_auxiliar->codigo     = "rh01_regist";
        $this->clarquivo_auxiliar->descr      = "z01_nome";
        $this->clarquivo_auxiliar->nomeobjeto = $this->re3nome;
        $this->clarquivo_auxiliar->funcao_js  = 'js_geraform_mostrapes';
        $this->clarquivo_auxiliar->funcao_js_hide = 'js_geraform_mostrapes1';
        $this->clarquivo_auxiliar->func_arquivo = "func_rhpessoal.php";
        $this->clarquivo_auxiliar->nomeiframe = "db_iframe_rhpessoal";
        $this->clarquivo_auxiliar->executa_script_apos_incluir = "document.form1.rh01_regist.focus();";
        $this->clarquivo_auxiliar->executa_script_lost_focus_campo = "js_insSelect".$this->re3nome."();";
        $this->clarquivo_auxiliar->executa_script_change_focus = "document.form1.rh01_regist.focus();";
        $this->clarquivo_auxiliar->completar_com_zeros_codigo = false;
  $registrosselecionados = "";
        if(isset($GLOBALS[$this->campo_auxilio_regi]) && trim($GLOBALS[$this->campo_auxilio_regi]) != ""){
          $registrosselecionados = $GLOBALS[$this->campo_auxilio_regi];
    if($this->testarescisaoregi == true){
      $registrosselecionados = "";
            $virgula = "";
            $arr_registrosselecion = split(",",$GLOBALS[$this->campo_auxilio_regi]);
            for($i=0; $i<count($arr_registrosselecion); $i++){
                    $result_rescisoes = db_query("select rh05_recis from rhpessoalmov inner join rhpesrescisao on rh05_seqpes = rh02_seqpes where rh02_anousu = ".$ano." and rh02_mesusu = ".$mes." and rh02_regist = ".$arr_registrosselecion[$i]);
        if(pg_numrows($result_rescisoes) == 0){
                $registrosselecionados.= $virgula.$arr_registrosselecion[$i];
                $virgula = ", ";
        }else{
          db_msgbox("Funcionário ".$arr_registrosselecion[$i]." rescindido. Verifique.");
          break;
        }
                  }
    }

          $sql_regi = "select rh01_regist,z01_nome from rhpessoal inner join cgm on z01_numcgm = rh01_numcgm where rh01_regist in (".$registrosselecionados.")";
          if(!isset($chama_funcao) || (isset($chama_funcao) && trim($chama_funcao) == "")){
            $chama_funcao = "";
          }
          $chama_funcao.= $sql_regi;
          $this->clarquivo_auxiliar->sql_exec = $sql_regi;
        }

      }else if($this->selrecu == true){
        $this->clarquivo_auxiliar->Labelancora= $this->rc4nome;
        $this->clarquivo_auxiliar->cabecalho  = "<strong>".$this->rc4nome." Selecionados</strong>";
        $this->clarquivo_auxiliar->codigo     = "o15_codigo";
        $this->clarquivo_auxiliar->descr      = "o15_descr";
        $this->clarquivo_auxiliar->nomeobjeto = $this->rc3nome;
        $this->clarquivo_auxiliar->funcao_js  = 'js_geraform_mostrarec';
        $this->clarquivo_auxiliar->funcao_js_hide = 'js_geraform_mostrarec1';
        $this->clarquivo_auxiliar->func_arquivo = "func_orctiporec.php";
        $this->clarquivo_auxiliar->nomeiframe = "db_iframe_orctiporec";
        $this->clarquivo_auxiliar->executa_script_apos_incluir = "document.form1.o15_codigo.focus();";
        $this->clarquivo_auxiliar->executa_script_lost_focus_campo = "js_insSelect".$this->rc3nome."();";
        $this->clarquivo_auxiliar->executa_script_change_focus = "document.form1.o15_codigo.focus();";
        $this->clarquivo_auxiliar->completar_com_zeros_codigo = false;
        if(isset($GLOBALS[$this->campo_auxilio_recu]) && trim($GLOBALS[$this->campo_auxilio_recu]) != ""){
          $sql_recu = "select o15_codigo,o15_descr from orctiporec where o15_codigo in (".$GLOBALS[$this->campo_auxilio_recu].") ";
          $this->clarquivo_auxiliar->sql_exec = $sql_recu;
        }
      }else if($this->selorga == true){
        $this->clarquivo_auxiliar->Labelancora= $this->or4nome;
        $this->clarquivo_auxiliar->cabecalho  = "<strong>".$this->or4nome." Selecionados</strong>";
        $this->clarquivo_auxiliar->codigo     = "o40_orgao";
        $this->clarquivo_auxiliar->descr      = "o40_descr";
        $this->clarquivo_auxiliar->nomeobjeto = $this->or3nome;
        $this->clarquivo_auxiliar->funcao_js  = 'js_geraform_mostraorg';
        $this->clarquivo_auxiliar->funcao_js_hide = 'js_geraform_mostraorg1';
        $this->clarquivo_auxiliar->func_arquivo = "func_orcorgao.php";
        $this->clarquivo_auxiliar->nomeiframe = "db_iframe_orcorgao";
        $this->clarquivo_auxiliar->executa_script_apos_incluir = "document.form1.o40_orgao.focus();";
        $this->clarquivo_auxiliar->executa_script_lost_focus_campo = "js_insSelect".$this->or3nome."();";
        $this->clarquivo_auxiliar->executa_script_change_focus = "document.form1.o40_orgao.focus();";
        $this->clarquivo_auxiliar->completar_com_zeros_codigo = false;
        if(isset($GLOBALS[$this->campo_auxilio_orga]) && trim($GLOBALS[$this->campo_auxilio_orga]) != ""){
          $sql_orga = "select o40_orgao,o40_descr from orcorgao where o40_orgao in (".$GLOBALS[$this->campo_auxilio_orga].") and o40_anousu = ".db_getsession("DB_anousu");
          $this->clarquivo_auxiliar->sql_exec = $sql_orga;
        }
      }else if($this->sellota == true){
        $this->clarquivo_auxiliar->Labelancora= $this->lo4nome;
        $this->clarquivo_auxiliar->cabecalho  = "<strong>".$this->lo4nome." Selecionadas</strong>";
        $this->clarquivo_auxiliar->codigo     = "r70_estrut";
        $this->clarquivo_auxiliar->descr      = "r70_descr";
        $this->clarquivo_auxiliar->nomeobjeto = $this->lo3nome;
        $this->clarquivo_auxiliar->funcao_js  = 'js_geraform_mostralot';
        $this->clarquivo_auxiliar->funcao_js_hide = 'js_geraform_mostralot1';
        $this->clarquivo_auxiliar->func_arquivo = "func_rhlotaestrut.php";
        $this->clarquivo_auxiliar->nomeiframe = "db_iframe_rhlota";
        $this->clarquivo_auxiliar->executa_script_apos_incluir = "document.form1.r70_estrut.focus();";
        $this->clarquivo_auxiliar->executa_script_lost_focus_campo = "js_insSelect".$this->lo3nome."();";
        $this->clarquivo_auxiliar->executa_script_change_focus = "document.form1.r70_estrut.focus();";
        $this->clarquivo_auxiliar->completar_com_zeros_codigo = false;
        if(isset($GLOBALS[$this->campo_auxilio_lota]) && trim($GLOBALS[$this->campo_auxilio_lota]) != ""){
          $lotacoesselecionadas = "";
          $virgula = "";
          $arr_lotacoesselecion = split(",",$GLOBALS[$this->campo_auxilio_lota]);
          for($i=0; $i<count($arr_lotacoesselecion); $i++){
            $lotacoesselecionadas = $virgula."'".$arr_lotacoesselecion[$i]."'";
            $virgula = ", ";
          }
          $sql_lota = "select r70_estrut,r70_descr from rhlota where r70_estrut in (".$lotacoesselecionadas.")";
          $this->clarquivo_auxiliar->sql_exec = $sql_lota;
        }
      }else if($this->selrubr == true){
        $this->clarquivo_auxiliar->Labelancora= $this->ru4nome;
        $this->clarquivo_auxiliar->cabecalho = "<strong>".strtoupper($this->ru4nome)." Selecionadas</strong>";
        $this->clarquivo_auxiliar->codigo = "rh27_rubric";
        $this->clarquivo_auxiliar->descr  = "rh27_descr";
        $this->clarquivo_auxiliar->nomeobjeto = $this->ru3nome;
        $this->clarquivo_auxiliar->funcao_js = 'js_geraform_mostrarub';
        $this->clarquivo_auxiliar->funcao_js_hide = 'js_geraform_mostrarub1';
        $this->clarquivo_auxiliar->func_arquivo = "func_rhrubricas.php";
        $this->clarquivo_auxiliar->nomeiframe = "db_iframe_rhrubricas";
        $this->clarquivo_auxiliar->executa_script_apos_incluir = "document.form1.rh27_rubric.focus();";
        $this->clarquivo_auxiliar->executa_script_lost_focus_campo = "js_insSelect".$this->ru3nome."()";
        $this->clarquivo_auxiliar->executa_script_change_focus = "document.form1.rh27_rubric.focus();";
        $this->clarquivo_auxiliar->completar_com_zeros_codigo = true;
        if(isset($GLOBALS[$this->campo_auxilio_rubr]) && trim($GLOBALS[$this->campo_auxilio_rubr]) != ""){
          $rubricasselecionadas = "";
          $virgula = "";
          $arr_rubricasselecion = split(",",$GLOBALS[$this->campo_auxilio_rubr]);
          for($i=0; $i<count($arr_rubricasselecion); $i++){
            $rubricasselecionadas = $virgula."'".$arr_rubricasselecion[$i]."'";
            $virgula = ", ";
          }
          $sql_rubr = "select rh27_rubric,rh27_descr from rhrubricas where rh27_rubric in (".$rubricasselecionadas.")";
          $this->clarquivo_auxiliar->sql_exec = $sql_rubr;
        }
      }else if($this->selloca == true){
        $this->clarquivo_auxiliar->Labelancora= $this->tr4nome;
        $this->clarquivo_auxiliar->cabecalho  = "<strong>".strtoupper($this->tr4nome)." Selecionados</strong>";
        $this->clarquivo_auxiliar->codigo     = "rh55_estrut";
        $this->clarquivo_auxiliar->descr      = "rh55_descr";
        $this->clarquivo_auxiliar->nomeobjeto = $this->tr3nome;
        $this->clarquivo_auxiliar->funcao_js  = 'js_geraform_mostralocal';
        $this->clarquivo_auxiliar->funcao_js_hide = 'js_geraform_mostralocal1';
        $this->clarquivo_auxiliar->func_arquivo = "func_rhlocaltrabestrut.php";
        $this->clarquivo_auxiliar->nomeiframe = "db_iframe_rhlocaltrab";
        $this->clarquivo_auxiliar->executa_script_apos_incluir = "document.form1.rh55_estrut.focus();";
        $this->clarquivo_auxiliar->executa_script_lost_focus_campo = "js_insSelect".$this->tr3nome."();";
        $this->clarquivo_auxiliar->executa_script_change_focus = "document.form1.rh55_estrut.focus();";
        $this->clarquivo_auxiliar->completar_com_zeros_codigo = false;
        if(isset($GLOBALS[$this->campo_auxilio_loca]) && trim($GLOBALS[$this->campo_auxilio_loca]) != ""){
          $locaistrabselecionados = "";
          $virgula = "";
          $arr_locaistrabselecion = split(",",$GLOBALS[$this->campo_auxilio_loca]);
          for($i=0; $i<count($arr_locaistrabselecion); $i++){
            $locaistrabselecionados = $virgula."'".$arr_locaistrabselecion[$i]."'";
            $virgula = ", ";
          }
          $sql_loca = "select rh55_estrut,rh55_descr from rhlocaltrab where rh55_estrut in (".$locaistrabselecionados.")";
          $this->clarquivo_auxiliar->sql_exec = $sql_loca;
        }
      }else if($this->selcarg == true){
        $this->clarquivo_auxiliar->Labelancora= $this->ca4nome;
        $this->clarquivo_auxiliar->cabecalho  = "<strong>".strtoupper($this->ca4nome)." Selecionados</strong>";
        $this->clarquivo_auxiliar->codigo     = "rh37_funcao";
        $this->clarquivo_auxiliar->descr      = "rh37_descr";
        $this->clarquivo_auxiliar->nomeobjeto = $this->ca3nome;
        $this->clarquivo_auxiliar->funcao_js  = 'js_geraform_mostracarg';
        $this->clarquivo_auxiliar->funcao_js_hide = 'js_geraform_mostracarg1';
        $this->clarquivo_auxiliar->func_arquivo = "func_rhfuncao.php";
        $this->clarquivo_auxiliar->nomeiframe = "db_iframe_rhfuncao";
        $this->clarquivo_auxiliar->executa_script_apos_incluir = "document.form1.rh37_funcao.focus();";
        $this->clarquivo_auxiliar->executa_script_lost_focus_campo = "js_insSelect".$this->ca3nome."();";
        $this->clarquivo_auxiliar->executa_script_change_focus = "document.form1.rh37_funcao.focus();";
        $this->clarquivo_auxiliar->completar_com_zeros_codigo = false;
  $this->clarquivo_auxiliar->tamanho_campo_descricao = 42;
        if(isset($GLOBALS[$this->campo_auxilio_carg]) && trim($GLOBALS[$this->campo_auxilio_carg]) != ""){
          $locaistrabselecionados = "";
          $virgula = "";
          $arr_locaistrabselecion = split(",",$GLOBALS[$this->campo_auxilio_carg]);
          for($i=0; $i<count($arr_locaistrabselecion); $i++){
            $locaistrabselecionados = $virgula.$arr_locaistrabselecion[$i];
            $virgula = ", ";
          }
          $sql_func = "select rh37_funcao,rh37_descr from rhfunção where rh37_funcao in (".$locaistrabselecionados.")";
          $this->clarquivo_auxiliar->sql_exec = $sql_carg;
        }
      }

      $query_rescisao = "";
      if($this->selregi == true && trim($this->testarescisaoregi) != ""){
  $query_rescisao = "&testarescisao=".$this->testarescisaoregi;
      }
      $this->clarquivo_auxiliar->passar_query_string_para_func = "&instit=".db_getsession("DB_instit").$query_rescisao;
      $this->clarquivo_auxiliar->mostrar_botao_lancar = false;
      $this->clarquivo_auxiliar->db_opcao = 2;
      $this->clarquivo_auxiliar->tipo = 2;
      $this->clarquivo_auxiliar->top = 20;
      $this->clarquivo_auxiliar->linhas = $this->linhasSelecion;
      $this->clarquivo_auxiliar->vwidth = "400";
      $this->clarquivo_auxiliar->funcao_gera_formulario( $sFieldsetClass );
      echo "
                </table>
              </td>
            </tr>
           ";
    }

    if($this->mbgerar == true){
      echo "
            <tr>
              <td align='center' nowrap colspan='2'>
                <input type='button' name='gerar' id='gerar' onclick='".$this->jsgerar."' value='Processar dados'>
              </td>
            </tr>
           ";
    }

    db_input($this->campo_auxilio_regi,20,0,true,"hidden",3);
    db_input($this->campo_auxilio_orga,20,0,true,"hidden",3);
    db_input($this->campo_auxilio_recu,20,0,true,"hidden",3);
    db_input($this->campo_auxilio_lota,20,0,true,"hidden",3);
    db_input($this->campo_auxilio_rubr,20,0,true,"hidden",3);
    db_input($this->campo_auxilio_loca,20,0,true,"hidden",3);
    db_input($this->campo_auxilio_carg,20,0,true,"hidden",3);

    echo "
          <script>
   ";
    if($this->testarescisaoregi == true){
     echo "
           function js_executasubmit_".$this->re3nome."(){
             var variavel_dados = js_campo_recebe_valores();
       document.".$this->formnam.".".$this->campo_auxilio_regi.".value = variavel_dados;
       document.".$this->formnam.".submit();
     }
          ";
    }
    echo "
      function js_testa_complementar(valor, tipo){
   ";
    if(trim($this->complementar) != ""){
    echo "
        if(valor == '".$this->complementar."' || document.".$this->formnam.".".$this->comnome."){
    document.".$this->formnam.".submit();
              }
   ";
    }
    if(trim($this->suplementar) != ""){
    echo "
        if(valor == '".$this->suplementar."' || document.".$this->formnam.".".$this->comnome."){
    document.".$this->formnam.".submit();
              }
   ";
    }
    echo "
      }
            function js_geraform_trocaopcao(){
              ".$this->monta_script_buscdados($this->formnam,$this->re3nome,$this->campo_auxilio_regi)."
              ".$this->monta_script_buscdados($this->formnam,$this->or3nome,$this->campo_auxilio_orga)."
              ".$this->monta_script_buscdados($this->formnam,$this->rc3nome,$this->campo_auxilio_recu)."
              ".$this->monta_script_buscdados($this->formnam,$this->lo3nome,$this->campo_auxilio_lota)."
              ".$this->monta_script_buscdados($this->formnam,$this->ru3nome,$this->campo_auxilio_rubr)."
              ".$this->monta_script_buscdados($this->formnam,$this->tr3nome,$this->campo_auxilio_loca)."
              ".$this->monta_script_buscdados($this->formnam,$this->ca3nome,$this->campo_auxilio_carg)."
              document.".$this->formnam.".submit();
            }
            function js_gerar_consrel(){
              qry = '';
         ";
    if(trim($this->relarqu) != ""){
      echo "
              com = '?';
           ";
    }else{
      echo "
              com = '';
           ";
    }
    echo "
              ".$this->monta_script_gerarif($this->formnam,$this->anonome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->mesnome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->re1nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->re2nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->re3nome,$this->campo_auxilio_regi)."
              ".$this->monta_script_gerarif($this->formnam,$this->or1nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->or2nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->or3nome,$this->campo_auxilio_orga)."
              ".$this->monta_script_gerarif($this->formnam,$this->rc1nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->rc2nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->rc3nome,$this->campo_auxilio_recu)."
              ".$this->monta_script_gerarif($this->formnam,$this->lo1nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->lo2nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->lo3nome,$this->campo_auxilio_lota)."
              ".$this->monta_script_gerarif($this->formnam,$this->ru1nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->ru2nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->ru3nome,$this->campo_auxilio_rubr)."
              ".$this->monta_script_gerarif($this->formnam,$this->tr1nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->tr2nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->tr3nome,$this->campo_auxilio_loca)."
              ".$this->monta_script_gerarif($this->formnam,$this->ca1nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->ca2nome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->ca3nome,$this->campo_auxilio_carg)."
              ".$this->monta_script_gerarif($this->formnam,$this->tfonome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->tponome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->trenome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->mornome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->masnome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->mtonome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->qbrnome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->aignome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->selnome,"")."
              ".$this->monta_script_gerarif($this->formnam,$this->previdnome,"")."
         ";
    if(trim($this->relarqu) != ""){
      echo "jan = window.open('".$this->relarqu."'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
    }else{
      echo "
            if(!document.".$this->formnam.".valores_campos_rel){
              obj=document.createElement('input');
              obj.setAttribute('type','hidden');
              obj.setAttribute('name','valores_campos_rel');
              obj.setAttribute('id','valores_campos_rel');
              obj.setAttribute('value',qry);
              document.".$this->formnam.".appendChild(obj);
            }else{
              document.".$this->formnam.".valores_campos_rel.value = qry;
            }
           ";
    }
    echo $this->jsconsr;
    flush();
    echo "
            }

            if(document.".$this->formnam.".tipores){
              if(document.".$this->formnam.".tipores.value == '".$this->valortipores."'){
                if(document.".$this->formnam.".".$this->tfinome."){
                  document.".$this->formnam.".".$this->tfinome.".disabled = true;
                }
              }else{
                if(document.".$this->formnam.".".$this->tfinome."){
                  document.".$this->formnam.".".$this->tfinome.".disabled = false;
                }
              }
            }

          </script>
         ";
  }
}
?>
