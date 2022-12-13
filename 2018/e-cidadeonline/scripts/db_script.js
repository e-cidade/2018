
    var engaged = false
    var offsetX = 0
    var offsetY = 0
    var Zindex = 5;
    
    function js_dragIt(obj,evt) {
      evt = (evt) ? evt : (window.event) ? window.event : "";
      if (engaged) {
        if (evt.pageX) {
          obj.style.left = evt.pageX - offsetX + "px";
          obj.style.top = evt.pageY - offsetY + "px";
        } else {
          obj.style.left = evt.clientX - offsetX + "px";
          obj.style.top = evt.clientY - offsetY + "px";
        }
        return false;
      }
    }
    function js_engage(obj,evt) {        
      evt = (evt) ? evt : (window.event) ? window.event : "SEM EVENTO";          

          if(JANS.isModal == 1)
            return false;
          SetCookie("modulo",obj.id);
        //  alert(GetCookie("modulo"));

      engaged = true;
      obj.style.zIndex = Zindex++;
          
            empilhaJanelas(obj.id.substr(3));          
            for(var i = 0;i < JANS.length - 1;i++) {
                  JANS[i].guardaCorFundoTitulo = JANS[i].corFundoTitulo;
          JANS[i].setCorFundoTitulo("#A6A6A6");
          }
          JANS[obj.id.substr(3)].setCorFundoTitulo("#2C7AFE");
 
      if (evt.pageX) {
        offsetX = evt.pageX - obj.offsetLeft;
        offsetY = evt.pageY - obj.offsetTop;                
      } else {
        offsetX = evt.offsetX - document.body.scrollLeft;
        offsetY = evt.offsetY - document.body.scrollTop;
        if (navigator.userAgent.indexOf("Win") == -1) {
          offsetX += document.body.scrollLeft;
          offsetY += document.body.scrollTop;
        }                
      }         
      return false;
    }
    function js_release(obj,evt) {
      evt = (evt) ? evt : (window.event) ? window.event : "";
      engaged = false;
    }
