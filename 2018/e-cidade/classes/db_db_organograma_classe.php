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

//MODULO: configuracoes
//CLASSE DA ENTIDADE db_organograma
class cl_db_organograma { 
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
   var $db122_sequencial = 0; 
   var $db122_depart = 0; 
   var $db122_estruturavalor = 0; 
   var $db122_descricao = null; 
   var $db122_associado = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 db122_sequencial = int4 = Sequencial 
                 db122_depart = int4 = Código Departamento 
                 db122_estruturavalor = int4 = Código Estrutura 
                 db122_descricao = varchar(100) = Descrição Organograma 
                 db122_associado = char(1) = Associado 
                 ";
   //funcao construtor da classe 
   function cl_db_organograma() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("db_organograma"); 
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
       $this->db122_sequencial = ($this->db122_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db122_sequencial"]:$this->db122_sequencial);
       $this->db122_depart = ($this->db122_depart == ""?@$GLOBALS["HTTP_POST_VARS"]["db122_depart"]:$this->db122_depart);
       $this->db122_estruturavalor = ($this->db122_estruturavalor == ""?@$GLOBALS["HTTP_POST_VARS"]["db122_estruturavalor"]:$this->db122_estruturavalor);
       $this->db122_descricao = ($this->db122_descricao == ""?@$GLOBALS["HTTP_POST_VARS"]["db122_descricao"]:$this->db122_descricao);
       $this->db122_associado = ($this->db122_associado == ""?@$GLOBALS["HTTP_POST_VARS"]["db122_associado"]:$this->db122_associado);
     }else{
       $this->db122_sequencial = ($this->db122_sequencial == ""?@$GLOBALS["HTTP_POST_VARS"]["db122_sequencial"]:$this->db122_sequencial);
     }
   }
   // funcao para inclusao
   function incluir ($db122_sequencial){ 
      $this->atualizacampos();
     if($this->db122_depart == null ){ 
       $this->erro_sql = " Campo Código Departamento nao Informado.";
       $this->erro_campo = "db122_depart";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db122_estruturavalor == null ){ 
       $this->erro_sql = " Campo Código Estrutura nao Informado.";
       $this->erro_campo = "db122_estruturavalor";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db122_descricao == null ){ 
       $this->erro_sql = " Campo Descrição Organograma nao Informado.";
       $this->erro_campo = "db122_descricao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->db122_associado == null ){ 
       $this->erro_sql = " Campo Associado nao Informado.";
       $this->erro_campo = "db122_associado";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($db122_sequencial == "" || $db122_sequencial == null ){
       $result = db_query("select nextval('db_organograma_db122_sequencial_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: db_organograma_db122_sequencial_seq do campo: db122_sequencial"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->db122_sequencial = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from db_organograma_db122_sequencial_seq");
       if(($result != false) && (pg_result($result,0,0) < $db122_sequencial)){
         $this->erro_sql = " Campo db122_sequencial maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->db122_sequencial = $db122_sequencial; 
       }
     }
     if(($this->db122_sequencial == null) || ($this->db122_sequencial == "") ){ 
       $this->erro_sql = " Campo db122_sequencial nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into db_organograma(
                                       db122_sequencial 
                                      ,db122_depart 
                                      ,db122_estruturavalor 
                                      ,db122_descricao 
                                      ,db122_associado 
                       )
                values (
                                $this->db122_sequencial 
                               ,$this->db122_depart 
                               ,$this->db122_estruturavalor 
                               ,'$this->db122_descricao' 
                               ,'$this->db122_associado' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Organograma ($this->db122_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Organograma já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Organograma ($this->db122_sequencial) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db122_sequencial;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->db122_sequencial));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,18026,'$this->db122_sequencial','I')");
       $resac = db_query("insert into db_acount values($acount,3186,18026,'','".AddSlashes(pg_result($resaco,0,'db122_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3186,18027,'','".AddSlashes(pg_result($resaco,0,'db122_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3186,18028,'','".AddSlashes(pg_result($resaco,0,'db122_estruturavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3186,18029,'','".AddSlashes(pg_result($resaco,0,'db122_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,3186,18080,'','".AddSlashes(pg_result($resaco,0,'db122_associado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($db122_sequencial=null) { 
      $this->atualizacampos();
     $sql = " update db_organograma set ";
     $virgula = "";
     if(trim($this->db122_sequencial)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db122_sequencial"])){ 
       $sql  .= $virgula." db122_sequencial = $this->db122_sequencial ";
       $virgula = ",";
       if(trim($this->db122_sequencial) == null ){ 
         $this->erro_sql = " Campo Sequencial nao Informado.";
         $this->erro_campo = "db122_sequencial";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db122_depart)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db122_depart"])){ 
       $sql  .= $virgula." db122_depart = $this->db122_depart ";
       $virgula = ",";
       if(trim($this->db122_depart) == null ){ 
         $this->erro_sql = " Campo Código Departamento nao Informado.";
         $this->erro_campo = "db122_depart";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db122_estruturavalor)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db122_estruturavalor"])){ 
       $sql  .= $virgula." db122_estruturavalor = $this->db122_estruturavalor ";
       $virgula = ",";
       if(trim($this->db122_estruturavalor) == null ){ 
         $this->erro_sql = " Campo Código Estrutura nao Informado.";
         $this->erro_campo = "db122_estruturavalor";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db122_descricao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db122_descricao"])){ 
       $sql  .= $virgula." db122_descricao = '$this->db122_descricao' ";
       $virgula = ",";
       if(trim($this->db122_descricao) == null ){ 
         $this->erro_sql = " Campo Descrição Organograma nao Informado.";
         $this->erro_campo = "db122_descricao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->db122_associado)!="" || isset($GLOBALS["HTTP_POST_VARS"]["db122_associado"])){ 
       $sql  .= $virgula." db122_associado = '$this->db122_associado' ";
       $virgula = ",";
       if(trim($this->db122_associado) == null ){ 
         $this->erro_sql = " Campo Associado nao Informado.";
         $this->erro_campo = "db122_associado";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($db122_sequencial!=null){
       $sql .= " db122_sequencial = $this->db122_sequencial";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->db122_sequencial));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18026,'$this->db122_sequencial','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db122_sequencial"]) || $this->db122_sequencial != "")
           $resac = db_query("insert into db_acount values($acount,3186,18026,'".AddSlashes(pg_result($resaco,$conresaco,'db122_sequencial'))."','$this->db122_sequencial',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db122_depart"]) || $this->db122_depart != "")
           $resac = db_query("insert into db_acount values($acount,3186,18027,'".AddSlashes(pg_result($resaco,$conresaco,'db122_depart'))."','$this->db122_depart',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db122_estruturavalor"]) || $this->db122_estruturavalor != "")
           $resac = db_query("insert into db_acount values($acount,3186,18028,'".AddSlashes(pg_result($resaco,$conresaco,'db122_estruturavalor'))."','$this->db122_estruturavalor',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db122_descricao"]) || $this->db122_descricao != "")
           $resac = db_query("insert into db_acount values($acount,3186,18029,'".AddSlashes(pg_result($resaco,$conresaco,'db122_descricao'))."','$this->db122_descricao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["db122_associado"]) || $this->db122_associado != "")
           $resac = db_query("insert into db_acount values($acount,3186,18080,'".AddSlashes(pg_result($resaco,$conresaco,'db122_associado'))."','$this->db122_associado',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Organograma nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->db122_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Organograma nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->db122_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->db122_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($db122_sequencial=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($db122_sequencial));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,18026,'$db122_sequencial','E')");
         $resac = db_query("insert into db_acount values($acount,3186,18026,'','".AddSlashes(pg_result($resaco,$iresaco,'db122_sequencial'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3186,18027,'','".AddSlashes(pg_result($resaco,$iresaco,'db122_depart'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3186,18028,'','".AddSlashes(pg_result($resaco,$iresaco,'db122_estruturavalor'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3186,18029,'','".AddSlashes(pg_result($resaco,$iresaco,'db122_descricao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,3186,18080,'','".AddSlashes(pg_result($resaco,$iresaco,'db122_associado'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from db_organograma
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($db122_sequencial != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " db122_sequencial = $db122_sequencial ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Organograma nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$db122_sequencial;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Organograma nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$db122_sequencial;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$db122_sequencial;
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
        $this->erro_sql   = "Record Vazio na Tabela:db_organograma";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   // funcao do sql 
   function sql_query ( $db122_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_organograma ";
     $sql .= "      inner join db_depart  on  db_depart.coddepto = db_organograma.db122_depart";
     $sql .= "      inner join db_estruturavalor  on  db_estruturavalor.db121_sequencial = db_organograma.db122_estruturavalor";
     $sql .= "      inner join db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      inner join db_usuarios  on  db_usuarios.id_usuario = db_depart.id_usuarioresp";
     $sql .= "      inner join db_estrutura  as a on   a.db77_codestrut = db_estruturavalor.db121_db_estrutura";
     $sql2 = "";
     if($dbwhere==""){
       if($db122_sequencial!=null ){
         $sql2 .= " where db_organograma.db122_sequencial = $db122_sequencial "; 
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
   function sql_query_file ( $db122_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_organograma ";
     $sql2 = "";
     if($dbwhere==""){
       if($db122_sequencial!=null ){
         $sql2 .= " where db_organograma.db122_sequencial = $db122_sequencial "; 
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
   function sql_query_busca ( $db122_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_organograma ";
     $sql .= "      LEFT JOIN db_depart  on  db_depart.coddepto = db_organograma.db122_depart";
     $sql .= "      LEFT JOIN db_estruturavalor  on  db_estruturavalor.db121_sequencial = db_organograma.db122_estruturavalor";
     $sql .= "      LEFT JOIN db_config  on  db_config.codigo = db_depart.instit";
     $sql .= "      LEFT JOIN db_usuarios  on  db_usuarios.id_usuario = db_depart.id_usuarioresp";
     $sql .= "      INNER JOIN db_estrutura  as a on   a.db77_codestrut = db_estruturavalor.db121_db_estrutura";
     $sql2 = "";
     if($dbwhere==""){
       if($db122_sequencial!=null ){
         $sql2 .= " where db_organograma.db122_sequencial = $db122_sequencial "; 
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
   function sql_query_conta ( $db122_sequencial=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from db_organograma ";
     $sql .= "      left  join db_estruturavalor          on db121_sequencial         = db122_estruturavalor";
     $sql .= "      left  join db_estrutura               on db77_codestrut           = db121_db_estrutura";
     $sql .= "      left  join db_depart                  on db122_depart             = coddepto";
     $sql2 = "";
     if($dbwhere==""){
       if($db122_sequencial!=null ){
         $sql2 .= " where db_organograma.db122_sequencial = $db122_sequencial "; 
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