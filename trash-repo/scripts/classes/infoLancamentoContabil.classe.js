infoLancamentoContabil = function(iLancamento, oParentWindow) {

  if ($('wndLancamentos'+iLancamento)) {
    return true;
  }
  var me       = this;
  var iWidth   = document.body.clientWidth/1.5;
  this.iLancamento = iLancamento;
  var iHeight  = (document.body.clientHeight/1.5); 
  this.oWindowLancamentos  = new windowAux('wndLancamentos'+iLancamento, 'Informação', iWidth, iHeight);
     
   sContent  = "<div class='infoLancamentoContabil' style='text-align:center;padding:2px;width:99%'>";
   sContent += "  <div style='width:100%' id='ctnDados"+iLancamento+"'>";
   sContent += "  <fieldset style='text-align:center;border:0px;border-top:2px groove white'>";
   sContent += "    <legend><b>Dados</b></legend>";
   sContent += "  <table>";
   sContent += "    <tr>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b>Código:</b>";
   sContent += "      </td>";  
   sContent += "      <td id='ctnCodigo"+iLancamento+"' style='width:40%;background:white'>";  
   sContent += "      </td>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b>Data:</b>";
   sContent += "      </td>";  
   sContent += "      <td id='ctnData"+iLancamento+"' style='width:40%;background:white'>";  
   sContent += "      </td>";
   sContent += "    </tr>";
   sContent += "    <tr>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b>Valor:</b>";
   sContent += "      </td>";  
   sContent += "      <td id='ctnValor"+iLancamento+"' style='width:40%;background:white'>";  
   sContent += "      </td>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b>Documento:</b>";
   sContent += "      </td>"; 
   sContent += "      <td id='ctnDocumento"+iLancamento+"' style='width:40%;background:white'>";  
   sContent += "      </td>";
   sContent += "    </tr>";
   sContent += "    <tr>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b>CGM:</b>";
   sContent += "      </td>";  
   sContent += "      <td id='ctnCgm"+iLancamento+"' style='width:40%;background:white'>";  
   sContent += "      </td>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b>Nome:</b>";
   sContent += "      </td>";  
   sContent += "      <td id='ctnNomeCgm"+iLancamento+"' style='width:40%;background:white'>";  
   sContent += "      </td>";
   sContent += "    </tr>";
   sContent += "    <tr>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b>Empenho:</b>";
   sContent += "      </td>";  
   sContent += "      <td id='ctnEmpenho"+iLancamento+"' style='width:40%;background:white'>";  
   sContent += "      </td>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b>Op:</b>";
   sContent += "      </td>";  
   sContent += "      <td id='ctnOP"+iLancamento+"' style='width:40%;background:white'>";  
   sContent += "      </td>";
   sContent += "    </tr>";
   sContent += "    <tr>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b>Nota:</b>";
   sContent += "      </td>";  
   sContent += "      <td id='ctnNota"+iLancamento+"' style='width:100px;background:white'>";  
   sContent += "      </td>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b><a id='ancoraDotacao"+iLancamento+"' href='#' onclick='return false';>Dotação:</a></b>";
   sContent += "      </td>";  
   sContent += "      <td id='ctnDotacao"+iLancamento+"' style='width:40%;background:white'>";  
   sContent += "      </td>";
   sContent += "    </tr>";
   
   sContent += "    <tr>";
   sContent += "      <td style='width:'10%;'>";
   sContent += "       <b><a id='ancoraReceita"+iLancamento+"' href='#' onclick='return false';>Receita:</a></b>";
   sContent += "      </td>";  
   sContent += "      <td id='ctnReceita"+iLancamento+"' style='width:100px;background:white'>";  
   sContent += "      </td>";
   sContent += "    </tr>";
   
   sContent += "    <tr>";
   sContent += "      <td  style='width:'10%;'>";
   sContent += "       <b>Complemento:</b>";
   sContent += "      </td>";  
   sContent += "      <td colspan='4' id='ctnComplemento"+iLancamento+"' style='width:90%;background:white'>";  
   sContent += "      </td>";
   sContent += "    </tr>";
   sContent += "  </table>";
   sContent += "  </fieldset>";
   sContent += "  </div>";
   sContent += "  <div style='width:95%'>";
   sContent += "  <fieldset style='text-align:center;border:0px;border-top:2px groove white'>";
   sContent += "    <legend><b>Lançamentos</b></legend>";
   sContent += "  <div style='width:100%' id='ctnDadosConlancam"+iLancamento+"'>";
   sContent += "  </div>";
   sContent += "  </div>";
   sContent += "  </fieldset>";
   sContent += "</div>";
   me.oWindowLancamentos.setContent(sContent);
   me.oMessage   = new DBMessageBoard('msgboard'+iLancamento, 
                                 'Informações Adicionais Lançamento Contábil - '+iLancamento,
                                 '',
                                 $("windowwndLancamentos"+iLancamento+"_content"));
   me.oMessage.show();
   me.oWindowLancamentos.setShutDownFunction(function (){
     
     me.oWindowLancamentos.destroy();
     delete me;
     
   });
   var iLeft =  ((screen.availWidth-iWidth)/2);
   var iTop  = ((50));
   var aJanelasInfo = $$('div.infoLancamentoContabil');
   if (aJanelasInfo.length > 0) {
   
     iLeft += (30*aJanelasInfo.length);
     iTop  += (50*aJanelasInfo.length);
   }
   
   /*
    *Monta a Grid;
    */
   me.oGridLancamentos = new DBGrid('gridLancamentos'+iLancamento);
   me.oGridLancamentos.nameInstance = 'me.oGridLancamentos';
   me.oGridLancamentos.setCellWidth(new Array('10%', '40%', '10%', '40%'));
   me.oGridLancamentos.setCellAlign(new Array("right", "left", "right", "left", "right"));
   me.oGridLancamentos.setHeader(new Array('Cta Débito', "Descr.",'Cta. Credito','Descr'));
   me.oGridLancamentos.show($('ctnDadosConlancam'+iLancamento));
   if (oParentWindow != null) {
     me.oWindowLancamentos.setChildOf(oParentWindow);
   }   
   me.oWindowLancamentos.show(iTop, iLeft);
   this.getInfoLancamento = function () {
     
     js_divCarregando("Aguarde, carregando informações do lançamento...", "msgBox");
    
     var oParam         = new Object();
     oParam.exec        = "getInfoLancamento";
     oParam.iLancamento = iLancamento; 
     var oAjax          = new Ajax.Request('con4_lancamentoscontabeisempenho.RPC.php',
                                {
                                 method: "post",
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: this.preencheDados
                                 });
    
  
   };
  
  this.preencheDados = function (oAjax) {
  
    js_removeObj("msgBox");
     var oRetorno = eval("("+oAjax.responseText+")");
     me.oGridLancamentos.clearAll(true);
     if (oRetorno.status == 1) {
       
       with(oRetorno.itens) {

         $('ctnCodigo'+me.iLancamento).innerHTML      = codigo;
         $('ctnData'+me.iLancamento).innerHTML        = js_formatar(data,'d');
         $('ctnValor'+me.iLancamento).innerHTML       = js_formatar(valor,'f');
         $('ctnDocumento'+me.iLancamento).innerHTML   = documento+" - "+descricaoevento.urlDecode();
         $('ctnCgm'+me.iLancamento).innerHTML         = cgm;
         $('ctnNomeCgm'+me.iLancamento).innerHTML     = nome.urlDecode();
         $('ctnEmpenho'+me.iLancamento).innerHTML     = empenho.urlDecode();
         $('ctnOP'+me.iLancamento).innerHTML          = ordempagamento.urlDecode();
         $('ctnNota'+me.iLancamento).innerHTML        = notafiscal.urlDecode();
         $('ctnDotacao'+me.iLancamento).innerHTML     = dotacao;
         $('ctnComplemento'+me.iLancamento).innerHTML = complemento.urlDecode();
         $('ctnReceita'+me.iLancamento).innerHTML     = receita;
         if (receita != '') {
           $('ancoraReceita'+me.iLancamento).onclick = function () {
             
             me.pesquisaReceita(receita, anolancamento);
             return false;
           };
         }
         
         if (dotacao != '') {
           $('ancoraDotacao'+me.iLancamento).onclick = function () {
             
             me.pesquisaDotacao(dotacao, anolancamento);
             return false;
           };
         }
         me.oMessage.setHelp("Tipo: "+documento+" - "+descricaoevento.urlDecode());
         for (var i =0; i < contas.length;i++){
        
           with (contas[i]) {
           
              var aLinha = new Array();
              aLinha[0]  = contadebito;
              aLinha[1]  = descricaodebito.urlDecode();
              aLinha[2]  = contacredito;
              aLinha[3]  = descricaocredito.urlDecode();
              me.oGridLancamentos.addRow(aLinha);
              me.oGridLancamentos.aRows[i].aCells[0].sStyle +='padding:1px';
              me.oGridLancamentos.aRows[i].aCells[1].sStyle +='padding:1px';
              me.oGridLancamentos.aRows[i].aCells[2].sStyle +='padding:1px';
              me.oGridLancamentos.aRows[i].aCells[3].sStyle +='padding:1px';
           }    
         }
         me.oGridLancamentos.renderRows();
       } 
     }
  };
  me.getInfoLancamento();
  
  me.pesquisaDotacao = function(iDotacao, iAnoUsu) {
    
    js_JanelaAutomatica('orcdotacao', iDotacao, iAnoUsu);
    $('Jandb_janelaDotacao').style.zIndex = '100000';
  };
  
  me.pesquisaReceita = function(iReceita, iAnoUsu) {
    
    js_JanelaAutomatica('orcreceita', iReceita, iAnoUsu);
    $('Jandb_janelaReceita').style.zIndex = '100000';
  };
};