function empilhaJanelas(nomeJan) {
  for(var i = 0;i < JANS.length;i++)
    if(JANS[i].nomeJanela == nomeJan) {
          var indice = i;
          aux = JANS[i];
          break;
        }
  for(i = indice;i < JANS.length - 1;i++)
    JANS[i] = JANS[i+1];  
  JANS[i] = aux
  if(typeof(pos)!='undefined' && pos<20){
    pos = 20;
  };
}        
        function js_MaximizarJan(img,cod) {
          if(JANS.isModal == 1)
            return false;
          var str = new String(img.src);
          if(str.indexOf("on") == -1)
            return false;
          else {
            alert("Not implemented Yet");
//        var fr = cod.firstChild.firstChild.childNodes[1].firstChild.childNodes[0];
//                for( i in cod.firstChild.firstChild.childNodes[1].firstChild.childNodes[0])
//        document.getElementById('ff').innerHTML += i + ' <==> ' + cod.firstChild.firstChild.childNodes[1].firstChild.childNodes[0][i] +  '<br>';
          }
        }
        
        function js_MinimizarJan(img,JanElA) {          
          var janela = eval(JanElA);
          if(JANS.isModal == 1)
            return false;
          var str = new String(img.src);
          if(str.indexOf("on") == -1)
            return false;
          else {
            if(janela.nomeJanela == JANS[JANS.length-1].nomeJanela) {
              JANS[JANS.length-1].guardaCorFundoTitulo = JANS[JANS.length-1].corFundoTitulo;
          JANS[JANS.length-1].setCorFundoTitulo("#A6A6A6");
                  var aux = JANS[JANS.length-1];
                  for(var i = JANS.length-1;i > 0;i--) {
                    JANS[i] = JANS[i - 1];
                  }
                  JANS[0] = aux;
                  JANS[JANS.length-1].setCorFundoTitulo("#2C7AFE");  
                  /*
                  aux = "";
                  for(var i = 0;i < JANS.length;i++)
                    aux += " MI "+JANS[i].nomeJanela;
                        alert(aux);
                  */
                }
                str = new String(img.src);
                if(str.indexOf("_2_") == -1) {
          JanPosX = (typeof(JanPosX)=="undefined" || JanPosX=="")?1:JanPosX;
          JanPosY = (typeof(JanPosY)=="undefined" || JanPosY=="")?400:JanPosY;                
                    if(JanPosX >= 600) {
                    JanPosX = 1;
                    JanPosY = JanPosY - 27;
                    }
                  if(typeof(janela.px) == "undefined" && typeof(janela.py) == "undefined") {
                    janela.px = JanPosX;
                        janela.py = JanPosY;
                        janela.Wi = janela.moldura.style.width;
                        janela.Hi = janela.moldura.style.height;
                        janela.Pl = janela.moldura.style.left;
                        janela.Pt = janela.moldura.style.top;
            JanPosX += (janela.titulo.length * 5) + 52;
                  }
          janela.setAltura(1);
              janela.setLargura(1);                  
                    janela.setPosX(janela.px);
                  janela.setPosY(janela.py);
                  img.src = 'imagens/jan_mini_2_on.gif';
                } else {
                  img.src = 'imagens/jan_mini_on.gif';                
                  janela.setAltura(janela.Hi);
              janela.setLargura(janela.Wi);
                  janela.setPosX(janela.Pl);
                  janela.setPosY(janela.Pt);
                  janela.focus();
                  SetCookie("modulo",janela.nomeJanela);
                  /****/
                  var aux = JANS[janela.nomeJanela];
                  var j = 0;
                  for(var i = 0;i < JANS.length;i++) {
                    if(aux.nomeJanela != JANS[i].nomeJanela)
                      JANS[j++] = JANS[i];
                  }
              JANS[JANS.length-2].guardaCorFundoTitulo = JANS[JANS.length-2].corFundoTitulo;
          JANS[JANS.length-2].setCorFundoTitulo("#A6A6A6");
                  JANS[JANS.length-1] = aux;
                  JANS[JANS.length-1].setCorFundoTitulo("#2C7AFE");  
                  /*                                                      
                  aux = "";
                  for(var i = 0;i < JANS.length;i++)
                    aux += " MA "+JANS[i].nomeJanela;
                        alert(aux);
                  */
                }
          }
        }
        
        function js_FecharJan(img,JanElA) {
          var str = new String(img.src);
          if(str.indexOf("on") == -1)
            return false;
          else {
            var janela = eval(JanElA);
                if(JANS.isModal == 1) {
                                  /*aux = "";
                             for(var i = 0;i < JANS.length;i++)
                          aux += " MA "+JANS[i].nomeJanela;
                              alert(aux);*/
          if(JANS[JANS.length-1].nomeJanela != janela.nomeJanela)
            return false;
            }
        janela.hide();
                return true;
          }
        }
        
        function janela(janP,cFt,Iframe) {
          this.moldura = janP;
          this.jan = Iframe;
          this.janFrame = Iframe.frameElement;
          this.nomeJanela = janP.id.substr(3);
          this.titulo = new String(cFt.firstChild.innerHTML);
          document.cookie = "modulo=" + janP.id;
          netscape = navigator.appName == "Netscape"?1:0;

          if(typeof(JANS)=="undefined") {
            JANS = new Array();        
                JANS.isModal = 0;                  
          }        else {          
              for(var i = 0;i < JANS.length;i++) {
                  JANS[i].guardaCorFundoTitulo = JANS[i].corFundoTitulo;
          JANS[i].setCorFundoTitulo("#A6A6A6");
                }
          }          
          JANS[this.nomeJanela] = this;
          JANS.push(this);      

          function setTarget() {
            var args = setTarget.arguments;
        var F = (typeof(args[0])=="undefined" || args[0]=="")?"form1":args[0];
            document.forms[F].target = Iframe.name;
          }
          this.setTarget = setTarget;
          
      function setTitulo(t) {
        cFt.firstChild.innerHTML = '&nbsp;' + t;
                this.titulo = new String('&nbsp;' + t);
          }
          this.setTitulo = setTitulo;
          this.titulo = new String(cFt.firstChild.innerHTML);
          
          function setCorFundoTitulo(cor) {
            cFt.style.backgroundColor = cor;
                this.corFundoTitulo = cor;
          }
          this.setCorFundoTitulo = setCorFundoTitulo;
          this.corFundoTitulo = cFt.style.backgroundColor;

      function setCorTitulo(cor) {
        cFt.firstChild.style.color = cor;
                this.corTitulo = cor;
          }
          this.setCorTitulo = setCorTitulo;
          this.corTitulo = cFt.firstChild.style.color;
          
          function setFonteTitulo(f) {
        cFt.firstChild.style.fontFamily = f;
                this.fonteTitulo = f;
          }
          this.setFonteTitulo = setFonteTitulo;
          this.fonteTitulo = cFt.firstChild.style.fontFamily;

      function setTamTitulo(t) {
        cFt.firstChild.style.fontSize = t;
                this.tamTitulo = t;
          }
          this.setTamTitulo = setTamTitulo;
          this.tamTitulo = cFt.firstChild.style.fontSize;
          
          function setPosX(pos) {
            janP.style.left = pos;
              this.posX = pos;
          }
          this.setPosX = setPosX;
          this.posX = janP.style.left;
          function setPosY(pos) {
            if(typeof(pos)!='undefined' && pos<20){
              pos = 20;
            }
            janP.style.top = pos;
                this.posY = pos;
          }
          this.setPosY = setPosY;
          this.posY = janP.style.top;
          function setLargura(l) {
            janP.style.width = l;
        //Iframe.frameElement.style.width = l;                           
                this.largura = l;
          }
          this.setLargura = setLargura;          
          this.largura = janP.style.width;
          function setAltura(a) {
            janP.style.height = a;         
        //Iframe.frameElement.style.height = a;
                this.altura = a;
          }
          this.setAltura = setAltura;
          this.altura = janP.style.height;
          function focus() {
            janP.style.zIndex = Zindex++;
          }
          this.focus = focus;
          function show() {
            empilhaJanelas(this.nomeJanela);
                // estava assim
                //JANS[JANS.length-2].setCorFundoTitulo("#A6A6A6");
                if(JANS.length > 1)
          JANS[JANS.length-2].setCorFundoTitulo("#A6A6A6");
            janP.style.visibility = 'visible';
          }
          this.show = show;          
          function hide() {
            var aux = JANS[JANS.length-1];
            for(var i = JANS.length-1;i > 0;i--)
          JANS[i] = JANS[i - 1];                
            JANS[0] = aux;
        JANS[JANS.length-1].setCorFundoTitulo("#2C7AFE");                                            
            janP.style.visibility = 'hidden';                        
          }
          this.hide = hide;                    
          /******/
          function procuraNo(obj,nome) {
            nome = nome.toUpperCase();
                //alert(obj.childNodes.length);
        if(obj.childNodes.length > 0) {
          for(var i = 0;i < obj.childNodes.length;i++) {
            if(obj.childNodes[i].nodeName == nome) {
              //alert(obj.childNodes[i].nodeName + ' ' + i + ' = ' + nome);
              try {        
                    ObjRet = obj.childNodes[i];
              } catch(e) {
              }
            return true;
            }                        
            procuraNo(obj.childNodes[i],nome);
          }
        }
          }
          /******/
          function limpaTela() {            
        procuraNo(Iframe.document,"body");
            corpo = ObjRet;//vem da funcao procuraNo
        var documento = corpo.childNodes;                
        do {
          for(var i = 0;i < documento.length;i++) {
            corpo.removeChild(documento[i]);
          }
        } while(documento.length != 0);
          }
          this.limpaTela = limpaTela;
          
          function mostraMsg() {
        var args = mostraMsg.arguments;
        var msg = (typeof(args[0])=="undefined" || args[0]=="")?"Processando...":args[0];
                var cor = (typeof(args[1])=="undefined" || args[1]=="")?"white":args[1];
                var Larg = (typeof(args[2])=="undefined" || args[2]=="")?this.moldura.style.width:args[2];                
                var Alt = (typeof(args[3])=="undefined" || args[3]=="")?this.moldura.style.height:args[3];                
                var PosX = (typeof(args[4])=="undefined" || args[4]=="")?"0":args[4];                
                var PosY = (typeof(args[5])=="undefined" || args[5]=="")?"0":args[5];
                
              if(elem = document.getElementById("mensagem") )
              elem.parentNode.removeChild(elem);
            var camada = Iframe.document.createElement("DIV");
            camada.setAttribute("id","mensagem");
                                    
                procuraNo(Iframe.document,"body");
            try {        
              ObjRet.appendChild(camada);
          var elem = Iframe.document.getElementById("mensagem");
                elem.innerHTML = "<table border='0' cellpadding='0' cellspacing='0'><tr><td width='" + Larg + "' height='" + Alt + "' align='center' valign='middle'><strong>" + msg + "</strong></td></tr></table>";
              elem.style.backgroundColor = cor;
              elem.style.layerBackgroundColor = cor;
              elem.style.position = "absolute";
              elem.style.left = "0px";
              elem.style.top = "0px";
              elem.style.zIndex = "100";                
              elem.style.visibility = 'visible';
              elem.style.width = Larg;
              elem.style.height = Alt;
        } catch(e) {
        }
          }
          this.mostraMsg = mostraMsg;
          function setJanBotoes(str) {
            var s = new String(str);           
            var img1 = cFt.childNodes[1].childNodes[0];
            var img2 = cFt.childNodes[1].childNodes[1];
            var img3 = cFt.childNodes[1].childNodes[2];                  

            kp = 0x4;
            m = kp & s;
            kp >>= 1;
            img1.src = m?"imagens/jan_mini_on.gif":"imagens/jan_mini_off.gif";
            img1.style.cursor = m?"hand":"";
            m = kp & s;
            kp >>= 1;
            img2.src = m?"imagens/jan_max_on.gif":"imagens/jan_max_off.gif";
            img2.style.cursor = m?"hand":"";                
            m = kp & s;
            kp >>= 1;
            img3.src = m?"imagens/jan_fechar_on.gif":"imagens/jan_fechar_off.gif";
            img3.style.cursor = m?"hand":"";                
          }
          this.setJanBotoes = setJanBotoes;
            function setModal() {
            JANS.isModal = 1;
          /*
        cFt.firstChild.onmousedown = null;
        cFt.firstChild.onmouseup = null;
        cFt.firstChild.onmousemove = null;
        cFt.firstChild.onmouseout = null;
                */
          }
          this.setModal = setModal;
          function setNoModal() {
            JANS.isModal = 0;
          /*
        cFt.firstChild.onmousedown = function (event) { js_engage(janP, event); };
        cFt.firstChild.onmouseup = function (event) { js_release(janP, event); };
        cFt.firstChild.onmousemove = function (event) { js_dragIt(janP, event); };
        cFt.firstChild.onmouseout = function (event) { js_release(janP, event); };
                */
          }
          this.setNoModal = setNoModal;
        }
