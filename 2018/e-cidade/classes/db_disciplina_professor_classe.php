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
//CLASSE DA ENTIDADE disciplina_professor
class cl_disciplina_professor { 
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
   var $ed12_i_codigo = 0; 
   var $ed12_i_professores = 0; 
   var $ed12_i_disciplina = 0; 
   var $ed12_f_ch = 0; 
   var $ed12_f_frequencia = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed12_i_codigo = int8 = Código 
                 ed12_i_professores = int8 = CGM do Professor 
                 ed12_i_disciplina = int8 = Disciplina 
                 ed12_f_ch = float8 = Carga Horária 
                 ed12_f_frequencia = float8 = Frequência 
                 ";
   //funcao construtor da classe 
   function cl_disciplina_professor() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("disciplina_professor"); 
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
       $this->ed12_i_codigo = ($this->ed12_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed12_i_codigo"]:$this->ed12_i_codigo);
       $this->ed12_i_professores = ($this->ed12_i_professores == ""?@$GLOBALS["HTTP_POST_VARS"]["ed12_i_professores"]:$this->ed12_i_professores);
       $this->ed12_i_disciplina = ($this->ed12_i_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed12_i_disciplina"]:$this->ed12_i_disciplina);
       $this->ed12_f_ch = ($this->ed12_f_ch == ""?@$GLOBALS["HTTP_POST_VARS"]["ed12_f_ch"]:$this->ed12_f_ch);
       $this->ed12_f_frequencia = ($this->ed12_f_frequencia == ""?@$GLOBALS["HTTP_POST_VARS"]["ed12_f_frequencia"]:$this->ed12_f_frequencia);
     }else{
       $this->ed12_i_codigo = ($this->ed12_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed12_i_codigo"]:$this->ed12_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed12_i_codigo){ 
      $this->atualizacampos();
     if($this->ed12_i_professores == null ){ 
       $this->erro_sql = " Campo CGM do Professor nao Informado.";
       $this->erro_campo = "ed12_i_professores";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed12_i_disciplina == null ){ 
       $this->erro_sql = " Campo Disciplina nao Informado.";
       $this->erro_campo = "ed12_i_disciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed12_f_ch == null ){ 
       $this->erro_sql = " Campo Carga Horária nao Informado.";
       $this->erro_campo = "ed12_f_ch";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed12_f_frequencia == null ){ 
       $this->erro_sql = " Campo Frequência nao Informado.";
       $this->erro_campo = "ed12_f_frequencia";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed12_i_codigo == "" || $ed12_i_codigo == null ){
       $result = @pg_query("select nextval('disciplina_professor_ed12_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: disciplina_professor_ed12_i_codigo_seq do campo: ed12_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed12_i_codigo = pg_result($result,0,0); 
     }else{
       $result = @pg_query("select last_value from disciplina_professor_ed12_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed12_i_codigo)){
         $this->erro_sql = " Campo ed12_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed12_i_codigo = $ed12_i_codigo; 
       }
     }
     if(($this->ed12_i_codigo == null) || ($this->ed12_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed12_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into disciplina_professor(
                                       ed12_i_codigo 
                                      ,ed12_i_professores 
                                      ,ed12_i_disciplina 
                                      ,ed12_f_ch 
                                      ,ed12_f_frequencia 
                       )
                values (
                                $this->ed12_i_codigo 
                               ,$this->ed12_i_professores 
                               ,$this->ed12_i_disciplina 
                               ,$this->ed12_f_ch 
                               ,$this->ed12_f_frequencia 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Disciplinas do Professor ($this->ed12_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Disciplinas do Professor já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Disciplinas do Professor ($this->ed12_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed12_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed12_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006010,'$this->ed12_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006019,1006010,'','".AddSlashes(pg_result($resaco,0,'ed12_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006019,1006046,'','".AddSlashes(pg_result($resaco,0,'ed12_i_professores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006019,1006012,'','".AddSlashes(pg_result($resaco,0,'ed12_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006019,1006013,'','".AddSlashes(pg_result($resaco,0,'ed12_f_ch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006019,1006011,'','".AddSlashes(pg_result($resaco,0,'ed12_f_frequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed12_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update disciplina_professor set ";
     $virgula = "";
     if(trim($this->ed12_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_codigo"])){ 
       $sql  .= $virgula." ed12_i_codigo = $this->ed12_i_codigo ";
       $virgula = ",";
       if(trim($this->ed12_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed12_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed12_i_professores)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_professores"])){ 
       $sql  .= $virgula." ed12_i_professores = $this->ed12_i_professores ";
       $virgula = ",";
       if(trim($this->ed12_i_professores) == null ){ 
         $this->erro_sql = " Campo CGM do Professor nao Informado.";
         $this->erro_campo = "ed12_i_professores";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed12_i_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_disciplina"])){ 
       $sql  .= $virgula." ed12_i_disciplina = $this->ed12_i_disciplina ";
       $virgula = ",";
       if(trim($this->ed12_i_disciplina) == null ){ 
         $this->erro_sql = " Campo Disciplina nao Informado.";
         $this->erro_campo = "ed12_i_disciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed12_f_ch)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed12_f_ch"])){ 
       $sql  .= $virgula." ed12_f_ch = $this->ed12_f_ch ";
       $virgula = ",";
       if(trim($this->ed12_f_ch) == null ){ 
         $this->erro_sql = " Campo Carga Horária nao Informado.";
         $this->erro_campo = "ed12_f_ch";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed12_f_frequencia)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed12_f_frequencia"])){ 
       $sql  .= $virgula." ed12_f_frequencia = $this->ed12_f_frequencia ";
       $virgula = ",";
       if(trim($this->ed12_f_frequencia) == null ){ 
         $this->erro_sql = " Campo Frequência nao Informado.";
         $this->erro_campo = "ed12_f_frequencia";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed12_i_codigo!=null){
       $sql .= " ed12_i_codigo = $this->ed12_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed12_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006010,'$this->ed12_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006019,1006010,'".AddSlashes(pg_result($resaco,$conresaco,'ed12_i_codigo'))."','$this->ed12_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_professores"]))
           $resac = pg_query("insert into db_acount values($acount,1006019,1006046,'".AddSlashes(pg_result($resaco,$conresaco,'ed12_i_professores'))."','$this->ed12_i_professores',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed12_i_disciplina"]))
           $resac = pg_query("insert into db_acount values($acount,1006019,1006012,'".AddSlashes(pg_result($resaco,$conresaco,'ed12_i_disciplina'))."','$this->ed12_i_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed12_f_ch"]))
           $resac = pg_query("insert into db_acount values($acount,1006019,1006013,'".AddSlashes(pg_result($resaco,$conresaco,'ed12_f_ch'))."','$this->ed12_f_ch',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed12_f_frequencia"]))
           $resac = pg_query("insert into db_acount values($acount,1006019,1006011,'".AddSlashes(pg_result($resaco,$conresaco,'ed12_f_frequencia'))."','$this->ed12_f_frequencia',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disciplinas do Professor nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed12_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disciplinas do Professor nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed12_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed12_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed12_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed12_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006010,'$ed12_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006019,1006010,'','".AddSlashes(pg_result($resaco,$iresaco,'ed12_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006019,1006046,'','".AddSlashes(pg_result($resaco,$iresaco,'ed12_i_professores'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006019,1006012,'','".AddSlashes(pg_result($resaco,$iresaco,'ed12_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006019,1006013,'','".AddSlashes(pg_result($resaco,$iresaco,'ed12_f_ch'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006019,1006011,'','".AddSlashes(pg_result($resaco,$iresaco,'ed12_f_frequencia'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from disciplina_professor
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed12_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed12_i_codigo = $ed12_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disciplinas do Professor nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed12_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disciplinas do Professor nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed12_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed12_i_codigo;
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
     $result = @pg_query($sql);
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
        $this->erro_sql   = "Record Vazio na Tabela:disciplina_professor";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from disciplina_professor ";
     //$sql .= "      inner join professores  on  professores.ed01_i_codigo = disciplina_professor.ed12_i_professores";
     $sql .= "      inner join disciplinas  on  disciplinas.ed27_i_codigo = disciplina_professor.ed12_i_disciplina";
     //$sql .= "      inner join cgm  on  cgm.z01_numcgm = professores.ed01_i_codigo";
     $sql .= "      inner join professores  on  professores.ed01_i_codigo = disciplina_professor.ed12_i_professores";
     //$sql .= "      inner join disciplina_professor  as a on   a.ed12_i_codigo = disciplina_professor.ed12_i_disciplina";
     $sql2 = "";
     if($dbwhere==""){
       if($ed12_i_codigo!=null ){
         $sql2 .= " where disciplina_professor.ed12_i_codigo = $ed12_i_codigo "; 
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
   function sql_query_file ( $ed12_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from disciplina_professor ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed12_i_codigo!=null ){
         $sql2 .= " where disciplina_professor.ed12_i_codigo = $ed12_i_codigo "; 
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