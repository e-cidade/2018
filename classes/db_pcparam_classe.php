<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

//MODULO: compras
//CLASSE DA ENTIDADE pcparam
class cl_pcparam { 
   // cria variaveis de erro 
   var $rotulo     = null; 
   var $query_sql  = null; 
   var $numrows    = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status= null; 
   var $erro_sql   = null; 
   var $erro_banco = null;  
   var $erro_msg   = null;  
   var $erro_campo = null;  
   var $pagina_retorno = null; 
   // cria variaveis do arquivo 
   var $pc30_instit = 0; 
   var $pc30_horas = null; 
   var $pc30_dias = 0; 
   var $pc30_tipcom = 0; 
   var $pc30_unid = 0; 
   var $pc30_obrigajust = 'f'; 
   var $pc30_obrigamat = 'f'; 
   var $pc30_gerareserva = 'f'; 
   var $pc30_liberaitem = 'f'; 
   var $pc30_liberado = 'f'; 
   var $pc30_seltipo = 'f'; 
   var $pc30_sugforn = 'f'; 
   var $pc30_mincar = null; 
   var $pc30_permsemdotac = 'f'; 
   var $pc30_passadepart = 'f'; 
   var $pc30_digval = 'f'; 
   var $pc30_libdotac = 'f'; 
   var $pc30_tipoemiss = 'f'; 
   var $pc30_comsaldo = 'f'; 
   var $pc30_contrandsol = 'f'; 
   var $pc30_tipoprocsol = 0; 
   var $pc30_itenslibaut = 'f'; 
   var $pc30_comobs = 'f'; 
   var $pc30_ultdotac = 'f'; 
   var $pc30_fornecdeb = 0; 
   var $pc30_emiteemail = 'f'; 
   var $pc30_modeloorc = 0; 
   var $pc30_modeloordemcompra = 0; 
   var $pc30_modeloorcsol = 0; 
   var $pc30_dotacaopordepartamento = 'f'; 
   var $pc30_valoraproximadoautomatico = 'f'; 
   var $pc30_basesolicitacao = 0; 
   var $pc30_baseprocessocompras = 0; 
   var $pc30_baseempenhos = 0; 
   var $pc30_maximodiasorcamento = 0; 
   var $pc30_validadepadraocertificado = 0; 
   var $pc30_tipovalidade = 0; 
   var $pc30_importaresumoemp = 'f'; 
   var $pc30_diasdebitosvencidos = 0; 
   var $pc30_notificaemail = 'f'; 
   var $pc30_notificacarta = 'f'; 
   var $pc30_permitirgerarnotifdebitos = 'f'; 
   var $pc30_consultarelatoriodepartamento = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc30_instit = int4 = Codigo da instituicao 
                 pc30_horas = varchar(5) = Hora padrão 
                 pc30_dias = int8 = Dias de prazo padrão para orçamento 
                 pc30_tipcom = int4 = Código do tipo de compra 
                 pc30_unid = int8 = Código da unidade padrão 
                 pc30_obrigajust = bool = Obrigar justificativa do item 
                 pc30_obrigamat = bool = Obrigar informação do material 
                 pc30_gerareserva = bool = Gera reserva de dotações automatico 
                 pc30_liberaitem = bool = Libera sem item cadastrado 
                 pc30_liberado = bool = Liberação total no processo de compras 
                 pc30_seltipo = bool = Selecionar tipo de solicitação 
                 pc30_sugforn = bool = Sugerir fornecedores 
                 pc30_mincar = varchar(6) = Mínimo de caracteres 
                 pc30_permsemdotac = bool = Permitir cadastro de itens sem dotação 
                 pc30_passadepart = bool = Passa departamento ao escolher dotação 
                 pc30_digval = bool = Permitir digitar valor aproximado 
                 pc30_libdotac = bool = Liberar item sem dotacao 
                 pc30_tipoemiss = bool = Emissão da solicitação 
                 pc30_comsaldo = bool = Imprimir com saldo reservado 
                 pc30_contrandsol = bool = Controla Andamento da Solicitação 
                 pc30_tipoprocsol = int4 = Tipo de Processo para item da solicitação 
                 pc30_itenslibaut = bool = Todos os itens liberados p/ autorização 
                 pc30_comobs = bool = Imprimir observação do cert. reg. cadastral 
                 pc30_ultdotac = bool = Trazer últ. dotação cadastr. na solic. 
                 pc30_fornecdeb = int4 = Utilização de fornecedor em débito 
                 pc30_emiteemail = bool = Emite Email 
                 pc30_modeloorc = int4 = Modelo do Orçamento do Processo de Compras 
                 pc30_modeloordemcompra = int4 = Modelo Ordem de Compra 
                 pc30_modeloorcsol = int4 = Modelo Orçamento solicitação 
                 pc30_dotacaopordepartamento = bool = Trazer Dotações Ligadas ao Departamento 
                 pc30_valoraproximadoautomatico = bool = Traz Valor Automático 
                 pc30_basesolicitacao = int4 = Quantidade de Solicitações 
                 pc30_baseprocessocompras = int4 = Quantidade de Processo de Compras 
                 pc30_baseempenhos = int4 = Quantidade de Empenhos 
                 pc30_maximodiasorcamento = int4 = Quantidade de Dias 
                 pc30_validadepadraocertificado = int4 = Validade Padrão para Certificados 
                 pc30_tipovalidade = int4 = Tipo Validade 
                 pc30_importaresumoemp = bool = Importa resumo do empenho para ordem de compra 
                 pc30_diasdebitosvencidos = int4 = Quantidade  de Dias Débito Vencido 
                 pc30_notificaemail = bool = Envia Email na Notificação 
                 pc30_notificacarta = bool = Envia Carta na Notificação 
                 pc30_permitirgerarnotifdebitos = bool = Permitir Gerar Notificação de Débitos 
                 pc30_consultarelatoriodepartamento = int4 = Consulta por 
                 ";
   //funcao construtor da classe 
   function cl_pcparam() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcparam"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]);
   }
   //funcao erro 
   function erro($mostra,$retorna) { 
     if(($this->erro_status == "0") || ($mostra == true && $this->erro_status != null )){
        echo "<script>alert(\"".$this->erro_msg."\");</script>";
        if($retorna==true){
           echo "<script>location.href='".$this->pagina_retorno."'</script>";
        }
     }
   }
   // funcao para atualizar campos
   function atualizacampos($exclusao=false) {
     if($exclusao==false){
       $this->pc30_instit = ($this->pc30_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_instit"]:$this->pc30_instit);
       $this->pc30_horas = ($this->pc30_horas == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_horas"]:$this->pc30_horas);
       $this->pc30_dias = ($this->pc30_dias == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_dias"]:$this->pc30_dias);
       $this->pc30_tipcom = ($this->pc30_tipcom == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_tipcom"]:$this->pc30_tipcom);
       $this->pc30_unid = ($this->pc30_unid == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_unid"]:$this->pc30_unid);
       $this->pc30_obrigajust = ($this->pc30_obrigajust == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_obrigajust"]:$this->pc30_obrigajust);
       $this->pc30_obrigamat = ($this->pc30_obrigamat == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_obrigamat"]:$this->pc30_obrigamat);
       $this->pc30_gerareserva = ($this->pc30_gerareserva == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_gerareserva"]:$this->pc30_gerareserva);
       $this->pc30_liberaitem = ($this->pc30_liberaitem == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_liberaitem"]:$this->pc30_liberaitem);
       $this->pc30_liberado = ($this->pc30_liberado == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_liberado"]:$this->pc30_liberado);
       $this->pc30_seltipo = ($this->pc30_seltipo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_seltipo"]:$this->pc30_seltipo);
       $this->pc30_sugforn = ($this->pc30_sugforn == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_sugforn"]:$this->pc30_sugforn);
       $this->pc30_mincar = ($this->pc30_mincar == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_mincar"]:$this->pc30_mincar);
       $this->pc30_permsemdotac = ($this->pc30_permsemdotac == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_permsemdotac"]:$this->pc30_permsemdotac);
       $this->pc30_passadepart = ($this->pc30_passadepart == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_passadepart"]:$this->pc30_passadepart);
       $this->pc30_digval = ($this->pc30_digval == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_digval"]:$this->pc30_digval);
       $this->pc30_libdotac = ($this->pc30_libdotac == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_libdotac"]:$this->pc30_libdotac);
       $this->pc30_tipoemiss = ($this->pc30_tipoemiss == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_tipoemiss"]:$this->pc30_tipoemiss);
       $this->pc30_comsaldo = ($this->pc30_comsaldo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_comsaldo"]:$this->pc30_comsaldo);
       $this->pc30_contrandsol = ($this->pc30_contrandsol == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_contrandsol"]:$this->pc30_contrandsol);
       $this->pc30_tipoprocsol = ($this->pc30_tipoprocsol == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_tipoprocsol"]:$this->pc30_tipoprocsol);
       $this->pc30_itenslibaut = ($this->pc30_itenslibaut == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_itenslibaut"]:$this->pc30_itenslibaut);
       $this->pc30_comobs = ($this->pc30_comobs == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_comobs"]:$this->pc30_comobs);
       $this->pc30_ultdotac = ($this->pc30_ultdotac == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_ultdotac"]:$this->pc30_ultdotac);
       $this->pc30_fornecdeb = ($this->pc30_fornecdeb == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_fornecdeb"]:$this->pc30_fornecdeb);
       $this->pc30_emiteemail = ($this->pc30_emiteemail == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_emiteemail"]:$this->pc30_emiteemail);
       $this->pc30_modeloorc = ($this->pc30_modeloorc == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_modeloorc"]:$this->pc30_modeloorc);
       $this->pc30_modeloordemcompra = ($this->pc30_modeloordemcompra == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_modeloordemcompra"]:$this->pc30_modeloordemcompra);
       $this->pc30_modeloorcsol = ($this->pc30_modeloorcsol == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_modeloorcsol"]:$this->pc30_modeloorcsol);
       $this->pc30_dotacaopordepartamento = ($this->pc30_dotacaopordepartamento == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_dotacaopordepartamento"]:$this->pc30_dotacaopordepartamento);
       $this->pc30_valoraproximadoautomatico = ($this->pc30_valoraproximadoautomatico == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_valoraproximadoautomatico"]:$this->pc30_valoraproximadoautomatico);
       $this->pc30_basesolicitacao = ($this->pc30_basesolicitacao == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_basesolicitacao"]:$this->pc30_basesolicitacao);
       $this->pc30_baseprocessocompras = ($this->pc30_baseprocessocompras == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_baseprocessocompras"]:$this->pc30_baseprocessocompras);
       $this->pc30_baseempenhos = ($this->pc30_baseempenhos == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_baseempenhos"]:$this->pc30_baseempenhos);
       $this->pc30_maximodiasorcamento = ($this->pc30_maximodiasorcamento == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_maximodiasorcamento"]:$this->pc30_maximodiasorcamento);
       $this->pc30_validadepadraocertificado = ($this->pc30_validadepadraocertificado == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_validadepadraocertificado"]:$this->pc30_validadepadraocertificado);
       $this->pc30_tipovalidade = ($this->pc30_tipovalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_tipovalidade"]:$this->pc30_tipovalidade);
       $this->pc30_importaresumoemp = ($this->pc30_importaresumoemp == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_importaresumoemp"]:$this->pc30_importaresumoemp);
       $this->pc30_diasdebitosvencidos = ($this->pc30_diasdebitosvencidos == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_diasdebitosvencidos"]:$this->pc30_diasdebitosvencidos);
       $this->pc30_notificaemail = ($this->pc30_notificaemail == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_notificaemail"]:$this->pc30_notificaemail);
       $this->pc30_notificacarta = ($this->pc30_notificacarta == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_notificacarta"]:$this->pc30_notificacarta);
       $this->pc30_permitirgerarnotifdebitos = ($this->pc30_permitirgerarnotifdebitos == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc30_permitirgerarnotifdebitos"]:$this->pc30_permitirgerarnotifdebitos);
       $this->pc30_consultarelatoriodepartamento = ($this->pc30_consultarelatoriodepartamento == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_consultarelatoriodepartamento"]:$this->pc30_consultarelatoriodepartamento);
     }else{
       $this->pc30_instit = ($this->pc30_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["pc30_instit"]:$this->pc30_instit);
     }
   }
   // funcao para inclusao
   function incluir ($pc30_instit){ 
      $this->atualizacampos();
     if($this->pc30_horas == null ){ 
       $this->erro_sql = " Campo Hora padrão nao Informado.";
       $this->erro_campo = "pc30_horas";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_dias == null ){ 
       $this->erro_sql = " Campo Dias de prazo padrão para orçamento nao Informado.";
       $this->erro_campo = "pc30_dias";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_tipcom == null ){ 
       $this->erro_sql = " Campo Código do tipo de compra nao Informado.";
       $this->erro_campo = "pc30_tipcom";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_unid == null ){ 
       $this->erro_sql = " Campo Código da unidade padrão nao Informado.";
       $this->erro_campo = "pc30_unid";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_obrigajust == null ){ 
       $this->erro_sql = " Campo Obrigar justificativa do item nao Informado.";
       $this->erro_campo = "pc30_obrigajust";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_obrigamat == null ){ 
       $this->erro_sql = " Campo Obrigar informação do material nao Informado.";
       $this->erro_campo = "pc30_obrigamat";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_gerareserva == null ){ 
       $this->erro_sql = " Campo Gera reserva de dotações automatico nao Informado.";
       $this->erro_campo = "pc30_gerareserva";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_liberaitem == null ){ 
       $this->erro_sql = " Campo Libera sem item cadastrado nao Informado.";
       $this->erro_campo = "pc30_liberaitem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_liberado == null ){ 
       $this->erro_sql = " Campo Liberação total no processo de compras nao Informado.";
       $this->erro_campo = "pc30_liberado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_seltipo == null ){ 
       $this->erro_sql = " Campo Selecionar tipo de solicitação nao Informado.";
       $this->erro_campo = "pc30_seltipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_sugforn == null ){ 
       $this->erro_sql = " Campo Sugerir fornecedores nao Informado.";
       $this->erro_campo = "pc30_sugforn";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_mincar == null ){ 
       $this->erro_sql = " Campo Mínimo de caracteres nao Informado.";
       $this->erro_campo = "pc30_mincar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_permsemdotac == null ){ 
       $this->erro_sql = " Campo Permitir cadastro de itens sem dotação nao Informado.";
       $this->erro_campo = "pc30_permsemdotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_passadepart == null ){ 
       $this->erro_sql = " Campo Passa departamento ao escolher dotação nao Informado.";
       $this->erro_campo = "pc30_passadepart";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_digval == null ){ 
       $this->erro_sql = " Campo Permitir digitar valor aproximado nao Informado.";
       $this->erro_campo = "pc30_digval";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_libdotac == null ){ 
       $this->erro_sql = " Campo Liberar item sem dotacao nao Informado.";
       $this->erro_campo = "pc30_libdotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_tipoemiss == null ){ 
       $this->erro_sql = " Campo Emissão da solicitação nao Informado.";
       $this->erro_campo = "pc30_tipoemiss";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_comsaldo == null ){ 
       $this->erro_sql = " Campo Imprimir com saldo reservado nao Informado.";
       $this->erro_campo = "pc30_comsaldo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_contrandsol == null ){ 
       $this->erro_sql = " Campo Controla Andamento da Solicitação nao Informado.";
       $this->erro_campo = "pc30_contrandsol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_tipoprocsol == null ){ 
       $this->erro_sql = " Campo Tipo de Processo para item da solicitação nao Informado.";
       $this->erro_campo = "pc30_tipoprocsol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_itenslibaut == null ){ 
       $this->erro_sql = " Campo Todos os itens liberados p/ autorização nao Informado.";
       $this->erro_campo = "pc30_itenslibaut";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_comobs == null ){ 
       $this->erro_sql = " Campo Imprimir observação do cert. reg. cadastral nao Informado.";
       $this->erro_campo = "pc30_comobs";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_ultdotac == null ){ 
       $this->erro_sql = " Campo Trazer últ. dotação cadastr. na solic. nao Informado.";
       $this->erro_campo = "pc30_ultdotac";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_fornecdeb == null ){ 
       $this->erro_sql = " Campo Utilização de fornecedor em débito nao Informado.";
       $this->erro_campo = "pc30_fornecdeb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_emiteemail == null ){ 
       $this->pc30_emiteemail = "f";
     }
     if($this->pc30_modeloorc == null ){ 
       $this->pc30_modeloorc = "0";
     }
     if($this->pc30_modeloordemcompra == null ){ 
       $this->erro_sql = " Campo Modelo Ordem de Compra nao Informado.";
       $this->erro_campo = "pc30_modeloordemcompra";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_modeloorcsol == null ){ 
       $this->erro_sql = " Campo Modelo Orçamento solicitação nao Informado.";
       $this->erro_campo = "pc30_modeloorcsol";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_dotacaopordepartamento == null ){ 
       $this->erro_sql = " Campo Trazer Dotações Ligadas ao Departamento nao Informado.";
       $this->erro_campo = "pc30_dotacaopordepartamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_valoraproximadoautomatico == null ){ 
       $this->erro_sql = " Campo Traz Valor Automático nao Informado.";
       $this->erro_campo = "pc30_valoraproximadoautomatico";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_basesolicitacao == null ){ 
       $this->pc30_basesolicitacao = "0";
     }
     if($this->pc30_baseprocessocompras == null ){ 
       $this->pc30_baseprocessocompras = "0";
     }
     if($this->pc30_baseempenhos == null ){ 
       $this->pc30_baseempenhos = "0";
     }
     if($this->pc30_maximodiasorcamento == null ){ 
       $this->pc30_maximodiasorcamento = "0";
     }
     if($this->pc30_validadepadraocertificado == null ){ 
       $this->pc30_validadepadraocertificado = "0";
     }
     if($this->pc30_tipovalidade == null ){ 
       $this->pc30_tipovalidade = "0";
     }
     if($this->pc30_importaresumoemp == null ){ 
       $this->erro_sql = " Campo Importa resumo do empenho para ordem de compra nao Informado.";
       $this->erro_campo = "pc30_importaresumoemp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_diasdebitosvencidos == null ){ 
       $this->erro_sql = " Campo Quantidade  de Dias Débito Vencido nao Informado.";
       $this->erro_campo = "pc30_diasdebitosvencidos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_notificaemail == null ){ 
       $this->erro_sql = " Campo Envia Email na Notificação nao Informado.";
       $this->erro_campo = "pc30_notificaemail";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_notificacarta == null ){ 
       $this->erro_sql = " Campo Envia Carta na Notificação nao Informado.";
       $this->erro_campo = "pc30_notificacarta";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_permitirgerarnotifdebitos == null ){ 
       $this->erro_sql = " Campo Permitir Gerar Notificação de Débitos nao Informado.";
       $this->erro_campo = "pc30_permitirgerarnotifdebitos";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc30_consultarelatoriodepartamento == null ){ 
       $this->erro_sql = " Campo Consulta por nao Informado.";
       $this->erro_campo = "pc30_consultarelatoriodepartamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->pc30_instit = $pc30_instit; 
     if(($this->pc30_instit == null) || ($this->pc30_instit == "") ){ 
       $this->erro_sql = " Campo pc30_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcparam(
                                       pc30_instit 
                                      ,pc30_horas 
                                      ,pc30_dias 
                                      ,pc30_tipcom 
                                      ,pc30_unid 
                                      ,pc30_obrigajust 
                                      ,pc30_obrigamat 
                                      ,pc30_gerareserva 
                                      ,pc30_liberaitem 
                                      ,pc30_liberado 
                                      ,pc30_seltipo 
                                      ,pc30_sugforn 
                                      ,pc30_mincar 
                                      ,pc30_permsemdotac 
                                      ,pc30_passadepart 
                                      ,pc30_digval 
                                      ,pc30_libdotac 
                                      ,pc30_tipoemiss 
                                      ,pc30_comsaldo 
                                      ,pc30_contrandsol 
                                      ,pc30_tipoprocsol 
                                      ,pc30_itenslibaut 
                                      ,pc30_comobs 
                                      ,pc30_ultdotac 
                                      ,pc30_fornecdeb 
                                      ,pc30_emiteemail 
                                      ,pc30_modeloorc 
                                      ,pc30_modeloordemcompra 
                                      ,pc30_modeloorcsol 
                                      ,pc30_dotacaopordepartamento 
                                      ,pc30_valoraproximadoautomatico 
                                      ,pc30_basesolicitacao 
                                      ,pc30_baseprocessocompras 
                                      ,pc30_baseempenhos 
                                      ,pc30_maximodiasorcamento 
                                      ,pc30_validadepadraocertificado 
                                      ,pc30_tipovalidade 
                                      ,pc30_importaresumoemp 
                                      ,pc30_diasdebitosvencidos 
                                      ,pc30_notificaemail 
                                      ,pc30_notificacarta 
                                      ,pc30_permitirgerarnotifdebitos 
                                      ,pc30_consultarelatoriodepartamento 
                       )
                values (
                                $this->pc30_instit 
                               ,'$this->pc30_horas' 
                               ,$this->pc30_dias 
                               ,$this->pc30_tipcom 
                               ,$this->pc30_unid 
                               ,'$this->pc30_obrigajust' 
                               ,'$this->pc30_obrigamat' 
                               ,'$this->pc30_gerareserva' 
                               ,'$this->pc30_liberaitem' 
                               ,'$this->pc30_liberado' 
                               ,'$this->pc30_seltipo' 
                               ,'$this->pc30_sugforn' 
                               ,'$this->pc30_mincar' 
                               ,'$this->pc30_permsemdotac' 
                               ,'$this->pc30_passadepart' 
                               ,'$this->pc30_digval' 
                               ,'$this->pc30_libdotac' 
                               ,'$this->pc30_tipoemiss' 
                               ,'$this->pc30_comsaldo' 
                               ,'$this->pc30_contrandsol' 
                               ,$this->pc30_tipoprocsol 
                               ,'$this->pc30_itenslibaut' 
                               ,'$this->pc30_comobs' 
                               ,'$this->pc30_ultdotac' 
                               ,$this->pc30_fornecdeb 
                               ,'$this->pc30_emiteemail' 
                               ,$this->pc30_modeloorc 
                               ,$this->pc30_modeloordemcompra 
                               ,$this->pc30_modeloorcsol 
                               ,'$this->pc30_dotacaopordepartamento' 
                               ,'$this->pc30_valoraproximadoautomatico' 
                               ,$this->pc30_basesolicitacao 
                               ,$this->pc30_baseprocessocompras 
                               ,$this->pc30_baseempenhos 
                               ,$this->pc30_maximodiasorcamento 
                               ,$this->pc30_validadepadraocertificado 
                               ,$this->pc30_tipovalidade 
                               ,'$this->pc30_importaresumoemp' 
                               ,$this->pc30_diasdebitosvencidos 
                               ,'$this->pc30_notificaemail' 
                               ,'$this->pc30_notificacarta' 
                               ,'$this->pc30_permitirgerarnotifdebitos' 
                               ,$this->pc30_consultarelatoriodepartamento 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "pcparam ($this->pc30_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "pcparam já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "pcparam ($this->pc30_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc30_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc30_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8114,'$this->pc30_instit','I')");
       $resac = db_query("insert into db_acount values($acount,1058,8114,'','".AddSlashes(pg_result($resaco,0,'pc30_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6443,'','".AddSlashes(pg_result($resaco,0,'pc30_horas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6442,'','".AddSlashes(pg_result($resaco,0,'pc30_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6452,'','".AddSlashes(pg_result($resaco,0,'pc30_tipcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6454,'','".AddSlashes(pg_result($resaco,0,'pc30_unid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6511,'','".AddSlashes(pg_result($resaco,0,'pc30_obrigajust'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6519,'','".AddSlashes(pg_result($resaco,0,'pc30_obrigamat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6527,'','".AddSlashes(pg_result($resaco,0,'pc30_gerareserva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6528,'','".AddSlashes(pg_result($resaco,0,'pc30_liberaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6529,'','".AddSlashes(pg_result($resaco,0,'pc30_liberado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6562,'','".AddSlashes(pg_result($resaco,0,'pc30_seltipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6563,'','".AddSlashes(pg_result($resaco,0,'pc30_sugforn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6604,'','".AddSlashes(pg_result($resaco,0,'pc30_mincar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6613,'','".AddSlashes(pg_result($resaco,0,'pc30_permsemdotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6614,'','".AddSlashes(pg_result($resaco,0,'pc30_passadepart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6615,'','".AddSlashes(pg_result($resaco,0,'pc30_digval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6616,'','".AddSlashes(pg_result($resaco,0,'pc30_libdotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,6857,'','".AddSlashes(pg_result($resaco,0,'pc30_tipoemiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,7624,'','".AddSlashes(pg_result($resaco,0,'pc30_comsaldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,7864,'','".AddSlashes(pg_result($resaco,0,'pc30_contrandsol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,7865,'','".AddSlashes(pg_result($resaco,0,'pc30_tipoprocsol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,8113,'','".AddSlashes(pg_result($resaco,0,'pc30_itenslibaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,9481,'','".AddSlashes(pg_result($resaco,0,'pc30_comobs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,9550,'','".AddSlashes(pg_result($resaco,0,'pc30_ultdotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,10548,'','".AddSlashes(pg_result($resaco,0,'pc30_fornecdeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,10549,'','".AddSlashes(pg_result($resaco,0,'pc30_emiteemail'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,10965,'','".AddSlashes(pg_result($resaco,0,'pc30_modeloorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,11071,'','".AddSlashes(pg_result($resaco,0,'pc30_modeloordemcompra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,11288,'','".AddSlashes(pg_result($resaco,0,'pc30_modeloorcsol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,15325,'','".AddSlashes(pg_result($resaco,0,'pc30_dotacaopordepartamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,15576,'','".AddSlashes(pg_result($resaco,0,'pc30_valoraproximadoautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,15577,'','".AddSlashes(pg_result($resaco,0,'pc30_basesolicitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,15578,'','".AddSlashes(pg_result($resaco,0,'pc30_baseprocessocompras'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,15579,'','".AddSlashes(pg_result($resaco,0,'pc30_baseempenhos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,15580,'','".AddSlashes(pg_result($resaco,0,'pc30_maximodiasorcamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,16554,'','".AddSlashes(pg_result($resaco,0,'pc30_validadepadraocertificado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,16555,'','".AddSlashes(pg_result($resaco,0,'pc30_tipovalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,17591,'','".AddSlashes(pg_result($resaco,0,'pc30_importaresumoemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,17636,'','".AddSlashes(pg_result($resaco,0,'pc30_diasdebitosvencidos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,17637,'','".AddSlashes(pg_result($resaco,0,'pc30_notificaemail'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,17638,'','".AddSlashes(pg_result($resaco,0,'pc30_notificacarta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,17712,'','".AddSlashes(pg_result($resaco,0,'pc30_permitirgerarnotifdebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1058,18828,'','".AddSlashes(pg_result($resaco,0,'pc30_consultarelatoriodepartamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc30_instit=null) { 
      $this->atualizacampos();
     $sql = " update pcparam set ";
     $virgula = "";
     if(trim($this->pc30_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_instit"])){ 
       $sql  .= $virgula." pc30_instit = $this->pc30_instit ";
       $virgula = ",";
       if(trim($this->pc30_instit) == null ){ 
         $this->erro_sql = " Campo Codigo da instituicao nao Informado.";
         $this->erro_campo = "pc30_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_horas)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_horas"])){ 
       $sql  .= $virgula." pc30_horas = '$this->pc30_horas' ";
       $virgula = ",";
       if(trim($this->pc30_horas) == null ){ 
         $this->erro_sql = " Campo Hora padrão nao Informado.";
         $this->erro_campo = "pc30_horas";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_dias)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_dias"])){ 
       $sql  .= $virgula." pc30_dias = $this->pc30_dias ";
       $virgula = ",";
       if(trim($this->pc30_dias) == null ){ 
         $this->erro_sql = " Campo Dias de prazo padrão para orçamento nao Informado.";
         $this->erro_campo = "pc30_dias";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_tipcom)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_tipcom"])){ 
       $sql  .= $virgula." pc30_tipcom = $this->pc30_tipcom ";
       $virgula = ",";
       if(trim($this->pc30_tipcom) == null ){ 
         $this->erro_sql = " Campo Código do tipo de compra nao Informado.";
         $this->erro_campo = "pc30_tipcom";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_unid)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_unid"])){ 
       $sql  .= $virgula." pc30_unid = $this->pc30_unid ";
       $virgula = ",";
       if(trim($this->pc30_unid) == null ){ 
         $this->erro_sql = " Campo Código da unidade padrão nao Informado.";
         $this->erro_campo = "pc30_unid";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_obrigajust)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_obrigajust"])){ 
       $sql  .= $virgula." pc30_obrigajust = '$this->pc30_obrigajust' ";
       $virgula = ",";
       if(trim($this->pc30_obrigajust) == null ){ 
         $this->erro_sql = " Campo Obrigar justificativa do item nao Informado.";
         $this->erro_campo = "pc30_obrigajust";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_obrigamat)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_obrigamat"])){ 
       $sql  .= $virgula." pc30_obrigamat = '$this->pc30_obrigamat' ";
       $virgula = ",";
       if(trim($this->pc30_obrigamat) == null ){ 
         $this->erro_sql = " Campo Obrigar informação do material nao Informado.";
         $this->erro_campo = "pc30_obrigamat";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_gerareserva)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_gerareserva"])){ 
       $sql  .= $virgula." pc30_gerareserva = '$this->pc30_gerareserva' ";
       $virgula = ",";
       if(trim($this->pc30_gerareserva) == null ){ 
         $this->erro_sql = " Campo Gera reserva de dotações automatico nao Informado.";
         $this->erro_campo = "pc30_gerareserva";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_liberaitem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_liberaitem"])){ 
       $sql  .= $virgula." pc30_liberaitem = '$this->pc30_liberaitem' ";
       $virgula = ",";
       if(trim($this->pc30_liberaitem) == null ){ 
         $this->erro_sql = " Campo Libera sem item cadastrado nao Informado.";
         $this->erro_campo = "pc30_liberaitem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_liberado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_liberado"])){ 
       $sql  .= $virgula." pc30_liberado = '$this->pc30_liberado' ";
       $virgula = ",";
       if(trim($this->pc30_liberado) == null ){ 
         $this->erro_sql = " Campo Liberação total no processo de compras nao Informado.";
         $this->erro_campo = "pc30_liberado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_seltipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_seltipo"])){ 
       $sql  .= $virgula." pc30_seltipo = '$this->pc30_seltipo' ";
       $virgula = ",";
       if(trim($this->pc30_seltipo) == null ){ 
         $this->erro_sql = " Campo Selecionar tipo de solicitação nao Informado.";
         $this->erro_campo = "pc30_seltipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_sugforn)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_sugforn"])){ 
       $sql  .= $virgula." pc30_sugforn = '$this->pc30_sugforn' ";
       $virgula = ",";
       if(trim($this->pc30_sugforn) == null ){ 
         $this->erro_sql = " Campo Sugerir fornecedores nao Informado.";
         $this->erro_campo = "pc30_sugforn";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_mincar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_mincar"])){ 
       $sql  .= $virgula." pc30_mincar = '$this->pc30_mincar' ";
       $virgula = ",";
       if(trim($this->pc30_mincar) == null ){ 
         $this->erro_sql = " Campo Mínimo de caracteres nao Informado.";
         $this->erro_campo = "pc30_mincar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_permsemdotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_permsemdotac"])){ 
       $sql  .= $virgula." pc30_permsemdotac = '$this->pc30_permsemdotac' ";
       $virgula = ",";
       if(trim($this->pc30_permsemdotac) == null ){ 
         $this->erro_sql = " Campo Permitir cadastro de itens sem dotação nao Informado.";
         $this->erro_campo = "pc30_permsemdotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_passadepart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_passadepart"])){ 
       $sql  .= $virgula." pc30_passadepart = '$this->pc30_passadepart' ";
       $virgula = ",";
       if(trim($this->pc30_passadepart) == null ){ 
         $this->erro_sql = " Campo Passa departamento ao escolher dotação nao Informado.";
         $this->erro_campo = "pc30_passadepart";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_digval)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_digval"])){ 
       $sql  .= $virgula." pc30_digval = '$this->pc30_digval' ";
       $virgula = ",";
       if(trim($this->pc30_digval) == null ){ 
         $this->erro_sql = " Campo Permitir digitar valor aproximado nao Informado.";
         $this->erro_campo = "pc30_digval";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_libdotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_libdotac"])){ 
       $sql  .= $virgula." pc30_libdotac = '$this->pc30_libdotac' ";
       $virgula = ",";
       if(trim($this->pc30_libdotac) == null ){ 
         $this->erro_sql = " Campo Liberar item sem dotacao nao Informado.";
         $this->erro_campo = "pc30_libdotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_tipoemiss)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_tipoemiss"])){ 
       $sql  .= $virgula." pc30_tipoemiss = '$this->pc30_tipoemiss' ";
       $virgula = ",";
       if(trim($this->pc30_tipoemiss) == null ){ 
         $this->erro_sql = " Campo Emissão da solicitação nao Informado.";
         $this->erro_campo = "pc30_tipoemiss";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_comsaldo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_comsaldo"])){ 
       $sql  .= $virgula." pc30_comsaldo = '$this->pc30_comsaldo' ";
       $virgula = ",";
       if(trim($this->pc30_comsaldo) == null ){ 
         $this->erro_sql = " Campo Imprimir com saldo reservado nao Informado.";
         $this->erro_campo = "pc30_comsaldo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_contrandsol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_contrandsol"])){ 
       $sql  .= $virgula." pc30_contrandsol = '$this->pc30_contrandsol' ";
       $virgula = ",";
       if(trim($this->pc30_contrandsol) == null ){ 
         $this->erro_sql = " Campo Controla Andamento da Solicitação nao Informado.";
         $this->erro_campo = "pc30_contrandsol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_tipoprocsol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_tipoprocsol"])){ 
       $sql  .= $virgula." pc30_tipoprocsol = $this->pc30_tipoprocsol ";
       $virgula = ",";
       if(trim($this->pc30_tipoprocsol) == null ){ 
         $this->erro_sql = " Campo Tipo de Processo para item da solicitação nao Informado.";
         $this->erro_campo = "pc30_tipoprocsol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_itenslibaut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_itenslibaut"])){ 
       $sql  .= $virgula." pc30_itenslibaut = '$this->pc30_itenslibaut' ";
       $virgula = ",";
       if(trim($this->pc30_itenslibaut) == null ){ 
         $this->erro_sql = " Campo Todos os itens liberados p/ autorização nao Informado.";
         $this->erro_campo = "pc30_itenslibaut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_comobs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_comobs"])){ 
       $sql  .= $virgula." pc30_comobs = '$this->pc30_comobs' ";
       $virgula = ",";
       if(trim($this->pc30_comobs) == null ){ 
         $this->erro_sql = " Campo Imprimir observação do cert. reg. cadastral nao Informado.";
         $this->erro_campo = "pc30_comobs";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_ultdotac)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_ultdotac"])){ 
       $sql  .= $virgula." pc30_ultdotac = '$this->pc30_ultdotac' ";
       $virgula = ",";
       if(trim($this->pc30_ultdotac) == null ){ 
         $this->erro_sql = " Campo Trazer últ. dotação cadastr. na solic. nao Informado.";
         $this->erro_campo = "pc30_ultdotac";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_fornecdeb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_fornecdeb"])){ 
       $sql  .= $virgula." pc30_fornecdeb = $this->pc30_fornecdeb ";
       $virgula = ",";
       if(trim($this->pc30_fornecdeb) == null ){ 
         $this->erro_sql = " Campo Utilização de fornecedor em débito nao Informado.";
         $this->erro_campo = "pc30_fornecdeb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_emiteemail)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_emiteemail"])){ 
       $sql  .= $virgula." pc30_emiteemail = '$this->pc30_emiteemail' ";
       $virgula = ",";
     }
     if(trim($this->pc30_modeloorc)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_modeloorc"])){ 
        if(trim($this->pc30_modeloorc)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc30_modeloorc"])){ 
           $this->pc30_modeloorc = "0" ; 
        } 
       $sql  .= $virgula." pc30_modeloorc = $this->pc30_modeloorc ";
       $virgula = ",";
     }
     if(trim($this->pc30_modeloordemcompra)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_modeloordemcompra"])){ 
       $sql  .= $virgula." pc30_modeloordemcompra = $this->pc30_modeloordemcompra ";
       $virgula = ",";
       if(trim($this->pc30_modeloordemcompra) == null ){ 
         $this->erro_sql = " Campo Modelo Ordem de Compra nao Informado.";
         $this->erro_campo = "pc30_modeloordemcompra";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_modeloorcsol)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_modeloorcsol"])){ 
       $sql  .= $virgula." pc30_modeloorcsol = $this->pc30_modeloorcsol ";
       $virgula = ",";
       if(trim($this->pc30_modeloorcsol) == null ){ 
         $this->erro_sql = " Campo Modelo Orçamento solicitação nao Informado.";
         $this->erro_campo = "pc30_modeloorcsol";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_dotacaopordepartamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_dotacaopordepartamento"])){ 
       $sql  .= $virgula." pc30_dotacaopordepartamento = '$this->pc30_dotacaopordepartamento' ";
       $virgula = ",";
       if(trim($this->pc30_dotacaopordepartamento) == null ){ 
         $this->erro_sql = " Campo Trazer Dotações Ligadas ao Departamento nao Informado.";
         $this->erro_campo = "pc30_dotacaopordepartamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_valoraproximadoautomatico)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_valoraproximadoautomatico"])){ 
       $sql  .= $virgula." pc30_valoraproximadoautomatico = '$this->pc30_valoraproximadoautomatico' ";
       $virgula = ",";
       if(trim($this->pc30_valoraproximadoautomatico) == null ){ 
         $this->erro_sql = " Campo Traz Valor Automático nao Informado.";
         $this->erro_campo = "pc30_valoraproximadoautomatico";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_basesolicitacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_basesolicitacao"])){ 
        if(trim($this->pc30_basesolicitacao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc30_basesolicitacao"])){ 
           $this->pc30_basesolicitacao = "0" ; 
        } 
       $sql  .= $virgula." pc30_basesolicitacao = $this->pc30_basesolicitacao ";
       $virgula = ",";
     }
     if(trim($this->pc30_baseprocessocompras)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_baseprocessocompras"])){ 
        if(trim($this->pc30_baseprocessocompras)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc30_baseprocessocompras"])){ 
           $this->pc30_baseprocessocompras = "0" ; 
        } 
       $sql  .= $virgula." pc30_baseprocessocompras = $this->pc30_baseprocessocompras ";
       $virgula = ",";
     }
     if(trim($this->pc30_baseempenhos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_baseempenhos"])){ 
        if(trim($this->pc30_baseempenhos)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc30_baseempenhos"])){ 
           $this->pc30_baseempenhos = "0" ; 
        } 
       $sql  .= $virgula." pc30_baseempenhos = $this->pc30_baseempenhos ";
       $virgula = ",";
     }
     if(trim($this->pc30_maximodiasorcamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_maximodiasorcamento"])){ 
        if(trim($this->pc30_maximodiasorcamento)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc30_maximodiasorcamento"])){ 
           $this->pc30_maximodiasorcamento = "0" ; 
        } 
       $sql  .= $virgula." pc30_maximodiasorcamento = $this->pc30_maximodiasorcamento ";
       $virgula = ",";
     }
     if(trim($this->pc30_validadepadraocertificado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_validadepadraocertificado"])){ 
        if(trim($this->pc30_validadepadraocertificado)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc30_validadepadraocertificado"])){ 
           $this->pc30_validadepadraocertificado = "0" ; 
        } 
       $sql  .= $virgula." pc30_validadepadraocertificado = $this->pc30_validadepadraocertificado ";
       $virgula = ",";
     }
     if(trim($this->pc30_tipovalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_tipovalidade"])){ 
        if(trim($this->pc30_tipovalidade)=="" && isset($GLOBALS["HTTP_POST_VARS"]["pc30_tipovalidade"])){ 
           $this->pc30_tipovalidade = "0" ; 
        } 
       $sql  .= $virgula." pc30_tipovalidade = $this->pc30_tipovalidade ";
       $virgula = ",";
     }
     if(trim($this->pc30_importaresumoemp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_importaresumoemp"])){ 
       $sql  .= $virgula." pc30_importaresumoemp = '$this->pc30_importaresumoemp' ";
       $virgula = ",";
       if(trim($this->pc30_importaresumoemp) == null ){ 
         $this->erro_sql = " Campo Importa resumo do empenho para ordem de compra nao Informado.";
         $this->erro_campo = "pc30_importaresumoemp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_diasdebitosvencidos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_diasdebitosvencidos"])){ 
       $sql  .= $virgula." pc30_diasdebitosvencidos = $this->pc30_diasdebitosvencidos ";
       $virgula = ",";
       if(trim($this->pc30_diasdebitosvencidos) == null ){ 
         $this->erro_sql = " Campo Quantidade  de Dias Débito Vencido nao Informado.";
         $this->erro_campo = "pc30_diasdebitosvencidos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_notificaemail)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_notificaemail"])){ 
       $sql  .= $virgula." pc30_notificaemail = '$this->pc30_notificaemail' ";
       $virgula = ",";
       if(trim($this->pc30_notificaemail) == null ){ 
         $this->erro_sql = " Campo Envia Email na Notificação nao Informado.";
         $this->erro_campo = "pc30_notificaemail";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_notificacarta)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_notificacarta"])){ 
       $sql  .= $virgula." pc30_notificacarta = '$this->pc30_notificacarta' ";
       $virgula = ",";
       if(trim($this->pc30_notificacarta) == null ){ 
         $this->erro_sql = " Campo Envia Carta na Notificação nao Informado.";
         $this->erro_campo = "pc30_notificacarta";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_permitirgerarnotifdebitos)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_permitirgerarnotifdebitos"])){ 
       $sql  .= $virgula." pc30_permitirgerarnotifdebitos = '$this->pc30_permitirgerarnotifdebitos' ";
       $virgula = ",";
       if(trim($this->pc30_permitirgerarnotifdebitos) == null ){ 
         $this->erro_sql = " Campo Permitir Gerar Notificação de Débitos nao Informado.";
         $this->erro_campo = "pc30_permitirgerarnotifdebitos";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc30_consultarelatoriodepartamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc30_consultarelatoriodepartamento"])){ 
       $sql  .= $virgula." pc30_consultarelatoriodepartamento = $this->pc30_consultarelatoriodepartamento ";
       $virgula = ",";
       if(trim($this->pc30_consultarelatoriodepartamento) == null ){ 
         $this->erro_sql = " Campo Consulta por nao Informado.";
         $this->erro_campo = "pc30_consultarelatoriodepartamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc30_instit!=null){
       $sql .= " pc30_instit = $this->pc30_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc30_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8114,'$this->pc30_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_instit"]) || $this->pc30_instit != "")
           $resac = db_query("insert into db_acount values($acount,1058,8114,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_instit'))."','$this->pc30_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_horas"]) || $this->pc30_horas != "")
           $resac = db_query("insert into db_acount values($acount,1058,6443,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_horas'))."','$this->pc30_horas',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_dias"]) || $this->pc30_dias != "")
           $resac = db_query("insert into db_acount values($acount,1058,6442,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_dias'))."','$this->pc30_dias',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_tipcom"]) || $this->pc30_tipcom != "")
           $resac = db_query("insert into db_acount values($acount,1058,6452,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_tipcom'))."','$this->pc30_tipcom',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_unid"]) || $this->pc30_unid != "")
           $resac = db_query("insert into db_acount values($acount,1058,6454,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_unid'))."','$this->pc30_unid',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_obrigajust"]) || $this->pc30_obrigajust != "")
           $resac = db_query("insert into db_acount values($acount,1058,6511,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_obrigajust'))."','$this->pc30_obrigajust',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_obrigamat"]) || $this->pc30_obrigamat != "")
           $resac = db_query("insert into db_acount values($acount,1058,6519,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_obrigamat'))."','$this->pc30_obrigamat',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_gerareserva"]) || $this->pc30_gerareserva != "")
           $resac = db_query("insert into db_acount values($acount,1058,6527,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_gerareserva'))."','$this->pc30_gerareserva',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_liberaitem"]) || $this->pc30_liberaitem != "")
           $resac = db_query("insert into db_acount values($acount,1058,6528,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_liberaitem'))."','$this->pc30_liberaitem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_liberado"]) || $this->pc30_liberado != "")
           $resac = db_query("insert into db_acount values($acount,1058,6529,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_liberado'))."','$this->pc30_liberado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_seltipo"]) || $this->pc30_seltipo != "")
           $resac = db_query("insert into db_acount values($acount,1058,6562,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_seltipo'))."','$this->pc30_seltipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_sugforn"]) || $this->pc30_sugforn != "")
           $resac = db_query("insert into db_acount values($acount,1058,6563,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_sugforn'))."','$this->pc30_sugforn',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_mincar"]) || $this->pc30_mincar != "")
           $resac = db_query("insert into db_acount values($acount,1058,6604,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_mincar'))."','$this->pc30_mincar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_permsemdotac"]) || $this->pc30_permsemdotac != "")
           $resac = db_query("insert into db_acount values($acount,1058,6613,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_permsemdotac'))."','$this->pc30_permsemdotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_passadepart"]) || $this->pc30_passadepart != "")
           $resac = db_query("insert into db_acount values($acount,1058,6614,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_passadepart'))."','$this->pc30_passadepart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_digval"]) || $this->pc30_digval != "")
           $resac = db_query("insert into db_acount values($acount,1058,6615,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_digval'))."','$this->pc30_digval',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_libdotac"]) || $this->pc30_libdotac != "")
           $resac = db_query("insert into db_acount values($acount,1058,6616,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_libdotac'))."','$this->pc30_libdotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_tipoemiss"]) || $this->pc30_tipoemiss != "")
           $resac = db_query("insert into db_acount values($acount,1058,6857,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_tipoemiss'))."','$this->pc30_tipoemiss',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_comsaldo"]) || $this->pc30_comsaldo != "")
           $resac = db_query("insert into db_acount values($acount,1058,7624,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_comsaldo'))."','$this->pc30_comsaldo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_contrandsol"]) || $this->pc30_contrandsol != "")
           $resac = db_query("insert into db_acount values($acount,1058,7864,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_contrandsol'))."','$this->pc30_contrandsol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_tipoprocsol"]) || $this->pc30_tipoprocsol != "")
           $resac = db_query("insert into db_acount values($acount,1058,7865,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_tipoprocsol'))."','$this->pc30_tipoprocsol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_itenslibaut"]) || $this->pc30_itenslibaut != "")
           $resac = db_query("insert into db_acount values($acount,1058,8113,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_itenslibaut'))."','$this->pc30_itenslibaut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_comobs"]) || $this->pc30_comobs != "")
           $resac = db_query("insert into db_acount values($acount,1058,9481,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_comobs'))."','$this->pc30_comobs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_ultdotac"]) || $this->pc30_ultdotac != "")
           $resac = db_query("insert into db_acount values($acount,1058,9550,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_ultdotac'))."','$this->pc30_ultdotac',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_fornecdeb"]) || $this->pc30_fornecdeb != "")
           $resac = db_query("insert into db_acount values($acount,1058,10548,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_fornecdeb'))."','$this->pc30_fornecdeb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_emiteemail"]) || $this->pc30_emiteemail != "")
           $resac = db_query("insert into db_acount values($acount,1058,10549,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_emiteemail'))."','$this->pc30_emiteemail',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_modeloorc"]) || $this->pc30_modeloorc != "")
           $resac = db_query("insert into db_acount values($acount,1058,10965,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_modeloorc'))."','$this->pc30_modeloorc',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_modeloordemcompra"]) || $this->pc30_modeloordemcompra != "")
           $resac = db_query("insert into db_acount values($acount,1058,11071,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_modeloordemcompra'))."','$this->pc30_modeloordemcompra',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_modeloorcsol"]) || $this->pc30_modeloorcsol != "")
           $resac = db_query("insert into db_acount values($acount,1058,11288,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_modeloorcsol'))."','$this->pc30_modeloorcsol',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_dotacaopordepartamento"]) || $this->pc30_dotacaopordepartamento != "")
           $resac = db_query("insert into db_acount values($acount,1058,15325,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_dotacaopordepartamento'))."','$this->pc30_dotacaopordepartamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_valoraproximadoautomatico"]) || $this->pc30_valoraproximadoautomatico != "")
           $resac = db_query("insert into db_acount values($acount,1058,15576,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_valoraproximadoautomatico'))."','$this->pc30_valoraproximadoautomatico',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_basesolicitacao"]) || $this->pc30_basesolicitacao != "")
           $resac = db_query("insert into db_acount values($acount,1058,15577,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_basesolicitacao'))."','$this->pc30_basesolicitacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_baseprocessocompras"]) || $this->pc30_baseprocessocompras != "")
           $resac = db_query("insert into db_acount values($acount,1058,15578,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_baseprocessocompras'))."','$this->pc30_baseprocessocompras',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_baseempenhos"]) || $this->pc30_baseempenhos != "")
           $resac = db_query("insert into db_acount values($acount,1058,15579,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_baseempenhos'))."','$this->pc30_baseempenhos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_maximodiasorcamento"]) || $this->pc30_maximodiasorcamento != "")
           $resac = db_query("insert into db_acount values($acount,1058,15580,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_maximodiasorcamento'))."','$this->pc30_maximodiasorcamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_validadepadraocertificado"]) || $this->pc30_validadepadraocertificado != "")
           $resac = db_query("insert into db_acount values($acount,1058,16554,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_validadepadraocertificado'))."','$this->pc30_validadepadraocertificado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_tipovalidade"]) || $this->pc30_tipovalidade != "")
           $resac = db_query("insert into db_acount values($acount,1058,16555,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_tipovalidade'))."','$this->pc30_tipovalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_importaresumoemp"]) || $this->pc30_importaresumoemp != "")
           $resac = db_query("insert into db_acount values($acount,1058,17591,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_importaresumoemp'))."','$this->pc30_importaresumoemp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_diasdebitosvencidos"]) || $this->pc30_diasdebitosvencidos != "")
           $resac = db_query("insert into db_acount values($acount,1058,17636,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_diasdebitosvencidos'))."','$this->pc30_diasdebitosvencidos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_notificaemail"]) || $this->pc30_notificaemail != "")
           $resac = db_query("insert into db_acount values($acount,1058,17637,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_notificaemail'))."','$this->pc30_notificaemail',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_notificacarta"]) || $this->pc30_notificacarta != "")
           $resac = db_query("insert into db_acount values($acount,1058,17638,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_notificacarta'))."','$this->pc30_notificacarta',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_permitirgerarnotifdebitos"]) || $this->pc30_permitirgerarnotifdebitos != "")
           $resac = db_query("insert into db_acount values($acount,1058,17712,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_permitirgerarnotifdebitos'))."','$this->pc30_permitirgerarnotifdebitos',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc30_consultarelatoriodepartamento"]) || $this->pc30_consultarelatoriodepartamento != "")
           $resac = db_query("insert into db_acount values($acount,1058,18828,'".AddSlashes(pg_result($resaco,$conresaco,'pc30_consultarelatoriodepartamento'))."','$this->pc30_consultarelatoriodepartamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pcparam nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc30_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pcparam nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc30_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc30_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc30_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc30_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8114,'$pc30_instit','E')");
         $resac = db_query("insert into db_acount values($acount,1058,8114,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6443,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_horas'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6442,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_dias'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6452,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_tipcom'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6454,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_unid'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6511,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_obrigajust'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6519,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_obrigamat'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6527,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_gerareserva'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6528,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_liberaitem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6529,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_liberado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6562,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_seltipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6563,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_sugforn'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6604,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_mincar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6613,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_permsemdotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6614,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_passadepart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6615,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_digval'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6616,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_libdotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,6857,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_tipoemiss'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,7624,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_comsaldo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,7864,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_contrandsol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,7865,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_tipoprocsol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,8113,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_itenslibaut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,9481,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_comobs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,9550,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_ultdotac'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,10548,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_fornecdeb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,10549,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_emiteemail'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,10965,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_modeloorc'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,11071,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_modeloordemcompra'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,11288,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_modeloorcsol'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,15325,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_dotacaopordepartamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,15576,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_valoraproximadoautomatico'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,15577,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_basesolicitacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,15578,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_baseprocessocompras'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,15579,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_baseempenhos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,15580,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_maximodiasorcamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,16554,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_validadepadraocertificado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,16555,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_tipovalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,17591,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_importaresumoemp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,17636,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_diasdebitosvencidos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,17637,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_notificaemail'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,17638,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_notificacarta'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,17712,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_permitirgerarnotifdebitos'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1058,18828,'','".AddSlashes(pg_result($resaco,$iresaco,'pc30_consultarelatoriodepartamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcparam
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc30_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc30_instit = $pc30_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "pcparam nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc30_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "pcparam nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc30_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc30_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao do recordset 
   function sql_record($sql) { 
     $result = db_query($sql);
     if($result==false){
       $this->numrows    = 0;
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Erro ao selecionar os registros.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:pcparam";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc30_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from pcparam ";
     $sql .= "      inner join pctipocompra  on  pctipocompra.pc50_codcom = pcparam.pc30_tipcom";
     $sql .= "      inner join matunid  on  matunid.m61_codmatunid = pcparam.pc30_unid";
     $sql2 = "";
     if($dbwhere==""){
       if($pc30_instit!=null ){
         $sql2 .= " where pcparam.pc30_instit = $pc30_instit "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
   // funcao do sql 
   function sql_query_file ( $pc30_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
     $sql = "select ";
     if($campos != "*" ){
       $campos_sql = split("#",$campos);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }else{
       $sql .= $campos;
     }
     $sql .= " from pcparam ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc30_instit!=null ){
         $sql2 .= " where pcparam.pc30_instit = $pc30_instit "; 
       } 
     }else if($dbwhere != ""){
       $sql2 = " where $dbwhere";
     }
     $sql .= $sql2;
     if($ordem != null ){
       $sql .= " order by ";
       $campos_sql = split("#",$ordem);
       $virgula = "";
       for($i=0;$i<sizeof($campos_sql);$i++){
         $sql .= $virgula.$campos_sql[$i];
         $virgula = ",";
       }
     }
     return $sql;
  }
}
?>