function criaJanela(nomeJan,arquivo,cabecalho,visivel,topo,esquerda,altura,largura) {

  var camada = document.createElement("DIV");
  var tabela1 = document.createElement("TABLE");
  var tabela2 = document.createElement("TABLE");
  var quadro = document.createElement("IFRAME");
  var img1 = document.createElement("IMG");
  var img2 = document.createElement("IMG");
  var img3 = document.createElement("IMG");    

  img3.setAttribute("src","imagens/jan_fechar_on.gif");
  img3.setAttribute("title","Fechar");
  img3.setAttribute("border","0");
  img3.style.cursor = "hand";
  img3.onclick = function() { js_FecharJan(this,nomeJan); };

  img2.setAttribute("src","imagens/jan_max_off.gif");
  img2.setAttribute("title","Maximizar");
  img2.setAttribute("border","0");
  img2.style.cursor = "hand";
  img2.onclick = function() { js_MinimizarJan(this,nomeJan); };

  img1.setAttribute("src","imagens/jan_mini_on.gif");
  img1.setAttribute("title","Minimizar");
  img1.setAttribute("border","0");
  img1.style.cursor = "hand";
  img1.onclick = function() { js_MinimizarJan(this,nomeJan); };
  
  camada.setAttribute("id","Jan" + nomeJan);
  tabela1.setAttribute("cellSpacing",0);
  tabela1.setAttribute("cellPadding",2);
  tabela1.setAttribute("border",0);
  tabela1.setAttribute("width","100%");
  tabela1.setAttribute("height","100%");
  
  tabela1.style.borderColor = "#f0f0f0 #606060 #404040 #d0d0d0";
  tabela1.style.borderStyle = "solid";
  tabela1.style.borderWidth = "2px";
  
  tabela2.setAttribute("cellSpacing",0);
  tabela2.setAttribute("cellPadding",0);
  tabela2.setAttribute("border",0);
  tabela2.setAttribute("width","100%");

  quadro.setAttribute("frameBorder","1");
  quadro.setAttribute("height","100%");
  quadro.setAttribute("width","100%");
  quadro.setAttribute("id","IF" + nomeJan);
  quadro.setAttribute("name","IF" + nomeJan);
  quadro.setAttribute("scrolling","auto");
  //quadro.setAttribute("src",arquivo);
  
  var tab1Linha1 = tabela1.insertRow(0);
  var tab1Linha2 = tabela1.insertRow(1);
  var tab2Linha1 = tabela2.insertRow(0);
  
  var tab1Coluna1 = tab1Linha1.insertCell(0);
  var tab1Coluna2 = tab1Linha2.insertCell(0);
  var tab2Coluna1 = tab2Linha1.insertCell(0);
  var tab2Coluna2 = tab2Linha1.insertCell(1);
 
  tab2Linha1.setAttribute("id","CF" + nomeJan);
  tab2Linha1.style.backgroundColor = '#2C7AFE';
  tab1Linha1.style.backgroundColor = '#c0c0c0';
  tab2Coluna1.style.whiteSpace = "nowrap";
  tab2Coluna1.onmousedown = function(event) { js_engage(document.getElementById('Jan' + nomeJan),event); };
  tab2Coluna1.onmouseup = function(event) { js_release(document.getElementById('Jan' + nomeJan),event);};
  tab2Coluna1.onmousemove = function(event) { js_dragIt(document.getElementById('Jan' + nomeJan),event);};
  tab2Coluna1.onmouseout = function(event) { js_release(document.getElementById('Jan' + nomeJan),event);};
  tab2Coluna1.setAttribute("width","80%");
  tab2Coluna1.style.cursor = 'hand';
  tab2Coluna1.style.fontWeight = 'bold';
  tab2Coluna1.style.color = 'white';
  tab2Coluna1.style.fontFamily = 'Arial, Helvetica, sans-serif';
  tab2Coluna1.style.fontSize = '11px';
  tab2Coluna1.innerHTML =  (typeof(cabecalho)=="undefined" || cabecalho=="")?'&nbsp; DBSeller Informática Ltda':('&nbsp;' + cabecalho);
//  tab2Coluna1.innerHTML =  (typeof(cabecalho)=="undefined" || cabecalho=="")?'&nbsp;' + nomeJan:('&nbsp;' + cabecalho);
  tab2Coluna1.setAttribute("nowrap","1");
  tab2Coluna2.setAttribute("nowrap","1");
  tab2Coluna2.setAttribute("width","20%");
  tab2Coluna2.setAttribute("align","right");
  tab2Coluna2.setAttribute("valign","middle");
  
  tab1Coluna2.setAttribute("width","100%");
  tab1Coluna2.setAttribute("height","100%");  
  camada.style.backgroundColor = "#c0c0c0";
  camada.style.layerBackgroundColor = "#c0c0c0";
  camada.style.border = "0px outset #666666";
  camada.style.position = "absolute";
  camada.style.left = esquerda;
  camada.style.top = topo;
  camada.style.zIndex = "1";
  camada.style.visibility = 'hidden';
  camada.style.width = altura;
  camada.style.height = largura;
  tab2Coluna2.appendChild(img1);
  tab2Coluna2.appendChild(img2);
  tab2Coluna2.appendChild(img3);        
  tab1Coluna1.appendChild(tabela2);
  tab1Coluna2.appendChild(quadro);
  camada.appendChild(tabela1);
  document.body.appendChild(camada);
  
  
  eval(nomeJan + " = new janela(document.getElementById('Jan" + nomeJan + "'),document.getElementById('CF" + nomeJan + "'),IF" + nomeJan + ")");
  document.getElementById('IF' + nomeJan).src = arquivo;

  eval(nomeJan + ".focus()");
  return eval(nomeJan);
}
function js_OpenJanelaIframe(aondeJanela,nomeJanela,arquivoJanela,tituloJanela,mostraJanela,topoJanela,leftJanela,widthJanela,heigthJanela){
//#01#//js_OpenJanelaIframe
//#10#//Funcão para gerar uma janela de iframe automática
//#15#//js_OpenJanelaIframe(aondeJanela,nomeJanela,arquivoJanela,tituloJanela,mostraJanela,topoJanela,leftJanela,widthJanela,heigthJanela);
//#20#//aondeJanela   : Objeto (local) onde será gerada a janela, normalmente "top.corpo" 
//#20#//nomeJanela    : Nome do Objeto gerado, objeto que será utilizado para manipulação da janela e dados da janela 
//#20#//arquivoJanela : Nome do arquivo com os parâmetros necessários para apresentar no iframe
//#20#//tituloJanela  : Título que será mostrado na janela
//#20#//mostraJanela  : True se janela será apresentada ou false se não for mostrada
//#20#//topoJanela    : Valor da posição em px do topo da janela no formulário que está sendo criada
//#20#//leftJanela    : Valor da posição em px do lado esquerdo da janela iframe
//#20#//widthJanela   : Valor da largura da janela a ser apresentada
//#20#//heigthJanela  : Valor da altura da janela a ser apresentada
//#99#//Os parâmetros obrigatórios são até titulo da janela, ficando os demais com os seguintes valores:
//#99#//mostraJanela = true - se mostra
//#99#//topoJanela   = 20   - posição em relação ao topo do formulário
//#99#//leftJanela   = 1    - posição em relação ao lado esquerdo do formulário
//#99#//widthJanela  = 780  - Largura da janela
//#99#//heigthJanela = 430  - Altera da janela
//#99#//Exemplo:  
//#99#//js_OpenJanelaIframe('top.corpo','db_janelaCgm','prot3_conscgm002.php?fechar=top.corpo.db_janelaCgm&numcgm='+qchave,'Dados Cadastrais');
//#99#//
//#99#//Para manipular dados de retorno de uma janela, deverá ser criada função para receber os dados no formulário onde
//#99#//a janela será criada e criado uma variável junto com o parâmetro arquivoJanela indicando qual a função a ser 
//#99#//executada, colocando os devidos parâmetros que forem necessários
//#99#//
//#99#//No formulário onde a janela vai ser criada:
//#99#// <script>
//#99#// js_OpenJanelaIframe('top.corpo','db_janelaCgm','[programa].php?js_funcao=parent.js_MINHA_FUNCAO&fechar=top.corpo.db_janelaCgm&numcgm='+qchave,'Dados Cadastrais');
//#99#// function js_MINHA_FUNCAO (codigo) { // Note que foi passado para o programa uma variável js_funcao que será executada dentro do iframe 
//#99#//   alert(codigo);
//#99#// }
//#99#// </script>
//#99#//
//#99#//No programa que será executado dentro do iframe:
//#99#// <script>
//#99#// <? // tag php
//#99#// echo $js_funcao."('1')";
//#99#// ?>
//#99#// </script>
//#99#//
//#99#//O resultado deste programa deverá ser um alert na tela com o número 1
//#99#//
//#99#//Funções de manipulação de uma janela iframe:
//#99#// [nome da janela].hide();     - Esconde a janela no formulário
//#99#// [nome da janela].show();     - Mostra a janela no formulário e da foco para ela
//#99#// [nome da janela].mostraMsg() - Mostra a mensagem de processando no centro da janela iframe
//#99#// [nome da janela].focus()     - Passa o foco para esta janela
//#99#// [nome da janela].jan.location.href = 'pagina de programa' - Executa a página dentro do iframe
//#99#// [nome da janela].setTitulo('descricao do titulo') - Troca o título da janela
//#99#// [nome da janela].setAltura('valor') - Altera da janela
//#99#// [nome da janela].setLargura('valor') - Largura da janela

if(mostraJanela==undefined)
    mostraJanela = true;
  if(topoJanela==undefined)
    topoJanela = '20';
  if(leftJanela==undefined)
    leftJanela = '1';
  if(widthJanela==undefined)
   widthJanela = '780';
  if(heigthJanela==undefined)
    heigthJanela = '430';
 
  if(eval((aondeJanela!=""?aondeJanela+".":"document.")+nomeJanela)){
 
    var executa = (aondeJanela!=""?aondeJanela+".":"")+nomeJanela+".jan.location.href = '"+arquivoJanela+"'";
    executa = eval(executa);
 
  }else{
      
    var executa = (aondeJanela!=""?aondeJanela+".":"")+"criaJanela('"+nomeJanela+"','"+arquivoJanela+"','"+tituloJanela+"',"+mostraJanela+","+topoJanela+","+leftJanela+","+widthJanela+","+heigthJanela+")";


    executa = eval(executa);
  
  }
  if(mostraJanela==true){
    
    var executa = (aondeJanela!=""?aondeJanela+".":"")+nomeJanela+".mostraMsg();";
    executa += (aondeJanela!=""?aondeJanela+".":"")+nomeJanela+".show();";
    executa += (aondeJanela!=""?aondeJanela+".":"")+nomeJanela+".focus();";
    
    executa = eval(executa);
  
  }

}

