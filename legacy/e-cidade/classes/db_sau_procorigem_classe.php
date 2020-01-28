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

//MODULO: saude
//CLASSE DA ENTIDADE sau_procorigem
class cl_sau_procorigem { 
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
   var $sd95_i_codigo = 0; 
   var $sd95_i_procedimento = 0; 
   var $sd95_i_origem = 0; 
   var $sd95_i_anocomp = 0; 
   var $sd95_i_mescomp = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd95_i_codigo = int8 = Código 
                 sd95_i_procedimento = int8 = Procedimento 
                 sd95_i_origem = int8 = Procedimento de Origem 
                 sd95_i_anocomp = int4 = Ano 
                 sd95_i_mescomp = int4 = Mes 
                 ";
   //funcao construtor da classe 
   function cl_sau_procorigem() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("sau_procorigem"); 
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
       $this->sd95_i_codigo = ($this->sd95_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd95_i_codigo"]:$this->sd95_i_codigo);
       $this->sd95_i_procedimento = ($this->sd95_i_procedimento == ""?@$GLOBALS["HTTP_POST_VARS"]["sd95_i_procedimento"]:$this->sd95_i_procedimento);
       $this->sd95_i_origem = ($this->sd95_i_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["sd95_i_origem"]:$this->sd95_i_origem);
       $this->sd95_i_anocomp = ($this->sd95_i_anocomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd95_i_anocomp"]:$this->sd95_i_anocomp);
       $this->sd95_i_mescomp = ($this->sd95_i_mescomp == ""?@$GLOBALS["HTTP_POST_VARS"]["sd95_i_mescomp"]:$this->sd95_i_mescomp);
     }else{
       $this->sd95_i_codigo = ($this->sd95_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd95_i_codigo"]:$this->sd95_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd95_i_codigo){ 
      $this->atualizacampos();
     if($this->sd95_i_procedimento == null ){ 
       $this->erro_sql = " Campo Procedimento nao Informado.";
       $this->erro_campo = "sd95_i_procedimento";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd95_i_origem == null ){ 
       $this->erro_sql = " Campo Procedimento de Origem nao Informado.";
       $this->erro_campo = "sd95_i_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd95_i_anocomp == null ){ 
       $this->erro_sql = " Campo Ano nao Informado.";
       $this->erro_campo = "sd95_i_anocomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd95_i_mescomp == null ){ 
       $this->erro_sql = " Campo Mes nao Informado.";
       $this->erro_campo = "sd95_i_mescomp";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd95_i_codigo == "" || $sd95_i_codigo == null ){
       $result = db_query("select nextval('sau_procorigem_sd95_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: sau_procorigem_sd95_i_codigo_seq do campo: sd95_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd95_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from sau_procorigem_sd95_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd95_i_codigo)){
         $this->erro_sql = " Campo sd95_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd95_i_codigo = $sd95_i_codigo; 
       }
     }
     if(($this->sd95_i_codigo == null) || ($this->sd95_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd95_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into sau_procorigem(
                                       sd95_i_codigo 
                                      ,sd95_i_procedimento 
                                      ,sd95_i_origem 
                                      ,sd95_i_anocomp 
                                      ,sd95_i_mescomp 
                       )
                values (
                                $this->sd95_i_codigo 
                               ,$this->sd95_i_procedimento 
                               ,$this->sd95_i_origem 
                               ,$this->sd95_i_anocomp 
                               ,$this->sd95_i_mescomp 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Procedimento de Origem ($this->sd95_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Procedimento de Origem já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Procedimento de Origem ($this->sd95_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd95_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd95_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,11599,'$this->sd95_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2001,11599,'','".AddSlashes(pg_result($resaco,0,'sd95_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2001,11600,'','".AddSlashes(pg_result($resaco,0,'sd95_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2001,11601,'','".AddSlashes(pg_result($resaco,0,'sd95_i_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2001,11602,'','".AddSlashes(pg_result($resaco,0,'sd95_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2001,11603,'','".AddSlashes(pg_result($resaco,0,'sd95_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd95_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update sau_procorigem set ";
     $virgula = "";
     if(trim($this->sd95_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd95_i_codigo"])){ 
       $sql  .= $virgula." sd95_i_codigo = $this->sd95_i_codigo ";
       $virgula = ",";
       if(trim($this->sd95_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd95_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd95_i_procedimento)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd95_i_procedimento"])){ 
       $sql  .= $virgula." sd95_i_procedimento = $this->sd95_i_procedimento ";
       $virgula = ",";
       if(trim($this->sd95_i_procedimento) == null ){ 
         $this->erro_sql = " Campo Procedimento nao Informado.";
         $this->erro_campo = "sd95_i_procedimento";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd95_i_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd95_i_origem"])){ 
       $sql  .= $virgula." sd95_i_origem = $this->sd95_i_origem ";
       $virgula = ",";
       if(trim($this->sd95_i_origem) == null ){ 
         $this->erro_sql = " Campo Procedimento de Origem nao Informado.";
         $this->erro_campo = "sd95_i_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd95_i_anocomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd95_i_anocomp"])){ 
       $sql  .= $virgula." sd95_i_anocomp = $this->sd95_i_anocomp ";
       $virgula = ",";
       if(trim($this->sd95_i_anocomp) == null ){ 
         $this->erro_sql = " Campo Ano nao Informado.";
         $this->erro_campo = "sd95_i_anocomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd95_i_mescomp)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd95_i_mescomp"])){ 
       $sql  .= $virgula." sd95_i_mescomp = $this->sd95_i_mescomp ";
       $virgula = ",";
       if(trim($this->sd95_i_mescomp) == null ){ 
         $this->erro_sql = " Campo Mes nao Informado.";
         $this->erro_campo = "sd95_i_mescomp";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd95_i_codigo!=null){
       $sql .= " sd95_i_codigo = $this->sd95_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd95_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11599,'$this->sd95_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd95_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,2001,11599,'".AddSlashes(pg_result($resaco,$conresaco,'sd95_i_codigo'))."','$this->sd95_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd95_i_procedimento"]))
           $resac = db_query("insert into db_acount values($acount,2001,11600,'".AddSlashes(pg_result($resaco,$conresaco,'sd95_i_procedimento'))."','$this->sd95_i_procedimento',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd95_i_origem"]))
           $resac = db_query("insert into db_acount values($acount,2001,11601,'".AddSlashes(pg_result($resaco,$conresaco,'sd95_i_origem'))."','$this->sd95_i_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd95_i_anocomp"]))
           $resac = db_query("insert into db_acount values($acount,2001,11602,'".AddSlashes(pg_result($resaco,$conresaco,'sd95_i_anocomp'))."','$this->sd95_i_anocomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd95_i_mescomp"]))
           $resac = db_query("insert into db_acount values($acount,2001,11603,'".AddSlashes(pg_result($resaco,$conresaco,'sd95_i_mescomp'))."','$this->sd95_i_mescomp',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimento de Origem nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd95_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimento de Origem nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd95_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd95_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd95_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd95_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,11599,'$sd95_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2001,11599,'','".AddSlashes(pg_result($resaco,$iresaco,'sd95_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2001,11600,'','".AddSlashes(pg_result($resaco,$iresaco,'sd95_i_procedimento'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2001,11601,'','".AddSlashes(pg_result($resaco,$iresaco,'sd95_i_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2001,11602,'','".AddSlashes(pg_result($resaco,$iresaco,'sd95_i_anocomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2001,11603,'','".AddSlashes(pg_result($resaco,$iresaco,'sd95_i_mescomp'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from sau_procorigem
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd95_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd95_i_codigo = $sd95_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Procedimento de Origem nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd95_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Procedimento de Origem nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd95_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd95_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:sau_procorigem";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $sd95_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_procorigem ";
     $sql .= "      inner join sau_procedimento as a on  a.sd63_i_codigo = sau_procorigem.sd95_i_procedimento ";
     $sql .= "      inner join sau_procedimento as b on  b.sd63_i_codigo = sau_procorigem.sd95_i_origem      ";

     $sql .= "      left join sau_financiamento as c on  c.sd65_i_codigo = a.sd63_i_financiamento";
     $sql .= "      left join sau_rubrica       as d on  d.sd64_i_codigo = a.sd63_i_rubrica";
     $sql .= "      left join sau_complexidade  as e on  e.sd69_i_codigo = a.sd63_i_complexidade";

     $sql .= "      left join sau_financiamento as f on   f.sd65_i_codigo = b.sd63_i_financiamento";
     $sql .= "      left join sau_rubrica       as g on   g.sd64_i_codigo = b.sd63_i_rubrica";
     $sql .= "      left join sau_complexidade  as h on   h.sd69_i_codigo = b.sd63_i_complexidade";

     $sql2 = "";
     if($dbwhere==""){
       if($sd95_i_codigo!=null ){
         $sql2 .= " where sau_procorigem.sd95_i_codigo = $sd95_i_codigo ";
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
   function sql_query_file ( $sd95_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){
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
     $sql .= " from sau_procorigem ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd95_i_codigo!=null ){
         $sql2 .= " where sau_procorigem.sd95_i_codigo = $sd95_i_codigo ";
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