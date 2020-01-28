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
//CLASSE DA ENTIDADE cursoescola
class cl_cursoescola { 
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
   var $ed71_i_codigo = 0; 
   var $ed71_i_escola = 0; 
   var $ed71_i_curso = 0; 
   var $ed71_c_situacao = null; 
   var $ed71_c_turmasala = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed71_i_codigo = int8 = Código 
                 ed71_i_escola = int8 = Escola 
                 ed71_i_curso = int8 = Curso 
                 ed71_c_situacao = char(1) = Ativo 
                 ed71_c_turmasala = char(1) = Permitir mais de uma turma por sala 
                 ";
   //funcao construtor da classe 
   function cl_cursoescola() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("cursoescola"); 
     $this->pagina_retorno =  basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]."?ed71_i_escola=".@$GLOBALS["HTTP_POST_VARS"]["ed71_i_escola"]."&ed18_c_nome=".@$GLOBALS["HTTP_POST_VARS"]["ed18_c_nome"]."&ed71_i_curso=".@$GLOBALS["HTTP_POST_VARS"]["ed71_i_curso"]."&ed29_c_descr=".@$GLOBALS["HTTP_POST_VARS"]["ed29_c_descr"]);
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
       $this->ed71_i_codigo = ($this->ed71_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed71_i_codigo"]:$this->ed71_i_codigo);
       $this->ed71_i_escola = ($this->ed71_i_escola == ""?@$GLOBALS["HTTP_POST_VARS"]["ed71_i_escola"]:$this->ed71_i_escola);
       $this->ed71_i_curso = ($this->ed71_i_curso == ""?@$GLOBALS["HTTP_POST_VARS"]["ed71_i_curso"]:$this->ed71_i_curso);
       $this->ed71_c_situacao = ($this->ed71_c_situacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed71_c_situacao"]:$this->ed71_c_situacao);
       $this->ed71_c_turmasala = ($this->ed71_c_turmasala == ""?@$GLOBALS["HTTP_POST_VARS"]["ed71_c_turmasala"]:$this->ed71_c_turmasala);
     }else{
       $this->ed71_i_codigo = ($this->ed71_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed71_i_codigo"]:$this->ed71_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed71_i_codigo){ 
      $this->atualizacampos();
     if($this->ed71_i_escola == null ){ 
       $this->erro_sql = " Campo Escola nao Informado.";
       $this->erro_campo = "ed71_i_escola";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed71_i_curso == null ){ 
       $this->erro_sql = " Campo Curso nao Informado.";
       $this->erro_campo = "ed71_i_curso";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed71_c_situacao == null ){
       $this->erro_sql = " Campo Ativo nao Informado.";
       $this->erro_campo = "ed71_c_situacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed71_c_turmasala == null ){ 
       $this->erro_sql = " Campo Permitir mais de uma turma por sala nao Informado.";
       $this->erro_campo = "ed71_c_turmasala";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed71_i_codigo == "" || $ed71_i_codigo == null ){
       $result = db_query("select nextval('cursoescola_ed71_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: cursoescola_ed71_i_codigo_seq do campo: ed71_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed71_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from cursoescola_ed71_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed71_i_codigo)){
         $this->erro_sql = " Campo ed71_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed71_i_codigo = $ed71_i_codigo; 
       }
     }
     if(($this->ed71_i_codigo == null) || ($this->ed71_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed71_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into cursoescola(
                                       ed71_i_codigo 
                                      ,ed71_i_escola 
                                      ,ed71_i_curso 
                                      ,ed71_c_situacao 
                                      ,ed71_c_turmasala 
                       )
                values (
                                $this->ed71_i_codigo 
                               ,$this->ed71_i_escola 
                               ,$this->ed71_i_curso 
                               ,'$this->ed71_c_situacao' 
                               ,'$this->ed71_c_turmasala' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Cursos associados a Escola ($this->ed71_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Cursos associados a Escola já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Cursos associados a Escola ($this->ed71_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed71_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed71_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008280,'$this->ed71_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010049,1008280,'','".AddSlashes(pg_result($resaco,0,'ed71_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010049,1008281,'','".AddSlashes(pg_result($resaco,0,'ed71_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010049,1008282,'','".AddSlashes(pg_result($resaco,0,'ed71_i_curso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010049,1008284,'','".AddSlashes(pg_result($resaco,0,'ed71_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010049,11285,'','".AddSlashes(pg_result($resaco,0,'ed71_c_turmasala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed71_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update cursoescola set ";
     $virgula = "";
     if(trim($this->ed71_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed71_i_codigo"])){ 
       $sql  .= $virgula." ed71_i_codigo = $this->ed71_i_codigo ";
       $virgula = ",";
       if(trim($this->ed71_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed71_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed71_i_escola)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed71_i_escola"])){ 
       $sql  .= $virgula." ed71_i_escola = $this->ed71_i_escola ";
       $virgula = ",";
       if(trim($this->ed71_i_escola) == null ){ 
         $this->erro_sql = " Campo Escola nao Informado.";
         $this->erro_campo = "ed71_i_escola";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed71_i_curso)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed71_i_curso"])){ 
       $sql  .= $virgula." ed71_i_curso = $this->ed71_i_curso ";
       $virgula = ",";
       if(trim($this->ed71_i_curso) == null ){ 
         $this->erro_sql = " Campo Curso nao Informado.";
         $this->erro_campo = "ed71_i_curso";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed71_c_situacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed71_c_situacao"])){
       $sql  .= $virgula." ed71_c_situacao = '$this->ed71_c_situacao' ";
       $virgula = ",";
       if(trim($this->ed71_c_situacao) == null ){ 
         $this->erro_sql = " Campo Ativo nao Informado.";
         $this->erro_campo = "ed71_c_situacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed71_c_turmasala)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed71_c_turmasala"])){ 
       $sql  .= $virgula." ed71_c_turmasala = '$this->ed71_c_turmasala' ";
       $virgula = ",";
       if(trim($this->ed71_c_turmasala) == null ){ 
         $this->erro_sql = " Campo Permitir mais de uma turma por sala nao Informado.";
         $this->erro_campo = "ed71_c_turmasala";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed71_i_codigo!=null){
       $sql .= " ed71_i_codigo = $this->ed71_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed71_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008280,'$this->ed71_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed71_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010049,1008280,'".AddSlashes(pg_result($resaco,$conresaco,'ed71_i_codigo'))."','$this->ed71_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed71_i_escola"]))
           $resac = db_query("insert into db_acount values($acount,1010049,1008281,'".AddSlashes(pg_result($resaco,$conresaco,'ed71_i_escola'))."','$this->ed71_i_escola',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed71_i_curso"]))
           $resac = db_query("insert into db_acount values($acount,1010049,1008282,'".AddSlashes(pg_result($resaco,$conresaco,'ed71_i_curso'))."','$this->ed71_i_curso',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed71_c_situacao"]))
           $resac = db_query("insert into db_acount values($acount,1010049,1008284,'".AddSlashes(pg_result($resaco,$conresaco,'ed71_c_situacao'))."','$this->ed71_c_situacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed71_c_turmasala"]))
           $resac = db_query("insert into db_acount values($acount,1010049,11285,'".AddSlashes(pg_result($resaco,$conresaco,'ed71_c_turmasala'))."','$this->ed71_c_turmasala',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cursos associados a Escola nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed71_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cursos associados a Escola nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed71_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed71_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed71_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed71_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008280,'$ed71_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010049,1008280,'','".AddSlashes(pg_result($resaco,$iresaco,'ed71_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010049,1008281,'','".AddSlashes(pg_result($resaco,$iresaco,'ed71_i_escola'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010049,1008282,'','".AddSlashes(pg_result($resaco,$iresaco,'ed71_i_curso'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010049,1008284,'','".AddSlashes(pg_result($resaco,$iresaco,'ed71_c_situacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010049,11285,'','".AddSlashes(pg_result($resaco,$iresaco,'ed71_c_turmasala'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from cursoescola
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed71_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed71_i_codigo = $ed71_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Cursos associados a Escola nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed71_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Cursos associados a Escola nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed71_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed71_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:cursoescola";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed71_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursoescola ";
     $sql .= "      inner join escola  on  escola.ed18_i_codigo = cursoescola.ed71_i_escola";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = cursoescola.ed71_i_curso";
     $sql .= "      inner join bairro  on  bairro.j13_codi = escola.ed18_i_bairro";
     $sql .= "      inner join ruas  on  ruas.j14_codigo = escola.ed18_i_rua";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = escola.ed18_i_codigo";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = cursoedu.ed29_i_ensino";
     $sql2 = "";
     if($dbwhere==""){
       if($ed71_i_codigo!=null ){
         $sql2 .= " where cursoescola.ed71_i_codigo = $ed71_i_codigo "; 
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
   function sql_query_file ( $ed71_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from cursoescola ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed71_i_codigo!=null ){
         $sql2 .= " where cursoescola.ed71_i_codigo = $ed71_i_codigo "; 
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