function db_mes(xmes) {
if ( xmes == '1' ) {
        Mes = 'janeiro';
   }
   if ( xmes == '2') {
        Mes = 'fevereiro';
   }
   if ( xmes == '3') {
        Mes = 'março';
   }
   if ( xmes == '4') {
        Mes = 'abril';
   }
   if ( xmes == '5') {
        Mes = 'maio';
   }
   if ( xmes == '6') {
        Mes = 'junho';
   } 
   if ( xmes == '7') {
        Mes = 'julho';
   } 
   if ( xmes == '8') {
        Mes = 'agosto';
   } 
   if ( xmes == '9') {
        Mes = 'setembro';
   } 
   if ( xmes == '10') {
        Mes = 'outubro';
   } 
   if ( xmes == '11') {
        Mes = 'novembro';
   } 
   if ( xmes == '12') {
        Mes = 'dezembro';
  }
  return Mes;
}

function js_controla_tecla_enter(obj,evt){
//#01#//js_controla_tecla_enter
//#10#//Funcao para controlar quando a tecla enter é precionada
//#15#//js_controla_tecla_enter(obj,evt);
//#20#//obj : Objeto que esta com a função
//#20#//evt : Este parâmetro não deverá ser passado, pois é automático do javascript
//#30#//Retorna false quando a tecla presionada é igual a 13
  
  var evt = (evt) ? evt : (window.event) ? window.event : "";

  if(evt.keyCode==13){

    return false;

  } 


}

