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

//MODULO: patrim
//CLASSE DA ENTIDADE departdiv
class cl_departdiv { 
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
   var $t30_codigo = 0; 
   var $t30_descr = null; 
   var $t30_depto = 0; 
   var $t30_ativo = 'f'; 
   var $t30_numcgm = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 t30_codigo = int4 = Código da divisão 
                 t30_descr = varchar(40) = Descrição da divisão 
                 t30_depto = int4 = Depart. 
                 t30_ativo = bool = Ativo 
                 t30_numcgm = int4 = Responsável 
                 ";
   //funcao construtor da classe 
   function cl_departdiv() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("departdiv"); 
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
       $this->t30_codigo = ($this->t30_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t30_codigo"]:$this->t30_codigo);
       $this->t30_descr = ($this->t30_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["t30_descr"]:$this->t30_descr);
       $this->t30_depto = ($this->t30_depto == ""?@$GLOBALS["HTTP_POST_VARS"]["t30_depto"]:$this->t30_depto);
       $this->t30_ativo = ($this->t30_ativo == "f"?@$GLOBALS["HTTP_POST_VARS"]["t30_ativo"]:$this->t30_ativo);
       $this->t30_numcgm = ($this->t30_numcgm == ""?@$GLOBALS["HTTP_POST_VARS"]["t30_numcgm"]:$this->t30_numcgm);
     }else{
       $this->t30_codigo = ($this->t30_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["t30_codigo"]:$this->t30_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($t30_codigo){ 
      $this->atualizacampos();
     if($this->t30_descr == null ){ 
       $this->erro_sql = " Campo Descrição da divisão nao Informado.";
       $this->erro_campo = "t30_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t30_depto == null ){ 
       $this->erro_sql = " Campo Depart. nao Informado.";
       $this->erro_campo = "t30_depto";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t30_ativo == null ){ 
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "t30_ativo";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->t30_numcgm == null ){ 
       $this->erro_sql = " Campo Responsável nao Informado.";
       $this->erro_campo = "t30_numcgm";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($t30_codigo == "" || $t30_codigo == null ){
       $result = db_query("select nextval('departdiv_t30_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: departdiv_t30_codigo_seq do campo: t30_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->t30_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from departdiv_t30_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $t30_codigo)){
         $this->erro_sql = " Campo t30_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->t30_codigo = $t30_codigo; 
       }
     }
     if(($this->t30_codigo == null) || ($this->t30_codigo == "") ){ 
       $this->erro_sql = " Campo t30_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into departdiv(
                                       t30_codigo 
                                      ,t30_descr 
                                      ,t30_depto 
                                      ,t30_ativo 
                                      ,t30_numcgm 
                       )
                values (
                                $this->t30_codigo 
                               ,'$this->t30_descr' 
                               ,$this->t30_depto 
                               ,'$this->t30_ativo' 
                               ,$this->t30_numcgm 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "divisões de um departamento ($this->t30_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "divisões de um departamento já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "divisões de um departamento ($this->t30_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t30_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->t30_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,8932,'$this->t30_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1526,8932,'','".AddSlashes(pg_result($resaco,0,'t30_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1526,8934,'','".AddSlashes(pg_result($resaco,0,'t30_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1526,8933,'','".AddSlashes(pg_result($resaco,0,'t30_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1526,8935,'','".AddSlashes(pg_result($resaco,0,'t30_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1526,8936,'','".AddSlashes(pg_result($resaco,0,'t30_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($t30_codigo=null) { 
      $this->atualizacampos();
     $sql = " update departdiv set ";
     $virgula = "";
     if(trim($this->t30_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t30_codigo"])){ 
       $sql  .= $virgula." t30_codigo = $this->t30_codigo ";
       $virgula = ",";
       if(trim($this->t30_codigo) == null ){ 
         $this->erro_sql = " Campo Código da divisão nao Informado.";
         $this->erro_campo = "t30_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t30_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t30_descr"])){ 
       $sql  .= $virgula." t30_descr = '$this->t30_descr' ";
       $virgula = ",";
       if(trim($this->t30_descr) == null ){ 
         $this->erro_sql = " Campo Descrição da divisão nao Informado.";
         $this->erro_campo = "t30_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t30_depto)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t30_depto"])){ 
       $sql  .= $virgula." t30_depto = $this->t30_depto ";
       $virgula = ",";
       if(trim($this->t30_depto) == null ){ 
         $this->erro_sql = " Campo Depart. nao Informado.";
         $this->erro_campo = "t30_depto";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t30_ativo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t30_ativo"])){ 
       $sql  .= $virgula." t30_ativo = '$this->t30_ativo' ";
       $virgula = ",";
       if(trim($this->t30_ativo) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "t30_ativo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->t30_numcgm)!="" || isset($GLOBALS["HTTP_POST_VARS"]["t30_numcgm"])){ 
       $sql  .= $virgula." t30_numcgm = $this->t30_numcgm ";
       $virgula = ",";
       if(trim($this->t30_numcgm) == null ){ 
         $this->erro_sql = " Campo Responsável nao Informado.";
         $this->erro_campo = "t30_numcgm";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($t30_codigo!=null){
       $sql .= " t30_codigo = $this->t30_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->t30_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8932,'$this->t30_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t30_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1526,8932,'".AddSlashes(pg_result($resaco,$conresaco,'t30_codigo'))."','$this->t30_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t30_descr"]))
           $resac = db_query("insert into db_acount values($acount,1526,8934,'".AddSlashes(pg_result($resaco,$conresaco,'t30_descr'))."','$this->t30_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t30_depto"]))
           $resac = db_query("insert into db_acount values($acount,1526,8933,'".AddSlashes(pg_result($resaco,$conresaco,'t30_depto'))."','$this->t30_depto',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t30_ativo"]))
           $resac = db_query("insert into db_acount values($acount,1526,8935,'".AddSlashes(pg_result($resaco,$conresaco,'t30_ativo'))."','$this->t30_ativo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["t30_numcgm"]))
           $resac = db_query("insert into db_acount values($acount,1526,8936,'".AddSlashes(pg_result($resaco,$conresaco,'t30_numcgm'))."','$this->t30_numcgm',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "divisões de um departamento nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->t30_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "divisões de um departamento nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->t30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->t30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($t30_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($t30_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,8932,'$t30_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1526,8932,'','".AddSlashes(pg_result($resaco,$iresaco,'t30_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1526,8934,'','".AddSlashes(pg_result($resaco,$iresaco,'t30_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1526,8933,'','".AddSlashes(pg_result($resaco,$iresaco,'t30_depto'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1526,8935,'','".AddSlashes(pg_result($resaco,$iresaco,'t30_ativo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1526,8936,'','".AddSlashes(pg_result($resaco,$iresaco,'t30_numcgm'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from departdiv
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($t30_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " t30_codigo = $t30_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "divisões de um departamento nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$t30_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "divisões de um departamento nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$t30_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$t30_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:departdiv";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $t30_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from departdiv ";
     $sql .= "      inner join cgm          on cgm.z01_numcgm     = departdiv.t30_numcgm";
     $sql .= "      inner join db_depart    on db_depart.coddepto = departdiv.t30_depto";
     $sql .= "      inner join db_departorg on db_departorg.db01_coddepto = departdiv.t30_depto"; 
     $sql .= "      inner join orcorgao     on orcorgao.o40_orgao         = db_departorg.db01_orgao and
                                               orcorgao.o40_anousu        = db_departorg.db01_anousu";
     $sql2 = "";
     if($dbwhere==""){
       if($t30_codigo!=null ){
         $sql2 .= " where departdiv.t30_codigo = $t30_codigo "; 
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
   function sql_query_div ( $coddepto=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_depart ";
     $sql .= "      inner join db_departorg on db_departorg.db01_coddepto = db_depart.coddepto"; 
     $sql .= "      inner join orcorgao     on orcorgao.o40_orgao         = db_departorg.db01_orgao and
                                               orcorgao.o40_anousu        = db_departorg.db01_anousu";
     $sql .= "      left  join departdiv    on departdiv.t30_depto        = db_depart.coddepto";
     $sql .= "      left  join cgm          on cgm.z01_numcgm             = departdiv.t30_numcgm";
     $sql2 = ""; 
     if($dbwhere==""){
       if($coddepto!=null ){
         $sql2 .= " where db_depart.coddepto = $coddepto "; 
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
   function sql_query_file ( $t30_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from departdiv ";
     $sql2 = "";
     if($dbwhere==""){
       if($t30_codigo!=null ){
         $sql2 .= " where departdiv.t30_codigo = $t30_codigo "; 
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