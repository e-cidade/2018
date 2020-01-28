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

//MODULO: Merenda
//CLASSE DA ENTIDADE mer_cardapioalunorepet
class cl_mer_cardapioalunorepet { 
   // cria variaveis de erro 
   var $rotulo          = null; 
   var $query_sql       = null; 
   var $numrows         = 0; 
   var $numrows_incluir = 0; 
   var $numrows_alterar = 0; 
   var $numrows_excluir = 0; 
   var $erro_status     = null; 
   var $erro_sql        = null; 
   var $erro_banco      = null;  
   var $erro_msg        = null;  
   var $erro_campo      = null;  
   var $pagina_retorno  = null; 
   // cria variaveis do arquivo 
   var $me40_i_codigo         = 0; 
   var $me40_i_cardapiodia    = 0; 
   var $me40_i_repeticao      = 0;
   var $me40_i_turma          = 0;
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 me40_i_codigo = int4 = Código 
                 me40_i_cardapiodia = int4 = Cardapiodia 
                 me40_i_repeticao = int4 = Repetições
                 me40_i_turma = int4 = turma
                 ";
   //funcao construtor da classe 
   function cl_mer_cardapioalunorepet() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("mer_cardapioalunorepet"); 
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
       $this->me40_i_codigo = ($this->me40_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me40_i_codigo"]:$this->me40_i_codigo);
       $this->me40_i_cardapiodia = ($this->me40_i_cardapiodia == ""?@$GLOBALS["HTTP_POST_VARS"]["me40_i_cardapiodia"]:$this->me40_i_cardapiodia);
       $this->me40_i_repeticao = ($this->me40_i_repeticao == ""?@$GLOBALS["HTTP_POST_VARS"]["me40_i_repeticao"]:$this->me40_i_repeticao);
       $this->me40_i_turma = ($this->me40_i_turma == ""?@$GLOBALS["HTTP_POST_VARS"]["me40_i_turma"]:$this->me40_i_turma);       
     }else{
       $this->me40_i_codigo = ($this->me40_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["me40_i_codigo"]:$this->me40_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($me40_i_codigo){ 
      $this->atualizacampos();
     if($this->me40_i_cardapiodia == null ){ 
       $this->erro_sql = " Campo Cardapiodia nao Informado.";
       $this->erro_campo = "me40_i_cardapiodia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me40_i_repeticao == null ){ 
       $this->erro_sql = " Campo Repetições nao Informado.";
       $this->erro_campo = "me40_i_repeticao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->me40_i_turma == null ){ 
       $this->erro_sql = " Campo Turma nao Informado.";
       $this->erro_campo = "me40_i_turma";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($me40_i_codigo == "" || $me40_i_codigo == null ){
       $result = db_query("select nextval('mer_cardapioalunorepet_me40_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: mer_cardapioalunorepet_me40_i_codigo_seq do campo: me40_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->me40_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from mer_cardapioalunorepet_me40_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $me40_i_codigo)){
         $this->erro_sql = " Campo me40_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->me40_i_codigo = $me40_i_codigo; 
       }
     }
     if(($this->me40_i_codigo == null) || ($this->me40_i_codigo == "") ){ 
       $this->erro_sql = " Campo me40_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into mer_cardapioalunorepet(
                                       me40_i_codigo 
                                      ,me40_i_cardapiodia 
                                      ,me40_i_repeticao
                                      ,me40_i_turma
                       )
                values (
                                $this->me40_i_codigo 
                               ,$this->me40_i_cardapiodia 
                               ,$this->me40_i_repeticao
                               ,$this->me40_i_turma
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "mer_cardapioalunorepet ($this->me40_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "mer_cardapioalunorepet já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "mer_cardapioalunorepet ($this->me40_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me40_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->me40_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,17650,'$this->me40_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,3090,17650,'','".AddSlashes(pg_result($resaco,0,'me40_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3090,17651,'','".AddSlashes(pg_result($resaco,0,'me40_i_cardapiodia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3090,17652,'','".AddSlashes(pg_result($resaco,0,'me40_i_repeticao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3090,17653,'','".AddSlashes(pg_result($resaco,0,'me40_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($me40_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update mer_cardapioalunorepet set ";
     $virgula = "";
     if(trim($this->me40_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me40_i_codigo"])){ 
       $sql  .= $virgula." me40_i_codigo = $this->me40_i_codigo ";
       $virgula = ",";
       if(trim($this->me40_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "me40_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me40_i_cardapiodia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me40_i_cardapiodia"])){ 
       $sql  .= $virgula." me40_i_cardapiodia = $this->me40_i_cardapiodia ";
       $virgula = ",";
       if(trim($this->me40_i_cardapiodia) == null ){ 
         $this->erro_sql = " Campo Cardapiodia nao Informado.";
         $this->erro_campo = "me40_i_cardapiodia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me40_i_repeticao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me40_i_repeticao"])){ 
       $sql  .= $virgula." me40_i_repeticao = $this->me40_i_repeticao ";
       $virgula = ",";
       if(trim($this->me40_i_repeticao) == null ){ 
         $this->erro_sql = " Campo Repetições nao Informado.";
         $this->erro_campo = "me40_i_repeticao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->me40_i_turma)!="" || isset($GLOBALS["HTTP_POST_VARS"]["me40_i_turma"])){ 
       $sql  .= $virgula." me40_i_turma = $this->me40_i_turma ";
       $virgula = ",";
       if(trim($this->me40_i_cardapiodia) == null ){ 
         $this->erro_sql = " Campo Cardapiodia nao Informado.";
         $this->erro_campo = "me40_i_turma";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($me40_i_codigo!=null){
       $sql .= " me40_i_codigo = $this->me40_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->me40_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17650,'$this->me40_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me40_i_codigo"]) || $this->me40_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,3090,17650,'".AddSlashes(pg_result($resaco,$conresaco,'me40_i_codigo'))."','$this->me40_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me40_i_cardapiodia"]) || $this->me40_i_cardapiodia != "")
           $resac = db_query("insert into db_acount values($acount,3090,17651,'".AddSlashes(pg_result($resaco,$conresaco,'me40_i_cardapiodia'))."','$this->me40_i_cardapiodia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me40_i_repeticao"]) || $this->me40_i_repeticao != "")
           $resac = db_query("insert into db_acount values($acount,3090,17652,'".AddSlashes(pg_result($resaco,$conresaco,'me40_i_repeticao'))."','$this->me40_i_repeticao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["me40_i_turma"]) || $this->me40_i_turma != "")
           $resac = db_query("insert into db_acount values($acount,3090,17653,'".AddSlashes(pg_result($resaco,$conresaco,'me40_i_turma'))."','$this->me40_i_turma',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_cardapioalunorepet nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->me40_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_cardapioalunorepet nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->me40_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->me40_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($me40_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($me40_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,17650,'$me40_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,3090,17650,'','".AddSlashes(pg_result($resaco,$iresaco,'me40_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3090,17651,'','".AddSlashes(pg_result($resaco,$iresaco,'me40_i_cardapiodia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3090,17652,'','".AddSlashes(pg_result($resaco,$iresaco,'me40_i_repeticao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3090,17653,'','".AddSlashes(pg_result($resaco,$iresaco,'me40_i_turma'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from mer_cardapioalunorepet
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($me40_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " me40_i_codigo = $me40_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "mer_cardapioalunorepet nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$me40_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "mer_cardapioalunorepet nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$me40_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$me40_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:mer_cardapioalunorepet";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $me40_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapioalunorepet ";
     $sql .= "      inner join mer_cardapiodia  on  mer_cardapiodia.me12_i_codigo = mer_cardapioalunorepet.me40_i_cardapiodia";
     $sql .= "      inner join mer_cardapio  on  mer_cardapio.me01_i_codigo = mer_cardapiodia.me12_i_cardapio";
     $sql .= "      inner join turma  on  turma.ed57_i_codigo = mer_cardapioalunorepet.me40_i_turma";
     $sql2 = "";
     if($dbwhere==""){
       if($me40_i_codigo!=null ){
         $sql2 .= " where mer_cardapioalunorepet.me40_i_codigo = $me40_i_codigo "; 
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
   function sql_query_file ( $me40_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from mer_cardapioalunorepet ";
     $sql2 = "";
     if($dbwhere==""){
       if($me40_i_codigo!=null ){
         $sql2 .= " where mer_cardapioalunorepet.me40_i_codigo = $me40_i_codigo "; 
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