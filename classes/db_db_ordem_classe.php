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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_ordem
class cl_db_ordem { 
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
   var $codordem = null; 
   var $dataordem_dia = null; 
   var $dataordem_mes = null; 
   var $dataordem_ano = null; 
   var $dataordem = null; 
   var $descricao = null; 
   var $id_usuario = 0; 
   var $usureceb = 0; 
   var $coddepto = 0; 
   var $dataprev_dia = null; 
   var $dataprev_mes = null; 
   var $dataprev_ano = null; 
   var $dataprev = null; 
   var $alertado = 'f'; 
   var $codorigem = 0; 
   var $codcli = 0; 
   var $prioridade = 0; 
   var $status = 0; 
   var $dtrecebe_dia = null; 
   var $dtrecebe_mes = null; 
   var $dtrecebe_ano = null; 
   var $dtrecebe = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 codordem = varchar(15) = Código 
                 dataordem = date = Data 
                 descricao = text = Descrição 
                 id_usuario = int4 = Cod. Usuário 
                 usureceb = int4 = Destinatário 
                 coddepto = int4 = Depart. 
                 dataprev = date = Data Prevista 
                 alertado = bool = Alertado 
                 codorigem = int4 = origem da ordem 
                 codcli = int4 = Código do cliente 
                 prioridade = int4 = Nivel de prioridade 
                 status = int4 = Status 
                 dtrecebe = date = Data do recebimento 
                 ";
   //funcao construtor da classe 
   function cl_db_ordem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_ordem"); 
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
       $this->codordem = ($this->codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["codordem"]:$this->codordem);
       if($this->dataordem == ""){
         $this->dataordem_dia = ($this->dataordem_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dataordem_dia"]:$this->dataordem_dia);
         $this->dataordem_mes = ($this->dataordem_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dataordem_mes"]:$this->dataordem_mes);
         $this->dataordem_ano = ($this->dataordem_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dataordem_ano"]:$this->dataordem_ano);
         if($this->dataordem_dia != ""){
            $this->dataordem = $this->dataordem_ano."-".$this->dataordem_mes."-".$this->dataordem_dia;
         }
       }
       $this->descricao = ($this->descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["descricao"]:$this->descricao);
       $this->id_usuario = ($this->id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["id_usuario"]:$this->id_usuario);
       $this->usureceb = ($this->usureceb == ""?@$GLOBALS["HTTP_POST_VARS"]["usureceb"]:$this->usureceb);
       $this->coddepto = ($this->coddepto == ""?@$GLOBALS["HTTP_POST_VARS"]["coddepto"]:$this->coddepto);
       if($this->dataprev == ""){
         $this->dataprev_dia = ($this->dataprev_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dataprev_dia"]:$this->dataprev_dia);
         $this->dataprev_mes = ($this->dataprev_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dataprev_mes"]:$this->dataprev_mes);
         $this->dataprev_ano = ($this->dataprev_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dataprev_ano"]:$this->dataprev_ano);
         if($this->dataprev_dia != ""){
            $this->dataprev = $this->dataprev_ano."-".$this->dataprev_mes."-".$this->dataprev_dia;
         }
       }
       $this->alertado = ($this->alertado == "f"?@$GLOBALS["HTTP_POST_VARS"]["alertado"]:$this->alertado);
       $this->codorigem = ($this->codorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["codorigem"]:$this->codorigem);
       $this->codcli = ($this->codcli == ""?@$GLOBALS["HTTP_POST_VARS"]["codcli"]:$this->codcli);
       $this->prioridade = ($this->prioridade == ""?@$GLOBALS["HTTP_POST_VARS"]["prioridade"]:$this->prioridade);
       $this->status = ($this->status == ""?@$GLOBALS["HTTP_POST_VARS"]["status"]:$this->status);
       if($this->dtrecebe == ""){
         $this->dtrecebe_dia = ($this->dtrecebe_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["dtrecebe_dia"]:$this->dtrecebe_dia);
         $this->dtrecebe_mes = ($this->dtrecebe_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["dtrecebe_mes"]:$this->dtrecebe_mes);
         $this->dtrecebe_ano = ($this->dtrecebe_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["dtrecebe_ano"]:$this->dtrecebe_ano);
         if($this->dtrecebe_dia != ""){
            $this->dtrecebe = $this->dtrecebe_ano."-".$this->dtrecebe_mes."-".$this->dtrecebe_dia;
         }
       }
     }else{
       $this->codordem = ($this->codordem == ""?@$GLOBALS["HTTP_POST_VARS"]["codordem"]:$this->codordem);
     }
   }
   // funcao para inclusao
   function incluir ($codordem){ 
      $this->atualizacampos();
     if($this->dataordem == null ){ 
       $this->erro_sql = " Campo Data nao Informado.";
       $this->erro_campo = "dataordem_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->id_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->usureceb == null ){ 
       $this->erro_sql = " Campo Destinatário nao Informado.";
       $this->erro_campo = "usureceb";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->coddepto == null ){ 
       $this->erro_sql = " Campo Depart. nao Informado.";
       $this->erro_campo = "coddepto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dataprev == null ){ 
       $this->dataprev = "null";
     }
     if($this->alertado == null ){ 
       $this->erro_sql = " Campo Alertado nao Informado.";
       $this->erro_campo = "alertado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codorigem == null ){ 
       $this->erro_sql = " Campo origem da ordem nao Informado.";
       $this->erro_campo = "codorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->codcli == null ){ 
       $this->erro_sql = " Campo Código do cliente nao Informado.";
       $this->erro_campo = "codcli";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->prioridade == null ){ 
       $this->erro_sql = " Campo Nivel de prioridade nao Informado.";
       $this->erro_campo = "prioridade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->status == null ){ 
       $this->erro_sql = " Campo Status nao Informado.";
       $this->erro_campo = "status";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->dtrecebe == null ){ 
       $this->erro_sql = " Campo Data do recebimento nao Informado.";
       $this->erro_campo = "dtrecebe_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($codordem == "" || $codordem == null ){
       $result = db_query("select nextval('db_ordem_codordem_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_ordem_codordem_seq do campo: codordem"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->codordem = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_ordem_codordem_seq");
       if(($result != false) && (pg_result($result,0,0) < $codordem)){
         $this->erro_sql = " Campo codordem maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->codordem = $codordem; 
       }
     }
     if(($this->codordem == null) || ($this->codordem == "") ){ 
       $this->erro_sql = " Campo codordem nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_ordem(
                                       codordem 
                                      ,dataordem 
                                      ,descricao 
                                      ,id_usuario 
                                      ,usureceb 
                                      ,coddepto 
                                      ,dataprev 
                                      ,alertado 
                                      ,codorigem 
                                      ,codcli 
                                      ,prioridade 
                                      ,status 
                                      ,dtrecebe 
                       )
                values (
                                '$this->codordem' 
                               ,".($this->dataordem == "null" || $this->dataordem == ""?"null":"'".$this->dataordem."'")." 
                               ,'$this->descricao' 
                               ,$this->id_usuario 
                               ,$this->usureceb 
                               ,$this->coddepto 
                               ,".($this->dataprev == "null" || $this->dataprev == ""?"null":"'".$this->dataprev."'")." 
                               ,'$this->alertado' 
                               ,$this->codorigem 
                               ,$this->codcli 
                               ,$this->prioridade 
                               ,$this->status 
                               ,".($this->dtrecebe == "null" || $this->dtrecebe == ""?"null":"'".$this->dtrecebe."'")." 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Ordem ($this->codordem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Ordem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Ordem ($this->codordem) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codordem;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->codordem));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1003,'$this->codordem','I')");
       $resac = db_query("insert into db_acount values($acount,169,1003,'','".AddSlashes(pg_result($resaco,0,'codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,1004,'','".AddSlashes(pg_result($resaco,0,'dataordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,750,'','".AddSlashes(pg_result($resaco,0,'descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,568,'','".AddSlashes(pg_result($resaco,0,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,1006,'','".AddSlashes(pg_result($resaco,0,'usureceb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,814,'','".AddSlashes(pg_result($resaco,0,'coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,1008,'','".AddSlashes(pg_result($resaco,0,'dataprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,1009,'','".AddSlashes(pg_result($resaco,0,'alertado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,7571,'','".AddSlashes(pg_result($resaco,0,'codorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,6734,'','".AddSlashes(pg_result($resaco,0,'codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,6735,'','".AddSlashes(pg_result($resaco,0,'prioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,7856,'','".AddSlashes(pg_result($resaco,0,'status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,169,7857,'','".AddSlashes(pg_result($resaco,0,'dtrecebe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($codordem=null) { 
      $this->atualizacampos();
     $sql = " update db_ordem set ";
     $virgula = "";
     if(trim($this->codordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codordem"])){ 
       $sql  .= $virgula." codordem = '$this->codordem' ";
       $virgula = ",";
       if(trim($this->codordem) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "codordem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dataordem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dataordem_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dataordem_dia"] !="") ){ 
       $sql  .= $virgula." dataordem = '$this->dataordem' ";
       $virgula = ",";
       if(trim($this->dataordem) == null ){ 
         $this->erro_sql = " Campo Data nao Informado.";
         $this->erro_campo = "dataordem_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dataordem_dia"])){ 
         $sql  .= $virgula." dataordem = null ";
         $virgula = ",";
         if(trim($this->dataordem) == null ){ 
           $this->erro_sql = " Campo Data nao Informado.";
           $this->erro_campo = "dataordem_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["descricao"])){ 
       $sql  .= $virgula." descricao = '$this->descricao' ";
       $virgula = ",";
       if(trim($this->descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"])){ 
       $sql  .= $virgula." id_usuario = $this->id_usuario ";
       $virgula = ",";
       if(trim($this->id_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->usureceb)!="" || isset($GLOBALS["HTTP_POST_VARS"]["usureceb"])){ 
       $sql  .= $virgula." usureceb = $this->usureceb ";
       $virgula = ",";
       if(trim($this->usureceb) == null ){ 
         $this->erro_sql = " Campo Destinatário nao Informado.";
         $this->erro_campo = "usureceb";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->coddepto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["coddepto"])){ 
       $sql  .= $virgula." coddepto = $this->coddepto ";
       $virgula = ",";
       if(trim($this->coddepto) == null ){ 
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "coddepto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dataprev)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dataprev_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dataprev_dia"] !="") ){ 
       $sql  .= $virgula." dataprev = '$this->dataprev' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dataprev_dia"])){ 
         $sql  .= $virgula." dataprev = null ";
         $virgula = ",";
       }
     }
     if(trim($this->alertado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["alertado"])){ 
       $sql  .= $virgula." alertado = '$this->alertado' ";
       $virgula = ",";
       if(trim($this->alertado) == null ){ 
         $this->erro_sql = " Campo Alertado nao Informado.";
         $this->erro_campo = "alertado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codorigem"])){ 
       $sql  .= $virgula." codorigem = $this->codorigem ";
       $virgula = ",";
       if(trim($this->codorigem) == null ){ 
         $this->erro_sql = " Campo origem da ordem nao Informado.";
         $this->erro_campo = "codorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->codcli)!="" || isset($GLOBALS["HTTP_POST_VARS"]["codcli"])){ 
       $sql  .= $virgula." codcli = $this->codcli ";
       $virgula = ",";
       if(trim($this->codcli) == null ){ 
         $this->erro_sql = " Campo Código do cliente nao Informado.";
         $this->erro_campo = "codcli";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->prioridade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["prioridade"])){ 
       $sql  .= $virgula." prioridade = $this->prioridade ";
       $virgula = ",";
       if(trim($this->prioridade) == null ){ 
         $this->erro_sql = " Campo Nivel de prioridade nao Informado.";
         $this->erro_campo = "prioridade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->status)!="" || isset($GLOBALS["HTTP_POST_VARS"]["status"])){ 
       $sql  .= $virgula." status = $this->status ";
       $virgula = ",";
       if(trim($this->status) == null ){ 
         $this->erro_sql = " Campo Status nao Informado.";
         $this->erro_campo = "status";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->dtrecebe)!="" || isset($GLOBALS["HTTP_POST_VARS"]["dtrecebe_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["dtrecebe_dia"] !="") ){ 
       $sql  .= $virgula." dtrecebe = '$this->dtrecebe' ";
       $virgula = ",";
       if(trim($this->dtrecebe) == null ){ 
         $this->erro_sql = " Campo Data do recebimento nao Informado.";
         $this->erro_campo = "dtrecebe_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["dtrecebe_dia"])){ 
         $sql  .= $virgula." dtrecebe = null ";
         $virgula = ",";
         if(trim($this->dtrecebe) == null ){ 
           $this->erro_sql = " Campo Data do recebimento nao Informado.";
           $this->erro_campo = "dtrecebe_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     $sql .= " where ";
     if($codordem!=null){
       $sql .= " codordem = '$this->codordem'";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->codordem));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1003,'$this->codordem','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codordem"]))
           $resac = db_query("insert into db_acount values($acount,169,1003,'".AddSlashes(pg_result($resaco,$conresaco,'codordem'))."','$this->codordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dataordem"]))
           $resac = db_query("insert into db_acount values($acount,169,1004,'".AddSlashes(pg_result($resaco,$conresaco,'dataordem'))."','$this->dataordem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["descricao"]))
           $resac = db_query("insert into db_acount values($acount,169,750,'".AddSlashes(pg_result($resaco,$conresaco,'descricao'))."','$this->descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["id_usuario"]))
           $resac = db_query("insert into db_acount values($acount,169,568,'".AddSlashes(pg_result($resaco,$conresaco,'id_usuario'))."','$this->id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["usureceb"]))
           $resac = db_query("insert into db_acount values($acount,169,1006,'".AddSlashes(pg_result($resaco,$conresaco,'usureceb'))."','$this->usureceb',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["coddepto"]))
           $resac = db_query("insert into db_acount values($acount,169,814,'".AddSlashes(pg_result($resaco,$conresaco,'coddepto'))."','$this->coddepto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dataprev"]))
           $resac = db_query("insert into db_acount values($acount,169,1008,'".AddSlashes(pg_result($resaco,$conresaco,'dataprev'))."','$this->dataprev',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["alertado"]))
           $resac = db_query("insert into db_acount values($acount,169,1009,'".AddSlashes(pg_result($resaco,$conresaco,'alertado'))."','$this->alertado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codorigem"]))
           $resac = db_query("insert into db_acount values($acount,169,7571,'".AddSlashes(pg_result($resaco,$conresaco,'codorigem'))."','$this->codorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["codcli"]))
           $resac = db_query("insert into db_acount values($acount,169,6734,'".AddSlashes(pg_result($resaco,$conresaco,'codcli'))."','$this->codcli',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["prioridade"]))
           $resac = db_query("insert into db_acount values($acount,169,6735,'".AddSlashes(pg_result($resaco,$conresaco,'prioridade'))."','$this->prioridade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["status"]))
           $resac = db_query("insert into db_acount values($acount,169,7856,'".AddSlashes(pg_result($resaco,$conresaco,'status'))."','$this->status',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["dtrecebe"]))
           $resac = db_query("insert into db_acount values($acount,169,7857,'".AddSlashes(pg_result($resaco,$conresaco,'dtrecebe'))."','$this->dtrecebe',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->codordem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($codordem=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($codordem));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1003,'$codordem','E')");
         $resac = db_query("insert into db_acount values($acount,169,1003,'','".AddSlashes(pg_result($resaco,$iresaco,'codordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,1004,'','".AddSlashes(pg_result($resaco,$iresaco,'dataordem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,750,'','".AddSlashes(pg_result($resaco,$iresaco,'descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,568,'','".AddSlashes(pg_result($resaco,$iresaco,'id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,1006,'','".AddSlashes(pg_result($resaco,$iresaco,'usureceb'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,814,'','".AddSlashes(pg_result($resaco,$iresaco,'coddepto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,1008,'','".AddSlashes(pg_result($resaco,$iresaco,'dataprev'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,1009,'','".AddSlashes(pg_result($resaco,$iresaco,'alertado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,7571,'','".AddSlashes(pg_result($resaco,$iresaco,'codorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,6734,'','".AddSlashes(pg_result($resaco,$iresaco,'codcli'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,6735,'','".AddSlashes(pg_result($resaco,$iresaco,'prioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,7856,'','".AddSlashes(pg_result($resaco,$iresaco,'status'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,169,7857,'','".AddSlashes(pg_result($resaco,$iresaco,'dtrecebe'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_ordem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($codordem != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " codordem = '$codordem' ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Ordem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$codordem;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Ordem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$codordem;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$codordem;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_ordem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $codordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_ordem ";
     $sql2 = "";
     if($dbwhere==""){
       if($codordem!=null ){
         $sql2 .= " where db_ordem.codordem = '$codordem' "; 
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
   function sql_query_file ( $codordem=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_ordem ";
     $sql2 = "";
     if($dbwhere==""){
       if($codordem!=null ){
         $sql2 .= " where db_ordem.codordem = '$codordem' "; 
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
   function sql_query_nome ( $codordem=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from db_ordem ";
     $sql .= "   inner join db_usuarios as a on a.id_usuario = db_ordem.id_usuario ";
     $sql .= "   inner join db_usuarios as b on b.id_usuario = db_ordem.usureceb ";
     $sql .= "   inner join db_depart        on db_depart.coddepto = db_ordem.coddepto ";
     $sql2 = "";
     if($dbwhere==""){
       if($codordem!=null ){
         $sql2 .= " where db_ordem.codordem = $codordem ";
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