function js_ValidaMaiusculo(obj,maiusculo,evt) {
//#01#//js_ValidaMaiusculo
//#10#//Funcao validar se maiusculo ou não
//#15#//js_ValidaMaiusculo(obj,maiusculo,evt);
//#20#//obj       : Objeto que será testado
//#20#//maiusculo : Se maiusculo ou não (t = verdadeiro e f = falso )
//#99#//Esta funlção coloca a letra digitado para maiúsculo e é executada no onkeypres e no onblur dos objetos
  evt = (evt)?evt:(event)?event:'';
  if(evt.keyCode < 37 || evt.keyCode > 40){
    if(maiusculo =='t'){
      var maiusc = new String(obj.value);
      obj.value = maiusc.toUpperCase();
    }
  }
}
////////////////////////////////////
function js_ValidaCampos(obj,tipo,nome,aceitanulo,maiusculo,evt) {
//#01#//js_ValidaCampos
//#10#//Funcao para validar o conteúdo do campo quando digitado no formulário
//#15#//js_ValidaCampos(obj,tipo,nome,aceitanulo,maiusculo,evt);
//#20#//objeto      : Nome do objeto do formulário
//#20#//tipo        : Cõdigo do tipo de consistencia do objeto gerado
//#20#//              0 - Não consistencia o campo
//#20#//              1 - Números  = RegExp("[^0-9]+")
//#20#//              2 - Letras   = RegExp("[^A-Za-zà-úÁ-ÚüÜ %]+")
//#20#//              3 - Números, Letras, espao e vírgula = RegExp("[^A-Za-z0-9à-úÁ-ÚüÜ \.,;:@&%-\_]+")
//#20#//              4 - Números do tipo flutuante (valores monetário ou com casas decimais) = RegExp("[^0-9\.]+")
//#20#//              5 - Campo deve ser somente falso ou verdadeiro = RegExp("fmFM")
//#20#//Nome        : Descrição do campo para mensagem de erro
//#20#//Aceitanuulo : Se aceita o campo nulo ou não true = aceita false = não aceita
//#20#//Maiusculo   : Se campo deve ser maiusculo, quando digita a sistema troca para maiusculo
//#20#//evt         : este parâmetro não deve ser passado para a função, pois é automático do javascript
  evt = (evt)?evt:(event)?event:'';
  if(maiusculo =='t'){
    var maiusc = new String(obj.value);
        obj.value = maiusc.toUpperCase();
  }
  /*
  if(obj.value ==''){
    if(aceitanulo!='t'){
      alert(nome+' deverá ser preenchido');
      obj.select();        
      obj.focus();        
    }
  }
  */
  if(tipo == 1) {
    var expr = new RegExp("[^0-9]+");
    if(obj.value.match(expr)) {
       if(obj.value!= ''){
          alert(nome+" deve ser preenchido somente com números!");
          obj.select();        
          obj.focus();        
        }
     }
  } else if(tipo == 2) {
    var expr = new RegExp("[^A-Za-zà-úÁ-ÚüÜ %]+");
    if(obj.value.match(expr)) {
          alert(nome+" deve ser preenchido somente com Letras!");
          obj.select();        
          obj.focus();        
        }  
  } else if(tipo == 3) {
    var expr = new RegExp("[^A-Za-z0-9à-úÁ-ÚüÜ \.,;:@&%-\_]+");
        if(obj.value.match(expr)) {
          alert(nome+" deve ser preenchido somente com Letras, números, espaço, virgula, ponto-e-virgula, hífen,2 pontos,arroba,sublinhado!");
          obj.select();        
          obj.focus();        
        }  
  } else  if(tipo == 4) {
    var expr = new RegExp("[^0-9\.]+");
    if(obj.value.match(expr)) {
          alert(nome+" deve ser preenchido somente com números decimais!");
          obj.select();        
          obj.focus();        
        }
  } else  if(tipo == 5) {
    var expr = new RegExp("fmFM");
    if(obj.value.match(expr)) {
          alert(nome+" deve ser preenchido somente com falso ou verdadeiro!");
          obj.select();        
          obj.focus();        
        }
  }
  
}

