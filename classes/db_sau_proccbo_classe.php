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

//MODULO: saude
//CLASSE DA ENTIDADE sau_proccbo
class cl_sau_proccbo { 
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
   var $sd96_i_codigo = 0; 
   var $sd96_i_procedimento = 0; 
   var $sd96_i_cbo = 0; 
   var $sd96_i_anocomp = 0; 
   var $sd96_i_mescomp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd96_i_codigo = int8 = Código 
                 sd96_i_procedimento = int8 = Procedimento 
                 sd96_i_cbo = int4 = CBO 
                 sd96_i_anocomp = int4 = Ano 
                 sd96_i_mescomp = int4 = Mes 
                 ";
   //funcao construtor da classe 
   function cl_sau_proccbo() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_proccbo"); 
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
       $this->sd96_i_codigo = ($this->sd96_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd96_i_codigo"]:$this->sd96_i_codigo);
       $this->sd96_i_procedimento = ($this->sd96_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd96_i_procedimento"]:$this->sd96_i_procedimento);
       $this->sd96_i_cbo = ($this->sd96_i_cbo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd96_i_cbo"]:$this->sd96_i_cbo);
       $this->sd96_i_anocomp = ($this->sd96_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd96_i_anocomp"]:$this->sd96_i_anocomp);
       $this->sd96_i_mescomp = ($this->sd96_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd96_i_mescomp"]:$this->sd96_i_mescomp);
     }else{
       $this->sd96_i_codigo = ($this->sd96_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd96_i_codigo"]:$this->sd96_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd96_i_codigo){ 
      $this->atualizacampos();
     if($this->sd96_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento nao Informado.";
       $this->erro_campo = "sd96_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd96_i_cbo == null ){ 
       $this->erro_sql = " Campo CBO nao Informado.";
       $this->erro_campo = "sd96_i_cbo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd96_i_anocomp == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "sd96_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd96_i_mescomp == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "sd96_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd96_i_codigo == "" || $sd96_i_codigo == null ){
       $result = db_query("select nextval('sau_proccbo_sd96_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_proccbo_sd96_i_codigo_seq do campo: sd96_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd96_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_proccbo_sd96_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd96_i_codigo)){
         $this->erro_sql = " Campo sd96_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd96_i_codigo = $sd96_i_codigo; 
       }
     }
     if(($this->sd96_i_codigo == null) || ($this->sd96_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd96_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_proccbo(
                                       sd96_i_codigo 
                                      ,sd96_i_procedimento 
                                      ,sd96_i_cbo 
                                      ,sd96_i_anocomp 
                                      ,sd96_i_mescomp 
                       )
                values (
                                $this->sd96_i_codigo 
                               ,$this->sd96_i_procedimento 
                               ,$this->sd96_i_cbo 
                               ,$this->sd96_i_anocomp 
                               ,$this->sd96_i_mescomp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimento Cbo ($this->sd96_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimento Cbo já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimento Cbo ($this->sd96_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd96_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd96_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11668,'$this->sd96_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2008,11668,'','".AddSlashes(pg_result($resaco,0,'sd96_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2008,11669,'','".AddSlashes(pg_result($resaco,0,'sd96_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2008,11670,'','".AddSlashes(pg_result($resaco,0,'sd96_i_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2008,11671,'','".AddSlashes(pg_result($resaco,0,'sd96_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2008,11672,'','".AddSlashes(pg_result($resaco,0,'sd96_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd96_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_proccbo set ";
     $virgula = "";
     if(trim($this->sd96_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd96_i_codigo"])){ 
       $sql  .= $virgula." sd96_i_codigo = $this->sd96_i_codigo ";
       $virgula = ",";
       if(trim($this->sd96_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd96_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd96_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd96_i_procedimento"])){ 
       $sql  .= $virgula." sd96_i_procedimento = $this->sd96_i_procedimento ";
       $virgula = ",";
       if(trim($this->sd96_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento nao Informado.";
         $this->erro_campo = "sd96_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd96_i_cbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd96_i_cbo"])){ 
       $sql  .= $virgula." sd96_i_cbo = $this->sd96_i_cbo ";
       $virgula = ",";
       if(trim($this->sd96_i_cbo) == null ){ 
         $this->erro_sql = " Campo CBO nao Informado.";
         $this->erro_campo = "sd96_i_cbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd96_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd96_i_anocomp"])){ 
       $sql  .= $virgula." sd96_i_anocomp = $this->sd96_i_anocomp ";
       $virgula = ",";
       if(trim($this->sd96_i_anocomp) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "sd96_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd96_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd96_i_mescomp"])){ 
       $sql  .= $virgula." sd96_i_mescomp = $this->sd96_i_mescomp ";
       $virgula = ",";
       if(trim($this->sd96_i_mescomp) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "sd96_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd96_i_codigo!=null){
       $sql .= " sd96_i_codigo = $this->sd96_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd96_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11668,'$this->sd96_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd96_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2008,11668,'".AddSlashes(pg_result($resaco,$conresaco,'sd96_i_codigo'))."','$this->sd96_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd96_i_procedimento"]))
           $resac = db_query("insert into db_acount values($acount,2008,11669,'".AddSlashes(pg_result($resaco,$conresaco,'sd96_i_procedimento'))."','$this->sd96_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd96_i_cbo"]))
           $resac = db_query("insert into db_acount values($acount,2008,11670,'".AddSlashes(pg_result($resaco,$conresaco,'sd96_i_cbo'))."','$this->sd96_i_cbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd96_i_anocomp"]))
           $resac = db_query("insert into db_acount values($acount,2008,11671,'".AddSlashes(pg_result($resaco,$conresaco,'sd96_i_anocomp'))."','$this->sd96_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd96_i_mescomp"]))
           $resac = db_query("insert into db_acount values($acount,2008,11672,'".AddSlashes(pg_result($resaco,$conresaco,'sd96_i_mescomp'))."','$this->sd96_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimento Cbo nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd96_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimento Cbo nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd96_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd96_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd96_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd96_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11668,'$sd96_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2008,11668,'','".AddSlashes(pg_result($resaco,$iresaco,'sd96_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2008,11669,'','".AddSlashes(pg_result($resaco,$iresaco,'sd96_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2008,11670,'','".AddSlashes(pg_result($resaco,$iresaco,'sd96_i_cbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2008,11671,'','".AddSlashes(pg_result($resaco,$iresaco,'sd96_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2008,11672,'','".AddSlashes(pg_result($resaco,$iresaco,'sd96_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_proccbo
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd96_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd96_i_codigo = $sd96_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimento Cbo nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd96_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimento Cbo nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd96_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd96_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_proccbo";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd96_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_proccbo ";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = sau_proccbo.sd96_i_cbo";
     $sql .= "      inner join sau_procedimento  on  sau_procedimento.sd63_i_codigo = sau_proccbo.sd96_i_procedimento";
     $sql .= "      left join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
     $sql .= "      left join sau_rubrica        on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
     $sql .= "      left join sau_complexidade   on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade";
     $sql2 = "";
     if($dbwhere==""){
       if($sd96_i_codigo!=null ){
         $sql2 .= " where sau_proccbo.sd96_i_codigo = $sd96_i_codigo ";
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
   function sql_query_file ( $sd96_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_proccbo ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd96_i_codigo!=null ){
         $sql2 .= " where sau_proccbo.sd96_i_codigo = $sd96_i_codigo ";
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

  /*  
   * Obtem o procedimento de acordo com o procedimento e a especialidade informados
   * 
   * @param   string   $sSd63_c_procedimento    procedimento
   * @param   int      $iRh70_sequencial        codigo da especialidade
   * @return  string   sql da busca
   */
  function sql_query_procedimento_especialidade($sSd63_c_procedimento = null, $iRh70_sequencial, $sCampos = "*",
                                                $sOrdem = "" ,
                                                $sDbwhere = "") {
 
    $sSql  = 'select ';
    if($sCampos != "*" ) {

      $aCampos_sql = split("#",$sCampos);
      $sVirgula = "";
      for($iCont = 0; $iCont < sizeof($aCampos_sql); $iCont++) {

        $sSql .= $sVirgula.$aCampos_sql[$iCont];
        $sVirgula = ",";
         
      }

    } else {
      $sSql .= $sCampos;
    }
    $sSql .= '  from sau_proccbo ';
    $sSql .= '    inner join rhcbo on rhcbo.rh70_sequencial = sau_proccbo.sd96_i_cbo ';
    $sSql .= '    inner join sau_procedimento on sau_procedimento.sd63_i_codigo = sau_proccbo.sd96_i_procedimento ';
    $sSql .= "      where sd96_i_cbo = $iRh70_sequencial ";
    /* Descomentar para filtrar por servico 
    $oDaoSau_config = db_utils::getdao('sau_config_ext');
    $sSqlSau_config = $oDaoSau_config->sql_query_ext(null, ' s103_c_servicoproc ');
    $rsSau_config = $oDaoSau_config->sql_record($sSqlSau_config);
    $oDadosSau_config = db_utils::fieldsmemory($rsSau_config, 0);
    if($oDadosSau_config->s103_c_servicoproc == 'S') {

      $sSql .= '        and sau_procedimento.sd63_i_codigo in ';
      $sSql .= '        (select sd88_i_procedimento ';
      $sSql .= '           from sau_procservico ';
      $sSql .= '             inner join ';
      $sSql .= '             (select (select sd87_i_codigo ';
      $sSql .= '                        from sau_servclassificacao ';
      $sSql .= '                          where sd87_c_classificacao = x.sd87_c_classificacao ';
      $sSql .= '                            and sd87_i_servico in ';
      $sSql .= '                            (select sd86_i_codigo ';
      $sSql .= '                               from sau_servico ';
      $sSql .= '                                 where sd86_c_servico = x.sd86_c_servico ';
      $sSql .= '                                   order by sd86_i_anocomp desc , sd86_i_mescomp desc ';
      $sSql .= '                                     limit 1 ) ) ';
      $sSql .= '                     as sd87_i_codigo ';
      $sSql .= '                from (select sd87_c_classificacao,';
      $sSql .= '                             sd86_c_servico ';
      $sSql .= '                        from unidadeservicos ';
      $sSql .= '                          inner join sau_servclassificacao on ';
      $sSql .= '                            sau_servclassificacao.sd87_i_codigo = unidadeservicos.s126_i_servico ';
      $sSql .= '                          inner join sau_servico on ';
      $sSql .= '                            sau_servico.sd86_i_codigo = sau_servclassificacao.sd87_i_servico) as x) as y';
      $sSql .= '             on sau_procservico.sd88_i_classificacao = y.sd87_i_codigo) ';

    }
    */
    if(!empty($sSd63_c_procedimento)) {
      $sSql .= "        and sau_procedimento.sd63_c_procedimento = '$sSd63_c_procedimento' ";
    }

    if(!empty($sDbwhere)) {
      $sSql .= 'and ';
    }
    if(!empty($sOrdem)) {
      $sOrdem = 'order by '.$sOrdem;
    }
    $sSql .= $sDbwhere.' '.$sOrdem;

    return $sSql;

  }

  function sql_query_func($iCodigo = null, $sCampos = '*', $sOrdem = null, $sDbWhere = '', $intUnidade,
                                                          $lFiltraServico = true , $sEspec = '',$lProcSemCBO = true) {

    $sSql = 'select ';
    if ($sCampos != '*') {

      $sCamposSql = split('#', $sCampos);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++){

        $sSql .= $sVirgula.$sCamposSql[$iCont];
        $virgula = ",";

      }

    } else {
      $sSql .= $sCampos;
    }
    $sWhere = '';
    if ($sDbWhere == '') {

      if ($iCodigo != null ){
        $sWhere .= " where sd96_i_codigo = $iCodigo ";
      }

    } else {
      $sWhere .= $sDbWhere;
    }
    //Sau_Config
    $clsau_config  = db_utils::getDao("sau_config_ext");
    $resSau_config = $clsau_config->sql_record($clsau_config->sql_query_ext());
    $objSau_config = db_utils::fieldsMemory($resSau_config,0 );
    if ( $objSau_config->s103_i_modalidade > 0 ) {

      if($sWhere != ''){
        $sWhere .= ' and ';
      }
      $sWhere .= " sd82_c_modalidade = '{$objSau_config->sd82_c_modalidade}'";
       
    }
    if ( $objSau_config->s103_i_todacomp == 2 || $objSau_config->s103_i_modalidade > 0 ) { //última competência

      if($sWhere != ''){
        $sWhere .= ' and ';
      }
    	//sau_atualiza
      $clsau_atualiza  = db_utils::getDao("sau_atualiza");
      $resSau_atualiza = $clsau_atualiza->sql_record($clsau_atualiza->sql_query(null, "*", "s100_i_codigo desc limit 1"));
      $objSau_atualiza = db_utils::fieldsMemory($resSau_atualiza,0);
      $sWhere .= " sd63_i_anocomp    = {$objSau_atualiza->s100_i_anocomp}"; 
      $sWhere .= " and sd63_i_mescomp    = {$objSau_atualiza->s100_i_mescomp}"; 
       
    }
    if ( $objSau_config->s103_c_servicoproc == 'S' && $lFiltraServico) {
     	
       $intUnidade = (int)$intUnidade==0?DB_getsession("DB_coddepto"):$intUnidade;
       
       if($sWhere != ''){
         $sWhere .= ' and ';
       }
       $sWhere .= " sau_procedimento.sd63_i_codigo in ( ";
       $sWhere .="select sd88_i_procedimento
                 from sau_procservico
                 inner join (select (select sd87_i_codigo
                                     from sau_servclassificacao
                                     where sd87_c_classificacao = x.sd87_c_classificacao
                                     and sd87_i_servico in (select sd86_i_codigo
                                                            from sau_servico
                                                            where sd86_c_servico = x.sd86_c_servico
                                                            order by  sd86_i_anocomp desc ,
                                                                      sd86_i_mescomp desc
                                                            limit 1
                                                           )
                                     ) as sd87_i_codigo
                            from (select sd87_c_classificacao,
                                          sd86_c_servico
                                   from unidadeservicos
                                   inner join sau_servclassificacao on sau_servclassificacao.sd87_i_codigo = unidadeservicos.s126_i_servico
                                   inner join sau_servico           on sau_servico.sd86_i_codigo = sau_servclassificacao.sd87_i_servico
                                   where unidadeservicos.s126_i_unidade = $intUnidade
                                  ) as x
                            ) as y on sau_procservico.sd88_i_classificacao = y.sd87_i_codigo)";
        }
    
    $sSql .= " from ((select distinct null::integer as sd96_i_codigo,";
    $sSql .= "               sd63_i_codigo, ";
    $sSql .= "               sau_procedimento.sd63_c_procedimento, ";
    $sSql .= "               sau_procedimento.sd63_c_nome, ";
    $sSql .= "               null::integer as sd96_i_cbo, ";
    $sSql .= "               sd63_i_anocomp as sd96_i_anocomp, ";
    $sSql .= "               sd63_i_mescomp as sd96_i_mescomp ";
    $sSql .= " from sau_procedimento ";
    $sSql .= "   left join sau_procmodalidade on  sau_procmodalidade.sd83_i_procedimento = sau_procedimento.sd63_i_codigo";
    $sSql .= "   left join sau_modalidade     on  sau_modalidade.sd82_i_codigo = sau_procmodalidade.sd83_i_modalidade";
    $sSql .= "   left join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
    $sSql .= "   left join sau_rubrica        on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
    $sSql .= "   left join sau_complexidade   on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade ";
    $sSql .= " where not exists (select sd96_i_procedimento from sau_proccbo where sd96_i_procedimento = sd63_i_codigo)";
    if($lProcSemCBO == false){
      $sWhere1 = ' 1 = 2';
    } else {
      $sWhere1 = '';
    }
    if($sWhere1 != ''){
      $sSql .= " and ".$sWhere1;
    } elseif ($sWhere != '') { 
      $sSql .= " and ".$sWhere;
    }
    
    $sSql .= "      ) ";
    $sSql .= "        union ";
    $sSql .= "     (select distinct sau_proccbo.sd96_i_codigo, ";
    $sSql .= "             sd63_i_codigo, ";
    $sSql .= "             sau_procedimento.sd63_c_procedimento, ";
    $sSql .= "             sau_procedimento.sd63_c_nome, ";
    $sSql .= "             sau_proccbo.sd96_i_cbo, ";
    $sSql .= "             sau_proccbo.sd96_i_anocomp, ";
    $sSql .= "             sau_proccbo.sd96_i_mescomp ";
    $sSql .= " from sau_proccbo ";
    $sSql .= "  inner join rhcbo on rhcbo.rh70_sequencial = sau_proccbo.sd96_i_cbo ";
    $sSql .= "  inner join sau_procedimento on sau_procedimento.sd63_i_codigo = sau_proccbo.sd96_i_procedimento ";
    $sSql .= "  left join sau_procmodalidade on  sau_procmodalidade.sd83_i_procedimento = sau_procedimento.sd63_i_codigo";
    $sSql .= "  left join sau_modalidade     on  sau_modalidade.sd82_i_codigo = sau_procmodalidade.sd83_i_modalidade";
    $sSql .= "  left join sau_financiamento  on  sau_financiamento.sd65_i_codigo = sau_procedimento.sd63_i_financiamento";
    $sSql .= "  left join sau_rubrica        on  sau_rubrica.sd64_i_codigo = sau_procedimento.sd63_i_rubrica";
    $sSql .= "  left join sau_complexidade   on  sau_complexidade.sd69_i_codigo = sau_procedimento.sd63_i_complexidade ";
    if ($sEspec != '') {
      if ($sWhere == '') {
        $sWhere  = " sd96_i_cbo = ".$sEspec;
      } else {
        $sWhere .= " and sd96_i_cbo = ".$sEspec;
      }
    }
    if ($sWhere != '') {
      $sSql .= " where $sWhere )) as geral ";
    } else {
      $sSql .= " )) as geral ";
    }
    $sSql2 = '';
    
    $sSql .= $sSql2;

    if ($sOrdem != null) {

      $sSql      .= ' order by ';
      $sCamposSql = split('#', $sOrdem);
      $sVirgula   = '';
      for ($iCont = 0; $iCont < sizeof($sCamposSql); $iCont++) {

        $sSql    .= $sVirgula.$sCamposSql[$iCont];
        $sVirgula = ',';

      }

    }

    return $sSql;

  }
}
?>