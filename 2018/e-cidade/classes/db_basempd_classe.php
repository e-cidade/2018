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
//CLASSE DA ENTIDADE basempd
class cl_basempd { 
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
   var $ed35_i_codigo = 0; 
   var $ed35_i_base = 0; 
   var $ed35_i_disciplina = 0; 
   var $ed35_i_qtdperiodo = 0; 
   var $ed35_i_chtotal = 0; 
   var $ed35_c_condicao = null; 
   // cria propriedade com as variaveis do arquivo 
   var $campos = "
                 ed35_i_codigo = int8 = Base 
                 ed35_i_base = int8 = Base 
                 ed35_i_disciplina = int8 = Disciplina 
                 ed35_i_qtdperiodo = int4 = Quantidade de Períodos 
                 ed35_i_chtotal = int4 = Carga Horária Total 
                 ed35_c_condicao = char(2) = Matrícula 
                 ";
   //funcao construtor da classe 
   function cl_basempd() { 
     //classes dos rotulos dos campos
     $this->rotulo = new rotulo("basempd"); 
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
       $this->ed35_i_codigo = ($this->ed35_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed35_i_codigo"]:$this->ed35_i_codigo);
       $this->ed35_i_base = ($this->ed35_i_base == ""?@$GLOBALS["HTTP_POST_VARS"]["ed35_i_base"]:$this->ed35_i_base);
       $this->ed35_i_disciplina = ($this->ed35_i_disciplina == ""?@$GLOBALS["HTTP_POST_VARS"]["ed35_i_disciplina"]:$this->ed35_i_disciplina);
       $this->ed35_i_qtdperiodo = ($this->ed35_i_qtdperiodo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed35_i_qtdperiodo"]:$this->ed35_i_qtdperiodo);
       $this->ed35_i_chtotal = ($this->ed35_i_chtotal == ""?@$GLOBALS["HTTP_POST_VARS"]["ed35_i_chtotal"]:$this->ed35_i_chtotal);
       $this->ed35_c_condicao = ($this->ed35_c_condicao == ""?@$GLOBALS["HTTP_POST_VARS"]["ed35_c_condicao"]:$this->ed35_c_condicao);
     }else{
       $this->ed35_i_codigo = ($this->ed35_i_codigo == ""?@$GLOBALS["HTTP_POST_VARS"]["ed35_i_codigo"]:$this->ed35_i_codigo);
     }
   }
   // funcao para inclusao
   function incluir ($ed35_i_codigo){ 
      $this->atualizacampos();
     if($this->ed35_i_base == null ){ 
       $this->erro_sql = " Campo Base nao Informado.";
       $this->erro_campo = "ed35_i_base";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed35_i_disciplina == null ){ 
       $this->erro_sql = " Campo Disciplina nao Informado.";
       $this->erro_campo = "ed35_i_disciplina";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($this->ed35_i_qtdperiodo == null ){ 
       $this->ed35_i_qtdperiodo = "0";
     }
     if($this->ed35_i_chtotal == null ){ 
       $this->ed35_i_chtotal = "0";
     }
     if($this->ed35_c_condicao == null ){ 
       $this->erro_sql = " Campo Matrícula nao Informado.";
       $this->erro_campo = "ed35_c_condicao";
       $this->erro_banco = "";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     if($ed35_i_codigo == "" || $ed35_i_codigo == null ){
       $result = db_query("select nextval('basempd_ed35_i_codigo_seq')"); 
       if($result==false){
         $this->erro_banco = str_replace("\n","",@pg_last_error());
         $this->erro_sql   = "Verifique o cadastro da sequencia: basempd_ed35_i_codigo_seq do campo: ed35_i_codigo"; 
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false; 
       }
       $this->ed35_i_codigo = pg_result($result,0,0); 
     }else{
       $result = db_query("select last_value from basempd_ed35_i_codigo_seq");
       if(($result != false) && (pg_result($result,0,0) < $ed35_i_codigo)){
         $this->erro_sql = " Campo ed35_i_codigo maior que último número da sequencia.";
         $this->erro_banco = "Sequencia menor que este número.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }else{
         $this->ed35_i_codigo = $ed35_i_codigo; 
       }
     }
     if(($this->ed35_i_codigo == null) || ($this->ed35_i_codigo == "") ){ 
       $this->erro_sql = " Campo ed35_i_codigo nao declarado.";
       $this->erro_banco = "Chave Primaria zerada.";
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       return false;
     }
     $sql = "insert into basempd(
                                       ed35_i_codigo 
                                      ,ed35_i_base 
                                      ,ed35_i_disciplina 
                                      ,ed35_i_qtdperiodo 
                                      ,ed35_i_chtotal 
                                      ,ed35_c_condicao 
                       )
                values (
                                $this->ed35_i_codigo 
                               ,$this->ed35_i_base 
                               ,$this->ed35_i_disciplina 
                               ,$this->ed35_i_qtdperiodo 
                               ,$this->ed35_i_chtotal 
                               ,'$this->ed35_c_condicao' 
                      )";
     $result = db_query($sql); 
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       if( strpos(strtolower($this->erro_banco),"duplicate key") != 0 ){
         $this->erro_sql   = "Disciplinas da Base Curricular por Disciplina ($this->ed35_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_banco = "Disciplinas da Base Curricular por Disciplina já Cadastrado";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }else{
         $this->erro_sql   = "Disciplinas da Base Curricular por Disciplina ($this->ed35_i_codigo) nao Incluído. Inclusao Abortada.";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       }
       $this->erro_status = "0";
       $this->numrows_incluir= 0;
       return false;
     }
     $this->erro_banco = "";
     $this->erro_sql = "Inclusao efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed35_i_codigo;
     $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
     $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
     $this->erro_status = "1";
     $this->numrows_incluir= pg_affected_rows($result);
     $resaco = $this->sql_record($this->sql_query_file($this->ed35_i_codigo));
     if(($resaco!=false)||($this->numrows!=0)){
       $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
       $acount = pg_result($resac,0,0);
       $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
       $resac = db_query("insert into db_acountkey values($acount,1008374,'$this->ed35_i_codigo','I')");
       $resac = db_query("insert into db_acount values($acount,1010062,1008374,'','".AddSlashes(pg_result($resaco,0,'ed35_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010062,1008392,'','".AddSlashes(pg_result($resaco,0,'ed35_i_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010062,1008375,'','".AddSlashes(pg_result($resaco,0,'ed35_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010062,1008376,'','".AddSlashes(pg_result($resaco,0,'ed35_i_qtdperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010062,1008377,'','".AddSlashes(pg_result($resaco,0,'ed35_i_chtotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       $resac = db_query("insert into db_acount values($acount,1010062,1008378,'','".AddSlashes(pg_result($resaco,0,'ed35_c_condicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
     }
     return true;
   } 
   // funcao para alteracao
   function alterar ($ed35_i_codigo=null) { 
      $this->atualizacampos();
     $sql = " update basempd set ";
     $virgula = "";
     if(trim($this->ed35_i_codigo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_codigo"])){ 
       $sql  .= $virgula." ed35_i_codigo = $this->ed35_i_codigo ";
       $virgula = ",";
       if(trim($this->ed35_i_codigo) == null ){ 
         $this->erro_sql = " Campo Base nao Informado.";
         $this->erro_campo = "ed35_i_codigo";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed35_i_base)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_base"])){ 
       $sql  .= $virgula." ed35_i_base = $this->ed35_i_base ";
       $virgula = ",";
       if(trim($this->ed35_i_base) == null ){ 
         $this->erro_sql = " Campo Base nao Informado.";
         $this->erro_campo = "ed35_i_base";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed35_i_disciplina)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_disciplina"])){ 
       $sql  .= $virgula." ed35_i_disciplina = $this->ed35_i_disciplina ";
       $virgula = ",";
       if(trim($this->ed35_i_disciplina) == null ){ 
         $this->erro_sql = " Campo Disciplina nao Informado.";
         $this->erro_campo = "ed35_i_disciplina";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     if(trim($this->ed35_i_qtdperiodo)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_qtdperiodo"])){ 
        if(trim($this->ed35_i_qtdperiodo)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_qtdperiodo"])){ 
           $this->ed35_i_qtdperiodo = "0" ; 
        } 
       $sql  .= $virgula." ed35_i_qtdperiodo = $this->ed35_i_qtdperiodo ";
       $virgula = ",";
     }
     if(trim($this->ed35_i_chtotal)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_chtotal"])){ 
        if(trim($this->ed35_i_chtotal)=="" && isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_chtotal"])){ 
           $this->ed35_i_chtotal = "0" ; 
        } 
       $sql  .= $virgula." ed35_i_chtotal = $this->ed35_i_chtotal ";
       $virgula = ",";
     }
     if(trim($this->ed35_c_condicao)!="" || isset($GLOBALS["HTTP_POST_VARS"]["ed35_c_condicao"])){ 
       $sql  .= $virgula." ed35_c_condicao = '$this->ed35_c_condicao' ";
       $virgula = ",";
       if(trim($this->ed35_c_condicao) == null ){ 
         $this->erro_sql = " Campo Matrícula nao Informado.";
         $this->erro_campo = "ed35_c_condicao";
         $this->erro_banco = "";
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "0";
         return false;
       }
     }
     $sql .= " where ";
     if($ed35_i_codigo!=null){
       $sql .= " ed35_i_codigo = $this->ed35_i_codigo";
     }
     $resaco = $this->sql_record($this->sql_query_file($this->ed35_i_codigo));
     if($this->numrows>0){
       for($conresaco=0;$conresaco<$this->numrows;$conresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008374,'$this->ed35_i_codigo','A')");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_codigo"]))
           $resac = db_query("insert into db_acount values($acount,1010062,1008374,'".AddSlashes(pg_result($resaco,$conresaco,'ed35_i_codigo'))."','$this->ed35_i_codigo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_base"]))
           $resac = db_query("insert into db_acount values($acount,1010062,1008392,'".AddSlashes(pg_result($resaco,$conresaco,'ed35_i_base'))."','$this->ed35_i_base',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_disciplina"]))
           $resac = db_query("insert into db_acount values($acount,1010062,1008375,'".AddSlashes(pg_result($resaco,$conresaco,'ed35_i_disciplina'))."','$this->ed35_i_disciplina',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_qtdperiodo"]))
           $resac = db_query("insert into db_acount values($acount,1010062,1008376,'".AddSlashes(pg_result($resaco,$conresaco,'ed35_i_qtdperiodo'))."','$this->ed35_i_qtdperiodo',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed35_i_chtotal"]))
           $resac = db_query("insert into db_acount values($acount,1010062,1008377,'".AddSlashes(pg_result($resaco,$conresaco,'ed35_i_chtotal'))."','$this->ed35_i_chtotal',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         if(isset($GLOBALS["HTTP_POST_VARS"]["ed35_c_condicao"]))
           $resac = db_query("insert into db_acount values($acount,1010062,1008378,'".AddSlashes(pg_result($resaco,$conresaco,'ed35_c_condicao'))."','$this->ed35_c_condicao',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $result = db_query($sql);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disciplinas da Base Curricular por Disciplina nao Alterado. Alteracao Abortada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed35_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_alterar = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disciplinas da Base Curricular por Disciplina nao foi Alterado. Alteracao Executada.\\n";
         $this->erro_sql .= "Valores : ".$this->ed35_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Alteração efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$this->ed35_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_alterar = pg_affected_rows($result);
         return true;
       } 
     } 
   } 
   // funcao para exclusao 
   function excluir ($ed35_i_codigo=null,$dbwhere=null) { 
     if($dbwhere==null || $dbwhere==""){
       $resaco = $this->sql_record($this->sql_query_file($ed35_i_codigo));
     }else{ 
       $resaco = $this->sql_record($this->sql_query_file(null,"*",null,$dbwhere));
     }
     if(($resaco!=false)||($this->numrows!=0)){
       for($iresaco=0;$iresaco<$this->numrows;$iresaco++){
         $resac = db_query("select nextval('db_acount_id_acount_seq') as acount");
         $acount = pg_result($resac,0,0);
         $resac = db_query("insert into db_acountacesso values($acount,".db_getsession("DB_acessado").")");
         $resac = db_query("insert into db_acountkey values($acount,1008374,'$ed35_i_codigo','E')");
         $resac = db_query("insert into db_acount values($acount,1010062,1008374,'','".AddSlashes(pg_result($resaco,$iresaco,'ed35_i_codigo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010062,1008392,'','".AddSlashes(pg_result($resaco,$iresaco,'ed35_i_base'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010062,1008375,'','".AddSlashes(pg_result($resaco,$iresaco,'ed35_i_disciplina'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010062,1008376,'','".AddSlashes(pg_result($resaco,$iresaco,'ed35_i_qtdperiodo'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010062,1008377,'','".AddSlashes(pg_result($resaco,$iresaco,'ed35_i_chtotal'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
         $resac = db_query("insert into db_acount values($acount,1010062,1008378,'','".AddSlashes(pg_result($resaco,$iresaco,'ed35_c_condicao'))."',".db_getsession('DB_datausu').",".db_getsession('DB_id_usuario').")");
       }
     }
     $sql = " delete from basempd
                    where ";
     $sql2 = "";
     if($dbwhere==null || $dbwhere ==""){
        if($ed35_i_codigo != ""){
          if($sql2!=""){
            $sql2 .= " and ";
          }
          $sql2 .= " ed35_i_codigo = $ed35_i_codigo ";
        }
     }else{
       $sql2 = $dbwhere;
     }
     $result = db_query($sql.$sql2);
     if($result==false){ 
       $this->erro_banco = str_replace("\n","",@pg_last_error());
       $this->erro_sql   = "Disciplinas da Base Curricular por Disciplina nao Excluído. Exclusão Abortada.\\n";
       $this->erro_sql .= "Valores : ".$ed35_i_codigo;
       $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
       $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
       $this->erro_status = "0";
       $this->numrows_excluir = 0;
       return false;
     }else{
       if(pg_affected_rows($result)==0){
         $this->erro_banco = "";
         $this->erro_sql = "Disciplinas da Base Curricular por Disciplina nao Encontrado. Exclusão não Efetuada.\\n";
         $this->erro_sql .= "Valores : ".$ed35_i_codigo;
         $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
         $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
         $this->erro_status = "1";
         $this->numrows_excluir = 0;
         return true;
       }else{
         $this->erro_banco = "";
         $this->erro_sql = "Exclusão efetuada com Sucesso\\n";
         $this->erro_sql .= "Valores : ".$ed35_i_codigo;
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
        $this->erro_sql   = "Record Vazio na Tabela:basempd";
        $this->erro_msg   = "Usuário: \\n\\n ".$this->erro_sql." \\n\\n";
        $this->erro_msg   .=  str_replace('"',"",str_replace("'","",  "Administrador: \\n\\n ".$this->erro_banco." \\n"));
        $this->erro_status = "0";
        return false;
      }
     return $result;
   }
   function sql_query ( $ed35_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from basempd ";
     $sql .= "      inner join disciplina  on  disciplina.ed12_i_codigo = basempd.ed35_i_disciplina";
     $sql .= "      inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina";
     $sql .= "      inner join base  on  base.ed31_i_codigo = basempd.ed35_i_base";
     $sql .= "      inner join ensino  on  ensino.ed10_i_codigo = disciplina.ed12_i_ensino";
     $sql .= "      inner join cursoedu  on  cursoedu.ed29_i_codigo = base.ed31_i_curso";
     $sql2 = "";
     if($dbwhere==""){
       if($ed35_i_codigo!=null ){
         $sql2 .= " where basempd.ed35_i_codigo = $ed35_i_codigo "; 
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
   function sql_query_file ( $ed35_i_codigo=null,$campos="*",$ordem=null,$dbwhere=""){ 
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
     $sql .= " from basempd ";
     $sql2 = "";
     if($dbwhere==""){
       if($ed35_i_codigo!=null ){
         $sql2 .= " where basempd.ed35_i_codigo = $ed35_i_codigo "; 
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