function SetCookie (name,value,expires,path,domain,secure) {
  document.cookie = name + "=" + escape (value) +
    ((expires) ? "; expires=" + expires.toGMTString() : "") +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    ((secure) ? "; secure" : "");
}
function DeleteCookie (name,path,domain) {
  if (GetCookie(name)) {
    document.cookie = name + "=" +
      ((path) ? "; path=" + path : "") +
      ((domain) ? "; domain=" + domain : "") +
      "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }
}

function js_restaurafundo(obj,color) {
  document.getElementById(obj.id).style.backgroundColor = ''+color+'';
}
function js_trocafundo(obj,color){
  document.getElementById(obj.id).style.backgroundColor = ''+color+'';
}
function js_link(arq) {
  location.href = arq;
}
//verifica se o elemento existe no array
function js_in_array(elem,vetor) {
  for(var i = 0;i < vetor.length;i++) {
    if(vetor[i] == elem)
          return true;
  }
  return false;
}

//tipo o parse int, só que pega o numero se tiver na final da straing tb!!
function js_parse_int(str) {
  var num = new Array("0","1","2","3","4","5","6","7","8","9");
  var tam = str.length;
  var aux = "";
  for(var i = 0;i < tam;i++) {
    if(js_in_array(str.substr(i,1),num))
          aux += str.substr(i,1);
  }
  return aux;
}

//document.oncontextmenu = new Function("return false");
function js_maiuscula(dbobjeto){
  var vstring = new String(dbobjeto.value);
  dbobjeto.value = vstring.toUpperCase();
}

function js_minuscula(dbobjeto){
  var vstring = new String(dbobjeto.value);
  dbobjeto.value = vstring.toLowerCase();
}

terminar = "";

function js_voltacor(valor) {

  window.clearInterval(terminar);

  document.form1[valor].style.backgroundColor = 'white';

  document.form1[valor].style.borderStyle = 'inset';

}

f = 0;

function js_trocacor(valor) {

    if(f == 0) {

      document.form1[valor].style.backgroundColor = 'black';

          document.form1[valor].borderStyle = 'none';

          f = 1;

    } else {

          document.form1[valor].style.backgroundColor = 'green';

          document.form1[valor].style.borderStyle = 'solid';

          f = 0;

  }

}



function js_verificapagina(pagina){
  var pag = pagina.split(",");
  var existe = 0;
  var loc = new String(document.location);
  loc = loc.substring(0,loc.lastIndexOf("/")+1);
  var ref = new String(document.referrer);
//  alert(document.referrer);
  for(i = 0;i < pag.length;i++) {

    if( ref.indexOf(loc+pag[i]) == 0 ) {
          existe = 1;
        }
  }
  if(existe == 0) {
  //  alert("Você esta acessando a página de uma URL inválida e será redirecionado.");
  //  top.location.href = "index.php";
  }
}



function js_emiteboleto(alias,pagredirect) {
  var x = "";
  for ( i = 0; i < document.form1.totalregistros.value;i++ ){
    if (document.form1.elements[i].checked == true ) {
      if ( x != "" ){
        x = x + "+" ;
          } 
          x = x + document.form1.elements[i].value ;
    }
  }
  if(x == "")
    alert("Você deverá Selecionar os valores a emitir");
  else
    window.open("emiteboleto.php?alias="+alias+"&numpres="+ x,"","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+(screen.height-100)+",width="+(screen.width-100));
        document.href = pagredirect;
}

function js_selecionavar(alias,cod_inscr) {
  var x = "";
  var vx = "";
  if (document.form1.somahistorico.value != "" ) {
    for ( i = 0;i < document.form1.totalregistros.value;i ++ ){
      if ( document.form1.elements[i].value != "" ) {
            if ( x != "" ) {    
                  x = x + "+" ;
                  vx = vx + "+";
                }
                x = x + document.form1.elements[i].name ;
        vx = vx + document.form1.elements[i].value ;
      }
        }
  }
  if( x == "")
    alert("Você deverá Selecionar Digitar os valores a Pagar");
  else {
    location.href = "pagaissvarsel.php?inscricao="+cod_inscr+"&alias="+alias+"&issvar="+ x + "&issvarvlr=" + vx;
  }
}

function js_emiteboletovar(alias) {
  var x = "";
  var xx = "";
  for ( i = 0; i < document.form1.totalregistros.value;i++ ){
    if (document.form1.elements[i].checked == true ) {
      if ( x != "" ){
        x = x + "+" ;
        xx = xx + "+" ;
          } 
          x = x + document.form1.elements[i].name ;
          xx = xx + document.form1.elements[i].value ;
    }
  }
  if(x == "")
    alert("Você deverá Selecionar os valores a emitir");
  else
    window.open("emiteboleto.php?alias="+alias+"&issvar="+ x + "&issvarvlr=" + xx,"","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+(screen.height-100)+",width="+(screen.width-100));
}

function js_emiteboletovarold(alias) {

  var x = "";

  var vx = "";

  if (document.form1.somahistorico.value != "" ) {

    for ( i = 0;i < document.form1.totalregistros.value;i ++ ){

      if ( document.form1.elements[i].value != "" ) {

            if ( x != "" ) {    

                  x = x + "+" ;

                  vx = vx + "+";

                }

                x = x + document.form1.elements[i].name ;

        vx = vx + document.form1.elements[i].value ;

      }

        }

  }

  

//  alert(x);

//  alert(vx);

  

  if( x == "")

    alert("Você deverá Selecionar Digitar os valores a Pagar");

  else {

    window.open("emiteboleto.php?alias="+alias+"&issvar="+ x + "&issvarvlr=" + vx,"","toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,height="+(screen.height-100)+",width="+(screen.width-100));

  }

}
function js_seltodos(sn) {

  for ( i = 0; i < document.form1.totalregistros.value;i++ ){

    if ( document.form1.elements[i].checked != sn ){

           document.form1.elements[i].click();

        }

  }

}





<!-- Funcoes para acesso da Layer de processando

<!--

function MM_reloadPage(init) {  //reloads the window if Nav4 resized

  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {

    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}

  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();

}

MM_reloadPage(true);





function MM_findObj(n, d) { //v4.0

  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {

    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}

  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];

  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);

  if(!x && document.getElementById) x=document.getElementById(n); return x;

}



function MM_showHideLayers() { //v3.0

  var i,p,v,obj,args=MM_showHideLayers.arguments;

  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];

    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
        
    obj.visibility=v;
        }

}
function MM_showHideLayersValor() { //v3.0

  var i,p,v,obj,args=MM_showHideLayersValor.arguments;

  for (i=0; i<(args.length-2); i+=3) if ((obj=MM_findObj(args[i]))!=null) { v=args[i+2];

    if (obj.style) { obj=obj.style; v=(v=='show')?'visible':(v='hide')?'hidden':v; }
        
    obj.left=document.body.scrollLeft+event.clientX;
        obj.top=document.body.scrollTop+event.clientY-80;

    obj.visibility=v;
        }

}
/////////////////////////////////////////////////////////////////
function js_teclas(event){
   
  var sMask = '';
  
  var obj   = event.srcElement ? event.srcElement : event.currentTarget;
  var t     = document.all ? event.keyCode : event.which;
  if (t == 44) {
    if ( obj.value.indexOf(".") == -1) {
     obj.value += ".";
    }
  }
  if (obj != null) {
    
    if (obj.value.indexOf(".") != -1 && t == 46) {
      return false;
    }
  }
  sMask = "0-9|.";
  return js_mask(event, sMask);
}

