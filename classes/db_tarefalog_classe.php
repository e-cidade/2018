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
//CLASSE DA ENTIDADE tarefalog
class cl_tarefalog { 
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
   var $at43_sequencial = 0; 
   var $at43_tarefa = 0; 
   var $at43_descr = null; 
   var $at43_obs = null; 
   var $at43_problema = 'f'; 
   var $at43_avisar = 0; 
   var $at43_progresso = 0; 
   var $at43_usuario = 0; 
   var $at43_diaini_dia = null; 
   var $at43_diaini_mes = null; 
   var $at43_diaini_ano = null; 
   var $at43_diaini = null; 
   var $at43_diafim_dia = null; 
   var $at43_diafim_mes = null; 
   var $at43_diafim_ano = null; 
   var $at43_diafim = null; 
   var $at43_horainidia = null; 
   var $at43_horafim = null; 
   var $at43_tipomov = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 at43_sequencial = int4 = Codigo de Andamento 
                 at43_tarefa = int4 = Tarefa 
                 at43_descr = text = Descricao 
                 at43_obs = text = Obs. 
                 at43_problema = bool = Problema 
                 at43_avisar = int4 = Avisar 
                 at43_progresso = float8 = Progresso da tarefa 
                 at43_usuario = int4 = Cod. Usuário 
                 at43_diaini = date = Data de início 
                 at43_diafim = date = Data final previsto 
                 at43_horainidia = char(5) = Hora inicial 
                 at43_horafim = char(5) = Hora final 
                 at43_tipomov = int4 = Movimento 
                 ";
   //funcao construtor da classe 
   function cl_tarefalog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("tarefalog"); 
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
       $this->at43_sequencial = ($this->at43_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_sequencial"]:$this->at43_sequencial);
       $this->at43_tarefa = ($this->at43_tarefa == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_tarefa"]:$this->at43_tarefa);
       $this->at43_descr = ($this->at43_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_descr"]:$this->at43_descr);
       $this->at43_obs = ($this->at43_obs == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_obs"]:$this->at43_obs);
       $this->at43_problema = ($this->at43_problema == "f"?@$GLOBALS["HTTP_POST_VARS"]["at43_problema"]:$this->at43_problema);
       $this->at43_avisar = ($this->at43_avisar == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_avisar"]:$this->at43_avisar);
       $this->at43_progresso = ($this->at43_progresso == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_progresso"]:$this->at43_progresso);
       $this->at43_usuario = ($this->at43_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_usuario"]:$this->at43_usuario);
       if($this->at43_diaini == ""){
         $this->at43_diaini_dia = ($this->at43_diaini_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_diaini_dia"]:$this->at43_diaini_dia);
         $this->at43_diaini_mes = ($this->at43_diaini_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_diaini_mes"]:$this->at43_diaini_mes);
         $this->at43_diaini_ano = ($this->at43_diaini_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_diaini_ano"]:$this->at43_diaini_ano);
         if($this->at43_diaini_dia != ""){
            $this->at43_diaini = $this->at43_diaini_ano."-".$this->at43_diaini_mes."-".$this->at43_diaini_dia;
         }
       }
       if($this->at43_diafim == ""){
         $this->at43_diafim_dia = ($this->at43_diafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_diafim_dia"]:$this->at43_diafim_dia);
         $this->at43_diafim_mes = ($this->at43_diafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_diafim_mes"]:$this->at43_diafim_mes);
         $this->at43_diafim_ano = ($this->at43_diafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_diafim_ano"]:$this->at43_diafim_ano);
         if($this->at43_diafim_dia != ""){
            $this->at43_diafim = $this->at43_diafim_ano."-".$this->at43_diafim_mes."-".$this->at43_diafim_dia;
         }
       }
       $this->at43_horainidia = ($this->at43_horainidia == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_horainidia"]:$this->at43_horainidia);
       $this->at43_horafim = ($this->at43_horafim == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_horafim"]:$this->at43_horafim);
       $this->at43_tipomov = ($this->at43_tipomov == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_tipomov"]:$this->at43_tipomov);
     }else{
       $this->at43_sequencial = ($this->at43_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["at43_sequencial"]:$this->at43_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($at43_sequencial){ 
      $this->atualizacampos();
     if($this->at43_tarefa == null ){ 
       $this->erro_sql = " Campo Tarefa nao Informado.";
       $this->erro_campo = "at43_tarefa";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at43_descr == null ){ 
       $this->erro_sql = " Campo Descricao nao Informado.";
       $this->erro_campo = "at43_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at43_problema == null ){ 
       $this->erro_sql = " Campo Problema nao Informado.";
       $this->erro_campo = "at43_problema";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at43_avisar == null ){ 
       $this->erro_sql = " Campo Avisar nao Informado.";
       $this->erro_campo = "at43_avisar";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at43_progresso == null ){ 
       $this->erro_sql = " Campo Progresso da tarefa nao Informado.";
       $this->erro_campo = "at43_progresso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at43_usuario == null ){ 
       $this->erro_sql = " Campo Cod. Usuário nao Informado.";
       $this->erro_campo = "at43_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at43_diaini == null ){ 
       $this->erro_sql = " Campo Data de início nao Informado.";
       $this->erro_campo = "at43_diaini_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at43_diafim == null ){ 
       $this->erro_sql = " Campo Data final previsto nao Informado.";
       $this->erro_campo = "at43_diafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at43_horainidia == null ){ 
       $this->erro_sql = " Campo Hora inicial nao Informado.";
       $this->erro_campo = "at43_horainidia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at43_horafim == null ){ 
       $this->erro_sql = " Campo Hora final nao Informado.";
       $this->erro_campo = "at43_horafim";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->at43_tipomov == null ){ 
       $this->erro_sql = " Campo Movimento nao Informado.";
       $this->erro_campo = "at43_tipomov";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($at43_sequencial == "" || $at43_sequencial == null ){
       $result = db_query("select nextval('tarefalog_at43_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: tarefalog_at43_sequencial_seq do campo: at43_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->at43_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from tarefalog_at43_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $at43_sequencial)){
         $this->erro_sql = " Campo at43_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->at43_sequencial = $at43_sequencial; 
       }
     }
     if(($this->at43_sequencial == null) || ($this->at43_sequencial == "") ){ 
       $this->erro_sql = " Campo at43_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into tarefalog(
                                       at43_sequencial 
                                      ,at43_tarefa 
                                      ,at43_descr 
                                      ,at43_obs 
                                      ,at43_problema 
                                      ,at43_avisar 
                                      ,at43_progresso 
                                      ,at43_usuario 
                                      ,at43_diaini 
                                      ,at43_diafim 
                                      ,at43_horainidia 
                                      ,at43_horafim 
                                      ,at43_tipomov 
                       )
                values (
                                $this->at43_sequencial 
                               ,$this->at43_tarefa 
                               ,'$this->at43_descr' 
                               ,'$this->at43_obs' 
                               ,'$this->at43_problema' 
                               ,$this->at43_avisar 
                               ,$this->at43_progresso 
                               ,$this->at43_usuario 
                               ,".($this->at43_diaini == "null" || $this->at43_diaini == ""?"null":"'".$this->at43_diaini."'")." 
                               ,".($this->at43_diafim == "null" || $this->at43_diafim == ""?"null":"'".$this->at43_diafim."'")." 
                               ,'$this->at43_horainidia' 
                               ,'$this->at43_horafim' 
                               ,$this->at43_tipomov 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Registros da tarefa ($this->at43_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Registros da tarefa já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Registros da tarefa ($this->at43_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at43_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->at43_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8105,'$this->at43_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,1368,8105,'','".AddSlashes(pg_result($resaco,0,'at43_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8106,'','".AddSlashes(pg_result($resaco,0,'at43_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8107,'','".AddSlashes(pg_result($resaco,0,'at43_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8681,'','".AddSlashes(pg_result($resaco,0,'at43_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8108,'','".AddSlashes(pg_result($resaco,0,'at43_problema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8109,'','".AddSlashes(pg_result($resaco,0,'at43_avisar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8110,'','".AddSlashes(pg_result($resaco,0,'at43_progresso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8135,'','".AddSlashes(pg_result($resaco,0,'at43_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8225,'','".AddSlashes(pg_result($resaco,0,'at43_diaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8226,'','".AddSlashes(pg_result($resaco,0,'at43_diafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8227,'','".AddSlashes(pg_result($resaco,0,'at43_horainidia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,8228,'','".AddSlashes(pg_result($resaco,0,'at43_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1368,9998,'','".AddSlashes(pg_result($resaco,0,'at43_tipomov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($at43_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update tarefalog set ";
     $virgula = "";
     if(trim($this->at43_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_sequencial"])){ 
       $sql  .= $virgula." at43_sequencial = $this->at43_sequencial ";
       $virgula = ",";
       if(trim($this->at43_sequencial) == null ){ 
         $this->erro_sql = " Campo Codigo de Andamento nao Informado.";
         $this->erro_campo = "at43_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at43_tarefa)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_tarefa"])){ 
       $sql  .= $virgula." at43_tarefa = $this->at43_tarefa ";
       $virgula = ",";
       if(trim($this->at43_tarefa) == null ){ 
         $this->erro_sql = " Campo Tarefa nao Informado.";
         $this->erro_campo = "at43_tarefa";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at43_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_descr"])){ 
       $sql  .= $virgula." at43_descr = '$this->at43_descr' ";
       $virgula = ",";
       if(trim($this->at43_descr) == null ){ 
         $this->erro_sql = " Campo Descricao nao Informado.";
         $this->erro_campo = "at43_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at43_obs)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_obs"])){ 
       $sql  .= $virgula." at43_obs = '$this->at43_obs' ";
       $virgula = ",";
     }
     if(trim($this->at43_problema)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_problema"])){ 
       $sql  .= $virgula." at43_problema = '$this->at43_problema' ";
       $virgula = ",";
       if(trim($this->at43_problema) == null ){ 
         $this->erro_sql = " Campo Problema nao Informado.";
         $this->erro_campo = "at43_problema";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at43_avisar)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_avisar"])){ 
       $sql  .= $virgula." at43_avisar = $this->at43_avisar ";
       $virgula = ",";
       if(trim($this->at43_avisar) == null ){ 
         $this->erro_sql = " Campo Avisar nao Informado.";
         $this->erro_campo = "at43_avisar";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at43_progresso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_progresso"])){ 
       $sql  .= $virgula." at43_progresso = $this->at43_progresso ";
       $virgula = ",";
       if(trim($this->at43_progresso) == null ){ 
         $this->erro_sql = " Campo Progresso da tarefa nao Informado.";
         $this->erro_campo = "at43_progresso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at43_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_usuario"])){ 
       $sql  .= $virgula." at43_usuario = $this->at43_usuario ";
       $virgula = ",";
       if(trim($this->at43_usuario) == null ){ 
         $this->erro_sql = " Campo Cod. Usuário nao Informado.";
         $this->erro_campo = "at43_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at43_diaini)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_diaini_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at43_diaini_dia"] !="") ){ 
       $sql  .= $virgula." at43_diaini = '$this->at43_diaini' ";
       $virgula = ",";
       if(trim($this->at43_diaini) == null ){ 
         $this->erro_sql = " Campo Data de início nao Informado.";
         $this->erro_campo = "at43_diaini_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at43_diaini_dia"])){ 
         $sql  .= $virgula." at43_diaini = null ";
         $virgula = ",";
         if(trim($this->at43_diaini) == null ){ 
           $this->erro_sql = " Campo Data de início nao Informado.";
           $this->erro_campo = "at43_diaini_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at43_diafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_diafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["at43_diafim_dia"] !="") ){ 
       $sql  .= $virgula." at43_diafim = '$this->at43_diafim' ";
       $virgula = ",";
       if(trim($this->at43_diafim) == null ){ 
         $this->erro_sql = " Campo Data final previsto nao Informado.";
         $this->erro_campo = "at43_diafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["at43_diafim_dia"])){ 
         $sql  .= $virgula." at43_diafim = null ";
         $virgula = ",";
         if(trim($this->at43_diafim) == null ){ 
           $this->erro_sql = " Campo Data final previsto nao Informado.";
           $this->erro_campo = "at43_diafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->at43_horainidia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_horainidia"])){ 
       $sql  .= $virgula." at43_horainidia = '$this->at43_horainidia' ";
       $virgula = ",";
       if(trim($this->at43_horainidia) == null ){ 
         $this->erro_sql = " Campo Hora inicial nao Informado.";
         $this->erro_campo = "at43_horainidia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at43_horafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_horafim"])){ 
       $sql  .= $virgula." at43_horafim = '$this->at43_horafim' ";
       $virgula = ",";
       if(trim($this->at43_horafim) == null ){ 
         $this->erro_sql = " Campo Hora final nao Informado.";
         $this->erro_campo = "at43_horafim";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->at43_tipomov)!="" || isset($GLOBALS["HTTP_POST_VARS"]["at43_tipomov"])){ 
       $sql  .= $virgula." at43_tipomov = $this->at43_tipomov ";
       $virgula = ",";
       if(trim($this->at43_tipomov) == null ){ 
         $this->erro_sql = " Campo Movimento nao Informado.";
         $this->erro_campo = "at43_tipomov";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($at43_sequencial!=null){
       $sql .= " at43_sequencial = $this->at43_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->at43_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8105,'$this->at43_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_sequencial"]))
           $resac = db_query("insert into db_acount values($acount,1368,8105,'".AddSlashes(pg_result($resaco,$conresaco,'at43_sequencial'))."','$this->at43_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_tarefa"]))
           $resac = db_query("insert into db_acount values($acount,1368,8106,'".AddSlashes(pg_result($resaco,$conresaco,'at43_tarefa'))."','$this->at43_tarefa',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_descr"]))
           $resac = db_query("insert into db_acount values($acount,1368,8107,'".AddSlashes(pg_result($resaco,$conresaco,'at43_descr'))."','$this->at43_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_obs"]))
           $resac = db_query("insert into db_acount values($acount,1368,8681,'".AddSlashes(pg_result($resaco,$conresaco,'at43_obs'))."','$this->at43_obs',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_problema"]))
           $resac = db_query("insert into db_acount values($acount,1368,8108,'".AddSlashes(pg_result($resaco,$conresaco,'at43_problema'))."','$this->at43_problema',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_avisar"]))
           $resac = db_query("insert into db_acount values($acount,1368,8109,'".AddSlashes(pg_result($resaco,$conresaco,'at43_avisar'))."','$this->at43_avisar',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_progresso"]))
           $resac = db_query("insert into db_acount values($acount,1368,8110,'".AddSlashes(pg_result($resaco,$conresaco,'at43_progresso'))."','$this->at43_progresso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_usuario"]))
           $resac = db_query("insert into db_acount values($acount,1368,8135,'".AddSlashes(pg_result($resaco,$conresaco,'at43_usuario'))."','$this->at43_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_diaini"]))
           $resac = db_query("insert into db_acount values($acount,1368,8225,'".AddSlashes(pg_result($resaco,$conresaco,'at43_diaini'))."','$this->at43_diaini',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_diafim"]))
           $resac = db_query("insert into db_acount values($acount,1368,8226,'".AddSlashes(pg_result($resaco,$conresaco,'at43_diafim'))."','$this->at43_diafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_horainidia"]))
           $resac = db_query("insert into db_acount values($acount,1368,8227,'".AddSlashes(pg_result($resaco,$conresaco,'at43_horainidia'))."','$this->at43_horainidia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_horafim"]))
           $resac = db_query("insert into db_acount values($acount,1368,8228,'".AddSlashes(pg_result($resaco,$conresaco,'at43_horafim'))."','$this->at43_horafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["at43_tipomov"]))
           $resac = db_query("insert into db_acount values($acount,1368,9998,'".AddSlashes(pg_result($resaco,$conresaco,'at43_tipomov'))."','$this->at43_tipomov',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros da tarefa nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->at43_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros da tarefa nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->at43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->at43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($at43_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($at43_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8105,'$at43_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,1368,8105,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8106,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_tarefa'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8107,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8681,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_obs'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8108,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_problema'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8109,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_avisar'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8110,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_progresso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8135,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8225,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_diaini'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8226,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_diafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8227,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_horainidia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,8228,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_horafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1368,9998,'','".AddSlashes(pg_result($resaco,$iresaco,'at43_tipomov'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from tarefalog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($at43_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " at43_sequencial = $at43_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Registros da tarefa nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$at43_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Registros da tarefa nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$at43_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$at43_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:tarefalog";
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
      include_once("libs/db_conn.php");
      $oSmtp = new Smtp();
      $oSmtp->html = true;
      return $oSmtp->Send($para, "www@desenvolvimento.dbseller.com.br", $assunto, $mensagem);
    }
  }

   function sql_query ( $at43_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefalog ";
     $sql .= "      inner join tarefa  on  tarefa.at40_sequencial = tarefalog.at43_tarefa";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tarefa.at40_responsavel";
     $sql2 = "";
     if($dbwhere==""){
       if($at43_sequencial!=null ){
         $sql2 .= " where tarefalog.at43_sequencial = $at43_sequencial "; 
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
   function sql_query_file ( $at43_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefalog ";
     $sql2 = "";
     if($dbwhere==""){
       if($at43_sequencial!=null ){
         $sql2 .= " where tarefalog.at43_sequencial = $at43_sequencial "; 
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
   function sql_query_usua ( $at43_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from tarefalog ";
     $sql .= "      inner join tarefa  on  tarefa.at40_sequencial = tarefalog.at43_tarefa";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = tarefalog.at43_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($at43_sequencial!=null ){
         $sql2 .= " where tarefalog.at43_sequencial = $at43_sequencial "; 
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