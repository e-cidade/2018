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

//MODULO: juridico
//CLASSE DA ENTIDADE partilhaarquivo
class cl_partilhaarquivo { 
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
   var $v78_sequencial = 0; 
   var $v78_nomearq = null; 
   var $v78_dtgeracao_dia = null; 
   var $v78_dtgeracao_mes = null; 
   var $v78_dtgeracao_ano = null; 
   var $v78_dtgeracao = null; 
   var $v78_tipoarq = 0; 
   var $v78_arquivo = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 v78_sequencial = int4 = Sequencial 
                 v78_nomearq = varchar(50) = Arquivo de Remessa 
                 v78_dtgeracao = date = Data de Geração 
                 v78_tipoarq = int4 = Tipo do Arquivo 
                 v78_arquivo = oid = Arquivo 
                 ";
   //funcao construtor da classe 
   function cl_partilhaarquivo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("partilhaarquivo"); 
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
       $this->v78_sequencial = ($this->v78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v78_sequencial"]:$this->v78_sequencial);
       $this->v78_nomearq = ($this->v78_nomearq == ""?@$GLOBALS["HTTP_POST_VARS"]["v78_nomearq"]:$this->v78_nomearq);
       if($this->v78_dtgeracao == ""){
         $this->v78_dtgeracao_dia = ($this->v78_dtgeracao_dia == ""?@$GLOBALS["HTTP_POST_VARS"]["v78_dtgeracao_dia"]:$this->v78_dtgeracao_dia);
         $this->v78_dtgeracao_mes = ($this->v78_dtgeracao_mes == ""?@$GLOBALS["HTTP_POST_VARS"]["v78_dtgeracao_mes"]:$this->v78_dtgeracao_mes);
         $this->v78_dtgeracao_ano = ($this->v78_dtgeracao_ano == ""?@$GLOBALS["HTTP_POST_VARS"]["v78_dtgeracao_ano"]:$this->v78_dtgeracao_ano);
         if($this->v78_dtgeracao_dia != ""){
            $this->v78_dtgeracao = $this->v78_dtgeracao_ano."-".$this->v78_dtgeracao_mes."-".$this->v78_dtgeracao_dia;
         }
       }
       $this->v78_tipoarq = ($this->v78_tipoarq == ""?@$GLOBALS["HTTP_POST_VARS"]["v78_tipoarq"]:$this->v78_tipoarq);
       $this->v78_arquivo = ($this->v78_arquivo == ""?@$GLOBALS["HTTP_POST_VARS"]["v78_arquivo"]:$this->v78_arquivo);
     }else{
       $this->v78_sequencial = ($this->v78_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["v78_sequencial"]:$this->v78_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($v78_sequencial){ 
      $this->atualizacampos();
     if($this->v78_nomearq == null ){ 
       $this->erro_sql = " Campo Arquivo de Remessa nao Informado.";
       $this->erro_campo = "v78_nomearq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v78_dtgeracao == null ){ 
       $this->erro_sql = " Campo Data de Geração nao Informado.";
       $this->erro_campo = "v78_dtgeracao_dia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v78_tipoarq == null ){ 
       $this->erro_sql = " Campo Tipo do Arquivo nao Informado.";
       $this->erro_campo = "v78_tipoarq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->v78_arquivo == null ){ 
       $this->erro_sql = " Campo Arquivo nao Informado.";
       $this->erro_campo = "v78_arquivo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($v78_sequencial == "" || $v78_sequencial == null ){
       $result = db_query("select nextval('partilhaarquivo_v78_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: partilhaarquivo_v78_sequencial_seq do campo: v78_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->v78_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from partilhaarquivo_v78_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $v78_sequencial)){
         $this->erro_sql = " Campo v78_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->v78_sequencial = $v78_sequencial; 
       }
     }
     if(($this->v78_sequencial == null) || ($this->v78_sequencial == "") ){ 
       $this->erro_sql = " Campo v78_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into partilhaarquivo(
                                       v78_sequencial 
                                      ,v78_nomearq 
                                      ,v78_dtgeracao 
                                      ,v78_tipoarq 
                                      ,v78_arquivo 
                       )
                values (
                                $this->v78_sequencial 
                               ,'$this->v78_nomearq' 
                               ,".($this->v78_dtgeracao == "null" || $this->v78_dtgeracao == ""?"null":"'".$this->v78_dtgeracao."'")." 
                               ,$this->v78_tipoarq 
                               ,$this->v78_arquivo 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Arquivo gerado para partilha ($this->v78_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Arquivo gerado para partilha já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Arquivo gerado para partilha ($this->v78_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v78_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->v78_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18257,'$this->v78_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3231,18257,'','".AddSlashes(pg_result($resaco,0,'v78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3231,18268,'','".AddSlashes(pg_result($resaco,0,'v78_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3231,18267,'','".AddSlashes(pg_result($resaco,0,'v78_dtgeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3231,18269,'','".AddSlashes(pg_result($resaco,0,'v78_tipoarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3231,19735,'','".AddSlashes(pg_result($resaco,0,'v78_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($v78_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update partilhaarquivo set ";
     $virgula = "";
     if(trim($this->v78_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v78_sequencial"])){ 
       $sql  .= $virgula." v78_sequencial = $this->v78_sequencial ";
       $virgula = ",";
       if(trim($this->v78_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "v78_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v78_nomearq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v78_nomearq"])){ 
       $sql  .= $virgula." v78_nomearq = '$this->v78_nomearq' ";
       $virgula = ",";
       if(trim($this->v78_nomearq) == null ){ 
         $this->erro_sql = " Campo Arquivo de Remessa nao Informado.";
         $this->erro_campo = "v78_nomearq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v78_dtgeracao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v78_dtgeracao_dia"]) &&  ($GLOBALS["HTTP_POST_VARS"]["v78_dtgeracao_dia"] !="") ){ 
       $sql  .= $virgula." v78_dtgeracao = '$this->v78_dtgeracao' ";
       $virgula = ",";
       if(trim($this->v78_dtgeracao) == null ){ 
         $this->erro_sql = " Campo Data de Geração nao Informado.";
         $this->erro_campo = "v78_dtgeracao_dia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }     else{ 
       if(isset($GLOBALS["HTTP_POST_VARS"]["v78_dtgeracao_dia"])){ 
         $sql  .= $virgula." v78_dtgeracao = null ";
         $virgula = ",";
         if(trim($this->v78_dtgeracao) == null ){ 
           $this->erro_sql = " Campo Data de Geração nao Informado.";
           $this->erro_campo = "v78_dtgeracao_dia";
           $this->erro_banco = "";
           $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
           $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
           $this->erro_status = "0";
           return false;
         }
       }
     }
     if(trim($this->v78_tipoarq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v78_tipoarq"])){ 
       $sql  .= $virgula." v78_tipoarq = $this->v78_tipoarq ";
       $virgula = ",";
       if(trim($this->v78_tipoarq) == null ){ 
         $this->erro_sql = " Campo Tipo do Arquivo nao Informado.";
         $this->erro_campo = "v78_tipoarq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->v78_arquivo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["v78_arquivo"])){ 
       $sql  .= $virgula." v78_arquivo = $this->v78_arquivo ";
       $virgula = ",";
       if(trim($this->v78_arquivo) == null ){ 
         $this->erro_sql = " Campo Arquivo nao Informado.";
         $this->erro_campo = "v78_arquivo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($v78_sequencial!=null){
       $sql .= " v78_sequencial = $this->v78_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->v78_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18257,'$this->v78_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v78_sequencial"]) || $this->v78_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3231,18257,'".AddSlashes(pg_result($resaco,$conresaco,'v78_sequencial'))."','$this->v78_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v78_nomearq"]) || $this->v78_nomearq != "")
           $resac = db_query("insert into db_acount values($acount,3231,18268,'".AddSlashes(pg_result($resaco,$conresaco,'v78_nomearq'))."','$this->v78_nomearq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v78_dtgeracao"]) || $this->v78_dtgeracao != "")
           $resac = db_query("insert into db_acount values($acount,3231,18267,'".AddSlashes(pg_result($resaco,$conresaco,'v78_dtgeracao'))."','$this->v78_dtgeracao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v78_tipoarq"]) || $this->v78_tipoarq != "")
           $resac = db_query("insert into db_acount values($acount,3231,18269,'".AddSlashes(pg_result($resaco,$conresaco,'v78_tipoarq'))."','$this->v78_tipoarq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["v78_arquivo"]) || $this->v78_arquivo != "")
           $resac = db_query("insert into db_acount values($acount,3231,19735,'".AddSlashes(pg_result($resaco,$conresaco,'v78_arquivo'))."','$this->v78_arquivo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo gerado para partilha nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->v78_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo gerado para partilha nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->v78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->v78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($v78_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($v78_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18257,'$v78_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3231,18257,'','".AddSlashes(pg_result($resaco,$iresaco,'v78_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3231,18268,'','".AddSlashes(pg_result($resaco,$iresaco,'v78_nomearq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3231,18267,'','".AddSlashes(pg_result($resaco,$iresaco,'v78_dtgeracao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3231,18269,'','".AddSlashes(pg_result($resaco,$iresaco,'v78_tipoarq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3231,19735,'','".AddSlashes(pg_result($resaco,$iresaco,'v78_arquivo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from partilhaarquivo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($v78_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " v78_sequencial = $v78_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Arquivo gerado para partilha nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$v78_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Arquivo gerado para partilha nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$v78_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$v78_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:partilhaarquivo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $v78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from partilhaarquivo ";
     $sql2 = "";
     if($dbwhere==""){
       if($v78_sequencial!=null ){
         $sql2 .= " where partilhaarquivo.v78_sequencial = $v78_sequencial "; 
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
   function sql_query_file ( $v78_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from partilhaarquivo ";
     $sql2 = "";
     if($dbwhere==""){
       if($v78_sequencial!=null ){
         $sql2 .= " where partilhaarquivo.v78_sequencial = $v78_sequencial "; 
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