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

//MODULO: atendimento
//CLASSE DA ENTIDADE tarefa
class cl_tarefa { 
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
   var $at40_sequencial = 0; 
   var $at40_responsavel = 0; 
   var $at40_descr = null; 
   var $at40_diaini_dia = null; 
   var $at40_diaini_mes = null; 
   var $at40_diaini_ano = null; 
   var $at40_diaini = null; 
   var $at40_diafim_dia = null; 
   var $at40_diafim_mes = null; 
   var $at40_diafim_ano = null; 
   var $at40_diafim = null; 
   var $at40_previsao = 0; 
   var $at40_tipoprevisao = null; 
   var $at40_horainidia = null; 
   var $at40_horafim = null; 
   var $at40_progresso = 0; 
   var $at40_prioridade = 0; 
   var $at40_obs = null; 
   var $at40_autorizada = 'f'; 
   var $at40_tipo = 0; 
   var $at40_ativo = 'f'; 
   var $at40_urgente = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at40_sequencial = int4 = Codigo da Tarefa 
                 at40_responsavel = int4 = Responsável 
                 at40_descr = text = Resumo da tarefa 
                 at40_diaini = date = Data de inicio 
                 at40_diafim = date = Dia final previsto 
                 at40_previsao = int4 = Previsao 
                 at40_tipoprevisao = char(1) = Tipo de previsao 
                 at40_horainidia = char(5) = Hora inicial 
                 at40_horafim = char(5) = Hora final 
                 at40_progresso = float8 = Progresso da tarefa 
                 at40_prioridade = int4 = Prioridade 
                 at40_obs = text = Obs. 
                 at40_autorizada = bool = Autorização 
                 at40_tipo = int4 = Disponibilidade da tarefa 
                 at40_ativo = bool = Ativa 
                 at40_urgente = int4 = Urgente 
                 ";
   //funcao construtor da classe 
   function cl_tarefa() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefa"); 
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
       $this->at40_sequencial = ($this->at40_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_sequencial"]:$this->at40_sequencial);
       $this->at40_responsavel = ($this->at40_responsavel == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_responsavel"]:$this->at40_responsavel);
       $this->at40_descr = ($this->at40_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_descr"]:$this->at40_descr);
       if($this->at40_diaini == ""){
         $this->at40_diaini_dia = ($this->at40_diaini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_diaini_dia"]:$this->at40_diaini_dia);
         $this->at40_diaini_mes = ($this->at40_diaini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_diaini_mes"]:$this->at40_diaini_mes);
         $this->at40_diaini_ano = ($this->at40_diaini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_diaini_ano"]:$this->at40_diaini_ano);
         if($this->at40_diaini_dia != ""){
            $this->at40_diaini = $this->at40_diaini_ano."-".$this->at40_diaini_mes."-".$this->at40_diaini_dia;
         }
       }
       if($this->at40_diafim == ""){
         $this->at40_diafim_dia = ($this->at40_diafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_diafim_dia"]:$this->at40_diafim_dia);
         $this->at40_diafim_mes = ($this->at40_diafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_diafim_mes"]:$this->at40_diafim_mes);
         $this->at40_diafim_ano = ($this->at40_diafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_diafim_ano"]:$this->at40_diafim_ano);
         if($this->at40_diafim_dia != ""){
            $this->at40_diafim = $this->at40_diafim_ano."-".$this->at40_diafim_mes."-".$this->at40_diafim_dia;
         }
       }
       $this->at40_previsao = ($this->at40_previsao == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_previsao"]:$this->at40_previsao);
       $this->at40_tipoprevisao = ($this->at40_tipoprevisao == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_tipoprevisao"]:$this->at40_tipoprevisao);
       $this->at40_horainidia = ($this->at40_horainidia == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_horainidia"]:$this->at40_horainidia);
       $this->at40_horafim = ($this->at40_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_horafim"]:$this->at40_horafim);
       $this->at40_progresso = ($this->at40_progresso == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_progresso"]:$this->at40_progresso);
       $this->at40_prioridade = ($this->at40_prioridade == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_prioridade"]:$this->at40_prioridade);
       $this->at40_obs = ($this->at40_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_obs"]:$this->at40_obs);
       $this->at40_autorizada = ($this->at40_autorizada == "f"?@$GLOBALS["HTTP_POST_VARS"]["at40_autorizada"]:$this->at40_autorizada);
       $this->at40_tipo = ($this->at40_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_tipo"]:$this->at40_tipo);
       $this->at40_ativo = ($this->at40_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["at40_ativo"]:$this->at40_ativo);
       $this->at40_urgente = ($this->at40_urgente == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_urgente"]:$this->at40_urgente);
     }else{
       $this->at40_sequencial = ($this->at40_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at40_sequencial"]:$this->at40_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at40_sequencial){ 
      $this->atualizacampos();
     if($this->at40_responsavel == null ){ 
       $this->erro_sql = " Campo Responsável nao Informado.";
       $this->erro_campo = "at40_responsavel";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at40_descr == null ){ 
       $this->erro_sql = " Campo Resumo da tarefa nao Informado.";
       $this->erro_campo = "at40_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at40_diaini == null ){ 
       $this->erro_sql = " Campo Data de inicio nao Informado.";
       $this->erro_campo = "at40_diaini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at40_diafim == null ){ 
       $this->at40_diafim = "null";
     }
     if($this->at40_previsao == null ){ 
       $this->at40_previsao = "0";
     }
     if($this->at40_tipoprevisao == null ){ 
       $this->erro_sql = " Campo Tipo de previsao nao Informado.";
       $this->erro_campo = "at40_tipoprevisao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at40_progresso == null ){ 
       $this->erro_sql = " Campo Progresso da tarefa nao Informado.";
       $this->erro_campo = "at40_progresso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at40_prioridade == null ){ 
       $this->erro_sql = " Campo Prioridade nao Informado.";
       $this->erro_campo = "at40_prioridade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at40_autorizada == null ){ 
       $this->at40_autorizada = "f";
     }
     if($this->at40_tipo == null ){ 
       $this->at40_tipo = "1";
     }
     if($this->at40_ativo == null ){ 
       $this->at40_ativo = "t";
     }
     if($this->at40_urgente == null ){ 
       $this->at40_urgente = "0";
     }
     if($at40_sequencial == "" || $at40_sequencial == null ){
       $result = db_query("select nextval('tarefa_at40_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefa_at40_sequencial_seq do campo: at40_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at40_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefa_at40_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at40_sequencial)){
         $this->erro_sql = " Campo at40_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at40_sequencial = $at40_sequencial; 
       }
     }
     if(($this->at40_sequencial == null) || ($this->at40_sequencial == "") ){ 
       $this->erro_sql = " Campo at40_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefa(
                                       at40_sequencial 
                                      ,at40_responsavel 
                                      ,at40_descr 
                                      ,at40_diaini 
                                      ,at40_diafim 
                                      ,at40_previsao 
                                      ,at40_tipoprevisao 
                                      ,at40_horainidia 
                                      ,at40_horafim 
                                      ,at40_progresso 
                                      ,at40_prioridade 
                                      ,at40_obs 
                                      ,at40_autorizada 
                                      ,at40_tipo 
                                      ,at40_ativo 
                                      ,at40_urgente 
                       )
                values (
                                $this->at40_sequencial 
                               ,$this->at40_responsavel 
                               ,'$this->at40_descr' 
                               ,".($this->at40_diaini == "null" || $this->at40_diaini == ""?"null":"'".$this->at40_diaini."'")." 
                               ,".($this->at40_diafim == "null" || $this->at40_diafim == ""?"null":"'".$this->at40_diafim."'")." 
                               ,$this->at40_previsao 
                               ,'$this->at40_tipoprevisao' 
                               ,'$this->at40_horainidia' 
                               ,'$this->at40_horafim' 
                               ,$this->at40_progresso 
                               ,$this->at40_prioridade 
                               ,'$this->at40_obs' 
                               ,'$this->at40_autorizada' 
                               ,$this->at40_tipo 
                               ,'$this->at40_ativo' 
                               ,$this->at40_urgente 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tarefas ($this->at40_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tarefas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tarefas ($this->at40_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at40_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at40_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8076,'$this->at40_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1365,8076,'','".AddSlashes(pg_result($resaco,0,'at40_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8078,'','".AddSlashes(pg_result($resaco,0,'at40_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8079,'','".AddSlashes(pg_result($resaco,0,'at40_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8080,'','".AddSlashes(pg_result($resaco,0,'at40_diaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8081,'','".AddSlashes(pg_result($resaco,0,'at40_diafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8082,'','".AddSlashes(pg_result($resaco,0,'at40_previsao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8083,'','".AddSlashes(pg_result($resaco,0,'at40_tipoprevisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8084,'','".AddSlashes(pg_result($resaco,0,'at40_horainidia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8085,'','".AddSlashes(pg_result($resaco,0,'at40_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8086,'','".AddSlashes(pg_result($resaco,0,'at40_progresso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8224,'','".AddSlashes(pg_result($resaco,0,'at40_prioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8673,'','".AddSlashes(pg_result($resaco,0,'at40_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8831,'','".AddSlashes(pg_result($resaco,0,'at40_autorizada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8833,'','".AddSlashes(pg_result($resaco,0,'at40_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,8832,'','".AddSlashes(pg_result($resaco,0,'at40_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1365,9147,'','".AddSlashes(pg_result($resaco,0,'at40_urgente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at40_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tarefa set ";
     $virgula = "";
     if(trim($this->at40_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_sequencial"])){ 
       $sql  .= $virgula." at40_sequencial = $this->at40_sequencial ";
       $virgula = ",";
       if(trim($this->at40_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo da Tarefa nao Informado.";
         $this->erro_campo = "at40_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at40_responsavel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_responsavel"])){ 
       $sql  .= $virgula." at40_responsavel = $this->at40_responsavel ";
       $virgula = ",";
       if(trim($this->at40_responsavel) == null ){ 
         $this->erro_sql = " Campo Responsável nao Informado.";
         $this->erro_campo = "at40_responsavel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at40_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_descr"])){ 
       $sql  .= $virgula." at40_descr = '$this->at40_descr' ";
       $virgula = ",";
       if(trim($this->at40_descr) == null ){ 
         $this->erro_sql = " Campo Resumo da tarefa nao Informado.";
         $this->erro_campo = "at40_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at40_diaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_diaini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at40_diaini_dia"] !="") ){ 
       $sql  .= $virgula." at40_diaini = '$this->at40_diaini' ";
       $virgula = ",";
       if(trim($this->at40_diaini) == null ){ 
         $this->erro_sql = " Campo Data de inicio nao Informado.";
         $this->erro_campo = "at40_diaini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at40_diaini_dia"])){ 
         $sql  .= $virgula." at40_diaini = null ";
         $virgula = ",";
         if(trim($this->at40_diaini) == null ){ 
           $this->erro_sql = " Campo Data de inicio nao Informado.";
           $this->erro_campo = "at40_diaini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at40_diafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_diafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at40_diafim_dia"] !="") ){ 
       $sql  .= $virgula." at40_diafim = '$this->at40_diafim' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at40_diafim_dia"])){ 
         $sql  .= $virgula." at40_diafim = null ";
         $virgula = ",";
       }
     }
     if(trim($this->at40_previsao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_previsao"])){ 
        if(trim($this->at40_previsao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["at40_previsao"])){ 
           $this->at40_previsao = "0" ; 
        } 
       $sql  .= $virgula." at40_previsao = $this->at40_previsao ";
       $virgula = ",";
     }
     if(trim($this->at40_tipoprevisao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_tipoprevisao"])){ 
       $sql  .= $virgula." at40_tipoprevisao = '$this->at40_tipoprevisao' ";
       $virgula = ",";
       if(trim($this->at40_tipoprevisao) == null ){ 
         $this->erro_sql = " Campo Tipo de previsao nao Informado.";
         $this->erro_campo = "at40_tipoprevisao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at40_horainidia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_horainidia"])){ 
       $sql  .= $virgula." at40_horainidia = '$this->at40_horainidia' ";
       $virgula = ",";
     }
     if(trim($this->at40_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_horafim"])){ 
       $sql  .= $virgula." at40_horafim = '$this->at40_horafim' ";
       $virgula = ",";
     }
     if(trim($this->at40_progresso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_progresso"])){ 
       $sql  .= $virgula." at40_progresso = $this->at40_progresso ";
       $virgula = ",";
       if(trim($this->at40_progresso) == null ){ 
         $this->erro_sql = " Campo Progresso da tarefa nao Informado.";
         $this->erro_campo = "at40_progresso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at40_prioridade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_prioridade"])){ 
       $sql  .= $virgula." at40_prioridade = $this->at40_prioridade ";
       $virgula = ",";
       if(trim($this->at40_prioridade) == null ){ 
         $this->erro_sql = " Campo Prioridade nao Informado.";
         $this->erro_campo = "at40_prioridade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at40_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_obs"])){ 
       $sql  .= $virgula." at40_obs = '$this->at40_obs' ";
       $virgula = ",";
     }
     if(trim($this->at40_autorizada)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_autorizada"])){ 
       $sql  .= $virgula." at40_autorizada = '$this->at40_autorizada' ";
       $virgula = ",";
     }
     if(trim($this->at40_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_tipo"])){ 
        if(trim($this->at40_tipo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["at40_tipo"])){ 
           $this->at40_tipo = "0" ; 
        } 
       $sql  .= $virgula." at40_tipo = $this->at40_tipo ";
       $virgula = ",";
     }
     if(trim($this->at40_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_ativo"])){ 
       $sql  .= $virgula." at40_ativo = '$this->at40_ativo' ";
       $virgula = ",";
     }
     if(trim($this->at40_urgente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at40_urgente"])){ 
        if(trim($this->at40_urgente)=="" && isset($GLOBALS["HTTP_POST_VARS"]["at40_urgente"])){ 
           $this->at40_urgente = "0" ; 
        } 
       $sql  .= $virgula." at40_urgente = $this->at40_urgente ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($at40_sequencial!=null){
       $sql .= " at40_sequencial = $this->at40_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at40_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8076,'$this->at40_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1365,8076,'".AddSlashes(pg_result($resaco,$conresaco,'at40_sequencial'))."','$this->at40_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_responsavel"]))
           $resac = db_query("insert into db_acount values($acount,1365,8078,'".AddSlashes(pg_result($resaco,$conresaco,'at40_responsavel'))."','$this->at40_responsavel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_descr"]))
           $resac = db_query("insert into db_acount values($acount,1365,8079,'".AddSlashes(pg_result($resaco,$conresaco,'at40_descr'))."','$this->at40_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_diaini"]))
           $resac = db_query("insert into db_acount values($acount,1365,8080,'".AddSlashes(pg_result($resaco,$conresaco,'at40_diaini'))."','$this->at40_diaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_diafim"]))
           $resac = db_query("insert into db_acount values($acount,1365,8081,'".AddSlashes(pg_result($resaco,$conresaco,'at40_diafim'))."','$this->at40_diafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_previsao"]))
           $resac = db_query("insert into db_acount values($acount,1365,8082,'".AddSlashes(pg_result($resaco,$conresaco,'at40_previsao'))."','$this->at40_previsao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_tipoprevisao"]))
           $resac = db_query("insert into db_acount values($acount,1365,8083,'".AddSlashes(pg_result($resaco,$conresaco,'at40_tipoprevisao'))."','$this->at40_tipoprevisao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_horainidia"]))
           $resac = db_query("insert into db_acount values($acount,1365,8084,'".AddSlashes(pg_result($resaco,$conresaco,'at40_horainidia'))."','$this->at40_horainidia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_horafim"]))
           $resac = db_query("insert into db_acount values($acount,1365,8085,'".AddSlashes(pg_result($resaco,$conresaco,'at40_horafim'))."','$this->at40_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_progresso"]))
           $resac = db_query("insert into db_acount values($acount,1365,8086,'".AddSlashes(pg_result($resaco,$conresaco,'at40_progresso'))."','$this->at40_progresso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_prioridade"]))
           $resac = db_query("insert into db_acount values($acount,1365,8224,'".AddSlashes(pg_result($resaco,$conresaco,'at40_prioridade'))."','$this->at40_prioridade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_obs"]))
           $resac = db_query("insert into db_acount values($acount,1365,8673,'".AddSlashes(pg_result($resaco,$conresaco,'at40_obs'))."','$this->at40_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_autorizada"]))
           $resac = db_query("insert into db_acount values($acount,1365,8831,'".AddSlashes(pg_result($resaco,$conresaco,'at40_autorizada'))."','$this->at40_autorizada',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1365,8833,'".AddSlashes(pg_result($resaco,$conresaco,'at40_tipo'))."','$this->at40_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_ativo"]))
           $resac = db_query("insert into db_acount values($acount,1365,8832,'".AddSlashes(pg_result($resaco,$conresaco,'at40_ativo'))."','$this->at40_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at40_urgente"]))
           $resac = db_query("insert into db_acount values($acount,1365,9147,'".AddSlashes(pg_result($resaco,$conresaco,'at40_urgente'))."','$this->at40_urgente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tarefas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at40_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tarefas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at40_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at40_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8076,'$at40_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1365,8076,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8078,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_responsavel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8079,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8080,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_diaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8081,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_diafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8082,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_previsao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8083,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_tipoprevisao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8084,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_horainidia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8085,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8086,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_progresso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8224,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_prioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8673,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8831,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_autorizada'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8833,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,8832,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1365,9147,'','".AddSlashes(pg_result($resaco,$iresaco,'at40_urgente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefa
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at40_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at40_sequencial = $at40_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tarefas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at40_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tarefas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at40_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at40_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefa";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function enviar_email($para,$assunto,$mensagem) {
    if(!class_exists("Smtp")) {
      $header = 'Content-type: text/html; charset=iso-8859-1'."\r\n";
      return(mail($para,$assunto,$mensagem,$header));
    } else {
      $oSmtp = new Smtp();
      $oSmtp->html = true;
      return $oSmtp->Send($para, "www@desenvolvimento.dbseller.com.br", $assunto, $mensagem);
    }

  }
   function sql_query ( $at40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa ";
     $sql .= "      inner join db_usuarios   on db_usuarios.id_usuario = tarefa.at40_responsavel ";
     $sql .= "      left join tarefa_lanc    on tarefa.at40_sequencial = tarefa_lanc.at36_tarefa and at36_tipo = 'I'"; 
     $sql .= "      left join tarefasituacao on at47_tarefa 		   = tarefa.at40_sequencial"; 
     $sql .= "      left join db_usuarios as db_usuarios2 on tarefa_lanc.at36_usuario = db_usuarios2.id_usuario";
     $sql .= "      left join tarefaproced on tarefaproced.at41_tarefa = tarefa.at40_sequencial";
     $sql .= "      left join db_proced    on db_proced.at30_codigo    = tarefaproced.at41_proced";
     $sql2 = "";
     if($dbwhere==""){
       if($at40_sequencial!=null ){
         $sql2 .= " where tarefa.at40_sequencial = $at40_sequencial "; 
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
   function sql_query_cons_envol ( $at40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa ";
     $sql .= "      left join tarefaproced       on tarefaproced.at41_tarefa   = tarefa.at40_sequencial";
     $sql .= "      left join db_proced          on db_proced.at30_codigo      = tarefaproced.at41_proced";
		 $sql .= "      left join db_procedgrupos    on db_procedgrupos.at52_proced = db_proced.at30_codigo";
     $sql .= "      left join  tarefasituacao    on tarefasituacao.at47_tarefa = tarefa.at40_sequencial";
     $sql .= "      left join  tarefamotivo      on tarefamotivo.at55_tarefa   = tarefa.at40_sequencial";
     $sql .= "      inner join tarefaenvol       on tarefaenvol.at45_tarefa    = tarefa.at40_sequencial";
     $sql .= "      left  join tarefaclientes    on tarefaclientes.at70_tarefa = tarefa.at40_sequencial";
     $sql .= "      left  join clientes          on clientes.at01_codcli       = tarefaclientes.at70_cliente";
     $sql .= "      inner join db_usuarios       on db_usuarios.id_usuario     = tarefaenvol.at45_usuario";
     $sql .= "      inner join db_depusu         on db_usuarios.id_usuario     = db_depusu.id_usuario";
     $sql .= "      left join tarefa_lanc        on tarefa_lanc.at36_tarefa    = tarefa.at40_sequencial and tarefa_lanc.at36_tipo = 'I'";
     $sql .= "      left join db_usuarios db_usuarios_lanc on tarefa_lanc.at36_usuario = db_usuarios_lanc.id_usuario";
     $sql .= "      left join tarefalog          on tarefalog.at43_tarefa      = tarefa.at40_sequencial";
		 $sql .= "      left join tarefamodulo			 on tarefamodulo.at49_tarefa   = tarefa.at40_sequencial";
		 $sql .= "      left join tarefasyscadproced on tarefasyscadproced.at37_tarefa = tarefa.at40_sequencial";
		 $sql .= "      left join db_syscadproced on tarefasyscadproced.at37_syscadproced = db_syscadproced.codproced";
     $sql2 = "";
     if($dbwhere==""){
       if($at40_sequencial!=null ){
         $sql2 .= " where tarefa.at40_sequencial = $at40_sequencial "; 
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
   function sql_query_cons_tarefa ( $at40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa ";
     $sql .= "      inner join tarefaproced   on tarefaproced.at41_tarefa   = tarefa.at40_sequencial";
     $sql .= "      inner join db_proced      on db_proced.at30_codigo      = tarefaproced.at41_proced";
     $sql .= "      left  join tarefaclientes on tarefaclientes.at70_tarefa = tarefa.at40_sequencial";
     $sql .= "      left  join clientes       on clientes.at01_codcli       = tarefaclientes.at70_cliente";
     $sql .= "      left join tarefa_lanc     on tarefa_lanc.at36_tarefa    = tarefa.at40_sequencial and tarefa_lanc.at36_tipo = 'I'";
     $sql .= "      left join db_usuarios db_usuarios_lanc on db_usuarios_lanc.id_usuario     = tarefa_lanc.at36_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($at40_sequencial!=null ){
         $sql2 .= " where tarefa.at40_sequencial = $at40_sequencial "; 
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
   function sql_query_envol ( $at40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa ";
     $sql .= "      left join  tarefaproced on tarefaproced.at41_tarefa = tarefa.at40_sequencial";
     $sql .= "      left join  tarefasituacao on tarefasituacao.at47_tarefa = tarefa.at40_sequencial";
     $sql .= "      inner join tarefaenvol on tarefaenvol.at45_tarefa = tarefa.at40_sequencial";
     $sql .= "      inner join db_usuarios on db_usuarios.id_usuario  = tarefaenvol.at45_usuario";
     $sql .= "      left  join tarefa_lanc on tarefa_lanc.at36_tarefa = tarefa.at40_sequencial and tarefa_lanc.at36_tipo = 'I'";
     $sql .= "      left  join db_usuarios db_usuarios2 on tarefa_lanc.at36_usuario = db_usuarios2.id_usuario ";
     $sql2 = "";
     if($dbwhere==""){
       if($at40_sequencial!=null ){
         $sql2 .= " where tarefa.at40_sequencial = $at40_sequencial "; 
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
   function sql_query_file ( $at40_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefa ";
     $sql2 = "";
     if($dbwhere==""){
       if($at40_sequencial!=null ){
         $sql2 .= " where tarefa.at40_sequencial = $at40_sequencial "; 
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