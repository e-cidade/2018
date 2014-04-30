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

//MODULO: habitacao
//CLASSE DA ENTIDADE habitprograma
class cl_habitprograma { 
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
   var $ht01_sequencial = 0; 
   var $ht01_habitgrupoprograma = 0; 
   var $ht01_descricao = null; 
   var $ht01_obs = null; 
   var $ht01_controlemultpartcandidato = 0; 
   var $ht01_controleqtd = 0; 
   var $ht01_validadeini_dia = null; 
   var $ht01_validadeini_mes = null; 
   var $ht01_validadeini_ano = null; 
   var $ht01_validadeini = null; 
   var $ht01_validadefim_dia = null; 
   var $ht01_validadefim_mes = null; 
   var $ht01_validadefim_ano = null; 
   var $ht01_validadefim = null; 
   var $ht01_descrcontrato = null; 
   var $ht01_lei = null; 
   var $ht01_exigeassconcedente = 'f'; 
   var $ht01_qtdparcpagamento = 0; 
   var $ht01_diapadraopagamento = 0; 
   var $ht01_receitapadraopagamento = 0; 
   var $ht01_exigevalcpf = 'f'; 
   var $ht01_qtdbenef = 0; 
   var $ht01_workflow = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht01_sequencial = int4 = Sequencial 
                 ht01_habitgrupoprograma = int4 = Grupo de Programa 
                 ht01_descricao = varchar(50) = Descrição 
                 ht01_obs = text = Observação 
                 ht01_controlemultpartcandidato = int4 = Controle de Participação 
                 ht01_controleqtd = int4 = Controle de Quantidade 
                 ht01_validadeini = date = Data Inicial de Validade 
                 ht01_validadefim = date = Data Final de Validade 
                 ht01_descrcontrato = text = Descrição do Contrato 
                 ht01_lei = varchar(50) = Lei 
                 ht01_exigeassconcedente = bool = Exige Assinatura do Concedente 
                 ht01_qtdparcpagamento = int4 = Quantidade Parcela Pagamento 
                 ht01_diapadraopagamento = int4 = Dia Padrão para Pagamento 
                 ht01_receitapadraopagamento = int4 = Receita Padrão para Pagamento 
                 ht01_exigevalcpf = bool = Exige CPF 
                 ht01_qtdbenef = int4 = Qtd. Beneficiados 
                 ht01_workflow = int4 = Código Work Flow 
                 ";
   //funcao construtor da classe 
   function cl_habitprograma() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitprograma"); 
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
       $this->ht01_sequencial = ($this->ht01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_sequencial"]:$this->ht01_sequencial);
       $this->ht01_habitgrupoprograma = ($this->ht01_habitgrupoprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_habitgrupoprograma"]:$this->ht01_habitgrupoprograma);
       $this->ht01_descricao = ($this->ht01_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_descricao"]:$this->ht01_descricao);
       $this->ht01_obs = ($this->ht01_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_obs"]:$this->ht01_obs);
       $this->ht01_controlemultpartcandidato = ($this->ht01_controlemultpartcandidato == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_controlemultpartcandidato"]:$this->ht01_controlemultpartcandidato);
       $this->ht01_controleqtd = ($this->ht01_controleqtd == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_controleqtd"]:$this->ht01_controleqtd);
       if($this->ht01_validadeini == ""){
         $this->ht01_validadeini_dia = ($this->ht01_validadeini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_validadeini_dia"]:$this->ht01_validadeini_dia);
         $this->ht01_validadeini_mes = ($this->ht01_validadeini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_validadeini_mes"]:$this->ht01_validadeini_mes);
         $this->ht01_validadeini_ano = ($this->ht01_validadeini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_validadeini_ano"]:$this->ht01_validadeini_ano);
         if($this->ht01_validadeini_dia != ""){
            $this->ht01_validadeini = $this->ht01_validadeini_ano."-".$this->ht01_validadeini_mes."-".$this->ht01_validadeini_dia;
         }
       }
       if($this->ht01_validadefim == ""){
         $this->ht01_validadefim_dia = ($this->ht01_validadefim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_validadefim_dia"]:$this->ht01_validadefim_dia);
         $this->ht01_validadefim_mes = ($this->ht01_validadefim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_validadefim_mes"]:$this->ht01_validadefim_mes);
         $this->ht01_validadefim_ano = ($this->ht01_validadefim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_validadefim_ano"]:$this->ht01_validadefim_ano);
         if($this->ht01_validadefim_dia != ""){
            $this->ht01_validadefim = $this->ht01_validadefim_ano."-".$this->ht01_validadefim_mes."-".$this->ht01_validadefim_dia;
         }
       }
       $this->ht01_descrcontrato = ($this->ht01_descrcontrato == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_descrcontrato"]:$this->ht01_descrcontrato);
       $this->ht01_lei = ($this->ht01_lei == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_lei"]:$this->ht01_lei);
       $this->ht01_exigeassconcedente = ($this->ht01_exigeassconcedente == "f"?@$GLOBALS["HTTP_POST_VARS"]["ht01_exigeassconcedente"]:$this->ht01_exigeassconcedente);
       $this->ht01_qtdparcpagamento = ($this->ht01_qtdparcpagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_qtdparcpagamento"]:$this->ht01_qtdparcpagamento);
       $this->ht01_diapadraopagamento = ($this->ht01_diapadraopagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_diapadraopagamento"]:$this->ht01_diapadraopagamento);
       $this->ht01_receitapadraopagamento = ($this->ht01_receitapadraopagamento == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_receitapadraopagamento"]:$this->ht01_receitapadraopagamento);
       $this->ht01_exigevalcpf = ($this->ht01_exigevalcpf == "f"?@$GLOBALS["HTTP_POST_VARS"]["ht01_exigevalcpf"]:$this->ht01_exigevalcpf);
       $this->ht01_qtdbenef = ($this->ht01_qtdbenef == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_qtdbenef"]:$this->ht01_qtdbenef);
       $this->ht01_workflow = ($this->ht01_workflow == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_workflow"]:$this->ht01_workflow);
     }else{
       $this->ht01_sequencial = ($this->ht01_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht01_sequencial"]:$this->ht01_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht01_sequencial){ 
      $this->atualizacampos();
     if($this->ht01_habitgrupoprograma == null ){ 
       $this->erro_sql = " Campo Grupo de Programa nao Informado.";
       $this->erro_campo = "ht01_habitgrupoprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ht01_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_controlemultpartcandidato == null ){ 
       $this->erro_sql = " Campo Controle de Participação nao Informado.";
       $this->erro_campo = "ht01_controlemultpartcandidato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_controleqtd == null ){ 
       $this->erro_sql = " Campo Controle de Quantidade nao Informado.";
       $this->erro_campo = "ht01_controleqtd";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_validadeini == null ){ 
       $this->ht01_validadeini = "null";
     }
     if($this->ht01_validadefim == null ){ 
       $this->ht01_validadefim = "null";
     }
     if($this->ht01_descrcontrato == null ){ 
       $this->erro_sql = " Campo Descrição do Contrato nao Informado.";
       $this->erro_campo = "ht01_descrcontrato";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_lei == null ){ 
       $this->erro_sql = " Campo Lei nao Informado.";
       $this->erro_campo = "ht01_lei";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_exigeassconcedente == null ){ 
       $this->erro_sql = " Campo Exige Assinatura do Concedente nao Informado.";
       $this->erro_campo = "ht01_exigeassconcedente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_qtdparcpagamento == null ){ 
       $this->erro_sql = " Campo Quantidade Parcela Pagamento nao Informado.";
       $this->erro_campo = "ht01_qtdparcpagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_diapadraopagamento == null ){ 
       $this->erro_sql = " Campo Dia Padrão para Pagamento nao Informado.";
       $this->erro_campo = "ht01_diapadraopagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_receitapadraopagamento == null ){ 
       $this->erro_sql = " Campo Receita Padrão para Pagamento nao Informado.";
       $this->erro_campo = "ht01_receitapadraopagamento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_exigevalcpf == null ){ 
       $this->erro_sql = " Campo Exige CPF nao Informado.";
       $this->erro_campo = "ht01_exigevalcpf";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht01_qtdbenef == null ){ 
       $this->ht01_qtdbenef = "0";
     }
     if($this->ht01_workflow == null ){ 
       $this->erro_sql = " Campo Código Work Flow nao Informado.";
       $this->erro_campo = "ht01_workflow";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht01_sequencial == "" || $ht01_sequencial == null ){
       $result = db_query("select nextval('habitprograma_ht01_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitprograma_ht01_sequencial_seq do campo: ht01_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht01_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitprograma_ht01_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht01_sequencial)){
         $this->erro_sql = " Campo ht01_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht01_sequencial = $ht01_sequencial; 
       }
     }
     if(($this->ht01_sequencial == null) || ($this->ht01_sequencial == "") ){ 
       $this->erro_sql = " Campo ht01_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitprograma(
                                       ht01_sequencial 
                                      ,ht01_habitgrupoprograma 
                                      ,ht01_descricao 
                                      ,ht01_obs 
                                      ,ht01_controlemultpartcandidato 
                                      ,ht01_controleqtd 
                                      ,ht01_validadeini 
                                      ,ht01_validadefim 
                                      ,ht01_descrcontrato 
                                      ,ht01_lei 
                                      ,ht01_exigeassconcedente 
                                      ,ht01_qtdparcpagamento 
                                      ,ht01_diapadraopagamento 
                                      ,ht01_receitapadraopagamento 
                                      ,ht01_exigevalcpf 
                                      ,ht01_qtdbenef 
                                      ,ht01_workflow 
                       )
                values (
                                $this->ht01_sequencial 
                               ,$this->ht01_habitgrupoprograma 
                               ,'$this->ht01_descricao' 
                               ,'$this->ht01_obs' 
                               ,$this->ht01_controlemultpartcandidato 
                               ,$this->ht01_controleqtd 
                               ,".($this->ht01_validadeini == "null" || $this->ht01_validadeini == ""?"null":"'".$this->ht01_validadeini."'")." 
                               ,".($this->ht01_validadefim == "null" || $this->ht01_validadefim == ""?"null":"'".$this->ht01_validadefim."'")." 
                               ,'$this->ht01_descrcontrato' 
                               ,'$this->ht01_lei' 
                               ,'$this->ht01_exigeassconcedente' 
                               ,$this->ht01_qtdparcpagamento 
                               ,$this->ht01_diapadraopagamento 
                               ,$this->ht01_receitapadraopagamento 
                               ,'$this->ht01_exigevalcpf' 
                               ,$this->ht01_qtdbenef 
                               ,$this->ht01_workflow 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Programa da Habitação ($this->ht01_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Programa da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Programa da Habitação ($this->ht01_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht01_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht01_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16934,'$this->ht01_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2989,16934,'','".AddSlashes(pg_result($resaco,0,'ht01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16935,'','".AddSlashes(pg_result($resaco,0,'ht01_habitgrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16936,'','".AddSlashes(pg_result($resaco,0,'ht01_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16937,'','".AddSlashes(pg_result($resaco,0,'ht01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16938,'','".AddSlashes(pg_result($resaco,0,'ht01_controlemultpartcandidato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16939,'','".AddSlashes(pg_result($resaco,0,'ht01_controleqtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16940,'','".AddSlashes(pg_result($resaco,0,'ht01_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16941,'','".AddSlashes(pg_result($resaco,0,'ht01_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16944,'','".AddSlashes(pg_result($resaco,0,'ht01_descrcontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16945,'','".AddSlashes(pg_result($resaco,0,'ht01_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16946,'','".AddSlashes(pg_result($resaco,0,'ht01_exigeassconcedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16947,'','".AddSlashes(pg_result($resaco,0,'ht01_qtdparcpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16948,'','".AddSlashes(pg_result($resaco,0,'ht01_diapadraopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16949,'','".AddSlashes(pg_result($resaco,0,'ht01_receitapadraopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,16950,'','".AddSlashes(pg_result($resaco,0,'ht01_exigevalcpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,17080,'','".AddSlashes(pg_result($resaco,0,'ht01_qtdbenef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2989,17858,'','".AddSlashes(pg_result($resaco,0,'ht01_workflow'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht01_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitprograma set ";
     $virgula = "";
     if(trim($this->ht01_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_sequencial"])){ 
       $sql  .= $virgula." ht01_sequencial = $this->ht01_sequencial ";
       $virgula = ",";
       if(trim($this->ht01_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht01_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_habitgrupoprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_habitgrupoprograma"])){ 
       $sql  .= $virgula." ht01_habitgrupoprograma = $this->ht01_habitgrupoprograma ";
       $virgula = ",";
       if(trim($this->ht01_habitgrupoprograma) == null ){ 
         $this->erro_sql = " Campo Grupo de Programa nao Informado.";
         $this->erro_campo = "ht01_habitgrupoprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_descricao"])){ 
       $sql  .= $virgula." ht01_descricao = '$this->ht01_descricao' ";
       $virgula = ",";
       if(trim($this->ht01_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ht01_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_obs"])){ 
       $sql  .= $virgula." ht01_obs = '$this->ht01_obs' ";
       $virgula = ",";
     }
     if(trim($this->ht01_controlemultpartcandidato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_controlemultpartcandidato"])){ 
       $sql  .= $virgula." ht01_controlemultpartcandidato = $this->ht01_controlemultpartcandidato ";
       $virgula = ",";
       if(trim($this->ht01_controlemultpartcandidato) == null ){ 
         $this->erro_sql = " Campo Controle de Participação nao Informado.";
         $this->erro_campo = "ht01_controlemultpartcandidato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_controleqtd)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_controleqtd"])){ 
       $sql  .= $virgula." ht01_controleqtd = $this->ht01_controleqtd ";
       $virgula = ",";
       if(trim($this->ht01_controleqtd) == null ){ 
         $this->erro_sql = " Campo Controle de Quantidade nao Informado.";
         $this->erro_campo = "ht01_controleqtd";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_validadeini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_validadeini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht01_validadeini_dia"] !="") ){ 
       $sql  .= $virgula." ht01_validadeini = '$this->ht01_validadeini' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_validadeini_dia"])){ 
         $sql  .= $virgula." ht01_validadeini = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ht01_validadefim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_validadefim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht01_validadefim_dia"] !="") ){ 
       $sql  .= $virgula." ht01_validadefim = '$this->ht01_validadefim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_validadefim_dia"])){ 
         $sql  .= $virgula." ht01_validadefim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ht01_descrcontrato)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_descrcontrato"])){ 
       $sql  .= $virgula." ht01_descrcontrato = '$this->ht01_descrcontrato' ";
       $virgula = ",";
       if(trim($this->ht01_descrcontrato) == null ){ 
         $this->erro_sql = " Campo Descrição do Contrato nao Informado.";
         $this->erro_campo = "ht01_descrcontrato";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_lei)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_lei"])){ 
       $sql  .= $virgula." ht01_lei = '$this->ht01_lei' ";
       $virgula = ",";
       if(trim($this->ht01_lei) == null ){ 
         $this->erro_sql = " Campo Lei nao Informado.";
         $this->erro_campo = "ht01_lei";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_exigeassconcedente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_exigeassconcedente"])){ 
       $sql  .= $virgula." ht01_exigeassconcedente = '$this->ht01_exigeassconcedente' ";
       $virgula = ",";
       if(trim($this->ht01_exigeassconcedente) == null ){ 
         $this->erro_sql = " Campo Exige Assinatura do Concedente nao Informado.";
         $this->erro_campo = "ht01_exigeassconcedente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_qtdparcpagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_qtdparcpagamento"])){ 
       $sql  .= $virgula." ht01_qtdparcpagamento = $this->ht01_qtdparcpagamento ";
       $virgula = ",";
       if(trim($this->ht01_qtdparcpagamento) == null ){ 
         $this->erro_sql = " Campo Quantidade Parcela Pagamento nao Informado.";
         $this->erro_campo = "ht01_qtdparcpagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_diapadraopagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_diapadraopagamento"])){ 
       $sql  .= $virgula." ht01_diapadraopagamento = $this->ht01_diapadraopagamento ";
       $virgula = ",";
       if(trim($this->ht01_diapadraopagamento) == null ){ 
         $this->erro_sql = " Campo Dia Padrão para Pagamento nao Informado.";
         $this->erro_campo = "ht01_diapadraopagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_receitapadraopagamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_receitapadraopagamento"])){ 
       $sql  .= $virgula." ht01_receitapadraopagamento = $this->ht01_receitapadraopagamento ";
       $virgula = ",";
       if(trim($this->ht01_receitapadraopagamento) == null ){ 
         $this->erro_sql = " Campo Receita Padrão para Pagamento nao Informado.";
         $this->erro_campo = "ht01_receitapadraopagamento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_exigevalcpf)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_exigevalcpf"])){ 
       $sql  .= $virgula." ht01_exigevalcpf = '$this->ht01_exigevalcpf' ";
       $virgula = ",";
       if(trim($this->ht01_exigevalcpf) == null ){ 
         $this->erro_sql = " Campo Exige CPF nao Informado.";
         $this->erro_campo = "ht01_exigevalcpf";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht01_qtdbenef)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_qtdbenef"])){ 
        if(trim($this->ht01_qtdbenef)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ht01_qtdbenef"])){ 
           $this->ht01_qtdbenef = "0" ; 
        } 
       $sql  .= $virgula." ht01_qtdbenef = $this->ht01_qtdbenef ";
       $virgula = ",";
     }
     if(trim($this->ht01_workflow)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht01_workflow"])){ 
       $sql  .= $virgula." ht01_workflow = $this->ht01_workflow ";
       $virgula = ",";
       if(trim($this->ht01_workflow) == null ){ 
         $this->erro_sql = " Campo Código Work Flow nao Informado.";
         $this->erro_campo = "ht01_workflow";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht01_sequencial!=null){
       $sql .= " ht01_sequencial = $this->ht01_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht01_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16934,'$this->ht01_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_sequencial"]) || $this->ht01_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2989,16934,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_sequencial'))."','$this->ht01_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_habitgrupoprograma"]) || $this->ht01_habitgrupoprograma != "")
           $resac = db_query("insert into db_acount values($acount,2989,16935,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_habitgrupoprograma'))."','$this->ht01_habitgrupoprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_descricao"]) || $this->ht01_descricao != "")
           $resac = db_query("insert into db_acount values($acount,2989,16936,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_descricao'))."','$this->ht01_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_obs"]) || $this->ht01_obs != "")
           $resac = db_query("insert into db_acount values($acount,2989,16937,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_obs'))."','$this->ht01_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_controlemultpartcandidato"]) || $this->ht01_controlemultpartcandidato != "")
           $resac = db_query("insert into db_acount values($acount,2989,16938,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_controlemultpartcandidato'))."','$this->ht01_controlemultpartcandidato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_controleqtd"]) || $this->ht01_controleqtd != "")
           $resac = db_query("insert into db_acount values($acount,2989,16939,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_controleqtd'))."','$this->ht01_controleqtd',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_validadeini"]) || $this->ht01_validadeini != "")
           $resac = db_query("insert into db_acount values($acount,2989,16940,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_validadeini'))."','$this->ht01_validadeini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_validadefim"]) || $this->ht01_validadefim != "")
           $resac = db_query("insert into db_acount values($acount,2989,16941,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_validadefim'))."','$this->ht01_validadefim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_descrcontrato"]) || $this->ht01_descrcontrato != "")
           $resac = db_query("insert into db_acount values($acount,2989,16944,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_descrcontrato'))."','$this->ht01_descrcontrato',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_lei"]) || $this->ht01_lei != "")
           $resac = db_query("insert into db_acount values($acount,2989,16945,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_lei'))."','$this->ht01_lei',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_exigeassconcedente"]) || $this->ht01_exigeassconcedente != "")
           $resac = db_query("insert into db_acount values($acount,2989,16946,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_exigeassconcedente'))."','$this->ht01_exigeassconcedente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_qtdparcpagamento"]) || $this->ht01_qtdparcpagamento != "")
           $resac = db_query("insert into db_acount values($acount,2989,16947,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_qtdparcpagamento'))."','$this->ht01_qtdparcpagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_diapadraopagamento"]) || $this->ht01_diapadraopagamento != "")
           $resac = db_query("insert into db_acount values($acount,2989,16948,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_diapadraopagamento'))."','$this->ht01_diapadraopagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_receitapadraopagamento"]) || $this->ht01_receitapadraopagamento != "")
           $resac = db_query("insert into db_acount values($acount,2989,16949,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_receitapadraopagamento'))."','$this->ht01_receitapadraopagamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_exigevalcpf"]) || $this->ht01_exigevalcpf != "")
           $resac = db_query("insert into db_acount values($acount,2989,16950,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_exigevalcpf'))."','$this->ht01_exigevalcpf',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_qtdbenef"]) || $this->ht01_qtdbenef != "")
           $resac = db_query("insert into db_acount values($acount,2989,17080,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_qtdbenef'))."','$this->ht01_qtdbenef',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht01_workflow"]) || $this->ht01_workflow != "")
           $resac = db_query("insert into db_acount values($acount,2989,17858,'".AddSlashes(pg_result($resaco,$conresaco,'ht01_workflow'))."','$this->ht01_workflow',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programa da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht01_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Programa da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht01_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht01_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16934,'$ht01_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2989,16934,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16935,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_habitgrupoprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16936,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16937,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16938,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_controlemultpartcandidato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16939,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_controleqtd'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16940,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_validadeini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16941,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_validadefim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16944,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_descrcontrato'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16945,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_lei'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16946,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_exigeassconcedente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16947,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_qtdparcpagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16948,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_diapadraopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16949,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_receitapadraopagamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,16950,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_exigevalcpf'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,17080,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_qtdbenef'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2989,17858,'','".AddSlashes(pg_result($resaco,$iresaco,'ht01_workflow'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitprograma
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht01_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht01_sequencial = $ht01_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Programa da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht01_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Programa da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht01_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht01_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitprograma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitprograma ";
     $sql .= "      inner join habitgrupoprograma      on habitgrupoprograma.ht03_sequencial         = habitprograma.ht01_habitgrupoprograma ";
     $sql .= "      inner join habittipogrupoprograma  on habittipogrupoprograma.ht02_sequencial     = habitgrupoprograma.ht03_habittipogrupoprograma ";
     $sql .= "      inner join workflow                on workflow.db112_sequencial                  = habitprograma.ht01_workflow ";
     $sql .= "      left  join workflowtipoproc        on workflowtipoproc.db116_workflow            = workflow.db112_sequencial ";
     $sql .= "      inner join tabrec                  on tabrec.k02_codigo                          = habitprograma.ht01_receitapadraopagamento ";
     $sql .= "      left  join habitprogramaconcedente on habitprogramaconcedente.ht19_habitprograma = habitprograma.ht01_sequencial ";
     $sql .= "      left  join cgm                     on cgm.z01_numcgm                             = habitprogramaconcedente.ht19_numcgm ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht01_sequencial!=null ){
         $sql2 .= " where habitprograma.ht01_sequencial = $ht01_sequencial "; 
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
   function sql_query_file ( $ht01_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitprograma ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht01_sequencial!=null ){
         $sql2 .= " where habitprograma.ht01_sequencial = $ht01_sequencial "; 
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