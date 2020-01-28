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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_estruturanivel
class cl_db_estruturanivel { 
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
   var $db78_codestrut = 0; 
   var $db78_nivel = 0; 
   var $db78_descr = null; 
   var $db78_tamanho = 0; 
   var $db78_inicio = 0; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db78_codestrut = int8 = Código 
                 db78_nivel = int4 = Nível 
                 db78_descr = varchar(40) = Descrição 
                 db78_tamanho = int4 = Tamanho 
                 db78_inicio = int4 = Inicio 
                 ";
   //funcao construtor da classe 
   function cl_db_estruturanivel() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_estruturanivel"); 
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
       $this->db78_codestrut = ($this->db78_codestrut == ""?@$GLOBALS["HTTP_POST_VARS"]["db78_codestrut"]:$this->db78_codestrut);
       $this->db78_nivel = ($this->db78_nivel == ""?@$GLOBALS["HTTP_POST_VARS"]["db78_nivel"]:$this->db78_nivel);
       $this->db78_descr = ($this->db78_descr == ""?@$GLOBALS["HTTP_POST_VARS"]["db78_descr"]:$this->db78_descr);
       $this->db78_tamanho = ($this->db78_tamanho == ""?@$GLOBALS["HTTP_POST_VARS"]["db78_tamanho"]:$this->db78_tamanho);
       $this->db78_inicio = ($this->db78_inicio == ""?@$GLOBALS["HTTP_POST_VARS"]["db78_inicio"]:$this->db78_inicio);
     }else{
       $this->db78_codestrut = ($this->db78_codestrut == ""?@$GLOBALS["HTTP_POST_VARS"]["db78_codestrut"]:$this->db78_codestrut);
       $this->db78_nivel = ($this->db78_nivel == ""?@$GLOBALS["HTTP_POST_VARS"]["db78_nivel"]:$this->db78_nivel);
     }
   }
   // funcao para inclusao
   function incluir ($db78_codestrut,$db78_nivel){ 
      $this->atualizacampos();
     if($this->db78_descr == null ){ 
       $this->erro_sql = " Campo Descrição nao Informado.";
       $this->erro_campo = "db78_descr";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db78_tamanho == null ){ 
       $this->erro_sql = " Campo Tamanho nao Informado.";
       $this->erro_campo = "db78_tamanho";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db78_inicio == null ){ 
       $this->erro_sql = " Campo Inicio nao Informado.";
       $this->erro_campo = "db78_inicio";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
       $this->db78_codestrut = $db78_codestrut; 
       $this->db78_nivel = $db78_nivel; 
     if(($this->db78_codestrut == null) || ($this->db78_codestrut == "") ){ 
       $this->erro_sql = " Campo db78_codestrut nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if(($this->db78_nivel == null) || ($this->db78_nivel == "") ){ 
       $this->erro_sql = " Campo db78_nivel nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_estruturanivel(
                                       db78_codestrut 
                                      ,db78_nivel 
                                      ,db78_descr 
                                      ,db78_tamanho 
                                      ,db78_inicio 
                       )
                values (
                                $this->db78_codestrut 
                               ,$this->db78_nivel 
                               ,'$this->db78_descr' 
                               ,$this->db78_tamanho 
                               ,$this->db78_inicio 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Niveis das estruturas ($this->db78_codestrut."-".$this->db78_nivel) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Niveis das estruturas já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Niveis das estruturas ($this->db78_codestrut."-".$this->db78_nivel) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db78_codestrut."-".$this->db78_nivel;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db78_codestrut,$this->db78_nivel));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,5694,'$this->db78_codestrut','I')");
       $resac = db_query("insert into db_acountkey values($acount,5695,'$this->db78_nivel','I')");
       $resac = db_query("insert into db_acount values($acount,899,5694,'','".AddSlashes(pg_result($resaco,0,'db78_codestrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,899,5695,'','".AddSlashes(pg_result($resaco,0,'db78_nivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,899,5696,'','".AddSlashes(pg_result($resaco,0,'db78_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,899,5697,'','".AddSlashes(pg_result($resaco,0,'db78_tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,899,5698,'','".AddSlashes(pg_result($resaco,0,'db78_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db78_codestrut=null,$db78_nivel=null) { 
      $this->atualizacampos();
     $sql = " update db_estruturanivel set ";
     $virgula = "";
     if(trim($this->db78_codestrut)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db78_codestrut"])){ 
       $sql  .= $virgula." db78_codestrut = $this->db78_codestrut ";
       $virgula = ",";
       if(trim($this->db78_codestrut) == null ){ 
         $this->erro_sql = " Campo Código nao Informado.";
         $this->erro_campo = "db78_codestrut";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db78_nivel)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db78_nivel"])){ 
       $sql  .= $virgula." db78_nivel = $this->db78_nivel ";
       $virgula = ",";
       if(trim($this->db78_nivel) == null ){ 
         $this->erro_sql = " Campo Nível nao Informado.";
         $this->erro_campo = "db78_nivel";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db78_descr)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db78_descr"])){ 
       $sql  .= $virgula." db78_descr = '$this->db78_descr' ";
       $virgula = ",";
       if(trim($this->db78_descr) == null ){ 
         $this->erro_sql = " Campo Descrição nao Informado.";
         $this->erro_campo = "db78_descr";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db78_tamanho)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db78_tamanho"])){ 
       $sql  .= $virgula." db78_tamanho = $this->db78_tamanho ";
       $virgula = ",";
       if(trim($this->db78_tamanho) == null ){ 
         $this->erro_sql = " Campo Tamanho nao Informado.";
         $this->erro_campo = "db78_tamanho";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db78_inicio)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db78_inicio"])){ 
       $sql  .= $virgula." db78_inicio = $this->db78_inicio ";
       $virgula = ",";
       if(trim($this->db78_inicio) == null ){ 
         $this->erro_sql = " Campo Inicio nao Informado.";
         $this->erro_campo = "db78_inicio";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db78_codestrut!=null){
       $sql .= " db78_codestrut = $this->db78_codestrut";
     }
     if($db78_nivel!=null){
       $sql .= " and  db78_nivel = $this->db78_nivel";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db78_codestrut,$this->db78_nivel));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5694,'$this->db78_codestrut','A')");
         $resac = db_query("insert into db_acountkey values($acount,5695,'$this->db78_nivel','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db78_codestrut"]))
           $resac = db_query("insert into db_acount values($acount,899,5694,'".AddSlashes(pg_result($resaco,$conresaco,'db78_codestrut'))."','$this->db78_codestrut',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db78_nivel"]))
           $resac = db_query("insert into db_acount values($acount,899,5695,'".AddSlashes(pg_result($resaco,$conresaco,'db78_nivel'))."','$this->db78_nivel',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db78_descr"]))
           $resac = db_query("insert into db_acount values($acount,899,5696,'".AddSlashes(pg_result($resaco,$conresaco,'db78_descr'))."','$this->db78_descr',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db78_tamanho"]))
           $resac = db_query("insert into db_acount values($acount,899,5697,'".AddSlashes(pg_result($resaco,$conresaco,'db78_tamanho'))."','$this->db78_tamanho',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db78_inicio"]))
           $resac = db_query("insert into db_acount values($acount,899,5698,'".AddSlashes(pg_result($resaco,$conresaco,'db78_inicio'))."','$this->db78_inicio',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Niveis das estruturas nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db78_codestrut."-".$this->db78_nivel;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Niveis das estruturas nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db78_codestrut."-".$this->db78_nivel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db78_codestrut."-".$this->db78_nivel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db78_codestrut=null,$db78_nivel=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db78_codestrut,$db78_nivel));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,5694,'$db78_codestrut','E')");
         $resac = db_query("insert into db_acountkey values($acount,5695,'$db78_nivel','E')");
         $resac = db_query("insert into db_acount values($acount,899,5694,'','".AddSlashes(pg_result($resaco,$iresaco,'db78_codestrut'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,899,5695,'','".AddSlashes(pg_result($resaco,$iresaco,'db78_nivel'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,899,5696,'','".AddSlashes(pg_result($resaco,$iresaco,'db78_descr'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,899,5697,'','".AddSlashes(pg_result($resaco,$iresaco,'db78_tamanho'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,899,5698,'','".AddSlashes(pg_result($resaco,$iresaco,'db78_inicio'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_estruturanivel
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db78_codestrut != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db78_codestrut = $db78_codestrut ";
        }
        if($db78_nivel != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db78_nivel = $db78_nivel ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Niveis das estruturas nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db78_codestrut."-".$db78_nivel;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Niveis das estruturas nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db78_codestrut."-".$db78_nivel;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db78_codestrut."-".$db78_nivel;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_estruturanivel";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $db78_codestrut=null,$db78_nivel=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_estruturanivel ";
     $sql .= "      inner join db_estrutura  on  db_estrutura.db77_codestrut = db_estruturanivel.db78_codestrut";
     $sql2 = "";
     if($dbwhere==""){
       if($db78_codestrut!=null ){
         $sql2 .= " where db_estruturanivel.db78_codestrut = $db78_codestrut "; 
       } 
       if($db78_nivel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_estruturanivel.db78_nivel = $db78_nivel "; 
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
   function sql_query_file ( $db78_codestrut=null,$db78_nivel=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_estruturanivel ";
     $sql2 = "";
     if($dbwhere==""){
       if($db78_codestrut!=null ){
         $sql2 .= " where db_estruturanivel.db78_codestrut = $db78_codestrut "; 
       } 
       if($db78_nivel!=null ){
         if($sql2!=""){
            $sql2 .= " and ";
         }else{
            $sql2 .= " where ";
         } 
         $sql2 .= " db_estruturanivel.db78_nivel = $db78_nivel "; 
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