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
//CLASSE DA ENTIDADE avaliacao_matric
class cl_avaliacao_matric { 
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
   var $ed29_i_codigo = 0; 
   var $ed29_i_avaliacao = 0; 
   var $ed29_i_matricula = 0; 
   var $ed29_f_nota = 0; 
   var $ed29_l_presente = 'f'; 
   var $ed29_c_descr = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed29_i_codigo = int8 = Código 
                 ed29_i_avaliacao = int8 = Avaliação 
                 ed29_i_matricula = int8 = Matrícula 
                 ed29_f_nota = float4 = Nota 
                 ed29_l_presente = bool = Presente 
                 ed29_c_descr = char(50) = Descrição 
                 ";
   //funcao construtor da classe 
   function cl_avaliacao_matric() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("avaliacao_matric"); 
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
       $this->ed29_i_codigo = ($this->ed29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_i_codigo"]:$this->ed29_i_codigo);
       $this->ed29_i_avaliacao = ($this->ed29_i_avaliacao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_i_avaliacao"]:$this->ed29_i_avaliacao);
       $this->ed29_i_matricula = ($this->ed29_i_matricula == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_i_matricula"]:$this->ed29_i_matricula);
       $this->ed29_f_nota = ($this->ed29_f_nota == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_f_nota"]:$this->ed29_f_nota);
       $this->ed29_l_presente = ($this->ed29_l_presente == "f"?@$GLOBALS["HTTP_POST_VARS"]["ed29_l_presente"]:$this->ed29_l_presente);
       $this->ed29_c_descr = ($this->ed29_c_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_c_descr"]:$this->ed29_c_descr);
     }else{
       $this->ed29_i_codigo = ($this->ed29_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed29_i_codigo"]:$this->ed29_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed29_i_codigo){ 
      $this->atualizacampos();
     if($this->ed29_i_avaliacao == null ){ 
       $this->erro_sql = " Campo Avaliação nao Informado.";
       $this->erro_campo = "ed29_i_avaliacao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed29_i_matricula == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed29_i_matricula";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed29_f_nota == null ){ 
       $this->erro_sql = " Campo Nota nao Informado.";
       $this->erro_campo = "ed29_f_nota";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed29_l_presente == null ){ 
       $this->erro_sql = " Campo Presente nao Informado.";
       $this->erro_campo = "ed29_l_presente";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed29_c_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "ed29_c_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->ed29_i_codigo = $ed29_i_codigo; 
     if(($this->ed29_i_codigo == null) || ($this->ed29_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed29_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into avaliacao_matric(
                                       ed29_i_codigo 
                                      ,ed29_i_avaliacao 
                                      ,ed29_i_matricula 
                                      ,ed29_f_nota 
                                      ,ed29_l_presente 
                                      ,ed29_c_descr 
                       )
                values (
                                $this->ed29_i_codigo 
                               ,$this->ed29_i_avaliacao 
                               ,$this->ed29_i_matricula 
                               ,$this->ed29_f_nota 
                               ,'$this->ed29_l_presente' 
                               ,'$this->ed29_c_descr' 
                      )";
     $result = @pg_exec($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Avaliação para Matrícula ($this->ed29_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Avaliação para Matrícula já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Avaliação para Matrícula ($this->ed29_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed29_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed29_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = pg_query("insert into db_acountkey values($acount,1006189,'$this->ed29_i_codigo','I')");
       $resac = pg_query("insert into db_acount values($acount,1006023,1006189,'','".AddSlashes(pg_result($resaco,0,'ed29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006023,1006190,'','".AddSlashes(pg_result($resaco,0,'ed29_i_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006023,1006191,'','".AddSlashes(pg_result($resaco,0,'ed29_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006023,1006192,'','".AddSlashes(pg_result($resaco,0,'ed29_f_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006023,1006193,'','".AddSlashes(pg_result($resaco,0,'ed29_l_presente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = pg_query("insert into db_acount values($acount,1006023,1006194,'','".AddSlashes(pg_result($resaco,0,'ed29_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed29_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update avaliacao_matric set ";
     $virgula = "";
     if(trim($this->ed29_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_codigo"])){ 
       $sql  .= $virgula." ed29_i_codigo = $this->ed29_i_codigo ";
       $virgula = ",";
       if(trim($this->ed29_i_codigo) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "ed29_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed29_i_avaliacao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_avaliacao"])){ 
       $sql  .= $virgula." ed29_i_avaliacao = $this->ed29_i_avaliacao ";
       $virgula = ",";
       if(trim($this->ed29_i_avaliacao) == null ){ 
         $this->erro_sql = " Campo Avaliação nao Informado.";
         $this->erro_campo = "ed29_i_avaliacao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed29_i_matricula)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_matricula"])){ 
       $sql  .= $virgula." ed29_i_matricula = $this->ed29_i_matricula ";
       $virgula = ",";
       if(trim($this->ed29_i_matricula) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed29_i_matricula";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed29_f_nota)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_f_nota"])){ 
       $sql  .= $virgula." ed29_f_nota = $this->ed29_f_nota ";
       $virgula = ",";
       if(trim($this->ed29_f_nota) == null ){ 
         $this->erro_sql = " Campo Nota nao Informado.";
         $this->erro_campo = "ed29_f_nota";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed29_l_presente)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_l_presente"])){ 
       $sql  .= $virgula." ed29_l_presente = '$this->ed29_l_presente' ";
       $virgula = ",";
       if(trim($this->ed29_l_presente) == null ){ 
         $this->erro_sql = " Campo Presente nao Informado.";
         $this->erro_campo = "ed29_l_presente";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed29_c_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed29_c_descr"])){ 
       $sql  .= $virgula." ed29_c_descr = '$this->ed29_c_descr' ";
       $virgula = ",";
       if(trim($this->ed29_c_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "ed29_c_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed29_i_codigo!=null){
       $sql .= " ed29_i_codigo = $this->ed29_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed29_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006189,'$this->ed29_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_codigo"]))
           $resac = pg_query("insert into db_acount values($acount,1006023,1006189,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_i_codigo'))."','$this->ed29_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_avaliacao"]))
           $resac = pg_query("insert into db_acount values($acount,1006023,1006190,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_i_avaliacao'))."','$this->ed29_i_avaliacao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_i_matricula"]))
           $resac = pg_query("insert into db_acount values($acount,1006023,1006191,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_i_matricula'))."','$this->ed29_i_matricula',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_f_nota"]))
           $resac = pg_query("insert into db_acount values($acount,1006023,1006192,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_f_nota'))."','$this->ed29_f_nota',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_l_presente"]))
           $resac = pg_query("insert into db_acount values($acount,1006023,1006193,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_l_presente'))."','$this->ed29_l_presente',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed29_c_descr"]))
           $resac = pg_query("insert into db_acount values($acount,1006023,1006194,'".AddSlashes(pg_result($resaco,$conresaco,'ed29_c_descr'))."','$this->ed29_c_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = @pg_exec($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação para Matrícula nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed29_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação para Matrícula nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed29_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed29_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = pg_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = pg_query("insert into db_acountkey values($acount,1006189,'$ed29_i_codigo','E')");
         $resac = pg_query("insert into db_acount values($acount,1006023,1006189,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006023,1006190,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_i_avaliacao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006023,1006191,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_i_matricula'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006023,1006192,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_f_nota'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006023,1006193,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_l_presente'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = pg_query("insert into db_acount values($acount,1006023,1006194,'','".AddSlashes(pg_result($resaco,$iresaco,'ed29_c_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from avaliacao_matric
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed29_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed29_i_codigo = $ed29_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = @pg_exec($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Avaliação para Matrícula nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed29_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Avaliação para Matrícula nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed29_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed29_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:avaliacao_matric";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $ed29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacao_matric ";
     $sql .= "      inner join matriculas  on  matriculas.ed09_i_codigo = avaliacao_matric.ed29_i_matricula";
     $sql .= "      inner join avaliacoes  on  avaliacoes.ed13_i_codigo = avaliacao_matric.ed29_i_avaliacao";
     $sql .= "      inner join series  on  series.ed03_i_codigo = matriculas.ed09_i_serie";
     $sql .= "      inner join alunos  on  alunos.ed07_i_codigo = matriculas.ed09_i_aluno";
     $sql .= "      inner join matriculas  on  matriculas.ed09_i_codigo = avaliacoes.ed13_i_periodo";
     $sql .= "      inner join disciplinas  on  disciplinas.ed27_i_codigo = avaliacoes.ed13_i_disciplinas";
     $sql2 = "";
     if($dbwhere==""){
       if($ed29_i_codigo!=null ){
         $sql2 .= " where avaliacao_matric.ed29_i_codigo = $ed29_i_codigo "; 
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
   function sql_query_file ( $ed29_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from avaliacao_matric ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed29_i_codigo!=null ){
         $sql2 .= " where avaliacao_matric.ed29_i_codigo = $ed29_i_codigo "; 
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