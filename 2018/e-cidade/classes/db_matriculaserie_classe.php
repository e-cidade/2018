<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

//MODULO: escola
//CLASSE DA ENTIDADE matriculaserie
class cl_matriculaserie { 
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
   var $ed221_i_codigo = 0; 
   var $ed221_i_matricula = 0; 
   var $ed221_i_serie = 0; 
   var $ed221_c_origem = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed221_i_codigo = int8 = Código 
                 ed221_i_matricula = int8 = Matrícula 
                 ed221_i_serie = int8 = Etapa 
                 ed221_c_origem = char(1) = Etapa de Origem 
                 ";
   //funcao construtor da classe 
   function cl_matriculaserie() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("matriculaserie"); 
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
       $this->ed221_i_codigo = ($this->ed221_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed221_i_codigo"]:$this->ed221_i_codigo);
       $this->ed221_i_matricula = ($this->ed221_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed221_i_matricula"]:$this->ed221_i_matricula);
       $this->ed221_i_serie = ($this->ed221_i_serie == ""?@$GLOBALS["HTTP_POST_VARS"]["ed221_i_serie"]:$this->ed221_i_serie);
       $this->ed221_c_origem = ($this->ed221_c_origem == ""?@$GLOBALS["HTTP_POST_VARS"]["ed221_c_origem"]:$this->ed221_c_origem);
     }else{
       $this->ed221_i_codigo = ($this->ed221_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed221_i_codigo"]:$this->ed221_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed221_i_codigo){ 
      $this->atualizacampos();
     if($this->ed221_i_matricula == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed221_i_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed221_i_serie == null ){ 
       $this->erro_sql = " Campo Etapa nao Informado.";
       $this->erro_campo = "ed221_i_serie";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed221_c_origem == null ){ 
       $this->erro_sql = " Campo Etapa de Origem nao Informado.";
       $this->erro_campo = "ed221_c_origem";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed221_i_codigo == "" || $ed221_i_codigo == null ){
       $result = db_query("select nextval('matriculaserie_ed221_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: matriculaserie_ed221_i_codigo_seq do campo: ed221_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed221_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from matriculaserie_ed221_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed221_i_codigo)){
         $this->erro_sql = " Campo ed221_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed221_i_codigo = $ed221_i_codigo; 
       }
     }
     if(($this->ed221_i_codigo == null) || ($this->ed221_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed221_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into matriculaserie(
                                       ed221_i_codigo 
                                      ,ed221_i_matricula 
                                      ,ed221_i_serie 
                                      ,ed221_c_origem 
                       )
                values (
                                $this->ed221_i_codigo 
                               ,$this->ed221_i_matricula 
                               ,$this->ed221_i_serie 
                               ,'$this->ed221_c_origem' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Séries da Matrícula ($this->ed221_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Séries da Matrícula já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Séries da Matrícula ($this->ed221_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed221_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed221_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,14966,'$this->ed221_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,2628,14966,'','".AddSlashes(pg_result($resaco,0,'ed221_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2628,14967,'','".AddSlashes(pg_result($resaco,0,'ed221_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2628,14968,'','".AddSlashes(pg_result($resaco,0,'ed221_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,2628,14969,'','".AddSlashes(pg_result($resaco,0,'ed221_c_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed221_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update matriculaserie set ";
     $virgula = "";
     if(trim($this->ed221_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed221_i_codigo"])){ 
       $sql  .= $virgula." ed221_i_codigo = $this->ed221_i_codigo ";
       $virgula = ",";
       if(trim($this->ed221_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed221_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed221_i_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed221_i_matricula"])){ 
       $sql  .= $virgula." ed221_i_matricula = $this->ed221_i_matricula ";
       $virgula = ",";
       if(trim($this->ed221_i_matricula) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed221_i_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed221_i_serie)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed221_i_serie"])){ 
       $sql  .= $virgula." ed221_i_serie = $this->ed221_i_serie ";
       $virgula = ",";
       if(trim($this->ed221_i_serie) == null ){ 
         $this->erro_sql = " Campo Etapa nao Informado.";
         $this->erro_campo = "ed221_i_serie";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed221_c_origem)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed221_c_origem"])){ 
       $sql  .= $virgula." ed221_c_origem = '$this->ed221_c_origem' ";
       $virgula = ",";
       if(trim($this->ed221_c_origem) == null ){ 
         $this->erro_sql = " Campo Etapa de Origem nao Informado.";
         $this->erro_campo = "ed221_c_origem";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed221_i_codigo!=null){
       $sql .= " ed221_i_codigo = $this->ed221_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed221_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14966,'$this->ed221_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed221_i_codigo"]) || $this->ed221_i_codigo != "")
           $resac = db_query("insert into db_acount values($acount,2628,14966,'".AddSlashes(pg_result($resaco,$conresaco,'ed221_i_codigo'))."','$this->ed221_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed221_i_matricula"]) || $this->ed221_i_matricula != "")
           $resac = db_query("insert into db_acount values($acount,2628,14967,'".AddSlashes(pg_result($resaco,$conresaco,'ed221_i_matricula'))."','$this->ed221_i_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed221_i_serie"]) || $this->ed221_i_serie != "")
           $resac = db_query("insert into db_acount values($acount,2628,14968,'".AddSlashes(pg_result($resaco,$conresaco,'ed221_i_serie'))."','$this->ed221_i_serie',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed221_c_origem"]) || $this->ed221_c_origem != "")
           $resac = db_query("insert into db_acount values($acount,2628,14969,'".AddSlashes(pg_result($resaco,$conresaco,'ed221_c_origem'))."','$this->ed221_c_origem',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Séries da Matrícula nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed221_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Séries da Matrícula nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed221_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed221_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed221_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed221_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,14966,'$ed221_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,2628,14966,'','".AddSlashes(pg_result($resaco,$iresaco,'ed221_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2628,14967,'','".AddSlashes(pg_result($resaco,$iresaco,'ed221_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2628,14968,'','".AddSlashes(pg_result($resaco,$iresaco,'ed221_i_serie'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,2628,14969,'','".AddSlashes(pg_result($resaco,$iresaco,'ed221_c_origem'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from matriculaserie
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed221_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed221_i_codigo = $ed221_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Séries da Matrícula nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed221_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Séries da Matrícula nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed221_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed221_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:matriculaserie";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed221_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matriculaserie ";
     $sql .= "      inner join serie            on  serie.ed11_i_codigo = matriculaserie.ed221_i_serie";
     $sql .= "      inner join matricula        on  matricula.ed60_i_codigo = matriculaserie.ed221_i_matricula";
     $sql .= "      inner join ensino           on  ensino.ed10_i_codigo = serie.ed11_i_ensino";
     $sql .= "      inner join aluno            on  aluno.ed47_i_codigo = matricula.ed60_i_aluno";
     $sql .= "      inner join turma            on  turma.ed57_i_codigo = matricula.ed60_i_turma";
     $sql2 = "";
     if($dbwhere==""){
       if($ed221_i_codigo!=null ){
         $sql2 .= " where matriculaserie.ed221_i_codigo = $ed221_i_codigo "; 
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
   function sql_query_file ( $ed221_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from matriculaserie ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed221_i_codigo!=null ){
         $sql2 .= " where matriculaserie.ed221_i_codigo = $ed221_i_codigo "; 
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