/////////////////////////////////////////////////////////////////
function js_mask(e,teclas) { 

  var ini  = '';
  var fim  = '';
  var aval = '';
  var or   = '';
  var and  = ''
  var t    = document.all ? event.keyCode : e.which;
  var ta   = teclas.split("|");
   for (var i = 0;i < ta.length;i++){
        
        if (ta[i].indexOf("-") != "-1" && ta[i].length == 1) {
         
          and = i > 0?' ||  ':'';
          aval += and+' t == '+ta[i].charCodeAt();
          and = ' ||';
        
        } else  if (ta[i].indexOf("-") != "-1"){
 
           vchars = ta[i].split("-");
            or = i > 0?'|| ':'';

           if (vchars.length > 1){

              ini = vchars[0].charCodeAt();
              fim = vchars[1].charCodeAt();

              aval += or+' (t >='+ini+' && t <='+fim+')';
              or = " ||";

           }else{
              aval += ' && t ='+vchars[0]
           }

        }else{
         
          if (ta[i].indexOf("\-")) {
           ta[i] = ta[i].replace("\\","");
          }
          and = i > 0?' ||  ':'';
          aval += and+' t == '+ta[i].charCodeAt();
          and = ' ||';

        }

    }

   if (eval(aval)){
       return true;
   }else{
       if (t != 8 && t != 0 && t != 13 && t != 32){ // backspace
          return false;
     }else{
          return true;
     }
  }
}
/////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////
function FormataDATA(Campo, teclapres){
        var tecla = teclapres.keyCode;
        
        var vr = new String(Campo.value);
        vr = vr.replace("/", "");
        vr = vr.replace("/", "");
        vr = vr.replace("/", "");

        tam = vr.length + 1;
        
        if (tecla != 9 && tecla != 8){
                if (tam > 2 && tam < 5)
                        Campo.value = vr.substr(0, 2) + '/' + 
                                      vr.substr(2, tam);
                if (tam >= 5 && tam <7)
                        Campo.value = vr.substr(0,2) + '/' + 
                                      vr.substr(2,2) + '/' + 
                                      vr.substr(4,tam-4);
                if (tam >= 9 && tam < 12)
                        Campo.value = vr.substr(0,2) + '/' + 
                                      vr.substr(2,2) + '/' +
                                      vr.substr(9,tam-9);
                }
}
/////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////
function FormataCPF(Campo, teclapres){
        var tecla = teclapres.keyCode;
        
        var vr = new String(Campo.value);
        vr = vr.replace(".", "");
        vr = vr.replace(".", "");
        vr = vr.replace("-", "");

        tam = vr.length + 1;
        
        if (tecla != 9 && tecla != 8){
                if (tam > 3 && tam < 7)
                        Campo.value = vr.substr(0, 3) + '.' + 
                                      vr.substr(3, tam);
                if (tam >= 7 && tam <10)
                        Campo.value = vr.substr(0,3) + '.' + 
                                      vr.substr(3,3) + '.' + 
                                      vr.substr(6,tam-6);
                if (tam >= 10 && tam < 12)
                        Campo.value = vr.substr(0,3) + '.' + 
                                      vr.substr(3,3) + '.' + 
                                      vr.substr(6,3) + '-' + 
                                      vr.substr(9,tam-9);
                }
}
/////////////////////////////////////////////////////////////////
function FormataCNPJ(Campo, teclapres){

        var tecla = teclapres.keyCode;

        var vr = new String(Campo.value);
        vr = vr.replace(".", "");
        vr = vr.replace(".", "");
        vr = vr.replace("/", "");
        vr = vr.replace("-", "");

        tam = vr.length + 1 ;

        
        if (tecla != 9 && tecla != 8){
                if (tam > 2 && tam < 6)
                        Campo.value = vr.substr(0, 2) + '.' + 
                                      vr.substr(2, tam);
                if (tam >= 6 && tam < 9)
                        Campo.value = vr.substr(0,2) + '.' + 
                                      vr.substr(2,3) + '.' + 
                                      vr.substr(5,tam-5);
                if (tam >= 9 && tam < 13)
                        Campo.value = vr.substr(0,2) + '.' + 
                                      vr.substr(2,3) + '.' + 
                                      vr.substr(5,3) + '/' + 
                                      vr.substr(8,tam-8);
                if (tam >= 13 && tam < 15)
                        Campo.value = vr.substr(0,2) + '.' + 
                                      vr.substr(2,3) + '.' + 
                                      vr.substr(5,3) + '/' + 
                                      vr.substr(8,4) + '-' + 
                                      vr.substr(12,tam-12);
                }
}
/////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////
function FormataCPFeCNPJ(Campo,teclapres){
                var tecla = teclapres.keyCode;
                var tam = Campo.value.length;  
       
                var vrc = new String(Campo.value);
                    vrc = vrc.replace(".", "");
                    vrc = vrc.replace(".", "");
                    vrc = vrc.replace("/", "");
                    vrc = vrc.replace("-", "");
                             
                var tamString = vrc.length + 1; 
               
                if (!isNaN(vrc)) {
                  if (tamString == 11 ){        
                   var vr = new String(Campo.value);
                       vr = vr.replace(".", "");
                       vr = vr.replace(".", "");
                       vr = vr.replace("-", "");
       
                   tam = vr.length + 1;
        
                if (tecla != 9 && tecla != 8){
                   if (tam > 3 && tam < 7)
                       Campo.value = vr.substr(0, 3) + '.' + 
                                     vr.substr(3, tam);
                   if (tam >= 7 && tam <10)
                       Campo.value = vr.substr(0,3) + '.' + 
                                     vr.substr(3,3) + '.' + 
                                     vr.substr(6,tam-6);
                   if (tam >= 10 && tam < 12)
                       Campo.value = vr.substr(0,3) + '.' + 
                                     vr.substr(3,3) + '.' + 
                                     vr.substr(6,3) + '-' + 
                                     vr.substr(9,tam-9);
                   } 
                                     
                  } if (tamString > 11){               
                      var vr = new String(Campo.value);
                          vr = vr.replace(".", "");
                          vr = vr.replace(".", "");
                          vr = vr.replace("/", "");
                          vr = vr.replace("-", "");

                      tam = vr.length + 1 ;
                      if (tecla != 9 && tecla != 8){
                          Campo.value = vr.substr(0,2) + '.' + 
                                        vr.substr(2,3) + '.' + 
                                        vr.substr(5,3) + '/' + 
                                        vr.substr(8,4)+ '-' + 
                                        vr.substr(12,tam-12);
                      }              
                   
                  }                   
               }              
}
///////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
function VerAlfaNumerico(pInd){
        var pValor = document.forms[0].elements[pInd].value
        var AuxTam = pValor.length  
        for(var j=0;j<AuxTam;j++)
                if ((!IndAlfaNumerico(pValor.charAt(j))) || (pValor.charAt(j) == " ")){
                        document.forms[0].elements[pInd].focus();  
                        document.forms[0].elements[pInd].value = pValor = pValor.substring(0,j)           
                        } 
        }
////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////
function IndAlfaNumerico(N){
        for(var i=0;i<10;i++)
        if(N == i)
                return true;
        return false;    
        }
//////////////////////////////////////////////////////////////////
function CalcularDV(sCampo, iPeso){
        
        var iTamCampo;
        var iPosicao, iDigito;
        var iSoma1 = 0;
        var iSoma2=0;
        var iDV1, iDV2;
                
        iTamCampo = sCampo.length;

        for (iPosicao=1; iPosicao<=iTamCampo; iPosicao++){
                iDigito = sCampo.substr(iPosicao-1, 1);
                iSoma1 = parseInt(iSoma1,10) + parseInt((iDigito * Calcular_Peso(iTamCampo - iPosicao, iPeso)),10);
                iSoma2 = parseInt(iSoma2,10) + parseInt((iDigito * Calcular_Peso(iTamCampo - iPosicao + 1, iPeso)),10);
                }

        iDV1 = 11 - (iSoma1 % 11);
        if (iDV1 > 9)
                iDV1 = 0;

        iSoma2 = iSoma2 + (iDV1 * 2);
        iDV2 = 11 - (iSoma2 % 11);
        if (iDV2 > 9)
                iDV2 = 0;

        Ret = (parseInt(iDV1 * 10,10) + parseInt(iDV2));

        Ret = "0" + Ret;
        Ret = Ret.substr(Ret.length - 2,Ret.length);
                
        return(Ret);
}

