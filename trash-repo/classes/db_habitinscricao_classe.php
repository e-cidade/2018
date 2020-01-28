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
//CLASSE DA ENTIDADE habitinscricao
class cl_habitinscricao { 
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
   var $ht15_sequencial = 0; 
   var $ht15_habitcandidatointeresseprograma = 0; 
   var $ht15_id_usuario = 0; 
   var $ht15_datalancamento_dia = null; 
   var $ht15_datalancamento_mes = null; 
   var $ht15_datalancamento_ano = null; 
   var $ht15_datalancamento = null; 
   var $ht15_hora = null; 
   var $ht15_tipoprioridade = 0; 
   var $ht15_lembrete = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ht15_sequencial = int4 = Sequencial 
                 ht15_habitcandidatointeresseprograma = int4 = Interesse no Programa 
                 ht15_id_usuario = int4 = Usuário 
                 ht15_datalancamento = date = Data de Lançamento 
                 ht15_hora = char(5) = Hora 
                 ht15_tipoprioridade = int4 = Tipo de Prioriadade 
                 ht15_lembrete = text = Lembrete 
                 ";
   //funcao construtor da classe 
   function cl_habitinscricao() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("habitinscricao"); 
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
       $this->ht15_sequencial = ($this->ht15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht15_sequencial"]:$this->ht15_sequencial);
       $this->ht15_habitcandidatointeresseprograma = ($this->ht15_habitcandidatointeresseprograma == ""?@$GLOBALS["HTTP_POST_VARS"]["ht15_habitcandidatointeresseprograma"]:$this->ht15_habitcandidatointeresseprograma);
       $this->ht15_id_usuario = ($this->ht15_id_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["ht15_id_usuario"]:$this->ht15_id_usuario);
       if($this->ht15_datalancamento == ""){
         $this->ht15_datalancamento_dia = ($this->ht15_datalancamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ht15_datalancamento_dia"]:$this->ht15_datalancamento_dia);
         $this->ht15_datalancamento_mes = ($this->ht15_datalancamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ht15_datalancamento_mes"]:$this->ht15_datalancamento_mes);
         $this->ht15_datalancamento_ano = ($this->ht15_datalancamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ht15_datalancamento_ano"]:$this->ht15_datalancamento_ano);
         if($this->ht15_datalancamento_dia != ""){
            $this->ht15_datalancamento = $this->ht15_datalancamento_ano."-".$this->ht15_datalancamento_mes."-".$this->ht15_datalancamento_dia;
         }
       }
       $this->ht15_hora = ($this->ht15_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["ht15_hora"]:$this->ht15_hora);
       $this->ht15_tipoprioridade = ($this->ht15_tipoprioridade == ""?@$GLOBALS["HTTP_POST_VARS"]["ht15_tipoprioridade"]:$this->ht15_tipoprioridade);
       $this->ht15_lembrete = ($this->ht15_lembrete == ""?@$GLOBALS["HTTP_POST_VARS"]["ht15_lembrete"]:$this->ht15_lembrete);
     }else{
       $this->ht15_sequencial = ($this->ht15_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ht15_sequencial"]:$this->ht15_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ht15_sequencial){ 
      $this->atualizacampos();
     if($this->ht15_habitcandidatointeresseprograma == null ){ 
       $this->erro_sql = " Campo Interesse no Programa nao Informado.";
       $this->erro_campo = "ht15_habitcandidatointeresseprograma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht15_id_usuario == null ){ 
       $this->erro_sql = " Campo Usuário nao Informado.";
       $this->erro_campo = "ht15_id_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht15_datalancamento == null ){ 
       $this->ht15_datalancamento = "null";
     }
     if($this->ht15_hora == null ){ 
       $this->erro_sql = " Campo Hora nao Informado.";
       $this->erro_campo = "ht15_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht15_tipoprioridade == null ){ 
       $this->erro_sql = " Campo Tipo de Prioriadade nao Informado.";
       $this->erro_campo = "ht15_tipoprioridade";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ht15_lembrete == null ){ 
       $this->erro_sql = " Campo Lembrete nao Informado.";
       $this->erro_campo = "ht15_lembrete";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ht15_sequencial == "" || $ht15_sequencial == null ){
       $result = db_query("select nextval('habitinscricao_ht15_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: habitinscricao_ht15_sequencial_seq do campo: ht15_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ht15_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from habitinscricao_ht15_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ht15_sequencial)){
         $this->erro_sql = " Campo ht15_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ht15_sequencial = $ht15_sequencial; 
       }
     }
     if(($this->ht15_sequencial == null) || ($this->ht15_sequencial == "") ){ 
       $this->erro_sql = " Campo ht15_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into habitinscricao(
                                       ht15_sequencial 
                                      ,ht15_habitcandidatointeresseprograma 
                                      ,ht15_id_usuario 
                                      ,ht15_datalancamento 
                                      ,ht15_hora 
                                      ,ht15_tipoprioridade 
                                      ,ht15_lembrete 
                       )
                values (
                                $this->ht15_sequencial 
                               ,$this->ht15_habitcandidatointeresseprograma 
                               ,$this->ht15_id_usuario 
                               ,".($this->ht15_datalancamento == "null" || $this->ht15_datalancamento == ""?"null":"'".$this->ht15_datalancamento."'")." 
                               ,'$this->ht15_hora' 
                               ,$this->ht15_tipoprioridade 
                               ,'$this->ht15_lembrete' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Inscrição da Habitação ($this->ht15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Inscrição da Habitação já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Inscrição da Habitação ($this->ht15_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht15_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ht15_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17003,'$this->ht15_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3003,17003,'','".AddSlashes(pg_result($resaco,0,'ht15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3003,17004,'','".AddSlashes(pg_result($resaco,0,'ht15_habitcandidatointeresseprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3003,17006,'','".AddSlashes(pg_result($resaco,0,'ht15_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3003,17007,'','".AddSlashes(pg_result($resaco,0,'ht15_datalancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3003,17008,'','".AddSlashes(pg_result($resaco,0,'ht15_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3003,17010,'','".AddSlashes(pg_result($resaco,0,'ht15_tipoprioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3003,17820,'','".AddSlashes(pg_result($resaco,0,'ht15_lembrete'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ht15_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update habitinscricao set ";
     $virgula = "";
     if(trim($this->ht15_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht15_sequencial"])){ 
       $sql  .= $virgula." ht15_sequencial = $this->ht15_sequencial ";
       $virgula = ",";
       if(trim($this->ht15_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ht15_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht15_habitcandidatointeresseprograma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht15_habitcandidatointeresseprograma"])){ 
       $sql  .= $virgula." ht15_habitcandidatointeresseprograma = $this->ht15_habitcandidatointeresseprograma ";
       $virgula = ",";
       if(trim($this->ht15_habitcandidatointeresseprograma) == null ){ 
         $this->erro_sql = " Campo Interesse no Programa nao Informado.";
         $this->erro_campo = "ht15_habitcandidatointeresseprograma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht15_id_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht15_id_usuario"])){ 
       $sql  .= $virgula." ht15_id_usuario = $this->ht15_id_usuario ";
       $virgula = ",";
       if(trim($this->ht15_id_usuario) == null ){ 
         $this->erro_sql = " Campo Usuário nao Informado.";
         $this->erro_campo = "ht15_id_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht15_datalancamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht15_datalancamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ht15_datalancamento_dia"] !="") ){ 
       $sql  .= $virgula." ht15_datalancamento = '$this->ht15_datalancamento' ";
       $virgula = ",";
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ht15_datalancamento_dia"])){ 
         $sql  .= $virgula." ht15_datalancamento = null ";
         $virgula = ",";
       }
     }
     if(trim($this->ht15_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht15_hora"])){ 
       $sql  .= $virgula." ht15_hora = '$this->ht15_hora' ";
       $virgula = ",";
       if(trim($this->ht15_hora) == null ){ 
         $this->erro_sql = " Campo Hora nao Informado.";
         $this->erro_campo = "ht15_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht15_tipoprioridade)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht15_tipoprioridade"])){ 
       $sql  .= $virgula." ht15_tipoprioridade = $this->ht15_tipoprioridade ";
       $virgula = ",";
       if(trim($this->ht15_tipoprioridade) == null ){ 
         $this->erro_sql = " Campo Tipo de Prioriadade nao Informado.";
         $this->erro_campo = "ht15_tipoprioridade";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ht15_lembrete)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ht15_lembrete"])){ 
       $sql  .= $virgula." ht15_lembrete = '$this->ht15_lembrete' ";
       $virgula = ",";
       if(trim($this->ht15_lembrete) == null ){ 
         $this->erro_sql = " Campo Lembrete nao Informado.";
         $this->erro_campo = "ht15_lembrete";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ht15_sequencial!=null){
       $sql .= " ht15_sequencial = $this->ht15_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ht15_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17003,'$this->ht15_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht15_sequencial"]) || $this->ht15_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3003,17003,'".AddSlashes(pg_result($resaco,$conresaco,'ht15_sequencial'))."','$this->ht15_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht15_habitcandidatointeresseprograma"]) || $this->ht15_habitcandidatointeresseprograma != "")
           $resac = db_query("insert into db_acount values($acount,3003,17004,'".AddSlashes(pg_result($resaco,$conresaco,'ht15_habitcandidatointeresseprograma'))."','$this->ht15_habitcandidatointeresseprograma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht15_id_usuario"]) || $this->ht15_id_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3003,17006,'".AddSlashes(pg_result($resaco,$conresaco,'ht15_id_usuario'))."','$this->ht15_id_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht15_datalancamento"]) || $this->ht15_datalancamento != "")
           $resac = db_query("insert into db_acount values($acount,3003,17007,'".AddSlashes(pg_result($resaco,$conresaco,'ht15_datalancamento'))."','$this->ht15_datalancamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht15_hora"]) || $this->ht15_hora != "")
           $resac = db_query("insert into db_acount values($acount,3003,17008,'".AddSlashes(pg_result($resaco,$conresaco,'ht15_hora'))."','$this->ht15_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht15_tipoprioridade"]) || $this->ht15_tipoprioridade != "")
           $resac = db_query("insert into db_acount values($acount,3003,17010,'".AddSlashes(pg_result($resaco,$conresaco,'ht15_tipoprioridade'))."','$this->ht15_tipoprioridade',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ht15_lembrete"]) || $this->ht15_lembrete != "")
           $resac = db_query("insert into db_acount values($acount,3003,17820,'".AddSlashes(pg_result($resaco,$conresaco,'ht15_lembrete'))."','$this->ht15_lembrete',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inscrição da Habitação nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inscrição da Habitação nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ht15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ht15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ht15_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ht15_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17003,'$ht15_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3003,17003,'','".AddSlashes(pg_result($resaco,$iresaco,'ht15_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3003,17004,'','".AddSlashes(pg_result($resaco,$iresaco,'ht15_habitcandidatointeresseprograma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3003,17006,'','".AddSlashes(pg_result($resaco,$iresaco,'ht15_id_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3003,17007,'','".AddSlashes(pg_result($resaco,$iresaco,'ht15_datalancamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3003,17008,'','".AddSlashes(pg_result($resaco,$iresaco,'ht15_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3003,17010,'','".AddSlashes(pg_result($resaco,$iresaco,'ht15_tipoprioridade'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3003,17820,'','".AddSlashes(pg_result($resaco,$iresaco,'ht15_lembrete'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from habitinscricao
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ht15_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ht15_sequencial = $ht15_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Inscrição da Habitação nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ht15_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Inscrição da Habitação nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ht15_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ht15_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:habitinscricao";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ht15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitinscricao ";
     $sql .= "      inner join db_usuarios                     on db_usuarios.id_usuario                          = habitinscricao.ht15_id_usuario                               ";
     $sql .= "      inner join habitcandidatointeresseprograma on habitcandidatointeresseprograma.ht13_sequencial = habitinscricao.ht15_habitcandidatointeresseprograma          ";
     $sql .= "      inner join habitcandidatointeresse         on habitcandidatointeresse.ht20_sequencial         = habitcandidatointeresseprograma.ht13_habitcandidatointeresse ";
     $sql .= "      inner join habitcandidato                  on habitcandidato.ht10_sequencial                  = habitcandidatointeresse.ht20_habitcandidato                  ";
     $sql .= "      inner join habitprograma                   on habitprograma.ht01_sequencial                   = habitcandidatointeresseprograma.ht13_habitprograma           ";
     $sql .= "      inner join cgm                             on cgm.z01_numcgm                                  = habitcandidato.ht10_numcgm                                   ";
     
     $sql2 = "";
     if($dbwhere==""){
       if($ht15_sequencial!=null ){
         $sql2 .= " where habitinscricao.ht15_sequencial = $ht15_sequencial "; 
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
   function sql_query_file ( $ht15_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from habitinscricao ";
     $sql2 = "";
     if($dbwhere==""){
       if($ht15_sequencial!=null ){
         $sql2 .= " where habitinscricao.ht15_sequencial = $ht15_sequencial "; 
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