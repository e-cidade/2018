<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

//MODULO: arrecadacao
//CLASSE DA ENTIDADE regracompensacao
class cl_regracompensacao { 
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
   var $k155_sequencial = 0; 
   var $k155_tiporegracompensacao = 0; 
   var $k155_descricao = null; 
   var $k155_arretipoorigem = 0; 
   var $k155_arretipodestino = null; 
   var $k155_percmaxuso = 0; 
   var $k155_tempovalidade = null; 
   var $k155_automatica = 'f'; 
   var $k155_permitetransferencia = 'f'; 
   var $k155_instit = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 k155_sequencial = int4 = Código 
                 k155_tiporegracompensacao = int4 = Código Tipo Regra 
                 k155_descricao = varchar(50) = Descrição 
                 k155_arretipoorigem = int4 = Tipo de Débito Origem 
                 k155_arretipodestino = varchar(10) = Tipo de Débito Destino 
                 k155_percmaxuso = float4 = Percentual Máximo de Uso 
                 k155_tempovalidade = varchar(10) = Tempo Validade Crédito (dias) 
                 k155_automatica = bool = Crédito Automático 
                 k155_permitetransferencia = bool = Permite Transferência 
                 k155_instit = int4 = Código Instituição 
                 ";
   //funcao construtor da classe 
   function cl_regracompensacao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("regracompensacao"); 
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
       $this->k155_sequencial = ($this->k155_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k155_sequencial"]:$this->k155_sequencial);
       $this->k155_tiporegracompensacao = ($this->k155_tiporegracompensacao == ""?@$GLOBALS["HTTP_POST_VARS"]["k155_tiporegracompensacao"]:$this->k155_tiporegracompensacao);
       $this->k155_descricao = ($this->k155_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["k155_descricao"]:$this->k155_descricao);
       $this->k155_arretipoorigem = ($this->k155_arretipoorigem == ""?@$GLOBALS["HTTP_POST_VARS"]["k155_arretipoorigem"]:$this->k155_arretipoorigem);
       $this->k155_arretipodestino = ($this->k155_arretipodestino == ""?@$GLOBALS["HTTP_POST_VARS"]["k155_arretipodestino"]:$this->k155_arretipodestino);
       $this->k155_percmaxuso = ($this->k155_percmaxuso == ""?@$GLOBALS["HTTP_POST_VARS"]["k155_percmaxuso"]:$this->k155_percmaxuso);
       $this->k155_tempovalidade = ($this->k155_tempovalidade == ""?@$GLOBALS["HTTP_POST_VARS"]["k155_tempovalidade"]:$this->k155_tempovalidade);
       $this->k155_automatica = ($this->k155_automatica == "f"?@$GLOBALS["HTTP_POST_VARS"]["k155_automatica"]:$this->k155_automatica);
       $this->k155_permitetransferencia = ($this->k155_permitetransferencia == "f"?@$GLOBALS["HTTP_POST_VARS"]["k155_permitetransferencia"]:$this->k155_permitetransferencia);
       $this->k155_instit = ($this->k155_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["k155_instit"]:$this->k155_instit);
     }else{
       $this->k155_sequencial = ($this->k155_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["k155_sequencial"]:$this->k155_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($k155_sequencial){ 
      $this->atualizacampos();
     if($this->k155_tiporegracompensacao == null ){ 
       $this->erro_sql = " Campo Código Tipo Regra nao Informado.";
       $this->erro_campo = "k155_tiporegracompensacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k155_descricao == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "k155_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k155_arretipoorigem == null ){ 
       $this->erro_sql = " Campo Tipo de Débito Origem nao Informado.";
       $this->erro_campo = "k155_arretipoorigem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k155_percmaxuso == null ){ 
       $this->erro_sql = " Campo Percentual Máximo de Uso nao Informado.";
       $this->erro_campo = "k155_percmaxuso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k155_automatica == null ){ 
       $this->erro_sql = " Campo Crédito Automático nao Informado.";
       $this->erro_campo = "k155_automatica";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k155_permitetransferencia == null ){ 
       $this->erro_sql = " Campo Permite Transferência nao Informado.";
       $this->erro_campo = "k155_permitetransferencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->k155_instit == null ){ 
       $this->erro_sql = " Campo Código Instituição nao Informado.";
       $this->erro_campo = "k155_instit";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($k155_sequencial == "" || $k155_sequencial == null ){
       $result = db_query("select nextval('regracompensacao_k155_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: regracompensacao_k155_sequencial_seq do campo: k155_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->k155_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from regracompensacao_k155_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $k155_sequencial)){
         $this->erro_sql = " Campo k155_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->k155_sequencial = $k155_sequencial; 
       }
     }
     if(($this->k155_sequencial == null) || ($this->k155_sequencial == "") ){ 
       $this->erro_sql = " Campo k155_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into regracompensacao(
                                       k155_sequencial 
                                      ,k155_tiporegracompensacao 
                                      ,k155_descricao 
                                      ,k155_arretipoorigem 
                                      ,k155_arretipodestino 
                                      ,k155_percmaxuso 
                                      ,k155_tempovalidade 
                                      ,k155_automatica 
                                      ,k155_permitetransferencia 
                                      ,k155_instit 
                       )
                values (
                                $this->k155_sequencial 
                               ,$this->k155_tiporegracompensacao 
                               ,'$this->k155_descricao' 
                               ,$this->k155_arretipoorigem 
                               ,'$this->k155_arretipodestino' 
                               ,$this->k155_percmaxuso 
                               ,'$this->k155_tempovalidade' 
                               ,'$this->k155_automatica' 
                               ,'$this->k155_permitetransferencia' 
                               ,$this->k155_instit 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "regracompensacao ($this->k155_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "regracompensacao já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "regracompensacao ($this->k155_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k155_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->k155_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,19555,'$this->k155_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3476,19555,'','".AddSlashes(pg_result($resaco,0,'k155_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3476,19556,'','".AddSlashes(pg_result($resaco,0,'k155_tiporegracompensacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3476,19576,'','".AddSlashes(pg_result($resaco,0,'k155_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3476,19558,'','".AddSlashes(pg_result($resaco,0,'k155_arretipoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3476,19560,'','".AddSlashes(pg_result($resaco,0,'k155_arretipodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3476,19562,'','".AddSlashes(pg_result($resaco,0,'k155_percmaxuso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3476,19564,'','".AddSlashes(pg_result($resaco,0,'k155_tempovalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3476,19568,'','".AddSlashes(pg_result($resaco,0,'k155_automatica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3476,19575,'','".AddSlashes(pg_result($resaco,0,'k155_permitetransferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3476,19569,'','".AddSlashes(pg_result($resaco,0,'k155_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($k155_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update regracompensacao set ";
     $virgula = "";
     if(trim($this->k155_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k155_sequencial"])){ 
       $sql  .= $virgula." k155_sequencial = $this->k155_sequencial ";
       $virgula = ",";
       if(trim($this->k155_sequencial) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "k155_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k155_tiporegracompensacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k155_tiporegracompensacao"])){ 
       $sql  .= $virgula." k155_tiporegracompensacao = $this->k155_tiporegracompensacao ";
       $virgula = ",";
       if(trim($this->k155_tiporegracompensacao) == null ){ 
         $this->erro_sql = " Campo Código Tipo Regra nao Informado.";
         $this->erro_campo = "k155_tiporegracompensacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k155_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k155_descricao"])){ 
       $sql  .= $virgula." k155_descricao = '$this->k155_descricao' ";
       $virgula = ",";
       if(trim($this->k155_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "k155_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k155_arretipoorigem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k155_arretipoorigem"])){ 
       $sql  .= $virgula." k155_arretipoorigem = $this->k155_arretipoorigem ";
       $virgula = ",";
       if(trim($this->k155_arretipoorigem) == null ){ 
         $this->erro_sql = " Campo Tipo de Débito Origem nao Informado.";
         $this->erro_campo = "k155_arretipoorigem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k155_arretipodestino)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k155_arretipodestino"])){ 
       $sql  .= $virgula." k155_arretipodestino = '$this->k155_arretipodestino' ";
       $virgula = ",";
     }
     if(trim($this->k155_percmaxuso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k155_percmaxuso"])){ 
       $sql  .= $virgula." k155_percmaxuso = $this->k155_percmaxuso ";
       $virgula = ",";
       if(trim($this->k155_percmaxuso) == null ){ 
         $this->erro_sql = " Campo Percentual Máximo de Uso nao Informado.";
         $this->erro_campo = "k155_percmaxuso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k155_tempovalidade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k155_tempovalidade"])){ 
       $sql  .= $virgula." k155_tempovalidade = '$this->k155_tempovalidade' ";
       $virgula = ",";
     }
     if(trim($this->k155_automatica)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k155_automatica"])){ 
       $sql  .= $virgula." k155_automatica = '$this->k155_automatica' ";
       $virgula = ",";
       if(trim($this->k155_automatica) == null ){ 
         $this->erro_sql = " Campo Crédito Automático nao Informado.";
         $this->erro_campo = "k155_automatica";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k155_permitetransferencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k155_permitetransferencia"])){ 
       $sql  .= $virgula." k155_permitetransferencia = '$this->k155_permitetransferencia' ";
       $virgula = ",";
       if(trim($this->k155_permitetransferencia) == null ){ 
         $this->erro_sql = " Campo Permite Transferência nao Informado.";
         $this->erro_campo = "k155_permitetransferencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->k155_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["k155_instit"])){ 
       $sql  .= $virgula." k155_instit = $this->k155_instit ";
       $virgula = ",";
       if(trim($this->k155_instit) == null ){ 
         $this->erro_sql = " Campo Código Instituição nao Informado.";
         $this->erro_campo = "k155_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($k155_sequencial!=null){
       $sql .= " k155_sequencial = $this->k155_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->k155_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19555,'$this->k155_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k155_sequencial"]) || $this->k155_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3476,19555,'".AddSlashes(pg_result($resaco,$conresaco,'k155_sequencial'))."','$this->k155_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k155_tiporegracompensacao"]) || $this->k155_tiporegracompensacao != "")
           $resac = db_query("insert into db_acount values($acount,3476,19556,'".AddSlashes(pg_result($resaco,$conresaco,'k155_tiporegracompensacao'))."','$this->k155_tiporegracompensacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k155_descricao"]) || $this->k155_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3476,19576,'".AddSlashes(pg_result($resaco,$conresaco,'k155_descricao'))."','$this->k155_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k155_arretipoorigem"]) || $this->k155_arretipoorigem != "")
           $resac = db_query("insert into db_acount values($acount,3476,19558,'".AddSlashes(pg_result($resaco,$conresaco,'k155_arretipoorigem'))."','$this->k155_arretipoorigem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k155_arretipodestino"]) || $this->k155_arretipodestino != "")
           $resac = db_query("insert into db_acount values($acount,3476,19560,'".AddSlashes(pg_result($resaco,$conresaco,'k155_arretipodestino'))."','$this->k155_arretipodestino',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k155_percmaxuso"]) || $this->k155_percmaxuso != "")
           $resac = db_query("insert into db_acount values($acount,3476,19562,'".AddSlashes(pg_result($resaco,$conresaco,'k155_percmaxuso'))."','$this->k155_percmaxuso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k155_tempovalidade"]) || $this->k155_tempovalidade != "")
           $resac = db_query("insert into db_acount values($acount,3476,19564,'".AddSlashes(pg_result($resaco,$conresaco,'k155_tempovalidade'))."','$this->k155_tempovalidade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k155_automatica"]) || $this->k155_automatica != "")
           $resac = db_query("insert into db_acount values($acount,3476,19568,'".AddSlashes(pg_result($resaco,$conresaco,'k155_automatica'))."','$this->k155_automatica',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k155_permitetransferencia"]) || $this->k155_permitetransferencia != "")
           $resac = db_query("insert into db_acount values($acount,3476,19575,'".AddSlashes(pg_result($resaco,$conresaco,'k155_permitetransferencia'))."','$this->k155_permitetransferencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["k155_instit"]) || $this->k155_instit != "")
           $resac = db_query("insert into db_acount values($acount,3476,19569,'".AddSlashes(pg_result($resaco,$conresaco,'k155_instit'))."','$this->k155_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "regracompensacao nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->k155_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "regracompensacao nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->k155_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->k155_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($k155_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($k155_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,19555,'$k155_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3476,19555,'','".AddSlashes(pg_result($resaco,$iresaco,'k155_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3476,19556,'','".AddSlashes(pg_result($resaco,$iresaco,'k155_tiporegracompensacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3476,19576,'','".AddSlashes(pg_result($resaco,$iresaco,'k155_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3476,19558,'','".AddSlashes(pg_result($resaco,$iresaco,'k155_arretipoorigem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3476,19560,'','".AddSlashes(pg_result($resaco,$iresaco,'k155_arretipodestino'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3476,19562,'','".AddSlashes(pg_result($resaco,$iresaco,'k155_percmaxuso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3476,19564,'','".AddSlashes(pg_result($resaco,$iresaco,'k155_tempovalidade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3476,19568,'','".AddSlashes(pg_result($resaco,$iresaco,'k155_automatica'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3476,19575,'','".AddSlashes(pg_result($resaco,$iresaco,'k155_permitetransferencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3476,19569,'','".AddSlashes(pg_result($resaco,$iresaco,'k155_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from regracompensacao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($k155_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " k155_sequencial = $k155_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "regracompensacao nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$k155_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "regracompensacao nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$k155_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$k155_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:regracompensacao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $k155_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from regracompensacao                                                                                                              ";
     $sql .= "      inner join arretipo as arretipoorigem    on  arretipoorigem.k00_tipo              = regracompensacao.k155_arretipoorigem      ";
     $sql .= "       left join arretipo as arretipodestino   on  arretipodestino.k00_tipo::varchar    = regracompensacao.k155_arretipodestino     ";
     $sql .= "      inner join db_config                     on  db_config.codigo                     = regracompensacao.k155_instit              ";
     $sql .= "      inner join tiporegracompensacao          on  tiporegracompensacao.k154_sequencial = regracompensacao.k155_tiporegracompensacao";
     $sql .= "      inner join db_config as db_configorigem  on  db_configorigem.codigo               = arretipoorigem.k00_instit                 ";
     $sql .= "      inner join cadtipo as cadtipoorigem      on  cadtipoorigem.k03_tipo               = arretipoorigem.k03_tipo                   ";     
     $sql .= "       left join db_config as db_configdestino on  db_configdestino.codigo              = arretipodestino.k00_instit                ";
     $sql .= "       left join cadtipo as cadtipodestino     on  cadtipodestino.k03_tipo              = arretipodestino.k03_tipo                  ";     
     $sql .= "      inner join cgm                           on  cgm.z01_numcgm                       = db_config.numcgm                          ";
     $sql .= "      inner join db_tipoinstit                 on  db_tipoinstit.db21_codtipo           = db_config.db21_tipoinstit                 ";
     $sql2 = "";
     if($dbwhere==""){
       if($k155_sequencial!=null ){
         $sql2 .= " where regracompensacao.k155_sequencial = $k155_sequencial "; 
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
   function sql_query_file ( $k155_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from regracompensacao ";
     $sql2 = "";
     if($dbwhere==""){
       if($k155_sequencial!=null ){
         $sql2 .= " where regracompensacao.k155_sequencial = $k155_sequencial "; 
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