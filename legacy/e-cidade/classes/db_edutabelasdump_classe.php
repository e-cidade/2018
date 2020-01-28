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

//MODULO: educação
//CLASSE DA ENTIDADE edutabelasdump
class cl_edutabelasdump { 
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
   var $ed130_i_codigo = 0; 
   var $ed130_c_tabela = null; 
   var $ed130_c_tipo = null; 
   var $ed130_c_dumpseq = null; 
   var $ed130_c_dumptrigger = null; 
   var $ed130_i_sequencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed130_i_codigo = int8 = Código 
                 ed130_c_tabela = char(50) = Tabela 
                 ed130_c_tipo = char(2) = Tipo do Dump 
                 ed130_c_dumpseq = char(1) = Criar sequencia 
                 ed130_c_dumptrigger = char(1) = Criar Trigger 
                 ed130_i_sequencia = int4 = Sequência 
                 ";
   //funcao construtor da classe 
   function cl_edutabelasdump() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("edutabelasdump"); 
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
       $this->ed130_i_codigo = ($this->ed130_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed130_i_codigo"]:$this->ed130_i_codigo);
       $this->ed130_c_tabela = ($this->ed130_c_tabela == ""?@$GLOBALS["HTTP_POST_VARS"]["ed130_c_tabela"]:$this->ed130_c_tabela);
       $this->ed130_c_tipo = ($this->ed130_c_tipo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed130_c_tipo"]:$this->ed130_c_tipo);
       $this->ed130_c_dumpseq = ($this->ed130_c_dumpseq == ""?@$GLOBALS["HTTP_POST_VARS"]["ed130_c_dumpseq"]:$this->ed130_c_dumpseq);
       $this->ed130_c_dumptrigger = ($this->ed130_c_dumptrigger == ""?@$GLOBALS["HTTP_POST_VARS"]["ed130_c_dumptrigger"]:$this->ed130_c_dumptrigger);
       $this->ed130_i_sequencia = ($this->ed130_i_sequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed130_i_sequencia"]:$this->ed130_i_sequencia);
     }else{
       $this->ed130_i_codigo = ($this->ed130_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed130_i_codigo"]:$this->ed130_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed130_i_codigo){ 
      $this->atualizacampos();
     if($this->ed130_c_tabela == null ){ 
       $this->erro_sql = " Campo Tabela nao Informado.";
       $this->erro_campo = "ed130_c_tabela";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed130_c_tipo == null ){ 
       $this->erro_sql = " Campo Tipo do Dump nao Informado.";
       $this->erro_campo = "ed130_c_tipo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed130_c_dumpseq == null ){ 
       $this->erro_sql = " Campo Criar sequencia nao Informado.";
       $this->erro_campo = "ed130_c_dumpseq";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed130_c_dumptrigger == null ){ 
       $this->erro_sql = " Campo Criar Trigger nao Informado.";
       $this->erro_campo = "ed130_c_dumptrigger";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed130_i_sequencia == null ){ 
       $this->erro_sql = " Campo Sequência nao Informado.";
       $this->erro_campo = "ed130_i_sequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed130_i_codigo == "" || $ed130_i_codigo == null ){
       $result = db_query("select nextval('edutabelasdump_ed130_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: edutabelasdump_ed130_i_codigo_seq do campo: ed130_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed130_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from edutabelasdump_ed130_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed130_i_codigo)){
         $this->erro_sql = " Campo ed130_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed130_i_codigo = $ed130_i_codigo; 
       }
     }
     if(($this->ed130_i_codigo == null) || ($this->ed130_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed130_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into edutabelasdump(
                                       ed130_i_codigo 
                                      ,ed130_c_tabela 
                                      ,ed130_c_tipo 
                                      ,ed130_c_dumpseq 
                                      ,ed130_c_dumptrigger 
                                      ,ed130_i_sequencia 
                       )
                values (
                                $this->ed130_i_codigo 
                               ,'$this->ed130_c_tabela' 
                               ,'$this->ed130_c_tipo' 
                               ,'$this->ed130_c_dumpseq' 
                               ,'$this->ed130_c_dumptrigger' 
                               ,$this->ed130_i_sequencia 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Tabelas para dump ($this->ed130_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Tabelas para dump já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Tabelas para dump ($this->ed130_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed130_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed130_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1009227,'$this->ed130_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010190,1009227,'','".AddSlashes(pg_result($resaco,0,'ed130_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010190,1009228,'','".AddSlashes(pg_result($resaco,0,'ed130_c_tabela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010190,1009229,'','".AddSlashes(pg_result($resaco,0,'ed130_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010190,1009230,'','".AddSlashes(pg_result($resaco,0,'ed130_c_dumpseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010190,1009236,'','".AddSlashes(pg_result($resaco,0,'ed130_c_dumptrigger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010190,1009231,'','".AddSlashes(pg_result($resaco,0,'ed130_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed130_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update edutabelasdump set ";
     $virgula = "";
     if(trim($this->ed130_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed130_i_codigo"])){ 
       $sql  .= $virgula." ed130_i_codigo = $this->ed130_i_codigo ";
       $virgula = ",";
       if(trim($this->ed130_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed130_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed130_c_tabela)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed130_c_tabela"])){ 
       $sql  .= $virgula." ed130_c_tabela = '$this->ed130_c_tabela' ";
       $virgula = ",";
       if(trim($this->ed130_c_tabela) == null ){ 
         $this->erro_sql = " Campo Tabela nao Informado.";
         $this->erro_campo = "ed130_c_tabela";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed130_c_tipo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed130_c_tipo"])){ 
       $sql  .= $virgula." ed130_c_tipo = '$this->ed130_c_tipo' ";
       $virgula = ",";
       if(trim($this->ed130_c_tipo) == null ){ 
         $this->erro_sql = " Campo Tipo do Dump nao Informado.";
         $this->erro_campo = "ed130_c_tipo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed130_c_dumpseq)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed130_c_dumpseq"])){ 
       $sql  .= $virgula." ed130_c_dumpseq = '$this->ed130_c_dumpseq' ";
       $virgula = ",";
       if(trim($this->ed130_c_dumpseq) == null ){ 
         $this->erro_sql = " Campo Criar sequencia nao Informado.";
         $this->erro_campo = "ed130_c_dumpseq";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed130_c_dumptrigger)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed130_c_dumptrigger"])){ 
       $sql  .= $virgula." ed130_c_dumptrigger = '$this->ed130_c_dumptrigger' ";
       $virgula = ",";
       if(trim($this->ed130_c_dumptrigger) == null ){ 
         $this->erro_sql = " Campo Criar Trigger nao Informado.";
         $this->erro_campo = "ed130_c_dumptrigger";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed130_i_sequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed130_i_sequencia"])){ 
       $sql  .= $virgula." ed130_i_sequencia = $this->ed130_i_sequencia ";
       $virgula = ",";
       if(trim($this->ed130_i_sequencia) == null ){ 
         $this->erro_sql = " Campo Sequência nao Informado.";
         $this->erro_campo = "ed130_i_sequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed130_i_codigo!=null){
       $sql .= " ed130_i_codigo = $this->ed130_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed130_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009227,'$this->ed130_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed130_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010190,1009227,'".AddSlashes(pg_result($resaco,$conresaco,'ed130_i_codigo'))."','$this->ed130_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed130_c_tabela"]))
           $resac = db_query("insert into db_acount values($acount,1010190,1009228,'".AddSlashes(pg_result($resaco,$conresaco,'ed130_c_tabela'))."','$this->ed130_c_tabela',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed130_c_tipo"]))
           $resac = db_query("insert into db_acount values($acount,1010190,1009229,'".AddSlashes(pg_result($resaco,$conresaco,'ed130_c_tipo'))."','$this->ed130_c_tipo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed130_c_dumpseq"]))
           $resac = db_query("insert into db_acount values($acount,1010190,1009230,'".AddSlashes(pg_result($resaco,$conresaco,'ed130_c_dumpseq'))."','$this->ed130_c_dumpseq',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed130_c_dumptrigger"]))
           $resac = db_query("insert into db_acount values($acount,1010190,1009236,'".AddSlashes(pg_result($resaco,$conresaco,'ed130_c_dumptrigger'))."','$this->ed130_c_dumptrigger',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed130_i_sequencia"]))
           $resac = db_query("insert into db_acount values($acount,1010190,1009231,'".AddSlashes(pg_result($resaco,$conresaco,'ed130_i_sequencia'))."','$this->ed130_i_sequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabelas para dump nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed130_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabelas para dump nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed130_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed130_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed130_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed130_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1009227,'$ed130_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010190,1009227,'','".AddSlashes(pg_result($resaco,$iresaco,'ed130_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010190,1009228,'','".AddSlashes(pg_result($resaco,$iresaco,'ed130_c_tabela'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010190,1009229,'','".AddSlashes(pg_result($resaco,$iresaco,'ed130_c_tipo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010190,1009230,'','".AddSlashes(pg_result($resaco,$iresaco,'ed130_c_dumpseq'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010190,1009236,'','".AddSlashes(pg_result($resaco,$iresaco,'ed130_c_dumptrigger'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010190,1009231,'','".AddSlashes(pg_result($resaco,$iresaco,'ed130_i_sequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from edutabelasdump
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed130_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed130_i_codigo = $ed130_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Tabelas para dump nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed130_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Tabelas para dump nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed130_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed130_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:edutabelasdump";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed130_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from edutabelasdump ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed130_i_codigo!=null ){
         $sql2 .= " where edutabelasdump.ed130_i_codigo = $ed130_i_codigo "; 
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
   function sql_query_file ( $ed130_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from edutabelasdump ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed130_i_codigo!=null ){
         $sql2 .= " where edutabelasdump.ed130_i_codigo = $ed130_i_codigo "; 
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