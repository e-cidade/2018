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

//MODULO: Ambulatorial
//CLASSE DA ENTIDADE especmedico
class cl_especmedico { 
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
   var $sd27_i_codigo = 0; 
   var $sd27_i_rhcbo = 0; 
   var $sd27_i_undmed = 0; 
   var $sd27_b_principal = 'f'; 
   var $sd27_c_situacao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 sd27_i_codigo = int4 = Código 
                 sd27_i_rhcbo = int4 = Especialidade 
                 sd27_i_undmed = int4 = Unidade do Médico 
                 sd27_b_principal = bool = Ativ. Principal 
                 sd27_c_situacao = char(1) = Situação 
                 ";
   //funcao construtor da classe 
   function cl_especmedico() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("especmedico"); 
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
       $this->sd27_i_codigo = ($this->sd27_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_codigo"]:$this->sd27_i_codigo);
       $this->sd27_i_rhcbo = ($this->sd27_i_rhcbo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_rhcbo"]:$this->sd27_i_rhcbo);
       $this->sd27_i_undmed = ($this->sd27_i_undmed == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_undmed"]:$this->sd27_i_undmed);
       $this->sd27_b_principal = ($this->sd27_b_principal == "f"?@$GLOBALS["HTTP_POST_VARS"]["sd27_b_principal"]:$this->sd27_b_principal);
       $this->sd27_c_situacao = ($this->sd27_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_c_situacao"]:$this->sd27_c_situacao);
     }else{
       $this->sd27_i_codigo = ($this->sd27_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["sd27_i_codigo"]:$this->sd27_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($sd27_i_codigo){ 
      $this->atualizacampos();
     if($this->sd27_i_rhcbo == null ){ 
       $this->erro_sql = " Campo Especialidade nao Informado.";
       $this->erro_campo = "sd27_i_rhcbo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd27_i_undmed == null ){ 
       $this->erro_sql = " Campo Unidade do Médico nao Informado.";
       $this->erro_campo = "sd27_i_undmed";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd27_b_principal == null ){ 
       $this->erro_sql = " Campo Ativ. Principal nao Informado.";
       $this->erro_campo = "sd27_b_principal";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->sd27_c_situacao == null ){ 
       $this->erro_sql = " Campo Situação nao Informado.";
       $this->erro_campo = "sd27_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($sd27_i_codigo == "" || $sd27_i_codigo == null ){
       $result = db_query("select nextval('especmedico_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: especmedico_i_codigo_seq do campo: sd27_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->sd27_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from especmedico_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $sd27_i_codigo)){
         $this->erro_sql = " Campo sd27_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->sd27_i_codigo = $sd27_i_codigo; 
       }
     }
     if(($this->sd27_i_codigo == null) || ($this->sd27_i_codigo == "") ){ 
       $this->erro_sql = " Campo sd27_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into especmedico(
                                       sd27_i_codigo 
                                      ,sd27_i_rhcbo 
                                      ,sd27_i_undmed 
                                      ,sd27_b_principal 
                                      ,sd27_c_situacao 
                       )
                values (
                                $this->sd27_i_codigo 
                               ,$this->sd27_i_rhcbo 
                               ,$this->sd27_i_undmed 
                               ,'$this->sd27_b_principal' 
                               ,'$this->sd27_c_situacao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Especialidades para os Médicos ($this->sd27_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Especialidades para os Médicos já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Especialidades para os Médicos ($this->sd27_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd27_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->sd27_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1006141,'$this->sd27_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,100026,1006141,'','".AddSlashes(pg_result($resaco,0,'sd27_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100026,100126,'','".AddSlashes(pg_result($resaco,0,'sd27_i_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100026,100127,'','".AddSlashes(pg_result($resaco,0,'sd27_i_undmed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100026,1008813,'','".AddSlashes(pg_result($resaco,0,'sd27_b_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,100026,13488,'','".AddSlashes(pg_result($resaco,0,'sd27_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($sd27_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update especmedico set ";
     $virgula = "";
     if(trim($this->sd27_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_codigo"])){ 
       $sql  .= $virgula." sd27_i_codigo = $this->sd27_i_codigo ";
       $virgula = ",";
       if(trim($this->sd27_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "sd27_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd27_i_rhcbo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_rhcbo"])){ 
       $sql  .= $virgula." sd27_i_rhcbo = $this->sd27_i_rhcbo ";
       $virgula = ",";
       if(trim($this->sd27_i_rhcbo) == null ){ 
         $this->erro_sql = " Campo Especialidade nao Informado.";
         $this->erro_campo = "sd27_i_rhcbo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd27_i_undmed)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_undmed"])){ 
       $sql  .= $virgula." sd27_i_undmed = $this->sd27_i_undmed ";
       $virgula = ",";
       if(trim($this->sd27_i_undmed) == null ){ 
         $this->erro_sql = " Campo Unidade do Médico nao Informado.";
         $this->erro_campo = "sd27_i_undmed";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd27_b_principal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_b_principal"])){ 
       $sql  .= $virgula." sd27_b_principal = '$this->sd27_b_principal' ";
       $virgula = ",";
       if(trim($this->sd27_b_principal) == null ){ 
         $this->erro_sql = " Campo Ativ. Principal nao Informado.";
         $this->erro_campo = "sd27_b_principal";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->sd27_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["sd27_c_situacao"])){ 
       $sql  .= $virgula." sd27_c_situacao = '$this->sd27_c_situacao' ";
       $virgula = ",";
       if(trim($this->sd27_c_situacao) == null ){ 
         $this->erro_sql = " Campo Situação nao Informado.";
         $this->erro_campo = "sd27_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($sd27_i_codigo!=null){
       $sql .= " sd27_i_codigo = $this->sd27_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->sd27_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1006141,'$this->sd27_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,100026,1006141,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_i_codigo'))."','$this->sd27_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_rhcbo"]))
           $resac = db_query("insert into db_acount values($acount,100026,100126,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_i_rhcbo'))."','$this->sd27_i_rhcbo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_i_undmed"]))
           $resac = db_query("insert into db_acount values($acount,100026,100127,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_i_undmed'))."','$this->sd27_i_undmed',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_b_principal"]))
           $resac = db_query("insert into db_acount values($acount,100026,1008813,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_b_principal'))."','$this->sd27_b_principal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["sd27_c_situacao"]))
           $resac = db_query("insert into db_acount values($acount,100026,13488,'".AddSlashes(pg_result($resaco,$conresaco,'sd27_c_situacao'))."','$this->sd27_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Especialidades para os Médicos nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd27_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Especialidades para os Médicos nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->sd27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->sd27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($sd27_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($sd27_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1006141,'$sd27_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,100026,1006141,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100026,100126,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_i_rhcbo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100026,100127,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_i_undmed'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100026,1008813,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_b_principal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,100026,13488,'','".AddSlashes(pg_result($resaco,$iresaco,'sd27_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from especmedico
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($sd27_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " sd27_i_codigo = $sd27_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Especialidades para os Médicos nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$sd27_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Especialidades para os Médicos nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$sd27_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$sd27_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:especmedico";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $sd27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from especmedico ";
     $sql .= "      inner join rhcbo  on  rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo";
     $sql .= "      inner join unidademedicos  on  unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed";
     $sql .= "      left join sau_orgaoemissor  on  sau_orgaoemissor.sd51_i_codigo = unidademedicos.sd04_i_orgaoemissor";
     $sql .= "      left join sau_modvinculo  on  sau_modvinculo.sd52_i_vinculacao = unidademedicos.sd04_i_vinculo";
     $sql .= "      inner join unidades  on  unidades.sd02_i_codigo = unidademedicos.sd04_i_unidade";
     $sql .= "      inner join medicos  on  medicos.sd03_i_codigo = unidademedicos.sd04_i_medico";
     $sql .= "      inner join cgm  as a on   a.z01_numcgm = medicos.sd03_i_cgm";
     $sql .= "       left join cgm  on  cgm.z01_numcgm = unidades.sd02_i_numcgm";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = unidades.sd02_i_codigo";
     $sql .= "       left join sau_esferaadmin  on  sau_esferaadmin.sd37_i_cod_esfadm = unidades.sd02_i_cod_esfadm";
     $sql .= "       left join sau_atividadeensino  on  sau_atividadeensino.sd38_i_cod_ativid = unidades.sd02_i_cod_ativ";
     $sql .= "       left join sau_retentributo  on  sau_retentributo.sd39_i_cod_reten = unidades.sd02_i_reten_trib";
     $sql .= "       left join sau_natorg  on  sau_natorg.sd40_i_cod_natorg = unidades.sd02_i_cod_natorg";
     $sql .= "       left join sau_fluxocliente  on  sau_fluxocliente.sd41_i_cod_cliente = unidades.sd02_i_cod_client";
     $sql .= "       left join sau_tipounidade  on  sau_tipounidade.sd42_i_tp_unid_id = unidades.sd02_i_tp_unid_id";
     $sql .= "       left join sau_turnoatend  on  sau_turnoatend.sd43_cod_turnat = unidades.sd02_i_cod_turnat";
     $sql .= "       left join sau_nivelhier  on  sau_nivelhier.sd44_i_codnivhier = unidades.sd02_i_codnivhier";
     $sql .= "       left join sau_tpmodvinculo on sau_tpmodvinculo.sd53_i_vinculacao = sd04_i_vinculo  
                                               and sau_tpmodvinculo.sd53_i_tpvinculo  = sd04_i_tipovinc
             ";
     $sql .= "       left join sau_subtpmodvinculo on sau_subtpmodvinculo.sd54_i_vinculacao = unidademedicos.sd04_i_vinculo  
                                                  and sau_subtpmodvinculo.sd54_i_tpvinculo  = unidademedicos.sd04_i_tipovinc
                                                  and sau_subtpmodvinculo.sd54_i_tpsubvinculo = unidademedicos.sd04_i_subtipovinc
             ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd27_i_codigo!=null ){
         $sql2 .= " where especmedico.sd27_i_codigo = $sd27_i_codigo "; 
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
   function sql_query_file ( $sd27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from especmedico ";
     $sql2 = "";
     if($dbwhere==""){
       if($sd27_i_codigo!=null ){
         $sql2 .= " where especmedico.sd27_i_codigo = $sd27_i_codigo "; 
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
  * Gera SQL para a busca das especialidades dos medicos. Pode filtrar por medicos ou nao.(filtra tambem por unidade)
  */
  function sql_query_especialidade($iRh70_estrutural, $lFiltraPorMedico = false, $iSd04_i_medico = '',
                                   $iSd04_i_unidade = '', $sCampos = "*", $sOrdem = '', $sDbwhere = "") { 
    $sSql  = 'select distinct ';
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

    $sSql .= '  from especmedico ';
    $sSql .= '    inner join rhcbo on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo ';
    $lFiltraPorMedico ? 
      $sSql .= '  inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ' : '';
    $sSql .= "      where sd27_c_situacao = 'A' ";
    $sSql .= "        and rh70_estrutural = '$iRh70_estrutural' ";
    $lFiltraPorMedico ?
      $sSql .= "      and sd04_i_unidade = $iSd04_i_unidade and sd04_i_medico = $iSd04_i_medico " : '';
 
    if(!empty($sDbwhere)) {
      $sSql .= 'and ';
    }
    if(!empty($sOrdem)) {
      $sOrdem = ' order by '.$sOrdem;
    }
    $sSql .= $sDbwhere.' '.$sOrdem;

    return $sSql;

  }

   function sql_query_especmedico ( $sd27_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from especmedico ";
     $sql .= '  inner join rhcbo on rhcbo.rh70_sequencial = especmedico.sd27_i_rhcbo ';
     $sql .= '  inner join unidademedicos on unidademedicos.sd04_i_codigo = especmedico.sd27_i_undmed ';
     $sql .= '  inner join medicos on medicos.sd03_i_codigo = unidademedicos.sd04_i_medico ';
     $sql2 = "";
     if($dbwhere==""){
       if($sd27_i_codigo!=null ){
         $sql2 .= " where especmedico.sd27_i_codigo = $sd27_i_codigo "; 
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