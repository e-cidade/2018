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

//MODULO: pessoal
//CLASSE DA ENTIDADE relrub
class cl_relrub { 
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
   var $rh45_instit = 0; 
   var $rh45_codigo = 0; 
   var $rh45_descr = null; 
   var $rh45_selecao = 0; 
   var $rh45_form = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 rh45_instit = int4 = Cod. Instituição 
                 rh45_codigo = int4 = Relatório 
                 rh45_descr = varchar(40) = Descrição 
                 rh45_selecao = int4 = Seleção 
                 rh45_form = varchar(70) = Fórmula 
                 ";
   //funcao construtor da classe 
   function cl_relrub() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("relrub"); 
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
       $this->rh45_instit = ($this->rh45_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh45_instit"]:$this->rh45_instit);
       $this->rh45_codigo = ($this->rh45_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh45_codigo"]:$this->rh45_codigo);
       $this->rh45_descr = ($this->rh45_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["rh45_descr"]:$this->rh45_descr);
       $this->rh45_selecao = ($this->rh45_selecao == ""?@$GLOBALS["HTTP_POST_VARS"]["rh45_selecao"]:$this->rh45_selecao);
       $this->rh45_form = ($this->rh45_form == ""?@$GLOBALS["HTTP_POST_VARS"]["rh45_form"]:$this->rh45_form);
     }else{
       $this->rh45_instit = ($this->rh45_instit == ""?@$GLOBALS["HTTP_POST_VARS"]["rh45_instit"]:$this->rh45_instit);
       $this->rh45_codigo = ($this->rh45_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["rh45_codigo"]:$this->rh45_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($rh45_codigo,$rh45_instit){ 
      $this->atualizacampos();
     if($this->rh45_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "rh45_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->rh45_selecao == null ){ 
       $this->rh45_selecao = "0";
     }
     if($rh45_codigo == "" || $rh45_codigo == null ){
       $result = db_query("select nextval('relrub_rh45_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: relrub_rh45_codigo_seq do campo: rh45_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->rh45_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from relrub_rh45_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $rh45_codigo)){
         $this->erro_sql = " Campo rh45_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->rh45_codigo = $rh45_codigo; 
       }
     }
     if(($this->rh45_codigo == null) || ($this->rh45_codigo == "") ){ 
       $this->erro_sql = " Campo rh45_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->rh45_instit == null) || ($this->rh45_instit == "") ){ 
       $this->erro_sql = " Campo rh45_instit nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into relrub(
                                       rh45_instit 
                                      ,rh45_codigo 
                                      ,rh45_descr 
                                      ,rh45_selecao 
                                      ,rh45_form 
                       )
                values (
                                $this->rh45_instit 
                               ,$this->rh45_codigo 
                               ,'$this->rh45_descr' 
                               ,$this->rh45_selecao 
                               ,'$this->rh45_form' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Relatório configurável de rubricas ($this->rh45_codigo."-".$this->rh45_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Relatório configurável de rubricas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Relatório configurável de rubricas ($this->rh45_codigo."-".$this->rh45_instit) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh45_codigo."-".$this->rh45_instit;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->rh45_codigo,$this->rh45_instit));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8014,'$this->rh45_codigo','I')");
       $resac = db_query("insert into db_acountkey values($acount,9899,'$this->rh45_instit','I')");
       $resac = db_query("insert into db_acount values($acount,1351,9899,'','".AddSlashes(pg_result($resaco,0,'rh45_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1351,8014,'','".AddSlashes(pg_result($resaco,0,'rh45_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1351,8015,'','".AddSlashes(pg_result($resaco,0,'rh45_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1351,8016,'','".AddSlashes(pg_result($resaco,0,'rh45_selecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1351,8017,'','".AddSlashes(pg_result($resaco,0,'rh45_form'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($rh45_codigo=null,$rh45_instit=null) { 
      $this->atualizacampos();
     $sql = " update relrub set ";
     $virgula = "";
     if(trim($this->rh45_instit)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh45_instit"])){ 
       $sql  .= $virgula." rh45_instit = $this->rh45_instit ";
       $virgula = ",";
       if(trim($this->rh45_instit) == null ){ 
         $this->erro_sql = " Campo Cod. Instituição nao Informado.";
         $this->erro_campo = "rh45_instit";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh45_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh45_codigo"])){ 
       $sql  .= $virgula." rh45_codigo = $this->rh45_codigo ";
       $virgula = ",";
       if(trim($this->rh45_codigo) == null ){ 
         $this->erro_sql = " Campo Relatório nao Informado.";
         $this->erro_campo = "rh45_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh45_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh45_descr"])){ 
       $sql  .= $virgula." rh45_descr = '$this->rh45_descr' ";
       $virgula = ",";
       if(trim($this->rh45_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "rh45_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->rh45_selecao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh45_selecao"])){ 
        if(trim($this->rh45_selecao)=="" && isset($GLOBALS["HTTP_POST_VARS"]["rh45_selecao"])){ 
           $this->rh45_selecao = "0" ; 
        } 
       $sql  .= $virgula." rh45_selecao = $this->rh45_selecao ";
       $virgula = ",";
     }
     if(trim($this->rh45_form)!="" || isset($GLOBALS["HTTP_POST_VARS"]["rh45_form"])){ 
       $sql  .= $virgula." rh45_form = '$this->rh45_form' ";
       $virgula = ",";
     }
     $sql .= " where ";
     if($rh45_codigo!=null){
       $sql .= " rh45_codigo = $this->rh45_codigo";
     }
     if($rh45_instit!=null){
       $sql .= " and  rh45_instit = $this->rh45_instit";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->rh45_codigo,$this->rh45_instit));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8014,'$this->rh45_codigo','A')");
         $resac = db_query("insert into db_acountkey values($acount,9899,'$this->rh45_instit','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh45_instit"]))
           $resac = db_query("insert into db_acount values($acount,1351,9899,'".AddSlashes(pg_result($resaco,$conresaco,'rh45_instit'))."','$this->rh45_instit',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh45_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1351,8014,'".AddSlashes(pg_result($resaco,$conresaco,'rh45_codigo'))."','$this->rh45_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh45_descr"]))
           $resac = db_query("insert into db_acount values($acount,1351,8015,'".AddSlashes(pg_result($resaco,$conresaco,'rh45_descr'))."','$this->rh45_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh45_selecao"]))
           $resac = db_query("insert into db_acount values($acount,1351,8016,'".AddSlashes(pg_result($resaco,$conresaco,'rh45_selecao'))."','$this->rh45_selecao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["rh45_form"]))
           $resac = db_query("insert into db_acount values($acount,1351,8017,'".AddSlashes(pg_result($resaco,$conresaco,'rh45_form'))."','$this->rh45_form',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relatório configurável de rubricas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh45_codigo."-".$this->rh45_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Relatório configurável de rubricas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->rh45_codigo."-".$this->rh45_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->rh45_codigo."-".$this->rh45_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($rh45_codigo=null,$rh45_instit=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($rh45_codigo,$rh45_instit));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8014,'$rh45_codigo','E')");
         $resac = db_query("insert into db_acountkey values($acount,9899,'$rh45_instit','E')");
         $resac = db_query("insert into db_acount values($acount,1351,9899,'','".AddSlashes(pg_result($resaco,$iresaco,'rh45_instit'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1351,8014,'','".AddSlashes(pg_result($resaco,$iresaco,'rh45_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1351,8015,'','".AddSlashes(pg_result($resaco,$iresaco,'rh45_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1351,8016,'','".AddSlashes(pg_result($resaco,$iresaco,'rh45_selecao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1351,8017,'','".AddSlashes(pg_result($resaco,$iresaco,'rh45_form'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from relrub
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($rh45_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh45_codigo = $rh45_codigo ";
        }
        if($rh45_instit != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " rh45_instit = $rh45_instit ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Relatório configurável de rubricas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$rh45_codigo."-".$rh45_instit;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Relatório configurável de rubricas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$rh45_codigo."-".$rh45_instit;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$rh45_codigo."-".$rh45_instit;
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
        $this->erro_sql   = "Record Vazio na Tabela:relrub";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $rh45_codigo=null,$rh45_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from relrub ";
     $sql .= "      inner join db_config  on  db_config.codigo = relrub.rh45_instit";
     $sql .= "      inner join cgm  on  cgm.z01_numcgm = db_config.numcgm";
     $sql2 = "";
     if($dbwhere==""){
       if($rh45_codigo!=null ){
         $sql2 .= " where relrub.rh45_codigo = $rh45_codigo "; 
       } 
       if($rh45_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " relrub.rh45_instit = $rh45_instit "; 
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
   function sql_query_file ( $rh45_codigo=null,$rh45_instit=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from relrub ";
     $sql2 = "";
     if($dbwhere==""){
       if($rh45_codigo!=null ){
         $sql2 .= " where relrub.rh45_codigo = $rh45_codigo "; 
       } 
       if($rh45_instit!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " relrub.rh45_instit = $rh45_instit "; 
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