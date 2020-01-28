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

//MODULO: Acordos
//CLASSE DA ENTIDADE acordovigencia
class cl_acordovigencia { 
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
   var $ac18_sequencial = 0; 
   var $ac18_acordoposicao = 0; 
   var $ac18_datainicio_dia = null; 
   var $ac18_datainicio_mes = null; 
   var $ac18_datainicio_ano = null; 
   var $ac18_datainicio = null; 
   var $ac18_datafim_dia = null; 
   var $ac18_datafim_mes = null; 
   var $ac18_datafim_ano = null; 
   var $ac18_datafim = null; 
   var $ac18_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ac18_sequencial = int4 = Sequencial 
                 ac18_acordoposicao = int4 = Acordo 
                 ac18_datainicio = date = Data Inicio 
                 ac18_datafim = date = Data Final 
                 ac18_ativo = bool = Ativo 
                 ";
   //funcao construtor da classe 
   function cl_acordovigencia() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("acordovigencia"); 
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
       $this->ac18_sequencial = ($this->ac18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac18_sequencial"]:$this->ac18_sequencial);
       $this->ac18_acordoposicao = ($this->ac18_acordoposicao == ""?@$GLOBALS["HTTP_POST_VARS"]["ac18_acordoposicao"]:$this->ac18_acordoposicao);
       if($this->ac18_datainicio == ""){
         $this->ac18_datainicio_dia = ($this->ac18_datainicio_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac18_datainicio_dia"]:$this->ac18_datainicio_dia);
         $this->ac18_datainicio_mes = ($this->ac18_datainicio_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac18_datainicio_mes"]:$this->ac18_datainicio_mes);
         $this->ac18_datainicio_ano = ($this->ac18_datainicio_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac18_datainicio_ano"]:$this->ac18_datainicio_ano);
         if($this->ac18_datainicio_dia != ""){
            $this->ac18_datainicio = $this->ac18_datainicio_ano."-".$this->ac18_datainicio_mes."-".$this->ac18_datainicio_dia;
         }
       }
       if($this->ac18_datafim == ""){
         $this->ac18_datafim_dia = ($this->ac18_datafim_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["ac18_datafim_dia"]:$this->ac18_datafim_dia);
         $this->ac18_datafim_mes = ($this->ac18_datafim_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["ac18_datafim_mes"]:$this->ac18_datafim_mes);
         $this->ac18_datafim_ano = ($this->ac18_datafim_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["ac18_datafim_ano"]:$this->ac18_datafim_ano);
         if($this->ac18_datafim_dia != ""){
            $this->ac18_datafim = $this->ac18_datafim_ano."-".$this->ac18_datafim_mes."-".$this->ac18_datafim_dia;
         }
       }
       $this->ac18_ativo = ($this->ac18_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["ac18_ativo"]:$this->ac18_ativo);
     }else{
       $this->ac18_sequencial = ($this->ac18_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["ac18_sequencial"]:$this->ac18_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($ac18_sequencial){ 
      $this->atualizacampos();
     if($this->ac18_acordoposicao == null ){ 
       $this->erro_sql = " Campo Acordo nao Informado.";
       $this->erro_campo = "ac18_acordoposicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac18_datainicio == null ){ 
       $this->erro_sql = " Campo Data Inicio nao Informado.";
       $this->erro_campo = "ac18_datainicio_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac18_datafim == null ){ 
       $this->erro_sql = " Campo Data Final nao Informado.";
       $this->erro_campo = "ac18_datafim_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ac18_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ac18_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ac18_sequencial == "" || $ac18_sequencial == null ){
       $result = db_query("select nextval('acordovigencia_ac18_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: acordovigencia_ac18_sequencial_seq do campo: ac18_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ac18_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from acordovigencia_ac18_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $ac18_sequencial)){
         $this->erro_sql = " Campo ac18_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ac18_sequencial = $ac18_sequencial; 
       }
     }
     if(($this->ac18_sequencial == null) || ($this->ac18_sequencial == "") ){ 
       $this->erro_sql = " Campo ac18_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into acordovigencia(
                                       ac18_sequencial 
                                      ,ac18_acordoposicao 
                                      ,ac18_datainicio 
                                      ,ac18_datafim 
                                      ,ac18_ativo 
                       )
                values (
                                $this->ac18_sequencial 
                               ,$this->ac18_acordoposicao 
                               ,".($this->ac18_datainicio == "null" || $this->ac18_datainicio == ""?"null":"'".$this->ac18_datainicio."'")." 
                               ,".($this->ac18_datafim == "null" || $this->ac18_datafim == ""?"null":"'".$this->ac18_datafim."'")." 
                               ,'$this->ac18_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Acordo Vigencia ($this->ac18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Acordo Vigencia já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Acordo Vigencia ($this->ac18_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac18_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ac18_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,16165,'$this->ac18_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,2836,16165,'','".AddSlashes(pg_result($resaco,0,'ac18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2836,16166,'','".AddSlashes(pg_result($resaco,0,'ac18_acordoposicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2836,16167,'','".AddSlashes(pg_result($resaco,0,'ac18_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2836,16168,'','".AddSlashes(pg_result($resaco,0,'ac18_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2836,16169,'','".AddSlashes(pg_result($resaco,0,'ac18_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ac18_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update acordovigencia set ";
     $virgula = "";
     if(trim($this->ac18_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac18_sequencial"])){ 
       $sql  .= $virgula." ac18_sequencial = $this->ac18_sequencial ";
       $virgula = ",";
       if(trim($this->ac18_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "ac18_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac18_acordoposicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac18_acordoposicao"])){ 
       $sql  .= $virgula." ac18_acordoposicao = $this->ac18_acordoposicao ";
       $virgula = ",";
       if(trim($this->ac18_acordoposicao) == null ){ 
         $this->erro_sql = " Campo Acordo nao Informado.";
         $this->erro_campo = "ac18_acordoposicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ac18_datainicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac18_datainicio_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac18_datainicio_dia"] !="") ){ 
       $sql  .= $virgula." ac18_datainicio = '$this->ac18_datainicio' ";
       $virgula = ",";
       if(trim($this->ac18_datainicio) == null ){ 
         $this->erro_sql = " Campo Data Inicio nao Informado.";
         $this->erro_campo = "ac18_datainicio_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac18_datainicio_dia"])){ 
         $sql  .= $virgula." ac18_datainicio = null ";
         $virgula = ",";
         if(trim($this->ac18_datainicio) == null ){ 
           $this->erro_sql = " Campo Data Inicio nao Informado.";
           $this->erro_campo = "ac18_datainicio_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac18_datafim)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac18_datafim_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["ac18_datafim_dia"] !="") ){ 
       $sql  .= $virgula." ac18_datafim = '$this->ac18_datafim' ";
       $virgula = ",";
       if(trim($this->ac18_datafim) == null ){ 
         $this->erro_sql = " Campo Data Final nao Informado.";
         $this->erro_campo = "ac18_datafim_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["ac18_datafim_dia"])){ 
         $sql  .= $virgula." ac18_datafim = null ";
         $virgula = ",";
         if(trim($this->ac18_datafim) == null ){ 
           $this->erro_sql = " Campo Data Final nao Informado.";
           $this->erro_campo = "ac18_datafim_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->ac18_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ac18_ativo"])){ 
       $sql  .= $virgula." ac18_ativo = '$this->ac18_ativo' ";
       $virgula = ",";
       if(trim($this->ac18_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ac18_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ac18_sequencial!=null){
       $sql .= " ac18_sequencial = $this->ac18_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ac18_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16165,'$this->ac18_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac18_sequencial"]) || $this->ac18_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,2836,16165,'".AddSlashes(pg_result($resaco,$conresaco,'ac18_sequencial'))."','$this->ac18_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac18_acordoposicao"]) || $this->ac18_acordoposicao != "")
           $resac = db_query("insert into db_acount values($acount,2836,16166,'".AddSlashes(pg_result($resaco,$conresaco,'ac18_acordoposicao'))."','$this->ac18_acordoposicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac18_datainicio"]) || $this->ac18_datainicio != "")
           $resac = db_query("insert into db_acount values($acount,2836,16167,'".AddSlashes(pg_result($resaco,$conresaco,'ac18_datainicio'))."','$this->ac18_datainicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac18_datafim"]) || $this->ac18_datafim != "")
           $resac = db_query("insert into db_acount values($acount,2836,16168,'".AddSlashes(pg_result($resaco,$conresaco,'ac18_datafim'))."','$this->ac18_datafim',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ac18_ativo"]) || $this->ac18_ativo != "")
           $resac = db_query("insert into db_acount values($acount,2836,16169,'".AddSlashes(pg_result($resaco,$conresaco,'ac18_ativo'))."','$this->ac18_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Vigencia nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Vigencia nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ac18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ac18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ac18_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ac18_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,16165,'$ac18_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,2836,16165,'','".AddSlashes(pg_result($resaco,$iresaco,'ac18_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2836,16166,'','".AddSlashes(pg_result($resaco,$iresaco,'ac18_acordoposicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2836,16167,'','".AddSlashes(pg_result($resaco,$iresaco,'ac18_datainicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2836,16168,'','".AddSlashes(pg_result($resaco,$iresaco,'ac18_datafim'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2836,16169,'','".AddSlashes(pg_result($resaco,$iresaco,'ac18_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from acordovigencia
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ac18_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ac18_sequencial = $ac18_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Acordo Vigencia nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ac18_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Acordo Vigencia nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ac18_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ac18_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:acordovigencia";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ac18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordovigencia ";
     $sql .= "      inner join acordoposicao  on  acordoposicao.ac26_sequencial = acordovigencia.ac18_acordoposicao";
     $sql .= "      inner join acordo  on  acordo.ac16_sequencial = acordoposicao.ac26_acordo";
     $sql .= "      inner join acordoposicaotipo  on  acordoposicaotipo.ac27_sequencial = acordoposicao.ac26_acordoposicaotipo";
     $sql2 = "";
     if($dbwhere==""){
       if($ac18_sequencial!=null ){
         $sql2 .= " where acordovigencia.ac18_sequencial = $ac18_sequencial "; 
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
   function sql_query_file ( $ac18_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from acordovigencia ";
     $sql2 = "";
     if($dbwhere==""){
       if($ac18_sequencial!=null ){
         $sql2 .= " where acordovigencia.ac18_sequencial = $ac18_sequencial "; 
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