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
//CLASSE DA ENTIDADE pcorcamjulgamentolog
class cl_pcorcamjulgamentolog { 
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
   var $pc92_sequencial = 0; 
   var $pc92_usuario = 0; 
   var $pc92_datajulgamento_dia = null; 
   var $pc92_datajulgamento_mes = null; 
   var $pc92_datajulgamento_ano = null; 
   var $pc92_datajulgamento = null; 
   var $pc92_hora = null; 
   var $pc92_ativo = 'f'; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 pc92_sequencial = int4 = C�digo 
                 pc92_usuario = int4 = Usu�rio 
                 pc92_datajulgamento = date = Data Julgamento 
                 pc92_hora = varchar(5) = Hora do Julgamento 
                 pc92_ativo = bool = Status do Julgamento 
                 ";
   //funcao construtor da classe 
   function cl_pcorcamjulgamentolog() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("pcorcamjulgamentolog"); 
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
       $this->pc92_sequencial = ($this->pc92_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc92_sequencial"]:$this->pc92_sequencial);
       $this->pc92_usuario = ($this->pc92_usuario == ""?@$GLOBALS["HTTP_POST_VARS"]["pc92_usuario"]:$this->pc92_usuario);
       if($this->pc92_datajulgamento == ""){
         $this->pc92_datajulgamento_dia = ($this->pc92_datajulgamento_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["pc92_datajulgamento_dia"]:$this->pc92_datajulgamento_dia);
         $this->pc92_datajulgamento_mes = ($this->pc92_datajulgamento_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["pc92_datajulgamento_mes"]:$this->pc92_datajulgamento_mes);
         $this->pc92_datajulgamento_ano = ($this->pc92_datajulgamento_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["pc92_datajulgamento_ano"]:$this->pc92_datajulgamento_ano);
         if($this->pc92_datajulgamento_dia != ""){
            $this->pc92_datajulgamento = $this->pc92_datajulgamento_ano."-".$this->pc92_datajulgamento_mes."-".$this->pc92_datajulgamento_dia;
         }
       }
       $this->pc92_hora = ($this->pc92_hora == ""?@$GLOBALS["HTTP_POST_VARS"]["pc92_hora"]:$this->pc92_hora);
       $this->pc92_ativo = ($this->pc92_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["pc92_ativo"]:$this->pc92_ativo);
     }else{
       $this->pc92_sequencial = ($this->pc92_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["pc92_sequencial"]:$this->pc92_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($pc92_sequencial){ 
      $this->atualizacampos();
     if($this->pc92_usuario == null ){ 
       $this->erro_sql = " Campo Usu�rio nao Informado.";
       $this->erro_campo = "pc92_usuario";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc92_datajulgamento == null ){ 
       $this->erro_sql = " Campo Data Julgamento nao Informado.";
       $this->erro_campo = "pc92_datajulgamento_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc92_hora == null ){ 
       $this->erro_sql = " Campo Hora do Julgamento nao Informado.";
       $this->erro_campo = "pc92_hora";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->pc92_ativo == null ){ 
       $this->erro_sql = " Campo Status do Julgamento nao Informado.";
       $this->erro_campo = "pc92_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($pc92_sequencial == "" || $pc92_sequencial == null ){
       $result = db_query("select nextval('pcorcamjulgamentolog_pc92_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: pcorcamjulgamentolog_pc92_sequencial_seq do campo: pc92_sequencial"; 
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->pc92_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from pcorcamjulgamentolog_pc92_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $pc92_sequencial)){
         $this->erro_sql = " Campo pc92_sequencial maior que �ltimo n�mero da sequencia.";
         $this->erro_banco = "Sequencia menor que este n�mero.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->pc92_sequencial = $pc92_sequencial; 
       }
     }
     if(($this->pc92_sequencial == null) || ($this->pc92_sequencial == "") ){ 
       $this->erro_sql = " Campo pc92_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into pcorcamjulgamentolog(
                                       pc92_sequencial 
                                      ,pc92_usuario 
                                      ,pc92_datajulgamento 
                                      ,pc92_hora 
                                      ,pc92_ativo 
                       )
                values (
                                $this->pc92_sequencial 
                               ,$this->pc92_usuario 
                               ,".($this->pc92_datajulgamento == "null" || $this->pc92_datajulgamento == ""?"null":"'".$this->pc92_datajulgamento."'")." 
                               ,'$this->pc92_hora' 
                               ,'$this->pc92_ativo' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Log do Julgamento ($this->pc92_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Log do Julgamento j� Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Log do Julgamento ($this->pc92_sequencial) nao Inclu�do. Inclusao Abortada.";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc92_sequencial;
     $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->pc92_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18829,'$this->pc92_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3339,18829,'','".AddSlashes(pg_result($resaco,0,'pc92_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3339,18832,'','".AddSlashes(pg_result($resaco,0,'pc92_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3339,18830,'','".AddSlashes(pg_result($resaco,0,'pc92_datajulgamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3339,18831,'','".AddSlashes(pg_result($resaco,0,'pc92_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3339,18833,'','".AddSlashes(pg_result($resaco,0,'pc92_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($pc92_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update pcorcamjulgamentolog set ";
     $virgula = "";
     if(trim($this->pc92_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc92_sequencial"])){ 
       $sql  .= $virgula." pc92_sequencial = $this->pc92_sequencial ";
       $virgula = ",";
       if(trim($this->pc92_sequencial) == null ){ 
         $this->erro_sql = " Campo C�digo nao Informado.";
         $this->erro_campo = "pc92_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc92_usuario)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc92_usuario"])){ 
       $sql  .= $virgula." pc92_usuario = $this->pc92_usuario ";
       $virgula = ",";
       if(trim($this->pc92_usuario) == null ){ 
         $this->erro_sql = " Campo Usu�rio nao Informado.";
         $this->erro_campo = "pc92_usuario";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc92_datajulgamento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc92_datajulgamento_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["pc92_datajulgamento_dia"] !="") ){ 
       $sql  .= $virgula." pc92_datajulgamento = '$this->pc92_datajulgamento' ";
       $virgula = ",";
       if(trim($this->pc92_datajulgamento) == null ){ 
         $this->erro_sql = " Campo Data Julgamento nao Informado.";
         $this->erro_campo = "pc92_datajulgamento_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["pc92_datajulgamento_dia"])){ 
         $sql  .= $virgula." pc92_datajulgamento = null ";
         $virgula = ",";
         if(trim($this->pc92_datajulgamento) == null ){ 
           $this->erro_sql = " Campo Data Julgamento nao Informado.";
           $this->erro_campo = "pc92_datajulgamento_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->pc92_hora)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc92_hora"])){ 
       $sql  .= $virgula." pc92_hora = '$this->pc92_hora' ";
       $virgula = ",";
       if(trim($this->pc92_hora) == null ){ 
         $this->erro_sql = " Campo Hora do Julgamento nao Informado.";
         $this->erro_campo = "pc92_hora";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->pc92_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["pc92_ativo"])){ 
       $sql  .= $virgula." pc92_ativo = '$this->pc92_ativo' ";
       $virgula = ",";
       if(trim($this->pc92_ativo) == null ){ 
         $this->erro_sql = " Campo Status do Julgamento nao Informado.";
         $this->erro_campo = "pc92_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($pc92_sequencial!=null){
       $sql .= " pc92_sequencial = $this->pc92_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->pc92_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18829,'$this->pc92_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc92_sequencial"]) || $this->pc92_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3339,18829,'".AddSlashes(pg_result($resaco,$conresaco,'pc92_sequencial'))."','$this->pc92_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc92_usuario"]) || $this->pc92_usuario != "")
           $resac = db_query("insert into db_acount values($acount,3339,18832,'".AddSlashes(pg_result($resaco,$conresaco,'pc92_usuario'))."','$this->pc92_usuario',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc92_datajulgamento"]) || $this->pc92_datajulgamento != "")
           $resac = db_query("insert into db_acount values($acount,3339,18830,'".AddSlashes(pg_result($resaco,$conresaco,'pc92_datajulgamento'))."','$this->pc92_datajulgamento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc92_hora"]) || $this->pc92_hora != "")
           $resac = db_query("insert into db_acount values($acount,3339,18831,'".AddSlashes(pg_result($resaco,$conresaco,'pc92_hora'))."','$this->pc92_hora',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["pc92_ativo"]) || $this->pc92_ativo != "")
           $resac = db_query("insert into db_acount values($acount,3339,18833,'".AddSlashes(pg_result($resaco,$conresaco,'pc92_ativo'))."','$this->pc92_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log do Julgamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc92_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log do Julgamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->pc92_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Altera��o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->pc92_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($pc92_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($pc92_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18829,'$pc92_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3339,18829,'','".AddSlashes(pg_result($resaco,$iresaco,'pc92_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3339,18832,'','".AddSlashes(pg_result($resaco,$iresaco,'pc92_usuario'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3339,18830,'','".AddSlashes(pg_result($resaco,$iresaco,'pc92_datajulgamento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3339,18831,'','".AddSlashes(pg_result($resaco,$iresaco,'pc92_hora'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3339,18833,'','".AddSlashes(pg_result($resaco,$iresaco,'pc92_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from pcorcamjulgamentolog
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($pc92_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " pc92_sequencial = $pc92_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Log do Julgamento nao Exclu�do. Exclus�o Abortada.\\n";
       $this->erro_sql .= "Valores : ".$pc92_sequencial;
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Log do Julgamento nao Encontrado. Exclus�o n�o Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$pc92_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclus�o efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$pc92_sequencial;
         $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
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
       $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $this->numrows = pg_numrows($result);
      if($this->numrows==0){
        $this->erro_banco = "";
        $this->erro_sql   = "Record Vazio na Tabela:pcorcamjulgamentolog";
        $this->erro_msg   = "Usu�rio: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $pc92_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamjulgamentolog ";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = pcorcamjulgamentolog.pc92_usuario";
     $sql2 = "";
     if($dbwhere==""){
       if($pc92_sequencial!=null ){
         $sql2 .= " where pcorcamjulgamentolog.pc92_sequencial = $pc92_sequencial "; 
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
   function sql_query_file ( $pc92_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from pcorcamjulgamentolog ";
     $sql2 = "";
     if($dbwhere==""){
       if($pc92_sequencial!=null ){
         $sql2 .= " where pcorcamjulgamentolog.pc92_sequencial = $pc92_sequencial "; 
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