//////////////////////////////////////////////////////////////////                
function Calcular_Peso(iPosicao, iPeso){

        //Pesos
        //CPF 11
        //CNPJ 9
        return (iPosicao % (iPeso - 1)) + 2;
        }
        
/////////////////////////////////////////////////////////////////
function LimpaCampo(sValor,iBase){
        var tam = sValor.length;
        var saida = new String;
        for (i=0;i<tam;i++)
                if (!isNaN(parseInt(sValor.substr(i,1),iBase)))
                        saida = saida + String(sValor.substr(i,1));
        return (saida);                
        }
////////////////////////////////////////////////////////////////////
function TestaNI(cNI,iTipo){
        var NI 
        NI = LimpaCampo(cNI.value,10);
        switch (iTipo) {
                case 1:
                        if (NI.length != 14){
                                alert('O número do CNPJ informado está incorreto');
                                cNI.select();
                                cNI.focus();
                                return(false);
                                }

                        if (NI.substr(12,2) != CalcularDV(NI.substr(0,12), 9)){
                                alert('O número do CNPJ informado está incorreto');
                                cNI.select();
                                cNI.focus();
                                return(false);
                                }
                        break;

                case 2:
                        if (NI.length != 11){
                                alert('O número do CPF informado está incorreto');
                                cNI.select();
                                cNI.focus();
                                return(false);
                                }

                        if (NI.substr(9,2) != CalcularDV(NI.substr(0,9), 11)){
                                alert('O número do CPF informado está incorreto');
                                cNI.select();
                                cNI.focus();
                                return(false);
                                }
                        break;

                default:
                        return(false);
                }
        return (true);        
        }  
/////////////////////////////////////////////////////////////////
function js_verificaCGCCPF(obcgc,obcpf){
  if (obcgc != ""){
      return TestaNI(obcgc,1);
  }
  if (obcpf != ""){
     return TestaNI(obcpf,2);
  }
  return true;
}

function CalculaDV(sCampo, iPeso)

{

        

        var iTamCampo;

        var iPosicao, iDigito;

        var iSoma1 = 0;

        var iSoma2=0;

        var iDV1, iDV2;

                

        iTamCampo = sCampo.length;

                

        for (iPosicao=1; iPosicao<=iTamCampo; iPosicao++){

                iDigito = sCampo.substr(iPosicao-1, 1);

                iSoma1 = parseInt(iSoma1,10) + parseInt((iDigito * Calcular_Peso(iTamCampo - iPosicao, iPeso)),10);

                iSoma2 = parseInt(iSoma2,10) + parseInt((iDigito * Calcular_Peso(iTamCampo - iPosicao + 1, iPeso)),10);

                }



        iDV1 = 11 - (iSoma1 % 11);

        if (iDV1 > 9)

                iDV1 = 0;



        iSoma2 = iSoma2 + (iDV1 * 2);

        iDV2 = 11 - (iSoma2 % 11);

        if (iDV2 > 9)

                iDV2 = 0;



        Ret = (parseInt(iDV1 * 10,10) + parseInt(iDV2));



        Ret = "0" + Ret;

        Ret = Ret.substr(Ret.length - 2,Ret.length);

                

        return(iDV1);

        

}



//////////////////////////////////////////////////////////////////                



function Calcular_Peso(iPosicao, iPeso)

{



//Pesos

//CPF 11

//CNPJ 9



return (iPosicao % (iPeso - 1)) + 2;

}
/////////////////////////////////////////////////////////////////
function FormataValor(Campo, teclapres){
        var tecla = teclapres.keyCode;
         var vr = new String(Campo.value);
        vr = vr.replace(".", "");
        vr = vr.replace(".", "");
        vr = vr.replace(".", "");
        vr = vr.replace(".", "");
        vr = vr.replace(",", "");
        tam = vr.length - 1  ;
  if (tecla != 9 && tecla != 8){
    if ( tecla >= 48 && tecla <= 57 ){
        var pree = "";
        for (contador = 0; contador < vr.length; contador ++){
                   if ( contador == 2 ){
                          pree = pree + vr.substr(contador-3,3) + "." + vr.substr(contador+1,2) ;
                   }
                   if ( contador == 5 ){
                          pree = pree + vr.substr(contador,1) + "." + vr.substr(contador+1,2) ;
                   }
                   if ( contador == 8 ){
                          pree = pree + vr.substr(contador,1) + "," + vr.substr(contador+1,2) ;
                   }
                }
                if (  pree != "" ){
                   Campo.value = pree;
                }
        }
  }
}
/////////////////////////////////////////////////////////////////
function js_validaAlfaNumerico(obvalida){
        var pValor = new String(obvalida.value)
        var AuxTam = pValor.length  
        pValor = pValor.replace('.','');
        for(var j=0;j<AuxTam;j++){
                if ((!IndAlfaNumerico(pValor.charAt(j))) || (pValor.charAt(j) == " ")){
            alert("Voce deverá digitar o valor separando os centavos com PONTO");
                        obvalida.value = "";          
                        obvalida.focus();  
                } 
        }
}
// ********************************************************
// funcoes do help
// ********************************************************
NoMe = new String(location.href);
NoMe = NoMe.split("/");
NoMe = NoMe[NoMe.length - 1];
NoMe = NoMe.substr(0,NoMe.search(".php"));
NoMe = NoMe + '_help';
var ob;
function js_moverDiv() {
  if(ob) {
    ob.pixelLeft = event.clientX - AntesX + document.body.scrollLeft - 2;
    ob.pixelTop = event.clientY - AntesY + document.body.scrollTop - 2;
    if(document.form1)
          if(document.form1.x_div) {
            document.form1.x_div.value = parseInt(document.getElementById(NoMe).style.left);
          document.form1.y_div.value = parseInt(document.getElementById(NoMe).style.top);        
    }          
    return false;
  }
}

function js_MD_Div() {
//  ob=event.srcElement.parentNode.style;
  ob = document.getElementById(NoMe).style;
  AntesY=event.offsetY;
  AntesX=event.offsetX;
}
function js_MU_Div() {
  ob = null;
}
